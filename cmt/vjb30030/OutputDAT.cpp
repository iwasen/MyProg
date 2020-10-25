// OutputDAT.cpp: COutputDAT �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputDAT.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

COutputDAT::COutputDAT()
{

}

COutputDAT::~COutputDAT()
{

}

//
//	�@�\	�F	�{�����[�����x���`�F�b�N����
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�O���}�̂̃{�����[�����x�����`�F�b�N����B
//
//	���l	�F	����
//
int COutputDAT::CheckVolumeName(Queue *pQueue)
{
	// DAT�̓{�����[�����x���`�F�b�N����
	return RC_NORMAL;
}

//
//	�@�\	�F	�O���}�̏o�͏���
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�O���}�̂Ƀt�@�C�����o�͂���B
//
//	���l	�F	����
//
int COutputDAT::OutputFiles(Queue *pQueue)
{
	CString sCmdLine;
	char cCurrentDir[256];
	STARTUPINFO si;
	PROCESS_INFORMATION pi;

	sCmdLine.Format("ntbackup backup %s /hc:off /t copy", m_sSrcDir);
	memset(&si, 0, sizeof(si));
	si.cb = sizeof(si);
	si.dwFlags = STARTF_USESHOWWINDOW;
	si.wShowWindow = SW_SHOW;
	GetCurrentDirectory(sizeof(cCurrentDir), cCurrentDir);
	CreateProcess(NULL, (LPTSTR)(LPCTSTR)sCmdLine, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, cCurrentDir, &si, &pi);
	CloseHandle(pi.hThread);

	ProcessWait(pi.hProcess);

	if (DispMessage(IDS_MSG_DAT_END, MB_YESNO, TRUE) == IDNO)
		return RC_IOERROR;

	return RC_NORMAL;
}
