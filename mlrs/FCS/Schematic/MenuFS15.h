#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS15.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g���x�����b�N�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS15 �_�C�A���O

class CMenuFS15 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS15)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,			// �������
		STATUS_CAGE_UP,			// CAGE UP (CAGE UNLCK)
		STATUS_CAGE_DOWN,		// CAGE DOWN (CAGE LOCK)
		STATUS_CAGE_WORKING		// ���쒆
	};

public:
	CMenuFS15(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS15 };

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
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
};
