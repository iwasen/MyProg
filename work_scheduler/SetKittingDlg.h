#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		SetKittingDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSetKittingDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "SpreadEx.h"
#include "LixxxtrlEx.h"

// キット化設定ダイアログ
class CSetKittingDlg : public CDialogEx
{
// コンストラクション
public:
	CSetKittingDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CSetKittingDlg)
	enum { IDD = IDD_SET_KITTING };
	CLixxxtrlEx	m_cListBuhin;
	CLixxxtrlEx	m_cListKotei;
	CLixxxtrlEx	m_cListYosoSagyogun;
	CLixxxtrlEx	m_cListYosoSagyo;
	CMyButton	m_cButtonOK;
	CMyEdit	m_cEditKitSaJikan;
	CMyEdit	m_cEditKitNashiGokeiJikan;
	CMyEdit	m_cEditKitAriGokeiJikan;
	CComboBox	m_cComboSubline;
//	CSpreadEx	m_cSpreadBuhin;
//	CSpreadEx	m_cSpreadKotei;
//	CSpreadEx	m_cSpreadYosoSagyo;
//	CSpreadEx	m_cSpreadYosoSagyogun;
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CSetKittingDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CSetKittingDlg)
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnSelchangeComboSubline();
	afx_msg void OnClickSpreadYosoSagyo(long Col, long Row);
	afx_msg void OnButtonNext();
	afx_msg void OnButtonBack();
	afx_msg void OnButtonZensentaku();
	afx_msg void OnButtonZenkaijo();
	afx_msg void OnButtonKittingTaishoBuhinhyo();
	virtual void OnCancel();
	afx_msg void OnItemchangedListBuhin(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListYosoSagyo(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	afx_msg LRESULT OnListExClick(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	struct SKoteiList {
		CString sKoteiName;
		BOOL bFuyoSagyo;
	};
	typedef CArrayEx2 <SKoteiList, SKoteiList&> CKoteiListArray;

	struct SYosoSagyogunList {
		CString sYosoSagyogunName;
		int nIndex;
		BOOL bFuyoSagyo;
	};
	typedef CArrayEx <SYosoSagyogunList, SYosoSagyogunList&> CYosoSagyogunListArray;

	int m_nSublineId;
	SKittingBuhin *m_pKittingBuhin;						// キット化部品データへのポインタ
	CString m_sKoteiName;								// 工程名
	int m_nKittingYosoSagyogunIndex;					// キット化要素作業群インデックス
	CIntArrayEx m_aKittingSublineId;					// キット化実行サブライン
	int	m_nSelectOff;									// 部品チェックフラグセットの一時無効

	void SelectBuhin(int nRow);
	void SelectKotei(int nRow);
	void SelectYosoSagyogun(int nRow);
	void SetBuhinList();
	void SetKoteiList(BOOL bInitFlag = TRUE);
	void SetYosoSagyogunList(BOOL bInitFlag = TRUE);
	void SetYosoSagyoList();
	void SetCheckAll(BOOL bCheck);
	void DispTotalTime(double fKitNashiJikan, double fKitAriJikan);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
