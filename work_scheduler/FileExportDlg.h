#if !defined(AFX_FILEEXPORTDLG_H__90D40D34_2673_421A_B624_DA90F1D1108E__INCLUDED_)
#define AFX_FILEEXPORTDLG_H__90D40D34_2673_421A_B624_DA90F1D1108E__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// FileExportDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CFileExportDlg ダイアログ

#include "DialogEx.h"

class CFileExportDlg : public CDialogEx
{

protected:
// コンストラクション
public:
	CFileExportDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CFileExportDlg)
	enum { IDD = IDD_FILE_EXPORT_DIALOG };
		// メモ: ClassWizard はこの位置にデータ メンバを追加します。
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];
	CMyButton	m_aCheckKishu[MAX_KISHU];
// Modify ... ( ADD )
	bool		m_aCheckedSubline[MAX_SUBLINE];	// サブラインの選択フラグ
	bool		m_aCheckedKishu[MAX_KISHU];		// 機種の選択フラグ
// By K.Kimura (xxxxx) 2007.02.16
// Modify ... ( ADD )
	CMyButton	m_bCheckWaritsuke;
	CMyButton	m_bCheckZenkishu;
	CMyButton	m_bCheckKajuHeikin;
	bool		m_bCheckedZenkishu;				// 全機種
	bool		m_bCheckedKajuHeikin;			// 加重平均
	bool		m_bCheckedWaritsuke;			// 割付作業表
// By Y.Itabashi (xxxxx) 2007.02.19

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CFileExportDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

	void SetAllSublixxxheck(BOOL bCheck);


// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CFileExportDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnButtonZensentaku();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_FILEEXPORTDLG_H__90D40D34_2673_421A_B624_DA90F1D1108E__INCLUDED_)
