// vjb30040.cpp : �A�v���P�[�V�����p�N���X�̒�`���s���܂��B
//

#include "stdafx.h"
#include "vjb30040.h"
#include "vjb30040Dlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App

BEGIN_MESSAGE_MAP(CVjb30040App, CWinApp)
	//{{AFX_MSG_MAP(CVjb30040App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App �N���X�̍\�z

CVjb30040App::CVjb30040App()
{
	// TODO: ���̈ʒu�ɍ\�z�p�̃R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}

/////////////////////////////////////////////////////////////////////////////
// �B��� CVjb30040App �I�u�W�F�N�g

CVjb30040App theApp;

CString g_sPathFPD;
CString g_sPathMOD;
CString g_sInputDir;
CString g_sDatDir;
CString g_sLogDir;
CString g_BatchServerFile;

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App �N���X�̏�����

BOOL CVjb30040App::InitInstance()
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

	ReadIniFile();				// ini�t�@�C���ǂݍ���

	CVjb30040Dlg dlg;
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
void CVjb30040App::ReadIniFile()
{	
	// FPD�̃p�X�����擾
	g_sPathFPD = GetIniData("Device", "FPD", "A:\\");

	// MOD�̃p�X�����擾
	g_sPathMOD = GetIniData("Device", "MOD", "M:\\");

	// ���̓f�B���N�g�������擾
	g_sInputDir = GetIniData("InputFile", "InputDir", "dummy");

	// DAT�p���̓f�B���N�g�������擾
	g_sDatDir = GetIniData("InputFile", "DatDir", "dummy");

	// ���O�t�@�C���f�B���N�g�����擾
	g_sLogDir = GetIniData("LogFile", "Dir", "");

	// INI�t�@�C�����o�b�`�T�[�o�w��t�@�C�����擾
	g_BatchServerFile = GetIniData("BatchServer", "Server", "");
}
