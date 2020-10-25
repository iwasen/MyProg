// CaptView.h : CCaptureView �N���X�̐錾����уC���^�[�t�F�C�X�̒�`�����܂��B
//
/////////////////////////////////////////////////////////////////////////////

class CCaptureView : public CView
{
protected: // �V���A���C�Y�@�\�݂̂���쐬���܂��B
	CCaptureView();
	DECLARE_DYNCREATE(CCaptureView)

// �A�g���r���[�g
public:
	CCaptureDoc* GetDocument();

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��𐶐����I�[�o�[���C�h���܂��B
	//{{AFX_VIRTUAL(CCaptureView)
	public:
	virtual void OnDraw(CDC* pDC);  // ���̃r���[��`�悷��ۂɃI�[�o�[���C�h����܂��B
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	protected:
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CCaptureView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

protected:

// �������ꂽ���b�Z�[�W �}�b�v�֐�
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

#ifndef _DEBUG  // CaptView.cpp �t�@�C�����f�o�b�O���̎��g�p����܂��B
inline CCaptureDoc* CCaptureView::GetDocument()
   { return (CCaptureDoc*)m_pDocument; }
#endif

/////////////////////////////////////////////////////////////////////////////

