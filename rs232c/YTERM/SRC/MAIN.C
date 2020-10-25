/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: ���C���v���O����			*
 *		�t�@�C����	: main.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <time.h>
#ifdef	__TURBOC__
#include <graphics.h>
#else
#include <graph.h>
#endif
#include <dos.h>
#include <string.h>
#include <signal.h>
#include <conio.h>
#include <process.h>
#include "rs232c.h"
#include "yterm.h"

/*	�����֐�	*/
static	void	set_screen(void);
static	void	initialize(void);
static	int	initialize2(void);
static	void	terminate(void);
static	void	ms_dos(void);
static	void	user_func(char *);
static	void	scr_save(void);
static	void	scr_restore(void);
static	void	back_scroll(void);
static	void	interrupt far	int_stop(void);
#ifdef	__TURBOC__
static	int	hard_err(unsigned, unsigned, unsigned far *);
#else
static	void	far hard_err(unsigned, unsigned, unsigned far *);
#endif

/*	�����ϐ�	*/
static	struct key_buf	func_save;
static	int	save_text[80*22];
static	int	save_attr[80*22];
static	char	save_gaiji[11][34];
static	void	(far interrupt *stop_vect)();

/*=======================================================================
 |
 |		���C������
 |
 |	int	main()
 |
 =======================================================================*/
main(int argc, char *argv[])
{
	int	loop_sw;
	int	ch;

	/* �I�v�V�����`�F�b�N */
	while (--argc) {
		argv++;
		if (strcmp(*argv, "/M") == 0 || strcmp(*argv, "/m") == 0)
			opt_m = 1;
		else {
			printf("����`�I�v�V���� '%s'\n", *argv);
			exit(1);
		}
	}

	/* �q�r�|�Q�R�Q�b�I�[�v�� */
	rs1_open(&default_prm);
	rs1_cmd(0x00, 0xff);

	/* ���������� */
	initialize();

	switch (initialize2()) {
	case 0:		/* ���ݒ�t�@�C������ */
		setuzoku();
		break;
	case 2:		/* �Z���^�[���t�@�C���L�� */
		settei();
		break;
	}

	set_screen();

	loop_sw = 1;
	while (loop_sw) {
		if (rs1_rxc() != 0) {	/* �f�[�^��M�`�F�b�N */
			/* ��M�f�[�^�L�� */
			ch = rs1_get();		/* ��M�f�[�^���o�� */

			/* �ڑ����Ȃ��ʂɕ\�� */
			if (conxxxt)
				putch2(ch);

			/* �e�L�X�g�t�@�C�����M�҂����� */
			if (snd_fp != NULL) {
				if (cprm.prompt != 0) {
					if (ch == cprm.prompt)
						send_wait = 0;	/* ���M�� */
				}
			}
		} else if (ch = inkey()) {	/* �L�[���̓`�F�b�N */
			if (conxxxt) {
				/* �ڑ����̏��� */
				switch (ch) {
				case KEY_F2:	/* ���[�U�t�@���N�V�����P */
					user_func(modem.f2cmd);
					set_screen();
					break;
				case KEY_F3:	/* ���[�U�t�@���N�V�����Q */
					user_func(cprm.f3cmd);
					set_screen();
					break;
				case KEY_F5:	/* ���Ұ� */
					parameter();
					set_screen();
					break;
				case KEY_F6:	/* ̧�� */
					file();
					set_screen();
					break;
				case KEY_F8:	/* MS-DOS */
					ms_dos();
					set_screen();
					break;
				case KEY_F9:	/* ���~ */
					snd_text_end();
					set_screen();
					break;
				case KEY_F10:	/* �ؒf */
					setudan();
					set_screen();
					break;
				case KEY_UP:
				case KEY_DOWN:
					back_scroll();
					set_screen();
					break;
				default:
					if (snd_fp == NULL) {
						/* �G�R�[�o�b�N */
						if (cprm.echo)
							putch2(ch);

						rs1_putc(ch);	/* ���M */

						/* �k�e�t������ */
						if (ch == KEY_CR &&
							cprm.retkey != 0) {
							if (cprm.echo)
								putch2(KEY_LF);
							rs1_putc(KEY_LF);
						}
					}
					break;
				}
			} else {
				/* ���ڑ����̏��� */
				switch (ch) {
				case KEY_F1:	/* �ڑ� */
					setuzoku();
					set_screen();
					break;
				case KEY_F2:	/* ���[�U�t�@���N�V�����P */
					user_func(modem.f2cmd);
					set_screen();
					break;
				case KEY_F5:	/* �o�^ */
					touroku();
					set_screen();
					break;
				case KEY_F6:	/* �ݒ� */
					settei();
					set_screen();
					break;
				case KEY_F8:	/* MS-DOS */
					ms_dos();
					set_screen();
					break;
				case KEY_F10:	/* �I�� */
					loop_sw = 0;
					break;
				case KEY_UP:
				case KEY_DOWN:
					back_scroll();
					set_screen();
					break;
				default:
					/*putch2(ch);*/
					break;
				}
			}
		} else if (snd_fp != NULL && send_wait == 0) {
			/* �e�L�X�g�t�@�C�����M���� */
			ch = fgetc(snd_fp);	/* �t�@�C������P�����ǂ݂���*/
			if (ch == EOF) {	/* �d�n�e�`�F�b�N */
				beep();
				snd_text_end();	/* �e�L�X�g�t�@�C�����M�I�� */
				set_screen();
			} else {
				/* �k�e�|���b�q */
				if (ch == '\n') {
					ch = KEY_CR;
					if (cprm.prompt != 0)
						send_wait = 1;
				}

				/* �G�R�[�o�b�N */
				if (cprm.echo)
					putch2(ch);

				rs1_putc(ch);	/* ���M */

				/* �k�e�t������ */
				if (ch == KEY_CR && cprm.retkey != 0) {
					if (cprm.echo)
						putch2(KEY_LF);
					rs1_putc(KEY_LF);
				}
			}
		}
	}

	rs1_close();	/* �q�r�|�Q�R�Q�b�N���[�Y */
	terminate();	/* �I������ */
	return(0);
}

/*=======================================================================
 |
 |		�t�@���N�V�����L�[�C�J�[�\���\��
 |
 |	void	set_screen()
 |
 =======================================================================*/
static	void	set_screen(void)
{
	static	char	*func1[] = {
		" �ڑ� ", NULL, NULL, NULL, " �o�^ ",
		" �ݒ� ", NULL, "MS-DOS", NULL, " �I�� "
	};
	static	char	*func2[] = {
		NULL, NULL, NULL, NULL, "���Ұ�",
		" ̧�� ", NULL, "MS-DOS", NULL, " �ؒf "
	};
	static	char	*func3[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " ���~ ", NULL
	};

	if (conxxxt) {
		/* �t�@���N�V�����L�[�\�� */
		func2[1] = modem.f2dsp;
		func2[2] = cprm.f3dsp;
		dsp_func(snd_fp == NULL ? func2 : func3);

		/* �J�[�\���\�� */
		t_loc(csr2_x, csr2_y);
		t_csron();
	} else {
		/* �t�@���N�V�����L�[�\�� */
		func1[1] = modem.f2dsp;
		dsp_func(func1);

		/* �J�[�\������ */
		t_csroff();
	}
}

/*=======================================================================
 |
 |		�����������P
 |
 |	void	initialize()
 |
 =======================================================================*/
static	void	initialize(void)
{
	static	char	gaiji_7621[] = {
		0x02, 0x02,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xab, 0xff, 0x55, 0x00,
		0xab, 0x7f, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40
	};
	static	char	gaiji_7622[] = {
		0x02, 0x02,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
		0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00
	};
	static	char	gaiji_7623[] = {
		0x02, 0x02,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xff, 0xea, 0x00, 0x55,
		0xff, 0x6a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75
	};
	static	char	gaiji_7624[] = {
		0x02, 0x02,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0xff, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75
	};
	static	char	gaiji_7625[] = {
		0x02, 0x02,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40
	};
	static	char	gaiji_7626[] = {
		0x02, 0x02,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0xff, 0x7a, 0x00, 0x75,
		0xff, 0xfa, 0xff, 0xf5, 0xff, 0xfa, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55
	};
	static	char	gaiji_7627[] = {
		0x02, 0x02,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x7f, 0x55, 0x00,
		0xab, 0xff, 0x55, 0x7f, 0xaa, 0xbf, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55
	};
	static	char	gaiji_7628[] = {
		0x02, 0x02,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x7f,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40,
		0xab, 0x40, 0x55, 0x40, 0xab, 0x40, 0x55, 0x40
	};
	static	char	gaiji_7629[] = {
		0x02, 0x02,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75,
		0x01, 0x7a, 0x01, 0x75, 0x01, 0x7a, 0x01, 0x75
	};
	static	char	gaiji_762a[] = {
		0x02, 0x02,
		0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00,
		0x00, 0x00, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0xff, 0xff, 0xff, 0xff, 0x55, 0x55,
		0xaa, 0xaa, 0x55, 0x55, 0xaa, 0xaa, 0x55, 0x55
	};
	static	char	gaiji_762b[] = {
		0x02, 0x02,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00,
		0xff, 0xff, 0x00, 0x00, 0xff, 0xff, 0x00, 0x00
	};
	static	char	*gaiji[] = {
		gaiji_7621, gaiji_7622, gaiji_7623, gaiji_7624,
		gaiji_7625, gaiji_7626, gaiji_7627, gaiji_7628,
		gaiji_7629, gaiji_762a, gaiji_762b
	};
	int	i;

	/* �r�f�I���[�h�ݒ� */
#ifdef	__TURBOC__
{
	int	gdriver = DETECT, gmode, err;

	registerfarbgidriver(PC98_driver_far);
	initgraph(&gdriver, &gmode, "");
	if ((err = graphresult()) != grOk) {
		rs1_close();
		printf("graphics error : %s\n", grapherrormsg(err));
		exit(1);
	}
}
#else
	_setvideomode(_98RESS8COLOR);
#endif
	signal(SIGINT, SIG_IGN);	/* �r�s�n�o�L�[�𖳎����� */
	stop_vect = _dos_getvect(0x06);	/* �r�s�n�o�L�[���荞�݃x�N�^�ۑ� */
	_dos_setvect(0x06, int_stop);	/* �r�s�n�o�L�[���荞�ݐݒ� */

	t_fget(&func_save);	/* �t�@���N�V�����L�[���x�����o�� */
	puts("\x1b[>1h");	/* �ŉ��ʍs���[�U�g�p */
	t_csroff();		/* �J�[�\������ */

	/* ���݂̊O���ۑ� */
	for (i = 0; i < 11; i++)
		t_getfont(0x7621+i, save_gaiji[i]);

	/* �O���Z�b�g */
	for (i = 0; i < 11; i++)
		t_setfont(0x7621+i, gaiji[i]);

	if (opt_m == 0) {
		/* �p���b�g�ݒ� */
#ifdef	__TURBOC__
		setrgbpalette(0, 0, 0, 6);
		setrgbpalette(1, 8, 8, 8);
		setrgbpalette(2, 11, 15, 15);

		/* �㉺�̊D�F�̕��� */
		setfillstyle(SOLID_FILL, 1);
		bar(0, 0, 639, 15);
		bar(0, 368, 639, 383);
#else
		_remappalette(0, 0x060000L);
		_remappalette(1, 0x080808L);
		_remappalette(2, 0x0f0f0bL);

		/* �㉺�̊D�F�̕��� */
		_setcolor(1);
		_rectangle(_GFILLINTERIOR, 0, 0, 639, 15);
		_rectangle(_GFILLINTERIOR, 0, 368, 639, 383);
#endif
	}

#ifdef	__TURBOC__
	/* ���C���\�� */
	setcolor(WHITE);
	line(0, 0, 639, 0);
	line(0, 15, 639, 15);
	setcolor(opt_m ? WHITE : 2);
	line(0, 368, 639, 368);
	line(0, 384, 639, 384);

	/* ���o���N�A�N�e�B�u�i��M�f�[�^�o�b�t�@�Ƃ��Ďg�p���邽�߁j */
	setactivepage(1);
#else
	/* ���C���\�� */
	_setcolor(7);
	_moveto(0, 0);		_lineto(639, 0);
	_moveto(0, 15);		_lineto(639, 15);
	_setcolor(opt_m ? 7 : 2);
	_moveto(0, 368);	_lineto(639, 368);
	_moveto(0, 384);	_lineto(639, 384);

	/* ���o���N�A�N�e�B�u�i��M�f�[�^�o�b�t�@�Ƃ��Ďg�p���邽�߁j */
	_setactivepage(1);
#endif
	w_dspstr(scrp, 1, 0, C_GREEN, "��");
	w_dspstr(scrp, 3, 0, C_YELLOW, "�x�|�s�d�q�l");
	w_dspstr(scrp, 15, 0, C_GREEN, "��");

	w_dspstr(scrp, 45, 0, C_WHITE, "DTR�� DSR�� RTS�� CTS�� CD�� CI��");
	w_dspstr(scrp, 42, 23, C_WHITE, "���ݎ���");
	w_dspstr(scrp, 51, 23, C_GREEN, " 0:00:00");
	w_dspstr(scrp, 62, 23, C_WHITE, "�ʐM����");
	w_dspstr(scrp, 71, 23, C_YELLOW, " 0:00:00");

	dsp_ctrl(1);	/* �R���g���[�����C���C�����\�� */

	tm_open();	/* �^�C�}�[�I�[�v�� */
}

/*=======================================================================
 |
 |		�����������Q
 |
 |	int	initialize2()
 |
 =======================================================================*/
static	int	initialize2(void)
{
	FILE	*fp;
	char	*vbufp;
	int	st = 0;

	/* �Z���^�[���t�@�C���ǂݍ��� */
	if ((fp = fopen(FN_CNT, "rb")) != NULL) {
		fread(center, sizeof(center), 1, fp);
		fclose(fp);
	} else
		st = 1;

	/* ���ݒ�t�@�C���ǂݍ��� */
	if ((fp = fopen(FN_ENV, "rb")) != NULL) {
		fread(&modem, sizeof(modem), 1, fp);
		fclose(fp);
	} else
		st = 2;

	/* ��M�f�[�^�o�b�t�@�P�s�ڃN���A */
	FP_SEG(vbufp) = SEG_GVRAM;
	FP_OFF(vbufp) = 0;
	memset(vbufp, 0, 80);

	/* �f�o�C�X�G���[�\���E�B���h�E�m�� */
	wp_deverr = w_alloc(44, 7);

	/* �f�o�C�X�G���[�n���h���Z�b�g */
#ifdef	__TURBOC__
	harderr(hard_err);
#else
	_harderr(hard_err);
#endif

	return(st);
}

/*=======================================================================
 |
 |		�I������
 |
 |	void	terminate()
 |
 =======================================================================*/
static	void	terminate(void)
{
	int	i;

	/* �O�����A */
	for (i = 0; i < 11; i++)
		t_setfont(0x7621+i, save_gaiji[i]);

	tm_close();		/* �^�C�}�[�g�p�I�� */

#ifdef	__TURBOC__
	setactivepage(0);	/* �\�o���N�A�N�e�B�u */
	setrgbpalette(0, 0, 0, 0);	/* �p���b�g�Z�b�g */
	closegraph();
	clrscr();		/* �e�L�X�g��ʃN���A */
#else
	_setactivepage(0);	/* �\�o���N�A�N�e�B�u */
	_remappalette(0, 0x000000L);	/* �p���b�g�Z�b�g */
	_setvideomode(_98TEXT80);	/* �e�L�X�g���[�h�Z�b�g */
#endif
	puts("\x1b[>1l");	/* �ŉ��ʍs�V�X�e���g�p */
	t_fput(&func_save);	/* �t�@���N�V�����L�[���x���ݒ� */
	t_csron();		/* �J�[�\���\�� */
	_dos_setvect(0x06, stop_vect);	/* �r�s�n�o�L�[���荞�݃x�N�^���A */
}

/*=======================================================================
 |
 |		�R���g���[�����C���C�����\��
 |
 |	void	dsp_ctrl(flag)
 |
 |		int	flag;		�O�F�ω������������̂̂ݕ\��
 |					�P�F�S�Ă�\��
 |
 =======================================================================*/
void	dsp_ctrl(int flag)
{
	static	int	dtr, dsr, rts, cts, cd, ci;
	int	w_dtr, w_dsr, w_rts, w_cts, w_cd, w_ci;
	long	n_time;
	static	long	s_time;
	struct	tm	*ltm;
	int	itime;
	int	far *vp;
	long	far *vlp;
	char	buf[9], *cp;
	long	on, off;

	if (opt_m == 0) {
		FP_SEG(vlp) = SEG_ATTR;
		on = ((long)C_GREEN << 16) | C_GREEN;
		off = ((long)C_RED << 16) | C_RED;
	} else {
		FP_SEG(vlp) = SEG_TVRAM;
		on = 0x7c817c01;
		off = 0x7b817b01;
	}

	/* �c�s�q�\�� */
	if ((w_dtr = (rs1_cmdreg & CMD_DTR)) != dtr || flag) {
		FP_OFF(vlp) = 48 * 2;
		*vlp = w_dtr ? on : off;
		dtr = w_dtr;
	}

	/* �c�r�q�\�� */
	if ((w_dsr = rs1_dsr()) != dsr || flag) {
		FP_OFF(vlp) = 54 * 2;
		*vlp = w_dsr ? on : off;
		dsr = w_dsr;
	}

	/* �q�s�r�\�� */
	if ((w_rts = (rs1_cmdreg & CMD_DTR)) != rts || flag) {
		FP_OFF(vlp) = 60 * 2;
		*vlp = w_rts ? on : off;
		rts = w_rts;
	}

	/* �b�s�r�\�� */
	if ((w_cts = rs1_cts()) != cts || flag) {
		FP_OFF(vlp) = 66 * 2;
		*vlp = w_cts ? on : off;
		cts = w_cts;
	}

	/* �b�c�\�� */
	if ((w_cd = rs1_cd()) != cd || flag) {
		FP_OFF(vlp) = 71 * 2;
		*vlp = w_cd ? on : off;
		cd = w_cd;
	}

	/* �b�h�\�� */
	if ((w_ci = rs1_ci()) != ci || flag) {
		FP_OFF(vlp) = 76 * 2;
		*vlp = w_ci ? on : off;
		ci = w_ci;
	}

	time(&n_time);

	if (n_time != s_time) {
		/* �������\�� */
		ltm = localtime(&n_time);
		sprintf(buf, "%2u:%02u:%02u"
				, ltm->tm_hour, ltm->tm_min, ltm->tm_sec);
		FP_SEG(vp) = SEG_TVRAM;
		FP_OFF(vp) = (51 + 23 * 80) * 2;
		for (cp = buf; *cp != '\0'; cp++)
			*vp++ = *cp;

		if (conxxxt) {
			/* �ʐM���ԕ\�� */
			itime = n_time - c_time;
			sprintf(buf, "%2u:%02u:%02u"
				, itime / 3600, (itime / 60) % 60, itime % 60);
			FP_SEG(vp) = SEG_TVRAM;
			FP_OFF(vp) = (71 + 23 * 80) * 2;
			for (cp = buf; *cp != '\0'; cp++)
				*vp++ = *cp;
		}
		s_time = n_time;
	}
}

/*=======================================================================
 |
 |		�b�n�l�l�`�m�c�D�b�n�l�N������
 |
 |	void	ms_dos()
 |
 =======================================================================*/
static	void	ms_dos(void)
{
	scr_save();		/* ����ʕۑ� */
	terminate();		/* �I������ */
	printf("'EXIT' �łx�|�s�d�q�l�ɖ߂�܂�.\n");
	spawnlp(0, "COMMAND", "COMMAND", 0L);	/* COMMAND.COM ���s */
	initialize();		/* ���������� */
	scr_restore();		/* ����ʕ��A */
}

/*=======================================================================
 |
 |		���[�U�t�@���N�V�����R�}���h���s����
 |
 |	void	user_func(cmd)
 |
 |		char	*cmd;		���s�R�}���h
 |
 =======================================================================*/
static	void	user_func(char *cmd)
{
	scr_save();		/* ����ʕۑ� */
	terminate();		/* �I������ */
	system(cmd);		/* �R�}���h���s */
	initialize();		/* ���������� */
	scr_restore();		/* ����ʕ��A */
}

/*=======================================================================
 |
 |		����ʕۑ�����
 |
 |	void	scr_save()
 |
 =======================================================================*/
static	void	scr_save(void)
{
	int	far *vp;

	/* �u�q�`�l�̓��e��ۑ��G���A�ɓ]�� */
	FP_OFF(vp) = 160;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(save_text, vp, 160 * 22);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(save_attr, vp, 160 * 22);
}

/*=======================================================================
 |
 |		����ʕ��A����
 |
 |	void	scr_restore()
 |
 =======================================================================*/
static	void	scr_restore(void)
{
	int	far *vp;

	/* �u�q�`�l�ɕۑ��G���A�̓��e��]�� */
	FP_OFF(vp) = 160;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(vp, save_text, 160 * 22);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(vp, save_attr, 160 * 22);
}

/*=======================================================================
 |
 |		�o�b�N�X�N���[������
 |
 |	void	back_scroll()
 |
 =======================================================================*/
static	void	back_scroll(void)
{
	int	loop_sw, bufc, bufn, i;
	char	*vbufp;

	/* �P��ʕ��\������Ă��Ȃ������牽�����Ȃ� */
	if (vbufn < 22)
		return;

	if (opt_m == 0) {
#ifdef	__TURBOC__
		setrgbpalette(0, 0, 6, 0);	/* �w�i�F�ύX */
#else
		_remappalette(0, 0x000600L);	/* �w�i�F�ύX */
#endif
	}

	t_csroff();		/* �J�[�\������ */
	scr_save();		/* ����ʕۑ� */
	dsp_func(func0);	/* �t�@���N�V�����L�[�\�� */

	bufc = vbufc;		/* �o�b�t�@�J�E���^�Z�b�g */
	bufn = vbufn;		/* �o�b�t�@���s���Z�b�g */

	loop_sw = 1;
	while (loop_sw) {
		switch (getchr()) {
		case KEY_DOWN:
			if (bufn < vbufn) {
				/* ��X�N���[�� */
				bufn++;
				bufc = ++bufc % MAX_VBUF;
				scroll_up();
				FP_SEG(vbufp) = SEG_GVRAM + bufc * 5;
				FP_OFF(vbufp) = 0;
				w_dspnstr(scrp, 0, 22, C_WHITE, vbufp, 80);
			}
			break;
		case KEY_UP:
			if (bufn - 22 >= 0) {
				/* ���X�N���[�� */
				bufn--;
				bufc = (bufc + MAX_VBUF - 1) % MAX_VBUF;
				scroll_down();
				FP_SEG(vbufp) = SEG_GVRAM +
				((bufc + MAX_VBUF - 21) % MAX_VBUF) * 5;
				FP_OFF(vbufp) = 0;
				w_dspnstr(scrp, 0, 1, C_WHITE, vbufp, 80);
			}
			break;
		case KEY_HOME:
			w_dspspc(scrp, 0, 1, 80*22);
			bufc = (vbufc == vbufn) ? 0 : (vbufc + 1);
			FP_OFF(vbufp) = 0;
			for (i = 0 ; ; i++) {
				FP_SEG(vbufp) = SEG_GVRAM + bufc * 5;
				w_dspnstr(scrp, 0, i+1, C_WHITE, vbufp, 80);
				if (i == 21)
					break;
				bufc = (bufc + 1) % MAX_VBUF;
			}
			bufn = 21;
			break;
		default:
			loop_sw = 0;
			break;
		}
	}

	scr_restore();			/* ����ʕ��A */

	if (opt_m == 0) {
#ifdef	__TURBOC__
		setrgbpalette(0, 0, 0, 6);	/* �w�i�F�ύX */
#else
		_remappalette(0, 0x060000L);	/* �w�i�F�����ɖ߂� */
#endif
	}
}

/*=======================================================================
 |
 |		�r�s�n�o�L�[���荞�ݏ���
 |
 |	void	int_stop()
 |
 =======================================================================*/
static	void	interrupt far	int_stop(void)
{
}

/*=======================================================================
 |
 |		�P�������M�i���M�^�C���A�E�g�`�F�b�N�t���j
 |
 |	void	rs1_putc(c)
 |
 |		int	c;		���M����
 |
 =======================================================================*/
void	rs1_putc(int c)
{
	if ((rs1_sts() & STS_TXRDY) == 0) {
		tm_set(1, 300);		/* �R�b */
		while ((rs1_sts() & STS_TXRDY) == 0) {
			if (tm_check(1))
				return;
		}
		tm_stop(1);
	}
	rs1_put(c);
}

#ifdef	__TURBOC__
int	hard_err(unsigned deverror, unsigned errcode, unsigned far *devhdr)
#else
void far hard_err(unsigned deverror, unsigned errcode, unsigned far *devhdr)
#endif
{
	int	mode, ch;
	char	*msg;

	if (deverror & 0x8000) {
#ifdef	__TURBOC__
		hardresume(3);
#else	
		_hardresume(3);
#endif
	}

	switch (errcode) {
	case 0:
		msg = "�t���b�s�[���������݋֎~�ɂȂ��Ă��܂�";
		break;
	case 2:
		msg = "�h���C�u�̏������ł��Ă��܂���";
		break;
	default:
		msg = "�t�@�C���A�N�Z�X�ŃG���[���������܂���";
		break;
	}

	w_display(wp_deverr, 34, 15, "�t�@�C���A�N�Z�X�G���[", RC_WHITE);
	w_dspstr(wp_deverr, 3, 3, RC_WHITE, msg);
	w_dspstr(wp_deverr, 5, 5, RC_WHITE, "�Ď��s  ���~");

	mode = 0;
	for (;;) {
		if (mode == 0)
			w_color(wp_deverr, 5, 5, 6, C_CYAN);
		else
			w_color(wp_deverr, 13, 5, 4, C_CYAN);
		ch = getch();
		if (ch == KEY_CR)
			break;
		if (mode == 0)
			w_color(wp_deverr, 5, 5, 6, C_WHITE);
		else
			w_color(wp_deverr, 13, 5, 4, C_WHITE);
		if (ch == KEY_RIGHT || ch == KEY_LEFT)
			mode = (mode + 1) % 2;
	}

	w_erase(wp_deverr);

#ifdef	__TURBOC__
	hardresume(mode ? 3 : 1);
	return(2);
#else
	_hardresume(mode ? 3 : 1);
#endif
}