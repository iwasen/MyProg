// CheckPwdDlg.cpp : �C���v�������e�[�V���� �t�@�C��
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
// CCheckPwdDlg �_�C�A���O


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
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCheckPwdDlg ���b�Z�[�W �n���h��
