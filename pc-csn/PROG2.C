/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h�I����ʁ|�P����		*
 *		�t�@�C����	: prog2.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*=======================================================================
 |
 |		�R�}���h�I����ʁ|�P����
 |
 |	void	prog2()
 |
 =======================================================================*/
void	prog2()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^�P */
		{ 2, 21, msg210},
		{ 2, 23, msg220},
		{ 0, 0, 0}
	};
	static	SCRDAT	scr2[] = {	/* ��ʕ\���f�[�^�Q */
		{42,  4, "�P�D"},
		{42,  6, "�Q�D"},
		{42,  8, "�R�D"},
		{42, 10, "�S�D"},
		{42, 12, "�T�D"},
		{42, 14, msg230},
		{42, 16, "�X�D"},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 18, 79, 18},
		{ 0,  1, 20, 79, 20},
		{ 0,  1, 22, 79, 22},
		{ 1,  3,  3, 39, 17},
		{ 1, 41,  3, 77, 17},
		{ 0, 41,  5, 77,  5},
		{ 0, 41,  7, 77,  7},
		{ 0, 41,  9, 77,  9},
		{ 0, 41, 11, 77, 11},
		{ 3, 41, 13, 77, 13},
		{ 0, 41, 15, 77, 15},
		{-1}
	};
	static	struct	{		/* �R�}���h�I���f�[�^ */
		int	flag;		/* �L���^�����t���O */
		int	num;		/* �R�}���h��ޔԍ� */
		int	y;		/* ���ʒu */
	} sel[7] =  {
		{0, '1', 4},
		{0, '2', 6},
		{0, '3', 8},
		{0, '4', 10},
		{0, '5', 12},
		{1, '8', 14},
		{0, '9', 16}
	};
	static	MOUSE	mouse[] = {	/* �}�E�X�f�[�^ */
		{42, 4, 34, '1', KEY_CR, 0},
		{42, 6, 34, '2', KEY_CR, 0},
		{42, 8, 34, '3', KEY_CR, 0},
		{42,10, 34, '4', KEY_CR, 0},
		{42,12, 34, '5', KEY_CR, 0},
		{42,14, 34, '8', KEY_CR, 0},
		{42,16, 34, '9', KEY_CR, 0}
	};
	static	int	cmd = 0;	/* �R�}���h�I��ԍ� */
	int	i;			/* ���[�v�J�E���^ */
	int	ch;			/* �L�[���͕��� */
	int	color;			/* �F�R�[�h */
	CMS_TBL	*ptr;			/* �R�}���h�I���e�[�u���|�C���^ */

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	color = online ? C_ONLINE : C_OFFLINE;
	if (mkcmd)
		xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	else
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg200);
	dspscr(scr1, color);
	dspscr(scr2, C_GUIDE);

	/* �G��ʕ\�� */
	dspgrf(mkcmd ? cfg_grf : ssg_grf);

	/* �R�}���h�I���`�F�b�N */
	for (i = 0; i < 5; i++) {
		if ((ptr = cmstbl[i]) != NULL) {
			dspstr(46, 4 + i * 2, ptr->title, C_GUIDE);
			sel[i].flag = 1;
			mouse[i].len = 34;
		} else {
			sel[i].flag = 0;
			mouse[i].len = 0;
		}
	}
	if (loctbl != NULL) {
		sel[6].flag = 1;
		dspstr(46, 16, loctbl->title, C_GUIDE);
		mouse[6].len = 34;
	} else {
		sel[6].flag = 0;
		mouse[6].len = 0;
	}

	/* ���̓t�B�[���h�\�� */
	dsp_field(38, 23, 1, C_GUIDE);

	/* �I�t���C���F���o�� */
	color = t_color_code(C_ONLINE);

	/* �}�E�X�f�[�^�Z�b�g */
	m_reg(mouse, 7);

	/* �t�@���N�V�����L�[�ݒ� */
	setfunc(0);

	while (prog == 2) {
		/* �R�}���h��ޑI�� */
		t_loc(38, 23);
		t_color(t_color_code(C_GUIDE));
		putch(sel[cmd].num);
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE, color|A_REVERSE);

		/* �R�}���h�����\�� */
		if (cmd >= 0 && cmd <= 4)
			dspstr(2, 19, cmstbl[cmd]->guide, C_GUIDE);
		else if (cmd == 5)
			dspstr(2, 19, "", C_GUIDE);
		else if (cmd == 6)
			dspstr(2, 19, loctbl->guide, C_GUIDE);
		clrtoeol();

		/* �R�}���h�I��ԍ����� */
		t_loc(38, 23);
		ch = getchr();

		/* �I�𒆃R�}���h���]���� */
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE
						, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* �b�q�L�[ */
			if (cmd >= 0 && cmd <= 4) {
				/* �I�����C���R�}���h�̎��s */
				/* �R�}���h�e�[�u���|�C���^�Z�b�g */
				cms_current = cmstbl[cmd];

				/* ���[�J���t���O�n�e�e */
				local = 0;

				/* �h�c�C�R�}���h���Z�b�g */
				cmdbuf[0] = csg.nsg.online_id;
				cmdbuf[1] = '\0';

				/* �R�}���h�I����ʁ|�Q */
				prog = 3;
			} else if (cmd == 5) {
				/* �R�}���h�t�@�C���̎��s */
				if (n_cmf != 0 && !mkcmd) {
					prog = 9;
				}
			} else if (cmd == 6) {
				/* ���[�J���R�}���h�̎��s */
				/* �R�}���h�e�[�u���|�C���^�Z�b�g */
				cms_current = loctbl;

				/* �h�c�C�R�}���h���Z�b�g */
				cmdbuf[0] = csg.nsg.offline_id;
				cmdbuf[1] = '\0';

				/* ���[�J���t���O�n�m */
				local = 1;

				/* �R�}���h�I����ʁ|�Q */
				prog = 3;
			}
			break;
		case KEY_ESC:	/* �d�r�b�L�[ */
			if (!mkcmd) {
				/* ���b�Z�[�W��M���*/
				prog = 7;
			}
			break;
		case KEY_HOME:	/* �g�n�l�d�L�[ */
		case KEY_HELP:	/* �g�d�k�o�L�[ */
			/* �R�}���h���ړ��͉�� */
			prog = 6;
			break;
		case KEY_UP:	/* ����L�[ */
			/* ��̃R�}���h��ނ�I�� */
			do {
				cmd = (cmd + 6) % 7;
			} while (sel[cmd].flag == 0);
			break;
		case KEY_DOWN:	/* �����L�[ */
			/* ���̃R�}���h��ނ�I�� */
			do {
				cmd = (cmd + 1) % 7;
			} while (sel[cmd].flag == 0);
			break;
		default:
			/* �R�}���h��ޔԍ��`�F�b�N */
			for (i = 0; i < 7; i++) {
				if (ch == sel[i].num)
					break;
			}
			if (i < 7 && sel[i].flag != 0)
				cmd = i;
			else
				beep();
			break;
		}
	}

	/* �}�E�X�f�[�^���� */
	m_reg(NULL, 0);
}