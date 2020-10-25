//*****************************************************************************************************
//  1. �t�@�C����
//		FftOctaveDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		1/3�I�N�^�[�u��̓O���t�̃_�C�A���O�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		�����ɕ����̃O���t��\���ł���悤�ɂ��邽�߁A���[�h���X�_�C�A���O�ŊJ���B
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
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

// �X�P�[���̕\���͈�
#define MIN_FREQ	1e-4		// ���g���̉����̃f�t�H���g�l
#define MIN_LEVEL	1e-10		// �����x�̉����̃f�t�H���g�l
#define MAX_LEVEL	1e-2		// �����x�̏���̃f�t�H���g�l
#define MAX_LEVEL_MME	1e-1	// �����x�̏���̃f�t�H���g�l�iMME�̏ꍇ�j

// CFftOctaveDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CFftOctaveDlg, CFftBaseDlg)

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::CFftOctaveDlg
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
CFftOctaveDlg::CFftOctaveDlg(CWnd *pParent)
	: CFftBaseDlg(CFftOctaveDlg::IDD, pParent)
	, m_sTitle(_T(""))
{
	// �����o�ϐ���������
	m_pOctaveX = NULL;
	m_pOctaveY = NULL;
	m_pOctaveZ = NULL;
	m_pOctaveG = NULL;
	m_nDataSize = 0;
	m_nFftSize = 0;
	m_nSpectrumDataSize = 0;
	m_bCalculation = FALSE;
	m_bFilter = FALSE;

	// 1/3�I�N�^�[�u�̎��g�����v�Z�i0.0001Hz����o���h�̋��E�ƒ��S���g���j
	for (int i = 0; i <= OCTAVE_BAND_NUM * 2; i++)
		m_fOctaveBandFreq[i] = pow(10.0, i / 20.0 - 4.05);
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::~CFftOctaveDlg
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
CFftOctaveDlg::~CFftOctaveDlg()
{
	// �o�b�t�@�����
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

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_FILTER_SET, "�ݒ肵���t�B���^���f�[�^�ɓK�p���܂��B"},
	{IDC_BUTTON_CSV, "1/3�I�N�^�[�u�o���h���l�f�[�^�\���_�C�A���O���J���܂��B"},
	{IDC_BUTTON_INITIALIZE, "���̃E�B���h�E�̕\�����e��������Ԃɖ߂��܂��B"},
	{IDC_BUTTON_PRINT, "1/3�I�N�^�[�u�o���h��̓O���t��������܂��B"},
	{IDC_BUTTON_CLOSE, "���̃E�B���h�E����܂��B"},
	{IDC_COMBO_FILTER_BAND, "�t�B���^�̎�ނ��A�����i�S��ʉ߁j�ALPF�i���ʉ߁j�AHPF�i����ʉ߁j�ABPF�i�ш�ʉ߁j�ABSF�i�ш�j�~�j����I�����܂��B"},
	{IDC_COMBO_FILTER_ORDER, "�t�B���^�̎�����ݒ肵�܂��B�������傫���قǋ}�s�ȃt�B���^�ƂȂ�܂��B"},
	{IDC_COMBO_FILTER_SHAPE, "�t�B���^�̓������AButterworth�i�o�^�[���[�X�����j�AChebyshev�i�`�F�r�V�F�t�����j�ABessel�i�x�b�Z�������j����I�����܂��B"},
	{IDC_COMBO_WINDOW_FUNCTION, "FFT�v�Z���ɓK�p���鑋�֐���I�����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::CreateGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�E�B���h�E�\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CGraphData		*pGraphData		[I] �O���t�f�[�^�N���X�ւ̃|�C���^
//		CGraphBar		*pGraphBar		[I] �O���t�o�[�ւ̃|�C���^
//		HWND			hWndParent		[I] �e�E�B���h�E�n���h��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::CreateGraph(CGraphData *pGraphData, CGraphBar *pGraphBar, HWND hWndParent)
{
	// �O���t�f�[�^�ւ̃|�C���^��ۑ�
	m_pGraphData = pGraphData;
	m_pGraphBar = pGraphBar;
	m_hWndParent = hWndParent;

// 2009/06/11 Version 3.01 appended by y.G ->
	// �I��͈͂��Z�[�u
	m_fSelectBegin = m_pGraphData->m_fSelectBegin;
	m_fSelectEnd = m_pGraphData->m_fSelectEnd;
// <- 2009/06/11 Version 3.01 appended by y.G

	if (!IsEnableCalc()) {
		delete this;
		return;
	}

	// ���[�h���X�Ń_�C�A���O��\��
	DoModeless();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnInitDialog
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
BOOL CFftOctaveDlg::OnInitDialog()
{
	CFftBaseDlg::OnInitDialog();

	SetIcon(AfxGetApp()->LoadIcon(IDI_OCTAVE), TRUE);

	// �X�e�[�^�X�o�[�쐬
	CreateStatusBar();

	// ���֐��v���_�E���ݒ�
	m_cFft.SetWindowFuncList(m_cComboWindowFunction, m_nWindowFunc);

	// �R���g���[���̈ʒu�𒲐�
	SetAllCtlPosition(0, 1, 0, 1);
	SetCtlPosition(IDC_STATIC_FFT_GRAPH, 0, 0, 1, 1);
	SetCtlPosition(IDC_BUTTON_PRINT, 1, 1, 1, 1);
	SetCtlPosition(IDC_BUTTON_CLOSE, 1, 1, 1, 1);

// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
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

	// �O���t�̐��̐F��ݒ�
	m_cStaticLineX.SetBackColor(COLOR_GRAPH_DATA_X);
	m_cStaticLineY.SetBackColor(COLOR_GRAPH_DATA_Y);
	m_cStaticLineZ.SetBackColor(COLOR_GRAPH_DATA_Z);
	m_cStaticLineG.SetBackColor(COLOR_GRAPH_DATA_G);

	// �t�B���^�̐ݒ�
	CGeneral::SetFilterBandList(m_cComboFilterBand, m_pGraphBar->m_cComboFilterBand.GetCurSel());
	CGeneral::SetFilterOrderList(m_cComboFilterOrder, m_pGraphBar->m_cComboFilterOrder.GetCurSel());
	CGeneral::SetFilterTypeList(m_cComboFilterShape, m_pGraphBar->m_cComboFilterShape.GetCurSel());
	m_cEditFilterFreq1 = (CString)m_pGraphBar->m_cEditFilterFreq1;
	m_cEditFilterFreq2 = (CString)m_pGraphBar->m_cEditFilterFreq2;
	OnCbnSelchangeComboFilterBand();
	SetFilter(FALSE);

	// ���~�b�g���C���쐬
	MakeLimitLine();

	// MME�̃��~�b�g���C��
	if (m_pGraphData->m_nDataType == DATA_TYPE_MME) {
		m_cCheckLimitLine1.SetWindowText("JEM���I����EFU/PIU��t�ʂɂ���������d�͊�");
		m_cCheckLimitLine2.ShowWindow(SW_HIDE);
	}

	// �e�R���g���[��������
	InitializeControls();

	// �v�Z�Ɏ��Ԃ�������̂ł����ŃE�B���h�E�\��
	ShowWindow(SW_SHOW);

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
//		CFftOctaveDlg::InitializeControls
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
void CFftOctaveDlg::InitializeControls()
{
	// ���~�b�g���C���̏����ݒ�
	m_cCheckLimitLine1 = TRUE;
	m_cCheckLimitLine2 = FALSE;

	// �\���f�[�^�`�F�b�N�{�b�N�X���Z�b�g
	m_cCheckDispX = TRUE;
	m_cCheckDispY = TRUE;
	m_cCheckDispZ = TRUE;
	m_cCheckDispG = TRUE;

	// �X�P�[���͈͂̏����l���Z�b�g
	m_fScaleFreqMin = 0;
	m_fScaleFreqMax = m_pGraphData->m_fSampleRate / 2;
	m_bScaleFreqLog = TRUE;
	m_fScaleLevelMin = 0;
	m_fScaleLevelMax = m_pGraphData->m_nDataType == DATA_TYPE_MME ? MAX_LEVEL_MME : MAX_LEVEL;
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
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedButtonClose
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
void CFftOctaveDlg::OnBnClickedButtonClose()
{
	// �E�B���h�E�����
	OnCancel();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::CalcAndDispGraph
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
void CFftOctaveDlg::CalcAndDispGraph()
{
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
		, (int)(m_pGraphData->m_fSelectBegin * 1000));
	to.SetTime(m_pGraphData->m_dStarttime.m_YY
		, m_pGraphData->m_dStarttime.m_MM
		, m_pGraphData->m_dStarttime.m_DD
		, (int)((m_pGraphData->m_fSelectEnd+0.001) * 1000));
// <- 2009/07/03 Version 3.02 modified by y.G
	m_cEditDispBegin = from.GetStr();
	m_cEditDispEnd = to.GetStr();

	// �X�e�[�^�X�o�[��"�v�Z��"��\��
	SetStatusBar("�v�Z��");

	// �O���t�E�B���h�E���\��
	m_cStaticFftGraph.HideGraph();
	UpdateWindow();

	// �f�[�^�v�Z����
	CalcData();

	// �O���t�\��
	DispGraph();

	// �X�e�[�^�X�o�[��"���f�B"��\��
	SetStatusBar("���f�B");
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::IsEnableCalc
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
bool CFftOctaveDlg::IsEnableCalc()
{
	// �I��͈͂̃f�[�^�̃C���f�b�N�X���擾
	int nBeginIndex = (int)((m_pGraphData->m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_pGraphData->m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	if (nEndIndex > m_pGraphData->m_nDataSize)
		nEndIndex = m_pGraphData->m_nDataSize;

	// �o�C�i���f�[�^�T�C�Y�AFFT�T�C�Y�A�X�y�N�g���f�[�^�T�C�Y��ݒ�
	int nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;
	int	nFftSize = (int)pow(2.0, ceil(log((double)nDataSize) / log(2.0)));

	// �I�������͈͂̃f�[�^�T�C�Y���`�F�b�N
	if (nFftSize < 8) {
		CGeneral::Alert("�I�������͈͂����������邽�߁A�O���t��\���ł��܂���B");
		return false;
	}
	
	return true;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::CalcData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::CalcData()
{
	// �I��͈͂̃f�[�^�̃C���f�b�N�X���擾
	int nBeginIndex = (int)((m_pGraphData->m_fSelectBegin - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	int nEndIndex = (int)((m_pGraphData->m_fSelectEnd - m_pGraphData->m_stTimeRange.fMin) / m_pGraphData->m_stTimeRange.fSpan * m_pGraphData->m_nDataSize);
	if (nEndIndex > m_pGraphData->m_nDataSize)
		nEndIndex = m_pGraphData->m_nDataSize;

	// �o�C�i���f�[�^�T�C�Y�AFFT�T�C�Y�A�X�y�N�g���f�[�^�T�C�Y��ݒ�
	m_nDataSize = (nEndIndex - nBeginIndex) / m_pGraphData->m_nDownRate;
	m_nFftSize = (int)pow(2.0, ceil(log((double)m_nDataSize) / log(2.0)));
	m_nSpectrumDataSize = m_nFftSize / 2;
	m_fSampleRate = m_pGraphData->m_fSampleRate / m_pGraphData->m_nDownRate;

	// �o�b�t�@�m��
	AllocBuf();

	// PSD�o�b�t�@�m��
	double *pSpectrumX = new double[m_nSpectrumDataSize];
	double *pSpectrumY = new double[m_nSpectrumDataSize];
	double *pSpectrumZ = new double[m_nSpectrumDataSize];
	double *pSpectrumG = new double[m_nSpectrumDataSize];

	// PSD�v�Z
	CalcSpectrum(m_pGraphData->m_pCurrentDataX + nBeginIndex, pSpectrumX);
	CalcSpectrum(m_pGraphData->m_pCurrentDataY + nBeginIndex, pSpectrumY);
	CalcSpectrum(m_pGraphData->m_pCurrentDataZ + nBeginIndex, pSpectrumZ);

	// ���͌v�Z
	for (int i = 0; i < m_nSpectrumDataSize; i++)
		pSpectrumG[i] = pSpectrumX[i] + pSpectrumY[i] + pSpectrumZ[i];

	// 1/3�I�N�^�[�u�o���h�v�Z
	CalcOctaveBand(pSpectrumX, m_pOctaveX);
	CalcOctaveBand(pSpectrumY, m_pOctaveY);
	CalcOctaveBand(pSpectrumZ, m_pOctaveZ);
	CalcOctaveBand(pSpectrumG, m_pOctaveG);

	// PSD�o�b�t�@���
	delete [] pSpectrumX;
	delete [] pSpectrumY;
	delete [] pSpectrumZ;
	delete [] pSpectrumG;

	// �v�Z�ς݃t���O�Z�b�g
	m_bCalculation = TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::AllocBuf
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
void CFftOctaveDlg::AllocBuf()
{
	// �o�b�t�@���
	FreeBuf();

	// �I�N�^�[�u�o���h�o�b�t�@�m��
	m_pOctaveX = new double[OCTAVE_BAND_NUM];
	m_pOctaveY = new double[OCTAVE_BAND_NUM];
	m_pOctaveZ = new double[OCTAVE_BAND_NUM];
	m_pOctaveG = new double[OCTAVE_BAND_NUM];
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::FreeBuf
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
//  1. �֐���
//		CFftOctaveDlg::CalcSpectrum
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		PSD�v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		*pBinaryData		[I] �o�C�i���f�[�^�ւ̃|�C���^
//		double		*pSpectrum			[O] PSD�o�b�t�@�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::CalcSpectrum(const double *pBinaryData, double *pSpectrum)
{
	int i;
	double fAve;

	// FFT�o�b�t�@�m��
	double *pFftBuf = new double[m_nFftSize];

	// ���ϒl�擾
	if (m_pGraphBar->m_cCheckZeroHosei) {
		double fSum = 0;
		for (i = 0; i < m_nDataSize; i++)
			fSum += pBinaryData[i * m_pGraphData->m_nDownRate];
		fAve = fSum / m_nDataSize;
	} else
		fAve = 0;

	// FFT�o�b�t�@�Ƀf�[�^�R�s�[
	for (i = 0; i < m_nDataSize; i++)
		pFftBuf[i] = (pBinaryData[i * m_pGraphData->m_nDownRate] - fAve) * 1e-6;

	// FFT�o�b�t�@�̎c����N���A
	memset(pFftBuf + m_nDataSize, 0, (m_nFftSize - m_nDataSize) * sizeof(double));

	// �t�B���^����
	if (m_bFilter)
		m_cFilter.ExecIIR(pFftBuf, pFftBuf, m_nDataSize);

	// ���֐�
	m_cFft.WindowFunc(m_nWindowFunc, m_nDataSize, pFftBuf);

	// FFT���s
	m_cFft.FFT(m_nFftSize, pFftBuf);

	// �p���[�X�y�N�g�����x(PSD)�v�Z
	double fPsd = 2 / (m_nDataSize * m_fSampleRate);
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
//		CFftOctaveDlg::CalcOctaveBand
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		1/3�I�N�^�[�u�o���h�v�Z����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		*pSpectrum			[I] PSD�o�b�t�@�ւ̃|�C���^
//		double		*pOctaveData		[O] 1/3�I�N�^�[�u�o�b�t�@�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::CalcOctaveBand(const double *pSpectrum, double *pOctaveData)
{
	int i;

	// ���g������\
	double fFreqRes = m_fSampleRate / m_nFftSize;

	// 1/3�I�N�^�[�u�v�Z�o�b�t�@�N���A
	memset(pOctaveData, 0, OCTAVE_BAND_NUM * sizeof(double));

	// ���g���ш�ʂɃp���[�X�y�N�g�������Z
	int nIndex = -1;
	for (i = 1; i < m_nSpectrumDataSize; i++) {
		while (i * fFreqRes >= m_fOctaveBandFreq[nIndex * 2 + 2])
			nIndex++;

		if (nIndex >= 0 && nIndex < OCTAVE_BAND_NUM)
			pOctaveData[nIndex] += pSpectrum[i] * fFreqRes;
	}

	// ���������v�Z
	for (i = 0; i < OCTAVE_BAND_NUM; i++)
		pOctaveData[i] = pOctaveData[i] ? sqrt(pOctaveData[i]) : 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::DispGraph
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
void CFftOctaveDlg::DispGraph()
{
	// �O���t�\��
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
//  1. �֐���
//		CFftOctaveDlg::OnCbnSelchangeComboWindowFunction
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
void CFftOctaveDlg::OnCbnSelchangeComboWindowFunction()
{
	// �I���������֐����擾
	m_nWindowFunc = m_cComboWindowFunction.GetCurSel();

	// �Čv�Z���\��
	CalcAndDispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnSize
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
void CFftOctaveDlg::OnSize(UINT nType, int cx, int cy)
{
	CFftBaseDlg::OnSize(nType, cx, cy);

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedButtonPrint
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
void CFftOctaveDlg::OnBnClickedButtonPrint()
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
		docinfo.lpszDocName = "MMA-G 1/3OctaveBand Graph Doc";

		// ����J�n
		dc.StartDoc(&docinfo);

		// �y�[�W����J�n
		dc.StartPage();

		// �O���t���
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

		// �^�C�g�������
		PrintGraphInfo(dc, rectView);

		// �}����
		PrintSample(dc, rectView
				, &cDrawGraph.m_penGraphDataX
				, &cDrawGraph.m_penGraphDataY
				, &cDrawGraph.m_penGraphDataZ
				, &cDrawGraph.m_penGraphDataG);

		// �y�[�W����I��
		dc.EndPage();

		// ����I��
		dc.EndDoc();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::PrintSample
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C�g�����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDC			&dc				[I] �f�o�C�X�R���e�L�X�g
//		CRect		rectView		[I] ����͈�
//		CPen		*x				[I] X���`��p�̃y��
//		CPen		*y				[I] Y���`��p�̃y��
//		CPen		*z				[I] Z���`��p�̃y��
//		CPen		*g				[I] ���͕`��p�̃y��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::PrintSample(CDC &dc, CRect rectView, CPen *x, CPen *y,CPen *z, CPen *g)
{
	CString	sPrTitle;
	int	defCol = 67;
	int	defX = 9;
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

	CPen *org = dc.SelectObject(x);

	if (m_cCheckDispX) {
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "�FX-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	if (m_cCheckDispY) {
		dc.SelectObject(y);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "�FY-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	if (m_cCheckDispZ) {
		dc.SelectObject(z);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "�FZ-Axis";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	if (m_cCheckDispG) {
		dc.SelectObject(g);
		dc.MoveTo(rectView.left + xp, yp);
		dc.LineTo(rectView.left + xp + nl, yp);
		xp += nl + 5;

		sPrTitle = "�FRMS";
		title.PrintDir(xp, defCol, sPrTitle);
		xp += dc.GetOutputTextExtent(sPrTitle).cx + sizeChar.cx * 2;
	}

	// �Z���T�����o��
	sPrTitle.Format("+X�F%s  +Y�F%s  +Z�F%s", (CString)m_pGraphBar->m_cEditDirX, (CString)m_pGraphBar->m_cEditDirY, (CString)m_pGraphBar->m_cEditDirZ);
	title.PrintDir(rectView.Width() - dc.GetOutputTextExtent(sPrTitle).cx, defCol, sPrTitle);

	dc.SelectObject(org);

// update 2008/03/18 by hama�@��

	// �^�C�g���󎚏I��
	title.EndTitle();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::PrintGraphInfo
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

	// �^�C�g���󎚏I��
	title.EndTitle();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::GetMinFreq
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
double CFftOctaveDlg::GetMinFreq()
{
	// �ΐ��\����0Hz�̏ꍇ�͍ŏ��͈͂�Ԃ�
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
//  1. �֐���
//		CFftOctaveDlg::GetMinLevel
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
double CFftOctaveDlg::GetMinLevel()
{
	// �ΐ��\����0���x���̏ꍇ�͍ŏ��͈͂�Ԃ�
	if (m_bScaleLevelLog && m_fScaleLevelMin <= 0)
		return MIN_LEVEL;
	else
		return m_fScaleLevelMin;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::DispCursorData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���ʒu�f�[�^��\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fCursorX		[I] 0�`1�ɐ��K�����ꂽ�}�E�X�J�[�\����X���W
//		double		fCursorY		[I] 0�`1�ɐ��K�����ꂽ�}�E�X�J�[�\����Y���W
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::DispCursorData(double fCursorX, double fCursorY)
{
	if (fCursorX == 0 && fCursorY == 0) {
		// �J�[�\�����X�P�[���͈̔͊O�̎��̓f�[�^������
		m_cEditCursorFreq.Blank();
		m_cEditCursorX.Blank();
		m_cEditCursorY.Blank();
		m_cEditCursorZ.Blank();
		m_cEditCursorG.Blank();

		// �J�[�\��������
		m_cStaticFftGraph.DispCursor(0);
	} else {
		// �J�[�\���̈ʒu������g�����v�Z
		double fFreq;
		double fMin = GetMinFreq();
		double fMax = m_fScaleFreqMax;
		if (m_bScaleFreqLog)
			fFreq = exp(log(fMin) + (log(fMax) - log(fMin)) * fCursorX);
		else
			fFreq = fMin + (fMax - fMin) * fCursorX;

		// ���g������o���h�ʒu���擾
		int i;
		for (i = 0; i < OCTAVE_BAND_NUM; i++) {
			if (fFreq < m_fOctaveBandFreq[i * 2 + 2] && m_pOctaveG[i] != 0)
				break;
		}

		// �J�[�\���ʒu�̃f�[�^��\��
		fFreq = m_fOctaveBandFreq[i * 2 + 1];
		m_cEditCursorFreq = fFreq;
		m_cEditCursorX = CGeneral::FormatExp(m_pOctaveX[i], 3);
		m_cEditCursorY = CGeneral::FormatExp(m_pOctaveY[i], 3);
		m_cEditCursorZ = CGeneral::FormatExp(m_pOctaveZ[i], 3);
		m_cEditCursorG = CGeneral::FormatExp(m_pOctaveG[i], 3);

		// �J�[�\����\��
		double fPosX;
		if (m_bScaleFreqLog)
			fPosX = (log(fFreq) - log(fMin)) / (log(fMax) - log(fMin));
		else
			fPosX = (fFreq - fMin) / (fMax - fMin);
		m_cStaticFftGraph.DispCursor(fPosX);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedCheckLimitLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���~�b�g���C���̃`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::OnBnClickedCheckLimitLine()
{
	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedCheckDisp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���f�[�^�̃`�F�b�N�{�b�N�X�N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::OnBnClickedCheckDisp()
{
	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnRetKey
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
//  1. �֐���
//		CFftOctaveDlg::OnRetKeyEditFreqMin
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̍ŏ��l�G�f�B�b�g�{�b�N�X�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::OnRetKeyEditFreqMin()
{
	// �����̍ŏ��l
	m_fScaleFreqMin = m_cEditFreqMin;

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnRetKeyEditFreqMax
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̍ő�l�G�f�B�b�g�{�b�N�X�ύX����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::OnRetKeyEditFreqMax()
{
	// �����̍ŏ��l
	m_fScaleFreqMax = m_cEditFreqMax;

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedRadioFreqLinear
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̐��`�X�P�[�����W�I�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::OnBnClickedRadioFreqLinear()
{
	if (m_bScaleFreqLog) {
		// �����̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleFreqLog = FALSE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedRadioFreqLog
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
void CFftOctaveDlg::OnBnClickedRadioFreqLog()
{
	if (!m_bScaleFreqLog) {
		// �����̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleFreqLog = TRUE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnRetKeyEditLevelMin
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
void CFftOctaveDlg::OnRetKeyEditLevelMin()
{
	// �c���̍ŏ��l
	m_fScaleLevelMin = m_cEditLevelMin;

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnRetKeyEditLevelMax
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
void CFftOctaveDlg::OnRetKeyEditLevelMax()
{
	// �c���̍ŏ��l
	m_fScaleLevelMax = m_cEditLevelMax;

	// �O���t�\��
	DispGraph();
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedRadioLevelLinear
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
void CFftOctaveDlg::OnBnClickedRadioLevelLinear()
{
	if (m_bScaleLevelLog) {
		// �c���̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleLevelLog = FALSE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedRadioLevelLog
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
void CFftOctaveDlg::OnBnClickedRadioLevelLog()
{
	if (!m_bScaleLevelLog) {
		// �c���̐��`�^�ΐ��X�P�[���ݒ�
		m_bScaleLevelLog = TRUE;

		// �O���t�\��
		DispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::SetFilter
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
//		CFftOctaveDlg::OnBnClickedButtonFilterSet
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
void CFftOctaveDlg::OnBnClickedButtonFilterSet()
{
	// �t�B���^�ݒ�
	if (SetFilter(TRUE)) {
		// �Čv�Z���\��
		CalcAndDispGraph();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnCbnSelchangeComboFilterBand
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
void CFftOctaveDlg::OnCbnSelchangeComboFilterBand()
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
//		CFftOctaveDlg::OnBnClickedButtonCsv
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
void CFftOctaveDlg::OnBnClickedButtonCsv()
{
	// �f�[�^���擾
	int nData = 0;
	for (int i = 0; i < OCTAVE_BAND_NUM; i++) {
		if (m_fOctaveBandFreq[i * 2] < m_fSampleRate / 2)
			nData++;
	}

	// ���l�f�[�^�\��
	CGraphDigitalData	dlg(this);
	dlg.SetDataInfo(1, nData
		, m_pOctaveX
		, m_pOctaveY
		, m_pOctaveZ
		, m_pOctaveG
		, "1/3�I�N�^�[�u�o���h���l�f�[�^�\��"
		, 0
		, 0
		, m_fOctaveBandFreq);
	dlg.DoModal();

	return;
}

//*****************************************************************************************************
//  1. �֐���
//		CFftOctaveDlg::OnBnClickedButtonInitialize
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
void CFftOctaveDlg::OnBnClickedButtonInitialize()
{
	if (MessageBox("���̃E�B���h�E�̕\����������Ԃɖ߂��܂����H", NULL, MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

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
//		CFftOctaveDlg::PreTranslateMessage
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
BOOL CFftOctaveDlg::PreTranslateMessage(MSG* pMsg)
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
//		CFftOctaveDlg::MakeLimitLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���~�b�g���C���쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CFftOctaveDlg::MakeLimitLine()
{
	// ���~�b�g���C�����v�Z
	for (int i = 0; i < OCTAVE_BAND_NUM; i++) {
		double fFreq = pow(10.0, i / 10.0 - 4.00);

		switch (m_pGraphData->m_nDataType) {
		case DATA_TYPE_MMA:
		case DATA_TYPE_ASU:
			// MMA�̃��~�b�g���C��
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
			// MME�̃��~�b�g���C��
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
