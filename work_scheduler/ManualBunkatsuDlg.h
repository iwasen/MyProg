#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ManualBunkatsuDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualBunkatsuDlg クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "ManualEditDocument.h"
#include "LixxxtrlEx1.h"

// 工程/要素作業群の分割削除ダイアログ
class CManualBunkatsuDlg : public CDialogEx
{
	// 作業者リストデータ
	struct SSagyoshaList {
		int nSublineId;
		int nSagyoshaId;
	};
	typedef CArrayEx <SSagyoshaList, SSagyoshaList&> CSagyoshaListArray;

	// リストボックス設定データ
	struct SListData {
// Modify ... ( ADD )
		int nSagyoNo;		// 作業No
// By Y.Itabashi (xxxxx) 2007.02.19
		CString sName;		// 項目名
		double fTime;		// 作業時間
		int nId;			// 項目ID
		SGraphBlock *pGraphBlock;
	};
	typedef CArrayEx <SListData, SListData&> CListDataArray;

// コンストラクション
public:
	CManualBunkatsuDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualBunkatsuDlg)
	enum { IDD = IDD_MANUAL_BUNKATSU };
	CButton	m_cButtonPreview;
	CButton	m_cButtonSettei;
	CButton	m_cButtonRightMove;
	CButton	m_cButtonLeftMove;
	CStatic	m_cStaticTotalTime2;
	CStatic	m_cStaticTotalTime1;
	CComboBox	m_cComboYosoSagyogun;
	CComboBox	m_cComboKotei;
	CComboBox	m_cComboSagyosha;
	CLixxxtrlEx1	m_cList2;
	CLixxxtrlEx1	m_cList1;
	//}}AFX_DATA

	int m_nKishuId;					// 機種ID
	int m_nSagyoshaId;				// 作業者ID
	CString m_sKoteiName;			// 工程名
	CString m_sYosoSagyogunName;	// 要素作業群名
	CManualEditDocument *m_pDoc;	// ドキュメントクラスへのポインタ

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualBunkatsuDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualBunkatsuDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnButtonLeftMove();
	afx_msg void OnButtonRightMove();
	afx_msg void OnButtonSettei();
	afx_msg void OnSelchangeComboSagyosha();
	afx_msg void OnSelchangeComboKotei();
	afx_msg void OnSelchangeComboYosoSagyogun();
	afx_msg void OnButtonPreview();
	virtual void OnCancel();
	afx_msg void OnClickList1(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedList1(NMHDR* pNMHDR, LRESULT* pResult);
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

	int m_nMode;					// 表示モード
	BOOL m_bPreview;				// プレビュー実行フラグ
	SYosoSagyogun *m_pYosoSagyogun;	// 要素作業群ポインタ
	CSagyoshaListArray m_aSagyoshaList;	// 作業者リストデータ
	CListDataArray m_aListData;		// リストボックスデータ配列

	void ExecBunkatsuSakujo();
	void KoteiBunkatsu();
	void YosoSagyogunBunkatsu();
	void SetSagyoshaList();
	void SetComboList();
	void SetKoteiList();
	void SetYosoSagyogunList();
	void SetYosoSagyoList();
	void SetComboBox(CComboBox &cComboBox);
	void SetListBox();
	void DispTotalTimeAll();
	void DispTotalTime(double fTime, CStatic &cStatic);
	double CalcTotalTime(CLixxxtrlEx1 &cList);
	void EnableButtons();
	void MoveListBox(CLixxxtrlEx1 &cListFrom, CLixxxtrlEx1 &cListTo);
	void SetLixxxolumn(CLixxxtrlEx1 &cList);
	BOOL ListDataCheck();
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。
