/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: チャット処理
 *		ファイル名	: chat.cpp
 *		作成者		: s.aizawa
 *
 ************************************************************************/

// chat.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "resource.h"
#include "pcvcomm.h"
#include "chat.h"

#ifdef _DEBUG
#undef THIS_FILE
static char BASED_CODE THIS_FILE[] = __FILE__;
#endif

static UINT indicators[] =
{
	ID_SEPARATOR,           // ステータス ライン インジケータ
	ID_INDICATOR_KANA,
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
};

void OpenChatWindow()
{
	if (pChatWnd == NULL) {
		pChatWnd = new CChatWnd();
		SendConxxxtAll();
	}
}

void CloseChatWindow()
{
	if (pChatWnd != NULL)
		pChatWnd->DestroyWindow();
}

/////////////////////////////////////////////////////////////////////////////
// CChatWnd

IMPLEMENT_DYNCREATE(CChatWnd, CFrameWnd)

CChatWnd::CChatWnd()
{
	LoadAccelTable(MAKEINTRESOURCE(IDR_MAINFRAME));

	m_nIDHelp = 1;
	m_nDisplayViewHeight = 0;
	m_nClientHeight = 0;

	if (iniData.chatWindow.font[0] == '\0')
		m_font.CreateStockObject(SYSTEM_FONT);
	else {
		LOGFONT	lf;

		memset(&lf, 0, sizeof(lf));
		lstrcpy(lf.lfFaceName, iniData.chatWindow.font);
		lf.lfHeight = -iniData.chatWindow.fontSize;
		lf.lfCharSet = SHIFTJIS_CHARSET;
		m_font.CreateFontIndirect(&lf);
	}

	m_fpLog = NULL;

	CRect	rect;
	rect.left = iniData.chatWindow.left;
	rect.top = iniData.chatWindow.top;
	rect.right = iniData.chatWindow.right;
	rect.bottom = iniData.chatWindow.bottom;
	Create(NULL, GetString(IDS_CHAT), WS_OVERLAPPEDWINDOW, rect, (CWnd *)&wndTop, MAKEINTRESOURCE(IDR_CHAT));

	ShowWindow(SW_SHOW);
	UpdateWindow();
}

CChatWnd::~CChatWnd()
{
}


BEGIN_MESSAGE_MAP(CChatWnd, CFrameWnd)
	//{{AFX_MSG_MAP(CChatWnd)
	ON_WM_CREATE()
	ON_WM_SIZE()
	ON_COMMAND(IDM_EXIT, OnExit)
	ON_COMMAND(IDM_FONT, OnFont)
	ON_COMMAND(IDM_LOGFILE, OnLogFile)
	ON_COMMAND(IDM_CHATCALL, OnChatCall)
	ON_WM_DESTROY()
	ON_COMMAND(ID_FILE_SAVE, OnFileSave)
	ON_UPDATE_COMMAND_UI(ID_FILE_SAVE, OnUpdateFileSave)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_ENTERSIZEMOVE, OnEnterSizeMove)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CChatWnd メッセージ ハンドラ


int CChatWnd::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	SetIcon(hIconChat, FALSE);

	if (iniData.chatWindow.bAlwaysLog)
		OpenLogFile();

	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_CHAT))
	{
		TRACE0("Failed to create toolbar\n");
		return -1;      // 作成に失敗
	}

	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("Failed to create status bar\n");
		return -1;      // 作成に失敗
	}

	m_wndStatusBar.SetPaneStyle(0, SBPS_NORMAL);

	m_pDisplayView->SetFont(&m_font);
	m_pInputView->SetFont(&m_font);

	if (iniData.settings.bFrontKeep)
		SetWindowPos(&wndTopMost, 0, 0, 0, 0, SWP_NOMOVE|SWP_NOSIZE|SWP_NOACTIVATE);

	return 0;
}

BOOL CChatWnd::OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext) 
{
	CRect	rect;

	// create a splitter with 2 row, 1 columns
	if (!m_wndSplitter.CreateStatic(this, 2, 1))
	{
		TRACE0("Failed to CreateStaticSplitter\n");
		return FALSE;
	}

	// add the first splitter pane - the default view in column 0
	if (!m_wndSplitter.CreateView(0, 0,
		RUNTIME_CLASS(CChatDispView), CSize(0, iniData.chatWindow.splitter), pContext))
	{
		TRACE0("Failed to create first pane\n");
		return FALSE;
	}

	// add the first splitter pane - the default view in column 0
	if (!m_wndSplitter.CreateView(1, 0,
		RUNTIME_CLASS(CChatInputView), CSize(0, 0), pContext))
	{
		TRACE0("Failed to create first pane\n");
		return FALSE;
	}

	m_wndSplitter.SetActivePane(1, 0);

	m_pDisplayView = (CChatDispView *)m_wndSplitter.GetDlgItem(m_wndSplitter.IdFromRowCol(0, 0));
	m_pInputView = (CChatInputView *)m_wndSplitter.GetDlgItem(m_wndSplitter.IdFromRowCol(1, 0));

	return TRUE;
}

void CChatWnd::OnDestroy() 
{
	WINDOWPLACEMENT winpl;

	CloseAnswerWindow();
	CloseCallWindow();
	CloseLogFile();

	winpl.length = sizeof(WINDOWPLACEMENT);
	GetWindowPlacement(&winpl);			// ダイアログ表示位置取得
	iniData.chatWindow.left = winpl.rcNormalPosition.left;
	iniData.chatWindow.top = winpl.rcNormalPosition.top;
	iniData.chatWindow.right = winpl.rcNormalPosition.right;
	iniData.chatWindow.bottom = winpl.rcNormalPosition.bottom;

	int cyCur, cyMin;
	m_wndSplitter.GetRowInfo(0, cyCur, cyMin);
	iniData.chatWindow.splitter = cyCur;
	m_wndSplitter.GetRowInfo(1, cyCur, cyMin);

	pChatWnd = NULL;
	SendConxxxtAll();

	CFrameWnd::OnDestroy();
}

LRESULT CChatWnd::OnEnterSizeMove(WPARAM wParam, LPARAM lParam)
{
	int cyMin;
	CRect rect;

	m_wndSplitter.GetRowInfo(0, m_nDisplayViewHeight, cyMin);

	GetClientRect(rect);
	m_nClientHeight = rect.bottom;

	return 0;
}

void CChatWnd::OnSize(UINT nType, int cx, int cy)
{
	if (m_nClientHeight != 0) {
		if (cy != m_nClientHeight) {
			int nDisplayViewHeight;

			nDisplayViewHeight = m_nDisplayViewHeight + (cy - m_nClientHeight);
			if (nDisplayViewHeight < 0)
				nDisplayViewHeight = 0;

			m_wndSplitter.SetRowInfo(0, nDisplayViewHeight, 0);
		}
	}

	CFrameWnd::OnSize(nType, cx, cy);
}

void CChatWnd::OnExit()
{
	OnClose();
}

void CChatWnd::OnFont()
{
	LOGFONT	lf;
	char	szStyle[LF_FACESIZE];

	memset(&lf, 0, sizeof(lf));

	CFontDialog	fontDialog(&lf, 0, NULL, this);
	fontDialog.m_cf.lStructSize = sizeof(CHOOSEFONT);
	fontDialog.m_cf.hwndOwner = m_hWnd;
	fontDialog.m_cf.lpLogFont = &lf;
	fontDialog.m_cf.lpszStyle = szStyle;
	fontDialog.m_cf.Flags |= CF_INITTOLOGFONTSTRUCT | CF_SCREENFONTS;

	lstrcpy(fontDialog.m_cf.lpLogFont->lfFaceName, iniData.chatWindow.font);
	fontDialog.m_cf.lpLogFont->lfHeight = -iniData.chatWindow.fontSize;

	if (fontDialog.DoModal()) {
		m_font.DeleteObject();
		m_font.CreateFontIndirect(&lf);

		lstrcpy(iniData.chatWindow.font, fontDialog.m_cf.lpLogFont->lfFaceName);
		iniData.chatWindow.fontSize = -fontDialog.m_cf.lpLogFont->lfHeight;

		m_pDisplayView->SetFont(&m_font);
		m_pInputView->SetFont(&m_font);
	}
}

void CChatWnd::OnFileSave() 
{
	CFileDialog fileDlg(FALSE, "txt", NULL,
			OFN_PATHMUSTEXIST | OFN_HIDEREADONLY | OFN_OVERWRITEPROMPT | OFN_NOCHANGEDIR,
			GetString(IDS_CHAT_FILE), this);

	CString title;
	title.LoadString(IDS_SAVE_CHAT);
	fileDlg.m_ofn.lpstrTitle = title;

	if (fileDlg.DoModal() != IDCANCEL) {
		int len = m_pDisplayView->GetWindowTextLength();
		char *bufp = new char[len + 1];
		m_pDisplayView->GetWindowText(bufp, len + 1);

		CFile file(fileDlg.GetPathName(), CFile::modeCreate | CFile::modeWrite);
		file.Write(bufp, len);
		file.Close();

		delete [] bufp;
	}
}

void CChatWnd::OnUpdateFileSave(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_pDisplayView->GetWindowTextLength() != 0);
}

void CChatWnd::OnLogFile()
{
	CLogFileDlg(this).DoModal();
}

void CChatWnd::OnChatCall()
{
	OpenCallWindow(this, FLAG_CHAT);
}

void	CChatWnd::OpenLogFile()
{
	SYSTEMTIME st;

	if (iniData.chatWindow.logFile[0] != '\0') {
		if ((m_fpLog = fopen(iniData.chatWindow.logFile,
				iniData.chatWindow.bAppend ? "a" : "w")) != NULL) {
			
			GetLocalTime(&st);

			fprintf(m_fpLog,
		"----- PC-VIDEO Chat Log %4u/%02u/%02u %2u:%02u:%02u -----\n",
				st.wYear, st.wMonth, st.wDay,
				st.wHour, st.wMinute, st.wSecond);
			fflush(m_fpLog);
		}
	}
}

void	CChatWnd::CloseLogFile()
{
	if (m_fpLog != NULL) {
		fclose(m_fpLog);
		m_fpLog = NULL;
	}
}

void	CChatWnd::DisplayStr(char *userName, char *str, COLORREF color)
{
	m_pDisplayView->PutStr(userName, str, color);

	if (m_fpLog != NULL) {
		fprintf(m_fpLog, "[%s]%s\n", (LPSTR)userName, (LPSTR)str);
		fflush(m_fpLog);
	}
}

/////////////////////////////////////////////////////////////////////////////
// CLogFileDlg ダイアログ

CLogFileDlg::CLogFileDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CLogFileDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CLogFileDlg)
		// 注意: ClassWizard はこの位置にメンバの初期化処理を追加します。
	//}}AFX_DATA_INIT
}

void CLogFileDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CLogFileDlg)
		// 注意: ClassWizard はこの位置に DDX と DDV の呼び出しコードを追加します。
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CLogFileDlg, CDialog)
	//{{AFX_MSG_MAP(CLogFileDlg)
	ON_BN_CLICKED(IDC_REFERENCE, OnClickedReference)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLogFileDlg メッセージハンドラ

BOOL CLogFileDlg::OnInitDialog()
{
	CDialog::OnInitDialog();
	
	SetDlgItemText(IDC_LOGFILE, iniData.chatWindow.logFile);

	if (iniData.chatWindow.bAppend) {
		SendDlgItemMessage(IDC_APPEND, BM_SETCHECK, 1, 0);
		SendDlgItemMessage(IDC_REPLACE, BM_SETCHECK, 0, 0);
	} else {
		SendDlgItemMessage(IDC_APPEND, BM_SETCHECK, 0, 0);
		SendDlgItemMessage(IDC_REPLACE, BM_SETCHECK, 1, 0);
	}

	SendDlgItemMessage(IDC_ALWAYSLOG, BM_SETCHECK,
				iniData.chatWindow.bAlwaysLog ? 1 : 0, 0);
	
	return TRUE;
}

void CLogFileDlg::OnOK()
{
	GetDlgItemText(IDC_LOGFILE, iniData.chatWindow.logFile,
					sizeof(iniData.chatWindow.logFile));
	iniData.chatWindow.bAppend = (BOOL)SendDlgItemMessage(
					IDC_APPEND, BM_GETCHECK, 0, 0);
	iniData.chatWindow.bAlwaysLog = (BOOL)SendDlgItemMessage(
					IDC_ALWAYSLOG, BM_GETCHECK, 0, 0);

	pChatWnd->CloseLogFile();
	pChatWnd->OpenLogFile();
	WriteIniFile();
	EndDialog(TRUE);
}

void CLogFileDlg::OnCancel()
{
	EndDialog(FALSE);
}

void CLogFileDlg::OnClickedReference()
{
	CFileDialog fileDlg(FALSE, "txt", NULL,
			OFN_PATHMUSTEXIST | OFN_HIDEREADONLY | OFN_OVERWRITEPROMPT | OFN_NOCHANGEDIR,
			GetString(IDS_CHAT_FILE), this);

	CString title;
	title.LoadString(IDS_REF_LOGFILE);
	fileDlg.m_ofn.lpstrTitle = title;

	if (fileDlg.DoModal() != IDCANCEL)
		SetDlgItemText(IDC_LOGFILE, fileDlg.GetPathName());
}

void	ReceiveChat(PUSER pUser, char *bufp, int len)
{
	char	*p;
	int	beepCount;

	if (len <= 0)
		return;

	bufp[len] = '\0';

	if ((p = strstr(bufp, ">>")) != NULL) {
		beepCount = 0;
		while (*++p == '>')
			beepCount++;
		while ((p = stristr(p, iniData.settings.userName)) != NULL) {
			if (!isalnum(*(p - 1)) &&
					!isalnum(*(p +
					strlen(iniData.settings.userName)))) {
				if (pChatWnd == NULL)
					OpenChatWindow();
				pChatWnd->ShowWindow(SW_RESTORE);
				while (--beepCount >= 0)
					MessageBeep(0);
				pChatWnd->m_pInputView->SetFocus();
				break;
			}
			p += strlen(iniData.settings.userName);
		}
	}

	if (pChatWnd != NULL)
		pChatWnd->DisplayStr(pUser->userName, bufp, RGB(0, 0, 255));
}
/////////////////////////////////////////////////////////////////////////////
// CChatDispView

IMPLEMENT_DYNCREATE(CChatDispView, CRichEditView)

CChatDispView::CChatDispView()
{
}

CChatDispView::~CChatDispView()
{
}


BEGIN_MESSAGE_MAP(CChatDispView, CRichEditView)
	//{{AFX_MSG_MAP(CChatDispView)
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CChatDispView 診断

#ifdef _DEBUG
void CChatDispView::AssertValid() const
{
	CRichEditView::AssertValid();
}

void CChatDispView::Dump(CDumpContext& dc) const
{
	CRichEditView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CChatDispView メッセージ ハンドラ

int CChatDispView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CRichEditView::OnCreate(lpCreateStruct) == -1)
		return -1;
	
	GetRichEditCtrl().SetReadOnly();
	
	return 0;
}

void CChatDispView::PutStr(LPCTSTR pUserName, LPCTSTR pMessage, COLORREF color)
{
	int	len;
	CHARFORMAT cf;
/*
	int	n;

	while ((len = GetWindowTextLength()) > 16384) {
		SetRedraw(FALSE);
		n = GetEditCtrl().LineLength(0) + 2;
		GetEditCtrl().SetSel(1, n);
		GetEditCtrl().ReplaceSel("");
		SetRedraw(TRUE);
	}
*/
	len = GetWindowTextLength();

	GetRichEditCtrl().SetSel(len, len);

	if (len != 0)
		GetRichEditCtrl().ReplaceSel("\r\n");

	memset(&cf, 0, sizeof(cf));
	cf.cbSize = sizeof(cf);
	cf.dwMask = CFM_BOLD | CFM_COLOR;
	cf.dwEffects = CFE_BOLD;
	cf.crTextColor = color;
	SetCharFormat(cf);
	GetRichEditCtrl().ReplaceSel("[");
	GetRichEditCtrl().ReplaceSel(pUserName);
	GetRichEditCtrl().ReplaceSel("] ");

	cf.dwEffects = CFE_AUTOCOLOR;
	SetCharFormat(cf);
	GetRichEditCtrl().ReplaceSel(pMessage);
}

/////////////////////////////////////////////////////////////////////////////
// CChatInputView

IMPLEMENT_DYNCREATE(CChatInputView, CEditView)

CChatInputView::CChatInputView()
{
}

CChatInputView::~CChatInputView()
{
}


BEGIN_MESSAGE_MAP(CChatInputView, CEditView)
	//{{AFX_MSG_MAP(CChatInputView)
	ON_CONTROL_REFLECT(EN_CHANGE, OnChange)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CChatInputView 診断

#ifdef _DEBUG
void CChatInputView::AssertValid() const
{
	CEditView::AssertValid();
}

void CChatInputView::Dump(CDumpContext& dc) const
{
	CEditView::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CChatInputView メッセージ ハンドラ

BOOL CChatInputView::PreCreateWindow(CREATESTRUCT& cs) 
{
	if (!CEditView::PreCreateWindow(cs))
		return FALSE;

	cs.style &= ~(WS_HSCROLL | WS_VSCROLL);

	return TRUE;
}

void CChatInputView::OnChange() 
{
	PHEADER	pHeader;
	PSEND	pSend;
	char	buf[1024], *bufp;
	int	len;

	bufp = buf + sizeof(HEADER);
	len = GetWindowText(bufp, sizeof(buf) - sizeof(HEADER));

	if (len == 0 || bufp[--len] != 0x0a)
		return;

	if (len == 0 || bufp[--len] != 0x0d)
		return;

	SetWindowText("");

	if (len == 0)
		return;

	bufp[len] = '\0';

	pChatWnd->DisplayStr(iniData.settings.userName, bufp, RGB(255, 0, 0));

	pHeader = (PHEADER)buf;

	pHeader->flag = FLAG_CHAT;
	pHeader->id = localIPAddress;

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain)
		SendData(&pSend->sock, buf, sizeof(HEADER) + len);
}
