/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : API functions                             *
**********************************************************/

#ifndef SF_DEBUG_WINDOWS
#include "vxWorks.h"
#include "semLib.h"
#endif
#include <stdio.h>
#include <stdlib.h>
#include <errno.h>
#include <fcntl.h>
#include <string.h>
#include <sys/stat.h>
#include "sf_fat.h"
#include "sf_faterr.h"
#include "sf_api.h"

/* ioctl() parameter */
#ifndef __INCioLibh
#define FIOSYNC			21		/* sync to disk */
#define FIOLABELGET		33      /* get volume label */
#define FIOLABELSET		34      /* set volume label */
#define FIONFREE		30      /* get free byte count on device */
#define FIONFREE64		(FIONFREE | 6400)
#endif

/* semaphore for exclusive API call */
#ifndef SF_DEBUG_WINDOWS
static SEM_ID semMutex;
#endif

/* Enter Critical Region */
static void EnterCriticalRegion(void)
{
#ifndef SF_DEBUG_WINDOWS
	semTake(semMutex, WAIT_FOREVER);
#endif
}

/* Leave Critical Region */
static void ExitCriticalRegion(void)
{
#ifndef SF_DEBUG_WINDOWS
	semGive(semMutex);
#endif
}

/* get error number from error code */
static int SF_errno(RESULT errCd)
{
	static struct SF_ERRNO {
		RESULT errCd;
		int errNo;
	} errTbl[] = {
		{SF_EC_InvalidFunctionCode, EINVAL},	/* Invalid Function Code */
		{SF_EC_FileNotFound, ENOENT},			/* File is not Found     */
		{SF_EC_PathNotFound, ENOENT},			/* Path is not Found     */
		{SF_EC_TooManyOpen, EMFILE},			/* Open too many File    */
		{SF_EC_AccessDenied, EACCES},			/* File access is Denied */
		{SF_EC_InvalidHandle, EBADF},			/* Invalid File Handle   */
		{SF_EC_InvalidAccessCode, EINVAL},		/* Invalid Access Code   */
		{SF_EC_InvalidData, EINVAL},			/* Invalid Data          */
		{SF_EC_InvalidDrive, ENOENT},			/* Invalid Drive(Device) */
		{SF_EC_NotSameDevice, EINVAL},			/* Not Same Device       */
		{SF_EC_NotDOSdisk, ENODEV},				/* Not DOS Disk          */
		{SF_EC_WriteFault, EIO},				/* Write is Failed       */
		{SF_EC_ReadFault, EIO},					/* Read is Failed        */
		{SF_EC_GeneralFailure, ENXIO}			/* General Fault         */
	};
	int i;

	for (i = 0; i < sizeof(errTbl) / sizeof(struct SF_ERRNO); i++) {
		if (errCd == errTbl[i].errCd)
			return errTbl[i].errNo;
	}
	return -1;

}

/* High Level Standard I/O Function */
#define SF_OPENMODE_READ	1
#define SF_OPENMODE_WRITE	2
#define SF_OPENMODE_APPEND	4
#define SF_OPENMODE_PLUS	8
FILE *SF_fopen(const char *filename, const char *mode)
{
	int oflag = 0;
	int modeFlag = 0;
	int fh;
	int *fp;

    /* check open mode */
	while (*mode != '\0') {
		switch (*mode++) {
		case 'r':
			modeFlag |= SF_OPENMODE_READ;
			break;
		case 'w':
			modeFlag |= SF_OPENMODE_WRITE;
			break;
		case 'a':
			modeFlag |= SF_OPENMODE_APPEND;
			break;
		case '+':
			modeFlag |= SF_OPENMODE_PLUS;
			break;
		default:
			return NULL;
		}
	}

    /* set open mode flag */
	switch (modeFlag) {
	case SF_OPENMODE_READ:
		oflag = O_RDONLY;
		break;
	case SF_OPENMODE_WRITE:
		oflag = O_WRONLY | O_CREAT | O_TRUNC;
		break;
	case SF_OPENMODE_APPEND:
		oflag = O_WRONLY | O_APPEND | O_CREAT;
		break;
	case SF_OPENMODE_READ | SF_OPENMODE_PLUS:
		oflag = O_RDWR;
		break;
	case SF_OPENMODE_WRITE | SF_OPENMODE_PLUS:
		oflag = O_RDWR | O_CREAT | O_TRUNC;
		break;
	case SF_OPENMODE_APPEND | SF_OPENMODE_PLUS:
		oflag = O_RDWR | O_APPEND | O_CREAT;
		break;
	}

	/* file open */
	if ((fh = SF_open(filename, oflag, 0)) == -1)
		return NULL;

	/* get file pointer */
	fp = malloc(sizeof(int));
	if (fp == NULL)
		return NULL;

    /* save file handle */
	*fp = fh;

    /* return file handle */
	return (FILE*)fp;
}

size_t SF_fread(void *buffer, size_t size, size_t count, FILE *stream)
{
	int result;

	if (stream == NULL)
		return 0;

	if (size == 0 || count == 0)
		return 0;

	if ((result = SF_read(*(int *)stream, buffer, size * count)) == -1)
		return 0;

	return result / size;
}

size_t SF_fwrite(const void *buffer, size_t size, size_t count, FILE *stream)
{
	int result;

	if (stream == NULL)
		return 0;

	if (size == 0 || count == 0)
		return 0;

	if ((result = SF_write(*(int *)stream, buffer, size * count)) == -1)
		return 0;

	return result / size;
}

int SF_fseek(FILE *stream, long offset, int origin)
{
	int result;

	if (stream == NULL)
		return 0;

	if ((result = SF_lseek(*(int *)stream, offset, origin)) == -1)
		return -1;

	return 0;
}

int SF_fclose(FILE *stream)
{
	int result;

	if (stream == NULL)
		return EOF;

	result = SF_close(*(int *)stream);

	free(stream);

	return result;
}

int SF_feof(FILE *stream)
{
	if (stream == NULL)
		return 0;

	return SF_eof(*(int *)stream);
}

static int SF_rename_s(const char *oldname, const char *newname)
{
	RESULT	result;

	if ((result = _SF_Rename((PSZ)oldname, (PSZ)newname)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_rename(const char *oldname, const char *newname)
{
	int result;

	EnterCriticalRegion();
	result = SF_rename_s(oldname, newname);
	ExitCriticalRegion();

	return result;
}

static int SF_remove_s(const char *path)
{
	RESULT	result;

	if ((result = _SF_Remove((PSZ)path)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_remove(const char *path)
{
	int result;

	EnterCriticalRegion();
	result = SF_remove_s(path);
	ExitCriticalRegion();

	return result;
}

static int SF_mkdir_s(const char *dirname)
{
	RESULT	result;

	if ((result = _SF_MkDir((PSZ)dirname)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_mkdir(const char *dirname)
{
	int result;

	EnterCriticalRegion();
	result = SF_mkdir_s(dirname);
	ExitCriticalRegion();

	return result;
}

static int SF_rmdir_s(const char *dirname)
{
	RESULT	result;

	if ((result = _SF_RmDir((PSZ)dirname)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_rmdir(const char *dirname)
{
	int result;

	EnterCriticalRegion();
	result = SF_rmdir_s(dirname);
	ExitCriticalRegion();

	return result;
}

int SF_fflush(FILE *stream)
{
	return 0;
}

int SF_fileno(FILE *stream)
{
	if (stream == NULL)
		return -1;

	return *(int *)stream;
}

static int SF_copy_s(const char *srcfilename, const char *dstfilename)
{
	RESULT result;
	INT32 srcFileID, dstFileID;
	DWORD rlen, wlen;
	char buf[8192];

	if ((result = _SF_Open((PSZ)srcfilename, O_RDONLY, &srcFileID)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	if ((result = _SF_Create((PSZ)dstfilename, SF_AT_NOR, &dstFileID)) != 0) {
		errno = SF_errno(result);
		_SF_Close(srcFileID);
		return -1;
	}

	for (;;) {
		if ((result = _SF_Read(srcFileID, buf, sizeof(buf), &rlen)) != 0) {
			errno = SF_errno(result);
			_SF_Close(srcFileID);
			_SF_Close(dstFileID);
			return -1;
		}

		if (rlen == 0)
			break;

		if ((result = _SF_Write(dstFileID, buf, rlen, &wlen)) != 0) {
			errno = SF_errno(result);
			_SF_Close(srcFileID);
			_SF_Close(dstFileID);
			return -1;
		}
	}

	_SF_Close(srcFileID);
	_SF_Close(dstFileID);

	return 0;
}

int SF_copy(const char *srcfilename, const char *dstfilename)
{
	int result;

	EnterCriticalRegion();
	result = SF_copy_s(srcfilename, dstfilename);
	ExitCriticalRegion();

	return result;
}

static int SF_stat_s(const char *path, struct stat *buffer)
{
	RESULT	result;
	SF_stat_t	stat;

	if ((result = _SF_Stat((PSZ)path, &stat)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	memset(buffer, 0, sizeof(struct stat));
#ifndef SF_DEBUG_WINDOWS
	buffer->st_attrib = stat.st_attr;
#endif
	buffer->st_mtime = stat.st_time;
	buffer->st_atime = buffer->st_mtime;
	buffer->st_size = stat.st_size;

	if (stat.st_attr & SF_AT_DIR) {
		buffer->st_mode |= 0x4000;
		buffer->st_mode |= 0x0040;
	} else
		buffer->st_mode |= 0x8000;

	buffer->st_mode |= 0x0100;

	if (!(stat.st_attr & SF_AT_ROL))
		buffer->st_mode |= 0x0080;

	return 0;
}

int SF_stat(const char *path, struct stat *buffer)
{
	int result;

	EnterCriticalRegion();
	result = SF_stat_s(path, buffer);
	ExitCriticalRegion();

	return result;
}

static int SF_attrib_s(const char *path, const char *attr)
{
	RESULT	result;
	WORD wAttr;
	char f = 0;

	if ((result = _SF_GetFattr((PSZ)path, &wAttr)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	while (*attr != '\0') {
		switch (*attr) {
		case '+':
		case '-':
			f = *attr;
			break;
		case 'R':
			if (f == '+')
				wAttr |= SF_AT_ROL;
			else if (f == '-')
				wAttr &= ~SF_AT_ROL;
			f = 0;
			break;
		case 'H':
			if (f == '+')
				wAttr |= SF_AT_HID;
			else if (f == '-')
				wAttr &= ~SF_AT_HID;
			f = 0;
			break;
		case 'A':
			if (f == '+')
				wAttr |= SF_AT_ARC;
			else if (f == '-')
				wAttr &= ~SF_AT_ARC;
			f = 0;
			break;
		}

		attr++;
	}

	if ((result = _SF_SetFattr((PSZ)path, wAttr)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_attrib(const char *path, const char *attr)
{
	int result;

	EnterCriticalRegion();
	result = SF_attrib_s(path, attr);
	ExitCriticalRegion();

	return result;
}

static DIR *SF_opendir_s(const char *dirname)
{
	RESULT	result;
	INT32	fileID = 0;
	DIR *dir = NULL;

	if ((result = _SF_Open((PSZ)dirname, SF_OPEN_DIR_RD, &fileID)) != 0) {
		errno = SF_errno(result);
		return NULL;
	}

	dir = malloc(sizeof(DIR));
	memset(dir, 0, sizeof(DIR));

	dir->dd_fd = (int)fileID;

	return dir;
}

DIR *SF_opendir(const char *dirname)
{
	DIR *result;

	EnterCriticalRegion();
	result = SF_opendir_s(dirname);
	ExitCriticalRegion();

	return result;
}

static struct dirent *SF_readdir_s(DIR *dir)
{
	RESULT	result;
	SF_dirEnt_t dirBuf;
	DWORD ww;
	struct dirent *dirent;

	if (dir == NULL)
		return NULL;

	if ((result = _SF_ReadDir((INT32)dir->dd_fd, &dirBuf, 1, &ww)) != 0) {
		errno = SF_errno(result);
		return NULL;
	}

	if (ww == 1) {
		dirent = &dir->dd_dirent;

		strcpy(dirent->d_name, dirBuf.lfname);
	} else
		dirent = NULL;

	return dirent;
}

struct dirent *SF_readdir(DIR *dir)
{
	struct dirent *result;

	EnterCriticalRegion();
	result = SF_readdir_s(dir);
	ExitCriticalRegion();

	return result;
}

static int SF_closedir_s(DIR *dir)
{
	RESULT	result;
	INT32	fileID;

	if (dir == NULL)
		return -1;

	fileID = (INT32)dir->dd_fd;
	free(dir);

	if ((result = _SF_Close(fileID)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_closedir(DIR *dir)
{
	int result;

	EnterCriticalRegion();
	result = SF_closedir_s(dir);
	ExitCriticalRegion();

	return result;
}

/* Low Level Standard I/O Function */
static int SF_open_s(const char *filename, int oflag, int pmode)
{
	RESULT	result;
	INT32	fileID = 0;
	DWORD	mode;
	DWORD	rwPtr;

	switch (oflag & 0x03) {
	case O_RDONLY:
		mode = SF_OPEN_RD;
		break;
	case O_WRONLY:
		mode = SF_OPEN_WR;
		break;
	default:
		mode = SF_OPEN_RW;
		break;
	}

	if ((result = _SF_Open((PSZ)filename, mode, &fileID)) != 0) {
		if ((oflag & O_CREAT) && result == SF_EC_FileNotFound) {
			if ((result = _SF_Create((PSZ)filename, SF_AT_ARC, &fileID)) != 0) {
				errno = SF_errno(result);
				return -1;
			}
		} else {
			errno = SF_errno(result);
			return -1;
		}
	}

	if (oflag & O_TRUNC) {
		if ((result = _SF_Truncate(fileID)) != 0) {
			errno = SF_errno(result);
			return -1;
		}
	}

	if (oflag & O_APPEND) {
		if ((result = _SF_Seek(fileID, SF_SEEK_END, 0, &rwPtr)) != 0) {
			errno = SF_errno(result);
			return -1;
		}
	}

	return fileID;
}

int SF_open(const char *filename, int oflag, int pmode)
{
	int result;

	EnterCriticalRegion();
	result = SF_open_s(filename, oflag, pmode);
	ExitCriticalRegion();

	return result;
}

static int SF_read_s(int handle, void *buffer, unsigned int count)
{
	RESULT	result;
	DWORD	len;

	if ((result = _SF_Read((INT32)handle, (PVOID)buffer, (DWORD)count, &len)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return (int)len;
}

int SF_read(int handle, void *buffer, unsigned int count)
{
	int result;

	EnterCriticalRegion();
	result = SF_read_s(handle, buffer, count);
	ExitCriticalRegion();

	return result;
}

static int SF_write_s(int handle, const void *buffer, unsigned int count)
{
	RESULT	result;
	DWORD	len;

	if ((result = _SF_Write((INT32)handle, (PVOID)buffer, (DWORD)count, &len)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return (int)len;
}

int SF_write(int handle, const void *buffer, unsigned int count)
{
	int result;

	EnterCriticalRegion();
	result = SF_write_s(handle, buffer, count);
	ExitCriticalRegion();

	return result;
}

static long SF_lseek_s(int handle, long offset, int origin)
{
	RESULT	result;
	DWORD	pos;

	if ((result = _SF_Seek((INT32)handle, (SF_BYTE)origin, (INT32)offset, &pos)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return (int)pos;
}

long SF_lseek(int handle, long offset, int origin)
{
	long result;

	EnterCriticalRegion();
	result = SF_lseek_s(handle, offset, origin);
	ExitCriticalRegion();

	return result;
}

static int SF_close_s(int handle)
{
	RESULT	result;

	if ((result = _SF_Close((INT32)handle)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_close(int handle)
{
	int result;

	EnterCriticalRegion();
	result = SF_close_s(handle);
	ExitCriticalRegion();

	return result;
}

static int SF_creat_s(const char *filename, int pmode)
{
	RESULT	result;
	INT32	fileID;

	if ((result = _SF_Create((PSZ)filename, (WORD)pmode, &fileID)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return (int)fileID;
}

int SF_creat(const char *filename, int pmode)
{
	int result;

	EnterCriticalRegion();
	result = SF_creat_s(filename, pmode);
	ExitCriticalRegion();

	return result;
}

static int SF_eof_s(int handle)
{
	RESULT	result;
	BOOL	eof;

	if ((result = _SF_Eof((INT32)handle, &eof)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return (int)eof;
}

int SF_eof(int handle)
{
	int result;

	EnterCriticalRegion();
	result = SF_eof_s(handle);
	ExitCriticalRegion();

	return result;
}

int SF_init(void)
{
	int rt;
#ifndef SF_DEBUG_WINDOWS
	if (semMutex == 0)
		semMutex = semBCreate (SEM_Q_PRIORITY, SEM_FULL);
#endif

	EnterCriticalRegion();
	rt = _SF_Init();
	ExitCriticalRegion();

	return rt;
}

static int SF_mount_s(const char *devname, const void *devinfo)
{
	RESULT result;

	if ((result = _SF_Mount((PSZ)devname, (PVOID)devinfo)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_mount(const char *devname, const void *devinfo)
{
	int result;

	EnterCriticalRegion();
	result =  SF_mount_s(devname, devinfo);
	ExitCriticalRegion();

	return result;
}

static int SF_format_s(const void *devinfo)
{
	RESULT result;

	if ((result = _SF_Format((PVOID)devinfo)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_format(const void *devinfo)
{
	int result;

	EnterCriticalRegion();
	result =  SF_format_s(devinfo);
	ExitCriticalRegion();

	return result;
}

static int SF_sync_s(const char *devname)
{
	RESULT	result;

	if ((result = _SF_Sync((PSZ)devname)) != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return 0;
}

int SF_sync(const char *devname)
{
	int result;

	EnterCriticalRegion();
	result = SF_sync_s(devname);
	ExitCriticalRegion();

	return result;
}

int SF_ioctl_s(int handle, int function, int arg)
{
	RESULT result;
	DWORD dwSectSize, dwSectPerClu, dwFreeClu, dwTotalClu;

	switch (function) {
	case FIOSYNC:
		result = _SF_Sync2((INT32)handle);
		break;
	case FIOLABELGET:
		result = _SF_ReadVolumeLabel((INT32)handle, (PSZ)arg);
		break;
	case FIOLABELSET:
		result = _SF_WriteVolumeLabel((INT32)handle, (PSZ)arg);
		break;
	case FIONFREE64:
		result = _SF_GetFreeClu((INT32)handle, &dwSectSize, &dwSectPerClu, &dwFreeClu, &dwTotalClu);
		if (result == 0) {
			*(INT64 *)arg = (INT64)dwSectSize * dwSectPerClu * dwFreeClu;
			result = 0;
		}
		break;
	default:
		result = 0;
		break;
	}

	if (result != 0) {
		errno = SF_errno(result);
		return -1;
	}

	return result;
}


int SF_ioctl(int handle, int function, int arg)
{
	int result;

	EnterCriticalRegion();
	result = SF_ioctl_s(handle, function, arg);
	ExitCriticalRegion();

	return result;
}

int	SF_GetFatCount(void)
{
	return	_SF_GetFatCount();
}

void SF_ResetFatCount(void)
{
	_SF_ResetFatCount();
}