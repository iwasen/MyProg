#pragma once

#include "MyCtrl.h"

class CSgNoise : public CDialog
{
public:
	CSgNoise(CWnd* pParent = NULL);   // �W���̃R���X�g���N�^

protected:
	enum { IDD = IDD_SG_NOISE };

	void SetTimeDiffRange();
	void SetTimeDiff(double fTime);
	void EnableTimeDiff();

	int		m_nSgNoiseType;
	int		m_nSgNoiseMode;
	CMySliderCtrl m_cTimeSlider;
	CMyEdit m_cTimeValue;
	CButton m_cTime0;
	CComboBox m_cTimeRange;
	CSpinButtonCtrl m_cTimeValueSpin;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnWhiteNoise();
	afx_msg void OnPinkNoise();
	afx_msg void OnMono();
	afx_msg void OnStreo();
	afx_msg void OnInverse();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnVScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnEnChangeTimeValue();
	afx_msg void OnBnClickedTime0();
	afx_msg void OnBnClickedBrownNoise();
	afx_msg void OnCbnSelchangeTimeRange();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
};
