#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		GraphBar.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータウィンドウのダイアログバークラスの定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "MyCtrl.h"

// CGraphBar ダイアログ

class CGraphBar : public CDialogBar
{
	DECLARE_DYNAMIC(CGraphBar)

public:
	enum { IDD = IDD_GRAPH_BAR_ACCL };

	void InitializeControls();
	void DispTimeData(CString sTimeBegin, CString sTimeEnd, double fTimeZoom);
	void DispLevelDataX(double fLevelBegin, double fLevelEnd, double fLevelZoom);
	void DispLevelDataY(double fLevelBegin, double fLevelEnd, double fLevelZoom);
	void DispLevelDataZ(double fLevelBegin, double fLevelEnd, double fLevelZoom);
	void DispTimeCursor(CString TimeCursor);
	void DispRsuData(double fRsuX, double fRsuY, double fRsuZ, double fRsuD);
	void ClearRsuData();
	void DispSelectArea(double fSelectBegin, double fSelectEnd, CString strBegin, CString strEnd);
	void DispZeroPointValue(double fAverageX, double fAverageY, double fAverageZ);

public:
	CEditEx m_cEditTimeBegin;
	CEditEx m_cEditTimeEnd;
	CEditEx m_cEditTimeCursor;
	CEditEx m_cEditTimeZoom;
	CEditEx m_cEditLevelBeginX;
	CEditEx m_cEditLevelBeginY;
	CEditEx m_cEditLevelBeginZ;
	CEditEx m_cEditLevelEndX;
	CEditEx m_cEditLevelEndY;
	CEditEx m_cEditLevelEndZ;
	CEditEx m_cEditLevelZoomX;
	CEditEx m_cEditLevelZoomY;
	CEditEx m_cEditLevelZoomZ;
	CEditEx m_cEditRsuX;
	CEditEx m_cEditRsuY;
	CEditEx m_cEditRsuZ;
	CEditEx m_cEditRsuD;
	CEditEx m_cEditScrollSpeed;
	CButtonEx m_cCheckScrollStart;
	CEditEx m_cEditSelectBegin;
	CEditEx m_cEditSelectEnd;
	CButtonEx m_cCheckZeroHosei;
	CComboBox m_cComboSampleRate;
	CEditEx m_cEditDirX;
	CEditEx m_cEditDirY;
	CEditEx m_cEditDirZ;
// 077FD050-GYM961-0_003 2007/9/7 appended by yG ->
	CEditEx m_cEditInfoTaaId;
	CEditEx m_cEditInfoAcqStartTime;
	CEditEx m_cEditInfoSamplingRate;
	CEditEx m_cEditInfoCutOffFreq;
	CEditEx m_cEditInfoGain;
	CEditEx m_cEditInfoPosition;
// 077FD050-GYM961-0_003 2007/9/7 appended by yG <-
// 077FD050-GYM961-0_005 2007/9/8 appended by yG ->
	CEditEx m_cEditSpectrogramPointCount;
	CEditEx m_cEditSpectrogramWidth;
// 077FD050-GYM961-0_005 2007/9/8 appended by yG <-
	CButtonEx m_cCheckZoomX;
	CButtonEx m_cCheckZoomY;
	CButtonEx m_cCheckZoomZ;
	CComboBox m_cComboFilterBand;
	CComboBox m_cComboFilterOrder;
	CComboBox m_cComboFilterShape;
	CEditEx m_cEditFilterFreq1;
	CEditEx m_cEditFilterFreq2;
	CButtonEx m_cCheckDispD;
	CButtonEx m_cCheckDispX;
	CButtonEx m_cCheckDispY;
	CButtonEx m_cCheckDispZ;
	CStaticEx m_cStaticLineD;
	CStaticEx m_cStaticLineX;
	CStaticEx m_cStaticLineY;
	CStaticEx m_cStaticLineZ;
	CEditEx m_cEditZeroX;
	CEditEx m_cEditZeroY;
	CEditEx m_cEditZeroZ;
	CButtonEx m_cCheckZeroOffsetGraph;
	CStatic m_cStaticInfoTaa;
	CStatic m_cStaticInfoCutOffFreq;
	CStatic m_cStaticInfoGain;
	CStatic m_cStaticInfoPosition;
	CStatic m_cStaticDirX;
	CStatic m_cStaticDirY;
	CStatic m_cStaticDirZ;

	int m_nGraphKind;

protected:
	CToolTipCtrl m_tooltip;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート
	virtual BOOL PreTranslateMessage(MSG* pMsg);

	afx_msg LRESULT OnInitDialog(WPARAM wParam, LPARAM lParam);
	afx_msg LRESULT OnRetKey(WPARAM wParam, LPARAM lParam);
	DECLARE_MESSAGE_MAP()
};
