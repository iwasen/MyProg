// OutputDevice.cpp: COutputDevice �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputDevice.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

COutputDevice::COutputDevice()
{

}

COutputDevice::~COutputDevice()
{

}

//
//	�@�\	�F	�o�̓t�@�C�����݃`�F�b�N����
//
//	����	�F	pQueue - �L���[���
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�O���}�̂ɏo�͂���t�@�C�������݂��邩�`�F�b�N����B
//
//	���l	�F	����
//
int COutputDevice::CheckDstFile(Queue *pQueue)
{
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	BOOL bFind;

	// �R�s�[���f�B���N�g�����擾
	m_sSrcDir = CreatePathName(g_sOutputDir, pQueue->sShikibetsuCode + pQueue->sVolLabel.SpanExcluding(","));

	hdir = FindFirstFile(CreatePathName(m_sSrcDir, "*"), &findBuf);
	if (hdir == INVALID_HANDLE_VALUE)
		return RC_NODIRECTORY;

	bFind = FALSE;
	do {
		if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
			if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0) {
				bFind = TRUE;
				break;
			}
		} else {
			bFind = TRUE;
			break;
		}
	} while (FindNextFile(hdir, &findBuf));

	FindClose(hdir);

	if (!bFind)
		return RC_NOFILE;

	return RC_NORMAL;
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
BOOL COutputDevice::GetVolumeName(LPCTSTR pPath, CString &sVolumeName)
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
void COutputDevice::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = 0;
}
