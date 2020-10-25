#pragma once

#include "stdiofileex.h"
#include "rsutbl.h"
#include "sentbl.h"

typedef struct TAG_ST_AD {
//	int	tdacm;					// 2010/9/24 modified by y.GOTO for ver.4.02
	unsigned int	tdacm;		// 2010/9/24 modified by y.GOTO for ver.4.02
	int	tx;
	int	ty;
	int	tz;
	int	x;
	int	y;
	int	z;
} ST_AD;

typedef struct TAG_ST_CALC_DATA{
	double	dacm;
	double	tx;
	double	ty;
	double	tz;
	double	x;
	double	y;
	double	z;
} ST_CALC_DATA;

typedef struct TAG_ST_XYZ{
	double	x;
	double	y;
	double	z;
} ST_XYZ;

class CCalcFile
{
public:
	CCalcFile(void);
	~CCalcFile(void);

public:
	bool Open(LPCSTR fname0, LPCSTR fname1, LPCSTR fname2, LPCSTR fname3);
	void Close(void);
	int ReadOneHour(void);
	// パラメータ引渡し
	bool SetArg(int rsu, int sen, int CntPerHour, int Offset, int Cutoff);
	int CalcData(ST_AD * ad, ST_CALC_DATA * calc);
	void WriteOneHour(LPCSTR fname, long seq);
	long AdjustForNegativeFullscale(long in_lCounts, long a_lSecondPrevious, long a_lFirstPrevious, long a_lFirstNext, long a_lSecondNext);

private:
	// ファイル0ハンドル
	CStdioFileEX m_fp0;
	// ファイル1ハンドル
	CStdioFileEX m_fp1;
	// ファイル2ハンドル
	CStdioFileEX m_fp2;
	// ファイル3ハンドル
	CStdioFileEX m_fp3;
	// RSU定数テーブル
	CRsuTbl m_RsuTbl;
	// センサー定数テーブル
	CSenTbl m_SenTbl;
	double	*m_dacm;
	double	*m_tx;
	double	*m_ty;
	double	*m_tz;
	double	*m_x;
	double	*m_y;
	double	*m_z;
	int	m_DataCnt;
	// 1時間データ数
	int m_CntPerHour;
	// オフセット
	int m_Offset;
	// カットオフ周波数
	int m_Cutoff;
	// RSU ID
	int	m_RsuId;
	// Sensor ID
	int	m_SenId;
	// 係数テーブル
	KEISUU_INFO m_Keisuu[3];

// 2008/07/22 appended by yG for adjust a/d converter error ->
	long AdjustForNegativeFullscale(CStdioFileEX* in_pFileHandle, long in_lCounts);
// <- 2008/07/22 appended by yG for adjust a/d converter error
};
