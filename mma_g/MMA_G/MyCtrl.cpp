//*****************************************************************************************************
//  1. ファイル名
//		MyCtrl.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		拡張コントロールクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MyCtrl.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define TS_MARGIN	10
#define	WM_KEY_RET_KEY	(WM_USER + 100)

/////////////////////////////////////////////////////////////////////////////
// CButtonEx


BEGIN_MESSAGE_MAP(CButtonEx, CButton)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CButtonEx メッセージ ハンドラ

BOOL CButtonEx::operator=(BOOL param)
{
	if (GetCheck() != param)
		SetCheck(param);

	return param;
}

CButtonEx::operator BOOL()
{
	return GetCheck();
}

LPCTSTR CButtonEx::operator=(LPCTSTR pText)
{
	SetWindowText(pText);
	return pText;
}

/////////////////////////////////////////////////////////////////////////////
// CEditEx

CEditEx::CEditEx()
{
	m_bSetTextColor = FALSE;
	m_nTextColor = (COLORREF)-1;
}


BEGIN_MESSAGE_MAP(CEditEx, CEdit)
	ON_WM_CTLCOLOR_REFLECT()
	ON_WM_CHAR()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CEditEx メッセージ ハンドラ

int CEditEx::operator=(int param)
{
	CString str1, str2;

	str1.Format("%d", param);
	GetWindowText(str2);

	if (str1 != str2) {
		SetWindowText(str1);
		UpdateWindow();
	}

	return param;
}

double CEditEx::operator=(double param)
{
	CString str1, str2;

	str1.Format("%g", param);
	GetWindowText(str2);

	if (str1 != str2) {
		SetWindowText(str1);
		UpdateWindow();
	}

	return param;
}

CString &CEditEx::operator=(CString &param)
{
	CString str2;

	GetWindowText(str2);

	if (param != str2) {
		SetWindowText(param);
		UpdateWindow();
	}

	return param;
}

LPCTSTR CEditEx::operator=(LPCTSTR param)
{
	CString str2;

	GetWindowText(str2);

	if (str2 != param) {
		SetWindowText(param);
		UpdateWindow();
	}

	return param;
}

CEditEx &CEditEx::operator=(CEditEx &param)
{
	CString str;

	param.GetWindowText(str);
	SetWindowText(str);
	UpdateWindow();

	return param;
}

int CEditEx::operator==(LPCTSTR param)
{
	GetWindowText(m_str);
	return m_str == param;
}

int CEditEx::operator==(int param)
{
	GetWindowText(m_str);
	return atoi(m_str) == param;
}

int CEditEx::operator!=(int param)
{
	GetWindowText(m_str);
	return atoi(m_str) != param;
}

CEditEx::operator int()
{
	GetWindowText(m_str);
	return atoi(m_str);
}

CEditEx::operator double()
{
	GetWindowText(m_str);
	return atof(m_str);
}

CEditEx::operator CString()
{
	GetWindowText(m_str);
	return m_str;
}

BOOL CEditEx::IsEmpty()
{
	GetWindowText(m_str);
	return m_str.IsEmpty();
}

void CEditEx::Format(LPCTSTR lpszFormat, ...)
{
	va_list vl;
	char str[256];
	CString str2;

	va_start(vl, lpszFormat);
	vsprintf_s(str, lpszFormat, vl);

	GetWindowText(str2);

	if (str2 != str) {
		SetWindowText(str);
		UpdateWindow();
	}
}

void CEditEx::Blank()
{
	CString str;

	GetWindowText(str);
	if (!str.IsEmpty()) {
		SetWindowText("");
		UpdateWindow();
	}
}

void CEditEx::LoadString(UINT nStringID)
{
	CString str1, str2;

	str1.LoadString(nStringID);

	GetWindowText(str2);

	if (str2 != str1) {
		SetWindowText(str1);
		UpdateWindow();
	}
}

void CEditEx::FormatMessage(UINT nStringID, ...)
{
	va_list vl;
	char str[256];
	CString str2;

	va_start(vl, nStringID);
	str2.LoadString(nStringID);
	vsprintf_s(str, (LPCTSTR)str2, vl);

	GetWindowText(str2);

	if (str2 != str) {
		SetWindowText(str);
		UpdateWindow();
	}
}

void CEditEx::SetTextColor(COLORREF color)
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

HBRUSH CEditEx::CtlColor(CDC* pDC, UINT nCtlColor) 
{
	if (m_bSetTextColor) {
		pDC->SetTextColor(m_nTextColor);
		pDC->SetBkMode(TRANSPARENT);
		return (HBRUSH)m_Brush;
	}

	return NULL;
}

void CEditEx::SetValidChar(UINT nValidChar)
{
	m_sValidStr = "\b\x03\x16\x18";		// BS,CTRL+C,CTRL+V,CTRL+X
	if (nValidChar & VC_NUM)
		m_sValidStr += "0123456789";
	if (nValidChar & VC_MINUS)
		m_sValidStr += "-";
	if (nValidChar & VC_POINT)
		m_sValidStr += ".";
	if (nValidChar & VC_EXP)
		m_sValidStr += "eE";
}

void CEditEx::OnChar(UINT nChar, UINT nRepCnt, UINT nFlags) 
{
	switch(nChar) {
	case 0x0d:
		GetParent()->SendMessage(WM_KEY_RET_KEY, this->GetDlgCtrlID(), 0);
		return;
	case 0x09:
		break;
	default:
//		break;
		if (!m_sValidStr.IsEmpty()) {
			if (strchr(m_sValidStr, nChar) == NULL) {
				MessageBeep(0);
				return;
			}
		}
	}

	CEdit::OnChar(nChar, nRepCnt, nFlags);
}

void CEditEx::SetFocusAndSel()
{
	SetFocus();
	SetSel(0, -1);
}


// CStaticEx

IMPLEMENT_DYNAMIC(CStaticEx, CStatic)


BEGIN_MESSAGE_MAP(CStaticEx, CStatic)
	ON_WM_CTLCOLOR_REFLECT()
	ON_WM_PAINT()
END_MESSAGE_MAP()



// CStaticEx メッセージ ハンドラ

void CStaticEx::SetBackColor(COLORREF rgbBackColor)
{
	m_brushBG.CreateSolidBrush(rgbBackColor);
}

HBRUSH CStaticEx::CtlColor(CDC* /*pDC*/, UINT /*nCtlColor*/)
{
	if (m_brushBG.m_hObject)
		return (HBRUSH)m_brushBG.m_hObject;

	return NULL;
}

void CStaticEx::OnPaint()
{
	if ((GetStyle() & SS_BLACKRECT) && m_brushBG.m_hObject) {
		CPaintDC dc(this);

		CRect rect;
		GetClientRect(rect);
		dc.FillRect(rect, &m_brushBG);
	} else
		CStatic::OnPaint();
}

// XPスタイルでグリップが正常に表示されないバグの対処
//////////////////////////////////////////////////////
// CToolBarEx
BEGIN_MESSAGE_MAP(CToolBarEx, CToolBar)
	ON_WM_NCPAINT()
END_MESSAGE_MAP()

void CToolBarEx::OnNcPaint() 
{
	EraseNonClient();
}
void CToolBarEx::EraseNonClient()
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

void CToolBarEx::DoPaint(CDC* pDC)
{
	ASSERT_VALID(this);
	ASSERT_VALID(pDC);

	// Paint inside the client area.
	CRect rect;
	GetClientRect(rect);
	DrawBorders(pDC, rect);
	DrawGripper(pDC, rect);
}

void CToolBarEx::DrawGripper(CDC* pDC, const CRect& rect)
{
	pDC->FillSolidRect( &rect, ::GetSysColor(COLOR_BTNFACE)); // Fill in the background.
	CToolBar::DrawGripper(pDC,rect);
}
