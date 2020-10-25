#pragma once

// CheckPwdDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CCheckPwdDlg ダイアログ

class CCheckPwdDlg : public CDialog
{
// コンストラクション
public:
	CCheckPwdDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_CHECK_PWD };
	CString	m_sLibrary;
	CString	m_sPassword;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	DECLARE_MESSAGE_MAP()
};
