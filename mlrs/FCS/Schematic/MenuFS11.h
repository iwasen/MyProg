#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS11.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ː����|�e���A�ڊ��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS11 �_�C�A���O

class CMenuFS11 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS11)

public:
	CMenuFS11(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS11 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
