// AcfMarkerDlg.cpp : 実装ファイル
//

#include "stdafx.h"
#include "SAS.h"
#include "AcfMarkerDlg.h"
#include "FileIO.h"

// CMarkerDlg ダイアログ

IMPLEMENT_DYNAMIC(CAcfMarkerDlg, CDialog)

CAcfMarkerDlg::CAcfMarkerDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CAcfMarkerDlg::IDD, pParent)
{
	m_nAcfMarkerID = 0;
}

CAcfMarkerDlg::~CAcfMarkerDlg()
{
}

void CAcfMarkerDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_TITLE, m_cEditTitle);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
}


BEGIN_MESSAGE_MAP(CAcfMarkerDlg, CDialog)
END_MESSAGE_MAP()


// CMarkerDlg メッセージ ハンドラ

BOOL CAcfMarkerDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	if (m_nAcfMarkerID != 0) {
		CDbAcfMarker dbAcfMarker;
		if (dbAcfMarker.Open()) {
			DbAcfMarkerRec dbAcfMarkerRec;
			dbAcfMarker.ReadRecID(m_nAcfMarkerID, &dbAcfMarkerRec);
			m_cEditTitle = dbAcfMarkerRec.sTitle;
			m_cEditComment = dbAcfMarkerRec.sComment;
			m_nAcfID = dbAcfMarkerRec.nAcfID;
			m_fTime = dbAcfMarkerRec.fTime;
		}
	}

	return TRUE;
}

void CAcfMarkerDlg::OnOK()
{
	if (m_cEditTitle.IsEmpty()) {
		CString msg;
		msg.LoadString(IDS_ERR_TITLE);
		MessageBox(msg, NULL, MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	CDbAcfMarker dbAcfMarker;
	if (dbAcfMarker.Open()) {
		DbAcfMarkerRec dbAcfMarkerRec;
		dbAcfMarkerRec.nAcfID = m_nAcfID;
		dbAcfMarkerRec.fTime = m_fTime;
		dbAcfMarkerRec.sTitle = m_cEditTitle;
		dbAcfMarkerRec.sComment = m_cEditComment;
		if (m_nAcfMarkerID == 0) {
			dbAcfMarker.GetNewID(&dbAcfMarkerRec.nMarkerID);
			dbAcfMarker.StoreRec(&dbAcfMarkerRec);
		} else {
			dbAcfMarkerRec.nMarkerID = m_nAcfMarkerID;
			dbAcfMarker.UpdateRec(&dbAcfMarkerRec);
		}
	}

	CDialog::OnOK();
}
