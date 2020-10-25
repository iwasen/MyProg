// SaveProjectDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "SaveProjectDlg001.h"
#include "SaveProjectDlg.h"
#include "shlwapi.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CSaveProjectDlg ダイアログ


CSaveProjectDlg::CSaveProjectDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CSaveProjectDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSaveProjectDlg)
	//}}AFX_DATA_INIT
}


void CSaveProjectDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSaveProjectDlg)
	DDX_Control(pDX, IDC_EDIT_PROJECT_NAME, m_cEditProjectName);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSaveProjectDlg, CDialogEx)
	//{{AFX_MSG_MAP(CSaveProjectDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSaveProjectDlg メッセージ ハンドラ

BOOL CSaveProjectDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SaveProjectDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

#ifdef _DEBUG
	// デバッグ用に新しいフォルダ名を表示
	CString sParentFolderName = CGlobal::GetFolderNameFromPath(g_pSystem->m_cIniUser.m_sDataPath);
	CString sFolderName;
	for (int i = 1; ; i++) {
		sFolderName.Format("AAAA%d", i);
		if (!::PathFileExists(CGlobal::MakePathName(sParentFolderName, sFolderName)))
			break;
	}
	m_cEditProjectName = sFolderName;
#endif

	m_cEditProjectName.SetMaxLength(40);

	return TRUE;
}

void CSaveProjectDlg::OnOK() 
{
	if (ProjectNameCheck()) {
		CString sFolderName;
		if (CGlobal::SelectFolderDialog(CGlobal::GetFolderNameFromPath(g_pSystem->m_cIniUser.m_sDataPath), sFolderName)) {
			CString sPathName = CGlobal::MakePathName(sFolderName, (CString)m_cEditProjectName);
			if (sPathName.GetLength() > MAX_PATH) {
				g_pSystem->DispMessage("E002006");
				return;
			}
			if (g_pDataManager->SaveAsFile(sPathName))
				EndDialog(IDOK);
		}
	}
}

BOOL CSaveProjectDlg::ProjectNameCheck()
{
	static CDataCheck cProjectNameCheck = {
		eDataTypeFileName, 40, 0, 0, 0, NULL
	};

	return EditDataCheck(cProjectNameCheck, m_cEditProjectName, "プロジェクト名");
}
