//*****************************************************************************************************
//  1. ファイル名
//		.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		1/3オクターブ解析グラフの描画クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		画面表示と印刷の描画処理は共通であるため、両方で利用できるように独立したクラスとした。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "FftOctaveDraw.h"
#include "Define.h"
#include <math.h>

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDraw::CFftOctaveDraw
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
CFftOctaveDraw::CFftOctaveDraw(CDC *pDC, int nPenWidth)
{
	// ペンの作成
	m_penGraphDataX.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_DATA_X);
	m_penGraphDataY.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_DATA_Y);
	m_penGraphDataZ.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_DATA_Z);
	m_penGraphDataG.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_DATA_G);
	m_penScale.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_LIGHT_GRAY);
	m_penGraphLimit.CreatePen(PS_SOLID, 3 * nPenWidth, COLOR_LIMIT_LINE);

	// フォントの作成
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfCharSet = DEFAULT_CHARSET;
	strcpy_s(font.lfFaceName, "MS UI Gothic");
	font.lfHeight = 90;
	m_fontGraph.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = 900;
	m_fontGraph2.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = 0;
	font.lfHeight = 80;
	m_fontGraph3.CreatePointFontIndirect(&font, pDC);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDraw::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CRect		rectView			[I] 描画範囲
//		double		*pDataX				[I] X軸1/3オクターブデータへのポインタ
//		double		*pDataY				[I] Y軸1/3オクターブデータへのポインタ
//		double		*pDataZ				[I] Z軸1/3オクターブデータへのポインタ
//		double		*pDataG				[I] 合力1/3オクターブデータへのポインタ
//		double		*pLimit1			[I] リミットラインデータ１へのポインタ
//		double		*pLimit2			[I] リミットラインデータ２へのポインタ
//		int			nData				[I] データ数
//		double		*pOctaveBandFreq	[I] 1/3オクターブバンドのバンド間の周波数データ
//		double		fFreqMin			[I] 周波数軸の最小値
//		double		fFreqMax			[I] 周波数軸の最大値
//		BOOL		bFreqLog			[I] 周波数軸の対数スケールフラグ
//		double		fLevelMin			[I] レベル軸の最小値
//		double		fLevelMax			[I] レベル軸の最大値
//		BOOL		bLevelLog			[I] レベル軸の対数スケールフラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDraw::DrawGraph(CDC &dc, CRect rectView,
		const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG,
		const double *pLimit1, const double *pLimit2,
		int nData, const double *pOctaveBandFreq,
		double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	// ビューサイズ保存
	m_rectView = rectView;

	// スケールのサイズを決定
	SetScaleRect(dc);

	// 背景全体を白で塗りつぶす
	dc.FillSolidRect(m_rectView, COLOR_WHITE);

	// 透過モードに設定
	dc.SetBkMode(TRANSPARENT);

	// スケールを描画
	DrawScale(dc, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

	// リミットを描画
	if (pLimit1 != NULL)
		DrawData(dc, pLimit1, nData, pOctaveBandFreq, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphLimit, FALSE);
	if (pLimit2 != NULL)
		DrawData(dc, pLimit2, nData, pOctaveBandFreq, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphLimit, FALSE);

	// データを描画
	if (pDataX != NULL)
		DrawData(dc, pDataX, nData, pOctaveBandFreq, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataX, TRUE);
	if (pDataY != NULL)
		DrawData(dc, pDataY, nData, pOctaveBandFreq, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataY, TRUE);
	if (pDataZ != NULL)
		DrawData(dc, pDataZ, nData, pOctaveBandFreq, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataZ, TRUE);
	if (pDataG != NULL)
		DrawData(dc, pDataG, nData, pOctaveBandFreq, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataG, TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDraw::SetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフスケールサイズ設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDraw::SetScaleRect(CDC &dc)
{
	// テキストサイズ取得
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);
	dc.SelectObject(pFontOrg);

	// グラフのスケールの位置と大きさを設定
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 5 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + tm.tmHeight;
	m_rectScale.right = m_rectView.right - 15;
	m_rectScale.bottom = m_rectView.bottom - tm.tmHeight * 3;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDraw::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スケール描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		double		fFreqMin			[I] 周波数軸の最小値
//		double		fFreqMax			[I] 周波数軸の最大値
//		BOOL		bFreqLog			[I] 周波数軸の対数スケールフラグ
//		double		fLevelMin			[I] レベル軸の最小値
//		double		fLevelMax			[I] レベル軸の最大値
//		BOOL		bLevelLog			[I] レベル軸の対数スケールフラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDraw::DrawScale(CDC &dc, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	double	fFreq, fLevel;
	int x, y;
	CString sText;
	CSize sizeText;
	double fStep;
	int ns;

	// 描画オブジェクトをセット
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	CPen *pPenOrg = dc.SelectObject(&m_penScale);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);

	// テキストサイズ取得
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// 横軸を描画
	if (bFreqLog) {
		// 対数スケール
		double fFreqMinLog = log(fFreqMin);
		double fFreqMaxLog = log(fFreqMax);
		for (fFreq = GetScaleStartValue(fFreqMin, GetLogScaleStep(fFreqMin)); fFreq <= fFreqMax; fFreq += fStep) {
			fStep = GetLogScaleStep(fFreq);
			x = m_rectScale.left + (int)((log(fFreq) - fFreqMinLog) * m_rectScale.Width() / (fFreqMaxLog - fFreqMinLog) + 0.5);

			int n = (int)(fFreq / fStep + 1e-3);

			if (x > m_rectScale.left && x < m_rectScale.right) {
				dc.SelectObject(n == 1 ? &m_penGray : &m_penLightGray);

				dc.MoveTo(x, m_rectScale.top);
				dc.LineTo(x, m_rectScale.bottom);
			}

			if (n == 1 || n == 2 || n == 5) {
				if (fFreq < 0.01)
					sText.Format("%gm", fFreq * 1000);
				else
					sText.Format("%g", fFreq);
				sizeText = dc.GetOutputTextExtent(sText);
				dc.TextOut(x - sizeText.cx / 2, m_rectScale.bottom + tm.tmHeight / 2, sText);
			}
		}
	} else {
		// 線形スケール
		double fFreqRange = fFreqMax - fFreqMin;
		fStep = GetLinearScaleStep(fFreqRange, 4, &ns);
		for (fFreq = floor(fFreqMin / fStep) * fStep; fFreq <= fFreqMax; fFreq += fStep) {
			x = m_rectScale.left + (int)((fFreq - fFreqMin) * m_rectScale.Width() / fFreqRange + 0.5);

			if (x >= m_rectScale.left && x <= m_rectScale.right) {
				if (int(fFreq / fStep + 0.5) % ns == 0) {
					dc.SelectObject(&m_penGray);
					dc.MoveTo(x, m_rectScale.top);
					dc.LineTo(x, m_rectScale.bottom);

					sText.Format("%g", fFreq);
					sizeText = dc.GetOutputTextExtent(sText);
					dc.TextOut(x - sizeText.cx / 2, m_rectScale.bottom + tm.tmHeight / 2, sText);
				} else {
					dc.SelectObject(&m_penLightGray);
					dc.MoveTo(x, m_rectScale.top);
					dc.LineTo(x, m_rectScale.bottom);
				}
			}
		}
	}

	// 縦軸を描画
	if (bLevelLog) {
		// 対数スケール
		double fLevelMinLog = log(fLevelMin);
		double fLevelMaxLog = log(fLevelMax);
		double fLevelRange = fLevelMaxLog - fLevelMinLog;
		for (fLevel = fLevelMin; fLevel <= fLevelMax * 1.001; fLevel += fStep) {
			fStep = GetLogScaleStep(fLevel);
			y = m_rectScale.bottom - (int)(log(fLevel / fLevelMin) * m_rectScale.Height() / fLevelRange + 0.5);

			if (y >= m_rectScale.top && y <= m_rectScale.bottom) {
				if ((int)(fLevel / fStep + 1e-3) == 1) {
					dc.SelectObject(&m_penGray);
					dc.MoveTo(m_rectScale.left, y);
					dc.LineTo(m_rectScale.right, y);

					dc.SelectObject(&m_fontGraph3);
					sText.Format("%d", (int)floor((log10(fLevel) + 1e-3)));
					sizeText = dc.GetOutputTextExtent(sText);
					x = m_rectScale.left - sizeText.cx - tm.tmAveCharWidth;
					dc.TextOut(x, y - sizeText.cy, sText);
					dc.SelectObject(&m_fontGraph);
					sText = "10";
					sizeText = dc.GetOutputTextExtent(sText);
					dc.TextOut(x - sizeText.cx, y - sizeText.cy / 2, sText);
				} else {
					dc.SelectObject(&m_penLightGray);
					dc.MoveTo(m_rectScale.left, y);
					dc.LineTo(m_rectScale.right, y);
				}
			}
		}
	} else {
		// 線形スケール
		double fLevelRange = fLevelMax - fLevelMin;
		fStep = GetLinearScaleStep(fLevelRange, 4, &ns);
		for (fLevel = fLevelMin; fLevel <= fLevelMax * 1.001; fLevel += fStep) {
			y = m_rectScale.bottom - (int)((fLevel - fLevelMin) * m_rectScale.Height() / fLevelRange + 0.5);

			if (y >= m_rectScale.top && y <= m_rectScale.bottom) {
				if (int(fLevel / fStep + 0.5) % ns == 0) {
					dc.SelectObject(&m_penGray);
					dc.MoveTo(m_rectScale.left, y);
					dc.LineTo(m_rectScale.right, y);

					if (fLevel == 0) {
						sText = "0";
						sizeText = dc.GetOutputTextExtent(sText);
						dc.TextOut(m_rectScale.left - sizeText.cx - tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
					} else  {
						int nExp = (int)floor((log10(fLevel) + 1e-3));
						dc.SelectObject(&m_fontGraph3);
						sText.Format("%d", nExp);
						sizeText = dc.GetOutputTextExtent(sText);
						x = m_rectScale.left - sizeText.cx - tm.tmAveCharWidth;
						dc.TextOut(x, y - sizeText.cy, sText);
						dc.SelectObject(&m_fontGraph);
						sText.Format("%gx10", fLevel / pow(10.0, nExp));
						sizeText = dc.GetOutputTextExtent(sText);
						dc.TextOut(x - sizeText.cx, y - sizeText.cy / 2, sText);
					}
				} else {
					dc.SelectObject(&m_penLightGray);
					dc.MoveTo(m_rectScale.left, y);
					dc.LineTo(m_rectScale.right, y);
				}
			}
		}
	}

	// 外枠を描画
	dc.SelectObject(&m_penScale);
	CRect rect(m_rectScale.left, m_rectScale.top, m_rectScale.right + 1, m_rectScale.bottom + 1);
	dc.Rectangle(rect);

	// 横軸の文字を描画
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "周波数 [Hz]";
	sText = "Frequency [Hz]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectScale.left + (m_rectScale.Width() - sizeText.cx) / 2, m_rectView.bottom - (sizeText.cy + 4), sText);

	// 縦軸の文字を描画
	dc.SelectObject(&m_fontGraph2);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "加速度 [μG]";
	sText = "Acceleration [G]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectView.left + 3, m_rectScale.top + (m_rectScale.Height() + sizeText.cx) / 2, sText);

	// 描画オブジェクトを元に戻す
	dc.SelectObject(pFontOrg);
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDraw::DrawData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		double		*pData				[I] 1/3オクターブデータへのポインタ
//		int			nData				[I] データ数
//		double		*pOctaveBandFreq	[I] 1/3オクターブバンドのバンド間の周波数データ
//		double		fFreqMin			[I] 周波数軸の最小値
//		double		fFreqMax			[I] 周波数軸の最大値
//		BOOL		bFreqLog			[I] 周波数軸の対数スケールフラグ
//		double		fLevelMin			[I] レベル軸の最小値
//		double		fLevelMax			[I] レベル軸の最大値
//		BOOL		bLevelLog			[I] レベル軸の対数スケールフラグ
//		CPen		&penGraphLine		[I] 描画に使用するペン
//		BOOL		bStepLine			[I] ステップ表示フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDraw::DrawData(CDC &dc, const double *pData, int nData, const double *pOctaveBandFreq, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog, CPen &penGraphLine, BOOL bStepLine)
{
	int i, j;
	int x, y;
	double fFreq;
	double fLevel;

	// 周波数の対数変換
	if (bFreqLog) {
		fFreqMin = log(fFreqMin);
		fFreqMax = log(fFreqMax);
	}

	// レベルの対数変換
	if (bLevelLog) {
		fLevelMin = log(fLevelMin);
		fLevelMax = log(fLevelMax);
	}

	// ポリライン用のバッファを確保
	POINT *pPolyline = new POINT[nData * 2];

	// 加速度と周波数のスケールの係数を計算
	double fLevelFactor = m_rectScale.Height() / (fLevelMax - fLevelMin);
	double fFreqFactor = m_rectScale.Width() / (fFreqMax - fFreqMin);

	// データの折れ線を描画
	int nPolyline = 0;
	for (i = 0; i < nData; i++) {
		if (pData[i] != 0) {
			j = i * 2;

			if (bStepLine)
				fFreq = bFreqLog ? log(pOctaveBandFreq[j]) : pOctaveBandFreq[j];
			else
				fFreq = bFreqLog ? log(pOctaveBandFreq[j + 1]) : pOctaveBandFreq[j + 1];
			x = m_rectScale.left + (int)((fFreq - fFreqMin) * fFreqFactor + 0.5);

			fLevel = bLevelLog ? log(pData[i]) : pData[i];
			double fy = m_rectScale.bottom - (fLevel - fLevelMin) * fLevelFactor + 0.5;
			fy = min(fy, m_rectScale.bottom * 2);
			fy = max(fy, -m_rectScale.bottom);
			y = (int)fy;

			pPolyline[nPolyline].x = x;
			pPolyline[nPolyline].y = y;
			nPolyline++;

			if (bStepLine) {
				fFreq = bFreqLog ? log(pOctaveBandFreq[j + 2]) : pOctaveBandFreq[j + 2];
				x = m_rectScale.left + (int)((fFreq - fFreqMin) * fFreqFactor + 0.5);

				pPolyline[nPolyline].x = x;
				pPolyline[nPolyline].y = y;
				nPolyline++;
			}
		}
	}

	// リージョンを設定
	CRgn rgnScale;
	rgnScale.CreateRectRgn(m_rectScale.left, m_rectScale.top, m_rectScale.right - 1, m_rectScale.bottom);
	dc.SelectClipRgn(&rgnScale);

	// ペンを設定
	CPen *pPenOrg = dc.SelectObject(&penGraphLine);

	// ポリラインを描画
	dc.Polyline(pPolyline, nPolyline);

	// リージョンを解除
	dc.SelectClipRgn(NULL);

	// ペンを元に戻す
	dc.SelectObject(pPenOrg);

	// ポリライン用のバッファを解放
	delete [] pPolyline;
}
