/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : Test main program                         *
**********************************************************/

#ifndef SF_DEBUG_WINDOWS
#include "vxWorks.h"
#include "scsiLib.h"
#include "ioLib.h"
#endif
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <fcntl.h>
#include <errno.h>
#include <ctype.h>
#include <sys/stat.h>
#include "sf_api.h"
#include "sf_define.h"
#include "sf_sub.h"

extern	void	SF_Final(void);
extern BOOL _DSKIO_ReadSector(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData);
extern BOOL _DSKIO_WriteSector(PVOID devInfo, DWORD blockNo, DWORD blockNum, PVOID pData);

/* ioctl() parameter */
#ifndef __INCioLibh
#define FIOSYNC			21		/* sync to disk */
#define FIOLABELGET		33      /* get volume label */
#define FIOLABELSET		34      /* set volume label */
#define FIONFREE		30      /* get free byte count on device */
#define FIONFREE64		(FIONFREE | 6400)
#endif

#ifndef SF_DEBUG_WINDOWS
extern	SCSI_BLK_DEV *pSbd;
#endif

/* Device Name and Pointer of Device Information For Test */
char devName[32];
static	void *devInfo;
char filename[256];
char filename2[256];

extern int main3(void);
extern void dspDir(char *ph, char ac);

static unsigned char buf[1024 * 1024];

void get_string(char *msg, char *str, char *def)
{
	for (;;) {
		printf("%s", msg);
		gets(str);
		if (str[0] == '\0') {
			if (def != NULL) {
				strcpy(str, def);
				break;
			}
		} else
			break;
	}
}

void get_number(char *msg, int *n, char *def)
{
	*n = 0;
	get_string(msg, buf, def);
	sscanf(buf, "%d", n);
}

static void get_hex(char *msg, int *n, char *def)
{
	*n = 0;
	get_string(msg, buf, def);
	sscanf(buf, "%x", n);
}

static void disp_data(char *buf, int len)
{
	int c = -1;
	int count = 0;
	int i;

	for (i = 0; i < len; i++) {
		if (buf[i] != c) {
			if (count != 0)
				printf("<%c:%d>", c, count);

			c = buf[i];
			count = 0;
		}
		count++;
	}

	if (count != 0)
		printf("<%c:%d>", c, count);

	printf("\n");
}

static void disp_file_time(long t)
{
	struct tm *tmp;

	tmp = localtime(&t);
	printf("%04d/%02d/%02d %02d:%02d:%02d", tmp->tm_year + 1900, tmp->tm_mon + 1, tmp->tm_mday, tmp->tm_hour, tmp->tm_min, tmp->tm_sec);
}

static void disp_errno()
{
	static char *errmsg[] = {
		"No Error",	/* 0 */
		"EPERM:Not owner",	/* 1 */
		"ENOENT:No such file or directory",	/* 2 */
		"ESRCH:No such process",	/* 3 */
		"EINTR:Interrupted system call",	/* 4 */
		"EIO:I/O error",	/* 5 */
		"ENXIO:No such device or address",	/* 6 */
		"E2BIG:Arg list too long",	/* 7 */
		"ENOEXEC:Exec format error",	/* 8 */
		"EBADF:Bad file number",	/* 9 */
		"ECHILD:No children",	/* 10 */
		"EAGAIN:No more processes",	/* 11 */
		"ENOMEM:Not enough core",	/* 12 */
		"EACCES:Permission denied",	/* 13 */
		"EFAULT:Bad address",	/* 14 */
		"ENOTEMPTY:Directory not empty",	/* 15 */
		"EBUSY:Mount device busy",	/* 16 */
		"EEXIST:File exists",	/* 17 */
		"EXDEV:Cross-device link",	/* 18 */
		"ENODEV:No such device",	/* 19 */
		"ENOTDIR:Not a directory",	/* 20 */
		"EISDIR:Is a directory",	/* 21 */
		"EINVAL:Invalid argument",	/* 22 */
		"ENFILE:File table overflow",	/* 23 */
		"EMFILE:Too many open files",	/* 24 */
		"ENOTTY:Not a typewriter",	/* 25 */
		"ENAMETOOLONG:File name too long",	/* 26 */
		"EFBIG:File too large",	/* 27 */
		"ENOSPC:No space left on device",	/* 28 */
		"ESPIPE:Illegal seek",	/* 29 */
		"EROFS:Read-only file system",	/* 30 */
		"EMLINK:Too many links",	/* 31 */
		"EPIPE:Broken pipe",	/* 32 */
		"EDEADLK:Resource deadlock avoided",	/* 33 */
		"ENOLCK:No locks available",	/* 34 */
		"ENOTSUP:Unsupported value",	/* 35 */
		"EMSGSIZE:Message size",	/* 36 */
	};

	if (errno != 0) {
		if (errno >= 1 && errno <= 36)
			printf("[errno] = %d (%s)\n", errno, errmsg[errno]);
		else
			printf("[errno] = %d\n", errno);
	}
}

static int ioctl_test(int function, int arg)
{
	int handle;
	int result;

	if ((handle = SF_open(devName, O_RDWR, 0)) == -1)
		return -1;

	result = SF_ioctl(handle, function, arg);

	SF_close(handle);

	return result;
}

static void ls_command(char *dirname)
{
	DIR *dir;
	struct dirent *dirent;
	struct stat st;
	int count = 0;
	char pathname[256];
	char s[10];

	if ((dir = SF_opendir(dirname)) == NULL) {
		disp_errno();
		return;
	}

	for (;;) {
		if ((dirent = SF_readdir(dir)) == NULL)
			break;

		sprintf(pathname, "%s/%s", dirname, dirent->d_name);
		if (SF_stat(pathname, &st) == -1) {
			disp_errno();
			break;
		}

		printf("%3d : ", ++count);
		strcpy(s, "----");
		if (st.st_mode & 0x4000)
			s[0] = 'd';
		if (st.st_mode & 0x0100)
			s[1] = 'r';
		if (st.st_mode & 0x0080)
			s[2] = 'w';
		if (st.st_mode & 0x0040)
			s[3] = 'x';
		printf(" %s ", s);
		disp_file_time(st.st_mtime);
		printf(" %10lu[byte]  ", st.st_size);
		printf("%s\n", dirent->d_name);
	}

	if (SF_closedir(dir) == -1)
		disp_errno();

	printf("\n");
}

static void dump_command(DWORD sectorNo)
{
	int offset;
	int i, j;
	char str[16];

	memset(buf, 0, sizeof(buf));
	offset = 0;
	for (;;) {
		if (offset == 0) {
			if (!_DSKIO_ReadSector(devInfo, sectorNo, 1, buf))
				break;
		}

		printf("Sector=%d\n", sectorNo);
		printf("OFFSET  0  1  2  3  4  5  6  7  8  9  A  B  C  D  E  F  0123456789ABCDEF\n");
		printf("------------------------------------------------------------------------\n");

		for (i = 0; i < 16; i++) {
			printf(" %04X ", offset);
			for (j = 0; j < 16; j++)
				printf(" %02X", (SF_BYTE)buf[offset + j]);

			printf("  ");

			for (j = 0; j < 16; j++)
				printf("%c", isprint(buf[offset + j]) ? buf[offset + j] : '.');

			printf("\n");

			offset += 16;
		}

		get_string("next ([Y]/N) ? ", str, "Y");
		if (str[0] != 'Y' && str[0] != 'y')
			break;

		if (offset == SECTOR_SIZE) {
			sectorNo++;
			offset = 0;
		}
	}
}

static	void DspFatKey(void)
{
	int	i;

	memset(buf, 0, sizeof(buf));
	for (i = 0; i < FAT_HISTORY_NUM; i++) {
		if (!_DSKIO_ReadSector(devInfo, 71 + i*8, 1, buf))
			break;
		printf("   %02X %02X %02X %02X\n"
			, (SF_BYTE)buf[8188], (SF_BYTE)buf[8189], (SF_BYTE)buf[8190], (SF_BYTE)buf[8191]);
		if (!_DSKIO_ReadSector(devInfo, 135 + i*8, 1, buf))
			break;
		printf("   %02X %02X %02X %02X\n"
			, (SF_BYTE)buf[8188], (SF_BYTE)buf[8189], (SF_BYTE)buf[8190], (SF_BYTE)buf[8191]);
	}
}

static	void	WrtFat1(void)
{
	memset(buf, 0xff, sizeof(buf));
	_DSKIO_WriteSector(devInfo, 64, 1, buf);
}
static	void	WrtFat2(void)
{
	memset(buf, 0xff, sizeof(buf));
	_DSKIO_WriteSector(devInfo, 128, 1,buf);
}

static	void	FatTest1(void)
{
	char *p;
	int	loop_cnt, i;

	if ((p = malloc(SECTOR_SIZE * FAT_SECTOR_NUM)) == NULL) {
		printf("memory allocate error!\n");
		return;
	}
	if (! _DSKIO_ReadSector(devInfo, FAT1_BLOCK_OFFSET, FAT_SECTOR_NUM, p)) {
		printf("FAT READ ERROR!\n");
		free(p);
		return;
	}
	get_number("loop cnt(n) ", &loop_cnt, "0");

	for (i = 0; i < loop_cnt; i++) {
		printf("-------[FAT WRITE COUNT(%d/%d)]----\n", i+1, loop_cnt);
		if (! _DSKIO_WriteSector(devInfo, FAT1_BLOCK_OFFSET, FAT_SECTOR_NUM, p))
			break;
		printf("FAT#1 WRITE OK!\n");

		if (! _DSKIO_WriteSector(devInfo, FAT2_BLOCK_OFFSET, FAT_SECTOR_NUM, p))
			break;
		printf("FAT#2 WRITE OK!\n");
		printf("-------[FAT WRITE OK]----\n");
	}
	free(p);
}

static	void	DataTest1(void)
{
	char *p;
	int	loop_cnt, sectorNo, sectorNm, i;

	if ((p = malloc(SECTOR_SIZE * SECTOR_PER_CLUSTER)) == NULL) {
		printf("memory allocate error!\n");
		return;
	}
	get_number("loop cnt(n) ", &loop_cnt, "0");
	get_number("sector No(n) ", &sectorNo, "256");
	get_number("sector Nm(n) ", &sectorNm, "1");

	if (! _DSKIO_ReadSector(devInfo, sectorNo, sectorNm, p)) {
		printf("DATA READ ERROR!\n");
		free(p);
		return;
	}

	printf("[SECTOR NO=%d NUMBER=%d]\n", sectorNo, sectorNm);
	for (i = 0; i < loop_cnt; i++) {
		printf("-------[WRITE COUNT(%d/%d)]----\n", i+1, loop_cnt);
		if (! _DSKIO_WriteSector(devInfo, sectorNo, sectorNm, p))
			break;
		printf("-------[DATA WRITE OK]----\n");
	}
	free(p);
}

static int RmDirRecursive(const char *dirname)
{
	DIR     *pDir;
	struct dirent *pEnt;
	struct stat   fStat;
	char    *fname;

	printf("Remove Directory : %s\n", dirname);

	/* open directory */
	pDir = SF_opendir(dirname);
	if ( pDir == NULL ) {
		printf("Open Directory %s Failed\n", dirname);
		disp_errno();
		return -1;
	}

	/* memory allocate */
	fname = malloc(NAME_MAX + 1);
	if ( fname == NULL ) {
		printf("Memory Allocate Error\n");
		SF_closedir(pDir);
		return -2;
	}

	while( TRUE ) {
		/* read dir */
		pEnt = SF_readdir(pDir);
		if ( pEnt == NULL ) {
			_PrintDebugMessage(4, "	Read Dir loop break\n");
			break;
		}

		if ( strcmp(pEnt->d_name, "." ) == 0 )
			continue;
		if ( strcmp(pEnt->d_name, ".." ) == 0 )
			continue;

		sprintf(fname, "%s/%s", dirname, pEnt->d_name);
		if ( SF_stat(fname, &fStat) == 0 ) {
/*@@@@@@@@@@@@@*/
#if 0
/*@@@@@@@@@@@@@*/
			if (fStat.st_mode & 0x4000) {
				RmDirRecursive(fname);
			}
			else {
/*@@@@@@@@@@@@@*/
#endif
/*@@@@@@@@@@@@@*/
				printf("	Remove File : %s\n", pEnt->d_name);
				if ( SF_remove(fname) != 0 ) {
					printf("	Remove File %s Failed\n", pEnt->d_name);
				}
/*@@@@@@@@@@@@@*/
#if 0
/*@@@@@@@@@@@@@*/
			}
/*@@@@@@@@@@@@@*/
#endif
/*@@@@@@@@@@@@@*/
		}
		else {
			printf("	Get File Status Failed : %s\n", fname);
			disp_errno();
		}
		_PrintDebugMessage(4, "	Next Entry\n");
	}

	_PrintDebugMessage(4, "	SF_closedir(DIR=0x%x)\n", pDir);
	/* close directory */
	if ( SF_closedir(pDir) == -1 ) {
		printf("Close Directory %s Failed\n", dirname);
		disp_errno();
		_PrintDebugMessage(4, "	Free fname\n");
		/* allocate memory free */
		free(fname);
		return -3;
	}

	_PrintDebugMessage(4, "	SF_rmdir\n");
	/* remove directory */
	if ( SF_rmdir(dirname) == -1 ) {
		printf("Remove Directory %s Failed\n", dirname);
		disp_errno();
		_PrintDebugMessage(4, "	Free fname\n");
		/* allocate memory free */
		free(fname);
		return -4;
	}

	_PrintDebugMessage(4, "	Free fname\n");
	/* allocate memory free */
	free(fname);

	return 0;
}

static void RmDir(char *dirname)
{
	DIR *dir;
	struct dirent *dirent;
	struct stat st;
	int count = 0;
	char pathname[256];
	char s[10];

	_PrintDebugMessage(4, "	SF_opendir\n");
	if ((dir = SF_opendir(dirname)) == NULL) {
		disp_errno();
		return;
	}

	memset(s, 0, sizeof(s));
	for (;;) {
		_PrintDebugMessage(4, "	SF_readdir\n");
		if ((dirent = SF_readdir(dir)) == NULL)
			break;

		_PrintDebugMessage(4, "	SF_stat\n");
		sprintf(pathname, "%s/%s", dirname, dirent->d_name);
		if (SF_stat(pathname, &st) == -1) {
			disp_errno();
			break;
		}

		if ( strcmp(dirent->d_name, ".") == 0 )
			continue;
		if ( strcmp(dirent->d_name, "..") == 0 )
			continue;

/*		if (st.st_mode & 0x4000)
			RmDir(pathname);
		else*/
			_PrintDebugMessage(4, "	SF_remove\n");
			if ( SF_remove(pathname) == -1 )
				disp_errno();
	}

	_PrintDebugMessage(4, "	SF_remove\n");
	if (SF_closedir(dir) == -1)
		disp_errno();

	_PrintDebugMessage(4, "	SF_rmdir\n");
	/* remove directory */
	if ( SF_rmdir(dirname) == -1 )
		disp_errno();
}

static void RmDir2(char *dirname)
{
	DIR *dir;
	struct dirent *dirent;
	struct stat st;
	int count = 0;
	char pathname[256];
	char s[10];

	_PrintDebugMessage(4, "	SF_opendir\n");
	if ((dir = SF_opendir(dirname)) == NULL) {
		disp_errno();
		return;
	}

	memset(s, 0, sizeof(s));
	for (;;) {
		_PrintDebugMessage(4, "	SF_readdir\n");
		if ((dirent = SF_readdir(dir)) == NULL)
			break;

		_PrintDebugMessage(4, "	SF_stat\n");
		sprintf(pathname, "%s/%s", dirname, dirent->d_name);
		if (SF_stat(pathname, &st) == -1) {
			disp_errno();
			break;
		}

		if ( strcmp(dirent->d_name, ".") == 0 )
			continue;
		if ( strcmp(dirent->d_name, "..") == 0 )
			continue;

		if (st.st_mode & 0x4000)
			RmDir(pathname);
		else
			_PrintDebugMessage(4, "	SF_remove\n");
			if ( SF_remove(pathname) == -1 )
				disp_errno();
	}

	_PrintDebugMessage(4, "	SF_remove\n");
	if (SF_closedir(dir) == -1)
		disp_errno();
}

#ifndef SF_DEBUG_WINDOWS
int	lsg_test()
#else
int main(int argc, char *argv[])
#endif
{
	BOOL loop_flag = TRUE;
	BOOL disp_menu = TRUE;
	int menu_no;
	char mode[32];
	FILE *fp = NULL;
	int size;
	int count;
	int n;
	char data[32];
	int offset;
	int origin;
	struct stat st;
	char attrib[32];
	DIR *dir = NULL;
	struct dirent *dirent = NULL;
	int oflag;
	int pmode;
	int fh = 0;
	INT64 i64;
	int sectorNo;

#ifndef SF_DEBUG_WINDOWS
	devInfo = (void *)pSbd;
#else
	devInfo = (void *)1;
#endif

	if (! SF_init()) {
		printf("SF_init error!\n");
		return 0;
	}

	get_string("mount device name [/sd0] ? ", devName, "/sd0");

	SF_mount(devName, devInfo);

	while (loop_flag) {
		if (disp_menu) {
			printf(" 1.SF_fopen()     2.SF_fread()     3.SF_write()     4.SF_fseek()\n");
			printf(" 5.SF_fclose()    6.SF_feof()      7.SF_rename()    8.SF_remove()\n");
			printf(" 9.SF_mkdir()    10.SF_rmdir()    11.SF_fflush()   12.SF_fileno()\n");
			printf("13.SF_copy()     14.SF_stat()     15.SF_attrib()   16.SF_opendir()\n");
			printf("17.SF_readdir()  18.SF_closedir() 19.SF_open()     20.SF_read()\n");
			printf("21.SF_write()    22.SF_lseek()    23.SF_close()    24.SF_format()\n");
			printf("25.SF_sync()     26.SF_GetFatCount()               27.SF_ResetFatCount()\n");
			printf("30.SF_ioctl(FIOSYNC)     31.SF_ioctl(FIOLABELGET)\n");
			printf("32.SF_ioctl(FIOLABELSET) 33.SF_ioctl(FIONFREE64)\n");
			printf("50.Remove Directory Recursively\n");
			printf("51.Remove Directory 1\n");
			printf("52.Remove Directory 2\n");
			printf("90.ls command    91.dump command\n");
			printf("92.Dsp Fat key   93.dummy Write FAT#1              94.dummy Write FAT#2\n");
            printf("95.FAT TEST1     96.DataTest1\n"); 
            printf("98.Set Debug Message Level\n"); 
			printf("99.exit\n");

			disp_menu = FALSE;
		}

		menu_no = 0;
		get_number("> ", &menu_no, "0");
		fflush(stdin);

		errno = 0;

		switch (menu_no) {
		case 1:		/* SF_fopen() */
			printf("SF_fopen(const char *filename, const char *mode)\n");
			get_string("filename (s) ? ", filename, NULL);
			get_string("mode (a,[r],w,a+,r+,w+) ? ", mode, "r");
			fp = SF_fopen(filename, mode);
			printf("[ret] = 0x%x\n", fp);
			disp_errno();
			break;
		case 2:		/* SF_fread() */
			printf("SF_fread(void *buffer, size_t size, size_t count, FILE *stream)\n");
			get_number("size (n) ? ", &size, NULL);
			get_number("count (n) ? ", &count, NULL);
			if (size * count > sizeof(buf)) {
				printf("* size over\n");
				break;
			}
			n = SF_fread(buf, size, count, fp);
			printf("[ret] = %d\n", n);
			disp_errno();
			printf("[data] = ");
			disp_data(buf, n * size);
			break;
		case 3:		/* SF_fwrite() */
			printf("SF_fwrite(const void *buffer, size_t size, size_t count, FILE *stream)\n");
			get_number("size (n) ? ", &size, NULL);
			get_number("count (n) ? ", &count, NULL);
			if (size * count > sizeof(buf)) {
				printf("* size over\n");
				break;
			}
			get_string("data (c) ? ", data, NULL);
			memset(buf, *data, size * count);
			n = SF_fwrite(buf, size, count, fp);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 4:		/* SF_fseek() */
			printf("SF_fseek(FILE *stream, long offset, int origin)\n");
			get_number("offset ([0]:n) ? ", &offset, "0");
			get_number("origin ([0]:SET,1:CUR,2:END) ? ", &origin, "0");
			n = SF_fseek(fp, offset, origin);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 5:		/* SF_fclose() */
			printf("SF_fclose(FILE *stream)\n");
			n = SF_fclose(fp);
			printf("[ret] = %d\n", n);
			disp_errno();
			fp = NULL;
			break;
		case 6:		/* SF_feof() */
			printf("SF_feof(FILE *stream)\n");
			n = SF_feof(fp);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 7:		/* SF_rename() */
			printf("SF_rename(const char *oldname, const char *newname)\n");
			get_string("oldname (s) ? ", filename, NULL);
			get_string("newname (s) ? ", filename2, NULL);
			n = SF_rename(filename, filename2);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 8:		/* SF_remove() */
			printf("SF_remove(const char *path)\n");
			get_string("path (s) ? ", filename, NULL);
			n = SF_remove(filename);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 9:		/* SF_mkdir() */
			printf("SF_mkdir(const char *dirname)\n");
			get_string("dirname (s) ? ", filename, NULL);
			n = SF_mkdir(filename);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;			
		case 10:	/* SF_rmdir() */
			printf("SF_rmdir(const char *dirname)\n");
			get_string("dirname (s) ? ", filename, NULL);
			n = SF_rmdir(filename);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 11:	/* SF_fflush() */
			printf("SF_fflush(FILE *stream)\n");
			n = SF_fflush(fp);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;		
		case 12:	/* SF_fileno() */
			printf("SF_fileno(FILE *stream)\n");
			n = SF_fileno(fp);
			printf("[ret] = %d\n", n);
			if (n > 0) {
				fh = n;
			}
			disp_errno();
			break;
		case 13:	/* SF_copy() */
			printf("SF_copy(const char *srcfilename, const char *dstfilename)\n");
			get_string("srcfilename (s) ? ", filename, NULL);
			get_string("dstfilename (s) ? ", filename2, NULL);
			n = SF_copy(filename, filename2);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 14:	/* SF_stat() */
			printf("SF_stat(const char *path, struct stat *buffer)\n");
			get_string("path (s) ? ", filename, NULL);
			n = SF_stat(filename, &st);
			printf("[ret] = %d\n", n);
			disp_errno();
			printf("[st_mode] = 0x%x\n", st.st_mode);
			printf("[st_mtime] = ");
			disp_file_time(st.st_mtime);
			printf("\n[st_size] = %d\n", st.st_size);
			break;
		case 15:	/* SF_attrib() */
			printf("SF_attrib(const char *path, const char *attr)\n");
			get_string("filename (s) ? ", filename, NULL);
			get_string("attrib (+A,-A.+R,-R,+H,-H) ? ", attrib, NULL);
			n = SF_attrib(filename, attrib);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 16:	/* SF_opendir() */
			printf("SF_opendir(const char *dirname)\n");
			get_string("dirname (s) ? ", filename, NULL);
			dir = SF_opendir(filename);
			printf("[ret] = 0x%x\n", dir);
			disp_errno();
			break;
		case 17:	/* SF_readdir() */
			printf("SF_readdir(DIR *dir)\n");
			dirent = SF_readdir(dir);
			printf("[ret] = 0x%x\n", dirent);
			disp_errno();
			if (dirent != NULL)
				printf("[d_name] = %s\n", dirent->d_name);
			break;
		case 18:	/* SF_closedir() */
			printf("SF_closedir(DIR *dir)\n");
			n = SF_closedir(dir);
			printf("[ret] = %d\n", n);
			disp_errno();
			dir = NULL;
			break;
		case 19:	/* SF_open() */
			printf("SF_open(const char *filename, int oflag, int pmode)\n");
			get_string("filename (s) ? ", filename, NULL);
			get_hex("oflag([0]:h) ? ", &oflag, "0");
			get_hex("pmode([0]:h) ? ", &pmode, "0");
			fh = SF_open(filename, oflag, pmode);
			printf("[ret] = %d\n", fh);
			disp_errno();
			break;
		case 20:	/* SF_read() */
			printf("SF_read(int handle, void *buffer, unsigned int count)\n");
			get_number("count (n) ? ", &count, NULL);
			if (count > sizeof(buf)) {
				printf("* size over\n");
				break;
			}
			n = SF_read(fh, buf, count);
			printf("[ret] = %d\n", n);
			disp_errno();
			printf("[data] = ");
			disp_data(buf, n);
			break;
		case 21:	/* SF_write() */
			printf("SF_write(int handle, const void *buffer, unsigned int count)\n");
			get_number("count (n) ? ", &count, NULL);
			if (count > sizeof(buf)) {
				printf("* size over\n");
				break;
			}
			get_string("data (C) ? ", data, NULL);
			memset(buf, *data, count);
			n = SF_write(fh, buf, count);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 22:	/* SF_lseek() */
			printf("SF_lseek(int handle, long offset, int origin)\n");
			get_number("offset ([0]:n) ? ", &offset, "0");
			get_number("origin ([0]:SET,1:CUR,2:END) ? ", &origin, "0");
			n = SF_lseek(fh, offset, origin);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 23:	/* SF_close() */
			printf("SF_close(int handle)\n");
			n = SF_close(fh);
			printf("[ret] = %d\n", n);
			disp_errno();
			fh = 0;
			break;
		case 24:	/* SF_format() */
			printf("SF_format(void)\n");
			get_string("Format OK (Y/[N]) ? ", data, "N");
			if (data[0] == 'Y' || data[0] == 'y') {
				n = SF_format(devInfo);
				printf("[ret] = %d\n", n);
				disp_errno();
			}
			break;
		case 25:	/* SF_sync() */
			printf("SF_sync(const void *devname)\n");
			n = SF_sync(devName);
			printf("[ret] = %d\n", n);
			disp_errno();
			fh = 0;
			break;
		case 26:	/* SF_GetFatCount() */
			n = SF_GetFatCount();
			printf("[count] = %d\n", n);
			break;
		case 27:	/* SF_ResetFatCount() */
			n = SF_GetFatCount();
			printf("[before count] = %d\n", n);
			SF_ResetFatCount();
			n = SF_GetFatCount();
			printf("[after count] = %d\n", n);
			break;
		case 30:	/* SF_ioctl(FIOSYNC) */
			printf("SF_ioctl(int handle, FIOSYNC, int arg)\n");
			n = ioctl_test(FIOSYNC, 0);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 31:	/* SF_ioctl(FIOLABELGET) */
			printf("SF_ioctl(int handle, FIOLABELGET, int arg)\n");
			memset(data, ' ', 12);
			n = ioctl_test(FIOLABELGET, (int)data);
			printf("[ret] = %d\n", n);
			disp_errno();
			data[11] = '\0';
			printf("[label] = %s\n", data);
			break;
		case 32:	/* SF_ioctl(FIOLABELSET) */
			printf("SF_ioctl(int handle, FIOLABELSET, int arg)\n");
			get_string("label (s) ? ", data, NULL);
			n = ioctl_test(FIOLABELSET, (int)data);
			printf("[ret] = %d\n", n);
			disp_errno();
			break;
		case 33:	/* SF_ioctl(FIONFREE64) */
			printf("SF_ioctl(int handle, FIONFREE64, int arg)\n");
			n = ioctl_test(FIONFREE64, (int)&i64);
			printf("[ret] = %d\n", n);
			disp_errno();
#ifndef SF_DEBUG_WINDOWS
			n = (int)(i64 / 1024);
			printf("[free] = (int)=%dkb\n", n);
#else
			_i64toa(i64, data, 10);
			printf("[free] = %s\n", data);
#endif
			break;
		case 50:
			printf("Remove Directory Recursively\n");
			get_string("dirname (s) ? ", filename, NULL);
			n = RmDirRecursive(filename);
			printf("[result] = %d\n", n);
			break;
		case 51:
			printf("Remove Directory 1\n");
			get_string("dirname (s) ? ", filename, NULL);
			RmDir(filename);
			break;
		case 52:
			printf("Remove Directory 2\n");
			get_string("dirname (s) ? ", filename, NULL);
			RmDir2(filename);
			break;
		case 83:
			main3();
			disp_menu = TRUE;
			break;
		case 90:		/* ls command */
			sprintf(buf, "dirname ([%s]:s) ? ", devName);
			get_string(buf, filename, devName);
			ls_command(filename);
			break;
		case 91:		/* dump command */
			get_number("sectorNo. (n) ? ", &sectorNo, NULL);
			dump_command(sectorNo);
			break;
		case 92:
			DspFatKey();
			break;
		case 93:
			WrtFat1();
			break;
		case 94:
			WrtFat2();
			break;
		case 95:
			FatTest1();
			break;
		case 96:
			DataTest1();
			break;
        case 98: /* Debug Message Level Set */
			get_number("Debug Message Level. (5) ? ", &n, "5");
            n = _SetDebugMsgLevel(n);
			printf("Now Debug Message Level = %d\n", n);
            break;
		case 99:
			loop_flag = FALSE;
			break;
		default:
			disp_menu = TRUE;
			break;
		}
	}

	if (fp != NULL)
		SF_fclose(fp);

	SF_sync(devName);

/*	printf("\nHit enter key."); */
/*	getchar(); */

	return 0;
}

