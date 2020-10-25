/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"

#pragma	module	eqv@

int b_eqv(p1, p2)
int	p1, p2;
{
	return(~(p1 ^ p2));
}


#pragma	module	imp@

int b_imp(p1, p2)
int	p1, p2;
{
	int	result, mask;
	char	i;

	mask = 1;
	result =0;
	for (i = 16; i != 0; i++) {
		result |= (p1 & mask) != 0 && (p2 & mask) == 0 ? 0 : mask;
		mask <<= 1;
	}
	return(result);
}


#pragma	module	ipwr@

float b_ipwr(p1, p2)
float	p1;
int	p2;
{
	float	result;

	result = 1;
	for ( ; p2 > 0; --p2)
		result *= p1;
	return(result);
}


#pragma	module	rpwr@

float b_rpwr(p1, p2)
float	p1, p2;
{
	extern	float	b_exp(float);
	extern	float	b_log(float);

	return(b_exp(b_log(p1)*p2));
}


#pragma	module	getarea@

STRTBL *b_getarea(len)
char	len;
{
#define	SBUFSIZ	512

	static	char	sbuff[SBUFSIZ];
	static	int	counter = 0;
	STRTBL	*sp;
	char	*cp;

	if ((counter + len + 3) >= SBUFSIZ)
		counter = 0;

	cp = &sbuff[counter];
	sp = (STRTBL *)cp;
	sp->s_str = cp + 3;
	sp->s_len = len;
	counter += len + 3;
	return(sp);
}


#pragma	module	strset@

void b_strset(a,b)
STRTBL	*a, *b;
{
	char	*p1, *p2;
	char	i;

	a->s_len = b->s_len;
	for (p1 = a->s_str, p2 = b->s_str, i = a->s_len; i; --i)
		*p1++ = *p2++;
}


#pragma	module	stradd@

STRTBL *b_stradd(a,b)
STRTBL	*a, *b;
{
	STRTBL	*result;
	char	*p1, *p2;
	char	i;

	result = b_getarea(a->s_len + b->s_len);
	p1 = result->s_str;
	for (i = a->s_len, p2 = a->s_str; i; --i)
		*p1++ = *p2++;
	for (i = b->s_len, p2 = b->s_str; i; --i)
		*p1++ = *p2++;
	return(result);
}


#pragma	module	strcmp@

int b_strcmp(a, b)
STRTBL	*a, *b;
{
	char	*p1, *p2;
	char	i;
	int	result;

	p1 = a->s_str;
	p2 = b->s_str;
	for (i = 0; ; i++) {
		if (a->s_len == i || b->s_len == i)
			break;
		if (result = ((int)*p1++ - (int)*p2++))
			return(result);
	}
	return((int)a->s_len - (int)b->s_len);
}
