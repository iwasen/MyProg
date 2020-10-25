//*****************************************************************************************************
//  1. ファイル名
//		DataFile.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		データファイルクラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "DataFile.h"
#include "General.h"
#include "MMA_G.h"
#include <math.h>

// 077FD050-GYM961-0_007 2007/9/8 modified by yG ->
//static	char *gTitle = "No,Time,X-Axis(μg),Y-Axis(μg),Z-Axis(μg),D-Temp(℃),X-Temp(℃),Y-Temp(℃),Z-Temp(℃)\n";
static const char *gTitleMma = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),X-Axis(μg),Y-Axis(μg),Z-Axis(μg),D-Temp(℃),X-Temp(℃),Y-Temp(℃),Z-Temp(℃)\n";
static const char *gTitleMme = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),X-Axis(μg),Y-Axis(μg),Z-Axis(μg)\n";
static const char *gTitleAsu = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),X-Axis(μg),Y-Axis(μg),Z-Axis(μg)\n";
// 077FD050-GYM961-0_007 2007/9/8 modified by yG ->
static const char *gTitleTemp = "No,Time(YYYY/MM/DD hh:mm:ss.tttt),D-Temp(℃),X-Temp(℃),Y-Temp(℃),Z-Temp(℃)\n";

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::CDataFile
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
CDataFile::CDataFile()
{
	// メンバ変数を初期化
	m_pTempDACM = NULL;
	m_pTempX = NULL;
	m_pTempY = NULL;
	m_pTempZ = NULL;
	m_pDataX = NULL;
	m_pDataY = NULL;
	m_pDataZ = NULL;
	m_nDataSize = 0;
	m_fSampleRate = 0;
	m_bReadAccelData = FALSE;
	m_bReadTempData = FALSE;
	m_nDownRate = 1;
	m_nAllocSize = 0;
	m_fAllocFactor = 1.0;
	m_nReadSize = 0;
	m_bTempOnly = FALSE;
	m_nDataType = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::~CDataFile
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
CDataFile::~CDataFile()
{
	// バイナリデータバッファを解放
	FreeBuf();
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::ReadInfoFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		情報ファイル読み込み処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pFilePath		[I] 情報ファイルのパス名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::ReadInfoFile(LPCTSTR pFilePath)
{
	CStdioFile cFile;
	CMapStringToString cStringMap;
	CString sReadBuf;
	int nPos;
	CString sKey, sData;

	// 情報ファイルオープン
	if (!cFile.Open(pFilePath, CFile::modeRead | CFile::shareDenyNone)) {
		CGeneral::Alert("情報ファイルが開けません。\n(%s)", pFilePath);
		return FALSE;
	}

	// 内容をMAPに読み込む
	m_aDataFileName.RemoveAll();
	while (cFile.ReadString(sReadBuf)) {
		nPos = sReadBuf.Find('=');
		if (nPos > 0) {
			sKey = sReadBuf.Left(nPos);
			sData = sReadBuf.Mid(nPos + 1);

			if (sKey == "FILE")
				m_aDataFileName.Add(sData);
			else
				cStringMap.SetAt(sKey, sData);
		}
	}

	// MAPから必要な情報を取り出す
	m_fSampleRate = atof(cStringMap["SAMPLERATE"]);
	m_sDatPath = cStringMap["PATH"];
	m_nSensorId = atoi(cStringMap["SENSORID"]);
	m_nGain = atoi(cStringMap["GAIN"]);
	m_nCutoff = atoi(cStringMap["CUTOFF"]);
	m_nSensorIdx = atoi(cStringMap["SENSORIDX"]);
	m_SensorPos = cStringMap["SENSORCOSYS"];
	m_IssConfig = cStringMap["ISSCONFIG"];

	// 開始時間を年月日と秒に分解
	CString sStartTime = cStringMap["STARTTIME"];
	m_nStartYear = atoi(sStartTime.Mid(0, 4));
	m_nStartMonth = atoi(sStartTime.Mid(4, 2));
	m_nStartDay = atoi(sStartTime.Mid(6, 2));
	m_nStartMiliSecond = atoi(sStartTime.Mid(8, 8));
	m_fStartSecond = (double)m_nStartMiliSecond / 1000;

	// 開始時間を表示用開始時間にセット
	m_dStarttime.SetTime(m_nStartYear, m_nStartMonth, m_nStartDay, m_nStartMiliSecond);

	// トータル時間を取得
	if (!GetTotalTime())
		return FALSE;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::GetTotalTime
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		トータル時間数取得
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::GetTotalTime()
{
	CFile cFile;
	CString sPathName;
	INFO_FILE_HEADER sHeader;

	m_nTotalDataSize = 0;

	// データファイルの個数だけループ
	int nFiles = (int)m_aDataFileName.GetSize();
	for (int i = 0; i < nFiles; i++) {
		// データファイルをオープン
		sPathName.Format("%s\\%s", m_sDatPath, m_aDataFileName[i]);
		if (!cFile.Open(sPathName, CFile::modeRead | CFile::shareDenyNone)) {
			CGeneral::Alert("データファイルが開けません。\n(%s)", sPathName);
			return FALSE;
		}

		// データファイルからヘッダ部分を読み込む
		cFile.Read(&sHeader, sizeof(sHeader));

		cFile.Close();

		// 全データファイルのデータ個数を加算
		m_nTotalDataSize += sHeader.nDataSize;
	}

	// トータル時間を求める
	m_fTotalSecond = m_nTotalDataSize / m_fSampleRate;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::ReadDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリファイル読み込み
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nBeginTime			[I] 読み込み開始時間（秒数）
//		int		nEndTime			[I] 読み込み終了時間（秒数）
//		BOOL	bReadAccelData		[I] 加速度データ読み込みフラグ
//		BOOL	bReadTempData		[I] 温度データ読み込みフラグ
//		BOOL	bProgressBar		[I] プログレスバー表示フラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::ReadDataFile(int nBeginTime, int nEndTime, BOOL bReadAccelData, BOOL bReadTempData, BOOL bProgressBar)
{
	CProgressBar cProgressBar;

	m_bReadAccelData = bReadAccelData;
	m_bReadTempData = bReadTempData;

	// バイナリデータサイズ設定
	m_nDataSize = (int)((nEndTime - nBeginTime) * m_fSampleRate);
	m_nDataOffset = (int)((nBeginTime * 1000 - m_nStartMiliSecond) * m_fSampleRate / 1000);
	if (m_nDataOffset < 0)
		m_nDataOffset = 0;

	// バイナリデータバッファ確保
	AllocBuf();

	if (bProgressBar) {
		// プログレスバーを表示し、別スレッドでファイルを読み込む
		return cProgressBar.ExecProcess("ファイル読み込み中", 10, this, ReadBinaryFileThread);
	} else {
		// データファイルを読み込む
		return ReadBinaryFileThread2(NULL);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::ReadWriteDataFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリファイル読み込み
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCSTR	fname				[I] ファイル名
//		int		nBeginTime			[I] 読み込み開始時間（秒数）
//		int		nEndTime			[I] 読み込み終了時間（秒数）
//		BOOL	bReadTempData		[I] 温度データ読み込みフラグ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::ReadWriteDataFile(LPCSTR fname, int nBeginTime, int nEndTime, BOOL bReadTempData)
{
	CProgressBar cProgressBar;

	m_bReadAccelData = TRUE;
	m_bReadTempData = bReadTempData;
	m_WriteFilePath = fname;
	m_nBeginTime = nBeginTime;

	// バイナリデータサイズ設定
	m_nDataSize = (int)((nEndTime - nBeginTime) * m_fSampleRate);
	m_nDataOffset = (int)((nBeginTime * 1000 - m_nStartMiliSecond) * m_fSampleRate / 1000);

	// バイナリデータバッファ確保
	AllocBuf();

	// プログレスバーを表示し、別スレッドでファイルを読み込む
	if (! cProgressBar.ExecProcess("ファイル読み込み中", 10, this, ReadBinaryFileThread))
		return FALSE;

	// プログレスバーを表示し、別スレッドでファイルを読み込む
	return cProgressBar.ExecProcess("ファイル書き込み中", 10, this, WriteBinaryFileThread);
}


//*****************************************************************************************************
//  1. 関数名
//		CDataFile::ReadBinaryFileThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル読み込みスレッドの入り口
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID			pParam			[I] CDataFileクラスへのポインタ
//		CProgressBar	*pProgressBar	[I] プログレスバークラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::ReadBinaryFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CDataFile *)pParam)->ReadBinaryFileThread2(pProgressBar);
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::WriteBinaryFileThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル書き込みスレッドの入り口
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID			pParam			[I] CDataFileクラスへのポインタ
//		CProgressBar	*pProgressBar	[I] プログレスバークラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::WriteBinaryFileThread(LPVOID pParam, CProgressBar *pProgressBar)
{
	return ((CDataFile *)pParam)->WriteBinaryFileThread2(pProgressBar);
}


//*****************************************************************************************************
//  1. 関数名
//		CDataFile::ReadBinaryFileThread2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル読み込みスレッドの本体
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CProgressBar	*pProgressBar	[I] プログレスバークラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::ReadBinaryFileThread2(CProgressBar *pProgressBar)
{
	CFile cFile;
	CString sPathName;
	INFO_FILE_HEADER sHeader;

	// 変数を初期化
	int nSkipSize = m_nDataOffset + m_nReadSize;
	int nReadPtr = m_nReadSize;

	// データファイルの個数だけループ
	int nFiles = (int)m_aDataFileName.GetSize();
	for (int i = 0; i < nFiles; i++) {
		// データファイルをオープン
		sPathName.Format("%s\\%s", m_sDatPath, m_aDataFileName[i]);
		if (!cFile.Open(sPathName, CFile::modeRead | CFile::shareDenyNone)) {
			CGeneral::Alert("データファイルが開けません。");
			return FALSE;
		}

		// データファイルからヘッダ部分を読み込む
		cFile.Read(&sHeader, sizeof(sHeader));

		// 読み飛ばすサイズを計算
		int nDataSize = sHeader.nDataSize;
		int nReadOffset = 0;
		if (nSkipSize > 0) {
			int nSkip = min(nSkipSize, nDataSize);
			nSkipSize -= nSkip;
			nReadOffset += nSkip;
			nDataSize -= nSkip;
		}

		// 読み飛ばしが終了したらデータを読み込む
		if (nSkipSize == 0) {
			int nRead = min(m_nDataSize - nReadPtr, nDataSize);
			if (nRead > 0) {
				// ファイルからデータを読み込む
				double *pBuf = new double[nRead * 7];
				cFile.Seek(nReadOffset * 7 * sizeof(double), CFile::current);
				cFile.Read(pBuf, nRead * 7 * sizeof(double));

				// データバッファにコピー
				double *pBuf2 = pBuf;
				for (int j = 0; j < nRead; j++) {
					// 各データをバッファにコピー
					if (m_bReadTempData) {
						m_pTempDACM[nReadPtr] = *pBuf2++;
						m_pTempX[nReadPtr] = *pBuf2++;
						m_pTempY[nReadPtr] = *pBuf2++;
						m_pTempZ[nReadPtr] = *pBuf2++;
					} else
						pBuf2 += 4;

					if (m_bReadAccelData) {
						m_pDataX[nReadPtr] = *pBuf2++;
						m_pDataY[nReadPtr] = *pBuf2++;
						m_pDataZ[nReadPtr] = *pBuf2++;
					} else
						pBuf2 += 3;

					// プログレスバーを更新
					if (pProgressBar != NULL) {
						if (!pProgressBar->SetProgress(nReadPtr * 10 / m_nDataSize + 1)) {
							// データファイルをクローズ
							cFile.Close();

							delete [] pBuf;
							return FALSE;
						}
					}

					nReadPtr++;
				}

				delete [] pBuf;
			}
		}

		// データファイルをクローズ
		cFile.Close();

		// 必要なサイズ読み込んだら終了
		if (nReadPtr >= m_nDataSize)
			break;
	}

	// 読み込み済みのサイズを保存
	m_nReadSize = nReadPtr;

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::WriteBinaryFileThread2
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		ファイル書き込みスレッドの本体
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CProgressBar	*pProgressBar	[I] プログレスバークラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		BOOL	TRUE：正常　FALSE：エラー
//*****************************************************************************************************
BOOL CDataFile::WriteBinaryFileThread2(CProgressBar *pProgressBar)
{
	CFile cWFile;
	int	i, nWrite;
	CString str, sText;

	// 書き込みファイルオープン
	if (!cWFile.Open(m_WriteFilePath, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyWrite)) {
		CGeneral::Alert("書き込みファイルが書けません。");
		return FALSE;
	}

// 077FD050-GYM961-0_007 2007/9/8 appended by yG ->
	// CSVファイルの先頭に計測情報を付加する
	str.Format(m_nDataType == DATA_TYPE_MMA ? "TAA ID:%d\n" : "Sensor ID:%d\n", m_nSensorId);
	cWFile.Write(str, str.GetLength());
	str.Format("Time Zero:%s\n", m_dStarttime.GetStr());
	cWFile.Write(str, str.GetLength());
	str.Format("Sampling Rate:%.2f[Hz]\n", m_fSampleRate);
	cWFile.Write(str, str.GetLength());
	if (m_nDataType == DATA_TYPE_MMA) {
		str.Format("Cut Off Freq:%d[Hz]\n", m_nCutoff);
		cWFile.Write(str, str.GetLength());
		str.Format("Gain:%d\n", m_nGain);
		cWFile.Write(str, str.GetLength());
	}
	str.Format("Sensor Position:%s\n", m_SensorPos);
	cWFile.Write(str, str.GetLength());
// 077FD050-GYM961-0_007 2007/9/8 appended by yG <-

	if (m_bTempOnly)
		str = gTitleTemp;
	else {
		switch (m_nDataType) {
		case DATA_TYPE_MMA:
			str = gTitleMma;
			break;
		case DATA_TYPE_MME:
			str = gTitleMme;
			break;
		case DATA_TYPE_ASU:
			str = gTitleAsu;
			break;
		}
	}
	cWFile.Write(str, str.GetLength());
	nWrite = 0;

	// CSVファイルにデータを出力
	if (m_bTempOnly)	// 温度データのみ
	{
		for (i = 0; i < m_nDataSize; i += (m_nDownRate*40)) {
			CDataDateTime Begin;
			Begin.SetTime(m_dStarttime.m_YY
				, m_dStarttime.m_MM
				, m_dStarttime.m_DD
				, (int)((m_nBeginTime + i / m_fSampleRate) * 1000));
			sText = Begin.GetStrYMD();
			sText += CGeneral::FormatTime(m_nBeginTime + i / m_fSampleRate, 4);
			str.Format("%d,%s,%f,%f,%f,%f\n", nWrite + 1
				, sText
				, m_pTempDACM[i]
				, m_pTempX[i]
				, m_pTempY[i]
				, m_pTempZ[i]);
			cWFile.Write(str, str.GetLength());

			if ((nWrite % 10000) == 0) {
				// プログレスバーを更新
				if (!pProgressBar->SetProgress(nWrite * 10 / (m_nDataSize / (m_nDownRate*40)) + 1)) {
					// データファイルをクローズ
					cWFile.Close();
					return FALSE;
				}
			}
			nWrite++;
		}
	}
	else	// 全データ
	{
		for (i = 0; i < m_nDataSize; i += m_nDownRate) {
			CDataDateTime Begin;
			Begin.SetTime(m_dStarttime.m_YY
				, m_dStarttime.m_MM
				, m_dStarttime.m_DD
				, (int)((m_nBeginTime + i / m_fSampleRate) * 1000));
			sText = Begin.GetStrYMD();
			sText += CGeneral::FormatTime(m_nBeginTime + i / m_fSampleRate, 4);
			switch (m_nDataType) {
			case DATA_TYPE_MMA:
				str.Format("%d,%s,%f,%f,%f,%f,%f,%f,%f\n", nWrite + 1
					, sText
					, m_pDataX[i]
					, m_pDataY[i]
					, m_pDataZ[i]
					, m_pTempDACM[i]
					, m_pTempX[i]
					, m_pTempY[i]
					, m_pTempZ[i]);
				break;
			case DATA_TYPE_MME:
			case DATA_TYPE_ASU:
				str.Format("%d,%s,%f,%f,%f\n", nWrite + 1
					, sText
					, m_pDataX[i]
					, m_pDataY[i]
					, m_pDataZ[i]);
				break;
			}
			cWFile.Write(str, str.GetLength());

			if ((nWrite % 10000) == 0) {
				// プログレスバーを更新
				if (!pProgressBar->SetProgress(nWrite * 10 / (m_nDataSize / m_nDownRate) + 1)) {
					// データファイルをクローズ
					cWFile.Close();
					return FALSE;
				}
			}
			nWrite++;
		}
	}
	// データファイルをクローズ
	cWFile.Close();

	pProgressBar->SetProgress(10);

	AfxMessageBox("CSVファイルへの出力が完了しました。",MB_ICONINFORMATION|MB_OK);

	return TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::AllocBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータバッファ確保処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDataFile::AllocBuf()
{
	if (m_nDataSize <= m_nAllocSize)
		return;

	int nAllocSize = (int)(m_nDataSize * m_fAllocFactor);

	// 加速度データバッファ確保
	if (m_bReadAccelData) {
		m_pDataX = ReallocBuf(m_pDataX, m_nAllocSize, nAllocSize);
		m_pDataY = ReallocBuf(m_pDataY, m_nAllocSize, nAllocSize);
		m_pDataZ = ReallocBuf(m_pDataZ, m_nAllocSize, nAllocSize);
	}

	// 温度データバッファ確保
	if (m_bReadTempData) {
		m_pTempDACM = ReallocBuf(m_pTempDACM, m_nAllocSize, nAllocSize);
		m_pTempX = ReallocBuf(m_pTempX, m_nAllocSize, nAllocSize);
		m_pTempY = ReallocBuf(m_pTempY, m_nAllocSize, nAllocSize);
		m_pTempZ = ReallocBuf(m_pTempZ, m_nAllocSize, nAllocSize);
	}

	m_nAllocSize = nAllocSize;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::ReallocBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータバッファ再確保処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		double	*pOldBuf	[I] 確保済みのバッファポインタ
//		int		nOldSize	[I] 確保済みのバッファサイズ
//		int		nNewSize	[I] 新しく確保するバッファサイズ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		double*		新しく確保したバッファポインタ
//*****************************************************************************************************
double *CDataFile::ReallocBuf(const double *pOldBuf, int nOldSize, int nNewSize)
{
	double *pNewBuf = new double[nNewSize];

	if (pOldBuf != NULL) {
		memcpy(pNewBuf, pOldBuf, nOldSize * sizeof(double));
		delete [] pOldBuf;
	}

	return pNewBuf;
}

//*****************************************************************************************************
//  1. 関数名
//		CDataFile::FreeBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータバッファ解放処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CDataFile::FreeBuf()
{
	if (m_pTempDACM != NULL) {
		delete [] m_pTempDACM;
		m_pTempDACM = NULL;
	}

	if (m_pTempX != NULL) {
		delete [] m_pTempX;
		m_pTempX = NULL;
	}

	if (m_pTempY != NULL) {
		delete [] m_pTempY;
		m_pTempY = NULL;
	}

	if (m_pTempZ != NULL) {
		delete [] m_pTempZ;
		m_pTempZ = NULL;
	}

	if (m_pDataX != NULL) {
		delete [] m_pDataX;
		m_pDataX = NULL;
	}

	if (m_pDataY != NULL) {
		delete [] m_pDataY;
		m_pDataY = NULL;
	}

	if (m_pDataZ != NULL) {
		delete [] m_pDataZ;
		m_pDataZ = NULL;
	}
}
