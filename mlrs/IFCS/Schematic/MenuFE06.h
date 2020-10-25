#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFE06.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���䉉�Z��|���{��ϊ���|�ˌ������p�l����ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuFE06 �_�C�A���O

class CMenuFE06 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFE06)

	// ����X�e�[�^�X
	enum EStatus {
		STATUS_INITIAL,			// �������
		STATUS_WORKING,			// ���쒆
		STATUS_END				// ����I��
	};

public:
	CMenuFE06(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FE06 };

protected:
	void SendAction(int nActionNo);
	void ActionEnd(int nActionNo);
	void SetStatus(EStatus eStatus);
	void SetMenuButton();

	EStatus m_eStatus;			// ����X�e�[�^�X
	BOOL m_bPause;				// �ꎞ��~�t���O

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

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
};
