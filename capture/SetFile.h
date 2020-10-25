// SetFile.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CSetFile ダイアログ

class CSetFile : public CDialog
{
// コンストラクション
public:
	CSetFile(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSetFile)
	enum { IDD = IDD_SET_FILE };
	CString	m_OutputFile;
	int		m_OutputRate;
	CString	m_OutputFile2;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数を生成しオーバーライドします。
	//{{AFX_VIRTUAL(CSetFile)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CSetFile)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSelectFile();
	afx_msg void OnSelectFile2();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
