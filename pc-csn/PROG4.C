/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h���͉�ʁ|�P����		*
 *		�t�@�C����	: prog4.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

static	void	dspprm(int);

/*=======================================================================
 |
 |		�R�}���h���͉�ʁ|�P����
 |
 |	void	prog4()
 |
 =======================================================================*/
void	prog4()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{ 2, 23, msg410},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1,  6, 79,  6},
		{ 0,  1,  8, 79,  8},
		{ 0,  1, 22, 79, 22},
		{ 0, 19,  8, 19, 22},
		{-1}
	};
	int	sn = 0;		/* �擪�p�����[�^�ԍ� */
	int	cn = 0;		/* �J�����g�p�����[�^�ԍ� */
	int	n;		/* �p�����[�^��ސ� */
	int	ch;		/* �L�[���͕��� */
	int	color;		/* �F�R�[�h */
	int	y;		/* �I�����ڍs�ʒu */
	int	i;		/* ���[�v�J�E���^ */
	PRM_TBL	*ptr;		/* �p�����[�^��ރe�[�u���|�C���^ */
	MOUSE	mouse[13];	/* �}�E�X�f�[�^ */


	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	color = online ? C_ONLINE : C_OFFLINE;
	if (mkcmd)
		xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	else if (local)
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg401);
	else
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg400);
	dspscr(scr1, color);

	/* ���݂܂łɑI�����ꂽ�R�}���h�̕\�� */
	cmdbuf[4] = '\0';
	dspstr(4, 3, cmdbuf, C_GUIDE);
	dspstr(4, 5, cmd_current->guide, C_GUIDE);

	/* �p�����[�^��ސ����o�� */
	n = cmd_current->n;

	/* �p�����[�^�\�� */
	dspprm(sn);

	/* �}�E�X�f�[�^�Z�b�g */
	for (i = 0; i < n && i < 13; i++) {
		mouse[i].x = 2;
		mouse[i].y = i + 9;
		mouse[i].len = 76;
		mouse[i].s_code = KEY_MOUSE + i;
		mouse[i].l_code = KEY_CR;
		mouse[i].r_code = 0;
	}
	m_reg(mouse, i);

	/* �t�@���N�V�����L�[�ݒ� */
	setfunc(0);

	while (prog == 4) {
		y = cn - sn + 9;	/* �I�𒆍��ڍs�ʒu */

		/* �p�����[�^��ރe�[�u���|�C���^�Z�b�g */
		ptr = &cmd_current->ptr[cn];

		/* ���ݑI�𒆂̃R�}���h�\�� */
		dspstr(8, 7, ptr->name, C_GUIDE);
		dspstr(21, 7, ptr->guide, C_GUIDE);
		clrtoeol();

		/* �I�𒆃p�����[�^��ޔ��] */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);

		ch = getchr();	/* �L�[���� */

		/* �I�𒆃p�����[�^��ޔ��]���� */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* �b�q�L�[ */
			/* �p�����[�^��ޖ����o�b�t�@�ɃZ�b�g */
			cmdbuf[4] = ' ';
			strcpy(&cmdbuf[5], ptr->name);

			if (ptr->n != 0) {
				/* �p�����[�^�L�� */
				/* �p�����[�^��ރe�[�u���|�C���^�Z�b�g */
				prm_current = ptr;
				prog = 5;
			} else {
				/* �p�����[�^���� */
				/* �R�}���h�I���}�[�N�Z�b�g */
				strcat(cmdbuf, "/");

				/* �L�[�o�b�t�@�ɃZ�b�g */
				set_keybuf(cmdbuf);

				/* �R�}���h���ړ��͉�� */
				prog = 6;
			}
			break;
		case KEY_ESC:	/* �d�r�b�L�[ */
			/* ���b�Z�[�W��M��� */
			prog = 7;
			break;
		case KEY_SFT_ESC:	/* �V�t�g�{�d�r�b�L�[ */
			/* �R�}���h�I����ʁ|�Q */
			prog = 3;
			break;
		case KEY_HOME:	/* �g�n�l�d�L�[ */
		case KEY_HELP:	/* �g�d�k�o�L�[ */
			/* �R�}���h���ړ��͉�� */
			prog = 6;
			break;
		case KEY_UP:	/* ����L�[ */
			/* ��̃p�����[�^��ނ�I�� */
			if (cn > 0) {
				if (sn == cn) {
					sn--;
					dspprm(sn);
				}
				cn--;
			}
			break;
		case KEY_DOWN:	/* �����L�[ */
			/* ���̃p�����[�^��ނ�I�� */
			if (cn + 1 < n) {
				if (cn == sn + 12) {
					sn++;
					dspprm(sn);
				}
				cn++;
			}
			break;
		default:
			if ((ch & 0xff00) == KEY_MOUSE)
				cn = sn + (ch & 0xff);
			else
				beep();
			break;
		}
	}

	/* �}�E�X�f�[�^���� */
	m_reg(NULL, 0);
}

/*=======================================================================
 |
 |		�p�����[�^��ޕ\������
 |
 |	void	dspprm(n)
 |
 |		int	n;		�擪�̃p�����[�^��ނm���D
 |
 =======================================================================*/
static	void	dspprm(int n)
{
	int	i;		/* ���[�v�J�E���^ */
	int	y;		/* �\���s�ʒu */
	PRM_TBL	*ptr;		/* �p�����[�^��ރe�[�u���|�C���^ */

	for (i = 0, ptr = &cmd_current->ptr[n]; i < 13; i++, ptr++) {
		y = i + 9;
		if (n++ < cmd_current->n) {
			/* �p�����[�^���\�� */
			dspstr(8, y, ptr->name, C_GUIDE);

			/* �����\�� */
			dspstr(21, y, ptr->guide, C_GUIDE);
			clrtoeol();
		}
	}
}