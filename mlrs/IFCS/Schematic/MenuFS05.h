#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS05.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS05 �_�C�A���O

class CMenuFS05 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS05)

public:
	CMenuFS05(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS05 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
