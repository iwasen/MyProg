//*****************************************************************************************************
//  1. �t�@�C����
//		FftBaseDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��̓O���t�_�C�A���O���N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2008.10.15 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftBaseDlg.h"
#include "Define.h"

// CFftBaseDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CFftBaseDlg, CDialogEx)

CFftBaseDlg::CFftBaseDlg(UINT nID, CWnd* pParent)
	: CDialogEx(nID, pParent)
{

}

CFftBaseDlg::~CFftBaseDlg()
{
}

void CFftBaseDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CFftBaseDlg, CDialogEx)
	ON_WM_DESTROY()
END_MESSAGE_MAP()


// CFftBaseDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CFftBaseDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CFftBaseDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// ��̓O���t�E�B���h�E�I�[�v���ʒm
	::SendMessage(m_hWndParent, WM_USER_OPEN_GRAPH, (WPARAM)GetSafeHwnd(), 0);

	// �e�E�B���h�E�̒����̈ʒu�ɕ\��
	CenterWindow(CWnd::FromHandle(m_hWndParent));

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftBaseDlg::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�p������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftBaseDlg::OnDestroy()
{
	CDialogEx::OnDestroy();

	// ��̓O���t�E�B���h�E�N���[�Y�ʒm
	::SendMessage(m_hWndParent, WM_USER_CLOSE_GRAPH, (WPARAM)GetSafeHwnd(), 0);
}