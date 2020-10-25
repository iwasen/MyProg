/************************************************************************
 *									*
 *		‚`‚b‚d‚r@‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€			*
 *									*
 *		–¼Ì		: ‚`‚c‚hƒ‚[ƒh				*
 *				  ƒ_ƒCƒiƒ~ƒbƒNEƒpƒ‰ƒ[ƒ^ŒvŽZˆ—	*
 *		ƒtƒ@ƒCƒ‹–¼	: dp_adi.c				*
 *									*
 ************************************************************************/

#pragma	SECTION PROG=dp_adi
#pragma	SECTION CONST=dp_adi
#pragma	SECTION DATA=dp_adi

#include "sg_cpu.h"

static	void	dp_M065(void);
static	void	dp_M066(void);
static	void	dp_M067(void);
static	void	dp_M068(void);
static	void	dp_M069(void);
static	void	dp_M070(void);
static	void	dp_M071(void);
static	void	dp_M072(void);
static	void	dp_M073(void);
static	void	dp_M074(void);
static	void	dp_M075(void);
static	void	dp_M076(void);
static	void	dp_M077(void);

/*=======================================================================
 |
 |		‚`‚c‚hƒ‚[ƒh@ƒ_ƒCƒiƒ~ƒbƒNEƒpƒ‰ƒ[ƒ^ŒvŽZˆ—
 |
 |	void	dp_adi()
 |
 =======================================================================*/
void	dp_adi(void)
{
	dp_M065();	/* Present Magnetic Heading */
	dp_M066();	/* Command Course Marker */
	dp_M067();	/* Roll Indices & Pointer */
	dp_M068();	/* Pitch Scale */
	dp_M069();	/* Flight Path Marker */
	dp_M070();	/* Present Altitude, Scale */
	dp_M071();	/* Glide Slope Scale */
	dp_M072();	/* Vertical Velocity Scale */
	dp_M073();	/* Locator Scale */
	dp_M074();	/* AOA Scale */
	dp_M075();	/* Present Airspeed */
	dp_M076();	/* Airplane Symbol */
	dp_M077();	/* Percent Trim Indicator */
}

/*=======================================================================
 |
 |		Present Magnetic Heading
 |
 |	void	dp_M065()
 |
 =======================================================================*/
static	void	dp_M065(void)
{
	ushort	heading;
	short	tmp;
	short	*p;
	int	i;

	if ((input_data.adi_symbol.symbol_status & 0x8000) == 0)
		return;

	dp.C065A0 = COLOR(input_data.adi_symbol.color_code1, 12);
	dp.C065A1 = COLOR(input_data.adi_symbol.color_code1, 8);
	if (digital_update_flag) {
		heading = (ushort)(((ushort)filter_data.adi_symbol.
			present_mag_heading * 360L + 0x8000L) >> 16);
		if (heading >= 360)
			heading -= 360;
		tonumR(heading, dp.A065A, 3, font7_tbl, FONT7_0);
	}
	SET_PABIT(PA_M065A);

	heading = (ushort)(((ushort)filter_data.adi_symbol.present_mag_heading
					* 27000L) >> 16);
	dp.LX065B = MM_TO_DP(heading % 750 + 2250);
	dp.C065B = COLOR(input_data.adi_symbol.present_mag_heading, 0);
	tmp = heading / 750 + 4;
	if (tmp >= 36)
		tmp -= 36;
	p = dp.A065BA;
	for (i = 0; i < 8; i++) {
		*p++ = font6_tbl[tmp % 10];
		*p++ = font6_tbl[tmp / 10];
		if (--tmp < 0)
			tmp += 36;
	}
	SET_PABIT(PA_M065B);
}

/*=======================================================================
 |
 |		Command Course Marker
 |
 |	void	dp_M066()
 |
 =======================================================================*/
static	void	dp_M066(void)
{
	short	tmp;

	if ((input_data.adi_symbol.symbol_status & 0x4000) == 0)
		return;

	tmp = ((filter_data.adi_symbol.comamnd_course -
			filter_data.adi_symbol.present_mag_heading)
			* 27000L) >> 16;
	if (tmp < -2250)
		tmp = -2250;
	else if (tmp > 2250)
		tmp = 2250;
	dp.L066 = MM_TO_DP(tmp + 2250);
	dp.C066 = COLOR(input_data.adi_symbol.color_code1, 4);
	SET_PABIT(PA_M0660);
	SET_PABIT(PA_M0661);
}

/*=======================================================================
 |
 |		Roll Indices & Pointer
 |
 |	void	dp_M067()
 |
 =======================================================================*/
static	void	dp_M067(void)
{
	short	tmp;

	if ((input_data.adi_symbol.symbol_status & 0x2000) != 0) {
		dp.C067A = COLOR(input_data.adi_symbol.color_code1, 0);
		SET_PABIT(PA_M067A);
	}
	if ((input_data.adi_symbol.symbol_status & 0x1000) != 0) {
		dp.R067B = ANGLE(filter_data.adi_symbol.roll_angle);
		tmp = ABS(filter_data.adi_symbol.roll_angle);
		if (tmp > 0x2aaa && tmp < 0x5555)
			dp.L067B = MM_TO_DP((2598L << 14) / _sin(tmp));
		else
			dp.L067B = MM_TO_DP(3000);
		dp.C067B = COLOR(input_data.adi_symbol.color_code2, 12);
		SET_PABIT(PA_M067B0);
		SET_PABIT(PA_M067B1);
	}
}

/*=======================================================================
 |
 |		Pitch Scale
 |
 |	void	dp_M068()
 |
 =======================================================================*/
static	void	dp_M068(void)
{
	short	pitch;

	if ((input_data.adi_symbol.symbol_status & 0x0800) == 0)
		return;

	dp.R068 = ANGLE(filter_data.adi_symbol.roll_angle);
	pitch = POSITION(filter_data.adi_symbol.pitch_angle, 205.2);
	dp.L068 = MM_TO_DP(10260) -
			LIMIT(pitch, -MM_TO_DP(10260), MM_TO_DP(10260));
	dp.C068 = COLOR(input_data.adi_symbol.color_code2, 8);
	dp.PY068 = LIMIT(-pitch, -MM_TO_DP(3000), MM_TO_DP(3000));

	SET_PABIT(PA_M0680);
	SET_PABIT(PA_M0681);
}

/*=======================================================================
 |
 |		Flight Path Marker
 |
 |	void	dp_M069()
 |
 =======================================================================*/
static	void	dp_M069(void)
{
	short	len;

	if ((input_data.adi_symbol.symbol_status & 0x0400) == 0)
		return;

	dp.PX069 = POSITION(filter_data.adi_symbol.flight_path_marker_az,
							102.6);
	dp.PY069 = POSITION(filter_data.adi_symbol.flight_path_marker_el,
							102.6);
	dp.C069 = COLOR(input_data.adi_symbol.color_code3, 12);

	len = _sqrt((long)dp.PX069 * (long)dp.PX069 +
					(long)dp.PY069 * (long)dp.PY069);
	if (len < MM_TO_DP(3000) && dp.PX069 < MM_TO_DP(2598) &&
					dp.PX069 > -MM_TO_DP(2598)) {
		SET_PABIT(PA_M0690);
		SET_PABIT(PA_M0691);
	}
}

/*=======================================================================
 |
 |		Present Altitude, Scale
 |
 |	void	dp_M070()
 |
 =======================================================================*/
static	void	dp_M070(void)
{
	short	alt;
	int	tmp, tmp2, i;
	short	*p;

	if ((input_data.adi_symbol.symbol_status & 0x0200) == 0)
		return;

	alt = LIMIT(filter_data.adi_symbol.present_altitude, -150*4, 8000*4);
	dp.C070A0 = COLOR(input_data.adi_symbol.color_code3, 4);
	dp.C070A1 = COLOR(input_data.adi_symbol.color_code3, 0);
	if (digital_update_flag) {
		dp.A070A[0] = FONT6_0;
		tonumR(ABS(alt)/4, &dp.A070A[1], 2, font6_tbl, FONT6_0);
		tonumR(ABS(alt)/400, &dp.A070A[3], 2, font7_tbl, FONT7_SP);
		if (alt < 0)
			dp.A070A[4] = FONT7_MINUS;
	}
	SET_PABIT(PA_M070A);

	dp.L070B = MM_TO_DP((((long)alt + 10025L*4) % (50*4)) * (24/4) + 1800);
	dp.C070B = COLOR(input_data.adi_symbol.color_code3, 8);
	p = dp.A070BA;
	tmp = (alt / 4 + 10025) / 50 * 5 - 1010;
	for (i = 0; i < 5; i++) {
		tmp2 = ABS(tmp);
		*p++ = font6_tbl[tmp2 % 10];
		*p++ = FONT6_01;
		tmp2 /= 10;
		*p++ = font6_tbl[tmp2 % 10];
		tmp2 /= 10;
		*p++ = (tmp < 0) ? FONT6_MINUS :
				(tmp2 ? font6_tbl[tmp2 % 10] : FONT6_SP);
		tmp += 5;
	}
	SET_PABIT(PA_M070B);
}

/*=======================================================================
 |
 |		Glide Slope Scale
 |
 |	void	dp_M071()
 |
 =======================================================================*/
static	void	dp_M071(void)
{
	if ((input_data.adi_symbol.symbol_status & 0x0100) == 0)
		return;

	dp.C071A = COLOR(input_data.adi_symbol.color_code4, 12);
	SET_PABIT(PA_M071A0);
	SET_PABIT(PA_M071A1);
	SET_PABIT(PA_M071A2);
	SET_PABIT(PA_M071A3);
	SET_PABIT(PA_M071A4);
	SET_PABIT(PA_M071A5);

	dp.L071B = MM_TO_DP(1860) +
		POSITION(LIMIT(filter_data.adi_symbol.glide_slope,
		-8960, 8960), 114.10);
	dp.C071B = COLOR(input_data.adi_symbol.color_code4, 8);
	SET_PABIT(PA_M071B0);
	SET_PABIT(PA_M071B1);
}

/*=======================================================================
 |
 |		Vertical Velocity Scale
 |
 |	void	dp_M072()
 |
 =======================================================================*/
static	void	dp_M072(void)
{
	short	vertical_velocity;
	short	vertical_velocity2;
	short	angle;

	if ((input_data.adi_symbol.symbol_status & 0x0080) == 0)
		return;

	vertical_velocity = filter_data.adi_symbol.vertical_velocity >> 3;
	dp.C072A0 = COLOR(input_data.adi_symbol.color_code4, 4);
	dp.C072A1 = COLOR(input_data.adi_symbol.color_code5, 8);
	if (digital_update_flag) {
		tonumR(ABS(vertical_velocity), dp.A072A, 5, font7_tbl,
								FONT7_SP);
		if (vertical_velocity < -999)
			dp.A072A[4] = FONT7_MINUS;
		else if (vertical_velocity < -99)
			dp.A072A[3] = FONT7_MINUS;
		else if (vertical_velocity < -9)
			dp.A072A[2] = FONT7_MINUS;
		else if (vertical_velocity < 0)
			dp.A072A[1] = FONT7_MINUS;
	}
/*	SET_PABIT(PA_M072A0);*/
	SET_PABIT(PA_M072A1);

	dp.C072B = COLOR(input_data.adi_symbol.color_code5, 12);
	vertical_velocity2 = ABS(vertical_velocity);
	if (vertical_velocity2 < 2000) {
		angle = vertical_velocity2 * 0x10000L / 6 / 2000;
	} else {
		vertical_velocity2 += 6000;
		vertical_velocity2 /= 4;
		angle = vertical_velocity2 * 0x10000L / 6 / 2000;
	}
	dp.L072B0 = LENGTH(vertical_velocity2, (6.6+0.3)*2*PI/12/1000*0x10000);
	dp.L072B1 = LENGTH(vertical_velocity2, (6.6+0.6)*2*PI/12/1000*0x10000);
	dp.L072B2 = LENGTH(vertical_velocity2, (6.6+0.9)*2*PI/12/1000*0x10000);
	if (vertical_velocity >= 0) {
		SET_PABIT(PA_M072B);
		angle = -angle;
	} else {
		SET_PABIT(PA_M072C);
	}

	dp.R072D = ANGLE(angle);
	dp.C072D = COLOR(input_data.adi_symbol.color_code4, 0);
	SET_PABIT(PA_M072D0);
	SET_PABIT(PA_M072D1);
}

/*=======================================================================
 |
 |		Locator Scale
 |
 |	void	dp_M073()
 |
 =======================================================================*/
static	void	dp_M073(void)
{
	if ((input_data.adi_symbol.symbol_status & 0x0040) == 0)
		return;

	dp.C073A = COLOR(input_data.adi_symbol.color_code5, 4);
	SET_PABIT(PA_M073A0);
	SET_PABIT(PA_M073A1);
	SET_PABIT(PA_M073A2);
	SET_PABIT(PA_M073A3);
	SET_PABIT(PA_M073A4);
	SET_PABIT(PA_M073A5);

	if ((input_data.adi_symbol.locator & 0x0001) == 0) {
		dp.L073B = MM_TO_DP(1860) +
			POSITION(LIMIT(filter_data.adi_symbol.locator,
			-16000, 16000), 63.8976);
	} else {
		dp.L073B = MM_TO_DP(1860) +
			POSITION(LIMIT(filter_data.adi_symbol.locator,
			-32000, 32000), 31.9488);
	}
	dp.C073B = COLOR(input_data.adi_symbol.color_code5, 0);
	SET_PABIT(PA_M073B0);
	SET_PABIT(PA_M073B1);
}

/*=======================================================================
 |
 |		AOA Scale
 |
 |	void	dp_M074()
 |
 =======================================================================*/
static	void	dp_M074(void)
{
	short	aoa, tmp;

	if ((input_data.adi_symbol.symbol_status & 0x0020) == 0)
		return;

	aoa = LIMIT(filter_data.adi_symbol.aoa, -1821, 16384);

	dp.C074A0 = COLOR(input_data.adi_symbol.color_code6, 12);
	dp.C074A1 = COLOR(input_data.adi_symbol.color_code6, 4);
	tmp = (aoa * 180L + 0x8000L) >> 16;
	tonumR(ABS(tmp), dp.A074A, 2, font7_tbl, FONT7_SP);
	if (tmp < 0)
		dp.A074A[1] = FONT7_MINUS;
/*	SET_PABIT(PA_M074A0);*/
	SET_PABIT(PA_M074A1);

	dp.C074B = COLOR(input_data.adi_symbol.color_code6, 0);
	if (aoa >= 0) {
		dp.L074B0 = LENGTH(aoa, (8.25+0.3)*2*PI);
		dp.L074B1 = LENGTH(aoa, (8.25+0.0)*2*PI);
		dp.L074B2 = LENGTH(aoa, (8.25-0.3)*2*PI);
		SET_PABIT(PA_M074B);

		dp.R074D = ANGLE(aoa);
	} else {
		dp.L074B0 = LENGTH(-aoa, (8.25+0.3)*2*PI*6);
		dp.L074B1 = LENGTH(-aoa, (8.25+0.0)*2*PI*6);
		dp.L074B2 = LENGTH(-aoa, (8.25-0.3)*2*PI*6);
		SET_PABIT(PA_M074C);

		dp.R074D = ANGLE(aoa * 6);
	}

	dp.C074D = COLOR(input_data.adi_symbol.color_code6, 8);
/*	SET_PABIT(PA_M074D0);*/
	SET_PABIT(PA_M074D1);
}

/*=======================================================================
 |
 |		Present Airspeed
 |
 |	void	dp_M075()
 |
 =======================================================================*/
static	void	dp_M075(void)
{
	short	speed;
	int	tmp, i;
	short	*p;

	if ((input_data.adi_symbol.symbol_status & 0x0010) == 0)
		return;

	dp.C075A0 = COLOR(input_data.adi_symbol.color_code7, 8);
	dp.C075A1 = COLOR(input_data.adi_symbol.color_code7, 4);
	speed = LIMIT(filter_data.adi_symbol.present_speed, 0, 999*16);
	if (digital_update_flag)
		tonumR((speed + 8) / 16, dp.A075A, 3, font7_tbl, FONT7_SP);
	SET_PABIT(PA_M075A);

	dp.LY075B = MM_TO_DP(3000 - (((long)speed + (25*16)) % (50*16)) * 24 / 16);
	dp.C075B = COLOR(input_data.adi_symbol.color_code7, 12);
	p = dp.A075BA;
	tmp = (speed / 16 + 25) / 50 * 5 + 10;
	for (i = 0; i < 5; i++) {
		if (tmp >= 0 ) {
			tonumR(ABS(tmp), p, 3, font6_tbl, FONT6_SP);
			if (tmp < -9)
				p[2] = FONT6_MINUS;
			else if (tmp < 0)
				p[1] = FONT6_MINUS;
			tmp -= 5;
		} else
			p[0] = p[1] = p[2] = FONT6_SP;
		p += 3;
	}
	SET_PABIT(PA_M075B);
}

/*=======================================================================
 |
 |		Airplane Symbol
 |
 |	void	dp_M076()
 |
 =======================================================================*/
static	void	dp_M076(void)
{
	if ((input_data.adi_symbol.symbol_status & 0x0008) == 0)
		return;

	dp.C076 = COLOR(input_data.adi_symbol.color_code7, 0);
	SET_PABIT(PA_M0760);
	SET_PABIT(PA_M0761);
}

/*=======================================================================
 |
 |		Percent Trim Indicator
 |
 |	void	dp_M077()
 |
 =======================================================================*/
static	void	dp_M077(void)
{
	short	tmp;

	if ((input_data.adi_symbol.symbol_status & 0x0004) == 0)
		return;

	dp.L077A = MM_TO_DP(885) +
			POSITION(LIMIT(filter_data.adi_symbol.trim_1,
			-100*256, 100*256), 16.8/200*256);
	dp.C077A0 = COLOR(input_data.adi_symbol.color_code8, 12);
	dp.C077A = COLOR(input_data.adi_symbol.color_code8, 8);
	SET_PABIT(PA_M077A);

	dp.L077B = MM_TO_DP(885) +
			POSITION(LIMIT(filter_data.adi_roll_trim,
			-100*256, 100*256), 16.8/200*256);
	dp.C077B0 = COLOR(input_data.adi_symbol.color_code8, 12);
	dp.C077B = COLOR(input_data.adi_symbol.color_code8, 4);
	SET_PABIT(PA_M077B);

	dp.C077C = COLOR(input_data.adi_symbol.color_code8, 12);
	SET_PABIT(PA_M077C);

	tmp = LIMIT(filter_data.adi_symbol.trim_2, -100*256, 100*256);
	if (tmp >= 0) {
		dp.L077D0 = LENGTH(tmp, ((10.8-0.3)*2*PI/3.6)/200*256);
		dp.L077D1 = LENGTH(tmp, ((10.8-0.6)*2*PI/3.6)/200*256);
		dp.L077D2 = LENGTH(tmp, ((10.8-0.9)*2*PI/3.6)/200*256);
		dp.C077D = COLOR(input_data.adi_symbol.color_code8, 0);
		SET_PABIT(PA_M077D);
	} else {
		tmp = -tmp;
		dp.L077D0 = LENGTH(tmp, ((10.8-0.3)*2*PI/3.6)/200*256);
		dp.L077D1 = LENGTH(tmp, ((10.8-0.6)*2*PI/3.6)/200*256);
		dp.L077D2 = LENGTH(tmp, ((10.8-0.9)*2*PI/3.6)/200*256);
		dp.C077D = COLOR(input_data.adi_symbol.color_code8, 0);
		SET_PABIT(PA_M077E);
	}
}
