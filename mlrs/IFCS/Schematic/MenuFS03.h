#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS03.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS03 �_�C�A���O

class CMenuFS03 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS03)

public:
	CMenuFS03(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS03 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
