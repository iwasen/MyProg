// MMLibDB.cpp: CMMLibDB クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "MMLib.h"
#include "MMLibDB.h"
#include "CheckPwdDlg.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define IDX_NULL			0
#define IDX_FOLDER_FID		1
#define IDX_FOLDER_PFID		2
#define IDX_DATA_FID		1
#define IDX_DATA_DID		2

CMMLibDB *g_pMMLibDB;
int g_nLastPcserveError;
BOOL g_bNoDispError;

static int CopyDB(CPSDB *pSrcFolderDB, CPSDB *pSrcDataDB, CPSDB *pDstFolderDB, CPSDB *pDstDataDB, CProgressCtrl *pProgress);
static int CopyFolderDB(CPSDB *pSrcFolderDB, CPSDB *pDstFolderDB, CProgressCtrl *pProgress, int nCount);
static int CopyDataDB(CPSDB *pSrcDataDB, CPSDB *pDstDataDB, CProgressCtrl *pProgress, int nCount);
static int CopyBinaryDB(CPSDB *pSrcDB, CPSDB *pDstDB, LPCTSTR pFieldName);
static void PcserveError(int nErrorCode);
//static void VersionError(int nChType, LPCTSTR pServer, LPCTSTR pDirectory);

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMMLibDB::CMMLibDB()
{
	::GetCacheDir(m_CacheDir);

	if (!CreateDirectoryAll(m_CacheDir))
		AfxMessageBox(IDS_CREATE_CACHE_FOLDER_FAIL);

	if (m_CacheDir.Right(1) != "\\")
		m_CacheDir += "\\";

	m_PcserveLocal.Open(CHT_LOCAL, "");
}

CMMLibDB::~CMMLibDB()
{
}

CMMPcsDB *CMMLibDB::OpenMMLibrary(int nChType, LPCTSTR pServerName, LPCTSTR pDirName, int nOpenMode, LPCTSTR pLibraryID)
{
	CMMPcserve *pPcserve;
	CMMPcsDB *pPcsDB;
	CString directory;

	directory = pDirName;

	if (directory.IsEmpty())
		directory = ".";

	if (directory.Right(1) != '\\')
		directory += "\\";

	if (nOpenMode != CACHEMODE_OFFLINE) {
		if ((pPcserve = ConxxxtPcserve(nChType, pServerName)) == NULL)
			return NULL;

		POSITION pos = pPcserve->m_PcsDBList.GetHeadPosition();
		while (pos != NULL) {
			pPcsDB = (CMMPcsDB *)pPcserve->m_PcsDBList.GetNext(pos);
			if (pPcsDB->m_DirName.CompareNoCase(directory) == 0) {
				PcserveError(ERROR_ALREADY_OPEN);
				return NULL;
			}
		}
	} else
		pPcserve = NULL;

	if (!VersionCheck(pPcserve, directory))
		return NULL;

	pPcsDB = new CMMPcsDB;
	if (!pPcsDB->OpenEx(pPcserve, &m_PcserveLocal, directory, nOpenMode, pLibraryID)) {
		delete pPcsDB;
		return NULL;
	}

	if (nOpenMode != CACHEMODE_OFFLINE)
		pPcserve->m_PcsDBList.AddTail(pPcsDB);

	pPcsDB->m_ServerName = pServerName;
	pPcsDB->m_nChType = nChType;

	return pPcsDB;
}

CMMPcsDB *CMMLibDB::CreateMMLibrary(int nChType, LPCTSTR pServerName, LPCTSTR pDirName, int nOpenMode, FOLDERREC *pLibraryRec)
{
	CMMPcserve *pPcserve;
	CMMPcsDB *pPcsDB;
	CString directory;

	directory = pDirName;

	if (directory.IsEmpty())
		directory = ".";

	if (directory.Right(1) != '\\')
		directory += "\\";

	if (nOpenMode != CACHEMODE_OFFLINE) {
		if ((pPcserve = ConxxxtPcserve(nChType, pServerName)) == NULL)
			return NULL;
	} else
		pPcserve = NULL;

	pPcsDB = new CMMPcsDB;
	if (!pPcsDB->CreateEx(pPcserve, &m_PcserveLocal, directory, nOpenMode, pLibraryRec)) {
		delete pPcsDB;
		return NULL;
	}

	if (nOpenMode != CACHEMODE_OFFLINE)
		pPcserve->m_PcsDBList.AddTail(pPcsDB);

	pPcsDB->m_ServerName = pServerName;
	pPcsDB->m_nChType = nChType;

	return pPcsDB;
}

void CMMLibDB::CloseMMLibrary(CMMPcsDB *pPcsDB)
{
	POSITION pos;
	CMMPcserve *pPcserve;

	if (pPcsDB->m_nCacheMode == CACHEMODE_OFFLINE)
		return;

	pPcserve = pPcsDB->m_pPcserve;

	if ((pos = pPcserve->m_PcsDBList.Find(pPcsDB)) != NULL)
		pPcserve->m_PcsDBList.RemoveAt(pos);

	delete pPcsDB;

	if (pPcserve->m_PcsDBList.IsEmpty()) {
		if ((pos = m_PcserveList.Find(pPcserve)) != NULL)
			m_PcserveList.RemoveAt(pos);

		delete pPcserve;
	}
}

BOOL CMMLibDB::DeleteMMLibrary(CMMPcsDB *pPcsDB)
{
	POSITION pos;
	CMMPcserve *pPcserve;
	int err;

	if (pPcsDB->m_nCacheMode == CACHEMODE_OFFLINE)
		return TRUE;

	if (!pPcsDB->CheckPermission(PERMISSION_DELETE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	pPcserve = pPcsDB->m_pPcserve;

	pPcsDB->CloseEx();

	if ((err = DeleteAllFiles(pPcserve, pPcsDB->m_DirName)) != 0) {
		PcserveError(err);
		pPcsDB->OpenEx(pPcserve, &m_PcserveLocal, pPcsDB->m_DirName, pPcsDB->m_nCacheMode, pPcsDB->m_LibraryID);
		return FALSE;
	}

	if ((pos = pPcserve->m_PcsDBList.Find(pPcsDB)) != NULL)
		pPcserve->m_PcsDBList.RemoveAt(pos);

	delete pPcsDB;

	if (pPcserve->m_PcsDBList.IsEmpty()) {
		if ((pos = m_PcserveList.Find(pPcserve)) != NULL)
			m_PcserveList.RemoveAt(pos);

		delete pPcserve;
	}

	return TRUE;
}

void CMMLibDB::ExitMMLibrary()
{
	CMMPcserve *pPcserve;
	CMMPcsDB *pPcsDB;

	while (!m_PcserveList.IsEmpty()) {
		pPcserve = (CMMPcserve *)m_PcserveList.RemoveHead();
		while (!pPcserve->m_PcsDBList.IsEmpty()) {
			pPcsDB = (CMMPcsDB *)pPcserve->m_PcsDBList.RemoveHead();
			delete pPcsDB;
		}
		delete pPcserve;
	}
}

BOOL CMMLibDB::GetLibraryName(LPCTSTR pDirName, CString &libraryName)
{
	CPSDB folderDB;
	CString pathName;
	int nIdxFolderFID;
	FOLDERBUF folderBuf;
	FOLDERREC folderRec;
	int find;

	pathName.Format("%s\\%s", pDirName, FN_FOLDER_DBF);
	if (folderDB.DBOpen(m_PcserveLocal.m_Pcserve, pathName) != 0)
		return FALSE;

	pathName.Format("%s\\%s", pDirName, FN_FOLDER_NDX1);
	if (folderDB.DBIndex(pathName, &nIdxFolderFID) != 0) {
		if (folderDB.DBIdxCreate(pathName, "FID", 1, &nIdxFolderFID) != 0)
			return FALSE;
	}

	if (folderDB.DBReadKey(FID_LIBRARY, LEN_ID, &folderBuf, 0, &find) != 0)
		return FALSE;

	if (find == 0)
		return FALSE;

	folderDB.DBGetFieldBuf(&folderBuf, &folderRec);

	libraryName = folderRec.folderName;

	return TRUE;
}

CMMPcserve *CMMLibDB::ConxxxtPcserve(int nChType, LPCTSTR pServerName)
{
	POSITION pos;
	CMMPcserve *pPcserve;
	BOOL bFind = FALSE;
	int err;

	pos = m_PcserveList.GetHeadPosition();
	while (pos != NULL) {
		pPcserve = (CMMPcserve *)m_PcserveList.GetNext(pos);
		if (pPcserve->m_nChType == nChType && (nChType == 0 || pPcserve->m_ServerName.CompareNoCase(pServerName) == 0)) {
			bFind = TRUE;
			break;
		}
	}

	if (!bFind) {
		pPcserve = new CMMPcserve;
		if ((err = pPcserve->Open(nChType, pServerName)) != 0) {
			delete pPcserve;
			return NULL;
		}

		m_PcserveList.AddTail(pPcserve);
	}

	return pPcserve;
}

int CMMLibDB::DeleteAllFiles(CMMPcserve *pPcserve, LPCTSTR pDirName)
{
	CString pathName;
	int err;

	pathName.Format("%s%s", pDirName, FN_FOLDER_DBF);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_FOLDER_BIN);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_FOLDER_NDX1);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_FOLDER_NDX2);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_DATA_DBF);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_DATA_BIN);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_DATA_NDX1);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	pathName.Format("%s%s", pDirName, FN_DATA_NDX2);
	if ((err = pPcserve->m_Pcserve.CFDelete(pathName)) != 0)
		return err;

	if (pPcserve->m_nChType == CHT_LOCAL) {
		CString libraryDir;
		::GetLibraryDir(libraryDir);
		if (_strnicmp(libraryDir, pDirName, libraryDir.GetLength()) == 0) {
			libraryDir = pDirName;
			libraryDir.TrimRight('\\');
			RemoveDirectory(libraryDir);
		}
	}

	return 0;
}

BOOL CMMLibDB::VersionCheck(CMMPcserve *pPcserve, LPCTSTR pDirName)
{
	return TRUE;
}

//////////////////////////////////////////////////////////////////////
// CMMPcserve クラス
//////////////////////////////////////////////////////////////////////

CMMPcserve::CMMPcserve()
{
}

CMMPcserve::~CMMPcserve()
{
}

int CMMPcserve::Open(int nChType, LPCTSTR pServerName)
{
	int err;

	if ((err = m_Pcserve.Conxxxt(nChType, pServerName, "MMLIB")) != 0) {
		PcserveError(err);
		return err;
	}

	m_nChType = nChType;

	if (nChType == CHT_LOCAL)
		m_ServerName = "(Local)";
	else
		m_ServerName = pServerName;

	return 0;
}

//////////////////////////////////////////////////////////////////////
// CMMPcsDB クラス
//////////////////////////////////////////////////////////////////////

CMMPcsDB::CMMPcsDB()
{
	m_nCurrentFolderIndex = 1;
	m_nCurrentFolderIndex2 = 1;
	m_nCurrentDataIndex = 1;
	m_nCurrentDataIndex2 = 1;
}

CMMPcsDB::~CMMPcsDB()
{
}

//////////////////////////////////////////////////////////////////////
// CMMPcsDB クラス
//////////////////////////////////////////////////////////////////////

BOOL CMMPcsDB::OpenEx(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, LPCTSTR pLibraryID)
{
	int err;

open:
	if ((err = Open(pPcserve, pCachePcserve, pDirName, nOpenMode, pLibraryID)) != 0) {
		if (nOpenMode != CACHEMODE_OFFLINE) {
			if (err == ERROR_DB_NOFILE) {
				if ((err = ConvertVersion(pPcserve, pDirName)) == 0)
					goto open;
			}
			PcserveError(err);
		}
		return FALSE;
	}

	if (!m_sPassword.IsEmpty()) {
		CCheckPwdDlg dlg;

		dlg.m_sLibrary = m_LibraryRec.folderName;
		for (;;) {
			if (dlg.DoModal() == IDOK) {
				if (dlg.m_sPassword == m_sPassword)
					break;
				else
					PcserveError(ERROR_PASSWORD);
			} else {
				CloseEx();
				return FALSE;
			}
		}
	}

	return TRUE;
}

int CMMPcsDB::ConvertVersion(CMMPcserve *pPcserve, LPCTSTR pDirName)
{
	CString pathName;
	int err;

	if ((err = ConvertVersionSub(pPcserve, pDirName)) != 0) {
		pathName.Format("%s%s", pDirName, FN_FOLDER_DBF);
		pPcserve->m_Pcserve.CFDelete(pathName);
		pathName.Format("%s%s", pDirName, FN_FOLDER_BIN);
		pPcserve->m_Pcserve.CFDelete(pathName);
		pathName.Format("%s%s", pDirName, FN_DATA_DBF);
		pPcserve->m_Pcserve.CFDelete(pathName);
		pathName.Format("%s%s", pDirName, FN_DATA_BIN);
		pPcserve->m_Pcserve.CFDelete(pathName);
	}

	return err;
}

int CMMPcsDB::ConvertVersionSub(CMMPcserve *pPcserve, LPCTSTR pDirName)
{
	CString pathName;
	CPSDB oldDB;
	int err;

	pathName.Format("%s%s", pDirName, "FOLDER2.DBF");
	if ((err = oldDB.DBOpen(pPcserve->m_Pcserve, pathName)) == 0) {
		CString msg;
		msg.FormatMessage(IDS_OLD_DATABASE_VERSION, pathName);
		::MessageBox(NULL, msg, "MMLib", MB_OK | MB_ICONEXCLAMATION);
		if ((err = CreateDB(pPcserve, m_FolderDB, m_DataDB, pDirName)) != 0)
			return err;
		{
			CWaitCursor wait;
			if ((err = m_FolderDB.DBCopy(oldDB)) != 0)
				return err;
		}
		oldDB.DBClose();

		pathName.Format("%s%s", pDirName, "MMDATA1.DBF");
		if ((err = oldDB.DBOpen(pPcserve->m_Pcserve, pathName)) == 0) {
			CWaitCursor wait;
			if ((err = m_DataDB.DBCopy(oldDB)) != 0)
				return err;
		}
		oldDB.DBClose();

		pathName.Format("%s%s", pDirName, "FOLDER2.DBF");
		pPcserve->m_Pcserve.CFDelete(pathName);
		pathName.Format("%s%s", pDirName, "FOLDER2.BIN");
		pPcserve->m_Pcserve.CFDelete(pathName);
		pathName.Format("%s%s", pDirName, "MMDATA1.DBF");
		pPcserve->m_Pcserve.CFDelete(pathName);
		pathName.Format("%s%s", pDirName, "MMDATA1.BIN");
		pPcserve->m_Pcserve.CFDelete(pathName);
	}

	return err;
}

BOOL CMMPcsDB::CreateEx(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, FOLDERREC *pLibraryRec)
{
	int err;

	if ((err = Create(pPcserve, pCachePcserve, pDirName, nOpenMode, pLibraryRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushFolder();
	FlushData();

	return TRUE;
}

void CMMPcsDB::CloseEx()
{
	m_FolderDB.DBClose();
	m_DataDB.DBClose();
	m_FolderDB2.DBClose();
	m_DataDB2.DBClose();
}

BOOL CMMPcsDB::ReadLibraryEx(FOLDERREC *pLibraryRec)
{
	*pLibraryRec = m_LibraryRec;

	return TRUE;
}

BOOL CMMPcsDB::ReadFolderEx(LPCTSTR fid, FOLDERREC *pFolderRec)
{
	int err;

	if ((err = ReadFolder(fid, pFolderRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::ReadChildFolderEx(LPCTSTR fid, FOLDERREC *pFolderRec, BOOL bFirst)
{
	BOOL bRead;
	int err;

	if ((err = ReadChildFolder(fid, pFolderRec, bFirst, &bRead)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return bRead;
}

BOOL CMMPcsDB::AddFolderEx(FOLDERREC *pFolderRec)
{
	int err;

	if (!CheckPermission(PERMISSION_ADD)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = AddFolder(pFolderRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushFolder();

	return TRUE;
}

BOOL CMMPcsDB::UpdateFolderEx(FOLDERREC *pFolderRec)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = UpdateFolder(pFolderRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushFolder();

	return TRUE;
}

BOOL CMMPcsDB::DeleteFolderEx(LPCTSTR fid)
{
	int err;

	if (!CheckPermission(PERMISSION_DELETE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = DeleteFolder(fid)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushFolder();
	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::ReadTextFolderEx(LPCTSTR fid, CString *pText)
{
	int err;

	if ((err = ReadTextFolder(fid, pText)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::WriteTextFolderEx(LPCTSTR fid, CString *pText)
{
	int err;

	if ((err = WriteTextFolder(fid, pText)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::MoveFolderEx(LPCTSTR fidSrc, LPCTSTR fidDst, FOLDERREC *pFolderRec)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = MoveFolder(fidSrc, fidDst, pFolderRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushFolder();

	return TRUE;
}

BOOL CMMPcsDB::CopyFolderEx(CMMPcsDB *pPcsDBSrc, LPCTSTR fidSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, FOLDERREC *pFolderRec)
{
	int err;

	if (!pPcsDBDst->CheckPermission(PERMISSION_ADD)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = CopyFolder(pPcsDBSrc, fidSrc, pPcsDBDst, fidDst, pFolderRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	pPcsDBDst->FlushFolder();
	pPcsDBDst->FlushData();

	return TRUE;
}

BOOL CMMPcsDB::RotateFolderEx(LPCTSTR fid, int nRotate)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = RotateFolder(fid, nRotate)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::ReadDataEx(LPCTSTR did, DATAREC *pDataRec)
{
	int err;

	if ((err = ReadData(did, pDataRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::RotateDataEx(LPCTSTR did, int nRotate)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = RotateData(did, nRotate)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::ReadChildDataEx(LPCTSTR fid, DATAREC *pDataRec, BOOL bFirst)
{
	BOOL bRead;
	int err;

	if ((err = ReadChildData(fid, pDataRec, bFirst, &bRead)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return bRead;
}

BOOL CMMPcsDB::ReadBinaryDataEx(LPCTSTR did, LPCTSTR pFieldName, LPSTR *pPict, long *pPictSize)
{
	int err;

	if ((err = ReadBinaryData(did, pFieldName, pPict, pPictSize)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::ReadPictureDataEx(LPCTSTR did, PHDIB phDIB1, PHDIB phDIB2, DATAREC *pDataRec)
{
	int err;

	if ((err = ReadPictureData(did, phDIB1, phDIB2, pDataRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::WritePictureDataEx(LPCTSTR did, HDIB hDIB1, HDIB hDIB2, short mmType)
{
	int err;

	if ((err = WritePictureData(did, hDIB1, hDIB2, mmType)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::ReadTextDataEx(LPCTSTR did, CString *pText)
{
	int err;

	if ((err = ReadTextData(did, pText)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::WriteTextDataEx(LPCTSTR did, CString *pText)
{
	int err;

	if ((err = WriteTextData(did, pText)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::AddDataEx(DATAREC *pDataRec)
{
	int err;

	if (!CheckPermission(PERMISSION_ADD)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = AddData(pDataRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::UpdateDataEx(DATAREC *pDataRec)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = UpdateData(pDataRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::DeleteDataEx(LPCTSTR did)
{
	int err;

	if (!CheckPermission(PERMISSION_DELETE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = DeleteData(did)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::MoveDataEx(LPCTSTR didSrc, LPCTSTR fidDst, DATAREC *pDataRec)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = MoveData(didSrc, fidDst, pDataRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::CopyDataEx(CMMPcsDB *pPcsDBSrc, LPCTSTR didSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, DATAREC *pDataRec)
{
	int err;

	if (!pPcsDBDst->CheckPermission(PERMISSION_ADD)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = CopyData(pPcsDBSrc, didSrc, pPcsDBDst, fidDst, pDataRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	pPcsDBDst->FlushData();

	return TRUE;
}

BOOL CMMPcsDB::FillCacheEx(LPCTSTR fid)
{
	int err;

	if ((err = FillCache(fid)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::ClearCacheEx()
{
	int err;

	if ((err = ClearCache()) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::FilterFolderEx(LPCTSTR pFilter)
{
	int err;

	if ((err = FilterFolder(pFilter)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::GetFolderCountEx(long *pRecNum)
{
	int err;

	if ((err = GetFolderCount(pRecNum)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::ReadFolderAllEx(FOLDERREC *pFolderRec, BOOL *pRead)
{
	int err;

	if ((err = ReadFolderAll(pFolderRec, pRead)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::FilterDataEx(LPCTSTR pFilter)
{
	int err;

	if ((err = FilterData(pFilter)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::GetDataCountEx(long *pRecNum)
{
	int err;

	if ((err = GetDataCount(pRecNum)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::ReadDataAllEx(DATAREC *pDataRec, BOOL *pRead)
{
	int err;

	if ((err = ReadDataAll(pDataRec, pRead)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::PackFileEx()
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = PackFile()) != 0) {
		PcserveError(err);
		return FALSE;
	}

	FlushFolder();
	FlushData();

	return TRUE;
}

BOOL CMMPcsDB::BackupEx(LPCTSTR pBackupDir, CProgressCtrl *pProgress)
{
	int err;

	if ((err = Backup(pBackupDir, pProgress)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

BOOL CMMPcsDB::RestoreEx(LPCTSTR pBackupDir, CProgressCtrl *pProgress)
{
	int err;

	if (!CheckPermission(PERMISSION_UPDATE)) {
		PcserveError(ERROR_PERMISSION);
		return FALSE;
	}

	if ((err = Restore(pBackupDir, pProgress)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	return TRUE;
}

int CMMPcsDB::Open(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, LPCTSTR pLibraryID)
{
	CString cacheDirName;
	int err;

	m_DirName = pDirName;
	m_nCacheMode = nOpenMode;

	switch (nOpenMode) {
	case CACHEMODE_DIRECT:
		m_pPcserve = pPcserve;
		if ((err = OpenDB(pPcserve, m_FolderDB, m_DataDB, pDirName)) != 0)
			return err;

		AfxGetApp()->DoWaitCursor(0); 

		if ((err = ReadFolder(FID_LIBRARY, &m_LibraryRec)) != 0)
			return err;
		break;
	case CACHEMODE_CACHE:
		m_pPcserve = pPcserve;
		if ((err = OpenDB(pPcserve, m_FolderDB, m_DataDB, pDirName)) != 0)
			return err;

		if ((err = ReadFolder(FID_LIBRARY, &m_LibraryRec)) != 0)
			return err;

		cacheDirName = g_pMMLibDB->m_CacheDir + m_LibraryID + "\\";
		if ((err = OpenDB(pCachePcserve, m_FolderDB2, m_DataDB2, cacheDirName)) != 0) {
			CreateDirectory(cacheDirName, NULL);
			if ((err = CreateDB(pCachePcserve, m_FolderDB2, m_DataDB2, cacheDirName)) != 0)
				return err;
		}
		break;
	case CACHEMODE_OFFLINE:
		m_pPcserve = pCachePcserve;
		m_LibraryID = pLibraryID;
		cacheDirName = g_pMMLibDB->m_CacheDir + m_LibraryID + "\\";
		if ((err = OpenDB(pCachePcserve, m_FolderDB, m_DataDB, cacheDirName)) != 0)
			return err;

		if ((err = ReadFolder(FID_LIBRARY, &m_LibraryRec)) != 0)
			return err;
		break;
	}

	m_sPassword = m_LibraryRec.codeDef;
	m_dwPermission = m_LibraryRec.codeStep;
	if (m_dwPermission > PERMISSION_FULL || m_dwPermission == 0)
		m_dwPermission = PERMISSION_FULL;

	return 0;
}

int CMMPcsDB::OpenDB(CMMPcserve *pPcserve, CPSDB &folderDB, CPSDB &dataDB, LPCTSTR pDirName, BOOL bIndex)
{
	CString pathName;
	int nRecSize;
	int nIndexNo;
	BOOL bCreateIndex;
	int err;

	if (::GetKeyState(VK_CONTROL) & 0x8000)
		bCreateIndex = TRUE;
	else
		bCreateIndex = FALSE;

	// フォルダ管理
	pathName.Format("%s%s", pDirName, FN_FOLDER_DBF);
	if ((err = folderDB.DBOpen(pPcserve->m_Pcserve, pathName)) != 0)
		return err;

	if ((err = folderDB.DBRecSize(&nRecSize)) != 0)
		return err;

	if (nRecSize - 1 != sizeof(FOLDERBUF)) {
//		VersionError(pPcserve->m_nChType, pPcserve->m_ServerName, pDirName);
		return ERROR_VERSION;
	}

	if (bIndex) {
		pathName.Format("%s%s", pDirName, FN_FOLDER_NDX1);
		if (bCreateIndex || (err = folderDB.DBIndex(pathName, &nIndexNo)) != 0) {
			if ((err = folderDB.DBIdxCreate(pathName, "FID", 1, &nIndexNo)) != 0)
				return err;
		}

		pathName.Format("%s%s", pDirName, FN_FOLDER_NDX2);
		if (bCreateIndex || (err = folderDB.DBIndex(pathName, &nIndexNo)) != 0) {
			if ((err = folderDB.DBIdxCreate(pathName, "PFID", 0, &nIndexNo)) != 0)
				return err;
		}
	}

	// データファイル管理
	pathName.Format("%s%s", pDirName, FN_DATA_DBF);
	if ((err = dataDB.DBOpen(pPcserve->m_Pcserve, pathName)) != 0)
			return err;

	if ((err = dataDB.DBRecSize(&nRecSize)) != 0)
		return err;

	if (nRecSize - 1 != sizeof(DATABUF)) {
//		VersionError(pPcserve->m_nChType, pPcserve->m_ServerName, pDirName);
		return ERROR_VERSION;
	}

	if (bIndex) {
		pathName.Format("%s%s", pDirName, FN_DATA_NDX1);
		if (bCreateIndex || (err = dataDB.DBIndex(pathName, &nIndexNo)) != 0) {
			if ((err = dataDB.DBIdxCreate(pathName, "FID", 0, &nIndexNo)) != 0)
				return err;
		}

		pathName.Format("%s%s", pDirName, FN_DATA_NDX2);
		if (bCreateIndex || (err = dataDB.DBIndex(pathName, &nIndexNo)) != 0) {
			if ((err = dataDB.DBIdxCreate(pathName, "DID", 1, &nIndexNo)) != 0)
				return err;
		}
	}

	return 0;
}

int CMMPcsDB::Create(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, FOLDERREC *pLibraryRec)
{
	FOLDERREC folderRec;
	CString cacheDirName;
	int err;

	m_pPcserve = pPcserve;
	m_DirName = pDirName;
	m_nCacheMode = nOpenMode;

	switch (nOpenMode) {
	case CACHEMODE_DIRECT:
		if ((err = CreateDB(pPcserve, m_FolderDB, m_DataDB, pDirName)) != 0)
			return err;
		break;
	case CACHEMODE_CACHE:
		if ((err = CreateDB(pPcserve, m_FolderDB, m_DataDB, pDirName)) != 0)
			return err;

		MakeLibraryID(m_LibraryID);

		cacheDirName = g_pMMLibDB->m_CacheDir + m_LibraryID + "\\";
		CreateDirectory(cacheDirName, NULL);
		if ((err = CreateDB(pCachePcserve, m_FolderDB2, m_DataDB2, cacheDirName)) != 0)
			return err;
		break;
	case CACHEMODE_OFFLINE:
		return ERROR_PROGRAM;
	}

	pLibraryRec->codeStep = PERMISSION_FULL;

	if ((err = AddFolder(pLibraryRec)) != 0) {
		PcserveError(err);
		return FALSE;
	}

	m_LibraryRec = *pLibraryRec;
	m_dwPermission = m_LibraryRec.codeStep;

	return 0;
}

int CMMPcsDB::CreateDB(CMMPcserve *pPcserve, CPSDB &folderDB, CPSDB &dataDB, LPCTSTR pDirName, BOOL bIndex)
{
	static DBF_I   folderField[] = {
		{"FID",       'C', LEN_ID,         0},	// フォルダＩＤ
		{"PFID",      'C', LEN_ID,         0},	// 親フォルダＩＤ
		{"FTYPE",     'C', LEN_FOLDERTYPE, 0},	// フォルダタイプ
		{"FNAME",     'C', LEN_FOLDERNAME, 0},	// フォルダ名
		{"CDATE",     'C', LEN_DATE,       0},	// 作成日付
		{"UDATE",     'C', LEN_DATE,       0},	// 更新日付
		{"CREATOR",   'C', LEN_CREATOR,    0},	// 作成者
		{"REMARK1",   'C', LEN_REMARK,     0},	// 備考
		{"REMARK2",   'C', LEN_KEYWORD,    0},	// キーワード
		{"UCOUNT",    'I', LEN_UCOUNT,     0},	// 更新カウンタ
		{"CODEDEF",   'C', LEN_CODE,       0},	// デフォルトデータコード
		{"CODESTEP",  'I', LEN_CODESTEP,   0},	// コード更新ステップ
		{FORM_FIELD,  'B', 0, 0}				// フォームデータ
	};

	static DBF_I   dataField[] = {
		{"FID",       'C', LEN_ID,      0},		// フォルダＩＤ
		{"DID",       'C', LEN_ID,      0},		// 画像ＩＤ
		{"CDATE",     'C', LEN_DATE,    0},		// 撮影年月日時分
		{"UDATE",     'C', LEN_DATE,    0},		// 更新年月日時分
		{"CREATOR",   'C', LEN_CREATOR, 0},		// 作成者
		{"CODE",      'C', LEN_CODE,    0},		// コード
		{"TITLE",     'C', LEN_TITLE,   0},		// 画像タイトル
		{"REMARK1",   'C', LEN_REMARK,  0},		// 備考
		{"REMARK2",   'C', LEN_KEYWORD, 0},		// キーワード
		{"DPI",       'I', LEN_DPI,     0},		// DPI
		{"WIDTH",     'I', LEN_WIDTH,   0},		// 画像の幅
		{"HEIGHT",    'I', LEN_HEIGHT,  0},		// 画像の高さ
		{"BIT",       'I', LEN_BIT,     0},		// 画像のビット数
		{"UCOUNT",    'I', LEN_UCOUNT,  0},		// 更新カウンタ
		{PICT1_FIELD, 'B', 0, 0},				// 画像データ１
		{PICT2_FIELD, 'B', 0, 0},				// 画像データ２（縮小）
		{TEXT_FIELD,  'B', 0, 0},				// テキストデータ
		{SOUND_FIELD, 'B', 0, 0}				// 音声データ
	};
	CString pathName;
	int nIndexNo;
	int err;

	// フォルダ管理
	pathName.Format("%s%s", pDirName, FN_FOLDER_DBF);
	if ((err = folderDB.DBCreate(pPcserve->m_Pcserve, pathName, folderField, sizeof(folderField) / sizeof(DBF_I))) != 0)
			return err;

	if (bIndex) {
		pathName.Format("%s%s", pDirName, FN_FOLDER_NDX1);
		if ((err = folderDB.DBIdxCreate(pathName, "FID", 1, &nIndexNo)) != 0)
			return err;

		pathName.Format("%s%s", pDirName, FN_FOLDER_NDX2);
		if ((err = folderDB.DBIdxCreate(pathName, "PFID", 0, &nIndexNo)) != 0)
			return err;
	}

	// データファイル管理
	pathName.Format("%s%s", pDirName, FN_DATA_DBF);
	if ((err = dataDB.DBCreate(pPcserve->m_Pcserve, pathName, dataField, sizeof(dataField) / sizeof(DBF_I))) != 0)
			return err;

	if (bIndex) {
		pathName.Format("%s%s", pDirName, FN_DATA_NDX1);
		if ((err = dataDB.DBIdxCreate(pathName, "FID", 0, &nIndexNo)) != 0)
			return err;

		pathName.Format("%s%s", pDirName, FN_DATA_NDX2);
		if ((err = dataDB.DBIdxCreate(pathName, "DID", 1, &nIndexNo)) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::ReadFolder(LPCTSTR fid, FOLDERREC *pFolderRec)
{
	FOLDERBUF folderBuf;
	int find;
	int err;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = m_FolderDB.DBReadKey(fid, LEN_ID, &folderBuf, 0, &find)) != 0)
		return err;

	if (find == 0)
		return ERROR_PROGRAM;

	m_FolderDB.DBGetFieldBuf(&folderBuf, pFolderRec);

	if (pFolderRec->folderID == FID_LIBRARY) {
		if (pFolderRec->parentFolderID.IsEmpty()) {
			MakeLibraryID(pFolderRec->parentFolderID);
			m_FolderDB.DBSetFieldBuf(&folderBuf, pFolderRec);
			m_FolderDB.DBUpdateKey(fid, LEN_ID, &folderBuf);
		}

		m_Title = pFolderRec->folderName;
		m_LibraryID = pFolderRec->parentFolderID;
	}

	return 0;
}

int CMMPcsDB::ReadChildFolder(LPCTSTR fid, FOLDERREC *pFolderRec, BOOL bFirst, BOOL *pRead)
{
	CPSDB *pFolderDB;
	FOLDERBUF folderBuf;
	int nRead;
	int err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pFolderDB = &m_FolderDB2;
	else
		pFolderDB = &m_FolderDB;

	if (bFirst) {
		if (m_nCacheMode == CACHEMODE_CACHE)
			CacheChildFolder(fid);

		if ((err = SetFolderIndex(IDX_FOLDER_PFID)) != 0)
			return err;

		if ((err = pFolderDB->DBSearch(fid, LEN_ID)) != 0)
			return err;
	}

	if ((err = pFolderDB->DBReadNext(1, &folderBuf, &nRead)) != 0)
		return err;

	if (nRead == 0 || memcmp(fid, folderBuf.parentFolderID, LEN_ID) != 0)
		*pRead = FALSE;
	else {
		folderBuf.folderType[0] = toupper(folderBuf.folderType[0]);
		pFolderDB->DBGetFieldBuf(&folderBuf, pFolderRec);
		*pRead = TRUE;
	}

	return 0;
}

int CMMPcsDB::CacheFolder(LPCTSTR fid, LPBOOL pCachedChildren, LPTSTR pFolderType)
{
	FOLDERBUF folderBuf1, folderBuf2;
	int nFind;
	BOOL bCachedChildren = FALSE;
	int err;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = m_FolderDB.DBReadKey(fid, LEN_ID, &folderBuf1, 0, &nFind)) != 0)
		return err;

	if (nFind == 0)
		return ERROR_PROGRAM;

	if (m_FolderDB2.DBReadKey(fid, LEN_ID, &folderBuf2, 0, &nFind) == 0 && nFind == 1) {
		if (isupper(folderBuf2.folderType[0]) && memcmp(folderBuf1.updateCount, folderBuf2.updateCount, LEN_UCOUNT) == 0)
			bCachedChildren = TRUE;
		else {
			if ((err = m_FolderDB2.DBUpdate(&folderBuf1)) != 0)
				return err;
		}
	} else {
		if ((err = m_FolderDB2.DBStoreUniq(&folderBuf1)) != 0)
			return err;
	}

	if (pCachedChildren != NULL)
		*pCachedChildren = bCachedChildren;

	if (pFolderType != NULL)
		*pFolderType = folderBuf1.folderType[0];

	return 0;
}

int CMMPcsDB::CacheChildFolder(LPCTSTR fid)
{
	FOLDERBUF folderBuf1, folderBuf2;
	BOOL bCachedChildren;
	int nFind;
	BOOL bReadFlag;
	long nRec;
	int err;

	if ((err = CacheFolder(fid, &bCachedChildren, NULL)) != 0)
		return err;

	if (bCachedChildren)
		return 0;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = SelectFolder(fid, &nRec)) != 0)
		return err;

	while (--nRec >= 0) {
		if ((err = m_FolderDB.DBReadAll(&folderBuf1, &bReadFlag)) != 0)
			break;

		if (!bReadFlag)
			break;

		folderBuf1.folderType[0] = tolower(folderBuf1.folderType[0]);

		if (m_FolderDB2.DBReadKey(folderBuf1.folderID, LEN_ID, &folderBuf2, 0, &nFind) == 0 && nFind == 1) {
			if (memcmp(folderBuf1.updateCount, folderBuf2.updateCount, LEN_UCOUNT) != 0) {
				if ((err = m_FolderDB2.DBUpdate(&folderBuf1)) != 0)
					return err;
			}
		} else {
			if ((err = m_FolderDB2.DBStoreUniq(&folderBuf1)) != 0)
				return err;
		}
	}

	if ((err = SelectFolder(NULL, &nRec)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::AddFolder(FOLDERREC *pFolderRec)
{
	FOLDERBUF folderBuf;
	FOLDERREC folderRec;
	int err;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if (pFolderRec->folderType == FTYPE_LIBRARY) {
		pFolderRec->parentFolderID = m_LibraryID;

		m_Title = pFolderRec->folderName;
		m_LibraryID = pFolderRec->parentFolderID;
		pFolderRec->folderID = FID_LIBRARY;
	} else {
		if ((err = MakeFolderID(pFolderRec->folderID)) != 0)
			return err;
	}

	if (pFolderRec->createDate.IsEmpty())
		SetCurrentTime(pFolderRec->createDate);
	if (pFolderRec->updateDate.IsEmpty())
		pFolderRec->updateDate = pFolderRec->createDate;
	pFolderRec->updateCount = 0;

	m_FolderDB.DBSetFieldBuf(&folderBuf, pFolderRec);

	if ((err = m_FolderDB.DBStoreUniq(&folderBuf)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		m_FolderDB2.DBDeleteKey(folderBuf.folderID, LEN_ID, 1);
		if ((err = m_FolderDB2.DBStoreUniq(&folderBuf)) != 0)
			return err;
	}

	UpdateFolderUpdateCount(folderBuf.parentFolderID);

	return 0;
}

int CMMPcsDB::UpdateFolder(FOLDERREC *pFolderRec)
{
	FOLDERBUF folderBuf;
	int err;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if (pFolderRec->folderType == FTYPE_LIBRARY) {
		m_Title = pFolderRec->folderName;
		m_LibraryID = pFolderRec->parentFolderID;
	}

	SetCurrentTime(pFolderRec->updateDate);
	(pFolderRec->updateCount)++;

	m_FolderDB.DBSetFieldBuf(&folderBuf, pFolderRec);

	if ((err = m_FolderDB.DBUpdateKey(pFolderRec->folderID, LEN_ID, &folderBuf)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		m_FolderDB2.DBDeleteKey(folderBuf.folderID, LEN_ID, 1);
		if ((err = m_FolderDB2.DBStoreUniq(&folderBuf)) != 0)
			return err;
	}

	UpdateFolderUpdateCount(folderBuf.parentFolderID);

	return 0;
}

int CMMPcsDB::DeleteFolder(LPCTSTR fid)
{
	FOLDERBUF folderBuf;
	int find;
	int err;

	for (;;) {
		if ((err = SetFolderIndex(IDX_FOLDER_PFID)) != 0)
			return err;

		if ((err = m_FolderDB.DBSearch2(fid, LEN_ID, &find)) != 0)
			return err;

		if (find != 1)
			break;

		if ((err = m_FolderDB.DBRead(&folderBuf)) != 0)
			return err;

		if ((err = DeleteFolder(folderBuf.folderID)) != 0)
			return err;
	}

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = m_FolderDB.DBDeleteKey(fid, LEN_ID, 1)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		m_FolderDB2.DBDeleteKey(fid, LEN_ID, 1);
	}

	if ((err = SetDataIndex(IDX_DATA_FID)) != 0)
		return err;

	if ((err = m_DataDB.DBDeleteKey(fid, LEN_ID, 1)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		m_FolderDB2.DBDeleteKey(fid, LEN_ID, 1);
	}

	return 0;
}

int CMMPcsDB::ReadTextFolder(LPCTSTR fid, CString *pText)
{
	CPSDB *pFolderDB;
	FOLDERBUF folderBuf;
	int find;
	long size;
	int err;

	pText->Empty();

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pFolderDB = &m_FolderDB2;
	else
		pFolderDB = &m_FolderDB;

	if ((err = pFolderDB->DBSearch2(fid, LEN_ID, &find)) != 0)
		return err;

	if (find != 1) {
		if (m_nCacheMode == CACHEMODE_CACHE) {
			if ((err = m_FolderDB.DBReadKey(fid, LEN_ID, &folderBuf, 0, &find)) != 0)
				return err;
			if (find == 1)
				m_FolderDB2.DBStoreUniq(&folderBuf);
			else
				return ERROR_PROGRAM;
		} else
			return ERROR_PROGRAM;
	}

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if (pText != NULL)
			CacheBinFldFolder(fid, FORM_FIELD);
	}

	if (pText != NULL) {
		if (pFolderDB->DBGetBinarySize(FORM_FIELD, &size) == 0 && size != 0) {
			pFolderDB->DBReadBinary(FORM_FIELD, pText->GetBuffer(size), size, &size);
			pText->ReleaseBuffer(size);
		}
	}

	return 0;
}

int CMMPcsDB::WriteTextFolder(LPCTSTR fid, CString *pText)
{
	CPSDB *pFolderDB;
	int find;
	int err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pFolderDB = &m_FolderDB2;
	else
		pFolderDB = &m_FolderDB;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = pFolderDB->DBSearch2(fid, LEN_ID, &find)) != 0)
		return err;

	if (find != 1)
		return ERROR_PROGRAM;

	if (pText != NULL && pText->GetLength() != 0) {
		if ((err = pFolderDB->DBWriteBinary(FORM_FIELD, (LPVOID)(LPCTSTR)*pText, pText->GetLength())) != 0)
			return err;
	} else {
		if ((err = pFolderDB->DBWriteBinary(FORM_FIELD, NULL, 0)) != 0)
			return err;
	}

	if (m_nCacheMode == CACHEMODE_CACHE)
		WriteTextCacheFolder(fid);

	return 0;
}

int CMMPcsDB::CacheBinFldFolder(LPCTSTR fid, LPCTSTR pFieldName)
{
	long size;
	int find;
	int err;

	if (m_FolderDB2.DBGetBinarySize(pFieldName, &size) == 0 && size == 0) {
		if (m_FolderDB.DBSearch2(fid, LEN_ID, &find) == 0 && find == 1) {
			if ((err = CacheBinaryFolder(pFieldName)) != 0)
				return err;
		}
	}

	return 0;
}

int CMMPcsDB::MoveFolder(LPCTSTR fidSrc, LPCTSTR fidDst, FOLDERREC *pFolderRec)
{
	FOLDERREC folderRec;
	int err;

	if ((err = ReadFolder(fidSrc, &folderRec)) != 0)
		return err;

	folderRec.parentFolderID = fidDst;

	if ((err = UpdateFolder(&folderRec)) != 0)
		return err;

	if (pFolderRec != NULL)
		*pFolderRec = folderRec;

	return 0;
}

int CMMPcsDB::CopyFolder(CMMPcsDB *pPcsDBSrc, LPCTSTR fidSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, FOLDERREC *pFolderRec)
{
	FOLDERREC folderRec;
	char (*pFID)[LEN_ID];
	long nRec;
	int nRead;
	int nLen;
	int i;
	int err;

	if ((err = pPcsDBSrc->ReadFolder(fidSrc, &folderRec)) != 0)
		return err;

	folderRec.parentFolderID = fidDst;

	if ((err = pPcsDBDst->AddFolder(&folderRec)) != 0)
		return err;

	switch (folderRec.folderType[0]) {
	case FTYPE_GFOLDER:
		if ((err = pPcsDBSrc->SelectFolder(fidSrc, &nRec)) != 0)
			return err;

		if ((err = pPcsDBSrc->m_FolderDB.DBSetReadField("FID", &nLen)) != 0)
			return err;

		pFID = new char[nRec][LEN_ID];
		if ((err = pPcsDBSrc->m_FolderDB.DBReadNext(nRec, pFID, &nRead)) != 0) {
			delete [] pFID;
			return err;
		}

		if ((err = pPcsDBSrc->SelectFolder(NULL, &nRec)) != 0) {
			delete [] pFID;
			return err;
		}

		if ((err = pPcsDBSrc->m_FolderDB.DBSetReadField(NULL, &nLen)) != 0) {
			delete [] pFID;
			return err;
		}

		for (i = 0; i < nRead; i++) {
			if ((err = CopyFolder(pPcsDBSrc, pFID[i], pPcsDBDst, folderRec.folderID)) != 0) {
				delete [] pFID;
				return err;
			}
		}

		delete [] pFID;
		break;
	case FTYPE_DFOLDER:
		if ((err = CopyChildData(pPcsDBSrc, fidSrc, pPcsDBDst, folderRec.folderID)) != 0)
			return err;
		break;
	}

	if (pFolderRec != NULL)
		*pFolderRec = folderRec;

	return 0;
}

int CMMPcsDB::RotateFolder(LPCTSTR fid, int nRotate)
{
	FOLDERREC folderRec;
	char (*pID)[LEN_ID];
	long nRec;
	int nRead;
	int nLen;
	int i;
	int err;

	if ((err = ReadFolder(fid, &folderRec)) != 0)
		return err;

	switch (folderRec.folderType[0]) {
	case FTYPE_LIBRARY:
	case FTYPE_GFOLDER:
		if ((err = SelectFolder(fid, &nRec)) != 0)
			return err;

		if ((err = m_FolderDB.DBSetReadField("FID", &nLen)) != 0)
			return err;

		pID = new char[nRec][LEN_ID];
		if ((err = m_FolderDB.DBReadNext(nRec, pID, &nRead)) != 0) {
			delete [] pID;
			return err;
		}

		if ((err = SelectFolder(NULL, &nRec)) != 0) {
			delete [] pID;
			return err;
		}

		if ((err = m_FolderDB.DBSetReadField(NULL, &nLen)) != 0) {
			delete [] pID;
			return err;
		}

		for (i = 0; i < nRead; i++) {
			if ((err = RotateFolder(pID[i], nRotate)) != 0) {
				delete [] pID;
				return err;
			}
		}

		delete [] pID;
		break;
	case FTYPE_DFOLDER:
		if ((err = SelectData(fid, &nRec)) != 0)
			return err;

		if ((err = m_DataDB.DBSetReadField("DID", &nLen)) != 0)
			return err;

		pID = new char[nRec][LEN_ID];
		if ((err = m_DataDB.DBReadNext(nRec, pID, &nRead)) != 0) {
			delete [] pID;
			return err;
		}

		if ((err = SelectData(NULL, &nRec)) != 0) {
			delete [] pID;
			return err;
		}

		if ((err = m_DataDB.DBSetReadField(NULL, &nLen)) != 0) {
			delete [] pID;
			return err;
		}

		for (i = 0; i < nRead; i++) {
			if ((err = RotateData(pID[i], nRotate)) != 0) {
				delete [] pID;
				return err;
			}
		}

		delete [] pID;
		break;
	}

	return 0;
}

int CMMPcsDB::RotateData(LPCTSTR did, int nRotate)
{
	HDIB hDIB, hDIB2;
	DATAREC dataRec;
	short width, height, color;
	int err;

	if ((err = ReadPictureData(did, &hDIB, NULL, &dataRec)) != 0)
		return err;

	if ((err = MMPictureRotate(hDIB, nRotate)) != 0) {
		MMPictureFree(hDIB);
		return err;
	}

	MMPictureGetInfo(hDIB, &width, &height, &color);
	dataRec.width = width;
	dataRec.height = height;

	if ((err = UpdateData(&dataRec)) != 0) {
		MMPictureFree(hDIB);
		return err;
	}

	hDIB2 = MakeSmallPicture(hDIB, PICT_WIDTH, PICT_HEIGHT);

	WritePictureData(did, hDIB, hDIB2, GetMMType(hDIB));

	MMPictureFree(hDIB);
	MMPictureFree(hDIB2);

	return 0;
}

int CMMPcsDB::ReadData(LPCTSTR did, DATAREC *pDataRec)
{
	DATABUF dataBuf;
	int find;
	int err;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if ((err = m_DataDB.DBReadKey(did, LEN_ID, &dataBuf, 0, &find)) != 0)
		return err;

	m_DataDB.DBGetFieldBuf(&dataBuf, pDataRec);

	return 0;
}

int CMMPcsDB::ReadChildData(LPCTSTR fid, DATAREC *pDataRec, BOOL bFirst, BOOL *pRead)
{
	CPSDB *pDataDB;
	DATABUF dataBuf;
	int nRead;
	int err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pDataDB = &m_DataDB2;
	else
		pDataDB = &m_DataDB;

	if (bFirst) {
		if (m_nCacheMode == CACHEMODE_CACHE)
			CacheChildData(fid);

		if ((err = SetDataIndex(IDX_DATA_FID)) != 0)
			return err;

		if ((err = pDataDB->DBSearch(fid, LEN_ID)) != 0)
			return err;
	}

	if ((err = pDataDB->DBReadNext(1, &dataBuf, &nRead)) != 0)
		return err;

	if (nRead == 0 || strncmp(fid, dataBuf.folderID, LEN_ID) != 0)
		*pRead = FALSE;
	else {
		pDataDB->DBGetFieldBuf(&dataBuf, pDataRec);
		*pRead = TRUE;
	}

	return 0;
}

int CMMPcsDB::ReadBinaryData(LPCTSTR did, LPCTSTR pFieldName, LPSTR *pPict, long *pPictSize)
{
	CPSDB *pDataDB;
	int find;
	long size;
	int err;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pDataDB = &m_DataDB2;
	else
		pDataDB = &m_DataDB;

	if ((err = pDataDB->DBSearch2(did, LEN_ID, &find)) != 0)
		return err;

	if (find != 1)
		return ERROR_PROGRAM;

	if (m_nCacheMode == CACHEMODE_CACHE)
		CacheBinFldData(did, pFieldName);

	if ((err = pDataDB->DBGetBinarySize(pFieldName, &size)) != 0)
		return err;

	if (size != 0) {
		*pPict = new char[size];
		if ((err = pDataDB->DBReadBinary(pFieldName, *pPict, size, pPictSize)) != 0) {
			delete [] pPict;
			return err;
		}
	} else {
		*pPict = NULL;
		*pPictSize = 0;
	}

	return 0;
}

int CMMPcsDB::CacheChildData(LPCTSTR fid)
{
	DATABUF dataBuf1, dataBuf2;
	BOOL bCachedChildren;
	BOOL bReadFlag;
	int nFind;
	long nRec;
	int err;

	if ((err = CacheFolder(fid, &bCachedChildren, NULL)) != 0)
		return err;

	if (bCachedChildren)
		return 0;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if ((err = SelectData(fid, &nRec)) != 0)
		return err;

	while (--nRec >= 0) {
		if ((err = m_DataDB.DBReadAll(&dataBuf1, &bReadFlag)) != 0)
			break;

		if (!bReadFlag)
			break;

		if (m_DataDB2.DBReadKey(dataBuf1.dataID, LEN_ID, &dataBuf2, 0, &nFind) == 0 && nFind == 1) {
			if (memcmp(dataBuf1.updateCount, dataBuf2.updateCount, LEN_UCOUNT) != 0) {
				if ((err = m_DataDB2.DBUpdate(&dataBuf1)) != 0)
					return err;

				m_DataDB2.DBWriteBinary(PICT1_FIELD, NULL, 0);
				m_DataDB2.DBWriteBinary(PICT2_FIELD, NULL, 0);
			}
		} else {
			if ((err = m_DataDB2.DBStoreUniq(&dataBuf1)) != 0)
				return err;
		}
	}

	if ((err = SelectData(NULL, &nRec)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::CacheBinaryFolder(LPCTSTR pFieldName)
{
	return CacheBinary(pFieldName, m_FolderDB, m_FolderDB2);
}

int CMMPcsDB::CacheBinaryData(LPCTSTR pFieldName)
{
	return CacheBinary(pFieldName, m_DataDB, m_DataDB2);
}

int CMMPcsDB::CacheBinary(LPCTSTR pFieldName, CPSDB &DB, CPSDB &DB2)
{
	long size;
	long nReadNum;
	int err;

	if ((err = DB.DBGetBinarySize(pFieldName, &size)) != 0)
		return err;

	char *pBuf = new char[size];

	if ((err = DB.DBReadBinary(pFieldName, pBuf, size, &nReadNum)) != 0) {
		delete pBuf;
		return err;
	}

	if ((err = DB2.DBWriteBinary(pFieldName, pBuf, nReadNum)) != 0) {
		delete pBuf;
		return err;
	}

	delete pBuf;

	return 0;
}

int CMMPcsDB::ReadPictureData(LPCTSTR did, PHDIB phDIB1, PHDIB phDIB2, DATAREC *pDataRec)
{
	CPSDB *pDataDB;
	DATABUF dataBuf;
	DATAREC dataRec;
	int find;
	int err;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pDataDB = &m_DataDB2;
	else
		pDataDB = &m_DataDB;

	if ((err = pDataDB->DBReadKey(did, LEN_ID, &dataBuf, 0, &find)) != 0)
		return err;

	if (find != 1) {
		if (m_nCacheMode == CACHEMODE_CACHE) {
			if ((err = m_DataDB.DBReadKey(did, LEN_ID, &dataBuf, 0, &find)) != 0)
				return err;
			if (find == 1)
				m_DataDB2.DBStoreUniq(&dataBuf);
			else
				return ERROR_PROGRAM;
		} else
			return ERROR_PROGRAM;
	}

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if (phDIB1 != NULL)
			CacheBinFldData(did, PICT1_FIELD);
		if (phDIB2 != NULL)
			CacheBinFldData(did, PICT2_FIELD);
	}

	if (pDataRec == NULL)
		pDataRec = &dataRec;

	pDataDB->DBGetFieldBuf(&dataBuf, pDataRec);

	if (phDIB1 != NULL) {
		if (MMPictureReadDB(phDIB1, pDataDB->GetFCB(), PICT1_FIELD) != 0)
			*phDIB1 = NULL;
		else {
			if (pDataRec->dpi > 0 && pDataRec->dpi < 10000) {
				LPBITMAPINFO lpBmi;
				lpBmi = (LPBITMAPINFO)::GlobalLock(*phDIB1);
				if (lpBmi->bmiHeader.biXPelsPerMeter == 0)
					lpBmi->bmiHeader.biYPelsPerMeter = lpBmi->bmiHeader.biXPelsPerMeter = (long)(pDataRec->dpi / 0.0254 + 0.5);
				::GlobalUnlock(*phDIB1);
			}
		}
	}

	if (phDIB2 != NULL) {
		if (MMPictureReadDB(phDIB2, pDataDB->GetFCB(), PICT2_FIELD) != 0)
			*phDIB2 = NULL;
	}

	return 0;
}

int CMMPcsDB::CacheBinFldData(LPCTSTR did, LPCTSTR pFieldName)
{
	long size;
	int find;
	int err;

	if (m_DataDB2.DBGetBinarySize(pFieldName, &size) == 0 && size == 0) {
		if (m_DataDB.DBSearch2(did, LEN_ID, &find) == 0 && find == 1) {
			if ((err = CacheBinaryData(pFieldName)) != 0)
				return err;
		}
	}

	return 0;
}

int CMMPcsDB::WritePictureData(LPCTSTR did, HDIB hDIB1, HDIB hDIB2, short mmType)
{
	CPSDB *pDataDB;
	int find;
	int err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pDataDB = &m_DataDB2;
	else
		pDataDB = &m_DataDB;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if ((err = pDataDB->DBSearch2(did, LEN_ID, &find)) != 0)
		return err;

	if (find != 1)
		return ERROR_PROGRAM;

	if (hDIB1 != NULL) {
		if ((err = MMPictureWriteDB(hDIB1, pDataDB->GetFCB(), PICT1_FIELD, mmType)) != 0)
			return err;
	} else {
		if ((err = pDataDB->DBWriteBinary(PICT1_FIELD, NULL, 0)) != 0)
			return err;
	}

	if (hDIB2 != NULL) {
		if ((err = MMPictureWriteDB(hDIB2, pDataDB->GetFCB(), PICT2_FIELD, mmType)) != 0)
			return err;
	} else {
		if ((err = pDataDB->DBWriteBinary(PICT2_FIELD, NULL, 0)) != 0)
			return err;
	}

	if (m_nCacheMode == CACHEMODE_CACHE)
		WritePictureCacheData(did);

	return 0;
}

int CMMPcsDB::ReadTextData(LPCTSTR did, CString *pText)
{
	CPSDB *pDataDB;
	DATABUF dataBuf;
	int find;
	long size;
	int err;

	pText->Empty();

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pDataDB = &m_DataDB2;
	else
		pDataDB = &m_DataDB;

	if ((err = pDataDB->DBSearch2(did, LEN_ID, &find)) != 0)
		return err;

	if (find != 1) {
		if (m_nCacheMode == CACHEMODE_CACHE) {
			if ((err = m_DataDB.DBReadKey(did, LEN_ID, &dataBuf, 0, &find)) != 0)
				return err;
			if (find == 1)
				m_DataDB2.DBStoreUniq(&dataBuf);
			else
				return ERROR_PROGRAM;
		} else
			return ERROR_PROGRAM;
	}

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if (pText != NULL)
			CacheBinFldData(did, TEXT_FIELD);
	}

	if (pText != NULL) {
		if (pDataDB->DBGetBinarySize(TEXT_FIELD, &size) == 0 && size != 0) {
			pDataDB->DBReadBinary(TEXT_FIELD, pText->GetBuffer(size), size, &size);
			pText->ReleaseBuffer(size);
		}
	}

	return 0;
}

int CMMPcsDB::WriteTextData(LPCTSTR did, CString *pText)
{
	CPSDB *pDataDB;
	int find;
	int err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		pDataDB = &m_DataDB2;
	else
		pDataDB = &m_DataDB;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if ((err = pDataDB->DBSearch2(did, LEN_ID, &find)) != 0)
		return err;

	if (find != 1)
		return ERROR_PROGRAM;

	if (pText != NULL && pText->GetLength() != 0) {
		if ((err = pDataDB->DBWriteBinary(TEXT_FIELD, (LPVOID)(LPCTSTR)*pText, pText->GetLength())) != 0)
			return err;
	} else {
		if ((err = pDataDB->DBWriteBinary(TEXT_FIELD, NULL, 0)) != 0)
			return err;
	}

	if (m_nCacheMode == CACHEMODE_CACHE)
		WriteTextCacheData(did);

	return 0;
}

int CMMPcsDB::WritePictureCacheData(LPCTSTR did)
{
	int find;

	if (m_DataDB2.DBSearch2(did, LEN_ID, &find) == 0 && find == 1) {
		if (m_DataDB.DBSearch2(did, LEN_ID, &find) == 0 && find == 1) {
			WriteCacheBinary(did, m_DataDB, m_DataDB2, PICT1_FIELD);
			WriteCacheBinary(did, m_DataDB, m_DataDB2, PICT2_FIELD);
		}
	}

	return 0;
}

int CMMPcsDB::WriteTextCacheData(LPCTSTR did)
{
	int find;

	if (m_DataDB2.DBSearch2(did, LEN_ID, &find) == 0 && find == 1) {
		if (m_DataDB.DBSearch2(did, LEN_ID, &find) == 0 && find == 1) {
			WriteCacheBinary(did, m_DataDB, m_DataDB2, TEXT_FIELD);
		}
	}

	return 0;
}

int CMMPcsDB::WriteTextCacheFolder(LPCTSTR fid)
{
	int find;

	if (m_DataDB2.DBSearch2(fid, LEN_ID, &find) == 0 && find == 1) {
		if (m_DataDB.DBSearch2(fid, LEN_ID, &find) == 0 && find == 1) {
			WriteCacheBinary(fid, m_DataDB, m_DataDB2, FORM_FIELD);
		}
	}

	return 0;
}

void CMMPcsDB::WriteCacheBinary(LPCTSTR id, CPSDB &DB, CPSDB &DB2, LPCTSTR pFieldName)
{
	long size;
	long nReadNum;
	char *pBuf;

	if (DB2.DBGetBinarySize(pFieldName, &size) == 0) {
		if (size != 0) {
			pBuf = new char [size];
			if (DB2.DBReadBinary(pFieldName, pBuf, size, &nReadNum) == 0)
				DB.DBWriteBinary(pFieldName, pBuf, nReadNum);
			delete pBuf;
		} else
			DB.DBWriteBinary(pFieldName, NULL, 0);
	}
}

int CMMPcsDB::AddData(DATAREC *pDataRec)
{
	DATABUF dataBuf;
	DATAREC dataRec;
	int err;

	if ((err = MakeDataID(pDataRec->dataID)) != 0)
		return err;

	if (pDataRec->createDate.IsEmpty())
		SetCurrentTime(pDataRec->createDate);
	if (pDataRec->updateDate.IsEmpty())
		pDataRec->updateDate = pDataRec->createDate;
	pDataRec->updateCount = 0;

	m_DataDB.DBSetFieldBuf(&dataBuf, pDataRec);

	if ((err = m_DataDB.DBStoreUniq(&dataBuf)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		m_DataDB2.DBDeleteKey(dataBuf.dataID, LEN_ID, 1);
		if ((err = m_DataDB2.DBStoreUniq(&dataBuf)) != 0)
			return err;
	}

	UpdateFolderUpdateCount(dataBuf.folderID);

	return 0;
}

int CMMPcsDB::UpdateData(DATAREC *pDataRec)
{
	DATABUF dataBuf;
	int err;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	SetCurrentTime(pDataRec->updateDate);
	(pDataRec->updateCount)++;

	m_DataDB.DBSetFieldBuf(&dataBuf, pDataRec);

	if ((err = m_DataDB.DBUpdateKey(pDataRec->dataID, LEN_ID, &dataBuf)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		m_DataDB2.DBDeleteKey(dataBuf.dataID, LEN_ID, 1);
		if ((err = m_DataDB2.DBStoreUniq(&dataBuf)) != 0)
			return err;
	}

	UpdateFolderUpdateCount(dataBuf.folderID);

	return 0;
}

int CMMPcsDB::DeleteData(LPCTSTR did)
{
	int err;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if ((err = m_DataDB.DBDeleteKey(did, LEN_ID, 1)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if ((err = m_DataDB2.DBDeleteKey(did, LEN_ID, 1)) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::MoveData(LPCTSTR didSrc, LPCTSTR fidDst, DATAREC *pDataRec)
{
	DATAREC dataRec;
	int err;

	if ((err = ReadData(didSrc, &dataRec)) != 0)
		return err;

	dataRec.folderID = fidDst;

	if ((err = UpdateData(&dataRec)) != 0)
		return err;

	if (pDataRec != NULL)
		*pDataRec = dataRec;

	return 0;
}

int CMMPcsDB::CopyData(CMMPcsDB *pPcsDBSrc, LPCTSTR didSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, DATAREC *pDataRec)
{
	DATAREC dataRec;
	long recNoSrc, recNoDst;
	int err;

	if ((err = pPcsDBSrc->ReadData(didSrc, &dataRec)) != 0)
		return err;

	if ((err = pPcsDBSrc->m_DataDB.DBRecNo(&recNoSrc)) != 0)
		return err;

	dataRec.folderID = fidDst;

	if ((err = pPcsDBDst->AddData(&dataRec)) != 0)
		return err;

	if ((err = pPcsDBDst->m_DataDB.DBRecNo(&recNoDst)) != 0)
		return err;

	if ((err = CopyBinary(pPcsDBSrc->m_DataDB, recNoSrc, pPcsDBDst->m_DataDB, recNoDst, PICT1_FIELD)) != 0)
		return err;

	if ((err = CopyBinary(pPcsDBSrc->m_DataDB, recNoSrc, pPcsDBDst->m_DataDB, recNoDst, PICT2_FIELD)) != 0)
		return err;

	if ((err = CopyBinary(pPcsDBSrc->m_DataDB, recNoSrc, pPcsDBDst->m_DataDB, recNoDst, TEXT_FIELD)) != 0)
		return err;

	if (pDataRec != NULL)
		*pDataRec = dataRec;

	return 0;
}

int CMMPcsDB::CopyChildData(CMMPcsDB *pPcsDBSrc, LPCTSTR fidSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst)
{
	DATAREC dataRec;
	DATABUF dataBuf;
	BOOL eof;
	long recNoSrc, recNoDst;
	int err;

	if ((err = pPcsDBSrc->SetDataIndex(IDX_DATA_FID)) != 0)
		return err;

	if ((err = pPcsDBSrc->m_DataDB.DBSearch(fidSrc, LEN_ID)) != 0)
		return err;

	for (;;) {
		if ((err = pPcsDBSrc->m_DataDB.DBEof(&eof)) != 0)
			return err;

		if (eof)
			break;

		if ((err = pPcsDBSrc->m_DataDB.DBRead(&dataBuf)) != 0)
			return err;

		if (strncmp(fidSrc, dataBuf.folderID, LEN_ID) != 0)
			break;

		pPcsDBSrc->m_DataDB.DBGetFieldBuf(&dataBuf, &dataRec);
		dataRec.folderID = fidDst;

		if ((err = pPcsDBSrc->m_DataDB.DBRecNo(&recNoSrc)) != 0)
			return err;

		if ((err = pPcsDBDst->AddData(&dataRec)) != 0)
			return err;

		if ((err = pPcsDBDst->m_DataDB.DBRecNo(&recNoDst)) != 0)
			return err;

		if ((err = CopyBinary(pPcsDBSrc->m_DataDB, recNoSrc, pPcsDBDst->m_DataDB, recNoDst, PICT1_FIELD)) != 0)
			return err;

		if ((err = CopyBinary(pPcsDBSrc->m_DataDB, recNoSrc, pPcsDBDst->m_DataDB, recNoDst, PICT2_FIELD)) != 0)
			return err;

		if ((err = CopyBinary(pPcsDBSrc->m_DataDB, recNoSrc, pPcsDBDst->m_DataDB, recNoDst, TEXT_FIELD)) != 0)
			return err;

		if ((err = pPcsDBSrc->SetDataIndex(IDX_DATA_FID)) != 0)
			return err;

		if ((err = pPcsDBSrc->m_DataDB.DBSet(recNoSrc)) != 0)
			return err;

		if ((err = pPcsDBSrc->m_DataDB.DBSkip(1)) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::CopyBinary(CPSDB &srcDB, long recNoSrc, CPSDB &dstDB, long recNoDst, LPCTSTR fieldName)
{
	long size;
	int err;

	if ((err = srcDB.DBSet(recNoSrc)) != 0)
		return err;

	if ((err = srcDB.DBGetBinarySize(fieldName, &size)) != 0)
		return err;

	char *pBuf = new char [size];
	long readNum;

	if ((err = srcDB.DBReadBinary(fieldName, pBuf, size, &readNum)) != 0) {
		delete [] pBuf;
		return err;
	}

	if ((err = dstDB.DBSet(recNoDst)) != 0) {
		delete [] pBuf;
		return err;
	}

	if ((err = dstDB.DBWriteBinary(fieldName, pBuf, readNum)) != 0) {
		delete [] pBuf;
		return err;
	}

	delete [] pBuf;

	return 0;
}

int CMMPcsDB::SetFolderIndex(int nIndexNo, int nIndexNo2)
{
	int err;

	if (nIndexNo2 == -1)
		nIndexNo2 = nIndexNo;

	if (nIndexNo != m_nCurrentFolderIndex) {
		if ((err = m_FolderDB.DBChgIdx(nIndexNo)) != 0)
			return err;

			m_nCurrentFolderIndex = nIndexNo;
	}

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if (nIndexNo2 != m_nCurrentFolderIndex2) {
			if ((err = m_FolderDB2.DBChgIdx(nIndexNo2)) != 0)
				return err;

			m_nCurrentFolderIndex2 = nIndexNo2;
		}
	}

	return 0;
}

int CMMPcsDB::SetDataIndex(int nIndexNo, int nIndexNo2)
{
	int err;

	if (nIndexNo2 == -1)
		nIndexNo2 = nIndexNo;

	if (nIndexNo != m_nCurrentDataIndex) {
		if ((err = m_DataDB.DBChgIdx(nIndexNo)) != 0)
			return err;

			m_nCurrentDataIndex = nIndexNo;
	}

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if (nIndexNo2 != m_nCurrentDataIndex2) {
			if ((err = m_DataDB2.DBChgIdx(nIndexNo2)) != 0)
				return err;

			m_nCurrentDataIndex2 = nIndexNo2;
		}
	}

	return 0;
}

void CMMPcsDB::MakeLibraryID(CString &id)
{
	time_t lid;
	static long offset;

	time(&lid);
	lid += offset++;
	MakeID('L', (DWORD)lid, id);
}

int CMMPcsDB::MakeFolderID(CString &id)
{
	time_t ltime;
	int find;
	int err;

	time(&ltime);

	for (;;) {
		MakeID('F', (DWORD)ltime, id);

		if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
			return err;

		if ((err = m_FolderDB.DBSearch2(id, LEN_ID, &find)) != 0)
			return err;

		if (find != 1)
			break;

		ltime++;
	}

	return 0;
}

int CMMPcsDB::MakeDataID(CString &id)
{
	time_t ltime;
	int find;
	int err;

	time(&ltime);

	for (;;) {
		MakeID('D', (DWORD)ltime, id);

		if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
			return err;

		if ((err = m_DataDB.DBSearch2(id, LEN_ID, &find)) != 0)
			return err;

		if (find != 1)
			break;

		ltime++;
	}

	return 0;
}

void CMMPcsDB::SetCurrentTime(CString &time)
{
	STIME stime;

	m_pPcserve->m_Pcserve.GetTime(&stime);
	time.Format("%04d%02d%02d%02d%02d", stime.year, stime.month, stime.day, stime.hour, stime.minute);
}

int CMMPcsDB::UpdateFolderUpdateCount(LPCTSTR fid)
{
	FOLDERBUF folderBuf;
	FOLDERREC folderRec;
	CString updateTime;
	int find;
	int err;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = m_FolderDB.DBReadKey(fid, LEN_ID, &folderBuf, 0, &find)) != 0)
		return err;

	if (find == 0)
		return ERROR_PROGRAM;

	m_FolderDB.DBGetFieldBuf(&folderBuf, &folderRec);

	(folderRec.updateCount)++;

	m_FolderDB.DBSetFieldBuf(&folderBuf, &folderRec);

	if ((err = m_FolderDB.DBUpdateKey(fid, LEN_ID, &folderBuf)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::FillCache(LPCTSTR fid)
{
	if (m_nCacheMode != CACHEMODE_CACHE)
		return ERROR_PROGRAM;

	char folderType;
	char (*pFID)[LEN_ID];
	long nRec;
	int nRead;
	int nLen;
	int i;
	int err;

	if ((err = CacheFolder(fid, NULL, &folderType)) != 0)
		return err;

	switch (folderType) {
	case FTYPE_LIBRARY:
	case FTYPE_GFOLDER:
		if ((err = SelectFolder(fid, &nRec)) != NULL)
			return err;

		if ((err = m_FolderDB.DBSetReadField("FID", &nLen)) != 0)
			return err;

		pFID = new char[nRec][LEN_ID];
		if ((err = m_FolderDB.DBReadNext(nRec, pFID, &nRead)) != 0) {
			delete [] pFID;
			return err;
		}

		if ((err = SelectFolder(NULL, &nRec)) != 0) {
			delete [] pFID;
			return err;
		}

		if ((err = m_FolderDB.DBSetReadField(NULL, &nLen)) != 0) {
			delete [] pFID;
			return err;
		}

		for (i = 0; i < nRead; i++) {
			if ((err = FillCache(pFID[i])) != 0) {
				delete [] pFID;
				return err;
			}
		}

		delete [] pFID;
		break;
	case FTYPE_DFOLDER:
		if ((err = FillDataCache(fid)) != 0)
			return err;
		break;
	}

	return 0;
}

int CMMPcsDB::FillDataCache(LPCTSTR fid)
{
	DATABUF dataBuf1, dataBuf2;
	int nFind;
	BOOL eof;
	BOOL bBinaryCopy;
	long size;
	int err;

	if ((err = SetDataIndex(IDX_DATA_FID, IDX_DATA_DID)) != 0)
		return err;

	if ((err = m_DataDB.DBSearch(fid, LEN_ID)) != 0)
		return err;

	for (;;) {
		if ((err = m_DataDB.DBEof(&eof)) != 0)
			return err;

		if (eof)
			break;

		if ((err = m_DataDB.DBRead(&dataBuf1)) != 0)
			return err;

		if (strncmp(fid, dataBuf1.folderID, LEN_ID) != 0)
			break;

		if (m_DataDB2.DBReadKey(dataBuf1.dataID, LEN_ID, &dataBuf2, 0, &nFind) == 0 && nFind == 1) {
			if (memcmp(dataBuf1.updateCount, dataBuf2.updateCount, LEN_UCOUNT) != 0) {
				if ((err = m_DataDB2.DBUpdate(&dataBuf1)) != 0)
					return err;

				bBinaryCopy = TRUE;
			} else
				bBinaryCopy = FALSE;
		} else {
			if ((err = m_DataDB2.DBStoreUniq(&dataBuf1)) != 0)
				return err;

			bBinaryCopy = TRUE;
		}

		if ((err = m_DataDB2.DBGetBinarySize(PICT1_FIELD, &size)) != 0)
				return err;

		if (size == 0) {
			if ((err = CacheBinaryData(PICT1_FIELD)) != 0)
				return err;
		}

		if ((err = m_DataDB2.DBGetBinarySize(PICT2_FIELD, &size)) != 0)
				return err;

		if (size == 0) {
			if ((err = CacheBinaryData(PICT2_FIELD)) != 0)
				return err;
		}

		if ((err = m_DataDB2.DBGetBinarySize(TEXT_FIELD, &size)) != 0)
				return err;

		if (size == 0) {
			if ((err = CacheBinaryData(TEXT_FIELD)) != 0)
				return err;
		}

		if ((err = m_DataDB.DBSkip(1)) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::ClearCache()
{
	int err;

	switch (m_nCacheMode) {
	case CACHEMODE_CACHE:
		if ((err = m_FolderDB2.DBZip()) != 0)
			return err;

		if ((err = m_DataDB2.DBZip()) != 0)
			return err;
		break;
	case CACHEMODE_OFFLINE:
		if ((err = m_FolderDB.DBZip()) != 0)
			return err;

		if ((err = m_DataDB.DBZip()) != 0)
			return err;
		break;
	default:
		return ERROR_PROGRAM;
	}

	return 0;
}

int CMMPcsDB::FilterFolder(LPCTSTR pFilter)
{
	int err;

	if ((err = SetFolderIndex(IDX_FOLDER_FID)) != 0)
		return err;

	if ((err = m_FolderDB.DBSetFilter(pFilter)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::GetFolderCount(long *pRecNum)
{
	int err;

	if ((err = m_FolderDB.DBLRecCount(pRecNum)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::ReadFolderAll(FOLDERREC *pFolderRec, BOOL *pRead)
{
	FOLDERBUF folderBuf;
	int err;

	if ((err = m_FolderDB.DBReadAll(&folderBuf, pRead)) != 0)
		return err;

	m_FolderDB.DBGetFieldBuf(&folderBuf, pFolderRec);

	return 0;
}

int CMMPcsDB::FilterData(LPCTSTR pFilter)
{
	int err;

	if ((err = SetDataIndex(IDX_DATA_DID)) != 0)
		return err;

	if ((err = m_DataDB.DBSetFilter(pFilter)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::GetDataCount(long *pRecNum)
{
	int err;

	if ((err = m_DataDB.DBLRecCount(pRecNum)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::ReadDataAll(DATAREC *pDataRec, BOOL *pRead)
{
	DATABUF dataBuf;
	int err;

	if ((err = m_DataDB.DBReadAll(&dataBuf, pRead)) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE)
		m_DataDB2.DBStoreUniq(&dataBuf);

	m_DataDB.DBGetFieldBuf(&dataBuf, pDataRec);

	return 0;
}

int CMMPcsDB::PackFile()
{
	int err;

	if ((err = m_FolderDB.DBPack()) != 0)
		return err;

	if ((err = m_DataDB.DBPack()) != 0)
		return err;

	if (m_nCacheMode == CACHEMODE_CACHE) {
		if ((err = m_FolderDB2.DBPack()) != 0)
			return err;

		if ((err = m_DataDB2.DBPack()) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::SelectFolder(LPCTSTR fid, long *nRec)
{
	int err;

	if (fid != NULL) {
		char fidbuf[LEN_ID + 1];
		CString select;

		memcpy(fidbuf, fid, LEN_ID);
		fidbuf[LEN_ID] = '\0';
		select.Format("#%d=%8s", IDX_FOLDER_PFID, fidbuf);
		if ((err = m_FolderDB.DBSelect(select, nRec)) != 0)
			return err;
	} else {
		if ((err = m_FolderDB.DBSelect(NULL, nRec)) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::SelectData(LPCTSTR fid, long *nRec)
{
	int err;

	if (fid != NULL) {
		char fidbuf[LEN_ID + 1];
		CString select;

		memcpy(fidbuf, fid, LEN_ID);
		fidbuf[LEN_ID] = '\0';
		select.Format("#%d=%8s", IDX_DATA_FID, fidbuf);
		if ((err = m_DataDB.DBSelect(select, nRec)) != 0)
			return err;
	} else {
		if ((err = m_DataDB.DBSelect(NULL, nRec)) != 0)
			return err;
	}

	return 0;
}

int CMMPcsDB::Backup(LPCTSTR pBackupDir, CProgressCtrl *pProgress)
{
	CMMPcserve pcserveLocal;
	CPSDB folderDB, dataDB;
	CString dir;
	int err;

	CreateDirectoryAll(pBackupDir);

	pcserveLocal.Open(CHT_LOCAL, NULL);

	dir = pBackupDir;
	if (dir.Right(1) != '\\')
		dir += "\\";

	if ((err = CreateDB(&pcserveLocal, folderDB, dataDB, dir, FALSE)) != 0)
		return err;

	if ((err = CopyDB(&m_FolderDB, &m_DataDB, &folderDB, &dataDB, pProgress)) != 0)
		return err;

	return 0;
}

int CMMPcsDB::Restore(LPCTSTR pBackupDir, CProgressCtrl *pProgress)
{
	CMMPcserve pcserveLocal;
	CPSDB folderDB, dataDB;
	CString dir;
	int err;

	CreateDirectoryAll(pBackupDir);

	pcserveLocal.Open(CHT_LOCAL, NULL);

	dir = pBackupDir;
	if (dir.Right(1) != '\\')
		dir += "\\";

	if ((err = OpenDB(&pcserveLocal, folderDB, dataDB, dir, FALSE)) != 0)
		return err;

	if ((err = CopyDB(&folderDB, &dataDB, &m_FolderDB, &m_DataDB, pProgress)) != 0)
		return err;

	return 0;
}

void CMMPcsDB::FlushFolder()
{
	if (m_pPcserve == NULL)
		return;

	if (m_pPcserve->m_nChType != CHT_LOCAL)
		return;

	m_FolderDB.DBFlush();
}

void CMMPcsDB::FlushData()
{
	if (m_pPcserve == NULL)
		return;

	if (m_pPcserve->m_nChType != CHT_LOCAL)
		return;

	m_DataDB.DBFlush();
}

BOOL CMMPcsDB::CheckPassword(CString sPassword)
{
	return (m_sPassword == sPassword);
}

void CMMPcsDB::SetPassword(CString sPassword)
{
	m_sPassword = sPassword;
	m_LibraryRec.codeDef = sPassword;
	UpdateFolder(&m_LibraryRec);
}

BOOL CMMPcsDB::CheckPermission(DWORD dwPermission)
{
	return (m_dwPermission & dwPermission) ? TRUE : FALSE;
}

void CMMPcsDB::SetPermission(DWORD dwPermission)
{
	m_dwPermission = dwPermission;
	m_LibraryRec.codeStep = dwPermission;
	UpdateFolder(&m_LibraryRec);
}


static int CopyDB(CPSDB *pSrcFolderDB, CPSDB *pSrcDataDB, CPSDB *pDstFolderDB, CPSDB *pDstDataDB, CProgressCtrl *pProgress)
{
	long nFolderCount, nDataCount;
	int err;

	if ((err = pSrcFolderDB->DBRecCount(&nFolderCount)) != 0)
		return err;

	if ((err = pSrcDataDB->DBRecCount(&nDataCount)) != 0)
		return err;

	pProgress->SetRange32(0, nFolderCount + nDataCount);

	if ((err = CopyFolderDB(pSrcFolderDB, pDstFolderDB, pProgress, 0)) != 0)
		return err;

	if ((err = CopyDataDB(pSrcDataDB, pDstDataDB, pProgress, nFolderCount)) != 0)
		return err;

	return 0;
}

static int CopyFolderDB(CPSDB *pSrcFolderDB, CPSDB *pDstFolderDB, CProgressCtrl *pProgress, int nCount)
{
	FOLDERBUF folderBuf;
	BOOL bEof;
	int err;

	if ((err = pSrcFolderDB->DBTop()) != 0)
		return err;

	if ((err = pDstFolderDB->DBZip()) != 0)
		return err;

	for (;;) {
		if ((err = pSrcFolderDB->DBEof(&bEof)) != 0)
			return err;

		if (bEof)
			break;

		if ((err = pSrcFolderDB->DBRead(&folderBuf)) != 0)
			return err;

		if ((err = pDstFolderDB->DBStore(&folderBuf)) != 0)
			return err;

		if ((err = CopyBinaryDB(pSrcFolderDB, pDstFolderDB, FORM_FIELD)) != 0)
			return err;

		if ((err = pSrcFolderDB->DBSkip(1)) != 0)
			return err;

		pProgress->SetPos(++nCount);
	}

	return 0;
}

static int CopyDataDB(CPSDB *pSrcDataDB, CPSDB *pDstDataDB, CProgressCtrl *pProgress, int nCount)
{
	DATABUF dataBuf;
	BOOL bEof;
	int err;

	if ((err = pSrcDataDB->DBTop()) != 0)
		return err;

	if ((err = pDstDataDB->DBZip()) != 0)
		return err;

	for (;;) {
		if ((err = pSrcDataDB->DBEof(&bEof)) != 0)
			return err;

		if (bEof)
			break;

		if ((err = pSrcDataDB->DBRead(&dataBuf)) != 0)
			return err;

		if ((err = pDstDataDB->DBStore(&dataBuf)) != 0)
			return err;

		if ((err = CopyBinaryDB(pSrcDataDB, pDstDataDB, PICT1_FIELD)) != 0)
			return err;

		if ((err = CopyBinaryDB(pSrcDataDB, pDstDataDB, PICT2_FIELD)) != 0)
			return err;

		if ((err = CopyBinaryDB(pSrcDataDB, pDstDataDB, TEXT_FIELD)) != 0)
			return err;

		if ((err = CopyBinaryDB(pSrcDataDB, pDstDataDB, SOUND_FIELD)) != 0)
			return err;

		if ((err = pSrcDataDB->DBSkip(1)) != 0)
			return err;

		pProgress->SetPos(++nCount);
	}

	return 0;
}

static int CopyBinaryDB(CPSDB *pSrcDB, CPSDB *pDstDB, LPCTSTR pFieldName)
{
	long size;
	int err;

	if ((err = pSrcDB->DBGetBinarySize(pFieldName, &size)) != 0)
		return err;

	if (size == 0)
		return 0;

	char *pBuf = new char[size];
	long readNum;

	if ((err = pSrcDB->DBReadBinary(pFieldName, pBuf, size, &readNum)) != 0) {
		delete [] pBuf;
		return err;
	}

	if ((err = pDstDB->DBWriteBinary(pFieldName, pBuf, readNum)) != 0) {
		delete [] pBuf;
		return err;
	}

	delete [] pBuf;

	return 0;
}

static void PcserveError(int nErrorCode)
{
	CString msg;

	if (!g_bNoDispError) {
		switch (nErrorCode) {
		case ERROR_PROGRAM:
			msg = GetString(IDS_PROGRAM_ERROR);
			break;
		case ERROR_VERSION:
			return;
		case ERROR_ALREADY_OPEN:
			msg = GetString(IDS_LIBRARY_ALREADY_OPEN);
			break;
		case ERROR_PERMISSION:
			msg = GetString(IDS_ACCESS_DENY);
			break;
		case ERROR_PASSWORD:
			msg = GetString(IDS_PASSWORD_INCORRECT);
			break;
		default:
			msg.Format("Pcserve Error %d %s", nErrorCode, CPcserve::GetErrorText(nErrorCode));
			break;
		}

		AfxMessageBox(msg);
	} else
		g_bNoDispError = FALSE;

	g_nLastPcserveError = nErrorCode;
}
/*
static void VersionError(int nChType, LPCTSTR pServer, LPCTSTR pDirectory)
{
	if (AfxMessageBox("データベースのバージョンが違います。変換しますか？", MB_OKCANCEL) == IDOK) {
		CString command;
		command.Format("Convert /C=%d /S=%s /D=%s", nChType, pServer, pDirectory);
		WinExec(command, SW_SHOW);
	}
}
*/