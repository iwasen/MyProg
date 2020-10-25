#pragma once

#include "MyCtrl.h"


// AcfMarkerDlg ダイアログ

class CAcfMarkerDlg : public CDialog
{
	DECLARE_DYNAMIC(CAcfMarkerDlg)

public:
	CAcfMarkerDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CAcfMarkerDlg();

// ダイアログ データ
	enum { IDD = IDD_ACF_MARKER };

	long m_nAcfID;
	long m_nAcfMarkerID;
	double m_fTime;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();

	CMyEdit m_cEditTitle;
	CMyEdit m_cEditComment;
};
