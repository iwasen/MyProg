#pragma once

// RestoreDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CRestoreDlg ダイアログ

class CRestoreDlg : public CDialog
{
// コンストラクション
public:
	CRestoreDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_RESTORE };
	CButton	m_cCancel;
	CProgressCtrl	m_cProgress;
	CButton	m_cDelete;
	CLixxxtrl	m_cBackupList;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnColumnclickBackupList(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnDelete();
	afx_msg void OnDestroy();
	DECLARE_MESSAGE_MAP()

private:
	int m_nSortItem;
	int m_nSortDir;

	void SetBackupList();
	static int CALLBACK CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort);
	CMMPcsDB *CreateRestoreLibrary();
	void RemoveBackupItem();
};
