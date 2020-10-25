#pragma once

// PictRotateDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CPictRotateDlg ダイアログ

class CPictRotateDlg : public CDialog
{
// コンストラクション
public:
	CPictRotateDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	enum { IDD = IDD_PICT_ROTATE };
	int		m_iRotate;


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	virtual void OnOK();
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()

public:
	int m_nRotate;
};
