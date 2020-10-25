/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �t�@�C���]������			*
 *		�t�@�C����	: file.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <string.h>
#include <sys\types.h>
#include <sys\stat.h>
#include "rs232c.h"
#include "yterm.h"

/*	�����֐�	*/
static	void	rcv_text(void);
static	void	snd_text(void);
static	void	rcv_xmodem(void);
static	void	snd_xmodem(void);

/*	�����ϐ�	*/
static	char	rfname[16];	/* ��M�t�@�C���� */

/*=======================================================================
 |
 |		�t�@�C���]������
 |
 |	void	file()
 |
 =======================================================================*/
void	file(void)
{
	static	char	*item1[] = {
		"�e�L�X�g�t�@�C����M",
		"�e�L�X�g�t�@�C�����M",
		"�w�l�n�c�d�l��M",
		"�w�l�n�c�d�l���M"
	};
	static	char	*item2[] = {
		"�e�L�X�g�t�@�C����M�I��",
		"�e�L�X�g�t�@�C�����M",
		"�w�l�n�c�d�l��M",
		"�w�l�n�c�d�l���M"
	};
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, " ���~ ", NULL
	};
	WINDOW	*wp;
	int 	sel;

	t_csroff();		/* �J�[�\������ */
	dsp_func(func1);	/* �t�@���N�V�����L�[�\�� */

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(24, 6, 30, 8, "�t�@�C���]��", RC_WHITE);

	/* �����I�� */
	sel = w_select(wp, rcv_fp == NULL ? item1 : item2, 4, 0
							, C_YELLOW|A_REVERSE);
	switch (sel) {
	case 0:		/* �e�L�X�g�t�@�C����M�i�I���j */
		if (rcv_fp == NULL)
			rcv_text();
		else
			rcv_text_end();
		break;
	case 1:		/* �e�L�X�g�t�@�C�����M */
		snd_text();
		break;
	case 2:		/* �w�l�n�c�d�l��M */
		rcv_xmodem();
		break;
	case 3:		/* �w�l�n�c�d�l���M */
		snd_xmodem();
		break;
	}

	w_close(wp);
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C����M����
 |
 |	void	rcv_text()
 |
 =======================================================================*/
static	void	rcv_text(void)
{
	WINDOW	*wp;
	int	mode, ch;

	memset(rfname, ' ', 15);	/* ��M�t�@�C�����N���A */
	mode = 0;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(20, 9, 40, 11, "�e�L�X�g�t�@�C����M", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "��M�t�@�C���� [                 ]");
retry:
	/* ��M�t�@�C�������� */
	ch = w_inputs(wp, rfname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	if (access(rfname, 0) == 0) {
		/* �ǉ��^�㏑���I�� */
		w_dspstr(wp, 3, 6, wp->color, "�ǉ�  �㏑��");
		for (;;) {
			if (mode == 0)
				w_color(wp, 3, 6, 4, C_CYAN);
			else
				w_color(wp, 9, 6, 6, C_CYAN);
			ch = getchr();
			if (ch == KEY_ESC || ch == KEY_F9) {
				w_close(wp);
				return;
			} else if (ch == KEY_CR)
				break;
			if (mode == 0)
				w_color(wp, 3, 6, 4, C_WHITE);
			else
				w_color(wp, 9, 6, 6, C_WHITE);
			if (ch == KEY_RIGHT || ch == KEY_LEFT)
				mode = (mode + 1) % 2;
		}
	} else
		mode = 1;

	/* ��M�t�@�C���I�[�v�� */
	if ((rcv_fp = fopen(rfname, mode ? "wb" : "ab")) == NULL) {
		w_dspstr(wp, 3, 8, C_MAGENTA|A_REVERSE, "�t�@�C�����J���܂���");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 12);
		w_dspspc(wp, 3, 8, 20);
		goto 	retry;
	}

	/* ��M�t�@�C�����\�� */
	w_printf(scrp, 1, 23, C_GREEN|A_REVERSE, " [%s]�Ɏ�M�� ", rfname);

	w_close(wp);
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C����M�I������
 |
 |	void	rcv_text_end()
 |
 =======================================================================*/
void	rcv_text_end(void)
{
	if (rcv_fp != NULL) {
		fclose(rcv_fp);
		rcv_fp = NULL;
		w_dspspc(scrp, 1, 23, 40);
	}
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C�����M����
 |
 |	void	snd_text()
 |
 =======================================================================*/
static	void	snd_text(void)
{
	WINDOW	*wp;
	char	sfname[16];
	int	ch;

	memset(sfname, ' ', 15);	/* ���M�t�@�C�����N���A */

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(20, 9, 40, 9, "�e�L�X�g�t�@�C�����M", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "���M�t�@�C���� [                 ]");
retry:
	/* ���M�t�@�C�������� */
	ch = w_inputs(wp, sfname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	/* ���M�t�@�C���I�[�v�� */
	if ((snd_fp = fopen(sfname, "r")) == NULL) {
		w_dspstr(wp, 3, 6, C_MAGENTA|A_REVERSE, "�t�@�C�����J���܂���");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 20);
		goto 	retry;
	}

	/* ���M�t�@�C�����\�� */
	w_dspspc(scrp, 1, 23, 40);
	w_printf(scrp, 1, 23, C_YELLOW|A_REVERSE, " [%s]�𑗐M�� ", sfname);

	w_close(wp);
	send_wait = 0;
}

/*=======================================================================
 |
 |		�e�L�X�g�t�@�C�����M�I������
 |
 |	void	snd_text_end()
 |
 =======================================================================*/
void	snd_text_end(void)
{
	if (snd_fp != NULL) {
		fclose(snd_fp);
		snd_fp = NULL;
		w_dspspc(scrp, 1, 23, 40);
		if (rcv_fp != NULL)
			w_printf(scrp, 1, 23, C_GREEN|A_REVERSE, " [%s]�Ɏ�M�� ", rfname);
	}
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l��M����
 |
 |	void	rcv_xmodem()
 |
 =======================================================================*/
static	void	rcv_xmodem(void)
{
	WINDOW	*wp;
	char	fname[16];
	int	ch;
	FILE	*fp;

	memset(fname, ' ', 15);		/* ��M�t�@�C�����N���A */

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(20, 9, 40, 11, "�w�l�n�c�d�l��M", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "��M�t�@�C���� [                 ]");
retry:
	/* ��M�t�@�C�������� */
	ch = w_inputs(wp, fname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	/* ��M�t�@�C���I�[�v�� */
	if ((fp = fopen(fname, "wb")) == NULL) {
		w_dspstr(wp, 3, 6, C_MAGENTA|A_REVERSE, "�t�@�C�����J���܂���");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 20);
		goto 	retry;
	}

	w_dspstr(wp, 3, 6, wp->color, "��M�u���b�N�m���D0");

	/* �w�l�n�c�d�l��M */
	switch (x_receive(fp, wp)) {
	case 0:
		w_dspstr(wp, 3, 8, RC_GREEN, "����I��");
		break;
	case -1:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "�G���[���������܂���");
		break;
	case -2:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "���~���܂���");
		break;
	}
	beep();
	tm_wait(200);

	fclose(fp);

	w_close(wp);
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l���M����
 |
 |	void	snd_xmodem()
 |
 =======================================================================*/
static	void	snd_xmodem(void)
{
	WINDOW	*wp;
	char	fname[16];
	int	ch;
	FILE	*fp;
	struct	stat	stat;

	memset(fname, ' ', 15);		/* ���M�t�@�C�������� */

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(20, 9, 40, 11, "�w�l�n�c�d�l���M", RC_WHITE);
	w_dspstr(wp, 3, 4, wp->color, "���M�t�@�C���� [                 ]");
retry:
	/* ���M�t�@�C�������� */
	ch = w_inputs(wp, fname, 20, 4, 15, 0x141, wp->color);
	if (ch == KEY_ESC || ch == KEY_F9) {
		w_close(wp);
		return;
	}

	/* ���M�t�@�C���I�[�v�� */
	if ((fp = fopen(fname, "rb")) == NULL) {
		w_dspstr(wp, 3, 6, C_MAGENTA|A_REVERSE, "�t�@�C�����J���܂���");
		tm_wait(150);
		w_dspspc(wp, 3, 6, 20);
		goto 	retry;
	}

	/* ���M�u���b�N���\�� */
	fstat(fileno(fp), &stat);
	w_printf(wp, 3, 6, wp->color, "���M�u���b�N�m���D0      / %ld", (stat.st_size + 127) / 128);

	/* �w�l�n�c�d�l���M */
	switch (x_send(fp, wp)) {
	case 0:
		w_dspstr(wp, 3, 8, RC_GREEN, "����I��");
		break;
	case -1:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "�G���[���������܂���");
		break;
	case -2:
		w_dspstr(wp, 3, 8, RC_MAGENTA, "���~���܂���");
		break;
	}
	beep();
	tm_wait(200);

	fclose(fp);

	w_close(wp);
}