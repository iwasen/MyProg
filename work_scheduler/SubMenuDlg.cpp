// SubMenuDlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "workscheduler.h"
#include "SubMenuDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CSubMenuDlg �_�C�A���O


CSubMenuDlg::CSubMenuDlg(UINT nID, CWnd* pParent /*=NULL*/)
	: CDialogEx(nID, pParent)
{
	//{{AFX_DATA_INIT(CSubMenuDlg)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_INIT
}


void CSubMenuDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSubMenuDlg)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSubMenuDlg, CDialogEx)
	//{{AFX_MSG_MAP(CSubMenuDlg)
	ON_WM_SHOWWINDOW()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CSubMenuDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CSubMenuDlg::OnShowWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL		bShow			[I] �E�B���h�E�\���t���O
//		UINT		nStatus			[I] �E�B���h�E�̏��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���j���[�{�^���̗L��������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSubMenuDlg::OnShowWindow(BOOL bShow, UINT nStatus) 
{
	CDialogEx::OnShowWindow(bShow, nStatus);

	// ���j���[�{�^���̗L����
	EnableMenuButton();
}

void CSubMenuDlg::EnableMenuButton()
{
}

// Enter�L�[�Ń_�C�A���O������̂�}�~
void CSubMenuDlg::OnOK()
{
}

//*****************************************************************************************************
//  1. �֐���
//		CSubMenuDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�����Z���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�������Ȃ��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		ESC�L�[�������Ă��_�C�A���O�����Ȃ��悤�ɐe�N���X�̊֐����I�[�o�[���C�h����B
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CSubMenuDlg::OnCancel()
{
}