// EcuRecv.h : ECURECV �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#if !defined(AFX_ECURECV_H__8CC9B145_B139_11D3_8056_7A2C79000000__INCLUDED_)
#define AFX_ECURECV_H__8CC9B145_B139_11D3_8056_7A2C79000000__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// ���C�� �V���{��

/////////////////////////////////////////////////////////////////////////////
// CEcuRecvApp:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� EcuRecv.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CEcuRecvApp : public CWinApp
{
public:
	CEcuRecvApp();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CEcuRecvApp)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CEcuRecvApp)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_ECURECV_H__8CC9B145_B139_11D3_8056_7A2C79000000__INCLUDED_)
