//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView1.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView1 クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "MenuDispCtrl.h"

#include "ManualEditWnd002.h"
#include "ManualEditView1.h"
#include "DataManager.h"
#include "ManualMoveDlg.h"
#include "ManualBunkatsuDlg.h"
#include "ManualEditDlg.h"
#include "ManualDstrDlg.h"
#include "SplitterEx.h"
#include "ManualEditChildWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define JIDO_SIZE	6		// 自動工程の三角表示のサイズ

// マウス移動モード
#define MOUSE_NONE		0
#define MOUSE_SELECT	1
#define MOUSE_DRAG		2

// Modify ... ( ADD )
#define MOVE_IVENT_NONE			0
#define MOVE_IVENT_MOVE			1
#define MOVE_IVENT_KITCANCEL	2
// By Y.Itabashi (xxxxx) 2007.03.05

IMPLEMENT_DYNCREATE(CManualEditView1, CManualEditView)

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::CManualEditView1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
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
CManualEditView1::CManualEditView1()
{
	// クラス変数の初期化
	m_pRightClickZahyo = NULL;
	m_nMouseMoveMode = MOUSE_NONE;
	m_nSelectRemoveIndex = -1;
// Modify ... ( CHANGE )
	m_rectNewMove.SetRectEmpty();
//	m_rectNewMove = 0;
// By Y.Itabashi (xxxxx) 2007.02.11

	// スケールパラメータ設定
	SetScaleParamaeter();

	// ドラッグ先の三角を描画するブラシを作成
	m_brushMoveDrag.CreateSolidBrush(RGB(0, 0xff, 0xff));

	m_penSelected.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbSelectProcessColor);
	m_penNewMove.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbNewMoveProcessColor);
	m_penKittingCancel.CreatePen(PS_DOT, 1, g_pSystem->m_cIniUser.m_rgbKittingCancelProcessColor);
// Modify ... ( ADD )
	m_penReference.CreatePen(PS_DOT, 2, g_pSystem->m_cIniUser.m_rgbReferenceProcessColor);
// By Y.Itabashi (xxxxx) 2007.02.27

	// 共通工程のビットマップ
	BITMAP bmp;
	m_bmpKyotsuKotei.LoadBitmap(IDB_BITMAP_KYOTSU_KOTEI);
	m_bmpKyotsuKotei.GetBitmap(&bmp);
	m_sizeKyotsuKotei.cx = bmp.bmWidth;
	m_sizeKyotsuKotei.cy = bmp.bmHeight;
}


BEGIN_MESSAGE_MAP(CManualEditView1, CManualEditView)
	//{{AFX_MSG_MAP(CManualEditView1)
	ON_WM_RBUTTONDOWN()
	ON_COMMAND(IDM_MANUAL_MOVE, OnManualMove)
	ON_COMMAND(IDM_MANUAL_BUNKATSU, OnManualBunkatsu)
	ON_COMMAND(IDM_MANUAL_EDIT, OnManualEdit)
	ON_WM_LBUTTONDOWN()
	ON_WM_MOUSEMOVE()
	ON_WM_LBUTTONUP()
	ON_WM_CHAR()
	ON_WM_LBUTTONDBLCLK()
	ON_WM_SIZE()
	ON_COMMAND(IDM_MANUAL_SAGYOSHA_MAE, OnManualSagyoshaMae)
	ON_COMMAND(IDM_MANUAL_SAGYOSHA_USHIRO, OnManualSagyoshaUshiro)
	ON_COMMAND(IDM_MANUAL_SAGYOSHA_SAKUJO, OnManualSagyoshaSakujo)
	ON_COMMAND(IDM_MANUAL_COPY, OnManualCopy)
	ON_COMMAND(IDM_MANUAL_PASTE, OnManualPaste)
	ON_COMMAND(IDM_MANUAL_DSTR, OnManualDstr)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_USER_DRAG, OnUserDrag)
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnInitialUpdate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ビュー初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		スクロールバーを表示させる。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnInitialUpdate()
{
	CManualEditView::OnInitialUpdate();

	SetWindowPos(&wndBottom, 0, 0, 0, 0, SWP_NOMOVE | SWP_NOSIZE);

	// タイトル設定
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[1].MenuNo, Menu_ManualWindowTitle[1].ItemNo );
	GetParent()->GetParent()->SetWindowText( str );
//	GetParent()->SetWindowText("編集画面");
// By Y.Itabashi (xxxxx) 2007.01.26

	// 初期状態で一番下にスクロールさせる
	CRect rect;
	GetClientRect(rect);
	ScrollToPosition(CPoint(0, m_rectView.Height() - rect.Height()));

	DispSublineInfo();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnDraw
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ビュー初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC			[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフの各パーツを描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnDraw(CDC* pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	CFont *pFontOrg = pDC->SelectObject(g_pSystem->m_pMainDlg->GetFont());

	// 描画モードをTRANSPARENTに設定
	int nBkMode = pDC->SetBkMode(TRANSPARENT);

	// 作業グラフ描画
	DrawGraph(pDC, TRUE);

	// 共通工程枠描画
	DrawKyotsuKoteiFrame(pDC);

	// 最新移動枠描画
	if (pDoc->m_bDispNewMove)
		DrawNewMoveFrame(pDC);

	// キット化解除復活枠描画
	DrawKittingCancelFrame(pDC);

// Modify ... ( ADD )
	// 参照工程描画
	DrawReferenceFrame(pDC);
// By Y.Itabashi (xxxxx) 2007.02.27

	// 縦軸描画
	DrawVerticalAxisLine(pDC);

	// 横軸描画
	DrawHorizontalAxisLine(pDC);

	// 縦軸テキスト描画
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[0].MenuNo, Menu_ManualWindowText[0].ItemNo );
	DrawVerticalAxisText(pDC, str);
//	DrawVerticalAxisText(pDC, "【作業時間】");
// By Y.Itabashi (xxxxx) 2007.01.26

	// 横軸テキスト描画
	DrawHorizontalAxisText(pDC);

	// 合計時間描画
	DrawTotalTime(pDC);

	// 目標タクト描画
	DrawTactLine(pDC);

	// サブライン間区切り線描画
	DrawSublineBoundLine(pDC);

	// キット化サブライン間区切り線描画
	DrawKittingSubline(pDC);

	// 描画モードを戻す
	pDC->SetBkMode(nBkMode);

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnUpdate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ビュー更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CView		*pSender		[I] ドキュメントを修正されたビューへのポインタ
//		LPARAM		lHint			[I] 変更についての情報
//		CObject		*pHint			[I] 変更についての情報が格納されたオブジェクト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ビューを再描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	CManualEditDocument *pDoc = GetDocument();
	CSplitterEx *pWnd = (CSplitterEx *)GetParent();
	CFrameWnd *pActiveFrame;

// Modify ... ( CHANGE )
	// 機種変更で対象画面以外はアップデートしない
	if( lHint == UPDATE_GRAPH_KISHU && pHint != GetParentFrame() ){
		Invalidate();
		return;
	}
// By Y.Itabashi (xxxxx) 2007.02.27

	switch (lHint) {
	case UPDATE_SCROLL:
		if (pWnd->IsActivePane(this)) {
			SZahyoSagyosha *pZahyoSagyosha = (SZahyoSagyosha *)pHint;
			DispCenterSagyosha(pZahyoSagyosha->nSublineId, pZahyoSagyosha->nSagyoshaId);
		}
		return;
// Modify ... ( ADD )
	case UPDATE_SCROLL_ACTIVE:
		pActiveFrame = ((CFrameWnd *)(AfxGetMainWnd()->GetActiveWindow()))->GetActiveFrame();
		if( pActiveFrame == GetParentFrame() ){
			if (pWnd->IsActivePane(this)) {
				SZahyoSagyosha *pZahyoSagyosha = (SZahyoSagyosha *)pHint;
				DispCenterSagyosha(pZahyoSagyosha->nSublineId, pZahyoSagyosha->nSagyoshaId);
			}
		}
		return;
// By Y.Itabashi (xxxxx) 2007.03.04
	case UPDATE_CHANGE_SLIM:
		break;
	case UPDATE_CHANGE_SUBLINE:
		InitializeZahyoData();
		break;
	case UPDATE_EDIT_VIEW:
		Invalidate(FALSE);
		return;
	case UPDATE_SELECT_BLINK:
		BlinkSelectedBox((BOOL)pHint);
		return;
	case UPDATE_ERASE_MOVE_TRIANGLE:
		DrawMoveTriangle(0);
		return;
	}

	// スケールパラメータ設定
	SetScaleParamaeter();
	SetSlimMode();

	// 座標を計算
	MakeGraph();

	// ビュー領域を無効化
	Invalidate();

	// サブライン情報表示
	DispSublineInfo();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウサイズ変更
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nType			[I] サイズ変更のタイプ
//		int		cx				[I] ウィンドウの幅
//		int		cy				[I] ウィンドウの高さ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ウィンドウサイズに合わせてグラフの座標を再計算して再描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnSize(UINT nType, int cx, int cy)
{
	CManualEditView::OnSize(nType, cx, cy);

	if (m_bInitialize) {
		// グラフの座標を再計算
		MakeGraph();

		// サブライン情報表示位置設定
		SetSublineInfoPos();

		// サブライン情報を表示する 20070213 xxxxx kuwa
		DispSublineInfo();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::MakeGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示データ作成
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフ描画に必要なデータを作成する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::MakeGraph()
{
	CManualEditDocument *pDoc = GetDocument();

	// ビューサイズを設定
	SetViewSize();

	// 座標データを削除
	ClearZahyoData();
	m_aZahyoDrag.RemoveAll();
	m_aZahyoDrop.RemoveAll();
	m_aKittingCancel.RemoveAll();

	// サブライン別にグラフの座標を計算
	int nSublineBound = m_rectGraph.left;
	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1) {
			nLeft += m_nBarSpacing;
			nSublineBound += m_nBarSpacing / 2;
			m_aZahyoSubline[nSublineIndex - 1].nRight = nSublineBound;
		}

		MakeGraphSub(cGraphSubline, nLeft);

		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];
		cZahyoSubline.nSublineId = cGraphSubline.nSublineId;
		cZahyoSubline.nLeft = nSublineBound;
		nSublineBound = nLeft - m_nBarSpacing / 2;
		cZahyoSubline.nRight = nSublineBound;
	}

	// 最新移動
// Modify ... ( CHANGE )
	m_rectNewMove.SetRectEmpty();
//	m_rectNewMove = 0;
// By Y.Itabashi (xxxxx) 2007.02.11
	int nSize = m_aZahyoDrag.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoDrag &cSZahyoDrag = m_aZahyoDrag[i];
		if (cSZahyoDrag.pGraphBlock->bNewMove)
			m_rectNewMove.UnionRect(m_rectNewMove, cSZahyoDrag.rect);
	}

// Modify ... ( CHANGE )
	// 選択リセット
	if( GetParentFrame() == GetDocument()->m_pSelectWnd )
		ResetSelectedBox();
//	ResetSelectedBox();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::MakeGraphSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示データ作成
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] グラフ作業者配列
//		int					&nLeft				[I/O] ウィンドウの幅
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフ描画に必要なデータを作成する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::MakeGraphSub(SGraphSubline &cGraphSubline, int &nLeft)
{
	CManualEditDocument *pDoc = GetDocument();
// Modify ... ( CHANGE )
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( cGraphSubline.nSublineId );
//	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11
	CString sKyotsuKotei;
	int nKittingSublineId = -1;
	CRect rectSagyosha;
	SGraphBlock *pLastGraphBlock = NULL;

	CDC *pDC = GetDC();
	CFont *pFontOrg = pDC->SelectObject(&m_fontH);

	// 作業者別にグラフの座標を計算
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
		CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;

		// グラフの座標
		MakeZahyoGraph(cGraphSubline.nSublineId, aGraphBlock, cGraphSagyosha.nSagyoshaId, nLeft, m_rectGraph.bottom, nKittingSublineId, pLastGraphBlock);

		// 作業者名の座標
		CSize size = pDC->GetTextExtent(cGraphSagyosha.sSagyoshaName);
		rectSagyosha.left = nLeft + (m_nBarWidth - size.cx) / 2;
		rectSagyosha.top = m_rectGraph.bottom + 8;
		rectSagyosha.right = rectSagyosha.left + size.cx;
		rectSagyosha.bottom = rectSagyosha.top + size.cy;
		AddZahyoSagyosha(rectSagyosha, cGraphSubline.nSublineId, cGraphSagyosha.nSagyoshaId);


		if (!cGraphSagyosha.bKitting) {
			// 共通工程マークの座標
			if (GetSagyoshaKyotsuKotei(cGraphSagyosha, sKyotsuKotei)) {
				CRect rect;
				rect.left = rectSagyosha.right + 2;
				rect.top = rectSagyosha.top;
				rect.right = rect.left + m_sizeKyotsuKotei.cx;
				rect.bottom = rect.top + m_sizeKyotsuKotei.cy;
				AddZahyoGraph(rect, sKyotsuKotei, COORDINATE_KIND_KYOTSU_KOTEI);
			}
		}

		nLeft += m_nBarWidth + m_nBarSpacing;
	}

	pDC->SelectObject(pFontOrg);
	ReleaseDC(pDC);
}

BOOL CManualEditView1::GetSagyoshaKyotsuKotei(SGraphSagyosha &cGraphSagyosha, CString &sKyotsuKotei)
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 207.02.26
	CStringArrayEx aKyotsuKotei;

// Modify ... ( CHANGE )
	if (pWnd->m_nGraphKishu == -1) {
//	if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 207.02.26
		int nKishuNum = cGraphSagyosha.aKishu.GetSize();
		for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++)
			GetSagyoshaKyotsuKoteiSub(cGraphSagyosha, nKishuId, aKyotsuKotei);
	} else
// Modify ... ( CHANGE )
		GetSagyoshaKyotsuKoteiSub(cGraphSagyosha, pWnd->m_nGraphKishu, aKyotsuKotei);
//		GetSagyoshaKyotsuKoteiSub(cGraphSagyosha, pDoc->m_nGraphKishu, aKyotsuKotei);
// By Y.Itabashi (xxxxx) 2007.02.26

	aKyotsuKotei.Unique();
	sKyotsuKotei = aKyotsuKotei.Join("\n");

	return !sKyotsuKotei.IsEmpty();
}

void CManualEditView1::GetSagyoshaKyotsuKoteiSub(SGraphSagyosha &cGraphSagyosha, int nKishuId, CStringArrayEx &aKyotsuKotei)
{
	CManualEditDocument *pDoc = GetDocument();
	CString sKyotsuKotei;

	CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
	int nSize = aGraphBlock.GetSize();
	for (int i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

		if (nKishuId == -1 || nKishuId == cGraphBlock.nKishuId) {
			if (cGraphBlock.bKyotsuKotei) {
				sKyotsuKotei = "・";

// Modify ... ( CHANGE )
				if (cGraphBlock.sJido == g_pConst->m_sJido){
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[8].MenuNo, Menu_ManualWindowText[8].ItemNo );
					sKyotsuKotei += str;
				}
				else if (cGraphBlock.sJido == g_pConst->m_sHanJido){
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[9].MenuNo, Menu_ManualWindowText[9].ItemNo );
					sKyotsuKotei += str;
				}
/*				if (cGraphBlock.sJido == g_pConst->m_sJido)
					sKyotsuKotei += "自)";
				else if (cGraphBlock.sJido == g_pConst->m_sHanJido)
					sKyotsuKotei += "半)";*/
// By Y.Itabashi (xxxxx) 2007.01.26

				sKyotsuKotei += pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphBlock.sYosoSagyogunName : cGraphBlock.sKoteiName;
				aKyotsuKotei.Add(sKyotsuKotei);
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::MakeZahyoGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ座標データ作成
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphBlockArray	&aGraphBlock	[I] グラフブロック配列
//		int					nSagyoshaId		[I] 作業者ID
//		int					nKishuId		[I] 機種ID
//		int					nLeft			[I] X座標
//		int					nTop			[I] Y座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		四角形の下辺の座標
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工程または要素作業群の四角形の座標を計算する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::MakeZahyoGraph(int nSublineId, CGraphBlockArray &aGraphBlock, int nSagyoshaId, int nLeft, int nTop, int &nKittingSublineId, SGraphBlock *&pLastGraphBlock)
{
// Modify ... ( ADD )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.26
	CManualEditDocument *pDoc = GetDocument();
	int i;
	double fBottom, fTop;
	CRect rect;
	CString sText;
	BOOL bJidoKyotsuKotei = FALSE;
	CRect rectJido;
	CStringArrayEx aJidoKoteiName;
	BOOL bHanJidoKyotsuKotei = FALSE;
	CRect rectHanJido;
	CStringArrayEx aHanJidoKoteiName;
	double fRatio;
	int nGroupNo = 0;
	int nDropTop;
	int nDropBottom = m_rectGraph.bottom;
	int nKyotsuKoteiIndex;
	CPoint pointLastJido = 0;
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );

	// グラフのブロックによるループ
	fBottom = nTop;
	int nSize = aGraphBlock.GetSize();
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];
		CString &sName = pDoc->m_nGraphKind == GRAPH_KOTEI ? cGraphBlock.sKoteiName : cGraphBlock.sYosoSagyogunName;

// Modify ... ( CHANGE )
		if (pWnd->m_nGraphKishu == -1 || cGraphBlock.nKishuId == pWnd->m_nGraphKishu) {
//		if (pDoc->m_nGraphKishu == -1 || cGraphBlock.nKishuId == pDoc->m_nGraphKishu) {
// By Y.Itabashi (xxxxx) 2007.02.26
			pLastGraphBlock = &cGraphBlock;

			// キット化のサブライン区切り
			if (cGraphBlock.bKitting) {
				if (nKittingSublineId != cGraphBlock.nSublineId) {
					if (nKittingSublineId != -1) {
						SZahyoKittingSubline *pKittingSubline = m_aZahyoKittingSubline.AddElement();
						pKittingSubline->pointLine.x = nLeft;
						pKittingSubline->pointLine.y = (int)fBottom;
						pKittingSubline->sSiblineName1 = g_pDataManager->GetSublineData(cGraphBlock.nSublineId)->sSublineName;

						if (i == 0) {
							pKittingSubline = m_aZahyoKittingSubline.AddElement();
							SZahyoDrag &cZahyoDrag = m_aZahyoDrag[m_aZahyoDrag.GetSize() - 1];
							pKittingSubline->pointLine.x = cZahyoDrag.rect.left;
							pKittingSubline->pointLine.y = cZahyoDrag.rect.top;
						}

						pKittingSubline->sSiblineName2 = g_pDataManager->GetSublineData(nKittingSublineId)->sSublineName;
					}
					nKittingSublineId = cGraphBlock.nSublineId;
				}
			}

			// グラフの四角い各領域の座標を計算
// Modify ... ( CHANGE )
			if (pWnd->m_nGraphKishu == -1) {
//			if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
				SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
				fRatio = (double)pKishu->nSeisanDaisu / nTotalSeisanDaisu;
			} else
				fRatio = 1;

			fTop = fBottom - cGraphBlock.fTime * m_fScaleFactor * fRatio;
			rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
			fBottom = fTop;
			sText.Format("%s\n%g", sName, cGraphBlock.fTime);
			AddZahyoGraph(rect, sText, COORDINATE_KIND_SAGYO, cGraphBlock.nKishuId);
			AddZahyoDrag(rect, &cGraphBlock, nSagyoshaId);
			nDropTop = (rect.bottom + rect.top) / 2;
			AddZahyoDrop(CRect(nLeft, nDropTop, rect.right, nDropBottom), &cGraphBlock, nSagyoshaId, CPoint(nLeft, rect.bottom), FALSE);
			nDropBottom = nDropTop;

			// キット化解除による復活枠
			if (cGraphBlock.bKittingCancel)
				m_aKittingCancel.Add(rect);

			// 共通工程の枠の座標
			if (cGraphBlock.bKyotsuKotei) {
				if (nGroupNo != cGraphBlock.nGroupNo) {
					nKyotsuKoteiIndex = AddKyotsuKoteiFrame(rect);
					nGroupNo = cGraphBlock.nGroupNo;
				} else
					m_aZahyoKyotsuKoteiFrame[nKyotsuKoteiIndex].rect.UnionRect(m_aZahyoKyotsuKoteiFrame[nKyotsuKoteiIndex].rect, rect);
			}

			// 自動工程の三角の座標を計算
			if (cGraphBlock.sJido == g_pConst->m_sJido) {
				sText = sName;
				if (cGraphBlock.bKyotsuKotei) {
// Modify ... ( CHANGE )
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[7].MenuNo, Menu_ManualWindowText[7].ItemNo );
					sText += str;
//					sText += "（共通）";
// By Y.Itabashi (xxxxx) 2007.01.26
					bJidoKyotsuKotei = TRUE;
				}
				if (aJidoKoteiName.Find(sText) == -1) {
					if (aJidoKoteiName.GetSize() == 0) {
						rectJido.SetRect(nLeft + m_nBarWidth + 1, (int)(fBottom - JIDO_SIZE), nLeft + m_nBarWidth + JIDO_SIZE + 2, (int)(fBottom + JIDO_SIZE));

						if (pointLastJido == rectJido.TopLeft())
							rectJido.OffsetRect(JIDO_SIZE + 1, 0);

						pointLastJido = rectJido.TopLeft();
					}

					aJidoKoteiName.InsertAt(0, sText);
				}
			}
			if (cGraphBlock.sJido != g_pConst->m_sJido || cGraphBlock.fTime != 0) {
				if (aJidoKoteiName.GetSize() != 0)
					AddZahyoGraph(rectJido, aJidoKoteiName.Join("\n↑\n"), bJidoKyotsuKotei ? COORDINATE_KIND_JIDO_KYOTSU : COORDINATE_KIND_JIDO);

				bJidoKyotsuKotei = FALSE;
				aJidoKoteiName.RemoveAll();
			}

			// 半自動工程の三角の座標を計算
			if (cGraphBlock.sJido == g_pConst->m_sHanJido) {
				sText = sName;
				if (cGraphBlock.bKyotsuKotei) {
// Modify ... ( CHANGE )
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[7].MenuNo, Menu_ManualWindowText[7].ItemNo );
					sText += str;
//					sText += "（共通）";
// By Y.Itabashi (xxxxx) 2007.01.26
					bHanJidoKyotsuKotei = TRUE;
				}
				if (aHanJidoKoteiName.Find(sText) == -1) {
					if (aHanJidoKoteiName.GetSize() == 0) {
						rectHanJido.SetRect(nLeft + m_nBarWidth + 1, (int)(fBottom - JIDO_SIZE), nLeft + m_nBarWidth + JIDO_SIZE + 2, (int)(fBottom + JIDO_SIZE));

						if (pointLastJido == rectHanJido.TopLeft())
							rectHanJido.OffsetRect(JIDO_SIZE + 1, 0);

						pointLastJido = rectHanJido.TopLeft();
					}

					aHanJidoKoteiName.InsertAt(0, sText);
				}
			}
			if (cGraphBlock.sJido != g_pConst->m_sHanJido || cGraphBlock.fTime != 0) {
				if (aHanJidoKoteiName.GetSize() != 0)
					AddZahyoGraph(rectHanJido, aHanJidoKoteiName.Join("\n↑\n"), bHanJidoKyotsuKotei ? COORDINATE_KIND_HANJIDO_KYOTSU : COORDINATE_KIND_HANJIDO);

				bHanJidoKyotsuKotei = FALSE;
				aHanJidoKoteiName.RemoveAll();
			}
		}
	}

	if (aJidoKoteiName.GetSize() != 0)
		AddZahyoGraph(rectJido, aJidoKoteiName.Join("\n↑\n"), bJidoKyotsuKotei ? COORDINATE_KIND_JIDO_KYOTSU : COORDINATE_KIND_JIDO);

	if (aHanJidoKoteiName.GetSize() != 0)
		AddZahyoGraph(rectHanJido, aHanJidoKoteiName.Join("\n↑\n"), bHanJidoKyotsuKotei ? COORDINATE_KIND_HANJIDO_KYOTSU : COORDINATE_KIND_HANJIDO);

	BOOL bLastBlock = TRUE;
	SGraphBlock *pLastGraphBlock2 = pLastGraphBlock;
	if (pLastGraphBlock2 == NULL) {
		pLastGraphBlock2 = pDoc->GetFirstGraphBlock(nSublineId);
		bLastBlock = FALSE;
	}
	AddZahyoDrop(CRect(nLeft, (int)fBottom - 50, nLeft + m_nBarWidth, nDropBottom), pLastGraphBlock2, nSagyoshaId, CPoint(nLeft, (int)fBottom), bLastBlock);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::SetViewSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフの大きさに合わせてビューサイズを設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ウィンドウサイズに合わせてビューのサイズを計算する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::SetViewSize()
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26

	// ビューのサイズを計算
	int nSagyoshaNum = GetTotalSagyoshaNum();
	GetClientRect(m_rectView);
	m_rectView.right = m_nLeftMargin + m_nLeftSpacing + nSagyoshaNum * (m_nBarWidth + m_nBarSpacing) + m_nRightMargin;
	int nKittingSagyoshaNum = GetKittingSagyoshaNum();
	if (nKittingSagyoshaNum != 0)
		m_rectView.right += m_nBarSpacing + nKittingSagyoshaNum * (m_nBarWidth + m_nBarSpacing);

	// グラフ領域を計算
	m_rectGraph = m_rectView;
	m_rectGraph.DeflateRect(m_nLeftMargin, m_nTopMargin, m_nRightMargin, m_nBottomMargin);

	SetScaleFactor(FALSE, 0);

	// 一番高いバーに合わせてビュー領域を拡張
// Modify ... ( CHANGE )
	int nOverHeight = GetScaleHeight(GetMaxTime(pWnd->m_nGraphKishu)) - m_rectGraph.Height();
//	int nOverHeight = GetScaleHeight(GetMaxTime(pDoc->m_nGraphKishu)) - m_rectGraph.Height();
// By Y.Itabashi (xxxxx) 2007.02.26
	if (nOverHeight > 0) {
		m_rectView.bottom += nOverHeight;
		m_rectGraph.bottom += nOverHeight;
	}

// Modify ... ( CHANGE )
	SCROLLINFO scrollInfo;
	CScrollBar *pBar;

	// 水平スクロールバーの設定
	pBar = GetScrollBarCtrl( SB_HORZ );
	memset( &scrollInfo, '\0', sizeof(SCROLLINFO) );
	scrollInfo.fMask = SIF_RANGE;
	scrollInfo.nMin = 0;
	scrollInfo.nMax = m_rectView.Width();
	pBar->SetScrollInfo( &scrollInfo, TRUE );

	// 垂直スクロールバーの設定
	pBar = GetScrollBarCtrl( SB_VERT );
	memset( &scrollInfo, '\0', sizeof(SCROLLINFO) );
	scrollInfo.fMask = SIF_RANGE;
	scrollInfo.nMin = 0;
	scrollInfo.nMax = m_rectView.Height();
	pBar->SetScrollInfo( &scrollInfo, TRUE );
	scrollInfo.fMask = SIF_PAGE;
	if (nOverHeight > 0)
		scrollInfo.nPage = m_rectView.Height() / 10;
	else
		scrollInfo.nPage = m_rectView.Height();
	pBar->SetScrollInfo( &scrollInfo, TRUE );
	if( nOverHeight<= 0 )
		pBar->EnableWindow( FALSE );

	SetScrollSizes(MM_TEXT, CSize(m_rectView.Width(), m_rectView.Height()));
// By Y.Itabashi (xxxxx) 2007.03.04
}

void CManualEditView1::DrawNewMoveFrame(CDC *pDC)
{
	if (!m_rectNewMove.IsRectNull()) {
		CPen *pPenOrg = pDC->SelectObject(&m_penNewMove);
		CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

		CRect rect(m_rectNewMove.left, m_rectNewMove.top, m_rectNewMove.right + 1, m_rectNewMove.bottom + 1);
		pDC->Rectangle(rect);

		pDC->SelectObject(pPenOrg);
		pDC->SelectObject(pBrushOrg);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::DrawHorizontalAxisText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸テキスト描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC			[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		横軸のテキスト（作業者名）を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::DrawHorizontalAxisText(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();

	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		DrawHorizontalAxisTextSub(pDC, pDoc->m_aGraphSubline[nSublineIndex], nLeft);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::DrawHorizontalAxisTextSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸テキスト描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC					*pDC				[I] デバイスコンテキスト
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] グラフ作業者配列
//		int					&nLeft				[I/O] X座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		横軸のテキスト（作業者名）を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::DrawHorizontalAxisTextSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft)
{
	CManualEditDocument *pDoc = GetDocument();

	CFont *pFontOrg = pDC->SelectObject(&m_fontH);

	// 作業者によるループ
	CRect rect;
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
		CSize size = pDC->GetTextExtent(cGraphSagyosha.sSagyoshaName);
		int x = nLeft + (m_nBarWidth - size.cx) / 2;
		rect.SetRect(x, m_rectGraph.bottom + 8, x + size.cx, m_rectView.bottom);
		if (pDC->RectVisible(rect)) {
//			double fTime = (pDoc->m_nGraphKishu == -1) ? cGraphSagyosha.fTime : cGraphSagyosha.aKishu[pDoc->m_nGraphKishu].fTime;

			// 作業者名を表示
			int color;
			if (pDoc->m_cSelectSagyosha.bSelect && pDoc->m_cSelectSagyosha.nSubnlineId == cGraphSubline.nSublineId && pDoc->m_cSelectSagyosha.nSagyoshaId == cGraphSagyosha.nSagyoshaId)
				color = pDC->SetTextColor(g_pSystem->m_cIniUser.m_rgbSelectWorkerColor);
			else
				color = pDC->SetTextColor(RGB(0, 0, 0));
			pDC->TextOut(rect.left, rect.top, cGraphSagyosha.sSagyoshaName);
			pDC->SetTextColor(color);
		}

		nLeft += m_nBarWidth + m_nBarSpacing;
	}

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::DrawTotalTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		合計時間描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC			[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフのバーの上に合計時間を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::DrawTotalTime(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();

	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		DrawTotalTimeSub(pDC, pDoc->m_aGraphSubline[nSublineIndex], nLeft);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::DrawTotalTimeSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		合計時間描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC					*pDC				[I] デバイスコンテキスト
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] グラフ作業者配列
//		int					&nLeft				[I/O] X座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフのバーの上に合計時間を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::DrawTotalTimeSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft)
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26

	CFont *pFontOrg = pDC->SelectObject(&m_fontTime);

	CString sTime;
	CRect rectText;

	// 作業によるループ
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
// Modify ... ( CHANGE )
		double fTime = (pWnd->m_nGraphKishu == -1) ? cGraphSagyosha.fTime : cGraphSagyosha.aKishu[pWnd->m_nGraphKishu].fTime;
//		double fTime = (pDoc->m_nGraphKishu == -1) ? cGraphSagyosha.fTime : cGraphSagyosha.aKishu[pDoc->m_nGraphKishu].fTime;
// By Y.Itabashi (xxxxx) 2007.02.26
		DrawTime(pDC, cGraphSubline, nLeft, fTime);

		nLeft += m_nBarWidth + m_nBarSpacing;
	}

	pDC->SelectObject(pFontOrg);
}

// キット化解除による復活枠描画
void CManualEditView1::DrawKittingCancelFrame(CDC *pDC)
{
// Modify ... ( ADD )
	// 最新の移動の表示
	if( m_rectNewMove.IsRectNull() ){
		CIntArrayEx aCheckArea;
		aCheckArea.RemoveAll();

		while( 1 ){

			CRect newRect;
			newRect.SetRectEmpty();

			for( int i = 0; i < m_aKittingCancel.GetSize(); i++ ){
				if( aCheckArea.Find( i ) != -1 )
					continue;

				if( newRect.IsRectNull() ){
					newRect = m_aKittingCancel[i];
					aCheckArea.Add( i );
					continue;
				}

				if( newRect.left != m_aKittingCancel[i].left )
					continue;

				if( newRect.top == m_aKittingCancel[i].bottom ||
					newRect.bottom == m_aKittingCancel[i].top )
					newRect.UnionRect( newRect, m_aKittingCancel[i] );
				else
					continue;

				aCheckArea.Add( i );
			}

			if( !newRect.IsRectNull() ){
				CPen *pPenOrg = pDC->SelectObject(&m_penNewMove);
				CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

				CRect rect(newRect.left, newRect.top, newRect.right + 1, newRect.bottom + 1);
				pDC->Rectangle(rect);

				pDC->SelectObject(pPenOrg);
				pDC->SelectObject(pBrushOrg);
			}else
				break;
		}
		aCheckArea.RemoveAll();
	}
// By Y.Itabashi (xxxxx) 2007.03.06

	CPen *pPenOrg = pDC->SelectObject(&m_penKittingCancel);

	int nSize = m_aKittingCancel.GetSize();
	for (int i = 0; i < nSize; i++) {
		CRect rect = m_aKittingCancel[i];

		pDC->MoveTo(rect.left, rect.top);
		pDC->LineTo(rect.right, rect.top);
		pDC->MoveTo(rect.left, rect.top - 1);
		pDC->LineTo(rect.right, rect.top - 1);

		pDC->MoveTo(rect.right, rect.top);
		pDC->LineTo(rect.right, rect.bottom);
		pDC->MoveTo(rect.right - 1, rect.top);
		pDC->LineTo(rect.right - 1, rect.bottom);

		pDC->MoveTo(rect.left, rect.bottom);
		pDC->LineTo(rect.right, rect.bottom);
		pDC->MoveTo(rect.left, rect.bottom - 1);
		pDC->LineTo(rect.right, rect.bottom - 1);

		pDC->MoveTo(rect.left, rect.top);
		pDC->LineTo(rect.left, rect.bottom);
		pDC->MoveTo(rect.left - 1, rect.top);
		pDC->LineTo(rect.left - 1, rect.bottom);
	}

	pDC->SelectObject(pPenOrg);
}

// 参照工程枠描画
void CManualEditView1::DrawReferenceFrame(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nReferenceBox = pDoc->m_aRefYosoSagyogun.GetSize();
	if (nReferenceBox == 0)
		return;

	int i, j;
	CArray <CRect, CRect&> aBlinkRect;

	for (i = 0; i < nReferenceBox; i++) {

		if( nGraphKishu != pDoc->m_aRefYosoSagyogun[i].nKishuId )
			continue;

		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aRefYosoSagyogun[i].nIndex : pDoc->m_aRefYosoSagyogun[i].nIndexKishu;
		CRect rectRefBox = m_aZahyoDrag[nIndex].rect;

		int nBlinkRect = aBlinkRect.GetSize();
		for (j = 0; j < nBlinkRect; j++) {
			CRect &rectBlink = aBlinkRect[j];

			if (rectRefBox.left == rectBlink.left) {
				if (rectRefBox.bottom == rectBlink.top) {
					rectBlink.top = rectRefBox.top;
					break;
				}

				if (rectRefBox.top == rectBlink.bottom) {
					 rectBlink.bottom = rectRefBox.bottom;
					break;
				}
			}
		}

		if (j == nBlinkRect)
			aBlinkRect.Add(rectRefBox);
	}

	CPen *pPenOrg = pDC->SelectObject(&m_penReference);
	CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

	for (j = 0; j < aBlinkRect.GetSize(); j++) {
		CRect rect(aBlinkRect[j]);
		rect.right++;
		rect.bottom++;
		pDC->Rectangle(rect);
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnRButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右クリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 仮想キーの状態
//		CPoint		point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		右クリックメニューを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnRButtonDown(UINT nFlags, CPoint point)
{
// Modify ... ( ADD )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.26
	CManualEditDocument *pDoc = GetDocument();

	// クリックされたグラフの座標を求める
	CRect rect;
	m_pRightClickZahyo = CheckZahyoDrag(point, rect);

	// リソースからメニューをロード
	CMenu cMenu;
	cMenu.LoadMenu(IDR_MANUAL_POPUPMENU);
	CMenu* pPopup = cMenu.GetSubMenu(0);

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( (CWnd *)pPopup, Menu_ManualWindowPopup );
// By Y.Itabashi (xxxxx) 2007.01.26

	if (m_pRightClickZahyo == NULL) {
// Modify ... ( CHANGE )
		if (pWnd->m_nGraphKishu == -1) {
//		if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
			// グラフのバーの上でなかったら加重平均表示での移動、分割削除は不可
// Modify ... ( CHANGE )
//			pPopup->EnableMenuItem(IDM_MANUAL_MOVE, MF_GRAYED);
// By Y.Itabashi (xxxxx)
			pPopup->EnableMenuItem(IDM_MANUAL_BUNKATSU, MF_GRAYED);
		}
	}

	// 加重平均表示時は編集不可
// Modify ... ( CHANGE )
	// UP4出荷モジュール用
// Modify ... ( CHANGE )
	if (pWnd->m_nGraphKishu == -1)
//	if (pDoc->m_nGraphKishu == -1)
// By Y.Itabashi (xxxxx) 2007.02.26
		pPopup->EnableMenuItem(IDM_MANUAL_EDIT, MF_GRAYED);
// By Y.Itabashi (xxxxx) 2007.02.22
// Modify ... ( ADD )
// Modify ... ( CHANGE )
	if (pWnd->m_nGraphKishu == -1)
//	if (pDoc->m_nGraphKishu == -1)
// By Y.Itabashi (xxxxx) 2007.02.26
		pPopup->EnableMenuItem(IDM_MANUAL_PASTE, MF_GRAYED);
// By Y.Itabashi (xxxxx)

	// 複数選択されていたらコピーは不可
// Modify ... ( CHANGE )
	// UP4出荷モジュール用
//	if (pDoc->m_aSelectedBox.GetSize() > 1 || pDoc->m_aSelectedBox.GetSize() == 0 || pDoc->m_bSelectKitting)
	if (pDoc->m_aSelectYosoSagyogun.GetSize() > 1 || pDoc->m_aSelectYosoSagyogun.GetSize() == 0 || pDoc->m_bSelectKitting)
		pPopup->EnableMenuItem(IDM_MANUAL_COPY, MF_GRAYED);
// By Y.Itabashi (xxxxx) 2007.02.27

// Modify ... ( ADD )
	// UP4出荷モジュール用
//	pPopup->EnableMenuItem(IDM_MANUAL_EDIT, MF_GRAYED);
//	pPopup->EnableMenuItem(IDM_MANUAL_MOVE, MF_GRAYED);
//	pPopup->EnableMenuItem(IDM_MANUAL_COPY, MF_GRAYED);
//	pPopup->EnableMenuItem(IDM_MANUAL_PASTE, MF_GRAYED);
// By Y.Itabashi (xxxxx) 2007.02.22

	// クリックされた位置にポップアップメニューを表示
	::GetCursorPos(&point);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, point.x, point.y, this);

	CManualEditView::OnRButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnManualMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程・要素作業群の移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工程・要素作業群の移動ダイアログを開く。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnManualMove()
{
	// ライン規制ファイルが開いていたらエラー
	if (g_pDataManager->m_bOpenLineKiseiMatrix) {
		g_pSystem->DispMessage("E006002");
		return;
	}

	// 型式規制ファイルが開いていたらエラー
	if (g_pDataManager->m_bOpenKatashikiKiseiMatrix) {
		g_pSystem->DispMessage("E006102");
		return;
	}

	CManualMoveDlg dlg(this);

	CManualEditDocument *pDoc = GetDocument();
	if (m_pRightClickZahyo != NULL) {
		// グラフのバー上ならそのデータをダイアログに渡す
		dlg.m_nKishuId = m_pRightClickZahyo->pGraphBlock->nKishuId;
		dlg.m_sSagyosha = g_pDataManager->GetSagyoshaName(m_pRightClickZahyo->pGraphBlock->nSublineId, m_pRightClickZahyo->nSagyoshaId);
	} else {
		// グラフのバー上でないなら機種をダイアログに渡す
// Modify ... ( CHANGE )
		dlg.m_nKishuId = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
		dlg.m_nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
//		dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26
	}
	dlg.m_nMode = pDoc->m_nGraphKind;
	dlg.m_pDoc = pDoc;

	// 工程・要素作業群の移動ダイアログ表示
	if (dlg.DoModal() == IDOK) {
		// OKボタンならグラフを更新
		GetDocument()->UpdateGraph(FALSE);

		// 現在のデータをUndoバッファに保存
		g_pDataManager->SaveUndoBuf();
	} else {
		// Cancelボタンならグラフを元に戻す
		GetDocument()->UpdateGraph(FALSE);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnManualBunkatsu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程・要素作業群の分割削除処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工程・要素作業群の分割削除ダイアログを開く。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnManualBunkatsu()
{
	CManualBunkatsuDlg dlg(this);

	if (m_pRightClickZahyo != NULL) {
		// グラフのバー上ならそのデータをダイアログに渡す
		dlg.m_nKishuId = m_pRightClickZahyo->pGraphBlock->nKishuId;
/*
		dlg.m_nSagyoshaId = m_pRightClickZahyo->nSagyoshaId;
		dlg.m_sKoteiName = m_pRightClickZahyo->pGraphBlock->sKoteiName;
		dlg.m_sYosoSagyogunName = m_pRightClickZahyo->pGraphBlock->sYosoSagyogunName;
*/
	} else {
		// グラフのバー上でないなら機種をダイアログに渡す
// Modify ... ( CHANGE )
		CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
		dlg.m_nKishuId = pWnd->m_nGraphKishu;
//		CManualEditDocument *pDoc = GetDocument();
//		dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26
	}
	dlg.m_pDoc = GetDocument();

	// 工程・要素作業群の分割削除ダイアログ表示
	if (dlg.DoModal() == IDOK) {
		// OKボタンならグラフを更新
		GetDocument()->UpdateGraph(TRUE);

		// 現在のデータをUndoバッファに保存
		g_pDataManager->SaveUndoBuf();
	} else {
		// Cancelボタンならグラフを元に戻す
		GetDocument()->UpdateGraph(FALSE);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnManualEdit
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工程・要素作業群の修正処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		工程・要素作業群の修正ダイアログを開く。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnManualEdit()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditDlg dlg(this);

	if (m_pRightClickZahyo != NULL && !m_pRightClickZahyo->pGraphBlock->bKitting) {
		dlg.m_nKishuId = m_pRightClickZahyo->pGraphBlock->nKishuId;
		dlg.m_nSagyoshaId = m_pRightClickZahyo->nSagyoshaId;
	} else {
// Modify ... ( CHANGE )
		dlg.m_nKishuId = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
//		dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26
	}
	dlg.m_pDoc = pDoc;

	if (dlg.DoModal() == IDOK) {
		// OKボタンならグラフを更新
		GetDocument()->UpdateGraph(TRUE);

		// 現在のデータをUndoバッファに保存
		g_pDataManager->SaveUndoBuf();
	} else {
		// Cancelボタンならグラフを元に戻す
		GetDocument()->UpdateGraph(FALSE);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンダウン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 仮想キーの状態
//		CPoint		point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ドラッグ開始のマウスカーソルの位置を保存する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnLButtonDown(UINT nFlags, CPoint point)
{

// Modify ... ( ADD )
	// 前に作業を選択した画面と同一画面でなければ、選択クリア
	if( GetParentFrame() != GetDocument()->m_pSelectWnd )
		ResetSelectedBox();

	GetDocument()->m_pSelectWnd = GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.27

	// クリックされた座標をチェック
	SZahyoDrag *pZahyoDrag = CheckZahyoDrag(point, m_rectDrag);
	if (pZahyoDrag != NULL) {
// Modify ... ( CHANGE )
		if (!pZahyoDrag->pGraphBlock->bKitting)
			ClickSublineArea(point, pZahyoDrag, nFlags);
//			ClickSublineArea(point, pZahyoDrag);
		else
			ClickKittingArea(point, pZahyoDrag, nFlags);
//			ClickKittingArea(point, pZahyoDrag);
// By Y.Itabashi (xxxxx) 2007.02.20
	} else {
		// シフトキーが押されてキット化エリアなら選択を解除しない
		SZahyoSubline *pZahyoSubline = GetZahyoSubline(point);
// Modify ... ( CHANGE )
		if ( pZahyoSubline == NULL || pZahyoSubline->nSublineId != -1 ||
			 ( nFlags != 0x0005 && pZahyoSubline->nSublineId == -1 ) )
//		if (!(GetKeyState(VK_SHIFT) & 0x8000) || pZahyoSubline == NULL || pZahyoSubline->nSublineId != -1)
// By Y.Itabashi (xxxxx) 2007.03.06
			ResetSelectedBox();

		SetCapture();
		m_rectSelect.SetRect(point, point);
		DrawFocusRect(m_rectSelect);
		m_nMouseMoveMode = MOUSE_SELECT;
	}

	SelectSagyosha(point);

	CManualEditView::OnLButtonDown(nFlags, point);
}

// Modify ... ( CHANGE )
void CManualEditView1::ClickSublineArea(CPoint point, SZahyoDrag *pZahyoDrag, UINT nFlags)
//void CManualEditView1::ClickSublineArea(CPoint point, SZahyoDrag *pZahyoDrag)
// By Y.Itabashi (xxxxx) 2007.02.21
{
	CManualEditDocument *pDoc = GetDocument();
	int i;

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSelectedBox = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSelectedBox = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27

	// シフトキーをチェック
// Modify ... ( CHANGE )
	if (nFlags == 0x0005) {
//	if (GetKeyState(VK_SHIFT) & 0x8000) {
// By Y.Itabashi (xxxxx) 2007.02.21
		// シフトキーが押されている場合の処理
		if (nSelectedBox == 0) {
			// ひとつも選択されていない場合はクリックした場所を選択する
			AddSelect(pZahyoDrag->nIndex);
		} else {
			// 既に選択されている場合は、クリックした場所との間を全て選択する
// Modify ... ( CHANGE )
			int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
			SZahyoDrag &cSelectedZahyoDrag = m_aZahyoDrag[nIndex];
			if (cSelectedZahyoDrag.rect.left == pZahyoDrag->rect.left) {
//			SZahyoDrag &cSelectedZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[0]];
//			if (m_aZahyoDrag[pDoc->m_aSelectedBox[0]].rect.left == pZahyoDrag->rect.left) {
// By Y.Itabashi (xxxxx) 2007.02.27
				CRect rect = GetSelectRect();
				rect.UnionRect(rect, pZahyoDrag->rect);
	
				ResetSelectedBox();
				int nSize = m_aZahyoDrag.GetSize();
				for (i = 0; i < nSize; i++) {
					SZahyoDrag &cZahyoDrag = m_aZahyoDrag[i];
					if (rect.PtInRect(cZahyoDrag.rect.TopLeft()))
						AddSelect(cZahyoDrag.nIndex);
				}
			} else {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
		}
	} else {
		// シフトキーが押されていない場合の処理
		if (nSelectedBox == 0) {
			// ひとつも選択されていない場合はクリックした場所を選択する
			AddSelect(pZahyoDrag->nIndex);
		} else if (nSelectedBox == 1) {
			// ひとつ選択されている場合は、それを解除してクリックした場所を選択する
// Modify ... ( CHANGE )
			int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
			SZahyoDrag &cSelectedZahyoDrag = m_aZahyoDrag[nIndex];
			if (pZahyoDrag->nIndex == cSelectedZahyoDrag.nIndex)
//			if (pZahyoDrag->nIndex == m_aZahyoDrag[pDoc->m_aSelectedBox[0]].nIndex)
// By Y.Itabashi (xxxxx) 2007.02.27
				m_nSelectRemoveIndex = pZahyoDrag->nIndex;
			else {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
		} else {
			// 複数選択されている場合は、上下端なら選択を解除する
// Modify ... ( CHANGE )
			int m_nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
			int nLastIndex = nSelectedBox-1;
// Modify ... ( CHANGE )
			for( i = nSelectedBox-1; i >= 0 && m_nGraphKishu != -1; i-- ){
//			for( i = nSelectedBox-1; i >= 0; i-- ){
				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId == m_nGraphKishu ){
					nLastIndex = i;
					break;
				}
// By Y.Itabashi (xxxxx) 2007.03.06

			}
			for (i = 0; i < nSelectedBox; i++) {
// Modify ... ( CHANGE )
				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != m_nGraphKishu &&
					m_nGraphKishu != -1 )
//				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != m_nGraphKishu )
// By Y.Itabashi (xxxxx) 2007.03.05
					continue;

				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				if (m_aZahyoDrag[nIndex].nIndex == pZahyoDrag->nIndex)
//				if (m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex == pZahyoDrag->nIndex)
					break;
			}
			if (i == 0 || i == nLastIndex) {
//			if (i == 0 || i == nSelectedBox - 1) {
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				m_nSelectRemoveIndex = m_aZahyoDrag[nIndex].nIndex;
//				m_nSelectRemoveIndex = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex;
			} else if (i == nSelectedBox) {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
// By Y.Itabashi (xxxxx) 2007.02.27
		}

		m_pointDrag = point;
		m_nMouseMoveMode = MOUSE_DRAG;
// Modify ... ( ADD )
		pDoc->m_pDragWnd = GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.27
	}

// Modify ... ( ADD )
	pDoc->AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

// Modify ... ( CHANGE )
void CManualEditView1::ClickKittingArea(CPoint point, SZahyoDrag *pZahyoDrag, UINT nFlags)
//void CManualEditView1::ClickKittingArea(CPoint point, SZahyoDrag *pZahyoDrag)
// By Y.Itabashi (xxxxx) 2007.02.21
{
// Modify ... ( ADD )
	// UP4用モジュール
//	return;
// By Y.Itabashi (xxxxx) 2007.02.14

	CManualEditDocument *pDoc = GetDocument();
	int i;

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSelectedBox = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSelectedBox = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27

	// シフトキーをチェック
// Modify ... ( CHANGE )
	if (nFlags == 0x0005) {
//	if (GetKeyState(VK_SHIFT) & 0x8000) {
// By Y.Itabashi (xxxxx) 2007.02.21
		// シフトキーが押されている場合の処理
		AddSelect(pZahyoDrag->nIndex);
	} else {
		// シフトキーが押されていない場合の処理
		if (nSelectedBox == 0) {
			// ひとつも選択されていない場合はクリックした場所を選択する
			AddSelect(pZahyoDrag->nIndex);
		} else if (nSelectedBox == 1) {
			// ひとつ選択されている場合は、それを解除してクリックした場所を選択する
// Modify ... ( CHANGE )
			int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
			if (pZahyoDrag->nIndex == m_aZahyoDrag[nIndex].nIndex)
//			if (pZahyoDrag->nIndex == m_aZahyoDrag[pDoc->m_aSelectedBox[0]].nIndex)
// By Y.Itabashi (xxxxx) 2007.02.27
				m_nSelectRemoveIndex = pZahyoDrag->nIndex;
			else {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
			}
		} else {
			// 複数選択されている場合はクリックした場所の選択を解除する
// Modify ... ( CHANGE )
			for (i = 0; i < nSelectedBox; i++) {
// Modify ... ( CHANGE )
				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu &&
					nGraphKishu != -1 )
//				if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu )
// By Y.Itabashi (xxxxx) 2007.03.06
					continue;

				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				if (m_aZahyoDrag[nIndex].nIndex == pZahyoDrag->nIndex)
//				if (m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex == pZahyoDrag->nIndex)
					break;
			}
// By Y.Itabashi (xxxxx) 2007.02.27
			if (i == nSelectedBox) {
				ResetSelectedBox();
				AddSelect(pZahyoDrag->nIndex);
// Modify ... ( CHANGE )
			} else{
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
				m_nSelectRemoveIndex = m_aZahyoDrag[nIndex].nIndex;
			}
//				m_nSelectRemoveIndex = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].nIndex;
// By Y.Itabashi (xxxxx) 2007.02.27
		}

		m_pointDrag = point;
		m_nMouseMoveMode = MOUSE_DRAG;
// Modify ... ( ADD )
		pDoc->m_pDragWnd = GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.27
	}

// Modify ... ( ADD )
	pDoc->AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンアップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 仮想キーの状態
//		CPoint		point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		クリックされた座標をキャンセルする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnLButtonUp(UINT nFlags, CPoint point)
{
	switch (m_nMouseMoveMode) {
	case MOUSE_SELECT:
		DrawFocusRect(m_rectSelect);
		SelectRect();
		ReleaseCapture();
		break;
	}

	if (m_nSelectRemoveIndex != -1) {
		RemoveSelect(m_nSelectRemoveIndex);
		m_nSelectRemoveIndex = -1;
	}

	m_nMouseMoveMode = MOUSE_NONE;

// Modify ... ( ADD )
	GetDocument()->m_pDragWnd = NULL;
// By Y.Itabashi (xxxxx) 2007.02.28

	CManualEditView::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 仮想キーの状態
//		CPoint		point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		クリックされている場合はドラッグを開始し、ドロップされたら移動処理を行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::OnMouseMove(UINT nFlags, CPoint point)
{
	CManualEditDocument *pDoc = GetDocument();

	switch (m_nMouseMoveMode) {
	case MOUSE_SELECT:
		DrawFocusRect(m_rectSelect);
		m_rectSelect.right = point.x;
		m_rectSelect.bottom = point.y;
		DrawFocusRect(m_rectSelect);
		break;
	case MOUSE_DRAG:
		// ドラッグ中に点滅させるとドラッグ枠が消えるので点滅を止める
		pDoc->StopBlink(TRUE);
// Modify ... ( ADD )
		if( pDoc->m_pDragWnd && pDoc->m_pDragWnd != GetParentFrame() )
			break;
// By Y.Itabashi (xxxxx) 2007.02.28

		// クリックされているならドラッグする四角を表示
		CRectTrackerEx cRectTracker(GetSelectRect() - GetScrollPosition(), CRectTracker::hatchedBorder);
		pDoc->m_pDrop = NULL;
		m_pointDragOld = 0;
		if (cRectTracker.Track(AfxGetMainWnd(), m_pointDrag, FALSE, NULL)) {
			if (!pDoc->m_bSelectKitting) {
				// グラフのバー上でドロップされたらグラフの種類により工程または要素作業群の移動処理
				if (pDoc->m_pDrop != NULL)
					MoveDrag();
			} else {
				// キット化作業が選択されていてサブラインエリアでドロップされたらキット化解除
				if (pDoc->m_nDropSublineId != -1)
					CancelKitting();
			}
		}
		pDoc->StopBlink(FALSE);
		pDoc->UpdateAllViews(NULL, UPDATE_ERASE_MOVE_TRIANGLE);
		m_nMouseMoveMode = MOUSE_NONE;
// Modify ... ( ADD )
		pDoc->m_pDragWnd = NULL;
// By Y.Itabashi (xxxxx) 2007.02.28
		m_nSelectRemoveIndex = -1;
		break;
	}

	CManualEditView::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnUserDrag
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ドラッグ中のマウス移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM	wParam			[I] ウィンドウメッセージパラメータ１
//		LPARAM	lParam			[I] ウィンドウメッセージパラメータ２
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		マウスカーソルが移動可能な位置にある場合は、移動先の三角形を表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
LRESULT CManualEditView1::OnUserDrag(WPARAM wParam, LPARAM lParam)
{
	CManualEditDocument *pDoc = GetDocument();

	CPoint point;
	::GetCursorPos(&point);
	ScreenToClient(&point);

// Modify ... ( ADD )
	if( pDoc->m_pDragWnd != GetParentFrame() )
		return 0;

	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.28

	// キット化作業が選択されている場合は何もしない
	if (!pDoc->m_bSelectKitting) {
		if (wParam == USER_DRAG_ENTER || wParam == USER_DRAG_OVER) {
// Modify ... ( CHANGE )
			if (pDoc->m_aSelectYosoSagyogun.GetSize() != 0) {
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
				SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
//			if (pDoc->m_aSelectedBox.GetSize() != 0) {
//				SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[0]];
// By Y.Itabashi (xxxxx) 2007.02.27

				CRect rect;
				pDoc->m_pDrop = CheckZahyoDrop(point, rect);
				if (pDoc->m_pDrop != NULL
						&& pDoc->m_pDrop->pGraphBlock->bKitting == cZahyoDrag.pGraphBlock->bKitting
						&& (cZahyoDrag.pGraphBlock->nSublineId != pDoc->m_pDrop->pGraphBlock->nSublineId
						|| cZahyoDrag.nSagyoshaId != pDoc->m_pDrop->nSagyoshaId
						|| !CheckSelect(pDoc->m_pDrop->pGraphBlock))) {
					int nIndex1 = pDoc->m_pDrop->nIndex;
					int nIndex2 = nIndex1 - 1;

					if (nIndex2 == -1
							|| m_aZahyoDrop[nIndex1].bLastBlock
							|| m_aZahyoDrop[nIndex1].nSagyoshaId != m_aZahyoDrop[nIndex2].nSagyoshaId
							|| !m_aZahyoDrop[nIndex1].pGraphBlock->bKyotsuKotei
							|| !m_aZahyoDrop[nIndex2].pGraphBlock->bKyotsuKotei
							|| m_aZahyoDrop[nIndex1].pGraphBlock->nGroupNo != m_aZahyoDrop[nIndex2].pGraphBlock->nGroupNo) {
						if (wParam == USER_DRAG_ENTER)
							m_pointDragOld = CPoint(0, 0);

						// グラフのバー上なら移動先の三角を表示
						DrawMoveTriangle(pDoc->m_pDrop->pointTriangle - GetScrollPosition());

						return 0;
					}
				}
			}
		}

		// グラフのバー上でないなら移動先の三角を消去
		DrawMoveTriangle(0);
		pDoc->m_pDrop = NULL;
	} else {
		SZahyoSubline *pZahyoSubline = GetZahyoSubline(point);
		if (pZahyoSubline != NULL)
			pDoc->m_nDropSublineId = pZahyoSubline->nSublineId;
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::DrawMoveTriangle
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		移動先の三角形描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CPoint		point			[I] 三角形を描画する座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された位置に三角形を描画する。以前の位置からは消去する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::DrawMoveTriangle(CPoint point)
{
	if (point != m_pointDragOld) {
		// 描画モード設定
		CDC *pDC = GetDC();
		int nDrawMode = pDC->SetROP2(R2_XORPEN);
		CBrush *pBrush = pDC->SelectObject(&m_brushMoveDrag);
		CPen *pPen = pDC->SelectObject((CPen *)pDC->SelectStockObject(NULL_PEN));

		// 以前の三角形を消去
		if (m_pointDragOld != CPoint(0, 0)) {
			CRect rect(m_pointDragOld.x - 8, m_pointDragOld.y - 6, m_pointDragOld.x - 1, m_pointDragOld.y + 6);
			DrawRightTriangle(pDC, rect);
			m_pointDragOld = 0;
		}

		// 新しい位置に三角形を描画
		if (point != CPoint(0, 0)) {
			CRect rect(point.x - 8, point.y - 6, point.x - 1, point.y + 6);
			DrawRightTriangle(pDC, rect);
		}
		m_pointDragOld = point;

		pDC->SelectObject(pPen);
		pDC->SelectObject(pBrush);
		pDC->SetROP2(nDrawMode);
		ReleaseDC(pDC);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::MoveKotei
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ドラッグによる工程移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ドラッグによる工程移動を行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::MoveDrag()
{
// Modify ... ( ADD )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// By Y.Itabashi (xxxxx) 2007.02.26
	CManualEditDocument *pDoc = GetDocument();
	int i;

// Modify ... ( ADD )
	int nGraphKishu;
	int nKishu;
	int nSize;
	BOOL bSetGraph;
	int nSetIndex;
	CGraphBlockPtrArray aGraphBlockFrom;
	CSelectYosoSagyogunArray m_aSelectYosoSagyogunBack;
	CReferenceYosoSagyogunArray m_aRefYosoSagyogunBack;

	m_aSelectYosoSagyogunBack.RemoveAll();
	for( i = 0; i < pDoc->m_aSelectYosoSagyogun.GetSize(); i++ )
		m_aSelectYosoSagyogunBack.Add( pDoc->m_aSelectYosoSagyogun[i] );

	m_aRefYosoSagyogunBack.RemoveAll();
	for( i = 0; i < pDoc->m_aRefYosoSagyogun.GetSize(); i++ )
		m_aRefYosoSagyogunBack.Add( pDoc->m_aRefYosoSagyogun[i] );
	// 直後が自動工程ならそれも選択する
	pDoc->AddSelectAutoKotei();
// By Y.Itabashi (xxxxx) 2007.03.07

	SGraphBlock *pGraphBlockTo = pDoc->m_pDrop->pGraphBlock;
// Modify ... ( CHANGE )
	int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
	SGraphBlock *pGraphBlockFrom = m_aZahyoDrag[nIndex].pGraphBlock;
//	SGraphBlock *pGraphBlockFrom = m_aZahyoDrag[pDoc->m_aSelectedBox[0]].pGraphBlock;
// By Y.Itabashi (xxxxx) 2007.02.27
	int nSublineIdFrom = pGraphBlockFrom->nSublineId;
	int nSublineIdTo = pGraphBlockTo->nSublineId;

	// ライン規制ファイルが開いていたらエラー
	if (g_pDataManager->m_bOpenLineKiseiMatrix) {
		g_pSystem->DispMessage("E006002");
		return;
	}

	// 型式規制ファイルが開いていたらエラー
	if (g_pDataManager->m_bOpenKatashikiKiseiMatrix) {
		g_pSystem->DispMessage("E006102");
		return;
	}

	// 機種別表示で共通工程が含まれていたらエラー
// Modify ... ( CHANGE )
	int nSelectSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	if (pWnd->m_nGraphKishu != -1) {
//	if (pDoc->m_nGraphKishu != -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
		for (int i = 0; i < nSelectSize; i++) {
// Modify ... ( CHANGE )
			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
			SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
//			SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[i]];
// By Y.Itabashi (xxxxx) 2007.02.27
			if (cZahyoDrag.pGraphBlock->bKyotsuKotei) {
				g_pSystem->DispMessage("E013001");
				goto ERR;	// Modify... ( CHANGE )
			}
		}
	}

	// 同一工程チェック
// Modify ... ( CHANGE )		同一工程チェックを関数化
	for (i = 0; i < nSelectSize; i++) {
// Modify ... ( CHANGE )
		int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		if( !pDoc->CheckSameKoteiMove( m_aZahyoDrag[nIndex].pGraphBlock, pGraphBlockTo, pDoc->m_pDrop->bLastBlock ) ){
//		if( !pDoc->CheckSameKoteiMove( m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock, pGraphBlockTo, pDoc->m_pDrop->bLastBlock ) ){
// By Y.Itabashi (xxxxx) 2007.02.27
				goto ERR;	// Modify... ( CHANGE )
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.10

	// 最新移動フラグクリア
	g_pDataManager->ClearNewMoveFlag();

// Modify ... ( CHANGE )
	nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	bSetGraph = TRUE;
	nSetIndex = -1;
	while( bSetGraph ){

		bSetGraph = FALSE;

		int nIndexMin = m_aZahyoDrag.GetSize();
		for (i = 0; i < nSize; i++){

			if( pWnd->m_nGraphKishu != pDoc->m_aSelectYosoSagyogun[i].nKishuId &&
				pWnd->m_nGraphKishu != -1 )
				continue;

			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
			if( nSetIndex < nIndex ){
				nIndexMin = ( nIndex < nIndexMin ) ? nIndex : nIndexMin;
				bSetGraph = TRUE;
			}
		}
		if( bSetGraph ){
			aGraphBlockFrom.Add(m_aZahyoDrag[nIndexMin].pGraphBlock);
			nSetIndex = nIndexMin;
		}
	}
//	int nSize = pDoc->m_aSelectedBox.GetSize();
//	for (i = 0; i < nSize; i++)
//		aGraphBlockFrom.Add(m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock);
// By Y.Itabashi (xxxxx) 2007.03.07

// Modify ... ( ADD )
	// 移動前の位置を設定
	for( nKishu = 0; nKishu < g_pDataManager->GetKishuNum(); nKishu++ ){
		CString sCheckKoteiName;
		CString sCheckYosoSagyogunName;
		int nCheckYosoSagyogunId = -1;

		for( i = 0; i < aGraphBlockFrom.GetSize(); i++ ){
			SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];

			if( pGraphBlockFrom->nKishuId != nKishu )
				continue;

			if( sCheckKoteiName.IsEmpty() ){
				sCheckKoteiName = pGraphBlockFrom->sKoteiName;
				sCheckYosoSagyogunName = pGraphBlockFrom->sYosoSagyogunName;
				nCheckYosoSagyogunId = pGraphBlockFrom->aYosoSagyogunId[0];
			}

			g_pDataManager->SetMoveBeforeSagyojun(nSublineIdFrom, nSublineIdTo,
				pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
				sCheckKoteiName, sCheckYosoSagyogunName, nCheckYosoSagyogunId,
				pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
				pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
		}
	}
/*	for( i = 0; i < aGraphBlockFrom.GetSize(); i++ ){
		SGraphBlock *pGraphBlockFrom = aGraphBlockFrom[i];
		g_pDataManager->SetMoveBeforeSagyojun(nSublineIdFrom,
			pGraphBlockFrom->sKoteiName, pGraphBlockFrom->sYosoSagyogunName,
			pGraphBlockFrom->aYosoSagyogunId, pGraphBlockFrom->nKishuId, 
			pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun );
	}*/
// By Y.Itabashi (xxxxx) 2007.02.28

	pDoc->MoveItem(nSublineIdFrom, aGraphBlockFrom, nSublineIdTo, pDoc->m_pDrop->pGraphBlock, pDoc->m_pDrop->nSagyoshaId, pDoc->m_pDrop->bLastBlock);

	// 工順規制チェック
// Modify ... ( CHANGE )
	if (!g_pDataManager->CheckKojunKisei(nSublineIdFrom, nSublineIdTo, pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun)) {
//	if (!g_pDataManager->CheckKojunKisei(nSublineIdTo, pDoc->m_nGraphKind == GRAPH_KOTEI ? eKojunCheckTypeKotei : eKojunCheckTypeYosoSagyogun)) {
// By Y.Itabashi (xxxxx) 2007.02.07
		g_pDataManager->RestoreUndoData();
		goto ERR;	// Modify... ( CHANGE )
	}

// Modify ... ( ADD )
	g_pDataManager->InitMoveBeforeSagyojun();
// By Y.Itabashi (xxxxx) 2007.02.06

	// 目標タクト上限チェック
// Modify ... ( CHANGE )
	nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	for (i = 0; i < nSelectSize; i++) {
		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu &&
			nGraphKishu != -1 )
//		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu )
			continue;

		int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
		if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineIdTo, pDoc->m_pDrop->nSagyoshaId, cZahyoDrag.pGraphBlock->nKishuId, pWnd->m_nGraphKishu)) {
//		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[i]];
//		if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineIdTo, pDoc->m_pDrop->nSagyoshaId, cZahyoDrag.pGraphBlock->nKishuId)) {
// By Y.Itabashi (xxxxx) 2007.03.07
			if(g_pSystem->DispMessage("W013002") == IDCANCEL) {
				g_pDataManager->RestoreUndoData();
				goto ERR;	// Modify... ( CHANGE )
			} else
				break;
		}
	}

// --Add 2007/03/02 aizawa 不懸D-147
	// サブライン間移動の場合、ライン規制マトリクスのサブラインを更新する
	if (nSublineIdFrom != nSublineIdTo)
		g_pDataManager->UpdateLineKiseiSubline(nSublineIdFrom, nSublineIdTo);
// --Add

	// データ更新フラグ設定
	g_pDataManager->SetUpdate();

	// 選択リセット
	ResetSelectedBox();

	// 画面更新
	GetDocument()->UpdateGraph(FALSE);

	// 現在のデータをUndoバッファに保存
	g_pDataManager->SaveUndoBuf();

// Modify ... ( ADD )
	return;

ERR:
	pDoc->m_aSelectYosoSagyogun.RemoveAll();
	for( i = 0; i < m_aSelectYosoSagyogunBack.GetSize(); i++ )
		pDoc->m_aSelectYosoSagyogun.Add( m_aSelectYosoSagyogunBack[i] );

	pDoc->m_aRefYosoSagyogun.RemoveAll();
	for( i = 0; i < m_aRefYosoSagyogunBack.GetSize(); i++ )
		pDoc->m_aRefYosoSagyogun.Add( m_aRefYosoSagyogunBack[i] );
// By Y.Itabashi (xxxxx) 2007.03.07
}

// キット化解除処理
void CManualEditView1::CancelKitting()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
// --Add 2007/03/05 aizawa 不懸D-199
	int nSublineId;
// --Add

	// キット化解除フラグクリア
	g_pDataManager->ClearKittingCancelFlag();

	// キット化データ取得
	g_pDataManager->GetKittingData();

	// 選択されている部品のキット化を解除
// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	for (int i = 0; i < nSize; i++) {
		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != nGraphKishu &&
			nGraphKishu != -1 )
			continue;

		int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		SGraphBlock *pGraphBlock = m_aZahyoDrag[nIndex].pGraphBlock;
		g_pDataManager->CancelKitting(pGraphBlock->nSublineId, pGraphBlock->sKoteiName);
	}
/*	int nSize = pDoc->m_aSelectedBox.GetSize();
	for (int i = 0; i < nSize; i++) {
		SGraphBlock *pGraphBlock = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock;
		g_pDataManager->CancelKitting(pGraphBlock->nSublineId, pGraphBlock->sKoteiName);
	}*/
// By Y.Itabashi (xxxxx) 2007.03.06

// --Add 2007/03/05 aizawa 不懸D-199
	// タクトオーバをチェックする
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		int nYosoSagyogunNum = pSubline->aYosoSagyogun.GetSize();
		for (int nYosoSagyogunId = 0; nYosoSagyogunId < nYosoSagyogunNum; nYosoSagyogunId++) {
			SYosoSagyogun &cYosoSagyogun = pSubline->aYosoSagyogun[nYosoSagyogunId];
			// キット化解除により作業が復活した要素作業群をチェック
			if (cYosoSagyogun.bKittingCancel) {
				if (pWnd->m_nGraphKishu == -1) {
					// 加重平均表示
					int nKishuSagyoNum = cYosoSagyogun.aKishuSagyo.GetSize();
					for (int nKishuId = 0; nKishuId < nKishuSagyoNum; nKishuId++) {
						SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[nKishuId];
						if (cKishuSagyo.nSagyoshaId != -1) {
							// 目標タクト上限チェック
							if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, cKishuSagyo.nSagyoshaId, nKishuId, pWnd->m_nGraphKishu)) {
								if(g_pSystem->DispMessage("W013002") == IDCANCEL) {
									g_pDataManager->RestoreUndoData();
									return;
								} else
									goto tact_check_ok;
							}
						}
					}
				} else {
					// 機種別表示
					SKishuSagyo &cKishuSagyo = cYosoSagyogun.aKishuSagyo[pWnd->m_nGraphKishu];
					if (cKishuSagyo.nSagyoshaId != -1) {
						// 目標タクト上限チェック
						if (!g_pDataManager->CheckMokuhyoTactJoukagen(nSublineId, cKishuSagyo.nSagyoshaId, pWnd->m_nGraphKishu, pWnd->m_nGraphKishu)) {
							if(g_pSystem->DispMessage("W013002") == IDCANCEL) {
								g_pDataManager->RestoreUndoData();
								return;
							} else
								goto tact_check_ok;
						}
					}
				}
			}
		}
	}
tact_check_ok:
// --Add

	// キット化部品情報保存
	g_pDataManager->SetBuhinKitting();

	// キット化作業自動編成
	CIntArrayEx aSublineId;
// --Delete 2007/03/05 aizawa 不懸D-199
//	int nSublineNum = g_pDataManager->GetSublineNum();
// --Delete
// --Change 2007/03/05 aizawa 不懸D-199
//	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
	for (nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
// --Change
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		if (pSubline->bSagyoshaWaritsuke)
			aSublineId.Add(nSublineId);
	}
	g_pDataManager->JidoHensei1Kitting(aSublineId);

// Modify ... ( ADD )
	// 移動フラグクリアー
	g_pDataManager->ClearNewMoveFlag();
// By Y.Itabashi (xxxxx) 2007.03.05

	// 選択リセット
	ResetSelectedBox();

	// データ更新フラグ設定
	g_pDataManager->SetUpdate();

	// 画面更新
	GetDocument()->UpdateGraph(TRUE);

	// 現在のデータをUndoバッファに保存
	g_pDataManager->SaveUndoBuf();
}

// スケールパラメータ設定
void CManualEditView1::SetScaleParamaeter()
{
	m_nLeftMargin = 40;
	m_nTopMargin = 80;
	m_nRightMargin = 20;
	m_nBottomMargin = 40;
	m_nBarWidth = 80;
	m_nLeftSpacing = 20;
	m_nBarSpacing = 30;
}

void CManualEditView1::DrawFocusRect(CRect rect)
{
	if (rect.TopLeft() != rect.BottomRight()) {
		CDC *pDC = GetDC();

		rect.NormalizeRect();
		pDC->DrawFocusRect(rect);

		ReleaseDC(pDC);
	}
}

void CManualEditView1::BlinkSelectedBox(BOOL bBlink)
{
	CManualEditDocument *pDoc = GetDocument();
// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSelectedBox = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSelectedBox = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27
	if (nSelectedBox == 0)
		return;

	int i, j;
	CArray <CRect, CRect&> aBlinkRect;
	CPoint point = GetScrollPosition();

// Modify ... ( ADD )
	if( nGraphKishu == -1 ){
		int nCheckIndex = -1;
		while( 1 ){
			int nMinIndex = -1;
			for (i = 0; i < nSelectedBox; i++) {

				int nIndex = pDoc->m_aSelectYosoSagyogun[i].nIndex;

				if( nCheckIndex < nIndex && nMinIndex == -1 )
					nMinIndex = nIndex;

				if( nCheckIndex >= nIndex )
					continue;

				nMinIndex = ( nMinIndex > nIndex ) ? nIndex : nMinIndex;
			}

			if( nCheckIndex == nMinIndex || nMinIndex == -1 )
				break;

			nCheckIndex = nMinIndex;
			CRect rectSelectedBox = m_aZahyoDrag[nMinIndex].rect - point;

			int nBlinkRect = aBlinkRect.GetSize();
			for (j = 0; j < nBlinkRect; j++) {
				CRect &rectBlink = aBlinkRect[j];

				if (rectSelectedBox.left == rectBlink.left) {
					if (rectSelectedBox.bottom == rectBlink.top) {
						rectBlink.top = rectSelectedBox.top;
						break;
					}

					if (rectSelectedBox.top == rectBlink.bottom) {
						 rectBlink.bottom = rectSelectedBox.bottom;
						break;
					}
				}
			}

			if (j == nBlinkRect)
				aBlinkRect.Add(rectSelectedBox);
		}
	}else{
// By Y.Itabashi (xxxxx) 2007.02.28
	for (i = 0; i < nSelectedBox; i++) {

// Modify ... ( CHANGE )
		if( nGraphKishu != pDoc->m_aSelectYosoSagyogun[i].nKishuId )
			continue;

		int nIndex = pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		CRect rectSelectedBox = m_aZahyoDrag[nIndex].rect - point;
//		CRect rectSelectedBox = m_aZahyoDrag[pDoc->m_aSelectedBox[i]].rect - point;
// By Y.Itabashi (xxxxx) 2007.02.20

		int nBlinkRect = aBlinkRect.GetSize();
		for (j = 0; j < nBlinkRect; j++) {
			CRect &rectBlink = aBlinkRect[j];

			if (rectSelectedBox.left == rectBlink.left) {
				if (rectSelectedBox.bottom == rectBlink.top) {
					rectBlink.top = rectSelectedBox.top;
					break;
				}

				if (rectSelectedBox.top == rectBlink.bottom) {
					 rectBlink.bottom = rectSelectedBox.bottom;
					break;
				}
			}
		}

		if (j == nBlinkRect)
			aBlinkRect.Add(rectSelectedBox);
	}
// Modify ... ( ADD )
	}
// By Y.Itabashi (xxxxx) 2007.02.28

	if (bBlink) {
		CDC *pDC = GetDC();
		CPen *pPenOrg = pDC->SelectObject(&m_penSelected);
		CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

		for (j = 0; j < aBlinkRect.GetSize(); j++) {
			CRect rect(aBlinkRect[j]);
			rect.right++;
			rect.bottom++;
			pDC->Rectangle(rect);
		}

		pDC->SelectObject(pPenOrg);
		pDC->SelectObject(pBrushOrg);

		ReleaseDC(pDC);
	} else {
		for (j = 0; j < aBlinkRect.GetSize(); j++) {
			CRect rect(aBlinkRect[j]);
			rect.InflateRect(1, 1);
			InvalidateRect(rect, FALSE);
		}
	}
}

void CManualEditView1::EraseSelectedBox()
{
	CManualEditDocument *pDoc = GetDocument();
	pDoc->UpdateAllViews(NULL, UPDATE_SELECT_BLINK, (CObject *)FALSE);
}

void CManualEditView1::ResetSelectedBox()
{
	CManualEditDocument *pDoc = GetDocument();

	EraseSelectedBox();
// Modify ... ( CHANGE )
	pDoc->m_aSelectYosoSagyogun.RemoveAll();
	pDoc->InitReferenceYosoSagyogun();
	this->Invalidate();
//	pDoc->m_aSelectedBox.RemoveAll();
// By Y.Itabashi (xxxxx) 2007.03.04
}

void CManualEditView1::SelectRect()
{
	CManualEditDocument *pDoc = GetDocument();
	CRect rectSelect = m_rectSelect;
	rectSelect.NormalizeRect();
	rectSelect += GetScrollPosition();
// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.22

	// 座標配列の中から指定された位置を含む四角形を検索
	int nLeft = 0;
	int nSize = m_aZahyoDrag.GetSize();
	for (int i = 0; i < nSize; i++) {
		CRect &rect = m_aZahyoDrag[i].rect;
		if (rectSelect.PtInRect(rect.TopLeft()) && rectSelect.PtInRect(rect.BottomRight())) {
			if (nLeft == 0 || nLeft == rect.left) {
				AddSelect(i);

// Modify ... ( CHANGE )
//				if( pDoc->m_aSelectedBox.GetSize() == 0 )
				if( pDoc->m_aSelectYosoSagyogun.GetSize() == 0 )
					continue;

//				if (!m_aZahyoDrag[pDoc->m_aSelectedBox[0]].pGraphBlock->bKitting)
				int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
				if (!m_aZahyoDrag[nIndex].pGraphBlock->bKitting)
					nLeft = rect.left;
// By Y.Itabashi (xxxxx) 2007.02.22
			}
		}
	}

// Modify ... ( ADD )
	pDoc->AddSelectKoteiOtherWindow();
// By Y.Itabashi (xxxxx) 2007.02.27
}

// 工程／要素作業群選択追加
void CManualEditView1::AddSelect(int nIndex)
{
	CManualEditDocument *pDoc = GetDocument();
	SZahyoDrag &cAddZahyoDrag = m_aZahyoDrag[nIndex];

// Modify ... ( ADD )
	// UP4用モジュール
//	if( cAddZahyoDrag.pGraphBlock->bKitting )
//		return;
// By Y.Itabashi (xxxxx) 2007.02.22

	if (cAddZahyoDrag.pGraphBlock->bKitting != pDoc->m_bSelectKitting) {
		ResetSelectedBox();
		pDoc->m_bSelectKitting = cAddZahyoDrag.pGraphBlock->bKitting;
	}

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	pDoc->AddSelectKotei( cAddZahyoDrag, nGraphKishu );
//	pDoc->m_aSelectedBox.Add(nIndex);
// By Y.Itabashi (xxxxx) 2007.02.27

	// 共通工程なら同じグループ番号の工程／要素作業群も選択する
	if (cAddZahyoDrag.pGraphBlock->bKyotsuKotei) {
		int nSize = m_aZahyoDrag.GetSize();
		for (int i = 0; i < nSize; i++) {
			SZahyoDrag &cZahyoDrag = m_aZahyoDrag[i];
			
// Modify ... ( CHANGE )
			if (cZahyoDrag.rect.left == cAddZahyoDrag.rect.left && cZahyoDrag.pGraphBlock->nGroupNo == cAddZahyoDrag.pGraphBlock->nGroupNo){
				pDoc->AddSelectKotei( cZahyoDrag, nGraphKishu );
//				pDoc->m_aSelectedBox.Add(i);
// Modify ... ( DELETE )
				// 直後が自動工程ならそれも選択する
/*				for (int j = i + 1; j < nSize; j++) {
					SZahyoDrag &cZahyoDrag = m_aZahyoDrag[j];
					if (cZahyoDrag.nSagyoshaId == cAddZahyoDrag.nSagyoshaId && cZahyoDrag.pGraphBlock->sJido == g_pConst->m_sJido){
						pDoc->AddSelectKotei( cZahyoDrag, nGraphKishu );
					}
					else
						break;
				}*/
// By Y.Itabashi (xxxxx) 2007.03.07
			}
// By Y.Itabashi (xxxxx) 2007.02.27
		}
	}

// Modify ... ( DELETE )
	// 直後が自動工程ならそれも選択する
/*	int nSize = m_aZahyoDrag.GetSize();
	for (int i = nIndex + 1; i < nSize; i++) {
		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[i];
// Modify ... ( CHANGE )
		if (cZahyoDrag.nSagyoshaId == cAddZahyoDrag.nSagyoshaId && cZahyoDrag.pGraphBlock->sJido == g_pConst->m_sJido){
			pDoc->AddSelectKotei( cZahyoDrag, nGraphKishu );
//			pDoc->m_aSelectedBox.Add(i);
		}
// By Y.Itabashi (xxxxx) 2007.02.27
		else
			break;
	}*/
// By Y.Itabashi (xxxxx) 2007.03.07

// Modify ... ( DELETE )
//	pDoc->m_aSelectedBox.Unique();
//	pDoc->m_aSelectedBox.Sort();
// By Y.Itabashi (xxxxx) 2007.02.27
}

// 工程／要素作業群選択解除
void CManualEditView1::RemoveSelect(int nIndex)
{
	CManualEditDocument *pDoc = GetDocument();
	SZahyoDrag &cAddZahyoDrag = m_aZahyoDrag[nIndex];
// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.22

	EraseSelectedBox();

	// 共通工程なら同じグループ番号の工程／要素作業群も解除する
// Modify ... ( CHANGE )
	for (int i = 0; i < pDoc->m_aSelectYosoSagyogun.GetSize(); ) {
//	for (int i = 0; i < pDoc->m_aSelectedBox.GetSize(); ) {
		int nCheckIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nCheckIndex];
//		SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[i]];
		
		if ( ( ( nGraphKishu == pDoc->m_aSelectYosoSagyogun[i].nKishuId || nGraphKishu == -1 ) &&
			   cZahyoDrag.nIndex == nIndex ) ||
			 ( pDoc->m_aSelectYosoSagyogun[i].bKyotsuKotei &&
			 pDoc->m_aSelectYosoSagyogun[i].nGroupNo == cAddZahyoDrag.pGraphBlock->nGroupNo) ){
//		if (cZahyoDrag.nIndex == nIndex || (cZahyoDrag.pGraphBlock->bKyotsuKotei && cZahyoDrag.rect.left == cAddZahyoDrag.rect.left && cZahyoDrag.pGraphBlock->nGroupNo == cAddZahyoDrag.pGraphBlock->nGroupNo)){
			pDoc->m_aSelectYosoSagyogun.RemoveAt(i);
			pDoc->CancelReferenceYosoSagyogun( cAddZahyoDrag.pGraphBlock->sKoteiName,
				cAddZahyoDrag.pGraphBlock->sYosoSagyogunName );
//			pDoc->m_aSelectedBox.RemoveAt(i);
		}else if( cAddZahyoDrag.pGraphBlock->bKitting &&
			pDoc->m_aSelectYosoSagyogun[i].sKoteiName == cAddZahyoDrag.pGraphBlock->sKoteiName &&
			pDoc->m_aSelectYosoSagyogun[i].sYosoSagyogunName == cAddZahyoDrag.pGraphBlock->sYosoSagyogunName ){
			pDoc->m_aSelectYosoSagyogun.RemoveAt(i);
		}else
			i++;
	}

	pDoc->UpdateAllViews(NULL, UPDATE_EDIT_VIEW);
// By Y.Itabashi (xxxxx) 2007.03.06
}

// 選択されている全ての箱を囲む枠を求める
CRect CManualEditView1::GetSelectRect()
{
	CManualEditDocument *pDoc = GetDocument();
	CRect rect(0);

// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	rect.SetRectEmpty();
// By Y.Itabashi (xxxxx) 2007.02.21

// Modify ... ( CHANGE )
	int m_nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
	for (int i = 0; i < nSize; i++){
		if( pDoc->m_aSelectYosoSagyogun[i].nKishuId != m_nGraphKishu &&
			m_nGraphKishu != -1 )
			continue;

		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		rect.UnionRect(rect, m_aZahyoDrag[nIndex].rect);
	}
//	int nSize = pDoc->m_aSelectedBox.GetSize();
//	for (int i = 0; i < nSize; i++)
//		rect.UnionRect(rect, m_aZahyoDrag[pDoc->m_aSelectedBox[i]].rect);
// By Y.Itabashi (xxxxx) 2007.02.27

	return rect;
}

// 指定した位置の工程／要素作業群が選択されているかチェック
BOOL CManualEditView1::CheckSelect(SGraphBlock *pGraphBlock)
{
	CManualEditDocument *pDoc = GetDocument();
// Modify ... ( ADD )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.22

// Modify ... ( CHANGE )
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSize = pDoc->m_aSelectedBox.GetSize();
	for (int i = 0; i < nSize; i++) {
		int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[i].nIndex : pDoc->m_aSelectYosoSagyogun[i].nIndexKishu;
		if (m_aZahyoDrag[nIndex].pGraphBlock == pGraphBlock)
//		if (m_aZahyoDrag[pDoc->m_aSelectedBox[i]].pGraphBlock == pGraphBlock)
			return TRUE;
	}
// By Y.Itabashi (xxxxx) 2007.02.27

	return FALSE;
}

void CManualEditView1::OnChar(UINT nChar, UINT nRepCnt, UINT nFlags)
{
	if (nChar == VK_ESCAPE) {
		ResetSelectedBox();
		SelectSagyosha(0);
	}

	CManualEditView::OnChar(nChar, nRepCnt, nFlags);
}

void CManualEditView1::OnLButtonDblClk(UINT nFlags, CPoint point)
{
	CSplitterEx *pWnd = (CSplitterEx *)GetParent();
	pWnd->ChangeSplit(point);

//	CManualEditView::OnLButtonDblClk(nFlags, point);
}

void CManualEditView1::OnActivateView(BOOL bActivate, CView* pActivateView, CView* pDeactiveView)
{
	CSplitterEx *pWnd = (CSplitterEx *)GetParent();
	pWnd->RecalcLayout();

	CManualEditView::OnActivateView(bActivate, pActivateView, pDeactiveView);
}

// 作業者を選択
void CManualEditView1::SelectSagyosha(CPoint point)
{
	CManualEditDocument *pDoc = GetDocument();
	CPoint pointScroll = GetScrollPosition();
	point += pointScroll;
	SSelectSagyosha cSelectSagyosha = pDoc->m_cSelectSagyosha;

	int nSize = m_aZahyoSagyosha.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSagyosha &cZahyoSagyosha = m_aZahyoSagyosha[i];
		if (cZahyoSagyosha.nSublineId != -1 && cZahyoSagyosha.rect.PtInRect(point)) {
			if (cSelectSagyosha.bSelect == TRUE && cSelectSagyosha.nSubnlineId == cZahyoSagyosha.nSublineId && cSelectSagyosha.nSagyoshaId == cZahyoSagyosha.nSagyoshaId)
				pDoc->m_cSelectSagyosha.bSelect = FALSE;
			else {
				pDoc->m_cSelectSagyosha.bSelect = TRUE;
				pDoc->m_cSelectSagyosha.nSubnlineId = cZahyoSagyosha.nSublineId;
				pDoc->m_cSelectSagyosha.nSagyoshaId = cZahyoSagyosha.nSagyoshaId;
			}
			break;
		}
	}

	if (i == nSize)
		pDoc->m_cSelectSagyosha.bSelect = FALSE;

	if (pDoc->m_cSelectSagyosha.bSelect != cSelectSagyosha.bSelect || pDoc->m_cSelectSagyosha.nSubnlineId != cSelectSagyosha.nSubnlineId || pDoc->m_cSelectSagyosha.nSagyoshaId != cSelectSagyosha.nSagyoshaId)
		pDoc->UpdateAllViews(NULL, UPDATE_EDIT_VIEW);
}

// 作業者の新規挿入（前に挿入）
void CManualEditView1::OnManualSagyoshaMae() 
{
	CManualEditDocument *pDoc = GetDocument();

	// 作業者が選択されているかチェック
	if (!pDoc->m_cSelectSagyosha.bSelect) {
		g_pSystem->DispMessage("E022001");
		return;
	}

	// 作業者挿入処理
	g_pDataManager->InsertSagyosha(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId);

	// 再表示
	GetDocument()->UpdateGraph(FALSE);

	// 現在のデータをUndoバッファに保存
	g_pDataManager->SaveUndoBuf();
}

// 作業者の新規挿入（後ろに挿入）
void CManualEditView1::OnManualSagyoshaUshiro() 
{
	CManualEditDocument *pDoc = GetDocument();

	// 作業者が選択されているかチェック
	if (!pDoc->m_cSelectSagyosha.bSelect) {
		g_pSystem->DispMessage("E022001");
		return;
	}

	// 作業者挿入処理
	g_pDataManager->InsertSagyosha(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId + 1);

	// 再表示
	GetDocument()->UpdateGraph(FALSE);

	// 現在のデータをUndoバッファに保存
	g_pDataManager->SaveUndoBuf();
}

// 作業者の削除
void CManualEditView1::OnManualSagyoshaSakujo() 
{
	CManualEditDocument *pDoc = GetDocument();

	// 作業者が選択されているかチェック
	if (!pDoc->m_cSelectSagyosha.bSelect) {
		g_pSystem->DispMessage("E023001");
		return;
	}

	if (g_pDataManager->CheckSagyoshaSagyo(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId)) {
		g_pSystem->DispMessage("E023002");
		return;
	}

	// 作業者削除処理
	g_pDataManager->DeleteSagyosha(pDoc->m_cSelectSagyosha.nSubnlineId, pDoc->m_cSelectSagyosha.nSagyoshaId);

	// 再表示
	GetDocument()->UpdateGraph(FALSE);

	// 現在のデータをUndoバッファに保存
	g_pDataManager->SaveUndoBuf();
}

// 工程／要素作業群のコピー
void CManualEditView1::OnManualCopy() 
{
	CManualEditDocument *pDoc = GetDocument();

// Modify ... ( CHANGE )
	if (pDoc->m_aSelectYosoSagyogun.GetSize() == 0) {
//	if (pDoc->m_aSelectedBox.GetSize() == 0) {
// By Y.Itabashi (xxxxx) 2007.02.27
		g_pSystem->DispMessage("E019001");
		return;
	}

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nIndex = ( nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[0].nIndex : pDoc->m_aSelectYosoSagyogun[0].nIndexKishu;
	SGraphBlock *pGraphBlock = m_aZahyoDrag[nIndex].pGraphBlock;
//	SGraphBlock *pGraphBlock = m_aZahyoDrag[pDoc->m_aSelectedBox[0]].pGraphBlock;
// By Y.Itabashi (xxxxx) 2007.02.27
	g_pDataManager->CopyClipboard(pGraphBlock->nSublineId, pGraphBlock->aYosoSagyogunId, pDoc->m_nGraphKind == GRAPH_KOTEI ? "1" : "2");
}

// 工程／要素作業群の貼り付け
void CManualEditView1::OnManualPaste() 
{
	CManualEditDocument *pDoc = GetDocument();

// Modify ... ( CHANGE )
	int nSize = pDoc->m_aSelectYosoSagyogun.GetSize();
//	int nSize = pDoc->m_aSelectedBox.GetSize();
// By Y.Itabashi (xxxxx) 2007.02.27
	if (nSize == 0 || pDoc->m_bSelectKitting) {
		g_pSystem->DispMessage("E020001");
		return;
	}

// Modify ... ( CHANGE )
	int nGraphKishu = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
	int nIndex = 0;

	if( nGraphKishu == -1 )
		return;

	for( int nSelect = pDoc->m_aSelectYosoSagyogun.GetSize()-1; nSelect >= 0; nSelect-- ){

		if( nGraphKishu == pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId ){
			if( nIndex < pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu ){
				nIndex = pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu;
				break;
			}
		}

	}
	SZahyoDrag &cZahyoDrag = m_aZahyoDrag[nIndex];
//	SZahyoDrag &cZahyoDrag = m_aZahyoDrag[pDoc->m_aSelectedBox[nSize - 1]];
// By Y.Itabashi (xxxxx) 2007.03.04
	SGraphBlock *pGraphBlock = cZahyoDrag.pGraphBlock;
	if (!g_pDataManager->PasteClipboard(pGraphBlock->nSublineId, pGraphBlock->aYosoSagyogunId[pGraphBlock->aYosoSagyogunId.GetSize() - 1], pGraphBlock->nKishuId, cZahyoDrag.nSagyoshaId)) {
		g_pSystem->DispMessage("E020002");
		return;
	}

	// グラフを更新
	GetDocument()->UpdateGraph(FALSE);

	// 現在のデータをUndoバッファに保存
	g_pDataManager->SaveUndoBuf();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView1::OnManualDstr
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		DSTR算出
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		DSTR算出ダイアログを開く。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2007.02.09 Y.Itabashi 新規作成
//*****************************************************************************************************
void CManualEditView1::OnManualDstr() 
{
	CManualEditDocument *pDoc = GetDocument();
	CManualDstrDlg dlg(this);

// Modify ... ( CHANGE )
	dlg.m_nKishuId = ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu;
//	dlg.m_nKishuId = pDoc->m_nGraphKishu;
// By Y.Itabashi (xxxxx) 2007.02.26

	dlg.m_pDoc = pDoc;

	dlg.DoModal();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::AddZahyoDrag
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		作業座標追加処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CRect		&rect				[I] 四角形の座標
//		SGraphBlock	*pGraphBlock		[I] グラフブロックデータ
//		CString		&sDispText			[I] 四角内に表示するテキスト
//		int			nKishuId			[I] 機種ID
//		int			nSagyoshaId			[I] 作業者ID
//		int			nKind				[I] グラフ種類（工程 or 要素作業群）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		m_aZahyoDragに指定された座標データを追加する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView1::AddZahyoDrag(const CRect &rect, SGraphBlock *pGraphBlock, int nSagyoshaId)
{
	int nIndex;
	SZahyoDrag *pZahyoDrag = m_aZahyoDrag.AddElement(nIndex);

	pZahyoDrag->rect = rect;
	pZahyoDrag->pGraphBlock = pGraphBlock;
	pZahyoDrag->nSagyoshaId = nSagyoshaId;
	pZahyoDrag->nIndex = nIndex;
}

void CManualEditView1::AddZahyoDrop(const CRect &rect, SGraphBlock *pGraphBlock, int nSagyoshaId, CPoint pointTriangle, BOOL bLastBlock)
{
// Modify ... ( ADD )
	if( !pGraphBlock )
		return;
// By Y.Itabashi (xxxxx) 2007.02.14

	if (!pGraphBlock->bKitting) {
		int nIndex;
		SZahyoDrop *pZahyoDrop = m_aZahyoDrop.AddElement(nIndex);

		pZahyoDrop->rect = rect;
		pZahyoDrop->pGraphBlock = pGraphBlock;
		pZahyoDrop->nSagyoshaId = nSagyoshaId;
		pZahyoDrop->nIndex = nIndex;
		pZahyoDrop->pointTriangle = pointTriangle;
		pZahyoDrop->bLastBlock = bLastBlock;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::CheckZahyoGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定された座標位置のポインタを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CPoint		point			[I] 座標
//		CRect		&rect			[O] 四角形の座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		SZahyoGraph*		座標配列のポインタ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		m_aZahyoGraphの中に指定された座標を含む四角形があるか検索し、あればそのポインタを返す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
SZahyoDrag *CManualEditView1::CheckZahyoDrag(CPoint point, CRect &rect)
{
	CPoint pointScroll = GetScrollPosition();

	// 座標配列の中から指定された位置を含む四角形を検索
	int nSize = m_aZahyoDrag.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoDrag *pZahyoDrag = &m_aZahyoDrag[i];
		rect = pZahyoDrag->rect - pointScroll;
		if (rect.PtInRect(point))
			return pZahyoDrag;
	}

	return NULL;
}

SZahyoDrop *CManualEditView1::CheckZahyoDrop(CPoint point, CRect &rect)
{
	CPoint pointScroll = GetScrollPosition();

	// 座標配列の中から指定された位置を含む四角形を検索
	int nSize = m_aZahyoDrop.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoDrop *pZahyoDrop = &m_aZahyoDrop[i];
		rect = pZahyoDrop->rect - pointScroll;
		if (rect.PtInRect(point))
			return pZahyoDrop;
	}

	return NULL;
}

// 共通工程の選択追加（非アクティブ画面）
void CManualEditView1::AddSelectKyotsuKotei()
{
	CManualEditDocument *pDoc = GetDocument();

	if( GetParentFrame() == pDoc->m_pSelectWnd )
		return;

	if( pDoc->m_bSelectKitting )
		return;

	BOOL bDispSagyosha = FALSE;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( !pDoc->m_aSelectYosoSagyogun[nSelect].bKyotsuKotei )
			continue;

		int nGroupNo = pDoc->m_aSelectYosoSagyogun[nSelect].nGroupNo;
		int nSubline = pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId;

// Modify ... ( CHANGE )
		for( int nSelectSubline = 0; nSelectSubline < pDoc->m_aGraphSubline.GetSize(); nSelectSubline++ ){
			if( pDoc->m_aGraphSubline[nSelectSubline].nSublineId == pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId )
				break;
		}
		if( nSelectSubline == pDoc->m_aGraphSubline.GetSize() )
			continue;
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSelectSubline];
//		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId];
// By Y.Itabashi (xxxxx) 2007.03.06
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[pDoc->m_aSelectYosoSagyogun[nSelect].nSagyoshaId];

		CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
		for( int nBlock = 0; nBlock < aGraphBlock.GetSize(); nBlock++ ){

			if( aGraphBlock[nBlock].nGroupNo == nGroupNo ){

				for( int nZahyoDrag = 0; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

					if( m_aZahyoDrag[nZahyoDrag].pGraphBlock == &aGraphBlock[nBlock] ){
						pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );

						if( !bDispSagyosha ){
							DispCenterSagyosha( m_aZahyoDrag[nZahyoDrag].pGraphBlock->nSublineId, m_aZahyoDrag[nZahyoDrag].nSagyoshaId );
							bDispSagyosha = TRUE;
						}
					}
				}

			}
		}
	}
}

// 参照工程の選択追加（非アクティブ画面）
void CManualEditView1::AddReferenceKotei()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();

	if( GetParentFrame() == pDoc->m_pSelectWnd )
		return;

	if( pDoc->m_bSelectKitting )
		return;

	BOOL bDispSagyosha = FALSE;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId == pWnd->m_nGraphKishu )
			continue;

		CString sKoteiName = g_pDataManager->RemoveUnderbarDigit( pDoc->m_aSelectYosoSagyogun[nSelect].sKoteiName );
		CString sYosoSagyogunName = g_pDataManager->RemoveUnderbarDigit( pDoc->m_aSelectYosoSagyogun[nSelect].sYosoSagyogunName );

		for( int nBlock = 0; nBlock < m_aZahyoDrag.GetSize(); nBlock++ ){

			if( m_aZahyoDrag[nBlock].pGraphBlock->nKishuId == pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId )
				continue;

			if( m_aZahyoDrag[nBlock].pGraphBlock->nSublineId != pDoc->m_aSelectYosoSagyogun[nSelect].nSublineId )
				continue;

			CString sCheckKoteiName = g_pDataManager->RemoveUnderbarDigit( m_aZahyoDrag[nBlock].pGraphBlock->sKoteiName );
			CString sCheckYosoSagyogunName = g_pDataManager->RemoveUnderbarDigit( m_aZahyoDrag[nBlock].pGraphBlock->sYosoSagyogunName );

			if( sKoteiName == sCheckKoteiName && sYosoSagyogunName == sCheckYosoSagyogunName )
				pDoc->AddReferenceKotei( m_aZahyoDrag[nBlock], pWnd->m_nGraphKishu );
		}
	}

	Invalidate();
	if( pDoc->m_aRefYosoSagyogun.GetSize() > 0 ){
		DispCenterSagyosha( pDoc->m_aRefYosoSagyogun[0].nSublineId, pDoc->m_aRefYosoSagyogun[0].nSagyoshaId );
		((CManualEditChildWnd *)GetParentFrame())->m_nRefIndex = 0;
	}
}

// キット化工程の選択追加（非アクティブ画面）
void CManualEditView1::AddSelectKittingKotei()
{
	CManualEditDocument *pDoc = GetDocument();

// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();

	if( GetParentFrame() == pDoc->m_pSelectWnd && pWnd->m_nGraphKishu != -1 )
//	if( GetParentFrame() == pDoc->m_pSelectWnd )
// By Y.Itabashi (xxxxx) 2007.03.06
		return;

	if( !pDoc->m_bSelectKitting )
		return;

	BOOL bDispSagyosha = FALSE;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( !pDoc->m_aSelectYosoSagyogun[nSelect].bKitting )
			continue;

		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[pDoc->m_aGraphSubline.GetSize()-1];
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[pDoc->m_aSelectYosoSagyogun[nSelect].nSagyoshaId];

		CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;
		for( int nBlock = 0; nBlock < aGraphBlock.GetSize(); nBlock++ ){

			if( aGraphBlock[nBlock].sKoteiName == pDoc->m_aSelectYosoSagyogun[nSelect].sKoteiName &&
				aGraphBlock[nBlock].sYosoSagyogunName == pDoc->m_aSelectYosoSagyogun[nSelect].sYosoSagyogunName ){

				for( int nZahyoDrag = 0; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

					if( m_aZahyoDrag[nZahyoDrag].pGraphBlock == &aGraphBlock[nBlock] ){
						pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );

						if( !bDispSagyosha ){
							DispCenterSagyosha( -1, m_aZahyoDrag[nZahyoDrag].nSagyoshaId );
							bDispSagyosha = TRUE;
						}
					}
				}

			}
		}
	}
}

// 自動工程の選択追加（全画面）
void CManualEditView1::AddSelectAutiKotei()
{
	CManualEditDocument *pDoc = GetDocument();
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();

	if( pDoc->m_bSelectKitting )
		return;

	int nCheckKishu = -1;
	int nSelectNum = pDoc->m_aSelectYosoSagyogun.GetSize();
	for( int nSelect = 0; nSelect < nSelectNum; nSelect++ ){

		if( pWnd->m_nGraphKishu == pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId ){

			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[nSelect].nIndex : pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu;
			for( int nZahyoDrag = nIndex+1; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

				if( m_aZahyoDrag[nZahyoDrag].pGraphBlock->sJido == g_pConst->m_sJido ){
					pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );
				}else
					return;
			}
		}else if( pWnd->m_nGraphKishu == -1 && nCheckKishu != pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId ){

			int nIndex = ( pWnd->m_nGraphKishu == -1 ) ? pDoc->m_aSelectYosoSagyogun[nSelect].nIndex : pDoc->m_aSelectYosoSagyogun[nSelect].nIndexKishu;
			for( int nZahyoDrag = nIndex+1; nZahyoDrag < m_aZahyoDrag.GetSize(); nZahyoDrag++ ){

				if( m_aZahyoDrag[nZahyoDrag].pGraphBlock->sJido == g_pConst->m_sJido ){
					pDoc->AddSelectKotei( m_aZahyoDrag[nZahyoDrag], ((CManualEditChildWnd *)GetParentFrame())->m_nGraphKishu );
				}else
					break;
			}

			nCheckKishu = pDoc->m_aSelectYosoSagyogun[nSelect].nKishuId;
		}
	}
}
