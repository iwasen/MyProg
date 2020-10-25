#pragma once

// OptFolder.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// COptFolder ダイアログ

class COptFolder : public CPropertyPage
{
	DECLARE_DYNCREATE(COptFolder)

// コンストラクション
public:
	COptFolder();
	~COptFolder();

// ダイアログ データ
	enum { IDD = IDD_OPT_FOLDER };
	CString	m_sBackupFolder;
	CString	m_sCacheFolder;
	CString	m_sLibraryFolder;
	CString	m_sInboxFolder;
	CString	m_sKeywordFolder;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnLibraryReference();
	afx_msg void OnCacheReference();
	afx_msg void OnBackupReference();
	afx_msg void OnDefault();
	afx_msg void OnInboxReference();
	afx_msg void OnKeywordReference();
	DECLARE_MESSAGE_MAP()

};
