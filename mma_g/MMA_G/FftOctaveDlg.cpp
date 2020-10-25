//*****************************************************************************************************
//  1. ファイル名
//		FftOctaveDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		1/3オクターブ解析グラフのダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		同時に複数のグラフを表示できるようにするため、モードレスダイアログで開く。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "FftOctaveDlg.h"
#include "FftOctaveDraw.h"
#include "Define.h"
#include "General.h"
#include "Globaldata.h"
#include "GraphDigitalData.h"
#include "GraphTitle.h"
#include "TitlePrint.h"
#include <math.h>

// スケールの表示範囲
#define MIN_FREQ	1e-4		// 周波数の下限のデフォルト値
#define MIN_LEVEL	1e-10		// 加速度の下限のデフォルト値
#define MAX_LEVEL	1e-2		// 加速度の上限のデフォルト値
#define MAX_LEVEL_MME	1e-1	// 加速度の上限のデフォルト値（MMEの場合）

// CFftOctaveDlg ダイアログ

IMPLEMENT_DYNAMIC(CFftOctaveDlg, CFftBaseDlg)

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::CFftOctaveDlg
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
CFftOctaveDlg::CFftOctaveDlg(CWnd *pParent)
	: CFftBaseDlg(CFftOctaveDlg::IDD, pParent)
	, m_sTitle(_T(""))
{
	// メンバ変数を初期化
	m_pOctaveX = NULL;
	m_pOctaveY = NULL;
	m_pOctaveZ = NULL;
	m_pOctaveG = NULL;
	m_nDataSize = 0;
	m_nFftSize = 0;
	m_nSpectrumDataSize = 0;
	m_bCalculation = FALSE;
	m_bFilter = FALSE;

	// 1/3オクターブの周波数を計算（0.0001Hzからバンドの境界と中心周波数）
	for (int i = 0; i <= OCTAVE_BAND_NUM * 2; i++)
		m_fOctaveBandFreq[i] = pow(10.0, i / 20.0 - 4.05);
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::~CFftOctaveDlg
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
CFftOctaveDlg::~CFftOctaveDlg()
{
	// バッファを解放
	FreeBuf();
}

void CFftOctaveDlg::DoDataExchange(CDataExchange* pDX)
{
	CFftBaseDlg::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_COMBO_WINDOW_FUNCTION, m_cComboWindowFunction);
	DDX_Control(pDX, IDC_STATIC_FFT_GRAPH, m_cStaticFftGraph);
	DDX_Control(pDX, IDC_EDIT_DISP_BEGIN, m_cEditDispBegin);
	DDX_Control(pDX, IDC_EDIT_DISP_END, m_cEditDispEnd);
	DDX_Control(pDX, IDC_CHECK_DISP_X, m_cCheckDispX);
	DDX_Control(pDX, IDC_CHECK_DISP_Y, m_cCheckDispY);
	DDX_Control(pDX, IDC_CHECK_DISP_Z, m_cCheckDispZ);
	DDX_Control(pDX, IDC_CHECK_DISP_G, m_cCheckDispG);
	DDX_Control(pDX, IDC_STATIC_LINE_X, m_cStaticLineX);
	DDX_Control(pDX, IDC_STATIC_LINE_Y, m_cStaticLineY);
	DDX_Control(pDX, IDC_STATIC_LINE_Z, m_cStaticLineZ);
	DDX_Control(pDX, IDC_STATIC_LINE_G, m_cStaticLineG);
	DDX_Control(pDX, IDC_CHECK_LIMIT_LINE_1, m_cCheckLimitLine1);
	DDX_Control(pDX, IDC_CHECK_LIMIT_LINE_2, m_cCheckLimitLine2);
// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
	DDX_Control(pDX, IDC_EDIT_INFO_TAA_ID, m_cEditInfoTaaId);
	DDX_Control(pDX, IDC_EDIT_INFO_ACQ_START_TIME, m_cEditInfoAcqStartTime);
	DDX_Control(pDX, IDC_EDIT_INFO_SAMPLING_RATE, m_cEditInfoSamplingRate);
	DDX_Control(pDX, IDC_EDIT_INFO_CUT_OFF_FREQ, m_cEditInfoCutOffFreq);
	DDX_Control(pDX, IDC_EDIT_INFO_GAIN, m_cEditInfoGain);
	DDX_Control(pDX, IDC_EDIT_INFO_POSITION, m_cEditInfoPosition);
// 077FD050-GYM961-0_003 2007/9/7 appended by yG <-
	DDX_Control(pDX, IDC_EDIT_CURSOR_FREQ, m_cEditCursorFreq);
	DDX_Control(pDX, IDC_EDIT_CURSOR_X, m_cEditCursorX);
	DDX_Control(pDX, IDC_EDIT_CURSOR_Y, m_cEditCursorY);
	DDX_Control(pDX, IDC_EDIT_CURSOR_Z, m_cEditCursorZ);
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
	DDX_Control(pDX, IDC_STATIC_INFO_TAA_ID, m_cStaticInfoTaa);
	DDX_Control(pDX, IDC_STATIC_INFO_CUT_OFF_FREQ, m_cStaticInfoCutOffFreq);
	DDX_Control(pDX, IDC_STATIC_INFO_GAIN, m_cStaticInfoGain);
	DDX_Control(pDX, IDC_STATIC_INFO_POSITION, m_cStaticInfoPosition);
}


BEGIN_MESSAGE_MAP(CFftOctaveDlg, CFftBaseDlg)
	ON_BN_CLICKED(IDC_BUTTON_CLOSE, &CFftOctaveDlg::OnBnClickedButtonClose)
	ON_CBN_SELCHANGE(IDC_COMBO_WINDOW_FUNCTION, &CFftOctaveDlg::OnCbnSelchangeComboWindowFunction)
	ON_WM_SIZE()
	ON_BN_CLICKED(IDC_BUTTON_PRINT, &CFftOctaveDlg::OnBnClickedButtonPrint)
	ON_BN_CLICKED(IDC_CHECK_LIMIT_LINE_1, &CFftOctaveDlg::OnBnClickedCheckLimitLine)
	ON_BN_CLICKED(IDC_CHECK_LIMIT_LINE_2, &CFftOctaveDlg::OnBnClickedCheckLimitLine)
	ON_BN_CLICKED(IDC_CHECK_DISP_X, &CFftOctaveDlg::OnBnClickedCheckDisp)
	ON_BN_CLICKED(IDC_CHECK_DISP_Y, &CFftOctaveDlg::OnBnClickedCheckDisp)
	ON_BN_CLICKED(IDC_CHECK_DISP_Z, &CFftOctaveDlg::OnBnClickedCheckDisp)
	ON_BN_CLICKED(IDC_CHECK_DISP_G, &CFftOctaveDlg::OnBnClickedCheckDisp)
	ON_BN_CLICKED(IDC_RADIO_FREQ_LINEAR, &CFftOctaveDlg::OnBnClickedRadioFreqLinear)
	ON_BN_CLICKED(IDC_RADIO_FREQ_LOG, &CFftOctaveDlg::OnBnClickedRadioFreqLog)
	ON_BN_CLICKED(IDC_RADIO_LEVEL_LINEAR, &CFftOctaveDlg::OnBnClickedRadioLevelLinear)
	ON_BN_CLICKED(IDC_RADIO_LEVEL_LOG, &CFftOctaveDlg::OnBnClickedRadioLevelLog)
	ON_BN_CLICKED(IDC_BUTTON_FILTER_SET, &CFftOctaveDlg::OnBnClickedButtonFilterSet)
	ON_CBN_SELCHANGE(IDC_COMBO_FILTER_BAND, &CFftOctaveDlg::OnCbnSelchangeComboFilterBand)
	ON_BN_CLICKED(IDC_BUTTON_CSV, &CFftOctaveDlg::OnBnClickedButtonCsv)
	ON_MESSAGE(WM_KEY_RET_KEY, OnRetKey)
	ON_BN_CLICKED(IDC_BUTTON_INITIALIZE, &CFftOctaveDlg::OnBnClickedButtonInitialize)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_FILTER_SET, "設定したフィルタをデータに適用します。"},
	{IDC_BUTTON_CSV, "1/3オクターブバンド数値データ表示ダイアログを開きます。"},
	{IDC_BUTTON_INITIALIZE, "このウィンドウの表示内容を初期状態に戻します。"},
	{IDC_BUTTON_PRINT, "1/3オクターブバンド解析グラフを印刷します。"},
	{IDC_BUTTON_CLOSE, "このウィンドウを閉じます。"},
	{IDC_COMBO_FILTER_BAND, "フィルタの種類を、無し（全域通過）、LPF（低域通過）、HPF（高域通過）、BPF（帯域通過）、BSF（帯域阻止）から選択します。"},
	{IDC_COMBO_FILTER_ORDER, "フィルタの次数を設定します。次数が大きいほど急峻なフィルタとなります。"},
	{IDC_COMBO_FILTER_SHAPE, "フィルタの特性を、Butterworth（バターワース特性）、Chebyshev（チェビシェフ特性）、Bessel（ベッセル特性）から選択します。"},
	{IDC_COMBO_WINDOW_FUNCTION, "FFT計算時に適用する窓関数を選択します。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::CreateGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフウィンドウ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphData		*pGraphData		[I] グラフデータクラスへのポインタ
//		CGraphBar		*pGraphBar		[I] グラフバーへのポインタ
//		HWND			hWndParent		[I] 親ウィンドウハンドル
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::CreateGraph(CGraphData *pGraphData, CGraphBar *pGraphBar, HWND hWndParent)
{
	// グラフデータへのポインタを保存
	m_pGraphData = pGraphData;
	m_pGraphBar = pGraphBar;
	m_hWndParent = hWndParent;

// 2009/06/11 Version 3.01 appended by y.G ->
	// 選択範囲をセーブ
	m_fSelectBegin = m_pGraphData->m_fSelectBegin;
	m_fSelectEnd = m_pGraphData->m_fSelectEnd;
// <- 2009/06/11 Version 3.01 appended by y.G

	if (!IsEnableCalc()) {
		delete this;
		return;
	}

	// モードレスでダイアログを表示
	DoModeless();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnInitDialog
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
BOOL CFftOctaveDlg::OnInitDialog()
{
	CFftBaseDlg::OnInitDialog();

	SetIcon(AfxGetApp()->LoadIcon(IDI_OCTAVE), TRUE);

	// ステータスバー作成
	CreateStatusBar();

	// 窓関数プルダウン設定
	m_cFft.SetWindowFuncList(m_cComboWindowFunction, m_nWindowFunc);

	// コントロールの位置を調整
	SetAllCtlPosition(0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_FFT_GRAPH, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_PRINT, 1, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_CLOSE, 1, 1, 1, 1);

// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
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

	// グラフの線の色を設定
	m_cStaticLineX.SetBackColor(COLOR_GRAPH_DATA_X);
	m_cStaticLineY.SetBackColor(COLOR_GRAPH_DATA_Y);
	m_cStaticLineZ.SetBackColor(COLOR_GRAPH_DATA_Z);
	m_cStaticLineG.SetBackColor(COLOR_GRAPH_DATA_G);

	// フィルタの設定
	CGeneral::SetFilterBandList(m_cComboFilterBand, m_pGraphBar->m_cComboFilterBand.GetCurSel());
	CGeneral::SetFilterOrderList(m_cComboFilterOrder, m_pGraphBar->m_cComboFilterOrder.GetCurSel());
	CGeneral::SetFilterTypeList(m_cComboFilterShape, m_pGraphBar->m_cComboFilterShape.GetCurSel());
	m_cEditFilterFreq1 = (CString)m_pGraphBar->m_cEditFilterFreq1;
	m_cEditFilterFreq2 = (CString)m_pGraphBar->m_cEditFilterFreq2;
	OnCbnSelchangeComboFilterBand();
	SetFilter(FALSE);

	// リミットライン作成
	MakeLimitLine();

	// MMEのリミットライン
	if (m_pGraphData->m_nDataType == DATA_TYPE_MME) {
		m_cCheckLimitLine1.SetWindowText("JEM曝露部のEFU/PIU取付面における微小重力環境");
		m_cCheckLimitLine2.ShowWindow(SW_HIDE);
	}

	// 各コントロール初期化
	InitializeControls();

	// 計算に時間がかかるのでここでウィンドウ表示
	ShowWindow(SW_SHOW);

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
//		CFftOctaveDlg::InitializeControls
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
void CFftOctaveDlg::InitializeControls()
{
	// リミットラインの初期設定
	m_cCheckLimitLine1 = TRUE;
	m_cCheckLimitLine2 = FALSE;

	// 表示データチェックボックスをセット
	m_cCheckDispX = TRUE;
	m_cCheckDispY = TRUE;
	m_cCheckDispZ = TRUE;
	m_cCheckDispG = TRUE;

	// スケール範囲の初期値をセット
	m_fScaleFreqMin = 0;
	m_fScaleFreqMax = m_pGraphData->m_fSampleRate / 2;
	m_bScaleFreqLog = TRUE;
	m_fScaleLevelMin = 0;
	m_fScaleLevelMax = m_pGraphData->m_nDataType == DATA_TYPE_MME ? MAX_LEVEL_MME : MAX_LEVEL;
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
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedButtonClose
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
void CFftOctaveDlg::OnBnClickedButtonClose()
{
	// ウィンドウを閉じる
	OnCancel();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::CalcAndDispGraph
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
void CFftOctaveDlg::CalcAndDispGraph()
{
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
		, (int)(m_pGraphData->m_fSelectBegin * 1000));
	to.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)((m_pGraphData->m_fSelectEnd+0.001) * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G
	m_cEditDispBegin = from.GetStr();
	m_cEditDispEnd = to.GetStr();

	// ステータスバーに"計算中"を表示
	SetStatusBar("計算中");

	// グラフウィンドウを非表示
	m_cStaticFftGraph.HideGraph();
	UpdateWindow();

	// データ計算処理
	CalcData();

	// グラフ表示
	DispGraph();

	// ステータスバーに"レディ"を表示
	SetStatusBar("レディ");
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::IsEnableCalc
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
bool CFftOctaveDlg::IsEnableCalc()
{
	// 選択範囲のデータのインデックスを取得
	int nBeginIndex = (int)((m_pGraphData->m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_pGraphData->m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	if (nEndIndex > m_pGraphData->m_nDataSize)
		nEndIndex = m_pGraphData->m_nDataSize;

	// バイナリデータサイズ、FFTサイズ、スペクトルデータサイズを設定
	int nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;
	int	nFftSize = (int)pow(2.0, ceil(log((double)nDataSize) / log(2.0)));

	// 選択した範囲のデータサイズをチェック
	if (nFftSize < 8) {
		CGeneral::Alert("選択した範囲が小さすぎるため、グラフを表示できません。");
		return false;
	}
	
	return true;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::CalcData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データ計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::CalcData()
{
	// 選択範囲のデータのインデックスを取得
	int nBeginIndex = (int)((m_pGraphData->m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_pGraphData->m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	if (nEndIndex > m_pGraphData->m_nDataSize)
		nEndIndex = m_pGraphData->m_nDataSize;

	// バイナリデータサイズ、FFTサイズ、スペクトルデータサイズを設定
	m_nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;
	m_nFftSize = (int)pow(2.0, ceil(log((double)m_nDataSize) / log(2.0)));
	m_nSpectrumDataSize = m_nFftSize / 2;
	m_fSampleRate = m_pGraphData->m_fSampleRate / m_pGraphData->m_nDownRate;

	// バッファ確保
	AllocBuf();

	// PSDバッファ確保
	double *pSpectrumX = new double[m_nSpectrumDataSize];
	double *pSpectrumY = new double[m_nSpectrumDataSize];
	double *pSpectrumZ = new double[m_nSpectrumDataSize];
	double *pSpectrumG = new double[m_nSpectrumDataSize];

	// PSD計算
	CalcSpectrum(m_pGraphData->m_pCurrentDataX + nBeginIndex, pSpectrumX);
	CalcSpectrum(m_pGraphData->m_pCurrentDataY + nBeginIndex, pSpectrumY);
	CalcSpectrum(m_pGraphData->m_pCurrentDataZ + nBeginIndex, pSpectrumZ);

	// 合力計算
	for (int i = 0; i < m_nSpectrumDataSize; i++)
		pSpectrumG[i] = pSpectrumX[i] + pSpectrumY[i] + pSpectrumZ[i];

	// 1/3オクターブバンド計算
	CalcOctaveBand(pSpectrumX, m_pOctaveX);
	CalcOctaveBand(pSpectrumY, m_pOctaveY);
	CalcOctaveBand(pSpectrumZ, m_pOctaveZ);
	CalcOctaveBand(pSpectrumG, m_pOctaveG);

	// PSDバッファ解放
	delete [] pSpectrumX;
	delete [] pSpectrumY;
	delete [] pSpectrumZ;
	delete [] pSpectrumG;

	// 計算済みフラグセット
	m_bCalculation = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::AllocBuf
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
void CFftOctaveDlg::AllocBuf()
{
	// バッファ解放
	FreeBuf();

	// オクターブバンドバッファ確保
	m_pOctaveX = new double[OCTAVE_BAND_NUM];
	m_pOctaveY = new double[OCTAVE_BAND_NUM];
	m_pOctaveZ = new double[OCTAVE_BAND_NUM];
	m_pOctaveG = new double[OCTAVE_BAND_NUM];
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::FreeBuf
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
void CFftOctaveDlg::FreeBuf()
{
	if (m_pOctaveX != NULL) {
		delete [] m_pOctaveX;
		m_pOctaveX = NULL;
	}

	if (m_pOctaveY != NULL) {
		delete [] m_pOctaveY;
		m_pOctaveY = NULL;
	}

	if (m_pOctaveZ != NULL) {
		delete [] m_pOctaveZ;
		m_pOctaveZ = NULL;
	}

	if (m_pOctaveG != NULL) {
		delete [] m_pOctaveG;
		m_pOctaveG = NULL;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::CalcSpectrum
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		PSD計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		*pBinaryData		[I] バイナリデータへのポインタ
//		double		*pSpectrum			[O] PSDバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::CalcSpectrum(const double *pBinaryData, double *pSpectrum)
{
	int i;
	double fAve;

	// FFTバッファ確保
	double *pFftBuf = new double[m_nFftSize];

	// 平均値取得
	if (m_pGraphBar->m_cCheckZeroHosei) {
		double fSum = 0;
		for (i = 0; i < m_nDataSize; i++)
			fSum += pBinaryData[i * m_pGraphData->m_nDownRate];
		fAve = fSum / m_nDataSize;
	} else
		fAve = 0;

	// FFTバッファにデータコピー
	for (i = 0; i < m_nDataSize; i++)
		pFftBuf[i] = (pBinaryData[i * m_pGraphData->m_nDownRate] - fAve) * 1e-6;

	// FFTバッファの残りをクリア
	memset(pFftBuf + m_nDataSize, 0, (m_nFftSize - m_nDataSize) * sizeof(double));

	// フィルタ処理
	if (m_bFilter)
		m_cFilter.ExecIIR(pFftBuf, pFftBuf, m_nDataSize);

	// 窓関数
	m_cFft.WindowFunc(m_nWindowFunc, m_nDataSize, pFftBuf);

	// FFT実行
	m_cFft.FFT(m_nFftSize, pFftBuf);

	// パワースペクトル密度(PSD)計算
	double fPsd = 2 / (m_nDataSize * m_fSampleRate);
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
//		CFftOctaveDlg::CalcOctaveBand
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		1/3オクターブバンド計算処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		*pSpectrum			[I] PSDバッファへのポインタ
//		double		*pOctaveData		[O] 1/3オクターブバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::CalcOctaveBand(const double *pSpectrum, double *pOctaveData)
{
	int i;

	// 周波数分解能
	double fFreqRes = m_fSampleRate / m_nFftSize;

	// 1/3オクターブ計算バッファクリア
	memset(pOctaveData, 0, OCTAVE_BAND_NUM * sizeof(double));

	// 周波数帯域別にパワースペクトルを加算
	int nIndex = -1;
	for (i = 1; i < m_nSpectrumDataSize; i++) {
		while (i * fFreqRes >= m_fOctaveBandFreq[nIndex * 2 + 2])
			nIndex++;

		if (nIndex >= 0 && nIndex < OCTAVE_BAND_NUM)
			pOctaveData[nIndex] += pSpectrum[i] * fFreqRes;
	}

	// 平方根を計算
	for (i = 0; i < OCTAVE_BAND_NUM; i++)
		pOctaveData[i] = pOctaveData[i] ? sqrt(pOctaveData[i]) : 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::DispGraph
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
void CFftOctaveDlg::DispGraph()
{
	// グラフ表示
	if (m_bCalculation) {
		m_cStaticFftGraph.DispGraph(
				m_cCheckDispX ? m_pOctaveX : NULL,
				m_cCheckDispY ? m_pOctaveY : NULL,
				m_cCheckDispZ ? m_pOctaveZ : NULL,
				m_cCheckDispG ? m_pOctaveG : NULL,
				m_cCheckLimitLine1 ? m_fLimitLine1 : NULL,
				m_cCheckLimitLine2 ? m_fLimitLine2 : NULL,
				OCTAVE_BAND_NUM, m_fOctaveBandFreq,
				GetMinFreq(), m_fScaleFreqMax, m_bScaleFreqLog,
				GetMinLevel(), m_fScaleLevelMax, m_bScaleLevelLog);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnCbnSelchangeComboWindowFunction
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
void CFftOctaveDlg::OnCbnSelchangeComboWindowFunction()
{
	// 選択した窓関数を取得
	m_nWindowFunc = m_cComboWindowFunction.GetCurSel();

	// 再計算＆表示
	CalcAndDispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnSize
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
void CFftOctaveDlg::OnSize(UINT nType, int cx, int cy)
{
	CFftBaseDlg::OnSize(nType, cx, cy);

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedButtonPrint
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
void CFftOctaveDlg::OnBnClickedButtonPrint()
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
		docinfo.lpszDocName = "MMA-G 1/3OctaveBand Graph Doc";

		// 印刷開始
		dc.StartDoc(&docinfo);

		// ページ印刷開始
		dc.StartPage();

		// グラフ印刷
		CFftOctaveDraw cDrawGraph(&dc, 2);
		cDrawGraph.DrawGraph(dc, rectView,
				m_cCheckDispX ? m_pOctaveX : NULL,
				m_cCheckDispY ? m_pOctaveY : NULL,
				m_cCheckDispZ ? m_pOctaveZ : NULL,
				m_cCheckDispG ? m_pOctaveG : NULL,
				m_cCheckLimitLine1 ? m_fLimitLine1 : NULL,
				m_cCheckLimitLine2 ? m_fLimitLine2 : NULL,
				OCTAVE_BAND_NUM, m_fOctaveBandFreq,
				GetMinFreq(), m_fScaleFreqMax, m_bScaleFreqLog,
				GetMinLevel(), m_fScaleLevelMax, m_bScaleLevelLog);

		// タイトル等印刷
		PrintGraphInfo(dc, rectView);

		// 凡例印刷
		PrintSample(dc, rectView
				, &cDrawGraph.m_penGraphDataX
				, &cDrawGraph.m_penGraphDataY
				, &cDrawGraph.m_penGraphDataZ
				, &cDrawGraph.m_penGraphDataG);

		// ページ印刷終了
		dc.EndPage();

		// 印刷終了
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::PrintSample
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイトル等印刷処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDC			&dc				[I] デバイスコンテキスト
//		CRect		rectView		[I] 印刷範囲
//		CPen		*x				[I] X軸描画用のペン
//		CPen		*y				[I] Y軸描画用のペン
//		CPen		*z				[I] Z軸描画用のペン
//		CPen		*g				[I] 合力描画用のペン
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::PrintSample(CDC &dc, CRect rectView, CPen *x, CPen *y,CPen *z, CPen *g)
{
	CString	sPrTitle;
	int	defCol = 67;
	int	defX = 9;
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

	CPen *org = dc.SelectObject(x);

	if (m_cCheckDispX) {
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "：X-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	if (m_cCheckDispY) {
		dc.SelectObject(y);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "：Y-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	if (m_cCheckDispZ) {
		dc.SelectObject(z);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "：Z-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	if (m_cCheckDispG) {
		dc.SelectObject(g);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "：RMS";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	// センサ軸情報出力
	sPrTitle.Format("+X：%s  +Y：%s  +Z：%s", (CString)m_pGraphBar->m_cEditDirX, (CString)m_pGraphBar->m_cEditDirY, (CString)m_pGraphBar->m_cEditDirZ);
	title.PrintDir(rectView.Width() - dc.GetOutputTextExtent(sPrTitle).cx, defCol, sPrTitle);

	dc.SelectObject(org);

// update 2008/03/18 by hama　←

	// タイトル印字終了
	title.EndTitle();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::PrintGraphInfo
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
void CFftOctaveDlg::PrintGraphInfo(CDC &dc, CRect rectView)
{
	CString sTitle = "1/3OctaveBand";
	CString	sBegin, sEnd, sStart;
	CString	sPrTitle;
	long	diff;
	int	defCol = 1;

	CDataDateTime Begin, End;
// 2009/06/11 Version 3.01 modified by y.G ->
//	Begin.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_pGraphData->m_fSelectBegin * 1000));
//	End.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_pGraphData->m_fSelectEnd * 1000));

// 2009/07/03 Version 3.02 modified by y.G ->
//	Begin.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_fSelectBegin * 1000));
//	End.SetTime(m_pGraphData->m_dInStarttime.m_YY
//		, m_pGraphData->m_dInStarttime.m_MM
//		, m_pGraphData->m_dInStarttime.m_DD
//		, (int)(m_fSelectEnd * 1000));

	Begin.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)(m_fSelectBegin * 1000));
	End.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)(m_fSelectEnd * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G

// <- 2009/06/11 Version 3.01 modified by y.G
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

	// タイトル印字終了
	title.EndTitle();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::GetMinFreq
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
double CFftOctaveDlg::GetMinFreq()
{
	// 対数表示で0Hzの場合は最小範囲を返す
	if (m_bScaleFreqLog && m_fScaleFreqMin <= 0) {
		for (int i = 0; i < OCTAVE_BAND_NUM; i++) {
			if (m_pOctaveG[i] != 0)
				return m_fOctaveBandFreq[i * 2 + 1];
		}
		return MIN_FREQ;
	} else
		return m_fScaleFreqMin;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::GetMinLevel
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
double CFftOctaveDlg::GetMinLevel()
{
	// 対数表示で0レベルの場合は最小範囲を返す
	if (m_bScaleLevelLog && m_fScaleLevelMin <= 0)
		return MIN_LEVEL;
	else
		return m_fScaleLevelMin;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::DispCursorData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル位置データを表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fCursorX		[I] 0～1に正規化されたマウスカーソルのX座標
//		double		fCursorY		[I] 0～1に正規化されたマウスカーソルのY座標
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::DispCursorData(double fCursorX, double fCursorY)
{
	if (fCursorX == 0 && fCursorY == 0) {
		// カーソルがスケールの範囲外の時はデータを消去
		m_cEditCursorFreq.Blank();
		m_cEditCursorX.Blank();
		m_cEditCursorY.Blank();
		m_cEditCursorZ.Blank();
		m_cEditCursorG.Blank();

		// カーソルを消去
		m_cStaticFftGraph.DispCursor(0);
	} else {
		// カーソルの位置から周波数を計算
		double fFreq;
		double fMin = GetMinFreq();
		double fMax = m_fScaleFreqMax;
		if (m_bScaleFreqLog)
			fFreq = exp(log(fMin) + (log(fMax) - log(fMin)) * fCursorX);
		else
			fFreq = fMin + (fMax - fMin) * fCursorX;

		// 周波数からバンド位置を取得
		int i;
		for (i = 0; i < OCTAVE_BAND_NUM; i++) {
			if (fFreq < m_fOctaveBandFreq[i * 2 + 2] && m_pOctaveG[i] != 0)
				break;
		}

		// カーソル位置のデータを表示
		fFreq = m_fOctaveBandFreq[i * 2 + 1];
		m_cEditCursorFreq = fFreq;
		m_cEditCursorX = CGeneral::FormatExp(m_pOctaveX[i], 3);
		m_cEditCursorY = CGeneral::FormatExp(m_pOctaveY[i], 3);
		m_cEditCursorZ = CGeneral::FormatExp(m_pOctaveZ[i], 3);
		m_cEditCursorG = CGeneral::FormatExp(m_pOctaveG[i], 3);

		// カーソルを表示
		double fPosX;
		if (m_bScaleFreqLog)
			fPosX = (log(fFreq) - log(fMin)) / (log(fMax) - log(fMin));
		else
			fPosX = (fFreq - fMin) / (fMax - fMin);
		m_cStaticFftGraph.DispCursor(fPosX);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedCheckLimitLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リミットラインのチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::OnBnClickedCheckLimitLine()
{
	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedCheckDisp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		表示データのチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::OnBnClickedCheckDisp()
{
	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnRetKey
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
LRESULT CFftOctaveDlg::OnRetKey(WPARAM wParam, LPARAM lParam)
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
//		CFftOctaveDlg::OnRetKeyEditFreqMin
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の最小値エディットボックス変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::OnRetKeyEditFreqMin()
{
	// 横軸の最小値
	m_fScaleFreqMin = m_cEditFreqMin;

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnRetKeyEditFreqMax
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の最大値エディットボックス変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::OnRetKeyEditFreqMax()
{
	// 横軸の最小値
	m_fScaleFreqMax = m_cEditFreqMax;

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedRadioFreqLinear
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の線形スケールラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::OnBnClickedRadioFreqLinear()
{
	if (m_bScaleFreqLog) {
		// 横軸の線形／対数スケール設定
		m_bScaleFreqLog = FALSE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedRadioFreqLog
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
void CFftOctaveDlg::OnBnClickedRadioFreqLog()
{
	if (!m_bScaleFreqLog) {
		// 横軸の線形／対数スケール設定
		m_bScaleFreqLog = TRUE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnRetKeyEditLevelMin
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
void CFftOctaveDlg::OnRetKeyEditLevelMin()
{
	// 縦軸の最小値
	m_fScaleLevelMin = m_cEditLevelMin;

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnRetKeyEditLevelMax
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
void CFftOctaveDlg::OnRetKeyEditLevelMax()
{
	// 縦軸の最小値
	m_fScaleLevelMax = m_cEditLevelMax;

	// グラフ表示
	DispGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedRadioLevelLinear
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
void CFftOctaveDlg::OnBnClickedRadioLevelLinear()
{
	if (m_bScaleLevelLog) {
		// 縦軸の線形／対数スケール設定
		m_bScaleLevelLog = FALSE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedRadioLevelLog
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
void CFftOctaveDlg::OnBnClickedRadioLevelLog()
{
	if (!m_bScaleLevelLog) {
		// 縦軸の線形／対数スケール設定
		m_bScaleLevelLog = TRUE;

		// グラフ表示
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::SetFilter
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
BOOL CFftOctaveDlg::SetFilter(BOOL bDispAlert)
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
//		CFftOctaveDlg::OnBnClickedButtonFilterSet
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
void CFftOctaveDlg::OnBnClickedButtonFilterSet()
{
	// フィルタ設定
	if (SetFilter(TRUE)) {
		// 再計算＆表示
		CalcAndDispGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnCbnSelchangeComboFilterBand
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
void CFftOctaveDlg::OnCbnSelchangeComboFilterBand()
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
//		CFftOctaveDlg::OnBnClickedButtonCsv
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
void CFftOctaveDlg::OnBnClickedButtonCsv()
{
	// データ個数取得
	int nData = 0;
	for (int i = 0; i < OCTAVE_BAND_NUM; i++) {
		if (m_fOctaveBandFreq[i * 2] < m_fSampleRate / 2)
			nData++;
	}

	// 数値データ表示
	CGraphDigitalData	dlg(this);
	dlg.SetDataInfo(1, nData
		, m_pOctaveX
		, m_pOctaveY
		, m_pOctaveZ
		, m_pOctaveG
		, "1/3オクターブバンド数値データ表示"
		, 0
		, 0
		, m_fOctaveBandFreq);
	dlg.DoModal();

	return;
}

//*****************************************************************************************************
//  1. 関数名
//		CFftOctaveDlg::OnBnClickedButtonInitialize
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
void CFftOctaveDlg::OnBnClickedButtonInitialize()
{
	if (MessageBox("このウィンドウの表示を初期状態に戻しますか？", NULL, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

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
//		CFftOctaveDlg::PreTranslateMessage
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
BOOL CFftOctaveDlg::PreTranslateMessage(MSG* pMsg)
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
//		CFftOctaveDlg::MakeLimitLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リミットライン作成処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CFftOctaveDlg::MakeLimitLine()
{
	// リミットラインを計算
	for (int i = 0; i < OCTAVE_BAND_NUM; i++) {
		double fFreq = pow(10.0, i / 10.0 - 4.00);

		switch (m_pGraphData->m_nDataType) {
		case DATA_TYPE_MMA:
		case DATA_TYPE_ASU:
			// MMAのリミットライン
			if (fFreq < 0.1) {
				m_fLimitLine1[i] = 1.6e-6;
				m_fLimitLine2[i] = 0.707e-6;
			} else if (fFreq < 100) {
				m_fLimitLine1[i] = fFreq * 16e-6;
				m_fLimitLine2[i] = fFreq * 7.07e-6;
			} else {
				m_fLimitLine1[i] = 1600e-6;
				m_fLimitLine2[i] = 707e-6;
			}
			break;
		case DATA_TYPE_MME:
			// MMEのリミットライン
			if (fFreq < 0.1)
				m_fLimitLine1[i] = 0.707e-6;
			else if (fFreq < 2) {
				double fTmp = log(1e-4 / 0.707e-6) / log(2.0 / 0.1);
				m_fLimitLine1[i] = pow(fFreq * pow(0.707e-6, 1 / fTmp) / 0.1, fTmp);
			} else if (fFreq < 10)
				m_fLimitLine1[i] = 1e-4;
			else if (fFreq < 50) {
				double fTmp = log(0.05 / 1e-4) / log(50.0 / 10.0);
				m_fLimitLine1[i] = pow(fFreq * pow(1e-4, 1 / fTmp) / 10, fTmp);
			} else
				m_fLimitLine1[i] = 0.05;
			break;
		}
	}
}
