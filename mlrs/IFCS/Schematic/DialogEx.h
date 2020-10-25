#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		DialogEx.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		拡張ダイアログクラス定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************


// CDialogEx ダイアログ

class CDialogEx : public CDialog
{
	DECLARE_DYNAMIC(CDialogEx)

public:
	CDialogEx(UINT nID, CWnd* pParent);   // 標準コンストラクタ

	BOOL DoModeless(CWnd *pParent = NULL);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual void OnCancel();
	virtual void OnOK();

	DECLARE_MESSAGE_MAP()
	virtual void PostNcDestroy();

protected:
	UINT m_nID;					// ダイアログリソースID
	BOOL m_bModeless;			// モードレスフラグ
};
