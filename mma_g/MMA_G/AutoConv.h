#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		AutoConv.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�����H�w�l�ϊ��N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2013.01.31 S.Aizawa �V�K�쐬
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
