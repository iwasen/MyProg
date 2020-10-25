//*****************************************************************************************************
//  1. ファイル名
//		ManualMoveDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualMoveDlg クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "DataManager.h"

#include "MenuDispCtrl.h"

#include "ManualMoveDlg001.h"
#include "ManualMoveDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

// 表示モード
#define MODE_KOTEI			0		// 工程が表示されている
#define MODE_YOSO_SAGYOGUN	1		// 要素作業群が表示されている

// リストの列番号
#define	COL_LIST_KOTEI		0		// 工程
#define COL_LIST_TIME		1		// 作業時間
#define	COL_LIST_KISHU		2		// 機種名

// 共通工程の色設定
#define	COLOR_TEXT		RGB(0x00, 0x00, 0x00)
#define	COLOR_KYOTSU	RGB(0xff, 0xff, 0)

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::CManualMoveDlg
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
//		クラス変数を初期化する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CManualMoveDlg::CManualMoveDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CManualMoveDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CManualMoveDlg)
	m_nRadioKotei = -1;
	//}}AFX_DATA_INIT

	// クラス変数の初期化
	m_bEdit = FALSE;
// Modify ... ( ADD )
	m_nSublineIdFrom = 0;
	m_nSublineIdTo = 0;
	m_bSelectIvent = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
// Modify ... ( ADD )
	m_bMove = 0;
// By Y.Itabashi (xxxxx) 2007.02.12
}


void CManualMoveDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CManualMoveDlg)
	DDX_Control(pDX, IDC_BUTTON_PREVIEW, m_cButtonPreview);
	DDX_Control(pDX, IDC_BUTTON_SETTEI, m_cButtonSettei);
	DDX_Control(pDX, IDC_STATIC_TOTAL_TIME2, m_cStaticTotalTime2);
	DDX_Control(pDX, IDC_STATIC_TOTAL_TIME1, m_cStaticTotalTime1);
	DDX_Control(pDX, IDC_COMBO_SAGYOSHA2, m_cComboSagyosha2);
	DDX_Control(pDX, IDC_COMBO_SAGYOSHA1, m_cComboSagyosha1);
	DDX_Control(pDX, IDC_LIST2, m_cList2);
	DDX_Control(pDX, IDC_LIST1, m_cList1);
	DDX_Radio(pDX, IDC_RADIO_KOTEI, m_nRadioKotei);
	//}}AFX_DATA_MAP
}


BEGIN_MESSAGE_MAP(CManualMoveDlg, CDialogEx)
	//{{AFX_MSG_MAP(CManualMoveDlg)
	ON_CBN_SELCHANGE(IDC_COMBO_SAGYOSHA1, OnSelchangeComboSagyosha1)
	ON_CBN_SELCHANGE(IDC_COMBO_SAGYOSHA2, OnSelchangeComboSagyosha2)
	ON_BN_CLICKED(IDC_BUTTON_SETTEI, OnButtonSettei)
	ON_BN_CLICKED(IDC_BUTTON_PREVIEW, OnButtonPreview)
	ON_BN_CLICKED(IDC_RADIO_KOTEI, OnRadioKotei)
	ON_BN_CLICKED(IDC_RADIO_YOSO_SAGYOGUN, OnRadioYosoSagyogun)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST1, OnItemchangedList1)
	ON_NOTIFY(LVN_ITEMCHANGED, IDC_LIST2, OnItemchangedList2)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_DRAG_DROP, OnDragDrop)
	ON_MESSAGE(WM_BEGIN_DRAG, OnBeginDrag)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnInitDialog
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
//		各コンボボックスを初期化する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CManualMoveDlg::OnInitDialog()
{
	m_nRadioKotei = m_nMode;

	CDialogEx::OnInitDialog();

// Modify ... ( ADD )
	// メニューにメニューファイルの文字列をセット
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_ManualMoveDlg );

	SetLixxxolumn( m_cList1, Menu_ManualMoveDlg, List_ManualMoveDlgList1, eSELECTTYPE_MULTI );
	SetLixxxolumn( m_cList2, Menu_ManualMoveDlg, List_ManualMoveDlgList2, eSELECTTYPE_MULTI );
	m_cList1.SetMultilineHeader( 1 );
	m_cList2.SetMultilineHeader( 1 );
//	SetLixxxolumn(m_cList1);
//	SetLixxxolumn(m_cList2);

//	m_cList1.SetExtendedStyle(LVS_EX_FULLROWSELECT);
//	m_cList2.SetExtendedStyle(LVS_EX_FULLROWSELECT);
// By Y.Itabashi (xxxxx) 2007.02.07

	// 作業者コンボボックスを設定
	SetSagyoshaList();

/* 仕様には無いので削除
	// 作業者、工程、要素作業群が指定されていたらそれを表示
	if (!m_sSagyosha.IsEmpty()) {
		if (CGlobal::SelectComboBox(m_cComboSagyosha1, m_sSagyosha)) {
			SetListAll();
		}
	}
*/

	// 最新移動フラグクリア
	g_pDataManager->ClearNewMoveFlag();
	m_pDoc->m_bDispNewMove = FALSE;

	// ボタンを有効化
	EnableButtons();

	return TRUE;
}

void CManualMoveDlg::OnRadioKotei()
{
	m_nMode = MODE_KOTEI;

	SetListAll();
}

void CManualMoveDlg::OnRadioYosoSagyogun()
{
	m_nMode = MODE_YOSO_SAGYOGUN;

	SetListAll();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnSelchangeComboSagyosha1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左側作業者コンボボックス選択変更
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		左側工程コンボボックス、リストボックスを設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::OnSelchangeComboSagyosha1()
{
	// 工程表示モードに設定
	SetListAll();

	// ボタンを有効化
	EnableButtons();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnSelchangeComboSagyosha2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右側作業者コンボボックス選択変更
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		右側工程コンボボックス、リストボックスを設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::OnSelchangeComboSagyosha2()
{
	// 工程表示モードに設定
	SetListAll();

	// ボタンを有効化
	EnableButtons();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnDragDrop
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ドラッグ＆ドロップ実行
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		合計時間を表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
LRESULT CManualMoveDlg::OnDragDrop(WPARAM wParam, LPARAM lParam)
{
	// 移動
	MoveItem((HWND)wParam, (HWND)lParam);

	// 合計時間を表示
	DispTotalTimeAll();

	// 編集フラグセット
	m_bEdit = TRUE;

	// ボタンを有効化
// Modify ... ( CHANGE )
	EnableButtons();
//	m_cList1.SetPermissionDragDrop( FALSE );
//	m_cList2.SetPermissionDragDrop( FALSE );
//	m_cList1.EnableDrag( FALSE );
//	m_cList2.EnableDrag( FALSE );
// By Y.Itabashi (xxxxx) 2007.02.07

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnButtonSettei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		設定ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		移動処理を実行する。エラーの場合はデータを元に戻す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::OnButtonSettei()
{
	// 移動を実行
	if (MoveCheck(TRUE)) {
		// データ更新フラグ設定
		g_pDataManager->SetUpdate();

		// 最新移動フラグクリア
// Modify ... ( DELETE )
//		g_pDataManager->ClearNewMoveFlag();
// By Y.Itabashi (xxxxx) 2007.03.06
		m_pDoc->m_bDispNewMove = TRUE;

		// 正常ならダイアログを終了
		EndDialog(IDOK);
	}

// Modify ... ( ADD )
	g_pDataManager->InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.07
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnButtonPreview
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		プレビューボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		現在のデータをバックアップした後、移動を実行してグラフ表示を更新する。その後バックアップ
//		したデータを戻す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::OnButtonPreview()
{
	// 移動を実行
	if (MoveCheck(FALSE)) {
		// グラフ表示を更新
		SSagyoshaList &cSagyoshaList = m_aSagyoshaList[m_cComboSagyosha1.GetItemData(m_cComboSagyosha1.GetCurSel())];
		m_pDoc->DispCenterSagyosha(cSagyoshaList.nSublineId, cSagyoshaList.nSagyoshaId);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キャンセルボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ダイアログを閉じる。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::OnCancel()
{
	if (m_bEdit) {
		// データを元に戻す
		g_pDataManager->RestoreUndoData();

// Modify ... ( ADD )
		g_pDataManager->InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.07
	}

	// プレビューしていたら戻ったときに画面を更新する
	EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::SetSagyoshaList
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
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::SetSagyoshaList()
{
	int nIndex;

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag) {
			// コンボボックスに作業者を設定
			int nSagyoshNum = g_pDataManager->GetSagyoshaNum(nSublineId);
			for (int nSagyoshId = 0; nSagyoshId < nSagyoshNum; nSagyoshId++) {
				SSagyoshaList *pSagyoshaList = m_aSagyoshaList.AddElement(nIndex);
				pSagyoshaList->nSublineId = nSublineId;
				pSagyoshaList->nSagyoshaId = nSagyoshId;

				CString &sSagyoshaName = g_pDataManager->GetSagyoshaName(nSublineId, nSagyoshId);
				m_cComboSagyosha1.SetItemData(m_cComboSagyosha1.AddString(sSagyoshaName), nIndex);
				m_cComboSagyosha2.SetItemData(m_cComboSagyosha2.AddString(sSagyoshaName), nIndex);
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::SetListAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全リストボックス設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		全てのリストボックスを設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::SetListAll()
{
	// 左側のコンボボックス、リストボックスを設定
	SetList(m_aListData1, m_cComboSagyosha1, m_cList1, 1);

	// 右側のコンボボックス、リストボックスを設定
	SetList(m_aListData2, m_cComboSagyosha2, m_cList2, 2);

	// 合計時間を表示
	DispTotalTimeAll();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::SetList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンボボックス、リストボックス設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nMode					[I] 表示モード
//		CListDataArray	&aListData				[I] リストデータ
//		CComboBox		&cComboSagyosha			[O] 作業者コンボボックス
//		CComboBox		&cComboKotei			[O] 工程コンボボックス
//		CComboBox		&cComboYosoSagyogun		[O] 要素作業群コンボボックス
//		CLixxxtrlEx1	&cList					[O] リストボックス
//		int				nOrg					[I] 1:移動元、2:移動先
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたコンボボックス、リストボックスにデータをセットする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg)
//void CManualMoveDlg::SetList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	// 表示モードによりンボボックス、リストボックスを設定
	switch (m_nMode) {
	case MODE_KOTEI:
		// 工程リスト設定
		SetKoteiList(aListData, cComboSagyosha, cList, nOrg);
		break;
	case MODE_YOSO_SAGYOGUN:
		// 要素作業群リスト設定
		SetYosoSagyogunList(aListData, cComboSagyosha, cList, nOrg);
		break;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::SetKoteiList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程リスト設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CListDataArray	&aListData				[I] リストデータ
//		CComboBox		&cComboSagyosha			[I] 作業者コンボボックス
//		CLixxxtrlEx1	&cList					[O] リストボックス
//		int				nOrg					[I] 1:移動元、2:移動先
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		リストボックスに工程を表示し、工程コンボボックスを設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg)
//void CManualMoveDlg::SetKoteiList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	int i;

// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[16].MenuNo, Menu_ManualMoveDlg[16].ItemNo );
	cList.SetColumnText(0, str);
//	cList.SetColumnText(0, "工程");
// By Y.Itabashi (xxxxx) 2007.01.26

	// コンボボックスから作業者を取得
	int nIndex = cComboSagyosha.GetCurSel();
	if (nIndex == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[cComboSagyosha.GetItemData(nIndex)];

	// リストボックスをクリア
	aListData.RemoveAll();
	cList.DeleteAllItems();

	SGraphSubline *pGraphSubline = m_pDoc->GetGraphSublineData(cSagyoshaList.nSublineId);
	CGraphBlockArray &aGraphBlock = pGraphSubline->aGraphSagyosha[cSagyoshaList.nSagyoshaId].aKotei;
	int nSize = aGraphBlock.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

// 加重平均時も表示
// Modify ... ( CHANGE )
		if ( (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) || m_nGraphKishu == -1 ) {
//		if (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) {
// By Y.Itabashi (xxxxx) 2007.02.26
			CListData *pListData = aListData.AddElement();
			pListData->sName = cGraphBlock.sKoteiName;
			pListData->fTime = cGraphBlock.fTime;
// Modify ... ( ADD )
			SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
			pListData->sKishuName = pKishu->sKishuName;
			if( cGraphBlock.bKyotsuKotei )
				pListData->nKyotsuKoteiNo = cGraphBlock.nGroupNo;
			else
				pListData->nKyotsuKoteiNo = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
			pListData->nOrg = nOrg;
			pListData->pGraphBlock = &cGraphBlock;
		}
	}

	// リストボックスにセット
	SetListBox(aListData, cList);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::SetYosoSagyogunList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		要素作業群リスト設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CListDataArray	&aListData				[I] リストデータ
//		CComboBox		&cComboSagyosha			[I] 作業者コンボボックス
//		CLixxxtrlEx1	&cList					[O] リストボックス
//		int				nOrg					[I] 1:移動元、2:移動先
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		リストボックスに要素作業群を表示し、要素作業群コンボボックスを設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx &cList, int nOrg)
//void CManualMoveDlg::SetYosoSagyogunList(CListDataArray &aListData, CComboBox &cComboSagyosha, CLixxxtrlEx1 &cList, int nOrg)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	int i;

// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[17].MenuNo, Menu_ManualMoveDlg[17].ItemNo );
	cList.SetColumnText(0, str);
//	cList.SetColumnText(0, "要素作業群");
// By Y.Itabashi (xxxxx) 2007.01.26

	// コンボボックスから作業者を取得
	int nIndex = cComboSagyosha.GetCurSel();
	if (nIndex == -1)
		return;

	SSagyoshaList &cSagyoshaList = m_aSagyoshaList[cComboSagyosha.GetItemData(nIndex)];

	// リストボックスをクリア
	aListData.RemoveAll();
	cList.DeleteAllItems();

	SGraphSubline *pGraphSubline = m_pDoc->GetGraphSublineData(cSagyoshaList.nSublineId);
	CGraphBlockArray &aGraphBlock = pGraphSubline->aGraphSagyosha[cSagyoshaList.nSagyoshaId].aYosoSagyogun;
	int nSize = aGraphBlock.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

// Modify ... ( CHANGE )
		if ( (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) || m_pDoc->m_nGraphKind == -1 ){
//		if (m_nKishuId == -1 || m_nKishuId == cGraphBlock.nKishuId) {
// By Y.Itabashi (xxxxx) 2007.02.07
			CListData *pListData = aListData.AddElement();
			pListData->sName = cGraphBlock.sYosoSagyogunName;
			pListData->fTime = cGraphBlock.fTime;
// Modify ... ( ADD )
			SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
			pListData->sKishuName = pKishu->sKishuName;
			if( cGraphBlock.bKyotsuKotei )
				pListData->nKyotsuKoteiNo = cGraphBlock.nGroupNo;
			else
				pListData->nKyotsuKoteiNo = 0;
// By Y.Itabashi (xxxxx) 2007.02.07
			pListData->nOrg = nOrg;
			pListData->pGraphBlock = &cGraphBlock;
		}
	}

	// リストボックスにセット
	SetListBox(aListData, cList);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::SetListBox
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リストボックス設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CListDataArray	&aListData			[I] リストデータ
//		CLixxxtrlEx1	&cList				[O] リストボックス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたコンボボックスにリストデータを設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::SetListBox(CListDataArray &aListData, CLixxxtrlEx &cList)
//void CManualMoveDlg::SetListBox(CListDataArray &aListData, CLixxxtrlEx1 &cList)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	CString sText;
	int nSize = aListData.GetSize();
	for (int i = 0; i < nSize; i++) {
		CListData &cListData = aListData[i];

// Modify ... ( CHANGE )
		cList.SetValue( i, COL_LIST_KOTEI, cListData.sName);
		cList.SetValue(i, COL_LIST_TIME, cListData.fTime);
		cList.SetValue( i, COL_LIST_KISHU, cListData.sKishuName );

		if( cListData.nKyotsuKoteiNo )
			cList.SetItemRowColor( i, COLOR_TEXT, COLOR_KYOTSU );
		cList.SetItemRowData(i, (DWORD)&cListData);
/*		sText.Format("%.6f", cListData.fTime);
		cList.InsertItem(i, cListData.sName);
		cList.SetItemText(i, 1, sText);
		cList.SetItemData(i, (DWORD)&cListData);*/
// By Y.Itabashi (xxxxx) 2007.02.07
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::DispTotalTimeAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		合計時間表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		左右のリストボックスの合計時間を表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::DispTotalTimeAll()
{
	// 左側のリストボックスの合計時間を表示
	DispTotalTime(CalcTotalTime(m_cList1), m_cStaticTotalTime1);

	// 右側のリストボックスの合計時間を表示
	DispTotalTime(CalcTotalTime(m_cList2), m_cStaticTotalTime2);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::DispTotalTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		合計時間表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTime			[I] 合計時間
//		CStatic		&cStatic		[O] スタティックコントロール
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたスタティックコントロールに指定された合計時間を表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::DispTotalTime(double fTime, CStatic &cStatic)
{
	CString sText;

// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[7].MenuNo, Menu_ManualMoveDlg[7].ItemNo );
	sText.Format("%s%.3f", str, fTime);
//	sText.Format("合計時間：%.3f", fTime);
// By Y.Itabashi (xxxxx) 2007.01.26
	cStatic.SetWindowText(sText);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::CalcTotalTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		合計時間計算
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CLixxxtrlEx1	&cList			[I] リストボックス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		double		合計時間
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたリストボックス内の合計時間を計算する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
double CManualMoveDlg::CalcTotalTime(CLixxxtrlEx &cList)
//double CManualMoveDlg::CalcTotalTime(CLixxxtrlEx1 &cList)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	double fTime = 0;

	// リストボックス内の作業時間を合計
	int nSize = cList.GetItemCount();
	for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
		CListData *pListData = (CListData *)cList.GetItemRowData(i);
//		CListData *pListData = (CListData *)cList.GetItemData(i);
// By Y.Itabashi (xxxxx) 2007.02.07
		fTime += pListData->fTime;
	}

	return fTime;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::EnableButtons
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		OK,プレビューボタンの有効／無効を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualMoveDlg::EnableButtons()
{
	int nIndex1 = m_cComboSagyosha1.GetCurSel();
	int nIndex2 = m_cComboSagyosha2.GetCurSel();
// Modify ... ( ADD )
	if( m_bMove == 1 ){
		m_cList1.SetPermissionDragDrop(FALSE);
		m_cList2.SetPermissionDragDrop(FALSE);
	}else
// By Y.Itabashi (xxxxx) 2007.02.12
	if (nIndex1 != nIndex2) {
// Modify ... ( CHANGE )
		m_cList1.SetPermissionDragDrop(TRUE);
		m_cList2.SetPermissionDragDrop(TRUE);
//		m_cList1.EnableDrag(TRUE);
//		m_cList2.EnableDrag(TRUE);
// By Y.Itabashi (xxxxx) 2007.02.07
	} else {
// Modify ... ( CHANGE )
		m_cList1.SetPermissionDragDrop(FALSE);
		m_cList2.SetPermissionDragDrop(FALSE);
//		m_cList1.EnableDrag(FALSE);
//		m_cList2.EnableDrag(FALSE);
// By Y.Itabashi (xxxxx) 2007.02.07
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::MoveCheck
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		移動実行処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL		bLimitCheck			[I] 上限チェックの有無
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：正常，FALSE：エラー有り
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		移動処理を行い、工順規制チェック、目標タクト上限チェックを行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CManualMoveDlg::MoveCheck(BOOL bLimitCheck)
{
	CIntArrayEx aSagyoshaId;

	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);

		if (pSubline->bManualEditFlag) {
			// 工順規制チェック
// Modify ... ( CAHNGE )			移動元サブラインのIDが正しいかチェックが必要
			if (!g_pDataManager->CheckKojunKisei(m_nSublineIdFrom, nSublineId, m_nMode == MODE_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun))
//			if (!g_pDataManager->CheckKojunKisei(nSublineId, m_nMode == MODE_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun))
// By Y.Itabashi (xxxxx) 2007.02.07
				return FALSE;

			// 目標タクト上限チェック
			if (bLimitCheck) {
				// 目標タクト上限チェック対象の作業者を取得
				aSagyoshaId.RemoveAll();
				int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
				for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
					SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
					int nKishuNum = cYosoSagyogun.aKishuSagyo.GetSize();
					for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
						if (cKishuSagyo.bNewMove)
							aSagyoshaId.AddUnique(cKishuSagyo.nSagyoshaId);
					}
				}

				int nSize = aSagyoshaId.GetSize();
				for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
					if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, aSagyoshaId[i], m_nKishuId, m_nGraphKishu)) {
//					if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, aSagyoshaId[i], m_nKishuId)) {
// By Y.Itabashi (xxxxx) 2007.02.26
						if(g_pSystem->DispMessage("W013002") == IDCANCEL)
							return FALSE;
					}
				}
			}
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualMoveDlg::GetMoveData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		移動データ取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CLixxxtrlEx1		&cList				[I] 移動元作業者ID
//		CMoveDataArray	&aMoveData			[O] 移動先作業者ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定されたリストボックスから、移動する項目を取得する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// Modify ... ( CHANGE )
void CManualMoveDlg::GetMoveData(CLixxxtrlEx &cList, CMoveDataArray &aMoveData)
//void CManualMoveDlg::GetMoveData(CLixxxtrlEx1 &cList, CMoveDataArray &aMoveData)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	// リストボックス内の項目を取得
	int nCount = cList.GetItemCount();
	for (int i = 0; i < nCount; i++) {
// Modify ... ( CHANGE )
		CListData *pListData = (CListData *)cList.GetItemRowData(i);
//		CListData *pListData = (CListData *)cList.GetItemData(i);
// By Y.Itabashi (xxxxx) 2007.02.07
		SMoveData *pMoveData = aMoveData.AddElement();
		pMoveData->sName = pListData->sName;
		pMoveData->nOrg = pListData->nOrg;
	}
}

// Modify ... ( CHANGE )
/*void CManualMoveDlg::SetLixxxolumn(CLixxxtrlEx &cList)
//void CManualMoveDlg::SetLixxxolumn(CLixxxtrlEx1 &cList)
// By Y.Itabashi (xxxxx) 2007.02.07
{
	cList.EnableScrollBarCtrl(SB_VERT, TRUE);

	CRect rect;
	cList.GetClientRect(rect);
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[4].MenuNo, Menu_ManualMoveDlg[4].ItemNo );
	cList.InsertColumn(0, str, LVCFMT_LEFT, rect.Width() * 4 / 5);
//	cList.InsertColumn(0, "工程", LVCFMT_LEFT, rect.Width() * 4 / 5);

	str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualMoveDlg[5].MenuNo, Menu_ManualMoveDlg[5].ItemNo );
	cList.InsertColumn(1, str, LVCFMT_RIGHT, rect.Width() / 5);
//	cList.InsertColumn(1, "作業時間", LVCFMT_RIGHT, rect.Width() / 5);
// By Y.Itabashi (xxxxx) 2007.01.26
/*
	HD_ITEM hdi;
	memset(&hdi, 0, sizeof(hdi));
	hdi.mask = HDI_FORMAT;
	hdi.fmt = HDF_CENTER;
	cList.GetHeaderCtrl()->SetItem(0, &hdi);
*/
//}

void CManualMoveDlg::MoveItem(HWND hWndDrag, HWND hWndDrop)
{
	int nIndex1 = m_cComboSagyosha1.GetCurSel();
	int nIndex2 = m_cComboSagyosha2.GetCurSel();

// Modify ... ( CHANGE )
	CLixxxtrlEx &cListDrag = (m_cList1.m_hWnd == hWndDrag) ? m_cList1 : m_cList2;
	CLixxxtrlEx &cListDrop = (m_cList1.m_hWnd == hWndDrop) ? m_cList1 : m_cList2;

	if( cListDrag != cListDrop && ( nIndex1 == -1 || nIndex2 == -1 ) ){
		if( nIndex1 == -1 ){
			m_cList1.DeleteAllItems();
			m_aListData1.RemoveAll();
		}
		if( nIndex2 == -1 ){
			m_cList2.DeleteAllItems();
			m_aListData2.RemoveAll();
		}
		SetListAll();
		return;
	}

	if( !CheckMoveIndex( cListDrop ) ){
		SetListAll();
		return;
	}
// By Y.Itabashi (xxxxx) 2007.02.07

	SSagyoshaList &cSagyoshaListDrag = (m_cList1.m_hWnd == hWndDrag) ? m_aSagyoshaList[m_cComboSagyosha1.GetItemData(nIndex1)] : m_aSagyoshaList[m_cComboSagyosha2.GetItemData(nIndex2)];
	SSagyoshaList &cSagyoshaListDrop = (m_cList1.m_hWnd == hWndDrop) ? m_aSagyoshaList[m_cComboSagyosha1.GetItemData(nIndex1)] : m_aSagyoshaList[m_cComboSagyosha2.GetItemData(nIndex2)];

// Modify ... ( CHANGE )
	m_nSublineIdFrom = cSagyoshaListDrag.nSublineId;
	m_nSublineIdTo = cSagyoshaListDrop.nSublineId;
// By Y.Itabashi (xxxxx) 2007.02.07

	BOOL bLastBlock = FALSE;
	BOOL bSelect = FALSE;
	SGraphBlock *pGraphBlockTo = NULL;
	SGraphBlock *pGraphBlockLast = NULL;
	CGraphBlockPtrArray aGraphBlockPtr;
	int nSize = cListDrop.GetItemCount();
	for (int i = 0; i < nSize; i++) {
// Modify ... ( CHANGE )
		CListData *pListData = (CListData *)cListDrop.GetItemRowData(i);
//		CListData *pListData = (CListData *)cListDrop.GetItemData(i);
// By Y.Itabashi (xxxxx) 2007.02.07
		if (cListDrop.GetItemState(i, LVIS_SELECTED)) {
			aGraphBlockPtr.Add(pListData->pGraphBlock);
			bSelect = TRUE;
		} else {
			if (bSelect && pGraphBlockTo == NULL)
				pGraphBlockTo = pListData->pGraphBlock;
			pGraphBlockLast = pListData->pGraphBlock;
		}
	}

	if (pGraphBlockTo == NULL) {
		if (pGraphBlockLast != NULL) {
			pGraphBlockTo = pGraphBlockLast;
			bLastBlock = TRUE;
		} else {
			pGraphBlockTo = m_pDoc->GetNextGraphBlock(cSagyoshaListDrop.nSublineId, cSagyoshaListDrop.nSagyoshaId);
			bLastBlock = FALSE;
		}
			
	}

// Modify ... ( ADD )
	// 機種別表示で共通工程が含まれていたらエラー
	int nSelectSize = aGraphBlockPtr.GetSize();
// Modify ... ( ADD )
	if (m_nGraphKishu != -1) {
//	if (m_pDoc->m_nGraphKishu != -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
		for (int i = 0; i < nSelectSize; i++) {
			SGraphBlock *pGraphBlockFrom = aGraphBlockPtr[i];
			if (pGraphBlockFrom->bKyotsuKotei) {
				g_pSystem->DispMessage("E013001");
				aGraphBlockPtr.RemoveAll();
				pGraphBlockTo = NULL;
				SetListAll();
				return;
			}
		}
	}

	// 同一工程チェック
// Modify ... ( CHANGE )		同一工程チェックを関数化
	for (i = 0; i < nSelectSize; i++) {
		if( !m_pDoc->CheckSameKoteiMove( aGraphBlockPtr[i], pGraphBlockTo, bLastBlock ) ){
			aGraphBlockPtr.RemoveAll();
			pGraphBlockTo = NULL;
			SetListAll();
			return;
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.10

	// 移動前の位置を設定
	if (pGraphBlockTo != NULL){

// Modify ... ( CHANGE )
		for( int nKishu = 0; nKishu < g_pDataManager->GetKishuNum(); nKishu++ ){

			CString sCheckKoteiName;
			CString sCheckYosoSagyogunName;
			int nCheckYosoSagyogunId = -1;

			for( i = 0; i < aGraphBlockPtr.GetSize(); i++ ){
				SGraphBlock *pGraphBlockFrom = aGraphBlockPtr[i];

				if( pGraphBlockFrom->nKishuId != nKishu )
					continue;

				if( sCheckKoteiName.IsEmpty() ){
					sCheckKoteiName = pGraphBlockFrom->sKoteiName;
					sCheckYosoSagyogunName = pGraphBlockFrom->sYosoSagyogunName;
					nCheckYosoSagyogunId = pGraphBlockFrom->aYosoSagyogunId[0];
				}

				g_pDataManager->SetMoveBeforeSagyojun(m_nSublineIdFrom, m_nSublineIdTo,
					pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
					sCheckKoteiName, sCheckYosoSagyogunName, nCheckYosoSagyogunId,
					pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
					m_pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
			}
		}
/*		for( i = 0; i < aGraphBlockPtr.GetSize(); i++ ){
			SGraphBlock *pGraphBlockFrom = aGraphBlockPtr[i];
			g_pDataManager->SetMoveBeforeSagyojun(m_nSublineIdFrom,
				pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
				pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
				m_pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
		}*/
// By Y.Itabashi (xxxxx) 2007.02.28

		m_pDoc->MoveItem(m_nSublineIdFrom, aGraphBlockPtr, m_nSublineIdTo, pGraphBlockTo, cSagyoshaListDrop.nSagyoshaId, bLastBlock);
	}
// By Y.Itabashi (xxxxx) 2007.02.06

	m_pDoc->MakeGraphData();
	SetListAll();
// Modify ... ( ADD )
	m_bMove = 1;
// By Y.Itabashi (xxxxx) 2007.02.12
}

void CManualMoveDlg::OnItemchangedList1(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;
	
	if( m_cList1.GetDraggingMode() || m_cList2.GetDraggingMode() )
		return;

	if( m_bSelectIvent )
		return;
	
	int Row = pNMListView->iItem;

	AutoKyotsuKoteiSelect( m_cList1, Row );

	*pResult = 0;
}

void CManualMoveDlg::OnItemchangedList2(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;
	
	if( m_cList1.GetDraggingMode() || m_cList2.GetDraggingMode() )
		return;
	
	if( m_bSelectIvent )
		return;
	
	int Row = pNMListView->iItem;

	AutoKyotsuKoteiSelect( m_cList1, Row );

	*pResult = 0;
}

void CManualMoveDlg::AutoKyotsuKoteiSelect( CLixxxtrlEx &cList, int Row )
{
	// 選択操作をロック
	m_bSelectIvent = 1;

	SHORT sKeyCheck1, sKeyCheck2;
	sKeyCheck1 = GetKeyState(VK_SHIFT);
	sKeyCheck2 = GetKeyState(VK_CONTROL);

	if( Row < 0 )
		return;

	UINT nState = cList.GetItemState( Row, LVIS_SELECTED );
	CListData *pListData = (CListData *)cList.GetItemRowData( Row );
	if( pListData == NULL )
		return;

	int nSelectGroupNo = pListData->nKyotsuKoteiNo;

	for( int i = 0; i < cList.GetItemCount(); i++ ){

		pListData = (CListData *)cList.GetItemRowData( i );
		if( !pListData )	continue;
		int nGroupNo = pListData->nKyotsuKoteiNo;
		if( !nGroupNo )		continue;

		if( nGroupNo && pListData->nKyotsuKoteiNo == nSelectGroupNo )
			cList.SelectChange( i );
		else if( !(sKeyCheck1 & 0x8000) && !(sKeyCheck2 & 0x8000) )
			cList.SetItemState( i, 0, LVIS_SELECTED );
	}
	
	// 選択操作ロック解除
	m_bSelectIvent = 0;
}

void CManualMoveDlg::AutoAutoKoteiSelect( CLixxxtrlEx &cList )
{
	// 選択操作をロック
	m_bSelectIvent = 1;

	CListData *pListData = NULL;
	for( int i = 0; i < cList.GetItemCount()-1; i++ ){
		if( !cList.GetItemState( i, LVIS_SELECTED ) )
			continue;

		pListData = (CListData *)cList.GetItemRowData( i+1 );
		if( !pListData )	continue;

		if( pListData->pGraphBlock->sJido == g_pConst->m_sJido )
			cList.SelectChange( i+1 );
	}
	
	// 選択操作ロック解除
	m_bSelectIvent = 0;

	cList.UpdateWindow();
}

BOOL CManualMoveDlg::CheckMoveIndex( CLixxxtrlEx &cList )
{
	int nCount = cList.GetItemCount();
	int nBeforeItem = -1;
	int nAfterItem = -1;

	int bBeforeSelect = 0;
	for( int i = 0; i < nCount; i++ ){
		int bSelect = cList.GetItemState( i, LVIS_SELECTED );
		if( bSelect && !bBeforeSelect )
			nBeforeItem = i - 1;
		else if( !bSelect && bBeforeSelect )
			nAfterItem = i;

		if( nBeforeItem != -1 && nAfterItem != -1 )
			break;

		bBeforeSelect = bSelect;
	}

	if( nBeforeItem == -1 || nAfterItem == -1 )
		return TRUE;

	CListData *pListData1 = (CListData *)cList.GetItemRowData( nBeforeItem );
	CListData *pListData2 = (CListData *)cList.GetItemRowData( nAfterItem );
	if( pListData1 == NULL || pListData2 == NULL )
		return TRUE;

	if( pListData1->nKyotsuKoteiNo == 0 || pListData2->nKyotsuKoteiNo == 0 )
		return TRUE;
	else if( pListData1->nKyotsuKoteiNo == pListData2->nKyotsuKoteiNo )
		return FALSE;

	return TRUE;
}

void CManualMoveDlg::OnBeginDrag(WPARAM wParam, LPARAM lParam) 
{
	CLixxxtrlEx &cListDrag = (m_cList1.m_hWnd == (HWND)wParam) ? m_cList1 : m_cList2;

	AutoAutoKoteiSelect( cListDrag );
}
