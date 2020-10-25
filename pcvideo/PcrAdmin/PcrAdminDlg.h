// PcrAdminDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CPcrAdminDlg dialog

class CPcrAdminDlg : public CDialog
{
// 構築
public:
	CPcrAdminDlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// Dialog Data
	//{{AFX_DATA(CPcrAdminDlg)
	enum { IDD = IDD_PCRADMIN_DIALOG };
		// メモ: この位置に ClassWizard によってデータ メンバが追加されます。
	//}}AFX_DATA

	// ClassWizard は仮想関数を生成しオーバーライドします。
	//{{AFX_VIRTUAL(CPcrAdminDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV のサポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:
	HICON m_hIcon;

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CPcrAdminDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

