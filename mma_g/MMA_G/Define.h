#pragma once
//*****************************************************************************************************
//  1. ファイル名
//		Define.h
//----------------------------------------------------------------------------------------------------
//  2. 機能
//		define定義
//----------------------------------------------------------------------------------------------------
//  3. 備考
//----------------------------------------------------------------------------------------------------
//  4. 履歴
//		2007.08.09 S.Aizawa 新規作成
//*****************************************************************************************************

// グラフで使う色
#define COLOR_GRAPH_DATA_X	RGB(0xff,0x00,0x80)
#define COLOR_GRAPH_DATA_Y	RGB(0x00,0xc0,0x00)
#define COLOR_GRAPH_DATA_Z	RGB(0x00,0x80,0xff)
#define COLOR_GRAPH_DATA_G	RGB(0x40,0x40,0x40)
#define COLOR_GRAPH_DATA_D	RGB(0x60,0x00,0xc0)
#define COLOR_GRAPH_SCALE	RGB(0x00,0x00,0x00)
#define COLOR_GRAPH_BG		RGB(0xff,0xff,0xff)
#define COLOR_GRAPH_CURSOR	RGB(0xff,0x00,0x00)
#define COLOR_GRAPH_SELECT	RGB(0xb0,0xff,0xff)
#define COLOR_LIMIT_LINE	RGB(0xff,0x00,0x00)
#define COLOR_WHITE			RGB(0xff,0xff,0xff)
#define COLOR_LIGHT_GRAY	RGB(0xb0,0xb0,0xb0)
#define COLOR_MID_GRAY		RGB(0xaa,0xaa,0xaa)
#define COLOR_GRAY			RGB(0x80,0x80,0x80)
#define COLOR_BLACK			RGB(0x00,0x00,0x00)

#define PI 3.14159265358979323846

// 対数変換
#define dB10(x)	(log10((double)(x)) * 10)
#define dB20(x)	(log10((double)(x)) * 20)

// ユーザ定義ウィンドウメッセージ
#define WM_USER_OPEN_GRAPH			(WM_APP + 1)	// 解析グラフウィンドウオープン通知
#define WM_USER_CLOSE_GRAPH			(WM_APP + 2)	// 解析グラフウィンドウクローズ通知
#define WM_USER_REALTIME_GRAPH		(WM_APP + 3)	// リアルタイムグラフウィンドウ通知
#define WM_USER_MESSAGE_BOX			(WM_APP + 4)	// メッセージボックス表示通知
#define WM_USER_AUTO_CONV			(WM_APP + 5)	// 自動工学値変換通知
#define WM_USER_CLOSE_ZERO_OFFSET	(WM_APP + 6)	// 0点補正値グラフウィンドウクローズ通知

// グラフ種類
#define GRAPH_ACCEL		0		// 加速度グラフ
#define GRAPH_TEMP		1		// 温度グラフ
#define GRAPH_ZERO_OFFSET	2	// 0点補正値グラフ
