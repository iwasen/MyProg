#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		WorkSchedulerDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CWorkSchedulerDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "SubMenu.h"
#include "ColorButton.h"

// メインダイアログ
class CWorkSchedulerDlg : public CDialogEx
{
// 構築
public:
	CWorkSchedulerDlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CWorkSchedulerDlg)
	enum { IDD = IDD_WORKSCHEDULER_DIALOG };
	CProgressCtrl	m_cProgressBar;
	CStatic	m_cStaticStatus;
	CColorButton	m_buttonExit;
	CColorButton	m_buttonAutoScheduling;
	CColorButton	m_buttonManualScheduling;
	CColorButton	m_buttonSetConditions;
	CColorButton	m_buttonStart;
	CSubMenu	m_cSubMenu;
	//}}AFX_DATA

	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CWorkSchedulerDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV のサポート
	virtual void PostNcDestroy();
	//}}AFX_VIRTUAL

public:
	void EnableMenu();
	void SetSubMenuStatus(LPCTSTR pText);
	void SetSubMenuStatus(CWnd &wndButton);
	void ClearSubMenuStatus();

// インプリメンテーション
protected:
	HICON m_hIcon;		// アイコン

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CWorkSchedulerDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	afx_msg void OnButtonStart();
	afx_msg void OnButtonSetConditions();
	afx_msg void OnButtonAutoScheduling();
	afx_msg void OnButtonManualScheduling();
	afx_msg void OnButtonExit();
	virtual void OnCancel();
	afx_msg void OnFileSave();
	afx_msg void OnFileSaveAs();
	afx_msg void OnUpdateFileSave(CCmdUI* pCmdUI);
	afx_msg void OnUpdateFileSaveAs(CCmdUI* pCmdUI);
	afx_msg void OnInitMenuPopup(CMenu* pPopupMenu, UINT nIndex, BOOL bSysMenu);
	afx_msg void OnHelpVersion();
	//}}AFX_MSG
	virtual void OnOK();
	afx_msg LRESULT OnOpenExcel(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnCloseExcel(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnErrorExcel(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	CTypedPtrArray< CObArray, CColorButton* > m_arrayMenuButton;	// メニューボタン配列
	CString m_sMainStatus;		// メインメニューステータス

	void InitMenuButton();
	void InitSubMenu();
	void SelectMenu(int nIndex);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
