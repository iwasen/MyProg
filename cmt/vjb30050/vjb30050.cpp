// vjb30050.cpp : �A�v���P�[�V�����p�N���X�̒�`���s���܂��B
//

#include "stdafx.h"
#include "vjb30050.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DEVICE_FPD		"FPD"
#define DEVICE_MOD		"MOD"
#define DEVICE_CMT		"CMT"
#define DEVICE_DAT		"DAT"

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App

BEGIN_MESSAGE_MAP(CVjb30050App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30050App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App �N���X�̍\�z

CVjb30050App::CVjb30050App()
{
	// TODO: ���̈ʒu�ɍ\�z�p�̃R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CVjb30050App �I�u�W�F�N�g

CVjb30050App theApp;

CString g_sLogDir;
CString g_sInputDir;
CString g_sDatDir;
CString g_sBatchUNC;

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App �N���X�̏�����

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
BOOL CVjb30050App::InitInstance()
{
	m_nRetCode = Main(__argc, __argv);

	return FALSE;
}

//
//	�@�\	�F	�t�@�C���R�s�[���C������
//
//	����	�F	argc - �p�����[�^��
//				argv - �p�����[�^���e
//
//	���A�l	�F	�v���O�����I���R�[�h
//
//	�@�\�����F	�t�@�C���R�s�[�v���O�����̃��C���������s���B
//
//	���l	�F	�v���O�����I�����Ƀt���[�����[�N���Ă΂��B
//
int CVjb30050App::Main(int argc, char *argv[])
{
	Param param;
	CString sSrcDir;
	CString sDstDir;
	char *pStatus;

	// �p�����[�^���`�F�b�N
	if (argc != 5)
		return RC_PARAMETER;

	// �e�p�����[�^�擾
	param.sShubetsuCode = argv[1];
	param.sBatchServer = argv[2];
	param.sShikibetsuCode = argv[3];
	param.sVolLabel = argv[4];

	// ini�t�@�C���ǂݍ���
	ReadIniFile();

	// �R�s�[���p�X���쐬
	if (param.sShubetsuCode == DEVICE_DAT)
		sSrcDir = g_sDatDir;
	else
		sSrcDir = CreatePathName(g_sInputDir, param.sShikibetsuCode + param.sVolLabel);

	// �o�b�`�T�[�o�̃R�s�[��p�X��(UNC)�쐬
	sDstDir = CreatePathName("\\\\" + param.sBatchServer + "\\" + g_sBatchUNC, param.sShikibetsuCode + param.sVolLabel);

	// �J�n���O��������
	WriteLogFile(&param, CreatePathName(g_sLogDir, "filecopystart"), NULL);

	// �o�b�`�T�[�o�̃R�s�[��f�B���N�g���폜
	DeleteDirectory(sDstDir, FALSE);

	// �o�b�`�T�[�o�̃R�s�[��f�B���N�g���쐬
	CreateDirectory(sDstDir, NULL);

	// �o�b�`�T�[�o�Ƀt�@�C���R�s�[
	if (CopyDirectory(sSrcDir, sDstDir, NULL)) {
		DispMessage(IDS_MSG_NORMAL_END, MB_OK, TRUE, param.sShubetsuCode);
		pStatus = "0";
	} else {
		DispMessage(IDS_MSG_COPY_ERROR, MB_OK, TRUE, param.sShubetsuCode, param.sBatchServer);
		pStatus = "7";
	}

	// �R�s�[���f�B���N�g���̍폜
	DeleteDirectory(sSrcDir, param.sShubetsuCode != DEVICE_DAT);

	// �I�����O��������
	WriteLogFile(&param, CreatePathName(g_sLogDir, "filecopyend"), pStatus);

	return 0;
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
void CVjb30050App::ReadIniFile()
{
	// ���O�t�@�C���f�B���N�g�����擾
	g_sLogDir = GetIniData("LogFile", "Dir", "");

	// ���̓f�B���N�g�������擾
	g_sInputDir = GetIniData("InputFile", "InputDir", "dummy");

	// DAT�p���̓f�B���N�g�������擾
	g_sDatDir = GetIniData("InputFile", "DatDir", "dummy");

	// �o�b�`�T�[�oUNC���擾
	g_sBatchUNC = GetIniData("BatchServer", "UNC", "");
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
void CVjb30050App::WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus)
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
