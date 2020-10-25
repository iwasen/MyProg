#pragma once

#include "DstManualWnd.h"
#include "MyCtrl.h"
#include "DialogEx.h"

class CDstManual : public CDialogEx
{
public:
	CDstManual(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CDstManual();

	void Initialize();
	BOOL WaveOutData(int &nFreq, double &fLevel);
	BOOL NotifyTHD(const double *pLeftDst, const double *pRightDst);
	void Redraw();
	void CsvOutput(LPCTSTR pFileName);
	BOOL CheckDataExist();
	HBITMAP GetBitmap();

protected:
	enum { IDD = IDD_DST_MANUAL };

	void InitFreqSlider();
	void InitLevelSlider();
	void SetFreq(int nFreq);
	void SetLevel(int nLevel);
	int GetPosFromFreq(int nFreq);
	int GetFreqFromPos(int nPos);
	void FreeBuffers();
	void DispTHD();

	double *m_pLeftDst;
	double *m_pRightDst;
	BOOL m_bValidData;
	int m_nChannel;
	int m_nMaxHxxxonics;
	CSliderCtrl m_cFreqSlider;
	CMyEdit m_cFreqEdit;
	CSliderCtrl m_cLevelSlider;
	CMyEdit m_cLevelEdit;
	int m_nAverageCnt;
	CMyEdit m_cLeftTHD;
	CMyEdit m_cRightTHD;
	CDstManualWnd m_cGraph;
	CStatic m_cLeftUnit;
	CStatic m_cRightUnit;
	CMyButton m_cAverage;
	BOOL m_bInitialized;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnEnChangeFreqEdit();
	afx_msg void OnEnChangeLevelEdit();
	afx_msg void OnBnClickedAverage();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg LRESULT OnTabInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
