/*	カメラ制御コマンド 	*/
#define	CM_HOME_POSITION	0	// ホームポジション（中央）に移動
#define	CM_PAN_RIGHT		1	// 右向きに移動開始
#define	CM_PAN_LEFT		2	// 左向きに移動開始
#define	CM_TILT_UP		3	// 上向きに移動開始
#define	CM_TILT_DOWN		4	// 下向きに移動開始
#define	CM_STOP			5	// カメラ向き移動停止
#define	CM_ZOOM_WIDE		6	// ワイド（広角）側へズーム開始
#define	CM_ZOOM_TELE		7	// テレ（望遠）側へズーム開始
#define	CM_ZOOM_STOP		8	// ズーム停止
#define	CM_POWER_ON		9	// パワーオン
#define	CM_POWER_OFF		10	// パワーオフ

#define	STATUS_FREE
#define	STATUS_LOCAL
#define	STATUS_NETWORK

#define	N_PRESET	8

#define	CMENTRY	__pascal __far __export __loadds

/*	カメラ制御ブロック	*/
typedef	struct	{
	int	status;
	int	sock;
	int	port;
} CCB;

/*	カメラオプションデータ	*/
typedef	struct	{
	int	panSpeed;
	int	tiltSpeed;
	int	zoomSpeed;
	int	shutterSpeed;
	int	audioVolume;
	BOOL	bAutoFocus;
	BOOL	bLightAdjust;
	BOOL	bAutoWhiteBalance;
	BOOL	bWideConverter;
} CAMERAOPT, *PCAMERAOPT;

/*	カメラ位置情報		*/
typedef	struct	{
	int	pan;
	int	tilt;
	int	zoom;
} CAMERAPOS;

/*	プリセット情報		*/
typedef	struct	{
	CAMERAPOS pos;
	char	comment[31];
} PRESET;

/*	ini ファイル情報	*/
typedef	struct	{
	CAMERAOPT opt;
	PRESET	preset[N_PRESET];
} INIDATA;
