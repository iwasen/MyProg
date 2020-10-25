/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: ���b�Z�[�W��M��ʏ���		*
 *		�t�@�C����	: prog7.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"
#include	<time.h>

/*	�ʐM�X�e�[�^�X�R�[�h	*/
#define	ST_RD	0
#define	ST_RA	1
#define	ST_SR	2
#define	ST_CQ	3
#define	ST_AK	4
#define	ST_STSR	5

/*	�����֐�	*/
static	void	check_key(void);
static	void	check_tim(void);
static	void	check_rcv(void);
static	void	rcv_sra(void);
static	void	rcv_raa(void);
static	void	rcv_cqa(void);
static	void	rcv_ab(void);
static	void	rcv_rd(void);
static	void	rcv_aka(void);
static	void	time_out(void);
static	void	next_prog(int);
static	void	kaiwa_outou(void);

/*	�X�^�e�B�b�N�ϐ�	*/
static	int	status;
static	int	next_prog_no;
static	long	rcv_time;
static	int	kaiwa_flag;

/*=======================================================================
 |
 |		���b�Z�[�W��M��ʏ���
 |
 |	void	prog7()
 |
 =======================================================================*/
void	prog7()
{
	static	LINE	line[] = {	/* �r���f�[�^ */
		{ 1,  1,  0, 79, 24},
		{ 3,  1,  2, 79,  2},
		{ 0,  1, 21, 79, 21},
		{-1}
	};
	int	color;		/* �F�R�[�h */

	/* �R�}���h�쐬���̏ꍇ�̓R�}���h�I����ʂ� */
	if (mkcmd) {
		prog = 2;
		return;
	}

	if (old_prog != 6) {
		/* ��ʏ��� */
		t_cls();
		g_cls();
	}

	/* �r���\�� */
	dspline(line, C_WAKU);

	/* ��ʕ\�� */
	if (online) {
		color = C_ONLINE;
		xprintf(2, 1, color, msg20, ssg.nsg.sys_name, msg700);
	} else {
		color = C_OFFLINE;
		xprintf(2, 1, color, msg10, csg.nsg.sys_name, msg701);
	}
	dspstr(2, 22, msg710, color);
	dspstr(26, 22, msg720, C_GUIDE);

	dsp_msgenv();		/* ���b�Z�[�W�o�͊��\�� */

	restore_scr();		/* ��M��ʃ��X�g�A */

	setfunc(online ? 0 : FUNC1);	/* �t�@���N�V�����L�[�Z�b�g */

	next_prog_no = 0;	/* �v���O�����ԍ��N���A */
	stop_flag = 0;		/* �r�s�n�o�L�[���̓t���O�N���A */

	if (online) {
		time(&rcv_time);	/* �������ǂ݂��� */
		timreset();		/* �^�C�}�[���Z�b�g */
		send_sig(SIG_RA);	/* �q�`�M�����M */
		status = ST_RA;
	} else
		status = ST_RD;

	while (prog == 7) {
		if (status == ST_RD) {
			check_key();	/* �L�[���̓`�F�b�N */
			check_tim();	/* ���ԃ`�F�b�N */
		}
		check_rcv();		/* ��M�`�F�b�N */
		out_msg();		/* ���b�Z�[�W�o�� */
	}

	save_scr();		/* ��M��ʃZ�[�u */

	/* �R�}���h�t�@�C�����s���������璆�~���� */
	if (exec_cmf_flag) {
		fclose(fp_cmf);
		exec_cmf_flag = 0;
	}
}

/*=======================================================================
 |
 |		�L�[���̓`�F�b�N
 |
 |	void	check_key()
 |
 =======================================================================*/
static	void	check_key()
{
	/* �r�s�n�o�L�[���̓`�F�b�N */
	if (stop_flag) {
		stop_flag = 0;		/* �r�s�n�o�L�[���̓t���O�N���A */
		send_sig(SIG_STSR);	/* �r�s�r�q�M�����M */
		status = ST_STSR;
		return;
	}
		
	switch (inkey()) {
	case 0:
		break;
	case KEY_ESC:
		if (online)
			next_prog(2);	/* �R�}���h�I����ʁ|�P */
		else {
			/* ���[�J���R�}���h�h�c�Z�b�g */
			cms_current = loctbl;
			cmdbuf[0] = csg.nsg.offline_id;
			cmdbuf[1] = '\0';

			next_prog(3);	/* �R�}���h�I����ʁ|�Q */
		}
		break;
	case KEY_HOME:	/* �g�n�l�d�L�[ */
	case KEY_HELP:	/* �g�d�k�o�L�[ */
		next_prog(6);	/* �R�}���h���ړ��͉�� */
		break;
	case KEY_F1:
		next_prog(1);	/* �R�}���h�R�l�N�V�����J�݉�� */
		break;
	default:
		beep();
		break;
	}
}

/*=======================================================================
 |
 |		�莞�N���R�}���h�`�F�b�N���������Ď�
 |
 |	void	check_tim()
 |
 =======================================================================*/
static	void	check_tim()
{
	long	ltime;			/* ������ */
	struct	tm	*ltm;		/* ���ԏ��|�C���^ */
	char	ctime[11];		/* ������ */
	static	char	stime[11];	/* �O�񎞍� */
	int	i;			/* ���[�v�J�E���^ */
	char	*sp;			/* �Z�p���[�^�|�C���^ */
	char	buf[82];		/* �莞�N���R�}���h�o�b�t�@ */

	if (online == 0)
		return;

	/* ���ݎ��Ԏ��o�� */
	time(&ltime);

	/* �������Ď� */
	if (ssg.nsg.kanshi != 0) {
		if (ltime > rcv_time + ssg.nsg.kanshi * 60) {
			send_sig(SIG_AK);
			status = ST_AK;
			rcv_time += ssg.nsg.kanshi * 60;
		}
	}

	/* ���ԕҏW */
	ltm = localtime(&ltime);
	sprintf(ctime, "%02u%02u%02u%02u%02u",
			ltm->tm_year, ltm->tm_mon+1, ltm->tm_mday,
			ltm->tm_hour, ltm->tm_min);

	if (strcmp(ctime, stime) == 0)
		return;

	/* �莞�N���R�}���h�`�F�b�N */
	for (i = 0; i < 5; i++) {
		strcpy(buf, ssg.aec[i].command);
		if ((sp = strchr(buf, ';')) != NULL) {
			*sp = '\0';
			if (strcmp(ctime+(10 - (sp - buf)), buf) == 0) {
				sp++;
				/* ���^�[���L�[�Z�b�g */
				strcat(sp, "\x0d");

				/* �L�[�o�b�t�@�ɃZ�b�g */
				set_keybuf(sp);

				/* �R�}���h���ړ��͉�� */
				next_prog(6);
				break;
			}
		}
	}

	strcpy(stime, ctime);	/* �������Z�[�u */
}

/*=======================================================================
 |
 |		�f�[�^��M�`�F�b�N
 |
 |	void	check_rcv()
 |
 =======================================================================*/
static	void	check_rcv()
{
	if (online == 0)
		return;

	switch (receive()) {
	case SIG_SRA:		/* �r�q�`�M����M */
		rcv_sra();
		break;
	case SIG_RAA:		/* �q�`�`�M����M */
		rcv_raa();
		break;
	case SIG_CQA:		/* �b�p�`�M����M */
		rcv_cqa();
		break;
	case SIG_AB:		/* �`�a�M����M */
		rcv_ab();
		break;
	case SIG_RD:		/* �q�c�M����M */
		rcv_rd();
		break;
	case SIG_AKA:		/* �`�j�`�M����M */
		rcv_aka();
		break;
	case TIMEOUT:		/* �^�C���A�E�g */
		time_out();
		break;
	}
}

/*=======================================================================
 |
 |		�r�q�`�M����M����
 |
 |	void	rcv_sra()
 |
 =======================================================================*/
static	void	rcv_sra()
{
	if (status == ST_SR) {
		if (kaiwa_flag)
			kaiwa_outou();	/* ��b���� */
		else if (exec_cmf_flag) {
			exec_cmf();		/* �R�}���h�t�@�C�����s */
			send_sig(SIG_RA);	/* �q�`�M�����M */
			status = ST_RA;
		} else
			prog = next_prog_no;
	} else if (status == ST_STSR) {
		send_sd("STP/");	/* �r�s�o�R�}���h���M */
		send_sig(SIG_RA);	/* �q�`�M�����M */
		status = ST_RA;
	}
}

/*=======================================================================
 |
 |		�q�`�`�M����M����
 |
 |	void	rcv_raa()
 |
 =======================================================================*/
static	void	rcv_raa()
{
	if (status == ST_RA) {
		status = ST_RD;
		timreset();		/* �^�C�}�[���Z�b�g */
		if (disconxxxt_request) {
			/* �R�l�N�V�����ؒf�v������ */
			disconxxxt_request = 0;
			send_sig(SIG_CQ);	/* �b�p�M�����M */
			status = ST_CQ;
		}
	}
}

/*=======================================================================
 |
 |		�b�p�`�M����M����
 |
 |	void	rcv_cqa()
 |
 =======================================================================*/
static	void	rcv_cqa()
{
	if (status == ST_CQ) {
		timreset();	/* �^�C�}�[���Z�b�g */
		online = 0;	/* �I�����C���t���O�N���A */
		/* �R�l�N�V�����ؒf���b�Z�[�W�\�� */
		dsp_loc_msg(LOC_MSG_DISCON, ssg.nsg.conxxxtion);
		prog = 1;
	}
}

/*=======================================================================
 |
 |		�`�a�M����M����
 |
 |	void	rcv_ab()
 |
 =======================================================================*/
static	void	rcv_ab()
{
	/* �A�{�[�g�M����M���b�Z�[�W�\�� */
	ab_msg[15] = rcv_buf[4];
	enq_msg(MSG_ANM, ab_msg);

	online = 0;	/* �I�����C���t���O�N���A */

	/* �R�l�N�V�����ؒf���b�Z�[�W�\�� */
	dsp_loc_msg(LOC_MSG_DISCON, ssg.nsg.conxxxtion);

	out_msg();	/* ���b�Z�[�W�o�� */
	alxxx_mj();	/* �A���[�� */
	prog = 8;	/* �R�l�N�V�����ؒf��� */
}

/*=======================================================================
 |
 |		�q�c�M����M����
 |
 |	void	rcv_rd()
 |
 =======================================================================*/
static	void	rcv_rd()
{
	switch (rcv_buf[4]) {
	case '2':	/* �R�}���h�������b�Z�[�W */
		/* ��b�����`�F�b�N */
		if (rcv_buf[5] == MN_KAIWA) {
			send_sig(SIG_SR);
			status = ST_SR;
			kaiwa_flag = 1;
		}

		/* �R�}���h�t�@�C�����s���`�F�b�N */
		if (exec_cmf_flag) {
			if (strncmp(rcv_buf+5, MN_NORMAL, 4) == 0
				    || strncmp(rcv_buf+5, MN_ERROR, 4) == 0
				    && csg.opt.cmd_err == 0) {
				send_sig(SIG_SR);
				status = ST_SR;
			} else if (strncmp(rcv_buf+5, MN_ERROR, 4) == 0
						&& csg.opt.cmd_err != 0) {
				fclose(fp_cmf);
				exec_cmf_flag = 0;
			}
		}

		memcpy(rcv_buf+1, "@MSG", 4);
		enq_msg(MSG_CRM, rcv_buf+1);
		break;
	case '3':	/* �������b�Z�[�W */
	case '4':	/* �ĊJ���b�Z�[�W */
		memcpy(rcv_buf+1, "@MSG", 4);
		enq_msg(MSG_ANM, rcv_buf+1);
		break;
	case '5':	/* �C�����b�Z�[�W */
		cmdbuf[0] = csg.nsg.online_id;
		strcpy(cmdbuf+1, rcv_buf+5);
		if (search_cms(cmdbuf+1) != 0)
			next_prog(5);
		else {
			/* �R�}���h�I���}�[�N�Z�b�g */
			strcat(cmdbuf, "/");

			/* �L�[�o�b�t�@�ɃZ�b�g */
			set_keybuf(cmdbuf);

			next_prog(6);
		}
		break;
	}
}

/*=======================================================================
 |
 |		�`�j�`�M����M����
 |
 |	void	rcv_aka()
 |
 =======================================================================*/
static	void	rcv_aka()
{
	if (status == ST_AK) {
		status = ST_RD;
		timreset();	/* �^�C�}�[���Z�b�g */
	}
}

/*=======================================================================
 |
 |		�^�C���A�E�g����
 |
 |	void	time_out()
 |
 =======================================================================*/
static	void	time_out()
{
	/* �V�X�e�����������b�Z�[�W�\�� */
	dsp_loc_msg(LOC_MSG_NORESP);

	online = 0;	/* �I�����C���t���O�N���A */

	/* �R�l�N�V�����ؒf���b�Z�[�W�\�� */
	dsp_loc_msg(LOC_MSG_DISCON, ssg.nsg.conxxxtion);

	out_msg();	/* ���b�Z�[�W�o�� */
	alxxx_mj();	/* �A���[�� */
	prog = 8;	/* �R�l�N�V�����ؒf��� */
}

/*=======================================================================
 |
 |		�v���O�����ԍ��Z�b�g
 |
 |	void	next_prog(prog_no)
 |
 |		int	prog_no;	�v���O�����ԍ�
 |
 =======================================================================*/
static	void	next_prog(int prog_no)
{
	if (online) {
		send_sig(SIG_SR);	/* �r�q�M�����M */
		status = ST_SR;
		next_prog_no = prog_no;
	} else
		prog = prog_no;
}

/*=======================================================================
 |
 |		��b��������
 |
 |	void	kaiwa_outou()
 |
 =======================================================================*/
static	void	kaiwa_outou()
{
	static	char	outou[] = "\\(Y/N)";	/* ��b�������b�Z�[�W */
	int	yn;		/* �x�����^�m�� �t���O */
	int	loop_sw;	/* ���[�v����ϐ� */

	/* ��b�������b�Z�[�W�\�� */
	outou[0] = csg.nsg.online_id;
	dsp_msg(outou, C_CRM);

	yn = 0;		/* �x�����^�m���t���O������ */
	t_csron();	/* �J�[�\���\�� */

	loop_sw = 1;
	while (loop_sw) {
		t_loc(yn == 0 ? 4 : 6, csr_y + 2);
		switch (getchr()) {
		case KEY_CR:
			loop_sw = 0;
			break;
		case KEY_LEFT:
		case 'Y':
		case 'y':
			yn = 0;
			break;
		case KEY_RIGHT:
		case 'N':
		case 'n':
			yn = 1;
			break;
		case ' ':
			yn ^= 1;
			break;
		}
	}

	t_csroff();	/* �J�[�\������ */

	send_sd(yn == 0 ? "Y" : "N");	/* �x or �m ���M */

	send_sig(SIG_RA);	/* �q�`�M�����M */
	status = ST_RA;

	kaiwa_flag = 0;
}