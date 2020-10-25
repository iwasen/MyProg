#pragma once

// MyCtrl.h : ヘッダー ファイル
//

#define VC_NUM		0x01
#define VC_MINUS	0x02
#define VC_DOT		0x04
#define VC_ALPHA	0x08

/////////////////////////////////////////////////////////////////////////////
// CMyButton ウィンドウ

class CMyButton : public CButton
{
public:
	CMyButton();
	virtual ~CMyButton();

	BOOL operator=(BOOL param);
	operator BOOL();

	//{{AFX_VIRTUAL(CMyButton)
	//}}AFX_VIRTUAL

	//{{AFX_MSG(CMyButton)
	//}}AFX_MSG

protected:
	DECLARE_MESSAGE_MAP()
};

/////////////////////////////////////////////////////////////////////////////
// CMyEdit ウィンドウ

class CMyEdit : public CEdit
{
public:
	CMyEdit();
	virtual ~CMyEdit();

	int operator=(int param);
	float operator=(float param);
	double operator=(double param);
	CString &operator=(CString &param);
	LPCTSTR operator=(LPCTSTR param);
	CMyEdit &operator=(CMyEdit &param);
	int operator==(LPCTSTR param);
	int operator==(int param);
	int operator!=(int param);
	operator int();
	operator float();
	operator double();
	operator CString();
	void Format(LPCTSTR lpszFormat, ...);
	void Blank();
	void LoadString(UINT nStringID);
	void FormatMessage(UINT nStringID, ...);
	void SetTextColor(COLORREF color);
	void SetMaxLength(int nMaxLength);
	void SetValidChar(UINT nValidChar);

protected:
	//{{AFX_MSG(CMyEdit)
	afx_msg HBRUSH CtlColor(CDC* pDC, UINT nCtlColor);
	afx_msg void OnChar(UINT nChar, UINT nRepCnt, UINT nFlags);
	//}}AFX_MSG

	DECLARE_MESSAGE_MAP()

	CString m_str;
	BOOL m_bSetTextColor;
	CBrush m_Brush;
	COLORREF m_nTextColor;
	UINT m_nValidChar;
	int m_nMaxLength;
};

/////////////////////////////////////////////////////////////////////////////
