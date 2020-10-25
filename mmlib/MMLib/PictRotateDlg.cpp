// PictRotateDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "PictRotateDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CPictRotateDlg ダイアログ


CPictRotateDlg::CPictRotateDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CPictRotateDlg::IDD, pParent)
{
	m_iRotate = -1;
}


void CPictRotateDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Radio(pDX, IDC_RIGHT_ROTATE, m_iRotate);
}


BEGIN_MESSAGE_MAP(CPictRotateDlg, CDialog)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPictRotateDlg メッセージ ハンドラ

BOOL CPictRotateDlg::OnInitDialog() 
{
	m_iRotate = theApp.GetProfileInt(g_SectionSettings, "PictRotate", -1);

	CDialog::OnInitDialog();

	return TRUE;
}

void CPictRotateDlg::OnOK() 
{
	UpdateData(TRUE);
	if (m_iRotate == -1) {
		AfxMessageBox(IDS_SELECT_ROTATION);
		return;
	}

	theApp.WriteProfileInt(g_SectionSettings, "PictRotate", m_iRotate);

	switch (m_iRotate) {
	case 0:
		m_nRotate = 1;
		break;
	case 1:
		m_nRotate = 3;
		break;
	case 2:
		m_nRotate = 2;
		break;
	}

	CDialog::OnOK();
}
