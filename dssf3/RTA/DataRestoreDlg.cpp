// DataRestoreDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "DataRestoreDlg.h"
#include "DssData.h"
#include "Help\ContextHelp.h"


// CDataRestoreDlg �_�C�A���O

CDataRestoreDlg::CDataRestoreDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CDataRestoreDlg::IDD, pParent)
{

}

CDataRestoreDlg::~CDataRestoreDlg()
{
}

void CDataRestoreDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_LIST_BACKUP, m_cListBackup);
}


BEGIN_MESSAGE_MAP(CDataRestoreDlg, CDialog)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()


// CDataRestoreDlg ���b�Z�[�W �n���h��

BOOL CDataRestoreDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cListBackup.SetExtendedStyle(m_cListBackup.GetExtendedStyle() | LVS_EX_FULLROWSELECT);
	m_cListBackup.InsertColumn(0, GetString(IDS_BACKUP_TIME), LVCFMT_LEFT, 120);
	m_cListBackup.InsertColumn(1, GetString(IDS_BACKUP_COMMENT), LVCFMT_LEFT, 240);
	SetBackupList(m_cListBackup, m_oBackupList);

	return TRUE;
}

void CDataRestoreDlg::OnOK()
{
	POSITION pos = m_cListBackup.GetFirstSelectedItemPosition();
	if (pos) {
		if (AfxMessageBox(IDS_RESTORE_DATA, MB_OKCANCEL | MB_ICONQUESTION) == IDOK) {
			CString sPathName;
			sPathName.Format("%s\\*\t", g_DssData.m_sDatabaseFolder);
			sPathName.Replace('\t', '\0');

			SHFILEOPSTRUCT sfo = { 0 };
			sfo.wFunc = FO_DELETE;
			sfo.pFrom = sPathName;
			sfo.fFlags = FOF_NOCONFIRMATION;
			::SHFileOperation(&sfo);

			CString sBackup = m_oBackupList.ElementAt(m_cListBackup.GetItemData(m_cListBackup.GetNextSelectedItem(pos)));
			CString sFrom;
			sFrom.Format("%s\\*.DBF\t%s\\*.BIN\t", sBackup, sBackup);
			sFrom.Replace('\t', '\0');

			sfo.wFunc = FO_COPY;
			sfo.pFrom = sFrom;
			sfo.pTo = g_DssData.m_sDatabaseFolder;
			sfo.fFlags = FOF_NOCONFIRMATION;
			::SHFileOperation(&sfo);

			AfxMessageBox(IDS_RESTORE_END, MB_OK | MB_ICONINFORMATION);

			EndDialog(IDOK);
		}
	} else
		AfxMessageBox(IDS_SELECT_RESTORE_DATA, MB_OK | MB_ICONEXCLAMATION);
}

BOOL CDataRestoreDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_LIST_BACKUP, IDH_RESTORE_LIST_BACKUP,
		IDOK, IDH_RESTORE_EXEC,
		IDCANCEL, IDH_RESTORE_CLOSE,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
