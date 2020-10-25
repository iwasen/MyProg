// MainFrm.cpp : CMainFrame �N���X�̎���
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
	ID_SEPARATOR,           // �X�e�[�^�X ���C�� �C���W�P�[�^
//	ID_INDICATOR_GUIDE,
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
	ID_INDICATOR_SCRL,
};


// CMainFrame �R���X�g���N�V����/�f�X�g���N�V����

CMainFrame::CMainFrame()
{
	// TODO: �����o�������R�[�h�������ɒǉ����Ă��������B
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

// �E�B���h�E�쐬����
int CMainFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// �c�[���o�[�쐬	
	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_MAINFRAME))
	{
		TRACE0("�c�[�� �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;      // �쐬�ł��܂���ł����B
	}

	// �X�e�[�^�X�o�[�쐬
	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("�X�e�[�^�X �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;      // �쐬�ł��܂���ł����B
	}

	// TODO: �c�[�� �o�[���h�b�L���O�\�ɂ��Ȃ��ꍇ�́A������ 3 �s���폜���Ă��������B
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

// �E�B���h�E�쐬�O����
BOOL CMainFrame::PreCreateWindow(CREATESTRUCT& cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.style &= ~FWS_ADDTOTITLE;

	return TRUE;
}


// CMainFrame �f�f

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


// CMainFrame ���b�Z�[�W �n���h��

// �N���C�A���g�̈�쐬����
BOOL CMainFrame::OnCreateClient(LPCREATESTRUCT lpcs, CCreateContext* pContext)
{
	// �����E�B���h�E���쐬���܂�
	if (!m_wndSplitter.CreateStatic(this, 1, 2))
		return FALSE;

	// �X�v���b�^���쐬
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
	// TODO: �����Ƀ��b�Z�[�W �n���h�� �R�[�h��ǉ����邩�A����̏������Ăяo���܂��B

#ifndef _DEBUG
	if( AfxMessageBox("MMA�n��^�p�V�X�e������\�t�g�E�F�A���I�����܂����H",MB_ICONQUESTION|MB_YESNO) == IDYES )
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
