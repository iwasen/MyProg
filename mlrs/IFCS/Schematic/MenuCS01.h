#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuCS01.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"
#include "MenuButton.h"

// CMenuCS01 �_�C�A���O

class CMenuCS01 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuCS01)

public:
	CMenuCS01(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_CS01 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
public:
	afx_msg void OnBnClickedButtonAction1();
};
