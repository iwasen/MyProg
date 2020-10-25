#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		MyMessageBox.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		メッセージボックス表示処理
//----------------------------------------------------------------------------------------------------
//  3. 備考
//*****************************************************************************************************

#include "DialogEx.h"

// CMyMessageBox ダイアログ

#define MB_OK_END					0x00000007L
#define MB_OIL						0x00000008L

#define WM_MY_MESSAGE_BOX			(WM_APP + 1)

class CMyMessageBox : public CDialogEx
{
	DECLARE_DYNAMIC(CMyMessageBox)

public:
	CMyMessageBox(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_MY_MESSAGE_BOX };

public:
	int MessageBox(LPCTSTR lpszText, LPCTSTR lpszCaption ="", UINT nType = MB_OK);
	void ModelessMessage(CWnd *pWnd, LPCTSTR lpszText, LPCTSTR lpszCaption ="", UINT nType = MB_OK);
	void ChangeMessage(LPCTSTR lpszText, UINT nType);
	void CloseMessage();

protected:
	CString m_sMessage;			// 表示メッセージ
	CString m_sTitle;			// ウィンドウタイトル
	UINT m_nType;				// アイコンとボタンの種類
	CRect m_rectWindow;			// ウィンドウの位置
	CRect m_rectClient;			// クライアント領域サイズ
	CRect m_rectButton1, m_rectButton2, m_rectButton3;	// ボタン
	CRect m_rectMessage;		// メッセージ表示領域
	int m_nButton1, m_nButton2, m_nButton3;	// ボタンのID
	BOOL m_bModal;				// モーダルフラグ

	void DispMessage();
	void ClickedButton(int nButton);

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	CStatic m_cStaticIcon;
	CStatic m_cStaticMessage;
	CButton m_cButton1;
	CButton m_cButton2;
	CButton m_cButton3;
	afx_msg void OnBnClickedButton1();
	afx_msg void OnBnClickedButton2();
	afx_msg void OnBnClickedButton3();
	virtual void OnCancel();
	afx_msg void OnDestroy();
};
