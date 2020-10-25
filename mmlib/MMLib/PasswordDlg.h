#pragma once

// PasswordDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CPasswordDlg ダイアログ

class CPasswordDlg : public CDialog
{
// コンストラクション
public:
	CPasswordDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_PASSWORD };
	CComboBox	m_cLibrary;
	CString	m_sPassword;
	CString	m_sPassword2;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSelchangeLibrary();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;

private:
	void SetPassword();
};
