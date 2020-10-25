Disp//*****************************************************************************************************
//  1. ファイル名
//		MmeRecvDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メインダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "AboutDlg.h"
#include "MmeRecvDlg.h"
#include "General.h"
#include "GraphFrm.h"
#include <mmsystem.h>
#include "SetMonitoringDlg.h"
#include <shlwapi.h>

#ifdef _DEBUG
#define new DEBUG_NEW
#endif

// UDPポート
#define PORT_NASA	35026
#define PORT_HCOR	35032

// ソケット通知メッセージ
#define WM_SOCKET_NASA	(WM_APP + 0)
#define WM_SOCKET_HCOR	(WM_APP + 1)

// テレメトリ受信Idle秒数
#define RECEIVE_IDLE_COUNT	3

// グラフデータバッファサイズ（24時間）
#define N_GRAPH_DATA	(60 * 60 * 24)

// CMmeRecvDlg ダイアログ

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::CMmeRecvDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent			[I] 親ウィンドウポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CMmeRecvDlg::CMmeRecvDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CMmeRecvDlg::IDD, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);

	m_sockNasa = INVALID_SOCKET;
	m_sockHcor = INVALID_SOCKET;
	m_bRunning = FALSE;
	m_bValidNasaDigitalData = FALSE;
	m_bValidNasaAnalogData = FALSE;
	m_bValidHcorDigitalData = FALSE;
	m_bValidHcorAnalogData = FALSE;
	m_pNasaGraphData = new SGraphData[N_GRAPH_DATA];
	m_pHcorGraphData = new SGraphData[N_GRAPH_DATA];
	m_bNasaReceiveStatus = FALSE;
	m_bNasaEnableDigitalData = FALSE;
	m_bNasaEnableAnalogData = FALSE;
	m_bHcorReceiveStatus = FALSE;
	m_bHcorEnableDigitalData = FALSE;
	m_bHcorEnableAnalogData = FALSE;
	m_nNasaReceiveIdleCounter = 0;
	m_nNasaDigitalIdleCounter = 0;
	m_nNasaAnalogIdleCounter = 0;
	m_nHcorReceiveIdleCounter = 0;
	m_nHcorDigitalIdleCounter = 0;
	m_nHcorAnalogIdleCounter = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::~CMmeRecvDlg
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
CMmeRecvDlg::~CMmeRecvDlg()
{
	delete [] m_pNasaGraphData;
	delete [] m_pHcorGraphData;
}

void CMmeRecvDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_STATIC_RECEIVE, m_cStaticReceive);
	DDX_Control(pDX, IDC_BUTTON_RECEIVE, m_cButtonReceive);
	DDX_Control(pDX, IDC_EDIT_NASA_PORT, m_cEditNasaPort);
	DDX_Control(pDX, IDC_EDIT_NASA_FILE_NAME, m_cEditNasaFileName);
	DDX_Control(pDX, IDC_CHECK_NASA_PQ, m_cCheckNasaPQ);
	DDX_Control(pDX, IDC_EDIT_HCOR_PORT, m_cEditHcorPort);
	DDX_Control(pDX, IDC_EDIT_HCOR_FILE_NAME, m_cEditHcorFileName);
	DDX_Control(pDX, IDC_CHECK_HCOR_PQ, m_cCheckHcorPQ);
	DDX_Control(pDX, IDC_EDIT_GRAPH_TIME_SPAN, m_cEditGraphTimeSpan);
	DDX_Control(pDX, IDC_EDIT_GRAPH_TEMP_RANGE1, m_cEditGraphTempRange1);
	DDX_Control(pDX, IDC_EDIT_GRAPH_TEMP_RANGE2, m_cEditGraphTempRange2);
	DDX_Control(pDX, IDC_EDIT_GRAPH_VOLTAGE_RANGE1, m_cEditGraphVoltageRange1);
	DDX_Control(pDX, IDC_EDIT_GRAPH_VOLTAGE_RANGE2, m_cEditGraphVoltageRange2);
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
	DDX_Control(pDX, IDC_STATIC_HEALTH_STATUS, m_cStaticHealthStatus);
	DDX_Control(pDX, IDC_EDIT_ALxxx_TIME, m_cEditAlxxxTime);
	DDX_Control(pDX, IDC_BUTTON_STOP_ALxxx, m_cButtonStopAlxxx);
	DDX_Control(pDX, IDC_BUTTON_CLEAR_STATUS, m_cButtonClearStatus);
	DDX_Control(pDX, IDC_EDIT_HEALTH_STATUS, m_cEditHealthStatus);
	DDX_Control(pDX, IDC_EDIT_NASA_USER_HEADER_TEXT, m_cEditNasaUserHeaderText);
	DDX_Control(pDX, IDC_STATIC_NASA_ACC_X, m_cStaticNasaAcc[ACC_X]);
	DDX_Control(pDX, IDC_STATIC_NASA_ACC_Y, m_cStaticNasaAcc[ACC_Y]);
	DDX_Control(pDX, IDC_STATIC_NASA_ACC_Z, m_cStaticNasaAcc[ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT1_ACC_X, m_cEditNasaDigitalAcc[UNIT_1][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT1_ACC_Y, m_cEditNasaDigitalAcc[UNIT_1][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT1_ACC_Z, m_cEditNasaDigitalAcc[UNIT_1][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT2_ACC_X, m_cEditNasaDigitalAcc[UNIT_2][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT2_ACC_Y, m_cEditNasaDigitalAcc[UNIT_2][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT2_ACC_Z, m_cEditNasaDigitalAcc[UNIT_2][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT3_ACC_X, m_cEditNasaDigitalAcc[UNIT_3][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT3_ACC_Y, m_cEditNasaDigitalAcc[UNIT_3][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_DIGITAL_UNIT3_ACC_Z, m_cEditNasaDigitalAcc[UNIT_3][ACC_Z]);
	DDX_Control(pDX, IDC_STATIC_NASA_ANALOG_X, m_cStaticNasaAnalog[TEMP_X]);
	DDX_Control(pDX, IDC_STATIC_NASA_ANALOG_Y, m_cStaticNasaAnalog[TEMP_Y]);
	DDX_Control(pDX, IDC_STATIC_NASA_ANALOG_Z, m_cStaticNasaAnalog[TEMP_Z]);
	DDX_Control(pDX, IDC_STATIC_NASA_ANALOG_SB, m_cStaticNasaAnalog[TEMP_SB]);
	DDX_Control(pDX, IDC_STATIC_NASA_ANALOG_HEATER, m_cStaticNasaAnalogHeater);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_ACC_X, m_cEditNasaAnalogAcc[UNIT_1][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_ACC_Y, m_cEditNasaAnalogAcc[UNIT_1][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_ACC_Z, m_cEditNasaAnalogAcc[UNIT_1][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_TEMP_X, m_cEditNasaAnalogTemp[UNIT_1][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_TEMP_Y, m_cEditNasaAnalogTemp[UNIT_1][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_TEMP_Z, m_cEditNasaAnalogTemp[UNIT_1][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_TEMP_SB, m_cEditNasaAnalogTemp[UNIT_1][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT1_HEATER, m_cEditNasaAnalogHeater[UNIT_1]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_ACC_X, m_cEditNasaAnalogAcc[UNIT_2][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_ACC_Y, m_cEditNasaAnalogAcc[UNIT_2][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_ACC_Z, m_cEditNasaAnalogAcc[UNIT_2][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_TEMP_X, m_cEditNasaAnalogTemp[UNIT_2][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_TEMP_Y, m_cEditNasaAnalogTemp[UNIT_2][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_TEMP_Z, m_cEditNasaAnalogTemp[UNIT_2][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_TEMP_SB, m_cEditNasaAnalogTemp[UNIT_2][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT2_HEATER, m_cEditNasaAnalogHeater[UNIT_2]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_ACC_X, m_cEditNasaAnalogAcc[UNIT_3][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_ACC_Y, m_cEditNasaAnalogAcc[UNIT_3][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_ACC_Z, m_cEditNasaAnalogAcc[UNIT_3][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_TEMP_X, m_cEditNasaAnalogTemp[UNIT_3][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_TEMP_Y, m_cEditNasaAnalogTemp[UNIT_3][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_TEMP_Z, m_cEditNasaAnalogTemp[UNIT_3][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_TEMP_SB, m_cEditNasaAnalogTemp[UNIT_3][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_NASA_ANALOG_UNIT3_HEATER, m_cEditNasaAnalogHeater[UNIT_3]);
	DDX_Control(pDX, IDC_EDIT_NASA_RECEIVE_TIME, m_cEditNasaReceiveTime);
	DDX_Control(pDX, IDC_STATIC_NASA_RECEIVE_STATUS, m_cStaticNasaReceiveStatus);
	DDX_Control(pDX, IDC_STATIC_NASA_GRAPH, m_cStaticNasaGraph);
	DDX_Control(pDX, IDC_LIST_NASA_STATUS_HISTORY, m_cListNasaStatusHistory);
	DDX_Control(pDX, IDC_EDIT_NASA_STATUS_DATA, m_cEditNasaStatusData);
	DDX_Control(pDX, IDC_EDIT_HCOR_USER_HEADER_TEXT, m_cEditHcorUserHeaderText);
	DDX_Control(pDX, IDC_STATIC_HCOR_ACC_X, m_cStaticHcorAcc[ACC_X]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ACC_Y, m_cStaticHcorAcc[ACC_Y]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ACC_Z, m_cStaticHcorAcc[ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT1_ACC_X, m_cEditHcorDigitalAcc[UNIT_1][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT1_ACC_Y, m_cEditHcorDigitalAcc[UNIT_1][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT1_ACC_Z, m_cEditHcorDigitalAcc[UNIT_1][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT2_ACC_X, m_cEditHcorDigitalAcc[UNIT_2][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT2_ACC_Y, m_cEditHcorDigitalAcc[UNIT_2][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT2_ACC_Z, m_cEditHcorDigitalAcc[UNIT_2][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT3_ACC_X, m_cEditHcorDigitalAcc[UNIT_3][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT3_ACC_Y, m_cEditHcorDigitalAcc[UNIT_3][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_DIGITAL_UNIT3_ACC_Z, m_cEditHcorDigitalAcc[UNIT_3][ACC_Z]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ANALOG_X, m_cStaticHcorAnalog[TEMP_X]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ANALOG_Y, m_cStaticHcorAnalog[TEMP_Y]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ANALOG_Z, m_cStaticHcorAnalog[TEMP_Z]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ANALOG_SB, m_cStaticHcorAnalog[TEMP_SB]);
	DDX_Control(pDX, IDC_STATIC_HCOR_ANALOG_HEATER, m_cStaticHcorAnalogHeater);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_ACC_X, m_cEditHcorAnalogAcc[UNIT_1][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_ACC_Y, m_cEditHcorAnalogAcc[UNIT_1][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_ACC_Z, m_cEditHcorAnalogAcc[UNIT_1][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_TEMP_X, m_cEditHcorAnalogTemp[UNIT_1][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_TEMP_Y, m_cEditHcorAnalogTemp[UNIT_1][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_TEMP_Z, m_cEditHcorAnalogTemp[UNIT_1][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_TEMP_SB, m_cEditHcorAnalogTemp[UNIT_1][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT1_HEATER, m_cEditHcorAnalogHeater[UNIT_1]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_ACC_X, m_cEditHcorAnalogAcc[UNIT_2][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_ACC_Y, m_cEditHcorAnalogAcc[UNIT_2][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_ACC_Z, m_cEditHcorAnalogAcc[UNIT_2][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_TEMP_X, m_cEditHcorAnalogTemp[UNIT_2][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_TEMP_Y, m_cEditHcorAnalogTemp[UNIT_2][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_TEMP_Z, m_cEditHcorAnalogTemp[UNIT_2][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_TEMP_SB, m_cEditHcorAnalogTemp[UNIT_2][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT2_HEATER, m_cEditHcorAnalogHeater[UNIT_2]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_ACC_X, m_cEditHcorAnalogAcc[UNIT_3][ACC_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_ACC_Y, m_cEditHcorAnalogAcc[UNIT_3][ACC_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_ACC_Z, m_cEditHcorAnalogAcc[UNIT_3][ACC_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_TEMP_X, m_cEditHcorAnalogTemp[UNIT_3][TEMP_X]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_TEMP_Y, m_cEditHcorAnalogTemp[UNIT_3][TEMP_Y]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_TEMP_Z, m_cEditHcorAnalogTemp[UNIT_3][TEMP_Z]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_TEMP_SB, m_cEditHcorAnalogTemp[UNIT_3][TEMP_SB]);
	DDX_Control(pDX, IDC_EDIT_HCOR_ANALOG_UNIT3_HEATER, m_cEditHcorAnalogHeater[UNIT_3]);
	DDX_Control(pDX, IDC_EDIT_HCOR_RECEIVE_TIME, m_cEditHcorReceiveTime);
	DDX_Control(pDX, IDC_STATIC_HCOR_RECEIVE_STATUS, m_cStaticHcorReceiveStatus);
	DDX_Control(pDX, IDC_STATIC_HCOR_GRAPH, m_cStaticHcorGraph);
	DDX_Control(pDX, IDC_LIST_HCOR_STATUS_HISTORY, m_cListHcorStatusHistory);
	DDX_Control(pDX, IDC_EDIT_HCOR_STATUS_DATA, m_cEditHcorStatusData);
	DDX_Control(pDX, IDC_CHECK_NASA_AUTO_FILE_NAME, m_cCheckNasaAutoFileName);
	DDX_Control(pDX, IDC_CHECK_HCOR_AUTO_FILE_NAME, m_cCheckHcorAutoFileName);
	DDX_Control(pDX, IDC_EDIT_NASA_SEQUENCE_COUNT, m_cEditNasaSequenceCount);
	DDX_Control(pDX, IDC_EDIT_HCOR_SEQUENCE_COUNT, m_cEditHcorSequenceCount);
	DDX_Control(pDX, IDC_CHECK_ENABLE_TEMP_CHK, m_cCheckEnableTempChk);
	DDX_Control(pDX, IDC_CHECK_ENABLE_VOLTAGE_CHK, m_cCheckEnableVoltageChk);
	DDX_Control(pDX, IDC_BUTTON_NASA_GRAPH_WINDOW, m_cButtonNasaGraph);
	DDX_Control(pDX, IDC_BUTTON_HCOR_GRAPH_WINDOW, m_cButtonHcorGraph);
	DDX_Control(pDX, IDC_RADIO_NASA_SAMPLE_RATE, m_cRadioNasaSampleRate);
	DDX_Control(pDX, IDC_RADIO_NASA_ACCUMULATE, m_cRadioNasaAccumulate);
	DDX_Control(pDX, IDC_RADIO_HCOR_SAMPLE_RATE, m_cRadioHcorSampleRate);
	DDX_Control(pDX, IDC_RADIO_HCOR_ACCUMULATE, m_cRadioHcorAccumulate);
}

BEGIN_MESSAGE_MAP(CMmeRecvDlg, CDialog)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_SOCKET_NASA, &CMmeRecvDlg::OnSocketNasa)
	ON_MESSAGE(WM_SOCKET_HCOR, &CMmeRecvDlg::OnSocketHcor)
	ON_WM_DESTROY()
	ON_LBN_SELCHANGE(IDC_LIST_NASA_STATUS_HISTORY, &CMmeRecvDlg::OnLbnSelchangeListNasaStatusHistory)
	ON_LBN_SELCHANGE(IDC_LIST_HCOR_STATUS_HISTORY, &CMmeRecvDlg::OnLbnSelchangeListHcorStatusHistory)
	ON_BN_CLICKED(IDC_BUTTON_STOP_ALxxx, &CMmeRecvDlg::OnBnClickedButtonStopAlxxx)
	ON_BN_CLICKED(IDC_BUTTON_CLEAR_STATUS, &CMmeRecvDlg::OnBnClickedButtonClearStatus)
	ON_BN_CLICKED(IDC_BUTTON_RECEIVE, &CMmeRecvDlg::OnBnClickedButtonReceive)
	ON_BN_CLICKED(IDC_BUTTON_READ_FILE, &CMmeRecvDlg::OnBnClickedButtonReadFile)
	ON_WM_TIMER()
	ON_BN_CLICKED(IDC_BUTTON_NASA_GRAPH_WINDOW, &CMmeRecvDlg::OnBnClickedButtonNasaGraphWindow)
	ON_BN_CLICKED(IDC_BUTTON_HCOR_GRAPH_WINDOW, &CMmeRecvDlg::OnBnClickedButtonHcorGraphWindow)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_X, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_Y, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_Z, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_SB, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT1_HEATER, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_X, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_Y, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_Z, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_SB, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT2_HEATER, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_X, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_Y, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_Z, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_SB, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_GRAPH_UNIT3_HEATER, &CMmeRecvDlg::OnBnClickedCheckGraph)
	ON_BN_CLICKED(IDC_CHECK_NASA_PQ, &CMmeRecvDlg::OnBnClickedCheckNasaPq)
	ON_BN_CLICKED(IDC_CHECK_HCOR_PQ, &CMmeRecvDlg::OnBnClickedCheckHcorPq)
	ON_EN_CHANGE(IDC_EDIT_NASA_PORT, &CMmeRecvDlg::OnEnChangeEditNasaPort)
	ON_EN_CHANGE(IDC_EDIT_HCOR_PORT, &CMmeRecvDlg::OnEnChangeEditHcorPort)
	ON_EN_CHANGE(IDC_EDIT_GRAPH_TIME_SPAN, &CMmeRecvDlg::OnEnChangeEditGraphTimeSpan)
	ON_EN_CHANGE(IDC_EDIT_GRAPH_TEMP_RANGE1, &CMmeRecvDlg::OnEnChangeEditGraphTempRange1)
	ON_EN_CHANGE(IDC_EDIT_GRAPH_TEMP_RANGE2, &CMmeRecvDlg::OnEnChangeEditGraphTempRange2)
	ON_EN_CHANGE(IDC_EDIT_GRAPH_VOLTAGE_RANGE1, &CMmeRecvDlg::OnEnChangeEditGraphVoltageRange1)
	ON_EN_CHANGE(IDC_EDIT_GRAPH_VOLTAGE_RANGE2, &CMmeRecvDlg::OnEnChangeEditGraphVoltageRange2)
	ON_BN_CLICKED(IDC_CHECK_NASA_AUTO_FILE_NAME, &CMmeRecvDlg::OnBnClickedCheckNasaAutoFileName)
	ON_BN_CLICKED(IDC_CHECK_HCOR_AUTO_FILE_NAME, &CMmeRecvDlg::OnBnClickedCheckHcorAutoFileName)
	ON_BN_CLICKED(IDC_CHECK_ENABLE_TEMP_CHK, &CMmeRecvDlg::OnBnClickedCheckEnableTempChk)
	ON_BN_CLICKED(IDC_CHECK_ENABLE_VOLTAGE_CHK, &CMmeRecvDlg::OnBnClickedCheckEnableVoltageChk)
	ON_BN_CLICKED(IDC_BUTTON_SET_MONITORING, &CMmeRecvDlg::OnBnClickedButtonSetMonitoring)
	ON_BN_CLICKED(IDC_RADIO_NASA_SAMPLE_RATE, &CMmeRecvDlg::OnBnClickedRadioNasaSampleRate)
	ON_BN_CLICKED(IDC_RADIO_NASA_ACCUMULATE, &CMmeRecvDlg::OnBnClickedRadioNasaAccumulate)
	ON_BN_CLICKED(IDC_RADIO_HCOR_SAMPLE_RATE, &CMmeRecvDlg::OnBnClickedRadioHcorSampleRate)
	ON_BN_CLICKED(IDC_RADIO_HCOR_ACCUMULATE, &CMmeRecvDlg::OnBnClickedRadioHcorAccumulate)
	ON_BN_CLICKED(IDC_BUTTON_CLOSE, &CMmeRecvDlg::OnBnClickedButtonClose)
	ON_MESSAGE(WM_MOUSEIN, &CMmeRecvDlg::OnMouseIn)
	ON_MESSAGE(WM_MOUSEOUT, &CMmeRecvDlg::OnMouseOut)
END_MESSAGE_MAP()


// CMmeRecvDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnInitDialog
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
BOOL CMmeRecvDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// "バージョン情報..." メニューをシステム メニューに追加します。

	// IDM_ABOUTBOX は、システム コマンドの範囲内になければなりません。
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// このダイアログのアイコンを設定します。アプリケーションのメイン ウィンドウがダイアログでない場合、
	//  Framework は、この設定を自動的に行います。
	SetIcon(m_hIcon, TRUE);			// 大きいアイコンの設定
	SetIcon(m_hIcon, FALSE);		// 小さいアイコンの設定

	// ウィンドウタイトル設定
	CString sVersion;
	sVersion.LoadString(IDS_VERSION);
	CString sTitle;
	sTitle.Format("MmeRecv Ver%s", sVersion);
	SetWindowText(sTitle);

	// 設定値をiniファイルから読み込む
	ReadSettings();

	// 受信フォルダを作成
	g_oMmeData.CreateReceiveFolder();

	// NASAの設定値を表示
	m_cEditNasaPort = m_oSettings.oNasa.nUdpPort;
	m_cCheckNasaAutoFileName = m_oSettings.oNasa.bAutoFileName;
	m_cCheckNasaPQ = m_oSettings.oNasa.bPysicalQuantity;
	m_cRadioNasaSampleRate = !m_oSettings.oNasa.bAccumulate;
	m_cRadioNasaAccumulate = m_oSettings.oNasa.bAccumulate;

	// HCORの設定を表示
	m_cEditHcorPort = m_oSettings.oHcor.nUdpPort;
	m_cCheckHcorAutoFileName = m_oSettings.oHcor.bAutoFileName;
	m_cCheckHcorPQ = m_oSettings.oHcor.bPysicalQuantity;
	m_cRadioHcorSampleRate = !m_oSettings.oHcor.bAccumulate;
	m_cRadioHcorAccumulate = m_oSettings.oHcor.bAccumulate;

	// グラフの設定値を表示
	m_cEditGraphTimeSpan = m_oSettings.oGraph.nTimeSpan;
	m_cEditGraphTempRange1 = m_oSettings.oGraph.fTempRange1;
	m_cEditGraphTempRange2 = m_oSettings.oGraph.fTempRange2;
	m_cEditGraphVoltageRange1 = m_oSettings.oGraph.fVoltageRange1;
	m_cEditGraphVoltageRange2 = m_oSettings.oGraph.fVoltageRange2;

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			m_cCheckGraph[nUnit][nGraph].SetCheck(m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph]);
			m_cStaticGraph[nUnit][nGraph].SetBackColor(CGraphWnd::m_aGraphColor[nUnit][nGraph]);
			m_cStaticGraph[nUnit][nGraph].SetMouseTrack(TRUE);
		}
	}

	// 温度・電圧監視の設定値を表示
	m_cCheckEnableTempChk = m_oSettings.oMonitoring.bEnableTempChk;
	m_cCheckEnableVoltageChk = m_oSettings.oMonitoring.bEnableVoltageChk;

	// エディットコントロールの入力可能文字種を設定
	m_cEditNasaPort.SetValidChar(VC_NUM);
	m_cEditHcorPort.SetValidChar(VC_NUM);
	m_cEditGraphTimeSpan.SetValidChar(VC_NUM);
	m_cEditGraphTempRange1.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);
	m_cEditGraphTempRange2.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);
	m_cEditGraphVoltageRange1.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);
	m_cEditGraphVoltageRange2.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);

	// フォントを作成
	LOGFONT logfont;
	memset(&logfont, 0, sizeof(logfont));
	logfont.lfHeight = 140;
	logfont.lfCharSet = DEFAULT_CHARSET;
	logfont.lfWeight = FW_BOLD;
	strcpy_s(logfont.lfFaceName, "MS UI Gothic");
	m_font2.CreatePointFontIndirect(&logfont);
	m_cStaticHealthStatus.SetFont(&m_font2);
	m_cStaticHealthStatus.SetTextColor(RGB(0, 0, 0));
	m_cStaticReceive.SetFont(&m_font2);

	// グラフ用バッファを確保
	m_oNasaGraphData.SetBufSize(N_GRAPH_DATA);
	m_oHcorGraphData.SetBufSize(N_GRAPH_DATA);

	// グラフの初期表示
	DispNasaGraph();
	DispHcorGraph();

	// タイマー起動
	SetTimer(0, 1000, NULL);

	StopReceive();
	ClearHealthStatus();

	return TRUE;  // フォーカスをコントロールに設定した場合を除き、TRUE を返します。
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnSysCommand
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nID				[I] システムコマンドID
//		LPARAM		lParam			[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX) {
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	} else if(nID == SC_CLOSE) {
		CloseDialog();
	} else {
		CDialog::OnSysCommand(nID, lParam);
	}
}

// ダイアログに最小化ボタンを追加する場合、アイコンを描画するための
//  下のコードが必要です。ドキュメント/ビュー モデルを使う MFC アプリケーションの場合、
//  これは、Framework によって自動的に設定されます。

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ペイントメッセージハンドラ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // 描画のデバイス コンテキスト

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// クライアントの四角形領域内の中央
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// アイコンの描画
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnQueryDragIcon
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ユーザーが最小化したウィンドウをドラッグしているときに表示するカーソルを取得するために
//		システムがこの関数を呼び出します。
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		HCURSOR		カーソル
//*****************************************************************************************************
HCURSOR CMmeRecvDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::InitSocket
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ソケット初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:失敗
//*****************************************************************************************************
BOOL CMmeRecvDlg::InitSocket()
{
	sockaddr_in socketAddr;

	// NASA用ソケットオープン
	m_sockNasa = socket(PF_INET, SOCK_DGRAM, 0);
	if (m_sockNasa == INVALID_SOCKET) {
		CGeneral::Alert("ソケットの作成に失敗しました。");
		return FALSE;
	}

	// HCOR用ソケットオープン
	m_sockHcor = socket(PF_INET, SOCK_DGRAM, 0);
	if (m_sockHcor == INVALID_SOCKET) {
		CGeneral::Alert("ソケットの作成に失敗しました。");
		return FALSE;
	}

	// NASA用ソケットのバインド
	memset(&socketAddr, 0, sizeof(socketAddr));
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(m_oSettings.oNasa.nUdpPort);
	socketAddr.sin_addr.s_addr = htonl(INADDR_ANY);
	if (bind(m_sockNasa, (LPSOCKADDR)&socketAddr, sizeof(socketAddr)) != 0) {
		CGeneral::Alert("ソケットのバインドに失敗しました。");
		return FALSE;
	}

	// HCOR用ソケットのバインド
	memset(&socketAddr, 0, sizeof(socketAddr));
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(m_oSettings.oHcor.nUdpPort);
	socketAddr.sin_addr.s_addr = htonl(INADDR_ANY);
	if (bind(m_sockHcor, (LPSOCKADDR)&socketAddr, sizeof(socketAddr)) != 0) {
		CGeneral::Alert("ソケットのバインドに失敗しました。");
		return FALSE;
	}

	// 受信バッファサイズを設定
	int val = 65536 * 2;
	setsockopt(m_sockNasa, SOL_SOCKET, SO_RCVBUF, (char *)&val, sizeof(val));
	setsockopt(m_sockHcor, SOL_SOCKET, SO_RCVBUF, (char *)&val, sizeof(val));

	// 受信開始
	WSAAsyncSelect(m_sockNasa, m_hWnd, WM_SOCKET_NASA, FD_READ);
	WSAAsyncSelect(m_sockHcor, m_hWnd, WM_SOCKET_HCOR, FD_READ);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::StartReceive
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信開始処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::StartReceive()
{
	CString sDateTime = CTime::GetCurrentTime().Format("%Y_%m%d_%H%M_%S");

	// NASA用受信ファイル名を取得
	CString sNasaFileName;
	if (m_oSettings.oNasa.bAutoFileName) {
		sNasaFileName.Format("NASA_%s", sDateTime);
		m_cEditNasaFileName = sNasaFileName;
	} else if (m_cEditNasaFileName.IsEmpty()) {
		CGeneral::Alert("Please enter NASA Real Time file name.");
		return;
	} else {
		sNasaFileName = m_cEditNasaFileName;
	}

	// HCOR用受信ファイル名を取得
	CString sHcorFileName;
	if (m_oSettings.oHcor.bAutoFileName) {
		sHcorFileName.Format("HCOR_%s", sDateTime);
		m_cEditHcorFileName = sHcorFileName;
	} else if (m_cEditHcorFileName.IsEmpty()) {
		CGeneral::Alert("Please enter HCOR file name.");
		return;
	} else {
		sHcorFileName = m_cEditHcorFileName;
	}

	// NASA用とHCOR用が同じファイル名ならエラー
	if (sNasaFileName == sHcorFileName) {
		CGeneral::Alert("Same file name.");
		return;
	}

	// NASA用受信ファイルの上書きチェック
	CString sNasaPathName;
	sNasaPathName.Format("%s\\%s.%s", g_oMmeData.GetReceiveFolder(), sNasaFileName, m_oSettings.oNasa.bAccumulate ? "a" : "s");
	if (::PathFileExists(sNasaPathName)) {
		if (AfxMessageBox("Overwrite NASA Real Time file ?", MB_YESNO | MB_ICONQUESTION) == IDNO)
			return;
	}

	// HCOR用受信ファイルの上書きチェック
	CString sHcorPathName;
	sHcorPathName.Format("%s\\%s.%s", g_oMmeData.GetReceiveFolder(), sHcorFileName, m_oSettings.oHcor.bAccumulate ? "a" : "s");
	if (::PathFileExists(sHcorPathName)) {
		if (AfxMessageBox("Overwrite HCOR file ?", MB_YESNO | MB_ICONQUESTION) == IDNO)
			return;
	}

	// ソケットを初期化
	if (!InitSocket()) {
		StopReceive();
		return;
	}

	// 受信ファイルをオープン
	g_oMmeData.OpenRecieveFiles(sNasaPathName, sHcorPathName);

	// グラフをリセット
	m_oNasaGraphData.Reset();
	m_oHcorGraphData.Reset();

	// データ有効フラグを初期化
	m_bValidNasaDigitalData = FALSE;
	m_bValidNasaAnalogData = FALSE;
	m_bValidHcorDigitalData = FALSE;
	m_bValidHcorAnalogData = FALSE;

	// モニタリングカウンタを初期化
	memset(&m_oNasaMonitoringCounter, 0, sizeof(m_oNasaMonitoringCounter));
	memset(&m_oHcorMonitoringCounter, 0, sizeof(m_oHcorMonitoringCounter));

	// ユーザヘッダ表示を消去
	m_cEditNasaUserHeaderText.Blank();
	m_cEditHcorUserHeaderText.Blank();

	// デジタルデータとアナログデータの表示を消去
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
			m_cEditNasaDigitalAcc[nUnit][nAcc].Blank();
			m_cEditNasaAnalogAcc[nUnit][nAcc].Blank();
			m_cEditHcorDigitalAcc[nUnit][nAcc].Blank();
			m_cEditHcorAnalogAcc[nUnit][nAcc].Blank();
		}

		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			m_cEditNasaAnalogTemp[nUnit][nTemp].Blank();
			m_cEditHcorAnalogTemp[nUnit][nTemp].Blank();
		}

		m_cEditNasaAnalogHeater[nUnit].Blank();
		m_cEditHcorAnalogHeater[nUnit].Blank();
	}

	// 受信時刻とシーケンスカウントの表示を消去
	m_cEditNasaReceiveTime.Blank();
	m_cEditNasaSequenceCount.Blank();
	m_cEditHcorReceiveTime.Blank();
	m_cEditHcorSequenceCount.Blank();

	// 受信ステータスを消去
	SetNasaReceiveStatus(FALSE, TRUE);
	SetHcorReceiveStatus(FALSE, TRUE);

	// ヘルスステータスを消去
	ClearHealthStatus();

	// Status/Messageを消去
	ClearNasaStatusList();
	ClearHcorStatusList();

	// グラフの初期表示
	DispNasaGraph();
	DispHcorGraph();

	// 動作中に操作できないエディットコントロールを無効化
	m_cEditNasaPort.EnableWindow(FALSE);
	m_cEditHcorPort.EnableWindow(FALSE);
	m_cCheckNasaAutoFileName.EnableWindow(FALSE);
	m_cCheckHcorAutoFileName.EnableWindow(FALSE);
	m_cEditNasaFileName.EnableWindow(FALSE);
	m_cEditHcorFileName.EnableWindow(FALSE);
	m_cRadioNasaSampleRate.EnableWindow(FALSE);
	m_cRadioNasaAccumulate.EnableWindow(FALSE);
	m_cRadioHcorSampleRate.EnableWindow(FALSE);
	m_cRadioHcorAccumulate.EnableWindow(FALSE);

	// 動作中表示
	m_cStaticReceive.SetTextColor(RGB(0, 0, 255));
	m_cStaticReceive = "Running";
	m_cButtonReceive = "Stop";
	m_bRunning = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::StopReceive
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信停止処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::StopReceive()
{
	// NASA用ソケットをクローズ
	if (m_sockNasa != INVALID_SOCKET) {
		closesocket(m_sockNasa);
		m_sockNasa = INVALID_SOCKET;
	}

	// HCOR用ソケットをクローズ
	if (m_sockHcor != INVALID_SOCKET) {
		closesocket(m_sockHcor);
		m_sockHcor = INVALID_SOCKET;
	}

	// 受信ファイルをクローズ
	g_oMmeData.CloseReceiveFiles();

	// 動作中に操作できないエディットコントロールを有効化
	m_cEditNasaPort.EnableWindow(TRUE);
	m_cEditHcorPort.EnableWindow(TRUE);
	m_cCheckNasaAutoFileName.EnableWindow(TRUE);
	m_cCheckHcorAutoFileName.EnableWindow(TRUE);
	m_cEditNasaFileName.EnableWindow(!m_cCheckNasaAutoFileName);
	m_cEditHcorFileName.EnableWindow(!m_cCheckHcorAutoFileName);
	m_cRadioNasaSampleRate.EnableWindow(TRUE);
	m_cRadioNasaAccumulate.EnableWindow(TRUE);
	m_cRadioHcorSampleRate.EnableWindow(TRUE);
	m_cRadioHcorAccumulate.EnableWindow(TRUE);

	// 受信ステータスをリセット
	SetNasaReceiveStatus(FALSE);
	SetHcorReceiveStatus(FALSE);

	// 停止中表示
	m_cStaticReceive.SetTextColor(RGB(0, 0, 0));
	m_cStaticReceive = "Stop";
	m_cButtonReceive = "Start";
	m_bRunning = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnSocketNasa
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用ソケット受信処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam				[I] メッセージパラメータ
//		LPARAM		lParam				[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CMmeRecvDlg::OnSocketNasa(WPARAM wParam, LPARAM lParam)
{
	BYTE aPacketBuf[2048];
	int nPacketLen;
	SUserHeader oUserHeader;
	STelemetryData oTelemetryData;
	SMmeStatusData oMmeStatusData;
	SErrorStatusData oErrorStatusData;
	SMessageData oMessageData;

	switch (WSAGETSELECTEVENT(lParam)) {
	case FD_READ:
		// パケットを受信
		nPacketLen = ReceivePacket(m_sockNasa, aPacketBuf);
		if (nPacketLen != 0) {
			// ユーザヘッダを取得
			g_oMmeData.GetUserHeader(aPacketBuf, oUserHeader);

			// パケットの種類ごとに処理を行う
			switch (oUserHeader.nPacketType) {
			case PACKET_TELEMETRY:
				// セグメントカウントが0ならデータを表示する
				if (oUserHeader.nSegmentCount == 0) {
					g_oMmeData.GetTeremetryData(aPacketBuf, oUserHeader, oTelemetryData);
					m_bValidNasaDigitalData = TRUE;
					m_bValidNasaAnalogData = TRUE;
					DispNasaReceiveInfo();
					DispNasaUserHeader(oUserHeader);
					DispNasaDigitalData(oTelemetryData.oDigitalData);
					DispNasaAnalogData(oTelemetryData.oAnalogData);
					m_oNasaGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oTelemetry.oStatusWord, oTelemetryData.oAnalogData);
					DispNasaGraph();
					Monitoring(oUserHeader.oTimestamp, oTelemetryData.oAnalogData, oUserHeader.oTelemetry.oStatusWord, m_oNasaMonitoringCounter, "NASA Real Time");
					SetNasaEnableDigitalData(TRUE);
					SetNasaEnableAnalogData(TRUE);
					m_nNasaDigitalIdleCounter = 0;
					m_nNasaAnalogIdleCounter = 0;
				}

				// アキュムレートならデジタルデータの加速度を加算
				if (m_oSettings.oNasa.bAccumulate)
					AccumulateAcc(aPacketBuf, oUserHeader, m_oNasaAccumulate, nPacketLen);
				break;
			case PACKET_MME_STATUS:
				// MMEステータスのデータを表示する
				g_oMmeData.GetMmeStatusData(aPacketBuf, oUserHeader, oMmeStatusData);
				m_bValidNasaAnalogData = TRUE;
				DispNasaAnalogData(oMmeStatusData.oAnalogData);
				DispNasaMmeStatusData(oUserHeader, oMmeStatusData);
				m_oNasaGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oMmeStatus.oStatusWord, oMmeStatusData.oAnalogData);
				DispNasaGraph();
				SetNasaEnableAnalogData(TRUE);
				m_nNasaAnalogIdleCounter = 0;
				break;
			case PACKET_ERROR_STATUS:
				// エラーステータスのデータを表示する
				g_oMmeData.GetErrorStatusData(aPacketBuf, oUserHeader, oErrorStatusData);
				DispNasaErrorStatusData(oUserHeader, oErrorStatusData);
				break;
			case PACKET_MESSAGE:
				// メッセージのデータを表示する
				g_oMmeData.GetMessageData(aPacketBuf, oUserHeader, oMessageData);
				DispNasaMessageData(oUserHeader, oMessageData);
				break;
			default:
				nPacketLen = 0;
				break;
			}

			// 受信ファイルに書き込む
			if (nPacketLen != 0)
				g_oMmeData.WriteNasaFile(aPacketBuf, nPacketLen);
		}
		break;
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnSocketHcor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用ソケット受信処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam				[I] メッセージパラメータ
//		LPARAM		lParam				[I] メッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CMmeRecvDlg::OnSocketHcor(WPARAM wParam, LPARAM lParam)
{
	BYTE aPacketBuf[2048];
	int nPacketLen;
	SUserHeader oUserHeader;
	STelemetryData oTelemetryData;
	SMmeStatusData oMmeStatusData;
	SErrorStatusData oErrorStatusData;
	SMessageData oMessageData;

	switch (WSAGETSELECTEVENT(lParam)) {
	case FD_READ:
		// パケットを受信
		nPacketLen = ReceivePacket(m_sockHcor, aPacketBuf);
		if (nPacketLen != 0) {
			// ユーザヘッダを取得
			g_oMmeData.GetUserHeader(aPacketBuf, oUserHeader);

			// パケットの種類ごとに処理を行う
			switch (oUserHeader.nPacketType) {
			case PACKET_TELEMETRY:
				// セグメントカウントが0ならデータを表示する
				if (oUserHeader.nSegmentCount == 0) {
					g_oMmeData.GetTeremetryData(aPacketBuf, oUserHeader, oTelemetryData);
					m_bValidHcorDigitalData = TRUE;
					m_bValidHcorAnalogData = TRUE;
					DispHcorReceiveInfo();
					DispHcorUserHeader(oUserHeader);
					DispHcorDigitalData(oTelemetryData.oDigitalData);
					DispHcorAnalogData(oTelemetryData.oAnalogData);
					m_oHcorGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oTelemetry.oStatusWord, oTelemetryData.oAnalogData);
					DispHcorGraph();
					Monitoring(oUserHeader.oTimestamp, oTelemetryData.oAnalogData, oUserHeader.oTelemetry.oStatusWord, m_oHcorMonitoringCounter, "HCOR");
					SetHcorEnableDigitalData(TRUE);
					SetHcorEnableAnalogData(TRUE);
					m_nHcorDigitalIdleCounter = 0;
					m_nHcorAnalogIdleCounter = 0;
				}

				// アキュムレートならデジタルデータの加速度を加算
				if (m_oSettings.oHcor.bAccumulate)
					AccumulateAcc(aPacketBuf, oUserHeader, m_oHcorAccumulate, nPacketLen);
				break;
			case PACKET_MME_STATUS:
				// MMEステータスのデータを表示する
				g_oMmeData.GetMmeStatusData(aPacketBuf, oUserHeader, oMmeStatusData);
				m_bValidHcorAnalogData = TRUE;
				DispHcorAnalogData(oMmeStatusData.oAnalogData);
				DispHcorMmeStatusData(oUserHeader, oMmeStatusData);
				m_oHcorGraphData.AddGraphData(oUserHeader.oTimestamp, oUserHeader.oMmeStatus.oStatusWord, oMmeStatusData.oAnalogData);
				DispHcorGraph();
				SetHcorEnableAnalogData(TRUE);
				m_nHcorAnalogIdleCounter = 0;
				break;
			case PACKET_ERROR_STATUS:
				// エラーステータスのデータを表示する
				g_oMmeData.GetErrorStatusData(aPacketBuf, oUserHeader, oErrorStatusData);
				DispHcorErrorStatusData(oUserHeader, oErrorStatusData);
				break;
			case PACKET_MESSAGE:
				// メッセージのデータを表示する
				g_oMmeData.GetMessageData(aPacketBuf, oUserHeader, oMessageData);
				DispHcorMessageData(oUserHeader, oMessageData);
				break;
			default:
				nPacketLen = 0;
				break;
			}

			// 受信ファイルに書き込む
			if (nPacketLen != 0)
				g_oMmeData.WriteHcorFile(aPacketBuf, nPacketLen);
		}
		break;
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::ReceivePacket
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		UDPパケット受信処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SOCKET		sock				[I] ソケット
//		BYTE		*pPacket			[O] 受信バッファポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		受信データバイト数
//*****************************************************************************************************
int CMmeRecvDlg::ReceivePacket(SOCKET sock, BYTE *pPacket)
{
	SOCKADDR_IN	socketAddr;
	int	nSocketAddrLen, nRecvLen;
	struct {
		struct {
			BYTE ocsHeader[20];
			BYTE ehsPrimary[16];
			BYTE ehsSecondary[12];
			BYTE ccsdsHeader[16];
		} headers;
		BYTE dataZone[2048];
	} recvBuf;

	// パケットを受信
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = 0;
	socketAddr.sin_addr.s_addr = 0;
	nSocketAddrLen = sizeof(socketAddr);
	memset(&recvBuf, 0, sizeof(recvBuf));
	nRecvLen = recvfrom(sock, (char *)&recvBuf, sizeof(recvBuf), 0, (LPSOCKADDR)&socketAddr, &nSocketAddrLen);

	// Data ZONEの長さを求める
	int nDataLen = nRecvLen - (sizeof(recvBuf.headers) + 2);
	if (nDataLen < 0)
		return 0;

	// Data Zoneの部分をコピー
	memcpy(pPacket, recvBuf.dataZone, nDataLen);

	return nDataLen;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::AccumulateAcc
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		UDPパケット受信処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pPacketBuf			[I] ソケット
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SAccumulate		&oAccumulate		[O] Accumulateデータ
//		int				&nPacketLen			[O] パケット長
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::AccumulateAcc(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SAccumulate &oAccumulate, int &nPacketLen)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	// セグメントカウントが0なら加算バッファをクリア
	if (oUserHeader.nSegmentCount == 0) {
		memset(&oAccumulate, 0, sizeof(oAccumulate));
		memcpy(&oAccumulate, pPacketBuf, USER_HEADER_SIZE + ANALOG_DATA_SIZE);
		pData += ANALOG_DATA_SIZE;
	}

	// 加速度データを加算
	for (UINT nData = 0; nData < oUserHeader.oTelemetry.nDataNum; nData++) {
		oAccumulate.wAccCount++;

		pData += 2;
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
				oAccumulate.nAcc[nUnit][nAcc] += (short)(((WORD)pData[0] << 8) | pData[1]);
				pData += 2;
			}
		}
	}

	// 終端セグメント識別が1なら加算結果をバッファに転送
	if (oUserHeader.nEndSegment) {
		memcpy(pPacketBuf, &oAccumulate, USER_HEADER_SIZE + ANALOG_DATA_SIZE);
		pData = pPacketBuf + USER_HEADER_SIZE + ANALOG_DATA_SIZE;
		*pData++ = oAccumulate.wAccCount >> 8;
		*pData++ = oAccumulate.wAccCount & 0xff;
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
				*pData++ = (oAccumulate.nAcc[nUnit][nAcc] >> 24) & 0xff;
				*pData++ = (oAccumulate.nAcc[nUnit][nAcc] >> 16) & 0xff;
				*pData++ = (oAccumulate.nAcc[nUnit][nAcc] >> 8) & 0xff;
				*pData++ = oAccumulate.nAcc[nUnit][nAcc] & 0xff;
			}
		}
		nPacketLen = (int)(pData - pPacketBuf);
	} else
		nPacketLen = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaReceiveInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用受信状態表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaReceiveInfo()
{
	m_cEditNasaReceiveTime = CTime::GetCurrentTime().Format("%H:%M:%S");
	SetNasaReceiveStatus(TRUE);
	m_nNasaReceiveIdleCounter = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaUserHeader
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用ユーザヘッダ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaUserHeader(const SUserHeader &oUserHeader)
{
	CString sUserHeaderData;

	GetUserHeaderText(oUserHeader, sUserHeaderData);
	m_cEditNasaUserHeaderText = sUserHeaderData;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaDigitalData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用デジタルデータ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDigitalData	&oDitalData		[I] デジタルデータ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaDigitalData(const SDigitalData &oDitalData)
{
	m_cEditNasaSequenceCount = oDitalData.nSequrnceCount;

	if (m_oSettings.oNasa.bPysicalQuantity) {
		// Physical QuantityがONの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaDigitalAcc[nUnit][nAcc].Format("%.5f", oDitalData.aUnit[nUnit].fAcc[nAcc] * 1000);
		}
	} else {
		// Physical QuantityがOFFの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaDigitalAcc[nUnit][nAcc].Format("%d", oDitalData.aUnit[nUnit].nAcc[nAcc]);
		}
	}

	memcpy(&m_oNasaLastDigitalData, &oDitalData, sizeof(SDigitalData));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaAnalogData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用アナログデータ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SAnalogData		&oAnalogData		[I] アナログデータ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaAnalogData(const SAnalogData &oAnalogData)
{
	if (m_oSettings.oNasa.bPysicalQuantity) {
		// Physical QuantityがONの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// 加速度を表示
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaAnalogAcc[nUnit][nAcc].Format("%.2f", oAnalogData.aUnit[nUnit].fAcc[nAcc] * 1000);

			// 温度を表示
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditNasaAnalogTemp[nUnit][nTemp].Format("%.3f", oAnalogData.aUnit[nUnit].fTemp[nTemp]);

			// ヒータ電圧を表示
			m_cEditNasaAnalogHeater[nUnit].Format("%.3f", oAnalogData.aUnit[nUnit].fHeater);
		}
	} else {
		// Physical QuantityがOFFの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// 加速度を表示
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaAnalogAcc[nUnit][nAcc].Format("%d", oAnalogData.aUnit[nUnit].nAcc[nAcc]);

			// 温度を表示
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditNasaAnalogTemp[nUnit][nTemp].Format("%d", oAnalogData.aUnit[nUnit].nTemp[nTemp]);

			// ヒータ電圧を表示
			m_cEditNasaAnalogHeater[nUnit].Format("%d", oAnalogData.aUnit[nUnit].nHeater);
		}
	}

	// データを保存（Physical Quantityを切り替えた時に表示するため）
	memcpy(&m_oNasaLastAnalogData, &oAnalogData, sizeof(SAnalogData));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaMmeStatusData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用MMEステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SMmeStatusData	&oMmeStatusData		[I] MMEステータス情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaMmeStatusData(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData)
{
	CString sData;

	// MMEステータスの表示内容を取得
	GetMmeStatusDataText(oUserHeader, oMmeStatusData, sData);

	// Status/Messageリストボックスに追加
	AddNasaStatusList("MME Status", sData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaErrorStatusData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用エラーステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader			&oUserHeader		[I] ユーザヘッダ情報
//		SErrorStatusData	&oErrorStatusData	[I] エラーステータス情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaErrorStatusData(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData)
{
	CString sData;

	// エラーステータスの表示内容を取得
	GetErrorStatusDataText(oUserHeader, oErrorStatusData, sData);

	// Status/Messageリストボックスに追加
	AddNasaStatusList("Error Status", sData);

	// ヘルスステータスに表示する内容を編集
	CString sError;
	sError.Format("経路：NASA Real Time\r\n"
		"ﾀｲﾑｽﾀﾝﾌﾟ：%s\r\n"
		"ｴﾗｰｺｰﾄﾞ：%s\r\n"
		"ｻﾌﾞｺｰﾄﾞ：%s",
		oUserHeader.oTimestamp.FormatGmt("%Y/%m/%d %H:%M:%S"),
		GetErrorCodeText(oUserHeader.oErrorStatus.nErrorCode),
		GetSubCodeText(oUserHeader.oErrorStatus.nErrorCode, oUserHeader.oErrorStatus.nSubCode),
		oErrorStatusData.sTaskName,
		oErrorStatusData.sErrorMessage);

	// エラーコード別にタスク名称とエラーメッセージを編集
	switch (oUserHeader.oErrorStatus.nErrorCode) {
	case 0x02:
		sData.Format(
				"%s\r\n"
				"ﾀｽｸ名称：%s\r\n"
				"ｴﾗｰﾒｯｾｰｼﾞ：%s",
				sError,
				oErrorStatusData.sTaskName,
				oErrorStatusData.sErrorMessage);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		sData.Format(
				"%s\r\n"
				"ｴﾗｰﾒｯｾｰｼﾞ：%s",
				sError,
				oErrorStatusData.sErrorMessage);
		break;
	default:
		sData = sError;
		break;
	}

	// ヘルスステータスに表示
	SetHealthStatus(sData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaMessageData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用メッセージ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SMessageData	&oMessageData		[I] メッセージ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaMessageData(const SUserHeader &oUserHeader, const SMessageData &oMessageData)
{
	CString sData;

	// メッセージの表示内容を取得
	GetMessageDataText(oUserHeader, oMessageData, sData);

	// Status/Messageリストボックスに追加
	AddNasaStatusList("Message", sData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::AddNasaStatusList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用ステータス／メッセージリストボックスへの追加処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pPacketName		[I] リストボックスに表示するパケット名称
//		LPCTSTR		pData			[I] パケット詳細情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::AddNasaStatusList(LPCTSTR pPacketName, LPCTSTR pData)
{
	CString sText;

	// 表示内容を保存
	CString *pStatusMessage = new CString();
	*pStatusMessage = pData;

	// リストボックスに追加
	sText.Format("%s %s", CTime::GetCurrentTime().Format("%H:%M:%S"), pPacketName);
	int nIndex = m_cListNasaStatusHistory.AddString(sText);
	m_cListNasaStatusHistory.SetItemDataPtr(nIndex, pStatusMessage);

	// 追加した項目を選択する
	m_cListNasaStatusHistory.SetCurSel(nIndex);
	OnLbnSelchangeListNasaStatusHistory();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorReceiveInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用受信状態表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorReceiveInfo()
{
	m_cEditHcorReceiveTime = CTime::GetCurrentTime().Format("%H:%M:%S");
	SetHcorReceiveStatus(TRUE);
	m_nHcorReceiveIdleCounter = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorUserHeader
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用ユーザヘッダ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorUserHeader(const SUserHeader &oUserHeader)
{
	CString sUserHeaderData;

	GetUserHeaderText(oUserHeader, sUserHeaderData);
	m_cEditHcorUserHeaderText = sUserHeaderData;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorDigitalData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用デジタルデータ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SDigitalData	&oDitalData		[I] デジタルデータ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorDigitalData(const SDigitalData &oDitalData)
{
	m_cEditHcorSequenceCount = oDitalData.nSequrnceCount;

	if (m_oSettings.oHcor.bPysicalQuantity) {
		// Physical QuantityがONの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorDigitalAcc[nUnit][nAcc].Format("%.5f", oDitalData.aUnit[nUnit].fAcc[nAcc] * 1000);
		}
	} else {
		// Physical QuantityがOFFの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorDigitalAcc[nUnit][nAcc].Format("%d", oDitalData.aUnit[nUnit].nAcc[nAcc]);
		}
	}

	memcpy(&m_oHcorLastDigitalData, &oDitalData, sizeof(SDigitalData));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorAnalogData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用アナログデータ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SAnalogData		&oAnalogData		[I] アナログデータ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorAnalogData(const SAnalogData &oAnalogData)
{
	if (m_oSettings.oHcor.bPysicalQuantity) {
		// Physical QuantityがONの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// 加速度を表示
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorAnalogAcc[nUnit][nAcc].Format("%.2f", oAnalogData.aUnit[nUnit].fAcc[nAcc] * 1000);

			// 温度を表示
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditHcorAnalogTemp[nUnit][nTemp].Format("%.3f", oAnalogData.aUnit[nUnit].fTemp[nTemp]);

			// ヒータ電圧を表示
			m_cEditHcorAnalogHeater[nUnit].Format("%.3f", oAnalogData.aUnit[nUnit].fHeater);
		}
	} else {
		// Physical QuantityがOFFの場合
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// 加速度を表示
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorAnalogAcc[nUnit][nAcc].Format("%d", oAnalogData.aUnit[nUnit].nAcc[nAcc]);

			// 温度を表示
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditHcorAnalogTemp[nUnit][nTemp].Format("%d", oAnalogData.aUnit[nUnit].nTemp[nTemp]);

			// ヒータ電圧を表示
			m_cEditHcorAnalogHeater[nUnit].Format("%d", oAnalogData.aUnit[nUnit].nHeater);
		}
	}

	// データを保存（Physical Quantityを切り替えた時に表示するため）
	memcpy(&m_oHcorLastAnalogData, &oAnalogData, sizeof(SAnalogData));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorMmeStatusData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用MMEステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SMmeStatusData	&oMmeStatusData		[I] MMEステータス情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorMmeStatusData(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData)
{
	CString sData;

	// MMEステータスの表示内容を取得
	GetMmeStatusDataText(oUserHeader, oMmeStatusData, sData);

	// Status/Messageリストボックスに追加
	AddHcorStatusList("MME Status", sData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorErrorStatusData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用エラーステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader			&oUserHeader		[I] ユーザヘッダ情報
//		SErrorStatusData	&oErrorStatusData	[I] エラーステータス情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorErrorStatusData(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData)
{
	CString sData;

	// エラーステータスの表示内容を取得
	GetErrorStatusDataText(oUserHeader, oErrorStatusData, sData);

	// Status/Messageリストボックスに追加
	AddHcorStatusList("Error Status", sData);

	// ヘルスステータスに表示する内容を編集
	CString sError;
	sError.Format("経路：HCOR\r\n"
		"ﾀｲﾑｽﾀﾝﾌﾟ：%s\r\n"
		"ｴﾗｰｺｰﾄﾞ：%s\r\n"
		"ｻﾌﾞｺｰﾄﾞ：%s",
		oUserHeader.oTimestamp.FormatGmt("%Y/%m/%d %H:%M:%S"),
		GetErrorCodeText(oUserHeader.oErrorStatus.nErrorCode),
		GetSubCodeText(oUserHeader.oErrorStatus.nErrorCode, oUserHeader.oErrorStatus.nSubCode),
		oErrorStatusData.sTaskName,
		oErrorStatusData.sErrorMessage);

	// エラーコード別にタスク名称とエラーメッセージを編集
	switch (oUserHeader.oErrorStatus.nErrorCode) {
	case 0x02:
		sData.Format(
				"%s\r\n"
				"ﾀｽｸ名称：%s\r\n"
				"ｴﾗｰﾒｯｾｰｼﾞ：%s",
				sError,
				oErrorStatusData.sTaskName,
				oErrorStatusData.sErrorMessage);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		sData.Format(
				"%s\r\n"
				"ｴﾗｰﾒｯｾｰｼﾞ：%s",
				sError,
				oErrorStatusData.sErrorMessage);
		break;
	default:
		sData = sError;
		break;
	}

	// ヘルスステータスに表示
	SetHealthStatus(sData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorMessageData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用メッセージ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SMessageData	&oMessageData		[I] メッセージ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorMessageData(const SUserHeader &oUserHeader, const SMessageData &oMessageData)
{
	CString sData;

	// メッセージの表示内容を取得
	GetMessageDataText(oUserHeader, oMessageData, sData);

	// Status/Messageリストボックスに追加
	AddHcorStatusList("Message", sData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::AddHcorStatusList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用ステータス／メッセージリストボックスへの追加処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pPacketName		[I] リストボックスに表示するパケット名称
//		LPCTSTR		pData			[I] パケット詳細情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::AddHcorStatusList(LPCTSTR pPacketName, LPCTSTR pData)
{
	CString sText;

	// 表示内容を保存
	CString *pStatusMessage = new CString();
	*pStatusMessage = pData;

	// リストボックスに追加
	sText.Format("%s %s", CTime::GetCurrentTime().Format("%H:%M:%S"), pPacketName);
	int nIndex = m_cListHcorStatusHistory.AddString(sText);
	m_cListHcorStatusHistory.SetItemDataPtr(nIndex, pStatusMessage);

	// 追加した項目を選択する
	m_cListHcorStatusHistory.SetCurSel(nIndex);
	OnLbnSelchangeListHcorStatusHistory();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnDestroy
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
void CMmeRecvDlg::OnDestroy()
{
	CDialog::OnDestroy();

	// 受信停止
	StopReceive();

	// Status/Messageリスト削除（メモリ解放）
	ClearNasaStatusList();
	ClearHcorStatusList();

	// 設定値保存
	WriteSettings();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnLbnSelchangeListNasaStatusHistory
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用ステータス／メッセージリストボックス選択処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnLbnSelchangeListNasaStatusHistory()
{
	// 選択されているStatus/Messageの内容を表示
	CString *pStatusMessage = (CString *)m_cListNasaStatusHistory.GetItemDataPtr(m_cListNasaStatusHistory.GetCurSel());
	m_cEditNasaStatusData = *pStatusMessage;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::ClearNasaStatusList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用ステータス／メッセージリストボックス消去処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::ClearNasaStatusList()
{
	// メモリ解放
	int nCount = m_cListNasaStatusHistory.GetCount();
	for (int i = 0; i < nCount; i++)
		delete (CString *)m_cListNasaStatusHistory.GetItemDataPtr(i);

	// リストボックスリセット
	m_cListNasaStatusHistory.ResetContent();

	// 内容消去
	m_cEditNasaStatusData.Blank();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnLbnSelchangeListHcorStatusHistory
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用ステータス／メッセージリストボックス選択処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnLbnSelchangeListHcorStatusHistory()
{
	// 選択されているStatus/Messageの内容を表示
	CString *pStatusMessage = (CString *)m_cListHcorStatusHistory.GetItemDataPtr(m_cListHcorStatusHistory.GetCurSel());
	m_cEditHcorStatusData = *pStatusMessage;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::ClearHcorStatusList
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用ステータス／メッセージリストボックス消去処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::ClearHcorStatusList()
{
	// メモリ解放
	int nCount = m_cListHcorStatusHistory.GetCount();
	for (int i = 0; i < nCount; i++)
		delete (CString *)m_cListHcorStatusHistory.GetItemDataPtr(i);

	// リストボックスリセット
	m_cListHcorStatusHistory.ResetContent();

	// 内容消去
	m_cEditHcorStatusData.Blank();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonStopAlxxx
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アラーム停止ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonStopAlxxx()
{
	StopAlxxx();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonClearStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ヘルスステータス消去ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonClearStatus()
{
	ClearHealthStatus();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetHealthStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ヘルスステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pPacketData			[I] ステータス詳細情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetHealthStatus(LPCTSTR pPacketData)
{
	if (!bAlertOn) {
		// アラーム音を鳴らす
		PlaySound("alxxx.wav", NULL, SND_FILENAME | SND_ASYNC | SND_LOOP);
		bAlertOn = TRUE;
	}

	// Abnormal表示
	m_cStaticHealthStatus.SetTextColor(RGB(255, 0, 0));
	m_cStaticHealthStatus = "Abnormal";
	m_cEditAlxxxTime = CTime::GetCurrentTime().Format("%m/%d %H:%M:%S");
	m_cEditHealthStatus = pPacketData;
	m_cButtonStopAlxxx.EnableWindow(TRUE);
	m_cButtonClearStatus.EnableWindow(TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::ClearHealthStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ヘルスステータス消去処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::ClearHealthStatus()
{
	// アラーム音停止
	StopAlxxx();

	// Normal表示
	m_cStaticHealthStatus.SetTextColor(RGB(0, 0, 255));
	m_cStaticHealthStatus = "Normal";
	m_cEditAlxxxTime.Blank();
	m_cEditHealthStatus.Blank();
	m_cButtonClearStatus.EnableWindow(FALSE);

	// 温度監視カウンタリセット
	memset(&m_oNasaMonitoringCounter, 0, sizeof(m_oNasaMonitoringCounter));
	memset(&m_oHcorMonitoringCounter, 0, sizeof(m_oHcorMonitoringCounter));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::StopAlxxx
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アラーム停止処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::StopAlxxx()
{
	// アラーム音停止
	PlaySound(NULL, NULL, 0);

	m_cButtonStopAlxxx.EnableWindow(FALSE);
	bAlertOn = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonReceive
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Start/Stopボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonReceive()
{
	if (m_bRunning)
		StopReceive();
	else
		StartReceive();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonReadFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信ファイル読み込みボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonReadFile()
{
	CFileDialog oFileDlg(TRUE, "s", "",
			OFN_HIDEREADONLY | OFN_NOCHANGEDIR,
			"Receive File (*.s)|*.s||", this, 0);

	if (oFileDlg.DoModal() == IDOK) {
		CGraphFrm *pWnd = new CGraphFrm();
		pWnd->DispGraph(
				oFileDlg.GetPathName(),
				m_oSettings.oGraph.oGraphLine,
				oFileDlg.GetFileName(),
				this,
				m_oSettings.oGraph.fTempRange1,
				m_oSettings.oGraph.fTempRange2,
				m_oSettings.oGraph.fVoltageRange1,
				m_oSettings.oGraph.fVoltageRange2);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnTimer
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイマメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT_PTR		nIDEvent			[I] タイマイベントID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnTimer(UINT_PTR nIDEvent)
{
	if (nIDEvent == 0) {
		if (m_bRunning) {
			// NASA用受信Idleチェック
			if (++m_nNasaReceiveIdleCounter > RECEIVE_IDLE_COUNT)
				SetNasaReceiveStatus(FALSE);

			// NASA用デジタルデータ有効チェック
			if (++m_nNasaDigitalIdleCounter > RECEIVE_IDLE_COUNT)
				SetNasaEnableDigitalData(FALSE);

			// NASA用アナログデータ有効チェック
			if (++m_nNasaAnalogIdleCounter > RECEIVE_IDLE_COUNT)
				SetNasaEnableAnalogData(FALSE);

			// HCOR用受信Idleチェック
			if (++m_nHcorReceiveIdleCounter > RECEIVE_IDLE_COUNT)
				SetHcorReceiveStatus(FALSE);

			// HCOR用デジタルデータ有効チェック
			if (++m_nHcorDigitalIdleCounter > RECEIVE_IDLE_COUNT)
				SetHcorEnableDigitalData(FALSE);

			// HCOR用アナログデータ有効チェック
			if (++m_nHcorAnalogIdleCounter > RECEIVE_IDLE_COUNT)
				SetHcorEnableAnalogData(FALSE);
		}
	}

	CDialog::OnTimer(nIDEvent);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonNasaGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用Graph Windowボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonNasaGraphWindow()
{
	// NASA受信データグラフ表示
	CGraphFrm *pWnd = new CGraphFrm();
	pWnd->DispGraph(
				m_oNasaGraphData,
				m_oSettings.oGraph.oGraphLine,
				"NASA Real Time Data",
				this,
				m_oSettings.oGraph.fTempRange1,
				m_oSettings.oGraph.fTempRange2,
				m_oSettings.oGraph.fVoltageRange1,
				m_oSettings.oGraph.fVoltageRange2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonHcorGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用Graph Windowボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonHcorGraphWindow()
{
	// HCOR受信データグラフ表示
	CGraphFrm *pWnd = new CGraphFrm();
	pWnd->DispGraph(
				m_oHcorGraphData,
				m_oSettings.oGraph.oGraphLine,
				"HCOR Data",
				this,
				m_oSettings.oGraph.fTempRange1,
				m_oSettings.oGraph.fTempRange2,
				m_oSettings.oGraph.fVoltageRange1,
				m_oSettings.oGraph.fVoltageRange2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::ReadSettings
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイル読み込み処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::ReadSettings()
{
	static const char aSession[] = "Settings";
	static const char *aGraphKind[N_GRAPH] = {"X", "Y", "Z", "SB", "Heater"};
	static const char *aTempKind[N_TEMP] = {"X", "Y", "Z", "SB"};
	CString sIniPathName;
	CString sKey;

	// iniパス名取得
	CGeneral::GetIniPathName(sIniPathName);

	// NASA用設定値取得
	m_oSettings.oNasa.nUdpPort = ::GetPrivateProfileInt(aSession, "NasaUdpPort", PORT_NASA, sIniPathName);
	m_oSettings.oNasa.bAutoFileName = ::GetPrivateProfileInt(aSession, "NasaAutoFileName", 1, sIniPathName);
	m_oSettings.oNasa.bPysicalQuantity = ::GetPrivateProfileInt(aSession, "NasaPysicalQuantity", 1, sIniPathName);
	m_oSettings.oNasa.bAccumulate = ::GetPrivateProfileInt(aSession, "NasaAccumulate", 0, sIniPathName);

	// HCOR用設定値取得
	m_oSettings.oHcor.nUdpPort = ::GetPrivateProfileInt(aSession, "HcorUdpPort", PORT_HCOR, sIniPathName);
	m_oSettings.oHcor.bAutoFileName = ::GetPrivateProfileInt(aSession, "HcorAutoFileName", 1, sIniPathName);
	m_oSettings.oHcor.bPysicalQuantity = ::GetPrivateProfileInt(aSession, "HcorPysicalQuantity", 1, sIniPathName);
	m_oSettings.oHcor.bAccumulate = ::GetPrivateProfileInt(aSession, "HcorAccumulate", 0, sIniPathName);

	// グラフ設定値取得
	m_oSettings.oGraph.nTimeSpan = ::GetPrivateProfileInt(aSession, "GraphTimeSpan", 600, sIniPathName);
	m_oSettings.oGraph.fTempRange1 = CGeneral::GetPrivateProfileDouble(aSession, "GraphTemperatureRange1", 0, sIniPathName);
	m_oSettings.oGraph.fTempRange2 = CGeneral::GetPrivateProfileDouble(aSession, "GraphTemperatureRange2", 100, sIniPathName);
	m_oSettings.oGraph.fVoltageRange1 = CGeneral::GetPrivateProfileDouble(aSession, "GeaphHeaterVoltageRange1", 0, sIniPathName);
	m_oSettings.oGraph.fVoltageRange2 = CGeneral::GetPrivateProfileDouble(aSession, "GeaphHeaterVoltageRange2", 30, sIniPathName);

	// Data Line設定値取得
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			sKey.Format("GraphUnit%d%s", nUnit + 1, aGraphKind[nGraph]);
			m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph] = ::GetPrivateProfileInt(aSession, sKey, 1, sIniPathName);
		}
	}

	// 温度・電圧監視設定値取得
	m_oSettings.oMonitoring.bEnableTempChk = ::GetPrivateProfileInt(aSession, "EnableTempChk", 1, sIniPathName);
	m_oSettings.oMonitoring.bEnableVoltageChk = ::GetPrivateProfileInt(aSession, "EnableVoltageChk", 1, sIniPathName);
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			sKey.Format("NormalTempRangeUnit%d%s1", nUnit + 1, aTempKind[nTemp]);
			m_oSettings.oMonitoring.fNormalTempRange1[nUnit][nTemp] = CGeneral::GetPrivateProfileDouble(aSession, sKey, 53, sIniPathName);

			sKey.Format("NormalTempRangeUnit%d%s2", nUnit + 1, aTempKind[nTemp]);
			m_oSettings.oMonitoring.fNormalTempRange2[nUnit][nTemp] = CGeneral::GetPrivateProfileDouble(aSession, sKey, 73, sIniPathName);
		}

		sKey.Format("NormalVoltageRangeUnit%dL", nUnit + 1);
		m_oSettings.oMonitoring.fNormalVoltageRange1[nUnit] = CGeneral::GetPrivateProfileDouble(aSession, sKey, 20, sIniPathName);

		sKey.Format("NormalVoltageRangeUnit%dH", nUnit + 1);
		m_oSettings.oMonitoring.fNormalVoltageRange2[nUnit] = CGeneral::GetPrivateProfileDouble(aSession, sKey, 50, sIniPathName);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::WriteSettings
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイル書き込み処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::WriteSettings()
{
	static const char aSession[] = "Settings";
	static const char *aGraphKind[N_GRAPH] = {"X", "Y", "Z", "SB", "Heater"};
	static const char *aTempKind[N_TEMP] = {"X", "Y", "Z", "SB"};
	CString sIniPathName;
	CString sKey;

	// iniパス名取得
	CGeneral::GetIniPathName(sIniPathName);

	// NASA用設定値保存
	CGeneral::WritePrivateProfileInt(aSession, "NasaUdpPort", m_oSettings.oNasa.nUdpPort, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "NasaAutoFileName", m_oSettings.oNasa.bAutoFileName, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "NasaPysicalQuantity", m_oSettings.oNasa.bPysicalQuantity, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "NasaAccumulate", m_oSettings.oNasa.bAccumulate, sIniPathName);

	// HCOR用設定値保存
	CGeneral::WritePrivateProfileInt(aSession, "HcorUdpPort", m_oSettings.oHcor.nUdpPort, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "HcorAutoFileName", m_oSettings.oHcor.bAutoFileName, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "HcorPysicalQuantity", m_oSettings.oHcor.bPysicalQuantity, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "HcorAccumulate", m_oSettings.oHcor.bAccumulate, sIniPathName);

	// グラフ設定値保存
	CGeneral::WritePrivateProfileInt(aSession, "GraphTimeSpan", m_oSettings.oGraph.nTimeSpan, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GraphTemperatureRange1", m_oSettings.oGraph.fTempRange1, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GraphTemperatureRange2", m_oSettings.oGraph.fTempRange2, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GeaphHeaterVoltageRange1", m_oSettings.oGraph.fVoltageRange1, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GeaphHeaterVoltageRange2", m_oSettings.oGraph.fVoltageRange2, sIniPathName);

	// Data Line設定値保存
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			sKey.Format("GraphUnit%d%s", nUnit + 1, aGraphKind[nGraph]);
			CGeneral::WritePrivateProfileInt(aSession, sKey, m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph], sIniPathName);
		}
	}

	// 温度・電圧監視設定値保存
	CGeneral::WritePrivateProfileInt(aSession, "EnableTempChk", m_oSettings.oMonitoring.bEnableTempChk, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "EnableVoltageChk", m_oSettings.oMonitoring.bEnableVoltageChk, sIniPathName);
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			sKey.Format("NormalTempRangeUnit%d%s1", nUnit + 1, aTempKind[nTemp]);
			CGeneral::WritePrivateProfileDouble(aSession, sKey, m_oSettings.oMonitoring.fNormalTempRange1[nUnit][nTemp], sIniPathName);

			sKey.Format("NormalTempRangeUnit%d%s2", nUnit + 1, aTempKind[nTemp]);
			CGeneral::WritePrivateProfileDouble(aSession, sKey, m_oSettings.oMonitoring.fNormalTempRange2[nUnit][nTemp], sIniPathName);
		}

		sKey.Format("NormalVoltageRangeUnit%dL", nUnit + 1);
		CGeneral::WritePrivateProfileDouble(aSession, sKey, m_oSettings.oMonitoring.fNormalVoltageRange1[nUnit], sIniPathName);

		sKey.Format("NormalVoltageRangeUnit%dH", nUnit + 1);
		CGeneral::WritePrivateProfileDouble(aSession, sKey, m_oSettings.oMonitoring.fNormalVoltageRange2[nUnit], sIniPathName);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフ表示チェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckGraph()
{
	// Data Lineの設定値取得
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph] = m_cCheckGraph[nUnit][nGraph];
		}
	}

	// グラフ表示
	DispNasaGraph();
	DispHcorGraph();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckNasaPq
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用Physical Quantityボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckNasaPq()
{
	m_oSettings.oNasa.bPysicalQuantity = m_cCheckNasaPQ;

	SetNasaPysicalQuantity();

	if (m_bValidNasaDigitalData)
		DispNasaDigitalData(m_oNasaLastDigitalData);

	if (m_bValidNasaAnalogData)
		DispNasaAnalogData(m_oNasaLastAnalogData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckHcorPq
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用Physical Quantityボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckHcorPq()
{
	m_oSettings.oHcor.bPysicalQuantity = m_cCheckHcorPQ;

	SetHcorPysicalQuantity();

	if (m_bValidHcorDigitalData)
		DispHcorDigitalData(m_oHcorLastDigitalData);

	if (m_bValidHcorAnalogData)
		DispHcorAnalogData(m_oHcorLastAnalogData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditNasaPort
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用UDPポート入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditNasaPort()
{
	if (!m_cEditNasaPort.IsEmpty())
		m_oSettings.oNasa.nUdpPort = m_cEditNasaPort;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditHcorPort
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用UDPポート入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditHcorPort()
{
	if (!m_cEditHcorPort.IsEmpty())
		m_oSettings.oHcor.nUdpPort = m_cEditHcorPort;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditGraphTimeSpan
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフのタイムスパン入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditGraphTimeSpan()
{
	if (!m_cEditGraphTimeSpan.IsEmpty()) {
		m_oSettings.oGraph.nTimeSpan = m_cEditGraphTimeSpan;

		DispNasaGraph();
		DispHcorGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditGraphTempRange1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフの温度レンジ下限入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditGraphTempRange1()
{
	if (!m_cEditGraphTempRange1.IsEmpty()) {
		m_oSettings.oGraph.fTempRange1 = m_cEditGraphTempRange1;

		DispNasaGraph();
		DispHcorGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditGraphTempRange2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフの温度レンジ上限入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditGraphTempRange2()
{
	if (!m_cEditGraphTempRange2.IsEmpty()) {
		m_oSettings.oGraph.fTempRange2 = m_cEditGraphTempRange2;

		DispNasaGraph();
		DispHcorGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditGraphVoltageRange1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフのヒータ電圧下限入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditGraphVoltageRange1()
{
	if (!m_cEditGraphVoltageRange1.IsEmpty()) {
		m_oSettings.oGraph.fVoltageRange1 = m_cEditGraphVoltageRange1;

		DispNasaGraph();
		DispHcorGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnEnChangeEditGraphVoltageRange2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		グラフのヒータ電圧上限入力変更処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditGraphVoltageRange2()
{
	if (!m_cEditGraphVoltageRange2.IsEmpty()) {
		m_oSettings.oGraph.fVoltageRange2 = m_cEditGraphVoltageRange2;

		DispNasaGraph();
		DispHcorGraph();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetNasaPysicalQuantity
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用Pysical Quantity表示設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetNasaPysicalQuantity()
{
	if (m_oSettings.oNasa.bPysicalQuantity) {
		m_cStaticNasaAcc[ACC_X] = "X[mG]";
		m_cStaticNasaAcc[ACC_Y] = "Y[mG]";
		m_cStaticNasaAcc[ACC_Z] = "Z[mG]";
		m_cStaticNasaAnalog[TEMP_X] = "X[degC]";
		m_cStaticNasaAnalog[TEMP_Y] = "Y[degC]";
		m_cStaticNasaAnalog[TEMP_Z] = "Z[degC]";
		m_cStaticNasaAnalog[TEMP_SB] = "S/B[degC]";
		m_cStaticNasaAnalogHeater = "Heater[V]";
	} else {
		m_cStaticNasaAcc[ACC_X] = "X[dec]";
		m_cStaticNasaAcc[ACC_Y] = "Y[dec]";
		m_cStaticNasaAcc[ACC_Z] = "Z[dec]";
		m_cStaticNasaAnalog[TEMP_X] = "X[dec]";
		m_cStaticNasaAnalog[TEMP_Y] = "Y[dec]";
		m_cStaticNasaAnalog[TEMP_Z] = "Z[dec]";
		m_cStaticNasaAnalog[TEMP_SB] = "S/B[dec]";
		m_cStaticNasaAnalogHeater = "Heater[dec]";
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetHcorPysicalQuantity
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用Pysical Quantity表示設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetHcorPysicalQuantity()
{
	if (m_oSettings.oHcor.bPysicalQuantity) {
		m_cStaticHcorAcc[ACC_X] = "X[mG]";
		m_cStaticHcorAcc[ACC_Y] = "Y[mG]";
		m_cStaticHcorAcc[ACC_Z] = "Z[mG]";
		m_cStaticHcorAnalog[TEMP_X] = "X[degC]";
		m_cStaticHcorAnalog[TEMP_Y] = "Y[degC]";
		m_cStaticHcorAnalog[TEMP_Z] = "Z[degC]";
		m_cStaticHcorAnalog[TEMP_SB] = "S/B[degC]";
		m_cStaticHcorAnalogHeater = "Heater[V]";
	} else {
		m_cStaticHcorAcc[ACC_X] = "X[dec]";
		m_cStaticHcorAcc[ACC_Y] = "Y[dec]";
		m_cStaticHcorAcc[ACC_Z] = "Z[dec]";
		m_cStaticHcorAnalog[TEMP_X] = "X[dec]";
		m_cStaticHcorAnalog[TEMP_Y] = "Y[dec]";
		m_cStaticHcorAnalog[TEMP_Z] = "Z[dec]";
		m_cStaticHcorAnalog[TEMP_SB] = "S/B[dec]";
		m_cStaticHcorAnalogHeater = "Heater[dec]";
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetFrameIdText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		フレーム構造識別IDの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nFrameId			[I] フレーム構造識別ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetFrameIdText(UINT nFrameId)
{
	CString sText;

	switch (nFrameId) {
	case 0x01:
		sText = "通常ｼｰｹﾝｽLoﾚﾝｼﾞ計測ﾃﾞｰﾀ";
		break;
	case 0x02:
		sText = "通常ｼｰｹﾝｽHiﾚﾝｼﾞ計測ﾃﾞｰﾀ";
		break;
	case 0x03:
		sText = "通常ｼｰｹﾝｽExﾚﾝｼﾞ計測ﾃﾞｰﾀ";
		break;
	case 0x11:
		sText = "連続ｼｰｹﾝｽLoﾚﾝｼﾞ計測ﾃﾞｰﾀ";
		break;
	case 0x12:
		sText = "連続ｼｰｹﾝｽHiﾚﾝｼﾞ計測ﾃﾞｰﾀ";
		break;
	case 0x13:
		sText = "連続ｼｰｹﾝｽExﾚﾝｼﾞ計測ﾃﾞｰﾀ";
		break;
	default:
		sText.Format("0x%02x", nFrameId);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMeasurementModeText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		計測モードの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nMeasurentMode			[I] 計測モード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasurementModeText(UINT nMeasurentMode)
{
	CString sText;

	switch (nMeasurentMode) {
	case 0x00:
		sText = "通常シーケンスモード";
		break;
	case 0x01:
		sText = "連続シーケンスモード";
		break;
	default:
		sText.Format("0x%02x", nMeasurentMode);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMeasurementRangeText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		計測レンジの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nMeasurementRange			[I] 計測レンジ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasurementRangeText(UINT nMeasurementRange)
{
	CString sText;

	switch (nMeasurementRange) {
	case 0x01:
		sText = "Loレンジ計測データ";
		break;
	case 0x02:
		sText = "Hiレンジ計測データ";
		break;
	case 0x03:
		sText = "Exレンジ計測データ";
		break;
	default:
		sText.Format("0x%02x", nMeasurementRange);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMeasurementRangeText2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		計測レンジの表示用テキスト（短縮型）取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nMeasurementRange			[I] 計測レンジ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasurementRangeText2(UINT nMeasurementRange)
{
	CString sText;

	switch (nMeasurementRange) {
	case 0x01:
		sText = "Lo";
		break;
	case 0x02:
		sText = "Hi";
		break;
	case 0x03:
		sText = "Ex";
		break;
	default:
		sText.Format("0x%02x", nMeasurementRange);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMeasureModeIdText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MMEステータスのmeasureModeIDの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nMeasureModeId			[I] measureModeID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasureModeIdText(UINT nMeasureModeId)
{
	CString sText;

	switch (nMeasureModeId) {
	case 0x00:
		sText = "normalMode";
		break;
	case 0x01:
		sText = "continualMode";
		break;
	default:
		sText.Format("0x%02x", nMeasureModeId);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMeasureStatusText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MMEステータスのmeasureStatusの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nMeasureStatus			[I] measureStatus
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasureStatusText(UINT nMeasureStatus)
{
	CString sText;

	switch (nMeasureStatus) {
	case 0x00:
		sText = "offMeasure";
		break;
	case 0x01:
		sText = "onMeasure";
		break;
	case 0x02:
		sText = "waitMeasure";
		break;
	case 0x03:
		sText = "waitStatus";
		break;
	default:
		sText.Format("0x%02x", nMeasureStatus);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetCurrentRangeIdText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MMEステータスのcurrentRangeIDの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nCurrentRangeId			[I] currentRangeID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetCurrentRangeIdText(UINT nCurrentRangeId)
{
	CString sText;

	switch (nCurrentRangeId) {
	case 0x01:
		sText = "LoRange";
		break;
	case 0x02:
		sText = "HiRange";
		break;
	case 0x03:
		sText = "ExtRange";
		break;
	default:
		sText.Format("0x%04x", nCurrentRangeId);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetBitStatusTypeText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		前回HALT時エラー情報のBit Statusの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nBitStatusType			[I] 前回HALT時エラー情報のBit Status
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetBitStatusTypeText(UINT nBitStatusType)
{
	CString sText;

	switch (nBitStatusType) {
	case 0x01:
		sText = "ALU＆ShiftUnitテスト";
		break;
	case 0x02:
		sText = "RAM Bank identification & address test";
		break;
	case 0x03:
		sText = "RAMパターンテスト（ROM to RAM Copy）";
		break;
	case 0x04:
		sText = "RAMパターンテスト（overall）";
		break;
	case 0x05:
		sText = "EDACテスト";
		break;
	case 0x06:
		sText = "Memory Protectionテスト";
		break;
	case 0x07:
		sText = "Interrupt mechnismテスト";
		break;
	case 0x08:
		sText = "Timersテスト";
		break;
	case 0x09:
		sText = "FPUテスト";
		break;
	case 0x0a:
		sText = "VMEテスト";
		break;
	case 0x0b:
		sText = "Checksum Vertification";
		break;
	default:
		sText.Format("0x%02x", nBitStatusType);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetBitStatusNatureText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		前回HALT時エラー情報のnatureの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nBitStatusNature			[I] 前回HALT時エラー情報のnature
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetBitStatusNatureText(UINT nBitStatusNature)
{
	CString sText;

	switch (nBitStatusNature) {
	case 0x00000000:
		sText = "OFFLINE";
		break;
	case 0xffffffff:
		sText = "ONLINE";
		break;
	default:
		sText.Format("0x%08x", nBitStatusNature);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetHaltInfoErrorCodeText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		前回HALT時エラー情報のエラーコードの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nHaltInfoErrorCode			[I] 前回HALT時エラー情報のエラーコード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetHaltInfoErrorCodeText(UINT nHaltInfoErrorCode)
{
	CString sText;

	switch (nHaltInfoErrorCode) {
	case 0x0201:
		sText = "BUS_INSTR_ACCESS";
		break;
	case 0x0202:
		sText = "BUS_ALIGN";
		break;
	case 0x0203:
		sText = "BUS_DATA_ACCESS";
		break;
	case 0x0204:
		sText = "ILL_ILLINSTR_FAULT";
		break;
	case 0x0205:
		sText = "ILL_PRIVINSTR_FAULT";
		break;
	case 0x0206:
		sText = "ILL_COPROC_DISABLED";
		break;
	case 0x0207:
		sText = "ILL_COPROC_EXCPTN";
		break;
	case 0x0208:
		sText = "ILL_TRAP_FAULT(0)";
		break;
	case 0x0209:
		sText = "FPE_FPA_ENABLE";
		break;
	case 0x020a:
		sText = "FPE_FPA_ERROR";
		break;
	case 0x020b:
		sText = "FPE_INTDIV_TRAP";
		break;
	case 0x020c:
		sText = "EMT_TAG";
		break;
	default:
		sText.Format("0x%04x", nHaltInfoErrorCode);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetErrorCodeText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		エラーステータスのエラーコードの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nErrorCode			[I] エラーコード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetErrorCodeText(UINT nErrorCode)
{
	CString sText;

	switch (nErrorCode) {
	case 0x00:
		sText = "正常";
		break;
	case 0x01:
		sText = "ウォッチドッグタイマタイムアウト";
		break;
	case 0x02:
		sText = "例外割込み発生";
		break;
	case 0x03:
		sText = "パケット送受信エラー";
		break;
	case 0x04:
		sText = "ハードウェアエラー";
		break;
	case 0x05:
		sText = "ソフトウェアエラー";
		break;
	default:
		sText.Format("0x%02x", nErrorCode);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetSubCodeText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		エラーステータスのサブコードの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT	nErrorCode			[I] エラーコード
//		UINT	nSubCode			[I] サブコード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetSubCodeText(UINT nErrorCode, UINT nSubCode)
{
	CString sText;

	switch (nErrorCode) {
	case 0x02:
		switch (nSubCode) {
		case 0x01:
			sText = "bus error on instruction fetch";
			break;
		case 0x02:
			sText = "address error";
			break;
		case 0x03:
			sText = "bus error on data access";
			break;
		case 0x04:
			sText = "illegal instruction";
			break;
		case 0x05:
			sText = "privilege violation";
			break;
		case 0x06:
			sText = "coprocessor disabled";
			break;
		case 0x07:
			sText = "coprocessor exception";
			break;
		case 0x08:
			sText = "uninitialized user trap";
			break;
		case 0x09:
			sText = "floating point disabled";
			break;
		case 0x0a:
			sText = "floating point exception";
			break;
		case 0x0b:
			sText = "zero divide";
			break;
		case 0x0c:
			sText = "tag overflow";
			break;
		}
		break;
	case 0x03:
		switch (nSubCode) {
		case 0x00:
			sText = "ソケットが生成できない";
			break;
		}
		break;
	case 0x04:
		switch (nSubCode) {
		case 0x00:
			sText = "AUXクロックが生成できない";
			break;
		case 0x01:
			sText = "ウォームアップ異常";
			break;
		case 0x02:
			sText = "ON/OFFステータス異常";
			break;
		case 0x03:
			sText = "レンジステータス異常";
			break;
		case 0x04:
			sText = "ヒータ系統異常";
			break;
		}
		break;
	case 0x05:
		switch (nSubCode) {
		case 0x01:
			sText = "Sifの割込みが登録できない";
			break;
		case 0x02:
			sText = "Sifの割込みがEnableできない";
			break;
		case 0x03:
			sText = "現在時刻が計測開始時刻を超えていた";
			break;
		case 0x04:
			sText = "タスクの初期化が終了しない";
			break;
		}		
	}

	if (sText.IsEmpty())
		sText.Format("0x%02x", nSubCode);

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetStatusWordText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ステータスワードの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SStatusWord		&oStatusWord			[I] ステータスワード情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetStatusWordText(const SStatusWord &oStatusWord)
{
	CString sText;

	sText.Format("1/%s/%s, 2/%s/%s, 3/%s/%s",
			oStatusWord.aUnit[UNIT_1].nOnOffStatus ? "ON" : "OFF",
			GetMeasurementRangeText2(oStatusWord.aUnit[UNIT_1].nRangeStatus),
			oStatusWord.aUnit[UNIT_2].nOnOffStatus ? "ON" : "OFF",
			GetMeasurementRangeText2(oStatusWord.aUnit[UNIT_2].nRangeStatus),
			oStatusWord.aUnit[UNIT_3].nOnOffStatus ? "ON" : "OFF",
			GetMeasurementRangeText2(oStatusWord.aUnit[UNIT_3].nRangeStatus));

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetUserHeaderCommonText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ユーザヘッダ共通部の表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader			[I] ユーザヘッダ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetUserHeaderCommonText(const SUserHeader &oUserHeader)
{
	CString sText;

	sText.Format(
			"ﾍﾟｲﾛｰﾄﾞ識別ID：0x%02x，"
			"ﾃﾞｰﾀ識別ID：0x%02x\r\n"
			"ﾌﾚｰﾑ構造識別ID：%s\r\n"
			"終端ｾｸﾞﾒﾝﾄ識別：%d，"
			"ｾｸﾞﾒﾝﾄｶｳﾝﾄ：%d\r\n"
			"ﾀｲﾑｽﾀﾝﾌﾟ：%s\r\n"
			"Fine Time：0x%04x",
			oUserHeader.nPayloadId,
			oUserHeader.nDataId,
			GetFrameIdText(oUserHeader.nFrameId),
			oUserHeader.nEndSegment,
			oUserHeader.nSegmentCount,
			oUserHeader.oTimestamp.FormatGmt("%Y/%m/%d %H:%M:%S"),
			oUserHeader.nFineTime);

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetLasttimeHaltText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		前回HALT時エラー情報の表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SLasttimeHalt		&oLasttimeHalt			[I] 前回HALT時エラー情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		表示用テキスト
//*****************************************************************************************************
CString CMmeRecvDlg::GetLasttimeHaltText(const SLasttimeHalt &oLasttimeHalt)
{
	CString sText;

	if (oLasttimeHalt.bValidData) {
		sText.Format("前回HALT時ｴﾗｰ情報：\r\n"
				"　ｾｯｼｮﾝ?：0x%08x\r\n"
				"　type：%s\r\n"
				"　nature：%s\r\n"
				"　タスク文字列ｺｰﾄﾞ：%s\r\n"
				"　エラーコード：%s\r\n"
				"　時刻：%s",
				oLasttimeHalt.oBitStatus.nSessionNumber,
				GetBitStatusTypeText(oLasttimeHalt.oBitStatus.nType),
				GetBitStatusNatureText(oLasttimeHalt.oBitStatus.nNature),
				oLasttimeHalt.oHaltInfo.sTaskName,
				GetHaltInfoErrorCodeText(oLasttimeHalt.oHaltInfo.nErrorCode),
				oLasttimeHalt.oHaltInfo.oErrorTime.FormatGmt("%Y/%m/%d %H:%M:%S"));
	} else
		sText = "前回HALT時ｴﾗｰ情報：No data";

	return sText;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetUserHeaderText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		テレメトリパケットのユーザヘッダの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader			[I] ユーザヘッダ情報
//		CString			&sText					[O] 表示用テキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::GetUserHeaderText(const SUserHeader &oUserHeader, CString &sText)
{
	sText.Format(
			"%s\r\n"
			"ﾃﾞｰﾀ個数：%d\r\n"
			"計測ﾓｰﾄﾞ：%s，"
			"計測ﾚﾝｼﾞ：%s\r\n"
			"ｻﾝﾌﾟﾘﾝｸﾞ周波数：%d\r\n"
			"ｽﾃｰﾀｽﾜｰﾄﾞ：%s",
			GetUserHeaderCommonText(oUserHeader),
			oUserHeader.oTelemetry.nDataNum,
			GetMeasurementModeText(oUserHeader.oTelemetry.nMeasurementMode),
			GetMeasurementRangeText(oUserHeader.oTelemetry.nMeasurementRange),
			oUserHeader.oTelemetry.nSamplingRate,
			GetStatusWordText(oUserHeader.oTelemetry.oStatusWord));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMmeStatusDataText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MMEステータスパケットの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader			[I] ユーザヘッダ情報
//		SMmeStatusData	&oMmeStatusData			[I] MMEステータス情報
//		CString			&sText					[O] 表示用テキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::GetMmeStatusDataText(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData, CString &sText)
{
	sText.Format(
			"%s\r\n"
			"ﾃﾞｰﾀ個数：%d\r\n"
			"ｽﾃｰﾀｽﾜｰﾄﾞ：%s\r\n"
			"制御ﾓｰﾄﾞﾃｰﾌﾞﾙ変数：%s\r\n"
			"measureModeID：%s\r\n"
			"measureStatus：%s\r\n"
			"currentRangeID：%s\r\n"
			"ﾀﾞｳﾝﾘﾝｸﾊﾟｽ変数名：%s\r\n"
			"経路：%d\r\n"
			"基本ﾀｲﾏｶｳﾝﾄ変数名：%s\r\n"
			"基本ﾀｲﾏｶｳﾝﾄ：0x%08x\r\n"
			"計測開始時刻変数名：%s\r\n"
			"計測開始設定値：0x%08x\r\n"
			"計測終了時刻変数名：%s\r\n"
			"計測終了設定値：0x%08x\r\n"
			"%s",
			GetUserHeaderCommonText(oUserHeader),
			oUserHeader.oMmeStatus.nDataNum,
			GetStatusWordText(oUserHeader.oMmeStatus.oStatusWord),
			oMmeStatusData.sContrxxxodeTableName,
			GetMeasureModeIdText(oMmeStatusData.nMeasureModeId),
			GetMeasureStatusText(oMmeStatusData.nMeasureStatus),
			GetCurrentRangeIdText(oMmeStatusData.nCurrentRangeId),
			oMmeStatusData.sDownlinkPathName,
			oMmeStatusData.nDownlinkPathData,
			oMmeStatusData.sTimerCountName,
			oMmeStatusData.nTimerCountData,
			oMmeStatusData.sMeasStartTimeName,
			oMmeStatusData.nMeasStartTimeData,
			oMmeStatusData.sMeasEndTimeName,
			oMmeStatusData.nMeasEndTimeData,
			GetLasttimeHaltText(oMmeStatusData.oLasttimeHalt));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetErrorStatusDataText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		エラーステータスパケットの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader			&oUserHeader			[I] ユーザヘッダ情報
//		SErrorStatusData	&oErrorStatusData		[I] エラーステータス情報
//		CString				&sText					[O] 表示用テキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::GetErrorStatusDataText(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData, CString &sText)
{
	CString sCommonText;

	sCommonText.Format(
			"%s\r\n"
			"ｴﾗｰｺｰﾄﾞ：%s\r\n"
			"ｻﾌﾞｺｰﾄﾞ：%s\r\n"
			"ﾃﾞｰﾀ個数：%d\r\n"
			"ｽﾃｰﾀｽﾜｰﾄﾞ：%s",
			GetUserHeaderCommonText(oUserHeader),
			GetErrorCodeText(oUserHeader.oErrorStatus.nErrorCode),
			GetSubCodeText(oUserHeader.oErrorStatus.nErrorCode, oUserHeader.oErrorStatus.nSubCode),
			oUserHeader.oErrorStatus.nDataNum,
			GetStatusWordText(oUserHeader.oErrorStatus.oStatusWord));

	switch (oUserHeader.oErrorStatus.nErrorCode) {
	case 0x02:
		sText.Format(
				"%s\r\n"
				"ﾀｽｸ名称：%s\r\n"
				"ｴﾗｰﾒｯｾｰｼﾞ：%s",
				sCommonText,
				oErrorStatusData.sTaskName,
				oErrorStatusData.sErrorMessage);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		sText.Format(
				"%s\r\n"
				"ｴﾗｰﾒｯｾｰｼﾞ：%s",
				sCommonText,
				oErrorStatusData.sErrorMessage);
		break;
	default:
		sText = sCommonText;
		break;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetMessageDataText
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メッセージパケットの表示用テキスト取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		SUserHeader		&oUserHeader			[I] ユーザヘッダ情報
//		SMessageData	&oMessageData			[I] メッセージ情報
//		CString			&sText					[O] 表示用テキスト
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::GetMessageDataText(const SUserHeader &oUserHeader, const SMessageData &oMessageData, CString &sText)
{
	sText.Format(
			"%s\r\n"
			"ﾃﾞｰﾀ個数：%d\r\n"
			"ｽﾃｰﾀｽﾜｰﾄﾞ：%s\r\n"
			"ﾒｯｾｰｼﾞ：%s\r\n"
			"%s",
			GetUserHeaderCommonText(oUserHeader),
			oUserHeader.oMessage.nDataNum,
			GetStatusWordText(oUserHeader.oMessage.oStatusWord),
			oMessageData.sMessage,
			GetLasttimeHaltText(oMessageData.oLasttimeHalt));
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispNasaGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaGraph()
{
	CTime oStartTime;
	CTime oEndTime;

	if (m_oNasaGraphData.GetDataNum() != 0) {
		oEndTime = m_oNasaGraphData.GetGraphData(m_oNasaGraphData.GetDataNum() - 1).oTimestamp;
		oStartTime = oEndTime - CTimeSpan(m_oSettings.oGraph.nTimeSpan);
		m_cButtonNasaGraph.EnableWindow(TRUE);
	} else
		m_cButtonNasaGraph.EnableWindow(FALSE);

	m_cStaticNasaGraph.DispGraph(
			m_oNasaGraphData,
			m_oSettings.oGraph.oGraphLine,
			(double)oStartTime.GetTime(),
			(double)oStartTime.GetTime() + m_oSettings.oGraph.nTimeSpan,
			m_oSettings.oGraph.fTempRange1, m_oSettings.oGraph.fTempRange2,
			m_oSettings.oGraph.fVoltageRange1, m_oSettings.oGraph.fVoltageRange2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::DispHcorGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用グラフ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorGraph()
{
	CTime oStartTime;
	CTime oEndTime;

	if (m_oHcorGraphData.GetDataNum() != 0) {
		oEndTime = m_oHcorGraphData.GetGraphData(m_oHcorGraphData.GetDataNum() - 1).oTimestamp;
		oStartTime = oEndTime - CTimeSpan(m_oSettings.oGraph.nTimeSpan);
		m_cButtonHcorGraph.EnableWindow(TRUE);
	} else
		m_cButtonHcorGraph.EnableWindow(FALSE);

	m_cStaticHcorGraph.DispGraph(
			m_oHcorGraphData,
			m_oSettings.oGraph.oGraphLine,
			(double)oStartTime.GetTime(),
			(double)oStartTime.GetTime() + m_oSettings.oGraph.nTimeSpan,
			m_oSettings.oGraph.fTempRange1, m_oSettings.oGraph.fTempRange2,
			m_oSettings.oGraph.fVoltageRange1, m_oSettings.oGraph.fVoltageRange2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckNasaAutoFileName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用Auto File Nameチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckNasaAutoFileName()
{
	m_oSettings.oNasa.bAutoFileName = m_cCheckNasaAutoFileName;
	m_cEditNasaFileName.EnableWindow(!m_oSettings.oNasa.bAutoFileName);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckHcorAutoFileName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用Auto File Nameチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckHcorAutoFileName()
{
	m_oSettings.oHcor.bAutoFileName = m_cCheckHcorAutoFileName;
	m_cEditHcorFileName.EnableWindow(!m_oSettings.oHcor.bAutoFileName);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckEnableTempChk
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Enable Temp Chkチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckEnableTempChk()
{
	m_oSettings.oMonitoring.bEnableTempChk = m_cCheckEnableTempChk;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedCheckEnableVoltageChk
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Enable Voltage Chkチェックボックスクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckEnableVoltageChk()
{
	m_oSettings.oMonitoring.bEnableVoltageChk = m_cCheckEnableVoltageChk;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::Monitoring
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		温度・電圧範囲チェック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CTime				&oTimestamp			[I] タイムスタンプ
//		SAnalogData			&oAnalogData		[I] アナログデータ情報
//		SStatusWord			&oStatusWord		[I] ステータスワード情報
//		SMonitoringCounter	&oMonitoringCounter	[I] モニタリングカウンタ
//		LPCTSTR				pKeiro				[I] 経路名称
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::Monitoring(const CTime &oTimestamp, const SAnalogData &oAnalogData, const SStatusWord &oStatusWord, SMonitoringCounter &oMonitoringCounter, LPCTSTR pKeiro)
{
	const char *aTemp[N_TEMP] = {"X", "Y", "Z", "S/B"};
	CString sMsg;
	CStringArray aMsg;

	// 温度範囲チェック
	if (m_oSettings.oMonitoring.bEnableTempChk) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			if (oStatusWord.aUnit[nUnit].nOnOffStatus) {
				for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
					if (oAnalogData.aUnit[nUnit].fTemp[nTemp] < m_oSettings.oMonitoring.fNormalTempRange1[nUnit][nTemp]) {
						if (++oMonitoringCounter.aTempCount[nUnit][nTemp] >= 5) {
							sMsg.Format("低温異常 Unit%d-%s", nUnit + 1, aTemp[nTemp]);
							aMsg.Add(sMsg);
						}
					} else if (oAnalogData.aUnit[nUnit].fTemp[nTemp] > m_oSettings.oMonitoring.fNormalTempRange2[nUnit][nTemp]) {
						if (++oMonitoringCounter.aTempCount[nUnit][nTemp] >= 5) {
							sMsg.Format("高温異常 Unit%d-%s", nUnit + 1, aTemp[nTemp]);
							aMsg.Add(sMsg);
						}
					} else
						oMonitoringCounter.aTempCount[nUnit][nTemp] = 0;
				}
			}
		}
	}

	// 電圧範囲チェック
	if (m_oSettings.oMonitoring.bEnableVoltageChk) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			if (oStatusWord.aUnit[nUnit].nOnOffStatus) {
				if (oAnalogData.aUnit[nUnit].fHeater < m_oSettings.oMonitoring.fNormalVoltageRange1[nUnit]) {
					if (++oMonitoringCounter.aVoltageCount[nUnit] >= 5) {
						sMsg.Format("電圧下限異常 Unit%d", nUnit + 1);
						aMsg.Add(sMsg);
					}
				} else if (oAnalogData.aUnit[nUnit].fHeater > m_oSettings.oMonitoring.fNormalVoltageRange2[nUnit]) {
					if (++oMonitoringCounter.aVoltageCount[nUnit] >= 5) {
						sMsg.Format("電圧上限異常 Unit%d", nUnit + 1);
						aMsg.Add(sMsg);
					}
				} else
					oMonitoringCounter.aVoltageCount[nUnit] = 0;
			}
		}
	}

	// エラーメッセージがあればヘルスステータスに表示
	if (aMsg.GetCount() != 0) {
		aMsg.InsertAt(0, CString("経路：") + pKeiro);
		aMsg.InsertAt(1, oTimestamp.FormatGmt("ﾀｲﾑｽﾀﾝﾌﾟ：%Y/%m/%d %H:%M:%S"));
		CString sMsg;
		CGeneral::StrJoin(aMsg, "\r\n", sMsg);
		SetHealthStatus(sMsg);
	}

}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetNasaReceiveStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用受信ステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bReceiveStatus		[I] Receiving or Stop
//		BOOL	bForce				[I] 強制表示フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetNasaReceiveStatus(BOOL bReceiveStatus, BOOL bForce)
{
	if (m_bNasaReceiveStatus != bReceiveStatus || bForce) {
		if (bReceiveStatus) {
			m_cStaticNasaReceiveStatus.SetBackColor(RGB(64, 255, 64));
			m_cStaticNasaReceiveStatus = "Receiving";
		} else {
			m_cStaticNasaReceiveStatus.ResetBackColor();
			m_cStaticNasaReceiveStatus = "Stop";
		}

		m_bNasaReceiveStatus = bReceiveStatus;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetNasaEnableDigitalData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用デジタルデータ有効表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bEnable		[I] 有効 or 無効
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetNasaEnableDigitalData(BOOL bEnable)
{
	if (m_bNasaEnableDigitalData != bEnable) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaDigitalAcc[nUnit][nAcc].EnableWindow(bEnable);
		}

		m_bNasaEnableDigitalData = bEnable;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetNasaEnableAnalogData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用アナログデータ有効表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bEnable		[I] 有効 or 無効
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetNasaEnableAnalogData(BOOL bEnable)
{
	if (m_bNasaEnableAnalogData != bEnable) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaAnalogAcc[nUnit][nAcc].EnableWindow(bEnable);

			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditNasaAnalogTemp[nUnit][nTemp].EnableWindow(bEnable);

			m_cEditNasaAnalogHeater[nUnit].EnableWindow(bEnable);
		}

		m_bNasaEnableAnalogData = bEnable;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetHcorReceiveStatus
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用受信ステータス表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bReceiveStatus		[I] Receiving or Stop
//		BOOL	bForce				[I] 強制表示フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetHcorReceiveStatus(BOOL bReceiveStatus, BOOL bForce)
{
	if (m_bHcorReceiveStatus != bReceiveStatus || bForce) {
		if (bReceiveStatus) {
			m_cStaticHcorReceiveStatus.SetBackColor(RGB(64, 255, 64));
			m_cStaticHcorReceiveStatus = "Receiving";
		} else {
			m_cStaticHcorReceiveStatus.ResetBackColor();
			m_cStaticHcorReceiveStatus = "Stop";
		}

		m_bHcorReceiveStatus = bReceiveStatus;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetHcorEnableDigitalData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用デジタルデータ有効表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bEnable		[I] 有効 or 無効
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetHcorEnableDigitalData(BOOL bEnable)
{
	if (m_bHcorEnableDigitalData != bEnable) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorDigitalAcc[nUnit][nAcc].EnableWindow(bEnable);
		}

		m_bHcorEnableDigitalData = bEnable;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::SetHcorEnableAnalogData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用アナログデータ有効表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BOOL	bEnable		[I] 有効 or 無効
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::SetHcorEnableAnalogData(BOOL bEnable)
{
	if (m_bHcorEnableAnalogData != bEnable) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorAnalogAcc[nUnit][nAcc].EnableWindow(bEnable);

			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditHcorAnalogTemp[nUnit][nTemp].EnableWindow(bEnable);

			m_cEditHcorAnalogHeater[nUnit].EnableWindow(bEnable);
		}

		m_bHcorEnableAnalogData = bEnable;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonSetMonitoring
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		正常温度範囲設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonSetMonitoring()
{
	CSetMonitoringDlg oDlg;

	oDlg.m_pNormalTempRange1 = &m_oSettings.oMonitoring.fNormalTempRange1;
	oDlg.m_pNormalTempRange2 = &m_oSettings.oMonitoring.fNormalTempRange2;
	oDlg.m_pNormalVoltageRange1 = &m_oSettings.oMonitoring.fNormalVoltageRange1;
	oDlg.m_pNormalVoltageRange2 = &m_oSettings.oMonitoring.fNormalVoltageRange2;

	oDlg.DoModal();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedRadioNasaSampleRate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用Sample Rate (*.s)ラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioNasaSampleRate()
{
	m_oSettings.oNasa.bAccumulate = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedRadioNasaAccumulate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASA用Accumulate (*.a)ラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioNasaAccumulate()
{
	m_oSettings.oNasa.bAccumulate = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedRadioHcorSampleRate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用Sample Rate (*.s)ラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioHcorSampleRate()
{
	m_oSettings.oHcor.bAccumulate = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedRadioHcorAccumulate
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCOR用Accumulate (*.a)ラジオボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioHcorAccumulate()
{
	m_oSettings.oHcor.bAccumulate = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnOK()
{
	// 何もしない（Enterキーで終了しないようにするため）
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キャンセルボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnCancel()
{
	// 何もしない（Escキーで終了しないようにするため）
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnBnClickedButtonClose
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Closeボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonClose()
{
	CloseDialog();
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::CloseDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		クローズ確認処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeRecvDlg::CloseDialog()
{
	if (m_bRunning) {
		if (AfxMessageBox("Now receiving, Shutdown ?", MB_YESNO | MB_ICONQUESTION) == IDNO)
			return;
	}

	EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnMouseIn
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
LRESULT CMmeRecvDlg::OnMouseIn(WPARAM wParam, LPARAM lParam)
{
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			if (m_cStaticGraph[nUnit][nGraph].m_hWnd == (HWND)wParam) {
				m_cStaticNasaGraph.SetHighlight(nUnit, nGraph);
				DispNasaGraph();

				m_cStaticHcorGraph.SetHighlight(nUnit, nGraph);
				DispHcorGraph();
			}				
		}
	}

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::OnMouseOut
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
LRESULT CMmeRecvDlg::OnMouseOut(WPARAM wParam, LPARAM lParam)
{
	m_cStaticNasaGraph.SetHighlight(-1, -1);
	DispNasaGraph();

	m_cStaticHcorGraph.SetHighlight(-1, -1);
	DispHcorGraph();

	return 0;
}
