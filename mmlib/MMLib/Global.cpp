#include "stdafx.h"
#include "MMLib.h"
#include "Mml.h"
#include "PictFile.h"
#include <mapi.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <shlwapi.h>

CImageList g_ImageList;
CImageList g_ImageList2;
CTreeCtrl *g_pTreeCtrl;
CString g_StatusInfo;
char g_SectionSettings[] = "Settings";
char g_SectionMainWindow[] = "MainWindow";
char g_SectionLibrary[] = "Library";
char g_SectionDetails[] = "Details";
char g_SectionOptions[] = "Options";
char g_SectionRecent[] = "Recent";
char g_SectionTransfer[] = "Transfer";
char g_SectionFindWindow[] = "FindWindow";
char g_SectionMailAtt[] = "MailAtt";
char g_sProgName[] = "Multimedia Library Manager";
char g_RegistryName[] = "Multimedia Library";
HICON g_hIcon;

int g_tColumnOrder[] = {
	DETAIL_NAME,
	DETAIL_FIRSTDATA,
	DETAIL_CODE,
	DETAIL_CDATE,
	DETAIL_UDATE,
	DETAIL_CREATOR,
	DETAIL_REMARK,
	DETAIL_KEYWORD,
	DETAIL_SERVER,
	DETAIL_DIRECTORY
};

UINT g_nCFMMLibrary;
int g_nDetailMode;
BOOL g_bParamOffline;
CFont g_DefaultFont;
int g_nFontSize;
CString g_ViewFontName;
int g_nViewFontSize;
CString g_TempFile;
CItemInbox *g_pItemInbox;
CString g_sImageEditor;

static void MakeFilterSub(CString &str, LPCTSTR pOption, LPCTSTR pFieldName, LPCTSTR pOperation, LPCTSTR pFind, BOOL bAnd = TRUE);
static CString MakeDateBefore(CString &days);
static void GetMmlibDir(CString &sDir, LPCTSTR pSubDir);

void FormatDate(CString &strIn, CString &strOut)
{
	LPCTSTR pDate = strIn;

	strOut.Format("%.4s/%.2s/%.2s %.2s:%.2s",
			pDate, pDate + 4, pDate + 6,
			pDate + 8, pDate + 10);
}

HDIB MakeSmallPicture(HDIB hDIB1, int width, int height)
{
	HDIB hDIB2;
	short width1, height1, color;

	if (MMPictureGetInfo(hDIB1, &width1, &height1, &color) != 0)
		return NULL;

	if (height1 * width / width1 < height)
		height = height1 * width / width1;
	else
		width = width1 * height / height1;

	if (MMPictureCopy(hDIB1, &hDIB2, width, height) != 0)
		return NULL;

	return hDIB2;
}

BOOL CreateDirectoryAll(LPCTSTR pDirPath)
{
	DWORD err;
	char *p;
	char buf[_MAX_PATH];

	if (!CreateDirectory(pDirPath, NULL)) {
		err = GetLastError();
		if (err == ERROR_ALREADY_EXISTS)
			return TRUE;
		else if (err == ERROR_PATH_NOT_FOUND) {
			strcpy(buf, pDirPath);
			if ((p = strrchr(buf, '\\')) != NULL) {
				*p = '\0';
				if (CreateDirectoryAll(buf))
					return CreateDirectory(pDirPath, NULL);
				else
					return FALSE;
			} else
				return FALSE;
		} else
			return FALSE;
	}

	return TRUE;
}

HANDLE CopyHandle(HANDLE h)
{
	BYTE *lpCopy;
	BYTE *lp;
	HANDLE     hCopy;
	SIZE_T      dwLen;

	if (h == NULL)
		return NULL;

	dwLen = ::GlobalSize((HGLOBAL) h);

	if ((hCopy = (HANDLE) ::GlobalAlloc (GHND, dwLen)) != NULL)
	{
		lpCopy = (BYTE *) ::GlobalLock((HGLOBAL) hCopy);
		lp     = (BYTE *) ::GlobalLock((HGLOBAL) h);

		while (dwLen--)
			*lpCopy++ = *lp++;

		::GlobalUnlock((HGLOBAL) hCopy);
		::GlobalUnlock((HGLOBAL) h);
	}

	return hCopy;
}

void PrintPicture(CDC *pDC, HDIB hDIB)
{
	short nSrcWidth, nSrcHeight, nColor;
	int nDstWidth, nDstHeight;

	if (hDIB == NULL)
		return;

	MMPictureGetInfo(hDIB, &nSrcWidth, &nSrcHeight, &nColor);

	int nDevWidth = pDC->GetDeviceCaps(HORZRES);
	int nDevHeight = pDC->GetDeviceCaps(VERTRES);

	nDstWidth = nDevWidth;
	nDstHeight = nSrcHeight * nDevWidth / nSrcWidth;
	if (nDstHeight > nDevHeight) {
		nDstWidth = nSrcWidth * nDevHeight / nSrcHeight;
		nDstHeight = nDevHeight;
	}

	MMPictureDraw(hDIB, pDC->m_hDC, 0, 0, nDstWidth, nDstHeight, 0, 0, nSrcWidth, nSrcHeight);
}

BOOL SendDocuments(LPCTSTR pFileName)
{
	HINSTANCE hModule;
	FARPROC  pProc;

	if ((hModule = LoadLibrary("MAPI32.DLL")) == NULL)
		return FALSE;

	if ((pProc = GetProcAddress(hModule, "MAPISendDocuments")) != NULL)
		(*(LPMAPISENDDOCUMENTS)pProc)((ULONG_PTR)AfxGetMainWnd()->m_hWnd, ";", (LPSTR)pFileName, NULL, 0);

	FreeLibrary(hModule);

	return TRUE;
}

CString MakeFilterString(FIND_CONDITION *pFindConditions, int nFindType)
{
	CString filter;
	CString str;
	CString option;
	int i, j;

	for (i = 0; i < N_FIND_CONDITIONS; i++) {
		option.Empty();
		for (j = 0; j < pFindConditions[i].matching.GetLength(); j++) {
			option += "*";
			option += pFindConditions[i].matching[j];
		}

		str.Empty();
		MakeFilterSub(str, NULL, "CDATE", ">=", pFindConditions[i].createDateFrom);
		MakeFilterSub(str, NULL, "CDATE", "<=", pFindConditions[i].createDateTo);
		MakeFilterSub(str, NULL, "CDATE", ">=", MakeDateBefore(pFindConditions[i].createDateBefore));
		MakeFilterSub(str, NULL, "UDATE", ">=", pFindConditions[i].updateDateFrom);
		MakeFilterSub(str, NULL, "UDATE", "<=", pFindConditions[i].updateDateTo);
		MakeFilterSub(str, NULL, "UDATE", ">=", MakeDateBefore(pFindConditions[i].updateDateBefore));
		MakeFilterSub(str, option, "CREATOR", "=", pFindConditions[i].creator);
		if (nFindType == FIND_TYPE_FOLDER) {
			MakeFilterSub(str, option, "FNAME", "=", pFindConditions[i].title);
		} else {
			MakeFilterSub(str, option, "CODE", "=", pFindConditions[i].code);
			MakeFilterSub(str, option, "TITLE", "=", pFindConditions[i].title);
		}
		MakeFilterSub(str, option, "REMARK1", "=", pFindConditions[i].remark);
		MakeFilterSub(str, option, "REMARK2", "=", pFindConditions[i].keyword);
		MakeFilterSub(str, option, nFindType == FIND_TYPE_FOLDER ? FORM_FIELD : TEXT_FIELD, "=", pFindConditions[i].text);

		if (!str.IsEmpty()) {
			if (!filter.IsEmpty())
				filter += "|";
			filter += "(" + str + ")";
		}
	}

	return filter;
}

CString MakeFilterString2(LPCTSTR pKeyword)
{
	CString filter;
	CString str;
	CString option;
	char *token;
	char buf[256];

	option = "*S*N*Z";

	strcpy(buf, pKeyword);
	token = strtok(buf, " ");
	while(token != NULL) {
		str.Empty();
		MakeFilterSub(str, option, "TITLE", "=", token, FALSE);
		MakeFilterSub(str, option, "CREATOR", "=", token, FALSE);
		MakeFilterSub(str, option, "CODE", "=", token, FALSE);
		MakeFilterSub(str, option, "REMARK1", "=", token, FALSE);
		MakeFilterSub(str, option, "REMARK2", "=", token, FALSE);
		MakeFilterSub(str, option, TEXT_FIELD, "=", token, FALSE);

		if (!str.IsEmpty()) {
			if (!filter.IsEmpty())
				filter += "&";
			filter += "(" + str + ")";
		}

		token = strtok(NULL, " ");
	}

	return filter;
}

static void MakeFilterSub(CString &str, LPCTSTR pOption, LPCTSTR pFieldName, LPCTSTR pOperation, LPCTSTR pFind, BOOL bAnd)
{
	CString temp;

	if (*pFind != '\0') {
		if (!str.IsEmpty())
			str += bAnd ? "&" : "|";
		if (pOption == NULL)
			pOption = "";
		temp.Format("%s%s%s'%s'", pOption, pFieldName, pOperation, pFind);
		str += temp;
	}
}

static CString MakeDateBefore(CString &days)
{
	CString str;

	if (!days.IsEmpty()) {
		SYSTEMTIME st;
		GetLocalTime(&st);
		CTime time(st);
		CTimeSpan span(atoi(days), 0, 0, 0);
		time -= span;
		str.Format("%04d%02d%02d", time.GetYear(), time.GetMonth(), time.GetDay());
	}

	return str;
}

void MakeID(char prefix, DWORD nNumber, CString &id)
{
	int i, n;
	char buf[8];

	for (i = 6; i >= 0; i--) {
		n = (nNumber & 0x1f);
		if (n < 10)
			buf[i] = n + 0x30;
		else
			buf[i] = n - 10 + 0x41;

		nNumber >>= 5;
	}

	buf[7] = '\0';

	id.Format("%c%s", prefix, buf);
}

int GetPictureBitCount(HDIB hDIB)
{
	LPBITMAPINFOHEADER lpBmi;
	int nBitCount;

	if ((lpBmi = (LPBITMAPINFOHEADER)::GlobalLock(hDIB)) == NULL) {
		::GlobalFree(hDIB);
		return 0;
	}

	nBitCount = lpBmi->biBitCount;

	::GlobalUnlock(hDIB);

	return nBitCount;
}

short GetMMType(HDIB hDIB)
{
	short mmType;
	LPBITMAPINFO pBmi;

	if (hDIB == NULL)
		return 0;

	if ((pBmi = (LPBITMAPINFO)::GlobalLock(hDIB)) == NULL)
		return 0;

	switch (pBmi->bmiHeader.biBitCount) {
	case 1:
//		mmType = MMTYPE_FAX;
//		break;
	case 8:
		mmType = MMTYPE_PNG;
		break;
	default:
		mmType = MMTYPE_JPG;
		break;
	}

	::GlobalUnlock(hDIB);

	return mmType;
}

BOOL MakeTempFile(CItem *pItem, CString &fileName, int nSupportFile, BOOL bReadIniFile, BOOL bDeleteTemp) 
{
	CWaitCursor wait;
	CItemFolder *pItemFolder;
	CItemData *pItemData;
	CItemFind *pItemFind;
	char tempPath[MAX_PATH];
	CString title;
	CString tempFile;

	DeleteTempFile(g_TempFile);
	g_TempFile.Empty();

	::GetTempPath(sizeof(tempPath), tempPath);
	size_t len = strlen(tempPath);
	if (tempPath[len - 1] != '\\')
		strcat(tempPath, "\\");

	for ( ; pItem != NULL; pItem = pItem->m_pNextSelected) {
		switch (pItem->m_nItemType) {
		case ITEM_FOLDER:
			pItemFolder = (CItemFolder *)pItem;
			if (pItemFolder->m_nFolderType != FTYPE_DFOLDER)
				goto error;
			title = pItemFolder->m_FolderRec.folderName;
			CheckFileName(title);
			tempFile.Format("%s%s.mml", tempPath, (LPCTSTR)title);
			if (!MakeMmlFile(pItemFolder, tempFile, bReadIniFile))
				continue;
			fileName += tempFile + ";";
			break;
		case ITEM_DATA:
			pItemData = (CItemData *)pItem;
			title = pItemData->m_DataRec.title;
			CheckFileName(title);
			tempFile.Format("%s%s", tempPath, (LPCTSTR)title);
			if (!MakePictFile(pItemData, tempFile, nSupportFile, bReadIniFile))
				continue;
			fileName += tempFile + ";";
			break;
		case ITEM_FIND:
			pItemFind = (CItemFind *)pItem;
			if (pItemFind->m_nFindType == FIND_TYPE_FOLDER)
				goto error;
			title = pItemFind->m_FindRec.folderName;
			CheckFileName(title);
			tempFile.Format("%s%s.mml", tempPath, (LPCTSTR)title);
			if (!MakeMmlFile(pItemFind, tempFile, bReadIniFile))
				continue;
			fileName += tempFile + ";";
			break;
		default:
		error:
			return FALSE;
		}
	}

	if (bDeleteTemp)
		g_TempFile = fileName;

	return TRUE;
}

BOOL MakeMmlFile(CItem *pItem, LPCTSTR pFileName, BOOL bReadIniFile)
{
	CMMPcsDB *pPcsDB;
	FOLDERREC *pFolderRec;
	FINDREC *pFindRec;
	HTREEITEM hItem;
	CItemFolder *pItemFolder;
	CItemFind *pItemFind;
	CItemData *pItemData;
	DATAREC *pDataRec;
	LPCTSTR pPict;
	long nSize;
	CString text;
	CMmlWrite mml;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		if (((CItemFolder *)pItem)->m_nFolderType != FTYPE_DFOLDER)
			return FALSE;
		break;
	case ITEM_FIND:
		if (((CItemFind *)pItem)->m_nFindType == FIND_TYPE_FOLDER)
			return FALSE;
		break;
	default:
		return FALSE;
	}

	if (!mml.Create(pFileName))
		return FALSE;

	switch (pItem->m_nItemType) {
	case ITEM_FOLDER:
		pItemFolder = (CItemFolder *)pItem;
		pFolderRec = &pItemFolder->m_FolderRec;
		pPcsDB = pItemFolder->m_pPcsDB;
		mml.WriteItem("FNAME", (LPCTSTR)pFolderRec->folderName, pFolderRec->folderName.GetLength());
		mml.WriteItem("CDATE", (LPCTSTR)pFolderRec->createDate, pFolderRec->createDate.GetLength());
		mml.WriteItem("UDATE", (LPCTSTR)pFolderRec->updateDate, pFolderRec->updateDate.GetLength());
		mml.WriteItem("CREATOR", (LPCTSTR)pFolderRec->creator, pFolderRec->creator.GetLength());
		mml.WriteItem("REMARK1", (LPCTSTR)pFolderRec->remark, pFolderRec->remark.GetLength());
		mml.WriteItem("REMARK2", (LPCTSTR)pFolderRec->keyword, pFolderRec->keyword.GetLength());
		if (!pPcsDB->ReadTextFolderEx(pItemFolder->m_FolderRec.folderID, &text))
			return FALSE;
		if (text.GetLength() != 0)
			mml.WriteItem("TEXT", (LPCTSTR)text, text.GetLength());
		break;
	case ITEM_FIND:
		pItemFind = (CItemFind *)pItem;
		pFindRec = &pItemFind->m_FindRec;
		pPcsDB = pItemFind->m_pPcsDB;
		mml.WriteItem("FNAME", (LPCTSTR)pFindRec->folderName, pFindRec->folderName.GetLength());
		break;
	}

	CPictFile *pPictFile = new CPictFile;
	if (bReadIniFile)
		pPictFile->ReadIniFile();

	for (hItem = g_pTreeCtrl->GetChildItem(pItem->m_hItem); hItem != NULL;
				hItem = g_pTreeCtrl->GetNextSiblingItem(hItem)) {
		pItem = (CItem *)g_pTreeCtrl->GetItemData(hItem);
		if (pItem->m_nItemType != ITEM_DATA)
			continue;

		pItemData = (CItemData *)pItem;
		if ((pPict = pPictFile->ReadPict(pItemData, PICTFILE_ALL, &nSize)) == NULL)
			continue;

		mml.NextPage();

		pDataRec = &pItemData->m_DataRec;
		mml.WriteItem("CDATE", (LPCTSTR)pDataRec->createDate, pDataRec->createDate.GetLength());
		mml.WriteItem("UDATE", (LPCTSTR)pDataRec->updateDate, pDataRec->updateDate.GetLength());
		mml.WriteItem("CREATOR", (LPCTSTR)pDataRec->creator, pDataRec->creator.GetLength());
		mml.WriteItem("CODE", (LPCTSTR)pDataRec->code, pDataRec->code.GetLength());
		mml.WriteItem("TITLE", (LPCTSTR)pDataRec->title, pDataRec->title.GetLength());
		mml.WriteItem("REMARK1", (LPCTSTR)pDataRec->remark, pDataRec->remark.GetLength());
		mml.WriteItem("REMARK2", (LPCTSTR)pDataRec->keyword, pDataRec->keyword.GetLength());

		if (!pPcsDB->ReadTextDataEx(pItemData->m_DataRec.dataID, &text))
			return FALSE;
		if (text.GetLength() != 0)
			mml.WriteItem("TEXT", (LPCTSTR)text, text.GetLength());

		mml.WriteItem(PICT1_FIELD, pPict, nSize);
	}

	delete pPictFile;

	return TRUE;
}

BOOL MakePictFile(CItemData *pItemData, CString &tempFile, int nSupportFile, BOOL bReadIniFile)
{
	LPCTSTR pPict;
	long nSize;
	CString ext;

	nSupportFile &= PICTFILE_DEFAULT;

	CPictFile *pPictFile = new CPictFile;
	if (bReadIniFile)
		pPictFile->ReadIniFile();

	if ((pPict = pPictFile->ReadPict(pItemData, nSupportFile, &nSize)) == NULL) {
		delete pPictFile;
		return FALSE;
	}

	switch (*(SHORT *)pPict) {
	case MMTYPE_GIF:
		ext = ".gif";
		break;
	case MMTYPE_JPG:
		ext = ".jpg";
		break;
	case MMTYPE_BMP:
		ext = ".bmp";
		break;
	case MMTYPE_PNG:
		ext = ".png";
		break;
	default:
		delete pPictFile;
		return FALSE;
	}

	if (ext.CompareNoCase(tempFile.Right(4)) != 0)
		tempFile += ext;

	CFile file;
	if (!file.Open(tempFile, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone)) {
		delete pPictFile;
		return FALSE;
	}
	file.Write(pPict + sizeof(SHORT), nSize - sizeof(SHORT));
	file.Close();
	::SetFileAttributes(tempFile, FILE_ATTRIBUTE_NORMAL);

	delete pPictFile;

	return TRUE;
}

void DeleteTempFile(CString &fileName)
{
	CString tempFile;

	for (;;) {
		tempFile = fileName.SpanExcluding(";");
		if (tempFile.IsEmpty())
			break;
		::DeleteFile(tempFile);
		fileName.Delete(0, tempFile.GetLength());
		if (!fileName.IsEmpty() && fileName.GetAt(0) == ';')
			fileName.Delete(0, 1);
	}
}

void CheckFileName(CString &fileName)
{
	int n;

	while ((n = fileName.FindOneOf(":<>\\/*?| ")) != -1)
		fileName.SetAt(n, '_');
}

int CheckFileType(LPCTSTR pFileName)
{
	LPCTSTR p;

	if ((p = strrchr(pFileName, '.')) == NULL)
		return 0;

	if (_stricmp(p, ".mml") == 0)
		return SRCFILETYPE_MML;

	if (_stricmp(p, ".bmp") == 0 || _stricmp(p, ".gif") == 0 || _stricmp(p, ".jpg") == 0 || _stricmp(p, ".png") == 0)
		return SRCFILETYPE_PICT;

	return 0;
}

void GetLibraryDir(CString &dir)
{
	CWinApp *pWinApp = AfxGetApp();

	dir = pWinApp->GetProfileString(g_SectionOptions, "LibraryFolder");
	if (dir.IsEmpty()) {
		GetDefaultLibraryDir(dir);
		pWinApp->WriteProfileString(g_SectionOptions, "LibraryFolder", dir);
	}
}

void GetDefaultLibraryDir(CString &dir)
{
	GetMmlibDir(dir, "LIBRARY");
}

void GetCacheDir(CString &dir)
{
	CWinApp *pWinApp = AfxGetApp();

	dir = pWinApp->GetProfileString(g_SectionOptions, "CacheFolder");
	if (dir.IsEmpty()) {
		GetDefaultCacheDir(dir);
		pWinApp->WriteProfileString(g_SectionOptions, "CacheFolder", dir);
	}
}

void GetDefaultCacheDir(CString &dir)
{
	GetMmlibDir(dir, "CACHE");
}

void GetBackupDir(CString &dir)
{
	CWinApp *pWinApp = AfxGetApp();

	dir = pWinApp->GetProfileString(g_SectionOptions, "BackupFolder");
	if (dir.IsEmpty()) {
		GetDefaultBackupDir(dir);
		pWinApp->WriteProfileString(g_SectionOptions, "BackupFolder", dir);
	}
}

void GetDefaultBackupDir(CString &dir)
{
	GetMmlibDir(dir, "BACKUP");
}

void GetInboxDir(CString &dir)
{
	CWinApp *pWinApp = AfxGetApp();

	dir = pWinApp->GetProfileString(g_SectionOptions, "InboxFolder");
	if (dir.IsEmpty()) {
		GetDefaultInboxDir(dir);
		pWinApp->WriteProfileString(g_SectionOptions, "InboxFolder", dir);
	}
}

void GetDefaultInboxDir(CString &dir)
{
	GetMmlibDir(dir, "INBOX");
}

void GetKeywordDir(CString &dir)
{
	CWinApp *pWinApp = AfxGetApp();

	dir = pWinApp->GetProfileString(g_SectionOptions, "KeywordFolder");
	if (dir.IsEmpty()) {
		GetDefaultKeywordDir(dir);
		pWinApp->WriteProfileString(g_SectionOptions, "KeywordFolder", dir);
	}
}

void GetDefaultKeywordDir(CString &dir)
{
	GetMmlibDir(dir, "KEYWORD");
}

static void GetFolder(int nFolder, TCHAR *pPath)
{
	HRESULT	(CALLBACK* pfnSHGetFolderPath)(HWND, int, HANDLE, DWORD, LPTSTR) = NULL;
	HMODULE hDLL;

	hDLL = ::LoadLibrary("shell32.dll");
	if (hDLL != NULL)
		*(FARPROC *)&pfnSHGetFolderPath = ::GetProcAddress(hDLL, "SHGetFolderPathA");

	if (pfnSHGetFolderPath == NULL) {
		if (hDLL != NULL)
			::FreeLibrary(hDLL);

		hDLL = ::LoadLibrary("shfolder.dll");
		if (hDLL != NULL)
			*(FARPROC *)&pfnSHGetFolderPath = ::GetProcAddress(hDLL, "SHGetFolderPathA");
	}

	if (pfnSHGetFolderPath != NULL)
		pfnSHGetFolderPath(NULL, CSIDL_APPDATA, NULL, 0, pPath);
	else
		strcpy_s(pPath, MAX_PATH, "C:\\");

	if (hDLL != NULL)
		::FreeLibrary(hDLL);
}

void GetMmlibDir(CString &sDir, LPCTSTR pSubDir)
{
	LPTSTR pDir = sDir.GetBuffer(MAX_PATH);

	GetFolder(CSIDL_APPDATA, pDir);
	::PathAppend(pDir, "xxxx\\MMLIB");

	if (pSubDir != NULL)
		::PathAppend(pDir, pSubDir);

	sDir.ReleaseBuffer();
}

int SetLibraryComboBox(CComboBox &comboBox)
{
	HTREEITEM hItem;
	CItemFolder *pItemFolder;
	int i, nIndex;

	hItem = g_pTreeCtrl->GetChildItem(g_pTreeCtrl->GetRootItem());
	for (i = 0; hItem != NULL; i++) {
		pItemFolder = (CItemFolder *)g_pTreeCtrl->GetItemData(hItem);
		if (pItemFolder->m_nItemType == ITEM_FOLDER) {
			nIndex = comboBox.AddString(pItemFolder->m_FolderRec.folderName);
			comboBox.SetItemDataPtr(nIndex, pItemFolder);
		}

		hItem = g_pTreeCtrl->GetNextSiblingItem(hItem);
	}

	return i;
}

void GetNewFolder(LPCTSTR pDir, LPCTSTR pPrefix, CString &path)
{
	int i;
	struct _stat st;

	for (i = 0; ; i++) {
		path.Format("%s\\%s%d", pDir, pPrefix,  i);
		if (_stat(path, &st) != 0)
			break;
	}
}

void DeleteDirectory(LPCTSTR pDirName)
{
	CString dir;
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	CString path;

	dir = pDirName;

	hdir = FindFirstFile(dir + "\\*", &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			path = dir + "\\" + findBuf.cFileName;
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0)
					DeleteDirectory(path);
			} else
				DeleteFile(path);
		} while (FindNextFile(hdir, &findBuf));

		FindClose(hdir);
	}

	RemoveDirectory(pDirName);
}

int CompareTitle(CString &str1, CString &str2)
{
	int n1, n2;
	int n;
	static char num[] = "0123456789";

	n1 = str1.FindOneOf(num);
	n2 = str2.FindOneOf(num);

	if (n1 != -1 && n1 == n2) {
		n = str1.Left(n1).CompareNoCase(str2.Left(n2));
		if (n == 0)
			n = atoi(str1.Mid(n1)) - atoi(str2.Mid(n2));
		return n;
	} else
		return str1.CompareNoCase(str2);
}

CString ConvFileTime(FILETIME *pFileTime)
{
	SYSTEMTIME st;
	CString str;

	FileTimeToSystemTime(pFileTime, &st);

	str.Format("%04d%02d%02d%02d%02d", st.wYear, st.wMonth, st.wDay, st.wHour, st.wMinute);
	return str;
}

CString RemoveDir(CString &pathName)
{
	int n;
	CString str;

	n = pathName.ReverseFind('\\');
	if (n != -1)
		str = pathName.Mid(n + 1);
	else
		str = pathName;

	return str;
}

CString RemoveExt(CString &fileName)
{
	int n;
	CString str;

	if ((n = fileName.ReverseFind('.')) != -1)
		str = fileName.Left(n);
	else
		str = fileName;

	return str;
}

CItemInbox *OpenInbox()
{
	CMMPcsDB *pPcsDB;
	FOLDERREC folderRec;
	CString dir;
	CString folderID;

	::GetInboxDir(dir);

	g_bNoDispError = TRUE;
	if ((pPcsDB = g_pMMLibDB->OpenMMLibrary(CHT_LOCAL, "", dir, FALSE)) != NULL) {
		pPcsDB->ReadLibraryEx(&folderRec);
	} else {
		CreateDirectoryAll(dir);
		folderRec.folderType =  FTYPE_LIBRARY;
		folderRec.folderName.LoadString(IDS_INBOX);
		folderRec.creator = GetDefaultUserName();
		folderRec.remark = "1";
		folderRec.keyword = "";
		if ((pPcsDB = g_pMMLibDB->CreateMMLibrary(CHT_LOCAL, "", dir, FALSE, &folderRec)) == NULL) {
			AfxMessageBox(IDS_CREATE_INBOX_FAIL);
			return NULL;
		}
	}

	// 旧インボックスの処置
	if (folderRec.remark.IsEmpty()) {
		FOLDERREC folderRec2;
		CString folderID;
		if (pPcsDB->ReadChildFolderEx(FID_LIBRARY, &folderRec2, TRUE)) {
			if (folderRec2.folderType == FTYPE_GFOLDER) {
				folderRec.folderName = folderRec2.folderName;
				folderRec.remark = "1";
				pPcsDB->UpdateFolderEx(&folderRec);

				folderID = folderRec2.folderID;
				while (pPcsDB->ReadChildFolderEx(folderID, &folderRec2, TRUE)) {
					folderRec2.parentFolderID = folderRec.folderID;
					pPcsDB->UpdateFolderEx(&folderRec2);
				}

				pPcsDB->DeleteFolderEx(folderID);
			}
		}
	}

	CItemInbox *pItemInbox = new CItemInbox(pPcsDB);
	pItemInbox->m_nFolderType = FTYPE_GFOLDER;
	pItemInbox->m_FolderRec = folderRec;

	return pItemInbox;
}

void WritePicture(CMMPcsDB *pPcsDB, LPCTSTR pDataID, HDIB hDIB)
{
	HDIB hDIB2;

	if (hDIB != NULL)
		hDIB2 = MakeSmallPicture(hDIB, PICT_WIDTH, PICT_HEIGHT);
	else
		hDIB2 = NULL;

	pPcsDB->WritePictureDataEx(pDataID, hDIB, hDIB2, GetMMType(hDIB));
}

CString GetDefaultUserName()
{
	CString userName;
	DWORD userNameSize = MAXUSERNAME;

	::GetUserName(userName.GetBuffer(MAXUSERNAME), &userNameSize);
	userName.ReleaseBuffer();

	return userName;
}

CString &GetString(UINT nID, int nIndex)
{
	static CString str[2];

	str[nIndex].LoadString(nID);
	return str[nIndex];
}

void AutoNextNumbering(CString &sCode, int nStep)
{
	static char number[] = "0123456789";
	int n;

	if ((n = sCode.FindOneOf(number)) == -1)
		return;

	CString format;
	CString str;
	int i, len;
	long nNum, nMax;

	str = sCode.Mid(n).SpanIncluding(number);
	len = str.GetLength();
	for (i = 0, nMax = 1; i < len; i++)
		nMax *= 10;
	nNum = (atol(str) + nStep) % nMax;

	format.Format("%%0%du", len);
	str.Format(format, nNum);
	sCode.Delete(n, len);
	sCode.Insert(n, str);
}

void ExecProcess(LPCTSTR pCommand)
{
	STARTUPINFO	siStartupInfo;
	PROCESS_INFORMATION	piProcInfo;

	memset(&siStartupInfo, 0, sizeof(siStartupInfo));
	siStartupInfo.cb = sizeof(siStartupInfo);
	CreateProcess(NULL, (LPTSTR)pCommand, NULL, NULL, FALSE, NORMAL_PRIORITY_CLASS, NULL, ".", &siStartupInfo, &piProcInfo);
	CloseHandle(piProcInfo.hThread);
	WaitForSingleObject(piProcInfo.hProcess, INFINITE);
	CloseHandle(piProcInfo.hProcess);
}
