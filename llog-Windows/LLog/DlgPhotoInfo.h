#pragma once

#include "myctrl.h"


// CDlgPhotoInfo ダイアログ

class CDlgPhotoInfo : public CDialog
{
	DECLARE_DYNAMIC(CDlgPhotoInfo)

public:
	CDlgPhotoInfo(CWnd* pParent = NULL);   // 標準コンストラクタ

// ダイアログ データ
	enum { IDD = IDD_DLG_PHOTO_INFO };

	CString m_sModelName;
	CString m_sShutterSpeed;
	CString m_sAperture;
	CString m_sIsoSpeed;
	CString m_sExposureBias;
	CString m_sFocalLength;

protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV サポート

	DECLARE_MESSAGE_MAP()
	virtual BOOL OnInitDialog();

	CEditEx m_cEditModelName;
	CEditEx m_cEditShutterSpeed;
	CEditEx m_cEditAperture;
	CEditEx m_cEditIsoSpeed;
	CEditEx m_cEditExposureBias;
	CEditEx m_cEditFocalLength;
};
