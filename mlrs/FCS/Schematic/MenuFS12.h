#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS12.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�k�o�^�b�|�e���A�ڊ��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS12 �_�C�A���O

class CMenuFS12 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS12)

public:
	CMenuFS12(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS12 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction2();
};
