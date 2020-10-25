#pragma once

#include "datafile.h"
#include "datadatetime.h"


// CCsvData ダイアログ

class CCsvData : public CDialog
{
	DECLARE_DYNAMIC(CCsvData)

public:
	CCsvData(CWnd* pParent = NULL);   // 標準コンストラクタ
	virtual ~CCsvData();

// ダイアログ データ
	enum { IDD = IDD_CONV_CSV };

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
protected:
	// 開始時間
	CEdit m_Edit_Start;
	// 終了時間
	CEdit m_Edit_Endtime;
	// CSVファイル名指定
	CEdit m_Edit_FileName;
private:
	CDataFile m_cDataFile;
public:
	void DspModal(LPCSTR pFilePath, int nDataType);
private:
	CDataDateTime m_startTime;
	CDataDateTime m_endTime;
	CDataDateTime m_In_startTime;
	CDataDateTime m_In_endTime;
	CString m_sCsvPath;
	CToolTipCtrl m_tooltip;

protected:
	CEdit m_Edit_s_yy;
	CEdit m_Edit_s_mm;
	CEdit m_Edit_s_dd;
	CEdit m_Edit_s_h;
	CEdit m_Edit_s_m;
	CEdit m_Edit_s_s;
	CEdit m_Edit_e_yy;
	CEdit m_Edit_e_mm;
	CEdit m_Edit_e_dd;
	CEdit m_Edit_e_h;
	CEdit m_Edit_e_m;
	CEdit m_Edit_e_s;
	CComboBox m_cComboSampleRate;
	CComboBox m_cComboTargetData;
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonRef();
	afx_msg void OnBnClickedButtonConv();
	virtual BOOL PreTranslateMessage(MSG* pMsg);

private:
	// 入力項目を取得する
	int GetInputData(void);
};
