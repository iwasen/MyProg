/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#pragma	module	iusr@

int b_iusr(addr,param)
void	(*addr)();
int	param;
{
	static	int	work;

	work = param;
	_asm_c("\n\tmvi\ta,2\n");
	(*addr)(&work);
	return(work);
}


#pragma	module	lusr@

long b_lusr(addr,param)
void	(*addr)();
long	param;
{
	static	long	work;

	work = param;
	_asm_c("\n\tmvi\ta,5\n");
	(*addr)(&work);
	return(work);
}


#pragma	module	fusr@

float b_fusr(addr,param)
void	(*addr)();
float	param;
{
	static	float	work;

	work = param;
	_asm_c("\n\tmvi\ta,4\n");
	(*addr)(&work);
	return(work);
}


#pragma	module	dusr@

double b_dusr(addr,param)
void	(*addr)();
double	param;
{
	static	union	{
		double	work;
		char	c[8];
	} fac;

	fac.work = param;
	_asm_c("\n\tmvi\ta,8\n");
	(*addr)(&fac.c[4]);
	return(fac.work);
}


#pragma	module	susr@

STRTBL *b_susr(addr,param)
void	(*addr)();
STRTBL	*param;
{
	char	i;
	STRTBL	*sp;

	sp = b_getarea((char)255);
	sp->s_len = param->s_len;
	for (i = 0; i != param->s_len; i++)
		sp->s_str[i] = param->s_str[i];
	(*addr)((char)3,sp);
	return(sp);
}
