// OutputCMT.cpp: COutputCMT �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "vjb30030.h"
#include "OutputCMT.h"
#include <ctype.h>

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

COutputCMT::COutputCMT()
{
	int nHAdapter, nScsiID, nScsiLUN;

	m_bLabelCheck = FALSE;

	// INI�t�@�C�����CMT�̐ݒ���擾
	nHAdapter = GetIniData("CMT", "HAdapter", 0);
	nScsiID = GetIniData("CMT", "ScsiID", 0);
	nScsiLUN = GetIniData("CMT", "ScsiLUN", 0);
	m_sSystemID = GetIniData("CMT", "SystemID", "HITACVOS3");

	// CMT���u�I�[�v��
	if (m_MTDriver.Open(nHAdapter, nScsiID, nScsiLUN) == 0)
		m_bOpen = TRUE;
	else
		m_bOpen = FALSE;
}

COutputCMT::~COutputCMT()
{
	// CMT���u�N���[�Y
	if (m_bOpen)
		m_MTDriver.Close();
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
int COutputCMT::CheckVolumeName(Queue *pQueue)
{
	// CMT���u���I�[�v������Ă��Ȃ���΃G���[
	if (!m_bOpen)
		return RC_IOERROR;

	m_bLabelCheck = TRUE;

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
int COutputCMT::OutputFiles(Queue *pQueue)
{
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	int i;
	char (*pFileBuf)[LEN_FILENAME];
	int nFileCount;
	CString sVolLabel[MAX_VOLUME_LABEL];
	int nVolLabel;
	int nResult;

	// CMT���u���I�[�v������Ă��Ȃ���΃G���[
	if (!m_bOpen)
		return RC_IOERROR;

	// �J���}�ŋ�؂�ꂽ�{�����[�����x���𕪗�
	nVolLabel = SplitVolumeLabel(pQueue, sVolLabel);

	// �t�@�C�����擾���t�@�C�����`�F�b�N
	nFileCount = 0;
	hdir = FindFirstFile(CreatePathName(m_sSrcDir, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
				if (!CheckFileName(findBuf.cFileName)) {
					FindClose(hdir);
					DispMessage(IDS_MSG_ILLEGAL_FILENAME, MB_OK, TRUE, findBuf.cFileName);
					return RC_ILLEGALFILENAME;
				}
				nFileCount++;
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	// �t�@�C�����o�b�t�@�m��
	pFileBuf = new char[nFileCount][LEN_FILENAME];

	// �t�@�C�������t�@�C�����o�b�t�@�Ɋi�[
	i = 0;
	hdir = FindFirstFile(CreatePathName(m_sSrcDir, "*"), &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
				strcpy(pFileBuf[i++], findBuf.cFileName);
				if (i >= nFileCount)
					break;
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	// �t�@�C���������� "n" �Ń\�[�g
	qsort(pFileBuf, nFileCount, LEN_FILENAME, CompareFileName);

	// CMT�������ݏ���
	nResult = WriteCMT(m_sSrcDir, pFileBuf, nFileCount, sVolLabel, nVolLabel);

	// �t�@�C�����o�b�t�@�J��
	delete [] pFileBuf;

	return nResult;
}

//
//	�@�\	�F	CMT�o�͏���
//
//	����	�F	sSrcDir - �R�s�[���f�B���N�g��
//				pFileBuf - �o�̓t�@�C�����o�b�t�@
//				nFileCount - �o�̓t�@�C����
//				sVolLabel - �{�����[�����x��
//				nVolLabel - �{�����[�����x����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	CMT�Ƀt�@�C�����o�͂���B
//
//	���l	�F	����
//
int COutputCMT::WriteCMT(CString &sSrcDir, char (*pFileBuf)[LEN_FILENAME], int nFileCount, CString *sVolLabel, int nVolLabel)
{
	CString sSrcFile;
	BOOL bBOT;
	CString sReadVolLabel;
	CString sFileID;
	CString sMsg;
	int nRecordLength;
	int nBlockingFactor;
	int nVolCount;
	int i;
	int nResult;

	// CMT�������݊J�n����
	if (m_MTDriver.WriteStart() != 0) {
		return RC_IOERROR;
	}

	// CMT�������ݏ���
	bBOT = TRUE;
	nVolCount = 0;
	for (i = 0; i < nFileCount; i++) {
		// �t�@�C��������t�@�C�����ʖ��A���R�[�h���A�u���b�N���W���𕪗�
		if (!AnalyzeFileName(pFileBuf[i], &sFileID, &nRecordLength, &nBlockingFactor))
			continue;

		// �R�s�[���t�@�C���̃p�X���擾
		sSrcFile = CreatePathName(sSrcDir, pFileBuf[i]);

		// �P�t�@�C���������ݏ���
		while (TRUE) {
			if (bBOT) {
				// �}�̃Z�b�g���b�Z�[�W
				if (nVolCount < nVolLabel) {
					if (DispMessage(IDS_MSG_DEVICE_SET2, MB_OKCANCEL, FALSE,
							"CMT", sVolLabel[nVolCount]) == IDCANCEL)
					return RC_CANCEL;
				} else {
					if (DispMessage(IDS_MSG_DEVICE_SET, MB_OKCANCEL, FALSE,
							"CMT") == IDCANCEL)
					return RC_CANCEL;
				}

				// �{�����[�����x���`�F�b�N
				if (m_bLabelCheck && nVolCount < nVolLabel) {
					while (true) {
						if ((nResult = m_MTDriver.ReadVolumeLabel(sReadVolLabel)) != 0) {
							if (nResult == MT_KEY_NOTREADY) {
								if (DispMessage(IDS_MSG_NOTREADY, MB_RETRYCANCEL, FALSE) == IDCANCEL)
									return RC_CANCEL;
								continue;
							}
							return RC_IOERROR;
						}

						if (sReadVolLabel == "")
							sReadVolLabel = "000000";

						if (sReadVolLabel != sVolLabel[nVolCount]) {
							m_MTDriver.Unload();
							if (DispMessage(IDS_MSG_DIFF_VOLLABEL, MB_RETRYCANCEL, FALSE,
									sReadVolLabel, sVolLabel[nVolCount]) == IDCANCEL) {
								return RC_VOLUMENAME;
							}
						} else
							break;
					}
				}
			}
retry:
			// CMT�Ƀt�@�C���̓��e����������
			if (sVolLabel[0] == "000000")
				nResult = m_MTDriver.WriteFileNonLabel(sSrcFile, nRecordLength, nBlockingFactor);
			else
				nResult = m_MTDriver.WriteFileWithLabel(sSrcFile, sFileID, m_sSystemID, nRecordLength, nBlockingFactor);

			if (nResult < 0) {
				if (nResult == MT_KEY_NOTREADY) {
					if (DispMessage(IDS_MSG_NOTREADY, MB_RETRYCANCEL, FALSE) == IDCANCEL)
						return RC_CANCEL;
					goto retry;
				}
				return RC_IOERROR;
			} else if (nResult == RESULT_FILE_END) {
				// �t�@�C���������ݏI��
				bBOT = FALSE;
				break;
			} else if (nResult == RESULT_VOLUME_END) {
				// ���x�������̏ꍇ�A�}���`�t�@�C���^�}���`�{�����[���̓T�|�[�g���Ȃ�
				if (sVolLabel[0] == "000000" && nFileCount > 1)
					return RC_MULTIFILEVOL;

				nVolCount++;
				bBOT = TRUE;
			}
		}
	}

	// CMT�������ݏI������
	if (m_MTDriver.WriteEnd() != 0)
		return RC_IOERROR;

	return RC_NORMAL;
}

//
//	�@�\	�F	�o�̓t�@�C���\�[�g�p��r����
//
//	����	�F	arg1 - �o�̓t�@�C�����P
//				arg2 - �o�̓t�@�C�����Q
//
//	���A�l	�F	��r����
//
//	�@�\�����F	�o�̓t�@�C�����\�[�g���邽�߂ɔ�r����B
//
//	���l	�F	����
//
int COutputCMT::CompareFileName(const void *arg1, const void *arg2)
{
	char *p;
	int n1, n2;

	// �t�@�C���������� "n" ���擾
	if ((p = strrchr((const char *)arg1, '.')) != NULL)
		n1 = atoi(p + 1);
	else
		n1 = 0;

	// �t�@�C���������� "n" ���擾
	if ((p = strrchr((const char *)arg2, '.')) != NULL)
		n2 = atoi(p + 1);
	else
		n2 = 0;

	return n1 - n2;
}

//
//	�@�\	�F	�{�����[�����x����������
//
//	����	�F	pQueue - �L���[���
//				pVolLabel - �������ꂽ�{�����[�����x��
//
//	���A�l	�F	�{�����[�����x����
//
//	�@�\�����F	�J���}�ŋ�؂�ꂽ�{�����[�����x���𕪗�����B
//
//	���l	�F	����
//
int COutputCMT::SplitVolumeLabel(Queue *pQueue, CString *pVolLabel)
{
	int i;
	CString sVolLabel;

	sVolLabel = pQueue->sVolLabel;
	for (i = 0; i < MAX_VOLUME_LABEL; i++) {
		if (sVolLabel.GetLength() == 0)
			break;

		*pVolLabel = sVolLabel.SpanExcluding(",");
		sVolLabel = sVolLabel.Mid(min((*pVolLabel).GetLength() + 1, sVolLabel.GetLength()));
		pVolLabel++;
	}

	return i;
}

//
//	�@�\	�F	�o�̓t�@�C�����`�F�b�N
//
//	����	�F	pFileName - �o�̓t�@�C����
//
//	���A�l	�F	����Ȃ�TRUE
//
//	�@�\�����F�@�o�̓t�@�C�������������`���ł��邩�`�F�b�N����B
//
//	���l	�F	����
//
BOOL COutputCMT::CheckFileName(LPCTSTR pFileName)
{
	char cFileName[LEN_FILENAME];
	char *p;

	// ".n" �� "." ��T��
	strcpy(cFileName, pFileName);
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// n �̐����`�F�b�N
	*p = '\0';
	if (!IsNumber(p))
		return FALSE;

	// ".�u���b�N���W��" �� "." ��T��
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// �u���b�N���W���̐����`�F�b�N
	*p++ = '\0';
	if (!IsNumber(p))
		return FALSE;

	// ".���R�[�h��" �� "." ��T��
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// ���R�[�h���̐����`�F�b�N
	*p++ = '\0';
	if (!IsNumber(p))
		return FALSE;

	return TRUE;
}

//
//	�@�\	�F	�o�̓t�@�C������͏���
//
//	����	�F	pFileName - �o�̓t�@�C����
//				pFileID - �t�@�C�����ʖ�
//				pRecordLength - ���R�[�h��
//				pBlockingFactor - �u���b�N���W��
//
//	���A�l	�F	����Ȃ�TRUE
//
//	�@�\�����F	�o�̓t�@�C���ɕt����ꂽ���R�[�h���A�u���b�N���W�������o���B
//
//	���l	�F	����
//
BOOL COutputCMT::AnalyzeFileName(LPCTSTR pFileName, CString *pFileID, int *pRecordLength, int *pBlockingFactor)
{
	char cFileName[LEN_FILENAME];
	char *p;

	// ".n" �� "." ��T��
	strcpy(cFileName, pFileName);
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// ".�u���b�N���W��" �� "." ��T��
	*p = '\0';
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// �u���b�N���W���̎擾
	*p++ = '\0';
	*pBlockingFactor = atoi(p);

	// ".���R�[�h��" �� "." ��T��
	if ((p = strrchr(cFileName, '.')) == NULL)
		return FALSE;

	// ���R�[�h���̎擾
	*p++ = '\0';
	*pRecordLength = atoi(p);

	// �t�@�C�����ʖ��̎擾
	*pFileID = cFileName;

	return TRUE;
}

//
//	�@�\	�F�@�����`�F�b�N����
//
//	����	�F	pNum - �`�F�b�N���镶����
//
//	���A�l	�F	TRUE - OK, FALSE - NG
//
//	�@�\�����F	�G���[�R�[�h�Ƃ��̓��e�i���b�Z�[�W�j���擾����B
//
//	���l	�F	����
BOOL COutputCMT::IsNumber(LPCTSTR pNum)
{
	while (*pNum != '\0') {
		if (!isdigit(*pNum++))
			return FALSE;
	}

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
//	�@�\�����F	�G���[�R�[�h�Ƃ��̓��e�i���b�Z�[�W�j���擾����B
//
//	���l	�F	����
//
void COutputCMT::GetError(int &nErrorCode, CString &sErrorMsg)
{
	nErrorCode = m_MTDriver.GetError(sErrorMsg);
}
