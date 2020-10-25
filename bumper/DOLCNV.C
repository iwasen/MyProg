/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: �h�[���[��R�[�h�ϊ��e�[�u���ێ珈��	*
 *		�t�@�C����	: colcnv.c				*
 *									*
 ************************************************************************/

#include "bumper.h"

static	void	dsp_tbl(void);
static	void	f_save(void);
static	void	f_load(void);
static	void	f_init(void);
static	void	f_write(void);
static	void	f_read(void);

/*=======================================================================
 |
 |		�h�[���[��R�[�h�ϊ��e�[�u���ێ��ʏ���
 |
 |	void	dolcnv()
 |
 =======================================================================*/
void	dolcnv()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{22,  1, "�o �� �p �[ �h �F �x �� �V �X �e ��"},
		{23,  3, "�h�[���[��R�[�h�ϊ��e�[�u���ێ�"},
		{ 6,  7, "���� �Ԏ�/�^  ���� �Ԏ�/�^  ���� �Ԏ�/�^  ���� �Ԏ�/�^  ���� �Ԏ�/�^"},
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
		NULL, NULL, NULL, NULL, " �I�� "
	};
	int	i, loop_sw, no, ch;

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_CYAN);

	/* ��ʕ\�� */
	dspscr(scr1, C_WHITE);

	for (i = 7; i <= 18; i++)
		g_line(5*8, i*16, 75*8, i*16, C_WHITE, 0, 0);
	for (i = 5; i <=75; i += 14)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 0);
	for (i = 10; i <=75; i += 14)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);

	/* �t�@���N�V�����L�[�\�� */
	dsp_func(func1);

	dsp_tbl();

	no = 0;
	loop_sw = 1;
	while (loop_sw) {
		dspstr(2, 22, "�Ԏ�^�Ԍ^����͂��ĉ������B", C_WHITE);
		clrtoeol();
		ch = inputs(dolcnv_tbl[no], 12+no/10*14, 8+no%10, 5, 0xc0, C_WHITE);
		switch (ch) {
		case KEY_CR:
			if (no < 49)
				no++;
			break;
		case KEY_UP:
			if (no % 10 != 0)
				no--;
			break;
		case KEY_DOWN:
			if (no % 10 != 9)
				no++;
			break;
		case KEY_LEFT:
			if (no - 10 >= 0)
				no -= 10;
			break;
		case KEY_RIGHT:
			if (no + 10 < 50)
				no += 10;
			break;
		case KEY_F1:	/* �ۑ� */
			f_save();
			break;
		case KEY_F2:	/* �ďo */
			f_load();
			break;
		case KEY_F3:	/* ������ */
			f_init();
			break;
		case KEY_F4:	/* ���� */
			f_write();
			break;
		case KEY_F5:	/* �Ǎ� */
			f_read();
			break;
		case KEY_F10:
			loop_sw = 0;
			break;
		}
	}
}

static	void	dsp_tbl()
{
	int	i;

	for (i = 0; i < 50; i++) {
		xprintf(7+i/10*14, 8+i%10, C_WHITE, "%2d   %-5.5s", i+1, dolcnv_tbl[i]);
	}
}

static	void	f_save()
{
	FILE	*fp;

	if (kakunin("�ްذ��R�[�h�ϊ��e�[�u�����t�@�C���ɕۑ����܂��B��낵���ł����H (Y/N) ")) {
		if ((fp = fopen("DOLCNV.TBL", "wb")) != NULL) {
			fwrite(dolcnv_tbl, 6, 50, fp);
			fclose(fp);
			message("�ۑ����܂����B", C_GREEN);
		}
	}
}

static	void	f_load()
{
	FILE	*fp;

	if (kakunin("�ްذ�����ޕϊ��e�[�u�����t�@�C������ǂݍ��݂܂��B��낵���ł����H (Y/N) ")) {
		if ((fp = fopen("DOLCNV.TBL", "rb")) != NULL) {
			fread(dolcnv_tbl, 6, 50, fp);
			fclose(fp);
			dsp_tbl();
			message("�ǂݍ��݂܂����B", C_GREEN);
		}
	}
}

static	void	f_init()
{
	int	i;

	if (kakunin("�h�[���[��R�[�h�ϊ��e�[�u�������������܂��B��낵���ł����H (Y/N) ")) {
		for (i = 0; i < 50; i++)
			strcpy(dolcnv_tbl[i], "     ");
		dsp_tbl();
		message("���������܂����B", C_GREEN);
	}
}

static	void	f_write()
{
	if (kakunin("�ްذ�����ޕϊ�ð��ق��o�k�b���W�X�^�ɏ������݂܂��B��낵���ł����H (Y/N) ")) {
		if (wccm(target_id, source_id, dolcnv_tbl, GR_DCT, sizeof(dolcnv_tbl)) == 0)
			message("�������݂܂����B", C_GREEN);
		else
			message("�������݂Ɏ��s���܂����B", C_RED);
	}
}

static	void	f_read()
{
	if (kakunin("�ްذ�����ޕϊ�ð��ق��o�k�bڼ޽�����ǂݍ��݂܂��B��낵���ł����H (Y/N) ")) {
		if (rccm(target_id, source_id, dolcnv_tbl, GR_DCT, sizeof(dolcnv_tbl)) == 0) {
			dsp_tbl();
			message("�ǂݍ��݂܂����B", C_GREEN);
		} else
			message("�ǂݍ��݂Ɏ��s���܂����B", C_RED);
	}
}