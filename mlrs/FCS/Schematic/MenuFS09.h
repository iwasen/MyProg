#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS09.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���䉉�Z��|���ː����|�k�o�^�b��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS09 �_�C�A���O

class CMenuFS09 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS09)

public:
	CMenuFS09(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS09 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
