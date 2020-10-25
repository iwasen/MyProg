#pragma once

#include "IdbFile.h"
#include "ProgressBar.h"

#define N_UNIT	3
#define UNIT_1	0
#define UNIT_2	1
#define UNIT_3	2
#define N_RANGE	3
#define RANGE_LO	0
#define RANGE_HI	1
#define RANGE_EX	2
#define N_AXIS	3
#define AXIS_X	0
#define AXIS_Y	1
#define AXIS_Z	2

class CMmeFile
{
	// 加速度データスケールファクタ
	struct SAccFactor {
		struct SUnit {
			struct SAxis {
				double range[N_RANGE];
			} axis[N_AXIS];
		} unit[N_UNIT];
	};

	// ユーザヘッダ情報
	struct SUserHeader {
		UINT nPayloadId;
		UINT nDataId;
		UINT nFrameId;
		UINT nEndSegment;
		UINT nSegmentCount;
		UINT nTimestamp;
		struct {
			UINT nMeasurementMode;
			UINT nMeasurementRange;
			UINT nSamplingRate;
			UINT nDataNum;
			UINT nUnitRange[N_UNIT];
		} TelemetryPacket;
	};

	// 加速度データ
	struct SAccData {
		short nValidFlag;
		short nAccData[N_AXIS];
	};

	// タイムスタンプ別データブロック
	struct STimestampBlock {
		UINT nStartTimestamp;
		UINT nEndTimestamp;
		UINT nLastSeqCount;
		SAccData *pAccData;

		STimestampBlock() {
			pAccData = NULL;
		}

		~STimestampBlock() {
			if (pAccData != NULL)
				delete [] pAccData;
		}
	};

	typedef CMap <UINT, UINT, UINT, UINT> CMapTimestamp;
	typedef CMapTimestamp TimestampMap[N_UNIT][N_RANGE];

	typedef CArray <UINT, UINT> CArrayTimestamp;
	typedef CArrayTimestamp TimestampArray[N_UNIT][N_RANGE];

	typedef CArray <STimestampBlock, STimestampBlock&> CArrayTimestampBlock;
	typedef CArrayTimestampBlock DataBlock[N_UNIT][N_RANGE];

public:
	CMmeFile(void);
	~CMmeFile(void);

	CString m_sComment;
	CString m_sDownlink;
	BOOL m_bOverWrite;

	BOOL ReadIniFile();
	static void ReadSensorInfo();
	BOOL CheckDataFile(LPCTSTR pDataFolder, CTime &oStartTime, CTime &oEndTime, int &nMeasTime);
	BOOL ConvDataFile();

private:
	CString m_sDataFolder;
	SAccFactor m_oAccFactor;
	UINT m_nSamplingRate[N_RANGE];
	CIdbFile m_oIdbFile;
	UINT m_nPacketCount;
	UINT m_nTotalDataCount;
	CArray <CTime, CTime> m_oLeapSecond;

	void GetDataFiles(CStringArray &saDataFiles);
	ULONGLONG GetTotalFileSize(const CStringArray &saDataFiles);
	BOOL CheckExistFile(const DataBlock &oDataBlock);
	static BOOL ConvDataThread(LPVOID pParam, CProgressBar *pProgressBar);
	BOOL ConvDataThread2(CProgressBar *pProgressBar);
	BOOL ReadDataPacket(CFile &oFile, SUserHeader *pUserHeader, void *pDataBuf);
	BOOL GetTimestamp(const CStringArray &saDataFiles, TimestampArray &oTimestampArray, CProgressBar *pProgressBar);
	void GetTimestampArray(const CMapTimestamp &oMapTimestamp, CArrayTimestamp &oArrayTimestamp);
	static int CompareTimestamp(const void *p1, const void *p2);
	void GetDataBlock(const TimestampArray &oTimestampArray, DataBlock &oDataBlock);
	void AllocateBuf(DataBlock &oDataBlock);
	BOOL ReadData(DataBlock &oDataBlock, CProgressBar *pProgressBar);
	BOOL WriteDatabase(DataBlock &oDataBlock, CProgressBar *pProgressBar);
	void GetOutputFolderName(int nUnit, int nRange, UINT nStartTimestamp, UINT nEndTimestamp, CString &sOutputFileName);
	void SetIdbFile(const CString &sFolderPath, const STimestampBlock &oTimestampBlock, int nUnit, int nRange);
	void ReplaceInvalidData(STimestampBlock &oTimestampBlock, UINT nDataCount);
	BOOL WriteDataFile(const CString &sFilePath, const STimestampBlock &oTimestampBlock, UINT nDataOffset, UINT nWriteDataSize, int nUnit, int nRange, CProgressBar *pProgressBar, INT64 &nDataSize);
	void WriteXmlFile(const CString &sFileName, const CTime &oDataStartTime);
	void WriteIdbFile(const STimestampBlock &oTimestampBlock);
	CTime GetTime(UINT nTimestamp);
};
