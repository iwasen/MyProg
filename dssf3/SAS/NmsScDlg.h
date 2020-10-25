#pragma once

#include "MyCtrl.h"

class CNmsScDlg : public CDialog
{
public:
	CNmsScDlg(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

	BOOL m_bNoWave;

protected:
	enum { IDD = IDD_NMS_SCREEN_COPY };

	void EnableControl();
	void SaveSetData();

	CButton	m_cMMLIB;
	CMyButton	m_cScSPL;
	CMyButton	m_cScWindow;
	CMyButton	m_cScGraph;
	CMyButton	m_cScWIACC;
	CMyButton	m_cScTauIACC;
	CMyButton	m_cScTauE;
	CMyButton	m_cScTau1;
	CMyEdit	m_cScRowSpace;
	CMyButton	m_cScPhi1;
	CMyButton	m_cScIACF;
	CMyButton	m_cScIACC;
	CMyButton	m_cScPhi0;
	CMyButton	m_cScMulti;
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
