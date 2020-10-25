//*****************************************************************************************************
//  1. ファイル名
//		ManualEditView.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CManualEditView クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"

#include "ManualEditWnd002.h"
#include "ManualEditView.h"
#include "DataManager.h"
#include "ManualEditFrameWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

IMPLEMENT_DYNCREATE(CManualEditView, CScrollView)

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::CManualEditView
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
//		クラス変数の初期化、描画オブジェクトの作成、機種ごとのグラフの色の作成を行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CManualEditView::CManualEditView()
{
	// クラス変数の初期化
	m_bInitialize = FALSE;

	// 描画オブジェクトを作成
	m_penGraph.CreatePen(PS_SOLID, 2, RGB(0x00, 0x00, 0x00));
	m_penAxis.CreatePen(PS_SOLID, 2, RGB(0, 0, 0));
	m_penTact.CreatePen(PS_SOLID, 2, RGB(0, 0, 0xff));
	m_penTactLimit.CreatePen(PS_DASH, 1, RGB(0xff, 0x77, 0x33));
	m_penSublineBar.CreatePen(PS_DOT, 1, g_pSystem->m_cIniUser.m_rgbSublineBarColor);
	m_penKyotsuKotei.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbCommonProcessColor);
	m_penKittingLine.CreatePen(PS_SOLID, 2, g_pSystem->m_cIniUser.m_rgbKittinglineBarColor);
	m_penKittingSubline.CreatePen(PS_DOT, 1, g_pSystem->m_cIniUser.m_rgbKitSublineBarColor);
	m_fontGraph.CreatePointFont(80, g_pConst->m_sDefaultFontName);
	m_fontH.CreateFont(14, 0, 0, 0, FW_BOLD, FALSE, FALSE, FALSE, DEFAULT_CHARSET, OUT_DEFAULT_PRECIS, CLIP_DEFAULT_PRECIS, DEFAULT_QUALITY, DEFAULT_PITCH, "MS UI Gothic");
	m_fontV.CreateFont(14, 0, 900, 0, FW_BOLD, FALSE, FALSE, FALSE, DEFAULT_CHARSET, OUT_DEFAULT_PRECIS, CLIP_DEFAULT_PRECIS, DEFAULT_QUALITY, DEFAULT_PITCH, "MS UI Gothic");
	m_fontTime.CreateFont(14, 0, 0, 0, FW_MEDIUM, FALSE, FALSE, FALSE, DEFAULT_CHARSET, OUT_DEFAULT_PRECIS, CLIP_DEFAULT_PRECIS, DEFAULT_QUALITY, DEFAULT_PITCH, "MS UI Gothic");
	m_brushJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbAutoProcessColor);
	m_brushHanJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbSemiAutoProcessColor);
	m_brushKyotsuJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbCommonAutoProcessColor);
	m_brushKyotsuHanJido.CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbCommonSemiAutoProcessColor);

	// 機種ごとのブラシを作成
	for (int i = 0; i < MAX_KISHU; i++)
		m_brushGraph[i].CreateSolidBrush(g_pSystem->m_cIniUser.m_rgbProcessGraphColor[i]);
}


BEGIN_MESSAGE_MAP(CManualEditView, CScrollView)
	//{{AFX_MSG_MAP(CManualEditView)
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	//}}AFX_MSG_MAP
	ON_NOTIFY(TTN_NEEDTEXT, 0, OnGetToolTipText)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::OnInitialUpdate
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
//		ツールチップ表示有効化を行う。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::OnInitialUpdate()
{
	ModifyStyle(0, WS_CLIPSIBLINGS | WS_CLIPCHILDREN);

	CDC *pDC = GetDC();
	m_dcCompatible.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);

	InitializeZahyoData();

	CScrollView::OnInitialUpdate();

	// ツールチップ表示有効化
	EnableToolTips(TRUE);

	// ツールチップ内に"&"を表示できるようにする
	_AFX_THREAD_STATE* pThreadState = AfxGetThreadState();
	CToolTipCtrl* pToolTip = pThreadState->m_pToolTip;
	if (pThreadState->m_pToolTip == NULL) {
		pThreadState->m_pToolTip = new CToolTipCtrl;
		pThreadState->m_pToolTip->Create(GetParentOwner(), TTS_ALWAYSTIP | TTS_NOPREFIX);
	}

	m_bInitialize = TRUE;
}

void CManualEditView::InitializeZahyoData()
{
	CManualEditDocument *pDoc = GetDocument();

	m_aZahyoSubline.RemoveAll();

	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	m_aZahyoSubline.SetSize(nGraphSublineNum);
	for (int i = 0; i < nGraphSublineNum; i++)
		m_aZahyoSubline[i].cSublineInfo.Create(this);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::OnDraw
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC			[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		描画は派生クラスで行うためここでは何もしない。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::OnDraw(CDC* pDC)
{
}

void CManualEditView::ClearZahyoData()
{
	m_aZahyoGraph.RemoveAll();
	m_aZahyoKittingSubline.RemoveAll();
	m_aZahyoKyotsuKoteiFrame.RemoveAll();
	m_aZahyoSagyosha.RemoveAll();
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::AddZahyoGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ座標追加処理
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
//		m_aZahyoGraphに指定された座標データを追加する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::AddZahyoGraph(const CRect &rect, CString &sText, int nKind, int nKishuId)
{
	if (!rect.IsRectEmpty()) {
		SZahyoGraph *pZahyoGraph = m_aZahyoGraph.AddElement();
		pZahyoGraph->rect = rect;
		pZahyoGraph->sText = sText;
		pZahyoGraph->nKind = nKind;
		pZahyoGraph->nKishuId = nKishuId;
	}
}

// 共通工程枠座標追加処理
int CManualEditView::AddKyotsuKoteiFrame(const CRect &rect)
{
	int nIndex;
	SZahyoKyotsuKoteiFrame *pZahyoKyotsuKoteiFrame = m_aZahyoKyotsuKoteiFrame.AddElement(nIndex);
	pZahyoKyotsuKoteiFrame->rect = rect;
	return nIndex;
}

void CManualEditView::AddZahyoSagyosha(const CRect &rect, int nSublineId, int nSagyoshaId)
{
	SZahyoSagyosha *pZahyoSagyosha = m_aZahyoSagyosha.AddElement();
	pZahyoSagyosha->rect = rect;
	pZahyoSagyosha->nSublineId = nSublineId;
	pZahyoSagyosha->nSagyoshaId = nSagyoshaId;
}

SZahyoSagyosha *CManualEditView::CheckZahyoSagyosha(CPoint point)
{
	point += GetScrollPosition();

	// 座標配列の中から指定された位置を含む四角形を検索
	int nSize = m_aZahyoSagyosha.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSagyosha *pZahyoSagyosha = &m_aZahyoSagyosha[i];
		if (pZahyoSagyosha->rect.PtInRect(point))
			return pZahyoSagyosha;
	}

	return NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//		BOOL	bDrawText			[I] 四角内にテキストをTRUE：表示する、FALSE：表示しない
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		m_aZahyoGraphに設定された座標データに従って四角形及びその中のテキストを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawGraph(CDC *pDC, BOOL bDrawText)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penGraph);
	CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);
	CFont *pFontOrg = pDC->SelectObject(&m_fontGraph);

	// 座標配列の全てを表示
	int nSize = m_aZahyoGraph.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoGraph &cZahyoGraph = m_aZahyoGraph[i];
		CRect &rect = cZahyoGraph.rect;

		// 四角形が表示領域内かチェック
		CRect rect2(rect.left - 1, rect.top - 1, rect.right + 1, rect.bottom + 1);
		if (pDC->RectVisible(rect2)) {
			switch (cZahyoGraph.nKind) {
			case COORDINATE_KIND_SAGYO:
				if (cZahyoGraph.rect.Height() != 0) {
					// グラフの色を選択
					pDC->SelectObject(&m_brushGraph[cZahyoGraph.nKishuId]);

					// 各工程・要素作業群の四角形を表示
					CRect rect2(rect.left, rect.top, rect.right + 1, rect.bottom + 1);
					pDC->Rectangle(rect2);

					// 四角形の中に工程名・要素作業群名を表示
					if (bDrawText)
						DrawTextCenter(pDC, cZahyoGraph.sText, rect2);
				}
				break;
			case COORDINATE_KIND_JIDO:
				// 自動工程の青い三角を表示
				pDC->SelectObject(&m_brushJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_HANJIDO:
				// 半自動工程の黄色い三角を表示
				pDC->SelectObject(&m_brushHanJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_JIDO_KYOTSU:
				// 共通自動工程の青い三角を表示
				pDC->SelectObject(&m_brushKyotsuJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_HANJIDO_KYOTSU:
				// 共通半自動工程の黄色い三角を表示
				pDC->SelectObject(&m_brushKyotsuHanJido);
				DrawLeftTriangle(pDC, rect);
				break;
			case COORDINATE_KIND_KYOTSU_KOTEI:
				CBitmap *pBitmapOrg = m_dcCompatible.SelectObject(&m_bmpKyotsuKotei);
				pDC->BitBlt(rect.left, rect.top, rect.Width(), rect.Height(), &m_dcCompatible, 0, 0, SRCCOPY);
				m_dcCompatible.SelectObject(pBitmapOrg);
				break;
			}
		}
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pBrushOrg);
	pDC->SelectObject(pFontOrg);
}

// 共通工程枠描画
void CManualEditView::DrawKyotsuKoteiFrame(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penKyotsuKotei);
	CBrush *pBrushOrg = (CBrush *)pDC->SelectStockObject(NULL_BRUSH);

	int nSize = m_aZahyoKyotsuKoteiFrame.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoKyotsuKoteiFrame &cKyotsuKotei = m_aZahyoKyotsuKoteiFrame[i];

		CRect rect2(cKyotsuKotei.rect.left - 1, cKyotsuKotei.rect.top - 1, cKyotsuKotei.rect.right + 1, cKyotsuKotei.rect.bottom + 1);
		if (pDC->RectVisible(rect2)) {
			CRect rect2(cKyotsuKotei.rect.left, cKyotsuKotei.rect.top, cKyotsuKotei.rect.right + 1, cKyotsuKotei.rect.bottom + 1);
			pDC->Rectangle(rect2);
		}
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawLeftTriangle
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左を向いた三角形を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//		CRect	&rect				[I] 三角形を表示する座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された座標に左を向いた三角形を表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawLeftTriangle(CDC *pDC, const CRect &rect)
{
	CPoint point[3];

	point[0].x = rect.left;
	point[0].y = rect.top + rect.Height() / 2;
	point[1].x = rect.right;
	point[1].y = rect.top;
	point[2].x = rect.right;
	point[2].y = rect.bottom;
	CPen *pPen = (CPen *)pDC->SelectStockObject(BLACK_PEN);
	pDC->Polygon(point, 3);
	pDC->SelectObject(pPen);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawRightTriangle
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右を向いた三角形を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//		CRect	&rect				[I] 三角形を表示する座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された座標に右を向いた三角形を表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawRightTriangle(CDC *pDC, const CRect &rect)
{
	CPoint point[3];

	point[0].x = rect.right;
	point[0].y = rect.top + rect.Height() / 2;
	point[1].x = rect.left;
	point[1].y = rect.top;
	point[2].x = rect.left;
	point[2].y = rect.bottom;
	CPen *pPen = (CPen *)pDC->SelectStockObject(BLACK_PEN);
	pDC->Polygon(point, 3);
	pDC->SelectObject(pPen);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawTextCenter
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		テキストを指定された枠の中央に描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//		CString	&sText				[I] 表示するテキスト
//		CRect	&rect				[I] 枠の四角形の座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された四角形の座標の中にテキストを表示する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawTextCenter(CDC *pDC, const CString &sText, CRect rect)
{
	// 枠内のマージンを確保
	rect.left += 2;
	rect.top += 1;
	rect.right -= 2;
	rect.bottom -= 1;

	CRect rectText(rect);
	CRect rectText2(rectText);

	if (rect.Height() > 20) {
		// 描画後のサイズを取得
		pDC->DrawText(sText, rectText, DT_CENTER | DT_WORDBREAK | DT_CALCRECT | DT_EDITCONTROL | DT_NOPREFIX);

		// 上下のセンタリング位置を求める
		CSize sizeOffset((rect.Width() - rectText.Width()) / 2, (rect.Height() - rectText.Height()) / 2);

		if (sizeOffset.cy > 0) {
			// 枠内に入る場合はそのまま表示
			rectText.OffsetRect(sizeOffset);
			pDC->DrawText(sText, rectText, DT_CENTER | DT_WORDBREAK | DT_EDITCONTROL | DT_NOPREFIX);
			return;
		}
	}

	// 枠内に入らない場合は１行で省略記号を付けて表示
	CString sText2(sText);
	sText2.Replace('\n', ' ');
	pDC->DrawText(sText2, rectText2, DT_CENTER | DT_VCENTER | DT_SINGLELINE | DT_END_ELLIPSIS | DT_NOPREFIX);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawVerticalAxisLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフの縦軸を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフの縦軸を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawVerticalAxisLine(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penAxis);

	pDC->MoveTo(m_rectGraph.left, m_rectGraph.top);
	pDC->LineTo(m_rectGraph.left, m_rectGraph.bottom);

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawHorizontalAxisLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフの横軸を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフの横軸を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawHorizontalAxisLine(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penAxis);

	pDC->MoveTo(m_rectGraph.left, m_rectGraph.bottom);
	pDC->LineTo(m_rectGraph.right, m_rectGraph.bottom);

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawTactLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		目標タクトと上限、下限の線を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC				[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		目標タクトを実線、上限、下限の線を破線で描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawTactLine(CDC *pDC)
{
	CManualEditDocument *pDoc = GetDocument();
	CPen *pPenOrg = pDC->SelectObject(&m_penTact);

	int nGraphSublineNum = m_aZahyoSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

		int nLeft = cZahyoSubline.nLeft;
		int nRight = cZahyoSubline.nRight;
		if (nSublineIndex == nGraphSublineNum - 1)
			nRight = m_rectGraph.right;

		// 目標タクトの線
		int nTactPos = m_rectGraph.bottom - GetScaleHeight(cGraphSubline.fMokugyoTact);
		pDC->SelectObject(&m_penTact);
		pDC->MoveTo(nLeft, nTactPos);
		pDC->LineTo(nRight, nTactPos);

		// 上限の線
		int nJogenPos = m_rectGraph.bottom - GetScaleHeight(cGraphSubline.fJogen);
		pDC->SelectObject(&m_penTactLimit);
		pDC->MoveTo(nLeft, nJogenPos);
		pDC->LineTo(nRight, nJogenPos);
		pDC->MoveTo(nLeft, nJogenPos - 1);
		pDC->LineTo(nRight, nJogenPos - 1);

		// 下限の線
		int nKagenPos = m_rectGraph.bottom - GetScaleHeight(cGraphSubline.fKagen);
		pDC->MoveTo(nLeft, nKagenPos);
		pDC->LineTo(nRight, nKagenPos);
		pDC->MoveTo(nLeft, nKagenPos - 1);
		pDC->LineTo(nRight, nKagenPos - 1);
	}

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawVerticalAxisText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の文字を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			*pDC				[I] デバイスコンテキスト
//		LPCTSTR		pText				[I] 表示する文字
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		縦軸の左側の文字を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawVerticalAxisText(CDC *pDC, LPCTSTR pText)
{
	CFont *pFontOrg = pDC->SelectObject(&m_fontV);

	// 表示サイズを取得
	CSize sizeText = pDC->GetTextExtent(pText);

	// 縦軸の中央に表示
	pDC->TextOut(m_rectGraph.left - sizeText.cy - 8, m_rectGraph.top + (m_rectGraph.Height() + sizeText.cx) / 2, pText);

	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawLegends
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		凡例を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			*pDC				[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		グラフの凡例を描画する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawLegends(CDC *pDC)
{
	CRect rect;
	int nLeft = m_rectGraph.right + 10;
	int nTop = m_rectGraph.top;
	CPen *pPenOrg = pDC->SelectObject(&m_penGraph);

	// 機種でループ
	int nKishuNum = g_pDataManager->GetKishuNum();
	for (int nKishuId = 0; nKishuId < nKishuNum; nKishuId++) {
		SKishu *pKishu = g_pDataManager->GetKishuData(nKishuId);

		rect.SetRect(nLeft, nTop, nLeft + 70, nTop + 20);
		CBrush *pBrushOrg = pDC->SelectObject(&m_brushGraph[nKishuId]);

		// 四角の中に機種名を表示
		if (pDC->RectVisible(rect)) {
			pDC->Rectangle(rect);
			pDC->DrawText(pKishu->sKishuName, rect, DT_CENTER | DT_VCENTER | DT_TOP | DT_SINGLELINE | DT_NOCLIP | DT_NOPREFIX);
		}
		pDC->SelectObject(&pBrushOrg);

		nTop += 30;
	}

	pDC->SelectObject(pPenOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::DrawTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		合計時間描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			*pDC			[I] デバイスコンテキスト
//		int			nLeft			[I] 表示位置
//		double		fTime			[I] 合計時間
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		合計時間と充実度を描画する。目標タクトの上限を超えている場合は赤で表示。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::DrawTime(CDC *pDC, SGraphSubline &cGraphSubline, int nLeft, double fTime)
{
	CString sTime;

	int nTextHeight = m_rectGraph.bottom - GetScaleHeight(max(cGraphSubline.fJogen, fTime));
	CRect rectText(nLeft, 0, nLeft + m_nBarWidth, nTextHeight - 8);
	if (pDC->RectVisible(rectText)) {
		// 上限を超えている場合は赤で表示
		int color = pDC->SetTextColor(fTime > cGraphSubline.fJogen ? RGB(255, 0, 0) : RGB(0, 0, 0));

		// 充実度
// Modify ... ( CHANGE )
		CString str1 = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[2].MenuNo, Menu_ManualWindowText[2].ItemNo );
		sTime.Format(str1, fTime / cGraphSubline.fMokugyoTact * 100);
//		sTime.Format("%.1f%%", fTime / cGraphSubline.fMokugyoTact * 100);
// By Y.Itabashi (xxxxx) 2007.01.26
		pDC->DrawText(sTime, rectText, DT_CENTER | DT_BOTTOM | DT_SINGLELINE | DT_NOCLIP | DT_NOPREFIX);

		// 作業時間
// Modify ... ( CHANGE )
		CString str2 = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[1].MenuNo, Menu_ManualWindowText[1].ItemNo );
		sTime.Format(str2, fTime);
//		sTime.Format("%.3f分", fTime);
// By Y.Itabashi (xxxxx) 2007.01.26
		rectText.bottom -= 16;
		pDC->DrawText(sTime, rectText, DT_CENTER | DT_BOTTOM | DT_SINGLELINE | DT_NOCLIP | DT_NOPREFIX);
//		pDC->DrawText("合計時間", rectText, DT_CENTER | DT_BOTTOM | DT_SINGLELINE | DT_NOCLIP);

		pDC->SetTextColor(color);
	}
}

// サブライン間区切り線描画
void CManualEditView::DrawSublineBoundLine(CDC *pDC)
{
	CPen *pPenOrg = pDC->SelectObject(&m_penSublineBar);

	int nGraphSublineNum = m_aZahyoSubline.GetSize();
	for (int nSublineIndex = 1; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

		if (cZahyoSubline.nSublineId != -1) {
			pDC->MoveTo(cZahyoSubline.nLeft, 0);
			pDC->LineTo(cZahyoSubline.nLeft, m_rectGraph.bottom);
			pDC->MoveTo(cZahyoSubline.nLeft - 1, 0);
			pDC->LineTo(cZahyoSubline.nLeft - 1, m_rectGraph.bottom);
		} else {
			pDC->SelectObject(&m_penKittingLine);
			pDC->MoveTo(cZahyoSubline.nLeft, 0);
			pDC->LineTo(cZahyoSubline.nLeft, m_rectGraph.bottom);
		}
	}

	pDC->SelectObject(pPenOrg);
}

// キット化サブライン間区切り線描画
void CManualEditView::DrawKittingSubline(CDC *pDC)
{
	CRect rect;
	CPen *pPenOrg = pDC->SelectObject(&m_penKittingSubline);
	CFont *pFontOrg = pDC->SelectObject(&m_fontGraph);

	int nSize = m_aZahyoKittingSubline.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoKittingSubline &cKittingSubline = m_aZahyoKittingSubline[i];
		int x = cKittingSubline.pointLine.x;
		int y = cKittingSubline.pointLine.y;

		rect.SetRect(x - m_nBarSpacing / 2, y - 14, x + m_nBarWidth + m_nBarSpacing, y + 14);
		if (pDC->RectVisible(rect)) {
			pDC->MoveTo(x - m_nBarSpacing / 2, y - 1);
			pDC->LineTo(x + m_nBarWidth + m_nBarSpacing / 2, y - 1);
			pDC->MoveTo(x - m_nBarSpacing / 2, y);
			pDC->LineTo(x + m_nBarWidth + m_nBarSpacing / 2, y);

			if (!cKittingSubline.sSiblineName1.IsEmpty())
				pDC->TextOut(x + m_nBarWidth + 2, y - 14, cKittingSubline.sSiblineName1);

			if (!cKittingSubline.sSiblineName2.IsEmpty())
				pDC->TextOut(x + m_nBarWidth + 2, y + 3, cKittingSubline.sSiblineName2);
		}
	}

	pDC->SelectObject(pPenOrg);
	pDC->SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::OnToolHitTest
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ツールチップの座標をチェック
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CPoint		point			[I] マウスカーソルの位置
//		TOOLINFO	*pTI			[I] ツールチップ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		座標配列のインデックス
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された位置に四角形が存在する場合はツールチップに表示するデータをセットする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CManualEditView::OnToolHitTest(CPoint point, TOOLINFO* pTI) const
{
	SZahyoGraph *pZahyoGraph;
	CRect rect;

	CPoint pointScroll = GetScrollPosition();

	// 座標配列の中から指定された位置を含む四角形を検索
	int nSize = m_aZahyoGraph.GetSize();
	for (int i = 0; i < nSize; i++) {
		pZahyoGraph = &m_aZahyoGraph[i];
		rect = pZahyoGraph->rect - pointScroll;
		if (rect.PtInRect(point))
			break;
	}
	if (i < nSize) {
		pTI->hwnd = m_hWnd;
		pTI->uFlags &= ~TTF_IDISHWND;
		pTI->rect = rect;
		pTI->uId = i;
		pTI->lpszText = LPSTR_TEXTCALLBACK;
		return i;
	}

	return -1;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::OnGetToolTipText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ツールチップを表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		NMHDR		*pNMH			[I] NMHDRポインタ
//		LRESULT		*pRes			[I] LRESULTポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ツールチップに表示するテキストを用意する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::OnGetToolTipText(NMHDR* pNMH, LRESULT* pRes)
{
	TOOLTIPTEXT *pTTT = (TOOLTIPTEXT*)pNMH;

	// ツールチップが\nで改行するようにする
	_AFX_THREAD_STATE* pThreadState = AfxGetThreadState();
	CToolTipCtrl* pToolTip = pThreadState->m_pToolTip;
	if (pToolTip != NULL)
		pToolTip->SendMessage(TTM_SETMAXTIPWIDTH, 0, 300);

	// ツールチップに表示する文字を設定
	if ((int)pNMH->idFrom < m_aZahyoGraph.GetSize())
		pTTT->lpszText = (LPTSTR)(LPCTSTR)m_aZahyoGraph[pNMH->idFrom].sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::SetTactHeight
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		目標タクトの位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bAdjust				[I] 目標タクト位置補正フラグ
//		int		nKishuId			[I] 機種ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		目標タクトの表示位置を設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
void CManualEditView::SetScaleFactor(BOOL bAdjust, int nKishuId)
{
	CManualEditDocument *pDoc = GetDocument();

	// 最大の目標タクトを取得
	double fMaxTime = 0;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.fMokugyoTact > fMaxTime)
			fMaxTime = cGraphSubline.fMokugyoTact;
	}

	// グラフのバーが上にはみ出す場合は、目標タクトの位置を下げる
	if (bAdjust) {
		double fTime = GetMaxTime(nKishuId);
		if (fTime > fMaxTime)
			fMaxTime = fTime;
	}

	m_fScaleFactor = m_rectGraph.Height() / fMaxTime * 0.9;
}

//*****************************************************************************************************
//  1. 関数名
//		CManualEditView::GetMaxTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		最も多い作業時間を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nKishuId			[I] 機種ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		指定された機種の中で最も多い作業時間を取得する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
double CManualEditView::GetMaxTime(int nKishuId)
{
	CManualEditDocument *pDoc = GetDocument();

	double fMaxTime = 0;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];

		if (cGraphSubline.nSublineId != -1) {
			int nSagyoshaNum = cGraphSubline.aGraphSagyosha.GetSize();
			for (int i = 0; i < nSagyoshaNum; i++) {
				SGraphSagyosha &cGraphSagyosha = cGraphSubline.aGraphSagyosha[i];
				if (nKishuId < 0) {
					// 加重平均の場合
					if (cGraphSagyosha.fTime > fMaxTime)
						fMaxTime = cGraphSagyosha.fTime;

					if (nKishuId == -2) {
						int nKishuNum = cGraphSagyosha.aKishu.GetSize();
						for (int j = 0; j < nKishuNum; j++) {
							SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[j];
							if (cGraphKishu.fTime > fMaxTime)
								fMaxTime = cGraphKishu.fTime;
						}
					}
				} else {
					// 機種別の場合
					SGraphKishu &cGraphKishu = cGraphSagyosha.aKishu[nKishuId];
					if (cGraphKishu.fTime > fMaxTime)
						fMaxTime = cGraphKishu.fTime;
				}
			}
		}
	}

	return fMaxTime;
}

// スリム表示設定
void CManualEditView::SetSlimMode()
{
	CManualEditDocument *pDoc = GetDocument();

	if (pDoc->m_bSlimMode) {
		SetSlim(m_nBarWidth);
		SetSlim(m_nLeftSpacing);
		SetSlim(m_nBarSpacing);
	}
}

void CManualEditView::SetSlim(int &nScale)
{
	nScale = nScale * 3 / 5;
}

int CManualEditView::GetTotalSagyoshaNum()
{
	CManualEditDocument *pDoc = GetDocument();

	int nTotalSagyoshaNum = 0;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		if (cGraphSubline.nSublineId != -1)
			nTotalSagyoshaNum += cGraphSubline.aGraphSagyosha.GetSize();
	}

	return nTotalSagyoshaNum;
}

int CManualEditView::GetKittingSagyoshaNum()
{
	CManualEditDocument *pDoc = GetDocument();

	return pDoc->m_nKittingIndex != -1 ? pDoc->m_aGraphSubline[pDoc->m_nKittingIndex].aGraphSagyosha.GetSize() : 0;
}

void CManualEditView::DispSublineInfo()
{
	CManualEditDocument *pDoc = GetDocument();

	CString sText1, sText2, sText3;
	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SGraphSubline &cGraphSubline = pDoc->m_aGraphSubline[nSublineIndex];
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

// Modify ... ( CHANGE )
		CString str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[3].MenuNo, Menu_ManualWindowText[3].ItemNo );
		sText1.Format(str, cGraphSubline.sSublineName);
		str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[4].MenuNo, Menu_ManualWindowText[4].ItemNo );
		sText2.Format(str, cGraphSubline.fMokugyoTact, cGraphSubline.aGraphSagyosha.GetSize());
		str = g_pSystem->m_cMenuDisp.GetMenu( Menu_ManualWindowText[5].MenuNo, Menu_ManualWindowText[5].ItemNo );
		sText3.Format(str, cGraphSubline.fKadoRitsu, g_pDataManager->GetSeisanDaisu(cGraphSubline.nSublineId));
//		sText1.Format("サブライン：%s", cGraphSubline.sSublineName);
//		sText2.Format("目標タクト：%.2f　/　総作業者：%d人", cGraphSubline.fMokugyoTact, cGraphSubline.aGraphSagyosha.GetSize());
//		sText3.Format("稼働率：%.0f%%　/　生産台数：%d台", cGraphSubline.fKadoRitsu, g_pDataManager->GetSeisanDaisu(cGraphSubline.nSublineId));
// By Y.Itabashi (xxxxx) 2007.01.26
		cZahyoSubline.cSublineInfo.SetText(sText1, sText2, sText3);
	}

    m_moveHight = 0;	// 20070214 xxxxx kuwa
	SetSublineInfoPos();
}

void CManualEditView::SetSublineInfoPos()
{
	CManualEditDocument *pDoc = GetDocument();
	CPoint point = GetScrollPosition();

	CRect rectClient;
	GetClientRect(rectClient);
	rectClient.OffsetRect(point.x, 0);

	int nGraphSublineNum = pDoc->m_aGraphSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++) {
		SZahyoSubline &cZahyoSubline = m_aZahyoSubline[nSublineIndex];

		int nLeft = max(rectClient.left, cZahyoSubline.nLeft);
		int nRight = min(rectClient.right, cZahyoSubline.nRight);
		int nWidth = nRight - nLeft;

		CRect rectSublineIndo;
		cZahyoSubline.cSublineInfo.GetClientRect(rectSublineIndo);
		if (nWidth > rectSublineIndo.Width()) {
			cZahyoSubline.cSublineInfo.SetWindowPos(NULL, (nLeft + nRight - rectSublineIndo.Width()) / 2 - point.x, 5, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
			cZahyoSubline.cSublineInfo.ShowWindow(SW_SHOW);
		} else
			cZahyoSubline.cSublineInfo.ShowWindow(SW_HIDE);
	}

	// 一番下にスクロールさせる 20070213 xxxxx kuwa
	if ( m_moveHight == 0 ) {	// 縦位置を底辺に持ってきます
	   ScrollToPosition( CPoint(point.x, m_rectView.Height() - rectClient.Height()));
	} else {					// 縦位置は指定したところ
	   ScrollToPosition( CPoint(point.x,  point.y));
	}
}

void CManualEditView::HideSublineInfo()
{
	int nGraphSublineNum = m_aZahyoSubline.GetSize();
	for (int nSublineIndex = 0; nSublineIndex < nGraphSublineNum; nSublineIndex++)
		m_aZahyoSubline[nSublineIndex].cSublineInfo.ShowWindow(SW_HIDE);
}

void CManualEditView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	HideSublineInfo();

	CScrollView::OnHScroll(nSBCode, nPos, pScrollBar);

	m_moveHight = 1;	// 20070214 xxxxx kuwa
	SetSublineInfoPos();
}

void CManualEditView::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar) 
{
	HideSublineInfo();

	CScrollView::OnVScroll(nSBCode, nPos, pScrollBar);

	m_moveHight = 1;	// 20070214 xxxxx kuwa
	SetSublineInfoPos();
}

int CManualEditView::GetScaleHeight(double fMinute)
{
	return (int)(fMinute * m_fScaleFactor + 0.5);
}

// 指定した作業者を中央に表示する
void CManualEditView::DispCenterSagyosha(int nSublineId, int nSagyoshaId)
{
	int nSize = m_aZahyoSagyosha.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSagyosha &cZahyoSagyosha = m_aZahyoSagyosha[i];

		if (cZahyoSagyosha.nSublineId == nSublineId && cZahyoSagyosha.nSagyoshaId == nSagyoshaId) {
			CRect rect;
			GetClientRect(rect);
			ScrollToPosition(CPoint(cZahyoSagyosha.rect.CenterPoint().x - rect.Width() / 2, m_rectView.Height() - rect.Height()));
			break;
		}
	}
}

// 指定した位置がどのサブラインエリアか調べる
SZahyoSubline *CManualEditView::GetZahyoSubline(CPoint point)
{
	point += GetScrollPosition();

	int nSize = m_aZahyoSubline.GetSize();
	for (int i = 0; i < nSize; i++) {
		SZahyoSubline &cZahyoSubline  = m_aZahyoSubline[i];
		if (point.x > cZahyoSubline.nLeft && point.x < cZahyoSubline.nRight)
			return &cZahyoSubline;
	}

	return NULL;
}
