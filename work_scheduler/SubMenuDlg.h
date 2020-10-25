#if !defined(AFX_SUBMENUDLG_H__8B65B23F_73BE_4A11_9462_FAEDBDBD947A__INCLUDED_)
#define AFX_SUBMENUDLG_H__8B65B23F_73BE_4A11_9462_FAEDBDBD947A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SubMenuDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CSubMenuDlg ダイアログ

class CSubMenuDlg : public CDialogEx
{
// コンストラクション
public:
	CSubMenuDlg(UINT nID, CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSubMenuDlg)
	enum { IDD = IDD_MANUAL_BAR };
		// メモ: ClassWizard はこの位置にデータ メンバを追加します。
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CSubMenuDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CSubMenuDlg)
	afx_msg void OnShowWindow(BOOL bShow, UINT nStatus);
	//}}AFX_MSG
	virtual void OnOK();
	virtual void OnCancel();
	DECLARE_MESSAGE_MAP()

public:
	virtual void EnableMenuButton();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_SUBMENUDLG_H__8B65B23F_73BE_4A11_9462_FAEDBDBD947A__INCLUDED_)
