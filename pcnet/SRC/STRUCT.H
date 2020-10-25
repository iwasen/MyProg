/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 構造体定義				*
 *		ファイル名	: struct.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

/*	通信パラメータ	*/
typedef	struct	{
	SHORT	conxxxt;	/* 接続状態 */
	SHORT	baudRate;	/* ボーレート */
	SHORT	chDataBits;	/* キャラクタビット長 */
	SHORT	chStopBits;	/* ストップビット長 */
	SHORT	chParity;	/* パリティー */
} COMPARAM;

/*	モデム情報	*/
typedef	struct	{
	CHAR	initCmd[21];	/* モデム初期化コマンド */
	CHAR	detectCall;	/* 着信検出方法 */
	CHAR	ringMsg[5];	/* 着信メッセージ */
	CHAR	conxxxtCmd[11];	/* 着信コマンド */
	CHAR	disconxxxt;	/* 回線切断方法 */
	CHAR	escCode[5];	/* エスケープコード */
	SHORT	guardTime;	/* エスケープガードタイム */
	CHAR	discCmd[5];	/* 回線切断コマンド */
	SHORT	dtrOffTime;	/* 回線切断時のＤＴＲオフ時間 */
} MODEM;

/*	環境設定データ	*/
typedef	struct	{
	CHAR	systemID[9];	/* システムＩＤ */
	CHAR	editor[33];	/* エディタファイル名 */
	CHAR	edOption[17];	/* エディタオプション */
	SHORT	timer;		/* 無応答切断時間 */
	CHAR	passWord[9];	/* システム管理者パスワード */
	CHAR	mailFlag;	/* メール到着通知有無フラグ */
	CHAR	logSave;	/* 通信内容保存有無フラグ */
} ENVDATA;

/*	環境設定ファイル	*/
typedef	struct	{
	COMPARAM	comParam[N_CHANNEL];	/* 通信パラメータ */
	MODEM		modem[N_CHANNEL];	/* モデム情報 */
	ENVDATA		env;			/* 環境設定データ */
} SAVEDATA;

/*	ログイン通知情報	*/
typedef	struct	{
	USHORT	chNo;		/* チャネル番号 */
	CHAR	userID[9];	/* ユーザＩＤ */
	struct	{
		USHORT	year;
		CHAR	month;
		CHAR	day;
	} loginDate;		/* ログイン日付 */
	struct	{
		CHAR	hour;
		CHAR	minutes;
		CHAR	seconds;
	} loginTime;		/* ログイン時間 */
	CHAR	logFile[13];	/* 通信内容保存ファイル名 */
} LOGIN;

/*	ログアウト通知情報	*/
typedef	struct	{
	USHORT	chNo;		/* チャネル番号 */
	struct	{
		CHAR	hour;
		CHAR	minutes;
		CHAR	seconds;
	} logoutTime;		/* ログアウト時間 */
	ULONG	usedTime;	/* 使用時間 */
} LOGOUT;

/*	通信履歴ファイル	*/
typedef	struct	{
	CHAR	chNo[1];	/* チャネル番号 */
	CHAR	userID[8];	/* ユーザＩＤ */
	CHAR	loginDate[8];	/* ログイン日付 */
	CHAR	loginTime[8];	/* ログイン時間 */
	CHAR	logoutTime[8];	/* ログアウト時間 */
	CHAR	usedTime[8];	/* 使用時間 */
	CHAR	fileName[12];	/* 通信内容保存ファイル名 */
} LOGDATA;

/*	ユーザ管理情報	*/
typedef	struct	{
	CHAR	userID[8];	/* ユーザＩＤ */
	CHAR	password[8];	/* パスワード */
	CHAR	group[8];	/* グループ */
	CHAR	userLevel[1];	/* 許可レベル */
	CHAR	furikana[16];	/* ふりがな */
	CHAR	name[16];	/* 氏名 */
	CHAR	bikou[40];	/* 備考 */
	CHAR	sDate[8];	/* 接続開始日 */
	CHAR	eDate[8];	/* 最終接続日 */
	CHAR	cCount[6];	/* 接続回数 */
	CHAR	cTime[10];	/* トータル通信時間 */
	CHAR	logSave[1];	/* 通信内容保存有無フラグ */
	CHAR	zipNo[6];	/* 郵便番号 */
	CHAR	address1[40];	/* 住所１ */
	CHAR	address2[40];	/* 住所２ */
	CHAR	company[40];	/* 会社名 */
	CHAR	section[40];	/* 部署 */
	CHAR	tel[15];	/* 電話番号 */
} USERDBF;

/*	販売情報	*/
typedef	struct	{
	CHAR	userID[8];	/* ユーザＩＤ */
	CHAR	sexxxn[40];	/* 製品名 */
	CHAR	serial[16];	/* シリアル番号 */
	CHAR	date[8];	/* 購入日付 */
	CHAR	price[12];	/* 購入価格 */
	CHAR	shop[40];	/* 購入店 */
	CHAR	bikou[40];	/* 備考 */
} SALESDBF;

/*	掲示板管理情報	*/
typedef	struct	{
	CHAR	name[8];	/* 掲示板名 */
	CHAR	title[20];	/* 掲示板タイトル */
	CHAR	date[17];	/* 登録日付 */
	CHAR	type[1];	/* 掲示ファイルタイプ */
	CHAR	listSeq[1];	/* 一覧表示順序 */
	CHAR	userWrite[1];	/* ユーザ書き込み許可フラグ */
} BBSMDBF;

/*	掲示物管理情報	*/
typedef	struct	{
	CHAR	userID[8];	/* 登録ユーザＩＤ */
	CHAR	name[12];	/* 掲示ファイル名 */
	CHAR	date[17];	/* 登録日付 */
	CHAR	title[40];	/* 掲示物タイトル */
	CHAR	userLevel[1];	/* 許可レベル */
	CHAR	size[8];	/* ファイルサイズ */
	CHAR	fileName[12];	/* 格納ファイル名 */
} BBSDBF;

/*	メール管理情報	*/
typedef	struct	{
	CHAR	sUserID[8];	/* 送信元ユーザＩＤ */
	CHAR	rUserID[8];	/* 送信先ユーザＩＤ */
	CHAR	title[40];	/* タイトル */
	CHAR	sDate[17];	/* 送信日付 */
	CHAR	rDate[17];	/* 受信日付 */
	CHAR	sDelete[1];	/* 送信側削除フラグ */
	CHAR	rDelete[1];	/* 受信側削除フラグ */
	CHAR	type[1];	/* 種別 */
	CHAR	fileName[12];	/* ファイル名 */
} MAILDBF;

/*	データ入力テーブル	*/
typedef	struct	{
	USHORT	type;		/* フィールドタイプ */
	USHORT	id;		/* フィールドＩＤ */
	USHORT	field;		/* フィールド長 */
	VOID	*ptr;		/* バッファポインタ */
} ENTRY_TBL;

/*	データ表示テーブル	*/
typedef	struct	{
	USHORT	menuID;		/* メニューＩＤ */
	BOOL	flag;		/* 表示フラグ */
	CHAR	*msg;		/* 項目名 */
	USHORT	length;		/* 表示桁数 */
	CHAR	*ptr;		/* バッファポインタ */
	USHORT	fieldLeng;	/* フィールド長 */
} DISP_TBL;

/*	ファイル名入力パラメータ	*/
typedef	struct	{
	CHAR	*title;
	CHAR	*bufp;
	USHORT	bufSize;
} INPUTFN;
