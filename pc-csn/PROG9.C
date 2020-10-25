/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h�t�@�C���I����ʏ���		*
 *		�t�@�C����	: prog9.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

static	void	dsp_cmf(int);

/*=======================================================================
 |
 |		�R�}���h�t�@�C���I����ʏ���
 |
 |	void	prog9()
 |
 =======================================================================*/
void	prog9()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^ */
		{ 2, 23, msg910},
		{45, 23, msg920},
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
	int	sn = 0, cn = 0;
	int	ch, color, y, i;
	CMF_TBL	*ptr;
	MOUSE	mouse[13];

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	if (online) {
		color = C_ONLINE;
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg900);
	} else {
		color = C_OFFLINE;
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg900);
	}
	dspscr(scr1, color);

	/* ���݂܂łɑI�����ꂽ�R�}���h�̕\�� */
	cmdbuf[0] = '\0';
	dspstr(4, 5, "�R�}���h�t�@�C���̎��s", C_GUIDE);

	/* �R�}���h�\�� */
	dsp_cmf(sn);

	/* �}�E�X�f�[�^�Z�b�g */
	for (i = 0; i < n_cmf && i < 13; i++) {
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

	while (prog == 9) {
		y = cn - sn + 9;	/* �I�𒆍��ڍs�ʒu */

		/* �R�}���h�e�[�u���|�C���^�Z�b�g */
		ptr = &cmf_tbl[cn];

		/* ���ݑI�𒆂̃R�}���h�\�� */
		dspstr(4, 7, ptr->name, C_GUIDE);
		clrtoeol();
		dspstr(21, 7, ptr->guide, C_GUIDE);

		/* �I�𒆃R�}���h���] */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(color)|A_REVERSE);

		ch = getchr();	/* �L�[���� */

		/* �I�𒆃R�}���h���]���� */
		attrib(2, y, 16, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));
		attrib(20, y, 58, A_WHITE|A_REVERSE, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_CR:	/* �b�q�L�[ */
			/* �L�[�o�b�t�@�ɃZ�b�g */
			set_keybuf(ptr->name);

			/* �R�}���h���ړ��͉�� */
			prog = 6;
			break;
		case KEY_ESC:	/* �d�r�b�L�[ */
			/* ���b�Z�[�W��M��� */
			prog = 7;
			break;
		case KEY_SFT_ESC:	/* �V�t�g�{�d�r�b�L�[ */
			/* �R�}���h�I����ʁ|�P */
			prog = 2;
			break;
		case KEY_HOME:	/* �g�n�l�d�L�[ */
		case KEY_HELP:	/* �g�d�k�o�L�[ */
			/* �R�}���h���ړ��͉�� */
			prog = 6;
			break;
		case KEY_UP:	/* ����L�[ */
			/* ��̃R�}���h��I�� */
			if (cn > 0) {
				if (sn == cn) {
					sn--;
					dsp_cmf(sn);
				}
				cn--;
			}
			break;
		case KEY_DOWN:	/* �����L�[ */
			/* ���̃R�}���h��I�� */
			if (cn + 1 < n_cmf) {
				if (cn == sn + 12) {
					sn++;
					dsp_cmf(sn);
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
 |		�R�}���h�t�@�C���\������
 |
 |	void	dsp_cmf(n)
 |
 |		int	n;		�擪�̃R�}���h�m���D
 |
 =======================================================================*/
static	void	dsp_cmf(int n)
{
	int	i, y;
	CMF_TBL	*ptr;

	for (i = 0, ptr = &cmf_tbl[n]; i < 13; i++, ptr++) {
		y = i + 9;
		if (n++ < n_cmf) {
			/* �R�}���h���\�� */
			dspstr(4, y, ptr->name, C_GUIDE);
			clrtoeol();

			/* �����\�� */
			dspstr(21, y, ptr->guide, C_GUIDE);
		}
	}
}