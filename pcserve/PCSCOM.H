/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ã§í ÉTÉuÉãÅ[É`ÉìÉwÉbÉ_
 *		ÉtÉ@ÉCÉãñº	: pcscom.h
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#ifdef	ALLOC_COUNT
#define	malloc	_malloc
#define	calloc	_calloc
#define	free	_free
void	*malloc(size_t);
void	*calloc(size_t, size_t);
void	free(void *);
#endif

extern	BOOL	active;

extern	double	natof(CHAR *, SHORT);
extern	SHORT	natoi(CHAR *, SHORT);
extern	LONG	natol(CHAR *, SHORT);
extern	VOID	StringToUpper(CHAR *);
extern	VOID	SetExtention(CHAR *, CHAR *);
extern	VOID	Scramble1(CHAR *, SHORT, USHORT);
extern	VOID	Scramble2(CHAR *, SHORT, USHORT);
extern	void GetFullPath(char *pPathNameSrc, char *pPathNameDst, int size);
extern	LONG lseek64(HANDLE hFile, LONG64 qwOffset, DWORD dwMoveMethod);
