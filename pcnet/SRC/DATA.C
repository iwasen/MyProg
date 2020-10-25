/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 外部変数				*
 *		ファイル名	: data.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"

char	systemName[] = "ＰＣ－ＮＥＴ／２";	/* システム名 */
char	version[] = "Version 1.00A";		/* バージョンＮｏ． */

/*	ウィンドウクラス名	*/
CHAR	szMainClassName[] = "MAIN_WINDOW";
CHAR	szMonitorClassName[] = "MONITOR_WINDOW";
CHAR	szUserClassName[] = "USER_WINDOW";
CHAR	szBBSClassName[] = "BBS_WINDOW";
CHAR	szMailClassName[] = "MAIL_WINDOW";
CHAR	szTerminalClassName[] = "TERMINAL_WINDOW";

HAB	hab;			/* アンカーブロックハンドル */
HWND	hwndMain;		/* メインウィンドウハンドル */
HWND	hwndUser;		/* ユーザ管理ウィンドウハンドル */
HWND	hwndBBS;		/* 掲示板管理ウィンドウハンドル */
HWND	hwndMail;		/* メール管理ウィンドウハンドル */
HWND	clientMonitor[N_CHANNEL];	/* モニターウィンドウハンドル */
HWND	clientTerminal[N_CHANNEL];	/* ターミナルウィンドウハンドル */
SAVEDATA	sv = {		/* 環境設定データ */
	{
		{PORT_LOCAL,  0, 0, 0, 0},
		{PORT_MODEM,  4, 1, 0, 0},
		{PORT_NOTUSE, 4, 1, 0, 0},
		{PORT_NOTUSE, 4, 1, 0, 0}
	},
	{
		{"", 0, "", "", 0, "", 0, "", 0},
		{"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3},
		{"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3},
		{"ATZ", 'R', "RING", "ATA", 'D', "+++", 2, "ATH", 3}
	},
	{
		"SYS00000",
		"E.EXE",
		"",
		3,
		"",
		'*',
		'*'
	}
};
char	*portName[N_CHANNEL] = {"", "com1", "com2", "com3"};	/* ポート名 */
BOOL	portOpenFlag[N_CHANNEL];	/* ポートオープンフラグ */
HFILE	portHndl[N_CHANNEL];		/* ポートハンドル */
HFILE	ch0Hndl;			/* チャネル０書き込みハンドル */
USHORT	threadID[N_CHANNEL];		/* スレッドＩＤ */
BOOL	monitorFlag[N_CHANNEL];		/* モニター中フラグ */
USHORT	terminalMode[N_CHANNEL];	/* ターミナルモード */
FILE	*termXmodemFP[N_CHANNEL];	/* ＸＭＯＤＥＭ送受信ファイルポインタ*/
BOOL	closeRequest;			/* 全チャネルクローズ要求フラグ */
USHORT	receiveTimer[N_CHANNEL];	/* 無応答監視タイマー値 */
BOOL	conxxxtFlag[N_CHANNEL];		/* 接続中フラグ */
FILE	*logFp[N_CHANNEL];		/* 通信内容保存ファイルポインタ */
SHORT	cxScreen;			/* 画面の横方向の大きさ */
SHORT	cyScreen;			/* 画面の縦方向の大きさ */
SHORT	gcyChar;			/* 文字の高さ */
SHORT	gcxAveChar;			/* 文字の平均幅 */
SHORT	gcxMaxChar;			/* 文字の最大幅 */
BOOL	echoBackFlag[N_CHANNEL];	/* エコーバックフラグ */
CHAR	cUserID[N_CHANNEL][9];		/* カレントユーザＩＤ */
CHAR	cGroup[N_CHANNEL][9];		/* カレントユーザグループ */
CHAR	cUserLevel[N_CHANNEL];		/* カレント許可レベル */

/*	データファイル	*/
CHAR	fnEnvData[] = "ENV.DAT";	/* 環境設定ファイル名 */
CHAR	fnOperation[] = "*.OPR";	/* 操作定義ファイル */

/*	メッセージファイル	*/
CHAR	fnConxxxtMsg[] = "CONxxxT.MSG";	/* 接続時メッセージ */
CHAR	fnLoginMsg[] = "LOGIN.MSG";	/* ログインメッセージ */

/*	通信履歴ファイル	*/
CHAR	dnLOGFILE[] = "LOGFILE";	/* ディレクトリ名 */
CHAR	fnLOGDBF[] = "LOG.DBF";		/* データファイル名 */
CHAR	fnLOGNDX[] = "LOG.NDX";		/* インデックスファイル名 */
DB	*logDBp;			/* データベース管理ポインタ */

/*	ユーザ管理ファイル	*/
CHAR	fnUSERDBF[] = "USER.DBF";	/* データファイル名 */
CHAR	fnUSERIDNDX[] = "USER_U.NDX";	/* ユーザＩＤ用インデックスファイル名*/
CHAR	fnFURIGANANDX[] = "USER_F.NDX"; /* ふりがな用インデックスファイル名*/
SHORT	indexUserID;			/* ユーザＩＤ用インデックス番号 */
SHORT	indexFurigana;			/* ふりがな用インデックス番号 */
DB	*userDBp;			/* データベース管理ポインタ */

/*	販売情報ファイル	*/
CHAR	fnSALESDBF[] = "SALES.DBF";	/* データファイル名 */
CHAR	fnSALESNDX[] = "SALES.NDX";	/* インデックスファイル名 */
SHORT	indexSales;			/* インデックス番号 */
DB	*salesDBp;			/* データベース管理ポインタ */

/*	掲示板管理ファイル	*/
CHAR	dnBBS[] = "BBS";		/* ディレクトリ名 */
CHAR	fnBBSMDBF[] = "BBSM.DBF";	/* データファイル名 */
CHAR	fnBBSMNDX[] = "BBSM.NDX";	/* インデックスファイル名 */
SHORT	indexBBSM;			/* インデックス番号 */
DB	*BBSMDBp;			/* データベース管理ポインタ */

/*	掲示物管理ファイル	*/
CHAR	fnBBSDBF[] = "BBS.DBF";		/* データファイル名 */
CHAR	fnBBSNDX[] = "BBS.NDX";		/* インデックスファイル名 */

/*	メール管理ファイル	*/
CHAR	dnMAIL[] = "MAIL";		/* ディレクトリ名 */
CHAR	fnMAILDBF[] = "MAIL.DBF";	/* データファイル名 */
CHAR	fnMAILANDX[] = "MAIL_A.NDX";	/* 全メールインデックスファイル名 */
CHAR	fnMAILS1NDX[] = "MAIL_S1.NDX";	/* 送信ユーザＩＤｲﾝﾃﾞｯｸｽﾌｧｲﾙ名 */
CHAR	fnMAILR1NDX[] = "MAIL_R1.NDX";	/* 受信ユーザＩＤｲﾝﾃﾞｯｸｽﾌｧｲﾙ名 */
CHAR	fnMAILS2NDX[] = "MAIL_S2.NDX";	/* 送信ユーザＩＤｲﾝﾃﾞｯｸｽﾌｧｲﾙ名 */
CHAR	fnMAILR2NDX[] = "MAIL_R2.NDX";	/* 受信ユーザＩＤｲﾝﾃﾞｯｸｽﾌｧｲﾙ名 */
SHORT	indexMailA;			/* 全メールインデックス番号 */
SHORT	indexMailS1;			/* 送信ユーザＩＤインデックス番号１ */
SHORT	indexMailR1;			/* 受信ユーザＩＤインデックス番号１ */
SHORT	indexMailS2;			/* 送信ユーザＩＤインデックス番号２ */
SHORT	indexMailR2;			/* 受信ユーザＩＤインデックス番号２ */
DB	*mailDBp;			/* データベース管理ポインタ */

/*	システム異常終了チェックファイル	*/
CHAR	fnFCHECK[] = "PCNET.CHK";
