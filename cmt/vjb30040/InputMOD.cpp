// InputMOD.cpp: CInputMOD �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputMOD.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CInputMOD::CInputMOD()
{

}

CInputMOD::~CInputMOD()
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
int CInputMOD::KakuninMessage(Param *pParam)
{
	if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
			pParam->sShubetsuCode, pParam->sShikibetsuCode, pParam->sVolLabel) == IDCANCEL)
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
int CInputMOD::CheckVolumeName(Param *pParam)
{
	CString sVolumeName;

	// �{�����[�����擾
	if (!GetVolumeName(g_sPathMOD, sVolumeName))
		return RC_IOERROR;

	// �{�����[�����`�F�b�N
	if (pParam->sVolLabel == "000000") {
		if (sVolumeName != "")
			return RC_VOLUMENAME;
	} else {
		if (sVolumeName != pParam->sVolLabel)
			return RC_VOLUMENAME;
	}

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
int CInputMOD::InputFiles(Param *pParam)
{
	CString sDstDir;

	// �R�s�[��f�B���N�g�����擾
	sDstDir = CreatePathName(g_sInputDir, pParam->sShikibetsuCode + pParam->sVolLabel);

	// �R�s�[��f�B���N�g���폜
	DeleteDirectory(sDstDir, FALSE);

	// �R�s�[��f�B���N�g���쐬
	CreateDirectory(sDstDir, NULL);

	// �f�B���N�g�����ƃt�@�C���R�s�[
	if (!CopyDirectory(g_sPathMOD, sDstDir, m_pUpdateWnd))
		return RC_IOERROR;

	return RC_NORMAL;
}
