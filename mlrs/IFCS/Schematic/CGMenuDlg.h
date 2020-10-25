#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		CGMenuDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機能図ＣＧ表示メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "DialogEx.h"


// CCGMenuDlg ダイアログ

class CCGMenuDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CCGMenuDlg)

public:
	CCGMenuDlg(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_CG_MENU };

protected:
	void AddMenuItem(CListBox &cListBox, LPCTSTR pSchematicCode);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

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
