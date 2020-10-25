// CaptView.cpp : CCaptureView クラスの動作の定義を行います。
//

#include "stdafx.h"
#include <mmsystem.h>
#include <vfw.h>

#include "Capture.h"

#include "CaptDoc.h"
#include "CaptView.h"
#include "SetFile.h"
#include "IniFile.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define IDT_CAPTURE	2000

LRESULT PASCAL ErrorCallbackProc(HWND hWnd, int nErrID, LPSTR lpErrorText);
LRESULT PASCAL FrameCallbackProc(HWND hWnd, LPVIDEOHDR lpVHdr);

/////////////////////////////////////////////////////////////////////////////
// CCaptureView

IMPLEMENT_DYNCREATE(CCaptureView, CView)

BEGIN_MESSAGE_MAP(CCaptureView, CView)
	//{{AFX_MSG_MAP(CCaptureView)
	ON_WM_CREATE()
	ON_COMMAND(ID_SET_VIDEO_FORMAT, OnSetVideoFormat)
	ON_COMMAND(ID_SET_VIDEO_SOURCE, OnSetVideoSource)
	ON_UPDATE_COMMAND_UI(ID_SET_VIDEO_FORMAT, OnUpdateSetVideoFormat)
	ON_UPDATE_COMMAND_UI(ID_SET_VIDEO_SOURCE, OnUpdateSetVideoSource)
	ON_WM_SIZE()
	ON_WM_ERASEBKGND()
	ON_COMMAND(ID_SET_FILE, OnSetFile)
	ON_COMMAND(ID_MODE_AUTO, OnModeAuto)
	ON_COMMAND(ID_MODE_PREVIEW, OnModePreview)
	ON_COMMAND(ID_MODE_STOP, OnModeStop)
	ON_UPDATE_COMMAND_UI(ID_MODE_AUTO, OnUpdateModeAuto)
	ON_UPDATE_COMMAND_UI(ID_MODE_PREVIEW, OnUpdateModePreview)
	ON_UPDATE_COMMAND_UI(ID_MODE_STOP, OnUpdateModeStop)
	ON_COMMAND(ID_SET_DISPLAY, OnSetDisplay)
	ON_UPDATE_COMMAND_UI(ID_SET_DISPLAY, OnUpdateSetDisplay)
	ON_WM_TIMER()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CCaptureView クラスの構築/消滅

CCaptureView::CCaptureView()
{
	PWMOpen();

	m_BrushBG.CreateStockObject(LTGRAY_BRUSH);
	m_Mode = iniData.m_StartupMode;
	m_TimerID = 0;
}

CCaptureView::~CCaptureView()
{
	PWMClose();
}

BOOL CCaptureView::PreCreateWindow(CREATESTRUCT& cs)
{
    cs.style |= WS_CLIPCHILDREN; 

	return CView::PreCreateWindow(cs);
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureView クラスの描画

void CCaptureView::OnDraw(CDC* pDC)
{
	CCaptureDoc* pDoc = GetDocument();
	ASSERT_VALID(pDoc);

	// TODO: この場所にネイティブ データ用の描画コードを追加します。
}

/////////////////////////////////////////////////////////////////////////////
// CCaptureView クラスの診断

#ifdef _DEBUG
void CCaptureView::AssertValid() const
{
	CView::AssertValid();
}

void CCaptureView::Dump(CDumpContext& dc) const
{
	CView::Dump(dc);
}

CCaptureDoc* CCaptureView::GetDocument() // 非デバッグ バージョンはインラインです。
{
	ASSERT(m_pDocument->IsKindOf(RUNTIME_CLASS(CCaptureDoc)));
	return (CCaptureDoc*)m_pDocument;
}
#endif //_DEBUG

/////////////////////////////////////////////////////////////////////////////
// CCaptureView クラスのメッセージ ハンドラ

int CCaptureView::OnCreate(LPCREATESTRUCT lpCreateStruct) 
{
	if (CView::OnCreate(lpCreateStruct) == -1)
		return -1;
	
    // キャプチャウィンドウの表示
    m_hCaptureWnd = capCreateCaptureWindow ("", 
                  WS_CHILD | WS_DLGFRAME, 
                  0, 0, 160, 120, m_hWnd, 0);

	if (m_hCaptureWnd == NULL) {
		MessageBox("キャプチャウィンドウの作成に失敗しました。");
		return -1;
	}

	// キャプチャウィンドウハンドルにビューウィンドウを関連づける
	capSetUserData(m_hCaptureWnd, (DWORD)this);

	// ドライバに接続
	if (capDriverConxxxt(m_hCaptureWnd, 0))
		::ShowWindow(m_hCaptureWnd, SW_SHOW);
	else {
		m_Mode = MODE_STOP;
		MessageBox("ビデオキャプチャ機能が使用できません。");
	}

	// ドライバの状態を取得
	if (!capDriverGetCaps(m_hCaptureWnd, &m_CapDriverCaps, sizeof(m_CapDriverCaps))) {
		memset(&m_CapDriverCaps, 0, sizeof(m_CapDriverCaps));
		memset(&m_CapStatus, 0, sizeof(m_CapStatus));                                                     
	}

	// コールバック関数の設定
	capSetCallbackOnError(m_hCaptureWnd, ErrorCallbackProc);
	capSetCallbackOnFrame(m_hCaptureWnd, FrameCallbackProc);

	SetMode();	// 初期モードを設定
	
	return 0;
}

// キャプチャウィンドウの大きさをキャプチャサイズに合わせ、中央に表示
void CCaptureView::ResizeCaptureWindow()
{
	CRect rectClient;
	int width, height;

	// キャプチャサイズを取得
	capGetStatus (m_hCaptureWnd, &m_CapStatus, sizeof(CAPSTATUS)); 
	width = m_CapStatus.uiImageWidth + ::GetSystemMetrics(SM_CXFIXEDFRAME) * 2;
	height = m_CapStatus.uiImageHeight + ::GetSystemMetrics(SM_CYFIXEDFRAME) * 2;

	GetClientRect(rectClient);		// ウィンドウのクライアントエリアのサイズ
	::SetWindowPos(m_hCaptureWnd, 0, (rectClient.right - width) / 2,
				(rectClient.bottom - height) / 2, width, height, SWP_NOZORDER);
} 

// ビデオフォーマットの設定
void CCaptureView::OnSetVideoFormat() 
{
    capDlgVideoFormat (m_hCaptureWnd);
	ResizeCaptureWindow();
}

// ビデオソースの設定
void CCaptureView::OnSetVideoSource()
{
    capDlgVideoSource (m_hCaptureWnd);
}

// ディスプレイの設定
void CCaptureView::OnSetDisplay() 
{
     capDlgVideoDisplay(m_hCaptureWnd);
}

void CCaptureView::OnUpdateSetVideoFormat(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable (m_CapDriverCaps.fCaptureInitialized && m_CapDriverCaps.fHasDlgVideoFormat);
}

void CCaptureView::OnUpdateSetVideoSource(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable (m_CapDriverCaps.fCaptureInitialized && m_CapDriverCaps.fHasDlgVideoSource);   
}

void CCaptureView::OnUpdateSetDisplay(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_CapDriverCaps.fCaptureInitialized && m_CapDriverCaps.fHasDlgVideoDisplay);   
}

// サイズの変更
void CCaptureView::OnSize(UINT nType, int cx, int cy) 
{
	CView::OnSize(nType, cx, cy);
	
	ResizeCaptureWindow();
}

// クライアントエリアのバックグランドの表示（灰色）
BOOL CCaptureView::OnEraseBkgnd(CDC* pDC) 
{
	CRect rect;

	GetClientRect(rect);
	pDC->FillRect(&rect, &m_BrushBG);
	return 0;
}

// 出力ファイルの設定
void CCaptureView::OnSetFile() 
{
	CSetFile dlgSetFile;

	dlgSetFile.DoModal();
}

// 自動モード
void CCaptureView::OnModeAuto() 
{
	m_Mode = MODE_AUTO;
	GetDocument()->SetWindowTitle(m_Mode);
	SetMode();
}

// プレビューモード
void CCaptureView::OnModePreview() 
{
	m_Mode = MODE_PREVIEW;
	GetDocument()->SetWindowTitle(m_Mode);
	SetMode();
}

// 停止モード
void CCaptureView::OnModeStop() 
{
	m_Mode = MODE_STOP;
	GetDocument()->SetWindowTitle(m_Mode);
	SetMode();
}

void CCaptureView::OnUpdateModeAuto(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_CapDriverCaps.fCaptureInitialized);
	pCmdUI->SetCheck((m_Mode == MODE_AUTO) ? 1 : 0);
}

void CCaptureView::OnUpdateModePreview(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_CapDriverCaps.fCaptureInitialized);
	pCmdUI->SetCheck((m_Mode == MODE_PREVIEW) ? 1 : 0);
}

void CCaptureView::OnUpdateModeStop(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_CapDriverCaps.fCaptureInitialized);
	pCmdUI->SetCheck((m_Mode == MODE_STOP) ? 1 : 0);
}

// 現在のモードによりプレビューとタイマーのセット
void CCaptureView::SetMode()
{
	switch (m_Mode) {
	case MODE_AUTO:
		m_TimerID = SetTimer(IDT_CAPTURE, iniData.m_OutputRate * 1000, NULL);
		capPreviewRate(m_hCaptureWnd, 0);
		capPreview (m_hCaptureWnd, FALSE);
		break;
	case MODE_PREVIEW:
		if (m_TimerID != 0) {
			KillTimer(m_TimerID);
			m_TimerID = 0;
		}
		capPreviewRate(m_hCaptureWnd, 66);
		capPreview (m_hCaptureWnd, TRUE);
		break;
	case MODE_STOP:
		if (m_TimerID != 0) {
			KillTimer(m_TimerID);
			m_TimerID = 0;
		}
		capPreviewRate(m_hCaptureWnd, 0);
		capPreview (m_hCaptureWnd, FALSE);
		break;
	}
}

// エラーコールバック関数
LRESULT PASCAL ErrorCallbackProc(HWND hWnd, int nErrID, LPSTR lpErrorText)
{
	return (LRESULT)TRUE;
}

// フレームコールバック関数
LRESULT PASCAL FrameCallbackProc(HWND hWnd, LPVIDEOHDR lpVHdr)
{
	CCaptureView *pWnd = (CCaptureView *)capGetUserData(hWnd);
	extern BOOL WriteJpegFile(HWND hWnd, LPVIDEOHDR lpVHdr);

	if (pWnd != NULL) {
		if (pWnd->m_Mode == MODE_AUTO)
			WriteJpegFile(hWnd, lpVHdr);	// JPEG ファイルに書き込み
	}

	return (LRESULT)TRUE;
}

// タイマー処理
void CCaptureView::OnTimer(UINT nIDEvent) 
{
	if (nIDEvent == IDT_CAPTURE)
		capGrabFrame(m_hCaptureWnd);	// １フレーム取り込み
	
	CView::OnTimer(nIDEvent);
}

/*
void CCaptureView::OnViewWindowsize() 
{
	extern CCaptureApp theApp;
	CRect rectWindow, rectClient;
	int width, height;

	theApp.m_pMainWnd->GetWindowRect(&rectWindow);
	GetClientRect(&rectClient);

	width = (rectWindow.Width() - rectClient.Width()) + max(m_CapStatus.uiImageWidth, 340) + ::GetSystemMetrics(SM_CXFIXEDFRAME) * 2;
	height = (rectWindow.Height() - rectClient.Height()) + max(m_CapStatus.uiImageHeight, 240) + ::GetSystemMetrics(SM_CYFIXEDFRAME) * 2;
	theApp.m_pMainWnd->SetWindowPos(0, 0, 0, width , height, SWP_NOZORDER | SWP_NOMOVE);
}

void CCaptureView::OnUpdateViewWindowsize(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_CapDriverCaps.fCaptureInitialized);
}
*/

void CCaptureView::PWMOpen() 
{
	FARPROC proc;

	if ((m_hModulePMW = ::LoadLibrary("pwcpth32.dll")) == NULL)
		return;

	if ((proc = ::GetProcAddress(m_hModulePMW, "PWM_Open32")) != NULL)
		(*proc)();
}

void CCaptureView::PWMClose() 
{
	FARPROC proc;

	if (m_hModulePMW == NULL)
		return;

	if ((proc = ::GetProcAddress(m_hModulePMW, "PWM_Close32")) != NULL)
		(*proc)();

	::FreeLibrary(m_hModulePMW);
}
