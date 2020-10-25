#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		Global.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CGlobal クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

// 汎用関数群
class CGlobal
{
public:
	static void SetComboBoxFromStringArray(CComboBox &cComboBox, CStringArray &cStringArray);
	static BOOL SelectComboBox(CComboBox &cComboBox, LPCTSTR pStr);
	static BOOL SelectComboBox(CComboBox &cComboBox, DWORD dwData);
	static CString FileSaveDlg(LPCTSTR pDir, LPCTSTR pFilter, LPCTSTR pFName, LPCTSTR pTitle, LPCTSTR pDefaultExt);
	static CString FileOpenDlg(LPCTSTR pDir, LPCTSTR pFilter, LPCTSTR pFName, LPCTSTR pTitle, LPCTSTR pDefaultExt);
	static CString GetString(UINT nID);
	static void FindFiles(LPCTSTR pPathName, CStringArray &aPathName);
	static BOOL SelectFolderDialog(LPCSTR pInitFolder, CString &sSelectedFolder);
	static CString MakePathName(LPCTSTR pFolderName, LPCTSTR pFileName);
	static CString GetFileNameFromPath(LPCTSTR pPathName);
	static CString GetFolderNameFromPath(LPCTSTR pPathName);
	static CString ChangeFolderName(LPCTSTR pFolderName, LPCTSTR pPathName);

protected:
	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);
};
