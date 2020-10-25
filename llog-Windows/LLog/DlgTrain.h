#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgTrain ダイアログ

class CDlgTrain : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgTrain)

public:
	CDlgTrain(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_TRAIN };

protected:
	void SetComboLine(CComboBox &oCombo);
	void SetComboStation(CComboBox &oCombo);
	void DispData();

	BOOL m_bUpdate;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnBnClickedButtonAdd();
	afx_msg void OnBnClickedButtonDelete();
	virtual void OnCancel();
	afx_msg void OnLbnSelchangeListTrain();
	afx_msg void OnBnClickedButtonUpdate();
	afx_msg void OnCbnKillfocusComboFromLine();

	CEditEx m_cEditDate;
	CListBox m_cListTrain;
	CComboBox m_cComboFromLine;
	CComboBox m_cComboFromStation;
	CComboBox m_cComboToLine;
	CComboBox m_cComboToStation;
};
