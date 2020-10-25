// ExcelFile.cpp: CExcelFile クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "ExcelFile.h"
#include <sys/types.h>
#include <sys/stat.h>
#include <stdio.h>



#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

CExcelFile::CExcelFile()
{
	m_pColumnInfo = NULL;
	m_nColumnInfo = 0;
	m_nMaxSheet = 1;
// Modify ... ( ADD )
	m_bSetSheetName = 0;
	m_bDeleteSheet = 0;
// By Y.Itabashi (xxxxx) 2007.02.15
// Modify ... ( ADD )
	m_bColumnCheck = 1;
	m_bFileLock = 1;
// By Y.Itabashi (xxxxx) 2007.02.19
}

BOOL CExcelFile::FileAccessCheck(LPCTSTR pFolderName, LPCTSTR pFileName, BOOL bMustExist)
{
	CString sPathName;
	CStringArrayEx aPathName;

	m_sFolderName = pFolderName;

	// パス名生成
	if (pFolderName != NULL)
		sPathName = CGlobal::MakePathName(pFolderName, pFileName);
	else
		sPathName = pFileName;

	// ディレクトリ内のファイルを検索
	CGlobal::FindFiles(sPathName, aPathName);

	// ファイル数チェック
	if (aPathName.GetSize() == 0) {
		// ファイルが無ければエラー
		if (bMustExist) {
			g_pSystem->DispMessage("E004001", m_sFileName);
			return FALSE;
		} else
			return TRUE;
	} else if (aPathName.GetSize() > 1) {
		// ファイルが複数あればエラー
		CStringArrayEx aFileName;
		int nSize = aPathName.GetSize();
		for (int i = 0; i < nSize; i++)
			aFileName.Add(CGlobal::GetFileNameFromPath(aPathName[i]));
		g_pSystem->DispMessage("E004002", aFileName.Join(","));
		return FALSE;
	}

	m_sPathName = aPathName[0];

	// 読み込み可能チェック
	CFile cFile;
	if (!cFile.Open(m_sPathName, CFile::modeRead | CFile::shareDenyNone)) {
		g_pSystem->DispMessage("E004001", CGlobal::GetFileNameFromPath(m_sPathName));
		return FALSE;
	} else
		cFile.Close();

	FileLock();

	return TRUE;
}

BOOL CExcelFile::ReadFile(int nFirstSheet)
{
	CExcelLib cExcel;

	if (m_sPathName.IsEmpty())
		return TRUE;

	// エクセルファイルをオープンする
	if (!cExcel.Open(m_sPathName))
		return FALSE;

	// 全シートのデータを読み込む
	Read(cExcel, 1, m_nMaxSheet);

	cExcel.Close();

	// カラム数チェック
// Modify ... ( CHANGE )
	if( m_bColumnCheck ){
		if( CheckHeader() == FALSE ){
			return FALSE;
		}
/*		int nSheetNum = m_aSheet.GetSize();
		for (int i = nFirstSheet; i < nSheetNum; i++) {
			if (m_aSheet[i].nUsedCols < m_nColumnInfo) {
				g_pSystem->DispMessage("E004003", CGlobal::GetFileNameFromPath(m_sPathName));
				return FALSE;
			}
		}*/
	}
// By Y.Itabashi (xxxxx) 2007.02.20

	// キーデータ作成
	MakeKey();

	return TRUE;
}

BOOL CExcelFile::WriteFile(LPCTSTR pFolderName, LPCTSTR pErrCode)
{
	CExcelLib cExcel;

	CString sPathNameSave = m_sPathName;

	if (m_sPathName.IsEmpty()) {
		if (!cExcel.New())
			return FALSE;

		if (pFolderName == NULL)
			pFolderName = m_sFolderName;

		m_sPathName = CGlobal::MakePathName(pFolderName, GetNewFileName());
	} else {
		FileUnlock();

		// 書き込むファイル名を取得
		CString sWriteFileName(m_sPathName);
		if (pFolderName != NULL)
			sWriteFileName = CGlobal::MakePathName(pFolderName, GetNewFileName());

		// 書き込みチェック
        struct _stat buf;
        int result;

        /* "stat.c" に関連付けられているデータを取得します。 */
        result = _stat( m_sFolderName, &buf );
        if (buf.st_mode == 0x416d) {
			if (pErrCode != NULL)
				g_pSystem->DispMessage(pErrCode, GetFileName());
			return FALSE;
		}

		CFileStatus cStatus;
		if (CFile::GetStatus(sWriteFileName, cStatus)) {
			CFile cFile;
			if (!cFile.Open(sWriteFileName, CFile::modeWrite | CFile::shareDenyWrite)) {
				if (pErrCode != NULL)
					g_pSystem->DispMessage(pErrCode, GetFileName());
				return FALSE;
			}
			cFile.Close();
		} else {	// add 2007/02/10 xxxxx kuwa
			if (g_pSystem->m_Save_mode == 1) {
				if (pErrCode != NULL)
					g_pSystem->DispMessage(pErrCode, GetFileName());
				return FALSE;
			}
		}

		// エクセルファイルをオープンする
		if (!cExcel.Open(m_sPathName)) {
			if (pErrCode != NULL)
				g_pSystem->DispMessage(pErrCode, GetFileName());			
			return FALSE;
		}

		// フォルダ名の指定があったら新しいパス名を保存
		if (pFolderName != NULL)
			m_sPathName = sWriteFileName;
	}

	if (GetUsedRows() == 0)
		CreateArray();

	// 全セルのデータを書き込み
	if (!Write(cExcel, m_sPathName)) {
		g_pSystem->DispMessage(pErrCode, GetFileName());
		m_sPathName = sPathNameSave;
		return FALSE;
	}

// Modify ... ( ADD )
	if( m_bSetSheetName ){
		if (!SetSheetName(cExcel, m_sPathName)) {
			g_pSystem->DispMessage(pErrCode, GetFileName());
			m_sPathName = sPathNameSave;
			return FALSE;
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.15

// Modify ... ( ADD )
	if( m_bDeleteSheet ){
		if (!DeleteSheet(cExcel, m_sPathName)) {
			g_pSystem->DispMessage(pErrCode, GetFileName());
			m_sPathName = sPathNameSave;
			return FALSE;
		}
	}
// By Y.Itabashi (xxxxx) 2007.02.15

	cExcel.Close();

	FileLock();

	return TRUE;
}

BOOL CExcelFile::FileDataCheck()
{
	CString sData;
	BOOL bError = FALSE;
	_variant_t vEmpty;

	// シートによるループ
	int nSheetNum = GetSheetNum();
	for (int nSheetNo = 0; nSheetNo < nSheetNum; nSheetNo++) {
		SetCurrentSheet(nSheetNo);

		// 行によるループ
		int nUsedRows = GetUsedRows();
		for (int nRow = 3; nRow <= nUsedRows; nRow++) {
			// 列によるループ
			for (int i = 0; i < m_nColumnInfo; i++) {
				SColumnInfo *pColumnInfo = m_pColumnInfo + i;
				int nCol = GetColumnNo(i + 1);

				// データ取得
				GetElement(nRow, nCol, sData);

				// エラーチェック
				if (!sData.IsEmpty()) {
					if ((pColumnInfo->cDataCheck.m_nDataType != eDataTypeNumber && !pColumnInfo->cDataCheck.LengthCheck(sData))
							|| !pColumnInfo->cDataCheck.ValidCharCheck(sData)
							|| !pColumnInfo->cDataCheck.RangeCheck(sData)) {
						// エラーログ
						ErrorFileLog("入力値不正", nRow, nCol);

						// エラーならデフォルト値をセット
						if (pColumnInfo->pDefault == NULL)
							PutElement(nRow, nCol, vEmpty);
						else
							PutElement(nRow, nCol, pColumnInfo->pDefault);

						bError = TRUE;
					}
				}
			}
		}
	}

	return !bError;
}

void CExcelFile::MakeKey()
{
	CString sKey, sKeys;
	int nKeyNum = m_aKeyCols.GetSize();

	for (int nRow = 3; nRow <= GetUsedRows(); nRow++) {
		sKeys.Empty();
		for (int i = 0; i < nKeyNum; i++) {
			GetElement(nRow, m_aKeyCols[i], sKey);
			sKeys += sKey;
		}

		AddKey(nRow, sKeys);
	}
}

void CExcelFile::AddKey(int nRow, LPCTSTR pKey)
{
	int *pRow = m_aKeyData.LookupElement(pKey);
	if (pRow == NULL)
		pRow = m_aKeyData.AddElement(pKey);
	*pRow = nRow;
}

void CExcelFile::SetKeys(int nKeys, ...)
{
	va_list args;
	va_start(args, nKeys);

	for (int i = 0; i < nKeys; i++)
		m_aKeyCols.Add(va_arg(args, int));
}

int CExcelFile::GetRow(int nKeys, ...)
{
	va_list args;
	va_start(args, nKeys);

	if (GetUsedRows() == 0)
		CreateArray();

	int i;
	CString sKeys;
	for (i = 0; i < nKeys; i++)
		sKeys += va_arg(args, LPCTSTR);
	va_end(args);

	int *pRow = m_aKeyData.LookupElement(sKeys);
	if (pRow != NULL)
		return *pRow;

	int nRow = AddRows();
	SetDefaultData(nRow);

	va_start(args, nKeys);
	int nKeyNum = m_aKeyCols.GetSize();
	for (i = 0; i < nKeyNum; i++)
		PutElement(nRow, m_aKeyCols[i], va_arg(args, LPCTSTR));
	va_end(args);

	AddKey(nRow, sKeys);

	return nRow;
}

// 指定した行にデフォルト値を書き込む
void CExcelFile::SetDefaultData(int nRow)
{
	for (int i = 0; i < m_nColumnInfo; i++) {
		int nCol = i + 1;
		if (nCol <= GetUsedCols()) {
			if (m_pColumnInfo[i].pDefault != NULL)
				PutElement(nRow, GetColumnNo(nCol), m_pColumnInfo[i].pDefault);
		}
	}
}

void CExcelFile::CreateArray()
{
	Create(2, m_nColumnInfo);

	SetHeader();
}

void CExcelFile::SetHeader()
{
	// １行目：ファイル名
//	PutElement(1, 1, m_sFileName);

	// ２行目：項目名
	int nSize = GetColumnNum();
	for (int i = 0; i < nSize; i++) {
		int nCol = i + 1;
		PutElement(2, nCol, GetColumnName(nCol));
	}
}

CString CExcelFile::GetNewFileName()
{
	return m_sFileName;
}

void CExcelFile::FileLock()
{
// Modify ... ( ADD )
	if( !m_bFileLock )
		return;
// By Y.Itabashi (xxxxx) 2007.02.19

	FileUnlock();

	if (!m_cFileLock.Open(m_sPathName, CFile::modeWrite | CFile::shareDenyWrite))
		g_pSystem->m_bReadOnly = TRUE;
}

void CExcelFile::FileUnlock()
{
	if (m_cFileLock.m_hFile != CFile::hFileNull)
		m_cFileLock.Close();
}

void CExcelFile::ClearAllData()
{
	SSheet *pSheet = &m_aSheet[m_nCurrentSheetNo];
	if (pSheet->nUsedRows == 0)
		CreateArray();
	else
		pSheet->nUsedRows = 2;
}

void CExcelFile::GetKishuCols(int nCol)
{
	SSheet *pSheet = &m_aSheet[m_nCurrentSheetNo];
	CString sKishuName;

	CString sColumnName = GetColumnName(nCol);
	int nLen = sColumnName.GetLength();

	SKishuCols2 *pKishuColsArray = m_aKishuCols.AddElement();
	pKishuColsArray->nCol = nCol;

	nCol = GetColumnNo(nCol);
	while (nCol <= pSheet->nUsedCols) {
		GetElement(2, nCol, sKishuName);
		if (sKishuName.GetLength() > nLen && sKishuName.Left(nLen) == sColumnName) {
			sKishuName = sKishuName.Mid(nLen + 1);
			SKishuCols *pKishuCols = pKishuColsArray->aKishuCols.AddElement(sKishuName);
			pKishuCols->sKishuName = sKishuName;
			pKishuCols->nCol = nCol++;
		} else
			break;
	}
}

int CExcelFile::GetColumnNo(int nColumnNo)
{
	int nColumnNo2 = nColumnNo;

	int nSize = m_aKishuCols.GetSize();
	for (int i = 0; i < nSize; i++) {
		if (nColumnNo > m_aKishuCols[i].nCol)
			nColumnNo2 += m_aKishuCols[i].aKishuCols.GetSize() - 1;
		else
			break;
	}

	return nColumnNo2;
}

int CExcelFile::GetColumnNum()
{
	return g_pSystem->m_aDPEInterface[m_nFileNo].aItem.GetSize() - 1;
}

CString &CExcelFile::GetColumnName(int nCol)
{
	return g_pSystem->m_aDPEInterface[m_nFileNo].aItem[nCol].aList[0];
}

CString CExcelFile::GetFileName()
{
	if (!m_sPathName.IsEmpty())
		return CGlobal::GetFileNameFromPath(m_sPathName);

	return m_sFileName;
}

void CExcelFile::ErrorFileLog(LPCTSTR pErrorMsg, int nRow, int nCol)
{
	CString sItemName, sErrorData, sKoteiName, sYosoSagyogunName;

	GetElement(2, nCol, sItemName);
	GetElement(nRow, nCol, sErrorData);

	if (m_sFileName == "element.xls") {
		GetElement(nRow, 6, sKoteiName);
		GetElement(nRow, 7, sYosoSagyogunName);
	}

	CString sLog;
	sLog.Format("%s\t%s(%s)\t%s\t%d\t%s\t%s\n", GetFileName(), pErrorMsg, sErrorData, sItemName, nRow, sKoteiName, sYosoSagyogunName);
	ErrorFileLog(sLog);
}

void CExcelFile::ErrorFileLog(LPCTSTR pErrorMsg)
{
	static BOOL bNoTruncate;
	CStdioFile cFile;

	UINT nOpenFlag = CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone;
	if (bNoTruncate)
		nOpenFlag |= CFile::modeNoTruncate;
	else
		bNoTruncate = TRUE;

	if (cFile.Open(CGlobal::MakePathName(g_pSystem->m_cIniUser.m_sDataPath, "プロジェクト.log"), nOpenFlag)) {
		cFile.SeekToEnd();
		cFile.WriteString(pErrorMsg);
	}
}

// Modify ... ( ADD )
BOOL CExcelFile::CheckHeader()
{

	int nHeaderRow = 2;
	CString sData, sCheckData1, sCheckData2;
	CString sHeader;

	if( g_pSystem->m_aDPEInterface.GetSize() < m_nFileNo )
		return FALSE;

	if( g_pSystem->m_aDPEInterface[m_nFileNo].aItem.GetSize() == 0 )
		return FALSE;

	int nSheetNum = GetSheetNum();
	for (int nSheetNo = 0; nSheetNo < nSheetNum; nSheetNo++) {
		SetCurrentSheet(nSheetNo);

		int nUsedCols = GetUsedCols();
		int nCols = 1;
		int nHeaderNum = g_pSystem->m_aDPEInterface[m_nFileNo].aItem.GetSize();
		for( int nHeader = 1; nHeader < nHeaderNum; nHeader++ ){

			if( nCols > nUsedCols )
				break;

			sHeader = g_pSystem->GetDPEIndex( m_nFileNo, nHeader, 0 );

			if( sHeader.GetLength() == 0 ){
				nCols++;
				continue;
			}

			GetElement( nHeaderRow, nCols, sData );
			int nCheckStr = sData.Find( ":", 0 );
			if( nCheckStr < 0 ){
				nCheckStr = sData.Find( "_", 0 );
				if( nCheckStr < 0 )
					sCheckData1 = sData;
				else
					sCheckData1 = sData.Left( nCheckStr );
			}else
				sCheckData1 = sData.Left( nCheckStr );

			if( sCheckData1.Compare( sHeader ) != 0 ){
				g_pSystem->DispMessage("E004003", CGlobal::GetFileNameFromPath(m_sPathName), sHeader);
				return FALSE;
			}

			while( nCols+1 <= nUsedCols ){
				GetElement( nHeaderRow, nCols+1, sData );
				nCheckStr = sData.Find( ":", 0 );
				if( nCheckStr < 0 ){
					nCheckStr = sData.Find( "_", 0 );
					if( nCheckStr < 0 )
						sCheckData2 = sData;
					else
						sCheckData2 = sData.Left( nCheckStr );
				}else
					sCheckData2 = sData.Left( nCheckStr );

				if( sCheckData1.Compare( sCheckData2 ) == 0 )
					nCols++;
				else
					break;
			}

			nCols++;
		}
		
	}

	return TRUE;
}
// By Y.Itabashi (xxxxx) 2007.02.20
