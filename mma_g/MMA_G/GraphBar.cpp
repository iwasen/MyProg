//*****************************************************************************************************
//  1. �t�@�C����
//		GraphBar.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�E�B���h�E�̃_�C�A���O�o�[�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "GraphBar.h"
#include "GraphFrm.h"
#include "DataFile.h"
#include "General.h"
#include "Globaldata.h"
#include "Define.h"


// CGraphBar �_�C�A���O

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

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_TIME_ZOOM_ALL, "�����̔{�����P�ɐݒ肵�A�S�̂�\�����܂��B"},
	{IDC_BUTTON_TIME_ZOOM_IN, "�����̔{�����Q�{�Ɋg�債�܂��B"},
	{IDC_BUTTON_TIME_ZOOM_OUT, "�����̔{����1/2�{�ɏk�����܂��B"},
	{IDC_BUTTON_LEVEL_ZOOM_ALL, "�c���̔{�����P�ɐݒ肵�A�S�̂�\�����܂��B"},
	{IDC_BUTTON_LEVEL_ZOOM_IN, "�c���̔{�����Q�{�Ɋg�債�܂��B"},
	{IDC_BUTTON_LEVEL_ZOOM_OUT, "�c���̔{����1/2�{�ɏk�����܂��B"},
	{IDC_BUTTON_SELECT_ALL, "�S�Ă̎��Ԕ͈͂���͔͈͂Ƃ��đI�����܂��B"},
	{IDC_BUTTON_SELECT_MANUAL, "��͔͈͂��蓮�Ŏw�肵�܂��B"},
	{IDC_BUTTON_FFT_SPECTRUM, "�I��������͔͈͂̃f�[�^��ΏۂƂ����X�y�N�g����̓E�B���h�E���J���܂��B"},
	{IDC_BUTTON_FFT_OCTAVE, "�I��������͔͈͂̃f�[�^��ΏۂƂ���1/3�I�N�^�[�u�o���h��̓E�B���h�E���J���܂��B"},
	{IDC_BUTTON_FFT_SPECTROGRAM, "�I��������͔͈͂̃f�[�^��ΏۂƂ����X�y�N�g���O������̓E�B���h�E���J���܂��B"},
	{IDC_BUTTON_TIME_CURSOR_TO_LEFT, "���ԃJ�[�\�����P�f�[�^���A���ֈړ����܂��B"},
	{IDC_BUTTON_TIME_CURSOR_TO_RIGHT, "���ԃJ�[�\�����P�f�[�^���A�E�ֈړ����܂��B"},
	{IDC_CHECK_SCROLL_START, "���ԃJ�[�\���������I�ɉE�ֈړ����܂��B���쒆�ɃN���b�N����ƒ�~���܂��B"},
	{IDC_BUTTON_FILTER_SET, "�ݒ肵���t�B���^���f�[�^�ɓK�p���܂��B"},
	{IDC_BUTTON_INITIALIZE, "���̃E�B���h�E�̕\�����e��������Ԃɖ߂��܂��B"},
	{IDC_EDIT_SPECTROGRAM_POINT_COUNT, "�X�y�N�g���O�����̎��Ԏ��̕���\��ݒ肵�܂��B\n100�Ɛݒ肵���ꍇ�A�I�����ꂽ��͔͈͎��Ԃ�100�������ăX�y�N�g����͂��܂��B"},
	{IDC_CHECK_ZERO_HOSEI, "�����x�f�[�^���璼�����������������ĉ�͂��s���܂��B"},
	{IDC_COMBO_SAMPLE_RATE, "�T���v�����O���[�g��ݒ肵���l�ɉ����ăO���t�\�����s���܂��B"},
	{IDC_COMBO_FILTER_BAND, "�t�B���^�̎�ނ��A�����i�S��ʉ߁j�ALPF�i���ʉ߁j�AHPF�i����ʉ߁j�ABPF�i�ш�ʉ߁j�ABSF�i�ш�j�~�j����I�����܂��B"},
	{IDC_COMBO_FILTER_ORDER, "�t�B���^�̎�����ݒ肵�܂��B�������傫���قǋ}�s�ȃt�B���^�ƂȂ�܂��B"},
	{IDC_COMBO_FILTER_SHAPE, "�t�B���^�̓������AButterworth�i�o�^�[���[�X�����j�AChebyshev�i�`�F�r�V�F�t�����j�ABessel�i�x�b�Z�������j����I�����܂��B"},
	{IDC_CHECK_ZERO_OFFSET_GRAPH, "0�_�␳�l�O���t��\�����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		WPARAM		wParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//		LPARAM		lParam		[I] �E�B���h�E���b�Z�[�W�p�����[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		LRESULT		TRUE
//*****************************************************************************************************
LRESULT CGraphBar::OnInitDialog(WPARAM wParam, LPARAM lParam)
{
	LRESULT bRet = HandleInitDialog(wParam, lParam);

	UpdateData(FALSE);

	CGraphFrame *pParent = (CGraphFrame *)GetParent();

	// �T���v�����O���g���̃R���{�{�b�N�X��ݒ�
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
	// �Z���T/�v�����G���A��\��
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

	// �t�B���^�̃R���{�{�b�N�X��ݒ�
	CGeneral::SetFilterBandList(m_cComboFilterBand, 0);
	CGeneral::SetFilterOrderList(m_cComboFilterOrder, 3);
	CGeneral::SetFilterTypeList(m_cComboFilterShape, 0);

	// �G�f�B�b�g�R���g���[���̗L�����͕�����ݒ�
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

	// �}��̐��̐F��ݒ�
	m_cStaticLineD.SetBackColor(COLOR_GRAPH_DATA_D);
	m_cStaticLineX.SetBackColor(COLOR_GRAPH_DATA_X);
	m_cStaticLineY.SetBackColor(COLOR_GRAPH_DATA_Y);
	m_cStaticLineZ.SetBackColor(COLOR_GRAPH_DATA_Z);

	// �e�R���g���[����������
	InitializeControls();

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return bRet;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::InitializeControls
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
void CGraphBar::InitializeControls()
{
	// �X�N���[���X�s�[�h��1�ɐݒ�
	m_cEditScrollSpeed = 1;

	switch (m_nGraphKind) {
	case GRAPH_ACCEL:
		// 0�_�␳��ݒ�
		m_cCheckZeroHosei = gZeroOffsetSw;

		// �g��k���{�^���̃`�F�b�N�{�b�N�X��ݒ�
		m_cCheckZoomX = TRUE;
		m_cCheckZoomY = TRUE;
		m_cCheckZoomZ = TRUE;

// 077FD050-GYM961-0_005 2007/9/8 appended by yG ->
		// �X�y�N�g���O������͂̉�͕�/���炵���̎Z�o�p
		// �|�C���g���Ǝ��ԕ��̃f�t�H���g�ݒ�
		m_cEditSpectrogramPointCount = 100;
		m_cEditSpectrogramWidth = 100;
// 077FD050-GYM961-0_005 2007/9/8 appended by yG <-

		// 0�_�␳�l�O���t�{�^��
		m_cCheckZeroOffsetGraph = FALSE;
		break;
	case GRAPH_TEMP:
		// �\���f�[�^�`�F�b�N�{�b�N�X���Z�b�g
		m_cCheckDispD = TRUE;
		m_cCheckDispX = TRUE;
		m_cCheckDispY = TRUE;
		m_cCheckDispZ = TRUE;
		break;
	case GRAPH_ZERO_OFFSET:
		// 0�_�␳��ݒ�
		m_cCheckZeroHosei.EnableWindow(FALSE);

		// �g��k���{�^���̃`�F�b�N�{�b�N�X��ݒ�
		m_cCheckZoomX = TRUE;
		m_cCheckZoomY = TRUE;
		m_cCheckZoomZ = TRUE;

		m_cEditSpectrogramPointCount.EnableWindow(FALSE);
		m_cEditSpectrogramWidth.EnableWindow(FALSE);

		// 0�_�␳�l�O���t�{�^��
		m_cCheckZeroOffsetGraph.ShowWindow(SW_HIDE);
		break;
	}

	// �f�[�^�Ԉ����\���ݒ�
	m_cComboSampleRate.SetCurSel(0);

	// �t�B���^�ݒ菉����
	m_cComboFilterBand.SetCurSel(0);

}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::DispTimeData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԃ̕\���͈͂�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		sTimeBegin		[I] �\���͈͂̊J�n����
//		CString		sTimeEnd		[I] �\���͈͂̏I������
//		double		fTimeZoom		[I] �����Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::DispTimeData(CString sTimeBegin, CString sTimeEnd, double fTimeZoom)
{
	m_cEditTimeBegin = sTimeBegin;
	m_cEditTimeEnd = sTimeEnd;

	if (m_cEditTimeZoom.GetSafeHwnd() != GetFocus()->GetSafeHwnd())
		m_cEditTimeZoom.Format("%g", fTimeZoom);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::DispLevelDataX
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�w�������x�̕\���͈͂�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fLevelBegin		[I] �\���͈͂̃��x���i��G�j
//		double		fLevelEnd		[I] �\���͈͂̃��x���i��G�j
//		double		fLevelZoom		[I] �c���Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphBar::DispLevelDataY
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�x�������x�̕\���͈͂�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fLevelBegin		[I] �\���͈͂̃��x���i��G�j
//		double		fLevelEnd		[I] �\���͈͂̃��x���i��G�j
//		double		fLevelZoom		[I] �c���Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphBar::DispLevelDataZ
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�y�������x�̕\���͈͂�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fLevelBegin		[I] �\���͈͂̃��x���i��G�j
//		double		fLevelEnd		[I] �\���͈͂̃��x���i��G�j
//		double		fLevelZoom		[I] �c���Y�[���{��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphBar::DispTimeCursor
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���ԃJ�[�\���̈ʒu��\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		TimeCursor		[I] �J�[�\���ʒu�̎���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::DispTimeCursor(CString TimeCursor)
{
	m_cEditTimeCursor = TimeCursor;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::DispRsuData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���ʒu��RSU�f�[�^��\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fRsuX		[I] �J�[�\���ʒu��X���̒l
//		double		fRsuY		[I] �J�[�\���ʒu��Y���̒l
//		double		fRsuZ		[I] �J�[�\���ʒu��Z���̒l
//		double		fRsuD		[I] �J�[�\���ʒu��DACM�̒l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphBar::ClearRsuData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�J�[�\���ʒu��RSU�f�[�^������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphBar::DispSelectArea
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�I���G���A�̎��Ԃ�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fSelectBegin		[I] �I���G���A�̊J�n����
//		double		fSelectEnd			[I] �I���G���A�̏I������
//		CString		strBegin			[I] �I���G���A�̊J�n����
//		CString		strEnd				[I] �I���G���A�̏I������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
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
//  1. �֐���
//		CGraphBar::DispZeroPointValue
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		0�_�␳�l��\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fAverageX			[I] X��0�_�␳�l
//		double		fAverageY			[I] Y��0�_�␳�l
//		double		fAverageZ			[I] Z��0�_�␳�l
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::DispZeroPointValue(double fAverageX, double fAverageY, double fAverageZ)
{
	// 0�_�␳�l��\��
	m_cEditZeroX.Format("%.3f", fAverageX);
	m_cEditZeroY.Format("%.3f", fAverageY);
	m_cEditZeroZ.Format("%.3f", fAverageZ);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::OnRetKey
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
LRESULT CGraphBar::OnRetKey(WPARAM wParam, LPARAM lParam)
{
	GetParent()->SendMessage(WM_KEY_RET_KEY, wParam, lParam);
	return 0;
}


//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::PreTranslateMessage
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
BOOL CGraphBar::PreTranslateMessage(MSG* pMsg)
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

	return CDialogBar::PreTranslateMessage(pMsg);
}
