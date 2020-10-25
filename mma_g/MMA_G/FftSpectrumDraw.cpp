//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrumDraw.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g����̓O���t�̕`��N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		��ʕ\���ƈ���̕`�揈���͋��ʂł��邽�߁A�����ŗ��p�ł���悤�ɓƗ������N���X�Ƃ����B
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "FftSpectrumDraw.h"
#include "Define.h"
#include <math.h>

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumDraw::CFftSpectrumDraw
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
CFftSpectrumDraw::CFftSpectrumDraw(CDC *pDC, int nPenWidth)
{
	// �y���̍쐬
	m_penGraphDataX.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_X);
	m_penGraphDataY.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_Y);
	m_penGraphDataZ.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_Z);
	m_penGraphDataG.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_G);
	m_penScale.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_LIGHT_GRAY);

	// �t�H���g�̍쐬
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
//  1. �֐���
//		CFftSpectrumDraw::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView			[I] �`��͈�
//		double		*pDataX				[I] X���X�y�N�g���f�[�^�ւ̃|�C���^
//		double		*pDataY				[I] Y���X�y�N�g���f�[�^�ւ̃|�C���^
//		double		*pDataZ				[I] Z���X�y�N�g���f�[�^�ւ̃|�C���^
//		double		*pDataG				[I] ���̓X�y�N�g���f�[�^�ւ̃|�C���^
//		int			nData				[I] �f�[�^��
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
void CFftSpectrumDraw::DrawGraph(CDC &dc, CRect rectView, const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	// �r���[�T�C�Y�ۑ�
	m_rectView = rectView;

	// �X�P�[���̃T�C�Y������
	SetScaleRect(dc);

	// �w�i�S�̂𔒂œh��Ԃ�
	dc.FillSolidRect(m_rectView, COLOR_WHITE);

	// ���߃��[�h�ɐݒ�
	dc.SetBkMode(TRANSPARENT);

	// �X�P�[����`��
	DrawScale(dc, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

	// �f�[�^��`��
	if (pDataX != NULL)
		DrawData(dc, pDataX, nData, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataX);
	if (pDataY != NULL)
		DrawData(dc, pDataY, nData, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataY);
	if (pDataZ != NULL)
		DrawData(dc, pDataZ, nData, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataZ);
	if (pDataG != NULL)
		DrawData(dc, pDataG, nData, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog, m_penGraphDataG);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumDraw::SetScaleRect
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
void CFftSpectrumDraw::SetScaleRect(CDC &dc)
{
	// �e�L�X�g�T�C�Y�擾
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);
	dc.SelectObject(pFontOrg);

	// �O���t�̃X�P�[���̈ʒu�Ƒ傫����ݒ�
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 9 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + tm.tmHeight;
	m_rectScale.right = m_rectView.right - 15;
	m_rectScale.bottom = m_rectView.bottom - tm.tmHeight * 3;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumDraw::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�P�[���`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
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
void CFftSpectrumDraw::DrawScale(CDC &dc, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	double	fFreq, fLevel;
	int x, y;
	CString sText;
	CSize sizeText;
	double fStep;
	int ns;

	// �`��I�u�W�F�N�g���Z�b�g
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	CPen *pPenOrg = dc.SelectObject(&m_penScale);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);

	// �e�L�X�g�T�C�Y�擾
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// ������`��
	if (bFreqLog) {
		// �ΐ��X�P�[��
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
		// ���`�X�P�[��
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

	// �c����`��
	if (bLevelLog) {
		// �ΐ��X�P�[��
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
		// ���`�X�P�[��
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

	// �O�g��`��
	dc.SelectObject(&m_penScale);
	CRect rect(m_rectScale.left, m_rectScale.top, m_rectScale.right + 1, m_rectScale.bottom + 1);
	dc.Rectangle(rect);

	// �����̕�����`��
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "���g�� [Hz]";
	sText = "Frequency [Hz]";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectScale.left + (m_rectScale.Width() - sizeText.cx) / 2, m_rectView.bottom - (sizeText.cy + 4), sText);

	// �c���̕�����`��
	dc.SelectObject(&m_fontGraph2);
	sText = "PSD [G^2/Hz]";
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(m_rectView.left + 3, m_rectScale.top + (m_rectScale.Height() + sizeText.cx) / 2, sText);

	// �`��I�u�W�F�N�g�����ɖ߂�
	dc.SelectObject(pFontOrg);
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumDraw::DrawData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		double		*pData				[I] �X�y�N�g���f�[�^�ւ̃|�C���^
//		int			nData				[I] �f�[�^��
//		double		fSampleRate			[I] �T���v�����O���[�g
//		double		fFreqMin			[I] ���g�����̍ŏ��l
//		double		fFreqMax			[I] ���g�����̍ő�l
//		BOOL		bFreqLog			[I] ���g�����̑ΐ��X�P�[���t���O
//		double		fLevelMin			[I] ���x�����̍ŏ��l
//		double		fLevelMax			[I] ���x�����̍ő�l
//		BOOL		bLevelLog			[I] ���x�����̑ΐ��X�P�[���t���O
//		CPen		&penGraphLine		[I] �`��Ɏg�p����y��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumDraw::DrawData(CDC &dc, const double *pData, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog, CPen &penGraphLine)
{
	int i;
	int x, x2;
	int y, ymin, ymax;
	int nGraphPoint = 0;
	double fFreq;
	double fLevel;

	// �|�����C���p�z��m��
	POINT *pGraphPoint = new POINT[m_rectScale.Width() * 2 + 4];

	if (bFreqLog) {
		fFreqMin = log(fFreqMin);
		fFreqMax = log(fFreqMax);
	}

	if (bLevelLog) {
		fLevelMin = log(fLevelMin);
		fLevelMax = log(fLevelMax);
	}

	double fFreqStep = fSampleRate / nData / 2;
	double fLevelFactor = m_rectScale.Height() / (fLevelMax - fLevelMin);
	double fFreqFactor = m_rectScale.Width() / (fFreqMax - fFreqMin);

	// �f�[�^�̐܂����`��
	x2 = 0;
	ymin = INT_MAX;
	ymax = -INT_MAX;
	for (i = 1; i < nData; i++) {
		fFreq = bFreqLog ? log(i * fFreqStep) : i * fFreqStep;
		x = m_rectScale.left + (int)((fFreq - fFreqMin) * fFreqFactor + 0.5);

		if (x2 == 0)
			x2 = x;

		if (x != x2) {
			if (x >= m_rectScale.left && x < m_rectScale.right) {
				pGraphPoint[nGraphPoint].x = x2;
				pGraphPoint[nGraphPoint].y = ymin;
				nGraphPoint++;
				if (ymax != ymin) {
					pGraphPoint[nGraphPoint].x = x2;
					pGraphPoint[nGraphPoint].y = ymax;
					nGraphPoint++;
				}
			}
			ymin = INT_MAX;
			ymax = -INT_MAX;
			x2 = x;
		}

		if (pData[i] != 0) {
			fLevel = bLevelLog ? log(pData[i]) : pData[i];
			double fy = m_rectScale.bottom - (fLevel - fLevelMin) * fLevelFactor + 0.5;
			fy = min(fy, m_rectScale.bottom * 2);
			fy = max(fy, -m_rectScale.bottom);
			y = (int)fy;
		} else
			y = m_rectScale.bottom;

		if (y < ymin)
			ymin = y;
		if (y > ymax)
			ymax = y;
	}

	// ���[�W������ݒ�
	CRgn rgnScale;
	rgnScale.CreateRectRgn(m_rectScale.left, m_rectScale.top, m_rectScale.right - 1, m_rectScale.bottom);
	dc.SelectClipRgn(&rgnScale);

	// �y����ݒ�
	CPen *pPenOrg = dc.SelectObject(&penGraphLine);

	// �|�����C����`��
	dc.Polyline(pGraphPoint, nGraphPoint);

	// ���[�W����������
	dc.SelectClipRgn(NULL);

	// �y�������ɖ߂�
	dc.SelectObject(pPenOrg);

	// �|�����C���p�z����
	delete [] pGraphPoint;
}