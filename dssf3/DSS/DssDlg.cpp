// DssDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "Dss.h"
#include "DssDlg.h"
#include "VerRes.h"

// �A�v���P�[�V�����̃o�[�W�������Ɏg���� CAboutDlg �_�C�A���O

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ABOUTBOX };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

// ����
protected:
	DECLARE_MESSAGE_MAP()
public:
	virtual BOOL OnInitDialog();
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()


// CDssDlg �_�C�A���O



CDssDlg::CDssDlg(CWnd* pParent /*=NULL*/)
	: CPropertySheet(IDS_DSS_DIALOG, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
	AddPage(&m_cFolder);
	AddPage(&m_cInitialize);
	AddPage(&m_cMmlib);

	m_psh.dwFlags |= PSH_NOAPPLYNOW;
	m_psh.dwFlags &= ~PSH_HASHELP;
	m_cFolder.m_psp.dwFlags &= ~PSP_HASHELP;
	m_cInitialize.m_psp.dwFlags &= ~PSP_HASHELP;
}

BEGIN_MESSAGE_MAP(CDssDlg, CPropertySheet)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
END_MESSAGE_MAP()


// CDssDlg ���b�Z�[�W �n���h��

BOOL CDssDlg::OnInitDialog()
{
	CPropertySheet::OnInitDialog();

	// "�o�[�W�������..." ���j���[���V�X�e�� ���j���[�ɒǉ����܂��B

	// IDM_ABOUTBOX �́A�V�X�e�� �R�}���h�͈͓̔��ɂȂ���΂Ȃ�܂���B
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

	CString strOnlineUpdate;
	strOnlineUpdate.LoadString(IDS_ONLINE_UPDATE);
	if (!strOnlineUpdate.IsEmpty())
	{
		pSysMenu->AppendMenu(MF_SEPARATOR);
		pSysMenu->AppendMenu(MF_STRING, IDM_ONLINE_UPDATE, strOnlineUpdate);
	}

	// ���̃_�C�A���O�̃A�C�R����ݒ肵�܂��B�A�v���P�[�V�����̃��C�� �E�B���h�E���_�C�A���O�łȂ��ꍇ�A
	//  Framework �́A���̐ݒ�������I�ɍs���܂��B
	SetIcon(m_hIcon, TRUE);			// �傫���A�C�R���̐ݒ�
	SetIcon(m_hIcon, FALSE);		// �������A�C�R���̐ݒ�

	// TODO: �������������ɒǉ����܂��B

	return TRUE;  // �t�H�[�J�X���R���g���[���ɐݒ肵���ꍇ�������ATRUE ��Ԃ��܂��B
}

void CDssDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX) {
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	} else if (nID == IDM_ONLINE_UPDATE) {
		((CDssApp *)AfxGetApp())->OnOnlineUpdate();
	} else {
		CPropertySheet::OnSysCommand(nID, lParam);
	}
}

// �_�C�A���O�ɍŏ����{�^����ǉ�����ꍇ�A�A�C�R����`�悷�邽�߂�
//  ���̃R�[�h���K�v�ł��B�h�L�������g/�r���[ ���f�����g�� MFC �A�v���P�[�V�����̏ꍇ�A
//  ����́AFramework �ɂ���Ď����I�ɐݒ肳��܂��B

void CDssDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // �`��̃f�o�C�X �R���e�L�X�g

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// �N���C�A���g�̎l�p�`�̈���̒���
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// �A�C�R���̕`��
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CPropertySheet::OnPaint();
	}
}

//���[�U�[���ŏ��������E�B���h�E���h���b�O���Ă���Ƃ��ɕ\������J�[�\�����擾���邽�߂ɁA
//  �V�X�e�������̊֐����Ăяo���܂��B
HCURSOR CDssDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}

BOOL CAboutDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	CVerRes		res;
	SetDlgItemText(IDC_PRODUCT, res.GetProductName() + " Version " + res.GetProductVersion());
	SetDlgItemText(IDC_COPYRIGHT, res.GetLegalCopyright());

	return TRUE;
}
