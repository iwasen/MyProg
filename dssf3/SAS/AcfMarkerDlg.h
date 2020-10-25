#pragma once

#include "MyCtrl.h"


// AcfMarkerDlg �_�C�A���O

class CAcfMarkerDlg : public CDialog
{
	DECLARE_DYNAMIC(CAcfMarkerDlg)

public:
	CAcfMarkerDlg(CWnd* pParent = NULL);   // �W���R���X�g���N�^
	virtual ~CAcfMarkerDlg();

// �_�C�A���O �f�[�^
	enum { IDD = IDD_ACF_MARKER };

	long m_nAcfID;
	long m_nAcfMarkerID;
	double m_fTime;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();
	virtual void OnOK();

	CMyEdit m_cEditTitle;
	CMyEdit m_cEditComment;
};
