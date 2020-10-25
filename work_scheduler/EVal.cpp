// EVal.cpp: CEVal クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "EVal.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

void CEVal::error()
{
	::AfxThrowNotSupportedException();
}

void CEVal::readch()
{
	do {
		if (m_sExpression.IsEmpty()) {
			m_ch = EOF;
			return;
		}
		m_ch = *m_sExpression.Left(1);
		m_sExpression = m_sExpression.Mid(1);
	} while (m_ch == ' ' || m_ch == '\t');
}

double CEVal::number()  /* 数 */
{
	double x, a;
	int sign;

	if (m_ch == '+' || m_ch == '-') {
		sign = m_ch;  readch();
	}
	if (! isdigit(m_ch)) error();
	x = m_ch - '0';
	while (readch(), isdigit(m_ch))
		x = 10 * x + m_ch - '0';
	if (m_ch == '.') {
		a = 1;
		while (readch(), isdigit(m_ch))
			x += (a /= 10) * (m_ch - '0');
	}
	if (sign == '-') return -x;  else return x;
}

double CEVal::factor()  /* 因子 */
{
	double x;

	if (m_ch != '(') return number();
	readch();  x = expression();
	if (m_ch != ')') error();
	readch();  return x;
}

double CEVal::term()  /* 項 */
{
	double x, y;

	x = factor();
	for ( ; ; )
		if (m_ch == '*') {
			readch();  x *= factor();
		} else if (m_ch == '/') {
			readch();  y = factor();
			if (y == 0) error();
			x /= y;
		} else break;
	return x;
}

double CEVal::expression()  /* 式 */
{
	double x;

	x = term();
	for ( ; ; )
		if (m_ch == '+') {
			readch();  x += term();
		} else if (m_ch == '-') {
			readch();  x -= term();
		} else break;
    return x;
}

double CEVal::GetValue(LPCTSTR pExpression)
{
	m_sExpression = pExpression;

	try {
		readch();
		return expression();
	} catch (...) {
		return 0;
	}
}
