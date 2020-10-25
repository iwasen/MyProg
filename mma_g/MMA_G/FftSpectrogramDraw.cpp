//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrogramDraw.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g���O������̓O���t�̕`��N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		��ʕ\���ƈ���̕`�揈���͋��ʂł��邽�߁A�����ŗ��p�ł���悤�ɓƗ������N���X�Ƃ����B
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "FftSpectrogramDraw.h"
#include "Define.h"
#include "DataFile.h"
#include "General.h"
#include <math.h>

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDraw::CFftSpectrogramDraw
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			*pDC			[I] �f�o�C�X�R���e�L�X�g�ւ̃|�C���^
//		int			nPenWidth		[I] �y���̑���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CFftSpectrogramDraw::CFftSpectrogramDraw(CDC *pDC, int nPenWidth)
{
	// �y���̍쐬
	m_penScale.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_LIGHT_GRAY);
	m_penMidGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_MID_GRAY);

	// �t�H���g�̍쐬
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
//  1. �֐���
//		CFftSpectrogramDraw::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView			[I] �`��͈�
//		double		*pDataG				[I] ���̓X�y�N�g���O�����f�[�^�ւ̃|�C���^
//		int			nFreqData			[I] ���g�����̌�
//		int			nTimeData			[I] ���Ԏ��̌�
//		double		fSampleRate			[I] �T���v�����O���[�g
//		double		fTimeMin			[I] ���Ԏ��̍ŏ��l
//		double		fTimeMax			[I] ���Ԏ��̍ő�l
//		double		fTimeOffset			[I] ���Ԏ��̃I�t�Z�b�g
//		double		fTimeZoom			[I] ���Ԏ��̃Y�[����
//		double		fFreqMin			[I] ���g�����̍ŏ��l
//		double		fFreqMax			[I] ���g�����̍ő�l
//		BOOL		bFreqLog			[I] ���g�����̑ΐ��X�P�[���t���O
//		double		fLevelMin			[I] ���x�����̍ŏ��l
//		double		fLevelMax			[I] ���x�����̍ő�l
//		BOOL		bLevelLog			[I] ���x�����̑ΐ��X�P�[���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDraw::DrawGraph(CDC &dc, CRect rectView, const double *pDataG, int nFreqData, int nTimeData, double fSampleRate, double fTimeMin, double fTimeMax, double fTimeOffset, double fTimeZoom, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	// �r���[�T�C�Y�ۑ�
	m_rectView = rectView;

	// �X�P�[���̃T�C�Y������
	SetScaleRect(dc);

	// �w�i�S�̂𔒂œh��Ԃ�
	dc.FillSolidRect(m_rectView, COLOR_WHITE);

	// ���߃��[�h�ɐݒ�
	dc.SetBkMode(TRANSPARENT);

	// �f�[�^��`��
	double fXOffset = fTimeOffset / (fTimeMin - fTimeMax) * fTimeZoom * m_rectScale.Width();
	DrawData(dc, pDataG, nFreqData, nTimeData, fXOffset, fTimeZoom, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

	// �X�P�[����`��
	fTimeMax = (fTimeMax - fTimeMin) / fTimeZoom + fTimeMin;
	fTimeMin += fTimeOffset;
	fTimeMax += fTimeOffset;
	DrawScale(dc, fTimeMin, fTimeMax, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDraw::SetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�X�P�[���T�C�Y�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDraw::SetScaleRect(CDC &dc)
{
	// �e�L�X�g�T�C�Y�擾
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);
	dc.SelectObject(pFontOrg);

	// �O���t�̃X�P�[���̈ʒu�Ƒ傫����ݒ�
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 4 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + tm.tmHeight;
	m_rectScale.right = m_rectView.right - (tm.tmHeight * 3 + tm.tmAveCharWidth * 5 + 15);
	m_rectScale.bottom = m_rectView.bottom - tm.tmHeight * 3;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDraw::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�P�[���`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		double		fTimeMin			[I] ���Ԏ��̍ŏ��l
//		double		fTimeMax			[I] ���Ԏ��̍ő�l
//		double		fFreqMin			[I] ���g�����̍ŏ��l
//		double		fFreqMax			[I] ���g�����̍ő�l
//		BOOL		bFreqLog			[I] ���g�����̑ΐ��X�P�[���t���O
//		double		fLevelMin			[I] ���x�����̍ŏ��l
//		double		fLevelMax			[I] ���x�����̍ő�l
//		BOOL		bLevelLog			[I] ���x�����̑ΐ��X�P�[���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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

	// �`��I�u�W�F�N�g���Z�b�g
	CPen *pPenOrg = dc.SelectObject(&m_penMidGray);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);

	// �e�L�X�g�T�C�Y�擾
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// ������`��
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

	// �c����`��
	if (bFreqLog) {
		// �ΐ��X�P�[��
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
		// ���`�X�P�[��
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

	// �O�g��`��
	dc.SelectObject(&m_penScale);
	dc.Rectangle(m_rectScale.left - 1, m_rectScale.top - 1, m_rectScale.right + 1, m_rectScale.bottom + 1);

	// �����̕�����`��
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "����";
	sText = "Time [hh:mm:ss](GMT)";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectScale.left + (m_rectScale.Width() - sizeText.cx) / 2, m_rectView.bottom - (sizeText.cy + 4), sText);

	// �c���̕�����`��
	dc.SelectObject(&m_fontGraph2);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "���g�� [Hz]";
	sText = "Frequency [Hz]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectView.left + 3, m_rectScale.top + (m_rectScale.Height() + sizeText.cx) / 2, sText);

	dc.SelectObject(&m_fontGraph);

	// �J���[�X�P�[���̘g��`��
	CRect rectColorScale(m_rectScale.right + tm.tmHeight * 2 + tm.tmAveCharWidth, m_rectScale.top - 1, m_rectScale.right + tm.tmHeight * 3 + 4, m_rectScale.bottom + 1);
	dc.Rectangle(rectColorScale);

	// �J���[�X�P�[���̕�����`��
	fStep = GetLinearScaleStep(fLevelRange, 4);
	for (fLevel = GetScaleStartValue(fLevelMinLog, fStep); fLevel <= fLevelMaxLog; fLevel += fStep) {
		y = m_rectScale.top - (int)((fLevel - fLevelMaxLog) * m_rectScale.Height() / fLevelRange + 0.5);

		sText.Format("%g", fLevel);
		sizeText = dc.GetOutputTextExtent(sText);
		dc.TextOut(rectColorScale.right + tm.tmAveCharWidth, y - sizeText.cy / 2, sText);
	}

	// �J���[�X�P�[���̐F��`��
	for (y = m_rectScale.top; y <= m_rectScale.bottom; y++)
		dc.FillSolidRect(rectColorScale.left + 1, y, rectColorScale.Width() - 2, 1, GetLevelColor(fLevelMaxLog - (y - m_rectScale.top) * fLevelRange / m_rectScale.Height(), fLevelMinLog, fLevelMaxLog));

// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "PSD [G^2/Hz]";
	sText = "PSD [Log(G^2/Hz)]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	dc.SelectObject(&m_fontGraph2);
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(rectColorScale.left - sizeText.cy - tm.tmAveCharWidth, rectColorScale.top + (rectColorScale.Height() + sizeText.cx) / 2, sText);

	// �`��I�u�W�F�N�g�����ɖ߂�
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pBrushOrg);
	dc.SelectObject(pFontOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDraw::GetLevelColor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���x��(PSD)�l��F�ɕϊ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fLevel				[I] �ϊ�����PSD�l
//		double		fLevelMin			[I] ���x�����̍ŏ��l
//		double		fLevelMax			[I] ���x�����̍ő�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		COLORREF	�ϊ������F
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
//  1. �֐���
//		CFftSpectrogramDraw::DrawData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		double		*pData				[I] �X�y�N�g���O�����f�[�^�ւ̃|�C���^
//		int			nFreqData			[I] ���g�����̌�
//		int			nTimeData			[I] ���Ԏ��̌�
//		double		fTimeOffset			[I] ���Ԏ��̃I�t�Z�b�g
//		double		fTimeZoom			[I] ���Ԏ��̃Y�[��
//		double		fSampleRate			[I] �T���v�����O���[�g
//		double		fFreqMin			[I] ���g�����̍ŏ��l
//		double		fFreqMax			[I] ���g�����̍ő�l
//		BOOL		bFreqLog			[I] ���g�����̑ΐ��X�P�[���t���O
//		double		fLevelMin			[I] ���x�����̍ŏ��l
//		double		fLevelMax			[I] ���x�����̍ő�l
//		BOOL		bLevelLog			[I] ���x�����̑ΐ��X�P�[���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDraw::DrawData(CDC &dc, const double *pData, int nFreqData, int nTimeData, double fTimeOffset, double fTimeZoom, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	int i;
	int x, y;
	int y2 = 0;
	int nFreqStep = m_rectScale.Height();
	int nHeightStep = 1;
	double fFreq;

	// �c�����̃s�N�Z������1000�ȉ��ɐ�������
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

	// �v�Z���ʈꎞ�ۑ��p�o�b�t�@�m��
	double *pSpgLevel = new double[nFreqStep];

	CRgn rgnScale;
	rgnScale.CreateRectRgn(m_rectScale.left, m_rectScale.top, m_rectScale.right, m_rectScale.bottom);
	dc.SelectClipRgn(&rgnScale);

	// �c�����̃s�N�Z�����̉����x���v�Z
	for (nTime = 0; nTime < nTimeData; nTime++) {
		memset(pSpgLevel, 0, sizeof(double) * nFreqStep);

		// �s�N�Z�����̉����x���x�����擾
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

		// �f�[�^�̑��݂��Ȃ��������Ԃ���
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

		// �����x�̃��x���ɑΉ������F��`��
		COLORREF color;
		x = (int)(m_rectScale.left + nTime * m_rectScale.Width() / nTimeData * fTimeZoom + fTimeOffset);
		for (y = 0; y < nFreqStep; y++) {
			color = GetLevelColor(pSpgLevel[y], fLevelMin, fLevelMax);
			dc.FillSolidRect(x, m_rectScale.top + y * m_rectScale.Height() / nFreqStep, nTimeStep, nHeightStep, color);
		}
	}

	dc.SelectClipRgn(NULL);

	// �v�Z���ʈꎞ�ۑ��p�o�b�t�@���
	delete [] pSpgLevel;
}
