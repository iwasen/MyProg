// ExcelFile.h: CExcelFile クラスのインターフェイス
//
//////////////////////////////////////////////////////////////////////

#if !defined(AFX_EXCELFILE_H__93136D01_4B88_45FE_9900_43F8322C0D9F__INCLUDED_)
#define AFX_EXCELFILE_H__93136D01_4B88_45FE_9900_43F8322C0D9F__INCLUDED_

#if _MSC_VER > 1000
#pragma once
#endif // _MSC_VER > 1000

#include "ExcelLib.h"
#include "DataCheck.h"

// 機種別カラムデータ
struct SKishuCols {
	CString sKishuName;
	int nCol;
};
typedef CArrayEx2 <SKishuCols, SKishuCols&> CKishuColsArray;

// 機種別カラムデータ
struct SKishuCols2 {
	int nCol;
	CKishuColsArray aKishuCols;
};
typedef CArrayEx <SKishuCols2, SKishuCols2&> CKishuCols2Array;

class CExcelFile : public CExcelData
{
public:
	// カラム情報
	struct SColumnInfo {
		LPCTSTR pDefault;
		CDataCheck cDataCheck;
	};

public:
	CString m_sFileName;
	CString m_sFolderName;
	CString m_sPathName;
	CKishuCols2Array m_aKishuCols;

	CExcelFile();
	BOOL AccessCheck(LPCTSTR pFolderName);
	BOOL ReadFile(int nFirstSheet = 0);
	BOOL WriteFile(LPCTSTR pFolderName, LPCTSTR pErrCode);
	BOOL FileDataCheck();
	int GetRow(int nKeys, ...);
	void SetDefaultData(int nRow);
	virtual CString GetNewFileName();
	void ClearAllData();
	void SetHeader();
	void FileLock();
	void FileUnlock();
	int GetColumnNo(int nColumnNo);
	int GetColumnNum();
	virtual CString &GetColumnName(int nCol);
	CString GetFileName();
	void ErrorFileLog(LPCTSTR pErrorMsg, int nRow, int nCol);
	static void ErrorFileLog(LPCTSTR pErrorMsg);
// Modify ... ( ADD )
	BOOL CheckHeader();
// By Y.Itabashi (xxxxx) 2007.02.20

protected:
	SColumnInfo *m_pColumnInfo;
	int m_nColumnInfo;
	int m_nMaxSheet;
	CIntArrayEx m_aKeyCols;
	CArrayEx2 <int, int> m_aKeyData;
	CFile m_cFileLock;
	int m_nFileNo;
// Modify ... ( ADD )
	int m_bSetSheetName;
	int m_bDeleteSheet;
	int m_bColumnCheck;
	int m_bFileLock;
// By Y.Itabashi (xxxxx) 2007.02.15

	BOOL FileAccessCheck(LPCTSTR pFolderName, LPCTSTR pFileName, BOOL bMustExist);
	void SetKeys(int nKeys, ...);
	void MakeKey();
	void AddKey(int nRow, LPCTSTR pKey);
	void CreateArray();
	void GetKishuCols(int nCol);
};

#endif // !defined(AFX_EXCELFILE_H__93136D01_4B88_45FE_9900_43F8322C0D9F__INCLUDED_)
