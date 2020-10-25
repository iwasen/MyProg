//*****************************************************************************************************
//  1. ファイル名
//		GraphFrm.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータグラフのメインフレームウィンドウクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphFrm.h"
#include "GraphDraw.h"
#include "TimeRangeDlg.h"
#include "General.h"
#include "DataDateTime.h"
#include "GraphTitle.h"
#include "TitlePrint.h"
#include "GlobalData.h"
#include "Define.h"
#include "AnalyzeRangeDlg.h"
#include "RealtimeData.h"
#include <AfxPriv.h>

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

// 自動スクロールタイマー
#define IDT_AUTO_SCROLL		100
#define TIMER_AUTO_SCROLL	100

// 準リアルタイム表示タイマー
#define IDT_REALTIME_UPDATE	101
#define TIMER_REALTIME_UPDATE	1000

CArray <HWND, HWND> CGraphFrame::m_aFrameWnd;

// CGraphFrame

IMPLEMENT_DYNAMIC(CGraphFrame, CFrameWnd)

BEGIN_MESSAGE_MAP(CGraphFrame, CFrameWnd)
	ON_WM_CREATE()
	ON_WM_SETFOCUS()
	ON_BN_CLICKED(IDC_BUTTON_TIME_ZOOM_ALL, &CGraphFrame::OnBnClickedButtonTimeZoomAll)
	ON_BN_CLICKED(IDC_BUTTON_TIME_ZOOM_IN, &CGraphFrame::OnBnClickedButtonTimeZoomIn)
	ON_BN_CLICKED(IDC_BUTTON_TIME_ZOOM_OUT, &CGraphFrame::OnBnClickedButtonTimeZoomOut)
	ON_BN_CLICKED(IDC_BUTTON_LEVEL_ZOOM_ALL, &CGraphFrame::OnBnClickedButtonLevelZoomAll)
	ON_BN_CLICKED(IDC_BUTTON_LEVEL_ZOOM_IN, &CGraphFrame::OnBnClickedButtonLevelZoomIn)
	ON_BN_CLICKED(IDC_BUTTON_LEVEL_ZOOM_OUT, &CGraphFrame::OnBnClickedButtonLevelZoomOut)
	ON_COMMAND(IDM_PRINT, &CGraphFrame::OnPrint)
	ON_COMMAND(IDM_CLOSE, &CGraphFrame::OnClose)
	ON_BN_CLICKED(IDC_CHECK_SCROLL_START, &CGraphFrame::OnBnClickedCheckScrollStart)
	ON_BN_CLICKED(IDC_BUTTON_FFT_OCTAVE, &CGraphFrame::OnBnClickedButtonFftOctave)
	ON_BN_CLICKED(IDC_BUTTON_FFT_SPECTRUM, &CGraphFrame::OnBnClickedButtonFftSpectrum)
	ON_BN_CLICKED(IDC_BUTTON_FFT_SPECTROGRAM, &CGraphFrame::OnBnClickedButtonFftSpectrogram)
	ON_BN_CLICKED(IDC_BUTTON_SELECT_ALL, &CGraphFrame::OnBnClickedButtonSelectAll)
	ON_BN_CLICKED(IDC_BUTTON_SELECT_MANUAL, &CGraphFrame::OnBnClickedButtonSelectManual)
	ON_BN_CLICKED(IDC_BUTTON_INITIALIZE, &CGraphFrame::OnBnClickedButtonInitialize)
	ON_WM_TIMER()
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_FFT_OCTAVE, &CGraphFrame::OnUpdateButtonFft)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_FFT_SPECTRUM, &CGraphFrame::OnUpdateButtonFft)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_FFT_SPECTROGRAM, &CGraphFrame::OnUpdateButtonFft)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_TIME_ZOOM_ALL, &CGraphFrame::OnUpdateButtonTimeZoom)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_TIME_ZOOM_OUT, &CGraphFrame::OnUpdateButtonTimeZoom)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_LEVEL_ZOOM_ALL, &CGraphFrame::OnUpdateButtonLevelZoom)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_LEVEL_ZOOM_OUT, &CGraphFrame::OnUpdateButtonLevelZoom)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_SELECT_ALL, &CGraphFrame::OnUpdateButtonSelect)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_SELECT_MANUAL, &CGraphFrame::OnUpdateButtonSelect)
	ON_CBN_SELCHANGE(IDC_COMBO_SAMPLE_RATE, &CGraphFrame::OnCbnSelchangeComboSampleRate)
	ON_BN_CLICKED(IDC_BUTTON_TIME_CURSOR_TO_LEFT, &CGraphFrame::OnBnClickedButtonTimeCursorToLeft)
	ON_BN_CLICKED(IDC_BUTTON_TIME_CURSOR_TO_RIGHT, &CGraphFrame::OnBnClickedButtonTimeCursorToRight)
	ON_EN_CHANGE(IDC_EDIT_TIME_ZOOM, &CGraphFrame::OnEnChangeEditTimeZoom)
	ON_EN_CHANGE(IDC_EDIT_LEVEL_ZOOM_X, &CGraphFrame::OnEnChangeEditLevelZoomX)
	ON_EN_CHANGE(IDC_EDIT_LEVEL_ZOOM_Y, &CGraphFrame::OnEnChangeEditLevelZoomY)
	ON_EN_CHANGE(IDC_EDIT_LEVEL_ZOOM_Z, &CGraphFrame::OnEnChangeEditLevelZoomZ)
	ON_BN_CLICKED(IDC_BUTTON_FILTER_SET, &CGraphFrame::OnBnClickedButtonFilterSet)
	ON_CBN_SELCHANGE(IDC_COMBO_FILTER_BAND, &CGraphFrame::OnCbnSelchangeComboFilterBand)
	ON_UPDATE_COMMAND_UI(IDC_BUTTON_FILTER_SET, &CGraphFrame::OnUpdateButtonFilterSet)
	ON_MESSAGE(WM_KEY_RET_KEY, OnRetKey)
	ON_WM_DESTROY()
	ON_MESSAGE(WM_USER_OPEN_GRAPH, OnUserOpenGraph)
	ON_MESSAGE(WM_USER_CLOSE_GRAPH, OnUserCloseGraph)
	ON_BN_CLICKED(IDC_CHECK_DISP_D, &CGraphFrame::OnBnClickedCheckDispD)
	ON_BN_CLICKED(IDC_CHECK_DISP_X, &CGraphFrame::OnBnClickedCheckDispX)
	ON_BN_CLICKED(IDC_CHECK_DISP_Y, &CGraphFrame::OnBnClickedCheckDispY)
	ON_BN_CLICKED(IDC_CHECK_DISP_Z, &CGraphFrame::OnBnClickedCheckDispZ)
	ON_WM_MOUSEWHEEL()
	ON_BN_CLICKED(IDC_CHECK_ZERO_OFFSET_GRAPH, &CGraphFrame::OnBnClickedCheckZeroPointGraph)
	ON_MESSAGE(WM_USER_CLOSE_ZERO_OFFSET, &CGraphFrame::OnUserCloseZeroOffset)
END_MESSAGE_MAP()

static UINT indicators[] =
{
	ID_SEPARATOR,           // ステータス ライン インジケータ
	ID_INDICATOR_CAPS,
	ID_INDICATOR_NUM,
	ID_INDICATOR_SCRL,
};

CGraphFrame::CGraphFrame()
: m_sTitle(_T(""))
{
	m_bInUpdateGraph = FALSE;
	m_bFilter = FALSE;
	m_bRealtime = FALSE;
	m_pRealtimeSpectrogramDlg = NULL;
	m_pZeroOffsetGraph = NULL;
	m_hAcclGraphWnd = NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pFilePath			[I] 情報ファイルのパス名
//		int			nGraphKind			[I] グラフの種類（加速度 or 温度）
//		int			nDataType			[I] データの種類（MMA or MME）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：正常　FALSE：中止またはエラー
//*****************************************************************************************************
BOOL CGraphFrame::DispGraph(LPCTSTR pFilePath, int nGraphKind, int nDataType)
{
	// グラフ種類保存
	m_nGraphKind = nGraphKind;

	// データ種類保存
	m_cGraphData.m_nDataType = nDataType;

	// 情報ファイル読み込み
	if (!m_cGraphData.ReadInfoFile(pFilePath))
		return FALSE;

	// 時間範囲設定ダイアログ表示
	CTimeRangeDlg cTimeRangeDlg;
	cTimeRangeDlg.m_dStarttime = m_cGraphData.m_dStarttime;
	cTimeRangeDlg.m_nDataSec = (int)m_cGraphData.m_fTotalSecond;
	cTimeRangeDlg.m_sKind = (nGraphKind == GRAPH_ACCEL) ? "加速度データグラフ" : "温度データグラフ";
	if (cTimeRangeDlg.DoModal() == IDCANCEL)
		return FALSE;

	// パラメータを保存
	m_sFilePath = pFilePath;
	int nBeginTime = cTimeRangeDlg.m_nDispBegin;
	int nEndTime = cTimeRangeDlg.m_nDispEnd;
	m_cGraphData.m_dInStarttime = cTimeRangeDlg.m_dInStarttime;
	m_cGraphData.m_dInEndtime = cTimeRangeDlg.m_dInEndtime;

	// データ読み込み
	if (!m_cGraphData.ReadDataFile(nBeginTime, nEndTime, m_nGraphKind, TRUE))
		return FALSE;

	// 時間表示範囲
	m_cGraphData.m_fTimeDispEnd = m_cGraphData.m_stTimeRange.fSpan;

	// フィルタバッファ確保
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// フィルタ処理
	FilterProcess();

	// フレームをリソースからロードして作成
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, NULL, NULL);

	// 表示と更新
	ShowWindow(SW_SHOW);
	UpdateWindow();

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::DispRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムグラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pFilePath			[I] 情報ファイルのパス名
//		int			nGraphKind			[I] グラフの種類（加速度 or 温度）
//		BOOL		bExistLossFile		[I] 欠損データの有無
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CGraphFrame::DispRealtimeGraph(LPCTSTR pFilePath, int nGraphKind, BOOL bExistLossFile)
{
	// グラフ種類保存
	m_nGraphKind = nGraphKind;
	m_bRealtime = TRUE;

	// 情報ファイル読み込み
	if (!m_cGraphData.ReadInfoFile(pFilePath))
		return FALSE;

	// パラメータを保存
	m_sFilePath = pFilePath;
	m_cGraphData.m_dInStarttime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime += (long)m_cGraphData.m_fTotalSecond * 1000;
	int nBeginTime = (m_cGraphData.m_dInStarttime.m_hh * 60 + m_cGraphData.m_dInStarttime.m_mm) * 60 + m_cGraphData.m_dInStarttime.m_ss;
	int nEndTime = (m_cGraphData.m_dInEndtime.m_hh * 60 + m_cGraphData.m_dInEndtime.m_mm) * 60 + m_cGraphData.m_dInEndtime.m_ss;

	// データ読み込み
	m_cGraphData.m_fAllocFactor = 1.5;	// 後でバッファを追加するため、予め1.5倍のサイズを確保する
	if (!m_cGraphData.ReadDataFile(nBeginTime, nEndTime, m_nGraphKind, FALSE))
		return FALSE;

	// 時間表示範囲
	m_cGraphData.m_stTimeRange.fSpan = floor(m_cGraphData.m_stTimeRange.fSpan);
	m_cGraphData.m_stTimeRange.fMax = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_stTimeRange.fSpan;
	m_cGraphData.m_fTimeDispEnd = 0;

	// フィルタバッファ確保
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// フィルタ処理
	FilterProcess();

	// フレームをリソースからロードして作成
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, NULL, NULL);

	if (bExistLossFile)
		m_wndStatusBar.SetPaneText(0, "このグラフには欠損データが存在します。");

	// 表示と更新
	ShowWindow(SW_SHOW);
	UpdateWindow();

	// スペクトログラム表示
	if (m_nGraphKind == GRAPH_ACCEL) {
		m_pRealtimeSpectrogramDlg = new CFftSpectrogramDlg(CWnd::GetDesktopWindow());
		m_pRealtimeSpectrogramDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd(), TRUE);

		// 表示更新タイマー設定
		SetTimer(IDT_REALTIME_UPDATE, TIMER_REALTIME_UPDATE, NULL);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::DispZeroOffsetGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		0点補正値グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphFrame		*pGraphFrame		[I] 加速度グラフウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::DispZeroOffsetGraph(CGraphFrame *pGraphFrame)
{
	// グラフ種類保存
	m_nGraphKind = GRAPH_ZERO_OFFSET;

	m_hAcclGraphWnd = pGraphFrame->m_hWnd;

	// グラフデータを保存
	m_cGraphData = pGraphFrame->m_cGraphData;
	m_cGraphData.CalcZeroPoint();
	m_cGraphData.m_pDataX = NULL;
	m_cGraphData.m_pDataY = NULL;
	m_cGraphData.m_pDataZ = NULL;
	m_cGraphData.m_pFilterDataX = NULL;
	m_cGraphData.m_pFilterDataY = NULL;
	m_cGraphData.m_pFilterDataZ = NULL;
	m_cGraphData.m_pCurrentDataX = m_cGraphData.m_pAverageX;
	m_cGraphData.m_pCurrentDataY = m_cGraphData.m_pAverageY;
	m_cGraphData.m_pCurrentDataZ = m_cGraphData.m_pAverageZ;

	// 縦軸範囲設定
	m_cGraphData.ZeroOffsetlevelRange();

	// 時間表示範囲
	m_cGraphData.m_fTimeDispEnd = m_cGraphData.m_stTimeRange.fSpan;

	// フィルタバッファ確保
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// フィルタ処理
	FilterProcess();

	// フレームをリソースからロードして作成
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, NULL, NULL);

	// 表示と更新
	ShowWindow(SW_SHOW);
	UpdateWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::UpdateRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムグラフ更新表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL		bExistLossFile		[I] 欠損データの有無
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CGraphFrame::UpdateRealtimeGraph(BOOL bExistLossFile)
{
	// 情報ファイル読み込み
	if (!m_cGraphData.ReadInfoFile(m_sFilePath))
		return FALSE;

	// パラメータを保存
	m_cGraphData.m_dInStarttime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime += (long)m_cGraphData.m_fTotalSecond * 1000;
	int nBeginTime = (m_cGraphData.m_dInStarttime.m_hh * 60 + m_cGraphData.m_dInStarttime.m_mm) * 60 + m_cGraphData.m_dInStarttime.m_ss;
	int nEndTime = (m_cGraphData.m_dInEndtime.m_hh * 60 + m_cGraphData.m_dInEndtime.m_mm) * 60 + m_cGraphData.m_dInEndtime.m_ss;

	// 現表示範囲を保存
	SDispRange stLevelRangeX = m_cGraphData.m_stLevelRangeX;
	SDispRange stLevelRangeY = m_cGraphData.m_stLevelRangeY;
	SDispRange stLevelRangeZ = m_cGraphData.m_stLevelRangeZ;

	// データ読み込み
	if (!m_cGraphData.ReadDataFile(nBeginTime, nEndTime, m_nGraphKind, FALSE))
		return FALSE;

	// 時間表示範囲
	m_cGraphData.m_stTimeRange.fSpan = floor(m_cGraphData.m_stTimeRange.fSpan);
	m_cGraphData.m_stTimeRange.fMax = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_stTimeRange.fSpan;

	// フィルタバッファ確保
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// フィルタ処理
	FilterProcess();

	// 縦軸の表示範囲を設定
	if (m_nGraphKind == GRAPH_ACCEL) {
		m_cGraphData.m_stLevelRangeX.fBegin = stLevelRangeX.fBegin;
		m_cGraphData.m_stLevelRangeX.fDisp = stLevelRangeX.fDisp;
		m_cGraphData.m_stLevelRangeX.fZoom *= (m_cGraphData.m_stLevelRangeX.fMax - m_cGraphData.m_stLevelRangeX.fMin) / m_cGraphData.m_stLevelRangeX.fDisp;

		m_cGraphData.m_stLevelRangeY.fBegin = stLevelRangeY.fBegin;
		m_cGraphData.m_stLevelRangeY.fDisp = stLevelRangeY.fDisp;
		m_cGraphData.m_stLevelRangeY.fZoom *= (m_cGraphData.m_stLevelRangeY.fMax - m_cGraphData.m_stLevelRangeY.fMin) / m_cGraphData.m_stLevelRangeY.fDisp;

		m_cGraphData.m_stLevelRangeZ.fBegin = stLevelRangeZ.fBegin;
		m_cGraphData.m_stLevelRangeZ.fDisp = stLevelRangeZ.fDisp;
		m_cGraphData.m_stLevelRangeZ.fZoom *= (m_cGraphData.m_stLevelRangeZ.fMax - m_cGraphData.m_stLevelRangeZ.fMin) / m_cGraphData.m_stLevelRangeZ.fDisp;
	} else {
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom);
		SetZoomLevel(m_cGraphData.m_stLevelRangeY, m_cGraphData.m_stLevelRangeY.fZoom);
		SetZoomLevel(m_cGraphData.m_stLevelRangeZ, m_cGraphData.m_stLevelRangeZ.fZoom);
	}

	// 横軸の表示範囲を再設定
	SetZoomTime(m_cGraphData.m_stTimeRange.fSpan / m_cGraphData.m_stTimeRange.fDisp);

	if (bExistLossFile)
		m_wndStatusBar.SetPaneText(0, "このグラフには欠損データが存在します。");

	// グラフ再表示
	UpdateGraph();

	// スペクトログラム表示
	if (m_pRealtimeSpectrogramDlg == NULL && m_nGraphKind == GRAPH_ACCEL) {
		m_pRealtimeSpectrogramDlg = new CFftSpectrogramDlg(CWnd::GetDesktopWindow());
		m_pRealtimeSpectrogramDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd(), TRUE);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCREATESTRUCT		lpCreateStruct			[I] ウィンドウ作成情報構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		0：正常　-1：エラー
//*****************************************************************************************************
int CGraphFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// フレームのクライアント領域全体を占めるビューを作成します。
	if (!m_wndGraphView.Create(NULL, NULL, AFX_WS_DEFAULT_VIEW,
		CRect(0, 0, 0, 0), this, AFX_IDW_PANE_FIRST, NULL))
	{
		TRACE0("ビュー ウィンドウを作成できませんでした。\n");
		return -1;
	}

	// ツールバーを作成
	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_GRAPHFRAME))
	{
		TRACE0("ツール バーの作成に失敗しました。\n");
		return -1;      // 作成できませんでした。
	}

	// ステータスバーを作成
	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("ステータス バーの作成に失敗しました。\n");
		return -1;      // 作成できませんでした。
	}

	// ダイアログバーを作成
	m_wndGraphBar.m_nGraphKind = m_nGraphKind;
	if (!m_wndGraphBar.Create(this, (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) ? IDD_GRAPH_BAR_ACCL : IDD_GRAPH_BAR_TEMP, CBRS_BOTTOM, (UINT)-1)) {
		TRACE0("ダイアログ バーの作成に失敗しました。\n");
		return -1;      // 作成できませんでした。
	}

	// TODO: ツール バーをドッキング可能にしない場合は、これらの 3 行を削除してください。
//	m_wndToolBar.EnableDocking(CBRS_ALIGN_ANY);
//	EnableDocking(CBRS_ALIGN_ANY);
//	DockControlBar(&m_wndToolBar);

	// ウィンドウタイトル設定
	switch (m_nGraphKind) {
	case GRAPH_ACCEL:
		SetWindowText(m_cGraphData.GetDataTypeText() + (m_bRealtime ? " 加速度リアルタイムグラフ表示" : " 加速度グラフ表示"));
		break;
	case GRAPH_TEMP:
		SetWindowText(m_cGraphData.GetDataTypeText() + (m_bRealtime ? " 温度リアルタイムグラフ表示" : " 温度グラフ表示"));
		break;
	case GRAPH_ZERO_OFFSET:
		SetWindowText(m_cGraphData.GetDataTypeText() + " 0点補正値グラフ表示");
		break;
	}

	RecalcLayout();

	// 横軸ズーム倍率を設定
	SetZoomTime(m_bRealtime ? m_cGraphData.m_stTimeRange.fSpan / gRealtimeRange : 1, FALSE);

	// 縦軸ズーム倍率を1に設定
	SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeY, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeZ, 1);

	// フィルタ表示設定
	OnCbnSelchangeComboFilterBand();

	// 時間カーソルを表示
	m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	DispTimeCursor();

	// グラフを更新
	UpdateGraph();

	SetIcon(AfxGetApp()->LoadIcon(IDR_GRAPHFRAME), TRUE);

	// ウィンドウテーブルに登録
	m_aFrameWnd.Add(m_hWnd);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CREATESTRUCT		&cs			[I] ウィンドウ作成情報構造体
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CGraphFrame::PreCreateWindow(CREATESTRUCT &cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;
	cs.lpszClass = AfxRegisterWndClass(0);

	return TRUE;
}


// CGraphFrame 診断

#ifdef _DEBUG
void CGraphFrame::AssertValid() const
{
	CFrameWnd::AssertValid();
}

void CGraphFrame::Dump(CDumpContext& dc) const
{
	CFrameWnd::Dump(dc);
}

#endif //_DEBUG


//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnSetFocus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnSetFocus(CWnd* /*pOldWnd*/)
{
	// ビューウィンドウにフォーカスを与える
	m_wndGraphView.SetFocus();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnCmdMsg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ作成前処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT				nID				[I] コマンドID
//		int					nCode			[I] コマンド通知コード
//		void				*pExtra			[I] 付加データ
//		AFX_CMDHANDLERINFO	*pHandlerInfo	[I] ハンドラ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	0以外：メッセージが処理された　0:それ以外の場合
//*****************************************************************************************************
BOOL CGraphFrame::OnCmdMsg(UINT nID, int nCode, void *pExtra, AFX_CMDHANDLERINFO *pHandlerInfo)
{
	// ビューに最初にコマンドを処理する機会を与えます。
	if (m_wndGraphView.OnCmdMsg(nID, nCode, pExtra, pHandlerInfo))
		return TRUE;

	// それ以外の場合は、既定の処理を行います。
	return CFrameWnd::OnCmdMsg(nID, nCode, pExtra, pHandlerInfo);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::UpdateGraph()
{
	m_bInUpdateGraph = TRUE;

	// 横軸の表示範囲がデータ範囲を超えていないかチェック
	DispRangeCheck(m_cGraphData.m_stTimeRange);

	// グラフの表示を更新
	m_wndGraphView.UpdateGraph();

	// 横軸の表示範囲を表示
	CDataDateTime from, to;
	from.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)(m_cGraphData.m_stTimeRange.fBegin * 1000));
	to = from + (int)(m_cGraphData.m_stTimeRange.fDisp * 1000);
	m_wndGraphBar.DispTimeData(from.GetStr(), to.GetStr(), m_cGraphData.m_stTimeRange.fZoom);

	// 縦軸の表示範囲を表示
	m_wndGraphBar.DispLevelDataX(m_cGraphData.m_stLevelRangeX.fBegin, m_cGraphData.m_stLevelRangeX.fBegin + m_cGraphData.m_stLevelRangeX.fDisp, m_cGraphData.m_stLevelRangeX.fZoom);
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		m_wndGraphBar.DispLevelDataY(m_cGraphData.m_stLevelRangeY.fBegin, m_cGraphData.m_stLevelRangeY.fBegin + m_cGraphData.m_stLevelRangeY.fDisp, m_cGraphData.m_stLevelRangeY.fZoom);
		m_wndGraphBar.DispLevelDataZ(m_cGraphData.m_stLevelRangeZ.fBegin, m_cGraphData.m_stLevelRangeZ.fBegin + m_cGraphData.m_stLevelRangeZ.fDisp, m_cGraphData.m_stLevelRangeZ.fZoom);
	}

	// 0点補正値表示
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET)
		m_wndGraphBar.DispZeroPointValue(m_cGraphData.m_fAverageX, m_cGraphData.m_fAverageY, m_cGraphData.m_fAverageZ);

	m_bInUpdateGraph = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::DispRangeCheck
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示範囲チェック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDispRange		&stDispRange			[I] 表示範囲情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::DispRangeCheck(SDispRange &stDispRange)
{
	if (!m_bRealtime) {
		// 表示範囲がデータ範囲を超えていないかチェック
		if (stDispRange.fDisp > stDispRange.fSpan)
			stDispRange.fDisp = stDispRange.fSpan;
	}

	// 表示開始位置がデータの範囲に入っているかチェック
	if (stDispRange.fBegin > stDispRange.fMax - stDispRange.fDisp)
		stDispRange.fBegin = stDispRange.fMax - stDispRange.fDisp;
	if (stDispRange.fBegin < stDispRange.fMin)
		stDispRange.fBegin = stDispRange.fMin;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::FilterProcess
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フィルタ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::FilterProcess()
{
	if (m_bFilter) {
		m_cFilter.ExecIIR(m_cGraphData.m_pCurrentDataX, m_cGraphData.m_pFilterDataX, m_cGraphData.m_nDataSize);
		m_cFilter.ExecIIR(m_cGraphData.m_pCurrentDataY, m_cGraphData.m_pFilterDataY, m_cGraphData.m_nDataSize);
		m_cFilter.ExecIIR(m_cGraphData.m_pCurrentDataZ, m_cGraphData.m_pFilterDataZ, m_cGraphData.m_nDataSize);
		if (m_cGraphData.m_pCurrentDataD != NULL)
			m_cFilter.ExecIIR(m_cGraphData.m_pCurrentDataD, m_cGraphData.m_pFilterDataD, m_cGraphData.m_nDataSize);
	} else {
		memcpy(m_cGraphData.m_pFilterDataX, m_cGraphData.m_pCurrentDataX, sizeof(double) * m_cGraphData.m_nDataSize);
		memcpy(m_cGraphData.m_pFilterDataY, m_cGraphData.m_pCurrentDataY, sizeof(double) * m_cGraphData.m_nDataSize);
		memcpy(m_cGraphData.m_pFilterDataZ, m_cGraphData.m_pCurrentDataZ, sizeof(double) * m_cGraphData.m_nDataSize);
		if (m_cGraphData.m_pCurrentDataD != NULL)
			memcpy(m_cGraphData.m_pFilterDataD, m_cGraphData.m_pCurrentDataD, sizeof(double) * m_cGraphData.m_nDataSize);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::SetZoomTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸のズーム倍率を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimeZoom			[I] 横軸のズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::SetZoomTime(double fTimeZoom, BOOL bCheck)
{
	if (bCheck && fTimeZoom < 1)
		fTimeZoom = 1;

	m_cGraphData.m_stTimeRange.fZoom = fTimeZoom;
	m_cGraphData.m_stTimeRange.fDisp = m_cGraphData.m_stTimeRange.fSpan / m_cGraphData.m_stTimeRange.fZoom;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonTimeZoomAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の「全体表示」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeZoomAll()
{
	// ズーム倍率を1に設定
	m_cGraphData.m_stTimeRange.fBegin = m_cGraphData.m_stTimeRange.fMin;
	SetZoomTime(1);

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonTimeZoomIn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の「拡大」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeZoomIn()
{
	// ズーム倍率を２倍に設定
	SetZoomTime(m_cGraphData.m_stTimeRange.fZoom * 2);

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonTimeZoomOut
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の「縮小」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeZoomOut()
{
	// ズーム倍率を1/2倍に設定
	SetZoomTime(m_cGraphData.m_stTimeRange.fZoom / 2);

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::SetZoomLevel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸のズーム倍率を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDispRange	&stDispRange		[I] 縦軸の表示範囲
//		double		fLevelZoom			[I] 縦軸のズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::SetZoomLevel(SDispRange &stDispRange, double fLevelZoom)
{
	stDispRange.fZoom = fLevelZoom;

	if (stDispRange.fZoom == 1) {
		stDispRange.fDisp = stDispRange.fSpan;
		stDispRange.fBegin = stDispRange.fMin;
	} else {
		double fLevelDisp = stDispRange.fDisp;
		stDispRange.fDisp = stDispRange.fSpan / stDispRange.fZoom;
		stDispRange.fBegin += (fLevelDisp - stDispRange.fDisp) / 2;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonLevelZoomAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の「全体表示」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonLevelZoomAll()
{
	// ズーム倍率を1に設定
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		if (m_wndGraphBar.m_cCheckZoomX)
			SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);
		if (m_wndGraphBar.m_cCheckZoomY)
			SetZoomLevel(m_cGraphData.m_stLevelRangeY, 1);
		if (m_wndGraphBar.m_cCheckZoomZ)
			SetZoomLevel(m_cGraphData.m_stLevelRangeZ, 1);
	} else
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonLevelZoomIn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の「拡大」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonLevelZoomIn()
{
	// ズーム倍率を２倍に設定
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		if (m_wndGraphBar.m_cCheckZoomX)
			SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom * 2);
		if (m_wndGraphBar.m_cCheckZoomY)
			SetZoomLevel(m_cGraphData.m_stLevelRangeY, m_cGraphData.m_stLevelRangeY.fZoom * 2);
		if (m_wndGraphBar.m_cCheckZoomZ)
			SetZoomLevel(m_cGraphData.m_stLevelRangeZ, m_cGraphData.m_stLevelRangeZ.fZoom * 2);
	} else
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom * 2);

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonLevelZoomOut
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の「縮小」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonLevelZoomOut()
{
	// ズーム倍率を1/2倍に設定
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		if (m_wndGraphBar.m_cCheckZoomX)
			SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom / 2);
		if (m_wndGraphBar.m_cCheckZoomY)
			SetZoomLevel(m_cGraphData.m_stLevelRangeY, m_cGraphData.m_stLevelRangeY.fZoom / 2);
		if (m_wndGraphBar.m_cCheckZoomZ)
			SetZoomLevel(m_cGraphData.m_stLevelRangeZ, m_cGraphData.m_stLevelRangeZ.fZoom / 2);
	} else
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom / 2);

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::SetTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間カーソル位置設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimeCursor			[I] カーソル位置の時間
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::SetTimeCursor(double fTimeCursor)
{
	// カーソルの範囲を補正
	if (fTimeCursor < m_cGraphData.m_stTimeRange.fMin)
		fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	else if (fTimeCursor > m_cGraphData.m_stTimeRange.fMax)
		fTimeCursor = m_cGraphData.m_stTimeRange.fMax;

	// 時間カーソルをデータの存在する位置に補正
	double fSamplingRate = m_cGraphData.m_fSampleRate / m_cGraphData.m_nDownRate;
	int nDataIndex = (int)((fTimeCursor - m_cGraphData.m_stTimeRange.fMin) * fSamplingRate + 0.5);
	if (nDataIndex >= m_cGraphData.m_nDataSize)
		nDataIndex = m_cGraphData.m_nDataSize - 1;
	fTimeCursor = nDataIndex / fSamplingRate + m_cGraphData.m_stTimeRange.fMin;

	// カーソル位置の時間を保存
	m_cGraphData.m_fTimeCursor = fTimeCursor;

	// カーソルを表示
	DispTimeCursor();

	// ウィンドウを更新
	m_wndGraphView.Invalidate(FALSE);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::DispTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間カーソル位置表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::DispTimeCursor()
{
	CDataDateTime TimeCursor;
// 2009/07/03 Version 3.02 modified by y.G ->
//	TimeCursor.SetTime(m_cGraphData.m_dInStarttime.m_YY
//		, m_cGraphData.m_dInStarttime.m_MM
//		, m_cGraphData.m_dInStarttime.m_DD
//		, (int)(m_cGraphData.m_fTimeCursor * 1000));

	TimeCursor.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)(m_cGraphData.m_fTimeCursor * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G
	m_wndGraphBar.DispTimeCursor(TimeCursor.GetStr());

	if (m_cGraphData.m_fTimeCursor <= m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd) {
		int nPos = (int)((m_cGraphData.m_fTimeCursor - m_cGraphData.m_stTimeRange.fMin) * m_cGraphData.m_fSampleRate + 0.5);
		if (nPos >= 0) {
			m_wndGraphBar.DispRsuData(
				m_cGraphData.m_pFilterDataX[nPos],
				m_cGraphData.m_pFilterDataY[nPos],
				m_cGraphData.m_pFilterDataZ[nPos],
				m_cGraphData.m_pFilterDataD != NULL ? m_cGraphData.m_pFilterDataD[nPos] : 0);
		}
	} else
		m_wndGraphBar.ClearRsuData();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnPrint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		印刷ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnPrint()
{
	CGraphTitle	title_dlg;

	// タイトル入力ダイアログ表示
	title_dlg.SetTitle(m_sTitle);
	if (title_dlg.DoModal() != IDOK)
		return;
	title_dlg.GetTitle(m_sTitle);

	CPrintDialog dlg(FALSE);

	// 用紙を横向きに設定
	dlg.GetDefaults();
	LPDEVMODE lpDevInfo = dlg.GetDevMode();
	lpDevInfo->dmOrientation = DMORIENT_LANDSCAPE;
    dlg.m_pd.Flags &= ~PD_RETURNDEFAULT;

	// 印刷開始ダイアログ表示
	if(dlg.DoModal() == IDOK) {
		// 印刷用CDC取得
		CDC	dc;
		dc.Attach(dlg.GetPrinterDC());

		// 印刷サイズ取得
		int nWidth = dc.GetDeviceCaps(HORZRES);
		int nHeight = dc.GetDeviceCaps(VERTRES);
		int	nMerX = nWidth / 15;
		int	nMerY = nHeight / 15;

		// マージン設定
		CRect rectView(0, 0, nWidth, nHeight);
		rectView.DeflateRect(nMerX, nMerY);
		rectView.OffsetRect(0, nMerY / 2);
		rectView.bottom -= nMerY / 4;

		// DOCINFOを用意
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		CString sDocName = "MMA-G Acceleration Graph Doc";
		docinfo.lpszDocName = sDocName;

		// 印刷開始
		dc.StartDoc(&docinfo);

		// ページ印刷開始
		dc.StartPage();

		// グラフ印刷
		CGraphDraw cDrawGraph(&dc, 2);
		cDrawGraph.DrawGraph(dc, rectView, m_cGraphData, FALSE, m_nGraphKind);

		// タイトル等印刷
		PrintGraphInfo(dc, rectView);

		// 凡例印刷
		PrintSample(dc, rectView
				, &cDrawGraph.m_penGraphLineX
				, &cDrawGraph.m_penGraphLineY
				, &cDrawGraph.m_penGraphLineZ
				, &cDrawGraph.m_penGraphLineD);

		// ページ印刷終了
		dc.EndPage();

		// 印刷終了
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::PrintGraphInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイトル等印刷処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc				[I] デバイスコンテキスト
//		CRect		rectView		[I] 印刷範囲
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::PrintGraphInfo(CDC &dc, CRect rectView)
{
	CString sTitle;
	CString	sBegin, sEnd, sStart;
	CString	sPrTitle;
	long	diff;

	switch (m_nGraphKind) {
	case GRAPH_ACCEL:
		sTitle = "Acceleration";
		break;
	case GRAPH_TEMP:
		sTitle = "Temperature";
		break;
	case GRAPH_ZERO_OFFSET:
		sTitle = "Zero Offset";
		break;
	}

	CDataDateTime Begin, End;
// 2009/07/03 Version 3.02 modified by y.G ->
//	Begin.SetTime(m_cGraphData.m_dInStarttime.m_YY
//		, m_cGraphData.m_dInStarttime.m_MM
//		, m_cGraphData.m_dInStarttime.m_DD
//		, (int)(m_cGraphData.m_stTimeRange.fBegin * 1000));
//	End.SetTime(m_cGraphData.m_dInStarttime.m_YY
//		, m_cGraphData.m_dInStarttime.m_MM
//		, m_cGraphData.m_dInStarttime.m_DD
//		, (int)((m_cGraphData.m_stTimeRange.fBegin + m_cGraphData.m_stTimeRange.fDisp) * 1000));

	Begin.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)(m_cGraphData.m_stTimeRange.fBegin * 1000));
	End.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)((m_cGraphData.m_stTimeRange.fBegin + m_cGraphData.m_stTimeRange.fDisp) * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G
	sStart = Begin.GetStrYMDHMS();
	sBegin = Begin.GetStr();
	sEnd = End.GetStr();
	diff = End - Begin;

	// タイトル印字
	CTitlePrint title;
	// タイトル印字開始
	title.StartTitle(dc, rectView);

	// 1行目
	// タイトル印字(左詰め) UnitID,Sensor Location,SensorCoordinate
	SENSOR_INFO *pSensorInfo;
	switch (m_cGraphData.m_nDataType) {
	case DATA_TYPE_MME:
		pSensorInfo = g_pSensorInfoMme;
		break;
	case DATA_TYPE_ASU:
		pSensorInfo = g_pSensorInfoAsu;
		break;
	default:
		pSensorInfo = g_pSensorInfo;
		break;
	}
	sPrTitle.Format("%s, %d at %s{%f %f %f}"
		, m_cGraphData.GetDataTypeText()
		, m_cGraphData.m_nSensorId
		, m_cGraphData.m_SensorPos
		, pSensorInfo[m_cGraphData.m_nSensorIdx].x
		, pSensorInfo[m_cGraphData.m_nSensorIdx].y
		, pSensorInfo[m_cGraphData.m_nSensorIdx].z);
	title.PrintLeft(1, sPrTitle);

	// タイトル印字(右詰め) ISS CONFIGURATION
	sPrTitle.Format("%s", m_cGraphData.m_IssConfig);
	title.PrintRight(1, sPrTitle);

	// 2行目
	// タイトル印字(左詰め) Sampling Frequency
	sPrTitle.Format(m_cGraphData.m_nDataType == DATA_TYPE_MMA ? "SamplingFreq = %.2f Hz" : "SamplingFreq = %g Hz", m_cGraphData.m_fSampleRate);
	title.PrintLeft(2, sPrTitle);

	// タイトル印字(センター) Title
	title.PrintMid(2, m_sTitle);

	// 3行目
	// タイトル印字(センター) sTitle
	title.PrintMid(3, sTitle);

	// タイトル印字(右詰め) SPAN
	sPrTitle.Format("Span = %f hours", (double)diff / 3600000.0);
	title.PrintRight(3, sPrTitle);

	if (m_cGraphData.m_nDataType == DATA_TYPE_MMA) {
		// 4行目
		// タイトル印字(左詰め) Cutoff
		sPrTitle.Format("Cutoff = %d Hz", m_cGraphData.m_nCutoff);
		title.PrintLeft(4, sPrTitle);

		// 5行目
		// タイトル印字(左詰め) Gain
		sPrTitle.Format("Gain = %d", m_cGraphData.m_nGain);
		title.PrintLeft(5, sPrTitle);
	}

	// タイトル印字(センター) Start
	sPrTitle.Format("Start GMT %s - End GMT %s", Begin.GetStrAsctime(), End.GetStrAsctime());
	title.PrintMid(5, sPrTitle);

	// タイトル印字終了
	title.EndTitle();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::PrintSample
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイトル等印刷処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc				[I] デバイスコンテキスト
//		CRect		rectView		[I] 印刷範囲
//		CPen		*x				[I] X軸の色
//		CPen		*y				[I] Y軸の色
//		CPen		*z				[I] Z軸の色
//		CPen		*d				[I] DACMの色
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::PrintSample(CDC &dc, CRect rectView, CPen *x, CPen *y, CPen *z, CPen *d)
{
	CString	sPrTitle;
	int	defCol = 70;
	int	defX = 12;
	int	LineLen = 5;

	// タイトル印字
	CTitlePrint title;

	// タイトル印字開始
	title.StartTitle(dc, rectView);

// update 2008/03/18 by hama　→

	// 文字のサイズを取得
	CSize sizeChar = dc.GetOutputTextExtent("0");

	// 出力X位置
	int	xp = defX * sizeChar.cx;
	int yp = defCol * sizeChar.cy + sizeChar.cy / 2;
	int nl = LineLen * sizeChar.cx;

	CPen *org = dc.SelectObject(d);

	if (m_nGraphKind == GRAPH_TEMP) {
		// DACM
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "：DACM";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;

		// X軸
		dc.SelectObject(x);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "：X-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;

		// Y軸
		dc.SelectObject(y);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "：Y-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;

		// Z軸
		dc.SelectObject(z);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "：Z-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	// センサ軸情報出力
	sPrTitle.Format("+X：%s  +Y：%s  +Z：%s", (CString)m_wndGraphBar.m_cEditDirX, (CString)m_wndGraphBar.m_cEditDirY, (CString)m_wndGraphBar.m_cEditDirZ);
	title.PrintDir(rectView.Width() - dc.GetOutputTextExtent(sPrTitle).cx, defCol, sPrTitle);

	dc.SelectObject(org);

	// タイトル印字終了
	title.EndTitle();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnClose
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「閉じる」メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnClose()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedCheckScrollStart
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動スクロール開始ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckScrollStart()
{
	if (m_wndGraphBar.m_cCheckScrollStart == TRUE) {
		// ボタンの表示を「停止」に設定
		m_wndGraphBar.m_cCheckScrollStart = "停止";

		// 自動スクロールON
		m_wndGraphView.m_bAutoScroll = TRUE;

		// 現tick取得
		m_dwAutoScrollTick = ::GetTickCount();

		// タイマー設定
		SetTimer(IDT_AUTO_SCROLL, TIMER_AUTO_SCROLL, NULL);
	} else {
		// ボタンの表示を「開始」に設定
		m_wndGraphBar.m_cCheckScrollStart = "開始";

		// 自動スクロールOFF
		m_wndGraphView.m_bAutoScroll = FALSE;

		// タイマー解除
		KillTimer(IDT_AUTO_SCROLL);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnTimer
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイマーイベント処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT_PTR		nIDEvent		[I] タイマーイベントID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_AUTO_SCROLL:
		// 自動スクロール
		AutoScroll();
		break;
	case IDT_REALTIME_UPDATE:
		// 準リアルタイム表示更新
		UpdateRealtimeDisp();
		break;
	}

	CFrameWnd::OnTimer(nIDEvent);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::AutoScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::AutoScroll()
{
	// スクロール速度取得
	double fScrollSpeed = m_wndGraphBar.m_cEditScrollSpeed;

	// 時間ステップ計算
	DWORD dwCurrentTick = ::GetTickCount();
	double fTimeStep = fScrollSpeed * (dwCurrentTick - m_dwAutoScrollTick) / 1000;
	m_dwAutoScrollTick = dwCurrentTick;

	// 表示開始位置と時間カーソル位置を更新
	m_cGraphData.m_stTimeRange.fBegin += fTimeStep;
	m_cGraphData.m_fTimeCursor += fTimeStep;

	// データの最大時間に達したら停止する
	if (m_cGraphData.m_fTimeCursor >= m_cGraphData.m_stTimeRange.fMax) {
		m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMax;
		m_wndGraphBar.m_cCheckScrollStart = 0;
		OnBnClickedCheckScrollStart();
	}

	// 時間カーソル位置表示
	DispTimeCursor();

	// グラフ表示を更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonFftSpectrum
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「スペクトル」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFftSpectrum()
{
	CFftSpectrumDlg *pDlg = new CFftSpectrumDlg(CWnd::GetDesktopWindow());
	pDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd());
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonFftOctave
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「1/3オクターブ」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFftOctave()
{
	CFftOctaveDlg *pDlg = new CFftOctaveDlg(CWnd::GetDesktopWindow());
	pDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd());
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonFftSpectrogram
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「スペクトログラム」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFftSpectrogram()
{
	// ポイント数が入力されていることを確認する
	CString strTemp = m_wndGraphBar.m_cEditSpectrogramPointCount;
	if (!strTemp.Compare(""))
	{
		AfxMessageBox("ポイント数が入力されておりません。", MB_OK);
		return;
	}

	CFftSpectrogramDlg *pDlg = new CFftSpectrogramDlg(CWnd::GetDesktopWindow());
	pDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd(), FALSE);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::UpdateSelectArea
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		選択範囲更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::UpdateSelectArea()
{
	if (m_cGraphData.m_fSelectEnd > m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd)
		m_cGraphData.m_fSelectEnd = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd;

	// 選択範囲をグラフ表示
	m_wndGraphView.DispSelectArea();

	// 選択範囲を数値表示
	CDataDateTime from, to;
// 2009/07/03 Version 3.02 modified by y.G ->
//	from.SetTime(m_cGraphData.m_dInStarttime.m_YY
//		, m_cGraphData.m_dInStarttime.m_MM
//		, m_cGraphData.m_dInStarttime.m_DD
//		, (int)(m_cGraphData.m_fSelectBegin * 1000));
//	to.SetTime(m_cGraphData.m_dInStarttime.m_YY
//		, m_cGraphData.m_dInStarttime.m_MM
//		, m_cGraphData.m_dInStarttime.m_DD
//		, (int)((m_cGraphData.m_fSelectEnd+0.001) * 1000));

	from.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)(m_cGraphData.m_fSelectBegin * 1000));
	to.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)((m_cGraphData.m_fSelectEnd+0.001) * 1000));
// 2009/07/03 Version 3.02 modified by y.G ->
	m_wndGraphBar.DispSelectArea(m_cGraphData.m_fSelectBegin, m_cGraphData.m_fSelectEnd, from.GetStr(), to.GetStr());
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonSelectAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「全選択」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonSelectAll()
{
	// 全データを選択範囲に設定
	m_cGraphData.m_fSelectBegin = m_cGraphData.m_stTimeRange.fMin;
	m_cGraphData.m_fSelectEnd = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd;

	// 選択範囲の表示を更新
	UpdateSelectArea();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonSelectManual
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「範囲指定」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonSelectManual()
{
	// 選択範囲を入力
	CAnalyzeRangeDlg dlg;
	dlg.m_dDataBegin = m_cGraphData.m_dInStarttime;
	dlg.m_dDataEnd = m_cGraphData.m_dInEndtime;
	if (dlg.DoModal() == IDOK) {
		// 選択範囲を設定
		m_cGraphData.m_fSelectBegin = (dlg.m_dSelectBegin - m_cGraphData.m_dInStarttime) / 1000 + m_cGraphData.m_stTimeRange.fMin;
		m_cGraphData.m_fSelectEnd = (dlg.m_dSelectEnd - m_cGraphData.m_dInStarttime) / 1000 + m_cGraphData.m_stTimeRange.fMin;

		// 選択範囲の表示を更新
		UpdateSelectArea();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUpdateButtonFft
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		FFT解析ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CCmdUI		*pCmdUI			[I] CCmdUIクラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonFft(CCmdUI *pCmdUI)
{
	// 選択範囲の開始と終了が同じでなければ有効
	pCmdUI->Enable(m_cGraphData.m_fSelectBegin != m_cGraphData.m_fSelectEnd);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUpdateButtonTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸全体表示ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CCmdUI		*pCmdUI			[I] CCmdUIクラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonTimeZoom(CCmdUI *pCmdUI)
{
	// 横軸のズーム倍率が１でなければ有効
	pCmdUI->Enable(m_cGraphData.m_stTimeRange.fZoom != 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUpdateButtonLevelZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸全体表示ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CCmdUI		*pCmdUI			[I] CCmdUIクラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonLevelZoom(CCmdUI *pCmdUI)
{
	// 縦軸のズーム倍率が１でなければ有効
	pCmdUI->Enable(m_cGraphData.m_stLevelRangeX.fZoom != 1 || m_cGraphData.m_stLevelRangeY.fZoom != 1 || m_cGraphData.m_stLevelRangeZ.fZoom != 1);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUpdateButtonSelect
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全選択ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CCmdUI		*pCmdUI			[I] CCmdUIクラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonSelect(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(m_nGraphKind == GRAPH_ACCEL);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnCbnSelchangeComboSampleRate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		サンプル周波数コンボボックス選択変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnCbnSelchangeComboSampleRate()
{
	int nIndex = m_wndGraphBar.m_cComboSampleRate.GetCurSel();
	if (nIndex >= 0) {
		// 間引きレート取得
		m_cGraphData.m_nDownRate = (int)m_wndGraphBar.m_cComboSampleRate.GetItemData(nIndex);

		// 時間カーソル位置を補正
		SetTimeCursor(m_cGraphData.m_fTimeCursor);

		// グラフ表示を更新
		UpdateGraph();
	}
}

// 077FD050-GYM961-0_004 2007/9/8 appended by yG ->
//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonTimeCursorToLeft
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間カーソルの左移動処理(マイナス時間方向)
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeCursorToLeft()
{
	SetTimeCursor(m_cGraphData.m_fTimeCursor - m_cGraphData.m_nDownRate / m_cGraphData.m_fSampleRate); // 1サンプル分マイナス方向に移動する
/*
	m_cGraphData.m_fTimeCursor -= 0.001; // 1msマイナス方向に移動する

	// カーソルの範囲を補正
	if (m_cGraphData.m_fTimeCursor < m_cGraphData.m_stTimeRange.fMin)
		m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	else if (m_cGraphData.m_fTimeCursor > m_cGraphData.m_stTimeRange.fMax)
		m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMax;

	CDataDateTime TimeCursor;
	TimeCursor.SetTime(m_cGraphData.m_dInStarttime.m_YY
		, m_cGraphData.m_dInStarttime.m_MM
		, m_cGraphData.m_dInStarttime.m_DD
		, (int)(m_cGraphData.m_fTimeCursor * 1000));
	m_wndGraphBar.DispTimeCursor(TimeCursor.GetStr());

	int nPos = (int)((m_cGraphData.m_fTimeCursor - m_cGraphData.m_stTimeRange.fMin) * m_cGraphData.m_fSampleRate);

	m_wndGraphBar.DispRsuData(
		m_cGraphData.m_pCurrentDataX[nPos],
		m_cGraphData.m_pCurrentDataY[nPos],
		m_cGraphData.m_pCurrentDataZ[nPos],
		m_cGraphData.m_pCurrentDataD != NULL ? m_cGraphData.m_pCurrentDataD[nPos] : 0);

	// ウィンドウを更新
	m_wndGraphView.Invalidate(FALSE);
*/
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonTimeCursorToRight
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間カーソルの右移動処理(プラス時間方向)
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeCursorToRight()
{
	SetTimeCursor(m_cGraphData.m_fTimeCursor + m_cGraphData.m_nDownRate / m_cGraphData.m_fSampleRate); // 1サンプル分プラス方向に移動する
/*
	m_cGraphData.m_fTimeCursor += 0.001; // 1msプラス方向に移動する

	// カーソルの範囲を補正
	if (m_cGraphData.m_fTimeCursor < m_cGraphData.m_stTimeRange.fMin)
		m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	else if (m_cGraphData.m_fTimeCursor > m_cGraphData.m_stTimeRange.fMax)
		m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMax;

	CDataDateTime TimeCursor;
	TimeCursor.SetTime(m_cGraphData.m_dInStarttime.m_YY
		, m_cGraphData.m_dInStarttime.m_MM
		, m_cGraphData.m_dInStarttime.m_DD
		, (int)(m_cGraphData.m_fTimeCursor * 1000));
	m_wndGraphBar.DispTimeCursor(TimeCursor.GetStr());

	int nPos = (int)((m_cGraphData.m_fTimeCursor - m_cGraphData.m_stTimeRange.fMin) * m_cGraphData.m_fSampleRate);

	m_wndGraphBar.DispRsuData(
		m_cGraphData.m_pCurrentDataX[nPos],
		m_cGraphData.m_pCurrentDataY[nPos],
		m_cGraphData.m_pCurrentDataZ[nPos],
		m_cGraphData.m_pCurrentDataD != NULL ? m_cGraphData.m_pCurrentDataD[nPos] : 0);

	// ウィンドウを更新
	m_wndGraphView.Invalidate(FALSE);
*/
}
// 077FD050-GYM961-0_004 2007/9/8 appended by yG <-

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnEnChangeEditTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		入力された時間軸のズーム倍率を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditTimeZoom()
{
	if (!m_bInUpdateGraph) {
		// 時間軸のズーム倍率を設定
		SetZoomTime((double)m_wndGraphBar.m_cEditTimeZoom);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKey
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Enterキー押下処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnRetKey(WPARAM wParam, LPARAM lParam)
{
	switch(wParam) {
	case IDC_EDIT_LEVEL_BEGIN_X:
		OnRetKeyEditLevelBeginX();
		break;
	case IDC_EDIT_LEVEL_BEGIN_Y:
		OnRetKeyEditLevelBeginY();
		break;
	case IDC_EDIT_LEVEL_BEGIN_Z:
		OnRetKeyEditLevelBeginZ();
		break;
	case IDC_EDIT_LEVEL_END_X:
		OnRetKeyEditLevelEndX();
		break;
	case IDC_EDIT_LEVEL_END_Y:
		OnRetKeyEditLevelEndY();
		break;
	case IDC_EDIT_LEVEL_END_Z:
		OnRetKeyEditLevelEndZ();
		break;
	}
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKeyEditLevelBeginX
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｘ軸の表示開始位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelBeginX()
{
	if (!m_bInUpdateGraph) {
		// Ｘ軸の表示範囲を設定
		SetLevelBegin(m_cGraphData.m_stLevelRangeX, (double)m_wndGraphBar.m_cEditLevelBeginX);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKeyEditLevelBeginY
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｙ軸の表示開始位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelBeginY()
{
	if (!m_bInUpdateGraph) {
		// Ｙ軸の表示範囲を設定
		SetLevelBegin(m_cGraphData.m_stLevelRangeY, (double)m_wndGraphBar.m_cEditLevelBeginY);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKeyEditLevelBeginZ
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｚ軸の表示開始位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelBeginZ()
{
	if (!m_bInUpdateGraph) {
		// Ｚ軸の表示範囲を設定
		SetLevelBegin(m_cGraphData.m_stLevelRangeZ, (double)m_wndGraphBar.m_cEditLevelBeginZ);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKeyEditLevelEndX
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｘ軸の表示終了位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelEndX()
{
	if (!m_bInUpdateGraph) {
		// Ｘ軸の表示範囲を設定
		SetLevelEnd(m_cGraphData.m_stLevelRangeX, (double)m_wndGraphBar.m_cEditLevelEndX);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKeyEditLevelEndY
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｙ軸の表示終了位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelEndY()
{
	if (!m_bInUpdateGraph) {
		// Ｙ軸の表示範囲を設定
		SetLevelEnd(m_cGraphData.m_stLevelRangeY, (double)m_wndGraphBar.m_cEditLevelEndY);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnRetKeyEditLevelEndZ
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｚ軸の表示終了位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelEndZ()
{
	if (!m_bInUpdateGraph) {
		// Ｚ軸の表示範囲を設定
		SetLevelEnd(m_cGraphData.m_stLevelRangeZ, (double)m_wndGraphBar.m_cEditLevelEndZ);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::SetLevelBegin
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸表示範囲の開始位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDispRange	&stDispRange		[I] 表示範囲情報
//		double		fLevelBegin			[I] 縦軸開始値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::SetLevelBegin(SDispRange &stDispRange, double fLevelBegin)
{
	stDispRange.fDisp += stDispRange.fBegin - fLevelBegin;
	stDispRange.fBegin = fLevelBegin;
	stDispRange.fZoom = stDispRange.fSpan / stDispRange.fDisp;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::SetLevelEnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸表示範囲の終了位置を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDispRange	&stDispRange		[I] 表示範囲情報
//		double		fLevelEnd			[I] 縦軸終了値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::SetLevelEnd(SDispRange &stDispRange, double fLevelEnd)
{
	stDispRange.fDisp = fLevelEnd - stDispRange.fBegin;
	stDispRange.fZoom = stDispRange.fSpan / stDispRange.fDisp;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnEnChangeEditLevelZoomX
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｘ軸のズーム倍率を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditLevelZoomX()
{
	if (!m_bInUpdateGraph) {
		// Ｘ軸のズーム倍率を設定
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, (double)m_wndGraphBar.m_cEditLevelZoomX);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnEnChangeEditLevelZoomY
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｙ軸のズーム倍率を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditLevelZoomY()
{
	if (!m_bInUpdateGraph) {
		// Ｙ軸のズーム倍率を設定
		SetZoomLevel(m_cGraphData.m_stLevelRangeY, (double)m_wndGraphBar.m_cEditLevelZoomY);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnEnChangeEditLevelZoomZ
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｚ軸のズーム倍率を設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditLevelZoomZ()
{
	if (!m_bInUpdateGraph) {
		// Ｚ軸のズーム倍率を設定
		SetZoomLevel(m_cGraphData.m_stLevelRangeZ, (double)m_wndGraphBar.m_cEditLevelZoomZ);

		// グラフ更新
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonFilterSet
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フィルタ設定ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFilterSet()
{
	double fSampleFreq = m_cGraphData.m_fSampleRate / m_cGraphData.m_nDownRate;
	double fNyquistFreq = fSampleFreq / 2;
	EDFBand eFilterBand = (EDFBand)m_wndGraphBar.m_cComboFilterBand.GetItemData((int)m_wndGraphBar.m_cComboFilterBand.GetCurSel());
	EDFShape eFilterShape = (EDFShape)m_wndGraphBar.m_cComboFilterShape.GetItemData((int)m_wndGraphBar.m_cComboFilterShape.GetCurSel());
	int nFilterOrder = (int)m_wndGraphBar.m_cComboFilterOrder.GetItemData((int)m_wndGraphBar.m_cComboFilterOrder.GetCurSel());
	double fFilterFreq1 = m_wndGraphBar.m_cEditFilterFreq1;
	double fFilterFreq2 = m_wndGraphBar.m_cEditFilterFreq2;

	if ((int)eFilterBand != -1) {
		// 遮断周波数１の入力チェック
		if (fFilterFreq1 <= 0 || fFilterFreq1 >= fNyquistFreq) {
			CGeneral::Alert("遮断周波数は0より大きく%.0fより小さい値を入力してください。（0は不可）", fNyquistFreq);
			m_wndGraphBar.m_cEditFilterFreq1.SetFocusAndSel();
			return;
		}

		// 遮断周波数２の入力チェック
		if (eFilterBand == EDF_BPF || eFilterBand == EDF_BSF) {
			if (fFilterFreq2 <= 0 || fFilterFreq2 >= fNyquistFreq) {
				CGeneral::Alert("遮断周波数は0より大きく%.0fより小さい値を入力してください。（0は不可）", fNyquistFreq);
				m_wndGraphBar.m_cEditFilterFreq2.SetFocusAndSel();
				return;
			}
		}

		// フィルタ初期化
		m_cFilter.InitIIR(eFilterShape, eFilterBand, nFilterOrder, fSampleFreq, fFilterFreq1, fFilterFreq2, 0.5);

		m_bFilter = TRUE;
	} else
		m_bFilter = FALSE;

	// フィルタ処理
	FilterProcess();

	// グラフ更新
	UpdateGraph();

	// カーソル位置データ更新
	DispTimeCursor();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnCbnSelchangeComboFilterBand
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フィルタ種類コンボボックス選択変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnCbnSelchangeComboFilterBand()
{
	EDFBand eFilterBand = (EDFBand)m_wndGraphBar.m_cComboFilterBand.GetItemData((int)m_wndGraphBar.m_cComboFilterBand.GetCurSel());

	switch (eFilterBand) {
	case EDF_LPF:
	case EDF_HPF:
		m_wndGraphBar.m_cComboFilterShape.EnableWindow(TRUE);
		m_wndGraphBar.m_cComboFilterOrder.EnableWindow(TRUE);
		m_wndGraphBar.m_cEditFilterFreq1.EnableWindow(TRUE);
		m_wndGraphBar.m_cEditFilterFreq2.EnableWindow(FALSE);
		break;
	case EDF_BPF:
	case EDF_BSF:
		m_wndGraphBar.m_cComboFilterShape.EnableWindow(TRUE);
		m_wndGraphBar.m_cComboFilterOrder.EnableWindow(TRUE);
		m_wndGraphBar.m_cEditFilterFreq1.EnableWindow(TRUE);
		m_wndGraphBar.m_cEditFilterFreq2.EnableWindow(TRUE);
		break;
	default:
		m_wndGraphBar.m_cComboFilterShape.EnableWindow(FALSE);
		m_wndGraphBar.m_cComboFilterOrder.EnableWindow(FALSE);
		m_wndGraphBar.m_cEditFilterFreq1.EnableWindow(FALSE);
		m_wndGraphBar.m_cEditFilterFreq2.EnableWindow(FALSE);
		break;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUpdateButtonFilterSet
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フィルタ設定ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CCmdUI		*pCmdUI			[I] CCmdUIクラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonFilterSet(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(m_bFilter || m_wndGraphBar.m_cComboFilterShape.IsWindowEnabled());
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedButtonInitialize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「初期化」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonInitialize()
{
	if (AfxMessageBox("このウィンドウの表示を初期状態に戻しますか？", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// 自動スクロール停止
	m_wndGraphBar.m_cCheckScrollStart = FALSE;
	OnBnClickedCheckScrollStart();

	// 各コントロールを初期化
	m_wndGraphBar.InitializeControls();

	// 横軸ズーム倍率を1に設定
	SetZoomTime(1);

	// 縦軸ズーム倍率を1に設定
	SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeY, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeZ, 1);

	// 時間カーソル・選択範囲初期化
	m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	m_cGraphData.m_fSelectBegin = 0;
	m_cGraphData.m_fSelectEnd = 0;
	DispTimeCursor();

	// 間引きレート初期化
	m_cGraphData.m_nDownRate = 1;

	// 表示データ初期化
	m_cGraphData.m_bDispD = TRUE;
	m_cGraphData.m_bDispX = TRUE;
	m_cGraphData.m_bDispY = TRUE;
	m_cGraphData.m_bDispZ = TRUE;

	// フィルタ設定
	OnCbnSelchangeComboFilterBand();
	OnBnClickedButtonFilterSet();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUserOpenGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		解析グラフウィンドウオープン通知ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnUserOpenGraph(WPARAM wParam, LPARAM lParam)
{
	// オープンされた解析グラフウィンドウのハンドルを保存する
	HWND hWnd = (HWND)wParam;
	if (hWnd)
		m_aryGraphWnd.Add(hWnd);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUserCloseGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		解析グラフウィンドウクローズ通知ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnUserCloseGraph(WPARAM wParam, LPARAM lParam)
{
	// クローズされた解析グラフウィンドウのハンドルを削除する
	HWND hWnd = (HWND)wParam;
	if (hWnd) {
		for (int i = 0; i < m_aryGraphWnd.GetCount(); i++) {
			if (m_aryGraphWnd[i] == hWnd) {
				m_aryGraphWnd.RemoveAt(i);
				break;
			}
		}
	}

	// リアルタイムスペクトログラムウィンドウ
	if (m_pRealtimeSpectrogramDlg != NULL && m_pRealtimeSpectrogramDlg->m_hWnd == hWnd)
		m_pRealtimeSpectrogramDlg = NULL;

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ破棄処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnDestroy()
{
	// 解析グラフウィンドウクローズ
	while (m_aryGraphWnd.GetCount() != 0)
		::DestroyWindow(m_aryGraphWnd[0]);

	// 0点補正値グラフクローズ
	if (m_pZeroOffsetGraph != NULL)
		m_pZeroOffsetGraph->DestroyWindow();

	// 加速度グラフにクローズ通知
	if (m_hAcclGraphWnd != NULL)
		::SendMessage(m_hAcclGraphWnd, WM_USER_CLOSE_ZERO_OFFSET, 0, 0);

	// メインウィンドウにクローズ通知
	AfxGetMainWnd()->SendMessage(WM_USER_CLOSE_GRAPH, (WPARAM)m_hWnd);

	// ウィンドウテーブルから削除
	for (int i = 0; i < m_aFrameWnd.GetSize(); i++) {
		if (m_aFrameWnd[i] == m_hWnd) {
			m_aFrameWnd.RemoveAt(i);
			break;
		}
	}

	CFrameWnd::OnDestroy();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedCheckDispD
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示データのDACMチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispD()
{
	m_cGraphData.m_bDispD = m_wndGraphBar.m_cCheckDispD;

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedCheckDispX
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示データのX軸チェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispX()
{
	m_cGraphData.m_bDispX = m_wndGraphBar.m_cCheckDispX;

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedCheckDispY
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示データのY軸チェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispY()
{
	m_cGraphData.m_bDispY = m_wndGraphBar.m_cCheckDispY;

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedCheckDispZ
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示データのZ軸チェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispZ()
{
	m_cGraphData.m_bDispZ = m_wndGraphBar.m_cCheckDispZ;

	// グラフ更新
	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::CloseAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全グラフウィンドウを閉じる
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::CloseAll()
{
	while (m_aFrameWnd.GetSize() != 0)
		::DestroyWindow(m_aFrameWnd[0]);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnMouseWheel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスホイール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFlags			[I] 押されている仮想キー
//		short		zDelta			[I] 回転距離
//		CPoint		pt				[I] マウスの座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
BOOL CGraphFrame::OnMouseWheel(UINT nFlags, short zDelta, CPoint pt)
{
	// グラフウィンドウに転送
	m_wndGraphView.OnMouseWheel(nFlags, zDelta, pt);

	return CFrameWnd::OnMouseWheel(nFlags, zDelta, pt);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnBnClickedCheckZeroPointGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		0点補正値グラフ表示ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckZeroPointGraph()
{
	if (m_wndGraphBar.m_cCheckZeroOffsetGraph) {
		m_pZeroOffsetGraph = new CGraphFrame;
		m_pZeroOffsetGraph->DispZeroOffsetGraph(this);
	} else {
		if (m_pZeroOffsetGraph != NULL)
			m_pZeroOffsetGraph->DestroyWindow();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::UpdateRealtimeDisp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		準リアルタイムグラフ表示更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::UpdateRealtimeDisp()
{
	if (g_RealtimeData.m_bStartFlag) {
		if (m_cGraphData.m_fTimeDispEnd < m_cGraphData.m_stTimeRange.fSpan) {
			// 欠損データをスキップ
			int nIndex = (int)(m_cGraphData.m_fSampleRate * m_cGraphData.m_fTimeDispEnd);
			int nCount = 0;
			while (nIndex < m_cGraphData.m_nDataSize) {
				if (m_cGraphData.m_pCurrentDataX[nIndex] != 0 || m_cGraphData.m_pCurrentDataY[nIndex] != 0 || m_cGraphData.m_pCurrentDataZ[nIndex] != 0)
					break;
				nIndex++;
				nCount++;
			}
			int nSecond = (int)(nCount / m_cGraphData.m_fSampleRate);
			if (nSecond == 0)
				nSecond = 1;

			// 加速度グラフ更新
			SetRealtimeDispEnd(m_cGraphData.m_fTimeDispEnd + nSecond);

			// 温度グラフ更新
			g_RealtimeData.UpdateTempGraph(this, m_cGraphData.m_fTimeDispEnd);

			// スペクトログラムグラフ更新
			if (m_pRealtimeSpectrogramDlg != NULL)
				m_pRealtimeSpectrogramDlg->UpdateGraph();
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::SetRealtimeDispEnd
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		準リアルタイムグラフ更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimeDispEnd			[I] 表示範囲
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphFrame::SetRealtimeDispEnd(double fTimeDispEnd)
{
	m_cGraphData.m_fTimeDispEnd = fTimeDispEnd;
	if (m_cGraphData.m_fTimeDispEnd > m_cGraphData.m_stTimeRange.fSpan)
		m_cGraphData.m_fTimeDispEnd = m_cGraphData.m_stTimeRange.fSpan;

	if (!m_wndGraphBar.m_cCheckScrollStart) {
		double fBegin = m_cGraphData.m_stTimeRange.fBegin;
		m_cGraphData.m_stTimeRange.fBegin = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd - m_cGraphData.m_stTimeRange.fDisp;
		if (m_cGraphData.m_stTimeRange.fBegin < m_cGraphData.m_stTimeRange.fMin)
			m_cGraphData.m_stTimeRange.fBegin = m_cGraphData.m_stTimeRange.fMin;
		else
			m_cGraphData.m_fTimeCursor += m_cGraphData.m_stTimeRange.fBegin - fBegin;
	}

	DispTimeCursor();

	UpdateGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphFrame::OnUserCloseZeroOffset
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		準リアルタイムグラフ更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnUserCloseZeroOffset(WPARAM wParam, LPARAM lParam)
{
	m_pZeroOffsetGraph = NULL;
	m_wndGraphBar.m_cCheckZeroOffsetGraph = FALSE;

	return 0;
}
