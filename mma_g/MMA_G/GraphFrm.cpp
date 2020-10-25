//*****************************************************************************************************
//  1. �t�@�C����
//		GraphFrm.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�O���t�̃��C���t���[���E�B���h�E�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
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

// �����X�N���[���^�C�}�[
#define IDT_AUTO_SCROLL		100
#define TIMER_AUTO_SCROLL	100

// �����A���^�C���\���^�C�}�[
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
	ID_SEPARATOR,           // �X�e�[�^�X ���C�� �C���W�P�[�^
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
//  1. �֐���
//		CGraphFrame::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pFilePath			[I] ���t�@�C���̃p�X��
//		int			nGraphKind			[I] �O���t�̎�ށi�����x or ���x�j
//		int			nDataType			[I] �f�[�^�̎�ށiMMA or MME�j
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����@FALSE�F���~�܂��̓G���[
//*****************************************************************************************************
BOOL CGraphFrame::DispGraph(LPCTSTR pFilePath, int nGraphKind, int nDataType)
{
	// �O���t��ޕۑ�
	m_nGraphKind = nGraphKind;

	// �f�[�^��ޕۑ�
	m_cGraphData.m_nDataType = nDataType;

	// ���t�@�C���ǂݍ���
	if (!m_cGraphData.ReadInfoFile(pFilePath))
		return FALSE;

	// ���Ԕ͈͐ݒ�_�C�A���O�\��
	CTimeRangeDlg cTimeRangeDlg;
	cTimeRangeDlg.m_dStarttime = m_cGraphData.m_dStarttime;
	cTimeRangeDlg.m_nDataSec = (int)m_cGraphData.m_fTotalSecond;
	cTimeRangeDlg.m_sKind = (nGraphKind == GRAPH_ACCEL) ? "�����x�f�[�^�O���t" : "���x�f�[�^�O���t";
	if (cTimeRangeDlg.DoModal() == IDCANCEL)
		return FALSE;

	// �p�����[�^��ۑ�
	m_sFilePath = pFilePath;
	int nBeginTime = cTimeRangeDlg.m_nDispBegin;
	int nEndTime = cTimeRangeDlg.m_nDispEnd;
	m_cGraphData.m_dInStarttime = cTimeRangeDlg.m_dInStarttime;
	m_cGraphData.m_dInEndtime = cTimeRangeDlg.m_dInEndtime;

	// �f�[�^�ǂݍ���
	if (!m_cGraphData.ReadDataFile(nBeginTime, nEndTime, m_nGraphKind, TRUE))
		return FALSE;

	// ���ԕ\���͈�
	m_cGraphData.m_fTimeDispEnd = m_cGraphData.m_stTimeRange.fSpan;

	// �t�B���^�o�b�t�@�m��
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// �t�B���^����
	FilterProcess();

	// �t���[�������\�[�X���烍�[�h���č쐬
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, NULL, NULL);

	// �\���ƍX�V
	ShowWindow(SW_SHOW);
	UpdateWindow();

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::DispRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pFilePath			[I] ���t�@�C���̃p�X��
//		int			nGraphKind			[I] �O���t�̎�ށi�����x or ���x�j
//		BOOL		bExistLossFile		[I] �����f�[�^�̗L��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CGraphFrame::DispRealtimeGraph(LPCTSTR pFilePath, int nGraphKind, BOOL bExistLossFile)
{
	// �O���t��ޕۑ�
	m_nGraphKind = nGraphKind;
	m_bRealtime = TRUE;

	// ���t�@�C���ǂݍ���
	if (!m_cGraphData.ReadInfoFile(pFilePath))
		return FALSE;

	// �p�����[�^��ۑ�
	m_sFilePath = pFilePath;
	m_cGraphData.m_dInStarttime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime += (long)m_cGraphData.m_fTotalSecond * 1000;
	int nBeginTime = (m_cGraphData.m_dInStarttime.m_hh * 60 + m_cGraphData.m_dInStarttime.m_mm) * 60 + m_cGraphData.m_dInStarttime.m_ss;
	int nEndTime = (m_cGraphData.m_dInEndtime.m_hh * 60 + m_cGraphData.m_dInEndtime.m_mm) * 60 + m_cGraphData.m_dInEndtime.m_ss;

	// �f�[�^�ǂݍ���
	m_cGraphData.m_fAllocFactor = 1.5;	// ��Ńo�b�t�@��ǉ����邽�߁A�\��1.5�{�̃T�C�Y���m�ۂ���
	if (!m_cGraphData.ReadDataFile(nBeginTime, nEndTime, m_nGraphKind, FALSE))
		return FALSE;

	// ���ԕ\���͈�
	m_cGraphData.m_stTimeRange.fSpan = floor(m_cGraphData.m_stTimeRange.fSpan);
	m_cGraphData.m_stTimeRange.fMax = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_stTimeRange.fSpan;
	m_cGraphData.m_fTimeDispEnd = 0;

	// �t�B���^�o�b�t�@�m��
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// �t�B���^����
	FilterProcess();

	// �t���[�������\�[�X���烍�[�h���č쐬
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, NULL, NULL);

	if (bExistLossFile)
		m_wndStatusBar.SetPaneText(0, "���̃O���t�ɂ͌����f�[�^�����݂��܂��B");

	// �\���ƍX�V
	ShowWindow(SW_SHOW);
	UpdateWindow();

	// �X�y�N�g���O�����\��
	if (m_nGraphKind == GRAPH_ACCEL) {
		m_pRealtimeSpectrogramDlg = new CFftSpectrogramDlg(CWnd::GetDesktopWindow());
		m_pRealtimeSpectrogramDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd(), TRUE);

		// �\���X�V�^�C�}�[�ݒ�
		SetTimer(IDT_REALTIME_UPDATE, TIMER_REALTIME_UPDATE, NULL);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::DispZeroOffsetGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		0�_�␳�l�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphFrame		*pGraphFrame		[I] �����x�O���t�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::DispZeroOffsetGraph(CGraphFrame *pGraphFrame)
{
	// �O���t��ޕۑ�
	m_nGraphKind = GRAPH_ZERO_OFFSET;

	m_hAcclGraphWnd = pGraphFrame->m_hWnd;

	// �O���t�f�[�^��ۑ�
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

	// �c���͈͐ݒ�
	m_cGraphData.ZeroOffsetlevelRange();

	// ���ԕ\���͈�
	m_cGraphData.m_fTimeDispEnd = m_cGraphData.m_stTimeRange.fSpan;

	// �t�B���^�o�b�t�@�m��
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// �t�B���^����
	FilterProcess();

	// �t���[�������\�[�X���烍�[�h���č쐬
	LoadFrame(IDR_GRAPHFRAME, WS_OVERLAPPEDWINDOW, NULL, NULL);

	// �\���ƍX�V
	ShowWindow(SW_SHOW);
	UpdateWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::UpdateRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���O���t�X�V�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL		bExistLossFile		[I] �����f�[�^�̗L��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CGraphFrame::UpdateRealtimeGraph(BOOL bExistLossFile)
{
	// ���t�@�C���ǂݍ���
	if (!m_cGraphData.ReadInfoFile(m_sFilePath))
		return FALSE;

	// �p�����[�^��ۑ�
	m_cGraphData.m_dInStarttime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime = m_cGraphData.m_dStarttime;
	m_cGraphData.m_dInEndtime += (long)m_cGraphData.m_fTotalSecond * 1000;
	int nBeginTime = (m_cGraphData.m_dInStarttime.m_hh * 60 + m_cGraphData.m_dInStarttime.m_mm) * 60 + m_cGraphData.m_dInStarttime.m_ss;
	int nEndTime = (m_cGraphData.m_dInEndtime.m_hh * 60 + m_cGraphData.m_dInEndtime.m_mm) * 60 + m_cGraphData.m_dInEndtime.m_ss;

	// ���\���͈͂�ۑ�
	SDispRange stLevelRangeX = m_cGraphData.m_stLevelRangeX;
	SDispRange stLevelRangeY = m_cGraphData.m_stLevelRangeY;
	SDispRange stLevelRangeZ = m_cGraphData.m_stLevelRangeZ;

	// �f�[�^�ǂݍ���
	if (!m_cGraphData.ReadDataFile(nBeginTime, nEndTime, m_nGraphKind, FALSE))
		return FALSE;

	// ���ԕ\���͈�
	m_cGraphData.m_stTimeRange.fSpan = floor(m_cGraphData.m_stTimeRange.fSpan);
	m_cGraphData.m_stTimeRange.fMax = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_stTimeRange.fSpan;

	// �t�B���^�o�b�t�@�m��
	m_cGraphData.AllocFilterBuf(m_nGraphKind);

	// �t�B���^����
	FilterProcess();

	// �c���̕\���͈͂�ݒ�
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

	// �����̕\���͈͂��Đݒ�
	SetZoomTime(m_cGraphData.m_stTimeRange.fSpan / m_cGraphData.m_stTimeRange.fDisp);

	if (bExistLossFile)
		m_wndStatusBar.SetPaneText(0, "���̃O���t�ɂ͌����f�[�^�����݂��܂��B");

	// �O���t�ĕ\��
	UpdateGraph();

	// �X�y�N�g���O�����\��
	if (m_pRealtimeSpectrogramDlg == NULL && m_nGraphKind == GRAPH_ACCEL) {
		m_pRealtimeSpectrogramDlg = new CFftSpectrogramDlg(CWnd::GetDesktopWindow());
		m_pRealtimeSpectrogramDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd(), TRUE);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnCreate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCREATESTRUCT		lpCreateStruct			[I] �E�B���h�E�쐬���\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		0�F����@-1�F�G���[
//*****************************************************************************************************
int CGraphFrame::OnCreate(LPCREATESTRUCT lpCreateStruct)
{
	if (CFrameWnd::OnCreate(lpCreateStruct) == -1)
		return -1;

	// �t���[���̃N���C�A���g�̈�S�̂��߂�r���[���쐬���܂��B
	if (!m_wndGraphView.Create(NULL, NULL, AFX_WS_DEFAULT_VIEW,
		CRect(0, 0, 0, 0), this, AFX_IDW_PANE_FIRST, NULL))
	{
		TRACE0("�r���[ �E�B���h�E���쐬�ł��܂���ł����B\n");
		return -1;
	}

	// �c�[���o�[���쐬
	if (!m_wndToolBar.CreateEx(this, TBSTYLE_FLAT, WS_CHILD | WS_VISIBLE | CBRS_TOP
		| CBRS_GRIPPER | CBRS_TOOLTIPS | CBRS_FLYBY | CBRS_SIZE_DYNAMIC) ||
		!m_wndToolBar.LoadToolBar(IDR_GRAPHFRAME))
	{
		TRACE0("�c�[�� �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;      // �쐬�ł��܂���ł����B
	}

	// �X�e�[�^�X�o�[���쐬
	if (!m_wndStatusBar.Create(this) ||
		!m_wndStatusBar.SetIndicators(indicators,
		  sizeof(indicators)/sizeof(UINT)))
	{
		TRACE0("�X�e�[�^�X �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;      // �쐬�ł��܂���ł����B
	}

	// �_�C�A���O�o�[���쐬
	m_wndGraphBar.m_nGraphKind = m_nGraphKind;
	if (!m_wndGraphBar.Create(this, (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) ? IDD_GRAPH_BAR_ACCL : IDD_GRAPH_BAR_TEMP, CBRS_BOTTOM, (UINT)-1)) {
		TRACE0("�_�C�A���O �o�[�̍쐬�Ɏ��s���܂����B\n");
		return -1;      // �쐬�ł��܂���ł����B
	}

	// TODO: �c�[�� �o�[���h�b�L���O�\�ɂ��Ȃ��ꍇ�́A������ 3 �s���폜���Ă��������B
//	m_wndToolBar.EnableDocking(CBRS_ALIGN_ANY);
//	EnableDocking(CBRS_ALIGN_ANY);
//	DockControlBar(&m_wndToolBar);

	// �E�B���h�E�^�C�g���ݒ�
	switch (m_nGraphKind) {
	case GRAPH_ACCEL:
		SetWindowText(m_cGraphData.GetDataTypeText() + (m_bRealtime ? " �����x���A���^�C���O���t�\��" : " �����x�O���t�\��"));
		break;
	case GRAPH_TEMP:
		SetWindowText(m_cGraphData.GetDataTypeText() + (m_bRealtime ? " ���x���A���^�C���O���t�\��" : " ���x�O���t�\��"));
		break;
	case GRAPH_ZERO_OFFSET:
		SetWindowText(m_cGraphData.GetDataTypeText() + " 0�_�␳�l�O���t�\��");
		break;
	}

	RecalcLayout();

	// �����Y�[���{����ݒ�
	SetZoomTime(m_bRealtime ? m_cGraphData.m_stTimeRange.fSpan / gRealtimeRange : 1, FALSE);

	// �c���Y�[���{����1�ɐݒ�
	SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeY, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeZ, 1);

	// �t�B���^�\���ݒ�
	OnCbnSelchangeComboFilterBand();

	// ���ԃJ�[�\����\��
	m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	DispTimeCursor();

	// �O���t���X�V
	UpdateGraph();

	SetIcon(AfxGetApp()->LoadIcon(IDR_GRAPHFRAME), TRUE);

	// �E�B���h�E�e�[�u���ɓo�^
	m_aFrameWnd.Add(m_hWnd);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::PreCreateWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CREATESTRUCT		&cs			[I] �E�B���h�E�쐬���\����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CGraphFrame::PreCreateWindow(CREATESTRUCT &cs)
{
	if( !CFrameWnd::PreCreateWindow(cs) )
		return FALSE;

	cs.dwExStyle &= ~WS_EX_CLIENTEDGE;
	cs.lpszClass = AfxRegisterWndClass(0);

	return TRUE;
}


// CGraphFrame �f�f

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
//  1. �֐���
//		CGraphFrame::OnSetFocus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnSetFocus(CWnd* /*pOldWnd*/)
{
	// �r���[�E�B���h�E�Ƀt�H�[�J�X��^����
	m_wndGraphView.SetFocus();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnCmdMsg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�쐬�O����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT				nID				[I] �R�}���hID
//		int					nCode			[I] �R�}���h�ʒm�R�[�h
//		void				*pExtra			[I] �t���f�[�^
//		AFX_CMDHANDLERINFO	*pHandlerInfo	[I] �n���h�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	0�ȊO�F���b�Z�[�W���������ꂽ�@0:����ȊO�̏ꍇ
//*****************************************************************************************************
BOOL CGraphFrame::OnCmdMsg(UINT nID, int nCode, void *pExtra, AFX_CMDHANDLERINFO *pHandlerInfo)
{
	// �r���[�ɍŏ��ɃR�}���h����������@���^���܂��B
	if (m_wndGraphView.OnCmdMsg(nID, nCode, pExtra, pHandlerInfo))
		return TRUE;

	// ����ȊO�̏ꍇ�́A����̏������s���܂��B
	return CFrameWnd::OnCmdMsg(nID, nCode, pExtra, pHandlerInfo);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::UpdateGraph()
{
	m_bInUpdateGraph = TRUE;

	// �����̕\���͈͂��f�[�^�͈͂𒴂��Ă��Ȃ����`�F�b�N
	DispRangeCheck(m_cGraphData.m_stTimeRange);

	// �O���t�̕\�����X�V
	m_wndGraphView.UpdateGraph();

	// �����̕\���͈͂�\��
	CDataDateTime from, to;
	from.SetTime(m_cGraphData.m_dStarttime.m_YY
		, m_cGraphData.m_dStarttime.m_MM
		, m_cGraphData.m_dStarttime.m_DD
		, (int)(m_cGraphData.m_stTimeRange.fBegin * 1000));
	to = from + (int)(m_cGraphData.m_stTimeRange.fDisp * 1000);
	m_wndGraphBar.DispTimeData(from.GetStr(), to.GetStr(), m_cGraphData.m_stTimeRange.fZoom);

	// �c���̕\���͈͂�\��
	m_wndGraphBar.DispLevelDataX(m_cGraphData.m_stLevelRangeX.fBegin, m_cGraphData.m_stLevelRangeX.fBegin + m_cGraphData.m_stLevelRangeX.fDisp, m_cGraphData.m_stLevelRangeX.fZoom);
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		m_wndGraphBar.DispLevelDataY(m_cGraphData.m_stLevelRangeY.fBegin, m_cGraphData.m_stLevelRangeY.fBegin + m_cGraphData.m_stLevelRangeY.fDisp, m_cGraphData.m_stLevelRangeY.fZoom);
		m_wndGraphBar.DispLevelDataZ(m_cGraphData.m_stLevelRangeZ.fBegin, m_cGraphData.m_stLevelRangeZ.fBegin + m_cGraphData.m_stLevelRangeZ.fDisp, m_cGraphData.m_stLevelRangeZ.fZoom);
	}

	// 0�_�␳�l�\��
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET)
		m_wndGraphBar.DispZeroPointValue(m_cGraphData.m_fAverageX, m_cGraphData.m_fAverageY, m_cGraphData.m_fAverageZ);

	m_bInUpdateGraph = FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::DispRangeCheck
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���͈̓`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDispRange		&stDispRange			[I] �\���͈͏��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::DispRangeCheck(SDispRange &stDispRange)
{
	if (!m_bRealtime) {
		// �\���͈͂��f�[�^�͈͂𒴂��Ă��Ȃ����`�F�b�N
		if (stDispRange.fDisp > stDispRange.fSpan)
			stDispRange.fDisp = stDispRange.fSpan;
	}

	// �\���J�n�ʒu���f�[�^�͈̔͂ɓ����Ă��邩�`�F�b�N
	if (stDispRange.fBegin > stDispRange.fMax - stDispRange.fDisp)
		stDispRange.fBegin = stDispRange.fMax - stDispRange.fDisp;
	if (stDispRange.fBegin < stDispRange.fMin)
		stDispRange.fBegin = stDispRange.fMin;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::FilterProcess
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphFrame::SetZoomTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̃Y�[���{����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimeZoom			[I] �����̃Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::SetZoomTime(double fTimeZoom, BOOL bCheck)
{
	if (bCheck && fTimeZoom < 1)
		fTimeZoom = 1;

	m_cGraphData.m_stTimeRange.fZoom = fTimeZoom;
	m_cGraphData.m_stTimeRange.fDisp = m_cGraphData.m_stTimeRange.fSpan / m_cGraphData.m_stTimeRange.fZoom;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonTimeZoomAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����́u�S�̕\���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeZoomAll()
{
	// �Y�[���{����1�ɐݒ�
	m_cGraphData.m_stTimeRange.fBegin = m_cGraphData.m_stTimeRange.fMin;
	SetZoomTime(1);

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonTimeZoomIn
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����́u�g��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeZoomIn()
{
	// �Y�[���{�����Q�{�ɐݒ�
	SetZoomTime(m_cGraphData.m_stTimeRange.fZoom * 2);

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonTimeZoomOut
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����́u�k���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeZoomOut()
{
	// �Y�[���{����1/2�{�ɐݒ�
	SetZoomTime(m_cGraphData.m_stTimeRange.fZoom / 2);

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::SetZoomLevel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̃Y�[���{����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDispRange	&stDispRange		[I] �c���̕\���͈�
//		double		fLevelZoom			[I] �c���̃Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonLevelZoomAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���́u�S�̕\���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonLevelZoomAll()
{
	// �Y�[���{����1�ɐݒ�
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		if (m_wndGraphBar.m_cCheckZoomX)
			SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);
		if (m_wndGraphBar.m_cCheckZoomY)
			SetZoomLevel(m_cGraphData.m_stLevelRangeY, 1);
		if (m_wndGraphBar.m_cCheckZoomZ)
			SetZoomLevel(m_cGraphData.m_stLevelRangeZ, 1);
	} else
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonLevelZoomIn
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���́u�g��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonLevelZoomIn()
{
	// �Y�[���{�����Q�{�ɐݒ�
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		if (m_wndGraphBar.m_cCheckZoomX)
			SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom * 2);
		if (m_wndGraphBar.m_cCheckZoomY)
			SetZoomLevel(m_cGraphData.m_stLevelRangeY, m_cGraphData.m_stLevelRangeY.fZoom * 2);
		if (m_wndGraphBar.m_cCheckZoomZ)
			SetZoomLevel(m_cGraphData.m_stLevelRangeZ, m_cGraphData.m_stLevelRangeZ.fZoom * 2);
	} else
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom * 2);

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonLevelZoomOut
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���́u�k���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonLevelZoomOut()
{
	// �Y�[���{����1/2�{�ɐݒ�
	if (m_nGraphKind == GRAPH_ACCEL || m_nGraphKind == GRAPH_ZERO_OFFSET) {
		if (m_wndGraphBar.m_cCheckZoomX)
			SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom / 2);
		if (m_wndGraphBar.m_cCheckZoomY)
			SetZoomLevel(m_cGraphData.m_stLevelRangeY, m_cGraphData.m_stLevelRangeY.fZoom / 2);
		if (m_wndGraphBar.m_cCheckZoomZ)
			SetZoomLevel(m_cGraphData.m_stLevelRangeZ, m_cGraphData.m_stLevelRangeZ.fZoom / 2);
	} else
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, m_cGraphData.m_stLevelRangeX.fZoom / 2);

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::SetTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԃJ�[�\���ʒu�ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimeCursor			[I] �J�[�\���ʒu�̎���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::SetTimeCursor(double fTimeCursor)
{
	// �J�[�\���͈̔͂�␳
	if (fTimeCursor < m_cGraphData.m_stTimeRange.fMin)
		fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	else if (fTimeCursor > m_cGraphData.m_stTimeRange.fMax)
		fTimeCursor = m_cGraphData.m_stTimeRange.fMax;

	// ���ԃJ�[�\�����f�[�^�̑��݂���ʒu�ɕ␳
	double fSamplingRate = m_cGraphData.m_fSampleRate / m_cGraphData.m_nDownRate;
	int nDataIndex = (int)((fTimeCursor - m_cGraphData.m_stTimeRange.fMin) * fSamplingRate + 0.5);
	if (nDataIndex >= m_cGraphData.m_nDataSize)
		nDataIndex = m_cGraphData.m_nDataSize - 1;
	fTimeCursor = nDataIndex / fSamplingRate + m_cGraphData.m_stTimeRange.fMin;

	// �J�[�\���ʒu�̎��Ԃ�ۑ�
	m_cGraphData.m_fTimeCursor = fTimeCursor;

	// �J�[�\����\��
	DispTimeCursor();

	// �E�B���h�E���X�V
	m_wndGraphView.Invalidate(FALSE);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::DispTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԃJ�[�\���ʒu�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphFrame::OnPrint
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		����{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnPrint()
{
	CGraphTitle	title_dlg;

	// �^�C�g�����̓_�C�A���O�\��
	title_dlg.SetTitle(m_sTitle);
	if (title_dlg.DoModal() != IDOK)
		return;
	title_dlg.GetTitle(m_sTitle);

	CPrintDialog dlg(FALSE);

	// �p�����������ɐݒ�
	dlg.GetDefaults();
	LPDEVMODE lpDevInfo = dlg.GetDevMode();
	lpDevInfo->dmOrientation = DMORIENT_LANDSCAPE;
    dlg.m_pd.Flags &= ~PD_RETURNDEFAULT;

	// ����J�n�_�C�A���O�\��
	if(dlg.DoModal() == IDOK) {
		// ����pCDC�擾
		CDC	dc;
		dc.Attach(dlg.GetPrinterDC());

		// ����T�C�Y�擾
		int nWidth = dc.GetDeviceCaps(HORZRES);
		int nHeight = dc.GetDeviceCaps(VERTRES);
		int	nMerX = nWidth / 15;
		int	nMerY = nHeight / 15;

		// �}�[�W���ݒ�
		CRect rectView(0, 0, nWidth, nHeight);
		rectView.DeflateRect(nMerX, nMerY);
		rectView.OffsetRect(0, nMerY / 2);
		rectView.bottom -= nMerY / 4;

		// DOCINFO��p��
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		CString sDocName = "MMA-G Acceleration Graph Doc";
		docinfo.lpszDocName = sDocName;

		// ����J�n
		dc.StartDoc(&docinfo);

		// �y�[�W����J�n
		dc.StartPage();

		// �O���t���
		CGraphDraw cDrawGraph(&dc, 2);
		cDrawGraph.DrawGraph(dc, rectView, m_cGraphData, FALSE, m_nGraphKind);

		// �^�C�g�������
		PrintGraphInfo(dc, rectView);

		// �}����
		PrintSample(dc, rectView
				, &cDrawGraph.m_penGraphLineX
				, &cDrawGraph.m_penGraphLineY
				, &cDrawGraph.m_penGraphLineZ
				, &cDrawGraph.m_penGraphLineD);

		// �y�[�W����I��
		dc.EndPage();

		// ����I��
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::PrintGraphInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C�g�����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc				[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView		[I] ����͈�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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

	// �^�C�g����
	CTitlePrint title;
	// �^�C�g���󎚊J�n
	title.StartTitle(dc, rectView);

	// 1�s��
	// �^�C�g����(���l��) UnitID,Sensor Location,SensorCoordinate
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

	// �^�C�g����(�E�l��) ISS CONFIGURATION
	sPrTitle.Format("%s", m_cGraphData.m_IssConfig);
	title.PrintRight(1, sPrTitle);

	// 2�s��
	// �^�C�g����(���l��) Sampling Frequency
	sPrTitle.Format(m_cGraphData.m_nDataType == DATA_TYPE_MMA ? "SamplingFreq = %.2f Hz" : "SamplingFreq = %g Hz", m_cGraphData.m_fSampleRate);
	title.PrintLeft(2, sPrTitle);

	// �^�C�g����(�Z���^�[) Title
	title.PrintMid(2, m_sTitle);

	// 3�s��
	// �^�C�g����(�Z���^�[) sTitle
	title.PrintMid(3, sTitle);

	// �^�C�g����(�E�l��) SPAN
	sPrTitle.Format("Span = %f hours", (double)diff / 3600000.0);
	title.PrintRight(3, sPrTitle);

	if (m_cGraphData.m_nDataType == DATA_TYPE_MMA) {
		// 4�s��
		// �^�C�g����(���l��) Cutoff
		sPrTitle.Format("Cutoff = %d Hz", m_cGraphData.m_nCutoff);
		title.PrintLeft(4, sPrTitle);

		// 5�s��
		// �^�C�g����(���l��) Gain
		sPrTitle.Format("Gain = %d", m_cGraphData.m_nGain);
		title.PrintLeft(5, sPrTitle);
	}

	// �^�C�g����(�Z���^�[) Start
	sPrTitle.Format("Start GMT %s - End GMT %s", Begin.GetStrAsctime(), End.GetStrAsctime());
	title.PrintMid(5, sPrTitle);

	// �^�C�g���󎚏I��
	title.EndTitle();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::PrintSample
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C�g�����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc				[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView		[I] ����͈�
//		CPen		*x				[I] X���̐F
//		CPen		*y				[I] Y���̐F
//		CPen		*z				[I] Z���̐F
//		CPen		*d				[I] DACM�̐F
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::PrintSample(CDC &dc, CRect rectView, CPen *x, CPen *y, CPen *z, CPen *d)
{
	CString	sPrTitle;
	int	defCol = 70;
	int	defX = 12;
	int	LineLen = 5;

	// �^�C�g����
	CTitlePrint title;

	// �^�C�g���󎚊J�n
	title.StartTitle(dc, rectView);

// update 2008/03/18 by hama�@��

	// �����̃T�C�Y���擾
	CSize sizeChar = dc.GetOutputTextExtent("0");

	// �o��X�ʒu
	int	xp = defX * sizeChar.cx;
	int yp = defCol * sizeChar.cy + sizeChar.cy / 2;
	int nl = LineLen * sizeChar.cx;

	CPen *org = dc.SelectObject(d);

	if (m_nGraphKind == GRAPH_TEMP) {
		// DACM
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "�FDACM";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;

		// X��
		dc.SelectObject(x);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "�FX-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;

		// Y��
		dc.SelectObject(y);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "�FY-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;

		// Z��
		dc.SelectObject(z);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;
		sPrTitle = "�FZ-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	// �Z���T�����o��
	sPrTitle.Format("+X�F%s  +Y�F%s  +Z�F%s", (CString)m_wndGraphBar.m_cEditDirX, (CString)m_wndGraphBar.m_cEditDirY, (CString)m_wndGraphBar.m_cEditDirZ);
	title.PrintDir(rectView.Width() - dc.GetOutputTextExtent(sPrTitle).cx, defCol, sPrTitle);

	dc.SelectObject(org);

	// �^�C�g���󎚏I��
	title.EndTitle();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnClose
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u����v���j���[����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnClose()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedCheckScrollStart
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[���J�n�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckScrollStart()
{
	if (m_wndGraphBar.m_cCheckScrollStart == TRUE) {
		// �{�^���̕\�����u��~�v�ɐݒ�
		m_wndGraphBar.m_cCheckScrollStart = "��~";

		// �����X�N���[��ON
		m_wndGraphView.m_bAutoScroll = TRUE;

		// ��tick�擾
		m_dwAutoScrollTick = ::GetTickCount();

		// �^�C�}�[�ݒ�
		SetTimer(IDT_AUTO_SCROLL, TIMER_AUTO_SCROLL, NULL);
	} else {
		// �{�^���̕\�����u�J�n�v�ɐݒ�
		m_wndGraphBar.m_cCheckScrollStart = "�J�n";

		// �����X�N���[��OFF
		m_wndGraphView.m_bAutoScroll = FALSE;

		// �^�C�}�[����
		KillTimer(IDT_AUTO_SCROLL);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnTimer
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C�}�[�C�x���g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT_PTR		nIDEvent		[I] �^�C�}�[�C�x���gID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_AUTO_SCROLL:
		// �����X�N���[��
		AutoScroll();
		break;
	case IDT_REALTIME_UPDATE:
		// �����A���^�C���\���X�V
		UpdateRealtimeDisp();
		break;
	}

	CFrameWnd::OnTimer(nIDEvent);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::AutoScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::AutoScroll()
{
	// �X�N���[�����x�擾
	double fScrollSpeed = m_wndGraphBar.m_cEditScrollSpeed;

	// ���ԃX�e�b�v�v�Z
	DWORD dwCurrentTick = ::GetTickCount();
	double fTimeStep = fScrollSpeed * (dwCurrentTick - m_dwAutoScrollTick) / 1000;
	m_dwAutoScrollTick = dwCurrentTick;

	// �\���J�n�ʒu�Ǝ��ԃJ�[�\���ʒu���X�V
	m_cGraphData.m_stTimeRange.fBegin += fTimeStep;
	m_cGraphData.m_fTimeCursor += fTimeStep;

	// �f�[�^�̍ő厞�ԂɒB�������~����
	if (m_cGraphData.m_fTimeCursor >= m_cGraphData.m_stTimeRange.fMax) {
		m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMax;
		m_wndGraphBar.m_cCheckScrollStart = 0;
		OnBnClickedCheckScrollStart();
	}

	// ���ԃJ�[�\���ʒu�\��
	DispTimeCursor();

	// �O���t�\�����X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonFftSpectrum
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�X�y�N�g���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFftSpectrum()
{
	CFftSpectrumDlg *pDlg = new CFftSpectrumDlg(CWnd::GetDesktopWindow());
	pDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd());
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonFftOctave
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u1/3�I�N�^�[�u�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFftOctave()
{
	CFftOctaveDlg *pDlg = new CFftOctaveDlg(CWnd::GetDesktopWindow());
	pDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd());
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonFftSpectrogram
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�X�y�N�g���O�����v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonFftSpectrogram()
{
	// �|�C���g�������͂���Ă��邱�Ƃ��m�F����
	CString strTemp = m_wndGraphBar.m_cEditSpectrogramPointCount;
	if (!strTemp.Compare(""))
	{
		AfxMessageBox("�|�C���g�������͂���Ă���܂���B", MB_OK);
		return;
	}

	CFftSpectrogramDlg *pDlg = new CFftSpectrogramDlg(CWnd::GetDesktopWindow());
	pDlg->CreateGraph(&m_cGraphData, &m_wndGraphBar, GetSafeHwnd(), FALSE);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::UpdateSelectArea
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�I��͈͍X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::UpdateSelectArea()
{
	if (m_cGraphData.m_fSelectEnd > m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd)
		m_cGraphData.m_fSelectEnd = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd;

	// �I��͈͂��O���t�\��
	m_wndGraphView.DispSelectArea();

	// �I��͈͂𐔒l�\��
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
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonSelectAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�S�I���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonSelectAll()
{
	// �S�f�[�^��I��͈͂ɐݒ�
	m_cGraphData.m_fSelectBegin = m_cGraphData.m_stTimeRange.fMin;
	m_cGraphData.m_fSelectEnd = m_cGraphData.m_stTimeRange.fMin + m_cGraphData.m_fTimeDispEnd;

	// �I��͈͂̕\�����X�V
	UpdateSelectArea();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonSelectManual
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�͈͎w��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonSelectManual()
{
	// �I��͈͂����
	CAnalyzeRangeDlg dlg;
	dlg.m_dDataBegin = m_cGraphData.m_dInStarttime;
	dlg.m_dDataEnd = m_cGraphData.m_dInEndtime;
	if (dlg.DoModal() == IDOK) {
		// �I��͈͂�ݒ�
		m_cGraphData.m_fSelectBegin = (dlg.m_dSelectBegin - m_cGraphData.m_dInStarttime) / 1000 + m_cGraphData.m_stTimeRange.fMin;
		m_cGraphData.m_fSelectEnd = (dlg.m_dSelectEnd - m_cGraphData.m_dInStarttime) / 1000 + m_cGraphData.m_stTimeRange.fMin;

		// �I��͈͂̕\�����X�V
		UpdateSelectArea();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnUpdateButtonFft
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		FFT��̓{�^���L��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonFft(CCmdUI *pCmdUI)
{
	// �I��͈͂̊J�n�ƏI���������łȂ���ΗL��
	pCmdUI->Enable(m_cGraphData.m_fSelectBegin != m_cGraphData.m_fSelectEnd);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnUpdateButtonTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����S�̕\���{�^���L��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonTimeZoom(CCmdUI *pCmdUI)
{
	// �����̃Y�[���{�����P�łȂ���ΗL��
	pCmdUI->Enable(m_cGraphData.m_stTimeRange.fZoom != 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnUpdateButtonLevelZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���S�̕\���{�^���L��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonLevelZoom(CCmdUI *pCmdUI)
{
	// �c���̃Y�[���{�����P�łȂ���ΗL��
	pCmdUI->Enable(m_cGraphData.m_stLevelRangeX.fZoom != 1 || m_cGraphData.m_stLevelRangeY.fZoom != 1 || m_cGraphData.m_stLevelRangeZ.fZoom != 1);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnUpdateButtonSelect
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S�I���{�^���L��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonSelect(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(m_nGraphKind == GRAPH_ACCEL);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnCbnSelchangeComboSampleRate
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T���v�����g���R���{�{�b�N�X�I��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnCbnSelchangeComboSampleRate()
{
	int nIndex = m_wndGraphBar.m_cComboSampleRate.GetCurSel();
	if (nIndex >= 0) {
		// �Ԉ������[�g�擾
		m_cGraphData.m_nDownRate = (int)m_wndGraphBar.m_cComboSampleRate.GetItemData(nIndex);

		// ���ԃJ�[�\���ʒu��␳
		SetTimeCursor(m_cGraphData.m_fTimeCursor);

		// �O���t�\�����X�V
		UpdateGraph();
	}
}

// 077FD050-GYM961-0_004 2007/9/8 appended by yG ->
//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonTimeCursorToLeft
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԃJ�[�\���̍��ړ�����(�}�C�i�X���ԕ���)
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeCursorToLeft()
{
	SetTimeCursor(m_cGraphData.m_fTimeCursor - m_cGraphData.m_nDownRate / m_cGraphData.m_fSampleRate); // 1�T���v�����}�C�i�X�����Ɉړ�����
/*
	m_cGraphData.m_fTimeCursor -= 0.001; // 1ms�}�C�i�X�����Ɉړ�����

	// �J�[�\���͈̔͂�␳
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

	// �E�B���h�E���X�V
	m_wndGraphView.Invalidate(FALSE);
*/
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonTimeCursorToRight
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԃJ�[�\���̉E�ړ�����(�v���X���ԕ���)
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonTimeCursorToRight()
{
	SetTimeCursor(m_cGraphData.m_fTimeCursor + m_cGraphData.m_nDownRate / m_cGraphData.m_fSampleRate); // 1�T���v�����v���X�����Ɉړ�����
/*
	m_cGraphData.m_fTimeCursor += 0.001; // 1ms�v���X�����Ɉړ�����

	// �J�[�\���͈̔͂�␳
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

	// �E�B���h�E���X�V
	m_wndGraphView.Invalidate(FALSE);
*/
}
// 077FD050-GYM961-0_004 2007/9/8 appended by yG <-

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnEnChangeEditTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���͂��ꂽ���Ԏ��̃Y�[���{����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditTimeZoom()
{
	if (!m_bInUpdateGraph) {
		// ���Ԏ��̃Y�[���{����ݒ�
		SetZoomTime((double)m_wndGraphBar.m_cEditTimeZoom);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnRetKey
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		Enter�L�[��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//		LPARAM		lParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
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
//  1. �֐���
//		CGraphFrame::OnRetKeyEditLevelBeginX
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���̕\���J�n�ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelBeginX()
{
	if (!m_bInUpdateGraph) {
		// �w���̕\���͈͂�ݒ�
		SetLevelBegin(m_cGraphData.m_stLevelRangeX, (double)m_wndGraphBar.m_cEditLevelBeginX);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnRetKeyEditLevelBeginY
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�x���̕\���J�n�ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelBeginY()
{
	if (!m_bInUpdateGraph) {
		// �x���̕\���͈͂�ݒ�
		SetLevelBegin(m_cGraphData.m_stLevelRangeY, (double)m_wndGraphBar.m_cEditLevelBeginY);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnRetKeyEditLevelBeginZ
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y���̕\���J�n�ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelBeginZ()
{
	if (!m_bInUpdateGraph) {
		// �y���̕\���͈͂�ݒ�
		SetLevelBegin(m_cGraphData.m_stLevelRangeZ, (double)m_wndGraphBar.m_cEditLevelBeginZ);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnRetKeyEditLevelEndX
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���̕\���I���ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelEndX()
{
	if (!m_bInUpdateGraph) {
		// �w���̕\���͈͂�ݒ�
		SetLevelEnd(m_cGraphData.m_stLevelRangeX, (double)m_wndGraphBar.m_cEditLevelEndX);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnRetKeyEditLevelEndY
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�x���̕\���I���ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelEndY()
{
	if (!m_bInUpdateGraph) {
		// �x���̕\���͈͂�ݒ�
		SetLevelEnd(m_cGraphData.m_stLevelRangeY, (double)m_wndGraphBar.m_cEditLevelEndY);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnRetKeyEditLevelEndZ
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y���̕\���I���ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnRetKeyEditLevelEndZ()
{
	if (!m_bInUpdateGraph) {
		// �y���̕\���͈͂�ݒ�
		SetLevelEnd(m_cGraphData.m_stLevelRangeZ, (double)m_wndGraphBar.m_cEditLevelEndZ);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::SetLevelBegin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���\���͈͂̊J�n�ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDispRange	&stDispRange		[I] �\���͈͏��
//		double		fLevelBegin			[I] �c���J�n�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::SetLevelBegin(SDispRange &stDispRange, double fLevelBegin)
{
	stDispRange.fDisp += stDispRange.fBegin - fLevelBegin;
	stDispRange.fBegin = fLevelBegin;
	stDispRange.fZoom = stDispRange.fSpan / stDispRange.fDisp;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::SetLevelEnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���\���͈͂̏I���ʒu��ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SDispRange	&stDispRange		[I] �\���͈͏��
//		double		fLevelEnd			[I] �c���I���l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::SetLevelEnd(SDispRange &stDispRange, double fLevelEnd)
{
	stDispRange.fDisp = fLevelEnd - stDispRange.fBegin;
	stDispRange.fZoom = stDispRange.fSpan / stDispRange.fDisp;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnEnChangeEditLevelZoomX
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w���̃Y�[���{����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditLevelZoomX()
{
	if (!m_bInUpdateGraph) {
		// �w���̃Y�[���{����ݒ�
		SetZoomLevel(m_cGraphData.m_stLevelRangeX, (double)m_wndGraphBar.m_cEditLevelZoomX);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnEnChangeEditLevelZoomY
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�x���̃Y�[���{����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditLevelZoomY()
{
	if (!m_bInUpdateGraph) {
		// �x���̃Y�[���{����ݒ�
		SetZoomLevel(m_cGraphData.m_stLevelRangeY, (double)m_wndGraphBar.m_cEditLevelZoomY);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnEnChangeEditLevelZoomZ
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y���̃Y�[���{����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnEnChangeEditLevelZoomZ()
{
	if (!m_bInUpdateGraph) {
		// �y���̃Y�[���{����ݒ�
		SetZoomLevel(m_cGraphData.m_stLevelRangeZ, (double)m_wndGraphBar.m_cEditLevelZoomZ);

		// �O���t�X�V
		UpdateGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonFilterSet
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^�ݒ�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
		// �Ւf���g���P�̓��̓`�F�b�N
		if (fFilterFreq1 <= 0 || fFilterFreq1 >= fNyquistFreq) {
			CGeneral::Alert("�Ւf���g����0���傫��%.0f��菬�����l����͂��Ă��������B�i0�͕s�j", fNyquistFreq);
			m_wndGraphBar.m_cEditFilterFreq1.SetFocusAndSel();
			return;
		}

		// �Ւf���g���Q�̓��̓`�F�b�N
		if (eFilterBand == EDF_BPF || eFilterBand == EDF_BSF) {
			if (fFilterFreq2 <= 0 || fFilterFreq2 >= fNyquistFreq) {
				CGeneral::Alert("�Ւf���g����0���傫��%.0f��菬�����l����͂��Ă��������B�i0�͕s�j", fNyquistFreq);
				m_wndGraphBar.m_cEditFilterFreq2.SetFocusAndSel();
				return;
			}
		}

		// �t�B���^������
		m_cFilter.InitIIR(eFilterShape, eFilterBand, nFilterOrder, fSampleFreq, fFilterFreq1, fFilterFreq2, 0.5);

		m_bFilter = TRUE;
	} else
		m_bFilter = FALSE;

	// �t�B���^����
	FilterProcess();

	// �O���t�X�V
	UpdateGraph();

	// �J�[�\���ʒu�f�[�^�X�V
	DispTimeCursor();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnCbnSelchangeComboFilterBand
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^��ރR���{�{�b�N�X�I��ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphFrame::OnUpdateButtonFilterSet
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^�ݒ�{�^���L��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnUpdateButtonFilterSet(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(m_bFilter || m_wndGraphBar.m_cComboFilterShape.IsWindowEnabled());
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedButtonInitialize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�������v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedButtonInitialize()
{
	if (AfxMessageBox("���̃E�B���h�E�̕\����������Ԃɖ߂��܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// �����X�N���[����~
	m_wndGraphBar.m_cCheckScrollStart = FALSE;
	OnBnClickedCheckScrollStart();

	// �e�R���g���[����������
	m_wndGraphBar.InitializeControls();

	// �����Y�[���{����1�ɐݒ�
	SetZoomTime(1);

	// �c���Y�[���{����1�ɐݒ�
	SetZoomLevel(m_cGraphData.m_stLevelRangeX, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeY, 1);
	SetZoomLevel(m_cGraphData.m_stLevelRangeZ, 1);

	// ���ԃJ�[�\���E�I��͈͏�����
	m_cGraphData.m_fTimeCursor = m_cGraphData.m_stTimeRange.fMin;
	m_cGraphData.m_fSelectBegin = 0;
	m_cGraphData.m_fSelectEnd = 0;
	DispTimeCursor();

	// �Ԉ������[�g������
	m_cGraphData.m_nDownRate = 1;

	// �\���f�[�^������
	m_cGraphData.m_bDispD = TRUE;
	m_cGraphData.m_bDispX = TRUE;
	m_cGraphData.m_bDispY = TRUE;
	m_cGraphData.m_bDispZ = TRUE;

	// �t�B���^�ݒ�
	OnCbnSelchangeComboFilterBand();
	OnBnClickedButtonFilterSet();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnUserOpenGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��̓O���t�E�B���h�E�I�[�v���ʒm�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//		LPARAM		lParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnUserOpenGraph(WPARAM wParam, LPARAM lParam)
{
	// �I�[�v�����ꂽ��̓O���t�E�B���h�E�̃n���h����ۑ�����
	HWND hWnd = (HWND)wParam;
	if (hWnd)
		m_aryGraphWnd.Add(hWnd);

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnUserCloseGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��̓O���t�E�B���h�E�N���[�Y�ʒm�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//		LPARAM		lParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnUserCloseGraph(WPARAM wParam, LPARAM lParam)
{
	// �N���[�Y���ꂽ��̓O���t�E�B���h�E�̃n���h�����폜����
	HWND hWnd = (HWND)wParam;
	if (hWnd) {
		for (int i = 0; i < m_aryGraphWnd.GetCount(); i++) {
			if (m_aryGraphWnd[i] == hWnd) {
				m_aryGraphWnd.RemoveAt(i);
				break;
			}
		}
	}

	// ���A���^�C���X�y�N�g���O�����E�B���h�E
	if (m_pRealtimeSpectrogramDlg != NULL && m_pRealtimeSpectrogramDlg->m_hWnd == hWnd)
		m_pRealtimeSpectrogramDlg = NULL;

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnDestroy
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
void CGraphFrame::OnDestroy()
{
	// ��̓O���t�E�B���h�E�N���[�Y
	while (m_aryGraphWnd.GetCount() != 0)
		::DestroyWindow(m_aryGraphWnd[0]);

	// 0�_�␳�l�O���t�N���[�Y
	if (m_pZeroOffsetGraph != NULL)
		m_pZeroOffsetGraph->DestroyWindow();

	// �����x�O���t�ɃN���[�Y�ʒm
	if (m_hAcclGraphWnd != NULL)
		::SendMessage(m_hAcclGraphWnd, WM_USER_CLOSE_ZERO_OFFSET, 0, 0);

	// ���C���E�B���h�E�ɃN���[�Y�ʒm
	AfxGetMainWnd()->SendMessage(WM_USER_CLOSE_GRAPH, (WPARAM)m_hWnd);

	// �E�B���h�E�e�[�u������폜
	for (int i = 0; i < m_aFrameWnd.GetSize(); i++) {
		if (m_aFrameWnd[i] == m_hWnd) {
			m_aFrameWnd.RemoveAt(i);
			break;
		}
	}

	CFrameWnd::OnDestroy();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedCheckDispD
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���f�[�^��DACM�`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispD()
{
	m_cGraphData.m_bDispD = m_wndGraphBar.m_cCheckDispD;

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedCheckDispX
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���f�[�^��X���`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispX()
{
	m_cGraphData.m_bDispX = m_wndGraphBar.m_cCheckDispX;

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedCheckDispY
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���f�[�^��Y���`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispY()
{
	m_cGraphData.m_bDispY = m_wndGraphBar.m_cCheckDispY;

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedCheckDispZ
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���f�[�^��Z���`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::OnBnClickedCheckDispZ()
{
	m_cGraphData.m_bDispZ = m_wndGraphBar.m_cCheckDispZ;

	// �O���t�X�V
	UpdateGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::CloseAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�S�O���t�E�B���h�E�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::CloseAll()
{
	while (m_aFrameWnd.GetSize() != 0)
		::DestroyWindow(m_aFrameWnd[0]);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnMouseWheel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�}�E�X�z�C�[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nFlags			[I] ������Ă��鉼�z�L�[
//		short		zDelta			[I] ��]����
//		CPoint		pt				[I] �}�E�X�̍��W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
BOOL CGraphFrame::OnMouseWheel(UINT nFlags, short zDelta, CPoint pt)
{
	// �O���t�E�B���h�E�ɓ]��
	m_wndGraphView.OnMouseWheel(nFlags, zDelta, pt);

	return CFrameWnd::OnMouseWheel(nFlags, zDelta, pt);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::OnBnClickedCheckZeroPointGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		0�_�␳�l�O���t�\���{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphFrame::UpdateRealtimeDisp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����A���^�C���O���t�\���X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphFrame::UpdateRealtimeDisp()
{
	if (g_RealtimeData.m_bStartFlag) {
		if (m_cGraphData.m_fTimeDispEnd < m_cGraphData.m_stTimeRange.fSpan) {
			// �����f�[�^���X�L�b�v
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

			// �����x�O���t�X�V
			SetRealtimeDispEnd(m_cGraphData.m_fTimeDispEnd + nSecond);

			// ���x�O���t�X�V
			g_RealtimeData.UpdateTempGraph(this, m_cGraphData.m_fTimeDispEnd);

			// �X�y�N�g���O�����O���t�X�V
			if (m_pRealtimeSpectrogramDlg != NULL)
				m_pRealtimeSpectrogramDlg->UpdateGraph();
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphFrame::SetRealtimeDispEnd
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����A���^�C���O���t�X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimeDispEnd			[I] �\���͈�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphFrame::OnUserCloseZeroOffset
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����A���^�C���O���t�X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//		LPARAM		lParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		0
//*****************************************************************************************************
LRESULT CGraphFrame::OnUserCloseZeroOffset(WPARAM wParam, LPARAM lParam)
{
	m_pZeroOffsetGraph = NULL;
	m_wndGraphBar.m_cCheckZeroOffsetGraph = FALSE;

	return 0;
}
