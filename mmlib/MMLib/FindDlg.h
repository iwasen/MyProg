#pragma once

// FindDlg.h : ヘッダー ファイル
//

#include "Item.h"
#include "MyCtrl.h"

/////////////////////////////////////////////////////////////////////////////
// CFindDlg ダイアログ

class CFindDlg : public CDialog
{
// コンストラクション
public:
	CFindDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_FIND };
	CEdit	m_cText;
	CEdit	m_cCode;
	CMyEdit	m_cFindNum;
	CMyDate	m_cUpdateDateTo;
	CMyDate	m_cUpdateDateFrom;
	CMyDate	m_cCreateDateTo;
	CMyDate	m_cCreateDateFrom;
	CTabCtrl	m_cConditionTab;
	CComboBox	m_cLibrary;
	CString	m_sCode;
	CString	m_sCreateDateBefore;
	CString	m_sCreator;
	CString	m_sFindFolder;
	CString	m_sRemark;
	CString	m_sKeyword;
	CString	m_sTitle;
	CString	m_sUpdateDateBefore;
	BOOL	m_bNoCase;
	int		m_iMatching;
	int		m_iFindType;
	BOOL	m_bSave;
	CString	m_sText;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSelchangingConditionTab(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnSelchangeConditionTab(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnGetfindnum();
	afx_msg void OnFolder();
	afx_msg void OnData();
	afx_msg HBRUSH OnCtlColor(CDC* pDC, CWnd* pWnd, UINT nCtlColor);
	DECLARE_MESSAGE_MAP()

public:
	BOOL m_bUpdateMode;
	int m_nFindType;
	CItemFolder *m_pItemFolder;
	FIND_CONDITION m_FindConditions[N_FIND_CONDITIONS];

private:
	void GetFindCondition();
	void SetFindCondition();
};
