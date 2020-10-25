/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		モジュール名称	: ｄｅｆｉｎｅ定義			*
 *		ファイル名	: define.h				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#define	N_CHANNEL	4	/* チャネル数 */

#define	CH0	0		/* チャネル０（ローカル） */
#define	CH1	1		/* チャネル１（本体RS-232C） */
#define	CH2	2		/* チャネル２（拡張ボードRS-232C） */
#define	CH3	3		/* チャネル３（拡張ボードRS-232C） */

#define	STACKSIZE	8192	/* スタックサイズ */

/*	ターミナルモード	*/
#define	TMODE_NORMAL	0x00	/* 通常（ターミナルモードでない） */
#define	TMODE_TERMINAL	0x01	/* ターミナルモード */
#define	TMODE_TRCV	0x02	/* テキストファイルに受信中 */
#define	TMODE_TSND	0x04	/* テキストファイルを送信中 */
#define	TMODE_XRCV	0x08	/* ＸＭＯＤＥＭ受信中 */
#define	TMODE_XSND	0x10	/* ＸＭＯＤＥＭ送信中 */

/*	致命的エラーコード	*/
#define	ER_WinInitialine	1
#define	ER_WinCreateMsgQueue	2
#define	ER_WinRegisterClass	3
#define	ER_WinCreateStdWindow	4
#define	ER_malloc		5
#define	ER_DosCreateThread	6

/*	フィールドタイプ	*/
#define	ET_STRINGFIELD		0	/* 左詰め文字列フィールド */
#define	ET_NUMSTRFIELD		1	/* 右詰め文字列フィールド */
#define	ET_STZEROFIELD		2	/* ０で終了する文字列フィールド */
#define	ET_NUMBERFIELD		3	/* 数値フィールド */
#define	ET_RADIOBUTTON		4	/* ラジオボタン */
#define	ET_CHECKBUTTON		5	/* チェックボタン */

/*	リソースＩＤ	*/
#define ID_RESOURCE_MAIN	1	/* メインウィンドウ */
#define ID_RESOURCE_USER	2	/* ユーザ管理ウィンドウ */
#define ID_RESOURCE_BBS		3	/* 掲示板管理ウィンドウ */
#define ID_RESOURCE_MAIL	4	/* メール管理ウィンドウ */
#define ID_RESOURCE_MONITOR	5	/* モニターウィンドウ */
#define ID_RESOURCE_TERMINAL	6	/* ターミナルウィンドウ */

/*	メインウィンドウ　メニューＩＤ	*/
#define	ID_MB			10
#define	IDM_EXIT		1
#define	IDM_ABOUT		2
#define	IDM_MAIN_FILE		0x100
#define	IDM_MAIN_FILE_USER	0x101
#define	IDM_MAIN_FILE_BBS	0x102
#define	IDM_MAIN_FILE_MAIL	0x103
#define	IDM_MAIN_FILE_USERINF	0x104
#define	IDM_MAIN_FILE_LOGDISP	0x105
#define	IDM_MAIN_FILE_LOGFDEL	0x106
#define	IDM_MAIN_FILE_LOGDEL	0x107
#define	IDM_MAIN_SETTEI		0x110
#define	IDM_MAIN_SET_PORT	0x111
#define	IDM_MAIN_SET_ENV	0x112
#define	IDM_MAIN_SET_CMSG	0x113
#define	IDM_MAIN_SET_LMSG	0x114
#define	IDM_MAIN_MONITOR	0x120
#define	IDM_MAIN_MON_CH1	0x121
#define	IDM_MAIN_MON_CH2	0x122
#define	IDM_MAIN_MON_CH3	0x123
#define	IDM_MAIN_TERMINAL	0x130
#define	IDM_MAIN_TERM_CH0	0x131
#define	IDM_MAIN_TERM_CH1	0x132
#define	IDM_MAIN_TERM_CH2	0x133
#define	IDM_MAIN_TERM_CH3	0x134
#define	IDM_MAIN_HELP		0x140
#define	IDM_MAIN_HELP_HELP	0x141
#define	IDM_MAIN_HELP_EXT	0x142
#define	IDM_MAIN_HELP_KEYS	0x143
#define	IDM_MAIN_HELP_INDEX	0x144

/*	ユーザ管理ウィンドウ　メニューＩＤ */
#define	IDM_USER_FILE		0x200
#define	IDM_USER_FILE_NEW	0x201
#define	IDM_USER_FILE_UPDATE	0x202
#define	IDM_USER_FILE_DELETE	0x203
#define	IDM_USER_DISPLAY	0x210
#define	IDM_USER_SEQ_USERID	0x211
#define	IDM_USER_SEQ_FURIGANA	0x212
#define	IDM_USER_DSP_USERID	0x213
#define	IDM_USER_DSP_PASSWORD	0x214
#define	IDM_USER_DSP_GROUP	0x215
#define	IDM_USER_DSP_LEVEL	0x216
#define	IDM_USER_DSP_NAME	0x217
#define	IDM_USER_DSP_FURIGANA	0x218
#define	IDM_USER_DSP_COMPANY	0x219
#define	IDM_USER_DSP_SDATE	0x21a
#define	IDM_USER_DSP_EDATE	0x21b
#define	IDM_USER_DSP_CCOUNT	0x21c
#define	IDM_USER_DSP_CTIME	0x21d
#define	IDM_USER_SEARCH		0x220

/*	掲示板管理ウィンドウ　メニューＩＤ */
#define	IDM_BBS_FILE		0x300
#define	IDM_BBS_FILE_SELECT	0x301
#define	IDM_BBS_FILE_NEW	0x302
#define	IDM_BBS_FILE_UPDATE	0x303
#define	IDM_BBS_FILE_DELETE	0x304
#define	IDM_BBS_FILE_READ	0x305
#define	IDM_BBS_DISPLAY		0x310
#define	IDM_BBS_DSP_CREATE	0x311
#define	IDM_BBS_DSP_USERID	0x312
#define	IDM_BBS_DSP_LEVEL	0x313
#define	IDM_BBS_DSP_FNAME	0x314
#define	IDM_BBS_DSP_SIZE	0x315
#define	IDM_BBS_DSP_TITLE	0x316

/*	メール管理ウィンドウ　メニューＩＤ */
#define	IDM_MAIL_FILE		0x400
#define	IDM_MAIL_FILE_SEND	0x401
#define	IDM_MAIL_FILE_SEND2	0x402
#define	IDM_MAIL_FILE_UPDATE	0x403
#define	IDM_MAIL_FILE_DELETE	0x404
#define	IDM_MAIL_FILE_READ	0x405
#define	IDM_MAIL_DISPLAY	0x410
#define	IDM_MAIL_DSP_SMAIL	0x411
#define	IDM_MAIL_DSP_RMAIL	0x412
#define	IDM_MAIL_DSP_ALL	0x413
#define	IDM_MAIL_DSP_SID	0x414
#define	IDM_MAIL_DSP_RID	0x415
#define	IDM_MAIL_DSP_TYPE	0x416
#define	IDM_MAIL_DSP_SDATE	0x417
#define	IDM_MAIL_DSP_RDATE	0x418
#define	IDM_MAIL_DSP_TITLE	0x419

/*	ターミナルウィンドウ　メニューＩＤ */
#define	IDM_TERM_FILE		0x500
#define	IDM_TERM_FILE_TRCV	0x501
#define	IDM_TERM_FILE_TSND	0x502
#define	IDM_TERM_FILE_XRCV	0x503
#define	IDM_TERM_FILE_XSND	0x504
#define	IDM_TERM_RESET		0x510

/*	キーコマンド	*/
#define	IDK_LINEUP		0x1000
#define	IDK_LINEDOWN		0x1001
#define	IDK_PAGEUP		0x1002
#define	IDK_PAGEDOWN		0x1003
#define	IDK_UP			0x1004
#define	IDK_DOWN		0x1005
#define	IDK_DRAG_UP		0x1006
#define	IDK_DRAG_DOWN		0x1007
#define	IDK_CANCEL		0x1008
#define	IDK_CR			0x1009

/*	接続状態	*/
#define	PORT_MODEM	0	/* モデム */
#define	PORT_DIRECT	1	/* 直結 */
#define	PORT_NOTUSE	2	/* 未使用 */
#define	PORT_LOCAL	3	/* ローカル */

/*	通信パラメータ	*/
#define	CP_BR_300	0	/* ３００ｂｐｓ */
#define	CP_BR_600	1	/* ６００ｂｐｓ */
#define	CP_BR_1200	2	/* １２００ｂｐｓ */
#define	CP_BR_2400	3	/* ２４００ｂｐｓ */
#define	CP_BR_4800	4	/* ４８００ｂｐｓ */
#define	CP_BR_9600	5	/* ９６００ｂｐｓ */
#define	CP_DB_7		0	/* キャラクタビット長　７ビット */
#define	CP_DB_8		1	/* キャラクタビット長　８ビット */
#define	CP_SB_1		0	/* ストップビット長　１ビット */
#define	CP_SB_15	1	/* ストップビット長　１．５ビット */
#define	CP_SB_2		2	/* ストップビット長　２ビット */
#define	CP_PT_NONE	0	/* パリティー無し */
#define	CP_PT_EVEN	1	/* 偶数パリティー */
#define	CP_PT_ODD	2	/* 奇数パリティー */

/*	ユーザ定義メッセージ	*/
#define	WM_USER_MONITOR	(WM_USER+0)
#define	WM_USER_LOGIN	(WM_USER+1)
#define	WM_USER_LOGOUT	(WM_USER+2)
#define	WM_USER_MSGBOX	(WM_USER+3)
#define	WM_USER_MAIL	(WM_USER+4)
#define	WM_USER_RCVTERM1	(WM_USER+5)
#define	WM_USER_RCVTERM2	(WM_USER+6)
#define	WM_USER_XMODEM	(WM_USER+7)

/*	タイマーＩＤ	*/
#define	TMID_CLOSE	1	/* プログラム終了時回線切断待ちタイマー */
#define	TMID_FLUSH	2	/* ファイル強制書き込みタイマー */

#define	KEYS_HELP_HP		9010
#define	PCNET_HELP_TABLE	900

#define	PAGE_UP		0x8000
#define	PAGE_DOWN	0x8001
