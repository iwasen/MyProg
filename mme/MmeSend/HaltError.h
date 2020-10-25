#pragma once
#include "afxwin.h"


// CHaltError ダイアログ

class CHaltError : public CDialog
{
	DECLARE_DYNAMIC(CHaltError)

public:
	CHaltError(CWnd* pParent, SLasttimeHalt &oLasttimeHalt);   // 標準コンストラクタ
	virtual ~CHaltError();

// ダイアログ データ
	enum { IDD = IDD_HALT_ERROR };

protected:
	SLasttimeHalt &m_oLasttimeHalt;

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	CString m_sEditSessionNo;
	CString m_sEditType;
	CString m_sEditNature;
	CString m_sEditTaskName;
	CString m_sEditErrorCode;
	CString m_sEditTime;
	virtual BOOL OnInitDialog();
	virtual void OnOK();
};
