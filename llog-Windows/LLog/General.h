#pragma once

class CGeneral
{
public:
	static CString GetOriginalPhotoPath(const CTime &oData, LPCTSTR pFolderName, LPCTSTR pFileName);
	static CString GetSmallPhotoPath(const CTime &oData, LPCTSTR pFolderName, LPCTSTR pFileName);
	static BOOL CreateDirectoryAll(LPCTSTR pDirPath);
};
