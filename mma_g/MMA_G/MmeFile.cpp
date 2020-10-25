//*****************************************************************************************************
//  1. ファイル名
//		MmeFile.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MME工学値変換処理クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2013.10.22 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "MmeFile.h"
#include "MMA_G.h"
#include "GlobalData.h"
#include "General.h"
#include "shlwapi.h"

// MMEのiniファイル名
#define MME_INI_FILE "mme.ini"

// バイトデータからワードデータへ変換
#define GETWORD(b1, b2) (((WORD)(b1) << 8) | (b2))
#define GETDWORD(b1, b2, b3, b4) (((((((DWORD)(b1) << 8) | (b2)) << 8) | (b3)) << 8) | (b4))

// パケットデータサイズ
#define PACKET_TIME_SIZE	8
#define USER_HEADER_SIZE	16
#define ACC_DATA_SIZE		20

// 最大ファイルサイズ(1.2GB -> 5.0GB)
//#define MAX_DATA_SIZE	(ULONGLONG)(1.2 * 1024 * 1024 * 1024)
#define MAX_DATA_SIZE	(ULONGLONG)(5.0 * 1024 * 1024 * 1024)	// 2014/04/14 modified by y.GOTO

CMmeFile::CMmeFile(void)
{
	memset(m_nSamplingRate, 0, sizeof(m_nSamplingRate));
	m_bOverWrite = FALSE;
}

CMmeFile::~CMmeFile(void)
{
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::CheckDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データフォルダの中をチェックする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pDataFolder		[I] データフォルダパス名
//		CTime		&oStartTime		[O] 計測開始時刻
//		CTime		&oEndTime		[O] 計測終了時刻
//		int			&nMeasTime		[O] 計測秒数
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:エラー
//*****************************************************************************************************
BOOL CMmeFile::CheckDataFile(LPCTSTR pDataFolder, CTime &oStartTime, CTime &oEndTime, int &nMeasTime)
{
	m_sDataFolder = pDataFolder;

	// データファイルパス取得
	CStringArray saDataFiles;
	GetDataFiles(saDataFiles);

	// .sファイル存在チェック
	if (saDataFiles.GetCount() == 0) {
		CGeneral::Alert("ダウンリンクファイルが存在しません。");
		return FALSE;
	}

	// データサイズチェック
	if (GetTotalFileSize(saDataFiles) > MAX_DATA_SIZE) {
		CGeneral::Alert("ファイルサイズが制限を超えています。");
		return FALSE;
	}

	// タイムスタンプ情報読み込み
	TimestampArray oTimestampArray;
	DataBlock oDataBlock;
	{
		CWaitCursor cursor;

		// タイムスタンプ取得
		m_nPacketCount = 0;
		GetTimestamp(saDataFiles, oTimestampArray, NULL);

		// データブロック取得
		GetDataBlock(oTimestampArray, oDataBlock);
	}

	// 変換済みファイル存在チェック
	m_bOverWrite = CheckExistFile(oDataBlock);

	// 開始時刻、終了時刻取得
	UINT nStartTime = UINT_MAX;
	UINT nEndTime = 0;
	for (int nRange = 0; nRange < N_RANGE; nRange++) {
		if (oTimestampArray[0][nRange].GetCount() != 0) {
			nStartTime = min(nStartTime, oTimestampArray[0][nRange][0]);
			nEndTime = max(nEndTime, oTimestampArray[0][nRange][oTimestampArray[0][nRange].GetCount() - 1]);
		}
	}
	if (nStartTime == UINT_MAX || nEndTime == 0) {
		CGeneral::Alert("ファイルの中に有効なデータが存在しないか、またはデータが不正です。");
		return FALSE;

	}
	oStartTime = GetTime(nStartTime);
	oEndTime = GetTime(nEndTime);

	// 計測時間取得
	nMeasTime = 0;
	for (int nRange = 0; nRange < N_RANGE; nRange++) {
		nMeasTime += (UINT)oTimestampArray[0][nRange].GetCount();
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetDataFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		拡張子が.sのファイル名を取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CStringArray	&saDataFiles		[O] 見つかったファイルのパス名の配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::GetDataFiles(CStringArray &saDataFiles)
{
	// .sファイルを検索するパスを取得
	CString sSearchPath;
	sSearchPath.Format("%s\\*.s", m_sDataFolder);

	// .sファイルを検索
	CFileFind oFileFind;
	if (oFileFind.FindFile(sSearchPath)) {
		BOOL bFndEnd = TRUE;
		while (bFndEnd) {
			bFndEnd = oFileFind.FindNextFile();

			if (!oFileFind.IsDirectory())
				saDataFiles.Add(oFileFind.GetFilePath());
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetTotalFileSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データファイルのサイズの合計を取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CStringArray	&saDataFiles		[I] データファイルのパス名の配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		ULONGLONG		ファイルサイズの合計
//*****************************************************************************************************
ULONGLONG CMmeFile::GetTotalFileSize(const CStringArray &saDataFiles)
{
	CFileStatus oStatus;
	ULONGLONG nTotalFileSize = 0;
	
	for (int i = 0; i < saDataFiles.GetCount(); i++) {
		CFile::GetStatus(saDataFiles[i], oStatus);
		nTotalFileSize += oStatus.m_size;
	}

	return nTotalFileSize;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::CheckExistFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換後のファイルが既に存在するかチェックする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		DataBlock	&oDataBlock		[I] データブロック情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:存在する　FALSE:存在しない
//*****************************************************************************************************
BOOL CMmeFile::CheckExistFile(const DataBlock &oDataBlock)
{
	CString sFolderPath;

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				const STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
				GetOutputFolderName(nUnit, nRange, oTimestampBlock.nStartTimestamp, oTimestampBlock.nEndTimestamp, sFolderPath);
				if (::PathFileExists(sFolderPath))
					return TRUE;
			}
		}
	}

	return FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ConvDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MMEデータファイルを工学値変換する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止またはエラー
//*****************************************************************************************************
BOOL CMmeFile::ConvDataFile()
{
	CProgressBar cProgressBar;

	return cProgressBar.ExecProcess("工学値変換処理中", 100, this, ConvDataThread);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ConvDataThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換処理スレッドの入り口
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID			pParam			[I] CMmeFileクラスへのポインタ（thisポインタ）
//		CProgressBar	*pProgressBar	[I] プログレスバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止またはエラー
//*****************************************************************************************************
BOOL CMmeFile::ConvDataThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CMmeFile *)pParam)->ConvDataThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ConvDataThread2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換処理スレッドの本体
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CProgressBar	*pProgressBar	[I] プログレスバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止またはエラー
//*****************************************************************************************************
BOOL CMmeFile::ConvDataThread2(CProgressBar *pProgressBar)
{
	// データファイルパス取得
	CStringArray saDataFiles;
	GetDataFiles(saDataFiles);	

	// タイムスタンプ取得
	TimestampArray oTimestampArray;
	if (GetTimestamp(saDataFiles, oTimestampArray, pProgressBar) == 0)
		return FALSE;

	// データブロック取得
	DataBlock oDataBlock;
	GetDataBlock(oTimestampArray, oDataBlock);

	// バッファ確保
	AllocateBuf(oDataBlock);

	// データ読み込み
	if (!ReadData(oDataBlock, pProgressBar))
		return FALSE;

	// データベース書き込み
	if (!WriteDatabase(oDataBlock, pProgressBar))
		return FALSE;

	m_bOverWrite = CheckExistFile(oDataBlock);

	pProgressBar->SetProgress(100);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ReadIniFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		mme.iniファイルを読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:mme.iniファイルが無い
//*****************************************************************************************************
BOOL CMmeFile::ReadIniFile()
{
	static const char *unit[N_UNIT] = {"ACC_SCALE_FACTOR_UNIT1", "ACC_SCALE_FACTOR_UNIT2", "ACC_SCALE_FACTOR_UNIT3"};
	static const char *axis[N_AXIS] = {"X", "Y", "Z"};
	static const char *range[N_RANGE] = {"Lo", "Hi", "Ex"};
	CString sKey;
	char buf[256];

	// iniファイルパスを取得
	CString sIniFileName;
	sIniFileName.Format("%s\\%s", gExePath, MME_INI_FILE);

	// ファイルが存在しなければエラー
	if (!::PathFileExists(sIniFileName)) {
		CGeneral::Alert("%sが存在しません。", MME_INI_FILE);
		return FALSE;
	}

	// ユニットの数だけループ
	for (int i = 0; i < N_UNIT; i++) {
		SAccFactor::SUnit *pUnit = &m_oAccFactor.unit[i];

		// 座標(XYZ)の数だけループ
		for (int j = 0; j < N_AXIS; j++) {
			SAccFactor::SUnit::SAxis *pAxis = &pUnit->axis[j];

			// 計測レンジの数だけループ
			for (int k = 0; k < N_RANGE; k++) {
				// iniファイルのキー
				sKey.Format("%s-%s", axis[j], range[k]);

				// iniファイルから読み込む
				GetPrivateProfileString(unit[i], sKey, "0", buf, sizeof(buf), sIniFileName);
				pAxis->range[k] = atof(buf);
			}
		}
	}

	// 閏秒の取得
	for (int i = 1; ; i++) {
		int nYear, nMonth, nDay;
		sKey.Format("DATE%d", i);
		GetPrivateProfileString("LEAP_SECOND", sKey, "", buf, sizeof(buf), sIniFileName);
		if (sscanf_s(buf, "%d/%d/%d", &nYear, &nMonth, &nDay) == 3)
			m_oLeapSecond.Add(CTime(nYear, nMonth, nDay, 8, 59, 59));
		else
			break;
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ReadSensorInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		mme.iniファイルからセンサー情報を読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::ReadSensorInfo()
{
	static const char *sensor[N_UNIT] = {"MME-S1", "MME-S2", "MME-S3"};
	char buf[256];

	// iniファイルパスを取得
	CString sIniFileName;
	sIniFileName.Format("%s\\%s", gExePath, MME_INI_FILE);

	g_pSensorInfoMme = new SENSOR_INFO[N_UNIT];
	memset(g_pSensorInfoMme, 0, sizeof(SENSOR_INFO) * N_UNIT);

	// センサー座標データの取得
	for (int i = 0; i < N_UNIT; i++) {
		GetPrivateProfileString(sensor[i], "X", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].x = atof(buf);

		GetPrivateProfileString(sensor[i], "Y", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].y = atof(buf);

		GetPrivateProfileString(sensor[i], "Z", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].z = atof(buf);

		GetPrivateProfileString(sensor[i], "Roll", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].roll = atof(buf);

		GetPrivateProfileString(sensor[i], "Pitch", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].pitch = atof(buf);

		GetPrivateProfileString(sensor[i], "Yaw", "0", buf, sizeof(buf), sIniFileName);
		g_pSensorInfoMme[i].yaw = atof(buf);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ReadDataPacket
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		.sファイルから１つのデータパケットを読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CFile		&oFile			[I] .sファイル
//		SUserHeader	*pUserHeader	[O] ユーザヘッダバッファへのポインタ
//		void		*pDataBuf		[O] データバッファへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:ファイルの終端
//*****************************************************************************************************
BOOL CMmeFile::ReadDataPacket(CFile &oFile, SUserHeader *pUserHeader, void *pDataBuf)
{
	struct {
		char time[PACKET_TIME_SIZE];
		BYTE header[USER_HEADER_SIZE];
	} buf;
	UINT nDataNum;
	UINT nReadSize;
	int nHour, nMinute, nSecond;

	// .sファイルの中をスキャンする
	BOOL bLoop = TRUE;
	while (bLoop) {
		// 受信時刻と受信パケットのヘッダを読み込む
		if (oFile.Read(&buf, sizeof(buf)) != sizeof(buf))
			return FALSE;

		// データ受信時刻フォーマットチェック
		if (sscanf_s(buf.time, "%2d:%2d:%2d", &nHour, &nMinute, &nSecond) != 3)
			return FALSE;

		// ヘッダの内容を取り出す
		pUserHeader->nPayloadId = buf.header[0];
		pUserHeader->nDataId = buf.header[1];
		pUserHeader->nFrameId = buf.header[2] >> 1;
		pUserHeader->nEndSegment = buf.header[2] & 0x01;
		pUserHeader->nSegmentCount = buf.header[3];
		pUserHeader->nTimestamp = GETDWORD(buf.header[4], buf.header[5], buf.header[6], buf.header[7]);

		// データ識別ID
		if (pUserHeader->nDataId == 0x80) {
			// フレーム構造識別ID
			switch (pUserHeader->nFrameId) {
			case 0x01:	// 通常シーケンスLoレンジ計測モード
			case 0x02:	// 通常シーケンスHiレンジ計測モード
			case 0x03:	// 通常シーケンス拡張レンジ計測モード
			case 0x11:	// 連続シーケンスLoレンジ計測モード
			case 0x12:	// 連続シーケンスHiレンジ計測モード
			case 0x13:	// 連続シーケンス拡張レンジ計測モード
				// テレメトリパケットの内容を取り出す
				pUserHeader->TelemetryPacket.nMeasurementMode = (buf.header[10] >> 6) & 0x03;
				pUserHeader->TelemetryPacket.nMeasurementRange = (buf.header[10] >> 4) & 0x03;
				pUserHeader->TelemetryPacket.nSamplingRate = GETWORD(buf.header[10] & 0x0f, buf.header[11]);
				pUserHeader->TelemetryPacket.nDataNum = GETWORD(buf.header[12], buf.header[13]);
				pUserHeader->TelemetryPacket.nUnitRange[UNIT_1] = (buf.header[14] >> 3) & 0x03;
				pUserHeader->TelemetryPacket.nUnitRange[UNIT_2] = (buf.header[15] >> 6) & 0x03;
				pUserHeader->TelemetryPacket.nUnitRange[UNIT_3] = (buf.header[15] >> 1) & 0x03;

				// アナログモニタをスキップ
				if (pUserHeader->nSegmentCount == 0)
					oFile.Seek(48, CFile::current);

				// データバッファが指定されていればそこに読み込む。指定されていなければ読み飛ばす
				nReadSize = pUserHeader->TelemetryPacket.nDataNum * ACC_DATA_SIZE;
				if (pDataBuf != NULL)
					oFile.Read(pDataBuf, nReadSize);
				else
					oFile.Seek(nReadSize, CFile::current);

				bLoop = FALSE;
				break;
			case 0x04:	// MMEステータスパケット
			case 0x05:	// エラーステータスパケット
			case 0x06:	// メッセージパケット
				// データ部分を読み飛ばす
				nDataNum = GETWORD(buf.header[12], buf.header[13]);
				oFile.Seek(nDataNum, CFile::current);
				break;
			}
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetTimestamp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		.sファイルからタイムスタンプ情報を読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CStringArray	&saDataFiles		[I] データファイルのパス名の配列
//		TimestampArray	&oTimestampArray	[O] タイムスタンプ情報
//		CProgressBar	*pProgressBar		[I] プログレスバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止
//*****************************************************************************************************
BOOL CMmeFile::GetTimestamp(const CStringArray &saDataFiles, TimestampArray &oTimestampArray, CProgressBar *pProgressBar)
{
	CFile oFile;
	SUserHeader oUserHeader;
	TimestampMap oTimestampMap;
	UINT nTimestamp;
	UINT nPacketCount = 0;

	for (int i = 0; i < saDataFiles.GetCount(); i++) {
		if (oFile.Open(saDataFiles[i], CFile::modeRead | CFile::shareDenyWrite)) {
			while (true) {
				if (!ReadDataPacket(oFile, &oUserHeader, NULL))
					break;

				for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
					int nRange = -1;
					switch (oUserHeader.TelemetryPacket.nUnitRange[nUnit]) {
					case 0x01:	// Loレンジ計測データ
						nRange = RANGE_LO;
						break;
					case 0x02:	// Hiレンジ計測データ
						nRange = RANGE_HI;
						break;
					case 0x03:	// 拡張レンジ計測データ
						nRange = RANGE_EX;
						break;
					}
					if (nRange != -1) {
						// Mapに追加
						if (!oTimestampMap[nUnit][nRange].Lookup(oUserHeader.nTimestamp, nTimestamp))
							oTimestampMap[nUnit][nRange].SetAt(oUserHeader.nTimestamp, oUserHeader.nTimestamp);

						// サンプリングレート保存
						m_nSamplingRate[nRange] = oUserHeader.TelemetryPacket.nSamplingRate;
					}
				}

				// プログレスバー表示
				if (pProgressBar != NULL) {
					if (!pProgressBar->SetProgress((int)(10 * ++nPacketCount / m_nPacketCount)))
						return FALSE;
				} else
					m_nPacketCount++;
			}

			oFile.Close();
		}
	}

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++)
			GetTimestampArray(oTimestampMap[nUnit][nRange], oTimestampArray[nUnit][nRange]);
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetTimestampArray
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		CMapTimestampからCArrayTimestampに変換してソートする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CMapTimestamp	&oMapTimestamp		[I] プログレスバー
//		TimestampArray	&oArrayTimestamp	[O] タイムスタンプ配列
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::GetTimestampArray(const CMapTimestamp &oMapTimestamp, CArrayTimestamp &oArrayTimestamp)
{
	UINT nKey;
	UINT nVal;

	// MapからArrayへコピー
	POSITION pos = oMapTimestamp.GetStartPosition();
	while (pos != NULL) {
		oMapTimestamp.GetNextAssoc(pos, nKey, nVal);
		oArrayTimestamp.Add(nVal);
	}

	// タイムスタンプをソート
	if (oArrayTimestamp.GetCount() != 0)
		qsort(oArrayTimestamp.GetData(), oArrayTimestamp.GetCount(), sizeof(UINT), CompareTimestamp);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::CompareTimestamp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイムスタンプソート用の比較関数
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		void	*p1			[I] タイムスタンプ1へのポインタ
//		void	*p2			[I] タイムスタンプ2へのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		1:タイムスタンプ1の方が大きい　-1:タイムスタンプ2の方が大きい　0:等しい
//*****************************************************************************************************
int CMmeFile::CompareTimestamp(const void *p1, const void *p2)
{
	if (*(UINT *)p1 > *(UINT *)p2)
		return 1;
	else if (*(UINT *)p1 < *(UINT *)p2)
		return -1;
	else
		return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetDataBlock
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		連続したタイムスタンプ毎にブロック化する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		TimestampArray	&oTimestampArray	[I] タイムスタンプ情報の配列
//		DataBlock		&oDataBlock			[O] データブロック情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::GetDataBlock(const TimestampArray &oTimestampArray, DataBlock &oDataBlock)
{
	// 連続したタイムスタンプのデータをまとめる
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			UINT nCurrenttimestamp = 0;

			for (int i = 0; i < oTimestampArray[nUnit][nRange].GetCount(); i++) {
				UINT nTimestamp = oTimestampArray[nUnit][nRange][i];

				if (nTimestamp > nCurrenttimestamp + 1) {
					STimestampBlock oTimestampBlock;
					oTimestampBlock.nStartTimestamp = nTimestamp;
					oTimestampBlock.nEndTimestamp = nTimestamp;
					oTimestampBlock.nLastSeqCount = 0;
					oDataBlock[nUnit][nRange].Add(oTimestampBlock);
				}

				if (oDataBlock[nUnit][nRange].GetCount() != 0)
					oDataBlock[nUnit][nRange][oDataBlock[nUnit][nRange].GetCount() - 1].nEndTimestamp = nTimestamp;

				nCurrenttimestamp = nTimestamp;
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::AllocateBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ブロック情報のデータバッファを確保する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		DataBlock		&oDataBlock			[O] データブロック情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::AllocateBuf(DataBlock &oDataBlock)
{
	// データバッファ確保
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
				UINT nBufSize = (oTimestampBlock.nEndTimestamp - oTimestampBlock.nStartTimestamp + 1) * m_nSamplingRate[nRange];
				oTimestampBlock.pAccData = new SAccData[nBufSize];
				memset(oTimestampBlock.pAccData, 0, nBufSize * sizeof(SAccData));
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ReadData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		.sファイルからブロック情報にデータを読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		DataBlock		&oDataBlock			[O] データブロック情報
//		CProgressBar	*pProgressBar		[I] プログレスバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
BOOL CMmeFile::ReadData(DataBlock &oDataBlock, CProgressBar *pProgressBar)
{
	static const int nValidFlag[N_UNIT] = {0x10, 0x20, 0x40};

	CStringArray saDataFiles;
	GetDataFiles(saDataFiles);

	UINT nPacketCount = 0;
	CFile oFile;
	SUserHeader oUserHeader;
	BYTE *pBuf = new BYTE[65535 * ACC_DATA_SIZE];
	for (int i = 0; i < saDataFiles.GetCount(); i++) {
		if (oFile.Open(saDataFiles[i], CFile::modeRead | CFile::shareDenyWrite)) {
			while (true) {
				if (!ReadDataPacket(oFile, &oUserHeader, pBuf))
					break;

				for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
					int nRange = -1;
					switch (oUserHeader.TelemetryPacket.nUnitRange[nUnit]) {
					case 0x01:	// Loレンジ計測データ
						nRange = RANGE_LO;
						break;
					case 0x02:	// Hiレンジ計測データ
						nRange = RANGE_HI;
						break;
					case 0x03:	// 拡張レンジ計測データ
						nRange = RANGE_EX;
						break;
					}

					if (nRange != -1) {
						for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
							STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
							if (oUserHeader.nTimestamp >= oTimestampBlock.nStartTimestamp
									&& oUserHeader.nTimestamp <= oTimestampBlock.nEndTimestamp) {
								BYTE *pBuf2 = pBuf;
								for (UINT j = 0; j < oUserHeader.TelemetryPacket.nDataNum; j++) {
									UINT nSeqCount = GETWORD(pBuf2[0] & 0x0f, pBuf2[1]);
									if (nSeqCount > 0 && nSeqCount <= m_nSamplingRate[nRange]) {
										UINT nOffset = (oUserHeader.nTimestamp - oTimestampBlock.nStartTimestamp) * m_nSamplingRate[nRange] + (nSeqCount - 1);

										// 不整合フラグ取得
										oTimestampBlock.pAccData[nOffset].nValidFlag = pBuf2[0] & nValidFlag[nUnit];

										// 加速度データ取得
										for (int nAxis = 0; nAxis < N_AXIS; nAxis++) {
											UINT nIndex = 2 + nUnit * 6 + nAxis * 2;
											oTimestampBlock.pAccData[nOffset].nAccData[nAxis] = GETWORD(pBuf2[nIndex], pBuf2[nIndex + 1]);
										}

										pBuf2 += ACC_DATA_SIZE;

										if (oUserHeader.nTimestamp == oTimestampBlock.nEndTimestamp)
											oTimestampBlock.nLastSeqCount = max(oTimestampBlock.nLastSeqCount, nSeqCount);
									}
								}
								break;
							}
						}
					}
				}

				// プログレスバー表示
				if (pProgressBar != NULL && !pProgressBar->SetProgress(10 + (int)(20 * ++nPacketCount / m_nPacketCount))) {
					delete [] pBuf;
					return FALSE;
				}
			}

			oFile.Close();
		}
	}

	delete [] pBuf;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::WriteDatabase
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換を行い、データベースフォルダにファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		DataBlock		&oDataBlock			[O] データブロック情報
//		CProgressBar	*pProgressBar		[I] プログレスバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止
//*****************************************************************************************************
BOOL CMmeFile::WriteDatabase(DataBlock &oDataBlock, CProgressBar *pProgressBar)
{
	CString sMeasTime;
	CString sFolderPath;
	CString sFilePath;
	CString sFileName;
	CStringArray saDataFilePath;
	INT64 nDataSize = 0;

	// プログレスバー表示用に全データ数取得
	m_nTotalDataCount = 0;
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];
				m_nTotalDataCount += (oTimestampBlock.nEndTimestamp - oTimestampBlock.nStartTimestamp) * m_nSamplingRate[nRange] + oTimestampBlock.nLastSeqCount;
			}
		}
	}

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int i = 0; i < oDataBlock[nUnit][nRange].GetCount(); i++) {
				STimestampBlock &oTimestampBlock = oDataBlock[nUnit][nRange][i];

				// 出力フォルダ名
				GetOutputFolderName(nUnit, nRange, oTimestampBlock.nStartTimestamp, oTimestampBlock.nEndTimestamp, sFolderPath);

				// ディレクトリ作成
				CGeneral::CreateDirectoryAll(sFolderPath);

				// Idbファイル設定
				SetIdbFile(sFolderPath, oTimestampBlock, nUnit, nRange);

				// 不整合データを置換
				UINT nDataCount = (oTimestampBlock.nEndTimestamp - oTimestampBlock.nStartTimestamp) * m_nSamplingRate[nRange] + oTimestampBlock.nLastSeqCount;
				ReplaceInvalidData(oTimestampBlock, nDataCount);

				// １時間毎に工学値変換ファイルを書き込む
				UINT n1HourDataSize = m_nSamplingRate[nRange] * 3600;
				UINT nDataTime = oTimestampBlock.nStartTimestamp;
				for (UINT nDataOffset = 0; nDataOffset < nDataCount; nDataOffset += n1HourDataSize) {
					UINT nWriteDataSize = min(n1HourDataSize, nDataCount - nDataOffset);

					// 出力ファイル名取得
					CTime oDataStartTime = GetTime(nDataTime);
					CTime oDataEndTime = GetTime(nDataTime + nWriteDataSize / m_nSamplingRate[nRange]);
					sFileName.Format("%s%c%s",
							oDataStartTime.FormatGmt("%Y_%m_%d_%H_%M_%S.000"),
							nDataOffset == 0 ? '-' : '+',
							oDataEndTime.FormatGmt("%Y_%m_%d_%H_%M_%S.000"));
					sFilePath.Format("%s\\%s", sFolderPath, sFileName);

					// データファイル書き込み
					if (!WriteDataFile(sFilePath, oTimestampBlock, nDataOffset, nWriteDataSize, nUnit, nRange, pProgressBar, nDataSize))
						return FALSE;

					// XMLファイル書き込み
					WriteXmlFile(sFileName, oDataStartTime);

					// データファイル名保存
					m_oIdbFile.AddFile(sFileName);

					// １時間（3600秒）加算
					nDataTime += 3600;
				}

				// idbファイル書き込み
				WriteIdbFile(oTimestampBlock);
			}
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetOutputFolderName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		出力フォルダパスを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int			nUnit				[I] ユニット番号
//		int			nRange				[I] レンジ番号
//		UINT		nStartTimestamp		[I] 開始タイムスタンプ
//		UINT		nEndTimestamp		[I] 終了タイムスタンプ
//		CString		&sFolderPath		[O] 出力フォルダパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	無し
//*****************************************************************************************************
void CMmeFile::GetOutputFolderName(int nUnit, int nRange, UINT nStartTimestamp, UINT nEndTimestamp, CString &sFolderPath)
{
	static const char *sUnit[N_UNIT] = {"Unit1", "Unit2", "Unit3"};
	static const char *sRange[N_RANGE] = {"Lo", "Hi", "Ex"};

	CTime oMeasStartTime = GetTime(nStartTimestamp);
	CTime oMeasEndTime = GetTime(nEndTimestamp);

	sFolderPath.Format("%s\\MME\\%s\\%s_%s_%s",
			gRootData,
			sUnit[nUnit],
			oMeasStartTime.FormatGmt("%Y_%m_%d_-%H_%M_%S_000"),
			oMeasEndTime.FormatGmt("%Y%m%d%H%M%S"),
			sRange[nRange]);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::SetIdbFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイルに情報をセットする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sFolderPath		[I] 出力フォルダパス
//		STimestampBlock	&oTimestampBlock	[I] タイムスタンプブロック情報
//		int				nUnit				[I] ユニット番号
//		int				nRange				[I] レンジ番号
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::SetIdbFile(const CString &sFolderPath, const STimestampBlock &oTimestampBlock, int nUnit, int nRange)
{
	CString sBuf;

	CTime oStartTime = GetTime(oTimestampBlock.nStartTimestamp);
	CTime oEndTime = GetTime(oTimestampBlock.nEndTimestamp);

	struct tm tm;
	oStartTime.GetGmtTm(&tm);

	// データ消去
	m_oIdbFile.DelAll();

	// センサーIDX
	m_oIdbFile.SetSensorIdx(nUnit);

	// コメント
	m_oIdbFile.SetComment(m_sComment);

	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	m_oIdbFile.SetStartTime(1900 + tm.tm_year, 1 + tm.tm_mon, tm.tm_mday, (((tm.tm_hour * 60) + tm.tm_min) * 60 + tm.tm_sec) * 1000);

	// サンプルレート
	m_oIdbFile.SetSampleRate((double)m_nSamplingRate[nRange]);

	// 期間
	sBuf.Format("%s-%s", oStartTime.FormatGmt("%Y%m%d%H%M%S"), oEndTime.FormatGmt("%Y%m%d%H%M%S"));
	m_oIdbFile.SetPeriod(sBuf);

	// ダウンリンク日
	m_oIdbFile.SetDownlink(m_sDownlink);

	// センサーID
	m_oIdbFile.SetSensorId(nUnit + 1);

	// タイムゼロ
	m_oIdbFile.SetTimeZero(oStartTime.FormatGmt("%Y%m%d%H%M%S"));

	// Sensor Co Sys
	sBuf.Format("MME-S%d", nUnit + 1);
	m_oIdbFile.SetSensorcosys(sBuf);

	// Data Co Sys
	sBuf.Format("MME-S%d", nUnit + 1);
	m_oIdbFile.SetDatacosys(sBuf);

	// 保存パス名
	m_oIdbFile.SetPath(sFolderPath);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::ReplaceInvalidData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		不整合データを置換する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		STimestampBlock	&oTimestampBlock	[O] タイムスタンプブロック情報
//		UINT			nDataCount			[I] データ数
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::ReplaceInvalidData(STimestampBlock &oTimestampBlock, UINT nDataCount)
{
	short nReplaceData[N_AXIS];
	UINT i, j;
	int nAxis;

	// 置換データ初期化
	for (nAxis = 0; nAxis < N_AXIS; nAxis++)
		nReplaceData[nAxis] = 0;

	// 不整合データを置換する
	for (i = 0; i < nDataCount; i++) {
		if (!oTimestampBlock.pAccData[i].nValidFlag) {
			// 次の有効データを探す
			if (i == 0 || oTimestampBlock.pAccData[i - 1].nValidFlag) {
				for (j = i; j < nDataCount; j++) {
					if (oTimestampBlock.pAccData[j].nValidFlag)
						break;
				}

				if (i == 0) {
					if (j < nDataCount) {
						// 先頭に不整合データがある場合は最初の有効データを使う
						for (nAxis = 0; nAxis < N_AXIS; nAxis++)
							nReplaceData[nAxis] = oTimestampBlock.pAccData[j].nAccData[nAxis];
					}
				} else {
					if (j < nDataCount) {
						// 中間に不整合データがある場合は前後の有効なデータの平均値を使う
						for (nAxis = 0; nAxis < N_AXIS; nAxis++)
							nReplaceData[nAxis] = (oTimestampBlock.pAccData[i - 1].nAccData[nAxis] + oTimestampBlock.pAccData[j].nAccData[nAxis]) / 2;
					} else {
						// 末尾に不整合データがある場合は最後の有効データを使う
						for (nAxis = 0; nAxis < N_AXIS; nAxis++)
							nReplaceData[nAxis] = oTimestampBlock.pAccData[i - 1].nAccData[nAxis];
					}
				}
			}

			// 不整合データを置換
			for (nAxis = 0; nAxis < N_AXIS; nAxis++)
				oTimestampBlock.pAccData[i].nAccData[nAxis] = nReplaceData[nAxis];
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::WriteDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換ファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sFilePath			[I] 出力ファイルパス
//		STimestampBlock	&oTimestampBlock	[I] タイムスタンプブロック情報
//		UINT			nDataOffset			[I] データバッファオフセット
//		UINT			nWriteDataSize		[I] 書き込みデータ数
//		int				nUnit				[I] ユニット番号
//		int				nRange				[I] レンジ番号
//		CProgressBar	*pProgressBar		[I] プログレスバー
//		INT64			nDataSize			[I/O] 処理済みデータカウント
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止
//*****************************************************************************************************
BOOL CMmeFile::WriteDataFile(const CString &sFilePath, const STimestampBlock &oTimestampBlock, UINT nDataOffset, UINT nWriteDataSize, int nUnit, int nRange, CProgressBar *pProgressBar, INT64 &nDataSize)
{
	// 工学値変換ファイルのヘッダ
	struct {
		INT32 nSeqNo;
		INT32 nDataSize;
		INT32 nDataSizeHour;
		char sFiller[4];
	} oHeader;

	CFile oFile;
	if (oFile.Open(sFilePath, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		// ヘッダ書き込み
		memset(&oHeader, 0, sizeof(oHeader));
		oHeader.nSeqNo = nDataOffset / m_nSamplingRate[nRange] + 1;
		oHeader.nDataSize = nWriteDataSize;
		oHeader.nDataSizeHour = m_nSamplingRate[nRange] * 3600;
		oFile.Write(&oHeader, sizeof(oHeader));

		// データ書き込み
		UINT nDataCount = 0;
		double aWriteBuf[100][7];
		memset(aWriteBuf, 0, sizeof(aWriteBuf));
		while (nDataCount < nWriteDataSize) {
			int nBufSize = min(100, nWriteDataSize - nDataCount);
			for (int i = 0; i < nBufSize; i++) {
				// 工学値変換
				for (int nAxis = 0; nAxis < N_AXIS; nAxis++)
					aWriteBuf[i][4 + nAxis] = oTimestampBlock.pAccData[nDataOffset + nDataCount].nAccData[nAxis] * m_oAccFactor.unit[nUnit].axis[nAxis].range[nRange] * 1000000;

				nDataCount++;
			}

			// プログレスバー表示
			nDataSize += nBufSize;
			if (pProgressBar != NULL && !pProgressBar->SetProgress(30 + (int)((INT64)70 * nDataSize / m_nTotalDataCount)))
				return FALSE;

			// ファイルに書き込み
			oFile.Write(aWriteBuf, 7 * nBufSize * sizeof(double));
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::WriteXmlFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		XMLファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		&sFileName				[I] 出力ファイル名
//		CTime		&oDataStartTime			[I] データの開始時刻
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::WriteXmlFile(const CString &sFileName, const CTime &oDataStartTime)
{
	// 計測開始時刻を編集
	m_oIdbFile.SetTimeZeroXML(oDataStartTime.FormatGmt("%Y_%m_%d_%H_%M_%S.000"));

	// XMLファイルを書き込み
	m_oIdbFile.WriteXml(sFileName, DATA_TYPE_MME);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::WriteIdbFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CTime		&oTimestampBlock		[I] タイムスタンプブロック情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeFile::WriteIdbFile(const STimestampBlock &oTimestampBlock)
{
	CTime oStartTime = GetTime(oTimestampBlock.nStartTimestamp);

	struct tm tm;
	oStartTime.GetGmtTm(&tm);

	// ファイル名を編集
	CString sFileName;
	sFileName.Format("%04d%02d%02d%d", 1900 + tm.tm_year, 1 + tm.tm_mon, tm.tm_mday, (((tm.tm_hour * 60) + tm.tm_min) * 60 + tm.tm_sec) * 1000);

	// idbファイルに書き込み
	m_oIdbFile.Write(sFileName);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeFile::GetTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		タイムスタンプを日時に変換する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT		nTimestamp		[I] タイムスタンプ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CTime		日時
//*****************************************************************************************************
CTime CMmeFile::GetTime(UINT nTimestamp)
{
	// 日時に変換
	CTime oTime(nTimestamp + 315964800 - 16);

	// 閏秒の補正
	for (int i = 0; i < m_oLeapSecond.GetCount(); i++) {
		if (m_oLeapSecond[i] < oTime)
			oTime -= 1;
		else
			break;
	}

	return oTime;
}
