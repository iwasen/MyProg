#pragma once

#include "DialogEx.h"

// CFftBaseDlg ダイアログ

class CFftBaseDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CFftBaseDlg)

public:
	CFftBaseDlg(UINT nID, CWnd* pParent);   // 標準コンストラクタ
	virtual ~CFftBaseDlg();

protected:
	HWND m_hWndParent;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnDestroy();
};
