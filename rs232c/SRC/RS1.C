/************************************************************************
 *									*
 *		�q�r�|�Q�R�Q�b���C�u����				*
 *									*
 *		����		: �q�r�|�Q�R�Q�b�@�b�g�P����		*
 *		�t�@�C����	: rs1.c					*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<stddef.h>
#include	<stdlib.h>
#include	<string.h>
#include	<dos.h>
#include	<conio.h>
#include	"rs232c.h"

/*	���[�N�G���A	*/
u_char	rs1_cmdreg;			/* �R�}���h���W�X�^ */
static	int	open_flag;		/* �I�[�v���t���O */
static	int	duplex;			/* ��d���[�h */
static	int	*rbufp;			/* ��M�o�b�t�@�|�C���^ */
static	u_int	rbufsize;		/* ��M�o�b�t�@�� */
static	int	alloc_flag;		/* ��M�o�b�t�@�A���P�[�V�����t���O */
static	u_int	read_ptr;		/* ��M�o�b�t�@�ǂݍ��݃|�C���^ */
static	u_int	write_ptr;		/* ��M�o�b�t�@�������݃|�C���^ */
static	u_int	rcv_count;		/* ��M������ */
static	int	flow_ctrl;		/* �t���[������� */
static	u_int	flow_off;		/* ��M�҂����킹�J�n�o�b�t�@�T�C�Y */
static	u_int	flow_on;		/* ��M�҂����킹�����o�b�t�@�T�C�Y */
static	int	flow_rflag;		/* ��M�t���[����t���O */
static	int	flow_sflag;		/* ���M�t���[����t���O */
static	char	*send_buf;		/* ���M�o�b�t�@�擪�|�C���^ */
static	char	*send_ptr;		/* ���M�o�b�t�@�J�����g�|�C���^ */
static	int	send_len;		/* ���M�f�[�^�� */
static	char	l_pad[10];		/* ���[�f�B���O�p�b�h�f�[�^ */
static	int	l_pad_size;		/* ���[�f�B���O�p�b�h�f�[�^�� */
static	char	t_pad[10];		/* �g���[�����O�p�b�h�f�[�^ */
static	int	t_pad_size;		/* �g���[�����O�p�b�h�f�[�^�� */
static	int	(*rs1_rcvfunc)(int);	/* ���[�U��M���荞�݊֐��|�C���^ */
static	void	(far interrupt *save_vect)();	/* ���荞�݃x�N�^�ۑ��G���A */
static	u_int	blt_tbl1[] = {	/* �{�[���[�g�J�E���^�i�P�O�l�g���j */
	0x8000, 0x4000, 0x2000, 0x1000, 0x800, 0x400, 0x200, 0x100, 0x80
};
static	u_int	blt_tbl2[] = {	/* �{�[���[�g�J�E���^�i�W�l�g���j */
	0x6800, 0x3400, 0x1a00, 0xd00, 0x680, 0x340, 0x1a0, 0xd0, 0x68
};

/*	�����֐�	*/
static	int	rs1_prmcheck(RSPRM *, u_int *, u_int *);
static	void	interrupt far	rs1_int(void);
static	void	outp2(int, int);

#pragma	check_stack-		/* �X�^�b�N�`�F�b�N���� */

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v��
 |
 |	int	rs1_open(rsprm)
 |
 |		RSPRM	*rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |
 |		�Ԓl			�O�F����
 |					�P�F�I�[�v���ς�
 |					�Q�F�������[�h�p�����[�^�G���[
 |					�R�F��d���[�h�p�����[�^�G���[
 |					�S�F�{�[���[�g�p�����[�^�G���[
 |					�T�F�p���e�B�[�p�����[�^�G���[
 |					�U�F�L�����N�^���p�����[�^�G���[
 |					�V�F�X�g�b�v�r�b�g���p�����[�^�G���[
 |					�W�F�t���[����p�����[�^�G���[
 |					�X�F�c�s�q����p�����[�^�G���[
 |					10�F��M�o�b�t�@�m�ێ��s
 |
 =======================================================================*/
int	rs1_open(RSPRM *rsprm)
{
	u_char	far	*fptr;
	union	{
		u_int	i;
		u_char	c[2];
	} cnt;
	u_int	mode, cmd;
	int	err;

	/* �I�[�v���ς݃`�F�b�N */
	if (open_flag != 0)
		return(1);

	/* �I�[�v���p�����[�^�`�F�b�N */
	if ((err = rs1_prmcheck(rsprm, &mode, &cmd)) != 0)
		return(err);

	/* ��M�o�b�t�@�|�C���^�A��M�o�b�t�@���Z�[�u */
	rbufp = (int *)rsprm->bufp;
	rbufsize = rsprm->buf_size / sizeof(int);

	/* ��M�o�b�t�@�m�� */
	if (rbufp == NULL) {
		if ((rbufp = malloc(rsprm->buf_size)) == NULL)
			return(10);
		alloc_flag = 1;
	} else
		alloc_flag = 0;

	_disable();		/* ���荞�݋֎~ */

	/* �{�[���[�g�ݒ� */
	FP_SEG(fptr) = 0;
	FP_OFF(fptr) = 0x501;
	cnt.i = ((*fptr & 0x80) ? blt_tbl2 : blt_tbl1)[rsprm->blt];
	if (rsprm->sync_mode == ASYNC)
		cnt.i /= 16;
	outp2(IOP_TMC_CWW, 0xb6);
	outp2(IOP_TMC_C2L, cnt.c[0]);
	outp2(IOP_TMC_C2L, cnt.c[1]);

	/* �q�r�|�Q�R�Q�b���荞�݃}�X�N */
	outp2(IOP_SYS_WRT, inp(IOP_SYS_RD) & 0xf8);

	/* �W�Q�T�P�`�n�[�h�E�F�A���Z�b�g */
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0x40);

	/* ���[�h���W�X�^�Z�b�g */
	outp2(IOP_RS1_MOD, mode);

	/* �����L�����N�^�Z�b�g */
	if (rsprm->sync_mode == SYNC) {
		outp2(IOP_RS1_SYN, rsprm->syn_chr);
		if ((mode & 0x80) == 0)
			outp2(IOP_RS1_SYN, rsprm->syn_chr >> 8);
	}

	/* �R�}���h�Z�b�g */
	rs1_cmdreg = cmd;
	outp2(IOP_RS1_MOD, rs1_cmdreg | CMD_ER | CMD_EH);

	/* �|�C���^���J�E���^������ */
	read_ptr = write_ptr = rcv_count = 0;

	/* ���荞�݃x�N�^�ۑ� */
	save_vect = _dos_getvect(0x0c);

	/* ���荞�݃x�N�^�Z�b�g */
	_dos_setvect(0x0c, rs1_int);

	/* ���荞�݃}�X�N���� */
	outp2(IOP_ICM_IMR, inp(IOP_ICM_IMR) & 0xef);
	outp2(IOP_SYS_SET, 0x01);

	_enable();		/* ���荞�݋��� */

	open_flag = 1;		/* �I�[�v�����t���O�Z�b�g */

	return(0);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�ăI�[�v��
 |
 |	int	rs1_reopen(rsprm)
 |
 |		RSPRM	*rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |
 |		�Ԓl			�O�F����
 |					�P�F���I�[�v��
 |					�Q�F�������[�h�p�����[�^�G���[
 |					�R�F��d���[�h�p�����[�^�G���[
 |					�S�F�{�[���[�g�p�����[�^�G���[
 |					�T�F�p���e�B�[�p�����[�^�G���[
 |					�U�F�L�����N�^���p�����[�^�G���[
 |					�V�F�X�g�b�v�r�b�g���p�����[�^�G���[
 |					�W�F�t���[����p�����[�^�G���[
 |					�X�F�c�s�q����p�����[�^�G���[
 |
 =======================================================================*/
int	rs1_reopen(RSPRM *rsprm)
{
	u_char	far	*fptr;
	union	{
		u_int	i;
		u_char	c[2];
	} cnt;
	u_int	mode, cmd;
	int	err;
	int	sync1_flag, sync1, sync2_flag, sync2;

	/* �I�[�v���ς݃`�F�b�N */
	if (open_flag == 0)
		return(1);

	/* �I�[�v���p�����[�^�`�F�b�N */
	if ((err = rs1_prmcheck(rsprm, &mode, &cmd)) != 0)
		return(err);

	_disable();		/* ���荞�݋֎~ */

	/* �{�[���[�g�ݒ� */
	FP_SEG(fptr) = 0;
	FP_OFF(fptr) = 0x501;
	cnt.i = ((*fptr & 0x80) ? blt_tbl2 : blt_tbl1)[rsprm->blt];
	if (rsprm->sync_mode == ASYNC)
		cnt.i /= 16;
	outp2(IOP_TMC_CWW, 0xb6);
	outp2(IOP_TMC_C2L, cnt.c[0]);
	outp2(IOP_TMC_C2L, cnt.c[1]);

	/* �q�r�|�Q�R�Q�b���荞�݃}�X�N */
	outp2(IOP_SYS_WRT, inp(IOP_SYS_RD) & 0xf8);

	/* �����L�����N�^�`�F�b�N */
	sync1_flag = sync2_flag = 0;
	if (rsprm->sync_mode == SYNC) {
		sync1_flag = 1;
		sync1 = rsprm->syn_chr;
		if ((mode & 0x80) == 0) {
			sync2_flag = 1;
			sync2 = rsprm->syn_chr >> 8;
		}
	}

	/* �W�Q�T�P�`�n�[�h�E�F�A���Z�b�g */
	outp2(IOP_RS1_MOD, 0x40);

	/* ���[�h���W�X�^�Z�b�g */
	outp2(IOP_RS1_MOD, mode);

	/* �����L�����N�^�Z�b�g */
	if (sync1_flag) {
		(void)outp(IOP_RS1_SYN, sync1);
		if (sync2_flag)
			(void)outp(IOP_RS1_SYN, sync2);
	}

	/* �R�}���h�Z�b�g */
	rs1_cmdreg = cmd;
	(void)outp(IOP_RS1_MOD, rs1_cmdreg | CMD_ER | CMD_EH);

	/* �|�C���^���J�E���^������ */
	read_ptr = write_ptr = rcv_count = 0;

	/* ���荞�݃}�X�N���� */
	outp2(IOP_SYS_SET, 0x01);

	_enable();		/* ���荞�݋��� */

	return(0);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^�`�F�b�N
 |
 |	int	rs1_prmcheck(rsprm, mode, cmd)
 |
 |		RSPRM	*rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |		u_int	*mode;		�W�Q�T�P�`���[�h
 |		u_int	*cmd;		�W�Q�T�P�`�R�}���h
 |
 |		�Ԓl			�O�F����p�����[�^
 |					�Q�F�������[�h�p�����[�^�G���[
 |					�R�F��d���[�h�p�����[�^�G���[
 |					�S�F�{�[���[�g�p�����[�^�G���[
 |					�T�F�p���e�B�[�p�����[�^�G���[
 |					�U�F�L�����N�^���p�����[�^�G���[
 |					�V�F�X�g�b�v�r�b�g���p�����[�^�G���[
 |					�W�F�t���[����p�����[�^�G���[
 |					�X�F�c�s�q����p�����[�^�G���[
 |
 =======================================================================*/
static	int	rs1_prmcheck(RSPRM *rsprm, u_int *mode, u_int *cmd)
{
	/* ���[�h�A�R�}���h������ */
	*mode = 0;
	*cmd = 0;

	/* �������[�h�p�����[�^�`�F�b�N */
	switch (rsprm->sync_mode) {
	case SYNC:		/* �������[�h */
		break;
	case ASYNC:		/* �񓯊����[�h */
		*mode |= 0x02;
		break;
	default:		/* �p�����[�^�G���[ */
		return(2);
	}

	/* ��d���[�h�p�����[�^�`�F�b�N */
	switch (duplex = rsprm->duplex) {
	case DUPLEX_FULL:	/* �S��d */
		*cmd |= CMD_RXE | CMD_TXE | CMD_RTS;
		break;
	case DUPLEX_HALF:	/* ����d */
		*cmd |= CMD_RXE;
		break;
	default:
		return(3);	/* �p�����[�^�G���[ */
	}

	/* �{�[���[�g�`�F�b�N */
	if (rsprm->blt < BLT_75 || rsprm->blt > BLT_19200)
		return(4);

	/* �p���e�B�[�`�F�b�N */
	switch (rsprm->parity) {
	case PARITY_NONE:	/* �p���e�B�[���� */
	case PARITY_EVEN:	/* �����p���e�B�[ */
	case PARITY_ODD:	/* ��p���e�B�[ */
		*mode |= rsprm->parity;
		break;
	default:		/* �p�����[�^�G���[ */
		return(5);
	}

	/* �L�����N�^���`�F�b�N */
	switch (rsprm->chr_len) {
	case CHR_5:		/* �T�r�b�g */
	case CHR_6:		/* �U�r�b�g */
	case CHR_7:		/* �V�r�b�g */
	case CHR_8:		/* �W�r�b�g */
		*mode |= rsprm->chr_len;
		break;
	default:		/* �p�����[�^�G���[ */
		return(6);
	}

	if (rsprm->sync_mode == ASYNC) {
		/* �X�g�b�v�r�b�g���`�F�b�N */
		switch (rsprm->stp_len) {
		case STP_1:	/* �P�r�b�g */
		case STP_1_5:	/* �P�D�T�r�b�g */
		case STP_2:	/* �Q�r�b�g */
			*mode |= rsprm->stp_len;
			break;
		default:	/* �p�����[�^�G���[ */
			return(7);
		}
	} else {
		/* �����L�����N�^�`�F�b�N */
		if ((rsprm->syn_chr & 0xff00) == 0)
			*mode |= 0x80;
	}

	/* �t���[����`�F�b�N */
	switch (rsprm->flow_ctrl) {
	case FLOW_NONE:		/* �t���[���䖳�� */
	case FLOW_SOFT:		/* �w�n�m�^�w�n�e�e���� */
	case FLOW_HARD:		/* �q�s�r�^�b�s�r���� */
		flow_ctrl = rsprm->flow_ctrl;
		flow_on = rsprm->buf_size / 8;
		flow_off = rsprm->buf_size / 8 * 3;
		flow_rflag = 0;
		flow_sflag = 0;
		break;
	default:		/* �p�����[�^�G���[ */
		return(8);
	}

	/* �c�s�q����`�F�b�N */
	switch (rsprm->dtr) {
	case DTR_OFF:
		break;
	case DTR_ON:
		*cmd |= CMD_DTR;
		break;
	default:		/* �p�����[�^�G���[ */
		return(9);
	}

	return(0);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�N���[�Y
 |
 |	void	rs1_close()
 |
 =======================================================================*/
void	rs1_close(void)
{
	/* ���I�[�v���Ȃ牽�����Ȃ� */
	if (open_flag == 0)
		return;

	_disable();		/* ���荞�݋֎~ */

	/* �n�[�h�E�F�A���Z�b�g */
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0);
	outp2(IOP_RS1_MOD, 0x40);

	/* ���荞�݃}�X�N */
	outp2(IOP_ICM_IMR, inp(IOP_ICM_IMR) | 0x10);
	outp2(IOP_SYS_WRT, inp(IOP_SYS_RD) & 0xf8);

	/* ���荞�݃x�N�^���A */
	_dos_setvect(0x0c, save_vect);

	_enable();		/* ���荞�݋��� */

	/* ��M�o�b�t�@���m�ۂ����ꍇ�͉������ */
	if (alloc_flag != 0)
		free(rbufp);

	rs1_cmdreg = 0;
	open_flag = 0;		/* �I�[�v�����t���O���Z�b�g */
}

/*=======================================================================
 |
 |		��M�o�b�t�@�`�F�b�N
 |
 |	int	rs1_rxc()
 |
 |		�Ԓl		��M������
 |
 =======================================================================*/
int	rs1_rxc(void)
{
	return(rcv_count);
}

/*=======================================================================
 |
 |		�P������M
 |
 |	int	rs1_get()
 |
 |		�Ԓl		��M����
 |
 =======================================================================*/
int	rs1_get(void)
{
	int	c;

	/* ��M�� */
	while (rcv_count == 0)
		;

	_disable();		/* ���荞�݋֎~ */

	/* �P�������o�� */
	c = rbufp[read_ptr++];
	if (read_ptr == rbufsize)
		read_ptr = 0;
	rcv_count--;

	/* �t���[���� */
	switch (flow_ctrl) {
	case FLOW_SOFT:		/* �w�n�m�^�w�n�e�e���� */
		if (flow_rflag != 0 && rcv_count < flow_on) {
			outp2(IOP_RS1_DAT, XON);	/* �w�n�m���M */
			flow_rflag = 0;
		}
		break;
	case FLOW_HARD:		/* �q�s�r�^�b�s�r���� */
		if (flow_rflag != 0 && rcv_count < flow_on) {
			rs1_cmd(CMD_RTS, CMD_RTS);	/* �q�s�r�I�� */
			flow_rflag = 0;
		}
		break;
	}

	_enable();		/* ���荞�݋��� */

	return(c);
}

/*=======================================================================
 |
 |		��M�o�b�t�@�N���A
 |
 |	void	rs1_clear()
 |
 =======================================================================*/
void	rs1_clear(void)
{
	_disable();		/* ���荞�݋֎~ */

	/* �|�C���^���J�E���^������ */
	read_ptr = write_ptr = rcv_count = 0;

	_enable();		/* ���荞�݋��� */
}

/*=======================================================================
 |
 |		�P�������M
 |
 |	void	rs1_put(c)
 |
 |		int	c;		���M����
 |
 =======================================================================*/
void	rs1_put(int c)
{
	/* �t���[����ɂ�鑗�M�҂����킹 */
	while (flow_sflag != 0)
		;

	/* ���M�� */
	while ((inp(IOP_RS1_STS) & STS_TXRDY) == 0)
		;

	/* �P�������M */
	outp2(IOP_RS1_DAT, c);
}

/*=======================================================================
 |
 |		�u���b�N���M
 |
 |	void	rs1_send(s, len)
 |
 |		char	*s;		���M������
 |		int	len;		������
 |
 =======================================================================*/
void	rs1_send(char *s, int len)
{
	int	i;

	if (len == 0)
		return;

	if (duplex == DUPLEX_HALF) {
		/* ���M�A��M�֎~�A�q�s�r�I�� */
		rs1_cmd(CMD_TXE|CMD_RTS, CMD_TXE|CMD_RXE|CMD_RTS);
	}

	/* ���[�f�B���O�o�`�c���M */
	for (i = 0; i < l_pad_size; i++)
		rs1_put(l_pad[i]);

	/* �f�[�^���M */
	while (len--)
		rs1_put(*s++);

	/* �g���[�����O�o�`�c���M */
	for (i = 0; i < t_pad_size; i++)
		rs1_put(t_pad[i]);

	if (duplex == DUPLEX_HALF) {
		rs1_cmd(0, CMD_TXE);	/* ���M�֎~ */

		/* ���M�I����҂� */
		while ((inp(IOP_RS1_STS) & STS_TXE) == 0)
			;
		/* ��M�A�q�s�r�I�t */
		rs1_cmd(CMD_RXE, CMD_RXE|CMD_RTS);
	}
}

/*=======================================================================
 |
 |		�u���b�N���M�v��
 |
 |	int	rs1_sendreq(s, len)
 |
 |		char	*s;		���M������
 |		int	len;		������
 |
 |		�Ԓl			�O�F����@�P�F���M�o�b�t�@�m�ێ��s
 |
 =======================================================================*/
int	rs1_sendreq(char *s, int len)
{
	int	data_size;

	if (len == 0)
		return(0);

	/* ���݃f�[�^���M���Ȃ瑗�M�I���܂ő҂� */
	while (send_len != 0)
		;

	/* �f�[�^�T�C�Y�v�Z */
	data_size = l_pad_size + len + t_pad_size;

	/* ���M�f�[�^�o�b�t�@�m�� */
	if ((send_buf = malloc(data_size)) == NULL)
		return(1);

	/* ���M�f�[�^�o�b�t�@�Z�b�g */
	memcpy(send_buf, l_pad, l_pad_size);
	memcpy(send_buf+l_pad_size, s, len);
	memcpy(send_buf+l_pad_size+len, t_pad, t_pad_size);

	/* ���M�f�[�^���A���M�f�[�^�o�b�t�@�|�C���^�Z�b�g */
	send_len = data_size;
	send_ptr = send_buf;

	if (duplex == DUPLEX_HALF){
		/* ���M�A��M�֎~�A�q�s�r�I�� */
		rs1_cmd(CMD_TXE|CMD_RXE|CMD_RTS, CMD_TXE|CMD_RTS);
	}

	outp2(IOP_SYS_SET, 0x05);	/* ���M���荞�݋��� */

	return(0);
}

/*=======================================================================
 |
 |		�f�[�^���M�����҂�
 |
 |	void	rs1_swait()
 |
 =======================================================================*/
void	rs1_swait(void)
{
	/* ���݃f�[�^���M���Ȃ瑗�M�I���܂ő҂� */
	while (send_len != 0)
		;

	/* ���M�I����҂� */
	while ((inp(IOP_RS1_STS) & STS_TXE) == 0)
		;
}

/*=======================================================================
 |
 |		�t���[����ݒ�
 |
 |	void	rs1_flow(ctrl)
 |
 |		int	ctrl;		�t���[�������
 |
 =======================================================================*/
void	rs1_flow(int ctrl)
{
	flow_ctrl = ctrl;
}

/*=======================================================================
 |
 |		�o�`�c�����ݒ�
 |
 |	void	rs1_pad(lpad, lpadsize, tpad, tpadsize)
 |
 |		char	*lpad;		���[�f�B���O�p�b�h�f�[�^
 |		int	lpad_size;	���[�f�B���O�p�b�h�f�[�^��
 |		char	*tpad;		�g���[�����O�p�b�h�f�[�^
 |		int	tpadsize;	�g���[�����O�p�b�h�f�[�^��
 |
 =======================================================================*/
void	rs1_pad(char *lpad, int lpadsize, char *tpad, int tpadsize)
{
	memcpy(l_pad, lpad, lpadsize);
	l_pad_size = lpadsize;
	memcpy(t_pad, tpad, tpadsize);
	t_pad_size = tpadsize;
}

/*=======================================================================
 |
 |		��M���荞�ݏ����ݒ�
 |
 |	void	rs1_rcvint(func)
 |
 |		int	(*func)();	��M���荞�ݏ���
 |
 =======================================================================*/
void	rs1_rcvint(int (*func)(int))
{
	rs1_rcvfunc = func;
}

/*=======================================================================
 |
 |		�G���^�[�n���g���[�h�Z�b�g
 |
 |	void	rs1_ehm()
 |
 =======================================================================*/
void	rs1_ehm(void)
{
	outp2(IOP_RS1_MOD, rs1_cmdreg | 0x80);
}

/*=======================================================================
 |
 |		�c�r�q�`�F�b�N
 |
 |	int	rs1_dsr()
 |
 |		�Ԓl		�O�F�c�r�q�I�t�@�P�F�c�r�q�I��
 |
 =======================================================================*/
int	rs1_dsr(void)
{
	return(inp(IOP_RS1_STS) & STS_DSR ? 1 : 0);
}

/*=======================================================================
 |
 |		�L�����A�`�F�b�N
 |
 |	int	rs1_cd()
 |
 |		�Ԓl		�O�F�L�����A�I�t�@�P�F�L�����A�I��
 |
 =======================================================================*/
int	rs1_cd(void)
{
	return(inp(IOP_SYS_SGN) & SGN_CD ? 0 : 1);
}

/*=======================================================================
 |
 |		�b�s�r�`�F�b�N
 |
 |	int	rs1_cts()
 |
 |		�Ԓl		�O�F�b�s�r�I�t�@�P�F�b�s�r�I��
 |
 =======================================================================*/
int	rs1_cts(void)
{
	return(inp(IOP_SYS_SGN) & SGN_CS ? 0 : 1);
}

/*=======================================================================
 |
 |		�b�h�`�F�b�N
 |
 |	int	rs1_ci()
 |
 |		�Ԓl		�O�F�b�h�I�t�@�P�F�b�h�I��
 |
 =======================================================================*/
int	rs1_ci(void)
{
	return(inp(IOP_SYS_SGN) & SGN_CI ? 0 : 1);
}

/*=======================================================================
 |
 |		�c�s�q����
 |
 |	void	rs1_dtr(sw);
 |
 |		int	sw;		�O�F�c�s�q�I�t�@���O�F�c�s�q�I��
 |
 =======================================================================*/
void	rs1_dtr(int sw)
{
	_disable();

	if (sw == 0)
		rs1_cmdreg &= ~CMD_DTR;
	else
		rs1_cmdreg |= CMD_DTR;

	outp2(IOP_RS1_MOD, rs1_cmdreg);

	_enable();
}

/*=======================================================================
 |
 |		�q�s�r����
 |
 |	void	rs1_rts(sw);
 |
 |		int	sw;		�O�F�q�s�r�I�t�@���O�F�q�s�r�I��
 |
 =======================================================================*/
void	rs1_rts(int sw)
{
	_disable();

	if (sw == 0)
		rs1_cmdreg &= ~CMD_RTS;
	else
		rs1_cmdreg |= CMD_RTS;

	outp2(IOP_RS1_MOD, rs1_cmdreg);

	_enable();
}

/*=======================================================================
 |
 |		�u���[�N�M�����o
 |
 |	void	rs1_break(sw);
 |
 |		int	sw;		  �O�F�u���[�N�M���I�t
 |					���O�F�u���[�N�M���I��
 |
 =======================================================================*/
void	rs1_break(int sw)
{
	_disable();

	if (sw == 0)
		rs1_cmdreg &= ~CMD_SBRK;
	else
		rs1_cmdreg |= CMD_SBRK;

	outp2(IOP_RS1_MOD, rs1_cmdreg);

	_enable();
}

/*=======================================================================
 |
 |		�R�}���h�o��
 |
 |	void	rs1_cmd(cmd, mask)
 |
 |		int	cmd;		�R�}���h
 |		int	mask;		�}�X�N�f�[�^
 |
 =======================================================================*/
void	rs1_cmd(int cmd, int mask)
{
	_disable();
	rs1_cmdreg = (cmd & mask) | (rs1_cmdreg & ~mask);
	outp2(IOP_RS1_MOD, rs1_cmdreg);
	_enable();
}

/*=======================================================================
 |
 |		�X�e�[�^�X�ǂݍ���
 |
 |	int	rs1_sts()
 |
 |		�Ԓl			�X�e�[�^�X
 |
 =======================================================================*/
int	rs1_sts(void)
{
	return(inp(IOP_RS1_STS));
}

/*=======================================================================
 |
 |		���荞�ݏ���
 |
 |	void	interrupt far	rs1_int()
 |
 =======================================================================*/
static	void	interrupt far	rs1_int(void)
{
	int	c, sts, sys_flag;

	sts = inp(IOP_RS1_STS);		/* �X�e�[�^�X�ǂݍ��� */

	/* ��M���荞�ݏ��� */
	if (sts & STS_RXRDY) {
		/* ��M�����ǂݍ��� */
		c = inp(IOP_RS1_DAT);

		/* �X�e�[�^�X�ǂݍ��� */
		if (sts & 0x38) {
			/* �G���[���Z�b�g */
			outp2(IOP_RS1_MOD, rs1_cmdreg | 0x10);
			c |= (sts & 0x38) << 8;
		}

		/* ���[�U��M���荞�݊֐��Ăяo�� */
		if (rs1_rcvfunc != NULL)
			c = (*rs1_rcvfunc)(c);

		if (c == -1)
			;
		else if (flow_ctrl == FLOW_SOFT && c == XOFF) {
			flow_sflag = 1;
			if (send_len != 0) {
				/* ���M�֎~ */
				outp2(IOP_RS1_MOD, rs1_cmdreg &= ~CMD_TXE);
			}
		} else if (flow_ctrl == FLOW_SOFT && c == XON) {
			flow_sflag = 0;
			if (send_len != 0) {
				/* ���M�� */
				outp2(IOP_RS1_MOD, rs1_cmdreg |= CMD_TXE);
			}
		} else if (rcv_count < rbufsize) {
			/* ��M�������o�b�t�@�ɃZ�b�g */
			rbufp[write_ptr++] = c;

			/* �������݃|�C���^�̍X�V */
			if (write_ptr == rbufsize)
				write_ptr = 0;

			/* ��M�������X�V */
			rcv_count++;

			/* �t���[���� */
			switch (flow_ctrl) {
			case FLOW_SOFT:
				if (flow_rflag == 0 && rcv_count > flow_off) {
					/* �w�n�e�e���M */
					if (sts & STS_TXRDY) {
						outp2(IOP_RS1_DAT, XOFF);
						flow_rflag = 1;
					}
				}
				break;
			case FLOW_HARD:
				if (flow_rflag == 0 && rcv_count > flow_off) {
					/* �q�s�r�I�t */
					outp2(IOP_RS1_MOD, rs1_cmdreg &= ~CMD_RTS);
					flow_rflag = 1;
				}
				break;
			}
		}
	}

	/* �d�l�o�s�x���荞�ݏ��� */
	if ((inp(IOP_SYS_RD) & IMR_TXEE) && (sts & STS_TXE)) {
		outp2(IOP_SYS_SET, 0x02);	/* �d�l�o�s�x���荞�݋֎~ */
		if (duplex == DUPLEX_HALF) {
			/* ��M�A�q�s�r�I�t */
			rs1_cmdreg = rs1_cmdreg & ~(CMD_RXE|CMD_RTS) | CMD_RXE;
			outp2(IOP_RS1_MOD, rs1_cmdreg);
		}
	}

	/* ���M���荞�ݏ��� */
	if (send_len != 0 && (sts & STS_TXRDY)) {
		outp2(IOP_RS1_DAT, *send_ptr++);		/* ���̂P�������M */
		if (--send_len == 0) {
			if (duplex == DUPLEX_HALF) {
				/* ���M�֎~ */
				outp2(IOP_RS1_MOD, rs1_cmdreg &= ~CMD_TXE);
			}
			outp2(IOP_SYS_SET, 0x04); /* ���M���荞�݋֎~ */
			outp2(IOP_SYS_SET, 0x03); /* �d�l�o�s�x���荞�݋��� */
			free(send_buf);		/* ���M�o�b�t�@��� */
		}
	}

	/* ���荞�݃}�X�N */
	sys_flag = inp(IOP_SYS_RD);
	outp2(IOP_SYS_WRT, sys_flag & 0xf8);

	/* ���荞�ݏI���ʒm */
	outp2(IOP_ICM_EOI, 0x20);

	/* ���荞�݃}�X�N���� */
	outp2(0x35, sys_flag);
}

/*=======================================================================
 |
 |		�|�[�g��������
 |
 |	void	outp2(int port, int databyte)
 |
 |		int	port;		�|�[�g�ԍ�
 |		int	databyte;	�o�͂���l
 |
 =======================================================================*/
static	void	outp2(int port, int databyte)
{
	(void)outp(port, databyte);
}