#pragma once

// PictureWnd.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CPictureWnd ウィンドウ

class CPictureWnd : public CWnd
{
// コンストラクション
public:
	CPictureWnd();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。


// インプリメンテーション
public:
	virtual ~CPictureWnd();

	// 生成されたメッセージ マップ関数
protected:
	afx_msg void OnPaint();
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	DECLARE_MESSAGE_MAP()

public:
	void DrawDIB(HDIB hDIB);

private:
	HDIB m_hDIB;
	short m_nWidth, m_nHeight;
	short m_nLeft, m_nTop;
	CBrush m_BrushBkgnd;
};
