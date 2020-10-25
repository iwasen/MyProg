/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: �g���b�L���O���j�^����		*
 *		�t�@�C����	: tacking.c				*
 *									*
 ************************************************************************/

#include "bumper.h"

static	void	dsp_tbl(int);
static	void	dsp_data(int);

/*=======================================================================
 |
 |		�g���b�L���O���j�^����
 |
 |	void	tracking()
 |
 =======================================================================*/
void	tracking()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{22,  1, "�o �� �p �[ �h �F �x �� �V �X �e ��"},
		{29,  3, "�g���b�L���O���j�^"},
		{ 6,  7, "����  �ްذNo. �װ���� ����  �ްذNo. �װ���� ����  �ްذNo. �װ����"},
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
		NULL, NULL, NULL, NULL, NULL,
		" �O�� ", " ���� ", NULL, NULL, " �I�� "
	};
	int	i, loop_sw, no, pno, page;

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_CYAN);

	/* ��ʕ\�� */
	dspscr(scr1, C_WHITE);

	for (i = 7; i <= 18; i++)
		g_line(5*8, i*16, 74*8, i*16, C_WHITE, 0, 0);
	for (i = 5; i <=74; i += 23)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 0);
	for (i = 11; i <=74; i += 23)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);
	for (i = 20; i <=74; i += 23)
		g_line(i*8, 7*16, i*8, 18*16, C_WHITE, 0, 1, 0xcccc);

	/* �t�@���N�V�����L�[�\�� */
	dsp_func(func1);

	no = 0;
	tm_ovf = 1;

	loop_sw = 1;
	while (loop_sw) {
		pno = no % 30;
		page = no / 30;

		if (tm_ovf) {		
			if (rccm(target_id, source_id, &schedule_tbl, GR_TRT, sizeof(schedule_tbl)) == 0)
				dspstr(2, 22, "���s��", C_GREEN);
			else
				dspstr(2, 22, "�ǂݍ��݃G���[", C_RED);
			clrtoeol();
			dsp_tbl(page);
			timset(500);
		}

		switch (inkey()) {
		case KEY_F6:	/* �O�� */
			if (page > 0)
				page--;
			else
				page = 3;
			no = page * 30;
			dsp_tbl(page);
			break;
		case KEY_F7:	/* ���� */
			if (page < 3)
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
		if (no < 100)
			dsp_data(no);
		else {
			t_loc(6+i/10*22, 8+i%10);
			clrtoeol();
		}
	}
}

static	void	dsp_data(int no)
{
	int	i;

	i = no % 30;
	xprintf(6+i/10*23, 8+i%10, C_WHITE, "%3d     %3d      %2d"
			, no+1
			, schedule_tbl.dolly_no[no]
			, schedule_tbl.color_code[no]);
}