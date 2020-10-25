#include "stdafx.h"
#include "Directory.h"
#include <shlobj.h>
#include <shlwapi.h>

#if defined(_LANG_JPN)
#define DATA_DIRECTORY	"xxxx\\DSSF3J"
#elif defined(_LANG_ENG)
#define DATA_DIRECTORY	"xxxx\\DSSF3E"
#endif

BOOL CreateDirectoryAll(LPCTSTR pDirPath)
{
	DWORD err;
	char *p;
	char buf[MAX_PATH];

	if (!::CreateDirectory(pDirPath, NULL)) {
		err = ::GetLastError();
		if (err == ERROR_ALREADY_EXISTS)
			return TRUE;
		else if (err == ERROR_PATH_NOT_FOUND) {
			strcpy_s(buf, pDirPath);
			if ((p = strrchr(buf, '\\')) != NULL) {
				*p = '\0';
				if (CreateDirectoryAll(buf))
					return ::CreateDirectory(pDirPath, NULL);
				else
					return FALSE;
			} else
				return FALSE;
		} else
			return FALSE;
	}

	return TRUE;
}

void DeleteDirectoryAll(LPCTSTR pDirName)
{
	CString dir;
	HANDLE	hdir;
	WIN32_FIND_DATA	findBuf;
	CString path;

	dir = pDirName;

	hdir = ::FindFirstFile(dir + "\\*", &findBuf);
	if (hdir != INVALID_HANDLE_VALUE) {
		do {
			path = dir + "\\" + findBuf.cFileName;
			if (findBuf.dwFileAttributes & FILE_ATTRIBUTE_DIRECTORY) {
				if (strcmp(findBuf.cFileName, ".") != 0 && strcmp(findBuf.cFileName, "..") != 0)
					DeleteDirectoryAll(path);
			} else
				::DeleteFile(path);
		} while (::FindNextFile(hdir, &findBuf));
	}

	::FindClose(hdir);

	::RemoveDirectory(pDirName);
}

static void GetFolder(TCHAR *pPath)
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

void GetDataDirectory(CString &sDataDirectory)
{
	TCHAR szPath[MAX_PATH];

	GetFolder(szPath);
	::PathAppend(szPath, DATA_DIRECTORY);

	if (!::PathFileExists(szPath))
		CreateDirectoryAll(szPath);

	sDataDirectory = szPath;
}

void GetDefaultDatabaseDirectory(CString &sDatabaseDirectory)
{
	CString sDataDirectory;

	GetDataDirectory(sDataDirectory);
	sDatabaseDirectory.Format("%s\\%s", sDataDirectory, "DataBase");
}

void GetDefaultBackupDirectory(CString &sBackupDirectory)
{
	CString sDataDirectory;

	GetDataDirectory(sDataDirectory);
	sBackupDirectory.Format("%s\\%s", sDataDirectory, "Backup");
}
