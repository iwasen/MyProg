#pragma once

#define SEARCH_PHOTO		1
#define SEARCH_GPS_LOGGER	2
#define SEARCH_KEITAI_GPS	3

// CDlgSearch ダイアログ

class CDlgSearch : public CDialog
{
	DECLARE_DYNAMIC(CDlgSearch)

public:
	CDlgSearch(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_SEARCH };

	int m_nSearchType;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	afx_msg void OnBnClickedButtonSearchPhoto();
	afx_msg void OnBnClickedButtonSearchGpsLogger();
	afx_msg void OnBnClickedButtonSearchKeitaiGps();
};
