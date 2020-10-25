// SelectRsu.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "SelectRsu.h"
#include "General.h"


// CSelectRsu �_�C�A���O

IMPLEMENT_DYNAMIC(CSelectRsu, CDialog)

CSelectRsu::CSelectRsu(CWnd* pParent /*=NULL*/)
	: CDialog(CSelectRsu::IDD, pParent)
{
	m_nIndex = -1;
}

CSelectRsu::~CSelectRsu()
{
}

void CSelectRsu::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_Cmb_Rsu);
	DDX_Control(pDX, IDCANCEL, m_cButtonOK);
}


BEGIN_MESSAGE_MAP(CSelectRsu, CDialog)
	ON_BN_CLICKED(IDCANCEL, &CSelectRsu::OnBnClickedCancel)
	ON_CBN_SELCHANGE(IDC_COMBO_SENSOR, &CSelectRsu::OnCbnSelchangeComboSensor)
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDCANCEL, "������RSU ID�̒�����ΏۂƂ���RSU ID��I�����܂��B"},
	{0, NULL}
};

// CSelectRsu ���b�Z�[�W �n���h��

void CSelectRsu::SetRsu(CStringArray *arr)
{
	int	i;

	for (i = 0; i < arr->GetSize(); i++) {
		m_Rsu.Add(arr->GetAt(i));
	}
}

bool CSelectRsu::GetRsu(CString &Rsu)
{
	if (m_nIndex >= 0) {
		Rsu = m_Rsu.GetAt(m_nIndex);
		return true;
	}
	return false;
}

BOOL CSelectRsu::OnInitDialog()
{
	CDialog::OnInitDialog();

	int	i;

	for (i = 0; i < m_Rsu.GetSize(); i++) {
		m_Cmb_Rsu.AddString(m_Rsu.GetAt(i));
	}

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

void CSelectRsu::OnBnClickedCancel()
{
	if ((m_nIndex = m_Cmb_Rsu.GetCurSel()) != CB_ERR) {
		OnCancel();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CSelectRsu::PreTranslateMessage
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
BOOL CSelectRsu::PreTranslateMessage(MSG* pMsg)
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

void CSelectRsu::OnCbnSelchangeComboSensor()
{
	m_cButtonOK.EnableWindow(m_Cmb_Rsu.GetCurSel() != CB_ERR);
}
