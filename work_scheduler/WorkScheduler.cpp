//*****************************************************************************************************
//  1. �t�@�C����
//		WorkScheduler.cpp
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�A�v���P�[�V�����p�N���X�̒�`���s���܂��B
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#include "stdafx.h"
#include "WorkScheduler.h"
#include "WorkSchedulerDlg.h"
#include "ManualEditDocument.h"
#include "ManualEditChildWnd.h"
#include "ManualEditChildWnd1.h"
#include "ManualEditView1.h"
#include "ManualEditView2.h"
#include "ManualEditView3.h"
#include "System.h"
#include "DataManager.h"
#include "Const.h"
#include "LoginDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

BEGIN_MESSAGE_MAP(CWorkSchedulerApp, CWinApp)
	//{{AFX_MSG_MAP(CWorkSchedulerApp)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ}�b�s���O�p�̃}�N����ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	ON_COMMAND(ID_HELP, CWinApp::OnHelp)
END_MESSAGE_MAP()

// �B��� CWorkSchedulerApp �I�u�W�F�N�g
CWorkSchedulerApp theApp;

CConst *g_pConst;
CSystem *g_pSystem;
CDataManager *g_pDataManager;

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerApp::InitInstance
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�C���X�^���X����������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		FALSE
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		��ƕҐ��_�C�A���O��\������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
BOOL CWorkSchedulerApp::InitInstance()
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

	::CoInitialize(NULL);

	// �V�X�e���Ǘ��N���X����
	g_pSystem = new CSystem;
	if (!g_pSystem->Initialize())
		return FALSE;

	// �萔�N���X����
	g_pConst = new CConst;

	// �f�[�^�Ǘ��N���X����
	g_pDataManager = new CDataManager;

	// ���O�C������
	{
		CLoginDlg cLoginDlg;
		if (cLoginDlg.DoModal() == IDCANCEL)
			return FALSE;
	}

	// �蓮�Ґ��E�B���h�E�p�h�L�������g�e���v���[�g�쐬
	m_pManualDocTemplate1 = new CMultiDocTemplate(
		IDR_MANUAL_FRAME,
		RUNTIME_CLASS(CManualEditDocument),
		RUNTIME_CLASS(CManualEditChildWnd1),
		RUNTIME_CLASS(CManualEditView1));
	AddDocTemplate(m_pManualDocTemplate1);
	DestroyMenu(m_pManualDocTemplate1->m_hMenuShared);
	m_pManualDocTemplate1->m_hMenuShared = NULL;

	m_pManualDocTemplate2 = new CMultiDocTemplate(
		IDR_MANUAL_FRAME,
		RUNTIME_CLASS(CManualEditDocument),
		RUNTIME_CLASS(CManualEditChildWnd),
		RUNTIME_CLASS(CManualEditView2));
	AddDocTemplate(m_pManualDocTemplate2);
	DestroyMenu(m_pManualDocTemplate2->m_hMenuShared);
	m_pManualDocTemplate2->m_hMenuShared = NULL;

	m_pManualDocTemplate3 = new CMultiDocTemplate(
		IDR_MANUAL_FRAME,
		RUNTIME_CLASS(CManualEditDocument),
		RUNTIME_CLASS(CManualEditChildWnd),
		RUNTIME_CLASS(CManualEditView3));
	AddDocTemplate(m_pManualDocTemplate3);
	DestroyMenu(m_pManualDocTemplate3->m_hMenuShared);
	m_pManualDocTemplate3->m_hMenuShared = NULL;

	CWorkSchedulerDlg dlg;
	m_pMainWnd = g_pSystem->m_pMainDlg = new CWorkSchedulerDlg;
	((CDialog *)m_pMainWnd)->Create(IDD_WORKSCHEDULER_DIALOG);

	// �_�C�A���O�������Ă���A�v���P�[�V�����̃��b�Z�[�W �|���v���J�n������́A
	// �A�v���P�[�V�������I�����邽�߂� FALSE ��Ԃ��Ă��������B
	return TRUE;
}

//*****************************************************************************************************
//  1. �֐���
//		CWorkSchedulerApp::ExitInstance
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�C���X�^���X�I������
//----------------------------------------------------------------------------------------------------
//  3. �p�����[�^����
//		����
//----------------------------------------------------------------------------------------------------
//  4. �߂�l
//		BOOL		FALSE
//----------------------------------------------------------------------------------------------------
//  5. �����T�v
//		�f�[�^�Ǘ��N���X���������B
//----------------------------------------------------------------------------------------------------
//  6. ���l
//----------------------------------------------------------------------------------------------------
//  7. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************
int CWorkSchedulerApp::ExitInstance()
{
	if (g_pConst != NULL) {
		delete g_pConst;
		g_pConst = NULL;
	}

	if (g_pSystem != NULL) {
		delete g_pSystem;
		g_pSystem = NULL;
	}

	if (g_pDataManager != NULL) {
		delete g_pDataManager;
		g_pDataManager = NULL;
	}

	::CoUninitialize();

	return CWinApp::ExitInstance();
}
