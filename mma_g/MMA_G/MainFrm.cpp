// MainFrm.cpp : CMainFrame クラスの実装
//

#include "stdafx.h"
#include "MMA_G.h"

#include "MainFrm.h"
#include "MainTreeView.h"
#include "MainListView.h"
#include "GlobalData.h"
#include "Define.h"
#include "RealtimeData.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CMainFrame

IMPLEMENT_DYNCREATE(CMainFrame, CFrameWnd)

BEGIN_MESSAGE_MAP(CMainFrame, CFrameWnd)
	ON_WM_CREATE()
//	ON_WM_ACTIVATE()
//	ON_COMMAND(ID_APP_EXIT, &CMainFrame::OnAppExit)
	ON_WM_CLOSE()
	ON_UPDATE_COMMAND_UI(ID_INDICATOR_GUIDE, OnUpdateStatusGude)
	ON_MESSAGE(WM_USER_REALTIME_GRAPH, OnUserRealtimeGraph)
	ON_MESSAGE(WM_USER_CLOSE_GRAPH, OnUserCloseGraph)
	ON_MESSAGE(WM_USER_MESSAGE_BOX, OnUserMessageBox)
	ON_MESSAGE(WM_USER_AUTO_CONV, OnUserAutoConv)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

static UINT indicators[] =
{
	ID_SEPARATOR,           // ステータス ライン インジケータ
//	ID_INDICATOR_GUIDE,
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
	ID_INDICATOR_SCRL,
};


// CMainFrame コンストラクション/デストラクション

CMainFrame::CMainFrame()
{
	// TODO: メンバ初期化コードをここに追加してください。
}

CMainFrame::~CMainFrame()
{
	for(int i = 0 ; i< g_iInfoNum; i++){
		if( g_pSensorInfo != NULL ){
			if( g_pSensorInfo[i].idx != -1 ){
				if( g_pSensorInfo[i].sel_dat != NULL ){
					delete [] g_pSensorInfo[i].sel_dat;
				}
				if( g_pSensorInfo[i].dat != NULL ){
					delete [] g_pSensorInfo[i].dat;
				}
				if( g_pSensorInfo[i].dir_x != NULL ){
					delete [] g_pSensorInfo[i].dir_x;
				}
				if( g_pSensorInfo[i].dir_y != NULL ){
					delete [] g_pSensorInfo[i].dir_y;
				}
				if( g_pSensorInfo[i].dir_z != NULL ){
					delete [] g_pSensorInfo[i].dir_z;
				}
				if( g_pSensorInfo[i].category != NULL ){
					delete [] g_pSensorInfo[i].category;
				}
				if( g_pSensorInfo[i].measurement_kind != NULL ){
					delete [] g_pSensorInfo[i].measurement_kind;
				}
			}
			else{
				break;
			}
		}
		else{
			break;
		}
	}

	if( g_pSensorInfo != NULL ){
		delete [] g_pSensorInfo;
	}

	if (g_pSensorInfoMme != NULL)
		delete [] g_pSensorInfoMme;

	if (g_pSensorInfoAsu != NULL)
		delete g_pSensorInfoAsu;
}

// ウィンドウ作成処理
int CMainFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// ツールバー作成	
	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_MAINFRAME))
	{
		TRACE0("ツール バーの作成に失敗しました。\n");
		return -1;      // 作成できませんでした。
	}

	// ステータスバー作成
	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("ステータス バーの作成に失敗しました。\n");
		return -1;      // 作成できませんでした。
	}

	// TODO: ツール バーをドッキング可能にしない場合は、これらの 3 行を削除してください。
//	m_wndToolBar.EnableDocking(CBRS_ALIGN_ANY);
//	EnableDocking(CBRS_ALIGN_ANY);
//	DockControlBar(&m_wndToolBar);

	m_oAutoConv.m_hMainWnd = m_hWnd;

	return 0;
}

void CMainFrame::OnUpdateStatusGude(CCmdUI *pCmdUI)
{
	pCmdUI->Enable();
}

// ウィンドウ作成前処理
BOOL CMainFrame::PreCreateWindow(CREATESTRUCT& cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.style &= ~FWS_ADDTOTITLE;

	return TRUE;
}


// CMainFrame 診断

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


// CMainFrame メッセージ ハンドラ

// クライアント領域作成処理
BOOL CMainFrame::OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext)
{
	// 分割ウィンドウを作成します
	if (!m_wndSplitter.CreateStatic(this, 1, 2))
		return FALSE;

	// スプリッタを作成
	if (!m_wndSplitter.CreateView(0, 0, RUNTIME_CLASS(CMainTreeView), CSize(350, 0), pContext) ||
		!m_wndSplitter.CreateView(0, 1, RUNTIME_CLASS(CMainListView), CSize(0, 0), pContext))
	{
		m_wndSplitter.DestroyWindow();
		return FALSE;
	}

	return TRUE;
}


void CMainFrame::OnClose()
{
	// TODO: ここにメッセージ ハンドラ コードを追加するか、既定の処理を呼び出します。

#ifndef _DEBUG
	if( AfxMessageBox("MMA地上運用システム制御ソフトウェアを終了しますか？",MB_ICONQUESTION|MB_YESNO) == IDYES )
#endif
		CFrameWnd::OnClose();
}


LRESULT CMainFrame::OnUserRealtimeGraph(WPARAM wParam, LPARAM lParam)
{
	g_RealtimeData.UpdateGraphWindow();

	return 0;
}

LRESULT CMainFrame::OnUserCloseGraph(WPARAM wParam, LPARAM lParam)
{
	g_RealtimeData.CloseGraphWindow((HWND)wParam);

	return 0;
}

LRESULT CMainFrame::OnUserMessageBox(WPARAM wParam, LPARAM lParam)
{
	return AfxMessageBox((LPCTSTR)wParam, (UINT)lParam);
}

LRESULT CMainFrame::OnUserAutoConv(WPARAM wParam, LPARAM lParam)
{
	m_oAutoConv.ExecAutoConvert((CMaiFile *)wParam, (CString *)lParam);

	return 0;
}

void CMainFrame::OnDestroy()
{
	CGraphFrame::CloseAll();

	CFrameWnd::OnDestroy();
}
