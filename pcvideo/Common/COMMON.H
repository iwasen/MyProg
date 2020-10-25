/************************************************************************
 *
 *			ＰＣ－ＶＩＤＥＯ
 *
 *		名称		: 共通ヘッダ
 *		ファイル名	: common.h
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#define	PORTNO		5100
#define PORTNO2		10414
#define	LEN_HOSTNAME	64

typedef short INT16;
typedef unsigned short BOOL16;

#define MAX_PACKET_SIZE	1024

/*	ヘッダフラグ	*/
#define	FLAG_CONxxxT	0x01		/* 接続 */
#define	FLAG_DISCONxxxT	0x02		/* 切断 */
#define	FLAG_REMOTE		0x03		/* リモート設定 */
#define	FLAG_LICENSE	0x04		/* ライセンスチェック */
#define FLAG_LOGINREQ	0x05		/* ログイン要求 */
#define FLAG_LOGIN		0x06		/* ログイン */
#define FLAG_DENY		0x07		/* 接続拒否 */
#define	FLAG_VIDEO		0x0c		/* ビデオデータ */
#define	FLAG_CHAT		0x10		/* チャットデータ */
#define	FLAG_VOICE		0x20		/* ボイスデータ */
#define	FLAG_SECOND		0x40		/* ２ｎｄポート */
#define	FLAG_CALL		0x80		/* 呼び出し */

/*	接続モード	*/
#define	MODE_SENDVIDEO	0x01		/* ビデオ送信有り */
#define	MODE_DLLCALL	0x02		/* DLL 呼び出し */
#define	MODE_CHAT		0x04		/* チャット中 */
#define	MODE_VIDEOREQ	0x08		/* ビデオデータ受付 */
#define	MODE_SENDVOICE	0x10		/* ボイス実行中 */
#define	MODE_VOICEREQ	0x20		/* ボイスデータ受付 */
#define	MODE_NEWVERSION	0x40		/* 新バージョン */
#define	MODE_REFLECT	0x80		/* リフレクタ */

/*	ビデオモード	*/
#define	VIDEO_PALETTE	0x01		/* パレットモード (256色) */
#define	VIDEO_FULLCOLOR	0x02		/* フルカラー */
#define	VIDEO_MONO		0x04		/* モノクロ */
#define	VIDEO_LOQUALITY1 0x10		/* 低品質モード１ */
#define	VIDEO_COMPXY	0x20		/* ブロック位置圧縮 */
#define	VIDEO_LOQUALITY2 0x40		/* 低品質モード２ */
#define	VIDEO_DRAW		0x80		/* ウィンドウ描画 */

/*	リモート設定	*/
#define	REMOTE_REQDATA	0x01		/* データ要求 */
#define	REMOTE_DATA		0x02		/* データ送信 */
#define	REMOTE_SETDATA	0x03		/* データ設定要求 */
#define	REMOTE_DENY		0x04		/* リモート環境設定拒否 */
#define	REMOTE_RESTART	0x05		/* 再起動 */
#define	REMOTE_CHAT		0x06		/* CHAT 開始／終了 */
#define	REMOTE_VOICE	0x07		/* VOICE 開始／終了 */
#define	REMOTE_EXIT		0x08		/* PCVIDEO 終了 */
#define	REMOTE_SVOICE	0x09		/* 音声送信 */
#define	REMOTE_MESSAGE	0x0a		/* メッセージ */
#define	REMOTE_CAMERA_COMMAND 0x0b	/* カメラコマンド */
#define	REMOTE_CAMERA_STATUS 0x0c	/* カメラステータス */
#define	REMOTE_CAMERA_SETINFO 0x0d	/* カメラ情報設定 */
#define	REMOTE_CAPTURE_COMMAND 0x0e	/* リモートキャプチャコマンド */
#define	REMOTE_CAPTURE_RESPONSE 0x0f	/* リモートキャプチャレスポンス */
#define	REMOTE_EXEC_COMMAND 0x10	/* コマンド実行 */
#define REMOTE_DOWNLOAD	0x11		/* ダウンロード */

/*	ライセンスチェック	*/
#define	LICENSE_CHECK	0x01		/* ライセンスチェック */
#define	LICENSE_REPLY	0x02		/* ライセンスチェック応答 */

/*	呼び出し処理タイプ	*/
#define	TYPE_CHAT	0x10
#define	TYPE_VOICE	0x20

/*	呼び出し要求・応答ステータス	*/
#define	ST_CALL		0x01
#define	ST_ANSWER	0x02

/*	呼び出しステータス	*/
#define	ST_OK			0x01
#define	ST_CANCEL		0x02
#define	ST_ACTIVE		0x04
#define	ST_NOSUPPORT	0x08

#define	CHANNEL_GLOBAL	255

#define	BLOCK_PIXEL	8

/*	ビデオの色数	*/
#define	COLOR_8		0
#define	COLOR_16	1
#define	COLOR_24	2

/*	カメラ種類	*/
#define	CAMERA_VCC1	1

/*	環境設定データ	*/
struct	SETTINGS {
	char	userName[32];
	BOOL16	bVideoCapture;
	BOOL16	bReceiveVideo;
	BOOL16	bRemoteSetting;
	INT16	movieSize;
	INT16	stillSize;
	INT16	videoColor;
	INT16	videoQuality;
	INT16	maxBPS;
	BOOL16	bMaxSpeed;
	BOOL16	bSendInfo;
	BOOL16	bResize;
	BOOL16	bFixedAspect;
	BOOL16	bFixedSize;
	INT16	recvSize;
	BOOL16	bDispInfo;
	BOOL16	bFrontKeep;
	INT16	videoEdgeEnhance;
	BOOL16	bCameraChange;
	BOOL16	bFullFrame;
	INT16	cameraType;
	char	reserved[8];
};

/*	カメラ(VC-C1)設定データ	*/
struct	VCC1DATA {
	INT16	serialPort;
	INT16	panSpeed;
	INT16	tiltSpeed;
	INT16	zoomSpeed;
	INT16	shutterSpeed;
	INT16	audioVolume;
	BOOL16	bAutoFocus;
	BOOL16	bLightAdjust;
	BOOL16	bAutoWhiteBalance;
	BOOL16	bWideConverter;
	struct	{
		INT16	panPos;
		INT16	tiltPos;
		INT16	zoomPos;
		char	comment[31];
	} preset[8];
};

struct	CAMERA_STATUS	{
	INT16	status;
	INT16	panPos;
	INT16	tiltPos;
	INT16	zoomPos;
	char	userName[32];
	VCC1DATA vcc1;
};

struct	CAMERA_COMMAND	{
	char	command;
	INT16	param1;
	INT16	param2;
	INT16	param3;
};

/*	共通パケットヘッダ	*/
typedef	struct	{
	char	flag;
	DWORD	id;
} HEADER, *PHEADER;

/*	接続パケット	*/
typedef	struct	{
	HEADER	header;
	char	userName[32];
	char	modeFlag;
	char	channelNo;
} CONxxxT, *PCONxxxT;

/*	ログイン要求パケット	*/
typedef struct {
	HEADER	header;
	char	serverName[32];
} LOGINREQ, *PLOGINREQ;

/*	ログインパケット	*/
typedef struct {
	CONxxxT	conxxxt;
	char	loginName[32];
	char	password[32];
} LOGIN, *PLOGIN;

/*	接続拒否パケット	*/
typedef struct {
	HEADER	header;
	char	userName[32];
	char	message[256];
} DENY, *PDENY;

/*	呼び出しパケット	*/
typedef	struct	{
	HEADER	header;		/* 共通ヘッダ */
	DWORD	id;			/* ＩＰアドレス（受け側） */
	char	type;		/* 処理タイプ */
						/*  TYPE_CHAT	0x10 */
						/*  TYPE_VOICE	0x20 */
	char	callans;	/* 要求 or 応答 */
						/*  ST_CALL	0x01 */
						/*  ST_ANSWER	0x02 */
	char	status;		/* ステータス */
						/* 	ST_OK	0x01 */
						/*  ST_CANCEL	0x02 */
} CALLHDR, *PCALLHDR;

/*	リモート管理パケット	*/
typedef	struct	{
	HEADER	header;
	char	type;
	DWORD	id;
} REMOTE, *PREMOTE;

/*	リモート設定パケット	*/
typedef	struct	{
	REMOTE	remote;
	SETTINGS	settings;
} REMOTE_SETTINGS, *PREMOTE_SETTINGS;

/*	リモートメッセージパケット	*/
typedef	struct	{
	REMOTE	remote;
	char	message[256];
} REMOTE_MSG, *PREMOTE_MSG;

/*	リモートカメラコマンドパケット */
typedef	struct	{
	REMOTE	remote;
	CAMERA_COMMAND	command;
} REMOTE_CAMERA_CMD, *PREMOTE_CAMERA_CMD;

/*	リモートカメラステータスパケット */
typedef	struct	{
	REMOTE	remote;
	INT16	cameraType;
	CAMERA_STATUS	status;
} REMOTE_CAMERA_STS, *PREMOTE_CAMERA_STS;

/*	リモートカメラ情報設定パケット */
typedef	struct	{
	REMOTE	remote;
	INT16	flag;
	VCC1DATA vcc1;
} REMOTE_CAMERA_SET, *PREMOTE_CAMERA_SET;

/*	リモートキャプチャコマンドパケット	*/
typedef	struct	{
	REMOTE	remote;
	INT16	size;
	INT16	color;
	INT16	compressMode;
	INT16	compressQuality;
} REMOTE_CAPTURE_CMD, *PREMOTE_CAPTURE_CMD;

/*	リモートキャプチャレスポンスパケット	*/
typedef	struct	{
	REMOTE	remote;
	INT16	errorCode;
	long	dataSize;
} REMOTE_CAPTURE_RSP, *PREMOTE_CAPTURE_RSP;

/*	リモートコマンド実行パケット	*/
typedef	struct	{
	REMOTE	remote;
	char	command[256];
	UINT	mode;
} REMOTE_EXEC_CMD, *PREMOTE_EXEC_CMD;

//	リモートダウンロード
typedef struct {
	REMOTE	remote;
	char	fileName[256];
} REMOTE_DOWNLOAD_CMD, *PREMOTE_DOWNLOAD_CMD;

/*	ライセンスチェックパケット	*/
typedef	struct	{
	HEADER	header;
	char	mode;
	char	registNo[16];
} LICENSE, *PLICENSE;
