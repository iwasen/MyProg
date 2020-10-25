#pragma once

// OptFont.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// COptFont ダイアログ

class COptFont : public CPropertyPage
{
	DECLARE_DYNCREATE(COptFont)

// コンストラクション
public:
	COptFont();
	~COptFont();

// ダイアログ データ
	enum { IDD = IDD_OPT_FONT };
	int		m_iFontSize;
	int		m_iViewFontSize;
	CString	m_sViewFontName;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	virtual BOOL OnInitDialog();
	afx_msg void OnViewFontChange();
	afx_msg void OnDefault();
	DECLARE_MESSAGE_MAP()

public:
	BOOL m_bChangeViewFont;
};
