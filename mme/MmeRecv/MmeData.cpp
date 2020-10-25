//*****************************************************************************************************
//  1. �t�@�C����
//		MmeData.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�f�[�^�N���X�̎���
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#include "StdAfx.h"
#include "MmeData.h"
#include "General.h"
#include <shlwapi.h>

// �o�C�g�f�[�^���烏�[�h�f�[�^�֕ϊ�
#define GETWORD(b1, b2) (((WORD)(b1) << 8) | (b2))
#define GETDWORD(b1, b2, b3, b4) (((((((DWORD)(b1) << 8) | (b2)) << 8) | (b3)) << 8) | (b4))

// �|�C���^���烏�[�h�f�[�^�֕ϊ�
#define GETWORD_P(p) GETWORD((p)[0], (p)[1])
#define GETDWORD_P(p) GETDWORD((p)[0], (p)[1], (p)[2], (p)[3])

// �|�C���^���當������擾
#define GETSTRING(p, n) CString((char *)(p), n)

// ��M�t�H���_
#define RECEIVE_FOLDER	"ReceiveData"

CMmeData g_oMmeData;

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::ReadIniFile
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
BOOL CMmeData::ReadIniFile()
{
	static const char *aUnit[N_UNIT] = {"ACC_SCALE_FACTOR_UNIT1", "ACC_SCALE_FACTOR_UNIT2", "ACC_SCALE_FACTOR_UNIT3"};
	static const char *aAcc[N_ACC] = {"X", "Y", "Z"};
	static const char *aRange[N_RANGE] = {"Lo", "Hi", "Ex"};
	static const char aAlalog[] = "ANALOG_SCALE_FACTOR";
	CString sIniPathName;
	CString sKey;
	char buf[256];

	// ini�p�X���擾
	CGeneral::GetIniPathName(sIniPathName);

	// �t�@�C�������݂��Ȃ���΃G���[
	if (!::PathFileExists(sIniPathName))
		return FALSE;

	// �f�W�^�������x�X�P�[���t�@�N�^�̎擾
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
				sKey.Format("%s-%s", aAcc[nAcc], aRange[nRange]);
				m_oFactors.oDigitalFactor.aUnit[nUnit].aRange[nRange].fAcc[nAcc] = CGeneral::GetPrivateProfileDouble(aUnit[nUnit], sKey, 0, sIniPathName);
			}
		}
	}

	// �A�i���O�f�[�^�t�@�N�^�̎擾
	for (int nRange = 0; nRange < N_RANGE; nRange++) {
		sKey.Format("Acc-%s", aRange[nRange]);
		m_oFactors.oAnalogFactor.aAcc[nRange] = CGeneral::GetPrivateProfileDouble(aAlalog, sKey, 0, sIniPathName);
	}
	m_oFactors.oAnalogFactor.fTemp = CGeneral::GetPrivateProfileDouble(aAlalog, "Temp", 0, sIniPathName);
	m_oFactors.oAnalogFactor.fHeater = CGeneral::GetPrivateProfileDouble(aAlalog, "Heater", 0, sIniPathName);

	// �[�b�̎擾
	static const UINT aLeaps[] = {46828800, 78364801, 109900802, 173059203, 252028804, 315187205, 346723206, 393984007, 425520008, 457056009, 504489610, 551750411, 599184012, 820108813, 914803214, 1025136015};
	SLeapSecond oLeapSecond;
	for (int i = 0; i < sizeof(aLeaps) / sizeof(int); i++) {
		oLeapSecond.nTime = aLeaps[i];
		oLeapSecond.nSecond = 1;
		m_oLeapSecond.Add(oLeapSecond);
	}
	for (int i = 0; ; i++) {
		int nYear, nMonth, nDay, nSecond;
		sKey.Format("Date%d", i + 1);
		::GetPrivateProfileString("LEAP_SECOND", sKey, "", buf, sizeof(buf), sIniPathName);
		int n = sscanf_s(buf, "%d/%d/%d %d", &nYear, &nMonth, &nDay, &nSecond);
		if (n >= 3) {
			try {
				oLeapSecond.nTime = (int)CTime(nYear, nMonth, nDay, 8, 59, 59).GetTime() - 315964800 + 16 + i;
				oLeapSecond.nSecond = (n == 3) ? 1 : nSecond;
				m_oLeapSecond.Add(oLeapSecond);
			} catch (CException *) {
			}
		} else
			break;
	}

	// ��M�f�[�^�t�H���_�̎擾
	::GetPrivateProfileString("RECEIVE_FILE", "Folder", RECEIVE_FOLDER, buf, sizeof(buf), sIniPathName);
	m_sReceiveDataFolder = buf;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::CreateReceiveFolder
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M�t�H���_���쐬����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::CreateReceiveFolder()
{
	::CreateDirectory(m_sReceiveDataFolder, NULL);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::OpenRecieveFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M�t�@�C���I�[�v������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pNasaPathName			[I] NASA�p��M�t�@�C���p�X
//		LPCTSTR		pHcorPathName			[I] HCOR�p��M�t�@�C���p�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE:����@FALSE:���s
//*****************************************************************************************************
BOOL CMmeData::OpenRecieveFiles(LPCTSTR pNasaPathName, LPCTSTR pHcorPathName)
{
	CString sPathName;

	// NASA�p��M�t�@�C���I�[�v��
	m_sNasaPathName = pNasaPathName;
	if (!m_fileNasa.Open(m_sNasaPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive))
		return FALSE;

	// HCOR�p��M�t�@�C���I�[�v��
	m_sHcorPathName = pHcorPathName;
	if (!m_fileHcor.Open(m_sHcorPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive))
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::CloseReceiveFiles
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��M�t�@�C���N���[�Y����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::CloseReceiveFiles()
{
	ULONGLONG nLength;

	// NASA�p��M�t�@�C���N���[�Y
	if (m_fileNasa.m_hFile != INVALID_HANDLE_VALUE) {
		nLength = m_fileNasa.GetLength();
		m_fileNasa.Close();

		// �T�C�Y��0�Ȃ�t�@�C�����폜����
		if (nLength == 0)
			::DeleteFile(m_sNasaPathName);
	}

	// HCOR�p��M�t�@�C���N���[�Y
	if (m_fileHcor.m_hFile != INVALID_HANDLE_VALUE) {
		nLength = m_fileHcor.GetLength();
		m_fileHcor.Close();

		// �T�C�Y��0�Ȃ�t�@�C�����폜����
		if (nLength == 0)
			::DeleteFile(m_sHcorPathName);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::WriteNasaFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		NASA�p�P�b�g���t�@�C���ɏ����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE		*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		UINT		nPacketLen			[I] �p�P�b�g�T�C�Y
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::WriteNasaFile(BYTE *pPacketBuf, UINT nPacketLen)
{
	CTime oTime = CTime::GetCurrentTime();

	m_fileNasa.Write(oTime.Format("%H:%M:%S"), 8);
	m_fileNasa.Write(pPacketBuf, nPacketLen);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::WriteHcorFile
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		HCOR�p�P�b�g���t�@�C���ɏ����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE		*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		UINT		nPacketLen			[I] �p�P�b�g�T�C�Y
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::WriteHcorFile(BYTE *pPacketBuf, UINT nPacketLen)
{
	CTime oTime = CTime::GetCurrentTime();

	m_fileHcor.Write(oTime.Format("%H:%M:%S"), 8);
	m_fileHcor.Write(pPacketBuf, nPacketLen);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetUserHeader
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g���烆�[�U�w�b�_�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		SUserHeader		&oUserHeader		[O] �p�P�b�g�T�C�Y
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetUserHeader(BYTE *pPacketBuf, SUserHeader &oUserHeader)
{
	// �w�b�_�̓��e�����o��
	oUserHeader.nPayloadId = pPacketBuf[0];
	oUserHeader.nDataId = pPacketBuf[1];
	oUserHeader.nFrameId = pPacketBuf[2] >> 1;
	oUserHeader.nEndSegment = pPacketBuf[2] & 0x01;
	oUserHeader.nSegmentCount = pPacketBuf[3];
	oUserHeader.oTimestamp = GetTime(GETDWORD(pPacketBuf[4], pPacketBuf[5], pPacketBuf[6], pPacketBuf[7]));
	oUserHeader.nFineTime = GETWORD(pPacketBuf[8], pPacketBuf[9]);
	oUserHeader.nPacketType = PACKET_UNKNOWN;

	// �y�C���[�hID
	if (oUserHeader.nPayloadId == 0x35) {
		// �f�[�^����ID
		if (oUserHeader.nDataId == 0x80) {
			// �t���[���\������ID
			switch (oUserHeader.nFrameId) {
			case 0x01:	// �ʏ�V�[�P���XLo�����W�v�����[�h
			case 0x02:	// �ʏ�V�[�P���XHi�����W�v�����[�h
			case 0x03:	// �ʏ�V�[�P���X�g�������W�v�����[�h
			case 0x11:	// �A���V�[�P���XLo�����W�v�����[�h
			case 0x12:	// �A���V�[�P���XHi�����W�v�����[�h
			case 0x13:	// �A���V�[�P���X�g�������W�v�����[�h
				oUserHeader.nPacketType = PACKET_TELEMETRY;
				oUserHeader.oTelemetry.nMeasurementMode = (pPacketBuf[10] >> 6) & 0x03;
				oUserHeader.oTelemetry.nMeasurementRange = (pPacketBuf[10] >> 4) & 0x03;
				oUserHeader.oTelemetry.nSamplingRate = GETWORD(pPacketBuf[10] & 0x0f, pPacketBuf[11]);
				oUserHeader.oTelemetry.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oTelemetry.oStatusWord);
				break;
			case 0x04:	// MME�X�e�[�^�X�p�P�b�g
				oUserHeader.nPacketType = PACKET_MME_STATUS;
				oUserHeader.oMmeStatus.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oMmeStatus.oStatusWord);
				break;			
			case 0x05:	// �G���[�X�e�[�^�X�p�P�b�g
				oUserHeader.nPacketType = PACKET_ERROR_STATUS;
				oUserHeader.oErrorStatus.nErrorCode = pPacketBuf[10];
				oUserHeader.oErrorStatus.nSubCode = pPacketBuf[11];
				oUserHeader.oErrorStatus.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oErrorStatus.oStatusWord);
				break;			
			case 0x06:	// ���b�Z�[�W�p�P�b�g
				oUserHeader.nPacketType = PACKET_MESSAGE;
				oUserHeader.oMessage.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oMessage.oStatusWord);
				break;			
			}
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetTeremetryData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����e�����g���f�[�^�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		STelemetryData	&oTelemetryData		[O] �e�����g���f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetTeremetryData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, STelemetryData &oTelemetryData)
{
	// �A�i���O�f�[�^���擾
	pPacketBuf += USER_HEADER_SIZE;
	GetAnalogData(pPacketBuf, oUserHeader.oTelemetry.oStatusWord, oTelemetryData.oAnalogData);

	// �f�W�^���f�[�^���擾
	pPacketBuf += ANALOG_DATA_SIZE;
	GetDigitalData(pPacketBuf, oUserHeader.oTelemetry.oStatusWord, oTelemetryData.oDigitalData);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetMmeStatusData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����MME�X�e�[�^�X�f�[�^�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SMmeStatusData	&oMmeStatusData		[O] MME�X�e�[�^�X�f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetMmeStatusData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SMmeStatusData &oMmeStatusData)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	oMmeStatusData.sContrxxxodeTableName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nMeasureModeId = pData[0];
	oMmeStatusData.nMeasureStatus = pData[1];
	oMmeStatusData.nCurrentRangeId = GETWORD(pData[2], pData[3]);
	pData += 4;

	oMmeStatusData.sDownlinkPathName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nDownlinkPathData = GETDWORD_P(pData);
	pData += 4;

	oMmeStatusData.sTimerCountName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nTimerCountData = GETDWORD_P(pData);
	pData += 4;

	oMmeStatusData.sMeasStartTimeName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nMeasStartTimeData = GETDWORD_P(pData);
	pData += 4;

	oMmeStatusData.sMeasEndTimeName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nMeasEndTimeData = GETDWORD_P(pData);
	pData += 4;

	// �A�i���O�f�[�^���擾
	GetAnalogData(pData, oUserHeader.oMmeStatus.oStatusWord, oMmeStatusData.oAnalogData);
	pData += ANALOG_DATA_SIZE;

	// �O��HALT���G���[�����擾
	GetLasttimeHaltData(pData, oMmeStatusData.oLasttimeHalt);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetLasttimeHaltData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����O��HALT���G���[���̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pData				[I] �p�P�b�g�f�[�^
//		SLasttimeHalt	&oLasttimeHalt		[I] �O��HALT���G���[���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetLasttimeHaltData(BYTE *pData, SLasttimeHalt &oLasttimeHalt)
{
	// �S�Ẵf�[�^��0�Ȃ�Ζ����Ƃ���
	oLasttimeHalt.bValidData = FALSE;
	for (int i = 0; i < 24; i++) {
		if (pData[i] != 0) {
			oLasttimeHalt.bValidData = TRUE;
			break;
		}
	}
		
	oLasttimeHalt.oBitStatus.nSessionNumber = GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oBitStatus.nType= GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oBitStatus.nNature = GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oHaltInfo.sTaskName = GETSTRING(pData, 4);
	pData += 4;

	oLasttimeHalt.oHaltInfo.nErrorCode = GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oHaltInfo.oErrorTime = GetTime(GETDWORD_P(pData));
	pData += 4;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetErrorStatusData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����G���[�X�e�[�^�X�f�[�^�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE				*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		SUserHeader			&oUserHeader		[I] ���[�U�w�b�_���
//		SErrorStatusData	&oErrorStatusData	[O] �G���[�X�e�[�^�X�f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetErrorStatusData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SErrorStatusData &oErrorStatusData)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	if (oUserHeader.oErrorStatus.nErrorCode == 0x02) {
		oErrorStatusData.sTaskName = GETSTRING(pData, 8);
		pData += 8;

		oErrorStatusData.sErrorMessage = GETSTRING(pData, 192);
	} else {
		oErrorStatusData.sErrorMessage = GETSTRING(pData, 200);
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetMessageData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g���烁�b�Z�[�W�f�[�^�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pPacketBuf			[I] �p�P�b�g�o�b�t�@
//		SUserHeader		&oUserHeader		[I] ���[�U�w�b�_���
//		SMessageData	&oMessageData		[O] ���b�Z�[�W�f�[�^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetMessageData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SMessageData &oMessageData)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	oMessageData.sMessage = GETSTRING(pData, 56);
	pData += 56;

	GetLasttimeHaltData(pData, oMessageData.oLasttimeHalt);
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetStatusWord
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����X�e�[�^�X���[�h�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		UINT			nStatusWord			[I] �X�e�[�^�X���[�h
//		SStatusWord		&oStatusWord		[O] �X�e�[�^�X���[�h���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetStatusWord(UINT nStatusWord, SStatusWord &oStatusWord)
{
	// ���j�b�g1
	oStatusWord.aUnit[UNIT_1].nOnOffStatus = (nStatusWord >> 13) & 0x01;
	oStatusWord.aUnit[UNIT_1].nRangeStatus = (nStatusWord >> 11) & 0x03;

	// ���j�b�g2
	oStatusWord.aUnit[UNIT_2].nOnOffStatus = (nStatusWord >> 8) & 0x01;
	oStatusWord.aUnit[UNIT_2].nRangeStatus = (nStatusWord >> 6) & 0x03;

	// ���j�b�g3
	oStatusWord.aUnit[UNIT_3].nOnOffStatus = (nStatusWord >> 3) & 0x01;
	oStatusWord.aUnit[UNIT_3].nRangeStatus = (nStatusWord >> 1) & 0x03;
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetAnalogData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����A�i���O���j�^�̓��e�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pData				[I] �p�P�b�g�f�[�^
//		SStatusWord		&oStatusWord		[I] �X�e�[�^�X���[�h���
//		SAnalogData		&oAnalogData		[O] �A�i���O���j�^���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetAnalogData(BYTE *pData, const SStatusWord &oStatusWord, SAnalogData &oAnalogData)
{
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		int nRange = oStatusWord.aUnit[nUnit].nRangeStatus - 1;

		// �����x�f�[�^
		for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
			oAnalogData.aUnit[nUnit].nAcc[nAcc] = (short)GETWORD_P(pData);
			if (nRange >= 0 && nRange < N_RANGE)
				oAnalogData.aUnit[nUnit].fAcc[nAcc] = oAnalogData.aUnit[nUnit].nAcc[nAcc] * m_oFactors.oAnalogFactor.aAcc[nRange];
			else
				oAnalogData.aUnit[nUnit].fAcc[nAcc] = 0;

			pData += 2;
		}

		// ���x�f�[�^
		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			oAnalogData.aUnit[nUnit].nTemp[nTemp] = (short)GETWORD_P(pData);
			oAnalogData.aUnit[nUnit].fTemp[nTemp] = oAnalogData.aUnit[nUnit].nTemp[nTemp] * m_oFactors.oAnalogFactor.fTemp;
			pData += 2;
		}

		// �q�[�^�d���f�[�^
		oAnalogData.aUnit[nUnit].nHeater = (short)GETWORD_P(pData);
		oAnalogData.aUnit[nUnit].fHeater = oAnalogData.aUnit[nUnit].nHeater * m_oFactors.oAnalogFactor.fHeater;
		pData += 2;
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeData::GetDigitalData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�p�P�b�g����f�W�^�������x�f�[�^�����o��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		BYTE			*pData				[I] �p�P�b�g�f�[�^
//		SStatusWord		&oStatusWord		[I] �X�e�[�^�X���[�h���
//		SDigitalData	&oDigitalData		[O] �f�W�^�������x�f�[�^���
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMmeData::GetDigitalData(BYTE *pData, const SStatusWord &oStatusWord, SDigitalData &oDigitalData)
{
	oDigitalData.nSequrnceCount = GETWORD(pData[0] & 0x0f, pData[1]);
	pData += 2;

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		int nRange = oStatusWord.aUnit[nUnit].nRangeStatus - 1;

		// �����x�f�[�^
		for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
			oDigitalData.aUnit[nUnit].nAcc[nAcc] = (short)GETWORD_P(pData);
			if (nRange >= 0 && nRange < N_RANGE)
				oDigitalData.aUnit[nUnit].fAcc[nAcc] = oDigitalData.aUnit[nUnit].nAcc[nAcc] * m_oFactors.oDigitalFactor.aUnit[nUnit].aRange[nRange].fAcc[nAcc];
			else
				oDigitalData.aUnit[nUnit].fAcc[nAcc] = 0;

			pData += 2;
		}
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMmeRecvDlg::GetTime
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
CTime CMmeData::GetTime(UINT nTimestamp)
{
	// �����ɕϊ�
	UINT nTime = nTimestamp + 315964800;

	// �[�b�̕␳
	for (int i = 0; i < m_oLeapSecond.GetCount(); i++) {
		if (m_oLeapSecond[i].nTime < nTimestamp)
			nTime -= m_oLeapSecond[i].nSecond;
		else
			break;
	}

	return CTime(nTime);
}
