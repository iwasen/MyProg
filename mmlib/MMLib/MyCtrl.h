#pragma once

// MyCtrl.h : ヘッダー ファイル
//

/////////////////////////////////////////////////////////////////////////////
// CMyDate ウィンドウ

class CMyDate : public CEdit
{
// コンストラクション
public:
	CMyDate();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

// インプリメンテーション
public:
	virtual ~CMyDate();

	// 生成されたメッセージ マップ関数
protected:
	afx_msg void OnUpdate();
	
	DECLARE_MESSAGE_MAP()

public:
	CString &operator=(CString &param);
	LPCTSTR operator=(LPCTSTR param);
	operator CString();

protected:
	BOOL GetDate(int &nYear, int &nMonth, int &nDay);
};

/////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////
// CMyEdit ウィンドウ

class CMyEdit : public CEdit
{
// コンストラクション
public:
	CMyEdit();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数を生成しオーバーライドします。

// インプリメンテーション
public:
	virtual ~CMyEdit();

	// 生成されたメッセージ マップ関数
protected:
	afx_msg HBRUSH CtlColor(CDC* pDC, UINT nCtlColor);

	DECLARE_MESSAGE_MAP()

public:
	int operator=(int param);
	double operator=(double param);
	CString &operator=(CString &param);
	LPCTSTR operator=(LPCTSTR param);
	CMyEdit &operator=(CMyEdit &param);
	int operator==(int param);
	int operator!=(int param);
	operator int();
	operator double();
	operator CString();
	void Format(LPCTSTR lpszFormat, ...);
	void Blank();
	void LoadString(UINT nStringID);
	void FormatMessage(UINT nStringID, ...);
	void SetTextColor(COLORREF color);

private:
	CString m_str;
	BOOL m_bSetTextColor;
	CBrush m_Brush;
	COLORREF m_nTextColor;
};

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
// CMyButton ウィンドウ

class CMyButton : public CButton
{
// コンストラクション
public:
	CMyButton();

// アトリビュート
public:

// オペレーション
public:

// オーバーライド
	// ClassWizard は仮想関数のオーバーライドを生成します。

// インプリメンテーション
public:
	virtual ~CMyButton();

	// 生成されたメッセージ マップ関数
protected:
	afx_msg void OnLButtonDown(UINT nFlags, CPoint point);

	DECLARE_MESSAGE_MAP()

public:
	BOOL m_bNotifyButtonDown;

	BOOL operator=(BOOL param);
	operator BOOL();
};

class CMyToolBar : public CToolBar
{
public:
	void DrawGripper(CDC* pDC, const CRect& rect);
	void EraseNonClient();
	virtual void DoPaint(CDC* pDC);

protected:

	afx_msg void OnNcPaint();
	
	DECLARE_MESSAGE_MAP()
};
