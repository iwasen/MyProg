#pragma nonrec

char	_asm_ci(char *, int);
char	_asm_cic(char *, int, char);
char	_asm_c(char *);

#define	inp(p)		_asm_ci("\n\tIN\t$1\n", p)
#define	outp(p, c)	_asm_cic("\n\tOUT\t$1\n", p, c)
#define	di()		_asm_c("\n\tDI\n")
#define	ei()		_asm_c("\n\tEI\n")

#define	RBUFSIZ1	256		/* receive buffer size (DCE)	*/
#define	RBUFSIZ2	256		/* receive buffer size (DTE)	*/

typedef	union	{
			int	i;
			char	c[2];
		} CRCDATA;

#define	ETB_BLK		'B'		/* ETB block-id			*/
#define	ETX_BLK		'X'		/* ETX block-id			*/

#define ON		1
#define	OFF		0
