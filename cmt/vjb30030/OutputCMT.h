// OutputCMT.h: COutputCMT クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_OUTPUTCMT_H__8E833C98_66EA_40CB_8C78_7923C7E1CC2A__INCLUDED_)
#define AFX_OUTPUTCMT_H__8E833C98_66EA_40CB_8C78_7923C7E1CC2A__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "OutputDevice.h"
#include "MTDriver.h"

#define LEN_FILENAME	128

class COutputCMT : public COutputDevice  
{
public:
	COutputCMT();
	virtual ~COutputCMT();

	int CheckVolumeName(Queue *pQueue);
	int OutputFiles(Queue *pQueue);
	void GetError(int &nErrorCode, CString &sErrorMsg);

protected:
	CMTDriver m_MTDriver;
	BOOL m_bOpen;
	BOOL m_bLabelCheck;
	CString m_sSystemID;

	int WriteCMT(CString &sSrcDir, char (*pFileBuf)[LEN_FILENAME], int nFileCount, CString *sVolLabel, int nVolLabel);
	static int CompareFileName(const void *arg1, const void *arg2);
	int SplitVolumeLabel(Queue *pQueue, CString *pVolLabel);
	BOOL CheckFileName(LPCTSTR pFileName);
	BOOL AnalyzeFileName(LPCTSTR pFileName, CString *pFileID, int *pRecordLength, int *pBlockingFactor);
	BOOL IsNumber(LPCTSTR pNum);
};

#endif // !defined(AFX_OUTPUTCMT_H__8E833C98_66EA_40CB_8C78_7923C7E1CC2A__INCLUDED_)
