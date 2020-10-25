#include "stdafx.h"
#include "GlobalData.h"

const SENSOR_INFO SensorInfo[] = {
	{0, "1:JPM A2 Upper-left", "JPM_A2_Upper-left"
		, "Overhead", "Starboard", "Forward"
		, 387.475748031, -170.754803150, 156.335826772
	},
	{1, "2:JPM A3 Upper-left", "JPM_A3_Upper-left"
		, "Overhead", "Starboard", "Forward"
		, 387.475748031, -212.754803150, 156.335826772
	},
	{2, "3:JPM A3 on FPEF", "JPM_A3_on_FPEF"
		, "Forward", "Port", "Overhead"
		, 393.337952756, -222.707559055, 158.572047244
	},
	{3, "4:JPM A3 SCOF REF-CELL", "JPM_A3_SCOF_REF-CELL"
		, "Forward", "Port", "Overhead"
		, 383.378307087, -236.872913386, 191.977559055
	},
	{-1, NULL, NULL, NULL, NULL, NULL, 0.0, 0.0, 0.0}
};

const char *g_tWindowFunc[] = {
	/*"Rectangular"*/"Boxcar", "Triangular", "Hamming", "Hanning", "Blackman", "Blackman-Harris", "Flat-Top"
};

char	gRootPath[_MAX_PATH];	// データ格納ROOTパス
char	gRootData[_MAX_PATH];	// 工学値データ格納パス
char	gExePath[_MAX_PATH];	// 本プログラムEXEのパス
char	gToolExe[_MAX_PATH];	// ツール用外部プログラムパス
char	gRealtimeDataPath[_MAX_PATH];	// リアルタイムデータパス
char	gRealtimeTempPath[_MAX_PATH];	// リアルタイムテンポラリパス
char	gDownlinkReferencePath[_MAX_PATH];	// ダウンリンクフォルダ参照パス
char	gLaunchTime[64];		// LaunchTime
CStringArray	gRootDataList;	// ツリー表示データパス（複数対応）
BOOL	gZeroOffsetSw;			// 0点補正有無デフォルト値
double	gSpectrumPsdRange;		// スペクトラムPSD範囲デフォルト値
int		gZeroOffsetSpan;		// 0点補正値グラフ時間間隔
char	gAutoConvPath[_MAX_PATH];	// 自動工学値変換パス
int		gRealtimeRange;			// 準リアルタイム表示範囲
CStringArray	gChoiceCategory;	// カテゴリ選択肢
CStringArray	gChoiceMeasurementKind;	// 計測種類選択肢

SENSOR_INFO* g_pSensorInfo;
SENSOR_INFO* g_pSensorInfoMme;
SENSOR_INFO* g_pSensorInfoAsu;
int		     g_iInfoNum;

double g_SF_x;
double g_SF_y;
double g_SF_z;
double g_BIAS_x;
double g_BIAS_y;
double g_BIAS_z;
