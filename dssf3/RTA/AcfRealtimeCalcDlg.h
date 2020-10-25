#pragma once


// CAcfRealtimeCalcDlg �_�C�A���O

class CAcfRealtimeCalcDlg : public CDialog
{
public:
	CAcfRealtimeCalcDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CAcfRealtimeCalcDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ACF_REALTIME_CALC };

protected:
	CComboBox	m_cFreqFilter;
	double	m_dIntegrationTime;
	double	m_dMaxDelayTime;
	double	m_dTaueInterval;
	double	m_dTaueEndLevel;
	double	m_dTaueEndTime;
	BOOL	m_bTaueFixedScale;
	double	m_dTaueMinTime;
	double	m_dTaueMaxTime;
	int		m_iTaueScale;
	double	m_dRunningStep;
	BOOL	m_bTaueOrigin;
	int		m_iTau1Peak;
	int		m_iSpgMaxLevel;
	int		m_iSpgMinLevel;
	int		m_iSpgScale;
	CEdit m_cTaueMaxTime;
	CEdit m_cTaueMinTime;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	afx_msg void OnBnClickedTaueFixedScale();
};
