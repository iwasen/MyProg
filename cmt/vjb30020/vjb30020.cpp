// vjb30020.cpp : �A�v���P�[�V�����p�N���X�̒�`���s���܂��B
//

#include "stdafx.h"
#include "vjb30020.h"
#include "vjb30020Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App

BEGIN_MESSAGE_MAP(CVjb30020App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30020App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App �N���X�̍\�z

CVjb30020App::CVjb30020App()
{
	// TODO: ���̈ʒu�ɍ\�z�p�̃R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CVjb30020App �I�u�W�F�N�g

CVjb30020App theApp;

CString g_sQueueDir;
int g_nTimer;

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App �N���X�̏�����

//
//	�@�\	�F	�C���X�^���X�J�n����
//
//	����	�F	����
//
//	���A�l	�F	FALSE - �v���O�������I������
//
//	�@�\�����F	�v���O�����{�̂̏����B
//
//	���l	�F	�v���O�����J�n���Ƀt���[�����[�N���Ă΂��B
//
BOOL CVjb30020App::InitInstance()
{
	AfxEnableControlContainer();

	// �W���I�ȏ���������
	// ���������̋@�\���g�p�����A���s�t�@�C���̃T�C�Y��������������
	//  ��Έȉ��̓���̏��������[�`���̒�����s�K�v�Ȃ��̂��폜����
	//  ���������B

#ifdef _AFXDLL
	Enable3dControls();			// ���L DLL ���� MFC ���g���ꍇ�͂������R�[�����Ă��������B
#else
	Enable3dControlsStatic();	// MFC �ƐÓI�Ƀ����N����ꍇ�͂������R�[�����Ă��������B
#endif

	//* �v���O�����̕����N���}�~
	m_hMutex = ::CreateMutex(NULL, FALSE, "MUTEX_vjb30020");
	if (::GetLastError() == ERROR_ALREADY_EXISTS)
		return FALSE;

	ReadIniFile();				// ini�t�@�C���ǂݍ���

	CVjb30020Dlg dlg;
	m_pMainWnd = &dlg;
	int nResponse = dlg.DoModal();
	if (nResponse == IDOK)
	{
		// TODO: �_�C�A���O�� <OK> �ŏ����ꂽ���̃R�[�h��
		//       �L�q���Ă��������B
	}
	else if (nResponse == IDCANCEL)
	{
		// TODO: �_�C�A���O�� <��ݾ�> �ŏ����ꂽ���̃R�[�h��
		//       �L�q���Ă��������B
	}

	// �_�C�A���O�������Ă���A�v���P�[�V�����̃��b�Z�[�W �|���v���J�n������́A
	// �A�v���P�[�V�������I�����邽�߂� FALSE ��Ԃ��Ă��������B
	return FALSE;
}

//
//	�@�\	�F	ini�t�@�C���ǂݍ��ݏ���
//
//	����	�F	����
//
//	���A�l	�F	����
//
//	�@�\�����F	ini�t�@�C�����K�v�ȏ���ǂݍ��ށB
//
//	���l	�F	����
//
void CVjb30020App::ReadIniFile()
{	
	// INI�t�@�C�����L���[�t�@�C���f�B���N�g���擾
	g_sQueueDir = GetIniData("Queue", "Dir", "");

	// �L���[�t�@�C���Ď��^�C�}�[�ݒ�
	g_nTimer = GetIniData("QUEUE", "Timer", 0);
}
