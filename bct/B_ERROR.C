/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	int	sprintf(char *, char *, ...);
extern	void	b_cputs(char *);
extern	void	b_exit(void);
extern	void	(*on_err_adr)();
extern	void	b_longjmp(int);
extern	int	jmpbuf1[], jmpbuf2[];

#pragma	module	error@

void b_error(err_code)
int	err_code;
{
	char	*s;

	erl = lno;
	err = err_code;

	if (on_err_adr) {
		jmpbuf2[0] = jmpbuf1[0];
		jmpbuf2[1] = jmpbuf1[1];
		(*on_err_adr)();
	} else {
		if (erl == 0)
			s = "Error %u\n";
		else
			s = "Error %u in %u\n";
		sprintf(b_work,s,err_code,erl);
		b_cputs(b_work);
		b_exit();
	}
}


#pragma	module	onerr@

void b_on_err(addr)
void	(*addr)();
{
	on_err_adr = addr;
}


#pragma module	resume@

void b_resume(flag)
int	flag;
{
	lno = erl;
	jmpbuf1[0] = jmpbuf2[0];
	jmpbuf1[1] = jmpbuf2[1];
	b_longjmp(flag);
}
