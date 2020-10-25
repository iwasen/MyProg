//*****************************************************************************************************
//  1. ファイル名
//		GraphBar.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフウィンドウのダイアログバークラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "GraphBar.h"
#include "GraphWnd.h"
#include "GraphFrm.h"

#define ZOOM_MAX	20

// CGraphBar ダイアログ

IMPLEMENT_DYNAMIC(CGraphBar, CDialogBar)

void CGraphBar::DoDataExchange(CDataExchange* pDX)
{
	CDialogBar::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_TIME_BEGIN, m_cEditTimeBegin);
	DDX_Control(pDX, IDC_EDIT_TIME_END, m_cEditTimeEnd);
	DDX_Control(pDX, IDC_SLIDER_TIME_ZOOM, m_cSliderTimeZoom);
	DDX_Control(pDX, IDC_EDIT_TEMP_BEGIN, m_cEditTempBegin);
	DDX_Control(pDX, IDC_EDIT_TEMP_END, m_cEditTempEnd);
	DDX_Control(pDX, IDC_EDIT_HEATER_BEGIN, m_cEditHeaterBegin);
	DDX_Control(pDX, IDC_EDIT_HEATER_END, m_cEditHeaterEnd);
	DDX_Control(pDX, IDC_SLIDER_LEVEL_ZOOM, m_cSliderLevelZoom);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT1_X, m_cCheckGraph[UNIT_1][GRAPH_X]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT1_Y, m_cCheckGraph[UNIT_1][GRAPH_Y]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT1_Z, m_cCheckGraph[UNIT_1][GRAPH_Z]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT1_SB, m_cCheckGraph[UNIT_1][GRAPH_SB]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT1_HEATER, m_cCheckGraph[UNIT_1][GRAPH_HEATER]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT2_X, m_cCheckGraph[UNIT_2][GRAPH_X]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT2_Y, m_cCheckGraph[UNIT_2][GRAPH_Y]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT2_Z, m_cCheckGraph[UNIT_2][GRAPH_Z]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT2_SB, m_cCheckGraph[UNIT_2][GRAPH_SB]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT2_HEATER, m_cCheckGraph[UNIT_2][GRAPH_HEATER]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT3_X, m_cCheckGraph[UNIT_3][GRAPH_X]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT3_Y, m_cCheckGraph[UNIT_3][GRAPH_Y]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT3_Z, m_cCheckGraph[UNIT_3][GRAPH_Z]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT3_SB, m_cCheckGraph[UNIT_3][GRAPH_SB]);
	DDX_Control(pDX, IDC_CHECK_GRAPH_UNIT3_HEATER, m_cCheckGraph[UNIT_3][GRAPH_HEATER]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT1_X, m_cStaticGraph[UNIT_1][GRAPH_X]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT1_Y, m_cStaticGraph[UNIT_1][GRAPH_Y]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT1_Z, m_cStaticGraph[UNIT_1][GRAPH_Z]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT1_SB, m_cStaticGraph[UNIT_1][GRAPH_SB]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT1_HEATER, m_cStaticGraph[UNIT_1][GRAPH_HEATER]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT2_X, m_cStaticGraph[UNIT_2][GRAPH_X]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT2_Y, m_cStaticGraph[UNIT_2][GRAPH_Y]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT2_Z, m_cStaticGraph[UNIT_2][GRAPH_Z]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT2_SB, m_cStaticGraph[UNIT_2][GRAPH_SB]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT2_HEATER, m_cStaticGraph[UNIT_2][GRAPH_HEATER]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT3_X, m_cStaticGraph[UNIT_3][GRAPH_X]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT3_Y, m_cStaticGraph[UNIT_3][GRAPH_Y]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT3_Z, m_cStaticGraph[UNIT_3][GRAPH_Z]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT3_SB, m_cStaticGraph[UNIT_3][GRAPH_SB]);
	DDX_Control(pDX, IDC_STATIC_GRAPH_UNIT3_HEATER, m_cStaticGraph[UNIT_3][GRAPH_HEATER]);
}


BEGIN_MESSAGE_MAP(CGraphBar, CDialogBar)
	ON_MESSAGE(WM_INITDIALOG, OnInitDialog)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_X, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_Y, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_Z, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_SB, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_HEATER, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_X, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_Y, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_Z, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_SB, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_HEATER, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_X, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_Y, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_Z, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_SB, &CGraphBar::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_HEATER, &CGraphBar::OnBnClickedCheckGraph)
	ON_WM_HSCROLL()
	ON_MESSAGE(WM_MOUSEIN, &CGraphBar::OnMouseIn)
	ON_MESSAGE(WM_MOUSEOUT, &CGraphBar::OnMouseOut)
END_MESSAGE_MAP()


// CGraphBar メッセージ ハンドラ

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

	// ズームスライダレンジ設定
	m_cSliderTimeZoom.SetRange(0, ZOOM_MAX, FALSE);
	m_cSliderLevelZoom.SetRange(0, ZOOM_MAX, FALSE);

	// Data Lineの色を設定
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			m_cStaticGraph[nUnit][nGraph].SetBackColor(CGraphWnd::m_aGraphColor[nUnit][nGraph]);
			m_cStaticGraph[nUnit][nGraph].SetMouseTrack(TRUE);
		}
	}

	return bRet;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispHorizontalAxis
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸の情報を表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTimeBegin		[I] 開始時刻
//		double		fTimeEnd		[I] 終了時刻
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispHorizontalAxis(double fTimeBegin, double fTimeEnd)
{
	// Time Rangeの表示
	m_cEditTimeBegin = CTime((time_t)fTimeBegin).FormatGmt("%Y/%m/%d %H:%M:%S");
	m_cEditTimeEnd = CTime((time_t)fTimeEnd).FormatGmt("%Y/%m/%d %H:%M:%S");
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispVerticalAxis
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸の情報を表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fTempBegin		[I] 温度範囲の開始
//		double		fTempEnd		[I] 温度範囲の終了
//		double		fHeaterBegin	[I] ヒータ電圧の開始
//		double		fHeaterEnd		[I] ヒータ電圧の終了
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispVerticalAxis(double fTempBegin, double fTempEnd, double fHeaterBegin, double fHeaterEnd)
{
	// Temperature Rangeの表示
	m_cEditTempBegin.Format("%.1f", fTempBegin);
	m_cEditTempEnd.Format("%.1f", fTempEnd);

	// Header Voltage Rangeの表示
	m_cEditHeaterBegin.Format("%.1f", fHeaterBegin);
	m_cEditHeaterEnd.Format("%.1f", fHeaterEnd);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::DispDataLine
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフデータラインの情報を表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SGraphLine		&oGraphLine		[I] グラフデータライン情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::DispDataLine(const SGraphLine &oGraphLine)
{
	// Data Lineのチェック状態を設定
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++)
			m_cCheckGraph[nUnit][nGraph].SetCheck(oGraphLine.bEnable[nUnit][nGraph]);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::OnBnClickedCheckGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフラインのチェックボックスのクリックイベントハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::OnBnClickedCheckGraph()
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
	SGraphLine oGraphLine;

	// Data Lineのチェック状態を取得
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			oGraphLine.bEnable[nUnit][nGraph] = m_cCheckGraph[nUnit][nGraph];
		}
	}

	// フレームウィンドウにData Lineのチェック状態の変更を通知
	pGraphFrm->ChangeGraphLine(oGraphLine);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ズームスライダの操作ハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nSBCode			[I] スクロール要求コード
//		UINT		nPos			[I] スクロールボックスの位置
//		CScrollBar	*pScrollBar		[I] スクロールバーコントロールへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	// フレームウィンドウにズームを通知
	if (pScrollBar->m_hWnd == m_cSliderTimeZoom.GetSafeHwnd()) {
		pGraphFrm->ZoomTime((double)m_cSliderTimeZoom.GetPos() / ZOOM_MAX);
	} else if (pScrollBar->m_hWnd == m_cSliderLevelZoom.GetSafeHwnd()) {
		pGraphFrm->ZoomLevel((double)m_cSliderLevelZoom.GetPos() / ZOOM_MAX);
	}

	CDialogBar::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::SetTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		横軸ズームスライダの設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fZoom			[I] ズーム位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::SetTimeZoom(double fZoom)
{
	m_cSliderTimeZoom.SetPos((int)(fZoom * ZOOM_MAX));
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::SetLevelZoom
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		縦軸ズームスライダの設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double		fZoom			[I] ズーム位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CGraphBar::SetLevelZoom(double fZoom)
{
	m_cSliderLevelZoom.SetPos((int)(fZoom * ZOOM_MAX));
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::OnMouseIn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスがデータラインの上に乗った時の処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam				[I] メッセージパラメータ
//		LPARAM		lParam				[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphBar::OnMouseIn(WPARAM wParam, LPARAM lParam)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			if (m_cStaticGraph[nUnit][nGraph].m_hWnd == (HWND)wParam) {
				pGraphFrm->m_wndGraphView.SetHighlight(nUnit, nGraph);
				pGraphFrm->UpdateGraph();
			}				
		}
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CGraphBar::OnMouseOut
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		マウスがデータラインの上から外れた時の処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam				[I] メッセージパラメータ
//		LPARAM		lParam				[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphBar::OnMouseOut(WPARAM wParam, LPARAM lParam)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	pGraphFrm->m_wndGraphView.SetHighlight(-1, -1);
	pGraphFrm->UpdateGraph();

	return 0;
}
