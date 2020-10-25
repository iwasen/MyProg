// AutoNumDlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MMLib.h"
#include "AutoNumDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CAutoNumDlg �_�C�A���O


CAutoNumDlg::CAutoNumDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CAutoNumDlg::IDD, pParent)
{
	m_sCode = _T("");
	m_lStep = 0;
	m_bAutoNumbering = FALSE;
}


void CAutoNumDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_STEP, m_cStep);
	DDX_Control(pDX, IDC_CODE, m_cCode);
	DDX_Text(pDX, IDC_CODE, m_sCode);
	DDX_Text(pDX, IDC_STEP, m_lStep);
	DDX_Check(pDX, IDC_AUTO_NUMBERING, m_bAutoNumbering);
}


BEGIN_MESSAGE_MAP(CAutoNumDlg, CDialog)
	ON_BN_CLICKED(IDC_AUTO_NUMBERING, OnAutoNumbering)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CAutoNumDlg ���b�Z�[�W �n���h��

BOOL CAutoNumDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();
	
	EnableControl();
	
	return TRUE;
}

void CAutoNumDlg::OnAutoNumbering() 
{
	EnableControl();
}

void CAutoNumDlg::EnableControl()
{
	UpdateData(TRUE);

	if (m_bAutoNumbering) {
		m_cCode.EnableWindow(TRUE);
		m_cStep.EnableWindow(TRUE);
	} else {
		m_cCode.EnableWindow(FALSE);
		m_cStep.EnableWindow(FALSE);
	}
}
