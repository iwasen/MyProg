//*****************************************************************************************************
//  1. �t�@�C����
//		MmeFile.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�H�w�l�ϊ������N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2013.10.22 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "StdAfx.h"
#include "MmeFile.h"
#include "MMA_G.h"
#include "GlobalData.h"
#include "General.h"
#include "shlwapi.h"

// MME��ini�t�@�C����
#define MME_INI_FILE "mme.ini"

// �o�C�g�f�[�^���烏�[�h�f�[�^�֕ϊ�
#define GETWORD(b1, b2) (((WORD)(b1) << 8) | (b2))
#define GETDWORD(b1, b2, b3, b4) (((((((DWORD)(b1) << 8) | (b2)) << 8) | (b3)) << 8) | (b4))

// �p�P�b�g�f�[�^�T�C�Y
#define PACKET_TIME_SIZE	8
#define USER_HEADER_SIZE	16
#define ACC_DATA_SIZE		20

// �ő�t�@�C���T�C�Y(1.2GB -> 5.0GB)
//#define MAX_DATA_SIZE	(ULONGLONG)(1.2 * 1024 * 1024 * 1024)
#define MAX_DATA_SIZE	(ULONGLONG)(5.0 * 1024 * 1024 * 1024)	// 2014/04/14 modified by y.GOTO

CMmeFile::CMmeFile(void)
{
	memset(m_nSamplingRate, 0, sizeof(m_nSamplingRate));
	m_bOverWrite = FALSE;
}

CMmeFile::~CMmeFile(void)
{
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::CheckDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�H���_�̒����`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pDataFolder		[I] �f�[�^�t�H���_�p�X��
//		CTime		&oStartTime		[O] �v���J�n����
//		CTime		&oEndTime		[O] �v���I������
//		int			&nMeasTime		[O] �v���b��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:�G���[
//*****************************************************************************************************
BOOL CMmeFile::CheckDataFile(LPCTSTR pDataFolder, CTime &oStartTime, CTime &oEndTime, int &nMeasTime)
{
	m_sDataFolder = pDataFolder;

	// �f�[�^�t�@�C���p�X�擾
	CStringArray saDataFiles;
	GetDataFiles(saDataFiles);

	// .s�t�@�C�����݃`�F�b�N
	if (saDataFiles.GetCount() == 0) {
		CGeneral::Alert("�_�E�������N�t�@�C�������݂��܂���B");
		return FALSE;
	}

	// �f�[�^�T�C�Y�`�F�b�N
	if (GetTotalFileSize(saDataFiles) > MAX_DATA_SIZE) {
		CGeneral::Alert("�t�@�C���T�C�Y�������𒴂��Ă��܂��B");
		return FALSE;
	}

	// �^�C���X�^���v���ǂݍ���
	TimestampArray oTimestampArray;
	DataBlock oDataBlock;
	{
		CWaitCursor cursor;

		// �^�C���X�^���v�擾
		m_nPacketCount = 0;
		GetTimestamp(saDataFiles, oTimestampArray, NULL);

		// �f�[�^�u���b�N�擾
		GetDataBlock(oTimestampArray, oDataBlock);
	}

	// �ϊ��ς݃t�@�C�����݃`�F�b�N
	m_bOverWrite = CheckExistFile(oDataBlock);

	// �J�n�����A�I�������擾
	UINT nStartTime = UINT_MAX;
	UINT nEndTime = 0;
	for (int nRange = 0; nRange < N_RANGE; nRange++) {
		if (oTimestampArray[0][nRange].GetCount() != 0) {
			nStartTime = min(nStartTime, oTimestampArray[0][nRange][0]);
			nEndTime = max(nEndTime, oTimestampArray[0][nRange][oTimestampArray[0][nRange].GetCount() - 1]);
		}
	}
	if (nStartTime == UINT_MAX || nEndTime == 0) {
		CGeneral::Alert("�t�@�C���̒��ɗL���ȃf�[�^�����݂��Ȃ����A�܂��̓f�[�^���s���ł��B");
		return FALSE;

	}
	oStartTime = GetTime(nStartTime);
	oEndTime = GetTime(nEndTime);

	// �v�����Ԏ擾
	nMeasTime = 0;
	for (int nRange = 0; nRange < N_RANGE; nRange++) {
		nMeasTime += (UINT)oTimestampArray[0][nRange].GetCount();
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetDataFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g���q��.s�̃t�@�C�������擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CStringArray	&saDataFiles		[O] ���������t�@�C���̃p�X���̔z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::GetDataFiles(CStringArray &saDataFiles)
{
	// .s�t�@�C������������p�X���擾
	CString sSearchPath;
	sSearchPath.Format("%s\\*.s", m_sDataFolder);

	// .s�t�@�C��������
	CFileFind oFileFind;
	if (oFileFind.FindFile(sSearchPath)) {
		BOOL bFndEnd = TRUE;
		while (bFndEnd) {
			bFndEnd = oFileFind.FindNextFile();

			if (!oFileFind.IsDirectory())
				saDataFiles.Add(oFileFind.GetFilePath());
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetTotalFileSize
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�f�[�^�t�@�C���̃T�C�Y�̍��v���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CStringArray	&saDataFiles		[I] �f�[�^�t�@�C���̃p�X���̔z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		ULONGLONG		�t�@�C���T�C�Y�̍��v
//*****************************************************************************************************
ULONGLONG CMmeFile::GetTotalFileSize(const CStringArray &saDataFiles)
{
	CFileStatus oStatus;
	ULONGLONG nTotalFileSize = 0;
	
	for (int i = 0; i < saDataFiles.GetCount(); i++) {
		CFile::GetStatus(saDataFiles[i], oStatus);
		nTotalFileSize += oStatus.m_size;
	}

	return nTotalFileSize;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::CheckExistFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ���̃t�@�C�������ɑ��݂��邩�`�F�b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		DataBlock	&oDataBlock		[I] �f�[�^�u���b�N���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:���݂���@FALSE:���݂��Ȃ�
//*****************************************************************************************************
BOOL CMmeFile::CheckExistFile(const DataBlock &oDataBlock)
{
	CString sFolderPath;

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				const STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
				GetOutputFolderName(nUnit, nRange, oTimestampBlock.nStartTimestamp, oTimestampBlock.nEndTimestamp, sFolderPath);
				if (::PathFileExists(sFolderPath))
					return TRUE;
			}
		}
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ConvDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�f�[�^�t�@�C�����H�w�l�ϊ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~�܂��̓G���[
//*****************************************************************************************************
BOOL CMmeFile::ConvDataFile()
{
	CProgressBar cProgressBar;

	return cProgressBar.ExecProcess("�H�w�l�ϊ�������", 100, this, ConvDataThread);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ConvDataThread
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
BOOL CMmeFile::ConvDataThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CMmeFile *)pParam)->ConvDataThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ConvDataThread2
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
BOOL CMmeFile::ConvDataThread2(CProgressBar *pProgressBar)
{
	// �f�[�^�t�@�C���p�X�擾
	CStringArray saDataFiles;
	GetDataFiles(saDataFiles);	

	// �^�C���X�^���v�擾
	TimestampArray oTimestampArray;
	if (GetTimestamp(saDataFiles, oTimestampArray, pProgressBar) == 0)
		return FALSE;

	// �f�[�^�u���b�N�擾
	DataBlock oDataBlock;
	GetDataBlock(oTimestampArray, oDataBlock);

	// �o�b�t�@�m��
	AllocateBuf(oDataBlock);

	// �f�[�^�ǂݍ���
	if (!ReadData(oDataBlock, pProgressBar))
		return FALSE;

	// �f�[�^�x�[�X��������
	if (!WriteDatabase(oDataBlock, pProgressBar))
		return FALSE;

	m_bOverWrite = CheckExistFile(oDataBlock);

	pProgressBar->SetProgress(100);

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ReadIniFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		mme.ini�t�@�C����ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:mme.ini�t�@�C��������
//*****************************************************************************************************
BOOL CMmeFile::ReadIniFile()
{
	static const char *unit[N_UNIT] = {"ACC_SCALE_FACTOR_UNIT1", "ACC_SCALE_FACTOR_UNIT2", "ACC_SCALE_FACTOR_UNIT3"};
	static const char *axis[N_AXIS] = {"X", "Y", "Z"};
	static const char *range[N_RANGE] = {"Lo", "Hi", "Ex"};
	CString sKey;
	char buf[256];

	// ini�t�@�C���p�X���擾
	CString sIniFileName;
	sIniFileName.Format("%s\\%s", gExePath, MME_INI_FILE);

	// �t�@�C�������݂��Ȃ���΃G���[
	if (!::PathFileExists(sIniFileName)) {
		CGeneral::Alert("%s�����݂��܂���B", MME_INI_FILE);
		return FALSE;
	}

	// ���j�b�g�̐��������[�v
	for (int i = 0; i < N_UNIT; i++) {
		SAccFactor::SUnit *pUnit = &m_oAccFactor.unit[i];

		// ���W(XYZ)�̐��������[�v
		for (int j = 0; j < N_AXIS; j++) {
			SAccFactor::SUnit::SAxis *pAxis = &pUnit->axis[j];

			// �v�������W�̐��������[�v
			for (int k = 0; k < N_RANGE; k++) {
				// ini�t�@�C���̃L�[
				sKey.Format("%s-%s", axis[j], range[k]);

				// ini�t�@�C������ǂݍ���
				GetPrivateProfileString(unit[i], sKey, "0", buf, sizeof(buf), sIniFileName);
				pAxis->range[k] = atof(buf);
			}
		}
	}

	// �[�b�̎擾
	for (int i = 1; ; i++) {
		int nYear, nMonth, nDay;
		sKey.Format("DATE%d", i);
		GetPrivateProfileString("LEAP_SECOND", sKey, "", buf, sizeof(buf), sIniFileName);
		if (sscanf_s(buf, "%d/%d/%d", &nYear, &nMonth, &nDay) == 3)
			m_oLeapSecond.Add(CTime(nYear, nMonth, nDay, 8, 59, 59));
		else
			break;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ReadSensorInfo
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		mme.ini�t�@�C������Z���T�[����ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::ReadSensorInfo()
{
	static const char *sensor[N_UNIT] = {"MME-S1", "MME-S2", "MME-S3"};
	char buf[256];

	// ini�t�@�C���p�X���擾
	CString sIniFileName;
	sIniFileName.Format("%s\\%s", gExePath, MME_INI_FILE);

	g_pSensorInfoMme = new SENSOR_INFO[N_UNIT];
	memset(g_pSensorInfoMme, 0, sizeof(SENSOR_INFO) * N_UNIT);

	// �Z���T�[���W�f�[�^�̎擾
	for (int i = 0; i < N_UNIT; i++) {
		GetPrivateProfileString(sensor[i], "X", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].x = atof(buf);

		GetPrivateProfileString(sensor[i], "Y", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].y = atof(buf);

		GetPrivateProfileString(sensor[i], "Z", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].z = atof(buf);

		GetPrivateProfileString(sensor[i], "Roll", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].roll = atof(buf);

		GetPrivateProfileString(sensor[i], "Pitch", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].pitch = atof(buf);

		GetPrivateProfileString(sensor[i], "Yaw", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].yaw = atof(buf);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ReadDataPacket
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		.s�t�@�C������P�̃f�[�^�p�P�b�g��ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CFile		&oFile			[I] .s�t�@�C��
//		SUserHeader	*pUserHeader	[O] ���[�U�w�b�_�o�b�t�@�ւ̃|�C���^
//		void		*pDataBuf		[O] �f�[�^�o�b�t�@�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:�t�@�C���̏I�[
//*****************************************************************************************************
BOOL CMmeFile::ReadDataPacket(CFile &oFile, SUserHeader *pUserHeader, void *pDataBuf)
{
	struct {
		char time[PACKET_TIME_SIZE];
		BYTE header[USER_HEADER_SIZE];
	} buf;
	UINT nDataNum;
	UINT nReadSize;
	int nHour, nMinute, nSecond;

	// .s�t�@�C���̒����X�L��������
	BOOL bLoop = TRUE;
	while (bLoop) {
		// ��M�����Ǝ�M�p�P�b�g�̃w�b�_��ǂݍ���
		if (oFile.Read(&buf, sizeof(buf)) != sizeof(buf))
			return FALSE;

		// �f�[�^��M�����t�H�[�}�b�g�`�F�b�N
		if (sscanf_s(buf.time, "%2d:%2d:%2d", &nHour, &nMinute, &nSecond) != 3)
			return FALSE;

		// �w�b�_�̓��e�����o��
		pUserHeader->nPayloadId = buf.header[0];
		pUserHeader->nDataId = buf.header[1];
		pUserHeader->nFrameId = buf.header[2] >> 1;
		pUserHeader->nEndSegment = buf.header[2] & 0x01;
		pUserHeader->nSegmentCount = buf.header[3];
		pUserHeader->nTimestamp = GETDWORD(buf.header[4], buf.header[5], buf.header[6], buf.header[7]);

		// �f�[�^����ID
		if (pUserHeader->nDataId == 0x80) {
			// �t���[���\������ID
			switch (pUserHeader->nFrameId) {
			case 0x01:	// �ʏ�V�[�P���XLo�����W�v�����[�h
			case 0x02:	// �ʏ�V�[�P���XHi�����W�v�����[�h
			case 0x03:	// �ʏ�V�[�P���X�g�������W�v�����[�h
			case 0x11:	// �A���V�[�P���XLo�����W�v�����[�h
			case 0x12:	// �A���V�[�P���XHi�����W�v�����[�h
			case 0x13:	// �A���V�[�P���X�g�������W�v�����[�h
				// �e�����g���p�P�b�g�̓��e�����o��
				pUserHeader->TelemetryPacket.nMeasurementMode = (buf.header[10] >> 6) & 0x03;
				pUserHeader->TelemetryPacket.nMeasurementRange = (buf.header[10] >> 4) & 0x03;
				pUserHeader->TelemetryPacket.nSamplingRate = GETWORD(buf.header[10] & 0x0f, buf.header[11]);
				pUserHeader->TelemetryPacket.nDataNum = GETWORD(buf.header[12], buf.header[13]);
				pUserHeader->TelemetryPacket.nUnitRange[UNIT_1] = (buf.header[14] >> 3) & 0x03;
				pUserHeader->TelemetryPacket.nUnitRange[UNIT_2] = (buf.header[15] >> 6) & 0x03;
				pUserHeader->TelemetryPacket.nUnitRange[UNIT_3] = (buf.header[15] >> 1) & 0x03;

				// �A�i���O���j�^���X�L�b�v
				if (pUserHeader->nSegmentCount == 0)
					oFile.Seek(48, CFile::current);

				// �f�[�^�o�b�t�@���w�肳��Ă���΂����ɓǂݍ��ށB�w�肳��Ă��Ȃ���Γǂݔ�΂�
				nReadSize = pUserHeader->TelemetryPacket.nDataNum * ACC_DATA_SIZE;
				if (pDataBuf != NULL)
					oFile.Read(pDataBuf, nReadSize);
				else
					oFile.Seek(nReadSize, CFile::current);

				bLoop = FALSE;
				break;
			case 0x04:	// MME�X�e�[�^�X�p�P�b�g
			case 0x05:	// �G���[�X�e�[�^�X�p�P�b�g
			case 0x06:	// ���b�Z�[�W�p�P�b�g
				// �f�[�^������ǂݔ�΂�
				nDataNum = GETWORD(buf.header[12], buf.header[13]);
				oFile.Seek(nDataNum, CFile::current);
				break;
			}
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetTimestamp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		.s�t�@�C������^�C���X�^���v����ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CStringArray	&saDataFiles		[I] �f�[�^�t�@�C���̃p�X���̔z��
//		TimestampArray	&oTimestampArray	[O] �^�C���X�^���v���
//		CProgressBar	*pProgressBar		[I] �v���O���X�o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~
//*****************************************************************************************************
BOOL CMmeFile::GetTimestamp(const CStringArray &saDataFiles, TimestampArray &oTimestampArray, CProgressBar *pProgressBar)
{
	CFile oFile;
	SUserHeader oUserHeader;
	TimestampMap oTimestampMap;
	UINT nTimestamp;
	UINT nPacketCount = 0;

	for (int i = 0; i < saDataFiles.GetCount(); i++) {
		if (oFile.Open(saDataFiles[i], CFile::modeRead | CFile::shareDenyWrite)) {
			while (true) {
				if (!ReadDataPacket(oFile, &oUserHeader, NULL))
					break;

				for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
					int nRange = -1;
					switch (oUserHeader.TelemetryPacket.nUnitRange[nUnit]) {
					case 0x01:	// Lo�����W�v���f�[�^
						nRange = RANGE_LO;
						break;
					case 0x02:	// Hi�����W�v���f�[�^
						nRange = RANGE_HI;
						break;
					case 0x03:	// �g�������W�v���f�[�^
						nRange = RANGE_EX;
						break;
					}
					if (nRange != -1) {
						// Map�ɒǉ�
						if (!oTimestampMap[nUnit][nRange].Lookup(oUserHeader.nTimestamp, nTimestamp))
							oTimestampMap[nUnit][nRange].SetAt(oUserHeader.nTimestamp, oUserHeader.nTimestamp);

						// �T���v�����O���[�g�ۑ�
						m_nSamplingRate[nRange] = oUserHeader.TelemetryPacket.nSamplingRate;
					}
				}

				// �v���O���X�o�[�\��
				if (pProgressBar != NULL) {
					if (!pProgressBar->SetProgress((int)(10 * ++nPacketCount / m_nPacketCount)))
						return FALSE;
				} else
					m_nPacketCount++;
			}

			oFile.Close();
		}
	}

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++)
			GetTimestampArray(oTimestampMap[nUnit][nRange], oTimestampArray[nUnit][nRange]);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetTimestampArray
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CMapTimestamp����CArrayTimestamp�ɕϊ����ă\�[�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMapTimestamp	&oMapTimestamp		[I] �v���O���X�o�[
//		TimestampArray	&oArrayTimestamp	[O] �^�C���X�^���v�z��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::GetTimestampArray(const CMapTimestamp &oMapTimestamp, CArrayTimestamp &oArrayTimestamp)
{
	UINT nKey;
	UINT nVal;

	// Map����Array�փR�s�[
	POSITION pos = oMapTimestamp.GetStartPosition();
	while (pos != NULL) {
		oMapTimestamp.GetNextAssoc(pos, nKey, nVal);
		oArrayTimestamp.Add(nVal);
	}

	// �^�C���X�^���v���\�[�g
	if (oArrayTimestamp.GetCount() != 0)
		qsort(oArrayTimestamp.GetData(), oArrayTimestamp.GetCount(), sizeof(UINT), CompareTimestamp);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::CompareTimestamp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C���X�^���v�\�[�g�p�̔�r�֐�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		void	*p1			[I] �^�C���X�^���v1�ւ̃|�C���^
//		void	*p2			[I] �^�C���X�^���v2�ւ̃|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		int		1:�^�C���X�^���v1�̕����傫���@-1:�^�C���X�^���v2�̕����傫���@0:������
//*****************************************************************************************************
int CMmeFile::CompareTimestamp(const void *p1, const void *p2)
{
	if (*(UINT *)p1 > *(UINT *)p2)
		return 1;
	else if (*(UINT *)p1 < *(UINT *)p2)
		return -1;
	else
		return 0;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetDataBlock
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�������^�C���X�^���v���Ƀu���b�N������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		TimestampArray	&oTimestampArray	[I] �^�C���X�^���v���̔z��
//		DataBlock		&oDataBlock			[O] �f�[�^�u���b�N���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::GetDataBlock(const TimestampArray &oTimestampArray, DataBlock &oDataBlock)
{
	// �A�������^�C���X�^���v�̃f�[�^���܂Ƃ߂�
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			UINT nCurrenttimestamp = 0;

			for (int i = 0; i < oTimestampArray[nUnit][nRange].GetCount(); i++) {
				UINT nTimestamp = oTimestampArray[nUnit][nRange][i];

				if (nTimestamp > nCurrenttimestamp + 1) {
					STimestampBlock oTimestampBlock;
					oTimestampBlock.nStartTimestamp = nTimestamp;
					oTimestampBlock.nEndTimestamp = nTimestamp;
					oTimestampBlock.nLastSeqCount = 0;
					oDataBlock[nUnit][nRange].Add(oTimestampBlock);
				}

				if (oDataBlock[nUnit][nRange].GetCount() != 0)
					oDataBlock[nUnit][nRange][oDataBlock[nUnit][nRange].GetCount() - 1].nEndTimestamp = nTimestamp;

				nCurrenttimestamp = nTimestamp;
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::AllocateBuf
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u���b�N���̃f�[�^�o�b�t�@���m�ۂ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		DataBlock		&oDataBlock			[O] �f�[�^�u���b�N���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::AllocateBuf(DataBlock &oDataBlock)
{
	// �f�[�^�o�b�t�@�m��
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
				UINT nBufSize = (oTimestampBlock.nEndTimestamp - oTimestampBlock.nStartTimestamp + 1) * m_nSamplingRate[nRange];
				oTimestampBlock.pAccData = new SAccData[nBufSize];
				memset(oTimestampBlock.pAccData, 0, nBufSize * sizeof(SAccData));
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ReadData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		.s�t�@�C������u���b�N���Ƀf�[�^��ǂݍ���
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		DataBlock		&oDataBlock			[O] �f�[�^�u���b�N���
//		CProgressBar	*pProgressBar		[I] �v���O���X�o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
BOOL CMmeFile::ReadData(DataBlock &oDataBlock, CProgressBar *pProgressBar)
{
	static const int nValidFlag[N_UNIT] = {0x10, 0x20, 0x40};

	CStringArray saDataFiles;
	GetDataFiles(saDataFiles);

	UINT nPacketCount = 0;
	CFile oFile;
	SUserHeader oUserHeader;
	BYTE *pBuf = new BYTE[65535 * ACC_DATA_SIZE];
	for (int i = 0; i < saDataFiles.GetCount(); i++) {
		if (oFile.Open(saDataFiles[i], CFile::modeRead | CFile::shareDenyWrite)) {
			while (true) {
				if (!ReadDataPacket(oFile, &oUserHeader, pBuf))
					break;

				for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
					int nRange = -1;
					switch (oUserHeader.TelemetryPacket.nUnitRange[nUnit]) {
					case 0x01:	// Lo�����W�v���f�[�^
						nRange = RANGE_LO;
						break;
					case 0x02:	// Hi�����W�v���f�[�^
						nRange = RANGE_HI;
						break;
					case 0x03:	// �g�������W�v���f�[�^
						nRange = RANGE_EX;
						break;
					}

					if (nRange != -1) {
						for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
							STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
							if (oUserHeader.nTimestamp >= oTimestampBlock.nStartTimestamp
									&& oUserHeader.nTimestamp <= oTimestampBlock.nEndTimestamp) {
								BYTE *pBuf2 = pBuf;
								for (UINT j = 0; j < oUserHeader.TelemetryPacket.nDataNum; j++) {
									UINT nSeqCount = GETWORD(pBuf2[0] & 0x0f, pBuf2[1]);
									if (nSeqCount > 0 && nSeqCount <= m_nSamplingRate[nRange]) {
										UINT nOffset = (oUserHeader.nTimestamp - oTimestampBlock.nStartTimestamp) * m_nSamplingRate[nRange] + (nSeqCount - 1);

										// �s�����t���O�擾
										oTimestampBlock.pAccData[nOffset].nValidFlag = pBuf2[0] & nValidFlag[nUnit];

										// �����x�f�[�^�擾
										for (int nAxis = 0; nAxis < N_AXIS; nAxis++) {
											UINT nIndex = 2 + nUnit * 6 + nAxis * 2;
											oTimestampBlock.pAccData[nOffset].nAccData[nAxis] = GETWORD(pBuf2[nIndex], pBuf2[nIndex + 1]);
										}

										pBuf2 += ACC_DATA_SIZE;

										if (oUserHeader.nTimestamp == oTimestampBlock.nEndTimestamp)
											oTimestampBlock.nLastSeqCount = max(oTimestampBlock.nLastSeqCount, nSeqCount);
									}
								}
								break;
							}
						}
					}
				}

				// �v���O���X�o�[�\��
				if (pProgressBar != NULL && !pProgressBar->SetProgress(10 + (int)(20 * ++nPacketCount / m_nPacketCount))) {
					delete [] pBuf;
					return FALSE;
				}
			}

			oFile.Close();
		}
	}

	delete [] pBuf;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::WriteDatabase
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ����s���A�f�[�^�x�[�X�t�H���_�Ƀt�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		DataBlock		&oDataBlock			[O] �f�[�^�u���b�N���
//		CProgressBar	*pProgressBar		[I] �v���O���X�o�[
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~
//*****************************************************************************************************
BOOL CMmeFile::WriteDatabase(DataBlock &oDataBlock, CProgressBar *pProgressBar)
{
	CString sMeasTime;
	CString sFolderPath;
	CString sFilePath;
	CString sFileName;
	CStringArray saDataFilePath;
	INT64 nDataSize = 0;

	// �v���O���X�o�[�\���p�ɑS�f�[�^���擾
	m_nTotalDataCount = 0;
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
				m_nTotalDataCount += (oTimestampBlock.nEndTimestamp - oTimestampBlock.nStartTimestamp) * m_nSamplingRate[nRange] + oTimestampBlock.nLastSeqCount;
			}
		}
	}

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];

				// �o�̓t�H���_��
				GetOutputFolderName(nUnit, nRange, oTimestampBlock.nStartTimestamp, oTimestampBlock.nEndTimestamp, sFolderPath);

				// �f�B���N�g���쐬
				CGeneral::CreateDirectoryAll(sFolderPath);

				// Idb�t�@�C���ݒ�
				SetIdbFile(sFolderPath, oTimestampBlock, nUnit, nRange);

				// �s�����f�[�^��u��
				UINT nDataCount = (oTimestampBlock.nEndTimestamp - oTimestampBlock.nStartTimestamp) * m_nSamplingRate[nRange] + oTimestampBlock.nLastSeqCount;
				ReplaceInvalidData(oTimestampBlock, nDataCount);

				// �P���Ԗ��ɍH�w�l�ϊ��t�@�C������������
				UINT n1HourDataSize = m_nSamplingRate[nRange] * 3600;
				UINT nDataTime = oTimestampBlock.nStartTimestamp;
				for (UINT nDataOffset = 0; nDataOffset < nDataCount; nDataOffset += n1HourDataSize) {
					UINT nWriteDataSize = min(n1HourDataSize, nDataCount - nDataOffset);

					// �o�̓t�@�C�����擾
					CTime oDataStartTime = GetTime(nDataTime);
					CTime oDataEndTime = GetTime(nDataTime + nWriteDataSize / m_nSamplingRate[nRange]);
					sFileName.Format("%s%c%s",
							oDataStartTime.FormatGmt("%Y_%m_%d_%H_%M_%S.000"),
							nDataOffset == 0 ? '-' : '+',
							oDataEndTime.FormatGmt("%Y_%m_%d_%H_%M_%S.000"));
					sFilePath.Format("%s\\%s", sFolderPath, sFileName);

					// �f�[�^�t�@�C����������
					if (!WriteDataFile(sFilePath, oTimestampBlock, nDataOffset, nWriteDataSize, nUnit, nRange, pProgressBar, nDataSize))
						return FALSE;

					// XML�t�@�C����������
					WriteXmlFile(sFileName, oDataStartTime);

					// �f�[�^�t�@�C�����ۑ�
					m_oIdbFile.AddFile(sFileName);

					// �P���ԁi3600�b�j���Z
					nDataTime += 3600;
				}

				// idb�t�@�C����������
				WriteIdbFile(oTimestampBlock);
			}
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetOutputFolderName
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�o�̓t�H���_�p�X���擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int			nUnit				[I] ���j�b�g�ԍ�
//		int			nRange				[I] �����W�ԍ�
//		UINT		nStartTimestamp		[I] �J�n�^�C���X�^���v
//		UINT		nEndTimestamp		[I] �I���^�C���X�^���v
//		CString		&sFolderPath		[O] �o�̓t�H���_�p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	����
//*****************************************************************************************************
void CMmeFile::GetOutputFolderName(int nUnit, int nRange, UINT nStartTimestamp, UINT nEndTimestamp, CString &sFolderPath)
{
	static const char *sUnit[N_UNIT] = {"Unit1", "Unit2", "Unit3"};
	static const char *sRange[N_RANGE] = {"Lo", "Hi", "Ex"};

	CTime oMeasStartTime = GetTime(nStartTimestamp);
	CTime oMeasEndTime = GetTime(nEndTimestamp);

	sFolderPath.Format("%s\\MME\\%s\\%s_%s_%s",
			gRootData,
			sUnit[nUnit],
			oMeasStartTime.FormatGmt("%Y_%m_%d_-%H_%M_%S_000"),
			oMeasEndTime.FormatGmt("%Y%m%d%H%M%S"),
			sRange[nRange]);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::SetIdbFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C���ɏ����Z�b�g����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sFolderPath		[I] �o�̓t�H���_�p�X
//		STimestampBlock	&oTimestampBlock	[I] �^�C���X�^���v�u���b�N���
//		int				nUnit				[I] ���j�b�g�ԍ�
//		int				nRange				[I] �����W�ԍ�
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::SetIdbFile(const CString &sFolderPath, const STimestampBlock &oTimestampBlock, int nUnit, int nRange)
{
	CString sBuf;

	CTime oStartTime = GetTime(oTimestampBlock.nStartTimestamp);
	CTime oEndTime = GetTime(oTimestampBlock.nEndTimestamp);

	struct tm tm;
	oStartTime.GetGmtTm(&tm);

	// �f�[�^����
	m_oIdbFile.DelAll();

	// �Z���T�[IDX
	m_oIdbFile.SetSensorIdx(nUnit);

	// �R�����g
	m_oIdbFile.SetComment(m_sComment);

	// �X�^�[�g�^�C��YYYY�i�N�jMM(��)DD(��)SSSSSSSS�i�~���b�j
	m_oIdbFile.SetStartTime(1900 + tm.tm_year, 1 + tm.tm_mon, tm.tm_mday, (((tm.tm_hour * 60) + tm.tm_min) * 60 + tm.tm_sec) * 1000);

	// �T���v�����[�g
	m_oIdbFile.SetSampleRate((double)m_nSamplingRate[nRange]);

	// ����
	sBuf.Format("%s-%s", oStartTime.FormatGmt("%Y%m%d%H%M%S"), oEndTime.FormatGmt("%Y%m%d%H%M%S"));
	m_oIdbFile.SetPeriod(sBuf);

	// �_�E�������N��
	m_oIdbFile.SetDownlink(m_sDownlink);

	// �Z���T�[ID
	m_oIdbFile.SetSensorId(nUnit + 1);

	// �^�C���[��
	m_oIdbFile.SetTimeZero(oStartTime.FormatGmt("%Y%m%d%H%M%S"));

	// Sensor Co Sys
	sBuf.Format("MME-S%d", nUnit + 1);
	m_oIdbFile.SetSensorcosys(sBuf);

	// Data Co Sys
	sBuf.Format("MME-S%d", nUnit + 1);
	m_oIdbFile.SetDatacosys(sBuf);

	// �ۑ��p�X��
	m_oIdbFile.SetPath(sFolderPath);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::ReplaceInvalidData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�s�����f�[�^��u������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		STimestampBlock	&oTimestampBlock	[O] �^�C���X�^���v�u���b�N���
//		UINT			nDataCount			[I] �f�[�^��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::ReplaceInvalidData(STimestampBlock &oTimestampBlock, UINT nDataCount)
{
	short nReplaceData[N_AXIS];
	UINT i, j;
	int nAxis;

	// �u���f�[�^������
	for (nAxis = 0; nAxis < N_AXIS; nAxis++)
		nReplaceData[nAxis] = 0;

	// �s�����f�[�^��u������
	for (i = 0; i < nDataCount; i++) {
		if (!oTimestampBlock.pAccData[i].nValidFlag) {
			// ���̗L���f�[�^��T��
			if (i == 0 || oTimestampBlock.pAccData[i - 1].nValidFlag) {
				for (j = i; j < nDataCount; j++) {
					if (oTimestampBlock.pAccData[j].nValidFlag)
						break;
				}

				if (i == 0) {
					if (j < nDataCount) {
						// �擪�ɕs�����f�[�^������ꍇ�͍ŏ��̗L���f�[�^���g��
						for (nAxis = 0; nAxis < N_AXIS; nAxis++)
							nReplaceData[nAxis] = oTimestampBlock.pAccData[j].nAccData[nAxis];
					}
				} else {
					if (j < nDataCount) {
						// ���Ԃɕs�����f�[�^������ꍇ�͑O��̗L���ȃf�[�^�̕��ϒl���g��
						for (nAxis = 0; nAxis < N_AXIS; nAxis++)
							nReplaceData[nAxis] = (oTimestampBlock.pAccData[i - 1].nAccData[nAxis] + oTimestampBlock.pAccData[j].nAccData[nAxis]) / 2;
					} else {
						// �����ɕs�����f�[�^������ꍇ�͍Ō�̗L���f�[�^���g��
						for (nAxis = 0; nAxis < N_AXIS; nAxis++)
							nReplaceData[nAxis] = oTimestampBlock.pAccData[i - 1].nAccData[nAxis];
					}
				}
			}

			// �s�����f�[�^��u��
			for (nAxis = 0; nAxis < N_AXIS; nAxis++)
				oTimestampBlock.pAccData[i].nAccData[nAxis] = nReplaceData[nAxis];
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::WriteDataFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�H�w�l�ϊ��t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString			&sFilePath			[I] �o�̓t�@�C���p�X
//		STimestampBlock	&oTimestampBlock	[I] �^�C���X�^���v�u���b�N���
//		UINT			nDataOffset			[I] �f�[�^�o�b�t�@�I�t�Z�b�g
//		UINT			nWriteDataSize		[I] �������݃f�[�^��
//		int				nUnit				[I] ���j�b�g�ԍ�
//		int				nRange				[I] �����W�ԍ�
//		CProgressBar	*pProgressBar		[I] �v���O���X�o�[
//		INT64			nDataSize			[I/O] �����ς݃f�[�^�J�E���g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���~
//*****************************************************************************************************
BOOL CMmeFile::WriteDataFile(const CString &sFilePath, const STimestampBlock &oTimestampBlock, UINT nDataOffset, UINT nWriteDataSize, int nUnit, int nRange, CProgressBar *pProgressBar, INT64 &nDataSize)
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
		oHeader.nSeqNo = nDataOffset / m_nSamplingRate[nRange] + 1;
		oHeader.nDataSize = nWriteDataSize;
		oHeader.nDataSizeHour = m_nSamplingRate[nRange] * 3600;
		oFile.Write(&oHeader, sizeof(oHeader));

		// �f�[�^��������
		UINT nDataCount = 0;
		double aWriteBuf[100][7];
		memset(aWriteBuf, 0, sizeof(aWriteBuf));
		while (nDataCount < nWriteDataSize) {
			int nBufSize = min(100, nWriteDataSize - nDataCount);
			for (int i = 0; i < nBufSize; i++) {
				// �H�w�l�ϊ�
				for (int nAxis = 0; nAxis < N_AXIS; nAxis++)
					aWriteBuf[i][4 + nAxis] = oTimestampBlock.pAccData[nDataOffset + nDataCount].nAccData[nAxis] * m_oAccFactor.unit[nUnit].axis[nAxis].range[nRange] * 1000000;

				nDataCount++;
			}

			// �v���O���X�o�[�\��
			nDataSize += nBufSize;
			if (pProgressBar != NULL && !pProgressBar->SetProgress(30 + (int)((INT64)70 * nDataSize / m_nTotalDataCount)))
				return FALSE;

			// �t�@�C���ɏ�������
			oFile.Write(aWriteBuf, 7 * nBufSize * sizeof(double));
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::WriteXmlFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		XML�t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CString		&sFileName				[I] �o�̓t�@�C����
//		CTime		&oDataStartTime			[I] �f�[�^�̊J�n����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::WriteXmlFile(const CString &sFileName, const CTime &oDataStartTime)
{
	// �v���J�n������ҏW
	m_oIdbFile.SetTimeZeroXML(oDataStartTime.FormatGmt("%Y_%m_%d_%H_%M_%S.000"));

	// XML�t�@�C������������
	m_oIdbFile.WriteXml(sFileName, DATA_TYPE_MME);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::WriteIdbFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		idb�t�@�C������������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CTime		&oTimestampBlock		[I] �^�C���X�^���v�u���b�N���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeFile::WriteIdbFile(const STimestampBlock &oTimestampBlock)
{
	CTime oStartTime = GetTime(oTimestampBlock.nStartTimestamp);

	struct tm tm;
	oStartTime.GetGmtTm(&tm);

	// �t�@�C������ҏW
	CString sFileName;
	sFileName.Format("%04d%02d%02d%d", 1900 + tm.tm_year, 1 + tm.tm_mon, tm.tm_mday, (((tm.tm_hour * 60) + tm.tm_min) * 60 + tm.tm_sec) * 1000);

	// idb�t�@�C���ɏ�������
	m_oIdbFile.Write(sFileName);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeFile::GetTime
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�^�C���X�^���v������ɕϊ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT		nTimestamp		[I] �^�C���X�^���v
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		CTime		����
//*****************************************************************************************************
CTime CMmeFile::GetTime(UINT nTimestamp)
{
	// �����ɕϊ�
	CTime oTime(nTimestamp + 315964800 - 16);

	// �[�b�̕␳
	for (int i = 0; i < m_oLeapSecond.GetCount(); i++) {
		if (m_oLeapSecond[i] < oTime)
			oTime -= 1;
		else
			break;
	}

	return oTime;
}
