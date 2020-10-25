#pragma once

#include "GoogleMap.h"
#include "MyCtrl.h"
#include "DlgCommon.h"

// CDlgGpsLogger �_�C�A���O

class CDlgGpsLogger : public CDlgCommon
{
	DECLARE_DYNAMIC(CDlgGpsLogger)

public:
	CDlgGpsLogger(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DLG_GPS_LOGGER };

protected:
	BOOL m_bUpdate;

	void DispData();

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	afx_msg void OnCbnSelchangeComboGpsLogger();
	afx_msg void OnDropFiles(HDROP hDropInfo);
	virtual void OnCancel();
	afx_msg void OnBnClickedButtonDelete();

	CGoogleMap m_cStaticGpsLogger;
	CComboBox m_cComboGpsLogger;
	CEditEx m_cEditDate;
public:
	afx_msg void OnBnClickedButtonPhotoLocation();
};
