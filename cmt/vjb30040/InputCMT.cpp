// InputCMT.cpp: CInputCMT �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30040.h"
#include "InputCMT.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CInputCMT::CInputCMT()
{
	int nHAdapter, nScsiID, nScsiLUN;

	// INI�t�@�C�����CMT�̐ݒ���擾
	nHAdapter = GetIniData("CMT", "HAdapter", 0);
	nScsiID = GetIniData("CMT", "ScsiID", 0);
	nScsiLUN = GetIniData("CMT", "ScsiLUN", 0);

	// CMT���u�I�[�v��
	if (m_MTDriver.Open(nHAdapter, nScsiID, nScsiLUN) == 0)
		m_bOpen = TRUE;
	else
		m_bOpen = FALSE;
}

CInputCMT::~CInputCMT()
{
	// CMT���u�N���[�Y
	if (m_bOpen)
		m_MTDriver.Close();
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
int CInputCMT::KakuninMessage(Param *pParam)
{
	CString sFirstVolLabel;

	// �ŏ��̃{�����[���������o��
	sFirstVolLabel = pParam->sVolLabel.SpanExcluding(",");

	if (pParam->bLabel) {
		// ���x���L��̊m�F���b�Z�[�W
		if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
				pParam->sShubetsuCode, pParam->sShikibetsuCode, sFirstVolLabel) == IDCANCEL)
			return RC_CANCEL;
	} else {
		// ���x�������̊m�F���b�Z�[�W
		if (DispMessage(IDS_MSG_DEVICE_SET3, MB_OKCANCEL, FALSE,
				pParam->sShubetsuCode, pParam->sShikibetsuCode, sFirstVolLabel) == IDCANCEL)
			return RC_CANCEL;
	}

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
int CInputCMT::CheckVolumeName(Param *pParam)
{
	CString sFirstVolLabel;
	CString sReadVolLabel;

	// CMT���u���I�[�v������Ă��Ȃ���΃G���[
	if (!m_bOpen)
		return RC_IOERROR;

	if (pParam->bLabel) {
		// �ŏ��̃{�����[���������o��
		sFirstVolLabel = pParam->sVolLabel.SpanExcluding(",");

		// CMT����{�����[�������擾
		if (m_MTDriver.ReadVolumeLabel(sReadVolLabel) != 0)
			return RC_IOERROR;

		// ��v�`�F�b�N
		if (sReadVolLabel != sFirstVolLabel)
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
int CInputCMT::InputFiles(Param *pParam)
{
	CString sDstDir;
	int nResult;

	// CMT���u���I�[�v������Ă��Ȃ���΃G���[
	if (!m_bOpen)
		return RC_IOERROR;

	// �R�s�[��f�B���N�g�����擾
	sDstDir = CreatePathName(g_sInputDir,
			pParam->sShikibetsuCode + (pParam->bLabel ? pParam->sVolLabel.SpanExcluding(",") : "000000"));

	// �R�s�[��f�B���N�g���폜
	DeleteDirectory(sDstDir, FALSE);

	// �R�s�[��f�B���N�g���쐬
	CreateDirectory(sDstDir, NULL);

	// ���t���b�V���E�B���h�E�ݒ�
	m_MTDriver.m_pUpdateWnd = m_pUpdateWnd;

	// ���x���L�薳���ŏ����𕪂���
	if (pParam->bLabel)
		nResult = InputFilesWithLabel(pParam, sDstDir);
	else
		nResult = InputFilesNonLabel(pParam, sDstDir);

	return nResult;
}

//
//	�@�\	�F�@���x��������͏���
//
//	����	�F	pParam - ���̓p�����[�^
//				pDirName - ���͐�f�B���N�g����
//
//	���A�l	�F	�������ʃR�[�h
//
//	�@�\�����F	���x������CMT����t�@�C������͂���B
//
//	���l	�F	����
//
int CInputCMT::InputFilesWithLabel(Param *pParam, LPCTSTR pDirName)
{
	CString sVolLabel;
	CString sCurrentVolLabel;
	CString sReadVolLabel;
	BOOL bFirst = TRUE;
	int nResult;

	sVolLabel = pParam->sVolLabel;
	while (sVolLabel.GetLength() != 0) {
		// �J�����g�{�����[�����x�����o��
		sCurrentVolLabel = sVolLabel.SpanExcluding(",");

		if (bFirst)
			bFirst = FALSE;
		else {
			// �Q�߂̃{�����[���ȍ~�̊m�F���b�Z�[�W
			if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
					pParam->sShubetsuCode, pParam->sShikibetsuCode, sCurrentVolLabel) == IDCANCEL)
				return RC_CANCEL;
		}

		while (true) {
			// CMT����{�����[�����x���擾
			if (m_MTDriver.ReadVolumeLabel(sReadVolLabel) != 0)
				return RC_IOERROR;

			// �{�����[�����x����v�`�F�b�N
			if (sReadVolLabel != sCurrentVolLabel) {
				m_MTDriver.Unload();
				if (DispMessage(IDS_MSG_DIFF_VOLLABEL2, MB_RETRYCANCEL, FALSE,
						sReadVolLabel, sCurrentVolLabel) == IDCANCEL) {
					return RC_CANCEL;
				}
			} else
				break;
		}

		// CMT����t�@�C���ɃR�s�[
		if ((nResult = m_MTDriver.ReadFileWithLabel(pDirName)) < 0 || nResult == RESULT_FILE_ERROR)
			return RC_IOERROR;
		else if (nResult != RESULT_VOLUME_END)
			break;

		// ���̃{�����[�����x���Ɉړ�
		sVolLabel = sVolLabel.Mid(min(sCurrentVolLabel.GetLength() + 1, sVolLabel.GetLength()));
	}

	return RC_NORMAL;
}


//
//	�@�\	�F�@���x���������͏���
//
//	����	�F	pParam - ���̓p�����[�^
//				pDirName - ���͐�f�B���N�g����
//
//	���A�l	�F	�������ʃR�[�h
//
//	�@�\�����F	���x������CMT����t�@�C������͂���B
//
//	���l	�F	����
//
int CInputCMT::InputFilesNonLabel(Param *pParam, LPCTSTR pDirName)
{
	CString sVolLabel;
	CString sCurrentVolLabel;
	CString sFileName;
	CString sTemp;
	int nRecordLength;
	int nVolume;
	int nNextPos;
	BOOL bBOT = FALSE;
	int nVolCount = 0;
	BOOL bLoop = TRUE;
	int i;
	int nResult;

	sVolLabel = pParam->sVolLabel;
	while (sVolLabel.GetLength() != 0 && bLoop) {
		// �t�@�C�����^���R�[�h���^�{�������o��
		sCurrentVolLabel = sVolLabel.SpanExcluding(",");

		// �t�@�C�����^���R�[�h���^�{���𕪉�
		sFileName = sCurrentVolLabel.SpanExcluding("/");
		nNextPos = sFileName.GetLength() + 1;
		sTemp = sCurrentVolLabel.Mid(nNextPos).SpanExcluding("/");
		nRecordLength = atoi(sTemp);
		nNextPos += sTemp.GetLength() + 1;
		nVolume = atoi(sCurrentVolLabel.Mid(nNextPos));

		// �R�s�[��t�@�C���̃p�X�����쐬
		sFileName = CreatePathName(pDirName, sFileName);

		for (i = 0; i < nVolume; i++) {
			// �{�����[���{���`�F�b�N
			if (nVolCount >= pParam->nVolCount) {
				DispMessage(IDS_MSG_NOTEXIST_DAT, MB_OK, FALSE, sFileName, nRecordLength, nVolume);
				bLoop = FALSE;
				break;
			}

			if (bBOT) {
				// �Q�߂̃{�����[���ȍ~�̊m�F���b�Z�[�W
				if (DispMessage(IDS_MSG_DEVICE_SET3, MB_OKCANCEL, FALSE,
						pParam->sShubetsuCode, pParam->sShikibetsuCode, sCurrentVolLabel) == IDCANCEL)
					return RC_CANCEL;
			}

			// CMT����t�@�C���ɃR�s�[
			if ((nResult = m_MTDriver.ReadFileNonLabel(sFileName)) < 0 || nResult == RESULT_FILE_ERROR)
				return RC_IOERROR;
			else if (nResult == RESULT_FILE_END && i + 1 != nVolume) {
				DispMessage(IDS_MSG_DIFF_VOLNUM, MB_OK, FALSE);
				bLoop = FALSE;
				break;
			} else if (nResult == RESULT_VOLUME_END) {
				nVolCount++;
				bBOT = TRUE;
			}
		}

		// ���̃{�����[�����x���Ɉړ�
		sVolLabel = sVolLabel.Mid(min(sCurrentVolLabel.GetLength() + 1, sVolLabel.GetLength()));
	}

	return RC_NORMAL;
}

//
//	�@�\	�F�@�G���[�擾����
//
//	����	�F	nErrorCode - �G���[�R�[�h
//				sErrorMsg - �G���[���e
//
//	���A�l	�F	����
//
//	�@�\�����F	�G���[�R�[�h�Ƃ��̓��e�i���b�Z�[�W�j���擾����B
//
//	���l	�F	����
//
void CInputCMT::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = m_MTDriver.GetError(sErrorMsg);
}
