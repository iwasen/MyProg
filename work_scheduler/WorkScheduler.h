#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		WorkScheduler.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		CWorkSchedulerApp �N���X�̃C���^�[�t�F�C�X
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2006.09.01 S.Aizawa(xxx) �V�K�쐬
//*****************************************************************************************************

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// ���C�� �V���{��
#include "Global.h"
#include "ArrayEx.h"
#include "Const.h"
#include "System.h"
#include "DataManager.h"

#define COLOR_MENU_BUTTON		RGB(0x55, 0xff, 0xff)
#define COLOR_SUBMENU_BUTTON	RGB(0xff, 0xff, 0x99)

extern CConst *g_pConst;				// �萔�N���X
extern CSystem *g_pSystem;				// �V�X�e���Ǘ��N���X
extern CDataManager *g_pDataManager;	// �f�[�^�Ǘ��I�u�W�F�N�g�|�C���^

// �v���O�����{�̂�CwinApp�N���X
class CWorkSchedulerApp : public CWinApp
{
public:
// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CWorkSchedulerApp)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CWorkSchedulerApp)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

public:
	CMultiDocTemplate* m_pManualDocTemplate1;	// �蓮�Ґ��h�L�������g�e���v���[�g�ւ̃|�C���^
	CMultiDocTemplate* m_pManualDocTemplate2;	// �蓮�Ґ��h�L�������g�e���v���[�g�ւ̃|�C���^
	CMultiDocTemplate* m_pManualDocTemplate3;	// �蓮�Ґ��h�L�������g�e���v���[�g�ւ̃|�C���^
};


//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B
