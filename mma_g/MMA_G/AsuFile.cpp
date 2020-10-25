//*****************************************************************************************************
//  1. �t�@�C����
//		AsuFile.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ASU�f�[�^�t�@�C���ǂݍ��ݏ����N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2016.02.01 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "AsuFile.h"
#include "MMA_G.h"
#include "General.h"
#include "GlobalData.h"
#include "shlwapi.h"

CAsuFile::CAsuFile(void)
{
}

CAsuFile::~CAsuFile(void)
{
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::ReadSensorInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		asu.ini�t�@�C������Z���T�[����ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAsuFile::ReadSensorInfo()
{
	static const char sensor[] = {"SENSOR_INFO"};
	char buf[256];

	// ini�t�@�C���p�X���擾
	CString sIniFileName;
	sIniFileName.Format("%s\\%s", gExePath, ASU_INI_FILE);

	g_pSensorInfoAsu = new SENSOR_INFO;
	memset(g_pSensorInfoAsu, 0, sizeof(SENSOR_INFO));

	GetPrivateProfileString(sensor, "X", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->x = atof(buf);

	GetPrivateProfileString(sensor, "Y", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->y = atof(buf);

	GetPrivateProfileString(sensor, "Z", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->z = atof(buf);

	GetPrivateProfileString(sensor, "Roll", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->roll = atof(buf);

	GetPrivateProfileString(sensor, "Pitch", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->pitch = atof(buf);

	GetPrivateProfileString(sensor, "Yaw", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->yaw = atof(buf);
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::CheckDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�H���_�̒����`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR			pDataFile		[I] �f�[�^�t�@�C���p�X��
//		double			&fSampleRate	[O] �T���v�����O���[�g
//		CDataDateTime	&oStartTime		[O] �v���J�n����
//		CDataDateTime	&oEndTime		[O] �v���I������
//		int				&nMeasTime		[O] �v���b��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:�G���[
//*****************************************************************************************************
BOOL CAsuFile::CheckDataFile(LPCTSTR pDataFile, double &fSampleRate, CDataDateTime &oStartTime, CDataDateTime &oEndTime, int &nMeasTime)
{
	m_sDataFile = pDataFile;

	CStdioFile oFile;
	if (!oFile.Open(pDataFile, CFile::modeRead | CFile::shareDenyWrite, NULL)) {
		CGeneral::Alert("ASU�f�[�^�t�@�C�����J���܂���B");
		return FALSE;
	}

	// �T���v�����O���[�g�A�v�����Ԏ擾�̂��߃t�@�C����ǂݍ���
	CString sData;
	CDataDateTime oTime, oSecondDataTime;
	double fDataX, fDataY, fDataZ;
	int nCount = 0;
	while (oFile.ReadString(sData) != NULL) {
		if (GetData(sData, oTime, fDataX, fDataY, fDataZ)) {
			switch (nCount) {
			case 0:
				m_oStartTime = oTime;
				break;
			case 1:
				oSecondDataTime = oTime;
				break;
			case 2:
				// �����̍������̂��߁A�r�����X�L�b�v����
				try {
					oFile.Seek(-200, CFile::end);
				} catch (CException *) {
				}
				break;
			}

			m_oEndTime = oTime;

			nCount++;
		}
	}

	// �L���f�[�^���݃`�F�b�N
	if (nCount < 2) {
		CGeneral::Alert("�t�@�C���̒��ɗL���ȃf�[�^�����݂��Ȃ����A�܂��̓f�[�^���s���ł��B");
		return FALSE;
	}

	// �ϊ��ς݃t�@�C�����݃`�F�b�N
	m_bOverWrite = CheckExistFile(m_oStartTime, m_oEndTime);

	// �T���v�����O���[�g�Z�o
	m_nSampleSpan = (oSecondDataTime - m_oStartTime) + (oSecondDataTime.m_ttt - m_oStartTime.m_ttt);
	m_fSampleRate = 1000.0 / m_nSampleSpan;
	fSampleRate = m_fSampleRate;

	// �v�����ԎZ�o
	nMeasTime = (m_oEndTime - m_oStartTime) / 1000;

	// �J�n�I������
	oStartTime = m_oStartTime;
	oEndTime = m_oEndTime;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::GetData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�@�C���̍s����e�������o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString &sData			[I] �f�[�^�t�@�C�����̂P�s
//		CDataDateTime &oTime	[O] �v������
//		double &fDataX			[O] X�������x�f�[�^
//		double &fDataY			[O] Y�������x�f�[�^
//		double &fDataZ			[O] Z�������x�f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:�G���[
//*****************************************************************************************************
BOOL CAsuFile::GetData(const CString &sData, CDataDateTime &oTime, double &fDataX, double &fDataY, double &fDataZ)
{
	// �e�f�[�^���擾����
	int nYear, nMonth, nDay, nHour, nMinute, nSecond, nMilliSecond;
	if (sscanf_s(sData, "%d/%d/%d %d:%d:%d.%d,%lf,%lf,%lf", &nMonth, &nDay, &nYear, &nHour, &nMinute, &nSecond, &nMilliSecond, &fDataX, &fDataY, &fDataZ) != 10)
		return FALSE;

	// �N���`�F�b�N
	if (nYear < 1900)
		return FALSE;

	// �����̃f�[�^�𐶐�
	oTime.SetTime(nYear, nMonth, nDay, nHour, nMinute, nSecond, nMilliSecond);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::CheckExistFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�C�i���ϊ���̃t�@�C�������ɑ��݂��邩�`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDataDateTime	&oStartTime		[I] �v���J�n����
//		CDataDateTime	&oEndTime		[I] �v���I������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:���݂���@FALSE:���݂��Ȃ�
//*****************************************************************************************************
BOOL CAsuFile::CheckExistFile(const CDataDateTime &oStartTime, const CDataDateTime &oEndTime)
{
	CString sFolderPath;

	GetOutputFolderName(oStartTime, oEndTime, sFolderPath);
	return ::PathFileExists(sFolderPath);
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::GetOutputFolderName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�̓t�H���_�p�X���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDataDateTime	&oStartTime		[I] �v���J�n����
//		CDataDateTime	&oEndTime		[I] �v���I������
//		CString			&sFolderPath	[O] �o�̓t�H���_�p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	����
//*****************************************************************************************************
void CAsuFile::GetOutputFolderName(const CDataDateTime &oStartTime, const CDataDateTime &oEndTime, CString &sFolderPath)
{
	sFolderPath.Format("%s\\ASU\\%04d_%02d_%02d_-%02d_%02d_%02d_%03d_%04d%02d%02d%02d%02d%02d",
			gRootData,
			oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, oStartTime.m_hh, oStartTime.m_mm, oStartTime.m_ss, oStartTime.m_ttt,
			oEndTime.m_YY, oEndTime.m_MM, oEndTime.m_DD, oEndTime.m_hh, oEndTime.m_mm, oEndTime.m_ss, oEndTime.m_ttt);
}
//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::ConvDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ASU�f�[�^�t�@�C����ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~�܂��̓G���[
//*****************************************************************************************************
BOOL CAsuFile::ConvDataFile()
{
	CProgressBar cProgressBar;

	return cProgressBar.ExecProcess("�ǂݍ��ݏ�����", 400, this, ConvDataThread);
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::ConvDataThread
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ������X���b�h�̓����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPVOID			pParam			[I] CMmeFile�N���X�ւ̃|�C���^�ithis�|�C���^�j
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~�܂��̓G���[
//*****************************************************************************************************
BOOL CAsuFile::ConvDataThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CAsuFile *)pParam)->ConvDataThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::ConvDataThread2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ������X���b�h�̖{��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CProgressBar	*pProgressBar	[I] �v���O���X�o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~�܂��̓G���[
//*****************************************************************************************************
BOOL CAsuFile::ConvDataThread2(CProgressBar *pProgressBar)
{
	// �f�[�^�x�[�X��������
	if (!WriteDatabase(pProgressBar))
		return FALSE;

	// �ϊ��ς݃t�@�C�����݃`�F�b�N
	m_bOverWrite = CheckExistFile(m_oStartTime, m_oEndTime);

	pProgressBar->SetProgress(400);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::WriteDatabase
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ASU�f�[�^�t�@�C���̓ǂݍ��݂��s���A�f�[�^�x�[�X�t�H���_�Ƀt�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CProgressBar	*pProgressBar		[I] �v���O���X�o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~
//*****************************************************************************************************
BOOL CAsuFile::WriteDatabase(CProgressBar *pProgressBar)
{
	CString sMeasTime;
	CString sFolderPath;
	CString sFilePath;
	CString sFileName;
	CStringArray saDataFilePath;
	INT64 nProgressCount = 0;
	CDataDateTime oTime;
	double fDataX, fDataY, fDataZ;
	CString sData;

	CStdioFile oFile;
	if (!oFile.Open(m_sDataFile, CFile::modeRead | CFile::shareDenyWrite, NULL)) {
		CGeneral::Alert("ASU�f�[�^�t�@�C�����J���܂���B");
		return FALSE;
	}

	// �v���O���X�o�[�\���p�ɑS�f�[�^���擾
	m_nTotalDataCount = ((m_oEndTime - m_oStartTime) + (m_oEndTime.m_ttt - m_oStartTime.m_ttt)) / m_nSampleSpan;

	// �o�̓t�H���_��
	GetOutputFolderName(m_oStartTime, m_oEndTime, sFolderPath);

	// �f�B���N�g���쐬
	CGeneral::CreateDirectoryAll(sFolderPath);

	// Idb�t�@�C���ݒ�
	SetIdbFile(sFolderPath, m_oStartTime, m_oEndTime);

	// �P���Ԗ��ɍH�w�l�ϊ��t�@�C������������
	UINT n1HourDataSize = (int)(m_fSampleRate * 3600);
	SAccData *pDataBuf = new SAccData[n1HourDataSize];
	SAccData oLastData = {0};
	CDataDateTime oDataTime = m_oStartTime;
	BOOL bRead = FALSE;
	BOOL bEof = FALSE;
	for (UINT nDataOffset = 0; ; nDataOffset += n1HourDataSize) {
		// ASU�f�[�^�t�@�C���ǂݍ���
		UINT nDataCount;
		CDataDateTime oCurrentTime = oDataTime;
		for (nDataCount = 0; nDataCount < n1HourDataSize; nDataCount++) {
			// �v���O���X�o�[�\��
			nProgressCount += 3;
			if (pProgressBar != NULL && !pProgressBar->SetProgress((int)((INT64)100 * nProgressCount / m_nTotalDataCount)))
				return FALSE;

			// �f�[�^�t�@�C������ǂݍ���
			if (!bRead) {
				while (true) {
					if (oFile.ReadString(sData) == NULL) {
						bEof = TRUE;
						break;
					}

					if (GetData(sData, oTime, fDataX, fDataY, fDataZ)) {
						bRead = TRUE;
						break;
					}
				}
			}

			// EOF�Ȃ�I��
			if (bEof)
				break;

			// �v���I�������ɒB������I��
			if (CompareDateTime(oTime, m_oEndTime) >= 0)
				break;

			// �����`�F�b�N
			if (CompareDateTime(oTime, oCurrentTime) <= 0) {
				oLastData.fDataX = fDataX;
				oLastData.fDataY = fDataY;
				oLastData.fDataZ = fDataZ;

				bRead = FALSE;
			}

			// �o�b�t�@�Ƀf�[�^���Z�b�g
			pDataBuf[nDataCount] = oLastData;

			oCurrentTime += m_nSampleSpan;
		}

		// �f�[�^��������ΏI��
		if (nDataCount == 0)
			break;

		// �o�̓t�@�C�����擾
		CDataDateTime oEndTime = oDataTime + (nDataCount * m_nSampleSpan);
		sFileName.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d%c%04d_%02d_%02d_%02d_%02d_%02d.%03d",
				oDataTime.m_YY, oDataTime.m_MM, oDataTime.m_DD, oDataTime.m_hh, oDataTime.m_mm, oDataTime.m_ss, oDataTime.m_ttt,
				nDataOffset == 0 ? '-' : '+',
				oEndTime.m_YY, oEndTime.m_MM, oEndTime.m_DD, oEndTime.m_hh, oEndTime.m_mm, oEndTime.m_ss, oEndTime.m_ttt);
		sFilePath.Format("%s\\%s", sFolderPath, sFileName);

		// �f�[�^�t�@�C����������
		if (!WriteDataFile(sFilePath, pDataBuf, nDataOffset, nDataCount, pProgressBar, nProgressCount))
			return FALSE;

		// XML�t�@�C����������
		WriteXmlFile(sFileName, oCurrentTime);

		// �f�[�^�t�@�C�����ۑ�
		m_oIdbFile.AddFile(sFileName);

		// �P���ԁi3600 * 1000 m�b�j���Z
		oDataTime += 3600 * 1000;
	}

	delete [] pDataBuf;

	// idb�t�@�C����������
	WriteIdbFile(m_oStartTime);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::CompareDateTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����̃f�[�^���r����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDataDateTime	&oDate1		[I] ��r�������
//		CDataDateTime	&oDate2		[I] ��r�������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int			0�F��v�@���̒l�FoData1�̕����傫���@���̒l�FoData2�̕����傫��
//*****************************************************************************************************
int CAsuFile::CompareDateTime(const CDataDateTime &oDate1, const CDataDateTime &oDate2)
{
	if (oDate1.m_YY != oDate2.m_YY)
		return oDate1.m_YY - oDate2.m_YY;

	if (oDate1.m_MM != oDate2.m_MM)
		return oDate1.m_MM - oDate2.m_MM;

	if (oDate1.m_DD != oDate2.m_DD)
		return oDate1.m_DD - oDate2.m_DD;

	if (oDate1.m_hh != oDate2.m_hh)
		return oDate1.m_hh - oDate2.m_hh;

	if (oDate1.m_mm != oDate2.m_mm)
		return oDate1.m_mm - oDate2.m_mm;

	if (oDate1.m_ss != oDate2.m_ss)
		return oDate1.m_ss - oDate2.m_ss;

	if (oDate1.m_ttt != oDate2.m_ttt)
		return oDate1.m_ttt - oDate2.m_ttt;

	return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::SetIdbFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C���ɏ����Z�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sFolderPath	[I] �o�̓t�H���_�p�X
//		CDataDateTime	&oStartTime		[I] �v���J�n����
//		CDataDateTime	&oEndTime		[I] �v���I������
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAsuFile::SetIdbFile(const CString &sFolderPath, const CDataDateTime &oStartTime, const CDataDateTime &oEndTime)
{
	CString sBuf;

	// �f�[�^����
	m_oIdbFile.DelAll();

	// �Z���T�[IDX
	m_oIdbFile.SetSensorIdx(0);

	// �R�����g
	m_oIdbFile.SetComment(m_sComment);

	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	m_oIdbFile.SetStartTime(oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, (((oStartTime.m_hh * 60) + oStartTime.m_mm) * 60 + oStartTime.m_ss) * 1000 + oStartTime.m_ttt);

	// �T���v�����[�g
	m_oIdbFile.SetSampleRate(m_fSampleRate);

	// ����
	sBuf.Format("%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d",
			oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, oStartTime.m_hh, oStartTime.m_mm, oStartTime.m_ss,
			oEndTime.m_YY, oEndTime.m_MM, oEndTime.m_DD, oEndTime.m_hh, oEndTime.m_mm, oEndTime.m_ss);
	m_oIdbFile.SetPeriod(sBuf);

	// �_�E�������N��
	m_oIdbFile.SetDownlink(m_sDownlink);

	// �Z���T�[ID
	m_oIdbFile.SetSensorId(1);

	// �^�C���[��
	sBuf.Format("%04d%02d%02d%02d%02d%02d", oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, oStartTime.m_hh, oStartTime.m_mm, oStartTime.m_ss);
	m_oIdbFile.SetTimeZero(sBuf);

	// Sensor Co Sys
	sBuf.Format("X=%g, Y=%g, Z=%g, Roll=%g, Pitch=%g, Yaw=%g",
			g_pSensorInfoAsu->x,
			g_pSensorInfoAsu->y,
			g_pSensorInfoAsu->z,
			g_pSensorInfoAsu->roll,
			g_pSensorInfoAsu->pitch,
			g_pSensorInfoAsu->yaw);
	m_oIdbFile.SetSensorcosys(sBuf);

	// Data Co Sys
	m_oIdbFile.SetDatacosys(sBuf);

	// �ۑ��p�X��
	m_oIdbFile.SetPath(sFolderPath);
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::WriteDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ��t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sFilePath			[I] �o�̓t�@�C���p�X
//		SAccData		*pDataBuf			[I] �f�[�^�o�b�t�@
//		UINT			nDataOffset			[I] �f�[�^�I�t�Z�b�g
//		UINT			nWriteDataSize		[I] �������݃f�[�^��
//		CProgressBar	*pProgressBar		[I] �v���O���X�o�[
//		INT64			nProgressCount		[I/O] �v���O���X�o�[�J�E���g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~
//*****************************************************************************************************
BOOL CAsuFile::WriteDataFile(const CString &sFilePath, SAccData *pDataBuf, UINT nDataOffset, UINT nWriteDataSize, CProgressBar *pProgressBar, INT64 &nProgressCount)
{
	// �H�w�l�ϊ��t�@�C���̃w�b�_
	struct {
		INT32 nSeqNo;
		INT32 nDataSize;
		INT32 nDataSizeHour;
		char sFiller[4];
	} oHeader;

	CFile oFile;
	if (oFile.Open(sFilePath, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		// �w�b�_��������
		memset(&oHeader, 0, sizeof(oHeader));
		oHeader.nSeqNo = (int)(nDataOffset / m_fSampleRate) + 1;
		oHeader.nDataSize = nWriteDataSize;
		oHeader.nDataSizeHour = (int)(m_fSampleRate * 3600);
		oFile.Write(&oHeader, sizeof(oHeader));

		// �f�[�^��������
		double aWriteBuf[7];
		memset(aWriteBuf, 0, sizeof(aWriteBuf));
		for (UINT nDataCount = 0; nDataCount < nWriteDataSize; nDataCount++) {
			aWriteBuf[4 + 0] = pDataBuf[nDataCount].fDataX * (1000 * 1000);
			aWriteBuf[4 + 1] = pDataBuf[nDataCount].fDataY * (1000 * 1000);
			aWriteBuf[4 + 2] = pDataBuf[nDataCount].fDataZ * (1000 * 1000);

			// �v���O���X�o�[�\��
			nProgressCount++;
			if (pProgressBar != NULL && !pProgressBar->SetProgress((int)((INT64)100 * nProgressCount / m_nTotalDataCount)))
				return FALSE;

			// �t�@�C���ɏ�������
			oFile.Write(aWriteBuf, sizeof(aWriteBuf));
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::WriteXmlFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		XML�t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sFileName				[I] �o�̓t�@�C����
//		CDataDateTime	&oDataStartTime			[I] �f�[�^�̊J�n����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAsuFile::WriteXmlFile(const CString &sFileName, const CDataDateTime &oDataStartTime)
{
	CString sBuf;

	// �v���J�n������ҏW
	sBuf.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d",
			oDataStartTime.m_YY, oDataStartTime.m_MM, oDataStartTime.m_DD,
			oDataStartTime.m_hh, oDataStartTime.m_mm, oDataStartTime.m_ss, oDataStartTime.m_ttt);
	m_oIdbFile.SetTimeZeroXML(sBuf);

	// XML�t�@�C������������
	m_oIdbFile.WriteXml(sFileName, DATA_TYPE_ASU);
}

//*****************************************************************************************************
//  1. �֐���
//		CAsuFile::WriteIdbFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CDataDateTime	&oDataStartTime		[I] �f�[�^�̊J�n����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CAsuFile::WriteIdbFile(const CDataDateTime &oDataStartTime)
{
	// �t�@�C������ҏW
	CString sFileName;
	sFileName.Format("%04d%02d%02d%d", oDataStartTime.m_YY, oDataStartTime.m_MM, oDataStartTime.m_DD, (((oDataStartTime.m_hh * 60) + oDataStartTime.m_mm) * 60 + oDataStartTime.m_ss) * 1000 + oDataStartTime.m_ttt);

	// idb�t�@�C���ɏ�������
	m_oIdbFile.Write(sFileName);
}
