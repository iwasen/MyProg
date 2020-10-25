/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �e�����v���O����			*
 *		�t�@�C����	: term.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <ctype.h>
#include <time.h>
#include <string.h>
#include "rs232c.h"
#include "yterm.h"

/*	�����֐� */
static	void	dsp_dial(WINDOW *, int, int);
static	void	dsp_modem(WINDOW *, int, int);
static	int	inp_param(WINDOW *);
static	WINDOW	*dsp_param(int, int, char *);
static	void	dsp_speed(WINDOW *, int, int);
static	void	dsp_parity(WINDOW *, int, int);
static	void	dsp_chrlen(WINDOW *, int, int);
static	void	dsp_stplen(WINDOW *, int, int);
static	void	dsp_xon(WINDOW *, int, int);
static	void	dsp_echo(WINDOW *, int, int);
static	void	dsp_retkey(WINDOW *, int, int);
static	void	dsp_prompt(WINDOW *, int);
static	WINDOW	*dsp_center(int, int, char *, int);
static	int	sel_center(WINDOW *, int);
static	int	auto_call(void);
static	int	rcv_ack(WINDOW *);
static	void	auto_call_err(WINDOW *, int, int);
static	void	auto_login(void);

struct	select	{
	int	x;
	int	len;
};

/*=======================================================================
 |
 |		�ڑ�����
 |
 |	void	setuzoku()
 |
 =======================================================================*/
void	setuzoku(void)
{
	static	int	speed[] = {
		BLT_300, BLT_1200, BLT_2400, BLT_4800, BLT_9600
	};
	static	int	parity[] = {
		PARITY_NONE, PARITY_ODD, PARITY_EVEN
	};
	static	int	chrlen[] = {
		CHR_8, CHR_7
	};
	static	int	stplen[] = {
		STP_1, STP_1_5, STP_2
	};
	static	int	xon[] = {
		FLOW_SOFT, FLOW_NONE
	};
	RSPRM	rsprm;
	WINDOW	*wp, *wp2;
	int	sel;

	/* �Z���^�[���\�� */
	wp = dsp_center(22, 4, "�ڑ���Z���^�[�I��", 1);

	/* �ڑ���Z���^�[�I�� */
	sel = sel_center(wp, 1);

	if (sel == 10) {
		/* �}�j���A���ݒ� */
		wp2 = dsp_param(10, 3, "�ʐM�p�����[�^�ݒ�");
		if (inp_param(wp2) == 0)
			sel = -1;
		w_close(wp2);
	}

	if (sel != -1) {
		/* �Z���^�[�����o�� */
		if (sel != 10)
			cprm = center[sel];

		/* �q�r�|�Q�R�Q�b�ăI�[�v�� */
		rsprm.sync_mode = ASYNC;
		rsprm.duplex = DUPLEX_FULL;
		rsprm.blt = speed[cprm.speed];
		rsprm.parity = parity[cprm.parity];
		rsprm.chr_len = chrlen[cprm.chrlen];
		rsprm.stp_len = stplen[cprm.chrlen];
		rsprm.flow_ctrl = xon[cprm.xon];
		rsprm.dtr = DTR_ON;
		rsprm.buf_size = 8192;
		rsprm.bufp = NULL;
		rs1_reopen(&rsprm);

		/* �Z���^�[���\�� */
		w_dspstr(scrp, 19, 0, C_CYAN, cprm.name);
	}

	w_close(wp);

	if (sel != -1 && cprm.tel[0] != '\0') {
		/* ����ڑ����� */
		if (auto_call() != 0) {
			setudan();
			sel = -1;
		}
	}

	if (sel != -1) {
		conxxxt = 1;		/* �ڑ����t���O�Z�b�g */
		time(&c_time);		/* �ڑ��������o�� */
		auto_login();		/* �I�[�g���O�C�� */
	}
}

/*=======================================================================
 |
 |		�Z���^�[���o�^����
 |
 |	void	touroku()
 |
 =======================================================================*/
void	touroku(void)
{
	WINDOW	*wp, *wp2;
	int	sel;
	FILE	*fp;

	/* �Z���^�[���\�� */
	wp = dsp_center(22, 4, "�Z���^�[�o�^", 0);

	for (;;) {
		sel = sel_center(wp, 0);	/*  �Z���^�[�I�� */
		if (sel != -1) {
			cprm = center[sel];
			wp2 = dsp_param(10, 3, "�ʐM�p�����[�^�ݒ�");
			if (inp_param(wp2)) {	/* �ʐM�p�����[�^���� */
				center[sel] = cprm;
				/* �t�@�C���ɕۑ� */
				if ((fp = fopen(FN_CNT, "wb")) != NULL) {
					fwrite(center, sizeof(center), 1, fp);
					fclose(fp);
				}
				w_close(wp2);
				w_dspstr(wp, 6, sel+3, wp->color
						, "                        ");
				w_dspstr(wp, 6, sel+3, wp->color, cprm.name);
			} else
				w_close(wp2);
		} else
			break;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		���ݒ菈��
 |
 |	void	settei()
 |
 =======================================================================*/
void	settei(void)
{
	static	char	*func1[] = {
		" ���s ", NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " ���~ ", NULL
	};
	static	SCRDAT	scr[] = {
		{3, 3, "�d�b���            [ �_�C����  �g�[�� ]"},
		{3, 4, "���f���R�}���h���  [ �`�s�R�}���h  �u�Q�T������ ]"},
		{3, 5, "�ڑ����⏕�R�}���h  [                            ]"},
		{3, 6, "���E�Q�ݒ�          [        ] [                 ]"},
		{0, 0, 0}
	};
	WINDOW	*wp;
	int	item, loop_sw, ch;
	FILE	*fp;

	dsp_func(func1);	/* �t�@���N�V�����L�[�\�� */

	/* ���ݒ�E�B���h�E�\�� */
	wp = w_open(10, 6, 58, 8, "���ݒ�", RC_WHITE);
	w_dspscr(wp, scr);

	/* ���ݒ�l�\�� */
	dsp_dial(wp, modem.dial, C_CYAN);
	dsp_modem(wp, modem.modem, C_CYAN);
	w_dspstr(wp, 25, 5, wp->color, modem.mdcmd);
	w_dspstr(wp, 25, 6, wp->color, modem.f2dsp);
	w_dspstr(wp, 36, 6, wp->color, modem.f2cmd);

	item = 0;
	loop_sw = 1;
	while (loop_sw) {
		w_color(wp, 2, item+3, 20, C_YELLOW);
		switch (item) {
		case 0:		/* �_�C������ʐݒ� */
			ch = getchr();
			dsp_dial(wp, modem.dial, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				modem.dial = (modem.dial + 1) % 2;
				break;
			}
			dsp_dial(wp, modem.dial, C_CYAN);
			break;
		case 1:		/* ���f����ސݒ� */
			ch = getchr();
			dsp_modem(wp, modem.modem, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				modem.modem = (modem.modem + 1) % 2;
				break;
			}
			dsp_modem(wp, modem.modem, C_CYAN);
			break;
		case 2:		/* �ڑ����⏕�R�}���h�ݒ� */
			ch = w_inputs(wp, modem.mdcmd, 25, 5, 26, 0x140, wp->color);
			break;
		case 3:		/* ���E�Q�ݒ� */ 
			ch = w_inputs(wp, modem.f2dsp, 25, 6, 6, 0x141, wp->color);
			if (ch == KEY_TAB)
				ch = w_inputs(wp, modem.f2cmd, 36, 6, 15, 0x141, wp->color);
			break;
		}
		w_color(wp, 2, item+3, 20, C_WHITE);

		switch (ch) {
		case KEY_F1:	/* ���s */
			if ((fp = fopen(FN_ENV, "wb")) != NULL) {
				fwrite(&modem, sizeof(modem), 1, fp);
				fclose(fp);
			}
			loop_sw = 0;
			break;
		case KEY_F9:	/* ���~ */
		case KEY_ESC:
			loop_sw = 0;
			break;
		case KEY_UP:
			item = (item + 3) % 4;
			break;
		case KEY_CR:
		case KEY_DOWN:
			item = (item + 1) % 4;
			break;
		}
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		�_�C������ʕ\��
 |
 |	void	dsp_dial(wp, dial, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	dial;		�_�C�������
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_dial(WINDOW *wp, int dial, int color)
{
	static	struct	select	sel[2] = {
		{25, 8}, {35, 6}
	};

	w_color(wp, sel[dial].x, 3, sel[dial].len, color);
}

/*=======================================================================
 |
 |		���f����ޕ\��
 |
 |	void	dsp_modem(wp, modem, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	modem;		���f�����
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_modem(WINDOW *wp, int modem, int color)
{
	static	struct	select	sel[2] = {
		{25, 12}, {39, 12}
	};

	w_color(wp, sel[modem].x, 4, sel[modem].len, color);
}

/*=======================================================================
 |
 |		�ʐM�p�����[�^�\������
 |
 |	WINDOW	dsp_param(x, y, title)
 |
 |		int	x;		�E�B���h�E�\�����ʒu
 |		int	y;		�E�B���h�E�\���s�ʒu
 |		char	*title;		�^�C�g��
 |
 |		�Ԓl			�E�B���h�E�|�C���^
 |
 =======================================================================*/
static	WINDOW	*dsp_param(int x, int y, char *title)
{
	static	SCRDAT	scr[] = {
		{3, 3, "�Z���^�[��            [                          ]"},
		{3, 4, "�d�b�ԍ�              [                ]"},
		{3, 5, "�ʐM���x              [ 300  1200  2400  4800  9600 ]"},
		{3, 6, "�p���e�B�[            [ ����  �  ���� ]"},
		{3, 7, "�L�����N�^��          [ �W  �V ]"},
		{3, 8, "�X�g�b�v�r�b�g��      [ �P  �P.�T  �Q ]"},
		{3, 9, "�w�n�m�^�w�n�e�e      [ �L��  ���� ]"},
		{3,10, "���[�J���G�R�[�o�b�N  [ ����  �L�� ]"},
		{3,11, "���^�[���L�[          [ �b�q  �b�q�{�k�e ]"},
		{3,12, "�t�@�C�����M�������   [    ]"},
		{3,13, "���E�R�ݒ�            [        ] [                 ]"},
		{3,14, "�ڑ����⏕�R�}���h    [                            ]"},
		{3,15, "���_�C�����Ԋu        [    ]"},
		{3,16, "�I�[�g���O�C��"},
		{3,17, "[                                                  ]"},
		{0, 0, 0}
	};
	WINDOW	*wp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(x, y, 58, 19, title, RC_WHITE);
	w_dspscr(wp, scr);

	/* �p�����[�^�ݒ�l�\�� */
	w_dspstr(wp, 27, 3, wp->color, cprm.name);
	w_dspstr(wp, 27, 4, wp->color, cprm.tel);
	dsp_speed(wp, cprm.speed, C_CYAN);
	dsp_parity(wp, cprm.parity, C_CYAN);
	dsp_chrlen(wp, cprm.chrlen, C_CYAN);
	dsp_stplen(wp, cprm.stplen, C_CYAN);
	dsp_xon(wp, cprm.xon, C_CYAN);
	dsp_echo(wp, cprm.echo, C_CYAN);
	dsp_retkey(wp, cprm.retkey, C_CYAN);
	dsp_prompt(wp, cprm.prompt);
	w_dspstr(wp, 27, 13, wp->color, cprm.f3dsp);
	w_dspstr(wp, 38, 13, wp->color, cprm.f3cmd);
	w_dspstr(wp, 27, 14, wp->color, cprm.mdcmd);
	w_dspstr(wp, 27, 15, wp->color, cprm.redial);
	w_dspstr(wp, 5, 17, wp->color, cprm.login);

	return(wp);
}

/*=======================================================================
 |
 |		�ʐM���x�\��
 |
 |	void	dsp_speed(wp, speed, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	speed;		�ʐM���x
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_speed(WINDOW *wp, int speed, int color)
{
	static	struct	select	sel[5] = {
		{27, 3}, {32, 4}, {38, 4}, {44, 4}, {50, 4}
	};

	w_color(wp, sel[speed].x, 5, sel[speed].len, color);
}

/*=======================================================================
 |
 |		�p���e�B�[�\��
 |
 |	void	dsp_parity(wp, parity, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	parity;		�p���e�B�[
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_parity(WINDOW *wp, int parity, int color)
{
	static	struct	select	sel[3] = {
		{27, 4}, {33, 4}, {39, 4}
	};

	w_color(wp, sel[parity].x, 6, sel[parity].len, color);
}

/*=======================================================================
 |
 |		�L�����N�^���\��
 |
 |	void	dsp_chrlen(wp, chrlen, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	chrlen;		�L�����N�^��
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_chrlen(WINDOW *wp, int chrlen, int color)
{
	static	struct	select	sel[2] = {
		{27, 2}, {31, 2}
	};

	w_color(wp, sel[chrlen].x, 7, sel[chrlen].len, color);
}

/*=======================================================================
 |
 |		�X�g�b�v�r�b�g���\��
 |
 |	void	dsp_stplen(wp, stplen, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	stplen;		�X�g�b�v�r�b�g��
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_stplen(WINDOW *wp, int stplen, int color)
{
	static	struct	select	sel[3] = {
		{27, 2}, {31, 5}, {38, 2}
	};

	w_color(wp, sel[stplen].x, 8, sel[stplen].len, color);
}

/*=======================================================================
 |
 |		�w�n�m�^�w�n�e�e�L���\��
 |
 |	void	dsp_xon(wp, xon, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	xon;		�w�n�m�^�w�n�e�e�L��
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_xon(WINDOW *wp, int xon, int color)
{
	static	struct	select	sel[2] = {
		{27, 4}, {33, 4}
	};

	w_color(wp, sel[xon].x, 9, sel[xon].len, color);
}

/*=======================================================================
 |
 |		�G�R�[�o�b�N�L���\��
 |
 |	void	dsp_echo(wp, echo, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	echo;		�G�R�[�o�b�N�L��
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_echo(WINDOW *wp, int echo, int color)
{
	static	struct	select	sel[2] = {
		{27, 4}, {33, 4}
	};

	w_color(wp, sel[echo].x, 10, sel[echo].len, color);
}

/*=======================================================================
 |
 |		���^�[���L�[�@�\�\��
 |
 |	void	dsp_speed(wp, retkey, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	retkey;		���^�[���L�[�@�\
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_retkey(WINDOW *wp, int retkey, int color)
{
	static	struct	select	sel[2] = {
		{27, 4}, {33, 10}
	};

	w_color(wp, sel[retkey].x, 11, sel[retkey].len, color);
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C�����M�v�����v�g�\��
 |
 |	void	dsp_speed(wp, prompt, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	prompt;		�v�����v�g
 |		int	color;		�\���F
 |
 =======================================================================*/
static	void	dsp_prompt(WINDOW *wp, int prompt)
{
	if (prompt == 0)
		w_dspspc(wp, 27, 12, 2);
	else if (prompt < 0x20)
		w_printf(wp, 27, 12, wp->color, "^%c", prompt + 0x40);
	else
		w_printf(wp, 27, 12, wp->color, "%c ", prompt);
}

/*=======================================================================
 |
 |		�ʐM�p�����[�^���͏���
 |
 |	int	inp_param(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 |		�Ԓl			�O�F���~�@�P�F���s
 |
 =======================================================================*/
static	int	inp_param(WINDOW *wp)
{
	int	item, loop_sw, ch, jikkou;

	item = 0;
	jikkou = 0;
	loop_sw = 1;
	while (loop_sw) {
		w_color(wp, 2, item+3, 22, C_YELLOW);
		switch (item) {
		case 0:		/* �Z���^�[������ */
			ch = w_inputs(wp, cprm.name, 27, 3, 24, 0x141, wp->color);
			break;
		case 1:		/* �d�b�ԍ����� */
			ch = w_inputs(wp, cprm.tel, 27, 4, 14, 0x140, wp->color);
			break;
		case 2:		/* �ʐM���x�ݒ� */
			ch = getchr();
			dsp_speed(wp, cprm.speed, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
				cprm.speed = (cprm.speed + 4) % 5;
				break;
			case KEY_RIGHT:
				cprm.speed = (cprm.speed + 1) % 5;
				break;
			}
			dsp_speed(wp, cprm.speed, C_CYAN);
			break;
		case 3:		/* �p���e�B�[�ݒ� */
			ch = getchr();
			dsp_parity(wp, cprm.parity, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
				cprm.parity = (cprm.parity + 2) % 3;
				break;
			case KEY_RIGHT:
				cprm.parity = (cprm.parity + 1) % 3;
				break;
			}
			dsp_parity(wp, cprm.parity, C_CYAN);
			break;
		case 4:		/* �L�����N�^���ݒ� */
			ch = getchr();
			dsp_chrlen(wp, cprm.chrlen, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.chrlen = (cprm.chrlen + 1) % 2;
				break;
			}
			dsp_chrlen(wp, cprm.chrlen, C_CYAN);
			break;
		case 5:		/* �X�g�b�v�r�b�g���ݒ� */
			ch = getchr();
			dsp_stplen(wp, cprm.stplen, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
				cprm.stplen = (cprm.stplen + 2) % 3;
				break;
			case KEY_RIGHT:
				cprm.stplen = (cprm.stplen + 1) % 3;
				break;
			}
			dsp_stplen(wp, cprm.stplen, C_CYAN);
			break;
		case 6:		/* �w�n�m�^�w�n�e�e�L���ݒ� */
			ch = getchr();
			dsp_xon(wp, cprm.xon, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.xon = (cprm.xon + 1) % 2;
				break;
			}
			dsp_xon(wp, cprm.xon, C_CYAN);
			break;
		case 7:		/* �G�R�[�o�b�N�L���ݒ� */
			ch = getchr();
			dsp_echo(wp, cprm.echo, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.echo = (cprm.echo + 1) % 2;
				break;
			}
			dsp_echo(wp, cprm.echo, C_CYAN);
			break;
		case 8:		/* ���^�[���L�[�ݒ� */
			ch = getchr();
			dsp_retkey(wp, cprm.retkey, C_WHITE);
			switch (ch) {
			case KEY_LEFT:
			case KEY_RIGHT:
				cprm.retkey = (cprm.retkey + 1) % 2;
				break;
			}
			dsp_retkey(wp, cprm.retkey, C_CYAN);
			break;
		case 9:		/* �t�@�C�����M�҂��v�����v�g�ݒ� */
			w_loc(wp, 27, 12);
			t_loc(csr1_x, csr1_y);
			t_csron();
			ch = getchr();
			t_csroff();
			if (isalnum(ch) || ch == KEY_LF)
				cprm.prompt = ch;
			else if (ch == KEY_DEL)
				cprm.prompt = 0;
			dsp_prompt(wp, cprm.prompt);
			break;
		case 10:	/* ���E�R�ݒ� */
			ch = w_inputs(wp, cprm.f3dsp, 27, 13, 6, 0x141, wp->color);
			if (ch == KEY_TAB)
				ch = w_inputs(wp, cprm.f3cmd, 38, 13, 15, 0x141, wp->color);
			break;
		case 11:	/* �ڑ����⏕�R�}���h�ݒ� */
			ch = w_inputs(wp, cprm.mdcmd, 27, 14, 26, 0x140, wp->color);
			break;
		case 12:	/* ���_�C�����Ԋu */
			ch = w_inputs(wp, cprm.redial, 27, 15, 2, 0x142, wp->color);
			break;
		case 13:	/* �I�[�g���O�C���R�}���h�ݒ� */
			ch = w_inputs(wp, cprm.login, 5, 17, 48, 0x140, wp->color);
			break;
		}
		w_color(wp, 2, item+3, 22, C_WHITE);

		switch (ch) {
		case KEY_F1:	/* ���s */
			jikkou  = 1;
			loop_sw = 0;
			break;
		case KEY_F9:	/* ���~ */
		case KEY_ESC:
			loop_sw = 0;
			break;
		case KEY_UP:
			item = (item + 13) % 14;
			break;
		case KEY_CR:
		case KEY_DOWN:
			item = (item + 1) % 14;
			break;
		}
	}
	return(jikkou);
}

/*=======================================================================
 |
 |		�Z���^�[���\������
 |
 |	WINDOW	*dsp_center(x, y, title, manual)
 |
 |		int	x;		�E�B���h�E�\�����ʒu
 |		int	y;		�E�B���h�E�\���s�ʒu
 |		char	*title;		�^�C�g��
 |		int	manual;		�O�F�}�j���A���ݒ薳���@�P�F�L��
 |
 |		�Ԓl			�E�B���h�E�|�C���^
 |
 =======================================================================*/
static	WINDOW	*dsp_center(int x, int y, char *title, int manual)
{
	static	SCRDAT	scr[] = {
		{3, 3, "1:"},
		{3, 4, "2:"},
		{3, 5, "3:"},
		{3, 6, "4:"},
		{3, 7, "5:"},
		{3, 8, "6:"},
		{3, 9, "7:"},
		{3,10, "8:"},
		{3,11, "9:"},
		{3,12, "0:"},
		{0, 0, 0}
	};
	WINDOW	*wp;
	int	i;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(x, y, 34, manual ? 15 : 14, title, RC_WHITE);

	w_dspscr(wp, scr);
	if (manual)
		w_dspstr(wp, 3, 13, wp->color, "M: �}�j���A���ݒ�");

	/* �Z���^�[���\�� */
	for (i = 0; i < 10; i++)
		w_dspstr(wp, 6, i+3, wp->color, center[i].name);

	return(wp);
}

/*=======================================================================
 |
 |		�Z���^�[�I������
 |
 |	int	sel_center(wp, manual)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	manual;		�O�F�}�j���A���ݒ薳���@�P�F�L��
 |
 |		�Ԓl			�E�B���h�E�|�C���^
 |
 =======================================================================*/
static	int	sel_center(WINDOW *wp, int manual)
{
	static	char	*func1[] = {
		" ���s ", NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " ���~ ", NULL
	};
	static	int	sel;
	int	loop_sw, ch;

	if (manual == 0 && sel == 10)
		sel = 0;

	dsp_func(func1);
	loop_sw = 1;
	while (loop_sw) {
		w_color(wp, 2, sel+3, wp->nx-4, C_YELLOW);
		ch = getchr();
		w_color(wp, 2, sel+3, wp->nx-4, C_WHITE);
		switch (ch) {
		case KEY_CR:
		case KEY_F1:	/* ���s */
			loop_sw = 0;
			break;
		case KEY_ESC:
		case KEY_F9:	/* ���~ */
			return(-1);
		case KEY_UP:
			if (manual)
				sel = (sel + 10) % 11;
			else
				sel = (sel + 9) % 10;
			break;
		case KEY_DOWN:
			if (manual)
				sel = (sel + 1) % 11;
			else
				sel = (sel + 1) % 10;
			break;
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			sel = ch - '1';
			break;
		case '0':
			sel = 9;
			break;
		case 'M':
		case 'm':
			if (manual)
				sel = 10;
			break;
		}
	}

	return(sel);
}

/*=======================================================================
 |
 |		�d�b����ڑ�����
 |
 |	int	auto_call()
 |
 |		�Ԓl			�O�F�ڑ������@�P�F�ڑ����s�i���~�j
 |
 =======================================================================*/
static	int	auto_call(void)
{
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " ���~ ", NULL
	};
	WINDOW	*wp;
	char	command[128], *cp, *dialp;
	int	y, err, ch, resp, interval;

	dsp_func(func1);	/* �t�@���N�V�����L�[�\�� */

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(14, 5, 50, 15, "�d�b����ڑ���", RC_WHITE);

	y = 2;
	interval = atoi(cprm.redial);

	/* �P�b��ɂb�s�r���I�t�������烂�f���̓d�����n�e�e�Ƃ݂Ȃ� */
	tm_wait(100);
	if (rs1_cts() == 0) {
		auto_call_err(wp, y, 1);
		return(1);
	}

	/* ���f���R�}���h�ҏW */
	if (modem.modem == 0) {
		/* �`�s�R�}���h */
		sprintf(command, "ATZ|%s|%s|ATD%c%s"
				, modem.mdcmd, cprm.mdcmd
				, modem.dial ? 'T' : 'P', cprm.tel);
	} else {
		/* �u�Q�T������ */
		sprintf(command, "SET|%s|%s|CRN%s"
				, modem.mdcmd, cprm.mdcmd, cprm.tel);
	}

	cp = command;
redial:
	for (;;) {
		while (*cp == '|')
			cp++;

		dialp = cp;

		/* �R�}���h���M */
		y = w_newline(wp, y);
		w_loc(wp, 3, y);
		while (*cp != '|' && *cp != '\0') {
			rs1_putc(*cp);
			putch1(*cp++, wp->color);
			tm_wait(10);
		}
		rs1_clear();
		rs1_putc(KEY_CR);

		if (*cp == '\0')
			break;

		/* ������M */
		y = w_newline(wp, y);
		w_loc(wp, 3, y);
		if ((err = rcv_ack(wp)) != 0) {
			auto_call_err(wp, y, err);
			return(1);
		}

		tm_wait(50);
	}

	/* �b�c���I���ɂȂ�̂�҂� */
	y = w_newline(wp, y);
	w_loc(wp, 3, y);
	resp = 0;
	tm_set(0, 6000);
	for (;;) {
		if (tm_check(0)) {
			/* �^�C���A�E�g */
			if (interval == 0) {
				auto_call_err(wp, y, 4);
				return(1);
			}
			cp = dialp;
			goto redial;
		} else if (rs1_cd()) {
			/* �ڑ����� */
			rs1_clear();
			beep();
			break;
		} else if (rs1_rxc()) {
			/* ������M */
			ch = rs1_get();
			if (isalnum(ch)) {
				putch1(ch, wp->color);
				resp = 1;
			} else if (ch == KEY_CR && resp != 0) {
				if (interval != 0)
					tm_set(0, interval*100);
				else
					tm_set(0, 200);
			}
		} else if (ch = inkey()) {
			/* ���~���� */
			if (ch == KEY_ESC || ch == KEY_F9) {
				auto_call_err(wp, y, 2);
				return(1);
			}
		}
	}

	tm_stop(0);

	w_close(wp);

	return(0);
}

/*=======================================================================
 |
 |		���f��������M����
 |
 |	int	rcv_ack(wp)
 |
 |		�Ԓl			�O�F�m�艞���@���O�F�ے艞���i���~�j
 |
 =======================================================================*/
static	int	rcv_ack(WINDOW *wp)
{
	char	buf[128];
	int	err, ch, count;

	count = 0;
	tm_set(0, 500);
	for (;;) {
		if (tm_check(0)) {
			/* �������� */
			err = 3;
			break;
		} else if (rs1_rxc()) {
			/* ������M */
			ch = rs1_get();
			if (isalnum(ch)) {
				putch1(ch, wp->color);
				buf[count++] = ch;
			}
			if (ch == 0x0d && count != 0) {
				/* �����f�[�^�`�F�b�N */
				if (modem.modem == 0) {
					if (strncmp(buf, "OK", 2) == 0)
						err = 0;
					else
						err = 5;
				} else {
					if (strncmp(buf, "VAL", 3) == 0)
						err = 0;
					else
						err = 5;
				}
				break;
			}
		} else if (ch = inkey()) {
			/* ���~���� */
			if (ch == KEY_ESC || ch == KEY_F9) {
				err = 2;
				break;
			}
		}
	}
	tm_stop(0);
	return(err);
}

/*=======================================================================
 |
 |		�d�b����ڑ��G���[���b�Z�[�W�\������
 |
 |	void	auto_call_err(wp, y, err)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 =======================================================================*/
static	void	auto_call_err(WINDOW *wp, int y, int err)
{
	char	*msg;

	switch (err) {
	case 1:
		msg = "���f���̓d���������Ă��܂���";
		break;
	case 2:
		msg = "���~���܂���";
		break;
	case 3:
		msg = "���f���̉���������܂���";
		break;
	case 4:
		msg = "�ڑ��ł��܂���";
		break;
	case 5:
		msg = "���f���̉������s���ł�";
		break;
	default:
		msg = "";
	}
	y = w_newline(wp, y);
	w_dspstr(wp, 3, y, C_MAGENTA|A_REVERSE, msg);
	tm_wait(200);
	w_close(wp);
}

/*=======================================================================
 |
 |		���ԑ҂�����
 |
 |	void	tm_wait(tm)
 |
 |		int	tm;		�҂����ԁi�P�O�����P�ʁj
 |
 =======================================================================*/
void	tm_wait(int tm)
{
	tm_set(1, tm);
	while (tm_check(1) == 0)
		dsp_ctrl(0);
}

/*=======================================================================
 |
 |		�d�b����ؒf����
 |
 |	void	setudan()
 |
 =======================================================================*/
void	setudan(void)
{
	rcv_text_end();
	rs1_cmd(0x00, 0xff);
	w_dspspc(scrp, 19, 0, 24);
	conxxxt = 0;
}

/*=======================================================================
 |
 |		�ʐM�p�����[�^�\������
 |
 |	void	parameter()
 |
 =======================================================================*/
void	parameter(void)
{
	WINDOW	*wp;

	t_csroff();
	wp = dsp_param(10, 3, "�ʐM�p�����[�^�ݒ�l");
	getchr();
	w_close(wp);
}

/*=======================================================================
 |
 |		�I�[�g���O�C������
 |
 |	void	auto_login()
 |
 =======================================================================*/
static	void	auto_login(void)
{
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " ���~ ", NULL
	};
	char	*cp;
	int	r_flag, ch;

	if (cprm.login[0] == '\0')
		return;

	dsp_func(func1);

	w_dspstr(scrp, 1, 23, C_YELLOW|A_REVERSE, " �I�[�g���O�C���� ");

	r_flag = 0;
	for (cp = cprm.login; *cp != '\0'; ) {
		ch = inkey();
		if (ch == KEY_ESC || ch == KEY_F9)
			break;
		if (rs1_rxc()) {
			ch = rs1_get();
			putch2(ch);
			if (r_flag) {
				if (ch == *cp) {
					r_flag = 0;
					cp++;
				}
			}
		} else if (r_flag == 0) {
			switch (ch = *cp++) {
			case '_':
				tm_wait(100);
				break;
			case '%':
				r_flag = 1;
				break;
			case '|':
				ch = KEY_CR;
			default:
				rs1_putc(ch);
				if (cprm.echo) {
					putch2(ch);
				}
				tm_wait(10);
				break;
			}
		}
	}
	w_dspspc(scrp, 1, 23, 18);
}