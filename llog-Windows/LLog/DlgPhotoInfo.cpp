// CDlgPhotoInfo メッセージ ハンドラ
// DlgPhotoInfo.cpp : 実装ファイル
//

#include "stdafx.h"
#include "LLog.h"
#include "DlgPhotoInfo.h"


// CDlgPhotoInfo ダイアログ

IMPLEMENT_DYNAMIC(CDlgPhotoInfo, CDialog)

CDlgPhotoInfo::CDlgPhotoInfo(CWnd* pParent /*=NULL*/)
	: CDialog(CDlgPhotoInfo::IDD, pParent)
{

}

void CDlgPhotoInfo::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_MODEL_NAME, m_cEditModelName);
	DDX_Control(pDX, IDC_EDIT_SHUTTER_SPEED, m_cEditShutterSpeed);
	DDX_Control(pDX, IDC_EDIT_APERTURE, m_cEditAperture);
	DDX_Control(pDX, IDC_EDIT_ISO_SPEED, m_cEditIsoSpeed);
	DDX_Control(pDX, IDC_EDIT_EXPOSURE_BIAS, m_cEditExposureBias);
	DDX_Control(pDX, IDC_EDIT_FOCAL_LENGTH, m_cEditFocalLength);
}


BEGIN_MESSAGE_MAP(CDlgPhotoInfo, CDialog)
END_MESSAGE_MAP()


// CDlgPhotoInfo メッセージ ハンドラ

BOOL CDlgPhotoInfo::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cEditModelName = m_sModelName;
	m_cEditShutterSpeed = m_sShutterSpeed;
	m_cEditAperture = m_sAperture;
	m_cEditIsoSpeed = m_sIsoSpeed;
	m_cEditExposureBias = m_sExposureBias;
	m_cEditFocalLength = m_sFocalLength;

	return TRUE;
}
