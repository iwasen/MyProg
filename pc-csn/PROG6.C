/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h���ړ��͉�ʏ���		*
 *		�t�@�C����	: prog6.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	�����֐�	*/
static	void	prog6a(void);
static	void	prog6b(void);
int	exec_cmd(char *);
static	void	dsp_comment(char *, int);
static	char	*find_comment(CMS_TBL *, char *, char *);

/*=======================================================================
 |
 |		�R�}���h�쐬���`�F�b�N
 |
 |	void	prog6()
 |
 =======================================================================*/
void	prog6()
{
	if (!mkcmd)
		prog6a();	/* �R�}���h���ړ��͉�ʏ��� */
	else
		prog6b();	/* �R�}���h�t�@�C���o�^��ʏ��� */
}

/*=======================================================================
 |
 |		�R�}���h���ړ��͉�ʏ���
 |
 |	void	prog6a()
 |
 =======================================================================*/
void	prog6a()
{
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	int	ch;		/* �L�[���͕��� */
	int	color;		/* �F�R�[�h */
	char	buf[76*2+1];	/* �R�}���h���̓o�b�t�@ */
	char	id[2];		/* �R�}���h�h�c */

	if (old_prog != 7) {
		/* ��ʏ��� */
		t_cls();
		g_cls();
	}

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	if (online) {
		color = C_ONLINE;
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg600);
	} else {
		color = C_OFFLINE;
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg601);
	}
	dspstr(2, 22, msg610, color);
	dspstr(28, 22, msg620, C_GUIDE);

	dsp_msgenv();		/* ���b�Z�[�W�o�͊��\�� */

	restore_scr();		/* ��M��ʃ��X�g�A */

	out_msg();		/* ���b�Z�[�W�o�� */

	/* ���s */
	csr_y = next_line(csr_y);
	if (csr_y == 17) {
		next_line(csr_y);
		csr_y--;
	}

	setfunc(FUNC4|FUNC5);	/* �t�@���N�V�����L�[�Z�b�g */
	
	memset(buf, ' ', 76);	/* ���̓o�b�t�@�N���A */

	while (prog == 6) {
		/* �R�}���h���� */
		ch = inputm(buf, 2, csr_y + 3, 76 * 2, 76, C_GUIDE);
		switch (ch) {
		case KEY_CR:
			prog = 7;
			buf[strcount(buf)] = '\0';
			exec_cmd(buf);		/* �R�}���h���s */
			break;
		case KEY_ESC:
			prog = 7;
			break;
		case KEY_SFT_ESC:
			prog = old_prog;
			break;
		case KEY_HOME:
		case KEY_HELP:
			if (online)
				prog = 2;
			else {
				cms_current = loctbl;
				cmdbuf[0] = csg.nsg.offline_id;
				cmdbuf[1] = '\0';
				prog = 3;
			}
			break;
		case KEY_F4:
			id[0] = csg.nsg.offline_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		case KEY_F5:
			id[0] = csg.nsg.online_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		default:
			beep();
			break;
		}
	}

	dspspc(2, csr_y + 3, 76);
	dspspc(2, csr_y + 4, 76);

	save_scr();		/* ��M��ʃZ�[�u */
}

/*=======================================================================
 |
 |		�R�}���h���s����
 |
 |	int	exec_cmd(buf)
 |
 |		char	*buf;		�R�}���h���̓o�b�t�@
 |
 |		�Ԓl			�O�F����I���@�P�F�ُ�I��
 |
 =======================================================================*/
int	exec_cmd(char *buf)
{
	int	st;		/* �R�}���h���s���� */

	if (*buf == csg.nsg.online_id) {
		/* �I�����C���R�}���h */
		enq_msg(MSG_CRM, buf);		/* �R�}���h�\�� */
		dsp_comment(buf, 0);		/* �R�����g�\�� */
		if (online) 
			send_sd(buf+1);		/* �R�}���h���M */
		st = 0;
	} else if (*buf == csg.nsg.offline_id) {
		/* ���[�J���R�}���h */
		enq_msg(MSG_CNS, buf);		/* �R�}���h�\�� */
		dsp_comment(buf, 1);		/* �R�����g�\�� */
		st = exec_local(buf);		/* ���[�J���R�}���h���s */
	} else if (strncmp(buf, "/*", 2) == 0) {
		/* �R�����g */
		enq_msg(MSG_CNS, buf);		/* �R�����g�\�� */
		st = 0;
	} else if (buf[0] >= 'A' && buf[0] <= 'Z'
					|| buf[0] >= 'a' && buf[0] <= 'z') {
		/* �R�}���h�t�@�C�� */
		enq_msg(MSG_CNS, buf);		/* �R�}���h�t�@�C�����\�� */
		st = start_cmf(buf);		/* �R�}���h�t�@�C�����s */
	} else {
		/* �󔒍s */
		st = 0;
	}

	return(st);
}

/*=======================================================================
 |
 |		�R�����g�\������
 |
 |	void	dsp_comment(bufp, flag)
 |
 |		char	*bufp;		�R�}���h�o�b�t�@
 |		int	flag;		�O�F�I�����C���@�P�F���[�J��
 |
 =======================================================================*/
static	void	dsp_comment(char *bufp, int flag)
{
	char	cmd_name[4];	/* �R�}���h�� */
	char	prm_name[4];	/* �p�����[�^�� */
	char	comment[80];	/* �R�����g�ҏW�o�b�t�@ */
	int	i;		/* ���[�v�J�E���^ */
	char	*cp;

	if (csg.opt.comment == 0)
		return;

	/* �R�}���h�����o�� */
	for (i = 0, bufp++; i < 3 && *bufp != '\0'; i++, bufp++)
		cmd_name[i] = *bufp;
	cmd_name[i] = '\0';

	/* �p�����[�^�����o�� */
	while (*bufp == ' ')
		bufp++;
	for (i = 0; i < 3 && *bufp != '\0'; i++, bufp++)
		prm_name[i] = *bufp;
	prm_name[i] = '\0';

	if (flag == 0) {
		/* �I�����C���R�}���h���� */
		for (i = 0; i < 5; i++) {
			cp = find_comment(cmstbl[i], cmd_name, prm_name);
			if (cp != NULL)
				break;
		}
	} else {
		/* ���[�J���R�}���h���� */
		cp = find_comment(loctbl, cmd_name, prm_name);
	}

	if (cp != NULL) {
		/* �R�����g�\�� */
		sprintf(comment, "/* %s */", cp);
		enq_msg(flag ? MSG_CNS : MSG_CRM, comment);
	}
}

/*=======================================================================
 |
 |		�R�����g��������
 |
 |	char	*find_comment(cmstp, cmd_name, prm_name)
 |
 |		CMS_TBL	*cmstp;		�R�}���h�I���e�[�u���|�C���^
 |		char	*cmd_name;	�R�}���h��
 |		char	*prm_name;	�p�����[�^��
 |
 |		�Ԓl			�R�����g
 |
 =======================================================================*/
static	char	*find_comment(CMS_TBL *cmstp, char *cmd_name, char *prm_name)
{
	int	i;		/* ���[�v�J�E���^ */
	CMD_TBL	*cmdtp;		/* �R�}���h�e�[�u���|�C���^ */
	PRM_TBL	*prmtp;		/* �p�����[�^��ރe�[�u���|�C���^ */

	if (cmstp == NULL)
		return(NULL);

	/* �R�}���h������ */
	for (i = 0, cmdtp = cmstp->ptr; i < cmstp->n; i++, cmdtp++) {
		if (strcmp(cmd_name, cmdtp->name) == 0)
			break;
	}

	if (i == cmstp->n)
		return(NULL);

	if (cmdtp->n == 0 || cmdtp->ptr->name == NULL)
		return(cmdtp->guide);

	/* �p�����[�^������ */
	for (i = 0, prmtp = cmdtp->ptr; i < cmdtp->n; i++, prmtp++) {
		if (strcmp(prm_name, prmtp->name) == 0)
			break;
	}

	if (i == cmdtp->n)
		return(NULL);

	return(prmtp->guide);
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C���o�^��ʏ���
 |
 |	void	prog6b()
 |
 =======================================================================*/
void	prog6b()
{
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	int	ch;		/* �L�[���͕��� */
	int	color;		/* �F�R�[�h */
	int	y;		/* �R�}���h�\���s�ʒu */
	int	mode;		/* �R�}���h���́^�x�m���̓��[�h */
	int	y_or_n;		/* �x���� ���� �m�� */
	char	buf[76*2+1];	/* �R�}���h���̓o�b�t�@ */
	char	id[2];		/* �R�}���h�h�c */
	QUEUE	*qp;		/* �L���[�|�C���^ */

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	color = online ? C_ONLINE : C_OFFLINE;
	xprintf(2, 1, color, msg30, csg.nsg.sys_name, cmf_name);
	dspstr(2, 22, msg650, C_GUIDE);
	dspstr(2, 23, msg660, C_GUIDE);

	/* ���̓t�B�[���h�\�� */
	dsp_field(37, 23,  1, C_GUIDE);

	y = 0;

	/* ���܂܂œo�^�����R�}���h�̕\�� */
	for (qp = cmf_que; qp != NULL; qp = qp->chain) {
		dspstr(2, y + 3, qp->data, C_GUIDE);
		y = next_line(y);
	}

	setfunc(FUNC4|FUNC5);	/* �t�@���N�V�����L�[�Z�b�g */

	memset(buf, ' ', 76);	/* ���̓o�b�t�@�N���A */

	mode = 0;		/* �R�}���h���ړ��̓��[�h */
	y_or_n = ' ';		/* �x�����^�m�����̓t���O�N���A */

	while (prog == 6) {
		if (mode == 0) {
			/* �R�}���h���ړ��� */
			ch = inputm(buf, 2, y + 3, 76 * 2, 76, C_GUIDE);
		} else {
			/* �x�����@�����@�m������ */
			t_loc(37, 23);
			t_csron();
			ch = getchr();
			t_csroff();
		}
		switch (ch) {
		case KEY_CR:
			if (mode == 0) {
				buf[strcount(buf)] = '\0';
				enq_cmf(buf);
				y = next_line(y);
				memset(buf, ' ', 76);
			} else {
				if (y_or_n == 'Y')
					prog = 2;
				else if (y_or_n == 'N') {
					write_cmf();
					prog = 0;
				} else
					beep();
			}
			break;
		case KEY_HOME:
		case KEY_HELP:
			mode = !mode;
			break;
		case KEY_F4:
			id[0] = csg.nsg.offline_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		case KEY_F5:
			id[0] = csg.nsg.online_id;
			id[1] = '\0';
			set_keybuf(id);
			break;
		case 'Y':
		case 'y':
			y_or_n = 'Y';
			dspstr(37, 23, "Y", C_GUIDE);
			break;
		case 'N':
		case 'n':
			y_or_n = 'N';
			dspstr(37, 23, "N", C_GUIDE);
			break;
		default:
			beep();
			break;
		}
	}

	/* ��ʏ��� */
	t_cls();
	g_cls();
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C�����e�\����ʏ���
 |
 |	void	prog6c()
 |
 =======================================================================*/
void	prog6c()
{
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 22, 79, 22},
		{-1}
	};
	int	color;		/* �F�R�[�h */
	int	y;		/* �R�}���h�\���s�ʒu */
	char	*cmd;		/* �R�}���h�|�C���^ */

	/* ��ʏ��� */
	t_cls();
	g_cls();

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	color = online ? C_ONLINE : C_OFFLINE;
	xprintf(2, 1, color, msg670, csg.nsg.sys_name, cmf_name);
	dspstr(2, 23, msg680, C_GUIDE);

	/* ���܂܂œo�^�����R�}���h�̕\�� */
	y = 0;
	while ((cmd = deq_cmf()) != NULL) {
		dspstr(2, y + 3, cmd, C_GUIDE);
		y = next_line(y);
		free(cmd);
	}

	/* �d�r�b���͑҂� */
	while (getchr() != KEY_ESC)
		;

	/* ��ʏ��� */
	t_cls();
	g_cls();
}