#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS08_6.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z��i�u�[�����[�^�E�z�C�X�g���[�^�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS08_6 �_�C�A���O

class CMenuFS08_6 : public CMenuCommon
{
	DECLARE_DYNAMIC(CMenuFS08_6)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,			// �������
		STATUS_BOOM_MENU,		// �u�[�����䃁�j���[
		STATUS_STRAGE,			// ���ˋ@�����[
		STATUS_READY_LAMP,		// �u�g�p�v�\����
		STATUS_LEFT,			// ��
		STATUS_BOTH,			// ����
		STATUS_RIGHT,			// �E
		STATUS_BOOM_SHORT,		// �u�[���k
		STATUS_BOOM_LONG,		// �u�[���L
		STATUS_HOOK_UP,			// �t�b�N��
		STATUS_HOOK_DOWN		// �t�b�N��
	};

public:
	CMenuFS08_6(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08_6 };

protected:
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction40();
	afx_msg void OnBnClickedButtonAction41();
	afx_msg void OnBnClickedButtonAction42();
	afx_msg void OnBnClickedButtonAction43();
	afx_msg void OnBnClickedButtonAction44();
	afx_msg void OnBnClickedButtonAction45();
	afx_msg void OnBnClickedButtonAction46();
	afx_msg void OnBnClickedButtonAction47();
	afx_msg void OnBnClickedButtonAction48();
	afx_msg void OnBnClickedButtonAction49();
	afx_msg void OnBnClickedButtonAction50();

	CMenuButton m_cButtonAction40;
	CMenuButton m_cButtonAction41;
	CMenuButton m_cButtonAction42;
	CMenuButton m_cButtonAction43;
	CMenuButton m_cButtonAction44;
	CMenuButton m_cButtonAction45;
	CMenuButton m_cButtonAction46;
	CMenuButton m_cButtonAction47;
	CMenuButton m_cButtonAction48;
	CMenuButton m_cButtonAction49;
	CMenuButton m_cButtonAction50;
	CMenuButton m_cButtonExit;
};
