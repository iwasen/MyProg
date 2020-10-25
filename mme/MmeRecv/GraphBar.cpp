//*****************************************************************************************************
//  1. �t�@�C����
//		GraphBar.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�E�B���h�E�̃_�C�A���O�o�[�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#include "stdafx.h"
#include "MmeRecv.h"
#include "GraphBar.h"
#include "GraphWnd.h"
#include "GraphFrm.h"

#define ZOOM_MAX	20

// CGraphBar �_�C�A���O

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


// CGraphBar ���b�Z�[�W �n���h��

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

	// �Y�[���X���C�_�����W�ݒ�
	m_cSliderTimeZoom.SetRange(0, ZOOM_MAX, FALSE);
	m_cSliderLevelZoom.SetRange(0, ZOOM_MAX, FALSE);

	// Data Line�̐F��ݒ�
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			m_cStaticGraph[nUnit][nGraph].SetBackColor(CGraphWnd::m_aGraphColor[nUnit][nGraph]);
			m_cStaticGraph[nUnit][nGraph].SetMouseTrack(TRUE);
		}
	}

	return bRet;
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::DispHorizontalAxis
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̏���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTimeBegin		[I] �J�n����
//		double		fTimeEnd		[I] �I������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::DispHorizontalAxis(double fTimeBegin, double fTimeEnd)
{
	// Time Range�̕\��
	m_cEditTimeBegin = CTime((time_t)fTimeBegin).FormatGmt("%Y/%m/%d %H:%M:%S");
	m_cEditTimeEnd = CTime((time_t)fTimeEnd).FormatGmt("%Y/%m/%d %H:%M:%S");
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::DispVerticalAxis
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���̏���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fTempBegin		[I] ���x�͈͂̊J�n
//		double		fTempEnd		[I] ���x�͈͂̏I��
//		double		fHeaterBegin	[I] �q�[�^�d���̊J�n
//		double		fHeaterEnd		[I] �q�[�^�d���̏I��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::DispVerticalAxis(double fTempBegin, double fTempEnd, double fHeaterBegin, double fHeaterEnd)
{
	// Temperature Range�̕\��
	m_cEditTempBegin.Format("%.1f", fTempBegin);
	m_cEditTempEnd.Format("%.1f", fTempEnd);

	// Header Voltage Range�̕\��
	m_cEditHeaterBegin.Format("%.1f", fHeaterBegin);
	m_cEditHeaterEnd.Format("%.1f", fHeaterEnd);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::DispDataLine
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t�f�[�^���C���̏���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		SGraphLine		&oGraphLine		[I] �O���t�f�[�^���C�����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::DispDataLine(const SGraphLine &oGraphLine)
{
	// Data Line�̃`�F�b�N��Ԃ�ݒ�
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++)
			m_cCheckGraph[nUnit][nGraph].SetCheck(oGraphLine.bEnable[nUnit][nGraph]);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::OnBnClickedCheckGraph
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�O���t���C���̃`�F�b�N�{�b�N�X�̃N���b�N�C�x���g�n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::OnBnClickedCheckGraph()
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();
	SGraphLine oGraphLine;

	// Data Line�̃`�F�b�N��Ԃ��擾
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nGraph = 0; nGraph < N_GRAPH; nGraph++) {
			oGraphLine.bEnable[nUnit][nGraph] = m_cCheckGraph[nUnit][nGraph];
		}
	}

	// �t���[���E�B���h�E��Data Line�̃`�F�b�N��Ԃ̕ύX��ʒm
	pGraphFrm->ChangeGraphLine(oGraphLine);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::OnHScroll
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�Y�[���X���C�_�̑���n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nSBCode			[I] �X�N���[���v���R�[�h
//		UINT		nPos			[I] �X�N���[���{�b�N�X�̈ʒu
//		CScrollBar	*pScrollBar		[I] �X�N���[���o�[�R���g���[���ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	// �t���[���E�B���h�E�ɃY�[����ʒm
	if (pScrollBar->m_hWnd == m_cSliderTimeZoom.GetSafeHwnd()) {
		pGraphFrm->ZoomTime((double)m_cSliderTimeZoom.GetPos() / ZOOM_MAX);
	} else if (pScrollBar->m_hWnd == m_cSliderLevelZoom.GetSafeHwnd()) {
		pGraphFrm->ZoomLevel((double)m_cSliderLevelZoom.GetPos() / ZOOM_MAX);
	}

	CDialogBar::OnHScroll(nSBCode, nPos, pScrollBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::SetTimeZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����Y�[���X���C�_�̐ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fZoom			[I] �Y�[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::SetTimeZoom(double fZoom)
{
	m_cSliderTimeZoom.SetPos((int)(fZoom * ZOOM_MAX));
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::SetLevelZoom
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�c���Y�[���X���C�_�̐ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double		fZoom			[I] �Y�[���ʒu
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CGraphBar::SetLevelZoom(double fZoom)
{
	m_cSliderLevelZoom.SetPos((int)(fZoom * ZOOM_MAX));
}

//*****************************************************************************************************
//  1. �֐���
//		CGraphBar::OnMouseIn
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
//  1. �֐���
//		CGraphBar::OnMouseOut
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
LRESULT CGraphBar::OnMouseOut(WPARAM wParam, LPARAM lParam)
{
	CGraphFrm *pGraphFrm = (CGraphFrm *)GetParent();

	pGraphFrm->m_wndGraphView.SetHighlight(-1, -1);
	pGraphFrm->UpdateGraph();

	return 0;
}
