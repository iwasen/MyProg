#pragma once

class CDataDateTime
{
public:
	CDataDateTime(void);
	CDataDateTime(long YY, long MM, long DD, long mS);
	~CDataDateTime(void);
private:
	long m_mS;

public:
	long m_YY;
	long m_MM;
	long m_DD;
	int m_hh;
	int m_mm;
	int m_ss;
	int m_ttt;

public:
	const CDataDateTime& operator=(const CDataDateTime& src);
	CDataDateTime operator+(long mS);
	CDataDateTime& operator+=(long mS);
	int CnvHHMMSS(void);
	int PlusNday(int nDay);
	void SetTime(long YY, long MM, long DD, long mS);
	long operator-(const CDataDateTime& other);
	void SetTime(long YY, long MM, long DD, long hh, long mm, long ss, long ttt = 0);
	CString GetStr(void);
	CString GetStrYMDHMS(void);
	CString GetStrHMS(void);
	CString GetStrHMST(void);
	CString GetStrYMD(void);
	CString GetStrAsctime(void);
};
