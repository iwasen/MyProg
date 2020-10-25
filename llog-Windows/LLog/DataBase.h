#pragma once

#include "libpq-fe.h"

class CDbByteData
{
public:
	CDbByteData();
	~CDbByteData();

	void operator=(BYTE *pData);
	operator BYTE*();

protected:
	BYTE *m_pData;
};

class CDbPoint
{
public:
	CDbPoint();

public:
	void SetData(double x, double y);
	void SetNull();

	double m_fX;
	double m_fY;
	BOOL bNull;
};

class CDbResult
{
public:
	CDbResult();
	~CDbResult();

	BOOL Exec(PGconn *pConn, LPCTSTR pSql);
	int GetCount();
	BOOL IsNull(int nRecNo, LPCTSTR pFieldName);
	LPTSTR GetTextValue(int nRecNo, LPCTSTR pFieldName);
	int GetIntValue(int nRecNo, LPCTSTR pFieldName);
	double GetFloatValue(int nRecNo, LPCTSTR pFieldName);
	CTime GetDateValue(int nRecNo, LPCTSTR pFieldName);
	CTime GetDateTimeValue(int nRecNo, LPCTSTR pFieldName);
	BOOL GetBoolValue(int nRecNo, LPCTSTR pFieldName);
	int GetByteaValue(int nRecNo, LPCTSTR pFieldName, CDbByteData &oDbByteData);
	void GetPointValue(int nRecNo, LPCTSTR pFieldName, CDbPoint &oDbPoint);

protected:
	void Clear();

	PGresult *m_pResult;
};

class CDataBase
{
public:
	CDataBase(void);
	~CDataBase(void);

	BOOL Open();
	BOOL Select(LPCTSTR pSql, CDbResult &oDbResult);
	BOOL Exec(LPCTSTR pSql);
	CString SqlText(LPCTSTR pText);
	CString SqlNumber(LPCTSTR pNumber);
	CString SqlTimestamp(CTime oTime);
	CString SqlDate(CTime oTime);
	CString SqlYM(CTime oTime);
	CString SqlByteA(BYTE *pByte, int nSize);
	CString SqlPoint(CDbPoint &oDbPoint);

protected:
	PGconn *m_pConn;
};
