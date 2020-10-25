/************************************************************************
 *
 *			‚o‚b|‚u‚h‚c‚d‚n
 *
 *		–¼Ì		: ƒJƒƒ‰ƒRƒ“ƒgƒ[ƒ‹ƒRƒ}ƒ“ƒhˆ—
 *		ƒtƒ@ƒCƒ‹–¼	: cmcmd.cpp
 *		ì¬ŽÒ		: s.aizawa
 *
 ************************************************************************/

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "pcvideo.h"
#include "video.h"
#include "camera.h"
#include "cmcmd.h"

extern	"C"
{
#include "vcc1.h"
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

#define	ZOOM_MAX	0x80
#define	BUSY_COUNT_ZOOM		3

static	BOOL	LockCheck(PUSER);
static	void	CommandOpenCamera(PUSER);
static	void	CommandSetPos(PUSER, int, int, int);
static	BOOL	BusyCheck(CAMERA_STATUS &);
static	int	ReadStatus(CAMERA_STATUS &);
static	void	SendStatus(CAMERA_STATUS &, PUSER);
static	void	SendStatusAll(CAMERA_STATUS &);
static	void	ReadAndSendStatus();
static	void	StartCameraTimer();
static	void	StopCameraTimer();
void CALLBACK EXPORT TimerProc(HWND, UINT, UINT, DWORD);
static void InitializeVCC1();
static void InitializeVCC1Timer();
static	BOOL	OpenPort();
static	void	ClosePort();

static	int	portOpenCounter;
static	int	localCameraControl;
static	BOOL	bCameraControlLock;
static	int	busyCount;
static	int	tmpPanPos = -1;
static	int	tmpTiltPos = -1;
static	int	tmpZoomPos = -1;
static	int	currentPanPos, currentTiltPos, currentZoomPos;
static	char	lockUserName[32];
static	UINT	idTimer;
static	TIMERPROC lpfnTimerProc;
static	int	VCC1InitCounter;
static	int	dummy;

void ReceiveCameraCommand(PUSER pUser, PREMOTE_CAMERA_CMD pRemote)
{
	if (pRemote->command.command != COMMAND_Open &&
					pUser->cameraControl == 0)
		return;

	ExecCameraCommand(pUser, pRemote->command);
}

void ReceiveCameraStatus(PUSER pUser, PREMOTE_CAMERA_STS pRemote)
{
	if (pCameraWnd != NULL)
		pCameraWnd->SetCameraStatus(pRemote->status);
}

void ReceiveCameraInfo(PUSER pUser, PREMOTE_CAMERA_SET pRemote)
{
	SetCameraInfo(pRemote->vcc1, pRemote->flag);
}

BOOL ExecCameraCommand(PUSER pUser, const CAMERA_COMMAND &command)
{
	int	rc = 0;

	switch (command.command) {
	case COMMAND_Open:
		CommandOpenCamera(pUser);
		break;
	case COMMAND_Close:
		CommandCloseCamera(pUser);
		break;
	case COMMAND_Reset:
		if (!LockCheck(pUser)) {
			Vcc1Reset();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_Home:
		if (!LockCheck(pUser)) {
			Vcc1Home();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_Stop:
		if (!LockCheck(pUser)) {
			Vcc1Stop();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_PanRight:
		if (!LockCheck(pUser)) {
			Vcc1PanRight();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_PanLeft:
		if (!LockCheck(pUser)) {
			Vcc1PanLeft();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_TiltUp:
		if (!LockCheck(pUser)) {
			Vcc1TiltUp();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_TiltDown:
		if (!LockCheck(pUser)) {
			Vcc1TiltDown();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_ZoomStop:
		if (!LockCheck(pUser)) {
			Vcc1ZoomStop();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_ZoomWide:
		if (!LockCheck(pUser)) {
			busyCount = BUSY_COUNT_ZOOM;
			if (iniData.vcc1.zoomSpeed == 0)
				Vcc1ZoomWide();
			else
				Vcc1ZoomWide2();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_ZoomTele:
		if (!LockCheck(pUser)) {
			busyCount = BUSY_COUNT_ZOOM;
			if (iniData.vcc1.zoomSpeed == 0)
				Vcc1ZoomTele();
			else
				Vcc1ZoomTele2();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_PowerOn:
		if (!LockCheck(pUser)) {
			Vcc1PowerOn();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_PowerOff:
		if (!LockCheck(pUser)) {
			Vcc1PowerOff();
			ReadAndSendStatus();
		}
		break;
	case COMMAND_FocusAf:
		if (!LockCheck(pUser)) {
			Vcc1FocusAf();
			iniData.vcc1.bAutoFocus = TRUE;
		}
		break;
	case COMMAND_FocusManual:
		if (!LockCheck(pUser)) {
			Vcc1FocusManual();
			iniData.vcc1.bAutoFocus = FALSE;
		}
		break;
	case COMMAND_FocusNear:
		if (!LockCheck(pUser)) {
			Vcc1FocusNear();
		}
		break;
	case COMMAND_FocusFar:
		if (!LockCheck(pUser)) {
			Vcc1FocusFar();
		}
		break;
	case COMMAND_ShutPro:
		Vcc1ShutPro();
		break;
	case COMMAND_Shut60:
		Vcc1Shut60();
		break;
	case COMMAND_Shut100:
		Vcc1Shut100();
		break;
	case COMMAND_Init1:
		Vcc1Init1();
		break;
	case COMMAND_Init2:
		Vcc1Init2();
		break;
	case COMMAND_HoseiOff:
		Vcc1HoseiOff();
		break;
	case COMMAND_HoseiOn:
		Vcc1HoseiOn();
		break;
	case COMMAND_WhiteNormal:
		Vcc1WhiteNormal();
		break;
	case COMMAND_WhiteLock:
		Vcc1WhiteLock();
		break;
	case COMMAND_ControlerOn:
		Vcc1ControlerOn();
		break;
	case COMMAND_ControlerOff:
		Vcc1ControlerOff();
		break;
	case COMMAND_ThrowOn:
		Vcc1ThrowOn();
		break;
	case COMMAND_ThrowOff:
		Vcc1ThrowOff();
		break;
	case COMMAND_SetPanSpeed:
		Vcc1SetPanSpeed(command.param1);
		break;
	case COMMAND_SetTiltSpeed:
		Vcc1SetTiltSpeed(command.param1);
		break;
	case COMMAND_SetPanPos:
		if (!LockCheck(pUser)) {
			Vcc1SetPanPos(command.param1);
			ReadAndSendStatus();
		}
		break;
	case COMMAND_SetTiltPos:
		if (!LockCheck(pUser)) {
			Vcc1SetTiltPos(command.param1);
			ReadAndSendStatus();
		}
		break;
	case COMMAND_SetZoomPos:
		if (!LockCheck(pUser)) {
			busyCount = BUSY_COUNT_ZOOM;
			if (command.param1 == ZOOM_MAX)
				Vcc1SetZoomPos(command.param1 - 1);
			else
				Vcc1SetZoomPos(command.param1);
			ReadAndSendStatus();
		}
		break;
	case COMMAND_SavePos:
		Vcc1SavePos(command.param1);
		break;
	case COMMAND_RestorePos:
		Vcc1RestorePos(command.param1);
		break;
	case COMMAND_GetPanSpeed:
		rc = Vcc1GetPanSpeed();
		break;
	case COMMAND_GetTiltSpeed:
		rc = Vcc1GetTiltSpeed();
		break;
	case COMMAND_GetPanPos:
		rc = Vcc1GetPanPos();
		break;
	case COMMAND_GetTiltPos:
		rc = Vcc1GetTiltPos();
		break;
	case COMMAND_GetZoomPos:
		rc = Vcc1GetZoomPos();
		break;
	case COMMAND_GetStatus:
		{
			CAMERA_STATUS	status;
			
			ReadStatus(status);
			if (command.param1 != 0) {
				status.status |= STATUS_INFO;
				status.vcc1 = iniData.vcc1;
			}
			SendStatus(status, pUser);
		}
		break;
	case COMMAND_GetPresetStatus:
		rc = Vcc1GetPresetStatus();
		break;
	case COMMAND_SetPos:
		if (!LockCheck(pUser)) {
			CommandSetPos(pUser, command.param1, command.param2,
							command.param3);
		}
		break;
	case COMMAND_Lock:
		if (command.param1 == 0) {
			if (pUser == NULL)
				localCameraControl = CAMERA_CONTROL;
			else
				pUser->cameraControl = CAMERA_CONTROL;

			bCameraControlLock = FALSE;
		} else if (!LockCheck(pUser)) {
			if (pUser == NULL) {
				localCameraControl = CAMERA_CONTROL_LOCK;
				strcpy(lockUserName, iniData.settings.userName);
			} else {
				pUser->cameraControl = CAMERA_CONTROL_LOCK;
				strcpy(lockUserName, pUser->userName);
			}

			bCameraControlLock = TRUE;
		}
		ReadAndSendStatus();
		break;
	case COMMAND_MovePreset:
		if (!LockCheck(pUser)) {
			CommandSetPos(pUser,
				iniData.vcc1.preset[command.param1].panPos,
				iniData.vcc1.preset[command.param1].tiltPos,
				iniData.vcc1.preset[command.param1].zoomPos);
		}
		break;
	}

	return rc;
}

static	BOOL	LockCheck(PUSER pUser)
{
	if (bCameraControlLock) {
		if (pUser == NULL) {
			if (localCameraControl == CAMERA_CONTROL_LOCK)
				return FALSE;
		} else {
			if (pUser->cameraControl == CAMERA_CONTROL_LOCK)
				return FALSE;
		}
		return TRUE;
	}

	return FALSE;
}

static	void CommandOpenCamera(PUSER pUser)
{
	CAMERA_STATUS	status;

	if (iniData.settings.cameraType == 0)
		status.status = STATUS_NOSUPPORT;
	else if (!iniData.settings.bRemoteSetting && pUser != NULL)
		status.status = STATUS_DENY;
	else if (!OpenPort())
		status.status = STATUS_PORTERR;
	else {
		ReadStatus(status);
		if (!(status.status & STATUS_ERROR)) {
			if (pUser != NULL)
				pUser->cameraControl = CAMERA_CONTROL;
			else
				localCameraControl = CAMERA_CONTROL;

			status.status |= STATUS_INFO;
			status.vcc1 = iniData.vcc1;
		} else {
			ClosePort();
		}
	}

	SendStatus(status, pUser);
	if (status.status & STATUS_BUSY)
		StartCameraTimer();
}

void CommandCloseCamera(PUSER pUser)
{
	int	cameraControl;

	if (pUser != NULL) {
		cameraControl = pUser->cameraControl;
		pUser->cameraControl = 0;
	} else {
		cameraControl = localCameraControl;
		localCameraControl = 0;
	}

	if (cameraControl == CAMERA_CONTROL_LOCK) {
		bCameraControlLock = FALSE;
		ReadAndSendStatus();
	}

	ClosePort();

	StopCameraTimer();
}

static	void CommandSetPos(PUSER pUser, int panPos, int tiltPos, int zoomPos)
{
	CAMERA_STATUS	status;

	tmpPanPos = panPos;
	tmpTiltPos = tiltPos;
	tmpZoomPos = zoomPos;

	if (BusyCheck(status))
		StartCameraTimer();

	SendStatusAll(status);
}

static	BOOL BusyCheck(CAMERA_STATUS &status)
{
	int	sts;

	sts = ReadStatus(status);

	if (!(sts & (VCC1_STS_PAN | VCC1_STS_TILT))) {
		if (tmpPanPos != -1) {
			Vcc1SetPanPos(tmpPanPos);
			tmpPanPos = -1;
			ReadStatus(status);
		} else if (tmpTiltPos != -1) {
			Vcc1SetTiltPos(tmpTiltPos);
			tmpTiltPos = -1;
			ReadStatus(status);
		}
	}

	if (!(sts & VCC1_STS_ZOOM)) {
 		if (tmpZoomPos != -1) {
			if (tmpZoomPos == ZOOM_MAX)
				Vcc1SetZoomPos(tmpZoomPos - 1);
			else
				Vcc1SetZoomPos(tmpZoomPos);
			tmpZoomPos = -1;
			ReadStatus(status);
			busyCount = BUSY_COUNT_ZOOM;
		}
	}

	if (busyCount != 0) {
		status.status |= STATUS_BUSY;
		busyCount--;
	}

	return status.status & STATUS_BUSY;
}

static	int ReadStatus(CAMERA_STATUS &status)
{
	int	sts, tmpSts;
	int	panPos, tiltPos, zoomPos;
	static	int	prevSts;

	sts = Vcc1GetStatus();
	if (sts & VCC1_STS_NOANSWER) {
		status.status = STATUS_PORTERR;
		return sts;
	}

	if (sts & VCC1_STS_BUSY)
		status.status = STATUS_BUSY;
	else
		status.status = 0;

	if (sts & VCC1_STS_NOINIT) {
		if (!(status.status & STATUS_BUSY)) {
			InitializeVCC1();
			Vcc1Init2();
		}
		status.status = STATUS_INIT | STATUS_BUSY;
		return sts;
	}

	if (sts & VCC1_STS_BUSY) {
		tmpSts = sts | prevSts;
		if (tmpSts & VCC1_STS_PAN) {
			if ((panPos = Vcc1GetPanPos()) != -1)
				currentPanPos = panPos;
		}
		if (tmpSts & VCC1_STS_TILT) {
			if ((tiltPos = Vcc1GetTiltPos()) != -1)
				currentTiltPos = tiltPos;
		}
		if (tmpSts & VCC1_STS_ZOOM) {
			if ((zoomPos = Vcc1GetZoomPos()) != -1)
				currentZoomPos = zoomPos;
		}
	} else {
		if ((panPos = Vcc1GetPanPos()) != -1)
			currentPanPos = panPos;

		if ((tiltPos = Vcc1GetTiltPos()) != -1)
			currentTiltPos = tiltPos;

		if ((zoomPos = Vcc1GetZoomPos()) != -1)
			currentZoomPos = zoomPos;
	}

	status.status |= STATUS_POS;
	status.panPos = currentPanPos;
	status.tiltPos = currentTiltPos;
	status.zoomPos = currentZoomPos;

	prevSts = sts;

	return sts;
}

static	void SendStatus(CAMERA_STATUS &status, PUSER pUser)
{
	REMOTE_CAMERA_STS	remote;

	if (LockCheck(pUser)) {
		status.status |= STATUS_LOCKED;
		strcpy(status.userName, lockUserName);
	} else
		status.status &= ~STATUS_LOCKED;

	if (pUser == NULL) {
		if (pCameraWnd != NULL)
			pCameraWnd->SetCameraStatus(status);
	} else {
		int	len;

		remote.remote.header.flag = FLAG_REMOTE;
		remote.remote.header.id = localIPAddress;
		remote.remote.type = REMOTE_CAMERA_STATUS;
		remote.remote.id = pUser->id;
		remote.cameraType = iniData.settings.cameraType;
		remote.status = status;
		if (status.status & STATUS_INFO)
			len = sizeof(REMOTE_CAMERA_STS);
		else if (status.status & STATUS_LOCKED)
			len = sizeof(REMOTE_CAMERA_STS) - sizeof(VCC1DATA);
		else
			len = sizeof(REMOTE_CAMERA_STS) - sizeof(VCC1DATA) -
									32;
		SendData(&pUser->pSend->sock, &remote, len);
	}
}

static	void SendStatusAll(CAMERA_STATUS &status)
{
	PUSER	pUser;

	if (localCameraControl != 0)
		SendStatus(status, NULL);

	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
		if (pUser->cameraControl != 0 &&
					!(pUser->modeFlag & MODE_DLLCALL))
			SendStatus(status, pUser);
	}
}

static	void	ReadAndSendStatus()
{
	CAMERA_STATUS	status;

	ReadStatus(status);

	SendStatusAll(status);

	if ((status.status & STATUS_BUSY) || busyCount)
		StartCameraTimer();
}

void	SetCameraInfo(const VCC1DATA &vcc1, int flag)
{
	CAMERA_STATUS	status;

	iniData.vcc1 = vcc1;
	WriteIniFile();

	if (flag & INFO_INIT)
		InitializeCamera();

	ReadStatus(status);

	status.status |= STATUS_INFO;
	status.vcc1 = iniData.vcc1;

	SendStatusAll(status);
}

static	void	StartCameraTimer()
{
	if (lpfnTimerProc == NULL) {
		lpfnTimerProc = (TIMERPROC)MakeProcInstance((FARPROC)TimerProc,
						theApp.m_hInstance);
	}

	if (idTimer == 0)
		idTimer = SetTimer(NULL, 0, 200, lpfnTimerProc);
}

static	void	StopCameraTimer()
{
	if (idTimer != 0) {
		KillTimer(NULL, idTimer);
		idTimer = 0;
	}
}

void CALLBACK EXPORT TimerProc(HWND hWnd, UINT msg, UINT idTimer,
							DWORD dwTime)
{
	CAMERA_STATUS	status;
	static	int	busyTimer;

	if (BusyCheck(status)) {
		if (++busyTimer == 50) {
			Vcc1Stop();
			Vcc1ZoomStop();
		}
	} else {
		if (VCC1InitCounter != 0) {
			InitializeVCC1Timer();
			return;
		}
		StopCameraTimer();
		busyTimer = 0;
	}

	SendStatusAll(status);
}

void InitializeCamera()
{
	switch (iniData.settings.cameraType) {
	case CAMERA_VCC1:
		InitializeVCC1();
		break;
	}
}

static void InitializeVCC1()
{
	VCC1InitCounter = 1;
	StartCameraTimer();
}

static void InitializeVCC1Timer()
{
	switch (VCC1InitCounter) {
	case 1:
		if (!OpenPort()) {
			VCC1InitCounter = 0;
			return;
		}

		Vcc1SetPanSpeed(iniData.vcc1.panSpeed);
		break;
	case 2:
		Vcc1SetTiltSpeed(iniData.vcc1.tiltSpeed);
		break;
	case 3:
		switch (iniData.vcc1.shutterSpeed) {
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
		break;
	case 7:
		if (iniData.vcc1.bLightAdjust)
			Vcc1HoseiOn();
		else
			Vcc1HoseiOff();
		break;
	case 11:
		if (iniData.vcc1.bAutoWhiteBalance)
			Vcc1WhiteNormal();
		else
			Vcc1WhiteLock();
		break;
	case 15:
		if (iniData.vcc1.bAutoFocus)
			Vcc1FocusAf();
		else
			Vcc1FocusManual();

		ClosePort();
		VCC1InitCounter = 0;
		return;
	}

	VCC1InitCounter++;
}

static	BOOL	OpenPort()
{
	int	rc;

	if (portOpenCounter == 0) {
		rc = Vcc1Open(iniData.vcc1.serialPort);
		if (rc == 7) {
			Vcc1Close();
			rc = Vcc1Open(iniData.vcc1.serialPort);
			if (rc != 0)
				return FALSE;
		}
	}

	portOpenCounter++;

	return TRUE;
}

static	void	ClosePort()
{
	if (portOpenCounter != 0) {
		if (--portOpenCounter == 0)
			Vcc1Close();
	}
}
