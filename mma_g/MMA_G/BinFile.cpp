// BinFile.cpp: CBinFile クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "BinFile.h"

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define	GetShort(x) ((*(BYTE *)x << 8) | *(BYTE *)(x + 1))
#define	GetLong(x) ((*(BYTE *)x << 24) | (*(BYTE *)(x + 1) << 16) \
					| (*(BYTE *)(x + 2) << 8) | *(BYTE *)(x + 3))

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CBinFile::CBinFile()
: m_fname(_T(""))
{
}

CBinFile::~CBinFile()
{
}

// ファイルOPEN
BOOL CBinFile::OpenBin(LPCSTR fname, BOOL bRetry)
{
	CFileException fileException;

	m_fname = "";
	for (int nRetry = 0; nRetry < 120; nRetry++) {
		if (! m_fp.Open(fname, CFile::modeRead | CFile::typeBinary | CFile::shareDenyNone, &fileException)) {
			TRACE( "Can't open file %s, error = %u\n", fname, fileException.m_cause );
			if (!bRetry)
				break;

			::Sleep(500);
		} else {
			m_fname = fname;
			return TRUE;
		}
	}

	return FALSE;
}

BOOL CBinFile::ReadHeader()
{
	BYTE	buf[128];

	if (! m_fp.Read(buf, 44))
		return FALSE;

	m_from_YY = GetShort(&buf[0]);	// 西暦
	m_from_DD = buf[2];
	m_from_MM = buf[3];
	m_from_SS = GetLong(&buf[4]);
	m_to_YY = GetShort(&buf[8]);	// 西暦
	m_to_DD = buf[10];
	m_to_MM = buf[11];
	m_to_SS = GetLong(&buf[12]);
	m_SensorNo = GetShort(&buf[16]);		// センサーチャンネルナンバー
	m_SampleRate = GetShort(&buf[18]);	// サンプルレート
	m_CutOff = GetShort(&buf[20]);		// フィルターカットオフ
	m_CalGain = GetShort(&buf[22]);		// Cal/Gain
	m_WxxxupTime = GetShort(&buf[24]);	// WxxxupTime
	m_Offset = GetShort(&buf[26]);		// Offset
	m_AcqRate = GetShort(&buf[28]);		// Acq rate
	m_Cnt = GetLong(&buf[30]);		// データ個数
	m_AutoZero = GetShort(&buf[34]);		// AutoZero
	m_SampleIndex = GetLong(&buf[36]);	// SampleIndex
	m_Size = GetLong(&buf[40]);		// データサイズ

	return TRUE;
}

void CBinFile::CloseBin()
{
	m_fp.Close();
}

// ヘッダーの個数分だけ書き込む
int CBinFile::WriteData(CStdioFile &wfp)
{
	char *p;
	int	len, readcnt, totalcnt;

	ReadHeader();

	readcnt = m_Size * 3;

	p = new char [readcnt];

	totalcnt = 0;
	for(;;) {
		if (readcnt <= 0)
			break;
		if ((len = m_fp.Read(p, (UINT)readcnt)) == 0) {
			TRACE("ヘッダ個数よりデータが少ない[%s]%d %d\n", m_fname, m_Size * 3, totalcnt);
			break;
		}
		wfp.Write(p, (UINT)len);
		readcnt -= len;
		totalcnt += len;
	}

	delete [] p;

	return totalcnt;
}

// 指定個数分だけ書き込む
int CBinFile::WriteData(CStdioFile &wfp, int from, int to)
{
	char *p;
	int	len, totalcnt, size, readcnt;

	ReadHeader();

	size = m_Size * 3;

	readcnt = min(to, m_Size) * 3 - from * 3;
	if (readcnt < 0)
		return 0;

	p = new char [size];

	// 開始分シーク
	m_fp.Seek(from * 3, CFile::current); 

	totalcnt = 0;
	for(;;) {
		if (readcnt <= 0)
			break;
		if ((len = m_fp.Read(p, readcnt)) == 0) {
			break;
		}
		wfp.Write(p, len);
		readcnt -= len;
		totalcnt += len;
	}

	delete [] p;

	return totalcnt / 3;
}

// データが存在するだけ書き込む
#define	READ_WRITE_SIZE	(1024*1024)
int CBinFile::WriteDataAll(CStdioFile &wfp)
{
	char *p;
	UINT	len;

	p = new char [READ_WRITE_SIZE];

	ReadHeader();

	for(;;) {
		if ((len = m_fp.Read(p, READ_WRITE_SIZE)) == 0)
			break;
		wfp.Write(p, len);
	}

	delete [] p;

	return 0;
}


int CBinFile::WriteStringHeadData(CStdioFile &wfp)
{
	CString str;

	str.Format("%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%s\n"
		, m_from_YY
		, m_from_DD
		, m_from_MM
		, m_from_SS
		, m_to_YY
		, m_to_DD
		, m_to_MM
		, m_to_SS
		, m_SensorNo
		, m_SampleRate
		, m_CutOff
		, m_CalGain
		, m_WxxxupTime
		, m_Offset
		, m_AcqRate
		, m_Cnt
		, m_AutoZero
		, m_SampleIndex
		, m_Size
		, m_fname);
	wfp.WriteString(str);

	return 0;
}
