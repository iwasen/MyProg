// CheckPwdDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "CheckPwdDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CCheckPwdDlg ダイアログ


CCheckPwdDlg::CCheckPwdDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CCheckPwdDlg::IDD, pParent)
{
	m_sLibrary = _T("");
	m_sPassword = _T("");
}


void CCheckPwdDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_LIBRARY, m_sLibrary);
	DDX_Text(pDX, IDC_PASSWORD, m_sPassword);
}


BEGIN_MESSAGE_MAP(CCheckPwdDlg, CDialog)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCheckPwdDlg メッセージ ハンドラ
