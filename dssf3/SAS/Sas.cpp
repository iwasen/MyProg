// Sas.cpp : �A�v���P�[�V�����p�N���X�̋@�\��`���s���܂��B
//

#include "stdafx.h"
#include "Sas.h"

#include "MainFrm.h"
#include "SasDoc.h"
#include "SasView.h"
#include "UpdateAp.h"
#include "VerRes.h"
#include "HyperLink.h"
#include "Regist.h"
#include "ImpFactor.h"
#include "AcfFactor.h"

CImageList imageList;

/////////////////////////////////////////////////////////////////////////////
// SasApp

BEGIN_MESSAGE_MAP(SasApp, CWinApp)
	ON_COMMAND(ID_APP_ABOUT, OnAppAbout)
	ON_COMMAND(IDM_ONLINE_UPDATE, OnOnlineUpdate)
	ON_COMMAND(ID_FILE_OPEN, CWinApp::OnFileOpen)
	ON_COMMAND(IDM_ONLINE_MANUAL, OnOnlineManual)
	ON_COMMAND(IDM_TECHNICAL_SUPPORT, OnTechnicalSupport)
	ON_COMMAND(IDM_DOCUMENT_QA, OnDocumentQa)
	ON_COMMAND(IDM_README, OnReadme)
	ON_COMMAND(IDM_xxxx_STORE, OnxxxxStore)
	ON_COMMAND(IDM_LICENSE_REGIST, OnLicenseRegist)
	ON_UPDATE_COMMAND_UI(IDM_LICENSE_REGIST, OnUpdateLicenseRegist)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// SasApp �N���X�̍\�z

SasApp::SasApp()
{
	m_nCheckData = GET_CHECK_DATA;
	if (GetCheckData2() == m_nCheckData)
		m_pRegistryName = g_sRegistryName;
}

/////////////////////////////////////////////////////////////////////////////
// �B��� SasApp �I�u�W�F�N�g

SasApp theApp;

/////////////////////////////////////////////////////////////////////////////
// SasApp �N���X�̏�����

BOOL SasApp::InitInstance()
{
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();
	AfxEnableControlContainer();
	AfxOleInit();

	// �W���I�ȏ���������
	// ���������̋@�\���g�p�����A���s�t�@�C���̃T�C�Y��������
	//  ��������Έȉ��̓���̏��������[�`���̒�����s�K�v�Ȃ���
	//  ���폜���Ă��������B
	g_hIcon = LoadIcon(IDR_MAINFRAME);
	CVerRes res;
	if (!CHECK_LICENSE0(APP_SA, res.GetProductVersion()))
		return FALSE;

	imageList.Create(IDB_IMAGE_LIST, 16, 1, RGB(255, 255, 255));

	// �ݒ肪�ۑ�����鉺�̃��W�X�g�� �L�[��ύX���܂��B
	// ��Ж��܂��͏����ȂǁA�K�؂ȕ������
	// �ύX���Ă��������B
//	SetRegistryKey(_T("Local AppWizard-Generated Applications"));

//	AdjustDialogSize();

	LoadStdProfileSettings(0);  // �W���� INI �t�@�C���̃I�v�V���������[�ނ��܂� (MRU ���܂�)

	g_oPcserve.Initialize();

	BOOL bImp = FALSE;
	BOOL bAcf = FALSE;
	for (int i = 1; i < __argc; i++) {
		CString argv = __argv[i];

		if (argv == "/IMP")
			bImp = TRUE;

		if (argv == "/ACF")
			bAcf = TRUE;
	}

	// �A�v���P�[�V�����p�̃h�L�������g �e���v���[�g��o�^���܂��B�h�L�������g �e���v���[�g
	//  �̓h�L�������g�A�t���[�� �E�B���h�E�ƃr���[���������邽�߂ɋ@�\���܂��B

	if (bImp) {
		CImpFactor *pDlg = new CImpFactor((CWnd *)&CWnd::wndTop);
		pDlg->m_nFolderID = -2;
		pDlg->m_nImpulseID = -2;
		pDlg->Create();
		m_pMainWnd = pDlg;
	} else if (bAcf) {
		CAcfFactor *pDlg = new CAcfFactor((CWnd *)&CWnd::wndTop);
		pDlg->m_nFolderID = -2;
		pDlg->m_nAcfID = -2;
		pDlg->Create();
		m_pMainWnd = pDlg;
	} else {
		CSingleDocTemplate* pDocTemplate;
		pDocTemplate = new CSingleDocTemplate(
			IDR_MAINFRAME,
			RUNTIME_CLASS(CSasDoc),
			RUNTIME_CLASS(CMainFrame),       // ���C�� SDI �t���[�� �E�B���h�E
			RUNTIME_CLASS(CSasView));
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
	}

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
	CHyperLink m_cCompanyURL;
	CHyperLink m_cEmailAddr;
	CHyperLink m_cxxxxStoreURL;

protected:
	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // // DDX/DDV �̃T�|�[�g
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
	sVersion += ", dev";
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
void SasApp::OnAppAbout()
{
	CAboutDlg aboutDlg;
	aboutDlg.DoModal();
}

/////////////////////////////////////////////////////////////////////////////
// SasApp �R�}���h

LRESULT SasApp::ProcessWndProcException(CException* e, const MSG* /*pMsg*/)
{
	e->ReportError(MB_ICONEXCLAMATION|MB_SYSTEMMODAL);
	exit(1);
//	return CWinApp::ProcessWndProcException(e, pMsg);
}

void SasApp::OnOnlineUpdate()
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
	pOnlineUpdate(sOnlineUpdate, exeFileName, m_lpCmdLine, LoadIcon(IDR_MAINFRAME), "Sound\nAnalyzer", g_sRegistryName);
}

void SasApp::AdjustDialogSize()
{
	HDC dc = ::GetDC(NULL);
	int nLogPixel = GetDeviceCaps(dc, LOGPIXELSX);
	::ReleaseDC(NULL, dc);

	if (nLogPixel <= 96)
		return;

	int nFontSize = 9 * 96 / nLogPixel;

	SetDialogFontSize(IDD_ACF_FACTOR, nFontSize);
	SetDialogFontSize(IDD_NMS_FACTOR, nFontSize);
	SetDialogFontSize(IDD_IMP_FACTOR, nFontSize);

	SetDialogFontSize(IDD_GRAPH_ACF, nFontSize);
	SetDialogFontSize(IDD_GRAPH_ECHO, nFontSize);
	SetDialogFontSize(IDD_GRAPH_FACTOR, nFontSize);
	SetDialogFontSize(IDD_GRAPH_FREQ, nFontSize);
	SetDialogFontSize(IDD_GRAPH_IACC, nFontSize);
	SetDialogFontSize(IDD_GRAPH_IACF, nFontSize);
	SetDialogFontSize(IDD_GRAPH_IR, nFontSize);
	SetDialogFontSize(IDD_GRAPH_PARAM, nFontSize);
	SetDialogFontSize(IDD_GRAPH_PREF, nFontSize);
}

void SasApp::SetDialogFontSize(UINT nIDTemplate, int nFontSize)
{
#pragma pack(push, 1)
	typedef struct {
		WORD   dlgVer;
		WORD   signature;
		DWORD  helpID;
		DWORD  exStyle;
		DWORD  style;
		WORD   cDlgItems;
		short  x;
		short  y;
		short  cx;
		short  cy;
	//    sz_Or_Ord menu;         // name or ordinal of a menu resource
	//    sz_Or_Ord windowClass;  // name or ordinal of a window class
	//    WCHAR  title[titleLen]; // title string of the dialog box
	//    short  pointsize;       // only if DS_SETFONT flag is set
	//    short  weight;          // only if DS_SETFONT flag is set
	//    short  bItalic;         // only if DS_SETFONT flag is set
	//    WCHAR  font[fontLen];   // typeface name, if DS_SETFONT is set
} DLGTEMPLATEEX, *LPDLGTEMPLATEEX;
#pragma pack(pop)
	DWORD dwStyle;
	LPWORD lpDlgTmp;

	HINSTANCE hInst = AfxGetResourceHandle();
	HRSRC hResInfo = ::FindResource(hInst, MAKEINTRESOURCE(nIDTemplate), RT_DIALOG);
	HGLOBAL hRes = ::LoadResource(hInst, hResInfo);
	LPDLGTEMPLATEEX lpDlgTemplateEx = (LPDLGTEMPLATEEX)::LockResource(hRes);
	if (lpDlgTemplateEx->signature == 0xffff) {
		dwStyle = lpDlgTemplateEx->style;
		lpDlgTmp = (LPWORD)((char *)lpDlgTemplateEx + sizeof(DLGTEMPLATEEX));
	} else {
		LPDLGTEMPLATE lpDlgTemplate = (LPDLGTEMPLATE)lpDlgTemplateEx;
		dwStyle = lpDlgTemplate->style;
		lpDlgTmp = (LPWORD)((char *)lpDlgTemplate + sizeof(DLGTEMPLATE));
	}

	if (dwStyle & DS_SETFONT) {
		if (*lpDlgTmp == 0xffff)
			lpDlgTmp += 2;
		else {
			while (*lpDlgTmp++ != 0)
				;
		}

		if (*lpDlgTmp == 0xffff)
			lpDlgTmp += 2;
		else {
			while (*lpDlgTmp++ != 0)
				;
		}
		while (*lpDlgTmp++ != 0)
			;

		*lpDlgTmp = (WORD)nFontSize;
	}
}


void SasApp::OnOnlineManual()
{
	OpenURL(IDS_ONLINE_MANUAL_CMD);
}

void SasApp::OnTechnicalSupport()
{
	OpenURL(IDS_TECHNICAL_SUPPORT_CMD);
}

void SasApp::OnDocumentQa()
{
	OpenURL(IDS_DOCUMENT_QA_CMD);
}

void SasApp::OnReadme()
{
	CString sReadmeFile;

	sReadmeFile.Format("%s\\ReadMe.txt", GetExeDir());
	OpenURL(sReadmeFile);
}

void SasApp::OnxxxxStore()
{
	OpenURL(IDS_xxxx_STORE);
}

void SasApp::OnLicenseRegist()
{
	REGIST_LICENSE();
}

void SasApp::OnUpdateLicenseRegist(CCmdUI *pCmdUI)
{
	pCmdUI->Enable(CHECK_LICENSE2(APP_SA) != LICENSE_REGIST);
}

BOOL SasApp::PreTranslateMessage(MSG* pMsg)
{
	if (pMsg->hwnd != NULL && pMsg->message == WM_KEYDOWN) {
		if (pMsg->wParam == VK_F9) {
			ScreenShot(pMsg->hwnd);
		}
	}

	return CWinApp::PreTranslateMessage(pMsg);
}
