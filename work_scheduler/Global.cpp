//*****************************************************************************************************
//  1. ファイル名
//		Global.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CGlobal クラスのインプリメンテーション
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "workscheduler.h"
#include "Global.h"
#include "shlwapi.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//*****************************************************************************************************
//  1. 関数名
//		CGlobal::SetComboBoxFromStringArray
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		文字列配列からコンボボックスに設定
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CComboBox		&cComboBox			[O] コンボボックス
//		CStringArray	&cStringArray		[I] コンボボックスに設定する文字列配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		文字列配列の全要素をコンボボックスに設定する。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
// 文字列配列からコンボボックスに設定
void CGlobal::SetComboBoxFromStringArray(CComboBox &cComboBox, CStringArray &cStringArray)
{
	int nSize = cStringArray.GetSize();
	for (int i = 0; i < nSize; i++)
		cComboBox.AddString(cStringArray[i]);
}

//*****************************************************************************************************
//  1. 関数名
//		CGlobal::SelectComboBox
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンボボックスの指定された文字列を選択状態にする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CComboBox		&cComboBox		[I] コンボボックス
//		LPCTSTR			pStr			[I] 選択する文字列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL			TRUE：設定した，FALSE：見つからなかった
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		コンボボックスの中から指定された文字列を選択状態にする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CGlobal::SelectComboBox(CComboBox &cComboBox, LPCTSTR pStr)
{
	int nIndex = cComboBox.FindStringExact(-1, pStr);
	if (nIndex != CB_ERR) {
		cComboBox.SetCurSel(nIndex);
		return TRUE;
	} else
		return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGlobal::SelectComboBox
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンボボックスの指定されたデータを選択状態にする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CComboBox		&cComboBox		[I/O] コンボボックス
//		DWORD			dwData			[I] 選択するデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL			TRUE：設定した，FALSE：見つからなかった
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		コンボボックスの中から指定されたデータをもつ項目を選択状態にする。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CGlobal::SelectComboBox(CComboBox &cComboBox, DWORD dwData)
{
	int nCount = cComboBox.GetCount();
	for (int i = 0; i < nCount; i++) {
		if (cComboBox.GetItemData(i) == dwData) {
			cComboBox.SetCurSel(i);
			return TRUE;
		}
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGlobal::FileSaveDlg
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル保存ダイアログ表示
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pDir			[I] デフォルトディレクトリ
//		LPCTSTR		pFilter			[I] ファイルを特定するためのフィルタ
//		LPCTSTR		pFName			[I] デフォルトファイル名
//		LPCTSTR		pTitle			[I] ダイアログタイトル
//		LPCTSTR		pDefaultExt		[I] デフォルト拡張子
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		入力されたファイル名
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		ファイル保存ダイアログを表示し、そこで入力されたファイル名を返す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CString CGlobal::FileSaveDlg(LPCTSTR pDir, LPCTSTR pFilter, LPCTSTR pFName, LPCTSTR pTitle, LPCTSTR pDefaultExt)
{
	CFileDialog cDlgFileOpen(FALSE, pDefaultExt, pFName, OFN_HIDEREADONLY | OFN_NOCHANGEDIR | OFN_EXPLORER | OFN_OVERWRITEPROMPT, pFilter, AfxGetMainWnd());

#if _MSC_VER <= 1200
	// WIndows2000以上はダイアログの左側にショートカットを表示
	OSVERSIONINFO osvi;
	ZeroMemory(&osvi, sizeof(OSVERSIONINFO));
	osvi.dwOSVersionInfoSize = sizeof(OSVERSIONINFO);
	GetVersionEx(&osvi);
	if (osvi.dwMajorVersion >= 5)
		cDlgFileOpen.m_ofn.lStructSize += 12;
#endif

	cDlgFileOpen.m_ofn.lpstrInitialDir = pDir;
	cDlgFileOpen.m_ofn.lpstrTitle = pTitle;

	if (cDlgFileOpen.DoModal() == IDCANCEL)
		return "";

	return cDlgFileOpen.GetPathName();
}

CString CGlobal::FileOpenDlg(LPCTSTR pDir, LPCTSTR pFilter, LPCTSTR pFName, LPCTSTR pTitle, LPCTSTR pDefaultExt)
{
	CFileDialog cDlgFileOpen(TRUE, pDefaultExt, pFName, OFN_HIDEREADONLY | OFN_NOCHANGEDIR | OFN_EXPLORER, pFilter, AfxGetMainWnd());

#if _MSC_VER <= 1200
	// WIndows2000以上はダイアログの左側にショートカットを表示
	OSVERSIONINFO osvi;
	ZeroMemory(&osvi, sizeof(OSVERSIONINFO));
	osvi.dwOSVersionInfoSize = sizeof(OSVERSIONINFO);
	GetVersionEx(&osvi);
	if (osvi.dwMajorVersion >= 5)
		cDlgFileOpen.m_ofn.lStructSize += 12;
#endif

	cDlgFileOpen.m_ofn.lpstrInitialDir = pDir;
	cDlgFileOpen.m_ofn.lpstrTitle = pTitle;

	if (cDlgFileOpen.DoModal() == IDCANCEL)
		return "";

	return cDlgFileOpen.GetPathName();
}

// 文字列リソースIDから文字列取得
CString CGlobal::GetString(UINT nID)
{
	CString sText;

	sText.LoadString(nID);

	return sText;
}

void CGlobal::FindFiles(LPCTSTR pPathName, CStringArray &aPathName)
{
	aPathName.RemoveAll();

	// ディレクトリ内のファイルを検索
	CFileFind cFileFind;
	BOOL bNext;
	if (cFileFind.FindFile(pPathName)) {
		do {
			bNext = cFileFind.FindNextFile();
			aPathName.Add(cFileFind.GetFilePath());
		} while(bNext);
	}
}

BOOL CGlobal::SelectFolderDialog(LPCSTR pInitFolder, CString &sSelectedFolder)
{
	BROWSEINFO bInfo;
	LPITEMIDLIST pIDList;
	TCHAR szDisplayName[MAX_PATH];
	TCHAR szFullPath[MAX_PATH];

	// デフォルトのフォルダをiniファイルから取得
	CString sDataFolder = pInitFolder;
	if (_fullpath(szFullPath, sDataFolder, sizeof(szFullPath)) != NULL)
		sDataFolder = szFullPath;


	// BROWSEINFO構造体に値を設定
	bInfo.hwndOwner             = AfxGetMainWnd()->m_hWnd;			// ダイアログの親ウインドウのハンドル
	bInfo.pidlRoot              = NULL;								// ルートフォルダを示すITEMIDLISTのポインタ (NULLの場合デスクトップフォルダが使われます）
	bInfo.pszDisplayName        = szDisplayName;					// 選択されたフォルダ名を受け取るバッファのポインタ
	bInfo.lpszTitle             = _T("プロジェクトフォルダの選択");	// ツリービューの上部に表示される文字列
// Modify ... ( CHANGE )
	bInfo.ulFlags               = BIF_RETURNONLYFSDIRS | 0x0040 | 0x0200;	// 0x0040:BIF_NEWDIALOGSTYLE
																			// 0x0200:BIF_NONEWFOLDERBUTTON
																	// 表示されるフォルダの種類を示すフラグ
//	bInfo.ulFlags               |= BIF_RETURNONLYFSDIRS;			// 表示されるフォルダの種類を示すフラグ
// By Y.Itabashi (xxxxx) 2007.03.02
	bInfo.lpfn                  = BrowseCallbackProc;				// BrowseCallbackProc関数のポインタ
	bInfo.lParam                = (LPARAM)(LPCTSTR)sDataFolder;		// コールバック関数に渡す値

	// フォルダ選択ダイアログを表示
	pIDList = ::SHBrowseForFolder(&bInfo);
	if (pIDList == NULL)
		return FALSE;

	// ItemIDListをパス名に変換
	::SHGetPathFromIDList(pIDList, szDisplayName);

	// IDListのポイントしているメモリを開放
	::CoTaskMemFree( pIDList );

	// フォルダ名保存
	sSelectedFolder = szDisplayName;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CGlobal::BrowseCallbackProc
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		初期フォルダを指定するためのコールバック関数
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		HWND		hwnd			[I] ウィンドウハンドル
//		UINT		uMsg			[I] イベントの種類
//		LPARAM		lParam			[I] 未使用
//		LPARAM		lpData			[I] BROWSEINFO構造体へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		0
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		初期化イベントのときに初期フォルダを選択させる。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CALLBACK CGlobal::BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData)
{
	if (uMsg == BFFM_INITIALIZED)
		::SendMessage(hwnd, BFFM_SETSELECTION, (WPARAM)TRUE, lpData);

	return 0;
}

CString CGlobal::MakePathName(LPCTSTR pFolderName, LPCTSTR pFileName)
{
	CString sFolderName;
	CString sPathName;

	_fullpath(sFolderName.GetBuffer(MAX_PATH), pFolderName, MAX_PATH);
	sFolderName.ReleaseBuffer();

	sPathName.Format("%s\\%s", sFolderName, pFileName);

	return sPathName;
}

CString CGlobal::GetFileNameFromPath(LPCTSTR pPathName)
{
	CString sPathName(pPathName);

	::PathStripPath(sPathName.GetBuffer(0));
	sPathName.ReleaseBuffer();

	return sPathName;
}

CString CGlobal::GetFolderNameFromPath(LPCTSTR pPathName)
{
	CString sPathName(pPathName);

	::PathRemoveFileSpec(sPathName.GetBuffer(0));
	sPathName.ReleaseBuffer();

	return sPathName;
}

CString CGlobal::ChangeFolderName(LPCTSTR pFolderName, LPCTSTR pPathName)
{
	return MakePathName(pFolderName, GetFileNameFromPath(pPathName));
}
