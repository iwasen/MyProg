// EVal.h: CEVal クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EVAL_H__C28EDAD4_0FB8_4866_8C28_2CFCD3796D2F__INCLUDED_)
#define AFX_EVAL_H__C28EDAD4_0FB8_4866_8C28_2CFCD3796D2F__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

class CEVal  
{
public:
	double GetValue(LPCTSTR pExpression);

protected:
	CString m_sExpression;
	int m_ch;

	void error();
	void readch();
	double number();
	double factor();
	double term();
	double expression();
};

#endif // !defined(AFX_EVAL_H__C28EDAD4_0FB8_4866_8C28_2CFCD3796D2F__INCLUDED_)
