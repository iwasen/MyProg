#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgComment ダイアログ

class CDlgComment : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgComment)

public:
	CDlgComment(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_COMMENT };

	int m_nSeqNo;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();

	CEditEx m_cEditDate;
	CEditEx m_cEditComment;
};
