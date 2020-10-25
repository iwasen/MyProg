/************************************************************************
 *									*
 *			�J�����R���g���[���c�k�k			*
 *									*
 *		����		: ���ʃw�b�_�t�@�C��			*
 *		�t�@�C����	: cmctl.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

/*	�J��������R�}���h 	*/
#define	CM_RESET		0	/* ���Z�b�g */
#define	CM_HOME_POSITION	1	/* �z�[���|�W�V�����i�����j�Ɉړ� */
#define	CM_PAN_RIGHT		2	/* �E�����Ɉړ��J�n */
#define	CM_PAN_LEFT		3	/* �������Ɉړ��J�n */
#define	CM_TILT_UP		4	/* ������Ɉړ��J�n */
#define	CM_TILT_DOWN		5	/* �������Ɉړ��J�n */
#define	CM_STOP			6	/* �J���������ړ���~ */
#define	CM_ZOOM_WIDE		7	/* ���C�h�i�L�p�j���փY�[���J�n */
#define	CM_ZOOM_TELE		8	/* �e���i�]���j���փY�[���J�n */
#define	CM_ZOOM_STOP		9	/* �Y�[����~ */
#define	CM_FOCUS_AUTO		10	/* �I�[�g�t�H�[�J�X */
#define	CM_FOCUS_MANUAL		11	/* �}�j���A���t�H�[�J�X */
#define	CM_FOCUS_NEAR		12	/* �t�H�[�J�X NEAR�i�߁j */
#define	CM_FOCUS_FAR		13	/* �t�H�[�J�X FAR�i���j */
#define	CM_POWER_ON		14	/* �p���[�I�� */
#define	CM_POWER_OFF		15	/* �p���[�I�t */

/*	�r�f�I�T�C�Y	*/
#define	SIZE_40_30	0	/* 40 x 30 */
#define	SIZE_80_60	1	/* 80 x 60 */
#define	SIZE_120_90	2	/* 120 x 90 */
#define	SIZE_160_120	3	/* 160 x 120 */
#define	SIZE_200_150	4	/* 200 x 150 */
#define	SIZE_240_180	5	/* 240 x 180 */
#define	SIZE_280_210	6	/* 280 x 210 */
#define	SIZE_320_240	7	/* 320 x 240 */
#define	SIZE_360_270	8	/* 360 x 270 */
#define	SIZE_400_300	9	/* 400 x 300 */
#define	SIZE_440_330	10	/* 440 x 330 */
#define	SIZE_480_360	11	/* 480 x 360 */
#define	SIZE_520_390	12	/* 520 x 390 */
#define	SIZE_560_420	13	/* 560 x 420 */
#define	SIZE_600_450	14	/* 600 x 450 */
#define	SIZE_640_480	15	/* 640 x 480 */
#define	SIZE_MIN	SIZE_40_30
#define	SIZE_MAX	SIZE_640_480

/*	�r�f�I�J���[���[�h	*/
#define	COLOR_8		0		/* 256 �F���m�N�� */
#define	COLOR_16	1		/* 64k �F�J���[ */
#define	COLOR_24	2		/* 16M �F�J���[ */

/*	�G���[�R�[�h	*/
#define	ERROR_CM_TOO_MANY_OPEN	1000	/* �I�[�v�������������� */
#define	ERROR_CM_VIDEO_OPEN	1001	/* �r�f�I�L���v�`���̃I�[�v���Ɏ��s */
#define	ERROR_CM_PORT_OPEN	1002	/* RS-232C �|�[�g�̃I�[�v���Ɏ��s */
#define	ERROR_CM_NOANSWER	1003	/* �J�������牞�����Ȃ� */
#define	ERROR_CM_INVALID_HANDLE	1004	/* �n���h�����s�� */
#define	ERROR_CM_INVALID_COMMAND 1005	/* CMControl() �̃R�}���h���s�� */
#define	ERROR_CM_PRESET_NO	1006	/* �v���Z�b�g�m�����s�� */
#define	ERROR_CM_VIDEO_CONFIG	1007	/* �L���v�`���{�[�h�̐ݒ�Ɏ��s */
#define	ERROR_CM_VIDEO_CAPTURE	1008	/* �L���v�`���Ɏ��s */
#define	ERROR_CM_CAPTURE_MODE	1009	/* �L���v�`�����[�h�̐ݒ肪�s�� */
#define	ERROR_CM_CAMERA_CONTROL	1010	/* �J�����R���g���[�����ł��Ȃ� */
#define	ERROR_CM_WINSOCK	1011	/* winsock.dll �����[�h�ł��Ȃ� */
#define	ERROR_CM_HOSTNAME	1012	/* �z�X�g�����s�� */
#define	ERROR_CM_SOCKET		1013	/* �\�P�b�g�̃I�[�v���Ɏ��s */
#define	ERROR_CM_CONxxxT	1014	/* PC-VIDEO �ւ̐ڑ��Ɏ��s */
#define	ERROR_CM_SEND		1015	/* �f�[�^���M�ŃG���[ */
#define	ERROR_CM_RECEIVE	1016	/* �f�[�^��M�ŃG���[ */
#define	ERROR_CM_NOCAPTURE	1017	/* PC-VIDEO ���r�f�I���M�����Ă��Ȃ� */
#define	ERROR_CM_MEMORY		1018	/* �������s�� */
#define	ERROR_CM_NOSUPPORT	1019	/* �J�����R���g���[���̃T�|�[�g�Ȃ� */
#define	ERROR_CM_DENY		1020	/* �J�����R���g���[�����֎~ */
#define	ERROR_CM_LOCK		1021	/* �������b�N�� */

#define	MAX_PRESET_NO	8		/* �ő�v���Z�b�g�m�� */

#define	CMENTRY	__pascal __far __export __loadds

/*	�J�����I�v�V�����f�[�^	*/
typedef	struct	{
	int	panSpeed;		/* �p�����x */
	int	tiltSpeed;		/* �`���g���x */
	int	zoomSpeed;		/* �Y�[�����x */
	int	shutterSpeed;		/* �V���b�^�[���x */
	int	autoFocus;		/* �I�[�g�t�H�[�J�X */
	int	lightAdjust;		/* �t���␳ */
	int	autoWhiteBalance;	/* �����z���C�g�o�����X */
	int	wideConverter;		/* ���C�h�R���o�[�^�g�p */
} CAMERAOPT, FAR *LPCAMERAOPT;

/*	�J�����ʒu���		*/
typedef	struct	{
	int	pan;			/* �p���ʒu */
	int	tilt;			/* �`���g�ʒu */
	int	zoom;			/* �Y�[���ʒu */
} CAMERAPOS, FAR *LPCAMERAPOS;

/*	�v���Z�b�g���		*/
typedef	struct	{
	CAMERAPOS pos;			/* �v���Z�b�g�ʒu */
	char	comment[31];		/* �R�����g */
} PRESET, FAR *LPPRESET;

/*	�r�f�I�L���v�`�����[�h	*/
typedef	struct	{
	int	size;			/* �r�f�I�T�C�Y */
	int	color;			/* �F�K�� */
	int	compressMode;		/* ���k���[�h */
	int	compressQuality;	/* ���k���̉掿 */
} CAPTUREMODE, FAR *LPCAPTUREMODE;

#ifdef __cplusplus
extern "C" {            /* Assume C declarations for C++ */
#endif  /* __cplusplus */

extern	int	CMENTRY	CMOpen(LPSTR hostName, LPSTR userName, int option,
								LPINT handle);
extern	int	CMENTRY	CMClose(int handle);
extern	int	CMENTRY	CMControl(int handle, int command);
extern	int	CMENTRY	CMGetCameraOpt(int handle, LPCAMERAOPT cameraOpt);
extern	int	CMENTRY	CMSetCameraOpt(int handle, LPCAMERAOPT opt);
extern	int	CMENTRY	CMGetPreset(int handle, LPPRESET preset);
extern	int	CMENTRY	CMSetPreset(int handle, LPPRESET preset, int presetNo);
extern	int	CMENTRY	CMMovePreset(int handle, int presetNo);
extern	int	CMENTRY	CMGetCameraPos(int handle, LPCAMERAPOS pos);
extern	int	CMENTRY	CMSetCameraPos(int handle, LPCAMERAPOS pos);
extern	int	CMENTRY CMLock(int handle, int lockFlag);
extern	int	CMENTRY CMCaptureVideo(int handle, LPCAPTUREMODE captureMode,
								LPSTR buf);
extern	int	CMENTRY CMDraw(HDC hDC, LPCAPTUREMODE captureMode, LPSTR buf);

#ifdef __cplusplus
}                       /* End of extern "C" { */
#endif  /* __cplusplus */