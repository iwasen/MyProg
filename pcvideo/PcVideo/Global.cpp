#include "stdafx.h"
#include "PCVIDEO.h"
#include "MainFrm.h"
#include "pcvcomm.h"
#include "receive.h"
#include "capture.h"

static	void	SendConxxxt(PUSER, PSOCK, int);

////////////////////////////////////////
//	全ホストに接続パケット送信
////////////////////////////////////////
void	SendConxxxtAll()
{
	PSEND	pSend;

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain)
		SendConxxxt1(pSend);
}

////////////////////////////////////////
//	特定ホストに接続パケット送信
////////////////////////////////////////
void	SendConxxxt1(PSEND pSend)
{
	SendConxxxt(pSend->pUser, &pSend->sock, pSend->pUser->channelNo);
}

////////////////////////////////////////
//	指定したホストに接続パケット送信
////////////////////////////////////////
void	SendConxxxt2(u_long IPAddress, u_short portNo, int channelNo)
{
	SOCK	sock;

	sock.bTCP = FALSE;
	sock.sock = sockUDP;
	sock.IPAddress = IPAddress;
	sock.portNo = portNo;

	SendConxxxt(NULL, &sock, channelNo);
}

static	void	SendConxxxt(PUSER pUser, PSOCK pSock, int channelNo)
{
	char	conxxxt[sizeof(CONxxxT) + MAX_VIDEO_WINDOW * sizeof(long)];
	u_long	*pLong;

	// 接続パケット作成
	MakeConxxxtPacket((PCONxxxT)conxxxt, channelNo);

	pLong = (u_long *)&conxxxt[sizeof(CONxxxT)];
	for ( ; pUser != NULL; pUser = pUser->pSendUser) {
		if (pUser->pVideo != NULL && !pUser->pVideo->m_bStill)
			*pLong++ = pUser->id;
	}

	// パケット送信
	if (SendData(pSock, conxxxt, (char *)pLong - conxxxt) == -1)
		conxxxtIPAddress = 0;

	if (pSock->IPAddress == guardIPAddress)
		guardIPAddress = 0;

	WSAAsyncSelect(sockUDP, pMainWnd->m_hWnd, WM_SOCKETUDP, FD_READ);
}

void MakeConxxxtPacket(PCONxxxT pConxxxt, int channelNo)
{
	pConxxxt->header.flag = FLAG_CONxxxT;
	pConxxxt->header.id = localIPAddress;
	strcpy(pConxxxt->userName, iniData.settings.userName);
	pConxxxt->modeFlag = MODE_VIDEOREQ | MODE_NEWVERSION;
	if (pCaptureWnd && pCaptureWnd->IsWindowVisible())
		pConxxxt->modeFlag |= MODE_SENDVIDEO;
	if (pVoiceWnd) {
		pConxxxt->modeFlag |= MODE_SENDVOICE;
		pConxxxt->modeFlag |= MODE_VOICEREQ;
	}
	if (pChatWnd)
		pConxxxt->modeFlag |= MODE_CHAT;
	pConxxxt->channelNo = channelNo;
}

////////////////////////////////////////
//	切断パケット送信
////////////////////////////////////////
void	SendDisconxxxt(PSOCK pSock)
{
	HEADER	header;

	// 切断パケット作成
	header.flag = FLAG_DISCONxxxT;
	header.id = localIPAddress;

	// パケット送信
	SendData(pSock, &header, sizeof(header));

	if (!pSock->bTCP) {
		guardIPAddress = pSock->IPAddress;
		guardTimer = 0;
	}
}

//	.INI ファイルのセクション名
static	char	sectionConxxxt[] = "Conxxxt";
static	char	sectionSettings[] = "Settings";
static	char	sectionMainWindow[] = "MainWindow";
static	char	sectionChatWindow[] = "ChatWindow";
static	char	sectionVoiceWindow[] = "VoiceWindow";
static	char	sectionCameraWindow[] = "CameraWindow";
static	char	sectionHostList[] = "HostList";
static	char	sectionVideoWindow[] = "VideoWindow";
static	char	sectionLicense[] = "License";
static	char	sectionVcc1[] = "VC-C1";
static	char	sectionLogin[] = "Login";

////////////////////////////////////////
//	.INI ファイル読み込み
////////////////////////////////////////
void	ReadIniFile()
{
	int	i;
	char	entry[20];
	char	buf[100];
	char	*p;

	// "Settings" senction
	GetPrivateProfileString(sectionSettings, "Target", "",
			iniData.hostList.target,
			sizeof(iniData.hostList.target),
			iniFile);

	iniData.hostList.channelNo = GetPrivateProfileInt(sectionSettings,
			"ChannelNo", 0, iniFile);

	GetPrivateProfileString(sectionSettings, "UserName", "",
			iniData.settings.userName,
			sizeof(iniData.settings.userName),
			iniFile);

	GetPrivateProfileString(sectionSettings, "VideoCapture", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bVideoCapture = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "ReceiveVideo", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bReceiveVideo =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "RemoteSetting", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bRemoteSetting =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	iniData.settings.movieSize = GetPrivateProfileInt(sectionSettings,
			"MovieSize", 7, iniFile);

	iniData.settings.stillSize = GetPrivateProfileInt(sectionSettings,
			"StillSize", 7, iniFile);

	iniData.settings.videoColor = GetPrivateProfileInt(sectionSettings,
			"VideoColors", COLOR_24, iniFile);

	iniData.settings.cameraType = GetPrivateProfileInt(sectionSettings,
			"CameraType", 0, iniFile);

	iniData.settings.videoQuality = GetPrivateProfileInt(sectionSettings,
			"VideoQuality", 50, iniFile);

	GetPrivateProfileString(sectionSettings, "CameraChange", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bCameraChange =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	iniData.settings.videoEdgeEnhance =
			GetPrivateProfileInt(sectionSettings,
			"VideoEdgeEnhance", 3, iniFile);

	iniData.settings.maxBPS = GetPrivateProfileInt(sectionSettings,
			"MaxBPS", 32, iniFile);

	GetPrivateProfileString(sectionSettings, "MaxSpeed", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bMaxSpeed = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "SendInfo", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bSendInfo =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "FullFrame", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bFullFrame =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "Resize", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bResize =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "FixedAspect", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bFixedAspect =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "FixedSize", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bFixedSize =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	iniData.settings.recvSize = GetPrivateProfileInt(sectionSettings,
			"RecvSize", 5, iniFile);

	GetPrivateProfileString(sectionSettings, "DispInfo", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bDispInfo =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionSettings, "FrontKeep", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.settings.bFrontKeep =
			(strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	// "Conxxxt" section
	for (i = 0; i < N_INI_CONxxxT; i++) {
		wsprintf(entry, "Host%d", i+1);
		GetPrivateProfileString(sectionConxxxt, entry, "",
						buf, sizeof(buf), iniFile);

		if ((p = strtok(buf, ",")) != NULL) {
			strcpy(iniData.conxxxt.host[i], p);
			if ((p = strtok(NULL, ",")) != NULL) {
				iniData.conxxxt.channelNo[i] = atoi(p);
				if ((p = strtok(NULL, ",")) != NULL)
					strcpy(iniData.conxxxt.name[i], p);
			}
		}
	}

	// "MainWindow" section
	iniData.mainWindow.left = GetPrivateProfileInt(sectionMainWindow,
			"Left", 100, iniFile);

	iniData.mainWindow.right = GetPrivateProfileInt(sectionMainWindow,
			"Right", 420, iniFile);

	iniData.mainWindow.top = GetPrivateProfileInt(sectionMainWindow,
			"Top", 100, iniFile);

	iniData.mainWindow.bottom = GetPrivateProfileInt(sectionMainWindow,
			"Bottom", 340, iniFile);

	iniData.mainWindow.nListOrder = GetPrivateProfileInt(sectionMainWindow,
			"ListOrder", 0, iniFile);

	GetPrivateProfileString(sectionMainWindow, "DispAddress", "FALSE", buf, sizeof(buf), iniFile);
	iniData.mainWindow.bDispAddress = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionMainWindow, "SendVideo", "TRUE", buf, sizeof(buf), iniFile);
	iniData.mainWindow.bSendVideo = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	// "ChatWindow" section
	iniData.chatWindow.left = GetPrivateProfileInt(sectionChatWindow,
			"Left", 32, iniFile);

	iniData.chatWindow.right = GetPrivateProfileInt(sectionChatWindow,
			"Right", 470, iniFile);

	iniData.chatWindow.top = GetPrivateProfileInt(sectionChatWindow,
			"Top", 32, iniFile);

	iniData.chatWindow.bottom = GetPrivateProfileInt(sectionChatWindow,
			"Bottom", 350, iniFile);

	iniData.chatWindow.splitter = GetPrivateProfileInt(sectionChatWindow,
			"Splitter", 180, iniFile);

	GetPrivateProfileString(sectionChatWindow, "Font", "",
			iniData.chatWindow.font,
			sizeof(iniData.chatWindow.font),
			iniFile);

	iniData.chatWindow.fontSize = GetPrivateProfileInt(sectionChatWindow,
			"FontSize", 13, iniFile);

	GetPrivateProfileString(sectionChatWindow, "LogFile", "",
			iniData.chatWindow.logFile,
			sizeof(iniData.chatWindow.logFile),
			iniFile);

	GetPrivateProfileString(sectionChatWindow, "Append", "FALSE", buf, sizeof(buf), iniFile);
	iniData.chatWindow.bAppend = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	GetPrivateProfileString(sectionChatWindow, "AlwaysLog", "FALSE", buf, sizeof(buf), iniFile);
	iniData.chatWindow.bAlwaysLog = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	// "VoiceWindow" section
	iniData.voiceWindow.left = GetPrivateProfileInt(sectionVoiceWindow, "Left", 32, iniFile);

	iniData.voiceWindow.top = GetPrivateProfileInt(sectionVoiceWindow, "Top", 32, iniFile);

	iniData.voiceWindow.mode = GetPrivateProfileInt(sectionVoiceWindow, "Mode", 0, iniFile);

	iniData.voiceWindow.autoSendLevel =
			GetPrivateProfileInt(sectionVoiceWindow,
			"AutoSendLevel", -20, iniFile);

	iniData.voiceWindow.bufferCount =
			GetPrivateProfileInt(sectionVoiceWindow,
			"BufferCount", 4, iniFile);
	iniData.voiceWindow.bufferCount = 3;

	GetPrivateProfileString(sectionVoiceWindow, "AutoLevelControl", "TRUE", buf, sizeof(buf), iniFile);
	iniData.voiceWindow.bAutoLevelControl = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;

	iniData.voiceWindow.nInputDevice = GetPrivateProfileInt(sectionVoiceWindow, "InputDevice", 0, iniFile);
	iniData.voiceWindow.nOutputDevice = GetPrivateProfileInt(sectionVoiceWindow, "OutputDevice", 0, iniFile);

	iniData.voiceWindow.nBufferNum = GetPrivateProfileInt(sectionVoiceWindow, "BufferNum", 3, iniFile);

	// "CameraWindow" section
	iniData.cameraWindow.left = GetPrivateProfileInt(sectionCameraWindow, "Left", 32, iniFile);

	iniData.cameraWindow.top = GetPrivateProfileInt(sectionCameraWindow, "Top", 32, iniFile);

	// "HostList" section
	for (i = 0; i < N_INI_HOSTLIST; i++) {
		wsprintf(entry, "Host%d", i+1);
		GetPrivateProfileString(sectionHostList, entry, "",
				iniData.hostList.host[i],
				sizeof(iniData.hostList.host[0]),
				iniFile);
	}

	// "VideoWindow" section
	for (i = 0; i < N_INI_VIDEOWINDOW; i++) {
		wsprintf(entry, "Window%d", i);
		wsprintf(buf, "%d,%d", (i + 1) * 8, (i + 1) * 8);
		GetPrivateProfileString(sectionVideoWindow, entry, buf,
				buf, sizeof(buf), iniFile);
		if ((p = strchr(buf, ',')) != NULL) {
			*p++ = '\0';
			iniData.videoWindow.left[i] = atoi(buf);
			iniData.videoWindow.top[i] = atoi(p);
		}
	}

	// "License" section
	GetPrivateProfileString(sectionLicense, "RegistNo", "",
			iniData.license.registNo,
			sizeof(iniData.license.registNo),
			iniFile);

	// "VC-C1" section
	iniData.vcc1.serialPort = GetPrivateProfileInt(sectionVcc1,
			"SerialPort", 0, iniFile);

	iniData.vcc1.panSpeed = GetPrivateProfileInt(sectionVcc1,
			"PanSpeed", 500, iniFile);

	iniData.vcc1.tiltSpeed = GetPrivateProfileInt(sectionVcc1,
			"TiltSpeed", 500, iniFile);

	iniData.vcc1.zoomSpeed = GetPrivateProfileInt(sectionVcc1,
			"ZoomSpeed", 0, iniFile);

	iniData.vcc1.shutterSpeed = GetPrivateProfileInt(sectionVcc1,
			"ShutterSpeed", 0, iniFile);

	iniData.vcc1.audioVolume = GetPrivateProfileInt(sectionVcc1,
			"AudioVolume", 255, iniFile);

	GetPrivateProfileString(sectionVcc1, "AutoFocus", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.vcc1.bAutoFocus = (strcmp(buf, "TRUE") == 0);

	GetPrivateProfileString(sectionVcc1, "LightAdjust", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.vcc1.bLightAdjust = (strcmp(buf, "TRUE") == 0);

	GetPrivateProfileString(sectionVcc1, "AutoWhiteBalance", "TRUE",
			buf, sizeof(buf), iniFile);
	iniData.vcc1.bAutoWhiteBalance = (strcmp(buf, "TRUE") == 0);

	GetPrivateProfileString(sectionVcc1, "WideConverter", "FALSE",
			buf, sizeof(buf), iniFile);
	iniData.vcc1.bWideConverter = (strcmp(buf, "TRUE") == 0);

	for (i = 0; i < 8; i++) {
		wsprintf(entry, "Preset%d", i + 1);
		GetPrivateProfileString(sectionVcc1, entry, "",
					buf, sizeof(buf), iniFile);
		if ((p = strtok(buf, ",")) != NULL)
			iniData.vcc1.preset[i].panPos = atoi(p);
		else
			iniData.vcc1.preset[i].panPos = 0x28a;

		if ((p = strtok(NULL, ",")) != NULL)
			iniData.vcc1.preset[i].tiltPos = atoi(p);
		else
			iniData.vcc1.preset[i].tiltPos = 0x121;

		if ((p = strtok(NULL, ",")) != NULL)
			iniData.vcc1.preset[i].zoomPos = atoi(p);
		else
			iniData.vcc1.preset[i].zoomPos = 0x40;

		if ((p = strtok(NULL, ",")) != NULL)
			strcpy(iniData.vcc1.preset[i].comment, p);
		else
			iniData.vcc1.preset[i].comment[0] = '\0';
	}

	// "Login" section
	for (i = 0; i < N_INI_LOGIN; i++) {
		wsprintf(entry, "Server%d", i);
		GetPrivateProfileString(sectionLogin, entry, "0", buf, sizeof(buf), iniFile);
		iniData.login[i].serverID = atol(buf);

		wsprintf(entry, "UserID%d", i);
		GetPrivateProfileString(sectionLogin, entry, "",
				iniData.login[i].userID, sizeof(iniData.login[i].userID), iniFile);

		wsprintf(entry, "Password%d", i);
		GetPrivateProfileString(sectionLogin, entry, "",
				iniData.login[i].password, sizeof(iniData.login[i].password), iniFile);

		wsprintf(entry, "Save%d", i);
		GetPrivateProfileString(sectionLogin, entry, "FALSE", buf, sizeof(buf), iniFile);
		iniData.login[i].bSavePassword = (strcmp(buf, "TRUE") == 0) ? TRUE : FALSE;
	}
}

////////////////////////////////////////
//	.INI ファイル書き込み
////////////////////////////////////////
void	WriteIniFile()
{
	int	i;
	char	entry[20];
	char	buf[100];

	// "Settings" section
	WritePrivateProfileString(sectionSettings, "Target",
			iniData.hostList.target, iniFile);

	wsprintf(buf, "%d", iniData.hostList.channelNo);
	WritePrivateProfileString(sectionSettings, "ChannelNo", buf, iniFile);

	WritePrivateProfileString(sectionSettings, "UserName",
			iniData.settings.userName, iniFile);

	WritePrivateProfileString(sectionSettings, "VideoCapture",
			iniData.settings.bVideoCapture ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "ReceiveVideo",
			iniData.settings.bReceiveVideo ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "RemoteSetting",
			iniData.settings.bRemoteSetting ? "TRUE" : "FALSE", iniFile);

	wsprintf(buf, "%d", iniData.settings.movieSize);
	WritePrivateProfileString(sectionSettings, "MovieSize", buf, iniFile);

	wsprintf(buf, "%d", iniData.settings.stillSize);
	WritePrivateProfileString(sectionSettings, "StillSize", buf, iniFile);

	wsprintf(buf, "%d", iniData.settings.videoColor);
	WritePrivateProfileString(sectionSettings, "VideoColors", buf, iniFile);

	wsprintf(buf, "%d", iniData.settings.cameraType);
	WritePrivateProfileString(sectionSettings, "CameraType", buf, iniFile);

	wsprintf(buf, "%d", iniData.settings.videoQuality);
	WritePrivateProfileString(sectionSettings, "VideoQuality", buf, iniFile);

	WritePrivateProfileString(sectionSettings, "CameraChange",
			iniData.settings.bCameraChange ? "TRUE" : "FALSE", iniFile);

	wsprintf(buf, "%d", iniData.settings.videoEdgeEnhance);
	WritePrivateProfileString(sectionSettings, "VideoEdgeEnhance", buf, iniFile);

	wsprintf(buf, "%d", iniData.settings.maxBPS);
	WritePrivateProfileString(sectionSettings, "MaxBPS", buf, iniFile);

	WritePrivateProfileString(sectionSettings, "maxSpeed",
			iniData.settings.bMaxSpeed ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "SendInfo",
			iniData.settings.bSendInfo ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "FullFrame",
			iniData.settings.bFullFrame ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "Resize",
			iniData.settings.bResize ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "FixedAspect",
			iniData.settings.bFixedAspect ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "FixedSize",
			iniData.settings.bFixedSize ? "TRUE" : "FALSE", iniFile);

	wsprintf(buf, "%d", iniData.settings.recvSize);
	WritePrivateProfileString(sectionSettings, "RecvSize", buf, iniFile);

	WritePrivateProfileString(sectionSettings, "DispInfo",
			iniData.settings.bDispInfo ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionSettings, "FrontKeep",
			iniData.settings.bFrontKeep ? "TRUE" : "FALSE", iniFile);

	// "Conxxxt" section
	for (i = 0; i < N_INI_CONxxxT; i++) {
		wsprintf(entry, "Host%d", i+1);
		if (iniData.conxxxt.host[i][0] != '\0')
			wsprintf(buf, "%s,%d,%s",
					(LPSTR)iniData.conxxxt.host[i],
					iniData.conxxxt.channelNo[i],
					(LPSTR)iniData.conxxxt.name[i]);
		else
			buf[0] = '\0';
		WritePrivateProfileString(sectionConxxxt, entry, buf, iniFile);
	}

	// "MainWindow" section
	wsprintf(buf, "%d", iniData.mainWindow.left);
	WritePrivateProfileString(sectionMainWindow, "Left", buf, iniFile);

	wsprintf(buf, "%d", iniData.mainWindow.top);
	WritePrivateProfileString(sectionMainWindow, "Top", buf, iniFile);

	wsprintf(buf, "%d", iniData.mainWindow.right);
	WritePrivateProfileString(sectionMainWindow, "Right", buf, iniFile);

	wsprintf(buf, "%d", iniData.mainWindow.bottom);
	WritePrivateProfileString(sectionMainWindow, "Bottom", buf, iniFile);

	wsprintf(buf, "%d", iniData.mainWindow.nListOrder);
	WritePrivateProfileString(sectionMainWindow, "ListOrder", buf, iniFile);

	WritePrivateProfileString(sectionMainWindow, "DispAddress",
			iniData.mainWindow.bDispAddress ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionMainWindow, "SendVideo",
			iniData.mainWindow.bSendVideo ? "TRUE" : "FALSE", iniFile);

	// "ChatWindow" section
	wsprintf(buf, "%d", iniData.chatWindow.left);
	WritePrivateProfileString(sectionChatWindow, "Left", buf, iniFile);

	wsprintf(buf, "%d", iniData.chatWindow.top);
	WritePrivateProfileString(sectionChatWindow, "Top", buf, iniFile);

	wsprintf(buf, "%d", iniData.chatWindow.right);
	WritePrivateProfileString(sectionChatWindow, "Right", buf, iniFile);

	wsprintf(buf, "%d", iniData.chatWindow.bottom);
	WritePrivateProfileString(sectionChatWindow, "Bottom", buf, iniFile);

	wsprintf(buf, "%d", iniData.chatWindow.splitter);
	WritePrivateProfileString(sectionChatWindow, "Splitter", buf, iniFile);

	WritePrivateProfileString(sectionChatWindow, "Font",
			iniData.chatWindow.font, iniFile);

	wsprintf(buf, "%d", iniData.chatWindow.fontSize);
	WritePrivateProfileString(sectionChatWindow, "FontSize", buf, iniFile);

	WritePrivateProfileString(sectionChatWindow, "LogFile",
			iniData.chatWindow.logFile, iniFile);

	WritePrivateProfileString(sectionChatWindow, "Append",
			iniData.chatWindow.bAppend ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionChatWindow, "AlwaysLog",
			iniData.chatWindow.bAlwaysLog ? "TRUE" : "FALSE", iniFile);

	// "VoiceWindow" section
	wsprintf(buf, "%d", iniData.voiceWindow.left);
	WritePrivateProfileString(sectionVoiceWindow, "Left", buf, iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.top);
	WritePrivateProfileString(sectionVoiceWindow, "Top", buf, iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.mode);
	WritePrivateProfileString(sectionVoiceWindow, "Mode", buf, iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.autoSendLevel);
	WritePrivateProfileString(sectionVoiceWindow, "AutoSendLevel", buf, iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.bufferCount);
	WritePrivateProfileString(sectionVoiceWindow, "BufferCount", buf, iniFile);

	WritePrivateProfileString(sectionVoiceWindow, "AutoLevelControl",
			iniData.voiceWindow.bAutoLevelControl ? "TRUE" : "FALSE", iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.nInputDevice);
	WritePrivateProfileString(sectionVoiceWindow, "InputDevice", buf, iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.nOutputDevice);
	WritePrivateProfileString(sectionVoiceWindow, "OutputDevice", buf, iniFile);

	wsprintf(buf, "%d", iniData.voiceWindow.nBufferNum);
	WritePrivateProfileString(sectionVoiceWindow, "BufferNum", buf, iniFile);

	// "CameraWindow" section
	wsprintf(buf, "%d", iniData.cameraWindow.left);
	WritePrivateProfileString(sectionCameraWindow, "Left", buf, iniFile);

	wsprintf(buf, "%d", iniData.cameraWindow.top);
	WritePrivateProfileString(sectionCameraWindow, "Top", buf, iniFile);

	// "HostList" section
	for (i = 0; i < N_INI_HOSTLIST; i++) {
		wsprintf(entry, "Host%d", i+1);
		WritePrivateProfileString(sectionHostList, entry,
				iniData.hostList.host[i], iniFile);
	}

	// "VideoWindow" section
	for (i = 0; i < N_INI_VIDEOWINDOW; i++) {
		wsprintf(entry, "Window%d", i);
		wsprintf(buf, "%d,%d", iniData.videoWindow.left[i],
						iniData.videoWindow.top[i]);
		WritePrivateProfileString(sectionVideoWindow, entry, buf,
								iniFile);
	}

	// "License" section
//	WritePrivateProfileString(sectionLicense, "registNo",
//				iniData.license.registNo, iniFile);

	// "VC-C1" section
	wsprintf(buf, "%d", iniData.vcc1.serialPort);
	WritePrivateProfileString(sectionVcc1, "SerialPort", buf, iniFile);

	wsprintf(buf, "%d", iniData.vcc1.panSpeed);
	WritePrivateProfileString(sectionVcc1, "PanSpeed", buf, iniFile);


	wsprintf(buf, "%d", iniData.vcc1.tiltSpeed);
	WritePrivateProfileString(sectionVcc1, "TiltSpeed", buf, iniFile);

	wsprintf(buf, "%d", iniData.vcc1.zoomSpeed);
	WritePrivateProfileString(sectionVcc1, "ZoomSpeed", buf, iniFile);

	wsprintf(buf, "%d", iniData.vcc1.shutterSpeed);
	WritePrivateProfileString(sectionVcc1, "ShutterSpeed", buf, iniFile);

	wsprintf(buf, "%d", iniData.vcc1.audioVolume);
	WritePrivateProfileString(sectionVcc1, "AudioVolume", buf, iniFile);

	WritePrivateProfileString(sectionVcc1, "AutoFocus",
		iniData.vcc1.bAutoFocus ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionVcc1, "LightAdjust",
		iniData.vcc1.bLightAdjust ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionVcc1, "AutoWhiteBalance",
		iniData.vcc1.bAutoWhiteBalance ? "TRUE" : "FALSE", iniFile);

	WritePrivateProfileString(sectionVcc1, "WideConverter",
		iniData.vcc1.bWideConverter ? "TRUE" : "FALSE", iniFile);

	for (i = 0; i < 8; i++) {
		wsprintf(entry, "Preset%d", i + 1);
		wsprintf(buf, "%d,%d,%d,%s",
				iniData.vcc1.preset[i].panPos,
				iniData.vcc1.preset[i].tiltPos,
				iniData.vcc1.preset[i].zoomPos,
				(LPSTR)iniData.vcc1.preset[i].comment);
		WritePrivateProfileString(sectionVcc1, entry, buf, iniFile);
	}

	// "Login" section
	for (i = 0; i < N_INI_LOGIN; i++) {
		if (iniData.login[i].serverID == 0)
			break;

		wsprintf(entry, "Server%d", i);
		wsprintf(buf, "%lu", iniData.login[i].serverID);
		WritePrivateProfileString(sectionLogin, entry, buf, iniFile);

		wsprintf(entry, "UserID%d", i);
		WritePrivateProfileString(sectionLogin, entry, iniData.login[i].userID, iniFile);

		wsprintf(entry, "Password%d", i);
		WritePrivateProfileString(sectionLogin, entry, iniData.login[i].password, iniFile);

		wsprintf(entry, "Save%d", i);
		WritePrivateProfileString(sectionLogin, entry, iniData.login[i].bSavePassword ? "TRUE" : "FALSE", iniFile);
	}
}

void	ConxxxtHost(char *hostName, int channelNo)
{
	PSEND	pSend;
	LPHOSTENT hp;
	int	i;
	u_long	addr;

	addr = inet_addr(hostName);
	if (addr == INADDR_NONE) {
		hp = gethostbyname(hostName);
		if (hp == NULL) {
			AfxMessageBox(IDS_NO_HOSTNAME, MB_OK | MB_ICONEXCLAMATION);
			return;
		} else
			addr = *(u_long *)hp->h_addr;
	}

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		if (pSend->sock.IPAddress == addr) {
			if (pSend->pUser->channelNo != channelNo) {
				SendDisconxxxt(&pSend->sock);
				DeleteSend(pSend);
			}
			break;
		}
	}

	SendConxxxt2(addr, htons(PORTNO2), channelNo);
	SendConxxxt2(addr, htons(PORTNO), channelNo);
	conxxxtIPAddress = addr;
	conxxxtChannelNo = channelNo;
	conxxxtTimer = 0;

	for (i = 0; i < 8; i++) {
		if (strcmp(hostName, iniData.hostList.host[i]) == 0) {
			for ( ; i < 7; i++) {
				strcpy(iniData.hostList.host[i],
						iniData.hostList.host[i+1]);
			}
			break;
		}
	}
	for (i = 7; i > 0; i--)
		strcpy(iniData.hostList.host[i], iniData.hostList.host[i-1]);
	strcpy(iniData.hostList.host[0], hostName);
	strcpy(iniData.hostList.target, hostName);
	iniData.hostList.channelNo = channelNo;
}
