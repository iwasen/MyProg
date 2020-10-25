#pragma once

// RefNetDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CRefNetDlg ダイアログ

class CRefNetDlg : public CDialog
{
// コンストラクション
public:
	CRefNetDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_REF_NET };
	CListBox	m_cLibList;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnDblclkLiblist();
	DECLARE_MESSAGE_MAP()

public:
	CString m_sServerName;
	int m_nChType;
	CString m_sDirectory;

private:
	int m_nItem;
	CString m_sPath[20];

	BOOL SetLibList();
	void AddRefList(CPcserve &pcserve, CString &dirName);
};
