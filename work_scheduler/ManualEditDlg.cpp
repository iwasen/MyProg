// ManualEditDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "DataManager.h"
#include "EVal.h"

#include "MenuDispCtrl.h"

#include "ManualEditDlg001.h"
#include "ManualEditDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// 工程リストカラム位置
#define	COL_KOTEI_SUBLINE		0
#define	COL_KOTEI_KIGOU			1
#define	COL_KOTEI_BANGO			2
#define	COL_KOTEI_NAME			3
#define	COL_KOTEI_JIDOU			4

// 要素作業群リストカラム位置
#define	COL_YOSOSAGYOGUN_LISTNO		0
#define	COL_YOSOSAGYOGUN_NAME		1

// 要素作業リストカラム位置
#define	COL_YOSOSAGYO_SAGYONO		0
#define	COL_YOSOSAGYO_YOSOSAGYO		1
#define	COL_YOSOSAGYO_YOSOSAGYONO	2
#define	COL_YOSOSAGYO_M				3
#define	COL_YOSOSAGYO_N				4
#define	COL_YOSOSAGYO_HINDO			5
#define	COL_YOSOSAGYO_KURIKAESI		6
#define	COL_YOSOSAGYO_KOTEI			7
#define	COL_YOSOSAGYO_KIJUN		8

// 表示色
#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_KYOTSU	RGB(0xff, 0xff, 0x00)
#define	COLOR_ERROR		RGB(0xff, 0x00, 0x00)

// 編集フラグ
#define EDIT_NONE		0
#define EDIT_CHANGE		1
#define EDIT_DELETE		2
#define EDIT_INSERT		3


/////////////////////////////////////////////////////////////////////////////
// CManualEditDlg ダイアログ


CManualEditDlg::CManualEditDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualEditDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualEditDlg)
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。
	//}}AFX_DATA_INIT

	m_nSublineId = -1;
	m_nSagyoshaId = -1;
}


void CManualEditDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualEditDlg)
	DDX_Control(pDX, IDC_BUTTON_YOSO_SAGYO_TSUIKA, m_cButtonYosoSagyoTsuika);
	DDX_Control(pDX, IDC_BUTTON_YOSO_SAGYO_SAKUJO, m_cButtonYosoSagyoSakujo);
	DDX_Control(pDX, IDC_EDIT_KISHU_NAME, m_cEditKishuName);
	DDX_Control(pDX, IDC_LIST_KOTEI, m_cListKotei);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYOGUN, m_cListYosoSagyogun);
	DDX_Control(pDX, IDC_LIST_YOSO_SAGYO, m_cListYosoSagyo);
	DDX_Control(pDX, IDC_COMBO_SAGYOSHA, m_cComboSagyosha);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualEditDlg, CDialogEx)
	//{{AFX_MSG_MAP(CManualEditDlg)
	ON_CBN_SELCHANGE(IDC_COMBO_SAGYOSHA, OnSelchangeComboSagyosha)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_KOTEI, OnItemchangedListKotei)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST_YOSO_SAGYOGUN, OnItemchangedListYosoSagyogun)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYO_SAKUJO, OnButtonYosoSagyoSakujo)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYOGUN_SAKUJO, OnButtonYosoSagyogunSakujo)
	ON_BN_CLICKED(IDC_BUTTON_KOTEI_SAKUJO, OnButtonKoteiSakujo)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYO_TSUIKA, OnButtonYosoSagyoTsuika)
	ON_BN_CLICKED(IDC_BUTTON_YOSO_SAGYOGUN_TSUIKA, OnButtonYosoSagyogunTsuika)
	ON_BN_CLICKED(IDC_BUTTON_KOTEI_TSUIKA, OnButtonKoteiTsuika)
	ON_BN_CLICKED(IDC_BUTTON_PREVIEW, OnButtonPreview)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USER + 0x0502, CreateComboList)
	ON_MESSAGE(WM_USER + 0x0503, OnChangeCombo)
	ON_MESSAGE(WM_USER + 0x0504, OnChangeEdit)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualEditDlg メッセージ ハンドラ

BOOL CManualEditDlg::OnInitDialog() 
{
	CDialogEx::OnInitDialog();
	
// Modify ... ( CHANGE )
	// 要素作業テンプレートを読み込み
	if (g_pDataManager->GetYosoSagyoTemplateNum() == 0) {
//		if (!g_pDataManager->ReadYosoSagyoTemplate()) {
			EndDialog(IDCANCEL);
			return TRUE;
//		}
	}
// By Y.Itabashi (xxxxx) 2007.02.16

	// メニューにメニューファイルの文字列をセット
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualEditDlg );

	// リストのカラム名をセット
	SetLixxxolumn( m_cListKotei, Menu_ManualEditDlg, List_ManualEditKotei, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyogun, Menu_ManualEditDlg, List_ManualEditYosoSagyogun, eSELECTTYPE_SINGLE );
	SetLixxxolumn( m_cListYosoSagyo, Menu_ManualEditDlg, List_ManualEditYosoSagyo, eSELECTTYPE_SINGLE );

	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();

	// リストデータ作成
	MakeSagyoshaList();

	// 作業者コンボボックスを設定
	SetSagyoshaList();

	// デフォルトの作業者を選択
	if (m_nSagyoshaId != -1) {
		int nCount = m_cComboSagyosha.GetCount();
		for (int i = 0; i < nCount; i++) {
			int nIndex = m_cComboSagyosha.GetItemData(i);
			if (m_aSagyoshaList[nIndex].nSagyoshaId == m_nSagyoshaId) {
				m_cComboSagyosha.SetCurSel(i);
				OnSelchangeComboSagyosha();
				break;
			}
		}
	}

	// 機種名を表示
	if (m_nKishuId != -1)
		m_cEditKishuName = g_pDataManager->GetKishuData(m_nKishuId)->sKishuName;

	// コントロールの位置を調整
	SetCtlPosition(IDC_STATIC005, 0, 0, 0, 0.5);
	SetCtlPosition(IDC_LIST_KOTEI, 0, 0, 0, 0.5);
	SetCtlPosition(IDC_STATIC013, 0, 0.5, 0, 1);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYOGUN_TSUIKA, 0, 0.5, 0, 0.5);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYOGUN_SAKUJO, 0, 0.5, 0, 0.5);
	SetCtlPosition(IDC_LIST_YOSO_SAGYOGUN, 0, 0.5, 0, 1);
	SetCtlPosition(IDC_STATIC018, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYO_TSUIKA, 1, 0, 1, 0);
	SetCtlPosition(IDC_BUTTON_YOSO_SAGYO_SAKUJO, 1, 0, 1, 0);
	SetCtlPosition(IDC_LIST_YOSO_SAGYO, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_PREVIEW, 1, 1, 1, 1);
	SetCtlPosition(IDOK, 1, 1, 1, 1);
	SetCtlPosition(IDCANCEL, 1, 1, 1, 1);

	return TRUE;
}

// 作業者コンボボックス選択変更
void CManualEditDlg::OnSelchangeComboSagyosha() 
{
	// 工程リスト表示
	SetKoteiList();
}

// 工程リスト選択変更
void CManualEditDlg::OnItemchangedListKotei(NMHDR* pNMHDR, LRESULT* pResult) 
{
	// 要素作業群リスト表示
	SetYosoSagyogunList();

	// 要素作業の追加・削除ボタン有効化設定
	EnableYosoSagyoTsuikaSakujo();

	*pResult = 0;
}

// 要素作業群リスト選択変更
void CManualEditDlg::OnItemchangedListYosoSagyogun(NMHDR* pNMHDR, LRESULT* pResult) 
{
	// 要素作業リスト表示
	SetYosoSagyoList();

	*pResult = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditDlg::SetSagyoshaList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業者リスト設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		左右の作業者コンボボックスに作業者を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//	
//*****************************************************************************************************
void CManualEditDlg::SetSagyoshaList()
{
	int nSize = m_aSagyoshaList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[i];

		// コンボボックスに作業者名を設定
		m_cComboSagyosha.SetItemData(m_cComboSagyosha.AddString(cSagyoshaList.sSagyoshaName), i);
	}
}

// 工程リスト設定
void CManualEditDlg::SetKoteiList(int nSelectIndex) 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];

	// リストをクリア
	m_cListKotei.DeleteAllItems();
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();

	// 工程リストにデータをセット
	int nRow = 0;
	int nSize = cSagyoshaList.aKoteiList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[i];

		if (cKoteiList.nEditFlag != EDIT_DELETE) {
			// 各カラムにデータをセット
			m_cListKotei.SetValue(nRow, COL_KOTEI_SUBLINE, cKoteiList.sSubline);
			m_cListKotei.SetValue(nRow, COL_KOTEI_KIGOU, cKoteiList.sKoteiKigo);
			m_cListKotei.SetValue(nRow, COL_KOTEI_BANGO, cKoteiList.sKoteiNo);
			m_cListKotei.SetValue(nRow, COL_KOTEI_NAME, cKoteiList.sKoteiName);
			m_cListKotei.SetValue(nRow, COL_KOTEI_JIDOU, cKoteiList.sJidoShudo);

			// 共通工程を黄色にする
			if (cKoteiList.bKyotsuKotei)
				m_cListKotei.SetItemRowColor( nRow, COLOR_TEXT, COLOR_KYOTSU );

			// エラー行を赤色にする
			if (cKoteiList.bErrorFlag)
				m_cListKotei.SetItemRowColor( nRow, COLOR_TEXT, COLOR_ERROR );

			// 行IDをセット
			m_cListKotei.SetItemRowData(nRow, i);

			// 指定された行を選択状態にする
			if (i == nSelectIndex)
				m_cListKotei.SelectChange(nRow);

			nRow++;
		}
	}
}

// 要素作業群リスト設定
void CManualEditDlg::SetYosoSagyogunList(int nSelectIndex)
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	// リストをクリア
	m_cListYosoSagyogun.DeleteAllItems();
	m_cListYosoSagyo.DeleteAllItems();

	// 要素作業群リストに要素作業群を設定
	int nRow = 0;
	int nSize = cKoteiList.aYosoSagyogunList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[i];

		if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
			// 各カラムにデータをセット
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_LISTNO, nRow + 1);
			m_cListYosoSagyogun.SetValue(nRow, COL_YOSOSAGYOGUN_NAME, cYosoSagyogunList.sYosoSagyogunName);

			// 共通工程を黄色にする
			if (cYosoSagyogunList.bKyotsuKotei)
				m_cListYosoSagyogun.SetItemRowColor( nRow, COLOR_TEXT, COLOR_KYOTSU );

			// エラー行を赤色にする
			if (cYosoSagyogunList.bErrorFlag)
				m_cListYosoSagyogun.SetItemRowColor( nRow, COLOR_TEXT, COLOR_ERROR );

			// 行IDをセット
			m_cListYosoSagyogun.SetItemRowData(nRow, i);

			// 指定された行を選択状態にする
			if (i == nSelectIndex)
				m_cListYosoSagyogun.SelectChange(nRow);

			nRow++;
		}
	}
}

// 要素作業リスト設定
void CManualEditDlg::SetYosoSagyoList(int nSelectIndex)
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// 選択されている要素作業群取得
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];

	// リストをクリア
	m_cListYosoSagyo.DeleteAllItems();

	// 自動工程は要素作業を表示しない
	if (cKoteiList.sJidoShudo == g_pConst->m_sJido)
		return;

	// 要素作業リストに要素作業を設定
// --Add 2007/02/26 aizawa 不懸D-187
	int nAdjust = 0;
// --Add
	int nRow = 0;
	int nSize = cYosoSagyogunList.aYosoSagyoList.GetSize();
	for (int i = 0; i < nSize; i++) {
		SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[i];

// --Add 2007/02/26 aizawa 不懸D-187
		// 追加削除による作業Noを補正
		if (cYosoSagyoList.nEditFlag == EDIT_INSERT)
			nAdjust++;
		else if (cYosoSagyoList.nEditFlag == EDIT_DELETE)
			nAdjust--;
// --Add

		if (cYosoSagyoList.nEditFlag != EDIT_DELETE) {
			// 各カラムにデータをセット
// --Change 2007/02/26 aizawa 不懸D-187
//			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_SAGYONO, nRow + 1);
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_SAGYONO, cYosoSagyoList.nSagyoNo + nAdjust);
// --Change
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_YOSOSAGYO, cYosoSagyoList.sYosoSagyoName);
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_YOSOSAGYONO, cYosoSagyoList.sYosoSagyoNo);
			if (cYosoSagyoList.bM)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_M, cYosoSagyoList.fM);
			if (cYosoSagyoList.bN)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_N, cYosoSagyoList.fN);
			if (cYosoSagyoList.bHassexxxndo)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_HINDO, cYosoSagyoList.nHassexxxndo);
			if (cYosoSagyoList.bKurikaeshiKaisu)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_KURIKAESI, cYosoSagyoList.nKurikaeshiKaisu);
			m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_KOTEI, cYosoSagyoList.sKoteibuHendobu);
			if (cYosoSagyoList.bKijunKotei)
				m_cListYosoSagyo.SetValue(nRow, COL_YOSOSAGYO_KIJUN, GetKijunKoteiString(cYosoSagyoList.nKijunKotei));

			// エラー行を赤色にする
			if (cYosoSagyoList.bErrorFlag)
				m_cListYosoSagyo.SetItemRowColor( nRow, COLOR_TEXT, COLOR_ERROR );

			// 行IDをセット
			m_cListYosoSagyo.SetItemRowData(nRow, i);

			// 指定された行を選択状態にする
			if (i == nSelectIndex)
				m_cListYosoSagyo.SelectChange(nRow);

			nRow++;
		}
	}
}

// 工程リストの削除
void CManualEditDlg::OnButtonKoteiSakujo() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	// 実行確認メッセージ
	if (cKoteiList.bKyotsuKotei) {
		if (g_pSystem->DispMessage("W014003") == IDCANCEL)
			return;
	} else {
		if (g_pSystem->DispMessage("W014006") == IDCANCEL)
			return;
	}

	// 削除状態に設定
	cKoteiList.nEditFlag = EDIT_DELETE;

	// 工程リスト表示
	SetKoteiList();
}

// 要素作業群リストの削除
void CManualEditDlg::OnButtonYosoSagyogunSakujo() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// 選択されている要素作業群取得
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];

	// 実行確認メッセージ
	if (cYosoSagyogunList.bKyotsuKotei) {
		if (g_pSystem->DispMessage("W014004") == IDCANCEL)
			return;
	} else {
		if (g_pSystem->DispMessage("W014007") == IDCANCEL)
			return;
	}

	// 削除状態に設定
	cYosoSagyogunList.nEditFlag = EDIT_DELETE;

	// 要素作業群リスト表示
	SetYosoSagyogunList();
}

// 要素作業リストの削除
void CManualEditDlg::OnButtonYosoSagyoSakujo() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// 選択されている要素作業群取得
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	// 選択されている要素作業取得
	int nSelectYosoSagyo = GetSelectYosoSagyo();
	if (nSelectYosoSagyo == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];
	SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nSelectYosoSagyo];

	// 実行確認メッセージ
	if (cYosoSagyogunList.bKyotsuKotei) {
		if (g_pSystem->DispMessage("W014005") == IDCANCEL)
			return;
	} else {
		if (g_pSystem->DispMessage("W014008") == IDCANCEL)
			return;
	}

	// 削除状態に設定
//	cYosoSagyoList.nEditFlag = EDIT_DELETE;
	if (cYosoSagyoList.nYosoSagyoId == -1)
		cYosoSagyogunList.aYosoSagyoList.RemoveAt(nSelectYosoSagyo);
	else
		cYosoSagyoList.nEditFlag = EDIT_DELETE;

	// 要素作業リスト表示
	SetYosoSagyoList();
}

// 工程リストの追加
void CManualEditDlg::OnButtonKoteiTsuika() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];

	// 選択されている工程取得
	int nSelectKotei = nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		nSelectKotei = cSagyoshaList.aKoteiList.GetSize();

	// 新規レコード追加
	SKoteiList cKoteiList;
	cKoteiList.nIndex = -1;
	cKoteiList.nEditFlag = EDIT_INSERT;
	cKoteiList.bErrorFlag = FALSE;
	cKoteiList.sSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId)->sSublineName;
	cKoteiList.sKoteiKigo = cSagyoshaList.sKoteiKigo;
	cKoteiList.bKyotsuKotei = FALSE;
	cSagyoshaList.aKoteiList.InsertAt(nSelectKotei, cKoteiList);

	// 工程リスト表示
	SetKoteiList(nSelectKotei);
}

// 要素作業群リストの追加
void CManualEditDlg::OnButtonYosoSagyogunTsuika() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	// 選択されている要素作業群取得
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		nSelectYosoSagyogun = cKoteiList.aYosoSagyogunList.GetSize();

	// 新規レコード追加
	SYosoSagyogunList cYosoSagyogunList;
	cYosoSagyogunList.nIndex = -1;
	cYosoSagyogunList.nEditFlag = EDIT_INSERT;
	cYosoSagyogunList.bErrorFlag = FALSE;
	cYosoSagyogunList.nYosoSagyogunId = -1;
	cYosoSagyogunList.bKyotsuKotei = FALSE;
	cKoteiList.aYosoSagyogunList.InsertAt(nSelectYosoSagyogun, cYosoSagyogunList);

	// 要素作業群リスト表示
	SetYosoSagyogunList(nSelectYosoSagyogun);
}

// 要素作業リストの追加
void CManualEditDlg::OnButtonYosoSagyoTsuika() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// 選択されている要素作業群取得
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];

	// 選択されている要素作業取得
	int nSelectYosoSagyo = GetSelectYosoSagyo();
	if (nSelectYosoSagyo == -1)
		nSelectYosoSagyo = cYosoSagyogunList.aYosoSagyoList.GetSize();

	// 新規レコード追加
	SYosoSagyoList cYosoSagyoList;
	cYosoSagyoList.nIndex = -1;
	cYosoSagyoList.nEditFlag = EDIT_INSERT;
	cYosoSagyoList.bErrorFlag = FALSE;
// --Add 2007/02/26 aizawa 不懸D-187
	cYosoSagyoList.nSagyoNo = (nSelectYosoSagyo == 0) ? 0 : cYosoSagyogunList.aYosoSagyoList[nSelectYosoSagyo - 1].nSagyoNo;
// --Add
	cYosoSagyoList.nYosoSagyoId = -1;
	cYosoSagyoList.bM = FALSE;
	cYosoSagyoList.bN = FALSE;
	cYosoSagyoList.bHassexxxndo = FALSE;
	cYosoSagyoList.bKurikaeshiKaisu = FALSE;
	cYosoSagyoList.bKijunKotei = FALSE;
	cYosoSagyogunList.aYosoSagyoList.InsertAt(nSelectYosoSagyo, cYosoSagyoList);

	// 要素作業リスト表示
	SetYosoSagyoList(nSelectYosoSagyo);
}

// リストコントロール変更
LRESULT CManualEditDlg::OnChangeEdit(WPARAM wp, LPARAM lp)
{
	LV_DISPINFO* pDispInfo = (LV_DISPINFO*)wp;
	LV_ITEM		 *plvItem = &pDispInfo->item;

	int nRow = plvItem->iItem;
	int nCol = plvItem->iSubItem;

	if (pDispInfo->hdr.hwndFrom == m_cListKotei.m_hWnd)
		EditKotei(nRow, nCol);
	else if (pDispInfo->hdr.hwndFrom == m_cListYosoSagyogun.m_hWnd)
		EditYosoSagyogun(nRow, nCol);
	else if (pDispInfo->hdr.hwndFrom == m_cListYosoSagyo.m_hWnd)
		EditYosoSagyo(nRow, nCol);

// Modify ... ( ADD )
	m_bCloseLock = 0;
// By Y.Itabashi (xxxxx) 2007.03.05

	return TRUE;
}

// 工程リスト変更
void CManualEditDlg::EditKotei(int nRow, int nCol)
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[m_cListKotei.GetItemRowData(nRow)];

	// 変更されたデータを取得
	switch (nCol) {
	case COL_KOTEI_BANGO:
		m_cListKotei.GetValue(nRow, nCol, cKoteiList.sKoteiNo);
		break;
	case COL_KOTEI_NAME:
		m_cListKotei.GetValue(nRow, nCol, cKoteiList.sKoteiName);
		break;
	case COL_KOTEI_JIDOU:
		m_cListKotei.GetValue(nRow, nCol, cKoteiList.sJidoShudo);
		SetYosoSagyoList();
		EnableYosoSagyoTsuikaSakujo();
		break;
	}
}

// 要素作業群リスト変更
void CManualEditDlg::EditYosoSagyogun(int nRow, int nCol)
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[m_cListYosoSagyogun.GetItemRowData(nRow)];

	// 変更されたデータを取得
	switch (nCol) {
	case COL_YOSOSAGYOGUN_NAME:
		m_cListYosoSagyogun.GetValue(nRow, nCol, cYosoSagyogunList.sYosoSagyogunName);
		break;
	}
}

// 要素作業リスト変更
void CManualEditDlg::EditYosoSagyo(int nRow, int nCol)
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	// 選択されている要素作業群取得
	int nSelectYosoSagyogun = GetSelectYosoSagyogun();
	if (nSelectYosoSagyogun == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];
	SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nSelectYosoSagyogun];
	SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[m_cListYosoSagyo.GetItemRowData(nRow)];
	CString sKijunKotei;

	// 変更されたデータを取得
	switch (nCol) {
	case COL_YOSOSAGYO_YOSOSAGYO:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.sYosoSagyoName);
		break;
	case COL_YOSOSAGYO_YOSOSAGYONO:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.sYosoSagyoNo);
		SetYosoSagyoTemplate(cYosoSagyoList);
		break;
	case COL_YOSOSAGYO_M:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.fM);
		cYosoSagyoList.bM = TRUE;
		break;
	case COL_YOSOSAGYO_N:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.fN);
		cYosoSagyoList.bN = TRUE;
		break;
	case COL_YOSOSAGYO_HINDO:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.nHassexxxndo);
		cYosoSagyoList.bHassexxxndo = TRUE;
		break;
	case COL_YOSOSAGYO_KURIKAESI:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.nKurikaeshiKaisu);
		cYosoSagyoList.bKurikaeshiKaisu = TRUE;
		break;
	case COL_YOSOSAGYO_KOTEI:
		m_cListYosoSagyo.GetValue(nRow, nCol, cYosoSagyoList.sKoteibuHendobu);
		break;
	case COL_YOSOSAGYO_KIJUN:
		m_cListYosoSagyo.GetValue(nRow, nCol, sKijunKotei);
		cYosoSagyoList.nKijunKotei = (sKijunKotei == g_pSystem->m_cMenuDisp.GetMenu(Menu_ManualEditDlg[37].MenuNo, Menu_ManualEditDlg[37].ItemNo)) ? 1 : 2;
		cYosoSagyoList.bKijunKotei = TRUE;
		break;
	}
}

// 要素作業テンプレートからデータを設定
void CManualEditDlg::SetYosoSagyoTemplate(SYosoSagyoList &cYosoSagyoList)
{
	SYosoSagyoTemplate *pYosoSagyoTemplate = g_pDataManager->GetYosoSagyoTemplateData(cYosoSagyoList.sYosoSagyoNo);
	if (pYosoSagyoTemplate != NULL) {
		// 要素作業テンプレートからデータを設定
// Modify ... ( ADD )
		cYosoSagyoList.sYosoSagyoName = pYosoSagyoTemplate->sSagyoNaiyo;
// By Y.Itabashi (xxxxx) 2007.02.16
		cYosoSagyoList.sEnzanShiki = pYosoSagyoTemplate->sEnzanShiki;
		cYosoSagyoList.fS1 = pYosoSagyoTemplate->fS1;
		cYosoSagyoList.fS2 = pYosoSagyoTemplate->fS2;
		cYosoSagyoList.fM = pYosoSagyoTemplate->fM;
		cYosoSagyoList.bM = TRUE;
		cYosoSagyoList.fN = pYosoSagyoTemplate->fN;
		cYosoSagyoList.bN = TRUE;
//		cYosoSagyoList.nHassexxxndo = pYosoSagyoTemplate->nHassexxxndo;
//		cYosoSagyoList.bHassexxxndo = TRUE;
//		cYosoSagyoList.nKurikaeshiKaisu = pYosoSagyoTemplate->nKurikaeshiKaisu;
//		cYosoSagyoList.bKurikaeshiKaisu = TRUE;
		cYosoSagyoList.sSagyoFlag = pYosoSagyoTemplate->sSagyoFlag;

		// 要素作業リスト表示
		SetYosoSagyoList();
	}
}

// リストビューのコンボボックスを作成
LRESULT CManualEditDlg::CreateComboList(WPARAM wp, LPARAM lp)
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)wp;

	int nRow = pNMListView->iItem;
	int nCol = pNMListView->iSubItem;

	CStringList	lstGroupNo;
	CString sValue;
	int i;
	int nIndex = 0;
	int nSize;

	switch( pNMListView->hdr.idFrom ){
	case IDC_LIST_KOTEI:
		m_cListKotei.GetValue( nRow, nCol, sValue );
		for (i = 0; i < 3; i++) {
			CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualEditDlg[32+i].MenuNo, Menu_ManualEditDlg[32+i].ItemNo );
			lstGroupNo.AddTail( str );
			if( str.Compare( sValue ) == 0 )
				nIndex = i;
		}
		m_cListKotei.SetComboList( nRow, nCol, lstGroupNo, nIndex );
		break;

	case IDC_LIST_YOSO_SAGYO:
		switch( nCol ){
		case COL_YOSOSAGYO_YOSOSAGYONO:
			m_cListYosoSagyo.GetValue( nRow, nCol, sValue );
			nSize = g_pDataManager->GetYosoSagyoTemplateNum();
			for (i = 0; i < nSize; i++) {
				SYosoSagyoTemplate *pYosoSagyoTemplate = g_pDataManager->GetYosoSagyoTemplateData(i);
				lstGroupNo.AddTail( pYosoSagyoTemplate->sYosoSagyoNo );
				if(sValue == pYosoSagyoTemplate->sYosoSagyoNo )
					nIndex = i;
			}
			m_cListYosoSagyo.SetComboList( nRow, nCol, lstGroupNo, nIndex );
			break;

		case COL_YOSOSAGYO_KOTEI:
			m_cListYosoSagyo.GetValue( nRow, nCol, sValue );
			for (i = 0; i < 2; i++) {
				CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualEditDlg[35+i].MenuNo, Menu_ManualEditDlg[35+i].ItemNo );
				lstGroupNo.AddTail( str );
				if( str.Compare( sValue ) == 0 )
					nIndex = i;
			}
			m_cListYosoSagyo.SetComboList( nRow, nCol, lstGroupNo, nIndex );
			break;

		case COL_YOSOSAGYO_KIJUN:
			m_cListYosoSagyo.GetValue( nRow, nCol, sValue );
			for (i = 0; i < 2; i++) {
				CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualEditDlg[37+i].MenuNo, Menu_ManualEditDlg[37+i].ItemNo );
				lstGroupNo.AddTail( str );
				if( str.Compare( sValue ) == 0 )
					nIndex = i;
			}
			m_cListYosoSagyo.SetComboList( nRow, nCol, lstGroupNo, nIndex );
			break;

		}
		break;

	default:
		break;
	}

	return TRUE;
}

// リストビューのコンボボックスの選択変更
LRESULT CManualEditDlg::OnChangeCombo(WPARAM wp, LPARAM lp)
{
	LV_DISPINFO *dispinfo = (LV_DISPINFO*)wp;

	int nRow = dispinfo->item.iItem;
	int nCol = dispinfo->item.iSubItem;
	
	CString sValue;
	if (dispinfo->hdr.hwndFrom == m_cListKotei.m_hWnd) {
		m_cListKotei.SetValue(nRow, nCol, dispinfo->item.pszText);
		EditKotei(nRow, nCol);
	} else if (dispinfo->hdr.hwndFrom == m_cListYosoSagyo.m_hWnd) {
		m_cListYosoSagyo.SetValue(nRow, nCol, dispinfo->item.pszText);
		EditYosoSagyo(nRow, nCol);
	}

	return TRUE;
}

// 基準・工程コードを文字に変換
CString CManualEditDlg::GetKijunKoteiString(int nKijunKotei)
{
	switch (nKijunKotei) {
	case 1:
		return g_pConst->m_sKijun;
	case 2:
		return g_pConst->m_sKotei;
	}
	return "";
}

// 作業者リストを作成
void CManualEditDlg::MakeSagyoshaList()
{
	// 作業者によるループ
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag) {
			// 作業者データを設定
			int nSagyoshNum = g_pDataManager->GetSagyoshaNum(nSublineId);
			for (int nSagyoshId = 0; nSagyoshId < nSagyoshNum; nSagyoshId++) {
				SSagyoshaList *pSagyoshaList = m_aSagyoshaList.AddElement();
				pSagyoshaList->nSublineId = nSublineId;
				pSagyoshaList->nSagyoshaId = nSagyoshId;
				pSagyoshaList->sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, nSagyoshId);
				pSagyoshaList->sKoteiKigo = pSubline->aYosoSagyogun[0].sKoteiKigo;

				// 工程リストを作成
				MakeKoteiList(nSublineId, nSagyoshId, pSagyoshaList->aKoteiList);
			}
		}
	}

	// 変更箇所をチェックするため、元データを保存しておく
	m_aSagyoshaList2 = m_aSagyoshaList;
}

// 工程リストを作成
void CManualEditDlg::MakeKoteiList(int nSublineId, int nSagyoshId, CKoteiListArray &aKoteiList)
{
	SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
	int nIndex;

	// 作業者の要素作業群を取得
	CIntArrayEx aYosoSagyogunId;
	g_pDataManager->FindSagyoWariate(nSublineId, nSagyoshId, m_nKishuId, aYosoSagyogunId);

	// 要素作業群から工程情報を取得
	int nSize = aYosoSagyogunId.GetSize();
	for (int i = 0; i < nSize; i++) {
		int nYosoSagyogunId = aYosoSagyogunId[i];
		SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];

		SKoteiList *pKoteiList = aKoteiList.LookupElement(cYosoSagyogun.sKoteiName);
		if (pKoteiList == NULL) {
			pKoteiList = aKoteiList.AddElement(cYosoSagyogun.sKoteiName, nIndex);
			pKoteiList->nIndex = nIndex;
			pKoteiList->nEditFlag = EDIT_NONE;
			pKoteiList->bErrorFlag = FALSE;
			pKoteiList->sSubline = pSubline->sSublineName;
			pKoteiList->sKoteiKigo = cYosoSagyogun.sKoteiKigo;
			pKoteiList->sKoteiNo = cYosoSagyogun.sKoteiNo;
			pKoteiList->sKoteiName = cYosoSagyogun.sKoteiName;
			pKoteiList->sJidoShudo = cYosoSagyogun.sJidoKotei;
			pKoteiList->bKyotsuKotei = FALSE;
		}
		pKoteiList->bKyotsuKotei |= cYosoSagyogun.aKishuSagyo[m_nKishuId].bKyotsuKotei;

		// 要素作業群リストを作成
		MakeYosoSagyogunList(nYosoSagyogunId, cYosoSagyogun, pKoteiList->aYosoSagyogunList);
	}
}

// 要素作業群リストを作成
void CManualEditDlg::MakeYosoSagyogunList(int nYosoSagyogunId, SYosoSagyogun &cYosoSagyogun, CYosoSagyogunListArray &aYosoSagyogunList)
{
	int nIndex;

	SYosoSagyogunList *pYosoSagyogunList = aYosoSagyogunList.AddElement(nIndex);
	pYosoSagyogunList->nIndex = nIndex;
	pYosoSagyogunList->nEditFlag = EDIT_NONE;
	pYosoSagyogunList->bErrorFlag = FALSE;
	pYosoSagyogunList->nYosoSagyogunId = nYosoSagyogunId;
	pYosoSagyogunList->sYosoSagyogunName = cYosoSagyogun.sYosoSagyogunName;
	pYosoSagyogunList->bKyotsuKotei = cYosoSagyogun.aKishuSagyo[m_nKishuId].bKyotsuKotei;

	// 要素作業リストを作成
	MakeYosoSagyoList(cYosoSagyogun, pYosoSagyogunList->aYosoSagyoList);
}

// 要素作業リストを作成
void CManualEditDlg::MakeYosoSagyoList(SYosoSagyogun &cYosoSagyogun, CYosoSagyoListArray &aYosoSagyoList)
{
	int nIndex;

	int nSize = cYosoSagyogun.aYosoSagyo.GetSize();
	for (int nYosoSagyoId = 0; nYosoSagyoId < nSize; nYosoSagyoId++) {
		SYosoSagyo &cYosoSagyo = cYosoSagyogun.aYosoSagyo[nYosoSagyoId];

		if (cYosoSagyo.sDeleteFlag.IsEmpty()) {
			SYosoSagyoList *pYosoSagyoList = aYosoSagyoList.AddElement(nIndex);
			pYosoSagyoList->nIndex = nIndex;
			pYosoSagyoList->nEditFlag = EDIT_NONE;
			pYosoSagyoList->bErrorFlag = FALSE;
			pYosoSagyoList->nYosoSagyoId = nYosoSagyoId;
			pYosoSagyoList->sYosoSagyoName = cYosoSagyo.sYosoSagyoName;
			pYosoSagyoList->sYosoSagyoNo = cYosoSagyo.sYosoSagyoNo;
// --Add 2007/02/26 aizawa 不懸D-187
			pYosoSagyoList->nSagyoNo = cYosoSagyo.nSagyoNo;
// --Add
			pYosoSagyoList->sEnzanShiki = cYosoSagyo.sEnzanShiki;
			pYosoSagyoList->fS1 = cYosoSagyo.fS1;
			pYosoSagyoList->fS2 = cYosoSagyo.fS2;
			pYosoSagyoList->fM = cYosoSagyo.fM;
			pYosoSagyoList->bM = TRUE;
			pYosoSagyoList->fN = cYosoSagyo.fN;
			pYosoSagyoList->bN = TRUE;
			pYosoSagyoList->nHassexxxndo = cYosoSagyo.nHassexxxndo;
			pYosoSagyoList->bHassexxxndo = TRUE;
			pYosoSagyoList->nKurikaeshiKaisu = cYosoSagyo.nKurikaeshiKaisu;
			pYosoSagyoList->bKurikaeshiKaisu = TRUE;
			pYosoSagyoList->sKoteibuHendobu = cYosoSagyo.sKoteibuHendobu;
			pYosoSagyoList->sSagyoFlag = cYosoSagyo.sSagyoFlag;
			pYosoSagyoList->nKijunKotei = cYosoSagyo.nKijunKotei;
			pYosoSagyoList->bKijunKotei = TRUE;
		}
	}
}

// 選択されている作業者を取得
int CManualEditDlg::GetSelectSagyosha()
{
	int nIndex = m_cComboSagyosha.GetCurSel();
	if (nIndex == -1)
		return -1;

	return m_cComboSagyosha.GetItemData(nIndex);
}

// 選択されている工程を取得
int CManualEditDlg::GetSelectKotei()
{
	POSITION pos = m_cListKotei.GetFirstSelectedItemPosition();
	int nRow = m_cListKotei.GetNextSelectedItem(pos);
	if (nRow < 0)
		return -1;

	return m_cListKotei.GetItemRowData(nRow);
}

// 選択されている要素作業群を取得
int CManualEditDlg::GetSelectYosoSagyogun()
{
	POSITION pos = m_cListYosoSagyogun.GetFirstSelectedItemPosition();
	int nRow = m_cListYosoSagyogun.GetNextSelectedItem(pos);
	if (nRow < 0)
		return -1;

	return m_cListYosoSagyogun.GetItemRowData(nRow);
}

// 選択されている要素作業を取得
int CManualEditDlg::GetSelectYosoSagyo()
{
	POSITION pos = m_cListYosoSagyo.GetFirstSelectedItemPosition();
	int nRow = m_cListYosoSagyo.GetNextSelectedItem(pos);
	if (nRow < 0)
		return -1;

	return m_cListYosoSagyo.GetItemRowData(nRow);
}

// 要素作業の追加・削除ボタンの有効・無効を設定
void CManualEditDlg::EnableYosoSagyoTsuikaSakujo()
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;

	// 選択されている工程取得
	int nSelectKotei = GetSelectKotei();
	if (nSelectKotei == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];
	SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nSelectKotei];

	if (cKoteiList.sJidoShudo == g_pConst->m_sJido) {
		// 追加・削除ボタンを無効
		m_cButtonYosoSagyoSakujo.EnableWindow(FALSE);
		m_cButtonYosoSagyoTsuika.EnableWindow(FALSE);
		return;
	} else {
		// 追加・削除ボタンを有効
		m_cButtonYosoSagyoSakujo.EnableWindow(TRUE);
		m_cButtonYosoSagyoTsuika.EnableWindow(TRUE);
	}
}

// OKボタンクリック
void CManualEditDlg::OnOK() 
{
	// 入力データチェック
	if (!CheckData())
		return;

	CWaitCursor cWaitCursor;

	// 変更処理
	ChangeData();

	// 削除処理
	DeleteData();

	// 追加処理
	InsertData();

// --Add 2007/02/26 aizawa 不懸D-187
	// 作業Noリナンバ
	g_pDataManager->RenumberSagyoNo();
// --Add
	
	CDialogEx::OnOK();
}

// キャンセルボタンクリック
void CManualEditDlg::OnCancel() 
{
// Modify ... ( ADD )
	if( m_bCloseLock )
		return;
// By Y.Itabashi (xxxxx) 2007.03.05

	CDialogEx::OnCancel();
}

// プレビューボタンクリック
void CManualEditDlg::OnButtonPreview() 
{
	// 選択されている作業者取得
	int nSelectSagyosha = GetSelectSagyosha();
	if (nSelectSagyosha == -1)
		return;
	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSelectSagyosha];

	// 入力データチェック
	if (!CheckData())
		return;

	CWaitCursor cWaitCursor;

	// リストデータを保存
	CSagyoshaListArray aSagyoshaList;
	aSagyoshaList = m_aSagyoshaList;

	// 変更処理
	ChangeData();

	// 削除処理
	DeleteData();

	// 追加処理
	InsertData();

// --Add 2007/02/26 aizawa 不懸D-187
	// 作業Noリナンバ
	g_pDataManager->RenumberSagyoNo();
// --Add

	// 手動編成画面を更新
	m_pDoc->DispCenterSagyosha(cSagyoshaList.nSublineId, cSagyoshaList.nSagyoshaId);

	// リストデータを復元
	g_pDataManager->RestoreUndoData();
	m_aSagyoshaList = aSagyoshaList;
}

// 指定したリストを表示
void CManualEditDlg::DispList(int nSagyoshaIndex, int nKoteiIndex, int nYosoSagyogunIndex)
{
	int i;
	int nCount;

	// 作業者を表示
	nCount = m_cComboSagyosha.GetCount();
	for (i = 0; i < nCount; i++) {
		if ((int)m_cComboSagyosha.GetItemData(i) == nSagyoshaIndex) {
			m_cComboSagyosha.SetCurSel(i);
			SetKoteiList();
			break;
		}
	}

	// 工程を表示
	if (nKoteiIndex != -1) {
		nCount = m_cListKotei.GetItemCount();
		for (i = 0; i < nCount; i++) {
			if ((int)m_cListKotei.GetItemRowData(i) == nKoteiIndex) {
				m_cListKotei.SelectChange(i);
				break;
			}
		}
	}

	// 要素作業群を表示
	if (nYosoSagyogunIndex != -1) {
		nCount = m_cListYosoSagyogun.GetItemCount();
		for (i = 0; i < nCount; i++) {
			if ((int)m_cListYosoSagyogun.GetItemRowData(i) == nYosoSagyogunIndex) {
				m_cListYosoSagyogun.SelectChange(i);
				break;
			}
		}
	}
}

// 入力データチェック
BOOL CManualEditDlg::CheckData()
{
	// エラーフラグクリア
	ClearErrorFlag();

	// 変更フラグセット
	SetChangeFlag();

	// 未入力チェック
	if (!CheckMinyuryoku())
		return FALSE;

	// 入力データ有効チェック
	if (!CheckInputData())
		return FALSE;

	// 同一工程存在チェック
	if (!CheckSameKotei())
		return FALSE;

	// 同一要素作業群存在チェック
	if (!CheckSameYosoSagyogun())
		return FALSE;

	// 要素作業群存在チェック
	if (!CheckYosoSagyogun())
		return FALSE;

	// 要素作業存在チェック
	if (!CheckYosoSagyo())
		return FALSE;

	// 共通工程編集チェック
	if (!CheckKyotsuKotei())
		return FALSE;

	return TRUE;
}

// エラーフラグクリア
void CManualEditDlg::ClearErrorFlag()
{
	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			// 工程エラーフラグクリア
			cKoteiList.bErrorFlag = FALSE;

			// 要素作業群リストによるループ
			int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
			for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
				SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

				// 要素作業群エラーフラグクリア
				cYosoSagyogunList.bErrorFlag = FALSE;

				// 要素作業リストによるループ
				int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
				for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
					SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

					// 要素作業エラーフラグクリア
					cYosoSagyoList.bErrorFlag = FALSE;
				}
			}
		}
	}
}

// 変更フラグセット
void CManualEditDlg::SetChangeFlag()
{
	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSagyoshaList &cSagyoshaList2 = m_aSagyoshaList2[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];
			if (cKoteiList.nIndex != -1 && cKoteiList.nEditFlag != EDIT_DELETE) {
				SKoteiList &cKoteiList2 = cSagyoshaList2.aKoteiList[cKoteiList.nIndex];

				// 工程の元データと入力データを比較
				if (cKoteiList.sKoteiNo != cKoteiList2.sKoteiNo ||
						cKoteiList.sKoteiName != cKoteiList2.sKoteiName ||
						cKoteiList.sJidoShudo != cKoteiList2.sJidoShudo)
					cKoteiList.nEditFlag = EDIT_CHANGE;
				else
					cKoteiList.nEditFlag = EDIT_NONE;

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];
					if (cYosoSagyogunList.nIndex != -1 && cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						SYosoSagyogunList &cYosoSagyogunList2 = cKoteiList2.aYosoSagyogunList[cYosoSagyogunList.nIndex];

						// 要素作業群の元データと入力データを比較
						if (cYosoSagyogunList.sYosoSagyogunName != cYosoSagyogunList2.sYosoSagyogunName)
							cYosoSagyogunList.nEditFlag = EDIT_CHANGE;
						else
							cYosoSagyogunList.nEditFlag = EDIT_NONE;

						// 要素作業リストによるループ
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];
							if (cYosoSagyoList.nIndex != -1 && cYosoSagyoList.nEditFlag != EDIT_DELETE) {
								SYosoSagyoList &cYosoSagyoList2 = cYosoSagyogunList2.aYosoSagyoList[cYosoSagyoList.nIndex];

								// 要素作業の元データと入力データを比較
								if (cYosoSagyoList.sYosoSagyoName != cYosoSagyoList2.sYosoSagyoName ||
										cYosoSagyoList.sYosoSagyoNo !=  cYosoSagyoList2.sYosoSagyoNo ||
										cYosoSagyoList.fM != cYosoSagyoList2.fM ||
										cYosoSagyoList.fN != cYosoSagyoList2.fN ||
										cYosoSagyoList.nHassexxxndo != cYosoSagyoList2.nHassexxxndo ||
										cYosoSagyoList.nKurikaeshiKaisu != cYosoSagyoList2.nKurikaeshiKaisu ||
										cYosoSagyoList.sKoteibuHendobu != cYosoSagyoList2.sKoteibuHendobu ||
										cYosoSagyoList.nKijunKotei != cYosoSagyoList2.nKijunKotei)
									cYosoSagyoList.nEditFlag = EDIT_CHANGE;
								else
									cYosoSagyoList.nEditFlag = EDIT_NONE;
							}
						}
					}
				}
			}
		}
	}
}

// 未入力チェック
BOOL CManualEditDlg::CheckMinyuryoku()
{
	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				if (cKoteiList.nEditFlag != EDIT_NONE) {
					if (cKoteiList.sKoteiNo.IsEmpty() || cKoteiList.sKoteiName.IsEmpty() || cKoteiList.sJidoShudo.IsEmpty()) {
						cKoteiList.bErrorFlag = TRUE;
						DispList(nSagyoshaIndex);
						g_pSystem->DispMessage("E014001");
						return FALSE;
					}
				}

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (cYosoSagyogunList.nEditFlag != EDIT_NONE) {
							if (cYosoSagyogunList.sYosoSagyogunName.IsEmpty()) {
								cYosoSagyogunList.bErrorFlag = TRUE;
								DispList(nSagyoshaIndex, nKoteiIndex);
								g_pSystem->DispMessage("E014001");
								return FALSE;
							}
						}

						// 要素作業リストによるループ
						if (cKoteiList.sJidoShudo != g_pConst->m_sJido) {
							int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
							for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
								SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

								if (cYosoSagyoList.nEditFlag != EDIT_DELETE && cYosoSagyoList.nEditFlag != EDIT_NONE) {
									if (cYosoSagyoList.sYosoSagyoName.IsEmpty() || cYosoSagyoList.sYosoSagyoNo.IsEmpty() ||
											!cYosoSagyoList.bM || !cYosoSagyoList.bN || !cYosoSagyoList.bHassexxxndo || !cYosoSagyoList.bKurikaeshiKaisu ||
											cYosoSagyoList.sKoteibuHendobu.IsEmpty() || !cYosoSagyoList.bKijunKotei) {
										cYosoSagyoList.bErrorFlag = TRUE;
										DispList(nSagyoshaIndex, nKoteiIndex, nYosoSagyogunIndex);
										g_pSystem->DispMessage("E014001");
										return FALSE;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	return TRUE;
}

// 入力データ有効チェック
BOOL CManualEditDlg::CheckInputData()
{
	static CDataCheck aKoteiDataCheck[] = {
		{eDataTypeNumber, 32, 0, 0, 0, NULL},
		{eDataTypeKoteiName, 128, 0, 0, 0, NULL},
		{eDataTypeAll, 6, 0, 0, 0, NULL}
	};
	static CDataCheck aYosoSagyogunDataCheck[] = {
		{eDataTypeKoteiName, 128, 0, 0, 0, NULL}
	};
	static CDataCheck aYosoSagyoDataCheck[] = {
		{eDataTypeAll, 128, 0, 0, 0, NULL},
		{eDataTypeNumber, 8, 0, 0, 0, NULL},
		{eDataTypeNumber, 10, 4, -9999.9999, 9999.9999, NULL},
		{eDataTypeNumber, 10, 4, -9999.9999, 9999.9999, NULL},
		{eDataTypeNumber, 4, 0, 1, 9999, NULL},
		{eDataTypeNumber, 4, 0, 0, 9999, NULL},
		{eDataTypeAll, 6, 0, 0, 0, NULL},
		{eDataTypeNumber, 1, 0, 1, 2, NULL}
	};

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				if (cKoteiList.nEditFlag != EDIT_NONE) {
					// 工程のデータチェック
					if (!CheckInputDataSub(aKoteiDataCheck[0], cKoteiList.sKoteiNo) ||
							!CheckInputDataSub(aKoteiDataCheck[1], cKoteiList.sKoteiName) ||
							!CheckInputDataSub(aKoteiDataCheck[2], cKoteiList.sJidoShudo)) {
						cKoteiList.bErrorFlag = TRUE;
						DispList(nSagyoshaIndex);
						g_pSystem->DispMessage("E014002");
						return FALSE;
					}
				}

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (cYosoSagyogunList.nEditFlag != EDIT_NONE) {
							// 要素作業群のデータチェック
							if (!CheckInputDataSub(aYosoSagyogunDataCheck[0], cYosoSagyogunList.sYosoSagyogunName)) {
								cYosoSagyogunList.bErrorFlag = TRUE;
								DispList(nSagyoshaIndex, nKoteiIndex);
								g_pSystem->DispMessage("E014002");
								return FALSE;
							}
						}

						// 要素作業リストによるループ
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag != EDIT_DELETE && cYosoSagyoList.nEditFlag != EDIT_NONE) {
								// 要素作業のデータチェック
								if (!CheckInputDataSub(aYosoSagyoDataCheck[0], cYosoSagyoList.sYosoSagyoName) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[1], cYosoSagyoList.sYosoSagyoNo) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[2], cYosoSagyoList.fM) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[3], cYosoSagyoList.fN) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[4], (double)cYosoSagyoList.nHassexxxndo) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[5], (double)cYosoSagyoList.nKurikaeshiKaisu) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[6], cYosoSagyoList.sKoteibuHendobu) ||
										!CheckInputDataSub(aYosoSagyoDataCheck[7], (double)cYosoSagyoList.nKijunKotei)) {
									cYosoSagyoList.bErrorFlag = TRUE;
									DispList(nSagyoshaIndex, nKoteiIndex, nYosoSagyogunIndex);
									g_pSystem->DispMessage("E014002");
									return FALSE;
								}
							}
						}
					}
				}
			}
		}
	}

	return TRUE;
}

// 文字列データのチェック
BOOL CManualEditDlg::CheckInputDataSub(CDataCheck &cDataCheck, LPCTSTR pData)
{
	return cDataCheck.ValidCharCheck(pData) && cDataCheck.LengthCheck(pData);
}

// 数値データのチェック
BOOL CManualEditDlg::CheckInputDataSub(CDataCheck &cDataCheck, double fData)
{
	return cDataCheck.RangeCheck(fData);
}

// 要素作業群存在チェック
BOOL CManualEditDlg::CheckYosoSagyogun()
{
	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					if (cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex].nEditFlag != EDIT_DELETE)
						break;
				}
				if (nYosoSagyogunIndex == nYosoSagyogunNum) {
					// 要素作業群が無ければエラー
					cKoteiList.bErrorFlag = TRUE;
					DispList(nSagyoshaIndex);
					g_pSystem->DispMessage("E014012");
					return FALSE;			
				}
			}
		}
	}

	return TRUE;
}

// 要素作業存在チェック
BOOL CManualEditDlg::CheckYosoSagyo()
{
	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						// 要素作業リストによるループ
						if (cKoteiList.sJidoShudo != g_pConst->m_sJido) {
							int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
							for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
								if (cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex].nEditFlag != EDIT_DELETE)
									break;
							}
							if (nYosoSagyoIndex == nYosoSagyoNum) {
								// 要素作業が無ければエラー
								cYosoSagyogunList.bErrorFlag = TRUE;
								DispList(nSagyoshaIndex, nKoteiIndex);
								g_pSystem->DispMessage("E014013");
								return FALSE;			
							}
						}
					}
				}
			}
		}
	}

	return TRUE;
}

// 同一工程存在チェック
BOOL CManualEditDlg::CheckSameKotei()
{
	CString sKoteiKey;
	CStringArrayEx aKoteiKey;

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		aKoteiKey.RemoveAll();

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				sKoteiKey = cKoteiList.sKoteiNo + cKoteiList.sKoteiName;
				if (aKoteiKey.Find(sKoteiKey) != -1) {
					// 同一工程があればエラー
					cKoteiList.bErrorFlag = TRUE;
					DispList(nSagyoshaIndex);
					g_pSystem->DispMessage("E014010");
					return FALSE;			
				} else
					aKoteiKey.Add(sKoteiKey);
			}
		}
	}

	return TRUE;
}

// 同一要素作業群存在チェック
BOOL CManualEditDlg::CheckSameYosoSagyogun()
{
	CStringArrayEx aYosoSagyogunName;

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				aYosoSagyogunName.RemoveAll();

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (aYosoSagyogunName.Find(cYosoSagyogunList.sYosoSagyogunName) != -1) {
							// 同一要素作業群があればエラー
							cYosoSagyogunList.bErrorFlag = TRUE;
							DispList(nSagyoshaIndex, nKoteiIndex);
							g_pSystem->DispMessage("E014011");
							return FALSE;			
						} else
							aYosoSagyogunName.Add(cYosoSagyogunList.sYosoSagyogunName);
					}
				}
			}
		}
	}

	return TRUE;
}

// 共通工程変更チェック
BOOL CManualEditDlg::CheckKyotsuKotei()
{
	CStringArrayEx aKyotsuKoteiEdit;

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList2.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				if (cKoteiList.nEditFlag == EDIT_CHANGE && cKoteiList.bKyotsuKotei)
					aKyotsuKoteiEdit.AddUnique(cSagyoshaList.sSagyoshaName + "\t" + cKoteiList.sKoteiName);

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						if (cYosoSagyogunList.nEditFlag == EDIT_CHANGE && cYosoSagyogunList.bKyotsuKotei)
							aKyotsuKoteiEdit.AddUnique(cSagyoshaList.sSagyoshaName + "\t" + cKoteiList.sKoteiName);

						// 要素作業リストによるループ
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag != EDIT_DELETE) {
								if (cYosoSagyoList.nEditFlag == EDIT_CHANGE && cYosoSagyogunList.bKyotsuKotei)
									aKyotsuKoteiEdit.AddUnique(cSagyoshaList.sSagyoshaName + "\t" + cKoteiList.sKoteiName);
							}
						}
					}
				}
			}
		}
	}

	// 共通工程の変更確認メッセージ表示
	CStringArrayEx aSagyoshaKotei;
	int nSize = aKyotsuKoteiEdit.GetSize();
	for (int i = 0; i < nSize; i++) {
		aSagyoshaKotei.Split("\t", aKyotsuKoteiEdit[i]);
		if (g_pSystem->DispMessage("W014009", aSagyoshaKotei[0], aSagyoshaKotei[1]) == IDCANCEL)
			return FALSE;
	}

	return TRUE;
}

// データ変更処理
void CManualEditDlg::ChangeData()
{
	CString sChangeKotei;
	CString sChangeYosoSagyogun;
	CString sChangeYosoSagyo;

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSubline *pSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId);

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE && cKoteiList.nEditFlag != EDIT_INSERT) {
				sChangeKotei = (cKoteiList.nEditFlag == EDIT_CHANGE) ? "1" : "";

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE && cYosoSagyogunList.nEditFlag != EDIT_INSERT) {
						sChangeYosoSagyogun = (cYosoSagyogunList.nEditFlag == EDIT_CHANGE) ? "2" : sChangeKotei;

						SYosoSagyogun *pYosoSagyogun;
						if (!sChangeYosoSagyogun.IsEmpty()){
							SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];

							// 要素作業群を新たに追加
							pYosoSagyogun = pSubline->aYosoSagyogun.AddElement(cKoteiList.sKoteiName + cYosoSagyogunList.sYosoSagyogunName, cYosoSagyogunList.nYosoSagyogunId);
							*pYosoSagyogun = cYosoSagyogun;
							pYosoSagyogun->sKoteiKigo = cKoteiList.sKoteiKigo;
							pYosoSagyogun->sKoteiNo = cKoteiList.sKoteiNo;
							pYosoSagyogun->sKoteiName = ConvertHankaku(cKoteiList.sKoteiName);
							pYosoSagyogun->sYosoSagyogunName = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							pYosoSagyogun->sJidoYosoSagyogun = cKoteiList.sJidoShudo;
							pYosoSagyogun->sJidoKotei = cKoteiList.sJidoShudo;

							int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
							for (int i = 0; i < nYosoSagyoNum; i++) {
								// 元の要素作業群に削除フラグを設定
								cYosoSagyogun.aYosoSagyo[i].sDeleteFlag = sChangeYosoSagyogun;

								// 追加した要素作業群に追加フラグを設定
								pYosoSagyogun->aYosoSagyo[i].sInsertFlag = sChangeYosoSagyogun;
							}
						} else
							pYosoSagyogun = &pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];

						// 要素作業リストによるループ
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag == EDIT_CHANGE) {
								SYosoSagyo &cYosoSagyo = pYosoSagyogun->aYosoSagyo[cYosoSagyoList.nYosoSagyoId];
								SetYosoSagyoData(cYosoSagyo, cYosoSagyoList);
							}
						}
					}
				}
			}
		}
	}
}

// データ削除処理
void CManualEditDlg::DeleteData()
{
	CString sDeleteKoteiFlag;
	CString sDeleteYosoSagyogunFlag;

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSubline *pSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId);

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_INSERT) {
				sDeleteKoteiFlag = (cKoteiList.nEditFlag == EDIT_DELETE) ? "1" : "";

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_INSERT && cYosoSagyogunList.nYosoSagyogunId != -1) {
						SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];
						sDeleteYosoSagyogunFlag = (sDeleteKoteiFlag.IsEmpty() && cYosoSagyogunList.nEditFlag == EDIT_DELETE) ? "2" : sDeleteKoteiFlag;

						// 要素作業リストによるループ
						int nOffset = 0;
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag != EDIT_INSERT && cYosoSagyoList.nYosoSagyoId != -1) {
								// 要素作業を削除
								if (cYosoSagyoList.nEditFlag == EDIT_DELETE) {
									cYosoSagyogun.aYosoSagyo.RemoveAt(cYosoSagyoList.nYosoSagyoId + nOffset);
									nOffset--;
								}

								// 工程または要素作業群が削除されている場合は削除フラグをセット
								if (!sDeleteYosoSagyogunFlag.IsEmpty()) {
									int nYosoSagyoNum = cYosoSagyogun.aYosoSagyo.GetSize();
									for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++)
										cYosoSagyogun.aYosoSagyo[nYosoSagyoIndex].sDeleteFlag = sDeleteYosoSagyogunFlag;
								}
							}
						}
					}
				}
			}
		}
	}
}

// データ追加処理
void CManualEditDlg::InsertData()
{
	CString sInsertKoteiFlag;
	CString sInsertYosoSagyogunFlag;
	int nKishuNum = g_pDataManager->GetKishuNum();
	int nLastYosoSagyogunId = -1;

	// 作業者リストによるループ
	int nSagyoshaNum = m_aSagyoshaList.GetSize();
	for (int nSagyoshaIndex = 0; nSagyoshaIndex < nSagyoshaNum; nSagyoshaIndex++) {
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[nSagyoshaIndex];
		SSubline *pSubline = g_pDataManager->GetSublineData(cSagyoshaList.nSublineId);

		// 工程リストによるループ
		int nKoteiNum = cSagyoshaList.aKoteiList.GetSize();
		for (int nKoteiIndex = 0; nKoteiIndex < nKoteiNum; nKoteiIndex++) {
			SKoteiList &cKoteiList = cSagyoshaList.aKoteiList[nKoteiIndex];

			if (cKoteiList.nEditFlag != EDIT_DELETE) {
				sInsertKoteiFlag = (cKoteiList.nEditFlag == EDIT_INSERT) ? "1" : "";

				// 要素作業群リストによるループ
				int nYosoSagyogunNum = cKoteiList.aYosoSagyogunList.GetSize();
				for (int nYosoSagyogunIndex = 0; nYosoSagyogunIndex < nYosoSagyogunNum; nYosoSagyogunIndex++) {
					SYosoSagyogunList &cYosoSagyogunList = cKoteiList.aYosoSagyogunList[nYosoSagyogunIndex];

					if (cYosoSagyogunList.nEditFlag != EDIT_DELETE) {
						sInsertYosoSagyogunFlag = (sInsertKoteiFlag.IsEmpty() && cYosoSagyogunList.nEditFlag == EDIT_INSERT) ? "2" : sInsertKoteiFlag;

						SYosoSagyogun *pYosoSagyogun;
						if (cYosoSagyogunList.nYosoSagyogunId != -1) {
							pYosoSagyogun = &pSubline->aYosoSagyogun[cYosoSagyogunList.nYosoSagyogunId];
							nLastYosoSagyogunId = cYosoSagyogunList.nYosoSagyogunId;
						} else {
							CSagyojunArray aSagyojun;
							g_pDataManager->GetSagyojun(cSagyoshaList.nSublineId, m_nKishuId, aSagyojun);

							int nSagyojun = 0;
							if (nLastYosoSagyogunId != -1) {
								int nSize = aSagyojun.GetSize();
								for (int i = 0; i < nSize; i++) {
									if (aSagyojun[i].nYosoSagyogunId == nLastYosoSagyogunId) {
										nSagyojun = i + 1;
										break;
									}
								}
							}

							// 要素作業群を追加
							int nAddYosoSagyogunId;
							pYosoSagyogun = pSubline->aYosoSagyogun.AddElement(cKoteiList.sKoteiName + cYosoSagyogunList.sYosoSagyogunName, nAddYosoSagyogunId);
							pYosoSagyogun->sKoteiKigo = cKoteiList.sKoteiKigo;
							pYosoSagyogun->sKoteiNo = cKoteiList.sKoteiNo;
							pYosoSagyogun->sKoteiName = ConvertHankaku(cKoteiList.sKoteiName);
							pYosoSagyogun->sYosoSagyogunName = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							pYosoSagyogun->sJidoYosoSagyogun = cKoteiList.sJidoShudo;
							pYosoSagyogun->sJidoKotei = cKoteiList.sJidoShudo;
							pYosoSagyogun->aKishuSagyo.SetSize(nKishuNum);
// Modify ... ( ADD )
							// 工順規制違反ファイル出力用データ作成
							SKatashikiError cKatashikiError;
							SLineError cLineError;

							cKatashikiError.nKishuID = m_nKishuId;
							cKatashikiError.sSublineFrom = pSubline->sSublineName;
							cKatashikiError.sKoteiNoFrom = cKoteiList.sKoteiNo;
							cKatashikiError.sKoteiNameFrom = ConvertHankaku(cKoteiList.sKoteiName);
							cKatashikiError.sYosoSagyogunNameFrom = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							cKatashikiError.sRiyuNo = "E014014";

							cLineError.nKishuID = m_nKishuId;
							cLineError.sSublineFrom = pSubline->sSublineName;
							cLineError.sKoteiNoFrom = cKoteiList.sKoteiNo;
							cLineError.sKoteiNameFrom = ConvertHankaku(cKoteiList.sKoteiName);
							cLineError.sYosoSagyogunNameFrom = ConvertHankaku(cYosoSagyogunList.sYosoSagyogunName);
							cLineError.sRiyuNo = "E014014";

							g_pDataManager->AddKojunKiseiError( cKatashikiError, cLineError );
// By Y.Itabashi (xxxxx) 2007.02.19

							// 機種別データを設定
							SKishuSagyo &cKishuSagyo = pYosoSagyogun->aKishuSagyo[m_nKishuId];
							cKishuSagyo.nSagyojun = 1;
							cKishuSagyo.nSagyoshaId = cSagyoshaList.nSagyoshaId;
							cKishuSagyo.nGroupNo = g_pDataManager->GetNewGroupNo(cSagyoshaList.nSublineId);
							cKishuSagyo.bKyotsuKotei = FALSE;
							cKishuSagyo.bNewMove = FALSE;

							// 作業順を設定
							SSagyojun cSagyojun;
							cSagyojun.nYosoSagyogunId = nAddYosoSagyogunId;
							cSagyojun.nKishuId = m_nKishuId;
							aSagyojun.InsertAt(nSagyojun, cSagyojun);
							g_pDataManager->OrderBySagyojun(cSagyoshaList.nSublineId, aSagyojun);

							nLastYosoSagyogunId = nAddYosoSagyogunId;
						}

						// 要素作業リストによるループ
						int nOffset = 0;
						int nInsertYosoSagyoId = 0;
						int nYosoSagyoNum = cYosoSagyogunList.aYosoSagyoList.GetSize();
						for (int nYosoSagyoIndex = 0; nYosoSagyoIndex < nYosoSagyoNum; nYosoSagyoIndex++) {
							SYosoSagyoList &cYosoSagyoList = cYosoSagyogunList.aYosoSagyoList[nYosoSagyoIndex];

							if (cYosoSagyoList.nEditFlag == EDIT_INSERT) {
								// 要素作業を追加
								SYosoSagyo cYosoSagyo;
								cYosoSagyo.sYosoSagyoNo = cYosoSagyoList.sYosoSagyoNo;
								cYosoSagyo.nSagyoNo = 0;
								if (!sInsertYosoSagyogunFlag.IsEmpty())
									cYosoSagyo.sInsertFlag = sInsertYosoSagyogunFlag;
								cYosoSagyo.nRow = g_pDataManager->AddYosoSagyoHyo();
								SetYosoSagyoData(cYosoSagyo, cYosoSagyoList);

								pYosoSagyogun->aYosoSagyo.InsertAt(nInsertYosoSagyoId++, cYosoSagyo);

								nOffset++;
							} else {
								if (cYosoSagyoList.nEditFlag == EDIT_DELETE)
									nOffset--;

								if (cYosoSagyoList.nYosoSagyoId != -1)
									nInsertYosoSagyoId = cYosoSagyoList.nYosoSagyoId + nOffset + 1;
							}
						}

						// 自動工程で要素作業が無い場合は１件追加
						if (cKoteiList.sJidoShudo == g_pConst->m_sJido && nYosoSagyoNum == 0) {
							SYosoSagyo *pYosoSagyo = pYosoSagyogun->aYosoSagyo.AddElement();
							pYosoSagyo->nSagyoNo = 1;
							if (!sInsertYosoSagyogunFlag.IsEmpty())
								pYosoSagyo->sInsertFlag = sInsertYosoSagyogunFlag;
							pYosoSagyo->nRow = g_pDataManager->AddYosoSagyoHyo();
						}
					}
				}
			}
		}
	}

// Modify ... ( ADD )
	// 工順規制違反ファイル
	g_pDataManager->SaveKojunKiseiError();
// By Y.Itabashi (xxxxx) 2007.02.19
}

// 要素作業データ設定
void CManualEditDlg::SetYosoSagyoData(SYosoSagyo &cYosoSagyo, SYosoSagyoList &cYosoSagyoList)
{
	cYosoSagyo.sYosoSagyoName = cYosoSagyoList.sYosoSagyoName;
	cYosoSagyo.sYosoSagyoNo = cYosoSagyoList.sYosoSagyoNo;
	cYosoSagyo.sEnzanShiki = cYosoSagyoList.sEnzanShiki;
	cYosoSagyo.fS1 = cYosoSagyoList.fS1;
	cYosoSagyo.fS2 = cYosoSagyoList.fS2;
	cYosoSagyo.fM = cYosoSagyoList.fM;
	cYosoSagyo.fN = cYosoSagyoList.fN;
	cYosoSagyo.fEnzangoSagyoJikan = CalcEnzangoSagyoJikan(cYosoSagyoList);
	cYosoSagyo.nHassexxxndo = cYosoSagyoList.nHassexxxndo;
	cYosoSagyo.nKurikaeshiKaisu = cYosoSagyoList.nKurikaeshiKaisu;
	cYosoSagyo.fTime = CalcDaiatariSagyoJikan(cYosoSagyoList, cYosoSagyo.fEnzangoSagyoJikan);
	cYosoSagyo.sKoteibuHendobu = cYosoSagyoList.sKoteibuHendobu;
	cYosoSagyo.sSagyoFlag = cYosoSagyoList.sSagyoFlag;
	cYosoSagyo.nKijunKotei = cYosoSagyoList.nKijunKotei;
}

// 演算後作業時間を計算
double CManualEditDlg::CalcEnzangoSagyoJikan(SYosoSagyoList &cYosoSagyoList)
{
	CEVal cEVal;
	CString sVal;

	CString sEnzanShiki = cYosoSagyoList.sEnzanShiki;

	// 演算式にMの値を代入
	sVal.Format("%10.4f", cYosoSagyoList.fM);
	sEnzanShiki.Replace("M", sVal);

	// 演算式にNの値を代入
	sVal.Format("%10.4f", cYosoSagyoList.fN);
	sEnzanShiki.Replace("N", sVal);

	// 演算式にS1の値を代入
	sVal.Format("%10.4f", cYosoSagyoList.fS1);
	sEnzanShiki.Replace("S1", sVal);

	// 演算式にS2の値を代入
	sVal.Format("%10.4f", cYosoSagyoList.fS2);
	sEnzanShiki.Replace("S2", sVal);

	// 演算式を計算
	return cEVal.GetValue(sEnzanShiki);
}

// 台当り作業時間を計算
double CManualEditDlg::CalcDaiatariSagyoJikan(SYosoSagyoList &cYosoSagyoList, double fEnzangoSagyoJikan)
{
	// 演算後の作業時間÷発生頻度×繰り返し回数
	if (cYosoSagyoList.nHassexxxndo != 0)
		return fEnzangoSagyoJikan / cYosoSagyoList.nHassexxxndo * cYosoSagyoList.nKurikaeshiKaisu;
	else
		return 0;
}

// 全角のアンダーバー、数字を半角に変換
CString CManualEditDlg::ConvertHankaku(LPCTSTR pText)
{
	CString sText(pText);
	g_pDataManager->ConvertHankaku(sText);
	return sText;
}
