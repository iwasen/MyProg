/************************************************************************
 |
 |		����		: VCC1 ���C�u�����@�w�b�_�t�@�C��
 |
 |		�t�@�C����	: vcc1.h
 |
 |		m.hama�@95/12/22		
 |				96/01/05	Vcc1Open ���Ұ��ǉ�
 |
 ************************************************************************/

#define DllExport   __declspec( dllexport )

#define	VCC1_COM1	0
#define	VCC1_COM2	1
#define	VCC1_COM3	2

#ifdef __cplusplus
extern "C" {
#endif

extern DllExport int	APIENTRY Vcc1Open(int);				/* VCC1����������(0:COM1 1:COM2 2:COM3) */
extern DllExport void	APIENTRY Vcc1Close(VOID);			/* VCC1�I������(ONLY RS CLOSE) */
extern DllExport void	APIENTRY Vcc1Reset(VOID);			/* �J����RESET���� */
extern DllExport void	APIENTRY Vcc1Home(VOID);			/* HOME �ʒu�ړ����� */
extern DllExport void	APIENTRY Vcc1Stop(VOID);			/* �p���A�`���g��~���� */
extern DllExport void	APIENTRY Vcc1PanRight(VOID);		/* �p���E�X�^�[�g���� */
extern DllExport void	APIENTRY Vcc1PanLeft(VOID);			/* �p�����X�^�[�g���� */
extern DllExport void	APIENTRY Vcc1TiltUp(VOID);			/* �`���g��X�^�[�g���� */
extern DllExport void	APIENTRY Vcc1TiltDown(VOID);		/* �`���g���X�^�[�g���� */
extern DllExport void	APIENTRY Vcc1ZoomStop(VOID);		/* ZOOM STOP���� */
extern DllExport void	APIENTRY Vcc1ZoomWide(VOID);		/* ZOOM WIDE���� */
extern DllExport void	APIENTRY Vcc1ZoomTele(VOID);		/* ZOOM TELE���� */
extern DllExport void	APIENTRY Vcc1ZoomWide2(VOID);		/* ZOOM WIDE ���� ���� */
extern DllExport void	APIENTRY Vcc1ZoomTele2(VOID);		/* ZOOM TELE ���� ���� */
extern DllExport void	APIENTRY Vcc1PowerOn(VOID);			/* �_�� POWER ON ���� */
extern DllExport void	APIENTRY Vcc1PowerOff(VOID);		/* �_�� POWER OFF ���� */
extern DllExport void	APIENTRY Vcc1FocusAf(VOID);			/* FOCUS_AF ���� */
extern DllExport void	APIENTRY Vcc1FocusManual(VOID);		/* FOCUS_MANUAL ���� */	
extern DllExport void	APIENTRY Vcc1FocusNear(VOID);		/* FOCUS_NEAR ���� */
extern DllExport void	APIENTRY Vcc1FocusFar(VOID);		/* FOCUS_FAR ���� */
extern DllExport void	APIENTRY Vcc1ShutPro(VOID);			/* SHUTTER PROGRAM MODE ���� */
extern DllExport void	APIENTRY Vcc1Shut60(VOID);			/* SHUTTER SPEED 1/60sec ���� */
extern DllExport void	APIENTRY Vcc1Shut100(VOID);			/* SHUTTER SPEED 1/100sec ���� */
extern DllExport void	APIENTRY Vcc1Init1(VOID);			/* �_�䏉����1 ���� */
extern DllExport void	APIENTRY Vcc1Init2(VOID);			/* �_�䏉����2 ���� */
extern DllExport void	APIENTRY Vcc1HoseiOff(VOID);		/* �t���␳OFF ���� */
extern DllExport void	APIENTRY Vcc1HoseiOn(VOID);			/* �t���␳ON ���� */
extern DllExport void	APIENTRY Vcc1WhiteNormal(VOID);		/* �ܲ����ݽɰ�� ���� */
extern DllExport void	APIENTRY Vcc1WhiteLock(VOID);		/* �ܲ����ݽۯ� ���� */
extern DllExport void	APIENTRY Vcc1ControlerOn(VOID);		/* �����R���n�m ���� */
extern DllExport void	APIENTRY Vcc1ControlerOff(VOID);	/* �����R���n�e�e ���� */
extern DllExport void	APIENTRY Vcc1ThrowOn(VOID);			/* �����R���X���[���[�hON ���� */
extern DllExport void	APIENTRY Vcc1ThrowOff(VOID);		/* �����R���X���[���[�hOFF ���� */

/* �ݒ�R�}���h */
extern DllExport void	APIENTRY Vcc1SetPanSpeed(int);		/* �p�����[�^���x�ݒ� (100-500) */
extern DllExport void	APIENTRY Vcc1SetTiltSpeed(int);		/* �`���g���[�^���x�ݒ� (100-500) */
extern DllExport void	APIENTRY Vcc1SetPanPos(int);		/* �p���ʒu�ݒ� (0-1339) */
extern DllExport void	APIENTRY Vcc1SetTiltPos(int);		/* �`���g�ʒu�ݒ� (0-650) */
extern DllExport void	APIENTRY Vcc1SetZoomPos(int);		/* �Y�[���ʒu�ݒ� (0-128) */
extern DllExport void	APIENTRY Vcc1SavePos(int);			/* ���݈ʒu���v���Z�b�g�w��ԍ��ɓo�^(1-6) */
extern DllExport void	APIENTRY Vcc1RestorePos(int);		/* �v���Z�b�g�w��ԍ��̈ʒu�Ɉړ� (1-6) */

/* �v���R�}���h */
extern DllExport int	APIENTRY Vcc1GetPanSpeed(VOID);		/* �p�����[�^���x�v�� (100-500) */
extern DllExport int	APIENTRY Vcc1GetTiltSpeed(VOID);	/* �`���g���[�^���x�v�� (100-500) */
extern DllExport int	APIENTRY Vcc1GetPanPos(VOID);		/* �p���ʒu�v�� (0-1339) */
extern DllExport int	APIENTRY Vcc1GetTiltPos(VOID);		/* �`���g�ʒu�v�� (0-650) */
extern DllExport int	APIENTRY Vcc1GetZoomPos(VOID);		/* �Y�[���ʒu�v�� (0-128) */
extern DllExport int	APIENTRY Vcc1GetStatus(VOID);		/* ����X�e�[�^�X�v�� */
extern DllExport int	APIENTRY Vcc1GetPresetStatus(VOID);	/* �v���Z�b�g�X�e�[�^�X�v�� */

#ifdef __cplusplus
}
#endif
