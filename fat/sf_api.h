/**********************************************************
*      System : SSMSD FAT File System                     *
*      Module : API header                                *
**********************************************************/

#ifndef __SF_API_H__
#define __SF_API_H__

#define	_POSIX_
#include <limits.h>

#ifndef NAME_MAX
#define NAME_MAX 255
#endif

#ifdef SF_DEBUG_WINDOWS
struct dirent {		/* dirent */
    char	d_name [NAME_MAX + 1];	/* file name, null-terminated */
};

typedef struct {	/* DIR */
    int		  dd_fd;				/* file descriptor for open directory */
    int		  dd_cookie;			/* filesys-specific marker within dir */
    struct dirent dd_dirent;		/* obtained directory entry */
} DIR;
#else
#include "dirent.h"
#endif

#ifdef SF_DEBUG_WINDOWS
#define INT64	_int64
#else
#define INT64	long long
#endif

extern FILE *SF_fopen(const char *filename, const char *mode);
extern size_t SF_fread(void *buffer, size_t size, size_t count, FILE *stream);
extern size_t SF_fwrite(const void *buffer, size_t size, size_t count, FILE *stream);
extern int SF_fseek(FILE *stream, long offset, int origin);
extern int SF_fclose(FILE *stream);
extern int SF_feof(FILE *stream);
extern int SF_rename(const char *oldname, const char *newname);
extern int SF_remove(const char *path);
extern int SF_mkdir(const char *dirname);
extern int SF_rmdir(const char *dirname);
extern int SF_fflush(FILE *stream);
extern int SF_fileno(FILE *stream);
extern int SF_copy(const char *srcfilename, const char *dstfilename);
extern int SF_stat(const char *path, struct stat *buffer);
extern int SF_attrib(const char *path, const char *attr);
extern DIR *SF_opendir(const char *dirname);
extern struct dirent *SF_readdir(DIR *dir);
extern int SF_closedir(DIR *dir);
extern int SF_open(const char *filename, int oflag, int pmode);
extern int SF_read(int handle, void *buffer, unsigned int count);
extern int SF_write(int handle, const void *buffer, unsigned int count);
extern long SF_lseek(int handle, long offset, int origin);
extern int SF_close(int handle);
extern int SF_creat(const char *filename, int pmode);
extern int SF_eof(int handle);
extern int SF_init(void);
extern int SF_mount(const char *devname, const void *devinfo);
extern int SF_format(const void *devinfo);
extern int SF_sync(const char *devname);
extern int SF_ioctl(int handle, int function, int arg);
extern int SF_GetFatCount(void);
extern void SF_ResetFatCount(void);

#endif	/* __SF_API_H__ */
