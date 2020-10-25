#include <afxrich.h>

/////////////////////////////////////////////////////////////////////////////
// CChatWnd �t���[��

class CChatWnd : public CFrameWnd
{
	DECLARE_DYNCREATE(CChatWnd)
public:
	CChatWnd();

protected:
	CSplitterWnd m_wndSplitter;
	CToolBar    m_wndToolBar;
	CStatusBar  m_wndStatusBar;

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CChatWnd)
	protected:
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	virtual ~CChatWnd();

	// ���b�Z�[�W �}�b�v�֐��̐���
	//{{AFX_MSG(CChatWnd)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnExit();
	afx_msg void OnFont();
	afx_msg void OnLogFile();
	afx_msg void OnChatCall();
	afx_msg void OnDestroy();
	afx_msg void OnFileSave();
	afx_msg void OnUpdateFileSave(CCmdUI* pCmdUI);
	//}}AFX_MSG
	afx_msg LRESULT OnEnterSizeMove(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

public:
	class CChatDispView *m_pDisplayView;
	class CChatInputView *m_pInputView;

	void	OpenLogFile();
	void	CloseLogFile();
	void	DisplayStr(char *, char *, COLORREF color);

private:
	CFont	m_font;
	FILE	*m_fpLog;
	int m_nDisplayViewHeight;
	int m_nClientHeight;
};

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// CLogFileDlg �_�C�A���O

class CLogFileDlg : public CDialog
{
// �R���X�g���N�^
public:
	CLogFileDlg(CWnd* pParent = NULL);	// �W���̃R���X�g���N�^

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CLogFileDlg)
	enum { IDD = IDD_LOGFILE };
		// ����: ClassWizard �͂��̈ʒu�Ƀf�[�^�����o��ǉ����܂��B
	//}}AFX_DATA

// �C���v�������e�[�V����
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV �T�|�[�g

	// ���b�Z�[�W�}�b�v�֐��̐���
	//{{AFX_MSG(CLogFileDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnClickedReference();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
/////////////////////////////////////////////////////////////////////////////
// CChatDispView �r���[

class CChatDispView : public CRichEditView
{
protected:
	CChatDispView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^
	DECLARE_DYNCREATE(CChatDispView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CChatDispView)
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	virtual ~CChatDispView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CChatDispView)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	void PutStr(LPCTSTR pUserName, LPCTSTR pMessage, COLORREF color);

};

/////////////////////////////////////////////////////////////////////////////
// CChatInputView �r���[

class CChatInputView : public CEditView
{
protected:
	CChatInputView();           // ���I�����Ɏg�p�����v���e�N�g �R���X�g���N�^
	DECLARE_DYNCREATE(CChatInputView)

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

	//{{AFX_VIRTUAL(CChatInputView)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	virtual ~CChatInputView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
protected:
	//{{AFX_MSG(CChatInputView)
	afx_msg void OnChange();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////