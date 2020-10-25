#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"


// CDlgBoard ダイアログ

class CDlgBoard : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgBoard)

public:
	CDlgBoard(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_BOARD };

protected:
	void DispData();

	BOOL m_bUpdate;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAdd();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnBnClickedButtonUpdate();
	virtual void OnCancel();
	afx_msg void OnLbnSelchangeListBoard();

	CListBox m_cListBoard;
	CEditEx m_cEditBoard;
};
