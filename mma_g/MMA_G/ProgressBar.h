#pragma once
//*****************************************************************************************************
//  1. �t�@�C����
//		ProgressBar.h
//----------------------------------------------------------------------------------------------------
//  2. �@�\
//		�v���O���X�o�[�_�C�A���O�N���X�̒�`
//----------------------------------------------------------------------------------------------------
//  3. ���l
//----------------------------------------------------------------------------------------------------
//  4. ����
//		2007.08.09 S.Aizawa �V�K�쐬
//*****************************************************************************************************

#include "DialogEx.h"
#include "resource.h"


// CProgressBar �_�C�A���O

class CProgressBar : public CDialogEx
{
	DECLARE_DYNAMIC(CProgressBar)

public:
	CProgressBar(CWnd* pParent = NULL);   // �W���R���X�g���N�^

	enum { IDD = IDD_PROGRESS_BAR };

	typedef BOOL (*LPExecFunc)(LPVOID, class CProgressBar *);

	BOOL ExecProcess(LPCTSTR pTitle, int nMaxProgress, LPVOID pParam, LPExecFunc pExecFunc);
	BOOL SetProgress(int nProgress);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg LRESULT OnSetProgressBar(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnEndDialog(WPARAM wParam, LPARAM lParam);
	CProgressCtrl m_cProgressBar;

protected:
	CString m_sTitle;
	int m_nMaxProgress;
	int m_nCurrentProgress;
	LPVOID m_pParam;
	LPExecFunc m_pExecFunc;
	static UINT __cdecl ThreadFunc(LPVOID pParam);
	CWinThread *m_pWinThread;
	INT_PTR m_nRet;
};
