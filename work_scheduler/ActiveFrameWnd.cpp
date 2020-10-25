// ActiveFrameWnd.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "ActiveFrameWnd.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CActiveFrameWnd

BEGIN_MESSAGE_MAP(CActiveFrameWnd, CWnd)
	//{{AFX_MSG_MAP(CActiveFrameWnd)
	ON_WM_ERASEBKGND()
	ON_WM_PAINT()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()


/////////////////////////////////////////////////////////////////////////////
// CActiveFrameWnd メッセージ ハンドラ

BOOL CActiveFrameWnd::OnEraseBkgnd(CDC* pDC)
{
	return TRUE;
}

void CActiveFrameWnd::OnPaint()
{
	CPaintDC dc(this); // 描画用のデバイス コンテキスト

	CRect rect;
	GetClientRect(rect);
	dc.FillSolidRect(rect, RGB(255, 0, 0));
}
