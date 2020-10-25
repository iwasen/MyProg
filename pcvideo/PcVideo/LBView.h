#if !defined(AFX_LBVIEW_H__4E0CC7F3_BB11_11D2_8BC7_00104B939DF5__INCLUDED_)
#define AFX_LBVIEW_H__4E0CC7F3_BB11_11D2_8BC7_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LBView.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CLBView ウィンドウ

class CLBView : public CListBox
{
// コンストラクション
public:
	CLBView();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CLBView)
	public:
	virtual void DrawItem(LPDRAWITEMSTRUCT lpDrawItemStruct);
	virtual void MeasureItem(LPMEASUREITEMSTRUCT lpMeasureItemStruct);
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CLBView();

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CLBView)
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()

private:
	CBrush m_brushWindow;
	CBrush m_brushHighLight;
	CPen m_penWindowText;
	CPen m_penHighLightText;
	CBitmap m_bitmapVideo;
	CBitmap m_bitmapVideoMask;
	CBitmap m_bitmapChat;
	CBitmap m_bitmapChatMask;
	CBitmap m_bitmapVoice;
	CBitmap m_bitmapVoiceMask;
	CBitmap m_bitmapReflect;
	CBitmap m_bitmapReflectMask;
	CBitmap m_bitmapDll;
	CBitmap m_bitmapDllMask;
	CBitmap m_bitmapNone;
	CBitmap m_bitmapNoneMask;
	CDC m_dc;
	int	m_itemHeight;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_LBVIEW_H__4E0CC7F3_BB11_11D2_8BC7_00104B939DF5__INCLUDED_)
