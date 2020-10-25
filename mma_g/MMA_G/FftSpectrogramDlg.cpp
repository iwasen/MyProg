//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrogramDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトログラム解析グラフのダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		同時に複数のグラフを表示できるようにするため、モードレスダイアログで開く。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftSpectrogramDlg.h"
#include "FftSpectrogramDraw.h"
#include "General.h"
#include "Define.h"
#include "Globaldata.h"
#include "GraphDigitalDataRowCol.h"
#include "GraphTitle.h"
#include "TitlePrint.h"
#include "TimeRangeDlg.h"
#include <math.h>

// スケールの表示範囲
#define MIN_LEVEL	1e-12		// PSDの下限のデフォルト値
#define MAX_LEVEL	1e-6		// PSDの上限のデフォルト値

// 解析条件
#define FFT_SIZE	4096		// 解析幅（２の累乗であること）
#define STEP_SIZE	2048		// ステップ幅（任意の整数が可能）

// 自動スクロール
#define IDT_AUTO_SCROLL		100
#define TIMER_AUTO_SCROLL	100

// CFftSpectrogramDlg ダイアログ

IMPLEMENT_DYNAMIC(CFftSpectrogramDlg, CFftBaseDlg)

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CFftSpectrogramDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent		[I] 親ウィンドウポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CFftSpectrogramDlg::CFftSpectrogramDlg(CWnd *pParent)
	: CFftBaseDlg(CFftSpectrogramDlg::IDD, pParent)
	, m_fSelectBegin(0)
{
	// メンバ変数を初期化
	m_pSpectrogramG = NULL;
	m_nFftSize = 0;
	m_nSpectrumDataSize = 0;
	m_nTimeDataNum = 0;
	m_bCalculation = FALSE;
	m_bFilter = FALSE;
	m_fCursorTime = 0;
	m_fCursorFreq = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::~CFftSpectrogramDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CFftSpectrogramDlg::~CFftSpectrogramDlg()
{
	// バッファを解放
	FreeBuf();
}

void CFftSpectrogramDlg::DoDataExchange(CDataExchange* pDX)
{
	CFftBaseDlg::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_WINDOW_FUNCTION, m_cComboWindowFunction);
	DDX_Control(pDX, IDC_STATIC_FFT_GRAPH, m_cStaticFftGraph);
	DDX_Control(pDX, IDC_EDIT_DISP_BEGIN, m_cEditDispBegin);
	DDX_Control(pDX, IDC_EDIT_DISP_END, m_cEditDispEnd);
	DDX_Control(pDX, IDC_EDIT_DISP_BEGIN2, m_cEditDispBegin2);
	DDX_Control(pDX, IDC_EDIT_DISP_END2, m_cEditDispEnd2);
	// 077FD050-GYM961-0_003 2007/9/8 appended by yG ->
	DDX_Control(pDX, IDC_EDIT_INFO_TAA_ID, m_cEditInfoTaaId);
	DDX_Control(pDX, IDC_EDIT_INFO_ACQ_START_TIME, m_cEditInfoAcqStartTime);
	DDX_Control(pDX, IDC_EDIT_INFO_SAMPLING_RATE, m_cEditInfoSamplingRate);
	DDX_Control(pDX, IDC_EDIT_INFO_CUT_OFF_FREQ, m_cEditInfoCutOffFreq);
	DDX_Control(pDX, IDC_EDIT_INFO_GAIN, m_cEditInfoGain);
	DDX_Control(pDX, IDC_EDIT_INFO_POSITION, m_cEditInfoPosition);
	// 077FD050-GYM961-0_003 2007/9/8 appended by yG <-
	DDX_Control(pDX, IDC_EDIT_CURSOR_FREQ, m_cEditCursorFreq);
	DDX_Control(pDX, IDC_EDIT_CURSOR_TIME, m_cEditCursorTime);
	DDX_Control(pDX, IDC_EDIT_CURSOR_G, m_cEditCursorG);
	DDX_Control(pDX, IDC_EDIT_FREQ_MIN, m_cEditFreqMin);
	DDX_Control(pDX, IDC_EDIT_FREQ_MAX, m_cEditFreqMax);
	DDX_Control(pDX, IDC_RADIO_FREQ_LINEAR, m_cRadioFreqLinear);
	DDX_Control(pDX, IDC_RADIO_FREQ_LOG, m_cRadioFreqLog);
	DDX_Control(pDX, IDC_EDIT_LEVEL_MIN, m_cEditLevelMin);
	DDX_Control(pDX, IDC_EDIT_LEVEL_MAX, m_cEditLevelMax);
	DDX_Control(pDX, IDC_RADIO_LEVEL_LINEAR, m_cRadioLevelLinear);
	DDX_Control(pDX, IDC_RADIO_LEVEL_LOG, m_cRadioLevelLog);
	DDX_Control(pDX, IDC_COMBO_FILTER_BAND, m_cComboFilterBand);
	DDX_Control(pDX, IDC_COMBO_FILTER_ORDER, m_cComboFilterOrder);
	DDX_Control(pDX, IDC_COMBO_FILTER_SHAPE, m_cComboFilterShape);
	DDX_Control(pDX, IDC_EDIT_FILTER_FREQ1, m_cEditFilterFreq1);
	DDX_Control(pDX, IDC_EDIT_FILTER_FREQ2, m_cEditFilterFreq2);
	DDX_Control(pDX, IDC_BUTTON_FILTER_SET, m_cButtonFilterSet);
	DDX_Control(pDX, IDC_EDIT_DISP_BEGIN2, m_cEditDispEnd2);
	DDX_Control(pDX, IDC_EDIT_TIME_ZOOM, m_cEditTimeZoom);
	DDX_Control(pDX, IDC_EDIT_SCROLL_SPEED, m_cEditScrollSpeed);
	DDX_Control(pDX, IDC_EDIT_SCROLL_FREQ, m_cEditScrollFreq);
	DDX_Control(pDX, IDC_CHECK_SCROLL_START, m_cCheckScrollStart);
	DDX_Control(pDX, IDC_BUTTON_TIME_ZOOM_ALL, m_cButtonTimeZoomAll);
	DDX_Control(pDX, IDC_BUTTON_TIME_ZOOM_OUT, m_cButtonTimeZoomOut);
	DDX_Control(pDX, IDC_BUTTON_TIME_SET, m_cButtonTimeSet);
	DDX_Control(pDX, IDC_STATIC_INFO_TAA_ID, m_cStaticInfoTaa);
	DDX_Control(pDX, IDC_STATIC_INFO_CUT_OFF_FREQ, m_cStaticInfoCutOffFreq);
	DDX_Control(pDX, IDC_STATIC_INFO_GAIN, m_cStaticInfoGain);
	DDX_Control(pDX, IDC_STATIC_INFO_POSITION, m_cStaticInfoPosition);
}


BEGIN_MESSAGE_MAP(CFftSpectrogramDlg, CFftBaseDlg)
	ON_BN_CLICKED(IDC_BUTTON_CLOSE, &CFftSpectrogramDlg::OnBnClickedButtonClose)
	ON_CBN_SELCHANGE(IDC_COMBO_WINDOW_FUNCTION, &CFftSpectrogramDlg::OnCbnSelchangeComboWindowFunction)
	ON_WM_SIZE()
	ON_BN_CLICKED(IDC_BUTTON_PRINT, &CFftSpectrogramDlg::OnBnClickedButtonPrint)
	ON_BN_CLICKED(IDC_RADIO_FREQ_LINEAR, &CFftSpectrogramDlg::OnBnClickedRadioFreqLinear)
	ON_BN_CLICKED(IDC_RADIO_FREQ_LOG, &CFftSpectrogramDlg::OnBnClickedRadioFreqLog)
	ON_BN_CLICKED(IDC_RADIO_LEVEL_LINEAR, &CFftSpectrogramDlg::OnBnClickedRadioLevelLinear)
	ON_BN_CLICKED(IDC_RADIO_LEVEL_LOG, &CFftSpectrogramDlg::OnBnClickedRadioLevelLog)
	ON_BN_CLICKED(IDC_BUTTON_FILTER_SET, &CFftSpectrogramDlg::OnBnClickedButtonFilterSet)
	ON_CBN_SELCHANGE(IDC_COMBO_FILTER_BAND, &CFftSpectrogramDlg::OnCbnSelchangeComboFilterBand)
	ON_BN_CLICKED(IDC_BUTTON_CSV, &CFftSpectrogramDlg::OnBnClickedButtonCsv)
	ON_BN_CLICKED(IDC_BUTTON_TIME_SET, &CFftSpectrogramDlg::OnBnClickedButtonTimeSet)
	ON_MESSAGE(WM_KEY_RET_KEY, OnRetKey)
	ON_BN_CLICKED(IDC_BUTTON_INITIALIZE, &CFftSpectrogramDlg::OnBnClickedButtonInitialize)
	ON_EN_CHANGE(IDC_EDIT_TIME_ZOOM, &CFftSpectrogramDlg::OnEnChangeEditTimeZoom)
	ON_BN_CLICKED(IDC_BUTTON_TIME_ZOOM_ALL, &CFftSpectrogramDlg::OnBnClickedButtonTimeZoomAll)
	ON_BN_CLICKED(IDC_BUTTON_TIME_ZOOM_IN, &CFftSpectrogramDlg::OnBnClickedButtonTimeZoomIn)
	ON_BN_CLICKED(IDC_BUTTON_TIME_ZOOM_OUT, &CFftSpectrogramDlg::OnBnClickedButtonTimeZoomOut)
	ON_BN_CLICKED(IDC_CHECK_SCROLL_START, &CFftSpectrogramDlg::OnBnClickedCheckScrollStart)
	ON_WM_TIMER()
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_TIME_SET, "横軸(時間）の範囲を変更するためのダイアログを開きます。"},
	{IDC_BUTTON_FILTER_SET, "設定したフィルタをデータに適用します。"},
	{IDC_BUTTON_CSV, "スペクトログラム数値データ表示ダイアログを開きます。"},
	{IDC_BUTTON_INITIALIZE, "このウィンドウの表示内容を初期状態に戻します。"},
	{IDC_BUTTON_PRINT, "スペクトログラム解析グラフを印刷します。"},
	{IDC_BUTTON_CLOSE, "このウィンドウを閉じます。"},
	{IDC_COMBO_FILTER_BAND, "フィルタの種類を、無し（全域通過）、LPF（低域通過）、HPF（高域通過）、BPF（帯域通過）、BSF（帯域阻止）から選択します。"},
	{IDC_COMBO_FILTER_ORDER, "フィルタの次数を設定します。次数が大きいほど急峻なフィルタとなります。"},
	{IDC_COMBO_FILTER_SHAPE, "フィルタの特性を、Butterworth（バターワース特性）、Chebyshev（チェビシェフ特性）、Bessel（ベッセル特性）から選択します。"},
	{IDC_COMBO_WINDOW_FUNCTION, "FFT計算時に適用する窓関数を選択します。"},
	{IDC_BUTTON_TIME_ZOOM_ALL, "横軸(時間）の倍率を１に設定し、全体を表示します。"},
	{IDC_BUTTON_TIME_ZOOM_IN, "横軸(時間）の倍率を２倍に拡大します。"},
	{IDC_BUTTON_TIME_ZOOM_OUT, "横軸(時間）の倍率を1/2倍に縮小します。"},
	{IDC_CHECK_SCROLL_START, "カーソルの時間を自動的に右へ移動します。動作中にクリックすると停止します。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CreateGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフウィンドウ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphData		*pGraphData		[I] グラフデータクラスへのポインタ
//		CGraphBar		*pGraphBar		[I] グラフバーへのポインタ
//		HWND			hWndParent		[I] 親ウィンドウハンドル
//		BOOL			bRealtime		[I] リアルタイム表示フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::CreateGraph(CGraphData *pGraphData, CGraphBar *pGraphBar, HWND hWndParent, BOOL bRealtime)
{
	// グラフデータへのポインタを保存
	m_pGraphData = pGraphData;
	m_pGraphBar = pGraphBar;
	m_hWndParent = hWndParent;
	m_bRealtime = bRealtime;

	// 表示範囲を保存
	if (bRealtime) {
		m_fDefaultSelectBegin = pGraphData->m_stTimeRange.fMin;
		m_fDefaultSelectEnd = pGraphData->m_stTimeRange.fMax;
		m_fCalcTime = pGraphData->m_fTimeDispEnd;
	} else {
		m_fDefaultSelectBegin = m_pGraphData->m_fSelectBegin;
		m_fDefaultSelectEnd = m_pGraphData->m_fSelectEnd;
	}
	m_fSelectBegin = m_fDefaultSelectBegin;
	m_fSelectEnd = m_fDefaultSelectEnd;

// 2009/07/03 Version 3.02 modified by y.G ->
//	m_StartTime.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_pGraphData->m_fSelectBegin * 1000));

	m_StartTime.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)(m_fDefaultSelectBegin * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G

	// 計算処理
	if (!m_bRealtime && !IsEnableCalc()) {
		delete this;
		return;
	}

	// モードレスでダイアログを表示
	DoModeless();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムグラフウィンドウ更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::UpdateGraph()
{
	// 表示範囲を保存
	m_fSelectEnd = m_pGraphData->m_stTimeRange.fMin + max(m_pGraphData->m_fTimeDispEnd, gRealtimeRange);
	m_fSelectBegin = m_fSelectEnd - gRealtimeRange;
	m_fTimeEnd = m_fSelectEnd;

	// 表示範囲表示
	CDataDateTime from, to;
	from.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)(m_fSelectBegin * 1000));
	to.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)((m_pGraphData->m_stTimeRange.fMin + m_pGraphData->m_fTimeDispEnd) * 1000));
	m_cEditDispBegin = from.GetStr();
	m_cEditDispEnd = to.GetStr();
	m_cEditDispBegin2 = from.GetStrYMDHMS();
	m_cEditDispEnd2 = to.GetStrYMDHMS();

	// 計算
	CalcRealtime();

	// カーソル位置データ表示
	DispCursor();

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CFftSpectrogramDlg::OnInitDialog()
{
	CFftBaseDlg::OnInitDialog();

	// タイトル表示
	SetWindowText(m_bRealtime ? "スペクトログラムリアルタイム表示" : "スペクトログラム表示");

	SetIcon(AfxGetApp()->LoadIcon(IDI_SPECTROGRAM), TRUE);

	// ステータスバー作成
	CreateStatusBar();

	// 窓関数プルダウン設定
	m_cFft.SetWindowFuncList(m_cComboWindowFunction, WF_HANNING);

	// コントロールの位置を調整
	SetAllCtlPosition(0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_FFT_GRAPH, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_PRINT, 1, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_CLOSE, 1, 1, 1, 1);

// 077FD050-GYM961-0_003 2007/9/8 appended by yG ->
	// センサ/計測情報エリアを表示
	CDataDateTime acqStartTime;
	acqStartTime.SetTime(m_pGraphData->m_nStartYear,
						 m_pGraphData->m_nStartMonth,
						 m_pGraphData->m_nStartDay,
						 m_pGraphData->m_nStartMiliSecond);
	m_cEditInfoTaaId		= m_pGraphData->m_nSensorId;
	m_cEditInfoAcqStartTime	= acqStartTime.GetStr();
	m_cEditInfoSamplingRate	= m_pGraphData->m_fSampleRate;
	switch (m_pGraphData->m_nDataType) {
	case DATA_TYPE_MMA:
		m_cEditInfoCutOffFreq	= m_pGraphData->m_nCutoff;
		m_cEditInfoGain			= m_pGraphData->m_nGain;
		m_cEditInfoPosition		= m_pGraphData->m_SensorPos;
		break;
	case DATA_TYPE_MME:
		m_cStaticInfoTaa.SetWindowText("Sensor ID");
		m_cStaticInfoCutOffFreq.EnableWindow(FALSE);
		m_cStaticInfoGain.EnableWindow(FALSE);
		m_cEditInfoPosition		= m_pGraphData->m_SensorPos;
		break;
	case DATA_TYPE_ASU:
		m_cStaticInfoTaa.SetWindowText("Sensor ID");
		m_cStaticInfoCutOffFreq.EnableWindow(FALSE);
		m_cStaticInfoGain.EnableWindow(FALSE);
		m_cStaticInfoPosition.EnableWindow(FALSE);
		break;
	}
// 077FD050-GYM961-0_003 2007/9/8 appended by yG <-

	// フィルタの設定
	CGeneral::SetFilterBandList(m_cComboFilterBand, m_pGraphBar->m_cComboFilterBand.GetCurSel());
	CGeneral::SetFilterOrderList(m_cComboFilterOrder, m_pGraphBar->m_cComboFilterOrder.GetCurSel());
	CGeneral::SetFilterTypeList(m_cComboFilterShape, m_pGraphBar->m_cComboFilterShape.GetCurSel());
	m_cEditFilterFreq1 = (CString)m_pGraphBar->m_cEditFilterFreq1;
	m_cEditFilterFreq2 = (CString)m_pGraphBar->m_cEditFilterFreq2;
	OnCbnSelchangeComboFilterBand();
	SetFilter(FALSE);

	// 各コントロール初期化
	InitializeControls();

	// 画面幅を加速度グラフと合わせる
	CRect rectAccl;
	CRect rectSpectrogram;
	CRect rectScreen;
	::SystemParametersInfo(SPI_GETWORKAREA, 0, &rectScreen, 0);
	::GetWindowRect(m_hWndParent, &rectAccl);
	GetWindowRect(rectSpectrogram);
	rectSpectrogram.right = rectSpectrogram.left + rectAccl.Width();
	SetWindowPos(NULL, (rectScreen.Width() - rectSpectrogram.Width()) / 2, rectSpectrogram.top, rectSpectrogram.Width(), rectSpectrogram.Height(), SWP_NOZORDER);

	// 計算に時間がかかるのでここでウィンドウ表示
	ShowWindow(SW_SHOW);

	// リアルタイム表示初期化
	if (m_bRealtime) {
		m_cButtonTimeSet.EnableWindow(FALSE);
		InitRealtime();
	}

	// 計算＆グラフ表示
	CalcAndDispGraph();

	// エディットコントロールの有効文字設定
	m_cEditFreqMin.SetValidChar(VC_NUM | VC_POINT);
	m_cEditFreqMax.SetValidChar(VC_NUM | VC_POINT);
	m_cEditLevelMin.SetValidChar(VC_NUM | VC_MINUS | VC_POINT | VC_EXP);
	m_cEditLevelMax.SetValidChar(VC_NUM | VC_MINUS | VC_POINT | VC_EXP);
	m_cEditFilterFreq1.SetValidChar(VC_NUM | VC_POINT);
	m_cEditFilterFreq2.SetValidChar(VC_NUM | VC_POINT);

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::InitializeControls
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		各コントロール初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::InitializeControls()
{
	// スケール範囲の初期値をセット
	m_fScaleFreqMin = 0;
	m_fScaleFreqMax = m_pGraphData->m_fSampleRate / 2;
	m_bScaleFreqLog = FALSE;
	m_fScaleLevelMin = MIN_LEVEL;
	m_fScaleLevelMax = MAX_LEVEL;
	m_bScaleLevelLog = TRUE;

	// 横軸の初期値をセット
	m_cEditFreqMin = m_fScaleFreqMin;
	m_cEditFreqMax = m_fScaleFreqMax;
	m_cRadioFreqLog = m_bScaleFreqLog;
	m_cRadioFreqLinear = !m_bScaleFreqLog;

	// 縦軸の初期値をセット
	m_cEditLevelMin = m_fScaleLevelMin;
	m_cEditLevelMax = m_fScaleLevelMax;
	m_cRadioLevelLog = m_bScaleLevelLog;
	m_cRadioLevelLinear = !m_bScaleLevelLog;

	// 窓関数の初期値をセット
	m_nWindowFunc = WF_HANNING;
	m_cComboWindowFunction.SetCurSel(m_nWindowFunc);

	// 時間ズームの初期値をセット
	m_fTimeOffset = 0;
	m_fTimeZoom = 1.0;
	SetTimeZoom();

	// 自動スクロールの初期値をセット
	m_cEditScrollSpeed = 1;
	m_cEditScrollFreq.Blank();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonClose
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「閉じる」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonClose()
{
	// ウィンドウを閉じる
	OnCancel();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CalcAndDispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		計算とグラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcAndDispGraph()
{
	if (m_bRealtime) {
		m_fCalcTime = max(m_pGraphData->m_fTimeDispEnd - m_nTimeDataNum, 0);
		CalcRealtime();
	} else {
		if (! IsEnableCalc())
			return;

		// 表示範囲表示
		CDataDateTime from, to;
	// 2009/07/03 Version 3.02 modified by y.G ->
	//	from.SetTime(m_pGraphData->m_dInStarttime.m_YY
	//		, m_pGraphData->m_dInStarttime.m_MM
	//		, m_pGraphData->m_dInStarttime.m_DD
	//		, (int)(m_pGraphData->m_fSelectBegin * 1000));
	//	to.SetTime(m_pGraphData->m_dInStarttime.m_YY
	//		, m_pGraphData->m_dInStarttime.m_MM
	//		, m_pGraphData->m_dInStarttime.m_DD
	//		, (int)((m_pGraphData->m_fSelectEnd+0.001) * 1000));

		from.SetTime(m_pGraphData->m_dStarttime.m_YY
			, m_pGraphData->m_dStarttime.m_MM
			, m_pGraphData->m_dStarttime.m_DD
			, (int)(m_fDefaultSelectBegin * 1000));
		to.SetTime(m_pGraphData->m_dStarttime.m_YY
			, m_pGraphData->m_dStarttime.m_MM
			, m_pGraphData->m_dStarttime.m_DD
			, (int)((m_fDefaultSelectEnd+0.001) * 1000));
	// <- 2009/07/03 Version 3.02 modified by y.G
		m_cEditDispBegin = from.GetStr();
		m_cEditDispEnd = to.GetStr();
		m_cEditDispBegin2 = from.GetStrYMDHMS();
		m_cEditDispEnd2 = to.GetStrYMDHMS();

		// ステータスバーに"計算中"を表示
		SetStatusBar("計算中");

		// グラフウィンドウを非表示
		m_cStaticFftGraph.HideGraph();
		UpdateWindow();

		// データ計算処理
		CalcData();
	}

	// グラフ表示
	DispGraph();

	// ステータスバーに"レディ"を表示
	SetStatusBar("レディ");
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::IsEnableCalc
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		bool	true：正常　false:エラー
//*****************************************************************************************************
bool CFftSpectrogramDlg::IsEnableCalc()
{
	int	nFftSize = 256;
	// 選択範囲のデータのインデックスを取得
	int nBeginIndex = (int)((m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);

	// FFTサイズ、スペクトルデータサイズを設定
	int nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;

	// 1ポイントあたりのデータ幅[秒]を求める
	int	nPointCount = m_pGraphBar->m_cEditSpectrogramPointCount;
	double dWidthPerPoint = (double)(m_fSelectEnd - m_fSelectBegin)  / nPointCount;
	// 1ポイントあたりの解析するデータ数を求める
	double dDataPerPoint = dWidthPerPoint * m_pGraphData->m_fSampleRate * 2;
	// 1ポイントあたりの解析データ数を超えない最大の2の累乗を解析幅とする
	// 2^0, 2^1, 2^2, 2^3, 2^4, 2^5, 2^6, 2^7, 2^8, 2^9, 2^10, 2^11, 2^12, 2^13, 2^14
	//   1    2    4    8   16   32   64  128  255  512  1024  2048  4096  8192 16384
	if (dDataPerPoint < 256)
		nFftSize = 256; // 最小値:2^8
// 2008/8/12 modified by yG ->
//	else if (dDataPerPoint > 16384)
//		nFftSize = 16384; // 最大値:2~
	else if (dDataPerPoint > 67108864)
		nFftSize = 67108864; // 最大値:2~
// <- 2008/8/12 modified by yG
	else
	{
		int nTempSize = 256;
// 2008/8/12 modified by yG ->
//		for (int iCount=9 ; iCount<15 ; iCount++)
		for (int iCount=9 ; iCount<27 ; iCount++)
// <- 2008/8/12 modified by yG
		{
			nTempSize *= 2;
			if (dDataPerPoint >= nTempSize && dDataPerPoint < nTempSize * 2)
			{
				nFftSize = nTempSize;
				break;
			}
		}
	}
	int nStepSize = nFftSize / 2; // ずらし幅は，解析幅の50％
	// 時間のパラメータを設定
	int nTimeDataNum = (nDataSize - nFftSize) / nStepSize;

	// 選択した範囲のデータサイズをチェック
	if (nTimeDataNum < 1) {
		if (!m_bRealtime)
			CGeneral::Alert("選択した範囲が小さすぎるため、グラフを表示できません。");
		return false;
	}

	return true;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CalcData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常	FALSE：エラー
//*****************************************************************************************************
BOOL CFftSpectrogramDlg::CalcData()
{
	// 選択範囲のデータのインデックスを取得
	int nBeginIndex = (int)((m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);

	if (nEndIndex > m_pGraphData->m_nDataSize)
		nEndIndex = m_pGraphData->m_nDataSize;

	// FFTサイズ、スペクトルデータサイズを設定
	int nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;

// 077FD050-GYM961-0_005 2007/9/8 modified by yG ->
//	m_nFftSize = FFT_SIZE;
//	m_nStepSize = STEP_SIZE;
	// 1ポイントあたりのデータ幅[秒]を求める
	int	nPointCount = m_pGraphBar->m_cEditSpectrogramPointCount;
	double dWidthPerPoint = (double)(m_fSelectEnd - m_fSelectBegin) / nPointCount;
	// 1ポイントあたりの解析するデータ数を求める
	double dDataPerPoint = dWidthPerPoint * m_pGraphData->m_fSampleRate * 2;
	// 1ポイントあたりの解析データ数を超えない最大の2の累乗を解析幅とする
	// 2^0, 2^1, 2^2, 2^3, 2^4, 2^5, 2^6, 2^7, 2^8, 2^9, 2^10, 2^11, 2^12, 2^13, 2^14
	//   1    2    4    8   16   32   64  128  255  512  1024  2048  4096  8192 16384
// 2008/8/12 modified by yG ->
	//  2^15, 2^16,  2^17,   2^18,   2^19,    2^20,    2^21,    2^22,    2^23,     2^24,     2^25,     2^26
	// 32768 65535 131072  262144  524288  1048576  2097152  4194304  8388608  16777216  33554432  67108864
	m_nFftSize = 256; // 最小値:2^8
	if (dDataPerPoint < 256)
		m_nFftSize = 256; // 最小値:2^8
	else if (dDataPerPoint > 67108864)
		m_nFftSize = 67108864; // 最大値:2^26
	else
	{
		int nTempSize = 256;
		for (int iCount=9 ; iCount<27 ; iCount++)
		{
			nTempSize *= 2;
			if (dDataPerPoint >= nTempSize && dDataPerPoint < nTempSize * 2)
			{
				m_nFftSize = nTempSize;
				break;
			}
		}
	}
	//m_nFftSize = 256; // 最小値:2^8
	//if (dDataPerPoint < 256)
	//	m_nFftSize = 256; // 最小値:2^8
	//else if (dDataPerPoint > 16384)
	//	m_nFftSize = 16384; // 最大値:2~
	//else
	//{
	//	int nTempSize = 256;
	//	for (int iCount=9 ; iCount<15 ; iCount++)
	//	{
	//		nTempSize *= 2;
	//		if (dDataPerPoint >= nTempSize && dDataPerPoint < nTempSize * 2)
	//		{
	//			m_nFftSize = nTempSize;
	//			break;
	//		}
	//	}
	//}
// <- 2008/8/12 modified by yG
	m_nStepSize = m_nFftSize / 2; // ずらし幅は，解析幅の50％
// 077FD050-GYM961-0_005 2007/9/8 modified by yG <-

	m_nSpectrumDataSize = m_nFftSize / 2;
	m_fSampleRate = m_pGraphData->m_fSampleRate / m_pGraphData->m_nDownRate;

	// 時間のパラメータを設定
	m_nTimeDataNum = (nDataSize - m_nFftSize) / m_nStepSize;
	m_fTimeStep = m_nStepSize / m_fSampleRate;
	m_fTimeEnd = m_fSelectBegin + m_nTimeDataNum * m_fTimeStep;

	// バッファ確保
	AllocBuf();

	// スペクトログラム計算
	CalcSpectrogram(
			m_pGraphData->m_pCurrentDataX + nBeginIndex,
			m_pGraphData->m_pCurrentDataY + nBeginIndex,
			m_pGraphData->m_pCurrentDataZ + nBeginIndex,
			m_pSpectrogramG);

	// 計算済みフラグセット
	m_bCalculation = TRUE;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::AllocBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バッファ確保処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::AllocBuf()
{
	// バッファ解放
	FreeBuf();

	// バッファ確保
	int nAllocSize = m_nSpectrumDataSize * m_nTimeDataNum;
	m_pSpectrogramG = new double[nAllocSize];
	memset(m_pSpectrogramG, 0, nAllocSize * sizeof(double));
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::FreeBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バッファ解放処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::FreeBuf()
{
	if (m_pSpectrogramG != NULL) {
		delete [] m_pSpectrogramG;
		m_pSpectrogramG = NULL;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::DispGraph()
{
	// グラフ表示
	if (m_bCalculation) {
		m_cStaticFftGraph.DispGraph(m_pSpectrogramG, m_nSpectrumDataSize, m_nTimeDataNum,
				m_fSampleRate,
				m_fSelectBegin, m_fTimeEnd,
				m_fTimeOffset, m_fTimeZoom,
				GetMinFreq(), m_fScaleFreqMax, m_bScaleFreqLog,
				GetMinLevel(), m_fScaleLevelMax, m_bScaleLevelLog);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CalcSpectrogram
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトログラム計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		*pBinaryDataX		[I] X軸バイナリデータへのポインタ
//		double		*pBinaryDataY		[I] Y軸バイナリデータへのポインタ
//		double		*pBinaryDataZ		[I] Z軸バイナリデータへのポインタ
//		double		*pSpectrogram		[O] スペクトログラムデータバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcSpectrogram(const double *pBinaryDataX, const double *pBinaryDataY, const double *pBinaryDataZ, double *pSpectrogram)
{
	int i, j;

	// PSDバッファ確保
	double *pSpectrumX = new double[m_nSpectrumDataSize];
	double *pSpectrumY = new double[m_nSpectrumDataSize];
	double *pSpectrumZ = new double[m_nSpectrumDataSize];

	for (i = 0; i < m_nTimeDataNum; i++) {
		// バイナリデータ位置オフセット
		int nOffset = i * m_nStepSize * m_pGraphData->m_nDownRate;

		// PSD計算
		CalcSpectrum(pBinaryDataX + nOffset, pSpectrumX);
		CalcSpectrum(pBinaryDataY + nOffset, pSpectrumY);
		CalcSpectrum(pBinaryDataZ + nOffset, pSpectrumZ);

		// 合力計算
		for (j = 0; j < m_nSpectrumDataSize; j++)
			*pSpectrogram++ = pSpectrumX[j] + pSpectrumY[j] + pSpectrumZ[j];
	}

	// PSDバッファ解放
	delete [] pSpectrumX;
	delete [] pSpectrumY;
	delete [] pSpectrumZ;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CalcSpectrum
//-------------------------------------------------------------------------------------------------
//  2. 機能
//		PSD計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		*pBinaryData		[I] バイナリデータへのポインタ
//		double		*pSpectrum			[O] スペクトルデータバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcSpectrum(const double *pBinaryData, double *pSpectrum)
{
	int i;
	double fAve;

	// FFTバッファ確保
	double *pFftBuf = new double[m_nFftSize];

	// 平均値取得
	if (m_pGraphBar->m_cCheckZeroHosei) {
		double fSum = 0;
		for (i = 0; i < m_nFftSize; i++)
			fSum += pBinaryData[i * m_pGraphData->m_nDownRate];
		fAve = fSum / m_nFftSize;
	} else
		fAve = 0;

	// FFTバッファにデータコピー
	for (i = 0; i < m_nFftSize; i++)
		pFftBuf[i] = (pBinaryData[i * m_pGraphData->m_nDownRate] - fAve) * 1e-6;

	// フィルタ処理
	if (m_bFilter)
		m_cFilter.ExecIIR(pFftBuf, pFftBuf, m_nFftSize);

	// 窓関数
	m_cFft.WindowFunc(m_nWindowFunc, m_nFftSize, pFftBuf);

	// FFT実行
	m_cFft.FFT(m_nFftSize, pFftBuf);

	// パワースペクトル密度(PSD)計算
	double fPsd = 2 / (m_nFftSize * m_fSampleRate);
	double x, y;
//	pFftBuf[0] = 0;
	for (i = 1; i < m_nSpectrumDataSize - 1; i++) {
		double x = pFftBuf[i * 2];
		double y = pFftBuf[i * 2 + 1];
		pSpectrum[i] = (x * x + y * y) * fPsd;
	}

	// K=0 and k=N/2のケースの計算
	fPsd = 1 / (m_nSpectrumDataSize * m_fSampleRate);
	x = pFftBuf[0];
	y = pFftBuf[1];
	pSpectrum[0] = (x * x + y * y) * fPsd;

	x = pFftBuf[m_nSpectrumDataSize - 2];
	y = pFftBuf[m_nSpectrumDataSize - 1];
	pSpectrum[m_nSpectrumDataSize - 1] = (x * x + y * y) * fPsd;

	// FFTバッファ解放
	delete [] pFftBuf;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnCbnSelchangeComboWindowFunction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		窓関数選択処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnCbnSelchangeComboWindowFunction()
{
	// 選択した窓関数を取得
	m_nWindowFunc = m_cComboWindowFunction.GetCurSel();

	// 再計算＆表示
	CalcAndDispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウサイズ変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nType			[I] サイズ変更のタイプ
//		int		cx				[I] ウィンドウの幅
//		int		cy				[I] ウィンドウの高さ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnSize(UINT nType, int cx, int cy)
{
	CFftBaseDlg::OnSize(nType, cx, cy);

	// カーソル表示
	DispCursor();

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonPrint
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
void CFftSpectrogramDlg::OnBnClickedButtonPrint()
{
	CGraphTitle	title_dlg(this);

	// タイトル入力ダイアログ表示
	title_dlg.SetTitle(m_sTitle);
	if (title_dlg.DoModal() != IDOK)
		return;
	title_dlg.GetTitle(m_sTitle);

	CPrintDialog dlg(FALSE, PD_ALLPAGES | PD_USEDEVMODECOPIES | PD_NOPAGENUMS | PD_HIDEPRINTTOFILE | PD_NOSELECTION, this);

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

		// マージン設定
		CRect rectView(0, 0, nWidth, nHeight);
		rectView.DeflateRect(nWidth / 10, nHeight / 10);

		// DOCINFOを用意
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		docinfo.lpszDocName = "MMA-G Spectrogram Graph Doc";

		// 印刷開始
		dc.StartDoc(&docinfo);

		// ページ印刷開始
		dc.StartPage();

		// グラフ印刷
		CFftSpectrogramDraw cDrawGraph(&dc, 2);
		cDrawGraph.DrawGraph(dc, rectView,
				m_pSpectrogramG, m_nSpectrumDataSize, m_nTimeDataNum, m_fSampleRate,
				m_fSelectBegin, m_fTimeEnd,
				m_fTimeOffset, m_fTimeZoom,
				GetMinFreq(), m_fScaleFreqMax, m_bScaleFreqLog,
				GetMinLevel(), m_fScaleLevelMax, m_bScaleLevelLog);

		// タイトル等印刷
		PrintGraphInfo(dc, rectView);

		// ページ印刷終了
		dc.EndPage();

		// 印刷終了
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::PrintGraphInfo
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
void CFftSpectrogramDlg::PrintGraphInfo(CDC &dc, CRect rectView)
{
	CString sTitle = "Spectrogram";
	CString	sBegin, sEnd, sStart;
	CString	sPrTitle;
	long	diff;
	int	defCol = 1;

	CDataDateTime Begin, End;
// 2009/06/08 Version 3.01 modified by y.G ->
//	Begin.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_pGraphData->m_fSelectBegin * 1000));
//	End.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)((m_pGraphData->m_fSelectEnd+0.001) * 1000));

// 2009/07/03 Version 3.02 modified by y.G ->
//	Begin.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_fSelectBegin * 1000));
//	End.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)((m_fSelectEnd+0.001) * 1000));

	Begin.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)(m_fSelectBegin * 1000));
	End.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)((m_fSelectEnd+0.001) * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G

// <- 2009/06/08 Version 3.01 modified by y.G
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
	switch (m_pGraphData->m_nDataType) {
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
		, m_pGraphData->GetDataTypeText()
		, m_pGraphData->m_nSensorId
		, m_pGraphData->m_SensorPos
		, pSensorInfo[m_pGraphData->m_nSensorIdx].x
		, pSensorInfo[m_pGraphData->m_nSensorIdx].y
		, pSensorInfo[m_pGraphData->m_nSensorIdx].z);
	title.PrintLeft(defCol + 1, sPrTitle);

	// タイトル印字(右詰め) ISS CONFIGURATION
	sPrTitle.Format("%s", m_pGraphData->m_IssConfig);
	title.PrintRight(defCol + 1, sPrTitle);

	// 2行目
	// タイトル印字(左詰め) Sampling Frequency
	sPrTitle.Format(m_pGraphData->m_nDataType == DATA_TYPE_MMA ? "SamplingFreq = %.2f Hz" : "SamplingFreq = %g Hz", m_pGraphData->m_fSampleRate);
	title.PrintLeft(defCol + 2, sPrTitle);

	// タイトル印字(センター) Title
	title.PrintMid(defCol + 2, m_sTitle);

	// タイトル印字(右詰め) Window
	sPrTitle.Format("%s", g_tWindowFunc[m_nWindowFunc]);
	title.PrintRight(defCol + 2, sPrTitle);

	// 3行目
	// タイトル印字(左詰め) df Nfft
	sPrTitle.Format("df = %f Hz, Nfft = %d", m_fSampleRate / m_nSpectrumDataSize, m_nSpectrumDataSize);
	title.PrintLeft(defCol + 3, sPrTitle);

	// タイトル印字(センター) sTitle
	title.PrintMid(defCol + 3, sTitle);

	// タイトル印字(右詰め) SPAN
	sPrTitle.Format("Span = %f hours", (double)diff / 3600000.0);
	title.PrintRight(defCol + 3, sPrTitle);

	if (m_pGraphData->m_nDataType == DATA_TYPE_MMA) {
		// 4行目
		// タイトル印字(左詰め) Cutoff
		sPrTitle.Format("Cutoff = %d Hz", m_pGraphData->m_nCutoff);
		title.PrintLeft(defCol + 4, sPrTitle);

		// 5行目
		// タイトル印字(左詰め) Gain
		sPrTitle.Format("Gain = %d", m_pGraphData->m_nGain);
		title.PrintLeft(defCol + 5, sPrTitle);
	}

	// タイトル印字(センター) Start End
	sPrTitle.Format("Start GMT %s - End GMT %s", Begin.GetStrAsctime(), End.GetStrAsctime());
	title.PrintMid(defCol + 5, sPrTitle);

	// センサ軸情報出力
	CSize sizeChar = dc.GetOutputTextExtent("0");
	sPrTitle.Format("+X：%s  +Y：%s  +Z：%s", (CString)m_pGraphBar->m_cEditDirX, (CString)m_pGraphBar->m_cEditDirY, (CString)m_pGraphBar->m_cEditDirZ);
	title.PrintDir(rectView.Width() - dc.GetOutputTextExtent(sPrTitle).cx, 67, sPrTitle);

	// タイトル印字終了
	title.EndTitle();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::GetMinFreq
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸のスケールの最小値を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		double	スケールの最小値
//*****************************************************************************************************
double CFftSpectrogramDlg::GetMinFreq()
{
	// 対数表示で0Hzの場合は最小範囲を返す
	if (m_bScaleFreqLog && m_fScaleFreqMin <= 0)
		return pow(10, ceil(log10(m_fSampleRate / m_nFftSize)));
	else
		return m_fScaleFreqMin;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::GetMinLevel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸のスケールの最小値を取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		double	スケールの最小値
//*****************************************************************************************************
double CFftSpectrogramDlg::GetMinLevel()
{
	// 対数表示で0レベルの場合は最小範囲を返す
	if (m_bScaleLevelLog && m_fScaleLevelMin <= 0)
		return MIN_LEVEL;
	else
		return m_fScaleLevelMin;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::SetCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル位置をセットする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fCursorX		[I] 0～1に正規化されたマウスカーソルのX座標
//		double		fCursorY		[I] 0～1に正規化されたマウスカーソルのY座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::SetCursor(double fCursorX, double fCursorY)
{
	if (fCursorX == 0 && fCursorY == 0) {
		m_fCursorTime = 0;
		m_fCursorFreq = 0;
	} else {
		// カーソルの位置から時間を計算
		m_fCursorTime = m_fSelectBegin + m_fTimeOffset + (m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom * fCursorX;

		// カーソルの位置から周波数を計算
		double fFreqMin = GetMinFreq();
		double fFreqMax = m_fScaleFreqMax;
		if (m_bScaleFreqLog)
			m_fCursorFreq = exp(log(fFreqMin) + (log(fFreqMax) - log(fFreqMin)) * fCursorY);
		else
			m_fCursorFreq = fFreqMin + (fFreqMax - fFreqMin) * fCursorY;

		if (m_cStaticFftGraph.m_bAutoScroll)
			m_cEditScrollFreq = m_fCursorFreq;
	}

	DispCursor();
}


//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::DispCursorData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル位置データを表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::DispCursor()
{
	if (!m_bCalculation)
		return;

	if (m_fCursorTime == 0 && m_fCursorFreq == 0) {
		// カーソルがスケールの範囲外の時はデータを消去
		m_cEditCursorFreq.Blank();
		m_cEditCursorTime.Blank();
		m_cEditCursorG.Blank();

		// カーソルを消去
		m_cStaticFftGraph.DispCursor(0, 0);
	} else {
		int nTimeIndex = (int)((m_fCursorTime - m_fSelectBegin) / m_fTimeStep);
		if (nTimeIndex >= m_nTimeDataNum)
			nTimeIndex = m_nTimeDataNum - 1;

		int nFreqIndex = (int)(m_fCursorFreq / m_fSampleRate * m_nFftSize);
		if (nFreqIndex >= m_nSpectrumDataSize)
			nFreqIndex = m_nSpectrumDataSize - 1;

		// カーソル位置のデータを表示
		m_cEditCursorFreq = m_fCursorFreq;
		m_cEditCursorTime = CGeneral::FormatTime(m_fCursorTime, 1);
		if (m_fCursorTime <= m_fSelectBegin + m_pGraphData->m_fTimeDispEnd)
			m_cEditCursorG = CGeneral::FormatExp(m_pSpectrogramG[nTimeIndex * m_nSpectrumDataSize + nFreqIndex], 3);
		else
			m_cEditCursorG.Blank();

		// カーソルを表示
		double fFreqMin = GetMinFreq();
		double fFreqMax = m_fScaleFreqMax;
		double fPosX, fPosY;
		fPosX = (m_fCursorTime - m_fSelectBegin - m_fTimeOffset) / ((m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom);
		if (m_bScaleFreqLog)
			fPosY = (log(m_fCursorFreq) - log(fFreqMin)) / (log(fFreqMax) - log(fFreqMin));
		else
			fPosY = (m_fCursorFreq - fFreqMin) / (fFreqMax - fFreqMin);
		m_cStaticFftGraph.DispCursor(fPosX, fPosY);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnRetKey
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
LRESULT CFftSpectrogramDlg::OnRetKey(WPARAM wParam, LPARAM lParam)
{
	switch(wParam) {
	case IDC_EDIT_FREQ_MIN:
		OnRetKeyEditFreqMin();
		break;
	case IDC_EDIT_FREQ_MAX:
		OnRetKeyEditFreqMax();
		break;
	case IDC_EDIT_LEVEL_MIN:
		OnRetKeyEditLevelMin();
		break;
	case IDC_EDIT_LEVEL_MAX:
		OnRetKeyEditLevelMax();
		break;
	}
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnRetKeyEditFreqMin
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の最小値エディットボックス変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditFreqMin()
{
	// 縦軸の最小値
	m_fScaleFreqMin = m_cEditFreqMin;
	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnRetKeyEditFreqMax
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の最大値エディットボックス変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditFreqMax()
{
	// 縦軸の最小値
	m_fScaleFreqMax = m_cEditFreqMax;
	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedRadioFreqLinear
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の線形スケールラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioFreqLinear()
{
	if (m_bScaleFreqLog) {
		// 縦軸の線形／対数スケール設定
		m_bScaleFreqLog = FALSE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedRadioFreqLog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の対数スケールラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioFreqLog()
{
	if (!m_bScaleFreqLog) {
		// 縦軸の線形／対数スケール設定
		m_bScaleFreqLog = TRUE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnRetKeyEditLevelMin
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		PSD軸の最小値エディットボックス変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditLevelMin()
{
	// PSD軸の最小値
	m_fScaleLevelMin = m_cEditLevelMin;
	// グラフ表示
	DispGraph();
}



//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnRetKeyEditLevelMax
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の最大値エディットボックス変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditLevelMax()
{
	// PSD軸の最小値
	m_fScaleLevelMax = m_cEditLevelMax;
	// グラフ表示
	DispGraph();
}


//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedRadioLevelLinear
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の線形スケールラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioLevelLinear()
{
	if (m_bScaleLevelLog) {
		// PSD軸の線形／対数スケール設定
		m_bScaleLevelLog = FALSE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedRadioLevelLog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の対数スケールラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioLevelLog()
{
	if (!m_bScaleLevelLog) {
		// PSD軸の線形／対数スケール設定
		m_bScaleLevelLog = TRUE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::SetFilter
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フィルタの設定に基づいてフィルタを初期化する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bDispAlert			[I] TRUE:フィルタの設定が不正の場合にアラートを表示する　FALSE:表示しない
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE:正常　FALSE:フィルタの設定が不正
//*****************************************************************************************************
BOOL CFftSpectrogramDlg::SetFilter(BOOL bDispAlert)
{
	double fSampleFreq = m_pGraphData->m_fSampleRate / m_pGraphData->m_nDownRate;
	double fNyquistFreq = fSampleFreq / 2;
	EDFBand eFilterBand = (EDFBand)m_cComboFilterBand.GetItemData(m_cComboFilterBand.GetCurSel());
	EDFShape eFilterShape = (EDFShape)m_cComboFilterShape.GetItemData(m_cComboFilterShape.GetCurSel());
	int nFilterOrder = (int)m_cComboFilterOrder.GetItemData(m_cComboFilterOrder.GetCurSel());
	double fFilterFreq1 = m_cEditFilterFreq1;
	double fFilterFreq2 = m_cEditFilterFreq2;

	if ((int)eFilterBand != -1) {
		// 遮断周波数１の入力チェック
		if (fFilterFreq1 <= 0 || fFilterFreq1 >= fNyquistFreq) {
			if (bDispAlert) {
				CGeneral::Alert("遮断周波数は0より大きく%.0fより小さい値を入力してください。（0は不可）", fNyquistFreq);
				m_cEditFilterFreq1.SetFocusAndSel();
			}
			return FALSE;
		}

		// 遮断周波数２の入力チェック
		if (eFilterBand == EDF_BPF || eFilterBand == EDF_BSF) {
			if (fFilterFreq2 <= 0 || fFilterFreq2 >= fNyquistFreq) {
				if (bDispAlert) {
					CGeneral::Alert("遮断周波数は0より大きく%.0fより小さい値を入力してください。（0は不可）", fNyquistFreq);
					m_cEditFilterFreq2.SetFocusAndSel();
				}
				return FALSE;
			}
		}
	}

	if ((int)eFilterBand != -1) {
		// フィルタ初期化
		m_cFilter.InitIIR(eFilterShape, eFilterBand, nFilterOrder, fSampleFreq, fFilterFreq1, fFilterFreq2, 0.5);

		m_bFilter = TRUE;
	} else {
		m_cButtonFilterSet.EnableWindow(FALSE);

		m_bFilter = FALSE;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonFilterSet
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
void CFftSpectrogramDlg::OnBnClickedButtonFilterSet()
{
	// フィルタ設定
	if (SetFilter(TRUE)) {
		// 再計算＆表示
		CalcAndDispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnCbnSelchangeComboFilterBand
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
void CFftSpectrogramDlg::OnCbnSelchangeComboFilterBand()
{
	EDFBand eFilterBand = (EDFBand)m_cComboFilterBand.GetItemData((int)m_cComboFilterBand.GetCurSel());

	// フィルタの各コントロールの有効／無効を設定する
	switch (eFilterBand) {
	case EDF_LPF:
	case EDF_HPF:
		m_cComboFilterShape.EnableWindow(TRUE);
		m_cComboFilterOrder.EnableWindow(TRUE);
		m_cEditFilterFreq1.EnableWindow(TRUE);
		m_cEditFilterFreq2.EnableWindow(FALSE);
		m_cButtonFilterSet.EnableWindow(TRUE);
		break;
	case EDF_BPF:
	case EDF_BSF:
		m_cComboFilterShape.EnableWindow(TRUE);
		m_cComboFilterOrder.EnableWindow(TRUE);
		m_cEditFilterFreq1.EnableWindow(TRUE);
		m_cEditFilterFreq2.EnableWindow(TRUE);
		m_cButtonFilterSet.EnableWindow(TRUE);
		break;
	default:
		m_cComboFilterShape.EnableWindow(FALSE);
		m_cComboFilterOrder.EnableWindow(FALSE);
		m_cEditFilterFreq1.EnableWindow(FALSE);
		m_cEditFilterFreq2.EnableWindow(FALSE);
		m_cButtonFilterSet.EnableWindow(m_bFilter);
		break;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonCsv
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSV出力処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonCsv()
{
	long period = (long)(m_fSelectEnd - m_fSelectBegin);

	CGraphDigitalDataRowCol dlg(this);
	dlg.SetDataInfo(m_pSpectrogramG
		, m_nTimeDataNum
		, m_nSpectrumDataSize
		, "スペクトログラム数値データ表示"
		, m_StartTime
		, m_fTimeStep * 1000
		, m_fSampleRate / m_nFftSize
		, period);
	dlg.DoModal();

	return;
}


//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonTimeSet
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CSV出力処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeSet()
{
	double	sv_Begin, sv_End;
	// 時間範囲設定ダイアログ表示
	CTimeRangeDlg dlg(this);
// 2009/07/03 Version 3.02 modified by y.G ->
//	dlg.m_dStarttime.SetTime(m_pGraphData->m_dStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (long)((m_pGraphData->m_fSelectBegin + 0.0001) * 1000));
//	dlg.m_nDataSec = (int)(m_pGraphData->m_fSelectEnd - m_pGraphData->m_fSelectBegin + 0.5);
//	dlg.m_dDspFrom.SetTime(m_pGraphData->m_dStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (long)(m_fSelectBegin * 1000));
//	dlg.m_dDspTo.SetTime(m_pGraphData->m_dStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (long)(m_fSelectEnd * 1000));
//	dlg.m_DspFlag = TRUE;

	dlg.m_dStarttime.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (long)((m_fDefaultSelectBegin + 0.0001) * 1000));
	dlg.m_nDataSec = (int)(m_fDefaultSelectEnd - m_fDefaultSelectBegin + 0.5);
	dlg.m_dDspFrom.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (long)(m_fSelectBegin * 1000));
	dlg.m_dDspTo.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (long)(m_fSelectEnd * 1000));
	dlg.m_bDspFlag = TRUE;
// <- 2009/07/03 Version 3.02 modified by y.G

	if (dlg.DoModal() == IDCANCEL) {
		return;
	}

	sv_Begin = m_fSelectBegin;
	sv_End = m_fSelectEnd;

// 2009/07/06 Version 3.02 modified by m.hama ->
//	m_fSelectBegin = dlg.m_nDispBegin;
//	m_fSelectEnd = dlg.m_nDispEnd;

	CTime oTime1(m_pGraphData->m_dStarttime.m_YY, m_pGraphData->m_dStarttime.m_MM, m_pGraphData->m_dStarttime.m_DD, 0, 0, 0);
	CTime oTime2(dlg.m_dInStarttime.m_YY, dlg.m_dInStarttime.m_MM, dlg.m_dInStarttime.m_DD, dlg.m_dInStarttime.m_hh, dlg.m_dInStarttime.m_mm, dlg.m_dInStarttime.m_ss);
	CTimeSpan oTimeSpan = oTime2 - oTime1;
	m_fSelectBegin = (int)oTimeSpan.GetTotalSeconds();
	m_fSelectEnd = m_fSelectBegin + ((dlg.m_dInEndtime - dlg.m_dInStarttime) / 1000);
// <- 2009/07/06 Version 3.02 modified by m.hama

	if (! IsEnableCalc()) {
		m_fSelectBegin = sv_Begin;
		m_fSelectEnd = sv_End;
		return;
	}

	CalcAndDispGraph();
	m_StartTime = dlg.m_dInStarttime;
	m_cEditDispBegin2 = dlg.m_dInStarttime.GetStrYMDHMS();
	m_cEditDispEnd2 = dlg.m_dInEndtime.GetStrYMDHMS();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonInitialize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		初期化ボタン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonInitialize()
{
	if (MessageBox("このウィンドウの表示を初期状態に戻しますか？", NULL, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// 横軸の範囲を戻す
	m_fSelectBegin = m_fDefaultSelectBegin;
	m_fSelectEnd = m_fDefaultSelectEnd;

	// 各コントロール初期化
	InitializeControls();

	// フィルタ設定初期化
	m_cComboFilterBand.SetCurSel(0);
	OnCbnSelchangeComboFilterBand();
	SetFilter(FALSE);

	// 再計算＆表示
	CalcAndDispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CFftSpectrogramDlg::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CFftBaseDlg::PreTranslateMessage(pMsg);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnEnChangeEditTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ズーム倍率変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnEnChangeEditTimeZoom()
{
	// ズーム値設定
	m_fTimeZoom = m_cEditTimeZoom;
	if (m_fTimeZoom < 1.0)
		m_fTimeZoom = 1.0;

	// グラフ表示
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonTimeZoomAll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間軸の「全体表示」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeZoomAll()
{
	// 
	m_fTimeOffset = 0;
	m_fTimeZoom = 1.0;

	// グラフ表示
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonTimeZoomIn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間軸の「拡大」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeZoomIn()
{
	m_fTimeZoom *= 2;

	// グラフ表示
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedButtonTimeZoomOut
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間軸の「縮小」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeZoomOut()
{
	m_fTimeZoom /= 2;
	if (m_fTimeZoom < 1.0)
		m_fTimeZoom = 1.0;

	// グラフ表示
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::TimeScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間軸スクロール処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double	fPos			[I] スクロール位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::TimeScroll(double fPos)
{
	m_fTimeOffset = (m_fTimeEnd - m_fSelectBegin) * fPos;

	// グラフ表示
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::SetTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間軸ズーム設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::SetTimeZoom()
{
	// オフセット補正
	double fTimeEnd = m_fSelectBegin + m_fTimeOffset + (m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom;
	if (fTimeEnd > m_fTimeEnd)
		m_fTimeOffset = m_fTimeEnd - (m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom - m_fSelectBegin;

	// ズーム表示
	m_cEditTimeZoom = m_fTimeZoom;

	// スクロールバー表示
	m_cStaticFftGraph.SetTimeScrollBar(m_fTimeOffset / (m_fTimeEnd - m_fSelectBegin), m_fTimeZoom);

	// ボタン有効設定
	m_cButtonTimeZoomAll.EnableWindow(m_fTimeZoom != 1);
	m_cButtonTimeZoomOut.EnableWindow(m_fTimeZoom != 1);

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnBnClickedCheckScrollStart
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
void CFftSpectrogramDlg::OnBnClickedCheckScrollStart()
{
	if (!m_cCheckScrollStart) {
		double fScrollFreq = m_cEditScrollFreq;
		if (fScrollFreq == 0) {
			CGeneral::Alert("周波数を設定してください。");
			m_cEditScrollFreq.SetFocus();
			return;
		}

		// ボタンの表示を「停止」に設定
		m_cCheckScrollStart = "停止";
		m_cCheckScrollStart.SetCheck(1);

		// 自動スクロールON
		m_cStaticFftGraph.m_bAutoScroll = TRUE;

		// カーソル位置リセット
		m_fCursorTime = m_fSelectBegin;
		m_fTimeOffset = 0;

		// 現tick取得
		m_dwAutoScrollTick = ::GetTickCount();

		// タイマー設定
		SetTimer(IDT_AUTO_SCROLL, TIMER_AUTO_SCROLL, NULL);
	} else {
		// ボタンの表示を「開始」に設定
		m_cCheckScrollStart = "開始";
		m_cCheckScrollStart.SetCheck(0);

		// 自動スクロールOFF
		m_cStaticFftGraph.m_bAutoScroll = FALSE;

		// タイマー解除
		KillTimer(IDT_AUTO_SCROLL);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::OnTimer
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
void CFftSpectrogramDlg::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_AUTO_SCROLL:
		// 自動スクロール
		AutoScroll();
		break;
	}

	CFftBaseDlg::OnTimer(nIDEvent);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::AutoScroll
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
void CFftSpectrogramDlg::AutoScroll()
{
	// スクロール速度取得
	double fScrollSpeed = m_cEditScrollSpeed;

	// 時間ステップ計算
	DWORD dwCurrentTick = ::GetTickCount();
	double fTimeStep = fScrollSpeed * (dwCurrentTick - m_dwAutoScrollTick) / 1000;
	m_dwAutoScrollTick = dwCurrentTick;

	// 表示開始位置と時間カーソル位置を更新
	m_fTimeOffset += fTimeStep;
	m_fCursorTime += fTimeStep;
	m_fCursorFreq = m_cEditScrollFreq;

	// 表示範囲チェック
	double fTimeSpan = m_fTimeEnd - m_fSelectBegin;
	if (m_fSelectBegin + m_fTimeOffset + fTimeSpan / m_fTimeZoom > m_fTimeEnd)
		m_fTimeOffset = fTimeSpan - fTimeSpan / m_fTimeZoom;

	// データの最大時間に達したら停止する
	if (m_fCursorTime >= m_fSelectEnd) {
		m_fCursorTime = 0;
		m_fCursorFreq = 0;
		OnBnClickedCheckScrollStart();
	}

	// カーソル表示
	DispCursor();

	// グラフ表示を更新
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::InitRealtime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		準リアルタイム表示初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::InitRealtime()
{
	// FFTサイズを設定
	m_nFftSize = (int)pow(2.0, ceil(log(m_pGraphData->m_fSampleRate) / log(2.0)));
	m_nSpectrumDataSize = m_nFftSize / 2;
	m_fSampleRate = m_pGraphData->m_fSampleRate;

	// 時間のパラメータを設定
	m_nTimeDataNum = gRealtimeRange;
	m_fTimeStep = 1.0;
	m_fTimeEnd = m_fSelectBegin + m_nTimeDataNum * m_fTimeStep;

	// バッファ確保
	AllocBuf();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftSpectrogramDlg::CalcRealtime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		準リアルタイム表示計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcRealtime()
{
	// PSDバッファ確保
	double *pSpectrumX = new double[m_nSpectrumDataSize];
	double *pSpectrumY = new double[m_nSpectrumDataSize];
	double *pSpectrumZ = new double[m_nSpectrumDataSize];

	while (m_fCalcTime < m_pGraphData->m_fTimeDispEnd) {
		// 計算済みデータを移動（スクロール）
		int nSpectrogramPos = min((int)m_fCalcTime, m_nTimeDataNum);
		int nSpectrograOffset = nSpectrogramPos * m_nSpectrumDataSize;
		if (nSpectrogramPos == m_nTimeDataNum) {
			memmove(m_pSpectrogramG, m_pSpectrogramG + m_nSpectrumDataSize, m_nSpectrumDataSize * (m_nTimeDataNum - 1) * sizeof(double));
			nSpectrograOffset -= m_nSpectrumDataSize;
			if (m_fCursorTime != 0)
				m_fCursorTime += 1.0;
		}

		// PSD計算
		int nDataOffset = (int)(m_fCalcTime * m_fSampleRate);
		CalcSpectrum(m_pGraphData->m_pCurrentDataX + nDataOffset, pSpectrumX);
		CalcSpectrum(m_pGraphData->m_pCurrentDataY + nDataOffset, pSpectrumY);
		CalcSpectrum(m_pGraphData->m_pCurrentDataZ + nDataOffset, pSpectrumZ);

		// 合力計算
		double *pSpectrogram = m_pSpectrogramG + nSpectrograOffset;
		for (int i = 0; i < m_nSpectrumDataSize; i++)
			*pSpectrogram++ = pSpectrumX[i] + pSpectrumY[i] + pSpectrumZ[i];

		m_fCalcTime += 1;
	}

	// PSDバッファ解放
	delete [] pSpectrumX;
	delete [] pSpectrumY;
	delete [] pSpectrumZ;

	// 計算済みフラグセット
	m_bCalculation = TRUE;
}
