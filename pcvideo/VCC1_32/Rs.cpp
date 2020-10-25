
/***********************************************************************
 |
 |	RS232C Win32 ���ʃT�u���[�`�����C�u����
 |
 ||		�q�r�Q�R�Q�b�֘A
 |
 |			rs.c
 |
 |		Ver 1.00    1994/12/12  HAMA
 |		Ver 2.00	1998/11/19	M.HAMA	Win32�Ή�
 |
 ************************************************************************/

#include "stdafx.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "rs.h"

#ifdef __cplusplus
extern "C" {
#endif

	/* RS232C��������č\���� */
typedef struct _RS232CSET {
	CHAR	portName[6];		/* �߰Ĕԍ�(COM1, COM2...) */
	DCB	dcb;					/* DCB�\���� */
	COMMTIMEOUTS	ctmo;		/* COMMTIMEOUTS�\���� */
} RS232CSET, *LPRS232CSET;

static	int	RsPrmcheck(LPRSPRM, LPRS232CSET);

static	HANDLE	rsDev;
static	int	open_flag;
static	int	duplex;
static	DWORD	tmStart;
static	DWORD	tmSetSec;

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v��
 |
 |	int	RsOpen(LPSTR rsprm)
 |
 |		LPSTR	rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |
 |		�Ԓl		�O�F����
 |					�P�F��d���[�h�p�����[�^�G���[
 |					�Q�F�{�[���[�g�p�����[�^�G���[
 |					�R�F�p���e�B�[�p�����[�^�G���[
 |					�S�F�L�����N�^���p�����[�^�G���[
 |					�T�F�X�g�b�v�r�b�g���p�����[�^�G���[
 |					�U�FCOM�p�����[�^�G���[
 |					�V�F�I�[�v���G���[
 |					�W�F�d���������Ă��Ȃ����A�ڑ�����Ă��Ȃ��B
 |
 =======================================================================*/
int	RsOpen(LPSTR prm)
{
	RS232CSET	rs232cset;
	LPRSPRM	lpParam;
	int	rt;

	lpParam = (LPRSPRM)prm;

	if ((rt = RsPrmcheck((LPRSPRM)lpParam, (LPRS232CSET)&rs232cset)) != 0)
		return(rt);

	/* ������ */
	if ((rsDev = CreateFile(rs232cset.portName, GENERIC_READ | GENERIC_WRITE, 0,
			NULL, OPEN_EXISTING, 0, NULL))	== INVALID_HANDLE_VALUE) {
		return(7);
	}

	/* ����M�ޯ̧��� */
	if (!SetupComm(rsDev, 1200, 1200)) {
		CloseHandle(rsDev);
		return(7);
	}

	/* RS232C����� */
	if (!SetCommState(rsDev, &rs232cset.dcb)) {
		CloseHandle(rsDev);
		return(7);
	}

	/* ��ѱ�Ď��Ԑݒ� */
	if (!SetCommTimeouts(rsDev, &rs232cset.ctmo)) {
		CloseHandle(rsDev);
		return(7);
	}

	open_flag = 1;

	return(0);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^�`�F�b�N
 |
 |	int	RsPrmcheck(RSPRM *rsprm, LPRS232CSET rs232cset)
 |
 |		RSPRM	*rsprm;		�q�r�|�Q�R�Q�b�I�[�v���p�����[�^
 |		LPRS232CSET	rs232cset	�p�����[�^�ҏW�o�b�t�@
 |
 |		�Ԓl			�O�F����p�����[�^
 |					�P�F��d���[�h�p�����[�^�G���[
 |					�U�F�b�����m���p�����[�^�G���[
 |	
 =======================================================================*/
static int RsPrmcheck(LPRSPRM rsprm, LPRS232CSET rs232cset)
{
	/* ��d���[�h�p�����[�^�`�F�b�N */
	switch (duplex = rsprm->duplex) {
	case DUPLEX_FULL:	/* �S��d */
		break;
	case DUPLEX_HALF:	/* ����d */
		break;
	default:
		return(1);	/* �p�����[�^�G���[ */
	}

	/* �b�����m���`�F�b�N */
	switch (rsprm->comno) {
	case COM1:	/* COM1 */
	case COM2:	/* COM2 */
	case COM3:	/* COM3 */
		break;
	default:	/* �p�����[�^�G���[ */
		return(6);
	}

	duplex = rsprm->duplex;

	memset(&rs232cset->dcb, 0, sizeof(DCB));
	rs232cset->dcb.DCBlength = sizeof(DCB);
	rs232cset->dcb.BaudRate = rsprm->blt;
	rs232cset->dcb.fBinary = TRUE;
	rs232cset->dcb.fOutxCtsFlow = TRUE;
	rs232cset->dcb.fDtrControl = DTR_CONTROL_ENABLE;
	rs232cset->dcb.fRtsControl = RTS_CONTROL_ENABLE;
	rs232cset->dcb.ByteSize = rsprm->chr_len;
	rs232cset->dcb.Parity = rsprm->parity;
	rs232cset->dcb.StopBits = rsprm->stp_len;
	rs232cset->dcb.XonChar = 0x11;
	rs232cset->dcb.XoffChar = 0x13;

	memset(&rs232cset->ctmo, 0, sizeof(COMMTIMEOUTS));
	rs232cset->ctmo.ReadTotalTimeoutConstant = 3000;
	rs232cset->ctmo.WriteTotalTimeoutConstant = 3000;

	sprintf(rs232cset->portName, "COM%d", rsprm->comno + 1);
			
	return(0);
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�N���[�Y
 |
 |	VOID	RsClose(VOID)
 |
 =======================================================================*/
VOID RsClose()
{
	/* ���I�[�v���Ȃ牽�����Ȃ� */
	if (open_flag == 0)
		return;

	PurgeComm(rsDev, PURGE_RXABORT | PURGE_RXCLEAR);
	CloseHandle(rsDev);

	open_flag = 0;		/* �I�[�v�����t���O���Z�b�g */
}

/*=======================================================================
 |
 |		�P������M
 |
 |	int	RsGet(VOID)
 |
 |		�Ԓl		��M����
 |
 =======================================================================*/
DWORD	RsGet()
{
	DWORD	fModemStatus;
	DWORD	numBytes;
	CHAR	buf[2];
	DWORD	c;

	/* DSR�`�F�b�N */
	GetCommModemStatus(rsDev, &fModemStatus);
	if (!(fModemStatus & MS_DSR_ON))
		return(0x100);

	/* 1�����ǂݍ��� */
	if (!ReadFile(rsDev, buf, 1, &numBytes, NULL))
		return(0xffff);

	if (numBytes != 1)
		return(0x200);

	c = (DWORD)(buf[0] & 0xff);

	return(c);
}

/*=======================================================================
 |
 |		�P�������M
 |
 |	VOID	RsPut(c)
 |
 |		int	c;		���M����
 |
 =======================================================================*/
BOOL RsPut(int c)
{
	DWORD	numBytes;
	char	buf;
	
	if (open_flag == 0)
		return(FALSE);

	buf = (char)c;
	
	return(WriteFile(rsDev, &buf, 1, &numBytes, NULL));
}

/*=======================================================================
 |
 |		�u���b�N���M
 |
 |	VOID	RsSend(LPSTR s, int len)
 |
 |		LPSTR	s;		���M������
 |		int	len;		������
 |
 =======================================================================*/
BOOL RsSend(LPSTR s, int len)
{
	if (open_flag == 0)
		return(FALSE);

	if (len == 0)
		return(FALSE);

	if (duplex == DUPLEX_HALF) {
		/* ���M�A��M�֎~�A�q�s�r�I�� */
		RsRts(1);
	}

	/* �f�[�^���M */
	while (len--){
		if (! RsPut(*s++))
			return(FALSE);
	}

	if (duplex == DUPLEX_HALF) {
		/* �҂� */
		TmWait(5);	

		/* ��M�A�q�s�r�I�t */
		RsRts(0);
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		�c�s�q����
 |
 |	VOID	RsDtr(sw);
 |
 |		int	sw;		�O�F�c�s�q�I�t�@���O�F�c�s�q�I��
 |
 =======================================================================*/
VOID RsDtr(int sw)
{
	EscapeCommFunction(rsDev, sw == 0 ? CLRDTR: SETDTR);
}

/*=======================================================================
 |
 |		�q�s�r����
 |
 |	VOID	RsRts(sw);
 |
 |		int	sw;		�O�F�q�s�r�I�t�@���O�F�q�s�r�I��
 |
 =======================================================================*/
VOID RsRts(int sw)
{
	/* RTS�@*/
	EscapeCommFunction(rsDev, sw == 0 ? CLRRTS : SETRTS);

	/* �҂� */
	TmWait(sw == 0 ? 1 : 24);	
}

/*=======================================================================
 |
 |		�c�r�q��ԕω��`�F�b�N
 |
 |	int	RsDsr()
 |
 |		�Ԓl	�O�F�c�r�q��ԕύX�����@�P�F�c�r�q��ԕύX����
 |
 =======================================================================*/
int RsDsr()
{
	DWORD	fModemStatus;

	GetCommModemStatus(rsDev, &fModemStatus);
	return(fModemStatus & MS_DSR_ON);
}

/*=======================================================================
 |
 |		�L�����A�`�F�b�N
 |
 |	int	RsCd()
 |
 |		�Ԓl		�O�F�L�����A�I�t�@�P�F�L�����A�I��
 |
 =======================================================================*/
int	 RsCd()
{
	DWORD	fModemStatus;

	GetCommModemStatus(rsDev, &fModemStatus);
	return(fModemStatus & MS_RLSD_ON);
}

/*=======================================================================
 |
 |		�b�s�r�`�F�b�N
 |
 |	int	RsCts()
 |
 |		�Ԓl		�O�F�b�s�r�I�t�@�P�F�b�s�r�I��
 |
 =======================================================================*/
int	 RsCts(void)
{
	DWORD	fModemStatus;

	GetCommModemStatus(rsDev, &fModemStatus);
	return(fModemStatus & MS_CTS_ON);
}

/*=======================================================================
 |
 |		�^�C�}�J�n
 |
 |	VOID	TmSet(int m)
 |
 |		int	m;		���ԁi�P�O�����P�ʁj
 |
 =======================================================================*/
VOID TmSet(int m)
{
	tmStart = GetTickCount();
	tmSetSec = (DWORD)m;
}

/*=======================================================================
 |
 |		�^�C���A�E�g�`�F�b�N
 |
 |	BOOL	TmCheck()
 |
 |		�Ԃ�l		�O�F���ԓ�	�P�F�^�C���A�E�g
 |
 =======================================================================*/
BOOL TmCheck()
{
	DWORD	tt;

	tt = GetTickCount();

	return(((tt - tmStart) / 10L >= tmSetSec));
}

/*=======================================================================
 |
 |		�E�G�C�g����
 |
 |	VOID	TmWait(int m)
 |
 |		int	m;		���ԁi�P�O�����P�ʁj
 |
 =======================================================================*/
VOID TmWait(int m)
{
	TmSet(m);
	for(;;){
		if (TmCheck())
			break;
	}
}

#ifdef __cplusplus
}
#endif
