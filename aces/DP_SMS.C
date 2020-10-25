/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: ＳＭＳモード				*
 *				  ダイナミック・パラメータ計算処理	*
 *		ファイル名	: dp_sms.c				*
 *									*
 ************************************************************************/

#pragma	SECTION PROG=dp_sms
#pragma	SECTION CONST=dp_sms
#pragma	SECTION DATA=dp_sms

#include "sg_cpu.h"

static	void	dp_M031(void);
static	void	dp_M032(void);
static	void	dp_M033(void);
static	void	dp_M034(void);
static	void	dp_M035(void);
static	void	dp_M036(void);

/*=======================================================================
 |
 |		ＳＭＳモード　ダイナミック・パラメータ計算処理
 |
 |	void	dp_sms()
 |
 =======================================================================*/
void	dp_sms(void)
{
	dp_M031();	/* Aircraft Symbol - F-4EJKAI */
	dp_M032();	/* Aircraft Symbol - F-15J */
	dp_M033();	/* Aircraft Symbol - FS-X Class */
	dp_M034();	/* Selective Jettison Lines - F-4EJKAI/F-15J */
	dp_M035();	/* Selective Jettison Lines - FS-X Class A/A Lines */
	dp_M036();	/* Selective Jettison Lines - FS-X Class A/G Lines */
}

/*=======================================================================
 |
 |		Aircraft Symbol - F-4EJKAI
 |
 |	void	dp_M031()
 |
 =======================================================================*/
static	void	dp_M031(void)
{
	if (input_data.fixed_symbol.sms_fixed_symbol1 & 0x8000) {
		dp.C031 = COLOR(input_data.fixed_symbol.sms_fixed_symbol1, 11);
		SET_PABIT(PA_M031);
	}
}

/*=======================================================================
 |
 |		Aircraft Symbol - F-15J
 |
 |	void	dp_M032()
 |
 =======================================================================*/
static	void	dp_M032(void)
{
	if (input_data.fixed_symbol.sms_fixed_symbol1 & 0x0400) {
		dp.C032 = COLOR(input_data.fixed_symbol.sms_fixed_symbol1, 6);
		SET_PABIT(PA_M032);
	}
}

/*=======================================================================
 |
 |		Aircraft Symbol - FS-X Class
 |
 |	void	dp_M033()
 |
 =======================================================================*/
static	void	dp_M033(void)
{
	if (input_data.fixed_symbol.sms_fixed_symbol1 & 0x0020) {
		dp.C033 = COLOR(input_data.fixed_symbol.sms_fixed_symbol1, 1);
		SET_PABIT(PA_M033);
	}
}

/*=======================================================================
 |
 |		Selective Jettison Lines - F-4EJKAI/F-15J
 |
 |	void	dp_M034()
 |
 =======================================================================*/
static	void	dp_M034(void)
{
	if (input_data.fixed_symbol.sms_fixed_symbol2 & 0x8000) {
		dp.C034 = COLOR(input_data.fixed_symbol.sms_fixed_symbol2, 11);
		SET_PABIT(PA_M034);
	}
}

/*=======================================================================
 |
 |		Selective Jettison Lines - FS-X Class A/A Lines
 |
 |	void	dp_M035()
 |
 =======================================================================*/
static	void	dp_M035(void)
{
	if (input_data.fixed_symbol.sms_fixed_symbol3 & 0x8000) {
		dp.C035 = COLOR(input_data.fixed_symbol.sms_fixed_symbol3, 11);
		SET_PABIT(PA_M035);
	}
}

/*=======================================================================
 |
 |		Selective Jettison Lines - FS-X Class A/G Lines
 |
 |	void	dp_M036()
 |
 =======================================================================*/
static	void	dp_M036(void)
{
	if (input_data.fixed_symbol.sms_fixed_symbol3 & 0x0400) {
		dp.C036 = COLOR(input_data.fixed_symbol.sms_fixed_symbol3, 6);
		SET_PABIT(PA_M036);
	}
}
