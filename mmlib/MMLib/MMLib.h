// MMLib.h : MMLIB �A�v���P�[�V�����̃��C�� �w�b�_�[ �t�@�C��
//

#pragma once

#ifndef __AFXWIN_H__
	#error include 'stdafx.h' before including this file for PCH
#endif

#include "resource.h"       // ���C�� �V���{��

#include "pcscl.h"
#include "mmdb.h"

#include "Define.h"
#include "Global.h"
#include "MyCtrl.h"
#include "ddeml.h"

/////////////////////////////////////////////////////////////////////////////
// CMMLibApp:
// ���̃N���X�̓���̒�`�Ɋւ��Ă� MMLib.cpp �t�@�C�����Q�Ƃ��Ă��������B
//

class CMMLibApp : public CWinApp
{
public:
	CMMLibApp();

// �I�[�o�[���C�h
	// ClassWizard �͉��z�֐��̃I�[�o�[���C�h�𐶐����܂��B
	public:
	virtual BOOL InitInstance();
	virtual int ExitInstance();

// �C���v�������e�[�V����

	afx_msg void OnAppAbout();
	afx_msg void OnFileNew();
	afx_msg void OnOnlineUpdate();
	afx_msg void OnUpdateOnlineUpdate(CCmdUI* pCmdUI);
	afx_msg void OnOnlineManual();
	DECLARE_MESSAGE_MAP()

public:
	HCURSOR	m_hCursorArrow;
	HCURSOR	m_hCursorHandOpen;
	HCURSOR	m_hCursorHandClose;
	CSingleDocTemplate *m_pPictDocTemplate;
	CSingleDocTemplate *m_pFindDocTemplate;
	HSZ m_hTopicText;
	HSZ m_hTopicData;
	CDocument *m_pDoc;
	int m_nDdeMode;
	DWORD m_dwIdInst;

private:
	HICON m_hIconApp;
	BOOL m_bOnlineUpdate;
	CString m_ExeFileName;
	HSZ m_hService;
	ULONG m_nCheckData;
	char *m_pRegistryName;

	void InitDDE();
	void UninitDDE();
	void CopyInifileToRegistory();
};

extern CMMLibApp theApp;


/////////////////////////////////////////////////////////////////////////////


class CMyCmdLineInfo : public CCommandLineInfo  
{
public:
	CMyCmdLineInfo();
	virtual ~CMyCmdLineInfo();

	void ParseParam( LPCTSTR lpszParam, BOOL bFlag, BOOL bLast );
};
