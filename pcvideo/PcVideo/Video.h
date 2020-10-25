#if !defined(AFX_VIDEO_H__4F85A741_BC10_11D2_8BC8_00104B939DF5__INCLUDED_)
#define AFX_VIDEO_H__4F85A741_BC10_11D2_8BC8_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// Video.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CVideoView �E�B���h�E

class CVideoView : public CWnd
{
// �R���X�g���N�V����
public:
	CVideoView();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CVideoView)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
public:
	virtual ~CVideoView();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
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
// CVideoWnd �t���[��

class CVideoWnd : public CFrameWnd
{
	DECLARE_DYNCREATE(CVideoWnd)
protected:
	CVideoWnd();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^�B

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVideoWnd)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	virtual ~CVideoWnd();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
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
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VIDEO_H__4F85A741_BC10_11D2_8BC8_00104B939DF5__INCLUDED_)
