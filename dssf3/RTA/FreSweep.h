#pragma once

#include "MyCtrl.h"
#include "FreWnd.h"
#include "DialogEx.h"
#include "FileIO.h"

class CFreSweep : public CDialogEx
{
public:
	CFreSweep(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CFreSweep();

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
	enum { IDD = IDD_FRE_SWEEP };

	struct SDataHold {
		COLORREF colorLeft;
		COLORREF colorRight;
		int nFreqCount;
		double *pLeftData;
		double *pRightData;
		double *pFreq;
	};

	void InitLevelSlider();
	void SetLevel(int nLevel);
	void FreeBuffers();
	double CalcFreqResponse(const double *pData, double fFreq, const DbMicCalRec &oMicCalData);

	CFreWnd m_cGraph;
	CMyEdit m_cFreqStart;
	CMyEdit m_cFreqEnd;
	CMyEdit m_cSweepTime;
	CMyEdit m_cFreqCurrent;
	CSliderCtrl m_cLevelSlider;
	CMyEdit m_cLevelEdit;
	CSpinButtonCtrl m_cSweepTimeSpin;
	int m_nSamplingRate;
	int m_nChannel;
	double *m_pWaveLeft;
	double *m_pWaveRight;
	double *m_pLeftData;
	double *m_pRightData;
	double *m_pFreq;
	double m_fFreqCurrent;
	double m_fFreqStep;
	double m_fSweepAngle;
	double m_fSweepStep;
	double m_fSweepFreq;
	double m_fSweepEnd;
	int m_nFreqPoint;
	int m_nFreqCount;
	int m_nFreqStart;
	int m_nFreqEnd;
	BOOL m_bValidData;
	int m_nWaveBufSize;
	double m_fLevel;
	BOOL m_bInitialized;
	CList<SDataHold,SDataHold&> m_oDataHoldList;

	DECLARE_MESSAGE_MAP()
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL OnInitDialog();
	afx_msg void OnEnChangeFreqStart();
	afx_msg void OnEnChangeFreqEnd();
	afx_msg void OnEnChangeSweepTime();
	afx_msg void OnEnChangeLevelEdit();
	afx_msg void OnHScroll(UINT nSBCode, UINT nPos, CScrollBar* pScrollBar);
	afx_msg BOOL OnHelpInfo(HELPINFO* pHelpInfo);
	afx_msg LRESULT OnTabInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg void OnSize(UINT nType, int cx, int cy);
};
