#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		DigitalDataDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デジタル数値表示ダイアログクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "DialogEx.h"
#include "MyCtrl.h"
#include "DataFile.h"

// CDigitalDataDlg ダイアログ

class CDigitalDataDlg : public CDialogEx
{
	DECLARE_DYNAMIC(CDigitalDataDlg)

public:
	CDigitalDataDlg(CWnd* pParent = NULL);   // 標準コンストラクタ

	enum { IDD = IDD_DIGITAL_DATA };

	void DispList(LPCTSTR pFilePath, int nDataType);

protected:
	void SetListHeader();
	void DispData(int nPageNo);
	void PrintData(CDC &dc, CRect rectView, LPCTSTR pTitle, CLixxxtrl &cLixxxtrl);
	void PrintInfo(CDC &dc, CRect rectView, LPCTSTR pTitle, LPCTSTR pTitle2);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnCancel();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnBnClickedButtonPrevPage();
	afx_msg void OnBnClickedButtonNextPage();
	afx_msg void OnCbnSelchangeComboSampleRate();
	afx_msg void OnBnClickedButtonPrint();
	afx_msg void OnBnClickedButtonSelectAll();

protected:
	CString m_sFilePath;
	int m_nBeginTime;
	int m_nEndTime;
	CDataFile m_cDataFile;
	int m_nPageNum;
	int m_nPageNo;
	CToolTipCtrl m_tooltip;

	CLixxxtrl m_cListData;
	CEditEx m_cEditPageNo;
	CButton m_cButtonPrevPage;
	CButton m_cButtonNextPage;
	CComboBox m_cComboSampleRate;
};
