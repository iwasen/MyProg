// NoiseSourceDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "Nms.h"
#include "NoiseSourceDlg.h"
#include "FileIO.h"
#include "Help\ContextHelp.h"

/////////////////////////////////////////////////////////////////////////////
// CNoiseSourceDlg ダイアログ


CNoiseSourceDlg::CNoiseSourceDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CNoiseSourceDlg::IDD, pParent)
{
	m_sNoiseSource = _T("");
}


void CNoiseSourceDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Text(pDX, IDC_NOISE_SOURCE_NAME, m_sNoiseSource);
}


BEGIN_MESSAGE_MAP(CNoiseSourceDlg, CDialog)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CNoiseSourceDlg メッセージ ハンドラ

void CNoiseSourceDlg::OnOK()
{
	CDbNsTmp dbNsTmp;
	DbNsTmpRec dbNsTmpRec;
	NsTmpData nsTmpData;

	UpdateData(TRUE);

	if (m_sNoiseSource.IsEmpty()) {
		AfxMessageBox(IDS_ERR_NOISE_SOURCE, MB_OK | MB_ICONEXCLAMATION);
		return;
	}
	if (!dbNsTmp.Open())
		return;

	if (!dbNsTmp.GetNewID(&dbNsTmpRec.nNsTmpID))
		return;

	dbNsTmpRec.sName = m_sNoiseSource;
	memset(&nsTmpData, 0, sizeof(nsTmpData));

	dbNsTmp.StoreRec(&dbNsTmpRec, &nsTmpData);

	CDialog::OnOK();
}

BOOL CNoiseSourceDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDOK, IDH_NOISE_SOURCE_OK,
		IDCANCEL, IDH_NOISE_SOURCE_CANCEL,
		IDC_NOISE_SOURCE_NAME, IDH_NOISE_SOURCE_NAME,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
