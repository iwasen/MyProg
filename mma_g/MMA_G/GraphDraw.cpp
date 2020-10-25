//*****************************************************************************************************
//  1. �t�@�C����
//		GraphDraw.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�O���t�̕`��N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		��ʕ\���ƈ���̕`�揈���͋��ʂł��邽�߁A�����ŗ��p�ł���悤�ɓƗ������N���X�Ƃ����B
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "GraphDraw.h"
#include "GraphData.h"
#include "Define.h"
#include "General.h"
#include "DataDateTime.h"
#include <math.h>

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::CGraphDraw
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
CGraphDraw::CGraphDraw(CDC *pDC, int nPenWidth)
{
	// �y�����쐬
	m_penGraphLineX.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_X);
	m_penGraphLineY.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_Y);
	m_penGraphLineZ.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_Z);
	m_penGraphLineD.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_DATA_D);
	m_penGraphScale.CreatePen(PS_SOLID, 2 * nPenWidth, COLOR_GRAPH_SCALE);
	m_penGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAY);
	m_penLightGray.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_LIGHT_GRAY);
	m_penCursor.CreatePen(PS_DOT, 1 * nPenWidth, COLOR_GRAPH_CURSOR);
	m_penSelect.CreatePen(PS_SOLID, 1 * nPenWidth, COLOR_GRAPH_SELECT);

	// �u���V���쐬
	m_brushSelect.CreateSolidBrush(COLOR_GRAPH_SELECT);
	m_brushGraphLineX.CreateSolidBrush(COLOR_GRAPH_DATA_X);
	m_brushGraphLineY.CreateSolidBrush(COLOR_GRAPH_DATA_Y);
	m_brushGraphLineZ.CreateSolidBrush(COLOR_GRAPH_DATA_Z);
	m_brushGraphLineD.CreateSolidBrush(COLOR_GRAPH_DATA_D);

	// �t�H���g���쐬
	LOGFONT font;
	memset(&font, 0, sizeof(font));
	font.lfHeight = 90;
	font.lfCharSet = DEFAULT_CHARSET;
	strcpy_s(font.lfFaceName, "MS UI Gothic");
	m_fontGraph.CreatePointFontIndirect(&font, pDC);
	font.lfEscapement = 900;
	m_fontGraph2.CreatePointFontIndirect(&font, pDC);

	// �����o�ϐ�������
	m_fSelectBegin = 0;
	m_fSelectEnd = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView			[I] �`��͈�
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		BOOL		bDrawSelect			[I] TRUE�F�I��͈͂�`�悷��@FALSE�F�I��͈͂�`�悵�Ȃ�
//		int			nGraphKind			[I] �O���t�̎�ށi�����x or ���x�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDraw::DrawGraph(CDC &dc, CRect rectView, const CGraphData &cGraphData, BOOL bDrawSelect, int nGraphKind)
{
	// �r���[�T�C�Y�ۑ�
	m_rectView = rectView;

	// �X�P�[���̃T�C�Y������
	SetScaleRect(dc, (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) ? 3 : 1);

	// �w�i��h��Ԃ�
	dc.FillSolidRect(m_rectView, COLOR_WHITE);

	// ���߃��[�h��ݒ�
	dc.SetBkMode(TRANSPARENT);

	// �X�P�[���ƃf�[�^��`��
	switch (nGraphKind) {
	case GRAPH_ACCEL:	// �����x�O���t
		// �X�P�[����`��
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, "X-axis Acceleration [��G]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, "Y-axis Acceleration [��G]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, "Z-axis Acceleration [��G]", 4);

		// �f�[�^��`��
		DrawData(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataX, m_penGraphLineX, m_brushGraphLineX);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, cGraphData.m_pFilterDataY, m_penGraphLineY, m_brushGraphLineY);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, cGraphData.m_pFilterDataZ, m_penGraphLineZ, m_brushGraphLineZ);
		break;
	case GRAPH_TEMP:	// ���x�O���t
		// �X�P�[����`��
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, "Temperature [��]", 8);

		// �f�[�^��`��
		if (cGraphData.m_bDispX)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataX, m_penGraphLineX, m_brushGraphLineX);
		if (cGraphData.m_bDispY)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataY, m_penGraphLineY, m_brushGraphLineY);
		if (cGraphData.m_bDispZ)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataZ, m_penGraphLineZ, m_brushGraphLineZ);
		if (cGraphData.m_bDispD)
			DrawData(dc, cGraphData, GetScaleRect(SCALE_TEMP), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataD, m_penGraphLineD, m_brushGraphLineD);
		break;
	case GRAPH_ZERO_OFFSET:	// 0�_�␳�l�O���t
		// �X�P�[����`��
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, "X-axis Zero Offset [��G]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, "Y-axis Zero Offset [��G]", 4);
		DrawScale(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, "Z-axis Zero Offset [��G]", 4);

		// �f�[�^��`��
		DrawData(dc, cGraphData, GetScaleRect(SCALE_X), cGraphData.m_stLevelRangeX, cGraphData.m_pFilterDataX, m_penGraphLineX, m_brushGraphLineX);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Y), cGraphData.m_stLevelRangeY, cGraphData.m_pFilterDataY, m_penGraphLineY, m_brushGraphLineY);
		DrawData(dc, cGraphData, GetScaleRect(SCALE_Z), cGraphData.m_stLevelRangeZ, cGraphData.m_pFilterDataZ, m_penGraphLineZ, m_brushGraphLineZ);
		break;
	}

	// �I��͈͂�\��
	if (bDrawSelect) {
		m_fSelectBegin = 0;
		m_fSelectEnd = 0;
		DrawSelect(dc, cGraphData, nGraphKind);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::SetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�X�P�[���T�C�Y�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		int			nScaleNum			[I] �X�P�[���̌�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDraw::SetScaleRect(CDC &dc, int nScaleNum)
{
	// �e�L�X�g�T�C�Y�擾
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);
	dc.SelectObject(pFontOrg);

	// �ЂƂ̃O���t�̍���
	m_nScaleHeight = (m_rectView.Height() - tm.tmHeight) / nScaleNum;

	// �ЂƂ̃O���t�̈ʒu�Ƒ傫��
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 8 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + 10;
	m_rectScale.right = m_rectView.right - 30;
	m_rectScale.bottom = m_rectView.top + m_nScaleHeight - tm.tmHeight * 2;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�P�[���`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		CRect		rectScale			[I] �X�P�[���̈ʒu
//		SDispRange	&stLevelRange		[I] ���x�������W
//		LPCTSTR		pXYZ				[I] �c����
//		int			nYAxis				[I] �c���ڐ��̖��x
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDraw::DrawScale(CDC &dc, const CGraphData &cGraphData, CRect rectScale, const SDispRange &stLevelRange, LPCTSTR pXYZ, int nYAxis)
{
	CString sText;
	CSize sizeText;
	int x, y;
	double fStep, t;
	int ns;

	// �`��I�u�W�F�N�g��ݒ�
	CPen *pPenOrg = dc.SelectObject(&m_penGraphScale);
	CFont *pFontOrg = dc.SelectObject(&m_fontGraph);
	CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);

	// �e�L�X�g�T�C�Y�擾
	TEXTMETRIC tm;
	dc.GetTextMetrics(&tm);

	// ������`��
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
					// 1000�{����Ƃ�0.49999���̐��l�̂��߂�0.0001�v���X���Ă���
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

	// �c����`��
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

	// �O�g��`��
	dc.SelectObject(&m_penGraphScale);
	CRect rectScale2(rectScale);
	rectScale2.InflateRect(1, 1, 2, 2);
	dc.Rectangle(&rectScale2);

	// �����̕�����`��
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText = "����";
	sText = "Time [hh:mm:ss](GMT)";
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(sText);
	dc.TextOut(rectScale.left + (rectScale.Width() - sizeText.cx) / 2, m_rectView.bottom - (sizeText.cy + 4), sText);

	// �c���̕�����`��
	dc.SelectObject(&m_fontGraph2);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG ->
//	sText.Format("%s�� �����x [��G]", pXYZ);
//	sText.Format("%s-axis Acceleration [��G]", pXYZ);
// 077FD050-GYM961-0_001 2007/9/6 modified by yG <-
	sizeText = dc.GetOutputTextExtent(pXYZ);
	dc.TextOut(m_rectView.left + 3, rectScale.top + (rectScale.Height() + sizeText.cx) / 2, pXYZ);

	// �`��I�u�W�F�N�g�����ɖ߂�
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pFontOrg);
	dc.SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::DrawData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		CRect		rectScale			[I] �X�P�[���̈ʒu
//		SDispRange	&stLevelRange		[I] �\���͈͏��
//		double		*pData				[I] �o�C�i���f�[�^�ւ̃|�C���^
//		CPen		&penGraphLine		[I] �`��Ɏg�p����y��
//		CBrush		&brushGraphLine		[I] �`��Ɏg�p����u���V
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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

	// �|�����C���p�z��m��
	int nPointSize = rectScale.Width() * 2 + 6;
	POINT *pGraphPoint = new POINT[nPointSize];

	// �|�����C�����v�Z
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

	// ���[�W������ݒ�
	CRgn rgnGraph;
	rgnGraph.CreateRectRgn(rectScale.left, rectScale.top, rectScale.right, rectScale.bottom);
	dc.SelectClipRgn(&rgnGraph);

	// �y����ݒ�
	CPen *pPenOrg = dc.SelectObject(&penGraphLine);

	// �|�����C����`��
	dc.Polyline(pGraphPoint, nGraphPoint);

	// �f�[�^�ʒu�Ƀh�b�g��\��
	if (nGraphPoint < (x2 - rectScale.left) / 10) {
		CBrush *pBrushOrg = dc.SelectObject(&brushGraphLine);

		POINT *pPoint;
		for (i = 0, pPoint = pGraphPoint; i < nGraphPoint; i++, pPoint++)
			dc.Ellipse(pPoint->x - 2, pPoint->y - 2, pPoint->x + 3, pPoint->y + 3);

		dc.SelectObject(pBrushOrg);
	}

	// �y����߂�
	dc.SelectObject(pPenOrg);

	// ���[�W����������
	dc.SelectClipRgn(NULL);

	// �|�����C���p�z����
	delete [] pGraphPoint;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::GetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		X,Y,Z���̃O���t�̈ʒu���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nScaleNo			[I] X,Y,Z���̎��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CRect	�X�P�[���̈ʒu
//*****************************************************************************************************
CRect CGraphDraw::GetScaleRect(int nScaleNo)
{
	if (nScaleNo == SCALE_TEMP)
		return m_rectScale;
	else
		return m_rectScale + CPoint(0, m_nScaleHeight * nScaleNo);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::DrawTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		X,Y,Z���̎��ԃJ�[�\����`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		int			nGraphKind			[I] �O���t�̎�ށi�����x or ���x�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphDraw::DrawTimeCursorSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԃJ�[�\����`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		CRect		rectScale			[I] �X�P�[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphDraw::DrawSelect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		X,Y,Z���̑I��͈͂�`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		int			nGraphKind			[I] �O���t�̎�ށi�����x or ���x�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDraw::DrawSelect(CDC &dc, const CGraphData &cGraphData, int nGraphKind)
{
	// �`�惂�[�h��ݒ�
	int ropModeOrg = dc.SetROP2(R2_NOTXORPEN);

	// �`��I�u�W�F�N�g��ݒ�
	CPen *pPenOrg = dc.SelectObject(&m_penSelect);
	CBrush *pBrushOrg = dc.SelectObject(&m_brushSelect);

	// �ȑO�̑I��͈͂�����
	DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_X));
	if (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) {
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Y));
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Z));
	}

	// �I��͈͂�ۑ�
	m_fSelectBegin = cGraphData.m_fSelectBegin;
	m_fSelectEnd = cGraphData.m_fSelectEnd;

	// �I��͈͂�`��
	DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_X));
	if (nGraphKind == GRAPH_ACCEL || nGraphKind == GRAPH_ZERO_OFFSET) {
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Y));
		DrawSelectSub(dc, cGraphData, GetScaleRect(SCALE_Z));
	}

	// �`��I�u�W�F�N�g��߂�
	dc.SelectObject(pPenOrg);
	dc.SelectObject(pBrushOrg);

	// �`�惂�[�h��߂�
	dc.SetROP2(ropModeOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::DrawSelectSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�I��͈͂�`��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc					[I] �f�o�C�X�R���e�L�X�g
//		CGraphData	&cGraphData			[I] �O���t�f�[�^�N���X
//		CRect		rectScale			[I] �X�P�[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphDraw::DispScrollBar
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		X,Y,Z���O���t�̃X�N���[���o�[��\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CScrollBar		&cScrollBarX			[I] X���O���t�̃X�N���[���o�[
//		CScrollBar		&cScrollBarY			[I] Y���O���t�̃X�N���[���o�[
//		CScrollBar		&cScrollBarZ			[I] Z���O���t�̃X�N���[���o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDraw::DispScrollBar(CScrollBar &cScrollBarX, CScrollBar &cScrollBarY, CScrollBar &cScrollBarZ)
{
	DispScrollBarSub(cScrollBarX, GetScaleRect(SCALE_X));
	DispScrollBarSub(cScrollBarY, GetScaleRect(SCALE_Y));
	DispScrollBarSub(cScrollBarZ, GetScaleRect(SCALE_Z));
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::DispScrollBarSub
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̃X�N���[���o�[��\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CScrollBar		&cScrollBar			[I] �O���t�̃X�N���[���o�[
//		CRect			rectScale			[I] �X�N���[���o�[�̕\���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphDraw::DispScrollBarSub(CScrollBar &cScrollBar, CRect rectScale)
{
	if (cScrollBar.m_hWnd) {
		int nWidth = ::GetSystemMetrics(SM_CXVSCROLL);
		cScrollBar.MoveWindow(rectScale.right + 2, rectScale.top, nWidth, rectScale.Height());
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphDraw::CheckGraphPoint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肵���ʒu���ǂ̃O���t���ł��邩���`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CPoint			point			[I] �`�F�b�N����ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int			0:�O���t�O�@1:X���O���t���@2:Y���O���t���@3:Z���O���t��
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
