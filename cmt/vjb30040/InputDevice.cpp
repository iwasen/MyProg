// InputDevice.cpp: CInputDevice �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputDevice.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CInputDevice::CInputDevice()
{
	m_pUpdateWnd = NULL;
}

CInputDevice::~CInputDevice()
{

}

//
//	�@�\	�F�@�{�����[�����x���擾����
//
//	����	�F	pPath - �f�o�C�X�̃��[�g�p�X��
//				sVolumeName - �擾���ꂽ�{�����[�����x��
//
//	���A�l	�F	TRUE - ����
//				FALSE - �G���[�i���~�j
//
//	�@�\�����F	�O���}�̂̃{�����[�����x�����擾����B
//
//	���l	�F	����
//
BOOL CInputDevice::GetVolumeName(LPCTSTR pPath, CString &sVolumeName)
{
	char cVolumeName[256];
	DWORD dwMaxCompLen;
	DWORD dwFileSystemFlags;
	BOOL bResult;

	// �{�����[�����擾
	while (TRUE) {
		bResult = GetVolumeInformation(
		   pPath,				// ���[�g�f�B���N�g�����ւ̃|�C���^
		   cVolumeName,			// �{�����[�������i�[����o�b�t�@�ւ̃|�C���^
		   sizeof(cVolumeName),	// lpVolumeNameBuffer �o�b�t�@�̃T�C�Y
		   NULL,				// �{�����[���V���A���i���o�[���i�[����ϐ��ւ̃|�C���^
		   &dwMaxCompLen,		// �t�@�C�����\���v�f�̍ő咷���i�[����ϐ��ւ̃|�C���^
		   &dwFileSystemFlags,	// �t�@�C���V�X�e���t���O���i�[����ϐ��ւ̃|�C���^
		   NULL,				// �t�@�C���V�X�e�������i�[����o�b�t�@�ւ̃|�C���^
		   0					// lpFileSystemNameBuffer �o�b�t�@�̃T�C�Y
		);
		if (bResult)
			break;
		else {
			if (GetLastError() == ERROR_NOT_READY) {
				if (DispMessage(IDS_MSG_NOT_READY, MB_OKCANCEL, FALSE, pPath) == IDCANCEL)
					return FALSE;
			} else
				return FALSE;
		}
	}

	sVolumeName = cVolumeName;

	return TRUE;
}

//
//	�@�\	�F�@�G���[�擾����
//
//	����	�F	nErrorCode - �G���[�R�[�h
//				sErrorMsg - �G���[���e
//
//	���A�l	�F	����
//
//	�@�\�����F	�G���[�擾�̃f�t�H���g�����B
//
//	���l	�F	�K�v�ɉ����Čp���N���X�ŃI�[�o�[���C�h����B
//
void CInputDevice::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = 0;
}

//
//	�@�\	�F�@���t���b�V���E�B���h�ݒ菈��
//
//	����	�F	pWnd - ���t���b�V������E�B���h�E
//
//	���A�l	�F	����
//
//	�@�\�����F	�R�s�[���Ƀ��t���b�V������E�B���h�E��ݒ肷��B
//
//	���l	�F	����
//
void CInputDevice::SetUpdateWindow(CWnd *pWnd)
{
	m_pUpdateWnd = pWnd;
}
