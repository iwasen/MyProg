#pragma once

// AutoNumDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CAutoNumDlg ダイアログ

class CAutoNumDlg : public CDialog
{
// コンストラクション
public:
	CAutoNumDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_AUTO_NUMBERING };
	CEdit	m_cStep;
	CEdit	m_cCode;
	CString	m_sCode;
	long	m_lStep;
	BOOL	m_bAutoNumbering;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	afx_msg void OnAutoNumbering();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

private:
	void EnableControl();
};
