// DataBackupDlg.cpp : �����t�@�C��
//

#include "stdafx.h"
#include "RTA.h"
#include "DataBackupDlg.h"
#include "DssData.h"
#include "Directory.h"
#include "Help\ContextHelp.h"


// CDataBackupDlg �_�C�A���O

CDataBackupDlg::CDataBackupDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CDataBackupDlg::IDD, pParent)
{

}

CDataBackupDlg::~CDataBackupDlg()
{
}

void CDataBackupDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_EDIT_COMMENT, m_cEditComment);
	DDX_Control(pDX, IDC_LIST_BACKUP, m_cListBackup);
}


BEGIN_MESSAGE_MAP(CDataBackupDlg, CDialog)
	ON_BN_CLICKED(IDC_DELETE_BACKUP, &CDataBackupDlg::OnBnClickedDeleteBackup)
	ON_WM_HELPINFO()
END_MESSAGE_MAP()


// CDataBackupDlg ���b�Z�[�W �n���h��

BOOL CDataBackupDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	m_cListBackup.SetExtendedStyle(m_cListBackup.GetExtendedStyle() | LVS_EX_FULLROWSELECT);
	m_cListBackup.InsertColumn(0, GetString(IDS_BACKUP_TIME), LVCFMT_LEFT, 120);
	m_cListBackup.InsertColumn(1, GetString(IDS_BACKUP_COMMENT), LVCFMT_LEFT, 240);
	SetBackupList(m_cListBackup, m_oBackupList);

	return TRUE;
}


void CDataBackupDlg::OnOK()
{
	if (AfxMessageBox(IDS_BACKUP_DATA, MB_OKCANCEL | MB_ICONQUESTION) == IDOK) {
		CString sFrom;
		sFrom.Format("%s\\*.DBF\t%s\\*.BIN\t", g_DssData.m_sDatabaseFolder, g_DssData.m_sDatabaseFolder);
		sFrom.Replace('\t', '\0');

		CTime time = CTime::GetCurrentTime();
		CString sTo;
		sTo.Format("%s\\%s", g_DssData.m_sBackupFolder, time.Format("%Y%m%d_%H%M%S"));
		CreateDirectoryAll(sTo);

		SHFILEOPSTRUCT sfo = { 0 };
		sfo.wFunc = FO_COPY;
		sfo.pFrom = sFrom;
		sfo.pTo = sTo;
		sfo.fFlags = FOF_NOCONFIRMATION;
		::SHFileOperation(&sfo);

		CString sIniFileName;
		sIniFileName.Format("%s\\%s", sTo, "backup.ini");
		::WritePrivateProfileString("backup", "time", time.Format("%Y/%m/%d %H:%M:%S"), sIniFileName);
		::WritePrivateProfileString("backup", "comment", (CString)m_cEditComment, sIniFileName);

		AfxMessageBox(IDS_BACKUP_END, MB_OK | MB_ICONINFORMATION);

		SetBackupList(m_cListBackup, m_oBackupList);
	}
}

void CDataBackupDlg::OnBnClickedDeleteBackup()
{
	POSITION pos = m_cListBackup.GetFirstSelectedItemPosition();
	if (pos) {
		if (AfxMessageBox(IDS_DELETE_BACKUP_DATA, MB_OKCANCEL | MB_ICONQUESTION) == IDOK) {
			CString sPathName;
			sPathName.Format("%s\t", m_oBackupList.ElementAt(m_cListBackup.GetItemData(m_cListBackup.GetNextSelectedItem(pos))));
			sPathName.Replace('\t', '\0');

			SHFILEOPSTRUCT sfo = { 0 };
			sfo.wFunc = FO_DELETE;
			sfo.pFrom = sPathName;
			sfo.fFlags = FOF_NOCONFIRMATION;
			::SHFileOperation(&sfo);

			SetBackupList(m_cListBackup, m_oBackupList);
		}
	} else
		AfxMessageBox(IDS_SELECT_DELETE_DATA, MB_OK | MB_ICONEXCLAMATION);
}

BOOL CDataBackupDlg::OnHelpInfo(HELPINFO* pHelpInfo)
{
	static const UINT aIDs[] = {
		IDC_EDIT_COMMENT, IDH_BACKUP_EDIT_COMMENT,
		IDC_LIST_BACKUP, IDH_BACKUP_LIST_BACKUP,
		IDOK, IDH_BACKUP_EXEC,
		IDCANCEL, IDH_BACKUP_CLOSE,
		IDC_DELETE_BACKUP, IDH_BACKUP_DELETE_BACKUP,
		0
	};

	DispContextHelp(pHelpInfo, aIDs);

	return TRUE;
}
