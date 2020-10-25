#include "stdafx.h"
#include "Rta.h"
#include "FftWnd.h"
#include "FftDlg.h"

void CFftWnd::SetBitmapCep(FFTWINDOW *pFftWindow)
{
	CSize size;
	CFont *pFontOrg;
	TEXTMETRIC tm;

	pFontOrg = m_dcMem.SelectObject(&m_Font);
	m_dcMem.GetTextMetrics(&tm);

	pFftWindow->m_nFrameLeft = tm.tmAveCharWidth * 5 + tm.tmAscent + 4;
	pFftWindow->m_nFrameTop = 5;
	pFftWindow->m_nFrameRight = pFftWindow->m_nWidth - 7;
	pFftWindow->m_nFrameBottom = pFftWindow->m_nHeight - (tm.tmAscent + 2) * 2 - 3;
	pFftWindow->m_nFrameWidth = pFftWindow->m_nFrameRight - pFftWindow->m_nFrameLeft;
	pFftWindow->m_nFrameHeight = pFftWindow->m_nFrameBottom - pFftWindow->m_nFrameTop;

	if (m_nViewMode != VM_SPLIT) {
		pFftWindow->m_nScaleWidth = pFftWindow->m_nFrameWidth;
		pFftWindow->m_nScaleHeight = pFftWindow->m_nFrameHeight;
		DrawScaleCep(pFftWindow, pFftWindow->m_nFrameLeft, pFftWindow->m_nFrameTop, pFftWindow->m_nFrameRight, pFftWindow->m_nFrameBottom, TRUE, m_nChannel == CH_STEREO ? "Lch / Rch" : NULL);
	} else {
		pFftWindow->m_nScaleWidth = pFftWindow->m_nFrameWidth;
		pFftWindow->m_nScaleHeight = pFftWindow->m_nFrameHeight / 2 - pFftWindow->m_nHeight / 50;

		DrawScaleCep(pFftWindow, pFftWindow->m_nFrameLeft, pFftWindow->m_nFrameTop, pFftWindow->m_nFrameRight, pFftWindow->m_nFrameTop + pFftWindow->m_nScaleHeight, FALSE, "Lch");
		DrawScaleCep(pFftWindow, pFftWindow->m_nFrameLeft, pFftWindow->m_nFrameBottom - pFftWindow->m_nScaleHeight, pFftWindow->m_nFrameRight, pFftWindow->m_nFrameBottom, TRUE, "Rch");
	}

	m_dcMem.SelectObject(pFontOrg);

	for (int i = 0; i < 2; i++) {
		FFTDATA &oFftData = m_aFftData[i];

		if (oFftData.m_pCepPoint == NULL)
			oFftData.m_pCepPoint = new POINT[pFftWindow->m_nScaleWidth * 2 + 4];
		oFftData.m_nCepPointCount = 0;

		if (oFftData.m_pCepPeakPoint == NULL)
			oFftData.m_pCepPeakPoint = new POINT[pFftWindow->m_nScaleWidth * 2 + 4];
		oFftData.m_nCepPeakCount = 0;
	}
}

void CFftWnd::DrawScaleCep(const FFTWINDOW *pFftWindow, int nLeft, int nTop, int nRight, int nBottom, BOOL bAxisX, LPCTSTR pText)
{
	double t;
	int	nLevel;
	int x, y;
	int nHeight = nBottom - nTop;
	CString sText;
	CSize size;
	CPen *pPenOrg;
	CBrush *pBrushOrg;
	int ns;
	double fTime = m_fTimeStep / (g_oSetData.Fft.nCrfZoom * 2);

	pPenOrg = m_dcMem.SelectObject(&m_penBlack);
	pBrushOrg = m_dcMem.SelectObject(&m_brushGraph);

	m_dcMem.Rectangle(nLeft - 1, nTop - 1, nRight + 1, nBottom + 1);

	m_dcMem.SetBkMode(TRANSPARENT);
	m_dcMem.SetTextColor(COLOR_TEXT);

	m_dcMem.SelectObject(&m_Font);

	double t1 = m_fTimeStep / (10 * g_oSetData.Fft.nCrfZoom);
	t1 *= 500.0 / pFftWindow->m_nScaleWidth;
	double fStep = pow(10.0, floor(log10(t1)));
	double t2 = t1 / fStep;
	if (t2 < 3) {
		fStep *= 0.5;
		ns = 2;
	} else if (t2 < 5)
		ns = 2;
	else
		ns = 5;

	for (t = 0; t < fTime; t += fStep) {
		x = pFftWindow->m_nFrameLeft + int(t * pFftWindow->m_nScaleWidth / fTime + 0.5);
		if (x >= nLeft && x <= nRight) {
			if (int(t / fStep + 0.5) % ns == 0) {
				if (x > nLeft) {
					if (t == 0)
						m_dcMem.SelectObject(&m_penBlack);
					else
						m_dcMem.SelectObject(&m_penGray);
					m_dcMem.MoveTo(x, nTop);
					m_dcMem.LineTo(x, nBottom);
				}

				if (bAxisX) {
					sText.Format("%g", t * 1000);
					size = m_dcMem.GetOutputTextExtent(sText);
					m_dcMem.TextOut(x - size.cx / 2, nBottom + 2, sText);
				}
			} else {
				if (x > nLeft) {
					m_dcMem.SelectObject(&m_penLightGray);
					m_dcMem.MoveTo(x, nTop);
					m_dcMem.LineTo(x, nBottom);
				}
			}
		}
	}

	m_dcMem.SelectObject(m_penGray);

	int nStep = GetLinearScaleStep(m_nMaxLevel, m_nMinLevel, nHeight);
	double fSpanLevel = m_nMaxLevel - m_nMinLevel;
	for (nLevel = GetScaleStartValue(m_nMinLevel, nStep); nLevel <= m_nMaxLevel; nLevel += nStep) {
		y = nBottom - (int)((nLevel - m_nMinLevel) * nHeight / fSpanLevel + 0.5);

		if (y > nTop && y < nBottom) {
			m_dcMem.MoveTo(nLeft, y);
			m_dcMem.LineTo(nRight, y);
		}

		sText.Format("%d", nLevel);
		size = m_dcMem.GetOutputTextExtent(sText);
		m_dcMem.TextOut(nLeft - size.cx - 2, y - size.cy / 2, sText);
	}

	if (bAxisX) {
		sText.LoadString(IDS_TIME);
		sText += " [ms]";
		size = m_dcMem.GetOutputTextExtent(sText);
		m_dcMem.TextOut(nLeft + (pFftWindow->m_nScaleWidth - size.cx) / 2, pFftWindow->m_nHeight - size.cy, sText);
	}

	m_dcMem.SelectObject(&m_Font2);
	sText.LoadString(IDS_LEVEL);
	sText += " [dB]";
	size = m_dcMem.GetOutputTextExtent(sText);
	m_dcMem.TextOut(0, nTop + (nHeight + size.cx) / 2, sText);

	DrawNote(pText, nTop, nRight);

	m_dcMem.SelectObject(pBrushOrg);
	m_dcMem.SelectObject(pPenOrg);
}

void CFftWnd::GetWaveDataCep(const FFTWINDOW *pFftWindow)
{
	if (m_bLch)
		CalcCep(pFftWindow, &m_aFftData[0]);

	if (m_bRch)
		CalcCep(pFftWindow, &m_aFftData[1]);

	DispCepInfo(pFftWindow);
}

void CFftWnd::CalcCep(const FFTWINDOW *pFftWindow, FFTDATA *pFftData)
{
	int i, j, k;
	double fLevel = 0;
	double *pPowerSpecBuf = pFftData->m_oPowerSpecBuf.GetBuf();
	double *pCepBuf = pFftData->m_pCepBuf;
	int nFftSize2 = m_nFftSize / 2;
	int nDataSize = nFftSize2 / g_oSetData.Fft.nCrfZoom;

	for (i = 0; i < nFftSize2; i++) {
		j = i * 2;
		for (k = i; k < nFftSize2; k++) {
			fLevel = pPowerSpecBuf[k];
			if (fLevel > 0)
				break;
		}
		if (k == nFftSize2)
			fLevel = 0;

		pCepBuf[j] = (fLevel != 0) ? dB10(fLevel) : 0;
		pCepBuf[j + 1] = 1;
	}

	m_oFFT.ifft(m_nFftSize, pCepBuf);

	pCepBuf[0] = 0;

	double fMul = (double)50 / m_nFftSize;
	for (i = 0; i < nFftSize2; i++)
		pCepBuf[i] *= fMul;

	pFftData->m_nCepPointCount = CalcCepSub(pFftWindow, pCepBuf, pFftData->m_pCepPoint);

	if (g_oSetData.Fft.bPeakHold) {
		double *pPeakLevel = pFftData->m_pCepPeakLevel;
		for (i = 0; i < nDataSize; i++) {
			fLevel = pCepBuf[i];
			if (fLevel > *pPeakLevel || m_nPeakHoldTimer == 0)
				*pPeakLevel = fLevel;

			pPeakLevel++;
		}

		pFftData->m_nCepPeakCount = CalcCepSub(pFftWindow, pFftData->m_pCepPeakLevel, pFftData->m_pCepPeakPoint);
	}
}

int CFftWnd::CalcCepSub(const FFTWINDOW *pFftWindow, double *pCepBuf, POINT *pPoint)
{
	int i;
	int x;
	int x2 = 0;
	double fStepY = (double)pFftWindow->m_nScaleHeight / (m_nMinLevel - m_nMaxLevel);
	double fLevel;
	int nDataSize = m_nFftSize / (2 * g_oSetData.Fft.nCrfZoom);
	int nPointCount = 0;
	double ymin = pCepBuf[0];
	double ymax = pCepBuf[0];

	for (i = 0; i < nDataSize; i++) {
		x = (i * pFftWindow->m_nScaleWidth) / nDataSize;

		if (x != x2) {
			if (x >= 0) {
				pPoint[nPointCount].x = x2;
				pPoint[nPointCount].y = (int)((ymin - m_nMaxLevel) * fStepY + 0.5);
				nPointCount++;
				if (ymax != ymin) {
					pPoint[nPointCount].x = x2;
					pPoint[nPointCount].y = (int)((ymax - m_nMaxLevel) * fStepY + 0.5);
					nPointCount++;
				}
				if (x2 >= pFftWindow->m_nScaleWidth)
					break;
			}

			x2 = x;
			ymin = FLT_MAX;
			ymax = 0;
		}

		fLevel = pCepBuf[i];
		if (fLevel < ymin)
			ymin = fLevel;
		if (fLevel > ymax)
			ymax = fLevel;
	}

	return nPointCount;
}

void CFftWnd::DispCepInfo(const FFTWINDOW *pFftWindow)
{
	if (m_bLch)
		DispCepInfoSub(pFftWindow, &m_aFftData[0], WM_FREQ_LEVEL_LEFT);

	if (m_bRch)
		DispCepInfoSub(pFftWindow, &m_aFftData[1], WM_FREQ_LEVEL_RIGHT);
}

void CFftWnd::DispCepInfoSub(const FFTWINDOW *pFftWindow, FFTDATA *pFftData, int nMessageId)
{
	CString sTime, sLevel;
	double *pCepBuf = pFftData->m_pCepBuf;
	int nDataSize = m_nFftSize / (2 * g_oSetData.Fft.nCrfZoom);

	if (g_oSetData.Fft.bPeakDisp) {
		double fPeakLevel = pCepBuf[1];
		pFftData->m_nCepDispTime = 1;
		for (int i = 2; i < nDataSize; i++) {
			if (pCepBuf[i] > fPeakLevel) {
				fPeakLevel = pCepBuf[i];
				pFftData->m_nCepDispTime = i;
			}
		}
	}

	pFftData->m_nCepTimePos = (pFftData->m_nCepDispTime * pFftWindow->m_nScaleWidth) / nDataSize;
	pFftData->m_nCepLevelPos = (int)((pCepBuf[pFftData->m_nCepDispTime] - m_nMaxLevel) * ((double)pFftWindow->m_nScaleHeight / (m_nMinLevel - m_nMaxLevel)));

	if (pFftData->m_nCepDispTime == 0) {
		sLevel = "";
		sTime = "";
	} else {
		sLevel.Format("%.2lf", pCepBuf[pFftData->m_nCepDispTime]);
		sTime.Format("%.1lf",  1000.0 * pFftData->m_nCepDispTime / m_nSamplingRate);
	}

	if (pFftWindow->m_bMasterWindow)
		m_pFftDlg->SendMessage(nMessageId, (WPARAM)(LPCTSTR)sTime, (LPARAM)(LPCTSTR)sLevel);
	else
		pFftWindow->m_pWnd->SendMessage(nMessageId, (WPARAM)(LPCTSTR)sTime, (LPARAM)(LPCTSTR)sLevel);
}

void CFftWnd::PaintCep(const FFTWINDOW *pFftWindow, int nChannel)
{
	if (nChannel == 0)
		PaintCepSub(pFftWindow, &m_aFftData[0], m_penLeft, m_penLeftPeak);
	else
		PaintCepSub(pFftWindow, &m_aFftData[1], m_penRight, m_penRightPeak);
}

void CFftWnd::PaintCepSub(const FFTWINDOW *pFftWindow, FFTDATA *pFftData, CPen &penData, CPen &penPeak)
{
	if (pFftData->m_nCepPointCount != 0) {
		m_dcMem2.SelectObject(&penData);
		m_dcMem2.Polyline(pFftData->m_pCepPoint, pFftData->m_nCepPointCount);

		if (g_oSetData.Fft.bPeakHold) {
			m_dcMem2.SelectObject(&penPeak);
			m_dcMem2.Polyline(pFftData->m_pCepPeakPoint, pFftData->m_nCepPeakCount);
		}

		if (pFftData->m_nCepDispTime != 0) {
			m_dcMem2.SelectObject(&penPeak);
			m_dcMem2.MoveTo(pFftData->m_nCepTimePos, 0);
			m_dcMem2.LineTo(pFftData->m_nCepTimePos, pFftWindow->m_nScaleHeight);
			m_dcMem2.MoveTo(0, pFftData->m_nCepLevelPos);
			m_dcMem2.LineTo(pFftWindow->m_nScaleWidth, pFftData->m_nCepLevelPos);
		}
	}
}

void CFftWnd::SetDispFreqCep(const FFTWINDOW *pFftWindow, CPoint point)
{
	if (!g_oSetData.Fft.bPeakDisp) {
		int n;
		int nDataSize = m_nFftSize / (2 * g_oSetData.Fft.nCrfZoom);

		n = (point.x - pFftWindow->m_nFrameLeft) * nDataSize / pFftWindow->m_nScaleWidth;
		m_aFftData[0].m_nCepDispTime = m_aFftData[1].m_nCepDispTime = (n > 1 && n < nDataSize) ? n : 0;
		DispCepInfo(pFftWindow);
		pFftWindow->m_pWnd->Invalidate(FALSE);
	}
}

void CFftWnd::ResetDispFreqCep(const FFTWINDOW *pFftWindow)
{
	if (!g_oSetData.Fft.bPeakDisp) {
		m_aFftData[0].m_nCepDispTime = m_aFftData[1].m_nCepDispTime = 0;
		DispCepInfo(pFftWindow);
		pFftWindow->m_pWnd->Invalidate(FALSE);
	}
}

void CFftWnd::CsvOutputCep(CCsvFile &cCsvFile)
{
	cCsvFile.SetData("FFT");
	cCsvFile.SetData("Cepstrum");
	cCsvFile.Output();

	cCsvFile.SetData("Time[ms]");
	switch (g_oSetData.Fft.nChannel) {
	case CH_MONO:
		cCsvFile.SetData("Level-M[dB]");
		break;
	case CH_STEREO:
		cCsvFile.SetData("Level-L[dB]");
		cCsvFile.SetData("Level-R[dB]");
		break;
	case CH_LEFT_ONLY:
		cCsvFile.SetData("Level-L[dB]");
		break;
	case CH_RIGHT_ONLY:
		cCsvFile.SetData("Level-R[dB]");
		break;
	case CH_DIFFERENCE:
		cCsvFile.SetData("Level-D[dB]");
		break;
	}
	if (g_oSetData.Fft.bPeakHold) {
		switch (g_oSetData.Fft.nChannel) {
		case CH_MONO:
			cCsvFile.SetData("Peak-M[dB]");
			break;
		case CH_STEREO:
			cCsvFile.SetData("Peak-L[dB]");
			cCsvFile.SetData("Peak-R[dB]");
			break;
		case CH_LEFT_ONLY:
			cCsvFile.SetData("Peak-L[dB]");
			break;
		case CH_RIGHT_ONLY:
			cCsvFile.SetData("Peak-R[dB]");
			break;
		case CH_DIFFERENCE:
			cCsvFile.SetData("Peak-D[dB]");
			break;
		}
	}
	cCsvFile.Output();

	double *pBuf1 = NULL;
	double *pBuf2 = NULL;
	double *pBuf3 = NULL;
	double *pBuf4 = NULL;

	if (m_bLch)
		pBuf1 = m_aFftData[0].m_pCepBuf;

	if (m_bRch)
		pBuf2 = m_aFftData[1].m_pCepBuf;

	if (g_oSetData.Fft.bPeakHold) {
		if (m_bLch)
			pBuf3 = m_aFftData[0].m_pCepPeakLevel;

		if (m_bRch)
			pBuf4 = m_aFftData[1].m_pCepPeakLevel;
	}

	int nFftSize2 = m_nFftSize / 2;
	for (int i = 1; i < nFftSize2; i++) {
		cCsvFile.SetData(1000.0 * i / m_nSamplingRate);

		if (pBuf1 != NULL)
			cCsvFile.SetData(pBuf1[i]);

		if (pBuf2 != NULL)
			cCsvFile.SetData(pBuf2[i]);

		if (pBuf3 != NULL)
			cCsvFile.SetData(pBuf3[i]);

		if (pBuf4 != NULL)
			cCsvFile.SetData(pBuf4[i]);

		cCsvFile.Output();
	}
}
