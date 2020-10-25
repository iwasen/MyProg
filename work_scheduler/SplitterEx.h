#if !defined(AFX_SPLITTEREX_H__D5F33B9F_34F0_4274_987D_DD4BF655BA72__INCLUDED_)
#define AFX_SPLITTEREX_H__D5F33B9F_34F0_4274_987D_DD4BF655BA72__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SplitterEx.h : ヘッダー ファイル
//

#include "ActiveFrameWnd.h"

/////////////////////////////////////////////////////////////////////////////
// CSplitterEx ウィンドウ

class CSplitterEx : public CSplitterWnd
{
// コンストラクション
public:

// アトリビュート
public:

// オペレーション
public:
	void ChangeSplit(CPoint point);
	BOOL IsActivePane(CWnd *pView);

// オーバーライド
	virtual void RecalcLayout();
	// ClassWizard は仮想関数のオーバーライドを生成します。

	//{{AFX_VIRTUAL(CSplitterEx)
	//}}AFX_VIRTUAL

// インプリメンテーション
public:

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CSplitterEx)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void DisplayActiveFrame();

	CActiveFrameWnd m_wndLeft;
	CActiveFrameWnd m_wndTop;
	CActiveFrameWnd m_wndRight;
	CActiveFrameWnd m_wndBottom;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_SPLITTEREX_H__D5F33B9F_34F0_4274_987D_DD4BF655BA72__INCLUDED_)
