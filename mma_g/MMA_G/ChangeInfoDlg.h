#pragma once

#include "IdbFile.h"
#include "MyCtrl.h"


// CChangeInfoDlg ダイアログ

class CChangeInfoDlg : public CDialog
{
	DECLARE_DYNAMIC(CChangeInfoDlg)

public:
	CChangeInfoDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CChangeInfoDlg();

// ダイアログ データ
	enum { IDD = IDD_CHANGE_INFO };

	CString m_sIdbPathName;

protected:
	CIdbFile m_oIdbFile;
	CToolTipCtrl m_tooltip;

	BOOL FindFileName(LPCTSTR pExt, CString &sFileName);
// 2009/07/07 Version 3.02 appended by m.hama ->
	void WriteXmlFile(void);
// <- 2009/07/07 Version 3.02 appended by m.hama
	BOOL ChangeFolder();

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

public:
	INT_PTR ChangeInfo(const CString &sIdbPathName);

protected:
	CComboBox m_cComboSensor;
	CEditEx m_cEditDownlink;
	CEditEx m_cEditQuality;
	CEditEx m_cEditIss;
	CEditEx m_cEditIss2;
	CEditEx m_cEditComment;
	CComboBox m_cComboCategory;
	CComboBox m_cComboMeasurementKind;

protected:
	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
};
