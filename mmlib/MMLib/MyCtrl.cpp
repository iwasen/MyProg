// MyCtrl.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "MyCtrl.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMyDate

CMyDate::CMyDate()
{
}

CMyDate::~CMyDate()
{
}


BEGIN_MESSAGE_MAP(CMyDate, CEdit)
	ON_CONTROL_REFLECT(EN_UPDATE, OnUpdate)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMyDate メッセージ ハンドラ

CString &CMyDate::operator=(CString &param)
{
	CString str;

	if (!param.IsEmpty())
		str.Format("%s/%s/%s", (LPCTSTR)param.Left(4), (LPCTSTR)param.Mid(4, 2), (LPCTSTR)param.Mid(6, 2));

	SetWindowText(str);

	return param;
}

LPCTSTR CMyDate::operator=(LPCTSTR param)
{
	SetWindowText(param);

	return param;
}

CMyDate::operator CString()
{
	int nYear, nMonth, nDay;
	CString str;

	if (GetDate(nYear, nMonth, nDay))
		str.Format("%04d%02d%02d", nYear, nMonth, nDay);

	return str;
}

BOOL CMyDate::GetDate(int &nYear, int &nMonth, int &nDay)
{
	CString str;
	CString temp;

	GetWindowText(str);

	if (str.IsEmpty())
		return FALSE;

	temp = str.SpanExcluding("/");
	nYear = atoi(temp);
	if (nYear < 50)
		nYear += 2000;
	else if (nYear < 100)
		nYear += 1900;

	if (str.GetLength() == temp.GetLength())
		str.Empty();
	else
		str = str.Mid(temp.GetLength() + 1);

	temp = str.SpanExcluding("/");
	nMonth = atoi(temp);
	if (nMonth == 0)
		nMonth = 1;

	if (str.GetLength() == temp.GetLength())
		str.Empty();
	else
		str = str.Mid(temp.GetLength() + 1);

	temp = str.SpanExcluding("/");
	nDay = atoi(temp);
	if (nDay == 0)
		nDay = 1;

	return TRUE;
}

void CMyDate::OnUpdate() 
{
	CString str;
	CString temp;

	GetWindowText(str);
	temp = str.SpanIncluding("0123456789/");
	if (str.GetLength() != temp.GetLength()) {
		SetWindowText(temp);
		SetSel(temp.GetLength(), temp.GetLength());
	}
}
/////////////////////////////////////////////////////////////////////////////
// CMyEdit

CMyEdit::CMyEdit()
{
	m_bSetTextColor = FALSE;
	m_nTextColor = -1;
}

CMyEdit::~CMyEdit()
{
}


BEGIN_MESSAGE_MAP(CMyEdit, CEdit)
	ON_WM_CTLCOLOR_REFLECT()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMyEdit メッセージ ハンドラ

int CMyEdit::operator=(int param)
{
	CString str1, str2;

	str1.Format("%d", param);
	GetWindowText(str2);

	if (str1 != str2)
		SetWindowText(str1);

	return param;
}

double CMyEdit::operator=(double param)
{
	CString str1, str2;

	str1.Format("%g", param);
	GetWindowText(str2);

	if (str1 != str2)
		SetWindowText(str1);

	return param;
}

CString &CMyEdit::operator=(CString &param)
{
	CString str2;

	GetWindowText(str2);

	if (param != str2)
		SetWindowText(param);

	return param;
}

LPCTSTR CMyEdit::operator=(LPCTSTR param)
{
	CString str2;

	GetWindowText(str2);

	if (str2 != param)
		SetWindowText(param);

	return param;
}

CMyEdit &CMyEdit::operator=(CMyEdit &param)
{
	CString str;

	param.GetWindowText(str);
	SetWindowText(str);

	return param;
}

int CMyEdit::operator==(int param)
{
	GetWindowText(m_str);
	return atoi(m_str) == param;
}

int CMyEdit::operator!=(int param)
{
	GetWindowText(m_str);
	return atoi(m_str) != param;
}

CMyEdit::operator int()
{
	GetWindowText(m_str);
	return atoi(m_str);
}

CMyEdit::operator double()
{
	GetWindowText(m_str);
	return atof(m_str);
}

CMyEdit::operator CString()
{
	GetWindowText(m_str);
	return m_str;
}

void CMyEdit::Format(LPCTSTR lpszFormat, ...)
{
	va_list vl;
	char str[256];
	CString str2;

	va_start(vl, lpszFormat);
	vsprintf(str, lpszFormat, vl);

	GetWindowText(str2);

	if (str2 != str)
		SetWindowText(str);
}

void CMyEdit::Blank()
{
	CString str;

	GetWindowText(str);
	if (!str.IsEmpty())
		SetWindowText("");
}

void CMyEdit::LoadString(UINT nStringID)
{
	CString str1, str2;

	str1.LoadString(nStringID);

	GetWindowText(str2);

	if (str2 != str1)
		SetWindowText(str1);
}

void CMyEdit::FormatMessage(UINT nStringID, ...)
{
	va_list vl;
	char str[256];
	CString str2;

	va_start(vl, nStringID);
	str2.LoadString(nStringID);
	vsprintf(str, (LPCTSTR)str2, vl);

	GetWindowText(str2);

	if (str2 != str)
		SetWindowText(str);
}

void CMyEdit::SetTextColor(COLORREF color)
{
	if (!m_bSetTextColor) {
		m_Brush.CreateSolidBrush(::GetSysColor(COLOR_3DFACE));
		m_bSetTextColor = TRUE;
	}

	if (color != m_nTextColor) {
		m_nTextColor = color;
		Invalidate();
	}
}

HBRUSH CMyEdit::CtlColor(CDC* pDC, UINT nCtlColor) 
{
	if (m_bSetTextColor) {
		pDC->SetTextColor(m_nTextColor);
		pDC->SetBkMode(TRANSPARENT);
		return (HBRUSH)m_Brush;
	}

	return NULL;
}
/////////////////////////////////////////////////////////////////////////////
// CMyButton

CMyButton::CMyButton()
{
	m_bNotifyButtonDown = FALSE;
}

CMyButton::~CMyButton()
{
}


BEGIN_MESSAGE_MAP(CMyButton, CButton)
	ON_WM_LBUTTONDOWN()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMyButton メッセージ ハンドラ

BOOL CMyButton::operator=(BOOL param)
{
	if (GetCheck() != param)
		SetCheck(param);

	return param;
}

CMyButton::operator BOOL()
{
	return GetCheck();
}

void CMyButton::OnLButtonDown(UINT nFlags, CPoint point) 
{
	if (m_bNotifyButtonDown)
		GetParent()->SendMessage(WM_BUTTON_DOWN, 0, 0);
	else
		CButton::OnLButtonDown(nFlags, point);
}

// XPスタイルでグリップが正常に表示されないバグの対処
//////////////////////////////////////////////////////
// CMyToolBar

BEGIN_MESSAGE_MAP(CMyToolBar, CToolBar)
	ON_WM_NCPAINT()
END_MESSAGE_MAP()

void CMyToolBar::OnNcPaint() 
{
	EraseNonClient();
}
void CMyToolBar::EraseNonClient()
{
	// Get window DC that is clipped to the non-client area.
	CWindowDC dc(this);
	CRect rectClient;
	GetClientRect(rectClient);
	CRect rectWindow;
	GetWindowRect(rectWindow);
	ScreenToClient(rectWindow);
	rectClient.OffsetRect(-rectWindow.left, -rectWindow.top);
	dc.ExcludeClipRect(rectClient);

	// Draw the borders in the non-client area.
	rectWindow.OffsetRect(-rectWindow.left, -rectWindow.top);
	DrawBorders(&dc, rectWindow);

	// Erase the parts that are not drawn.
	dc.IntersectClipRect(rectWindow);
	SendMessage(WM_ERASEBKGND, (WPARAM)dc.m_hDC);

	// Draw the gripper in the non-client area.
	DrawGripper(&dc, rectWindow);
}

void CMyToolBar::DoPaint(CDC* pDC)
{
	ASSERT_VALID(this);
	ASSERT_VALID(pDC);

	// Paint inside the client area.
	CRect rect;
	GetClientRect(rect);
	DrawBorders(pDC, rect);
	DrawGripper(pDC, rect);
}

void CMyToolBar::DrawGripper(CDC* pDC, const CRect& rect)
{
	pDC->FillSolidRect( &rect, ::GetSysColor(COLOR_BTNFACE)); // Fill in the background.
	CToolBar::DrawGripper(pDC,rect);
}
