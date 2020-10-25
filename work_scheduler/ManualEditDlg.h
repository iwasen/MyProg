#if !defined(AFX_MANUALEDITDLG_H__A4E9233D_2CFE_415F_8624_F1D6FEB6BC7A__INCLUDED_)
#define AFX_MANUALEDITDLG_H__A4E9233D_2CFE_415F_8624_F1D6FEB6BC7A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000
// ManualEditDlg.h : ヘッダー ファイル
//
#include "DialogEx.h"
#include "ManualEditDocument.h"
#include "LixxxtrlEx1.h"

/////////////////////////////////////////////////////////////////////////////
// CManualEditDlg ダイアログ

class CManualEditDlg : public CDialogEx
{
	// 要素作業リストデータ
	struct SYosoSagyoList {
		int nIndex;					// インデックス番号
		int nEditFlag;				// 編集フラグ
		BOOL bErrorFlag;			// エラーフラグ
		int nYosoSagyoId;			// 要素作業ID
		CString sYosoSagyoName;		// 作業内容
		CString sYosoSagyoNo;		// 要素作業NO
// --Add 2007/02/26 aizawa 不懸D-187
		int nSagyoNo;				// 作業No
// --Add
		CString sEnzanShiki;		// 演算式
		double fS1;					// S1
		double fS2;					// S2
		double fM;					// M
		BOOL bM;					// M入力フラグ
		double fN;					// N
		BOOL bN;					// N入力フラグ
		int nHassexxxndo;			// 発生頻度
		BOOL bHassexxxndo;			// 発生頻度入力フラグ
		int nKurikaeshiKaisu;		// 繰り返し回数
		BOOL bKurikaeshiKaisu;		// 繰り返し回数入力フラグ
		CString sKoteibuHendobu;	// 固定／変動
		CString sSagyoFlag;			// 作業フラグ
		int nKijunKotei;			// 基準／工程
		BOOL bKijunKotei;			// 基準／工程入力フラグ
	};
	typedef CArrayEx <SYosoSagyoList, SYosoSagyoList&> CYosoSagyoListArray;
	
	// 要素作業群リストデータ
	struct SYosoSagyogunList {
		int nIndex;					// インデックス番号
		int nEditFlag;				// 編集フラグ
		BOOL bErrorFlag;			// エラーフラグ
		int nYosoSagyogunId;		// 要素作業群ID
		CString sYosoSagyogunName;
		BOOL bKyotsuKotei;
		CYosoSagyoListArray aYosoSagyoList;
	};
	typedef CArrayEx <SYosoSagyogunList, SYosoSagyogunList&> CYosoSagyogunListArray;

	// 工程リストデータ
	struct SKoteiList {
		int nIndex;					// インデックス番号
		int nEditFlag;				// 編集フラグ
		BOOL bErrorFlag;			// エラーフラグ
		CString sSubline;			// サブライン名
		CString sKoteiKigo;			// 工程記号
		CString sKoteiNo;			// 工程番号
		CString sKoteiName;			// 工程名
		CString sJidoShudo;			// 自動／手動
		BOOL bKyotsuKotei;
		CYosoSagyogunListArray aYosoSagyogunList;
	};
	typedef CArrayEx2 <SKoteiList, SKoteiList&> CKoteiListArray;

	// 作業者リストデータ
	struct SSagyoshaList {
		int nSublineId;
		int nSagyoshaId;
		CString sSagyoshaName;
		CString sKoteiKigo;
		CKoteiListArray aKoteiList;
	};
	typedef CArrayEx <SSagyoshaList, SSagyoshaList&> CSagyoshaListArray;

// コンストラクション
public:
	CManualEditDlg(CWnd* pParent = NULL);   // 標準のコンストラクタ

// ダイアログ データ
	//{{AFX_DATA(CManualEditDlg)
	enum { IDD = IDD_MANUAL_EDIT };
	CButton	m_cButtonYosoSagyoTsuika;
	CButton	m_cButtonYosoSagyoSakujo;
	CMyEdit	m_cEditKishuName;
	CLixxxtrlEx	m_cListKotei;
	CLixxxtrlEx	m_cListYosoSagyogun;
	CLixxxtrlEx	m_cListYosoSagyo;
	CComboBox	m_cComboSagyosha;
	//}}AFX_DATA

	int m_nSagyoshaId;				// 作業者ID
	int m_nKishuId;					// 機種ID
	CManualEditDocument *m_pDoc;	// ドキュメントクラスへのポインタ


// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。
	//{{AFX_VIRTUAL(CManualEditDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	//}}AFX_VIRTUAL

// インプリメンテーション
protected:

	// 生成されたメッセージ マップ関数
	//{{AFX_MSG(CManualEditDlg)
	virtual BOOL OnInitDialog();
	afx_msg void OnSelchangeComboSagyosha();
	afx_msg void OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult);
	afx_msg void OnButtonYosoSagyoSakujo();
	afx_msg void OnButtonYosoSagyogunSakujo();
	afx_msg void OnButtonKoteiSakujo();
	afx_msg void OnButtonYosoSagyoTsuika();
	afx_msg void OnButtonYosoSagyogunTsuika();
	afx_msg void OnButtonKoteiTsuika();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnButtonPreview();
	//}}AFX_MSG
	afx_msg LRESULT OnChangeCombo(WPARAM wp, LPARAM lp);
	afx_msg LRESULT CreateComboList(WPARAM wp, LPARAM lp);
	afx_msg LRESULT OnChangeEdit(WPARAM wp, LPARAM lp);
	DECLARE_MESSAGE_MAP()

	CSagyoshaListArray m_aSagyoshaList;	// 作業者リストデータ
	CSagyoshaListArray m_aSagyoshaList2;	// 作業者リスト初期データ

	int m_nSublineId;

	void SetSagyoshaList();
	void SetKoteiList(int nSelectIndex = -1);
	void SetYosoSagyogunList(int nSelectIndex = -1);
	void SetYosoSagyoList(int nSelectIndex = -1);
	void EditKotei(int nRow, int nCol);
	void EditYosoSagyogun(int nRow, int nCol);
	void EditYosoSagyo(int nRow, int nCol);
	void SetYosoSagyoTemplate(SYosoSagyoList &cYosoSagyoList);
	CString GetKijunKoteiString(int nKijunKotei);
	void MakeSagyoshaList();
	void MakeKoteiList(int nSublineId, int nSagyoshId, CKoteiListArray &aKoteiList);
	void MakeYosoSagyogunList(int nYosoSagyogunId, SYosoSagyogun &cYosoSagyogun, CYosoSagyogunListArray &aYosoSagyogunList);
	void MakeYosoSagyoList(SYosoSagyogun &cYosoSagyogun, CYosoSagyoListArray &aYosoSagyoList);
	int GetSelectSagyosha();
	int GetSelectKotei();
	int GetSelectYosoSagyogun();
	int GetSelectYosoSagyo();
	void EnableYosoSagyoTsuikaSakujo();
	void DispList(int nSagyoshaIndex, int nKoteiIndex = -1, int nYosoSagyogunIndex = -1);
	BOOL CheckData();
	void ClearErrorFlag();
	void SetChangeFlag();
	BOOL CheckMinyuryoku();
	BOOL CheckInputData();
	BOOL CheckInputDataSub(CDataCheck &cDataCheck, LPCTSTR pData);
	BOOL CheckInputDataSub(CDataCheck &cDataCheck, double fData);
	BOOL CheckSameKotei();
	BOOL CheckSameYosoSagyogun();
	BOOL CheckYosoSagyogun();
	BOOL CheckYosoSagyo();
	BOOL CheckKyotsuKotei();
	void ChangeData();
	void DeleteData();
	void InsertData();
	void SetYosoSagyoData(SYosoSagyo &cYosoSagyo, SYosoSagyoList &cYosoSagyoList);
	double CalcEnzangoSagyoJikan(SYosoSagyoList &cYosoSagyoList);
	double CalcDaiatariSagyoJikan(SYosoSagyoList &cYosoSagyoList, double fEnzangoSagyoJikan);
	CString ConvertHankaku(LPCTSTR pText);
};

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ は前行の直前に追加の宣言を挿入します。

#endif // !defined(AFX_MANUALEDITDLG_H__A4E9233D_2CFE_415F_8624_F1D6FEB6BC7A__INCLUDED_)
