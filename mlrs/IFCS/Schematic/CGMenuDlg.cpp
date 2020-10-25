//*****************************************************************************************************
//  1. ファイル名
//		CGMenuDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		機能図ＣＧ表示メニュー処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "stdafx.h"
#include "Schematic.h"
#include "CGMenuDlg.h"
#include "MyMessageBox.h"


// CCGMenuDlg ダイアログ

IMPLEMENT_DYNAMIC(CCGMenuDlg, CDialogEx)

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::CCGMenuDlg
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
CCGMenuDlg::CCGMenuDlg(CWnd* pParent /*=NULL*/)
	: CDialogEx(CCGMenuDlg::IDD, pParent)
{
}

void CCGMenuDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialogEx::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_KEITOZU, m_cListKeitozu);
	DDX_Control(pDX, IDC_LIST_SETSUMEIZU, m_cListSetsumeizu);
	DDX_Control(pDX, IDC_BUTTON_DISP_KEITOZU, m_cButtonDispKeitozu);
	DDX_Control(pDX, IDC_BUTTON_DISP_SETSUMEIZU, m_cButtonDispSetsumeizu);
}


BEGIN_MESSAGE_MAP(CCGMenuDlg, CDialogEx)
	ON_BN_CLICKED(IDC_BUTTON_EXIT, &CCGMenuDlg::OnBnClickedButtonExit)
	ON_BN_CLICKED(IDC_BUTTON_DISP_KEITOZU, &CCGMenuDlg::OnBnClickedButtonDispKeitozu)
	ON_BN_CLICKED(IDC_BUTTON_DISP_SETSUMEIZU, &CCGMenuDlg::OnBnClickedButtonDispSetsumeizu)
	ON_BN_CLICKED(IDC_BUTTON_RENZOKU, &CCGMenuDlg::OnBnClickedButtonRenzoku)
	ON_LBN_SELCHANGE(IDC_LIST_KEITOZU, &CCGMenuDlg::OnLbnSelchangeListKeitozu)
	ON_LBN_DBLCLK(IDC_LIST_KEITOZU, &CCGMenuDlg::OnLbnDblclkListKeitozu)
	ON_LBN_SELCHANGE(IDC_LIST_SETSUMEIZU, &CCGMenuDlg::OnLbnSelchangeListSetsumeizu)
	ON_LBN_DBLCLK(IDC_LIST_SETSUMEIZU, &CCGMenuDlg::OnLbnDblclkListSetsumeizu)
END_MESSAGE_MAP()


// CCGMenuDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnInitDialog
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
BOOL CCGMenuDlg::OnInitDialog()
{
	CDialogEx::OnInitDialog();

	AfxGetApp()->m_pMainWnd = this;

	// 機能系統図メニュー項目設定
	AddMenuItem(m_cListKeitozu, "FS01");
	AddMenuItem(m_cListKeitozu, "FS02");
	AddMenuItem(m_cListKeitozu, "FS03");
	AddMenuItem(m_cListKeitozu, "FS04");
	AddMenuItem(m_cListKeitozu, "FS05");
	AddMenuItem(m_cListKeitozu, "FS06");
	AddMenuItem(m_cListKeitozu, "FS07");
	AddMenuItem(m_cListKeitozu, "FS08");
	AddMenuItem(m_cListKeitozu, "FS09");
	AddMenuItem(m_cListKeitozu, "FS10");
	AddMenuItem(m_cListKeitozu, "FS11");
	AddMenuItem(m_cListKeitozu, "FS12");
	AddMenuItem(m_cListKeitozu, "FS13");
	AddMenuItem(m_cListKeitozu, "FS14");
	AddMenuItem(m_cListKeitozu, "FS15");
	AddMenuItem(m_cListKeitozu, "FS16");
	AddMenuItem(m_cListKeitozu, "FS17");
	AddMenuItem(m_cListKeitozu, "FS18");
	AddMenuItem(m_cListKeitozu, "FS19");

	// 機能説明図メニュー項目設定
	AddMenuItem(m_cListSetsumeizu, "FE07");
	AddMenuItem(m_cListSetsumeizu, "FE06");
	AddMenuItem(m_cListSetsumeizu, "FE05");
	AddMenuItem(m_cListSetsumeizu, "FE01");
	AddMenuItem(m_cListSetsumeizu, "FE02");
	AddMenuItem(m_cListSetsumeizu, "FE03");
	AddMenuItem(m_cListSetsumeizu, "FE04");

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::AddMenuItem
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リストボックスにメニュー項目を設定する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		CListBox	&cListBox			[I] リストボックス
//		LPCTSTR		pSchematicCode		[I] 図表コード
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::AddMenuItem(CListBox &cListBox, LPCTSTR pSchematicCode)
{
	SSchematicWindow *pSchematicWindow = CSchematic::GetSchematicWindowData(pSchematicCode);
	cListBox.SetItemDataPtr(cListBox.AddString(pSchematicWindow->sWindowTitle), (void *)pSchematicCode);
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnBnClickedButtonExit
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
void CCGMenuDlg::OnBnClickedButtonExit()
{
	DestroyWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnBnClickedButtonDispKeitozu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		系統図の「表示」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonDispKeitozu()
{
	int nIndex = m_cListKeitozu.GetCurSel();
	if (nIndex >= 0)
		CSchematic::OpenSchematicWindow((LPCTSTR)m_cListKeitozu.GetItemDataPtr(nIndex));
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnBnClickedButtonDispSetsumeizu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		説明図の「表示」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonDispSetsumeizu()
{
	int nIndex = m_cListSetsumeizu.GetCurSel();
	if (nIndex >= 0)
		CSchematic::OpenSchematicWindow((LPCTSTR)m_cListSetsumeizu.GetItemData(nIndex));
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnBnClickedButtonRenzoku
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		説明図の「連続表示」ボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnBnClickedButtonRenzoku()
{
	CMyMessageBox cMyMessageBox;
	if (cMyMessageBox.MessageBox("機能説明図の連続表示を開始します。\nよろしいですか？", "連続表示確認", MB_OKCANCEL | MB_ICONQUESTION) == IDOK) {
		CSchematic::RenzokuFE();
//		EnableWindow(FALSE);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnLbnSelchangeListKeitozu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		系統図の項目の選択処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnLbnSelchangeListKeitozu()
{
	m_cButtonDispKeitozu.EnableWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnLbnDblclkListKeitozu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		系統図の項目のダブルクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnLbnDblclkListKeitozu()
{
	OnBnClickedButtonDispKeitozu();
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnLbnSelchangeListSetsumeizu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		説明図の項目の選択処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnLbnSelchangeListSetsumeizu()
{
	m_cButtonDispSetsumeizu.EnableWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CCGMenuDlg::OnLbnDblclkListSetsumeizu
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		説明図の項目のダブルクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CCGMenuDlg::OnLbnDblclkListSetsumeizu()
{
	OnBnClickedButtonDispSetsumeizu();
}
