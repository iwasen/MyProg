/************************************************************************
 |
 |		名称		: VCC1 ライブラリ　ヘッダファイル
 |
 |		ファイル名	: vcc1.h
 |
 |		m.hama　95/12/22		
 |				96/01/05	Vcc1Open ﾊﾟﾗﾒｰﾀ追加
 |
 ************************************************************************/

#define DllExport   __declspec( dllexport )

#define	VCC1_COM1	0
#define	VCC1_COM2	1
#define	VCC1_COM3	2

#ifdef __cplusplus
extern "C" {
#endif

extern DllExport int	APIENTRY Vcc1Open(int);				/* VCC1初期化処理(0:COM1 1:COM2 2:COM3) */
extern DllExport void	APIENTRY Vcc1Close(VOID);			/* VCC1終了処理(ONLY RS CLOSE) */
extern DllExport void	APIENTRY Vcc1Reset(VOID);			/* カメラRESET処理 */
extern DllExport void	APIENTRY Vcc1Home(VOID);			/* HOME 位置移動処理 */
extern DllExport void	APIENTRY Vcc1Stop(VOID);			/* パン、チルト停止処理 */
extern DllExport void	APIENTRY Vcc1PanRight(VOID);		/* パン右スタート処理 */
extern DllExport void	APIENTRY Vcc1PanLeft(VOID);			/* パン左スタート処理 */
extern DllExport void	APIENTRY Vcc1TiltUp(VOID);			/* チルト上スタート処理 */
extern DllExport void	APIENTRY Vcc1TiltDown(VOID);		/* チルト下スタート処理 */
extern DllExport void	APIENTRY Vcc1ZoomStop(VOID);		/* ZOOM STOP処理 */
extern DllExport void	APIENTRY Vcc1ZoomWide(VOID);		/* ZOOM WIDE処理 */
extern DllExport void	APIENTRY Vcc1ZoomTele(VOID);		/* ZOOM TELE処理 */
extern DllExport void	APIENTRY Vcc1ZoomWide2(VOID);		/* ZOOM WIDE 高速 処理 */
extern DllExport void	APIENTRY Vcc1ZoomTele2(VOID);		/* ZOOM TELE 高速 処理 */
extern DllExport void	APIENTRY Vcc1PowerOn(VOID);			/* 雲台 POWER ON 処理 */
extern DllExport void	APIENTRY Vcc1PowerOff(VOID);		/* 雲台 POWER OFF 処理 */
extern DllExport void	APIENTRY Vcc1FocusAf(VOID);			/* FOCUS_AF 処理 */
extern DllExport void	APIENTRY Vcc1FocusManual(VOID);		/* FOCUS_MANUAL 処理 */	
extern DllExport void	APIENTRY Vcc1FocusNear(VOID);		/* FOCUS_NEAR 処理 */
extern DllExport void	APIENTRY Vcc1FocusFar(VOID);		/* FOCUS_FAR 処理 */
extern DllExport void	APIENTRY Vcc1ShutPro(VOID);			/* SHUTTER PROGRAM MODE 処理 */
extern DllExport void	APIENTRY Vcc1Shut60(VOID);			/* SHUTTER SPEED 1/60sec 処理 */
extern DllExport void	APIENTRY Vcc1Shut100(VOID);			/* SHUTTER SPEED 1/100sec 処理 */
extern DllExport void	APIENTRY Vcc1Init1(VOID);			/* 雲台初期化1 処理 */
extern DllExport void	APIENTRY Vcc1Init2(VOID);			/* 雲台初期化2 処理 */
extern DllExport void	APIENTRY Vcc1HoseiOff(VOID);		/* 逆光補正OFF 処理 */
extern DllExport void	APIENTRY Vcc1HoseiOn(VOID);			/* 逆光補正ON 処理 */
extern DllExport void	APIENTRY Vcc1WhiteNormal(VOID);		/* ﾎﾜｲﾄﾊﾞﾗﾝｽﾉｰﾏﾙ 処理 */
extern DllExport void	APIENTRY Vcc1WhiteLock(VOID);		/* ﾎﾜｲﾄﾊﾞﾗﾝｽﾛｯｸ 処理 */
extern DllExport void	APIENTRY Vcc1ControlerOn(VOID);		/* リモコンＯＮ 処理 */
extern DllExport void	APIENTRY Vcc1ControlerOff(VOID);	/* リモコンＯＦＦ 処理 */
extern DllExport void	APIENTRY Vcc1ThrowOn(VOID);			/* リモコンスルーモードON 処理 */
extern DllExport void	APIENTRY Vcc1ThrowOff(VOID);		/* リモコンスルーモードOFF 処理 */

/* 設定コマンド */
extern DllExport void	APIENTRY Vcc1SetPanSpeed(int);		/* パンモータ速度設定 (100-500) */
extern DllExport void	APIENTRY Vcc1SetTiltSpeed(int);		/* チルトモータ速度設定 (100-500) */
extern DllExport void	APIENTRY Vcc1SetPanPos(int);		/* パン位置設定 (0-1339) */
extern DllExport void	APIENTRY Vcc1SetTiltPos(int);		/* チルト位置設定 (0-650) */
extern DllExport void	APIENTRY Vcc1SetZoomPos(int);		/* ズーム位置設定 (0-128) */
extern DllExport void	APIENTRY Vcc1SavePos(int);			/* 現在位置をプリセット指定番号に登録(1-6) */
extern DllExport void	APIENTRY Vcc1RestorePos(int);		/* プリセット指定番号の位置に移動 (1-6) */

/* 要求コマンド */
extern DllExport int	APIENTRY Vcc1GetPanSpeed(VOID);		/* パンモータ速度要求 (100-500) */
extern DllExport int	APIENTRY Vcc1GetTiltSpeed(VOID);	/* チルトモータ速度要求 (100-500) */
extern DllExport int	APIENTRY Vcc1GetPanPos(VOID);		/* パン位置要求 (0-1339) */
extern DllExport int	APIENTRY Vcc1GetTiltPos(VOID);		/* チルト位置要求 (0-650) */
extern DllExport int	APIENTRY Vcc1GetZoomPos(VOID);		/* ズーム位置要求 (0-128) */
extern DllExport int	APIENTRY Vcc1GetStatus(VOID);		/* 動作ステータス要求 */
extern DllExport int	APIENTRY Vcc1GetPresetStatus(VOID);	/* プリセットステータス要求 */

#ifdef __cplusplus
}
#endif
