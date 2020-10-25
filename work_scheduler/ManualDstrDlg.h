#if !defined(AFX_MANUALDSTRDLG_H__B4D18366_86C9_414A_BCC2_1A9A765034E9__INCLUDED_)
#define AFX_MANUALDSTRDLG_H__B4D18366_86C9_414A_BCC2_1A9A765034E9__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualDstrDlg.h : ヘッダー ファイル
//

#include "DialogEx.h"
#include "ManualEditDocument.h"

/////////////////////////////////////////////////////////////////////////////
// CManualDstrDlg ダイアログ

class CManualDstrDlg : public CDialogEx
{
	typedef CArrayEx <SDSTRSanshutsu, SDSTRSanshutsu&> SDSTRDataArray;

// コンストラクション
public:
	CManualDstrDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualDstrDlg)
	enum { IDD = IDD_MANUAL_DSTR };
	CComboBox	m_cComboKishu;
	double	m_fKosu;
	double	m_fHyojunJikan;
	double	m_fTeikiTenkenJikan;
	double	m_fHinshitsuLoss;
	double	m_fSetsubiFuguaiLoss;
	double	m_fShujukuLoss;
	double	m_fLineTeishiLoss;
	double	m_fHenseiLoss;
	double	m_fHukugouNoritsuBun;
	double	m_fKanriTeishiJikan;
	double	m_fDST;
	double	m_fDSTR;
	//}}AFX_DATA

	CManualEditDocument *m_pDoc;	// ドキュメントクラスへのポインタ
	int m_nKishuId;					// 機種ID
	int m_bChangeEdit;				// 入力領域の変更の受入


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualDstrDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	SDSTRDataArray m_aDstr;	// DSTR算出データ

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualDstrDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboKishu();
	afx_msg void OnButtonCalc();
	virtual void OnOK();
	virtual void OnCancel();
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	void SetKishuList( int nKishu );
	void InitDstrInfo();
// Modify ... ( CHANGE )
	BOOL GetDstrInfo();
//	void GetDstrInfo();
// By Y.Itabashi (xxxxx) 2007.02.19
	void SetDstrInfo( int mode );
	double CalcKosu( SDSTRSanshutsu &pDstr );
	double CalcHyojunjikan( SDSTRSanshutsu &pDstr, int nKishuId );
	double CalcHenseiLoss( SDSTRSanshutsu &pDstr, int nKishuId );
	void OnChangeEdit( int nID ); 
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALDSTRDLG_H__B4D18366_86C9_414A_BCC2_1A9A765034E9__INCLUDED_)
