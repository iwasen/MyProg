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
#include "MenuFS20.h"
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
	{"CS01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuCS01), "CS_01.swf", "�ڑ��n���}"},
	{"FS01", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS01), "FS_01.swf", "�@�\�n���}�i�T�v�j"},
	{"FS02", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS02), "FS_02.swf", "�d���n�i�����}�j"},
	{"FS03", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS03), "FS_03.swf", "�z�C�X�g���[�^�n�i�����}�j"},
	{"FS04", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS04), "FS_04.swf", "�u�[�����[�^�n�i�����}�j"},
	{"FS05", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS05), "FS_05.swf", "���ˋ@���쓮���u�i�����}�j"},
	{"FS06", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS06), "FS_06.swf", "���䉉�Z��|���{��ϊ���|�ˌ������p�l��"},
	{"FS07", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS07), "FS_07.swf", "���䉉�Z��|�d�������|���ː����"},
	{"FS08", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS08), "FS_08.swf", "���Ȉʒu�E�p���W�葕�u�|���ː����|�d�͕��z��"},
	{"FS09", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS09), "FS_09.swf", "���䉉�Z��|���ː����|�k�o�^�b"},
	{"FS10", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS10), "FS_10.swf", "���䉉�Z��|�e���A�ڊ�"},
	{"FS11", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS11), "FS_11.swf", "���ː����|�e���A�ڊ�"},
	{"FS12", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS12), "FS_12.swf", "�k�o�^�b�|�e���A�ڊ�"},
	{"FS13", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS13), "FS_13.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�`�b�N�j"},
	{"FS13_1", RUNTIME_CLASS(CSchematicView), NULL, "FS_13_matrix.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�`�b�N�j"},
	{"FS13_2", RUNTIME_CLASS(CSchematicView), NULL, "FS_13_AtoZ.swf", "���~�b�g�X�C�b�`�i�@�\�X�P�}�`�b�N�j"},
	{"FS14", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS14), "FS_14.swf", "���m�X�C�b�`�i�@�\�X�P�}�`�b�N�j"},
	{"FS15", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS15), "FS_15.swf", "�g���x�����b�N�i�@�\�X�P�}�`�b�N�j"},
	{"FS16", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS16), "FS_16.swf", "���ʁE����ʒu���o��y�ы쓮�T�[�{���[�^�i�@�\�X�P�}�`�b�N�j"},
	{"FS17", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS17), "FS_17.swf", "���ʁE��������o���u�i�@�\�X�P�}�`�b�N�j"},
	{"FS18", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS18), "FS_18.swf", "���ˋ@�쓮���u�i�@�\�X�P�}�`�b�N�j"},
	{"FS19", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS19), "FS_19.swf", "�u�[�����[�^�i�@�\�X�P�}�`�b�N�j"},
	{"FS20", RUNTIME_CLASS(CSchematicView), RUNTIME_CLASS(CMenuFS20), "FS_20.swf", "�z�C�X�g���[�^�i�@�\�X�P�}�`�b�N�j"},
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
