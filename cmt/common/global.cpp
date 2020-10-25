#include "stdafx.h"
#include <lm.h>

#define	IDS_PROGRAM_ID	1				// �v���O����ID���\�[�X�ԍ�
#define INI_DIR		"VJBINIDIR"			// INI�t�@�C���̃f�B���N�g�����ݒ肳�ꂽ���ϐ���
#define INI_FILE	"VJB30010.INI"		// INI�t�@�C����
#define INI_DEF_FILE	".\\baitai.ini"	// ���ϐ����ݒ肳��Ă��Ȃ��ꍇ��INI�t�@�C����

//
//	�@�\	�F	ini�t�@�C���ǂݍ��ݏ����i�����j
//
//	����	�F	pSection - �Z�N�V������
//			�F	pEntry - �G���g����
//			�F	pDefault - �f�t�H���g�f�[�^
//
//	���A�l	�F	�p�X��
//
//	�@�\�����F	ini�t�@�C������w�肳�ꂽ�����f�[�^�����o���B
//
//	���l	�F	����
//
CString GetIniData(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pDefault)
{
	char iniFile[256];
	char buf[256];

	// ���ϐ����INI�t�@�C�����擾
	if (GetEnvironmentVariable(INI_DIR, iniFile, sizeof(iniFile)) == 0)
		strcpy(iniFile, INI_DEF_FILE);
	else
		strcat(iniFile, INI_FILE);

	GetPrivateProfileString(pSection, pEntry, pDefault, buf, sizeof(buf), iniFile);
	return buf;
}

//
//	�@�\	�F	ini�t�@�C���ǂݍ��ݏ����i���l�j
//
//	����	�F	pSection - �Z�N�V������
//			�F	pEntry - �G���g����
//			�F	nDefault - �f�t�H���g�f�[�^
//
//	���A�l	�F	�p�X��
//
//	�@�\�����F	ini�t�@�C������w�肳�ꂽ���l�f�[�^�����o���B
//
//	���l	�F	����
//
int GetIniData(LPCTSTR pSection, LPCTSTR pEntry, int nDefault)
{
	char iniFile[256];

	// ���ϐ����INI�t�@�C�����擾
	if (GetEnvironmentVariable(INI_DIR, iniFile, sizeof(iniFile)) == 0)
		strcpy(iniFile, INI_DEF_FILE);
	else
		strcat(iniFile, INI_FILE);

	return GetPrivateProfileInt(pSection, pEntry, nDefault, iniFile);
}

//
//	�@�\	�F	�p�X���쐬����
//
//	����	�F	pDirName - �f�B���N�g����
//			�F	pFileName - �t�@�C����
//
//	���A�l	�F	�p�X��
//
//	�@�\�����F	�f�B���N�g�����ƃt�@�C��������p�X�����쐬����B
//
//	���l	�F	����
//
CString CreatePathName(LPCTSTR pDirName, LPCTSTR pFileName)
{
	CString sPathName;

	sPathName = pDirName;
	if (sPathName.Right(1) == "\\")
		return sPathName + pFileName;
	else
		return sPathName + "\\" + pFileName;
}

//
//	�@�\	�F	�f�B���N�g���폜����
//
//	����	�F	pDirName - �f�B���N�g����
//			�F	bDeleteTop - �ŏ�ʃf�B���N�g�����폜���邩�ǂ����̎w��
//
//	���A�l	�F	TRUE - ����
//				FALSE - �G���[
//
//	�@�\�����F	�w�肳�ꂽ�f�B���N�g�ȉ����T�u�f�B���N�g�����܂߂č폜����B
//
//	���l	�F	����
//
BOOL DeleteDirectory(LPCTSTR pDirName, BOOL bDeleteTop)
{
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	CString sPathName;

	hdir = FindFirstFile(CreatePathName(pDirName, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			sPathName = CreatePathName(pDirName, findBuf.cFileName);
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
					if (!DeleteDirectory(sPathName, TRUE)) {
						FindClose(hdir);
						return FALSE;
					}
				}
			} else {
				if (!DeleteFile(sPathName)) {
					FindClose(hdir);
					return FALSE;
				}
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	if (bDeleteTop && strlen(pDirName) > 3) {
		if (!RemoveDirectory(pDirName))
			return FALSE;
	}

	return TRUE;
}

//
//	�@�\	�F	�f�B���N�g���R�s�[����
//
//	����	�F	pSrcDir - �R�s�[���f�B���N�g����
//			�F	pDstDir - �R�s�[��f�B���N�g����
//			�F	pWnd - �R�s�[���Ƀ��t���b�V������E�B���h�E
//
//	���A�l	�F	TRUE - ����
//				FALSE - �G���[
//
//	�@�\�����F	�w�肳�ꂽ�f�B���N�g�ȉ����T�u�f�B���N�g�����܂߂ăR�s�[����B
//
//	���l	�F	����
//
BOOL CopyDirectory(LPCTSTR pSrcDir, LPCTSTR pDstDir, CWnd *pWnd)
{
	CString sSrcFile, sDstFile;
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;

	hdir = FindFirstFile(CreatePathName(pSrcDir, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			sSrcFile = CreatePathName(pSrcDir, findBuf.cFileName);
			sDstFile = CreatePathName(pDstDir, findBuf.cFileName);
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
					if (!CreateDirectory(sDstFile, NULL)) {
						FindClose(hdir);
						return FALSE;
					}

					if (!CopyDirectory(sSrcFile, sDstFile, pWnd)) {
						FindClose(hdir);
						return FALSE;
					}
				}
			} else {
				if (!CopyFile(sSrcFile, sDstFile, FALSE)) {
					FindClose(hdir);
					return FALSE;
				}

				if (pWnd != NULL)
					pWnd->UpdateWindow();
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	return TRUE;
}

//
//	�@�\	�F	���b�Z�[�W���M����
//
//	����	�F	pComputerName - ���M��R���s���[�^��
//			�F	pMessage - ���M���郁�b�Z�[�W
//
//	���A�l	�F	����
//
//	�@�\�����F	�w�肳�ꂽ�R���s���[�^�Ƀ��b�Z�[�W�𑗐M����B
//
//	���l	�F	����
//
void NetSendMessage(LPCTSTR pComputerName, LPCTSTR pMessage)
{
	WCHAR wbuf1[128], wbuf2[1024];

	if (*pComputerName != '\0') {
		MultiByteToWideChar(CP_ACP, 0, pComputerName, -1, wbuf1, sizeof(wbuf1));
		int n = MultiByteToWideChar(CP_ACP, 0, pMessage, -1, wbuf2, sizeof(wbuf2));
		NetMessageBufferSend(NULL, wbuf1, NULL, (LPBYTE)wbuf2, n * 2);
	}
}

//
//	�@�\	�F	�v���Z�X�I���҂�����
//
//	����	�F	hProcess - �v���Z�X�n���h��
//
//	���A�l	�F	�v���Z�X�̏I���R�[�h
//
//	�@�\�����F	�w�肳�ꂽ�v���Z�X���I������܂ő҂B
//
//	���l	�F	����
//
DWORD ProcessWait(HANDLE hProcess)
{
    DWORD dwExitCode;

    while (TRUE) {
        GetExitCodeProcess(hProcess ,&dwExitCode) ;

		if (dwExitCode != STILL_ACTIVE )
			break;

		Sleep(1000);
    }

    CloseHandle(hProcess);

    return dwExitCode;
}

//
//	�@�\	�F	���b�Z�[�W�\������
//
//	����	�F	nMsgID - ���b�Z�[�WID
//			�F	nBottumIcon - �{�^����� �� �A�C�R�����
//			�F	bSendMsg - �N���C�A���g�Ƀ��b�Z�[�W���M�̗L��
//			�F	... - ���b�Z�[�W�ɖ��ߍ��ޏ��
//
//	���A�l	�F	�����ꂽ�{�^��
//
//	�@�\�����F	�w�肵�����b�Z�[�WID�̃��b�Z�[�W��\������B
//
//	���l	�F	����
//
int DispMessage(UINT nMsgID, int nBottum, BOOL bSendMsg, ...)
{
	CString sMsg, sCode, sType, sBody;
	CString sCaption, sText;
	CString sProgramID;
	va_list args;
	va_start(args, bSendMsg);

	// ���\�[�X���烁�b�Z�[�W���e���擾
	sMsg.LoadString(nMsgID);

	// ���b�Z�[�W�𕪉�
	sCode = sMsg.SpanExcluding("\n");
	sBody.FormatV(sMsg.Mid(sCode.GetLength() + 1), args);

	// ��ނ𔻒f
	switch (sCode[sCode.GetLength() - 1]) {
	case 'I':
		sType = "���";
		nBottum |= MB_ICONINFORMATION;
		break;
	case 'W':
		sType = "�x��";
		nBottum |= MB_ICONEXCLAMATION;
		break;
	case 'E':
		sType = "�G���[";
		nBottum |= MB_ICONSTOP;
		break;
	case 'S':
		sType = "�d��ȃG���[";
		nBottum |= MB_ICONSTOP;
		break;
	case 'Q':
		sType = "�m�F";
		nBottum |= MB_ICONQUESTION;
		break;
	case 'P':
		sType = "���";
		break;
	default:
		sType = "���b�Z�[�W";
		break;
	}

	// �^�C�g���ҏW
	sProgramID.LoadString(IDS_PROGRAM_ID);
	sCaption.Format("%s(%s)", sType, sProgramID);
	
	// ���b�Z�[�W�ҏW
	sText.Format("%s\n\n%s", sCode, sBody);
	
	// �^�p�Ǘ��N���C�A���g�Ƀ��b�Z�[�W���M
	if (bSendMsg)
		NetSendMessage(GetIniData("OperatorClient", "ComputerName", ""), sText);

	// ���b�Z�[�W��\��
	return AfxGetMainWnd()->MessageBox(sText, sCaption, nBottum);
}
