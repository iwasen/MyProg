#include <afxrich.h>

/////////////////////////////////////////////////////////////////////////////
// CChatWnd フレーム

class CChatWnd : public CFrameWnd
{
	DECLARE_DYNCREATE(CChatWnd)
public:
	CChatWnd();

protected:
	CSplitterWnd m_wndSplitter;
	CToolBar    m_wndToolBar;
	CStatusBar  m_wndStatusBar;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CChatWnd)
	protected:
	virtual BOOL OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	virtual ~CChatWnd();

	// メッセージ マップ関数の生成
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
// CLogFileDlg ダイアログ

class CLogFileDlg : public CDialog
{
// コンストラクタ
public:
	CLogFileDlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CLogFileDlg)
	enum { IDD = IDD_LOGFILE };
		// 注意: ClassWizard はこの位置にデータメンバを追加します。
	//}}AFX_DATA

// インプリメンテーション
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート

	// メッセージマップ関数の生成
	//{{AFX_MSG(CLogFileDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnClickedReference();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
/////////////////////////////////////////////////////////////////////////////
// CChatDispView ビュー

class CChatDispView : public CRichEditView
{
protected:
	CChatDispView();           // 動的生成に使用されるプロテクト コンストラクタ
	DECLARE_DYNCREATE(CChatDispView)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	//{{AFX_VIRTUAL(CChatDispView)
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	virtual ~CChatDispView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CChatDispView)
	afx_msg int OnCreate(LPCREATESTRUCT lpCreateStruct);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	void PutStr(LPCTSTR pUserName, LPCTSTR pMessage, COLORREF color);

};

/////////////////////////////////////////////////////////////////////////////
// CChatInputView ビュー

class CChatInputView : public CEditView
{
protected:
	CChatInputView();           // 動的生成に使用されるプロテクト コンストラクタ
	DECLARE_DYNCREATE(CChatInputView)

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	//{{AFX_VIRTUAL(CChatInputView)
	protected:
	virtual BOOL PreCreateWindow(CREATESTRUCT& cs);
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	virtual ~CChatInputView();
#ifdef _DEBUG
	virtual void AssertValid() const;
	virtual void Dump(CDumpContext& dc) const;
#endif

	// 生成されたメッセージ マップ関数
protected:
	//{{AFX_MSG(CChatInputView)
	afx_msg void OnChange();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
