#include "stdafx.h"
#include "Rta.h"
#include "FftWnd.h"
#include "FftDlg.h"

void CFftWnd::SetBitmapPws(FFTWINDOW *pFftWindow)
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
		DrawScalePws(pFftWindow, pFftWindow->m_nFrameLeft, pFftWindow->m_nFrameTop, pFftWindow->m_nFrameRight, pFftWindow->m_nFrameBottom, TRUE, m_nChannel == CH_STEREO ? "Lch / Rch" : NULL);
	} else {
		pFftWindow->m_nScaleWidth = pFftWindow->m_nFrameWidth;
		pFftWindow->m_nScaleHeight = pFftWindow->m_nFrameHeight / 2 - pFftWindow->m_nHeight / 50;

		DrawScalePws(pFftWindow, pFftWindow->m_nFrameLeft, pFftWindow->m_nFrameTop, pFftWindow->m_nFrameRight, pFftWindow->m_nFrameTop + pFftWindow->m_nScaleHeight, FALSE, "Lch");
		DrawScalePws(pFftWindow, pFftWindow->m_nFrameLeft, pFftWindow->m_nFrameBottom - pFftWindow->m_nScaleHeight, pFftWindow->m_nFrameRight, pFftWindow->m_nFrameBottom, TRUE, "Rch");
	}

	m_dcMem.SelectObject(pFontOrg);

	for (int i = 0; i < 2; i++) {
		FFTDATA &oFftData = m_aFftData[i];

		if (oFftData.m_pPwsPoint == NULL)
			oFftData.m_pPwsPoint = new POINT[pFftWindow->m_nScaleWidth * 2 + 4];
		oFftData.m_nPwsPointCount = 0;

		if (oFftData.m_pPwsPeakPoint == NULL)
			oFftData.m_pPwsPeakPoint = new POINT[pFftWindow->m_nScaleWidth * 2 + 4];
		oFftData.m_nPwsPeakCount = 0;
	}
}

void CFftWnd::DrawScalePws(const FFTWINDOW *pFftWindow, int nLeft, int nTop, int nRight, int nBottom, BOOL bAxisX, LPCTSTR pText)
{
	int	nFreq, nLevel;
	int x, y;
	int nWidth = nRight - nLeft;
	int nHeight = nBottom - nTop;
	CString sText;
	CSize size;
	CPen *pPenOrg;
	CBrush *pBrushOrg;

	pPenOrg = m_dcMem.SelectObject(&m_penBlack);
	pBrushOrg = m_dcMem.SelectObject(&m_brushGraph);

	m_dcMem.Rectangle(nLeft - 1, nTop - 1, nRight + 1, nBottom + 1);

	m_dcMem.SetBkMode(TRANSPARENT);
	m_dcMem.SetTextColor(COLOR_TEXT);

	m_dcMem.SelectObject(&m_Font);

	if (g_oSetData.Fft.nFftScale == 0) {
		int step = GetLogScaleStep(m_nMinFreq);
		double fSpanFreq = m_fLogMaxFreq - m_fLogMinFreq;
		for (nFreq = GetScaleStartValue(m_nMinFreq, step); nFreq <= m_nMaxFreq; nFreq += GetLogScaleStep(nFreq)) {
			x =  nLeft + (int)((log((double)nFreq) - m_fLogMinFreq) * nWidth / fSpanFreq + 0.5);

			if (nFreq < 1000)
				sText.Format("%d", nFreq);
			else
				sText.Format("%gk", (double)nFreq / 1000);

			if (bAxisX && (sText[0] == '1' || sText[0] == '2' || sText[0] == '5')) {
				size = m_dcMem.GetOutputTextExtent(sText);
				m_dcMem.TextOut(x - size.cx / 2, nBottom + 2, sText);
			}

			if (x > nLeft && x < nRight) {
				m_dcMem.SelectObject(sText[0] == '1' ? m_penGray : m_penLightGray);
				m_dcMem.MoveTo(x, nTop);
				m_dcMem.LineTo(x, nBottom);
			}
		}
	} else {
		m_dcMem.SelectObject(m_penGray);

		int step = GetLinearScaleStep(m_nMaxFreq, m_nMinFreq, (int)(nWidth * 0.75));
		double fSpanFreq = m_nMaxFreq - m_nMinFreq;
		for (nFreq = GetScaleStartValue(m_nMinFreq, step); nFreq <= m_nMaxFreq; nFreq += step) {
			x = nLeft + (int)((nFreq - m_nMinFreq) * nWidth / fSpanFreq + 0.5);

			if (x > nLeft && x < nRight) {
				m_dcMem.MoveTo(x, nTop);
				m_dcMem.LineTo(x, nBottom);
			}

			if (bAxisX) {
				if (nFreq < 1000)
					sText.Format("%d", nFreq);
				else
					sText.Format("%gk", (double)nFreq / 1000);
				size = m_dcMem.GetOutputTextExtent(sText);
				m_dcMem.TextOut(x - size.cx / 2, nBottom + 2, sText);
			}
		}
	}

	m_dcMem.SelectObject(m_penGray);

	int step = GetLinearScaleStep(m_nMaxLevel, m_nMinLevel, nHeight);
	double fSpanLevel = m_nMaxLevel - m_nMinLevel;
	for (nLevel = GetScaleStartValue(m_nMinLevel, step); nLevel <= m_nMaxLevel; nLevel += step) {
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
		sText.LoadString(IDS_FREQUENCY);
		sText += " [Hz]";
		size = m_dcMem.GetOutputTextExtent(sText);
		m_dcMem.TextOut(nLeft + (nWidth - size.cx) / 2, pFftWindow->m_nHeight - size.cy, sText);
	}

	m_dcMem.SelectObject(&m_Font2);
	sText.LoadString(IDS_SPL);
	sText += " [dB]";
	size = m_dcMem.GetOutputTextExtent(sText);
	m_dcMem.TextOut(0, nTop + (nHeight + size.cx) / 2, sText);

	DrawNote(pText, nTop, nRight);

	m_dcMem.SelectObject(pBrushOrg);
	m_dcMem.SelectObject(pPenOrg);
}

void CFftWnd::GetWaveDataPws(const FFTWINDOW *pFftWindow)
{
	if (m_bLch)
		CalcPws(pFftWindow, &m_aFftData[0]);

	if (m_bRch)
		CalcPws(pFftWindow, &m_aFftData[1]);

	DispPwsInfo(pFftWindow);
}

void CFftWnd::CalcPws(const FFTWINDOW *pFftWindow, FFTDATA *pFftData)
{
	int i;
	double fLevel;
	double *pPowerSpecBuf = pFftData->m_oPowerSpecBuf.GetBuf();
	int nFftSize2 = m_nFftSize / 2;

	pFftData->m_nPwsPointCount = CalcPwsSub(pFftWindow, pPowerSpecBuf, pFftData->m_pPwsPoint);

	if (g_oSetData.Fft.bPeakHold) {
		double *pPeakLevel = pFftData->m_pPwsPeakLevel;
		for (i = 0; i < nFftSize2; i++) {
			fLevel = pPowerSpecBuf[i];
			if (fLevel > *pPeakLevel || m_nPeakHoldTimer == 0)
				*pPeakLevel = fLevel;

			pPeakLevel++;
		}

		pFftData->m_nPwsPeakCount = CalcPwsSub(pFftWindow, pFftData->m_pPwsPeakLevel, pFftData->m_pPwsPeakPoint);
	}
}

int CFftWnd::CalcPwsSub(const FFTWINDOW *pFftWindow, const double *pBuf, POINT *pPoint)
{
	int i;
	int x;
	int x2 = 0;
	double fStepY = (double)pFftWindow->m_nScaleHeight / (m_nMinLevel - m_nMaxLevel);
	double fTmp1 = (double)pFftWindow->m_nScaleWidth / (m_fLogMaxFreq - m_fLogMinFreq);
	double fTmp2 = (double)pFftWindow->m_nScaleWidth / (m_nMaxFreq - m_nMinFreq);
	double fLevel;
	int nFftSize2 = m_nFftSize / 2;
	int nPointCount = 0;
	double ymin = pBuf[0];
	double ymax = pBuf[0];

	for (i = 0; i < nFftSize2; i++) {
		if (g_oSetData.Fft.nFftScale == 0)
			x = (int)((m_pLogFreqTbl[i] - m_fLogMinFreq) * fTmp1 + 0.5);
		else
			x = (int)((m_fFreqStep * i - m_nMinFreq) * fTmp2 + 0.5);

		if (x != x2) {
			if (x >= 0 && ymin > 0) {
				pPoint[nPointCount].x = x2;
				pPoint[nPointCount].y = (int)((dB10(ymin) - m_nMaxLevel) * fStepY + 0.5);
				nPointCount++;
				if (ymax != ymin) {
					pPoint[nPointCount].x = x2;
					pPoint[nPointCount].y = (int)((dB10(ymax) - m_nMaxLevel) * fStepY + 0.5);
					nPointCount++;
				}
				if (x2 >= pFftWindow->m_nScaleWidth)
					break;
			}

			x2 = x;
			ymin = FLT_MAX;
			ymax = 0;
		}

		fLevel = pBuf[i];
		if (fLevel < ymin)
			ymin = fLevel;
		if (fLevel > ymax)
			ymax = fLevel;
	}

	return nPointCount;
}

void CFftWnd::DispPwsInfo(const FFTWINDOW *pFftWindow)
{
	if (m_bLch)
		DispPwsInfoSub(pFftWindow, &m_aFftData[0], WM_FREQ_LEVEL_LEFT);

	if (m_bRch)
		DispPwsInfoSub(pFftWindow, &m_aFftData[1], WM_FREQ_LEVEL_RIGHT);
}

void CFftWnd::DispPwsInfoSub(const FFTWINDOW *pFftWindow, FFTDATA *pFftData, int nMessageId)
{
	CString sFreq, sLevel;
	double *pBuf = pFftData->m_oPowerSpecBuf.GetBuf();

	if (g_oSetData.Fft.bPeakDisp) {
		int nFftSize2 = m_nFftSize / 2;
		double fPeakLevel = 0;
		for (int i = 1; i < nFftSize2; i++) {
			double fFreq = m_fFreqStep * i;
			if (fFreq >= m_nMinFreq && fFreq <= m_nMaxFreq && pBuf[i] > fPeakLevel) {
				fPeakLevel = pBuf[i];
				pFftData->m_nPwsDispFreq = i;
			}
		}
	}

	if (g_oSetData.Fft.nFftScale == 0)
		pFftData->m_nPwsFreqPos = (int)((m_pLogFreqTbl[pFftData->m_nPwsDispFreq] - m_fLogMinFreq) * ((double)pFftWindow->m_nFrameWidth / (m_fLogMaxFreq - m_fLogMinFreq)));
	else
		pFftData->m_nPwsFreqPos = (int)((m_fFreqStep * pFftData->m_nPwsDispFreq - m_nMinFreq) * ((double)pFftWindow->m_nFrameWidth / (m_nMaxFreq - m_nMinFreq)));
	pFftData->m_nPwsLevelPos = (int)((dB10(pBuf[pFftData->m_nPwsDispFreq]) - m_nMaxLevel) * ((double)pFftWindow->m_nScaleHeight / (m_nMinLevel - m_nMaxLevel)));

	if (pFftData->m_nPwsDispFreq == 0) {
		if (pFftData->m_fFftAllPower != 0)
			sLevel.Format("%.2lf", dB10(pFftData->m_fFftAllPower));
		sFreq = "ALL";
	} else {
		if (pBuf[pFftData->m_nPwsDispFreq] != 0)
			sLevel.Format("%.2lf", dB10(pBuf[pFftData->m_nPwsDispFreq]));
		sFreq.Format("%.1lf",  m_fFreqStep * pFftData->m_nPwsDispFreq);
	}

	if (pFftWindow->m_bMasterWindow)
		m_pFftDlg->SendMessage(nMessageId, (WPARAM)(LPCTSTR)sFreq, (LPARAM)(LPCTSTR)sLevel);
	else
		pFftWindow->m_pWnd->SendMessage(nMessageId, (WPARAM)(LPCTSTR)sFreq, (LPARAM)(LPCTSTR)sLevel);
}

void CFftWnd::PaintPws(const FFTWINDOW *pFftWindow, int nChannel)
{
	if (nChannel == 0)
		PaintPwsSub(pFftWindow, &m_aFftData[0], m_penLeft, m_penLeftPeak);
	else
		PaintPwsSub(pFftWindow, &m_aFftData[1], m_penRight, m_penRightPeak);
}

void CFftWnd::PaintPwsSub(const FFTWINDOW *pFftWindow, FFTDATA *pFftData, CPen &penData, CPen &penPeak)
{
	if (pFftData->m_nPwsPointCount != 0) {
		m_dcMem2.SelectObject(&penData);
		m_dcMem2.Polyline(pFftData->m_pPwsPoint, pFftData->m_nPwsPointCount);

		if (g_oSetData.Fft.bPeakHold) {
			m_dcMem2.SelectObject(&penPeak);
			m_dcMem2.Polyline(pFftData->m_pPwsPeakPoint, pFftData->m_nPwsPeakCount);
		}

		if (pFftData->m_nPwsDispFreq != 0) {
			m_dcMem2.SelectObject(&penPeak);
			m_dcMem2.MoveTo(pFftData->m_nPwsFreqPos, 0);
			m_dcMem2.LineTo(pFftData->m_nPwsFreqPos, pFftWindow->m_nScaleHeight);
			m_dcMem2.MoveTo(0, pFftData->m_nPwsLevelPos);
			m_dcMem2.LineTo(pFftWindow->m_nScaleWidth, pFftData->m_nPwsLevelPos);
		}
	}
}

void CFftWnd::SetDispFreqPws(const FFTWINDOW *pFftWindow, CPoint point)
{
	if (!g_oSetData.Fft.bPeakDisp) {
		int n;

		if (g_oSetData.Fft.nFftScale == 0)
			n = (int)(exp((point.x - pFftWindow->m_nFrameLeft) * (m_fLogMaxFreq - m_fLogMinFreq) / pFftWindow->m_nScaleWidth + m_fLogMinFreq) / m_fFreqStep);
		else
			n = (int)(((point.x - pFftWindow->m_nFrameLeft) * (m_nMaxFreq - m_nMinFreq) / pFftWindow->m_nScaleWidth + m_nMinFreq) / m_fFreqStep);

		m_aFftData[0].m_nPwsDispFreq = m_aFftData[1].m_nPwsDispFreq = (n > 1 && n < m_nFftSize / 2) ? n : 0;
		DispPwsInfo(pFftWindow);
		pFftWindow->m_pWnd->Invalidate(FALSE);
	}
}

void CFftWnd::ResetDispFreqPws(const FFTWINDOW *pFftWindow)
{
	if (!g_oSetData.Fft.bPeakDisp) {
		m_aFftData[0].m_nPwsDispFreq = m_aFftData[1].m_nPwsDispFreq = 0;
		DispPwsInfo(pFftWindow);
		pFftWindow->m_pWnd->Invalidate(FALSE);
	}
}

void CFftWnd::CsvOutputPws(CCsvFile &cCsvFile)
{
	cCsvFile.SetData("FFT");
	cCsvFile.SetData("Power Spectrum");
	cCsvFile.Output();

	cCsvFile.SetData("Frequency[Hz]");
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
		pBuf1 = m_aFftData[0].m_oPowerSpecBuf.GetBuf();

	if (m_bRch)
		pBuf2 = m_aFftData[1].m_oPowerSpecBuf.GetBuf();

	if (g_oSetData.Fft.bPeakHold) {
		if (m_bLch)
			pBuf3 = m_aFftData[0].m_pPwsPeakLevel;

		if (m_bRch)
			pBuf4 = m_aFftData[1].m_pPwsPeakLevel;
	}

	int nFftSize2 = m_nFftSize / 2;
	for (int i = 1; i < nFftSize2; i++) {
		cCsvFile.SetData(m_fFreqStep * i);

		if (pBuf1 != NULL)
			cCsvFile.SetData(dB10(pBuf1[i]));

		if (pBuf2 != NULL)
			cCsvFile.SetData(dB10(pBuf2[i]));

		if (pBuf3 != NULL)
			cCsvFile.SetData(dB10(pBuf3[i]));

		if (pBuf4 != NULL)
			cCsvFile.SetData(dB10(pBuf4[i]));

		cCsvFile.Output();
	}
}
