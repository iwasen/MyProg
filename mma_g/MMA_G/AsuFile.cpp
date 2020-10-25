//*****************************************************************************************************
//  1. ファイル名
//		AsuFile.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ASUデータファイル読み込み処理クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2016.02.01 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "AsuFile.h"
#include "MMA_G.h"
#include "General.h"
#include "GlobalData.h"
#include "shlwapi.h"

CAsuFile::CAsuFile(void)
{
}

CAsuFile::~CAsuFile(void)
{
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::ReadSensorInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		asu.iniファイルからセンサー情報を読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAsuFile::ReadSensorInfo()
{
	static const char sensor[] = {"SENSOR_INFO"};
	char buf[256];

	// iniファイルパスを取得
	CString sIniFileName;
	sIniFileName.Format("%s\\%s", gExePath, ASU_INI_FILE);

	g_pSensorInfoAsu = new SENSOR_INFO;
	memset(g_pSensorInfoAsu, 0, sizeof(SENSOR_INFO));

	GetPrivateProfileString(sensor, "X", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->x = atof(buf);

	GetPrivateProfileString(sensor, "Y", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->y = atof(buf);

	GetPrivateProfileString(sensor, "Z", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->z = atof(buf);

	GetPrivateProfileString(sensor, "Roll", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->roll = atof(buf);

	GetPrivateProfileString(sensor, "Pitch", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->pitch = atof(buf);

	GetPrivateProfileString(sensor, "Yaw", "0", buf, sizeof(buf), sIniFileName);
	g_pSensorInfoAsu->yaw = atof(buf);
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::CheckDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データフォルダの中をチェックする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR			pDataFile		[I] データファイルパス名
//		double			&fSampleRate	[O] サンプリングレート
//		CDataDateTime	&oStartTime		[O] 計測開始時刻
//		CDataDateTime	&oEndTime		[O] 計測終了時刻
//		int				&nMeasTime		[O] 計測秒数
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:エラー
//*****************************************************************************************************
BOOL CAsuFile::CheckDataFile(LPCTSTR pDataFile, double &fSampleRate, CDataDateTime &oStartTime, CDataDateTime &oEndTime, int &nMeasTime)
{
	m_sDataFile = pDataFile;

	CStdioFile oFile;
	if (!oFile.Open(pDataFile, CFile::modeRead | CFile::shareDenyWrite, NULL)) {
		CGeneral::Alert("ASUデータファイルが開けません。");
		return FALSE;
	}

	// サンプリングレート、計測時間取得のためファイルを読み込む
	CString sData;
	CDataDateTime oTime, oSecondDataTime;
	double fDataX, fDataY, fDataZ;
	int nCount = 0;
	while (oFile.ReadString(sData) != NULL) {
		if (GetData(sData, oTime, fDataX, fDataY, fDataZ)) {
			switch (nCount) {
			case 0:
				m_oStartTime = oTime;
				break;
			case 1:
				oSecondDataTime = oTime;
				break;
			case 2:
				// 処理の高速化のため、途中をスキップする
				try {
					oFile.Seek(-200, CFile::end);
				} catch (CException *) {
				}
				break;
			}

			m_oEndTime = oTime;

			nCount++;
		}
	}

	// 有効データ存在チェック
	if (nCount < 2) {
		CGeneral::Alert("ファイルの中に有効なデータが存在しないか、またはデータが不正です。");
		return FALSE;
	}

	// 変換済みファイル存在チェック
	m_bOverWrite = CheckExistFile(m_oStartTime, m_oEndTime);

	// サンプリングレート算出
	m_nSampleSpan = (oSecondDataTime - m_oStartTime) + (oSecondDataTime.m_ttt - m_oStartTime.m_ttt);
	m_fSampleRate = 1000.0 / m_nSampleSpan;
	fSampleRate = m_fSampleRate;

	// 計測時間算出
	nMeasTime = (m_oEndTime - m_oStartTime) / 1000;

	// 開始終了時刻
	oStartTime = m_oStartTime;
	oEndTime = m_oEndTime;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::GetData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データファイルの行から各情報を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString &sData			[I] データファイル内の１行
//		CDataDateTime &oTime	[O] 計測日時
//		double &fDataX			[O] X軸加速度データ
//		double &fDataY			[O] Y軸加速度データ
//		double &fDataZ			[O] Z軸加速度データ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:エラー
//*****************************************************************************************************
BOOL CAsuFile::GetData(const CString &sData, CDataDateTime &oTime, double &fDataX, double &fDataY, double &fDataZ)
{
	// 各データを取得する
	int nYear, nMonth, nDay, nHour, nMinute, nSecond, nMilliSecond;
	if (sscanf_s(sData, "%d/%d/%d %d:%d:%d.%d,%lf,%lf,%lf", &nMonth, &nDay, &nYear, &nHour, &nMinute, &nSecond, &nMilliSecond, &fDataX, &fDataY, &fDataZ) != 10)
		return FALSE;

	// 年をチェック
	if (nYear < 1900)
		return FALSE;

	// 日時のデータを生成
	oTime.SetTime(nYear, nMonth, nDay, nHour, nMinute, nSecond, nMilliSecond);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::CheckExistFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリ変換後のファイルが既に存在するかチェックする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDataDateTime	&oStartTime		[I] 計測開始時刻
//		CDataDateTime	&oEndTime		[I] 計測終了時刻
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:存在する　FALSE:存在しない
//*****************************************************************************************************
BOOL CAsuFile::CheckExistFile(const CDataDateTime &oStartTime, const CDataDateTime &oEndTime)
{
	CString sFolderPath;

	GetOutputFolderName(oStartTime, oEndTime, sFolderPath);
	return ::PathFileExists(sFolderPath);
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::GetOutputFolderName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		出力フォルダパスを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDataDateTime	&oStartTime		[I] 計測開始時刻
//		CDataDateTime	&oEndTime		[I] 計測終了時刻
//		CString			&sFolderPath	[O] 出力フォルダパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	無し
//*****************************************************************************************************
void CAsuFile::GetOutputFolderName(const CDataDateTime &oStartTime, const CDataDateTime &oEndTime, CString &sFolderPath)
{
	sFolderPath.Format("%s\\ASU\\%04d_%02d_%02d_-%02d_%02d_%02d_%03d_%04d%02d%02d%02d%02d%02d",
			gRootData,
			oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, oStartTime.m_hh, oStartTime.m_mm, oStartTime.m_ss, oStartTime.m_ttt,
			oEndTime.m_YY, oEndTime.m_MM, oEndTime.m_DD, oEndTime.m_hh, oEndTime.m_mm, oEndTime.m_ss, oEndTime.m_ttt);
}
//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::ConvDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ASUデータファイルを読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止またはエラー
//*****************************************************************************************************
BOOL CAsuFile::ConvDataFile()
{
	CProgressBar cProgressBar;

	return cProgressBar.ExecProcess("読み込み処理中", 400, this, ConvDataThread);
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::ConvDataThread
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
BOOL CAsuFile::ConvDataThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CAsuFile *)pParam)->ConvDataThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::ConvDataThread2
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
BOOL CAsuFile::ConvDataThread2(CProgressBar *pProgressBar)
{
	// データベース書き込み
	if (!WriteDatabase(pProgressBar))
		return FALSE;

	// 変換済みファイル存在チェック
	m_bOverWrite = CheckExistFile(m_oStartTime, m_oEndTime);

	pProgressBar->SetProgress(400);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::WriteDatabase
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ASUデータファイルの読み込みを行い、データベースフォルダにファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CProgressBar	*pProgressBar		[I] プログレスバー
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止
//*****************************************************************************************************
BOOL CAsuFile::WriteDatabase(CProgressBar *pProgressBar)
{
	CString sMeasTime;
	CString sFolderPath;
	CString sFilePath;
	CString sFileName;
	CStringArray saDataFilePath;
	INT64 nProgressCount = 0;
	CDataDateTime oTime;
	double fDataX, fDataY, fDataZ;
	CString sData;

	CStdioFile oFile;
	if (!oFile.Open(m_sDataFile, CFile::modeRead | CFile::shareDenyWrite, NULL)) {
		CGeneral::Alert("ASUデータファイルが開けません。");
		return FALSE;
	}

	// プログレスバー表示用に全データ数取得
	m_nTotalDataCount = ((m_oEndTime - m_oStartTime) + (m_oEndTime.m_ttt - m_oStartTime.m_ttt)) / m_nSampleSpan;

	// 出力フォルダ名
	GetOutputFolderName(m_oStartTime, m_oEndTime, sFolderPath);

	// ディレクトリ作成
	CGeneral::CreateDirectoryAll(sFolderPath);

	// Idbファイル設定
	SetIdbFile(sFolderPath, m_oStartTime, m_oEndTime);

	// １時間毎に工学値変換ファイルを書き込む
	UINT n1HourDataSize = (int)(m_fSampleRate * 3600);
	SAccData *pDataBuf = new SAccData[n1HourDataSize];
	SAccData oLastData = {0};
	CDataDateTime oDataTime = m_oStartTime;
	BOOL bRead = FALSE;
	BOOL bEof = FALSE;
	for (UINT nDataOffset = 0; ; nDataOffset += n1HourDataSize) {
		// ASUデータファイル読み込み
		UINT nDataCount;
		CDataDateTime oCurrentTime = oDataTime;
		for (nDataCount = 0; nDataCount < n1HourDataSize; nDataCount++) {
			// プログレスバー表示
			nProgressCount += 3;
			if (pProgressBar != NULL && !pProgressBar->SetProgress((int)((INT64)100 * nProgressCount / m_nTotalDataCount)))
				return FALSE;

			// データファイルから読み込む
			if (!bRead) {
				while (true) {
					if (oFile.ReadString(sData) == NULL) {
						bEof = TRUE;
						break;
					}

					if (GetData(sData, oTime, fDataX, fDataY, fDataZ)) {
						bRead = TRUE;
						break;
					}
				}
			}

			// EOFなら終了
			if (bEof)
				break;

			// 計測終了時刻に達したら終了
			if (CompareDateTime(oTime, m_oEndTime) >= 0)
				break;

			// 日時チェック
			if (CompareDateTime(oTime, oCurrentTime) <= 0) {
				oLastData.fDataX = fDataX;
				oLastData.fDataY = fDataY;
				oLastData.fDataZ = fDataZ;

				bRead = FALSE;
			}

			// バッファにデータをセット
			pDataBuf[nDataCount] = oLastData;

			oCurrentTime += m_nSampleSpan;
		}

		// データが無ければ終了
		if (nDataCount == 0)
			break;

		// 出力ファイル名取得
		CDataDateTime oEndTime = oDataTime + (nDataCount * m_nSampleSpan);
		sFileName.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d%c%04d_%02d_%02d_%02d_%02d_%02d.%03d",
				oDataTime.m_YY, oDataTime.m_MM, oDataTime.m_DD, oDataTime.m_hh, oDataTime.m_mm, oDataTime.m_ss, oDataTime.m_ttt,
				nDataOffset == 0 ? '-' : '+',
				oEndTime.m_YY, oEndTime.m_MM, oEndTime.m_DD, oEndTime.m_hh, oEndTime.m_mm, oEndTime.m_ss, oEndTime.m_ttt);
		sFilePath.Format("%s\\%s", sFolderPath, sFileName);

		// データファイル書き込み
		if (!WriteDataFile(sFilePath, pDataBuf, nDataOffset, nDataCount, pProgressBar, nProgressCount))
			return FALSE;

		// XMLファイル書き込み
		WriteXmlFile(sFileName, oCurrentTime);

		// データファイル名保存
		m_oIdbFile.AddFile(sFileName);

		// １時間（3600 * 1000 m秒）加算
		oDataTime += 3600 * 1000;
	}

	delete [] pDataBuf;

	// idbファイル書き込み
	WriteIdbFile(m_oStartTime);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::CompareDateTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		日時のデータを比較する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDataDateTime	&oDate1		[I] 比較する日時
//		CDataDateTime	&oDate2		[I] 比較する日時
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int			0：一致　正の値：oData1の方が大きい　負の値：oData2の方が大きい
//*****************************************************************************************************
int CAsuFile::CompareDateTime(const CDataDateTime &oDate1, const CDataDateTime &oDate2)
{
	if (oDate1.m_YY != oDate2.m_YY)
		return oDate1.m_YY - oDate2.m_YY;

	if (oDate1.m_MM != oDate2.m_MM)
		return oDate1.m_MM - oDate2.m_MM;

	if (oDate1.m_DD != oDate2.m_DD)
		return oDate1.m_DD - oDate2.m_DD;

	if (oDate1.m_hh != oDate2.m_hh)
		return oDate1.m_hh - oDate2.m_hh;

	if (oDate1.m_mm != oDate2.m_mm)
		return oDate1.m_mm - oDate2.m_mm;

	if (oDate1.m_ss != oDate2.m_ss)
		return oDate1.m_ss - oDate2.m_ss;

	if (oDate1.m_ttt != oDate2.m_ttt)
		return oDate1.m_ttt - oDate2.m_ttt;

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::SetIdbFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイルに情報をセットする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sFolderPath	[I] 出力フォルダパス
//		CDataDateTime	&oStartTime		[I] 計測開始時刻
//		CDataDateTime	&oEndTime		[I] 計測終了時刻
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAsuFile::SetIdbFile(const CString &sFolderPath, const CDataDateTime &oStartTime, const CDataDateTime &oEndTime)
{
	CString sBuf;

	// データ消去
	m_oIdbFile.DelAll();

	// センサーIDX
	m_oIdbFile.SetSensorIdx(0);

	// コメント
	m_oIdbFile.SetComment(m_sComment);

	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	m_oIdbFile.SetStartTime(oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, (((oStartTime.m_hh * 60) + oStartTime.m_mm) * 60 + oStartTime.m_ss) * 1000 + oStartTime.m_ttt);

	// サンプルレート
	m_oIdbFile.SetSampleRate(m_fSampleRate);

	// 期間
	sBuf.Format("%04d%02d%02d%02d%02d%02d-%04d%02d%02d%02d%02d%02d",
			oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, oStartTime.m_hh, oStartTime.m_mm, oStartTime.m_ss,
			oEndTime.m_YY, oEndTime.m_MM, oEndTime.m_DD, oEndTime.m_hh, oEndTime.m_mm, oEndTime.m_ss);
	m_oIdbFile.SetPeriod(sBuf);

	// ダウンリンク日
	m_oIdbFile.SetDownlink(m_sDownlink);

	// センサーID
	m_oIdbFile.SetSensorId(1);

	// タイムゼロ
	sBuf.Format("%04d%02d%02d%02d%02d%02d", oStartTime.m_YY, oStartTime.m_MM, oStartTime.m_DD, oStartTime.m_hh, oStartTime.m_mm, oStartTime.m_ss);
	m_oIdbFile.SetTimeZero(sBuf);

	// Sensor Co Sys
	sBuf.Format("X=%g, Y=%g, Z=%g, Roll=%g, Pitch=%g, Yaw=%g",
			g_pSensorInfoAsu->x,
			g_pSensorInfoAsu->y,
			g_pSensorInfoAsu->z,
			g_pSensorInfoAsu->roll,
			g_pSensorInfoAsu->pitch,
			g_pSensorInfoAsu->yaw);
	m_oIdbFile.SetSensorcosys(sBuf);

	// Data Co Sys
	m_oIdbFile.SetDatacosys(sBuf);

	// 保存パス名
	m_oIdbFile.SetPath(sFolderPath);
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::WriteDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換ファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sFilePath			[I] 出力ファイルパス
//		SAccData		*pDataBuf			[I] データバッファ
//		UINT			nDataOffset			[I] データオフセット
//		UINT			nWriteDataSize		[I] 書き込みデータ数
//		CProgressBar	*pProgressBar		[I] プログレスバー
//		INT64			nProgressCount		[I/O] プログレスバーカウント
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:中止
//*****************************************************************************************************
BOOL CAsuFile::WriteDataFile(const CString &sFilePath, SAccData *pDataBuf, UINT nDataOffset, UINT nWriteDataSize, CProgressBar *pProgressBar, INT64 &nProgressCount)
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
		oHeader.nSeqNo = (int)(nDataOffset / m_fSampleRate) + 1;
		oHeader.nDataSize = nWriteDataSize;
		oHeader.nDataSizeHour = (int)(m_fSampleRate * 3600);
		oFile.Write(&oHeader, sizeof(oHeader));

		// データ書き込み
		double aWriteBuf[7];
		memset(aWriteBuf, 0, sizeof(aWriteBuf));
		for (UINT nDataCount = 0; nDataCount < nWriteDataSize; nDataCount++) {
			aWriteBuf[4 + 0] = pDataBuf[nDataCount].fDataX * (1000 * 1000);
			aWriteBuf[4 + 1] = pDataBuf[nDataCount].fDataY * (1000 * 1000);
			aWriteBuf[4 + 2] = pDataBuf[nDataCount].fDataZ * (1000 * 1000);

			// プログレスバー表示
			nProgressCount++;
			if (pProgressBar != NULL && !pProgressBar->SetProgress((int)((INT64)100 * nProgressCount / m_nTotalDataCount)))
				return FALSE;

			// ファイルに書き込み
			oFile.Write(aWriteBuf, sizeof(aWriteBuf));
		}
	}

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::WriteXmlFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		XMLファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString			&sFileName				[I] 出力ファイル名
//		CDataDateTime	&oDataStartTime			[I] データの開始時刻
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAsuFile::WriteXmlFile(const CString &sFileName, const CDataDateTime &oDataStartTime)
{
	CString sBuf;

	// 計測開始時刻を編集
	sBuf.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d",
			oDataStartTime.m_YY, oDataStartTime.m_MM, oDataStartTime.m_DD,
			oDataStartTime.m_hh, oDataStartTime.m_mm, oDataStartTime.m_ss, oDataStartTime.m_ttt);
	m_oIdbFile.SetTimeZeroXML(sBuf);

	// XMLファイルを書き込み
	m_oIdbFile.WriteXml(sFileName, DATA_TYPE_ASU);
}

//*****************************************************************************************************
//  1. 関数名
//		CAsuFile::WriteIdbFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイルを書き込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CDataDateTime	&oDataStartTime		[I] データの開始時刻
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CAsuFile::WriteIdbFile(const CDataDateTime &oDataStartTime)
{
	// ファイル名を編集
	CString sFileName;
	sFileName.Format("%04d%02d%02d%d", oDataStartTime.m_YY, oDataStartTime.m_MM, oDataStartTime.m_DD, (((oDataStartTime.m_hh * 60) + oDataStartTime.m_mm) * 60 + oDataStartTime.m_ss) * 1000 + oDataStartTime.m_ttt);

	// idbファイルに書き込み
	m_oIdbFile.Write(sFileName);
}
