#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MenuFS16.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ʁE����ʒu���o��y�ы쓮�T�[�{���[�^�i�@�\�X�P�}�`�b�N�j��ʂ̑��상�j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "MenuCommon.h"

// CMenuFS16 �_�C�A���O

class CMenuFS16 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS16)

public:
	CMenuFS16(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS16 };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	afx_msg void OnBnClickedButtonAction7();
};
