#pragma once

#include "FftDlg.h"

// CFftLevelDlg �_�C�A���O

class CFftLevelDlg : public CDialog
{
public:
	CFftLevelDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CFftLevelDlg();

	CFftDlg *m_pFftDlg;
	int m_nMaxRange;
	int m_nMinRange;
	int m_nMaxLevel;
	int m_nMinLevel;

// �_�C�A���O �f�[�^
	enum { IDD = IDD_FFT_LEVEL };

protected:
	CSliderCtrl m_cFftMaxLevel;
	CSliderCtrl m_cFftMinLevel;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnDestroy();
	afx_msg void OnNcDestroy();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
};
