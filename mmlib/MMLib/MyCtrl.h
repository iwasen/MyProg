#pragma once

// MyCtrl.h : �w�b�_�[ �t�@�C��
//

/////////////////////////////////////////////////////////////////////////////
// CMyDate �E�B���h�E

class CMyDate : public CEdit
{
// �R���X�g���N�V����
public:
	CMyDate();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

// �C���v�������e�[�V����
public:
	virtual ~CMyDate();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
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
// CMyEdit �E�B���h�E

class CMyEdit : public CEdit
{
// �R���X�g���N�V����
public:
	CMyEdit();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��𐶐����I�[�o�[���C�h���܂��B

// �C���v�������e�[�V����
public:
	virtual ~CMyEdit();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
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
// CMyButton �E�B���h�E

class CMyButton : public CButton
{
// �R���X�g���N�V����
public:
	CMyButton();

// �A�g���r���[�g
public:

// �I�y���[�V����
public:

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B

// �C���v�������e�[�V����
public:
	virtual ~CMyButton();

	// �������ꂽ���b�Z�[�W �}�b�v�֐�
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
