// MMLib.cpp : �A�v���P�[�V�����p�N���X�̋@�\��`���s���܂��B
//

#include "stdafx.h"
#include "MMLib.h"

#include "MainFrm.h"
#include "MMLibDoc.h"
#include "MMLibTreeView.h"
#include "MMLibDB.h"
#include "UpdateAp.h"
#include "PictFrame.h"
#include "PictDoc.h"
#include "PictView.h"
#include "FindFrame.h"
#include "FindDoc.h"
#include "FindView.h"
#include "VerRes.h"
#include "HyperLink.h"
#include "Regist.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

#define DDE_TEXT	1
#define DDE_DATA	2

/////////////////////////////////////////////////////////////////////////////
// CMMLibApp

BEGIN_MESSAGE_MAP(CMMLibApp, CWinApp)
	ON_COMMAND(ID_APP_ABOUT, OnAppAbout)
	ON_COMMAND(ID_FILE_NEW, OnFileNew)
	ON_COMMAND(IDM_ONLINE_UPDATE, OnOnlineUpdate)
	ON_UPDATE_COMMAND_UI(IDM_ONLINE_UPDATE, OnUpdateOnlineUpdate)
	ON_COMMAND(IDM_ONLINE_MANUAL, OnOnlineManual)
	// �W���̃t�@�C����{�h�L�������g �R�}���h
	ON_COMMAND(ID_FILE_NEW, CWinApp::OnFileNew)
	ON_COMMAND(ID_FILE_OPEN, CWinApp::OnFileOpen)
	// �W���̈���Z�b�g�A�b�v �R�}���h
	ON_COMMAND(ID_FILE_PRINT_SETUP, CWinApp::OnFilePrintSetup)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CMMLibApp �N���X�̍\�z

CMMLibApp::CMMLibApp()
{
	m_dwIdInst = 0;
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CMMLibApp �I�u�W�F�N�g

CMMLibApp theApp;

/////////////////////////////////////////////////////////////////////////////
// CMMLibApp �N���X�̏�����

BOOL CMMLibApp::InitInstance()
{
	//* �v���O�����̕����N���}�~
	{
		CWnd *pWnd = CWnd::FindWindow(NULL, "Multimedia Library Manager");
		if (pWnd != NULL) {
			pWnd->ShowWindow(SW_RESTORE);
			pWnd->SetForegroundWindow();
			return FALSE;
		}
	}

	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	AfxEnableControlContainer();

	// �W���I�ȏ���������
	// ���������̋@�\���g�p�����A���s�t�@�C���̃T�C�Y��������
	//  ��������Έȉ��̓���̏��������[�`���̒�����s�K�v�Ȃ���
	//  ���폜���Ă��������B

	g_hIcon = LoadIcon(IDR_MAINFRAME);

	g_ImageList.Create(IDB_IMAGE_LIST_SMALL, 16, 1, RGB(255, 255, 255));
	g_ImageList2.Create(IDB_IMAGE_LIST_LARGE, 32, 1, RGB(255, 255, 255));

	// �ݒ肪�ۑ�����鉺�̃��W�X�g�� �L�[��ύX���܂��B
	// ��Ж��܂��͏����ȂǁA�K�؂ȕ������
	// �ύX���Ă��������B
	SetRegistryKey(_T("xxxxxxxxx Electronic Inc.\\Multimedia Library"));

	// ���o�[�W������ini�t�@�C�����烌�W�X�g���փR�s�[
	if (GetProfileInt(g_SectionMainWindow, "Left", -1) == -1)
		CopyInifileToRegistory();

	LoadStdProfileSettings();  // �W���� INI �t�@�C���̃I�v�V���������[�ނ��܂� (MRU ���܂�)

	AfxOleInit();
//	AfxInitRichEdit();

	g_pMMLibDB = new CMMLibDB;
	g_nCFMMLibrary = RegisterClipboardFormat("MMLibrary");

	// �J�[�\���̃��[�h
	m_hCursorArrow = LoadStandardCursor(IDC_ARROW);
	m_hCursorHandOpen = LoadCursor(IDC_HANDOPEN);
	m_hCursorHandClose = LoadCursor(IDC_HANDCLOSE);

	g_DefaultFont.CreatePointFont(90, GetString(IDS_DEFAULT_FONT));

	m_hIconApp = LoadIcon(IDR_MAINFRAME);

	g_ViewFontName = GetProfileString(g_SectionOptions, "ViewFontName", GetString(IDS_DEFAULT_FONT));
	g_nViewFontSize = GetProfileInt(g_SectionOptions, "ViewFontSize", 90);
	g_nFontSize = GetProfileInt(g_SectionOptions, "FontSize", 0);
	g_sImageEditor = GetProfileString(g_SectionOptions, "ImageEditor");

	::GetModuleFileName(m_hInstance, m_ExeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	m_ExeFileName.ReleaseBuffer();

	CFileStatus st;
	m_bOnlineUpdate = CFile::GetStatus(m_ExeFileName.Left(m_ExeFileName.ReverseFind('\\') + 1) + "YMUPDATE.INI", st);

	// �A�v���P�[�V�����p�̃h�L�������g �e���v���[�g��o�^���܂��B�h�L�������g �e���v���[�g
	//  �̓h�L�������g�A�t���[�� �E�B���h�E�ƃr���[���������邽�߂ɋ@�\���܂��B

	CSingleDocTemplate* pDocTemplate;
	pDocTemplate = new CSingleDocTemplate(
		IDR_MAINFRAME,
		RUNTIME_CLASS(CMMLibDoc),
		RUNTIME_CLASS(CMainFrame),       // ���C�� SDI �t���[�� �E�B���h�E
		RUNTIME_CLASS(CMMLibTreeView));
	AddDocTemplate(pDocTemplate);

	m_pPictDocTemplate = new CSingleDocTemplate(
		IDR_PICTFRAME,
		RUNTIME_CLASS(CPictDoc),
		RUNTIME_CLASS(CPictFrame),       // �摜 SDI �t���[�� �E�B���h�E
		RUNTIME_CLASS(CPictView));
	AddDocTemplate(m_pPictDocTemplate);

	m_pFindDocTemplate = new CSingleDocTemplate(
		IDR_FINDFRAME,
		RUNTIME_CLASS(CFindDoc),
		RUNTIME_CLASS(CFindFrame),       // �������� SDI �t���[�� �E�B���h�E
		RUNTIME_CLASS(CFindView));
	AddDocTemplate(m_pFindDocTemplate);

	// DDE�Afile open �ȂǕW���̃V�F�� �R�}���h�̃R�}���h���C������͂��܂��B
	CMyCmdLineInfo cmdInfo;
	ParseCommandLine(cmdInfo);

	// �R�}���h���C���Ńf�B�X�p�b�` �R�}���h���w�肵�܂��B
//	if (!ProcessShellCommand(cmdInfo))
//		return FALSE;
	pDocTemplate->OpenDocumentFile(NULL);

	// ���C�� �E�B���h�E�����������ꂽ�̂ŁA�\���ƍX�V���s���܂��B
	int nShow = GetProfileInt(g_SectionMainWindow, "Show", SW_SHOW);
	if (nShow == SW_SHOWMINIMIZED)
		nShow = SW_SHOW;
	m_pMainWnd->ShowWindow(nShow);
	m_pMainWnd->UpdateWindow();

	OpenDocumentFile("Current");

	InitDDE();

	return TRUE;
}

int CMMLibApp::ExitInstance() 
{
	if (g_pMMLibDB != NULL) {
		g_pMMLibDB->ExitMMLibrary();
		delete g_pMMLibDB;
	}

	::DeleteTempFile(g_TempFile);

	UninitDDE();

	return CWinApp::ExitInstance();
}

static HDDEDATA CALLBACK DdeCallback(
	UINT uType,     // �g�����U�N�V�����^
	UINT uFmt,      // �N���b�v�{�[�h�f�[�^�`��
	HCONV hconv,    // �Θb�̃n���h��
	HSZ hsz1,       // ������̃n���h��
	HSZ hsz2,       // ������̃n���h��
	HDDEDATA hdata, // �O���[�o���������I�u�W�F�N�g�̃n���h��
	DWORD dwData1,  // �g�����U�N�V�����ŗL�f�[�^
	DWORD dwData2   // �g�����U�N�V�����ŗL�f�[�^
	)
{
	DWORD	cbData;
	LPSTR lpStr;
	struct MMLIBDATA {
		struct {
			char	title[64];
			char	comment[64];
		} folder;
		struct {
			char	title[64];
			char	comment[64];
		} data;
		char	text[65536];
		DWORD	size;
		char	dib[0];
	} *pMmlibData;

	switch (uType) {
	case XTYP_CONxxxT:
		if (hsz1 == theApp.m_hTopicText) {
			theApp.m_nDdeMode = DDE_TEXT;
			return (HDDEDATA)TRUE;
		} else if (hsz1 == theApp.m_hTopicData) {
			theApp.m_nDdeMode = DDE_DATA;
			return (HDDEDATA)TRUE;
		}
		break;
	case XTYP_EXECUTE:
		switch (theApp.m_nDdeMode) {
		case DDE_TEXT:
			lpStr = (LPSTR)DdeAccessData(hdata, &cbData);
			{
				CFile file;
				if (file.Open(lpStr, CFile::modeRead | CFile::shareDenyNone)) {
					theApp.m_pDoc->UpdateAllViews(NULL, VIEWHINT_TEXTFILE, &file);
					file.Close();
				}
			}
			DdeUnaccessData(hdata);
			return (HDDEDATA)DDE_FACK;
		case DDE_DATA:
			pMmlibData = (MMLIBDATA *)DdeAccessData(hdata, &cbData);
			HDIB hDIB = GlobalAlloc(GMEM_MOVEABLE, pMmlibData->size);
			LPBYTE pDib = (LPBYTE)GlobalLock(hDIB);
			memcpy(pDib, pMmlibData->dib, pMmlibData->size);
			GlobalUnlock(hDIB);
			((CMMLibDoc *)theApp.m_pDoc)->InsertData(
					pMmlibData->folder.title, pMmlibData->folder.comment,
					pMmlibData->data.title, pMmlibData->data.comment, hDIB, pMmlibData->text);
			GlobalFree(hDIB);
			DdeUnaccessData(hdata);
			return (HDDEDATA)DDE_FACK;
		}
		break;
	}
	return 0;
}

void CMMLibApp::InitDDE()
{
	if (DdeInitialize(&m_dwIdInst,
			(PFNCALLBACK)MakeProcInstance((FARPROC)DdeCallback, hInst),
			APPCMD_FILTERINITS, NULL) != 0)
		return;

	m_hService = DdeCreateStringHandle(m_dwIdInst, "MMLIB", CP_WINANSI);
	m_hTopicText = DdeCreateStringHandle(m_dwIdInst, "text", 0);
	m_hTopicData = DdeCreateStringHandle(m_dwIdInst, "data", 0);

	DdeNameService(m_dwIdInst, m_hService, NULL, DNS_REGISTER);
}

void CMMLibApp::UninitDDE()
{
	if (m_hService)
		DdeFreeStringHandle(m_dwIdInst, m_hService);

	if (m_hTopicText)
		DdeFreeStringHandle(m_dwIdInst, m_hTopicText);

	if (m_hTopicData)
		DdeFreeStringHandle(m_dwIdInst, m_hTopicData);

	if (m_dwIdInst)
		DdeUninitialize(m_dwIdInst);
}

void CMMLibApp::OnOnlineUpdate() 
{
	OnlineUpdateFunc pOnlineUpdate;
	HINSTANCE hModule;

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

	pOnlineUpdate(sOnlineUpdate, m_ExeFileName, m_lpCmdLine, m_hIconApp, "Multimedia\nLibrary", NULL);
}

void CMMLibApp::OnUpdateOnlineUpdate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(m_bOnlineUpdate);
}

void CMMLibApp::OnOnlineManual() 
{
	ShellExecute(NULL, "open", GetString(IDS_HELP_URL), NULL, NULL, SW_SHOWNORMAL);
}

void CMMLibApp::CopyInifileToRegistory()
{
	static char sIniFile[] = "MMLib.ini";
	char sSections[1024];
	char sEntries[1024];

	GetPrivateProfileSectionNames(sSections, sizeof(sSections), sIniFile);
	for (char *pSection = sSections; *pSection != '\0'; pSection += strlen(pSection) + 1) {
		GetPrivateProfileSection(pSection, sEntries, sizeof(sEntries), sIniFile);
		for (char *pEntry = sEntries; *pEntry != '\0'; pEntry += strlen(pEntry) + 1) {
			char *pValue;
			if ((pValue = strchr(pEntry, '=')) != NULL) {
				*pValue++ = '\0';
				if (strspn(pValue, "0123456789-") == strlen(pValue))
					WriteProfileInt(pSection, pEntry, atoi(pValue));
				else
					WriteProfileString(pSection, pEntry, pValue);
			}
		}
	}
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
	virtual void DoDataExchange(CDataExchange* pDX);    // // DDX/DDV �̃T�|�[�g

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
void CMMLibApp::OnAppAbout()
{
	CAboutDlg aboutDlg;
	aboutDlg.DoModal();
}

/////////////////////////////////////////////////////////////////////////////
// CMMLibApp �R�}���h

void CMMLibApp::OnFileNew() 
{
	// TODO: ���̈ʒu�ɃR�}���h �n���h���p�̃R�[�h��ǉ����Ă�������
	CWinApp::OnFileNew();
}

//////////////////////////////////////////////////////////////////////
// CMyCmdLineInfo �N���X
//////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////
// �\�z/����
//////////////////////////////////////////////////////////////////////

CMyCmdLineInfo::CMyCmdLineInfo()
{

}

CMyCmdLineInfo::~CMyCmdLineInfo()
{

}

void CMyCmdLineInfo::ParseParam(LPCTSTR lpszParam, BOOL bFlag, BOOL bLast)
{
	if (_stricmp(lpszParam, "offline") == 0)
		g_bParamOffline = TRUE;
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
