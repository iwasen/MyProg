/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: PC-VIDEO 共通ヘッダ
 *		ファイル名	: pcvcomm.h
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include <winsock.h>
#include <mmsystem.h>
#include <vfw.h>
#include "common.h"

#define	N_PALETTE	(256-20)
#define	WM_SOCKETUDP	(WM_USER+1400)
#define	WM_SOCKETTCP	(WM_USER+1401)

#define	MAX_VIDEO_WINDOW	32

#define	MAXSIZE_X	640U
#define	MAXSIZE_Y	480U

#define	TIMEOUT_USER	30
#define	GUARD_TIME	4

#define	IDC_LISTBOX	AFX_IDW_PANE_FIRST
#define	IDT_MONITOR	2010
#define	IDM_HOSTBASE	3000

#define	CAMERA_CONTROL		1
#define	CAMERA_CONTROL_LOCK	2

#define LIST_ORDER_CONxxxT		0
#define LIST_ORDER_NAME			1
#define LIST_ORDER_IPADDRESS	2

#define	GETPORTNO(_flag) (((_flag) & FLAG_SECOND) ? (PORTNO+1) : PORTNO)

#define N_INI_CONxxxT		15
#define N_INI_HOSTLIST		8
#define N_INI_VIDEOWINDOW	16
#define N_INI_LOGIN			5

/*	ini ファイルデータ	*/
struct	INIDATA {
	SETTINGS	settings;
	struct	{
		char	host[N_INI_CONxxxT][20];
		char	name[N_INI_CONxxxT][32];
		int	channelNo[N_INI_CONxxxT];
	} conxxxt;
	struct	{
		int	left;
		int	right;
		int	top;
		int	bottom;
		int nListOrder;
		BOOL bDispAddress;
		BOOL bSendVideo;
	} mainWindow;
	struct	{
		int	left;
		int	right;
		int	top;
		int	bottom;
		int splitter;
		char	font[32];
		int	fontSize;
		char	logFile[128];
		BOOL	bAppend;
		BOOL	bAlwaysLog;
	} chatWindow;
	struct	{
		int	left;
		int	top;
		int	mode;		/* 0:手動 1:自動 */
		int	autoSendLevel;
		int	bufferCount;
		BOOL bAutoLevelControl;
		int nInputDevice;
		int nOutputDevice;
		int nBufferNum;
	} voiceWindow;
	struct	{
		int	left;
		int	top;
	} cameraWindow;
	struct	{
		char	host[N_INI_HOSTLIST][20];
		char	target[20];
		int	channelNo;
	} hostList;
	struct	{
		int	left[N_INI_VIDEOWINDOW];
		int	top[N_INI_VIDEOWINDOW];
	} videoWindow;
	struct	{
		char	registNo[16];
		int	maxUser;
	} license;
	struct {
		u_long serverID;
		char userID[32];
		char password[64];
		BOOL bSavePassword;
	} login[N_INI_LOGIN];
	struct	VCC1DATA vcc1;
};

typedef	struct	{
	int	divisor[3][2][64];
	int	last_dc_val[3];
	int	outcount;
	int	putcount;
	unsigned bitbuf;
	char *putptr;
	char *inbuf;
	int	width;
	int	color;
	int	qtblNo;
} CINFO, *LPCINFO;

typedef	struct	{
	int	dctTable[3][2][64];
	int	last_dc_val[3];
	long	get_buffer;
	int	bits_left;
	unsigned char	*next_input_byte;
	char *outbuf;
	int	width;
	int	color;
	int	qtblNo;
} DINFO, *LPDINFO;

/*	ソケット管理情報	*/
typedef	struct	_SOCK {
	BOOL	bTCP;
	SOCKET	sock;
	u_long	IPAddress;
	u_short	portNo;
} SOCK, *PSOCK;

/*	送信先管理情報	*/
typedef	struct	_SEND {
	SOCK	sock;
	BOOL	bVideoSendFlag;
	struct	_USER *pUser;
	struct	_SEND *pChain;
} SEND, *PSEND;

typedef	class	CReceiveWnd *PVIDEO;

/*	キャプチャバッファ	*/
typedef	struct	{
	long	size;
	HANDLE	hVideoHdr;
	HANDLE	hData;
	LPVIDEOHDR lpVideoHdr;
	long	sendSize;
	HPSTR	lpSendBuf;
	int	captureCounter;
} CAPTUREBUF, *PCAPTUREBUF;

/*	ユーザ管理情報		*/
typedef	struct	_USER {
	DWORD	id;
	char	userName[32];
	int	modeFlag;
	int	channelNo;
	int nConxxxtCounter;
	int	timer;
	int	listIndex;
	int	indent;
	BOOL	bSendVideo;
	BOOL bMaster;
	int	cameraControl;
	class	CReceiveWnd *pVideo;
	PSEND	pSend;
	PCAPTUREBUF pCaptureBuf;
	struct	_USER *pSendUser;
	struct	_USER *pChain;
} USER, *PUSER;

/*	カラーパレット	*/
typedef	struct	{
	WORD	palVersion;
	WORD	palNumEntries;
	PALETTEENTRY	palPalEntry[256];
} PALETTE;

/*	ビデオサイズ	*/
typedef	struct	{
	int	width;
	int	height;
} VIDEOSIZE;

typedef	unsigned char	MONO8, _huge *LPMONO8;

typedef	struct	{
	WORD	blue:5,
		green:5,
		red:5;
} RGB16, _huge *LPRGB16;

typedef	struct	{
	BYTE	blue;
	BYTE	green;
	BYTE	red;
} RGB24, _huge *LPRGB24;

typedef	struct	{
	char	data[BLOCK_PIXEL];
} BCOPY, *PBCOPY, *LPBCOPY;

/*	pcvideo.cpp	*/
extern	class	CPcVideoApp theApp;

/*	global.cpp	*/
extern	void	SendConxxxtAll();
extern	void	SendConxxxt1(PSEND);
extern	void	SendConxxxt2(u_long, u_short, int);
extern	void	SendDisconxxxt(PSOCK);
extern	void MakeConxxxtPacket(PCONxxxT pConxxxt, int channelNo);
extern	void	ReadIniFile();
extern	void	WriteIniFile();
extern	void	ConxxxtHost(char *, int);

/*	call.cpp	*/
extern	void	ReceiveCall(PUSER, char *, int);
extern	void	OpenAnswerWindow();
extern	void	CloseAnswerWindow();
extern	void	OpenCallWindow(CWnd *, char);
extern	void	CloseCallWindow();

/*	settei.cpp	*/
extern	BOOL	SettingDialog(HWND, SETTINGS *, char *);

/*	license.cpp	*/
extern	BOOL	LicenseDialog(HWND);
extern	void	ReceiveLicense(PLICENSE);
extern	void	SendLicenseCheck();

/*	capture.cpp	*/
extern	BOOL	InitializeCapture();
extern	void	OpenCaptureWindow();
extern	void	CloseCaptureWindow();
extern	void	ReceiveCaptureCommand(PUSER, PREMOTE_CAPTURE_CMD);

/*	receive.cpp	*/
extern	PVIDEO	CreateVideo(PUSER);
extern	void	DeleteVideo(PVIDEO);

/*	user.cpp	*/
extern	PUSER	AddUser(PSOCK, PCONxxxT);
extern	PUSER	SearchUser(DWORD id, PSOCK pSockSend);
extern	PUSER	GetUser(int);
extern	void	DeleteUser(PUSER);
extern	void	DeleteSend(PSEND);
extern	void ReorderList();

/*	socket.cpp	*/
extern	BOOL	InitSocket();
extern	void	ExitSocket();
extern	void	ReadSocketUDP(SOCKET);
extern	void	AcceptSocket(SOCKET);
extern	void	ReadSocketTCP(SOCKET);
extern	void	CloseSocket(SOCKET);
extern	int	SendData(PSOCK, LPVOID, int);
extern	int	SendUDP(SOCKET, u_long, u_short, LPSTR, int);
extern	void	WriteSocketTCP(SOCKET);
extern	void	SendCaptureData(PUSER);

/*	chat.cpp	*/
extern	void	OpenChatWindow();
extern	void	CloseChatWindow();
extern	void	ReceiveChat(PUSER, char *, int);

/*	voice.cpp	*/
extern	BOOL	InitializeVoice();
extern	void	OpenVoiceWindow();
extern	void	CloseVoiceWindow();
extern	void	ReceiveVoice(PUSER, char *, int);

/*	cdvideo.cpp	*/
extern	BOOL	InitVideoCompress();
extern void ExitVideoCompress();
extern	LPCINFO	AllocVideoCInfo();
extern	void	SetVideoCompressQuality(LPCINFO, int, int);
extern	void	StartVideoCompress(LPCINFO, char *, char *, int, int,
									int);
extern	int	DoVideoCompress(LPCINFO, int, int);
extern void ExitVideoDecompress();
extern	int	FinishVideoCompress(LPCINFO);
extern	BOOL	InitVideoDecompress();
extern	LPDINFO	AllocVideoDInfo();
extern	void	SetVideoDecompressQuality(LPDINFO, int);
extern	void	StartVideoDecompress(LPDINFO, char *, char *, int, int,
								int);
extern	void	DoVideoDecompress(LPDINFO, int, int);

/*	cdaudio.cpp	*/
extern	void	InitializeAudioCompress();
extern	void ExitAudioCompress();
extern	int	CompressAudio(char *, char *, int, int);
extern	int	DecompressAudio(char *, char *);

/*	remote.cpp	*/
extern	void	RemoteSetting(CWnd *);
extern	void	ReceiveRemote(PUSER, PREMOTE);

/*	cmcmd.cpp	*/
extern	void	CommandCloseCamera(PUSER);
extern	void	ReceiveCameraCommand(PUSER, PREMOTE_CAMERA_CMD);
extern	void	ReceiveCameraStatus(PUSER, PREMOTE_CAMERA_STS);
extern	void	ReceiveCameraInfo(PUSER, PREMOTE_CAMERA_SET);
extern	BOOL	ExecCameraCommand(PUSER, const CAMERA_COMMAND &);
extern	void	SetCameraInfo(const VCC1DATA &, int);
extern	void	InitializeCamera();

/*	csub.cpp	*/
extern	int	CompressRL(char *, char *, int);
extern	int	DecompressRL(char *, char *, int);
extern	char	*stristr(char *, char *);
extern	void	SetDialogPositionCenter(HWND);
extern	void	SetChangeListBoxItem(HWND, int);
extern	int MessageBoxID(CWnd *pWnd, UINT nID, UINT nType);
extern	CString GetString(UINT nID);

/*	data.cpp	*/
extern	char	version[];
extern	HINSTANCE	hInst;
extern	class	CMainFrame *pMainWnd;
extern	class	CListBox *pMainListWnd;
extern	class	CCaptureWnd *pCaptureWnd;
extern	class	CChatWnd *pChatWnd;
extern	class	CVoiceWnd *pVoiceWnd;
extern	class	CCallWnd *pCallWnd;
extern	class	CAnswerWnd *pAnswerWnd;
extern	class	CCameraWnd *pCameraWnd;
extern	CString	cameraFrameWndClassName;
extern	char	title[];
extern	char	iniFile[];
extern	SOCKET	sockUDP;
extern	SOCKET	sockTCP;
extern	HDRAWDIB	hDD;
extern	BOOL	bVoice;
extern	PALETTE	palette;
extern	INIDATA	iniData;
extern	PUSER	pTopUser;
extern	PSEND	pTopSend;
extern	char	localHostName[64];
extern	u_long	localIPAddress;
extern	BOOL	bSecond;
extern	u_long	guardIPAddress;
extern	int	guardTimer;
extern	u_long	conxxxtIPAddress;
extern	int	conxxxtChannelNo;
extern	int	conxxxtTimer;
extern	CFont	systemFont;
extern	CFont	infoFont;
extern	HCURSOR	hSpaceCursor;
extern	CBrush	cameraBrush;
extern	CPen	cameraPen;
extern	BOOL	bCaptureStill;
extern	BOOL	bChangeCaptureMode;
extern	BOOL	bRestart;
extern	BOOL	bAdmin;
extern	VIDEOSIZE	videoSize[16];
extern	char	bitmapInfo[];
extern	int	colorTable[3];
extern	int	licenseReplyCount;
extern	BOOL	bNewVideoUser;
extern	BOOL	bDebug;
extern	HICON hIconApp;
extern	HICON hIconVideo;
extern	HICON hIconMic;
extern	HICON hIconMicOff;
extern	HICON hIconSpeaker;
extern	HICON hIconSpeakerOff;
extern	HICON hIconChat;
extern	CString downLoadFile;
extern	int nCaptureDriver;
extern	BOOL bOnlineUpdate;
