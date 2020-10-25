/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: サーバ側共通ヘッダ
 *		ファイル名	: pcserve.h
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsdef.h"
#include "pcssys.h"
#include "pcsfc.h"
#include "pcsdb.h"
#include "pcssn.h"
#include "pcscl.h"
#include "pcscom.h"

#define	MEGABYTE	1048576L

#define	LEN_SERVERNAME	20
#define	LEN_FILENAME	78

/*	RS-232C状態	*/
#define	RS232C_NOTUSE	0	/* 未使用 */
#define	RS232C_MODEM	1	/* モデム */
#define	RS232C_DIRECT	2	/* 直結 */

#define	DUPLEX_MASTER	1
#define	DUPLEX_SLAVE	2

#define	DUPLEX_STATUS_UNLINK	0
#define	DUPLEX_STATUS_NORMAL	1
#define	DUPLEX_STATUS_NODUAL	2
#define	DUPLEX_STATUS_SYNC	3
#define	DUPLEX_STATUS_BACKUP	4

#define MULTI_SERVER_CLIENTID	"PCSERVE"

/*	ファイル制御ブロック	*/
typedef	struct	_FCB	{
	SHORT	fileType;
	int	fileHandle;
	DB	*dbp;
	DBINF	DBInf;
	CHAR	*pFileName;
	struct	_MFCB	*pMFCB;
} FCB, *PFCB;

/*	RS-232C 制御情報	*/
typedef	struct	_RS232C {
	SHORT	portNo;
	HANDLE	handle;
	int	chNo;
	CHAR	*pRcvBuf;
	USHORT	rcvLength;
	USHORT	readCounter;
	BOOL	bStxRcvFlag;
	struct	_SCB	*pSCB[MAX_RS232C_CHANNEL];
} RS232C, *PRS232C;

/*	サービス制御ブロック	*/
typedef	struct	_SCB {
	struct	_SCB	*chainSCB;
	struct	_FCB	*pFCB;
	struct	_MSCB	*pMSCB;
	VOID	(*pMultiServerFunc)(struct _SCB *, struct _FCB *, BOOL);
	SHORT	chType;
	int	handle;
	BOOL	bConxxxtClient;
	BOOL	bLoginUser;
	BOOL	bCloseConxxxtion;
	char userID[12];
	LOGIN	login;
	COMMAND	command;
	RESPONSE	response;
	CHAR	*lpData;
	CHAR	*lpData2;
	SHORT	asyncStatus;
	BOOL	bContinue;
	struct	_FCB	*pCurrentFCB;
	int	hdir;
	PRS232C	rsp;
	int	nFCB;
	BOOL bMultiPertner;
} SCB, *PSCB;

typedef	struct	_CCB {
	BOOL	bConxxxt;
	USHORT	chType;
	int	handle;
	COMMAND	command;
	RESPONSE response;
	VOID	*pInData;
	VOID	*pOutData;
	CRITICALSECTION	csCCB;
} CCB, *PCCB;

/*	ログファイル	*/
typedef	struct	{
	CHAR	clientID[8];		/* クライアントＩＤ */
	CHAR	computerName[16];	/* コンピュータ名 */
	CHAR	chType[1];			/* 接続種別 */
	CHAR	loginDate[8];		/* 接続開始日付 */
	CHAR	loginTime[8];		/* 接続開始時間 */
	CHAR	logoutTime[8];		/* 接続終了時間 */
	CHAR	usedTime[8];		/* 使用時間 */
	CHAR	reserved[12];		/* 予備 */
} LOGDATA;

/*	分散サーバ管理ファイル	*/
typedef	struct	{
	CHAR	serverName[LEN_SERVERNAME];
	CHAR	chType[1];
	CHAR	master[1];
	CHAR	comment[50];
} MULTISERVER, *PMULTISERVER;

/*	分散ファイル管理ファイル	*/
typedef	struct	{
	CHAR	serverName[LEN_SERVERNAME];
	CHAR	fileName1[LEN_FILENAME];
	CHAR	fileName2[LEN_FILENAME];
} MULTIFILE, *PMULTIFILE;

/*	分散エラー管理ファイル	*/
typedef	struct	{
	CHAR	serverName[LEN_SERVERNAME];
	CHAR	fileName[LEN_FILENAME];
	CHAR	errorCode[5];
	CHAR	time[14];
} MULTIERROR, *PMULTIERROR;

/*	分散サーバ管理ブロック	*/
typedef	struct	_MSCB {
	CHAR	serverName[LEN_SERVERNAME + 1];
	int	chType;
	BOOL	bMaster;
	int	id;
	u_long	ipAddress;
	BOOL	bActive;
	CHAR	*lpData;
	CCB	ccb;
	struct	_MSCB	*chain;
} MSCB, *PMSCB;

/*	分散ファイル管理ブロック	*/
typedef	struct	_MFCB {
	CHAR	fileName1[LEN_FILENAME + 1];
	CHAR	fileName2[LEN_FILENAME + 1];
	CHAR	password[9];
	int	handle;
	SHORT	errorCode;
	BOOL	bErrorPending;
	BOOL	bForward;
	LONG	recNo;
	LONG	recCount;
	LONG	offset;
	struct	_MSCB	*pMSCB;
	struct	_MFCB	*chain;
} MFCB, *PMFCB;

/*	二重化サーバ管理ブロック	*/
typedef	struct	_DUPLEXSERVER {
	CHAR	serverName[64];
	int	chType;
	int	mode;
	int	autoRepairTime;
	BOOL	bAutoRepair;
	CRITICALSECTION	csCommand;
	CCB	ccb;
} DUPLEXSERVER, *PDUPLEXSERVER;

/*	二重化ファイル管理ブロック	*/
typedef	struct	_DUPLEXFILE {
	CHAR	fileName[FILENAME_MAX];
	HANDLE	handle;
	long	accessTime;
	struct	_DUPLEXFILE *chain;
} DUPLEXFILE, *PDUPLEXFILE;

/*	二重化エラー管理ファイル	*/
typedef	struct	_DUPLEXERROR {
	CHAR	fileName[LEN_FILENAME];
	CHAR	errorCode[5];
	CHAR	time[14];
} DUPLEXERROR, *PDUPLEXERROR;

/*	pcserve.c	*/
extern	CHAR	version[];
extern	CHAR	dnLOGFILE[];
extern	CHAR	fnLOGDBF[];
extern	CHAR	fnIniData[];
extern	CHAR	fnERRLOG[];
extern	PDB	pLogDB;
extern	PDB	pDirNameDB;
extern	PDB	pPermissionDB;
extern	PDB	pMultiServerDB;
extern	PDB	pMultiFileDB;
extern	PDB	pMultiErrorDB;
extern	int	logLastRec;
extern	INIDATA	ini;
extern	PSCB	pTopSCB;
extern	SHORT	maxFiles;
extern	int	sock;
extern	SHORT	debugMode;
extern	SHORT	remoteMente;
extern	CRITICALSECTION	csSCB, csLogin, csSN, csSort;
extern	STATISTICS	statistics;
extern	BOOL	bLicense;
extern	VOID	Terminate(VOID);

/*	net.c		*/
extern	VOID	StartTCPIP(VOID);
extern	VOID	StartNetBIOS(VOID);
extern	VOID	StartRS232C(VOID);
extern	BOOL	SendData(PSCB, VOID *, UINT);
extern	BOOL	ReceiveData(PSCB, VOID *, UINT);

/*	cmd.c		*/
extern	BOOL	CommandService(PSCB);
extern	BOOL	ReceiveCommand(PSCB);
extern	BOOL	SendResponse(PSCB, BOOL);
extern	VOID	ConxxxtClient(PSCB);
extern	VOID	DisconxxxtClient(PSCB);
extern	int	AllocFCB(PSCB);
extern	PFCB	GetFCB(PSCB, int);
extern	VOID	OpenLogFile(VOID);
extern	VOID	CloseLogFile(VOID);

/*	sps.c		*/
extern	VOID	ExecPSConxxxt(PSCB);
extern	VOID	ExecPSDisconxxxt(PSCB);
extern	VOID	ExecPSLogin(PSCB);
extern	VOID	ExecPSExecCommand(PSCB);
extern	VOID	ExecPSGetTime(PSCB);
extern	VOID	ExecPSGetAsyncStatus(PSCB);
extern	VOID	ExecPSExecSQL(PSCB);
extern	VOID	ExecPSGetSQL(PSCB);

/*	multi.c		*/
extern	VOID	CloseMultiServer(PSCB);
extern	VOID	ForwardCommand(PSCB, PFCB);
extern	SHORT	MDBOpen(PSCB, PFCB, CHAR *);
extern	SHORT	MDBCreate(PSCB, PFCB, CHAR *);
extern	int	MDBClose(PSCB, PFCB, BOOL);
extern	int	MDBFlush(PSCB, PFCB, BOOL);
extern	int	MDBIndex(PSCB, PFCB, BOOL);
extern	int	MDBIdxCreate(PSCB, PFCB, BOOL);
extern	int	MDBChgIdx(PSCB, PFCB, BOOL);
extern	int	MDBSearchLock(PSCB, PFCB, BOOL);
extern	int	MDBStore(PSCB, PFCB, BOOL);
extern	int	MDBStoreUniq(PSCB, PFCB, BOOL);
extern	int	MDBUpdate(PSCB, PFCB, BOOL);
extern	int	MDBUpdateKey(PSCB, PFCB, BOOL);
extern	int	MDBDelete(PSCB, PFCB, BOOL);
extern	int	MDBRecall(PSCB, PFCB, BOOL);
extern	int	MDBDelete2(PSCB, PFCB, BOOL);
extern	int	MDBDelete3(PSCB, PFCB, BOOL);
extern	int	MDBDeleteKey(PSCB, PFCB, BOOL);
extern	int	MDBReindex(PSCB, PFCB, BOOL);
extern	int	MDBPack(PSCB, PFCB, BOOL);
extern	int	MDBReadKey(PSCB, PFCB, BOOL);
extern	int	MDBAddRecord(PSCB, PFCB, BOOL);
extern	int	MDBUpdRecord(PSCB, PFCB, BOOL);
extern	int	MDBSetFilter(PSCB, PFCB, BOOL);
extern	int	MDBSetDeleted(PSCB, PFCB, BOOL);
extern	int	MDBZip(PSCB, PFCB, BOOL);
extern	int	MDBLock(PSCB, PFCB, BOOL);
extern	int	MDBUnlock(PSCB, PFCB, BOOL);
extern	int	MDBSetScramble(PSCB, PFCB, BOOL);
extern	int	MDBSetPassword(PSCB, PFCB, BOOL);
extern	int	MDBPackIndex(PSCB, PFCB, BOOL);
extern	int	MDBWriteBinary(PSCB, PFCB, BOOL);
extern	SHORT	MRFOpen(PSCB, PFCB, CHAR *);
extern	SHORT	MRFCreate(PSCB, PFCB, CHAR *);
extern	int	MRFClose(PSCB, PFCB, BOOL);
extern	int	MRFWrite(PSCB, PFCB, BOOL);
extern	int	MRFLock(PSCB, PFCB, BOOL);
extern	int	MRFUnlock(PSCB, PFCB, BOOL);
extern	int	MRFNewSize(PSCB, PFCB, BOOL);
extern	int	MCFDelete(PSCB, PFCB, BOOL);
extern	BOOL	StartSyncMultiFile(CHAR *);
extern	BOOL	MultiCopyInfo(VOID);

/*	duplex.c	*/
extern	VOID	StartDuplexServer(VOID);
extern	VOID	ExecPSDuplexError(PSCB);
extern	VOID	ExecPSDuplexKeepAlive(PSCB);
extern	VOID	ExecPSDuplexCreate(PSCB);
extern	VOID	ExecPSDuplexWrite(PSCB);
extern	VOID	ExecPSDuplexNewSize(PSCB);
extern	VOID	ExecPSDuplexDelete(PSCB);
extern	VOID	ExecPSDuplexFileTime(PSCB);
extern	VOID	ExecPSDuplexCopy(PSCB);
extern	VOID	ExecPSDuplexRename(PSCB);
extern	BOOL	StartSyncDuplexFile(CHAR *);
extern	int	GetDuplexStatus(VOID);
extern	VOID	GetDuplexInfo(PDUPLEXINFO);
extern	VOID	SetDuplexInfo(PDUPLEXINFO);

/*	sfunc.c		*/
extern	int	PSConxxxt(PCCB, int, CHAR *, CHAR *);
extern	int	PSDisconxxxt(PCCB);
extern	int	PSDuplexKeepAlive(PCCB, int);
extern	int	PSDuplexError(PCCB, int, CHAR *, int);
extern	int	PSDuplexCreate(PCCB, int, CHAR *);
extern	int	PSDuplexWrite(PCCB, int, char *, char *, int, long);
extern	int	PSDuplexNewSize(PCCB, int, char *, int);
extern	int	PSDuplexDelete(PCCB, int, char *);
extern	int	PSDuplexFileTime(PCCB, int, char *, LPFILETIME, LPFILETIME,
								LPFILETIME);
extern	int	PSDuplexCopy(PCCB, int, char *, char *);
extern	int	PSDuplexRename(PCCB, int, char *, char *);
extern	int	PSDBOpen(PCCB, int, CHAR *);
extern	int	PSDBCreate(PCCB, int, CHAR *, PDBF_I, int);
extern	int	PSDBClose(PCCB, int, int);
extern	int	PSDBFlush(PCCB, int, int);
extern	int	PSDBIndex(PCCB, int, int, CHAR *);
extern	int	PSDBIdxCreate(PCCB, int, int, CHAR *, CHAR *, int);
extern	int	PSDBChgIdx(PCCB, int, int, int);
extern	int	PSDBSearchLock(PCCB, int, int, CHAR *, int);
extern	int	PSDBStore(PCCB, int, int, CHAR *, int);
extern	int	PSDBStoreUniq(PCCB, int, int, CHAR *, int);
extern	int	PSDBUpdateEx(PCCB, int, int, CHAR *, int, LONG, LONG);
extern	int	PSDBUpdateKey(PCCB, int, int, CHAR *, int, CHAR *, int);
extern	int	PSDBDeleteEx(PCCB, int, int, LONG, LONG);
extern	int	PSDBRecallEx(PCCB, int, int, LONG, LONG);
extern	int	PSDBDelete2Ex(PCCB, int, int, LONG, LONG);
extern	int	PSDBDeleteKey(PCCB, int, int, CHAR *, int, int);
extern	int	PSDBReindex(PCCB, int, int);
extern	int	PSDBPack(PCCB, int, int);
extern	int	PSDBReadKey(PCCB, int, int, CHAR *, int, CHAR *, int);
extern	int	PSDBSetFilter(PCCB, int, int, CHAR *);
extern	int	PSDBSetDeleted(PCCB, int, int, int);
extern	int	PSDBZip(PCCB, int, int);
extern	int	PSDBLockEx(PCCB, int, int, LONG, LONG, int);
extern	int	PSDBUnlock(PCCB, int, int, int);
extern	int	PSDBSetScramble(PCCB, int, int);
extern	int	PSDBSetPassword(PCCB, int, int, CHAR *);
extern	int	PSDBPackIndex(PCCB, int, CHAR *);
extern	int	PSDBWriteBinaryEx(PCCB, int, int, CHAR *, CHAR *, LONG, LONG,
								LONG, LONG);
extern	int	PSRFOpen(PCCB, int, CHAR *, int, int);
extern	int	PSRFCreate(PCCB, int, CHAR *, int, int);
extern	int	PSRFClose(PCCB, int, int);
extern	int	PSRFWrite(PCCB, int, int, VOID *, int);
extern	int	PSRFWriteEx(PCCB, int, int, CHAR *, LONG, int);
extern	int	PSRFLock(PCCB, int, int, LONG, LONG);
extern	int	PSRFUnlock(PCCB, int, int, LONG, LONG);
extern	int	PSRFNewSize(PCCB, int, int, LONG);
extern	int	PSRFFileTime(PCCB, int, int, LPFILETIME, LPFILETIME,
								LPFILETIME);
extern	int	PSCFDelete(PCCB, int, CHAR *);
extern	int	PSCFCreateDirectory(PCCB, int, CHAR *);
extern	int	PSMultiCopyInfo(PCCB, int, int, VOID *, int);

/*	sio.c		*/
extern	BOOL	NetConxxxt(PCCB, CHAR *);
extern	BOOL	NetDisconxxxt(PCCB);
extern	BOOL	SendData2(PCCB, VOID *, int);
extern	BOOL	ReceiveData2(PCCB, VOID *, int);
extern	int	Requexxxommand(PCCB, UINT, UINT, UINT, UINT);
extern	BOOL	SendCommand(PCCB);
extern	BOOL	ReceiveResponse(PCCB);

/*	sdb.c		*/
extern	VOID	ExecPSDBOpen(PSCB);
extern	VOID	ExecPSDBCreate(PSCB);
extern	VOID	ExecPSDBClose(PSCB);
extern	VOID	ExecPSDBFlush(PSCB);
extern	VOID	ExecPSDBIndex(PSCB);
extern	VOID	ExecPSDBIdxCreate(PSCB);
extern	VOID	ExecPSDBChgIdx(PSCB);
extern	VOID	ExecPSDBSearch(PSCB);
extern	VOID	ExecPSDBSearch2(PSCB);
extern	VOID	ExecPSDBSearchLock(PSCB);
extern	VOID	ExecPSDBCount(PSCB);
extern	VOID	ExecPSDBStore(PSCB);
extern	VOID	ExecPSDBStoreUniq(PSCB);
extern	VOID	ExecPSDBUpdate(PSCB);
extern	VOID	ExecPSDBUpdateKey(PSCB);
extern	VOID	ExecPSDBDelete(PSCB);
extern	VOID	ExecPSDBRecall(PSCB);
extern	VOID	ExecPSDBDelete2(PSCB);
extern	VOID	ExecPSDBDelete3(PSCB);
extern	VOID	ExecPSDBDeleteKey(PSCB);
extern	VOID	ExecPSDBTop(PSCB);
extern	VOID	ExecPSDBBottom(PSCB);
extern	VOID	ExecPSDBSet(PSCB);
extern	VOID	ExecPSDBSkip(PSCB);
extern	VOID	ExecPSDBReindex(PSCB);
extern	VOID	ExecPSDBPack(PSCB);
extern	VOID	ExecPSDBRead(PSCB);
extern	VOID	ExecPSDBReadNext(PSCB);
extern	VOID	ExecPSDBReadBack(PSCB);
extern	VOID	ExecPSDBReadKey(PSCB);
extern	VOID	ExecPSDBReadBinary(PSCB);
extern	VOID	ExecPSDBWriteBinary(PSCB);
extern	VOID	ExecPSDBWriteBinaryEx(PSCB);
extern	VOID	ExecPSDBGetBinarySize(PSCB);
extern	VOID	ExecPSDBClrRecord(PSCB);
extern	VOID	ExecPSDBGetRecord(PSCB);
extern	VOID	ExecPSDBGetField(PSCB);
extern	VOID	ExecPSDBSetField(PSCB);
extern	VOID	ExecPSDBAddRecord(PSCB);
extern	VOID	ExecPSDBUpdRecord(PSCB);
extern	VOID	ExecPSDBCopy(PSCB);
extern	VOID	ExecPSDBCheckDeleted(PSCB);
extern	VOID	ExecPSDBSetFilter(PSCB);
extern	VOID	ExecPSDBSetDeleted(PSCB);
extern	VOID	ExecPSDBLRecNo(PSCB);
extern	VOID	ExecPSDBLRecCount(PSCB);
extern	VOID	ExecPSDBLSet(PSCB);
extern	VOID	ExecPSDBZip(PSCB);
extern	VOID	ExecPSDBCheckUpdate(PSCB);
extern	VOID	ExecPSDBLock(PSCB);
extern	VOID	ExecPSDBUnlock(PSCB);
extern	VOID	ExecPSDBRecCount(PSCB);
extern	VOID	ExecPSDBBof(PSCB);
extern	VOID	ExecPSDBDbf(PSCB);
extern	VOID	ExecPSDBEof(PSCB);
extern	VOID	ExecPSDBNField(PSCB);
extern	VOID	ExecPSDBField(PSCB);
extern	VOID	ExecPSDBNdx(PSCB);
extern	VOID	ExecPSDBRecNo(PSCB);
extern	VOID	ExecPSDBRecSize(PSCB);
extern	VOID	ExecPSDBSetReadField(PSCB);
extern	VOID	ExecPSDBSetScramble(PSCB);
extern	VOID	ExecPSDBUpdateOracle(PSCB);
extern	VOID	ExecPSDBDeleteOracle(PSCB);
extern	VOID	ExecPSDBSetPassword(PSCB);
extern	VOID	ExecPSDBUpdateEx(PSCB);
extern	VOID	ExecPSDBDeleteEx(PSCB);
extern	VOID	ExecPSDBRecallEx(PSCB);
extern	VOID	ExecPSDBDelete2Ex(PSCB);
extern	VOID	ExecPSDBLockEx(PSCB);
extern	VOID	ExecPSDBSelect(PSCB);
extern	VOID	ExecPSDBPackIndex(PSCB);

/*	srf.c		*/
extern	VOID	ExecPSRFOpen(PSCB);
extern	VOID	ExecPSRFCreate(PSCB);
extern	VOID	ExecPSRFClose(PSCB);
extern	VOID	ExecPSRFRead(PSCB);
extern	VOID	ExecPSRFWrite(PSCB);
extern	VOID	ExecPSRFSeek(PSCB);
extern	VOID	ExecPSRFLock(PSCB);
extern	VOID	ExecPSRFUnlock(PSCB);
extern	VOID	ExecPSRFNewSize(PSCB);
extern	VOID	ExecPSRFWriteEx(PSCB);
extern	VOID	ExecPSRFFileTime(PSCB);
extern	SHORT	RFClose(PFCB);

/*	scf.c		*/
extern	VOID	ExecPSCFCopy(PSCB);
extern	VOID	ExecPSCFDelete(PSCB);
extern	VOID	ExecPSCFFindFirst(PSCB);
extern	VOID	ExecPSCFFindNext(PSCB);
extern	VOID	ExecPSCFRename(PSCB);
extern	VOID	ExecPSCFStat(PSCB);
extern	VOID	ExecPSCFCreateDirectory(PSCB);
extern	VOID	ExecPSCFRemoveDirectory(PSCB);

/*	ssn.c		*/
extern	VOID	ExecPSSNCreate(PSCB);
extern	VOID	ExecPSSNGetNo(PSCB);
extern	VOID	ExecPSSNSetNo(PSCB);
extern	VOID	ExecPSSNDelete(PSCB);

/*	scl.c		*/
extern	VOID	ExecPSCLGetDate(PSCB);
extern	VOID	ExecPSCLGetCalendar(PSCB);

/*	sms.c		*/
extern	VOID	ExecPSMSSendRequest(PSCB);
extern	VOID	ExecPSMSSetMember(PSCB);
extern	VOID	ExecPSMSDeleteMember(PSCB);
extern	VOID	ExecPSMSGetMember(PSCB);
extern	VOID	StartNetMessage(VOID);

/*	sad.c		*/
extern	VOID	ExecPSADStop(PSCB);
extern	VOID	ExecPSADLogin(PSCB);
extern	VOID	ExecPSADOpenFile(PSCB);
extern	VOID	ExecPSADDebug(PSCB);
extern	VOID	ExecPSADGetStatistics(PSCB);
extern	VOID	ExecPSADClrStatistics(PSCB);
extern	VOID	InitStatistics(VOID);
extern	VOID	ExecPSADDuplexStatus(PSCB);
extern	VOID	ExecPSADDuplexSync(PSCB);
extern	VOID	ExecPSADDuplexGetInfo(PSCB);
extern	VOID	ExecPSADDuplexSetInfo(PSCB);
extern	VOID	ExecPSADMultiSync(PSCB);
extern	VOID	ExecPSADGetIniData(PSCB);
extern	VOID	ExecPSADSetIniData(PSCB);
extern	VOID	ExecPSADGetLicenseData(PSCB);
extern	VOID	ExecPSADSetLicenseData(PSCB);

/*	sgw.c		*/
extern	VOID	ExecPSGWCheckUser(PSCB);
extern	SHORT	_CheckUser(CHAR *, CHAR *, SHORT *);
extern	VOID	ExecPSGWCheckAccess(PSCB);
extern	VOID	ExecPSGWSetPassword(PSCB);
extern	VOID	ExecPSGWCheckTerminal(PSCB);

/*	sendmail.c	*/
extern	BOOL	SendReportMail(char *, ...);
extern	BOOL	SendMail(CHAR *, CHAR *, CHAR *, CHAR *, CHAR *);

/*	pcssub.c	*/
extern	VOID	CopyToDBF(CHAR *, CHAR *, SHORT);
extern	VOID	CopyFromDBF(CHAR *, CHAR *, SHORT);
extern	VOID	ReadIniFile(VOID);
extern	VOID	WriteIniFile(VOID);
extern	SHORT	ConvertPathName(CHAR *, CHAR *);
extern	SHORT	CheckPermission(PSCB pSCB, char *path, SHORT *pPermission);
extern	VOID	SystemError(CHAR *);
#if defined OS_WINDOWS
extern	VOID	ErrorLog(PSCB, CHAR *, ...);
#else
extern	VOID	ErrorLog();
#endif

//	SFile.c
extern SHORT OpenUserDBF(PDB *pDB);
extern SHORT OpenUserGrpDBF(PDB *pDB);
extern SHORT OpenTermDBF(PDB *pDB);
extern SHORT OpenTermGrpDBF(PDB *pDB);
extern SHORT OpenAccessDBF(PDB *pDB);

/*	cs_wnt.c	*/
extern	BOOL	CheckSystem(CHAR *, CHAR *);
