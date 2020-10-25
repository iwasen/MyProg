//*****************************************************************************************************
//  1. ファイル名
//		ChangeInfoAsuDlg.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ASUデータの情報変更ダイアログの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2016.02.08 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "MMA_G.h"
#include "ChangeInfoAsuDlg.h"
#include "General.h"
#include <io.h>


// CChangeInfoAsuDlg ダイアログ

IMPLEMENT_DYNAMIC(CChangeInfoAsuDlg, CDialog)

CChangeInfoAsuDlg::CChangeInfoAsuDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CChangeInfoAsuDlg::IDD, pParent)
{

}

CChangeInfoAsuDlg::~CChangeInfoAsuDlg()
{
}

void CChangeInfoAsuDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_DOWNLINK, m_cEditDownlink);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
}


BEGIN_MESSAGE_MAP(CChangeInfoAsuDlg, CDialog)
END_MESSAGE_MAP()

// ツールチップ表示データ
static const SToolTipData s_aToolTip[] = {
	{IDOK, "設定された内容にデータファイルを書き換えます。"},
	{IDCANCEL, "このダイアログを閉じます。"},
	{0, NULL}
};

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoAsuDlg::ChangeInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		情報変更ダイアログ表示処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		&sIdbPathName			[I] Idbファイルパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		INT_PTR		ダイアログの返値
//*****************************************************************************************************
INT_PTR CChangeInfoAsuDlg::ChangeInfo(const CString &sIdbPathName)
{
	// Idbファイルパスを保存
	m_sIdbPathName = sIdbPathName;

	// ダイアログを開く
	return DoModal();
}

// CChangeInfoAsuDlg メッセージ ハンドラ

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoAsuDlg::OnInitDialog
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
BOOL CChangeInfoAsuDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// idbファイル読み込み
	m_oIdbFile.Read(m_sIdbPathName);

	// ダイアログに表示
	m_cEditDownlink = m_oIdbFile.m_downlink;
	m_cEditComment = m_oIdbFile.m_comment;

	// ツールチップ表示設定
	CGeneral::SetToolTip(m_tooltip, s_aToolTip, this);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoAsuDlg::OnOK
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		OKボタンクリック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CChangeInfoAsuDlg::OnOK()
{
	// ダイアログからidbデータに設定
	m_oIdbFile.m_downlink = m_cEditDownlink;
	m_oIdbFile.m_comment = m_cEditComment;

	// idbファイル更新
	CString sFileName;
	if (FindFileName("idb", sFileName))
		m_oIdbFile.Write(sFileName.Left(sFileName.GetLength() - 4));

	AfxMessageBox("情報を変更しました。", MB_OK | MB_ICONINFORMATION);

	CDialog::OnOK();
}

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoAsuDlg::FindFileName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		指定された拡張子のファイル名検索処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pExt			[I] 拡張子
//		CString		&sFileName		[O] 検索されたファイル名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CChangeInfoAsuDlg::FindFileName(LPCTSTR pExt, CString &sFileName)
{
	CString sPath;
	struct _finddata_t c_file;
    intptr_t hFile;

	// 拡張子が".s"のファイルを探す
	sPath.Format("%s\\*.%s", m_sIdbPathName, pExt);
	if ((hFile = _findfirst(sPath, &c_file )) != -1L) {
		sFileName = c_file.name;
		_findclose(hFile);
		return TRUE;
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CChangeInfoAsuDlg::PreTranslateMessage
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ウィンドウメッセージ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		MSG*	pMsg			[I] MSG構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	親クラスの返値をそのまま返す
//*****************************************************************************************************
BOOL CChangeInfoAsuDlg::PreTranslateMessage(MSG* pMsg)
{
	// ツールチップを表示するための処理
	switch(pMsg->message){
	case WM_LBUTTONDOWN:            
	case WM_LBUTTONUP:              
	case WM_MOUSEMOVE:
		if (m_tooltip.m_hWnd)
			m_tooltip.RelayEvent(pMsg);
		break;
	}

	return CDialog::PreTranslateMessage(pMsg);
}
