//*****************************************************************************************************
//  1. ファイル名
//		MenuCommon.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		接続系統図・機能系統図・機能説明図の操作メニューの共通親クラス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "MenuCommon.h"
#include "MenuButton.h"

#define HALF_TRANSPARENT	150

// CMenuCommon ダイアログ

IMPLEMENT_DYNAMIC(CMenuCommon, CDialogEx)

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::CMenuCommon
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nID			[I] リソースID
//		CWnd*	pParent		[I] 親ウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CMenuCommon::CMenuCommon(UINT nID, CWnd* pParent)
	: CDialogEx(nID, pParent)
{
	m_bSlowSpeed = FALSE;
}

void CMenuCommon::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
}


BEGIN_MESSAGE_MAP(CMenuCommon, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_ZOOM_IN, &CMenuCommon::OnBnClickedButtonZoomIn)
	ON_BN_CLICKED(IDC_BUTTON_ZOOM_OUT, &CMenuCommon::OnBnClickedButtonZoomOut)
	ON_BN_CLICKED(IDC_BUTTON_SIZE_RESET, &CMenuCommon::OnBnClickedButtonSizeReset)
	ON_BN_CLICKED(IDC_BUTTON_PRINT, &CMenuCommon::OnBnClickedButtonPrint)
	ON_BN_CLICKED(IDC_BUTTON_EXIT, &CMenuCommon::OnBnClickedButtonExit)
	ON_BN_CLICKED(IDC_BUTTON_PAUSE, &CMenuCommon::OnBnClickedButtonPause)
	ON_BN_CLICKED(IDC_BUTTON_SLOW, &CMenuCommon::OnBnClickedButtonSlow)
	ON_BN_CLICKED(IDC_BUTTON_NORMAL, &CMenuCommon::OnBnClickedButtonNormal)
	ON_WM_ACTIVATE()
	ON_WM_NCLBUTTONDBLCLK()
END_MESSAGE_MAP()


// CMenuCommon メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnInitDialog
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ダイアログ初期化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE
//*****************************************************************************************************
BOOL CMenuCommon::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	EnableSpeedButton(FALSE);

	// 親ウィンドウの右端に表示
	if (m_pParentWnd != NULL) {
		CRect rectWindow;
		CRect rectParent;
		GetWindowRect(rectWindow);
		m_pParentWnd->GetWindowRect(rectParent);
		SetWindowPos(NULL, rectParent.right - rectWindow.Width() - 5, rectWindow.top + 5, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
	}

	// ダイアログのサイズを保存
	GetClientRect(m_rectClient);

	// 終了ボタンにフォーカスを設定
	GetDlgItem(IDC_BUTTON_EXIT)->SetFocus();

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonZoomIn
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「拡大」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonZoomIn()
{
	GetDlgItem(IDC_BUTTON_ZOOM_IN)->EnableWindow((BOOL)SendMessageParent(WM_USER_ZOOM_IN));
	GetDlgItem(IDC_BUTTON_ZOOM_OUT)->EnableWindow(TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonZoomOut
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「縮小」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonZoomOut()
{
	GetDlgItem(IDC_BUTTON_ZOOM_OUT)->EnableWindow((BOOL)SendMessageParent(WM_USER_ZOOM_OUT));
	GetDlgItem(IDC_BUTTON_ZOOM_IN)->EnableWindow(TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonSizeReset
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「サイズリセット」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonSizeReset()
{
	SendMessageParent(WM_USER_SIZE_RESET);

	GetDlgItem(IDC_BUTTON_ZOOM_IN)->EnableWindow(TRUE);
	GetDlgItem(IDC_BUTTON_ZOOM_OUT)->EnableWindow(TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonPrint
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「印刷」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonPrint()
{
	SendMessageParent(WM_USER_PRINT);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonExit
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「終了」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonExit()
{
	SendMessageParent(WM_USER_EXIT);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キャンセル処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnCancel()
{
	// ESCキーで閉じるのを防ぐ
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::SendAction
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Flashへ動作指示送信
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		int			nActionNo			[I] アクション番号
//		CUIntArray	*pParamArray		[I] 付加パラメータの配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::SendAction(int nActionNo, CUIntArray *pParamArray)
{
	SendMessageParent(WM_USER_ACTION, nActionNo, (LPARAM)pParamArray);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::SendMessageParent
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		親ウィンドウへメッセージ送信
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nMessage		[I] メッセージ番号
//		WPARAM	wParam			[I] 通知パラメータ
//		LPARAM	lParam			[I] 通知パラメータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		LRESULT		メッセージ処理結果
//*****************************************************************************************************
LRESULT CMenuCommon::SendMessageParent(UINT nMessage, WPARAM wParam, LPARAM lParam)
{
	return m_pParentWnd->SendMessage(nMessage, wParam, lParam);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnNcLButtonDblClk
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイトル部分のダブルクリックでタイトルのみに縮小させる
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nHitTest		[I] ヒットテストコード
//		CPoint	point			[I] カーソル位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnNcLButtonDblClk(UINT nHitTest, CPoint point)
{
	if (nHitTest == HTCAPTION) {
		CRect rectClinet;
		GetClientRect(rectClinet);
		rectClinet.bottom = (rectClinet.bottom == 0) ? m_rectClient.bottom : 0;
		CalcWindowRect(rectClinet, FALSE);
		SetWindowPos(NULL, 0, 0, rectClinet.Width(), rectClinet.Height(), SWP_NOMOVE | SWP_NOZORDER);
	}

	CDialogEx::OnNcLButtonDblClk(nHitTest, point);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonPause
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「一時停止」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonPause()
{
	CWnd *pWnd = GetDlgItem(IDC_BUTTON_PAUSE);

	if (pWnd->SendMessage(BM_GETCHECK) == BST_CHECKED) {
		pWnd->SetWindowText("停止解除");
		SendMessageParent(WM_USER_PAUSE, 1);
		EnableButton(IDC_BUTTON_SLOW, FALSE);
		EnableButton(IDC_BUTTON_NORMAL, FALSE);
	} else {
		pWnd->SetWindowText("一時停止");
		SendMessageParent(WM_USER_PAUSE, 0);
		EnableButton(IDC_BUTTON_SLOW, TRUE);
		EnableButton(IDC_BUTTON_NORMAL, m_bSlowSpeed);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonSlow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「低速」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonSlow()
{
	m_bSlowSpeed = TRUE;
	SendMessageParent(WM_USER_SLOW);
	EnableSpeedButton(TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::OnBnClickedButtonNormal
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「通常」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::OnBnClickedButtonNormal()
{
	m_bSlowSpeed = FALSE;
	SendMessageParent(WM_USER_NORMAL);
	EnableSpeedButton(TRUE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::EnableSpeedButton
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		「一時停止」「低速」「通常」ボタン有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		BOOL	bStart		[I] TRUE:動作開始　FALSE:動作終了
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::EnableSpeedButton(BOOL bStart)
{
	if (bStart) {
		EnableButton(IDC_BUTTON_PAUSE, TRUE);
		EnableButton(IDC_BUTTON_SLOW, TRUE);
		EnableButton(IDC_BUTTON_NORMAL, m_bSlowSpeed);
	} else {
		EnableButton(IDC_BUTTON_PAUSE, FALSE);
		EnableButton(IDC_BUTTON_SLOW, FALSE);
		EnableButton(IDC_BUTTON_NORMAL, FALSE);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMenuCommon::EnableButton
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ボタンクリック有効化処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		UINT	nID			[I] ボタンのコントロールID
//		BOOL	bEnable		[I] TRUE:有効　FALSE:無効
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMenuCommon::EnableButton(UINT nID, BOOL bEnable)
{
	CWnd *pWnd = GetDlgItem(nID);
	if (pWnd != NULL) {
		CMenuButton cButton;
		cButton.Attach(pWnd->m_hWnd);
		cButton.SetEnable(bEnable);
		cButton.Detach();
	}
}
