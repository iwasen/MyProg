// Nms.cpp : �A�v���P�[�V�����p�N���X�̋@�\��`���s���܂��B
//

#include "stdafx.h"
#include "Nms.h"
#include "MainFrm.h"
#include "NmsDoc.h"
#include "NmsView.h"
#include "UpdateAp.h"
#include "SplWnd.h"
#include "PeakLevelWnd.h"
#include "NmsFactor.h"
#include "VerRes.h"
#include "HyperLink.h"
#include "SetData.h"
#include "Regist.h"
#include "Directory.h"

/////////////////////////////////////////////////////////////////////////////
// CNmsApp

BEGIN_MESSAGE_MAP(CNmsApp, CWinApp)
	ON_COMMAND(ID_APP_ABOUT, OnAppAbout)
	ON_COMMAND(IDM_ONLINE_UPDATE, OnOnlineUpdate)
	// �W���̃t�@�C����{�h�L�������g �R�}���h
	ON_COMMAND(ID_FILE_NEW, CWinApp::OnFileNew)
	ON_COMMAND(ID_FILE_OPEN, CWinApp::OnFileOpen)
	// �W���̈���Z�b�g�A�b�v �R�}���h
	ON_COMMAND(ID_FILE_PRINT_SETUP, CWinApp::OnFilePrintSetup)
	ON_COMMAND(IDM_ONLINE_MANUAL, OnOnlineManual)
	ON_COMMAND(IDM_TECHNICAL_SUPPORT, OnTechnicalSupport)
	ON_COMMAND(IDM_DOCUMENT_QA, OnDocumentQa)
	ON_COMMAND(IDM_README, OnReadme)
	ON_COMMAND(IDM_xxxx_STORE, OnxxxxStore)
	ON_COMMAND(IDM_LICENSE_REGIST, OnLicenseRegist)
	ON_UPDATE_COMMAND_UI(IDM_LICENSE_REGIST, OnUpdateLicenseRegist)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CNmsApp �N���X�̍\�z

CNmsApp::CNmsApp()
{
	m_nCheckData = GET_CHECK_DATA;
	if (GetCheckData2() == m_nCheckData)
		m_pRegistryName = g_sRegistryName;
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CNmsApp �I�u�W�F�N�g

CNmsApp theApp;

/////////////////////////////////////////////////////////////////////////////
// CNmsApp �N���X�̏�����

BOOL CNmsApp::InitInstance()
{
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();
	AfxEnableControlContainer();

	// �W���I�ȏ���������
	// ���������̋@�\���g�p�����A���s�t�@�C���̃T�C�Y��������
	// ��������Έȉ��̓���̏��������[�`���̒�����s�K�v�Ȃ���
	// ���폜���Ă��������B

	// �ݒ肪�ۑ�����鉺�̃��W�X�g�� �L�[��ύX���܂��B
	// TODO: ���̕�������A��Ж��܂��͏����ȂǓK�؂Ȃ��̂�
	// �ύX���Ă��������B
//	SetRegistryKey(_T("Local AppWizard-Generated Applications"));

	LoadStdProfileSettings(0);  // �W���� INI �t�@�C���̃I�v�V���������[�ނ��܂� (MRU ���܂�)

	g_oPcserve.Initialize();

//	SetPriorityClass(GetCurrentProcess(), HIGH_PRIORITY_CLASS);

	::GetTempPath(MAX_PATH, g_sTempDir.GetBuffer(MAX_PATH));
	g_sTempDir.ReleaseBuffer();
	g_sTempDir += "\\NMS";
	DeleteDirectoryAll(g_sTempDir);
	CreateDirectoryAll(g_sTempDir);

	g_hIcon = LoadIcon(IDR_MAINFRAME);
	CVerRes res;
	if (!CHECK_LICENSE0(APP_EA, res.GetProductVersion() + "/EA"))
		return FALSE;

	// �A�v���P�[�V�����p�̃h�L�������g �e���v���[�g��o�^���܂��B�h�L�������g �e���v���[�g
	//  �̓h�L�������g�A�t���[�� �E�B���h�E�ƃr���[���������邽�߂ɋ@�\���܂��B

	CSingleDocTemplate* pDocTemplate;
	pDocTemplate = new CSingleDocTemplate(
		IDR_MAINFRAME,
		RUNTIME_CLASS(CNmsDoc),
		RUNTIME_CLASS(CMainFrame),       // ���C�� SDI �t���[�� �E�B���h�E
		RUNTIME_CLASS(CNmsView));
	AddDocTemplate(pDocTemplate);

	// DDE�Afile open �ȂǕW���̃V�F�� �R�}���h�̃R�}���h���C������͂��܂��B
	CCommandLineInfo cmdInfo;
	ParseCommandLine(cmdInfo);

	// �R�}���h���C���Ńf�B�X�p�b�` �R�}���h���w�肵�܂��B
	if (!ProcessShellCommand(cmdInfo))
		return FALSE;

	// ���C�� �E�B���h�E�����������ꂽ�̂ŁA�\���ƍX�V���s���܂��B
	m_pMainWnd->ShowWindow(SW_SHOW);
	m_pMainWnd->UpdateWindow();

	return TRUE;
}


/////////////////////////////////////////////////////////////////////////////
// �A�v���P�[�V�����̃o�[�W�������Ŏg���� CAboutDlg �_�C�A���O

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

	enum { IDD = IDD_ABOUTBOX };

public:
	CHyperLink m_cProductURL;
	CHyperLink m_cxxxxStoreURL;
	CHyperLink m_cCompanyURL;
	CHyperLink m_cEmailAddr;

protected:
	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �̃T�|�[�g
	virtual BOOL OnInitDialog();
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_PRODUCT_URL, m_cProductURL);
	DDX_Control(pDX, IDC_COMPANY_URL, m_cCompanyURL);
	DDX_Control(pDX, IDC_EMAIL_ADDR, m_cEmailAddr);
	DDX_Control(pDX, IDC_xxxx_STORE, m_cxxxxStoreURL);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()

BOOL CAboutDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	CVerRes		res;
	CString sVersion;
	sVersion = res.GetProductName() + "  Version " + res.GetProductVersion();
#ifdef BUILD_DEV
	sVersion += "d";
#endif
#ifdef _WIN64
	sVersion += " (64bit)";
#else
	sVersion += " (32bit)";
#endif
	SetDlgItemText(IDC_PRODUCT, sVersion);
	SetDlgItemText(IDC_COPYRIGHT, res.GetLegalCopyright());

	m_cProductURL.SetHyperLinkURL(NULL);
	m_cxxxxStoreURL.SetHyperLinkURL(NULL);
	m_cCompanyURL.SetHyperLinkURL(NULL);
	m_cEmailAddr.SetHyperLinkURL("mailto:xxxx@xxxx.com");

	return TRUE;
}

// �_�C�A���O�����s���邽�߂̃A�v���P�[�V���� �R�}���h
void CNmsApp::OnAppAbout()
{
	CAboutDlg aboutDlg;
	aboutDlg.DoModal();
}

/////////////////////////////////////////////////////////////////////////////
// CNmsApp ���b�Z�[�W �n���h��

int CNmsApp::ExitInstance()
{
	g_oSetData.SplWindow.bShow = (g_pSplWnd != NULL) ? TRUE : FALSE;
	if (g_pSplWnd != NULL)
		g_pSplWnd->DestroyWindow();

	g_oSetData.PeakLevelWindow.bShow = (g_pPeakLevelWnd != NULL) ? TRUE : FALSE;
	if (g_pPeakLevelWnd != NULL)
		g_pPeakLevelWnd->DestroyWindow();

	CNmsFactor::CloseFactorWnd();

	if (!g_sTempDir.IsEmpty())
		DeleteDirectoryAll(g_sTempDir);

	return CWinApp::ExitInstance();
}

void CNmsApp::OnOnlineUpdate()
{
	OnlineUpdateFunc pOnlineUpdate;
	HINSTANCE hModule;
	CString exeFileName;

#ifdef _DEBUG
	if ((hModule = ::LoadLibrary("YMUPDATED.DLL")) == NULL) {
#else
	if ((hModule = ::LoadLibrary("YMUPDATE.DLL")) == NULL) {
#endif
		AfxMessageBox(IDS_ERR_YMUPDATE);
		return;
	}

	if ((pOnlineUpdate = (OnlineUpdateFunc)::GetProcAddress(hModule, "OnlineUpdate")) == NULL) {
		AfxMessageBox(IDS_ERR_ONLINEUPDATE);
		::FreeLibrary(hModule);
		return;
	}

	::GetModuleFileName(m_hInstance, exeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	exeFileName.ReleaseBuffer();

	CString sOnlineUpdate;
	sOnlineUpdate.LoadString(IDS_ONLINEUPDATE);
#ifdef _WIN64
	sOnlineUpdate += "64";
#else
	sOnlineUpdate += "32";
#endif
	pOnlineUpdate(sOnlineUpdate, exeFileName, m_lpCmdLine, LoadIcon(IDR_MAINFRAME), "Noise\nAnalyzer", g_sRegistryName);
}

void CNmsApp::OnOnlineManual()
{
	OpenURL(IDS_ONLINE_MANUAL_CMD);
}

void CNmsApp::OnTechnicalSupport()
{
	OpenURL(IDS_TECHNICAL_SUPPORT_CMD);
}

void CNmsApp::OnDocumentQa()
{
	OpenURL(IDS_DOCUMENT_QA_CMD);
}

void CNmsApp::OnReadme()
{
	CString sReadmeFile;

	sReadmeFile.Format("%s\\ReadMe.txt", GetExeDir());
	OpenURL(sReadmeFile);
}

void CNmsApp::OnxxxxStore()
{
	OpenURL(IDS_xxxx_STORE);
}

void CNmsApp::OnLicenseRegist()
{
	REGIST_LICENSE();
}

void CNmsApp::OnUpdateLicenseRegist(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(CHECK_LICENSE2(APP_EA) != LICENSE_REGIST);
}

BOOL CNmsApp::PreTranslateMessage(MSG* pMsg)
{
	if (pMsg->hwnd != NULL && pMsg->message == WM_KEYDOWN) {
		if (pMsg->wParam == VK_F9) {
			ScreenShot(pMsg->hwnd);
		}
	}

	return CWinApp::PreTranslateMessage(pMsg);
}
