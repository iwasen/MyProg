#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ProgressBar.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		プログレスバーダイアログクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "resource.h"


// CProgressBar ダイアログ

class CProgressBar : public CDialogEx
{
	DECLARE_DYNAMIC(CProgressBar)

public:
	CProgressBar(CWnd* pParent = NULL);   // 標準コンストラクタ

	enum { IDD = IDD_PROGRESS_BAR };

	typedef BOOL (*LPExecFunc)(LPVOID, class CProgressBar *);

	BOOL ExecProcess(LPCTSTR pTitle, int nMaxProgress, LPVOID pParam, LPExecFunc pExecFunc);
	BOOL SetProgress(int nProgress);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

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
