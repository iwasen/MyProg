// setting.h : ヘッダー ファイル
//


/////////////////////////////////////////////////////////////////////////////
// CSettingDlg ダイアログ

class CSettingDlg : public CDialog
{
// コンストラクタ
public:
	CSettingDlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSettingDlg)
	enum { IDD = IDD_SETTING };
	CComboBox	m_cVoiceOutputDevice;
	CComboBox	m_cVoiceInputDevice;
	int		m_iVoiceBufferNum;
	//}}AFX_DATA

// インプリメンテーション
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート

	// メッセージマップ関数の生成
	//{{AFX_MSG(CSettingDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnClickedMaxspeed();
	afx_msg void OnClickedResize();
	afx_msg void OnClickedFixedsize();
	afx_msg void OnLicense();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	int	Open(SETTINGS *, LPCTSTR);

private:
	CString	caption;

	void InitVoiceSetting();
};

/////////////////////////////////////////////////////////////////////////////
// CSetHostDlg ダイアログ

class CSetHostDlg : public CDialog
{
// コンストラクタ
public:
	CSetHostDlg(CWnd* pParent = NULL);	// 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSetHostDlg)
	enum { IDD = IDD_SETHOST };
		// 注意: ClassWizard はこの位置にデータメンバを追加します。
	//}}AFX_DATA

// インプリメンテーション
protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV サポート

	// メッセージマップ関数の生成
	//{{AFX_MSG(CSetHostDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnClickedAppend();
	afx_msg void OnClickedChange();
	afx_msg void OnClickedDelete();
	afx_msg void OnDblclkHostlist();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void DispHostData();
};
