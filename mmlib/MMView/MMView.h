// MMView.h : MMVIEW �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C��
//

#if !defined(AFX_MMVIEW_H__95FF6435_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
#define AFX_MMVIEW_H__95FF6435_EE22_11D2_8C18_00104B939DF5__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"       // ���C�� �V���{��
#include "pcscl.h"
#include "mmdb.h"
#include "Mml.h"
#include "Item.h"
#include "Global.h"

/////////////////////////////////////////////////////////////////////////////
// CMMViewApp:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� MMView.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CMMViewApp : public CWinApp
{
public:
	CMMViewApp();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual BOOL InitInstance();

// �C���v�������e�[�V����
	afx_msg void OnAppAbout();
	afx_msg void OnOnlineUpdate();
	afx_msg void OnUpdateOnlineUpdate(CCmdUI* pCmdUI);
	afx_msg void OnOnlineManual();
	DECLARE_MESSAGE_MAP()

public:
	HCURSOR	m_hCursorArrow;
	HCURSOR	m_hCursorHandOpen;
	HCURSOR	m_hCursorHandClose;

private:
	HICON m_hIconApp;
	BOOL m_bOnlineUpdate;
	CString m_ExeFileName;
};


/////////////////////////////////////////////////////////////////////////////

//{{AFX_INSERT_LOCATION}}
// Microsoft Visual C++ �͑O�s�̒��O�ɒǉ��̐錾��}�����܂��B

#endif // !defined(AFX_MMVIEW_H__95FF6435_EE22_11D2_8C18_00104B939DF5__INCLUDED_)
