// Video.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "PcVideo.h"
#include "pcvcomm.h"
#include "Video.h"
#include "camera.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

static UINT BASED_CODE indicators[] =
{
	IDS_INDICATOR_FPS,
	IDS_INDICATOR_BPF,
	IDS_INDICATOR_BPS,
	IDS_INDICATOR_COMPRATE
};

/////////////////////////////////////////////////////////////////////////////
// CVideoWnd

IMPLEMENT_DYNCREATE(CVideoWnd, CFrameWnd)

CVideoWnd::CVideoWnd()
{
	m_nWidth = 0;
	m_nHeight = 0;
	m_nColor = 0;
	m_nFPS = 0;
	m_nBlock = 0;
	m_nBPS = 0;
	m_nCompressRate = 0;
	m_bResize = FALSE;
	m_bFixedAspect = TRUE;
	m_bFixedSize = FALSE;
	m_bDispInfo = FALSE;
	m_pCameraWnd = NULL;
	m_lpVideoBuf = NULL;
	m_pUser = NULL;
	m_nFrameWidth = 0;
	m_nFrameHeight = 0;
	m_bSizeChanging = FALSE;
}

CVideoWnd::~CVideoWnd()
{
	if (m_pCameraWnd != NULL)
		pCameraWnd->SetVideoWindow(NULL);
}


BEGIN_MESSAGE_MAP(CVideoWnd, CFrameWnd)
	//{{AFX_MSG_MAP(CVideoWnd)
	ON_WM_SIZE()
	ON_WM_CREATE()
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_EXITSIZEMOVE, OnExitSizeMove)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVideoWnd メッセージ ハンドラ

BOOL CVideoWnd::PreCreateWindow(CREATESTRUCT& cs) 
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;

	return TRUE;
}

int CVideoWnd::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	SetIcon(hIconVideo, TRUE);		// 大きいアイコンを設定
	SetIcon(hIconVideo, FALSE);		// 小さいアイコンを設定

	CRect	rect;
	rect.SetRectEmpty();
	m_wndVideoView.Create(NULL, "VideoView", WS_CHILD | WS_VISIBLE, rect, this, AFX_IDW_PANE_FIRST);

	return 0;
}

void CVideoWnd::OnSize(UINT nType, int cx, int cy) 
{
	CFrameWnd::OnSize(nType, cx, cy);

	if (m_nFrameHeight != 0 && (nType == SIZE_RESTORED || nType == SIZE_MAXIMIZED)) {
		if (m_bFixedAspect && m_nWidth != 0 && m_nHeight != 0) {
			CRect	rect;
			GetWindowRect(&rect);
			int nViewWidth = rect.Width() - m_nFrameWidth;
			int nViewHeight = rect.Height() - m_nFrameHeight;
			if (nViewWidth < nViewHeight * m_nWidth / m_nHeight)
				nViewHeight = nViewWidth * m_nHeight / m_nWidth;
			else
				nViewWidth = nViewHeight * m_nWidth / m_nHeight;

			m_wndVideoView.SetWindowSize(nViewWidth, nViewHeight);

			m_bSizeChanging = TRUE;
		}
	}
}

LRESULT CVideoWnd::OnExitSizeMove(WPARAM wParam, LPARAM lParam)
{
	if (m_bSizeChanging) {
		CRect	rect;
		GetWindowRect(&rect);
		int nViewWidth = rect.Width() - m_nFrameWidth;
		int nViewHeight = rect.Height() - m_nFrameHeight;
		if (nViewWidth < nViewHeight * m_nWidth / m_nHeight)
			nViewHeight = nViewWidth * m_nHeight / m_nWidth;
		else
			nViewWidth = nViewHeight * m_nWidth / m_nHeight;

		SetWindowSize(nViewWidth, nViewHeight);
		RecalcLayout();

		m_bSizeChanging = FALSE;
	}

	return 0;
}

void	CVideoWnd::CreateVideoWindow(LPSTR pCaption, int left, int top, int nCmdShow)
{
	DWORD	dwStyle;

	dwStyle = WS_OVERLAPPEDWINDOW | WS_CLIPCHILDREN;
	if (!m_bResize)
		dwStyle &= ~WS_THICKFRAME;

	CRect rect;
	rect.left = left;
	rect.top = top;
	rect.right = left + 100;
	rect.bottom = top + 100;
	Create(NULL, pCaption, dwStyle, rect, NULL, NULL);

	if (m_bDispInfo) {
		if (m_wndStatusBar.Create(this)) {
			m_wndStatusBar.SetFont(&infoFont);
			m_wndStatusBar.SetIndicators(indicators, sizeof(indicators) / sizeof(UINT));
		}
	}

	CRect rectWindow, rectView;
	RecalcLayout();
	GetWindowRect(&rectWindow);
	m_wndVideoView.GetClientRect(&rectView);
	m_nFrameWidth = rectWindow.Width() - rectView.Width();
	m_nFrameHeight = rectWindow.Height() - rectView.Height();

	SetInitialWindowSize(m_nWidth, m_nHeight);

	ShowWindow(nCmdShow);
	UpdateWindow();
}

void	CVideoWnd::DrawVideoWindow()
{
	m_wndVideoView.Invalidate(FALSE);

	if (m_wndStatusBar.m_hWnd && !IsIconic()) {
		char	buf[30];
		wsprintf(buf, "%2dfps", m_nFPS);
		m_wndStatusBar.SetPaneText(0, buf);
		wsprintf(buf, "%4dbpf", m_nBlock);
		m_wndStatusBar.SetPaneText(1, buf);
		wsprintf(buf, "%3d.%1dkbps", m_nBPS / 10, m_nBPS % 10);
		m_wndStatusBar.SetPaneText(2, buf);
		wsprintf(buf, "%2d.%1d%%", m_nCompressRate / 10, m_nCompressRate % 10);
		m_wndStatusBar.SetPaneText(3, buf);
	}
}

void	CVideoWnd::SetVideoSize(int width, int height, int color)
{
	m_nWidth = width;
	m_nHeight = height;
	m_nColor = color;

	if (m_hWnd) {
		SetInitialWindowSize(width, height);
		RecalcLayout();
		Invalidate(FALSE);
	}
}

void	CVideoWnd::SetInitialWindowSize(int nWidth, int nHeight)
{
	if (m_bFixedSize) {
		nWidth = videoSize[iniData.settings.recvSize].width;
		nHeight = videoSize[iniData.settings.recvSize].height;
	}

	SetWindowSize(nWidth, nHeight);
}

void CVideoWnd::SetWindowSize(int nWidth, int nHeight)
{
	SetWindowPos(NULL, 0, 0, nWidth + m_nFrameWidth, nHeight + m_nFrameHeight, SWP_NOMOVE | SWP_NOZORDER);
}

/////////////////////////////////////////////////////////////////////////////
// CVideoView

CVideoView::CVideoView()
{
}

CVideoView::~CVideoView()
{
}


BEGIN_MESSAGE_MAP(CVideoView, CWnd)
	//{{AFX_MSG_MAP(CVideoView)
	ON_WM_PAINT()
	ON_WM_LBUTTONDBLCLK()
	ON_WM_LBUTTONDOWN()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CVideoView メッセージ ハンドラ

void CVideoView::OnPaint() 
{
	CPaintDC dc(this); // 描画用のデバイス コンテキスト

	BITMAPINFO	*bip;
	int	space, space2;
	CVideoWnd *pVideoWnd = (CVideoWnd *)GetParent();
	CRect rect;

	GetClientRect(&rect);

	if (pVideoWnd->m_pCameraWnd) {
		dc.SelectObject(&cameraPen);
		dc.SelectStockObject(NULL_BRUSH);
		dc.Rectangle(1, 1, rect.right, rect.bottom);
		space = 2;
	} else {
		space = 0;
	}

	if (pVideoWnd->m_lpVideoBuf == NULL)
		return;

	space2 = space * 2;
	bip = (BITMAPINFO *)bitmapInfo;
	bip->bmiHeader.biWidth = pVideoWnd->m_nWidth;
	bip->bmiHeader.biHeight = pVideoWnd->m_nHeight;
	bip->bmiHeader.biBitCount = colorTable[pVideoWnd->m_nColor];

	DrawDibDraw(hDD, dc.m_hDC, space, space,
				rect.right - space2, rect.bottom - space2,
				(LPBITMAPINFOHEADER)bip, pVideoWnd->m_lpVideoBuf,
				space, space,
				pVideoWnd->m_nWidth - space2, pVideoWnd->m_nHeight - space2, 0);
}

BOOL CVideoView::PreCreateWindow(CREATESTRUCT& cs) 
{
	cs.dwExStyle |= WS_EX_CLIENTEDGE;

	return CWnd::PreCreateWindow(cs);
}

void CVideoView::OnLButtonDblClk(UINT nFlags, CPoint point) 
{
	CVideoWnd *pVideoWnd = (CVideoWnd *)GetParent();

	if (bAdmin || pVideoWnd->m_pUser == NULL) {
		if (pCameraWnd == NULL)
			pCameraWnd = new CCameraWnd();

		if (pCameraWnd != NULL)
			pCameraWnd->SetVideoWindow(pVideoWnd);

		Invalidate(FALSE);
	}
	
	CWnd::OnLButtonDblClk(nFlags, point);
}

void CVideoView::OnLButtonDown(UINT nFlags, CPoint point) 
{
	CVideoWnd *pVideoWnd = (CVideoWnd *)GetParent();

	if (pVideoWnd != NULL) {
		if (pVideoWnd->m_pCameraWnd != NULL) {
			int	x, y;
			CRect	rect;

			GetClientRect(rect);
			x = (int)((point.x - rect.right / 2) * 1000L / rect.right);
			y = (int)((point.y - rect.bottom / 2) * 1000L / rect.bottom);
			pVideoWnd->m_pCameraWnd->SendMessage(WM_POINTWND, 0L, MAKELPARAM(x, y));
		}

		pVideoWnd->SendMessage(WM_LBUTTONDOWN, nFlags, MAKELPARAM(point.x, point.y));
	}

	CWnd::OnLButtonDown(nFlags, point);
}

void CVideoView::SetWindowSize(int nWidth, int nHeight)
{
	CRect rectWindow, rectClient;

	GetWindowRect(rectWindow);
	GetClientRect(rectClient);

	SetWindowPos(NULL, 0, 0, nWidth + rectWindow.Width() - rectClient.Width(),
				nHeight + rectWindow.Height() - rectClient.Height(), SWP_NOMOVE | SWP_NOZORDER);
}
