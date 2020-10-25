//*****************************************************************************************************
//  1. ファイル名
//		GraphWnd.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフウィンドウクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "GraphWnd.h"
#include "GraphFrm.h"
#include <math.h>

// グラフで使う色
#define COLOR_GRAPH_SCALE	RGB(0x00,0x00,0x00)
#define COLOR_GRAY			RGB(0x80,0x80,0x80)
#define COLOR_LIGHT_GRAY	RGB(0xc0,0xc0,0xc0)
#define COLOR_WHITE			RGB(0xff,0xff,0xff)
#define COLOR_ZOOM			RGB(0x00,0x00,0x00)

// マウス状態
#define MOUSE_OFF		0
#define MOUSE_LEFT		1
#define MOUSE_RIGHT		2

// Data Lineの色
const COLORREF CGraphWnd::m_aGraphColor[N_UNIT][N_GRAPH] = {
	{RGB(0x00, 0xee, 0x00), RGB(0xff, 0xee, 0x00), RGB(0xee, 0x00, 0xee), RGB(0x00, 0xee, 0xff), RGB(0xee, 0x00, 0x00)},
	{RGB(0x00, 0xbb, 0x00), RGB(0xcc, 0xbb, 0x00), RGB(0xbb, 0x00, 0xbb), RGB(0x00, 0xbb, 0xcc), RGB(0xbb, 0x00, 0x00)},
	{RGB(0x00, 0x88, 0x00), RGB(0xaa, 0x88, 0x00), RGB(0x88, 0x00, 0x88), RGB(0x00, 0x88, 0xaa), RGB(0x88, 0x00, 0x00)}
};

// CGraphWnd

IMPLEMENT_DYNAMIC(CGraphWnd, CWnd)

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::CGraphWnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CGraphWnd::CGraphWnd()
{
	m_pGraphData = NULL;
	m_sizeView = 0;
	m_bSetBitmap = FALSE;
	m_nMouseMode = MOUSE_OFF;
	m_nHighlightUnit = -1;
	m_nHighlightGraph = -1;
}


BEGIN_MESSAGE_MAP(CGraphWnd, CWnd)
	ON_WM_CREATE()
	ON_WM_PAINT()
	ON_WM_ERASEBKGND()
	ON_WM_SIZE()
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_WM_RBUTTONDOWN()
	ON_WM_RBUTTONUP()
	ON_WM_RBUTTONDBLCLK()
END_MESSAGE_MAP()



// CGraphWnd メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CREATESTRUCT		&cs			[I] ウィンドウ作成情報構造体
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
void CGraphWnd::PreSubclassWindow()
{
	// メモリデバイスコンテキストを作成
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);

	// ペンを作成
	m_penGraphScale.CreatePen(PS_SOLID, 2, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1, COLOR_LIGHT_GRAY);
	m_penZoom.CreatePen(PS_SOLID, 1, COLOR_ZOOM);
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			m_penDataLine[nUnit][nGraph].CreatePen(PS_SOLID, 1, m_aGraphColor[nUnit][nGraph]);
			m_penDataLine2[nUnit][nGraph].CreatePen(PS_SOLID, 4, m_aGraphColor[nUnit][nGraph]);
		}
	}

	// フォントを作成
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfHeight = 90;
	font.lfCharSet = DEFAULT_CHARSET;
	strcpy_s(font.lfFaceName, "MS UI Gothic");
	m_fontGraph.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = 900;
	m_fontGraph2.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = -900;
	m_fontGraph3.CreatePointFontIndirect(&font, pDC);

	ReleaseDC(pDC);

	CWnd::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CREATESTRUCT		&cs			[I] ウィンドウ作成情報構造体
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
void CGraphWnd::OnPaint()
{
	CPaintDC dc(this);

	if (m_bSetBitmap) {
		// メモリデバイスコンテキストにビットマップを設定
		CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

		// グラフのビットマップを画面に転送
		dc.BitBlt(0, 0, m_sizeView.cx, m_sizeView.cy, &m_dcGraph, 0, 0, SRCCOPY);

		// マウスでズーム中なら四角を描画
		if (m_nMouseMode == MOUSE_RIGHT) {
			CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);
			CPen *pPenOrg = (CPen *)dc.SelectObject(&m_penZoom);
			dc.Rectangle(CRect(m_pointZoom1, m_pointZoom2));
			dc.SelectObject(pPenOrg);
			dc.SelectObject(pBrushOrg);
		}

		// メモリデバイスコンテキストからビットマップを削除
		m_dcGraph.SelectObject(pBitmapOrg);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnEraseBkgnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		背景消去処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC		*pDC			[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値
//*****************************************************************************************************
BOOL CGraphWnd::OnEraseBkgnd(CDC* pDC)
{
	// 背景を白で塗りつぶす
	CRect rectClient;
	GetClientRect(rectClient);
	pDC->FillSolidRect(rectClient, RGB(255, 255, 255));

	return CWnd::OnEraseBkgnd(pDC);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウサイズ変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nType			[I] サイズ変更のタイプ
//		int		cx				[I] ウィンドウの幅
//		int		cy				[I] ウィンドウの高さ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnSize(UINT nType, int cx, int cy)
{
	CWnd::OnSize(nType, cx, cy);

	DrawGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphData	&oGraphData			[I] グラフデータ
//		SGraphLine	&oGraphLine			[I] グラフデータライン表示情報
//		LPCTSTR		fTimeRange1			[I] 表示開始時間
//		LPCTSTR		fTimeRange2			[I] 表示終了時間
//		double		fTempRange1			[I] 温度範囲下限
//		double		fTempRange2			[I] 温度範囲上限
//		double		fHeaterRange1		[I] ヒータ電圧下限
//		double		fHeaterRange2		[I] ヒータ電圧上限
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::DispGraph(CGraphData &oGraphData, SGraphLine &oGraphLine, double fTimeRange1, double fTimeRange2, double fTempRange1, double fTempRange2, double fHeaterRange1, double fHeaterRange2)
{
	m_pGraphData = &oGraphData;
	m_pGraphLine = &oGraphLine;
	m_fTimeRange1 = fTimeRange1;
	m_fTimeRange2 = fTimeRange2;
	m_fTempRange1 = fTempRange1;
	m_fTempRange2 = fTempRange2;
	m_fHeaterRange1 = fHeaterRange1;
	m_fHeaterRange2 = fHeaterRange2;

	DrawGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::DrawGraph()
{
	if (m_pGraphData == NULL)
		return;

	// データが無ければグラフを表示しない
	if (m_pGraphData->GetDataNum() == 0) {
		m_bSetBitmap = FALSE;
		Invalidate(TRUE);
		return;
	}

	// スケールのサイズを決定
	SetScaleRect();

	// ビットマップを作成
	CreateBitmap();

	// メモリデバイスコンテキストにビットマップを設定
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

	// 背景を塗りつぶす
	m_dcGraph.FillSolidRect(m_rectView, COLOR_WHITE);

	// スケールを描画
	DrawScale();

	// データの描画範囲を取得
	int nStartIndex, nEndIndex;
	GetDataRange(nStartIndex, nEndIndex);

	// リージョンを設定
	CRgn rgnGraph;
	rgnGraph.CreateRectRgn(m_rectScale.left, m_rectScale.top, m_rectScale.right, m_rectScale.bottom);
	m_dcGraph.SelectClipRgn(&rgnGraph);

	// データを描画
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			if (m_pGraphLine->bEnable[nUnit][nGraph]) {
				if (nUnit != m_nHighlightUnit || nGraph != m_nHighlightGraph)
					DrawData(nUnit, nGraph, nStartIndex, nEndIndex, m_penDataLine[nUnit][nGraph]);
			}
		}
	}
	if (m_nHighlightUnit != -1 && m_nHighlightGraph != -1)
		DrawData(m_nHighlightUnit, m_nHighlightGraph, nStartIndex, nEndIndex, m_penDataLine2[m_nHighlightUnit][m_nHighlightGraph]);

	// リージョンを解除
	m_dcGraph.SelectClipRgn(NULL);

	// メモリデバイスコンテキストからビットマップを解除
	m_dcGraph.SelectObject(pBitmapOrg);

	Invalidate(FALSE);

	m_bSetBitmap = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::SetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフスケールサイズ設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::SetScaleRect()
{
	// テキストサイズ取得
	CFont *pFontOrg = m_dcGraph.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	m_dcGraph.GetTextMetrics(&tm);
	m_dcGraph.SelectObject(pFontOrg);

	// グラフの位置と大きさ
	GetClientRect(m_rectView);
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 4 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + 10;
	m_rectScale.right = m_rectView.right - (tm.tmAveCharWidth * 4 + tm.tmHeight * 2);
	m_rectScale.bottom = m_rectView.bottom - tm.tmHeight * 3 - 2;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::CreateBitmap
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ビットマップ作成処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::CreateBitmap()
{
	CSize sizeView(m_rectView.Width(), m_rectView.Height());

	// クライアント領域のサイズに合わせてビットマップを作成
	if (m_sizeView != sizeView) {
		m_sizeView = sizeView;

		CDC *pDC = GetDC();
		m_bmpGraph.DeleteObject();
		m_bmpGraph.CreateCompatibleBitmap(pDC, m_sizeView.cx, m_sizeView.cy);
		ReleaseDC(pDC);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::GetDataRange
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		&nStartIndex		[I/O] グラフデータ
//		int		&nEndIndex			[I/O] グラフデータライン表示情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::GetDataRange(int &nStartIndex, int &nEndIndex)
{
	int nDataNum = m_pGraphData->GetDataNum();
	nStartIndex = 0;
	nEndIndex = nDataNum;

	// 指定された時間範囲のデータのインデックスを求める
	for (int i = 0; i < nDataNum; i++) {
		if ((double)m_pGraphData->GetGraphData(i).oTimestamp.GetTime() < m_fTimeRange1)
			nStartIndex = i;

		if ((double)m_pGraphData->GetGraphData(i).oTimestamp.GetTime() < m_fTimeRange2)
			nEndIndex = i + 1;
	}

	if (nEndIndex < nDataNum)
		nEndIndex++;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフスケール描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::DrawScale()
{
	CString sText;
	CSize sizeText;
	int x, y;
	double fStep;
	double t;
	int ns;

	// 描画オブジェクトを設定
	CPen *pPenOrg = m_dcGraph.SelectObject(&m_penGraphScale);
	CFont *pFontOrg = m_dcGraph.SelectObject(&m_fontGraph);
	CBrush *pBrushOrg = (CBrush *)m_dcGraph.SelectStockObject(NULL_BRUSH);

	// テキストサイズ取得
	TEXTMETRIC tm;
	m_dcGraph.GetTextMetrics(&tm);

	// 横軸を描画
	double fTimeSpan = m_fTimeRange2 - m_fTimeRange1;
	fStep = (int)GetLinearScaleStep(fTimeSpan, m_sizeView.cx / 120, &ns);
	if (fStep == 0) {
		fStep = 1;
		ns = 1;
	}
	for (t = floor(m_fTimeRange1 / fStep) * fStep; t < m_fTimeRange2 + fStep / 2; t += fStep) {
		x = m_rectScale.left + int((t - m_fTimeRange1) / fTimeSpan * m_rectScale.Width() + 0.5);
		if (x >= m_rectScale.left && x <= m_rectScale.right) {
			if (int(t / fStep + 0.5) % ns == 0) {
				if (x > m_rectScale.left) {
					m_dcGraph.SelectObject(&m_penGray);
					m_dcGraph.MoveTo(x, m_rectScale.top);
					m_dcGraph.LineTo(x, m_rectScale.bottom);
				}
				sText = CTime((time_t)t).FormatGmt("%H:%M:%S");
				sizeText = m_dcGraph.GetOutputTextExtent(sText);
				m_dcGraph.TextOut(x - sizeText.cx / 2, m_rectScale.bottom + tm.tmHeight / 2, sText);
			} else {
				if (x > m_rectScale.left) {
					m_dcGraph.SelectObject(&m_penLightGray);
					m_dcGraph.MoveTo(x, m_rectScale.top);
					m_dcGraph.LineTo(x, m_rectScale.bottom);
				}
			}
		}
	}

	// 縦軸を描画
	double fTempSpan = m_fTempRange2 - m_fTempRange1;
	double fHeaterSpan = m_fHeaterRange2 - m_fHeaterRange1;
	fStep = GetLinearScaleStep(fTempSpan, m_sizeView.cy / 40, &ns);
	for (t = floor(m_fTempRange1 / fStep) * fStep; t < m_fTempRange2 + fStep / 2; t += fStep) {
		if (fabs(t) < fStep / 2)
			t = 0;

		y = m_rectScale.bottom - int((t - m_fTempRange1) / fTempSpan * m_rectScale.Height() + 0.5);

		if (y >= m_rectScale.top && y <= m_rectScale.bottom) {
			if (int(fabs(t) / fStep + 0.5) % ns == 0) {
				if (y >= m_rectScale.top) {
					if (t < m_fTempRange1 + fStep / 2 || t > m_fTempRange1 + fTempSpan - fStep / 2)
						m_dcGraph.SelectObject(&m_penGraphScale);
					else
						m_dcGraph.SelectObject(&m_penGray);
					m_dcGraph.MoveTo(m_rectScale.left, y);
					m_dcGraph.LineTo(m_rectScale.right, y);
				}

				sText.Format("%g", (int)(t * 10 + 0.5) / 10.0);
				sizeText = m_dcGraph.GetOutputTextExtent(sText);
				m_dcGraph.TextOut(m_rectScale.left - sizeText.cx - tm.tmAveCharWidth, y - sizeText.cy / 2, sText);

				sText.Format("%g", (int)((m_fHeaterRange1 + (t - m_fTempRange1) / fTempSpan * fHeaterSpan) * 10 + 0.5) / 10.0);
				sizeText = m_dcGraph.GetOutputTextExtent(sText);
				m_dcGraph.TextOut(m_rectScale.right + tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
			} else {
				m_dcGraph.SelectObject(&m_penLightGray);
				m_dcGraph.MoveTo(m_rectScale.left, y);
				m_dcGraph.LineTo(m_rectScale.right, y);
			}
		}
	}

	CRect rectClinet;
	GetClientRect(rectClinet);

	// 外枠を描画
	m_dcGraph.SelectObject(&m_penGraphScale);
	CRect rectScale2(m_rectScale);
	rectScale2.InflateRect(1, 0, 2, 1);
	m_dcGraph.Rectangle(&rectScale2);

	// 時間軸の文字を描画
	sText = "Time [hh:mm:ss](GMT)";
	sizeText = m_dcGraph.GetOutputTextExtent(sText);
	m_dcGraph.TextOut(m_rectScale.left + (m_rectScale.Width() - sizeText.cx) / 2, rectClinet.bottom - (sizeText.cy + 4), sText);

	// 温度軸の文字を描画
	m_dcGraph.SelectObject(&m_fontGraph2);
	sText = "Temperature [degC]";
	sizeText = m_dcGraph.GetOutputTextExtent(sText);
	m_dcGraph.TextOut(rectClinet.left + 3, m_rectScale.top + (m_rectScale.Height() + sizeText.cx) / 2, sText);

	// ヒータ電圧軸の文字を描画
	m_dcGraph.SelectObject(&m_fontGraph3);
	sText = "Heater Voltage [V]";
	sizeText = m_dcGraph.GetOutputTextExtent(sText);
	m_dcGraph.TextOut(rectClinet.right - 3, m_rectScale.top + (m_rectScale.Height() - sizeText.cx) / 2, sText);

	// 描画オブジェクトを元に戻す
	m_dcGraph.SelectObject(pPenOrg);
	m_dcGraph.SelectObject(pFontOrg);
	m_dcGraph.SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::DrawData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nUnit			[I] ユニット番号
//		int		nGraph			[I] グラフ種類番号
//		int		nStartIndex		[I] データ開始インデックス
//		int		nEndIndex		[I] データ終了インデックス
//		CPen	&oPen			[I] データラインを描画するペン
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::DrawData(int nUnit, int nGraph, int nStartIndex, int nEndIndex, CPen &oPen)
{
	int i;
	int nX;
	int nX2 = INT_MAX;
	int nY = 0;
	int nY2 = INT_MAX;
	int nMinY = INT_MAX;
	int nMaxY = INT_MIN;
	int nGraphPoint = 0;
	POINT *pGraphPoint;
	double fRange1;
	double fRange2;

	if (nGraph == GRAPH_HEATER) {
		fRange1 = m_fHeaterRange1;
		fRange2 = m_fHeaterRange2;
	} else {
		fRange1 = m_fTempRange1;
		fRange2 = m_fTempRange2;
	}

	// ポリライン用配列初期化
	int nPointSize = (m_rectScale.Width() + 2) * 4;
	pGraphPoint = new POINT[nPointSize];

	// ポリラインを計算
	int nDataNum = m_pGraphData->GetDataNum();
	for (i = nStartIndex; i < nEndIndex; i++) {
		SGraphData &oGraphData = m_pGraphData->GetGraphData(i);

		nX = m_rectScale.left + (int)(((double)oGraphData.oTimestamp.GetTime() - m_fTimeRange1) / (m_fTimeRange2 - m_fTimeRange1) * m_rectScale.Width() + 0.5);
		nY = m_rectScale.bottom - (int)((oGraphData.aUnit[nUnit].aData[nGraph] - fRange1) / (fRange2 - fRange1) * m_rectScale.Height() + 0.5);

		if (nX < 0)
			nX = 0;

		if (nX2 == INT_MAX)
			nX2 = nX;

		if (nX != nX2) {
			pGraphPoint[nGraphPoint].x = nX2;
			pGraphPoint[nGraphPoint].y = nMinY;
			nGraphPoint++;
			if (nMaxY != nMinY) {
				pGraphPoint[nGraphPoint].x = nX2;
				pGraphPoint[nGraphPoint].y = nMaxY;
				nGraphPoint++;
			}

			if (nX > nX2 + 1) {
				pGraphPoint[nGraphPoint].x = nX2;
				pGraphPoint[nGraphPoint].y = nY2;
				nGraphPoint++;

				pGraphPoint[nGraphPoint].x = nX;
				pGraphPoint[nGraphPoint].y = nY;
				nGraphPoint++;
			}

			nMinY = INT_MAX;
			nMaxY = INT_MIN;

			nX2 = nX;
		}

		nY2 = nY;

		if (nY < nMinY)
			nMinY = nY;
		if (nY > nMaxY)
			nMaxY = nY;
	}

	if (nGraphPoint != 0) {
		pGraphPoint[nGraphPoint].x = nX;
		pGraphPoint[nGraphPoint].y = nY;
		nGraphPoint++;
	}

	// ペンを設定
	CPen *pPenOrg = m_dcGraph.SelectObject(&oPen);

	// ポリラインを描画
	m_dcGraph.Polyline(pGraphPoint, nGraphPoint);

	// ペンを戻す
	m_dcGraph.SelectObject(pPenOrg);

	// ポリライン用配列解放
	delete [] pGraphPoint;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::GetLinearScaleStep
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リニアスケールの目盛り線のステップを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fRange		[I] スケールのレンジ幅（最大値と最小値の差）
//		int			nPixel		[I] スケールのピクセル数
//		int			*pStep		[O] ステップの幅
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		double		１目盛り間のステップ値
//*****************************************************************************************************
double CGraphWnd::GetLinearScaleStep(double fRange, int nPixel, int *pStep)
{
	double t1, t2;
	int nStep;

	if (fRange <= 0)
		return 1;

	t1 = fRange / nPixel;
	double fStep = pow(10.0, floor(log10(t1)));
	t2 = t1 / fStep;
	if (t2 < 2) {
		fStep *= 0.5;
		nStep = 2;
	} else if (t2 < 5)
		nStep = 2;
	else
		nStep = 5;

	if (pStep != NULL)
		*pStep = nStep;

	return fStep;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		水平スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar	*pScrollBar		[I] スクロールバーコントロールへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// 現在のスクロール情報を取得
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	GetScrollInfo(SB_HORZ, &si);
	int nPageSize = si.nPage;
	int nLineSize = nPageSize / 32;
	if (nLineSize == 0)
		nLineSize = 1;

	// スクロール位置を更新
	switch (nSBCode) {
	case SB_LEFT:
		nPos = 0;
		break;
	case SB_LINELEFT:
		nPos = si.nPos - nLineSize;
		break;
	case SB_LINERIGHT:
		nPos = si.nPos + nLineSize;
		break;
	case SB_PAGELEFT:
		nPos = si.nPos - nPageSize;
		break;
	case SB_PAGERIGHT:
		nPos = si.nPos + nPageSize;
		break;
	case SB_RIGHT:
		nPos = si.nMax - si.nPage;
		break;
	case SB_THUMBPOSITION:
	case SB_THUMBTRACK:
		nPos = si.nTrackPos;
		break;
	default:
		return;
	}

	if (nPos < 0)
		nPos = 0;

	if ((int)nPos > si.nMax - nPageSize)
		nPos = si.nMax - nPageSize;

	// スクロール位置をフレームウィンドウに通知
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
	pGraphFrm->ScrollTime((double)nPos / si.nMax);

	CWnd::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnVScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		垂直スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar	*pScrollBar		[I] スクロールバーコントロールへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// 現在のスクロール情報を取得
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	GetScrollInfo(SB_VERT, &si);
	int nPageSize = si.nPage;
	int nLineSize = nPageSize / 32;
	if (nLineSize == 0)
		nLineSize = 1;

	// スクロール位置を更新
	switch (nSBCode) {
	case SB_LEFT:
		nPos = 0;
		break;
	case SB_LINELEFT:
		nPos = si.nPos - nLineSize;
		break;
	case SB_LINERIGHT:
		nPos = si.nPos + nLineSize;
		break;
	case SB_PAGELEFT:
		nPos = si.nPos - nPageSize;
		break;
	case SB_PAGERIGHT:
		nPos = si.nPos + nPageSize;
		break;
	case SB_RIGHT:
		nPos = si.nMax - si.nPage;
		break;
	case SB_THUMBPOSITION:
	case SB_THUMBTRACK:
		nPos = si.nTrackPos;
		break;
	default:
		return;
	}

	if (nPos < 0)
		nPos = 0;

	if ((int)nPos > si.nMax - nPageSize)
		nPos = si.nMax - nPageSize;

	// スクロール位置をフレームウィンドウに通知
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
	pGraphFrm->ScrollLevel((double)(si.nMax - si.nPage - nPos) / si.nMax);

	CWnd::OnVScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンダウン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnLButtonDown(UINT nFlags, CPoint point)
{
	if (m_pGraphData != NULL && m_nMouseMode == MOUSE_OFF) {
		m_nMouseMode = MOUSE_LEFT;

		// マウスポインタ位置を保存
		m_pointScroll = point;

		// マウスをキャプチャ
		SetCapture();

		// マウスカーソルを設定
		m_hCursorSave = ::SetCursor(AfxGetApp()->LoadCursor(IDC_CURSOR_HAND_CLOSE));
	}

	CWnd::OnLButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		左マウスボタンアップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnLButtonUp(UINT nFlags, CPoint point)
{
	if (m_nMouseMode == MOUSE_LEFT) {
		m_nMouseMode = MOUSE_OFF;

		// マウスをリリース
		if (GetCapture()->GetSafeHwnd() == m_hWnd)
			ReleaseCapture();

		// マウスカーソルを戻す
		::SetCursor(m_hCursorSave);
	}

	CWnd::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウス移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnMouseMove(UINT nFlags, CPoint point)
{
	switch (m_nMouseMode) {
	case MOUSE_LEFT:
		// スクロール位置をフレームウィンドウに通知
		((CGraphFrm *)GetParent())->MouseScroll((double)(m_pointScroll.x - point.x) / m_rectScale.Width(), (double)(point.y - m_pointScroll.y) / m_rectScale.Height());
		m_pointScroll = point;
		break;
	case MOUSE_RIGHT:
		// ズーム範囲を保存
		if (m_rectScale.PtInRect(point)) {
			m_pointZoom2 = point;
			Invalidate(FALSE);
		}
		break;
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnRButtonDown
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右マウスボタンダウン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnRButtonDown(UINT nFlags, CPoint point)
{
	if (m_pGraphData != NULL && m_nMouseMode == MOUSE_OFF) {
		if (m_rectScale.PtInRect(point)) {
			m_nMouseMode = MOUSE_RIGHT;

			// マウスポインタ位置を保存
			m_pointZoom1 = m_pointZoom2 = point;

			// マウスをキャプチャ
			SetCapture();
		}
	}

	CWnd::OnRButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnRButtonUp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右マウスボタンアップ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnRButtonUp(UINT nFlags, CPoint point)
{
	if (m_nMouseMode == MOUSE_RIGHT) {
		m_nMouseMode = MOUSE_OFF;

		// マウスをリリース
		if (GetCapture()->GetSafeHwnd() == m_hWnd)
			ReleaseCapture();

		// ズーム範囲をフレームウィンドウに通知
		if (m_pointZoom1.x != m_pointZoom2.x && m_pointZoom1.y != m_pointZoom2.y) {
			CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
			pGraphFrm->MouseZoom(
					(double)(m_pointZoom1.x - m_rectScale.left) / m_rectScale.Width(),
					(double)(m_pointZoom2.x - m_rectScale.left) / m_rectScale.Width(),
					(double)(m_rectScale.bottom - m_pointZoom1.y) / m_rectScale.Height(),
					(double)(m_rectScale.bottom - m_pointZoom2.y) / m_rectScale.Height());

		}

		Invalidate(FALSE);
	}

	CWnd::OnRButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::OnRButtonDblClk
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		右マウスボタンダブルクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		CPoint		point			[I] カーソルの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::OnRButtonDblClk(UINT nFlags, CPoint point)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	// ズーム解除
	pGraphFrm->ZoomReset();

	CWnd::OnRButtonDblClk(nFlags, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphWnd::SetHighlight
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		強調表示するデータラインの設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nUnit			[I] ユニット番号
//		int		nGraph			[I] グラフ種類番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphWnd::SetHighlight(int nUnit, int nGraph)
{
	m_nHighlightUnit = nUnit;
	m_nHighlightGraph = nGraph;
}
