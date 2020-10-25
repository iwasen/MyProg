/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include	"baslib.h"
#include	"bcbios.h"

extern	void	b_cputc(char);

#pragma	module	curon@
void
b_cursor_on()
{
	b_cputc(0x1b);
	b_cputc(0x33);
	cursor_flag = 1;
}

#pragma	module	curoff@
void
b_cursor_off()
{
	b_cputc(0x1b);
	b_cputc(0x32);
	cursor_flag = 0;
}
