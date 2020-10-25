#if !defined(AFX_VIDEO_H__4F85A741_BC10_11D2_8BC8_00104B939DF5__INCLUDED_)
#define AFX_VIDEO_H__4F85A741_BC10_11D2_8BC8_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// Video.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CVideoView ウィンドウ

class CVideoView : public CWnd
{
// コンストラクション
public:
	CVideoView();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	//{{AFX_VIRTUAL(CVideoView)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CVideoView();

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CVideoView)
	afx_msg void OnPaint();
	afx_msg void OnLButtonDblClk(UINT nFlags, CPoint point);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	void SetWindowSize(int nWidth, int nHeight);
};

/////////////////////////////////////////////////////////////////////////////
// CVideoWnd フレーム

class CVideoWnd : public CFrameWnd
{
	DECLARE_DYNCREATE(CVideoWnd)
protected:
	CVideoWnd();           // 動的生成に使用されるプロテクト コンストラクタ。

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CVideoWnd)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	virtual ~CVideoWnd();

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CVideoWnd)
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	//}}AFX_MSG
	afx_msg LRESULT OnExitSizeMove(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

protected:
	friend class CCameraWnd;
	friend class CVideoView;
	CVideoView m_wndVideoView;
	CStatusBar m_wndStatusBar;
	int	m_nWidth;
	int	m_nHeight;
	int	m_nColor;
	int	m_nFPS;
	int	m_nBlock;
	int	m_nBPS;
	int	m_nCompressRate;
	BOOL	m_bResize;
	BOOL	m_bFixedAspect;
	BOOL	m_bFixedSize;
	BOOL	m_bDispInfo;
	CCameraWnd *m_pCameraWnd;
	LPSTR	m_lpVideoBuf;
	PUSER	m_pUser;
	int m_nFrameWidth;
	int m_nFrameHeight;
	BOOL m_bSizeChanging;

	void	CreateVideoWindow(LPSTR pCaption, int left, int top, int nCmdShow = SW_SHOW);
	void	DrawVideoWindow();
	void	SetVideoSize(int, int, int);
	void	SetInitialWindowSize(int nWidth, int nHeight);
	void SetWindowSize(int nWidth, int nHeight);
};

/////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////
//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_VIDEO_H__4F85A741_BC10_11D2_8BC8_00104B939DF5__INCLUDED_)
