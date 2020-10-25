#pragma once

// MakeIndexDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CMakeIndexDlg ダイアログ

class CMakeIndexDlg : public CDialog
{
// コンストラクション
public:
	CMakeIndexDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MAKE_INDEX };
	CListBox	m_cLibraryList;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnAllSelect();
	DECLARE_MESSAGE_MAP()

public:
	CMMLibDoc *m_pDoc;

protected:
	int SetLibraryListBox();
	BOOL CreateAllIndex();
	BOOL CreateKeywordIndex(CPcserve &pcserve, CPSDB &db);
	BOOL WriteKeywordIndex(CItemFolder *pItemFolder, CPSDB &db);
	BOOL WriteSub(CPSDB *pDB, CPSDB &db, char type, LPCTSTR pLibraryID, LPCTSTR pLibraryName);
};
