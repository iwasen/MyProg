#include "stdafx.h"
#include "StdioFileEX.h"

#define	SIZE_READ_BUF	(1024*1024*13)	// 13M

CStdioFileEX::CStdioFileEX(void)
: m_ReadBuf(NULL)
{
	m_ReadCnt = 0;
	m_CurPos = 0;
	m_ReadBuf = NULL;

// 2008/07/22 appended by yG for adjust a/d converter error ->
	memset(m_caSecondPreviousData, NULL, 3);
	memset(m_caFirstPreviousData, NULL, 3);
	memset(m_caCurrentData, NULL, 3);
// <- 2008/07/22 appended by yG for adjust a/d converter error
}

CStdioFileEX::~CStdioFileEX(void)
{
}

// OPEN処理
BOOL CStdioFileEX::OpenEX(LPCSTR lpszFileName, UINT nOpenFlags, CFileException * pError)
{
	BOOL	rt;

	if ((rt = Open(lpszFileName, nOpenFlags, pError)) != FALSE) {
		// OPEN成功
		m_ReadBuf = new char [SIZE_READ_BUF];
	} else {
		// OPEN失敗
		m_ReadBuf = NULL;
	}
	return rt;
}

// CLOSE処理
void CStdioFileEX::CloseEX(void)
{
	Close();
	if (m_ReadBuf != NULL)
		delete [] m_ReadBuf;
	m_ReadBuf = NULL;
}


// 読み込み処理
UINT CStdioFileEX::ReadEX(void * lpBuf, UINT nCount)
{
	UINT	size, cnt;

	if (m_ReadBuf == NULL)
		return 0;

	// 残データより指定読み込みサイズが多い場合新たに読み込む
	size = m_ReadCnt - m_CurPos;
	if (size < nCount) {
		// 残りデータを先頭にコピー
		if (size > 0)
			memcpy(m_ReadBuf, &m_ReadBuf[m_CurPos], size);
		// 新たにデータを読む
		m_ReadCnt = Read(&m_ReadBuf[size], SIZE_READ_BUF - size);
		// READカウントは読み込みカウント＋コピーしたカウント
		m_ReadCnt += size;
		m_CurPos = 0;
		size = m_ReadCnt - m_CurPos;
	}
	// 読み込み後足りない場合もあるので、再チェック
	if (size >= nCount)
		cnt = nCount;
	else
		cnt = size;

	// 指定読み込みバッファにデータをコピー
	memcpy(lpBuf, &m_ReadBuf[m_CurPos], cnt);

// 2008/07/22 appended by yG for adjust a/d converter error ->
	memcpy(m_caSecondPreviousData, m_caFirstPreviousData, 3);	// 2つ前のデータを更新
	memcpy(m_caFirstPreviousData, m_caCurrentData, 3);			// 1つ前のデータを更新
	memcpy(m_caCurrentData, &m_ReadBuf[m_CurPos], 3);			// 現在のデータ
// <- 2008/07/22 appended by yG for adjust a/d converter error

	m_CurPos += cnt;

	// コピーしたサイズを返値
	return cnt;
}

// 2008/07/22 appended by yG for adjust a/d converter error ->
void
CStdioFileEX::GetSecondPrevious(char* out_caBuffer)
{
	// 本関数は，x，y，z軸データの取得のみを目的とし，
	// データ取得のバッファサイズは，必ず｢3｣であることとする。

	// 2つ前のデータを取得する
	memcpy(out_caBuffer, m_caSecondPreviousData, 3);
}
void
CStdioFileEX::GetFirstPrevious(char* out_caBuffer)
{
	// 本関数は，x，y，z軸データの取得のみを目的とし，
	// データ取得のバッファサイズは，必ず｢3｣であることとする。

	// 1つ前のデータを取得する
	memcpy(out_caBuffer, m_caFirstPreviousData, 3);
}
void
CStdioFileEX::GetFirstNext(char* out_caBuffer)
{
	// 本関数は，x，y，z軸データの取得のみを目的とし，
	// データ取得のバッファサイズは，必ず｢3｣であることとする。

	// 1つ後のデータを取得する
	// 現在のバッファにあるか確認する
	UINT a_ulSize = m_ReadCnt - m_CurPos;
	if (a_ulSize > 3)
	{
		// 現在のバッファにある場合は，
		// リードバッファからコピーする
		memcpy(out_caBuffer, &m_ReadBuf[m_CurPos], 3);
	}
	else
	{
		// 現在のバッファにない場合は，新たに読み込む
		if (a_ulSize > 0)
		{
			// 中途半端な位置の場合，残りデータを先頭にコピー
			memcpy(m_ReadBuf, &m_ReadBuf[m_CurPos], a_ulSize);
		}
		// 新たにデータを読む
		m_ReadCnt = Read(&m_ReadBuf[a_ulSize], SIZE_READ_BUF - a_ulSize);
		// READカウントは読み込みカウント＋コピーしたカウント
		m_ReadCnt += a_ulSize;
		m_CurPos = 0;

		// データが足りない場合は，0とする
		if ((m_ReadCnt - m_CurPos) < 3)
		{
			memset(out_caBuffer, NULL, 3);
		}
		else
		{
			// リードバッファからコピーする
			memcpy(out_caBuffer, &m_ReadBuf[m_CurPos], 3);
		}
	}
}
void
CStdioFileEX::GetSecondNext(char* out_caBuffer)
{
	// 本関数は，x，y，z軸データの取得のみを目的とし，
	// データ取得のバッファサイズは，必ず｢3｣であることとする。

	// 1つ後のデータを取得する
	// 現在のバッファにあるか確認する
	UINT a_ulSize = m_ReadCnt - m_CurPos;
	if (a_ulSize > 6)
	{
		// 現在のバッファにある場合は，
		// リードバッファからコピーする
		memcpy(out_caBuffer, &m_ReadBuf[m_CurPos+3], 3);
	}
	else
	{
		// 現在のバッファにない場合は，新たに読み込む
		// 残りデータを先頭にコピー
		memcpy(m_ReadBuf, &m_ReadBuf[m_CurPos], a_ulSize);
		// 新たにデータを読む
		m_ReadCnt = Read(&m_ReadBuf[a_ulSize], SIZE_READ_BUF - a_ulSize);
		// READカウントは読み込みカウント＋コピーしたカウント
		m_ReadCnt += a_ulSize;
		m_CurPos = 0;

		// データが足りない場合は，0とする
		if ((m_ReadCnt - m_CurPos) < 3)
		{
			memset(out_caBuffer, NULL, 3);
		}
		else
		{
			// リードバッファからコピーする
			memcpy(out_caBuffer, &m_ReadBuf[m_CurPos+3], 3);
		}
	}
}
// <- 2008/07/22 appended by yG for adjust a/d converter error