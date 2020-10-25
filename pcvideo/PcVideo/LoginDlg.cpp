// LoginDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "pcvideo.h"
#include "pcvcomm.h"
#include "LoginDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

void ReceiveLoginRequest(PLOGINREQ pLoginReq, PSOCK pSockSend)
{
	CLoginDlg dlg;
	static BOOL bInLoginDlg;

	dlg.m_ServerID = pLoginReq->header.id;
	dlg.m_sServerName = pLoginReq->serverName;

	if (!bInLoginDlg) {
		bInLoginDlg = TRUE;
		if (dlg.DoModal() == IDOK) {
			LOGIN login;
			MakeConxxxtPacket((PCONxxxT)&login, conxxxtChannelNo);
			login.conxxxt.header.flag = FLAG_LOGIN;
			strcpy(login.loginName, dlg.m_sUserID);
			strcpy(login.password, dlg.m_sPassword);
			SendData(pSockSend, &login, sizeof(LOGIN));
		}
		bInLoginDlg = FALSE;
	}
}

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg ダイアログ


CLoginDlg::CLoginDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CLoginDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CLoginDlg)
	m_sPassword = _T("");
	m_bSavePassword = FALSE;
	m_sServerName = _T("");
	m_sUserID = _T("");
	//}}AFX_DATA_INIT
}


void CLoginDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CLoginDlg)
	DDX_Control(pDX, IDC_USERID, m_cUserID);
	DDX_Control(pDX, IDOK, m_cOK);
	DDX_Control(pDX, IDC_PASSWORD, m_cPassword);
	DDX_Text(pDX, IDC_PASSWORD, m_sPassword);
	DDV_MaxChars(pDX, m_sPassword, 30);
	DDX_Check(pDX, IDC_SAVE_PASSWORD, m_bSavePassword);
	DDX_Text(pDX, IDC_SERVER_NAME, m_sServerName);
	DDX_Text(pDX, IDC_USERID, m_sUserID);
	DDV_MaxChars(pDX, m_sUserID, 30);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CLoginDlg, CDialog)
	//{{AFX_MSG_MAP(CLoginDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLoginDlg メッセージ ハンドラ

BOOL CLoginDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	int i;

	for (i = 0; i < N_INI_LOGIN; i++) {
		if (m_ServerID == iniData.login[i].serverID)
			break;
	}

	if (i < N_INI_LOGIN) {
		m_sUserID = iniData.login[i].userID;
		m_bSavePassword = iniData.login[i].bSavePassword;
		if (m_bSavePassword && iniData.login[0].password[0] != '\0') {
			char password[32];
			DecodePassword(iniData.login[i].password, password);
			m_sPassword = password;
		}
	} else {
		char userName[32];
		DWORD len = sizeof(userName);
		if (GetUserName(userName, &len))
			m_sUserID = userName;
	}

	if (m_sUserID.GetLength() == 0)
		m_cUserID.SetFocus();
	else if (m_sPassword.GetLength() == 0)
		m_cPassword.SetFocus();
	else
		m_cOK.SetFocus();

	UpdateData(FALSE);
	
	return FALSE;
}

void CLoginDlg::OnOK() 
{
	UpdateData(TRUE);

	int i;

	for (i = 0; i < N_INI_LOGIN; ) {
		if (m_ServerID == iniData.login[i++].serverID)
			break;
	}

	while (--i > 0) {
		iniData.login[i] = iniData.login[i - 1];
	}

	iniData.login[0].serverID = m_ServerID;
	strcpy(iniData.login[0].userID, m_sUserID);
	EncodePassword(m_sPassword, iniData.login[0].password);
	iniData.login[0].bSavePassword = m_bSavePassword;

	EndDialog(IDOK);
}

void CLoginDlg::EncodePassword(LPCTSTR pPasswordIn, LPSTR pPasswordOut)
{
	int i;
	int len = strlen(pPasswordIn);

	srand(123);

	for (i = 0; i < len; i++) {
		*pPasswordOut++ = (pPasswordIn[i] >> 4) + 0x40 + (rand() % 10);
		*pPasswordOut++ = (pPasswordIn[i] & 0x0f) + 0x40 + (rand() % 10);
	}

	*pPasswordOut = '\0';
}

void CLoginDlg::DecodePassword(LPCTSTR pPasswordIn, LPSTR pPasswordOut)
{
	int c1, c2, i;
	int len = strlen(pPasswordIn) / 2;

	srand(123);

	for (i = 0; i < len; i++) {
		c1 = *pPasswordIn++;
		c1 = (c1 - 0x40 - (rand() % 10)) << 4;
		c2 = *pPasswordIn++;
		c2 = (c2 - 0x40 - (rand() % 10));
		*pPasswordOut++ = c1 | c2;
	}

	*pPasswordOut = '\0';
}
