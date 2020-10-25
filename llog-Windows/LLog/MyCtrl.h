#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MyCtrl.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�g���R���g���[���N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include <afxtempl.h>

#define VC_NUM		0x01
#define VC_MINUS	0x02
#define VC_POINT	0x04
#define VC_EXP		0x08

/////////////////////////////////////////////////////////////////////////////
// CButtonEx �E�B���h�E

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
// CEditEx �E�B���h�E

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
public:
	afx_msg void OnLButtonDblClk(UINT nFlags, CPoint point);
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
#pragma once


// CLixxxtrlEx

class CLixxxtrlEx : public CLixxxtrl
{
	DECLARE_DYNAMIC(CLixxxtrlEx)

public:
	CLixxxtrlEx();
	virtual ~CLixxxtrlEx();

protected:
	DECLARE_MESSAGE_MAP()
	afx_msg void OnNcCalcSize(BOOL bCalcValidRects, NCCALCSIZE_PARAMS* lpncsp);
};


