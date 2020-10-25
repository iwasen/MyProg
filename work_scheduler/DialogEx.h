#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		Dialog.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CDialogEx クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.12.04 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "SpreadEx.h"
#include "MyCtrl.h"
#include "DataCheck.h"
#include "LixxxtrlEx.h"
#include "MenuDispCtrl.h"

/////////////////////////////////////////////////////////////////////////////
// CDialogEx ダイアログ

class CDialogEx : public CDialog
{
	struct SDlgCtlPosition {
		UINT nID;
		CRect rectCtl;
		double fLeft;
		double fTop;
		double fRight;
		double fBottom;
	};
	typedef CArray <SDlgCtlPosition, SDlgCtlPosition&> CDlgCtlPositionArray;

	struct SEditDataCheck {
		LPCTSTR pItemName;
		UINT nID;
		CDataCheck cDataCheck;
	};

// コンストラクション
public:
	CDialogEx(UINT nID, CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CDialogEx)
		// メモ: ClassWizard はこの位置にデータ メンバを追加します。
	//}}AFX_DATA


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CDialogEx)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CDialogEx)
	virtual BOOL OnInitDialog();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnGetMinMaxInfo(MINMAXINFO FAR* lpMMI);
	afx_msg void OnSetFocus(CWnd* pNewWnd);
	//}}AFX_MSG
	afx_msg long OnExitSizeMove(WPARAM wParam, LPARAM lParam);
// Modify ... ( ADD )
	afx_msg LRESULT OnEditError(WPARAM wp, LPARAM lp);
// By Y.Itabashi (xxxxx) 2007.03.05
	DECLARE_MESSAGE_MAP()

public:
	void SetCtlPosition(UINT nID, double fLeft, double fTop, double fRight, double fBottom);
	void SetMinSize(int nWidth, int nHeight);

protected:
	CDlgCtlPositionArray m_aDlgCtlPosition;
	CSize m_sizeDlg;
	CSize m_sizeMin;
	BOOL m_bSizeChange;
// Modify ... ( ADD )
	BOOL m_bCloseLock;
// By Y.Itabashi (xxxxx) 2007.03.05

	void SetSpreadColumn(CSpreadSheet &cSpread);
	void SetLixxxolumn( CLixxxtrlEx &cList, MENUCTRLDATA *MenuCtrlData, LIxxxTRLDATA *LixxxtrlData, int SelectMode );
	BOOL EditDataCheck(CDataCheck &cDataCheck, CWnd &cEdit, LPCTSTR pItemName);
	BOOL EditDataCheckSub(CDataCheck &cDataCheck, CWnd &cEdit, LPCTSTR pItemName);
	BOOL ComboDataCheck(CComboBox &cComboBox, LPCTSTR pItemName);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
