//*****************************************************************************************************
//  1. �t�@�C����
//		FftSpectrogramDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g���O������̓O���t�̃_�C�A���O�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		�����ɕ����̃O���t��\���ł���悤�ɂ��邽�߁A���[�h���X�_�C�A���O�ŊJ���B
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
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

// �X�P�[���̕\���͈�
#define MIN_LEVEL	1e-12		// PSD�̉����̃f�t�H���g�l
#define MAX_LEVEL	1e-6		// PSD�̏���̃f�t�H���g�l

// ��͏���
#define FFT_SIZE	4096		// ��͕��i�Q�̗ݏ�ł��邱�Ɓj
#define STEP_SIZE	2048		// �X�e�b�v���i�C�ӂ̐������\�j

// �����X�N���[��
#define IDT_AUTO_SCROLL		100
#define TIMER_AUTO_SCROLL	100

// CFftSpectrogramDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CFftSpectrogramDlg, CFftBaseDlg)

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::CFftSpectrogramDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent		[I] �e�E�B���h�E�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CFftSpectrogramDlg::CFftSpectrogramDlg(CWnd *pParent)
	: CFftBaseDlg(CFftSpectrogramDlg::IDD, pParent)
	, m_fSelectBegin(0)
{
	// �����o�ϐ���������
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
//  1. �֐���
//		CFftSpectrogramDlg::~CFftSpectrogramDlg
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
CFftSpectrogramDlg::~CFftSpectrogramDlg()
{
	// �o�b�t�@�����
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

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_TIME_SET, "����(���ԁj�͈̔͂�ύX���邽�߂̃_�C�A���O���J���܂��B"},
	{IDC_BUTTON_FILTER_SET, "�ݒ肵���t�B���^���f�[�^�ɓK�p���܂��B"},
	{IDC_BUTTON_CSV, "�X�y�N�g���O�������l�f�[�^�\���_�C�A���O���J���܂��B"},
	{IDC_BUTTON_INITIALIZE, "���̃E�B���h�E�̕\�����e��������Ԃɖ߂��܂��B"},
	{IDC_BUTTON_PRINT, "�X�y�N�g���O������̓O���t��������܂��B"},
	{IDC_BUTTON_CLOSE, "���̃E�B���h�E����܂��B"},
	{IDC_COMBO_FILTER_BAND, "�t�B���^�̎�ނ��A�����i�S��ʉ߁j�ALPF�i���ʉ߁j�AHPF�i����ʉ߁j�ABPF�i�ш�ʉ߁j�ABSF�i�ш�j�~�j����I�����܂��B"},
	{IDC_COMBO_FILTER_ORDER, "�t�B���^�̎�����ݒ肵�܂��B�������傫���قǋ}�s�ȃt�B���^�ƂȂ�܂��B"},
	{IDC_COMBO_FILTER_SHAPE, "�t�B���^�̓������AButterworth�i�o�^�[���[�X�����j�AChebyshev�i�`�F�r�V�F�t�����j�ABessel�i�x�b�Z�������j����I�����܂��B"},
	{IDC_COMBO_WINDOW_FUNCTION, "FFT�v�Z���ɓK�p���鑋�֐���I�����܂��B"},
	{IDC_BUTTON_TIME_ZOOM_ALL, "����(���ԁj�̔{�����P�ɐݒ肵�A�S�̂�\�����܂��B"},
	{IDC_BUTTON_TIME_ZOOM_IN, "����(���ԁj�̔{�����Q�{�Ɋg�債�܂��B"},
	{IDC_BUTTON_TIME_ZOOM_OUT, "����(���ԁj�̔{����1/2�{�ɏk�����܂��B"},
	{IDC_CHECK_SCROLL_START, "�J�[�\���̎��Ԃ������I�ɉE�ֈړ����܂��B���쒆�ɃN���b�N����ƒ�~���܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::CreateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�E�B���h�E�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphData		*pGraphData		[I] �O���t�f�[�^�N���X�ւ̃|�C���^
//		CGraphBar		*pGraphBar		[I] �O���t�o�[�ւ̃|�C���^
//		HWND			hWndParent		[I] �e�E�B���h�E�n���h��
//		BOOL			bRealtime		[I] ���A���^�C���\���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::CreateGraph(CGraphData *pGraphData, CGraphBar *pGraphBar, HWND hWndParent, BOOL bRealtime)
{
	// �O���t�f�[�^�ւ̃|�C���^��ۑ�
	m_pGraphData = pGraphData;
	m_pGraphBar = pGraphBar;
	m_hWndParent = hWndParent;
	m_bRealtime = bRealtime;

	// �\���͈͂�ۑ�
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

	// �v�Z����
	if (!m_bRealtime && !IsEnableCalc()) {
		delete this;
		return;
	}

	// ���[�h���X�Ń_�C�A���O��\��
	DoModeless();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::UpdateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���A���^�C���O���t�E�B���h�E�X�V����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::UpdateGraph()
{
	// �\���͈͂�ۑ�
	m_fSelectEnd = m_pGraphData->m_stTimeRange.fMin + max(m_pGraphData->m_fTimeDispEnd, gRealtimeRange);
	m_fSelectBegin = m_fSelectEnd - gRealtimeRange;
	m_fTimeEnd = m_fSelectEnd;

	// �\���͈͕\��
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

	// �v�Z
	CalcRealtime();

	// �J�[�\���ʒu�f�[�^�\��
	DispCursor();

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnInitDialog
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
BOOL CFftSpectrogramDlg::OnInitDialog()
{
	CFftBaseDlg::OnInitDialog();

	// �^�C�g���\��
	SetWindowText(m_bRealtime ? "�X�y�N�g���O�������A���^�C���\��" : "�X�y�N�g���O�����\��");

	SetIcon(AfxGetApp()->LoadIcon(IDI_SPECTROGRAM), TRUE);

	// �X�e�[�^�X�o�[�쐬
	CreateStatusBar();

	// ���֐��v���_�E���ݒ�
	m_cFft.SetWindowFuncList(m_cComboWindowFunction, WF_HANNING);

	// �R���g���[���̈ʒu�𒲐�
	SetAllCtlPosition(0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_FFT_GRAPH, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_PRINT, 1, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_CLOSE, 1, 1, 1, 1);

// 077FD050-GYM961-0_003 2007/9/8 appended by yG ->
	// �Z���T/�v�����G���A��\��
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

	// �t�B���^�̐ݒ�
	CGeneral::SetFilterBandList(m_cComboFilterBand, m_pGraphBar->m_cComboFilterBand.GetCurSel());
	CGeneral::SetFilterOrderList(m_cComboFilterOrder, m_pGraphBar->m_cComboFilterOrder.GetCurSel());
	CGeneral::SetFilterTypeList(m_cComboFilterShape, m_pGraphBar->m_cComboFilterShape.GetCurSel());
	m_cEditFilterFreq1 = (CString)m_pGraphBar->m_cEditFilterFreq1;
	m_cEditFilterFreq2 = (CString)m_pGraphBar->m_cEditFilterFreq2;
	OnCbnSelchangeComboFilterBand();
	SetFilter(FALSE);

	// �e�R���g���[��������
	InitializeControls();

	// ��ʕ��������x�O���t�ƍ��킹��
	CRect rectAccl;
	CRect rectSpectrogram;
	CRect rectScreen;
	::SystemParametersInfo(SPI_GETWORKAREA, 0, &rectScreen, 0);
	::GetWindowRect(m_hWndParent, &rectAccl);
	GetWindowRect(rectSpectrogram);
	rectSpectrogram.right = rectSpectrogram.left + rectAccl.Width();
	SetWindowPos(NULL, (rectScreen.Width() - rectSpectrogram.Width()) / 2, rectSpectrogram.top, rectSpectrogram.Width(), rectSpectrogram.Height(), SWP_NOZORDER);

	// �v�Z�Ɏ��Ԃ�������̂ł����ŃE�B���h�E�\��
	ShowWindow(SW_SHOW);

	// ���A���^�C���\��������
	if (m_bRealtime) {
		m_cButtonTimeSet.EnableWindow(FALSE);
		InitRealtime();
	}

	// �v�Z���O���t�\��
	CalcAndDispGraph();

	// �G�f�B�b�g�R���g���[���̗L�������ݒ�
	m_cEditFreqMin.SetValidChar(VC_NUM | VC_POINT);
	m_cEditFreqMax.SetValidChar(VC_NUM | VC_POINT);
	m_cEditLevelMin.SetValidChar(VC_NUM | VC_MINUS | VC_POINT | VC_EXP);
	m_cEditLevelMax.SetValidChar(VC_NUM | VC_MINUS | VC_POINT | VC_EXP);
	m_cEditFilterFreq1.SetValidChar(VC_NUM | VC_POINT);
	m_cEditFilterFreq2.SetValidChar(VC_NUM | VC_POINT);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::InitializeControls
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�e�R���g���[������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::InitializeControls()
{
	// �X�P�[���͈͂̏����l���Z�b�g
	m_fScaleFreqMin = 0;
	m_fScaleFreqMax = m_pGraphData->m_fSampleRate / 2;
	m_bScaleFreqLog = FALSE;
	m_fScaleLevelMin = MIN_LEVEL;
	m_fScaleLevelMax = MAX_LEVEL;
	m_bScaleLevelLog = TRUE;

	// �����̏����l���Z�b�g
	m_cEditFreqMin = m_fScaleFreqMin;
	m_cEditFreqMax = m_fScaleFreqMax;
	m_cRadioFreqLog = m_bScaleFreqLog;
	m_cRadioFreqLinear = !m_bScaleFreqLog;

	// �c���̏����l���Z�b�g
	m_cEditLevelMin = m_fScaleLevelMin;
	m_cEditLevelMax = m_fScaleLevelMax;
	m_cRadioLevelLog = m_bScaleLevelLog;
	m_cRadioLevelLinear = !m_bScaleLevelLog;

	// ���֐��̏����l���Z�b�g
	m_nWindowFunc = WF_HANNING;
	m_cComboWindowFunction.SetCurSel(m_nWindowFunc);

	// ���ԃY�[���̏����l���Z�b�g
	m_fTimeOffset = 0;
	m_fTimeZoom = 1.0;
	SetTimeZoom();

	// �����X�N���[���̏����l���Z�b�g
	m_cEditScrollSpeed = 1;
	m_cEditScrollFreq.Blank();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonClose
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u����v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonClose()
{
	// �E�B���h�E�����
	OnCancel();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::CalcAndDispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v�Z�ƃO���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcAndDispGraph()
{
	if (m_bRealtime) {
		m_fCalcTime = max(m_pGraphData->m_fTimeDispEnd - m_nTimeDataNum, 0);
		CalcRealtime();
	} else {
		if (! IsEnableCalc())
			return;

		// �\���͈͕\��
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

		// �X�e�[�^�X�o�[��"�v�Z��"��\��
		SetStatusBar("�v�Z��");

		// �O���t�E�B���h�E���\��
		m_cStaticFftGraph.HideGraph();
		UpdateWindow();

		// �f�[�^�v�Z����
		CalcData();
	}

	// �O���t�\��
	DispGraph();

	// �X�e�[�^�X�o�[��"���f�B"��\��
	SetStatusBar("���f�B");
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::IsEnableCalc
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		bool	true�F����@false:�G���[
//*****************************************************************************************************
bool CFftSpectrogramDlg::IsEnableCalc()
{
	int	nFftSize = 256;
	// �I��͈͂̃f�[�^�̃C���f�b�N�X���擾
	int nBeginIndex = (int)((m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);

	// FFT�T�C�Y�A�X�y�N�g���f�[�^�T�C�Y��ݒ�
	int nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;

	// 1�|�C���g������̃f�[�^��[�b]�����߂�
	int	nPointCount = m_pGraphBar->m_cEditSpectrogramPointCount;
	double dWidthPerPoint = (double)(m_fSelectEnd - m_fSelectBegin)  / nPointCount;
	// 1�|�C���g������̉�͂���f�[�^�������߂�
	double dDataPerPoint = dWidthPerPoint * m_pGraphData->m_fSampleRate * 2;
	// 1�|�C���g������̉�̓f�[�^���𒴂��Ȃ��ő��2�̗ݏ����͕��Ƃ���
	// 2^0, 2^1, 2^2, 2^3, 2^4, 2^5, 2^6, 2^7, 2^8, 2^9, 2^10, 2^11, 2^12, 2^13, 2^14
	//   1    2    4    8   16   32   64  128  255  512  1024  2048  4096  8192 16384
	if (dDataPerPoint < 256)
		nFftSize = 256; // �ŏ��l:2^8
// 2008/8/12 modified by yG ->
//	else if (dDataPerPoint > 16384)
//		nFftSize = 16384; // �ő�l:2~
	else if (dDataPerPoint > 67108864)
		nFftSize = 67108864; // �ő�l:2~
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
	int nStepSize = nFftSize / 2; // ���炵���́C��͕���50��
	// ���Ԃ̃p�����[�^��ݒ�
	int nTimeDataNum = (nDataSize - nFftSize) / nStepSize;

	// �I�������͈͂̃f�[�^�T�C�Y���`�F�b�N
	if (nTimeDataNum < 1) {
		if (!m_bRealtime)
			CGeneral::Alert("�I�������͈͂����������邽�߁A�O���t��\���ł��܂���B");
		return false;
	}

	return true;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::CalcData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����	FALSE�F�G���[
//*****************************************************************************************************
BOOL CFftSpectrogramDlg::CalcData()
{
	// �I��͈͂̃f�[�^�̃C���f�b�N�X���擾
	int nBeginIndex = (int)((m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);

	if (nEndIndex > m_pGraphData->m_nDataSize)
		nEndIndex = m_pGraphData->m_nDataSize;

	// FFT�T�C�Y�A�X�y�N�g���f�[�^�T�C�Y��ݒ�
	int nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;

// 077FD050-GYM961-0_005 2007/9/8 modified by yG ->
//	m_nFftSize = FFT_SIZE;
//	m_nStepSize = STEP_SIZE;
	// 1�|�C���g������̃f�[�^��[�b]�����߂�
	int	nPointCount = m_pGraphBar->m_cEditSpectrogramPointCount;
	double dWidthPerPoint = (double)(m_fSelectEnd - m_fSelectBegin) / nPointCount;
	// 1�|�C���g������̉�͂���f�[�^�������߂�
	double dDataPerPoint = dWidthPerPoint * m_pGraphData->m_fSampleRate * 2;
	// 1�|�C���g������̉�̓f�[�^���𒴂��Ȃ��ő��2�̗ݏ����͕��Ƃ���
	// 2^0, 2^1, 2^2, 2^3, 2^4, 2^5, 2^6, 2^7, 2^8, 2^9, 2^10, 2^11, 2^12, 2^13, 2^14
	//   1    2    4    8   16   32   64  128  255  512  1024  2048  4096  8192 16384
// 2008/8/12 modified by yG ->
	//  2^15, 2^16,  2^17,   2^18,   2^19,    2^20,    2^21,    2^22,    2^23,     2^24,     2^25,     2^26
	// 32768 65535 131072  262144  524288  1048576  2097152  4194304  8388608  16777216  33554432  67108864
	m_nFftSize = 256; // �ŏ��l:2^8
	if (dDataPerPoint < 256)
		m_nFftSize = 256; // �ŏ��l:2^8
	else if (dDataPerPoint > 67108864)
		m_nFftSize = 67108864; // �ő�l:2^26
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
	//m_nFftSize = 256; // �ŏ��l:2^8
	//if (dDataPerPoint < 256)
	//	m_nFftSize = 256; // �ŏ��l:2^8
	//else if (dDataPerPoint > 16384)
	//	m_nFftSize = 16384; // �ő�l:2~
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
	m_nStepSize = m_nFftSize / 2; // ���炵���́C��͕���50��
// 077FD050-GYM961-0_005 2007/9/8 modified by yG <-

	m_nSpectrumDataSize = m_nFftSize / 2;
	m_fSampleRate = m_pGraphData->m_fSampleRate / m_pGraphData->m_nDownRate;

	// ���Ԃ̃p�����[�^��ݒ�
	m_nTimeDataNum = (nDataSize - m_nFftSize) / m_nStepSize;
	m_fTimeStep = m_nStepSize / m_fSampleRate;
	m_fTimeEnd = m_fSelectBegin + m_nTimeDataNum * m_fTimeStep;

	// �o�b�t�@�m��
	AllocBuf();

	// �X�y�N�g���O�����v�Z
	CalcSpectrogram(
			m_pGraphData->m_pCurrentDataX + nBeginIndex,
			m_pGraphData->m_pCurrentDataY + nBeginIndex,
			m_pGraphData->m_pCurrentDataZ + nBeginIndex,
			m_pSpectrogramG);

	// �v�Z�ς݃t���O�Z�b�g
	m_bCalculation = TRUE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::AllocBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�b�t�@�m�ۏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::AllocBuf()
{
	// �o�b�t�@���
	FreeBuf();

	// �o�b�t�@�m��
	int nAllocSize = m_nSpectrumDataSize * m_nTimeDataNum;
	m_pSpectrogramG = new double[nAllocSize];
	memset(m_pSpectrogramG, 0, nAllocSize * sizeof(double));
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::FreeBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�b�t�@�������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::FreeBuf()
{
	if (m_pSpectrogramG != NULL) {
		delete [] m_pSpectrogramG;
		m_pSpectrogramG = NULL;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::DispGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::DispGraph()
{
	// �O���t�\��
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
//  1. �֐���
//		CFftSpectrogramDlg::CalcSpectrogram
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�X�y�N�g���O�����v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		*pBinaryDataX		[I] X���o�C�i���f�[�^�ւ̃|�C���^
//		double		*pBinaryDataY		[I] Y���o�C�i���f�[�^�ւ̃|�C���^
//		double		*pBinaryDataZ		[I] Z���o�C�i���f�[�^�ւ̃|�C���^
//		double		*pSpectrogram		[O] �X�y�N�g���O�����f�[�^�o�b�t�@�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcSpectrogram(const double *pBinaryDataX, const double *pBinaryDataY, const double *pBinaryDataZ, double *pSpectrogram)
{
	int i, j;

	// PSD�o�b�t�@�m��
	double *pSpectrumX = new double[m_nSpectrumDataSize];
	double *pSpectrumY = new double[m_nSpectrumDataSize];
	double *pSpectrumZ = new double[m_nSpectrumDataSize];

	for (i = 0; i < m_nTimeDataNum; i++) {
		// �o�C�i���f�[�^�ʒu�I�t�Z�b�g
		int nOffset = i * m_nStepSize * m_pGraphData->m_nDownRate;

		// PSD�v�Z
		CalcSpectrum(pBinaryDataX + nOffset, pSpectrumX);
		CalcSpectrum(pBinaryDataY + nOffset, pSpectrumY);
		CalcSpectrum(pBinaryDataZ + nOffset, pSpectrumZ);

		// ���͌v�Z
		for (j = 0; j < m_nSpectrumDataSize; j++)
			*pSpectrogram++ = pSpectrumX[j] + pSpectrumY[j] + pSpectrumZ[j];
	}

	// PSD�o�b�t�@���
	delete [] pSpectrumX;
	delete [] pSpectrumY;
	delete [] pSpectrumZ;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::CalcSpectrum
//-------------------------------------------------------------------------------------------------
//  2. �@�\
//		PSD�v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		*pBinaryData		[I] �o�C�i���f�[�^�ւ̃|�C���^
//		double		*pSpectrum			[O] �X�y�N�g���f�[�^�o�b�t�@�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcSpectrum(const double *pBinaryData, double *pSpectrum)
{
	int i;
	double fAve;

	// FFT�o�b�t�@�m��
	double *pFftBuf = new double[m_nFftSize];

	// ���ϒl�擾
	if (m_pGraphBar->m_cCheckZeroHosei) {
		double fSum = 0;
		for (i = 0; i < m_nFftSize; i++)
			fSum += pBinaryData[i * m_pGraphData->m_nDownRate];
		fAve = fSum / m_nFftSize;
	} else
		fAve = 0;

	// FFT�o�b�t�@�Ƀf�[�^�R�s�[
	for (i = 0; i < m_nFftSize; i++)
		pFftBuf[i] = (pBinaryData[i * m_pGraphData->m_nDownRate] - fAve) * 1e-6;

	// �t�B���^����
	if (m_bFilter)
		m_cFilter.ExecIIR(pFftBuf, pFftBuf, m_nFftSize);

	// ���֐�
	m_cFft.WindowFunc(m_nWindowFunc, m_nFftSize, pFftBuf);

	// FFT���s
	m_cFft.FFT(m_nFftSize, pFftBuf);

	// �p���[�X�y�N�g�����x(PSD)�v�Z
	double fPsd = 2 / (m_nFftSize * m_fSampleRate);
	double x, y;
//	pFftBuf[0] = 0;
	for (i = 1; i < m_nSpectrumDataSize - 1; i++) {
		double x = pFftBuf[i * 2];
		double y = pFftBuf[i * 2 + 1];
		pSpectrum[i] = (x * x + y * y) * fPsd;
	}

	// K=0 and k=N/2�̃P�[�X�̌v�Z
	fPsd = 1 / (m_nSpectrumDataSize * m_fSampleRate);
	x = pFftBuf[0];
	y = pFftBuf[1];
	pSpectrum[0] = (x * x + y * y) * fPsd;

	x = pFftBuf[m_nSpectrumDataSize - 2];
	y = pFftBuf[m_nSpectrumDataSize - 1];
	pSpectrum[m_nSpectrumDataSize - 1] = (x * x + y * y) * fPsd;

	// FFT�o�b�t�@���
	delete [] pFftBuf;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnCbnSelchangeComboWindowFunction
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���֐��I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnCbnSelchangeComboWindowFunction()
{
	// �I���������֐����擾
	m_nWindowFunc = m_cComboWindowFunction.GetCurSel();

	// �Čv�Z���\��
	CalcAndDispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�T�C�Y�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT	nType			[I] �T�C�Y�ύX�̃^�C�v
//		int		cx				[I] �E�B���h�E�̕�
//		int		cy				[I] �E�B���h�E�̍���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnSize(UINT nType, int cx, int cy)
{
	CFftBaseDlg::OnSize(nType, cx, cy);

	// �J�[�\���\��
	DispCursor();

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonPrint
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
void CFftSpectrogramDlg::OnBnClickedButtonPrint()
{
	CGraphTitle	title_dlg(this);

	// �^�C�g�����̓_�C�A���O�\��
	title_dlg.SetTitle(m_sTitle);
	if (title_dlg.DoModal() != IDOK)
		return;
	title_dlg.GetTitle(m_sTitle);

	CPrintDialog dlg(FALSE, PD_ALLPAGES | PD_USEDEVMODECOPIES | PD_NOPAGENUMS | PD_HIDEPRINTTOFILE | PD_NOSELECTION, this);

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

		// �}�[�W���ݒ�
		CRect rectView(0, 0, nWidth, nHeight);
		rectView.DeflateRect(nWidth / 10, nHeight / 10);

		// DOCINFO��p��
		DOCINFO	docinfo;
		memset(&docinfo, 0, sizeof(docinfo));
		docinfo.cbSize  = sizeof(docinfo);
		docinfo.lpszDocName = "MMA-G Spectrogram Graph Doc";

		// ����J�n
		dc.StartDoc(&docinfo);

		// �y�[�W����J�n
		dc.StartPage();

		// �O���t���
		CFftSpectrogramDraw cDrawGraph(&dc, 2);
		cDrawGraph.DrawGraph(dc, rectView,
				m_pSpectrogramG, m_nSpectrumDataSize, m_nTimeDataNum, m_fSampleRate,
				m_fSelectBegin, m_fTimeEnd,
				m_fTimeOffset, m_fTimeZoom,
				GetMinFreq(), m_fScaleFreqMax, m_bScaleFreqLog,
				GetMinLevel(), m_fScaleLevelMax, m_bScaleLevelLog);

		// �^�C�g�������
		PrintGraphInfo(dc, rectView);

		// �y�[�W����I��
		dc.EndPage();

		// ����I��
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::PrintGraphInfo
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

	// �^�C�g����
	CTitlePrint title;
	// �^�C�g���󎚊J�n
	title.StartTitle(dc, rectView);

	// 1�s��
	// �^�C�g����(���l��) UnitID,Sensor Location,SensorCoordinate
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

	// �^�C�g����(�E�l��) ISS CONFIGURATION
	sPrTitle.Format("%s", m_pGraphData->m_IssConfig);
	title.PrintRight(defCol + 1, sPrTitle);

	// 2�s��
	// �^�C�g����(���l��) Sampling Frequency
	sPrTitle.Format(m_pGraphData->m_nDataType == DATA_TYPE_MMA ? "SamplingFreq = %.2f Hz" : "SamplingFreq = %g Hz", m_pGraphData->m_fSampleRate);
	title.PrintLeft(defCol + 2, sPrTitle);

	// �^�C�g����(�Z���^�[) Title
	title.PrintMid(defCol + 2, m_sTitle);

	// �^�C�g����(�E�l��) Window
	sPrTitle.Format("%s", g_tWindowFunc[m_nWindowFunc]);
	title.PrintRight(defCol + 2, sPrTitle);

	// 3�s��
	// �^�C�g����(���l��) df Nfft
	sPrTitle.Format("df = %f Hz, Nfft = %d", m_fSampleRate / m_nSpectrumDataSize, m_nSpectrumDataSize);
	title.PrintLeft(defCol + 3, sPrTitle);

	// �^�C�g����(�Z���^�[) sTitle
	title.PrintMid(defCol + 3, sTitle);

	// �^�C�g����(�E�l��) SPAN
	sPrTitle.Format("Span = %f hours", (double)diff / 3600000.0);
	title.PrintRight(defCol + 3, sPrTitle);

	if (m_pGraphData->m_nDataType == DATA_TYPE_MMA) {
		// 4�s��
		// �^�C�g����(���l��) Cutoff
		sPrTitle.Format("Cutoff = %d Hz", m_pGraphData->m_nCutoff);
		title.PrintLeft(defCol + 4, sPrTitle);

		// 5�s��
		// �^�C�g����(���l��) Gain
		sPrTitle.Format("Gain = %d", m_pGraphData->m_nGain);
		title.PrintLeft(defCol + 5, sPrTitle);
	}

	// �^�C�g����(�Z���^�[) Start End
	sPrTitle.Format("Start GMT %s - End GMT %s", Begin.GetStrAsctime(), End.GetStrAsctime());
	title.PrintMid(defCol + 5, sPrTitle);

	// �Z���T�����o��
	CSize sizeChar = dc.GetOutputTextExtent("0");
	sPrTitle.Format("+X�F%s  +Y�F%s  +Z�F%s", (CString)m_pGraphBar->m_cEditDirX, (CString)m_pGraphBar->m_cEditDirY, (CString)m_pGraphBar->m_cEditDirZ);
	title.PrintDir(rectView.Width() - dc.GetOutputTextExtent(sPrTitle).cx, 67, sPrTitle);

	// �^�C�g���󎚏I��
	title.EndTitle();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::GetMinFreq
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̃X�P�[���̍ŏ��l���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		double	�X�P�[���̍ŏ��l
//*****************************************************************************************************
double CFftSpectrogramDlg::GetMinFreq()
{
	// �ΐ��\����0Hz�̏ꍇ�͍ŏ��͈͂�Ԃ�
	if (m_bScaleFreqLog && m_fScaleFreqMin <= 0)
		return pow(10, ceil(log10(m_fSampleRate / m_nFftSize)));
	else
		return m_fScaleFreqMin;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::GetMinLevel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̃X�P�[���̍ŏ��l���擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		double	�X�P�[���̍ŏ��l
//*****************************************************************************************************
double CFftSpectrogramDlg::GetMinLevel()
{
	// �ΐ��\����0���x���̏ꍇ�͍ŏ��͈͂�Ԃ�
	if (m_bScaleLevelLog && m_fScaleLevelMin <= 0)
		return MIN_LEVEL;
	else
		return m_fScaleLevelMin;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::SetCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���ʒu���Z�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fCursorX		[I] 0�`1�ɐ��K�����ꂽ�}�E�X�J�[�\����X���W
//		double		fCursorY		[I] 0�`1�ɐ��K�����ꂽ�}�E�X�J�[�\����Y���W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::SetCursor(double fCursorX, double fCursorY)
{
	if (fCursorX == 0 && fCursorY == 0) {
		m_fCursorTime = 0;
		m_fCursorFreq = 0;
	} else {
		// �J�[�\���̈ʒu���玞�Ԃ��v�Z
		m_fCursorTime = m_fSelectBegin + m_fTimeOffset + (m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom * fCursorX;

		// �J�[�\���̈ʒu������g�����v�Z
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
//  1. �֐���
//		CFftSpectrogramDlg::DispCursorData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���ʒu�f�[�^��\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::DispCursor()
{
	if (!m_bCalculation)
		return;

	if (m_fCursorTime == 0 && m_fCursorFreq == 0) {
		// �J�[�\�����X�P�[���͈̔͊O�̎��̓f�[�^������
		m_cEditCursorFreq.Blank();
		m_cEditCursorTime.Blank();
		m_cEditCursorG.Blank();

		// �J�[�\��������
		m_cStaticFftGraph.DispCursor(0, 0);
	} else {
		int nTimeIndex = (int)((m_fCursorTime - m_fSelectBegin) / m_fTimeStep);
		if (nTimeIndex >= m_nTimeDataNum)
			nTimeIndex = m_nTimeDataNum - 1;

		int nFreqIndex = (int)(m_fCursorFreq / m_fSampleRate * m_nFftSize);
		if (nFreqIndex >= m_nSpectrumDataSize)
			nFreqIndex = m_nSpectrumDataSize - 1;

		// �J�[�\���ʒu�̃f�[�^��\��
		m_cEditCursorFreq = m_fCursorFreq;
		m_cEditCursorTime = CGeneral::FormatTime(m_fCursorTime, 1);
		if (m_fCursorTime <= m_fSelectBegin + m_pGraphData->m_fTimeDispEnd)
			m_cEditCursorG = CGeneral::FormatExp(m_pSpectrogramG[nTimeIndex * m_nSpectrumDataSize + nFreqIndex], 3);
		else
			m_cEditCursorG.Blank();

		// �J�[�\����\��
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
//  1. �֐���
//		CFftSpectrogramDlg::OnRetKey
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
//  1. �֐���
//		CFftSpectrogramDlg::OnRetKeyEditFreqMin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̍ŏ��l�G�f�B�b�g�{�b�N�X�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditFreqMin()
{
	// �c���̍ŏ��l
	m_fScaleFreqMin = m_cEditFreqMin;
	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnRetKeyEditFreqMax
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̍ő�l�G�f�B�b�g�{�b�N�X�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditFreqMax()
{
	// �c���̍ŏ��l
	m_fScaleFreqMax = m_cEditFreqMax;
	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedRadioFreqLinear
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̐��`�X�P�[�����W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioFreqLinear()
{
	if (m_bScaleFreqLog) {
		// �c���̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleFreqLog = FALSE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedRadioFreqLog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̑ΐ��X�P�[�����W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioFreqLog()
{
	if (!m_bScaleFreqLog) {
		// �c���̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleFreqLog = TRUE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnRetKeyEditLevelMin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		PSD���̍ŏ��l�G�f�B�b�g�{�b�N�X�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditLevelMin()
{
	// PSD���̍ŏ��l
	m_fScaleLevelMin = m_cEditLevelMin;
	// �O���t�\��
	DispGraph();
}



//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnRetKeyEditLevelMax
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̍ő�l�G�f�B�b�g�{�b�N�X�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnRetKeyEditLevelMax()
{
	// PSD���̍ŏ��l
	m_fScaleLevelMax = m_cEditLevelMax;
	// �O���t�\��
	DispGraph();
}


//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedRadioLevelLinear
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̐��`�X�P�[�����W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioLevelLinear()
{
	if (m_bScaleLevelLog) {
		// PSD���̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleLevelLog = FALSE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedRadioLevelLog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̑ΐ��X�P�[�����W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedRadioLevelLog()
{
	if (!m_bScaleLevelLog) {
		// PSD���̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleLevelLog = TRUE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::SetFilter
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�B���^�̐ݒ�Ɋ�Â��ăt�B���^������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BOOL	bDispAlert			[I] TRUE:�t�B���^�̐ݒ肪�s���̏ꍇ�ɃA���[�g��\������@FALSE:�\�����Ȃ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE:����@FALSE:�t�B���^�̐ݒ肪�s��
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
		// �Ւf���g���P�̓��̓`�F�b�N
		if (fFilterFreq1 <= 0 || fFilterFreq1 >= fNyquistFreq) {
			if (bDispAlert) {
				CGeneral::Alert("�Ւf���g����0���傫��%.0f��菬�����l����͂��Ă��������B�i0�͕s�j", fNyquistFreq);
				m_cEditFilterFreq1.SetFocusAndSel();
			}
			return FALSE;
		}

		// �Ւf���g���Q�̓��̓`�F�b�N
		if (eFilterBand == EDF_BPF || eFilterBand == EDF_BSF) {
			if (fFilterFreq2 <= 0 || fFilterFreq2 >= fNyquistFreq) {
				if (bDispAlert) {
					CGeneral::Alert("�Ւf���g����0���傫��%.0f��菬�����l����͂��Ă��������B�i0�͕s�j", fNyquistFreq);
					m_cEditFilterFreq2.SetFocusAndSel();
				}
				return FALSE;
			}
		}
	}

	if ((int)eFilterBand != -1) {
		// �t�B���^������
		m_cFilter.InitIIR(eFilterShape, eFilterBand, nFilterOrder, fSampleFreq, fFilterFreq1, fFilterFreq2, 0.5);

		m_bFilter = TRUE;
	} else {
		m_cButtonFilterSet.EnableWindow(FALSE);

		m_bFilter = FALSE;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonFilterSet
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
void CFftSpectrogramDlg::OnBnClickedButtonFilterSet()
{
	// �t�B���^�ݒ�
	if (SetFilter(TRUE)) {
		// �Čv�Z���\��
		CalcAndDispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnCbnSelchangeComboFilterBand
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
void CFftSpectrogramDlg::OnCbnSelchangeComboFilterBand()
{
	EDFBand eFilterBand = (EDFBand)m_cComboFilterBand.GetItemData((int)m_cComboFilterBand.GetCurSel());

	// �t�B���^�̊e�R���g���[���̗L���^������ݒ肷��
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
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonCsv
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSV�o�͏���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonCsv()
{
	long period = (long)(m_fSelectEnd - m_fSelectBegin);

	CGraphDigitalDataRowCol dlg(this);
	dlg.SetDataInfo(m_pSpectrogramG
		, m_nTimeDataNum
		, m_nSpectrumDataSize
		, "�X�y�N�g���O�������l�f�[�^�\��"
		, m_StartTime
		, m_fTimeStep * 1000
		, m_fSampleRate / m_nFftSize
		, period);
	dlg.DoModal();

	return;
}


//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonTimeSet
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CSV�o�͏���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeSet()
{
	double	sv_Begin, sv_End;
	// ���Ԕ͈͐ݒ�_�C�A���O�\��
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
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonInitialize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�������{�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonInitialize()
{
	if (MessageBox("���̃E�B���h�E�̕\����������Ԃɖ߂��܂����H", NULL, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// �����͈̔͂�߂�
	m_fSelectBegin = m_fDefaultSelectBegin;
	m_fSelectEnd = m_fDefaultSelectEnd;

	// �e�R���g���[��������
	InitializeControls();

	// �t�B���^�ݒ菉����
	m_cComboFilterBand.SetCurSel(0);
	OnCbnSelchangeComboFilterBand();
	SetFilter(FALSE);

	// �Čv�Z���\��
	CalcAndDispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E���b�Z�[�W����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		MSG*	pMsg			[I] MSG�\���̂ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	�e�N���X�̕Ԓl�����̂܂ܕԂ�
//*****************************************************************************************************
BOOL CFftSpectrogramDlg::PreTranslateMessage(MSG* pMsg)
{
	// �c�[���`�b�v��\�����邽�߂̏���
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
//  1. �֐���
//		CFftSpectrogramDlg::OnEnChangeEditTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Y�[���{���ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnEnChangeEditTimeZoom()
{
	// �Y�[���l�ݒ�
	m_fTimeZoom = m_cEditTimeZoom;
	if (m_fTimeZoom < 1.0)
		m_fTimeZoom = 1.0;

	// �O���t�\��
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonTimeZoomAll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԏ��́u�S�̕\���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeZoomAll()
{
	// 
	m_fTimeOffset = 0;
	m_fTimeZoom = 1.0;

	// �O���t�\��
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonTimeZoomIn
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԏ��́u�g��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeZoomIn()
{
	m_fTimeZoom *= 2;

	// �O���t�\��
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedButtonTimeZoomOut
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԏ��́u�k���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::OnBnClickedButtonTimeZoomOut()
{
	m_fTimeZoom /= 2;
	if (m_fTimeZoom < 1.0)
		m_fTimeZoom = 1.0;

	// �O���t�\��
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::TimeScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԏ��X�N���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double	fPos			[I] �X�N���[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::TimeScroll(double fPos)
{
	m_fTimeOffset = (m_fTimeEnd - m_fSelectBegin) * fPos;

	// �O���t�\��
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::SetTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԏ��Y�[���ݒ菈��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::SetTimeZoom()
{
	// �I�t�Z�b�g�␳
	double fTimeEnd = m_fSelectBegin + m_fTimeOffset + (m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom;
	if (fTimeEnd > m_fTimeEnd)
		m_fTimeOffset = m_fTimeEnd - (m_fTimeEnd - m_fSelectBegin) / m_fTimeZoom - m_fSelectBegin;

	// �Y�[���\��
	m_cEditTimeZoom = m_fTimeZoom;

	// �X�N���[���o�[�\��
	m_cStaticFftGraph.SetTimeScrollBar(m_fTimeOffset / (m_fTimeEnd - m_fSelectBegin), m_fTimeZoom);

	// �{�^���L���ݒ�
	m_cButtonTimeZoomAll.EnableWindow(m_fTimeZoom != 1);
	m_cButtonTimeZoomOut.EnableWindow(m_fTimeZoom != 1);

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnBnClickedCheckScrollStart
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
void CFftSpectrogramDlg::OnBnClickedCheckScrollStart()
{
	if (!m_cCheckScrollStart) {
		double fScrollFreq = m_cEditScrollFreq;
		if (fScrollFreq == 0) {
			CGeneral::Alert("���g����ݒ肵�Ă��������B");
			m_cEditScrollFreq.SetFocus();
			return;
		}

		// �{�^���̕\�����u��~�v�ɐݒ�
		m_cCheckScrollStart = "��~";
		m_cCheckScrollStart.SetCheck(1);

		// �����X�N���[��ON
		m_cStaticFftGraph.m_bAutoScroll = TRUE;

		// �J�[�\���ʒu���Z�b�g
		m_fCursorTime = m_fSelectBegin;
		m_fTimeOffset = 0;

		// ��tick�擾
		m_dwAutoScrollTick = ::GetTickCount();

		// �^�C�}�[�ݒ�
		SetTimer(IDT_AUTO_SCROLL, TIMER_AUTO_SCROLL, NULL);
	} else {
		// �{�^���̕\�����u�J�n�v�ɐݒ�
		m_cCheckScrollStart = "�J�n";
		m_cCheckScrollStart.SetCheck(0);

		// �����X�N���[��OFF
		m_cStaticFftGraph.m_bAutoScroll = FALSE;

		// �^�C�}�[����
		KillTimer(IDT_AUTO_SCROLL);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::OnTimer
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
void CFftSpectrogramDlg::OnTimer(UINT_PTR nIDEvent)
{
	switch (nIDEvent) {
	case IDT_AUTO_SCROLL:
		// �����X�N���[��
		AutoScroll();
		break;
	}

	CFftBaseDlg::OnTimer(nIDEvent);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::AutoScroll
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
void CFftSpectrogramDlg::AutoScroll()
{
	// �X�N���[�����x�擾
	double fScrollSpeed = m_cEditScrollSpeed;

	// ���ԃX�e�b�v�v�Z
	DWORD dwCurrentTick = ::GetTickCount();
	double fTimeStep = fScrollSpeed * (dwCurrentTick - m_dwAutoScrollTick) / 1000;
	m_dwAutoScrollTick = dwCurrentTick;

	// �\���J�n�ʒu�Ǝ��ԃJ�[�\���ʒu���X�V
	m_fTimeOffset += fTimeStep;
	m_fCursorTime += fTimeStep;
	m_fCursorFreq = m_cEditScrollFreq;

	// �\���͈̓`�F�b�N
	double fTimeSpan = m_fTimeEnd - m_fSelectBegin;
	if (m_fSelectBegin + m_fTimeOffset + fTimeSpan / m_fTimeZoom > m_fTimeEnd)
		m_fTimeOffset = fTimeSpan - fTimeSpan / m_fTimeZoom;

	// �f�[�^�̍ő厞�ԂɒB�������~����
	if (m_fCursorTime >= m_fSelectEnd) {
		m_fCursorTime = 0;
		m_fCursorFreq = 0;
		OnBnClickedCheckScrollStart();
	}

	// �J�[�\���\��
	DispCursor();

	// �O���t�\�����X�V
	SetTimeZoom();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::InitRealtime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����A���^�C���\������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::InitRealtime()
{
	// FFT�T�C�Y��ݒ�
	m_nFftSize = (int)pow(2.0, ceil(log(m_pGraphData->m_fSampleRate) / log(2.0)));
	m_nSpectrumDataSize = m_nFftSize / 2;
	m_fSampleRate = m_pGraphData->m_fSampleRate;

	// ���Ԃ̃p�����[�^��ݒ�
	m_nTimeDataNum = gRealtimeRange;
	m_fTimeStep = 1.0;
	m_fTimeEnd = m_fSelectBegin + m_nTimeDataNum * m_fTimeStep;

	// �o�b�t�@�m��
	AllocBuf();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftSpectrogramDlg::CalcRealtime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����A���^�C���\���v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftSpectrogramDlg::CalcRealtime()
{
	// PSD�o�b�t�@�m��
	double *pSpectrumX = new double[m_nSpectrumDataSize];
	double *pSpectrumY = new double[m_nSpectrumDataSize];
	double *pSpectrumZ = new double[m_nSpectrumDataSize];

	while (m_fCalcTime < m_pGraphData->m_fTimeDispEnd) {
		// �v�Z�ς݃f�[�^���ړ��i�X�N���[���j
		int nSpectrogramPos = min((int)m_fCalcTime, m_nTimeDataNum);
		int nSpectrograOffset = nSpectrogramPos * m_nSpectrumDataSize;
		if (nSpectrogramPos == m_nTimeDataNum) {
			memmove(m_pSpectrogramG, m_pSpectrogramG + m_nSpectrumDataSize, m_nSpectrumDataSize * (m_nTimeDataNum - 1) * sizeof(double));
			nSpectrograOffset -= m_nSpectrumDataSize;
			if (m_fCursorTime != 0)
				m_fCursorTime += 1.0;
		}

		// PSD�v�Z
		int nDataOffset = (int)(m_fCalcTime * m_fSampleRate);
		CalcSpectrum(m_pGraphData->m_pCurrentDataX + nDataOffset, pSpectrumX);
		CalcSpectrum(m_pGraphData->m_pCurrentDataY + nDataOffset, pSpectrumY);
		CalcSpectrum(m_pGraphData->m_pCurrentDataZ + nDataOffset, pSpectrumZ);

		// ���͌v�Z
		double *pSpectrogram = m_pSpectrogramG + nSpectrograOffset;
		for (int i = 0; i < m_nSpectrumDataSize; i++)
			*pSpectrogram++ = pSpectrumX[i] + pSpectrumY[i] + pSpectrumZ[i];

		m_fCalcTime += 1;
	}

	// PSD�o�b�t�@���
	delete [] pSpectrumX;
	delete [] pSpectrumY;
	delete [] pSpectrumZ;

	// �v�Z�ς݃t���O�Z�b�g
	m_bCalculation = TRUE;
}
