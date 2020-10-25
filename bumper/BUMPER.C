/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: �o���p�[�e�[�u���ێ�			*
 *		�t�@�C����	: bumper.c				*
 *		�쐬��		: s.aizawa				*
 *		�쐬��		: ??/??/??				*
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
 |		�o���p�[�e�[�u���ێ�
 |
 |	void	bumper()
 |
 =======================================================================*/
void	bumper()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{22,  1, "�o �� �p �[ �h �F �x �� �V �X �e ��"},
		{29,  3, "�o���p�[�e�[�u���ێ�"},
		{11,  7, "����߰����  �ްذ����  �Ԏ�/�Ԍ^  �װ����  �װ��  �K���݌�"},
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
	int	i, loop_sw, no, pno, page, ch, field;
	char	buf[5];

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_CYAN);

	/* ��ʕ\�� */
	dspscr(scr1, C_WHITE);

	for (i = 7; i <= 18; i++)
		g_line(10*8, i*16, 70*8, i*16, C_WHITE, 0, 0);
	g_line(10*8, 7*16, 10*8, 18*16, C_WHITE, 0, 0);
	g_line(22*8, 7*16, 22*8, 18*16, C_WHITE, 0, 0);
	g_line(33*8, 7*16, 33*8, 18*16, C_WHITE, 0, 1, 0xcccc);
	g_line(44*8, 7*16, 44*8, 18*16, C_WHITE, 0, 0);
	g_line(53*8, 7*16, 53*8, 18*16, C_WHITE, 0, 1, 0xcccc);
	g_line(60*8, 7*16, 60*8, 18*16, C_WHITE, 0, 0);
	g_line(70*8, 7*16, 70*8, 18*16, C_WHITE, 0, 0);

	/* �t�@���N�V�����L�[�\�� */
	dsp_func(func1);

	dsp_tbl(0);

	no = 0;
	field = 0;
	loop_sw = 1;
	while (loop_sw) {
		pno = no % 10;
		page = no / 10;
		switch (field) {
		case 0:
			dspstr(2, 22, "�h�[���[�R�[�h����͂��ĉ������B", C_WHITE);
			clrtoeol();
			sprintf(buf, "%-2d", bumper_tbl.dolly_code[no]);
			ch = inputs(buf, 26, 8+pno, 2, 0xc2, C_WHITE);
			if ((bumper_tbl.dolly_code[no] = atoi(buf)) > 50)
				beep();
			break;
		case 1:
			dspstr(2, 22, "�J���[�R�[�h����͂��ĉ������B", C_WHITE);
			clrtoeol();
			sprintf(buf, "%-2d", bumper_tbl.color_code[no]);
			ch = inputs(buf, 47, 8+pno, 2, 0xc2, C_WHITE);
			if ((bumper_tbl.color_code[no] = atoi(buf)) > 30)
				beep();
			break;
		case 2:
			dspstr(2, 22, "�K���݌ɗʂ���͂��ĉ������B", C_WHITE);
			clrtoeol();
			sprintf(buf, "%-4d", bumper_tbl.tekisei_zaiko[no]);
			ch = inputs(buf, 63, 8+pno, 4, 0xc2, C_WHITE);
			bumper_tbl.tekisei_zaiko[no] = atoi(buf);
			break;
		}
		dsp_data(no);
		switch (ch) {
		case KEY_CR:
			if (field == 2) {
				field = 0;
				if (pno < 9)
					no++;
			} else
				field++;
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
			if (field != 0)
				field--;
			break;
		case KEY_RIGHT:
			if (field != 2)
				field++;
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
				page = 49;
			no = page * 10;
			dsp_tbl(page);
			break;
		case KEY_F7:	/* ���� */
			if (page < 49)
				page++;
			else
				page = 0;
			no = page * 10;
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

	for (i = 0; i < 10; i++) {
		no = page * 10 + i;
		dsp_data(no);
	}
}

static	void	dsp_data(int no)
{
	int	i, dolly_code, color_code;

	i = no % 10;
	dolly_code = bumper_tbl.dolly_code[no];
	color_code = bumper_tbl.color_code[no];
	xprintf(14, 8+i, C_WHITE, "%3d         %-2d       %-3.3s %-2.2s      %-2d      %-3.3s     %-4d"
			, no+1
			, dolly_code
			, dolly_code != 0 ? dolcnv_tbl[dolly_code-1] : "   "
			, dolly_code != 0 ? dolcnv_tbl[dolly_code-1]+3 : "  "
			, color_code
			, color_code != 0 ? colcnv_tbl[color_code-1] : "   "
			, bumper_tbl.tekisei_zaiko[no]);
}

static	void	f_save()
{
	FILE	*fp;

	if (kakunin("�o���p�[�e�[�u�����t�@�C���ɕۑ����܂��B��낵���ł����H (Y/N) ")) {
		if ((fp = fopen("BUMPER.TBL", "wb")) != NULL) {
			fwrite(&bumper_tbl, sizeof(bumper_tbl), 1, fp);
			fclose(fp);
			message("�ۑ����܂����B", C_GREEN);
		}
	}
}

static	void	f_load(int page)
{
	FILE	*fp;

	if (kakunin("�o���p�[�e�[�u�����t�@�C������ǂݍ��݂܂��B��낵���ł����H (Y/N) ")) {
		if ((fp = fopen("BUMPER.TBL", "rb")) != NULL) {
			fread(&bumper_tbl, sizeof(bumper_tbl), 1, fp);
			fclose(fp);
			dsp_tbl(page);
			message("�ǂݍ��݂܂����B", C_GREEN);
		}
	}
}

static	void	f_init(int page)
{
	if (kakunin("�o���p�[�e�[�u�������������܂��B��낵���ł����H (Y/N) ")) {
		memset(&bumper_tbl, 0, sizeof(bumper_tbl));
		dsp_tbl(page);
		message("���������܂����B", C_GREEN);
	}
}

static	void	f_write()
{
	if (kakunin("�o���p�[�e�[�u�����o�k�b���W�X�^�ɏ������݂܂��B��낵���ł����H (Y/N) ")) {
		if (wccm(target_id, source_id, &bumper_tbl, GR_BPT, sizeof(bumper_tbl)) == 0)
			message("�������݂܂����B", C_GREEN);
		else
			message("�������݂Ɏ��s���܂����B", C_RED);
	}
}

static	void	f_read(int page)
{
	if (kakunin("�o���p�[�e�[�u�����o�k�b���W�X�^����ǂݍ��݂܂��B��낵���ł����H (Y/N) ")) {
		if (rccm(target_id, source_id, &bumper_tbl, GR_BPT, sizeof(bumper_tbl)) == 0) {
			dsp_tbl(page);
			message("�ǂݍ��݂܂����B", C_GREEN);
		} else
			message("�ǂݍ��݂Ɏ��s���܂����B", C_RED);
	}
}