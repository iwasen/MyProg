// vjb30020.h : VJB30020 �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#if !defined(AFX_VJB30020_H__33D62399_5984_4ECA_94D3_9E326EE00F1F__INCLUDED_)
#define AFX_VJB30020_H__33D62399_5984_4ECA_94D3_9E326EE00F1F__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// ���C�� �V���{��
#include "global.h"

extern CString g_sQueueDir;
extern int g_nTimer;

/////////////////////////////////////////////////////////////////////////////
// CVjb30020App:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� vjb30020.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CVjb30020App : public CWinApp
{
public:
	CVjb30020App();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30020App)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CVjb30020App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	HANDLE m_hMutex;

	void ReadIniFile();
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30020_H__33D62399_5984_4ECA_94D3_9E326EE00F1F__INCLUDED_)
