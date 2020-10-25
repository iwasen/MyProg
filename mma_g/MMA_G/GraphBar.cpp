//*****************************************************************************************************
//  1. ファイル名
//		GraphBar.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータウィンドウのダイアログバークラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphBar.h"
#include "GraphFrm.h"
#include "DataFile.h"
#include "General.h"
#include "Globaldata.h"
#include "Define.h"


// CGraphBar ダイアログ

IMPLEMENT_DYNAMIC(CGraphBar, CDialogBar)

void CGraphBar::DoDataExchange(CDataExchange* pDX)
{
	CDialogBar::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_TIME_BEGIN, m_cEditTimeBegin);
	DDX_Control(pDX, IDC_EDIT_TIME_END, m_cEditTimeEnd);
	DDX_Control(pDX, IDC_EDIT_TIME_CURSOR, m_cEditTimeCursor);
	DDX_Control(pDX, IDC_EDIT_TIME_ZOOM, m_cEditTimeZoom);
	DDX_Control(pDX, IDC_EDIT_LEVEL_BEGIN_X, m_cEditLevelBeginX);
	DDX_Control(pDX, IDC_EDIT_LEVEL_END_X, m_cEditLevelEndX);
	DDX_Control(pDX, IDC_EDIT_LEVEL_ZOOM_X, m_cEditLevelZoomX);
	DDX_Control(pDX, IDC_EDIT_RSU_X, m_cEditRsuX);
	DDX_Control(pDX, IDC_EDIT_RSU_Y, m_cEditRsuY);
	DDX_Control(pDX, IDC_EDIT_RSU_Z, m_cEditRsuZ);
	DDX_Control(pDX, IDC_EDIT_SCROLL_SPEED, m_cEditScrollSpeed);
	DDX_Control(pDX, IDC_CHECK_SCROLL_START, m_cCheckScrollStart);
	DDX_Control(pDX, IDC_EDIT_SELECT_BEGIN, m_cEditSelectBegin);
	DDX_Control(pDX, IDC_EDIT_SELECT_END, m_cEditSelectEnd);
	DDX_Control(pDX, IDC_COMBO_SAMPLE_RATE, m_cComboSampleRate);
	DDX_Control(pDX, IDC_EDIT_DIR_X, m_cEditDirX);
	DDX_Control(pDX, IDC_EDIT_DIR_Y, m_cEditDirY);
	DDX_Control(pDX, IDC_EDIT_DIR_Z, m_cEditDirZ);
// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
	DDX_Control(pDX, IDC_EDIT_INFO_TAA_ID, m_cEditInfoTaaId);
	DDX_Control(pDX, IDC_EDIT_INFO_ACQ_START_TIME, m_cEditInfoAcqStartTime);
	DDX_Control(pDX, IDC_EDIT_INFO_SAMPLING_RATE, m_cEditInfoSamplingRate);
	DDX_Control(pDX, IDC_EDIT_INFO_CUT_OFF_FREQ, m_cEditInfoCutOffFreq);
	DDX_Control(pDX, IDC_EDIT_INFO_GAIN, m_cEditInfoGain);
	DDX_Control(pDX, IDC_EDIT_INFO_POSITION, m_cEditInfoPosition);
// 077FD050-GYM961-0_003 2007/9/7 appended by yG <-
	DDX_Control(pDX, IDC_COMBO_FILTER_BAND, m_cComboFilterBand);
	DDX_Control(pDX, IDC_COMBO_FILTER_ORDER, m_cComboFilterOrder);
	DDX_Control(pDX, IDC_COMBO_FILTER_SHAPE, m_cComboFilterShape);
	DDX_Control(pDX, IDC_EDIT_FILTER_FREQ1, m_cEditFilterFreq1);
	DDX_Control(pDX, IDC_EDIT_FILTER_FREQ2, m_cEditFilterFreq2);
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		DDX_Control(pDX, IDC_EDIT_LEVEL_BEGIN_Y, m_cEditLevelBeginY);
		DDX_Control(pDX, IDC_EDIT_LEVEL_BEGIN_Z, m_cEditLevelBeginZ);
		DDX_Control(pDX, IDC_EDIT_LEVEL_END_Y, m_cEditLevelEndY);
		DDX_Control(pDX, IDC_EDIT_LEVEL_END_Z, m_cEditLevelEndZ);
		DDX_Control(pDX, IDC_EDIT_LEVEL_ZOOM_Y, m_cEditLevelZoomY);
		DDX_Control(pDX, IDC_EDIT_LEVEL_ZOOM_Z, m_cEditLevelZoomZ);
		DDX_Control(pDX, IDC_CHECK_ZERO_HOSEI, m_cCheckZeroHosei);
		DDX_Control(pDX, IDC_EDIT_SPECTROGRAM_POINT_COUNT, m_cEditSpectrogramPointCount);
		DDX_Control(pDX, IDC_CHECK_ZOOM_X, m_cCheckZoomX);
		DDX_Control(pDX, IDC_CHECK_ZOOM_Y, m_cCheckZoomY);
		DDX_Control(pDX, IDC_CHECK_ZOOM_Z, m_cCheckZoomZ);
		DDX_Control(pDX, IDC_EDIT_SPECTROGRAM_WIDTH, m_cEditSpectrogramWidth);
		DDX_Control(pDX, IDC_EDIT_ZERO_X, m_cEditZeroX);
		DDX_Control(pDX, IDC_EDIT_ZERO_Y, m_cEditZeroY);
		DDX_Control(pDX, IDC_EDIT_ZERO_Z, m_cEditZeroZ);
		DDX_Control(pDX, IDC_CHECK_ZERO_OFFSET_GRAPH, m_cCheckZeroOffsetGraph);
		DDX_Control(pDX, IDC_STATIC_INFO_TAA_ID, m_cStaticInfoTaa);
		DDX_Control(pDX, IDC_STATIC_INFO_CUT_OFF_FREQ, m_cStaticInfoCutOffFreq);
		DDX_Control(pDX, IDC_STATIC_INFO_GAIN, m_cStaticInfoGain);
		DDX_Control(pDX, IDC_STATIC_INFO_POSITION, m_cStaticInfoPosition);
		DDX_Control(pDX, IDC_STATIC_DIR_X, m_cStaticDirX);
		DDX_Control(pDX, IDC_STATIC_DIR_Y, m_cStaticDirY);
		DDX_Control(pDX, IDC_STATIC_DIR_Z, m_cStaticDirZ);
	} else {
		DDX_Control(pDX, IDC_EDIT_RSU_D, m_cEditRsuD);
		DDX_Control(pDX, IDC_CHECK_DISP_X, m_cCheckDispX);
		DDX_Control(pDX, IDC_CHECK_DISP_Y, m_cCheckDispY);
		DDX_Control(pDX, IDC_CHECK_DISP_Z, m_cCheckDispZ);
		DDX_Control(pDX, IDC_CHECK_DISP_D, m_cCheckDispD);
		DDX_Control(pDX, IDC_STATIC_LINE_X, m_cStaticLineX);
		DDX_Control(pDX, IDC_STATIC_LINE_Y, m_cStaticLineY);
		DDX_Control(pDX, IDC_STATIC_LINE_Z, m_cStaticLineZ);
		DDX_Control(pDX, IDC_STATIC_LINE_D, m_cStaticLineD);
	}
}


BEGIN_MESSAGE_MAP(CGraphBar, CDialogBar)
	ON_MESSAGE(WM_INITDIALOG, OnInitDialog)
	ON_MESSAGE(WM_KEY_RET_KEY, OnRetKey)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_TIME_ZOOM_ALL, "横軸の倍率を１に設定し、全体を表示します。"},
	{IDC_BUTTON_TIME_ZOOM_IN, "横軸の倍率を２倍に拡大します。"},
	{IDC_BUTTON_TIME_ZOOM_OUT, "横軸の倍率を1/2倍に縮小します。"},
	{IDC_BUTTON_LEVEL_ZOOM_ALL, "縦軸の倍率を１に設定し、全体を表示します。"},
	{IDC_BUTTON_LEVEL_ZOOM_IN, "縦軸の倍率を２倍に拡大します。"},
	{IDC_BUTTON_LEVEL_ZOOM_OUT, "縦軸の倍率を1/2倍に縮小します。"},
	{IDC_BUTTON_SELECT_ALL, "全ての時間範囲を解析範囲として選択します。"},
	{IDC_BUTTON_SELECT_MANUAL, "解析範囲を手動で指定します。"},
	{IDC_BUTTON_FFT_SPECTRUM, "選択した解析範囲のデータを対象としたスペクトル解析ウィンドウを開きます。"},
	{IDC_BUTTON_FFT_OCTAVE, "選択した解析範囲のデータを対象とした1/3オクターブバンド解析ウィンドウを開きます。"},
	{IDC_BUTTON_FFT_SPECTROGRAM, "選択した解析範囲のデータを対象としたスペクトログラム解析ウィンドウを開きます。"},
	{IDC_BUTTON_TIME_CURSOR_TO_LEFT, "時間カーソルを１データ分、左へ移動します。"},
	{IDC_BUTTON_TIME_CURSOR_TO_RIGHT, "時間カーソルを１データ分、右へ移動します。"},
	{IDC_CHECK_SCROLL_START, "時間カーソルを自動的に右へ移動します。動作中にクリックすると停止します。"},
	{IDC_BUTTON_FILTER_SET, "設定したフィルタをデータに適用します。"},
	{IDC_BUTTON_INITIALIZE, "このウィンドウの表示内容を初期状態に戻します。"},
	{IDC_EDIT_SPECTROGRAM_POINT_COUNT, "スペクトログラムの時間軸の分解能を設定します。\n100と設定した場合、選択された解析範囲時間を100分割してスペクトル解析します。"},
	{IDC_CHECK_ZERO_HOSEI, "加速度データから直流成分を差し引いて解析を行います。"},
	{IDC_COMBO_SAMPLE_RATE, "サンプリングレートを設定した値に下げてグラフ表示を行います。"},
	{IDC_COMBO_FILTER_BAND, "フィルタの種類を、無し（全域通過）、LPF（低域通過）、HPF（高域通過）、BPF（帯域通過）、BSF（帯域阻止）から選択します。"},
	{IDC_COMBO_FILTER_ORDER, "フィルタの次数を設定します。次数が大きいほど急峻なフィルタとなります。"},
	{IDC_COMBO_FILTER_SHAPE, "フィルタの特性を、Butterworth（バターワース特性）、Chebyshev（チェビシェフ特性）、Bessel（ベッセル特性）から選択します。"},
	{IDC_CHECK_ZERO_OFFSET_GRAPH, "0点補正値グラフを表示します。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		TRUE
//*****************************************************************************************************
LRESULT CGraphBar::OnInitDialog(WPARAM wParam, LPARAM lParam)
{
	LRESULT bRet = HandleInitDialog(wParam, lParam);

	UpdateData(FALSE);

	CGraphFrame *pParent = (CGraphFrame *)GetParent();

	// サンプリング周波数のコンボボックスを設定
	CGeneral::SetSampleRateList(m_cComboSampleRate, pParent->m_cGraphData.m_fSampleRate);

	switch (pParent->m_cGraphData.m_nDataType) {
	case DATA_TYPE_MMA:
		{
			int	idx = pParent->m_cGraphData.m_nSensorIdx;
			if (idx >= 0) {
				m_cEditDirX = g_pSensorInfo[idx].dir_x;
				m_cEditDirY = g_pSensorInfo[idx].dir_y;
				m_cEditDirZ = g_pSensorInfo[idx].dir_z;
			}
		}
		break;
	case DATA_TYPE_MME:
	case DATA_TYPE_ASU:
		m_cStaticDirX.EnableWindow(FALSE);
		m_cStaticDirY.EnableWindow(FALSE);
		m_cStaticDirZ.EnableWindow(FALSE);
		break;
	}

// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
	// センサ/計測情報エリアを表示
	CDataDateTime acqStartTime;
	acqStartTime.SetTime(pParent->m_cGraphData.m_nStartYear,
						 pParent->m_cGraphData.m_nStartMonth,
						 pParent->m_cGraphData.m_nStartDay,
						 pParent->m_cGraphData.m_nStartMiliSecond);
	m_cEditInfoTaaId		= pParent->m_cGraphData.m_nSensorId;
	m_cEditInfoAcqStartTime	= acqStartTime.GetStr();
	m_cEditInfoSamplingRate	= pParent->m_cGraphData.m_fSampleRate;
	switch (pParent->m_cGraphData.m_nDataType) {
	case DATA_TYPE_MMA:
		m_cEditInfoCutOffFreq	= pParent->m_cGraphData.m_nCutoff;
		m_cEditInfoGain			= pParent->m_cGraphData.m_nGain;
		m_cEditInfoPosition		= pParent->m_cGraphData.m_SensorPos;
		break;
	case DATA_TYPE_MME:
		m_cStaticInfoTaa.SetWindowText("Sensor ID");
		m_cStaticInfoCutOffFreq.EnableWindow(FALSE);
		m_cStaticInfoGain.EnableWindow(FALSE);
		m_cEditInfoPosition		= pParent->m_cGraphData.m_SensorPos;
		break;
	case DATA_TYPE_ASU:
		m_cStaticInfoTaa.SetWindowText("Sensor ID");
		m_cStaticInfoCutOffFreq.EnableWindow(FALSE);
		m_cStaticInfoGain.EnableWindow(FALSE);
		m_cStaticInfoPosition.EnableWindow(FALSE);
		break;
	}

	// フィルタのコンボボックスを設定
	CGeneral::SetFilterBandList(m_cComboFilterBand, 0);
	CGeneral::SetFilterOrderList(m_cComboFilterOrder, 3);
	CGeneral::SetFilterTypeList(m_cComboFilterShape, 0);

	// エディットコントロールの有効入力文字を設定
	m_cEditTimeZoom.SetValidChar(VC_NUM | VC_POINT);
	m_cEditLevelBeginX.SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	m_cEditLevelBeginY.SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	m_cEditLevelBeginZ.SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	m_cEditLevelEndX.SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	m_cEditLevelEndY.SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	m_cEditLevelEndZ.SetValidChar(VC_NUM | VC_POINT | VC_MINUS);
	m_cEditLevelZoomX.SetValidChar(VC_NUM | VC_POINT);
	m_cEditLevelZoomY.SetValidChar(VC_NUM | VC_POINT);
	m_cEditLevelZoomZ.SetValidChar(VC_NUM | VC_POINT);
	m_cEditFilterFreq1.SetValidChar(VC_NUM | VC_POINT);
	m_cEditFilterFreq2.SetValidChar(VC_NUM | VC_POINT);

	// 凡例の線の色を設定
	m_cStaticLineD.SetBackColor(COLOR_GRAPH_DATA_D);
	m_cStaticLineX.SetBackColor(COLOR_GRAPH_DATA_X);
	m_cStaticLineY.SetBackColor(COLOR_GRAPH_DATA_Y);
	m_cStaticLineZ.SetBackColor(COLOR_GRAPH_DATA_Z);

	// 各コントロールを初期化
	InitializeControls();

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return bRet;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::InitializeControls
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
void CGraphBar::InitializeControls()
{
	// スクロールスピードを1に設定
	m_cEditScrollSpeed = 1;

	switch (m_nGraphKind) {
	case GRAPH_ACCEL:
		// 0点補正を設定
		m_cCheckZeroHosei = gZeroOffsetSw;

		// 拡大縮小ボタンのチェックボックスを設定
		m_cCheckZoomX = TRUE;
		m_cCheckZoomY = TRUE;
		m_cCheckZoomZ = TRUE;

// 077FD050-GYM961-0_005 2007/9/8 appended by yG ->
		// スペクトログラム解析の解析幅/ずらし幅の算出用
		// ポイント数と時間幅のデフォルト設定
		m_cEditSpectrogramPointCount = 100;
		m_cEditSpectrogramWidth = 100;
// 077FD050-GYM961-0_005 2007/9/8 appended by yG <-

		// 0点補正値グラフボタン
		m_cCheckZeroOffsetGraph = FALSE;
		break;
	case GRAPH_TEMP:
		// 表示データチェックボックスをセット
		m_cCheckDispD = TRUE;
		m_cCheckDispX = TRUE;
		m_cCheckDispY = TRUE;
		m_cCheckDispZ = TRUE;
		break;
	case GRAPH_ZERO_OFFSET:
		// 0点補正を設定
		m_cCheckZeroHosei.EnableWindow(FALSE);

		// 拡大縮小ボタンのチェックボックスを設定
		m_cCheckZoomX = TRUE;
		m_cCheckZoomY = TRUE;
		m_cCheckZoomZ = TRUE;

		m_cEditSpectrogramPointCount.EnableWindow(FALSE);
		m_cEditSpectrogramWidth.EnableWindow(FALSE);

		// 0点補正値グラフボタン
		m_cCheckZeroOffsetGraph.ShowWindow(SW_HIDE);
		break;
	}

	// データ間引き表示設定
	m_cComboSampleRate.SetCurSel(0);

	// フィルタ設定初期化
	m_cComboFilterBand.SetCurSel(0);

}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispTimeData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間の表示範囲を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		sTimeBegin		[I] 表示範囲の開始時間
//		CString		sTimeEnd		[I] 表示範囲の終了時間
//		double		fTimeZoom		[I] 横軸ズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispTimeData(CString sTimeBegin, CString sTimeEnd, double fTimeZoom)
{
	m_cEditTimeBegin = sTimeBegin;
	m_cEditTimeEnd = sTimeEnd;

	if (m_cEditTimeZoom.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditTimeZoom.Format("%g", fTimeZoom);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispLevelDataX
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｘ軸加速度の表示範囲を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fLevelBegin		[I] 表示範囲のレベル（μG）
//		double		fLevelEnd		[I] 表示範囲のレベル（μG）
//		double		fLevelZoom		[I] 縦軸ズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispLevelDataX(double fLevelBegin, double fLevelEnd, double fLevelZoom)
{
	if (m_cEditLevelBeginX.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelBeginX.Format("%.3f", fLevelBegin);

	if (m_cEditLevelEndX.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelEndX.Format("%.3f", fLevelEnd);

	if (m_cEditLevelZoomX.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelZoomX.Format("%g", fLevelZoom);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispLevelDataY
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｙ軸加速度の表示範囲を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fLevelBegin		[I] 表示範囲のレベル（μG）
//		double		fLevelEnd		[I] 表示範囲のレベル（μG）
//		double		fLevelZoom		[I] 縦軸ズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispLevelDataY(double fLevelBegin, double fLevelEnd, double fLevelZoom)
{
	if (m_cEditLevelBeginY.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelBeginY.Format("%.3f", fLevelBegin);

	if (m_cEditLevelEndY.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelEndY.Format("%.3f", fLevelEnd);

	if (m_cEditLevelZoomY.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelZoomY.Format("%g", fLevelZoom);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispLevelDataZ
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Ｚ軸加速度の表示範囲を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fLevelBegin		[I] 表示範囲のレベル（μG）
//		double		fLevelEnd		[I] 表示範囲のレベル（μG）
//		double		fLevelZoom		[I] 縦軸ズーム倍率
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispLevelDataZ(double fLevelBegin, double fLevelEnd, double fLevelZoom)
{
	if (m_cEditLevelBeginZ.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelBeginZ.Format("%.3f", fLevelBegin);

	if (m_cEditLevelEndZ.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelEndZ.Format("%.3f", fLevelEnd);

	if (m_cEditLevelZoomZ.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditLevelZoomZ.Format("%g", fLevelZoom);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		時間カーソルの位置を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		TimeCursor		[I] カーソル位置の時間
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispTimeCursor(CString TimeCursor)
{
	m_cEditTimeCursor = TimeCursor;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispRsuData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル位置のRSUデータを表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fRsuX		[I] カーソル位置のX軸の値
//		double		fRsuY		[I] カーソル位置のY軸の値
//		double		fRsuZ		[I] カーソル位置のZ軸の値
//		double		fRsuD		[I] カーソル位置のDACMの値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispRsuData(double fRsuX, double fRsuY, double fRsuZ, double fRsuD)
{
	m_cEditRsuX.Format("%.3f", fRsuX);
	m_cEditRsuY.Format("%.3f", fRsuY);
	m_cEditRsuZ.Format("%.3f", fRsuZ);
	if (m_nGraphKind == GRAPH_TEMP)
		m_cEditRsuD.Format("%.3f", fRsuD);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::ClearRsuData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		カーソル位置のRSUデータを消去
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::ClearRsuData()
{
	m_cEditRsuX.Blank();
	m_cEditRsuY.Blank();
	m_cEditRsuZ.Blank();
	if (m_nGraphKind == GRAPH_TEMP)
		m_cEditRsuD.Blank();
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispSelectArea
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		選択エリアの時間を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fSelectBegin		[I] 選択エリアの開始時間
//		double		fSelectEnd			[I] 選択エリアの終了時間
//		CString		strBegin			[I] 選択エリアの開始時間
//		CString		strEnd				[I] 選択エリアの終了時間
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispSelectArea(double fSelectBegin, double fSelectEnd, CString strBegin, CString strEnd)
{
	if (fSelectBegin != fSelectEnd) {
		if (fSelectBegin > fSelectEnd) {
			double fTmp = fSelectBegin;
			fSelectBegin = fSelectEnd;
			fSelectEnd = fTmp;
		}

		m_cEditSelectBegin = strBegin;
		m_cEditSelectEnd = strEnd;
	} else {
		m_cEditSelectBegin.Blank();
		m_cEditSelectEnd.Blank();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispZeroPointValue
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		0点補正値を表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fAverageX			[I] X軸0点補正値
//		double		fAverageY			[I] Y軸0点補正値
//		double		fAverageZ			[I] Z軸0点補正値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispZeroPointValue(double fAverageX, double fAverageY, double fAverageZ)
{
	// 0点補正値を表示
	m_cEditZeroX.Format("%.3f", fAverageX);
	m_cEditZeroY.Format("%.3f", fAverageY);
	m_cEditZeroZ.Format("%.3f", fAverageZ);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::OnRetKey
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
LRESULT CGraphBar::OnRetKey(WPARAM wParam, LPARAM lParam)
{
	GetParent()->SendMessage(WM_KEY_RET_KEY, wParam, lParam);
	return 0;
}


//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::PreTranslateMessage
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
BOOL CGraphBar::PreTranslateMessage(MSG* pMsg)
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

	return CDialogBar::PreTranslateMessage(pMsg);
}
