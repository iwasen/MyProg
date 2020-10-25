#pragma once

// BackupDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CBackupDlg ダイアログ

class CBackupDlg : public CDialog
{
// コンストラクション
public:
	CBackupDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_BACKUP };
	CButton	m_cDelete;
	CButton	m_cCancel;
	CLixxxtrl	m_cBackupList;
	CComboBox	m_cLibrary;
	CProgressCtrl	m_cProgress;

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
	afx_msg void OnColumnclickBackupList(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDestroy();
	DECLARE_MESSAGE_MAP()

public:
	CItemFolder *m_pItemFolder;

private:
	int m_nSortItem;
	int m_nSortDir;

	void SetBackupList();
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	void RemoveBackupItem();
};
