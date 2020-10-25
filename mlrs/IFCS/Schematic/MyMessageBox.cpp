//*****************************************************************************************************
//  1. ファイル名
//		MyMessageBox.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メッセージボックス表示処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "resource.h"
#include "MyMessageBox.h"

// CMyMessageBox ダイアログ


IMPLEMENT_DYNAMIC(CMyMessageBox, CDialogEx)

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::CMyMessageBox
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CWnd*	pParent		[I] 親ウィンドウ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CMyMessageBox::CMyMessageBox(CWnd* pParent /*=NULL*/)
	: CDialogEx(CMyMessageBox::IDD, pParent)
{
}

void CMyMessageBox::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_STATIC_ICON, m_cStaticIcon);
	DDX_Control(pDX, IDC_STATIC_MESSAGE, m_cStaticMessage);
	DDX_Control(pDX, IDC_BUTTON1, m_cButton1);
	DDX_Control(pDX, IDC_BUTTON2, m_cButton2);
	DDX_Control(pDX, IDC_BUTTON3, m_cButton3);
}


BEGIN_MESSAGE_MAP(CMyMessageBox, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON1, &CMyMessageBox::OnBnClickedButton1)
	ON_BN_CLICKED(IDC_BUTTON2, &CMyMessageBox::OnBnClickedButton2)
	ON_BN_CLICKED(IDC_BUTTON3, &CMyMessageBox::OnBnClickedButton3)
	ON_WM_DESTROY()
END_MESSAGE_MAP()


// CMyMessageBox メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::OnInitDialog
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
BOOL CMyMessageBox::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	// メッセージボックスのタイトルを設定
	SetWindowText(m_sTitle);

	// ウィンドウのサイズを取得
	GetWindowRect(m_rectWindow);

	// クライアント領域のサイズを取得
	GetClientRect(m_rectClient);

	// 各ボタンの位置とサイズを取得
	m_cButton1.GetWindowRect(m_rectButton1);
	m_cButton2.GetWindowRect(m_rectButton2);
	m_cButton3.GetWindowRect(m_rectButton3);
	ScreenToClient(m_rectButton1);
	ScreenToClient(m_rectButton2);
	ScreenToClient(m_rectButton3);

	// メッセージエリアのサイズを取得
	m_cStaticMessage.GetWindowRect(m_rectMessage);
	ScreenToClient(m_rectMessage);

	// アイコンとメッセージ表示
	DispMessage();

	// モードレスなら親ウィンドウをDisableする
	if (!m_bModal) {
		CWnd *pParent = GetParent();
		if (pParent != NULL)
			pParent->EnableWindow(FALSE);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::DispMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		アイコンとメッセージ表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::DispMessage()
{
	// ウィンドウが開いていなければ何もしない
	if (m_hWnd == NULL)
		return;

	// アイコンを取得
	HICON hIcon;
	switch (m_nType & 0xf0) {
	case MB_ICONEXCLAMATION:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_EXCLAMATION);
		break;
	case MB_ICONINFORMATION:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_INFORMATION);
		break;
	case MB_ICONQUESTION:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_QUESTION);
		break;
	case MB_ICONSTOP:
		hIcon = AfxGetApp()->LoadStandardIcon(IDI_ERROR);
		break;
	default:
		hIcon = NULL;
		break;
	}

	// アイコンを表示
	if (hIcon != NULL)
		m_cStaticIcon.SetIcon(hIcon);

	// ボタンの種類を取得
	LPCTSTR pButton1, pButton2, pButton3;
	switch (m_nType & 0x0f) {
	case MB_ABORTRETRYIGNORE:	// [中止]、[再試行]、[無視]
		pButton1 = "中　止";
		pButton2 = "再試行";
		pButton3 = "無　視";

		m_nButton1 = IDABORT;
		m_nButton2 = IDRETRY;		
		m_nButton3 = IDIGNORE;
		break;
	case MB_OK:	// [OK]
		pButton1 = "了　解";
		pButton2 = NULL;
		pButton3 = NULL;

		m_nButton1 = IDOK;
		break;
	case MB_OK_END:	// [OK]
		pButton1 = "終　了";
		pButton2 = NULL;
		pButton3 = NULL;

		m_nButton1 = IDOK;
		break;
	case MB_OKCANCEL:	// [OK]、[キャンセル]
		pButton1 = "了　解";
		pButton2 = "取　消";
		pButton3 = NULL;

		m_nButton1 = IDOK;
		m_nButton2 = IDCANCEL;
		break;
	case MB_OIL:		// [OK]、[キャンセル]　作業油の補充特別バージョン
		pButton1 = "補充実行";
		pButton2 = " 取　消 ";
		pButton3 = NULL;

		m_nButton1 = IDOK;
		m_nButton2 = IDCANCEL;
		break;
	case MB_RETRYCANCEL:	// [再試行]、[キャンセル]
		pButton1 = "再試行";
		pButton2 = "取　消";
		pButton3 = NULL;

		m_nButton1 = IDRETRY;
		m_nButton2 = IDCANCEL;
		break;
	case MB_YESNO:	// [はい]、[いいえ]
		pButton1 = "は　い";
		pButton2 = "いいえ";
		pButton3 = NULL;

		m_nButton1 = IDYES;
		m_nButton2 = IDNO;
	case MB_YESNOCANCEL:	// [はい]、[いいえ]、[キャンセル]
		pButton1 = "は　い";
		pButton2 = "いいえ";
		pButton3 = "取　消";

		m_nButton1 = IDYES;
		m_nButton2 = IDNO;		
		m_nButton3 = IDCANCEL;
		break;
	}

	// ボタンのサイズを取得
	CSize sizeButton(m_rectButton1.Width(), m_rectButton1.Height());

	// ボタンの間のスペースを取得
	int nButtonSpace = m_rectButton2.left - m_rectButton1.right;

	// ボタンの左側のスペースを取得
	int nLeftSpace = m_rectButton1.left;

	// ボタンの右側のスペースを取得
	int nRightSpace = nLeftSpace;

	// 全てのボタンが入る最小のウィンドウサイズを計算
	CSize sizeWindowButton(nLeftSpace + sizeButton.cx + nRightSpace, m_rectWindow.Height());
	if (pButton2 != NULL)
		sizeWindowButton.cx += sizeButton.cx + nButtonSpace;
	if (pButton3 != NULL)
		sizeWindowButton.cx += sizeButton.cx + nButtonSpace;

	// メッセージのサイズを取得
	CRect rectText(0, 0, 0, 0);
	CDC *pDC = GetWindowDC();
	CFont *pFontOrg = pDC->SelectObject(GetFont());
	pDC->DrawText(m_sMessage, rectText, DT_CALCRECT);
	pDC->SelectObject(pFontOrg);
	ReleaseDC(pDC);

	// 全てのメッセージが入る最小のウィンドウサイズを計算
	CSize sizeWindowText;
	sizeWindowText.cx = m_rectWindow.Width() + rectText.Width() - m_rectMessage.Width();
	sizeWindowText.cy = m_rectWindow.Height() + rectText.Height() - m_rectMessage.Height();

	// メッセージのサイズによりウィンドウサイズを拡張
	CSize sizeWindow(max(sizeWindowButton.cx, sizeWindowText.cx), max(sizeWindowButton.cy, sizeWindowText.cy));

	// ウィンドウの位置とサイズを調整
	SetWindowPos(NULL, 0, 0, sizeWindow.cx, sizeWindow.cy, SWP_NOZORDER | SWP_NOMOVE);
	CenterWindow();

	// メッセージのサイズを調整
	m_cStaticMessage.SetWindowPos(NULL, 0, 0, rectText.right, rectText.bottom, SWP_NOMOVE | SWP_NOZORDER);

	// メッセージを表示
	m_cStaticMessage.SetWindowText(m_sMessage);

	// ボタンの位置を計算
	CPoint pointButton;
	pointButton.x = nLeftSpace + (sizeWindow.cx - sizeWindowButton.cx) / 2;
	pointButton.y = m_rectButton1.top + sizeWindow.cy - m_rectWindow.Height();

	// １番目のボタンを表示
	m_cButton1.SetWindowPos(NULL, pointButton.x, pointButton.y, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
	m_cButton1.SetWindowText(pButton1);

	// ２番目のボタンを表示
	if (pButton2 != NULL) {
		pointButton.x += sizeButton.cx + nButtonSpace;
		m_cButton2.SetWindowPos(NULL, pointButton.x, pointButton.y, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
		m_cButton2.SetWindowText(pButton2);
		m_cButton2.ShowWindow(SW_SHOW);
	} else
		m_cButton2.ShowWindow(SW_HIDE);

	// ３番目のボタンを表示
	if (pButton3 != NULL) {
		pointButton.x += sizeButton.cx + nButtonSpace;
		m_cButton3.SetWindowPos(NULL, pointButton.x, pointButton.y, 0, 0, SWP_NOSIZE | SWP_NOZORDER);
		m_cButton3.SetWindowText(pButton3);
		m_cButton3.ShowWindow(SW_SHOW);
	} else
		m_cButton3.ShowWindow(SW_HIDE);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::MessageBox
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		モーダルメッセージボックス表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCTSTR	lpszText		[I] 表示メッセージ
//		LPCTSTR	lpszCaption		[I] ウィンドウタイトル
//		UINT	nType			[I] アイコンとボタンの種類
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
int CMyMessageBox::MessageBox(LPCTSTR lpszText, LPCTSTR lpszCaption, UINT nType)
{
	m_sMessage = lpszText;
	m_sTitle = lpszCaption;
	m_nType = nType;
	m_bModal = TRUE;

	if (m_sTitle=="")
		m_sTitle=::AfxGetAppName();

	return (int)DoModal();
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::ModelessMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		モードレスメッセージボックス表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CWnd	*pWnd			[I] 親ウィンドウ
//		LPCTSTR	lpszText		[I] 表示メッセージ
//		LPCTSTR	lpszCaption		[I] ウィンドウタイトル
//		UINT	nType			[I] アイコンとボタンの種類
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::ModelessMessage(CWnd *pWnd, LPCTSTR lpszText, LPCTSTR lpszCaption, UINT nType)
{
	m_sMessage = lpszText;
	m_sTitle = lpszCaption;
	m_nType = nType;
	m_bModal = FALSE;

	if (m_sTitle=="")
		m_sTitle=::AfxGetAppName();

	// モードレスでダイアログを開く
	DoModeless(pWnd);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::ChangeMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メッセージ変更
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		LPCTSTR	lpszText		[I] 表示メッセージ
//		UINT	nType			[I] アイコンとボタンの種類
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::ChangeMessage(LPCTSTR lpszText, UINT nType)
{
	m_sMessage = lpszText;
	m_nType = nType;

	DispMessage();
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::CloseMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メッセージボックスクローズ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::CloseMessage()
{
	if (m_hWnd != NULL)
		EndDialog(IDCANCEL);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::OnBnClickedButton1
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		第１ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::OnBnClickedButton1()
{
	ClickedButton(m_nButton1);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::OnBnClickedButton2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		第２ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::OnBnClickedButton2()
{
	ClickedButton(m_nButton2);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::OnBnClickedButton3
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		第３ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::OnBnClickedButton3()
{
	ClickedButton(m_nButton3);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::ClickedButton
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		int		nButton		[I] クリックしたボタンのID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::ClickedButton(int nButton)
{
	if (m_bModal)
		EndDialog(nButton);
	else {
		// 親ウィンドウに押されたボタンを通知する
		CWnd *pParent = GetParent();
		if (pParent != NULL)
			pParent->PostMessage(WM_MY_MESSAGE_BOX, nButton);

		// ウィンドウを閉じる
		DestroyWindow();
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::OnCancel
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		キャンセルボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::OnCancel()
{
	ClickedButton(IDCANCEL);
}

//*****************************************************************************************************
//  1. 関数名
//		CMyMessageBox::OnDestroy
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウ破棄処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMyMessageBox::OnDestroy()
{
	CDialogEx::OnDestroy();

	// モードレスなら親ウィンドウをEnableにする
	if (!m_bModal) {
		CWnd *pParent = GetParent();
		if (pParent != NULL)
			pParent->EnableWindow(TRUE);
	}
}
