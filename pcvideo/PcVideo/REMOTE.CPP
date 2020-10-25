/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: リモートコントロール処理
 *		ファイル名	: remote.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// remote.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "remote.h"
#include "pcvideo.h"
#include "mainfrm.h"
#include "setting.h"
#include "capture.h"
#include "chat.h"
#include <io.h>

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

static	void	SendRemoteCommand(PUSER, int);
static	void	SendRemoteMessage(PUSER, UINT);

void	RemoteSetting(CWnd *pWnd)
{
	int	n;
	PUSER	pUser;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			if ((GetKeyState(VK_SHIFT) & 0x8000) &&
					(GetKeyState(VK_CONTROL) & 0x8000)) {
				CRemoteDlg(pWnd, pUser).DoModal();
			} else
				SendRemoteCommand(pUser, REMOTE_REQDATA);
		}
	} else
		MessageBeep(0);
}

/////////////////////////////////////////////////////////////////////////////
// CRemoteDlg ダイアログ

CRemoteDlg::CRemoteDlg(CWnd* pParent /*=NULL*/, PUSER pUser)
	: CDialog(CRemoteDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CRemoteDlg)
	m_sDownLoadFile = _T("");
	//}}AFX_DATA_INIT
	this->pUser = pUser;
}

void CRemoteDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CRemoteDlg)
	DDX_Text(pDX, IDC_REMOTE_DOWNLOAD_FILE, m_sDownLoadFile);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CRemoteDlg, CDialog)
	//{{AFX_MSG_MAP(CRemoteDlg)
	ON_BN_CLICKED(IDC_REMOTE_CHAT, OnClickedRemoteChat)
	ON_BN_CLICKED(IDC_REMOTE_VOICE, OnClickedRemoteVoice)
	ON_BN_CLICKED(IDC_REMOTE_RESTART, OnClickedRemoteRestart)
	ON_BN_CLICKED(IDC_REMOTE_EXIT, OnClickedRemoteExit)
	ON_BN_CLICKED(IDC_REMOTE_SVOICE, OnClickedRemoteSvoice)
	ON_BN_CLICKED(IDC_REMOTE_EXEC, OnRemoteExec)
	ON_BN_CLICKED(IDC_REMOTE_DOWNLOAD, OnRemoteDownload)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CRemoteDlg メッセージハンドラ

BOOL CRemoteDlg::OnInitDialog()
{
	CDialog::OnInitDialog();
	
	char	buf[100];

	wsprintf(buf, "Remote control - (%s)", (LPSTR)pUser->userName);
	SetWindowText(buf);

	return TRUE;
}

void CRemoteDlg::OnClickedRemoteChat()
{
	SendRemoteCommand(pUser, REMOTE_CHAT);
}

void CRemoteDlg::OnClickedRemoteVoice()
{
	SendRemoteCommand(pUser, REMOTE_VOICE);
}

void CRemoteDlg::OnClickedRemoteRestart()
{
	SendRemoteCommand(pUser, REMOTE_RESTART);
}

void CRemoteDlg::OnClickedRemoteExit()
{
	SendRemoteCommand(pUser, REMOTE_EXIT);
}

void CRemoteDlg::OnClickedRemoteSvoice()
{
	SendRemoteCommand(pUser, REMOTE_SVOICE);
}

void CRemoteDlg::OnRemoteExec() 
{
	REMOTE_EXEC_CMD	remote;

	remote.remote.header.flag = FLAG_REMOTE;
	remote.remote.header.id = localIPAddress;
	remote.remote.type = REMOTE_EXEC_COMMAND;
	remote.remote.id = pUser->id;
	remote.mode = SW_HIDE;
	GetDlgItemText(IDC_REMOTE_COMMAND, remote.command, sizeof(remote.command));
	SendData(&pUser->pSend->sock, &remote, sizeof(remote));
}

void CRemoteDlg::OnRemoteDownload() 
{
	UpdateData(TRUE);

	if (m_sDownLoadFile.GetLength() == 0)
		return;

	
	REMOTE_DOWNLOAD_CMD	remote;

	remote.remote.header.flag = FLAG_REMOTE;
	remote.remote.header.id = localIPAddress;
	remote.remote.type = REMOTE_DOWNLOAD;
	remote.remote.id = pUser->id;
	strcpy(remote.fileName, m_sDownLoadFile);
	SendData(&pUser->pSend->sock, &remote, sizeof(remote));
}

static	void	SendRemoteCommand(PUSER pUser, int type)
{
	REMOTE	remote;

	remote.header.flag = FLAG_REMOTE;
	remote.header.id = localIPAddress;
	remote.type = type;
	remote.id = pUser->id;
	SendData(&pUser->pSend->sock, &remote, sizeof(remote));
}

static	void	SendRemoteMessage(PUSER pUser, UINT nID)
{
	CString msg;
	REMOTE_MSG	remote;

	msg.LoadString(nID);
	remote.remote.header.flag = FLAG_REMOTE;
	remote.remote.header.id = localIPAddress;
	remote.remote.type = REMOTE_MESSAGE;
	remote.remote.id = pUser->id;
	strcpy(remote.message, msg);
	SendData(&pUser->pSend->sock, &remote, sizeof(remote));
}

void	ReceiveRemote(PUSER pUser, PREMOTE pRemote)
{
	PSEND	pSend;
	REMOTE_SETTINGS	remote;
	SETTINGS	settings;
	CString	msg;
	char	fileName[128], *p;
	extern	void	StartVoiceInput();

	pSend = pUser->pSend;

	if (pRemote->id != localIPAddress && pRemote->id != 0L)
		return;

	switch (pRemote->type) {
	case REMOTE_REQDATA:
		remote.remote.header.flag = FLAG_REMOTE;
		remote.remote.header.id = localIPAddress;

		if (iniData.settings.bRemoteSetting) {
			remote.remote.type = REMOTE_DATA;
			remote.remote.id = pUser->id;
			remote.settings = iniData.settings;
			SendData(&pSend->sock, &remote, sizeof(REMOTE_SETTINGS));
		} else {
			remote.remote.type = REMOTE_DENY;
			remote.remote.id = pUser->id;

			SendData(&pSend->sock, &remote, sizeof(REMOTE));
		}
		break;
	case REMOTE_DATA:
		settings = ((PREMOTE_SETTINGS)pRemote)->settings;
		msg.Format(IDS_REMOTE_SETTING, (LPSTR)pUser->userName);
		if (CSettingDlg().Open( &settings, msg)) {
			remote.remote.header.flag = FLAG_REMOTE;
			remote.remote.header.id = localIPAddress;
			remote.remote.type = REMOTE_SETDATA;
			remote.remote.id = pUser->id;
			remote.settings = settings;
			SendData(&pSend->sock, &remote,
						sizeof(REMOTE_SETTINGS));
		}
		break;
	case REMOTE_SETDATA:
		iniData.settings = ((PREMOTE_SETTINGS)pRemote)->settings;
		WriteIniFile();
		if (pCaptureWnd != NULL)
			pCaptureWnd->SetWindowText(iniData.settings.userName);
		bChangeCaptureMode = TRUE;
		break;
	case REMOTE_DENY:
		MessageBeep(0);
		msg.Format(IDS_NO_REMOTE, (LPSTR)pUser->userName);
		AfxMessageBox(msg);
		break;
	case REMOTE_RESTART:
		lstrcpy(fileName, theApp.m_pszHelpFilePath);
		if ((p = strrchr(fileName, '\\')) != NULL)
			strcpy(p, "\\PCVIDEO.EXE");
		else
			strcat(fileName, "PCVIDEO.EXE");

		if (access(fileName, 0) != 0) {
			SendRemoteMessage(pUser, IDS_NO_PCVIDEO);
			break;
		}

		lstrcpy(fileName, theApp.m_pszHelpFilePath);
		if ((p = strrchr(fileName, '\\')) != NULL)
			strcpy(p, "\\PCVSTART.EXE");
		else
			strcat(fileName, "PCVSTART.EXE");
		if (access(fileName, 0) != 0) {
			SendRemoteMessage(pUser, IDS_NO_PCVSTART);
			break;
		}

		bRestart = TRUE;
		pMainWnd->PostMessage(WM_CLOSE, 0, 0L);
		SendRemoteMessage(pUser, IDS_PCVIDEO_RESTART);
		break;
	case REMOTE_CHAT:
		if (pChatWnd)
			CloseChatWindow();
		else
			OpenChatWindow();
		break;
	case REMOTE_VOICE:
		if (pVoiceWnd)
			CloseVoiceWindow();
		else if (bVoice)
			OpenVoiceWindow();
		break;
	case REMOTE_EXIT:
		pMainWnd->PostMessage(WM_CLOSE, 0, 0L);
		SendRemoteMessage(pUser, IDS_END_PCVIDEO);
		break;
	case REMOTE_SVOICE:
		if (pVoiceWnd)
			StartVoiceInput();
		break;
	case REMOTE_MESSAGE:
		msg.Format(IDS_RECV_MESSAGE,
				(LPSTR)pUser->userName,
				(LPSTR)((PREMOTE_MSG)pRemote)->message);
		AfxMessageBox(msg, MB_OK);
		break;
	case REMOTE_CAMERA_COMMAND:
		ReceiveCameraCommand(pUser, (PREMOTE_CAMERA_CMD)pRemote);
		break;
	case REMOTE_CAMERA_STATUS:
		ReceiveCameraStatus(pUser, (PREMOTE_CAMERA_STS)pRemote);
		break;
	case REMOTE_CAMERA_SETINFO:
		ReceiveCameraInfo(pUser, (PREMOTE_CAMERA_SET)pRemote);
		break;
	case REMOTE_CAPTURE_COMMAND:
		ReceiveCaptureCommand(pUser, (PREMOTE_CAPTURE_CMD)pRemote);
		break;
	case REMOTE_EXEC_COMMAND:
		WinExec(((PREMOTE_EXEC_CMD)pRemote)->command,
					((PREMOTE_EXEC_CMD)pRemote)->mode);
		break;
	case REMOTE_DOWNLOAD:
		if (access(((PREMOTE_DOWNLOAD_CMD)pRemote)->fileName, 0) != 0) {
			SendRemoteMessage(pUser, IDS_NO_FILE);
			break;
		}
		downLoadFile = ((PREMOTE_DOWNLOAD_CMD)pRemote)->fileName;
		bRestart = TRUE;
		pMainWnd->PostMessage(WM_CLOSE, 0, 0L);
		SendRemoteMessage(pUser, IDS_DOWNLOAD);
		break;
	}
}
