//*****************************************************************************************************
//  1. ファイル名
//		RealtimeData.cpp
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイム表示クラスの実装
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2008.12.01 S.Aizawa 新規作成
//*****************************************************************************************************

#include "StdAfx.h"
#include "MMA_G.h"
#include "RealtimeData.h"
#include "GlobalData.h"
#include "Define.h"
#include "General.h"
#include "BinFile.h"
#include "DataFile.h"
#include "CalcFile.h"
#include "RsuTaaTbl.h"
#include <shlwapi.h>

// define定義
#define CYCLE_TIME	1000		// リアルタイムデータ監視周期
#define BIN_HEADER_SIZE	44		// バイナリファイルヘッダサイズ

// 生データをintに変換するマクロ
#define	GetAd(x) ((*(BYTE *)(x) << 16) | *(BYTE *)((x) + 1) << 8 | *(BYTE *)((x) + 2))

// 欠損データ
#define LOSS_DATA	-1

// 旧binファイル移動先フォルダ
#define	OLD_BIN_FILE	"OLD_BIN_FILE"

// リアルタイム表示クラスのインスタンス
CRealtimeData g_RealtimeData;

// RSU_IDとTAA_IDの対応テーブル
static CRsuTaaTbl g_RsuTaaTbl;

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinFile::CRealtimeBinFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリファイルクラスのコンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeBinFile::CRealtimeBinFile()
{
	m_nBinDataSize = 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::CRealtimeBinData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータクラスのコンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeBinData::CRealtimeBinData()
{
	m_pBinBuf = NULL;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::~CRealtimeBinData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータクラスのデストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeBinData::~CRealtimeBinData()
{
	// ファイル読み込みバッファ解放
	FreeBuf();
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::FreeBuf
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータ読み込みバッファ解放処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeBinData::FreeBuf()
{
	// ファイル読み込みバッファ解放
	if (m_pBinBuf != NULL) {
		delete [] m_pBinBuf;
		m_pBinBuf = NULL;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::GetBinDataSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータサイズを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		データサイズ
//*****************************************************************************************************
int CRealtimeBinData::GetBinDataSize()
{
	int nDataSize = 0;

	if (m_aBinFile.GetSize() != 0) {
		int nLastIndex = (int)m_aBinFile.GetSize() - 1;
		nDataSize = m_aBinFile[nLastIndex].m_nBinDataOffset + m_aBinFile[nLastIndex].m_nBinDataSize;
	}

	return nDataSize;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::SeekBinFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータファイルを指定された位置にシークする
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nSeekSize		[I] シークする位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeBinData::SeekBinFile(int nSeekSize)
{
	// シークする位置のバイナリファイルを求める
	m_nBinFileIndex = 0;
	int nOffset;
	while (true) {
		nOffset = nSeekSize - m_aBinFile[m_nBinFileIndex].m_nBinDataOffset;
		if (nOffset >= m_aBinFile[m_nBinFileIndex].m_nBinDataSize)
			m_nBinFileIndex++;
		else
			break;
	}

	// バイナリファイルバッファにデータを読み込む
	ReadBinFile(nOffset);

	// 先行遅延読み出しバッファクリア
	memset(m_aPrevNextBuf, 0, sizeof(m_aPrevNextBuf));
	m_bPrevNext = FALSE;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::GetBinData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータファイルから１データを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		取得したデータ
//*****************************************************************************************************
int CRealtimeBinData::GetBinData()
{
	// バッファのデータを読み終わったら次のバイナリファイルを読む
	if (m_nReadPtr >= m_nBufSize) {
		if (m_nBinFileIndex >= m_aBinFile.GetSize() - 1)
			return 0;

		int nCurrentOffset = m_aBinFile[m_nBinFileIndex].m_nBinDataOffset + m_aBinFile[m_nBinFileIndex].m_nBinDataSize;
		ReadBinFile(nCurrentOffset - m_aBinFile[++m_nBinFileIndex].m_nBinDataOffset);
	}

	int nData;
	if (m_nReadPtr >= 0) {
		// バイナリファイルバッファから１データを取り込む
		BYTE *p = m_pBinBuf + m_nReadPtr * 3;
		nData = GetAd(p);
	} else {
		// 欠損ファイルデータを返す
		nData = LOSS_DATA;
	}
	m_nReadPtr++;

	return nData;
}

// 2010/9/24 modified by y.GOTO for ver.4.02 ->
//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::GetBinDataUnsigned
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータファイルから１データを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		unsigned int		取得したデータ
//*****************************************************************************************************
unsigned int CRealtimeBinData::GetBinDataUnsigned()
{
	// バッファのデータを読み終わったら次のバイナリファイルを読む
	if (m_nReadPtr >= m_nBufSize) {
		if (m_nBinFileIndex >= m_aBinFile.GetSize() - 1)
			return 0;

		int nCurrentOffset = m_aBinFile[m_nBinFileIndex].m_nBinDataOffset + m_aBinFile[m_nBinFileIndex].m_nBinDataSize;
		ReadBinFile(nCurrentOffset - m_aBinFile[++m_nBinFileIndex].m_nBinDataOffset);
	}

	unsigned int nData;
	if (m_nReadPtr >= 0) {
		// バイナリファイルバッファから１データを取り込む
		BYTE *p = m_pBinBuf + m_nReadPtr * 3;
		nData = GetAd(p);
	} else {
		// 欠損ファイルデータを返す
		nData = LOSS_DATA;
	}
	m_nReadPtr++;

	return nData;
}
// <- 2010/9/24 modified by y.GOTO for ver.4.02

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::GetAccelData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータファイルから加速度データを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		取得したデータ
//*****************************************************************************************************
int CRealtimeBinData::GetAccelData()
{
	// 初回は先行遅延読み出しバッファに２データを読み込む
	if (!m_bPrevNext) {
		m_bPrevNext = TRUE;
		m_aPrevNextBuf[1] = GetBinData();
		m_aPrevNextBuf[0] = GetBinData();
	}

	// バイナリファイルバッファから１データを取り込む
	int nData = GetBinData();

	// 先行遅延読み出しバッファにセットする
	memmove(m_aPrevNextBuf + 1, m_aPrevNextBuf, sizeof(m_aPrevNextBuf) - sizeof(m_aPrevNextBuf[0]));
	m_aPrevNextBuf[0] = nData;

	return m_aPrevNextBuf[2];
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::GetTempData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータファイルから温度データを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nOffset			[I] データ全体のオフセット位置
//		int		&tdacm			[O] 取得したtdacmデータ -> unsigned intに変更(ver.4.02)
//		int		&tx				[O] 取得したtxデータ
//		int		&ty				[O] 取得したtyデータ
//		int		&tz				[O] 取得したtzデータ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
//void CRealtimeBinData::GetTempData(int nOffset, int &tdacm, int &tx, int &ty, int &tz)		// 2010/9/24 modified by y.GOTO for ver.4.02
void CRealtimeBinData::GetTempData(int nOffset, unsigned int &tdacm, int &tx, int &ty, int &tz)	// 2010/9/24 modified by y.GOTO for ver.4.02
{
	// 40データ毎にデータを読む
	if (nOffset % 40 == 0) {
		m_aTempData[0] = GetBinDataUnsigned();
		m_aTempData[1] = GetBinData();
		m_aTempData[2] = GetBinData();
		m_aTempData[3] = GetBinData();
	}

	// バイナリファイルバッファから温度データを取り込む
	tdacm = m_aTempData[0];
	tx = m_aTempData[1];
	ty = m_aTempData[2];
	tz = m_aTempData[3];
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeBinData::ReadBinFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリデータファイルバッファにデータを読み込む
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		int		nOffset			[I] データのオフセット位置
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeBinData::ReadBinFile(int nOffset)
{
	// バイナリファイルバッファを解放
	FreeBuf();

	// 読み出しポインタを設定
	m_nReadPtr = min(nOffset, 0);
	int nReadOffset = max(nOffset, 0);

	// バイナリファイルバッファを確保
	m_nBufSize = m_aBinFile[m_nBinFileIndex].m_nBinDataSize - nReadOffset;
	m_pBinBuf = new BYTE[m_nBufSize * 3];

	// バイナリファイルをオープン
	CFile oBinFile;
	int nRetry = 0;
	while (true) {
		if (!oBinFile.Open(m_aBinFile[m_nBinFileIndex].m_sPathName, CFile::modeRead | CFile::shareDenyNone)) {
			if (++nRetry == 120) {
				CGeneral::Alert("リアルタイム用バイナリファイルが開けません。(%s)", m_aBinFile[m_nBinFileIndex].m_sPathName);
				throw new CUserException;
			}
			::Sleep(500);
		} else
			break;
	}

	// バイナリファイルからバッファにデータを読み込む（１データは３バイト）
	oBinFile.Seek(BIN_HEADER_SIZE + nReadOffset * 3, CFile::begin);
	oBinFile.Read(m_pBinBuf, m_nBufSize * 3);
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::CRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムグラフクラスのコンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeGraph::CRealtimeGraph()
{
	m_pGraphAccl = NULL;
	m_pGraphTemp = NULL;
	m_nIdbSize = 0;
	m_bUpdateGraph = FALSE;
	m_bExistLossFile = FALSE;
	m_nStartOffset = -1;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::~CRealtimeGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムグラフクラスのデストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeGraph::~CRealtimeGraph()
{
	if (m_pGraphAccl != NULL)
		m_pGraphAccl->DestroyWindow();

	if (m_pGraphTemp != NULL)
		m_pGraphTemp->DestroyWindow();
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::GetIdbName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイルのファイル名を取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		idbファイルのファイル名
//*****************************************************************************************************
CString CRealtimeGraph::GetIdbName()
{
	CString sIdbName;
	sIdbName.Format("%04d%04d%02d%02d%d", m_nRsuId, m_dtStartTime.m_YY, m_dtStartTime.m_MM, m_dtStartTime.m_DD, ((m_dtStartTime.m_hh * 60 + m_dtStartTime.m_mm) * 60 + m_dtStartTime.m_ss) * 1000 + m_dtStartTime.m_ttt);
	return sIdbName;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::GetIdbPathName
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイルのパス名を取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		CString		idbファイルのパス名
//*****************************************************************************************************
CString CRealtimeGraph::GetIdbPathName()
{
	CString sPathName;
	sPathName.Format("%s\\%s.idb", gRealtimeTempPath, GetIdbName());
	return sPathName;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::GetBinDataSize
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		温度、XYZ軸データの最小サイズを取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		int		最小サイズ
//*****************************************************************************************************
int CRealtimeGraph::GetBinDataSize()
{
	// 温度、XYZ軸のデータから最小サイズを取得
	int nDataSize = m_aBinData[0].GetBinDataSize() * 10;
	for (int i = 1; i < 4; i++) {
		if (m_aBinData[i].GetBinDataSize() < nDataSize)
			nDataSize = m_aBinData[i].GetBinDataSize();
	}

	return nDataSize / 40 * 40;		// 温度データの区切りサイズとする
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::MakeIdbFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		idbファイル及び工学値変換済みファイルを作成する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeGraph::MakeIdbFile()
{
	if (GetBinDataSize() > 0) {
		if (m_nStartOffset == -1) {
			m_nStartOffset = m_aBinData[0].m_aBinFile[0].m_nBinDataOffset * 10;
			for (int i = 1; i < 4; i++) {
				if (m_aBinData[i].m_aBinFile[0].m_nBinDataOffset > m_nStartOffset)
					m_nStartOffset = m_aBinData[i].m_aBinFile[0].m_nBinDataOffset;
			}
			m_nStartOffset = (m_nStartOffset + 40 - 1) / 40 * 40;
			m_dtStartTime += (long)(m_nStartOffset / m_aBinData[1].m_aBinFile[0].m_fSampleRate) * 1000;
		}

		// idbファイル名を取得する
		CString sIdbPathName = GetIdbPathName();

		// idbファイルが存在するかチェックする
		if (::PathFileExists(sIdbPathName)) {
			// 存在した場合は情報を読み込む
			CDataFile oDataFile;
			if (!oDataFile.ReadInfoFile(sIdbPathName))
				throw new CUserException;
			m_nIdbSize = m_nStartOffset + oDataFile.m_nTotalDataSize;
		} else
			m_nIdbSize = m_nStartOffset;

		// バイナリデータサイズが工学値変換済みデータより大きければ追加されたデータを工学値変換する
		if (GetBinDataSize() > m_nIdbSize) {
			CIdbFile oIdbFile;

			if (m_nIdbSize != 0) {
				// idbファイルが存在する場合はそれを読み込む
				oIdbFile.Read2(GetIdbPathName());
			}

			// idbファイルから情報を取得する
			CBinFile oBinFile;
			if (oBinFile.OpenBin(m_aBinData[1].m_aBinFile[0].m_sPathName, TRUE)) {
				oBinFile.ReadHeader();
				oBinFile.CloseBin();

				double fSampleRate = 10e6 / (512 * oBinFile.m_SampleRate);
				oIdbFile.SetSampleRate(fSampleRate);
				oIdbFile.SetPath(gRealtimeTempPath);
				oIdbFile.SetGain((int)(pow(2.0, (double)(oBinFile.m_CalGain & 0x0f))));
				oIdbFile.SetCutoff(oBinFile.m_CutOff);
				oIdbFile.SetStartTime(m_dtStartTime.m_YY, m_dtStartTime.m_MM, m_dtStartTime.m_DD, ((m_dtStartTime.m_hh * 60 + m_dtStartTime.m_mm) * 60 + m_dtStartTime.m_ss) * 1000 + m_dtStartTime.m_ttt);
				oIdbFile.SetRsuId(m_nRsuId);
				oIdbFile.SetNetworkid(0);
				oIdbFile.SetSensorId(m_nTaaId);
				oIdbFile.SetSensorIdx(-1);

				m_nCntPerHour = (int)(fSampleRate * 3600);
				m_nCutOff = oBinFile.m_CutOff;
				m_nOffset = oBinFile.m_Offset;
			}

			// 工学値変換を行う
			ConvertFile(oIdbFile);

			// idbファイルに書き込む
			oIdbFile.Write(GetIdbName());

			// グラフ更新フラグをセットする
			m_bUpdateGraph = TRUE;
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeGraph::ConvertFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		工学値変換済みファイルを作成する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CIdbFile	&oIdbFile			[I] idbファイルクラス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeGraph::ConvertFile(CIdbFile &oIdbFile)
{
	int i;
	ST_AD ad;
	ST_CALC_DATA calc;
	CFile oFileCalc;
	INFO_FILE_HEADER sHeader;
	CString sPathName;
	CDataDateTime oTime;

	// 計算パラメータ設定
	CCalcFile oCalcFile;
	if (!oCalcFile.SetArg(m_nRsuId, m_nTaaId, m_nCntPerHour, m_nOffset, m_nCutOff)) {
		CGeneral::Alert("工学値変換用定数テーブルに不備があります。");
		throw new CUserException;
	}

	// 工学値変換済みファイルをオープン
	if (oIdbFile.m_File.GetSize() != 0) {
		sPathName.Format("%s\\%s", gRealtimeTempPath, oIdbFile.m_File[oIdbFile.m_File.GetSize() - 1]);
		if (!oFileCalc.Open(sPathName, CFile::modeReadWrite | CFile::shareDenyNone)) {
			CGeneral::Alert("リアルタイム用工学値変換済みファイルが開けません。(%s)", sPathName);
			throw new CUserException;
		}
		oFileCalc.Read(&sHeader, sizeof(sHeader));
		oFileCalc.Seek(sHeader.nDataSize * sizeof(double) * 7, CFile::current);
	} else {
		memset(&sHeader, 0, sizeof(sHeader));
	}

	// BINファイルをシーク
	m_aBinData[0].SeekBinFile(m_nIdbSize / 40 * 4);
	for (i = 1; i < 4; i++)
		m_aBinData[i].SeekBinFile(m_nIdbSize);

	// 工学値変換処理
	int nDataSize = GetBinDataSize();
	while (m_nIdbSize < nDataSize) {
		// バイナリファイルから温度データ、加速度データを取り出す
		m_aBinData[0].GetTempData(m_nIdbSize, ad.tdacm, ad.tx, ad.ty, ad.tz);
		ad.x = m_aBinData[1].GetAccelData();
		ad.y = m_aBinData[2].GetAccelData();
		ad.z = m_aBinData[3].GetAccelData();

		// エラー値の処置
		if (ad.x == 0x800000)
			ad.x = oCalcFile.AdjustForNegativeFullscale(ad.x, m_aBinData[1].m_aPrevNextBuf[4], m_aBinData[1].m_aPrevNextBuf[3], m_aBinData[1].m_aPrevNextBuf[1], m_aBinData[1].m_aPrevNextBuf[0]);
		if (ad.y == 0x800000)
			ad.y = oCalcFile.AdjustForNegativeFullscale(ad.y, m_aBinData[2].m_aPrevNextBuf[4], m_aBinData[2].m_aPrevNextBuf[3], m_aBinData[2].m_aPrevNextBuf[1], m_aBinData[2].m_aPrevNextBuf[0]);
		if (ad.z == 0x800000)
			ad.z = oCalcFile.AdjustForNegativeFullscale(ad.z, m_aBinData[3].m_aPrevNextBuf[4], m_aBinData[3].m_aPrevNextBuf[3], m_aBinData[3].m_aPrevNextBuf[1], m_aBinData[3].m_aPrevNextBuf[0]);

		// 工学値変換の計算を行う
		oCalcFile.CalcData(&ad, &calc);

		// 欠損ファイルの工学変換値を0にする
		if (ad.x == LOSS_DATA || ad.tx == LOSS_DATA || ad.tdacm == LOSS_DATA) {
			calc.x = 0;
			calc.tx = 0;
			calc.dacm = 0;
			m_bExistLossFile = TRUE;
		}
		if (ad.y == LOSS_DATA || ad.ty == LOSS_DATA || ad.tdacm == LOSS_DATA) {
			calc.y = 0;
			calc.ty = 0;
			calc.dacm = 0;
			m_bExistLossFile = TRUE;
		}
		if (ad.z == LOSS_DATA || ad.tz == LOSS_DATA || ad.tdacm == LOSS_DATA) {
			calc.z = 0;
			calc.tz = 0;
			calc.dacm = 0;
			m_bExistLossFile = TRUE;
		}

		// 工学値変換済みファイルは１時間毎に分割する
		if (sHeader.nDataSize == sHeader.nDataSizeHour) {
			// １時間分を書き込んだらヘッダを書いてクローズする
			if (oFileCalc.m_hFile != CFile::hFileNull) {
				oFileCalc.Seek(0, CFile::begin);
				oFileCalc.Write(&sHeader, sizeof(sHeader));
				oFileCalc.Close();
			}

			// ヘッダの初期化
			sHeader.nSeqNo = (int)oIdbFile.m_File.GetSize() + 1;
			sHeader.nDataSize = 0;
			sHeader.nDataSizeHour = m_nCntPerHour;

			// idbファイルに次の工学値変換済みファイルを登録する
			CString sFileName;
			oTime = m_dtStartTime + 3600000 * (int)oIdbFile.m_File.GetSize();
			sFileName.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d.%04x"
				, oTime.m_YY
				, oTime.m_MM
				, oTime.m_DD
				, oTime.m_hh
				, oTime.m_mm
				, oTime.m_ss
				, oTime.m_ttt
				, m_nTaaId);
			oIdbFile.m_File.Add(sFileName);

			// 工学値変換済みファイルを新規作成する
			sPathName.Format("%s\\%s", gRealtimeTempPath, sFileName);
			if (!oFileCalc.Open(sPathName, CFile::modeCreate | CFile::modeWrite | CFile::shareDenyNone)) {
				CGeneral::Alert("リアルタイム用工学値変換済みファイルが開けません。(%s)", sPathName);
				throw new CUserException;
			}
			oFileCalc.Write(&sHeader, sizeof(sHeader));
		}

		// 工学値変換済みファイルにデータを書き込む
		oFileCalc.Write(&calc, sizeof(calc));

		// データサイズを更新する
		sHeader.nDataSize++;
		m_nIdbSize++;
	}

	// ヘッダを書き込む
	oFileCalc.Seek(0, CFile::begin);
	oFileCalc.Write(&sHeader, sizeof(sHeader));

	// バイナリファイル読み込みバッファを解放する
	for (i = 0; i < 4; i++)
		m_aBinData[i].FreeBuf();
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::CRealtimeData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムデータ処理クラスのコンストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeData::CRealtimeData()
{
	m_bStartFlag = FALSE;
	m_bStopFlag = FALSE;
	m_bLoop = TRUE;

	// ワーカスレッド起動
	m_hThread = AfxBeginThread(RealtimeDataThreadEntry, this, THREAD_PRIORITY_BELOW_NORMAL)->m_hThread;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::~CRealtimeData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムデータ処理クラスのデストラクタ
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
CRealtimeData::~CRealtimeData()
{
	// ワーカスレッドのループを終了させる
	m_bLoop = FALSE;

	// ワーカスレッドスレッドの終了を待つ
	m_oEvent.PulseEvent();
	::WaitForSingleObject(m_hThread, 1000);

	// テンポラリフォルダ内のファイルを削除する
	DeleteTempFiles();
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::Start
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイム表示を開始する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::Start()
{
	// RSU_ID-TAA_ID対応テーブルを読み込む
	if (!g_RsuTaaTbl.SetTbl()) {
		AfxMessageBox("RSU_ID-TAA_ID対応テーブルがありません。", MB_OK | MB_ICONEXCLAMATION);
		return;
	}

	// 既存のbinファイルを移動
	MoveOldBinData();

	// 既存のリアルタイム画面を閉じる
	m_aGraph.RemoveAll();

	// ワーカスレッドでリアルタイム処理を開始する
	m_bStartFlag = TRUE;
	m_bStopFlag = FALSE;
	m_oEvent.PulseEvent();
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::Stop
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイム表示を停止する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::Stop()
{
	// ワーカスレッドのリアルタイム処理を停止する
	m_bStartFlag = FALSE;
	m_bStopFlag = TRUE;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::RealtimeDataThreadEntry
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイム処理スレッドの入り口
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPVOID			pParam			[I] CDataFileクラスへのポインタ
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		UINT	0
//*****************************************************************************************************
UINT CRealtimeData::RealtimeDataThreadEntry(LPVOID pParam)
{
	((CRealtimeData *)pParam)->RealtimeDataThread();

	return 0;
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::RealtimeDataThread
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイム処理スレッドの本体
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::RealtimeDataThread()
{
	while (m_bLoop) {
		// 処理待ち
		m_oEvent.Lock(CYCLE_TIME);

		if (m_bStartFlag) {
			try {
				// バイナリファイルの情報を読み込む
				GetBinFileInfo();

				// idbファイル及び工学値変換済みファイルを作成する
				for (int i = 0; i < m_aGraph.GetSize(); i++)
					m_aGraph[i].MakeIdbFile();

				// グラフウィンドウはメインスレッドで開く
				CWnd *pMainWnd = theApp.m_pMainWnd;
				if (pMainWnd->GetSafeHwnd() != NULL)
					pMainWnd->SendMessage(WM_USER_REALTIME_GRAPH);
			} catch (CUserException *e) {
				// エラーがあった場合はリアルタイム表示を停止する（定周期で何度も表示されるのを避けるため）
				m_bStartFlag = FALSE;
				e->Delete();
			}
		}

		if (m_bStopFlag) {
			DeleteTempFiles();
			m_bStopFlag = FALSE;
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::GetBinFileInfo
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリファイルの情報を取得する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::GetBinFileInfo()
{
	CFileFind FileFind;

	// リアルタイムデータフォルダ内のファイルを検索
	CString sSearchFile;
	sSearchFile.Format("%s\\*.bin", gRealtimeDataPath);
	if (!FileFind.FindFile(sSearchFile)) 
		return; 

	// 検索されたバイナリファイルを処理する
	BOOL bContinue = TRUE;
	while (bContinue) { 
		bContinue = FileFind.FindNextFile();

		// 検索されたバイナリファイルを配列に追加する
		AddBinFile(FileFind.GetFileName(), FileFind.GetFilePath());
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::AddBinFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		バイナリファイルの情報を配列に設定する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CString		&sBinFileName			[I] バイナリファイルのファイル名
//		CString		&sBinPathName			[I] バイナリファイルのパス名
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::AddBinFile(const CString &sBinFileName, const CString &sBinPathName)
{
	int i, j;

	// BINファイル名を分解
	CStringArray saFileNameParts;
	CGeneral::StrSplit(sBinFileName, "-.", saFileNameParts);
	if (saFileNameParts.GetSize() < 5)
		return;

	// BINファイル名からユニット番号と種別番号を取得
	int nRsuId = atoi(saFileNameParts[0]);
	int nKind = atoi(saFileNameParts[1]);
	CString sStartDateTime = saFileNameParts[2] + saFileNameParts[3];
	int nBinDataOffset = atoi(saFileNameParts[4]);
	if (nKind < 0 || nKind > 3)
		return;

	// BINファイルのヘッダを取得
	CBinFile oBinFile;
	if (!oBinFile.OpenBin(sBinPathName, TRUE))
		return;
	oBinFile.ReadHeader();

	// BINファイル情報を配列に追加
	for (i = 0; i < m_aGraph.GetSize(); i++) {
		if (m_aGraph[i].m_nRsuId == nRsuId && m_aGraph[i].m_sStartDateTime == sStartDateTime)
			break;
	}
	if (i == m_aGraph.GetSize()) {
		// RSU IDからTAA IDを求める
		int nTaaId = g_RsuTaaTbl.GetTaaId(nRsuId);
		if (nTaaId == -1) {
			CGeneral::Alert("リアルタイムデータのRSU_IDがRSU_ID-TAA_ID対応テーブルに存在しません。\n(RSU_ID=%d)", nRsuId);
			throw new CUserException;
		}

		// リアルタイムグラフデータを作成する
		CRealtimeGraph oRealtimeGraph;
		oRealtimeGraph.m_nRsuId = nRsuId;
		oRealtimeGraph.m_nTaaId = g_RsuTaaTbl.GetTaaId(nRsuId);
		oRealtimeGraph.m_sStartDateTime = sStartDateTime;

		// 計測日時を分解する
		CStringArray saFileNameParts;
		CGeneral::StrSplit(sStartDateTime, "_", saFileNameParts);
		oRealtimeGraph.m_dtStartTime.SetTime(atoi(saFileNameParts[0]), atoi(saFileNameParts[1]), atoi(saFileNameParts[2]), atoi(saFileNameParts[3]), atoi(saFileNameParts[4]), atoi(saFileNameParts[5]), atoi(saFileNameParts[6]));

		// リアルタイムグラフ配列に追加する
		m_aGraph.Add(oRealtimeGraph);
	}

	// バイナリファイル情報をファイル名順に配列にセットする
	CRealtimeGraph &oRealtimeGraph = m_aGraph[i];
	CRealtimeBinData &oRealtimeBinData = oRealtimeGraph.m_aBinData[nKind];
	for (j = 0; j < oRealtimeBinData.m_aBinFile.GetSize(); j++) {
		if (oRealtimeBinData.m_aBinFile[j].m_nBinDataOffset >= nBinDataOffset)
			break;
	}
	if (j < oRealtimeBinData.m_aBinFile.GetSize() && oRealtimeBinData.m_aBinFile[j].m_nBinDataOffset == nBinDataOffset) {
		oRealtimeBinData.m_aBinFile[j].m_nBinDataSize = oBinFile.m_Size;
	} else {
		CRealtimeBinFile oRealtimeBinFile;
		oRealtimeBinFile.m_sPathName = sBinPathName;
		oRealtimeBinFile.m_nBinDataOffset = nBinDataOffset;
		oRealtimeBinFile.m_nBinDataSize = oBinFile.m_Size;
		oRealtimeBinFile.m_fSampleRate = 10e6 / (512 * oBinFile.m_SampleRate);
		oRealtimeBinData.m_aBinFile.InsertAt(j, oRealtimeBinFile);
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::UpdateGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムデータウィンドウを表示する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::UpdateGraphWindow()
{
	for (int i = 0; i < m_aGraph.GetSize(); i++) {
		CRealtimeGraph &oGraph = m_aGraph[i];

		if (oGraph.m_nIdbSize > 0) {
			// 温度グラフ
			if (oGraph.m_pGraphTemp == NULL) {
				// リアルタイムグラフ画面が表示されていない場合は新規にウィンドウを開く
				CGraphFrame *pGraphFrame = new CGraphFrame;
				if (pGraphFrame->DispRealtimeGraph(oGraph.GetIdbPathName(), GRAPH_TEMP, oGraph.m_bExistLossFile))
					oGraph.m_pGraphTemp = pGraphFrame;
				else {
					delete pGraphFrame;
					m_aGraph.RemoveAt(i);
				}
			} else if (oGraph.m_bUpdateGraph) {
				// リアルタイムグラフ画面が表示されていて更新があった場合はグラフに反映する
				oGraph.m_pGraphTemp->UpdateRealtimeGraph(oGraph.m_bExistLossFile);
			}

			// 加速度グラフ
			if (oGraph.m_pGraphAccl == NULL) {
				// リアルタイムグラフ画面が表示されていない場合は新規にウィンドウを開く
				CGraphFrame *pGraphFrame = new CGraphFrame;
				if (pGraphFrame->DispRealtimeGraph(oGraph.GetIdbPathName(), GRAPH_ACCEL, oGraph.m_bExistLossFile))
					oGraph.m_pGraphAccl = pGraphFrame;
				else {
					delete pGraphFrame;
					m_aGraph.RemoveAt(i);
				}
			} else if (oGraph.m_bUpdateGraph) {
				// リアルタイムグラフ画面が表示されていて更新があった場合はグラフに反映する
				oGraph.m_pGraphAccl->UpdateRealtimeGraph(oGraph.m_bExistLossFile);
			}
		}

		oGraph.m_bUpdateGraph = FALSE;
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::CloseGraphWindow
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		リアルタイムデータウィンドウが手動でクローズされた時の処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		HWND		hWnd			[I] クローズしたウィンドウのハンドル
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::CloseGraphWindow(HWND hWnd)
{
	// グラフウィンドウが手動でクローズした場合はそのポインタをクリアする
	for (int i = 0; i < m_aGraph.GetSize(); i++) {
		CRealtimeGraph &oGraph = m_aGraph[i];
		if (oGraph.m_pGraphAccl != NULL) {
			if (oGraph.m_pGraphAccl->m_hWnd == hWnd) {
				oGraph.m_pGraphAccl = NULL;
				break;
			}
		}
		if (oGraph.m_pGraphTemp != NULL) {
			if (oGraph.m_pGraphTemp->m_hWnd == hWnd) {
				oGraph.m_pGraphTemp = NULL;
				break;
			}
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::DeleteTempFiles
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		テンポラリファイル削除処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::DeleteTempFiles()
{
	if (gRealtimeTempPath[0] != '\n') {
		CFileFind FileFind;

		// テンポラリフォルダ内のidbファイルを検索
		CString sSearchFile;
		sSearchFile.Format("%s\\*.idb", gRealtimeTempPath);
		if(!FileFind.FindFile(sSearchFile)) 
			return; 

		// 検索されたidbファイルを処理する
		BOOL bContinue = TRUE;
		while(bContinue){ 
			bContinue = FileFind.FindNextFile();

			// 検索されたidbファイルを削除する
			DeleteIdbFile(FileFind.GetFilePath());
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::DeleteIdbFile
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		Idbファイル削除処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		LPCTSTR		pIdbFilePath			[I] idbファイルパス
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::DeleteIdbFile(LPCTSTR pIdbFilePath)
{
	CDataFile oDataFile;
	if (oDataFile.ReadInfoFile(pIdbFilePath)) {
		CString sDatPath;
		for (int i = 0; i < oDataFile.m_aDataFileName.GetSize(); i++) {
			// データファイルを削除する
			sDatPath.Format("%s\\%s", oDataFile.m_sDatPath, oDataFile.m_aDataFileName[i]);
			::DeleteFile(sDatPath);
		}
	}

	// idbファイルを削除する
	::DeleteFile(pIdbFilePath);
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::UpdateTempGraph
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		温度グラフ更新処理
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		CGraphFrame		*pGraphAccl			[I] 加速度グラフ
//		double			fTimeDispEnd		[I] 表示範囲
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::UpdateTempGraph(CGraphFrame *pGraphAccl, double fTimeDispEnd)
{
	for (int i = 0; i < m_aGraph.GetSize(); i++) {
		CRealtimeGraph &oGraph = m_aGraph[i];
		if (oGraph.m_pGraphAccl == pGraphAccl) {
			if (oGraph.m_pGraphTemp != NULL)
				oGraph.m_pGraphTemp->SetRealtimeDispEnd(fTimeDispEnd);
		}
	}
}

//*****************************************************************************************************
//  1. 関数名
//		CRealtimeData::MoveOldBinData
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		準リアルタイム開始時に既に存在するbinファイルを移動する
//----------------------------------------------------------------------------------------------------
//  3. パラメータ説明
//		無し
//----------------------------------------------------------------------------------------------------
//  4. 戻り値
//		無し
//*****************************************************************************************************
void CRealtimeData::MoveOldBinData()
{
	CString sOldBinFolder;
	sOldBinFolder.Format("%s\\%s", gRealtimeTempPath, OLD_BIN_FILE);

	// 移動先フォルダ作成
	if (!::PathIsDirectory(sOldBinFolder))
		::CreateDirectory(sOldBinFolder, NULL);

	// binファイルを移動
	CFileStatus fs;
	CString sFindFile;
	CString sMovePath;
	sFindFile.Format("%s\\*", gRealtimeDataPath);
	CFileFind oFileFind;
	BOOL bFind = oFileFind.FindFile(sFindFile);
	while (bFind) {
		bFind = oFileFind.FindNextFile();

		CFile::GetStatus(oFileFind.GetFilePath(), fs);
		if (!(fs.m_attribute & CFile::directory)) {
			sMovePath.Format("%s\\%s", sOldBinFolder, oFileFind.GetFileName());
			if (::PathFileExists(sMovePath))
				::DeleteFile(sMovePath);

			::MoveFile(oFileFind.GetFilePath(), sMovePath);
		}
	}
}
