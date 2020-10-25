#pragma once

class CStdioFileEX :
	public CStdioFile
{
public:
	CStdioFileEX(void);
	~CStdioFileEX(void);
private:
	int m_ReadCnt;
	int m_CurPos;
public:
	UINT ReadEX(void * lpBuf, UINT nCount);
private:
	char *m_ReadBuf;
public:
	BOOL OpenEX(LPCSTR lpszFileName, UINT nOpenFlags, CFileException * pError);
	void CloseEX(void);

// 2008/07/22 appended by yG for adjust a/d converter error ->
private:
	char m_caSecondPreviousData[3];
	char m_caFirstPreviousData[3];
	char m_caCurrentData[3];
public:
	void GetSecondPrevious(char* out_caBuffer);
	void GetFirstPrevious(char* out_caBuffer);
	void GetFirstNext(char* out_caBuffer);
	void GetSecondNext(char* out_caBuffer);
// <- 2008/07/22 appended by yG for adjust a/d converter error
};
