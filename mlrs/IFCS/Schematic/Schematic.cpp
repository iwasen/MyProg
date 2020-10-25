//*****************************************************************************************************
//  1. �t�@�C����
//		Schematic.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�^�@�\�n���}�^�@�\�����}�\�����C���N���X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "SchematicDlg.h"
#include "SchematicFrm.h"
#include "CGMenuDlg.h"
#include "MenuCS01.h"
#include "MenuFS01.h"
#include "MenuFS02.h"
#include "MenuFS03.h"
#include "MenuFS04.h"
#include "MenuFS05.h"
#include "MenuFS06.h"
#include "MenuFS07.h"
#include "MenuFS08.h"
#include "MenuFS09.h"
#include "MenuFS10.h"
#include "MenuFS11.h"
#include "MenuFS12.h"
#include "MenuFS13.h"
#include "MenuFS14.h"
#include "MenuFS15.h"
#include "MenuFS16.h"
#include "MenuFS17.h"
#include "MenuFS18.h"
#include "MenuFS19.h"
#include "MenuFE01.h"
#include "MenuFE02.h"
#include "MenuFE03.h"
#include "MenuFE04.h"
#include "MenuFE05.h"
#include "MenuFE06.h"
#include "MenuFE07.h"
#include "shlwapi.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CSchematicApp

BEGIN_MESSAGE_MAP(CSchematicApp, CWinApp)
	ON_COMMAND(ID_HELP, &CWinApp::OnHelp)
END_MESSAGE_MAP()


// CSchematicApp �R���X�g���N�V����

//*****************************************************************************************************
//  1. �֐���
//		CSchematicApp::CSchematicApp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CSchematicApp::CSchematicApp()
{
	// TODO: ���̈ʒu�ɍ\�z�p�R�[�h��ǉ����Ă��������B
	// ������ InitInstance ���̏d�v�ȏ��������������ׂċL�q���Ă��������B
}


// �B��� CSchematicApp �I�u�W�F�N�g�ł��B

CSchematicApp theApp;


// CSchematicApp ������

//*****************************************************************************************************
//  1. �֐���
//		CSchematicApp::InitInstance
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�C���X�^���X����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CSchematicApp::InitInstance()
{
	INITCOMMONCONTROLSEX InitCtrls;
	InitCtrls.dwSize = sizeof(InitCtrls);
	InitCtrls.dwICC = ICC_WIN95_CLASSES;
	InitCommonControlsEx(&InitCtrls);

	CWinApp::InitInstance();

	AfxEnableControlContainer();

	// �N���p�����[�^���
	int nModeParam = 0;
	for (int i = 1; i < __argc; i++) {
		if (_strnicmp(__argv[i], "P=", 2) == 0)
			nModeParam = atoi(__argv[i] + 2);
		else if (_strnicmp(__argv[i], "F=", 2) == 0)
			CSchematic::m_sFlashDir = __argv[i] + 2;
	}

	// Flash�̃p�X�w�肪�������"..\flash"�Ƃ���
	if (CSchematic::m_sFlashDir.IsEmpty()) {
		char path[MAX_PATH];
		strcpy_s(path, m_pszHelpFilePath);
		PathRemoveFileSpec(path);
		CSchematic::m_sFlashDir.Format("%s\\..\\flash", path);
	}

	switch (nModeParam) {
	case 1:
		CSchematic::OpenSchematicWindow("CS01");
		break;
	case 2:
		(new CCGMenuDlg)->DoModeless();
		break;
	default:
		(new CSchematicDlg)->DoModeless();
		break;
	}

	return TRUE;
}

// �ڑ��n���}�^�@�\�n���}�^�@�\�����}�f�[�^
SSchematicWindow CSchematic::m_aSchematicWindow[] = {
	{"CS01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuCS01), "ICS_01.swf", "�ڑ��n���}"},
	{"FS01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS01), "IFS_01.swf", "�d���n"},
	{"FS02", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS02), "IFS_02.swf", "�C�[�T�[�l�b�g�o�X�n"},
	{"FS03", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS03), "IFS_03.swf", "1553�o�X�n"},
	{"FS04", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS04), "IFS_04.swf", "�˓��\����n��-�˓�������"},
	{"FS05", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS05), "IFS_05.swf", "���ˋ@�����|�ʐM������"},
	{"FS06", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS06), "IFS_06.swf", "�e�򐧌��|LP/C�i1�j"},
	{"FS07", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS07), "IFS_07.swf", "���[�R�l�N�^�i1�j"},
	{"FS08", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS08), "IFS_08.swf", "�e�򐧌��|LP/C�i2�j"},
	{"FS09", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS09), "IFS_09.swf", "���[�R�l�N�^�i2�j"},
	{"FS10", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS10), "IFS_10.swf", "W330�AW331�n"},
	{"FS11", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS11), "IFS_11.swf", "�G���R�[�_�[�|�ʒu�p���W���|GPS�A���e�i"},
	{"FS12", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS12), "IFS_12.swf", "�d�͐����|���ˋ@�����|�˓��\����"},
	{"FS13", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS13), "IFS_13.swf", "���ˋ@�쓮���u�i�@�\�X�P�}�e�B�b�N�j�i1�j"},
	{"FS14", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS14), "IFS_14.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�e�B�b�N�j�i1�j"},
	{"FS14_1", RUNTIME_CLASS(CSchematicView), NULL, "IFS_14_matrix.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�e�B�b�N�j�i1�j"},
	{"FS14_2", RUNTIME_CLASS(CSchematicView), NULL, "IFS_14_AtoZ.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�e�B�b�N�j�i1�j"},
	{"FS15", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS15), "IFS_15.swf", "�g���x�����b�N�i�@�\�X�P�}�e�B�b�N�j"},
	{"FS16", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS16), "IFS_16.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�e�B�b�N�j�i2�j"},
	{"FS17", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS17), "IFS_17.swf", "�u�[�����[�^�i�@�\�X�P�}�e�B�b�N�j"},
	{"FS18", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS18), "IFS_18.swf", "�z�C�X�g���[�^�i�@�\�X�P�}�e�B�b�N�j"},
	{"FS19", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS19), "IFS_19.swf", "���ˋ@�쓮���u�i�@�\�X�P�}�e�B�b�N�j�i2�j"},
	{"FE01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE01), "FE_01.swf", "�k�o�^�b�Œ葕�u"},
	{"FE02", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE02), "FE_02.swf", "�g���x�����b�N���u"},
	{"FE03", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE03), "FE_03.swf", "�z�C�X�g�`������"},
	{"FE04", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE04), "FE_04.swf", "�u�[�����u"},
	{"FE05", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE05), "FE_05.swf", "�����Օ����u"},
	{"FE06", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE06), "FE_06.swf", "���ˋ@���������u"},
	{"FE07", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFE07), "FE_07.swf", "�ϑ������@"}
};

// Flash�t�@�C���f�B���N�g��
CString CSchematic::m_sFlashDir;

//*****************************************************************************************************
//  1. �֐���
//		CSchematic::CSchematic
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�R���X�g���N�^
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
CSchematic::CSchematic()
{
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematic::OpenSchematicWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�^�@�\�n���}�^�@�\�����}�I�[�v������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCTSTR		pSchematicCode		[I] �}�\�R�[�h
//		int			nActionCode			[I] �A�N�V�����R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematic::OpenSchematicWindow(LPCTSTR pSchematicCode, int nActionCode)
{
	SSchematicWindow *pSchematicWindow = GetSchematicWindowData(pSchematicCode);
	if (pSchematicWindow != NULL)
		OpenFrameWindow(pSchematicWindow, nActionCode);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematic::GetSchematicWindowData
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�ڑ��n���}�^�@�\�n���}�^�@�\�����}�E�B���h�E�f�[�^�擾����
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		LPCTSTR		pSchematicCode		[I] �}�\�R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		SSchematicWindow*	�}�\�E�B���h�E�f�[�^�ւ̃|�C���^
//*****************************************************************************************************
SSchematicWindow *CSchematic::GetSchematicWindowData(LPCTSTR pSchematicCode)
{
	// �w�肳�ꂽ�}�\�R�[�h�̃f�[�^����������
	for (int i = 0; i < sizeof(m_aSchematicWindow) / sizeof(SSchematicWindow); i++) {
		if (pSchematicCode == m_aSchematicWindow[i].sSchematicCode)
			return &m_aSchematicWindow[i];
	}

	return NULL;
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematic::RenzokuFE
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�@�\�����}�A���\������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematic::RenzokuFE()
{
	// �A���\������}�\�R�[�h
	static LPCTSTR tSchematicCode[] = {"FE07", "FE06", "FE05", "FE01", "FE02", "FE03", "FE04"};

	CSchematicFrame *pFrame = new CSchematicFrame;

	// �}�\�f�[�^���t���[���E�B���h�E�̐}�\���X�g�ɐݒ肷��
	for (int i = 0; i < sizeof(tSchematicCode) / sizeof(LPCTSTR); i++)
		pFrame->m_lSchematicWindow.AddTail(GetSchematicWindowData(tSchematicCode[i]));

	// �t���[���E�B���h�E�\��
	pFrame->CreateFrameWindow(0);
}

//*****************************************************************************************************
//  1. �֐���
//		CSchematic::OpenFrameWindow
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�t���[���E�B���h�E�I�[�v������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^
//		SSchematicWindow	*pSchematicClass	[I] �}�\�E�B���h�E�f�[�^�ւ̃|�C���^
//		int					nActionCode			[I] �A�N�V�����R�[�h
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		����
//*****************************************************************************************************
void CSchematic::OpenFrameWindow(SSchematicWindow *pSchematicClass, int nActionCode)
{
	CSchematicFrame *pFrame = new CSchematicFrame;

	// �w�肳�ꂽ�}�\�f�[�^���t���[���E�B���h�E�̐}�\���X�g�ɐݒ肷��
	pFrame->m_lSchematicWindow.AddTail(pSchematicClass);

	// �t���[���E�B���h�E�\��
	pFrame->CreateFrameWindow(nActionCode);
}
