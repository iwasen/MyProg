/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"

extern	int	sprintf(char *, char *, ...);
extern	void	b_cputs(char *);
extern	void	b_exit(void);
extern	void	exit(void);


#pragma	module	end@

void b_end()
{
	b_exit();
}


#pragma	module	system@

void b_system()
{
	exit();
}


#pragma	module	stop@

void b_stop(lno)
int	lno;
{
	sprintf(b_work,"Break in %u\n",lno);
	b_cputs(b_work);
	b_exit();
}
