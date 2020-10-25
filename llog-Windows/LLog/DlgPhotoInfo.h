#pragma once

#include "myctrl.h"


// CDlgPhotoInfo �_�C�A���O

class CDlgPhotoInfo : public CDialog
{
	DECLARE_DYNAMIC(CDlgPhotoInfo)

public:
	CDlgPhotoInfo(CWnd* pParent = NULL);   // �W���R���X�g���N�^

// �_�C�A���O �f�[�^
	enum { IDD = IDD_DLG_PHOTO_INFO };

	CString m_sModelName;
	CString m_sShutterSpeed;
	CString m_sAperture;
	CString m_sIsoSpeed;
	CString m_sExposureBias;
	CString m_sFocalLength;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV �T�|�[�g

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();

	CEditEx m_cEditModelName;
	CEditEx m_cEditShutterSpeed;
	CEditEx m_cEditAperture;
	CEditEx m_cEditIsoSpeed;
	CEditEx m_cEditExposureBias;
	CEditEx m_cEditFocalLength;
};
