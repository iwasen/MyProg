// FolderDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "FolderDlg.h"
#include "TextDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CFolderDlg ダイアログ


CFolderDlg::CFolderDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CFolderDlg::IDD, pParent)
{
	m_sCreator = _T("");
	m_sFolderName = _T("");
	m_sRemark = _T("");
	m_sKeyword = _T("");
}


void CFolderDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COPY_DATA, m_cCopyData);
	DDX_Text(pDX, IDC_CREATOR, m_sCreator);
	DDX_Text(pDX, IDC_FOLDERNAME, m_sFolderName);
	DDX_Text(pDX, IDC_REMARK, m_sRemark);
	DDX_Text(pDX, IDC_KEYWORD, m_sKeyword);
}


BEGIN_MESSAGE_MAP(CFolderDlg, CDialog)
	ON_BN_CLICKED(IDC_TEXT_DATA, OnTextData)
	ON_BN_CLICKED(IDC_COPY_DATA, OnCopyData)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFolderDlg メッセージ ハンドラ

BOOL CFolderDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	SetWindowText(m_Caption);

	if (m_FolderRec.folderName.IsEmpty())
		m_cCopyData.EnableWindow(FALSE);

	return TRUE;
}

void CFolderDlg::OnTextData() 
{
	CTextDlg dlg;

	dlg.m_sText = m_sTextData;
	dlg.DoModal();
	m_sTextData = dlg.m_sText;
}

void CFolderDlg::OnCopyData() 
{
	m_sCreator = m_FolderRec.creator;
	m_sFolderName = m_FolderRec.folderName;
	m_sRemark = m_FolderRec.remark;
	m_sKeyword = m_FolderRec.keyword;

	UpdateData(FALSE);
}

/////////////////////////////////////////////////////////////////////////////
// CFolderNewDlg ダイアログ


CFolderNewDlg::CFolderNewDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CFolderNewDlg::IDD, pParent)
{
	m_sCreator = _T("");
	m_sFolderName = _T("");
	m_iFolderType = -1;
	m_sRemark = _T("");
	m_sKeyword = _T("");
}


void CFolderNewDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COPY_DATA, m_cCopyData);
	DDX_Text(pDX, IDC_CREATOR, m_sCreator);
	DDX_Text(pDX, IDC_FOLDERNAME, m_sFolderName);
	DDX_Radio(pDX, IDC_GROUP_FOLDER, m_iFolderType);
	DDX_Text(pDX, IDC_REMARK, m_sRemark);
	DDX_Text(pDX, IDC_KEYWORD, m_sKeyword);
}


BEGIN_MESSAGE_MAP(CFolderNewDlg, CDialog)
	ON_BN_CLICKED(IDC_TEXT_DATA, OnTextData)
	ON_BN_CLICKED(IDC_COPY_DATA, OnCopyData)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CFolderNewDlg メッセージ ハンドラ

BOOL CFolderNewDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	if (m_FolderRec.folderName.IsEmpty())
		m_cCopyData.EnableWindow(FALSE);
	
	return TRUE;
}

void CFolderNewDlg::OnOK() 
{
	UpdateData(TRUE);

	if (m_iFolderType == -1) {
		AfxMessageBox(IDS_SELECT_FOLDER_TYPE);
		return;
	}

	CDialog::OnOK();
}

void CFolderNewDlg::OnTextData() 
{
	CTextDlg dlg;

	dlg.m_sText = m_sTextData;
	dlg.DoModal();
	m_sTextData = dlg.m_sText;
}

void CFolderNewDlg::OnCopyData() 
{
	m_sCreator = m_FolderRec.creator;
	m_sFolderName = m_FolderRec.folderName;
	m_sRemark = m_FolderRec.remark;
	m_sKeyword = m_FolderRec.keyword;

	UpdateData(FALSE);
}
