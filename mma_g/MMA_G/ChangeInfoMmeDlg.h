#pragma once

#include "IdbFile.h"
#include "MyCtrl.h"


// CChangeInfoMmeDlg ダイアログ

class CChangeInfoMmeDlg : public CDialog
{
	DECLARE_DYNAMIC(CChangeInfoMmeDlg)

public:
	CChangeInfoMmeDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CChangeInfoMmeDlg();

// ダイアログ データ
	enum { IDD = IDD_CHANGE_INFO_MME };

	CString m_sIdbPathName;
	CIdbFile m_oIdbFile;
	CToolTipCtrl m_tooltip;

	INT_PTR ChangeInfo(const CString &sIdbPathName);

protected:
	BOOL FindFileName(LPCTSTR pExt, CString &sFileName);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	CEditEx m_cEditDownlink;
	CEditEx m_cEditComment;
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
};
