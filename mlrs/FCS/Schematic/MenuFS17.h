#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS17.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ʁE��������o���u�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS17 �_�C�A���O

class CMenuFS17 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS17)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,		// �������
		STATUS_WORKING		// ���쒆
	};

public:
	CMenuFS17(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS17 };

protected:
	void SendAction(int nActionNo);
	virtual void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;		// ����X�e�[�^�X
	BOOL m_bPause;			// �ꎞ��~�t���O

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
};
