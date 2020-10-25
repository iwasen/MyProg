#pragma once

#include "MyCtrl.h"
#include "FreWnd.h"
#include "DialogEx.h"
#include "FileIO.h"

class CFreSpot : public CDialogEx
{
public:
	CFreSpot(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CFreSpot();

	int Initialize();
	void Redraw();
	BOOL WaveOutData(double *pData, int nBitsPerSample);
	BOOL WaveInData(const double *pData);
	BOOL CheckDataExist();
	BOOL CheckDataHold();
	void CsvOutput(LPCTSTR pFileName);
	HBITMAP GetBitmap();
	void AddDataHold(COLORREF colorLeft, COLORREF colorRight);
	void DelDataHold(BOOL bRedraw = TRUE);

protected:
	enum { IDD = IDD_FRE_SPOT };

	struct SDataHold {
		COLORREF colorLeft;
		COLORREF colorRight;
		int nFreqPoint;
		int nFreqCount;
		double *pLeftData;
		double *pRightData;
		double *pFreq;
	};

	void InitLevelSlider();
	void SetLevel(int nLevel);
	void FreeBuffers();
	void MakeSinTable();
	double CalcFreqResponse(const double *pData, double fFreq, const DbMicCalRec &oMicCalData);

	CFreWnd m_cGraph;
	CMyEdit m_cFreqStart;
	CMyEdit m_cFreqEnd;
	CMyEdit m_cFreqPoint;
	CMyEdit m_cFreqCurrent;
	CSliderCtrl m_cLevelSlider;
	CMyEdit m_cLevelEdit;
	CSpinButtonCtrl m_cFreqPointSpin;
	int m_nSamplingRate;
	int m_nChannel;
	double *m_pWaveLeft;
	double *m_pWaveRight;
	double *m_pLeftData;
	double *m_pRightData;
	double *m_pFreq;
	double m_fFreqCurrent;
	double m_fFreqStep;
	double m_fLevel;
	int m_nFreqPoint;
	int m_nFreqCount;
	int m_nFreqStart;
	int m_nFreqEnd;
	double m_fAngle;
	int m_nWaveInOffset;
	int m_nWaveBufSize;
	int m_nGuardCnt;
	BOOL m_bValidData;
	BOOL m_bFreqChange;
	BOOL m_bInitialized;
	CList<SDataHold,SDataHold&> m_oDataHoldList;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g
	virtual BOOL OnInitDialog();
	afx_msg void OnEnChangeFreqStart();
	afx_msg void OnEnChangeFreqEnd();
	afx_msg void OnEnChangeFreqPoint();
	afx_msg void OnEnChangeLevelEdit();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg LRESULT OnTabInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
