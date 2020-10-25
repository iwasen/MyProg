// AutoRecDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "RTA.h"
#include "AutoRecDlg.h"
#include <shlobj.h>


// CAutoRecDlg ダイアログ

IMPLEMENT_DYNAMIC(CAutoRecDlg, CDialog)

CAutoRecDlg::CAutoRecDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CAutoRecDlg::IDD, pParent)
{

}

CAutoRecDlg::~CAutoRecDlg()
{
}

void CAutoRecDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_SAVE_FOLDER, m_cEditSaveFolder);
	DDX_Control(pDX, IDC_CHECK_INPUT_DATA, m_cCheckInputData);
	DDX_Control(pDX, IDC_CHECK_OUTPUT_DATA, m_cCheckOutputData);
}


BEGIN_MESSAGE_MAP(CAutoRecDlg, CDialog)
	ON_BN_CLICKED(IDC_BUTTON_REFERENCE, &CAutoRecDlg::OnBnClickedButtonReference)
END_MESSAGE_MAP()


// CAutoRecDlg メッセージ ハンドラ

BOOL CAutoRecDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cEditSaveFolder = g_oSetData2.sAutoRecFolder;
	m_cCheckInputData = g_oSetData2.bAutoRecInput;
	m_cCheckOutputData = g_oSetData2.bAutoRecOutput;

	return TRUE;
}

void CAutoRecDlg::OnOK()
{
	if (m_cCheckInputData || m_cCheckOutputData) {
		if (m_cEditSaveFolder.IsEmpty()) {
			AfxMessageBox(IDS_NO_AUTO_REC_FOLDER, MB_ICONEXCLAMATION);
			return;
		}
	}

	g_oSetData2.sAutoRecFolder = m_cEditSaveFolder;
	g_oSetData2.bAutoRecInput = m_cCheckInputData;
	g_oSetData2.bAutoRecOutput = m_cCheckOutputData;

	CDialog::OnOK();
}

void CAutoRecDlg::OnBnClickedButtonReference()
{
	char dst_file[MAX_PATH];
	BROWSEINFO binfo;
	LPITEMIDLIST idlist;
	CString sTitle;

	sTitle.LoadString(IDS_SAVE_FOLDER);
	strcpy_s(dst_file, g_oSetData2.sAutoRecFolder);
	binfo.hwndOwner = m_hWnd;
	binfo.pidlRoot = NULL;
	binfo.pszDisplayName = dst_file;
	binfo.lpszTitle = sTitle;
	binfo.ulFlags = BIF_RETURNONLYFSDIRS | BIF_USENEWUI; 
	binfo.lpfn = &BrowseCallbackProc;
	binfo.lParam = (LPARAM)dst_file;
	binfo.iImage = (int)NULL;
	idlist = ::SHBrowseForFolder(&binfo);
	if (idlist) {
		::SHGetPathFromIDList(idlist, dst_file);
		::CoTaskMemFree(idlist);
		m_cEditSaveFolder = dst_file;
	}
}

int CALLBACK CAutoRecDlg::BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM /*lParam*/, LPARAM lpData)
{
	if (uMsg == BFFM_INITIALIZED)
		::SendMessage(hwnd, BFFM_SETSELECTION, (WPARAM)TRUE, lpData);

	return 0;
}
