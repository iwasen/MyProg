// MyCtrl.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "MyCtrl.h"
#include <afxole.h>

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMyButton

CMyButton::CMyButton()
{
}

CMyButton::~CMyButton()
{
}


BEGIN_MESSAGE_MAP(CMyButton, CButton)
	//{{AFX_MSG_MAP(CMyButton)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMyButton ���b�Z�[�W �n���h��

// �{�^��On/Off��Ԑݒ�
BOOL CMyButton::operator=(BOOL param)
{
	if (GetCheck() != param)
		SetCheck(param);

	return param;
}

// �{�^��On/Off��Ԏ擾
CMyButton::operator BOOL()
{
	return GetCheck();
}

/////////////////////////////////////////////////////////////////////////////
// CMyEdit

CMyEdit::CMyEdit()
{
	m_bSetTextColor = FALSE;
	m_nTextColor = -1;
	m_nValidChar = 0;
	m_nMaxLength = 0;
}

CMyEdit::~CMyEdit()
{
}


BEGIN_MESSAGE_MAP(CMyEdit, CEdit)
	//{{AFX_MSG_MAP(CMyEdit)
	ON_WM_CTLCOLOR_REFLECT()
	ON_WM_CHAR()
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMyEdit ���b�Z�[�W �n���h��

// �G�f�B�b�g�R���g���[���ɐ�����ݒ�
int CMyEdit::operator=(int param)
{
	CString str1, str2;

	str1.Format("%d", param);
	GetWindowText(str2);

	if (str1 != str2)
		SetWindowText(str1);

	return param;
}

// �G�f�B�b�g�R���g���[����float��ݒ�
float CMyEdit::operator=(float param)
{
	CString str1, str2;

	str1.Format("%g", param);
	GetWindowText(str2);

	if (str1 != str2)
		SetWindowText(str1);

	return param;
}

// �G�f�B�b�g�R���g���[����double��ݒ�
double CMyEdit::operator=(double param)
{
	CString str1, str2;

	str1.Format("%g", param);
	GetWindowText(str2);

	if (str1 != str2)
		SetWindowText(str1);

	return param;
}

// �G�f�B�b�g�R���g���[���Ƀe�L�X�g��ݒ�
CString &CMyEdit::operator=(CString &param)
{
	CString str2;

	GetWindowText(str2);

	if (param != str2)
		SetWindowText(param);

	return param;
}

// �G�f�B�b�g�R���g���[���Ƀe�L�X�g��ݒ�
LPCTSTR CMyEdit::operator=(LPCTSTR param)
{
	CString str2;

	GetWindowText(str2);

	if (str2 != param)
		SetWindowText(param);

	return param;
}

// �G�f�B�b�g�R���g���[������G�f�B�b�g�R���g���[���ɓ��e���R�s�[
CMyEdit &CMyEdit::operator=(CMyEdit &param)
{
	CString str;

	param.GetWindowText(str);
	SetWindowText(str);

	return param;
}

// �G�f�B�b�g�R���g���[�����̃e�L�X�g���r
int CMyEdit::operator==(LPCTSTR param)
{
	GetWindowText(m_str);
	return m_str == param;
}

// �G�f�B�b�g�R���g���[�����̐������r
int CMyEdit::operator==(int param)
{
	GetWindowText(m_str);
	return atoi(m_str) == param;
}

// �G�f�B�b�g�R���g���[�����̐������r
int CMyEdit::operator!=(int param)
{
	GetWindowText(m_str);
	return atoi(m_str) != param;
}

// �G�f�B�b�g�R���g���[�����琮�����擾
CMyEdit::operator int()
{
	GetWindowText(m_str);
	return atoi(m_str);
}

// �G�f�B�b�g�R���g���[������float���擾
CMyEdit::operator float()
{
	GetWindowText(m_str);
	return (float)atof(m_str);
}

// �G�f�B�b�g�R���g���[������double���擾
CMyEdit::operator double()
{
	GetWindowText(m_str);
	return atof(m_str);
}

// �G�f�B�b�g�R���g���[������e�L�X�g���擾
CMyEdit::operator CString()
{
	GetWindowText(m_str);
	return m_str;
}

// �G�f�B�b�g�R���g���[���ɏ��������ꂽ�e�L�X�g��ݒ�
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

// �G�f�B�b�g�R���g���[���Ƀu�����N��ݒ�
void CMyEdit::Blank()
{
	CString str;

	GetWindowText(str);
	if (!str.IsEmpty())
		SetWindowText("");
}

// �G�f�B�b�g�R���g���[���Ƀ��\�[�XID���當�����ݒ�
void CMyEdit::LoadString(UINT nStringID)
{
	CString str1, str2;

	str1.LoadString(nStringID);

	GetWindowText(str2);

	if (str2 != str1)
		SetWindowText(str1);
}

// �G�f�B�b�g�R���g���[���Ƀ��\�[�XID���珑�������ꂽ�������ݒ�
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

// �G�f�B�b�g�R���g���[���̕����F��ݒ�
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

// �G�f�B�b�g�R���g���[���J���[�擾�n���h��
HBRUSH CMyEdit::CtlColor(CDC* pDC, UINT nCtlColor)
{
	if (m_bSetTextColor) {
		pDC->SetTextColor(m_nTextColor);
		pDC->SetBkMode(TRANSPARENT);
		return (HBRUSH)m_Brush;
	}

	return NULL;
}

// �G�f�B�b�g�R���g���[���ɓ��͉\�ȕ�������ݒ�
void CMyEdit::SetMaxLength(int nMaxLength)
{
	m_nMaxLength = nMaxLength;
}

// �G�f�B�b�g�R���g���[���ɓ��͉\�ȕ�����ݒ�
void CMyEdit::SetValidChar(UINT nValidChar)
{
	m_nValidChar = nValidChar;
}

#define VC_NUM		0x01
#define VC_MINUS	0x02
#define VC_DOT		0x04
#define VC_ALPHA	0x08


// �G�f�B�b�g�R���g���[���������̓n���h��
void CMyEdit::OnChar(UINT nChar, UINT nRepCnt, UINT nFlags)
{
	BOOL bValid = FALSE;
	CString str1;
	int nStartChar, nEndChar;

	if (m_nMaxLength != 0) {
		GetWindowText(str1);
		GetSel(nStartChar, nEndChar);
	}

	if (m_nValidChar == 0)
		bValid = TRUE;

	if (!bValid && (m_nValidChar & VC_NUM)) {
		if (nChar >= '0' && nChar <= '9')
			bValid = TRUE;
	}

	if (!bValid && (m_nValidChar & VC_MINUS)) {
		if (nChar == '-')
			bValid = TRUE;
	}

	if (!bValid && (m_nValidChar & VC_DOT)) {
		if (nChar == '.')
			bValid = TRUE;
	}

	if (!bValid && (m_nValidChar & VC_ALPHA)) {
		if ((nChar >= 'A' && nChar <= 'Z') || (nChar >= 'a' && nChar <= 'z'))
			bValid = TRUE;
	}

	if (!bValid) {
		MessageBeep(0);
		return;
	}

	CEdit::OnChar(nChar, nRepCnt, nFlags);

	if (m_nMaxLength != 0) {
		CString str2;
		GetWindowText(str2);
		if (str2.GetLength() > m_nMaxLength) {
			SetWindowText(str1);
			SetSel(nStartChar, nEndChar);
		}
	}
}
