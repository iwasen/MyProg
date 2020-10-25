#include "StdAfx.h"
#include "General.h"

CString CGeneral::GetOriginalPhotoPath(const CTime &oData, LPCTSTR pFolderName, LPCTSTR pFileName)
{
	CString sPathName;

	sPathName = "t:\\photo";

	if ((CString)pFolderName != "‚»‚Ì‘¼")
		sPathName += oData.Format("\\%Y");

	if (pFolderName != NULL)
		sPathName += (CString)"\\" + pFolderName;

	if (pFileName != NULL)
		sPathName += (CString)"\\" + pFileName;

	return sPathName;
}

CString CGeneral::GetSmallPhotoPath(const CTime &oData, LPCTSTR pFolderName, LPCTSTR pFileName)
{
	CString sPathName;

	sPathName = "s:\\photo";

	if ((CString)pFolderName != "‚»‚Ì‘¼")
		sPathName += oData.Format("\\%Y");

	if (pFolderName != NULL)
		sPathName += (CString)"\\" + pFolderName;

	if (pFileName != NULL)
		sPathName += (CString)"\\" + pFileName;

	return sPathName;
}

BOOL CGeneral::CreateDirectoryAll(LPCTSTR pDirPath)
{
	DWORD err;
	char *p;
	char buf[_MAX_PATH];

	if (!::CreateDirectory(pDirPath, NULL)) {
		err = GetLastError();
		if (err == ERROR_ALREADY_EXISTS)
			return TRUE;
		else if (err == ERROR_PATH_NOT_FOUND) {
			strcpy_s(buf, pDirPath);
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
