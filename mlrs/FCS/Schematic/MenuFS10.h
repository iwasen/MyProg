#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS10.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���䉉�Z��|�e���A�ڊ��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS10 �_�C�A���O

class CMenuFS10 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS10)

public:
	CMenuFS10(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS10 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
