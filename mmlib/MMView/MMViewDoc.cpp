// MMViewDoc.cpp : CMMViewDoc クラスの動作の定義を行います。
//

#include "stdafx.h"
#include "MMView.h"
#include "MainFrm.h"
#include "MMViewDoc.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMViewDoc

IMPLEMENT_DYNCREATE(CMMViewDoc, CDocument)

BEGIN_MESSAGE_MAP(CMMViewDoc, CDocument)
	ON_COMMAND(IDM_PICT_NEXT, OnPictNext)
	ON_UPDATE_COMMAND_UI(IDM_PICT_NEXT, OnUpdatePictNext)
	ON_COMMAND(IDM_PICT_PREV, OnPictPrev)
	ON_UPDATE_COMMAND_UI(IDM_PICT_PREV, OnUpdatePictPrev)
	ON_COMMAND(IDM_VIEW_LIST, OnViewList)
	ON_COMMAND(IDM_VIEW_PICT, OnViewPict)
	ON_COMMAND(IDM_VIEW_ICON, OnViewIcon)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_PICT, OnUpdateViewPict)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_ICON, OnUpdateViewIcon)
	ON_UPDATE_COMMAND_UI(IDM_VIEW_LIST, OnUpdateViewList)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT, OnUpdateFilePrint)
	ON_UPDATE_COMMAND_UI(ID_FILE_PRINT_PREVIEW, OnUpdateFilePrintPreview)
	ON_COMMAND(IDM_SETFONT, OnSetfont)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMViewDoc クラスの構築/消滅

CMMViewDoc::CMMViewDoc()
{
	m_pMMFile = NULL;
	m_nViewMode = VIEWMODE_PICT;
	m_pItem = NULL;
	m_nMaxPage = 0;
	m_nCurrentPage = 0;

	CWinApp *pWinApp = AfxGetApp();
	m_sFontName = pWinApp->GetProfileString(g_SectionSettings, "FontName", "System");
	m_nFontSize = pWinApp->GetProfileInt(g_SectionSettings, "FontSize", 90);
	m_Font.CreatePointFont(m_nFontSize, m_sFontName);
}

CMMViewDoc::~CMMViewDoc()
{
	CloseFile();
}

BOOL CMMViewDoc::OnNewDocument()
{
	if (!CDocument::OnNewDocument())
		return FALSE;

	// TODO: この位置に再初期化処理を追加してください。
	// (SDI ドキュメントはこのドキュメントを再利用します。)

	return TRUE;
}



/////////////////////////////////////////////////////////////////////////////
// CMMViewDoc シリアライゼーション

void CMMViewDoc::Serialize(CArchive& ar)
{
	if (ar.IsStoring())
	{
		// TODO: この位置に保存用のコードを追加してください。
	}
	else
	{
		// TODO: この位置に読み込み用のコードを追加してください。
	}
}

/////////////////////////////////////////////////////////////////////////////
// CMMViewDoc クラスの診断

#ifdef _DEBUG
void CMMViewDoc::AssertValid() const
{
	CDocument::AssertValid();
}

void CMMViewDoc::Dump(CDumpContext& dc) const
{
	CDocument::Dump(dc);
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMMViewDoc コマンド

BOOL CMMViewDoc::OnOpenDocument(LPCTSTR lpszPathName) 
{
	if (!CDocument::OnOpenDocument(lpszPathName))
		return FALSE;

	CloseFile();

	m_pMMFile = new CMmlRead;

	if (!m_pMMFile->Open(lpszPathName)) {
		AfxMessageBox(IDS_FILE_OPEN_FAIL);
		delete m_pMMFile;
		m_pMMFile = NULL;
		return FALSE;
	}

	if (m_pMMFile->m_nPage <= 1) {
		AfxMessageBox(IDS_FILE_NO_DATA);
		delete m_pMMFile;
		m_pMMFile = NULL;
		return FALSE;
	}

	ReadHeader();

	m_nMaxPage = m_pMMFile->m_nPage - 1;
	m_nCurrentPage = 0;

	m_pItem = new CItem[m_nMaxPage];

	ReadCurrentPage();
	UpdateAllViews(NULL);

	CreateReadThread();

	return TRUE;
}

void CMMViewDoc::CloseFile()
{
	m_nMaxPage = 0;

	m_CriticalSection1.Lock();

	if (m_pItem != NULL) {
		delete [] m_pItem;
		m_pItem = NULL;
	}

	if (m_pMMFile != NULL)
		delete m_pMMFile;

	m_CriticalSection1.Unlock();
}

void CMMViewDoc::ReadHeader()
{
	char itemName[11];
	LPVOID itemData;
	long itemLen;

	m_Title.Empty();
	m_Text.Empty();

	if (!m_pMMFile->SetPage(0))
		return;

	while (m_pMMFile->ReadItem(itemName, &itemData, &itemLen)) {
		if (strcmp(itemName, "TITLE") == 0)
			m_Title = CString((LPCTSTR)itemData, itemLen);
		else if (strcmp(itemName, "TEXT") == 0)
			m_Text = CString((LPCTSTR)itemData, itemLen);

		delete itemData;
	}

}

void CMMViewDoc::ReadCurrentPage()
{
	ReadPage(m_nCurrentPage);
	DispStatus();
	UpdateAllViews(NULL);
}

void CMMViewDoc::ReadPage(int nPage)
{
	m_CriticalSection2.Lock();
	m_pItem[nPage].ReadItem(m_pMMFile, nPage + 1);
	m_CriticalSection2.Unlock();
}

void CMMViewDoc::ReadAllPages()
{
	CWaitCursor wait;
	int i;

	for (i = 0; i < m_nMaxPage; i++) {
		ReadPage(i);
	}
}

void CMMViewDoc::DispStatus()
{
	if (m_nViewMode == VIEWMODE_PICT) {
		if (m_pItem != NULL) {
			SetStatusInfo(m_pItem[m_nCurrentPage].m_Title);
			g_PageInfo.Format("%d/%d Page", m_nCurrentPage + 1, m_nMaxPage);
		}
	} else {
		SetStatusInfo(m_Title);
		g_PageInfo.Format("%d Item(s)", m_nMaxPage);
	}
}

void CMMViewDoc::OnPictNext() 
{
	if (m_nCurrentPage < m_nMaxPage) {
		m_nCurrentPage++;
		ReadCurrentPage();
	}
}

void CMMViewDoc::OnUpdatePictNext(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_nViewMode == VIEWMODE_PICT && m_pMMFile != NULL && m_nCurrentPage < m_nMaxPage - 1);
}

void CMMViewDoc::OnPictPrev() 
{
	if (m_nCurrentPage > 0) {
		m_nCurrentPage--;
		ReadCurrentPage();
	}
}

void CMMViewDoc::OnUpdatePictPrev(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_nViewMode == VIEWMODE_PICT && m_pMMFile != NULL && m_nCurrentPage > 0);
}

void CMMViewDoc::OnViewList() 
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();

	m_nViewMode = VIEWMODE_LIST;

	ReadAllPages();
	DispStatus();

	pMainWnd->SwitchView(VIEWWND_LIST);
	UpdateAllViews(NULL);
}

void CMMViewDoc::OnUpdateViewList(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_pMMFile != NULL);
	pCmdUI->SetRadio(m_nViewMode == VIEWMODE_LIST);
}

void CMMViewDoc::OnViewPict() 
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();

	m_nViewMode = VIEWMODE_PICT;

	DispStatus();

	pMainWnd->SwitchView(VIEWWND_PICT);
	UpdateAllViews(NULL);
}

void CMMViewDoc::OnUpdateViewPict(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_pMMFile != NULL);
	pCmdUI->SetRadio(m_nViewMode == VIEWMODE_PICT);
}

void CMMViewDoc::OnViewIcon() 
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();

	m_nViewMode = VIEWMODE_ICON;

	ReadAllPages();
	DispStatus();

	pMainWnd->SwitchView(VIEWWND_PICT);
	UpdateAllViews(NULL);
}

void CMMViewDoc::OnUpdateViewIcon(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_pMMFile != NULL);
	pCmdUI->SetRadio(m_nViewMode == VIEWMODE_ICON);
}

void CMMViewDoc::ShowPicture(int nPage)
{
	CMainFrame *pMainWnd = (CMainFrame *)AfxGetMainWnd();

	m_nCurrentPage = nPage;
	m_nViewMode = VIEWMODE_PICT;
	pMainWnd->SwitchView(VIEWWND_PICT);
	UpdateAllViews(NULL);
}

void CMMViewDoc::SetStatusInfo(LPCTSTR pInfoMsg)
{
	if (pInfoMsg != NULL)
		g_StatusInfo = pInfoMsg;
	else
		g_StatusInfo.Empty();
}

UINT ReadThread(LPVOID pParam)
{
	CMMViewDoc *pDoc = (CMMViewDoc *)pParam;

	pDoc->m_CriticalSection1.Lock();
	pDoc->ReadAllPages();
	pDoc->m_CriticalSection1.Unlock();

	return 0;
}

void CMMViewDoc::CreateReadThread()
{
	CWinThread *pThread;

	pThread = AfxBeginThread(ReadThread, this, THREAD_PRIORITY_LOWEST);
}

void CMMViewDoc::OnUpdateFilePrint(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_pMMFile != NULL);
}

void CMMViewDoc::OnUpdateFilePrintPreview(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_pMMFile != NULL);
}

void CMMViewDoc::OnSetfont() 
{
	CWinApp *pWinApp = AfxGetApp();
	CWnd *pMainWnd = AfxGetMainWnd();
	LOGFONT	lf;
	char	szStyle[LF_FACESIZE];

	memset(&lf, 0, sizeof(lf));

	CFontDialog	fontDialog(&lf, 0, NULL, pMainWnd);
	fontDialog.m_cf.lStructSize = sizeof(CHOOSEFONT);
	fontDialog.m_cf.hwndOwner = pMainWnd->m_hWnd;
	fontDialog.m_cf.iPointSize = m_nFontSize;
	fontDialog.m_cf.lpLogFont = &lf;
	fontDialog.m_cf.lpszStyle = szStyle;
	fontDialog.m_cf.Flags |= CF_INITTOLOGFONTSTRUCT | CF_SCREENFONTS;

	lstrcpy(lf.lfFaceName, m_sFontName);
	lf.lfHeight = -MulDiv(m_nFontSize, pMainWnd->GetDC()->GetDeviceCaps(LOGPIXELSY), 720);
	lf.lfCharSet = SHIFTJIS_CHARSET;

	if (fontDialog.DoModal() == IDOK) {
		m_Font.DeleteObject();
		m_Font.CreateFontIndirect(&lf);
		UpdateAllViews(NULL, VIEWHINT_SETFONT, &m_Font);

		m_sFontName = fontDialog.m_cf.lpLogFont->lfFaceName;
		m_nFontSize = fontDialog.m_cf.iPointSize;
		pWinApp->WriteProfileString(g_SectionSettings, "FontName", m_sFontName);
		pWinApp->WriteProfileInt(g_SectionSettings, "FontSize", m_nFontSize);
	}
}
