/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �w�l�n�c�d�l����M����		*
 *		�t�@�C����	: xmodem.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include "rs232c.h"
#include "yterm.h"

#define	ST_END		0
#define	ST_ERR		-1
#define	ST_ABORT	-2
#define	ST_1		1
#define	ST_2		2
#define	ST_3		3
#define	SOH		0x01
#define	EOT		0x04
#define	ACK		0x06
#define	NAK		0x15
#define	CAN		0x18
#define	TIMEOUT		0x101
#define	ABORT		0x102

/*	�����֐�	*/
static	int s_start(void);
static	int s_st1(int);
static	int s_st2(int);
static	int s_st3(int);
static	int send_dat(void);
static	int r_start(void);
static	int r_st1(int);
static	int r_st2(int);
static	int rcv_dat(void);
static	int event(void);

/*	�����ϐ�	*/
static	u_int	blkno;
static	FILE	*sfp;
static	WINDOW	*swp;
static	u_char	rcv_buf[132];
static	int	retry_counter;
static	int	rcv_counter;
static	long	save_fp;

/*=======================================================================
 |
 |		�t�@�C�����M����
 |
 |	int	x_send(fp, wp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 |		�Ԓl			�@�O�F����I��
 |					�|�P�F���M���s
 |					�|�Q�F���~
 |
 =======================================================================*/
int	x_send(FILE *fp, WINDOW *wp)
{
	int	st, ev;

	sfp = fp;		/* ���M�t�@�C���|�C���^�ۑ� */
	swp = wp;		/* �E�B���h�E�|�C���^�ۑ� */
	rs1_flow(FLOW_NONE);	/* �t���[���䖳�� */
	rs1_clear();		/* ��M�o�b�t�@�N���A */

	st = s_start();		/* ���M�J�n���� */
	while (st > 0) {
		ev = event();	/* �C�x���g�҂� */
		switch (st) {
		case ST_1:
			st = s_st1(ev);
			break;
		case ST_2:
			st = s_st2(ev);
			break;
		case ST_3:
			st = s_st3(ev);
			break;
		}
	}
	tm_stop(0);		/* �^�C�}�[��~ */

	if (cprm.xon)
		rs1_flow(FLOW_SOFT);	/* �t���[����ݒ� */

	return(st);
}

/*=======================================================================
 |
 |		�t�@�C�����M�J�n����
 |
 |	int	s_start()
 |
 |		�Ԓl			�r�s�Q�P
 |
 =======================================================================*/
static	int	s_start(void)
{
	tm_set(0, 6000);		/* �m�`�j�҂��^�C�}�[�Z�b�g */
	blkno = 0;			/* �u���b�N�ԍ��N���A */

	return(ST_1);
}

/*=======================================================================
 |
 |		�m�`�j�i���M�v���j�҂�����
 |
 |	int	s_st1(ev)
 |
 |		int	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	int	s_st1(int ev)
{
	int	st;

	st = ST_1;
	switch (ev) {
	case NAK:
		blkno++;	/* �u���b�N�ԍ��X�V */
		if (send_dat() != EOF) {	/* �f�[�^�u���b�N���M */
			tm_set(0, 6000);	/* �`�b�j�҂��^�C�}�[�Z�b�g */
			st = ST_2;
		} else {
			/* ���M�I�� */
			rs1_putc(EOT);		/* �d�n�s���M */
			st = ST_3;
		}
		break;
	case CAN:
		st = ST_ERR;
		break;
	case TIMEOUT:
		st = ST_ERR;
		break;
	case ABORT:
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�f�[�^�u���b�N���M��`�b�j�҂�����
 |
 |	int	s_st2(ev)
 |
 |		int	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	int	s_st2(int ev)
{
	int	st;

	st = ST_2;
	switch(ev) {
	case ACK:
		blkno++;	/* �u���b�N�ԍ��X�V */
		if (send_dat() != EOF) {	/* �f�[�^�u���b�N���M */
			tm_set(0, 6000);	/* �`�b�j�҂��^�C�}�[�Z�b�g */
			st = ST_2;
		} else {
			/* ���M�I�� */
			rs1_putc(EOT);		/* �d�n�s���M */
			st = ST_3;
		}
		break;
	case NAK:
		fseek(sfp, save_fp, SEEK_SET);	/* �t�@�C���ʒu��߂� */
		send_dat();			/* �f�[�^�u���b�N���M */
		tm_set(0, 6000);		/* �`�b�j�҂��^�C�}�[�Z�b�g */
		st = ST_2;
		break;
	case CAN:
		st = ST_ERR;
		break;
	case TIMEOUT:
		st = ST_ERR;
		break;
	case ABORT:
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�d�n�s���M��`�b�j�҂�����
 |
 |	int	s_st3(ev)
 |
 |		int	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	int	s_st3(int ev)
{
	int	st;

	st = ST_3;
	switch (ev) {
	case ACK:
		st = ST_END;
		break;
	case NAK:
		rs1_putc(EOT);
		tm_set(0, 6000);	/* �`�b�j�҂��^�C�}�[�Z�b�g */
		break;
	case CAN:
		st = ST_ERR;
		break;
	case TIMEOUT:
		st = ST_ERR;
		break;
	case ABORT:
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�f�[�^�u���b�N���M����
 |
 |	int	send_dat()
 |
 |		�Ԓl			�O�F���M����
 |					�d�n�e�F���M�I��
 |
 =======================================================================*/
static	int	send_dat(void)
{
	int	c, i;
	u_char	sum;

	/* ���t�@�C���|�C���^�Z�[�u */
	save_fp = ftell(sfp);

	/* �t�@�C���I���`�F�b�N */
	if ((c = fgetc(sfp)) == EOF)
		return(EOF);

	w_printf(swp, 21, 6, swp->color, "%u", blkno);	/* �u���b�N�ԍ��\�� */
	rs1_putc(SOH);		/* �r�n�g���M */
	rs1_putc(blkno);		/* �u���b�N�ԍ����M */
	rs1_putc(~blkno);	/* ���]�����u���b�N�ԍ����M */
	rs1_putc(c);		/* �f�[�^�̂P�o�C�g�ڑ��M */
	sum = c;		/* �`�F�b�N�T���N���A */
	for (i = 0; i < 127; i++) {
		c = fgetc(sfp);	/* �f�[�^�ǂݍ��� */
		if (c == EOF)
			c = 0x1a;
		rs1_putc(c);	/* �f�[�^���M */
		sum += c;	/* �`�F�b�N�T���v�Z */
	}
	rs1_putc(sum);		/* �`�F�b�N�T�����M */

	return(0);
}

/*=======================================================================
 |
 |		�t�@�C����M����
 |
 |	int	x_receive(fp, wp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 |		�Ԓl			�@�O�F����I��
 |					�|�P�F��M���s
 |					�|�Q�F���~
 |
 =======================================================================*/
int	x_receive(FILE *fp, WINDOW *wp)
{
	int	st, ev;

	sfp = fp;		/* ���M�t�@�C���|�C���^�ۑ� */
	swp = wp;		/* �E�B���h�E�|�C���^�ۑ� */
	rs1_flow(FLOW_NONE);	/* �t���[���䖳�� */
	rs1_clear();		/* ��M�o�b�t�@�N���A */

	st = r_start();		/* ��M�J�n���� */
	while (st > 0) {
		ev = event();	/* �C�x���g�҂� */
		switch (st) {
		case ST_1:
			st = r_st1(ev);
			break;
		case ST_2:
			st = r_st2(ev);
			break;
		}
	}
	tm_stop(0);		/* �^�C�}�[��~ */

	if (cprm.xon)
		rs1_flow(FLOW_SOFT);	/* �t���[����ݒ� */

	return(st);
}

/*=======================================================================
 |
 |		�t�@�C����M�J�n����
 |
 |	int	r_start()
 |
 |		�Ԓl			�r�s�Q�P
 |
 =======================================================================*/
static	int	r_start(void)
{
	rs1_putc(NAK);			/* �m�`�j�i���M�v���j���M */
	tm_set(0, 1000);		/* �r�n�g�҂��^�C�}�[�Z�b�g */
	retry_counter = 0;		/* ���g���C�J�E���^�N���A */
	blkno = 0;			/* �u���b�N�ԍ��N���A */
	return(ST_1);
}

/*=======================================================================
 |
 |		�r�n�g��M�҂�����
 |
 |	int	r_st1(ev)
 |
 |		int	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	int	r_st1(int ev)
{
	int	st;

	st = ST_1;
	switch (ev) {
	case SOH:
		rcv_buf[0] = ev;	/* �r�n�g�Z�[�u */
		rcv_counter = 1;	/* ��M�J�E���^�Z�b�g */
		tm_set(0, 100);		/* �����ԃ^�C�}�[�Z�b�g */
		st = ST_2;
		break;
	case EOT:
		rs1_putc(ACK);		/* �`�b�j���M */
		st = ST_END;
		break;
	case TIMEOUT:
		if (++retry_counter <= 10) {	/* ���g���C�`�F�b�N */
			rs1_putc(NAK);	/* �m�`�j���M */
			tm_set(0, 1000);	/* �r�n�g�҂��^�C�}�[�Z�b�g */
		} else {
			/* ���g���C�I�[�o */
			rs1_putc(CAN);	/* �b�`�m���M */
			st = ST_ERR;
		}
		break;
	case ABORT:
		rs1_putc(CAN);	/* �b�`�m���M */
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�f�[�^�u���b�N��M�҂�����
 |
 |	int	r_st2(ev)
 |
 |		int	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	int	r_st2(int ev)
{
	int	st;

	st = ST_2;
	switch (ev) {
	case TIMEOUT:
		if (++retry_counter <= 10) {	/* ���g���C�`�F�b�N */
			rs1_putc(NAK);		/* �m�`�j���M */
			tm_set(0, 1000);	/* �r�n�g�҂��^�C�}�[�Z�b�g */
			st = ST_1;
		} else {
			rs1_putc(CAN);		/* �b�`�m���M */
			st = ST_ERR;
		}
		break;
	case ABORT:
		rs1_putc(CAN);			/* �b�`�m���M */
		st = ST_ABORT;
		break;
	default:
		if (ev <= 0xff) {
			rcv_buf[rcv_counter++] = ev;	/* ��M�f�[�^�Z�[�u */
			if (rcv_counter == 132) {
				/* �P�u���b�N��M�I�� */
				if (rcv_dat() == 0) {
					/* ��M�f�[�^�͐����� */
					rs1_putc(ACK);
					tm_set(0, 1000);
					retry_counter = 0;
					st = ST_1;
				} else {
					/* ��M�f�[�^�ɃG���[�L�� */
					if (++retry_counter <= 10) {
						rs1_putc(NAK);
						tm_set(0, 1000);
						st = ST_1;
					} else {
						rs1_putc(CAN);
						st = ST_ERR;
					}
				}
			} else
				tm_set(0, 100);	/* ��������ϰ��� */
		}
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�f�[�^�u���b�N��M����
 |
 |	int	rcv_dat()
 |
 |		�Ԓl			�O�F����u���b�N
 |					�P�F�ُ�u���b�N
 |
 =======================================================================*/
static	int	rcv_dat(void)
{
	u_char	sum;
	int	i;

	/* �u���b�N�ԍ��`�F�b�N */
	if (rcv_buf[1] != (u_char)~rcv_buf[2])
		return(1);

	/* �`�F�b�N�T���`�F�b�N */
	sum = 0;
	for (i = 0; i < 128; i++)
		sum += rcv_buf[3+i];
	if (sum != rcv_buf[131])
		return(1);

	/* �u���b�N�ԍ��V�[�P���X�`�F�b�N */
	if (rcv_buf[1] == (u_char)blkno)
		;
	else if (rcv_buf[1] == (u_char)(blkno + 1)) {
		fwrite(rcv_buf + 3, 128, 1, sfp);
		blkno++;
	} else
		return(1);

	/* ��M�u���b�N�ԍ��\�� */
	w_printf(swp, 21, 6, swp->color, "%u", blkno);

	return(0);
}

/*=======================================================================
 |
 |		�C�x���g�҂�����
 |
 |	int	event()
 |
 |		�Ԓl			�C�x���g
 |
 =======================================================================*/
static	int	event(void)
{
	int	ev, ch;

	for (;;) {
		if (tm_check(0)) {
			ev = TIMEOUT;
			break;
		} else if (rs1_rxc()) {
			ev = (u_char)rs1_get();
			break;
		} else if (ch = inkey()) {
			if (ch == KEY_ESC || ch == KEY_F9) {
				ev = ABORT;
				break;
			}
		}
	}

	return(ev);
}