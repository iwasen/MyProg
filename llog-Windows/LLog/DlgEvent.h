#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgEvent ダイアログ

class CDlgEvent : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgEvent)

public:
	CDlgEvent(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_EVENT };

protected:
	void DispData();

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAdd();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnLbnDblclkListEventFrom();
	afx_msg void OnLbnDblclkListEventTo();
	virtual void OnOK();

	CEditEx m_cEditDate;
	CListBox m_cListEventFrom;
	CListBox m_cListEventTo;
};
