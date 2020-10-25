#pragma once

class CIdbFile
{
public:
	CIdbFile(void);
	~CIdbFile(void);

public:
	// データ範囲
	CString m_period;
	// ダウンリンク日
	CString m_downlink;
	// ネットワークID（UNIT ID上位４桁）
	int	m_networkid;
	// ユニットID（UNIT ID下位４桁）
	int m_rsuid;
	// センサーID
	int m_sensorid;
	// タイムゼロ
	CString m_timezero;
	// タイムゼロ(XML)
	CString m_timezeroXML;
	// ゲイン
	int m_gain;
	// サンプルレート
	double m_samplerate;
	// カットオフ周波数
	int m_cutoff;
	// 保存パス名
	CString m_path;
	// Bias Co Eff
	int m_biascoeff;
	// Sensor Co Sys
	CString m_sensorcosys;
	// Data Co Sys
	CString m_datacosys;
	// Data Quality
	CString m_dataquality;
	// ISS Config
	CString m_issconfig;
	// スケールファクター
	int m_scalefactor;
	// コメント
	CString m_comment;
	// １時間毎のファイル名保存エリア
	CStringArray m_File;
	// カテゴリ
	CString m_sCategory;
	// 計測種類
	CString m_sMeasurementKind;
	// idbファイルパス
	CString m_sIdbPath;
	// データ種別
	int m_nDataType;

private:
	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	CString	m_starttime;
	// センサーIDX
	int	m_SensorIdx;
	// IDBファイル読み込みエリア
	CStringArray	m_Data;
// 2009/07/07 Version 3.02 appended by m.hama ->
	// XMLファイル読み込みエリア
	CStringArray	m_XmlData;
// <- 2009/07/07 Version 3.02 appended by m.hama

public:
	// データ範囲
	void SetPeriod(LPCSTR str)	{ m_period = str; }
	// ダウンリンク日
	void SetDownlink(LPCSTR str)	{ m_downlink = str; }
	// ネットワークID（UNIT ID上位４桁）
	void SetNetworkid(int id)	{m_networkid = id; }
	// ユニットID（UNIT ID下位４桁）
	void SetRsuId(int id)	{ m_rsuid = id; }
	// センサーID
	void SetSensorId(int id)	{ m_sensorid = id; }
	// タイムゼロ
	void SetTimeZero(LPCSTR str)	{ m_timezero = str; }
	// タイムゼロ
	void SetTimeZeroXML(LPCSTR str)	{ m_timezeroXML = str; }
	// ゲイン
	void SetGain(int gain)	{ m_gain = gain; }
	// サンプルレート
	void SetSampleRate(double f)	{ m_samplerate = f; }
	// カットオフ周波数
	void SetCutoff(int c)	{ m_cutoff = c; }
	// 保存パス名
	void SetPath(LPCSTR str)	{ m_path = str; }
	// Bias Co Eff
	void SetBiascoeff(int c)	{ m_biascoeff = c; }
	// Sensor Co Sys
	void SetSensorcosys(LPCSTR str)	{ m_sensorcosys = str; }
	// Data Co Sys
	void SetDatacosys(LPCSTR str)	{ m_datacosys = str; }
	// Data Quality
	void SetDataquality(LPCSTR str)	{ m_dataquality = str; }
	// ISS Config
	void SetIssconfig(LPCSTR str)	{ m_issconfig = str; }
	// スケールファクター
	void SetScalefactor(int sf)	{ m_scalefactor = sf; }
	// コメント
	void SetComment(LPCSTR str)	{ m_comment = str; }
	// カテゴリ
	void SetCategory(LPCSTR str) { m_sCategory = str; }
	// 計測種類
	void SetMeasurementKind(LPCSTR str) { m_sMeasurementKind = str; }

	// スタートタイムYYYY（年）MM(月)DD(日)SSSSSSSS（ミリ秒）
	void SetStartTime(long YY, long MM, long DD, long mS)	{ m_starttime.Format("%04d%02d%02d%08d", YY, MM, DD, mS); }
	// センサーIDX
	void SetSensorIdx(int idx) { m_SensorIdx = idx; }
	// 1時間毎のファイル名保存エリア
	void AddFile(LPCSTR str)	{ m_File.Add(str); }
	// IDBファイルを書き込む
	bool Write(LPCSTR name);
	// IDBファイルを読み込む
	bool Read(LPCSTR path);
	bool Read2(LPCSTR fname);
	// IDBファイル名を取得する
	bool GetFname(LPCSTR path, CString& fname);
private:
	// IDBファイルを読み込む
	bool GetIdbFileData(LPCSTR path);
	// データをセットする
	void SetIdbFileData(void);
	// ファイルデータから指定項目を取得する
	bool GetData(LPCSTR itemStr, CString &oDat);
	// ファイルデータから複数の指定項目を取得する
	void GetData(LPCSTR itemStr, CStringArray &oDat);
	// ファイルデータから指定項目を取得する
	bool GetDataFromIdb(LPCSTR dat, LPCSTR itemStr, CString &oDat);
	// バイナリデータファイル保存先チェック
	void CheckBinDataPath(LPCTSTR pIdbFilePath);
	// MMAのXMLファイルを書き込む
	void WriteXmlMma(CStdioFile &fp, LPCSTR name);
	// MMEのXMLファイルを書き込む
	void WriteXmlMme(CStdioFile &fp, LPCSTR name);
	// ASUのXMLファイルを書き込む
	void WriteXmlAsu(CStdioFile &fp, LPCSTR name);

public:
	bool WriteXml(LPCSTR name, int nDataType);
// 2009/07/07 Version 3.02 appended by m.hama ->
	bool GetXmlFileData(LPCSTR path);
	bool WriteXml2(LPCSTR name);
// <- 2009/07/07 Version 3.02 appended by m.hama

	// 情報クリア
	void DelAll(void);
};
