//*****************************************************************************************************
//  1. ファイル名
//		ProgressBar.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		プログレスバーダイアログクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		ファイル読み込み等の処理を行いながらプログレスバーの表示を同時に行うため、マルチスレッドで
//		処理する。メインスレッド側でプログレスバーの表示を行い、ワーカスレッドで処理本体を実行する。
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "ProgressBar.h"

#define WM_SET_PROGRESS_BAR	(WM_USER + 1)
#define WM_END_DIALOG	(WM_USER + 2)

// CProgressBar ダイアログ

IMPLEMENT_DYNAMIC(CProgressBar, CDialogEx)

//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::CProgressBar
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CWnd		*pParent		[I] 親ウィンドウへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CProgressBar::CProgressBar(CWnd* pParent)
	: CDialogEx(CProgressBar::IDD, pParent)
{
	m_nRet = 0;
}

void CProgressBar::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_PROGRESS_BAR, m_cProgressBar);
}


BEGIN_MESSAGE_MAP(CProgressBar, CDialogEx)
	ON_MESSAGE(WM_SET_PROGRESS_BAR, OnSetProgressBar)
	ON_MESSAGE(WM_END_DIALOG, OnEndDialog)
END_MESSAGE_MAP()


//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::ExecProcess
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		開始処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pTitle				[I] プログレスバーウィンドウのタイトル
//		int			nMaxProgress		[I] プログレスバーの最大値
//		LPVOID		pParam				[I] 処理実行関数に渡すパラメータ
//		LPExecFunc	pExecFunc			[I] 処理実行関数へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：処理中止
//*****************************************************************************************************
BOOL CProgressBar::ExecProcess(LPCTSTR pTitle, int nMaxProgress, LPVOID pParam, LPExecFunc pExecFunc)
{
	// パラメータを保存
	m_sTitle = pTitle;
	m_nMaxProgress = nMaxProgress;
	m_nCurrentProgress = 0;
	m_pParam = pParam;
	m_pExecFunc = pExecFunc;

	// ワーカスレッド起動
	m_pWinThread = AfxBeginThread(ThreadFunc, this, THREAD_PRIORITY_NORMAL, 0, CREATE_SUSPENDED);
	m_pWinThread->m_bAutoDelete = FALSE;

	// プログレスバーダイアログを開く
	m_nRet = DoModal();

	// ワーカスレッドの終了を待つ
	::WaitForSingleObject(m_pWinThread->m_hThread, INFINITE);

	// ワーカスレッドオブジェクトを解放
	delete m_pWinThread;

	return m_nRet != IDCANCEL;
}

//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::ThreadFunc
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ワーカスレッドエントリ関数
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID		pParam				[I] 処理実行関数に渡すパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		UINT	0
//*****************************************************************************************************
UINT __cdecl CProgressBar::ThreadFunc(LPVOID pParam)
{
	CProgressBar *pProgressBar = (CProgressBar *)pParam;

	// 処理本体を呼び出す
	BOOL bResult = pProgressBar->m_pExecFunc(pProgressBar->m_pParam, pProgressBar);

	// プログレスバーダイアログを終了させる
	if (pProgressBar->m_hWnd)
		pProgressBar->PostMessage(WM_END_DIALOG, bResult);

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::SetProgress
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		プログレスバー設定処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nProgress			[I] プログレスバーにセットする値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：処理続行　FALSE：処理中止
//*****************************************************************************************************
BOOL CProgressBar::SetProgress(int nProgress)
{
	if (nProgress != m_nCurrentProgress) {
		m_nCurrentProgress = nProgress;

		if (m_hWnd)
			PostMessage(WM_SET_PROGRESS_BAR, nProgress);
	}

	return m_nRet != IDCANCEL;
}

//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CProgressBar::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// ウィンドウタイトル設定
	SetWindowText(m_sTitle);

	// プログレスバーのレンジを設定
	m_cProgressBar.SetRange(0, (short)m_nMaxProgress);

	// ワーカスレッドの実行を再開
	m_pWinThread->ResumeThread();

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::OnSetProgressBar
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		プログレスバー設定メッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CProgressBar::OnSetProgressBar(WPARAM wParam, LPARAM lParam)
{
	// プログレスバーの位置を設定
	m_cProgressBar.SetPos((int)wParam);
	UpdateWindow();

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CProgressBar::OnEndDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ終了メッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		WPARAM		wParam		[I] ウィンドウメッセージパラメータ
//		LPARAM		lParam		[I] ウィンドウメッセージパラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		0
//*****************************************************************************************************
LRESULT CProgressBar::OnEndDialog(WPARAM wParam, LPARAM lParam)
{
	EndDialog(wParam ? IDOK : IDCANCEL);

	return 0;
}
