//*****************************************************************************************************
//  1. �t�@�C����
//		TimeRangeDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԕ͈͐ݒ�_�C�A���O�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "TimeRangeDlg.h"
#include "General.h"


// CTimeRangeDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CTimeRangeDlg, CDialog)

//*****************************************************************************************************
//  1. �֐���
//		CTimeRangeDlg::CTimeRangeDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent		[I] �e�E�B���h�E�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CTimeRangeDlg::CTimeRangeDlg(CWnd* pParent)
	: CDialog(CTimeRangeDlg::IDD, pParent)
{
	m_bDspFlag = false;
}

void CTimeRangeDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DATA_BEGIN, m_cEditDataBegin);
	DDX_Control(pDX, IDC_EDIT_DATA_END, m_cEditDataEnd);
	DDX_Control(pDX, IDC_EDIT_S_YY, m_Edit_s_yy);
	DDX_Control(pDX, IDC_EDIT_S_MM, m_Edit_s_mm);
	DDX_Control(pDX, IDC_EDIT_S_DD, m_Edit_s_dd);
	DDX_Control(pDX, IDC_EDIT_S_H, m_Edit_s_h);
	DDX_Control(pDX, IDC_EDIT_S_M, m_Edit_s_m);
	DDX_Control(pDX, IDC_EDIT_S_S, m_Edit_s_s);
	DDX_Control(pDX, IDC_EDIT_E_YY, m_Edit_e_yy);
	DDX_Control(pDX, IDC_EDIT_E_MM, m_Edit_e_mm);
	DDX_Control(pDX, IDC_EDIT_E_DD, m_Edit_e_dd);
	DDX_Control(pDX, IDC_EDIT_E_H, m_Edit_e_h);
	DDX_Control(pDX, IDC_EDIT_E_M, m_Edit_e_m);
	DDX_Control(pDX, IDC_EDIT_E_S, m_Edit_e_s);
}


BEGIN_MESSAGE_MAP(CTimeRangeDlg, CDialog)
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDCANCEL, "���������ɂ��̃_�C�A���O����܂��B"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. �֐���
//		CTimeRangeDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//*****************************************************************************************************
BOOL CTimeRangeDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_dEndtime = m_dStarttime;
	m_dEndtime += (m_nDataSec * 1000);
	// �I���f�[�^���Ԕ͈͂�ݒ�
	m_cEditDataBegin.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_dStarttime.m_YY
		, m_dStarttime.m_MM
		, m_dStarttime.m_DD
		, m_dStarttime.m_hh
		, m_dStarttime.m_mm
		, m_dStarttime.m_ss);
	m_cEditDataEnd.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_dEndtime.m_YY
		, m_dEndtime.m_MM
		, m_dEndtime.m_DD
		, m_dEndtime.m_hh
		, m_dEndtime.m_mm
		, m_dEndtime.m_ss);

	if (! m_bDspFlag) {
		m_dDspFrom = m_dStarttime;
		m_dDspTo = m_dEndtime;
	}
	m_Edit_s_yy.Format("%04d", m_dDspFrom.m_YY);
	m_Edit_s_mm.Format("%02d", m_dDspFrom.m_MM);
	m_Edit_s_dd.Format("%02d", m_dDspFrom.m_DD);
	m_Edit_s_h.Format("%02d", m_dDspFrom.m_hh);
	m_Edit_s_m.Format("%02d", m_dDspFrom.m_mm);
	m_Edit_s_s.Format("%02d", m_dDspFrom.m_ss);
	m_Edit_e_yy.Format("%04d", m_dDspTo.m_YY);
	m_Edit_e_mm.Format("%02d", m_dDspTo.m_MM);
	m_Edit_e_dd.Format("%02d", m_dDspTo.m_DD);
	m_Edit_e_h.Format("%02d", m_dDspTo.m_hh);
	m_Edit_e_m.Format("%02d", m_dDspTo.m_mm);
	m_Edit_e_s.Format("%02d", m_dDspTo.m_ss);

	// �\�����Ԕ͈͂𐔎��̂ݓ��͉ɐݒ�
	m_Edit_s_yy.SetValidChar(VC_NUM);
	m_Edit_s_mm.SetValidChar(VC_NUM);
	m_Edit_s_dd.SetValidChar(VC_NUM);
	m_Edit_s_h.SetValidChar(VC_NUM);
	m_Edit_s_m.SetValidChar(VC_NUM);
	m_Edit_s_s.SetValidChar(VC_NUM);
	m_Edit_e_yy.SetValidChar(VC_NUM);
	m_Edit_e_mm.SetValidChar(VC_NUM);
	m_Edit_e_dd.SetValidChar(VC_NUM);
	m_Edit_e_h.SetValidChar(VC_NUM);
	m_Edit_e_m.SetValidChar(VC_NUM);
	m_Edit_e_s.SetValidChar(VC_NUM);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);
	CString sTooltip;
	sTooltip.Format("�w�肵�����Ԕ͈͂�%s��\�����܂��B", m_sKind);
	m_tooltip.AddTool(GetDlgItem(IDOK), sTooltip);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CTimeRangeDlg::ConvertSecondToHMS
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�b����HHMMSS�`���̕�����ɕϊ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nSecond			[I] �b��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CString		�ϊ�����������
//*****************************************************************************************************
CString CTimeRangeDlg::ConvertSecondToHMS(int nSecond)
{
	int nHour = nSecond / (60 * 60);
	nSecond -= nHour * (60 * 60);
	int nMinute = nSecond / 60;
	nSecond -= nMinute * 60;

	CString sHMS;
	sHMS.Format("%02d%02d%02d", nHour, nMinute, nSecond);

	return sHMS;
}

//*****************************************************************************************************
//  1. �֐���
//		CTimeRangeDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�\���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CTimeRangeDlg::OnOK()
{
	// ���Ԃ̐������`�F�b�N
	if (!CheckInputData()) {
		return;
	}

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. �֐���
//		CTimeRangeDlg::CheckInputData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���Ԃ̐������`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CTimeRangeDlg::CheckInputData()
{
	int	yy, mm, dd, h, m, s;
	CString str;
	long Start_msec, End_msec;

	UpdateData(TRUE);

	// ���Ԕ͈͎擾
	yy = m_Edit_s_yy;
	mm = m_Edit_s_mm;
	dd = m_Edit_s_dd;
	h = m_Edit_s_h;
	m = m_Edit_s_m;
	s = m_Edit_s_s;
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("�J�n���ԁF�b�ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	m_dInStarttime.SetTime(yy, mm, dd, h, m, s);

	yy = m_Edit_e_yy;
	mm = m_Edit_e_mm;
	dd = m_Edit_e_dd;
	h = m_Edit_e_h;
	m = m_Edit_e_m;
	s = m_Edit_e_s;
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("�I�����ԁF�b�ɐ������l����͂��Ă��������B");
		return FALSE;
	}
	m_dInEndtime.SetTime(yy, mm, dd, h, m, s);

	Start_msec = m_dInStarttime - m_dStarttime;
	End_msec = m_dInEndtime - m_dStarttime;
	// �J�n���Ԃ��O�ɐݒ�
	if (Start_msec < 0) {
		CGeneral::Alert("�J�n���ԁF�J�n���Ԃ��O�̐ݒ�͂ł��܂���B");
		return FALSE;
	}
	if (End_msec <= 0) {
		CGeneral::Alert("�I�����ԁF�J�n���ԈȑO�̐ݒ�͂ł��܂���B");
		return FALSE;
	}

	Start_msec = m_dStarttime - m_dEndtime;
	End_msec = m_dInEndtime - m_dEndtime;
	// �I�����Ԃ���ɐݒ�
	if (Start_msec >= 0) {
		CGeneral::Alert("�J�n���ԁF�I�����Ԃ���̐ݒ�͂ł��܂���B");
		return FALSE;
	}
	if (End_msec > 0) {
		CGeneral::Alert("�I�����ԁF�I�����Ԃ���̐ݒ�͂ł��܂���B");
		return FALSE;
	}
	long sa = m_dInEndtime - m_dInStarttime;
	if (sa < 0) {
		CGeneral::Alert("�I�����ԁF�J�n���Ԃ��O�̐ݒ�͂ł��܂���B");
		return FALSE;
	}

	// �b���ɕϊ�
	CTime oTime1(m_dStarttime.m_YY, m_dStarttime.m_MM, m_dStarttime.m_DD, 0, 0, 0);
	CTime oTime2(m_dInStarttime.m_YY, m_dInStarttime.m_MM, m_dInStarttime.m_DD, m_dInStarttime.m_hh, m_dInStarttime.m_mm, m_dInStarttime.m_ss);
	CTimeSpan oTimeSpan = oTime2 - oTime1;
	m_nDispBegin = (int)oTimeSpan.GetTotalSeconds();
	m_nDispEnd = m_nDispBegin + (sa / 1000);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CTimeRangeDlg::PreTranslateMessage
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
BOOL CTimeRangeDlg::PreTranslateMessage(MSG* pMsg)
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

	return CDialog::PreTranslateMessage(pMsg);
}
