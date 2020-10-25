#pragma once

// OptImageEditor.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// COptImageEditor ダイアログ

class COptImageEditor : public CPropertyPage
{
	DECLARE_DYNCREATE(COptImageEditor)

// コンストラクション
public:
	COptImageEditor();
	~COptImageEditor();

// ダイアログ データ
	enum { IDD = IDD_OPT_IMAGE_EDITOR };
	CStatic	m_cImageEditorIcon;
	CString	m_sImageEditor;
	BOOL	m_bEditDblClick;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

	public:
	virtual void OnOK();
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:
	// 生成されたメッセージ マップ関数
	afx_msg void OnSelectFile();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

private:
	void DispIcon();
};
