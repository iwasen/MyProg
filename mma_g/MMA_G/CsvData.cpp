// CsvData.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "CsvData.h"
#include "DataDateTime.h"
#include "General.h"
#include <shlwapi.h>

// CCsvData �_�C�A���O

IMPLEMENT_DYNAMIC(CCsvData, CDialog)

CCsvData::CCsvData(CWnd* pParent /*=NULL*/)
	: CDialog(CCsvData::IDD, pParent)
{

}

CCsvData::~CCsvData()
{
}

void CCsvData::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_START, m_Edit_Start);
	DDX_Control(pDX, IDC_EDIT_END, m_Edit_Endtime);
	DDX_Control(pDX, IDC_EDIT_MAI, m_Edit_FileName);
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
	DDX_Control(pDX, IDC_COMBO_SAMPLE_RATE, m_cComboSampleRate);
	DDX_Control(pDX, IDC_COMBO_TARGET_DATA, m_cComboTargetData);
}


BEGIN_MESSAGE_MAP(CCsvData, CDialog)
	ON_BN_CLICKED(IDC_BUTTON_REF, &CCsvData::OnBnClickedButtonRef)
	ON_BN_CLICKED(IDC_BUTTON_CONV, &CCsvData::OnBnClickedButtonConv)
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_REF, "�o�͂���CSV�t�@�C�����w�肷�邽�߂̃_�C�A���O���J���܂��B"},
	{IDC_BUTTON_CONV, "CSV�t�@�C���̏o�͂����s���܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};


// CCsvData ���b�Z�[�W �n���h��

void CCsvData::DspModal(LPCSTR pFilePath, int nDataType)
{
	// �f�[�^���(MMA or MME)�ۑ�
	m_cDataFile.m_nDataType = nDataType;

	// ���t�@�C���ǂݍ���
	if (!m_cDataFile.ReadInfoFile(pFilePath))
		return;

	// ���t�@�C���ɏ]���J�n���ԕҏW
	m_startTime.SetTime(m_cDataFile.m_nStartYear
		, m_cDataFile.m_nStartMonth
		, m_cDataFile.m_nStartDay
		, m_cDataFile.m_nStartMiliSecond);

	// ���t�@�C���ɏ]���I�����ԕҏW
	m_endTime = m_startTime;
	m_endTime += (int)(m_cDataFile.m_fTotalSecond * 1000);

	// �ݒ�_�C�A���O���J��
	if (DoModal() == IDCANCEL)
		return;

	m_cDataFile.m_dStarttime = m_startTime;
	m_cDataFile.m_dInStarttime = m_In_startTime;
	m_cDataFile.m_dInEndtime = m_In_endTime;

	// �ݒ���ɏ]���A�J�n�b���`�I���b���Z�o
	CTime oTime1(m_cDataFile.m_dStarttime.m_YY, m_cDataFile.m_dStarttime.m_MM, m_cDataFile.m_dStarttime.m_DD, 0, 0, 0);
	CTime oTime2(m_cDataFile.m_dInStarttime.m_YY, m_cDataFile.m_dInStarttime.m_MM, m_cDataFile.m_dInStarttime.m_DD, m_cDataFile.m_dInStarttime.m_hh, m_cDataFile.m_dInStarttime.m_mm, m_cDataFile.m_dInStarttime.m_ss);
	CTimeSpan oTimeSpan = oTime2 - oTime1;
	int	from = (int)oTimeSpan.GetTotalSeconds();
	int	to = from + (m_In_endTime - m_In_startTime) / 1000;

	// �f�[�^�ǂݍ���
	if (!m_cDataFile.ReadWriteDataFile(m_sCsvPath, from, to, TRUE)) {
		return;
	}
}

BOOL CCsvData::OnInitDialog()
{
	CDialog::OnInitDialog();

	// �T���v�����O���g���̃R���{�{�b�N�X��ݒ�
	CGeneral::SetSampleRateList(m_cComboSampleRate, m_cDataFile.m_fSampleRate);

	// CSV�ϊ��Ώۃf�[�^���R���{�{�b�N�X�ɐݒ�
	m_cComboTargetData.SetItemData(m_cComboTargetData.AddString("�����x�f�[�^"), FALSE);
	if (m_cDataFile.m_nDataType == DATA_TYPE_MMA)
		m_cComboTargetData.SetItemData(m_cComboTargetData.AddString("���x�f�[�^"), TRUE);
	m_cComboTargetData.SetCurSel(0);	// �f�t�H���g�͉����x�f�[�^

	CString	str;

	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
			, m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
			, m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss);
	m_Edit_Start.SetWindowText(str);

	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
			, m_endTime.m_YY, m_endTime.m_MM, m_endTime.m_DD
			, m_endTime.m_hh, m_endTime.m_mm, m_endTime.m_ss);
	m_Edit_Endtime.SetWindowText(str);

	str.Format("%04d", m_startTime.m_YY);
	m_Edit_s_yy.SetWindowText(str);

	str.Format("%02d", m_startTime.m_MM);
	m_Edit_s_mm.SetWindowText(str);

	str.Format("%02d", m_startTime.m_DD);
	m_Edit_s_dd.SetWindowText(str);

	str.Format("%02d", m_startTime.m_hh);
	m_Edit_s_h.SetWindowText(str);

	str.Format("%02d", m_startTime.m_mm);
	m_Edit_s_m.SetWindowText(str);

	str.Format("%02d", m_startTime.m_ss);
	m_Edit_s_s.SetWindowText(str);

	str.Format("%04d", m_endTime.m_YY);
	m_Edit_e_yy.SetWindowText(str);

	str.Format("%02d", m_endTime.m_MM);
	m_Edit_e_mm.SetWindowText(str);

	str.Format("%02d", m_endTime.m_DD);
	m_Edit_e_dd.SetWindowText(str);

	str.Format("%02d", m_endTime.m_hh);
	m_Edit_e_h.SetWindowText(str);

	str.Format("%02d", m_endTime.m_mm);
	m_Edit_e_m.SetWindowText(str);

	str.Format("%02d", m_endTime.m_ss);
	m_Edit_e_s.SetWindowText(str);

	LPITEMIDLIST a_pItemList;

	HRESULT hresult;
	LPMALLOC pMalloc;

	hresult = ::SHGetMalloc( &pMalloc );
	if(FAILED(hresult)){
		AfxMessageBox("");
	}

	hresult = ::SHGetSpecialFolderLocation((HWND)this->m_hWnd,CSIDL_DESKTOP, &a_pItemList);
	char strPath[MAX_PATH+1];
	::SHGetPathFromIDList(a_pItemList, strPath);

	CString strFileName;
	strFileName.Format("\\%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d.csv"
			,m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
			,m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss
			,m_endTime.m_YY,   m_endTime.m_MM,   m_endTime.m_DD
			,m_endTime.m_hh,   m_endTime.m_mm,   m_endTime.m_ss);

	CString strFilePath;
	strFilePath.Format("%s", strPath);
	strFilePath += strFileName;
	m_Edit_FileName.SetWindowText(strFilePath);
	pMalloc->Free(a_pItemList);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}


void CCsvData::OnBnClickedButtonRef()
{
	// �t�@�C���I���_�C�A���O���J��
	CString strFileName;
	m_Edit_FileName.GetWindowText(strFileName);

	CFileDialog cFDlg(TRUE
		, "csv"
		, strFileName
		, OFN_HIDEREADONLY | OFN_OVERWRITEPROMPT 
		, "CSV�t�@�C��(*.csv)|*.csv|�S�Ẵt�@�C��(*.*)|*.*||", this);

	// �L�����Z���Ȃ�Ή������Ȃ�
	if (cFDlg.DoModal() == IDCANCEL) {
		return;
	}
	CString str;

	str = cFDlg.GetPathName();
	m_Edit_FileName.SetWindowText(str);
}

void CCsvData::OnBnClickedButtonConv()
{
	// ���͍��ڂ��擾����
	if (GetInputData())
		return;

	// ���s�m�F
	if (AfxMessageBox("���s���܂����H", MB_YESNO) != IDYES)
		return;

	// CSV�t�@�C�����݋y�я㏑���m�F
	if (::PathFileExists(m_sCsvPath)) {
		if (AfxMessageBox("CSV�t�@�C���͊��ɑ��݂��܂��B�㏑�����܂����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
			return;
	}

	CDialog::OnOK();
}

// ���͍��ڂ��擾����
int CCsvData::GetInputData(void)
{
	int	yy, mm, dd, h, m, s;
	CString str;
	long Start_msec, End_msec;

	UpdateData(TRUE);

	// �_�E�������N�f�[�^�w��`�F�b�N
	m_Edit_FileName.GetWindowText(str);
	if (str.GetLength() == 0) {
		CGeneral::Alert("CSV�t�@�C�������w�肵�Ă��������B");
		return 1;
	}
	m_sCsvPath = str;

	// ���Ԕ͈͎擾
	m_Edit_s_yy.GetWindowText(str);
	yy = atoi(str);
	m_Edit_s_mm.GetWindowText(str);
	mm = atoi(str);
	m_Edit_s_dd.GetWindowText(str);
	dd = atoi(str);
	m_Edit_s_h.GetWindowText(str);
	h = atoi(str);
	m_Edit_s_m.GetWindowText(str);
	m = atoi(str);
	m_Edit_s_s.GetWindowText(str);
	s = atoi(str);
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("�J�n���ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("�J�n���ԁF�b�ɐ������l����͂��Ă��������B");
		return 1;
	}
	m_In_startTime.SetTime(yy, mm, dd, h, m, s);

	m_Edit_e_yy.GetWindowText(str);
	yy = atoi(str);
	m_Edit_e_mm.GetWindowText(str);
	mm = atoi(str);
	m_Edit_e_dd.GetWindowText(str);
	dd = atoi(str);
	m_Edit_e_h.GetWindowText(str);
	h = atoi(str);
	m_Edit_e_m.GetWindowText(str);
	m = atoi(str);
	m_Edit_e_s.GetWindowText(str);
	s = atoi(str);
	if (mm > 12 || mm < 1) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (dd > 31 || dd < 1) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (h > 23 || h < 0) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (m > 59 || m < 0) {
		CGeneral::Alert("�I�����ԁF���ɐ������l����͂��Ă��������B");
		return 1;
	}
	if (s > 59 || s < 0) {
		CGeneral::Alert("�I�����ԁF�b�ɐ������l����͂��Ă��������B");
		return 1;
	}
	m_In_endTime.SetTime(yy, mm, dd, h, m, s);

	Start_msec = m_In_startTime - m_startTime;
	End_msec = m_In_endTime - m_startTime;
	// �J�n���Ԃ��O�ɐݒ�
	if (Start_msec < 0) {
		CGeneral::Alert("�J�n���ԁF�J�n���Ԃ��O�̐ݒ�͂ł��܂���B");
		return 1;
	}
	if (End_msec < 0) {
		CGeneral::Alert("�I�����ԁF�J�n���Ԃ��O�̐ݒ�͂ł��܂���B");
		return 2;
	}

	Start_msec = m_In_startTime - m_endTime;
	End_msec = m_In_endTime - m_endTime;
	// �I�����Ԃ���ɐݒ�
	if (Start_msec >= 0) {
		CGeneral::Alert("�J�n���ԁF�I�����Ԃ���̐ݒ�͂ł��܂���B");
		return 3;
	}
	if (End_msec > 0) {
		CGeneral::Alert("�I�����ԁF�I�����Ԃ���̐ݒ�͂ł��܂���B");
		return 4;
	}
	long period = m_In_endTime - m_In_startTime;
	if (period > 60 * 60 * 1000) {
		CGeneral::Alert("���Ԕ͈́F�P���ԕ��܂łɂ��Ă��������B");
		return 5;
	}

	int	nIndex;
	if ((nIndex = m_cComboSampleRate.GetCurSel()) == CB_ERR) {
		CGeneral::Alert("�T���v�����O���g���F�����ꂩ��I�����Ă��������B");
		return 6;
	}
	// �Ԉ������[�g�擾
	m_cDataFile.m_nDownRate = (int)m_cComboSampleRate.GetItemData(nIndex);

	if ((nIndex = m_cComboTargetData.GetCurSel()) == CB_ERR) {
		CGeneral::Alert("�Ώۃf�[�^�F�����ꂩ��I�����Ă��������B");
		return 7;
	}
	// �Ώۃf�[�^���擾
	m_cDataFile.m_bTempOnly = (BOOL)m_cComboTargetData.GetItemData(nIndex);
	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CCsvData::PreTranslateMessage
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
BOOL CCsvData::PreTranslateMessage(MSG* pMsg)
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
