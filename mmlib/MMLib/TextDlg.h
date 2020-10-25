#pragma once

// TextDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CTextDlg ダイアログ

class CTextDlg : public CDialog
{
// コンストラクション
public:
	CTextDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_TEXT };
	CEdit	m_cText;
	CString	m_sText;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnFont();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnCloseText();
	DECLARE_MESSAGE_MAP()

private:
	CFont m_Font;
	CString m_FontName;
	int m_nFontSize;
};
