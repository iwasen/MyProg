#include "stdafx.h"
#include "DataDateTime.h"

CDataDateTime::CDataDateTime(void)
: m_YY(0)
, m_MM(0)
, m_DD(0)
, m_mS(0)
, m_hh(0)
, m_mm(0)
, m_ss(0)
, m_ttt(0)
{
}

CDataDateTime::CDataDateTime(long YY, long MM, long DD, long mS)
{
	m_YY = YY;
	m_MM = MM;
	m_DD = DD;
	m_mS = mS;
	CnvHHMMSS();
}

CDataDateTime::~CDataDateTime(void)
{
}

void CDataDateTime::SetTime(long YY, long MM, long DD, long mS)
{
	m_YY = YY;
	m_MM = MM;
	m_DD = DD;
	m_mS = mS;
	CnvHHMMSS();
}

void CDataDateTime::SetTime(long YY, long MM, long DD, long hh, long mm, long ss, long ttt)
{
	m_YY = YY;
	m_MM = MM;
	m_DD = DD;
	m_mS = 0;
	m_hh = hh;
	m_mm = mm;
	m_ss = ss;
	m_ttt = ttt;
}

const CDataDateTime& CDataDateTime::operator=(const CDataDateTime& src)
{
	m_YY = src.m_YY;
	m_MM = src.m_MM;
	m_DD = src.m_DD;
	m_mS = src.m_mS;
	m_hh = src.m_hh;
	m_mm = src.m_mm;
	m_ss = src.m_ss;
	m_ttt = src.m_ttt;

	return src;
}

CDataDateTime CDataDateTime::operator+(long mS)
{
	return CDataDateTime(m_YY, m_MM, m_DD
		, m_hh * 60 * 60 * 1000	+ m_mm * 60 * 1000 + m_ss * 1000 + m_ttt + mS);
}

CDataDateTime& CDataDateTime::operator+=(long mS)
{
	m_mS = m_hh * 60 * 60 * 1000 + m_mm * 60 * 1000 + m_ss * 1000 + m_ttt + mS;
	CnvHHMMSS();

	return *this;
}

// mS������t�������Z�o����
int CDataDateTime::CnvHHMMSS(void)
{
	long hh;
	int	mS = m_mS;
	int	nDay = 0;

	// ���Z�o
	hh = (int)(mS / 60 / 60 / 1000);
	nDay = hh / 24;
	m_hh = hh % 24;
	mS -= hh * 60 * 60 * 1000;
	// ���Z�o
	m_mm = (int)(mS / 60 / 1000);
	mS -= m_mm * 60 * 1000;
	// �b�Z�o
	m_ss = (int)(mS / 1000);
	mS -= m_ss * 1000;
	// m�b�Z�o
	m_ttt = mS;
	// �Z�o�I������̂�mS�̓N���A
	m_mS = mS;

	// ���t�̌J��オ�肪����ꍇ
	if (nDay > 0) {
		// ���t���v���X����
		PlusNday(nDay);
	}
	return 0;
}

// ���t���v���X����
int CDataDateTime::PlusNday(int nDay)
{
	struct tm	ymd;

	memset(&ymd, 0, sizeof(ymd));
	ymd.tm_year = m_YY-1900;
	ymd.tm_mon = m_MM - 1;
	ymd.tm_mday = m_DD;

	ymd.tm_mday += nDay;
	if(mktime(&ymd) != (time_t)-1 ) {
		m_YY = ymd.tm_year + 1900;
		m_MM = ymd.tm_mon + 1;
		m_DD = ymd.tm_mday;
	}

	return 0;
}



long CDataDateTime::operator-(const CDataDateTime& other)
{
	struct tm ymd;
	time_t t0, t1;

	memset(&ymd, 0, sizeof(ymd));
	ymd.tm_year = other.m_YY - 1900;
	ymd.tm_mon = other.m_MM - 1;
	ymd.tm_mday = other.m_DD;
	ymd.tm_hour = other.m_hh;
	ymd.tm_min = other.m_mm;
	ymd.tm_sec = other.m_ss;
	if ((t1 = mktime(&ymd)) == -1)
		return 0;

	memset(&ymd, 0, sizeof(ymd));
	ymd.tm_year = m_YY - 1900;
	ymd.tm_mon = m_MM - 1;
	ymd.tm_mday = m_DD;
	ymd.tm_hour = m_hh;
	ymd.tm_min = m_mm;
	ymd.tm_sec = m_ss;
	if ((t0 = mktime(&ymd)) == -1)
		return 0;

	return (long)(t0 - t1) * 1000;
}


CString CDataDateTime::GetStr(void)
{
	CString	str;

	str.Format("%04d/%02d/%02d %02d:%02d:%02d.%03d"
		, m_YY
		, m_MM
		, m_DD
		, m_hh
		, m_mm
		, m_ss
		, m_ttt);

	return str;
}

CString CDataDateTime::GetStrYMDHMS(void)
{
	CString	str;

	str.Format("%04d/%02d/%02d %02d:%02d:%02d"
		, m_YY
		, m_MM
		, m_DD
		, m_hh
		, m_mm
		, m_ss);

	return str;
}

CString CDataDateTime::GetStrHMS(void)
{
	CString	str;

	str.Format("%02d:%02d:%02d"
		, m_hh
		, m_mm
		, m_ss);

	return str;
}

CString CDataDateTime::GetStrHMST(void)
{
	CString	str;

	str.Format("%02d:%02d:%02d.%03d"
		, m_hh
		, m_mm
		, m_ss
		, m_ttt);

	return str;
}

CString CDataDateTime::GetStrYMD(void)
{
	CString	str;

	str.Format("%04d/%02d/%02d "
		, m_YY
		, m_MM
		, m_DD);

	return str;
}

CString CDataDateTime::GetStrAsctime(void)
{
	static const char *aMonth[] = {
		"January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"
	};
	CString str;

	str.Format("%02d-%s-%d %02d:%02d:%02d"
		, m_DD
		, aMonth[m_MM - 1]
		, m_YY
		, m_hh
		, m_mm
		, m_ss);

	return str;
}
