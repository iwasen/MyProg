/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: �h�[���[�e�[�u���ێ�			*
 *		�t�@�C����	: dolly.c				*
 *									*
 ************************************************************************/

#include "bumper.h"

static	void	dsp_tbl(int);
static	void	dsp_data(int);
static	void	f_save(void);
static	void	f_load(int);
static	void	f_init(int);
static	void	f_write(void);
static	void	f_read(int);

/*=======================================================================
 |
 |		�h�[���[�e�[�u���ێ�
 |
 |	void	dolly()
 |
 =======================================================================*/
void	dolly()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{22,  1, "�o �� �p �[ �h �F �x �� �V �X �e ��"},
		{27,  3, "�h�[���[�m���e�[�u���ێ�"},
		{ 8,  7, "No.  ����  �Ԏ�/�Ԍ^  No.  ����  �Ԏ�/�Ԍ^  No.  ����  �Ԏ�/�Ԍ^"},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 23},
		{ 3,  1,  2, 79,  2},
		{ 1,  1,  4, 79,  4},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	static	char	*func1[] = {
		" �ۑ� ", " �ďo ", "������", " ���� ", " �Ǎ� ",
		" �O�� ", " ���� ", NULL, NULL, " �I�� "
	};
	int	i, loop_sw, no, pno, page, ch;
	char	buf[3];

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_CYAN);

	/* ��ʕ\�� */
	dspscr(scr1, C_WHITE);

	for (i = 7; i <= 18; i++)
		g_line(7*8, i*16, 73*8, i*16, C_WHITE, 0, 0);
	for (i = 7; i <=73; i += 22)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 0);
	for (i = 12; i <=73; i += 22)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);
	for (i = 18; i <=73; i += 22)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);

	/* �t�@���N�V�����L�[�\�� */
	dsp_func(func1);

	dsp_tbl(0);

	no = 0;
	loop_sw = 1;
	while (loop_sw) {
		pno = no % 30;
		page = no / 30;
		dspstr(2, 22, "�h�[���[�R�[�h����͂��ĉ������B", C_WHITE);
		clrtoeol();
		sprintf(buf, "%-2d", dolly_tbl[no]);
		ch = inputs(buf, 14+pno/10*22, 8+pno%10, 2, 0xc2, C_WHITE);
		if ((dolly_tbl[no] = atoi(buf)) > 50)
			beep();
		dsp_data(no);
		switch (ch) {
		case KEY_CR:
			if (pno < 29 && no < 499)
				no++;
			break;
		case KEY_UP:
			if (pno % 10 != 0)
				no--;
			break;
		case KEY_DOWN:
			if (pno % 10 != 9)
				no++;
			break;
		case KEY_LEFT:
			if (pno - 10 >= 0)
				no -= 10;
			break;
		case KEY_RIGHT:
			if (pno + 10 < 30 && no + 10 < 499)
				no += 10;
			break;
		case KEY_F1:	/* �ۑ� */
			f_save();
			break;
		case KEY_F2:	/* �ďo */
			f_load(page);
			break;
		case KEY_F3:	/* ������ */
			f_init(page);
			break;
		case KEY_F4:	/* ���� */
			f_write();
			break;
		case KEY_F5:	/* �Ǎ� */
			f_read(page);
			break;
		case KEY_F6:	/* �O�� */
			if (page > 0)
				page--;
			else
				page = 16;
			no = page * 30;
			dsp_tbl(page);
			break;
		case KEY_F7:	/* ���� */
			if (page < 16)
				page++;
			else
				page = 0;
			no = page * 30;
			dsp_tbl(page);
			break;
		case KEY_F10:
			loop_sw = 0;
			break;
		}
	}
}

static	void	dsp_tbl(int page)
{
	int	i, no;

	for (i = 0; i < 30; i++) {
		no = page * 30 + i;
		if (no < 500)
			dsp_data(no);
		else {
			t_loc(8+i/10*22, 8+i%10);
			clrtoeol();
		}
	}
}

static	void	dsp_data(int no)
{
	int	i, code;

	i = no % 30;
	code = dolly_tbl[no];
	xprintf(8+i/10*22, 8+i%10, C_WHITE, "%3d   %-2d    %-3.3s %-2.2s"
			, no+1
			, code
			, code != 0 ? dolcnv_tbl[code-1] : "   "
			, code != 0 ? dolcnv_tbl[code-1]+3 : "  ");
}

static	void	f_save()
{
	FILE	*fp;

	if (kakunin("�h�[���[�m���e�[�u�����t�@�C���ɕۑ����܂��B��낵���ł����H (Y/N) ")) {
		if ((fp = fopen("DOLLY.TBL", "wb")) != NULL) {
			fwrite(dolly_tbl, 2, 500, fp);
			fclose(fp);
			message("�ۑ����܂����B", C_GREEN);
		}
	}
}

static	void	f_load(int page)
{
	FILE	*fp;

	if (kakunin("�h�[���[�m���e�[�u�����t�@�C������ǂݍ��݂܂��B��낵���ł����H (Y/N) ")) {
		if ((fp = fopen("DOLLY.TBL", "rb")) != NULL) {
			fread(dolly_tbl, 2, 500, fp);
			fclose(fp);
			dsp_tbl(page);
			message("�ǂݍ��݂܂����B", C_GREEN);
		}
	}
}

static	void	f_init(int page)
{
	int	i;

	if (kakunin("�h�[���[�m���e�[�u�������������܂��B��낵���ł����H (Y/N) ")) {
		for (i = 0; i < 500; i++)
			dolly_tbl[i] = 0;
		dsp_tbl(page);
		message("���������܂����B", C_GREEN);
	}
}

static	void	f_write()
{
	if (kakunin("�h�[���[�e�[�u�����o�k�b���W�X�^�ɏ������݂܂��B��낵���ł����H (Y/N) ")) {
		if (wccm(target_id, source_id, dolly_tbl, GR_DLT, sizeof(dolly_tbl)) == 0)
			message("�������݂܂����B", C_GREEN);
		else
			message("�������݂Ɏ��s���܂����B", C_RED);
	}
}

static	void	f_read(int page)
{
	if (kakunin("�h�[���[�e�[�u�����o�k�b���W�X�^����ǂݍ��݂܂��B��낵���ł����H (Y/N) ")) {
		if (rccm(target_id, source_id, dolly_tbl, GR_DLT, sizeof(dolly_tbl)) == 0) {
			dsp_tbl(page);
			message("�ǂݍ��݂܂����B", C_GREEN);
		} else
			message("�ǂݍ��݂Ɏ��s���܂����B", C_RED);
	}
}