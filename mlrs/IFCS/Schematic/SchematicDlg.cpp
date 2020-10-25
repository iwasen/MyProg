//*****************************************************************************************************
//  1. ファイル名
//		SchematicDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "SchematicDlg.h"
#include "Schematic.h"
#include "shlwapi.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CSchematicDlg ダイアログ

CSchematicDlg::CSchematicDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CSchematicDlg::IDD, pParent)
{
}

void CSchematicDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CSchematicDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_FS01, &CSchematicDlg::OnBnClickedButtonFS01)
	ON_BN_CLICKED(IDC_BUTTON_FS02, &CSchematicDlg::OnBnClickedButtonFS02)
	ON_BN_CLICKED(IDC_BUTTON_FS03, &CSchematicDlg::OnBnClickedButtonFS03)
	ON_BN_CLICKED(IDC_BUTTON_FS04, &CSchematicDlg::OnBnClickedButtonFS04)
	ON_BN_CLICKED(IDC_BUTTON_FS05, &CSchematicDlg::OnBnClickedButtonFS05)
	ON_BN_CLICKED(IDC_BUTTON_FS06, &CSchematicDlg::OnBnClickedButtonFS06)
	ON_BN_CLICKED(IDC_BUTTON_FS07, &CSchematicDlg::OnBnClickedButtonFS07)
	ON_BN_CLICKED(IDC_BUTTON_FS08, &CSchematicDlg::OnBnClickedButtonFS08)
	ON_BN_CLICKED(IDC_BUTTON_FS09, &CSchematicDlg::OnBnClickedButtonFS09)
	ON_BN_CLICKED(IDC_BUTTON_FS10, &CSchematicDlg::OnBnClickedButtonFS10)
	ON_BN_CLICKED(IDC_BUTTON_FS11, &CSchematicDlg::OnBnClickedButtonFS11)
	ON_BN_CLICKED(IDC_BUTTON_FS12, &CSchematicDlg::OnBnClickedButtonFS12)
	ON_BN_CLICKED(IDC_BUTTON_FS13, &CSchematicDlg::OnBnClickedButtonFS13)
	ON_BN_CLICKED(IDC_BUTTON_FS14, &CSchematicDlg::OnBnClickedButtonFS14)
	ON_BN_CLICKED(IDC_BUTTON_FS15, &CSchematicDlg::OnBnClickedButtonFS15)
	ON_BN_CLICKED(IDC_BUTTON_FS16, &CSchematicDlg::OnBnClickedButtonFS16)
	ON_BN_CLICKED(IDC_BUTTON_FS17, &CSchematicDlg::OnBnClickedButtonFS17)
	ON_BN_CLICKED(IDC_BUTTON_FS18, &CSchematicDlg::OnBnClickedButtonFS18)
	ON_BN_CLICKED(IDC_BUTTON_FS19, &CSchematicDlg::OnBnClickedButtonFS19)
	ON_BN_CLICKED(IDC_BUTTON_FE01, &CSchematicDlg::OnBnClickedButtonFE01)
	ON_BN_CLICKED(IDC_BUTTON_FE02, &CSchematicDlg::OnBnClickedButtonFE02)
	ON_BN_CLICKED(IDC_BUTTON_FE03, &CSchematicDlg::OnBnClickedButtonFE03)
	ON_BN_CLICKED(IDC_BUTTON_FE04, &CSchematicDlg::OnBnClickedButtonFE04)
	ON_BN_CLICKED(IDC_BUTTON_FE05, &CSchematicDlg::OnBnClickedButtonFE05)
	ON_BN_CLICKED(IDC_BUTTON_FE06, &CSchematicDlg::OnBnClickedButtonFE06)
	ON_BN_CLICKED(IDC_BUTTON_FE07, &CSchematicDlg::OnBnClickedButtonFE07)
	ON_BN_CLICKED(IDC_BUTTON_CS01, &CSchematicDlg::OnBnClickedButtonCS01)
END_MESSAGE_MAP()


// CSchematicDlg メッセージ ハンドラ

// MLRS画面テストダイアログの初期化処理
BOOL CSchematicDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	AfxGetApp()->m_pMainWnd = this;

	return TRUE;
}

void CSchematicDlg::OnBnClickedButtonFS01()
{
	CSchematic::OpenSchematicWindow("FS01");
}

void CSchematicDlg::OnBnClickedButtonFS02()
{
	CSchematic::OpenSchematicWindow("FS02");
}

void CSchematicDlg::OnBnClickedButtonFS03()
{
	CSchematic::OpenSchematicWindow("FS03");
}

void CSchematicDlg::OnBnClickedButtonFS04()
{
	CSchematic::OpenSchematicWindow("FS04");
}

void CSchematicDlg::OnBnClickedButtonFS05()
{
	CSchematic::OpenSchematicWindow("FS05");
}

void CSchematicDlg::OnBnClickedButtonFS06()
{
	CSchematic::OpenSchematicWindow("FS06");
}

void CSchematicDlg::OnBnClickedButtonFS07()
{
	CSchematic::OpenSchematicWindow("FS07");
}

void CSchematicDlg::OnBnClickedButtonFS08()
{
	CSchematic::OpenSchematicWindow("FS08");
}

void CSchematicDlg::OnBnClickedButtonFS09()
{
	CSchematic::OpenSchematicWindow("FS09");
}

void CSchematicDlg::OnBnClickedButtonFS10()
{
	CSchematic::OpenSchematicWindow("FS10");
}

void CSchematicDlg::OnBnClickedButtonFS11()
{
	CSchematic::OpenSchematicWindow("FS11");
}

void CSchematicDlg::OnBnClickedButtonFS12()
{
	CSchematic::OpenSchematicWindow("FS12");
}

void CSchematicDlg::OnBnClickedButtonFS13()
{
	CSchematic::OpenSchematicWindow("FS13");
}

void CSchematicDlg::OnBnClickedButtonFS14()
{
	CSchematic::OpenSchematicWindow("FS14");
}

void CSchematicDlg::OnBnClickedButtonFS15()
{
	CSchematic::OpenSchematicWindow("FS15");
}

void CSchematicDlg::OnBnClickedButtonFS16()
{
	CSchematic::OpenSchematicWindow("FS16");
}

void CSchematicDlg::OnBnClickedButtonFS17()
{
	CSchematic::OpenSchematicWindow("FS17");
}

void CSchematicDlg::OnBnClickedButtonFS18()
{
	CSchematic::OpenSchematicWindow("FS18");
}

void CSchematicDlg::OnBnClickedButtonFS19()
{
	CSchematic::OpenSchematicWindow("FS19");
}

void CSchematicDlg::OnBnClickedButtonFE01()
{
	CSchematic::OpenSchematicWindow("FE01");
}

void CSchematicDlg::OnBnClickedButtonFE02()
{
	CSchematic::OpenSchematicWindow("FE02");
}

void CSchematicDlg::OnBnClickedButtonFE03()
{
	CSchematic::OpenSchematicWindow("FE03");
}

void CSchematicDlg::OnBnClickedButtonFE04()
{
	CSchematic::OpenSchematicWindow("FE04");
}

void CSchematicDlg::OnBnClickedButtonFE05()
{
	CSchematic::OpenSchematicWindow("FE05");
}

void CSchematicDlg::OnBnClickedButtonFE06()
{
	CSchematic::OpenSchematicWindow("FE06");
}

void CSchematicDlg::OnBnClickedButtonFE07()
{
	CSchematic::OpenSchematicWindow("FE07");
}

void CSchematicDlg::OnBnClickedButtonCS01()
{
	CSchematic::OpenSchematicWindow("CS01");
}
