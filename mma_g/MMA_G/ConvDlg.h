#pragma once

#include "maifile.h"
#include "datadatetime.h"


// CConvDlg ダイアログ

class CConvDlg : public CDialog
{
	DECLARE_DYNAMIC(CConvDlg)

public:
	CConvDlg(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CConvDlg();

// ダイアログ データ
	enum { IDD = IDD_CONV_DATA };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()

private:
	void Read();
	void DspData(void);
	void KeyOn(void);
	void KeyOff(void);
	// 入力項目を取得する
	int GetInputData(void);
	static int CALLBACK BrowseCallbackProc(HWND hwnd, UINT uMsg, LPARAM lParam, LPARAM lpData);
	bool CheckData(void);

private:
	CMaiFile m_maiFile;
	CString m_MaiPath;
	CDataDateTime m_startTime;
	CDataDateTime m_endTime;
	CToolTipCtrl m_tooltip;
	bool m_GoEnable;
	CDocument *m_pDoc;
	CEdit m_Edit_MeasTime;

private:
	CEdit m_Edit_ref;
	CEdit m_Edit_UnitId;
	CEdit m_Edit_SensorId;
	CEdit m_Edit_SampleRate;
	CEdit m_Edit_StartTime;
	CEdit m_Edit_EndTime;
	CButton m_Button_Go;
	CButton m_Button_Cancel;
	CButton m_Button_Ref;
	CComboBox m_Combo_Sensor;
	CEdit m_Edit_Quality;
	CEdit m_Edit_Config;
	CEdit m_Edit_Config2;
	CEdit m_Edit_Comment;
	CComboBox m_Combo_Category;
	CComboBox m_Combo_MeasurementKind;
	CEdit m_Downlink;

public:
	void SetDocPtr(CDocument *ptr);

protected:
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonConv();
	afx_msg void OnBnClickedButtonRef();
	afx_msg void OnBnClickedCancel();
	virtual void OnOK();
	afx_msg void OnEnUpdateEditDownlink();
	void UpdateGoButton(void);
	afx_msg void OnEnUpdateEditQuality();
	afx_msg void OnEnUpdateEditIss();
	afx_msg void OnEnUpdateEditSYy();
	afx_msg void OnEnUpdateEditSMm();
	afx_msg void OnEnUpdateEditSDd();
	afx_msg void OnEnUpdateEditSH();
	afx_msg void OnEnUpdateEditSM();
	afx_msg void OnEnUpdateEditSS();
	afx_msg void OnEnUpdateEditEYy();
	afx_msg void OnEnUpdateEditEMm();
	afx_msg void OnEnUpdateEditEDd();
	afx_msg void OnEnUpdateEditEH();
	afx_msg void OnEnUpdateEditEM();
	afx_msg void OnEnUpdateEditES();
	afx_msg void OnCbnSelchangeComboSensor();
	afx_msg void OnCbnEditchangeComboSensor();
	afx_msg void OnEnUpdateEditIss2();
	virtual BOOL PreTranslateMessage(MSG* pMsg);
	afx_msg void OnCbnSelchangeComboCategory();
	afx_msg void OnCbnEditchangeComboCategory();
	afx_msg void OnCbnSelchangeComboMeasurementKind();
	afx_msg void OnCbnEditchangeComboMeasurementKind();
};
