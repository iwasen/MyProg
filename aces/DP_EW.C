/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: ＥＷモード				*
 *				  ダイナミック・パラメータ計算処理	*
 *		ファイル名	: dp_ew.c				*
 *									*
 ************************************************************************/

#pragma	SECTION PROG=dp_ew
#pragma	SECTION CONST=dp_ew
#pragma	SECTION DATA=dp_ew

#include "sg_cpu.h"

static	void	dp_M041(void);
static	void	dp_M042(void);
static	void	dp_M043(void);
static	void	dp_M044(void);

/*=======================================================================
 |
 |		ＥＷモード　ダイナミック・パラメータ計算処理
 |
 |	void	dp_ew()
 |
 =======================================================================*/
void	dp_ew(void)
{
	dp_M041();	/* EW Aircraft Center Symbol */
	dp_M042();	/* EW Format Window */
	dp_M043();	/* EW Mode - Dot Symbols */
	dp_M044();	/* OMNI Threat Symbol */
}

/*=======================================================================
 |
 |		EW Aircraft Center Symbol
 |
 |	void	dp_M041()
 |
 =======================================================================*/
static	void	dp_M041(void)
{
	switch (input_data.fixed_symbol.ew_fixed_symbol & 0xc000) {
	case 0x4000:	/* Normal */
		dp.C041 = COLOR(input_data.fixed_symbol.ew_fixed_symbol, 10);
		SET_PABIT(PA_M041A);
		break;
	case 0x8000:	/* Expanded */
		dp.C041 = COLOR(input_data.fixed_symbol.ew_fixed_symbol, 10);
		SET_PABIT(PA_M041B);
		break;
	}
}

/*=======================================================================
 |
 |		EW Format Window
 |
 |	void	dp_M042()
 |
 =======================================================================*/
static	void	dp_M042(void)
{
	if (input_data.fixed_symbol.ew_fixed_symbol & 0x0200) {
		dp.C042 = COLOR(input_data.fixed_symbol.ew_fixed_symbol, 5);
		SET_PABIT(PA_M0420);
		SET_PABIT(PA_M0421);
	}
}

/*=======================================================================
 |
 |		EW Mode - Dot Symbols
 |
 |	void	dp_M043()
 |
 =======================================================================*/
static	void	dp_M043(void)
{
	if ((input_data.fixed_symbol.ew_dot_symbol & 0x8000) &&
			filter_data.ew_dot_symbol < 0x3a80) {
		dp.L043 = LENGTH(filter_data.ew_dot_symbol, 204.8);
		dp.C043 = COLOR(input_data.fixed_symbol.ew_dot_symbol, 0);
		SET_PABIT(PA_M043);
	}
}

/*=======================================================================
 |
 |		OMNI Threat Symbol
 |
 |	void	dp_M044()
 |
 =======================================================================*/
static	void	dp_M044(void)
{
	if (input_data.fixed_symbol.ew_fixed_symbol & 0x0010) {
		dp.C044 = COLOR(input_data.fixed_symbol.ew_fixed_symbol, 0);
		SET_PABIT(PA_M044);
	}
}
