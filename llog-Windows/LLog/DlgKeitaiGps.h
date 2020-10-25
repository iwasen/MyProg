#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"
#include "GoogleMap.h"


// CDlgKeitaiGps ダイアログ

class CDlgKeitaiGps : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgKeitaiGps)

public:
	CDlgKeitaiGps(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_KEITAI_GPS };

protected:
	void SetListHeader();
	void DispData();
	void DispMap(double lat, double lon);

	BOOL m_bUpdate;

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonDelete();
	afx_msg void OnBnClickedButtonSet();
	virtual void OnCancel();
	afx_msg void OnLvnItemchangedListKeitaiGps(NMHDR *pNMHDR, LRESULT *pResult);
	afx_msg void OnBnClickedButtonDestination();
	afx_msg void OnNMDblclkListKeitaiGps(NMHDR *pNMHDR, LRESULT *pResult);

	CLixxxtrl m_cListKeitaiGps;
	CEditEx m_cEditComment;
	CGoogleMap m_cStaticMap;
};
