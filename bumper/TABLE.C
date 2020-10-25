/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: �e��e�[�u���ێ�			*
 *		�t�@�C����	: table.c				*
 *		�쐬��		: s.aizawa				*
 *		�쐬��		: ??/??/??				*
 *									*
 ************************************************************************/

#include "bumper.h"

/*=======================================================================
 |
 |		�e��e�[�u���ێ�
 |
 |	void	table()
 |
 =======================================================================*/
void	table()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{22,  1, "�o �� �p �[ �h �F �x �� �V �X �e ��"},
		{31,  3, "�e��e�[�u���ێ�"},
		{20,  8, "�P�D�o���p�[�e�[�u���ێ�"},
		{20, 11, "�Q�D�h�[���[�e�[�u���ێ�"},
		{20, 14, "�R�D�J���[�R�[�h�ϊ��e�[�u���ێ�"},
		{20, 17, "�S�D�h�[���[��R�[�h�ϊ��e�[�u���ێ�"},
		{ 4, 22, "������I�����ĉ�����"},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 23},
		{ 3,  1,  2, 79,  2},
		{ 0,  1,  4, 79,  4},
		{ 0,  1, 21, 79, 21},
		{ 1, 18,  7, 62,  9},
		{ 1, 18, 10, 62, 12},
		{ 1, 18, 13, 62, 15},
		{ 1, 18, 16, 62, 18},
		{-1}
	};
	static	char	*func1[] = {
		NULL, NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, NULL, " �I�� "
	};
	int	loop_sw, ch, sel = 1;

menu:
	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_CYAN);

	/* ��ʕ\�� */
	dspscr(scr1, C_WHITE);

	/* ���̓t�B�[���h�\�� */
	dsp_field(30, 22, 1, C_CYAN);

	/* �t�@���N�V�����L�[�\�� */
	dsp_func(func1);

	loop_sw = 1;
	while (loop_sw) {
		/* �R�}���h��ޑI�� */
		t_loc(30, 22);
		t_color(t_color_code(C_WHITE));
		putch(sel + '0');

		attrib(20, 5+sel*3, 40, A_WHITE|A_REVERSE, A_YELLOW|A_REVERSE);

		/* �R�}���h�I��ԍ����� */
		t_loc(38, 23);
		ch = getchr();

		/* �I�𒆃R�}���h���]���� */
		attrib(20, 5+sel*3, 40, A_WHITE|A_REVERSE, A_WHITE);

		switch (ch) {
		case KEY_CR:	/* �b�q�L�[ */
			switch (sel) {
			case 1:		/* �o���p�[�e�[�u���ێ� */
				bumper();
				break;
			case 2:		/* �h�[���[�e�[�u���ێ� */
				dolly();
				break;
			case 3:		/* �J���[�R�[�h�ϊ��e�[�u���ێ� */
				colcnv();
				break;
			case 4:		/* �h�[���[��R�[�h�ϊ��e�[�u���ێ� */
				dolcnv();
				break;
			}
			goto menu;
			break;
		case KEY_ESC:	/* �d�r�b�L�[ */
			break;
		case KEY_UP:	/* ����L�[ */
			if (sel > 1)
				sel--;
			else
				sel = 4;
			break;
		case KEY_DOWN:	/* �����L�[ */
			if (sel < 4)
				sel++;
			else
				sel = 1;
			break;
		case KEY_F10:
			loop_sw = 0;
			break;
		default:
			if (ch >= '1' && ch <= '4')
				sel = ch - '0';
			break;
		}
	}
}