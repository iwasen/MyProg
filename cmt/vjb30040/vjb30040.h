// vjb30040.h : VJB30040 �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#if !defined(AFX_VJB30040_H__3FD25355_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
#define AFX_VJB30040_H__3FD25355_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// ���C�� �V���{��
#include "global.h"

// ���^�[���R�[�h
#define RC_NORMAL		0				// ����I��
#define RC_VOLUMENAME	1				// �{�����[�����x�����Ⴄ
#define RC_IOERROR		2				// I/O �G���[
#define RC_CANCEL		3				// �������L�����Z�����ꂽ

// ���̓p�����[�^
struct Param {
	int nBaitaiShubetsu;
	CString sShubetsuCode;
	CString sShikibetsuCode;
	CString sBatchServer;
	CString sVolLabel;
	BOOL bLabel;
	int nVolCount;
};

// �O���[�o���ϐ�
extern CString g_sPathFPD;
extern CString g_sPathMOD;
extern CString g_sInputDir;
extern CString g_sDatDir;
extern CString g_sLogDir;
extern CString g_BatchServerFile;

/////////////////////////////////////////////////////////////////////////////
// CVjb30040App:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� vjb30040.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CVjb30040App : public CWinApp
{
public:
	CVjb30040App();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30040App)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CVjb30040App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	void ReadIniFile();
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30040_H__3FD25355_9ABD_11D4_ADFB_00105A67EE03__INCLUDED_)
