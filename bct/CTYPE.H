/*
	ctype.h		character handling
*/

#define	isalpha(c)	(isupper(c) || islower(c))
#define	isupper(c)	('A' <= (c) && (c) <= 'Z')
#define	islower(c)	('a' <= (c) && (c) <= 'z')
#define	isdigit(c)	('0' <= (c) && (c) <= '9')
#define	isspace(c)	((c) == ' ' || (c) == '\t' || (c) == '\n' ||\
			(c) == '\r' || (c) == '\f' || (c) == '\v')

#define	isalnum(c)	(isalpha(c) || isdigit(c))
#define	iscntrl(c)	((c) < ' ' || (c) == '\177')
#define	isgraph(c)	(' ' < (c) && (c) < '\177')
#define	isprint(c)	(' ' <= (c) && (c) < '\177')
#define	ispunct(c)	(isgraph(c) && ! isalnum(c))
#define	isxdigit(c)	(isdigit(c) || 'A'<=(c)&&(c)<='F' || 'a'<=(c)&&c<='f')
#define	isascii(c)	((c) < '\200')

char	tolower(char);
char	toupper(char);
