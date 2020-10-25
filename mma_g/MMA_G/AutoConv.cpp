//*****************************************************************************************************
//  1. ファイル名
//		AutoConv.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動工学値変換クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2013.01.31 S.Aizawa 新規作成
//*****************************************************************************************************

#include "stdafx.h"
#include "AutoConv.h"
#include "MMA_G.h"
#include "GlobalData.h"
#include "Complement.h"
#include "General.h"
#include "GraphFrm.h"
#include "Define.h"

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::CAutoConv
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		コンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CAutoConv::CAutoConv()
{
	// 自動工学値変換フォルダパス設定チェック
	if (gAutoConvPath[0] == '\0')
		return;

	m_bExit = FALSE;
	m_hMainWnd = NULL;
	m_hThread = AfxBeginThread(AutoConvertThreadEntry, this, THREAD_PRIORITY_LOWEST)->m_hThread;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::~CAutoConv
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		デストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CAutoConv::~CAutoConv()
{
	// ワーカスレッドスレッドの終了を待つ
	m_bExit = TRUE;
	m_oEvent.SetEvent();
	::WaitForSingleObject(m_hThread, 1000);
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::AutoConvertThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動工学値変換チェックスレッドエントリ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID		pParam			[I] スレッドに渡されたパラメータ（CAutoConvへのポインタ）
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
UINT CAutoConv::AutoConvertThreadEntry(LPVOID pParam)
{
	((CAutoConv *)pParam)->CheckAutoConvert();
	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::CheckAutoConvert
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動工学値変換チェック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAutoConv::CheckAutoConvert()
{
	// maiファイル検索パス
	CString sMaiFilePath;
	sMaiFilePath.Format("%s\\*.mai", gAutoConvPath);

	while (!m_bExit) {
		// maiファイルを検索
		CFileFind oFileFind;
		BOOL bFind = oFileFind.FindFile(sMaiFilePath);
		while (bFind) {
			bFind = oFileFind.FindNextFile();

			CMaiFile oMaiFile;

			if (GetNewestMaiFile(oMaiFile, oFileFind.GetFileName())) {
				CString sMaiPathName;
				sMaiPathName.Format("%s\\%s", gAutoConvPath, oMaiFile.m_maiFname);
				if (oMaiFile.ReadMaiFile2(sMaiPathName, FALSE)) {
					if (CheckDataFileExist(oMaiFile)) {
						if (m_hMainWnd != NULL) {
							::SendMessage(m_hMainWnd, WM_USER_AUTO_CONV, (WPARAM)&oMaiFile, (LPARAM)&sMaiPathName);
							break;
						}
					}
				}
			}
		}

		m_oEvent.Lock(1000);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::ExecAutoConvert
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		自動工学値変換実行処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMaiFile		*pMaiFile			[I] maiファイルデータ
//		CString			*pMaiFilePath		[I] maiファイルパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAutoConv::ExecAutoConvert(CMaiFile *pMaiFile, CString *pMaiFilePath)
{
	if (ConvertProc(*pMaiFile, *pMaiFilePath)) {
		MoveDataFiles(*pMaiFile, "処理済みデータ");
		((CFrameWnd *)AfxGetMainWnd())->GetActiveDocument()->UpdateAllViews(NULL);

		// 加速度グラフ表示
		CGraphFrame* pFrame = new CGraphFrame;
		if (!pFrame->DispGraph(pMaiFile->m_Idb.m_sIdbPath, GRAPH_ACCEL, DATA_TYPE_MMA))
			delete pFrame;
	} else
		MoveDataFiles(*pMaiFile, "エラーデータ");
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::GetNewestMaiFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		最新のmaiファイル取得処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMaiFile		&oMaiFile			[I] maiファイルデータ
//		CString			&sFileName			[I] maiファイル名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CAutoConv::GetNewestMaiFile(CMaiFile &oMaiFile, const CString &sFileName)
{
	// 全てのmaiファイルを取得
	GetMaiFiles(oMaiFile, sFileName);
	INT_PTR nCount = oMaiFile.m_Name.GetSize();

	// コピー中でないことを確かめるため200ms後に再度取得
	Sleep(200);
	GetMaiFiles(oMaiFile, sFileName);

	// 最新のmaiファイルを取得
	if (nCount != 0 && nCount == oMaiFile.m_Name.GetSize()) {
		CString sTemp;
		int n = 0;
		for (int i = 0; i < oMaiFile.m_Name.GetSize(); i++) {
			if (oMaiFile.m_Name.GetAt(i).Mid(30, 14) > sTemp) {
				sTemp = oMaiFile.m_Name[i].Mid(30, 14);
				n = i;
			}
		}

		// 最新のmaiファイルを保存
		oMaiFile.m_maiFname = oMaiFile.m_Name[n];
		return TRUE;
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::GetMaiFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全てのmaiファイル取得処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMaiFile		&oMaiFile			[I] maiファイルデータ
//		CString			&sFileName			[I] maiファイル名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAutoConv::GetMaiFiles(CMaiFile &oMaiFile, const CString &sFileName)
{
	CString sMaiFilePath;
	sMaiFilePath.Format("%s\\%s*.mai", gAutoConvPath, sFileName.Left(30));

	oMaiFile.m_Name.RemoveAll();
	CFileFind oFileFind;
	BOOL bFind = oFileFind.FindFile(sMaiFilePath);
	while (bFind) {
		bFind = oFileFind.FindNextFile();

		oMaiFile.m_Name.Add(oFileFind.GetFileName());
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::CheckDataFileExist
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全binファイル存在チェック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMaiFile		&oMaiFile			[I] maiファイルデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：全て存在する　FALSE：存在しないものがある
//*****************************************************************************************************
BOOL CAutoConv::CheckDataFileExist(CMaiFile &oMaiFile)
{
	if (!CheckBinFileExist(oMaiFile.m_BinName0))
		return FALSE;

	if (!CheckBinFileExist(oMaiFile.m_BinName1))
		return FALSE;

	if (!CheckBinFileExist(oMaiFile.m_BinName2))
		return FALSE;

	if (!CheckBinFileExist(oMaiFile.m_BinName3))
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::CheckBinFileExist
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		全binファイル存在チェック処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CStringArray		&aBinFiles			[I] binファイル名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：全て存在する　FALSE：存在しないものがある
//*****************************************************************************************************
BOOL CAutoConv::CheckBinFileExist(const CStringArray &aBinFiles)
{
	CFile file;
	CString sBinPath;

	for (int i = 0; i < aBinFiles.GetCount(); i++) {
		sBinPath.Format("%s\\%s", gAutoConvPath, aBinFiles[i]);
		if (!file.Open(sBinPath, CFile::modeRead | CFile::shareDenyWrite))
			return FALSE;

		file.Close();
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::ConvertProc
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMaiFile		&oMaiFile			[I] maiファイルデータ
//		CString			&sMaiPathName		[I] maiファイルパス名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CAutoConv::ConvertProc(CMaiFile &oMaiFile, const CString &sMaiPathName)
{
	// maiファイルから各項目を取得
	if (!oMaiFile.GetItems())
		return FALSE;

	int nSensorIndex = GetSensorIndex(oMaiFile.m_RsuId);
	if (nSensorIndex == -1) {
		CGeneral::Alert("rack_pos.defにRSU IDが登録されていません。");
		return FALSE;
	}

	if (g_pSensorInfo[nSensorIndex].category == NULL || g_pSensorInfo[nSensorIndex].category[0] == '\0') {
		CGeneral::Alert("rack_pos.defにカテゴリが登録されていません。");
		return FALSE;
	}
	oMaiFile.m_Idb.m_sCategory = g_pSensorInfo[nSensorIndex].category;

	if (g_pSensorInfo[nSensorIndex].measurement_kind == NULL || g_pSensorInfo[nSensorIndex].measurement_kind[0] == '\0') {
		CGeneral::Alert("rack_pos.defに計測種類が登録されていません。");
		return FALSE;
	}
	oMaiFile.m_Idb.m_sMeasurementKind = g_pSensorInfo[nSensorIndex].measurement_kind;

	CDataDateTime start, end;
	oMaiFile.GetStartEnd(&start, &end);

	CString str;
	CString	pos, period;
	pos = g_pSensorInfo[nSensorIndex].dat;
	period.Format("%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d"
		, start.m_YY
		, start.m_MM
		, start.m_DD
		, start.m_hh
		, start.m_mm
		, start.m_ss
		, end.m_YY
		, end.m_MM
		, end.m_DD
		, end.m_hh
		, end.m_mm
		, end.m_ss);
	if (oMaiFile.mkDir(pos, oMaiFile.m_Idb.m_sCategory, oMaiFile.m_Idb.m_sMeasurementKind))
		return FALSE;

	// センサーIDX
	oMaiFile.m_Idb.SetSensorIdx(nSensorIndex);
	// データ範囲
	oMaiFile.m_Idb.SetPeriod(period);
	// ネットワークID（UNIT ID上位４桁）
	oMaiFile.m_Idb.SetNetworkid(oMaiFile.m_NetworkId);
	// ユニットID（UNIT ID下位４桁）
	oMaiFile.m_Idb.SetRsuId(oMaiFile.m_RsuId);
	// センサーID
	oMaiFile.m_Idb.SetSensorId(oMaiFile.m_SensorId);
	// タイムゼロ
	str.Format("%04d%02d%02d%02d%02d%02d"
		, start.m_YY
		, start.m_MM
		, start.m_DD
		, start.m_hh
		, start.m_mm
		, start.m_ss);
	oMaiFile.m_Idb.SetTimeZero(str);
	// サンプルレート
	oMaiFile.m_Idb.SetSampleRate(oMaiFile.m_SampleRate);
	// 保存パス名
	oMaiFile.m_Idb.SetPath(oMaiFile.m_ConvDataPath);
	// Sensor Co Sys
	oMaiFile.m_Idb.SetSensorcosys(pos);
	// Data Co Sys
	oMaiFile.m_Idb.SetDatacosys(pos);
	// カテゴリ
	oMaiFile.m_Idb.SetCategory(oMaiFile.m_Idb.m_sCategory);
	// 計測種類
	oMaiFile.m_Idb.SetMeasurementKind(oMaiFile.m_Idb.m_sMeasurementKind);

	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	oMaiFile.m_Idb.SetStartTime(
		start.m_YY
		, start.m_MM
		, start.m_DD
		, (start.m_hh * 60 * 60 + start.m_mm * 60 + start.m_ss) * 1000);

	// 入力開始時間を引き渡す
	oMaiFile.m_In_startTime = start;

	// 補間処理
	Complement complement;
	if (complement.ComplementData(gAutoConvPath, oMaiFile.m_maiFname)) {
		// Data Quality Measureにメッセージ追加
		oMaiFile.m_Idb.m_dataquality = "Termperature Data Error was found and complemented.";

		// maiファイル再読み込み
		oMaiFile.ReadMaiFile2(sMaiPathName, FALSE);
	}

	// 指定時刻の出力ファイルを書き込む
	oMaiFile.WriteData(gAutoConvPath, 0, end - start);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::GetSensorIndex
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		センサーインデックス取得処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int				nRsuId				[I] RSU ID
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
int CAutoConv::GetSensorIndex(int nRsuId)
{
	if (g_pSensorInfo != NULL) {
		for (int i = 0; g_pSensorInfo[i].idx != -1; i++) {
			if (g_pSensorInfo[i].rsu_id == nRsuId) {
				if (g_pSensorInfo[i].category == NULL || g_pSensorInfo[i].category[0] == '\0') {
					CGeneral::Alert("rack_pos.defにカテゴリが登録されていません。");
					return -1;
				}

				if (g_pSensorInfo[i].measurement_kind == NULL || g_pSensorInfo[i].measurement_kind[0] == '\0') {
					CGeneral::Alert("rack_pos.defに計測種類が登録されていません。");
					return -1;
				}

				return i;
			}
		}
	}

	CGeneral::Alert("rack_pos.defにRSU IDが登録されていません。");

	return -1;
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::MoveDataFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データファイル移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMaiFile		&oMaiFile			[I] maiファイルデータ
//		CString			sFolder				[I] 移動先フォルダ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAutoConv::MoveDataFiles(const CMaiFile &oMaiFile, const CString sFolder)
{
	// ディレクトリ作成
	CString sMoveFolder;
	sMoveFolder.Format("%s\\%s", gAutoConvPath, sFolder);
	::CreateDirectory(sMoveFolder, NULL);

	// maiファイル移動
	for (int i = 0; i < oMaiFile.m_Name.GetSize(); i++) {
		CString sFromMaiPath;
		CString sToMaiPath;
		sFromMaiPath.Format("%s\\%s", gAutoConvPath, oMaiFile.m_Name[i]);
		sToMaiPath.Format("%s\\%s", sMoveFolder, oMaiFile.m_Name[i]);
		::DeleteFile(sToMaiPath);
		::MoveFile(sFromMaiPath, sToMaiPath);
	}

	// binファイル削除
	MoveBinFiles(oMaiFile.m_BinName0, sMoveFolder);
	MoveBinFiles(oMaiFile.m_BinName1, sMoveFolder);
	MoveBinFiles(oMaiFile.m_BinName2, sMoveFolder);
	MoveBinFiles(oMaiFile.m_BinName3, sMoveFolder);
}

//*****************************************************************************************************
//  1. 関数名
//		CAutoConv::MoveBinFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		binファイル移動処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CStringArray	&aBinFiles			[I] binファイル名
//		CString			&sMoveFolder		[I] 移動先フォルダ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAutoConv::MoveBinFiles(const CStringArray &aBinFiles, const CString &sMoveFolder)
{
	for (int i = 0; i < aBinFiles.GetSize(); i++) {
		// binファイル削除
		CString sFromBinPath;
		CString sToBinPath;
		sFromBinPath.Format("%s\\%s", gAutoConvPath, aBinFiles[i]);
		sToBinPath.Format("%s\\%s", sMoveFolder, aBinFiles[i]);
		::DeleteFile(sToBinPath);
		::MoveFile(sFromBinPath, sToBinPath);
	}
}
