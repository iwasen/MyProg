#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		DataFile.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データファイルクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "ProgressBar.h"
#include "datadatetime.h"

// 情報ファイルのヘッダ部
struct INFO_FILE_HEADER {
	INT32 nSeqNo;
	INT32 nDataSize;
	INT32 nDataSizeHour;
	char sFiller[4];
};

class CStringArrayEx : public CStringArray
{
public:
	int operator=(CStringArrayEx&)
	{
		return 0;
	}
};

class CDataFile
{
public:
	CDataFile();
	~CDataFile();

	BOOL ReadInfoFile(LPCTSTR pFilePath);
	BOOL ReadDataFile(int nBeginTime, int nEndTime, BOOL bReadAccelData, BOOL bReadTempData, BOOL bProgressBar);
	BOOL ReadWriteDataFile(LPCSTR fname, int nBeginTime, int nEndTime, BOOL bReadTempData);

public:
	CString m_sDatPath;
	double *m_pTempDACM;
	double *m_pTempX;
	double *m_pTempY;
	double *m_pTempZ;
	double *m_pDataX;
	double *m_pDataY;
	double *m_pDataZ;
	int m_nDataSize;
	double m_fSampleRate;
	int m_nStartYear;
	int m_nStartMonth;
	int m_nStartDay;
	int m_nStartMiliSecond;
	double m_fStartSecond;
	double m_fTotalSecond;
	CStringArrayEx m_aDataFileName;
	int m_nDownRate;
	CString	m_WriteFilePath;
	int m_nBeginTime;
	int	m_nSensorId;
	int	m_nGain;
	int	m_nCutoff;
	int	m_nSensorIdx;
	CString	m_SensorPos;
	CString	m_IssConfig;
	CDataDateTime m_dStarttime;
	CDataDateTime m_dInStarttime;
	CDataDateTime m_dInEndtime;
	double m_fAllocFactor;
	int m_nTotalDataSize;
	BOOL m_bTempOnly;
	int m_nDataType;

protected:
	BOOL GetTotalTime();
	static BOOL ReadBinaryFileThread(LPVOID pParam, CProgressBar *pProgressBar);
	static BOOL WriteBinaryFileThread(LPVOID pParam, CProgressBar *pProgressBar);
	BOOL ReadBinaryFileThread2(CProgressBar *pProgressBar);
	BOOL WriteBinaryFileThread2(CProgressBar *pProgressBar);
	void AllocBuf();
	double *ReallocBuf(const double *pOldBuf, int nOldSize, int nNewSize);
	void FreeBuf();

protected:
	int m_nDataOffset;
	BOOL m_bReadAccelData;
	BOOL m_bReadTempData;
	int m_nAllocSize;
	int m_nReadSize;
};
