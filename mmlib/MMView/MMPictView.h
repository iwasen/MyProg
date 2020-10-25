// MMPictView.h : CMMPictView クラスの宣言およびインターフェイスの定義をします。
//
/////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_MMPICTVIEW_H__95FF643D_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
#define AFX_MMPICTVIEW_H__95FF643D_EE22_11D2_8C18_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000


class CMMPictView : public CScrollView
{
protected: // シリアライズ機能のみから作成します。
	CMMPictView();
	DECLARE_DYNCREATE(CMMPictView)

// アトリビュート
public:
	CMMViewDoc* GetDocument();

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	public:
	virtual void OnDraw(CDC* pDC);  // このビューを描画する際にオーバーライドされます。
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	virtual BOOL OnCmdMsg(UINT nID, int nCode, void* pExtra, AFX_CMDHANDLERINFO* pHandlerInfo);
	protected:
	virtual void OnInitialUpdate(); // 構築後の最初の１度だけ呼び出されます。
	virtual BOOL OnPreparePrinting(CPrintInfo* pInfo);
	virtual void OnBeginPrinting(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnEndPrinting(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnPrint(CDC* pDC, CPrintInfo* pInfo);
	virtual void OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint);

// インプリメンテーション
public:
	virtual ~CMMPictView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:

// 生成されたメッセージ マップ関数
protected:
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPictLeftRotate();
	afx_msg void OnUpdatePictLeftRotate(CCmdUI* pCmdUI);
	afx_msg void OnPictRightRotate();
	afx_msg void OnUpdatePictRightRotate(CCmdUI* pCmdUI);
	afx_msg void OnPictEnlarge();
	afx_msg void OnUpdatePictEnlarge(CCmdUI* pCmdUI);
	afx_msg void OnPictReduce();
	afx_msg void OnUpdatePictReduce(CCmdUI* pCmdUI);
	afx_msg void OnPictAutosize();
	afx_msg void OnUpdatePictAutosize(CCmdUI* pCmdUI);
	afx_msg void OnPictAutosize2();
	afx_msg void OnUpdatePictAutosize2(CCmdUI* pCmdUI);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg BOOL OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message);
	afx_msg void OnUpdateCombo(CCmdUI* pCmdUI);
	afx_msg void OnEditCopy();
	afx_msg void OnUpdateEditCopy(CCmdUI* pCmdUI);
	afx_msg void OnSelchangeCombo();
	afx_msg void OnEditchangeCombo();
	DECLARE_MESSAGE_MAP()

private:
	double m_fPictRatio;
	SIZE m_sizeDraw;
	CBrush m_BrushBkgndPict;
	CBrush m_BrushBkgndIcon;
	int m_nAutoSize;
	BOOL m_bScrollPicture;
	CPoint m_posPicture;
	BOOL m_bIgnoreUpdate;

	void DrawItems();
	void DrawPicture(CDC *pDC);
	void DrawIcon(CDC *pDC);
	int GetItemByPoint(CPoint point);
	void SetPictScrollSize();
};

#ifndef _DEBUG  // MMPictView.cpp ファイルがデバッグ環境の時使用されます。
inline CMMViewDoc* CMMPictView::GetDocument()
   { return (CMMViewDoc*)m_pDocument; }
#endif

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MMPICTVIEW_H__95FF643D_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
