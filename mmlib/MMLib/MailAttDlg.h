#pragma once

// MailAttDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CMailAttDlg ダイアログ

class CMailAttDlg : public CDialog
{
// コンストラクション
public:
	CMailAttDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MAIL_ATT };
	CMyEdit	m_cAttVPixel;
	CMyButton	m_cAttVLimit;
	CMyEdit	m_cAttScale;
	CMyEdit	m_cAttHPixel;
	CMyButton	m_cAttHLimit;
	int		m_iAttActual;

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnAttActual();
	afx_msg void OnAttReduction();
	afx_msg void OnAttHlimit();
	afx_msg void OnAttVlimit();
	DECLARE_MESSAGE_MAP()

private:
	void EnableControls();
};
