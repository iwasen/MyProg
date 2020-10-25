// ConvDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "MMA_G.h"
#include "GlobalData.h"
#include "ConvDlg.h"
#include "General.h"
#include "Complement.h"
#include "shlwapi.h"

// CConvDlg �_�C�A���O

IMPLEMENT_DYNAMIC(CConvDlg, CDialog)

CConvDlg::CConvDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CConvDlg::IDD, pParent)
	, m_MaiPath(_T(""))
	, m_pDoc(NULL)
	, m_GoEnable(false)
{
}

CConvDlg::~CConvDlg()
{
}

void CConvDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_MAI, m_Edit_ref);
	DDX_Control(pDX, IDC_EDIT_UNITID, m_Edit_UnitId);
	DDX_Control(pDX, IDC_EDIT_SENSORID, m_Edit_SensorId);
	DDX_Control(pDX, IDC_EDIT_SENSORID2, m_Edit_SampleRate);
	DDX_Control(pDX, IDC_EDIT_START, m_Edit_StartTime);
	DDX_Control(pDX, IDC_EDIT_END, m_Edit_EndTime);
	DDX_Control(pDX, IDC_BUTTON_CONV, m_Button_Go);
	DDX_Control(pDX, IDCANCEL, m_Button_Cancel);
	DDX_Control(pDX, IDC_BUTTON_REF, m_Button_Ref);
	DDX_Control(pDX, IDC_COMBO_SENSOR, m_Combo_Sensor);
	DDX_Control(pDX, IDC_EDIT_QUALITY, m_Edit_Quality);
	DDX_Control(pDX, IDC_EDIT_ISS, m_Edit_Config);
	DDX_Control(pDX, IDC_EDIT_ISS2, m_Edit_Config2);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_Edit_Comment);
	DDX_Control(pDX, IDC_EDIT_DOWNLINK, m_Downlink);
	DDX_Control(pDX, IDC_COMBO_CATEGORY, m_Combo_Category);
	DDX_Control(pDX, IDC_COMBO_MEASUREMENT_KIND, m_Combo_MeasurementKind);
	DDX_Control(pDX, IDC_MEAS_TIME, m_Edit_MeasTime);
}


BEGIN_MESSAGE_MAP(CConvDlg, CDialog)
	ON_BN_CLICKED(IDC_BUTTON_CONV, &CConvDlg::OnBnClickedButtonConv)
	ON_BN_CLICKED(IDC_BUTTON_REF, &CConvDlg::OnBnClickedButtonRef)
	ON_BN_CLICKED(IDCANCEL, &CConvDlg::OnBnClickedCancel)
	ON_EN_UPDATE(IDC_EDIT_DOWNLINK, &CConvDlg::OnEnUpdateEditDownlink)
	ON_EN_UPDATE(IDC_EDIT_QUALITY, &CConvDlg::OnEnUpdateEditQuality)
	ON_EN_UPDATE(IDC_EDIT_ISS, &CConvDlg::OnEnUpdateEditIss)
	ON_EN_UPDATE(IDC_EDIT_S_YY, &CConvDlg::OnEnUpdateEditSYy)
	ON_EN_UPDATE(IDC_EDIT_S_MM, &CConvDlg::OnEnUpdateEditSMm)
	ON_EN_UPDATE(IDC_EDIT_S_DD, &CConvDlg::OnEnUpdateEditSDd)
	ON_EN_UPDATE(IDC_EDIT_S_H, &CConvDlg::OnEnUpdateEditSH)
	ON_EN_UPDATE(IDC_EDIT_S_M, &CConvDlg::OnEnUpdateEditSM)
	ON_EN_UPDATE(IDC_EDIT_S_S, &CConvDlg::OnEnUpdateEditSS)
	ON_EN_UPDATE(IDC_EDIT_E_YY, &CConvDlg::OnEnUpdateEditEYy)
	ON_EN_UPDATE(IDC_EDIT_E_MM, &CConvDlg::OnEnUpdateEditEMm)
	ON_EN_UPDATE(IDC_EDIT_E_DD, &CConvDlg::OnEnUpdateEditEDd)
	ON_EN_UPDATE(IDC_EDIT_E_H, &CConvDlg::OnEnUpdateEditEH)
	ON_EN_UPDATE(IDC_EDIT_E_M, &CConvDlg::OnEnUpdateEditEM)
	ON_EN_UPDATE(IDC_EDIT_E_S, &CConvDlg::OnEnUpdateEditES)
	ON_CBN_SELCHANGE(IDC_COMBO_SENSOR, &CConvDlg::OnCbnSelchangeComboSensor)
	ON_CBN_EDITCHANGE(IDC_COMBO_SENSOR, &CConvDlg::OnCbnEditchangeComboSensor)
	ON_EN_UPDATE(IDC_EDIT_ISS2, &CConvDlg::OnEnUpdateEditIss2)
	ON_CBN_SELCHANGE(IDC_COMBO_CATEGORY, &CConvDlg::OnCbnSelchangeComboCategory)
	ON_CBN_EDITCHANGE(IDC_COMBO_CATEGORY, &CConvDlg::OnCbnEditchangeComboCategory)
	ON_CBN_SELCHANGE(IDC_COMBO_MEASUREMENT_KIND, &CConvDlg::OnCbnSelchangeComboMeasurementKind)
	ON_CBN_EDITCHANGE(IDC_COMBO_MEASUREMENT_KIND, &CConvDlg::OnCbnEditchangeComboMeasurementKind)
END_MESSAGE_MAP()

// �c�[���`�b�v�\���f�[�^
static const SToolTipData s_aToolTip[] = {
	{IDC_BUTTON_REF, "�_�E�������N�f�[�^�̑��݂���t�H���_��I�����邽�߂̃_�C�A���O���J���܂��B"},
	{IDC_BUTTON_CONV, "�ݒ肳�ꂽ���e�ŁA�H�w�l�ϊ������s���܂��B"},
	{IDCANCEL, "���̃_�C�A���O����܂��B"},
	{0, NULL}
};

// CConvDlg ���b�Z�[�W �n���h��

// �_�C�A���O�������֐�
BOOL CConvDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	int	i;

	for (i = 0; g_pSensorInfo[i].idx != -1; i++) {
		m_Combo_Sensor.InsertString(g_pSensorInfo[i].idx, g_pSensorInfo[i].sel_dat);
	}

	// �����͎��s�{�^��GRAY
	m_Button_Go.EnableWindow(m_GoEnable);

	// �c�[���`�b�v�\���ݒ�
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	// �J�e�S���I������ݒ�
	CGeneral::SetComboCategory(m_Combo_Category, "");

	// �v����ޑI������ݒ�
	CGeneral::SetComboMeasurementKind(m_Combo_MeasurementKind, "");

	return TRUE;
}

// ��������GRAY�ɂ���
void CConvDlg::KeyOff(void)
{
	m_Button_Go.EnableWindow(FALSE);
	m_Button_Cancel.EnableWindow(FALSE);
	m_Button_Ref.EnableWindow(FALSE);
}

// GRAY����������
void CConvDlg::KeyOn(void)
{
	m_Button_Go.EnableWindow(m_GoEnable);
	m_Button_Cancel.EnableWindow(TRUE);
	m_Button_Ref.EnableWindow(TRUE);
}

// �Q�ƃ{�^����������
void CConvDlg::OnBnClickedButtonRef()
{
    BROWSEINFO bInfo;
    LPITEMIDLIST pIDList;
	char	Buf[_MAX_PATH];
    
	strcpy_s(Buf, gDownlinkReferencePath);
    // BROWSEINFO�\���̂ɒl��ݒ�
    bInfo.hwndOwner             = m_hWnd;		           // �_�C�A���O�̐e�E�C���h�E�̃n���h��
    bInfo.pidlRoot              = NULL;			           // ���[�g�t�H���_������ITEMIDLIST�̃|�C���^ (NULL�̏ꍇ�f�X�N�g�b�v�t�H���_���g���܂��j
    bInfo.pszDisplayName        = Buf;          // �I�����ꂽ�t�H���_�����󂯎��o�b�t�@�̃|�C���^
    bInfo.lpszTitle             = "�_�E�������N�t�@�C�����݃t�H���_�w��";				   // �c���[�r���[�̏㕔�ɕ\������镶���� 
    bInfo.ulFlags               = BIF_RETURNONLYFSDIRS;    // �\�������t�H���_�̎�ނ������t���O
    bInfo.lpfn                  = BrowseCallbackProc;      // BrowseCallbackProc�֐��̃|�C���^
    bInfo.lParam                = (LPARAM)Buf;             // �R�[���o�b�N�֐��ɓn���l
    
    // �t�H���_�I���_�C�A���O��\��
    pIDList = ::SHBrowseForFolder(&bInfo);
    if(pIDList != NULL){
        // ItemIDList���p�X���ɕϊ����܂�
        if(!::SHGetPathFromIDList(pIDList, Buf)){
            // �G���[����
        }

        // �Ō��pIDList�̃|�C���g���Ă��郁������������܂�
        ::CoTaskMemFree( pIDList );
		m_Edit_ref.SetWindowText(Buf);

		// mai�t�@�C���ǂݍ��ݏ���
		Read();
    }
}

// �t�H���_�I���_�C�A���O�R�[���o�b�N�֐�
int CALLBACK CConvDlg::BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData)
{
	if (uMsg == BFFM_INITIALIZED)
		::SendMessage(hwnd, BFFM_SETSELECTION, (WPARAM)TRUE, lpData);

	return 0;
}

// mai�t�@�C���ǂݍ��ݏ���
void CConvDlg::Read()
{
	CWaitCursor cursor;

	KeyOff();

	m_Edit_ref.GetWindowText(m_MaiPath);

	// �w��p�X��mai�t�@�C����ǂ݁A�J�n�����I���������Z�b�g����
	if (m_maiFile.ReadMaiFile((LPCSTR)m_MaiPath, &m_startTime, &m_endTime) != 0) {
		// �t�@�C�����e�\������
		DspData();
	} else {
		m_Edit_ref.SetWindowText("");
	}

	KeyOn();
}

// �t�@�C�����e�\������
void CConvDlg::DspData(void)
{
	CString	str;

	// UnitId�\��
	str.Format("%d", m_maiFile.m_RsuId);
	m_Edit_UnitId.SetWindowText(str);

	// Sensor Id�\��
	str.Format("%d", m_maiFile.m_SensorId);
	m_Edit_SensorId.SetWindowText(str);

	// �T���v�����[�g�\��
	str.Format("%.2lf", m_maiFile.m_SampleRate);
	m_Edit_SampleRate.SetWindowText(str);

	// �J�n�����\��
	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
		, m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss);
	m_Edit_StartTime.SetWindowText(str);

	// �I�������\��
	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_endTime.m_YY, m_endTime.m_MM, m_endTime.m_DD
		, m_endTime.m_hh, m_endTime.m_mm, m_endTime.m_ss);
	m_Edit_EndTime.SetWindowText(str);

	// �v�����ԕ\��
	COleDateTime a_oDateStart(m_startTime.m_YY, m_startTime.m_MM, m_startTime.m_DD
							, m_startTime.m_hh, m_startTime.m_mm, m_startTime.m_ss);
	COleDateTime a_oDateEnd(m_endTime.m_YY, m_endTime.m_MM, m_endTime.m_DD
							, m_endTime.m_hh, m_endTime.m_mm, m_endTime.m_ss);
	COleDateTimeSpan a_oMeasTime = a_oDateEnd - a_oDateStart;

	// 1����1�N�Ԃ��v���𑱂��Ă��邱�Ƃ͍l���ɂ����̂ŁC�����ł�
	// �ő�ł��C�����Ԃ��̃f�[�^�����Ȃ��Ɖ��肷��B
	// �i�N�⌎�̂��Ƃ͍l���Ȃ��j
	LONG a_lDays = a_oMeasTime.GetDays();
	LONG a_lHour = a_oMeasTime.GetHours();
	LONG a_lMin  = a_oMeasTime.GetMinutes();
	LONG a_lSec  = a_oMeasTime.GetSeconds();

	if(a_lDays > 0){
		a_lHour += a_lDays * 24;
	}

	str.Format("%dhours  %02dmin  %02dsec", a_lHour, a_lMin, a_lSec);
	m_Edit_MeasTime.SetWindowText(str);

}

// �ϊ����s�{�^������
void CConvDlg::OnBnClickedButtonConv()
{
	CWaitCursor cursor;

	// ���̓f�[�^�擾�A�`�F�b�N�A�f�[�^�ۑ�
	if (GetInputData() != 0)
		return;

	// Event Logs�t�H���_���݃`�F�b�N
	if (!::PathFileExists(m_MaiPath + "\\Event Logs")) {
		if (AfxMessageBox("Event Logs�t�H���_������܂���B�ϊ��������p�����܂����H", MB_YESNO | MB_ICONQUESTION) == IDNO) {
			AfxMessageBox("�ϊ������𒆎~���܂��B\nEvent Logs�t�H���_���R�s�[���Ă���ēx���s���Ă��������B", MB_OK | MB_ICONINFORMATION);
			return;
		}
	}

	if (AfxMessageBox("�ϊ����������s���܂����H", MB_YESNO) != IDYES)
		return;

	KeyOff();

	// ��ԏ���
	Complement complement;
	if (complement.ComplementData(m_MaiPath, m_maiFile.m_maiFname)) {
		// Data Quality Measure�Ƀ��b�Z�[�W�ǉ�
		m_maiFile.m_Idb.m_dataquality += ", Termperature Data Error was found and complemented.";

		// mai�t�@�C���ēǂݍ���
		m_maiFile.ReadMaiFile2(m_MaiPath + "\\" + m_maiFile.m_maiFname, TRUE);
	}

	// �w�莞���̏o�̓t�@�C������������
	m_maiFile.WriteData((LPCSTR)m_MaiPath, 0, m_endTime - m_startTime);

	m_pDoc->UpdateAllViews(NULL);

	AfxMessageBox("�H�w�l�ϊ����I�����܂���", MB_ICONINFORMATION | MB_OK);

	KeyOn();
}

// ���͍��ڂ��擾����
int CConvDlg::GetInputData(void)
{
	CString str, str2;

	UpdateData(TRUE);

	// �_�E�������N�f�[�^�w��`�F�b�N
	m_Edit_ref.GetWindowText(str);
	if (str.GetLength() == 0) {
		CGeneral::Alert("�_�E�������N�f�[�^���w�肵�Ă��������B");
		return 1;
	}

	// Sensor Coordinate System�擾
	int	sidx;
	if ((sidx = m_Combo_Sensor.GetCurSel()) == -1) {
		CGeneral::Alert("Sensor Coordinate System�F�����ꂩ�I�����Ă��������B");
		return 5;
	}

	// �J�e�S�����擾
	m_Combo_Category.GetWindowText(str);
	str = str.Trim();
	if( str == _T("") ){
		CGeneral::Alert("�J�e�S����I�����Ă��������B");
		return 11;
	}
	if (!CGeneral::CheckFileName("�J�e�S��", str))
		return 11;

	// �v����ނ��擾
	m_Combo_MeasurementKind.GetWindowText(str);
	str = str.Trim();
	if( str == _T("") ){
		CGeneral::Alert("�v����ނ�I�����Ă��������B");
		return 12;
	}
	if (!CGeneral::CheckFileName("�v�����", str))
		return 12;

	// �_�E�������N����������擾
	m_Downlink.GetWindowText(str);
	str = str.Trim();
	// �_�E�������N�����󗓂̏ꍇ�́C�G���[�Ƃ���B
	if( str == _T("") ){
		CGeneral::Alert("�_�E�������N�����󗓂ł��B�_�E�������N������͂��Ă��������B");
		return 6;
	}
/*
	// Quality Measure��������擾
	m_Edit_Quality.GetWindowText(str);
	str = str.Trim();
	// Data Quality Measure���󗓂̏ꍇ�́C�G���[�Ƃ���B
	if( str == _T("") ){
		CGeneral::Alert("Data Quality Measure���󗓂ł��BData Quality Measure����͂��Ă��������B");
		return 7;
	}

	// ISS Config��������擾
	m_Edit_Config.GetWindowText(str);
	str = str.Trim();
	// ISS Configuration���󗓂̏ꍇ�́C�G���[�Ƃ���B
	if( str == _T("") ){
		CGeneral::Alert("ISS Configuration��Increment���󗓂ł��BISS Configuration��Increment����͂��Ă��������B");
		return 8;
	}
	m_Edit_Config2.GetWindowText(str);
	str = str.Trim();
	// ISS Configuration���󗓂̏ꍇ�́C�G���[�Ƃ���B
	if( str == _T("") ){
		CGeneral::Alert("ISS Configuration��Flight���󗓂ł��BISS Configuration��Flight����͂��Ă��������B");
		return 9;
	}
*/
	// �H�w�l�f�[�^�������݃t�H���_�쐬
	CString	pos, category, period, measurement_kind;
//	pos = SensorInfo[sidx].dat;
	pos = g_pSensorInfo[sidx].dat;
	m_Combo_Category.GetWindowText(category);
	m_Combo_MeasurementKind.GetWindowText(measurement_kind);
	period.Format("%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d"
		, m_startTime.m_YY
		, m_startTime.m_MM
		, m_startTime.m_DD
		, m_startTime.m_hh
		, m_startTime.m_mm
		, m_startTime.m_ss
		, m_endTime.m_YY
		, m_endTime.m_MM
		, m_endTime.m_DD
		, m_endTime.m_hh
		, m_endTime.m_mm
		, m_endTime.m_ss);
	if(m_maiFile.mkDir(pos, category, measurement_kind)){
		return 10;
	}

	// ���̓f�[�^��ۑ�
	// ���N���A
	m_maiFile.m_Idb.DelAll();
	// �Z���T�[IDX
	m_maiFile.m_Idb.SetSensorIdx(sidx);
	// �f�[�^�͈�
	m_maiFile.m_Idb.SetPeriod(period);
	// �_�E�������N��
	m_Downlink.GetWindowText(str);
	m_maiFile.m_Idb.SetDownlink(str);
	// �l�b�g���[�NID�iUNIT ID��ʂS���j
	m_maiFile.m_Idb.SetNetworkid(m_maiFile.m_NetworkId);
	// ���j�b�gID�iUNIT ID���ʂS���j
	m_maiFile.m_Idb.SetRsuId(m_maiFile.m_RsuId);
	// �Z���T�[ID
	m_maiFile.m_Idb.SetSensorId(m_maiFile.m_SensorId);
	// �^�C���[��
	str.Format("%04d%02d%02d%02d%02d%02d"
		, m_startTime.m_YY
		, m_startTime.m_MM
		, m_startTime.m_DD
		, m_startTime.m_hh
		, m_startTime.m_mm
		, m_startTime.m_ss);
	m_maiFile.m_Idb.SetTimeZero(str);
	// �T���v�����[�g
	m_maiFile.m_Idb.SetSampleRate(m_maiFile.m_SampleRate);
	// �ۑ��p�X��
	m_maiFile.m_Idb.SetPath(m_maiFile.m_ConvDataPath);
	// Sensor Co Sys
	m_maiFile.m_Idb.SetSensorcosys(pos);
	// Data Co Sys
	m_maiFile.m_Idb.SetDatacosys(pos);
	// Data Quality
	m_Edit_Quality.GetWindowText(str);
	m_maiFile.m_Idb.SetDataquality(str);
	// ISS Config
	m_Edit_Config.GetWindowText(str);
	m_Edit_Config2.GetWindowText(str2);
	CString str3;
	if (!str.IsEmpty() || !str2.IsEmpty()) {
		str3 = "Increment: ";
		str3 += str;
		str3 += ", Flight: ";
		str3 += str2;
	} else
		str3.Empty();
	m_maiFile.m_Idb.SetIssconfig(str3);
	// �R�����g
	m_Edit_Comment.GetWindowText(str);
	m_maiFile.m_Idb.SetComment(str);
	// �J�e�S��
	m_Combo_Category.GetWindowText(str);
	m_maiFile.m_Idb.SetCategory(str);
	// �v�����
	m_Combo_MeasurementKind.GetWindowText(str);
	m_maiFile.m_Idb.SetMeasurementKind(str);

	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	m_maiFile.m_Idb.SetStartTime(
		m_startTime.m_YY
		, m_startTime.m_MM
		, m_startTime.m_DD
		, (m_startTime.m_hh * 60 * 60 + m_startTime.m_mm * 60 + m_startTime.m_ss) * 1000);

	// ���͊J�n���Ԃ������n��
	m_maiFile.m_In_startTime = m_startTime;

	return 0;
}

bool CConvDlg::CheckData(void)
{
	CString str;

	UpdateData(TRUE);

	// �_�E�������N�f�[�^�w��`�F�b�N
	m_Edit_ref.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// Sensor Coordinate System�擾
	if (m_Combo_Sensor.GetCurSel() == -1)
		return false;

	// �J�e�S�����擾
	m_Combo_Category.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// �v����ނ��擾
	m_Combo_MeasurementKind.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// �_�E�������N����������擾
	m_Downlink.GetWindowText(str);
	if (str.IsEmpty())
		return false;
/*
	// Quality Measure��������擾
	m_Edit_Quality.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// ISS Config��������擾
	m_Edit_Config.GetWindowText(str);
	if (str.IsEmpty())
		return false;

	// ISS Config��������擾
	m_Edit_Config2.GetWindowText(str);
	if (str.IsEmpty())
		return false;
*/
	return true;
}

void CConvDlg::OnBnClickedCancel()
{
	// �f�[�^���Z�b�g
	m_maiFile.ResetData();

	OnCancel();
}

void CConvDlg::SetDocPtr(CDocument *pDoc)
{
	m_pDoc = pDoc;
}

void CConvDlg::OnOK()
{
	// TODO: �����ɓ���ȃR�[�h��ǉ����邩�A�������͊�{�N���X���Ăяo���Ă��������B
	// �������Ȃ��悤�ɂ���
}

void CConvDlg::UpdateGoButton(void)
{
	m_GoEnable = CheckData();

	m_Button_Go.EnableWindow(m_GoEnable);
}

void CConvDlg::OnEnUpdateEditDownlink()
{
	UpdateGoButton();
}
void CConvDlg::OnEnUpdateEditQuality()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditIss()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditIss2()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSYy()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSMm()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSDd()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSH()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSM()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditSS()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEYy()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEMm()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEDd()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEH()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditEM()
{
	UpdateGoButton();
}

void CConvDlg::OnEnUpdateEditES()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnSelchangeComboSensor()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnEditchangeComboSensor()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnSelchangeComboCategory()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnEditchangeComboCategory()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnSelchangeComboMeasurementKind()
{
	UpdateGoButton();
}

void CConvDlg::OnCbnEditchangeComboMeasurementKind()
{
	UpdateGoButton();
}

//*****************************************************************************************************
//  1. �֐���
//		CConvDlg::PreTranslateMessage
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
BOOL CConvDlg::PreTranslateMessage(MSG* pMsg)
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
