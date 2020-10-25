#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		ExcelLib.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CExcelLib,CWorksheet,CExcelData クラスのインターフェイス
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************

// Excel側で用意されているCOMインターフェイスを使用して、Excelを操作するために
// #importを使用してタイプライブラリを取り込む
/*
// 内容：符号付きの値を代入する変数には、符号付きの型にキャストしなければなりません
#pragma warning( disable : 4146 )	// リビルド時のWarningを非表示にするため
// 内容：'name' を自動的に除外し、タイプ ライブラリ 'library' をインポートします
#pragma warning( disable : 4192 )	// リビルド時のWarningを非表示にするため
// 内容：コンパイラの制限 : 行番号の出力を中止します。
#pragma warning( disable : 4049 )	// リビルド時のWarningを非表示にするため

// 環境制限：動作環境はMSOffic2000、インストール先はC:\Program Files\Microsoft Office
#import "C:\Program Files\Microsoft Office\Office\MSO9.DLL"
#import "C:\Program Files\Common Files\Microsoft Shared\VBA\VBA6\VBE6EXT.OLB"
#import "C:\Program Files\Microsoft Office\Office\EXCEL9.OLB" \
rename("DialogBox", "DialogBoxForExcel") rename( "RGB", "RGBForExcel") no_dual_interfaces
*/

#include "ArrayEx.h"

// エクセルファイル操作
class CExcelLib
{
public:
	CExcelLib();
	virtual ~CExcelLib();

	CString m_sErrorMsg;		// エラーメッセージ
	int m_nErrorCode;			// エラーコード

	BOOL Open(LPCTSTR pFileName);
	BOOL New();
	BOOL Exec(CStringArray &aPathName);
	void Close(BOOL bQuit = TRUE);

	Excel::_ApplicationPtr GetApplication() { return m_pExcel; }
	Excel::WorkbooksPtr GetWorkbooks() { return m_pBooks; }
	Excel::_WorkbookPtr GetWorkbook() { return m_pBook; }
	Excel::WorksheetsPtr GetWorksheets() { return m_pSheets; }

protected:
	Excel::_ApplicationPtr	m_pExcel;		// Excelアプリオブジェクトポインタ
	Excel::WorkbooksPtr		m_pBooks;		// WorkBooksオブジェクトポインタ
	Excel::_WorkbookPtr		m_pBook;		// WorkBookオブジェクトポインタ
	Excel::WorksheetsPtr	m_pSheets;		// Sheetsオブジェクトポインタ

	BOOL Initialize();
	void ErrorProc(_com_error &e);
};

// エクセル配列データを保持・操作する
class CExcelData
{
public:
	CExcelData();
	void AddSheet(LPCTSTR pSheetName);
	void Create(int nRows, int nCols);
	BOOL Read(CExcelLib &cExcel, int nFirstSheet, int nMaxSheet);
	BOOL Write(CExcelLib &cExcel, LPCTSTR pPathName = NULL);
	void Copy(CExcelData &cSrc);
	void SetCols(int nCols);
// Modify ... ( ADD )
	BOOL SetSheetName(CExcelLib &cExcel, LPCTSTR pPathName = NULL);
// By Y.Itabashi (xxxxx) 2007.02.15
// Modify ... ( ADD )
	BOOL DeleteSheet(CExcelLib &cExcel, LPCTSTR pPathName = NULL);
	void SetDeleteSheet(LPCTSTR pSheetName);
// By Y.Itabashi (xxxxx) 2007.02.19
	void SetRows(int nRows);
	int AddRows(int nAddRows = 1);
	void Clear();
	void GetElement(int nRow, int nCol, _variant_t &vData);
	void GetElement(int nRow, int nCol, CString &sData);
	void GetElement(int nRow, int nCol, double &dData);
	void GetElement(int nRow, int nCol, int &nData);
	void PutElement(int nRow, int nCol, _variant_t &vData);
	void PutElement(int nRow, int nCol, LPCTSTR pData) { PutElement(nRow, nCol, (_variant_t)pData); }
	void PutElement(int nRow, int nCol, double dData) { PutElement(nRow, nCol, (_variant_t)dData); }
	void PutElement(int nRow, int nCol, int nData) { PutElement(nRow, nCol, (_variant_t)(long)nData); }
	int GetUsedCols() { return m_aSheet[m_nCurrentSheetNo].nUsedCols; }
	int GetUsedRows() { return m_aSheet[m_nCurrentSheetNo].nUsedRows; }
	int GetAllocRows() { return m_aSheet[m_nCurrentSheetNo].nAllocRows; }
	COleSafeArray &GetSafeArray() { return m_aSheet[m_nCurrentSheetNo].cSafeArray; }
	void SetCurrentSheet(int nSheetNo) { m_nCurrentSheetNo = nSheetNo; }
	void SetCurrentSheet(LPCTSTR pSheetName) { m_aSheet.LookupElement(pSheetName, m_nCurrentSheetNo); }
	int GetSheetNum() { return m_aSheet.GetSize(); }
	CString GetSheetName() { return m_aSheet[m_nCurrentSheetNo].sSheetName; }
// Modify ... ( ADD )
	int GetCurrentSheetNo()	{ return m_nCurrentSheetNo; }
	void SetCurrentSheetName( CString sSheetName )	{ m_aSheet[m_nCurrentSheetNo].sSheetName = sSheetName; }
// By Y.Itabashi (xxxxx) 2007.02.14

protected:
	struct SSheet{
		CString sSheetName;				// シート名
		COleSafeArray cSafeArray;		// エクセルデータ配列
		int nUsedRows;					// 使用行数
		int nUsedCols;					// 使用列数
		int nAllocRows;					// 確保行数
// Modify ... ( ADD )
		bool bDeleteFlag;				// 削除シート
// By Y.Itabashi (xxxxx) 2007.02.19
	};
	typedef CArrayEx2 <SSheet, SSheet&> CSheetArray;

	CSheetArray m_aSheet;
	int m_nCurrentSheetNo;

	void ReadSub(Excel::_WorksheetPtr pWorksheet, SSheet *pSheet);
	void WriteSub(Excel::_WorksheetPtr pWorksheet, SSheet *pSheet);
};
