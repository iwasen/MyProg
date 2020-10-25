// call.h : ヘッダー ファイル
//


/////////////////////////////////////////////////////////////////////////////
// CCallWnd ダイアログ

class CCallWnd : public CDialog
{
// コンストラクタ
public:
	CCallWnd(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CCallWnd)
	enum { IDD = IDD_CALL };
		// 注意: ClassWizard はこの位置にデータメンバを追加します。
	//}}AFX_DATA

protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	virtual void PostNcDestroy();

	// メッセージマップ関数の生成
	//{{AFX_MSG(CCallWnd)
	virtual BOOL OnInitDialog();
	afx_msg void OnClose();
	virtual void OnCancel();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
/////////////////////////////////////////////////////////////////////////////
// CAnswerWnd ダイアログ

class CAnswerWnd : public CDialog
{
// コンストラクタ
public:
	CAnswerWnd(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CAnswerWnd)
	enum { IDD = IDD_ANSWER };
		// 注意: ClassWizard はこの位置にデータメンバを追加します。
	//}}AFX_DATA

// インプリメンテーション
protected:
	virtual void DoDataExchange(CDataExchange* pDX);
	virtual void PostNcDestroy();

	// メッセージマップ関数の生成
	//{{AFX_MSG(CAnswerWnd)
	virtual BOOL OnInitDialog();
	afx_msg void OnClose();
	virtual void OnCancel();
	virtual void OnOK();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
