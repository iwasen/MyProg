// PcVideo.h : PCVIDEO �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C��
//

#if !defined(AFX_PCVIDEO_H__EA0547D5_BB10_11D2_8BC7_00104B939DF5__INCLUDED_)
#define AFX_PCVIDEO_H__EA0547D5_BB10_11D2_8BC7_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"       // ���C�� �V���{��

/////////////////////////////////////////////////////////////////////////////
// CPcVideoApp:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� PcVideo.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CPcVideoApp : public CWinApp
{
public:
	CPcVideoApp();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	//{{AFX_VIRTUAL(CPcVideoApp)
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();
	//}}AFX_VIRTUAL

// �C���v�������e�[�V����
	//{{AFX_MSG(CPcVideoApp)
	afx_msg void OnAppAbout();
	afx_msg void OnHelpUsing();
	afx_msg void OnHelpContents();
	afx_msg void OnHelpKey();
	afx_msg void OnOnlineUpdate();
	afx_msg void OnUpdateOnlineUpdate(CCmdUI* pCmdUI);
	//}}AFX_MSG
	afx_msg void OnHelp();
	DECLARE_MESSAGE_MAP()

private:
	void	InitResources();
	void	InitializePalette();

	BOOL	m_bReadIniFile;
	CString m_ExeFileName;
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_PCVIDEO_H__EA0547D5_BB10_11D2_8BC7_00104B939DF5__INCLUDED_)
