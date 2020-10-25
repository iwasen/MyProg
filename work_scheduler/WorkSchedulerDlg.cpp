//*****************************************************************************************************
//  1. �t�@�C����
//		WorkSchedulerDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CAboutDlg,CWorkSchedulerDlg �N���X�̃C���v�������e�[�V����
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"

#include "MenuDispCtrl.h"

#include "WorkSchedulerDlg001.h"
#include "WorkSchedulerDlg.h"
#include "AboutDlg.h"
#include "DataManager.h"
#include "StartMenuDlg.h"
#include "SaveProjectDlg.h"
#include "SetConditionsDlg.h"
#include "AutoSchedulingDlg.h"
#include "ManualSchedulingDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define MENU_START				0
#define MENU_SET_CONDITIONS		1
#define MENU_AUTO_SCHEDULING	2
#define MENU_MANUAL_SCHEDULING	3

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::CWorkSchedulerDlg
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		*pParent			[I] �e�E�B���h�E
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���\�[�X����A�C�R�����擾����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
CWorkSchedulerDlg::CWorkSchedulerDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CWorkSchedulerDlg::IDD, pParent)
{
	//{{AFX_DATA_INIT(CWorkSchedulerDlg)
		// ����: ���̈ʒu�� ClassWizard �ɂ���ă����o�̏��������ǉ�����܂��B
	//}}AFX_DATA_INIT
	// ����: LoadIcon �� Win32 �� DestroyIcon �̃T�u�V�[�P���X��v�����܂���B
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CWorkSchedulerDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	//{{AFX_DATA_MAP(CWorkSchedulerDlg)
	DDX_Control(pDX, IDC_PROGRESS_BAR, m_cProgressBar);
	DDX_Control(pDX, IDC_STATIC_STATUS, m_cStaticStatus);
	DDX_Control(pDX, IDC_BUTTON_EXIT, m_buttonExit);
	DDX_Control(pDX, IDC_BUTTON_AUTO_SCHEDULING, m_buttonAutoScheduling);
	DDX_Control(pDX, IDC_BUTTON_MANUAL_SCHEDULING, m_buttonManualScheduling);
	DDX_Control(pDX, IDC_BUTTON_SET_CONDITIONS, m_buttonSetConditions);
	DDX_Control(pDX, IDC_BUTTON_START, m_buttonStart);
	DDX_Control(pDX, IDC_STATIC_CHILD_MENU, m_cSubMenu);
	//}}AFX_DATA_MAP
}

BEGIN_MESSAGE_MAP(CWorkSchedulerDlg, CDialogEx)
	//{{AFX_MSG_MAP(CWorkSchedulerDlg)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	ON_BN_CLICKED(IDC_BUTTON_START, OnButtonStart)
	ON_BN_CLICKED(IDC_BUTTON_SET_CONDITIONS, OnButtonSetConditions)
	ON_BN_CLICKED(IDC_BUTTON_AUTO_SCHEDULING, OnButtonAutoScheduling)
	ON_BN_CLICKED(IDC_BUTTON_MANUAL_SCHEDULING, OnButtonManualScheduling)
	ON_BN_CLICKED(IDC_BUTTON_EXIT, OnButtonExit)
	ON_COMMAND(IDM_FILE_SAVE, OnFileSave)
	ON_COMMAND(IDM_FILE_SAVE_AS, OnFileSaveAs)
	ON_UPDATE_COMMAND_UI(IDM_FILE_SAVE, OnUpdateFileSave)
	ON_UPDATE_COMMAND_UI(IDM_FILE_SAVE_AS, OnUpdateFileSaveAs)
	ON_WM_INITMENUPOPUP()
	ON_COMMAND(IDM_HELP_VERSION, OnHelpVersion)
	//}}AFX_MSG_MAP
	ON_MESSAGE(WM_OPEN_EXCEL, OnOpenExcel)
	ON_MESSAGE(WM_CLOSE_EXCEL, OnCloseExcel)
	ON_MESSAGE(WM_ERROR_EXCEL, OnErrorExcel)
END_MESSAGE_MAP()

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�_�C�A���O����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		TRUE
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�f�[�^�Ǘ��N���X�𐶐����A���j���[�A�T�u���j���[�A�{�^��������������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CWorkSchedulerDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

// Modify ... ( CHANGE )
	// �^�C�g���Ƀo�[�W�����ԍ���ǉ�
	CString sWindowVersion;
	int j = 0;
	for( int i = 0; i < g_pConst->m_sVersion.GetLength(); i++ ){
		if( g_pConst->m_sVersion[i] == '.' ){
			j++;
			if( j == 2 ){
				sWindowVersion.Format("%s", g_pConst->m_sVersion.Left(i) );
				break;
			}
		}
	}
/*	CString sWindowTitle, sWindowTitle2;
	GetWindowText(sWindowTitle);
	sWindowTitle2.Format("%s (Ver.%s)", sWindowTitle, g_pConst->m_sVersion);
	SetWindowText(sWindowTitle2);*/
// By Y.Itabashi (xxxxx) 2007.01.25

// Modify ... ( ADD )
	sprintf( Menu_WorkSchedulerDlg[0].str1, "%s", sWindowVersion );
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_WorkSchedulerDlg );
// By Y.Itabashi (xxxxx) 2007.01.24

	// IDM_ABOUTBOX �̓R�}���h ���j���[�͈̔͂łȂ���΂Ȃ�܂���B
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
//		CString strAboutMenu;
//		strAboutMenu.LoadString(IDS_ABOUTBOX);
//		if (!strAboutMenu.IsEmpty())
//		{
//			pSysMenu->AppendMenu(MF_SEPARATOR);
//			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
//		}
	}

	// ���̃_�C�A���O�p�̃A�C�R����ݒ肵�܂��B�t���[�����[�N�̓A�v���P�[�V�����̃��C��
	// �E�B���h�E���_�C�A���O�łȂ����͎����I�ɐݒ肵�܂���B
	SetIcon(m_hIcon, TRUE);			// �傫���A�C�R����ݒ�
	SetIcon(m_hIcon, FALSE);		// �������A�C�R����ݒ�

	InitMenuButton();
	InitSubMenu();
	EnableMenu();

// Modify ... ( ADD )
	g_pSystem->m_cMenuDisp.SetMenuText( this, Menu_WorkSchedulerMenu );
// By Y.Itabashi (xxxxx) 2007.01.26

	return TRUE;  // TRUE ��Ԃ��ƃR���g���[���ɐݒ肵���t�H�[�J�X�͎����܂���B
}

void CWorkSchedulerDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialogEx::OnSysCommand(nID, lParam);
	}
}

// �����_�C�A���O�{�b�N�X�ɍŏ����{�^����ǉ�����Ȃ�΁A�A�C�R����`�悷��
// �R�[�h���ȉ��ɋL�q����K�v������܂��BMFC �A�v���P�[�V������ document/view
// ���f�����g���Ă���̂ŁA���̏����̓t���[�����[�N�ɂ�莩���I�ɏ�������܂��B

void CWorkSchedulerDlg::OnPaint()
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
		CDialogEx::OnPaint();
	}
}

// �V�X�e���́A���[�U�[���ŏ����E�B���h�E���h���b�O���Ă���ԁA
// �J�[�\����\�����邽�߂ɂ������Ăяo���܂��B
HCURSOR CWorkSchedulerDlg::OnQueryDragIcon()
{
	return (HCURSOR)m_hIcon;
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::InitMenuButton
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�{�^��������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		���j���[�{�^����z��ɓ���A�{�^���̐F��ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::InitMenuButton()
{
	// ���j���[�{�^����z��ɐݒ�
	m_arrayMenuButton.Add(&m_buttonStart);
	m_arrayMenuButton.Add(&m_buttonSetConditions);
	m_arrayMenuButton.Add(&m_buttonAutoScheduling);
	m_arrayMenuButton.Add(&m_buttonManualScheduling);
	m_arrayMenuButton.Add(&m_buttonExit);

	// �{�^���̐F��ݒ�
	int nSize = m_arrayMenuButton.GetSize();
	for (int i = 0; i < nSize; i++)
		m_arrayMenuButton[i]->SetBackGroundColor(COLOR_MENU_BUTTON);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::InitSubMenu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u���j���[������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�T�u���j���[�̃_�C�A���O��z��ɓ���A�u�Ґ��J�n�v��I����Ԃɂ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::InitSubMenu()
{
	// �T�u���j���[�̃_�C�A���O��z��ɐݒ�
	m_cSubMenu.Add(new CStartMenuDlg, IDD_START_MENU);
	m_cSubMenu.Add(new CSetConditionsDlg, IDD_SET_CONDITIONS);
	m_cSubMenu.Add(new CAutoSchedulingDlg, IDD_AUTO_SCHEDULING);
	m_cSubMenu.Add(new CManualSchedulingDlg, IDD_MANUAL_SCHEDULING);

	// �u�Ґ��J�n�v��I��
	SelectMenu(MENU_START);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::SelectMenu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�I��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		int		nIndex			[I] �I�����郁�j���[�̃C���f�b�N�X
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�S�Ẵ��j���[�{�^���̑I��������������A�w�肳�ꂽ���j���[�{�^����I����Ԃɂ��A
//		�T�u���j���[��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::SelectMenu(int nIndex)
{
	// �I����Ԃ�����
	int nSize = m_arrayMenuButton.GetSize();
	for (int i = 0; i < nSize; i++) {
		if (m_arrayMenuButton[i]->GetCheck() != 0) {
			m_arrayMenuButton[i]->SetCheck(0);
			m_arrayMenuButton[i]->Invalidate();
		}
	}

	// �w�肳�ꂽ���j���[�{�^����I����Ԃɐݒ�
	m_arrayMenuButton[nIndex]->SetCheck(1);
	m_arrayMenuButton[nIndex]->Invalidate();

	// �T�u���j���[��\��
	m_cSubMenu.Show(nIndex);

	// �X�e�[�^�X�o�[�ɕ\��
	m_arrayMenuButton[nIndex]->GetWindowText(m_sMainStatus);
	m_cStaticStatus.SetWindowText(m_sMainStatus);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::EnableMenu
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�{�^���̗L�����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�X�e�[�^�X�ɂ�胁�j���[�{�^���̗L���^������ݒ肷��B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::EnableMenu()
{
	// �Ґ������ݒ�{�^��
	m_buttonSetConditions.EnableWindow(g_pDataManager->m_nStatus >= STATUS_READ_DATA);

	// �Ґ����s�{�^��
	m_buttonAutoScheduling.EnableWindow(g_pDataManager->m_nStatus >= STATUS_SEISAN_JOKEN && !g_pDataManager->m_bOpenLineKiseiMatrix && !g_pDataManager->m_bOpenKatashikiKiseiMatrix);

	// �蓮�Ґ��{�^��
	BOOL bSagyoshaWaritsuke = FALSE;
	int nSublineNum = g_pDataManager->GetSublineNum();
	for (int nSublineId = 0; nSublineId < nSublineNum; nSublineId++) {
		SSubline *pSubline = g_pDataManager->GetSublineData(nSublineId);
		if (pSubline->bSagyoshaWaritsuke) {
			bSagyoshaWaritsuke = TRUE;
			break;
		}
	}
	m_buttonManualScheduling.EnableWindow(g_pDataManager->m_nStatus >= STATUS_SEISAN_JOKEN && bSagyoshaWaritsuke);

	// �T�u���j���[�̃{�^��
	m_cSubMenu.EnableMenu();
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::SetSubMenuStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u���j���[�ɂ��X�e�[�^�X�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		LPCTSTR		pText			[I] �X�e�[�^�X�o�[�ɕ\������e�L�X�g
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�e�L�X�g���X�e�[�^�X�o�[�ɕ\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::SetSubMenuStatus(LPCTSTR pText)
{
	m_cStaticStatus.SetWindowText(m_sMainStatus + "�F" + pText);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::SetSubMenuStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u���j���[�ɂ��X�e�[�^�X�\��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CWnd		&wndButton			[I] �T�u���j���[�{�^��
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�w�肳�ꂽ�T�u���j���[�{�^���̃e�L�X�g���X�e�[�^�X�o�[�ɕ\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::SetSubMenuStatus(CWnd &wndButton)
{
	CString sText;
	wndButton.GetWindowText(sText);
	SetSubMenuStatus(sText);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::ClearSubMenuStatus
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�T�u���j���[�̃X�e�[�^�X����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�X�e�[�^�X�o�[����T�u���j���[�̃e�L�X�g����������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::ClearSubMenuStatus()
{
	m_cStaticStatus.SetWindowText(m_sMainStatus);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnButtonStart
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�Ґ��J�n�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�Ґ��J�n�T�u���j���[��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnButtonStart()
{
	SelectMenu(MENU_START);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnButtonSetConditions
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�Ґ������ݒ�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�Ґ������ݒ�T�u���j���[��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnButtonSetConditions()
{
	SelectMenu(MENU_SET_CONDITIONS);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnButtonAutoScheduling
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�Ґ����s�v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�Ґ����s�T�u���j���[��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnButtonAutoScheduling()
{
	SelectMenu(MENU_AUTO_SCHEDULING);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnButtonManualScheduling
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�蓮�Ґ��v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�蓮�Ґ��T�u���j���[��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnButtonManualScheduling()
{
	SelectMenu(MENU_MANUAL_SCHEDULING);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnButtonExit
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�u�I���v�{�^���N���b�N����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƕ\���X�V����Ă���ꍇ�͕ۑ����A�v���O�������I������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnButtonExit()
{
	if (!g_pSystem->m_bReadOnly && g_pDataManager->m_bUpdate) {
		if (g_pSystem->DispMessage("Q030001") == IDYES)
			g_pDataManager->SaveFile();
	}

	if (g_pSystem->DispMessage("Q030002") == IDYES)
		DestroyWindow();
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnFileSave
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�㏑���ۑ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�v�f��ƕ\���㏑���ۑ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnFileSave()
{
	g_pSystem->m_Save_mode = 1;	//2007/02/10 xxxxx kuwa

	if (g_pSystem->DispMessage("Q003003") == IDYES) {
		if (g_pDataManager->SaveFile())
			g_pSystem->DispMessage("I003004");
	}
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnFileSaveAs
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���O��t���ĕۑ�����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�t�@�C���ۑ��_�C�A���O��\�����A���͂��ꂽ�t�@�C�����ŗv�f��ƕ\��ۑ�����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnFileSaveAs()
{
	g_pSystem->m_Save_mode = 2;	//2007/02/10 xxxxx kuwa

	CSaveProjectDlg dlg;
	if (dlg.DoModal() == IDOK)
		g_pSystem->DispMessage("I002005");
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�L�����Z������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�I�������Ɠ����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnCancel()
{
#ifdef _DEBUG
	DestroyWindow();
#else
	OnButtonExit();
#endif
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnUpdateFileSave
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�㏑���ۑ����j���[�L�����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�I�u�W�F�N�g�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�G�N�Z���t�@�C�����ǂ܂�Ă���Ώ㏑���ۑ����j���[��L���ɂ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnUpdateFileSave(CCmdUI* pCmdUI)
{
	pCmdUI->Enable(g_pDataManager->m_nStatus != STATUS_NOREAD && !g_pSystem->m_bReadOnly);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnUpdateFileSaveAs
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���O��t���ĕۑ����j���[�L�����ݒ�
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CCmdUI		*pCmdUI			[I] CCmdUI�I�u�W�F�N�g�|�C���^
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�G�N�Z���t�@�C�����ǂ܂�Ă���Ζ��O��t���ĕۑ����j���[��L���ɂ���B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
void CWorkSchedulerDlg::OnUpdateFileSaveAs(CCmdUI* pCmdUI)
{
	pCmdUI->Enable(g_pDataManager->m_nStatus != STATUS_NOREAD);
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerDlg::OnInitMenuPopup
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		���j���[�\���n���h��
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		CMenu		*pPopupMenu		[I] ���j���[�I�u�W�F�N�g�|�C���^
//		UINT		nIndex			[I] ���j���[�C���f�b�N�X
//		BOOL		bSysMenu		[I] �V�X�e�����j���[�t���O
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�_�C�A���O��OnUpdate**�n���h����L���ɂ��邽�߂̏����B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//		MFC��FrameWnd�N���X����R�s�[
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
// ���j���[�\���n���h��
void CWorkSchedulerDlg::OnInitMenuPopup(CMenu* pPopupMenu, UINT nIndex, BOOL bSysMenu)
{
	CDialogEx::OnInitMenuPopup(pPopupMenu, nIndex, bSysMenu);

	// [artifact]CMainFrame�̃����o�ϐ� m_bAutoMenuEnable �̑���ɒ�`�B
	// true �Ȃ�f�t�H���g�Ń��j���[��������ԂɂȂ�B
	bool bAutoMenuEnable = true;

//	AfxCancelModes(m_hWnd);

	if (bSysMenu)
		return; // don't support system menu

	ASSERT(pPopupMenu != NULL);
	// check the enabled state of various menu items

	CCmdUI state;
	state.m_pMenu = pPopupMenu;
	ASSERT(state.m_pOther == NULL);
	ASSERT(state.m_pParentMenu == NULL);

	// determine if menu is popup in top-level menu and set m_pOther to
	//  it if so (m_pParentMenu == NULL indicates that it is secondary popup)
	HMENU hParentMenu;
	if (AfxGetThreadState()->m_hTrackingMenu == pPopupMenu->m_hMenu)
		state.m_pParentMenu = pPopupMenu; // parent == child for tracking popup
	else if ((hParentMenu = ::GetMenu(m_hWnd)) != NULL)
	{
		CWnd* pParent = GetTopLevelParent();
		// child windows don't have menus -- need to go to the top!
		if (pParent != NULL &&
			(hParentMenu = ::GetMenu(pParent->m_hWnd)) != NULL)
		{
			int nIndexMax = ::GetMenuItemCount(hParentMenu);
			for (int nIndex = 0; nIndex < nIndexMax; nIndex++)
			{
				if (::GetSubMenu(hParentMenu, nIndex) == pPopupMenu->m_hMenu)
				{
					// when popup is found, m_pParentMenu is containing menu
					state.m_pParentMenu = CMenu::FromHandle(hParentMenu);
					break;
				}
			}
		}
	}

	state.m_nIndexMax = pPopupMenu->GetMenuItemCount();
	for (state.m_nIndex = 0; state.m_nIndex < state.m_nIndexMax;
			state.m_nIndex++)
	{
		state.m_nID = pPopupMenu->GetMenuItemID(state.m_nIndex);
		if (state.m_nID == 0)
			continue; // menu separator or invalid cmd - ignore it

		ASSERT(state.m_pOther == NULL);
		ASSERT(state.m_pMenu != NULL);
		if (state.m_nID == (UINT)-1)
		{
			// possibly a popup menu, route to first item of that popup
			state.m_pSubMenu = pPopupMenu->GetSubMenu(state.m_nIndex);
			if (state.m_pSubMenu == NULL ||
				(state.m_nID = state.m_pSubMenu->GetMenuItemID(0)) == 0 ||
				state.m_nID == (UINT)-1)
			{
				continue;       // first item of popup can't be routed to
			}
			state.DoUpdate(this, FALSE);    // popups are never auto disabled
		}
		else
		{
			// normal menu item
			// Auto enable/disable if frame window has 'm_bAutoMenuEnable'
			//    set and command is _not_ a system command.
			state.m_pSubMenu = NULL;
			state.DoUpdate(this, bAutoMenuEnable && state.m_nID < 0xF000);
		}

		// adjust for menu deletions and additions
		UINT nCount = pPopupMenu->GetMenuItemCount();
		if (nCount < state.m_nIndexMax)
		{
			state.m_nIndex -= (state.m_nIndexMax - nCount);
			while (state.m_nIndex < nCount &&
				pPopupMenu->GetMenuItemID(state.m_nIndex) == state.m_nID)
			{
				state.m_nIndex++;
			}
		}
		state.m_nIndexMax = nCount;
	}
}

void CWorkSchedulerDlg::OnHelpVersion()
{
	CAboutDlg dlgAbout;
	dlgAbout.DoModal();
}

void CWorkSchedulerDlg::PostNcDestroy()
{
	CDialogEx::PostNcDestroy();

	delete this;
}

LRESULT CWorkSchedulerDlg::OnOpenExcel(WPARAM wParam, LPARAM lParam)
{
	g_pDataManager->OpenExcelNotify(wParam);

	EnableMenu();

	return 0;
}

LRESULT CWorkSchedulerDlg::OnCloseExcel(WPARAM wParam, LPARAM lParam)
{
	g_pDataManager->CloseExcelNotify(wParam);

	EnableMenu();

	return 0;
}

LRESULT CWorkSchedulerDlg::OnErrorExcel(WPARAM wParam, LPARAM lParam)
{
	g_pDataManager->ErrorExcelNotify(wParam);

	EnableMenu();

	return 0;
}

// Enter�L�[�Ń_�C�A���O������̂�}�~
void CWorkSchedulerDlg::OnOK()
{
}