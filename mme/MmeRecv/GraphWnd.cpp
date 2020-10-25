//*****************************************************************************************************
//  1. �t�@�C����
//		GraphWnd.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�E�B���h�E�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "GraphWnd.h"
#include "GraphFrm.h"
#include <math.h>

// �O���t�Ŏg���F
#define COLOR_GRAPH_SCALE	RGB(0x00,0x00,0x00)
#define COLOR_GRAY			RGB(0x80,0x80,0x80)
#define COLOR_LIGHT_GRAY	RGB(0xc0,0xc0,0xc0)
#define COLOR_WHITE			RGB(0xff,0xff,0xff)
#define COLOR_ZOOM			RGB(0x00,0x00,0x00)

// �}�E�X���
#define MOUSE_OFF		0
#define MOUSE_LEFT		1
#define MOUSE_RIGHT		2

// Data Line�̐F
const COLORREF CGraphWnd::m_aGraphColor[N_UNIT][N_GRAPH] = {
	{RGB(0x00, 0xee, 0x00), RGB(0xff, 0xee, 0x00), RGB(0xee, 0x00, 0xee), RGB(0x00, 0xee, 0xff), RGB(0xee, 0x00, 0x00)},
	{RGB(0x00, 0xbb, 0x00), RGB(0xcc, 0xbb, 0x00), RGB(0xbb, 0x00, 0xbb), RGB(0x00, 0xbb, 0xcc), RGB(0xbb, 0x00, 0x00)},
	{RGB(0x00, 0x88, 0x00), RGB(0xaa, 0x88, 0x00), RGB(0x88, 0x00, 0x88), RGB(0x00, 0x88, 0xaa), RGB(0x88, 0x00, 0x00)}
};

// CGraphWnd

IMPLEMENT_DYNAMIC(CGraphWnd, CWnd)

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::CGraphWnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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



// CGraphWnd ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CREATESTRUCT		&cs			[I] �E�B���h�E�쐬���\����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
void CGraphWnd::PreSubclassWindow()
{
	// �������f�o�C�X�R���e�L�X�g���쐬
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);

	// �y�����쐬
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

	// �t�H���g���쐬
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
//  1. �֐���
//		CGraphWnd::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CREATESTRUCT		&cs			[I] �E�B���h�E�쐬���\����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
void CGraphWnd::OnPaint()
{
	CPaintDC dc(this);

	if (m_bSetBitmap) {
		// �������f�o�C�X�R���e�L�X�g�Ƀr�b�g�}�b�v��ݒ�
		CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

		// �O���t�̃r�b�g�}�b�v����ʂɓ]��
		dc.BitBlt(0, 0, m_sizeView.cx, m_sizeView.cy, &m_dcGraph, 0, 0, SRCCOPY);

		// �}�E�X�ŃY�[�����Ȃ�l�p��`��
		if (m_nMouseMode == MOUSE_RIGHT) {
			CBrush *pBrushOrg = (CBrush *)dc.SelectStockObject(NULL_BRUSH);
			CPen *pPenOrg = (CPen *)dc.SelectObject(&m_penZoom);
			dc.Rectangle(CRect(m_pointZoom1, m_pointZoom2));
			dc.SelectObject(pPenOrg);
			dc.SelectObject(pBrushOrg);
		}

		// �������f�o�C�X�R���e�L�X�g����r�b�g�}�b�v���폜
		m_dcGraph.SelectObject(pBitmapOrg);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnEraseBkgnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�i��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC		*pDC			[I] �f�o�C�X�R���e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�e�N���X�̕Ԓl
//*****************************************************************************************************
BOOL CGraphWnd::OnEraseBkgnd(CDC* pDC)
{
	// �w�i�𔒂œh��Ԃ�
	CRect rectClient;
	GetClientRect(rectClient);
	pDC->FillSolidRect(rectClient, RGB(255, 255, 255));

	return CWnd::OnEraseBkgnd(pDC);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�T�C�Y�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nType			[I] �T�C�Y�ύX�̃^�C�v
//		int		cx				[I] �E�B���h�E�̕�
//		int		cy				[I] �E�B���h�E�̍���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnSize(UINT nType, int cx, int cy)
{
	CWnd::OnSize(nType, cx, cy);

	DrawGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphData	&oGraphData			[I] �O���t�f�[�^
//		SGraphLine	&oGraphLine			[I] �O���t�f�[�^���C���\�����
//		LPCTSTR		fTimeRange1			[I] �\���J�n����
//		LPCTSTR		fTimeRange2			[I] �\���I������
//		double		fTempRange1			[I] ���x�͈͉���
//		double		fTempRange2			[I] ���x�͈͏��
//		double		fHeaterRange1		[I] �q�[�^�d������
//		double		fHeaterRange2		[I] �q�[�^�d�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphWnd::DrawGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::DrawGraph()
{
	if (m_pGraphData == NULL)
		return;

	// �f�[�^��������΃O���t��\�����Ȃ�
	if (m_pGraphData->GetDataNum() == 0) {
		m_bSetBitmap = FALSE;
		Invalidate(TRUE);
		return;
	}

	// �X�P�[���̃T�C�Y������
	SetScaleRect();

	// �r�b�g�}�b�v���쐬
	CreateBitmap();

	// �������f�o�C�X�R���e�L�X�g�Ƀr�b�g�}�b�v��ݒ�
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

	// �w�i��h��Ԃ�
	m_dcGraph.FillSolidRect(m_rectView, COLOR_WHITE);

	// �X�P�[����`��
	DrawScale();

	// �f�[�^�̕`��͈͂��擾
	int nStartIndex, nEndIndex;
	GetDataRange(nStartIndex, nEndIndex);

	// ���[�W������ݒ�
	CRgn rgnGraph;
	rgnGraph.CreateRectRgn(m_rectScale.left, m_rectScale.top, m_rectScale.right, m_rectScale.bottom);
	m_dcGraph.SelectClipRgn(&rgnGraph);

	// �f�[�^��`��
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

	// ���[�W����������
	m_dcGraph.SelectClipRgn(NULL);

	// �������f�o�C�X�R���e�L�X�g����r�b�g�}�b�v������
	m_dcGraph.SelectObject(pBitmapOrg);

	Invalidate(FALSE);

	m_bSetBitmap = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::SetScaleRect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�X�P�[���T�C�Y�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::SetScaleRect()
{
	// �e�L�X�g�T�C�Y�擾
	CFont *pFontOrg = m_dcGraph.SelectObject(&m_fontGraph);
	TEXTMETRIC tm;
	m_dcGraph.GetTextMetrics(&tm);
	m_dcGraph.SelectObject(pFontOrg);

	// �O���t�̈ʒu�Ƒ傫��
	GetClientRect(m_rectView);
	m_rectScale.left = m_rectView.left + tm.tmAveCharWidth * 4 + tm.tmHeight * 2;
	m_rectScale.top = m_rectView.top + 10;
	m_rectScale.right = m_rectView.right - (tm.tmAveCharWidth * 4 + tm.tmHeight * 2);
	m_rectScale.bottom = m_rectView.bottom - tm.tmHeight * 3 - 2;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::CreateBitmap
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�r�b�g�}�b�v�쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::CreateBitmap()
{
	CSize sizeView(m_rectView.Width(), m_rectView.Height());

	// �N���C�A���g�̈�̃T�C�Y�ɍ��킹�ăr�b�g�}�b�v���쐬
	if (m_sizeView != sizeView) {
		m_sizeView = sizeView;

		CDC *pDC = GetDC();
		m_bmpGraph.DeleteObject();
		m_bmpGraph.CreateCompatibleBitmap(pDC, m_sizeView.cx, m_sizeView.cy);
		ReleaseDC(pDC);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::GetDataRange
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		&nStartIndex		[I/O] �O���t�f�[�^
//		int		&nEndIndex			[I/O] �O���t�f�[�^���C���\�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::GetDataRange(int &nStartIndex, int &nEndIndex)
{
	int nDataNum = m_pGraphData->GetDataNum();
	nStartIndex = 0;
	nEndIndex = nDataNum;

	// �w�肳�ꂽ���Ԕ͈͂̃f�[�^�̃C���f�b�N�X�����߂�
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
//  1. �֐���
//		CGraphWnd::DrawScale
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�X�P�[���`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::DrawScale()
{
	CString sText;
	CSize sizeText;
	int x, y;
	double fStep;
	double t;
	int ns;

	// �`��I�u�W�F�N�g��ݒ�
	CPen *pPenOrg = m_dcGraph.SelectObject(&m_penGraphScale);
	CFont *pFontOrg = m_dcGraph.SelectObject(&m_fontGraph);
	CBrush *pBrushOrg = (CBrush *)m_dcGraph.SelectStockObject(NULL_BRUSH);

	// �e�L�X�g�T�C�Y�擾
	TEXTMETRIC tm;
	m_dcGraph.GetTextMetrics(&tm);

	// ������`��
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

	// �c����`��
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

	// �O�g��`��
	m_dcGraph.SelectObject(&m_penGraphScale);
	CRect rectScale2(m_rectScale);
	rectScale2.InflateRect(1, 0, 2, 1);
	m_dcGraph.Rectangle(&rectScale2);

	// ���Ԏ��̕�����`��
	sText = "Time [hh:mm:ss](GMT)";
	sizeText = m_dcGraph.GetOutputTextExtent(sText);
	m_dcGraph.TextOut(m_rectScale.left + (m_rectScale.Width() - sizeText.cx) / 2, rectClinet.bottom - (sizeText.cy + 4), sText);

	// ���x���̕�����`��
	m_dcGraph.SelectObject(&m_fontGraph2);
	sText = "Temperature [degC]";
	sizeText = m_dcGraph.GetOutputTextExtent(sText);
	m_dcGraph.TextOut(rectClinet.left + 3, m_rectScale.top + (m_rectScale.Height() + sizeText.cx) / 2, sText);

	// �q�[�^�d�����̕�����`��
	m_dcGraph.SelectObject(&m_fontGraph3);
	sText = "Heater Voltage [V]";
	sizeText = m_dcGraph.GetOutputTextExtent(sText);
	m_dcGraph.TextOut(rectClinet.right - 3, m_rectScale.top + (m_rectScale.Height() - sizeText.cx) / 2, sText);

	// �`��I�u�W�F�N�g�����ɖ߂�
	m_dcGraph.SelectObject(pPenOrg);
	m_dcGraph.SelectObject(pFontOrg);
	m_dcGraph.SelectObject(pBrushOrg);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::DrawData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nUnit			[I] ���j�b�g�ԍ�
//		int		nGraph			[I] �O���t��ޔԍ�
//		int		nStartIndex		[I] �f�[�^�J�n�C���f�b�N�X
//		int		nEndIndex		[I] �f�[�^�I���C���f�b�N�X
//		CPen	&oPen			[I] �f�[�^���C����`�悷��y��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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

	// �|�����C���p�z�񏉊���
	int nPointSize = (m_rectScale.Width() + 2) * 4;
	pGraphPoint = new POINT[nPointSize];

	// �|�����C�����v�Z
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

	// �y����ݒ�
	CPen *pPenOrg = m_dcGraph.SelectObject(&oPen);

	// �|�����C����`��
	m_dcGraph.Polyline(pGraphPoint, nGraphPoint);

	// �y����߂�
	m_dcGraph.SelectObject(pPenOrg);

	// �|�����C���p�z����
	delete [] pGraphPoint;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::GetLinearScaleStep
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j�A�X�P�[���̖ڐ�����̃X�e�b�v���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fRange		[I] �X�P�[���̃����W���i�ő�l�ƍŏ��l�̍��j
//		int			nPixel		[I] �X�P�[���̃s�N�Z����
//		int			*pStep		[O] �X�e�b�v�̕�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		double		�P�ڐ���Ԃ̃X�e�b�v�l
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
//  1. �֐���
//		CGraphWnd::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nSBCode			[I] �X�N���[���v���R�[�h
//		UINT		nPos			[I] �X�N���[���{�b�N�X�̈ʒu
//		CScrollBar	*pScrollBar		[I] �X�N���[���o�[�R���g���[���ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// ���݂̃X�N���[�������擾
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	GetScrollInfo(SB_HORZ, &si);
	int nPageSize = si.nPage;
	int nLineSize = nPageSize / 32;
	if (nLineSize == 0)
		nLineSize = 1;

	// �X�N���[���ʒu���X�V
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

	// �X�N���[���ʒu���t���[���E�B���h�E�ɒʒm
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
	pGraphFrm->ScrollTime((double)nPos / si.nMax);

	CWnd::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnVScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nSBCode			[I] �X�N���[���v���R�[�h
//		UINT		nPos			[I] �X�N���[���{�b�N�X�̈ʒu
//		CScrollBar	*pScrollBar		[I] �X�N���[���o�[�R���g���[���ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// ���݂̃X�N���[�������擾
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	GetScrollInfo(SB_VERT, &si);
	int nPageSize = si.nPage;
	int nLineSize = nPageSize / 32;
	if (nLineSize == 0)
		nLineSize = 1;

	// �X�N���[���ʒu���X�V
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

	// �X�N���[���ʒu���t���[���E�B���h�E�ɒʒm
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
	pGraphFrm->ScrollLevel((double)(si.nMax - si.nPage - nPos) / si.nMax);

	CWnd::OnVScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���_�E������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnLButtonDown(UINT nFlags, CPoint point)
{
	if (m_pGraphData != NULL && m_nMouseMode == MOUSE_OFF) {
		m_nMouseMode = MOUSE_LEFT;

		// �}�E�X�|�C���^�ʒu��ۑ�
		m_pointScroll = point;

		// �}�E�X���L���v�`��
		SetCapture();

		// �}�E�X�J�[�\����ݒ�
		m_hCursorSave = ::SetCursor(AfxGetApp()->LoadCursor(IDC_CURSOR_HAND_CLOSE));
	}

	CWnd::OnLButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���A�b�v����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnLButtonUp(UINT nFlags, CPoint point)
{
	if (m_nMouseMode == MOUSE_LEFT) {
		m_nMouseMode = MOUSE_OFF;

		// �}�E�X�������[�X
		if (GetCapture()->GetSafeHwnd() == m_hWnd)
			ReleaseCapture();

		// �}�E�X�J�[�\����߂�
		::SetCursor(m_hCursorSave);
	}

	CWnd::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnMouseMove(UINT nFlags, CPoint point)
{
	switch (m_nMouseMode) {
	case MOUSE_LEFT:
		// �X�N���[���ʒu���t���[���E�B���h�E�ɒʒm
		((CGraphFrm *)GetParent())->MouseScroll((double)(m_pointScroll.x - point.x) / m_rectScale.Width(), (double)(point.y - m_pointScroll.y) / m_rectScale.Height());
		m_pointScroll = point;
		break;
	case MOUSE_RIGHT:
		// �Y�[���͈͂�ۑ�
		if (m_rectScale.PtInRect(point)) {
			m_pointZoom2 = point;
			Invalidate(FALSE);
		}
		break;
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnRButtonDown
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�}�E�X�{�^���_�E������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnRButtonDown(UINT nFlags, CPoint point)
{
	if (m_pGraphData != NULL && m_nMouseMode == MOUSE_OFF) {
		if (m_rectScale.PtInRect(point)) {
			m_nMouseMode = MOUSE_RIGHT;

			// �}�E�X�|�C���^�ʒu��ۑ�
			m_pointZoom1 = m_pointZoom2 = point;

			// �}�E�X���L���v�`��
			SetCapture();
		}
	}

	CWnd::OnRButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::OnRButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�}�E�X�{�^���A�b�v����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnRButtonUp(UINT nFlags, CPoint point)
{
	if (m_nMouseMode == MOUSE_RIGHT) {
		m_nMouseMode = MOUSE_OFF;

		// �}�E�X�������[�X
		if (GetCapture()->GetSafeHwnd() == m_hWnd)
			ReleaseCapture();

		// �Y�[���͈͂��t���[���E�B���h�E�ɒʒm
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
//  1. �֐���
//		CGraphWnd::OnRButtonDblClk
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�}�E�X�{�^���_�u���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::OnRButtonDblClk(UINT nFlags, CPoint point)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	// �Y�[������
	pGraphFrm->ZoomReset();

	CWnd::OnRButtonDblClk(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphWnd::SetHighlight
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����\������f�[�^���C���̐ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nUnit			[I] ���j�b�g�ԍ�
//		int		nGraph			[I] �O���t��ޔԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphWnd::SetHighlight(int nUnit, int nGraph)
{
	m_nHighlightUnit = nUnit;
	m_nHighlightGraph = nGraph;
}
