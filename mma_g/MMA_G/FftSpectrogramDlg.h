#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		FftSpectrogramDlg.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		スペクトログラム解析グラフのダイアログクラス定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "FftBaseDlg.h"
#include "FftSpectrogramView.h"
#include "GraphData.h"
#include "GraphBar.h"
#include "Fft.h"
#include "MyCtrl.h"
#include "DigitalFilter.h"
#include "datadatetime.h"

// CFftSpectrogramDlg ダイアログ

class CFftSpectrogramDlg : public CFftBaseDlg
{
	DECLARE_DYNAMIC(CFftSpectrogramDlg)

public:
	CFftSpectrogramDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CFftSpectrogramDlg();

// ダイアログ データ
	enum { IDD = IDD_FFT_SPECTROGRAM };

	void CreateGraph(CGraphData *pGraphData, CGraphBar *pGraphBar, HWND hWndParent, BOOL bRealtime);
	void UpdateGraph();
	void SetCursor(double fCursorX, double fCursorY);
	void DispGraph();
	void TimeScroll(double fPos);

protected:
	void InitializeControls();
	void CalcAndDispGraph();
	BOOL CalcData();
	void AllocBuf();
	void FreeBuf();
	void CalcSpectrogram(const double *pBinaryDataX, const double *pBinaryDataY, const double *pBinaryDataZ, double *pSpectrogram);
	void CalcSpectrum(const double *pBinaryData, double *pSpectrum);
	void PrintGraphInfo(CDC &dc, CRect rectView);
	double GetMinFreq();
	double GetMinLevel();
	BOOL SetFilter(BOOL bDispAlert);
	void SetTimeZoom();
	void AutoScroll();
	void DispCursor();
	void InitRealtime();
	void CalcRealtime();

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnBnClickedButtonClose();
	afx_msg void OnCbnSelchangeComboWindowFunction();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnBnClickedButtonPrint();
	afx_msg void OnBnClickedRadioFreqLinear();
	afx_msg void OnBnClickedRadioFreqLog();
	afx_msg void OnBnClickedRadioLevelLinear();
	afx_msg void OnBnClickedRadioLevelLog();
	afx_msg void OnBnClickedButtonFilterSet();
	afx_msg void OnCbnSelchangeComboFilterBand();
	afx_msg LRESULT OnRetKey(WPARAM wParam, LPARAM lParam);
	afx_msg void OnBnClickedButtonCsv();
	afx_msg void OnBnClickedButtonTimeSet();
	afx_msg void OnBnClickedButtonInitialize();
	afx_msg void OnEnChangeEditTimeZoom();
	afx_msg void OnBnClickedButtonTimeZoomAll();
	afx_msg void OnBnClickedButtonTimeZoomIn();
	afx_msg void OnBnClickedButtonTimeZoomOut();
	afx_msg void OnBnClickedCheckScrollStart();
	afx_msg void OnTimer(UINT_PTR nIDEvent);

protected:
	CGraphData *m_pGraphData;
	CGraphBar *m_pGraphBar;
	double *m_pSpectrogramG;
	int m_nFftSize;
	int m_nStepSize;
	int m_nSpectrumDataSize;
	int m_nTimeDataNum;
	double m_fTimeStep;
	double m_fTimeEnd;
	int m_nWindowFunc;
	CFFT m_cFft;
	BOOL m_bCalculation;
	double m_fSampleRate;
	double m_fScaleFreqMin;
	double m_fScaleFreqMax;
	BOOL m_bScaleFreqLog;
	double m_fScaleLevelMin;
	double m_fScaleLevelMax;
	BOOL m_bScaleLevelLog;
	CDigitalFilter m_cFilter;
	BOOL m_bFilter;
	double m_fSelectBegin;
	double m_fSelectEnd;
	CToolTipCtrl m_tooltip;
	BOOL m_bRealtime;
	double m_fDefaultSelectBegin;
	double m_fDefaultSelectEnd;
	double m_fTimeOffset;
	double m_fTimeZoom;
	DWORD m_dwAutoScrollTick;
	double m_fCursorTime;
	double m_fCursorFreq;
	double m_fCalcTime;
	CString m_sTitle;
	CDataDateTime m_StartTime;

	CComboBox m_cComboWindowFunction;
	CFftSpectrogramView m_cStaticFftGraph;
	CEditEx m_cEditDispBegin;
	CEditEx m_cEditDispEnd;
	CEditEx m_cEditDispEnd2;
	CEditEx m_cEditDispBegin2;
// 077FD050-GYM961-0_003 2007/9/8 appended by yG ->
	CEditEx m_cEditInfoTaaId;
	CEditEx m_cEditInfoAcqStartTime;
	CEditEx m_cEditInfoSamplingRate;
	CEditEx m_cEditInfoCutOffFreq;
	CEditEx m_cEditInfoGain;
	CEditEx m_cEditInfoPosition;
// 077FD050-GYM961-0_003 2007/9/8 appended by yG <-
	CEditEx m_cEditCursorFreq;
	CEditEx m_cEditCursorTime;
	CEditEx m_cEditCursorG;
	CEditEx m_cEditFreqMin;
	CEditEx m_cEditFreqMax;
	CButtonEx m_cRadioFreqLinear;
	CButtonEx m_cRadioFreqLog;
	CEditEx m_cEditLevelMin;
	CEditEx m_cEditLevelMax;
	CButtonEx m_cRadioLevelLinear;
	CButtonEx m_cRadioLevelLog;
	CComboBox m_cComboFilterBand;
	CComboBox m_cComboFilterOrder;
	CComboBox m_cComboFilterShape;
	CEditEx m_cEditFilterFreq1;
	CEditEx m_cEditFilterFreq2;
	CButtonEx m_cButtonFilterSet;
	CEditEx m_cEditTimeZoom;
	CEditEx m_cEditScrollSpeed;
	CEditEx m_cEditScrollFreq;
	CButtonEx m_cCheckScrollStart;
	CButtonEx m_cButtonTimeZoomAll;
	CButtonEx m_cButtonTimeZoomOut;
	CButtonEx m_cButtonTimeSet;
	CStatic m_cStaticInfoTaa;
	CStatic m_cStaticInfoCutOffFreq;
	CStatic m_cStaticInfoGain;
	CStatic m_cStaticInfoPosition;

	bool IsEnableCalc();
	void OnRetKeyEditFreqMin();
	void OnRetKeyEditFreqMax();
	void OnRetKeyEditLevelMin();
	void OnRetKeyEditLevelMax();
};
