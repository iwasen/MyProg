/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: ���b�Z�[�W����			*
 *		�t�@�C����	: msg.c					*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"
#include <time.h>

/*	�����֐�	*/
static	MSG_TBL	*read_msg1file(char *);
static	char	*loc_kihonbu(void);
static	void	read_msg(MSG_TBL *, int, int *, int *, KAHENBU **, char **);
static	char	*read_submsg(MSG_TBL *, int);

/*=======================================================================
 |
 |		���b�Z�[�W��M��ʕۑ�����
 |
 |	void	save_scr()
 |
 =======================================================================*/
void	save_scr()
{
	char	*vp;		/* �u�q�`�l�|�C���^ */

	FP_OFF(vp) = 80 * 2 * 3;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(t_save_buf, vp, 80*2*18);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(a_save_buf, vp, 80*2*18);
}

/*=======================================================================
 |
 |		���b�Z�[�W��M��ʕ�������
 |
 |	void	restore_scr()
 |
 =======================================================================*/
void	restore_scr()
{
	char	*vp;		/* �u�q�`�l�|�C���^ */

	FP_OFF(vp) = 80 * 2 * 3;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(vp, t_save_buf, 80*2*18);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(vp, a_save_buf, 80*2*18);
}

/*=======================================================================
 |
 |		���b�Z�[�W�o�͏���
 |
 |	void	out_msg()
 |
 =======================================================================*/
void	out_msg()
{
	int	color;		/* �\���F */
	int	prn;		/* �v�����^�[�o�͗L��^�����t���O */
	int	fil;		/* �t�@�C���o�͗L��^�����t���O */
	int	class;		/* �������b�Z�[�W�N���X */
	char	*fname;		/* ���b�Z�[�W�t�@�C���� */
	MSG_QUE	*mqp;		/* ���b�Z�[�W�L���[�|�C���^ */
	char	*msgp;		/* ���b�Z�[�W������|�C���^ */
	char	*nlp;		/* ���s�R�[�h�|�C���^ */
	FILE	*fp;		/* �t�@�C���|�C���^ */
	int	dummy;

	while ((mqp = deq_msg()) != NULL) {
		msgp = mqp->msg;
		class = MSG_CLASS_DFLT;
		/* ���b�Z�[�W��ʃ`�F�b�N */
		switch (mqp->type) {
		case MSG_ANM:	/* �������b�Z�[�W */
			msgp = cnv_msg(msgp+4, anm_msg, &class);
			color = ssg.anm.dsp ? (class == MSG_CLASS_A1 || class == MSG_CLASS_A2 ? C_ANMA : C_ANMB) : 0;
			prn = ssg.anm.prn;
			fil = ssg.anm.fil;
			fname = ssg.anm.fname;
			break;
		case MSG_CRM:	/* �R�}���h�������b�Z�[�W */
			if (strncmp(msgp, "@MSG", 4) == 0)
				msgp = cnv_msg(msgp+4, crm_msg, &dummy);
			color = ssg.crm.dsp ? C_CRM : 0;
			prn = ssg.crm.prn;
			fil = ssg.crm.fil;
			fname = ssg.crm.fname;
			break;
		case MSG_CNS:	/* �R���\�[�����b�Z�[�W */
			color = csg.lrm.dsp ? C_CNSMSG : 0;
			prn = csg.lrm.prn;
			fil = csg.lrm.fil;
			fname = csg.lrm.fname;
			break;
		case MSG_CNSR:	/* �ԐF�R���\�[�����b�Z�[�W */
			color = csg.lrm.dsp ? C_RED : 0;
			prn = csg.lrm.prn;
			fil = csg.lrm.fil;
			fname = csg.lrm.fname;
			break;
		}

		/* ���b�Z�[�W�o�� */
		for (;;) {
			/* �s�̕��� */
			if ((nlp = strchr(msgp, '\n')) != NULL)
				*nlp = '\0';

			/* ��ʕ\�� */
			if (color != 0)
				dsp_msg(msgp, color);

			/* �v�����^�[�o�� */
			if (prn)
				lprintf("%s\x0d\x0a", msgp);

			/* �t�@�C���o�� */
			if (fil) {
				if ((fp = fopen(fname, "a")) != NULL) {
					fprintf(fp, "%s\n", fil == 1 ? msgp : mqp->msg);
					fclose(fp);
				}
			}

			if (nlp == NULL)
				break;

			msgp = nlp + 1;
		}

		free_msg(mqp);

		if (class == MSG_CLASS_A1)
			alxxx_mj();
		else if (class == MSG_CLASS_A2)
			alxxx_mn();
	}
}

/*=======================================================================
 |
 |		���b�Z�[�W�\������
 |
 |	void	dsp_msg(msg, color)
 |
 |		char	*msg;		���b�Z�[�W
 |		int	color;		�\���F
 |
 =======================================================================*/
 void	dsp_msg(char *msg, int color)
{
	char	*p;		/* ���b�Z�[�W������|�C���^ */
	int	n;		/* �P�s�̕\�������� */

	for (p = msg; *p != '\0'; p += n) {
		kbhit();
		n = min(strlen(p), 76);
		dspnstr(2, csr_y + 3, p, n , color);
		csr_y = next_line(csr_y);
	}
}

/*=======================================================================
 |
 |		���s����
 |
 |	int	next_line(y)
 |
 |		int	y;		���݂̍s�ʒu
 |
 |		�Ԓl			���s��̍s�ʒu
 |
 =======================================================================*/
int	next_line(int y)
{
	char	*vp;		/* �u�q�`�l�|�C���^ */

	if (y < 17)
		y++;
	else {
		/* �P�s�X�N���[���A�b�v */
		FP_OFF(vp) = 80 * 2 * 3;
		FP_SEG(vp) = SEG_TVRAM;
		memcpy(vp, vp+160, 80*2*17);
		FP_SEG(vp) = SEG_ATTR;
		memcpy(vp, vp+160, 80*2*17);
		dspspc(2, 20, 76);
	}

	return(y);
}

/*=======================================================================
 |
 |		���b�Z�[�W�o�^����
 |
 |	void	enq_msg(type, msg)
 |
 |		int	type;		���b�Z�[�W���
 |		char	*msg;		���b�Z�[�W
 |
 =======================================================================*/
void	enq_msg(int type, char *msg)
{
	MSG_QUE	mq;		/* ���b�Z�[�W�L���[ */

	mq.type = type;		/* ���b�Z�[�W�^�C�v�Z�[�u */
	mq.msg = talloc(strlen(msg) + 1);
	strcpy(mq.msg, msg);		/* ���b�Z�[�W�f�[�^�Z�[�u */

	en_queue(&msg_que, &mq, sizeof(mq));	/* �L���[�o�^ */
}

/*=======================================================================
 |
 |		���b�Z�[�W���o������
 |
 |	MSG_QUE	*deq_msg()
 |
 |		�Ԓl			���b�Z�[�W�L���[�|�C���^
 |
 =======================================================================*/
MSG_QUE	*deq_msg()
{
	return(de_queue(&msg_que));
}

/*=======================================================================
 |
 |		���b�Z�[�W�������
 |
 |	void	free_msg(mqp)
 |
 |		MSG_QUE	*mqp;		���b�Z�[�W�L���[�|�C���^
 |
 =======================================================================*/
void	free_msg(MSG_QUE *mqp)
{
	free(mqp->msg);
	free(mqp);
}

/*=======================================================================
 |
 |		�S���b�Z�[�W�t�@�C���ǂݍ��ݏ���
 |
 |	void	read_msgfile()
 |
 =======================================================================*/
void	read_msgfile()
{
	/* �������b�Z�[�W�t�@�C���ǂݍ��� */
	anm_msg = read_msg1file(csg.fsg.amsg_file);

	/* �R�}���h�������b�Z�[�W�t�@�C���ǂݍ��� */
	crm_msg = read_msg1file(csg.fsg.cmsg_file);

	/* ���[�J�����b�Z�[�W�t�@�C���ǂݍ��� */
	loc_msg = read_msg1file(csg.fsg.lmsg_file);
}

/*=======================================================================
 |
 |		�P���b�Z�[�W�t�@�C���ǂݍ��ݏ���
 |
 |	MSG_TBL	*read_msg1file(file_name)
 |
 |		char	*file_name;	���b�Z�[�W�t�@�C����
 |
 |		�Ԓl			���b�Z�[�W�e�[�u���|�C���^
 |
 =======================================================================*/
static	MSG_TBL	*read_msg1file(char *file_name)
{
	FILE	*fp;		/* �t�@�C���|�C���^ */
	MSG_TBL	*ptr;		/* ���b�Z�[�W�e�[�u���|�C���^ */

	/* �t�@�C�������ݒ肳��Ă��Ȃ��ꍇ�̓��^�[�� */
	if (file_name[0] == '\0')
		return(NULL);

	/* ���b�Z�[�W�t�@�C���I�[�v�� */
	if ((fp = fopen(file_name, "rb")) == NULL)
		return(NULL);

	/* ���b�Z�[�W�e�[�u���G���A�m�� */
	ptr = talloc(sizeof(MSG_TBL));

	/* ���b�Z�[�W���ǂݍ��� */
	fread(&ptr->n_msg, sizeof(int), 1, fp);
	ptr->fp = fp;

	/* ���b�Z�[�W�C���f�b�N�X�ǂݍ��� */
	ptr->msg = talloc(ptr->n_msg * sizeof(struct msg_no));
	fread(ptr->msg, sizeof(struct msg_no), ptr->n_msg, fp);

	return(ptr);
}

/*=======================================================================
 |
 |		���b�Z�[�W���{��ϊ�����
 |
 |	char	*cnv_msg(msg, mtp, class)
 |
 |		char	*msgp;		�ϊ��O���b�Z�[�W
 |		MSG_TBL	*mtp;		���b�Z�[�W�ϊ��e�[�u��
 |		int	*class;		�������b�Z�[�W�N���X�i�`�����a�j
 |
 |		�Ԓl			�ϊ���̃��b�Z�[�W�ւ̃|�C���^
 |
 =======================================================================*/
char	*cnv_msg(char *msgp, MSG_TBL *mtp, int *class)
{
	int	msg_no;		/* ���b�Z�[�W�ԍ� */
	int	n_kahenbu;	/* ��M�f�[�^��̉ϕ��� */
	int	n_kahenbu2;	/* ���b�Z�[�W�t�@�C����̉ϕ��� */
	int	l_kahenbu;	/* �ϕ��̒��� */
	int	i;		/* ���[�v�J�E���^ */
	KAHENBU	*kahenbu;	/* �ϕ��f�[�^ */
	KAHENBU	*kp;		/* �ϕ��f�[�^�|�C���^ */
	char	prmbuf[256];	/* �ϕ��ҏW�o�b�t�@ */
	char	*prm;		/* �ϕ��ҏW�o�b�t�@�|�C���^ */
	char	*msg;		/* ү����̧�ق���ǂݍ��񂾃��b�Z�[�W�f�[�^ */
	char	*kdp[100];	/* �ϕ��f�[�^�|�C���^ */
	char	*p;		/* �ϕ����o���p�|�C���^ */
	static	char	msgbuf[512];	/* �ϊ���̃��b�Z�[�W�o�b�t�@ */

	/* ���b�Z�[�W�ԍ��C�ϕ������o�� */
	sscanf(msgp, "%4d%2d", &msg_no, &n_kahenbu);

	/* �ϕ����o�� */
	for (i = 1, p = msgp + 6; i <= n_kahenbu; i++) {
		sscanf(p, "%2d", &l_kahenbu);
		kdp[i] = talloc(l_kahenbu + 1);
		p += 2;
		memcpy(kdp[i], p, l_kahenbu);
		kdp[i][l_kahenbu] = '\0';
		p += l_kahenbu;
	}

	/* ��{�����o�� */
	kdp[0] = talloc(45);
	sprintf(kdp[0], "[VSE#%.2s MODULE#%.2s %.1s] %.23s", p+3, p+1, p, p+5);

	/* ���b�Z�[�W�t�@�C���ǂݍ��� */
	read_msg(mtp, msg_no, class, &n_kahenbu2, &kahenbu, &msg);

	/* �ϕ��f�[�^�ҏW */
	for (i = 0, prm = prmbuf, kp = kahenbu; i < n_kahenbu2; i++, kp++) {
		switch (kp->type) {
		case 0:		/* ���l�^ */
			sscanf(kdp[kp->kahenbu_no], "%x", ((int *)prm)++);
			break;
		case 1:		/* �����^ */
			*((char **)prm)++ = kdp[kp->kahenbu_no];
			break;
		case 2:		/* �T�u���b�Z�[�W */
			msg_no = atoi(kdp[kp->kahenbu_no]);
			free(kdp[kp->kahenbu_no]);
			kdp[kp->kahenbu_no] = read_submsg(mtp, msg_no);
			*((char **)prm)++ = kdp[kp->kahenbu_no];
			break;
		}
	}

	/* ���b�Z�[�W�ҏW */
	vsprintf(msgbuf, msg, prmbuf);

	/* �o�b�t�@��� */
	free(kahenbu);
	free(msg);
	for (i = 0; i <= n_kahenbu; i++)
		free(kdp[i]);

	return(msgbuf);
}

/*=======================================================================
 |
 |		���[�J�����b�Z�[�W�\������
 |
 |	void	dsp_loc_msg(msg_no, p)
 |
 |		int	msg_no;		���b�Z�[�W�ԍ�
 |		char	*p;		�ϕ��f�[�^
 |
 =======================================================================*/
void	dsp_loc_msg(int msg_no, char *p)
{
	int	class;		/* �������b�Z�[�W�N���X�i�_�~�[�j */
	int	n_kahenbu;	/* �ϕ��� */
	int	i;		/* ���[�v�J�E���^ */
	int	msg_type;	/* ���b�Z�[�W��� */
	KAHENBU	*kahenbu;	/* �ϕ��f�[�^ */
	KAHENBU	*kp;		/* �ϕ��f�[�^�|�C���^ */
	char	prmbuf[256];	/* �ϕ��ҏW�o�b�t�@ */
	char	*prm;		/* �ϕ��ҏW�o�b�t�@�|�C���^ */
	char	**pp;		/* �p�����[�^�|�C���^ */
	char	*msg;		/* ү����̧�ق���ǂݍ��񂾃��b�Z�[�W�f�[�^ */
	char	msgbuf[256];	/* �ϊ���̃��b�Z�[�W�o�b�t�@ */

	/* ���b�Z�[�W�t�@�C���ǂݍ��� */
	read_msg(loc_msg, msg_no, &class, &n_kahenbu, &kahenbu, &msg);

	/* �ϕ��C��{���ҏW */
	pp = &p;
	for (i = 0, prm = prmbuf, kp = kahenbu; i < n_kahenbu; i++, kp++) {
		if (kp->kahenbu_no == 0) {
			/* ��{�� */
			*((char **)prm)++ = loc_kihonbu();
		} else {
			/* �ϕ� */
			if (kp->type == 0) {
				/* ���l�^ */
				*((int *)prm)++ = *((int *)pp)++;
			} else {
				/* �����^ */
				*((char **)prm)++ = *((char **)pp)++;
			}
		}
	}

	vsprintf(msgbuf, msg, prmbuf);	/* ���b�Z�[�W�ҏW */

	/* �o�b�t�@��� */
	free(kahenbu);
	free(msg);

	/* ���b�Z�[�W��ʃZ�b�g */
	switch (msg_no) {
	case LOC_MSG_DISCON:
	case LOC_MSG_RESTART:
		msg_type = MSG_CNSR;
		break;
	default:
		msg_type = MSG_CNS;
		break;
	}

	enq_msg(msg_type, msgbuf);	/* ���b�Z�[�W�o�^ */
}

/*=======================================================================
 |
 |		���[�J�����b�Z�[�W��{���ҏW
 |
 |	char	*loc_kihonbu()
 |
 |		�Ԓl			���[�J�����b�Z�[�W��{��
 |
 =======================================================================*/
static	char	*loc_kihonbu()
{
	static	char	kihonbu[30];	/* ��{���ҏW�o�b�t�@ */
	static	char	week[7][3] = {	/* �j���f�[�^ */
		"��", "��", "��", "��", "��", "��", "�y"
	};
	long	ltime;		/* ���������o���G���A */
	struct	tm	*ltm;	/* �����\���̃|�C���^ */

	/* ���������o�� */
	time(&ltime);
	ltm = localtime(&ltime);

	/* ��{���ҏW */
	sprintf(kihonbu, "LOC%04u %04u-%02u-%02u(%s) %02u:%02u"
			, ++loc_seq
			, 1900 + ltm->tm_year, ltm->tm_mon+1, ltm->tm_mday
			, week[ltm->tm_wday]
			, ltm->tm_hour, ltm->tm_min);

	return(kihonbu);
}

/*=======================================================================
 |
 |		���b�Z�[�W�ǂݍ��ݏ���
 |
 |	void	read_msg(mtp, msg_no, class, n_kahenbu, kahenbu, msg)
 |
 |		MSG_TBL	*mtp;		���b�Z�[�W�e�[�u���|�C���^
 |		int	msg_no;		���b�Z�[�W�ԍ�
 |		int	*class;		�������b�Z�[�W�N���X�i�`�����a�j
 |		int	*n_kahenbu;	�ϕ���
 |		KAHENBU	**kahenbu;	�ϕ����
 |		char	**msg;		���b�Z�[�W
 |
 =======================================================================*/
static	void	read_msg(MSG_TBL *mtp, int msg_no, int *class, int *n_kahenbu
					, KAHENBU **kahenbu, char **msg)
{
	int	i;		/* ���[�v�J�E���^ */
	int	len;		/* ���b�Z�[�W�� */
	char	buf[80];

	*class = MSG_CLASS_DFLT;	/* ���b�Z�[�W�N���X�Ƀf�t�H���g�Z�b�g*/
	*n_kahenbu = 0;			/* �ϕ����N���A */
	*kahenbu = NULL;
	*msg = NULL;

	if (mtp == NULL) {
		strcpy(buf, "���b�Z�[�W�t�@�C��������܂���");
		*msg = talloc(strlen(buf) + 1);
		strcpy(*msg, buf);
		return;
	}

	/* ���b�Z�[�W�ԍ����� */
	for (i = 0; i < mtp->n_msg; i++) {
		if (mtp->msg[i].msg_no == msg_no)
			break;
	}

	if (i == mtp->n_msg) {
		/* ���b�Z�[�W������ */
		sprintf(buf, "���o�^���b�Z�[�W(%4d)", msg_no);
		*msg = talloc(strlen(buf) + 1);
		strcpy(*msg, buf);
		return;
	}

	/* �ǂݍ��݈ʒu�Z�b�g */
	fseek(mtp->fp, mtp->msg[i].offset, 0);

	/* �������b�Z�[�W�N���X�ǂݍ��� */
	fread(class, sizeof(int), 1, mtp->fp);

	/* �ϕ����ǂݍ��� */
	fread(n_kahenbu, sizeof(int), 1, mtp->fp);

	/* �ϕ��f�[�^�ǂݍ��� */
	*kahenbu = talloc(sizeof(KAHENBU) * (*n_kahenbu));
	fread(*kahenbu, sizeof(KAHENBU), *n_kahenbu, mtp->fp);

	/* ���b�Z�[�W���ǂݍ��� */
	fread(&len, sizeof(int), 1, mtp->fp);

	/* ���b�Z�[�W�f�[�^�ǂݍ��� */
	*msg = talloc(len + 1);
	fread(*msg, 1, len, mtp->fp);
	(*msg)[len] = '\0';
}

/*=======================================================================
 |
 |		�T�u���b�Z�[�W�ǂݍ��ݏ���
 |
 |	char	*read_submsg(mtp, msg_no)
 |
 |		MSG_TBL	*mtp;		���b�Z�[�W�e�[�u���|�C���^
 |		int	msg_no;		���b�Z�[�W�ԍ�
 |
 |		�Ԓl			�T�u���b�Z�[�W
 |
 =======================================================================*/
static	char	*read_submsg(MSG_TBL *mtp, int msg_no)
{
	int	i;		/* ���[�v�J�E���^ */
	int	len;		/* ���b�Z�[�W�� */
	int	n_kahenbu;	/* �ϕ��� */
	char	*msg;		/* ���b�Z�[�W�|�C���^ */

	/* ���b�Z�[�W�ԍ����� */
	for (i = 0; i < mtp->n_msg; i++) {
		if (mtp->msg[i].msg_no == msg_no)
			break;
	}

	if (i == mtp->n_msg) {
		/* ���b�Z�[�W�������ꍇ */
		msg = talloc(1);
		len = 0;
	} else {
		/* ���b�Z�[�W���L�����ꍇ */
		fseek(mtp->fp, mtp->msg[i].offset + sizeof(int), 0);
		fread(&n_kahenbu, sizeof(int), 1, mtp->fp);
		fseek(mtp->fp, (long)(sizeof(KAHENBU) * n_kahenbu), 1);
		fread(&len, sizeof(int), 1, mtp->fp);
		msg = talloc(len + 1);
		fread(msg, 1, len, mtp->fp);
	}
	msg[len] = '\0';

	return(msg);
}