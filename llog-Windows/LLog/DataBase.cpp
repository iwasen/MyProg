#include "StdAfx.h"
#include "DataBase.h"

CDbByteData::CDbByteData()
{
	m_pData = NULL;
}

CDbByteData::~CDbByteData()
{
	if (m_pData != NULL)
		::PQfreemem(m_pData);
}

void CDbByteData::operator=(BYTE *pData)
{
	if (m_pData != NULL)
		::PQfreemem(m_pData);

	m_pData = pData;
}

CDbByteData::operator BYTE*()
{
	return m_pData;
}

CDbPoint::CDbPoint()
{
	m_fX = 0;
	m_fY = 0;
	bNull = TRUE;
}

void CDbPoint::SetData(double x, double y)
{
	m_fX = x;
	m_fY = y;
	bNull = FALSE;
}

void CDbPoint::SetNull()
{
	bNull = TRUE;
}

CDbResult::CDbResult()
{
	m_pResult = NULL;
}

CDbResult::~CDbResult()
{
	Clear();
}

BOOL CDbResult::Exec(PGconn *pConn, LPCTSTR pSql)
{
	Clear();

	m_pResult = ::PQexec(pConn, pSql);
	if (m_pResult != NULL) {
		ExecStatusType est = ::PQresultStatus(m_pResult);
		if (est == PGRES_COMMAND_OK || est == PGRES_TUPLES_OK)
			return TRUE;
	}

	::AfxMessageBox(::PQerrorMessage(pConn));
	return FALSE;
}

int CDbResult::GetCount()
{
	if (m_pResult == NULL)
		return 0;

	return ::PQntuples(m_pResult);
}

BOOL CDbResult::IsNull(int nRecNo, LPCTSTR pFieldName)
{
	return ::PQgetisnull(m_pResult, nRecNo, pFieldName != NULL ? ::PQfnumber(m_pResult, pFieldName) : 0);
}

LPTSTR CDbResult::GetTextValue(int nRecNo, LPCTSTR pFieldName)
{
	if (m_pResult == NULL)
		return "";

	return ::PQgetvalue(m_pResult, nRecNo, pFieldName != NULL ? ::PQfnumber(m_pResult, pFieldName) : 0);
}

int CDbResult::GetIntValue(int nRecNo, LPCTSTR pFieldName)
{
	return atoi(GetTextValue(nRecNo, pFieldName));
}

double CDbResult::GetFloatValue(int nRecNo, LPCTSTR pFieldName)
{
	return atof(GetTextValue(nRecNo, pFieldName));
}

CTime CDbResult::GetDateValue(int nRecNo, LPCTSTR pFieldName)
{
	CString sDate = GetTextValue(nRecNo, pFieldName);
	CTime oDate(atoi(sDate.Left(4)), atoi(sDate.Mid(5, 2)), atoi(sDate.Mid(8, 2)), 0, 0, 0);
	return oDate;
}

CTime CDbResult::GetDateTimeValue(int nRecNo, LPCTSTR pFieldName)
{
	CString sDate = GetTextValue(nRecNo, pFieldName);
	CTime oDate(atoi(sDate.Left(4)), atoi(sDate.Mid(5, 2)), atoi(sDate.Mid(8, 2)), atoi(sDate.Mid(11, 2)), atoi(sDate.Mid(14, 2)), atoi(sDate.Mid(17, 2)));
	return oDate;
}

BOOL CDbResult::GetBoolValue(int nRecNo, LPCTSTR pFieldName)
{
	return strcmp(GetTextValue(nRecNo, pFieldName), "t") == 0;
}

int CDbResult::GetByteaValue(int nRecNo, LPCTSTR pFieldName, CDbByteData &oDbByteData)
{
	int nField = ::PQfnumber(m_pResult, pFieldName);

	size_t nSize;
	oDbByteData = ::PQunescapeBytea((BYTE *)::PQgetvalue(m_pResult, nRecNo, nField), &nSize);

	return nSize;
}

void CDbResult::GetPointValue(int nRecNo, LPCTSTR pFieldName, CDbPoint &oDbPoint)
{
	CString sData = GetTextValue(nRecNo, pFieldName);
	if (sData.IsEmpty())
		oDbPoint.bNull = TRUE;
	else {
		::sscanf_s(sData, "(%f,%f)", oDbPoint.m_fX, oDbPoint.m_fY);
		oDbPoint.bNull = FALSE;
	}
}

void CDbResult::Clear()
{
	if (m_pResult != NULL) {
		::PQclear(m_pResult);
		m_pResult = NULL;
	}
}

//////////////////////////////////////////////////////////////////////////////

CDataBase::CDataBase(void)
{
	m_pConn = NULL;
}

CDataBase::~CDataBase(void)
{
	if (m_pConn != NULL)
		::PQfinish(m_pConn);
}

BOOL CDataBase::Open()
{
	m_pConn = ::PQconxxxtdb("host=inet.troot.co.jp port=5432 dbname=llog user=postgres password=");
	if (::PQstatus(m_pConn) == CONxxxTION_BAD) {
		::AfxMessageBox(::PQerrorMessage(m_pConn));
		return FALSE;
	}

	::PQsetClientEncoding(m_pConn, "SJIS");

	return TRUE;
}

BOOL CDataBase::Select(LPCTSTR pSql, CDbResult &oDbResult)
{
	if (!oDbResult.Exec(m_pConn, pSql))
		return FALSE;

	return TRUE;
}

BOOL CDataBase::Exec(LPCTSTR pSql)
{
	CDbResult oCDbResult;

	if (!oCDbResult.Exec(m_pConn, pSql))
		return FALSE;

	return TRUE;
}

CString CDataBase::SqlText(LPCTSTR pText)
{
	if (pText[0] == '\0')
		return "NULL";

	int nSize = strlen(pText) * 2 + 1;
	CString sText;

	::PQescapeStringConn(m_pConn, sText.GetBuffer(nSize), pText, nSize, NULL);

	CString sText2;
	sText2.Format("'%s'", sText);
	return sText2;
}

CString CDataBase::SqlNumber(LPCTSTR pNumber)
{
	if (*pNumber == '\0')
		return "NULL";
	else
		return pNumber;
}

CString CDataBase::SqlTimestamp(CTime oTime)
{
	return oTime.Format("'%Y-%m-%d %H:%M:%S'");
}

CString CDataBase::SqlDate(CTime oTime)
{
	return oTime.Format("'%Y-%m-%d'");
}

CString CDataBase::SqlYM(CTime oTime)
{
	return oTime.Format("'%Y%m'");
}

CString CDataBase::SqlByteA(BYTE *pByte, int nSize)
{
	size_t nToSize;

	BYTE *p = ::PQescapeByteaConn(m_pConn, pByte, nSize, &nToSize);
	CString sData;
	sData.Format("'%s'", p);
	::PQfreemem(p);

	return sData;
}

CString CDataBase::SqlPoint(CDbPoint &oDbPoint)
{
	if (oDbPoint.bNull)
		return "NULL";

	CString sText;
	sText.Format("point(%.7f,%.7f)", oDbPoint.m_fX, oDbPoint.m_fY);
	return sText;
}
