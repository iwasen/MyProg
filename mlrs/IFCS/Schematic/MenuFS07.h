#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS07.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS07 �_�C�A���O

class CMenuFS07 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS07)

public:
	CMenuFS07(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS07 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
