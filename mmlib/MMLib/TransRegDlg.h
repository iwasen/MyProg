#pragma once

// TransRegDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CTransRegDlg ダイアログ

class CTransRegDlg : public CDialog
{
// コンストラクション
public:
	CTransRegDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_TRANS_REG };
	CLixxxtrl	m_cAppList;
	CString	m_sAppFile;
	CString	m_sAppName;
	int		m_iMenuIcon;
	BOOL	m_bPictBMP;
	BOOL	m_bPictGIF;
	BOOL	m_bPictJPG;
	BOOL	m_bPictPNG;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDelete();
	afx_msg void OnClose();
	afx_msg void OnReference();
	afx_msg void OnItemchangedAppList(NMHDR* pNMHDR, LRESULT* pResult);
	DECLARE_MESSAGE_MAP()

private:
	void SetAppList();
};
