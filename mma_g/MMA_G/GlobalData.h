#pragma once

typedef struct TAG_KEISUU_INFO {
	double A4;
	double A3;
	double A2;
	double A1;
	double A0;
	double B4;
	double B3;
	double B2;
	double B1;
	double B0;
	double C4;
	double C3;
	double C2;
	double C1;
	double C0;
	double D4;
	double D3;
	double D2;
	double D1;
	double D0;
	double E4;
	double E3;
	double E2;
	double E1;
	double E0;
} KEISUU_INFO;

typedef struct TAG_SENSOR_INFO {
	int	idx;
	char *sel_dat;
	char *dat;
	char *dir_x;
	char *dir_y;
	char *dir_z;
	double	x;
	double	y;
	double	z;
// 2008/4/25 appended by yG for rpw addition ->
	double	roll;
	double	pitch;
	double	yaw;
// <- 2008/4/25 appended by yG for rpw addition
	int rsu_id;
	char *category;
	char *measurement_kind;
} SENSOR_INFO;

extern const SENSOR_INFO SensorInfo[];
extern SENSOR_INFO* g_pSensorInfo;
extern SENSOR_INFO* g_pSensorInfoMme;
extern SENSOR_INFO* g_pSensorInfoAsu;
extern char	gRootPath[_MAX_PATH];	// データ格納ROOTパス
extern char	gRootData[_MAX_PATH];	// 工学値データ格納パス
extern char	gExePath[_MAX_PATH];	// 本プログラムEXEのパス
extern char	gToolExe[_MAX_PATH];	// ツール用外部プログラムパス
extern char	gRealtimeDataPath[_MAX_PATH];	// リアルタイムデータパス
extern char	gRealtimeTempPath[_MAX_PATH];	// リアルタイムテンポラリパス
extern char	gDownlinkReferencePath[_MAX_PATH];	// ダウンリンクフォルダ参照パス
extern char	gLaunchTime[64];		// LaunchTime
extern CStringArray	gRootDataList;	// ツリー表示データパス（複数対応）
extern BOOL	gZeroOffsetSw;			// 0点補正有無デフォルト値
extern double	gSpectrumPsdRange;		// スペクトラムPSD範囲デフォルト値
extern int		gZeroOffsetSpan;		// 0点補正値グラフ時間間隔
extern char	gAutoConvPath[_MAX_PATH];	// 自動工学値変換パス
extern int		gRealtimeRange;			// 準リアルタイム表示範囲
extern CStringArray	gChoiceCategory;	// カテゴリ選択肢
extern CStringArray	gChoiceMeasurementKind;	// 計測種類選択肢
extern const char	*g_tWindowFunc[];		// 窓関数
extern int		g_iInfoNum;
extern double g_SF_x;
extern double g_SF_y;
extern double g_SF_z;
extern double g_BIAS_x;
extern double g_BIAS_y;
extern double g_BIAS_z;
