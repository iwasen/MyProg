// remote.h : ヘッダー ファイル
//


/////////////////////////////////////////////////////////////////////////////
// CRemoteDlg ダイアログ

class CRemoteDlg : public CDialog
{
// コンストラクタ
public:
	CRemoteDlg(CWnd* pParent = NULL, PUSER pUser = NULL);

// ダイアログ データ
	//{{AFX_DATA(CRemoteDlg)
	enum { IDD = IDD_REMOTE };
	CString	m_sDownLoadFile;
	//}}AFX_DATA

// インプリメンテーション
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート

	// メッセージマップ関数の生成
	//{{AFX_MSG(CRemoteDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnClickedRemoteChat();
	afx_msg void OnClickedRemoteVoice();
	afx_msg void OnClickedRemoteRestart();
	afx_msg void OnClickedRemoteExit();
	afx_msg void OnClickedRemoteSvoice();
	afx_msg void OnRemoteExec();
	afx_msg void OnRemoteDownload();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	PUSER	pUser;
};
