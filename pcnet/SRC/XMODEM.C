/************************************************************************
 *									*
 *		�n�r�^�Q�@�I�����C�����[�U�T�|�[�g�V�X�e��		*
 *			�o�b�|�m�d�s�^�Q				*
 *									*
 *		����		: �w�l�n�c�d�l����M����		*
 *		�t�@�C����	: xmodem.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <string.h>

#define	ST_END		0
#define	ST_ERR		-1
#define	ST_ABORT	-2
#define	ST_1		1
#define	ST_2		2
#define	ST_3		3
#define	SOH		(CHAR)0x01
#define	EOT		(CHAR)0x04
#define	ACK		(CHAR)0x06
#define	NAK		(CHAR)0x15
#define	CAN		(CHAR)0x18
#define	TIMEOUT		0x101
#define	ABORT		0x102

typedef	struct	{
	USHORT	chNo;
	FILE	*fp;
	VOID	(*func)(USHORT, USHORT);
	USHORT	blkno;
	CHAR	rcv_buf[132];
	SHORT	timer;
	SHORT	retry_counter;
	SHORT	rcv_counter;
	LONG	save_fp;
	BOOL	crcMode;
} XMODEM;

/*--------------------------------------*/
/*		�����֐�		*/
/*--------------------------------------*/
static	SHORT	s_start(XMODEM *);
static	SHORT	s_st1(XMODEM *, SHORT);
static	SHORT	s_st2(XMODEM *, SHORT);
static	SHORT	s_st3(XMODEM *, SHORT);
static	SHORT	send_dat(XMODEM *);
static	SHORT	r_start(XMODEM *);
static	SHORT	r_st1(XMODEM *, SHORT);
static	SHORT	r_st2(XMODEM *, SHORT);
static	SHORT	rcv_dat(XMODEM *);
static	SHORT	event(XMODEM *);
static	VOID	SendChar(XMODEM *, CHAR);
static	VOID	SendBlock(XMODEM *, CHAR *, USHORT);
static	VOID	SetTimer(XMODEM *, SHORT);
static	USHORT	calcrc(CHAR *, SHORT);

/*--------------------------------------*/
/*		�����ϐ�		*/
/*--------------------------------------*/
static	BOOL	xFlag[N_CHANNEL];

/*=======================================================================
 |
 |		�t�@�C�����M����
 |
 |	SHORT	SXmodem(chNo, fp, func)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		FILE	*fp;		�t�@�C���|�C���^
 |		VOID	(*func)();	�u���b�N�ԍ��\�������֐�
 |
 |		�Ԓl			�@�O�F����I��
 |					�|�P�F���M���s
 |					�|�Q�F���~
 |
 =======================================================================*/
SHORT	SXmodem(USHORT chNo, FILE *fp, VOID (*func)())
{
	SHORT	st, ev;
	DCBINFO		svDCBInfo, DCBInfo;
	XMODEM	xmodem;

	if (chNo == CH0)
		return(-1);

	xFlag[chNo] = TRUE;

	DosDevIOCtl(&DCBInfo, 0L, ASYNC_GETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);
	memcpy(&svDCBInfo, &DCBInfo, sizeof(DCBINFO));

	DCBInfo.fbFlowReplace = 0;
	DCBInfo.fbTimeout = MODE_READ_TIMEOUT;
	DosDevIOCtl(0L, &DCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	ClearRcvQue(chNo);		/* ��M�o�b�t�@�N���A */

	xmodem.chNo = chNo;
	xmodem.fp = fp;
	xmodem.func = func;

	st = s_start(&xmodem);		/* ���M�J�n���� */
	while (st > 0) {
		ev = event(&xmodem);	/* �C�x���g�҂� */
		switch (st) {
		case ST_1:
			st = s_st1(&xmodem, ev);
			break;
		case ST_2:
			st = s_st2(&xmodem, ev);
			break;
		case ST_3:
			st = s_st3(&xmodem, ev);
			break;
		}
	}

	DosDevIOCtl(0L, &svDCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	xFlag[chNo] = FALSE;

	return(st);
}

/*=======================================================================
 |
 |		�t�@�C�����M�J�n����
 |
 |	SHORT	s_start(xp)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |
 |		�Ԓl			�r�s�Q�P
 |
 =======================================================================*/
static	SHORT	s_start(XMODEM *xp)
{
	SetTimer(xp, 60);		/* �m�`�j�҂��^�C�}�[�Z�b�g */
	xp->blkno = 0;			/* �u���b�N�ԍ��N���A */

	return(ST_1);
}

/*=======================================================================
 |
 |		�m�`�j�i���M�v���j�҂�����
 |
 |	SHORT	s_st1(xp, ev)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		SHORT	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	SHORT	s_st1(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_1;
	switch (ev) {
	case NAK:
		xp->crcMode = FALSE;
		goto label_1;
	case 'C':
		xp->crcMode = TRUE;
	label_1:
		xp->blkno++;		/* �u���b�N�ԍ��X�V */
		if (send_dat(xp) != EOF) {	/* �f�[�^�u���b�N���M */
			SetTimer(xp, 60);	/* �`�b�j�҂��^�C�}�[�Z�b�g */
			st = ST_2;
		} else {
			/* ���M�I�� */
			SendChar(xp, EOT);	/* �d�n�s���M */
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
 |	SHORT	s_st2(xp, ev)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		SHORT	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	SHORT	s_st2(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_2;
	switch(ev) {
	case ACK:
		xp->blkno++;		/* �u���b�N�ԍ��X�V */
		if (send_dat(xp) != EOF) {	/* �f�[�^�u���b�N���M */
			SetTimer(xp, 60);	/* �`�b�j�҂��^�C�}�[�Z�b�g */
			st = ST_2;
		} else {
			/* ���M�I�� */
			SendChar(xp, EOT);	/* �d�n�s���M */
			st = ST_3;
		}
		break;
	case NAK:
		/* �t�@�C���ʒu��߂� */
		fseek(xp->fp, xp->save_fp, SEEK_SET);
		send_dat(xp);			/* �f�[�^�u���b�N���M */
		SetTimer(xp, 60);		/* �`�b�j�҂��^�C�}�[�Z�b�g */
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
 |	SHORT	s_st3(xp, ev)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		SHORT	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	SHORT	s_st3(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_3;
	switch (ev) {
	case ACK:
		st = ST_END;
		break;
	case NAK:
		SendChar(xp, EOT);
		SetTimer(xp, 60);		/* �`�b�j�҂��^�C�}�[�Z�b�g */
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
 |	SHORT	send_dat(xp)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |
 |		�Ԓl			�O�F���M����
 |					�d�n�e�F���M�I��
 |
 =======================================================================*/
static	SHORT	send_dat(XMODEM *xp)
{
	SHORT	c, i;
	CHAR	sum;
	USHORT	crc;
	CHAR	sendBuf[132];

	/* ���t�@�C���|�C���^�Z�[�u */
	xp->save_fp = ftell(xp->fp);

	/* �t�@�C���I���`�F�b�N */
	if ((c = fgetc(xp->fp)) == EOF)
		return(EOF);

	sendBuf[0] = SOH;
	sendBuf[1] = (CHAR)xp->blkno;	/* �u���b�N�ԍ����M */
	sendBuf[2] = (CHAR)~xp->blkno;	/* ���]�����u���b�N�ԍ����M */
	sendBuf[3] = (CHAR)c;		/* �f�[�^�̂P�o�C�g�ڑ��M */
	for (i = 4; i < 131; i++) {
		c = fgetc(xp->fp);		/* �f�[�^�ǂݍ��� */
		if (c == EOF)
			c = 0x1a;
		sendBuf[i] = (CHAR)c;	/* �f�[�^���M */
	}

	if (xp->crcMode) {
		crc = calcrc(&sendBuf[3], 128);
		sendBuf[i++] = (CHAR)(crc >> 8);
		sendBuf[i] = (CHAR)crc;
		SendBlock(xp, sendBuf, 133);
	} else {
		sum = 0;
		for (i = 3; i < 131; i++)
			sum += sendBuf[i];
		sendBuf[i] = sum;
		SendBlock(xp, sendBuf, 132);
	}

	if (xp->func != NULL)
		(*xp->func)(xp->chNo, xp->blkno);

	return(0);
}

/*=======================================================================
 |
 |		�t�@�C����M����
 |
 |	SHORT	RXmodem(chNo, fp, func)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |		FILE	*fp;		�t�@�C���|�C���^
 |		VOID	(*func)();	�u���b�N�ԍ��\�������֐�
 |
 |		�Ԓl			�@�O�F����I��
 |					�|�P�F��M���s
 |					�|�Q�F���~
 |
 =======================================================================*/
SHORT	RXmodem(USHORT chNo, FILE *fp, VOID (*func)())
{
	SHORT	st, ev;
	DCBINFO		svDCBInfo, DCBInfo;
	XMODEM	xmodem;

	if (chNo == CH0)
		return(-1);

	xFlag[chNo] = TRUE;

	DosDevIOCtl(&DCBInfo, 0L, ASYNC_GETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);
	memcpy(&svDCBInfo, &DCBInfo, sizeof(DCBINFO));

	DCBInfo.fbFlowReplace = 0;
	DCBInfo.fbTimeout = MODE_READ_TIMEOUT;
	DosDevIOCtl(0L, &DCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	ClearRcvQue(chNo);		/* ��M�o�b�t�@�N���A */

	xmodem.chNo = chNo;
	xmodem.fp  = fp;
	xmodem.func = func;

	st = r_start(&xmodem);		/* ��M�J�n���� */
	while (st > 0) {
		ev = event(&xmodem);	/* �C�x���g�҂� */
		switch (st) {
		case ST_1:
			st = r_st1(&xmodem, ev);
			break;
		case ST_2:
			st = r_st2(&xmodem, ev);
			break;
		}
	}

	DosDevIOCtl(0L, &svDCBInfo, ASYNC_SETDCBINFO,
					IOCTL_ASYNC, portHndl[chNo]);

	xFlag[chNo] = FALSE;

	return(st);
}

/*=======================================================================
 |
 |		�t�@�C����M�J�n����
 |
 |	SHORT	r_start(xp)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |
 |		�Ԓl			�r�s�Q�P
 |
 =======================================================================*/
static	SHORT	r_start(XMODEM *xp)
{
	SendChar(xp, NAK);		/* �m�`�j�i���M�v���j���M */
	SetTimer(xp, 10);		/* �r�n�g�҂��^�C�}�[�Z�b�g */
	xp->retry_counter = 0;		/* ���g���C�J�E���^�N���A */
	xp->blkno = 0;			/* �u���b�N�ԍ��N���A */
	return(ST_1);
}

/*=======================================================================
 |
 |		�r�n�g��M�҂�����
 |
 |	SHORT	r_st1(xp, ev)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		SHORT	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	SHORT	r_st1(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_1;
	switch (ev) {
	case SOH:
		xp->rcv_buf[0] = SOH;	/* �r�n�g�Z�[�u */
		xp->rcv_counter = 1;	/* ��M�J�E���^�Z�b�g */
		SetTimer(xp, 1);	/* �����ԃ^�C�}�[�Z�b�g */
		st = ST_2;
		break;
	case EOT:
		SendChar(xp, ACK);	/* �`�b�j���M */
		st = ST_END;
		break;
	case TIMEOUT:
		if (++xp->retry_counter <= 10) {	/* ���g���C�`�F�b�N */
			SendChar(xp, NAK);	/* �m�`�j���M */
			SetTimer(xp, 10);	/* �r�n�g�҂��^�C�}�[�Z�b�g */
		} else {
			/* ���g���C�I�[�o */
			SendChar(xp, CAN);	/* �b�`�m���M */
			st = ST_ERR;
		}
		break;
	case ABORT:
		SendChar(xp, CAN);	/* �b�`�m���M */
		st = ST_ABORT;
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�f�[�^�u���b�N��M�҂�����
 |
 |	SHORT	r_st2(xp, ev)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		SHORT	ev;		�C�x���g
 |
 |		�Ԓl			�X�e�[�^�X�i�r�s�Q�w�w�w�j
 |
 =======================================================================*/
static	SHORT	r_st2(XMODEM *xp, SHORT ev)
{
	SHORT	st;

	st = ST_2;
	switch (ev) {
	case TIMEOUT:
		if (++xp->retry_counter <= 10) {	/* ���g���C�`�F�b�N */
			SendChar(xp, NAK);	/* �m�`�j���M */
			SetTimer(xp, 10);	/* �r�n�g�҂��^�C�}�[�Z�b�g */
			st = ST_1;
		} else {
			SendChar(xp, CAN);	/* �b�`�m���M */
			st = ST_ERR;
		}
		break;
	case ABORT:
		SendChar(xp, CAN);		/* �b�`�m���M */
		st = ST_ABORT;
		break;
	default:
		if (ev <= 0xff) {
			/* ��M�f�[�^�Z�[�u*/
			xp->rcv_buf[xp->rcv_counter++] = (CHAR)ev;
			if (xp->rcv_counter == 132) {
				/* �P�u���b�N��M�I�� */
				if (rcv_dat(xp) == 0) {
					/* ��M�f�[�^�͐����� */
					SendChar(xp, ACK);
					SetTimer(xp, 10);
					xp->retry_counter = 0;
					st = ST_1;
				} else {
					/* ��M�f�[�^�ɃG���[�L�� */
					if (++xp->retry_counter <= 10) {
						SendChar(xp, NAK);
						SetTimer(xp, 10);
						st = ST_1;
					} else {
						SendChar(xp, CAN);
						st = ST_ERR;
					}
				}
			} else
				SetTimer(xp, 1);	/* ��������ϰ��� */
		}
		break;
	}

	return(st);
}

/*=======================================================================
 |
 |		�f�[�^�u���b�N��M����
 |
 |	SHORT	rcv_dat(xp)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |
 |		�Ԓl			�O�F����u���b�N
 |					�P�F�ُ�u���b�N
 |
 =======================================================================*/
static	SHORT	rcv_dat(XMODEM *xp)
{
	CHAR	sum;
	SHORT	i;

	/* �u���b�N�ԍ��`�F�b�N */
	if (xp->rcv_buf[1] != (CHAR)~xp->rcv_buf[2])
		return(1);

	/* �`�F�b�N�T���`�F�b�N */
	sum = 0;
	for (i = 0; i < 128; i++)
		sum += xp->rcv_buf[3+i];
	if (sum != xp->rcv_buf[131])
		return(1);

	/* �u���b�N�ԍ��V�[�P���X�`�F�b�N */
	if (xp->rcv_buf[1] == (CHAR)xp->blkno)
		;
	else if (xp->rcv_buf[1] == (CHAR)(xp->blkno + 1)) {
		fwrite(xp->rcv_buf + 3, 128, 1, xp->fp);
		xp->blkno++;
	} else
		return(1);

	if (xp->func != NULL)
		(*xp->func)(xp->chNo, xp->blkno);

	return(0);
}

/*=======================================================================
 |
 |		�C�x���g�҂�����
 |
 |	SHORT	event(xp)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |
 |		�Ԓl			�C�x���g
 |
 =======================================================================*/
static	SHORT	event(XMODEM *xp)
{
	CHAR	rcvChar;
	USHORT	numBytes;
	SHORT	ev;
	SHORT	timer;

	timer = xp->timer;
	for (;;) {
		if (xFlag[xp->chNo] == FALSE) {
			ev = ABORT;
			break;
		}

		DosRead(portHndl[xp->chNo], &rcvChar, 1, &numBytes);
		if (numBytes != 0) {
			ev = rcvChar;
			break;
		} else if (--timer == 0) {
			ev = TIMEOUT;
			break;
		}
	}

	return(ev);
}

/*=======================================================================
 |
 |		�P�������M����
 |
 |	VOID	SendChar(xp, ch)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		CHAR	ch;		���M����
 |
 =======================================================================*/
static	VOID	SendChar(XMODEM *xp, CHAR ch)
{
	USHORT	numBytes;

	DosWrite(portHndl[xp->chNo], &ch, 1, &numBytes);
}

/*=======================================================================
 |
 |		�P�u���b�N���M����
 |
 |	VOID	SendBlock(xp, block, len)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		CHAR	*block;		�u���b�N�o�b�t�@
 |		USHORT	len;		�u���b�N��
 |
 =======================================================================*/
static	VOID	SendBlock(XMODEM *xp, CHAR *block, USHORT len)
{
	USHORT	numBytes;

	DosWrite(portHndl[xp->chNo], block, len, &numBytes);
}

/*=======================================================================
 |
 |		�^�C�}�[�Z�b�g����
 |
 |	VOID	SetTimer(xp, tm)
 |
 |		XMODEM	*xp;		�w�l�n�c�d�l�p�����[�^
 |		SHORT	tm;		�^�C�}�[�l
 |
 =======================================================================*/
static	VOID	SetTimer(XMODEM *xp, SHORT tm)
{
	xp->timer = tm;
}

/*=======================================================================
 |
 |		�w�l�n�c�d�l���~����
 |
 |	VOID	AbortXmodem(chNo)
 |
 |		USHORT	chNo;		�`���l���ԍ�
 |
 =======================================================================*/
VOID	AbortXmodem(USHORT chNo)
{
	xFlag[chNo] = FALSE;
}

/*=======================================================================
 |
 |		�b�q�b�v�Z
 |
 |	USHORT	calcrc(ptr, count)
 |
 |		CHAR	*ptr;		�f�[�^�o�b�t�@
 |		USHORT	crc;		�f�[�^��
 |
 |		�Ԓl	�b�q�b
 |
 =======================================================================*/
static	USHORT	calcrc(CHAR *ptr, SHORT count)
{
	USHORT	crc, i;

	crc = 0;
	while (--count >= 0) {
		crc = crc ^ (USHORT)*ptr++ << 8;
		for (i = 0; i < 8; i++)
			if (crc & 0x8000)
				crc = crc << 1 ^ 0x1021;
			else
				crc = crc << 1;
	}
	return(crc);
}