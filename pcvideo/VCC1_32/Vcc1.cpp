
/***********************************************************************
 *
 *			���ʃT�u���[�`�����C�u����
 *
 *									for MS-WINDOWS Ver 3.1
 *			VCC1�}���h�֘A
 *
 *			vcc1.c
 *
 *	Ver 1.00    1995/12/22  HAMA
 *
 ************************************************************************/

#include "stdafx.h"
#include <stdlib.h>
#include <string.h>
#include "rs.h"
#include "vcc1.h"

#ifdef __cplusplus
extern "C" {
#endif

#define	HEADER		(LPSTR)"\xff\x30\x30"	/* �w�b�_�[ */
#define	TERMINATOR	(LPSTR)"\xef"			/* �^�[�~�l�C�^ */

/* �R�}���h */
#define	STOP			0
#define	PAN_RIGHT		1
#define	PAN_LEFT		2
#define	TILT_UP			3
#define	TILT_DOWN		4
#define	ZOOM_STOP		5
#define	ZOOM_WIDE		6
#define	ZOOM_TELE		7
#define	ZOOM_WIDE2		8
#define	ZOOM_TELE2		9
#define	POWER_OFF		10
#define	POWER_ON		11
#define	FOCUS_AF		12
#define	FOCUS_MANUAL	13
#define	FOCUS_NEAR		14
#define	FOCUS_FAR		15
#define	SHUT_PRO		16
#define	SHUT_60			17
#define	SHUT_100		18
#define	INIT1			19
#define	INIT2			20
#define	HOSEI_OFF		21
#define	HOSEI_ON		22
#define	WHITE_NORMAL	23
#define	WHITE_LOCK		24
#define	CONTROLER_ON	25
#define	CONTROLER_OFF	26
#define	THROW_ON		27
#define	THROW_OFF		28

/* �ݒ�R�}���h */
#define	PAN_SPEED		0
#define	TILT_SPEED		1
#define	PAN_POS			2
#define	TILT_POS		3
#define	ZOOM_POS		4
#define	SAVE_POS		5
#define	RESTORE_POS		6

/* �v���R�}���h */
#define	R_PAN_SPEED		0
#define	R_TILT_SPEED	1
#define	R_PAN_POS		2
#define	R_TILT_POS		3


/* �R�}���h�e�[�u�� */
static	LPSTR	command_tbl[] = {
	/* 0x53 */
	(LPSTR)"\x00\x53\x30", (LPSTR)"\x00\x53\x31", (LPSTR)"\x00\x53\x32", 
	(LPSTR)"\x00\x53\x33", (LPSTR)"\x00\x53\x34",
	/* 0xA2 */
	(LPSTR)"\x00\xA2\x30", (LPSTR)"\x00\xA2\x31", (LPSTR)"\x00\xA2\x32", 
	(LPSTR)"\x00\xA2\x33", (LPSTR)"\x00\xA2\x34",
	/* 0xA0 */
	(LPSTR)"\x00\xA0\x30", (LPSTR)"\x00\xA0\x31",
	/* 0xA1 */ 
	(LPSTR)"\x00\xA1\x30", (LPSTR)"\x00\xA1\x31", (LPSTR)"\x00\xA1\x32", 
	(LPSTR)"\x00\xA1\x33",
	/* 0xA8 */
	(LPSTR)"\x00\xA8\x30", (LPSTR)"\x00\xA8\x31", (LPSTR)"\x00\xA8\x32", 
	/* 0x58 */
	(LPSTR)"\x00\x58\x30", (LPSTR)"\x00\x58\x31",
	/* 0xA5 */
	(LPSTR)"\x00\xA5\x30", (LPSTR)"\x00\xA5\x31",
	/* 0xA7 */
	(LPSTR)"\x00\xA7\x30", (LPSTR)"\x00\xA7\x31",
	/* 0x80 */
	(LPSTR)"\x00\x80\x30", (LPSTR)"\x00\x80\x31",
	/* 0x8D */
	(LPSTR)"\x00\x8D\x30", (LPSTR)"\x00\x8D\x31"
};

/* �ݒ�R�}���h�e�[�u�� */
static	LPSTR	scommand_tbl[] = {
	(LPSTR)"\x00\x50", (LPSTR)"\x00\x51", (LPSTR)"\x00\x54",
	(LPSTR)"\x00\x55", (LPSTR)"\x00\xA3", (LPSTR)"\x00\x89",
	(LPSTR)"\x00\x8A"
};

/* �v���R�}���h�e�[�u�� */
static	LPSTR	rcommand_tbl[] = {
	/* 0x52 */
	(LPSTR)"\x00\x52\x30", (LPSTR)"\x00\x52\x31",
	/* 0x56 */
	(LPSTR)"\x00\x56\x30", (LPSTR)"\x00\x56\x31"
};

static	VOID	SetPrm(LPRSPRM, int);
static	VOID	Cmd_Chr(BYTE);
static	VOID	Cmd_Send(int);
static	VOID	Cmd_Param_Send(int, int, int);
static	int		Cmd_Request_Chr(BYTE);
static	int		Cmd_Request_Send(int);
static	int		GetAns(LPINT);

static	VOID	itoa_hex(LPSTR, WORD, int);
static	int		atonum(char);
static	int		atoi_hex(LPSTR, int);

/*=======================================================================
 |
 |		VCC1����������
 |
 |		int	Vcc1Open()
 |
 |		�Ԓl		�O�F����
 |					�P�F��d���[�h�p�����[�^�G���[
 |					�Q�F�{�[���[�g�p�����[�^�G���[
 |					�R�F�p���e�B�[�p�����[�^�G���[
 |					�S�F�L�����N�^���p�����[�^�G���[
 |					�T�F�X�g�b�v�r�b�g���p�����[�^�G���[
 |					�U�FCOM�p�����[�^�G���[
 |					�V�F�I�[�v���G���[
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1Open(int com)
{
	RSPRM	rsprm;
	int	rt;

	if (com < VCC1_COM1 || com > VCC1_COM3)
		return(6);

	/* �p�����[�^�Z�b�g */
	SetPrm((LPRSPRM)&rsprm, com);

	/* �q�r�I�[�v�� */
	if ((rt = RsOpen((LPSTR)&rsprm)) != 0)
		return(rt);

	return(0);
}

/*=======================================================================
 |
 |		VCC1�I������
 |
 |		void	Vcc1Close()
 |
 =======================================================================*/
DllExport VOID APIENTRY Vcc1Close()
{
	RsClose();
}

/*=======================================================================
 |
 |		�J����RESET����
 |
 |	void	Vcc1Reset()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Reset()
{
	Cmd_Chr(0xAA);
}

/*=======================================================================
 |
 |		HOME �|�W�V��������
 |
 |	void	Vcc1Home()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Home()
{
	Cmd_Chr(0x57);
}


/*=======================================================================
 |
 |		�p���A�`���g��~����
 |
 |	void	Vcc1Stop()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Stop()
{
	Cmd_Send(STOP);
}

/*=======================================================================
 |
 |		�p���E�X�^�[�g����
 |
 |	void	Vcc1PanRight()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1PanRight()
{
	Cmd_Send(PAN_RIGHT);
}

/*=======================================================================
 |
 |		�p�����X�^�[�g����
 |
 |	void	Vcc1PanLeft()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1PanLeft()
{
	Cmd_Send(PAN_LEFT);
}

/*=======================================================================
 |
 |		�`���g��X�^�[�g����
 |
 |	void	Vcc1TiltUp()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1TiltUp()
{
	Cmd_Send(TILT_UP);
}

/*=======================================================================
 |
 |		�`���g���X�^�[�g����
 |
 |	void	Vcc1TiltDown()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1TiltDown()
{
	Cmd_Send(TILT_DOWN);
}

/*=======================================================================
 |
 |		ZOOM STOP����
 |
 |	void	Vcc1ZoomStop()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ZoomStop()
{
	Cmd_Send(ZOOM_STOP);
}

/*=======================================================================
 |
 |		ZOOM WIDE����
 |
 |	void	Vcc1ZoomWide()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ZoomWide()
{
	Cmd_Send(ZOOM_WIDE);
}

/*=======================================================================
 |
 |		ZOOM TELE����
 |
 |	void	Vcc1ZoomTele()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ZoomTele()
{
	Cmd_Send(ZOOM_TELE);
}

/*=======================================================================
 |
 |		ZOOM WIDE HIGHT ����
 |
 |	void	Vcc1ZoomWide()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ZoomWide2()
{
	Cmd_Send(ZOOM_WIDE2);
}

/*=======================================================================
 |
 |		ZOOM TELE����
 |
 |	void	Vcc1ZoomTele()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ZoomTele2()
{
	Cmd_Send(ZOOM_TELE2);
}

/*=======================================================================
 |
 |		POWER ON ����
 |
 |	void	Vcc1PowerOn()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1PowerOn()
{
	Cmd_Send(POWER_ON);
}

/*=======================================================================
 |
 |		POWER OFF ����
 |
 |	void	Vcc1PowerOn()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1PowerOff()
{
	Cmd_Send(POWER_OFF);
}

/*=======================================================================
 |
 |		FOCUS_AF ����
 |
 |	void	Vcc1FocusAf()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1FocusAf()
{
	Cmd_Send(FOCUS_AF);
}

/*=======================================================================
 |
 |		FOCUS_MANUAL ����
 |
 |	void	Vcc1FocusManual()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1FocusManual()
{
	Cmd_Send(FOCUS_MANUAL);
}

/*=======================================================================
 |
 |		FOCUS_NEAR ����
 |
 |	void	Vcc1FocusNear()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1FocusNear()
{
	Cmd_Send(FOCUS_NEAR);
}

/*=======================================================================
 |
 |		FOCUS_FAR ����
 |
 |	void	Vcc1FocusFar()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1FocusFar()
{
	Cmd_Send(FOCUS_FAR);
}

/*=======================================================================
 |
 |		SHUTTER PROGRAM MODE ����
 |
 |	void	Vcc1ShutPro()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ShutPro()
{
	Cmd_Send(SHUT_PRO);
}

/*=======================================================================
 |
 |		SHUTTER SPEED 1/60sec ����
 |
 |	void	Vcc1Shut60()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Shut60()
{
	Cmd_Send(SHUT_60);
}

/*=======================================================================
 |
 |		SHUTTER SPEED 1/100sec ����
 |
 |	void	Vcc1Shut100()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Shut100()
{
	Cmd_Send(SHUT_100);
}

/*=======================================================================
 |
 |		INIT1 ����
 |
 |	void	Vcc1Init1()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Init1()
{
	Cmd_Send(INIT1);
}

/*=======================================================================
 |
 |		INIT2 ����
 |
 |	void	Vcc1Init2()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1Init2()
{
	Cmd_Send(INIT2);
}

/*=======================================================================
 |
 |		�t���␳OFF ����
 |
 |	void	Vcc1HoseiOff()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1HoseiOff()
{
	Cmd_Send(HOSEI_OFF);
}

/*=======================================================================
 |
 |		�t���␳ON ����
 |
 |	void	Vcc1HoseiOn()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1HoseiOn()
{
	Cmd_Send(HOSEI_ON);
}

/*=======================================================================
 |
 |		�z���C�g�o�����X�m�[�}�� ����
 |
 |	void	Vcc1WhiteNormal()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1WhiteNormal()
{
	Cmd_Send(WHITE_NORMAL);
}

/*=======================================================================
 |
 |		�z���C�g�o�����X���b�N ����
 |
 |	void	Vcc1WhiteLock()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1WhiteLock()
{
	Cmd_Send(WHITE_LOCK);
}

/*=======================================================================
 |
 |		�����R���n�m ����
 |
 |	void	Vcc1ControlerOn()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ControlerOn()
{
	Cmd_Send(CONTROLER_ON);
}

/*=======================================================================
 |
 |		�����R���n�e�e ����
 |
 |	void	Vcc1ControlerOff()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ControlerOff()
{
	Cmd_Send(CONTROLER_OFF);
}

/*=======================================================================
 |
 |		�����R���X���[���[�h�n�m ����
 |
 |	void	Vcc1ThrowOn()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ThrowOn()
{
	Cmd_Send(THROW_ON);
}

/*=======================================================================
 |
 |		�����R���X���[���[�hOFF ����
 |
 |	void	Vcc1ThrowOn()
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1ThrowOff()
{
	Cmd_Send(THROW_OFF);
}

/*=======================================================================
 |
 |		�p�����[�^���x�ݒ� ����
 |
 |	void	Vcc1SetPanSpeed(int speed)
 |
 |			int	speed (100-500)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1SetPanSpeed(int speed)
{
	if (speed < 100 || speed > 500)
		return;

	Cmd_Param_Send(PAN_SPEED, speed, 3);
}

/*=======================================================================
 |
 |		�`���g���[�^���x�ݒ� ����
 |
 |	void	Vcc1SetTiltSpeed(int speed)
 |
 |			int	speed (100-500)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1SetTiltSpeed(int speed)
{
	if (speed < 100 || speed > 500)
		return;

	Cmd_Param_Send(TILT_SPEED, speed, 3);
}

/*=======================================================================
 |
 |		�p���p�x�ݒ� ����
 |
 |	void	Vcc1SetPanPos(int pos)
 |
 |			int	pos (0-1339)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1SetPanPos(int pos)
{
	if (pos < 0 || pos > 1339)
		return;

	Cmd_Param_Send(PAN_POS, pos, 3);
}

/*=======================================================================
 |
 |		�`���g�p�x�ݒ� ����
 |
 |	void	Vcc1SetTiltPos(int pos)
 |
 |			int	pos (0-650)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1SetTiltPos(int pos)
{
	if (pos < 0 || pos > 650)
		return;

	Cmd_Param_Send(TILT_POS, pos, 3);
}

/*=======================================================================
 |
 |		�Y�[���ʒu�ݒ� ����
 |
 |	void	Vcc1SetZoomPos(int pos)
 |
 |			int	pos (0-128)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1SetZoomPos(int pos)
{
	if (pos < 0 || pos > 127)
		return;

	Cmd_Param_Send(ZOOM_POS, pos, 2);
}

/*=======================================================================
 |
 |		���݈ʒu���v���Z�b�g�w��ԍ��ɓo�^ ����
 |
 |	void	Vcc1SavePos(int no)
 |
 |			int	no (1-6)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1SavePos(int no)
{
	if (no < 1 || no > 6)
		return;

	Cmd_Param_Send(SAVE_POS, no, 1);
}

/*=======================================================================
 |
 |		�v���Z�b�g�w��ԍ��̈ʒu�Ɉړ� ����
 |
 |	void	Vcc1RestorePos(int no)
 |
 |			int	no (1-6)
 |
 =======================================================================*/
DllExport void APIENTRY Vcc1RestorePos(int no)
{
	if (no < 1 || no > 6)
		return;

	Cmd_Param_Send(RESTORE_POS, no, 1);
}

/*=======================================================================
 |
 |		�p�����[�^���x�v�� ����
 |
 |	int		Vcc1GetPanSpeed()
 |
 |			�Ԓl�@	speed (100-500)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetPanSpeed()
{
	return(Cmd_Request_Send(R_PAN_SPEED));
}

/*=======================================================================
 |
 |		�`���g���[�^���x�v�� ����
 |
 |	int		Vcc1GetTiltSpeed()
 |
 |			�Ԓl	speed (100-500)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetTiltSpeed()
{
	return(Cmd_Request_Send(R_TILT_SPEED));
}

/*=======================================================================
 |
 |		�p���p�x�v�� ����
 |
 |	int		Vcc1GetPanPos()
 |
 |			�Ԓl	pos (0-1339)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetPanPos()
{
	return(Cmd_Request_Send(R_PAN_POS));
}

/*=======================================================================
 |
 |		�`���g�p�x�v�� ����
 |
 |	int		Vcc1GetTiltPos()
 |
 |			�Ԓl	pos (0-650)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetTiltPos()
{
	return(Cmd_Request_Send(R_TILT_POS));
}

/*=======================================================================
 |
 |		�Y�[���ʒu�v�� ����
 |
 |	int		Vcc1GetZoomPos()
 |
 |			�Ԓl	pos (0-128)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetZoomPos()
{
	return(Cmd_Request_Chr(0xA4));
}

/*=======================================================================
 |
 |		����X�e�[�^�X�v�� ����
 |
 |	int		Vcc1GetStatus()
 |
 |			�Ԓl	pos (0-128)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetStatus()
{
	return(Cmd_Request_Chr(0x86));
}

/*=======================================================================
 |
 |		�v���Z�b�g�X�e�[�^�X�v�� ����
 |
 |	int		Vcc1GetPresetStatus()
 |
 |			�Ԓl	pos (0-128)
 |
 =======================================================================*/
DllExport int APIENTRY Vcc1GetPresetStatus()
{
	return(Cmd_Request_Chr(0x8B));
}


/*=======================================================================
 |
 |		vcc1�I�[�v���p�����[�^�Z�b�g
 |
 |	VOID	SetPrm(LPRSPRM rp);
 |
 |		LPRSPRM	rp;	�p�����[�^�\���̃|�C���^
 |
 =======================================================================*/
static	VOID	SetPrm(LPRSPRM rp, int com)
{
	rp->comno = com;
	rp->duplex = DUPLEX_FULL;	/* �S��d */
	rp->blt = CBR_9600;			/* �{�[���[�g */
	rp->parity = NOPARITY;		/* �p���e�B�[ */
	rp->chr_len = 8;			/* �L�����N�^�� */
	rp->stp_len = TWOSTOPBITS;	/* �X�g�b�v�r�b�g�� */
	rp->buf_size = 2048;		/* ��M�o�b�t�@�T�C�Y */
}

static	VOID	Cmd_Chr(BYTE c)
{
	BYTE	buf[2];
	int	ans;
	int	sts;

	buf[0] = 0x00;
	buf[1] = c;

	if (! RsCts()){
		return;
	}
	RsSend(HEADER, 3);
	RsSend((LPSTR)buf, 2);
	RsSend(TERMINATOR, 1);
	sts = GetAns((LPINT)&ans);
}

static	int	Cmd_Request_Chr(BYTE c)
{
	BYTE	buf[2];
	int	ans, sts;

	buf[0] = 0x00;
	buf[1] = c;

	if (! RsCts()){
		return(0x1000);
	}
	RsSend(HEADER, 3);
	RsSend((LPSTR)buf, 2);
	RsSend(TERMINATOR, 1);

	sts = GetAns((LPINT)&ans);
	switch(sts){
	case -1:
		ans |= 0x1000;
		break;
	case 0:
		break;
	default:
		ans = -1;
		break;
	}
	return(ans);
}

static	VOID	Cmd_Send(int no)
{
	LPSTR p;
	int	ans;
	int	sts;

	p = command_tbl[no];

	if (! RsCts()){
		return;
	}
	RsSend(HEADER, 3);
	RsSend(p, 3);
	RsSend(TERMINATOR, 1);

	sts = GetAns((LPINT)&ans);
}

static	VOID	Cmd_Param_Send(int no, int Param, int len)
{
	LPSTR p;
	int	ans, sts;
	char	str[10];

	itoa_hex((LPSTR)str, (WORD)Param, len);
	p = scommand_tbl[no];

	if (! RsCts()){
		return;
	}
	RsSend(HEADER, 3);
	RsSend(p, 2);
	RsSend(str, len);
	RsSend(TERMINATOR, 1);

	sts = GetAns((LPINT)&ans);
}

static	int	Cmd_Request_Send(int no)
{
	LPSTR p;
	int	ans, sts;

	p = rcommand_tbl[no];

	if (! RsCts()){
		return(0x1000);
	}
	RsSend(HEADER, 3);
	RsSend(p, 3);
	RsSend(TERMINATOR, 1);

	sts = GetAns((LPINT)&ans);
	switch(sts){
	case -1:
		ans |= 0x1000;
		break;
	case 0:
		break;
	default:
		ans = -1;
		break;
	}
	return(ans);
}

static	int	GetAns(LPINT ans)
{
	DWORD	c;
	int		cnt, rcnt, link, rt;
	BYTE	buf[128];
	BYTE	sts[2];

	memset((LPSTR)sts, '0', sizeof(sts));

	rcnt = cnt = 0;
	link = 0;

	TmSet(100);

	for (;;){
		if (TmCheck()){
			*ans = 0;
			return(-1);		/* ��ѱ�� */
		}

		c = RsGet();
		if (c == 0xffff)
			return(-1);		/* READ�G���[ */

		if (c & 0xff00)
			continue;		/* ��ײ */

		if (c == 0xef)
			break;
		else if (c == 0xfe){
			link = 1;
			continue;
		}
		if (link){
			rcnt++;
			if (rcnt == 1 || rcnt == 2)
				continue;
			else if (rcnt == 3 || rcnt == 4){
				sts[rcnt - 3] = (BYTE)c;
			} else
				buf[cnt++] = (BYTE)c;
		}
	}

	/* �G���[�X�e�[�^�X�ҏW */
	rt = 0;
	rt = (max(sts[1] - '0', 0) * 0x100)+(max(sts[0] - '0', 0));
	*ans = atoi_hex((LPSTR)buf, cnt);

	return(rt);
}

static	void	itoa_hex(LPSTR str, WORD value, int keta)
{
	static	char	hex[] = "0123456789ABCDEF";

	while (keta--) {
		*(str + keta) = hex[value & 0x0f];
		value >>= 4;
	}
}

/*=======================================================================
 |
 |		�g�d�w������𐔒l�ɕҏW����
 |
 |	int	atoi_hex(LPSTR str, int len);
 |
 |		LPSTR	str;		�o�b�t�@
 |		int	len;			������
 |
 |		�Ԃ�l		���l
 |
 =======================================================================*/
static	int	atoi_hex(LPSTR str, int len)
{
	int		i, rt;
	WORD	dt;

	if (len <= 0)
		return(0);

	rt = i = 0;
	dt = 0x01;

	while(len--){
		rt += atonum(str[len]) * dt;
		i++;
		if (i == 4)
			break; 
		dt <<= 4;
	}

	return(rt);
}

/*=======================================================================
 |
 |		�P�U�i�����i�f�O�f�[�f�e�f�j�𐔎��ɕϊ�����
 |
 |	int	atonum(char c);
 |
 |		char	c;		����
 |
 |		�Ԃ�l	�i�O�|�P�T�j�@�Ώە����ȊO�@�O
 |
 =======================================================================*/
static	int	atonum(char c)
{
	static	char	tbl[] = "0123456789ABCDEF";
	int	i;
	char	uc;

	uc = (char)(toupper(c) & 0xff);

	for(i = 0; tbl[i] != '\0'; i++){
		if (tbl[i] == uc)
			return(i);
	}
	return(0);
}

#ifdef __cplusplus
}
#endif
