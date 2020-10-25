/************************************************************************
 *									*
 *		Ç`ÇbÇdÇrÅ@ÇrÅ^ÇfÅ@ÇbÇoÇtÉvÉçÉOÉâÉÄ			*
 *									*
 *		ñºèÃ		: ÇeÇbÇqÉÇÅ[Éh				*
 *				  É_ÉCÉiÉ~ÉbÉNÅEÉpÉâÉÅÅ[É^åvéZèàóù	*
 *		ÉtÉ@ÉCÉãñº	: dp_fcr.c				*
 *									*
 ************************************************************************/

#pragma	SECTION PROG=dp_fcr
#pragma	SECTION CONST=dp_fcr
#pragma	SECTION DATA=dp_fcr

#include "sg_cpu.h"

static	void	dp_M001(void);
static	void	dp_M002(void);
static	void	dp_M003(void);
static	void	dp_M004(void);
static	void	dp_M005(void);
static	void	dp_M006(void);
static	void	dp_M007(void);
static	void	dp_M008(void);
static	void	dp_M009(void);
static	void	dp_M010(void);
static	void	dp_M011(void);
static	void	dp_M012(void);
static	void	dp_M013(void);
static	void	dp_M014(void);
static	void	dp_M015(void);
static	void	dp_M016(void);
static	void	dp_M017(void);
static	void	dp_M018(void);
static	void	dp_M019(void);
static	void	dp_M020(void);
static	void	dp_M021(void);
static	void	dp_M022(void);
static	void	dp_M023(void);
static	void	dp_M024(void);

/*=======================================================================
 |
 |		ÇeÇbÇqÉÇÅ[ÉhÅ@É_ÉCÉiÉ~ÉbÉNÅEÉpÉâÉÅÅ[É^åvéZèàóù
 |
 |	void	dp_fcr()
 |
 =======================================================================*/
void	dp_fcr(void)
{
	dp_M001();	/* Aircraft Reference Symbol */
	dp_M002();	/* Artificial Horizon Line Symbol */
	dp_M003();	/* Antenna Elevetion Scale & Index Marker */
	dp_M004();	/* Antenna Azimuth Scale & Index Marker */
	dp_M005();	/* Breakaway Symbol */
	dp_M006();	/* Allowable Steering Error (ASE) Circle */
	dp_M007();	/* Steering dot */
	dp_M008();	/* R Max, R Max2, R Min, reference Ranges */
	dp_M009();	/* Acquisition Symbol */
	dp_M010();	/* Range and Azimuth Referecne Grid */
	dp_M011();	/* Range Reference Marks */
	dp_M012();	/* Aircraft to Target Reference Line */
	dp_M013();	/* FCR Format Window */
	dp_M014();	/* Range Marks */
	dp_M015();	/* 5NM Reference Mark */
	dp_M016();	/* X-Y Cursor */
	dp_M017();	/* Frequency Channel Indicator */
	dp_M018();	/* Target Symbol */
	dp_M019();	/* Aircraft Position / Altitude Line Symbol */
	dp_M020();	/* Antenna Position Symbol */
	dp_M021();	/* Reaquisition Symbol */
	dp_M022();	/* ASM circle */
	dp_M023();	/* MI CUE (ECCM) */
	dp_M024();	/* AOJ Strobe (ECCM) */
}

/*=======================================================================
 |
 |		Aircraft Reference Symbol
 |
 |	void	dp_M001()
 |
 =======================================================================*/
static	void	dp_M001(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol4 & 0x8000) {
		dp.C001 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol4, 11);
		SET_PABIT(PA_M001);
	}
}

/*=======================================================================
 |
 |		Artificial Horizon Line Symbol
 |
 |	void	dp_M002()
 |
 =======================================================================*/
static	void	dp_M002(void)
{
	short	pitch;

	if (CHECK_COLOR(input_data.moving_symbol1.art_horizon_color, 12) == 0)
		return;

	pitch = POSITION(filter_data.moving_symbol1.art_horizon_pitch, 140.4);
	dp.LY002 = MM_TO_DP(4680) -
			LIMIT(pitch, -MM_TO_DP(4680), MM_TO_DP(4680));
	dp.R002 = ANGLE(filter_data.moving_symbol1.art_horizon_roll);
	dp.C002 = COLOR(input_data.moving_symbol1.art_horizon_color, 12);
	SET_PABIT(PA_M002);
}

/*=======================================================================
 |
 |		Antenna Elevetion Scale & Index Marker
 |
 |	void	dp_M003()
 |
 =======================================================================*/
static	void	dp_M003(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.art_horizon_color, 4) != 0) {
		dp.C003A = COLOR(input_data.moving_symbol1.art_horizon_color,
									4);
		SET_PABIT(PA_M003A0);
		SET_PABIT(PA_M003A1);
	}

	if (CHECK_COLOR(input_data.moving_symbol1.ant_scale_el, 0) != 0) {
		dp.PY003B = POSITION(LIMIT(
				filter_data.moving_symbol1.ant_scale_el,
				-13440, 13440), 204.8);
		dp.C003B = COLOR(input_data.moving_symbol1.ant_scale_el, 0);
		SET_PABIT(PA_M003B0);
		SET_PABIT(PA_M003B1);
	}
}

/*=======================================================================
 |
 |		Antenna Azimuth Scale & Index Marker
 |
 |	void	dp_M004()
 |
 =======================================================================*/
static	void	dp_M004(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.art_horizon_color, 8) != 0) {
		dp.C004A = COLOR(input_data.moving_symbol1.art_horizon_color,										8);
		SET_PABIT(PA_M004A0);
		SET_PABIT(PA_M004A1);
	}

	if (CHECK_COLOR(input_data.moving_symbol1.ant_scale_az, 0) != 0) {
		dp.PX004B = POSITION(LIMIT(
				filter_data.moving_symbol1.ant_scale_az,
				-13440, 13440), 204.8);
		dp.C004B = COLOR(input_data.moving_symbol1.ant_scale_az, 0);
		SET_PABIT(PA_M004B0);
		SET_PABIT(PA_M004B1);
	}
}

/*=======================================================================
 |
 |		Breakaway Symbol
 |
 |	void	dp_M005()
 |
 =======================================================================*/
static	void	dp_M005(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol1 & 0x8000) {
		dp.C005 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol1, 11);
		SET_PABIT(PA_M0050);
		SET_PABIT(PA_M0051);
	}
}

/*=======================================================================
 |
 |		Allowable Steering Error (ASE) Circle
 |
 |	void	dp_M006()
 |
 =======================================================================*/
static	void	dp_M006(void)
{
	ushort	rad;

	if (CHECK_COLOR(input_data.moving_symbol1.ase_circle_radius, 0) == 0)
		return;

	if ((input_data.moving_symbol1.ase_circle_radius & 0xffc0) == 0)
		return;

	rad = filter_data.moving_symbol1.ase_circle_radius;
	if (rad < (8 << 6))
		rad = 8 << 6;
	dp.PX006 = POSITION(filter_data.moving_symbol1.ase_circle_x, 204.8);
	dp.PY006 = POSITION(filter_data.moving_symbol1.ase_circle_y -
		(short)(rad >> 1), 204.8);
	dp.L006 = LENGTH(rad, 102.8*2*PI);
	dp.C006 = COLOR(input_data.moving_symbol1.ase_circle_radius, 0);
	dp.R006 = RADIUS(rad, 102.4);
	if (rad < (16 << 6))
		SET_PABIT(PA_M006B);
	else
		SET_PABIT(PA_M006);
}

/*=======================================================================
 |
 |		Steering dot
 |
 |	void	dp_M007()
 |
 =======================================================================*/
static	void	dp_M007(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.steering_dot_y, 0) == 0)
		return;

	dp.PX007 = POSITION(filter_data.moving_symbol1.steering_dot_x, 204.8);
	dp.PY007 = POSITION(filter_data.moving_symbol1.steering_dot_y, 204.8);
	dp.C007 = COLOR(input_data.moving_symbol1.steering_dot_y, 0);
	SET_PABIT(PA_M007);
}

/*=======================================================================
 |
 |		R Max, R Max2, R Min, reference Ranges
 |
 |	void	dp_M008()
 |
 =======================================================================*/
static	void	dp_M008(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.r_max_y, 0) != 0) {
		dp.PX008A = POSITION(filter_data.moving_symbol1.r_max_x,
								204.8);
		dp.PY008A = POSITION(filter_data.moving_symbol1.r_max_y,
								204.8);
		dp.C008A = COLOR(input_data.moving_symbol1.r_max_y, 0);
		SET_PABIT(PA_M008A);
	}

	if (CHECK_COLOR(input_data.moving_symbol1.r_max2_y, 0) != 0) {
		dp.PX008B = POSITION(filter_data.moving_symbol1.r_max2_x,
								204.8);
		dp.PY008B = POSITION(filter_data.moving_symbol1.r_max2_y,
								204.8);
		dp.C008B = COLOR(input_data.moving_symbol1.r_max2_y, 0);
		SET_PABIT(PA_M008B);
	}

	if (CHECK_COLOR(input_data.moving_symbol1.r_min_y, 0) != 0) {
		dp.PX008C = POSITION(filter_data.moving_symbol1.r_min_x,
								204.8);
		dp.PY008C = POSITION(filter_data.moving_symbol1.r_min_y,
								204.8);
		dp.C008C = COLOR(input_data.moving_symbol1.r_min_y, 0);
		SET_PABIT(PA_M008C);
	}
}

/*=======================================================================
 |
 |		Acquisition Symbol
 |
 |	void	dp_M009()
 |
 =======================================================================*/
static	void	dp_M009(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.acq_symbol_y, 0) == 0)
		return;

	dp.PX009 = POSITION(filter_data.moving_symbol1.acq_symbol_x, 204.8);
	dp.PY009 = POSITION(filter_data.moving_symbol1.acq_symbol_y, 204.8);
	dp.C009 = COLOR(input_data.moving_symbol1.acq_symbol_y, 0);
	SET_PABIT(PA_M0090);
	SET_PABIT(PA_M0091);
}

/*=======================================================================
 |
 |		Range and Azimuth Referecne Grid
 |
 |	void	dp_M010()
 |
 =======================================================================*/
static	void	dp_M010(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol1 & 0x0400) {
		dp.C010 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol1, 6);
		SET_PABIT(PA_M010);
	}
}

/*=======================================================================
 |
 |		Range Reference Marks
 |
 |	void	dp_M011()
 |
 =======================================================================*/
static	void	dp_M011(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol4 & 0x0400) {
		dp.C011 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol4, 6);
		SET_PABIT(PA_M011);
	}
}

/*=======================================================================
 |
 |		Aircraft to Target Reference Line
 |
 |	void	dp_M012()
 |
 =======================================================================*/
static	void	dp_M012(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.ac_to_tgt_line_angle,0) == 0)
		return;

	dp.PX012 = POSITION(filter_data.moving_symbol1.ac_to_tgt_line_x,
							204.8);
	dp.PY012 = POSITION(filter_data.moving_symbol1.ac_to_tgt_line_y,
							204.8);
	dp.R012 = ANGLE(0x4000 -
		(filter_data.moving_symbol1.ac_to_tgt_line_angle >> 1));
	dp.L012 = LENGTH(filter_data.moving_symbol1.ac_to_tgt_line_length,
							204.8);
	dp.C012 = COLOR(input_data.moving_symbol1.ac_to_tgt_line_angle, 0);
	SET_PABIT(PA_M012);
}

/*=======================================================================
 |
 |		FCR Format Window
 |
 |	void	dp_M013()
 |
 =======================================================================*/
static	void	dp_M013(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol3 & 0x0100) {
		dp.C013 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol3, 4);
		SET_PABIT(PA_M0130);
		SET_PABIT(PA_M0131);
	}
}

/*=======================================================================
 |
 |		Range Marks
 |
 |	void	dp_M014()
 |
 =======================================================================*/
static	void	dp_M014(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol2 & 0x2000) {
		dp.C014 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol2, 9);
		SET_PABIT(PA_M014A);
	}

	if (input_data.fixed_symbol.fcr_fixed_symbol2 & 0x4000) {
		dp.C014 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol2, 9);
		SET_PABIT(PA_M014B);
	}

	if (input_data.fixed_symbol.fcr_fixed_symbol2 & 0x8000) {
		dp.C014 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol2, 9);
		SET_PABIT(PA_M014C);
	}
}

/*=======================================================================
 |
 |		5NM Reference Mark
 |
 |	void	dp_M015()
 |
 =======================================================================*/
static	void	dp_M015(void)
{
	if (input_data.fixed_symbol.fcr_fixed_symbol2 & 0x0100) {
		dp.C015 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol2, 4);
		SET_PABIT(PA_M015);
	}
}

/*=======================================================================
 |
 |		X-Y Cursor
 |
 |	void	dp_M016()
 |
 =======================================================================*/
static	void	dp_M016(void)
{
	if (CHECK_COLOR(input_data.moving_symbol2.xy_cursor_y, 0) == 0)
		return;

	dp.PX016 = POSITION(filter_data.moving_symbol2.xy_cursor_x, 204.8);
	dp.PY016 = POSITION(filter_data.moving_symbol2.xy_cursor_y, 204.8);
	dp.C016 = COLOR(input_data.moving_symbol2.xy_cursor_y, 0);
	SET_PABIT(PA_M016);
}

/*=======================================================================
 |
 |		Frequency Channel Indicator
 |
 |	void	dp_M017()
 |
 =======================================================================*/
static	void	dp_M017(void)
{
	dp.C017 = COLOR(input_data.fixed_symbol.fcr_fixed_symbol3, 9);
	switch (input_data.fixed_symbol.fcr_fixed_symbol3 & 0xe000) {
	case 0x2000:	/* 1 */
		SET_PABIT(PA_M017A);
		break;
	case 0x4000:	/* 2 */
		SET_PABIT(PA_M017B);
		break;
	case 0x6000:	/* 3 */
		SET_PABIT(PA_M017C);
		break;
	case 0x8000:	/* 4 */
		SET_PABIT(PA_M017D);
		break;
	}
}

/*=======================================================================
 |
 |		Target Symbol
 |
 |	void	dp_M018()
 |
 =======================================================================*/
static	void	dp_M018(void)
{
	int	i;

	for (i = 0; i < 64; i++) {
		if (CHECK_COLOR(input_data.fcr_target[i].y, 0) != 0 &&
				filter_data.fcr_target[i].x < 14976 &&
				filter_data.fcr_target[i].x > -14976 &&
				filter_data.fcr_target[i].y < 14976 &&
				filter_data.fcr_target[i].y > -14976) {
			dp.fcr_target[i].PX018 =
				POSITION(filter_data.fcr_target[i].x, 204.8);
			dp.fcr_target[i].PY018 =
				POSITION(filter_data.fcr_target[i].y, 204.8);
			if ((input_data.fcr_target[i].x & 0x0001) == 0)
				dp.fcr_target[i].R018 = 0;
			else
				dp.fcr_target[i].R018 = 0x2000;
			dp.fcr_target[i].C018 =
					COLOR(input_data.fcr_target[i].y, 0);
			SET_PABIT(PA_MB18A0+i*2);
			SET_PABIT(PA_M018A0+i*2);
		}
	}
}

/*=======================================================================
 |
 |		Aircraft Position / Altitude Line Symbol
 |
 |	void	dp_M019()
 |
 =======================================================================*/
static	void	dp_M019(void)
{
	if (CHECK_COLOR(input_data.moving_symbol2.ac_alt_line_y, 0) == 0)
		return;

	dp.PX019 = POSITION(filter_data.moving_symbol2.ac_alt_line_x, 204.8);
	dp.PY019 = POSITION(filter_data.moving_symbol2.ac_alt_line_y, 204.8);
	dp.C019 = COLOR(input_data.moving_symbol2.ac_alt_line_y, 0);
	SET_PABIT(PA_M019);
}

/*=======================================================================
 |
 |		Antenna Position Symbol
 |
 |	void	dp_M020()
 |
 =======================================================================*/
static	void	dp_M020(void)
{
	if (CHECK_COLOR(input_data.moving_symbol2.ant_position_y, 0) == 0)
		return;

	dp.PX020 = POSITION(filter_data.moving_symbol2.ant_position_x, 204.8);
	dp.PY020 = POSITION(filter_data.moving_symbol2.ant_position_y, 204.8);
	dp.C020 = COLOR(input_data.moving_symbol2.ant_position_y, 0);
	SET_PABIT(PA_M020);
}

/*=======================================================================
 |
 |		Reaquisition Symbol
 |
 |	void	dp_M021()
 |
 =======================================================================*/
static	void	dp_M021(void)
{
	if (CHECK_COLOR(input_data.moving_symbol2.reacq_symbol_y, 0) == 0)
		return;

	dp.PX021 = POSITION(filter_data.moving_symbol2.reacq_symbol_x, 204.8);
	dp.PY021 = POSITION(filter_data.moving_symbol2.reacq_symbol_y, 204.8);
	dp.C021 = COLOR(input_data.moving_symbol2.reacq_symbol_y, 0);
	SET_PABIT(PA_M0210);
	SET_PABIT(PA_M0211);
}

/*=======================================================================
 |
 |		ASM Circle
 |
 |	void	dp_M022()
 |
 =======================================================================*/
static	void	dp_M022(void)
{
	if (CHECK_COLOR(input_data.moving_symbol1.asm_circle_y, 0) == 0)
		return;

	dp.PX022 = POSITION(filter_data.moving_symbol1.asm_circle_x, 204.8);
	dp.PY022 = POSITION(filter_data.moving_symbol1.asm_circle_y, 204.8);
	dp.C022 = COLOR(input_data.moving_symbol1.asm_circle_y, 0);
	SET_PABIT(PA_M022);
}

/*=======================================================================
 |
 |		MI CUE (ECCM)
 |
 |	void	dp_M023()
 |
 =======================================================================*/
static	void	dp_M023(void)
{
	if (input_data.moving_symbol2.mi_cue_eccm & 0x8000) {
		dp.C023 = COLOR(input_data.moving_symbol2.mi_cue_eccm, 0);
		SET_PABIT(PA_M0230);
		SET_PABIT(PA_M0231);
	}
}

/*=======================================================================
 |
 |		AOJ Strobe (ECCM)
 |
 |	void	dp_M024()
 |
 =======================================================================*/
static	void	dp_M024(void)
{
	if (CHECK_COLOR(input_data.moving_symbol2.aoj_strobe_eccm, 0) == 0)
		return;

	dp.PX024 = POSITION(filter_data.moving_symbol2.aoj_strobe_eccm, 204.8);
	dp.C024 = COLOR(input_data.moving_symbol2.aoj_strobe_eccm, 0);
	SET_PABIT(PA_M0240);
	SET_PABIT(PA_M0241);
}
