// LLog.cpp : �A�v���P�[�V�����̃N���X������`���܂��B
//

#include "stdafx.h"
#include "LLog.h"
#include "LLogDlg.h"
#include "atlimage.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CLLogApp

BEGIN_MESSAGE_MAP(CLLogApp, CWinApp)
	ON_COMMAND(ID_HELP, &CWinApp::OnHelp)
END_MESSAGE_MAP()


// CLLogApp �R���X�g���N�V����

CLLogApp::CLLogApp()
{
	// TODO: ���̈ʒu�ɍ\�z�p�R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}


// �B��� CLLogApp �I�u�W�F�N�g�ł��B

CLLogApp theApp;


// CLLogApp ������

BOOL CLLogApp::InitInstance()
{
	//* �v���O�����̕����N���}�~
	{
		CWnd *pWnd = CWnd::FindWindow("#32770", "LLog");
		if (pWnd != NULL) {
			pWnd->ShowWindow(SW_RESTORE);
			pWnd->SetForegroundWindow();
			return FALSE;
		}
	}

	// �A�v���P�[�V���� �}�j�t�F�X�g�� visual �X�^�C����L���ɂ��邽�߂ɁA
	// ComCtl32.dll Version 6 �ȍ~�̎g�p���w�肷��ꍇ�́A
	// Windows XP �� InitCommonControlsEx() ���K�v�ł��B�����Ȃ���΁A�E�B���h�E�쐬�͂��ׂĎ��s���܂��B
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	// �A�v���P�[�V�����Ŏg�p���邷�ׂẴR���� �R���g���[�� �N���X���܂߂�ɂ́A
	// �����ݒ肵�܂��B
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();

	::AfxEnableControlContainer();

	//AfxInitRichEdit2();

	// �W��������
	// �����̋@�\���g�킸�ɍŏI�I�Ȏ��s�\�t�@�C����
	// �T�C�Y���k���������ꍇ�́A�ȉ�����s�v�ȏ�����
	// ���[�`�����폜���Ă��������B
	// �ݒ肪�i�[����Ă��郌�W�X�g�� �L�[��ύX���܂��B
	// TODO: ��Ж��܂��͑g�D���Ȃǂ̓K�؂ȕ������
	// ���̕������ύX���Ă��������B
	//SetRegistryKey(_T("�A�v���P�[�V���� �E�B�U�[�h�Ő������ꂽ���[�J�� �A�v���P�[�V����"));

	CoInitialize(NULL);

	ULONG_PTR m_Token;
	Gdiplus::GdiplusStartupInput m_StartupInput;
	Gdiplus::GdiplusStartup(&m_Token, &m_StartupInput, NULL);

	CLLogDlg dlg;
	m_pMainWnd = &dlg;
	dlg.DoModal();

	Gdiplus::GdiplusShutdown(m_Token);

	::CoUninitialize();

	return FALSE;
}

BOOL CLLogApp::PreTranslateMessage(MSG* pMsg)
{
	// ESC�ŕ���̂�h�~
	if (pMsg->message == WM_KEYDOWN && pMsg->wParam == VK_ESCAPE)
		return TRUE;

	return CWinApp::PreTranslateMessage(pMsg);
}
