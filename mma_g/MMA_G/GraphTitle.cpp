// GraphTitle.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphTitle.h"
#include "General.h"


// CGraphTitle �_�C�A���O

IMPLEMENT_DYNAMIC(CGraphTitle, CDialog)

CGraphTitle::CGraphTitle(CWnd* pParent /*=NULL*/)
	: CDialog(CGraphTitle::IDD, pParent)
{

}

CGraphTitle::~CGraphTitle()
{
}

void CGraphTitle::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_TITLE, m_cEditTitle);
}


BEGIN_MESSAGE_MAP(CGraphTitle, CDialog)
	ON_BN_CLICKED(IDOK, &CGraphTitle::OnBnClickedOk)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDOK, "�O���t�̈�������s���܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};

BOOL CGraphTitle::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cEditTitle.SetWindowText(m_sTitle);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

void CGraphTitle::SetTitle(CString title)
{
	m_sTitle = title;
}

void CGraphTitle::GetTitle(CString& title)
{
	title = m_sTitle;
}

void CGraphTitle::OnBnClickedOk()
{
	m_cEditTitle.GetWindowText(m_sTitle);

	OnOK();
}

void CGraphTitle::OnDestroy()
{
	CDialog::OnDestroy();

	OnCancel();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphTitle::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E���b�Z�[�W����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		MSG*	pMsg			[I] MSG�\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�e�N���X�̕Ԓl�����̂܂ܕԂ�
//*****************************************************************************************************
BOOL CGraphTitle::PreTranslateMessage(MSG* pMsg)
{
	// �c�[���`�b�v��\�����邽�߂̏���
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
