// license.h : ヘッダー ファイル
//


/////////////////////////////////////////////////////////////////////////////
// CLicenseWnd ダイアログ

class CLicenseWnd : public CDialog
{
// コンストラクタ
public:
	CLicenseWnd(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CLicenseWnd)
	enum { IDD = IDD_LICENSE };
		// 注意: ClassWizard はこの位置にデータメンバを追加します。
	//}}AFX_DATA

// インプリメンテーション
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート

	// メッセージマップ関数の生成
	//{{AFX_MSG(CLicenseWnd)
		// 注意: ClassWizard はこの位置にメンバ関数を追加します。
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};
