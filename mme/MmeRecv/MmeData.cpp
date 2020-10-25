//*****************************************************************************************************
//  1. ファイル名
//		MmeData.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		MMEデータクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//		無し
//*****************************************************************************************************

#include "StdAfx.h"
#include "MmeData.h"
#include "General.h"
#include <shlwapi.h>

// バイトデータからワードデータへ変換
#define GETWORD(b1, b2) (((WORD)(b1) << 8) | (b2))
#define GETDWORD(b1, b2, b3, b4) (((((((DWORD)(b1) << 8) | (b2)) << 8) | (b3)) << 8) | (b4))

// ポインタからワードデータへ変換
#define GETWORD_P(p) GETWORD((p)[0], (p)[1])
#define GETDWORD_P(p) GETDWORD((p)[0], (p)[1], (p)[2], (p)[3])

// ポインタから文字列を取得
#define GETSTRING(p, n) CString((char *)(p), n)

// 受信フォルダ
#define RECEIVE_FOLDER	"ReceiveData"

CMmeData g_oMmeData;

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::ReadIniFile
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
BOOL CMmeData::ReadIniFile()
{
	static const char *aUnit[N_UNIT] = {"ACC_SCALE_FACTOR_UNIT1", "ACC_SCALE_FACTOR_UNIT2", "ACC_SCALE_FACTOR_UNIT3"};
	static const char *aAcc[N_ACC] = {"X", "Y", "Z"};
	static const char *aRange[N_RANGE] = {"Lo", "Hi", "Ex"};
	static const char aAlalog[] = "ANALOG_SCALE_FACTOR";
	CString sIniPathName;
	CString sKey;
	char buf[256];

	// iniパス名取得
	CGeneral::GetIniPathName(sIniPathName);

	// ファイルが存在しなければエラー
	if (!::PathFileExists(sIniPathName))
		return FALSE;

	// デジタル加速度スケールファクタの取得
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		for (int nRange = 0; nRange < N_RANGE; nRange++) {
			for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
				sKey.Format("%s-%s", aAcc[nAcc], aRange[nRange]);
				m_oFactors.oDigitalFactor.aUnit[nUnit].aRange[nRange].fAcc[nAcc] = CGeneral::GetPrivateProfileDouble(aUnit[nUnit], sKey, 0, sIniPathName);
			}
		}
	}

	// アナログデータファクタの取得
	for (int nRange = 0; nRange < N_RANGE; nRange++) {
		sKey.Format("Acc-%s", aRange[nRange]);
		m_oFactors.oAnalogFactor.aAcc[nRange] = CGeneral::GetPrivateProfileDouble(aAlalog, sKey, 0, sIniPathName);
	}
	m_oFactors.oAnalogFactor.fTemp = CGeneral::GetPrivateProfileDouble(aAlalog, "Temp", 0, sIniPathName);
	m_oFactors.oAnalogFactor.fHeater = CGeneral::GetPrivateProfileDouble(aAlalog, "Heater", 0, sIniPathName);

	// 閏秒の取得
	static const UINT aLeaps[] = {46828800, 78364801, 109900802, 173059203, 252028804, 315187205, 346723206, 393984007, 425520008, 457056009, 504489610, 551750411, 599184012, 820108813, 914803214, 1025136015};
	SLeapSecond oLeapSecond;
	for (int i = 0; i < sizeof(aLeaps) / sizeof(int); i++) {
		oLeapSecond.nTime = aLeaps[i];
		oLeapSecond.nSecond = 1;
		m_oLeapSecond.Add(oLeapSecond);
	}
	for (int i = 0; ; i++) {
		int nYear, nMonth, nDay, nSecond;
		sKey.Format("Date%d", i + 1);
		::GetPrivateProfileString("LEAP_SECOND", sKey, "", buf, sizeof(buf), sIniPathName);
		int n = sscanf_s(buf, "%d/%d/%d %d", &nYear, &nMonth, &nDay, &nSecond);
		if (n >= 3) {
			try {
				oLeapSecond.nTime = (int)CTime(nYear, nMonth, nDay, 8, 59, 59).GetTime() - 315964800 + 16 + i;
				oLeapSecond.nSecond = (n == 3) ? 1 : nSecond;
				m_oLeapSecond.Add(oLeapSecond);
			} catch (CException *) {
			}
		} else
			break;
	}

	// 受信データフォルダの取得
	::GetPrivateProfileString("RECEIVE_FILE", "Folder", RECEIVE_FOLDER, buf, sizeof(buf), sIniPathName);
	m_sReceiveDataFolder = buf;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::CreateReceiveFolder
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信フォルダを作成する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::CreateReceiveFolder()
{
	::CreateDirectory(m_sReceiveDataFolder, NULL);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::OpenRecieveFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信ファイルオープン処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pNasaPathName			[I] NASA用受信ファイルパス
//		LPCTSTR		pHcorPathName			[I] HCOR用受信ファイルパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL		TRUE:正常　FALSE:失敗
//*****************************************************************************************************
BOOL CMmeData::OpenRecieveFiles(LPCTSTR pNasaPathName, LPCTSTR pHcorPathName)
{
	CString sPathName;

	// NASA用受信ファイルオープン
	m_sNasaPathName = pNasaPathName;
	if (!m_fileNasa.Open(m_sNasaPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive))
		return FALSE;

	// HCOR用受信ファイルオープン
	m_sHcorPathName = pHcorPathName;
	if (!m_fileHcor.Open(m_sHcorPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareExclusive))
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::CloseReceiveFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		受信ファイルクローズ処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::CloseReceiveFiles()
{
	ULONGLONG nLength;

	// NASA用受信ファイルクローズ
	if (m_fileNasa.m_hFile != INVALID_HANDLE_VALUE) {
		nLength = m_fileNasa.GetLength();
		m_fileNasa.Close();

		// サイズが0ならファイルを削除する
		if (nLength == 0)
			::DeleteFile(m_sNasaPathName);
	}

	// HCOR用受信ファイルクローズ
	if (m_fileHcor.m_hFile != INVALID_HANDLE_VALUE) {
		nLength = m_fileHcor.GetLength();
		m_fileHcor.Close();

		// サイズが0ならファイルを削除する
		if (nLength == 0)
			::DeleteFile(m_sHcorPathName);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::WriteNasaFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		NASAパケットをファイルに書き出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE		*pPacketBuf			[I] パケットバッファ
//		UINT		nPacketLen			[I] パケットサイズ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::WriteNasaFile(BYTE *pPacketBuf, UINT nPacketLen)
{
	CTime oTime = CTime::GetCurrentTime();

	m_fileNasa.Write(oTime.Format("%H:%M:%S"), 8);
	m_fileNasa.Write(pPacketBuf, nPacketLen);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::WriteHcorFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		HCORパケットをファイルに書き出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE		*pPacketBuf			[I] パケットバッファ
//		UINT		nPacketLen			[I] パケットサイズ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::WriteHcorFile(BYTE *pPacketBuf, UINT nPacketLen)
{
	CTime oTime = CTime::GetCurrentTime();

	m_fileHcor.Write(oTime.Format("%H:%M:%S"), 8);
	m_fileHcor.Write(pPacketBuf, nPacketLen);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetUserHeader
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからユーザヘッダの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pPacketBuf			[I] パケットバッファ
//		SUserHeader		&oUserHeader		[O] パケットサイズ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetUserHeader(BYTE *pPacketBuf, SUserHeader &oUserHeader)
{
	// ヘッダの内容を取り出す
	oUserHeader.nPayloadId = pPacketBuf[0];
	oUserHeader.nDataId = pPacketBuf[1];
	oUserHeader.nFrameId = pPacketBuf[2] >> 1;
	oUserHeader.nEndSegment = pPacketBuf[2] & 0x01;
	oUserHeader.nSegmentCount = pPacketBuf[3];
	oUserHeader.oTimestamp = GetTime(GETDWORD(pPacketBuf[4], pPacketBuf[5], pPacketBuf[6], pPacketBuf[7]));
	oUserHeader.nFineTime = GETWORD(pPacketBuf[8], pPacketBuf[9]);
	oUserHeader.nPacketType = PACKET_UNKNOWN;

	// ペイロードID
	if (oUserHeader.nPayloadId == 0x35) {
		// データ識別ID
		if (oUserHeader.nDataId == 0x80) {
			// フレーム構造識別ID
			switch (oUserHeader.nFrameId) {
			case 0x01:	// 通常シーケンスLoレンジ計測モード
			case 0x02:	// 通常シーケンスHiレンジ計測モード
			case 0x03:	// 通常シーケンス拡張レンジ計測モード
			case 0x11:	// 連続シーケンスLoレンジ計測モード
			case 0x12:	// 連続シーケンスHiレンジ計測モード
			case 0x13:	// 連続シーケンス拡張レンジ計測モード
				oUserHeader.nPacketType = PACKET_TELEMETRY;
				oUserHeader.oTelemetry.nMeasurementMode = (pPacketBuf[10] >> 6) & 0x03;
				oUserHeader.oTelemetry.nMeasurementRange = (pPacketBuf[10] >> 4) & 0x03;
				oUserHeader.oTelemetry.nSamplingRate = GETWORD(pPacketBuf[10] & 0x0f, pPacketBuf[11]);
				oUserHeader.oTelemetry.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oTelemetry.oStatusWord);
				break;
			case 0x04:	// MMEステータスパケット
				oUserHeader.nPacketType = PACKET_MME_STATUS;
				oUserHeader.oMmeStatus.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oMmeStatus.oStatusWord);
				break;			
			case 0x05:	// エラーステータスパケット
				oUserHeader.nPacketType = PACKET_ERROR_STATUS;
				oUserHeader.oErrorStatus.nErrorCode = pPacketBuf[10];
				oUserHeader.oErrorStatus.nSubCode = pPacketBuf[11];
				oUserHeader.oErrorStatus.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oErrorStatus.oStatusWord);
				break;			
			case 0x06:	// メッセージパケット
				oUserHeader.nPacketType = PACKET_MESSAGE;
				oUserHeader.oMessage.nDataNum = GETWORD(pPacketBuf[12], pPacketBuf[13]);
				GetStatusWord(GETWORD(pPacketBuf[14], pPacketBuf[15]), oUserHeader.oMessage.oStatusWord);
				break;			
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetTeremetryData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからテレメトリデータの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pPacketBuf			[I] パケットバッファ
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		STelemetryData	&oTelemetryData		[O] テレメトリデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetTeremetryData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, STelemetryData &oTelemetryData)
{
	// アナログデータを取得
	pPacketBuf += USER_HEADER_SIZE;
	GetAnalogData(pPacketBuf, oUserHeader.oTelemetry.oStatusWord, oTelemetryData.oAnalogData);

	// デジタルデータを取得
	pPacketBuf += ANALOG_DATA_SIZE;
	GetDigitalData(pPacketBuf, oUserHeader.oTelemetry.oStatusWord, oTelemetryData.oDigitalData);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetMmeStatusData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからMMEステータスデータの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pPacketBuf			[I] パケットバッファ
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SMmeStatusData	&oMmeStatusData		[O] MMEステータスデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetMmeStatusData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SMmeStatusData &oMmeStatusData)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	oMmeStatusData.sContrxxxodeTableName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nMeasureModeId = pData[0];
	oMmeStatusData.nMeasureStatus = pData[1];
	oMmeStatusData.nCurrentRangeId = GETWORD(pData[2], pData[3]);
	pData += 4;

	oMmeStatusData.sDownlinkPathName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nDownlinkPathData = GETDWORD_P(pData);
	pData += 4;

	oMmeStatusData.sTimerCountName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nTimerCountData = GETDWORD_P(pData);
	pData += 4;

	oMmeStatusData.sMeasStartTimeName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nMeasStartTimeData = GETDWORD_P(pData);
	pData += 4;

	oMmeStatusData.sMeasEndTimeName = GETSTRING(pData, 4);
	pData += 4;

	oMmeStatusData.nMeasEndTimeData = GETDWORD_P(pData);
	pData += 4;

	// アナログデータを取得
	GetAnalogData(pData, oUserHeader.oMmeStatus.oStatusWord, oMmeStatusData.oAnalogData);
	pData += ANALOG_DATA_SIZE;

	// 前回HALT時エラー情報を取得
	GetLasttimeHaltData(pData, oMmeStatusData.oLasttimeHalt);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetLasttimeHaltData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットから前回HALT時エラー情報の内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pData				[I] パケットデータ
//		SLasttimeHalt	&oLasttimeHalt		[I] 前回HALT時エラー情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetLasttimeHaltData(BYTE *pData, SLasttimeHalt &oLasttimeHalt)
{
	// 全てのデータが0ならば無効とする
	oLasttimeHalt.bValidData = FALSE;
	for (int i = 0; i < 24; i++) {
		if (pData[i] != 0) {
			oLasttimeHalt.bValidData = TRUE;
			break;
		}
	}
		
	oLasttimeHalt.oBitStatus.nSessionNumber = GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oBitStatus.nType= GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oBitStatus.nNature = GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oHaltInfo.sTaskName = GETSTRING(pData, 4);
	pData += 4;

	oLasttimeHalt.oHaltInfo.nErrorCode = GETDWORD_P(pData);
	pData += 4;

	oLasttimeHalt.oHaltInfo.oErrorTime = GetTime(GETDWORD_P(pData));
	pData += 4;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetErrorStatusData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからエラーステータスデータの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE				*pPacketBuf			[I] パケットバッファ
//		SUserHeader			&oUserHeader		[I] ユーザヘッダ情報
//		SErrorStatusData	&oErrorStatusData	[O] エラーステータスデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetErrorStatusData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SErrorStatusData &oErrorStatusData)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	if (oUserHeader.oErrorStatus.nErrorCode == 0x02) {
		oErrorStatusData.sTaskName = GETSTRING(pData, 8);
		pData += 8;

		oErrorStatusData.sErrorMessage = GETSTRING(pData, 192);
	} else {
		oErrorStatusData.sErrorMessage = GETSTRING(pData, 200);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetMessageData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからメッセージデータの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pPacketBuf			[I] パケットバッファ
//		SUserHeader		&oUserHeader		[I] ユーザヘッダ情報
//		SMessageData	&oMessageData		[O] メッセージデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetMessageData(BYTE *pPacketBuf, const SUserHeader &oUserHeader, SMessageData &oMessageData)
{
	BYTE *pData = pPacketBuf + USER_HEADER_SIZE;

	oMessageData.sMessage = GETSTRING(pData, 56);
	pData += 56;

	GetLasttimeHaltData(pData, oMessageData.oLasttimeHalt);
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetStatusWord
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからステータスワードの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		UINT			nStatusWord			[I] ステータスワード
//		SStatusWord		&oStatusWord		[O] ステータスワード情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetStatusWord(UINT nStatusWord, SStatusWord &oStatusWord)
{
	// ユニット1
	oStatusWord.aUnit[UNIT_1].nOnOffStatus = (nStatusWord >> 13) & 0x01;
	oStatusWord.aUnit[UNIT_1].nRangeStatus = (nStatusWord >> 11) & 0x03;

	// ユニット2
	oStatusWord.aUnit[UNIT_2].nOnOffStatus = (nStatusWord >> 8) & 0x01;
	oStatusWord.aUnit[UNIT_2].nRangeStatus = (nStatusWord >> 6) & 0x03;

	// ユニット3
	oStatusWord.aUnit[UNIT_3].nOnOffStatus = (nStatusWord >> 3) & 0x01;
	oStatusWord.aUnit[UNIT_3].nRangeStatus = (nStatusWord >> 1) & 0x03;
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetAnalogData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからアナログモニタの内容を取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pData				[I] パケットデータ
//		SStatusWord		&oStatusWord		[I] ステータスワード情報
//		SAnalogData		&oAnalogData		[O] アナログモニタ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetAnalogData(BYTE *pData, const SStatusWord &oStatusWord, SAnalogData &oAnalogData)
{
	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		int nRange = oStatusWord.aUnit[nUnit].nRangeStatus - 1;

		// 加速度データ
		for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
			oAnalogData.aUnit[nUnit].nAcc[nAcc] = (short)GETWORD_P(pData);
			if (nRange >= 0 && nRange < N_RANGE)
				oAnalogData.aUnit[nUnit].fAcc[nAcc] = oAnalogData.aUnit[nUnit].nAcc[nAcc] * m_oFactors.oAnalogFactor.aAcc[nRange];
			else
				oAnalogData.aUnit[nUnit].fAcc[nAcc] = 0;

			pData += 2;
		}

		// 温度データ
		for (int nTemp = 0; nTemp < N_TEMP; nTemp++) {
			oAnalogData.aUnit[nUnit].nTemp[nTemp] = (short)GETWORD_P(pData);
			oAnalogData.aUnit[nUnit].fTemp[nTemp] = oAnalogData.aUnit[nUnit].nTemp[nTemp] * m_oFactors.oAnalogFactor.fTemp;
			pData += 2;
		}

		// ヒータ電圧データ
		oAnalogData.aUnit[nUnit].nHeater = (short)GETWORD_P(pData);
		oAnalogData.aUnit[nUnit].fHeater = oAnalogData.aUnit[nUnit].nHeater * m_oFactors.oAnalogFactor.fHeater;
		pData += 2;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeData::GetDigitalData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		パケットからデジタル加速度データを取り出す
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		BYTE			*pData				[I] パケットデータ
//		SStatusWord		&oStatusWord		[I] ステータスワード情報
//		SDigitalData	&oDigitalData		[O] デジタル加速度データ情報
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CMmeData::GetDigitalData(BYTE *pData, const SStatusWord &oStatusWord, SDigitalData &oDigitalData)
{
	oDigitalData.nSequrnceCount = GETWORD(pData[0] & 0x0f, pData[1]);
	pData += 2;

	for (int nUnit = 0; nUnit < N_UNIT; nUnit++) {
		int nRange = oStatusWord.aUnit[nUnit].nRangeStatus - 1;

		// 加速度データ
		for (int nAcc = 0; nAcc < N_ACC; nAcc++) {
			oDigitalData.aUnit[nUnit].nAcc[nAcc] = (short)GETWORD_P(pData);
			if (nRange >= 0 && nRange < N_RANGE)
				oDigitalData.aUnit[nUnit].fAcc[nAcc] = oDigitalData.aUnit[nUnit].nAcc[nAcc] * m_oFactors.oDigitalFactor.aUnit[nUnit].aRange[nRange].fAcc[nAcc];
			else
				oDigitalData.aUnit[nUnit].fAcc[nAcc] = 0;

			pData += 2;
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CMmeRecvDlg::GetTime
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
CTime CMmeData::GetTime(UINT nTimestamp)
{
	// 日時に変換
	UINT nTime = nTimestamp + 315964800;

	// 閏秒の補正
	for (int i = 0; i < m_oLeapSecond.GetCount(); i++) {
		if (m_oLeapSecond[i].nTime < nTimestamp)
			nTime -= m_oLeapSecond[i].nSecond;
		else
			break;
	}

	return CTime(nTime);
}
