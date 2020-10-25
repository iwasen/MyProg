#include "stdafx.h"
#include "io.h"
#include "GlobalData.h"
#include "IdbFile.h"
#include "General.h"
#include "MMA_G.h"

static const char *xml_mma[] = {
	"<?xml version=\"1.0\" encoding=\"US-ASCII\"?>\n",
	"<mma_accel>\n",
	"<SensorID>%04X</SensorID>\n",
	"<TimeZero>%s</TimeZero>\n",
	"<Gain>%d</Gain>\n",
	"<SampleRate>%.2lf</SampleRate>\n",
	"<CutoffFreq>%d</CutoffFreq>\n",
	"<GData format=\"binary 64 bit IEEE double little endian\" file=\"%s\"/>\n",
	"<BiasCoeff x=\"%lf\" y=\"%lf\" z=\"%lf\"/>\n",
//	"<SensorCoodinateSystem name=\"%04X\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\"/>\n",
//	"<DataCoordinateSystem name=\"%04X\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\"/>\n",
// 2008/4/25 modified by yG for rpw addition ->
//	"<SensorCoordinateSystem name=\"%04X\" r=\"%2.1lf\" p=\"%2.1lf\" w=\"%2.1lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
//	"<DataCoordinateSystem name=\"%04X\" r=\"%2.1lf\" p=\"%2.1lf\" w=\"%2.1lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
	"<SensorCoordinateSystem name=\"%04X\" r=\"%lf\" p=\"%lf\" w=\"%lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
	"<DataCoordinateSystem name=\"%04X\" r=\"%lf\" p=\"%lf\" w=\"%lf\" x=\"%lf\" y=\"%lf\" z=\"%lf\" comment=\"%s\" time=\"%s\"/>\n",
// <- 2008/4/25 modified by yG for rpw addition
	"<DataQualityMeasure>%s</DataQualityMeasure>\n",
	"<ISSConfiguration>%s</ISSConfiguration>\n",
	"<ScaleFactor x=\"%lf\" y=\"%lf\" z=\"%lf\"/>\n",
	"</mma_accel>\n"
};

static const char *xml_mme[] = {
	"<?xml version=\"1.0\" encoding=\"US-ASCII\"?>\n",
	"<mme_accel>\n",
	"<SensorID>%d</SensorID>\n",
	"<TimeZero>%s</TimeZero>\n",
	"<SampleRate>%g</SampleRate>\n",
	"<GData format=\"binary 64 bit IEEE double little endian\" file=\"%s\"/>\n",
	"<SensorCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"<DataCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"</mme_accel>\n"
};

static const char *xml_asu[] = {
	"<?xml version=\"1.0\" encoding=\"US-ASCII\"?>\n",
	"<asu_accel>\n",
	"<SensorID>%d</SensorID>\n",
	"<TimeZero>%s</TimeZero>\n",
	"<SampleRate>%g</SampleRate>\n",
	"<GData format=\"binary 64 bit IEEE double little endian\" file=\"%s\"/>\n",
	"<SensorCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"<DataCoordinateSystem name=\"%d\" r=\"%f\" p=\"%f\" w=\"%f\" x=\"%f\" y=\"%f\" z=\"%f\" comment=\"%s\" time=\"%s\"/>\n",
	"</asu_accel>\n"
};

CIdbFile::CIdbFile(void)
: m_path(_T(""))
, m_scalefactor(0)
, m_issconfig(_T(""))
, m_dataquality(_T(""))
, m_datacosys(_T(""))
, m_sensorcosys(_T(""))
, m_biascoeff(0)
, m_cutoff(0)
, m_gain(0)
, m_timezero(_T(""))
, m_sensorid(0)
, m_rsuid(0)
, m_downlink(_T(""))
, m_period(_T(""))
, m_samplerate(0)
, m_comment(_T(""))
, m_sCategory(_T(""))
, m_sMeasurementKind(_T(""))
{
}

CIdbFile::~CIdbFile(void)
{
}

// 2009/07/07 Version 3.02 appended by m.hama ->
// XMLファイルを読み込む
bool CIdbFile::GetXmlFileData(LPCSTR path)
{
	CStdioFile	fp;
	CFileException fileException;
	CString buf;

	m_XmlData.RemoveAll();

	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return false;
	}
	for (;;) {
		if (! fp.ReadString(buf))
			break;
		m_XmlData.Add(buf);
	}
	fp.Close();

	return true;
}

// XMLファイルを変更する
bool CIdbFile::WriteXml2(LPCSTR name)
{
	CStdioFile	fp;
	CFileException fileException;
	CString fname, buf, str;
	int	cnt, i;

	fname.Format("%s\\%s.header", m_path, name);

	// 既存のXMLファイルの内容をすべて読みこんでおく
	if (! GetXmlFileData(fname))
		return false;

	// XMLファイルの行数取得
	cnt = (int)m_XmlData.GetSize();

	// XMLファイルの行数分ループ
	for (i = 0; i < cnt; i++) {
		// <GData format行？
		if (m_XmlData.GetAt(i).Mid(1, 12) == "GData format") {
			// 保存パス名
			str = m_path;
			str += "\\";
			str += name;
			buf.Format(xml_mma[i], str);
			m_XmlData.SetAt(i, buf);
		}
		// <SensorCoordinateSystem行？
		if (m_XmlData.GetAt(i).Mid(1, 22) == "SensorCoordinateSystem") {
			buf.Format(xml_mma[i], m_sensorid
				//, SensorInfo[m_SensorIdx].x
				//, SensorInfo[m_SensorIdx].y
				//, SensorInfo[m_SensorIdx].z
		// 2008/4/25 modified by yG for rpw addition ->
		//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
				, g_pSensorInfo[m_SensorIdx].roll
				, g_pSensorInfo[m_SensorIdx].pitch
				, g_pSensorInfo[m_SensorIdx].yaw
		// <- 2008/4/25 modified by yG for rpw addition
				, g_pSensorInfo[m_SensorIdx].x
				, g_pSensorInfo[m_SensorIdx].y
				, g_pSensorInfo[m_SensorIdx].z
				, m_sensorcosys
				, gLaunchTime);
				m_XmlData.SetAt(i, buf);
		}
		// <DataCoordinateSystem行？
		if (m_XmlData.GetAt(i).Mid(1, 20) == "DataCoordinateSystem") {
			buf.Format(xml_mma[i], m_sensorid
				//, SensorInfo[m_SensorIdx].x
				//, SensorInfo[m_SensorIdx].y
				//, SensorInfo[m_SensorIdx].z
		// 2008/4/25 modified by yG for rpw addition ->
		//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
				, g_pSensorInfo[m_SensorIdx].roll
				, g_pSensorInfo[m_SensorIdx].pitch
				, g_pSensorInfo[m_SensorIdx].yaw
		// <- 2008/4/25 modified by yG for rpw addition
				, g_pSensorInfo[m_SensorIdx].x
				, g_pSensorInfo[m_SensorIdx].y
				, g_pSensorInfo[m_SensorIdx].z
				, m_datacosys
				, gLaunchTime);
				m_XmlData.SetAt(i, buf);
		}
		// <DataQualityMeasure行？
		if (m_XmlData.GetAt(i).Mid(1, 18) == "DataQualityMeasure") {
			// Data Quality
			buf.Format(xml_mma[i], m_dataquality);
			m_XmlData.SetAt(i, buf);
		}
		// <ISSConfiguration行？
		if (m_XmlData.GetAt(i).Mid(1, 16) == "ISSConfiguration") {
			// ISS Config
			buf.Format(xml_mma[i], m_issconfig);
			m_XmlData.SetAt(i, buf);
		}
	}

	if (! fp.Open(fname, CFile::modeCreate | CFile::modeWrite, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", fname, fileException.m_cause );
		return false;
	}

	for (i = 0; i < cnt; i++) {
		fp.WriteString(m_XmlData.GetAt(i));
		if (m_XmlData.GetAt(i).Right(1) != "\n")
			fp.WriteString("\n");
	}
	fp.Close();

	return true;
}
// <- 2009/07/07 Version 3.02 appended by m.hama

// XMLファイルを書き込む
bool CIdbFile::WriteXml(LPCSTR name, int nDataType)
{
	CStdioFile	fp;
	CFileException fileException;
	CString fname;

//	fname = m_path + "\\" + name + ".heder.xml";
	fname.Format("%s\\%s.header", m_path, name);
	if (! fp.Open(fname, CFile::modeCreate | CFile::modeWrite, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", fname, fileException.m_cause );
		return false;
	}

	switch (nDataType) {
	case DATA_TYPE_MMA:
		WriteXmlMma(fp, name);
		break;
	case DATA_TYPE_MME:
		WriteXmlMme(fp, name);
		break;
	case DATA_TYPE_ASU:
		WriteXmlAsu(fp, name);
		break;
	}

	fp.Close();

	return true;
}

// MMAのXMLファイルを書き込む
void CIdbFile::WriteXmlMma(CStdioFile &fp, LPCSTR name)
{
	CString buf, str;
	int	cnt = 0;

	// ヘッダ
	fp.WriteString(xml_mma[cnt++]);
	fp.WriteString(xml_mma[cnt++]);

	// センサーID
	buf.Format(xml_mma[cnt++], m_sensorid);
	fp.WriteString(buf);

	// タイムゼロ
	buf.Format(xml_mma[cnt++], m_timezeroXML);
	fp.WriteString(buf);

	// ゲイン
	buf.Format(xml_mma[cnt++], m_gain);
	fp.WriteString(buf);

	// サンプルレート
	buf.Format(xml_mma[cnt++], m_samplerate);
	fp.WriteString(buf);

	// カットオフ周波数
	buf.Format(xml_mma[cnt++], m_cutoff);
	fp.WriteString(buf);

	// 保存パス名
	str = m_path;
	str += "\\";
	str += name;
//	str += ".dat";
	buf.Format(xml_mma[cnt++], str);
	fp.WriteString(buf);

	// Bias Co Eff (TBD)
	buf.Format(xml_mma[cnt++], g_BIAS_x, g_BIAS_y, g_BIAS_z);
	fp.WriteString(buf);

	// Sensor Co Sys
//	double a_drpqVaule = 0.0;
	buf.Format(xml_mma[cnt++], m_sensorid
		//, SensorInfo[m_SensorIdx].x
		//, SensorInfo[m_SensorIdx].y
		//, SensorInfo[m_SensorIdx].z
// 2008/4/25 modified by yG for rpw addition ->
//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
		, g_pSensorInfo[m_SensorIdx].roll
		, g_pSensorInfo[m_SensorIdx].pitch
		, g_pSensorInfo[m_SensorIdx].yaw
// <- 2008/4/25 modified by yG for rpw addition
		, g_pSensorInfo[m_SensorIdx].x
		, g_pSensorInfo[m_SensorIdx].y
		, g_pSensorInfo[m_SensorIdx].z
		, m_sensorcosys
		, gLaunchTime);
	fp.WriteString(buf);

	// Data Co Sys
	buf.Format(xml_mma[cnt++], m_sensorid
		//, SensorInfo[m_SensorIdx].x
		//, SensorInfo[m_SensorIdx].y
		//, SensorInfo[m_SensorIdx].z
// 2008/4/25 modified by yG for rpw addition ->
//		,a_drpqVaule,a_drpqVaule,a_drpqVaule
		, g_pSensorInfo[m_SensorIdx].roll
		, g_pSensorInfo[m_SensorIdx].pitch
		, g_pSensorInfo[m_SensorIdx].yaw
// <- 2008/4/25 modified by yG for rpw addition
		, g_pSensorInfo[m_SensorIdx].x
		, g_pSensorInfo[m_SensorIdx].y
		, g_pSensorInfo[m_SensorIdx].z
		, m_datacosys
		, gLaunchTime);
	fp.WriteString(buf);

	// Data Quality
	buf.Format(xml_mma[cnt++], m_dataquality);
	fp.WriteString(buf);

	// ISS Config
	buf.Format(xml_mma[cnt++], m_issconfig);
	fp.WriteString(buf);

	// スケールファクター
	buf.Format(xml_mma[cnt++], g_SF_x, g_SF_y, g_SF_z);
	fp.WriteString(buf);

	// トレーラ
	fp.WriteString(xml_mma[cnt]);
}

// MMEのXMLファイルを書き込む
void CIdbFile::WriteXmlMme(CStdioFile &fp, LPCSTR name)
{
	CString buf, str;
	int	cnt = 0;

	// ヘッダ
	fp.WriteString(xml_mme[cnt++]);
	fp.WriteString(xml_mme[cnt++]);

	// センサーID
	buf.Format(xml_mme[cnt++], m_sensorid);
	fp.WriteString(buf);

	// タイムゼロ
	buf.Format(xml_mme[cnt++], m_timezeroXML);
	fp.WriteString(buf);

	// サンプルレート
	buf.Format(xml_mme[cnt++], m_samplerate);
	fp.WriteString(buf);

	// 保存パス名
	str = m_path;
	str += "\\";
	str += name;
	buf.Format(xml_mme[cnt++], str);
	fp.WriteString(buf);

	// Sensor Co Sys
	buf.Format(xml_mme[cnt++], m_sensorid
			, g_pSensorInfoMme[m_SensorIdx].roll
			, g_pSensorInfoMme[m_SensorIdx].pitch
			, g_pSensorInfoMme[m_SensorIdx].yaw
			, g_pSensorInfoMme[m_SensorIdx].x
			, g_pSensorInfoMme[m_SensorIdx].y
			, g_pSensorInfoMme[m_SensorIdx].z
			, m_sensorcosys, gLaunchTime);
	fp.WriteString(buf);

	// Data Co Sys
	buf.Format(xml_mme[cnt++], m_sensorid
			, g_pSensorInfoMme[m_SensorIdx].roll
			, g_pSensorInfoMme[m_SensorIdx].pitch
			, g_pSensorInfoMme[m_SensorIdx].yaw
			, g_pSensorInfoMme[m_SensorIdx].x
			, g_pSensorInfoMme[m_SensorIdx].y
			, g_pSensorInfoMme[m_SensorIdx].z
			, m_datacosys, gLaunchTime);
	fp.WriteString(buf);

	// トレーラ
	fp.WriteString(xml_mme[cnt]);
}

// ASUのXMLファイルを書き込む
void CIdbFile::WriteXmlAsu(CStdioFile &fp, LPCSTR name)
{
	CString buf, str;
	int	cnt = 0;

	// ヘッダ
	fp.WriteString(xml_asu[cnt++]);
	fp.WriteString(xml_asu[cnt++]);

	// センサーID
	buf.Format(xml_asu[cnt++], m_sensorid);
	fp.WriteString(buf);

	// タイムゼロ
	buf.Format(xml_asu[cnt++], m_timezeroXML);
	fp.WriteString(buf);

	// サンプルレート
	buf.Format(xml_asu[cnt++], m_samplerate);
	fp.WriteString(buf);

	// 保存パス名
	str = m_path;
	str += "\\";
	str += name;
	buf.Format(xml_asu[cnt++], str);
	fp.WriteString(buf);

	// Sensor Co Sys
	buf.Format(xml_asu[cnt++], m_sensorid
			, g_pSensorInfoAsu[m_SensorIdx].roll
			, g_pSensorInfoAsu[m_SensorIdx].pitch
			, g_pSensorInfoAsu[m_SensorIdx].yaw
			, g_pSensorInfoAsu[m_SensorIdx].x
			, g_pSensorInfoAsu[m_SensorIdx].y
			, g_pSensorInfoAsu[m_SensorIdx].z
			, m_sensorcosys, gLaunchTime);
	fp.WriteString(buf);

	// Data Co Sys
	buf.Format(xml_asu[cnt++], m_sensorid
			, g_pSensorInfoAsu[m_SensorIdx].roll
			, g_pSensorInfoAsu[m_SensorIdx].pitch
			, g_pSensorInfoAsu[m_SensorIdx].yaw
			, g_pSensorInfoAsu[m_SensorIdx].x
			, g_pSensorInfoAsu[m_SensorIdx].y
			, g_pSensorInfoAsu[m_SensorIdx].z
			, m_datacosys, gLaunchTime);
	fp.WriteString(buf);

	// トレーラ
	fp.WriteString(xml_asu[cnt]);
}

// IDBファイルを書き込む
bool CIdbFile::Write(LPCSTR name)
{
	CStdioFile	fp;
	CFileException fileException;
	CString buf;

	m_sIdbPath = m_path + "\\" + name + ".idb";
	if (! fp.Open(m_sIdbPath, CFile::modeCreate | CFile::modeWrite, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", m_sIdbPath, fileException.m_cause );
		return false;
	}

	// コメント
	fp.WriteString("COMMENT=" + m_comment + "\n");
	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	fp.WriteString("STARTTIME=" + m_starttime + "\n");
	// サンプルレート
	buf.Format("SAMPLERATE=%.2lf\n", m_samplerate);
	fp.WriteString(buf);
	// 期間
	fp.WriteString("PERIOD=" + m_period + "\n");
	// ダウンリンク日
	fp.WriteString("DOWNLINK=" + m_downlink + "\n");
	// ユニットID
	buf.Format("RSUID=%d\n", m_rsuid);
	fp.WriteString(buf);
	// ネットワークID
	buf.Format("NETWORKID=%d\n", m_networkid);
	fp.WriteString(buf);
	// センサーID
	buf.Format("SENSORID=%d\n", m_sensorid);
	fp.WriteString(buf);
	// センサーIDX
	buf.Format("SENSORIDX=%d\n", m_SensorIdx);
	fp.WriteString(buf);
	// タイムゼロ
	fp.WriteString("TIMEZERO=" + m_timezero + "\n");
	// ゲイン
	buf.Format("GAIN=%d\n", m_gain);
	fp.WriteString(buf);
	// カットオフ周波数
	buf.Format("CUTOFF=%d\n", m_cutoff);
	fp.WriteString(buf);
	// Bias Co Eff
	buf.Format("BIASCOEFF=%d\n", m_biascoeff);
	fp.WriteString(buf);
	// Sensor Co Sys
	fp.WriteString("SENSORCOSYS=" + m_sensorcosys + "\n");
	// Data Co Sys
	fp.WriteString("DATACOSYS=" + m_datacosys + "\n");
	// Data Quality
	fp.WriteString("DATAQUALITY=" + m_dataquality + "\n");
	// ISS Config
	fp.WriteString("ISSCONFIG=" + m_issconfig + "\n");
	// スケールファクター
	buf.Format("SCALEFACTOR=%d\n", m_scalefactor);
	fp.WriteString(buf);
	// 保存パス名
	fp.WriteString("PATH=" + m_path + "\n");
	// カテゴリ
	fp.WriteString("CATEGORY=" + m_sCategory + "\n");
	// 計測種類
	fp.WriteString("MEASUREMENT_KIND=" + m_sMeasurementKind + "\n");

	// １時間毎のファイル名保存エリア
	int cnt = (int)m_File.GetSize();
	for (int i = 0; i < cnt; i++) {
		fp.WriteString("FILE=" + m_File.GetAt(i) + "\n");
	}

	fp.Close();

	return true;
}

// IDBファイルを読み込む
bool CIdbFile::Read(LPCSTR path)
{
	CString fname;

	// IDBファイル名を取得する
	if (! GetFname(path, fname))
		return false;

	// IDBファイルを読み込む
	return Read2(fname);
}

// IDBファイルを読み込む
bool CIdbFile::Read2(LPCSTR fname)
{
	// IDBファイルを読み込む
	if (! GetIdbFileData(fname))
		return false;

	// データをセットする
	SetIdbFileData();

	// バイナリデータ保存先パスをチェックする
	CheckBinDataPath(fname);

	return true;
}

// IDBファイル名を取得する
bool CIdbFile::GetFname(LPCSTR path, CString& fname)
{
	char wpath[MAX_PATH];
	struct _finddata_t c_file;
    intptr_t hFile;

	memset(wpath, 0, sizeof(wpath));
	strcpy_s(wpath, path);
	strcat_s(wpath, "\\*.idb");

    /* カレント ディレクトリ内の最初のファイルを探します。*/
	if( (hFile = _findfirst(wpath, &c_file )) != -1L ) {
		fname.Format("%s\\%s", path, c_file.name);
		_findclose( hFile );
		return true;
	}
	return false;
}

// IDBファイルを読み込む
bool CIdbFile::GetIdbFileData(LPCSTR path)
{
	CStdioFile	fp;
	CFileException fileException;
	CString buf;

	if (! fp.Open(path, CFile::modeRead, &fileException)) {
		TRACE( "Can't open file %s, error = %u\n", path, fileException.m_cause );
		return false;
	}
	for (;;) {
		if (! fp.ReadString(buf))
			break;
		m_Data.Add(buf);
	}
	fp.Close();

	return true;
}

// データをセットする
void CIdbFile::SetIdbFileData(void)
{
	CString	buf;

	// コメント
	if (GetData("COMMENT", buf) != false) {
		m_comment = buf;
	}
	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	if (GetData("STARTTIME", buf) != false) {
		m_starttime = buf;
	}
	// サンプルレート
	if (GetData("SAMPLERATE", buf) != false) {
		m_samplerate = atof((LPCSTR)buf);
	}
	// 期間
	if (GetData("PERIOD", buf) != false) {
		m_period = buf;
	}
	// ダウンリンク日
	if (GetData("DOWNLINK", buf) != false) {
		m_downlink = buf;
	}
	// ユニットID
	if (GetData("RSUID", buf) != false) {
		m_rsuid = atoi(buf);
	}
	// ネットワークID
	if (GetData("NETWORKID", buf) != false) {
		m_networkid = atoi(buf);
	}
	// センサーID
	if (GetData("SENSORID", buf) != false) {
		m_sensorid = atoi(buf);
	}
	// センサーIDX
	if (GetData("SENSORIDX", buf) != false) {
		m_SensorIdx = atoi(buf);
	}
	// タイムゼロ
	if (GetData("TIMEZERO", buf) != false) {
		m_timezero = buf;
	}
	// ゲイン
	if (GetData("GAIN", buf) != false) {
		m_gain = atoi(buf);
	}
	// カットオフ周波数
	if (GetData("CUTOFF", buf) != false) {
		m_cutoff = atoi(buf);
	}
	// Bias Co Eff
	if (GetData("BIASCOEFF", buf) != false) {
		m_biascoeff = atoi(buf);
	}
	// Sensor Co Sys
	if (GetData("SENSORCOSYS", buf) != false) {
		m_sensorcosys = buf;
	}
	// Data Co Sys
	if (GetData("DATACOSYS", buf) != false) {
		m_datacosys = buf;
	}
	// Data Quality
	if (GetData("DATAQUALITY", buf) != false) {
		m_dataquality = buf;
	}
	// ISS Config
	if (GetData("ISSCONFIG", buf) != false) {
		m_issconfig = buf;
	}
	// スケールファクター
	if (GetData("SCALEFACTOR", buf) != false) {
		m_scalefactor = atoi(buf);
	}
	// 保存パス名
	if (GetData("PATH", buf) != false) {
		m_path = buf;
	}
	// カテゴリ
	if (GetData("CATEGORY", buf) != false) {
		m_sCategory = buf;
	}
	// 計測種類
	if (GetData("MEASUREMENT_KIND", buf) != false) {
		m_sMeasurementKind = buf;
	}
	// バイナリファイル名
	GetData("FILE", m_File);
}

// ファイルデータから指定項目を取得する
bool CIdbFile::GetData(LPCSTR itemStr, CString &oDat)
{
	INT_PTR	i, cnt;

	cnt = m_Data.GetSize();
	for (i = 0; i < cnt; i++) {
		if (GetDataFromIdb(m_Data.GetAt(i), itemStr, oDat) != false)
			return true;
	}
	return false;
}

// ファイルデータから複数の指定項目を取得する
void CIdbFile::GetData(LPCSTR itemStr, CStringArray &oDat)
{
	INT_PTR	i, cnt;
	CString sDat;

	oDat.RemoveAll();
	cnt = m_Data.GetSize();
	for (i = 0; i < cnt; i++) {
		if (GetDataFromIdb(m_Data.GetAt(i), itemStr, sDat) != false)
			oDat.Add(sDat);
	}
}

// ファイルデータから指定項目を取得する
bool CIdbFile::GetDataFromIdb(LPCSTR dat, LPCSTR itemStr, CString &oDat)
{
	CString sItemKey, sItemValue;
	CString sDat(dat);
	int nPos= 0;

	sItemKey = sDat.Tokenize("=", nPos);
	sItemValue = sDat.Tokenize("", nPos);

	if (sItemKey != itemStr)
		return false;

	oDat = sItemValue;
	return true;
}


// 情報クリア
void CIdbFile::DelAll(void)
{
	// データ範囲
	m_period = "";
	// ダウンリンク日
	m_downlink = "";
	// ネットワークID（UNIT ID上位４桁）
	m_networkid = 0;
	// ユニットID（UNIT ID下位４桁）
	m_rsuid = 0;
	// センサーID
	m_sensorid = 0;
	// タイムゼロ
	m_timezero = "";
	// ゲイン
	m_gain = 0;
	// サンプルレート
	m_samplerate = 0.0;
	// カットオフ周波数
	m_cutoff = 0;
	// 保存パス名
	m_path = "";
	// Bias Co Eff
	m_biascoeff = 0;
	// Sensor Co Sys
	m_sensorcosys = "";
	// Data Co Sys
	m_datacosys = "";
	// Data Quality
	m_dataquality = "";
	// ISS Config
	m_issconfig  = "";
	// スケールファクター
	m_scalefactor = 0;
	// コメント
	m_comment = "";
	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	m_starttime = "";
	// センサーIDX
	m_SensorIdx = 0;
	// カテゴリ
	m_sCategory = "";
	// 計測種類
	m_sMeasurementKind = "";
	// １時間毎のファイル名保存エリア
	m_File.RemoveAll();
	// IDBファイル読み込みエリア
	m_Data.RemoveAll();
}

// バイナリデータファイル保存先チェック
void CIdbFile::CheckBinDataPath(LPCTSTR pIdbFilePath)
{
	// idbファイルパスを\で分割して配列にセットする
	CStringArray saIdbFilePath;
	CGeneral::StrSplit(pIdbFilePath, "\\", saIdbFilePath);
	int sSize = (int)saIdbFilePath.GetSize();

	// idbファイル名から拡張子を取り除く
	CString sName;
	sName = saIdbFilePath[sSize - 1];
	sName = sName.Left(sName.GetLength() - 4);

	// idbファイルパスからidbファイル名を除いたものを結合してパス名を取得する
	CString sPath;
	saIdbFilePath.RemoveAt(sSize - 1);
	CGeneral::StrJoin(saIdbFilePath, "\\", sPath);

	// 保存パスが異なる場合は正しいパスに変更する
	if (m_path != sPath) {
		m_path = sPath;

		Write(sName);
	}
}
