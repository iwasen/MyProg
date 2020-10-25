// OutputMOD.cpp: COutputMOD �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputMOD.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

COutputMOD::COutputMOD()
{

}

COutputMOD::~COutputMOD()
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
int COutputMOD::CheckVolumeName(Queue *pQueue)
{
	CString sVolumeName;

	// �{�����[�����擾
	if (!GetVolumeName(g_sPathMOD, sVolumeName))
		return RC_IOERROR;

	// �{�����[�����`�F�b�N
	if (pQueue->sVolLabel == "000000") {
		if (sVolumeName != "")
			return RC_VOLUMENAME;
	} else {
		if (sVolumeName != pQueue->sVolLabel)
			return RC_VOLUMENAME;
	}

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
int COutputMOD::OutputFiles(Queue *pQueue)
{
	// �R�s�[��̃f�B���N�g���i�t�@�C���j���폜
	if (!DeleteDirectory(g_sPathMOD, TRUE))
		return RC_IOERROR;

	// �f�B���N�g�����ƃt�@�C���R�s�[
	if (!CopyDirectory(m_sSrcDir, g_sPathMOD, NULL))
		return RC_IOERROR;

	return RC_NORMAL;
}
