/************************************************************************
 *									*
 *			カメラコントロールＤＬＬ			*
 *									*
 *		名称		: ローカルカメラ処理			*
 *		ファイル名	: local.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <windows.h>
#include <mmsystem.h>
#include <stdlib.h>
#include <string.h>
#include <vfw.h>
#include "cmctldll.h"
extern	"C" {
#include "../vcc1.h"
}

#define	VCC1_STS_PANLIMIT	0x100
#define	VCC1_STS_PAN		0x200
#define	VCC1_STS_TILTLIMIT	0x400
#define	VCC1_STS_TILT		0x800
#define	VCC1_STS_NOINIT		0x010
#define	VCC1_STS_POWEROFF	0x020
#define	VCC1_STS_NOREMOTE	0x040
#define	VCC1_STS_ZOOM		0x080
#define	VCC1_STS_FOCUS		0x001
#define	VCC1_STS_MANUALFOCUS	0x002
#define	VCC1_STS_SHUTTERFLAG1	0x004
#define	VCC1_STS_SHUTTERFLAG2	0x008
#define	VCC1_STS_NOANSWER	0x1000
#define	VCC1_STS_BUSY		(VCC1_STS_PAN|VCC1_STS_TILT|VCC1_STS_ZOOM)

#define	PAN_CENTER	0x28a
#define	PAN_MAX		0x514
#define	TILT_CENTER	0x121
#define	TILT_MAX	0x242
#define	ZOOM_CENTER	0x40
#define	ZOOM_MAX	0x80

/*	カラーパレット	*/
typedef	struct	{
	WORD	palVersion;
	WORD	palNumEntries;
	PALETTEENTRY	palPalEntry[256];
} PALETTE;

typedef	struct	{
	int	size;
	int	color;
	int	width;
	int	height;
	long	imageSize;
} CAPTUREINFO;

static	HVIDEO	hVideo;
static	INIDATA	iniData;
static	int	portOpenCount;
static	int	videoOpenCount;
static	CAPTUREINFO currentCaptureInfo;
static	CAMERAPOS currentPos;
static	CAMERAPOS setPos;
static	BOOL	bSetCapturePalette;
static	UINT	idTimer;
static	char	iniFile[] = "PCVIDEO.INI";
static	char	sectionVcc1[] = "VC-C1";
static	PCCB	pLockCCB;

static	int	SetCaptureMode(LPCAPTUREMODE);
static	void	InitializeCamera(void);
static	void	StartCameraTimer(void);
static	void	StopCameraTimer(void);
static	void	ReadIniFile(void);
static	void	WriteIniFile(void);
void	CALLBACK _loadds _export TimerProc(HWND, UINT, UINT, DWORD);

int	LocalOpen(PCCB pCCB, LPSTR portName, int option)
{
	int	sts;
	int	err;

	memset(pCCB, 0, sizeof(CCB));

	ReadIniFile();

	if (portName != NULL && portName[0] != '\0') {
		pCCB->port = atoi(portName + 3) - 1;

		if (portOpenCount == 0) {
			err = Vcc1Open(pCCB->port);
			if (err == 7) {
				Vcc1Close();
				err = Vcc1Open(pCCB->port);
			}

			if (err != 0)
				return ERROR_CM_PORT_OPEN;

			sts = Vcc1GetStatus();
			if (sts & VCC1_STS_NOANSWER)
				return ERROR_CM_NOANSWER;

			InitializeCamera();

			if (sts & VCC1_STS_NOINIT)
				Vcc1Init2();
		}

		portOpenCount++;
		pCCB->bPortOpen = TRUE;
	}

	if (videoOpenCount == 0) {
		if (videoOpen(&hVideo, 0, VIDEO_IN) != 0) {
			LocalClose(pCCB);
			return ERROR_CM_VIDEO_OPEN;
		}
		memset(&currentCaptureInfo, 0, sizeof(currentCaptureInfo));
	}
	videoOpenCount++;
	pCCB->bVideoOpen = TRUE;

	return 0;
}

int	LocalClose(PCCB pCCB)
{
	if (pCCB->bVideoOpen && --videoOpenCount == 0) {
		videoClose(hVideo);
		pCCB->bVideoOpen = FALSE;
		bSetCapturePalette = FALSE;
	}

	if (pCCB->bPortOpen && --portOpenCount == 0) {
		Vcc1Close();
		pCCB->bPortOpen = FALSE;
	}

	if (pLockCCB == pCCB)
		pLockCCB = NULL;

	return 0;
}

int	LocalControl(PCCB pCCB, int command)
{
	int	err = 0;

	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	if (pLockCCB != NULL && pLockCCB != pCCB)
		return ERROR_CM_LOCK;

	switch (command) {
	case CM_RESET:
		Vcc1Reset();
		break;
	case CM_HOME_POSITION:
		Vcc1Home();
		break;
	case CM_PAN_RIGHT:
		Vcc1PanRight();
		break;
	case CM_PAN_LEFT:
		Vcc1PanLeft();
		break;
	case CM_TILT_UP:
		Vcc1TiltUp();
		break;
	case CM_TILT_DOWN:
		Vcc1TiltDown();
		break;
	case CM_STOP:
		Vcc1Stop();
		break;
	case CM_ZOOM_WIDE:
		Vcc1ZoomWide();
		break;
	case CM_ZOOM_TELE:
		Vcc1ZoomTele();
		break;
	case CM_ZOOM_STOP:
		Vcc1ZoomStop();
		break;
	case CM_FOCUS_AUTO:
		Vcc1FocusAf();
		break;
	case CM_FOCUS_MANUAL:
		Vcc1FocusManual();
		break;
	case CM_FOCUS_NEAR:
		Vcc1FocusNear();
		break;
	case CM_FOCUS_FAR:
		Vcc1FocusFar();
		break;
	case CM_POWER_ON:
		Vcc1PowerOn();
		break;
	case CM_POWER_OFF:
		Vcc1PowerOff();
		break;
	default:
		err = ERROR_CM_INVALID_COMMAND;
		break;
	}

	return err;
}

int	LocalGetCameraOpt(PCCB pCCB, LPCAMERAOPT opt)
{
	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	*opt = iniData.opt;

	return 0;
}

int	LocalSetCameraOpt(PCCB pCCB, LPCAMERAOPT opt)
{
	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	if (pLockCCB != NULL && pLockCCB != pCCB)
		return ERROR_CM_LOCK;

	iniData.opt = *opt;
	InitializeCamera();
	WriteIniFile();

	return 0;
}

int	LocalGetPreset(PCCB pCCB, LPPRESET preset)
{
	int	i;

	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	for (i = 0; i < N_PRESET; i++)
		*preset++ = iniData.preset[i];

	return 0;
}

int	LocalSetPreset(PCCB pCCB, LPPRESET preset, int presetNo)
{
	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	if (pLockCCB != NULL && pLockCCB != pCCB)
		return ERROR_CM_LOCK;

	iniData.preset[presetNo] = *preset;
	WriteIniFile();

	return 0;
}

int	LocalMovePreset(PCCB pCCB, int presetNo)
{
	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	if (pLockCCB != NULL && pLockCCB != pCCB)
		return ERROR_CM_LOCK;

	setPos = iniData.preset[presetNo].pos;
	StartCameraTimer();

	return 0;
}

int	LocalGetCameraPos(PCCB pCCB, LPCAMERAPOS pos)
{
	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	if ((pos->pan = Vcc1GetPanPos()) == -1)
		pos->pan = currentPos.pan;
	else
		currentPos.pan = pos->pan;

	if ((pos->tilt = Vcc1GetTiltPos()) == -1)
		pos->tilt = currentPos.tilt;
	else
		currentPos.tilt = pos->tilt;

	if ((pos->zoom = Vcc1GetZoomPos()) == -1)
		pos->zoom = currentPos.zoom;
	else
		currentPos.zoom = pos->zoom;

	return 0;
}

int	LocalSetCameraPos(PCCB pCCB, LPCAMERAPOS pos)
{
	if (!pCCB->bPortOpen)
		return ERROR_CM_CAMERA_CONTROL;

	if (pLockCCB != NULL && pLockCCB != pCCB)
		return ERROR_CM_LOCK;

	setPos = *pos;
	StartCameraTimer();

	return 0;
}

int	LocalLock(PCCB pCCB, int lockFlag)
{
	if (pLockCCB != NULL && pLockCCB != pCCB)
		return ERROR_CM_LOCK;

	if (lockFlag != 0)
		pLockCCB = pCCB;
	else
		pLockCCB = NULL;

	return 0;
}

int	LocalCaptureVideo(PCCB pCCB, LPCAPTUREMODE captureMode, HPSTR buf)
{
	int	err;
	VIDEOHDR videoHdr;

	if ((err = SetCaptureMode(captureMode)) != 0)
		return err;

	memset(&videoHdr, 0, sizeof(videoHdr));
	videoHdr.lpData = (LPBYTE)buf;
	videoHdr.dwBufferLength = currentCaptureInfo.imageSize;
	if (videoFrame(hVideo, &videoHdr) != 0)
		return ERROR_CM_VIDEO_CAPTURE;

	return 0;
}

static	int	SetCaptureMode(LPCAPTUREMODE captureMode)
{
	BITMAPINFOHEADER captureFormat;
	int	width, height;
	long	imageSize;
	int	i;
	PALETTE	palette;
	DWORD	err;

	if (captureMode->size == currentCaptureInfo.size &&
			captureMode->color == currentCaptureInfo.color)
		return 0;

	width = videoSize[captureMode->size].width;
	height = videoSize[captureMode->size].height;
	imageSize = (long)width * (long)height * (captureMode->color + 1);

	err = videoConfigure(hVideo, DVM_FORMAT, VIDEO_CONFIGURE_GET,
					0x0, &captureFormat,
					sizeof(captureFormat), 0x0, 0);
	if (err != 0)
		return ERROR_CM_VIDEO_CONFIG;

	captureFormat.biWidth = width;
	captureFormat.biHeight = height;
	captureFormat.biSizeImage = imageSize;
	captureFormat.biBitCount = colorTable[captureMode->color];

	err = videoConfigure(hVideo, DVM_FORMAT, VIDEO_CONFIGURE_SET,
					0x0, &captureFormat,
					sizeof(captureFormat), 0x0, 0);
	if (err != 0)
		return ERROR_CM_VIDEO_CONFIG;

	if (captureMode->color == COLOR_8 && !bSetCapturePalette) {
		palette.palVersion = 0x300;
		palette.palNumEntries = 256;

		for (i = 0; i < 256; i++) {
			palette.palPalEntry[i].peRed = i;
			palette.palPalEntry[i].peGreen = i;
			palette.palPalEntry[i].peBlue = i;
		}
		err = videoConfigure(hVideo, DVM_PALETTE, VIDEO_CONFIGURE_SET,
				0x0, &palette, sizeof(palette), 0x0, 0);
		if (err != 0)
			return ERROR_CM_VIDEO_CONFIG;

		bSetCapturePalette = TRUE;
	}

	currentCaptureInfo.size = captureMode->size;
	currentCaptureInfo.color = captureMode->color;
	currentCaptureInfo.width = width;
	currentCaptureInfo.height = height;
	currentCaptureInfo.imageSize = imageSize;

	return 0;
}

static	void InitializeCamera(void)
{
	Vcc1SetPanSpeed(iniData.opt.panSpeed);
	Vcc1SetTiltSpeed(iniData.opt.tiltSpeed);

	switch (iniData.opt.shutterSpeed) {
	case 0:
		Vcc1ShutPro();
		break;
	case 1:
		Vcc1Shut60();
		break;
	case 2:
		Vcc1Shut100();
		break;
	}

	if (iniData.opt.autoFocus)
		Vcc1FocusAf();
	else
		Vcc1FocusManual();

	if (iniData.opt.lightAdjust)
		Vcc1HoseiOn();
	else
		Vcc1HoseiOff();

	if (iniData.opt.autoWhiteBalance)
		Vcc1WhiteNormal();
	else
		Vcc1WhiteLock();
}

static	void	StartCameraTimer(void)
{
	if (idTimer == 0)
		idTimer = SetTimer(NULL, 0, 200, (TIMERPROC)TimerProc);
}

static	void	StopCameraTimer(void)
{
	if (idTimer != 0) {
		KillTimer(NULL, idTimer);
		idTimer = 0;
	}
}

void	CALLBACK _loadds _export TimerProc(HWND hWnd, UINT msg, UINT idTimer, DWORD dwTime)
{
	int	sts;

	sts = Vcc1GetStatus();

	if (!(sts & (VCC1_STS_PAN | VCC1_STS_TILT))) {
		if (setPos.pan != -1) {
			Vcc1SetPanPos(setPos.pan);
			setPos.pan = -1;
		} else if (setPos.tilt != -1) {
			Vcc1SetTiltPos(setPos.tilt);
			setPos.tilt = -1;
		}
	}

	if (!(sts & VCC1_STS_ZOOM)) {
		if (setPos.zoom != -1) {
			if (setPos.zoom == ZOOM_MAX)
				Vcc1SetZoomPos(setPos.zoom - 1);
			else
				Vcc1SetZoomPos(setPos.zoom);
			setPos.zoom = -1;
		}
	}

	if (setPos.pan == -1 && setPos.tilt == -1 && setPos.zoom == -1)
		StopCameraTimer();
}

static	void	ReadIniFile(void)
{
	int	i;
	char	entry[20];
	char	buf[100];
	char	*p;

	iniData.opt.panSpeed = GetPrivateProfileInt(sectionVcc1,
			"PanSpeed", 500, iniFile);

	iniData.opt.tiltSpeed = GetPrivateProfileInt(sectionVcc1,
			"TiltSpeed", 500, iniFile);

	iniData.opt.zoomSpeed = GetPrivateProfileInt(sectionVcc1,
			"ZoomSpeed", 0, iniFile);

	iniData.opt.shutterSpeed = GetPrivateProfileInt(sectionVcc1,
			"ShutterSpeed", 0, iniFile);

	GetPrivateProfileString(sectionVcc1, "AutoFocus", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.opt.autoFocus = (strcmp(buf, "TRUE") == 0);

	GetPrivateProfileString(sectionVcc1, "LightAdjust", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.opt.lightAdjust = (strcmp(buf, "TRUE") == 0);

	GetPrivateProfileString(sectionVcc1, "AutoWhiteBalance", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.opt.autoWhiteBalance = (strcmp(buf, "TRUE") == 0);

	GetPrivateProfileString(sectionVcc1, "WideConverter", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.opt.wideConverter = (strcmp(buf, "TRUE") == 0);

	for (i = 0; i < N_PRESET; i++) {
		wsprintf(entry, "Preset%d", i + 1);
		GetPrivateProfileString(sectionVcc1, entry, "",
					buf, sizeof(buf), iniFile);
		if ((p = strtok(buf, ",")) != NULL)
			iniData.preset[i].pos.pan = atoi(p);
		else
			iniData.preset[i].pos.pan = 0x28a;

		if ((p = strtok(NULL, ",")) != NULL)
			iniData.preset[i].pos.tilt = atoi(p);
		else
			iniData.preset[i].pos.tilt = 0x121;

		if ((p = strtok(NULL, ",")) != NULL)
			iniData.preset[i].pos.zoom = atoi(p);
		else
			iniData.preset[i].pos.zoom = 0x40;

		if ((p = strtok(NULL, ",")) != NULL)
			strcpy(iniData.preset[i].comment, p);
		else
			iniData.preset[i].comment[0] = '\0';
	}
}

static	void	WriteIniFile(void)
{
	int	i;
	char	entry[20];
	char	buf[100];

	wsprintf(buf, "%d", iniData.opt.panSpeed);
	WritePrivateProfileString(sectionVcc1, "PanSpeed", buf, iniFile);

	wsprintf(buf, "%d", iniData.opt.tiltSpeed);
	WritePrivateProfileString(sectionVcc1, "TiltSpeed", buf, iniFile);

	wsprintf(buf, "%d", iniData.opt.zoomSpeed);
	WritePrivateProfileString(sectionVcc1, "ZoomSpeed", buf, iniFile);

	wsprintf(buf, "%d", iniData.opt.shutterSpeed);
	WritePrivateProfileString(sectionVcc1, "ShutterSpeed", buf, iniFile);

	WritePrivateProfileString(sectionVcc1, "AutoFocus",
		iniData.opt.autoFocus ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionVcc1, "LightAdjust",
		iniData.opt.lightAdjust ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionVcc1, "AutoWhiteBalance",
		iniData.opt.autoWhiteBalance ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionVcc1, "WideConverter",
		iniData.opt.wideConverter ? "TRUE" : "FALSE", iniFile);

	for (i = 0; i < N_PRESET; i++) {
		wsprintf(entry, "Preset%d", i + 1);
		wsprintf(buf, "%d,%d,%d,%s",
				iniData.preset[i].pos.pan,
				iniData.preset[i].pos.tilt,
				iniData.preset[i].pos.zoom,
				(LPSTR)iniData.preset[i].comment);
		WritePrivateProfileString(sectionVcc1, entry, buf, iniFile);
	}
}
