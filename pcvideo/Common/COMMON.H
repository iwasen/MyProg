/************************************************************************
 *
 *			�o�b�|�u�h�c�d�n
 *
 *		����		: ���ʃw�b�_
 *		�t�@�C����	: common.h
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#define	PORTNO		5100
#define PORTNO2		10414
#define	LEN_HOSTNAME	64

typedef short INT16;
typedef unsigned short BOOL16;

#define MAX_PACKET_SIZE	1024

/*	�w�b�_�t���O	*/
#define	FLAG_CONxxxT	0x01		/* �ڑ� */
#define	FLAG_DISCONxxxT	0x02		/* �ؒf */
#define	FLAG_REMOTE		0x03		/* �����[�g�ݒ� */
#define	FLAG_LICENSE	0x04		/* ���C�Z���X�`�F�b�N */
#define FLAG_LOGINREQ	0x05		/* ���O�C���v�� */
#define FLAG_LOGIN		0x06		/* ���O�C�� */
#define FLAG_DENY		0x07		/* �ڑ����� */
#define	FLAG_VIDEO		0x0c		/* �r�f�I�f�[�^ */
#define	FLAG_CHAT		0x10		/* �`���b�g�f�[�^ */
#define	FLAG_VOICE		0x20		/* �{�C�X�f�[�^ */
#define	FLAG_SECOND		0x40		/* �Q�����|�[�g */
#define	FLAG_CALL		0x80		/* �Ăяo�� */

/*	�ڑ����[�h	*/
#define	MODE_SENDVIDEO	0x01		/* �r�f�I���M�L�� */
#define	MODE_DLLCALL	0x02		/* DLL �Ăяo�� */
#define	MODE_CHAT		0x04		/* �`���b�g�� */
#define	MODE_VIDEOREQ	0x08		/* �r�f�I�f�[�^��t */
#define	MODE_SENDVOICE	0x10		/* �{�C�X���s�� */
#define	MODE_VOICEREQ	0x20		/* �{�C�X�f�[�^��t */
#define	MODE_NEWVERSION	0x40		/* �V�o�[�W���� */
#define	MODE_REFLECT	0x80		/* ���t���N�^ */

/*	�r�f�I���[�h	*/
#define	VIDEO_PALETTE	0x01		/* �p���b�g���[�h (256�F) */
#define	VIDEO_FULLCOLOR	0x02		/* �t���J���[ */
#define	VIDEO_MONO		0x04		/* ���m�N�� */
#define	VIDEO_LOQUALITY1 0x10		/* ��i�����[�h�P */
#define	VIDEO_COMPXY	0x20		/* �u���b�N�ʒu���k */
#define	VIDEO_LOQUALITY2 0x40		/* ��i�����[�h�Q */
#define	VIDEO_DRAW		0x80		/* �E�B���h�E�`�� */

/*	�����[�g�ݒ�	*/
#define	REMOTE_REQDATA	0x01		/* �f�[�^�v�� */
#define	REMOTE_DATA		0x02		/* �f�[�^���M */
#define	REMOTE_SETDATA	0x03		/* �f�[�^�ݒ�v�� */
#define	REMOTE_DENY		0x04		/* �����[�g���ݒ苑�� */
#define	REMOTE_RESTART	0x05		/* �ċN�� */
#define	REMOTE_CHAT		0x06		/* CHAT �J�n�^�I�� */
#define	REMOTE_VOICE	0x07		/* VOICE �J�n�^�I�� */
#define	REMOTE_EXIT		0x08		/* PCVIDEO �I�� */
#define	REMOTE_SVOICE	0x09		/* �������M */
#define	REMOTE_MESSAGE	0x0a		/* ���b�Z�[�W */
#define	REMOTE_CAMERA_COMMAND 0x0b	/* �J�����R�}���h */
#define	REMOTE_CAMERA_STATUS 0x0c	/* �J�����X�e�[�^�X */
#define	REMOTE_CAMERA_SETINFO 0x0d	/* �J�������ݒ� */
#define	REMOTE_CAPTURE_COMMAND 0x0e	/* �����[�g�L���v�`���R�}���h */
#define	REMOTE_CAPTURE_RESPONSE 0x0f	/* �����[�g�L���v�`�����X�|���X */
#define	REMOTE_EXEC_COMMAND 0x10	/* �R�}���h���s */
#define REMOTE_DOWNLOAD	0x11		/* �_�E�����[�h */

/*	���C�Z���X�`�F�b�N	*/
#define	LICENSE_CHECK	0x01		/* ���C�Z���X�`�F�b�N */
#define	LICENSE_REPLY	0x02		/* ���C�Z���X�`�F�b�N���� */

/*	�Ăяo�������^�C�v	*/
#define	TYPE_CHAT	0x10
#define	TYPE_VOICE	0x20

/*	�Ăяo���v���E�����X�e�[�^�X	*/
#define	ST_CALL		0x01
#define	ST_ANSWER	0x02

/*	�Ăяo���X�e�[�^�X	*/
#define	ST_OK			0x01
#define	ST_CANCEL		0x02
#define	ST_ACTIVE		0x04
#define	ST_NOSUPPORT	0x08

#define	CHANNEL_GLOBAL	255

#define	BLOCK_PIXEL	8

/*	�r�f�I�̐F��	*/
#define	COLOR_8		0
#define	COLOR_16	1
#define	COLOR_24	2

/*	�J�������	*/
#define	CAMERA_VCC1	1

/*	���ݒ�f�[�^	*/
struct	SETTINGS {
	char	userName[32];
	BOOL16	bVideoCapture;
	BOOL16	bReceiveVideo;
	BOOL16	bRemoteSetting;
	INT16	movieSize;
	INT16	stillSize;
	INT16	videoColor;
	INT16	videoQuality;
	INT16	maxBPS;
	BOOL16	bMaxSpeed;
	BOOL16	bSendInfo;
	BOOL16	bResize;
	BOOL16	bFixedAspect;
	BOOL16	bFixedSize;
	INT16	recvSize;
	BOOL16	bDispInfo;
	BOOL16	bFrontKeep;
	INT16	videoEdgeEnhance;
	BOOL16	bCameraChange;
	BOOL16	bFullFrame;
	INT16	cameraType;
	char	reserved[8];
};

/*	�J����(VC-C1)�ݒ�f�[�^	*/
struct	VCC1DATA {
	INT16	serialPort;
	INT16	panSpeed;
	INT16	tiltSpeed;
	INT16	zoomSpeed;
	INT16	shutterSpeed;
	INT16	audioVolume;
	BOOL16	bAutoFocus;
	BOOL16	bLightAdjust;
	BOOL16	bAutoWhiteBalance;
	BOOL16	bWideConverter;
	struct	{
		INT16	panPos;
		INT16	tiltPos;
		INT16	zoomPos;
		char	comment[31];
	} preset[8];
};

struct	CAMERA_STATUS	{
	INT16	status;
	INT16	panPos;
	INT16	tiltPos;
	INT16	zoomPos;
	char	userName[32];
	VCC1DATA vcc1;
};

struct	CAMERA_COMMAND	{
	char	command;
	INT16	param1;
	INT16	param2;
	INT16	param3;
};

/*	���ʃp�P�b�g�w�b�_	*/
typedef	struct	{
	char	flag;
	DWORD	id;
} HEADER, *PHEADER;

/*	�ڑ��p�P�b�g	*/
typedef	struct	{
	HEADER	header;
	char	userName[32];
	char	modeFlag;
	char	channelNo;
} CONxxxT, *PCONxxxT;

/*	���O�C���v���p�P�b�g	*/
typedef struct {
	HEADER	header;
	char	serverName[32];
} LOGINREQ, *PLOGINREQ;

/*	���O�C���p�P�b�g	*/
typedef struct {
	CONxxxT	conxxxt;
	char	loginName[32];
	char	password[32];
} LOGIN, *PLOGIN;

/*	�ڑ����ۃp�P�b�g	*/
typedef struct {
	HEADER	header;
	char	userName[32];
	char	message[256];
} DENY, *PDENY;

/*	�Ăяo���p�P�b�g	*/
typedef	struct	{
	HEADER	header;		/* ���ʃw�b�_ */
	DWORD	id;			/* �h�o�A�h���X�i�󂯑��j */
	char	type;		/* �����^�C�v */
						/*  TYPE_CHAT	0x10 */
						/*  TYPE_VOICE	0x20 */
	char	callans;	/* �v�� or ���� */
						/*  ST_CALL	0x01 */
						/*  ST_ANSWER	0x02 */
	char	status;		/* �X�e�[�^�X */
						/* 	ST_OK	0x01 */
						/*  ST_CANCEL	0x02 */
} CALLHDR, *PCALLHDR;

/*	�����[�g�Ǘ��p�P�b�g	*/
typedef	struct	{
	HEADER	header;
	char	type;
	DWORD	id;
} REMOTE, *PREMOTE;

/*	�����[�g�ݒ�p�P�b�g	*/
typedef	struct	{
	REMOTE	remote;
	SETTINGS	settings;
} REMOTE_SETTINGS, *PREMOTE_SETTINGS;

/*	�����[�g���b�Z�[�W�p�P�b�g	*/
typedef	struct	{
	REMOTE	remote;
	char	message[256];
} REMOTE_MSG, *PREMOTE_MSG;

/*	�����[�g�J�����R�}���h�p�P�b�g */
typedef	struct	{
	REMOTE	remote;
	CAMERA_COMMAND	command;
} REMOTE_CAMERA_CMD, *PREMOTE_CAMERA_CMD;

/*	�����[�g�J�����X�e�[�^�X�p�P�b�g */
typedef	struct	{
	REMOTE	remote;
	INT16	cameraType;
	CAMERA_STATUS	status;
} REMOTE_CAMERA_STS, *PREMOTE_CAMERA_STS;

/*	�����[�g�J�������ݒ�p�P�b�g */
typedef	struct	{
	REMOTE	remote;
	INT16	flag;
	VCC1DATA vcc1;
} REMOTE_CAMERA_SET, *PREMOTE_CAMERA_SET;

/*	�����[�g�L���v�`���R�}���h�p�P�b�g	*/
typedef	struct	{
	REMOTE	remote;
	INT16	size;
	INT16	color;
	INT16	compressMode;
	INT16	compressQuality;
} REMOTE_CAPTURE_CMD, *PREMOTE_CAPTURE_CMD;

/*	�����[�g�L���v�`�����X�|���X�p�P�b�g	*/
typedef	struct	{
	REMOTE	remote;
	INT16	errorCode;
	long	dataSize;
} REMOTE_CAPTURE_RSP, *PREMOTE_CAPTURE_RSP;

/*	�����[�g�R�}���h���s�p�P�b�g	*/
typedef	struct	{
	REMOTE	remote;
	char	command[256];
	UINT	mode;
} REMOTE_EXEC_CMD, *PREMOTE_EXEC_CMD;

//	�����[�g�_�E�����[�h
typedef struct {
	REMOTE	remote;
	char	fileName[256];
} REMOTE_DOWNLOAD_CMD, *PREMOTE_DOWNLOAD_CMD;

/*	���C�Z���X�`�F�b�N�p�P�b�g	*/
typedef	struct	{
	HEADER	header;
	char	mode;
	char	registNo[16];
} LICENSE, *PLICENSE;