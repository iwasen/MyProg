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

#define	VCC1_COM1	0
#define	VCC1_COM2	1
#define	VCC1_COM3	2


extern	int		PASCAL Vcc1Open(int);			/* VCC1����������(0:COM1 1:COM2 2:COM3) */
extern	void	PASCAL Vcc1Close(VOID);			/* VCC1�I������(ONLY RS CLOSE) */
extern	void	PASCAL Vcc1Reset(VOID);			/* �J����RESET���� */
extern	void	PASCAL Vcc1Home(VOID);			/* HOME �ʒu�ړ����� */
extern	void	PASCAL Vcc1Stop(VOID);			/* �p���A�`���g��~���� */
extern	void	PASCAL Vcc1PanRight(VOID);		/* �p���E�X�^�[�g���� */
extern	void	PASCAL Vcc1PanLeft(VOID);		/* �p�����X�^�[�g���� */
extern	void	PASCAL Vcc1TiltUp(VOID);		/* �`���g��X�^�[�g���� */
extern	void	PASCAL Vcc1TiltDown(VOID);		/* �`���g���X�^�[�g���� */
extern	void	FAR PASCAL Vcc1ZoomStop(VOID);		/* ZOOM STOP���� */
extern	void	PASCAL Vcc1ZoomWide(VOID);		/* ZOOM WIDE���� */
extern	void	PASCAL Vcc1ZoomTele(VOID);		/* ZOOM TELE���� */
extern	void	PASCAL Vcc1ZoomWide2(VOID);		/* ZOOM WIDE ���� ���� */
extern	void	PASCAL Vcc1ZoomTele2(VOID);		/* ZOOM TELE ���� ���� */
extern	void	PASCAL Vcc1PowerOn(VOID);		/* �_�� POWER ON ���� */
extern	void	PASCAL Vcc1PowerOff(VOID);		/* �_�� POWER OFF ���� */
extern	void	PASCAL Vcc1FocusAf(VOID);		/* FOCUS_AF ���� */
extern	void	PASCAL Vcc1FocusManual(VOID);	/* FOCUS_MANUAL ���� */	
extern	void	PASCAL Vcc1FocusNear(VOID);		/* FOCUS_NEAR ���� */
extern	void	PASCAL Vcc1FocusFar(VOID);		/* FOCUS_FAR ���� */
extern	void	PASCAL Vcc1ShutPro(VOID);		/* SHUTTER PROGRAM MODE ���� */
extern	void	PASCAL Vcc1Shut60(VOID);		/* SHUTTER SPEED 1/60sec ���� */
extern	void	PASCAL Vcc1Shut100(VOID);		/* SHUTTER SPEED 1/100sec ���� */
extern	void	PASCAL Vcc1Init1(VOID);			/* �_�䏉����1 ���� */
extern	void	PASCAL Vcc1Init2(VOID);			/* �_�䏉����2 ���� */
extern	void	PASCAL Vcc1HoseiOff(VOID);		/* �t���␳OFF ���� */
extern	void	PASCAL Vcc1HoseiOn(VOID);		/* �t���␳ON ���� */
extern	void	PASCAL Vcc1WhiteNormal(VOID);	/* �ܲ����ݽɰ�� ���� */
extern	void	PASCAL Vcc1WhiteLock(VOID);		/* �ܲ����ݽۯ� ���� */
extern	void	PASCAL Vcc1ControlerOn(VOID);	/* �����R���n�m ���� */
extern	void	PASCAL Vcc1ControlerOff(VOID);	/* �����R���n�e�e ���� */
extern	void	PASCAL Vcc1ThrowOn(VOID);		/* �����R���X���[���[�hON ���� */
extern	void	PASCAL Vcc1ThrowOff(VOID);		/* �����R���X���[���[�hOFF ���� */

/* �ݒ�R�}���h */
extern	void	PASCAL Vcc1SetPanSpeed(int);	/* �p�����[�^���x�ݒ� (100-500) */
extern	void	PASCAL Vcc1SetTiltSpeed(int);	/* �`���g���[�^���x�ݒ� (100-500) */
extern	void	PASCAL Vcc1SetPanPos(int);		/* �p���ʒu�ݒ� (0-1339) */
extern	void	PASCAL Vcc1SetTiltPos(int);		/* �`���g�ʒu�ݒ� (0-650) */
extern	void	PASCAL Vcc1SetZoomPos(int);		/* �Y�[���ʒu�ݒ� (0-128) */
extern	void	PASCAL Vcc1SavePos(int);		/* ���݈ʒu���v���Z�b�g�w��ԍ��ɓo�^(1-6) */
extern	void	PASCAL Vcc1RestorePos(int);		/* �v���Z�b�g�w��ԍ��̈ʒu�Ɉړ� (1-6) */

/* �v���R�}���h */
extern	int		PASCAL Vcc1GetPanSpeed(VOID);	/* �p�����[�^���x�v�� (100-500) */
extern	int		PASCAL Vcc1GetTiltSpeed(VOID);	/* �`���g���[�^���x�v�� (100-500) */
extern	int		PASCAL Vcc1GetPanPos(VOID);		/* �p���ʒu�v�� (0-1339) */
extern	int		PASCAL Vcc1GetTiltPos(VOID);	/* �`���g�ʒu�v�� (0-650) */
extern	int		PASCAL Vcc1GetZoomPos(VOID);	/* �Y�[���ʒu�v�� (0-128) */
extern	int		PASCAL Vcc1GetStatus(VOID);		/* ����X�e�[�^�X�v�� */
extern	int		PASCAL Vcc1GetPresetStatus(VOID);	/* �v���Z�b�g�X�e�[�^�X�v�� */











































