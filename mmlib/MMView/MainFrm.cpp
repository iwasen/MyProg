// MainFrm.cpp : CMainFrame �N���X�̓���̒�`���s���܂��B
//

#include "stdafx.h"
#include "MMView.h"

#include "MainFrm.h"
#include "MMViewDoc.h"
#include "MMPictView.h"
#include "MMListView.h"
#include "MMTextView.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMainFrame

IMPLEMENT_DYNCREATE(CMainFrame, CFrameWnd)

BEGIN_MESSAGE_MAP(CMainFrame, CFrameWnd)
	ON_WM_CREATE()
	ON_WM_CLOSE()
	ON_WM_SIZE()
	ON_UPDATE_COMMAND_UI(ID_INDICATOR_INFO, OnUpdateInfo)
	ON_UPDATE_COMMAND_UI(ID_INDICATOR_PAGE, OnUpdatePage)
	ON_MESSAGE(WM_ENTERSIZEMOVE, OnEnterSizeMove)
END_MESSAGE_MAP()

static UINT indicators[] =
{
	ID_SEPARATOR,           // �X�e�[�^�X ���C�� �C���W�P�[�^
	ID_INDICATOR_INFO,
	ID_INDICATOR_PAGE,
};

// XP�X�^�C���ŃO���b�v������ɕ\������Ȃ��o�O�̑Ώ�
//////////////////////////////////////////////////////
// CMyToolBar

BEGIN_MESSAGE_MAP(CMyToolBar, CToolBar)
	ON_WM_NCPAINT()
END_MESSAGE_MAP()

void CMyToolBar::OnNcPaint() 
{
	EraseNonClient();
}
void CMyToolBar::EraseNonClient()
{
	// Get window DC that is clipped to the non-client area.
	CWindowDC dc(this);
	CRect rectClient;
	GetClientRect(rectClient);
	CRect rectWindow;
	GetWindowRect(rectWindow);
	ScreenToClient(rectWindow);
	rectClient.OffsetRect(-rectWindow.left, -rectWindow.top);
	dc.ExcludeClipRect(rectClient);

	// Draw the borders in the non-client area.
	rectWindow.OffsetRect(-rectWindow.left, -rectWindow.top);
	DrawBorders(&dc, rectWindow);

	// Erase the parts that are not drawn.
	dc.IntersectClipRect(rectWindow);
	SendMessage(WM_ERASEBKGND, (WPARAM)dc.m_hDC);

	// Draw the gripper in the non-client area.
	DrawGripper(&dc, rectWindow);
}

void CMyToolBar::DoPaint(CDC* pDC)
{
	ASSERT_VALID(this);
	ASSERT_VALID(pDC);

	// Paint inside the client area.
	CRect rect;
	GetClientRect(rect);
	DrawBorders(pDC, rect);
	DrawGripper(pDC, rect);
}

void CMyToolBar::DrawGripper(CDC* pDC, const CRect& rect)
{
	pDC->FillSolidRect( &rect, ::GetSysColor(COLOR_BTNFACE)); // Fill in the background.
	CToolBar::DrawGripper(pDC,rect);
}

/////////////////////////////////////////////////////////////////////////////
// CMainFrame �N���X�̍\�z/����

CMainFrame::CMainFrame()
{
	m_bShowText = TRUE;
	m_nFixedHeight = 0;
	m_nClientHeight = 0;
	m_nViewIndex = 0;
}

CMainFrame::~CMainFrame()
{
}

int CMainFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_MAINFRAME))
	{
		TRACE0("Failed to create toolbar\n");
		return -1;      // �쐬�Ɏ��s
	}

	m_wndToolBar.SetHeight(27);

	CRect rect;
	m_wndToolBar.GetItemRect(7, rect);

	// create a combo box for the address bar
	if (!m_wndComboBox.Create(CBS_DROPDOWN | WS_CHILD | WS_VISIBLE, CRect(rect.left+2, 1, rect.left + 58, 200), &m_wndToolBar, IDW_COMBO))
	{
		TRACE0("Failed to create combobox\n");
		return -1;      // fail to create
	}

	static CFont font;
	font.CreatePointFont(90, GetString(IDS_DEFAULT_FONT));
	m_wndComboBox.SetFont(&font);

	m_wndToolBar.SetButtonInfo(7, IDW_COMBO, TBBS_SEPARATOR, 62);

	m_wndComboBox.AddString("200%");
	m_wndComboBox.AddString("150%");
	m_wndComboBox.AddString("100%");
	m_wndComboBox.AddString("75%");
	m_wndComboBox.AddString("50%");
	m_wndComboBox.AddString("25%");
	m_wndComboBox.LimitText(4);

	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("Failed to create status bar\n");
		return -1;      // �쐬�Ɏ��s
	}

	m_wndStatusBar.SetPaneStyle(0, SBPS_NORMAL);
	m_wndStatusBar.SetPaneStyle(1, SBPS_STRETCH);
	UINT nID, nStyle;
	int cxWidth;
	m_wndStatusBar.GetPaneInfo(2, nID, nStyle, cxWidth);
	m_wndStatusBar.SetPaneInfo(2, nID, nStyle, 70);

	// TODO: �c�[�� �o�[���h�b�L���O�\�ɂ��Ȃ��ꍇ�͈ȉ��̂R�s���폜
	//       ���Ă��������B
	m_wndToolBar.EnableDocking(CBRS_ALIGN_ANY);
	EnableDocking(CBRS_ALIGN_ANY);
	DockControlBar(&m_wndToolBar);

	DragAcceptFiles();

	return 0;
}

BOOL CMainFrame::PreCreateWindow(CREATESTRUCT& cs)
{
	CWinApp *pWinApp = AfxGetApp();

	int left = pWinApp->GetProfileInt(g_SectionMainWindow, "Left", 0);
	int top = pWinApp->GetProfileInt(g_SectionMainWindow, "Top", 0);
	int right = pWinApp->GetProfileInt(g_SectionMainWindow, "Right", 0);
	int bottom = pWinApp->GetProfileInt(g_SectionMainWindow, "Bottom", 0);

	if (right != 0 && bottom != 0) {
		cs.x = left;
		cs.y = top;
		cs.cx = right - left;
		cs.cy = bottom - top;
	}

//	cs.style &= ~FWS_ADDTOTITLE;

	return CFrameWnd::PreCreateWindow(cs);
}

/////////////////////////////////////////////////////////////////////////////
// CMainFrame �N���X�̐f�f

#ifdef _DEBUG
void CMainFrame::AssertValid() const
{
	CFrameWnd::AssertValid();
}

void CMainFrame::Dump(CDumpContext& dc) const
{
	CFrameWnd::Dump(dc);
}

#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CMainFrame ���b�Z�[�W �n���h��

BOOL CMainFrame::OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext) 
{
	// 1 ��A2 �s�̃X�v���b�^�[���쐬
	if (!m_wndSplitter.CreateStatic(this, 2, 1))
	{
		TRACE0("Failed to CreateStaticSplitter\n");
		return FALSE;
	}

	CRect rect;
	GetClientRect(rect);
	int m_nHeight = AfxGetApp()->GetProfileInt(g_SectionMainWindow, "Height", rect.Height() - 120);

	// 1 �Ԗڂ̃X�v���b�^�[ �y�C����ǉ� - 0 �s�ڂ̃f�t�H���g �r���[ 
	if (!m_wndSplitter.CreateView(0, 0,
		pContext->m_pNewViewClass, CSize(0, m_nHeight), pContext))
	{
		TRACE0("Failed to create first pane\n");
		return FALSE;
	}

	// 2 �Ԗڂ̃X�v���b�^�[ �y�C����ǉ� - 1 �s�ڂ̓��̓r���[
	if (!m_wndSplitter.CreateView(1, 0,
		RUNTIME_CLASS(CMMTextView), CSize(0, 0), pContext))
	{
		TRACE0("Failed to create second pane\n");
		return FALSE;
	}

	m_pViews[VIEWWND_PICT] = (CView *)m_wndSplitter.GetDlgItem(m_wndSplitter.IdFromRowCol(0, 0));

	m_pViews[VIEWWND_LIST] = (CView *)RUNTIME_CLASS(CMMListView)->CreateObject();
	m_pViews[VIEWWND_LIST]->Create(NULL, NULL, WS_CHILD | WS_BORDER, rectDefault, &m_wndSplitter, m_wndSplitter.IdFromRowCol(0, 0) + 2, pContext);

	m_pViews[VIEWWND_LIST]->ShowWindow(SW_HIDE);

	return TRUE;
}

void CMainFrame::SwitchView(UINT nIndex)
{
	if (nIndex != m_nViewIndex) {
		CView *pViewAdd, *pViewRemove;

		if (nIndex == VIEWWND_PICT) {
			pViewAdd = m_pViews[VIEWWND_PICT];
			pViewRemove = m_pViews[VIEWWND_LIST];
		} else {
			pViewAdd = m_pViews[VIEWWND_LIST];
			pViewRemove = m_pViews[VIEWWND_PICT];
		}

		int nSwitchChildID = pViewAdd->GetDlgCtrlID();
		pViewAdd->SetDlgCtrlID(pViewRemove->GetDlgCtrlID());
		pViewRemove->SetDlgCtrlID(nSwitchChildID);

		pViewRemove->ShowWindow(SW_HIDE);
		pViewAdd->ShowWindow(SW_SHOW);
		SetActiveView(pViewAdd);

		if (m_bShowText)
			m_wndSplitter.RecalcLayout();
		else
			RecalcLayout();

		m_nViewIndex = nIndex;
	}
}

void CMainFrame::OnClose() 
{
	CWinApp *pWinApp = AfxGetApp();

	WINDOWPLACEMENT winpl;
	winpl.length = sizeof(WINDOWPLACEMENT);
	GetWindowPlacement(&winpl);

	pWinApp->WriteProfileInt(g_SectionMainWindow, "Left", winpl.rcNormalPosition.left);
	pWinApp->WriteProfileInt(g_SectionMainWindow, "Top", winpl.rcNormalPosition.top);
	pWinApp->WriteProfileInt(g_SectionMainWindow, "Right", winpl.rcNormalPosition.right);
	pWinApp->WriteProfileInt(g_SectionMainWindow, "Bottom", winpl.rcNormalPosition.bottom);
	pWinApp->WriteProfileInt(g_SectionMainWindow, "Show", winpl.showCmd);

	if (m_bShowText) {
		int cxCur, cxMin;
		m_wndSplitter.GetRowInfo(0, cxCur, cxMin);
		m_nHeight = cxCur;
		pWinApp->WriteProfileInt(g_SectionMainWindow, "Height", m_nHeight);
	}

	CFrameWnd::OnClose();
}

void CMainFrame::OnUpdateInfo(CCmdUI *pCmdUI)
{
    pCmdUI->Enable();
    pCmdUI->SetText(g_StatusInfo);
}

void CMainFrame::OnUpdatePage(CCmdUI *pCmdUI)
{
    pCmdUI->Enable();
    pCmdUI->SetText(g_PageInfo);
}

void CMainFrame::ShowTextView(BOOL bShow)
{
	if (bShow != m_bShowText) {
		CView *pView;

		if (bShow) {
			m_pViews[VIEWWND_LIST]->SetParent(&m_wndSplitter);
			m_pViews[VIEWWND_PICT]->SetParent(&m_wndSplitter);
			if (m_nViewIndex == VIEWWND_PICT)
				pView = m_pViews[VIEWWND_PICT];
			else
				pView = m_pViews[VIEWWND_LIST];
			int nSwitchChildID = m_wndSplitter.GetDlgCtrlID() - 1;
			m_wndSplitter.SetDlgCtrlID(pView->GetDlgCtrlID());
			pView->SetDlgCtrlID(nSwitchChildID);
			RecalcLayout();
			m_wndSplitter.RecalcLayout();
			m_wndSplitter.ShowWindow(SW_SHOW);
		} else {
			m_wndSplitter.ShowWindow(SW_HIDE);
			m_pViews[VIEWWND_LIST]->SetParent(this);
			m_pViews[VIEWWND_PICT]->SetParent(this);
			if (m_nViewIndex == VIEWWND_PICT)
				pView = m_pViews[VIEWWND_PICT];
			else
				pView = m_pViews[VIEWWND_LIST];
			int nSwitchChildID = pView->GetDlgCtrlID() + 1;
			pView->SetDlgCtrlID(m_wndSplitter.GetDlgCtrlID());
			m_wndSplitter.SetDlgCtrlID(nSwitchChildID);
			RecalcLayout();
		}
		m_bShowText = bShow;
	}
}

LRESULT CMainFrame::OnEnterSizeMove(WPARAM wParam, LPARAM lParam)
{
	int cxCur, cyMin;
	CRect rect;

	m_wndSplitter.GetRowInfo(0, cxCur, cyMin);
	m_nFixedHeight = cxCur;

	GetClientRect(rect);
	m_nClientHeight = rect.bottom;

	return 0;
}

void CMainFrame::OnSize(UINT nType, int cx, int cy) 
{
	if (m_nClientHeight != 0) {
		if (cy != m_nClientHeight) {
			int nTextViewHeight;

			nTextViewHeight = m_nFixedHeight + (cy - m_nClientHeight);
			if (nTextViewHeight < 0)
				nTextViewHeight = 0;

			m_wndSplitter.SetRowInfo(0, nTextViewHeight, 0);
		}
	}

	CFrameWnd::OnSize(nType, cx, cy);
}
