#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgWalking ダイアログ

class CDlgWalking : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgWalking)

public:
	CDlgWalking(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_WALKING };

protected:
	CString SetPoint(CString sNumber, int nPoint);
	CString RemovePoint(CString sNumber);

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedButtonAllZero();

	CEditEx m_cEditDate;
	CEditEx m_cEditWalkingStep;
	CEditEx m_cEditWalkingCalorie;
	CEditEx m_cEditWalkingFat;
	CEditEx m_cEditWalkingDistance;
	CEditEx m_cEditWalkingTime;
	CEditEx m_cEditWalkingSpeed;
};
