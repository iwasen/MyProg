// InputDAT.cpp: CInputDAT �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputDAT.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CInputDAT::CInputDAT()
{

}

CInputDAT::~CInputDAT()
{

}

//
//	�@�\	�F�@���s�m�F���b�Z�[�W����
//
//	����	�F	pParam - ���̓p�����[�^
//
//	���A�l	�F	�������ʃR�[�h
//
//	�@�\�����F	���s�m�F���b�Z�[�W��\�����A�I�y���[�^�̎x����҂B
//
//	���l	�F	����
//
int CInputDAT::KakuninMessage(Param *pParam)
{
	if (DispMessage(IDS_MSG_DEVICE_SET1, MB_OKCANCEL, FALSE,
			pParam->sShubetsuCode, pParam->sShikibetsuCode) == IDCANCEL)
		return RC_CANCEL;

	return RC_NORMAL;
}

//
//	�@�\	�F�@�{�����[�����x���`�F�b�N����
//
//	����	�F	pParam - ���̓p�����[�^
//
//	���A�l	�F	�������ʃR�[�h
//
//	�@�\�����F	���͂��ꂽ�{�����[�����x���Ɣ}�̂̃{�����[�����x������v���邩�`�F�b�N����B
//
//	���l	�F	����
//
int CInputDAT::CheckVolumeName(Param *pParam)
{
	// DAT �̓��x���`�F�b�N����
	return RC_NORMAL;
}

//
//	�@�\	�F�@�}�̓��͏���
//
//	����	�F	pParam - ���̓p�����[�^
//
//	���A�l	�F	�������ʃR�[�h
//
//	�@�\�����F	�}�̂���t�@�C������͂���B
//
//	���l	�F	����
//
int CInputDAT::InputFiles(Param *pParam)
{
	CString sDstDir;
	CString sCmdLine;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	// �R�s�[��f�B���N�g�����擾
	sDstDir = g_sDatDir;

	// �R�s�[��f�B���N�g���폜
	DeleteDirectory(g_sDatDir, FALSE);

	// �R�s�[��f�B���N�g���쐬
	CreateDirectory(sDstDir, NULL);

	// NTBackup.exe ���N��
	sCmdLine.Format("ntbackup");
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hThread);
	ProcessWait(pi.hProcess);

	// ���ʓ��̓��b�Z�[�W
	if (DispMessage(IDS_MSG_NTBACKUP_END, MB_YESNO, FALSE) == IDNO)
		return RC_CANCEL;

	return RC_NORMAL;
}
