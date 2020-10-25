#pragma once

#include "SetData.h"

class CCalcNmsDlg : public CDialog
{
public:
	CCalcNmsDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	AcfCondition m_AcfCondition;
	IdentCondition m_IdentCondition;
	long m_nFolderID;
	int m_iCalcData;

protected:
	enum { IDD = IDD_CALC_NMS };

	void EnableControl();

	CEdit	m_cIdentBeforeStep;
	CEdit	m_cIdentAfterStep;
	CEdit	m_cPhi0RefLevel;
	CComboBox	m_cFreqFilter;
	double	m_dIntegrationTime;
	double	m_dMaxDelayTime;
	double	m_dTaueInterval;
	double	m_dTaueEndLevel;
	double	m_dTaueEndTime;
	double	m_dRunningStep;
	BOOL	m_bTaueOrigin;
	int		m_iIdentAfterStep;
	int		m_iIdentBeforeStep;
	int		m_iIdentMode;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnIdentSpl();
	afx_msg void OnIdentTaue();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
