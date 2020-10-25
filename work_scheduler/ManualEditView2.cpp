//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView2.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView2 クラスのインプリメンテーション
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
#include "ManualEditView2.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditView2, CManualEditView)

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::CManualEditView2
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
CManualEditView2::CManualEditView2()
{
	// スケールパラメータ設定
	SetScaleParamaeter();
}


BEGIN_MESSAGE_MAP(CManualEditView2, CManualEditView)
	//{{AFX_MSG_MAP(CManualEditView2)
	ON_WM_SIZE()
	ON_WM_LBUTTONDOWN()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

void CManualEditView2::OnInitialUpdate()
{
	CManualEditView::OnInitialUpdate();

	// タイトル設定
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[2].MenuNo, Menu_ManualWindowTitle[2].ItemNo );
	GetParent()->SetWindowText( str );
//	GetParent()->SetWindowText("作業者一覧表示画面");
// By Y.Itabashi (xxxxx) 2007.01.26
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::OnDraw
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ビュー描画処理
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
void CManualEditView2::OnDraw(CDC* pDC)
{
	CFont *pFontOrg = pDC->SelectObject(g_pSystem->m_pMainDlg->GetFont());

	// 描画モードをTRANSPARENTに設定
	int nBkMode = pDC->SetBkMode(TRANSPARENT);

	// 作業グラフ描画
	DrawGraph(pDC, FALSE);

	// 横軸描画
	DrawHorizontalAxisLine(pDC);

	// 横軸テキスト描画
	DrawHorizontalAxisText(pDC);

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
//		CManualEditView2::OnUpdate
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
void CManualEditView2::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	switch (lHint) {
	case UPDATE_GRAPH_KIND:
	case UPDATE_CHANGE_SLIM:
	case UPDATE_SCROLL:
	case UPDATE_EDIT_VIEW:
	case UPDATE_SELECT_BLINK:
	case UPDATE_ERASE_MOVE_TRIANGLE:
		return;
	case UPDATE_CHANGE_SUBLINE:
		InitializeZahyoData();
		break;
	}

	// 座標を計算
	MakeGraph();

	// ビュー領域を無効化
	Invalidate();

	// サブライン情報表示
	DispSublineInfo();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::OnSize
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
void CManualEditView2::OnSize(UINT nType, int cx, int cy)
{
	CManualEditView::OnSize(nType, cx, cy);

	if (m_bInitialize) {
		// グラフの座標を再計算
		MakeGraph();

		// サブライン情報表示位置設定
		SetSublineInfoPos();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::MakeGraph
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
void CManualEditView2::MakeGraph()
{
	CManualEditDocument *pDoc = GetDocument();

	// ビューサイズを設定
	SetViewSize();

	// 座標データを削除
	ClearZahyoData();

	// 作業者別にグラフの座標を計算
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
		cZahyoSubline.nRight = (cGraphSubline.nSublineId != -1) ? nSublineBound : m_rectGraph.right;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::MakeGraphSubline
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
void CManualEditView2::MakeGraphSub(SGraphSubline &cGraphSubline, int &nLeft)
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26
	int i;
	double fBottom, fTop;
	CRect rect;
// Modify ... ( CHANGE )
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( cGraphSubline.nSublineId );
//	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu();
// By Y.Itabashi (xxxxx) 2007.02.11

	// 作業者別にグラフの座標を計算
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
		fBottom = m_rectGraph.bottom;
// Modify ... ( CHANGE )
		if (pWnd->m_nGraphKishu == -1) {
//		if (pDoc->m_nGraphKishu == -1) {
// By Y.Itabashi (xxxxx) 2007.02.26
			// 加重平均のグラフ
			int nKishuNum = cGraphSagyosha.aKishu.GetSize();
			for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
				SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);
				SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[nKishuId];

				fTop = fBottom - GetScaleHeight(cGraphKishu.fTime) * pKishu->nSeisanDaisu / nTotalSeisanDaisu;
				rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
				AddZahyoGraph(rect, cGraphKishu.sKishuName, COORDINATE_KIND_SAGYO, nKishuId);
				fBottom = fTop;
			}
		} else {
			// 機種別のグラフ
// Modify ... ( CHANGE )
			SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[pWnd->m_nGraphKishu];
//			SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[pDoc->m_nGraphKishu];
// By Y.Itabashi (xxxxx) 2007.02.26
			fTop = fBottom - GetScaleHeight(cGraphKishu.fTime);
			rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
// Modify ... ( CHANGE )
			AddZahyoGraph(rect, cGraphKishu.sKishuName, COORDINATE_KIND_SAGYO, pWnd->m_nGraphKishu);
//			AddZahyoGraph(rect, cGraphKishu.sKishuName, COORDINATE_KIND_SAGYO, pDoc->m_nGraphKishu);
// By Y.Itabashi (xxxxx) 2007.02.26
		}

		rect.bottom = m_rectGraph.bottom;
		AddZahyoSagyosha(rect, cGraphSubline.nSublineId, cGraphSagyosha.nSagyoshaId);

		nLeft += m_nBarWidth + m_nBarSpacing;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::SetViewSize
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
void CManualEditView2::SetViewSize()
{
// Modify ... ( CHANGE )
	CManualEditChildWnd *pWnd = (CManualEditChildWnd *)GetParentFrame();
//	CManualEditDocument *pDoc = GetDocument();
// By Y.Itabashi (xxxxx) 2007.02.26

	// ビューのサイズを取得
	GetClientRect(m_rectView);

	// グラフ領域を計算
	m_rectGraph = m_rectView;
	m_rectGraph.DeflateRect(m_nLeftMargin, m_nTopMargin, m_nRightMargin, m_nBottomMargin);

	// バーが重ならないように幅を調整
	int nSagyoshaNum = GetTotalSagyoshaNum();
	int nKittingSagyoshaNum = GetKittingSagyoshaNum();
	if (nKittingSagyoshaNum != 0)
		nSagyoshaNum += nKittingSagyoshaNum + 1;
	m_nBarWidth = nSagyoshaNum ? (m_rectGraph.Width() - m_nLeftSpacing) / nSagyoshaNum / 2 : 0;
	if (m_nBarWidth > 10)
		m_nBarWidth = 10;
	m_nBarSpacing = nSagyoshaNum ? (m_rectGraph.Width() - m_nLeftSpacing - m_nBarWidth * nSagyoshaNum) / nSagyoshaNum : 0;

	// 目標タクトの高さ
// Modify ... ( CHANGE )
	SetScaleFactor(TRUE, pWnd->m_nGraphKishu);
//	SetScaleFactor(TRUE, pDoc->m_nGraphKishu);
// By Y.Itabashi (xxxxx) 2007.02.26

	SetScrollSizes(MM_TEXT, CSize(m_rectView.Width(), m_rectView.Height()));
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::DrawHorizontalAxisText
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
void CManualEditView2::DrawHorizontalAxisText(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	BOOL bAlternate = FALSE;
	int nSublineIndex;

	// 作業者の表示が重なってしまうかチェック
	int nSagyoshaNum = GetTotalSagyoshaNum();
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		nSagyoshaNum += cGraphSubline.aGraphSagyosha.GetSize();
	}
	nSagyoshaNum += GetKittingSagyoshaNum();
	CSize sizeText = pDC->GetTextExtent("D99");
	if ((sizeText.cx + 2) * nSagyoshaNum > m_rectGraph.Width() - m_nLeftSpacing)
		bAlternate = TRUE;

	int nCounter = 0;
	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	for (nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		DrawHorizontalAxisTextSub(pDC, cGraphSubline, nLeft, bAlternate, nCounter);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::DrawHorizontalAxisText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸テキスト描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC					*pDC				[I] デバイスコンテキスト
//		CGraphSagyoshaArray	&aGraphSagyosha		[I] グラフ作業者配列
//		int					&nLeft				[I/O] X座標
//		BOOL				bAlternate			[I] TRUE：作業者を２段に表示する
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
void CManualEditView2::DrawHorizontalAxisTextSub(CDC *pDC, SGraphSubline &cGraphSubline, int &nLeft, BOOL bAlternate, int &nCounter)
{
	CSize sizeText = pDC->GetTextExtent("D99");

	// 作業者によるループ
	CRect rectText;
	int nBaseHeight = m_rectGraph.bottom;
	int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
	for (int i = 0; i < nSagyoshaNum; i++) {
		SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];

		// 作業者の表示が重なってしまう場合は２段に表示
		if (!bAlternate || nCounter++ % 2 == 0)
			rectText.SetRect(nLeft, nBaseHeight + 8, nLeft + m_nBarWidth, m_rectView.bottom);
		else
			rectText.SetRect(nLeft, nBaseHeight + 8 + sizeText.cy, nLeft + m_nBarWidth, m_rectView.bottom);

		// 作業者名を表示
		if (pDC->RectVisible(rectText))
			pDC->DrawText(cGraphSagyosha.sSagyoshaDispName, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);

		nLeft += m_nBarWidth;
		nLeft += m_nBarSpacing;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView2::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 仮想キーの状態
//		CPoint		point			[I] マウスカーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		クリックされた作業者を他のビューの中央に表示させる。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView2::OnLButtonDown(UINT nFlags, CPoint point)
{
	SZahyoSagyosha *pClickZahyoSagyosha = CheckZahyoSagyosha(point);
	if (pClickZahyoSagyosha != NULL)
		GetDocument()->UpdateAllViews(this, UPDATE_SCROLL, (CObject *)pClickZahyoSagyosha);

	CManualEditView::OnLButtonDown(nFlags, point);
}

// スケールパラメータ設定
void CManualEditView2::SetScaleParamaeter()
{
	m_nLeftMargin = 0;
	m_nTopMargin = 60;
	m_nRightMargin = 0;
	m_nBottomMargin = 35;
	m_nBarWidth = 10;
	m_nLeftSpacing = 20;
	m_nBarSpacing = 10;
}
