/*
	stdio.h		header fot input/output
*/

#ifndef	NULL
#include <stdefs.h>
#endif

typedef	struct	{
	char	mode;		/*	file mode	R, W, R/W	*/
	char	*ptr;		/*	next character position		*/
	int	rcount;		/*	number of characters left	*/
	int	wcount;		/*	number of rooms left		*/
	char	*base;		/*	location of buffer		*/
	unsigned bufsiz;	/*	size of bufer			*/
	int	fd;		/*	file descriptor			*/
	char	smallbuf[1];	/*	used for buffer when unbufferd	*/
	BOOL	autobuf;	/*	is buffer allocated		*/
}	FILE;


#define	SEEK_SET	0
#define	SEEK_CUR	1
#define	SEEK_END	2
#define	SEEK_TEND	3	/*	used internally		*/

#define	SYS_OPEN	10	/*	maximum number of buffered I/O	*/
#define	BUFSIZ		1024	/*	default buffer size allocated	*
				 *	by fopen.			*/

extern	FILE	_iob[SYS_OPEN];

#define	stdin	(&_iob[0])
#define	stdout	(&_iob[1])
#define	stderr	(&_iob[2])


#define	EOF	-1
#define	WILDCARD	-2

#define	L_tmpnam	8
#define	TMP_MAX		200


int	unlink(char *);
#define	remove	unlink
int	rename(char *, char *);
FILE	*tmpfile(void);
char	*tmpnam(char *);
int	fclose(FILE *);
int	fflush(FILE *);
FILE	*fopen(char *, char *);
FILE	*freopen(char *, char *, FILE *);
int	fprintf(FILE *, char *, ...);
int	fscanf(FILE *, char *, ...);
int	printf(char *, ...);
int	scanf(char *, ...);
int	sprintf(char *, char *, ...);
int	sscanf(char *, char *, ...);
int	fgetc(FILE *);

int	fp_fprintf(FILE *, char *, ...);
int	fp_fscanf(FILE *, char *, ...);
int	fp_printf(char *, ...);
int	fp_scanf(char *, ...);
int	fp_sprintf(char *, char *, ...);
int	fp_sscanf(char *, char *, ...);

char	*fgets(char *, int, FILE *);
char	*gets(char *);
#define	getc	fgetc
#define	getchar()	getc(stdin)

int	fputc(char, FILE *);
int	fputs(char *, FILE *);
int	puts(char *);
#define	putc	fputc
#define	putchar(c)	putc(c, stdout)
int	ungetc(char, FILE *);
#define	ungetch(c)	ungetc(c, stdin)

int	fread(void *, size_t, int, FILE *);
int	fwrite(void *, size_t, int, FILE *);
int	fseek(FILE *, long, int);
long	ftell(FILE *);
void	rewind(FILE *);

void	clearerr(FILE *);
BOOL	feof(FILE *);
BOOL	ferror(FILE *);

void	setbuf(FILE *, char *);
void	setvbuf(FILE *, char *, int, int);	/* type is not supported */
#define	_IOFBF	0

/*  non-ANSI functions  */
int	putw(int, FILE *);
int	getw(FILE *);

typedef	int	FD;
FD	creat(char *);
FD	open(char *, int);
int	lseek(FD, long, int);
long	tell(FD);
int	read(FD, char *, int);
int	write(FD, char *, int);
int	close(FD);

BOOL	kbhit(void);
VOID	sensebrk(void);
char	cput(char);
char	cget(void);
