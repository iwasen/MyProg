#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS08.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z���ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS08 �_�C�A���O

class CMenuFS08 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS08)

public:
	CMenuFS08(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
