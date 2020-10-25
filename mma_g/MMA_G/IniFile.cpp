#include "StdAfx.h"
#include "IniFile.h"
#include "GlobalData.h"
#include "General.h"

#define	ROOT_PATH		"c:\\MMA-G"
#define	DATA_PATH		"\\data"
#define	NAME_INI		"MMA_G.ini"

#define	SEC_PATH		"PATH"
#define	KEY_ROOT_PATH	"ROOT_PATH"
#define	KEY_REAL_TIME_DATA_PATH	"REALTIME_DATA_PATH"
#define	KEY_REAL_TIME_TEMP_PATH	"REALTIME_TEMP_PATH"
#define	KEY_DOWNLINK_REFERENCE_PATH	"DOWNLINK_REFERENCE_PATH"
#define KEY_AUTO_CONV_PATH "AUTO_CONV_PATH"

#define	SEC_TOOL		"TOOL"
#define	KEY_EXE_PATH	"EXE_PATH"

#define	SEC_LAUNCH		"LAUNCH"
#define	KEY_LAUNCH_TIME	"LAUNCH_TIME"

#define SEC_SETTING		"SETTING"
#define KEY_ZERO_OFFSET_SW	"ZERO_OFFSET_SW"
#define KEY_SPECTRUM_PSD_RANGE	"SPECTRUM_PSD_RANGE"
#define KEY_ZERO_OFFSET_SPAN	"ZERO_OFFSET_SPAN"
#define KEY_REALTIME_RANGE	"REALTIME_RANGE"

#define SEC_CHOICE		"CHOICE"
#define KEY_CHOICE_CATEGORY	"CATEGORY"
#define KEY_CHOICE_MEASUREMENT_KIND	"MEASUREMENT_KIND"

// iniファイルの情報を取得する
bool CIniFile::Read(void)
{
	CString	iniName;

	// 起動されたアプリケーションのフルパス取得
	SetExeDir();
	iniName = gExePath;
	iniName += '\\';
	iniName += NAME_INI;

	// データ格納フォルダを取得する
	GetPrivateProfileString(
		SEC_PATH,			// セクション名
		KEY_ROOT_PATH,		// キー名
		ROOT_PATH,			// 既存の文字列
		gRootPath,			// 情報が格納されるバッファ
		sizeof(gRootPath),	// 情報バッファのサイズ
		(LPCSTR)iniName		// .iniファイル名
	);

	// セミコロンでつなげた複数のパスを分解する
	int nPos= 0;
	CString sRootPath = gRootPath;
	CString sToken = sRootPath.Tokenize(";", nPos);
	while (sToken != "") {
		sToken = sToken.TrimRight('\\');
		if (gRootDataList.GetSize() == 0)
			strcpy_s(gRootPath, sToken);
		gRootDataList.Add(sToken + DATA_PATH);
		sToken = sRootPath.Tokenize(";", nPos);
	}

	// 工学値データ格納フォルダを編集する
	strcpy_s(gRootData, gRootPath);
	strcat_s(gRootData, DATA_PATH);

	// ツールEXEを取得する
	GetPrivateProfileString(
		SEC_TOOL,					// セクション名
		KEY_EXE_PATH,				// キー名
		"",							// 既存の文字列
		gToolExe,					// 情報が格納されるバッファ
		sizeof(gToolExe),			// 情報バッファのサイズ
		iniName						// .iniファイル名
	);

	// LaunchTimeを取得する
	GetPrivateProfileString(
		SEC_LAUNCH,					// セクション名
		KEY_LAUNCH_TIME,			// キー名
		"YYYY_MM_DD_hh_mm_ss.ttt",	// 既存の文字列
		gLaunchTime,				// 情報が格納されるバッファ
		sizeof(gLaunchTime),		// 情報バッファのサイズ
		iniName						// .iniファイル名
	);

	// リアルタイムデータパスを取得する
	GetPrivateProfileString(
		SEC_PATH,					// セクション名
		KEY_REAL_TIME_DATA_PATH,	// キー名
		"",							// 既存の文字列
		gRealtimeDataPath,			// 情報が格納されるバッファ
		sizeof(gRealtimeDataPath),	// 情報バッファのサイズ
		iniName						// .iniファイル名
	);

	// リアルタイムテンポラリパスを取得する
	GetPrivateProfileString(
		SEC_PATH,					// セクション名
		KEY_REAL_TIME_TEMP_PATH,	// キー名
		"",							// 既存の文字列
		gRealtimeTempPath,			// 情報が格納されるバッファ
		sizeof(gRealtimeTempPath),	// 情報バッファのサイズ
		iniName						// .iniファイル名
	);

	// ダウンリンクフォルダ参照パスを取得する
	GetPrivateProfileString(
		SEC_PATH,						// セクション名
		KEY_DOWNLINK_REFERENCE_PATH,	// キー名
		"",								// 既存の文字列
		gDownlinkReferencePath,			// 情報が格納されるバッファ
		sizeof(gDownlinkReferencePath),	// 情報バッファのサイズ
		iniName							// .iniファイル名
	);

	// 0点補正有無デフォルト値を取得する
	char tZeroOffsetSw[256];
	GetPrivateProfileString(
		SEC_SETTING,					// セクション名
		KEY_ZERO_OFFSET_SW,				// キー名
		"",								// 既存の文字列
		tZeroOffsetSw,					// 情報が格納されるバッファ
		sizeof(tZeroOffsetSw),			// 情報バッファのサイズ
		iniName							// .iniファイル名
	);
	gZeroOffsetSw = (_stricmp(tZeroOffsetSw, "ON") == 0);

	// スペクトラムPSD範囲デフォルト値を取得する
	char tSpectrumPsdRange[256];
	GetPrivateProfileString(
		SEC_SETTING,					// セクション名
		KEY_SPECTRUM_PSD_RANGE,			// キー名
		"0",							// 既存の文字列
		tSpectrumPsdRange,				// 情報が格納されるバッファ
		sizeof(tSpectrumPsdRange),		// 情報バッファのサイズ
		iniName							// .iniファイル名
	);
	gSpectrumPsdRange = atof(tSpectrumPsdRange);

	// 0点補正値グラフ時間間隔を取得する
	gZeroOffsetSpan = GetPrivateProfileInt(
		SEC_SETTING,					// セクション名
		KEY_ZERO_OFFSET_SPAN,			// キー名
		3600,							// 既定の数値
		iniName							// .iniファイル名
	);

	// 自動工学値変換フォルダパスを取得する
	GetPrivateProfileString(
		SEC_PATH,						// セクション名
		KEY_AUTO_CONV_PATH,				// キー名
		"",								// 既定の文字列
		gAutoConvPath,					// 情報が格納されるバッファ
		sizeof(gAutoConvPath),			// 情報バッファのサイズ
		iniName							// .iniファイル名
	);

	// 準リアルタイム表示範囲
	gRealtimeRange = GetPrivateProfileInt(
		SEC_SETTING,					// セクション名
		KEY_REALTIME_RANGE,				// キー名
		60,								// 既定の数値
		iniName							// .iniファイル名
	);

	// カテゴリ選択肢を取得する
	char tChoiceCategory[1024];
	GetPrivateProfileString(
		SEC_CHOICE,						// セクション名
		KEY_CHOICE_CATEGORY,			// キー名
		"",								// 既存の文字列
		tChoiceCategory,				// 情報が格納されるバッファ
		sizeof(tChoiceCategory),		// 情報バッファのサイズ
		iniName							// .iniファイル名
	);
	CGeneral::StrSplit(tChoiceCategory, ";", gChoiceCategory);

	// 計測種類選択肢を取得する
	char tChoiceMeasurementKind[1024];
	GetPrivateProfileString(
		SEC_CHOICE,						// セクション名
		KEY_CHOICE_MEASUREMENT_KIND,	// キー名
		"",								// 既存の文字列
		tChoiceMeasurementKind,			// 情報が格納されるバッファ
		sizeof(tChoiceMeasurementKind),	// 情報バッファのサイズ
		iniName							// .iniファイル名
	);
	CGeneral::StrSplit(tChoiceMeasurementKind, ";", gChoiceMeasurementKind);

	return true;
}

// 起動されたアプリケーションのフルパス取得
void CIniFile::SetExeDir(void)
{
	TCHAR szFull[_MAX_PATH];    // 起動されたアプリケーションのフルパス名
	TCHAR szDrive[_MAX_DRIVE];  // 起動されたアプリケーションのドライブ名
	TCHAR szDir[_MAX_DIR];      // 起動されたアプリケーションのディレクトリ名

	memset(szFull, 0, sizeof(szFull));
	memset(szDrive, 0, sizeof(szDrive));
	memset(szDir, 0, sizeof(szDir));

	// 起動されたアプリケーションのフルパス名を取得
	::GetModuleFileName(NULL, szFull, sizeof(szFull) / sizeof(TCHAR));
	// フルパス名をドライブ名やディレクトリ名部分に分解
	_splitpath_s(szFull, szDrive, sizeof(szDrive)
		, szDir, sizeof(szDir)
		, NULL, 0, NULL, 0);
	// ドライブ名とディレクトリ名部分を連結
	_makepath_s(gExePath, szDrive, szDir, NULL, NULL);
	int len;
	if ((len = (int)strlen(gExePath)) != 0) {
		if (gExePath[len - 1] == '\\')
			gExePath[len - 1] = 0;
	}
}
