#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// camera.h : header file
//

#define	WM_SETPANTILT	(WM_USER+1401)
#define	WM_SETZOOM	(WM_USER+1402)
#define	WM_POINTWND	(WM_USER+1403)

/////////////////////////////////////////////////////////////////////////////
// CCameraButton window

class CCameraButton : public CBitmapButton
{
// Construction
public:
	CCameraButton();

// Attributes
public:

// Operations
public:

// Overrides

	//{{AFX_VIRTUAL(CCameraButton)
	//}}AFX_VIRTUAL

// Implementation
public:
	virtual ~CCameraButton();

	// Generated message map functions
protected:
	//{{AFX_MSG(CCameraButton)
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// CCameraFrame window

class CCameraFrame : public CWnd
{
// Construction
public:
	CCameraFrame();

	//{{AFX_VIRTUAL(CCameraFrame)
	//}}AFX_VIRTUAL

// Implementation
public:
	virtual ~CCameraFrame();

	// Generated message map functions
protected:
	//{{AFX_MSG(CCameraFrame)
	afx_msg BOOL OnEraseBkgnd(CDC* pDC);
	afx_msg void OnPaint();
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void CalcFramePoint(int, int, CPoint &);
	void CalcFrameSize(int, CSize &);
	void CalcFrameRect(int, int, int, CRect &);
	long CalcPanTilt(const CPoint &);
	int CalcZoom(const CSize &);
	int CheckCursorPos(const CPoint &);
	void CalcMovePoint(const CPoint &, CPoint &);
	void CalcMoveSize(const CPoint &, CSize &);
	void DrawMoveFrame(const CPoint &, const CSize &, int);

	CCameraWnd *m_pCameraWnd;
	CBrush m_brushView;
	CBrush m_brushFrame;
	CBrush m_brushFrameBusy;
	CPen	m_penCenterLine;
	CPen	m_penFrame;
	CPen	m_penPreset;
	HCURSOR	m_hCursorArrow;
	HCURSOR	m_hCursorOpenHand;
	HCURSOR	m_hCursorCloseHand;
	HCURSOR	m_hCursorHMove;
	HCURSOR	m_hCursorVMove;
	int	m_clickMode;
	CPoint	m_clickFramePoint;
	CSize	m_clickFrameSize;
	CPoint	m_clickCursorPoint;
	CRect	m_prevFrameRect;
};

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// CToggleButton window

class CToggleButton : public CButton
{
// Construction
public:
	CToggleButton();

// Implementation
public:
	virtual ~CToggleButton();

	// Generated message map functions
protected:
	//{{AFX_MSG(CToggleButton)
	afx_msg void OnLButtonUp(UINT nFlags, CPoint point);
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg void OnKillFocus(CWnd* pNewWnd);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	WNDPROC *GetSuperWndProcAddr();

	friend	class CCameraWnd;

public:
	BOOL	m_bPreset;
};

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// CCameraWnd dialog

class CCameraWnd : public CDialog
{
// Construction
public:
	CCameraWnd(CWnd* pParent = NULL);

// Dialog Data
	//{{AFX_DATA(CCameraWnd)
	enum { IDD = IDD_CAMERA };
	//}}AFX_DATA

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CCameraWnd)
	//}}AFX_VIRTUAL

protected:
	CBitmapButton m_btnHome;
	CCameraButton m_btnUp, m_btnDown, m_btnLeft, m_btnRight;
	CCameraButton m_btnTele, m_btnWide;
	CCameraButton m_btnFar, m_btnNear;
	CToggleButton m_btnPreset;
	CCameraFrame m_frame;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	virtual void PostNcDestroy();

// Implementation
protected:

	// Generated message map functions
	//{{AFX_MSG(CCameraWnd)
	afx_msg void OnClose();
	virtual void OnOK();
	virtual void OnCancel();
	virtual BOOL OnInitDialog();
	afx_msg void OnCameraHome();
	afx_msg void OnCameraStop();
	afx_msg void OnCameraUp();
	afx_msg void OnCameraDown();
	afx_msg void OnCameraLeft();
	afx_msg void OnCameraRight();
	afx_msg void OnCameraTele();
	afx_msg void OnCameraWide();
	afx_msg void OnCameraZoomStop();
	afx_msg void OnCameraFar();
	afx_msg void OnCameraNear();
	afx_msg void OnCameraStopFocus();
	afx_msg LONG OnSetPanTilt(UINT, LONG);
	afx_msg LONG OnSetZoom(UINT, LONG);
	afx_msg LONG OnPointWnd(UINT, LONG);
	afx_msg void OnCameraAuto();
	afx_msg void OnCameraManual();
	afx_msg void OnCameraPs1();
	afx_msg void OnCameraPs2();
	afx_msg void OnCameraPs3();
	afx_msg void OnCameraPs4();
	afx_msg void OnCameraPs5();
	afx_msg void OnCameraPs6();
	afx_msg void OnCameraPs7();
	afx_msg void OnCameraPs8();
	afx_msg void OnCameraOption();
	afx_msg void OnTimer(UINT nIDEvent);
	afx_msg BOOL OnSetCursor(CWnd* pWnd, UINT nHitTest, UINT message);
	afx_msg void OnCameraLock();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	friend class CCameraFrame;
	friend class CCameraDlg;
	friend class CToggleButton;
	friend class CPresetDlg;

public:
	void SetVideoWindow(CVideoWnd *);
	void SetCameraStatus(const CAMERA_STATUS &);
private:
	void OpenCamera();
	void CloseCamera();
	BOOL SendCommand(const CAMERA_COMMAND &);
	void SendCameraInfo(int);
	void DispStatus(int, const char * = NULL);
	void EnableControl(BOOL);
	void EnablePresetButton();
	void Preset(int);

	CVideoWnd *m_pVideoWnd;
	BOOL	m_bEnable;
	BOOL	m_bEnablePos;
	int	m_panPos;
	int	m_tiltPos;
	int	m_zoomPos;
	int	m_msgNo;
	int	m_currentPresetNo;
	UINT	m_nTimerID;
	int	m_nTimerCounter;
	BOOL	m_bBusy;
	BOOL	m_bInfo;
	VCC1DATA m_vcc1;
};

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// CCameraDlg dialog

class CCameraDlg : public CDialog
{
// Construction
public:
	CCameraDlg(CWnd* pParent = NULL);	// standard constructor

// Dialog Data
	//{{AFX_DATA(CCameraDlg)
	enum { IDD = IDD_CAMERA_OPTION };
		// NOTE: the ClassWizard will add data members here
	//}}AFX_DATA


// Overrides
public:

protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV support

// Implementation
protected:

	// Generated message map functions
	//{{AFX_MSG(CCameraDlg)
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	afx_msg void OnHScroll(UINT, UINT, CScrollBar*);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int CalcScrollPos(int, int, int, int, CScrollBar*);
};
/////////////////////////////////////////////////////////////////////////////
// CPresetDlg dialog

class CPresetDlg : public CDialog
{
// Construction
public:
	CPresetDlg(CWnd* pParent = NULL, int presetNo = 0);

// Dialog Data
	//{{AFX_DATA(CPresetDlg)
	enum { IDD = IDD_CAMERA_PRESET };
		// NOTE: the ClassWizard will add data members here
	//}}AFX_DATA


// Overrides
public:

protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV support

// Implementation
protected:

	// Generated message map functions
	//{{AFX_MSG(CPresetDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDelete();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	CCameraWnd *m_pCameraWnd;
	int	m_presetNo;
};
/////////////////////////////////////////////////////////////////////////////
