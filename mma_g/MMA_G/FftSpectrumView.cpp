//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrumView.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g����̓O���t�̃E�B���h�E�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftSpectrumDlg.h"
#include "FftSpectrumView.h"
#include "Define.h"


// CFftSpectrumView

IMPLEMENT_DYNAMIC(CFftSpectrumView, CWnd)

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::CFftSpectrumView
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
CFftSpectrumView::CFftSpectrumView()
{
	// �����o�ϐ���������
	m_sizeBitmap = 0;
	m_bDispGraph = FALSE;
	m_nCursorX = 0;
	m_bTrackMouse = FALSE;
	m_penCursor.CreatePen(PS_DOT, 1, COLOR_GRAPH_CURSOR);
}


BEGIN_MESSAGE_MAP(CFftSpectrumView, CWnd)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	ON_WM_MOUSEMOVE()
	ON_MESSAGE(WM_MOUSELEAVE, OnMouseLeave)
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::PreSubclassWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u�N���X������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumView::PreSubclassWindow()
{
	// ����������
	Initialize();

	CWnd::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::OnEraseBkgnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�i�`�揈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			*pDC			[I] �f�o�C�X�R���e�L�X�g�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CFftSpectrumView::OnEraseBkgnd(CDC *pDC)
{
	// �������Ȃ�
	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y�C���g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumView::OnPaint()
{
	CPaintDC dc(this);

	// �N���C�A���g�̈�̃T�C�Y���擾
	CRect rect;
	GetClientRect(&rect);

	if (m_bDispGraph) {
		// �O���t�̃r�b�g�}�b�v����ʂɓ]��
		CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);
		dc.BitBlt(0, 0, rect.Width(), rect.Height(), &m_dcGraph, 0, 0, SRCCOPY);

		// �J�[�\���`��
		if (m_nCursorX != 0) {
			CRect &rectScale = m_cFftSpectrumDraw.m_rectScale;
			CPen *pPenOrg = dc.SelectObject(&m_penCursor);
			dc.MoveTo(m_nCursorX, rectScale.top);
			dc.LineTo(m_nCursorX, rectScale.bottom);
			dc.SelectObject(pPenOrg);
		}

		m_dcGraph.SelectObject(pBitmapOrg);
	} else {
		// �O���[�œh��Ԃ�
		CBrush brush;
		brush.CreateSysColorBrush(COLOR_MENU);
		dc.FillRect(&rect, &brush);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::Initialize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumView::Initialize()
{
	// �������f�o�C�X�R���e�L�X�g���쐬
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
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
void CFftSpectrumView::DispGraph(const double *pDataX, const double *pDataY, const double *pDataZ, const double *pDataG, int nData, double fSampleRate, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
{
	if (m_hWnd == NULL)
		return;

	// �N���C�A���g�̈�̑傫���ɍ��킹�ăr�b�g�}�b�v���쐬
	CRect rectClinet;
	GetClientRect(&rectClinet);
	CSize sizeView(rectClinet.Width(), rectClinet.Height());
	if (m_sizeBitmap != sizeView) {
		m_sizeBitmap = sizeView;

		CDC *pDC = GetDC();
		m_bmpGraph.DeleteObject();
		m_bmpGraph.CreateCompatibleBitmap(pDC, m_sizeBitmap.cx, m_sizeBitmap.cy);
		ReleaseDC(pDC);
	}

	// �������f�o�C�X�R���e�L�X�g�Ƀr�b�g�}�b�v��ݒ�
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

	// �O���t�`��
	m_cFftSpectrumDraw.DrawGraph(m_dcGraph, rectClinet, pDataX, pDataY, pDataZ, pDataG, nData, fSampleRate, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

	// �������f�o�C�X�R���e�L�X�g����r�b�g�}�b�v�����
	m_dcGraph.SelectObject(pBitmapOrg);

	// �O���t�\���t���O�Z�b�g
	m_bDispGraph = TRUE;

	// �E�B���h�E�̖�����
	Invalidate(FALSE);

	// �E�B���h�E���X�V
	UpdateWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::HideGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumView::HideGraph()
{
	// �O���t�\���t���O���Z�b�g
	m_bDispGraph = FALSE;

	// ��ʕ`��
	Invalidate();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ړ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint		point			[I] �}�E�X�̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumView::OnMouseMove(UINT nFlags, CPoint point)
{
	CFftSpectrumDlg *pWnd = (CFftSpectrumDlg *)GetParent();

	// �}�E�X�J�[�\�����X�P�[��������΃J�[�\���ʒu�f�[�^��\������
	CRect &rectScale = m_cFftSpectrumDraw.m_rectScale;
	if (rectScale.PtInRect(point)) {
		if (!m_bTrackMouse) {
			TRACKMOUSEEVENT tme;
			tme.cbSize = sizeof(TRACKMOUSEEVENT);
			tme.dwFlags = TME_LEAVE;
			tme.hwndTrack = m_hWnd;
			::TrackMouseEvent(&tme);

			m_bTrackMouse = TRUE;
		}

		pWnd->DispCursorData((double)(point.x - rectScale.left) / rectScale.Width(), (double)(rectScale.bottom - point.y) / rectScale.Height());
	} else {
		pWnd->DispCursorData(0, 0);
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::OnMouseLeave
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�J�[�\�����E�B���h�E�O�ֈړ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam			[I] WM_MOUSELEAVE���b�Z�[�W�p�����[�^
//		LPARAM		lParam			[I] WM_MOUSELEAVE���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		0
//*****************************************************************************************************
LRESULT CFftSpectrumView::OnMouseLeave(WPARAM wParam, LPARAM lParam)
{
	CFftSpectrumDlg *pWnd = (CFftSpectrumDlg *)GetParent();

	// �J�[�\���ʒu�f�[�^����������
	pWnd->DispCursorData(0, 0);
	m_bTrackMouse = FALSE;

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrumView::DispCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fPosX			[I] 0�`1�ɐ��K�����ꂽ�J�[�\���̈ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrumView::DispCursor(double fPosX)
{
	int nCursorX = 0;

	if (fPosX != 0) {
		CRect &rectScale = m_cFftSpectrumDraw.m_rectScale;
		nCursorX = rectScale.left + (int)(rectScale.Width() * fPosX + 0.5);
		if (nCursorX < rectScale.left || nCursorX > rectScale.right)
			nCursorX = 0;
	}

	if (nCursorX != m_nCursorX) {
		m_nCursorX = nCursorX;
		Invalidate(FALSE);
	}
}
