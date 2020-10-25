// ManualEditChildWnd1.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "workscheduler.h"
#include "ManualEditChildWnd1.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd1

IMPLEMENT_DYNCREATE(CManualEditChildWnd1, CManualEditChildWnd)

CManualEditChildWnd1::CManualEditChildWnd1()
{
}

CManualEditChildWnd1::~CManualEditChildWnd1()
{
}


BEGIN_MESSAGE_MAP(CManualEditChildWnd1, CManualEditChildWnd)
	//{{AFX_MSG_MAP(CManualEditChildWnd1)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CManualEditChildWnd1 メッセージ ハンドラ

BOOL CManualEditChildWnd1::OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext)
{
	return m_wndSplitter.Create(this, 1, 2, CSize(10, 10), pContext);
}
