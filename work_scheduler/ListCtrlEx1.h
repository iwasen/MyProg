#if !defined(AFX_LIxxxTRLEX1_H__167DE98F_31A6_439A_8713_37F0AC055E3E__INCLUDED_)
#define AFX_LIxxxTRLEX1_H__167DE98F_31A6_439A_8713_37F0AC055E3E__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// LixxxtrlEx1.h : ヘッダー ファイル
//

#define WM_DRAG_DROP	(WM_USER + 200)

/////////////////////////////////////////////////////////////////////////////
// CLixxxtrlEx1 ウィンドウ

class CLixxxtrlEx1 : public CLixxxtrl
{
// コンストラクション
public:
	CLixxxtrlEx1();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CLixxxtrlEx1)
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CLixxxtrlEx1();

	BOOL m_bEnableDrag;
	void SetColumnText(int nColumn, LPCTSTR pText);
	void EnableDrag(BOOL bEnableDrag);

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CLixxxtrlEx1)
	afx_msg void OnBegindrag(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()

	enum		{NUM_BUTTONS = 9};
	CImageList	m_Image;
	CImageList* m_pDragImage;
	BOOL		m_bDragging;
	int			m_nDragIndex, m_nDropIndex;
	CWnd*		m_pDropWnd;

	void DropItemOnList (CLixxxtrl* pList);
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_LIxxxTRLEX1_H__167DE98F_31A6_439A_8713_37F0AC055E3E__INCLUDED_)
