Disp//*****************************************************************************************************
//  1. �t�@�C����
//		MmeRecvDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���C���_�C�A���O�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
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

// UDP�|�[�g
#define PORT_NASA	35026
#define PORT_HCOR	35032

// �\�P�b�g�ʒm���b�Z�[�W
#define WM_SOCKET_NASA	(WM_APP + 0)
#define WM_SOCKET_HCOR	(WM_APP + 1)

// �e�����g����MIdle�b��
#define RECEIVE_IDLE_COUNT	3

// �O���t�f�[�^�o�b�t�@�T�C�Y�i24���ԁj
#define N_GRAPH_DATA	(60 * 60 * 24)

// CMmeRecvDlg �_�C�A���O

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::CMmeRecvDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent			[I] �e�E�B���h�E�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::~CMmeRecvDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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


// CMmeRecvDlg ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CMmeRecvDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// "�o�[�W�������..." ���j���[���V�X�e�� ���j���[�ɒǉ����܂��B

	// IDM_ABOUTBOX �́A�V�X�e�� �R�}���h�͈͓̔��ɂȂ���΂Ȃ�܂���B
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

	// ���̃_�C�A���O�̃A�C�R����ݒ肵�܂��B�A�v���P�[�V�����̃��C�� �E�B���h�E���_�C�A���O�łȂ��ꍇ�A
	//  Framework �́A���̐ݒ�������I�ɍs���܂��B
	SetIcon(m_hIcon, TRUE);			// �傫���A�C�R���̐ݒ�
	SetIcon(m_hIcon, FALSE);		// �������A�C�R���̐ݒ�

	// �E�B���h�E�^�C�g���ݒ�
	CString sVersion;
	sVersion.LoadString(IDS_VERSION);
	CString sTitle;
	sTitle.Format("MmeRecv Ver%s", sVersion);
	SetWindowText(sTitle);

	// �ݒ�l��ini�t�@�C������ǂݍ���
	ReadSettings();

	// ��M�t�H���_���쐬
	g_oMmeData.CreateReceiveFolder();

	// NASA�̐ݒ�l��\��
	m_cEditNasaPort = m_oSettings.oNasa.nUdpPort;
	m_cCheckNasaAutoFileName = m_oSettings.oNasa.bAutoFileName;
	m_cCheckNasaPQ = m_oSettings.oNasa.bPysicalQuantity;
	m_cRadioNasaSampleRate = !m_oSettings.oNasa.bAccumulate;
	m_cRadioNasaAccumulate = m_oSettings.oNasa.bAccumulate;

	// HCOR�̐ݒ��\��
	m_cEditHcorPort = m_oSettings.oHcor.nUdpPort;
	m_cCheckHcorAutoFileName = m_oSettings.oHcor.bAutoFileName;
	m_cCheckHcorPQ = m_oSettings.oHcor.bPysicalQuantity;
	m_cRadioHcorSampleRate = !m_oSettings.oHcor.bAccumulate;
	m_cRadioHcorAccumulate = m_oSettings.oHcor.bAccumulate;

	// �O���t�̐ݒ�l��\��
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

	// ���x�E�d���Ď��̐ݒ�l��\��
	m_cCheckEnableTempChk = m_oSettings.oMonitoring.bEnableTempChk;
	m_cCheckEnableVoltageChk = m_oSettings.oMonitoring.bEnableVoltageChk;

	// �G�f�B�b�g�R���g���[���̓��͉\�������ݒ�
	m_cEditNasaPort.SetValidChar(VC_NUM);
	m_cEditHcorPort.SetValidChar(VC_NUM);
	m_cEditGraphTimeSpan.SetValidChar(VC_NUM);
	m_cEditGraphTempRange1.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);
	m_cEditGraphTempRange2.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);
	m_cEditGraphVoltageRange1.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);
	m_cEditGraphVoltageRange2.SetValidChar(VC_NUM | VC_MINUS | VC_POINT);

	// �t�H���g���쐬
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

	// �O���t�p�o�b�t�@���m��
	m_oNasaGraphData.SetBufSize(N_GRAPH_DATA);
	m_oHcorGraphData.SetBufSize(N_GRAPH_DATA);

	// �O���t�̏����\��
	DispNasaGraph();
	DispHcorGraph();

	// �^�C�}�[�N��
	SetTimer(0, 1000, NULL);

	StopReceive();
	ClearHealthStatus();

	return TRUE;  // �t�H�[�J�X���R���g���[���ɐݒ肵���ꍇ�������ATRUE ��Ԃ��܂��B
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnSysCommand
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nID				[I] �V�X�e���R�}���hID
//		LPARAM		lParam			[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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

// �_�C�A���O�ɍŏ����{�^����ǉ�����ꍇ�A�A�C�R����`�悷�邽�߂�
//  ���̃R�[�h���K�v�ł��B�h�L�������g/�r���[ ���f�����g�� MFC �A�v���P�[�V�����̏ꍇ�A
//  ����́AFramework �ɂ���Ď����I�ɐݒ肳��܂��B

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnPaint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y�C���g���b�Z�[�W�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // �`��̃f�o�C�X �R���e�L�X�g

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// �N���C�A���g�̎l�p�`�̈���̒���
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// �A�C�R���̕`��
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnQueryDragIcon
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���[�U�[���ŏ��������E�B���h�E���h���b�O���Ă���Ƃ��ɕ\������J�[�\�����擾���邽�߂�
//		�V�X�e�������̊֐����Ăяo���܂��B
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		HCURSOR		�J�[�\��
//*****************************************************************************************************
HCURSOR CMmeRecvDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::InitSocket
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\�P�b�g����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���s
//*****************************************************************************************************
BOOL CMmeRecvDlg::InitSocket()
{
	sockaddr_in socketAddr;

	// NASA�p�\�P�b�g�I�[�v��
	m_sockNasa = socket(PF_INET, SOCK_DGRAM, 0);
	if (m_sockNasa == INVALID_SOCKET) {
		CGeneral::Alert("�\�P�b�g�̍쐬�Ɏ��s���܂����B");
		return FALSE;
	}

	// HCOR�p�\�P�b�g�I�[�v��
	m_sockHcor = socket(PF_INET, SOCK_DGRAM, 0);
	if (m_sockHcor == INVALID_SOCKET) {
		CGeneral::Alert("�\�P�b�g�̍쐬�Ɏ��s���܂����B");
		return FALSE;
	}

	// NASA�p�\�P�b�g�̃o�C���h
	memset(&socketAddr, 0, sizeof(socketAddr));
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(m_oSettings.oNasa.nUdpPort);
	socketAddr.sin_addr.s_addr = htonl(INADDR_ANY);
	if (bind(m_sockNasa, (LPSOCKADDR)&socketAddr, sizeof(socketAddr)) != 0) {
		CGeneral::Alert("�\�P�b�g�̃o�C���h�Ɏ��s���܂����B");
		return FALSE;
	}

	// HCOR�p�\�P�b�g�̃o�C���h
	memset(&socketAddr, 0, sizeof(socketAddr));
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = htons(m_oSettings.oHcor.nUdpPort);
	socketAddr.sin_addr.s_addr = htonl(INADDR_ANY);
	if (bind(m_sockHcor, (LPSOCKADDR)&socketAddr, sizeof(socketAddr)) != 0) {
		CGeneral::Alert("�\�P�b�g�̃o�C���h�Ɏ��s���܂����B");
		return FALSE;
	}

	// ��M�o�b�t�@�T�C�Y��ݒ�
	int val = 65536 * 2;
	setsockopt(m_sockNasa, SOL_SOCKET, SO_RCVBUF, (char *)&val, sizeof(val));
	setsockopt(m_sockHcor, SOL_SOCKET, SO_RCVBUF, (char *)&val, sizeof(val));

	// ��M�J�n
	WSAAsyncSelect(m_sockNasa, m_hWnd, WM_SOCKET_NASA, FD_READ);
	WSAAsyncSelect(m_sockHcor, m_hWnd, WM_SOCKET_HCOR, FD_READ);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::StartReceive
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M�J�n����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::StartReceive()
{
	CString sDateTime = CTime::GetCurrentTime().Format("%Y_%m%d_%H%M_%S");

	// NASA�p��M�t�@�C�������擾
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

	// HCOR�p��M�t�@�C�������擾
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

	// NASA�p��HCOR�p�������t�@�C�����Ȃ�G���[
	if (sNasaFileName == sHcorFileName) {
		CGeneral::Alert("Same file name.");
		return;
	}

	// NASA�p��M�t�@�C���̏㏑���`�F�b�N
	CString sNasaPathName;
	sNasaPathName.Format("%s\\%s.%s", g_oMmeData.GetReceiveFolder(), sNasaFileName, m_oSettings.oNasa.bAccumulate ? "a" : "s");
	if (::PathFileExists(sNasaPathName)) {
		if (AfxMessageBox("Overwrite NASA Real Time file ?", MB_YESNO | MB_ICONQUESTION) == IDNO)
			return;
	}

	// HCOR�p��M�t�@�C���̏㏑���`�F�b�N
	CString sHcorPathName;
	sHcorPathName.Format("%s\\%s.%s", g_oMmeData.GetReceiveFolder(), sHcorFileName, m_oSettings.oHcor.bAccumulate ? "a" : "s");
	if (::PathFileExists(sHcorPathName)) {
		if (AfxMessageBox("Overwrite HCOR file ?", MB_YESNO | MB_ICONQUESTION) == IDNO)
			return;
	}

	// �\�P�b�g��������
	if (!InitSocket()) {
		StopReceive();
		return;
	}

	// ��M�t�@�C�����I�[�v��
	g_oMmeData.OpenRecieveFiles(sNasaPathName, sHcorPathName);

	// �O���t�����Z�b�g
	m_oNasaGraphData.Reset();
	m_oHcorGraphData.Reset();

	// �f�[�^�L���t���O��������
	m_bValidNasaDigitalData = FALSE;
	m_bValidNasaAnalogData = FALSE;
	m_bValidHcorDigitalData = FALSE;
	m_bValidHcorAnalogData = FALSE;

	// ���j�^�����O�J�E���^��������
	memset(&m_oNasaMonitoringCounter, 0, sizeof(m_oNasaMonitoringCounter));
	memset(&m_oHcorMonitoringCounter, 0, sizeof(m_oHcorMonitoringCounter));

	// ���[�U�w�b�_�\��������
	m_cEditNasaUserHeaderText.Blank();
	m_cEditHcorUserHeaderText.Blank();

	// �f�W�^���f�[�^�ƃA�i���O�f�[�^�̕\��������
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

	// ��M�����ƃV�[�P���X�J�E���g�̕\��������
	m_cEditNasaReceiveTime.Blank();
	m_cEditNasaSequenceCount.Blank();
	m_cEditHcorReceiveTime.Blank();
	m_cEditHcorSequenceCount.Blank();

	// ��M�X�e�[�^�X������
	SetNasaReceiveStatus(FALSE, TRUE);
	SetHcorReceiveStatus(FALSE, TRUE);

	// �w���X�X�e�[�^�X������
	ClearHealthStatus();

	// Status/Message������
	ClearNasaStatusList();
	ClearHcorStatusList();

	// �O���t�̏����\��
	DispNasaGraph();
	DispHcorGraph();

	// ���쒆�ɑ���ł��Ȃ��G�f�B�b�g�R���g���[���𖳌���
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

	// ���쒆�\��
	m_cStaticReceive.SetTextColor(RGB(0, 0, 255));
	m_cStaticReceive = "Running";
	m_cButtonReceive = "Stop";
	m_bRunning = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::StopReceive
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M��~����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::StopReceive()
{
	// NASA�p�\�P�b�g���N���[�Y
	if (m_sockNasa != INVALID_SOCKET) {
		closesocket(m_sockNasa);
		m_sockNasa = INVALID_SOCKET;
	}

	// HCOR�p�\�P�b�g���N���[�Y
	if (m_sockHcor != INVALID_SOCKET) {
		closesocket(m_sockHcor);
		m_sockHcor = INVALID_SOCKET;
	}

	// ��M�t�@�C�����N���[�Y
	g_oMmeData.CloseReceiveFiles();

	// ���쒆�ɑ���ł��Ȃ��G�f�B�b�g�R���g���[����L����
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

	// ��M�X�e�[�^�X�����Z�b�g
	SetNasaReceiveStatus(FALSE);
	SetHcorReceiveStatus(FALSE);

	// ��~���\��
	m_cStaticReceive.SetTextColor(RGB(0, 0, 0));
	m_cStaticReceive = "Stop";
	m_cButtonReceive = "Start";
	m_bRunning = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnSocketNasa
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�\�P�b�g��M����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam				[I] ���b�Z�[�W�p�����[�^
//		LPARAM		lParam				[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
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
		// �p�P�b�g����M
		nPacketLen = ReceivePacket(m_sockNasa, aPacketBuf);
		if (nPacketLen != 0) {
			// ���[�U�w�b�_���擾
			g_oMmeData.GetUserHeader(aPacketBuf, oUserHeader);

			// �p�P�b�g�̎�ނ��Ƃɏ������s��
			switch (oUserHeader.nPacketType) {
			case PACKET_TELEMETRY:
				// �Z�O�����g�J�E���g��0�Ȃ�f�[�^��\������
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

				// �A�L�������[�g�Ȃ�f�W�^���f�[�^�̉����x�����Z
				if (m_oSettings.oNasa.bAccumulate)
					AccumulateAcc(aPacketBuf, oUserHeader, m_oNasaAccumulate, nPacketLen);
				break;
			case PACKET_MME_STATUS:
				// MME�X�e�[�^�X�̃f�[�^��\������
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
				// �G���[�X�e�[�^�X�̃f�[�^��\������
				g_oMmeData.GetErrorStatusData(aPacketBuf, oUserHeader, oErrorStatusData);
				DispNasaErrorStatusData(oUserHeader, oErrorStatusData);
				break;
			case PACKET_MESSAGE:
				// ���b�Z�[�W�̃f�[�^��\������
				g_oMmeData.GetMessageData(aPacketBuf, oUserHeader, oMessageData);
				DispNasaMessageData(oUserHeader, oMessageData);
				break;
			default:
				nPacketLen = 0;
				break;
			}

			// ��M�t�@�C���ɏ�������
			if (nPacketLen != 0)
				g_oMmeData.WriteNasaFile(aPacketBuf, nPacketLen);
		}
		break;
	}

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnSocketHcor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�\�P�b�g��M����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam				[I] ���b�Z�[�W�p�����[�^
//		LPARAM		lParam				[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
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
		// �p�P�b�g����M
		nPacketLen = ReceivePacket(m_sockHcor, aPacketBuf);
		if (nPacketLen != 0) {
			// ���[�U�w�b�_���擾
			g_oMmeData.GetUserHeader(aPacketBuf, oUserHeader);

			// �p�P�b�g�̎�ނ��Ƃɏ������s��
			switch (oUserHeader.nPacketType) {
			case PACKET_TELEMETRY:
				// �Z�O�����g�J�E���g��0�Ȃ�f�[�^��\������
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

				// �A�L�������[�g�Ȃ�f�W�^���f�[�^�̉����x�����Z
				if (m_oSettings.oHcor.bAccumulate)
					AccumulateAcc(aPacketBuf, oUserHeader, m_oHcorAccumulate, nPacketLen);
				break;
			case PACKET_MME_STATUS:
				// MME�X�e�[�^�X�̃f�[�^��\������
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
				// �G���[�X�e�[�^�X�̃f�[�^��\������
				g_oMmeData.GetErrorStatusData(aPacketBuf, oUserHeader, oErrorStatusData);
				DispHcorErrorStatusData(oUserHeader, oErrorStatusData);
				break;
			case PACKET_MESSAGE:
				// ���b�Z�[�W�̃f�[�^��\������
				g_oMmeData.GetMessageData(aPacketBuf, oUserHeader, oMessageData);
				DispHcorMessageData(oUserHeader, oMessageData);
				break;
			default:
				nPacketLen = 0;
				break;
			}

			// ��M�t�@�C���ɏ�������
			if (nPacketLen != 0)
				g_oMmeData.WriteHcorFile(aPacketBuf, nPacketLen);
		}
		break;
	}

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::ReceivePacket
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		UDP�p�P�b�g��M����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SOCKET		sock				[I] �\�P�b�g
//		BYTE		*pPacket			[O] ��M�o�b�t�@�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		��M�f�[�^�o�C�g��
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

	// �p�P�b�g����M
	socketAddr.sin_family      = AF_INET;
	socketAddr.sin_port        = 0;
	socketAddr.sin_addr.s_addr = 0;
	nSocketAddrLen = sizeof(socketAddr);
	memset(&recvBuf, 0, sizeof(recvBuf));
	nRecvLen = recvfrom(sock, (char *)&recvBuf, sizeof(recvBuf), 0, (LPSOCKADDR)&socketAddr, &nSocketAddrLen);

	// Data ZONE�̒��������߂�
	int nDataLen = nRecvLen - (sizeof(recvBuf.headers) + 2);
	if (nDataLen < 0)
		return 0;

	// Data Zone�̕������R�s�[
	memcpy(pPacket, recvBuf.dataZone, nDataLen);

	return nDataLen;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::AccumulateAcc
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		UDP�p�P�b�g��M����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pPacketBuf			[I] �\�P�b�g
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SAccumulate		&oAccumulate		[O] Accumulate�f�[�^
//		int				&nPacketLen			[O] �p�P�b�g��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::AccumulateAcc(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SAccumulate &oAccumulate, int &nPacketLen)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	// �Z�O�����g�J�E���g��0�Ȃ���Z�o�b�t�@���N���A
	if (oUserHeader.nSegmentCount == 0) {
		memset(&oAccumulate, 0, sizeof(oAccumulate));
		memcpy(&oAccumulate, pPacketBuf, USER_HEADER_SIZE + ANALOG_DATA_SIZE);
		pData += ANALOG_DATA_SIZE;
	}

	// �����x�f�[�^�����Z
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

	// �I�[�Z�O�����g���ʂ�1�Ȃ���Z���ʂ��o�b�t�@�ɓ]��
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
//  1. �֐���
//		CMmeRecvDlg::DispNasaReceiveInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p��M��ԕ\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaReceiveInfo()
{
	m_cEditNasaReceiveTime = CTime::GetCurrentTime().Format("%H:%M:%S");
	SetNasaReceiveStatus(TRUE);
	m_nNasaReceiveIdleCounter = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaUserHeader
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p���[�U�w�b�_�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaUserHeader(const SUserHeader &oUserHeader)
{
	CString sUserHeaderData;

	GetUserHeaderText(oUserHeader, sUserHeaderData);
	m_cEditNasaUserHeaderText = sUserHeaderData;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaDigitalData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�f�W�^���f�[�^�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDigitalData	&oDitalData		[I] �f�W�^���f�[�^���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaDigitalData(const SDigitalData &oDitalData)
{
	m_cEditNasaSequenceCount = oDitalData.nSequrnceCount;

	if (m_oSettings.oNasa.bPysicalQuantity) {
		// Physical Quantity��ON�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaDigitalAcc[nUnit][nAcc].Format("%.5f", oDitalData.aUnit[nUnit].fAcc[nAcc] * 1000);
		}
	} else {
		// Physical Quantity��OFF�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaDigitalAcc[nUnit][nAcc].Format("%d", oDitalData.aUnit[nUnit].nAcc[nAcc]);
		}
	}

	memcpy(&m_oNasaLastDigitalData, &oDitalData, sizeof(SDigitalData));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaAnalogData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�A�i���O�f�[�^�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SAnalogData		&oAnalogData		[I] �A�i���O�f�[�^���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaAnalogData(const SAnalogData &oAnalogData)
{
	if (m_oSettings.oNasa.bPysicalQuantity) {
		// Physical Quantity��ON�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// �����x��\��
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaAnalogAcc[nUnit][nAcc].Format("%.2f", oAnalogData.aUnit[nUnit].fAcc[nAcc] * 1000);

			// ���x��\��
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditNasaAnalogTemp[nUnit][nTemp].Format("%.3f", oAnalogData.aUnit[nUnit].fTemp[nTemp]);

			// �q�[�^�d����\��
			m_cEditNasaAnalogHeater[nUnit].Format("%.3f", oAnalogData.aUnit[nUnit].fHeater);
		}
	} else {
		// Physical Quantity��OFF�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// �����x��\��
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditNasaAnalogAcc[nUnit][nAcc].Format("%d", oAnalogData.aUnit[nUnit].nAcc[nAcc]);

			// ���x��\��
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditNasaAnalogTemp[nUnit][nTemp].Format("%d", oAnalogData.aUnit[nUnit].nTemp[nTemp]);

			// �q�[�^�d����\��
			m_cEditNasaAnalogHeater[nUnit].Format("%d", oAnalogData.aUnit[nUnit].nHeater);
		}
	}

	// �f�[�^��ۑ��iPhysical Quantity��؂�ւ������ɕ\�����邽�߁j
	memcpy(&m_oNasaLastAnalogData, &oAnalogData, sizeof(SAnalogData));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaMmeStatusData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pMME�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SMmeStatusData	&oMmeStatusData		[I] MME�X�e�[�^�X���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaMmeStatusData(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData)
{
	CString sData;

	// MME�X�e�[�^�X�̕\�����e���擾
	GetMmeStatusDataText(oUserHeader, oMmeStatusData, sData);

	// Status/Message���X�g�{�b�N�X�ɒǉ�
	AddNasaStatusList("MME Status", sData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaErrorStatusData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�G���[�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader			&oUserHeader		[I] ���[�U�w�b�_���
//		SErrorStatusData	&oErrorStatusData	[I] �G���[�X�e�[�^�X���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaErrorStatusData(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData)
{
	CString sData;

	// �G���[�X�e�[�^�X�̕\�����e���擾
	GetErrorStatusDataText(oUserHeader, oErrorStatusData, sData);

	// Status/Message���X�g�{�b�N�X�ɒǉ�
	AddNasaStatusList("Error Status", sData);

	// �w���X�X�e�[�^�X�ɕ\��������e��ҏW
	CString sError;
	sError.Format("�o�H�FNASA Real Time\r\n"
		"��ѽ���߁F%s\r\n"
		"�װ���ށF%s\r\n"
		"��޺��ށF%s",
		oUserHeader.oTimestamp.FormatGmt("%Y/%m/%d %H:%M:%S"),
		GetErrorCodeText(oUserHeader.oErrorStatus.nErrorCode),
		GetSubCodeText(oUserHeader.oErrorStatus.nErrorCode, oUserHeader.oErrorStatus.nSubCode),
		oErrorStatusData.sTaskName,
		oErrorStatusData.sErrorMessage);

	// �G���[�R�[�h�ʂɃ^�X�N���̂ƃG���[���b�Z�[�W��ҏW
	switch (oUserHeader.oErrorStatus.nErrorCode) {
	case 0x02:
		sData.Format(
				"%s\r\n"
				"������́F%s\r\n"
				"�װү���ށF%s",
				sError,
				oErrorStatusData.sTaskName,
				oErrorStatusData.sErrorMessage);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		sData.Format(
				"%s\r\n"
				"�װү���ށF%s",
				sError,
				oErrorStatusData.sErrorMessage);
		break;
	default:
		sData = sError;
		break;
	}

	// �w���X�X�e�[�^�X�ɕ\��
	SetHealthStatus(sData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaMessageData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p���b�Z�[�W�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SMessageData	&oMessageData		[I] ���b�Z�[�W���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispNasaMessageData(const SUserHeader &oUserHeader, const SMessageData &oMessageData)
{
	CString sData;

	// ���b�Z�[�W�̕\�����e���擾
	GetMessageDataText(oUserHeader, oMessageData, sData);

	// Status/Message���X�g�{�b�N�X�ɒǉ�
	AddNasaStatusList("Message", sData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::AddNasaStatusList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�X�e�[�^�X�^���b�Z�[�W���X�g�{�b�N�X�ւ̒ǉ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pPacketName		[I] ���X�g�{�b�N�X�ɕ\������p�P�b�g����
//		LPCTSTR		pData			[I] �p�P�b�g�ڍ׏��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::AddNasaStatusList(LPCTSTR pPacketName, LPCTSTR pData)
{
	CString sText;

	// �\�����e��ۑ�
	CString *pStatusMessage = new CString();
	*pStatusMessage = pData;

	// ���X�g�{�b�N�X�ɒǉ�
	sText.Format("%s %s", CTime::GetCurrentTime().Format("%H:%M:%S"), pPacketName);
	int nIndex = m_cListNasaStatusHistory.AddString(sText);
	m_cListNasaStatusHistory.SetItemDataPtr(nIndex, pStatusMessage);

	// �ǉ��������ڂ�I������
	m_cListNasaStatusHistory.SetCurSel(nIndex);
	OnLbnSelchangeListNasaStatusHistory();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorReceiveInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p��M��ԕ\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorReceiveInfo()
{
	m_cEditHcorReceiveTime = CTime::GetCurrentTime().Format("%H:%M:%S");
	SetHcorReceiveStatus(TRUE);
	m_nHcorReceiveIdleCounter = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorUserHeader
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p���[�U�w�b�_�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorUserHeader(const SUserHeader &oUserHeader)
{
	CString sUserHeaderData;

	GetUserHeaderText(oUserHeader, sUserHeaderData);
	m_cEditHcorUserHeaderText = sUserHeaderData;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorDigitalData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�f�W�^���f�[�^�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDigitalData	&oDitalData		[I] �f�W�^���f�[�^���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorDigitalData(const SDigitalData &oDitalData)
{
	m_cEditHcorSequenceCount = oDitalData.nSequrnceCount;

	if (m_oSettings.oHcor.bPysicalQuantity) {
		// Physical Quantity��ON�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorDigitalAcc[nUnit][nAcc].Format("%.5f", oDitalData.aUnit[nUnit].fAcc[nAcc] * 1000);
		}
	} else {
		// Physical Quantity��OFF�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorDigitalAcc[nUnit][nAcc].Format("%d", oDitalData.aUnit[nUnit].nAcc[nAcc]);
		}
	}

	memcpy(&m_oHcorLastDigitalData, &oDitalData, sizeof(SDigitalData));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorAnalogData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�A�i���O�f�[�^�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SAnalogData		&oAnalogData		[I] �A�i���O�f�[�^���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorAnalogData(const SAnalogData &oAnalogData)
{
	if (m_oSettings.oHcor.bPysicalQuantity) {
		// Physical Quantity��ON�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// �����x��\��
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorAnalogAcc[nUnit][nAcc].Format("%.2f", oAnalogData.aUnit[nUnit].fAcc[nAcc] * 1000);

			// ���x��\��
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditHcorAnalogTemp[nUnit][nTemp].Format("%.3f", oAnalogData.aUnit[nUnit].fTemp[nTemp]);

			// �q�[�^�d����\��
			m_cEditHcorAnalogHeater[nUnit].Format("%.3f", oAnalogData.aUnit[nUnit].fHeater);
		}
	} else {
		// Physical Quantity��OFF�̏ꍇ
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			// �����x��\��
			for (int nAcc = 0; nAcc < N_ACC; nAcc++)
				m_cEditHcorAnalogAcc[nUnit][nAcc].Format("%d", oAnalogData.aUnit[nUnit].nAcc[nAcc]);

			// ���x��\��
			for (int nTemp = 0; nTemp < N_TEMP; nTemp++)
				m_cEditHcorAnalogTemp[nUnit][nTemp].Format("%d", oAnalogData.aUnit[nUnit].nTemp[nTemp]);

			// �q�[�^�d����\��
			m_cEditHcorAnalogHeater[nUnit].Format("%d", oAnalogData.aUnit[nUnit].nHeater);
		}
	}

	// �f�[�^��ۑ��iPhysical Quantity��؂�ւ������ɕ\�����邽�߁j
	memcpy(&m_oHcorLastAnalogData, &oAnalogData, sizeof(SAnalogData));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorMmeStatusData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pMME�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SMmeStatusData	&oMmeStatusData		[I] MME�X�e�[�^�X���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorMmeStatusData(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData)
{
	CString sData;

	// MME�X�e�[�^�X�̕\�����e���擾
	GetMmeStatusDataText(oUserHeader, oMmeStatusData, sData);

	// Status/Message���X�g�{�b�N�X�ɒǉ�
	AddHcorStatusList("MME Status", sData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorErrorStatusData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�G���[�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader			&oUserHeader		[I] ���[�U�w�b�_���
//		SErrorStatusData	&oErrorStatusData	[I] �G���[�X�e�[�^�X���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorErrorStatusData(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData)
{
	CString sData;

	// �G���[�X�e�[�^�X�̕\�����e���擾
	GetErrorStatusDataText(oUserHeader, oErrorStatusData, sData);

	// Status/Message���X�g�{�b�N�X�ɒǉ�
	AddHcorStatusList("Error Status", sData);

	// �w���X�X�e�[�^�X�ɕ\��������e��ҏW
	CString sError;
	sError.Format("�o�H�FHCOR\r\n"
		"��ѽ���߁F%s\r\n"
		"�װ���ށF%s\r\n"
		"��޺��ށF%s",
		oUserHeader.oTimestamp.FormatGmt("%Y/%m/%d %H:%M:%S"),
		GetErrorCodeText(oUserHeader.oErrorStatus.nErrorCode),
		GetSubCodeText(oUserHeader.oErrorStatus.nErrorCode, oUserHeader.oErrorStatus.nSubCode),
		oErrorStatusData.sTaskName,
		oErrorStatusData.sErrorMessage);

	// �G���[�R�[�h�ʂɃ^�X�N���̂ƃG���[���b�Z�[�W��ҏW
	switch (oUserHeader.oErrorStatus.nErrorCode) {
	case 0x02:
		sData.Format(
				"%s\r\n"
				"������́F%s\r\n"
				"�װү���ށF%s",
				sError,
				oErrorStatusData.sTaskName,
				oErrorStatusData.sErrorMessage);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		sData.Format(
				"%s\r\n"
				"�װү���ށF%s",
				sError,
				oErrorStatusData.sErrorMessage);
		break;
	default:
		sData = sError;
		break;
	}

	// �w���X�X�e�[�^�X�ɕ\��
	SetHealthStatus(sData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispHcorMessageData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p���b�Z�[�W�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SMessageData	&oMessageData		[I] ���b�Z�[�W���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::DispHcorMessageData(const SUserHeader &oUserHeader, const SMessageData &oMessageData)
{
	CString sData;

	// ���b�Z�[�W�̕\�����e���擾
	GetMessageDataText(oUserHeader, oMessageData, sData);

	// Status/Message���X�g�{�b�N�X�ɒǉ�
	AddHcorStatusList("Message", sData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::AddHcorStatusList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�X�e�[�^�X�^���b�Z�[�W���X�g�{�b�N�X�ւ̒ǉ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pPacketName		[I] ���X�g�{�b�N�X�ɕ\������p�P�b�g����
//		LPCTSTR		pData			[I] �p�P�b�g�ڍ׏��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::AddHcorStatusList(LPCTSTR pPacketName, LPCTSTR pData)
{
	CString sText;

	// �\�����e��ۑ�
	CString *pStatusMessage = new CString();
	*pStatusMessage = pData;

	// ���X�g�{�b�N�X�ɒǉ�
	sText.Format("%s %s", CTime::GetCurrentTime().Format("%H:%M:%S"), pPacketName);
	int nIndex = m_cListHcorStatusHistory.AddString(sText);
	m_cListHcorStatusHistory.SetItemDataPtr(nIndex, pStatusMessage);

	// �ǉ��������ڂ�I������
	m_cListHcorStatusHistory.SetCurSel(nIndex);
	OnLbnSelchangeListHcorStatusHistory();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�j������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnDestroy()
{
	CDialog::OnDestroy();

	// ��M��~
	StopReceive();

	// Status/Message���X�g�폜�i����������j
	ClearNasaStatusList();
	ClearHcorStatusList();

	// �ݒ�l�ۑ�
	WriteSettings();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnLbnSelchangeListNasaStatusHistory
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�X�e�[�^�X�^���b�Z�[�W���X�g�{�b�N�X�I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnLbnSelchangeListNasaStatusHistory()
{
	// �I������Ă���Status/Message�̓��e��\��
	CString *pStatusMessage = (CString *)m_cListNasaStatusHistory.GetItemDataPtr(m_cListNasaStatusHistory.GetCurSel());
	m_cEditNasaStatusData = *pStatusMessage;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::ClearNasaStatusList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�X�e�[�^�X�^���b�Z�[�W���X�g�{�b�N�X��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::ClearNasaStatusList()
{
	// ���������
	int nCount = m_cListNasaStatusHistory.GetCount();
	for (int i = 0; i < nCount; i++)
		delete (CString *)m_cListNasaStatusHistory.GetItemDataPtr(i);

	// ���X�g�{�b�N�X���Z�b�g
	m_cListNasaStatusHistory.ResetContent();

	// ���e����
	m_cEditNasaStatusData.Blank();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnLbnSelchangeListHcorStatusHistory
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�X�e�[�^�X�^���b�Z�[�W���X�g�{�b�N�X�I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnLbnSelchangeListHcorStatusHistory()
{
	// �I������Ă���Status/Message�̓��e��\��
	CString *pStatusMessage = (CString *)m_cListHcorStatusHistory.GetItemDataPtr(m_cListHcorStatusHistory.GetCurSel());
	m_cEditHcorStatusData = *pStatusMessage;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::ClearHcorStatusList
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�X�e�[�^�X�^���b�Z�[�W���X�g�{�b�N�X��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::ClearHcorStatusList()
{
	// ���������
	int nCount = m_cListHcorStatusHistory.GetCount();
	for (int i = 0; i < nCount; i++)
		delete (CString *)m_cListHcorStatusHistory.GetItemDataPtr(i);

	// ���X�g�{�b�N�X���Z�b�g
	m_cListHcorStatusHistory.ResetContent();

	// ���e����
	m_cEditHcorStatusData.Blank();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonStopAlxxx
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A���[����~�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonStopAlxxx()
{
	StopAlxxx();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonClearStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���X�X�e�[�^�X�����{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonClearStatus()
{
	ClearHealthStatus();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::SetHealthStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���X�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pPacketData			[I] �X�e�[�^�X�ڍ׏��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::SetHealthStatus(LPCTSTR pPacketData)
{
	if (!bAlertOn) {
		// �A���[������炷
		PlaySound("alxxx.wav", NULL, SND_FILENAME | SND_ASYNC | SND_LOOP);
		bAlertOn = TRUE;
	}

	// Abnormal�\��
	m_cStaticHealthStatus.SetTextColor(RGB(255, 0, 0));
	m_cStaticHealthStatus = "Abnormal";
	m_cEditAlxxxTime = CTime::GetCurrentTime().Format("%m/%d %H:%M:%S");
	m_cEditHealthStatus = pPacketData;
	m_cButtonStopAlxxx.EnableWindow(TRUE);
	m_cButtonClearStatus.EnableWindow(TRUE);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::ClearHealthStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���X�X�e�[�^�X��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::ClearHealthStatus()
{
	// �A���[������~
	StopAlxxx();

	// Normal�\��
	m_cStaticHealthStatus.SetTextColor(RGB(0, 0, 255));
	m_cStaticHealthStatus = "Normal";
	m_cEditAlxxxTime.Blank();
	m_cEditHealthStatus.Blank();
	m_cButtonClearStatus.EnableWindow(FALSE);

	// ���x�Ď��J�E���^���Z�b�g
	memset(&m_oNasaMonitoringCounter, 0, sizeof(m_oNasaMonitoringCounter));
	memset(&m_oHcorMonitoringCounter, 0, sizeof(m_oHcorMonitoringCounter));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::StopAlxxx
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A���[����~����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::StopAlxxx()
{
	// �A���[������~
	PlaySound(NULL, NULL, 0);

	m_cButtonStopAlxxx.EnableWindow(FALSE);
	bAlertOn = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonReceive
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Start/Stop�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonReceive()
{
	if (m_bRunning)
		StopReceive();
	else
		StartReceive();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonReadFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M�t�@�C���ǂݍ��݃{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnTimer
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C�}���b�Z�[�W����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT_PTR		nIDEvent			[I] �^�C�}�C�x���gID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnTimer(UINT_PTR nIDEvent)
{
	if (nIDEvent == 0) {
		if (m_bRunning) {
			// NASA�p��MIdle�`�F�b�N
			if (++m_nNasaReceiveIdleCounter > RECEIVE_IDLE_COUNT)
				SetNasaReceiveStatus(FALSE);

			// NASA�p�f�W�^���f�[�^�L���`�F�b�N
			if (++m_nNasaDigitalIdleCounter > RECEIVE_IDLE_COUNT)
				SetNasaEnableDigitalData(FALSE);

			// NASA�p�A�i���O�f�[�^�L���`�F�b�N
			if (++m_nNasaAnalogIdleCounter > RECEIVE_IDLE_COUNT)
				SetNasaEnableAnalogData(FALSE);

			// HCOR�p��MIdle�`�F�b�N
			if (++m_nHcorReceiveIdleCounter > RECEIVE_IDLE_COUNT)
				SetHcorReceiveStatus(FALSE);

			// HCOR�p�f�W�^���f�[�^�L���`�F�b�N
			if (++m_nHcorDigitalIdleCounter > RECEIVE_IDLE_COUNT)
				SetHcorEnableDigitalData(FALSE);

			// HCOR�p�A�i���O�f�[�^�L���`�F�b�N
			if (++m_nHcorAnalogIdleCounter > RECEIVE_IDLE_COUNT)
				SetHcorEnableAnalogData(FALSE);
		}
	}

	CDialog::OnTimer(nIDEvent);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonNasaGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pGraph Window�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonNasaGraphWindow()
{
	// NASA��M�f�[�^�O���t�\��
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
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonHcorGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pGraph Window�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonHcorGraphWindow()
{
	// HCOR��M�f�[�^�O���t�\��
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
//  1. �֐���
//		CMmeRecvDlg::ReadSettings
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C���ǂݍ��ݏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::ReadSettings()
{
	static const char aSession[] = "Settings";
	static const char *aGraphKind[N_GRAPH] = {"X", "Y", "Z", "SB", "Heater"};
	static const char *aTempKind[N_TEMP] = {"X", "Y", "Z", "SB"};
	CString sIniPathName;
	CString sKey;

	// ini�p�X���擾
	CGeneral::GetIniPathName(sIniPathName);

	// NASA�p�ݒ�l�擾
	m_oSettings.oNasa.nUdpPort = ::GetPrivateProfileInt(aSession, "NasaUdpPort", PORT_NASA, sIniPathName);
	m_oSettings.oNasa.bAutoFileName = ::GetPrivateProfileInt(aSession, "NasaAutoFileName", 1, sIniPathName);
	m_oSettings.oNasa.bPysicalQuantity = ::GetPrivateProfileInt(aSession, "NasaPysicalQuantity", 1, sIniPathName);
	m_oSettings.oNasa.bAccumulate = ::GetPrivateProfileInt(aSession, "NasaAccumulate", 0, sIniPathName);

	// HCOR�p�ݒ�l�擾
	m_oSettings.oHcor.nUdpPort = ::GetPrivateProfileInt(aSession, "HcorUdpPort", PORT_HCOR, sIniPathName);
	m_oSettings.oHcor.bAutoFileName = ::GetPrivateProfileInt(aSession, "HcorAutoFileName", 1, sIniPathName);
	m_oSettings.oHcor.bPysicalQuantity = ::GetPrivateProfileInt(aSession, "HcorPysicalQuantity", 1, sIniPathName);
	m_oSettings.oHcor.bAccumulate = ::GetPrivateProfileInt(aSession, "HcorAccumulate", 0, sIniPathName);

	// �O���t�ݒ�l�擾
	m_oSettings.oGraph.nTimeSpan = ::GetPrivateProfileInt(aSession, "GraphTimeSpan", 600, sIniPathName);
	m_oSettings.oGraph.fTempRange1 = CGeneral::GetPrivateProfileDouble(aSession, "GraphTemperatureRange1", 0, sIniPathName);
	m_oSettings.oGraph.fTempRange2 = CGeneral::GetPrivateProfileDouble(aSession, "GraphTemperatureRange2", 100, sIniPathName);
	m_oSettings.oGraph.fVoltageRange1 = CGeneral::GetPrivateProfileDouble(aSession, "GeaphHeaterVoltageRange1", 0, sIniPathName);
	m_oSettings.oGraph.fVoltageRange2 = CGeneral::GetPrivateProfileDouble(aSession, "GeaphHeaterVoltageRange2", 30, sIniPathName);

	// Data Line�ݒ�l�擾
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			sKey.Format("GraphUnit%d%s", nUnit + 1, aGraphKind[nGraph]);
			m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph] = ::GetPrivateProfileInt(aSession, sKey, 1, sIniPathName);
		}
	}

	// ���x�E�d���Ď��ݒ�l�擾
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
//  1. �֐���
//		CMmeRecvDlg::WriteSettings
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ini�t�@�C���������ݏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::WriteSettings()
{
	static const char aSession[] = "Settings";
	static const char *aGraphKind[N_GRAPH] = {"X", "Y", "Z", "SB", "Heater"};
	static const char *aTempKind[N_TEMP] = {"X", "Y", "Z", "SB"};
	CString sIniPathName;
	CString sKey;

	// ini�p�X���擾
	CGeneral::GetIniPathName(sIniPathName);

	// NASA�p�ݒ�l�ۑ�
	CGeneral::WritePrivateProfileInt(aSession, "NasaUdpPort", m_oSettings.oNasa.nUdpPort, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "NasaAutoFileName", m_oSettings.oNasa.bAutoFileName, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "NasaPysicalQuantity", m_oSettings.oNasa.bPysicalQuantity, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "NasaAccumulate", m_oSettings.oNasa.bAccumulate, sIniPathName);

	// HCOR�p�ݒ�l�ۑ�
	CGeneral::WritePrivateProfileInt(aSession, "HcorUdpPort", m_oSettings.oHcor.nUdpPort, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "HcorAutoFileName", m_oSettings.oHcor.bAutoFileName, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "HcorPysicalQuantity", m_oSettings.oHcor.bPysicalQuantity, sIniPathName);
	CGeneral::WritePrivateProfileInt(aSession, "HcorAccumulate", m_oSettings.oHcor.bAccumulate, sIniPathName);

	// �O���t�ݒ�l�ۑ�
	CGeneral::WritePrivateProfileInt(aSession, "GraphTimeSpan", m_oSettings.oGraph.nTimeSpan, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GraphTemperatureRange1", m_oSettings.oGraph.fTempRange1, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GraphTemperatureRange2", m_oSettings.oGraph.fTempRange2, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GeaphHeaterVoltageRange1", m_oSettings.oGraph.fVoltageRange1, sIniPathName);
	CGeneral::WritePrivateProfileDouble(aSession, "GeaphHeaterVoltageRange2", m_oSettings.oGraph.fVoltageRange2, sIniPathName);

	// Data Line�ݒ�l�ۑ�
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			sKey.Format("GraphUnit%d%s", nUnit + 1, aGraphKind[nGraph]);
			CGeneral::WritePrivateProfileInt(aSession, sKey, m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph], sIniPathName);
		}
	}

	// ���x�E�d���Ď��ݒ�l�ۑ�
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
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\���`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckGraph()
{
	// Data Line�̐ݒ�l�擾
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			m_oSettings.oGraph.oGraphLine.bEnable[nUnit][nGraph] = m_cCheckGraph[nUnit][nGraph];
		}
	}

	// �O���t�\��
	DispNasaGraph();
	DispHcorGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckNasaPq
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pPhysical Quantity�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckHcorPq
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pPhysical Quantity�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditNasaPort
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pUDP�|�[�g���͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditNasaPort()
{
	if (!m_cEditNasaPort.IsEmpty())
		m_oSettings.oNasa.nUdpPort = m_cEditNasaPort;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditHcorPort
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pUDP�|�[�g���͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnEnChangeEditHcorPort()
{
	if (!m_cEditHcorPort.IsEmpty())
		m_oSettings.oHcor.nUdpPort = m_cEditHcorPort;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditGraphTimeSpan
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̃^�C���X�p�����͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditGraphTempRange1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̉��x�����W�������͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditGraphTempRange2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̉��x�����W������͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditGraphVoltageRange1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̃q�[�^�d���������͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnEnChangeEditGraphVoltageRange2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�̃q�[�^�d��������͕ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetNasaPysicalQuantity
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pPysical Quantity�\���ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetHcorPysicalQuantity
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pPysical Quantity�\���ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::GetFrameIdText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���\������ID�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFrameId			[I] �t���[���\������ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetFrameIdText(UINT nFrameId)
{
	CString sText;

	switch (nFrameId) {
	case 0x01:
		sText = "�ʏ��ݽLo�ݼތv���ް�";
		break;
	case 0x02:
		sText = "�ʏ��ݽHi�ݼތv���ް�";
		break;
	case 0x03:
		sText = "�ʏ��ݽEx�ݼތv���ް�";
		break;
	case 0x11:
		sText = "�A�����ݽLo�ݼތv���ް�";
		break;
	case 0x12:
		sText = "�A�����ݽHi�ݼތv���ް�";
		break;
	case 0x13:
		sText = "�A�����ݽEx�ݼތv���ް�";
		break;
	default:
		sText.Format("0x%02x", nFrameId);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetMeasurementModeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�����[�h�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nMeasurentMode			[I] �v�����[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasurementModeText(UINT nMeasurentMode)
{
	CString sText;

	switch (nMeasurentMode) {
	case 0x00:
		sText = "�ʏ�V�[�P���X���[�h";
		break;
	case 0x01:
		sText = "�A���V�[�P���X���[�h";
		break;
	default:
		sText.Format("0x%02x", nMeasurentMode);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetMeasurementRangeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�������W�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nMeasurementRange			[I] �v�������W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetMeasurementRangeText(UINT nMeasurementRange)
{
	CString sText;

	switch (nMeasurementRange) {
	case 0x01:
		sText = "Lo�����W�v���f�[�^";
		break;
	case 0x02:
		sText = "Hi�����W�v���f�[�^";
		break;
	case 0x03:
		sText = "Ex�����W�v���f�[�^";
		break;
	default:
		sText.Format("0x%02x", nMeasurementRange);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetMeasurementRangeText2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�������W�̕\���p�e�L�X�g�i�Z�k�^�j�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nMeasurementRange			[I] �v�������W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetMeasureModeIdText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�X�e�[�^�X��measureModeID�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nMeasureModeId			[I] measureModeID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetMeasureStatusText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�X�e�[�^�X��measureStatus�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nMeasureStatus			[I] measureStatus
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetCurrentRangeIdText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�X�e�[�^�X��currentRangeID�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nCurrentRangeId			[I] currentRangeID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetBitStatusTypeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O��HALT���G���[����Bit Status�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nBitStatusType			[I] �O��HALT���G���[����Bit Status
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetBitStatusTypeText(UINT nBitStatusType)
{
	CString sText;

	switch (nBitStatusType) {
	case 0x01:
		sText = "ALU��ShiftUnit�e�X�g";
		break;
	case 0x02:
		sText = "RAM Bank identification & address test";
		break;
	case 0x03:
		sText = "RAM�p�^�[���e�X�g�iROM to RAM Copy�j";
		break;
	case 0x04:
		sText = "RAM�p�^�[���e�X�g�ioverall�j";
		break;
	case 0x05:
		sText = "EDAC�e�X�g";
		break;
	case 0x06:
		sText = "Memory Protection�e�X�g";
		break;
	case 0x07:
		sText = "Interrupt mechnism�e�X�g";
		break;
	case 0x08:
		sText = "Timers�e�X�g";
		break;
	case 0x09:
		sText = "FPU�e�X�g";
		break;
	case 0x0a:
		sText = "VME�e�X�g";
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
//  1. �֐���
//		CMmeRecvDlg::GetBitStatusNatureText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O��HALT���G���[����nature�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nBitStatusNature			[I] �O��HALT���G���[����nature
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetHaltInfoErrorCodeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O��HALT���G���[���̃G���[�R�[�h�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nHaltInfoErrorCode			[I] �O��HALT���G���[���̃G���[�R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetErrorCodeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�G���[�X�e�[�^�X�̃G���[�R�[�h�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nErrorCode			[I] �G���[�R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetErrorCodeText(UINT nErrorCode)
{
	CString sText;

	switch (nErrorCode) {
	case 0x00:
		sText = "����";
		break;
	case 0x01:
		sText = "�E�H�b�`�h�b�O�^�C�}�^�C���A�E�g";
		break;
	case 0x02:
		sText = "��O�����ݔ���";
		break;
	case 0x03:
		sText = "�p�P�b�g����M�G���[";
		break;
	case 0x04:
		sText = "�n�[�h�E�F�A�G���[";
		break;
	case 0x05:
		sText = "�\�t�g�E�F�A�G���[";
		break;
	default:
		sText.Format("0x%02x", nErrorCode);
		break;
	}

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetSubCodeText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�G���[�X�e�[�^�X�̃T�u�R�[�h�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nErrorCode			[I] �G���[�R�[�h
//		UINT	nSubCode			[I] �T�u�R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
			sText = "�\�P�b�g�������ł��Ȃ�";
			break;
		}
		break;
	case 0x04:
		switch (nSubCode) {
		case 0x00:
			sText = "AUX�N���b�N�������ł��Ȃ�";
			break;
		case 0x01:
			sText = "�E�H�[���A�b�v�ُ�";
			break;
		case 0x02:
			sText = "ON/OFF�X�e�[�^�X�ُ�";
			break;
		case 0x03:
			sText = "�����W�X�e�[�^�X�ُ�";
			break;
		case 0x04:
			sText = "�q�[�^�n���ُ�";
			break;
		}
		break;
	case 0x05:
		switch (nSubCode) {
		case 0x01:
			sText = "Sif�̊����݂��o�^�ł��Ȃ�";
			break;
		case 0x02:
			sText = "Sif�̊����݂�Enable�ł��Ȃ�";
			break;
		case 0x03:
			sText = "���ݎ������v���J�n�����𒴂��Ă���";
			break;
		case 0x04:
			sText = "�^�X�N�̏��������I�����Ȃ�";
			break;
		}		
	}

	if (sText.IsEmpty())
		sText.Format("0x%02x", nSubCode);

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetStatusWordText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�e�[�^�X���[�h�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SStatusWord		&oStatusWord			[I] �X�e�[�^�X���[�h���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
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
//  1. �֐���
//		CMmeRecvDlg::GetUserHeaderCommonText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���[�U�w�b�_���ʕ��̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader			[I] ���[�U�w�b�_���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetUserHeaderCommonText(const SUserHeader &oUserHeader)
{
	CString sText;

	sText.Format(
			"�߲۰�ގ���ID�F0x%02x�C"
			"�ް�����ID�F0x%02x\r\n"
			"�ڰэ\������ID�F%s\r\n"
			"�I�[�����Ď��ʁF%d�C"
			"�����Ķ��āF%d\r\n"
			"��ѽ���߁F%s\r\n"
			"Fine Time�F0x%04x",
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
//  1. �֐���
//		CMmeRecvDlg::GetLasttimeHaltText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O��HALT���G���[���̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SLasttimeHalt		&oLasttimeHalt			[I] �O��HALT���G���[���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�\���p�e�L�X�g
//*****************************************************************************************************
CString CMmeRecvDlg::GetLasttimeHaltText(const SLasttimeHalt &oLasttimeHalt)
{
	CString sText;

	if (oLasttimeHalt.bValidData) {
		sText.Format("�O��HALT���װ���F\r\n"
				"�@�����?�F0x%08x\r\n"
				"�@type�F%s\r\n"
				"�@nature�F%s\r\n"
				"�@�^�X�N�������ށF%s\r\n"
				"�@�G���[�R�[�h�F%s\r\n"
				"�@�����F%s",
				oLasttimeHalt.oBitStatus.nSessionNumber,
				GetBitStatusTypeText(oLasttimeHalt.oBitStatus.nType),
				GetBitStatusNatureText(oLasttimeHalt.oBitStatus.nNature),
				oLasttimeHalt.oHaltInfo.sTaskName,
				GetHaltInfoErrorCodeText(oLasttimeHalt.oHaltInfo.nErrorCode),
				oLasttimeHalt.oHaltInfo.oErrorTime.FormatGmt("%Y/%m/%d %H:%M:%S"));
	} else
		sText = "�O��HALT���װ���FNo data";

	return sText;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetUserHeaderText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�e�����g���p�P�b�g�̃��[�U�w�b�_�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader			[I] ���[�U�w�b�_���
//		CString			&sText					[O] �\���p�e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::GetUserHeaderText(const SUserHeader &oUserHeader, CString &sText)
{
	sText.Format(
			"%s\r\n"
			"�ް����F%d\r\n"
			"�v��Ӱ�ށF%s�C"
			"�v���ݼށF%s\r\n"
			"�����ݸގ��g���F%d\r\n"
			"�ð��ܰ�ށF%s",
			GetUserHeaderCommonText(oUserHeader),
			oUserHeader.oTelemetry.nDataNum,
			GetMeasurementModeText(oUserHeader.oTelemetry.nMeasurementMode),
			GetMeasurementRangeText(oUserHeader.oTelemetry.nMeasurementRange),
			oUserHeader.oTelemetry.nSamplingRate,
			GetStatusWordText(oUserHeader.oTelemetry.oStatusWord));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetMmeStatusDataText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�X�e�[�^�X�p�P�b�g�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader			[I] ���[�U�w�b�_���
//		SMmeStatusData	&oMmeStatusData			[I] MME�X�e�[�^�X���
//		CString			&sText					[O] �\���p�e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::GetMmeStatusDataText(const SUserHeader &oUserHeader, const SMmeStatusData &oMmeStatusData, CString &sText)
{
	sText.Format(
			"%s\r\n"
			"�ް����F%d\r\n"
			"�ð��ܰ�ށF%s\r\n"
			"����Ӱ��ð��ٕϐ��F%s\r\n"
			"measureModeID�F%s\r\n"
			"measureStatus�F%s\r\n"
			"currentRangeID�F%s\r\n"
			"�޳��ݸ�߽�ϐ����F%s\r\n"
			"�o�H�F%d\r\n"
			"��{��϶��ĕϐ����F%s\r\n"
			"��{��϶��āF0x%08x\r\n"
			"�v���J�n�����ϐ����F%s\r\n"
			"�v���J�n�ݒ�l�F0x%08x\r\n"
			"�v���I�������ϐ����F%s\r\n"
			"�v���I���ݒ�l�F0x%08x\r\n"
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
//  1. �֐���
//		CMmeRecvDlg::GetErrorStatusDataText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�G���[�X�e�[�^�X�p�P�b�g�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader			&oUserHeader			[I] ���[�U�w�b�_���
//		SErrorStatusData	&oErrorStatusData		[I] �G���[�X�e�[�^�X���
//		CString				&sText					[O] �\���p�e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::GetErrorStatusDataText(const SUserHeader &oUserHeader, const SErrorStatusData &oErrorStatusData, CString &sText)
{
	CString sCommonText;

	sCommonText.Format(
			"%s\r\n"
			"�װ���ށF%s\r\n"
			"��޺��ށF%s\r\n"
			"�ް����F%d\r\n"
			"�ð��ܰ�ށF%s",
			GetUserHeaderCommonText(oUserHeader),
			GetErrorCodeText(oUserHeader.oErrorStatus.nErrorCode),
			GetSubCodeText(oUserHeader.oErrorStatus.nErrorCode, oUserHeader.oErrorStatus.nSubCode),
			oUserHeader.oErrorStatus.nDataNum,
			GetStatusWordText(oUserHeader.oErrorStatus.oStatusWord));

	switch (oUserHeader.oErrorStatus.nErrorCode) {
	case 0x02:
		sText.Format(
				"%s\r\n"
				"������́F%s\r\n"
				"�װү���ށF%s",
				sCommonText,
				oErrorStatusData.sTaskName,
				oErrorStatusData.sErrorMessage);
		break;
	case 0x03:
	case 0x04:
	case 0x05:
		sText.Format(
				"%s\r\n"
				"�װү���ށF%s",
				sCommonText,
				oErrorStatusData.sErrorMessage);
		break;
	default:
		sText = sCommonText;
		break;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetMessageDataText
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���b�Z�[�W�p�P�b�g�̕\���p�e�L�X�g�擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SUserHeader		&oUserHeader			[I] ���[�U�w�b�_���
//		SMessageData	&oMessageData			[I] ���b�Z�[�W���
//		CString			&sText					[O] �\���p�e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::GetMessageDataText(const SUserHeader &oUserHeader, const SMessageData &oMessageData, CString &sText)
{
	sText.Format(
			"%s\r\n"
			"�ް����F%d\r\n"
			"�ð��ܰ�ށF%s\r\n"
			"ү���ށF%s\r\n"
			"%s",
			GetUserHeaderCommonText(oUserHeader),
			oUserHeader.oMessage.nDataNum,
			GetStatusWordText(oUserHeader.oMessage.oStatusWord),
			oMessageData.sMessage,
			GetLasttimeHaltText(oMessageData.oLasttimeHalt));
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::DispNasaGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::DispHcorGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckNasaAutoFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pAuto File Name�`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckNasaAutoFileName()
{
	m_oSettings.oNasa.bAutoFileName = m_cCheckNasaAutoFileName;
	m_cEditNasaFileName.EnableWindow(!m_oSettings.oNasa.bAutoFileName);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckHcorAutoFileName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pAuto File Name�`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckHcorAutoFileName()
{
	m_oSettings.oHcor.bAutoFileName = m_cCheckHcorAutoFileName;
	m_cEditHcorFileName.EnableWindow(!m_oSettings.oHcor.bAutoFileName);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckEnableTempChk
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Enable Temp Chk�`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckEnableTempChk()
{
	m_oSettings.oMonitoring.bEnableTempChk = m_cCheckEnableTempChk;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedCheckEnableVoltageChk
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Enable Voltage Chk�`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedCheckEnableVoltageChk()
{
	m_oSettings.oMonitoring.bEnableVoltageChk = m_cCheckEnableVoltageChk;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::Monitoring
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���x�E�d���͈̓`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CTime				&oTimestamp			[I] �^�C���X�^���v
//		SAnalogData			&oAnalogData		[I] �A�i���O�f�[�^���
//		SStatusWord			&oStatusWord		[I] �X�e�[�^�X���[�h���
//		SMonitoringCounter	&oMonitoringCounter	[I] ���j�^�����O�J�E���^
//		LPCTSTR				pKeiro				[I] �o�H����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::Monitoring(const CTime &oTimestamp, const SAnalogData &oAnalogData, const SStatusWord &oStatusWord, SMonitoringCounter &oMonitoringCounter, LPCTSTR pKeiro)
{
	const char *aTemp[N_TEMP] = {"X", "Y", "Z", "S/B"};
	CString sMsg;
	CStringArray aMsg;

	// ���x�͈̓`�F�b�N
	if (m_oSettings.oMonitoring.bEnableTempChk) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			if (oStatusWord.aUnit[nUnit].nOnOffStatus) {
				for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
					if (oAnalogData.aUnit[nUnit].fTemp[nTemp] < m_oSettings.oMonitoring.fNormalTempRange1[nUnit][nTemp]) {
						if (++oMonitoringCounter.aTempCount[nUnit][nTemp] >= 5) {
							sMsg.Format("�ቷ�ُ� Unit%d-%s", nUnit + 1, aTemp[nTemp]);
							aMsg.Add(sMsg);
						}
					} else if (oAnalogData.aUnit[nUnit].fTemp[nTemp] > m_oSettings.oMonitoring.fNormalTempRange2[nUnit][nTemp]) {
						if (++oMonitoringCounter.aTempCount[nUnit][nTemp] >= 5) {
							sMsg.Format("�����ُ� Unit%d-%s", nUnit + 1, aTemp[nTemp]);
							aMsg.Add(sMsg);
						}
					} else
						oMonitoringCounter.aTempCount[nUnit][nTemp] = 0;
				}
			}
		}
	}

	// �d���͈̓`�F�b�N
	if (m_oSettings.oMonitoring.bEnableVoltageChk) {
		for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
			if (oStatusWord.aUnit[nUnit].nOnOffStatus) {
				if (oAnalogData.aUnit[nUnit].fHeater < m_oSettings.oMonitoring.fNormalVoltageRange1[nUnit]) {
					if (++oMonitoringCounter.aVoltageCount[nUnit] >= 5) {
						sMsg.Format("�d�������ُ� Unit%d", nUnit + 1);
						aMsg.Add(sMsg);
					}
				} else if (oAnalogData.aUnit[nUnit].fHeater > m_oSettings.oMonitoring.fNormalVoltageRange2[nUnit]) {
					if (++oMonitoringCounter.aVoltageCount[nUnit] >= 5) {
						sMsg.Format("�d������ُ� Unit%d", nUnit + 1);
						aMsg.Add(sMsg);
					}
				} else
					oMonitoringCounter.aVoltageCount[nUnit] = 0;
			}
		}
	}

	// �G���[���b�Z�[�W������΃w���X�X�e�[�^�X�ɕ\��
	if (aMsg.GetCount() != 0) {
		aMsg.InsertAt(0, CString("�o�H�F") + pKeiro);
		aMsg.InsertAt(1, oTimestamp.FormatGmt("��ѽ���߁F%Y/%m/%d %H:%M:%S"));
		CString sMsg;
		CGeneral::StrJoin(aMsg, "\r\n", sMsg);
		SetHealthStatus(sMsg);
	}

}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::SetNasaReceiveStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p��M�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bReceiveStatus		[I] Receiving or Stop
//		BOOL	bForce				[I] �����\���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetNasaEnableDigitalData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�f�W�^���f�[�^�L���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bEnable		[I] �L�� or ����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetNasaEnableAnalogData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�A�i���O�f�[�^�L���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bEnable		[I] �L�� or ����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetHcorReceiveStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p��M�X�e�[�^�X�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bReceiveStatus		[I] Receiving or Stop
//		BOOL	bForce				[I] �����\���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetHcorEnableDigitalData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�f�W�^���f�[�^�L���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bEnable		[I] �L�� or ����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::SetHcorEnableAnalogData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�A�i���O�f�[�^�L���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bEnable		[I] �L�� or ����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonSetMonitoring
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���퉷�x�͈͐ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedRadioNasaSampleRate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pSample Rate (*.s)���W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioNasaSampleRate()
{
	m_oSettings.oNasa.bAccumulate = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedRadioNasaAccumulate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�pAccumulate (*.a)���W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioNasaAccumulate()
{
	m_oSettings.oNasa.bAccumulate = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedRadioHcorSampleRate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pSample Rate (*.s)���W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioHcorSampleRate()
{
	m_oSettings.oHcor.bAccumulate = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedRadioHcorAccumulate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�pAccumulate (*.a)���W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedRadioHcorAccumulate()
{
	m_oSettings.oHcor.bAccumulate = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		OK�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnOK()
{
	// �������Ȃ��iEnter�L�[�ŏI�����Ȃ��悤�ɂ��邽�߁j
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�����Z���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnCancel()
{
	// �������Ȃ��iEsc�L�[�ŏI�����Ȃ��悤�ɂ��邽�߁j
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::OnBnClickedButtonClose
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Close�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeRecvDlg::OnBnClickedButtonClose()
{
	CloseDialog();
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::CloseDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�N���[�Y�m�F����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CMmeRecvDlg::OnMouseIn
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X���f�[�^���C���̏�ɏ�������̏���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam				[I] ���b�Z�[�W�p�����[�^
//		LPARAM		lParam				[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
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
//  1. �֐���
//		CMmeRecvDlg::OnMouseOut
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X���f�[�^���C���̏ォ��O�ꂽ���̏���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam				[I] ���b�Z�[�W�p�����[�^
//		LPARAM		lParam				[I] ���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
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
