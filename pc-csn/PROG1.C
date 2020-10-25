/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h�R�l�N�V�����J�݉�ʏ���	*
 *		�t�@�C����	: prog1.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	�����֐�	*/
static	int	sel_command(int);
static	int	inp_conxxxtion(int);
static	int	inp_password(char *);

/*=======================================================================
 |
 |		�R�}���h�R�l�N�V�����J�݉�ʏ���
 |
 |	void	prog1()
 |
 =======================================================================*/
void	prog1()
{
	static	SCRDAT	scr1[] = {	/* ��ʕ\���f�[�^�P */
		{ 2, 19, msg110},
		{ 2, 21, msg120},
		{ 2, 23, msg130},
		{ 0, 0, 0}
	};
	static	SCRDAT	scr2[] = {	/* ��ʕ\���f�[�^�Q */
		{42,  4, msg140},
		{42,  8, msg150},
		{42, 16, msg160},
		{ 0, 0, 0}
	};
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 18, 79, 18},
		{ 0,  1, 20, 79, 20},
		{ 0,  1, 22, 79, 22},
		{ 1,  3,  3, 39, 17},
		{ 1, 41,  3, 77,  5},
		{ 1, 41,  7, 77,  9},
		{ 1, 41, 15, 77, 17},
		{-1}
	};
	static	int	cmd = 0;	/* �R�}���h��ޔԍ� */

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	xprintf(2, 1, C_OFFLINE, msg10, csg.nsg.sys_name, msg100);
	dspscr(scr1, C_OFFLINE);
	dspscr(scr2, C_GUIDE);
	dspgrf(csg_grf);

	/* ���̓t�B�[���h�\�� */
	dsp_field(38, 19,  1, C_GUIDE);
	dsp_field(38, 21,  2, C_GUIDE);
	dsp_field(38, 23, 10, C_GUIDE);

	online = 0;		/* �I�����C���t���O�N���A */
	while (prog == 1) {
		cmd = sel_command(cmd);	/* �R�}���h��ޑI�� */
		switch (cmd) {
		case 0:		/* �u�r�d�V�X�e���̕ێ� */
		case 1:		/* ���u�u�r�d�V�X�e���̕ێ� */
			/* �R�l�N�V�����ԍ����� */
			if (inp_conxxxtion(cmd) == -1)
				break;

			/* �p�X���[�h���� */
			if (inp_password(password) == -1)
				break;

			/* �R�l�N�V�������� */
			dsp_loc_msg(LOC_MSG_CONxxxT, ssg.nsg.conxxxtion);
			if (conxxxt(1) == 0) {
				find_cmf();
				prog = 2;
			}
			break;
		case 2:		/* ���[�J���R�}���h�̑I�� */
			/* �R�}���h�e�[�u���|�C���^�Z�b�g */
			cms_current = loctbl;

			/* �h�c�C�R�}���h���Z�b�g */
			cmdbuf[0] = csg.nsg.offline_id;
			cmdbuf[1] = '\0';

			/* ���[�J���t���O�n�m */
			local = 1;

			/* �R�}���h�I����ʁ|�Q */
			prog = 3;
			break;
		}
	}
}

/*=======================================================================
 |
 |		�R�}���h�I������
 |
 |	int	sel_command(cmd)
 |
 |		int	cmd;	�ŏ��ɑI������Ă���R�}���h�ԍ�
 |
 |		�Ԓl		�R�}���h�I��ԍ�
 |
 =======================================================================*/
static	int	sel_command(int cmd)
{
	static	struct	{
		int	num;	/* �R�}���h�ԍ� */
		int	y;	/* �s�ʒu */
	} sel[3] =  {
		{'1', 4},
		{'2', 8},
		{'9', 16}
	};
	static	MOUSE	mouse[] = {	/* �}�E�X�f�[�^ */
		{42, 4, 34, '1', KEY_CR, 0},
		{42, 8, 34, '2', KEY_CR, 0},
		{42,16, 34, '9', KEY_CR, 0}
	};
	int	ch;		/* �L�[���͕��� */
	int	i;		/* ���[�v�J�E���^ */
	int	color;		/* �F�R�[�h */

	/* �����F�Z�b�g */
	t_color(t_color_code(C_GUIDE));

	/* �I�t���C���F���o�� */
	color = t_color_code(C_OFFLINE);

	/* �}�E�X�f�[�^�Z�b�g */
	m_reg(mouse, 3);

	/* �t�@���N�V�����L�[�ݒ� */
	setfunc(0);

	for (;;) {
		/* �R�}���h�ԍ��\�� */
		t_loc(38, 19);
		putch(sel[cmd].num);

		/* �I�𒆃R�}���h���] */
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE, color|A_REVERSE);

		/* �R�}���h�ԍ����� */
		t_loc(38, 19);
		t_csron();
		ch = getchr();
		t_csroff();

		/* �b�q�L�[�������烊�^�[�� */
		if (ch == KEY_CR)
			break;

		/* �I�𒆃R�}���h���]���� */
		attrib(42, sel[cmd].y, 34, A_WHITE|A_REVERSE
						, t_color_code(C_GUIDE));

		switch (ch) {
		case KEY_UP:	/* ����L�[ */
			cmd = (cmd + 2) % 3;
			break;
		case KEY_DOWN:	/* �����L�[ */
			cmd = (cmd + 1) % 3;
			break;
		default:
			/* ���͕����`�F�b�N */
			for (i = 0; i < 3; i++) {
				if (ch == sel[i].num)
					break;
			}
			if (i < 3)
				cmd = i;
			else
				beep();
			break;
		}
	}

	/* �}�E�X�f�[�^���� */
	m_reg(NULL, 0);

	return(cmd);
}

/*=======================================================================
 |
 |		�R�l�N�V�����ԍ����͏���
 |
 |	int	inp_conxxxtion(cmd)
 |
 |		int	cmd;	�R�}���h�ԍ�
 |
 |		�Ԓl		�R�l�N�V�����ԍ�
 |				�i�d�r�b�L�[�������ꂽ�ꍇ�A�|�P�j
 |
 =======================================================================*/
static	int	inp_conxxxtion(int cmd)
{
	char	buf[3];		/* �R�l�N�V�����ԍ����̓o�b�t�@ */
	int	loop_sw;	/* ���[�v����X�C�b�` */
	int	ch;		/* �L�[���͕��� */
	int	con;		/* �R�l�N�V�����ԍ� */

	/* �t�@���N�V�����L�[�ݒ� */
	setfunc(0);

	loop_sw = 1;
	while (loop_sw) {
		/* �R�l�N�V�����ԍ����� */
		ch = inputs(buf, 38, 21, 2, 0x312, C_GUIDE);
		switch (ch) {
		case KEY_CR:	/* �b�q�L�[ */
			/* �ԍ��`�F�b�N */
			if (strcmp(buf, "  ") != 0) {
			    con = atoi(buf);
			    if ((cmd == 0 && (con >= 0 && con <= 1))
				|| (cmd == 1 && (con >= 2 && con <= 63))) {
				/* �V�X�e���r�f�t�@�C������ */
				if (find_ssg(con) == 0) {
					/* �V�X�e���r�f�t�@�C���ǂݍ��� */
					read_ssg();
					loop_sw = 0;
					break;
				}
			    }
			}

			/* �G���[���b�Z�[�W�\�� */
			beep();
			dspstr(62, 21, err10, C_ERR);
			attrib(62, 21, 14, A_REVERSE, A_REVERSE);
			break;
		case KEY_ESC:	/* �d�r�b�L�[ */
			con = -1;
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	/* �G���[���b�Z�[�W���� */
	dspspc(62, 21, 14);

	return(con);
}

/*=======================================================================
 |
 |		�p�X���[�h���͏���
 |
 |	int	inp_password(pwd)
 |
 |		char	*pwd;	�p�X���[�h
 |
 |		�Ԓl		�O�F���^�[���L�[�@�|�P�F�d�r�b�L�[	
 |
 =======================================================================*/
static	int	inp_password(char *pwd)
{
	int	loop_sw;	/* ���[�v����X�C�b�` */
	int	ch;		/* �L�[���͕��� */
	int	st;		/* ���^�[���X�e�[�^�X */

	loop_sw = 1;
	while (loop_sw) {
		/* �p�X���[�h���� */
		ch = inputs(pwd, 38, 23, 10, 0x314, C_GUIDE);
		switch (ch) {
		case KEY_CR:	/* �b�q�L�[ */
			st = 0;
			loop_sw = 0;
			break;
		case KEY_ESC:	/* �d�r�b�L�[ */
			st = -1;
			loop_sw = 0;
			break;
		}
	}
	return(st);
}