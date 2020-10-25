#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		AutoConv.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動工学値変換クラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2013.01.31 S.Aizawa 新規作成
//*****************************************************************************************************

#include "MaiFile.h"
#include "afxmt.h"

class CAutoConv  
{
public:
	CAutoConv();
	~CAutoConv();
	void ExecAutoConvert(CMaiFile *pMaiFile, CString *pMaiFilePath);

	HWND m_hMainWnd;

protected:
	static UINT AutoConvertThreadEntry(LPVOID pParam);
	void CheckAutoConvert();
	BOOL GetNewestMaiFile(CMaiFile &oMaiFile, const CString &sFileName);
	void GetMaiFiles(CMaiFile &oMaiFile, const CString &sFileName);
	BOOL CheckDataFileExist(CMaiFile &oMaiFile);
	BOOL CheckBinFileExist(const CStringArray &aBinFiles);
	BOOL ConvertProc(CMaiFile &oMaiFile, const CString &sMaiPathName);
	int GetSensorIndex(int nRsuId);
	void MoveDataFiles(const CMaiFile &oMaiFile, const CString sFolder);
	void MoveBinFiles(const CStringArray &aBinFiles, const CString &sMoveFolder);

	CEvent m_oEvent;
	BOOL m_bExit;
	HANDLE m_hThread;
};
