#pragma once

#include "DstFreqWnd.h"
#include "MyCtrl.h"
#include "DialogEx.h"

class CDstFreq : public CDialogEx
{
public:
	CDstFreq(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CDstFreq();

	void Initialize();
	BOOL WaveOutData(int &nFreq, double &fLevel);
	BOOL NotifyTHD(const double *pLeftDst, const double *pRightDst);
	void Redraw();
	void CsvOutput(LPCTSTR pFileName);
	BOOL CheckDataExist();
	BOOL CheckDataHold();
	HBITMAP GetBitmap();
	void AddDataHold(COLORREF colorLeft, COLORREF colorRight);
	void DelDataHold(BOOL bRedraw = TRUE);

protected:
	enum { IDD = IDD_DST_FREQ };

	struct SDataHold {
		COLORREF colorLeft;
		COLORREF colorRight;
		int nFreqPoint;
		int nFreqCount;
		double *pLeftDst[3];
		double *pRightDst[3];
		double *pFreq;
	};

	void InitLevelSlider();
	void SetLevel(int nLevel);
	void FreeBuffers();
	void DispTHD(double fLeftTHD, double fRightTHD);
	void DispUnit();

	double *m_pLeftDst[3];
	double *m_pRightDst[3];
	double *m_pFreq;
	double m_fFreqCurrent;
	double m_fFreqStep;
	int m_nFreqPoint;
	int m_nFreqCount;
	int m_nFreqStart;
	int m_nFreqEnd;
	BOOL m_bValidData;
	CSliderCtrl m_cLevelSlider;
	CMyEdit m_cLevelEdit;
	CMyEdit m_cLeftTHD;
	CMyEdit m_cRightTHD;
	CDstFreqWnd m_cGraph;
	CStatic m_cLeftUnit;
	CStatic m_cRightUnit;
	CMyEdit m_cFreqStart;
	CMyEdit m_cFreqEnd;
	CMyEdit m_cFreqPoint;
	CMyEdit m_cFreqCurrent;
	CMyButton m_cFreqSpline;
	CMyButton m_cChkMarker;
	CComboBox m_cComboHD;
	BOOL m_bInitialized;
	CList<SDataHold,SDataHold&> m_oDataHoldList;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	virtual void OnOK();
	virtual void OnCancel();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg void OnEnChangeLevelEdit();
	afx_msg void OnEnChangeFreqStart();
	afx_msg void OnEnChangeFreqEnd();
	afx_msg void OnEnChangeFreqPoint();
	afx_msg void OnBnClickedFreqSpline();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg void OnBnClickedChkMarker();
	afx_msg void OnCbnSelchangeComboHd();
	afx_msg LRESULT OnTabInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
