/************************************************************************
 *									*
 *			カメラコントロールＤＬＬ			*
 *									*
 *		名称		: 共通ヘッダファイル			*
 *		ファイル名	: cmctl.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

/*	カメラ制御コマンド 	*/
#define	CM_RESET		0	/* リセット */
#define	CM_HOME_POSITION	1	/* ホームポジション（中央）に移動 */
#define	CM_PAN_RIGHT		2	/* 右向きに移動開始 */
#define	CM_PAN_LEFT		3	/* 左向きに移動開始 */
#define	CM_TILT_UP		4	/* 上向きに移動開始 */
#define	CM_TILT_DOWN		5	/* 下向きに移動開始 */
#define	CM_STOP			6	/* カメラ向き移動停止 */
#define	CM_ZOOM_WIDE		7	/* ワイド（広角）側へズーム開始 */
#define	CM_ZOOM_TELE		8	/* テレ（望遠）側へズーム開始 */
#define	CM_ZOOM_STOP		9	/* ズーム停止 */
#define	CM_FOCUS_AUTO		10	/* オートフォーカス */
#define	CM_FOCUS_MANUAL		11	/* マニュアルフォーカス */
#define	CM_FOCUS_NEAR		12	/* フォーカス NEAR（近） */
#define	CM_FOCUS_FAR		13	/* フォーカス FAR（遠） */
#define	CM_POWER_ON		14	/* パワーオン */
#define	CM_POWER_OFF		15	/* パワーオフ */

/*	ビデオサイズ	*/
#define	SIZE_40_30	0	/* 40 x 30 */
#define	SIZE_80_60	1	/* 80 x 60 */
#define	SIZE_120_90	2	/* 120 x 90 */
#define	SIZE_160_120	3	/* 160 x 120 */
#define	SIZE_200_150	4	/* 200 x 150 */
#define	SIZE_240_180	5	/* 240 x 180 */
#define	SIZE_280_210	6	/* 280 x 210 */
#define	SIZE_320_240	7	/* 320 x 240 */
#define	SIZE_360_270	8	/* 360 x 270 */
#define	SIZE_400_300	9	/* 400 x 300 */
#define	SIZE_440_330	10	/* 440 x 330 */
#define	SIZE_480_360	11	/* 480 x 360 */
#define	SIZE_520_390	12	/* 520 x 390 */
#define	SIZE_560_420	13	/* 560 x 420 */
#define	SIZE_600_450	14	/* 600 x 450 */
#define	SIZE_640_480	15	/* 640 x 480 */
#define	SIZE_MIN	SIZE_40_30
#define	SIZE_MAX	SIZE_640_480

/*	ビデオカラーモード	*/
#define	COLOR_8		0		/* 256 色モノクロ */
#define	COLOR_16	1		/* 64k 色カラー */
#define	COLOR_24	2		/* 16M 色カラー */

/*	エラーコード	*/
#define	ERROR_CM_TOO_MANY_OPEN	1000	/* オープン数が多すぎる */
#define	ERROR_CM_VIDEO_OPEN	1001	/* ビデオキャプチャのオープンに失敗 */
#define	ERROR_CM_PORT_OPEN	1002	/* RS-232C ポートのオープンに失敗 */
#define	ERROR_CM_NOANSWER	1003	/* カメラから応答がない */
#define	ERROR_CM_INVALID_HANDLE	1004	/* ハンドルが不正 */
#define	ERROR_CM_INVALID_COMMAND 1005	/* CMControl() のコマンドが不正 */
#define	ERROR_CM_PRESET_NO	1006	/* プリセットＮｏが不正 */
#define	ERROR_CM_VIDEO_CONFIG	1007	/* キャプチャボードの設定に失敗 */
#define	ERROR_CM_VIDEO_CAPTURE	1008	/* キャプチャに失敗 */
#define	ERROR_CM_CAPTURE_MODE	1009	/* キャプチャモードの設定が不正 */
#define	ERROR_CM_CAMERA_CONTROL	1010	/* カメラコントロールができない */
#define	ERROR_CM_WINSOCK	1011	/* winsock.dll がロードできない */
#define	ERROR_CM_HOSTNAME	1012	/* ホスト名が不正 */
#define	ERROR_CM_SOCKET		1013	/* ソケットのオープンに失敗 */
#define	ERROR_CM_CONxxxT	1014	/* PC-VIDEO への接続に失敗 */
#define	ERROR_CM_SEND		1015	/* データ送信でエラー */
#define	ERROR_CM_RECEIVE	1016	/* データ受信でエラー */
#define	ERROR_CM_NOCAPTURE	1017	/* PC-VIDEO がビデオ送信をしていない */
#define	ERROR_CM_MEMORY		1018	/* メモリ不足 */
#define	ERROR_CM_NOSUPPORT	1019	/* カメラコントロールのサポートなし */
#define	ERROR_CM_DENY		1020	/* カメラコントロールが禁止 */
#define	ERROR_CM_LOCK		1021	/* 他がロック中 */

#define	MAX_PRESET_NO	8		/* 最大プリセットＮｏ */

#define	CMENTRY	__pascal __far __export __loadds

/*	カメラオプションデータ	*/
typedef	struct	{
	int	panSpeed;		/* パン速度 */
	int	tiltSpeed;		/* チルト速度 */
	int	zoomSpeed;		/* ズーム速度 */
	int	shutterSpeed;		/* シャッター速度 */
	int	autoFocus;		/* オートフォーカス */
	int	lightAdjust;		/* 逆光補正 */
	int	autoWhiteBalance;	/* 自動ホワイトバランス */
	int	wideConverter;		/* ワイドコンバータ使用 */
} CAMERAOPT, FAR *LPCAMERAOPT;

/*	カメラ位置情報		*/
typedef	struct	{
	int	pan;			/* パン位置 */
	int	tilt;			/* チルト位置 */
	int	zoom;			/* ズーム位置 */
} CAMERAPOS, FAR *LPCAMERAPOS;

/*	プリセット情報		*/
typedef	struct	{
	CAMERAPOS pos;			/* プリセット位置 */
	char	comment[31];		/* コメント */
} PRESET, FAR *LPPRESET;

/*	ビデオキャプチャモード	*/
typedef	struct	{
	int	size;			/* ビデオサイズ */
	int	color;			/* 色階調 */
	int	compressMode;		/* 圧縮モード */
	int	compressQuality;	/* 圧縮時の画質 */
} CAPTUREMODE, FAR *LPCAPTUREMODE;

#ifdef __cplusplus
extern "C" {            /* Assume C declarations for C++ */
#endif  /* __cplusplus */

extern	int	CMENTRY	CMOpen(LPSTR hostName, LPSTR userName, int option,
								LPINT handle);
extern	int	CMENTRY	CMClose(int handle);
extern	int	CMENTRY	CMControl(int handle, int command);
extern	int	CMENTRY	CMGetCameraOpt(int handle, LPCAMERAOPT cameraOpt);
extern	int	CMENTRY	CMSetCameraOpt(int handle, LPCAMERAOPT opt);
extern	int	CMENTRY	CMGetPreset(int handle, LPPRESET preset);
extern	int	CMENTRY	CMSetPreset(int handle, LPPRESET preset, int presetNo);
extern	int	CMENTRY	CMMovePreset(int handle, int presetNo);
extern	int	CMENTRY	CMGetCameraPos(int handle, LPCAMERAPOS pos);
extern	int	CMENTRY	CMSetCameraPos(int handle, LPCAMERAPOS pos);
extern	int	CMENTRY CMLock(int handle, int lockFlag);
extern	int	CMENTRY CMCaptureVideo(int handle, LPCAPTUREMODE captureMode,
								LPSTR buf);
extern	int	CMENTRY CMDraw(HDC hDC, LPCAPTUREMODE captureMode, LPSTR buf);

#ifdef __cplusplus
}                       /* End of extern "C" { */
#endif  /* __cplusplus */
