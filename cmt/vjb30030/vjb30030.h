// vjb30030.h : VJB30030 �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#if !defined(AFX_VJB30030_H__D9F2D691_7B09_4B71_AF0E_F17A8E77917B__INCLUDED_)
#define AFX_VJB30030_H__D9F2D691_7B09_4B71_AF0E_F17A8E77917B__INCLUDED_

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
#define RC_NODIRECTORY	7				// �R�s�[���f�B���N�g��������
#define RC_NOFILE		8				// �R�s�[���t�@�C��������
#define RC_ILLEGALFILENAME	9			// �t�@�C�������s��

#define MAX_VOLUME_LABEL	7			// �ő�{�����[�����x����

// �L���[�t�@�C�����
struct Queue {
	CString sFileName;					// �t�@�C����
	CString sShubetsuCode;				// �}�̎�ʃR�[�h
	CString sShikibetsuCode;			// �}�̎��ʃR�[�h
	CString sVolLabel;					// �{�����[�����x��
	CString sCreateDate;				// �쐬���t
	char cStatus;						// ��ԃR�[�h
};

extern CString g_sPathFPD;
extern CString g_sPathMOD;
extern CString g_sOutputDir;
extern CString g_sLogDir;

/////////////////////////////////////////////////////////////////////////////
// CVjb30030App:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� vjb30030.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CVjb30030App : public CWinApp
{
public:
	CVjb30030App();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30030App)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CVjb30030App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nRetCode;
	CString m_sQueueDir;

	int Main(int argc, char *argv[]);
	void ReadIniFile();
	Queue *GetQueueData(CString sFileName);
	void ChangeStatus(Queue *pQueue, char status);
	void DeleteSrcFile(Queue *pQueue);
	void NotifyChangeStatus();
	void WriteLogFile(Queue *pQueue, LPCTSTR pLogFile);
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30030_H__D9F2D691_7B09_4B71_AF0E_F17A8E77917B__INCLUDED_)
