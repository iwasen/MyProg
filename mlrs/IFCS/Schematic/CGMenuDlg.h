#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		CGMenuDlg.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@�\�}�b�f�\�����j���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "DialogEx.h"


// CCGMenuDlg �_�C�A���O

class CCGMenuDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CCGMenuDlg)

public:
	CCGMenuDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_CG_MENU };

protected:
	void AddMenuItem(CListBox &cListBox, LPCTSTR pSchematicCode);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	CListBox m_cListKeitozu;
	CListBox m_cListSetsumeizu;
	afx_msg void OnBnClickedButtonExit();
	afx_msg void OnBnClickedButtonDispKeitozu();
	afx_msg void OnBnClickedButtonDispSetsumeizu();
	afx_msg void OnBnClickedButtonRenzoku();
	afx_msg void OnLbnSelchangeListKeitozu();
	afx_msg void OnLbnDblclkListKeitozu();
	afx_msg void OnLbnSelchangeListSetsumeizu();
	afx_msg void OnLbnDblclkListSetsumeizu();
	CButton m_cButtonDispKeitozu;
	CButton m_cButtonDispSetsumeizu;
};
