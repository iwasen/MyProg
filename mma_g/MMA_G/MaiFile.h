// MaiFile.h: CMaiFile クラスのインターフェイス
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
	long	m_StartYY;	// 開始時刻：年
	long	m_StartMM;	// 開始時刻：月
	long	m_StartDD;	// 開始時刻：日
	long	m_StartMS;	// 開始時刻：m秒
	int		m_RsuId;	// RSU ID
	int		m_NetworkId;// ネットワークID
	int		m_SensorId;	// センサーID
	double	m_SampleRate;	// サンプルレート
	int		m_CntPerHour;	// １時間のデータ数
	int		m_Cutofff;	// カットオフ周波数
	int		m_Offset;	// オフセット
	CString m_maiFname;	// maiファイル名（PATHなし）
	CStringArray	m_BinName0;	// チャンネルNo0のBINファイル名保存エリア
	CStringArray	m_BinName1;	// チャンネルNo1のBINファイル名保存エリア
	CStringArray	m_BinName2;	// チャンネルNo2のBINファイル名保存エリア
	CStringArray	m_BinName3;	// チャンネルNo3のBINファイル名保存エリア
	CString m_ConvDataPath;	// 工学値変換データ書き込みフォルダ
	// IDBファイル情報
	CIdbFile m_Idb;
	CDataDateTime m_In_startTime;
	CStringArray	m_Name;		// 指定フォルダに存在する全てのmaiファイル名保存エリア

private:
	CStringArray	m_maiDat;	// maiファイルの内容を全て読み込むエリア
// update 2008/03/18 by hama　→
	CStringArray	m_Rsu;	// maiファイル名のRSU情報保存エリア
// update 2008/03/18 by hama　←
	CArray<long, long> m_Samples0;	// 0ファイルのデータ個数
	CArray<long, long> m_Samples1;	// 1ファイルのデータ個数
	CArray<long, long> m_Samples2;	// 2ファイルのデータ個数
	CArray<long, long> m_Samples3;	// 3ファイルのデータ個数
	long	m_Total0;	// 0ファイルのデータ個数総数
	long	m_Total1;	// 1ファイルのデータ個数総数
	long	m_Total2;	// 2ファイルのデータ個数総数
	long	m_Total3;	// 3ファイルのデータ個数総数
	CString m_WorkPath;	// 結合ファイル書き込みフォルダ
	CString	m_path;		// 書き込みフォルダパス
	long	m_Start_msec;
	long	m_End_msec;
	// 工学値変換クラスポインタ
	CCalcFile* m_CalcFile;

public:
	int ReadMaiFile(LPCSTR path, CDataDateTime * start, CDataDateTime * end);
	int ReadMaiFile2(LPCSTR maiPath, BOOL bDispErrMsg);
	void ResetData(void);
	BOOL WriteData(LPCSTR p_path, long Start_msec, long End_msec);
	// 工学値データ書き込みフォルダ作成
	int mkDir(LPCSTR pos, LPCSTR category, LPCTSTR measurement_kind);
	// BINファイルのヘッダ情報をIDBに保存
	void GetBinInfo(LPCSTR fname);
	BOOL GetItems();
	void GetStartEnd(CDataDateTime *start, CDataDateTime *end);

private:
	int GetMaiFileName(LPCSTR path);
// update 2008/03/18 by hama　→
	int GetRsuFromMaiFileName(void);
	bool IsRsu(LPCSTR sen);
// update 2008/03/18 by hama　←
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
