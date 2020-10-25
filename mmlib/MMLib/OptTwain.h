#pragma once

// OptTwain.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// COptTwain ダイアログ

class COptTwain : public CPropertyPage
{
	DECLARE_DYNCREATE(COptTwain)

// コンストラクション
public:
	COptTwain();
	~COptTwain();

// ダイアログ データ
	enum { IDD = IDD_OPT_TWAIN };
	CString	m_sTwainDriver;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnSelectDriver();
	DECLARE_MESSAGE_MAP()

};
