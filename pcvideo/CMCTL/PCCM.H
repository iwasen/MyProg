/*	�J��������R�}���h 	*/
#define	CM_HOME_POSITION	0	// �z�[���|�W�V�����i�����j�Ɉړ�
#define	CM_PAN_RIGHT		1	// �E�����Ɉړ��J�n
#define	CM_PAN_LEFT		2	// �������Ɉړ��J�n
#define	CM_TILT_UP		3	// ������Ɉړ��J�n
#define	CM_TILT_DOWN		4	// �������Ɉړ��J�n
#define	CM_STOP			5	// �J���������ړ���~
#define	CM_ZOOM_WIDE		6	// ���C�h�i�L�p�j���փY�[���J�n
#define	CM_ZOOM_TELE		7	// �e���i�]���j���փY�[���J�n
#define	CM_ZOOM_STOP		8	// �Y�[����~
#define	CM_POWER_ON		9	// �p���[�I��
#define	CM_POWER_OFF		10	// �p���[�I�t

#define	STATUS_FREE
#define	STATUS_LOCAL
#define	STATUS_NETWORK

#define	N_PRESET	8

#define	CMENTRY	__pascal __far __export __loadds

/*	�J��������u���b�N	*/
typedef	struct	{
	int	status;
	int	sock;
	int	port;
} CCB;

/*	�J�����I�v�V�����f�[�^	*/
typedef	struct	{
	int	panSpeed;
	int	tiltSpeed;
	int	zoomSpeed;
	int	shutterSpeed;
	int	audioVolume;
	BOOL	bAutoFocus;
	BOOL	bLightAdjust;
	BOOL	bAutoWhiteBalance;
	BOOL	bWideConverter;
} CAMERAOPT, *PCAMERAOPT;

/*	�J�����ʒu���		*/
typedef	struct	{
	int	pan;
	int	tilt;
	int	zoom;
} CAMERAPOS;

/*	�v���Z�b�g���		*/
typedef	struct	{
	CAMERAPOS pos;
	char	comment[31];
} PRESET;

/*	ini �t�@�C�����	*/
typedef	struct	{
	CAMERAOPT opt;
	PRESET	preset[N_PRESET];
} INIDATA;