/****************************************
*					*
*	basic library			*
*					*
****************************************/

#include "baslib.h"
#include "bcbios.h"

#pragma	module	data@

char	lp_width = 255;
char	cm_width[4] = {255,255,255,255};
char	currency = '\\';

char	lpos;
LASTPOINT	lp;
REGISTER	regs;
char	cursor_flag;
int	lno;
int	erl;
int	err;
char	*data_ptr;
char	b_work[256];
int	jmpbuf1[2], jmpbuf2[2];
void	(*on_err_adr)();
char	f_hc10;

#pragma	module	com@
void	(*on_com_adr[4])();
char	on_com_flg[4];

#pragma	module	key@
void	(*on_key_adr[11])();
char	on_key_flg[11];
char	key_int;

#pragma	module	brcd@
void	(*on_brcd_adr)();
char	on_brcd_flg;
