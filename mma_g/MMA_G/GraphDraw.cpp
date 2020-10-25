//*****************************************************************************************************
//  1. ファイル名
//		GraphDraw.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータグラフの描画クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		画面表示と印刷の描画処理は共通であるため、両方で利用できるように独立したクラスとした。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "GraphDraw.h"
#include "GraphData.h"
#include "Define.h"
#include "General.h"
#include "DataDateTime.h"
#include <math.h>

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::CGraphDraw
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			*pDC			[I] デバイスコンテキストへのポインタ
//		int			nPenWidth		[I] ペンの太さ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CGraphDraw::CGraphDraw(CDC *pDC, int nPenWidth)
{
	// ペンを作成
	m_penGraphLineX.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_X);
	m_penGraphLineY.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_Y);
	m_penGraphLineZ.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_Z);
	m_penGraphLineD.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_D);
	m_penGraphScale.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_LIGHT_GRAY);
	m_penCursor.CreatePen(PS_DOT, 1 * nPenWidth, COLOR_GRAPH_CURSOR);
	m_penSelect.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_SELECT);

	// ブラシを作成
	m_brushSelect.CreateSolidBrush(COLOR_GRAPH_SELECT);
	m_brushGraphLineX.CreateSolidBrush(COLOR_GRAPH_DATA_X);
	m_brushGraphLineY.CreateSolidBrush(COLOR_GRAPH_DATA_Y);
	m_brushGraphLineZ.CreateSolidBrush(COLOR_GRAPH_DATA_Z);
	m_brushGraphLineD.CreateSolidBrush(COLOR_GRAPH_DATA_D);

	// フォントを作成
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfHeight = 90;
	font.lfCharSet = DEFAULT_CHARSET;
	strcpy_s(font.lfFaceName, "MS UI Gothic");
	m_fontGraph.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = 900;
	m_fontGraph2.CreatePointFontIndirect(&font, pDC);

	// メンバ変数初期化
	m_fSelectBegin = 0;
	m_fSelectEnd = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CRect		rectView			[I] 描画範囲
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		BOOL		bDrawSelect			[I] TRUE：選択範囲を描画する　FALSE：選択範囲を描画しない
//		int			nGraphKind			[I] グラフの種類（加速度 or 温度）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawGraph(CDC &dc, CRect rectView, const CGraphData &cGraphData, BOOL bDrawSelect, int nGraphKind)
{
	// ビューサイズ保存
	m_rectView = rectView;

	// スケールのサイズを決定
	SetScaleRect(dc, (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) ? 3 : 1);

	// 背景を塗りつぶす
	dc.FillSolidRect(m_rectView, COLOR_WHITE);

	// 透過モードを設定
	dc.SetBkMode(TRANSPARENT);

	// スケールとデータを描画
	switch (nGraphKind) {
	case GRAPH_ACCEL:	// 加速度グラフ
		// スケールを描画
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, "X-axis Acceleration [μG]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, "Y-axis Acceleration [μG]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, "Z-axis Acceleration [μG]", 4);

		// データを描画
		DrawData(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataX, m_penGraphLineX, m_brushGraphLineX);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, cGraphData.m_pFilterDataY, m_penGraphLineY, m_brushGraphLineY);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, cGraphData.m_pFilterDataZ, m_penGraphLineZ, m_brushGraphLineZ);
		break;
	case GRAPH_TEMP:	// 温度グラフ
		// スケールを描画
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, "Temperature [℃]", 8);

		// データを描画
		if (cGraphData.m_bDispX)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataX, m_penGraphLineX, m_brushGraphLineX);
		if (cGraphData.m_bDispY)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataY, m_penGraphLineY, m_brushGraphLineY);
		if (cGraphData.m_bDispZ)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataZ, m_penGraphLineZ, m_brushGraphLineZ);
		if (cGraphData.m_bDispD)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataD, m_penGraphLineD, m_brushGraphLineD);
		break;
	case GRAPH_ZERO_OFFSET:	// 0点補正値グラフ
		// スケールを描画
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, "X-axis Zero Offset [μG]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, "Y-axis Zero Offset [μG]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, "Z-axis Zero Offset [μG]", 4);

		// データを描画
		DrawData(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataX, m_penGraphLineX, m_brushGraphLineX);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, cGraphData.m_pFilterDataY, m_penGraphLineY, m_brushGraphLineY);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, cGraphData.m_pFilterDataZ, m_penGraphLineZ, m_brushGraphLineZ);
		break;
	}

	// 選択範囲を表示
	if (bDrawSelect) {
		m_fSelectBegin = 0;
		m_fSelectEnd = 0;
		DrawSelect(dc, cGraphData, nGraphKind);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::SetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフスケールサイズ設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		int			nScaleNum			[I] スケールの個数
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::SetScaleRect(CDC &dc, int nScaleNum)
{
	// テキストサイズ取得
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);
	dc.SelectObject(pFontOrg);

	// ひとつのグラフの高さ
	m_nScaleHeight = (m_rectView.Height() - tm.tmHeight) / nScaleNum;

	// ひとつのグラフの位置と大きさ
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 8 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + 10;
	m_rectScale.right = m_rectView.right - 30;
	m_rectScale.bottom = m_rectView.top + m_nScaleHeight - tm.tmHeight * 2;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スケール描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		CRect		rectScale			[I] スケールの位置
//		SDispRange	&stLevelRange		[I] レベルレンジ
//		LPCTSTR		pXYZ				[I] 縦軸名
//		int			nYAxis				[I] 縦軸目盛の密度
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawScale(CDC &dc, const CGraphData &cGraphData, CRect rectScale, const SDispRange &stLevelRange, LPCTSTR pXYZ, int nYAxis)
{
	CString sText;
	CSize sizeText;
	int x, y;
	double fStep, t;
	int ns;

	// 描画オブジェクトを設定
	CPen *pPenOrg = dc.SelectObject(&m_penGraphScale);
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);

	// テキストサイズ取得
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// 横軸を描画
	fStep = GetLinearScaleStep(cGraphData.m_stTimeRange.fDisp, 4, &ns);
	for (t = floor(cGraphData.m_stTimeRange.fBegin / fStep) * fStep; t <= cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp; t += fStep) {
		x = rectScale.left + int((t - cGraphData.m_stTimeRange.fBegin) * rectScale.Width() / cGraphData.m_stTimeRange.fDisp);
		if (x >= rectScale.left && x <= rectScale.right) {
			if ((int)(t / fStep + 0.5) % ns == 0) {
				if (x > rectScale.left) {
					dc.SelectObject(&m_penGray);
					dc.MoveTo(x, rectScale.top);
					dc.LineTo(x, rectScale.bottom);
				}
				CDataDateTime dTime;
				dTime.SetTime(cGraphData.m_dInStarttime.m_YY
					, cGraphData.m_dInStarttime.m_MM
					, cGraphData.m_dInStarttime.m_DD
					, (int)((t+0.0001) * 1000));
					// 1000倍するとき0.49999等の数値のために0.0001プラスしておく
				if (fStep < 0.5)
					sText = dTime.GetStrHMST();
				else
					sText = dTime.GetStrHMS();
				sizeText = dc.GetOutputTextExtent(sText);
				dc.TextOut(x - sizeText.cx / 2, rectScale.bottom + tm.tmHeight / 2, sText);
			} else {
				if (x > rectScale.left) {
					dc.SelectObject(&m_penLightGray);
					dc.MoveTo(x, rectScale.top);
					dc.LineTo(x, rectScale.bottom);
				}
			}
		}
	}

	// 縦軸を描画
	fStep = GetLinearScaleStep(stLevelRange.fDisp, nYAxis, &ns);
	for (t = floor(stLevelRange.fBegin / fStep) * fStep; t < stLevelRange.fBegin + stLevelRange.fDisp + fStep / 2; t += fStep) {
		if (fabs(t) < fStep / 2)
			t = 0;

		y = rectScale.bottom - int((t - stLevelRange.fBegin) * rectScale.Height() / stLevelRange.fDisp);

		if (y >= rectScale.top && y <= rectScale.bottom) {
			if (int(fabs(t) / fStep + 0.5) % ns == 0) {
				if (y >= rectScale.top) {
					if (t < stLevelRange.fBegin + fStep / 2 || t > stLevelRange.fBegin + stLevelRange.fDisp - fStep / 2)
						dc.SelectObject(&m_penGraphScale);
					else
						dc.SelectObject(&m_penGray);
					dc.MoveTo(rectScale.left, y);
					dc.LineTo(rectScale.right, y);
				}
				sText.Format("%g", t);
				sizeText = dc.GetOutputTextExtent(sText);
				dc.TextOut(rectScale.left - sizeText.cx - tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
			} else {
				dc.SelectObject(&m_penLightGray);
				dc.MoveTo(rectScale.left, y);
				dc.LineTo(rectScale.right, y);
			}
		}
	}

	// 外枠を描画
	dc.SelectObject(&m_penGraphScale);
	CRect rectScale2(rectScale);
	rectScale2.InflateRect(1, 1, 2, 2);
	dc.Rectangle(&rectScale2);

	// 横軸の文字を描画
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "時間";
	sText = "Time [hh:mm:ss](GMT)";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(rectScale.left + (rectScale.Width() - sizeText.cx) / 2, m_rectView.bottom - (sizeText.cy + 4), sText);

	// 縦軸の文字を描画
	dc.SelectObject(&m_fontGraph2);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText.Format("%s軸 加速度 [μG]", pXYZ);
//	sText.Format("%s-axis Acceleration [μG]", pXYZ);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(pXYZ);
	dc.TextOut(m_rectView.left + 3, rectScale.top + (rectScale.Height() + sizeText.cx) / 2, pXYZ);

	// 描画オブジェクトを元に戻す
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pFontOrg);
	dc.SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		CRect		rectScale			[I] スケールの位置
//		SDispRange	&stLevelRange		[I] 表示範囲情報
//		double		*pData				[I] バイナリデータへのポインタ
//		CPen		&penGraphLine		[I] 描画に使用するペン
//		CBrush		&brushGraphLine		[I] 描画に使用するブラシ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawData(CDC &dc, const CGraphData &cGraphData, CRect rectScale, const SDispRange &stLevelRange, const double *pData, CPen &penGraphLine, CBrush &brushGraphLine)
{
	int i;
	int x, x2;
	int y, ymin, ymax;
	int nSampleIndex;
	int nDataIndex;
	int nGraphPoint = 0;

	if (pData == NULL)
		return;

	double fSampleRate = cGraphData.m_fSampleRate / cGraphData.m_nDownRate;
	double fBeginOffset = cGraphData.m_stTimeRange.fBegin - cGraphData.m_stTimeRange.fMin;
	int nBeginPos = (int)(fBeginOffset * fSampleRate);
	int nDispSize = (int)(min(cGraphData.m_stTimeRange.fDisp, cGraphData.m_fTimeDispEnd - fBeginOffset) * fSampleRate) + 3;
	if (nDispSize <= 0)
		return;

	// ポリライン用配列確保
	int nPointSize = rectScale.Width() * 2 + 6;
	POINT *pGraphPoint = new POINT[nPointSize];

	// ポリラインを計算
	double fSampleStep = 1 / fSampleRate;
	double fWidthStep = rectScale.Width() / cGraphData.m_stTimeRange.fDisp;
	int nDataStep = nDispSize / 100000 + 1;
	nBeginPos = nBeginPos / nDataStep * nDataStep;
	x2 = 0;
	ymin = INT_MAX;
	ymax = -INT_MAX;
	for (i = 0; i < nDispSize; i += nDataStep) {
		nSampleIndex = nBeginPos + i;
		x = (int)((nSampleIndex * fSampleStep + cGraphData.m_stTimeRange.fMin - cGraphData.m_stTimeRange.fBegin) * fWidthStep + 0.5) + rectScale.left;

		if (x2 == 0)
			x2 = x;

		if (x != x2) {
			if (nGraphPoint >= nPointSize - 1)
				break;

			pGraphPoint[nGraphPoint].x = x2;
			pGraphPoint[nGraphPoint].y = ymin;
			nGraphPoint++;
			if (ymax != ymin) {
				pGraphPoint[nGraphPoint].x = x2;
				pGraphPoint[nGraphPoint].y = ymax;
				nGraphPoint++;
			}
			ymin = INT_MAX;
			ymax = -INT_MAX;
			x2 = x;
		}

		nDataIndex = nSampleIndex * cGraphData.m_nDownRate;
		if (nDataIndex >= cGraphData.m_nDataSize)
			break;

		y = (int)(rectScale.bottom - (pData[nDataIndex] - stLevelRange.fBegin) / stLevelRange.fDisp * rectScale.Height());
		if (y < ymin)
			ymin = y;
		if (y > ymax)
			ymax = y;
	}

	// リージョンを設定
	CRgn rgnGraph;
	rgnGraph.CreateRectRgn(rectScale.left, rectScale.top, rectScale.right, rectScale.bottom);
	dc.SelectClipRgn(&rgnGraph);

	// ペンを設定
	CPen *pPenOrg = dc.SelectObject(&penGraphLine);

	// ポリラインを描画
	dc.Polyline(pGraphPoint, nGraphPoint);

	// データ位置にドットを表示
	if (nGraphPoint < (x2 - rectScale.left) / 10) {
		CBrush *pBrushOrg = dc.SelectObject(&brushGraphLine);

		POINT *pPoint;
		for (i = 0, pPoint = pGraphPoint; i < nGraphPoint; i++, pPoint++)
			dc.Ellipse(pPoint->x - 2, pPoint->y - 2, pPoint->x + 3, pPoint->y + 3);

		dc.SelectObject(pBrushOrg);
	}

	// ペンを戻す
	dc.SelectObject(pPenOrg);

	// リージョンを解除
	dc.SelectClipRgn(NULL);

	// ポリライン用配列解放
	delete [] pGraphPoint;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::GetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		X,Y,Z軸のグラフの位置を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nScaleNo			[I] X,Y,Z軸の種別
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CRect	スケールの位置
//*****************************************************************************************************
CRect CGraphDraw::GetScaleRect(int nScaleNo)
{
	if (nScaleNo == SCALE_TEMP)
		return m_rectScale;
	else
		return m_rectScale + CPoint(0, m_nScaleHeight * nScaleNo);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		X,Y,Z軸の時間カーソルを描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		int			nGraphKind			[I] グラフの種類（加速度 or 温度）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawTimeCursor(CDC &dc, const CGraphData &cGraphData, int nGraphKind)
{
	DrawTimeCursorSub(dc, cGraphData, GetScaleRect(SCALE_X));
	if (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) {
		DrawTimeCursorSub(dc, cGraphData, GetScaleRect(SCALE_Y));
		DrawTimeCursorSub(dc, cGraphData, GetScaleRect(SCALE_Z));
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawTimeCursorSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間カーソルを描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		CRect		rectScale			[I] スケール位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawTimeCursorSub(CDC &dc, const CGraphData &cGraphData, CRect rectScale)
{
	int x = rectScale.left + (int)((cGraphData.m_fTimeCursor - cGraphData.m_stTimeRange.fBegin) / cGraphData.m_stTimeRange.fDisp * rectScale.Width());

	if (x >= rectScale.left && x <= rectScale.right) {
		CPen *pPenOrg = dc.SelectObject(&m_penCursor);

		dc.MoveTo(x, rectScale.top);
		dc.LineTo(x, rectScale.bottom);
		x++;
		dc.MoveTo(x, rectScale.top);
		dc.LineTo(x, rectScale.bottom);

		dc.SelectObject(pPenOrg);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawSelect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		X,Y,Z軸の選択範囲を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		int			nGraphKind			[I] グラフの種類（加速度 or 温度）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawSelect(CDC &dc, const CGraphData &cGraphData, int nGraphKind)
{
	// 描画モードを設定
	int ropModeOrg = dc.SetROP2(R2_NOTXORPEN);

	// 描画オブジェクトを設定
	CPen *pPenOrg = dc.SelectObject(&m_penSelect);
	CBrush *pBrushOrg = dc.SelectObject(&m_brushSelect);

	// 以前の選択範囲を消す
	DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_X));
	if (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) {
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Y));
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Z));
	}

	// 選択範囲を保存
	m_fSelectBegin = cGraphData.m_fSelectBegin;
	m_fSelectEnd = cGraphData.m_fSelectEnd;

	// 選択範囲を描画
	DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_X));
	if (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) {
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Y));
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Z));
	}

	// 描画オブジェクトを戻す
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pBrushOrg);

	// 描画モードを戻す
	dc.SetROP2(ropModeOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DrawSelectSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		選択範囲を描画
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CGraphData	&cGraphData			[I] グラフデータクラス
//		CRect		rectScale			[I] スケール位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DrawSelectSub(CDC &dc, const CGraphData &cGraphData, CRect rectScale)
{
	int x1, x2;

	if (m_fSelectBegin != m_fSelectEnd) {
		x1 = (int)((m_fSelectBegin - cGraphData.m_stTimeRange.fBegin) / cGraphData.m_stTimeRange.fDisp * rectScale.Width() + 0.5) + rectScale.left;
		x2 = (int)((m_fSelectEnd - cGraphData.m_stTimeRange.fBegin) / cGraphData.m_stTimeRange.fDisp * rectScale.Width() + 0.5) + rectScale.left;

		if (x1 < rectScale.right && x2 > rectScale.left) {
			if (x1 < rectScale.left)
				x1  = rectScale.left;

			if (x2 > rectScale.right)
				x2 = rectScale.right;

			dc.Rectangle(x1, rectScale.top, x2, rectScale.bottom);
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DispScrollBar
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		X,Y,Z軸グラフのスクロールバーを表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CScrollBar		&cScrollBarX			[I] X軸グラフのスクロールバー
//		CScrollBar		&cScrollBarY			[I] Y軸グラフのスクロールバー
//		CScrollBar		&cScrollBarZ			[I] Z軸グラフのスクロールバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DispScrollBar(CScrollBar &cScrollBarX, CScrollBar &cScrollBarY, CScrollBar &cScrollBarZ)
{
	DispScrollBarSub(cScrollBarX, GetScaleRect(SCALE_X));
	DispScrollBarSub(cScrollBarY, GetScaleRect(SCALE_Y));
	DispScrollBarSub(cScrollBarZ, GetScaleRect(SCALE_Z));
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::DispScrollBarSub
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフのスクロールバーを表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CScrollBar		&cScrollBar			[I] グラフのスクロールバー
//		CRect			rectScale			[I] スクロールバーの表示位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphDraw::DispScrollBarSub(CScrollBar &cScrollBar, CRect rectScale)
{
	if (cScrollBar.m_hWnd) {
		int nWidth = ::GetSystemMetrics(SM_CXVSCROLL);
		cScrollBar.MoveWindow(rectScale.right + 2, rectScale.top, nWidth, rectScale.Height());
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphDraw::CheckGraphPoint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定した位置がどのグラフ内であるかをチェックする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CPoint			point			[I] チェックする位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int			0:グラフ外　1:X軸グラフ内　2:Y軸グラフ内　3:Z軸グラフ内
//*****************************************************************************************************
int CGraphDraw::CheckGraphPoint(CPoint point)
{
	if (GetScaleRect(SCALE_X).PtInRect(point))
		return 1;
	else if (GetScaleRect(SCALE_Y).PtInRect(point))
		return 2;
	else if (GetScaleRect(SCALE_Z).PtInRect(point))
		return 3;
	else
		return 0;
}
