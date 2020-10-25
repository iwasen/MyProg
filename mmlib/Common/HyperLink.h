#pragma once

// HyperLink.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CHyperLink ウィンドウ

class CHyperLink : public CStatic
{
// コンストラクション
public:
	CHyperLink();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

// インプリメンテーション
public:
	virtual ~CHyperLink();

	// 生成されたメッセージ マップ関数
protected:
	afx_msg HBRUSH CtlColor(CDC* pDC, UINT nCtlColor);
	afx_msg void OnClicked();
	afx_msg BOOL OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message);
	DECLARE_MESSAGE_MAP()

public:
	void SetHyperLinkURL(LPCTSTR pLinkURL, LPCTSTR pFontName = NULL, int nFontSize = 0);

protected:
	CString m_sLinkURL;
	CFont m_Font;
	CBrush m_brushBG;
};
