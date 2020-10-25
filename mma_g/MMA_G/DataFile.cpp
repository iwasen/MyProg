//*****************************************************************************************************
//  1. �t�@�C����
//		DataFile.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�@�C���N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "DataFile.h"
#include "General.h"
#include "MMA_G.h"
#include <math.h>

// 077FD050-GYM961-0_007 2007/9/8 modified by yG ->
//static	char *gTitle = "No,Time,X-Axis(��g),Y-Axis(��g),Z-Axis(��g),D-Temp(��),X-Temp(��),Y-Temp(��),Z-Temp(��)\n";
static const char *gTitleMma = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),X-Axis(��g),Y-Axis(��g),Z-Axis(��g),D-Temp(��),X-Temp(��),Y-Temp(��),Z-Temp(��)\n";
static const char *gTitleMme = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),X-Axis(��g),Y-Axis(��g),Z-Axis(��g)\n";
static const char *gTitleAsu = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),X-Axis(��g),Y-Axis(��g),Z-Axis(��g)\n";
// 077FD050-GYM961-0_007 2007/9/8 modified by yG ->
static const char *gTitleTemp = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),D-Temp(��),X-Temp(��),Y-Temp(��),Z-Temp(��)\n";

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::CDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CDataFile::CDataFile()
{
	// �����o�ϐ���������
	m_pTempDACM = NULL;
	m_pTempX = NULL;
	m_pTempY = NULL;
	m_pTempZ = NULL;
	m_pDataX = NULL;
	m_pDataY = NULL;
	m_pDataZ = NULL;
	m_nDataSize = 0;
	m_fSampleRate = 0;
	m_bReadAccelData = FALSE;
	m_bReadTempData = FALSE;
	m_nDownRate = 1;
	m_nAllocSize = 0;
	m_fAllocFactor = 1.0;
	m_nReadSize = 0;
	m_bTempOnly = FALSE;
	m_nDataType = 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::~CDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CDataFile::~CDataFile()
{
	// �o�C�i���f�[�^�o�b�t�@�����
	FreeBuf();
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::ReadInfoFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���t�@�C���ǂݍ��ݏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pFilePath		[I] ���t�@�C���̃p�X��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::ReadInfoFile(LPCTSTR pFilePath)
{
	CStdioFile cFile;
	CMapStringToString cStringMap;
	CString sReadBuf;
	int nPos;
	CString sKey, sData;

	// ���t�@�C���I�[�v��
	if (!cFile.Open(pFilePath, CFile::modeRead | CFile::shareDenyNone)) {
		CGeneral::Alert("���t�@�C�����J���܂���B\n(%s)", pFilePath);
		return FALSE;
	}

	// ���e��MAP�ɓǂݍ���
	m_aDataFileName.RemoveAll();
	while (cFile.ReadString(sReadBuf)) {
		nPos = sReadBuf.Find('=');
		if (nPos > 0) {
			sKey = sReadBuf.Left(nPos);
			sData = sReadBuf.Mid(nPos + 1);

			if (sKey == "FILE")
				m_aDataFileName.Add(sData);
			else
				cStringMap.SetAt(sKey, sData);
		}
	}

	// MAP����K�v�ȏ������o��
	m_fSampleRate = atof(cStringMap["SAMPLERATE"]);
	m_sDatPath = cStringMap["PATH"];
	m_nSensorId = atoi(cStringMap["SENSORID"]);
	m_nGain = atoi(cStringMap["GAIN"]);
	m_nCutoff = atoi(cStringMap["CUTOFF"]);
	m_nSensorIdx = atoi(cStringMap["SENSORIDX"]);
	m_SensorPos = cStringMap["SENSORCOSYS"];
	m_IssConfig = cStringMap["ISSCONFIG"];

	// �J�n���Ԃ�N�����ƕb�ɕ���
	CString sStartTime = cStringMap["STARTTIME"];
	m_nStartYear = atoi(sStartTime.Mid(0, 4));
	m_nStartMonth = atoi(sStartTime.Mid(4, 2));
	m_nStartDay = atoi(sStartTime.Mid(6, 2));
	m_nStartMiliSecond = atoi(sStartTime.Mid(8, 8));
	m_fStartSecond = (double)m_nStartMiliSecond / 1000;

	// �J�n���Ԃ�\���p�J�n���ԂɃZ�b�g
	m_dStarttime.SetTime(m_nStartYear, m_nStartMonth, m_nStartDay, m_nStartMiliSecond);

	// �g�[�^�����Ԃ��擾
	if (!GetTotalTime())
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::GetTotalTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g�[�^�����Ԑ��擾
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::GetTotalTime()
{
	CFile cFile;
	CString sPathName;
	INFO_FILE_HEADER sHeader;

	m_nTotalDataSize = 0;

	// �f�[�^�t�@�C���̌��������[�v
	int nFiles = (int)m_aDataFileName.GetSize();
	for (int i = 0; i < nFiles; i++) {
		// �f�[�^�t�@�C�����I�[�v��
		sPathName.Format("%s\\%s", m_sDatPath, m_aDataFileName[i]);
		if (!cFile.Open(sPathName, CFile::modeRead | CFile::shareDenyNone)) {
			CGeneral::Alert("�f�[�^�t�@�C�����J���܂���B\n(%s)", sPathName);
			return FALSE;
		}

		// �f�[�^�t�@�C������w�b�_������ǂݍ���
		cFile.Read(&sHeader, sizeof(sHeader));

		cFile.Close();

		// �S�f�[�^�t�@�C���̃f�[�^�������Z
		m_nTotalDataSize += sHeader.nDataSize;
	}

	// �g�[�^�����Ԃ����߂�
	m_fTotalSecond = m_nTotalDataSize / m_fSampleRate;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::ReadDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���t�@�C���ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nBeginTime			[I] �ǂݍ��݊J�n���ԁi�b���j
//		int		nEndTime			[I] �ǂݍ��ݏI�����ԁi�b���j
//		BOOL	bReadAccelData		[I] �����x�f�[�^�ǂݍ��݃t���O
//		BOOL	bReadTempData		[I] ���x�f�[�^�ǂݍ��݃t���O
//		BOOL	bProgressBar		[I] �v���O���X�o�[�\���t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::ReadDataFile(int nBeginTime, int nEndTime, BOOL bReadAccelData, BOOL bReadTempData, BOOL bProgressBar)
{
	CProgressBar cProgressBar;

	m_bReadAccelData = bReadAccelData;
	m_bReadTempData = bReadTempData;

	// �o�C�i���f�[�^�T�C�Y�ݒ�
	m_nDataSize = (int)((nEndTime - nBeginTime) * m_fSampleRate);
	m_nDataOffset = (int)((nBeginTime * 1000 - m_nStartMiliSecond) * m_fSampleRate / 1000);
	if (m_nDataOffset < 0)
		m_nDataOffset = 0;

	// �o�C�i���f�[�^�o�b�t�@�m��
	AllocBuf();

	if (bProgressBar) {
		// �v���O���X�o�[��\�����A�ʃX���b�h�Ńt�@�C����ǂݍ���
		return cProgressBar.ExecProcess("�t�@�C���ǂݍ��ݒ�", 10, this, ReadBinaryFileThread);
	} else {
		// �f�[�^�t�@�C����ǂݍ���
		return ReadBinaryFileThread2(NULL);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::ReadWriteDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���t�@�C���ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCSTR	fname				[I] �t�@�C����
//		int		nBeginTime			[I] �ǂݍ��݊J�n���ԁi�b���j
//		int		nEndTime			[I] �ǂݍ��ݏI�����ԁi�b���j
//		BOOL	bReadTempData		[I] ���x�f�[�^�ǂݍ��݃t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::ReadWriteDataFile(LPCSTR fname, int nBeginTime, int nEndTime, BOOL bReadTempData)
{
	CProgressBar cProgressBar;

	m_bReadAccelData = TRUE;
	m_bReadTempData = bReadTempData;
	m_WriteFilePath = fname;
	m_nBeginTime = nBeginTime;

	// �o�C�i���f�[�^�T�C�Y�ݒ�
	m_nDataSize = (int)((nEndTime - nBeginTime) * m_fSampleRate);
	m_nDataOffset = (int)((nBeginTime * 1000 - m_nStartMiliSecond) * m_fSampleRate / 1000);

	// �o�C�i���f�[�^�o�b�t�@�m��
	AllocBuf();

	// �v���O���X�o�[��\�����A�ʃX���b�h�Ńt�@�C����ǂݍ���
	if (! cProgressBar.ExecProcess("�t�@�C���ǂݍ��ݒ�", 10, this, ReadBinaryFileThread))
		return FALSE;

	// �v���O���X�o�[��\�����A�ʃX���b�h�Ńt�@�C����ǂݍ���
	return cProgressBar.ExecProcess("�t�@�C���������ݒ�", 10, this, WriteBinaryFileThread);
}


//*****************************************************************************************************
//  1. �֐���
//		CDataFile::ReadBinaryFileThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���ǂݍ��݃X���b�h�̓����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID			pParam			[I] CDataFile�N���X�ւ̃|�C���^
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::ReadBinaryFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CDataFile *)pParam)->ReadBinaryFileThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::WriteBinaryFileThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���������݃X���b�h�̓����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID			pParam			[I] CDataFile�N���X�ւ̃|�C���^
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::WriteBinaryFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CDataFile *)pParam)->WriteBinaryFileThread2(pProgressBar);
}


//*****************************************************************************************************
//  1. �֐���
//		CDataFile::ReadBinaryFileThread2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���ǂݍ��݃X���b�h�̖{��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::ReadBinaryFileThread2(CProgressBar *pProgressBar)
{
	CFile cFile;
	CString sPathName;
	INFO_FILE_HEADER sHeader;

	// �ϐ���������
	int nSkipSize = m_nDataOffset + m_nReadSize;
	int nReadPtr = m_nReadSize;

	// �f�[�^�t�@�C���̌��������[�v
	int nFiles = (int)m_aDataFileName.GetSize();
	for (int i = 0; i < nFiles; i++) {
		// �f�[�^�t�@�C�����I�[�v��
		sPathName.Format("%s\\%s", m_sDatPath, m_aDataFileName[i]);
		if (!cFile.Open(sPathName, CFile::modeRead | CFile::shareDenyNone)) {
			CGeneral::Alert("�f�[�^�t�@�C�����J���܂���B");
			return FALSE;
		}

		// �f�[�^�t�@�C������w�b�_������ǂݍ���
		cFile.Read(&sHeader, sizeof(sHeader));

		// �ǂݔ�΂��T�C�Y���v�Z
		int nDataSize = sHeader.nDataSize;
		int nReadOffset = 0;
		if (nSkipSize > 0) {
			int nSkip = min(nSkipSize, nDataSize);
			nSkipSize -= nSkip;
			nReadOffset += nSkip;
			nDataSize -= nSkip;
		}

		// �ǂݔ�΂����I��������f�[�^��ǂݍ���
		if (nSkipSize == 0) {
			int nRead = min(m_nDataSize - nReadPtr, nDataSize);
			if (nRead > 0) {
				// �t�@�C������f�[�^��ǂݍ���
				double *pBuf = new double[nRead * 7];
				cFile.Seek(nReadOffset * 7 * sizeof(double), CFile::current);
				cFile.Read(pBuf, nRead * 7 * sizeof(double));

				// �f�[�^�o�b�t�@�ɃR�s�[
				double *pBuf2 = pBuf;
				for (int j = 0; j < nRead; j++) {
					// �e�f�[�^���o�b�t�@�ɃR�s�[
					if (m_bReadTempData) {
						m_pTempDACM[nReadPtr] = *pBuf2++;
						m_pTempX[nReadPtr] = *pBuf2++;
						m_pTempY[nReadPtr] = *pBuf2++;
						m_pTempZ[nReadPtr] = *pBuf2++;
					} else
						pBuf2 += 4;

					if (m_bReadAccelData) {
						m_pDataX[nReadPtr] = *pBuf2++;
						m_pDataY[nReadPtr] = *pBuf2++;
						m_pDataZ[nReadPtr] = *pBuf2++;
					} else
						pBuf2 += 3;

					// �v���O���X�o�[���X�V
					if (pProgressBar != NULL) {
						if (!pProgressBar->SetProgress(nReadPtr * 10 / m_nDataSize + 1)) {
							// �f�[�^�t�@�C�����N���[�Y
							cFile.Close();

							delete [] pBuf;
							return FALSE;
						}
					}

					nReadPtr++;
				}

				delete [] pBuf;
			}
		}

		// �f�[�^�t�@�C�����N���[�Y
		cFile.Close();

		// �K�v�ȃT�C�Y�ǂݍ��񂾂�I��
		if (nReadPtr >= m_nDataSize)
			break;
	}

	// �ǂݍ��ݍς݂̃T�C�Y��ۑ�
	m_nReadSize = nReadPtr;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::WriteBinaryFileThread2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t�@�C���������݃X���b�h�̖{��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[�N���X�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE�F����@FALSE�F�G���[
//*****************************************************************************************************
BOOL CDataFile::WriteBinaryFileThread2(CProgressBar *pProgressBar)
{
	CFile cWFile;
	int	i, nWrite;
	CString str, sText;

	// �������݃t�@�C���I�[�v��
	if (!cWFile.Open(m_WriteFilePath, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		CGeneral::Alert("�������݃t�@�C���������܂���B");
		return FALSE;
	}

// 077FD050-GYM961-0_007 2007/9/8 appended by yG ->
	// CSV�t�@�C���̐擪�Ɍv������t������
	str.Format(m_nDataType == DATA_TYPE_MMA ? "TAA ID:%d\n" : "Sensor ID:%d\n", m_nSensorId);
	cWFile.Write(str, str.GetLength());
	str.Format("Time Zero:%s\n", m_dStarttime.GetStr());
	cWFile.Write(str, str.GetLength());
	str.Format("Sampling Rate:%.2f[Hz]\n", m_fSampleRate);
	cWFile.Write(str, str.GetLength());
	if (m_nDataType == DATA_TYPE_MMA) {
		str.Format("Cut Off Freq:%d[Hz]\n", m_nCutoff);
		cWFile.Write(str, str.GetLength());
		str.Format("Gain:%d\n", m_nGain);
		cWFile.Write(str, str.GetLength());
	}
	str.Format("Sensor Position:%s\n", m_SensorPos);
	cWFile.Write(str, str.GetLength());
// 077FD050-GYM961-0_007 2007/9/8 appended by yG <-

	if (m_bTempOnly)
		str = gTitleTemp;
	else {
		switch (m_nDataType) {
		case DATA_TYPE_MMA:
			str = gTitleMma;
			break;
		case DATA_TYPE_MME:
			str = gTitleMme;
			break;
		case DATA_TYPE_ASU:
			str = gTitleAsu;
			break;
		}
	}
	cWFile.Write(str, str.GetLength());
	nWrite = 0;

	// CSV�t�@�C���Ƀf�[�^���o��
	if (m_bTempOnly)	// ���x�f�[�^�̂�
	{
		for (i = 0; i < m_nDataSize; i += (m_nDownRate*40)) {
			CDataDateTime Begin;
			Begin.SetTime(m_dStarttime.m_YY
				, m_dStarttime.m_MM
				, m_dStarttime.m_DD
				, (int)((m_nBeginTime + i / m_fSampleRate) * 1000));
			sText = Begin.GetStrYMD();
			sText += CGeneral::FormatTime(m_nBeginTime + i / m_fSampleRate, 4);
			str.Format("%d,%s,%f,%f,%f,%f\n", nWrite + 1
				, sText
				, m_pTempDACM[i]
				, m_pTempX[i]
				, m_pTempY[i]
				, m_pTempZ[i]);
			cWFile.Write(str, str.GetLength());

			if ((nWrite % 10000) == 0) {
				// �v���O���X�o�[���X�V
				if (!pProgressBar->SetProgress(nWrite * 10 / (m_nDataSize / (m_nDownRate*40)) + 1)) {
					// �f�[�^�t�@�C�����N���[�Y
					cWFile.Close();
					return FALSE;
				}
			}
			nWrite++;
		}
	}
	else	// �S�f�[�^
	{
		for (i = 0; i < m_nDataSize; i += m_nDownRate) {
			CDataDateTime Begin;
			Begin.SetTime(m_dStarttime.m_YY
				, m_dStarttime.m_MM
				, m_dStarttime.m_DD
				, (int)((m_nBeginTime + i / m_fSampleRate) * 1000));
			sText = Begin.GetStrYMD();
			sText += CGeneral::FormatTime(m_nBeginTime + i / m_fSampleRate, 4);
			switch (m_nDataType) {
			case DATA_TYPE_MMA:
				str.Format("%d,%s,%f,%f,%f,%f,%f,%f,%f\n", nWrite + 1
					, sText
					, m_pDataX[i]
					, m_pDataY[i]
					, m_pDataZ[i]
					, m_pTempDACM[i]
					, m_pTempX[i]
					, m_pTempY[i]
					, m_pTempZ[i]);
				break;
			case DATA_TYPE_MME:
			case DATA_TYPE_ASU:
				str.Format("%d,%s,%f,%f,%f\n", nWrite + 1
					, sText
					, m_pDataX[i]
					, m_pDataY[i]
					, m_pDataZ[i]);
				break;
			}
			cWFile.Write(str, str.GetLength());

			if ((nWrite % 10000) == 0) {
				// �v���O���X�o�[���X�V
				if (!pProgressBar->SetProgress(nWrite * 10 / (m_nDataSize / m_nDownRate) + 1)) {
					// �f�[�^�t�@�C�����N���[�Y
					cWFile.Close();
					return FALSE;
				}
			}
			nWrite++;
		}
	}
	// �f�[�^�t�@�C�����N���[�Y
	cWFile.Close();

	pProgressBar->SetProgress(10);

	AfxMessageBox("CSV�t�@�C���ւ̏o�͂��������܂����B",MB_ICONINFORMATION|MB_OK);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::AllocBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�o�b�t�@�m�ۏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDataFile::AllocBuf()
{
	if (m_nDataSize <= m_nAllocSize)
		return;

	int nAllocSize = (int)(m_nDataSize * m_fAllocFactor);

	// �����x�f�[�^�o�b�t�@�m��
	if (m_bReadAccelData) {
		m_pDataX = ReallocBuf(m_pDataX, m_nAllocSize, nAllocSize);
		m_pDataY = ReallocBuf(m_pDataY, m_nAllocSize, nAllocSize);
		m_pDataZ = ReallocBuf(m_pDataZ, m_nAllocSize, nAllocSize);
	}

	// ���x�f�[�^�o�b�t�@�m��
	if (m_bReadTempData) {
		m_pTempDACM = ReallocBuf(m_pTempDACM, m_nAllocSize, nAllocSize);
		m_pTempX = ReallocBuf(m_pTempX, m_nAllocSize, nAllocSize);
		m_pTempY = ReallocBuf(m_pTempY, m_nAllocSize, nAllocSize);
		m_pTempZ = ReallocBuf(m_pTempZ, m_nAllocSize, nAllocSize);
	}

	m_nAllocSize = nAllocSize;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::ReallocBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�o�b�t�@�Ċm�ۏ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		double	*pOldBuf	[I] �m�ۍς݂̃o�b�t�@�|�C���^
//		int		nOldSize	[I] �m�ۍς݂̃o�b�t�@�T�C�Y
//		int		nNewSize	[I] �V�����m�ۂ���o�b�t�@�T�C�Y
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		double*		�V�����m�ۂ����o�b�t�@�|�C���^
//*****************************************************************************************************
double *CDataFile::ReallocBuf(const double *pOldBuf, int nOldSize, int nNewSize)
{
	double *pNewBuf = new double[nNewSize];

	if (pOldBuf != NULL) {
		memcpy(pNewBuf, pOldBuf, nOldSize * sizeof(double));
		delete [] pOldBuf;
	}

	return pNewBuf;
}

//*****************************************************************************************************
//  1. �֐���
//		CDataFile::FreeBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���f�[�^�o�b�t�@�������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CDataFile::FreeBuf()
{
	if (m_pTempDACM != NULL) {
		delete [] m_pTempDACM;
		m_pTempDACM = NULL;
	}

	if (m_pTempX != NULL) {
		delete [] m_pTempX;
		m_pTempX = NULL;
	}

	if (m_pTempY != NULL) {
		delete [] m_pTempY;
		m_pTempY = NULL;
	}

	if (m_pTempZ != NULL) {
		delete [] m_pTempZ;
		m_pTempZ = NULL;
	}

	if (m_pDataX != NULL) {
		delete [] m_pDataX;
		m_pDataX = NULL;
	}

	if (m_pDataY != NULL) {
		delete [] m_pDataY;
		m_pDataY = NULL;
	}

	if (m_pDataZ != NULL) {
		delete [] m_pDataZ;
		m_pDataZ = NULL;
	}
}
