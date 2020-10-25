// MaiFile.cpp: CMaiFile クラスのインプリメンテーション
//
//////////////////////////////////////////////////////////////////////

#include "stdafx.h"
#include "direct.h"
#include "MaiFile.h"
#include "io.h"
#include "binfile.h"
#include "calcfile.h"
#include "idbfile.h"
#include "GlobalData.h"
#include "General.h"
#include "SelectRsu.h"
#include "SelectStartTimeDlg.h"
#include "MMA_G.h"
#include <math.h>

#ifdef _DEBUG
#undef THIS_FILE
static char THIS_FILE[]=__FILE__;
#define new DEBUG_NEW
#endif

#define	WORK_PATH		"\\temp"
#define	FN_DAT0	"data0.dat"
#define	FN_DAT1	"data1.dat"
#define	FN_DAT2	"data2.dat"
#define	FN_DAT3	"data3.dat"

static	int cmpfunc(const void *prm1, const void *prm2);
static	unsigned long GetMSecFromFileName(LPCSTR fname);

//////////////////////////////////////////////////////////////////////
// 構築/消滅
//////////////////////////////////////////////////////////////////////

CMaiFile::CMaiFile()
: m_maiFname(_T(""))
, m_WorkPath(_T(""))
, m_ConvDataPath(_T(""))
, m_CalcFile(NULL)
{
	CString	str;

	str = gRootPath;
	_mkdir((LPCSTR)str);
	str = gRootData;
	_mkdir((LPCSTR)str);

	m_WorkPath.Format("%s%s", gRootPath, WORK_PATH);	// 結合ファイル書き込みフォルダ
	_mkdir((LPCSTR)m_WorkPath);

	m_ConvDataPath = ".";
}

CMaiFile::~CMaiFile()
{

}

// 工学値データ書き込みフォルダ作成
int CMaiFile::mkDir(LPCSTR pos, LPCTSTR category, LPCTSTR measurement_kind)
{
	CString	str;
	str.Format("%s\\%s", gRootData, pos);
	// 位置フォルダ作成
	_mkdir(str);

	// カテゴリフォルダ作成
	CString str1;
	str1.Format("%s\\%s", str, category);
	_mkdir(str1);

	// 計測種類フォルダ作成
	CString str2;
	str2.Format("%s\\%s", str1, measurement_kind);
	_mkdir(str2);

	CString	str3;
	str3.Format("%s\\%s", str2, m_maiFname);
	// maiファイル名フォルダ作成
	if(_mkdir(str3)){
		if(errno == EEXIST){
			if(AfxMessageBox("この変換データは既に存在します。このまま変換処理を行うと，既存データが上書きされます。\n変換処理を継続しますか？",MB_ICONQUESTION|MB_YESNO) == IDNO){
				return -1;
			}
		}
	}

	// 工学値書き込みフォルダ保存
	m_ConvDataPath = str3;

	return 0;
}

// データリセット
void CMaiFile::ResetData(void)
{
	m_Name.RemoveAll();		// 指定フォルダに存在する全てのmaiファイル名保存エリア
	m_Rsu.RemoveAll();	// maiファイル名のセンサ情報保存エリア
	m_BinName0.RemoveAll();	// チャンネルNo0のBINファイル名保存エリア
	m_BinName1.RemoveAll();	// チャンネルNo1のBINファイル名保存エリア
	m_BinName2.RemoveAll();	// チャンネルNo2のBINファイル名保存エリア
	m_BinName3.RemoveAll();	// チャンネルNo3のBINファイル名保存エリア
	m_maiDat.RemoveAll();	// maiファイルの内容を全て読み込むエリア
	m_Samples0.RemoveAll();
	m_Samples1.RemoveAll();
	m_Samples2.RemoveAll();
	m_Samples3.RemoveAll();
}



// 指定フォルダ内に存在するmaiファイル名を全て取得する
int CMaiFile::GetMaiFileName(LPCSTR path)
{
	char wpath[MAX_PATH];
	struct _finddata_t c_file;
    intptr_t hFile;

	m_Name.RemoveAll();
	memset(wpath, 0, sizeof(wpath));
	strcpy_s(wpath, path);
	strcat_s(wpath, "\\*.mai");

    /* カレント ディレクトリ内の最初の .mai ファイルを探します。*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		m_Name.Add(c_file.name);
		/* 残りの .maiファイルを探します。 */
		while( _findnext( hFile, &c_file ) == 0 ) {
			m_Name.Add(c_file.name);
		}
		_findclose( hFile );
	}
	return (int)m_Name.GetSize();
}

// update 2008/03/18 by hama　→
// 取得したmaiファイル名からセンサNoを取得する
int CMaiFile::GetRsuFromMaiFileName(void)
{
	int	i, cnt;
	char buf[128], *token, *nexttoken;
	char *sep = "-_.";

	m_Rsu.RemoveAll();

	// maiファイル数取得
	if ((cnt = (int)m_Name.GetSize()) != 0) {
		// maiファイル数分ループ
		for (i = 0; i < cnt; i++) {
			strcpy_s(buf, (LPCSTR)m_Name.GetAt(i));
			// トークン取得
			if ((token = strtok_s( buf, sep, &nexttoken)) != NULL) {
				// センサNoを取得済か判定
				if (! IsRsu(token)) {
					// ファイル名の最初の項目を取得
					m_Rsu.Add(token);
				}
			}
		}
	}
	return (int)m_Rsu.GetSize();
}

// センサNoを取得済か判定
bool CMaiFile::IsRsu(LPCSTR rsu)
{
	int	cnt = (int)m_Rsu.GetSize();
	int	i;

	for (i = 0; i < cnt; i++) {
		if (strcmp(rsu, (LPCSTR)m_Rsu.GetAt(i)) == 0)
			return true;
	}
	return false;
}
// update 2008/03/18 by hama　←

// 取得したmaiファイル名の中でMAXの数字を持ったファイルを探す
bool CMaiFile::GetMaxMaiFileName(CString &fname, CString RsuNo)
{
	int	i, cnt, cntToken;
	unsigned long	l_max, value;	
	char buf[128], *token, *nexttoken, tokenDat[64];
	char *sep = "-_.";
	CString str = "";

	l_max = 0;
	// maiファイル数取得
	if ((cnt = (int)m_Name.GetSize()) == 0)
		return false;

	// maiファイル数分ループ
	for (i = 0; i < cnt; i++) {
		cntToken = 0;
		memset(tokenDat, 0, sizeof(tokenDat));
		strcpy_s(buf, (LPCSTR)m_Name.GetAt(i));
		// トークン取得
		token = strtok_s(buf, sep, &nexttoken);
// update 2008/03/18 by hama　→
		if (strcmp(token, RsuNo) != 0)
			continue;
// update 2008/03/18 by hama　←
		while (token != NULL) {
			cntToken++;
			// ファイル名の最後の項目取得？
			if (cntToken > 8) {
				// ファイル名の最後の項目の月日以降取得
				strcpy_s(tokenDat, token+4);
				value = (unsigned long)atoi(tokenDat);
				// MAX値判定
				if (l_max < value) {
					l_max = value;
					str = m_Name.GetAt(i);
				}
				break;
			}
			/* "string" にトークンがなくなるまで繰り返します。 */
			/* 次のトークンを取得します。 */
			token = strtok_s( NULL, sep, &nexttoken );
		}
	}
	// MAX値取得できた？
	if (l_max != 0) {
		fname = str;
		return true;
	}
	return false;
}

// maiファイルの内容を読み込む
int CMaiFile::GetMaFileiData(LPCSTR path)
{
	CStdioFile	fp;
	CFileException fileException;

	m_maiDat.RemoveAll();

	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return 0;
	}

	CString	str;
	for(;;) {
		if (! fp.ReadString(str))
			break;
		m_maiDat.Add(str);
	}
	fp.Close();
	return (int)m_maiDat.GetSize();
}

// BINファイル名を0,1,2,3毎に振り分ける
bool CMaiFile::FuriwakeBinFileName(void)
{
	int	i, chNo;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="File";
	char *sep2="Samples";
	CString	Files, Samples;
	long	sample;

	m_BinName0.RemoveAll();
	m_BinName1.RemoveAll();
	m_BinName2.RemoveAll();
	m_BinName3.RemoveAll();
	m_Samples0.RemoveAll();
	m_Samples1.RemoveAll();
	m_Samples2.RemoveAll();
	m_Samples3.RemoveAll();
	m_Total0 = 0;
	m_Total1 = 0;
	m_Total2 = 0;
	m_Total3 = 0;

	for (i = 0; i < cnt;) {
		// maiファイルデータからBINファイル名を取得する
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, Files) != false) {
			// BINファイル名からチャンネルNoを取得する
			chNo = GetChNoFromFName((LPCSTR)Files);
			// チャンネルNo毎に振り分ける
			switch(chNo) {
			case 0:
				// ファイル名セーブ
				m_BinName0.Add(Files);
				// ファイルに含まれるサンプル数取得
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total0 += sample;
					m_Samples0.Add(sample);
					i++;
				}
				break;
			case 1:
				// ファイル名セーブ
				m_BinName1.Add(Files);
				// ファイルに含まれるサンプル数取得
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total1 += sample;
					m_Samples1.Add(sample);
					i++;
				}
				break;
			case 2:
				// ファイル名セーブ
				m_BinName2.Add(Files);
				// ファイルに含まれるサンプル数取得
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total2 += sample;
					m_Samples2.Add(sample);
					i++;
				}
				break;
			case 3:
				// ファイル名セーブ
				m_BinName3.Add(Files);
				// ファイルに含まれるサンプル数取得
				if (GetDataFromMai(m_maiDat.GetAt(i + 1), sep2, Samples) != false) {
					sample = atol((LPCSTR)Samples);
					m_Total3 += sample;
					m_Samples3.Add(sample);
					i++;
				}
				break;
			}
		}
		i++;
	}

	return true;
}

// maiファイルデータから指定項目を取得する
bool CMaiFile::GetDataFromMai(LPCSTR dat, LPCSTR itemStr, CString &oDat)
{
	char buf[128], *token, *nexttoken;
	char tokenDat[2][256];
	char *sep = "=\0";
	CString str = "";
	int	tokenCnt;

	memset(tokenDat, 0, sizeof(tokenDat));
	strcpy_s(buf, dat);
	tokenCnt = 0;
	// トークン取得
	token = strtok_s( buf, sep, &nexttoken);
	while (token != NULL) {
		strcpy_s(tokenDat[tokenCnt], token);
		tokenCnt++;
		if (tokenCnt > 1)
			break;
		token = strtok_s( NULL, sep, &nexttoken);
	}
	if (strcmp(itemStr, tokenDat[0]) != 0)
		return false;

	oDat = tokenDat[1];
	return true;
}

// BINファイル名からチャンネルNoを取得する
int CMaiFile::GetChNoFromFName(LPCSTR fname)
{
	int	cntToken, rtNo;
	char buf[128], *token, *nexttoken, tokenDat[2][10];
	char *sep = "-";

	cntToken = 0;
	memset(tokenDat, 0, sizeof(tokenDat));
	strcpy_s(buf, fname);
	// トークン取得
	token = strtok_s( buf, sep, &nexttoken);
	while( token != NULL ) {
		strcpy_s(tokenDat[cntToken], token);
		cntToken++;
		if (cntToken > 1)
			break;
		/* "string" にトークンがなくなるまで繰り返します。 */
		/* 次のトークンを取得します。 */
		token = strtok_s( NULL, sep, &nexttoken );
	}
	rtNo = atoi(tokenDat[1]);
	return rtNo;
}


// BINファイルの同一ファイル名は削除する
int CMaiFile::DelSameName(void)
{
	int	arrcnt, i, j, isCnt;
	CString str;
	CStringArray *arr;
	CArray<long,long> *arr_l;

	for (arrcnt = 0; arrcnt < 4; arrcnt++) {
		switch(arrcnt) {
		case 0:
			arr = &m_BinName0;
			arr_l = &m_Samples0;
			break;
		case 1:
			arr = &m_BinName1;
			arr_l = &m_Samples1;
			break;
		case 2:
			arr = &m_BinName2;
			arr_l = &m_Samples2;
			break;
		case 3:
			arr = &m_BinName3;
			arr_l = &m_Samples3;
			break;
		}
		// 何件あるか調べる
		for (i = 0; i < arr->GetSize(); i++) {
			str = arr->GetAt(i);
			isCnt = 0;
			for (j = 0; j < arr->GetSize(); ) {
				if (str == arr->GetAt(j)) {
					// 複数ある場合、削除する
					if (isCnt != 0) {
						arr->RemoveAt(j);
						arr_l->RemoveAt(j);
						continue;
					} else {
						isCnt++;
					}
				}
				j++;
			}
		}
	}
	return 0;
}

// BINファイル名をソートする
void CMaiFile::SortArr(void)
{
	int	i, cnt, arrcnt;
	char (*p)[MAX_PATH];
	CStringArray *arr;

	for (arrcnt = 0; arrcnt < 4; arrcnt++) {
		switch(arrcnt) {
		case 0:
			arr = &m_BinName0;
			break;
		case 1:
			arr = &m_BinName1;
			break;
		case 2:
			arr = &m_BinName2;
			break;
		case 3:
			arr = &m_BinName3;
			break;
		}
		cnt = (int)arr->GetSize();
		p = new char[cnt][MAX_PATH];
		memset(p, 0, cnt * MAX_PATH);
		for (i = 0; i < cnt; i++) {
			strcpy_s(p[i], (LPCSTR)arr->GetAt(i));
		}
		qsort(p, cnt, MAX_PATH, cmpfunc);
		arr->RemoveAll();
		for (i = 0; i < cnt; i++) {
			arr->Add(p[i]);
		}
		delete [] p;
	}
}

// BINファイル名のm秒を取得して、大小比較する
static	int cmpfunc(const void *prm1, const void *prm2)
{
	unsigned long p1, p2;

	p1 = GetMSecFromFileName((LPCSTR)prm1);
	p2 = GetMSecFromFileName((LPCSTR)prm2);
	return p1 - p2;
}

// BINファイル名のm秒を取得する
static	unsigned long GetMSecFromFileName(LPCSTR fname)
{
	int	cntToken;
	char buf[128], *token, *nexttoken;
	char *sep = "-_.";
	unsigned long rt;

	rt = 0;
	cntToken = 0;
	strcpy_s(buf, fname);
	// トークン取得
	token = strtok_s( buf, sep, &nexttoken);
	while( token != NULL ) {
		cntToken++;
		// BINファイル名のm秒は10項目目
		if (cntToken >= 10) {
			rt = (unsigned long)atol(token);
			break;
		}
		/* "string" にトークンがなくなるまで繰り返します。 */
		/* 次のトークンを取得します。 */
		token = strtok_s( NULL, sep, &nexttoken );
	}
	return rt;
}

// データ個数を再計算する
void CMaiFile::RecalcTotal(void)
{
	INT_PTR	i, cnt;

	m_Total0 = 0;
	m_Total1 = 0;
	m_Total2 = 0;
	m_Total3 = 0;

	cnt = m_Samples0.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total0 += m_Samples0.GetAt(i);

	cnt = m_Samples1.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total1 += m_Samples1.GetAt(i);

	cnt = m_Samples2.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total2 += m_Samples2.GetAt(i);

	cnt = m_Samples3.GetSize();
	for (i = 0; i < cnt; i++)
		m_Total3 += m_Samples3.GetAt(i);
}

// スタートタイムを取得する
bool CMaiFile::GetStartTime(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Start Time";
	CString	str;
	long YY, MM, DD, mS;
	bool	rt;

	rt = false;
	m_StartYY = 0;	// 開始時刻：年
	m_StartMM = 0;	// 開始時刻：月
	m_StartDD = 0;	// 開始時刻：日
	m_StartMS = 0;	// 開始時刻：m秒

	for (i = 0; i < cnt; i++) {
		// maiファイルデータからスタートタイム文字列を取得する
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// スタートタイム文字列から、YYMMDDmSを取得する
			if (GetStartYMDmS((LPCSTR)str, &YY, &MM, &DD, &mS) != false) {
				m_StartYY = YY;	// 開始時刻：年
				m_StartMM = MM;	// 開始時刻：月
				m_StartDD = DD;	// 開始時刻：日
				m_StartMS = mS;	// 開始時刻：m秒
				rt = true;
				break;
			}
		}
	}

	return rt;
}

// スタートタイム文字列から、YYMMDDmSを取得する
bool CMaiFile::GetStartYMDmS(LPCSTR str, long * YY, long * MM, long * DD, long * mS)
{
	int	cntToken;
	char buf[128], *token, *nexttoken;
	char *sep = " ";

	cntToken = 0;
	strcpy_s(buf, str);
	// トークン取得
	token = strtok_s( buf, sep, &nexttoken);
	while( token != NULL ) {
		// BINファイル名のm秒は10項目目
		switch(cntToken) {
		case 0:
			*YY = atol(token);
			break;
		case 1:
			*MM = atol(token);
			break;
		case 2:
			*DD = atol(token);
			break;
		case 3:
			*mS = atol(token);
			break;
		}
		cntToken++;
		/* "string" にトークンがなくなるまで繰り返します。 */
		/* 次のトークンを取得します。 */
		token = strtok_s( NULL, sep, &nexttoken );
	}
	return (cntToken == 4);
}

// ユニットIDを取得する
bool CMaiFile::GetUnitID(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Unit ID";
	CString	str;
	long	Unit;
	bool	rt;

	rt = false;
	m_RsuId = 0;

	for (i = 0; i < cnt; i++) {
		// maiファイルデータからユニットID文字列を取得する
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// スタートタイム文字列から、YYMMDDmSを取得する
			Unit = strtol((LPCSTR)str, NULL, 0);
			m_RsuId = (Unit & 0xffff);
			m_NetworkId = ((Unit & 0xffff0000) >> 16);
			rt = true;
			break;
		}
	}
	return rt;
}

// センサーIDを取得する
bool CMaiFile::GetSensorID(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Sensor";
	CString	str;
	long	Sensor;
	bool	rt;

	rt = false;
	m_SensorId = 0;

	for (i = 0; i < cnt; i++) {
		// maiファイルデータからユニットID文字列を取得する
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// スタートタイム文字列から、YYMMDDmSを取得する
			Sensor = strtol((LPCSTR)str, NULL, 0);
			m_SensorId = (Sensor & 0xffff);
			rt = true;
			break;
		}
	}
	return rt;
}

// サンプルレートを取得する
bool CMaiFile::GetSampleRate(void)
{
	int	i;
	int	cnt = (int)m_maiDat.GetSize();
	char *sep="Data Sample Rate";
	CString	str;
	long	Rate;
	bool	rt;

	rt = false;
	m_SampleRate = 0;

	for (i = 0; i < cnt; i++) {
		// maiファイルデータからユニットID文字列を取得する
		if (GetDataFromMai(m_maiDat.GetAt(i), sep, str) != false) {
			// スタートタイム文字列から、YYMMDDmSを取得する
			Rate = strtol((LPCSTR)str, NULL, 0);
			m_CntPerHour = Rate;
			m_SampleRate = (double)Rate / 60.0 / 60.0;
			rt = true;
			break;
		}
	}
	return rt;
}

// 指定パスのmaiファイルを読み、開始時刻終了時刻をセットする
int CMaiFile::ReadMaiFile(LPCSTR p_path, CDataDateTime * start, CDataDateTime * end)
{
	CString	path = p_path;
	CString	fname;
	CString	RsuNo;
	int	nRsu;

	// データリセット
	ResetData();

	// maiファイル名を全て取得
	if (GetMaiFileName(path) == 0) {
		CGeneral::Alert("ダウンリンクファイルが存在しません。");
		return 0;
	}

	// 取得したmaiファイル名からセンサNoを取得する
	if ((nRsu = GetRsuFromMaiFileName()) == 0) {
		CGeneral::Alert("ダウンリンクファイルが存在しません。");
		return 0;
	}

// update 2008/03/18 by hama　→
	// 複数センサーがある場合は、センサー選択画面表示
	if (nRsu > 1) {
		CSelectRsu	rsu;
		rsu.SetRsu(&m_Rsu);
		rsu.DoModal();
		rsu.GetRsu(RsuNo);
	} else {
		RsuNo = m_Rsu.GetAt(0);
	}
// update 2008/03/18 by hama　←
/*
	// maiファイル名の内MAX値を含むファイル名を取得
	if (! GetMaxMaiFileName(fname, RsuNo)) {
		CGeneral::Alert("ダウンリンクファイルが存在しません。");
		return 0;
	}
*/
	// 複数の開始時刻を持つデータが存在した場合は、選択画面表示
	{
		CSelectStartTimeDlg dlg;
		fname = dlg.GetMaiFileName(RsuNo, m_Name);
	}

	// maiファイル名保存
	m_maiFname = fname;

	//maiファイルデータを取得
	if (ReadMaiFile2(path + "\\" + fname, TRUE) == 0)
		return 0;

	// maiファイルから各項目を取得
	if (!GetItems())
		return 0;

	// maiファイルの開始時間とデータ数から時間範囲算出
	GetStartEnd(start, end);

	return 1;
}

// maiファイルから各項目を取得
BOOL CMaiFile::GetItems()
{
	// スタートタイムを取得する
	if (! GetStartTime()) {
		CGeneral::Alert("開始時刻が取得できません。");
		return 0;
	}

	// ユニットIDを取得する
	if (! GetUnitID()) {
		CGeneral::Alert("UnitIDが取得できません。");
		return 0;
	}

	// センサーIDを取得する
	if (! GetSensorID()) {
		CGeneral::Alert("SensorIDが取得できません。");
		return 0;
	}

	// サンプルレートを取得する
	if (! GetSampleRate()) {
		CGeneral::Alert("DataSampleRateが取得できません。");
		return 0;
	}

	return 1;
}

// maiファイルの開始時間とデータ数から時間範囲取得
void CMaiFile::GetStartEnd(CDataDateTime *start, CDataDateTime *end)
{
	start->SetTime(m_StartYY, m_StartMM, m_StartDD, m_StartMS);
	*end = *start + (long)(m_Total0 / 4 * 40 / m_SampleRate * 1000);
}

// 指定maiファイルを読む
int CMaiFile::ReadMaiFile2(LPCSTR maiPath, BOOL bDispErrMsg)
{
	if (GetMaFileiData(maiPath) == 0) {
		if (bDispErrMsg)
			CGeneral::Alert("ダウンリンクファイルが存在しません。");
		return 0;
	}

	// BINファイル名を0,1,2,3毎に振り分ける
	FuriwakeBinFileName();

	// 同一ファイル名は削除する
	DelSameName();

	// ファイル名をソートする
	SortArr();

	// データ個数を再計算する
	RecalcTotal();

	return 1;
}

// BINファイルのヘッダ情報をIDBに保存
void CMaiFile::GetBinInfo(LPCSTR fname)
{
	CBinFile binFile;
	if (binFile.OpenBin(fname)) {
		binFile.ReadHeader();
		binFile.CloseBin();
		// ゲイン設定
		m_Idb.SetGain((int)(pow(2.0, (double)(binFile.m_CalGain & 0x0f))));
		// カットオフ周波数
		m_Idb.SetCutoff(binFile.m_CutOff);
		// BIAS（TBD)
		m_Idb.SetBiascoeff(0);
		// スケールファクター（TBD)
		m_Idb.SetScalefactor(0);
		m_Cutofff = binFile.m_CutOff;	// カットオフ周波数
		m_Offset = binFile.m_Offset;	// オフセット
	}
}

// データを出力する（結合ファイル→工学値ファイルまで）
BOOL CMaiFile::WriteData(LPCSTR p_path, long Start_msec, long End_msec)
{
	CProgressBar cProgressBar;
	BOOL	rt;

	m_path = p_path;		// 書き込みフォルダパス
	m_Start_msec = Start_msec;
	m_End_msec = End_msec;

	m_CalcFile = new CCalcFile;

	// BINファイルのヘッダ情報をIDBに保存
	GetBinInfo(m_path+"\\"+m_BinName0.GetAt(0));

	// パラメータ引渡し
	if (! m_CalcFile->SetArg(m_RsuId, m_SensorId, m_CntPerHour, m_Offset, m_Cutofff)) {
		delete m_CalcFile;
		CGeneral::Alert("工学値変換用定数テーブルに不備があります。変換できません。");
		return FALSE;
	}

	// プログレスバーを表示し、別スレッドでファイルを読み込む
	if (cProgressBar.ExecProcess("結合ファイル書き込み中", 10, this, WriteDataThread) == FALSE) {
		delete m_CalcFile;
		return FALSE;
	}

	// プログレスバーを表示し、別スレッドでファイルを読み込む
	rt = cProgressBar.ExecProcess("工学値変換ファイル書き込み中", 10, this, WriteDataThread2);

	delete m_CalcFile;

	return rt;
}

BOOL CMaiFile::WriteDataThread(LPVOID pParam, CProgressBar* pProgressBar)
{
	return ((CMaiFile *)pParam)->WriteKetugouFile(pProgressBar);
}

BOOL CMaiFile::WriteDataThread2(LPVOID pParam, CProgressBar* pProgressBar)
{
	return ((CMaiFile *)pParam)->WriteCalcData(pProgressBar);
}

// 指定時刻のBINファイルを結合して書き込む
BOOL CMaiFile::WriteKetugouFile(CProgressBar* pProgressBar)
{
	CStdioFile dfp;
	CFileException f;
	int	i, j, nBin;
	long Cnt, Total, sa, WriteCnt, zan, from, PTotalCnt, PWCnt;
	CArray<long, long>*smpl;
	CStringArray	*arr;
	long startCnt, endCnt, sv_startCnt, sv_endCnt;
	CString path = m_path;
	CString	fname;

	// 開始時刻の先頭からの個数、終了時刻の先頭からの個数を算出
	sv_startCnt = (long)(m_Start_msec * m_SampleRate / 1000);
	sv_endCnt = (long)(m_End_msec * m_SampleRate / 1000);
	PTotalCnt = (sv_endCnt - sv_startCnt) * 3 + (sv_endCnt - sv_startCnt) / 10;
	PWCnt = 0;

	// 0-3、4ファイル分ループ
	for (i = 0; i < 4; i++) {
		switch(i) {
		case 0:
			startCnt = sv_startCnt / 10;
			endCnt = sv_endCnt / 10;
			arr = &m_BinName0;
			smpl = &m_Samples0;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT0);
			break;
		case 1:
			startCnt = sv_startCnt;
			endCnt = sv_endCnt;
			arr = &m_BinName1;
			smpl = &m_Samples1;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT1);
			break;
		case 2:
			startCnt = sv_startCnt;
			endCnt = sv_endCnt;
			arr = &m_BinName2;
			smpl = &m_Samples2;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT2);
			break;
		case 3:
			startCnt = sv_startCnt;
			endCnt = sv_endCnt;
			arr = &m_BinName3;
			smpl = &m_Samples3;
			fname.Format("%s\\%s", m_WorkPath, FN_DAT3);
			break;
		}
		Total = 0;
		WriteCnt = 0;
		zan = endCnt - startCnt;
		// 結合ファイル1OPEN
		if (dfp.Open((LPCSTR)fname, CFile::modeCreate | CFile::modeWrite | CFile::typeBinary, &f)) {
			// 件数カウント総数取得
			nBin = (int)smpl->GetSize();
			for (j = 0; j < nBin; j++) {
				// 本ファイルの個数取得
//				Cnt = smpl->GetAt(i);
				Cnt = smpl->GetAt(j);
				// 現総数算出
				Total += Cnt;
				// 書き込み数が終了個数より大きければ終了
				if (WriteCnt >= endCnt)
					break;
				// データ総数が開始個数より少なければ次
				if (Total < startCnt)
					continue;
				// 総数と開始個数との差を求める
				sa = Total - startCnt;
				// 本ファイル個数より差が大きければ全て書く
				if (sa >= Cnt)
					from = 0;
				else
					from = Cnt - sa;

				CBinFile binFile;
				if (binFile.OpenBin(path+"\\"+arr->GetAt(j))) {
					int	cnt;
					cnt = binFile.WriteData(dfp, from, zan);
					WriteCnt += cnt;
					PWCnt += cnt;
					binFile.CloseBin();
				} else {
					CString	str;
					str.Format("データ不整合です。\nbinファイル[%s]が存在しませんので中止します。", arr->GetAt(j));
					CGeneral::Alert(str);
					return FALSE;
				}
				zan = endCnt - WriteCnt;
				if (!pProgressBar->SetProgress(PWCnt * 10 / PTotalCnt)) {
					// データファイルをクローズ
					dfp.Close();
					return FALSE;
				}
			}
			dfp.Close();
		}
	}
	return TRUE;
}

BOOL CMaiFile::WriteCalcData(CProgressBar* pProgressBar)
{
	int	i;
	long	msecCount, period;
	char	c;

	CString	fn0, fn1, fn2, fn3;
	fn0.Format("%s\\%s", m_WorkPath, FN_DAT0);
	fn1.Format("%s\\%s", m_WorkPath, FN_DAT1);
	fn2.Format("%s\\%s", m_WorkPath, FN_DAT2);
	fn3.Format("%s\\%s", m_WorkPath, FN_DAT3);

	CDataDateTime TimeZero, to;
	TimeZero = m_In_startTime;
	to = TimeZero;
	// データ時間範囲を算出（msec)
	period = m_End_msec - m_Start_msec;

	// 結合ファイルを工学値変換し1時間毎のファイルに書き込む
	if (m_CalcFile->Open((LPCSTR)fn0, (LPCSTR)fn1, (LPCSTR)fn2, (LPCSTR)fn3)) {
		CString	str, str2, fullpath;
		// 何時間存在するか算出
		int	nHour = period / 3600000 + ((period % 3600000) != 0);
		// 存在する時間数分ループ
		for (i = 0; i < nHour; i++) {
			msecCount = 0;
			if (period >= (i+1) * 3600000)
				msecCount = 3600000;
			else
				msecCount = (period - i * 3600000);
			// 結合ファイルファイル名編集
			to += msecCount;
			if (i == 0) {
				// 初回は'-'マイナス
				c = '-';
			} else {
				// 初回以外は'+'プラス
				c = '+';
			}
// 2008/5/13 modified by yG for sensor id format (decimal to hex) ->
//			str.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d%c%04d_%02d_%02d_%02d_%02d_%02d.%03d.%04d"
			str.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d%c%04d_%02d_%02d_%02d_%02d_%02d.%03d.%04x"
// <- 2008/5/13 modified by yG for sensor id format (decimal to hex)
				, TimeZero.m_YY
				, TimeZero.m_MM
				, TimeZero.m_DD
				, TimeZero.m_hh
				, TimeZero.m_mm
				, TimeZero.m_ss
				, TimeZero.m_ttt
				, c
				, to.m_YY
				, to.m_MM
				, to.m_DD
				, to.m_hh
				, to.m_mm
				, to.m_ss
				, to.m_ttt
				, m_SensorId);
//			str.Format("%04d%04d.dat", m_RsuId, i+1);
			str2 = str;

			fullpath.Format("%s\\%s", m_ConvDataPath, str);
			m_CalcFile->ReadOneHour();
			m_CalcFile->WriteOneHour((LPCSTR)fullpath, i + 1);

			m_Idb.AddFile((LPCSTR)str);
			// タイムゼロ
			str.Format("%04d_%02d_%02d_%02d_%02d_%02d.%03d"
				, TimeZero.m_YY
				, TimeZero.m_MM
				, TimeZero.m_DD
				, TimeZero.m_hh
				, TimeZero.m_mm
				, TimeZero.m_ss
				, TimeZero.m_ttt);
			m_Idb.SetTimeZeroXML(str);
			m_Idb.WriteXml(str2, DATA_TYPE_MMA);
			TimeZero += 3600000;
			// プログレスバーを更新
			if (!pProgressBar->SetProgress((i + 1) * 10 / nHour)) {
				// データファイルをクローズ
				m_CalcFile->Close();
				return FALSE;
			}
		}
		m_CalcFile->Close();
		str.Format("%04d%04d%02d%02d%d", m_RsuId, m_StartYY, m_StartMM, m_StartDD, m_StartMS);
		m_Idb.Write(str);
	}

	return TRUE;
}
