//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrogramView.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g���O������̓O���t�̃E�B���h�E�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftSpectrogramDlg.h"
#include "FftSpectrogramView.h"
#include "Define.h"

// �X�N���[���o�[�̍ő�l
#define SCROLL_MAX		0x7ffffffe

// CFftSpectrogramView

IMPLEMENT_DYNAMIC(CFftSpectrogramView, CWnd)

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::CFftSpectrogramView
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
CFftSpectrogramView::CFftSpectrogramView()
{
	// �����o�ϐ���������
	m_sizeBitmap = 0;
	m_bDispGraph = FALSE;
	m_nCursorX = 0;
	m_nCursorY = 0;
	m_bTrackMouse = FALSE;
	m_penCursor.CreatePen(PS_DOT, 1, COLOR_GRAPH_CURSOR);
	m_bAutoScroll = FALSE;
}


BEGIN_MESSAGE_MAP(CFftSpectrogramView, CWnd)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	ON_WM_MOUSEMOVE()
	ON_WM_HSCROLL()
	ON_WM_NCHITTEST()
	ON_WM_NCLBUTTONDOWN()
	ON_WM_NCLBUTTONUP()
	ON_WM_LBUTTONDOWN()
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::PreSubclassWindow
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
void CFftSpectrogramView::PreSubclassWindow()
{
	// ����������
	Initialize();

	CWnd::PreSubclassWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnEraseBkgnd
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
BOOL CFftSpectrogramView::OnEraseBkgnd(CDC *pDC)
{
	// �������Ȃ�
	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnPaint
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
void CFftSpectrogramView::OnPaint()
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
		if (m_nCursorX >= m_cFftSpectrogramDraw.m_rectScale.left
				&& m_nCursorX <= m_cFftSpectrogramDraw.m_rectScale.right
				&& m_nCursorY >= m_cFftSpectrogramDraw.m_rectScale.top
				&& m_nCursorY <= m_cFftSpectrogramDraw.m_rectScale.bottom) {
			CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
			CPen *pPenOrg = dc.SelectObject(&m_penCursor);
			dc.MoveTo(m_nCursorX, rectScale.top);
			dc.LineTo(m_nCursorX, rectScale.bottom);
			dc.MoveTo(rectScale.left, m_nCursorY);
			dc.LineTo(rectScale.right, m_nCursorY);
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
//		CFftSpectrogramView::Initialize
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
void CFftSpectrogramView::Initialize()
{
	// �������f�o�C�X�R���e�L�X�g���쐬
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);

	// �����X�N���[���o�[�\��
	ShowScrollBar(SB_HORZ, TRUE);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
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
void CFftSpectrogramView::DispGraph(const double *pDataG, int nFreqData, int nTimeData, double fSampleRate, double fTimeMin, double fTimeMax, double fTimeOffset, double fTimeZoom, double fFreqMin, double fFreqMax, BOOL bFreqLog, double fLevelMin, double fLevelMax, BOOL bLevelLog)
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
	m_cFftSpectrogramDraw.DrawGraph(m_dcGraph, rectClinet, pDataG, nFreqData, nTimeData, fSampleRate, fTimeMin, fTimeMax, fTimeOffset, fTimeZoom, fFreqMin, fFreqMax, bFreqLog, fLevelMin, fLevelMax, bLevelLog);

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
//		CFftSpectrogramView::HideGraph
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
void CFftSpectrogramView::HideGraph()
{
	// �O���t�\���t���O���Z�b�g
	m_bDispGraph = FALSE;

	// ��ʕ`��
	Invalidate();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnMouseMove
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
void CFftSpectrogramView::OnMouseMove(UINT nFlags, CPoint point)
{
	if (!m_bAutoScroll) {
		CFftSpectrogramDlg *pWnd = (CFftSpectrogramDlg *)GetParent();

		// �}�E�X�J�[�\�����X�P�[��������΃J�[�\���ʒu�f�[�^��\������
		CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
		if (rectScale.PtInRect(point)) {
			if (!m_bTrackMouse) {
				TRACKMOUSEEVENT tme;
				tme.cbSize = sizeof(TRACKMOUSEEVENT);
				tme.dwFlags = TME_LEAVE;
				tme.hwndTrack = m_hWnd;
				::TrackMouseEvent(&tme);

				m_bTrackMouse = TRUE;
			}

			pWnd->SetCursor((double)(point.x - rectScale.left) / rectScale.Width(), (double)(rectScale.bottom - point.y) / rectScale.Height());
		} else {
			pWnd->SetCursor(0, 0);
		}
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnMouseLeave
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
LRESULT CFftSpectrogramView::OnMouseLeave(WPARAM wParam, LPARAM lParam)
{
	CFftSpectrogramDlg *pWnd = (CFftSpectrogramDlg *)GetParent();

	// �J�[�\���ʒu�f�[�^����������
	pWnd->SetCursor(0, 0);
	m_bTrackMouse = FALSE;

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::DispCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fPosX			[I] 0�`1�ɐ��K�����ꂽ�J�[�\����X���W
//		double		fPosY			[I] 0�`1�ɐ��K�����ꂽ�J�[�\����Y���W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramView::DispCursor(double fPosX, double fPosY)
{
	int nCursorX = 0;
	int nCursorY = 0;

	// �J�[�\����X���W���v�Z
	CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
	nCursorX = rectScale.left + (int)(rectScale.Width() * fPosX);

	// �J�[�\����Y���W���v�Z
	if (fPosY != 0) {
		nCursorY = rectScale.bottom - (int)(rectScale.Height() * fPosY + 0.5);
		if (nCursorY < rectScale.top || nCursorY > rectScale.bottom)
			nCursorY = 0;
	}

	// �ړ����Ă�����ĕ\��
	if (nCursorX != m_nCursorX || nCursorY != m_nCursorY) {
		m_nCursorX = nCursorX;
		m_nCursorY = nCursorY;
		Invalidate(FALSE);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnHScroll
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
void CFftSpectrogramView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// ���݂̃X�N���[�������擾
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

	if (nPos != si.nPos) {
		CFftSpectrogramDlg *pFftSpectrogramDlg = (CFftSpectrogramDlg *)GetParent();

		// �������X�N���[��
		pFftSpectrogramDlg->TimeScroll((double)nPos / si.nMax);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnNcHitTest
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��N���C�A���g�̈�q�b�g�e�X�g�����i�X�N���[���o�[��L���ɂ��邽�߂ɕK�v�j
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CPoint		point			[I] �J�[�\���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		DefWindowProc�̕Ԃ�l
//*****************************************************************************************************
LRESULT CFftSpectrogramView::OnNcHitTest(CPoint point)
{
	return ::DefWindowProc(m_hWnd, WM_NCHITTEST, 0, MAKELPARAM(point.x, point.y));
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnNcLButtonDown�i�X�N���[���o�[��L���ɂ��邽�߂ɕK�v�j
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��N���C�A���g�̈�}�E�X�_�E������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nHitTest		[I] �q�b�g�e�X�g
//		CPoint		point			[I] �J�[�\���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramView::OnNcLButtonDown(UINT nHitTest, CPoint point)
{
	::DefWindowProc(m_hWnd, WM_NCLBUTTONDOWN, nHitTest, MAKELPARAM(point.x, point.y));
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnNcLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��N���C�A���g�̈�}�E�X�A�b�v�����i�X�N���[���o�[��L���ɂ��邽�߂ɕK�v�j
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nHitTest		[I] �q�b�g�e�X�g
//		CPoint		point			[I] �J�[�\���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramView::OnNcLButtonUp(UINT nHitTest, CPoint point)
{
	::DefWindowProc(m_hWnd, WM_NCLBUTTONUP, nHitTest, MAKELPARAM(point.x, point.y));
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::SetTimeScrollBar
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�N���[�����ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimeOffset			[I] ���ԃI�t�Z�b�g
//		double		fTimeZoom			[I] ���ԃY�[��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramView::SetTimeScrollBar(double fTimeOffset, double fTimeZoom)
{
	SCROLLINFO si;

	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	si.nMax = SCROLL_MAX;
	si.nMin = 0;
	si.nPage = (int)(SCROLL_MAX / fTimeZoom);
	if (si.nPage == 0)
		si.nPage = 1;
	si.nPos = (int)(SCROLL_MAX * fTimeOffset);

	SetScrollInfo(SB_HORZ, &si);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramView::OnLButtonDown
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
void CFftSpectrogramView::OnLButtonDown(UINT nFlags, CPoint point)
{
	CFftSpectrogramDlg *pWnd = (CFftSpectrogramDlg *)GetParent();

	// �}�E�X�J�[�\�����X�P�[��������΃J�[�\���ʒu�f�[�^��\������
	CRect &rectScale = m_cFftSpectrogramDraw.m_rectScale;
	if (rectScale.PtInRect(point))
		pWnd->SetCursor((double)(point.x - rectScale.left) / rectScale.Width(), (double)(rectScale.bottom - point.y) / rectScale.Height());
	else
		pWnd->SetCursor(0, 0);

	CWnd::OnLButtonDown(nFlags, point);
}
