// CaptView.h : CCaptureView クラスの宣言およびインターフェイスの定義をします。
//
/////////////////////////////////////////////////////////////////////////////

class CCaptureView : public CView
{
protected: // シリアライズ機能のみから作成します。
	CCaptureView();
	DECLARE_DYNCREATE(CCaptureView)

// アトリビュート
public:
	CCaptureDoc* GetDocument();

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数を生成しオーバーライドします。
	//{{AFX_VIRTUAL(CCaptureView)
	public:
	virtual void OnDraw(CDC* pDC);  // このビューを描画する際にオーバーライドされます。
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	protected:
	//}}AFX_VIRTUAL

// インプリメンテーション
public:
	virtual ~CCaptureView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:

// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CCaptureView)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSetVideoFormat();
	afx_msg void OnSetVideoSource();
	afx_msg void OnUpdateSetVideoFormat(CCmdUI* pCmdUI);
	afx_msg void OnUpdateSetVideoSource(CCmdUI* pCmdUI);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnSetFile();
	afx_msg void OnModeAuto();
	afx_msg void OnModePreview();
	afx_msg void OnModeStop();
	afx_msg void OnUpdateModeAuto(CCmdUI* pCmdUI);
	afx_msg void OnUpdateModePreview(CCmdUI* pCmdUI);
	afx_msg void OnUpdateModeStop(CCmdUI* pCmdUI);
	afx_msg void OnSetDisplay();
	afx_msg void OnUpdateSetDisplay(CCmdUI* pCmdUI);
	afx_msg void OnTimer(UINT nIDEvent);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	void ResizeCaptureWindow();
	void SetMode();
	void PWMOpen();
	void PWMClose();

	HWND m_hCaptureWnd;
	CAPDRIVERCAPS m_CapDriverCaps;
	CAPSTATUS m_CapStatus;
	CBrush m_BrushBG;
	int m_Mode;
	UINT m_TimerID;
	HINSTANCE m_hModulePMW;
};

#ifndef _DEBUG  // CaptView.cpp ファイルがデバッグ環境の時使用されます。
inline CCaptureDoc* CCaptureView::GetDocument()
   { return (CCaptureDoc*)m_pDocument; }
#endif

/////////////////////////////////////////////////////////////////////////////

