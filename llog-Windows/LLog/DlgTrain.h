#pragma once

#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgTrain �_�C�A���O

class CDlgTrain : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgTrain)

public:
	CDlgTrain(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DLG_TRAIN };

protected:
	void SetComboLine(CComboBox &oCombo);
	void SetComboStation(CComboBox &oCombo);
	void DispData();

	BOOL m_bUpdate;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

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
