/************************************************************************
 *									*
 *		‚`‚b‚d‚r@‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€			*
 *									*
 *		–¼Ì		: ‚g‚r‚hƒ‚[ƒh				*
 *				  ƒ_ƒCƒiƒ~ƒbƒNEƒpƒ‰ƒ[ƒ^ŒvŽZˆ—	*
 *		ƒtƒ@ƒCƒ‹–¼	: dp_hsi.c				*
 *									*
 ************************************************************************/

#pragma	SECTION PROG=dp_hsi
#pragma	SECTION CONST=dp_hsi
#pragma	SECTION DATA=dp_hsi

#include "sg_cpu.h"

static	void	dp_M051(void);
static	void	dp_M052(void);
static	void	dp_M053(void);
static	void	dp_M054(void);
static	void	dp_M055(void);
static	void	dp_M056(void);
static	void	dp_M057(void);
static	void	dp_M058(void);
static	void	dp_M059(void);
static	void	dp_M060(void);
static	void	dp_M061(void);
static	void	dp_M062(void);

/*=======================================================================
 |
 |		‚g‚r‚hƒ‚[ƒh@ƒ_ƒCƒiƒ~ƒbƒNEƒpƒ‰ƒ[ƒ^ŒvŽZˆ—
 |
 |	void	dp_hsi()
 |
 =======================================================================*/
void	dp_hsi(void)
{
	dp_M051();	/* STPT Bearing Pointer */
	dp_M052();	/* Present Magnetic Heading, Compass Rose */
	dp_M053();	/* Graund Track */
	dp_M054();	/* Command Course */
	dp_M055();	/* Designated Point Bearing Pointer */
	dp_M056();	/* Glide Slope Scale */
	dp_M057();	/* TACAN Bearing Pointer */
	dp_M058();	/* Localizer Scale */
	dp_M059();	/* Deviation Bar */
	dp_M060();	/* Airplane Symbol */
	dp_M061();	/* Heading Pointer */
	dp_M062();	/* Percent Trim Indicator */
}

/*=======================================================================
 |
 |		STPT Bearing Pointer
 |
 |	void	dp_M051()
 |
 =======================================================================*/
static	void	dp_M051(void)
{
	short	stpt_number;

	if ((input_data.hsi_symbol.symbol_status & 0x8000) == 0)
		return;

	dp.R051 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.stpt_bearing);
	dp.C051A0 = COLOR(input_data.hsi_symbol.color_code1, 8);
	dp.C051A1 = COLOR(input_data.hsi_symbol.color_code1, 4);
	SET_PABIT(PA_M051A);

	dp.C051B = COLOR(input_data.hsi_symbol.color_code1, 12);
	stpt_number = (input_data.hsi_symbol.stpt_number >> 11) & 0x1f;
	if (stpt_number >= 1 && stpt_number <= 11)
		tonumR(stpt_number, dp.A051B, 2, font6_tbl, FONT6_SP);
	else if (stpt_number == 26) {
		dp.A051B[1] = FONT6_M;
		dp.A051B[0] = FONT6_1;
	} else if (stpt_number == 27) {
		dp.A051B[1] = FONT6_M;
		dp.A051B[0] = FONT6_2;
	} else if (stpt_number == 28) {
		dp.A051B[1] = FONT6_M;
		dp.A051B[0] = FONT6_3;
	} else if (stpt_number == 31) {
		dp.A051B[1] = FONT6_SP;
		dp.A051B[0] = FONT6_B;
	} else {
		dp.A051B[1] = FONT6_SP;
		dp.A051B[0] = FONT6_SP;
	}
	SET_PABIT(PA_M051B);
}

/*=======================================================================
 |
 |		Present Magnetic Heading, Compass Rose
 |
 |	void	dp_M052()
 |
 =======================================================================*/
static	void	dp_M052(void)
{
	ushort	heading;

	if ((input_data.hsi_symbol.symbol_status & 0x4000) == 0)
		return;

	dp.C052A0 = COLOR(input_data.hsi_symbol.color_code2, 4);
	dp.C052A1 = COLOR(input_data.hsi_symbol.color_code2, 8);
	if (digital_update_flag) {
		heading = (ushort)(((ushort)filter_data.hsi_symbol.
			present_mag_heading * 360L + 0x8000L) >> 16);
		if (heading >= 360)
			heading -= 360;
		tonumR(heading, dp.A052A, 3, font7_tbl, FONT7_0);
	}
	SET_PABIT(PA_M052A);

	dp.C052B = COLOR(input_data.hsi_symbol.color_code2, 12);
	SET_PABIT(PA_M052B);

	dp.R052C = ANGLE(filter_data.hsi_symbol.present_mag_heading);
	dp.C052C = COLOR(input_data.hsi_symbol.color_code1, 0);
	SET_PABIT(PA_M052C);

	SET_PABIT(PA_M052D);
	SET_PABIT(PA_M052E);
	SET_PABIT(PA_M052F);
	SET_PABIT(PA_M052G);
}

/*=======================================================================
 |
 |		Graund Track
 |
 |	void	dp_M053()
 |
 =======================================================================*/
static	void	dp_M053(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x2000) == 0)
		return;

	dp.R053 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.ground_track);
	dp.C053 = COLOR(input_data.hsi_symbol.color_code2, 0);
	SET_PABIT(PA_M053);
}

/*=======================================================================
 |
 |		Command Course
 |
 |	void	dp_M054()
 |
 =======================================================================*/
static	void	dp_M054(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x1000) == 0)
		return;

	dp.R054 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.comamnd_course);
	dp.C0540 = COLOR(input_data.hsi_symbol.color_code3, 12);
	dp.C0541 = COLOR(input_data.hsi_symbol.color_code3, 8);
	SET_PABIT(PA_M054);
}

/*=======================================================================
 |
 |		Designated Point Bearing Pointer
 |
 |	void	dp_M055()
 |
 =======================================================================*/
static	void	dp_M055(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x0800) == 0)
		return;

	dp.R055 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.designated_point_brg);
	dp.C055 = COLOR(input_data.hsi_symbol.color_code3, 4);
	SET_PABIT(PA_M055);
}

/*=======================================================================
 |
 |		Glide Slope Scale
 |
 |	void	dp_M056()
 |
 =======================================================================*/
static	void	dp_M056(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x0400) == 0)
		return;

	dp.C056A = COLOR(input_data.hsi_symbol.color_code3, 0);
	SET_PABIT(PA_M056A0);
	SET_PABIT(PA_M056A1);
	SET_PABIT(PA_M056A2);
	SET_PABIT(PA_M056A3);
	SET_PABIT(PA_M056A4);
	SET_PABIT(PA_M056A5);

	dp.L056B = MM_TO_DP(2490) +
		POSITION(LIMIT(filter_data.hsi_symbol.glide_slope,
			-8960, 8960), 151.4);
	dp.C056B = COLOR(input_data.hsi_symbol.color_code4, 12);
	SET_PABIT(PA_M056B0);
	SET_PABIT(PA_M056B1);
}

/*=======================================================================
 |
 |		TACAN Bearing Pointer
 |
 |	void	dp_M057()
 |
 =======================================================================*/
static	void	dp_M057(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x0200) == 0)
		return;

	dp.R057 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.tacan_bearing_pointer);
	dp.C057A0 = COLOR(input_data.hsi_symbol.color_code4, 8);
	dp.C057A1 = COLOR(input_data.hsi_symbol.color_code4, 4);
	SET_PABIT(PA_M057A0);
	SET_PABIT(PA_M057A1);

	dp.C057B = COLOR(input_data.hsi_symbol.color_code4, 0);
	SET_PABIT(PA_M057B);
}

/*=======================================================================
 |
 |		Localizer Scale
 |
 |	void	dp_M058()
 |
 =======================================================================*/
static	void	dp_M058(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x0100) == 0)
		return;

	dp.C058A = COLOR(input_data.hsi_symbol.color_code5, 12);
	SET_PABIT(PA_M058A0);
	SET_PABIT(PA_M058A1);
	SET_PABIT(PA_M058A2);
	SET_PABIT(PA_M058A3);
	SET_PABIT(PA_M058A4);
	SET_PABIT(PA_M058A5);

	if ((input_data.hsi_symbol.localizer & 0x0001) == 0) {
		dp.L058B = MM_TO_DP(2490) +
			POSITION(LIMIT(filter_data.hsi_symbol.localizer,
			-16000, 16000),	84.7872);
	} else {
		dp.L058B = MM_TO_DP(2490) +
			POSITION(LIMIT(filter_data.hsi_symbol.localizer,
			-32000, 32000),	42.3936);
	}
	dp.C058B = COLOR(input_data.hsi_symbol.color_code5, 8);
	SET_PABIT(PA_M058B0);
	SET_PABIT(PA_M058B1);
}

/*=======================================================================
 |
 |		Deviation Bar
 |
 |	void	dp_M059()
 |
 =======================================================================*/
static	void	dp_M059(void)
{
	if (input_data.hsi_symbol.symbol_status & 0x0080) {
		if ((input_data.hsi_symbol.deviation_bar & 0x0001) == 0) {
			dp.LX059A = MM_TO_DP(1530) +
			  POSITION(LIMIT(filter_data.hsi_symbol.deviation_bar,
			  -16000, 16000), 62.6688);
			if (dp.LX059A < 0)
				dp.LX059A = 0;
		} else {
			dp.LX059A = MM_TO_DP(1530) +
			  POSITION(LIMIT(filter_data.hsi_symbol.deviation_bar,
			  -32000, 32000), 31.3344);
			if (dp.LX059A < 0)
				dp.LX059A = 0;
		}
		dp.R054 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.comamnd_course);
		dp.C059A = COLOR(input_data.hsi_symbol.color_code5, 4);
		SET_PABIT(PA_M059A0);
		SET_PABIT(PA_M059A1);
	}

	if (input_data.hsi_symbol.symbol_status & 0x0040) {
		dp.C059B = COLOR(input_data.hsi_symbol.color_code5, 0);
		SET_PABIT(PA_M059B0);
		SET_PABIT(PA_M059B1);
		SET_PABIT(PA_M059B2);
		SET_PABIT(PA_M059B3);
	}
}

/*=======================================================================
 |
 |		Airplane Symbol
 |
 |	void	dp_M060()
 |
 =======================================================================*/
static	void	dp_M060(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x0020) == 0)
		return;

	dp.C060 = COLOR(input_data.hsi_symbol.color_code6, 12);
	SET_PABIT(PA_M060);
}

/*=======================================================================
 |
 |		Heading Pointer
 |
 |	void	dp_M061()
 |
 =======================================================================*/
static	void	dp_M061(void)
{
	if ((input_data.hsi_symbol.symbol_status & 0x0010) == 0)
		return;

	dp.R061 = ANGLE(filter_data.hsi_symbol.present_mag_heading -
			filter_data.hsi_symbol.heading_pointer);
	dp.C061 = COLOR(input_data.hsi_symbol.color_code6, 8);
	SET_PABIT(PA_M0610);
	SET_PABIT(PA_M0611);
}

/*=======================================================================
 |
 |		Percent Trim Indicator
 |
 |	void	dp_M062()
 |
 =======================================================================*/
static	void	dp_M062(void)
{
	short	tmp;

	if ((input_data.hsi_symbol.symbol_status & 0x0008) == 0)
		return;

	dp.L062A = MM_TO_DP(885) +
			POSITION(LIMIT(filter_data.hsi_symbol.trim_1,
			-100*256, 100*256), 16.8/200*256);
	dp.C062A0 = COLOR(input_data.hsi_symbol.color_code6, 4);
	dp.C062A = COLOR(input_data.hsi_symbol.color_code6, 0);
	SET_PABIT(PA_M062A);

	dp.L062B = MM_TO_DP(885) +
			POSITION(LIMIT(filter_data.hsi_roll_trim,
			-100*256, 100*256), 16.8/200*256);
	dp.C062B0 = COLOR(input_data.hsi_symbol.color_code6, 4);
	dp.C062B = COLOR(input_data.hsi_symbol.color_code7, 12);
	SET_PABIT(PA_M062B);

	dp.C062C = COLOR(input_data.hsi_symbol.color_code6, 4);
	SET_PABIT(PA_M062C);

	tmp = LIMIT(filter_data.hsi_symbol.trim_2, -100*256, 100*256);
	if (tmp >= 0) {
		dp.L062D0 = LENGTH(tmp, ((10.8-0.3)*2*PI/3.6)/200*256);
		dp.L062D1 = LENGTH(tmp, ((10.8-0.6)*2*PI/3.6)/200*256);
		dp.L062D2 = LENGTH(tmp, ((10.8-0.9)*2*PI/3.6)/200*256);
		dp.C062D = COLOR(input_data.hsi_symbol.color_code7, 8);
		SET_PABIT(PA_M062D);
	} else {
		tmp = -tmp;
		dp.L062D0 = LENGTH(tmp, ((10.8-0.3)*2*PI/3.6)/200*256);
		dp.L062D1 = LENGTH(tmp, ((10.8-0.6)*2*PI/3.6)/200*256);
		dp.L062D2 = LENGTH(tmp, ((10.8-0.9)*2*PI/3.6)/200*256);
		dp.C062D = COLOR(input_data.hsi_symbol.color_code7, 8);
		SET_PABIT(PA_M062E);
	}
}
