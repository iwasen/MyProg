#pragma once

#include "MmeFile.h"
#include "MyCtrl.h"

// CConvMmeDlg ダイアログ

class CConvMmeDlg : public CDialog
{
	DECLARE_DYNAMIC(CConvMmeDlg)

public:
	CConvMmeDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CConvMmeDlg();

// ダイアログ データ
	enum { IDD = IDD_CONV_DATA_MME };

	void ShowDialog(CDocument *pDoc);

protected:
	CDocument *m_pDoc;
	CMmeFile m_oMmeFile;
	CToolTipCtrl m_tooltip;

	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);
	BOOL CheckDataFile(LPCTSTR pDataFolder);
	void KeyOff(void);
	void KeyOn(void);
	void EnableConvButton();

	CEditEx m_cEditDataFolder;
	CButton m_cButtonRef;
	CButton m_cButtonConv;
	CButton m_cButtonCancel;
	CEditEx m_cEditStartTime;
	CEditEx m_cEditEndTime;
	CEditEx m_cEditMeasTime;
	CEditEx m_cEditDownlink;
	CEditEx m_cEditComment;

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonRef();
	afx_msg void OnBnClickedButtonConv();
	afx_msg void OnEnUpdateEditDownlink();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	virtual void OnOK();
};
