#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS04.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFS04 �_�C�A���O

class CMenuFS04 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS04)

	// �g���x�����b�N����X�e�[�^�X
	enum ETravelStatus {
		STATUS_TRAVEL_INITIAL,		// �������
		STATUS_TRAVEL_CAGE_UP,		// CAGE UP
		STATUS_TRAVEL_CAGE_DOWN		// CAGE DOWN
	};

	// �z�C�X�g���[�^����X�e�[�^�X
	enum EHoistStatus {
		STATUS_HOIST_INITIAL,		// �������
		STATUS_HOIST_BOOM_MENU,		// �u�[�����䃁�j���[
		STATUS_HOIST_STRAGE,		// ���ˋ@�����[
		STATUS_HOIST_READY_LAMP,	// �u�g�p�v�\����
		STATUS_HOIST_LEFT,			// ��
		STATUS_HOIST_BOTH,			// ����
		STATUS_HOIST_RIGHT,			// �E
		STATUS_HOIST_BOOM_SHORT,	// �u�[���k
		STATUS_HOIST_BOOM_LONG		// �u�[���L
	};

public:
	CMenuFS04(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS04 };

protected:
	void SetStatus(ETravelStatus eTravelStatus, EHoistStatus eHoistStatus);
	void SetMenuButton();

	ETravelStatus m_eTravelStatus;	// �g���x�����b�N����X�e�[�^�X
	EHoistStatus m_eHoistStatus;	// �z�C�X�g���[�^����X�e�[�^�X

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
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
	afx_msg void OnBnClickedButtonAction12();

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
	CMenuButton m_cButtonAction12;
};
