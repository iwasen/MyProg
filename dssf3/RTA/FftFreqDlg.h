#pragma once

#include "FftDlg.h"

// CFfrFreqDlg �_�C�A���O

class CFftFreqDlg : public CDialog
{
public:
	CFftFreqDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CFftFreqDlg();

	CFftDlg *m_pFftDlg;
	int m_nMaxRange;
	int m_nMinRange;
	int m_nMaxFreq;
	int m_nMinFreq;

// �_�C�A���O �f�[�^
	enum { IDD = IDD_FFT_FREQ };

protected:
	int CalcSliderPos(double fFreq);
	int CalcFreq(int pos);
	void SetFreqScale(CSliderCtrl &oSliderCtrl);

	CSliderCtrl m_cFftMaxFreq;
	CSliderCtrl m_cFftMinFreq;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnDestroy();
	afx_msg void OnNcDestroy();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
};
