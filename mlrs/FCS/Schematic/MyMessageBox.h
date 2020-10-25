#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		MyMessageBox.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���b�Z�[�W�{�b�N�X�\������
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "DialogEx.h"

// CMyMessageBox �_�C�A���O

#define MB_OK_END					0x00000007L
#define MB_OIL						0x00000008L

#define WM_MY_MESSAGE_BOX			(WM_APP + 1)

class CMyMessageBox : public CDialogEx
{
	DECLARE_DYNAMIC(CMyMessageBox)

public:
	CMyMessageBox(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_MY_MESSAGE_BOX };

public:
	int MessageBox(LPCTSTR lpszText, LPCTSTR lpszCaption ="", UINT nType = MB_OK);
	void ModelessMessage(CWnd *pWnd, LPCTSTR lpszText, LPCTSTR lpszCaption ="", UINT nType = MB_OK);
	void ChangeMessage(LPCTSTR lpszText, UINT nType);
	void CloseMessage();

protected:
	CString m_sMessage;			// �\�����b�Z�[�W
	CString m_sTitle;			// �E�B���h�E�^�C�g��
	UINT m_nType;				// �A�C�R���ƃ{�^���̎��
	CRect m_rectWindow;			// �E�B���h�E�̈ʒu
	CRect m_rectClient;			// �N���C�A���g�̈�T�C�Y
	CRect m_rectButton1, m_rectButton2, m_rectButton3;	// �{�^��
	CRect m_rectMessage;		// ���b�Z�[�W�\���̈�
	int m_nButton1, m_nButton2, m_nButton3;	// �{�^����ID
	BOOL m_bModal;				// ���[�_���t���O

	void DispMessage();
	void ClickedButton(int nButton);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	CStatic m_cStaticIcon;
	CStatic m_cStaticMessage;
	CButton m_cButton1;
	CButton m_cButton2;
	CButton m_cButton3;
	afx_msg void OnBnClickedButton1();
	afx_msg void OnBnClickedButton2();
	afx_msg void OnBnClickedButton3();
	virtual void OnCancel();
	afx_msg void OnDestroy();
};
