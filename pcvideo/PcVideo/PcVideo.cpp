// PcVideo.cpp : �A�v���P�[�V�����p�N���X�̋@�\��`���s���܂��B
//

#include "stdafx.h"
#include "PcVideo.h"

#include "MainFrm.h"
#include "pcvcomm.h"
#include "voice.h"
#include "UpdateAp.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

extern "C" {
extern BOOL APIENTRY CheckLicense(LPCTSTR pAppName, HICON hIcon, LPCTSTR pRegistryKey, int nAppID, BOOL bNoDlgTrial);
}

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp

BEGIN_MESSAGE_MAP(CPcVideoApp, CWinApp)
	//{{AFX_MSG_MAP(CPcVideoApp)
	ON_COMMAND(ID_APP_ABOUT, OnAppAbout)
	ON_COMMAND(ID_HELP_USING, OnHelpUsing)
	ON_COMMAND(IDM_HELP_CONTENTS, OnHelpContents)
	ON_COMMAND(IDM_HELP_KEY, OnHelpKey)
	ON_COMMAND(IDM_ONLINE_UPDATE, OnOnlineUpdate)
	ON_UPDATE_COMMAND_UI(IDM_ONLINE_UPDATE, OnUpdateOnlineUpdate)
	//}}AFX_MSG_MAP
	// �W���̃t�@�C����{�h�L�������g �R�}���h
	ON_COMMAND(ID_HELP, OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp �N���X�̍\�z

CPcVideoApp::CPcVideoApp()
{
	// TODO: ���̈ʒu�ɍ\�z�p�R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CPcVideoApp �I�u�W�F�N�g

CPcVideoApp theApp;

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp �N���X�̏�����

BOOL CPcVideoApp::InitInstance()
{
	LPHOSTENT hp;
	int	channelNo = 0;
	BOOL	bNoCapture = FALSE;
	BOOL	bStartChat = FALSE;
	BOOL	bStartVoice = FALSE;
	char	cmdLine[128], *p;

	AfxEnableControlContainer();

	// �W���I�ȏ���������
	// ���������̋@�\���g�p�����A���s�t�@�C���̃T�C�Y��������
	// ��������Έȉ��̓���̏��������[�`���̒�����s�K�v�Ȃ���
	// ���폜���Ă��������B

#ifdef _AFXDLL
	Enable3dControls();		// ���L DLL �̒��� MFC ���g�p����ꍇ�ɂ͂������Ăяo���Ă��������B
#else
	Enable3dControlsStatic();	// MFC �ƐÓI�Ƀ����N���Ă���ꍇ�ɂ͂������Ăяo���Ă��������B
#endif

	if (!CheckLicense("PC-VIDEO", LoadIcon(IDR_MAINFRAME), "PC-VIDEO", 0x01, FALSE))
		return FALSE;

	// �ݒ肪�ۑ�����鉺�̃��W�X�g�� �L�[��ύX���܂��B
	// TODO: ���̕�������A��Ж��܂��͏����ȂǓK�؂Ȃ��̂�
	// �ύX���Ă��������B
//	SetRegistryKey(_T("Local AppWizard-Generated Applications"));

	LoadStdProfileSettings(0);  // �W���� INI �t�@�C���̃I�v�V���������[�ނ��܂� (MRU ���܂�)


	// �R�}���h�p�����[�^���
	lstrcpy(cmdLine, m_lpCmdLine);
	p = strtok(cmdLine, " ");
	while (p != NULL) {
		if (*p == '/') {
			AnsiUpper(p);
			if (strncmp(p, "/CHNO=", 6) == 0)
				channelNo = atoi(p + 6);
			else if (strcmp(p, "/CHAT") == 0)
				bStartChat = TRUE;
			else if (strcmp(p, "/VOICE") == 0)
				bStartVoice = TRUE;
			else if (strcmp(p, "/NOCAPTURE") == 0)
				bNoCapture = TRUE;
			else if (strcmp(p, "/ADMIN") == 0)
				bAdmin = TRUE;
			else if (strncmp(p, "/CAPTURE=", 9) == 0)
				nCaptureDriver = atoi(p + 9);
			else if (strcmp(p, "/DEBUG") == 0)
				bDebug = TRUE;
		}

		p = strtok(NULL, " ");
	}

	ReadIniFile();		// PCVIDEO.INI �ǂݍ���
	m_bReadIniFile = TRUE;

	if (!InitSocket())	// �\�P�b�g������
		return FALSE;

	// ���O���Z�b�g����Ă��Ȃ�������z�X�g�����Z�b�g
	if (iniData.settings.userName[0] == '\0') {
		if ((p = strchr(localHostName, '.')) != NULL)
			*p = '\0';
		strcpy(iniData.settings.userName, localHostName);
	}

	if (!InitVideoDecompress())	// �r�f�I���k������
		return FALSE;

	hDD = DrawDibOpen();		// �`�揈���I�[�v��

	InitResources();		// ���\�[�X������
	InitializePalette();		// �p���b�g������

	::GetModuleFileName(m_hInstance, m_ExeFileName.GetBuffer(MAX_PATH), MAX_PATH);
	m_ExeFileName.ReleaseBuffer();

	CFileStatus st;
	bOnlineUpdate = CFile::GetStatus(m_ExeFileName.Left(m_ExeFileName.ReverseFind('\\') + 1) + "YMUPDATE.INI", st);

	// �E�B���h�E�N���X�o�^
//	videoWndClassName =
//			AfxRegisterWndClass(CS_BYTEALIGNWINDOW | CS_DBLCLKS,
//			LoadStandardCursor(IDC_ARROW), 0, 0);
	cameraFrameWndClassName = AfxRegisterWndClass(0, 0, 0, 0);

	// ���C���E�B���h�E�쐬
	m_pMainWnd = pMainWnd = new CMainFrame();
	m_pMainWnd->ShowWindow(m_nCmdShow);
	m_pMainWnd->UpdateWindow();


	// �L���v�`���E�B���h�E������
	if (!bNoCapture && iniData.settings.bVideoCapture && InitializeCapture()) {
		OpenCaptureWindow();
	}

	// �{�C�X�E�B���h�E������
	if (!InitializeVoice()) {
		bStartVoice = FALSE;
		bVoice = FALSE;
	}

	// �R�}���h�p�����[�^�Ŏw�肵���z�X�g�ɐڑ�
	lstrcpy(cmdLine, m_lpCmdLine);
	p = strtok(cmdLine, " ");
	while (p != NULL) {
		if (*p != '/') {
			if ((hp = gethostbyname(p)) != NULL) {
				ConxxxtHost(p, channelNo);
			}
		}

		p = strtok(NULL, " ");
	}

	// �R�}���h�p�����[�^�� "/CHAT" ������΃`���b�g�E�B���h�E�\��
	if (bStartChat)
		OpenChatWindow();

	// �R�}���h�p�����[�^�� "/VOICE" ������΃{�C�X�E�B���h�E�\��
	if (bStartVoice)
		OpenVoiceWindow();

	// ���C���E�B���h�E�̃^�C�}�N��
	pMainWnd->SetTimer(IDT_MONITOR, 1000, NULL);

	return TRUE;
}


/////////////////////////////////////////////////////////////////////////////
// �A�v���P�[�V�����̃o�[�W�������Ŏg���� CAboutDlg �_�C�A���O

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// �_�C�A���O �f�[�^
	//{{AFX_DATA(CAboutDlg)
	enum { IDD = IDD_ABOUTBOX };
	//}}AFX_DATA

	// ClassWizard ���z�֐��̃I�[�o�[���C�h�𐶐����܂��B
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
	//}}AFX_MSG_MAP
END_MESSAGE_MAP()

// �_�C�A���O�����s���邽�߂̃A�v���P�[�V���� �R�}���h
void CPcVideoApp::OnAppAbout()
{
	CAboutDlg aboutDlg;
	aboutDlg.DoModal();
}

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp ���b�Z�[�W �n���h��

int CPcVideoApp::ExitInstance() 
{
	PUSER	pUser;
	PSEND	pSend;
	HEADER	header;

	CloseCaptureWindow();

	for (pSend = pTopSend; pSend != NULL; pSend = pSend->pChain) {
		if (!bRestart) {
			header.flag = FLAG_DISCONxxxT;
			header.id = localIPAddress;
			SendData(&pSend->sock, &header, sizeof(header));
		}
	}

	while ((pUser = GetUser(0)) != NULL)
		DeleteUser(pUser);

	ExitSocket();

	if (hDD != 0) {
		DrawDibClose(hDD);	// �`�揈���N���[�Y
		hDD = 0;
	}

	if (m_bReadIniFile) {
		WriteIniFile();		// PCVIDEO.INI ��������
		m_bReadIniFile = FALSE;
	}

	ExitVideoCompress();
	ExitVideoDecompress();

	ExitAudioCompress();

	// �ċN������
	if (bRestart) {
		char	command[128];
		char	*p;

		lstrcpy(command, m_pszHelpFilePath);
		if ((p = strrchr(command, '\\')) != NULL)
			strcpy(p, "\\PCVSTART.EXE");
		else
			strcat(command, "PCVSTART.EXE");

		strcat(command, " ");
		strcat(command, m_pszExeName);

		if (m_lpCmdLine[0] != '\0')
			strcat(command, " ");
		strcat(command, m_lpCmdLine);

		if (downLoadFile.GetLength() != 0) {
			strcat(command, " ");
			strcat(command, "/DL=");
			strcat(command, downLoadFile);
		}

		STARTUPINFO	siStartupInfo;
		PROCESS_INFORMATION	piProcInfo;
		memset(&siStartupInfo, 0, sizeof(siStartupInfo));
		siStartupInfo.cb = sizeof(siStartupInfo);
		CreateProcess(NULL, command, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, ".", &siStartupInfo, &piProcInfo);
	}

	return CWinApp::ExitInstance();
}

////////////////////////////////////////
//	���\�[�X����������
////////////////////////////////////////
void	CPcVideoApp::InitResources()
{
	static	LOGBRUSH brush;

	// �V�X�e���t�H���g�i�Œ蕝�j�擾
	systemFont.CreateStockObject(SYSTEM_FIXED_FONT);

	// �l�r�S�V�b�N�t�H���g�擾
	infoFont.CreateFont(-11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
					0x04|FIXED_PITCH, "MS Sans Serif");

	hSpaceCursor = LoadCursor(IDC_MOVE);	// �J�[�\���擾

	// �u���V�̍쐬
	brush.lbStyle = BS_HATCHED;
	brush.lbColor = DIB_RGB_COLORS;
	brush.lbHatch = HS_BDIAGONAL;
	cameraBrush.CreateBrushIndirect(&brush);

	// �y���̍쐬
	cameraPen.CreatePen(PS_SOLID, 2, RGB(255, 0, 0));

	//�A�C�R���̃��[�h
	hIconApp = LoadIcon(IDR_MAINFRAME);
	hIconVideo = LoadIcon(IDI_VIDEO);
	hIconMic = LoadIcon(IDI_MICROPHONE);
	hIconMicOff = LoadIcon(IDI_MICROPHONE_OFF);
	hIconSpeaker = LoadIcon(IDI_SPEAKER);
	hIconSpeakerOff = LoadIcon(IDI_SPEAKER_OFF);
	hIconChat = LoadIcon(IDI_CHAT);
}

////////////////////////////////////////
//	�p���b�g����������
////////////////////////////////////////
void	CPcVideoApp::InitializePalette()
{
	int	i;
	BITMAPINFO	*bip;

	palette.palVersion = 0x300;
	palette.palNumEntries = 256;

	for (i = 0; i < 256; i++) {
		palette.palPalEntry[i].peRed = i;
		palette.palPalEntry[i].peGreen = i;
		palette.palPalEntry[i].peBlue = i;
	}

	bip = (BITMAPINFO *)bitmapInfo;
	bip->bmiHeader.biSize = sizeof(BITMAPINFOHEADER);
	bip->bmiHeader.biPlanes = 1;
	bip->bmiHeader.biCompression = BI_RGB;
	for (i = 0; i < 256; i++) {
		bip->bmiColors[i].rgbRed = i;
		bip->bmiColors[i].rgbGreen = i;
		bip->bmiColors[i].rgbBlue = i;
	}
}

////////////////////////////////////////
//	�w���v�\���iF1�L�[�j
////////////////////////////////////////
void CPcVideoApp::OnHelp()
{
	CWnd *pWnd = pMainWnd->GetActiveWindow();
	if (pWnd != NULL)
		pWnd->OnHelp();
}

void CPcVideoApp::OnHelpUsing() 
{
	CWinApp::OnHelpUsing();
}

void CPcVideoApp::OnHelpContents() 
{
	WinHelp(0, HELP_CONTENTS);
}

void CPcVideoApp::OnHelpKey() 
{
	WinHelp((DWORD)(LPSTR)"", HELP_PARTIALKEY);
}

void CPcVideoApp::OnOnlineUpdate() 
{
	static char registry[] = "SoftWare\\xxxxxxxxx Electronic Inc.\\PC-VIDEO";
	OnlineUpdateFunc pOnlineUpdate;
	HINSTANCE hModule;

	if ((hModule = LoadLibrary("YMUPDATE.DLL")) == NULL) {
		AfxMessageBox(IDS_NO_YMUPDATE);
		return;
	}

	if ((pOnlineUpdate = (OnlineUpdateFunc)GetProcAddress(hModule, "OnlineUpdate")) == NULL) {
		AfxMessageBox(IDS_NO_ONLINEUPDATE);
		::FreeLibrary(hModule);
		return;
	}

	pOnlineUpdate(GetString(IDS_ONLINE_UPDATE), m_ExeFileName, m_lpCmdLine, hIconApp, "PC-VIDEO", registry);
}

void CPcVideoApp::OnUpdateOnlineUpdate(CCmdUI* pCmdUI) 
{
	pCmdUI->Enable(bOnlineUpdate);
}
