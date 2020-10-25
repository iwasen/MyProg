#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MyCtrl.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		拡張コントロールクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#define VC_NUM		0x01
#define VC_MINUS	0x02
#define VC_POINT	0x04
#define VC_EXP		0x08

/////////////////////////////////////////////////////////////////////////////
// CButtonEx ウィンドウ

class CButtonEx : public CButton
{
public:
	BOOL operator=(BOOL param);
	operator BOOL();
	LPCTSTR operator=(LPCTSTR pText);

protected:
	DECLARE_MESSAGE_MAP()
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
	void LoadString(UINT nStringID);
	void FormatMessage(UINT nStringID, ...);
	void SetTextColor(COLORREF color);
	void SetValidChar(UINT nValidChar);
	void SetFocusAndSel();

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
	void SetBackColor(COLORREF rgbBackColor);

protected:
	CBrush m_brushBG;

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg HBRUSH CtlColor(CDC* /*pDC*/, UINT /*nCtlColor*/);
	afx_msg void OnPaint();
};

class CToolBarEx : public CToolBar
{
public:
	void DrawGripper(CDC* pDC, const CRect& rect);
	void EraseNonClient();
	virtual void DoPaint(CDC* pDC);

protected:
	afx_msg void OnNcPaint();
	DECLARE_MESSAGE_MAP()
};
