#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS06.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS06 �_�C�A���O

class CMenuFS06 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS06)

public:
	CMenuFS06(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS06 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
