// Capture.h : CAPTURE �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C��
//

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"       // ���C�� �V���{��

#define MODE_AUTO		0
#define MODE_PREVIEW	1
#define MODE_STOP		2

/////////////////////////////////////////////////////////////////////////////
// CCaptureApp:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� Capture.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CCaptureApp : public CWinApp
{
public:
	CCaptureApp();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��𐶐����I�[�o�[���C�h���܂��B
	//{{AFX_VIRTUAL(CCaptureApp)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CCaptureApp)
	afx_msg void OnAppAbout();
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()
};


/////////////////////////////////////////////////////////////////////////////

