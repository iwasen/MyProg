/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: システム情報ヘッダ
 *		ファイル名	: pcssys.h
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#define	TCPIP_PORT		5000
#define	MAX_PARAM_SIZE	256
#define	MIN_PARAM_SIZE	8
#define	MAX_FILE_NAME	128

#define	MAX_RS232C_CHANNEL	10

#define	ID_COMMAND			'C'
#define	ID_COMMAND_ASYNC	'A'
#define	ID_COMMAND_MASTER	'M'
#define	ID_COMMAND_SLAVE	'S'
#define	ID_RESPONSE			'R'
#define	ID_CONTINUE			0x20

#define	FILETYPE_NULL		0
#define	FILETYPE_DB			1
#define	FILETYPE_RF			2
#define	FILETYPE_FORWARD	4

#define	RS_ACK		0x06
#define	RS_NAK		0x15
#define	RS_SOH		0x01
#define	RS_STX		0x02
#define	RS_ETX		0x03

typedef	struct	{
	CHAR	id;
	USHORT	seqNo;
	USHORT	code;
	USHORT	paramLength;
	USHORT	dataLength;
	CHAR	param[MAX_PARAM_SIZE];
} COMMAND;

typedef	struct	{
	CHAR	id;
	USHORT	seqNo;
	USHORT	dataLength;
	union	{
		SHORT	sValue;
		LONG	lValue;
	} ret;
	SHORT	status;
} RESPONSE;

typedef	struct	{
	CHAR	id;
	CHAR	endMark;
	SHORT	dataLength;
	CHAR	data[1020];
} BINDATA;

typedef	struct	{
	SHORT	recSize;
} DBINF, *PDBINF;

/*	モデム情報	*/
typedef	struct	{
	CHAR	initCmd[21];	/* モデム初期化コマンド */
	CHAR	detectCall;		/* 着信検出方法 */
	CHAR	ringMsg[5];		/* 着信メッセージ */
	CHAR	conxxxtCmd[11];	/* 着信コマンド */
	CHAR	disconxxxt;		/* 回線切断方法 */
	CHAR	escCode[5];		/* エスケープコード */
	SHORT	guardTime;		/* エスケープガードタイム */
	CHAR	discCmd[5];		/* 回線切断コマンド */
	SHORT	dtrOffTime;		/* 回線切断時のＤＴＲオフ時間 */
} MODEM;

/*	RS-232C パラメータ	*/
typedef	struct	{
	SHORT	conxxxt;		/* 接続状態 */
	SHORT	baudRate;		/* ボーレート */
	MODEM	modem;			/* モデム情報 */
} RSPARAM;

/*	環境設定データ(PCSERVE.INI)	*/
typedef	struct	{
	SHORT	bNetBIOS;
	CHAR	pipeName[34];
	SHORT	bTCPIP;
	USHORT	portNo;
	RSPARAM	rsParam[N_RS232C];	/* RS-232C パラメータ */
	SHORT	serverLog;
	LONG	maxLogFileSize;
	SHORT	bNoHideLog;
	CHAR	mailSMTPServer[64];
	CHAR	mailToAddress[64];
	CHAR	mailFromAddress[64];
	SHORT	mailReportStartStop;
	SHORT	mailReportDuplexServer;
	SHORT	mailReportMultiServer;
	SHORT	mailReportSystemError;
	SHORT	bMultiServer;
	SHORT	duplexMode;
	CHAR	duplexServer[64];
	CHAR	duplexAutoRepairTime[6];
	CHAR	reserve[128];
} INIDATA;

#define FN_LOG_DBF			"_LOG.DBF"
#define FN_ERROR_LOG		"ERROR.LOG"

#define FN_DIRNAME_DBF			"_DIR.DBF"
#define FN_DIRNAME_NDX			"_DIR.NDX"
#define LEN_DIRNAME_DIRNAME		8
#define LEN_DIRNAME_DIRECTORY	128

typedef struct {
	char dirName[LEN_DIRNAME_DIRNAME];
	char directory[LEN_DIRNAME_DIRECTORY];
} DBBufDirName;

#define FN_PERM_DBF				"_PERMIT.DBF,TERRA"
#define FN_PERM_NDX				"_PERMIT.EDX"
#define LEN_PERM_DIRECTORY		80
#define LEN_PERM_USERGRPFLG		1
#define LEN_PERM_USERID			10
#define LEN_PERM_PERMISSION		5

typedef struct {
	char directory[LEN_PERM_DIRECTORY];
	char userGrpFlg[LEN_PERM_USERGRPFLG];
	char userID[LEN_PERM_USERID];
	char permission[LEN_PERM_PERMISSION];
} DBBufPerm;

#define FN_SEQNO_DBF			"_SEQNO.DBF"
#define FN_SEQNO_NDX			"_SEQNO.NDX"
#define LEN_SEQNO_SEQNOID		8
#define LEN_SEQNO_COMMENT		30
#define LEN_SEQNO_STARTNO		10
#define LEN_SEQNO_ENDNO			10
#define LEN_SEQNO_STEP			10
#define LEN_SEQNO_COLUMN		1
#define LEN_SEQNO_SEQNO			10

typedef struct {
	char seqNoID[LEN_SEQNO_SEQNOID];
	char comment[LEN_SEQNO_COMMENT];
	char startNo[LEN_SEQNO_STARTNO];
	char endNo[LEN_SEQNO_ENDNO];
	char step[LEN_SEQNO_STEP];
	char column[LEN_SEQNO_COLUMN];
	char seqNo[LEN_SEQNO_SEQNO];
} DBBufSeqNo;

#define FN_HOLIDAY_DBF		"_HOLIDAY.DBF"
#define FN_HOLIDAY_NDX		"_HOLIDAY.NDX"
#define LEN_HOLIDAY_CODE	8
#define LEN_HOLIDAY_WEEK	7
#define LEN_HOLIDAY_COUNT	3
#define LEN_HOLIDAY_HOLIDAY	512

typedef struct {
	char code[LEN_HOLIDAY_CODE];
	char week[LEN_HOLIDAY_WEEK];
	char count[LEN_HOLIDAY_COUNT];
	char holiday[LEN_HOLIDAY_HOLIDAY];
} DBBufHoliday;

#define FN_CALENDAR_DBF		"_CALENDR.DBF"
#define FN_CALENDAR_NDX		"_CALENDR.NDX"
#define LEN_CALENDAR_CODEYEAR	12
#define LEN_CALENDAR_HOLIFLAG	366

typedef struct {
	char codeYear[LEN_CALENDAR_CODEYEAR];
	char HoliFlag[LEN_CALENDAR_HOLIFLAG];
} DBBufCalendar;

#define FN_USER_DBF			"_USER.DBF,MERCURY"
#define FN_USER_NDX			"_USER.EDX"
#define LEN_USER_GROUPFLAG	1
#define LEN_USER_ID			10
#define LEN_USER_PASSWORD	10
#define LEN_USER_NAME		20
#define LEN_USER_COMMENT	50

typedef struct {
	char groupFlag[LEN_USER_GROUPFLAG];
	char id[LEN_USER_ID];
	char password[LEN_USER_PASSWORD];
	char name[LEN_USER_NAME];
	char comment[LEN_USER_COMMENT];
} DBBufUser;

#define FN_USRGRP_DBF		"_USRGRP.DBF"
#define FN_USRGRP1_NDX		"_USRGRP1.EDX"
#define FN_USRGRP2_NDX		"_USRGRP2.EDX"
#define LEN_USRGRP_USERID	10
#define LEN_USRGRP_GROUPID	10

typedef struct {
	char userID[LEN_USRGRP_USERID];
	char groupID[LEN_USRGRP_GROUPID];
} DBBufUsrGrp;

#define FN_TERM_DBF			"_TERM.DBF,MARS"
#define FN_TERM_NDX			"_TERM.EDX"
#define LEN_TERM_GROUPFLAG	1
#define LEN_TERM_ID			10
#define LEN_TERM_COMMENT	50
#define LEN_TERM_USERID		10
#define LEN_TERM_REMOTE		1

typedef struct {
	char groupFlag[LEN_TERM_GROUPFLAG];
	char id[LEN_TERM_ID];
	char comment[LEN_TERM_COMMENT];
	char userID[LEN_TERM_USERID];
	char remote[LEN_TERM_REMOTE];
} DBBufTerm;

#define FN_TRMGRP_DBF		"_TRMGRP.DBF"
#define FN_TRMGRP1_NDX		"_TRMGRP1.EDX"
#define FN_TRMGRP2_NDX		"_TRMGRP2.EDX"
#define LEN_TRMGRP_TERMID	10
#define LEN_TRMGRP_GROUPID	10

typedef struct {
	char termID[LEN_TRMGRP_TERMID];
	char groupID[LEN_TRMGRP_GROUPID];
} DBBufTrmGrp;

#define FN_APPL_DBF			"_APPL.DBF,JUPITER"
#define FN_APPL_NDX			"_APPL.EDX"
#define LEN_APPL_SUBFLAG	1
#define LEN_APPL_SYSTEMID	10
#define LEN_APPL_SSYSTEMID	10
#define LEN_APPL_COMMENT	50

typedef struct {
	char subFlag[LEN_APPL_SUBFLAG];
	char systemID[LEN_APPL_SYSTEMID];
	char subSystemID[LEN_APPL_SSYSTEMID];
	char comment[LEN_APPL_COMMENT];
} DBBufAppl;

#define FN_ACCESS_DBF			"_ACCESS.DBF,VENUS"
#define FN_ACCESS_NDX			"_ACCESS.EDX"
#define LEN_ACCESS_USERGRPFLG	1
#define LEN_ACCESS_USERID		10
#define LEN_ACCESS_TERMGRPFTERMIDLG	1
#define LEN_ACCESS_TERMID		10
#define LEN_ACCESS_SYSTEMID		10
#define LEN_ACCESS_SUBSYSTEM	10

typedef struct {
	char userGrpFlg[LEN_ACCESS_USERGRPFLG];
	char userID[LEN_ACCESS_USERID];
	char termGrpFlg[LEN_ACCESS_TERMGRPFTERMIDLG];
	char termID[LEN_ACCESS_TERMID];
	char systemID[LEN_ACCESS_SYSTEMID];
	char subSystemID[LEN_ACCESS_SUBSYSTEM];
} DBBufAccess;

#define FN_MSERVER_DBF			"_MSERVER.DBF"
#define FN_MSERVER_NDX			"_MSERVER.EDX"
#define LEN_MSERVER_SERVERNAME	20
#define LEN_MSERVER_CHTYPE		1
#define LEN_MSERVER_MASTER		1
#define LEN_MSERVER_COMMENT		50

typedef struct {
	char serverName[LEN_MSERVER_SERVERNAME];
	char chType[LEN_MSERVER_CHTYPE];
	char master[LEN_MSERVER_MASTER];
	char comment[LEN_MSERVER_COMMENT];
} DBBufMServer;

#define FN_MFILE_DBF			"_MFILE.DBF"
#define FN_MFILE1_NDX			"_MFILE1.EDX"
#define FN_MFILE2_NDX			"_MFILE2.EDX"
#define LEN_MFILE_SERVERNAME	20
#define LEN_MFILE_NAME1			78
#define LEN_MFILE_NAME2			78

typedef struct {
	char serverName[LEN_MFILE_SERVERNAME];
	char name1[LEN_MFILE_NAME1];
	char name2[LEN_MFILE_NAME2];
} DBBufMFile;

#define FN_MERROR_DBF			"_MERROR.DBF"
#define FN_MERROR_NDX			"_MERROR.EDX"
#define LEN_MERROR_SERVERNAME	20
#define LEN_MERROR_FILENAME		78
#define LEN_MERROR_ERRORCODE	5
#define LEN_MERROR_TIME			14

typedef struct {
	char serverName[LEN_MERROR_SERVERNAME];
	char fileName[LEN_MERROR_FILENAME];
	char errorCode[LEN_MERROR_ERRORCODE];
	char time[LEN_MERROR_TIME];
} DBBufMError;

#define FN_NETMSG_DBF			"_NETMSG.DBF"
#define FN_NETMSG_NDX			"_NETMSG.NDX"
#define LEN_NETMSG_FLAG			1
#define LEN_NETMSG_GROUPNAME	20
#define LEN_NETMSG_MEMBER		20

typedef struct {
	char flag[LEN_NETMSG_FLAG];
	char groupName[LEN_NETMSG_GROUPNAME];
	char member[LEN_NETMSG_MEMBER];
} DBBufNetMsg;

#define FN_DERROR_DBF			"_DERROR.DBF"
#define FN_DERROR_NDX			"_DERROR.EDX"
