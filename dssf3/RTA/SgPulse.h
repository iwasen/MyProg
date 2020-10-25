#pragma once

#include "MyCtrl.h"

class CSgPulse : public CDialog
{
public:
	CSgPulse(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

protected:
	enum { IDD = IDD_SG_PULSE };

	void SetPulseWidth();

	CMyEdit	m_cPulseNum;
	CMyEdit	m_cPulseCycle;
	CMyButton	m_cPulseContinue;
	CComboBox	m_cPulseWidth;
	CSpinButtonCtrl m_cPulseNumSpin;
	CSpinButtonCtrl m_cPulseCycleSpin;
	int		m_nSgPulsePolarity;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnSelchangePulseWidth();
	afx_msg void OnPulsePositive();
	afx_msg void OnPulseNegative();
	afx_msg void OnPulseContinue();
	afx_msg void OnChangePulseCycle();
	afx_msg LRESULT OnTabNotify(WPARAM wParam, LPARAM lParam);
	afx_msg void OnChangePulseNum();
	afx_msg void OnPulseBoth();
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
