//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView3.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView3 クラスのインプリメンテーション
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
#include "ManualEditView3.h"
#include "DataManager.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditView3, CManualEditView)

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::CManualEditView3
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
CManualEditView3::CManualEditView3()
{
	// スケールパラメータ設定
	SetScaleParamaeter();
}


BEGIN_MESSAGE_MAP(CManualEditView3, CManualEditView)
	//{{AFX_MSG_MAP(CManualEditView3)
	ON_WM_SIZE()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

void CManualEditView3::OnInitialUpdate()
{
	CManualEditView::OnInitialUpdate();

	// タイトル設定
// Modify ... ( CHANGE )
	CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowTitle[3].MenuNo, Menu_ManualWindowTitle[3].ItemNo );
	GetParent()->SetWindowText( str );
//	GetParent()->SetWindowText("全情報表示画面");
// By Y.Itabashi (xxxxx) 2007.01.26
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::OnDraw
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
void CManualEditView3::OnDraw(CDC* pDC)
{
	CFont *pFontOrg = pDC->SelectObject(g_pSystem->m_pMainDlg->GetFont());

	// 描画モードをTRANSPARENTに設定
	int nBkMode = pDC->SetBkMode(TRANSPARENT);

	// 作業グラフ描画
	DrawGraph(pDC, TRUE);

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

	// 凡例描画
	DrawLegends(pDC);

	// 描画モードを戻す
	pDC->SetBkMode(nBkMode);

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::OnUpdate
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
void CManualEditView3::OnUpdate(CView* pSender, LPARAM lHint, CObject* pHint)
{
	switch (lHint) {
	case UPDATE_GRAPH_KISHU:
	case UPDATE_EDIT_VIEW:
	case UPDATE_SELECT_BLINK:
	case UPDATE_ERASE_MOVE_TRIANGLE:
		return;
	case UPDATE_SCROLL:
		{
			SZahyoSagyosha *pZahyoSagyosha = (SZahyoSagyosha *)pHint;
			DispCenterSagyosha(pZahyoSagyosha->nSublineId, pZahyoSagyosha->nSagyoshaId);
		}
		return;
	case UPDATE_CHANGE_SLIM:
		// スケールパラメータ設定
		SetScaleParamaeter();
		SetSlimMode();
		break;
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
//		CManualEditView3::OnSize
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
void CManualEditView3::OnSize(UINT nType, int cx, int cy)
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
//		CManualEditView3::MakeGraph
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
void CManualEditView3::MakeGraph()
{
	CManualEditDocument *pDoc = GetDocument();
	int nKittingSublineId = -1;
	int i, j;

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
		int nSublineId = cGraphSubline.nSublineId;

		if (nSublineId == -1) {
			nLeft += m_nBarSpacing;
			nSublineBound += m_nBarSpacing / 2;
			m_aZahyoSubline[nSublineIndex - 1].nRight = nSublineBound;
		}

		int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
		for (i = 0; i < nSagyoshaNum; i++) {
			SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
			CGraphBlockArray &aGraphBlock = pDoc->m_nGraphKind == GRAPH_YOSO_SAGYOGUN ? cGraphSagyosha.aYosoSagyogun : cGraphSagyosha.aKotei;

			int nLeftSagyosha = nLeft;

			// 加重平均グラフの座標
			MakeZahyoSagyo(nSublineId, aGraphBlock, cGraphSagyosha.nSagyoshaId, -1, nLeft, m_rectGraph.bottom, nKittingSublineId);
			nLeft += m_nBarWidth;

			// 機種別にグラフの座標を計算
			if (nSublineId != -1) {
				int nKishuNum = cGraphSagyosha.aKishu.GetSize();
				for (j = 0; j < nKishuNum; j++) {
					MakeZahyoSagyo(nSublineId, aGraphBlock, cGraphSagyosha.nSagyoshaId, j, nLeft, m_rectGraph.bottom, nKittingSublineId);
					nLeft += m_nBarWidth;
				}
			}

			AddZahyoSagyosha(CRect(nLeftSagyosha, 0, nLeft, 0), nSublineId, cGraphSagyosha.nSagyoshaId);

			nLeft += m_nBarSpacing;
		}

		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];
		cZahyoSubline.nSublineId = nSublineId;
		cZahyoSubline.nLeft = nSublineBound;
		nSublineBound = nLeft - m_nBarSpacing / 2;
		cZahyoSubline.nRight = nSublineBound;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::MakeZahyoSagyo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ座標データ作成
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphBlockArray	&aGraphBlock		[I] グラフブロック配列
//		int					nSagyoshaId		[I] 作業者ID
//		int					nKishuId		[I] 機種ID
//		int					nLeft			[I] X座標
//		int					nTop			[I] Y座標
//		double				fRatio			[I] 全機種の生産台数に対するこの機種の生産台数の割合
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
int CManualEditView3::MakeZahyoSagyo(int nSublineId, CGraphBlockArray &aGraphBlock, int nSagyoshaId, int nKishuId, int nLeft, int nTop, int &nKittingSublineId)
{
	CManualEditDocument *pDoc = GetDocument();
	int i;
	double fBottom, fTop;
	CRect rect;
	double fRatio;
	int nTotalSeisanDaisu = g_pDataManager->GetTotalSeisanDaisu( nSublineId );

	int nSize = aGraphBlock.GetSize();

	// グラフのブロックによるループ
	fBottom = nTop;
	for (i = 0; i < nSize; i++) {
		SGraphBlock &cGraphBlock = aGraphBlock[i];

		if (nKishuId == -1 || cGraphBlock.nKishuId == nKishuId) {
			// キット化のサブライン区切り
			if (cGraphBlock.bKitting) {
				if (nKittingSublineId != cGraphBlock.nSublineId) {
					if (nKittingSublineId != -1) {
						SZahyoKittingSubline *pZahyoKittingSubline = m_aZahyoKittingSubline.AddElement();
						pZahyoKittingSubline->pointLine.x = nLeft;
						pZahyoKittingSubline->pointLine.y = (int)fBottom;
						pZahyoKittingSubline->sSiblineName1 = g_pDataManager->GetSublineData(cGraphBlock.nSublineId)->sSublineName;

						if (i == 0) {
							pZahyoKittingSubline = m_aZahyoKittingSubline.AddElement();
							SZahyoGraph &cZahyoGraph = m_aZahyoGraph[m_aZahyoGraph.GetSize() - 1];
							pZahyoKittingSubline->pointLine.x = cZahyoGraph.rect.left;
							pZahyoKittingSubline->pointLine.y = cZahyoGraph.rect.top;
						}

						pZahyoKittingSubline->sSiblineName2 = g_pDataManager->GetSublineData(nKittingSublineId)->sSublineName;
					}
					nKittingSublineId = cGraphBlock.nSublineId;
				}
			}

			// グラフの四角い各領域の座標を計算
			if (cGraphBlock.fTime != 0) {
				if (nKishuId == -1) {
					SKishu *pKishu = g_pDataManager->GetKishuData(cGraphBlock.nKishuId);
					fRatio = (double)pKishu->nSeisanDaisu / nTotalSeisanDaisu;
				} else
					fRatio = 1;

				fTop = fBottom - cGraphBlock.fTime * m_fScaleFactor * fRatio;
				rect.SetRect(nLeft, (int)fTop, nLeft + m_nBarWidth, (int)fBottom);
				fBottom = fTop;
				AddZahyoGraph(rect, pDoc->m_nGraphKind == GRAPH_KOTEI ? cGraphBlock.sKoteiName : cGraphBlock.sYosoSagyogunName, COORDINATE_KIND_SAGYO, cGraphBlock.nKishuId);
			}
		}
	}

	return (int)fBottom;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::SetViewSize
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
void CManualEditView3::SetViewSize()
{
	int nSagyoshaNum = GetTotalSagyoshaNum();
	int nKishuNum = g_pDataManager->GetKishuNum();

	// ビューのサイズを計算
	GetClientRect(m_rectView);
	if (GetTotalSize().cx < m_rectView.Width())
		m_rectView.bottom -= ::GetSystemMetrics(SM_CYHSCROLL);

	m_rectView.right = m_nLeftMargin + m_nLeftSpacing + (nKishuNum + 1) * nSagyoshaNum * m_nBarWidth + nSagyoshaNum * m_nBarSpacing + m_nRightMargin;
	int nKittingSagyoshaNum = GetKittingSagyoshaNum();
	if (nKittingSagyoshaNum != 0)
		m_rectView.right += (nKittingSagyoshaNum + 1) * (m_nBarWidth + m_nBarSpacing);

	// グラフ領域を計算
	m_rectGraph = m_rectView;
	m_rectGraph.DeflateRect(m_nLeftMargin, m_nTopMargin, m_nRightMargin, m_nBottomMargin);

	// 目標タクトの高さ
	SetScaleFactor(TRUE, -2);

	// スクロールエリアのサイズを設定
	SetScrollSizes(MM_TEXT, CSize(m_rectView.Width(), m_rectView.Height()));

	// 垂直スクロールバーを非表示
	ShowScrollBar(SB_VERT, FALSE);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::DrawHorizontalAxisText
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
//		横軸のテキスト（機種名、作業者名）を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView3::DrawHorizontalAxisText(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	int i, j;

	CFont *pFontOrg = pDC->SelectObject(&m_fontH);

	// 作業者によるループ
	CRect rectText;
	int nBaseHeight = m_rectGraph.bottom;
	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
		for (i = 0; i < nSagyoshaNum; i++) {
			SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
			int nKishuNum = cGraphSagyosha.aKishu.GetSize();
			int nLeft2 = nLeft;

			if (cGraphSubline.nSublineId != -1) {
				// 「全」を表示
				rectText.SetRect(nLeft, nBaseHeight + 8, nLeft + m_nBarWidth, m_rectView.bottom);
// Modify ... ( CHANGE )
				if (pDC->RectVisible(rectText)){
					CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[6].MenuNo, Menu_ManualWindowText[6].ItemNo );
					pDC->DrawText(str, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);
				}
//				if (pDC->RectVisible(rectText))
//					pDC->DrawText("全", rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);
// By Y.Itabashi (xxxxx) 2007.01.26
				nLeft += m_nBarWidth;

				// 機種によるループ
				for (j = 0; j < nKishuNum; j++) {
					SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[j];

					// 機種名を表示
					rectText.SetRect(nLeft, nBaseHeight + 8, nLeft + m_nBarWidth, m_rectView.bottom);
					if (pDC->RectVisible(rectText))
						pDC->DrawText(cGraphKishu.sKishuName, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);

					nLeft += m_nBarWidth;
				}
			} else
				nLeft += m_nBarWidth;

			// 作業者名を表示
			rectText.SetRect(nLeft2, nBaseHeight + (cGraphSubline.nSublineId != -1 ? 28 : 8), nLeft, m_rectView.bottom);
			if (pDC->RectVisible(rectText))
				pDC->DrawText(cGraphSagyosha.sSagyoshaName, rectText, DT_CENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP);

			nLeft += m_nBarSpacing;
		}
	}

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView3::DrawTotalTime
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
void CManualEditView3::DrawTotalTime(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();

	CFont *pFontOrg = pDC->SelectObject(&m_fontTime);

	int i, j;
	CString sTime;
	CRect rectText;

	int nLeft = m_rectGraph.left + m_nLeftSpacing;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId == -1)
			nLeft += m_nBarSpacing;

		int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
		for (i = 0; i < nSagyoshaNum; i++) {
			SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];

			DrawTime(pDC, cGraphSubline, nLeft, cGraphSagyosha.fTime);

			nLeft += m_nBarWidth;

			if (cGraphSubline.nSublineId != -1) {
				// 機種別にグラフの座標を計算
				int nKishuNum = cGraphSagyosha.aKishu.GetSize();
				for (j = 0; j < nKishuNum; j++) {
					SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[j];

					DrawTime(pDC, cGraphSubline, nLeft, cGraphKishu.fTime);

					nLeft += m_nBarWidth;
				}
			}

			nLeft += m_nBarSpacing;
		}
	}

	pDC->SelectObject(pFontOrg);
}

// スケールパラメータ設定
void CManualEditView3::SetScaleParamaeter()
{
	m_nLeftMargin = 40;
	m_nTopMargin = 70;
	m_nRightMargin = 100;
	m_nBottomMargin = 50;
	m_nBarWidth = 80;
	m_nLeftSpacing = 20;
	m_nBarSpacing = 30;
}
