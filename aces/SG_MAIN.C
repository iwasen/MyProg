/************************************************************************
 *									*
 *		Ç`ÇbÇdÇrÅ@ÇrÅ^ÇfÅ@ÇbÇoÇtÉvÉçÉOÉâÉÄ			*
 *									*
 *		ñºèÃ		: ÉÅÉCÉìèàóù				*
 *		ÉtÉ@ÉCÉãñº	: sg_main.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=sg_main

#include "sg_cpu.h"

#define	COLUMN(x)	(-5200+(x-1)*451)
#define	LINE(y)		(4680-(y-1)*720)

/*	ì‡ïîä÷êî	*/
static	void	sg_ibit(void);
static	void	dsp_ibit_result(void);
static	void	dsp_sg_ibit_result(int *, ushort);
static	void	dsp_du_ibit_result(int *, ushort, uchar *);
/*static*/	void	sg_debug(void);
static	void	sg_normal(void);
static	void	debug_menu(void);
/*static*/	void	get_data(void);
/*static*/	void	filter(void);
static	void	filter_sub(short *, short *, short *, ushort *, int);
static	void	filter_reset(short *, short *, ushort *, int);
static	void	ibit_start(void);
static	void	rbit_start(void);

/*=======================================================================
 |
 |		ÉÅÉCÉìÉãÅ[Évèàóù
 |
 |	void	sg_main()
 |
 =======================================================================*/
void	sg_main(void)
{
	/* É^ÉCÉ}Å[ãNìÆ */
	tm_update = TM_UPDATE;		/* ÉfÅ[É^çXêVé¸ä˙äƒéãÉ^ÉCÉ}Å[ãNìÆ */
	tm_dudata = TM_DUDATA;		/* ÇcÅ^ÇtÉfÅ[É^ëóêMÉ^ÉCÉ}Å[ãNìÆ */
	tm_digital = TM_DIGITAL;	/* ÉfÉWÉ^Éãï\é¶çXêVÉ^ÉCÉ}Å[ãNìÆ */
	tm_gscbackup = TM_GSCBACKUP;	/* ÇfÇrÇbäÑÇËçûÇ›ÉoÉbÉNÉAÉbÉvèàóù */
	if ((debug_flag & DBF_DUSTART) == 0)
		tm_dustart = TM_DUSTART;/* ÇcÅ^ÇtóßÇøè„Ç™ÇËåüèoÉ^ÉCÉ}Å[ãNìÆ */
	else
		sg_mode = SGM_NORMAL;	/* í èÌÉÇÅ[Éh */

	/* ì¸óÕÉfÅ[É^èàóù */
	for (;;) {
		if (calc_flag != 0) {
			calc_flag = 0;	/* åvéZäJénÉtÉâÉOÉäÉZÉbÉg */
			SET_WORD(pa, 0, N_PABIT);	/* P/A ÉrÉbÉgÉNÉäÉA */
			str_count = 0;	/* ï∂éöï\é¶ÉJÉEÉìÉ^ÉäÉZÉbÉg */
			switch (sg_mode) {
			case SGM_IBIT:
				sg_ibit();
				old_display_mode = DSPM_NOSYMBOL;
				break;
			case SGM_NORMAL:
				sg_normal();
				old_display_mode = display_mode;
				break;
			case SGM_DEBUG:
				sg_debug();
				old_display_mode = DSPM_NOSYMBOL;
				break;
			default:
				old_display_mode = DSPM_NOSYMBOL;
				break;
			}

			if (rate_dsp_flag)
				dp_update_rate();	/* çXêVÉåÅ[Égï\é¶ */

			copy_pa_dp();	/* ÇoÅ^Ç`ÅCÇcÅ^ÇoÉRÉsÅ[ */

/*@@@@@@@@@@@@@@@*/
{
	extern	int	time_count;
	extern	int	calc_count;
	extern	int	time_test;
	if (time_test == 0)
		disp_data();
	else {
		calc_count++;
		if (time_count != 0)
			calc_flag = 1;
		else {
			printf("\n%f mS\n#", 1000.0 / calc_count);
			time_test = 0;
		}
	}
}
/*@@@@@@@@@@@@@@@*/
		} else {
			cpu_check();		/* ÇbÇoÇtÉ`ÉFÉbÉN */
		}
/*@@@@@@@@@@@@@@@*/
{
	extern	short	test_mbi_error;
	if (test_mbi_error)
		halt_cpu("MBI BIT ERROR", 1);
	while (kbhit()) {
		if (kbcheck())
			return;
	}
}
/*@@@@@@@@@@@@@@@*/
	}
}

/*=======================================================================
 |
 |		ÇhÅ|ÇaÇhÇsÉÇÅ[Éhèàóù
 |
 |	void	sg_ibit()
 |
 =======================================================================*/
static	void	sg_ibit(void)
{
	static	short	pa_switch[28] = {
		PA_TEST51,  PA_TEST52,  PA_TEST53,  PA_TEST54,  PA_TEST55,
		PA_TEST56,  PA_TEST57,  PA_TEST58,  PA_TEST59,  PA_TEST510,
		PA_TEST511, PA_TEST512, PA_TEST513, PA_TEST514, PA_TEST515,
		PA_TEST516, PA_TEST517, PA_TEST518, PA_TEST519, PA_TEST520,
		PA_TEST528, PA_TEST527, PA_TEST526, PA_TEST525,
		PA_TEST524, PA_TEST523, PA_TEST522, PA_TEST521
	};
	int	i;
	long	bit;
	short	ibit_flag;

	switch (ibit_mode) {
	case IBIT_START:
	case IBIT_DUBIT:
		break;
	case IBIT_TPTN1:
		SET_PABIT(PA_TEST1);
		break;
	case IBIT_TPTN2:
		SET_PABIT(PA_TEST2);
		break;
	case IBIT_TPTN3:
		SET_PABIT(PA_TEST3);
		break;
	case IBIT_TPTN4:
		SET_PABIT(PA_TEST4);
		break;
	case IBIT_SWITCH:
		SET_PABIT(PA_TEST529);
		for (i = 0, bit = 1; i < 28; i++, bit <<= 1) {
			if ((ibit_switch & bit) == 0)
				SET_PABIT(pa_switch[i]);
		}
		break;
	case IBIT_RESULT:
		dsp_ibit_result();	/* ÇhÅ|ÇaÇhÇsåãâ ï\é¶ */

		/* ÇhÅ|ÇaÇhÇsåãâ ï\é¶èIóπÉ`ÉFÉbÉN */
		if (debug_flag & DBF_IBIT)
			ibit_flag = ibit_request;
		else
			ibit_flag = *GSC_INPUT & 0x8000;
		if (ibit_flag == 0 && tm_ibit == TM_STOP)
			ibit_status(BIT_OK);
		break;
	}
}

/*=======================================================================
 |
 |		ÇhÅ|ÇaÇhÇsåãâ ï\é¶èàóù
 |
 |	void	dsp_ibit_result()
 |
 =======================================================================*/
static	void	dsp_ibit_result(void)
{
	ushort	sg1_mode, sg2_mode, sg3_mode;
	ushort	sg1_bit_status, sg2_bit_status, sg3_bit_status;
	ushort	sgx_bit_status;
	ushort	du1_bit_status, du2_bit_status, du3_bit_status;
	ushort	ibit_mode1, ibit_mode2, ibit_mode3;
	int	y;

	/* äeÇbÇoÇtÇÃÉXÉeÅ[É^ÉXéÊÇËèoÇµ */
	switch (cpu) {
	case CPU_1:
		sg1_mode = sg_mode;
		sg2_mode = CPU2_VME(sg_mode);
		sg3_mode = CPU3_VME(sg_mode);
		ibit_mode1 = ibit_mode;
		ibit_mode2 = CPU2_VME(ibit_mode);
		ibit_mode3 = CPU3_VME(ibit_mode);
		sg1_bit_status = sg_bit_status;
		sg2_bit_status = CPU2_VME(sg_bit_status);
		sg3_bit_status = CPU3_VME(sg_bit_status);
		du1_bit_status = du_bit_status;
		du2_bit_status = CPU2_VME(du_bit_status);
		du3_bit_status = CPU3_VME(du_bit_status);
		break;
	case CPU_2:
		sg1_mode = CPU1_VME(sg_mode);
		sg2_mode = sg_mode;
		sg3_mode = CPU3_VME(sg_mode);
		ibit_mode1 = CPU1_VME(ibit_mode);
		ibit_mode2 = ibit_mode;
		ibit_mode3 = CPU3_VME(ibit_mode);
		sg1_bit_status = CPU1_VME(sg_bit_status);
		sg2_bit_status = sg_bit_status;
		sg3_bit_status = CPU3_VME(sg_bit_status);
		du1_bit_status = CPU1_VME(du_bit_status);
		du2_bit_status = du_bit_status;
		du3_bit_status = CPU3_VME(du_bit_status);
		break;
	case CPU_3:
		sg1_mode = CPU1_VME(sg_mode);
		sg2_mode = CPU2_VME(sg_mode);
		sg3_mode = sg_mode;
		ibit_mode1 = CPU1_VME(ibit_mode);
		ibit_mode2 = CPU2_VME(ibit_mode);
		ibit_mode3 = ibit_mode;
		sg1_bit_status = CPU1_VME(sg_bit_status);
		sg2_bit_status = CPU2_VME(sg_bit_status);
		sg3_bit_status = sg_bit_status;
		du1_bit_status = CPU1_VME(du_bit_status);
		du2_bit_status = CPU2_VME(du_bit_status);
		du3_bit_status = du_bit_status;
		break;
	}

	/* CPU HALT É`ÉFÉbÉN */
	if (sg1_mode == SGM_HALT)
		sg1_bit_status |= BIT_SG_HLT1;
	if (sg2_mode == SGM_HALT)
		sg2_bit_status |= BIT_SG_HLT2;
	if (sg3_mode == SGM_HALT)
		sg3_bit_status |= BIT_SG_HLT3;

	/* ÇhÅ|ÇaÇhÇsåãâ éÊÇËèoÇµ */
	sgx_bit_status = (sg1_bit_status | sg2_bit_status | sg3_bit_status) &
			(BIT_SG_ROMC | BIT_SG_RAML | BIT_SG_CPUT | BIT_SG_SW |
			BIT_SG_232T | BIT_SG_HLT1 | BIT_SG_HLT2 | BIT_SG_HLT3);
	du1_bit_status &= BIT_DU_ROMC | BIT_DU_RAML | BIT_DU_CPUT |
			BIT_DU_232T | BIT_DU_BKLT | BIT_DU_BLSN | BIT_DU_PWRS;
	du2_bit_status &= BIT_DU_ROMC | BIT_DU_RAML | BIT_DU_CPUT |
			BIT_DU_232T | BIT_DU_BKLT | BIT_DU_BLSN | BIT_DU_PWRS;
	du3_bit_status &= BIT_DU_ROMC | BIT_DU_RAML | BIT_DU_CPUT |
			BIT_DU_232T | BIT_DU_BKLT | BIT_DU_BLSN | BIT_DU_PWRS;

	/* åãâ ï\é¶ */
	if ((sg1_mode != SGM_IBIT || ibit_mode1 == IBIT_RESULT) &&
			(sg2_mode != SGM_IBIT || ibit_mode2 == IBIT_RESULT) &&
			(sg3_mode != SGM_IBIT || ibit_mode3 == IBIT_RESULT)) {

		/* åãâ É`ÉFÉbÉN */
		if (sgx_bit_status == 0 && du1_bit_status == 0 &&
				du2_bit_status == 0 && du3_bit_status == 0) {
			/* ëSÇƒê≥èÌ */
			dspstr(COLUMN(1), LINE(1), COLOR_WHITE1, 2,
						"TEST OK");
		} else {
			/* ÉGÉâÅ[Ç†ÇË */
			dspstr(COLUMN(1), LINE(1), COLOR_YELLOW, 2,
						"TEST FAIL");
			y = 3;

			/* ÇrÅ^ÇfÇÃåãâ ï\é¶ */
			if (sgx_bit_status != 0)
				dsp_sg_ibit_result(&y, sgx_bit_status);

			/* ÇcÇtÇPÇÃåãâ ï\é¶ */
			if (du1_bit_status != 0)
				dsp_du_ibit_result(&y, du1_bit_status, "DU1");

			/* ÇcÇtÇQÇÃåãâ ï\é¶ */
			if (du2_bit_status != 0)
				dsp_du_ibit_result(&y, du2_bit_status, "DU2");

			/* ÇcÇtÇRÇÃåãâ ï\é¶ */
			if (du3_bit_status != 0)
				dsp_du_ibit_result(&y, du3_bit_status, "DU3");
		}
	}
}

/*=======================================================================
 |
 |		ÇhÅ|ÇaÇhÇsÅ@ÇrÅ^Çfåãâ ï\é¶èàóù
 |
 |	void	dsp_sg_ibit_result()
 |
 =======================================================================*/
static	void	dsp_sg_ibit_result(int *y, ushort status)
{
	static	struct	{
		short	bit;
		char	*str;
	} sg_bit[8] = {
		{BIT_SG_ROMC, "ROMC"},
		{BIT_SG_RAML, "RAML"},
		{BIT_SG_CPUT, "CPUT"},
		{BIT_SG_232T, "232T"},
		{BIT_SG_SW,   "SW  "},
		{BIT_SG_HLT1, "HLT1"},
		{BIT_SG_HLT2, "HLT2"},
		{BIT_SG_HLT3, "HLT3"}
	};
	int	i, x;

	dspstr(COLUMN(1), LINE(*y), COLOR_YELLOW, 2, "S/G");
	x = 5;
	for (i = 0; i < 8; i++) {
		if (status & sg_bit[i].bit) {
			if (x > 20) {
				x = 5;
				(*y)++;
			}
			dspstr(COLUMN(x), LINE(*y), COLOR_YELLOW,
					2, sg_bit[i].str);
			x += 5;
		}
	}
	*y += 3;
}

/*=======================================================================
 |
 |		ÇhÅ|ÇaÇhÇsÅ@ÇcÅ^Çtåãâ ï\é¶èàóù
 |
 |	void	dsp_du_ibit_result()
 |
 =======================================================================*/
static	void	dsp_du_ibit_result(int *y, ushort status, char *str)
{
	static	struct	{
		short	bit;
		char	*str;
	} du_bit[7] = {
		{BIT_DU_ROMC, "ROMC"},
		{BIT_DU_RAML, "RAML"},
		{BIT_DU_CPUT, "CPUT"},
		{BIT_DU_232T, "232T"},
		{BIT_DU_BKLT, "BLKT"},
		{BIT_DU_BLSN, "BLSN"},
		{BIT_DU_PWRS, "PWRS"}
	};
	int	i, x;

	dspstr(COLUMN(1), LINE(*y), COLOR_YELLOW, 2, str);
	x = 5;
	for (i = 0; i < 7; i++) {
		if (status & du_bit[i].bit) {
			if (x > 20) {
				x = 5;
				(*y)++;
			}
			dspstr(COLUMN(x), LINE(*y), COLOR_YELLOW,
					2, du_bit[i].str);
			x += 5;
		}
	}
	*y += 2;
}

/*=======================================================================
 |
 |		ÉmÅ[É}ÉãÉÇÅ[Éhèàóù
 |
 |	void	sg_normal()
 |
 =======================================================================*/
static	void	sg_normal(void)
{
	short	ibit_flag;

	if (debug_flag & DBF_IBIT)
		ibit_flag = ibit_request;
	else
		ibit_flag = *GSC_INPUT & 0x8000;
	if (ibit_flag != 0) {			/* ÇhÅ|ÇaÇhÇsì¸óÕÉ`ÉFÉbÉN */
		ibit_start();			/* ÇhÅ|ÇaÇhÇsäJén */
	} else if (rbit_request) {		/* ÇqÅ|ÇaÇhÇsÉ`ÉFÉbÉN */
		rbit_start();			/* ÇqÅ|ÇaÇhÇsäJén */
		rbit_request = 0;
	} else if (bus_fail) {			/* ÇaÇtÇr ÇeÇ`ÇhÇkÉ`ÉFÉbÉN */
		dspstr(COLUMN(1), LINE(1), COLOR_YELLOW, 2, "BUS FAIL");
	} else {
		get_data();	/* ì¸óÕÉfÅ[É^éÊÇËçûÇ›*/
		filter();	/* ÉtÉBÉãÉ^èàóù */
		dp_calc();	/* ÇcÅ^ÇoåvéZ */
	}
}

/*=======================================================================
 |
 |		ÉfÉoÉbÉOÉÇÅ[Éhèàóù
 |
 |	void	sg_debug()
 |
 =======================================================================*/
/*static*/	void	sg_debug(void)
{
	switch (debug_mode) {
	case DBM_DBGMENU:
		debug_menu();
		break;
	case DBM_TPTN1:
		SET_PABIT(PA_TEST1);
		break;
	case DBM_TPTN2:
		SET_PABIT(PA_TEST2);
		break;
	case DBM_TPTN3:
		SET_PABIT(PA_TEST3);
		break;
	case DBM_TPTN4:
		SET_PABIT(PA_TEST4);
		break;
	case DBM_SIMTBL:
		get_data();	/* ì¸óÕÉfÅ[É^éÊÇËçûÇ›*/
		filter();	/* ÉtÉBÉãÉ^èàóù */
		dp_calc();	/* ÇcÅ^ÇoåvéZ */
		break;
	}
}

/*=======================================================================
 |
 |		ÉfÉoÉbÉOÉÅÉjÉÖÅ[ï\é¶èàóù
 |
 |	void	debug_menu()
 |
 =======================================================================*/
static	void	debug_menu(void)
{
	char	buf[14];

	dspstr(-3553, 4500,  COLOR_YELLOW, 1, "1    2    3    4");
	dspstr(-2030, 3500,  COLOR_YELLOW, 1, "TEST PATTERN");
	dspstr(-4060, 1000,  COLOR_WHITE1, 2, "ACES MFDS  VER 1.0");
	dspstr(-2255, 0,     COLOR_WHITE1, 2, "DEBUG MODE");
	dspstr(-5000, -1500, COLOR_GREEN1, 1, "NORMAL");
	dspstr(-2708, -3800, COLOR_CYAN,   1, "SIMULATION TABLE");
	dspstr(-3553, -4800, COLOR_CYAN,   1, "1    2    3    4    5");

	if (rom_error_flag) {
		buf[0] = 'S';
		buf[1] = 'U';
		buf[2] = 'M';
		buf[3] = ' ';
		buf[4] = 'E';
		buf[5] = 'R';
		buf[6] = 'R';
		buf[7] = '=';
		shorttohex(check_sum, &buf[8], 4);
		buf[12] = 0;
		dspstr(0, -1500, COLOR_RED1, 1, buf);
	}
}

/*=======================================================================
 |
 |		ì¸óÕÉfÅ[É^éÊÇËçûÇ›èàóù
 |
 |	void	get_data()
 |
 =======================================================================*/
/*static*/	void	get_data(void)
{
	short	(*rbuf)[32];

	if (cpu == CPU_1) {
		/* É}ÉXÉ^Å[ÇbÇoÇt */
		rbuf = mbi_rcvbuf;
	} else {
		/* ÉXÉåÅ[ÉuÇbÇoÇt */
		rbuf = (short (*)[32])CPU1_ADR(mbi_rcvbuf);
	}

	input_data.fighter_code = rbuf[0][0];
	input_data.display_mode = rbuf[0][1];

	/* â¬ïœà íuÉEÉBÉìÉhÉEÉfÅ[É^éÊÇËçûÇ› */
	switch (cpu) {
	case CPU_1:
		display_mode = (input_data.display_mode >> 10) & 0x0f;
		COPY_WORD(input_data.mfd_window1, &rbuf[0][2], 4*38);
		COPY_WORD(input_data.mfd_window1+38, &rbuf[29][2], 4*38);
		COPY_WORD(input_data.mfd_window2, &rbuf[14][10], 4*15);
		break;
	case CPU_2:
		display_mode = (input_data.display_mode >> 6) & 0x0f;
		COPY_WORD(input_data.mfd_window1, &rbuf[4][26], 4*38);
		COPY_WORD(input_data.mfd_window1+38, &rbuf[33][26], 4*38);
		COPY_WORD(input_data.mfd_window2, &rbuf[16][6], 4*15);
		break;
	case CPU_3:
		display_mode = (input_data.display_mode >> 2) & 0x0f;
		COPY_WORD(input_data.mfd_window1, &rbuf[9][18], 4*38);
		COPY_WORD(input_data.mfd_window1+38, &rbuf[38][18], 4*38);
		COPY_WORD(input_data.mfd_window2, &rbuf[18][2], 4*15);
		break;
	}

	/* ÉÇÅ[Éhï ÇÃÉfÅ[É^éÊÇËçûÇ› */
	switch (display_mode) {
	case DSPM_FCR:
		input_data.fixed_symbol.fcr_fixed_symbol1 = rbuf[20][0];
		input_data.fixed_symbol.fcr_fixed_symbol2 = rbuf[20][1];
		input_data.fixed_symbol.fcr_fixed_symbol3 = rbuf[20][2];
		input_data.fixed_symbol.fcr_fixed_symbol4 = rbuf[20][8];
		COPY_WORD(input_data.fcr_target, rbuf[21], 2*64);
		input_data.moving_symbol1 = *(MOVING_SYMBOL1 *)rbuf[25];
		input_data.moving_symbol2 = *(MOVING_SYMBOL2 *)rbuf[26];
		break;
	case DSPM_SMS:
		input_data.fixed_symbol.sms_fixed_symbol1 = rbuf[20][3];
		input_data.fixed_symbol.sms_fixed_symbol2 = rbuf[20][4];
		input_data.fixed_symbol.sms_fixed_symbol3 = rbuf[20][5];
		break;
	case DSPM_EW:
		input_data.fixed_symbol.ew_fixed_symbol = rbuf[20][6];
		input_data.fixed_symbol.ew_dot_symbol = rbuf[20][7];
		break;
	case DSPM_HSI:
		input_data.hsi_symbol = *(HSI_SYMBOL *)rbuf[27];
		input_data.adi_symbol = *(ADI_SYMBOL *)rbuf[28];
		switch (input_data.hsi_symbol.mode_submode & 0xe000) {
		case 0x0000:
			/*display_mode = DSPM_HSI;*/
			break;
		case 0x2000:
			display_mode = DSPM_ADI;
			break;
		default:
			display_mode = DSPM_NOSYMBOL;
			break;
		}
		break;
	}
}

/*=======================================================================
 |
 |		ÉtÉBÉãÉ^èàóù
 |
 |	void	filter()
 |
 =======================================================================*/
/*static*/	void	filter(void)
{
	static	ushort	mask_moving_symbol1[] = {
		0xfff0,		/* 01 OMHZLPT */
		0xfff8,		/* 02 OMHZLRL */
		0x0000,		/* 03 OMHZLCL */
		0xffe0,		/* 04 OMANTAZ */
		0xffe0,		/* 05 OMANTEL */
		0xffe0,		/* 06 OMASECX */
		0xffe0,		/* 07 OMASECY */
		0xffc0,		/* 08 OMASECR */
		0xffe0,		/* 09 OMSTRDTX */
		0xffe0,		/* 10 OMSTRDTY */
		0xffe0,		/* 11 OMASMCX */
		0xffe0,		/* 12 OMASMCY */
		0xffe0,		/* 13 OMRMAXX */
		0xffe0,		/* 14 OMRMAXY */
		0xffe0,		/* 15 OMRMAX2X */
		0xffe0,		/* 16 OMRMAX2Y */
		0xffe0,		/* 17 OMRMINX */
		0xffe0,		/* 18 OMRMINY */
		0xffe0,		/* 19 OMACQX */
		0xffe0,		/* 20 OMACQY */
		0xffe0,		/* 21 OMACTGTX */
		0xffe0,		/* 22 OMACTGTY */
		0xffe0,		/* 23 OMACTGTL */
		0xffe0		/* 24 OMACTGTR */
	};
	static	ushort	mask_moving_symbol2[] = {
		0xffe0,		/* 01 OMXYCURX */
		0xffe0,		/* 02 OMXYCURY */
		0xffe0,		/* 03 OMACALTX */
		0xffe0,		/* 04 OMACALTY */
		0xffe0,		/* 05 OMANTPX */
		0xffe0,		/* 06 OMANTPY */
		0xffe0,		/* 07 OMRACQX */
		0xffe0,		/* 08 OMRACQY */
		0x0000,		/* 09 OMMIC */
		0xffe0		/* 10 OMAOJ */
	};
	static	ushort	mask_hsi_symbol[] = {
		0x0000,		/* 01 OMHSISUB */
		0x0000,		/* 02 OMHSIST */
		0x0000,		/* 03 OMHSICC1 */
		0x0000,		/* 04 OMHSICC2 */
		0x0000,		/* 05 OMHSICC3 */
		0x0000,		/* 06 OMHSICC4 */
		0x0000,		/* 07 OMHSICC5 */
		0x0000,		/* 08 OMHSICC6 */
		0x0000,		/* 09 OMHSICC7 */
		0x0000,		/* 10 OMHSTPTN */
		0xfffc,		/* 11 OMHSTPTB */
		0xfffc,		/* 12 OMHPMHDG */
		0xfffc,		/* 13 OMHGTRK */
		0xfffc,		/* 14 OMHCCRS */
		0xfffc,		/* 15 OMHDPB */
		0xff80,		/* 16 OMHGS */
		0xfffc,		/* 17 OMHTBP */
		0xffc0,		/* 18 OMHLOC */
		0xffc0,		/* 19 OMHDEVB */
		0xfffc,		/* 20 OMHHP */
		0xff00,		/* 21 OMHTRM1 */
		0xff00		/* 22 OMHTRM2 */
	};
	static	ushort	mask_adi_symbol[] = {
		0x0000,		/* 01 OMADIST */
		0x0000,		/* 02 OMADICC1 */
		0x0000,		/* 03 OMADICC2 */
		0x0000,		/* 04 OMADICC3 */
		0x0000,		/* 05 OMADICC4 */
		0x0000,		/* 06 OMADICC5 */
		0x0000,		/* 07 OMADICC6 */
		0x0000,		/* 08 OMADICC7 */
		0x0000,		/* 09 OMADICC8 */
		0xfff0,		/* 10 OMAPMHDG */
		0xfffc,		/* 11 OMACCRS */
		0xfffc,		/* 12 OMAROLL */
		0xfff8,		/* 13 OMAPITCH */
		0xfff8,		/* 14 OMAFPMA */
		0xfff8,		/* 15 OMAFPME */
		0xfffc,		/* 16 OMAPALT */
		0xff80,		/* 17 OMAGS */
		0xfff8,		/* 18 OMAVV */
		0xffc0,		/* 19 OMALOC */
		0xfff8,		/* 20 OMAAOA */
		0xfff0,		/* 21 OMAPSPD */
		0xff00,		/* 22 OMATRM1 */
		0xff00		/* 23 OMATRM2 */
	};
	int	i;

	switch (display_mode) {
	case DSPM_FCR:
		if (display_mode != old_display_mode) {
			for (i = 0; i < 64; i++) {
				tmp_data.fcr_target[i].x = 
				    (input_data.fcr_target[i].x & 0xffe0);
				tmp_data.fcr_target[i].y = 
				    (input_data.fcr_target[i].y & 0xffe0);
			}
			filter_reset((short *)&input_data.moving_symbol1,
					(short *)&tmp_data.moving_symbol1,
					mask_moving_symbol1, 24);
			filter_reset((short *)&input_data.moving_symbol2,
					(short *)&tmp_data.moving_symbol2,
					mask_moving_symbol2, 10);
		}
		for (i = 0; i < 64; i++) {
			FILTER((short)(input_data.fcr_target[i].x & 0xffe0),
				filter_data.fcr_target[i].x,
				tmp_data.fcr_target[i].x);
			FILTER((short)(input_data.fcr_target[i].y & 0xffe0),
				filter_data.fcr_target[i].y,
				tmp_data.fcr_target[i].y);
		}
		filter_sub((short *)&input_data.moving_symbol1,
				(short *)&filter_data.moving_symbol1,
				(short *)&tmp_data.moving_symbol1,
				mask_moving_symbol1, 24);
		filter_sub((short *)&input_data.moving_symbol2,
				(short *)&filter_data.moving_symbol2,
				(short *)&tmp_data.moving_symbol2,
				mask_moving_symbol2, 10);
		break;
	case DSPM_EW:
		if (display_mode != old_display_mode) {
			tmp_data.ew_dot_symbol = 
				(input_data.fixed_symbol.ew_dot_symbol &
				0x7ff0) << 1;
		}
		FILTER((short)(input_data.fixed_symbol.ew_dot_symbol & 0x7ff0)
				<< 1, filter_data.ew_dot_symbol,
				tmp_data.ew_dot_symbol);
		break;
	case DSPM_HSI:
		if (display_mode != old_display_mode) {
			filter_reset((short *)&input_data.hsi_symbol,
					(short *)&tmp_data.hsi_symbol,
					mask_hsi_symbol, 22);
			tmp_data.hsi_roll_trim =
					input_data.hsi_symbol.trim_1 << 8;
		}
		filter_sub((short *)&input_data.hsi_symbol,
				(short *)&filter_data.hsi_symbol,
				(short *)&tmp_data.hsi_symbol,
				mask_hsi_symbol, 22);
		FILTER((short)(input_data.hsi_symbol.trim_1 << 8),
				filter_data.hsi_roll_trim,
				tmp_data.hsi_roll_trim);
		break;
	case DSPM_ADI:
		if (display_mode != old_display_mode) {
			filter_reset((short *)&input_data.adi_symbol,
					(short *)&tmp_data.adi_symbol,
					mask_adi_symbol, 23);
			tmp_data.adi_roll_trim =
					input_data.adi_symbol.trim_1 << 8;
		}
		filter_sub((short *)&input_data.adi_symbol,
				(short *)&filter_data.adi_symbol,
				(short *)&tmp_data.adi_symbol,
				mask_adi_symbol, 23);
		FILTER((short)(input_data.adi_symbol.trim_1 << 8),
				filter_data.adi_roll_trim,
				tmp_data.adi_roll_trim);
		break;
	}
}

/*=======================================================================
 |
 |		ÉtÉBÉãÉ^ÉTÉuÉãÅ[É`Éì
 |
 |	void	filter_sub()
 |
 =======================================================================*/
static	void	filter_sub(short *in, short *out, short *tmp, ushort *mask, int n)
{
	while (n--) {
		if (*mask != 0)
			FILTER((short)(*in & *mask), *out, *tmp);
		in++;
		out++;
		tmp++;
		mask++;
	}
}

/*=======================================================================
 |
 |		ÉtÉBÉãÉ^ÉäÉZÉbÉgÉTÉuÉãÅ[É`Éì
 |
 |	void	filter_reset()
 |
 =======================================================================*/
static	void	filter_reset(short *in, short *out, ushort *mask, int n)
{
	while (n--) {
		if (*mask != 0)
			*out = (*in & *mask);
		in++;
		out++;
		mask++;
	}
}

/*=======================================================================
 |
 |	ÇoÅ^Ç`ÅCÇcÅ^ÇoÉRÉsÅ[
 |		
 |	void	copy_pa_dp()
 |
 =======================================================================*/
void	copy_pa_dp(void)
{
//	*REG_VIC_VICR4 = 0x84;	/* ÇfÇrÇbï`âÊèIóπäÑÇËçûÇ›ã÷é~ */
	dp_tmp = dp;			/* ÇcÅ^ÇoÉRÉsÅ[ */
	COPY_WORD(pa_tmp, pa, N_PABIT);	/* ÇoÅ^Ç`ÉRÉsÅ[ */
//	*REG_VIC_VICR4 = 0x04;	/* ÇfÇrÇbï`âÊèIóπäÑÇËçûÇ›ã÷é~âèú */
}

/*=======================================================================
 |
 |		ÇhÇmÇhÇsÇhÇ`ÇsÇdÇc ÇaÇhÇsäJénèàóù
 |
 |	void	ibit_start()
 |
 =======================================================================*/
static	void	ibit_start(void)
{
	ibit_mode = IBIT_START;
	sg_mode = SGM_IBIT;

	copy_pa_dp();		/* âÊñ è¡ãé */
	rom_check();		/* ÇqÇnÇlÉ`ÉFÉbÉN*/
	ram_check();		/* ÇqÇ`ÇlÉ`ÉFÉbÉN */
	cpu_check();		/* ÇbÇoÇtÉ`ÉFÉbÉN */
}

/*=======================================================================
 |
 |		ÇhÇmÇhÇsÇhÇ`ÇsÇdÇc ÇaÇhÇsé¿çs
 |
 |	void	ibit_status()
 |
 =======================================================================*/
void	ibit_status(int status)
{
	if (status == BIT_OK) {
		switch (ibit_mode) {
		case IBIT_START:
			ibit_mode = IBIT_DUBIT;
			break;
		case IBIT_DUBIT:
			ibit_mode = IBIT_TPTN1;
			tm_ibit = TM_TPTN;
			break;
		case IBIT_TPTN1:
			ibit_mode = IBIT_TPTN2;
			tm_ibit = TM_TPTN;
			break;
		case IBIT_TPTN2:
			ibit_mode = IBIT_TPTN3;
			tm_ibit = TM_TPTN;
			break;
		case IBIT_TPTN3:
			ibit_mode = IBIT_TPTN4;
			tm_ibit = TM_TPTN;
			break;
		case IBIT_TPTN4:
			ibit_mode = IBIT_SWITCH;
			tm_ibit = TM_SWITCH;
			ibit_switch = 0;
			break;
		case IBIT_SWITCH:
			ibit_mode = IBIT_RESULT;
			tm_ibit = TM_RESULT;
			break;
		case IBIT_RESULT:
			sg_mode = SGM_NORMAL;
			tm_ibit = TM_STOP;
			break;
		}
	} else {
		ibit_mode = IBIT_RESULT;
		tm_ibit = TM_RESULT;
	}
}

/*=======================================================================
 |
 |		ÇqÇdÇlÇnÇsÇd ÇaÇhÇsäJénèàóù
 |
 |	void	rbit_start()
 |
 =======================================================================*/
static	void	rbit_start(void)
{
	rbit_mode = RBIT_START;
	sg_mode = SGM_RBIT;

	copy_pa_dp();		/* âÊñ è¡ãé */
	rom_check();		/* ÇqÇnÇlÉ`ÉFÉbÉN*/
	ram_check();		/* ÇqÇ`ÇlÉ`ÉFÉbÉN */
	cpu_check();		/* ÇbÇoÇtÉ`ÉFÉbÉN */

	rbit_mode |= RBIT_SG_END;	/* ÇrÅ^ÇfÇÃÇqÅ|ÇaÇhÇsèIóπ */

	/* ÇqÅ|ÇaÇhÇsèIóπÉ`ÉFÉbÉN */
	if ((rbit_mode & (RBIT_SG_END | RBIT_DU_END)) ==
					(RBIT_SG_END | RBIT_DU_END))
		sg_mode = SGM_NORMAL;
}
