//*****************************************************************************************************
//  1. �t�@�C����
//		MyMessageBox.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���b�Z�[�W�{�b�N�X�\������
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "resource.h"
#include "MyMessageBox.h"

// CMyMessageBox �_�C�A���O


IMPLEMENT_DYNAMIC(CMyMessageBox, CDialogEx)

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::CMyMessageBox
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CWnd*	pParent		[I] �e�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CMyMessageBox::CMyMessageBox(CWnd* pParent /*=NULL*/)
	: CDialogEx(CMyMessageBox::IDD, pParent)
{
}

void CMyMessageBox::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_STATIC_ICON, m_cStaticIcon);
	DDX_Control(pDX, IDC_STATIC_MESSAGE, m_cStaticMessage);
	DDX_Control(pDX, IDC_BUTTON1, m_cButton1);
	DDX_Control(pDX, IDC_BUTTON2, m_cButton2);
	DDX_Control(pDX, IDC_BUTTON3, m_cButton3);
}


BEGIN_MESSAGE_MAP(CMyMessageBox, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON1, &CMyMessageBox::OnBnClickedButton1)
	ON_BN_CLICKED(IDC_BUTTON2, &CMyMessageBox::OnBnClickedButton2)
	ON_BN_CLICKED(IDC_BUTTON3, &CMyMessageBox::OnBnClickedButton3)
	ON_WM_DESTROY()
END_MESSAGE_MAP()


// CMyMessageBox ���b�Z�[�W �n���h��

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CMyMessageBox::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// ���b�Z�[�W�{�b�N�X�̃^�C�g����ݒ�
	SetWindowText(m_sTitle);

	// �E�B���h�E�̃T�C�Y���擾
	GetWindowRect(m_rectWindow);

	// �N���C�A���g�̈�̃T�C�Y���擾
	GetClientRect(m_rectClient);

	// �e�{�^���̈ʒu�ƃT�C�Y���擾
	m_cButton1.GetWindowRect(m_rectButton1);
	m_cButton2.GetWindowRect(m_rectButton2);
	m_cButton3.GetWindowRect(m_rectButton3);
	ScreenToClient(m_rectButton1);
	ScreenToClient(m_rectButton2);
	ScreenToClient(m_rectButton3);

	// ���b�Z�[�W�G���A�̃T�C�Y���擾
	m_cStaticMessage.GetWindowRect(m_rectMessage);
	ScreenToClient(m_rectMessage);

	// �A�C�R���ƃ��b�Z�[�W�\��
	DispMessage();

	// ���[�h���X�Ȃ�e�E�B���h�E��Disable����
	if (!m_bModal) {
		CWnd *pParent = GetParent();
		if (pParent != NULL)
			pParent->EnableWindow(FALSE);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::DispMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�C�R���ƃ��b�Z�[�W�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::DispMessage()
{
	// �E�B���h�E���J���Ă��Ȃ���Ή������Ȃ�
	if (m_hWnd == NULL)
		return;

	// �A�C�R�����擾
	HICON hIcon;
	switch (m_nType & 0xf0) {
	case MB_ICONEXCLAMATION:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_EXCLAMATION);
		break;
	case MB_ICONINFORMATION:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_INFORMATION);
		break;
	case MB_ICONQUESTION:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_QUESTION);
		break;
	case MB_ICONSTOP:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_ERROR);
		break;
	default:
		hIcon = NULL;
		break;
	}

	// �A�C�R����\��
	if (hIcon != NULL)
		m_cStaticIcon.SetIcon(hIcon);

	// �{�^���̎�ނ��擾
	LPCTSTR pButton1, pButton2, pButton3;
	switch (m_nType & 0x0f) {
	case MB_ABORTRETRYIGNORE:	// [���~]�A[�Ď��s]�A[����]
		pButton1 = "���@�~";
		pButton2 = "�Ď��s";
		pButton3 = "���@��";

		m_nButton1 = IDABORT;
		m_nButton2 = IDRETRY;		
		m_nButton3 = IDIGNORE;
		break;
	case MB_OK:	// [OK]
		pButton1 = "���@��";
		pButton2 = NULL;
		pButton3 = NULL;

		m_nButton1 = IDOK;
		break;
	case MB_OK_END:	// [OK]
		pButton1 = "�I�@��";
		pButton2 = NULL;
		pButton3 = NULL;

		m_nButton1 = IDOK;
		break;
	case MB_OKCANCEL:	// [OK]�A[�L�����Z��]
		pButton1 = "���@��";
		pButton2 = "��@��";
		pButton3 = NULL;

		m_nButton1 = IDOK;
		m_nButton2 = IDCANCEL;
		break;
	case MB_OIL:		// [OK]�A[�L�����Z��]�@��Ɩ��̕�[���ʃo�[�W����
		pButton1 = "��[���s";
		pButton2 = " ��@�� ";
		pButton3 = NULL;

		m_nButton1 = IDOK;
		m_nButton2 = IDCANCEL;
		break;
	case MB_RETRYCANCEL:	// [�Ď��s]�A[�L�����Z��]
		pButton1 = "�Ď��s";
		pButton2 = "��@��";
		pButton3 = NULL;

		m_nButton1 = IDRETRY;
		m_nButton2 = IDCANCEL;
		break;
	case MB_YESNO:	// [�͂�]�A[������]
		pButton1 = "�́@��";
		pButton2 = "������";
		pButton3 = NULL;

		m_nButton1 = IDYES;
		m_nButton2 = IDNO;
	case MB_YESNOCANCEL:	// [�͂�]�A[������]�A[�L�����Z��]
		pButton1 = "�́@��";
		pButton2 = "������";
		pButton3 = "��@��";

		m_nButton1 = IDYES;
		m_nButton2 = IDNO;		
		m_nButton3 = IDCANCEL;
		break;
	}

	// �{�^���̃T�C�Y���擾
	CSize sizeButton(m_rectButton1.Width(), m_rectButton1.Height());

	// �{�^���̊Ԃ̃X�y�[�X���擾
	int nButtonSpace = m_rectButton2.left - m_rectButton1.right;

	// �{�^���̍����̃X�y�[�X���擾
	int nLeftSpace = m_rectButton1.left;

	// �{�^���̉E���̃X�y�[�X���擾
	int nRightSpace = nLeftSpace;

	// �S�Ẵ{�^��������ŏ��̃E�B���h�E�T�C�Y���v�Z
	CSize sizeWindowButton(nLeftSpace + sizeButton.cx + nRightSpace, m_rectWindow.Height());
	if (pButton2 != NULL)
		sizeWindowButton.cx += sizeButton.cx + nButtonSpace;
	if (pButton3 != NULL)
		sizeWindowButton.cx += sizeButton.cx + nButtonSpace;

	// ���b�Z�[�W�̃T�C�Y���擾
	CRect rectText(0, 0, 0, 0);
	CDC *pDC = GetWindowDC();
	CFont *pFontOrg = pDC->SelectObject(GetFont());
	pDC->DrawText(m_sMessage, rectText, DT_CALCRECT);
	pDC->SelectObject(pFontOrg);
	ReleaseDC(pDC);

	// �S�Ẵ��b�Z�[�W������ŏ��̃E�B���h�E�T�C�Y���v�Z
	CSize sizeWindowText;
	sizeWindowText.cx = m_rectWindow.Width() + rectText.Width() - m_rectMessage.Width();
	sizeWindowText.cy = m_rectWindow.Height() + rectText.Height() - m_rectMessage.Height();

	// ���b�Z�[�W�̃T�C�Y�ɂ��E�B���h�E�T�C�Y���g��
	CSize sizeWindow(max(sizeWindowButton.cx, sizeWindowText.cx), max(sizeWindowButton.cy, sizeWindowText.cy));

	// �E�B���h�E�̈ʒu�ƃT�C�Y�𒲐�
	SetWindowPos(NULL, 0, 0, sizeWindow.cx, sizeWindow.cy, SWP_NOZORDER | SWP_NOMOVE);
	CenterWindow();

	// ���b�Z�[�W�̃T�C�Y�𒲐�
	m_cStaticMessage.SetWindowPos(NULL, 0, 0, rectText.right, rectText.bottom, SWP_NOMOVE | SWP_NOZORDER);

	// ���b�Z�[�W��\��
	m_cStaticMessage.SetWindowText(m_sMessage);

	// �{�^���̈ʒu���v�Z
	CPoint pointButton;
	pointButton.x = nLeftSpace + (sizeWindow.cx - sizeWindowButton.cx) / 2;
	pointButton.y = m_rectButton1.top + sizeWindow.cy - m_rectWindow.Height();

	// �P�Ԗڂ̃{�^����\��
	m_cButton1.SetWindowPos(NULL, pointButton.x, pointButton.y, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
	m_cButton1.SetWindowText(pButton1);

	// �Q�Ԗڂ̃{�^����\��
	if (pButton2 != NULL) {
		pointButton.x += sizeButton.cx + nButtonSpace;
		m_cButton2.SetWindowPos(NULL, pointButton.x, pointButton.y, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
		m_cButton2.SetWindowText(pButton2);
		m_cButton2.ShowWindow(SW_SHOW);
	} else
		m_cButton2.ShowWindow(SW_HIDE);

	// �R�Ԗڂ̃{�^����\��
	if (pButton3 != NULL) {
		pointButton.x += sizeButton.cx + nButtonSpace;
		m_cButton3.SetWindowPos(NULL, pointButton.x, pointButton.y, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
		m_cButton3.SetWindowText(pButton3);
		m_cButton3.ShowWindow(SW_SHOW);
	} else
		m_cButton3.ShowWindow(SW_HIDE);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::MessageBox
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���[�_�����b�Z�[�W�{�b�N�X�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCTSTR	lpszText		[I] �\�����b�Z�[�W
//		LPCTSTR	lpszCaption		[I] �E�B���h�E�^�C�g��
//		UINT	nType			[I] �A�C�R���ƃ{�^���̎��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
int CMyMessageBox::MessageBox(LPCTSTR lpszText, LPCTSTR lpszCaption, UINT nType)
{
	m_sMessage = lpszText;
	m_sTitle = lpszCaption;
	m_nType = nType;
	m_bModal = TRUE;

	if (m_sTitle=="")
		m_sTitle=::AfxGetAppName();

	return (int)DoModal();
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::ModelessMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���[�h���X���b�Z�[�W�{�b�N�X�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		CWnd	*pWnd			[I] �e�E�B���h�E
//		LPCTSTR	lpszText		[I] �\�����b�Z�[�W
//		LPCTSTR	lpszCaption		[I] �E�B���h�E�^�C�g��
//		UINT	nType			[I] �A�C�R���ƃ{�^���̎��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::ModelessMessage(CWnd *pWnd, LPCTSTR lpszText, LPCTSTR lpszCaption, UINT nType)
{
	m_sMessage = lpszText;
	m_sTitle = lpszCaption;
	m_nType = nType;
	m_bModal = FALSE;

	if (m_sTitle=="")
		m_sTitle=::AfxGetAppName();

	// ���[�h���X�Ń_�C�A���O���J��
	DoModeless(pWnd);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::ChangeMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���b�Z�[�W�ύX
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCTSTR	lpszText		[I] �\�����b�Z�[�W
//		UINT	nType			[I] �A�C�R���ƃ{�^���̎��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::ChangeMessage(LPCTSTR lpszText, UINT nType)
{
	m_sMessage = lpszText;
	m_nType = nType;

	DispMessage();
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::CloseMessage
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���b�Z�[�W�{�b�N�X�N���[�Y
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::CloseMessage()
{
	if (m_hWnd != NULL)
		EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::OnBnClickedButton1
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��P�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::OnBnClickedButton1()
{
	ClickedButton(m_nButton1);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::OnBnClickedButton2
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��Q�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::OnBnClickedButton2()
{
	ClickedButton(m_nButton2);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::OnBnClickedButton3
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		��R�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::OnBnClickedButton3()
{
	ClickedButton(m_nButton3);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::ClickedButton
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		int		nButton		[I] �N���b�N�����{�^����ID
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::ClickedButton(int nButton)
{
	if (m_bModal)
		EndDialog(nButton);
	else {
		// �e�E�B���h�E�ɉ����ꂽ�{�^����ʒm����
		CWnd *pParent = GetParent();
		if (pParent != NULL)
			pParent->PostMessage(WM_MY_MESSAGE_BOX, nButton);

		// �E�B���h�E�����
		DestroyWindow();
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�����Z���{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::OnCancel()
{
	ClickedButton(IDCANCEL);
}

//*****************************************************************************************************
//  1. �֐���
//		CMyMessageBox::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�E�B���h�E�j������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CMyMessageBox::OnDestroy()
{
	CDialogEx::OnDestroy();

	// ���[�h���X�Ȃ�e�E�B���h�E��Enable�ɂ���
	if (!m_bModal) {
		CWnd *pParent = GetParent();
		if (pParent != NULL)
			pParent->EnableWindow(TRUE);
	}
}
