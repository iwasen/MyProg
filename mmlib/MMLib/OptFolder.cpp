// OptFolder.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "OptFolder.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// COptFolder プロパティ ページ

IMPLEMENT_DYNCREATE(COptFolder, CPropertyPage)

COptFolder::COptFolder() : CPropertyPage(COptFolder::IDD)
{
	m_sBackupFolder = _T("");
	m_sCacheFolder = _T("");
	m_sLibraryFolder = _T("");
	m_sInboxFolder = _T("");
	m_sKeywordFolder = _T("");
}

COptFolder::~COptFolder()
{
}

void COptFolder::DoDataExchange(CDataExchange* pDX)
{
	CPropertyPage::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_BACKUP_FOLDER, m_sBackupFolder);
	DDX_Text(pDX, IDC_CACHE_FOLDER, m_sCacheFolder);
	DDX_Text(pDX, IDC_LIBRARY_FOLDER, m_sLibraryFolder);
	DDX_Text(pDX, IDC_INBOX_FOLDER, m_sInboxFolder);
	DDX_Text(pDX, IDC_KEYWORD_FOLDER, m_sKeywordFolder);
}


BEGIN_MESSAGE_MAP(COptFolder, CPropertyPage)
	ON_BN_CLICKED(IDC_LIBRARY_REFERENCE, OnLibraryReference)
	ON_BN_CLICKED(IDC_CACHE_REFERENCE, OnCacheReference)
	ON_BN_CLICKED(IDC_BACKUP_REFERENCE, OnBackupReference)
	ON_BN_CLICKED(IDC_DEFAULT, OnDefault)
	ON_BN_CLICKED(IDC_INBOX_REFERENCE, OnInboxReference)
	ON_BN_CLICKED(IDC_KEYWORD_REFERENCE, OnKeywordReference)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// COptFolder メッセージ ハンドラ

BOOL COptFolder::OnInitDialog() 
{
	CWinApp *pWinApp = AfxGetApp();

	::GetLibraryDir(m_sLibraryFolder);
	::GetCacheDir(m_sCacheFolder);
	::GetBackupDir(m_sBackupFolder);
	::GetInboxDir(m_sInboxFolder);
	::GetKeywordDir(m_sKeywordFolder);

	CPropertyPage::OnInitDialog();

	return TRUE;
}

void COptFolder::OnOK() 
{
	CWinApp *pWinApp = AfxGetApp();

	UpdateData(TRUE);

	pWinApp->WriteProfileString(g_SectionOptions, "LibraryFolder", m_sLibraryFolder);
	pWinApp->WriteProfileString(g_SectionOptions, "CacheFolder", m_sCacheFolder);
	pWinApp->WriteProfileString(g_SectionOptions, "BackupFolder", m_sBackupFolder);
	pWinApp->WriteProfileString(g_SectionOptions, "InboxFolder", m_sInboxFolder);
	pWinApp->WriteProfileString(g_SectionOptions, "KeywordFolder", m_sKeywordFolder);

	CPropertyPage::OnOK();
}

void COptFolder::OnLibraryReference() 
{
	CFileDialog fileDlg(TRUE, NULL, "*", OFN_NOCHANGEDIR | OFN_HIDEREADONLY | OFN_NOVALIDATE, NULL, this);

	fileDlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_LIBRARY_FOLDER);
	if (fileDlg.DoModal() == IDCANCEL)
		return;

	m_sLibraryFolder = fileDlg.GetPathName();
	int pos = m_sLibraryFolder.ReverseFind('\\');
	if (pos != -1)
		m_sLibraryFolder = m_sLibraryFolder.Mid(0, pos);

	UpdateData(FALSE);
}

void COptFolder::OnCacheReference() 
{
	CFileDialog fileDlg(TRUE, NULL, "*", OFN_NOCHANGEDIR | OFN_HIDEREADONLY | OFN_NOVALIDATE, NULL, this);

	fileDlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_CACHE_FOLDER);
	if (fileDlg.DoModal() == IDCANCEL)
		return;

	m_sCacheFolder = fileDlg.GetPathName();
	int pos = m_sCacheFolder.ReverseFind('\\');
	if (pos != -1)
		m_sCacheFolder = m_sCacheFolder.Mid(0, pos);

	UpdateData(FALSE);
}

void COptFolder::OnBackupReference() 
{
	CFileDialog fileDlg(TRUE, NULL, "*", OFN_NOCHANGEDIR | OFN_HIDEREADONLY | OFN_NOVALIDATE, NULL, this);

	fileDlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_BACKUP_FOLDER);
	if (fileDlg.DoModal() == IDCANCEL)
		return;

	m_sBackupFolder = fileDlg.GetPathName();
	int pos = m_sBackupFolder.ReverseFind('\\');
	if (pos != -1)
		m_sBackupFolder = m_sBackupFolder.Mid(0, pos);

	UpdateData(FALSE);
}

void COptFolder::OnDefault() 
{
	::GetDefaultLibraryDir(m_sLibraryFolder);
	::GetDefaultCacheDir(m_sCacheFolder);
	::GetDefaultBackupDir(m_sBackupFolder);
	::GetDefaultInboxDir(m_sInboxFolder);
	::GetDefaultKeywordDir(m_sKeywordFolder);

	UpdateData(FALSE);
}

void COptFolder::OnInboxReference() 
{
	CFileDialog fileDlg(TRUE, NULL, "*", OFN_NOCHANGEDIR | OFN_HIDEREADONLY | OFN_NOVALIDATE, NULL, this);

	fileDlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_INBOX_FOLDER);
	if (fileDlg.DoModal() == IDCANCEL)
		return;

	m_sInboxFolder = fileDlg.GetPathName();
	int pos = m_sInboxFolder.ReverseFind('\\');
	if (pos != -1)
		m_sInboxFolder = m_sInboxFolder.Mid(0, pos);

	UpdateData(FALSE);
}

void COptFolder::OnKeywordReference() 
{
	CFileDialog fileDlg(TRUE, NULL, "*", OFN_NOCHANGEDIR | OFN_HIDEREADONLY | OFN_NOVALIDATE, NULL, this);

	fileDlg.m_ofn.lpstrTitle = GetString(IDS_SELECT_KEYWORD_FOLDER);
	if (fileDlg.DoModal() == IDCANCEL)
		return;

	m_sKeywordFolder = fileDlg.GetPathName();
	int pos = m_sKeywordFolder.ReverseFind('\\');
	if (pos != -1)
		m_sKeywordFolder = m_sKeywordFolder.Mid(0, pos);

	UpdateData(FALSE);
}
