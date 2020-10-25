#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualMoveDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualMoveDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "ManualEditDocument.h"
#include "LixxxtrlEx1.h"

// 工程/要素作業群の移動ダイアログ
class CManualMoveDlg : public CDialogEx
{
	// 作業者リストデータ
	struct SSagyoshaList {
		int nSublineId;
		int nSagyoshaId;
	};
	typedef CArrayEx <SSagyoshaList, SSagyoshaList&> CSagyoshaListArray;

	// リストボックス設定データ
	struct CListData {
		CString sName;		// 項目名
		double fTime;		// 作業時間
// Modify ... ( ADD )
		CString sKishuName;		// 機種ID
		int nKyotsuKoteiNo;		// 共通工程グループNo
// By Y.Itabashi (xxxxx) 2007.02.07
		int nId;			// 項目ID
		int nOrg;			// 移動前
		SGraphBlock *pGraphBlock;
		BOOL bMoveFalg;
	};
	typedef CArrayEx <CListData, CListData&> CListDataArray;

// コンストラクション
public:
	CManualMoveDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualMoveDlg)
	enum { IDD = IDD_MANUAL_MOVE };
	CButton	m_cButtonPreview;
	CButton	m_cButtonSettei;
	CStatic	m_cStaticTotalTime2;
	CStatic	m_cStaticTotalTime1;
	CComboBox	m_cComboSagyosha2;
	CComboBox	m_cComboSagyosha1;
// Modify ... ( CHANGE )
	CLixxxtrlEx		m_cList2;
	CLixxxtrlEx		m_cList1;
//	CLixxxtrlEx1	m_cList2;
//	CLixxxtrlEx1	m_cList1;
// By Y.Itabashi (xxxxx) 2007.02.07
	int		m_nRadioKotei;
	//}}AFX_DATA

	int m_nMode;					// 工程／要素作業群モード
	int m_nKishuId;					// 機種ID
// Modify ... ( ADD )
	int m_nGraphKishu;				// 表示機種ID
// By Y.Itabashi (xxxxx) 2007.02.26
	CString m_sSagyosha;			// 作業者名
	CManualEditDocument *m_pDoc;	// ドキュメントクラスへのポインタ
// Modify ... ( ADD )
	int m_nSublineIdFrom;			// 移動元サブライン
	int m_nSublineIdTo;			// 移動先サブライン
// By Y.Itabashi (xxxxx) 2007.02.07

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualMoveDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualMoveDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboSagyosha1();
	afx_msg void OnSelchangeComboSagyosha2();
	afx_msg void OnButtonSettei();
	afx_msg void OnButtonPreview();
	virtual void OnCancel();
	afx_msg void OnRadioKotei();
	afx_msg void OnRadioYosoSagyogun();
	afx_msg void OnItemchangedList1(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedList2(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnClickList1(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnClickList2(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	afx_msg LRESULT OnDragDrop(WPARAM wParam, LPARAM lParam);
	afx_msg void OnBeginDrag(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()

	BOOL m_bEdit;					// 変更有無フラグ
	CSagyoshaListArray m_aSagyoshaList;	// 作業者リストデータ
	CListDataArray m_aListData1;	// 左リストボックス設定データ配列
	CListDataArray m_aListData2;	// 右リストボックス設定データ配列
// Modify ... ( ADD )
	BOOL m_bSelectIvent;			// 選択処理モード
// By Y.Itabashi (xxxxx) 2007.02.07
// Modify ... ( ADD )
	BOOL m_bMove;					// 移動処理済フラグ
// By Y.Itabashi (xxxxx) 2007.02.12

// Modify ... ( CHANGE )
	void SetSagyoshaList();
	void SetListAll();
	void SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg);
//	void SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg);
	void SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg);
//	void SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg);
	void SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg);
//	void SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg);
	void SetListBox(CListDataArray &aListData, CLixxxtrlEx &cList);
//	void SetListBox(CListDataArray &aListData, CLixxxtrlEx1 &cList);
	void DispTotalTimeAll();
	void DispTotalTime(double fTime, CStatic &cStatic);
	double CalcTotalTime(CLixxxtrlEx &cList);
//	double CalcTotalTime(CLixxxtrlEx1 &cList);
	void EnableButtons();
	BOOL MoveCheck(BOOL bLimitCheck) ;
	void GetMoveData(CLixxxtrlEx &cList, CMoveDataArray &aMoveData);
//	void GetMoveData(CLixxxtrlEx1 &cList, CMoveDataArray &aMoveData);
//	void SetLixxxolumn(CLixxxtrlEx1 &cList);
	void MoveItem(HWND hWndDrag, HWND hWndDrop);
	void AutoKyotsuKoteiSelect( CLixxxtrlEx &cList, int Row );
	void AutoAutoKoteiSelect( CLixxxtrlEx &cList );
	BOOL CheckMoveIndex( CLixxxtrlEx &cList );
// By Y.Itabashi (xxxxx) 2007.02.07
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
