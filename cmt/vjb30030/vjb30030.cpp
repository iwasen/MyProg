// vjb30030.cpp : �A�v���P�[�V�����p�N���X�̒�`���s���܂��B
//

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputDevice.h"
#include "OutputFPD.h"
#include "OutputMOD.h"
#include "OutputCMT.h"
#include "OutputDAT.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DEVICE_FPD		"FPD"
#define DEVICE_MOD		"MOD"
#define DEVICE_CMT		"CMT"
#define DEVICE_DAT		"DAT"

#define STATUS_READY		'N'
#define STATUS_EXECUTE		'1'
#define STATUS_NONCHECK		'2'
#define STATUS_EXECUTING	'3'
#define STATUS_IOERROR		'7'
#define STATUS_EXECUTEERROR	'8'

#define QF_LEN_DATE						14
#define QF_LEN_BAITAI_SHUBETSU_CODE		3
#define QF_LEN_BAITAI_SHIKIBETSU_CODE	6
#define QF_LEN_STATUS					1
#define QF_LEN_VOLUMELABEL				6

#define QF_POS_DATE						0
#define QF_POS_BAITAI_SHUBETSU_CODE		14
#define QF_POS_BAITAI_SHIKIBETSU_CODE	17
#define QF_POS_STATUS					23
#define QF_POS_VOLUMELABEL				24

// ���b�Z�[�W
#define	MSG_KAKUNIN			"�m�F���b�Z�[�W"
#define MSG_ERROR			"�G���[���b�Z�[�W"

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App

BEGIN_MESSAGE_MAP(CVjb30030App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30030App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App �N���X�̍\�z

CVjb30030App::CVjb30030App()
{
	m_nRetCode = RC_NORMAL;
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CVjb30030App �I�u�W�F�N�g

CVjb30030App theApp;

CString g_sPathFPD;
CString g_sPathMOD;
CString g_sOutputDir;
CString g_sLogDir;

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App �N���X�̏�����

//
//	�@�\	�F	�C���X�^���X�J�n����
//
//	����	�F	����
//
//	���A�l	�F	FALSE - �v���O�������I������
//
//	�@�\�����F	�v���O�����{�̂̏����B
//
//	���l	�F	�v���O�����J�n���Ƀt���[�����[�N���Ă΂��B
//
BOOL CVjb30030App::InitInstance()
{
	m_nRetCode = Main(__argc, __argv);

	return FALSE;
}

//
//	�@�\	�F	�C���X�^���X�I������
//
//	����	�F	����
//
//	���A�l	�F	�v���O�����I���R�[�h
//
//	�@�\�����F	�v���O�����I���R�[�h��Ԃ��B
//
//	���l	�F	�v���O�����I�����Ƀt���[�����[�N���Ă΂��B
//
int CVjb30030App::ExitInstance() 
{
	return m_nRetCode;
}

//
//	�@�\	�F	�O���}�̏o�̓��C������
//
//	����	�F	argc - �p�����[�^��
//				argv - �p�����[�^���e
//
//	���A�l	�F	�v���O�����I���R�[�h
//
//	�@�\�����F	�O���}�̏o�͂̃��C���������s���B
//
//	���l	�F	�v���O�����I�����Ƀt���[�����[�N���Ă΂��B
//
int CVjb30030App::Main(int argc, char *argv[])
{
	Queue *pQueue;
	COutputDevice *pOutputDevice;
	int nErrorCode;
	CString sErrorMsg;
	int nRetCode = RC_NORMAL;
	char cStatus;

	// �p�����[�^���`�F�b�N
	if (argc != 2)
		return RC_PARAMETER;

	// ini�t�@�C���ǂݍ���
	ReadIniFile();

	// �L���[�f�[�^�擾
	pQueue = GetQueueData(argv[1]);

	// ���s�܂��̓��x���m���`�F�b�N���s�łȂ���΃G���[
	if (!(pQueue->cStatus == STATUS_EXECUTE || pQueue->cStatus == STATUS_NONCHECK))
		return RC_STATUS;

	// �J�n���O��������
	WriteLogFile(pQueue, CreatePathName(g_sLogDir, "syuturyokustart"));

	// ��ԃR�[�h�����s���ɐݒ�
	cStatus = pQueue->cStatus;
	ChangeStatus(pQueue, STATUS_EXECUTING);

	// �o�̓f�o�C�X�ʂ̃N���X�𐶐�
	if (pQueue->sShubetsuCode == DEVICE_FPD)
		pOutputDevice = new COutputFPD;
	else if (pQueue->sShubetsuCode == DEVICE_MOD)
		pOutputDevice = new COutputMOD;
	else if (pQueue->sShubetsuCode == DEVICE_CMT)
		pOutputDevice = new COutputCMT;
	else if (pQueue->sShubetsuCode == DEVICE_DAT)
		pOutputDevice = new COutputDAT;
	else
		return RC_PARAMETER;

	// �R�s�[���f�B���N�g���^�t�@�C�����݃`�F�b�N
	nRetCode = pOutputDevice->CheckDstFile(pQueue);

	// �{�����[�����`�F�b�N
	if (nRetCode == RC_NORMAL && cStatus != STATUS_NONCHECK)
		nRetCode = pOutputDevice->CheckVolumeName(pQueue);

	// �t�@�C���o��
	if (nRetCode == RC_NORMAL)
		nRetCode = pOutputDevice->OutputFiles(pQueue);

	// �㏈��
	switch (nRetCode) {
	case RC_NORMAL:
		pQueue->cStatus = '0';
		DeleteSrcFile(pQueue);
		NotifyChangeStatus();
		DispMessage(IDS_MSG_NORMAL_END, MB_OK, TRUE,
				(LPCTSTR)pQueue->sShubetsuCode, pQueue->sShikibetsuCode);
		break;
	case RC_VOLUMENAME:
		ChangeStatus(pQueue, STATUS_EXECUTEERROR);
		DispMessage(IDS_MSG_VOLLABEL_ERROR, MB_OK, TRUE, pQueue->sShubetsuCode);
		break;
	case RC_CANCEL:
		ChangeStatus(pQueue, STATUS_READY);
		break;
	case RC_MULTIFILEVOL:
		ChangeStatus(pQueue, STATUS_IOERROR);
		DispMessage(IDS_MSG_MULTIFILEVOL, MB_OK, TRUE);
		break;
	case RC_NODIRECTORY:
		ChangeStatus(pQueue, STATUS_IOERROR);
		DispMessage(IDS_MSG_NODIRECTORY, MB_OK, TRUE, pQueue->sShubetsuCode, pQueue->sShikibetsuCode);
		break;
	case RC_NOFILE:
		ChangeStatus(pQueue, STATUS_IOERROR);
		DispMessage(IDS_MSG_NOFILE, MB_OK, TRUE, pQueue->sShubetsuCode, pQueue->sShikibetsuCode);
		break;
	case RC_ILLEGALFILENAME:
		ChangeStatus(pQueue, STATUS_IOERROR);
		break;
	default:
		ChangeStatus(pQueue, STATUS_IOERROR);
		pOutputDevice->GetError(nErrorCode, sErrorMsg);
		if (nErrorCode == 0)
			DispMessage(IDS_MSG_IO_ERROR, MB_OK, TRUE, pQueue->sShubetsuCode);
		else
			DispMessage(IDS_MSG_IO_ERROR2, MB_OK, TRUE, pQueue->sShubetsuCode, -nErrorCode, sErrorMsg);
		break;
	}

	// �}�̏o�̓N���X�J��
	delete pOutputDevice;

	// �I�����O��������
	WriteLogFile(pQueue, CreatePathName(g_sLogDir, "syuturyokuend"));

	// �L���[���J��
	delete pQueue;

	return nRetCode;
}

//
//	�@�\	�F	ini�t�@�C���ǂݍ��ݏ���
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	ini�t�@�C�����K�v�ȏ���ǂݍ��ށB
//
//	���l	�F	����
//
void CVjb30030App::ReadIniFile()
{
	// INI�t�@�C�����L���[�t�@�C���f�B���N�g���擾
	m_sQueueDir = GetIniData("Queue", "Dir", "");

	// FPD�̃p�X�����擾
	g_sPathFPD = GetIniData("Device", "FPD", "A:\\");

	// MOD�̃p�X�����擾
	g_sPathMOD = GetIniData("Device", "MOD", "M:\\");

	// �o�̓t�@�C���f�B���N�g�����擾
	g_sOutputDir = GetIniData("OutputFile", "Dir", "Z:\\");

	// ���O�t�@�C���f�B���N�g�����擾
	g_sLogDir = GetIniData("LogFile", "Dir", "");
}

//
//	�@�\	�F	�L���[���擾����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[�t�@�C������L���[�����쐬����B
//
//	���l	�F	����
//
Queue *CVjb30030App::GetQueueData(CString sFileName)
{
	Queue *pQueue = new Queue;

	// �L���[�t�@�C��������e�p�����[�^�擾
	pQueue->sFileName = sFileName;
	pQueue->sShubetsuCode = sFileName.Mid(QF_POS_BAITAI_SHUBETSU_CODE, QF_LEN_BAITAI_SHUBETSU_CODE);
	pQueue->sShikibetsuCode = sFileName.Mid(QF_POS_BAITAI_SHIKIBETSU_CODE, QF_LEN_BAITAI_SHIKIBETSU_CODE);
	pQueue->sVolLabel = sFileName.Mid(QF_POS_VOLUMELABEL);
	pQueue->sCreateDate = sFileName.Left(QF_LEN_DATE);
	pQueue->cStatus = sFileName[QF_POS_STATUS];

	return pQueue;
}

//
//	�@�\	�F�@��ԃR�[�h�ύX����
//
//	����	�F	pQueue - �L���[���
//				status - ��ԃR�[�h
//
//	���A�l	�F	����
//
//	�@�\�����F	�L���[�t�@�C���̏�ԃR�[�h��ύX����B
//
//	���l	�F	����
//
void CVjb30030App::ChangeStatus(Queue *pQueue, char status)
{
	CString sOldFileName, sNewFileName;

	// �L���[�t�@�C���̃p�X�����擾
	sOldFileName = CreatePathName(m_sQueueDir, pQueue->sFileName);

	// ��ԃR�[�h��ύX
	pQueue->sFileName.SetAt(QF_POS_STATUS, status);
	pQueue->cStatus = status;

	// �ύX��̃L���[�t�@�C���̃p�X�����擾
	sNewFileName = CreatePathName(m_sQueueDir, pQueue->sFileName);

	// �t�@�C�����ύX
	MoveFile(sOldFileName, sNewFileName);

	// �O���}�̏o�͊Ǘ��v���O�����ɒʒm
	NotifyChangeStatus();
}

//
//	�@�\	�F�@�o�͌��t�@�C���폜����
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	����
//
//	�@�\�����F	�o�͂̏I������t�@�C�����폜����B
//
//	���l	�F	����
//
void CVjb30030App::DeleteSrcFile(Queue *pQueue)
{
	// �L���[�t�@�C���̍폜
	DeleteFile(CreatePathName(m_sQueueDir, pQueue->sFileName));

	// �R�s�[���f�B���N�g���A�t�@�C���폜
	DeleteDirectory(CreatePathName(g_sOutputDir, pQueue->sShikibetsuCode + pQueue->sVolLabel.SpanExcluding(",")), TRUE);
}

//
//	�@�\	�F�@�L���[�t�@�C���ύX�ʒm����
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	�O���}�̊Ǘ��v���O�����ɃL���[�t�@�C���̕ύX��ʒm����B
//
//	���l	�F	����
//
void CVjb30030App::NotifyChangeStatus()
{
	CWnd *pWnd;

	pWnd = CWnd::FindWindow(NULL, "�}�̏o�͎w�����(vjb30020)");
	if (pWnd != NULL)
		pWnd->SendMessage(WM_TIMER, 1, 0);
}

//
//	�@�\	�F	���O�t�@�C���������ݏ���
//
//	����	�F	pParam - ���̓p�����[�^
//				pLogFile - ���O�t�@�C����
//
//	���A�l	�F	����
//
//	�@�\�����F	�w�肳�ꂽ���O�t�@�C���Ƀ��O���������ށB
//
//	���l	�F	����
//
void CVjb30030App::WriteLogFile(Queue *pQueue, LPCTSTR pLogFile)
{
	CStdioFile file;

	// ���O�t�@�C�����I�[�v��
	if (file.Open(pLogFile, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareDenyWrite)) {
		SYSTEMTIME st;
		CString str;

		// ���ݎ������擾
		GetLocalTime(&st);

		// ���O�̃t�H�[�}�b�g
		str.Format("%04d/%02d/%02d %02d:%02d:%02d,%s/%s/%s %s:%s:%s,%s,%s,%s,%c\n",
				st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute, st.wSecond,
				pQueue->sCreateDate.Mid(0, 4),
				pQueue->sCreateDate.Mid(4, 2),
				pQueue->sCreateDate.Mid(6, 2),
				pQueue->sCreateDate.Mid(8, 2),
				pQueue->sCreateDate.Mid(10, 2),
				pQueue->sCreateDate.Mid(12, 2),
				pQueue->sShubetsuCode,
				pQueue->sShikibetsuCode,
				pQueue->sVolLabel,
				pQueue->cStatus);

		// �ŏI�ʒu�ɒǉ���������
		file.Seek(0, CFile::end);
		file.WriteString(str);
		file.Close();
	}
}
