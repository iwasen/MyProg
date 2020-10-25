// TransRegDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "TransRegDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CTransRegDlg ダイアログ


CTransRegDlg::CTransRegDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CTransRegDlg::IDD, pParent)
{
	m_sAppFile = _T("");
	m_sAppName = _T("");
	m_iMenuIcon = -1;
	m_bPictBMP = TRUE;
	m_bPictGIF = FALSE;
	m_bPictJPG = TRUE;
	m_bPictPNG = TRUE;
}


void CTransRegDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_APP_LIST, m_cAppList);
	DDX_Text(pDX, IDC_APP_FILE, m_sAppFile);
	DDX_Text(pDX, IDC_APP_NAME, m_sAppName);
	DDX_Radio(pDX, IDC_ICON_NONE, m_iMenuIcon);
	DDX_Check(pDX, IDC_PICT_BMP, m_bPictBMP);
	DDX_Check(pDX, IDC_PICT_GIF, m_bPictGIF);
	DDX_Check(pDX, IDC_PICT_JPG, m_bPictJPG);
	DDX_Check(pDX, IDC_PICT_PNG, m_bPictPNG);
}


BEGIN_MESSAGE_MAP(CTransRegDlg, CDialog)
	ON_BN_CLICKED(IDC_DELETE, OnDelete)
	ON_BN_CLICKED(IDC_CLOSE, OnClose)
	ON_BN_CLICKED(IDC_REFERENCE, OnReference)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_APP_LIST, OnItemchangedAppList)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CTransRegDlg メッセージ ハンドラ

BOOL CTransRegDlg::OnInitDialog() 
{
	CWinApp *pWinApp = AfxGetApp();
	m_iMenuIcon = pWinApp->GetProfileInt(g_SectionTransfer, "MenuIcon", 1);

	CDialog::OnInitDialog();

	m_cAppList.SetExtendedStyle(LVS_EX_FULLROWSELECT);

	m_cAppList.InsertColumn(0, GetString(IDS_APPLICATION_NAME), LVCFMT_LEFT, 100);
	m_cAppList.InsertColumn(1, GetString(IDS_EXECUTABLE_FILE), LVCFMT_LEFT, 300);

	SetAppList();

	return TRUE;
}

void CTransRegDlg::OnOK() 
{
	int i, n;
	int nSupportFile;

	UpdateData(TRUE);

	if (m_sAppName.IsEmpty()) {
		AfxMessageBox(IDS_INPUT_APPLICATION_NAME);
		return;
	}

	if (m_sAppFile.IsEmpty()) {
		AfxMessageBox(IDS_INPUT_EXECUTABLE_FILE);
		return;
	}

	n = m_cAppList.GetItemCount();
	for (i = 0; i < n; i++) {
		if (m_cAppList.GetItemText(i, 0) == m_sAppName)
			break;
	}
	if (i == n)
		m_cAppList.InsertItem(i, m_sAppName);
	m_cAppList.SetItemText(i, 1, m_sAppFile);

	nSupportFile = 0;
	if (m_bPictBMP)
		nSupportFile |= PICTFILE_BMP;
	if (m_bPictGIF)
		nSupportFile |= PICTFILE_GIF;
	if (m_bPictJPG)
		nSupportFile |= PICTFILE_JPG;
	if (m_bPictPNG)
		nSupportFile |= PICTFILE_PNG;
	m_cAppList.SetItemData(i, nSupportFile);
}

void CTransRegDlg::OnDelete() 
{
	int i, nItem;

	if (m_cAppList.GetSelectedCount() == 0) {
		AfxMessageBox(IDS_SELECT_DELETE_ITEM);
		return;
	}

	if (AfxMessageBox(IDS_DELETE_SELECT_ITEM, MB_OKCANCEL + MB_ICONQUESTION) == IDCANCEL)
		return;

	nItem = m_cAppList.GetItemCount();
	for (i = nItem - 1; i >= 0; i--) {
		if (m_cAppList.GetItemState(i, LVIS_SELECTED) != 0)
			m_cAppList.DeleteItem(i);
	}
}

void CTransRegDlg::OnClose() 
{
	CWinApp *pWinApp = AfxGetApp();
	int nItem;
	int i;
	CString entry;

	UpdateData(TRUE);

	nItem = m_cAppList.GetItemCount();

	pWinApp->WriteProfileInt(g_SectionTransfer, "Num", nItem);

	for (i = 0; i < nItem; i++) {
		entry.Format("AppName%d", i);
		pWinApp->WriteProfileString(g_SectionTransfer, entry, m_cAppList.GetItemText(i, 0));

		entry.Format("AppFile%d", i);
		pWinApp->WriteProfileString(g_SectionTransfer, entry, m_cAppList.GetItemText(i, 1));

		entry.Format("SupportFile%d", i);
		pWinApp->WriteProfileInt(g_SectionTransfer, entry, (int)m_cAppList.GetItemData(i));
	}

	pWinApp->WriteProfileInt(g_SectionTransfer, "MenuIcon", m_iMenuIcon);

	EndDialog(IDOK);
}

void CTransRegDlg::OnReference() 
{
	CFileDialog dlg(TRUE, "*.exe", NULL,
			OFN_HIDEREADONLY,
			GetString(IDS_PROGRAM) + " (*.exe)|*.exe|" + GetString(IDS_ALL_FILES, 1) + " (*.*)|*.*||", AfxGetMainWnd());

	dlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_TRANSFER_APPL);
	if (dlg.DoModal() == IDOK) {
		UpdateData(TRUE);
		m_sAppFile = dlg.GetPathName();
		UpdateData(FALSE);
	}
}

void CTransRegDlg::SetAppList()
{
	CWinApp *pWinApp = AfxGetApp();
	int n;
	int i;
	CString entry;
	CString appName, appFile;
	int nSupportFile;

	n = pWinApp->GetProfileInt(g_SectionTransfer, "Num", 0);
	for (i = 0; i < n; i++) {
		entry.Format("AppName%d", i);
		appName = pWinApp->GetProfileString(g_SectionTransfer, entry);

		entry.Format("AppFile%d", i);
		appFile = pWinApp->GetProfileString(g_SectionTransfer, entry);

		entry.Format("SupportFile%d", i);
		nSupportFile = pWinApp->GetProfileInt(g_SectionTransfer, entry, PICTFILE_TRANSDEF);

		m_cAppList.InsertItem(i, appName);
		m_cAppList.SetItemText(i, 1, appFile);
		m_cAppList.SetItemData(i, nSupportFile);
	}
}

void CTransRegDlg::OnItemchangedAppList(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;
	int nSupportFile;

	if (pNMListView->uNewState & LVIS_SELECTED) {
		m_sAppName = m_cAppList.GetItemText(pNMListView->iItem, 0);
		m_sAppFile = m_cAppList.GetItemText(pNMListView->iItem, 1);
		nSupportFile = (int)m_cAppList.GetItemData(pNMListView->iItem);
		m_bPictBMP = ((nSupportFile & PICTFILE_BMP) != 0);
//		m_bPictGIF = ((nSupportFile & PICTFILE_GIF) != 0);
		m_bPictJPG = ((nSupportFile & PICTFILE_JPG) != 0);
		m_bPictPNG = ((nSupportFile & PICTFILE_PNG) != 0);
		UpdateData(FALSE);
	}

	*pResult = 0;
}
