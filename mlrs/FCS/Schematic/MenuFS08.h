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
#include "MenuButton.h"

// CMenuFS08 �_�C�A���O

class CMenuFS08 : public CMenuCommon
{
	DECLARE_DYNCREATE(CMenuFS08)

public:
	CMenuFS08(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MENU_FS08 };

protected:
	void OpenSubMenu(CMenuCommon *pSubMenu);

public:
	CMenuCommon *m_pSubMenu;		// �\�����̃T�u���j���[

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonAction1();
	afx_msg void OnBnClickedButtonAction2();
	afx_msg void OnBnClickedButtonAction3();
	afx_msg void OnBnClickedButtonAction4();
	afx_msg void OnBnClickedButtonAction5();
	afx_msg void OnBnClickedButtonAction6();
	LRESULT OnUserZoomIn(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserZoomOut(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserSizeReset(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserPrint(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserExit(WPARAM wParam, LPARAM lParam);
	LRESULT OnUserAction(WPARAM wParam, LPARAM lParam);

	CMenuButton m_cButtonAction1;
	CMenuButton m_cButtonAction2;
	CMenuButton m_cButtonAction3;
	CMenuButton m_cButtonAction4;
	CMenuButton m_cButtonAction5;
	CMenuButton m_cButtonAction6;
	CMenuButton m_cButtonExit;
};
