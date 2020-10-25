#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		AsuFile.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		ASU�f�[�^�t�@�C���ǂݍ��ݏ����N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2016.02.01 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "IdbFile.h"
#include "DataDateTime.h"
#include "ProgressBar.h"

// ASU��ini�t�@�C����
#define ASU_INI_FILE "asu.ini"

class CAsuFile
{
	// �����x�f�[�^
	struct SAccData {
		double fDataX;
		double fDataY;
		double fDataZ;
	};

public:
	CAsuFile(void);
	~CAsuFile(void);

	CString m_sComment;
	CString m_sDownlink;
	BOOL m_bOverWrite;
	int m_nSampleSpan;

	static void ReadSensorInfo();
	BOOL CheckDataFile(LPCTSTR pDataFile, double &fSampleRate, CDataDateTime &oStartTime, CDataDateTime &oEndTime, int &nMeasTime);
	BOOL ConvDataFile();

protected:
	CString m_sDataFile;
	double m_fSampleRate;
	CDataDateTime m_oStartTime;
	CDataDateTime m_oEndTime;
	int m_nTotalDataCount;
	CIdbFile m_oIdbFile;

	BOOL GetData(const CString &sData, CDataDateTime &oTime, double &fDataX, double &fDataY, double &fDataZ);
	BOOL CheckExistFile(const CDataDateTime &oStartTime, const CDataDateTime &oEndTime);
	void GetOutputFolderName(const CDataDateTime &oStartTime, const CDataDateTime &oEndTime, CString &sFolderPath);
	static BOOL ConvDataThread(LPVOID pParam, CProgressBar *pProgressBar);
	BOOL ConvDataThread2(CProgressBar *pProgressBar);
	BOOL WriteDatabase(CProgressBar *pProgressBar);
	void SetIdbFile(const CString &sFolderPath, const CDataDateTime &oStartTime, const CDataDateTime &oEndTime);
	int CompareDateTime(const CDataDateTime &oDate1, const CDataDateTime &oDate2);
	BOOL WriteDataFile(const CString &sFilePath, SAccData *pDataBuf, UINT nDataOffset, UINT nWriteDataSize, CProgressBar *pProgressBar, INT64 &nProgressCount);
	void WriteXmlFile(const CString &sFileName, const CDataDateTime &oDataStartTime);
	void WriteIdbFile(const CDataDateTime &oDataStartTime);
};
