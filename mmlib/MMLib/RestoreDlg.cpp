// RestoreDlg.cpp : インプリメンテーション ファイル
//

#include "stdafx.h"
#include "MMLib.h"
#include "RestoreDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#undef THIS_FILE
static char THIS_FILE[] = __FILE__;
#endif

/////////////////////////////////////////////////////////////////////////////
// CRestoreDlg ダイアログ


CRestoreDlg::CRestoreDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CRestoreDlg::IDD, pParent)
{
		// メモ - ClassWizard はこの位置にマッピング用のマクロを追加または削除します。

	m_nSortItem = 0;
}


void CRestoreDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
	DDX_Control(pDX, IDCANCEL, m_cCancel);
	DDX_Control(pDX, IDC_PROGRESS1, m_cProgress);
	DDX_Control(pDX, IDC_DELETE, m_cDelete);
	DDX_Control(pDX, IDC_BACKUP_LIST, m_cBackupList);
}


BEGIN_MESSAGE_MAP(CRestoreDlg, CDialog)
	ON_NOTIFY(LVN_COLUMNCLICK, IDC_BACKUP_LIST, OnColumnclickBackupList)
	ON_BN_CLICKED(IDC_DELETE, OnDelete)
	ON_WM_DESTROY()
END_MESSAGE_MAP()

/////////////////////////////////////////////////////////////////////////////
// CRestoreDlg メッセージ ハンドラ

BOOL CRestoreDlg::OnInitDialog() 
{
	CDialog::OnInitDialog();

	m_cBackupList.InsertColumn(0, GetString(IDS_LIBRARY_NAME), LVCFMT_LEFT, 230);
	m_cBackupList.InsertColumn(1, GetString(IDS_BACKUP_TIME), LVCFMT_LEFT, 120);

	SetBackupList();
	
	return TRUE;
}

void CRestoreDlg::OnOK() 
{
	POSITION pos;
	int nIndex;
	CMMPcsDB *pPcsDB;
	CString *pBackupPath;

	if ((pos = m_cBackupList.GetFirstSelectedItemPosition()) == NULL) {
		AfxMessageBox(IDS_SELECT_RECTORE_LIBRARY);
		return;
	}

	if (AfxMessageBox(IDS_RESTORE_LIBRARY, MB_OKCANCEL | MB_ICONQUESTION) != IDOK)
		return;

	if ((pPcsDB = CreateRestoreLibrary()) == NULL) {
		AfxMessageBox(IDS_RESTORE_FAIL);
		return;
	}

	nIndex = m_cBackupList.GetNextSelectedItem(pos);
	pBackupPath = (CString *)m_cBackupList.GetItemData(nIndex);

	if (pPcsDB->RestoreEx(*pBackupPath, &m_cProgress)) {
		g_pMMLibDB->CloseMMLibrary(pPcsDB);
		m_cCancel.SetWindowText(GetString(IDS_CLOSE));
		AfxMessageBox(IDS_RESTORE_NORMAL);
	} else {
		g_pMMLibDB->DeleteMMLibrary(pPcsDB);
		AfxMessageBox(IDS_RESTORE_FAIL);
	}

	m_cProgress.SetPos(0);
}

void CRestoreDlg::OnColumnclickBackupList(NMHDR* pNMHDR, LRESULT* pResult) 
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

int CALLBACK CRestoreDlg::CompareItems(LPARAM lParam1, LPARAM lParam2, LPARAM lParamSort)
{
	CRestoreDlg *pDlg = (CRestoreDlg *)lParamSort;
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

void CRestoreDlg::OnDelete() 
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

void CRestoreDlg::SetBackupList()
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

CMMPcsDB *CRestoreDlg::CreateRestoreLibrary()
{
	CMMPcsDB *pPcsDB;
	FOLDERREC folderRec;
	CString libraryDir;
	CString libraryPath;

	::GetLibraryDir(libraryDir);
	::GetNewFolder(libraryDir, "MMLIB", libraryPath);
	CreateDirectoryAll(libraryPath);
	
	if ((pPcsDB = g_pMMLibDB->CreateMMLibrary(CHT_LOCAL, "", libraryPath, CACHEMODE_DIRECT, &folderRec)) == NULL)
		return NULL;

	return pPcsDB;
}

void CRestoreDlg::OnDestroy() 
{
	CDialog::OnDestroy();

	RemoveBackupItem();
}

void CRestoreDlg::RemoveBackupItem()
{
	int nCount;
	int i;

	nCount = m_cBackupList.GetItemCount();

	for (i = 0; i < nCount; i++)
		delete (CString *)m_cBackupList.GetItemData(i);

	m_cBackupList.DeleteAllItems();
}
