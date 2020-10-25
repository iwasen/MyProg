#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		RealtimeData.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイム表示クラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2008.12.01 S.Aizawa 新規作成
//*****************************************************************************************************

#include "GraphFrm.h"
#include "IdbFile.h"
#include "afxmt.h"

// 配列テンプレートの拡張
template< class TYPE, class ARG_TYPE >
class CArrayEx : public CArray < TYPE, ARG_TYPE >
{
public:
	CArrayEx& operator=(const CArrayEx& other) {
		Copy(other);
		return *this;
	}
};

// バイナリファイルクラス
class CRealtimeBinFile
{
public:
	CRealtimeBinFile();

	CString m_sPathName;
	int m_nBinDataOffset;
	int m_nBinDataSize;
	double m_fSampleRate;
};
typedef CArrayEx <CRealtimeBinFile, CRealtimeBinFile&> CRealtimeBinFileArray;

// バイナリデータクラス
class CRealtimeBinData
{
public:
	CRealtimeBinData();
	~CRealtimeBinData();

	CRealtimeBinFileArray m_aBinFile;
	int m_nBinFileIndex;
	BYTE *m_pBinBuf;
	int m_nBufSize;
	int m_nReadPtr;
	int m_aPrevNextBuf[5];
	BOOL m_bPrevNext;
	int m_aTempData[4];

	int GetBinDataSize();
	void SeekBinFile(int nSeekSize);
	int GetBinData();
	unsigned int GetBinDataUnsigned();	// 2010/9/24 modified by y.GOTO for ver.4.02
	int GetAccelData();
//	void GetTempData(int nOffset, int &tdacm, int &tx, int &ty, int &tz);			// 2010/9/24 modified by y.GOTO for ver.4.02
	void GetTempData(int nOffset, unsigned int &tdacm, int &tx, int &ty, int &tz);	// 2010/9/24 modified by y.GOTO for ver.4.02
	void ReadBinFile(int nOffset);
	void FreeBuf();
};

// リアルタイムグラフクラス
class CRealtimeGraph
{
public:
	CRealtimeGraph();
	~CRealtimeGraph();

	int m_nRsuId;
	CString m_sStartDateTime;
	int m_nTaaId;
	CRealtimeBinData m_aBinData[4];
	CGraphFrame *m_pGraphAccl;
	CGraphFrame *m_pGraphTemp;
	int m_nIdbSize;
	BOOL m_bUpdateGraph;
	CDataDateTime m_dtStartTime;
	int m_nCntPerHour;
	int m_nCutOff;
	int m_nOffset;
	BOOL m_bExistLossFile;
	int m_nStartOffset;

public:
	CString GetIdbName();
	CString GetIdbPathName();
	int GetBinDataSize();
	void MakeIdbFile();

protected:
	void ConvertFile(CIdbFile &oIdbFile);
};
typedef CArray <CRealtimeGraph, CRealtimeGraph&> CRealtimeGraphArray;

// リアルタイムデータ処理クラス
class CRealtimeData
{
public:
	CRealtimeData();
	~CRealtimeData();

	void Start();
	void Stop();
	void UpdateGraphWindow();
	void CloseGraphWindow(HWND hWnd);
	void UpdateTempGraph(CGraphFrame *pGraphAccl, double fTimeDispEnd);

	BOOL m_bStartFlag;
	BOOL m_bStopFlag;

protected:
	HANDLE m_hThread;
	CEvent m_oEvent;
	BOOL m_bLoop;
	CRealtimeGraphArray m_aGraph;

	static UINT RealtimeDataThreadEntry(LPVOID pParam);
	void RealtimeDataThread();
	void GetBinFileInfo();
	void AddBinFile(const CString &sBinFileName, const CString &sBinPathName);
	void DeleteTempFiles();
	void DeleteIdbFile(LPCTSTR pIdbFilePath);
	void MoveOldBinData();
};

extern CRealtimeData g_RealtimeData;