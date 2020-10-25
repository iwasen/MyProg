// MMLibDB.h: CMMLibDB クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#pragma once

#define ERROR_PROGRAM		-1
#define ERROR_VERSION		-2
#define ERROR_ALREADY_OPEN	-3
#define ERROR_PERMISSION	-4
#define ERROR_PASSWORD		-5

class CMMPcserve : public CObject  
{
public:
	CMMPcserve();
	virtual ~CMMPcserve();

public:
	CObList m_PcsDBList;
	CPcserve m_Pcserve;
	int m_nChType;
	CString m_ServerName;

	int Open(int nChType, LPCTSTR pServerName);
};


class CMMPcsDB : public CObject  
{
public:
	CMMPcsDB();
	virtual ~CMMPcsDB();

public:
	CMMPcserve *m_pPcserve;
	CMMPcserve *m_pCachePcserve;
	CPSDB m_FolderDB;
	CPSDB m_DataDB;
	CPSDB m_FolderDB2;
	CPSDB m_DataDB2;
	DWORD m_ItemData;
	CString m_DirName;
	int m_nCacheMode;
	CString m_ServerName;
	int m_nChType;
	CString m_Title;
	CString m_LibraryID;
	CString m_sPassword;
	DWORD m_dwPermission;

	BOOL OpenEx(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, LPCTSTR pLibraryID);
	BOOL CreateEx(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, FOLDERREC *pLibraryRec);
	void CloseEx();
	BOOL ReadLibraryEx(FOLDERREC *pLibraryRec);
	BOOL ReadFolderEx(LPCTSTR fid, FOLDERREC *pFolderRec);
	BOOL ReadChildFolderEx(LPCTSTR fid, FOLDERREC *pFolderRec, BOOL bFirst);
	BOOL AddFolderEx(FOLDERREC *pFolderRec);
	BOOL UpdateFolderEx(FOLDERREC *pFolderRec);
	BOOL DeleteFolderEx(LPCTSTR fid);
	BOOL ReadTextFolderEx(LPCTSTR fid, CString *pText);
	BOOL WriteTextFolderEx(LPCTSTR fid, CString *pText);
	BOOL MoveFolderEx(LPCTSTR fidSrc, LPCTSTR fidDst, FOLDERREC *pFolderRec);
	static BOOL CopyFolderEx(CMMPcsDB *pPcsDBSrc, LPCTSTR fidSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, FOLDERREC *pFolderRec);
	BOOL RotateFolderEx(LPCTSTR fid, int nRotate);
	BOOL ReadDataEx(LPCTSTR did, DATAREC *pDataRec);
	BOOL RotateDataEx(LPCTSTR did, int nRotate);
	BOOL ReadChildDataEx(LPCTSTR fid, DATAREC *pDataRec, BOOL bFirst);
	BOOL ReadBinaryDataEx(LPCTSTR did, LPCTSTR pFieldName, LPSTR *pPict, long *pPictSize);
	BOOL ReadPictureDataEx(LPCTSTR did, PHDIB phDIB1, PHDIB phDIB2, DATAREC *pDataRec = NULL);
	BOOL WritePictureDataEx(LPCTSTR did, HDIB hDIB1, HDIB hDIB2, short mmType);
	BOOL ReadTextDataEx(LPCTSTR did, CString *pText);
	BOOL WriteTextDataEx(LPCTSTR did, CString *pText);
	BOOL AddDataEx(DATAREC *pDataRec);
	BOOL UpdateDataEx(DATAREC *pDataRec);
	BOOL DeleteDataEx(LPCTSTR did);
	BOOL MoveDataEx(LPCTSTR didSrc, LPCTSTR fidDst, DATAREC *pDataRec);
	static BOOL CopyDataEx(CMMPcsDB *pPcsDBSrc, LPCTSTR didSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, DATAREC *pDataRec);
	BOOL FillCacheEx(LPCTSTR fid);
	BOOL ClearCacheEx();
	BOOL FilterFolderEx(LPCTSTR pFilter);
	BOOL GetFolderCountEx(long *pRecNum);
	BOOL ReadFolderAllEx(FOLDERREC *pFolderRec, BOOL *pRead);
	BOOL FilterDataEx(LPCTSTR pFilter);
	BOOL GetDataCountEx(long *pRecNum);
	BOOL ReadDataAllEx(DATAREC *pDataRec, BOOL *pRead);
	BOOL PackFileEx();
	BOOL BackupEx(LPCTSTR pBackupDir, CProgressCtrl *pProgress);
	BOOL RestoreEx(LPCTSTR pBackupDir, CProgressCtrl *pProgress);
	BOOL CheckPassword(CString sPassword);
	void SetPassword(CString sPassword);
	BOOL CheckPermission(DWORD dwPermission);
	void SetPermission(DWORD dwPermission);
	int SetFolderIndex(int nIndexNo, int nIndexNo2 = -1);
	int SetDataIndex(int nIndexNo, int nIndexNo2 = -1);

private:
	int m_nCurrentFolderIndex;
	int m_nCurrentFolderIndex2;
	int m_nCurrentDataIndex;
	int m_nCurrentDataIndex2;
	FOLDERREC m_LibraryRec;

	int ConvertVersion(CMMPcserve *pPcserve, LPCTSTR pDirName);
	int ConvertVersionSub(CMMPcserve *pPcserve, LPCTSTR pDirName);
	int Open(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, LPCTSTR pLibraryID);
	int Create(CMMPcserve *pPcserve, CMMPcserve *pCachePcserve, LPCTSTR pDirName, int nOpenMode, FOLDERREC *pLibraryRec);
	int ReadFolder(LPCTSTR fid, FOLDERREC *pFolderRec);
	int ReadChildFolder(LPCTSTR fid, FOLDERREC *pFolderRec, BOOL bFirst, BOOL *pRead);
	int AddFolder(FOLDERREC *pFolderRec);
	int UpdateFolder(FOLDERREC *pFolderRec);
	int DeleteFolder(LPCTSTR fid);
	int ReadTextFolder(LPCTSTR fid, CString *pText);
	int WriteTextFolder(LPCTSTR fid, CString *pText);
	int CacheBinFldFolder(LPCTSTR fid, LPCTSTR pFieldName);
	int MoveFolder(LPCTSTR fidSrc, LPCTSTR fidDst, FOLDERREC *pFolderRec = NULL);
	static int CopyFolder(CMMPcsDB *pPcsDBSrc, LPCTSTR fidSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, FOLDERREC *pFolderRec = NULL);
	int RotateFolder(LPCTSTR fid, int nRotate);
	int RotateData(LPCTSTR did, int nRotate);
	int ReadData(LPCTSTR did, DATAREC *pDataRec);
	int ReadChildData(LPCTSTR fid, DATAREC *pDataRec, BOOL bFirst, BOOL *pRead);
	int ReadBinaryData(LPCTSTR did, LPCTSTR pFieldName, LPSTR *pPict, long *pPictSize);
	int ReadPictureData(LPCTSTR did, PHDIB phDIB1, PHDIB phDIB2, DATAREC *pDataRec);
	int WritePictureData(LPCTSTR did, HDIB hDIB1, HDIB hDIB2, short mmType);
	int ReadTextData(LPCTSTR did, CString *pText);
	int WriteTextData(LPCTSTR did, CString *pText);
	void WriteCacheBinary(LPCTSTR id, CPSDB &DB, CPSDB &DB2, LPCTSTR pFieldName);
	int AddData(DATAREC *pDataRec);
	int UpdateData(DATAREC *pDataRec);
	int DeleteData(LPCTSTR did);
	int MoveData(LPCTSTR didSrc, LPCTSTR fidDst, DATAREC *pDataRec);
	static int CopyData(CMMPcsDB *pPcsDBSrc, LPCTSTR didSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst, DATAREC *pDataRec = NULL);
	static int CopyChildData(CMMPcsDB *pPcsDBSrc, LPCTSTR fidSrc, CMMPcsDB *pPcsDBDst, LPCTSTR fidDst);
	static int OpenDB(CMMPcserve *pPcserve, CPSDB &folderDB, CPSDB &dataDB, LPCTSTR pDirName, BOOL bIndex = TRUE);
	static int CreateDB(CMMPcserve *pPcserve, CPSDB &folderDB, CPSDB &dataDB, LPCTSTR pDirName, BOOL bIndex = TRUE);
	int CacheFolder(LPCTSTR fid, LPBOOL pCachedChildren, LPTSTR pFolderType);
	int CacheChildFolder(LPCTSTR fid);
	int CacheChildData(LPCTSTR fid);
	int CacheBinaryFolder(LPCTSTR pFieldName);
	int CacheBinaryData(LPCTSTR pFieldName);
	int CacheBinary(LPCTSTR pFieldName, CPSDB &DB, CPSDB &DB2);
	int CacheBinFldData(LPCTSTR did, LPCTSTR pFieldName);
	int WritePictureCacheData(LPCTSTR did);
	int WriteTextCacheData(LPCTSTR did);
	int WriteTextCacheFolder(LPCTSTR fid);
	static int CopyBinary(CPSDB &srcDB, long recNoSrc, CPSDB &dstDB, long recNoDst, LPCTSTR fieldName);
	void MakeLibraryID(CString &id);
	int MakeFolderID(CString &id);
	int MakeDataID(CString &id);
	void SetCurrentTime(CString &time);
	int UpdateFolderUpdateCount(LPCTSTR fid);
	int FillCache(LPCTSTR fid);
	int FillDataCache(LPCTSTR fid);
	int ClearCache();
	int FilterFolder(LPCTSTR pFilter);
	int GetFolderCount(long *pRecNum);
	int ReadFolderAll(FOLDERREC *pFolderRec, BOOL *pRead);
	int FilterData(LPCTSTR pFilter);
	int GetDataCount(long *pRecNum);
	int ReadDataAll(DATAREC *pDataRec, BOOL *pRead);
	int PackFile();
	int SelectFolder(LPCTSTR fid, long *nRec);
	int SelectData(LPCTSTR fid, long *nRec);
	int Backup(LPCTSTR pBackupDir, CProgressCtrl *pProgress);
	int Restore(LPCTSTR pBackupDir, CProgressCtrl *pProgress);
	void FlushFolder();
	void FlushData();
};

class CMMLibDB  
{
public:
	CMMLibDB();
	virtual ~CMMLibDB();

public:
	CString m_CacheDir;

	CMMPcsDB *OpenMMLibrary(int nChType, LPCTSTR pServerName, LPCTSTR pDirName, int nOpenMode, LPCTSTR pLibraryID = NULL);
	CMMPcsDB *CreateMMLibrary(int nChType, LPCTSTR pServerName, LPCTSTR pDirName, int nOpenMode, FOLDERREC *pLibraryRec);
	void CloseMMLibrary(CMMPcsDB *pPcsDB);
	BOOL DeleteMMLibrary(CMMPcsDB *pPcsDB);
	void ExitMMLibrary();
	BOOL GetLibraryName(LPCTSTR pDirName, CString &libraryName);

protected:
	CObList m_PcserveList;
	CMMPcserve m_PcserveLocal;

	CMMPcserve *ConxxxtPcserve(int nChType, LPCTSTR pServerName);
	int DeleteAllFiles(CMMPcserve *pPcserve, LPCTSTR pDirName);
	BOOL VersionCheck(CMMPcserve *pPcserve, LPCTSTR pDirName);
};

extern CMMLibDB *g_pMMLibDB;
extern int g_nLastPcserveError;
extern BOOL g_bNoDispError;
