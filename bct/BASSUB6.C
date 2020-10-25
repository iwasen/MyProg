/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

#pragma	module	wait@
void
b_wait(port,x,y)
int	port,x,y;
{
extern	int	inp_sub(int);
char	z;

	do {
		z = (char)inp_sub(port);
		z ^= (char)y;
		z &= (char)x;
	} while (z == 0);
}

static	int
inp_sub(port)
int	port;
{
char	_asm_c(char *);

	_asm_c("\n\tMOV\tC,L\n");	/* LD	C,L	*/
	_asm_c("\n\tDB\t0EDH\n");	/* IN	L,(C)	*/
	_asm_c("\n\tDB\t068H\n");
	_asm_c("\n\tMVI\tH,0\n");	/* LD	H,0	*/
}
