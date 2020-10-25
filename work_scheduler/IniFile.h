// IniFile.h: CIniFile クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_INIFILE_H__8379858F_1721_4FD6_BA46_5C41E30B2191__INCLUDED_)
#define AFX_INIFILE_H__8379858F_1721_4FD6_BA46_5C41E30B2191__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "Const.h"

class CIniFile
{
protected:
	BOOL ReadSystemIni();
	BOOL ReadUserIni(LPCTSTR pShokuban);
	CString ReadIniString(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, LPCTSTR pDefault = "");
	int ReadIniInt(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, int nDefault = 0);
	double ReadIniFloat(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, double fDefault = 0);
	COLORREF ReadIniColor(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, COLORREF rgbDefault = RGB(0,0,0));
	BOOL WriteIniString(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, LPCTSTR pData);
	BOOL WriteIniInt(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, int nData);
	BOOL WriteIniFloat(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, double fData);
};


class CIniSystem : public CIniFile
{
public:
	CString m_sSystemServerPath;
	CString m_sPartMatrixPath;
	CString m_sEngMatrixPath;
	CString m_sUserInfoPath;
	CString m_sUserListFilePath;
	CString m_sElementTableFilePath;
	CString m_sKittingTemplateFilePath;
// Mdify ... ( ADD )
	CString m_sMessageFilePath;
	CString m_sMenuFilePath;
// By Y.Itabshi (xxxxx) 2007.1.24

	BOOL ReadIniFile();
};


class CIniUser : public CIniFile
{
public:
	CString m_sDataPath;
	int m_nWindowType;
	int m_nWindowNo1DisplayType;
	int m_nWindowNo2DisplayType;
	int m_nWindowNo3DisplayType;
	COLORREF m_rgbProcessGraphColor[MAX_KISHU];
	COLORREF m_rgbSublineBarColor;
	COLORREF m_rgbKittinglineBarColor;
	COLORREF m_rgbSelectProcessColor;
	COLORREF m_rgbNewMoveProcessColor;
	COLORREF m_rgbKittingCancelProcessColor;
// Modify ... ( ADD )
	COLORREF m_rgbReferenceProcessColor;
// By Y.Itabashi (xxxxx) 2007.02.027
	COLORREF m_rgbSelectWorkerColor;
	COLORREF m_rgbCommonProcessColor;
	COLORREF m_rgbAutoProcessColor;
	COLORREF m_rgbSemiAutoProcessColor;
	COLORREF m_rgbCommonAutoProcessColor;
	COLORREF m_rgbCommonSemiAutoProcessColor;
	COLORREF m_rgbKitSublineBarColor;

	BOOL ReadIniFile(LPCTSTR pFolderName, LPCTSTR pShokuban);
	BOOL WriteIniFile(LPCTSTR pFolderName, LPCTSTR pShokuban);
};

#endif // !defined(AFX_INIFILE_H__8379858F_1721_4FD6_BA46_5C41E30B2191__INCLUDED_)
