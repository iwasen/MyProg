// vjb30010.cpp : �A�v���P�[�V�����p�N���X�̒�`���s���܂��B
//

#include "stdafx.h"
#include "vjb30010.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App

BEGIN_MESSAGE_MAP(CVjb30010App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30010App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App �N���X�̍\�z

CVjb30010App::CVjb30010App()
{
	m_nRetCode = RC_NORMAL;
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CVjb30010App �I�u�W�F�N�g

CVjb30010App theApp;

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App �N���X�̏�����

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
BOOL CVjb30010App::InitInstance()
{
	Param param;

	if ((m_nRetCode = GetParam(__argc, __argv, &param)) == 0)
		m_nRetCode = CreateQueueFile(&param);

	return FALSE;
}

//
//	�@�\	�F	�p�����[�^�擾����
//
//	����	�F	argc - �p�����[�^��
//				argv - �p�����[�^���e
//				pParam - �p�����[�^�i�[�G���A
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�R�}���h���C�����p�����[�^���擾����B
//
//	���l	�F	����
//
int CVjb30010App::GetParam(int argc, char* argv[], Param *pParam)
{
	char *p;

	// �p�����[�^���`�F�b�N
	if (argc != 2) {
		DispMessage("�p�����[�^�̐����Ⴂ�܂��B");
		return RC_PARAMNUM;
	}

	// �}�̎�ʃR�[�h�A�}�̎��ʃR�[�h�AVol���x�����擾
	if ((p = strtok(argv[1], ",")) == NULL) {
		DispMessage("�p�����[�^�Ɍ�肪����܂��B");
		return RC_PARAMVAL;
	}

	// �p�����[�^�̕������`�F�b�N
	if (strlen(p) != LEN_TOTAL) {
		DispMessage("�p�����[�^�̒������Ⴂ�܂��B");
		return RC_PARAMLEN;
	}

	// �}�̎�ʃR�[�h�A�}�̎��ʃR�[�h�AVol���x�����̕���
	strncpy(pParam->cShubetsuCode, p, LEN_SHUBETSU_CODE);
	pParam->cShubetsuCode[LEN_SHUBETSU_CODE] = '\0';
	strncpy(pParam->cShikibetsuCode, p + POS_SHIKIBETSU_CODE, LEN_SHIKIBETSU_CODE);
	pParam->cShikibetsuCode[LEN_SHIKIBETSU_CODE] = '\0';
	strncpy(pParam->cVolLabel[0], p + POS_VOLUME_LABEL, LEN_VOLUME_LABEL);
	pParam->cVolLabel[0][LEN_VOLUME_LABEL] = '\0';

	// �ǉ���Vol���x�����擾
	pParam->nVolLabel = 1;
	while ((p = strtok(NULL, ",")) != NULL) {
		// Vol���x�����`�F�b�N
		if (pParam->nVolLabel >= MAX_VOLUME_LABEL) {
			DispMessage("Vol���x�����̐����������܂��B");
			return RC_VOLNUM;
		}

		// Vol���x�����`�F�b�N
		if (strlen(p) != LEN_VOLUME_LABEL) {
			DispMessage("Vol���x�����̒������Ⴂ�܂��B");
			return RC_VOLLEN;
		}

		// Vol���x���擾
		strcpy(pParam->cVolLabel[pParam->nVolLabel++], p);
	}

	return RC_NORMAL;
}

//
//	�@�\	�F	�L���[�t�@�C���쐬����
//
//	����	�F	pParam - �p�����[�^�i�[�G���A
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�p�����[�^���L���[�t�@�C�����쐬����B
//
//	���l	�F	����
//
int CVjb30010App::CreateQueueFile(Param *pParam)
{
	char iniFile[256];
	char queueDir[256];
	char queueFile[256];
	SYSTEMTIME st;
	int i;
	HANDLE hFile;

	// ���ϐ����INI�t�@�C�����擾
	if (GetEnvironmentVariable("VJBINIDIR", iniFile, sizeof(iniFile)) == 0)
		strcpy(iniFile, INI_FILE);
	else
		strcat(iniFile, "VJB30010.INI");

	// INI�t�@�C�����L���[�t�@�C���f�B���N�g���擾
	GetPrivateProfileString("QUEUE", "Dir", "", queueDir, sizeof(queueDir), iniFile);

	// �V�X�e�����t�擾
	GetLocalTime(&st);

	// �L���[�t�@�C�����쐬�iVol���x���������j
	sprintf(queueFile, "%s\\%04d%02d%02d%02d%02d%02d%s%sN",
			queueDir,
			st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute, st.wSecond,
			pParam->cShubetsuCode, pParam->cShikibetsuCode);

	// Vol���x���t��
	for (i = 0; i < pParam->nVolLabel; i++) {
		if (i != 0)
			strcat(queueFile, ",");
		strcat(queueFile, pParam->cVolLabel[i]);
	}

	// �L���[�t�@�C���쐬
	hFile = CreateFile(
		queueFile,				// pointer to name of the file
		GENERIC_WRITE,			// access (read-write) mode
		FILE_SHARE_READ,		// share mode
		NULL,					// pointer to security attributes
		CREATE_ALWAYS,			// how to create
		FILE_ATTRIBUTE_NORMAL,	// file attributes
		NULL					// handle to file with attributes to copy
	);
	if (hFile == NULL) {
		DispMessage("�L���[�t�@�C�����쐬�ł��܂���B");
		return RC_QUEUEFILE;
	}
	CloseHandle(hFile);

	return RC_NORMAL;
}

//
//	�@�\	�F	���b�Z�[�W�\������
//
//	����	�F	msg - �\�����b�Z�[�W
//
//	���A�l	�F	����
//
//	�@�\�����F	���b�Z�[�W��\������B
//
//	���l	�F	�f�o�b�O���̂ݕ\��
//
void CVjb30010App::DispMessage(LPCTSTR msg)
{
#if _DEBUG
	AfxMessageBox(msg);
#endif
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
int CVjb30010App::ExitInstance() 
{
	return m_nRetCode;
}
