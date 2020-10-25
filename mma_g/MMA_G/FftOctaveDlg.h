#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		FftOctaveDlg .h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		1/3オクターブ解析グラフのダイアログクラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "FftBaseDlg.h"
#include "FftOctaveView.h"
#include "GraphData.h"
#include "GraphBar.h"
#include "Fft.h"
#include "MyCtrl.h"
#include "DigitalFilter.h"

#define OCTAVE_BAND_NUM	70

// CFftOctaveDlg ダイアログ

class CFftOctaveDlg : public CFftBaseDlg
{
	DECLARE_DYNAMIC(CFftOctaveDlg)

public:
	CFftOctaveDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CFftOctaveDlg();

// ダイアログ データ
	enum { IDD = IDD_FFT_OCTAVE };

	void CreateGraph(CGraphData *pGraphData, CGraphBar *pGraphBar, HWND hWndParent);
	void DispCursorData(double fCursorX, double fCursorY);

protected:
	void InitializeControls();
	void CalcAndDispGraph();
	void CalcData();
	void AllocBuf();
	void FreeBuf();
	void CalcSpectrum(const double *pBinaryData, double *pSpectrum);
	void CalcOctaveBand(const double *pSpectrum, double *pOctaveData);
	void DispGraph();
	void PrintGraphInfo(CDC &dc, CRect rectView);
	void PrintSample(CDC &dc, CRect rectView, CPen *x, CPen *y,CPen *z, CPen *g);
	double GetMinFreq();
	double GetMinLevel();
	BOOL SetFilter(BOOL bDispAlert);
	bool IsEnableCalc();
	void MakeLimitLine();

	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnBnClickedButtonClose();
	afx_msg void OnCbnSelchangeComboWindowFunction();
	afx_msg void OnSize(UINT nType, int cx, int cy);
	afx_msg void OnBnClickedButtonPrint();
	afx_msg void OnBnClickedCheckLimitLine();
	afx_msg void OnBnClickedCheckDisp();
	afx_msg void OnBnClickedRadioFreqLinear();
	afx_msg void OnBnClickedRadioFreqLog();
	afx_msg void OnBnClickedRadioLevelLinear();
	afx_msg void OnBnClickedRadioLevelLog();
	afx_msg void OnBnClickedButtonFilterSet();
	afx_msg void OnCbnSelchangeComboFilterBand();
	afx_msg LRESULT OnRetKey(WPARAM wParam, LPARAM lParam);
	afx_msg void OnBnClickedButtonCsv();
	afx_msg void OnBnClickedButtonInitialize();

	void OnRetKeyEditLevelMin();
	void OnRetKeyEditLevelMax();
	void OnRetKeyEditFreqMin();
	void OnRetKeyEditFreqMax();

protected:
	CGraphData *m_pGraphData;
	CGraphBar *m_pGraphBar;
	double *m_pOctaveX;
	double *m_pOctaveY;
	double *m_pOctaveZ;
	double *m_pOctaveG;
	double m_fOctaveBandFreq[OCTAVE_BAND_NUM * 2 + 1];
	double m_fLimitLine1[OCTAVE_BAND_NUM];
	double m_fLimitLine2[OCTAVE_BAND_NUM];
	int m_nDataSize;
	int m_nFftSize;
	int m_nSpectrumDataSize;
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
// 2009/06/11 Version 3.01 appended by y.G ->
	double m_fSelectBegin;
	double m_fSelectEnd;
// <- 2009/06/11 Version 3.01 appended by y.G
	CToolTipCtrl m_tooltip;
	CString m_sTitle;

	CComboBox m_cComboWindowFunction;
	CFftOctaveView m_cStaticFftGraph;
	CEditEx m_cEditDispBegin;
	CEditEx m_cEditDispEnd;
	CButtonEx m_cCheckDispX;
	CButtonEx m_cCheckDispY;
	CButtonEx m_cCheckDispZ;
	CButtonEx m_cCheckDispG;
	CStaticEx m_cStaticLineX;
	CStaticEx m_cStaticLineY;
	CStaticEx m_cStaticLineZ;
	CStaticEx m_cStaticLineG;
	CButtonEx m_cCheckLimitLine1;
	CButtonEx m_cCheckLimitLine2;
// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
	CEditEx m_cEditInfoTaaId;
	CEditEx m_cEditInfoAcqStartTime;
	CEditEx m_cEditInfoSamplingRate;
	CEditEx m_cEditInfoCutOffFreq;
	CEditEx m_cEditInfoGain;
	CEditEx m_cEditInfoPosition;
// 077FD050-GYM961-0_003 2007/9/7 appended by yG <-
	CEditEx m_cEditCursorFreq;
	CEditEx m_cEditCursorX;
	CEditEx m_cEditCursorY;
	CEditEx m_cEditCursorZ;
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
	CStatic m_cStaticInfoTaa;
	CStatic m_cStaticInfoCutOffFreq;
	CStatic m_cStaticInfoGain;
	CStatic m_cStaticInfoPosition;
};
