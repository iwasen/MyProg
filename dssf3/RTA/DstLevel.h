#pragma once

#include "DstLevelWnd.h"
#include "MyCtrl.h"
#include "DialogEx.h"

class CDstLevel : public CDialogEx
{
public:
	CDstLevel(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CDstLevel();

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
	enum { IDD = IDD_DST_LEVEL };

	struct SDataHold {
		COLORREF colorLeft;
		COLORREF colorRight;
		int nLevelPoint;
		int nLevelCount;
		double *pLeftDst[3];
		double *pRightDst[3];
		double *pLevel;
	};

	void InitFreqSlider();
	void SetFreq(int nFreq);
	int GetPosFromFreq(int nFreq);
	int GetFreqFromPos(int nPos);
	void FreeBuffers();
	void DispTHD(double fLeftTHD, double fRightTHD);
	void DispUnit();

	double *m_pLeftDst[3];
	double *m_pRightDst[3];
	double m_fLevelCurrent;
	double m_fLevelStep;
	double *m_pLevel;
	int m_nLevelPoint;
	int m_nLevelCount;
	int m_nLevelStart;
	int m_nLevelEnd;
	BOOL m_bValidData;
	CSliderCtrl m_cFreqSlider;
	CMyEdit m_cFreqEdit;
	CMyEdit m_cLeftTHD;
	CMyEdit m_cRightTHD;
	CDstLevelWnd m_cGraph;
	CStatic m_cLeftUnit;
	CStatic m_cRightUnit;
	CMyEdit m_cLevelStart;
	CMyEdit m_cLevelEnd;
	CMyEdit m_cLevelPoint;
	CMyEdit m_cLevelCurrent;
	CMyButton m_cLevelSpline;
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
	afx_msg void OnEnChangeFreqEdit();
	afx_msg void OnEnChangeLevelStart();
	afx_msg void OnEnChangeLevelEnd();
	afx_msg void OnEnChangeLevelPoint();
	afx_msg void OnBnClickedLevelSpline();
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg void OnBnClickedChkMarker();
	afx_msg void OnCbnSelchangeComboHd();
	afx_msg LRESULT OnTabInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
