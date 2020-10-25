// MTDriver.h: CMTDriver クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_MTDRIVER_H__7EFC897C_8F73_4CC2_BD92_0EB59493ED9D__INCLUDED_)
#define AFX_MTDRIVER_H__7EFC897C_8F73_4CC2_BD92_0EB59493ED9D__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "..\MT\MT_Drv.h"
#define	EXTERN	extern
#include "..\MT\MT_Prot0.h"
#include "..\MT\MT_Prot1.h"

#define RESULT_FILE_END		1
#define RESULT_VOLUME_END	2
#define	RESULT_FILE_ERROR	3
#define	RESULT_MT_ERROR		4

#define BUF_SIZE		65520		// I/Oバッファサイズ

class CMTDriver  
{
public:
	CMTDriver();
	virtual ~CMTDriver();

public:
	CWnd *m_pUpdateWnd;
	CString m_sSystemID;

	int Open(int nHAdapter, int nScsiID, int nScsiLUN);
	int Close();
	int GetError(CString &sErrorMsg);
	int Unload();
	int Rewind();
	int WriteFmk(int nCount);
	int Read();
	int Write(int nDataLen);
	int ReadVolumeLabel(CString &sVolLabel);
	int WriteStart();
	int WriteEnd();
	int WriteFileWithLabel(LPCTSTR pFileName, LPCTSTR pFileID, LPCTSTR pSystemID, int nRecordLength, int nBlockingFactor);
	int WriteFileNonLabel(LPCTSTR pFileName, int nRecordLength, int nBlockingFactor);
	int ReadFileWithLabel(LPCTSTR pDirName);
	int ReadFileNonLabel(LPCTSTR pFileName);

protected:
	long m_hHandle;
	MT_StatusData m_StatusData;
	int m_nErrorCode;
	CFile m_File;
	CString m_sVolumeID;
	CString m_sVolumeID2;
	CString m_sFileID;
	int m_nVolSeqNo;
	int m_nFileSeqNo;
	int m_nRecordLength;
	int m_nBlockingFactor;
	int m_nBlockLength;
	int m_nBlockCount;
	BOOL m_bBOT;
	BOOL m_nReadAhead;
	BYTE m_IOBuf[BUF_SIZE];
	char m_cVolumeStatus;

	int CheckHeader(CString &sFileID);
	int CheckEOForEOV();
	int WaitReady();
	int WriteFileToMT();
	int ReadFileFromMT();
	int WriteLabel1(LPCTSTR pHeaderLabel);
	int WriteLabel2(LPCTSTR pHeaderLabel);
	void SetString(LPTSTR pBuf, LPCTSTR pStr);
	void SetNumber(LPTSTR pBuf, int nNum, int nLength);
	void SetSystemDate(LPTSTR pBuf);
	int ReadConvert();
	int WriteConvert(int nBufSize);
	int CheckTapeMark();
	void EbcdicToJis(BYTE *buf, int len);
	void JisToEbcdic(BYTE *buf, int len);
};

#endif // !defined(AFX_MTDRIVER_H__7EFC897C_8F73_4CC2_BD92_0EB59493ED9D__INCLUDED_)
