/*
	string.h - string handling

	26-Mar-86
*/

#ifndef	NULL
#include <stdefs.h>
#endif

#define	memcpy(d, s, n)	movmem(s, d, n)
#define	memset(s, c, n)	setmem(s, n, c)
char	*strcpy(char *, char *);
char	*strncpy(char *, char *, size_t);
char	*strcat(char *, char *);
char	*strncat(char *, char *, size_t);
int	memcmp(void *, void *, size_t);
int	strcmp(char *, char *);
size_t	strlen(char *);
int	strncmp(char *, char *, size_t);
void	*memchr(void *, char, size_t);
char	*strchr(char *, char);
size_t	strcspn(char *, char *);
char	*strpbrk(char *, char *);
char	*strrchr(char *, char);
size_t	strspn(char *, char *);
char	*strtok(char *, char *);


/*	non-ANSI functions	*/

void	*movmem(void *, void *, size_t);
void	*setmem(void *, size_t, char);
