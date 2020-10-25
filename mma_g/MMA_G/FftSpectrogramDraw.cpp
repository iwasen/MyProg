//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrogramDraw.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトログラム解析グラフの描画クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		画面表示と印刷の描画処理は共通であるため、両方で利用できるように独立したクラスとした。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "FftSpectrogramDraw.h"
#include "Define.h"
#include "DataFile.h"
#include "General.h"
#include <math.h>

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDraw::CFftSpectrogramDraw
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
CFftSpectrogramDraw::CFftSpectrogramDraw(CDC *pDC, int nPenWidth)
{
	// ペンの作成
	m_penScale.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_LIGHT_GRAY);
	m_penMidGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_MID_GRAY);

	// フォントの作成
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfHeight = 90;
	font.lfCharSet = DEFAULT_CHARSET;
	strcpy_s(font.lfFaceName, "MS UI Gothic");
	m_fontGraph.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = 900;
	m_fontGraph2.CreatePointFontIndirect(&font, pDC);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDraw::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		CRect		rectView			[I] 描画範囲
//		double		*pDataG				[I] 合力スペクトログラムデータへのポインタ
//		int			nFreqData			[I] 周波数軸の個数
//		int			nTimeData			[I] 時間軸の個数
//		double		fSampleRate			[I] サンプリングレート
//		double		fTimeMin			[I] 時間軸の最小値
//		double		fTimeMax			[I] 時間軸の最大値
//		double		fTimeOffset			[I] 時間軸のオフセット
//		double		fTimeZoom			[I] 時間軸のズーム比
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
void CFftSpectrogramDraw::DrawGraph(CDC &dc, CRect rectView, const double *pDataG, int nFreqData, int nTimeData, double fSampleRate, double fTimeMin, double fTimeMax, double fTimeOffset, double fTimeZoom, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	// ビューサイズ保存
	m_rectView = rectView;

	// スケールのサイズを決定
	SetScaleRect(dc);

	// 背景全体を白で塗りつぶす
	dc.FillSolidRect(m_rectView, COLOR_WHITE);

	// 透過モードに設定
	dc.SetBkMode(TRANSPARENT);

	// データを描画
	double fXOffset = fTimeOffset / (fTimeMin - fTimeMax) * fTimeZoom * m_rectScale.Width();
	DrawData(dc, pDataG, nFreqData, nTimeData, fXOffset, fTimeZoom, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

	// スケールを描画
	fTimeMax = (fTimeMax - fTimeMin) / fTimeZoom + fTimeMin;
	fTimeMin += fTimeOffset;
	fTimeMax += fTimeOffset;
	DrawScale(dc, fTimeMin, fTimeMax, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDraw::SetScaleRect
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
void CFftSpectrogramDraw::SetScaleRect(CDC &dc)
{
	// テキストサイズ取得
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);
	dc.SelectObject(pFontOrg);

	// グラフのスケールの位置と大きさを設定
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 4 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + tm.tmHeight;
	m_rectScale.right = m_rectView.right - (tm.tmHeight * 3 + tm.tmAveCharWidth * 5 + 15);
	m_rectScale.bottom = m_rectView.bottom - tm.tmHeight * 3;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDraw::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スケール描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		double		fTimeMin			[I] 時間軸の最小値
//		double		fTimeMax			[I] 時間軸の最大値
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
void CFftSpectrogramDraw::DrawScale(CDC &dc, double fTimeMin, double fTimeMax, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	double	fFreq;
	double fTime;
	double fStep;
	int x, y;
	CString sText;
	CSize sizeText;
	double fTimeSpan = fTimeMax - fTimeMin;
	int ns;
	double fLevelMinLog = log10(fLevelMin);
	double fLevelMaxLog = log10(fLevelMax);
	double fLevelRange = fLevelMaxLog - fLevelMinLog;
	double fLevel;

	// 描画オブジェクトをセット
	CPen *pPenOrg = dc.SelectObject(&m_penMidGray);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);

	// テキストサイズ取得
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// 横軸を描画
	fStep = GetLinearScaleStep(fTimeSpan, 4, &ns);
	for (fTime = GetScaleStartValue(fTimeMin, fStep); fTime <= fTimeMin + fTimeSpan; fTime += fStep) {
		x = m_rectScale.left + int((fTime - fTimeMin) * m_rectScale.Width() / fTimeSpan);
		if (x >= m_rectScale.left && x <= m_rectScale.right) {
			if (int(fTime / fStep + 0.5) % ns == 0) {
				if (x > m_rectScale.left && x < m_rectScale.right) {
					dc.MoveTo(x, m_rectScale.top);
					dc.LineTo(x, m_rectScale.bottom);
				}
				sText = CGeneral::FormatTime(fTime, 0);
				sizeText = dc.GetOutputTextExtent(sText);
				dc.TextOut(x - sizeText.cx / 2, m_rectScale.bottom + tm.tmHeight / 2, sText);
			}
		}
	}

	// 縦軸を描画
	if (bFreqLog) {
		// 対数スケール
		double fFreqMinLog = log(fFreqMin);
		double fFreqMaxLog = log(fFreqMax);
		for (fFreq = GetScaleStartValue(fFreqMin, GetLogScaleStep(fFreqMin)); fFreq <= fFreqMax; fFreq += fStep) {
			fStep = GetLogScaleStep(fFreq);
			y = m_rectScale.bottom - (int)((log(fFreq) - fFreqMinLog) * m_rectScale.Height() / (fFreqMaxLog - fFreqMinLog) + 0.5);

			int n = (int)(fFreq / fStep + 1e-3);

			if (y >= m_rectScale.top && y <= m_rectScale.bottom) {
				dc.SelectObject(n == 1 ? &m_penGray : &m_penLightGray);
				dc.MoveTo(m_rectScale.left, y);
				dc.LineTo(m_rectScale.right, y);
			}

			if (n == 1 || n == 2 || n == 5) {
				if (fFreq < 0.01)
					sText.Format("%gm", fFreq * 1000);
				else
					sText.Format("%g", fFreq);
				sizeText = dc.GetOutputTextExtent(sText);
				dc.TextOut(m_rectScale.left - sizeText.cx - tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
			}
		}
	} else {
		// 線形スケール
		double fFreqRange = fFreqMax - fFreqMin;
		fStep = GetLinearScaleStep(fFreqRange, 4, &ns);
		for (fFreq = GetScaleStartValue(fFreqMin, fStep); fFreq <= fFreqMax; fFreq += fStep) {
			y = m_rectScale.bottom - (int)((fFreq - fFreqMin) * m_rectScale.Height() / fFreqRange + 0.5);

			if (y >= m_rectScale.top && y <= m_rectScale.bottom) {
				if (int(fabs(fFreq) / fStep + 0.5) % ns == 0) {
					dc.SelectObject(&m_penGray);
					dc.MoveTo(m_rectScale.left, y);
					dc.LineTo(m_rectScale.right, y);

					sText.Format("%g", fFreq);
					sizeText = dc.GetOutputTextExtent(sText);
					dc.TextOut(m_rectScale.left - sizeText.cx - tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
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
	dc.Rectangle(m_rectScale.left - 1, m_rectScale.top - 1, m_rectScale.right + 1, m_rectScale.bottom + 1);

	// 横軸の文字を描画
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "時間";
	sText = "Time [hh:mm:ss](GMT)";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectScale.left + (m_rectScale.Width() - sizeText.cx) / 2, m_rectView.bottom - (sizeText.cy + 4), sText);

	// 縦軸の文字を描画
	dc.SelectObject(&m_fontGraph2);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "周波数 [Hz]";
	sText = "Frequency [Hz]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectView.left + 3, m_rectScale.top + (m_rectScale.Height() + sizeText.cx) / 2, sText);

	dc.SelectObject(&m_fontGraph);

	// カラースケールの枠を描画
	CRect rectColorScale(m_rectScale.right + tm.tmHeight * 2 + tm.tmAveCharWidth, m_rectScale.top - 1, m_rectScale.right + tm.tmHeight * 3 + 4, m_rectScale.bottom + 1);
	dc.Rectangle(rectColorScale);

	// カラースケールの文字を描画
	fStep = GetLinearScaleStep(fLevelRange, 4);
	for (fLevel = GetScaleStartValue(fLevelMinLog, fStep); fLevel <= fLevelMaxLog; fLevel += fStep) {
		y = m_rectScale.top - (int)((fLevel - fLevelMaxLog) * m_rectScale.Height() / fLevelRange + 0.5);

		sText.Format("%g", fLevel);
		sizeText = dc.GetOutputTextExtent(sText);
		dc.TextOut(rectColorScale.right + tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
	}

	// カラースケールの色を描画
	for (y = m_rectScale.top; y <= m_rectScale.bottom; y++)
		dc.FillSolidRect(rectColorScale.left + 1, y, rectColorScale.Width() - 2, 1, GetLevelColor(fLevelMaxLog - (y - m_rectScale.top) * fLevelRange / m_rectScale.Height(), fLevelMinLog, fLevelMaxLog));

// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "PSD [G^2/Hz]";
	sText = "PSD [Log(G^2/Hz)]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	dc.SelectObject(&m_fontGraph2);
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(rectColorScale.left - sizeText.cy - tm.tmAveCharWidth, rectColorScale.top + (rectColorScale.Height() + sizeText.cx) / 2, sText);

	// 描画オブジェクトを元に戻す
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pBrushOrg);
	dc.SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDraw::GetLevelColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		レベル(PSD)値を色に変換
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fLevel				[I] 変換するPSD値
//		double		fLevelMin			[I] レベル軸の最小値
//		double		fLevelMax			[I] レベル軸の最大値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		COLORREF	変換した色
//*****************************************************************************************************
COLORREF CFftSpectrogramDraw::GetLevelColor(double fLevel, double fLevelMin, double fLevelMax)
{
	int r, g, b;

	if (fLevel < fLevelMin)
		fLevel = fLevelMin;

	if (fLevel > fLevelMax)
		fLevel = fLevelMax;

	fLevel = (fLevel - fLevelMin) / (fLevelMax - fLevelMin);

	if (fLevel >= 0.8) {
		r = 255;
		g = 255 - (int)((fLevel - 0.8) * 5 * 255 + 0.5);
		b = 0;
	} else if (fLevel >= 0.6) {
		r = (int)((fLevel - 0.6) * 5 * 255 + 0.5);
		g = 255;
		b = 0;
	} else if (fLevel >= 0.4) {
		r = 0;
		g = 255;
		b = 255 - (int)((fLevel - 0.4) * 5 * 255 + 0.5);
	} else if (fLevel >= 0.2) {
		r = 0;
		g = (int)((fLevel - 0.2) * 5 * 255 + 0.5);
		b = 255;
	} else {
		r = 0;
		g = 0;
		b = (int)(fLevel * 5 * 255 + 0.5);
	}

	return RGB(r, g, b);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDraw::DrawData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ描画処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc					[I] デバイスコンテキスト
//		double		*pData				[I] スペクトログラムデータへのポインタ
//		int			nFreqData			[I] 周波数軸の個数
//		int			nTimeData			[I] 時間軸の個数
//		double		fTimeOffset			[I] 時間軸のオフセット
//		double		fTimeZoom			[I] 時間軸のズーム
//		double		fSampleRate			[I] サンプリングレート
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
void CFftSpectrogramDraw::DrawData(CDC &dc, const double *pData, int nFreqData, int nTimeData, double fTimeOffset, double fTimeZoom, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	int i;
	int x, y;
	int y2 = 0;
	int nFreqStep = m_rectScale.Height();
	int nHeightStep = 1;
	double fFreq;

	// 縦方向のピクセル数を1000以下に制限する
	while (nFreqStep > 1000) {
		nFreqStep /= 2;
		nHeightStep *= 2;
	}

	if (bFreqLog) {
		fFreqMin = log(fFreqMin);
		fFreqMax = log(fFreqMax);
	}

	if (bLevelLog) {
		fLevelMin = log(fLevelMin);
		fLevelMax = log(fLevelMax);
	}

	double fFreqFactor = nFreqStep / (fFreqMax - fFreqMin);
	double fLevel = 0;
	double fFreqStep = fSampleRate / nFreqData / 2;
	int count = 0;
	int t1, t2, t3;
	int nTimeStep = (int)(ceil((double)m_rectScale.Width() / nTimeData) * fTimeZoom);
	int nTime;

	// 計算結果一時保存用バッファ確保
	double *pSpgLevel = new double[nFreqStep];

	CRgn rgnScale;
	rgnScale.CreateRectRgn(m_rectScale.left, m_rectScale.top, m_rectScale.right, m_rectScale.bottom);
	dc.SelectClipRgn(&rgnScale);

	// 縦方向のピクセル毎の加速度を計算
	for (nTime = 0; nTime < nTimeData; nTime++) {
		memset(pSpgLevel, 0, sizeof(double) * nFreqStep);

		// ピクセル毎の加速度レベルを取得
		fLevel = 0;
		count = 0;
		for (i = 0; i < nFreqData; i++) {
			fFreq = bFreqLog ? (i == 0 ? 0 : log(i * fFreqStep)) : i * fFreqStep;
			y = (int)((fFreqMax - fFreq) * fFreqFactor + 0.5);

			if (i == 0)
				y2 = y;

			if (y != y2) {
				if (y >= 0 && y < nFreqStep)
					pSpgLevel[y] = (fLevel == 0) ? fLevelMin : log(fLevel / count);

				y2 = y;
				fLevel = 0;
				count = 0;
			}

			fLevel += *pData++;
			count++;
		}

		// データの存在しない部分を補間する
		for (i = 0; i < nFreqStep; i++) {
			if (pSpgLevel[i] == 0) {
				for (t1 = i; t1 >= 0 && pSpgLevel[t1] == 0; t1--)
					;
				for (t2 = i; t2 < nFreqStep && pSpgLevel[t2] == 0; t2++)
					;
				if (t1 < 0 && t2 >= nFreqStep)
					pSpgLevel[i] = fLevelMin;
				else if (t1 < 0)
					pSpgLevel[i] = pSpgLevel[t2];
				else if (t2 >= nFreqStep)
					pSpgLevel[i] = pSpgLevel[t1];
				else {
					t3 = t2 - t1;
					pSpgLevel[i] = (pSpgLevel[t1] * (t2 - i) + pSpgLevel[t2] * (i - t1)) / t3;
				}
			}
		}

		// 加速度のレベルに対応した色を描画
		COLORREF color;
		x = (int)(m_rectScale.left + nTime * m_rectScale.Width() / nTimeData * fTimeZoom + fTimeOffset);
		for (y = 0; y < nFreqStep; y++) {
			color = GetLevelColor(pSpgLevel[y], fLevelMin, fLevelMax);
			dc.FillSolidRect(x, m_rectScale.top + y * m_rectScale.Height() / nFreqStep, nTimeStep, nHeightStep, color);
		}
	}

	dc.SelectClipRgn(NULL);

	// 計算結果一時保存用バッファ解放
	delete [] pSpgLevel;
}
