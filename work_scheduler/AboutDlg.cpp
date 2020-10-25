//*****************************************************************************************************
//  1. �t�@�C����
//		AboutDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CAboutDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"

#include "MenuDispCtrl.h"

#include "AboutDlg001.h"
#include "AboutDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

//*****************************************************************************************************
//  1. �֐���
//		CAboutDlg::CAboutDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���\�[�X����A�C�R�����擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CAboutDlg::CAboutDlg() : CDialogEx(CAboutDlg::IDD)
{
	//{{AFX_DATA_INIT(CAboutDlg)
	//}}AFX_DATA_INIT
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CAboutDlg)
	DDX_Control(pDX, IDC_STATIC_VERSION, m_cStaticVersion);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialogEx)
	//{{AFX_MSG_MAP(CAboutDlg)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

BOOL CAboutDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();

// Modify ... ( DELETE )
/*	CString sText, sText2;
	m_cStaticVersion.GetWindowText(sText);
	sText2.Format(sText, g_pConst->m_sVersion);
	m_cStaticVersion.SetWindowText(sText2);*/
// By Y.Itabashi (xxxxx) 2007.01.25

// Modify ... ( ADD )
	sprintf( Menu_AbortDlg[1].str1, "%s", g_pConst->m_sVersion );
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_AbortDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	return TRUE;
}

void CAboutDlg::OnOK() 
{
	EndDialog(IDOK);
}
