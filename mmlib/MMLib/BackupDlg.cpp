// BackupDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "BackupDlg.h"
#include "OptProperty.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CBackupDlg ダイアログ


CBackupDlg::CBackupDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CBackupDlg::IDD, pParent)
{

	m_pItemFolder = NULL;
	m_nSortItem = 0;
}


void CBackupDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDC_DELETE, m_cDelete);
	DDX_Control(pDX, IDCANCEL, m_cCancel);
	DDX_Control(pDX, IDC_BACKUP_LIST, m_cBackupList);
	DDX_Control(pDX, IDC_LIBRARY, m_cLibrary);
	DDX_Control(pDX, IDC_PROGRESS, m_cProgress);
}


BEGIN_MESSAGE_MAP(CBackupDlg, CDialog)
	ON_BN_CLICKED(IDC_DELETE, OnDelete)
	ON_NOTIFY(LVN_COLUMNCLICK, IDC_BACKUP_LIST, OnColumnclickBackupList)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CBackupDlg メッセージ ハンドラ

BOOL CBackupDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	int i;
	int n;

	n = SetLibraryComboBox(m_cLibrary);
	if (n == 1)
		m_cLibrary.SetCurSel(0);
	else if (n != 0) {
		for (i = 0; i < n; i++) {
			if (m_cLibrary.GetItemDataPtr(i) == m_pItemFolder) {
				m_cLibrary.SetCurSel(i);
				break;
			}
		}
	}

	m_cBackupList.InsertColumn(0, GetString(IDS_LIBRARY_NAME), LVCFMT_LEFT, 230);
	m_cBackupList.InsertColumn(1, GetString(IDS_BACKUP_TIME), LVCFMT_LEFT, 120);

	SetBackupList();
	
	return TRUE;
}

void CBackupDlg::OnOK() 
{
	int nIndex;
	CItemFolder *pItemFolder;
	CString backupDir;
	CString backupPath;

	if ((nIndex = m_cLibrary.GetCurSel()) == LB_ERR) {
		AfxMessageBox(IDS_SELECT_LIBRARY);
		return;
	}

	pItemFolder = (CItemFolder *)m_cLibrary.GetItemDataPtr(nIndex);

	::GetBackupDir(backupDir);
	::GetNewFolder(backupDir, "BAKUP", backupPath);

	if (pItemFolder->m_pPcsDB->BackupEx(backupPath, &m_cProgress)) {
		SetBackupList();
		m_cCancel.SetWindowText(GetString(IDS_CLOSE));
		AfxMessageBox(IDS_BACKUP_NORMAL);
	} else {
		::DeleteDirectory(backupPath);
		AfxMessageBox(IDS_BACKUP_FAIL);
	}

	m_cProgress.SetPos(0);
}

void CBackupDlg::SetBackupList()
{
	CString dir;
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	int	nIndex = 0;
	CString libraryName;
	CString backupPath;
	CString *pBackupPath;

	::GetBackupDir(dir);

	RemoveBackupItem();

	hdir = FindFirstFile(dir + "\\*", &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				 backupPath = dir + "\\" + findBuf.cFileName;
				if (g_pMMLibDB->GetLibraryName(backupPath, libraryName)) {
					m_cBackupList.InsertItem(nIndex, libraryName);

					CTime time(findBuf.ftCreationTime);
					m_cBackupList.SetItemText(nIndex, 1, time.Format("%Y/%m/%d %X"));

					pBackupPath = new CString;
					*pBackupPath = backupPath;
					m_cBackupList.SetItemData(nIndex, (DWORD_PTR)pBackupPath);
					nIndex++;
				}
			}
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	m_cBackupList.SortItems(CompareItems, (LPARAM)this);

	m_cDelete.EnableWindow(nIndex != 0);
}

void CBackupDlg::OnDelete() 
{
	POSITION pos;
	int nIndex;
	CString *pBackupPath;

	if ((pos = m_cBackupList.GetFirstSelectedItemPosition()) == NULL) {
		AfxMessageBox(IDS_SELECT_DELETE_LIBRARY);
		return;
	}

	if (AfxMessageBox(IDS_DELETE_SELECTED_LIBRARY, MB_OKCANCEL | MB_ICONQUESTION) != IDOK)
		return;

	nIndex = m_cBackupList.GetNextSelectedItem(pos);

	pBackupPath = (CString *)m_cBackupList.GetItemData(nIndex);
	::DeleteDirectory(*pBackupPath);

	SetBackupList();
}

void CBackupDlg::OnColumnclickBackupList(NMHDR* pNMHDR, LRESULT* pResult) 
{
	NM_LISTVIEW* pNMListView = (NM_LISTVIEW*)pNMHDR;

	if (m_nSortItem != pNMListView->iSubItem)
		m_nSortDir = 1;
	else
		m_nSortDir *= -1;

	m_nSortItem = pNMListView->iSubItem;
	m_cBackupList.SortItems(CompareItems, (LPARAM)this);

	*pResult = 0;
}

int CALLBACK CBackupDlg::CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CBackupDlg *pDlg = (CBackupDlg *)lParamSort;
	CLixxxtrl &dataList = pDlg->m_cBackupList;
	LV_FINDINFO findInfo;
	int index1, index2;

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam1;
	index1 = dataList.FindItem(&findInfo);

	findInfo.flags = LVFI_PARAM;
	findInfo.lParam = lParam2;
	index2 = dataList.FindItem(&findInfo);

	return dataList.GetItemText(index1, pDlg->m_nSortItem).Compare(dataList.GetItemText(index2, pDlg->m_nSortItem)) * pDlg->m_nSortDir;
}

void CBackupDlg::OnDestroy() 
{
	CDialog::OnDestroy();

	RemoveBackupItem();
}

void CBackupDlg::RemoveBackupItem()
{
	int nCount;
	int i;

	nCount = m_cBackupList.GetItemCount();

	for (i = 0; i < nCount; i++)
		delete (CString *)m_cBackupList.GetItemData(i);

	m_cBackupList.DeleteAllItems();
}
