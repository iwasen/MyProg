/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

extern	void	b_cputc(char);

#pragma	module	country@
void b_country(sp)
STRTBL	*sp;
{
	char	c;

	if (sp->s_len == 0)
		b_error(E_FC);

	c = sp->s_str[0];
	switch (c) {
	case 'D':
	case 'E':
	case 'F':
	case 'G':
	case 'I':
	case 'J':
	case 'N':
	case 'S':
	case 'U':
	case 'W':
		break;
	default:
		b_error(E_FC);
	}
	
	b_cputc(0x1b);
	b_cputc('C');
	b_cputc(c);
}


#pragma	module	currency
void b_currency(sp)
STRTBL	*sp;
{
	if (sp->s_len == 0)
		b_error(E_FC);

	currency = sp->s_str[0];
}
