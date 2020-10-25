/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: カメラコントロールウィンドウ処理
 *		ファイル名	: camera.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// camera.cpp : implementation file
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "video.h"
#include "camera.h"
#include "pcvideo.h"
#include "cmcmd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define	BN_LBUTTONDOWN	10
#define	BN_LBUTTONUP	11

#define	FRAME_SIZE_X	224
#define	FRAME_SIZE_Y	112
#define	FRAME_CENTER_X	(FRAME_SIZE_X / 2)
#define	FRAME_CENTER_Y	(FRAME_SIZE_Y / 2)
#define	FRAME_MOVE_CX	160
#define	FRAME_MOVE_CY	64
#define	FRAME_MOVE_LEFT	(FRAME_CENTER_X - FRAME_MOVE_CX / 2)
#define	FRAME_MOVE_TOP	(FRAME_CENTER_Y - FRAME_MOVE_CY / 2)
#define	FRAME_MOVE_RIGHT  (FRAME_CENTER_X + FRAME_MOVE_CX / 2)
#define	FRAME_MOVE_BOTTOM (FRAME_CENTER_Y + FRAME_MOVE_CY / 2)
#define	FRAME_WIDE_CX	64
#define	FRAME_WIDE_CY	48
#define	FRAME_TELE_CX	8
#define	FRAME_TELE_CY	6

#define	PAN_CENTER	0x28a
#define	PAN_MAX		0x514
#define	TILT_CENTER	0x121
#define	TILT_MAX	0x242
#define	ZOOM_CENTER	0x40
#define	ZOOM_MAX	0x80

#define	PAN_SPEED_MIN	0x64
#define	PAN_SPEED_MAX	0x1f4
#define	TILT_SPEED_MIN	0x64
#define	TILT_SPEED_MAX	0x1f4

#define	CURSOR_NONE		0
#define	CURSOR_IN_FRAME		1
#define	CURSOR_LEFT_FRAME	2
#define	CURSOR_TOP_FRAME	3
#define	CURSOR_RIGHT_FRAME	4
#define	CURSOR_BOTTOM_FRAME	5
#define	CURSOR_OTHER		6

#define	IDT_OPENTIMER	7000
#define IDC_CAMERA_FRAME 7001

static UINT BASED_CODE indicators[] =
{
	ID_SEPARATOR,			// ステータス ライン インジケータ
	IDS_INDICATOR_VIDEO,
	IDS_INDICATOR_CHAT,
	IDS_INDICATOR_VOICE
};

/////////////////////////////////////////////////////////////////////////////
// CCameraWnd dialog


CCameraWnd::CCameraWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CCameraWnd::IDD, pParent)
{
	//{{AFX_DATA_INIT(CCameraWnd)
	//}}AFX_DATA_INIT

	m_pVideoWnd = NULL;
	m_panPos = 0;
	m_tiltPos = 0;
	m_zoomPos = 0;
	m_nTimerID = 0;
	m_nTimerCounter = 0;
	m_msgNo = 0;
	m_currentPresetNo = -1;
	m_bEnable = TRUE;
	m_bEnablePos = FALSE;
	m_bBusy = FALSE;
	m_bInfo = FALSE;

	Create(IDD_CAMERA, (CWnd *)&wndTop);

	if (iniData.settings.bFrontKeep)
		SetWindowPos(&wndTopMost, 0, 0, 0, 0,
				SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);
}


void CCameraWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CCameraWnd)
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CCameraWnd, CDialog)
	//{{AFX_MSG_MAP(CCameraWnd)
	ON_WM_CLOSE()
	ON_BN_CLICKED(IDC_CAMERA_HOME, OnCameraHome)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_UP,    OnCameraStop)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_UP,    OnCameraUp)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_DOWN,  OnCameraDown)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_LEFT,  OnCameraLeft)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_RIGHT, OnCameraRight)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_TELE,  OnCameraTele)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_WIDE,  OnCameraWide)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_TELE,  OnCameraZoomStop)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_FAR,   OnCameraFar)
	ON_CONTROL(BN_LBUTTONDOWN, IDC_CAMERA_NEAR,   OnCameraNear)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_FAR,   OnCameraStopFocus)
	ON_MESSAGE(WM_SETPANTILT, OnSetPanTilt)
	ON_MESSAGE(WM_SETZOOM, OnSetZoom)
	ON_MESSAGE(WM_POINTWND, OnPointWnd)
	ON_BN_CLICKED(IDC_CAMERA_AUTO, OnCameraAuto)
	ON_BN_CLICKED(IDC_CAMERA_MANUAL, OnCameraManual)
	ON_BN_CLICKED(IDC_CAMERA_PS1, OnCameraPs1)
	ON_BN_CLICKED(IDC_CAMERA_PS2, OnCameraPs2)
	ON_BN_CLICKED(IDC_CAMERA_PS3, OnCameraPs3)
	ON_BN_CLICKED(IDC_CAMERA_PS4, OnCameraPs4)
	ON_BN_CLICKED(IDC_CAMERA_PS5, OnCameraPs5)
	ON_BN_CLICKED(IDC_CAMERA_PS6, OnCameraPs6)
	ON_BN_CLICKED(IDC_CAMERA_PS7, OnCameraPs7)
	ON_BN_CLICKED(IDC_CAMERA_PS8, OnCameraPs8)
	ON_BN_CLICKED(IDC_CAMERA_OPTION, OnCameraOption)
	ON_WM_TIMER()
	ON_WM_SETCURSOR()
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_DOWN,  OnCameraStop)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_LEFT,  OnCameraStop)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_RIGHT, OnCameraStop)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_WIDE,  OnCameraZoomStop)
	ON_CONTROL(BN_LBUTTONUP,   IDC_CAMERA_NEAR,   OnCameraStopFocus)
	ON_BN_CLICKED(IDC_CAMERA_LOCK, OnCameraLock)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CCameraWnd message handlers

BOOL CCameraWnd::OnInitDialog()
{
	CDialog::OnInitDialog();
	
	SetWindowPos(0, iniData.cameraWindow.left, iniData.cameraWindow.top, 0, 0, SWP_NOSIZE);

	CRect	rect;
	rect.left = 18;
	rect.top = 30;
	rect.right = 18 + FRAME_SIZE_X + 1;
	rect.bottom = 30 + FRAME_SIZE_Y + 1;
	m_frame.Create(cameraFrameWndClassName, "", WS_CHILD | WS_VISIBLE, rect, this, IDC_CAMERA_FRAME);

	m_btnHome.AutoLoad(IDC_CAMERA_HOME, this);
	m_btnLeft.AutoLoad(IDC_CAMERA_LEFT, this);
	m_btnUp.AutoLoad(IDC_CAMERA_UP, this);
	m_btnRight.AutoLoad(IDC_CAMERA_RIGHT, this);
	m_btnDown.AutoLoad(IDC_CAMERA_DOWN, this);
	m_btnTele.AutoLoad(IDC_CAMERA_TELE, this);
	m_btnWide.AutoLoad(IDC_CAMERA_WIDE, this);
	m_btnFar.AutoLoad(IDC_CAMERA_FAR, this);
	m_btnNear.AutoLoad(IDC_CAMERA_NEAR, this);

	m_btnPreset.SubclassDlgItem(IDC_CAMERA_PRESET, this);

	EnableControl(FALSE);

	return TRUE;
}

void CCameraWnd::OnClose()
{
	CRect	rect;

	GetWindowRect(rect);		// ダイアログ表示位置取得
	iniData.cameraWindow.left = rect.left;
	iniData.cameraWindow.top = rect.top;

	SetVideoWindow(NULL);

	DestroyWindow();
}

BOOL CCameraWnd::OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message) 
{
	int	presetNo;

	switch (pWnd->GetDlgCtrlID()) {
	case IDC_CAMERA_PS1:
		presetNo = 0;
		break;
	case IDC_CAMERA_PS2:
		presetNo = 1;
		break;
	case IDC_CAMERA_PS3:
		presetNo = 2;
		break;
	case IDC_CAMERA_PS4:
		presetNo = 3;
		break;
	case IDC_CAMERA_PS5:
		presetNo = 4;
		break;
	case IDC_CAMERA_PS6:
		presetNo = 5;
		break;
	case IDC_CAMERA_PS7:
		presetNo = 6;
		break;
	case IDC_CAMERA_PS8:
		presetNo = 7;
		break;
	default:
		presetNo = -1;
		break;
	}

	if (presetNo != m_currentPresetNo) {
		SetDlgItemText(IDC_CAMERA_COMMENT, (presetNo == -1) ? "" : m_vcc1.preset[presetNo].comment);
		m_currentPresetNo = presetNo;
		GetDlgItem(IDC_CAMERA_FRAME)->Invalidate();
	}

	return CDialog::OnSetCursor(pWnd, nHitTest, message);
}

void CCameraWnd::OnOK()
{
	PostMessage(WM_CLOSE, 0, 0);
}

void CCameraWnd::OnCancel()
{
	PostMessage(WM_CLOSE, 0, 0);
}

LONG CCameraWnd::OnSetPanTilt(UINT wParam, LONG lParam)
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_SetPos;
	command.param1 = LOWORD(lParam);
	command.param2 = HIWORD(lParam);
	command.param3 = -1;
	SendCommand(command);

	return 0L;
}

LONG CCameraWnd::OnSetZoom(UINT wParam, LONG lParam)
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_SetZoomPos;
	command.param1 = LOWORD(wParam);
	SendCommand(command);

	return 0L;
}

LONG CCameraWnd::OnPointWnd(UINT wParam, LONG lParam)
{
	CAMERA_COMMAND	command;
	int	x, y, z;

	if (!m_bEnable)
		return 0L;

	z = (int)((ZOOM_MAX - m_zoomPos) * 700L / ZOOM_MAX) + 100;

	if (m_zoomPos < 0x10)
		z += (0x10 - m_zoomPos) * 15;
	else if (m_zoomPos > 0x70)
		z += (m_zoomPos - 0x70) * 3;

	if (m_vcc1.bWideConverter)
		z = (int)(z * 100L / 75);

	x = ((short)LOWORD(lParam) * z / 1750) + m_panPos;
	if (x < 0)
		x = 0;
	else if (x > PAN_MAX)
		x = PAN_MAX;

	y = (-(short)HIWORD(lParam) * z / 1750) + m_tiltPos;
	if (y < 0)
		y = 0;
	else if (y > TILT_MAX)
		y = TILT_MAX;

	command.command = COMMAND_SetPos;
	command.param1 = x;
	command.param2 = y;
	command.param3 = -1;
	SendCommand(command);

	return 0L;
}

void CCameraWnd::OnTimer(UINT nIDEvent) 
{
	switch (nIDEvent) {
	case IDT_OPENTIMER:
		if (++m_nTimerCounter == 3) {
			KillTimer(m_nTimerID);
			m_nTimerID = 0;
			DispStatus(IDS_CAMERA_NORESPONSE);
		} else {
			CAMERA_COMMAND	command;

			command.command = COMMAND_Open;
			SendCommand(command);
		}
		break;
	}

	CDialog::OnTimer(nIDEvent);
}

void	CCameraWnd::PostNcDestroy()
{
	CDialog::PostNcDestroy();

	pCameraWnd = NULL;
	delete this;
}

void CCameraWnd::SetVideoWindow(CVideoWnd *pVideoWnd)
{
	DispStatus(0);

	if (m_pVideoWnd != NULL) {
		CloseCamera();
		EnableControl(FALSE);
		m_bInfo = FALSE;
		m_pVideoWnd->m_pCameraWnd = NULL;
		SetWindowText(GetString(IDS_CAMERA_CONTROL));
	}

	m_pVideoWnd = pVideoWnd;

	if (m_pVideoWnd != NULL) {
		m_pVideoWnd->m_pCameraWnd = this;
		m_currentPresetNo = -1;
		m_bEnablePos = FALSE;

		OpenCamera();

		CString	caption;
		caption.Format(IDS_CAMERA_CONTROL2,
				m_pVideoWnd->m_pUser == NULL ?
				(LPSTR)iniData.settings.userName :
				(LPSTR)m_pVideoWnd->m_pUser->userName);
		SetWindowText(caption);

		GetDlgItem(IDC_CAMERA_FRAME)->Invalidate();
	}
}

void CCameraWnd::OnCameraHome()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_Home;
	SendCommand(command);
}

void CCameraWnd::OnCameraStop()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_Stop;
	SendCommand(command);
}

void CCameraWnd::OnCameraUp()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_TiltUp;
	SendCommand(command);
}

void CCameraWnd::OnCameraDown()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_TiltDown;
	SendCommand(command);
}

void CCameraWnd::OnCameraLeft()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_PanLeft;
	SendCommand(command);
}

void CCameraWnd::OnCameraRight()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_PanRight;
	SendCommand(command);
}

void CCameraWnd::OnCameraTele()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_ZoomTele;
	SendCommand(command);
}

void CCameraWnd::OnCameraWide()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_ZoomWide;
	SendCommand(command);
}

void CCameraWnd::OnCameraZoomStop()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_ZoomStop;
	SendCommand(command);
}

void CCameraWnd::OnCameraFar()
{
	CAMERA_COMMAND	command;

	DispStatus(IDS_CAMERA_FOCUS);
	command.command = COMMAND_FocusFar;
	SendCommand(command);
}

void CCameraWnd::OnCameraNear()
{
	CAMERA_COMMAND	command;

	DispStatus(IDS_CAMERA_FOCUS);
	command.command = COMMAND_FocusNear;
	SendCommand(command);
}

void CCameraWnd::OnCameraStopFocus() 
{
	CAMERA_COMMAND	command;

	DispStatus(IDS_CAMERA_READY);
	command.command = COMMAND_FocusManual;
	SendCommand(command);
}

void CCameraWnd::OnCameraAuto() 
{
	CAMERA_COMMAND	command;

	GetDlgItem(IDC_CAMERA_FAR)->EnableWindow(FALSE);
	GetDlgItem(IDC_CAMERA_NEAR)->EnableWindow(FALSE);

	command.command = COMMAND_FocusAf;
	SendCommand(command);
}

void CCameraWnd::OnCameraManual() 
{
	CAMERA_COMMAND	command;

	GetDlgItem(IDC_CAMERA_FAR)->EnableWindow(TRUE);
	GetDlgItem(IDC_CAMERA_NEAR)->EnableWindow(TRUE);

	command.command = COMMAND_FocusManual;
	SendCommand(command);
}

void CCameraWnd::OnCameraPs1() 
{
	Preset(0);
}

void CCameraWnd::OnCameraPs2() 
{
	Preset(1);
}

void CCameraWnd::OnCameraPs3() 
{
	Preset(2);
}

void CCameraWnd::OnCameraPs4() 
{
	Preset(3);
}

void CCameraWnd::OnCameraPs5() 
{
	Preset(4);
}

void CCameraWnd::OnCameraPs6() 
{
	Preset(5);
}

void CCameraWnd::OnCameraPs7() 
{
	Preset(6);
}

void CCameraWnd::OnCameraPs8() 
{
	Preset(7);
}

void CCameraWnd::OnCameraLock() 
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_Lock;

	if ((BOOL)SendDlgItemMessage(IDC_CAMERA_LOCK, BM_GETCHECK, 0, 0))
		command.param1 = 1;
	else
		command.param1 = 0;

	SendCommand(command);
}

void CCameraWnd::Preset(int i)
{
	if (((CToggleButton *)GetDlgItem(IDC_CAMERA_PRESET))->m_bPreset) {
		if (CPresetDlg(this, i).DoModal()) {
			SetDlgItemText(IDC_CAMERA_COMMENT, m_vcc1.preset[i].comment);
			SendCameraInfo(0);
		}

		GetDlgItem(IDC_CAMERA_PRESET)->SendMessage(WM_LBUTTONUP, 0, 0L);
	} else {
		CAMERA_COMMAND	command;

		command.command = COMMAND_SetPos;
		command.param1 = m_vcc1.preset[i].panPos;
		command.param2 = m_vcc1.preset[i].tiltPos;
		command.param3 = m_vcc1.preset[i].zoomPos;
		SendCommand(command);
	}
}

void CCameraWnd::OpenCamera()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_Open;
	SendCommand(command);

	if (m_pVideoWnd->m_pUser != NULL) {
		m_nTimerCounter = 0;
		m_nTimerID = SetTimer(IDT_OPENTIMER, 1000, NULL);
	}
}

void CCameraWnd::CloseCamera()
{
	CAMERA_COMMAND	command;

	command.command = COMMAND_Close;
	SendCommand(command);
}

void CCameraWnd::SetCameraStatus(const CAMERA_STATUS &status)
{
	if (m_nTimerID == IDT_OPENTIMER) {
		KillTimer(m_nTimerID);
		m_nTimerID = 0;
	}

	if (status.status & STATUS_NOSUPPORT)
		DispStatus(IDS_CAMERA_NOSUPPORT);
	else if (status.status & STATUS_DENY)
		DispStatus(IDS_CAMERA_DENY);
	else if (status.status & STATUS_PORTERR)
		DispStatus(IDS_CAMERA_NOCONTROL);
	else if (status.status & STATUS_LOCKED)
		DispStatus(IDS_CAMERA_LOCKED, status.userName);
	else if (status.status & STATUS_INIT)
		DispStatus(IDS_CAMERA_INIT);
	else if (status.status & STATUS_BUSY)
		DispStatus(IDS_CAMERA_BUSY);
	else
		DispStatus(IDS_CAMERA_READY);

	m_bBusy = (status.status & STATUS_BUSY) ? TRUE : FALSE;

	if (status.status & STATUS_POS) {
		m_bEnablePos = TRUE;
		m_panPos = status.panPos;
		m_tiltPos = status.tiltPos;
		m_zoomPos = status.zoomPos;
		GetDlgItem(IDC_CAMERA_FRAME)->Invalidate();
	}

	if (status.status & STATUS_INFO) {
		m_vcc1 = status.vcc1;
		m_bInfo = TRUE;
		GetDlgItem(IDC_CAMERA_AUTO)->SendMessage(BM_SETCHECK, m_vcc1.bAutoFocus ? 1 : 0, 0L);
		GetDlgItem(IDC_CAMERA_MANUAL)->SendMessage(BM_SETCHECK, m_vcc1.bAutoFocus ? 0 : 1, 0L);
	}

	EnableControl(m_bInfo && !(status.status & (STATUS_ERROR | STATUS_INIT)));

	if (m_bEnablePos) {
		char	buf[10];
		wsprintf(buf, "%3dﾟ", (int)(((long)m_panPos * 100 + PAN_CENTER) / PAN_MAX) - 50);
		SetDlgItemText(IDC_CAMERA_PAN, buf);

		wsprintf(buf, "%3dﾟ", (int)(((long)m_tiltPos * 40 + TILT_CENTER) / TILT_MAX) - 20);
		SetDlgItemText(IDC_CAMERA_TILT, buf);
	}
}

BOOL CCameraWnd::SendCommand(const CAMERA_COMMAND &command)
{
	PUSER	pUser = m_pVideoWnd->m_pUser;
	int	rc;

	if (pUser == NULL)
		rc = ExecCameraCommand(pUser, command);
	else {
		REMOTE_CAMERA_CMD	remote;

		remote.remote.header.flag = FLAG_REMOTE;
		remote.remote.header.id = localIPAddress;
		remote.remote.type = REMOTE_CAMERA_COMMAND;
		remote.remote.id = pUser->id;
		remote.command = command;
		SendData(&pUser->pSend->sock, &remote, sizeof(remote));
		rc = TRUE;
	}

	return rc;
}

void CCameraWnd::SendCameraInfo(int flag)
{
	PUSER	pUser = m_pVideoWnd->m_pUser;

	if (pUser == NULL)
		SetCameraInfo(m_vcc1, flag);
	else {
		REMOTE_CAMERA_SET	remote;

		remote.remote.header.flag = FLAG_REMOTE;
		remote.remote.header.id = localIPAddress;
		remote.remote.type = REMOTE_CAMERA_SETINFO;
		remote.remote.id = pUser->id;
		remote.flag = flag;
		remote.vcc1 = m_vcc1;
		SendData(&pUser->pSend->sock, &remote, sizeof(remote));
	}
}

void CCameraWnd::DispStatus(int msgNo, const char *str)
{
	if (msgNo == m_msgNo)
		return;

	if (msgNo == 0) {
		SetDlgItemText(IDC_CAMERA_STATUS, "");
	} else {
		CString	msg;

		if (msg.LoadString(msgNo)) {
			if (msgNo == IDS_CAMERA_LOCKED) {
				char	buf[100];
				wsprintf(buf, (const char *)msg, (LPSTR)str);
				msg = buf;
			}
		} else
			msg.LoadString(IDS_UNDEFINED_MSG);

		SetDlgItemText(IDC_CAMERA_STATUS, (CString)" " + msg);

	}

	m_msgNo = msgNo;
}

void CCameraWnd::EnableControl(BOOL bEnable)
{
	static	int	controlID[] = {
		IDC_CAMERA_OPTION,
		IDC_CAMERA_HOME,
		IDC_CAMERA_UP, IDC_CAMERA_DOWN,
		IDC_CAMERA_LEFT, IDC_CAMERA_RIGHT,
		IDC_CAMERA_TELE, IDC_CAMERA_WIDE,
		IDC_CAMERA_PRESET,
		IDC_CAMERA_AUTO, IDC_CAMERA_MANUAL,
		IDC_CAMERA_LOCK,
	};
	int	i;

	if (bEnable != m_bEnable) {
		m_bEnable = bEnable;

		for (i = 0; i < sizeof(controlID) / sizeof(int); i++)
			GetDlgItem(controlID[i])->EnableWindow(bEnable);

		if (bEnable) {
			GetDlgItem(IDC_CAMERA_FAR)->EnableWindow(!m_vcc1.bAutoFocus);
			GetDlgItem(IDC_CAMERA_NEAR)->EnableWindow(!m_vcc1.bAutoFocus);
		} else {
			GetDlgItem(IDC_CAMERA_FAR)->EnableWindow(FALSE);
			GetDlgItem(IDC_CAMERA_NEAR)->EnableWindow(FALSE);

			SetDlgItemText(IDC_CAMERA_PAN, " --ﾟ");
			SetDlgItemText(IDC_CAMERA_TILT, " --ﾟ");
		}

		EnablePresetButton();
	}
}

void CCameraWnd::EnablePresetButton()
{
	static	int	presetID[] = {
		IDC_CAMERA_PS1, IDC_CAMERA_PS2, IDC_CAMERA_PS3, IDC_CAMERA_PS4,
		IDC_CAMERA_PS5, IDC_CAMERA_PS6, IDC_CAMERA_PS7, IDC_CAMERA_PS8,
	};
	BOOL	bAllEnable = FALSE;
	int	i;

	if (((CToggleButton *)GetDlgItem(IDC_CAMERA_PRESET))->m_bPreset)
		bAllEnable = TRUE;

	for (i = 0; i < sizeof(presetID) / sizeof(int); i++) {
		if (m_bEnable) {
			GetDlgItem(presetID[i])->EnableWindow(bAllEnable || m_vcc1.preset[i].comment[0] != '\0');
		} else
			GetDlgItem(presetID[i])->EnableWindow(FALSE);
	}
}

/////////////////////////////////////////////////////////////////////////////
// CCameraButton

CCameraButton::CCameraButton()
{
}

CCameraButton::~CCameraButton()
{
}


BEGIN_MESSAGE_MAP(CCameraButton, CBitmapButton)
	//{{AFX_MSG_MAP(CCameraButton)
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CCameraButton message handlers


void CCameraButton::OnLButtonDown(UINT nFlags, CPoint point)
{
	GetParent()->SendMessage(WM_COMMAND, MAKEWPARAM(GetDlgCtrlID(), BN_LBUTTONDOWN), (LPARAM)m_hWnd);
	CBitmapButton::OnLButtonDown(nFlags, point);
}

void CCameraButton::OnLButtonUp(UINT nFlags, CPoint point)
{
	GetParent()->SendMessage(WM_COMMAND, MAKEWPARAM(GetDlgCtrlID(), BN_LBUTTONUP), (LPARAM)m_hWnd);
	CBitmapButton::OnLButtonUp(nFlags, point);
}

void CCameraButton::OnMouseMove(UINT nFlags, CPoint point)
{
}

/////////////////////////////////////////////////////////////////////////////
// CCameraFrame

CCameraFrame::CCameraFrame()
{
	m_brushView.CreateSolidBrush(RGB(164, 200, 240));
	m_brushFrame.CreateSolidBrush(RGB(192, 255, 192));
	m_brushFrameBusy.CreateSolidBrush(RGB(255, 128, 0));

	m_penCenterLine.CreatePen(PS_SOLID, 1, RGB(255,251,240));
	m_penFrame.CreatePen(PS_SOLID, 1, RGB(64,64,64));
	m_penPreset.CreatePen(PS_SOLID, 1, RGB(0,255,0));

	m_hCursorArrow = theApp.LoadStandardCursor(IDC_ARROW);
	m_hCursorOpenHand = theApp.LoadCursor(IDC_OPENHAND);
	m_hCursorCloseHand = theApp.LoadCursor(IDC_CLOSEHAND);
	m_hCursorVMove = theApp.LoadCursor(IDC_VMOVE);
	m_hCursorHMove = theApp.LoadCursor(IDC_HMOVE);

	m_clickMode = CURSOR_NONE;
}

CCameraFrame::~CCameraFrame()
{
	m_brushView.DeleteObject();
	m_brushFrame.DeleteObject();
	m_brushFrameBusy.DeleteObject();

	m_penCenterLine.DeleteObject();
	m_penFrame.DeleteObject();
	m_penPreset.DeleteObject();
}


BEGIN_MESSAGE_MAP(CCameraFrame, CWnd)
	//{{AFX_MSG_MAP(CCameraFrame)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CCameraFrame message handlers


int CCameraFrame::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CWnd::OnCreate(lpCreateStruct) == -1)
		return -1;
	
	m_pCameraWnd = (CCameraWnd *)GetParent();
	
	return 0;
}

void CCameraFrame::OnPaint()
{
	CPaintDC dc(this); // device context for painting

	CRect	rect;

	dc.SelectStockObject(BLACK_PEN);
	dc.MoveTo(0, FRAME_SIZE_Y);
	dc.LineTo(0, 0);
	dc.LineTo(FRAME_SIZE_X, 0);
	dc.SelectStockObject(WHITE_PEN);
	dc.LineTo(FRAME_SIZE_X, FRAME_SIZE_Y);
	dc.LineTo(0, FRAME_SIZE_Y);

	rect.left = 1;
	rect.top = 1;
	rect.right = FRAME_SIZE_X;
	rect.bottom = FRAME_SIZE_Y;
	dc.FillRect(rect, &m_brushView);

	dc.SetBkMode(TRANSPARENT);
	dc.SelectObject(&m_penCenterLine);
	dc.MoveTo(1, FRAME_CENTER_Y);
	dc.LineTo(FRAME_SIZE_X, FRAME_CENTER_Y);
	dc.MoveTo(FRAME_CENTER_X, 1);
	dc.LineTo(FRAME_CENTER_X, FRAME_SIZE_Y);

	POINT	point[5];
	point[0].x = FRAME_MOVE_LEFT;
	point[0].y = FRAME_MOVE_TOP;
	point[1].x = FRAME_MOVE_RIGHT;
	point[1].y = FRAME_MOVE_TOP;
	point[2].x = FRAME_MOVE_RIGHT;
	point[2].y = FRAME_MOVE_BOTTOM;
	point[3].x = FRAME_MOVE_LEFT;
	point[3].y = FRAME_MOVE_BOTTOM;
	point[4].x = FRAME_MOVE_LEFT;
	point[4].y = FRAME_MOVE_TOP;
	dc.Polyline(point, 5);

	if (m_pCameraWnd->m_bEnablePos) {
		CRect	rect;

		CalcFrameRect(m_pCameraWnd->m_panPos, m_pCameraWnd->m_tiltPos, m_pCameraWnd->m_zoomPos, rect);
		dc.SelectObject(&m_penFrame);
		dc.SelectObject(m_pCameraWnd->m_bBusy ? &m_brushFrameBusy : &m_brushFrame);
		dc.Rectangle(rect);

		if (m_pCameraWnd->m_currentPresetNo != -1) {
			int	presetNo = m_pCameraWnd->m_currentPresetNo;
			CalcFrameRect(
				m_pCameraWnd->m_vcc1.preset[presetNo].panPos,
				m_pCameraWnd->m_vcc1.preset[presetNo].tiltPos,
				m_pCameraWnd->m_vcc1.preset[presetNo].zoomPos,
				rect);
			dc.SelectObject(&m_penPreset);
			dc.SelectStockObject(NULL_BRUSH);
			dc.Rectangle(rect);
		}
	}
}

BOOL CCameraFrame::OnEraseBkgnd(CDC* pDC)
{
	return CWnd::OnEraseBkgnd(pDC);
}

void CCameraFrame::OnMouseMove(UINT nFlags, CPoint point) 
{
	CPoint	framePoint;
	CSize	frameSize;

	if (!m_pCameraWnd->m_bEnable)
		return;

	switch (m_clickMode) {
	case CURSOR_NONE:
		switch (CheckCursorPos(point)) {
		case CURSOR_IN_FRAME:
			SetCursor(m_hCursorOpenHand);
			break;
		case CURSOR_LEFT_FRAME:
		case CURSOR_RIGHT_FRAME:
			SetCursor(m_hCursorHMove);
			break;
		case CURSOR_TOP_FRAME:
		case CURSOR_BOTTOM_FRAME:
			SetCursor(m_hCursorVMove);
			break;
		case CURSOR_OTHER:
			SetCursor(m_hCursorArrow);
			break;
		}
		break;
	case CURSOR_IN_FRAME:
		CalcMovePoint(point, framePoint);
		DrawMoveFrame(framePoint, m_clickFrameSize, 2);
		SetCursor(m_hCursorCloseHand);
		break;
	case CURSOR_LEFT_FRAME:
	case CURSOR_RIGHT_FRAME:
		CalcMoveSize(point, frameSize);
		DrawMoveFrame(m_clickFramePoint, frameSize, 2);
		SetCursor(m_hCursorHMove);
		break;
	case CURSOR_TOP_FRAME:
	case CURSOR_BOTTOM_FRAME:
		CalcMoveSize(point, frameSize);
		DrawMoveFrame(m_clickFramePoint, frameSize, 2);
		SetCursor(m_hCursorVMove);
		break;
	case CURSOR_OTHER:
		SetCursor(m_hCursorArrow);
		break;
	}
}

void CCameraFrame::OnLButtonDown(UINT nFlags, CPoint point) 
{
	CPoint	framePoint;
	CSize	frameSize;

	if (!m_pCameraWnd->m_bEnable)
		return;

	CalcFramePoint(m_pCameraWnd->m_panPos, m_pCameraWnd->m_tiltPos, framePoint);
	CalcFrameSize(m_pCameraWnd->m_zoomPos, frameSize);

	m_clickFramePoint = framePoint;
	m_clickFrameSize = frameSize;
	m_clickCursorPoint = point;

	switch (m_clickMode = CheckCursorPos(point)) {
	case CURSOR_IN_FRAME:
		DrawMoveFrame(framePoint, frameSize, 0);
		SetCursor(m_hCursorCloseHand);
		break;
	case CURSOR_LEFT_FRAME:
	case CURSOR_RIGHT_FRAME:
		DrawMoveFrame(framePoint, frameSize, 0);
		SetCursor(m_hCursorHMove);
		break;
	case CURSOR_TOP_FRAME:
	case CURSOR_BOTTOM_FRAME:
		DrawMoveFrame(framePoint, frameSize, 0);
		SetCursor(m_hCursorVMove);
		break;
	case CURSOR_OTHER:
		m_pCameraWnd->SendMessage(WM_SETPANTILT, 0,
						CalcPanTilt(point));
		break;
	}

	SetCapture();
}

void CCameraFrame::OnLButtonUp(UINT nFlags, CPoint point) 
{
	CPoint	framePoint;
	CSize	frameSize;

	if (!m_pCameraWnd->m_bEnable)
		return;

	switch (m_clickMode) {
	case CURSOR_NONE:
		break;
	case CURSOR_IN_FRAME:
		CalcMovePoint(point, framePoint);
		DrawMoveFrame(point, m_clickFrameSize, 1);
		m_pCameraWnd->SendMessage(WM_SETPANTILT, 0, CalcPanTilt(framePoint));
		SetCursor(m_hCursorOpenHand);
		break;
	case CURSOR_LEFT_FRAME:
	case CURSOR_RIGHT_FRAME:
		CalcMoveSize(point, frameSize);
		DrawMoveFrame(m_clickFramePoint, frameSize, 1);
		m_pCameraWnd->SendMessage(WM_SETZOOM, CalcZoom(frameSize), 0L);
		SetCursor(m_hCursorHMove);
		break;
	case CURSOR_TOP_FRAME:
	case CURSOR_BOTTOM_FRAME:
		CalcMoveSize(point, frameSize);
		DrawMoveFrame(m_clickFramePoint, frameSize, 1);
		m_pCameraWnd->SendMessage(WM_SETZOOM, CalcZoom(frameSize), 0L);
		SetCursor(m_hCursorVMove);
		break;
	case CURSOR_OTHER:
		SetCursor(m_hCursorArrow);
		break;
	}
	m_clickMode = CURSOR_NONE;

	ReleaseCapture();
}

void CCameraWnd::OnCameraOption() 
{
	CCameraDlg(this).DoModal();
}

void CCameraFrame::CalcFramePoint(int panPos, int tiltPos, CPoint &point)
{
	if (panPos > PAN_MAX)
		panPos = PAN_MAX;
	point.x = (int)((long)(panPos - PAN_CENTER) * FRAME_MOVE_CX / PAN_MAX) + FRAME_CENTER_X;

	if (tiltPos > TILT_MAX)
		tiltPos = TILT_MAX;
	point.y = (int)((long)(TILT_CENTER - tiltPos) * FRAME_MOVE_CY / TILT_MAX) + FRAME_CENTER_Y;
}

void CCameraFrame::CalcFrameSize(int zoomPos, CSize &size)
{
	size.cx = (FRAME_TELE_CX + (ZOOM_MAX - zoomPos) * (FRAME_WIDE_CX - FRAME_TELE_CX) / ZOOM_MAX) / 2;
	size.cy = size.cx * 3 / 4;
}

void CCameraFrame::CalcFrameRect(int panPos, int tiltPos, int zoomPos, CRect &rect)
{
	CPoint	point;
	CSize	size;

	CalcFramePoint(panPos, tiltPos, point);
	CalcFrameSize(zoomPos, size);

	rect.left = point.x - size.cx;
	rect.top = point.y - size.cy;
	rect.right = point.x + size.cx;
	rect.bottom = point.y + size.cy;
}

long CCameraFrame::CalcPanTilt(const CPoint &point)
{
	int	x, y;

	x = (int)((long)(point.x - FRAME_MOVE_LEFT) * PAN_MAX / FRAME_MOVE_CX);
	if (x < 0)
		x = 0;
	else if (x > PAN_MAX)
		x = PAN_MAX;

	y = (int)((long)(point.y - FRAME_MOVE_TOP) * TILT_MAX / FRAME_MOVE_CY);
	if (y < 0)
		y = 0;
	else if (y > TILT_MAX)
		y = TILT_MAX;
	y = TILT_MAX - y;

	return MAKELPARAM(x, y);
}

int CCameraFrame::CalcZoom(const CSize &size)
{
	int	zoom;

	zoom = ZOOM_MAX - (size.cx * 2 - FRAME_TELE_CX) * ZOOM_MAX / (FRAME_WIDE_CX - FRAME_TELE_CX);
	if (zoom < 0)
		zoom = 0;
	else if (zoom > ZOOM_MAX)
		zoom = ZOOM_MAX;

	return zoom;
}

int CCameraFrame::CheckCursorPos(const CPoint &point)
{
	CRect	rect;
	int	rc;

	CalcFrameRect(m_pCameraWnd->m_panPos, m_pCameraWnd->m_tiltPos, m_pCameraWnd->m_zoomPos, rect);
	if (point.x > rect.left + 1 && point.x < rect.right - 1 &&
			point.y > rect.top + 1 && point.y < rect.bottom - 1)
		rc = CURSOR_IN_FRAME;
	else if (point.x >= rect.left - 1 && point.x <= rect.right + 1 &&
					point.y >= rect.top - 1 &&
					point.y <= rect.bottom + 1) {
		if (point.x <= rect.left + 1)
			rc = CURSOR_LEFT_FRAME;
		else if (point.x >= rect.right - 1)
			rc = CURSOR_RIGHT_FRAME;
		else if (point.y <= rect.top + 1)
			rc = CURSOR_TOP_FRAME;
		else
			rc = CURSOR_BOTTOM_FRAME;
	} else
		rc = CURSOR_OTHER;

	return rc;	
}

void CCameraFrame::CalcMovePoint(const CPoint &cursorPoint, CPoint &framePoint)
{
	framePoint.x = cursorPoint.x - m_clickCursorPoint.x + m_clickFramePoint.x;
	if (framePoint.x < FRAME_MOVE_LEFT)
		framePoint.x = FRAME_MOVE_LEFT;
	else if (framePoint.x > FRAME_MOVE_RIGHT)
		framePoint.x = FRAME_MOVE_RIGHT;

	framePoint.y = cursorPoint.y - m_clickCursorPoint.y + m_clickFramePoint.y;
	if (framePoint.y < FRAME_MOVE_TOP)
		framePoint.y = FRAME_MOVE_TOP;
	else if (framePoint.y > FRAME_MOVE_BOTTOM)
		framePoint.y = FRAME_MOVE_BOTTOM;
}

void CCameraFrame::CalcMoveSize(const CPoint &cursorPoint, CSize &frameSize)
{
	switch (m_clickMode) {
	case CURSOR_LEFT_FRAME:
		frameSize.cx = m_clickFramePoint.x - cursorPoint.x;
		goto setcy;
	case CURSOR_RIGHT_FRAME:
		frameSize.cx = cursorPoint.x - m_clickFramePoint.x;
	setcy:
		if (frameSize.cx < 0)
			frameSize.cx = FRAME_TELE_CX / 2;
		else if (frameSize.cx > FRAME_WIDE_CX / 2)
			frameSize.cx = FRAME_WIDE_CX / 2;
		frameSize.cy = frameSize.cx * 3 / 4;
		break;
	case CURSOR_TOP_FRAME:
		frameSize.cy = m_clickFramePoint.y - cursorPoint.y;
		goto setcx;
	case CURSOR_BOTTOM_FRAME:
		frameSize.cy = cursorPoint.y - m_clickFramePoint.y;
	setcx:
		if (frameSize.cy < 0)
			frameSize.cy = FRAME_TELE_CY / 2;
		else if (frameSize.cy > FRAME_WIDE_CY / 2)
			frameSize.cy = FRAME_WIDE_CY / 2;
		frameSize.cx = frameSize.cy * 4 / 3;
		break;
	}
}

void CCameraFrame::DrawMoveFrame(const CPoint &point, const CSize &size,
								int mode)
{
	CClientDC dc(this);
	CPoint	framePoint;
	CRect	rect;

	rect.left = point.x - size.cx;
	rect.top = point.y - size.cy;
	rect.right = point.x + size.cx;
	rect.bottom = point.y + size.cy;

	dc.SelectStockObject(NULL_BRUSH);
	dc.SelectStockObject(WHITE_PEN);
	dc.SetROP2(R2_XORPEN);
	if (mode != 0)
		dc.Rectangle(m_prevFrameRect);
	if (mode != 1)
		dc.Rectangle(rect);

	m_prevFrameRect = rect;
}

/////////////////////////////////////////////////////////////////////////////
// CToggleButton

CToggleButton::CToggleButton()
{
	m_bPreset = FALSE;
}

CToggleButton::~CToggleButton()
{
}


BEGIN_MESSAGE_MAP(CToggleButton, CButton)
	//{{AFX_MSG_MAP(CToggleButton)
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_WM_KILLFOCUS()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CToggleButton message handlers

WNDPROC *CToggleButton::GetSuperWndProcAddr()
{
	static WNDPROC NEAR pfnSuper;
	return &pfnSuper;
}

void CToggleButton::OnLButtonUp(UINT nFlags, CPoint point) 
{
	m_bPreset = !m_bPreset;
	if (!m_bPreset)
		CButton::OnLButtonUp(nFlags, point);
	else
		ReleaseCapture();
	((CCameraWnd *)GetParent())->EnablePresetButton();
}

void CToggleButton::OnMouseMove(UINT nFlags, CPoint point) 
{
}

void CToggleButton::OnKillFocus(CWnd* pNewWnd) 
{
}

/////////////////////////////////////////////////////////////////////////////
// CCameraDlg dialog


CCameraDlg::CCameraDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CCameraDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CCameraDlg)
		// NOTE: the ClassWizard will add member initialization here
	//}}AFX_DATA_INIT
}


void CCameraDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CCameraDlg)
		// NOTE: the ClassWizard will add DDX and DDV calls here
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CCameraDlg, CDialog)
	//{{AFX_MSG_MAP(CCameraDlg)
	ON_WM_HSCROLL()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CCameraDlg message handlers

void CCameraDlg::OnOK() 
{
	VCC1DATA &vcc1 = ((CCameraWnd *)GetParent())->m_vcc1;
	CScrollBar *pScrollBar;

	vcc1.serialPort = (int)SendDlgItemMessage(IDC_CAMERA_PORT, CB_GETCURSEL, 0, 0L);

	vcc1.bLightAdjust = (BOOL)SendDlgItemMessage(IDC_CAMERA_LIGHTADJUST, BM_GETCHECK, 0, 0);

	vcc1.bAutoWhiteBalance = (BOOL)SendDlgItemMessage(IDC_CAMERA_AUTOWHITE, BM_GETCHECK, 0, 0);

	pScrollBar = (CScrollBar *)GetDlgItem(IDC_CAMERA_PANSPEED);
	vcc1.panSpeed = pScrollBar->GetScrollPos();

	pScrollBar = (CScrollBar *)GetDlgItem(IDC_CAMERA_TILTSPEED);
	vcc1.tiltSpeed = pScrollBar->GetScrollPos();

	if ((int)SendDlgItemMessage(IDC_CAMERA_ZOOMLOW, BM_GETCHECK, 0, 0))
		vcc1.zoomSpeed = 0;
	else
		vcc1.zoomSpeed = 1;

	if ((int)SendDlgItemMessage(IDC_CAMERA_SHUTTER_AUTO, BM_GETCHECK, 0, 0))
		vcc1.shutterSpeed = 0;
	else if ((int)SendDlgItemMessage(IDC_CAMERA_SHUTTER_60, BM_GETCHECK, 0, 0))
		vcc1.shutterSpeed = 1;
	else
		vcc1.shutterSpeed = 2;

	vcc1.bWideConverter = (BOOL)SendDlgItemMessage(IDC_CAMERA_WIDECONVERTER, BM_GETCHECK, 0, 0);

	((CCameraWnd *)GetParent())->SendCameraInfo(INFO_INIT);

	CDialog::OnOK();
}

BOOL CCameraDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	int	 i;
	CScrollBar *pScrollBar;
	VCC1DATA &vcc1 = ((CCameraWnd *)GetParent())->m_vcc1;
	char	buf[10];

	for (i = 0; i < 4; i++) {
		wsprintf(buf, "COM%d", i + 1);
		SendDlgItemMessage(IDC_CAMERA_PORT, CB_ADDSTRING, 0, (LONG)(LPSTR)buf);
	}
	SendDlgItemMessage(IDC_CAMERA_PORT, CB_SETCURSEL, vcc1.serialPort, 0L);

	GetDlgItem(IDC_CAMERA_LIGHTADJUST)->SendMessage(BM_SETCHECK, vcc1.bLightAdjust ? 1 : 0, 0L);

	GetDlgItem(IDC_CAMERA_AUTOWHITE)->SendMessage(BM_SETCHECK, vcc1.bAutoWhiteBalance ? 1 : 0, 0L);

	pScrollBar = (CScrollBar *)GetDlgItem(IDC_CAMERA_PANSPEED);
	pScrollBar->SetScrollRange(PAN_SPEED_MIN, PAN_SPEED_MAX, FALSE);
	pScrollBar->SetScrollPos(vcc1.panSpeed, TRUE);
	wsprintf(buf, "%3dpps", vcc1.panSpeed);
	SetDlgItemText(IDC_CAMERA_PANPPS, buf);

	pScrollBar = (CScrollBar *)GetDlgItem(IDC_CAMERA_TILTSPEED);
	pScrollBar->SetScrollRange(PAN_SPEED_MIN, TILT_SPEED_MAX, FALSE);
	pScrollBar->SetScrollPos(vcc1.tiltSpeed, TRUE);
	wsprintf(buf, "%3dpps", vcc1.tiltSpeed);
	SetDlgItemText(IDC_CAMERA_TILTPPS, buf);

	GetDlgItem(IDC_CAMERA_ZOOMLOW)->SendMessage(BM_SETCHECK,
					vcc1.zoomSpeed == 0 ? 1 : 0, 0L);
	GetDlgItem(IDC_CAMERA_ZOOMHIGH)->SendMessage(BM_SETCHECK,
					vcc1.zoomSpeed == 1 ? 1 : 0, 0L);

	GetDlgItem(IDC_CAMERA_SHUTTER_AUTO)->SendMessage(BM_SETCHECK,
					vcc1.shutterSpeed == 0 ? 1 : 0, 0L);
	GetDlgItem(IDC_CAMERA_SHUTTER_60)->SendMessage(BM_SETCHECK,
					vcc1.shutterSpeed == 1 ? 1 : 0, 0L);
	GetDlgItem(IDC_CAMERA_SHUTTER_100)->SendMessage(BM_SETCHECK,
					vcc1.shutterSpeed == 2 ? 1 : 0, 0L);

	GetDlgItem(IDC_CAMERA_WIDECONVERTER)->SendMessage(BM_SETCHECK,
					vcc1.bWideConverter ? 1 : 0, 0L);

	return TRUE;
}

void CCameraDlg::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	char	buf[10];

	switch (pScrollBar->GetDlgCtrlID()) {
	case IDC_CAMERA_PANSPEED:
		nPos = CalcScrollPos(nSBCode, nPos, PAN_SPEED_MIN, PAN_SPEED_MAX, pScrollBar);
		pScrollBar->SetScrollPos(nPos, TRUE);

		wsprintf(buf, "%3dpps", nPos);
		SetDlgItemText(IDC_CAMERA_PANPPS, buf);
		break;
	case IDC_CAMERA_TILTSPEED:
		nPos = CalcScrollPos(nSBCode, nPos, TILT_SPEED_MIN, TILT_SPEED_MAX, pScrollBar);
		pScrollBar->SetScrollPos(nPos, TRUE);

		wsprintf(buf, "%3dpps", nPos);
		SetDlgItemText(IDC_CAMERA_TILTPPS, buf);
		break;
	}

}

int CCameraDlg::CalcScrollPos(int nSBCode, int nPos, int minPos, int maxPos, CScrollBar* pScrollBar)
{
	switch (nSBCode) {
	case SB_LEFT:
		nPos = minPos;
		break;
	case SB_LINELEFT:
		nPos = pScrollBar->GetScrollPos() - 1;
		break;
	case SB_PAGELEFT:
		nPos = pScrollBar->GetScrollPos() - 100;
		break;
	case SB_RIGHT:
		nPos = maxPos;
		break;
	case SB_LINERIGHT:
		nPos = pScrollBar->GetScrollPos() + 1;
		break;
	case SB_PAGERIGHT:
		nPos = pScrollBar->GetScrollPos() + 100;
		break;
	case SB_THUMBPOSITION:
		break;
	case SB_THUMBTRACK:
		break;
	default:
		nPos = pScrollBar->GetScrollPos();
		break;
	}

	if (nPos < minPos)
		nPos = minPos;
	else if (nPos > maxPos)
		nPos = maxPos;

	return nPos;
}
/////////////////////////////////////////////////////////////////////////////
// CPresetDlg dialog


CPresetDlg::CPresetDlg(CWnd* pParent /*=NULL*/, int presetNo)
	: CDialog(CPresetDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CPresetDlg)
		// NOTE: the ClassWizard will add member initialization here
	//}}AFX_DATA_INIT

	m_pCameraWnd = (CCameraWnd *)pParent;
	m_presetNo = presetNo;
}


void CPresetDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CPresetDlg)
		// NOTE: the ClassWizard will add DDX and DDV calls here
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CPresetDlg, CDialog)
	//{{AFX_MSG_MAP(CPresetDlg)
	ON_BN_CLICKED(IDC_DELETE, OnDelete)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CPresetDlg message handlers

BOOL CPresetDlg::OnInitDialog() 
{
	char	buf[10];

	CDialog::OnInitDialog();

	wsprintf(buf, "%d", m_presetNo + 1);
	SetDlgItemText(IDC_CAMERA_PRESETNO, buf);

	wsprintf(buf, "%dﾟ", (int)(((long)m_pCameraWnd->m_panPos * 100 + PAN_CENTER) / PAN_MAX) - 50);
	SetDlgItemText(IDC_CAMERA_PAN, buf);

	wsprintf(buf, "%dﾟ", (int)(((long)m_pCameraWnd->m_tiltPos * 40 + TILT_CENTER) / TILT_MAX) - 20);
	SetDlgItemText(IDC_CAMERA_TILT, buf);

	SetDlgItemText(IDC_CAMERA_COMMENT, pCameraWnd->m_vcc1.preset[m_presetNo].comment);

	GetDlgItem(IDC_CAMERA_COMMENT)->SetFocus();

	return FALSE;
}

void CPresetDlg::OnOK() 
{
	char	comment[31];

	GetDlgItemText(IDC_CAMERA_COMMENT, comment, sizeof(comment));
	if (comment[0] == '\0') {
		MessageBoxID(this, IDS_INPUT_COMMENT, MB_ICONEXCLAMATION);
		GetDlgItem(IDC_CAMERA_COMMENT)->SetFocus();
		return;
	}

	pCameraWnd->m_vcc1.preset[m_presetNo].panPos = pCameraWnd->m_panPos;
	pCameraWnd->m_vcc1.preset[m_presetNo].tiltPos = pCameraWnd->m_tiltPos;
	pCameraWnd->m_vcc1.preset[m_presetNo].zoomPos = pCameraWnd->m_zoomPos;
	strcpy(pCameraWnd->m_vcc1.preset[m_presetNo].comment, comment);

	EndDialog(1);	
}

void CPresetDlg::OnDelete() 
{
	pCameraWnd->m_vcc1.preset[m_presetNo].comment[0] = '\0';

	EndDialog(1);
}
/////////////////////////////////////////////////////////////////////////////
