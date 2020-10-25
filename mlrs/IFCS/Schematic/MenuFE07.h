#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE07.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���䉉�Z��|�d�������|���ː�����ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE07 �_�C�A���O

class CMenuFE07 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE07)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_STOP,			// ��~
		STATUS_NEUTRAL,			// ����
		STATUS_FORWARD,			// �O�i
		STATUS_REVERSE,			// ��i
		STATUS_STATIONARY_TURN	// ���M�n����
	};

public:
	CMenuFE07(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE07 };

protected:
	void SendAction(int nActionNo);
	void SetMenuButton();

	EStatus m_eStatus;			// ����X�e�[�^�X
	int m_nClutch;				// �N���b�`�̈ʒu
	BOOL m_bArrow;				// ���̕\���t���O
	BOOL m_bWait;				// ���슮���҂��t���O
	BOOL m_bTurn;				// ���񒆃t���O
	BOOL m_bBrake;				// �u���[�L�t���O

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
	afx_msg void OnBnClickedButtonAction8();
	afx_msg void OnBnClickedButtonAction9();
	afx_msg void OnBnClickedButtonAction10();
	afx_msg void OnBnClickedButtonAction11();
	afx_msg void OnTimer(UINT_PTR nIDEvent);

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonAction7;
	CMenuButton m_cButtonAction8;
	CMenuButton m_cButtonAction9;
	CMenuButton m_cButtonAction10;
	CMenuButton m_cButtonAction11;
public:
	virtual BOOL OnInitDialog();
};
