//*****************************************************************************************************
//  1. �t�@�C����
//		GraphView.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�O���t�̃E�B���h�E�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphView.h"
#include "GraphFrm.h"
#include "Define.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

// �}�E�X�ړ����[�h
#define MOUSE_MOVE_NONE		0
#define MOUSE_MOVE_SELECT	1
#define MOUSE_MOVE_SCROLL	2

// �X�N���[���o�[�̍ő�l
#define SCROLL_MAX		0x7ffffffe

// �c���̃X�N���[���o�[��ID
#define IDC_SCROLL_X	200
#define IDC_SCROLL_Y	201
#define IDC_SCROLL_Z	202

// CGraphView

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::CGraphView
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
CGraphView::CGraphView()
{
	// �����o�ϐ�������
	m_sizeBitmap = 0;
	m_bSetBitmap = FALSE;
	m_nMouseMoveMode = MOUSE_MOVE_NONE;
	m_bAutoScroll = FALSE;

	// ���\�[�X����J�[�\���擾
	m_hCursorHandClose = AfxGetApp()->LoadCursor(IDC_HANDCLOSE);
}


BEGIN_MESSAGE_MAP(CGraphView, CWnd)
	ON_WM_PAINT()
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	ON_WM_SIZE()
	ON_WM_CREATE()
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
	ON_WM_RBUTTONDOWN()
	ON_WM_RBUTTONUP()
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. �֐���
//		CGraphView::PreCreateWindow
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
BOOL CGraphView::PreCreateWindow(CREATESTRUCT& cs) 
{
	if (!CWnd::PreCreateWindow(cs))
		return FALSE;

	cs.dwExStyle |= WS_EX_CLIENTEDGE;
	cs.style &= ~WS_BORDER;
	cs.style |= WS_HSCROLL | WS_CLIPCHILDREN;
	cs.lpszClass = AfxRegisterWndClass(CS_HREDRAW|CS_DBLCLKS, 
		::LoadCursor(NULL, IDC_ARROW), reinterpret_cast<HBRUSH>(COLOR_WINDOW+1), NULL);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnPaint
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
void CGraphView::OnPaint() 
{
	CPaintDC dc(this);

	if (m_bSetBitmap) {
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();

		// �������f�o�C�X�R���e�L�X�g�Ƀr�b�g�}�b�v��ݒ�
		CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

		// �O���t�̃r�b�g�}�b�v����ʂɓ]��
		dc.BitBlt(0, 0, m_sizeBitmap.cx, m_sizeBitmap.cy, &m_dcGraph, 0, 0, SRCCOPY);

		// ���ԃJ�[�\����`��
		m_cDrawGraph.DrawTimeCursor(dc, pGraphFrame->m_cGraphData, pGraphFrame->m_nGraphKind);

		// �������f�o�C�X�R���e�L�X�g����r�b�g�}�b�v���폜
		m_dcGraph.SelectObject(pBitmapOrg);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnHScroll
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
void CGraphView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
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
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		CGraphData &cGraphData = pGraphFrame->m_cGraphData;

		// �������X�N���[��
		cGraphData.m_stTimeRange.fBegin = cGraphData.m_stTimeRange.fMin + (int)nPos * cGraphData.m_stTimeRange.fSpan / si.nMax;
		pGraphFrame->UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnVScroll
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
void CGraphView::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	SCROLLINFO si;

	// ���݂̃X�N���[�������擾
	pScrollBar->GetScrollInfo(&si);
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
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		CGraphData &cGraphData = pGraphFrame->m_cGraphData;

		// �c�����X�N���[��
		SDispRange *pLevelRange;
		switch (pScrollBar->GetDlgCtrlID()) {
		case IDC_SCROLL_X:
			pLevelRange = &cGraphData.m_stLevelRangeX;
			break;
		case IDC_SCROLL_Y:
			pLevelRange = &cGraphData.m_stLevelRangeY;
			break;
		case IDC_SCROLL_Z:
			pLevelRange = &cGraphData.m_stLevelRangeZ;
			break;
		}
		pLevelRange->fBegin = pLevelRange->fMin + (int)(si.nMax - si.nPage - nPos) * pLevelRange->fSpan / si.nMax;
		pGraphFrame->UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnSize
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
void CGraphView::OnSize(UINT nType, int cx, int cy)
{
	CWnd::OnSize(nType, cx, cy);

	// �O���t�X�V
	if (cx != 0 && cy != 0) {
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		pGraphFrame->UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphView::UpdateGraph()
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;

	// �N���C�A���g�̈�̃T�C�Y���擾
	CRect rectClinet;
	GetClientRect(rectClinet);
	CSize sizeView(rectClinet.Width(), rectClinet.Height());

	// �r�b�g�}�b�v�쐬
	CreateBitmap(sizeView);

	// �������f�o�C�X�R���e�L�X�g�Ƀr�b�g�}�b�v��ݒ�
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);

	// �O���t��`��
	m_cDrawGraph.DrawGraph(m_dcGraph, rectClinet, cGraphData, TRUE, pGraphFrame->m_nGraphKind);

	// �������f�o�C�X�R���e�L�X�g����r�b�g�}�b�v������
	m_dcGraph.SelectObject(pBitmapOrg);

	// �r�b�g�}�b�v�ݒ�t���O�ݒ�
	m_bSetBitmap = TRUE;

	// �X�N���[���o�[�ݒ�
	SetScrollBar();

	// �N���C�A���g�̈�𖳌���
	Invalidate(FALSE);

	// �E�B���h�E�X�V
	UpdateWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::CreateBitmap
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�r�b�g�}�b�v�쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CSize	sizeBitmap			[I] �r�b�g�}�b�v�̃T�C�Y
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphView::CreateBitmap(CSize sizeBitmap)
{
	// �N���C�A���g�̈�̃T�C�Y�ɍ��킹�ăr�b�g�}�b�v���쐬
	if (m_sizeBitmap != sizeBitmap) {
		m_sizeBitmap = sizeBitmap;

		CDC *pDC = GetDC();
		m_bmpGraph.DeleteObject();
		m_bmpGraph.CreateCompatibleBitmap(pDC, m_sizeBitmap.cx, m_sizeBitmap.cy);
		ReleaseDC(pDC);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::SetScrollBar
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�N���[���o�[�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphView::SetScrollBar()
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;
	SCROLLINFO si;

	// �����X�N���[���o�[�ݒ�
	MakeScrollInfo(cGraphData.m_stTimeRange, si, FALSE);
	SetScrollInfo(SB_HORZ, &si);

	// �w�������X�N���[���o�[�ݒ�
	MakeScrollInfo(cGraphData.m_stLevelRangeX, si, TRUE);
	m_cScrollBarX.SetScrollInfo(&si);

	// �x�������X�N���[���o�[�ݒ�
	MakeScrollInfo(cGraphData.m_stLevelRangeY, si, TRUE);
	m_cScrollBarY.SetScrollInfo(&si);

	// �y�������X�N���[���o�[�ݒ�
	MakeScrollInfo(cGraphData.m_stLevelRangeZ, si, TRUE);
	m_cScrollBarZ.SetScrollInfo(&si);

	m_cDrawGraph.DispScrollBar(m_cScrollBarX, m_cScrollBarY, m_cScrollBarZ);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::MakeScrollInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�N���[�����ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDispRange	&stDispRange		[I] �\���͈͏��
//		SCROLLINFO	&si					[O] �X�N���[�����
//		BOOL		bDir				[I] FASLE:�����X�N���[���@TRUE�F�����X�N���[��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphView::MakeScrollInfo(const SDispRange &stDispRange, SCROLLINFO &si, BOOL bDir)
{
	memset(&si, 0, sizeof(si));
	si.cbSize = sizeof(si);
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	si.nMax = SCROLL_MAX;
	si.nMin = 0;
	si.nPage = stDispRange.fDisp <= stDispRange.fSpan ? (int)(SCROLL_MAX * stDispRange.fDisp / stDispRange.fSpan) : SCROLL_MAX;
	if (!bDir)
		si.nPos = (int)(SCROLL_MAX * (stDispRange.fBegin - stDispRange.fMin) / stDispRange.fSpan);
	else
		si.nPos = si.nMax - si.nPage - (int)(SCROLL_MAX * (stDispRange.fBegin - stDispRange.fMin) / stDispRange.fSpan);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCREATESTRUCT		lpCreateStruct			[I] �E�B���h�E�쐬���\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0�F����@-1�F�G���[
//*****************************************************************************************************
int CGraphView::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// �������f�o�C�X�R���e�L�X�g���쐬
	CDC *pDC = GetDC();
	m_dcGraph.CreateCompatibleDC(pDC);
	ReleaseDC(pDC);

	// �c���̃X�N���[���o�[���쐬
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CRect rect(0, 0, 0, 0);
	m_cScrollBarX.Create(SBS_VERT | WS_CHILD | WS_VISIBLE, rect, this, IDC_SCROLL_X);
	if (pGraphFrame->m_nGraphKind == GRAPH_ACCEL || pGraphFrame->m_nGraphKind == GRAPH_ZERO_OFFSET) {
		m_cScrollBarY.Create(SBS_VERT | WS_CHILD | WS_VISIBLE, rect, this, IDC_SCROLL_Y);
		m_cScrollBarZ.Create(SBS_VERT | WS_CHILD | WS_VISIBLE, rect, this, IDC_SCROLL_Z);
	}

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnLButtonDown
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
void CGraphView::OnLButtonDown(UINT nFlags, CPoint point)
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;

	// ���ԃJ�[�\���ʒu�ݒ�
	pGraphFrame->SetTimeCursor(cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (point.x - m_cDrawGraph.m_rectScale.left) / m_cDrawGraph.m_rectScale.Width());

	if (cGraphData.m_fTimeCursor <= cGraphData.m_stTimeRange.fMin + cGraphData.m_fTimeDispEnd) {
		// �I��͈͂�������
		m_fSelectStartTime = cGraphData.m_fSelectBegin = cGraphData.m_fSelectEnd = cGraphData.m_fTimeCursor;
		pGraphFrame->UpdateSelectArea();

		// �}�E�X���L���v�`��
		SetCapture();

		// �}�E�X�ړ����[�h�ݒ�
		m_nMouseMoveMode = MOUSE_MOVE_SELECT;
	}

	CWnd::OnLButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnLButtonUp
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
void CGraphView::OnLButtonUp(UINT nFlags, CPoint point)
{
	// �}�E�X�������[�X
	if (GetCapture()->GetSafeHwnd() == m_hWnd)
		ReleaseCapture();

	// �}�E�X�ړ����[�h����
	m_nMouseMoveMode = MOUSE_MOVE_NONE;

	CWnd::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnMouseMove
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
void CGraphView::OnMouseMove(UINT nFlags, CPoint point)
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;
	double fSelect;
	SDispRange *pLevelRange;

	switch (m_nMouseMoveMode) {
	case MOUSE_MOVE_SELECT:		// �I��
		switch (pGraphFrame->m_nGraphKind) {
		case GRAPH_ACCEL:
			fSelect = cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (point.x - m_cDrawGraph.m_rectScale.left) / m_cDrawGraph.m_rectScale.Width();
			if (fSelect < cGraphData.m_stTimeRange.fBegin)
				fSelect = cGraphData.m_stTimeRange.fBegin;
			else if (fSelect > cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp)
				fSelect = cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp;
			if (fSelect > m_fSelectStartTime) {
				cGraphData.m_fSelectBegin = m_fSelectStartTime;
				cGraphData.m_fSelectEnd = fSelect;
			} else {
				cGraphData.m_fSelectBegin = fSelect;
				cGraphData.m_fSelectEnd = m_fSelectStartTime;
			}
			pGraphFrame->UpdateSelectArea();
			break;
		}
		break;
	case MOUSE_MOVE_SCROLL:		// �X�N���[����
		// �X�N���[�����Ȃ�}�E�X�̈ʒu�ɍ��킹�ăE�B���h�E���X�N���[��
		CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
		cGraphData.m_stTimeRange.fBegin = cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (m_pointScroll.x - point.x) / m_cDrawGraph.m_rectScale.Width();
		switch (m_nScroolGraph) {
		case 1:
			pLevelRange = &cGraphData.m_stLevelRangeX;
			break;
		case 2:
			pLevelRange = &cGraphData.m_stLevelRangeY;
			break;
		case 3:
			pLevelRange = &cGraphData.m_stLevelRangeZ;
			break;
		}
		pLevelRange->fBegin = pLevelRange->fBegin - pLevelRange->fDisp * (m_pointScroll.y - point.y) / m_cDrawGraph.m_rectScale.Height();
		pGraphFrame->UpdateGraph();
		m_pointScroll = point;
		break;
	}

	// ���ԃJ�[�\���ʒu�ݒ�
	if (!m_bAutoScroll)
		pGraphFrame->SetTimeCursor(cGraphData.m_stTimeRange.fBegin + cGraphData.m_stTimeRange.fDisp * (point.x - m_cDrawGraph.m_rectScale.left) / m_cDrawGraph.m_rectScale.Width());

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnRButtonDown
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
void CGraphView::OnRButtonDown(UINT nFlags, CPoint point)
{
	m_nScroolGraph = m_cDrawGraph.CheckGraphPoint(point);
	if (m_nScroolGraph != 0) {
		// �}�E�X�|�C���^�ʒu��ۑ�
		m_pointScroll = point;

		// �}�E�X���L���v�`��
		SetCapture();

		// �}�E�X�ړ����[�h�ݒ�
		m_nMouseMoveMode = MOUSE_MOVE_SCROLL;

		// �}�E�X�J�[�\����ݒ�
		m_hCursorSave = ::SetCursor(m_hCursorHandClose);
	}

	CWnd::OnRButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnRButtonUp
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
void CGraphView::OnRButtonUp(UINT nFlags, CPoint point)
{
	// �}�E�X�������[�X
	if (GetCapture()->GetSafeHwnd() == m_hWnd)
		ReleaseCapture();

	// �}�E�X�J�[�\����߂�
	if (m_nMouseMoveMode == MOUSE_MOVE_SCROLL) {
		::SetCursor(m_hCursorHandClose);
		m_nMouseMoveMode = MOUSE_MOVE_NONE;
	}

	CWnd::OnRButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::DispSelectArea
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�I��͈͕\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphView::DispSelectArea()
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;

	// �I��͈͂�`��
	CBitmap *pBitmapOrg = m_dcGraph.SelectObject(&m_bmpGraph);
	m_cDrawGraph.DrawSelect(m_dcGraph, cGraphData, pGraphFrame->m_nGraphKind);
	m_dcGraph.SelectObject(pBitmapOrg);

	// �N���C�A���g�̈�𖳌���
	Invalidate(FALSE);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphView::OnMouseWheel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�z�C�[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		short		zDelta			[I] ��]����
//		CPoint		pt				[I] �}�E�X�̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
BOOL CGraphView::OnMouseWheel(UINT nFlags, short zDelta, CPoint pt)
{
	CGraphFrame *pGraphFrame = (CGraphFrame *)GetParent();
	CGraphData &cGraphData = pGraphFrame->m_cGraphData;
	SDispRange *pLevelRange = NULL;

	ScreenToClient(&pt);
	switch (m_cDrawGraph.CheckGraphPoint(pt)) {
	case 1:
		pLevelRange = &cGraphData.m_stLevelRangeX;
		break;
	case 2:
		pLevelRange = &cGraphData.m_stLevelRangeY;
		break;
	case 3:
		pLevelRange = &cGraphData.m_stLevelRangeZ;
		break;
	}

	if (pLevelRange != NULL) {
		pLevelRange->fBegin = pLevelRange->fBegin + pLevelRange->fDisp * (zDelta / WHEEL_DELTA * 10) / m_cDrawGraph.m_rectScale.Height();
		pGraphFrame->UpdateGraph();
	}

	return TRUE;
}
