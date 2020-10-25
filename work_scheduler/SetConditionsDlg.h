#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		SetConditionsDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSetConditionsDlg �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "ColorBUtton.h"

// �Ґ������ݒ胁�j���[�{�^���̃T�u���j���[���
class CSetConditionsDlg : public CSubMenuDlg
{
// �R���X�g���N�V����
public:
	CSetConditionsDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CSetConditionsDlg)
	enum { IDD = IDD_SET_CONDITIONS };
	CColorButton	m_cButtonSetSeisanJoken;
	CColorButton	m_cButtonSetLineKisei;
	CColorButton	m_cButtonSetKyotsuKotei;
	CColorButton	m_cButtonSetKatashikiKisei;
	CColorButton	m_cButtonSetKitting;
	//}}AFX_DATA


// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CSetConditionsDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
	//{{AFX_MSG(CSetConditionsDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonSetKitting();
	afx_msg void OnButtonSetSeisanJoken();
	afx_msg void OnButtonSetLineKisei();
	afx_msg void OnButtonSetKatashikiKisei();
	afx_msg void OnButtonSetKyotuKotei();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void EnableMenuButton();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
