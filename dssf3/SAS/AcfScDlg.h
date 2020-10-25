#pragma once

#include "MyCtrl.h"

class CAcfScDlg : public CDialog
{
public:
	CAcfScDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	BOOL m_bNoWave;
	int m_nChannel;

protected:
	enum { IDD = IDD_ACF_SCREEN_COPY };

	void EnableControl();
	void SaveSetData();

	CButton	m_cMMLIB;
	CMyButton	m_cScTauIACC;
	CMyButton	m_cScWIACC;
	CMyButton	m_cScIACF;
	CMyButton	m_cScWindow;
	CMyButton	m_cScGraph;
	CMyButton	m_cScTauE;
	CMyButton	m_cScTau1;
	CMyEdit	m_cScRowSpace;
	CMyButton	m_cScPhi1;
	CMyButton	m_cScPhi0;
	CMyButton	m_cScMulti;
	CMyButton	m_cScIACC;
	CMyButton	m_cScFrame;
	CMyEdit	m_cScColumns;
	CMyEdit	m_cScColSpace;
	CMyButton	m_cScACF;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnScWindow();
	afx_msg void OnScGraph();
	afx_msg void OnScMulti();
	afx_msg void OnMmlib();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
