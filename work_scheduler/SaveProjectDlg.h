#if !defined(AFX_SAVEPROJECTDLG_H__EC323F26_9944_4783_B9E0_6ED2F559314E__INCLUDED_)
#define AFX_SAVEPROJECTDLG_H__EC323F26_9944_4783_B9E0_6ED2F559314E__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SaveProjectDlg.h : ヘッダー ファイル
//

#include "DialogEx.h"

/////////////////////////////////////////////////////////////////////////////
// CSaveProjectDlg ダイアログ

class CSaveProjectDlg : public CDialogEx
{
// コンストラクション
public:
	CSaveProjectDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSaveProjectDlg)
	enum { IDD = IDD_SAVE_PROJECT };
	CMyEdit	m_cEditProjectName;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CSaveProjectDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CSaveProjectDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	BOOL ProjectNameCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_SAVEPROJECTDLG_H__EC323F26_9944_4783_B9E0_6ED2F559314E__INCLUDED_)
