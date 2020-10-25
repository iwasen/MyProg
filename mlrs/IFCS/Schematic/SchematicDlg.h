#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		SchematicDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "DialogEx.h"

// CSchematicDlg ダイアログ
class CSchematicDlg : public CDialogEx
{
// コンストラクション
public:
	CSchematicDlg(CWnd* pParent = NULL);	// 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_SCHEMATICTEST_DIALOG };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
public:
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonFS01();
	afx_msg void OnBnClickedButtonFS02();
	afx_msg void OnBnClickedButtonFS03();
	afx_msg void OnBnClickedButtonFS04();
	afx_msg void OnBnClickedButtonFS05();
	afx_msg void OnBnClickedButtonFS06();
	afx_msg void OnBnClickedButtonFS07();
	afx_msg void OnBnClickedButtonFS08();
	afx_msg void OnBnClickedButtonFS09();
	afx_msg void OnBnClickedButtonFS10();
	afx_msg void OnBnClickedButtonFS11();
	afx_msg void OnBnClickedButtonFS12();
	afx_msg void OnBnClickedButtonFS13();
	afx_msg void OnBnClickedButtonFS14();
	afx_msg void OnBnClickedButtonFS15();
	afx_msg void OnBnClickedButtonFS16();
	afx_msg void OnBnClickedButtonFS17();
	afx_msg void OnBnClickedButtonFS18();
	afx_msg void OnBnClickedButtonFS19();
	afx_msg void OnBnClickedButtonFE01();
	afx_msg void OnBnClickedButtonFE02();
	afx_msg void OnBnClickedButtonFE03();
	afx_msg void OnBnClickedButtonFE04();
	afx_msg void OnBnClickedButtonFE05();
	afx_msg void OnBnClickedButtonFE06();
	afx_msg void OnBnClickedButtonFE07();
	afx_msg void OnBnClickedButtonCS01();
};
