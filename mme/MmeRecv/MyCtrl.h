#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MyCtrl.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		拡張コントロールクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#define VC_NUM		0x01
#define VC_MINUS	0x02
#define VC_POINT	0x04
#define VC_EXP		0x08

#define	WM_KEY_RET_KEY	(WM_APP + 100)
#define	WM_MOUSEIN		(WM_APP + 101)
#define	WM_MOUSEOUT		(WM_APP + 102)

/////////////////////////////////////////////////////////////////////////////
// CButtonEx ウィンドウ

class CButtonEx : public CButton
{
public:
	CButtonEx();
	BOOL operator=(BOOL param);
	operator BOOL();
	LPCTSTR operator=(LPCTSTR pText);
	void SetTextColor(COLORREF colorText);
	void SetBackColor(COLORREF rgbBackColor);

protected:
	COLORREF m_colorText;
	BOOL m_bSetTextColor;
	CBrush m_brushBG;

	DECLARE_MESSAGE_MAP()
	afx_msg HBRUSH CtlColor(CDC* /*pDC*/, UINT /*nCtlColor*/);
};

/////////////////////////////////////////////////////////////////////////////
// CEditEx ウィンドウ

class CEditEx : public CEdit
{
public:
	CEditEx();

	int operator=(int param);
	double operator=(double param);
	CString &operator=(CString &param);
	LPCTSTR operator=(LPCTSTR param);
	CEditEx &operator=(CEditEx &param);
	int operator==(LPCTSTR param);
	int operator==(int param);
	int operator!=(int param);
	operator int();
	operator double();
	operator CString();
	BOOL IsEmpty();
	void Format(LPCTSTR lpszFormat, ...);
	void Blank();
	void SetTextColor(COLORREF color);
	void SetValidChar(UINT nValidChar);

protected:
	afx_msg HBRUSH CtlColor(CDC* pDC, UINT nCtlColor);
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);

	DECLARE_MESSAGE_MAP()

	CString m_str;
	BOOL m_bSetTextColor;
	CBrush m_Brush;
	COLORREF m_nTextColor;
	CString m_sValidStr;
};

// CStaticEx

class CStaticEx : public CStatic
{
	DECLARE_DYNAMIC(CStaticEx)

public:
	CStaticEx();
	LPCTSTR operator=(LPCTSTR pText);
	void SetTextColor(COLORREF colorText);
	void SetBackColor(COLORREF rgbBackColor);
	void ResetBackColor();
	void Blank();
	void SetMouseTrack(BOOL bMouseTrack);

protected:
	BOOL m_bSetTextColor;
	COLORREF m_colorText;
	CBrush m_brushBG;
	BOOL m_bMouseTrack;
	BOOL m_bMouseIn;

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg HBRUSH CtlColor(CDC* /*pDC*/, UINT /*nCtlColor*/);
	afx_msg void OnPaint();
	afx_msg void OnMouseMove(UINT nFlags, CPoint point);
	afx_msg LRESULT OnMouseLeave(WPARAM wparam, LPARAM lparam);
};
