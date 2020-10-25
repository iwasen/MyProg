// vjb30050.h : VJB30050 �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#if !defined(AFX_VJB30050_H__39C04A94_BAC8_4B0A_A626_B3C62533925B__INCLUDED_)
#define AFX_VJB30050_H__39C04A94_BAC8_4B0A_A626_B3C62533925B__INCLUDED_

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
#define RC_PARAMETER	1				// �p�����[�^���s��
#define RC_STATUS		2				// �L���[�t�@�C���̏�ԃR�[�h���s��
#define RC_VOLUMENAME	3				// �{�����[�����x�����Ⴄ
#define RC_IOERROR		4				// I/O �G���[
#define RC_CANCEL		5				// �������L�����Z�����ꂽ
#define RC_MULTIFILEVOL	6				// ���x�������̃}���`�t�@�C���^�}���`�{�����[��

struct Param {
	CString sShubetsuCode;
	CString sBatchServer;
	CString sShikibetsuCode;
	CString sVolLabel;
};

extern CString g_sLogDir;
extern CString g_sInputDir;
extern CString g_sDatDir;
extern CString g_sBatchUNC;

/////////////////////////////////////////////////////////////////////////////
// CVjb30050App:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� vjb30050.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CVjb30050App : public CWinApp
{
public:
	CVjb30050App();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30050App)
	public:
	virtual BOOL InitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CVjb30050App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nRetCode;

	int Main(int argc, char *argv[]);
	void ReadIniFile();
	void WriteLogFile(Param *pParam, LPCTSTR pLogFile, LPCTSTR pStatus);
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30050_H__39C04A94_BAC8_4B0A_A626_B3C62533925B__INCLUDED_)
