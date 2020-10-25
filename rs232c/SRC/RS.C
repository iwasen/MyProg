/************************************************************************
 *									*
 *		�q�r�|�Q�R�Q�b���C�u����				*
 *									*
 *		����		: �q�r�|�Q�R�Q�b�@�S�`���l��		*
 *		�t�@�C����	: rs.c					*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<stddef.h>
#include	<stdlib.h>
#include	<dos.h>
#include	<conio.h>
#include	"rs232c.h"

#pragma	check_stack-		/* �X�^�b�N�`�F�b�N���� */

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v��
 |
 |	int	rsx_open(channel, rsprm)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		RSPRM	*rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |
 |		�Ԓl			�O�F����I�[�v��
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
int	rsx_open(int channel, RSPRM *rsprm)
{
	static	int	(*rs_func[3])(RSPRM *) = {
		rs1_open, rs2_open, rs3_open
	};

	return (*rs_func[channel])(rsprm);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�ăI�[�v��
 |
 |	int	rsx_reopen(channel, rsprm)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		RSPRM	*rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |
 |		�Ԓl			�O�F����I�[�v��
 |					�P�F���I�[�v��
 |					�Q�F�������[�h�p�����[�^�G���[
 |					�R�F��d���[�h�p�����[�^�G���[
 |					�S�F�{�[���[�g�p�����[�^�G���[
 |					�T�F�p���e�B�[�p�����[�^�G���[
 |					�U�F�L�����N�^���p�����[�^�G���[
 |					�V�F�X�g�b�v�r�b�g���p�����[�^�G���[
 |					�W�F�t���[����p�����[�^�G���[
 |
 =======================================================================*/
int	rsx_reopen(int channel, RSPRM *rsprm)
{
	static	int	(*rs_func[3])(RSPRM *) = {
		rs1_reopen, rs2_reopen, rs3_reopen
	};

	return (*rs_func[channel])(rsprm);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�N���[�Y
 |
 |	void	rsx_close(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 =======================================================================*/
void	rsx_close(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_close, rs2_close, rs3_close
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		��M�o�b�t�@�`�F�b�N
 |
 |	int	rsx_rxc(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl			��M������
 |
 =======================================================================*/
int	rsx_rxc(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_rxc, rs2_rxc, rs3_rxc
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		�P������M
 |
 |	int	rsx_get(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl			��M����
 |
 =======================================================================*/
int	rsx_get(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_get, rs2_get, rs3_get
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		��M�o�b�t�@�N���A
 |
 |	void	rsx_clear(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 =======================================================================*/
void	rsx_clear(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_clear, rs2_clear, rs3_clear
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		�P�������M
 |
 |	void	rsx_put(channel, c)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	c;		���M����
 |
 =======================================================================*/
void	rsx_put(int channel, int c)
{
	static	void	(*rs_func[3])(int) = {
		rs1_put, rs2_put, rs3_put
	};

	(*rs_func[channel])(c);
}

/*=======================================================================
 |
 |		�u���b�N���M
 |
 |	void	rsx_send(channel, s, len)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		char	*s;		���M������
 |		int	len;		������
 |
 =======================================================================*/
void	rsx_send(int channel, char *s, int len)
{
	static	void	(*rs_func[3])(char *, int) = {
		rs1_send, rs2_send, rs3_send
	};

	(*rs_func[channel])(s, len);
}

/*=======================================================================
 |
 |		�u���b�N���M�v��
 |
 |	int	rsx_sendreq(channel, s, len)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		char	*s;		���M������
 |		int	len;		������
 |
 |		�Ԓl			�O�F����@�P�F���M�o�b�t�@�m�ێ��s
 |
 =======================================================================*/
int	rsx_sendreq(int channel, char *s, int len)
{
	static	int	(*rs_func[3])(char *, int) = {
		rs1_sendreq, rs2_sendreq, rs3_sendreq
	};

	return (*rs_func[channel])(s, len);
}

/*=======================================================================
 |
 |		�f�[�^���M�����҂�
 |
 |	void	rsx_swait(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 =======================================================================*/
void	rsx_swait(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_swait, rs2_swait, rs3_swait
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		�t���[����ݒ�
 |
 |	void	rsx_flow(channel, ctrl)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	ctrl;		�t���[�������
 |
 =======================================================================*/
void	rsx_flow(int channel, int ctrl)
{
	static	void	(*rs_func[3])(int) = {
		rs1_flow, rs2_flow, rs3_flow
	};

	(*rs_func[channel])(ctrl);
}

/*=======================================================================
 |
 |		�o�`�c�����ݒ�
 |
 |	void	rsx_pad(channel, lpad, lpadsize, tpad, tpadsize)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		char	*lpad;		���[�f�B���O�p�b�h�f�[�^
 |		int	lpad_size;	���[�f�B���O�p�b�h�f�[�^��
 |		char	*tpad;		�g���[�����O�p�b�h�f�[�^
 |		int	tpadsize;	�g���[�����O�p�b�h�f�[�^��
 |
 =======================================================================*/
void	rsx_pad(int channel, char *lpad, int lpadsize, char *tpad, int tpadsize)
{
	static	void	(*rs_func[3])(char *, int, char *, int) = {
		rs1_pad, rs2_pad, rs3_pad
	};

	(*rs_func[channel])(lpad, lpadsize, tpad, tpadsize);
}

/*=======================================================================
 |
 |		��M���荞�ݏ����ݒ�
 |
 |	void	rsx_rcvint(channel, func)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	(*func)();	��M���荞�ݏ���
 |
 =======================================================================*/
void	rsx_rcvint(int channel, int (*func)(int))
{
	static	void	(*rs_func[3])(int (*func)(int)) = {
		rs1_rcvint, rs2_rcvint, rs3_rcvint
	};

	(*rs_func[channel])(func);
}

/*=======================================================================
 |
 |		�G���^�[�n���g���[�h�Z�b�g
 |
 |	void	rsx_ehm(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 =======================================================================*/
void	rsx_ehm(int channel)
{
	static	void	(*rs_func[3])(void) = {
		rs1_ehm, rs2_ehm, rs3_ehm
	};

	(*rs_func[channel])();
}

/*=======================================================================
 |
 |		�c�r�q�`�F�b�N
 |
 |	int	rsx_dsr(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl		�O�F�c�r�q�I�t�@�P�F�c�r�q�I��
 |
 =======================================================================*/
int	rsx_dsr(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_dsr, rs2_dsr, rs3_dsr
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		�L�����A�`�F�b�N
 |
 |	int	rsx_cd(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl		�O�F�L�����A�I�t�@�P�F�L�����A�I��
 |
 =======================================================================*/
int	rsx_cd(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_cd, rs2_cd, rs3_cd
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		�b�s�r�`�F�b�N
 |
 |	int	rsx_cts(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl		�O�F�b�s�r�I�t�@�P�F�b�s�r�I��
 |
 =======================================================================*/
int	rsx_cts(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_cts, rs2_cts, rs3_cts
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		�b�h�`�F�b�N
 |
 |	int	rsx_ci(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl		�O�F�b�h�I�t�@�P�F�b�h�I��
 |
 =======================================================================*/
int	rsx_ci(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_ci, rs2_ci, rs3_ci
	};

	return (*rs_func[channel])();
}

/*=======================================================================
 |
 |		�c�s�q����
 |
 |	void	rsx_dtr(channel, sw);
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	sw;		�O�F�c�s�q�I�t�@���O�F�c�s�q�I��
 |
 =======================================================================*/
void	rsx_dtr(int channel, int sw)
{
	static	void	(*rs_func[3])(int) = {
		rs1_dtr, rs2_dtr, rs3_dtr
	};

	(*rs_func[channel])(sw);
}

/*=======================================================================
 |
 |		�q�s�r����
 |
 |	void	rsx_rts(channel, sw);
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	sw;		�O�F�q�s�r�I�t�@���O�F�q�s�r�I��
 |
 =======================================================================*/
void	rsx_rts(int channel, int sw)
{
	static	void	(*rs_func[3])(int) = {
		rs1_rts, rs2_rts, rs3_rts
	};

	(*rs_func[channel])(sw);
}

/*=======================================================================
 |
 |		�u���[�N�M�����o
 |
 |	void	rsx_break(channel, sw);
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	sw;		  �O�F�u���[�N�M���I�t
 |					���O�F�u���[�N�M���I��
 |
 =======================================================================*/
void	rsx_break(int channel, int sw)
{
	static	void	(*rs_func[3])(int) = {
		rs1_break, rs2_break, rs3_break
	};

	(*rs_func[channel])(sw);
}

/*=======================================================================
 |
 |		�R�}���h�o��
 |
 |	void	rsx_cmd(channel, cmd, mask)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |		int	cmd;		�R�}���h
 |		int	mask;		�}�X�N�f�[�^
 |
 =======================================================================*/
void	rsx_cmd(int channel, int cmd, int mask)
{
	static	void	(*rs_func[3])(int, int) = {
		rs1_cmd, rs2_cmd, rs3_cmd
	};

	(*rs_func[channel])(cmd, mask);
}

/*=======================================================================
 |
 |		�X�e�[�^�X�ǂݍ���
 |
 |	int	rsx_sts(channel)
 |
 |		int	channel;	�`���l���ԍ��i�P�`�R�j
 |
 |		�Ԓl			�X�e�[�^�X
 |
 =======================================================================*/
int	rsx_sts(int channel)
{
	static	int	(*rs_func[3])(void) = {
		rs1_sts, rs2_sts, rs3_sts
	};

	return (*rs_func[channel])();
}