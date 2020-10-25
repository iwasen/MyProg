// vjb30010.h : VJB30010 �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C���ł��B
//

#if !defined(AFX_VJB30010_H__71FB461D_A04F_49FD_9322_657F9DA46131__INCLUDED_)
#define AFX_VJB30010_H__71FB461D_A04F_49FD_9322_657F9DA46131__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"		// ���C�� �V���{��

// �p�����[�^��
#define LEN_SHUBETSU_CODE	3	// �}�̎�ʃR�[�h��
#define LEN_SHIKIBETSU_CODE	6	// �}�̎��ʃR�[�h��
#define LEN_VOLUME_LABEL			6	// �{�����[�����x����
#define LEN_TOTAL	(LEN_SHUBETSU_CODE + LEN_SHIKIBETSU_CODE + LEN_VOLUME_LABEL)

// �p�����[�^�ʒu
#define POS_SHUBETSU_CODE	0	// �}�̎�ʃR�[�h��
#define POS_SHIKIBETSU_CODE	3	// �}�̎��ʃR�[�h��
#define POS_VOLUME_LABEL			9	// �{�����[�����x����

#define MAX_VOLUME_LABEL			7	// �ő�{�����[�����x����
#define INI_FILE	".\\baitai.ini"		// .ini�t�@�C����

// ���^�[���R�[�h
#define RC_NORMAL		0				// ����I��
#define RC_PARAMNUM		1				// �p�����[�^�����s��
#define RC_PARAMVAL		2				// �p�����[�^�̓��e���s��
#define RC_PARAMLEN		3				// �p�����[�^�̒������s��
#define RC_VOLNUM		4				// �{�����[�����x���̐�����������
#define RC_VOLLEN		5				// �{�����[�����x���̒������s��
#define RC_QUEUEFILE	6				// �L���[�t�@�C�����쐬�ł��Ȃ�

// �p�����[�^���
struct Param {
	char cShubetsuCode[LEN_SHUBETSU_CODE + 1];					// �}�̎�ʃR�[�h
	char cShikibetsuCode[LEN_SHIKIBETSU_CODE + 1];				// �}�̎��ʃR�[�h
	char cVolLabel[MAX_VOLUME_LABEL][LEN_VOLUME_LABEL + 1];		// �{�����[�����x��
	int nVolLabel;												// �{�����[�����x���̐�
};	

/////////////////////////////////////////////////////////////////////////////
// CVjb30010App:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� vjb30010.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CVjb30010App : public CWinApp
{
public:
	CVjb30010App();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CVjb30010App)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����

	//{{AFX_MSG(CVjb30010App)
		// ���� - ClassWizard �͂��̈ʒu�Ƀ����o�֐���ǉ��܂��͍폜���܂��B
		//        ���̈ʒu�ɐ��������R�[�h��ҏW���Ȃ��ł��������B
	//}}AFX_MSG
	DECLARE_MESSAGE_MAP()

private:
	int m_nRetCode;

	int GetParam(int argc, char* argv[], Param *pParam);
	int CreateQueueFile(Param *pParam);
	void DispMessage(LPCTSTR msg);
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_VJB30010_H__71FB461D_A04F_49FD_9322_657F9DA46131__INCLUDED_)
