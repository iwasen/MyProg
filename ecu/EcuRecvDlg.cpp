// EcuRecvDlg.cpp : �C���v�������e�[�V���� �t�@�C��
//

#include "stdafx.h"
#include "EcuRecv.h"
#include "EcuRecvDlg.h"
#include "comm.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// �A�v���P�[�V�����̃o�[�W�������Ŏg���Ă��� CAboutDlg �_�C�A���O

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CAboutDlg)
	enum { IDD = IDD_ABOUTBOX };
	//}}AFX_DATA

	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂�
	//{{AFX_VIRTUAL(CAboutDlg)
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �̃T�|�[�g
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
protected:
	//{{AFX_MSG(CAboutDlg)
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
	//{{AFX_DATA_INIT(CAboutDlg)
	//}}AFX_DATA_INIT
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CAboutDlg)
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
	//{{AFX_MSG_MAP(CAboutDlg)
		// ���b�Z�[�W �n���h��������܂���B
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvDlg �_�C�A���O

CEcuRecvDlg::CEcuRecvDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CEcuRecvDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CEcuRecvDlg)
	m_sReceiveStatus = _T("");
	m_sSaveFolder = _T("");
	m_sTexxxount = _T("");
	m_sReceiveCount = _T("");
	//}}AFX_DATA_INIT
	// ����: LoadIcon �� Win32 �� DestroyIcon �̃T�u�V�[�P���X��v�����܂���B
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CEcuRecvDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CEcuRecvDlg)
	DDX_Control(pDX, IDC_SAVE_FOLDER, m_cSaveFolder);
	DDX_Control(pDX, IDCANCEL, m_cExit);
	DDX_Control(pDX, IDC_STOP, m_cStop);
	DDX_Control(pDX, IDC_START, m_cStart);
	DDX_Text(pDX, IDC_RECEIVE_STATUS, m_sReceiveStatus);
	DDX_Text(pDX, IDC_SAVE_FOLDER, m_sSaveFolder);
	DDX_Text(pDX, IDC_TEST_COUNT, m_sTexxxount);
	DDX_Text(pDX, IDC_RECEIVE_COUNT, m_sReceiveCount);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CEcuRecvDlg, CDialog)
	//{{AFX_MSG_MAP(CEcuRecvDlg)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_START, OnStart)
	ON_BN_CLICKED(IDC_STOP, OnStop)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_RECV_NOTIFY, OnReceiveNotify)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvDlg ���b�Z�[�W �n���h��

//===================================================================
//
//	�@�\�@�F���C���_�C�A���O�� WM_INITDIALOG ���b�Z�[�W����
//
//	�����@�F����
//
//	�߂�l�FTRUE
//
//===================================================================
BOOL CEcuRecvDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// "�o�[�W�������..." ���j���[���ڂ��V�X�e�� ���j���[�֒ǉ����܂��B

	// IDM_ABOUTBOX �̓R�}���h ���j���[�͈̔͂łȂ���΂Ȃ�܂���B
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		CString strAboutMenu;
		strAboutMenu.LoadString(IDS_ABOUTBOX);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// ���̃_�C�A���O�p�̃A�C�R����ݒ肵�܂��B�t���[�����[�N�̓A�v���P�[�V�����̃��C��
	// �E�B���h�E���_�C�A���O�łȂ����͎����I�ɐݒ肵�܂���B
	SetIcon(m_hIcon, TRUE);			// �傫���A�C�R����ݒ�
	SetIcon(m_hIcon, FALSE);		// �������A�C�R����ݒ�

	// �R���g���[���̃C�l�[�u������
	EnableControl(FALSE);

	// �ʐM�n���h���N��
	StartCommHandler();
	
	return TRUE;  // TRUE ��Ԃ��ƃR���g���[���ɐݒ肵���t�H�[�J�X�͎����܂���B
}

//===================================================================
//
//	�@�\�@�F�V�X�e���R�}���h����
//
//	�����@�FnID - �R�}���hID
//			lParam - ���b�Z�[�W�p�����[�^
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

//===================================================================
//
//	�@�\�@�F���C���_�C�A���O�� WM_PAINT ���b�Z�[�W����
//
//	�����@�F����
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::OnPaint() 
{
	if (IsIconic())
	{
		CPaintDC dc(this); // �`��p�̃f�o�C�X �R���e�L�X�g

		SendMessage(WM_ICONERASEBKGND, (WPARAM) dc.GetSafeHdc(), 0);

		// �N���C�A���g�̋�`�̈���̒���
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// �A�C�R����`�悵�܂��B
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// �V�X�e���́A���[�U�[���ŏ����E�B���h�E���h���b�O���Ă���ԁA
// �J�[�\����\�����邽�߂ɂ������Ăяo���܂��B
HCURSOR CEcuRecvDlg::OnQueryDragIcon()
{
	return (HCURSOR) m_hIcon;
}

//===================================================================
//
//	�@�\�@�F�u�J�n�v�{�^������
//
//	�����@�F����
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::OnStart() 
{
	UpdateData(TRUE);

	// �ۑ��t�H���_�����̓`�F�b�N
	if (m_sSaveFolder.IsEmpty()) {
		AfxMessageBox("�ۑ��t�H���_������͂��Ă��������B");
		return;
	}

	//�@�ۑ��t�@�C�����݃`�F�b�N
	if (SaveFolderCheck(m_sSaveFolder)) {
		if (AfxMessageBox("�w�肵���t�H���_�ɂ��łɃt�@�C��������܂��B�㏑�����܂����H",
				MB_ICONQUESTION | MB_OKCANCEL) == IDCANCEL)
			return;

		DeleteSaveFile(m_sSaveFolder);
	}

	// �ۑ��p�t�H���_�쐬
	CreateDirectory(m_sSaveFolder, NULL);

	// �R���g���[���̃C�l�[�u����Ԑݒ�
	EnableControl(TRUE);

	// �����񐔁A��M�����A��M��ԏ���
	m_sTexxxount.Empty();
	m_sReceiveCount.Empty();
	m_sReceiveStatus.Empty();
	UpdateData(FALSE);

	// �ʐM�J�n
	::StartTest(this, m_sSaveFolder);
}

//===================================================================
//
//	�@�\�@�F�u��~�v�{�^������
//
//	�����@�F����
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::OnStop() 
{
	//�m�F���b�Z�[�W
	if (AfxMessageBox("�f�[�^��M���~���܂��B��낵���ł����H", MB_OKCANCEL | MB_ICONQUESTION) == IDCANCEL)
		return;

	// �R���g���[���̃C�l�[�u����Ԑݒ�
	EnableControl(FALSE);

	// �ʐM�I��
	::StopTest();

	m_sReceiveStatus.Empty();
	UpdateData(FALSE);
}

//===================================================================
//
//	�@�\�@�F�u�I���v�{�^������
//
//	�����@�F����
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::OnCancel() 
{
	// �_�C�A���O���I��
	EndDialog(IDCANCEL);
}

//===================================================================
//
//	�@�\�@�F�R���g���[���̃C�l�[�u����Ԑݒ菈��
//
//	�����@�FbStart - TRUE:�J�n  FALSE:��~
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::EnableControl(BOOL bStart)
{
	if (bStart) {
		// �J�n
		m_cSaveFolder.SetReadOnly(TRUE);
		m_cStart.EnableWindow(FALSE);
		m_cStop.EnableWindow(TRUE);
		m_cExit.EnableWindow(FALSE);
	} else {
		// ��~
		m_cSaveFolder.SetReadOnly(FALSE);
		m_cStart.EnableWindow(TRUE);
		m_cStop.EnableWindow(FALSE);
		m_cExit.EnableWindow(TRUE);
	}
}

//===================================================================
//
//	�@�\�@�F�ۑ��p�t�H���_���݃`�F�b�N
//
//	�����@�FpSaveFolder - �t�H���_��
//
//	�߂�l�FTRUE ���݂���, FALSE ���݂��Ȃ�
//
//===================================================================
BOOL CEcuRecvDlg::SaveFolderCheck(CString &sSaveFolder)
{
	HANDLE	hDir;
	WIN32_FIND_DATA	findBuf;
	BOOL bFind = FALSE;

	// �t�H���_�̑��݃`�F�b�N
	hDir = FindFirstFile(sSaveFolder, &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return FALSE;

	// �t�@�C���̑��݃`�F�b�N
	hDir = FindFirstFile(sSaveFolder + "\\*", &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return FALSE;
	do {
		if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY)) {
			bFind = TRUE;
			break;
		}
	} while (FindNextFile(hDir, &findBuf));

	FindClose(hDir);

	return bFind;
}

//===================================================================
//
//	�@�\�@�F�����̃t�@�C���폜����
//
//	�����@�FpSaveFolder - �t�H���_��
//
//	�߂�l�F����
//
//===================================================================
void CEcuRecvDlg::DeleteSaveFile(CString &sSaveFolder)
{
	HANDLE	hDir;
	WIN32_FIND_DATA	findBuf;

	// �t�H���_���݃`�F�b�N
	hDir = FindFirstFile(sSaveFolder, &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return;

	// �t�@�C������
	hDir = FindFirstFile(sSaveFolder + "\\*", &findBuf);
	if (hDir == INVALID_HANDLE_VALUE)
		return;

	// ���݂���t�@�C�����폜����
	do {
		if (!(findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY))
			DeleteFile(sSaveFolder + "\\" + findBuf.cFileName);
	} while (FindNextFile(hDir, &findBuf));

	FindClose(hDir);
}

//===================================================================
//
//	�@�\�@�F�f�[�^��M�ʒm����
//
//	�����@�FwParam - ���b�Z�[�W�p�����[�^�P
//			lParam - ���b�Z�[�W�p�����[�^�Q
//
//	�߂�l�F��ɂO
//
//===================================================================
LONG CEcuRecvDlg::OnReceiveNotify(WPARAM wParam, LPARAM lParam)
{
	switch (wParam) {
	case NOTIFY_TExxxOUNT:
		// �����񐔂�\��
		m_sTexxxount = (LPCTSTR)lParam;
		UpdateData(FALSE);
		break;
	case NOTIFY_RECVCOUNT:
		// ��M������\��
		m_sReceiveCount = (LPCTSTR)lParam;
		UpdateData(FALSE);
		break;
	case NOTIFY_RECVSTATUS:
		// ��M��Ԃ�\��
		m_sReceiveStatus = (LPCTSTR)lParam;
		UpdateData(FALSE);
		break;
	case NOTIFY_TESTEND:
		// �R���g���[���̃C�l�[�u������
		EnableControl(FALSE);
		break;
	}

	return 0;
}
