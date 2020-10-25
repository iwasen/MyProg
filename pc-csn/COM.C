/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �ʐM����				*
 *		�t�@�C����	: com.c					*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	�����֐�	*/
static	void	rs_puts(char *);

/*	�X�^�e�B�b�N�ϐ�	*/
static	int	_sig_ack;		/* �����M���i�e�X�g�p�j */

/*=======================================================================
 |
 |		�R�l�N�V�����J�ݏ���
 |
 |	int	conxxxt(flag)
 |
 |		int	flag;		�O�F�R�l�N�V�����J�݌�r�q���M����
 |					�P�F�@�@�@�@�@�V�@�@�@�@�@�@�@�L��
 |
 |		�Ԓl			�O�F�J�ݐ����@�P�F�J�ݎ��s
 |
 =======================================================================*/
int	conxxxt(flag)
{
	char	sig_cn[16];
	int	st, loop_sw;

	rs_open();		/* �q�r�|�Q�R�Q�b�I�[�v�� */

	/* �b�m�M�����M */
	sprintf(sig_cn, "%02d%s%02X\x0d", ssg.nsg.conxxxtion, password, SIG_CN);	rs_puts(sig_cn);

	_sig_ack = SIG_CNA;
	timset(1000);		/* �^�C�}�[�Z�b�g�i�P�O�b�j */

	loop_sw = 1;
	while (loop_sw) {
		switch (receive()) {
		case SIG_CNA:		/* �b�m�`��M */
			online = 1;	/* �I�����C���t���O�Z�b�g */
			if (flag == 0) {
				st = 0;
				loop_sw = 0;
			} else
				send_sig(SIG_SR);
				timset(1000);	/* �^�C�}�[�Z�b�g�i�P�O�b�j */
			break;
		case SIG_SRA:
			if (flag && online) {
				st = 0;
				loop_sw = 0;
			}
			break;
		case SIG_AB:		/* �`�a��M */
			/* �A�{�[�g�M����M���b�Z�[�W�o�� */
			ab_msg[15] = rcv_buf[4];
			enq_msg(MSG_ANM, ab_msg);
			st = 1;
			loop_sw = 0;
			break;
		case TIMEOUT:		/* ������ */
			/* �V�X�e�����������b�Z�[�W�o�� */
			dsp_loc_msg(LOC_MSG_NORESP);
			st = 1;
			loop_sw = 0;
			break;
		}
	}

	return(st);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v��
 |
 |	void	rs_open()
 |
 =======================================================================*/
void	rs_open()
{
	static	int	speed[4] = {7, 6, 5, 4};

	/* �q�r�|�Q�R�Q�b�I�[�v�� */
	/* �f�[�^���@�@�@�@�F�@�W������ */
	/* �p���e�B�@�@�@�@�F�@�d�u�d�m */
	/* �X�g�b�v�r�b�g�@�F�@�P������ */
	rs1_open(speed[csg.opt.rs232c], 0x7e, 0x37);
}

/*=======================================================================
 |
 |		�R�l�N�V�����ؒf����
 |
 |	void	disconxxxt()
 |
 =======================================================================*/
void	disconxxxt()
{
	disconxxxt_request = 1;		/* �R�l�N�V�����ؒf�v���t���O�Z�b�g */
}

/*=======================================================================
 |
 |		�M�����M����
 |
 |	void	send_sig(sig_type)
 |
 |		int	sig_type;	�M�����
 |
 =======================================================================*/
void	send_sig(int sig_type)
{
	char	sig[6];

	if (opt_t == 0) {
		sprintf(sig, "%02d%02X\x0d", ssg.nsg.conxxxtion, sig_type);
		rs_puts(sig);
	} else {
		if (sig_type == SIG_SR)
			_sig_ack = sig_type + 2;
		else
			_sig_ack = sig_type + 1;
	}

	timset(1000);		/* �^�C�}�[�Z�b�g�i�P�O�b�j */
}

/*=======================================================================
 |
 |		�f�[�^���M����
 |
 |	void	send_sd(data)
 |
 |		char	*data;		���M�f�[�^
 |
 =======================================================================*/
void	send_sd(char *data)
{
	char	sig[6];

	if (opt_t == 0) {
		sprintf(sig, "%02d%02X1", ssg.nsg.conxxxtion, SIG_SD);
		rs_puts(sig);
		rs_puts(data);
		rs1_put(0x0d);
	}
}

/*=======================================================================
 |
 |		�����񑗐M����
 |
 |	void	rs_puts(s)
 |
 |		char	*s;		���M������
 |
 =======================================================================*/
static	void	rs_puts(char *s)
{
	if (opt_t)
		return;

	while (*s)
		rs1_put(*s++);
}

/*=======================================================================
 |
 |		��M����
 |
 |	int	receive()
 |
 |		�Ԓl			��M�M�����
 |
 =======================================================================*/
int	receive()
{
	static	int	rcv_count = 0;
	static	char	*rcv_ptr = rcv_buf;
	char	con[3];
	int	c, sig;

	if (tm_ovf) {
		tm_ovf = 0;
		return(TIMEOUT);
	}

	if (opt_t) {
		sig = _sig_ack;
		_sig_ack = 0;
		return(sig);
	}

	sprintf(con, "%02d", ssg.nsg.conxxxtion);

	sig = 0;
	if (rs1_rxc() != 0) {
		c = rs1_get();
		if (c == 0x0d) {
			*rcv_ptr = '\0';
			if (strncmp(rcv_buf, con, 2) == 0) {
				sscanf(rcv_buf+2, "%2x", &sig);
			}
			rcv_count = 0;
			rcv_ptr = rcv_buf;
		} else {
			if (++rcv_count > 256) {
				rcv_count = 0;
				rcv_ptr = rcv_buf;
			} else {
				*rcv_ptr++ = c;
				rcv_count++;
			}
		}
	}
	return(sig);
}