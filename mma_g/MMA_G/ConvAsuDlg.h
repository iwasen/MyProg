#pragma once

#include "myctrl.h"
#include "AsuFile.h"

// CConvAsuDlg ダイアログ

class CConvAsuDlg : public CDialog
{
	DECLARE_DYNAMIC(CConvAsuDlg)

public:
	CConvAsuDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CConvAsuDlg();

// ダイアログ データ
	enum { IDD = IDD_CONV_DATA_ASU };

	void ShowDialog(CDocument *pDoc);

protected:
	CDocument *m_pDoc;
	CAsuFile m_oAsuFile;
	CToolTipCtrl m_tooltip;

	BOOL CheckDataFile(LPCTSTR pDataFile);
	void KeyOff(void);
	void KeyOn(void);
	void EnableConvButton();

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonRef();
	afx_msg void OnBnClickedButtonConv();
	virtual void OnOK();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	CEditEx m_cEditDataFile;
	CEditEx m_cEditStartTime;
	CEditEx m_cEditEndTime;
	CEditEx m_cEditMeasTime;
	CEditEx m_cEditDownlink;
	CEditEx m_cEditComment;
	CEditEx m_cEditSampleRate;
	CButton m_cButtonRef;
	CButton m_cButtonConv;
	CButton m_cButtonCancel;
};
