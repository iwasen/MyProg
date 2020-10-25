#pragma once

#include "MyCtrl.h"

class CManualMode : public CDialog
{
public:
	CManualMode(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	void NotifyMeasuring(BOOL bStart);
	void NotifyElpseTime(double fTime);

	CNmsDoc *m_pDoc;

protected:
	enum { IDD = IDD_MANUAL_MODE };

	CMyEdit	m_cSamplingTime;
	CMyButton	m_cStartStop;
	CMyEdit	m_cElpseTime;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual void PostNcDestroy();
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnDestroy();
	afx_msg void OnChangeSamplingTime();
	afx_msg void OnStartStop();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
