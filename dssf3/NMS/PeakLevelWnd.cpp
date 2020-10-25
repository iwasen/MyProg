// PeakLevelWnd.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "Nms.h"
#include "PeakLevelWnd.h"
#include "SetData.h"
#include "Help\ContextHelp.h"

/////////////////////////////////////////////////////////////////////////////
// CPeakLevelWnd ダイアログ


CPeakLevelWnd::CPeakLevelWnd(CWnd* pParent /*=NULL*/)
	: CDialog(CPeakLevelWnd::IDD, pParent)
{
	Create(IDD, (CWnd *)&wndTop);
}


void CPeakLevelWnd::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_PEAK_METER, m_cPeakLevel);
}


BEGIN_MESSAGE_MAP(CPeakLevelWnd, CDialog)
	ON_WM_DESTROY()
	ON_WM_PAINT()
	ON_WM_HELPINFO()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPeakLevelWnd メッセージ ハンドラ

BOOL CPeakLevelWnd::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cPeakLevel.Initialize();

	SetWindowPos(&wndTopMost, g_oSetData.PeakLevelWindow.left, g_oSetData.PeakLevelWindow.top, 0, 0, SWP_NOSIZE);
	ShowWindow(SW_SHOW);

	return TRUE;
}

void CPeakLevelWnd::OnCancel()
{
	DestroyWindow();
}

void CPeakLevelWnd::OnOK()
{
}

void CPeakLevelWnd::OnDestroy()
{
	WINDOWPLACEMENT winpl;

	winpl.length = sizeof(WINDOWPLACEMENT);
	GetWindowPlacement(&winpl);			// ダイアログ表示位置取得
	g_oSetData.PeakLevelWindow.left = winpl.rcNormalPosition.left;
	g_oSetData.PeakLevelWindow.top = winpl.rcNormalPosition.top;

	CDialog::OnDestroy();
}

void CPeakLevelWnd::PostNcDestroy()
{
	delete this;
	g_pPeakLevelWnd  = NULL;

	CDialog::PostNcDestroy();
}

void CPeakLevelWnd::OnPaint()
{
	CPaintDC dc(this); // 描画用のデバイス コンテキスト
	CFont *pFont;
	CString text;
	CSize size;
	CRect rect;
	int x;
	int i;
	CPen pen1(PS_SOLID, 0, RGB(128, 128, 128));
	CPen *pPen;

	WINDOWPLACEMENT wp;
	m_cPeakLevel.GetWindowPlacement(&wp);
	m_cPeakLevel.GetClientRect(rect);
	pFont = dc.SelectObject(GetFont());
	pPen = dc.SelectObject(&pen1);
	dc.SetBkMode(TRANSPARENT);
	for (i = 0; i <= 8; i++) {
		x = wp.rcNormalPosition.left + rect.Width() * i / 8;
		dc.MoveTo(x, wp.rcNormalPosition.bottom);
		dc.LineTo(x, wp.rcNormalPosition.bottom + 4);
		text.Format("%d", i * 10 - 80);
		size = dc.GetOutputTextExtent(text);
		dc.TextOut(x - size.cx / 2, wp.rcNormalPosition.bottom + 4, text);
	}
	dc.SelectObject(pFont);
	dc.SelectObject(pPen);
}

BOOL CPeakLevelWnd::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDCANCEL, IDH_PEAK_LEVEL_CANCEL,
		IDC_PEAK_METER, IDH_PEAK_LEVEL_METER,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
