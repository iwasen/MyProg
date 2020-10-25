// IniFile.cpp: CIniFile クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "workscheduler.h"
#include "IniFile.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::ReadIniString
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルから文字列データを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		LPCTSTR		pDefault		[I] デフォルト値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		該当エントリのデータ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルから指定されたセクション、エントリの文字列データを取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
CString CIniFile::ReadIniString(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, LPCTSTR pDefault)
{
	CString sIniData;

	::GetPrivateProfileString(pSection, pEntry, pDefault, sIniData.GetBuffer(256), 256, pFileName);
	sIniData.ReleaseBuffer();

	int n = sIniData.Find('#');
	if (n > 0)
		sIniData = sIniData.Left(n);

	sIniData.TrimLeft();
	sIniData.TrimRight();

	return sIniData;
}

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::ReadIniInt
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルから整数データを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		int			nDefault		[I] デフォルト値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		該当エントリのデータ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルから指定されたセクション、エントリの整数データを取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
int CIniFile::ReadIniInt(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, int nDefault)
{
	CString sDefault;
	sDefault.Format("%d", nDefault);
	CString sIniData = ReadIniString(pSection, pEntry, pFileName, sDefault);

	return atoi(sIniData);
}

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::ReadIniFloat
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルから浮動小数データを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		double		fDefault		[I] デフォルト値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		該当エントリのデータ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルから指定されたセクション、エントリの浮動小数データを取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
double CIniFile::ReadIniFloat(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, double fDefault)
{
	CString sDefault;
	sDefault.Format("%g", fDefault);
	CString sIniData = ReadIniString(pSection, pEntry, pFileName, sDefault);

	return atof(sIniData);
}

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::ReadIniColor
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルから色データを取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		COLORREF	rgbDefault		[I] デフォルト値
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		COLORREF		該当エントリのデータ
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルから指定されたセクション、エントリの色(RGB)データを取り出す。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
COLORREF CIniFile::ReadIniColor(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, COLORREF rgbDefault)
{
	CString sDefault;
	sDefault.Format("%d.%d.%d", GetRValue(rgbDefault), GetGValue(rgbDefault), GetBValue(rgbDefault));
	CString sColor = ReadIniString(pSection, pEntry, pFileName, sDefault);

	int n1, n2;
	COLORREF rgbColor = rgbDefault;
	if ((n1 = sColor.Find('.')) > 0) {
		if ((n2 = sColor.Find('.', n1 + 1)) > 0)
			rgbColor = RGB(atoi(sColor.Left(n1)), atoi(sColor.Mid(n1 + 1, n2 - n1 - 1)), atoi(sColor.Mid(n2 + 1)));
	}

	return rgbColor;
}

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::WriteIniString
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルに文字列データを書き込み
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		LPCTSTR		pData			[I] 書き込むデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルの指定されたセクション、エントリに文字列データを書き込む。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CIniFile::WriteIniString(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, LPCTSTR pData)
{
	return ::WritePrivateProfileString(pSection, pEntry, pData, pFileName);
}

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::WriteIniInt
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルに整数データを書き込み
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		int			nData			[I] 書き込むデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルの指定されたセクション、エントリに整数データを書き込む。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CIniFile::WriteIniInt(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, int nData)
{
	CString sData;

	sData.Format("%d", nData);
	return ::WritePrivateProfileString(pSection, pEntry, sData, pFileName);
}

//*****************************************************************************************************
//  1. 関数名
//		CIniFile::WriteIniFloat
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		iniファイルに浮動小数データを書き込み
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pSection		[I] セクション名
//		LPCTSTR		pEntry			[I] エントリ名
//		LPCTSTR		pFileName		[I] iniファイル名
//		double		fData			[I] 書き込むデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//----------------------------------------------------------------------------------------------------
//  5. 処理概要
//		iniファイルの指定されたセクション、エントリに浮動小数データを書き込む。
//----------------------------------------------------------------------------------------------------
//  6. 備考
//----------------------------------------------------------------------------------------------------
//  7. 履歴
//		2006.09.01 S.Aizawa(xxx) 新規作成
//*****************************************************************************************************
BOOL CIniFile::WriteIniFloat(LPCTSTR pSection, LPCTSTR pEntry, LPCTSTR pFileName, double fData)
{
	CString sData;

	sData.Format("%g", fData);
	return ::WritePrivateProfileString(pSection, pEntry, sData, pFileName);
}

//////////////////////////////////////////////////////////////////////
// CIniSystem クラス
//////////////////////////////////////////////////////////////////////

BOOL CIniSystem::ReadIniFile()
{
	const CString sIniPathName = "..\\Profile\\System.ini";

	// ファイル存在チェック
	CFileStatus cStatus;
	if (!CFile::GetStatus(sIniPathName, cStatus))
		return FALSE;

	m_sSystemServerPath = ReadIniString("Path", "SystemServerPath", sIniPathName);
	m_sPartMatrixPath = ReadIniString("Path", "Part-MatrixPath", sIniPathName);
	m_sEngMatrixPath = ReadIniString("Path", "Eng-MatrixPath", sIniPathName);
	m_sUserInfoPath = ReadIniString("Path", "UserInfoPath", sIniPathName);
	m_sUserListFilePath = ReadIniString("Path", "UserListFilePath", sIniPathName);
	m_sElementTableFilePath = ReadIniString("Path", "ElementTableFilePath", sIniPathName);
	m_sKittingTemplateFilePath = ReadIniString("Path", "KittingTemplateFilePath", sIniPathName);
// Modify ... ( ADD )
	m_sMessageFilePath = ReadIniString("Path", "MessageFilePath", sIniPathName);
	m_sMenuFilePath = ReadIniString("Path", "MenuFilePath", sIniPathName);
// By Y.Itabashi (xxxxx) 2007.01.24

	return TRUE;
}

//////////////////////////////////////////////////////////////////////
// CIniUser クラス
//////////////////////////////////////////////////////////////////////

BOOL CIniUser::ReadIniFile(LPCTSTR pFolderName, LPCTSTR pShokuban)
{
	CString sIniPathName;

	sIniPathName.Format("%s\\%s\\%s.ini", pFolderName, pShokuban, pShokuban);

	// ファイル存在チェック
	CFileStatus cStatus;
	if (!CFile::GetStatus(sIniPathName, cStatus))
		return FALSE;

	m_sDataPath = ReadIniString("Path", "DataPath", sIniPathName);
	//(20070213 xxxxx kuwa) m_nWindowType = ReadIniInt("Window", "WindowType", sIniPathName, 3);
	m_nWindowType = ReadIniInt("Window", "WindowDisplayType", sIniPathName, 3);
	m_nWindowNo1DisplayType = ReadIniInt("Window", "WindowNo1DisplayType", sIniPathName, 1);
	m_nWindowNo2DisplayType = ReadIniInt("Window", "WindowNo2DisplayType", sIniPathName, 2);
	m_nWindowNo3DisplayType = ReadIniInt("Window", "WindowNo3DisplayType", sIniPathName, 3);
	m_rgbProcessGraphColor[0] = ReadIniColor("Color", "ProcessGraphColor1", sIniPathName);
	m_rgbProcessGraphColor[1] = ReadIniColor("Color", "ProcessGraphColor2", sIniPathName);
	m_rgbProcessGraphColor[2] = ReadIniColor("Color", "ProcessGraphColor3", sIniPathName);
	m_rgbProcessGraphColor[3] = ReadIniColor("Color", "ProcessGraphColor4", sIniPathName);
	m_rgbProcessGraphColor[4] = ReadIniColor("Color", "ProcessGraphColor5", sIniPathName);
	m_rgbSublineBarColor = ReadIniColor("Color", "SublineBarColor", sIniPathName, RGB(0,0,0));
	m_rgbKittinglineBarColor = ReadIniColor("Color", "KittinglineBarColor", sIniPathName, RGB(255,0,0));
	m_rgbSelectProcessColor = ReadIniColor("Color", "SelectProcessColor", sIniPathName, RGB(0,0,255));
	m_rgbNewMoveProcessColor = ReadIniColor("Color", "NewMoveProcessColor", sIniPathName, RGB(0,128,0));
	m_rgbKittingCancelProcessColor = ReadIniColor("Color", "KittingCancelProcessColor", sIniPathName, RGB(255,255,0));
// Modify ... ( ADD )
	m_rgbReferenceProcessColor = ReadIniColor("Color", "ReferenceProcessColor", sIniPathName, RGB(128,255,255));
// By Y.Itabashi (xxxxx) 2007.02.27
	m_rgbSelectWorkerColor = ReadIniColor("Color", "SelectWorkerColor", sIniPathName, RGB(0,0,255));
	m_rgbCommonProcessColor = ReadIniColor("Color", "CommonProcessColor", sIniPathName, RGB(255,128,0));
	m_rgbAutoProcessColor = ReadIniColor("Color", "AutoProcessColor", sIniPathName, RGB(0,0,255));
	m_rgbSemiAutoProcessColor = ReadIniColor("Color", "SemiAutoProcessColor", sIniPathName, RGB(255,255,0));
	m_rgbCommonAutoProcessColor = ReadIniColor("Color", "CommonAutoProcessColor", sIniPathName, RGB(0,128,0));
	m_rgbCommonSemiAutoProcessColor = ReadIniColor("Color", "CommonSemiAutoProcessColor", sIniPathName, RGB(255,128,0));
	m_rgbKitSublineBarColor = ReadIniColor("Color", "KitSublineBarColor", sIniPathName, RGB(0,0,128));

	return TRUE;
}

BOOL CIniUser::WriteIniFile(LPCTSTR pFolderName, LPCTSTR pShokuban)
{
	CString sIniPathName;

	sIniPathName.Format("%s\\%s\\%s.ini", pFolderName, pShokuban, pShokuban);

	if (!WriteIniString("Path", "DataPath", sIniPathName, m_sDataPath))
		return FALSE;

	if (!WriteIniInt("Window", "WindowDisplayType", sIniPathName, m_nWindowType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	if (!WriteIniInt("Window", "WindowNo1DisplayType", sIniPathName, m_nWindowNo1DisplayType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	if (!WriteIniInt("Window", "WindowNo2DisplayType", sIniPathName, m_nWindowNo2DisplayType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	if (!WriteIniInt("Window", "WindowNo3DisplayType", sIniPathName, m_nWindowNo3DisplayType))
		return FALSE;	// 2007.02.13 xxxxx kuwa

	return TRUE;
}
