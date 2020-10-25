#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MmeData.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		MME�f�[�^�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//		����
//*****************************************************************************************************

#define N_UNIT	3
#define UNIT_1	0
#define UNIT_2	1
#define UNIT_3	2

#define N_RANGE		3
#define RANGE_LO	0
#define RANGE_HI	1
#define RANGE_EX	2

#define N_ACC	3
#define ACC_X	0
#define ACC_Y	1
#define ACC_Z	2

#define N_TEMP	4
#define TEMP_X	0
#define TEMP_Y	1
#define TEMP_Z	2
#define TEMP_SB	3

#define PACKET_UNKNOWN		0
#define PACKET_TELEMETRY	1
#define PACKET_MME_STATUS	2
#define PACKET_ERROR_STATUS	3
#define PACKET_MESSAGE		4

#define USER_HEADER_SIZE	16
#define ANALOG_DATA_SIZE	48
#define DIGITAL_DATA_SIZE	20

// �X�e�[�^�X���[�h
struct SStatusWord {
	struct {
		UINT nOnOffStatus;
		UINT nRangeStatus;
	} aUnit[N_UNIT];
};

// �e�����g���p�P�b�g
struct STelemetry {
	UINT nMeasurementMode;
	UINT nMeasurementRange;
	UINT nSamplingRate;
	UINT nDataNum;
	SStatusWord oStatusWord;
};

// MME�X�e�[�^�X�p�P�b�g
struct SMmeStatus {
	UINT nDataNum;
	SStatusWord oStatusWord;
};

// �G���[�X�e�[�^�X�p�P�b�g
struct SErrorStatus {
	UINT nErrorCode;
	UINT nSubCode;
	UINT nDataNum;
	SStatusWord oStatusWord;
};

// ���b�Z�[�W�p�P�b�g
struct SMessage {
	UINT nDataNum;
	SStatusWord oStatusWord;
};

// ���[�U�w�b�_���
struct SUserHeader {
	UINT nPayloadId;
	UINT nDataId;
	UINT nFrameId;
	UINT nEndSegment;
	UINT nSegmentCount;
	CTime oTimestamp;
	UINT nFineTime;
	UINT nPacketType;
	union {
		STelemetry oTelemetry;
		SMmeStatus oMmeStatus;
		SErrorStatus oErrorStatus;
		SMessage oMessage;
	};
};

// �f�W�^���f�[�^
struct SDigitalData {
	int nSequrnceCount;
	struct {
		int nAcc[N_ACC];
		double fAcc[N_ACC];
	} aUnit[N_UNIT];
};

// �A�i���O�f�[�^
struct SAnalogData {
	struct {
		int nAcc[N_ACC];
		double fAcc[N_ACC];
		int nTemp[N_TEMP];
		double fTemp[N_TEMP];
		int nHeater;
		double fHeater;
	} aUnit[N_UNIT];
};

// �e�����g���f�[�^
struct STelemetryData {
	SAnalogData oAnalogData;
	SDigitalData oDigitalData;
};

// �O��HALT���G���[���
struct SLasttimeHalt {
	BOOL bValidData;
	struct {
		UINT nSessionNumber;
		UINT nType;
		UINT nNature;
	} oBitStatus;
	struct {
		CString sTaskName;
		UINT nErrorCode;
		CTime oErrorTime;
	} oHaltInfo;
};

// MME�X�e�[�^�X�f�[�^
struct SMmeStatusData {
	CString sContrxxxodeTableName;
	UINT nMeasureModeId;
	UINT nMeasureStatus;
	UINT nCurrentRangeId;
	CString sDownlinkPathName;
	UINT nDownlinkPathData;
	CString sTimerCountName;
	UINT nTimerCountData;
	CString sMeasStartTimeName;
	UINT nMeasStartTimeData;
	CString sMeasEndTimeName;
	UINT nMeasEndTimeData;
	SAnalogData oAnalogData;
	SLasttimeHalt oLasttimeHalt;
};

// �G���[�X�e�[�^�X�f�[�^
struct SErrorStatusData {
	CString sTaskName;
	CString sErrorMessage;
};

// ���b�Z�[�W�f�[�^
struct SMessageData {
	CString sMessage;
	SLasttimeHalt oLasttimeHalt;
};

// ���邤�b�␳�f�[�^
struct SLeapSecond {
	UINT nTime;
	int nSecond;
};

class CMmeData
{
	// �W���f�[�^
	struct SFactors {
		// �f�W�^�������x�f�[�^�X�P�[���t�@�N�^
		struct SDigitalFactor {
			struct {
				struct {
					double fAcc[N_ACC];
				} aRange[N_RANGE];
			} aUnit[N_UNIT];
		} oDigitalFactor;

		// �A�i���O�f�[�^�X�P�[���t�@�N�^
		struct SAnalogFactor {
			double aAcc[N_ACC];
			double fTemp;
			double fHeater;
		} oAnalogFactor;
	};

public:
	CString GetReceiveFolder() {return m_sReceiveDataFolder;}
	BOOL ReadIniFile();
	void CreateReceiveFolder();
	BOOL OpenRecieveFiles(LPCTSTR pNasaPathName, LPCTSTR pHcorPathName);
	void CloseReceiveFiles();
	void WriteNasaFile(BYTE *pPacketBuf, UINT nPacketLen);
	void WriteHcorFile(BYTE *pPacketBuf, UINT nPacketLen);
	void GetUserHeader(BYTE *pPacketBuf, SUserHeader &oUserHeader);
	void GetTeremetryData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, STelemetryData &oTelemetryData);
	void GetMmeStatusData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SMmeStatusData &oMmeStatusData);
	void GetErrorStatusData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SErrorStatusData &oErrorStatusData);
	void GetMessageData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SMessageData &oMessageData);
	void GetStatusWord(UINT nStatusWord, SStatusWord &oStatusWord);
	void GetAnalogData(BYTE *pData, const SStatusWord &oStatusWord, SAnalogData &oAnalogData);
	void GetDigitalData(BYTE *pData, const SStatusWord &oStatusWord, SDigitalData &oDigitalData);
	CTime GetTime(UINT nTimestamp);

protected:
	SFactors m_oFactors;
	CString m_sReceiveDataFolder;
	CString m_sNasaPathName;
	CString m_sHcorPathName;
	CFile m_fileNasa;
	CFile m_fileHcor;
	CArray <SLeapSecond, SLeapSecond &> m_oLeapSecond;

	void GetLasttimeHaltData(BYTE *pData, SLasttimeHalt &oLasttimeHalt);
};

extern 	CMmeData g_oMmeData;
