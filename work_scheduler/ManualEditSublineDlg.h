#if !defined(AFX_MANUALEDITSUBLINEDLG_H__A7B3B7EC_9F36_4DD0_BA70_1F90D21D2D42__INCLUDED_)
#define AFX_MANUALEDITSUBLINEDLG_H__A7B3B7EC_9F36_4DD0_BA70_1F90D21D2D42__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditSublineDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CManualEditSublineDlg ダイアログ

class CManualEditSublineDlg : public CDialogEx
{
// コンストラクション
public:
	CManualEditSublineDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualEditSublineDlg)
	enum { IDD = IDD_MANUAL_SUBLINE };
	CButton	m_cButtonOK;
	//}}AFX_DATA
	CMyButton	m_aCheckSubline[MAX_SUBLINE];


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditSublineDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditSublineDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	//}}AFX_MSG
	afx_msg void OnCheckSubline();
	DECLARE_MESSAGE_MAP()

	void EnableExecButton();
	void SetAllSublixxxheck(BOOL bCheck);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALEDITSUBLINEDLG_H__A7B3B7EC_9F36_4DD0_BA70_1F90D21D2D42__INCLUDED_)
