/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"

#pragma	module	rset@

void b_rset(a,b)
STRTBL	*a, *b;
{
	char	*p1, *p2;
	char	i;

	p1 = a->s_str;
	p2 = b->s_str;

	for (i = a->s_len; i > b->s_len; i--)
		*p1++ = ' ';
	while (i--)
		*p1++ = *p2++;
}

#pragma	module	lset@

void b_lset(a,b)
STRTBL	*a, *b;
{
	char	*p1, *p2;
	char	i, j;

	p1 = a->s_str;
	p2 = b->s_str;

	for (i = a->s_len, j = b->s_len; i != 0; i--) {
		if (j != 0) {
			*p1++ = *p2++;
			j--;
		} else
			*p1++ = ' ';
	}
}
