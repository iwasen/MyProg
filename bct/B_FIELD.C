/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#pragma	module	field@

void b_field(fp)
FB	*fp;
{
	union {
		int	*p_len;
		STRTBL	**p_sp;
	} p;	
	int	len, offset;
	STRTBL	*sp;


	switch (fp->f_mode) {
	case 0:
		b_error(E_BN);
	case RANDOM:
		break;
	default:
		b_error(E_BF);
	}

	p.p_len = (int *)(&fp + 1);
	offset = 0;
	while (*p.p_len != -1) {
		len = *p.p_len++;
		sp = *p.p_sp++;
		sp->s_len = len;
		sp->s_str = &fp->f_rbuf[offset];
		offset += len;
	}
}
