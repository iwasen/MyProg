/************************************************************************
 *									*
 *		ＡＣＥＳ　Ｓ／Ｇ　ＣＰＵプログラム			*
 *									*
 *		名称		: ダイナミック・パラメータ計算処理	*
 *		ファイル名	: dp_calc.c				*
 *									*
 ************************************************************************/

#pragma	SECTION PROG=dp_calc
#pragma	SECTION CONST=dp_calc
#pragma	SECTION DATA=dp_calc

#include "sg_cpu.h"

static	void	dp_window(void);

/*=======================================================================
 |
 |		ダイナミック・パラメータ計算処理
 |
 |	void	dp_calc()
 |
 =======================================================================*/
void	dp_calc(void)
{
	switch (display_mode) {
	case DSPM_FCR:		/* ＦＣＲ */
		dp_fcr();
		break;
	case DSPM_SMS:		/* ＳＭＳ */
		dp_sms();
		break;
	case DSPM_EW:		/* ＥＷ */
		dp_ew();
		break;
	case DSPM_HSI:		/* ＨＳＩ */
		dp_hsi();
		break;
	case DSPM_ADI:		/* ＡＤＩ */
		dp_adi();
		break;
	}

	dp_window();		/* 可変位置ウィンドウ */
/* @@@@@@@@@@@@@@ */
	if (grid_flag)
		SET_PABIT(1023);
/* @@@@@@@@@@@@@@ */

	digital_update_flag = 0;	/* デジタル数値更新フラグリセット */
}

/*=======================================================================
 |
 |		可変位置ウィンドウ Ｄ／Ｐセット
 |
 |	void	dp_window()
 |
 =======================================================================*/
static	void	dp_window(void)
{
	int	i;

	/* 10 Hz */
	for (i = 0; i < 76; i++) {
		if ((input_data.mfd_window1[i].y & 0x000f) == 0)
			continue;
		dp.mfd_window1[i].W_PX =
			POSITION((short)(input_data.mfd_window1[i].x & 0xffe0),
			204.8);
		dp.mfd_window1[i].W_PY =
			POSITION((short)(input_data.mfd_window1[i].y & 0xffe0),
			204.8);
		dp.mfd_window1[i].W_C = COLOR(input_data.mfd_window1[i].y, 0);
		switch (input_data.mfd_window1[i].x & 0x0003) {
		case 0:
			dp.mfd_window1[i].W_A[0] = font6_code[
				(input_data.mfd_window1[i].cha >> 8) & 0x00ff];
			dp.mfd_window1[i].W_A[1] = font6_code[
				input_data.mfd_window1[i].cha & 0x00ff];
			dp.mfd_window1[i].W_A[2] = font6_code[
				(input_data.mfd_window1[i].chb >> 8) & 0x00ff];
			dp.mfd_window1[i].W_A[3] = font6_code[
				input_data.mfd_window1[i].chb & 0x00ff];
			SET_PABIT(PA_MW00+i);
			break;
		case 1:
			dp.mfd_window1[i].W_A[0] = font7_code[
				(input_data.mfd_window1[i].cha >> 8) & 0x00ff];
			dp.mfd_window1[i].W_A[1] = font7_code[
				input_data.mfd_window1[i].cha & 0x00ff];
			dp.mfd_window1[i].W_A[2] = font7_code[
				(input_data.mfd_window1[i].chb >> 8) & 0x00ff];
			dp.mfd_window1[i].W_A[3] = font7_code[
				input_data.mfd_window1[i].chb & 0x00ff];
			SET_PABIT(PA_MW00+i);
			break;
		case 2:
			dp.mfd_window1[i].W_A[0] = font8_code[
				(input_data.mfd_window1[i].cha >> 8) & 0x00ff];
			dp.mfd_window1[i].W_A[1] = font8_code[
				input_data.mfd_window1[i].cha & 0x00ff];
			dp.mfd_window1[i].W_A[2] = font8_code[
				(input_data.mfd_window1[i].chb >> 8) & 0x00ff];
			dp.mfd_window1[i].W_A[3] = font8_code[
				input_data.mfd_window1[i].chb & 0x00ff];
			SET_PABIT(PA_MW00+i);
			break;
		}
	}

	/* 20Hz */
	for (i = 0; i < 15; i++) {
		if ((input_data.mfd_window2[i].y & 0x000f) == 0)
			continue;
		dp.mfd_window2[i].W_PX =
			POSITION((short)(input_data.mfd_window2[i].x & 0xffe0),
			204.8);
		dp.mfd_window2[i].W_PY =
			POSITION((short)(input_data.mfd_window2[i].y & 0xffe0),
			204.8);
		dp.mfd_window2[i].W_C = COLOR(input_data.mfd_window2[i].y, 0);
		switch (input_data.mfd_window2[i].x & 0x0003) {
		case 0:
			dp.mfd_window2[i].W_A[0] = font6_code[
				(input_data.mfd_window2[i].cha >> 8) & 0x00ff];
			dp.mfd_window2[i].W_A[1] = font6_code[
				input_data.mfd_window2[i].cha & 0x00ff];
			dp.mfd_window2[i].W_A[2] = font6_code[
				(input_data.mfd_window2[i].chb >> 8) & 0x00ff];
			dp.mfd_window2[i].W_A[3] = font6_code[
				input_data.mfd_window2[i].chb & 0x00ff];
			SET_PABIT(PA_MW80+i);
			break;
		case 1:
			dp.mfd_window2[i].W_A[0] = font7_code[
				(input_data.mfd_window2[i].cha >> 8) & 0x00ff];
			dp.mfd_window2[i].W_A[1] = font7_code[
				input_data.mfd_window2[i].cha & 0x00ff];
			dp.mfd_window2[i].W_A[2] = font7_code[
				(input_data.mfd_window2[i].chb >> 8) & 0x00ff];
			dp.mfd_window2[i].W_A[3] = font7_code[
				input_data.mfd_window2[i].chb & 0x00ff];
			SET_PABIT(PA_MW80+i);
			break;
		case 2:
			dp.mfd_window2[i].W_A[0] = font8_code[
				(input_data.mfd_window2[i].cha >> 8) & 0x00ff];
			dp.mfd_window2[i].W_A[1] = font8_code[
				input_data.mfd_window2[i].cha & 0x00ff];
			dp.mfd_window2[i].W_A[2] = font8_code[
				(input_data.mfd_window2[i].chb >> 8) & 0x00ff];
			dp.mfd_window2[i].W_A[3] = font8_code[
				input_data.mfd_window2[i].chb & 0x00ff];
			SET_PABIT(PA_MW80+i);
			break;
		}
	}
}

/*=======================================================================
 |
 |		更新レート表示
 |
 |	void	dp_update_rate()
 |
 =======================================================================*/
void	dp_update_rate(void)
{
	short	tmp[2];

	/* 表示の更新レートセット */
	tonumR(rate_gscint, tmp, 2, font8_tbl, FONT8_0);
	dp.T70A[0] = tmp[1];
	dp.T70A[1] = tmp[0];
	dp.PX70 = MM_TO_DP(4000);
	dp.PY70 = MM_TO_DP(4500);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_TEST7A);

	/* １５５３Ｂバスの更新レートセット */
	tonumR(rate_1553B, tmp, 2, font8_tbl, FONT8_0);
	dp.T71A[0] = tmp[1];
	dp.T71A[1] = tmp[0];
	dp.PX71 = MM_TO_DP(4000);
	dp.PY71 = MM_TO_DP(4000);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_TEST7B);
}
