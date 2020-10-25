// MaiFile.h: CMaiFile �N���X�̃C���^�[�t�F�C�X
//
//////////////////////////////////////////////////////////////////////

#pragma once

#include "DataDateTime.h"
#include "idbfile.h"
#include "ProgressBar.h"
#include "CalcFile.h"

class CMaiFile  
{
public:
	CMaiFile();
	virtual ~CMaiFile();

public:
	long	m_StartYY;	// �J�n�����F�N
	long	m_StartMM;	// �J�n�����F��
	long	m_StartDD;	// �J�n�����F��
	long	m_StartMS;	// �J�n�����Fm�b
	int		m_RsuId;	// RSU ID
	int		m_NetworkId;// �l�b�g���[�NID
	int		m_SensorId;	// �Z���T�[ID
	double	m_SampleRate;	// �T���v�����[�g
	int		m_CntPerHour;	// �P���Ԃ̃f�[�^��
	int		m_Cutofff;	// �J�b�g�I�t���g��
	int		m_Offset;	// �I�t�Z�b�g
	CString m_maiFname;	// mai�t�@�C�����iPATH�Ȃ��j
	CStringArray	m_BinName0;	// �`�����l��No0��BIN�t�@�C�����ۑ��G���A
	CStringArray	m_BinName1;	// �`�����l��No1��BIN�t�@�C�����ۑ��G���A
	CStringArray	m_BinName2;	// �`�����l��No2��BIN�t�@�C�����ۑ��G���A
	CStringArray	m_BinName3;	// �`�����l��No3��BIN�t�@�C�����ۑ��G���A
	CString m_ConvDataPath;	// �H�w�l�ϊ��f�[�^�������݃t�H���_
	// IDB�t�@�C�����
	CIdbFile m_Idb;
	CDataDateTime m_In_startTime;
	CStringArray	m_Name;		// �w��t�H���_�ɑ��݂���S�Ă�mai�t�@�C�����ۑ��G���A

private:
	CStringArray	m_maiDat;	// mai�t�@�C���̓��e��S�ēǂݍ��ރG���A
// update 2008/03/18 by hama�@��
	CStringArray	m_Rsu;	// mai�t�@�C������RSU���ۑ��G���A
// update 2008/03/18 by hama�@��
	CArray<long, long> m_Samples0;	// 0�t�@�C���̃f�[�^��
	CArray<long, long> m_Samples1;	// 1�t�@�C���̃f�[�^��
	CArray<long, long> m_Samples2;	// 2�t�@�C���̃f�[�^��
	CArray<long, long> m_Samples3;	// 3�t�@�C���̃f�[�^��
	long	m_Total0;	// 0�t�@�C���̃f�[�^������
	long	m_Total1;	// 1�t�@�C���̃f�[�^������
	long	m_Total2;	// 2�t�@�C���̃f�[�^������
	long	m_Total3;	// 3�t�@�C���̃f�[�^������
	CString m_WorkPath;	// �����t�@�C���������݃t�H���_
	CString	m_path;		// �������݃t�H���_�p�X
	long	m_Start_msec;
	long	m_End_msec;
	// �H�w�l�ϊ��N���X�|�C���^
	CCalcFile* m_CalcFile;

public:
	int ReadMaiFile(LPCSTR path, CDataDateTime * start, CDataDateTime * end);
	int ReadMaiFile2(LPCSTR maiPath, BOOL bDispErrMsg);
	void ResetData(void);
	BOOL WriteData(LPCSTR p_path, long Start_msec, long End_msec);
	// �H�w�l�f�[�^�������݃t�H���_�쐬
	int mkDir(LPCSTR pos, LPCSTR category, LPCTSTR measurement_kind);
	// BIN�t�@�C���̃w�b�_����IDB�ɕۑ�
	void GetBinInfo(LPCSTR fname);
	BOOL GetItems();
	void GetStartEnd(CDataDateTime *start, CDataDateTime *end);

private:
	int GetMaiFileName(LPCSTR path);
// update 2008/03/18 by hama�@��
	int GetRsuFromMaiFileName(void);
	bool IsRsu(LPCSTR sen);
// update 2008/03/18 by hama�@��
	bool GetMaxMaiFileName(CString &fname, CString Sensor);
	int GetMaFileiData(LPCSTR path);
	bool GetDataFromMai(LPCSTR dat, LPCSTR itemStr, CString& oDat);
	bool FuriwakeBinFileName(void);
	int GetChNoFromFName(LPCSTR fname);
	int DelSameName(void);
	void SortArr(void);
	void RecalcTotal(void);
	bool GetStartTime(void);
	bool GetStartYMDmS(LPCSTR str, long * YY, long * MM, long * DD, long * mS);
	bool GetUnitID(void);
	bool GetSensorID(void);
	bool GetSampleRate(void);
	BOOL WriteKetugouFile(CProgressBar* pProgressBar);
	BOOL WriteCalcData(CProgressBar* pProgressBar);
	static	BOOL WriteDataThread(LPVOID pParam, CProgressBar* pProgressBar);
	static	BOOL WriteDataThread2(LPVOID pParam, CProgressBar* pProgressBar);
};
