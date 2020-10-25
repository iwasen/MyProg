// SetFile.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "Capture.h"
#include "SetFile.h"
#include "IniFile.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CSetFile ダイアログ


CSetFile::CSetFile(CWnd* pParent /*=NULL*/)
	: CDialog(CSetFile::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetFile)
	m_OutputFile = _T("");
	m_OutputRate = 0;
	m_OutputFile2 = _T("");
	//}}AFX_DATA_INIT
}


void CSetFile::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetFile)
	DDX_Text(pDX, IDC_OUTPUT_FILE, m_OutputFile);
	DDX_Text(pDX, IDC_OUTPUT_RATE, m_OutputRate);
	DDX_Text(pDX, IDC_OUTPUT_FILE2, m_OutputFile2);
	//}}AFX_DATA_MAP
//	DDX_Control(pDX, IDC_LOW_QUALITY, m_LowQuality);
}


BEGIN_MESSAGE_MAP(CSetFile, CDialog)
	//{{AFX_MSG_MAP(CSetFile)
	ON_BN_CLICKED(IDC_SELECT_FILE, OnSelectFile)
	ON_BN_CLICKED(IDC_SELECT_FILE2, OnSelectFile2)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSetFile メッセージ ハンドラ

BOOL CSetFile::OnInitDialog() 
{
	m_OutputFile = iniData.m_OutputFile;
	m_OutputFile2 = iniData.m_OutputFile2;
	m_OutputRate = iniData.m_OutputRate;
	CheckRadioButton(IDC_LOW_QUALITY, IDC_HIGHEST_QUALITY, iniData.m_Quality + IDC_LOW_QUALITY);
	CheckRadioButton(IDC_MODE_AUTO, IDC_MODE_STOP, iniData.m_StartupMode + IDC_MODE_AUTO);

	CDialog::OnInitDialog();
	
	return TRUE;
}

void CSetFile::OnOK() 
{
	UpdateData(TRUE);

	iniData.m_OutputFile = m_OutputFile;
	iniData.m_OutputFile2 = m_OutputFile2;
	iniData.m_OutputRate = m_OutputRate;
	iniData.m_Quality = GetCheckedRadioButton(IDC_LOW_QUALITY, IDC_HIGHEST_QUALITY) - IDC_LOW_QUALITY;
	iniData.m_StartupMode = GetCheckedRadioButton(IDC_MODE_AUTO, IDC_MODE_STOP) - IDC_MODE_AUTO;

	iniData.Write();

	EndDialog(TRUE);
}

void CSetFile::OnSelectFile() 
{
	UpdateData(TRUE);

	CFileDialog dlgFile(FALSE, "jpg", m_OutputFile, OFN_HIDEREADONLY, "JPEG ﾌｧｲﾙ (*.jpg)|*.jpg|全てのﾌｧｲﾙ (*.*)|*.*||", this);

	dlgFile.m_ofn.lpstrTitle = "出力ﾌｧｲﾙ(ﾌﾙｻｲｽﾞ)の選択";
	dlgFile.DoModal();
	m_OutputFile = dlgFile.GetPathName();
	UpdateData(FALSE);
}

void CSetFile::OnSelectFile2() 
{
	UpdateData(TRUE);

	CFileDialog dlgFile(FALSE, "jpg", m_OutputFile2, OFN_HIDEREADONLY, "JPEG ﾌｧｲﾙ (*.jpg)|*.jpg|全てのﾌｧｲﾙ (*.*)|*.*||", this);

	dlgFile.m_ofn.lpstrTitle = "出力ﾌｧｲﾙ(ﾊｰﾌｻｲｽﾞ)の選択";
	dlgFile.DoModal();
	m_OutputFile2 = dlgFile.GetPathName();
	UpdateData(FALSE);
}
