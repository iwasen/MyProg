#include "cmctl.h"

#define	STATUS_FREE	0
#define	STATUS_LOCAL	1
#define	STATUS_NETWORK	2

#define	MAX_CCB		10
#define	N_PRESET	8

/*	カメラ制御ブロック	*/
typedef	struct	{
	int	status;
	int	sock;
	int	port;
	BOOL	bVideoOpen;
	BOOL	bPortOpen;
	int	remoteError;
} CCB, *PCCB;

/*	ini ファイル情報	*/
typedef	struct	{
	CAMERAOPT opt;
	PRESET	preset[N_PRESET];
} INIDATA;

typedef	struct	{
	int	width;
	int	height;
} VIDEOSIZE;

/*	local.cpp	*/
extern	int	LocalOpen(PCCB, LPSTR, int);
extern	int	LocalClose(PCCB);
extern	int	LocalControl(PCCB, int);
extern	int	LocalGetCameraOpt(PCCB, LPCAMERAOPT);
extern	int	LocalSetCameraOpt(PCCB, LPCAMERAOPT);
extern	int	LocalGetPreset(PCCB, LPPRESET);
extern	int	LocalSetPreset(PCCB, LPPRESET, int);
extern	int	LocalMovePreset(PCCB, int);
extern	int	LocalGetCameraPos(PCCB, LPCAMERAPOS);
extern	int	LocalSetCameraPos(PCCB, LPCAMERAPOS);
extern	int	LocalLock(PCCB, int);
extern	int	LocalCaptureVideo(PCCB, LPCAPTUREMODE, HPSTR);

/*	network.cpp	*/
extern	int	NetOpen(PCCB, LPSTR, LPSTR, int);
extern	int	NetClose(PCCB);
extern	int	NetControl(PCCB, int);
extern	int	NetGetCameraOpt(PCCB, LPCAMERAOPT);
extern	int	NetSetCameraOpt(PCCB, LPCAMERAOPT);
extern	int	NetGetPreset(PCCB, LPPRESET);
extern	int	NetSetPreset(PCCB, LPPRESET, int);
extern	int	NetMovePreset(PCCB, int);
extern	int	NetGetCameraPos(PCCB, LPCAMERAPOS);
extern	int	NetSetCameraPos(PCCB, LPCAMERAPOS);
extern	int	NetLock(PCCB, int);
extern	int	NetCaptureVideo(PCCB, LPCAPTUREMODE, HPSTR);

/*	data.cpp	*/
extern	int	colorTable[3];
extern	VIDEOSIZE videoSize[16];
extern	HINSTANCE hWinSock;
