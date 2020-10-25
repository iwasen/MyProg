//*****************************************************************************************************
//  1. �t�@�C����
//		SchematicView.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�E�@�\�n���}�E�@�\�����}��Flash�\���p�r���[����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "SchematicFrm.h"
#include "SchematicView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

// CSchematicView

IMPLEMENT_DYNCREATE(CSchematicView, CWnd)

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::CSchematicView
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CSchematicView::CSchematicView()
{
	// �����o�ϐ�������������
	m_fZoomRatio = 1;
	m_bInScroll = FALSE;
	m_bScroll = FALSE;
	m_pMenuDlg = NULL;
	m_bQuickMode = FALSE;
	m_hCursorHandClose = AfxGetApp()->LoadCursor(IDC_HANDCLOSE);
}


BEGIN_MESSAGE_MAP(CSchematicView, CWnd)
	ON_WM_PAINT()
	ON_WM_SIZE()
	ON_WM_HSCROLL()
	ON_WM_VSCROLL()
	ON_WM_CREATE()
	ON_MESSAGE(WM_USER_ZOOM_IN, OnUserZoomIn)
	ON_MESSAGE(WM_USER_ZOOM_OUT, OnUserZoomOut)
	ON_MESSAGE(WM_USER_SIZE_RESET, OnUserSizeReset)
	ON_MESSAGE(WM_USER_PRINT, OnUserPrint)
	ON_MESSAGE(WM_USER_EXIT, OnUserExit)
	ON_MESSAGE(WM_USER_ACTION, OnUserAction)
	ON_MESSAGE(WM_USER_PAUSE, OnUserPause)
	ON_MESSAGE(WM_USER_SLOW, OnUserSlow)
	ON_MESSAGE(WM_USER_NORMAL, OnUserNormal)
	ON_WM_LBUTTONDOWN()
	ON_WM_LBUTTONUP()
	ON_WM_MOUSEMOVE()
#ifdef _DEVELOP
	ON_WM_RBUTTONUP()
	ON_COMMAND(IDM_QUICK_MODE, &CSchematicView::OnQuickMode)
	ON_COMMAND(IDM_SCREEN_COPY, &CSchematicView::OnScreenCopy)
	ON_COMMAND(IDM_START_ALL, &CSchematicView::OnStartAll)
#endif
END_MESSAGE_MAP()



// CSchematicView ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CREATESTRUCT&		cs			[I] �E�B���h�E�������p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSchematicView::PreCreateWindow(CREATESTRUCT& cs) 
{
	if (!CWnd::PreCreateWindow(cs))
		return FALSE;

	cs.dwExStyle |= WS_EX_CLIENTEDGE;
	cs.style &= ~WS_BORDER;
	cs.lpszClass = AfxRegisterWndClass(/*CS_HREDRAW|CS_VREDRAW|*/CS_DBLCLKS, 
		::LoadCursor(NULL, IDC_ARROW), reinterpret_cast<HBRUSH>(COLOR_WINDOW+1), NULL);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E�쐬������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCREATESTRUCT		lpCreateStruct			[I] �E�B���h�E�������p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0
//*****************************************************************************************************
int CSchematicView::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// �t���[���E�B���h�E�̃|�C���^��ۑ�
	m_pSchematicFrm = (CSchematicFrame *)GetParent();

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y�C���g(WM_PAINT)���b�Z�[�W�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnPaint() 
{
	// �\����Flash���s���̂ŁA�r���[���͕̂`��͍s��Ȃ�
	CPaintDC dc(this);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::ShowSchematic
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�\�E�B���h�E�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CRuntimeClass	*pMenuClass			[I] ���상�j���[�N���X
//		CString			&sFlashFileName		[I] Flash�̃t�@�C����
//		int				nActionCode			[I] �A�N�V�����R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::ShowSchematic(CRuntimeClass *pMenuClass, const CString &sFlashFileName, int nActionCode)
{
	// Flash�E�B���h�E�̑傫����������
	InitFlashRect();

	// Flash�E�B���h�E���쐬
	if (m_cFlash.m_hWnd != NULL)
		m_cFlash.DestroyWindow();
	m_cFlash.Create(NULL, NULL, WS_CHILD, m_rectFlash, this, 0);

	// Flash�E�B���h�E���X�N���[��
	ScrollFlashWindow();

	// swf�t�@�C����\��
	CString sFullPath;
	_fullpath(sFullPath.GetBuffer(MAX_PATH), CSchematic::m_sFlashDir + "\\" + sFlashFileName, MAX_PATH);
	sFullPath.ReleaseBuffer();
	sFullPath += "?";
	m_cFlash.LoadMovie(0, sFullPath);

	// �X�e�[�W�̃T�C�Y���擾
	m_sizeStage.cx = atoi(m_cFlash.GetVariable("stageWidth"));
	m_sizeStage.cy = atoi(m_cFlash.GetVariable("stageHeight"));

	if (nActionCode != 0)
		FlashAction(nActionCode, NULL);
	m_cFlash.ShowWindow(SW_SHOW);

	// ���상�j���[��\��
	if (m_pMenuDlg != NULL)
		m_pMenuDlg->DestroyWindow();
	if (pMenuClass != NULL) {
		m_pMenuDlg = (CMenuCommon *)pMenuClass->CreateObject();
		if (m_pMenuDlg != NULL)
			m_pMenuDlg->DoModeless(this);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�T�C�Y�ύX���b�Z�[�W(WM_SIZE)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT	nType		[I] �T�C�Y�ύX�̃^�C�v
//		int		cx			[I] �N���C�A���g�̈�̕�
//		int		cy			[I] �N���C�A���g�̈�̍���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnSize(UINT nType, int cx, int cy)
{
	CWnd::OnSize(nType, cx, cy);

	// ���݂̃r���[�T�C�Y��ۑ�
	m_sizeView.SetSize(cx, cy);

	// Flash�E�B���h�E�̃X�N���[������
	if (m_cFlash.m_hWnd != NULL)
		ScrollFlashWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[�����b�Z�[�W(WM_HSCROLL)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT		nSBCode			[I] �X�N���[���v���R�[�h
//		UINT		nPos			[I] �X�N���[���{�b�N�X�̈ʒu
//		CScrollBar*	pScrollBar		[I] �X�N���[���o�[�R���g���[��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	// Flash�E�B���h�E�̍��W���X�V
	m_rectFlash.MoveToX(-GetNextScrollPos(SB_HORZ, nSBCode, nPos));

	// Flash�E�B���h�E�̃X�N���[������
	ScrollFlashWindow();

	CWnd::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnVScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[�����b�Z�[�W(WM_VSCROLL)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT		nSBCode			[I] �X�N���[���v���R�[�h
//		UINT		nPos			[I] �X�N���[���{�b�N�X�̈ʒu
//		CScrollBar*	pScrollBar		[I] �X�N���[���o�[�R���g���[��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	// Flash�E�B���h�E�̍��W���X�V
	m_rectFlash.MoveToY(-GetNextScrollPos(SB_VERT, nSBCode, nPos));

	// Flash�E�B���h�E�̃X�N���[������
	ScrollFlashWindow();

	CWnd::OnVScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnLButtonDown
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���_�E�����b�Z�[�W(WM_LBUTTONDOWN)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT	nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint	point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnLButtonDown(UINT nFlags, CPoint point)
{
	if (GetStyle() & (WS_HSCROLL | WS_VSCROLL)) {
		// �}�E�X�|�C���^�ʒu��ۑ�
		m_pointScroll = point;

		// �}�E�X���L���v�`��
		SetCapture();
	}

	CWnd::OnLButtonDown(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnLButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���}�E�X�{�^���A�b�v���b�Z�[�W(WM_LBUTTONUP)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT	nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint	point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnLButtonUp(UINT nFlags, CPoint point)
{
	// �}�E�X�J�[�\�������ɖ߂��ăX�N���[�����I������
	if (GetCapture()->GetSafeHwnd() == m_hWnd) {
		::SetCursor(m_hCursorHandClose);
		m_bScroll = FALSE;
		ReleaseCapture();
	}

	CWnd::OnLButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnMouseMove
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�ړ����b�Z�[�W(WM_MOUSEMOVE)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT	nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint	point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnMouseMove(UINT nFlags, CPoint point)
{
	if (m_bScroll) {
		// �X�N���[�����Ȃ�}�E�X�̈ʒu�ɍ��킹�ăE�B���h�E���X�N���[��
		m_rectFlash.MoveToX(-GetNextScrollPos(SB_HORZ, SB_THUMBTRACK, m_pointScroll.x - point.x - m_rectFlash.left));
		m_rectFlash.MoveToY(-GetNextScrollPos(SB_VERT, SB_THUMBTRACK, m_pointScroll.y - point.y - m_rectFlash.top));
		ScrollFlashWindow();
		m_cFlash.UpdateWindow();
		m_pointScroll = point;
	} else {
		// ���}�E�X�{�^����������Ă���΃}�E�X�J�[�\����ύX���A�X�N���[�����t���O��ON
		if (GetCapture()->GetSafeHwnd() == m_hWnd) {
			m_bScroll = TRUE;
			m_hCursorSave = ::SetCursor(m_hCursorHandClose);
		}
	}

	CWnd::OnMouseMove(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E���b�Z�[�W�̃t�B���^����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		MSG*	pMsg			[I] ���b�Z�[�W�f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	CWnd::PreTranslateMessage(pMsg)
//*****************************************************************************************************
BOOL CSchematicView::PreTranslateMessage(MSG* pMsg)
{
	switch (pMsg->message) {
	case WM_LBUTTONDOWN:
	case WM_LBUTTONUP:
	case WM_RBUTTONDOWN:
	case WM_RBUTTONUP:
	case WM_MOUSEMOVE:
		// Flash�E�B���h�E�̉��̃r���[�E�B���h�E�Ƀ}�E�X���b�Z�[�W��]������
		{
			char sClassName[256];
			GetClassName(pMsg->hwnd, sClassName, sizeof(sClassName));
			if (strcmp(sClassName, "MacromediaFlashPlayerActiveX") == 0) {
				if (pMsg->message == WM_RBUTTONDOWN)
					return TRUE;
				CPoint point((int)(pMsg->lParam & 0xFFFF), (int)((pMsg->lParam >> 16) & 0xFFFF));
				HWND hParent = ::GetParent(pMsg->hwnd);
				if (hParent != NULL) {
					::MapWindowPoints(pMsg->hwnd, hParent, &point, 1);
					::SendMessage(hParent, pMsg->message, pMsg->wParam, point.x | (point.y << 16));
				}
			}
		}
		break;
	}

	return CWnd::PreTranslateMessage(pMsg);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserZoomIn
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[�́u�g��v�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		�g�債�Ă�Flash�̃T�C�Y��4096�ȓ��Ȃ��TRUE�A������ꍇ��FALSE�i4096�����x�̂��߁j
//*****************************************************************************************************
LRESULT CSchematicView::OnUserZoomIn(WPARAM wParam, LPARAM lParam)
{
	// 1.25�{�Ɋg�傷��
	SetZoomRatio(m_fZoomRatio * 1.25);

	// �ēx�g��\���`�F�b�N����
	double fScaleX = m_rectFlash.Width() / (double)m_sizeStage.cx;
	double fScaleY = m_rectFlash.Height() / (double)m_sizeStage.cy;
	double fScale = min(fScaleX, fScaleY);
	int nRight = (m_rectFlash.Width() + (int)(m_sizeStage.cx * fScale)) / 2;
	int nBottom = (m_rectFlash.Height() + (int)(m_sizeStage.cy * fScale)) / 2;
	return max(nRight, nBottom) * 1.25 <= 4096;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserZoomOut
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[�́u�k���v�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		�g�嗦��0.25�ȏ�Ȃ�TRUE�A����ȊO��FALSE
//*****************************************************************************************************
LRESULT CSchematicView::OnUserZoomOut(WPARAM wParam, LPARAM lParam)
{
	// 1/1.25�ɏk������
	SetZoomRatio(m_fZoomRatio / 1.25);

	return m_fZoomRatio >= 0.25;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserSizeReset
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[�́u�T�C�Y���Z�b�g�v�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserSizeReset(WPARAM wParam, LPARAM lParam)
{
	// �X�N���[���o�[������
	m_bInScroll = TRUE;
	ShowScrollBar(SB_BOTH, FALSE);
	m_bInScroll = FALSE;

	// Flash�E�B���h�E���X�N���[������
	InitFlashRect();
	ScrollFlashWindow();

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserPrint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[�́u����v�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserPrint(WPARAM wParam, LPARAM lParam)
{
	CRect rectView(0, 0, m_sizeView.cx, m_sizeView.cy);
	double fStageWidth = m_sizeStage.cx;
	double fStageHeight = m_sizeStage.cy;
	double fRatio = min(m_rectFlash.Width() / fStageWidth, m_rectFlash.Height() / fStageHeight);
	CPoint pointCenter = m_rectFlash.CenterPoint();
	CPoint pointLeftTop(pointCenter.x - (int)(fStageWidth / 2 * fRatio), pointCenter.y - (int)(fStageHeight / 2 * fRatio));
	rectView.MoveToXY(-pointLeftTop);

	CString sTitle;
	GetParent()->GetWindowText(sTitle);

	CString sParam;
	sParam.Format("<arguments><number>%d</number><number>%d</number><number>%d</number><number>%d</number><string>%s</string></arguments>",
		(int)(rectView.left / fRatio),
		(int)(rectView.top / fRatio),
		(int)(rectView.right / fRatio),
		(int)(rectView.bottom / fRatio),
		sTitle);
	CallFlashFunction("extPrint", sParam);
/*
	CPrintDialog dlg(FALSE);

	// �p�����������ɐݒ�
	dlg.GetDefaults();
	LPDEVMODE lpDevInfo = dlg.GetDevMode();
	lpDevInfo->dmOrientation = DMORIENT_LANDSCAPE;
    dlg.m_pd.Flags &= ~PD_RETURNDEFAULT;

	if(dlg.DoModal() == IDOK) {
		// ����pCDC�擾
		CDC	dc;
		dc.Attach(dlg.GetPrinterDC());

		int nWidth = dc.GetDeviceCaps(HORZRES);
		int nHeight = dc.GetDeviceCaps(VERTRES);

		CDC dcMem;
		CBitmap bmpMem;
		dcMem.CreateCompatibleDC(&dc);
		bmpMem.CreateCompatibleBitmap(&dc, nWidth, nHeight);
		dcMem.SelectObject(&bmpMem);

		// DOCINFO��p��
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		docinfo.lpszDocName = "Test";

		// ����J�n
		dc.StartDoc(&docinfo);

		// �y�[�W����J�n
		dc.StartPage();
		m_cFlash.PrintWindow(&dcMem, 0);
		dc.StretchBlt(0, 0, nWidth, nHeight, &dcMem, 0, 0, m_rectFlash.Width(), m_rectFlash.Height(), SRCCOPY);

		// �y�[�W����I��
		dc.EndPage();

		// ����I��
		dc.EndDoc();
	}
*/
	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserExit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[�́u�I���v�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserExit(WPARAM wParam, LPARAM lParam)
{
	// �e�E�B���h�E�i�t���[���E�B���h�E�j�����
	GetParent()->SendMessage(WM_CLOSE);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserAction
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[����̊e����{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserAction(WPARAM wParam, LPARAM lParam)
{
	// Flash�ɓ���w���R�}���h�𑗐M����
	FlashAction((int)wParam, (CUIntArray *)lParam);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserPause
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[����̈ꎞ��~�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserPause(WPARAM wParam, LPARAM lParam)
{
	// Flash�ɓ���w���R�}���h�𑗐M����
	CUIntArray aParams;
	aParams.Add((UINT)wParam);
	FlashAction(ACTION_PAUSE, &aParams);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserSlow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[����̒ᑬ�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserSlow(WPARAM wParam, LPARAM lParam)
{
	// Flash�ɓ���w���R�}���h�𑗐M����
	FlashAction(ACTION_SLOW, NULL);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnUserNormal
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���상�j���[����̒ʏ�{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		WPARAM	wParam			[I] ���b�Z�[�W�p�����[�^
//		LPARAM	lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT	0
//*****************************************************************************************************
LRESULT CSchematicView::OnUserNormal(WPARAM wParam, LPARAM lParam)
{
	// Flash�ɓ���w���R�}���h�𑗐M����
	FlashAction(ACTION_NORMAL, NULL);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::FlashAction
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Flash�ɓ���w���R�}���h���M����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		int			nActionCode			[I] �A�N�V�����R�[�h
//		CUIntArray	*pParams			[I] �t���p�����[�^�̔z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::FlashAction(int nActionCode, CUIntArray *pParams)
{
	CString sParams;
	CString sParam;

	// ����R�[�h
	sParams.Format("<number>%d</number>", nActionCode);

	// �t���p�����[�^�ҏW
	if (pParams != NULL) {
		for (int i = 0; i < pParams->GetSize(); i++) {
			sParam.Format("<number>%d</number>", pParams->ElementAt(i));
			sParams += sParam;
		}
	}

	// �p�����[�^
	sParams = "<arguments>" + sParams + "</arguments>";

	// Flash����"extAction"�֐����Ăяo��
	CallFlashFunction("extAction", sParams);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::PostNcDestroy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�j���̍ŏI����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::PostNcDestroy()
{
	CWnd::PostNcDestroy();

	// �������g���폜
	delete this;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::InitFlashRect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		������Ԃ̃E�B���h�E�T�C�Y��ݒ肷��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::InitFlashRect()
{
	// �Y�[���{����1�ɂ���
	m_fZoomRatio = 1;
	m_sizeDefault = m_sizeView;
	m_rectFlash.SetRect(0, 0, m_sizeDefault.cx, m_sizeDefault.cy);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::GetNextScrollPos
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�N���[���o�[�𑀍삵���Ƃ��̃X�N���[���ʒu���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		int		nBar			[I] �X�N���[���o�[�̎�ށi�c�E���j
//		UINT	nSBCode			[I] �X�N���[���v���R�[�h
//		UINT	nPos			[I] �X�N���[���{�b�N�X�̈ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		���̃X�N���[���{�b�N�X�̈ʒu
//*****************************************************************************************************
int CSchematicView::GetNextScrollPos(int nBar, UINT nSBCode, UINT nPos)
{
	SCROLLINFO si;
	si.fMask = SIF_PAGE | SIF_POS | SIF_RANGE;
	GetScrollInfo(nBar, &si);
	int nNextPos = si.nPos;

	switch (nSBCode) {
	case SB_TOP:
		nNextPos = 0;
		break;
	case SB_BOTTOM:
		nNextPos = INT_MAX;
		break;
	case SB_LINEUP:
		nNextPos -= si.nMax / 100;
		break;
	case SB_LINEDOWN:
		nNextPos += si.nMax / 100;
		break;
	case SB_PAGEUP:
		nNextPos -= si.nMax / 10;
		break;
	case SB_PAGEDOWN:
		nNextPos += si.nMax / 10;
		break;
	case SB_THUMBTRACK:
		nNextPos = nPos;
		break;
	}

	if (nNextPos < si.nMin)
		nNextPos = si.nMin;
	if (nNextPos > si.nMax - (int)si.nPage)
		nNextPos = si.nMax - si.nPage;

	return nNextPos;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::ScrollFlashWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Flash�E�B���h�E���X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::ScrollFlashWindow()
{
	// �ē���h��
	if (m_bInScroll)
		return;

	m_bInScroll = TRUE;

	// �����X�N���[���o�[��ݒ�
	if (0 > m_rectFlash.left || m_sizeView.cx < m_rectFlash.right) {
		if (m_sizeView.cx > m_rectFlash.right)
			m_rectFlash.MoveToX(m_rectFlash.left + m_sizeView.cx - m_rectFlash.right);

		SCROLLINFO si;
		si.fMask = SIF_PAGE | SIF_RANGE | SIF_POS;
		si.nMin = 0;
		si.nMax = m_rectFlash.Width();
		si.nPage = m_sizeView.cx;
		si.nPos = -m_rectFlash.left;
		SetScrollInfo(SB_HORZ, &si);
		ShowScrollBar(SB_HORZ, TRUE);
	} else
		ShowScrollBar(SB_HORZ, FALSE);

	// �����X�N���[���o�[��ݒ�
	if (0 > m_rectFlash.top || m_sizeView.cy < m_rectFlash.bottom) {
		if (m_sizeView.cy > m_rectFlash.bottom)
			m_rectFlash.MoveToY(m_rectFlash.top + m_sizeView.cy - m_rectFlash.bottom);

		SCROLLINFO si;
		si.fMask = SIF_PAGE | SIF_RANGE | SIF_POS;
		si.nMin = 0;
		si.nMax = m_rectFlash.Height();
		si.nPage = m_sizeView.cy;
		si.nPos = -m_rectFlash.top;
		SetScrollInfo(SB_VERT, &si);
		ShowScrollBar(SB_VERT, TRUE);
	} else
		ShowScrollBar(SB_VERT, FALSE);

	// Flash�E�B���h�E���X�N���[���ʒu�Ɉړ�����
	m_cFlash.MoveWindow(m_rectFlash, FALSE);
	m_cFlash.UpdateWindow();

	m_bInScroll = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::SetZoomRatio
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肵���Y�[���{���ɂ��Flash�E�B���h�E���g��E�k������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		double		fZoomRatio			[I] �Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::SetZoomRatio(double fZoomRatio)
{
	double fZoom = fZoomRatio / m_fZoomRatio;

	// �Y�[���{���ɂ��Flash�E�B���h�E�̑傫���ƈʒu���v�Z����
	m_rectFlash.left = (int)((m_rectFlash.left - m_sizeView.cx / 2) * fZoom) + m_sizeView.cx / 2;
	m_rectFlash.top = (int)((m_rectFlash.top - m_sizeView.cy / 2) * fZoom) + m_sizeView.cy / 2;
	m_rectFlash.right = m_rectFlash.left + (int)(m_sizeDefault.cx * fZoomRatio);
	m_rectFlash.bottom = m_rectFlash.top + (int)(m_sizeDefault.cy * fZoomRatio);

	m_fZoomRatio = fZoomRatio;

	ScrollFlashWindow();
}

// Flash����̃C�x���g�}�b�v
BEGIN_EVENTSINK_MAP(CSchematicView, CWnd)
	ON_EVENT(CSchematicView, 0, 150, CSchematicView::FSCommandFlash, VTS_BSTR VTS_BSTR)
END_EVENTSINK_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::FSCommandFlash
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Flash�����FSCommand�C�x���g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCTSTR		command			[I] �R�}���h
//		LPCTSTR		args			[I] �p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::FSCommandFlash(LPCTSTR command, LPCTSTR args)
{
	CString sCommand = command;
	CString sArgs = args;

	if (sCommand == "ActionEnd") {
		// �A�N�V�����I���C�x���g
		m_pMenuDlg->ActionEnd(atoi(sArgs));
	} else if (sCommand == "Notify") {
		// �A�N�V�����ʒm�C�x���g
		if (sArgs == "CloseWindow")
			GetParent()->SendMessage(WM_CLOSE);
		else if (sArgs == "ActionStart")
			m_pMenuDlg->EnableSpeedButton(TRUE);
		else if (sArgs == "ActionEnd")
			m_pMenuDlg->EnableSpeedButton(FALSE);
	} else if (sCommand == "OpenFS") {
		// �@�\�n���}�\���C�x���g
		CString sSchematicCode;
		sSchematicCode.Format("FS%02d", atoi(sArgs));
		CSchematic::OpenSchematicWindow(sSchematicCode);
	} else if (sCommand == "FS13") {
		// ���~�b�g�X�C�b�`�̃T�u��ʕ\���C�x���g
		CSchematic::OpenSchematicWindow("FS13_2", atoi(sArgs));
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::CallFlashFunction
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Flash���̊֐��Ăяo������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCTSTR		pFunc			[I] �֐���
//		LPCTSTR		pParams			[I] �p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::CallFlashFunction(LPCTSTR pFunc, LPCTSTR pParams)
{
	// �֐��Ăяo����XML�`���ŋL�q����
	CString sCommand;
	sCommand.Format("<invoke name=\"%s\" returntype=\"xml\">%s</invoke>", pFunc, pParams);

	try {
		// XML�̓��e�ɏ]����Flash���̊֐����Ăяo��
		m_cFlash.CallFunction(sCommand);
	} catch (CException *e) {
		// �Ăяo���Ɏ��s�����ꍇ�͗�O���X���[�����̂ł�����L���b�`����
		AfxMessageBox("���̋@�\�͖������ł��B");
		e->Delete();
	}
}

// �ȉ��J���p�@�\
#ifdef _DEVELOP

#include "MenuFS08.h"

// ���e�F�����t���̒l��������ϐ��ɂ́A�����t���̌^�ɃL���X�g���Ȃ���΂Ȃ�܂���
#pragma warning( disable : 4146 )	// ���r���h����Warning���\���ɂ��邽��
// ���e�F'name' �������I�ɏ��O���A�^�C�v ���C�u���� 'library' ���C���|�[�g���܂�
#pragma warning( disable : 4192 )	// ���r���h����Warning���\���ɂ��邽��
// ���e�F�R���p�C���̐��� : �s�ԍ��̏o�͂𒆎~���܂��B
#pragma warning( disable : 4049 )	// ���r���h����Warning���\���ɂ��邽��

// Excel
#import "C:\Program Files\Microsoft Office\Office\Mso9.dll" no_namespace rename("DocumentProperties", "DocumentPropertiesXL")
#import "C:\Program Files\Common Files\Microsoft Shared\VBA\VBA6\Vbe6ext.olb" no_namespace
#import "C:\Program Files\Microsoft Office\Office\excel9.olb" rename("DialogBox", "DialogBoxXL") rename("RGB", "RBGXL") rename("DocumentProperties", "DocumentPropertiesXL") no_dual_interfaces

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnRButtonUp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�}�E�X�{�^���A�b�v���b�Z�[�W(WM_RBUTTONUP)�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		UINT	nFlags			[I] ������Ă��鉼�z�L�[
//		CPoint	point			[I] �}�E�X�J�[�\���̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnRButtonUp(UINT nFlags, CPoint point)
{
	POINT posScreen;
	::GetCursorPos(&posScreen);

	CMenu menu;
	menu.LoadMenu(IDR_MENU_POPUP);
	CMenu* pPopup = menu.GetSubMenu(0);
	pPopup->CheckMenuItem(IDM_QUICK_MODE, m_bQuickMode ? MF_CHECKED : MF_UNCHECKED);
	pPopup->TrackPopupMenu(TPM_LEFTALIGN | TPM_RIGHTBUTTON, posScreen.x, posScreen.y, this);

	CWnd::OnRButtonUp(nFlags, point);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnQuickMode
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�N�C�b�N���샂�[�h�ݒ�i�J���p�j
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnQuickMode()
{
	m_bQuickMode = !m_bQuickMode;

	CallFlashFunction("setQuickMode", m_bQuickMode ? "<arguments><true/></arguments>" : "<arguments><false/></arguments>");
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnScreenCopy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��ʃR�s�[���G�N�Z���ɓ\��t����i�J���p�j
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnScreenCopy()
{
	CWnd *pParent = GetParent();
	CRect rectWindow;
	pParent->GetWindowRect(rectWindow);
	if (rectWindow.Width() != 1024 || rectWindow.Height() != 768) {
		pParent->SetWindowPos(NULL, 0, 0, 1024, 768, SWP_NOMOVE | SWP_NOZORDER);
		OnUserSizeReset(0, 0);
		pParent->Invalidate();
		pParent->UpdateWindow();
	}

	::CoInitialize(NULL);

	try {
		Excel::_ApplicationPtr	pExcel;
		Excel::WorkbooksPtr		pBooks;
		Excel::_WorkbookPtr		pBook;
		Excel::SheetsPtr		pSheets;
		Excel::_WorksheetPtr	pSheet;
		CString sSheetName;
		CString sNewName;
		_variant_t cell;

		pExcel.GetActiveObject("Excel.Application");
		pBooks = pExcel->Workbooks;
		if (pBooks != NULL) {
			pBook = pBooks->Item[1];
			if (pBook != NULL) {
				pSheets = pBook->Worksheets;
				if (pSheets != NULL) {
					pSheet = pSheets->Item[1];
					sSheetName = (LPCTSTR)pSheet->Name;
					if (sSheetName.Right(2) == "%d") {
						sNewName.Format(sSheetName, pSheets->Count);
						Excel::_WorksheetPtr pSheet2 = pSheets->Item[pSheets->Count];
						VARIANT v;
						v.vt = VT_DISPATCH;
						v.pdispVal = pSheet2;
						pSheet->Copy(vtMissing, v);
						pSheet = pSheets->Item[pSheets->Count];
						pSheet->Name = (LPCTSTR)sNewName;
						cell = pSheet->Cells->GetItem((long)3, (long)1);
						pSheet->GetRange(cell, cell)->Activate();
					} else
						pSheet = pBook->ActiveSheet;

					if (pSheet != NULL) {
						ScreenShot(GetParent());
						pSheet->Paste();

						CWnd *pMenuDlg = m_pMenuDlg;
						if (pMenuDlg != NULL) {
							if (pMenuDlg->IsKindOf(RUNTIME_CLASS(CMenuFS08))) {
								CMenuFS08 *pMenuFS08 = (CMenuFS08 *)pMenuDlg;
								if (pMenuFS08->m_pSubMenu != NULL)
									pMenuDlg = pMenuFS08->m_pSubMenu;
							}

							ScreenShot(pMenuDlg);
							cell = pSheet->Cells->GetItem(pExcel->ActiveCell->Row, (long)16);
							pSheet->GetRange(cell, cell)->Activate();
							pSheet->Paste();
						}

						cell = pSheet->Cells->GetItem((long)1, (long)1);
						pSheet->GetRange(cell, cell)->Activate();
					}
				}
			}
		}
	} catch (_com_error &e) {
		AfxMessageBox(e.Description());
	}

	::CoUninitialize();
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::ScreenShot
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�肳�ꂽ�E�B���h�E�̉�ʃR�s�[���G�N�Z���ɓ\��t����i�J���p�j
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CWnd	*pWnd			[I] ��ʃR�s�[�����E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::ScreenShot(CWnd *pWnd)
{
	CDC dcMem;
	CBitmap bmpMem;
	CRect rect;

	pWnd->SetActiveWindow();
	CDC *pDC = pWnd->GetWindowDC();
	dcMem.CreateCompatibleDC(pDC);
	pWnd->GetWindowRect(rect);
	bmpMem.CreateCompatibleBitmap(pDC, rect.Width(), rect.Height());
	ReleaseDC(pDC);
	dcMem.SelectObject(&bmpMem);
	pWnd->PrintWindow(&dcMem, 0);

	OpenClipboard();
	EmptyClipboard();
	SetClipboardData(CF_BITMAP, bmpMem.m_hObject);
	CloseClipboard();
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematicView::OnStartAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S���i�𓮍��Ԃɂ���i�J���p�j
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematicView::OnStartAll()
{
	CallFlashFunction("startAll", "");
}

#endif
