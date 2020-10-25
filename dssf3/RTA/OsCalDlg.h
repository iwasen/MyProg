#pragma once

#include "MyCtrl.h"
#include "WaveNotify.h"


// COsCalDlg �_�C�A���O

class COsCalDlg : public CDialog, public IWaveNotify
{
public:
	COsCalDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~COsCalDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_OS_CAL };

protected:
	int m_iCalType;
	CMyEdit m_cEditCalValue;
	CMyEdit m_cEditUnit;
	CButton m_cOK;
	class CWaveInEx *m_pWaveIn;
	double *m_pWaveData;

	double GetCalLevel();
	int OnWaveNotify(int nCode, LPWAVENOTIFY pWaveNotify);

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedButtonDisable();
	afx_msg void OnDestroy();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
