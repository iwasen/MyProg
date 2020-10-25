// MMView.cpp : �A�v���P�[�V�����p�N���X�̋@�\��`���s���܂��B
//

#include "stdafx.h"
#include "MMView.h"

#include "MainFrm.h"
#include "MMViewDoc.h"
#include "MMPictView.h"
#include "UpdateAp.h"
#include "VerRes.h"
#include "HyperLink.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CMMViewApp

BEGIN_MESSAGE_MAP(CMMViewApp, CWinApp)
	ON_COMMAND(ID_APP_ABOUT, OnAppAbout)
	ON_COMMAND(IDM_ONLINE_UPDATE, OnOnlineUpdate)
	ON_UPDATE_COMMAND_UI(IDM_ONLINE_UPDATE, OnUpdateOnlineUpdate)
	ON_COMMAND(IDM_ONLINE_MANUAL, OnOnlineManual)
	// �W���̃t�@�C����{�h�L�������g �R�}���h
	ON_COMMAND(ID_FILE_OPEN, CWinApp::OnFileOpen)
	// �W���̈���Z�b�g�A�b�v �R�}���h
	ON_COMMAND(ID_FILE_PRINT_SETUP, CWinApp::OnFilePrintSetup)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMViewApp �N���X�̍\�z

CMMViewApp::CMMViewApp()
{
	// TODO: ���̈ʒu�ɍ\�z�p�R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CMMViewApp �I�u�W�F�N�g

CMMViewApp theApp;

/////////////////////////////////////////////////////////////////////////////
// CMMViewApp �N���X�̏�����

BOOL CMMViewApp::InitInstance()
{
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	AfxEnableControlContainer();

	// �W���I�ȏ���������
	// ���������̋@�\���g�p�����A���s�t�@�C���̃T�C�Y��������
	// ��������Έȉ��̓���̏��������[�`���̒�����s�K�v�Ȃ���
	// ���폜���Ă��������B

	// �ݒ肪�ۑ�����鉺�̃��W�X�g�� �L�[��ύX���܂��B
	// TODO: ���̕�������A��Ж��܂��͏����ȂǓK�؂Ȃ��̂�
	// �ύX���Ă��������B
	SetRegistryKey(_T("xxxxxxxxx Electronic Inc.\\Multimedia Library"));

	LoadStdProfileSettings();  // �W���� INI �t�@�C���̃I�v�V���������[�ނ��܂� (MRU ���܂�)

	g_ImageList.Create(IDB_IMAGE_LIST_SMALL, 16, 1, RGB(255, 255, 255));
	g_ImageList2.Create(IDB_IMAGE_LIST_LARGE, 32, 1, RGB(255, 255, 255));

	// �J�[�\���̃��[�h
	m_hCursorArrow = LoadStandardCursor(IDC_ARROW);
	m_hCursorHandOpen = LoadCursor(IDC_HANDOPEN);
	m_hCursorHandClose = LoadCursor(IDC_HANDCLOSE);

	m_hIconApp = LoadIcon(IDR_MAINFRAME);

	::GetModuleFileName(m_hInstance, m_ExeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	m_ExeFileName.ReleaseBuffer();

	CFileStatus st;
	m_bOnlineUpdate = CFile::GetStatus(m_ExeFileName.Left(m_ExeFileName.ReverseFind('\\') + 1) + "YMUPDATE.INI", st);

	// �A�v���P�[�V�����p�̃h�L�������g �e���v���[�g��o�^���܂��B�h�L�������g �e���v���[�g
	//  �̓h�L�������g�A�t���[�� �E�B���h�E�ƃr���[���������邽�߂ɋ@�\���܂��B

	CSingleDocTemplate* pDocTemplate;
	pDocTemplate = new CSingleDocTemplate(
		IDR_MAINFRAME,
		RUNTIME_CLASS(CMMViewDoc),
		RUNTIME_CLASS(CMainFrame),       // ���C�� SDI �t���[�� �E�B���h�E
		RUNTIME_CLASS(CMMPictView));
	AddDocTemplate(pDocTemplate);

	// DDE�Afile open �ȂǕW���̃V�F�� �R�}���h�̃R�}���h���C������͂��܂��B
	CCommandLineInfo cmdInfo;
	ParseCommandLine(cmdInfo); 

	// �R�}���h���C���Ńf�B�X�p�b�` �R�}���h���w�肵�܂��B
	if (!ProcessShellCommand(cmdInfo))
		return FALSE;

	// ���C�� �E�B���h�E�����������ꂽ�̂ŁA�\���ƍX�V���s���܂��B
	m_pMainWnd->ShowWindow(GetProfileInt(g_SectionMainWindow, "Show", SW_SHOW));
	m_pMainWnd->UpdateWindow();

	return TRUE;
}


/////////////////////////////////////////////////////////////////////////////
// �A�v���P�[�V�����̃o�[�W�������Ŏg���� CAboutDlg �_�C�A���O

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ABOUTBOX };
	CHyperLink	m_cProductURL;
	CHyperLink	m_cEmailAddr;
	CHyperLink	m_cCompanyURL;

	// ClassWizard ���z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �̃T�|�[�g

// �C���v�������e�[�V����
protected:
	virtual BOOL OnInitDialog();
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_PRODUCT_URL, m_cProductURL);
	DDX_Control(pDX, IDC_EMAIL_ADDR, m_cEmailAddr);
	DDX_Control(pDX, IDC_COMPANY_URL, m_cCompanyURL);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()

// �_�C�A���O�����s���邽�߂̃A�v���P�[�V���� �R�}���h
void CMMViewApp::OnAppAbout()
{
	CAboutDlg aboutDlg;
	aboutDlg.DoModal();
}

/////////////////////////////////////////////////////////////////////////////
// CMMViewApp ���b�Z�[�W �n���h��

void CMMViewApp::OnOnlineUpdate() 
{
	OnlineUpdateFunc pOnlineUpdate;
	HINSTANCE hModule;
	CString exe;

	if ((hModule = LoadLibrary("YMUPDATE.DLL")) == NULL) {
		AfxMessageBox(IDS_NOT_FOUND_YMUPDATE_DLL);
		return;
	}

	if ((pOnlineUpdate = (OnlineUpdateFunc)GetProcAddress(hModule, "OnlineUpdate")) == NULL) {
		AfxMessageBox(IDS_ONLINE_UPDATE_FAIL);
		::FreeLibrary(hModule);
		return;
	}

	CString sOnlineUpdate;
	sOnlineUpdate.LoadString(IDS_ONLINE_UPDATE);
#ifdef _WIN64
	sOnlineUpdate += "64";
#else
	sOnlineUpdate += "32";
#endif
	pOnlineUpdate(sOnlineUpdate, m_ExeFileName, m_lpCmdLine, m_hIconApp, "MMViewer", NULL);
}

void CMMViewApp::OnUpdateOnlineUpdate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bOnlineUpdate);
}

void CMMViewApp::OnOnlineManual() 
{
	ShellExecute(NULL, "open", GetString(IDS_HELP_URL), NULL, NULL, SW_SHOWNORMAL);
}

BOOL CAboutDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	CVerRes		res;
	SetDlgItemText(IDC_PRODUCT, res.GetProductName() + "  Version " + res.GetProductVersion());
	SetDlgItemText(IDC_COPYRIGHT, res.GetLegalCopyright());

	m_cProductURL.SetHyperLinkURL(NULL);
	m_cCompanyURL.SetHyperLinkURL(NULL);
	m_cEmailAddr.SetHyperLinkURL("mailto:shop@xxxx.com");

	return TRUE;
}
