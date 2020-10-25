/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ÉtÉ@ÉCÉãÇhÇnÉ}ÉNÉç
 *		ÉtÉ@ÉCÉãñº	: pcsio.h
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#ifdef	OS_WINDOWS
#define	__CREAT_LOCAL(_fname)	_lcreat(_fname,0)
#define	__CREAT(_fname)	_lcreat(_fname,0)
#define	__OPEN(_fname)	_lopen(_fname,READ_WRITE)
#define	__READ(_handle,_buf,_size)	(int)_lread(_handle,_buf,_size)
#define	__WRITE_LOCAL(_handle,_buf,_size)\
		(int)_lwrite(_handle,_buf,_size)
#define	__WRITE(_fname,_handle,_buf,_size)\
		(int)_lwrite(_handle,_buf,_size)
#define	__WRITE2(_fname,_handle,_buf,_size,_offset)\
		(_llseek(_handle,_offset,0),\
		(int)_lwrite(_handle,_buf,_size))
#define	__LSEEK(_handle,_offset,_origin) _llseek(_handle,_offset,_origin)
#define	__CLOSE(_handle)	_lclose(_handle)
#define	__CHSIZE_LOCAL(_handle,_size)\
		_llseek(_handle,_size,0);_lwrite(_handle,NULL,0)
#define	__CHSIZE(_fname,_handle,_size)\
		_llseek(_handle,_size,0);_lwrite(_handle,NULL,0)
#define	__DELETE(_fname)\
		remove(_fname)
#define	__RENAME(_fname1,_fname2)\
		rename(_fname1,_fname2)
#define	__FLUSH(_handle)
#endif

#ifdef	OS_MSDOS
#define	__CREAT(_fname)	open(_fname,O_RDWR|O_CREAT|O_TRUNC|O_BINARY,S_IREAD|S_IWRITE)
#define	__CREAT_LOCAL(_fname)	open(_fname,O_RDWR|O_CREAT|O_TRUNC|O_BINARY,S_IREAD|S_IWRITE)
#define	__OPEN(_fname)	open(_fname,O_RDWR|O_BINARY)
#define	__READ(_handle,_buf,_size)	read(_handle,_buf,_size)
#define	__WRITE(_fname,_handle,_buf,_size)	write(_handle,_buf,_size)
#define	__WRITE_LOCAL(_handle,_buf,_size)	write(_handle,_buf,_size)
#define	__WRITE2(_fname,_handle,_buf,_size,_offset)\
		(lseek(_handle,_offset,0),write(_handle,_buf,_size))
#define	__LSEEK(_handle,_offset,_origin) lseek(_handle,_offset,_origin)
#define	__CLOSE(_handle)	close(_handle)
#define	__CHSIZE(_fname,_handle,_size)	chsize(_handle,_size)
#define	__CHSIZE_LOCAL(_handle,_size)	chsize(_handle,_size)
#define	__DELETE(_fname)\
		remove(_fname)
#define	__RENAME(_fname1,_fname2)\
		rename(_fname1,_fname2)
#endif

#ifdef	OS_WNT
#ifdef	_SERVER
#define	__CREAT_LOCAL(_fname)\
		(int)CreateFile(_fname,GENERIC_READ|GENERIC_WRITE,\
				FILE_SHARE_READ,NULL,CREATE_ALWAYS,\
				FILE_ATTRIBUTE_NORMAL,NULL)
#define	__CREAT(_fname)\
		(int)PSCreateFile(_fname,GENERIC_READ|GENERIC_WRITE,\
				FILE_SHARE_READ)
#define	__OPEN(_fname)\
		(int)CreateFile(_fname,GENERIC_READ|GENERIC_WRITE,\
				FILE_SHARE_READ,NULL,OPEN_EXISTING,\
				FILE_ATTRIBUTE_NORMAL,NULL)
#define	__READ(_handle,_buf,_size)\
		(ReadFile((HANDLE)_handle,_buf,_size,&numBytes,NULL),\
				(int)numBytes)
#define	__WRITE_LOCAL(_handle,_buf,_size)\
		(WriteFile((HANDLE)_handle,_buf,_size,&numBytes,NULL),\
				(int)numBytes)
#define	__WRITE(_fname,_handle,_buf,_size)\
		PSWriteFile(_fname,_handle,_buf,_size,-1)
#define	__WRITE2(_fname,_handle,_buf,_size,_offset)\
		PSWriteFile(_fname,_handle,_buf,_size,_offset)
#define	__LSEEK(_handle,_offset,_origin)\
		SetFilePointer((HANDLE)_handle,_offset,NULL,_origin)
#define	__CLOSE(_handle)\
		CloseHandle((HANDLE)_handle)
#define	__CHSIZE_LOCAL(_handle,_size)\
		SetFilePointer((HANDLE)_handle,_size,NULL,\
				FILE_BEGIN);SetEndOfFile((HANDLE)_handle)
#define	__CHSIZE(_fname,_handle,_size)\
		PSChangeFileSize(_fname,_handle,_size)
#define	__DELETE(_fname)\
		PSDeleteFile(_fname)
#define	__COPYFILE(_fname1,_fname2)\
		PSCopyFile(_fname1,_fname2)
#define	__RENAME(_fname1,_fname2)\
		PSRename(_fname1,_fname2)
#define	__FLUSH(_handle)\
		FlushFileBuffers((HANDLE)_handle)

extern	int	PSCreateFile(char *, int, int);
extern	int	PSWriteFile(char *, int, char *, int, long);
extern	BOOL	PSChangeFileSize(CHAR *, int, int);
extern	BOOL	PSDeleteFile(CHAR *);
extern	BOOL	PSCopyFile(CHAR *, CHAR *);
extern	BOOL	PSRename(CHAR *, CHAR *);
#else
#define	__CREAT_LOCAL(_fname)\
		(int)CreateFile(_fname,GENERIC_READ|GENERIC_WRITE,\
				FILE_SHARE_READ,NULL,CREATE_ALWAYS,\
				FILE_ATTRIBUTE_NORMAL,NULL)
#define	__CREAT(_fname)\
		(int)CreateFile(_fname,GENERIC_READ|GENERIC_WRITE,\
				FILE_SHARE_READ,NULL,CREATE_ALWAYS,\
				FILE_ATTRIBUTE_NORMAL,NULL)
#define	__OPEN(_fname)\
		(int)CreateFile(_fname,GENERIC_READ|GENERIC_WRITE,\
				FILE_SHARE_READ,NULL,OPEN_EXISTING,\
				FILE_ATTRIBUTE_NORMAL,NULL)
#define	__READ(_handle,_buf,_size)\
		(ReadFile((HANDLE)_handle,_buf,_size,\
				&numBytes,NULL),(int)numBytes)
#define	__WRITE_LOCAL(_handle,_buf,_size)\
		(WriteFile((HANDLE)_handle,_buf,_size,\
				&numBytes,NULL),(int)numBytes)
#define	__WRITE(_fname,_handle,_buf,_size)\
		(WriteFile((HANDLE)_handle,_buf,_size,\
				&numBytes,NULL),(int)numBytes)
#define	__WRITE2(_fname,_handle,_buf,_size,_offset)\
		(SetFilePointer((HANDLE)_handle,_offset,NULL,FILE_BEGIN),\
				WriteFile((HANDLE)_handle,_buf,_size,\
				&numBytes,NULL),(int)numBytes)
#define	__WRITE2_64(_fname,_handle,_buf,_size,_offset)\
		(lseek64((HANDLE)_handle,_offset,FILE_BEGIN),\
				WriteFile((HANDLE)_handle,_buf,_size,\
				&numBytes,NULL),(int)numBytes)
#define	__LSEEK(_handle,_offset,_origin)\
		SetFilePointer((HANDLE)_handle,_offset,NULL,_origin)
#define	__LSEEK_64(_handle,_offset,_origin)\
		lseek64((HANDLE)_handle,_offset,_origin)
#define	__CLOSE(_handle)\
		CloseHandle((HANDLE)_handle)
#define	__CHSIZE_LOCAL(_handle,_size)\
		SetFilePointer((HANDLE)_handle,_size,NULL,FILE_BEGIN);SetEndOfFile((HANDLE)_handle)
#define	__CHSIZE_LOCAL_64(_handle,_size)\
		lseek64((HANDLE)_handle,_size,FILE_BEGIN);SetEndOfFile((HANDLE)_handle)
#define	__CHSIZE(_fname,_handle,_size)\
		SetFilePointer((HANDLE)_handle,_size,NULL,FILE_BEGIN);SetEndOfFile((HANDLE)_handle)
#define	__CHSIZE_64(_fname,_handle,_size)\
		lseek64((HANDLE)_handle,_size,FILE_BEGIN);SetEndOfFile((HANDLE)_handle)
#define	__DELETE(_fname)\
		DeleteFile(_fname)
#define	__COPYFILE(_fname1,_fname2)\
		CopyFile(_fname1,_fname2,FALSE)
#define	__RENAME(_fname1,_fname2)\
		MoveFile(_fname1,_fname2)
#define	__FLUSH(_handle)\
		FlushFileBuffers((HANDLE)_handle)
#endif
#endif
