#pragma once

// PermissionDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CPermissionDlg ダイアログ

class CPermissionDlg : public CDialog
{
// コンストラクション
public:
	CPermissionDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_PERMISSION };
	CComboBox	m_cLibrary;
	int		m_iPermission;


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
	DWORD m_dwPermission;

private:
	void SetRadioButton();
	void GetRadioButton();
};
