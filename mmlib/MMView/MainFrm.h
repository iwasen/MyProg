// MainFrm.h : CMainFrame クラスの宣言およびインターフェイスの定義をします。
//
/////////////////////////////////////////////////////////////////////////////

#if !defined(AFX_MAINFRM_H__95FF6439_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
#define AFX_MAINFRM_H__95FF6439_EE22_11D2_8C18_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CMyToolBar : public CToolBar
{
public:
	void DrawGripper(CDC* pDC, const CRect& rect);
	void EraseNonClient();
	virtual void DoPaint(CDC* pDC);

protected:

	afx_msg void OnNcPaint();
	
	DECLARE_MESSAGE_MAP()
};

class CMainFrame : public CFrameWnd
{
	
protected: // シリアライズ機能のみから作成します。
	CMainFrame();
	DECLARE_DYNCREATE(CMainFrame)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	public:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	protected:
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);

// インプリメンテーション
public:
	virtual ~CMainFrame();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:  // コントロール バー用メンバ
	CStatusBar  m_wndStatusBar;
	CMyToolBar  m_wndToolBar;
	CComboBox	m_wndComboBox;

// 生成されたメッセージ マップ関数
protected:
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnClose();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnUpdateInfo(CCmdUI *pCmdUI);
	afx_msg void OnUpdatePage(CCmdUI *pCmdUI);
	afx_msg LRESULT OnEnterSizeMove(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

public:
	CComboBox &GetComboBox() {return m_wndComboBox;}
	void SwitchView(UINT nIndex);
	void ShowTextView(BOOL bShow);

private:
	CView *m_pViews[2];
	UINT m_nViewIndex;
	CSplitterWnd m_wndSplitter;
	int m_nHeight;
	BOOL m_bShowText;
	int m_nFixedHeight;
	int m_nClientHeight;
};

/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MAINFRM_H__95FF6439_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
