#pragma once

#include "GoogleMap.h"
#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgGpsLogger ダイアログ

class CDlgGpsLogger : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgGpsLogger)

public:
	CDlgGpsLogger(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_GPS_LOGGER };

protected:
	BOOL m_bUpdate;

	void DispData();

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnCbnSelchangeComboGpsLogger();
	afx_msg void OnDropFiles(HDROP hDropInfo);
	virtual void OnCancel();
	afx_msg void OnBnClickedButtonDelete();

	CGoogleMap m_cStaticGpsLogger;
	CComboBox m_cComboGpsLogger;
	CEditEx m_cEditDate;
public:
	afx_msg void OnBnClickedButtonPhotoLocation();
};
