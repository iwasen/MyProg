#include "stdafx.h"
#include "CalcFile.h"
#include "GlobalData.h"

#define	CNT_DLT_HOUR	40	// DACMは1秒1/40分存在。X,Y,Zの場合40倍する必要がある

#define	CH_X	1
#define	CH_Y	2
#define	CH_Z	3
#define	DATA_X	0
#define	DATA_Y	1
#define	DATA_Z	2

#define	GetAd(x) ((*(BYTE *)x << 16) | *(BYTE *)(x + 1) << 8 | *(BYTE *)(x + 2))

#define	CT_TEMP_VFS		2.048	// Vfs 2.048V
#define	CT_AMP_VFS		8.192	// Vfs 8.192V
#define	CT_TEMP_OFFSET_1	68.35	// 68.35
#define	CT_TEMP_OFFSET_2	273.0	// 273
#define	CT_AMP_OFFSET	293		// 293

#define	CT_VAD_VFS	5.0	// 5V
#define	CT_GP		1	// 1V
#define	CT_GH		0.01	// 0.01V/μA

#define	CT_24BIT	16777216	// 2^24
#define	CT_16BIT	65536	// 2^16

CCalcFile::CCalcFile(void)
{
}

CCalcFile::~CCalcFile(void)
{
}

// パラメータ引渡し
bool CCalcFile::SetArg(int rsu, int sen, int CntPerHour, int Offset, int Cutoff)
{
	// RSU ID
	m_RsuId = rsu;
	// Sensor ID
	m_SenId = sen;
	// 1時間データ数
	m_CntPerHour = CntPerHour / CNT_DLT_HOUR;
	// オフセット
	m_Offset = Offset;
	// カットオフ周波数
	m_Cutoff = Cutoff;

	// RSU係数テーブル生成
	if (! m_RsuTbl.SetTbl())
		return false;
	// SENSOR係数テーブル生成
	if (! m_SenTbl.SetTbl())
		return false;

	memset(m_Keisuu, 0, sizeof(m_Keisuu));
	// RSU情報から係数を取得する
	if (! m_RsuTbl.GetTbl(m_RsuId, CH_X, m_Cutoff, &m_Keisuu[DATA_X]))
		return false;
	if (! m_RsuTbl.GetTbl(m_RsuId, CH_Y, m_Cutoff, &m_Keisuu[DATA_Y]))
		return false;
	if (! m_RsuTbl.GetTbl(m_RsuId, CH_Z, m_Cutoff, &m_Keisuu[DATA_Z]))
		return false;
	// Sensor情報から係数を取得する
	if (! m_SenTbl.GetTbl(m_SenId, CH_X, &m_Keisuu[DATA_X]))
		return false;
	if (! m_SenTbl.GetTbl(m_SenId, CH_Y, &m_Keisuu[DATA_Y]))
		return false;
	if (! m_SenTbl.GetTbl(m_SenId, CH_Z, &m_Keisuu[DATA_Z]))
		return false;

	return true;

}

// データファイル4種類OPEN
bool CCalcFile::Open(LPCSTR fname0, LPCSTR fname1, LPCSTR fname2, LPCSTR fname3)
{
	CFileException f0, f1, f2, f3;

	if (! m_fp0.OpenEX((LPCSTR)fname0, CFile::modeRead | CFile::typeBinary, &f0)) {
		return false;
	}
	if (! m_fp1.OpenEX((LPCSTR)fname1, CFile::modeRead | CFile::typeBinary, &f1)) {
		m_fp0.CloseEX();
		return false;
	}
	if (! m_fp2.OpenEX((LPCSTR)fname2, CFile::modeRead | CFile::typeBinary, &f2)) {
		m_fp0.CloseEX();
		m_fp1.CloseEX();
		return false;
	}
	if (! m_fp3.OpenEX((LPCSTR)fname3, CFile::modeRead | CFile::typeBinary, &f3)) {
		m_fp0.CloseEX();
		m_fp1.CloseEX();
		m_fp2.CloseEX();
		return false;
	}
	return true;
}

// データファイル4種類CLOSE
void CCalcFile::Close(void)
{
	m_fp0.CloseEX();
	m_fp1.CloseEX();
	m_fp2.CloseEX();
	m_fp3.CloseEX();
}



// データファイル4種類1時間分読み込む
int CCalcFile::ReadOneHour(void)
{
	char	buf0[4][3], buf1[3], buf2[3], buf3[3];
	ST_AD ad;
	ST_CALC_DATA	calc;
	int	i, j, rt;

	// 保存エリアクリア
	m_DataCnt = 0;
	m_dacm = new double[m_CntPerHour * CNT_DLT_HOUR];
	m_tx = new double[m_CntPerHour * CNT_DLT_HOUR];
	m_ty = new double[m_CntPerHour * CNT_DLT_HOUR];
	m_tz = new double[m_CntPerHour * CNT_DLT_HOUR];
	m_x = new double[m_CntPerHour * CNT_DLT_HOUR];
	m_y = new double[m_CntPerHour * CNT_DLT_HOUR];
	m_z = new double[m_CntPerHour * CNT_DLT_HOUR];

	rt = 1;
	// 1時間分読む
	for(i = 0; i < m_CntPerHour; i++) {

		if (m_fp0.ReadEX(buf0, 12) == 0) {
			rt = 0;
			break;
		}
		for (j = 0; j < CNT_DLT_HOUR; j++) {
			m_fp1.ReadEX(buf1, 3);
			m_fp2.ReadEX(buf2, 3);
			m_fp3.ReadEX(buf3, 3);

			ad.tdacm = GetAd(buf0[0]);
			ad.tx = GetAd(buf0[1]);
			ad.ty = GetAd(buf0[2]);
			ad.tz = GetAd(buf0[3]);
			ad.x = GetAd(buf1);
			ad.y = GetAd(buf2);
			ad.z = GetAd(buf3);

// 2008/07/22 appended by yG for adjust a/d converter error ->
			if (ad.x == 0x800000)
			{
				ad.x = AdjustForNegativeFullscale(&m_fp1, ad.x);
			}
			if (ad.y == 0x800000)
			{
				ad.y = AdjustForNegativeFullscale(&m_fp2, ad.y);
			}
			if (ad.z == 0x800000)
			{
				ad.z = AdjustForNegativeFullscale(&m_fp3, ad.z);
			}
// <- 2008/07/22 appended by yG for adjust a/d converter error

			// 工学値変換
			CalcData(&ad, &calc);
			m_dacm[m_DataCnt] = calc.dacm;
			m_tx[m_DataCnt] = calc.tx;
			m_ty[m_DataCnt] = calc.ty;
			m_tz[m_DataCnt] = calc.tz;
			m_x[m_DataCnt] = calc.x;
			m_y[m_DataCnt] = calc.y;
			m_z[m_DataCnt] = calc.z;
			m_DataCnt++;
		}
	}

	return rt;
}

// データファイル1時間分書き込む
void CCalcFile::WriteOneHour(LPCSTR fname, long seq)
{
	CStdioFile	fp;
	CFileException f;
	long	cnt, total;
	char buf[16], buf2[56];
	int	i;

	if (fp.Open(fname, CFile::modeCreate | CFile::modeWrite | CFile::typeBinary, &f) != 0) {
		// ヘッダ書き込み
		cnt = m_DataCnt;
		total = m_CntPerHour * CNT_DLT_HOUR;
		memset(buf, 0, sizeof(buf));
		memcpy(buf, &seq, sizeof(long));
		memcpy(&buf[4], &cnt, sizeof(cnt));
		memcpy(&buf[8], &total, sizeof(total));
		fp.Write(buf, sizeof(buf));

		for (i = 0; i < cnt; i++) {
			memcpy(buf2, &m_dacm[i], sizeof(double));
			memcpy(&buf2[8], &m_tx[i], sizeof(double));
			memcpy(&buf2[16], &m_ty[i], sizeof(double));
			memcpy(&buf2[24], &m_tz[i], sizeof(double));
			memcpy(&buf2[32], &m_x[i], sizeof(double));
			memcpy(&buf2[40], &m_y[i], sizeof(double));
			memcpy(&buf2[48], &m_z[i], sizeof(double));
			fp.Write(buf2, sizeof(buf2));
		}
		fp.Close();
	}

	delete [] m_dacm;
	delete [] m_tx;
	delete [] m_ty;
	delete [] m_tz;
	delete [] m_x;
	delete [] m_y;
	delete [] m_z;

	m_DataCnt = 0;
}


// 工学値変換
int CCalcFile::CalcData(ST_AD * ad, ST_CALC_DATA * calc)
{
	ST_XYZ	a, Vad, Vadj, sf, Bias, Vbias;
	double	offset;
	double	Ghx, Ghy, Ghz;
	double	t4, t3, t2, t1;
	double	x4,x3,x2,x1;
	double	y4,y3,y2,y1;
	double	z4,z3,z2,z1;

	// DACM X,Y,Z TEMP　算出
	calc->dacm = ((double)ad->tdacm / CT_24BIT * CT_TEMP_VFS) / 0.01 - CT_TEMP_OFFSET_1;
//--> 2007.09.25 
//	calc->tx = ((double)ad->tx / CT_24BIT * CT_TEMP_VFS) / 0.01 - CT_TEMP_OFFSET_1;
//	calc->ty = ((double)ad->ty / CT_24BIT * CT_TEMP_VFS) / 0.01 - CT_TEMP_OFFSET_1;
//	calc->tz = ((double)ad->tz / CT_24BIT * CT_TEMP_VFS) / 0.01 - CT_TEMP_OFFSET_1;
	calc->tx = ((double)ad->tx / CT_24BIT * CT_AMP_VFS) / 0.01 - CT_TEMP_OFFSET_2;
	calc->ty = ((double)ad->ty / CT_24BIT * CT_AMP_VFS) / 0.01 - CT_TEMP_OFFSET_2;
	calc->tz = ((double)ad->tz / CT_24BIT * CT_AMP_VFS) / 0.01 - CT_TEMP_OFFSET_2;
//<-- 2007.09.25
	t1 = calc->dacm;		// temp
	t2 = t1 * t1;		// temp^2
	t3 = t2 * t1;		// temp^3
	t4 = t3 * t1;		// temp^4
	// Vbias 算出
	Vbias.x = m_Keisuu[DATA_X].B4 * t4
		+ m_Keisuu[DATA_X].B3 * t3
		+ m_Keisuu[DATA_X].B2 * t2
		+ m_Keisuu[DATA_X].B1 * t1
		+ m_Keisuu[DATA_X].B0;

	// Vbias 算出
	Vbias.y = m_Keisuu[DATA_Y].B4 * t4
		+ m_Keisuu[DATA_Y].B3 * t3
		+ m_Keisuu[DATA_Y].B2 * t2
		+ m_Keisuu[DATA_Y].B1 * t1
		+ m_Keisuu[DATA_Y].B0;

	// Vbias 算出
	Vbias.z = m_Keisuu[DATA_Z].B4 * t4
		+ m_Keisuu[DATA_Z].B3 * t3
		+ m_Keisuu[DATA_Z].B2 * t2
		+ m_Keisuu[DATA_Z].B1 * t1
		+ m_Keisuu[DATA_Z].B0;

	// xyz μA 算出
	a.x = (((double)ad->tx / CT_24BIT * CT_AMP_VFS / CT_GP) / CT_GH - CT_AMP_OFFSET);
	a.y = (((double)ad->ty / CT_24BIT * CT_AMP_VFS / CT_GP) / CT_GH - CT_AMP_OFFSET);
	a.z = (((double)ad->tz / CT_24BIT * CT_AMP_VFS / CT_GP) / CT_GH - CT_AMP_OFFSET);
	x1 = a.x;
	x2 = x1 * x1;
	x3 = x2 * x1;
	x4 = x3 * x1;
	y1 = a.y;
	y2 = y1 * y1;
	y3 = y2 * y1;
	y4 = y3 * y1;
	z1 = a.z;
	z2 = z1 * z1;
	z3 = z2 * z1;
	z4 = z3 * z1;


	// OFFSET
	offset = (double)(m_Offset > (CT_16BIT/2-1) ? m_Offset - CT_16BIT : m_Offset) / (CT_16BIT/2) * 1.25;

	// Vad 算出
	Vad.x = (double)(ad->x > (CT_24BIT/2-1) ? ad->x - CT_24BIT : ad->x)
		 / CT_24BIT * CT_VAD_VFS / CT_GP - Vbias.x - offset;
	Vad.y = (double)(ad->y > (CT_24BIT/2-1) ? ad->y - CT_24BIT : ad->y)
		 / CT_24BIT * CT_VAD_VFS / CT_GP - Vbias.y - offset;
	Vad.z = (double)(ad->z > (CT_24BIT/2-1) ? ad->z - CT_24BIT : ad->z)
		 / CT_24BIT * CT_VAD_VFS / CT_GP - Vbias.z - offset;

	// Vadj算出
	Vadj.x = (m_Keisuu[DATA_X].E4 * Vad.x*Vad.x*Vad.x*Vad.x
		+ m_Keisuu[DATA_X].E3 * Vad.x*Vad.x*Vad.x
		+ m_Keisuu[DATA_X].E2 * Vad.x*Vad.x
		+ m_Keisuu[DATA_X].E1 * Vad.x
		+ m_Keisuu[DATA_X].E0) * Vad.x;
	Vadj.y = (m_Keisuu[DATA_Y].E4 * Vad.y*Vad.y*Vad.y*Vad.y
		+ m_Keisuu[DATA_Y].E3 * Vad.y*Vad.y*Vad.y
		+ m_Keisuu[DATA_Y].E2 * Vad.y*Vad.y
		+ m_Keisuu[DATA_Y].E1 * Vad.y
		+ m_Keisuu[DATA_Y].E0) * Vad.y;
	Vadj.z = (m_Keisuu[DATA_Z].E4 * Vad.z*Vad.z*Vad.z*Vad.z
		+ m_Keisuu[DATA_Z].E3 * Vad.z*Vad.z*Vad.z
		+ m_Keisuu[DATA_Z].E2 * Vad.z*Vad.z
		+ m_Keisuu[DATA_Z].E1 * Vad.z
		+ m_Keisuu[DATA_Z].E0) * Vad.z;

	// SF算出
	sf.x = m_Keisuu[DATA_X].C4 * x4
		+ m_Keisuu[DATA_X].C3 * x3
		+ m_Keisuu[DATA_X].C2 * x2
		+ m_Keisuu[DATA_X].C1 * x1
		+ m_Keisuu[DATA_X].C0;
	sf.y = m_Keisuu[DATA_Y].C4 * y4
		+ m_Keisuu[DATA_Y].C3 * y3
		+ m_Keisuu[DATA_Y].C2 * y2
		+ m_Keisuu[DATA_Y].C1 * y1
		+ m_Keisuu[DATA_Y].C0;
	sf.z = m_Keisuu[DATA_Z].C4 * z4
		+ m_Keisuu[DATA_Z].C3 * z3
		+ m_Keisuu[DATA_Z].C2 * z2
		+ m_Keisuu[DATA_Z].C1 * z1
		+ m_Keisuu[DATA_Z].C0;

	g_SF_x = sf.x;
	g_SF_y = sf.y;
	g_SF_z = sf.z;

	// Bias算出
	Bias.x = m_Keisuu[DATA_X].D4 * x4
		+ m_Keisuu[DATA_X].D3 * x3
		+ m_Keisuu[DATA_X].D2 * x2
		+ m_Keisuu[DATA_X].D1 * x1
		+ m_Keisuu[DATA_X].D0;
	Bias.y = m_Keisuu[DATA_Y].D4 * y4
		+ m_Keisuu[DATA_Y].D3 * y3
		+ m_Keisuu[DATA_Y].D2 * y2
		+ m_Keisuu[DATA_Y].D1 * y1
		+ m_Keisuu[DATA_Y].D0;
	Bias.z = m_Keisuu[DATA_Z].D4 * z4
		+ m_Keisuu[DATA_Z].D3 * z3
		+ m_Keisuu[DATA_Z].D2 * z2
		+ m_Keisuu[DATA_Z].D1 * z1
		+ m_Keisuu[DATA_Z].D0;

	g_BIAS_x = Bias.x;
	g_BIAS_y = Bias.y;
	g_BIAS_z = Bias.z;

	// GH算出
	Ghx = m_Keisuu[DATA_X].A4 * t4
		+ m_Keisuu[DATA_X].A3 * t3
		+ m_Keisuu[DATA_X].A2 * t2
		+ m_Keisuu[DATA_X].A1 * t1
		+ m_Keisuu[DATA_X].A0;
	Ghy = m_Keisuu[DATA_Y].A4 * t4
		+ m_Keisuu[DATA_Y].A3 * t3
		+ m_Keisuu[DATA_Y].A2 * t2
		+ m_Keisuu[DATA_Y].A1 * t1
		+ m_Keisuu[DATA_Y].A0;
	Ghz = m_Keisuu[DATA_Z].A4 * t4
		+ m_Keisuu[DATA_Z].A3 * t3
		+ m_Keisuu[DATA_Z].A2 * t2
		+ m_Keisuu[DATA_Z].A1 * t1
		+ m_Keisuu[DATA_Z].A0;

	// μｇ算出
	calc->x = (Vadj.x / Ghx / sf.x - Bias.x / 1000000) * 1000000;
	calc->y = (Vadj.y / Ghy / sf.y - Bias.y / 1000000) * 1000000;
	calc->z = (Vadj.z / Ghz / sf.z - Bias.z / 1000000) * 1000000;

	// debug write by y.G 2009/08/21 ->
	if (calc->x > -252097.0 && calc->x < -252096.0)
	{
		return 0;
	}
	// <- debug write by y.G 2009/08/21

	return 0;
}

// 2008/07/22 appended by yG for adjust a/d converter error ->
#define	NEGATIVE_FULLSCALE					-8388608	// 0x800000
#define	THREE_QUARTERS_NEGATIVE_FULLSCALE	-6291456
long
CCalcFile::AdjustForNegativeFullscale(CStdioFileEX* in_pFileHandle, long in_lCounts)
{
	// 0x800000の場合，A/D Converterの不具合によるnegative full scaleの可能性があるため
	// 前後の値と比較して，不具合かそうでないかを確認する

	// 前後のデータの取得
	char a_caReadBuffer[3];
	memset(a_caReadBuffer, NULL, 3);
	// 2つ前のデータの取得
	in_pFileHandle->GetSecondPrevious(a_caReadBuffer);
	long a_lSecondPrevious = GetAd(a_caReadBuffer);
	// 1つ前のデータの取得
	in_pFileHandle->GetFirstPrevious(a_caReadBuffer);
	long a_lFirstPrevious = GetAd(a_caReadBuffer);
	// 1つ後のデータの取得
	in_pFileHandle->GetFirstNext(a_caReadBuffer);
	long a_lFirstNext = GetAd(a_caReadBuffer);
	// 2つ後のデータの取得
	in_pFileHandle->GetSecondNext(a_caReadBuffer);
	long a_lSecondNext = GetAd(a_caReadBuffer);

	return AdjustForNegativeFullscale(in_lCounts, a_lSecondPrevious, a_lFirstPrevious, a_lFirstNext, a_lSecondNext);
}

long
CCalcFile::AdjustForNegativeFullscale(long in_lCounts, long a_lSecondPrevious, long a_lFirstPrevious, long a_lFirstNext, long a_lSecondNext)
{
	// 24bit signed integerとして扱う
	a_lSecondPrevious	= a_lSecondPrevious > (CT_24BIT/2-1) ? a_lSecondPrevious - CT_24BIT : a_lSecondPrevious;
	a_lFirstPrevious	= a_lFirstPrevious > (CT_24BIT/2-1) ? a_lFirstPrevious - CT_24BIT : a_lFirstPrevious;
	a_lFirstNext		= a_lFirstNext > (CT_24BIT/2-1) ? a_lFirstNext - CT_24BIT : a_lFirstNext;
	a_lSecondNext		= a_lSecondNext > (CT_24BIT/2-1) ? a_lSecondNext - CT_24BIT : a_lSecondNext;

	if ((a_lSecondPrevious == NEGATIVE_FULLSCALE) &&
		(a_lFirstPrevious == NEGATIVE_FULLSCALE))
	{
		// 前2つが共に0x800000ならば，変更無し
		return in_lCounts;
	}
	else if ((a_lFirstNext == NEGATIVE_FULLSCALE) &&
			 (a_lSecondNext == NEGATIVE_FULLSCALE))
	{
		// 後2つが共に0x800000ならば，変更無し
		return in_lCounts;
	}
	else if ((a_lFirstPrevious == NEGATIVE_FULLSCALE) &&
			 (a_lFirstNext == NEGATIVE_FULLSCALE))
	{
		// 前後1つずつが共に0x800000ならば，変更無し
		return in_lCounts;
	}
	else if (a_lFirstNext == NEGATIVE_FULLSCALE)
	{
		if ((a_lFirstPrevious + a_lSecondNext) < THREE_QUARTERS_NEGATIVE_FULLSCALE)
		{
			// 近くの2つの和がNegativeFullscaleの3/4より小さければ，変更無し
			return in_lCounts;
		}
	}
	else if (a_lFirstPrevious == NEGATIVE_FULLSCALE)
	{
		if ((a_lSecondPrevious + a_lFirstNext) < THREE_QUARTERS_NEGATIVE_FULLSCALE)
		{
			// 近くの2つの和がNegativeFullscaleの3/4より小さければ，変更無し
			return in_lCounts;
		}
	}
	else if ((a_lFirstPrevious + a_lFirstNext) < THREE_QUARTERS_NEGATIVE_FULLSCALE)
	{
		// 前後1つずつの和がNegativeFullscaleの3/4より小さければ，変更無し
		return in_lCounts;
	}
	return 0;	// それ以外のケースの場合，0とする
}
// <- 2008/07/22 appended by yG for adjust a/d converter error