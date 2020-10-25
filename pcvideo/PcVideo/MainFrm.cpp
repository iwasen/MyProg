// MainFrm.cpp : CMainFrame クラスの動作の定義を行います。
//

#include "stdafx.h"
#include "PcVideo.h"
#include "MainFrm.h"
#include "pcvcomm.h"
#include "capture.h"
#include "chat.h"
#include "camera.h"
#include "setting.h"
#include "ConxxxtDlg.h"
#include "receive.h"
#include "voice.h"
#include "call.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMainFrame

IMPLEMENT_DYNCREATE(CMainFrame, CFrameWnd)

BEGIN_MESSAGE_MAP(CMainFrame, CFrameWnd)
	//{{AFX_MSG_MAP(CMainFrame)
	ON_WM_CREATE()
	ON_WM_CLOSE()
	ON_WM_SIZE()
	ON_COMMAND(IDM_SETTING, OnSetting)
	ON_COMMAND(IDM_SETHOST, OnSethost)
	ON_COMMAND(IDM_VIDEO_SOURCE, OnVideoSource)
	ON_COMMAND(IDM_REMOTE_SETTING, OnRemoteSetting)
	ON_COMMAND(IDM_CONxxxT, OnConxxxt)
	ON_COMMAND(IDM_CHAT, OnChat)
	ON_COMMAND(IDM_VERSION, OnVersion)
	ON_COMMAND(IDM_DISCONxxxT, OnDisconxxxt)
	ON_WM_ACTIVATE()
	ON_WM_ACTIVATEAPP()
	ON_WM_TIMER()
	ON_COMMAND(IDM_TILE, OnTile)
	ON_COMMAND(IDM_RECV_DISP, OnRecvDisp)
	ON_COMMAND(IDM_RECV_STILL, OnRecvStill)
	ON_COMMAND(IDM_SEND_DISP, OnSendDisp)
	ON_COMMAND(IDM_SEND_STILL, OnSendStill)
	ON_COMMAND(IDM_RECV_CAMERA, OnRecvCamera)
	ON_COMMAND(IDM_SEND_CAMERA, OnSendCamera)
	ON_COMMAND(IDM_VOICE, OnVoice)
	ON_COMMAND(IDM_IPADDRESS, OnIpaddress)
	ON_COMMAND(IDM_FRONT, OnFront)
	ON_COMMAND(IDM_CONxxxT_DIRECT, OnConxxxtDirect)
	ON_UPDATE_COMMAND_UI(IDM_DISCONxxxT, OnUpdateDisconxxxt)
	ON_UPDATE_COMMAND_UI(IDM_RECV_DISP, OnUpdateRecvDisp)
	ON_UPDATE_COMMAND_UI(IDM_FRONT, OnUpdateFront)
	ON_UPDATE_COMMAND_UI(IDM_REMOTE_SETTING, OnUpdateRemoteSetting)
	ON_UPDATE_COMMAND_UI(IDM_RECV_STILL, OnUpdateRecvStill)
	ON_UPDATE_COMMAND_UI(IDM_RECV_CAMERA, OnUpdateRecvCamera)
	ON_UPDATE_COMMAND_UI(IDM_SEND_CAMERA, OnUpdateSendCamera)
	ON_UPDATE_COMMAND_UI(IDM_VIDEO_SOURCE, OnUpdateVideoSource)
	ON_UPDATE_COMMAND_UI(IDM_VOICE, OnUpdateVoice)
	ON_UPDATE_COMMAND_UI(IDM_SEND_DISP, OnUpdateSendDisp)
	ON_UPDATE_COMMAND_UI(IDM_SEND_STILL, OnUpdateSendStill)
	ON_UPDATE_COMMAND_UI(IDM_CHAT, OnUpdateChat)
	ON_UPDATE_COMMAND_UI(IDM_IPADDRESS, OnUpdateIpaddress)
	ON_COMMAND(IDM_ORDER_CONxxxT, OnOrderConxxxt)
	ON_COMMAND(IDM_ORDER_IPADDRESS, OnOrderIpaddress)
	ON_COMMAND(IDM_ORDER_NAME, OnOrderName)
	ON_UPDATE_COMMAND_UI(IDM_ORDER_NAME, OnUpdateOrderName)
	ON_UPDATE_COMMAND_UI(IDM_ORDER_CONxxxT, OnUpdateOrderConxxxt)
	ON_UPDATE_COMMAND_UI(IDM_ORDER_IPADDRESS, OnUpdateOrderIpaddress)
	ON_COMMAND(IDM_VIDEO_FORMAT, OnVideoFormat)
	ON_UPDATE_COMMAND_UI(IDM_VIDEO_FORMAT, OnUpdateVideoFormat)
	//}}AFX_MSG_MAP
	ON_COMMAND_EX(IDM_HOSTBASE +  0, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  1, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  2, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  3, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  4, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  5, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  6, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  7, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  8, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE +  9, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE + 10, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE + 11, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE + 12, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE + 13, OnConxxxtHost)
	ON_COMMAND_EX(IDM_HOSTBASE + 14, OnConxxxtHost)
	ON_MESSAGE(WM_SOCKETUDP, OnSocketUDP)
	ON_MESSAGE(WM_SOCKETTCP, OnSocketTCP)
	ON_LBN_SELCHANGE(IDC_LISTBOX, OnSelChangeListBox)
	ON_LBN_DBLCLK(IDC_LISTBOX, OnDblClkListBox)
END_MESSAGE_MAP()

static UINT indicators[] =
{
	ID_SEPARATOR,			// ステータス ライン インジケータ
	IDS_INDICATOR_VIDEO,
	IDS_INDICATOR_CHAT,
	IDS_INDICATOR_VOICE
};

/////////////////////////////////////////////////////////////////////////////
// CMainFrame クラスの構築/消滅

CMainFrame::CMainFrame()
{
	CRect	rect;

	m_bActive = 0;
	m_bAutoMenuEnable = 0;

	LoadAccelTable(MAKEINTRESOURCE(IDR_MAINFRAME));

	rect.left = iniData.mainWindow.left;
	rect.top = iniData.mainWindow.top;
	rect.right = iniData.mainWindow.right;
	rect.bottom = iniData.mainWindow.bottom;
	Create(NULL, "PC-VIDEO",
		WS_OVERLAPPEDWINDOW | WS_CLIPCHILDREN, rect, NULL, MAKEINTRESOURCE(IDR_MAINFRAME));

	if (iniData.settings.bFrontKeep)
		SetWindowPos(&wndTopMost, 0, 0, 0, 0, SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
}

CMainFrame::~CMainFrame()
{
}

int CMainFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_MAINFRAME))
	{
		TRACE0("Failed to create toolbar\n");
		return -1;      // 作成に失敗
	}

	m_wndToolBar.GetToolBarCtrl().SetButtonWidth(43, 50);
	m_wndToolBar.SetSizes(CSize(43,36), CSize(16,17));
	m_wndToolBar.SetButtonText(0, GetString(IDS_CONxxxT));
	m_wndToolBar.SetButtonText(1, GetString(IDS_DISCONxxxT));
	m_wndToolBar.SetButtonText(3, GetString(IDS_VIDEO));
	m_wndToolBar.SetButtonText(4, GetString(IDS_CHAT));
	m_wndToolBar.SetButtonText(5, GetString(IDS_VOICE));
	m_wndToolBar.SetButtonText(7, GetString(IDS_ALIGN));

	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("Failed to create status bar\n");
		return -1;      // 作成に失敗
	}

	m_wndStatusBar.SetPaneStyle(0, SBPS_NORMAL);

	m_wndStatusBar.SetPaneText(1, "");
	m_wndStatusBar.SetPaneText(2, "");
	m_wndStatusBar.SetPaneText(3, "");

	CRect	rect;
	rect.SetRectEmpty();
	if (!m_wndListBox.Create(WS_CHILD | WS_VISIBLE | WS_VSCROLL | LBS_NOINTEGRALHEIGHT | LBS_NOTIFY | LBS_OWNERDRAWFIXED, rect, this, IDC_LISTBOX)) {
		TRACE("Failed to create a list box.\n");
		return -1;
	}
	pMainListWnd = &m_wndListBox;

	m_wndListBox.SetFont(&systemFont, FALSE);

	SetConxxxtMenu();

	WSAAsyncSelect(sockUDP, m_hWnd, WM_SOCKETUDP, FD_READ);
	WSAAsyncSelect(sockTCP, m_hWnd, WM_SOCKETTCP, FD_ACCEPT);

	return 0;
}

BOOL CMainFrame::PreCreateWindow(CREATESTRUCT& cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;

	return TRUE;
}

/////////////////////////////////////////////////////////////////////////////
// CMainFrame クラスの診断

#ifdef _DEBUG
void CMainFrame::AssertValid() const
{
	CFrameWnd::AssertValid();
}

void CMainFrame::Dump(CDumpContext& dc) const
{
	CFrameWnd::Dump(dc);
}

#endif //_DEBUG

void CMainFrame::SetConxxxtMenu()
{
	CMenu	*pMenu;
	static	CMenu subMenu;
	int	i, c;
	char	buf[100];
	CString	menu;

	menu.LoadString(IDS_MENU_CONxxxT);
	pMenu = GetMenu();

	pMenu->DeleteMenu(1, MF_BYPOSITION);

	if (iniData.conxxxt.host[0][0] == '\0') {
		pMenu->InsertMenu(1, MF_BYPOSITION, IDM_CONxxxT, menu);
	} else {
		subMenu.Detach();
		subMenu.CreatePopupMenu();
		for (i = 0; i < 15; i++) {
			if (iniData.conxxxt.host[i][0] != '\0') {
				c = (i < 9) ? ('1' + i) : ('a' + i - 9);
				wsprintf(buf, "&%c %s (%s,%d)",
						c,
						(LPSTR)iniData.conxxxt.name[i],
						(LPSTR)iniData.conxxxt.host[i],
						iniData.conxxxt.channelNo[i]);
				subMenu.AppendMenu(MF_STRING|MF_ENABLED,
						IDM_HOSTBASE + i, buf);
			}
		}
		subMenu.AppendMenu(MF_SEPARATOR, 0, "");
		subMenu.AppendMenu(MF_STRING, IDM_CONxxxT, GetString(IDS_OTHER_HOST));

		subMenu.AppendMenu(MF_SEPARATOR, 0, "");
		subMenu.AppendMenu(MF_STRING, IDM_DISCONxxxT, GetString(IDS_MENU_DISCONxxxT));

		pMenu->InsertMenu(1, MF_BYPOSITION|MF_POPUP, (UINT)subMenu.GetSafeHmenu(), menu);
	}
}

void CMainFrame::SetListBoxRect()
{
	CRect	rect, rectToolBar, rectStatusBar;
	int	height;

	GetClientRect(rect);
	m_wndToolBar.GetWindowRect(rectToolBar);
	m_wndStatusBar.GetWindowRect(rectStatusBar);
	height = rect.bottom - (rectToolBar.bottom - rectToolBar.top) -
				(rectStatusBar.bottom - rectStatusBar.top);
	if (height < 0)
		height = 0;

	m_wndListBox.MoveWindow(0, rectToolBar.bottom - rectToolBar.top, rect.right, height);
}

/////////////////////////////////////////////////////////////////////////////
// CMainFrame メッセージ ハンドラ


void CMainFrame::OnClose() 
{
	CloseAnswerWindow();
	CloseCallWindow();

	CloseCaptureWindow();
	CloseChatWindow();
	CloseVoiceWindow();

	if (pCameraWnd != NULL)
		pCameraWnd->SendMessage(WM_CLOSE, 0, 0);

	WINDOWPLACEMENT wndpl;
	wndpl.length = sizeof(wndpl);
	GetWindowPlacement(&wndpl);
	iniData.mainWindow.left = wndpl.rcNormalPosition.left;
	iniData.mainWindow.top = wndpl.rcNormalPosition.top;
	iniData.mainWindow.right = wndpl.rcNormalPosition.right;
	iniData.mainWindow.bottom = wndpl.rcNormalPosition.bottom;
	
	CFrameWnd::OnClose();
}

void CMainFrame::OnSize(UINT nType, int cx, int cy) 
{
	CFrameWnd::OnSize(nType, cx, cy);

//	RecalcLayout();
	SetListBoxRect();	
}

void CMainFrame::OnSetting() 
{
	CSettingDlg	settingDlg;

	if (settingDlg.Open(&iniData.settings, GetString(IDS_SETTING))) {
		WriteIniFile();
		SendConxxxtAll();
		if (pCaptureWnd != NULL) {
			pCaptureWnd->SetWindowText(iniData.settings.userName);
		}
		bChangeCaptureMode = TRUE;
	}
}

void CMainFrame::OnSethost() 
{
	CSetHostDlg(this).DoModal();
}

void CMainFrame::OnVideoSource() 
{
	extern HWND hCaptureWnd;
    capDlgVideoSource(hCaptureWnd);
}

void CMainFrame::OnRemoteSetting() 
{
	RemoteSetting(this);
}

BOOL CMainFrame::OnConxxxtHost(UINT nID)
{
	nID -= IDM_HOSTBASE;
	ConxxxtHost(iniData.conxxxt.host[nID], iniData.conxxxt.channelNo[nID]);

	return TRUE;
}

void CMainFrame::OnConxxxt() 
{
	CConxxxtDlg(this).DoModal();
}

LONG CMainFrame::OnSocketUDP(UINT wParam, LONG lParam)
{
	switch (WSAGETSELECTEVENT(lParam)) {
	case FD_READ:
		ReadSocketUDP(wParam);
		break;
	}

	return 0;
}

LONG CMainFrame::OnSocketTCP(UINT wParam, LONG lParam)
{
	switch (WSAGETSELECTEVENT(lParam)) {
	case FD_READ:
		ReadSocketTCP(wParam);
		break;
	case FD_WRITE:
		WriteSocketTCP(wParam);
		break;
	case FD_ACCEPT:
		AcceptSocket(wParam);
		break;
	case FD_CLOSE:
		CloseSocket(wParam);
		break;
	}

	return 0;
}

void CMainFrame::OnChat() 
{
	if (pChatWnd)
		CloseChatWindow();
	else
		OpenChatWindow();
}

void CMainFrame::OnVersion() 
{
	CDialog(IDD_VERSION).DoModal();
}

void CMainFrame::OnDisconxxxt() 
{
	PUSER	pUser;
	PSEND	pSend;
	int	n;

	if ((n = m_wndListBox.GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			pSend = pUser->pSend;

			SendDisconxxxt(&pSend->sock);

			DeleteSend(pSend);
		}
	} else
		MessageBeep(0);
}

void CMainFrame::OnActivate(UINT nState, CWnd* pWndOther, BOOL bMinimized) 
{
	CFrameWnd::OnActivate(nState, pWndOther, bMinimized);
	
	if ((nState == WA_ACTIVE || nState == WA_CLICKACTIVE) && m_bActive) {
		if (!iniData.settings.bFrontKeep)
			FrontWindow();
		m_bActive = FALSE;
	}
}

void CMainFrame::OnActivateApp(BOOL bActive, HTASK hTask) 
{
	CFrameWnd::OnActivateApp(bActive, hTask);

	m_bActive = (bActive != 0);
}

void CMainFrame::OnTimer(UINT nIDEvent) 
{
	PUSER	pUser;
	static	int	counter;

	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
//		if (!pUser->sock.bTCP) {
			if (++pUser->timer > TIMEOUT_USER) {
				DeleteUser(pUser);
				break;
			}
//		}
	}

	if (++counter == 10) {
		SendConxxxtAll();
		counter = 0;
	}

	if (conxxxtIPAddress != 0) {
//		接続パケットの再送はログインダイアログが重複する可能性があるので暫定的に取りやめる
//		SendConxxxt2(conxxxtIPAddress, htons(PORTNO), conxxxtChannelNo);
//		SendConxxxt2(conxxxtIPAddress, htons(PORTNO2), conxxxtChannelNo);
		if (++conxxxtTimer > 10)
			conxxxtIPAddress = 0;
	}

	if (guardIPAddress != 0) {
		if (++guardTimer > GUARD_TIME)
			guardIPAddress = 0;
	}

	CFrameWnd::OnTimer(nIDEvent);
}

void CMainFrame::OnTile() 
{
	int	cxScreen, cyScreen;
	int	i, n;
	CWnd	*pWnd[MAX_VIDEO_WINDOW + 5];
	static	ix = 4, iy = 4;
	int	x, y;
	int	yMax;
	PUSER	pUser;
	CRect	rect;

	cxScreen = GetSystemMetrics(SM_CXSCREEN);
	cyScreen = GetSystemMetrics(SM_CYSCREEN);

	n = 0;
	if (pCaptureWnd != NULL && pCaptureWnd->IsWindowVisible() && !pCaptureWnd->IsIconic())
		pWnd[n++] = pCaptureWnd;

	int nVideoWindowNo = 0;
	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
		if (pUser->pVideo != NULL) {
			if (!pUser->pVideo->IsIconic())
				pWnd[n++] = (CWnd *)pUser->pVideo;
			pUser->pVideo->m_no = nVideoWindowNo++;
		}
	}

	if (pChatWnd != 0 && !pChatWnd->IsIconic())
		pWnd[n++] = pChatWnd;

	if (pVoiceWnd != 0 && !pVoiceWnd->IsIconic())
		pWnd[n++] = pVoiceWnd;

	x = ix;
	y = iy;
	yMax = 0;

	for (i = 0; i < n; i++) {
		pWnd[i]->GetWindowRect(rect);

		if (x + (rect.right - rect.left) > cxScreen) {
			x = ix;
			y += yMax + iy;
			if (y > cyScreen)
				y = iy;
			yMax = 0;
		}

		if (rect.bottom - rect.top > yMax)
			yMax = rect.bottom - rect.top;

		pWnd[i]->SetWindowPos(0, x, y, 0, 0, SWP_NOSIZE);

		x += rect.right - rect.left + ix;
	}
}

void CMainFrame::OnRecvDisp() 
{
	OpenVideoWindow();
}

void CMainFrame::OnRecvStill() 
{
	PUSER	pUser;
	PVIDEO	pVideo;
	int	n;
	CString	buf;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			pVideo = pUser->pVideo;
			if (pVideo != NULL) {
				pVideo->m_bStill = !pVideo->m_bStill;
				if (pVideo->m_bStill) {
					buf.Format(IDS_STILL_IMAGE, (LPCSTR)pUser->userName);
					pVideo->SetWindowText(buf);
				} else {
					pVideo->SetWindowText(pUser->userName);
				}
			}
		}
	}
}

void CMainFrame::OnSendDisp() 
{
	iniData.mainWindow.bSendVideo = !iniData.mainWindow.bSendVideo;

	pCaptureWnd->ShowWindow(iniData.mainWindow.bSendVideo ? SW_SHOW : SW_HIDE);
}

void CMainFrame::OnSendStill() 
{
	bCaptureStill = !bCaptureStill;
	bChangeCaptureMode = TRUE;
}

void CMainFrame::OnRecvCamera() 
{
	PUSER	pUser;
	PVIDEO	pVideo;
	int	n;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			if ((pVideo = pUser->pVideo) != NULL) {
				if (pCameraWnd == NULL)
					pCameraWnd = new CCameraWnd();

				if (pCameraWnd != NULL)
					pCameraWnd->SetVideoWindow(pVideo);
			}
		}
	}
}

void CMainFrame::OnSendCamera() 
{
	if (pCameraWnd == NULL)
		pCameraWnd = new CCameraWnd();

	if (pCameraWnd != NULL)
		pCameraWnd->SetVideoWindow(pCaptureWnd);
}

void CMainFrame::OnVoice() 
{
	if (pVoiceWnd)
		CloseVoiceWindow();
	else
		OpenVoiceWindow();
}

void CMainFrame::OnIpaddress() 
{
	iniData.mainWindow.bDispAddress = !iniData.mainWindow.bDispAddress;
	pMainListWnd->Invalidate();
}

void CMainFrame::OnFront() 
{
	const CWnd	*pZ;
	PUSER	pUser;

	iniData.settings.bFrontKeep = !iniData.settings.bFrontKeep;
	if (iniData.settings.bFrontKeep)
		pZ = &wndTopMost;
	else
		pZ = &wndNoTopMost;

	SetWindowPos(pZ, 0, 0, 0, 0, SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pCaptureWnd != NULL)
		pCaptureWnd->SetWindowPos(pZ, 0, 0, 0, 0,
					SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
		if (pUser->pVideo != NULL) {
			pUser->pVideo->SetWindowPos(
					pZ, 0, 0, 0, 0,
					SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
		}
	}
	if (pChatWnd != NULL)
		pChatWnd->SetWindowPos(pZ, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pVoiceWnd != NULL)
		pVoiceWnd->SetWindowPos(pZ, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pAnswerWnd != NULL)
		pAnswerWnd->SetWindowPos(pZ, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pCallWnd != NULL)
		pCallWnd->SetWindowPos(pZ, 0, 0, 0, 0,
					SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pCameraWnd != NULL)
		pCameraWnd->SetWindowPos(pZ, 0, 0, 0, 0,
					SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
}

void	CMainFrame::OpenVideoWindow()
{
	int	n;
	PUSER	pUser;
	PVIDEO	pVideo;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		pUser = GetUser(n);
		if ((pVideo = pUser->pVideo) == NULL) {
			if (pUser->modeFlag & MODE_SENDVIDEO)
				pUser->pVideo = CreateVideo(pUser);
		} else
			DeleteVideo(pVideo);

		SendConxxxt1(pUser->pSend);
	}
}

void	CMainFrame::FrontWindow()
{
	PUSER	pUser;

	if (pCaptureWnd != NULL)
		pCaptureWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	for (pUser = pTopUser; pUser != NULL; pUser = pUser->pChain) {
		if (pUser->pVideo != NULL && pUser->pVideo->m_hWnd != NULL) {
			pUser->pVideo->SetWindowPos(&wndTop,
				0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
		}
	}

	if (pChatWnd != NULL)
		pChatWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pVoiceWnd != NULL)
		pVoiceWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pAnswerWnd != NULL)
		pAnswerWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pCallWnd != NULL)
		pCallWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pCameraWnd != NULL)
		pCameraWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	if (pMainWnd != NULL)
		pMainWnd->SetWindowPos(&wndTop, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
}

void	CMainFrame::OnSelChangeListBox()
{
	int	n;
	PUSER	pUser;
	BOOL	bVideo = FALSE;
	BOOL	bChat = FALSE;
	BOOL	bVoice = FALSE;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			if (pUser->modeFlag & MODE_SENDVIDEO)
				bVideo = TRUE;
			if (pUser->modeFlag & MODE_CHAT)
				bChat = TRUE;
			if (pUser->modeFlag & MODE_SENDVOICE)
				bVoice = TRUE;
		}
	}

	m_wndStatusBar.SetPaneText(1, bVideo ? GetString(IDS_VIDEO) : "");
	m_wndStatusBar.SetPaneText(2, bChat ? GetString(IDS_CHAT) : "");
	m_wndStatusBar.SetPaneText(3, bVoice ? GetString(IDS_VOICE) : "");
}

void	CMainFrame::OnDblClkListBox()
{
	OpenVideoWindow();
}

void CMainFrame::OnConxxxtDirect() 
{
	if (iniData.hostList.target[0] == '\0')
		CConxxxtDlg(this).DoModal();
	else
		ConxxxtHost(iniData.hostList.target, iniData.hostList.channelNo);
}

void CMainFrame::OnUpdateDisconxxxt(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(pTopUser != NULL);
}

void CMainFrame::OnUpdateRecvDisp(CCmdUI* pCmdUI) 
{
	int	n;
	PUSER	pUser;
	BOOL	bEnable = FALSE;
	BOOL	iCheck = 0;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			if (pUser->modeFlag & MODE_SENDVIDEO)
				bEnable = TRUE;
			if (pUser->pVideo != NULL)
				iCheck = 1;
		}
	}

	pCmdUI->Enable(bEnable);
	pCmdUI->SetCheck(iCheck);
}

void CMainFrame::OnUpdateFront(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(iniData.settings.bFrontKeep ? 1 : 0);
}

void CMainFrame::OnUpdateRemoteSetting(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(bAdmin && m_wndListBox.GetCurSel() != LB_ERR);
}

void CMainFrame::OnUpdateRecvStill(CCmdUI* pCmdUI) 
{
	int	n;
	PUSER	pUser;
	BOOL	bEnable = FALSE;
	BOOL	iCheck = 0;

	if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(n)) != NULL) {
			if (pUser->pVideo != NULL) {
				bEnable = TRUE;
				if (pUser->pVideo->m_bStill)
					iCheck = 1;
			}
		}
	}

	pCmdUI->Enable(bEnable);
	pCmdUI->SetCheck(iCheck);
}

void CMainFrame::OnUpdateRecvCamera(CCmdUI* pCmdUI) 
{
	int	n;
	PUSER	pUser;
	BOOL	bEnable = FALSE;

	if (bAdmin) {
		if ((n = pMainListWnd->GetCurSel()) != LB_ERR) {
			if ((pUser = GetUser(n)) != NULL) {
				if (pUser->pVideo != NULL)
					bEnable = TRUE;
			}
		}
	}

	pCmdUI->Enable(bEnable);
}

void CMainFrame::OnUpdateSendCamera(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(pCaptureWnd != 0);
}

void CMainFrame::OnUpdateVideoSource(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(pCaptureWnd != 0);
}

void CMainFrame::OnUpdateVoice(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(bVoice);
	pCmdUI->SetCheck(pVoiceWnd ? 1 : 0);
}

void CMainFrame::OnUpdateSendDisp(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(pCaptureWnd != 0);
	pCmdUI->SetCheck(iniData.mainWindow.bSendVideo && pCaptureWnd != NULL ? 1 : 0);
}

void CMainFrame::OnUpdateSendStill(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(pCaptureWnd != 0);
	pCmdUI->SetCheck(bCaptureStill ? 1 : 0);
}

void CMainFrame::OnUpdateChat(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(pChatWnd ? 1 : 0);
}

void CMainFrame::OnUpdateIpaddress(CCmdUI* pCmdUI) 
{
	pCmdUI->SetCheck(iniData.mainWindow.bDispAddress ? 1 : 0);
}

void CMainFrame::OnOrderConxxxt() 
{
	iniData.mainWindow.nListOrder = LIST_ORDER_CONxxxT;
	ReorderList();
}

void CMainFrame::OnOrderIpaddress() 
{
	iniData.mainWindow.nListOrder = LIST_ORDER_IPADDRESS;
	ReorderList();
}

void CMainFrame::OnOrderName() 
{
	iniData.mainWindow.nListOrder = LIST_ORDER_NAME;
	ReorderList();
}

void CMainFrame::OnUpdateOrderConxxxt(CCmdUI* pCmdUI) 
{
	pCmdUI->SetRadio(iniData.mainWindow.nListOrder == LIST_ORDER_CONxxxT);
}

void CMainFrame::OnUpdateOrderIpaddress(CCmdUI* pCmdUI) 
{
	pCmdUI->SetRadio(iniData.mainWindow.nListOrder == LIST_ORDER_IPADDRESS);
}

void CMainFrame::OnUpdateOrderName(CCmdUI* pCmdUI) 
{
	pCmdUI->SetRadio(iniData.mainWindow.nListOrder == LIST_ORDER_NAME);
}

void CMainFrame::OnVideoFormat() 
{
	extern HWND hCaptureWnd;
    capDlgVideoFormat(hCaptureWnd);

	if (pCaptureWnd != NULL) {
		pCaptureWnd->m_bStopCapture = TRUE;
		pCaptureWnd->SetCaptureMode(videoSize[iniData.settings.movieSize].width,
				videoSize[iniData.settings.movieSize].height,
				iniData.settings.videoColor);
		pCaptureWnd->m_bStopCapture = FALSE;
	}
}

void CMainFrame::OnUpdateVideoFormat(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(pCaptureWnd != 0);
}
