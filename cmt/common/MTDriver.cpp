// MTDriver.cpp: CMTDriver �N���X�̃C���v�������e�[�V����
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MTDriver.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define RETRY_READY		10			// Ready �̃��g���C��

// VOL1 ���x��
struct VOL1 {
	char	LabelID[4];				// ���x�����ʖ�
	char	VolumeID[6];			// �{�����[�����ʖ�
	char	Reserved1[31];			// ���g�p
	char	Owner[10];				// ���L��
	char	Reserved2[29];			// ���g�p
};

// HDR1,EOF1,EOV1 ���x��
struct LABEL1 {
	char	LabelID[4];				// ���x�����ʖ�
	char	FileID[17];				// �t�@�C�����ʖ�
	char	VolumeID[6];			// �{�����[�����ʖ�
	char	VolSeqNo[4];			// �{�����[�������ԍ�
	char	FileSeqNo[4];			// �t�@�C�������ԍ�
	char	GenerationNo[4];		// ����ԍ�
	char	VersionNo[2];			// �o�[�W�����ԍ�
	char	CreationDate[6];		// �쐬���t
	char	ExpirationDate[6];		// �������t
	char	Accessibility[1];		// �@���ی�\��
	char	BlockCount[6];			// �u���b�N��
	char	SystemCode[13];			// �V�X�e�����ʖ�
	char	Reserved1[7];			// ���g�p
};

// HDR2,EOF2,EOV2 ���x��
struct LABEL2 {
	char	LabelID[4];				// ���x�����ʖ�
	char	RecordFormat[1];		// ���R�[�h�`��
	char	BlockLength[5];			// �u���b�N��
	char	RecordLength[5];		// ���R�[�h��
	char	Density[1];				// �L�^���x
	char	VolumeStatus[1];		// �{�����[�����
	char	JobStep[17];			// �W���u�^�X�e�b�v��
	char	Compressed[2];			// �L�^����
	char	ControlChar[1];			// ���䕶��
	char	Reserved1[1];			// ���g�p
	char	Blocking[1];			// �u���b�N��
	char	Reserved2[11];			// ���g�p
	char	BufferOffet[2];			// �o�b�t�@�I�t�Z�b�g
	char	Reserved3[28];			// ���g�p
};

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CMTDriver::CMTDriver()
{
	m_hHandle = -1;
	m_nErrorCode = 0;
	m_nReadAhead = 0;
	m_pUpdateWnd = NULL;
	m_bBOT = TRUE;
}

CMTDriver::~CMTDriver()
{
	Close();
}

//
//	�@�\	�F�@MT�I�[�v������
//
//	����	�F	nHAdapter - �z�X�g�A�_�v�^�ԍ�
//				nScsiID - SCSI ID
//				nScsiLUN - SCSI LUN
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT���u�̃I�[�v�����s���B
//
//	���l	�F	�Ȃ�
//
int CMTDriver::Open(int nHAdapter, int nScsiID, int nScsiLUN)
{
	int nResult;

	nResult = MT_open(nHAdapter, nScsiID, nScsiLUN);
	if (nResult >= 0) {
		m_hHandle = nResult;
		nResult = 0;
	} else
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@MT�N���[�Y����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT���u�̃N���[�Y���s���B
//
//	���l	�F	����
//
int CMTDriver::Close()
{
	int nResult;

	if (m_hHandle != -1) {
		nResult = MT_close(m_hHandle);
		m_hHandle = -1;
	} else
		nResult = 0;

	return nResult;
}

//
//	�@�\	�F�@�G���[�擾����
//
//	����	�F	sErrorMsg - �G���[���b�Z�[�W
//
//	���A�l	�F	�G���[�R�[�h
//
//	�@�\�����F	�G���[�R�[�h�ƃG���[���b�Z�[�W���擾����B
//
//	���l	�F	����
//
int CMTDriver::GetError(CString &sErrorMsg)
{
	int nErrorCode;
	BYTE msg[256 + 1];

	nErrorCode = m_nErrorCode;
	MT_message2(nErrorCode, msg);
	sErrorMsg = msg;

	m_nErrorCode = 0;

	return nErrorCode;
}

//
//	�@�\	�F�@�A�����[�h����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT���A�����[�h����B
//
//	���l	�F	����
//
int CMTDriver::Unload()
{
	int nResult;

	// �A�����[�h
	if ((nResult = MT_unload(m_hHandle, MT_OPERATE_STD, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@�����C���h����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT�������C���h����B
//
//	���l	�F	����
//
int CMTDriver::Rewind()
{
	int nResult;

	// �����C���h
	if ((nResult = MT_rewind(m_hHandle, MT_OPERATE_STD, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@�t�@�C���}�[�N�������ݏ���
//
//	����	�F	nCount - �������ރt�@�C���}�[�N�̐�
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT�Ɏw�肳�ꂽ�������t�@�C���}�[�N���������ށB
//
//	���l	�F	����
//
int CMTDriver::WriteFmk(int nCount)
{
	int nResult;

	// �t�@�C���}�[�N��������
	if ((nResult = MT_writefmk(m_hHandle, nCount, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@�P�u���b�N�ǂݍ��ݏ���
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT����o�b�t�@�ɂP�u���b�N�ǂݍ��ށB
//
//	���l	�F	����
//
int CMTDriver::Read()
{
	int nResult;

	// �P�u���b�N�ǂݍ���
	if ((nResult = MT_read(m_hHandle, m_IOBuf, BUF_SIZE, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@�P�u���b�N�������ݏ���
//
//	����	�F	nDataLen - �������ރu���b�N��
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT�Ƀo�b�t�@����P�u���b�N�������ށB
//
//	���l	�F	����
//
int CMTDriver::Write(int nDataLen)
{
	int nResult;

	// �P�u���b�N��������
	if ((nResult = MT_write(m_hHandle, m_IOBuf, nDataLen, &m_StatusData)) < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@�{�����[�����ʖ��擾����
//
//	����	�F	sVolLabel - �擾���ꂽ�{�����[�����ʖ�
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT����{�����[�����ʖ����擾����B
//
//	���l	�F	����
//
int CMTDriver::ReadVolumeLabel(CString &sVolLabel)
{
	int nResult;
	VOL1 *pVol1;

	// Ready �҂�
	if ((nResult = WaitReady()) != 0)
		return nResult;

	// �����C���h
	if ((nResult = Rewind()) != 0)
		return nResult;

	// �P�u���b�N(VOL1)�ǂݍ���
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// ���x�����ʖ��`�F�b�N
	pVol1 = (VOL1 *)m_IOBuf;
	if (strncmp(pVol1->LabelID, "VOL1", 4) == 0)
		sVolLabel = CString(pVol1->VolumeID, sizeof(pVol1->VolumeID));
	else
		sVolLabel = "";

	return 0;
}

//
//	�@�\	�F�@�������݊J�n����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT�ɏ������݂��s���ۂ̏��������B
//
//	���l	�F	����
//
int CMTDriver::WriteStart()
{
	// �t�@�C�����N���[�Y����Ă��Ȃ�������N���[�Y
	if (m_File.m_hFile != CFile::hFileNull)
		m_File.Close();

	m_bBOT = TRUE;
	m_nVolSeqNo = 1;
	m_nFileSeqNo = 1;

	return 0;
}

//
//	�@�\	�F�@�������ݏI������
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT�ɏ������I�����̌㏈���B
//
//	���l	�F	����
//
int CMTDriver::WriteEnd()
{
	int nResult;

	WaitReady();

	// BOT�ʒu�ɖ�����΃e�[�v�}�[�N��������
	if (!m_bBOT) {
		if ((nResult = WriteFmk(1)) != 0 && nResult != MT_FOUND_EOM)
			return nResult;
	}

	// �A�����[�h
	Unload();

	return 0;
}

//
//	�@�\	�F�@���x���L��Ńt�@�C���������ݏ���
//
//	����	�F	pFileName - �������ރf�B�X�N��̃t�@�C����
//				pFileID - �t�@�C�����ʖ�
//				pSystemID - �V�X�e�����ʖ�
//				nRecordLength - ���R�[�h��
//				nBlockingFactor - �u���b�N���W��
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�w�肳�ꂽ�t�@�C���̓��e��MT�Ƀ��x���L��ŏ������ށB
//
//	���l	�F	����
//
int CMTDriver::WriteFileWithLabel(LPCTSTR pFileName, LPCTSTR pFileID, LPCTSTR pSystemID, int nRecordLength, int nBlockingFactor)
{
	int nResult, nResult2;
	VOL1 *pVol1;

	m_sFileID = pFileID;
	m_sSystemID = pSystemID;
	m_nRecordLength = nRecordLength;
	m_nBlockingFactor = nBlockingFactor;
	m_nBlockLength = nRecordLength * nBlockingFactor;
	m_nBlockCount = 0;

	if (m_File.m_hFile == CFile::hFileNull) {
		if (!m_File.Open(pFileName, CFile::modeRead | CFile::shareDenyWrite))
			return RESULT_FILE_ERROR;

		m_sVolumeID = m_sVolumeID2;
		m_cVolumeStatus = '0';
	} else
		m_cVolumeStatus = '1';

	if (m_bBOT) {
		// Ready �҂�
		if ((nResult = WaitReady()) != 0)
			return nResult;

		// �����C���h
		if ((nResult = Rewind()) != 0)
			return nResult;

		// �P�u���b�N(VOL1)�ǂݍ���
		if ((nResult = ReadConvert()) < 0)
			return nResult;

		// �{�����[�����ʖ���ۑ�
		pVol1 = (VOL1 *)m_IOBuf;
		m_sVolumeID2 = CString(pVol1->VolumeID, sizeof(pVol1->VolumeID));
		if (m_nVolSeqNo == 1)
			m_sVolumeID = m_sVolumeID2;

		m_bBOT = FALSE;
	}

	// HDR1��������
	if ((nResult = WriteLabel1("HDR1")) != 0)
		return nResult;

	// HDR2��������
	if ((nResult = WriteLabel2("HDR2")) != 0)
		return nResult;

	// �e�[�v�}�[�N��������
	if ((nResult = WriteFmk(1)) != 0)
		return nResult;

	// �t�@�C���R�s�[
	if ((nResult = WriteFileToMT()) < 0)
		return nResult;

	// �e�[�v�}�[�N��������
	if ((nResult2 = WriteFmk(1)) != 0 && nResult2 != MT_FOUND_EOM)
		return nResult2;

	if (nResult == RESULT_FILE_END) {
		// EOF1��������
		if ((nResult2 = WriteLabel1("EOF1")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// EOF2��������
		if ((nResult2 = WriteLabel2("EOF2")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// �e�[�v�}�[�N��������
		if ((nResult2 = WriteFmk(1)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// �R�s�[���t�@�C���N���[�Y
		m_File.Close();

		// �t�@�C�������ԍ��X�V
		m_nFileSeqNo++;
	} else if (nResult == RESULT_VOLUME_END) {
		// EOV1��������
		if ((nResult2 = WriteLabel1("EOV1")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// EOV2��������
		if ((nResult2 = WriteLabel2("EOV2")) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// �e�[�v�}�[�N��������
		if ((nResult2 = WriteFmk(2)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// �A�����[�h
		if ((nResult2 = Unload()) != 0)
			return nResult2;

		// �{�����[�������ԍ��X�V
		m_nVolSeqNo++;

		m_bBOT = TRUE;
	}

	return nResult;
}

//
//	�@�\	�F�@���x�������Ńt�@�C���������ݏ���
//
//	����	�F	pFileName - �������ރf�B�X�N��̃t�@�C����
//				nRecordLength - ���R�[�h��
//				nBlockingFactor - �u���b�N���W��
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�w�肳�ꂽ�t�@�C���̓��e��MT�Ƀ��x�������ŏ������ށB
//
//	���l	�F	����
//
int CMTDriver::WriteFileNonLabel(LPCTSTR pFileName, int nRecordLength, int nBlockingFactor)
{
	int nResult, nResult2;

	// �u���b�N���ݒ�
	m_nBlockLength = nRecordLength * nBlockingFactor;

	// �R�s�[���t�@�C���I�[�v��
	if (m_File.m_hFile == CFile::hFileNull) {
		if (!m_File.Open(pFileName, CFile::modeRead | CFile::shareDenyWrite))
			return RESULT_FILE_ERROR;
	}

	if (m_bBOT) {
		// Ready �҂�
		if ((nResult = WaitReady()) != 0)
			return nResult;

		// �����C���h
		if ((nResult = Rewind()) != 0)
			return nResult;

		m_bBOT = FALSE;
	}

	// �t�@�C���R�s�[
	if ((nResult = WriteFileToMT()) < 0)
		return nResult;

	if (nResult == RESULT_FILE_END) {
		// �e�[�v�}�[�N��������
		if ((nResult2 = WriteFmk(1)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// �R�s�[���t�@�C���N���[�Y
		m_File.Close();
	} else if (nResult == RESULT_VOLUME_END) {
		// �e�[�v�}�[�N��������
		if ((nResult2 = WriteFmk(2)) != 0 && nResult2 != MT_FOUND_EOM)
			return nResult2;

		// �A�����[�h
		if ((nResult2 = Unload()) != 0)
			return nResult2;

		m_bBOT = TRUE;
	}

	return nResult;
}

//
//	�@�\	�F�@���x���L��Ńt�@�C���ǂݍ��ݏ���
//
//	����	�F	pDirName - �������ރf�B�X�N��̃f�B���N�g����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	���x���L���MT����t�@�C����ǂݍ��݁A�w�肳�ꂽ�f�B���N�g���ɏ������ށB
//
//	���l	�F	����
//
int CMTDriver::ReadFileWithLabel(LPCTSTR pDirName)
{
	int nResult, nResult2;
	VOL1 *pVol1;
	CString sFileID;
	CString sFileName;

	// �����C���h
	if ((nResult = Rewind()) != 0)
		return nResult;

	// �P�u���b�N(VOL1)�ǂݍ���
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "VOL1"�̃`�F�b�N
	pVol1 = (VOL1 *)m_IOBuf;
	if (CString(pVol1->LabelID, sizeof(pVol1->LabelID)) != "VOL1")
		return RESULT_MT_ERROR;

	while (true) {
		// �w�b�_�iHDR1,HDR2,TM)�`�F�b�N
		if ((nResult = CheckHeader(sFileID)) == MT_FOUND_FMK) {
			nResult = 0;
			break;
		} else if (nResult != 0)
			return nResult;

		// �R�s�[��t�@�C���I�[�v��
		sFileName = (CString)pDirName + "\\" + sFileID;
		if (!m_File.Open(sFileName, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareExclusive))
			return RESULT_FILE_ERROR;
		m_File.Seek(0, CFile::end);

		// �t�@�C���R�s�[
		if ((nResult = ReadFileFromMT()) != 0) {
			m_File.Close();
			return nResult;
		}

		// �R�s�[��t�@�C���N���[�Y
		m_File.Close();

		// EOF or EOV �`�F�b�N
		if ((nResult = CheckEOForEOV()) < 0)
			return nResult;

		// EOV�Ȃ烋�[�v�𔲂���
		if (nResult == RESULT_VOLUME_END)
			break;
	}

	// �A�����[�h
	if ((nResult2 = Unload()) != 0)
		return nResult2;

	return nResult;
}

//
//	�@�\	�F�@���x�������Ńt�@�C���ǂݍ��ݏ���
//
//	����	�F	pFileName - �������ރf�B�X�N��̃t�@�C����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	���x��������MT����t�@�C����ǂݍ��݁A�w�肳�ꂽ�t�@�C���ɏ������ށB
//
//	���l	�F	����
//
int CMTDriver::ReadFileNonLabel(LPCTSTR pFileName)
{
	int nResult;

	if (m_bBOT) {
		// Ready �҂�
		if ((nResult = WaitReady()) != 0)
			return nResult;

		// �����C���h
		if ((nResult = Rewind()) != 0)
			return nResult;

		m_bBOT = FALSE;
	}

	// �R�s�[��t�@�C���I�[�v��
	if (!m_File.Open(pFileName, CFile::modeCreate | CFile::modeNoTruncate | CFile::modeWrite | CFile::shareExclusive))
		return RESULT_FILE_ERROR;
	m_File.Seek(0, CFile::end);

	// �t�@�C���R�s�[
	if ((nResult = ReadFileFromMT()) != 0) {
		m_File.Close();
		return nResult;
	}

	// �R�s�[��t�@�C���N���[�Y
	m_File.Close();

	// �e�[�v�}�[�N�̃`�F�b�N
	if ((nResult = Read()) == MT_FOUND_FMK || nResult == MT_KEY_MEDIUMERR) {
		// �A�����[�h
		if ((nResult = Unload()) != 0)
			return nResult;

		// �{�����[���I��
		m_bBOT = TRUE;
		nResult = RESULT_VOLUME_END;
	} else if (nResult < 0) {
		// I/O�G���[
		return nResult;
	} else {
		// ��ǂ݃f�[�^�ۑ�
		m_nReadAhead = nResult;

		// �t�@�C���I��
		nResult = RESULT_FILE_END;
	}

	return nResult;
}

//
//	�@�\	�F�@�w�b�_(HDR1,HDR2,TM)�̃`�F�b�N����
//
//	����	�F	sFileID - �w�b�_�ɏ����ꂽ�t�@�C�����ʖ�
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	���x���L���MT����w�b�_��ǂݍ��ށB
//
//	���l	�F	����
//
int CMTDriver::CheckHeader(CString &sFileID)
{
	int nResult;
	LABEL1 *pLabel1;
	LABEL2 *pLabel2;

	// �P�u���b�N(HDR1)�ǂݍ���
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "HDR1"�̃`�F�b�N
	pLabel1 = (LABEL1 *)m_IOBuf;
	if (CString(pLabel1->LabelID, sizeof(pLabel1->LabelID)) != "HDR1")
		return RESULT_MT_ERROR;

	// �t�@�C�����ʖ��ۑ�
	sFileID = CString(pLabel1->FileID, sizeof(pLabel1->FileID));
	sFileID.TrimRight();

	// �P�u���b�N(HDR2)�ǂݍ���
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "HDR2"�̃`�F�b�N
	pLabel2 = (LABEL2 *)m_IOBuf;
	if (CString(pLabel2->LabelID, sizeof(pLabel2->LabelID)) != "HDR2")
		return RESULT_MT_ERROR;

	// �e�[�v�}�[�N�`�F�b�N
	if ((nResult = CheckTapeMark()) != 0)
		return nResult;

	return 0;
}

//
//	�@�\	�F�@EOF�܂���EOV�̃`�F�b�N����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	���x���L���MT����EOF�܂���EOV��ǂݍ��ށB
//
//	���l	�F	����
//
int CMTDriver::CheckEOForEOV()
{
	int nResult;
	LABEL1 *pLabel1;
	LABEL2 *pLabel2;
	CString sHeader;

	// �P�u���b�N(EOF1orEOV1)�ǂݍ���
	if ((nResult = ReadConvert()) < 0)
		return nResult;

	// "EOF1"or"EOV1"�̃`�F�b�N
	pLabel1 = (LABEL1 *)m_IOBuf;
	sHeader = CString(pLabel1->LabelID, sizeof(pLabel1->LabelID));
	if (sHeader == "EOF1") {
		// �P�u���b�N(EOF2)�ǂݍ���
		if ((nResult = ReadConvert()) < 0)
			return nResult;

		// "EOF2"�̃`�F�b�N
		pLabel2 = (LABEL2 *)m_IOBuf;
		if (CString(pLabel2->LabelID, sizeof(pLabel2->LabelID)) != "EOF2")
			return RESULT_MT_ERROR;

		// �e�[�v�}�[�N�`�F�b�N
		if ((nResult = CheckTapeMark()) != 0)
			return nResult;

		return RESULT_FILE_END;
	} else if (sHeader == "EOV1") {
		// �P�u���b�N(EOV2)�ǂݍ���
		if ((nResult = ReadConvert()) < 0)
			return nResult;

		// "EOV2"�̃`�F�b�N
		pLabel2 = (LABEL2 *)m_IOBuf;
		if (CString(pLabel2->LabelID, sizeof(pLabel2->LabelID)) != "EOV2")
			return RESULT_MT_ERROR;

		return RESULT_VOLUME_END;
	} else
		return RESULT_MT_ERROR;
}

//
//	�@�\	�F�@Ready�҂�����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT���u��Ready�ɂȂ�̂�҂B
//
//	���l	�F	����
//
int CMTDriver::WaitReady()
{
	int nResult;
	int i;

	// ���g���C���[�v
	for (i = 0; i < RETRY_READY; i++) {
		// Ready�`�F�b�N
		if ((nResult = MT_ready(m_hHandle, &m_StatusData)) == 0)
			break;

		// �P�b�҂�
		Sleep(1000);
	}

	if (nResult < 0)
		m_nErrorCode = nResult;

	return nResult;
}

//
//	�@�\	�F�@�t�@�C������MT�ɃR�s�[����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�t�@�C������MT�ɂP�u���b�N���R�s�[����B
//
//	���l	�F	����
//
int CMTDriver::WriteFileToMT()
{
	int nResult;
	int nRead;

	// �t�@�C������P�u���b�N���ǂ��CMT�ɏ���
	while ((nRead = m_File.Read(m_IOBuf, m_nBlockLength)) != 0) {
		m_nBlockCount++;
		if ((nResult = Write(nRead)) < 0) {
			if (nResult == MT_FOUND_EOM) {
				//m_File.Seek(-nRead, CFile::current);
				return RESULT_VOLUME_END;
			} else
				return nResult;
		}

		// �R�s�[���̃E�B���h�E�X�V
		if (m_pUpdateWnd != NULL)
			m_pUpdateWnd->UpdateWindow();
	}

	return RESULT_FILE_END;
}

//
//	�@�\	�F�@MT����t�@�C���ɃR�s�[����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT����t�@�C���ɂP�u���b�N���R�s�[����B
//
//	���l	�F	����
//
int CMTDriver::ReadFileFromMT()
{
	int nResult;

	// CMT����P�u���b�N���ǂ�Ńt�@�C���ɏ���
	while (true) {
		if (m_nReadAhead == 0) {
			// CMT����P�u���b�N�ǂݍ���
			if ((nResult = Read()) < 0)
				break;
		} else {
			// ��ǂ݂����f�[�^������
			nResult = m_nReadAhead;
			m_nReadAhead = 0;
		}

		try {
			m_File.Write(m_IOBuf, nResult);
		} catch (CFileException *fe) {
			fe->Delete();
			return RESULT_FILE_ERROR;
		}

		// �R�s�[���̃E�B���h�E�X�V
		if (m_pUpdateWnd != NULL)
			m_pUpdateWnd->UpdateWindow();

	}

	if (nResult != MT_FOUND_FMK)
		return nResult;

	return 0;
}

//
//	�@�\	�F�@HDR1,EOF1,EOV1�������ݏ���
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	HDR1�܂���EOF1�܂���EOV1��MT�ɏ������ށB
//
//	���l	�F	����
//
int CMTDriver::WriteLabel1(LPCTSTR pLabelID)
{
	LABEL1 *pLabel;
	int nResult;

	// ���x���P�o�b�t�@�X�y�[�X�N���A
	pLabel = (LABEL1 *)m_IOBuf;
	memset(pLabel, ' ', sizeof(LABEL1));

	// �e���ڂ̃Z�b�g
	SetString(pLabel->LabelID, pLabelID);
	SetString(pLabel->FileID, m_sFileID);
	SetString(pLabel->VolumeID, m_sVolumeID);
	SetNumber(pLabel->VolSeqNo, m_nVolSeqNo, sizeof(pLabel->VolSeqNo));
	SetNumber(pLabel->FileSeqNo, m_nFileSeqNo, sizeof(pLabel->FileSeqNo));
	//SetString(pLabel->GenerationNo, "0000");
	// takazawa 20001031
	//SetString(pLabel->VersionNo, "00");
	SetSystemDate(pLabel->CreationDate);
	SetString(pLabel->ExpirationDate, " 00000");
	pLabel->Accessibility[0] = '0';
	SetNumber(pLabel->BlockCount, m_nBlockCount % 1000000, sizeof(pLabel->BlockCount));
	SetString(pLabel->SystemCode, m_sSystemID);

	// CMT�ɏ�������
	if ((nResult = WriteConvert(sizeof(LABEL1))) < 0)
		return nResult;

	return 0;
}

//
//	�@�\	�F�@HDR2,EOF2,EOV2�������ݏ���
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	HDR2�܂���EOF2�܂���EOV2��MT�ɏ������ށB
//
//	���l	�F	����
//
int CMTDriver::WriteLabel2(LPCTSTR pLabelID)
{
	LABEL2 *pLabel;
	int nResult;

	// ���x���Q�o�b�t�@�X�y�[�X�N���A
	pLabel = (LABEL2 *)m_IOBuf;
	memset(pLabel, ' ', sizeof(LABEL2));

	// �e���ڂ̃Z�b�g
	SetString(pLabel->LabelID, pLabelID);
	pLabel->RecordFormat[0] = 'F';
	SetNumber(pLabel->BlockLength, m_nBlockLength, sizeof(pLabel->BlockLength));
	SetNumber(pLabel->RecordLength, m_nRecordLength, sizeof(pLabel->RecordLength));
	pLabel->VolumeStatus[0] = m_cVolumeStatus;
	SetString(pLabel->JobStep, "Mt_Sys/Mt_Ctl");
	pLabel->Blocking[0] = (m_nRecordLength == m_nBlockLength) ? ' ' : 'B';

	// CMT�ɏ�������
	if ((nResult = WriteConvert(sizeof(LABEL2))) < 0)
		return nResult;

	return 0;
}

//
//	�@�\	�F�@������Z�b�g����
//
//	����	�F	pBuf - �o�b�t�@
//				pStr - �Z�b�g���镶����
//
//	���A�l	�F	����
//
//	�@�\�����F	pBuf��pStr�̒��������R�s�[����B�i�I�[��'\0'�͖����j
//
//	���l	�F	����
//
void CMTDriver::SetString(LPTSTR pBuf, LPCTSTR pStr)
{
	strncpy(pBuf, pStr, strlen(pStr));
}

//
//	�@�\	�F�@���l�Z�b�g����
//
//	����	�F	pBuf - �o�b�t�@
//				nNum - �Z�b�g���鐔�l
//				nLength - ����
//
//	���A�l	�F	����
//
//	�@�\�����F	���l�� 9999 �`���Ńo�b�t�@�ɃZ�b�g����B
//
//	���l	�F	����
//
void CMTDriver::SetNumber(LPTSTR pBuf, int nNum, int nLength)
{
	CString sFmt, sNum;

	sFmt.Format("%%0%dd", nLength);
	sNum.Format(sFmt, nNum);
	strncpy(pBuf, sNum, nLength);
}

//
//	�@�\	�F�@�V�X�e�����t�Z�b�g����
//
//	����	�F	pBuf - �o�b�t�@
//
//	���A�l	�F	����
//
//	�@�\�����F	�o�b�t�@�ɃV�X�e�����t���Z�b�g����B
//
//	���l	�F	����
//
void CMTDriver::SetSystemDate(LPTSTR pBuf)
{
	CTime t = CTime::GetCurrentTime();
	CTimeSpan ts = t - CTime(t.GetYear(), 1, 1, 0, 0, 0);
	CString sDate;

	sDate.Format("%03d%03d", t.GetYear() % 1000, ts.GetDays() + 1);
	// takazawa
	strncpy(pBuf,sDate,6);
}

//
//	�@�\	�F�@�P�u���b�N�ǂݍ��݁��R�[�h�ϊ�����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	MT����P�u���b�N�ǂݍ��݁A�����EBCDIC����JIS�ɕϊ�����B
//
//	���l	�F	����
//
int CMTDriver::ReadConvert()
{
	int nResult;

	// �P�u���b�N�ǂݍ���
	if ((nResult = Read()) < 0)
		return nResult;

	// JIS�R�[�h�ɕϊ�
	EbcdicToJis(m_IOBuf, nResult);

	return nResult;
}

//
//	�@�\	�F�@�R�[�h�ϊ����P�u���b�N�������ݏ���
//
//	����	�F	nBufSize - �������ރu���b�N��
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	�o�b�t�@�̓��e��JIS����EBCDIC�ɕϊ����A�����MT�ɏ������ށB
//
//	���l	�F	����
//
int CMTDriver::WriteConvert(int nBufSize)
{
	// EBCDIC�R�[�h�ɕϊ�
	JisToEbcdic(m_IOBuf, nBufSize);

	// CMT�ɏ�������
	return Write(nBufSize);
}

//
//	�@�\	�F�@�e�[�v�}�[�N�`�F�b�N����
//
//	����	�F	����
//
//	���A�l	�F	���ʃR�[�h
//
//	�@�\�����F	���Ƀe�[�v�}�[�N�����݂��邩�`�F�b�N����B
//
//	���l	�F	����
//
int CMTDriver::CheckTapeMark()
{
	int nResult;

	// �P�u���b�N�ǂݍ���
	if ((nResult = Read()) != MT_FOUND_FMK) {
		if (nResult < 0)
			return nResult;
		else
			return RESULT_MT_ERROR;
	}

	return 0;
}

//
//	�@�\	�F�@EBCDIC����JIS�ɃR�[�h�ϊ�����
//
//	����	�F	buf - �o�b�t�@
//				len - buf �Ƀf�[�^��
//
//	���A�l	�F	����
//
//	�@�\�����F	�o�b�t�@�̓��e��EBCDIC����JIS�ɃR�[�h�ϊ�����B
//
//	���l	�F	����
//
void CMTDriver::EbcdicToJis(BYTE *buf, int len)
{
	MT_convert(MT_CONV_EBCDIK_A8, buf, len);
}

//
//	�@�\	�F�@JIS����EBCDIC�ɃR�[�h�ϊ�����
//
//	����	�F	buf - �o�b�t�@
//				len - buf �Ƀf�[�^��
//
//	���A�l	�F	����
//
//	�@�\�����F	�o�b�t�@�̓��e��JIS����EBCDIC�ɃR�[�h�ϊ�����B
//
//	���l	�F	����
//
void CMTDriver::JisToEbcdic(BYTE *buf, int len)
{
	MT_convert(MT_CONV_A8_EBCDIK, buf, len);
}
