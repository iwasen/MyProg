/************************************************************************
 *
 *			ÇoÇbÅ|ÇuÇhÇcÇdÇn
 *
 *		ñºèÃ		: ã§í ÉfÅ[É^èàóù
 *		ÉtÉ@ÉCÉãñº	: data.cpp
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#include "stdafx.h"
#include "pcvcomm.h"

//char	version[] = "1.21";
class	CMainFrame *pMainWnd;
class	CListBox *pMainListWnd;
class	CCaptureWnd *pCaptureWnd;
class	CChatWnd *pChatWnd;
class	CVoiceWnd *pVoiceWnd;
class	CCallWnd *pCallWnd;
class	CAnswerWnd *pAnswerWnd;
class	CCameraWnd *pCameraWnd;
CString	cameraFrameWndClassName;
char	title[] = "PC-VIDEO";
char	iniFile[] = "PCVIDEO.INI";
SOCKET	sockUDP = INVALID_SOCKET;
SOCKET	sockTCP = INVALID_SOCKET;
HDRAWDIB	hDD;
BOOL	bVoice = TRUE;
PALETTE	palette;
INIDATA	iniData;
PUSER	pTopUser;
PSEND	pTopSend;
char	localHostName[64];
u_long	localIPAddress;
BOOL	bSecond;
u_long	guardIPAddress;
int	guardTimer;
u_long	conxxxtIPAddress;
int	conxxxtChannelNo;
int	conxxxtTimer;
CFont	systemFont;
CFont	infoFont;
HCURSOR	hSpaceCursor;
CBrush	cameraBrush;
CPen	cameraPen;
BOOL	bCaptureStill;
BOOL	bChangeCaptureMode;
BOOL	bRestart;
BOOL	bAdmin;
VIDEOSIZE	videoSize[16] = {
	{40, 30},
	{80, 60},
	{120, 90},
	{160, 120},
	{200, 150},
	{240, 180},
	{280, 210},
	{320, 240},
	{360, 270},
	{400, 300},
	{440, 330},
	{480, 360},
	{520, 390},
	{560, 420},
	{600, 450},
	{640, 480}
};
char	bitmapInfo[sizeof(BITMAPINFOHEADER)+sizeof(RGBQUAD)*256];
int	colorTable[3] = {8, 16, 24};
int	licenseReplyCount;
BOOL	bNewVideoUser;
BOOL	bDebug;
HICON hIconApp;
HICON hIconVideo;
HICON hIconMic;
HICON hIconMicOff;
HICON hIconSpeaker;
HICON hIconSpeakerOff;
HICON hIconChat;
CString downLoadFile;
int nCaptureDriver = -1;
BOOL bOnlineUpdate;