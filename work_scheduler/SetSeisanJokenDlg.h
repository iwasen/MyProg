#if !defined(AFX_SETSEISANJOKENDLG_H__9B8C18E9_5DF2_4914_A521_3017390251BA__INCLUDED_)
#define AFX_SETSEISANJOKENDLG_H__9B8C18E9_5DF2_4914_A521_3017390251BA__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// SetSeisanJokenDlg.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CSetSeisanJokenDlg ダイアログ

class CSetSeisanJokenDlg : public CDialogEx
{
// コンストラクション
public:
	CSetSeisanJokenDlg(UINT nID, CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSetSeisanJokenDlg)
		// メモ: ClassWizard はこの位置にデータ メンバを追加します。
	//}}AFX_DATA
	CComboBox	m_cComboMonth;
	CComboBox	m_cComboYear;
	CSpreadEx	m_cSpread;
	CLixxxtrlEx	m_cList;
	CMyEdit	m_aEditKishu[MAX_KISHU];
	CMyEdit	m_aEditDaisu[MAX_KISHU];


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CSetSeisanJokenDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CSetSeisanJokenDlg)
	afx_msg void OnSize(UINT nType, int cx, int cy);
	//}}AFX_MSG
	virtual void OnOK();
	DECLARE_MESSAGE_MAP()

public:
	struct SSpreadDataCheck {
		int nCol;
		CDataCheck cDataCheck;
	};

protected:
	CString m_sYear;				// 対象年
	CString m_sMonth;				// 対象月

	void SetYearCombo();
	void SetMonthCombo();
	void SetKishuName();
	void SetSeisanDaisu();
	BOOL CheckDataInputSub(SSpreadDataCheck aDataCheck[], int nDataCheck);
	int GetSeisanDaisu(int nSublineId);
	void SetIkoHanei(int nCol);
	void SaveYMDaisu();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_SETSEISANJOKENDLG_H__9B8C18E9_5DF2_4914_A521_3017390251BA__INCLUDED_)
