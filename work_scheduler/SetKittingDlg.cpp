//*****************************************************************************************************
//  1. ファイル名
//		SetKittingDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSetKittingDlg クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "SetKittingDlg001.h"
#include "SetKittingDlg.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// Modify ... ( CHANGE )
// Lixxxtrlに変更
// 部品スプレッドカラム位置
#define	COL_BUHIN_CHECK		0
#define COL_BUHIN_BLOCK		1
#define	COL_BUHIN_BUHINNO	2
#define	COL_BUHIN_BUHINNAME	3
#define	COL_BUHIN_PC		4

/*enum EBuhinList {
	eBuhinLixxxheck = 1,
	eBuhinListBlock = 2,
	eBuhinListBuhinNo = 3,
	eBuhinListBuhinName = 4,
	eBuhinListPC = 5
};*/

// 工程スプレッドカラム位置
#define	COL_KOTEI_LISTNO		0
#define	COL_KOTEI_KOTEINAME		1
#define	COL_KOTEI_FUYOSAGYO		2

/*enum EKoteiList {
	eKoteiListNo = 1,
	eKoteiListKoteiName = 2,
	eKoteiListFuyoSagyo = 3
};*/

// 要素作業群スプレッドカラム位置
#define	COL_YOSOSAGYOGUN_LISTNO		0
#define	COL_YOSOSAGYOGUN_KOTEINAME	1
#define	COL_YOSOSAGYOGUN_FUYOSAGYO	2

/*enum EYosoSagyogunList {
	eYosoSagyogunListNo = 1,
	eYosoSagyogunListKoteiName = 2,
	eYosoSagyogunListFuyoSagyo = 3
};*/

// 要素作業スプレッドカラム位置
#define	COL_YOSOSAGYO_SAGYONO	0
#define	COL_YOSOSAGYO_YOSOSAGYO	1

/*enum EYosoSagyoList {
	eYosoSagyoListSagyoNo = 1,
	eYosoSagyoListYosoSagyo = 2
};*/

#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_CHECKOK	RGB(0xcc, 0xff, 0xff)
#define	COLOR_CHECKNON	RGB(0xff, 0xff, 0xff)
#define	COLOR_FUYOYES	RGB(0xff, 0xff, 0)
#define	COLOR_FUYONO	RGB(0xff, 0xff, 0xff)
// By Y.Itabashi (xxxxx) 2007.01.26

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::CSetKittingDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent			[I] 親ウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		無し
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CSetKittingDlg::CSetKittingDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CSetKittingDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CSetKittingDlg)
	//}}AFX_DATA_INIT

	m_nSublineId = -1;
	m_nSelectOff = 0;
}


void CSetKittingDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CSetKittingDlg)
	DDX_Control(pDX, IDC_LIST_BUHIN, m_cListBuhin);
	DDX_Control(pDX, IDC_LIST_KOTEI, m_cListKotei);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYOGUN, m_cListYosoSagyogun);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYO, m_cListYosoSagyo);
	DDX_Control(pDX, IDOK, m_cButtonOK);
	DDX_Control(pDX, IDC_EDIT_KIT_SA_JIKAN, m_cEditKitSaJikan);
	DDX_Control(pDX, IDC_EDIT_KIT_NASHI_GOKEI_JIKAN, m_cEditKitNashiGokeiJikan);
	DDX_Control(pDX, IDC_EDIT_KIT_ARI_GOKEI_JIKAN, m_cEditKitAriGokeiJikan);
	DDX_Control(pDX, IDC_COMBO_SUBLINE, m_cComboSubline);
//	DDX_Control(pDX, IDC_SPREAD_BUHIN, m_cSpreadBuhin);
//	DDX_Control(pDX, IDC_SPREAD_KOTEI, m_cSpreadKotei);
//	DDX_Control(pDX, IDC_SPREAD_YOSO_SAGYO, m_cSpreadYosoSagyo);
//	DDX_Control(pDX, IDC_SPREAD_YOSO_SAGYOGUN, m_cSpreadYosoSagyogun);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CSetKittingDlg, CDialogEx)
	//{{AFX_MSG_MAP(CSetKittingDlg)
	ON_WM_SIZE()
	ON_CBN_SELCHANGE(IDC_COMBO_SUBLINE, OnSelchangeComboSubline)
	ON_BN_CLICKED(IDC_BUTTON_NEXT, OnButtonNext)
	ON_BN_CLICKED(IDC_BUTTON_BACK, OnButtonBack)
	ON_BN_CLICKED(IDC_BUTTON_ZENSENTAKU, OnButtonZensentaku)
	ON_BN_CLICKED(IDC_BUTTON_ZENKAIJO, OnButtonZenkaijo)
	ON_BN_CLICKED(IDC_BUTTON_KITTING_TAISHO_BUHINHYO, OnButtonKittingTaishoBuhinhyo)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_BUHIN, OnItemchangedListBuhin)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_KOTEI, OnItemchangedListKotei)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_YOSO_SAGYOGUN, OnItemchangedListYosoSagyogun)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_YOSO_SAGYO, OnItemchangedListYosoSagyo)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USEREVENT_LISTEX_CLICK, OnListExClick)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		サブラインを表示し、スプレッドにキット化部品を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CSetKittingDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	int i;
	CString sText;

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_SetKittingDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// キット化データ取得
	g_pDataManager->GetKittingData();

	// サブライン表示
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		m_cComboSubline.SetItemData(m_cComboSubline.AddString(pSubline->sSublineName), nSublineId);
	}

// Modify ... ( DELETE )
// Lixxxtrlに変更
//	m_cSpreadBuhin.SetMaxRows(0);
//	m_cSpreadKotei.SetMaxRows(0);
//	m_cSpreadYosoSagyogun.SetMaxRows(0);
//	m_cSpreadYosoSagyo.SetMaxRows(0);
// By Y.Itabashi (xxxxx) 2007.01.26

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	// スプレッドに機種ごとの列を追加
	int nKishuNum = g_pDataManager->GetKishuNum();
	for (i = 0; i < nKishuNum; i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);
		sprintf( Menu_SetKittingDlg[List_SetKittingBuhin[i+COL_BUHIN_PC].nHeaderNo].str1, "%s", pKishu->sKishuName );
	}

	SetLixxxolumn( m_cListBuhin, Menu_SetKittingDlg, List_SetKittingBuhin, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListKotei, Menu_SetKittingDlg, List_SetKittingKotei, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyogun, Menu_SetKittingDlg, List_SetKittingYosoSagyogun, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyo, Menu_SetKittingDlg, List_SetKittingYosoSagyo, eSELECTTYPE_SINGLE );

	m_cListBuhin.DeleteAllItems();
	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();
/*	// スプレッドに機種ごとの列を追加
	int nKishuNum = g_pDataManager->GetKishuNum();
	m_cSpreadBuhin.SetMaxCols(eBuhinListPC - 1 + nKishuNum);
	for (i = 0; i < nKishuNum; i++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(i);

		m_cSpreadBuhin.SetValue(0, eBuhinListPC + i, pKishu->sKishuName);

		m_cSpreadBuhin.SetRow(-1);
		m_cSpreadBuhin.SetCol(eBuhinListPC + i);
		m_cSpreadBuhin.SetTypeHAlign(1);
	}*/

	// コントロールの位置を調整
	SetCtlPosition(IDC_BUTTON_KITTING_TAISHO_BUHINHYO, 1, 0, 1, 0);
	SetCtlPosition(IDC_LIST_BUHIN, 0, 0, 1, 0.5);
//	SetCtlPosition(IDC_SPREAD_BUHIN, 0, 0, 1, 0.5);
	SetCtlPosition(IDC_BUTTON_NEXT, 0.5, 0.5, 0.5, 0.5);
	SetCtlPosition(IDC_BUTTON_BACK, 0.5, 0.5, 0.5, 0.5);
	SetCtlPosition(IDC_STATIC_KIT_NASHI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_EDIT_KIT_NASHI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_STATIC_KIT_ARI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_EDIT_KIT_ARI_GOKEI_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_STATIC_KIT_SA_JIKAN, 1, 0.5, 1, 0.5);
	SetCtlPosition(IDC_EDIT_KIT_SA_JIKAN, 1, 0.5, 1, 0.5);
//	SetCtlPosition(IDC_SPREAD_KOTEI, 0, 0.5, 0.5, 0.75);
//	SetCtlPosition(IDC_SPREAD_YOSO_SAGYOGUN, 0, 0.75, 0.5, 1);
//	SetCtlPosition(IDC_SPREAD_YOSO_SAGYO, 0.5, 0.5, 1, 1);
	SetCtlPosition(IDC_LIST_KOTEI, 0, 0.5, 0.5, 0.75);
	SetCtlPosition(IDC_LIST_YOSO_SAGYOGUN, 0, 0.75, 0.5, 1);
	SetCtlPosition(IDC_LIST_YOSO_SAGYO, 0.5, 0.5, 1, 1);
	SetCtlPosition(IDOK, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);
// By Y.Itabashi (xxxxx) 2007.01.26

	m_cButtonOK.EnableWindow(FALSE);

	return TRUE;
}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnClickSpreadBuhin
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スプレッドクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		long		Col			[I] クリックした列
//		long		Row			[I] クリックした行
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		１列目をクリックした場合は、スプレッドのチェックボックスをON/OFFする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
/*void CSetKittingDlg::OnClickSpreadBuhin(long Col, long Row)
{
	if (Row > 0) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

		// 単一選択モードに設定
		m_cSpreadBuhin.SetOperationMode(3);

		// チェックボックスのON/OFFを切り替える
		if (Col == eBuhinLixxxheck) {
			SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[m_cSpreadBuhin.GetRowItemData(Row)];

			if (cKittingBuhin.bCheck) {
				cKittingBuhin.bCheck = FALSE;
				m_cSpreadBuhin.SetValue(Row, Col, "0");
			} else {
				cKittingBuhin.bCheck = TRUE;
				m_cSpreadBuhin.SetValue(Row, Col, "1");
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnDblClickSpreadBuhin
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スプレッドダブルクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		long		Col			[I] クリックした列
//		long		Row			[I] クリックした行
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		Kitting作業選択ダイアログを開く。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::OnDblClickSpreadBuhin(long Col, long Row)
{
	// Kitting作業選択ダイアログ表示
	if (Row > 0)
		SelectBuhin(Row);
}*/
// By Y.Itabashi (xxxxx) 2007.01.26

void CSetKittingDlg::SelectBuhin(int nRow)
{
	if( m_nSelectOff == 1 )
		return;

	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	int nIndex = m_cListBuhin.GetItemRowData( nRow );
	m_pKittingBuhin = &pSubline->aKittingBuhin[nIndex];
//	m_pKittingBuhin = &pSubline->aKittingBuhin[m_cSpreadBuhin.GetRowItemData(nRow)];

	// チェック済み
	pSubline->aCheckBuhin.Add(m_pKittingBuhin->sBuhinNo);
	m_cListBuhin.SetItemRowColor(nRow, COLOR_TEXT, COLOR_CHECKOK);
//	m_cSpreadBuhin.SetBkColor(nRow, -1, RGB(0xcc, 0xcc, 0xcc));

	SetKoteiList();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();
//	m_cSpreadYosoSagyogun.SetMaxRows(0);
//	m_cSpreadYosoSagyo.SetMaxRows(0);
// By Y.Itabashi (xxxxx) 2007.02.01
}

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		キット化による不要作業を削除し、キット化作業テンプレート追加する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::OnOK()
{
	// キット化部品存在チェック
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	BOOL bKitting = FALSE;
	int nBuhinNum = pSubline->aKittingBuhin.GetSize();
	for (int i = 0; i < nBuhinNum; i++) {
		if (pSubline->aKittingBuhin[i].bCheck) {
			bKitting = TRUE;
			break;
		}
	}
	if (bKitting) {
		if (g_pSystem->DispMessage("Q008002") == IDNO)
			return;
	} else {
		if (g_pSystem->DispMessage("Q008001") == IDNO)
			return;
	}

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (m_aKittingSublineId.Find(nSublineId) != -1) {
			// キット化解除
			g_pDataManager->CancelKitting(nSublineId);

			// キット化部品存在チェック
			pSubline->bSetKitting = FALSE;
			int nBuhinNum = pSubline->aKittingBuhin.GetSize();
			for (int i = 0; i < nBuhinNum; i++) {
				if (pSubline->aKittingBuhin[i].bCheck) {
					pSubline->bSetKitting = TRUE;
					break;
				}
			}

			// キット化による不要作業削除
			g_pDataManager->DeleteKittingSagyo(nSublineId);

			if (pSubline->bSetKitting) {
				// キット化作業テンプレート追加
				g_pDataManager->AddKittingTemplate(nSublineId);
			}
		}
	}
	
	// 部品キット化情報ファイル設定
	g_pDataManager->SetBuhinKitting();

	// データ更新フラグ設定
	g_pDataManager->SetUpdate();

	EndDialog(IDOK);
}

// WM_SIZEメッセージハンドラ
void CSetKittingDlg::OnSize(UINT nType, int cx, int cy)
{
	CDialogEx::OnSize(nType, cx, cy);

	CWnd *pParentWnd = GetParent();
	if (pParentWnd != NULL) {
		switch (nType) {
		case SIZE_MINIMIZED:
			// 親ウィンドウを最小化
			pParentWnd->ShowWindow(SW_MINIMIZE);
			pParentWnd->EnableWindow(TRUE);
			break;
		case SIZE_RESTORED:
			// 親ウィンドウを無効化
			pParentWnd->EnableWindow(FALSE);
			EnableWindow(TRUE);
			break;
		}
	}
}

// サブライン選択
void CSetKittingDlg::OnSelchangeComboSubline() 
{
	int nIndex = m_cComboSubline.GetCurSel();
	if (nIndex != -1) {
		m_nSublineId = m_cComboSubline.GetItemData(nIndex);

		// スプレッドに部品を表示
		SetBuhinList();

		// キット化実行対象設定
		m_aKittingSublineId.AddUnique(m_nSublineId);

		m_cButtonOK.EnableWindow(TRUE);
	}
}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnClickSpreadKotei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程スプレッドクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		long		Col			[I] クリックした列
//		long		Row			[I] クリックした行
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業群スプレッドを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
/*void CSetKittingDlg::OnClickSpreadKotei(long Col, long Row)
{
	if (Row > 0) {
		// 単一選択モードに設定
		m_cSpreadKotei.SetOperationMode(3);

		// 要素作業群スプレッドを表示
		m_cSpreadKotei.GetValue(Row, eKoteiListKoteiName, m_sKoteiName);
		SetYosoSagyogunList();
	}
}*/

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnClickSpreadYosoSagyogun
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群スプレッドクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		long		Col			[I] クリックした列
//		long		Row			[I] クリックした行
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業スプレッドを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
/*void CSetKittingDlg::OnClickSpreadYosoSagyogun(long Col, long Row)
{
	if (Row > 0) {
		// 単一選択モードに設定
		m_cSpreadYosoSagyogun.SetOperationMode(3);

		// 要素作業スプレッドを表示
		m_nKittingYosoSagyogunIndex = m_cSpreadYosoSagyogun.GetRowItemData(Row);
		SetYosoSagyoList();
	}
}*/
// By Y.Itabashi (xxxxx) 2007.01.26

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::OnClickSpreadYosoSagyo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業スプレッドクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		long		Col			[I] クリックした列
//		long		Row			[I] クリックした行
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		削除フラグのON/OFFを切り替える。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::OnClickSpreadYosoSagyo(long Col, long Row)
{
	// 削除フラグのON/OFFを切り替える
	if (Row > 0) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_nKittingYosoSagyogunIndex];
// Modify ... ( CAHNGE )
// Lixxxtrlに変更
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[m_cListYosoSagyo.GetItemRowData(Row)];
//		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[m_cSpreadYosoSagyo.GetItemData(Row)];
// By Y.Itabashi (xxxxx) 2007.01.26

		if (cKittingYosoSagyo.bSakujoFlag) {
			cKittingYosoSagyo.bSakujoFlag = FALSE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_CHECKNON);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0xff));
		} else {
			cKittingYosoSagyo.bSakujoFlag = TRUE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_CHECKOK);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0));
		}

		SetKoteiList(FALSE);
		SetYosoSagyogunList(FALSE);

		// 合計時間を計算
		double fKitNashiJikan = 0;
		double fKitAriJikan = 0;
		int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
		for (int i = 0; i < nYosoSagyoNum; i++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];

			fKitNashiJikan += cKittingYosoSagyo.fTime;
			if (!cKittingYosoSagyo.bSakujoFlag)
				fKitAriJikan += cKittingYosoSagyo.fTime;
		}

		// 合計時間表示
		DispTotalTime(fKitNashiJikan, fKitAriJikan);
	}
}

void CSetKittingDlg::OnButtonKittingTaishoBuhinhyo() 
{
	CString sPathName = CGlobal::FileSaveDlg(g_pSystem->m_cIniUser.m_sDataPath, "Excel Files (*.xls)|*.xls|All Files (*.*)|*.*||", "", "Title", ".xls");
	if (!sPathName.IsEmpty())
		g_pDataManager->SaveKittingTaishoBuhin(sPathName);
}

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingDlg::SetBuhinList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		部品スプレッド設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		スプレッドの各列に部品データをセットする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::SetBuhinList()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i, j;

	int nKishuNum = g_pDataManager->GetKishuNum();

	int nKittingBuhinNum = pSubline->aKittingBuhin.GetSize();
// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	m_cListBuhin.DeleteAllItems();
	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();
	for (i = 0; i < nKittingBuhinNum; i++) {
		SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[i];
		int nRow = i;

		// チェックボックス
		m_cListBuhin.SetValue(nRow, COL_BUHIN_CHECK, cKittingBuhin.bCheck ? true : false);

		// BLOCK番号
		m_cListBuhin.SetValue(nRow, COL_BUHIN_BLOCK, cKittingBuhin.sBlockNo);

		// 部品番号
		m_cListBuhin.SetValue(nRow, COL_BUHIN_BUHINNO, cKittingBuhin.sBuhinNo);

		// 部品名称
		m_cListBuhin.SetValue(nRow, COL_BUHIN_BUHINNAME, cKittingBuhin.sBuhinName);

		// PC
		for  (j = 0; j < nKishuNum; j++)
			m_cListBuhin.SetValue(nRow, COL_BUHIN_PC + j, cKittingBuhin.aPC[j]);

		// 設定済みは色を変える
		m_cListBuhin.SetItemRowColor(nRow, COLOR_TEXT,
			pSubline->aCheckBuhin.Find(cKittingBuhin.sBuhinNo) != -1 ? COLOR_CHECKOK : COLOR_CHECKNON);

		m_cListBuhin.SetItemRowData(nRow, i);
	}

	UpdateWindow();

/*	m_cSpreadBuhin.SetMaxRows(nKittingBuhinNum);
	m_cSpreadBuhin.SetTopRow(0);
	for (i = 0; i < nKittingBuhinNum; i++) {
		SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[i];
		int nRow = i + 1;

		// 設定済みは色を変える
		m_cSpreadBuhin.SetBkColor(nRow, -1, pSubline->aCheckBuhin.Find(cKittingBuhin.sBuhinNo) != -1 ? RGB(0xcc, 0xcc, 0xcc) : RGB(0xff, 0xff, 0xff));

		// チェックボックス
		m_cSpreadBuhin.SetValue(nRow, eBuhinLixxxheck, cKittingBuhin.bCheck ? "1" : "0");

		// BLOCK番号
		m_cSpreadBuhin.SetValue(nRow, eBuhinListBlock, cKittingBuhin.sBlockNo);

		// 部品番号
		m_cSpreadBuhin.SetValue(nRow, eBuhinListBuhinNo, cKittingBuhin.sBuhinNo);

		// 部品名称
		m_cSpreadBuhin.SetValue(nRow, eBuhinListBuhinName, cKittingBuhin.sBuhinName);

		// PC
		for  (j = 0; j < nKishuNum; j++)
			m_cSpreadBuhin.SetValue(nRow, eBuhinListPC + j, cKittingBuhin.aPC[j]);

		m_cSpreadBuhin.SetRowItemData(nRow, i);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	// 合計時間表示
	DispTotalTime(0, 0);
}

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingSagyoDlg::SetKoteiList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程スプレッド設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工程スプレッドに工程名を設定し、要素作業群スプレッド、要素作業スプレッドをクリアする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::SetKoteiList(BOOL bInitFlag)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i, j;
	double fKitNashiJikan = 0;
	double fKitAriJikan = 0;

	// 工程名を取得
	CKoteiListArray aKoteiList;
	int nYosoSagyogun = m_pKittingBuhin->aKittingYosoSagyogunId.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_pKittingBuhin->aKittingYosoSagyogunId[i]];
		SKoteiList *pKoteiList = aKoteiList.LookupElement(cKittingYosoSagyogun.sKoteiName);
		if (pKoteiList == NULL) {
			pKoteiList = aKoteiList.AddElement(cKittingYosoSagyogun.sKoteiName);
			pKoteiList->sKoteiName = cKittingYosoSagyogun.sKoteiName;
			pKoteiList->bFuyoSagyo = FALSE;
		}

		// 不要作業フラグ設定
		int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
		for (j = 0; j < nYosoSagyoNum; j++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
			if (cKittingYosoSagyo.bSakujoFlag) {
				pKoteiList->bFuyoSagyo = TRUE;
				break;
			}
		}

		// 合計時間計算
		for (j = 0; j < nYosoSagyoNum; j++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
			fKitNashiJikan += cKittingYosoSagyo.fTime;
			if (!cKittingYosoSagyo.bSakujoFlag)
				fKitAriJikan += cKittingYosoSagyo.fTime;
		}
	}

	int nKoteiNum = aKoteiList.GetSize();

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	if (bInitFlag) {
		// スプレッドの行数を初期化
		m_cListYosoSagyogun.DeleteAllItems();
		m_cListYosoSagyo.DeleteAllItems();
/*		m_cSpreadKotei.SetMaxRows(nKoteiNum);
		m_cSpreadKotei.SetTopRow(0);
		m_cSpreadYosoSagyogun.SetMaxRows(0);
		m_cSpreadYosoSagyo.SetMaxRows(0);

		// スプレッドを読み出し専用モードに設定
		m_cSpreadKotei.SetOperationMode(1);
		m_cSpreadYosoSagyogun.SetOperationMode(1);
		m_cSpreadYosoSagyo.SetOperationMode(1);*/

		// 合計時間表示
		DispTotalTime(fKitNashiJikan, fKitAriJikan);

		// 工程スプレッドに工程名を設定
		m_cListKotei.DeleteAllItems();
		for (i = 0; i < nKoteiNum; i++) {
			SKoteiList &cKoteiList = aKoteiList[i];
			int nRow = i;

			m_cListKotei.SetValue(nRow, COL_KOTEI_LISTNO, nRow+1);
			m_cListKotei.SetValue(nRow, COL_KOTEI_KOTEINAME, cKoteiList.sKoteiName);
			m_cListKotei.SetValue(nRow, COL_KOTEI_FUYOSAGYO, cKoteiList.bFuyoSagyo ? "*" : "");

			// 設定済みは色を変える
			m_cListKotei.SetItemRowColor(nRow, COLOR_TEXT,
				pSubline->aCheckKotei.Find(cKoteiList.sKoteiName) != -1 ? COLOR_CHECKOK : COLOR_CHECKNON);
		}
	} else {
		for (i = 0; i < nKoteiNum; i++) {
			SKoteiList &cKoteiList = aKoteiList[i];
			int nRow = i;

			m_cListKotei.SetValue(nRow, COL_KOTEI_FUYOSAGYO, cKoteiList.bFuyoSagyo ? "*" : "");
		}
	}
}

// Modify ... ( CHANGE )
void CSetKittingDlg::SelectKotei(int nRow)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

	m_cListKotei.GetValue(nRow, COL_KOTEI_KOTEINAME, m_sKoteiName);

	// チェック済み
	pSubline->aCheckKotei.Add(m_sKoteiName);
	m_cListKotei.SetItemRowColor(nRow, COLOR_TEXT, COLOR_CHECKOK);

	SetYosoSagyogunList();
	m_cListYosoSagyo.DeleteAllItems();
}

void CSetKittingDlg::SelectYosoSagyogun(int nRow)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

	CString	sYosoSagyogunName;
	m_cListYosoSagyogun.GetValue(nRow, COL_YOSOSAGYOGUN_KOTEINAME, sYosoSagyogunName);

	// チェック済み
	pSubline->aCheckYosoSagyogun.Add(sYosoSagyogunName);
	m_cListYosoSagyogun.SetItemRowColor(nRow, COLOR_TEXT, COLOR_CHECKOK);

	m_nKittingYosoSagyogunIndex = m_cListYosoSagyogun.GetItemRowData(nRow);
	SetYosoSagyoList();
}
// By Y.Itabashi (xxxxx) 2007.01.29

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingSagyoDlg::SetYosoSagyogunList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群スプレッド設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業群スプレッドに要素作業群名を設定し、要素作業スプレッドをクリアする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::SetYosoSagyogunList(BOOL bInitFlag)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i, j;
	int nIndex;
	double fKitNashiJikan = 0;
	double fKitAriJikan = 0;

	// 要素作業群を取得
	CYosoSagyogunListArray aYosoSagyogunList;
	int nYosoSagyogun = m_pKittingBuhin->aKittingYosoSagyogunId.GetSize();
	for (i = 0; i < nYosoSagyogun; i++) {
		nIndex = m_pKittingBuhin->aKittingYosoSagyogunId[i];
		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[nIndex];
		if (cKittingYosoSagyogun.sKoteiName == m_sKoteiName) {
			SYosoSagyogunList *pYosoSagyogunList = aYosoSagyogunList.AddElement();
			pYosoSagyogunList->sYosoSagyogunName = cKittingYosoSagyogun.sYosoSagyogunName;
			pYosoSagyogunList->nIndex = nIndex;
			pYosoSagyogunList->bFuyoSagyo = FALSE;

			// 不要作業フラグ設定
			int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
			for (j = 0; j < nYosoSagyoNum; j++) {
				SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
				if (cKittingYosoSagyo.bSakujoFlag) {
					pYosoSagyogunList->bFuyoSagyo = TRUE;
					break;
				}
			}

			// 合計時間計算
			for (j = 0; j < nYosoSagyoNum; j++) {
				SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[j];
				fKitNashiJikan += cKittingYosoSagyo.fTime;
				if (!cKittingYosoSagyo.bSakujoFlag)
					fKitAriJikan += cKittingYosoSagyo.fTime;
			}
		}
	}

	nYosoSagyogun = aYosoSagyogunList.GetSize();

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	if (bInitFlag) {
		// スプレッドの行数を初期化
		m_cListYosoSagyo.DeleteAllItems();
/*		m_cSpreadYosoSagyogun.SetMaxRows(nYosoSagyogun);
		m_cSpreadYosoSagyogun.SetTopRow(0);
		m_cSpreadYosoSagyo.SetMaxRows(0);

		// スプレッドを読み出し専用モードに設定
		m_cSpreadYosoSagyogun.SetOperationMode(1);
		m_cSpreadYosoSagyo.SetOperationMode(1);*/

		// 合計時間表示
		DispTotalTime(fKitNashiJikan, fKitAriJikan);

		// 要素作業群スプレッドに要素作業群を設定
		m_cListYosoSagyogun.DeleteAllItems();
		for (i = 0; i < nYosoSagyogun; i++) {
			SYosoSagyogunList &cYosoSagyogunList = aYosoSagyogunList[i];
			int nRow = i;

			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_LISTNO, nRow+1);
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_KOTEINAME, cYosoSagyogunList.sYosoSagyogunName);
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_FUYOSAGYO, cYosoSagyogunList.bFuyoSagyo ? "*" : "");

			m_cListYosoSagyogun.SetItemRowData(nRow, cYosoSagyogunList.nIndex);

			// 設定済みは色を変える
			m_cListYosoSagyogun.SetItemRowColor(nRow, COLOR_TEXT,
				pSubline->aCheckYosoSagyogun.Find(cYosoSagyogunList.sYosoSagyogunName) != -1 ? COLOR_CHECKOK : COLOR_CHECKNON);
		}
	} else {
		for (i = 0; i < nYosoSagyogun; i++) {
			SYosoSagyogunList &cYosoSagyogunList = aYosoSagyogunList[i];
			int nRow = i;

			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_FUYOSAGYO, cYosoSagyogunList.bFuyoSagyo ? "*" : "");
		}
	}

/*	// 要素作業群スプレッドに要素作業群を設定
	for (i = 0; i < nYosoSagyogun; i++) {
		SYosoSagyogunList &cYosoSagyogunList = aYosoSagyogunList[i];
		int nRow = i + 1;

		m_cSpreadYosoSagyogun.SetValue(nRow, eYosoSagyogunListNo, nRow);
		m_cSpreadYosoSagyogun.SetValue(nRow, eYosoSagyogunListKoteiName, cYosoSagyogunList.sYosoSagyogunName);
		m_cSpreadYosoSagyogun.SetValue(nRow, eYosoSagyogunListFuyoSagyo, cYosoSagyogunList.bFuyoSagyo ? "*" : "");

		m_cSpreadYosoSagyogun.SetRowItemData(nRow, cYosoSagyogunList.nIndex);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

//*****************************************************************************************************
//  1. 関数名
//		CSetKittingSagyoDlg::SetYosoSagyoList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業スプレッド設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		要素作業スプレッドに要素作業名を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CSetKittingDlg::SetYosoSagyoList()
{
	SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
	int i;
	double fKitNashiJikan = 0;
	double fKitAriJikan = 0;

	// 要素作業を取得
	SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_nKittingYosoSagyogunIndex];

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	// スプレッドの行数を初期化
	int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
//	m_cSpreadYosoSagyo.SetMaxRows(nYosoSagyoNum);
//	m_cSpreadYosoSagyo.SetTopRow(0);

	// 要素作業スプレッドに要素作業を設定
	m_cListYosoSagyo.DeleteAllItems();
	for (i = 0; i < nYosoSagyoNum; i++) {
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];
		int nRow = i;

		m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_SAGYONO, cKittingYosoSagyo.nSagyoNo);
		m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_YOSOSAGYO, cKittingYosoSagyo.sYosoSagyoName);

		m_cListYosoSagyo.SetItemRowColor(nRow, COLOR_TEXT,
			cKittingYosoSagyo.bSakujoFlag ? COLOR_FUYOYES : COLOR_FUYONO);
		m_cListYosoSagyo.SetItemRowData(nRow, i);

		fKitNashiJikan += cKittingYosoSagyo.fTime;
		if (!cKittingYosoSagyo.bSakujoFlag)
			fKitAriJikan += cKittingYosoSagyo.fTime;
	}

/*	for (i = 0; i < nYosoSagyoNum; i++) {
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];
		int nRow = i + 1;

		m_cSpreadYosoSagyo.SetValue(nRow, eYosoSagyoListSagyoNo, cKittingYosoSagyo.nSagyoNo);
		m_cSpreadYosoSagyo.SetValue(nRow, eYosoSagyoListYosoSagyo, cKittingYosoSagyo.sYosoSagyoName);

		m_cSpreadYosoSagyo.SetBkColor(nRow, -1, cKittingYosoSagyo.bSakujoFlag ? RGB(0xff, 0xff, 0) : RGB(0xff, 0xff, 0xff));
		m_cSpreadYosoSagyo.SetRowItemData(nRow, i);

		fKitNashiJikan += cKittingYosoSagyo.fTime;
		if (!cKittingYosoSagyo.bSakujoFlag)
			fKitAriJikan += cKittingYosoSagyo.fTime;
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26

	// 合計時間表示
	DispTotalTime(fKitNashiJikan, fKitAriJikan);
}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
void CSetKittingDlg::OnButtonNext() 
{
	POSITION pos = m_cListBuhin.GetFirstSelectedItemPosition();
	int nRow = m_cListBuhin.GetNextSelectedItem( pos );
	if (nRow >= 0 && nRow < m_cListBuhin.GetItemCount()-1) {
		nRow++;

		bool bCheck;
		m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		while( !bCheck ){
			if( nRow == m_cListBuhin.GetItemCount()-1 )
				return;

			nRow++;
			m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		}

		m_cListBuhin.SelectChange(nRow);
		SelectBuhin(nRow);
	}

/*	int nRow = m_cSpreadBuhin.GetActiveRow();
	if (nRow > 0 && nRow < m_cSpreadBuhin.GetMaxRows()) {
		nRow++;
		m_cSpreadBuhin.SetActiveCell(0, nRow);
		SelectBuhin(nRow);
	}*/
}

void CSetKittingDlg::OnButtonBack() 
{
	POSITION pos = m_cListBuhin.GetFirstSelectedItemPosition();
	int nRow = m_cListBuhin.GetNextSelectedItem( pos );
	if (nRow > 0 && nRow < m_cListBuhin.GetItemCount()) {
		nRow--;

		bool bCheck;
		m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		while( !bCheck ){
			if( nRow == 0 )
				return;

			nRow--;
			m_cListBuhin.GetValue( nRow, COL_BUHIN_CHECK, bCheck );
		}

		m_cListBuhin.SelectChange(nRow);
		SelectBuhin(nRow);
	}

/*	int nRow = m_cSpreadBuhin.GetActiveRow();
	if (nRow > 1 && nRow <= m_cSpreadBuhin.GetMaxRows()) {
		nRow--;
		m_cSpreadBuhin.SetActiveCell(0, nRow);
		SelectBuhin(nRow);
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetKittingDlg::OnButtonZensentaku() 
{
// Modify ... ( CAHNGE )
	m_nSelectOff = 1;

	SetCheckAll(TRUE);

	m_nSelectOff = 0;
// By Y.Itabashi (xxxxx) 2007.01.30
}

void CSetKittingDlg::OnButtonZenkaijo() 
{
// Modify ... ( CAHNGE )
	m_nSelectOff = 1;

	SetCheckAll(FALSE);

	m_nSelectOff = 0;
// By Y.Itabashi (xxxxx) 2007.01.30
}

void CSetKittingDlg::SetCheckAll(BOOL bCheck)
{
// Modify ... ( CAHNGE )
// Lixxxtrlに変更
	if (m_nSublineId != -1) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
		int nSize = pSubline->aKittingBuhin.GetSize();
		for (int i = 0; i < nSize; i++)
			pSubline->aKittingBuhin[i].bCheck = bCheck;

		int nMaxRows = m_cListBuhin.GetItemCount();
		LVITEM	item;
		for (int nRow = 0; nRow < nMaxRows; nRow++){
			CString	sValue = ( bCheck ) ? "*" : "";

			item.mask		= LVIF_TEXT;
			item.iItem		= nRow;
			item.iSubItem	= COL_BUHIN_CHECK;
			item.pszText	= sValue.GetBuffer(strlen(sValue));
			item.cchTextMax	= strlen(sValue);

			m_cListBuhin.SetItem(&item);
		}
	}

/*	if (m_nSublineId != -1) {
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);
		int nSize = pSubline->aKittingBuhin.GetSize();
		for (int i = 0; i < nSize; i++)
			pSubline->aKittingBuhin[i].bCheck = bCheck;

		int nMaxRows = m_cSpreadBuhin.GetMaxRows();
		for (int nRow = 1; nRow <= nMaxRows; nRow++)
			m_cSpreadBuhin.SetValue(nRow, eBuhinLixxxheck, bCheck ? "1" : "0");
	}*/
// By Y.Itabashi (xxxxx) 2007.01.26
}

void CSetKittingDlg::DispTotalTime(double fKitNashiJikan, double fKitAriJikan)
{
	if (fKitNashiJikan == 0) {
		m_cEditKitNashiGokeiJikan.Blank();
		m_cEditKitAriGokeiJikan.Blank();
		m_cEditKitSaJikan.Blank();
	} else {
		m_cEditKitNashiGokeiJikan.Format("%.6f", fKitNashiJikan);
		m_cEditKitAriGokeiJikan.Format("%.6f", fKitAriJikan);
		m_cEditKitSaJikan.Format("%.6f", fKitNashiJikan - fKitAriJikan);
	}
}

void CSetKittingDlg::OnCancel() 
{
	// Undoバッファから復元
	g_pDataManager->RestoreUndoData();
	
	CDialogEx::OnCancel();
}

// Modify ... ( CAHNGE )
// Lixxxtrlに変更
void CSetKittingDlg::OnItemchangedListBuhin(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

	// Kitting作業選択ダイアログ表示
// --Add 2007/03/06 aizawa 不懸(84)
//	if (Row >= 0){
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED)) {
// --Add
		if( Col == 0 ){
			SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

			bool bCheck;
			m_cListBuhin.GetValue( Row, Col, bCheck );
// Modify ... ( CHANGE )
			int nIndex = m_cListBuhin.GetItemRowData( Row );
			SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[nIndex];
//			SKittingBuhin &cKittingBuhin = pSubline->aKittingBuhin[Row];
// By Y.Itabashi (xxxxx) 
			cKittingBuhin.bCheck = bCheck;
		} 
		SelectBuhin(Row);
	}
	
	*pResult = 0;
}

void CSetKittingDlg::OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

// Modify ... ( CHANGE )
// --Add 2007/03/06 aizawa 不懸(84)
//	if (Row >= 0)
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED))
// --Add
		SelectKotei(Row);
/*	if (Row >= 0) {

		// 要素作業群スプレッドを表示
		m_cListKotei.GetValue(Row, COL_KOTEI_KOTEINAME, m_sKoteiName);
		SetYosoSagyogunList();
	}*/
// By Y.Itabashi (xxxxx) 2007.01.29
	
	*pResult = 0;
}

void CSetKittingDlg::OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

// Modify ... ( CHANGE )
// --Add 2007/03/06 aizawa 不懸(84)
//	if (Row >= 0)
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED))
// --Add
		SelectYosoSagyogun(Row);
/*	if (Row >= 0) {
		// 要素作業スプレッドを表示
		m_nKittingYosoSagyogunIndex = m_cListYosoSagyogun.GetItemRowData(Row);
		SetYosoSagyoList();
	}*/
// By Y.Itabashi (xxxxx) 2007.01.29
	
	*pResult = 0;
}

void CSetKittingDlg::OnItemchangedListYosoSagyo(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	int Row = pNMListView->iItem;
	int Col = pNMListView->iSubItem;

	// 削除フラグのON/OFFを切り替える
// --Add 2007/03/06 aizawa 不懸(84)
//	if (Row >= 0) {
	if (Row >= 0 && (pNMListView->uNewState & LVIS_SELECTED)) {
// --Add
		SSubline *pSubline = g_pDataManager->GetSublineData(m_nSublineId);

		SKittingYosoSagyogun &cKittingYosoSagyogun = pSubline->aKittingYosoSagyogun[m_nKittingYosoSagyogunIndex];
		SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[m_cListYosoSagyo.GetItemRowData(Row)];

		if (cKittingYosoSagyo.bSakujoFlag) {
			cKittingYosoSagyo.bSakujoFlag = FALSE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_FUYONO);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0xff));
		} else {
			cKittingYosoSagyo.bSakujoFlag = TRUE;
			m_cListYosoSagyo.SetItemRowColor(Row, COLOR_TEXT, COLOR_FUYOYES);
//			m_cSpreadYosoSagyo.SetBkColor(Row, -1, RGB(0xff, 0xff, 0));
		}

		SetKoteiList(FALSE);
		SetYosoSagyogunList(FALSE);

		// 合計時間を計算
		double fKitNashiJikan = 0;
		double fKitAriJikan = 0;
		int nYosoSagyoNum = cKittingYosoSagyogun.aKittingYosoSagyo.GetSize();
		for (int i = 0; i < nYosoSagyoNum; i++) {
			SKittingYosoSagyo &cKittingYosoSagyo = cKittingYosoSagyogun.aKittingYosoSagyo[i];

			fKitNashiJikan += cKittingYosoSagyo.fTime;
			if (!cKittingYosoSagyo.bSakujoFlag)
				fKitAriJikan += cKittingYosoSagyo.fTime;
		}

		// 合計時間表示
		DispTotalTime(fKitNashiJikan, fKitAriJikan);
	}
	
	*pResult = 0;
}
// By Y.Itabashi (xxxxx) 2007.01.26

LRESULT CSetKittingDlg::OnListExClick(WPARAM wParam, LPARAM lParam)
{
	NMHDR* pNMHdr = (NMHDR*)wParam;
	LRESULT result;

	switch (pNMHdr->idFrom) {
	case IDC_LIST_BUHIN:
		OnItemchangedListBuhin(pNMHdr, &result);
		break;
	case IDC_LIST_KOTEI:
		OnItemchangedListKotei(pNMHdr, &result);
		break;
	}

	return 0;
}
