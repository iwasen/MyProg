// LibOpenDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "LibOpenDlg.h"
#include "RefNetDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CLibOpenDlg ダイアログ


CLibOpenDlg::CLibOpenDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CLibOpenDlg::IDD, pParent)
{
	m_sDirectory = _T("");
	m_iChType = -1;
	m_sServerName = _T("");
	m_bCache = FALSE;
}


void CLibOpenDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIBRARY_LIST, m_cLibraryList);
	DDX_Control(pDX, IDC_EXTERNAL, m_cExternal);
	DDX_Control(pDX, IDC_SEPARATOR, m_cSeparator);
	DDX_Control(pDX, IDC_DIRECTORY, m_cDirectory);
	DDX_Control(pDX, IDC_SERVER_NAME, m_cServerName);
	DDX_Control(pDX, IDC_CACHE, m_cCache);
	DDX_Text(pDX, IDC_DIRECTORY, m_sDirectory);
	DDX_Radio(pDX, IDC_LOCAL, m_iChType);
	DDX_Text(pDX, IDC_SERVER_NAME, m_sServerName);
	DDX_Check(pDX, IDC_CACHE, m_bCache);
}


BEGIN_MESSAGE_MAP(CLibOpenDlg, CDialog)
	ON_BN_CLICKED(IDC_TCPIP, OnTcpip)
	ON_BN_CLICKED(IDC_NETBIOS, OnNetbios)
	ON_BN_CLICKED(IDC_LOCAL, OnLocal)
	ON_BN_CLICKED(IDC_REFERENCE, OnReference)
	ON_BN_CLICKED(IDC_EXTERNAL, OnExternal)
	ON_LBN_SELCHANGE(IDC_LIBRARY_LIST, OnSelchangeLibraryList)
	ON_WM_DESTROY()
	ON_LBN_DBLCLK(IDC_LIBRARY_LIST, OnDblclkLibraryList)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CLibOpenDlg メッセージ ハンドラ

BOOL CLibOpenDlg::OnInitDialog() 
{
	CWinApp *pWinApp = AfxGetApp();
	CString entry;
	CString value;
	int i;

	m_iChType = 0;

	CDialog::OnInitDialog();

	CRect rectSeparator;
	GetWindowRect(m_rectDlg);
	m_cSeparator.GetWindowRect(rectSeparator);
	SetWindowPos(NULL, 0, 0, m_rectDlg.Width(), rectSeparator.top - m_rectDlg.top, SWP_NOZORDER | SWP_NOMOVE);

	SetLibraryList();

	for (i = 0; i < MAX_RECENT; i++) {
		entry.Format("Server%d", i);
		value = pWinApp->GetProfileString(g_SectionRecent, entry);
		if (value.IsEmpty())
			break;
		m_cServerName.AddString(value);
	}

	for (i = 0; i < MAX_RECENT; i++) {
		entry.Format("Directory%d", i);
		value = pWinApp->GetProfileString(g_SectionRecent, entry);
		if (value.IsEmpty())
			break;
		m_cDirectory.AddString(value);
	}

	OnLocal();

	return TRUE;
}

void CLibOpenDlg::OnLocal() 
{
	m_cServerName.EnableWindow(FALSE);
	m_cCache.SetCheck(0);
	m_cCache.EnableWindow(FALSE);
}

void CLibOpenDlg::OnTcpip() 
{
	m_cServerName.EnableWindow(TRUE);
	m_cCache.EnableWindow(TRUE);
}

void CLibOpenDlg::OnNetbios() 
{
	m_cServerName.EnableWindow(TRUE);
	m_cCache.EnableWindow(TRUE);
}

void CLibOpenDlg::OnOK() 
{
	CWinApp *pWinApp = AfxGetApp();
	int nCount;
	CString entry;
	CString value;
	int i, n;

	UpdateData(TRUE);

	n = 0;
	if (!m_sServerName.IsEmpty()) {
		pWinApp->WriteProfileString(g_SectionRecent, "Server0", m_sServerName);
		n++;
	}
	nCount = m_cServerName.GetCount();
	for (i = 0; i < nCount && n < MAX_RECENT; i++) {
		m_cServerName.GetLBText(i, value);
		if (value.CompareNoCase(m_sServerName) != 0) {
			entry.Format("Server%d", n++);
			pWinApp->WriteProfileString(g_SectionRecent, entry, value);
		}
	}

	n = 0;
	if (!m_sDirectory.IsEmpty()) {
		pWinApp->WriteProfileString(g_SectionRecent, "Directory0", m_sDirectory);
		n++;
	}
	nCount = m_cDirectory.GetCount();
	for (i = 0; i < nCount && n < MAX_RECENT; i++) {
		m_cDirectory.GetLBText(i, value);
		if (value.CompareNoCase(m_sDirectory) != 0) {
			entry.Format("Directory%d", n++);
			pWinApp->WriteProfileString(g_SectionRecent, entry, value);
		}
	}

	CDialog::OnOK();
}

void CLibOpenDlg::OnReference() 
{
	UpdateData(TRUE);

	switch (m_iChType) {
	case CHT_LOCAL:
		ReferenceLocal();
		break;
	case CHT_TCPIP:
	case CHT_NETBIOS:
		ReferenceNetwork();
		break;
	}
}

void CLibOpenDlg::ReferenceLocal()
{
	CFileDialog fileDlg(TRUE, NULL, "*", OFN_NOCHANGEDIR | OFN_HIDEREADONLY | OFN_NOVALIDATE, NULL, this);

	fileDlg.m_ofn.lpstrTitle = GetString(IDS_MMLIB_FOLDER);
	if (fileDlg.DoModal() == IDOK) {
		m_sDirectory = fileDlg.GetPathName();
		int pos = m_sDirectory.ReverseFind('\\');
		if (pos != -1)
			m_sDirectory = m_sDirectory.Mid(0, pos);

		UpdateData(FALSE);
	}
}

void CLibOpenDlg::ReferenceNetwork()
{
	CRefNetDlg dlg;

	UpdateData(TRUE);

	if (m_sServerName.IsEmpty()) {
		AfxMessageBox(IDS_INPUT_SERVER_NAME);
		return;
	}

	dlg.m_sServerName = m_sServerName;
	dlg.m_nChType = m_iChType;
	if (dlg.DoModal() == IDOK) {
		m_sDirectory = dlg.m_sDirectory;
		UpdateData(FALSE);
	}
}

void CLibOpenDlg::OnExternal() 
{
	SetWindowPos(NULL, 0, 0, m_rectDlg.Width(), m_rectDlg.Height(), SWP_NOZORDER | SWP_NOMOVE);
	m_cExternal.EnableWindow(FALSE);
}

void CLibOpenDlg::SetLibraryList()
{
	CString dir;
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	int	nIndex;
	CString libraryName;
	CString libraryPath;
	CString *pLibraryPath;

	::GetLibraryDir(dir);

	hdir = FindFirstFile(dir + "\\*", &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			if (findBuf.dwFileAttributes && FILE_ATTRIBUTE_DIRECTORY) {
				 libraryPath = dir + "\\" + findBuf.cFileName;
				if (g_pMMLibDB->GetLibraryName(libraryPath, libraryName)) {
					nIndex = m_cLibraryList.AddString(libraryName);
					pLibraryPath = new CString;
					*pLibraryPath = libraryPath;
					m_cLibraryList.SetItemDataPtr(nIndex, pLibraryPath);
				}
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}
}

void CLibOpenDlg::OnSelchangeLibraryList() 
{
	int nIndex;
	CString *pLibraryPath;

	if ((nIndex = m_cLibraryList.GetCurSel()) == LB_ERR)
		return;

	m_iChType = 0;
	m_sServerName.Empty();
	pLibraryPath = (CString *)m_cLibraryList.GetItemDataPtr(nIndex);
	m_sDirectory = *pLibraryPath;
	m_bCache = FALSE;

	UpdateData(FALSE);

	OnLocal();
}

void CLibOpenDlg::OnDestroy() 
{
	CDialog::OnDestroy();

	CString *pLibraryPath;

	while (m_cLibraryList.GetCount() > 0) {
		pLibraryPath = (CString *)m_cLibraryList.GetItemDataPtr(0);
		delete pLibraryPath;
		m_cLibraryList.DeleteString(0);
	}	
}

void CLibOpenDlg::OnDblclkLibraryList() 
{
	OnOK();
}
