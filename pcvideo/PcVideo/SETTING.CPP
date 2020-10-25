/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: 環境設定処理
 *		ファイル名	: setting.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// setting.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "setting.h"
#include "pcvideo.h"
#include "mainfrm.h"

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

static	SETTINGS	*sp;

/////////////////////////////////////////////////////////////////////////////
// CSettingDlg ダイアログ

CSettingDlg::CSettingDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CSettingDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSettingDlg)
	m_iVoiceBufferNum = 0;
	//}}AFX_DATA_INIT
}

void CSettingDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSettingDlg)
	DDX_Control(pDX, IDC_VOICE_OUTPUT_DEVICE, m_cVoiceOutputDevice);
	DDX_Control(pDX, IDC_VOICE_INPUT_DEVICE, m_cVoiceInputDevice);
	DDX_Text(pDX, IDC_VOICE_BUFFER_NUM, m_iVoiceBufferNum);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CSettingDlg, CDialog)
	//{{AFX_MSG_MAP(CSettingDlg)
	ON_BN_CLICKED(IDC_MAXSPEED, OnClickedMaxspeed)
	ON_BN_CLICKED(IDC_RESIZE, OnClickedResize)
	ON_BN_CLICKED(IDC_FIXEDSIZE, OnClickedFixedsize)
	ON_BN_CLICKED(IDC_LICENSE, OnLicense)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSettingDlg メッセージハンドラ

BOOL CSettingDlg::OnInitDialog()
{
	CDialog::OnInitDialog();
	
	char	buf[20];
	int	i;

	SetWindowText(caption);

	SetDlgItemText(IDC_USERNAME, sp->userName);

	SendDlgItemMessage(IDC_VIDEO_CAPTURE, BM_SETCHECK, sp->bVideoCapture ? 1 : 0, 0);
	SendDlgItemMessage(IDC_RECEIVEVIDEO, BM_SETCHECK, sp->bReceiveVideo ? 1 : 0, 0);

	SendDlgItemMessage(IDC_REMOTESETTING, BM_SETCHECK, sp->bRemoteSetting ? 1 : 0, 0);

	for (i = 0; i < 16; i++) {
		wsprintf(buf, "%d x %d", videoSize[i].width, videoSize[i].height);
		SendDlgItemMessage(IDC_MOVIESIZE, CB_ADDSTRING, 0, (LONG)(LPSTR)buf);
		SendDlgItemMessage(IDC_STILLSIZE, CB_ADDSTRING, 0, (LONG)(LPSTR)buf);
		SendDlgItemMessage(IDC_RECVSIZE, CB_ADDSTRING, 0, (LONG)(LPSTR)buf);
	}
	SendDlgItemMessage(IDC_MOVIESIZE, CB_SETCURSEL, sp->movieSize, 0L);
	SendDlgItemMessage(IDC_STILLSIZE, CB_SETCURSEL, sp->stillSize, 0L);

	SendDlgItemMessage(IDC_VIDEOCOLORS, CB_ADDSTRING, 0, (LONG)(LPCSTR)GetString(IDS_COLOR_MONO));
	SendDlgItemMessage(IDC_VIDEOCOLORS, CB_ADDSTRING, 0, (LONG)(LPCSTR)GetString(IDS_COLOR_HIGH));
	SendDlgItemMessage(IDC_VIDEOCOLORS, CB_ADDSTRING, 0, (LONG)(LPCSTR)GetString(IDS_COLOR_TRUE));
	SendDlgItemMessage(IDC_VIDEOCOLORS, CB_SETCURSEL, sp->videoColor, 0L);

	SendDlgItemMessage(IDC_CAMERA_TYPE, CB_ADDSTRING, 0, (LONG)(LPCSTR)GetString(IDS_OTHER));
	SendDlgItemMessage(IDC_CAMERA_TYPE, CB_ADDSTRING, 0, (LONG)(LPCSTR)GetString(IDS_CANNON_VCC1));
	SendDlgItemMessage(IDC_CAMERA_TYPE, CB_SETCURSEL, sp->cameraType, 0L);

	SetDlgItemInt(IDC_VIDEOQUALITY, sp->videoQuality, FALSE);

	SendDlgItemMessage(IDC_CAMERACHANGE, BM_SETCHECK, sp->bCameraChange ? 1 : 0, 0);

	SetDlgItemInt(IDC_EDGEENHANCE, sp->videoEdgeEnhance, FALSE);

	SetDlgItemInt(IDC_MAXBPS, sp->maxBPS, FALSE);
	SendDlgItemMessage(IDC_MAXSPEED, BM_SETCHECK, sp->bMaxSpeed ? 1 : 0, 0);

	GetDlgItem(IDC_MAXBPS)->EnableWindow(!sp->bMaxSpeed);

	SendDlgItemMessage(IDC_SENDINFO, BM_SETCHECK, sp->bSendInfo ? 1 : 0, 0);

	SendDlgItemMessage(IDC_FULLFRAME, BM_SETCHECK, sp->bFullFrame ? 1 : 0, 0);

	SendDlgItemMessage(IDC_RESIZE, BM_SETCHECK, sp->bResize ? 1 : 0, 0);

	SendDlgItemMessage(IDC_FIXEDASPECT, BM_SETCHECK, sp->bFixedAspect ? 1 : 0, 0);
	GetDlgItem(IDC_FIXEDASPECT)->EnableWindow(sp->bResize);

	SendDlgItemMessage(IDC_FIXEDSIZE, BM_SETCHECK, sp->bFixedSize ? 1 : 0, 0);

	SendDlgItemMessage(IDC_RECVSIZE, CB_SETCURSEL, sp->recvSize, 0L);
	GetDlgItem(IDC_RECVSIZE)->EnableWindow(sp->bFixedSize);

	SendDlgItemMessage(IDC_DISPINFO, BM_SETCHECK, sp->bDispInfo ? 1 : 0, 0);

	InitVoiceSetting();

	UpdateData(FALSE);
	
	return TRUE;
}

void CSettingDlg::InitVoiceSetting()
{
	int nInputDevice;
	int nOutputDevice;
	WAVEINCAPS waveInCaps;
	WAVEOUTCAPS waveOutCaps;
	int i;

	nInputDevice = waveInGetNumDevs();
	for (i = 0; i < nInputDevice; i++) {
		waveInGetDevCaps(i, &waveInCaps, sizeof(waveInCaps));
		m_cVoiceInputDevice.AddString(waveInCaps.szPname);
	}
	if (iniData.voiceWindow.nInputDevice >= nInputDevice)
		iniData.voiceWindow.nInputDevice = 0;
	m_cVoiceInputDevice.SetCurSel(iniData.voiceWindow.nInputDevice);

	nOutputDevice = waveOutGetNumDevs();
	for (i = 0; i < nOutputDevice; i++) {
		waveOutGetDevCaps(i, &waveOutCaps, sizeof(waveOutCaps));
		m_cVoiceOutputDevice.AddString(waveOutCaps.szPname);
	}
	if (iniData.voiceWindow.nOutputDevice >= nOutputDevice)
		iniData.voiceWindow.nOutputDevice = 0;
	m_cVoiceOutputDevice.SetCurSel(iniData.voiceWindow.nOutputDevice);

	m_iVoiceBufferNum = iniData.voiceWindow.nBufferNum;
}

void CSettingDlg::OnOK()
{
	int nIndex;
	BOOL	err;

	UpdateData(TRUE);

	GetDlgItemText(IDC_USERNAME, sp->userName, sizeof(sp->userName));
	sp->bVideoCapture = (BOOL)SendDlgItemMessage(IDC_VIDEO_CAPTURE, BM_GETCHECK, 0, 0);
	sp->bReceiveVideo = (BOOL)SendDlgItemMessage(IDC_RECEIVEVIDEO, BM_GETCHECK, 0, 0);
	sp->bRemoteSetting = (BOOL)SendDlgItemMessage(IDC_REMOTESETTING, BM_GETCHECK, 0, 0);
	sp->movieSize = (int)SendDlgItemMessage(IDC_MOVIESIZE, CB_GETCURSEL, 0, 0L);
	sp->stillSize = (int)SendDlgItemMessage(IDC_STILLSIZE, CB_GETCURSEL, 0, 0L);

	sp->videoColor = (int)SendDlgItemMessage(IDC_VIDEOCOLORS, CB_GETCURSEL, 0, 0L);
	sp->cameraType = (int)SendDlgItemMessage(IDC_CAMERA_TYPE, CB_GETCURSEL, 0, 0L);
	sp->videoQuality = GetDlgItemInt(IDC_VIDEOQUALITY, &err, FALSE);
	sp->bCameraChange = (BOOL)SendDlgItemMessage(IDC_CAMERACHANGE, BM_GETCHECK, 0, 0);
	sp->videoEdgeEnhance = GetDlgItemInt(IDC_EDGEENHANCE, &err, FALSE);

	sp->maxBPS = GetDlgItemInt(IDC_MAXBPS, &err, FALSE);
	sp->bMaxSpeed = (BOOL)SendDlgItemMessage(IDC_MAXSPEED, BM_GETCHECK, 0, 0);
	sp->bSendInfo = (BOOL)SendDlgItemMessage(IDC_SENDINFO, BM_GETCHECK, 0, 0);
	sp->bFullFrame = (BOOL)SendDlgItemMessage(IDC_FULLFRAME, BM_GETCHECK, 0, 0);
	sp->bResize = (BOOL)SendDlgItemMessage(IDC_RESIZE, BM_GETCHECK, 0, 0);
	sp->bFixedAspect = (BOOL)SendDlgItemMessage(IDC_FIXEDASPECT, BM_GETCHECK, 0, 0);
	sp->bFixedSize = (BOOL)SendDlgItemMessage(IDC_FIXEDSIZE, BM_GETCHECK, 0, 0);
	sp->recvSize = (int)SendDlgItemMessage(IDC_RECVSIZE, CB_GETCURSEL, 0, 0);
	sp->bDispInfo = (BOOL)SendDlgItemMessage(IDC_DISPINFO, BM_GETCHECK, 0, 0);

	if ((nIndex = m_cVoiceInputDevice.GetCurSel()) != LB_ERR)
		iniData.voiceWindow.nInputDevice = nIndex;

	if ((nIndex = m_cVoiceOutputDevice.GetCurSel()) != LB_ERR)
		iniData.voiceWindow.nOutputDevice = nIndex;

	iniData.voiceWindow.nBufferNum = m_iVoiceBufferNum;

	EndDialog(TRUE);
}

void CSettingDlg::OnCancel()
{
	EndDialog(FALSE);
}

void CSettingDlg::OnClickedMaxspeed()
{
	GetDlgItem(IDC_MAXBPS)->EnableWindow(
				!SendDlgItemMessage(IDC_MAXSPEED,
				BM_GETCHECK, 0, 0));
}

void CSettingDlg::OnClickedResize()
{
	GetDlgItem(IDC_FIXEDASPECT)->EnableWindow(
				(int)SendDlgItemMessage(IDC_RESIZE,
				BM_GETCHECK, 0, 0) != 0);
}

void CSettingDlg::OnClickedFixedsize()
{
	GetDlgItem(IDC_RECVSIZE)->EnableWindow(
				(int)SendDlgItemMessage(IDC_FIXEDSIZE,
				BM_GETCHECK, 0, 0) != 0);
}

BOOL	CSettingDlg::Open(SETTINGS *settings, LPCTSTR title)
{
	int	rc;

	caption = title;
	sp = settings;
	rc = DoModal();
	sp = NULL;

	return rc;
}

/////////////////////////////////////////////////////////////////////////////
// CSetHostDlg ダイアログ

CSetHostDlg::CSetHostDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CSetHostDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetHostDlg)
		// 注意: ClassWizard はこの位置にメンバの初期化処理を追加します。
	//}}AFX_DATA_INIT
}

void CSetHostDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetHostDlg)
		// 注意: ClassWizard はこの位置に DDX と DDV の呼び出しコードを追加します。
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CSetHostDlg, CDialog)
	//{{AFX_MSG_MAP(CSetHostDlg)
	ON_BN_CLICKED(IDC_APPEND, OnClickedAppend)
	ON_BN_CLICKED(IDC_CHANGE, OnClickedChange)
	ON_BN_CLICKED(IDC_DELETE, OnClickedDelete)
	ON_LBN_DBLCLK(IDC_HOSTLIST, OnDblclkHostlist)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSetHostDlg メッセージハンドラ

BOOL CSetHostDlg::OnInitDialog()
{
	int	i, index;
	char	buf[100];
	PUSER	pUser;
	struct	hostent	*hp;

	CDialog::OnInitDialog();

	SendDlgItemMessage(IDC_HOSTNAME, EM_LIMITTEXT,
				sizeof(iniData.conxxxt.host[0]) - 1, 0L);
	SendDlgItemMessage(IDC_NAME, EM_LIMITTEXT,
				sizeof(iniData.conxxxt.name[0]) - 1, 0L);
	SendDlgItemMessage(IDC_CHANNELNO, EM_LIMITTEXT, 2, 0L);

	for (i = 0; i < 15; i++) {
		if (iniData.conxxxt.host[i][0] != '\0') {
			wsprintf(buf, "%s (%s,%d)",
					(LPSTR)iniData.conxxxt.name[i],
					(LPSTR)iniData.conxxxt.host[i],
					iniData.conxxxt.channelNo[i]);
			SendDlgItemMessage(IDC_HOSTLIST,
					LB_ADDSTRING, 0, (LPARAM)(LPSTR)buf);
		}
	}

	if ((index = pMainListWnd->GetCurSel()) != LB_ERR) {
		if ((pUser = GetUser(index)) != NULL) {
			SetDlgItemText(IDC_NAME, pUser->userName);
			hp = gethostbyaddr((const char *)&pUser->id, 4, PF_INET);
			if (hp != NULL)
				SetDlgItemText(IDC_HOSTNAME, hp->h_name);
			else
				SetDlgItemText(IDC_HOSTNAME, inet_ntoa(*(struct in_addr *)&pUser->id));
			SetDlgItemInt(IDC_CHANNELNO, pUser->channelNo, FALSE);
		}
	}

	SetChangeListBoxItem(m_hWnd, IDC_HOSTLIST);
	
	return TRUE;  // コントロールにフォーカスを設定しないとき、戻り値は TRUE となります。
}

void CSetHostDlg::OnOK()
{
	int	i, n;
	char	buf[100], *p;

	n = SendDlgItemMessage(IDC_HOSTLIST, LB_GETCOUNT, 0, 0L);

	memset(&iniData.conxxxt, 0, sizeof(iniData.conxxxt));
	for (i = 0; i < n; i++) {
		SendDlgItemMessage(IDC_HOSTLIST, LB_GETTEXT, i, (LPARAM)(LPSTR)buf);
		p = strrchr(buf, ' ');
		*p++ = '\0';
		p = strchr(p, '(') + 1;
		strcpy(iniData.conxxxt.name[i], buf);
		strcpy(iniData.conxxxt.host[i], strtok(p, ","));
		iniData.conxxxt.channelNo[i] = atoi(strtok(NULL, ")"));
	}

	pMainWnd->SetConxxxtMenu();

	EndDialog(TRUE);
}

void CSetHostDlg::OnCancel()
{
	EndDialog(FALSE);
}

void CSetHostDlg::OnClickedAppend()
{
	char	name[32];
	char	hostName[20];
	char	channelNo[2];
	char	buf[100];
	int	n;

	if (SendDlgItemMessage(IDC_HOSTLIST, LB_GETCOUNT, 0, 0L) >= 15) {
		AfxMessageBox(IDS_REG_LIMIT);
		return;
	}

	GetDlgItemText(IDC_NAME, name, sizeof(name));
	GetDlgItemText(IDC_HOSTNAME, hostName, sizeof(hostName));
	GetDlgItemText(IDC_CHANNELNO, channelNo, sizeof(channelNo));

	if (name[0] == '\0' || hostName[0] == '\0') {
		MessageBeep(0);
		return;
	}

	strtok(name, ",");
	strtok(hostName, " ,");
	wsprintf(buf, "%s (%s,%d)", (LPSTR)name, (LPSTR)hostName, atoi(channelNo));
	SendDlgItemMessage(IDC_HOSTLIST, LB_ADDSTRING, 0, (LPARAM)(LPSTR)buf);

	n = (int)SendDlgItemMessage(IDC_HOSTLIST, LB_GETCOUNT, 0, 0L);
	SendDlgItemMessage(IDC_HOSTLIST, LB_SETCURSEL, n - 1, 0L);
}

void CSetHostDlg::OnClickedChange()
{
	int	index;
	char	name[32];
	char	hostName[20];
	char	channelNo[2];
	char	buf[100];

	index = (int)SendDlgItemMessage(IDC_HOSTLIST, LB_GETCURSEL, 0, 0L);
	if (index == LB_ERR)
		return;

	GetDlgItemText(IDC_NAME, name, sizeof(name));
	GetDlgItemText(IDC_HOSTNAME, hostName, sizeof(hostName));
	GetDlgItemText(IDC_CHANNELNO, channelNo, sizeof(channelNo));
	strtok(name, ",");
	strtok(hostName, " ,");
	wsprintf(buf, "%s (%s,%d)", (LPSTR)name, (LPSTR)hostName, atoi(channelNo));
	SendDlgItemMessage(IDC_HOSTLIST, LB_DELETESTRING, index, 0L);
	SendDlgItemMessage(IDC_HOSTLIST, LB_INSERTSTRING, index, (LPARAM)(LPSTR)buf);
	SendDlgItemMessage(IDC_HOSTLIST, LB_SETCURSEL, index, 0L);
}

void CSetHostDlg::OnClickedDelete()
{
	int	index, n;

	index = (int)SendDlgItemMessage(IDC_HOSTLIST, LB_GETCURSEL, 0, 0L);
	if (index == LB_ERR)
		return;

	SendDlgItemMessage(IDC_HOSTLIST, LB_DELETESTRING, index, 0L);

	n = (int)SendDlgItemMessage(IDC_HOSTLIST, LB_GETCOUNT, 0, 0L);
	if (n != 0) {
		if (index == n)
			SendDlgItemMessage(IDC_HOSTLIST, LB_SETCURSEL, index - 1, 0L);
		else
			SendDlgItemMessage(IDC_HOSTLIST, LB_SETCURSEL, index, 0L);
		DispHostData();
	}
}

void CSetHostDlg::OnDblclkHostlist()
{
	DispHostData();
}

void	CSetHostDlg::DispHostData()
{
	int	index;
	char	buf[100], *p;

	if ((index = (int)SendDlgItemMessage(IDC_HOSTLIST, LB_GETCURSEL, 0, 0L)) == LB_ERR)
		return;

	SendDlgItemMessage(IDC_HOSTLIST, LB_GETTEXT, index, (LPARAM)(LPSTR)buf);

	p = strrchr(buf, ' ');
	*p++ = '\0';
	p = strchr(p, '(') + 1;
	SetDlgItemText(IDC_NAME, buf);
	SetDlgItemText(IDC_HOSTNAME, strtok(p, ","));
	SetDlgItemText(IDC_CHANNELNO, strtok(NULL, ")"));
}

void CSettingDlg::OnLicense() 
{
	CDialog(IDD_LICENSE, this).DoModal();
}
