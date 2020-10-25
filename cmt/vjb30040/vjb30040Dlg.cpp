// vjb30040Dlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "vjb30040.h"
#include "InputDevice.h"
#include "vjb30040Dlg.h"
#include "InputFPD.h"
#include "InputMOD.h"
#include "InputCMT.h"
#include "InputDAT.h"

// �萔��` 

// �R���{�{�b�N�X�C���f�b�N�X
#define DEVICE_CMT  0
#define DEVICE_DAT  1
#define DEVICE_FPD  2
#define DEVICE_MOD  3

// �e�L�X�g���e��������(DeleteEditInfo)�p�����[�^
#define LABEL_ARINASHI_CHECK_STATUS  0
#define ELSE_STATUS  1

// �}�̎��ʃR�[�h���͉\������
#define DEVICE_SHIKIBETSU_CODE  "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890" 

// Vol���x�������͉\������
#define DEVICE_VOL_NAME  "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-" 

// Vol�{�����͒l�`�F�b�N�p
#define DEVICE_VOL_SU  "1234567890" 

// ��`������
#define DEFINE_SLASH  "/" 
#define DEFINE_COMMA  "," 

// ���̓f�[�^��������l
#define CMT_LABEL_ARI_KANMA  0 
#define CMT_LABEL_NASHI_KANMA  1 

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30040Dlg �_�C�A���O

CVjb30040Dlg::CVjb30040Dlg(CWnd* pParent /*=NULL*/)
	: CDialog(CVjb30040Dlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CVjb30040Dlg)
	m_iRadioLabel = -1;
	m_iComboDeviceName = -1;
	m_sEditVolFile = _T("");
	m_sEditShikibetsuCode = _T("");
	m_sEditVolSu = _T("");
	//}}AFX_DATA_INIT
	// ����: LoadIcon �� Win32 �� DestroyIcon �̃T�u�V�[�P���X��v�����܂���B
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CVjb30040Dlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CVjb30040Dlg)
	DDX_Control(pDX, IDC_EDIT_SHIKIBETSU_CODE, m_EDIT_SHIKIBETSU_CODE);
	DDX_Control(pDX, IDC_COMBO_SRV_NAME, m_COMBO_SRV_NAME);
	DDX_Control(pDX, IDC_COMBO_DEVICE_NAME, m_COMBO_DEVICE_NAME);
	DDX_Control(pDX, IDC_EDIT_VOL_SU, m_EDIT_VOL_SU);
	DDX_Control(pDX, IDC_LABEL_VOL_SU, m_LABEL_VOL_SU);
	DDX_Control(pDX, IDC_EDIT_VOL_FILE, m_EDIT_VOL_FILE);
	DDX_Control(pDX, IDC_LABEL_FILE_NAME, m_LABEL_FILE_NAME);
	DDX_Control(pDX, IDC_LABEL_VOL_NAME, m_LABEL_VOL_NAME);
	DDX_Control(pDX, IDC_RADIO_LABEL_ARI, m_RADIO_LABEL_ARI);
	DDX_Control(pDX, IDC_RADIO_LABEL_NASHI, m_RADIO_LABEL_NASHI);
	DDX_Radio(pDX, IDC_RADIO_LABEL_ARI, m_iRadioLabel);
	DDX_CBIndex(pDX, IDC_COMBO_DEVICE_NAME, m_iComboDeviceName);
	DDX_Text(pDX, IDC_EDIT_VOL_FILE, m_sEditVolFile);
	DDX_Text(pDX, IDC_EDIT_SHIKIBETSU_CODE, m_sEditShikibetsuCode);
	DDX_Text(pDX, IDC_EDIT_VOL_SU, m_sEditVolSu);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CVjb30040Dlg, CDialog)
	//{{AFX_MSG_MAP(CVjb30040Dlg)
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_CBN_SELCHANGE(IDC_COMBO_DEVICE_NAME, OnSelchangeComboDeviceName)
	ON_BN_CLICKED(IDC_RADIO_LABEL_ARI, OnRadioLabelAri)
	ON_BN_CLICKED(IDC_RADIO_LABEL_NASHI, OnRadioLabelNashi)
	ON_BN_CLICKED(IDC_BUTTON_ZIKOU, OnButtonZikou)
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30040Dlg ���b�Z�[�W �n���h��

BOOL CVjb30040Dlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// ���̃_�C�A���O�p�̃A�C�R����ݒ肵�܂��B�t���[�����[�N�̓A�v���P�[�V�����̃��C��
	// �E�B���h�E���_�C�A���O�łȂ����͎����I�ɐݒ肵�܂���B
	SetIcon(m_hIcon, TRUE);			// �傫���A�C�R����ݒ�
	SetIcon(m_hIcon, FALSE);		// �������A�C�R����ݒ�
	
	// �����\����ʐݒ菈��
    SetInitInstance();
	
	// ���͔}�̐ݒ菈��
	SetDeviceName();
	
	// �o�͐�T�[�o���ݒ菈��
	SetServerName();
	
	return TRUE;  // TRUE ��Ԃ��ƃR���g���[���ɐݒ肵���t�H�[�J�X�͎����܂���B
}

// �����_�C�A���O�{�b�N�X�ɍŏ����{�^����ǉ�����Ȃ�΁A�A�C�R����`�悷��
// �R�[�h���ȉ��ɋL�q����K�v������܂��BMFC �A�v���P�[�V������ document/view
// ���f�����g���Ă���̂ŁA���̏����̓t���[�����[�N�ɂ�莩���I�ɏ�������܂��B

void CVjb30040Dlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // �`��p�̃f�o�C�X �R���e�L�X�g

		SendMessage(WM_ICONERASEBKGND, (WPARAM) dc.GetSafeHdc(), 0);

		// �N���C�A���g�̋�`�̈���̒���
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// �A�C�R����`�悵�܂��B
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// �V�X�e���́A���[�U�[���ŏ����E�B���h�E���h���b�O���Ă���ԁA
// �J�[�\����\�����邽�߂ɂ������Ăяo���܂��B
HCURSOR CVjb30040Dlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

//
//	�@�\	�F	������ʐݒ菈��
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�}�̓��͎w����ʂ̏����\����Ԃ�ݒ肷��B
//
//	���l	�F	����
//
void CVjb30040Dlg::SetInitInstance()
{
	// �}�̓��͎w����ʏ����ݒ菈��
	m_RADIO_LABEL_ARI.ShowWindow(SW_HIDE);
	m_RADIO_LABEL_NASHI.ShowWindow(SW_HIDE);
	m_LABEL_VOL_NAME.ShowWindow(SW_HIDE);
	m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
	m_EDIT_VOL_FILE.ShowWindow(SW_HIDE);
	m_LABEL_VOL_SU.ShowWindow(SW_HIDE);
	m_EDIT_VOL_SU.ShowWindow(SW_HIDE);
}

//
//	�@�\	�F	���͔}�̐ݒ菈��
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	���͔}��(�R���{�{�b�N�X)�ɁACMT�ADAT�AFPD�AMO��ݒ肷��B
//
//	���l	�F	����
//
void CVjb30040Dlg::SetDeviceName()
{
	// ���͔}�̐ݒ菈��
	m_COMBO_DEVICE_NAME.AddString("CMT");
	m_COMBO_DEVICE_NAME.AddString("DAT");
	m_COMBO_DEVICE_NAME.AddString("FPD");
	m_COMBO_DEVICE_NAME.AddString("MO");
}

//
//	�@�\	�F	�o�͐�o�b�`�T�[�o���擾����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�o�b�`�T�[�o�w��t�@�C����Ǎ��ށB
//				�o�͐�o�b�`�T�[�o(�R���{�{�b�N�X)�ɁA�o�b�`�T�[�o�w��t�@�C���̃f�[�^��ݒ肷��B
//
//	���l	�F	����
//
void CVjb30040Dlg::SetServerName()
{	
	CStdioFile	csMyCStdio;
	CString		csFileData;

	// �t�@�C�����I�[�v��
	if (csMyCStdio.Open(g_BatchServerFile, CFile::modeRead, NULL)) {
		// NULL�ɂȂ�܂Ń��[�v
		while(csMyCStdio.ReadString(csFileData) != NULL) {
			// �o�͐�o�b�`�T�[�o�����R���{�{�b�N�X�ɐݒ肷��B
			m_COMBO_SRV_NAME.AddString(csFileData);
		}
		
		// �t�@�C�����N���[�Y
		csMyCStdio.Close();
	}
}
	
//
//	�@�\	�F	�R���{�{�b�N�X(���͔}��)�Z���`�F���W����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�R���{�{�b�N�X(���͔}��)�Z�����`�F���W�����ꍇ�̈�A�̏������s���B
//
//	���l	�F	����
//
void CVjb30040Dlg::OnSelchangeComboDeviceName() 
{
	int nIndex;
	
	nIndex = m_COMBO_DEVICE_NAME.GetCurSel();
	if (nIndex == CB_ERR)
		return;

	// DAT�AFPD�AMO�̏ꍇ
	if (nIndex == DEVICE_DAT || nIndex == DEVICE_FPD || nIndex == DEVICE_MOD) {
		// �\����\���ݒ�
		m_LABEL_VOL_NAME.ShowWindow(nIndex == DEVICE_DAT ? SW_HIDE : SW_SHOW);
		m_EDIT_VOL_FILE.ShowWindow(nIndex == DEVICE_DAT ? SW_HIDE : SW_SHOW);
		m_RADIO_LABEL_ARI.ShowWindow(SW_HIDE);
		m_RADIO_LABEL_NASHI.ShowWindow(SW_HIDE);
		m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
		m_EDIT_VOL_SU.ShowWindow(SW_HIDE);
		m_LABEL_VOL_SU.ShowWindow(SW_HIDE);
		
		// ���W�I�����ݒ菈��
		SetInitRadio();
		
		// �e�L�X�g���e������
		DeleteEditInfo(ELSE_STATUS);
	}
	// CMT�̏ꍇ
	else{
		// �\����\���ݒ�
		m_RADIO_LABEL_ARI.ShowWindow(SW_SHOW);
		m_RADIO_LABEL_NASHI.ShowWindow(SW_SHOW);
		m_LABEL_VOL_NAME.ShowWindow(SW_HIDE);
		m_EDIT_VOL_FILE.ShowWindow(SW_HIDE);
		m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
		
		// ���W�I�����ݒ菈��
		SetInitRadio();
		
		// �e�L�X�g���e����������
		DeleteEditInfo(ELSE_STATUS);
	}
}

//
//	�@�\	�F	���W�I�{�^������N���b�N����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	���W�I�{�^��������N���b�N�����ꍇ�̈�A�̏������s���B
//
//	���l	�F	����
//
void CVjb30040Dlg::OnRadioLabelAri() 
{
	// �\����\���ݒ�
	m_LABEL_VOL_NAME.ShowWindow(SW_SHOW);
	m_EDIT_VOL_FILE.ShowWindow(SW_SHOW);		
	m_LABEL_FILE_NAME.ShowWindow(SW_HIDE);
	m_EDIT_VOL_SU.ShowWindow(SW_HIDE);
	m_LABEL_VOL_SU.ShowWindow(SW_HIDE);

	// �e�L�X�g���e������
	DeleteEditInfo(LABEL_ARINASHI_CHECK_STATUS);
}

//
//	�@�\	�F	���W�I�{�^�������N���b�N����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	���W�I�{�^���������N���b�N�����ꍇ�̈�A�̏������s���B
//
//	���l	�F	����
//
void CVjb30040Dlg::OnRadioLabelNashi() 
{
	// �\����\���ݒ�
	m_LABEL_VOL_NAME.ShowWindow(SW_HIDE);
	m_EDIT_VOL_FILE.ShowWindow(SW_SHOW);		
	m_LABEL_FILE_NAME.ShowWindow(SW_SHOW);
	m_EDIT_VOL_SU.ShowWindow(SW_SHOW);
	m_LABEL_VOL_SU.ShowWindow(SW_SHOW);

	// �e�L�X�g���e������
	DeleteEditInfo(LABEL_ARINASHI_CHECK_STATUS);
}

//
//	�@�\	�F	���s�{�^����������
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	���s�{�^��������̏��������s�A���䂷��B
//
//	���l	�F	����
//
void CVjb30040Dlg::OnButtonZikou() 
{
	// ���͔}�́A�o�͐�o�b�`�T�[�o�A�}�̎��ʃR�[�h���̓`�F�b�N���� 
	if (CheckData() == false)
		return;

	// DEVICE���Ƃ̓��̓`�F�b�N����
	if (CheckDeviceInfo() == false)
		return;
	
	// �}�̎��ʃR�[�h���͒l�Ó����`�F�b�N����
	if (CheckDeviceCodeData() == false)
		return;

	// Vol���x�����A�t�@�C����/���R�[�h���AVol�{�����͒l�Ó����`�F�b�N����
	if (CheckVolFileVolsuData() == false)
		return;

	Execute();
}

//
//	�@�\	�F	�e�I�u�W�F�N�g���e�`�F�b�N����
//
//	����	�F	����
//
//	���A�l	�F		bool   TRUE : �`�F�b�NOK
//			�F		       FALSE : �`�F�b�NNG
//
//	�@�\�����F	�e�I�u�W�F�N�g�̏�Ԃ��`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckData() 
{
	// ���͔}�̂��`�F�b�N
	if (CheckNyuryokuData(&m_COMBO_DEVICE_NAME) == false) {
		// ���b�Z�[�W�\������
		DispMessage(IDS_MSG_301, MB_OK, FALSE);
		
		m_COMBO_DEVICE_NAME.SetFocus();
		return false;
	}

	// �o�͐�o�b�`�T�[�o���`�F�b�N
	if (CheckNyuryokuData(&m_COMBO_SRV_NAME) == false) {
		
		// ���b�Z�[�W�\������
		DispMessage(IDS_MSG_302, MB_OK, FALSE);

		m_COMBO_SRV_NAME.SetFocus();
		return false;
	}

	// �}�̎��ʃR�[�h���`�F�b�N
	if (CheckNyuryokuData(&m_EDIT_SHIKIBETSU_CODE) == false) {
	
		// ���b�Z�[�W�\������
		DispMessage(IDS_MSG_303, MB_OK, FALSE);
		
		m_EDIT_SHIKIBETSU_CODE.SetFocus();
		return false;
	}

	return true;
}

//
//	�@�\	�F	�u���s�v�{�^������
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�}�̂���t�@�C������͂���B
//
//	���l	�F	����
//
void CVjb30040Dlg::Execute()
{
	Param param;
	CInputDevice *pInputDevice;
	int nRetCode;
	char *pStatus;
	int nErrorCode;
	CString sErrorMsg;

	// ���̓f�[�^���擾
	UpdateData(TRUE);

	// �p�����[�^�\���̂ɃZ�b�g
	param.nBaitaiShubetsu = m_iComboDeviceName;
	m_COMBO_DEVICE_NAME.GetWindowText(param.sShubetsuCode);
	param.sShikibetsuCode = m_sEditShikibetsuCode;
	m_COMBO_SRV_NAME.GetWindowText(param.sBatchServer);
	param.sVolLabel = m_sEditVolFile;
	param.bLabel = m_iRadioLabel == 0 ? TRUE : FALSE;
	param.nVolCount = atoi(m_sEditVolSu);

	// ���͔}�̃N���X�𐶐�
	switch (param.nBaitaiShubetsu) {
	case DEVICE_FPD:
		pInputDevice = new CInputFPD;
		break;
	case DEVICE_MOD:
		pInputDevice = new CInputMOD;
		break;
	case DEVICE_CMT:
		pInputDevice = new CInputCMT;
		break;
	case DEVICE_DAT:
		pInputDevice = new CInputDAT;
		break;
	default:
		return;
	}

	// �J�n���O��������
	WriteLogFile(&param, CreatePathName(g_sLogDir, "nyuryokustart"), NULL);

	// �}�̓��͏���
	nRetCode = InputDevice(&param, pInputDevice);
	switch (nRetCode) {
	case RC_NORMAL:			// ����I��
		ExecuteFileCopyPrg(&param);
		pStatus = "0";
		break;
	case RC_VOLUMENAME:		// �{�����[�����x�����Ⴄ
		DispMessage(IDS_MSG_DIFF_VOLLABEL, MB_OK, TRUE);
		pStatus = "7";
		break;
	case RC_IOERROR:		// I/O �G���[
		pInputDevice->GetError(nErrorCode, sErrorMsg);
		if (nErrorCode == 0)
			DispMessage(IDS_MSG_COPY_ERROR, MB_OK, TRUE, param.sShubetsuCode);
		else
			DispMessage(IDS_MSG_COPY_ERROR2, MB_OK, TRUE, param.sShubetsuCode, -nErrorCode, sErrorMsg);
		pStatus = "8";
		break;
	default:
		pStatus = "7";
		break;
	}

	// �I�����O��������
	WriteLogFile(&param, CreatePathName(g_sLogDir, "nyuryokuend"), pStatus);

	// ���͔}�̃N���X�̔j��
	delete pInputDevice;
}

//
//	�@�\	�F	�}�̓��͏���
//
//	����	�F	pParam - ���̓p�����[�^
//				pInputDevice - ���͔}�̃N���X
//
//	���A�l	�F	�������ʃR�[�h
//
//	�@�\�����F	�}�̂���t�@�C������͂���B
//
//	���l	�F	����
//
int CVjb30040Dlg::InputDevice(Param *pParam, CInputDevice *pInputDevice)
{
	int nRetCode;

	pInputDevice->SetUpdateWindow(this);

	// �m�F���b�Z�[�W�\��
	if ((nRetCode = pInputDevice->KakuninMessage(pParam)) != RC_NORMAL)
		return nRetCode;

	// �{�����[�����x���`�F�b�N
	if ((nRetCode = pInputDevice->CheckVolumeName(pParam)) != RC_NORMAL)
		return nRetCode;

	// �}�̂������
	if ((nRetCode = pInputDevice->InputFiles(pParam)) != RC_NORMAL)
		return nRetCode;

	return RC_NORMAL;
}

//
//	�@�\	�F	�t�@�C���R�s�[�v���O�����N������
//
//	����	�F	pParam - ���̓p�����[�^
//
//	���A�l	�F	����
//
//	�@�\�����F	�t�@�C���R�s�[�v���O����(VJB30050.EXE)���N������B
//
//	���l	�F	����
//
void CVjb30040Dlg::ExecuteFileCopyPrg(Param *pParam)
{
	CString sCmdLine;
	CString sVolLabel;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	// �{�����[�����x�����擾
	switch (pParam->nBaitaiShubetsu) {
	case DEVICE_CMT:
		if (pParam->bLabel)
			sVolLabel = pParam->sVolLabel.SpanExcluding(",");
		else
			sVolLabel = "000000";
		break;
	case DEVICE_DAT:
		sVolLabel = "000000";
		break;
	default:
		sVolLabel = pParam->sVolLabel;
		break;
	}

	// �R�}���h���C���𐶐�
#if _DEBUG
	sCmdLine.Format("%s %s %s %s %s", "vjb30050\\debug\\vjb30050.exe",
			(LPCTSTR)pParam->sShubetsuCode,
			(LPCTSTR)pParam->sBatchServer,
			(LPCTSTR)pParam->sShikibetsuCode,
			(LPCTSTR)sVolLabel);
#else
	sCmdLine.Format("%s %s %s %s %s", "vjb30050.exe",
			(LPCTSTR)pParam->sShubetsuCode,
			(LPCTSTR)pParam->sBatchServer,
			(LPCTSTR)pParam->sShikibetsuCode,
			(LPCTSTR)sVolLabel);
#endif
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hProcess);
	CloseHandle(pi.hThread);
}

//
//	�@�\	�F	���͔}�́E�o�͐�o�b�`�T�[�o���E���W�I�{�^���̃`�F�b�N����
//
//	����	�F	����
//
//	���A�l	�F		bool   TRUE : �`�F�b�NOK
//			�F		       FALSE : �`�F�b�NNG
//
//	�@�\�����F	��L�̃I�u�W�F�N�g�̏�Ԃ��`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckDeviceInfo()
{	
	int nIndex, nCheckLabelAri, nCheckLabelNashi;

	nIndex = m_COMBO_DEVICE_NAME.GetCurSel();
	if (nIndex == CB_ERR)
		return false;

	if (nIndex == DEVICE_FPD || nIndex == DEVICE_MOD) {
		
		if (CheckNyuryokuData(&m_EDIT_VOL_FILE) == false) {
			
			// ���b�Z�[�W�\������
			DispMessage(IDS_MSG_304, MB_OK, FALSE);

			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	else if (nIndex == DEVICE_CMT) {
		if ((nCheckLabelAri=m_RADIO_LABEL_ARI.GetCheck()) == 0) {
			if ((nCheckLabelNashi = m_RADIO_LABEL_NASHI.GetCheck()) == 0) {
				// ���b�Z�[�W�\������
				DispMessage(IDS_MSG_305, MB_OK, FALSE);
				
				return false;
			}
			else {
				if (CheckNyuryokuData(&m_EDIT_VOL_FILE) == false) {
					// ���b�Z�[�W�\������
					DispMessage(IDS_MSG_306, MB_OK, FALSE);
					
					m_EDIT_VOL_FILE.SetFocus();
					return false;
				}
			
				if (CheckNyuryokuData(&m_EDIT_VOL_SU) == false) {
				
					// ���b�Z�[�W�\������
					DispMessage(IDS_MSG_307, MB_OK, FALSE);
										
					m_EDIT_VOL_FILE.SetFocus();
					return false;
				}
			}
		}
		else {
			if (CheckNyuryokuData(&m_EDIT_VOL_FILE) == false) {
				// ���b�Z�[�W�\������
				DispMessage(IDS_MSG_304, MB_OK, FALSE);
				
				m_EDIT_VOL_FILE.SetFocus();
				return false;
			}
		}
	}
	return true;
}

//
//	�@�\	�F	�G�f�B�b�g�l��������
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�e�G�f�B�b�g�ɋ󕶎�����Z�b�g����B
//
//	���l	�F	����
//
void CVjb30040Dlg::DeleteEditInfo(int p_iCode) 
{
	// �e�L�X�g�f�[�^������
	switch (p_iCode) {
	case ELSE_STATUS:
		m_EDIT_SHIKIBETSU_CODE.SetWindowText("");
		m_EDIT_VOL_FILE.SetWindowText("");
		m_EDIT_VOL_SU.SetWindowText("");

	case LABEL_ARINASHI_CHECK_STATUS:
		m_EDIT_VOL_FILE.SetWindowText("");
		m_EDIT_VOL_SU.SetWindowText("");
	}
}

//
//	�@�\	�F	���W�I�{�^�������ݒ菈��
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	���W�I�{�^���𖢃`�F�b�N�ɐݒ肷��B
//
//	���l	�F	����
//
void CVjb30040Dlg::SetInitRadio() 
{
	// ���W�I�{�^�����`�F�b�N�����ɐݒ�
	m_RADIO_LABEL_ARI.SetCheck(0);
	m_RADIO_LABEL_NASHI.SetCheck(0);	
}

//
//	�@�\	�F	�e�f�[�^���͒l�擾�E�`�F�b�N����
//
//	����	�F	p_uObjectID - �I�u�W�F�N�g�h�c
//
//	���A�l	�F	bool   TRUE : �`�F�b�NOK
//			�F		   FALSE : �`�F�b�NNG
//
//	�@�\�����F	�e�f�[�^���͒l�擾���A�󕶎���̏ꍇ�A���A�l��FALSE���Z�b�g����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckNyuryokuData(CWnd *p_Wnd)
{
	CString csTemp;

	p_Wnd -> GetWindowText(csTemp);

	if (csTemp == "") {
		return false;
	}

	return true;
}

//
//	�@�\	�F	�}�̎��ʃR�[�h���͒l�`�F�b�N����
//
//	����	�F	����
//
//	���A�l	�F	bool   TRUE : �`�F�b�NOK
//			�F		   FALSE : �`�F�b�NNG
//
//	�@�\�����F	�}�̎��ʃR�[�h���͒l���`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckDeviceCodeData()
{
	CString csShikibetsuCode, csSpanIncludeData;
	int iLen;

	// �����`�F�b�N
	if (m_EDIT_SHIKIBETSU_CODE.GetWindowTextLength() !=6) {
		// ���b�Z�[�W�\������
		DispMessage(IDS_MSG_308, MB_OK, FALSE);
		
		m_EDIT_SHIKIBETSU_CODE.SetFocus();	
		return false;
	}

	// �}�̎��ʃR�[�h���擾
	m_EDIT_SHIKIBETSU_CODE.GetWindowText(csShikibetsuCode);
	
	// ���͕�����`�F�b�N
	csSpanIncludeData=csShikibetsuCode.SpanIncluding(DEVICE_SHIKIBETSU_CODE);
	
	// �擾����������̌������`�F�b�N
	if (iLen = csSpanIncludeData.GetLength() != 6) {
		// ���b�Z�[�W�\������
		DispMessage(IDS_MSG_309, MB_OK, FALSE);
		
		m_EDIT_SHIKIBETSU_CODE.SetFocus();	
		return false;
	}

	return true;
}


//
//	�@�\	�F	Vol���x�����E�t�@�C�������R�[�h���EVol�{�����͒l�`�F�b�N����
//
//	����	�F	����
//
//	���A�l	�F	bool   TRUE : �`�F�b�NOK
//			�F		   FALSE : �`�F�b�NNG
//
//	�@�\�����F	�e���͒l���`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckVolFileVolsuData()
{
	int nIndex, nCheck, nKanmaLen;
	CString csVolLabelData;

	nIndex = m_COMBO_DEVICE_NAME.GetCurSel();
	
	// Vol���x�����f�[�^���擾
	m_EDIT_VOL_FILE.GetWindowText(csVolLabelData);

	// FPD�AMO�ADAT�̏ꍇ
	if (nIndex == DEVICE_FPD || nIndex == DEVICE_MOD) {
		// ","���������������B
		nKanmaLen = csVolLabelData.Find(DEFINE_COMMA);		

		// �J���}������̑��݃`�F�b�N
		if (nKanmaLen > 0) {
				DispMessage(IDS_MSG_310, MB_OK, FALSE);
				m_EDIT_VOL_FILE.SetFocus();
				return false;
		}
		
		// ���͒l"SpanInclude"�`�F�b�N����
		if (CheckVolNyuryokuData(csVolLabelData) == false) {
			DispMessage(IDS_MSG_310, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}	
	// CMT�̏ꍇ
	else if (nIndex == DEVICE_CMT) {
		// ���x�����肪�`�F�b�N����Ă���ꍇ
		if ((nCheck=m_RADIO_LABEL_ARI.GetCheck()) == 1) {
			
			// Vol���x�������͒l�Ó����`�F�b�N
			if (CheckLabelAriVolData(csVolLabelData) == false) {
				return false;
			}
		}
		else {
			// �t�@�C����/���R�[�h�����͒l�Ó����`�F�b�N
			if (CheckLabelNashiFileNameRecord() == false) {
				return false;
			}
			
			// Vol�{�����͒l�Ó����`�F�b�N
			if (CheckLabelNashiVolSu() == false) {
				return false;
			}
		}
	}
	return true;
}

//
//	�@�\	�F	Vol���x�������͒l�`�F�b�N����
//
//	����	�F	p_csVolLabelData - Vol���x�����͒l
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					   false - �`�F�b�NNG
//
//	�@�\�����F	Vol���x�����̓��͕�����̑Ó����E�������`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckVolNyuryokuData(CString &p_csVolLabelData)
{
	CString csCheckData;
	
	// �p�����[�^Vol���x�����̌����`�F�b�N
	if (p_csVolLabelData.GetLength() != 6) {
		return false;
	}

	// Vol���x�������͒l�A�`�F�b�N
	csCheckData = p_csVolLabelData.SpanIncluding(DEVICE_VOL_NAME);

	// �`�F�b�N��̒l�̌������`�F�b�N
	if ((csCheckData.GetLength()) != 6) {
		return false;
	}
	
	return true;	
}

//
//	�@�\	�F	Vol���x�������͒l�Ó����`�F�b�N
//
//	����	�F	&p_csVolLabelData - Vol���x�����f�[�^
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					 : false - �`�F�b�NNG	
//
//	�@�\�����F	���͔}��CMT�A���x������`�F�b�N�AVol���x�����̓��̓f�[�^�̑Ó������`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckLabelAriVolData(CString &p_csVolLabelData)
{
	int nKanmaLen;

	// ","���������������B
	nKanmaLen=p_csVolLabelData.Find(DEFINE_COMMA);

	// �J���}�������񂪊܂܂�Ȃ��ꍇ
	if (nKanmaLen < 0) {
		// ���͒l�Ó����`�F�b�N����
		if (CheckVolNyuryokuData(p_csVolLabelData) == false) {
			DispMessage(IDS_MSG_310, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	// �J���}��������Ɋ܂܂��ꍇ
	else {
		if (CheckLabelAriNashiKanmaData(p_csVolLabelData, CMT_LABEL_ARI_KANMA) == false) {
			DispMessage(IDS_MSG_310, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	return true;
}


//
//	�@�\	�F	Vol�{�����͒l�Ó����`�F�b�N
//
//	����	�F	����
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					 : false - �`�F�b�NNG	
//
//	�@�\�����F	Vol�{���̓��͒l�����l���ǂ����`�F�b�N����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckLabelNashiVolSu()
{
	CString csVolSu, csTemp, csCheckData;
	int iLength, iLoopIndex;

	// Vol�{���f�[�^���擾
	m_EDIT_VOL_SU.GetWindowText(csVolSu);

	// �����擾
	iLength = csVolSu.GetLength();

	// ������̌��������[�v����B
	for (iLoopIndex = 0; iLoopIndex <= (iLength - 1); iLoopIndex++) {
		// �ꕶ�����擾����B
		csTemp = csVolSu.Mid(iLoopIndex, 1);
		
		// �擪������0�̏ꍇ�A�G���[
		if (iLoopIndex == 0) {
			if (csTemp == "0") {
				DispMessage(IDS_MSG_312, MB_OK, FALSE);
				m_EDIT_VOL_SU.SetFocus();
				return false;
			}
		}

		// Vol���x�������͒l�A�`�F�b�N
		csCheckData = csTemp.SpanIncluding(DEVICE_VOL_SU);

		if (csCheckData.GetLength() != 1) {
			DispMessage(IDS_MSG_312, MB_OK, FALSE);
			m_EDIT_VOL_SU.SetFocus();
			return false;
		}
	}
	
	return true;
}

//
//	�@�\	�F	�t�@�C����/���R�[�h�����͒l�Ó����`�F�b�N
//
//	����	�F	����
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					 : false - �`�F�b�NNG	
//
//	�@�\�����F	�t�@�C����/���R�[�h���̓��͒l�̑Ó����`�F�b�N���s���B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckLabelNashiFileNameRecord()
{
	CString csFileNameRecord;
	int nKanmaLen;

	// �t�@�C����/���R�[�h���f�[�^���擾
	m_EDIT_VOL_FILE.GetWindowText(csFileNameRecord);
	
	// ","���������������B
	nKanmaLen = csFileNameRecord.Find(DEFINE_COMMA);

	// �J���}�������񂪊܂܂�Ȃ��ꍇ
	if (nKanmaLen < 0) {
		// ���͒l�Ó����`�F�b�N����
		if (CheckSlashData(csFileNameRecord) == false) {
			DispMessage(IDS_MSG_311, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	else {
		// ���͒l�Ó����`�F�b�N����
		if (CheckLabelAriNashiKanmaData(csFileNameRecord, CMT_LABEL_NASHI_KANMA) == false)	{
			DispMessage(IDS_MSG_311, MB_OK, FALSE);
			m_EDIT_VOL_FILE.SetFocus();
			return false;
		}
	}
	return true;
}

//
//	�@�\	�F	"/"�����񌟍�����
//
//	����	�F	p_csFileNameRecord - �t�@�C����/���R�[�h�����͒l
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					 : false - �`�F�b�NNG	
//
//	�@�\�����F	�p�����[�^�̒l���"/"�����������
//              "/"��������̐擪�A�y�сA�����̏ꍇ�A"/"�����񂪊܂܂�Ȃ��ꍇ�Afalse��Ԃ�
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckSlashData(CString &p_csFileNameRecord)
{
	int nFileLen, nIndex,nCount;
	CString csCheckData;

	// �t�@�C����/���R�[�h���̌������擾
	nFileLen = p_csFileNameRecord.GetLength();
	
	// �J�E���^��0���Z�b�g
	nCount = 0;

	// �擪�A�����A"/"������`�F�b�N����
	if (CheckStartEndSlashData(p_csFileNameRecord, nFileLen) == false) {
		return false;
	}
	
	// ������̌��������[�v����B
	for (nIndex = 0; nIndex <= nFileLen - 1 ; nIndex++) {
		// 1�o�C�g���������擾
		csCheckData = p_csFileNameRecord.Mid(nIndex, 1);

		// ������"/"�̏ꍇ
		if (csCheckData == DEFINE_SLASH) {
			// �J�E���^�Ƀv���X�P
			nCount = nCount + 1;
		}
	}
	
	// �J�E���^��1�ȊO�̏ꍇ
	if (nCount != 2) {
		return false;
	}
	
	return true;
}

//
//	�@�\	�F	�}�̎��(CMT)�BVOL���x�����A�t�@�C����/���R�[�h���̓��͒l(�J���}����)���`�F�b�N����B
//
//	����	�F	p_csFileNameRecord - �t�@�C����/���R�[�h�����͒l
//				n_pFlg - ��������t���O
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					 : false - �`�F�b�NNG	
//
//	�@�\�����F	��������t���O��0�̏ꍇ�AVol���x�����̓��͒l�`�F�b�N�����s���A
//				1 �̏ꍇ�A�t�@�C����/���R�[�h���̓��͒l�`�F�b�N�����s����
//				�`�F�b�N������NG�̏ꍇ�A���A�lFALSE���Z�b�g����B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckLabelAriNashiKanmaData(CString &p_csFileNameRecord, int p_nFlg)
{
	int nFileLen, nKanmaLen ;
	CString csTemp1, csFilenameRecord ;
	
	// �p�����[�^�̌������擾
	nFileLen = p_csFileNameRecord.GetLength();
	
	// CMT���x�������̏ꍇ
	if (p_nFlg == CMT_LABEL_NASHI_KANMA) {
		// �擪�A�����A"/"������`�F�b�N����
		if (CheckStartEndSlashData(p_csFileNameRecord, nFileLen) == false) {
			return false;
		}
	}

	// �ꎞ�i�[�p�Ƀp�����[�^�l���Z�b�g
	csTemp1 = p_csFileNameRecord;

	while (true) {
		// �ꎞ�i�[�f�[�^�̃J���}�܂ł̌�����-1�̏ꍇ
		if ((nKanmaLen = csTemp1.Find(DEFINE_COMMA)) == -1) {
			if (p_nFlg == CMT_LABEL_NASHI_KANMA) {
				// �t�@�C����/���R�[�h�����͒l�Ó����`�F�b�N����
				if (CheckSlashData(csTemp1) == false) {
					return false;
				}
				else {
					break;
				}
			}
			else {
				// Vol���x�����͒l�Ó����`�F�b�N����
				if (CheckVolNyuryokuData(csTemp1) == false) {
					return false;
				}
				else {
					break;
				}
			}
		}
		
		// �J���}�܂ł̕�������擾
		csFilenameRecord = csTemp1.Mid(0, nKanmaLen);	

		if ((nKanmaLen = csTemp1.Find(DEFINE_COMMA)) == -1) {
			
			if (p_nFlg == CMT_LABEL_NASHI_KANMA) {
				// �t�@�C����/���R�[�h�����͒l�Ó����`�F�b�N����
				if (CheckSlashData(csFilenameRecord) == false) {
					return false;
				}
			}
			else {
				// VOL���x�������͒l�Ó����`�F�b�N����
				if (CheckVolNyuryokuData(csFilenameRecord) == false) {
					return false;
				}
			}
		}

		// �ꎞ�i�[�p�ɃJ���}�ȍ~�̕�������Z�b�g
		csTemp1 = csTemp1.Mid(nKanmaLen + 1, nFileLen - nKanmaLen + 1);
	}

	return true;
}

//
//	�@�\	�F	�}��(CMT)�A���x�������`�F�b�N���A�t�@�C����/���R�[�h�����͒l��
//				�擪�A�y�сA����������"/"�����񂩂ǂ����`�F�b�N����B
//
//	����	�F	p_csNyuryokuData - �t�@�C����/���R�[�h�����͒l
//				p_nFileLen - �t�@�C����/���R�[�h�����͒l����
//
//	���A�l	�F	bool : true - �`�F�b�NOK
//					 : false - �`�F�b�NNG	
//
//	�@�\�����F	�p�����[�^�l�̐擪�A�y�сA����������"/"������̏ꍇ�A���A�l��FALSE��Ԃ��B
//
//	���l	�F	����
//
bool CVjb30040Dlg::CheckStartEndSlashData(CString &p_csNyuryokuData,int &p_nFileLen)
{
	CString csTemp ;
	
	// �擪�������`�F�b�N
	if ((csTemp = p_csNyuryokuData.Mid(0,1)) == DEFINE_SLASH) {
		return false;
	}
	
	// �����������`�F�b�N
	if ((csTemp = p_csNyuryokuData.Mid(p_nFileLen -1, 1)) == DEFINE_SLASH) {
		return false;
	}
	
	return true;
}

//
//	�@�\	�F	���O�t�@�C���������ݏ���
//
//	����	�F	pParam - ���̓p�����[�^
//				pLogFile - ���O�t�@�C����
//				pStatus - �X�e�[�^�X�R�[�h
//
//	���A�l	�F	����
//
//	�@�\�����F	�w�肳�ꂽ���O�t�@�C���Ƀ��O���������ށB
//
//	���l	�F	����
//
void CVjb30040Dlg::WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus)
{
	CStdioFile file;

	// ���O�t�@�C�����I�[�v��
	if (file.Open(pLogFile, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareDenyWrite)) {
		SYSTEMTIME st;
		CString str;

		// ���ݎ������擾
		GetLocalTime(&st);

		// ���O�̃t�H�[�}�b�g
		str.Format("%04d/%02d/%02d %02d:%02d:%02d,%s,%s,%s,%s%s\n",
				st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute, st.wSecond,
				pParam->sShubetsuCode,
				pParam->sBatchServer,
				pParam->sShikibetsuCode,
				pParam->sVolLabel,
				pStatus == NULL ? "" : (CString)"," + pStatus);

		// �ŏI�ʒu�ɒǉ���������
		file.Seek(0, CFile::end);
		file.WriteString(str);
		file.Close();
	}
}
