/************************************************************************
 *									*
 *		ÇnÇrÅ^ÇQÅ@ÉIÉìÉâÉCÉìÉÜÅ[ÉUÉTÉ|Å[ÉgÉVÉXÉeÉÄ		*
 *			ÇoÇbÅ|ÇmÇdÇsÅ^ÇQ				*
 *									*
 *		ñºèÃ		: äOïîä÷êîíËã`				*
 *		ÉtÉ@ÉCÉãñº	: extern.h				*	 *		çÏê¨é“		: s.aizawa				*
 *									*
 ************************************************************************/

/*	main.c		*/
extern	BOOL	OpenLogDBF(BOOL);
extern	VOID	Terminate(VOID);
extern	VOID	FatalError(USHORT);

/*	hoshu.c		*/
MRESULT CALLBACK MaintenanceDlg(HWND, USHORT, MPARAM, MPARAM);

/*	operate.c	*/
extern	BOOL	ReadOperationFile(VOID);
extern	void	OnLine(USHORT);

/*	settei.c	*/
extern	MRESULT CALLBACK SetPortDlg(HWND, USHORT, MPARAM, MPARAM);
extern	MRESULT CALLBACK SetEnvDlg(HWND, USHORT, MPARAM, MPARAM);

/*	user.c		*/
extern	MRESULT CALLBACK UserWindowProc(HWND, USHORT, MPARAM, MPARAM);
extern	MRESULT CALLBACK UserDlg(HWND, USHORT, MPARAM, MPARAM);
extern	VOID	UserManagement(VOID);
extern	BOOL	OpenUserDBF(BOOL);

/*	sales.c		*/
extern	MRESULT CALLBACK SalesDlg(HWND, USHORT, MPARAM, MPARAM);
extern	BOOL	OpenSalesDBF(BOOL);

/*	bbs.c		*/
extern	MRESULT CALLBACK BBSWindowProc(HWND, USHORT, MPARAM, MPARAM);
extern	VOID	BBSManagement(VOID);
extern	BOOL	GetBBSM(CHAR *, BBSMDBF *);
extern	BOOL	OpenBBSMDBF(BOOL);
extern	DB	*OpenBBSDBF(CHAR *, BOOL);
extern	VOID	CloseBBSDBF(DB *);
extern	VOID	MakeBBSFile(CHAR *, CHAR *, CHAR *);

/*	mail.c		*/
extern	MRESULT CALLBACK MailWindowProc(HWND, USHORT, MPARAM, MPARAM);
extern	VOID	MailManagement(VOID);
extern	BOOL	SendMail(CHAR *, CHAR *, CHAR *, CHAR *, USHORT);
extern	VOID	SetMailReadDate(VOID);
extern	VOID	DeleteSMail(VOID);
extern	VOID	DeleteRMail(VOID);
extern	BOOL	OpenMailDBF(BOOL);
extern	VOID	CheckReceiveMail(VOID);
extern	VOID	MakeMailFile(CHAR *, CHAR *);

/*	isam.c		*/
extern	SHORT	_dbErrCode;
extern	DB	*DBOpen(CHAR *);
extern	DB	*DBCreate(CHAR *, DBF_I *, SHORT);
extern	VOID	DBClose(DB *);
extern	VOID	DBCloseAll(VOID);
extern	VOID	DBFlush(DB *);
extern	VOID	DBFlushAll(VOID);
extern	SHORT	DBIndex(DB *, CHAR *);
extern	SHORT	DBIdxCreate(DB *, CHAR *, CHAR *, SHORT);
extern	VOID	DBChgIdx(DB *, SHORT);
extern	BOOL	DBSearch(DB *, CHAR *, SHORT, SHORT);
extern	LONG	DBCount(DB *, CHAR *, SHORT, SHORT);
extern	VOID	DBStore(DB *, VOID *);
extern	VOID	DBUpdate(DB *, VOID *);
extern	VOID	DBDelete(DB *);
extern	VOID	DBRecall(DB *);
extern	VOID	DBDelete2(DB *);
extern	VOID	DBDelete3(DB *);
extern	VOID	DBDelete4(DB *);
extern	VOID	DBTop(DB *);
extern	VOID	DBBottom(DB *);
extern	VOID	DBSet(DB *, LONG);
extern	VOID	DBSkip(DB *, LONG);
extern	VOID	DBReindex(DB *);
extern	VOID	DBPack(DB *);
extern	VOID	DBRead(DB *, VOID *);
extern	VOID	DBLock(DB *);
extern	VOID	DBRelease(DB *);
extern	LONG	DBGetRecNo(DB *);
extern	BOOL	DBCheckSize(DB *);
extern	BOOL	DBCheckUpdate(DB *);
extern	VOID	DBResetUpdate(DB *);
extern	DBF	*DBFOpen(CHAR *);
extern	DBF	*DBFCreate(CHAR *, DBF_I *, SHORT);
extern	VOID	DBFRead(DBF *, LONG);
extern	VOID	DBFWrite(DBF *, LONG);
extern	VOID	DBFClose(DBF *);
extern	VOID	DBFFlush(DBF *);
extern	VOID	DBFDelete(DBF *, LONG);
extern	VOID	DBFRecall(DBF *, LONG);
extern	VOID	DBFPack(DBF *);
extern	SHORT	DBFCheckDeleted(DBF *, long);
extern	IDX	*IDXOpen(CHAR *);
extern	IDX	*IDXCreate(CHAR *, CHAR *, SHORT);
extern	VOID	IDXSetInfo(IDX *, SHORT, SHORT);
extern	IDX_B	*IDXRead(IDX *, LONG);
extern	IDX_B	*IDXGetBlk(IDX *, LONG);
extern	VOID	IDXWrite(IDX *, IDX_B *);
extern	VOID	IDXClose(IDX *);
extern	VOID	IDXFlush(IDX *);
extern	LONG	IDXSearch(IDX *, VOID *, SHORT);
extern	SHORT	IDXCompare(IDX *, VOID *, SHORT);
extern	VOID	IDXStore(IDX *, CHAR *, LONG);
extern	VOID	IDXDelete(IDX *);
extern	LONG	IDXNext(IDX *);
extern	LONG	IDXBack(IDX *);
extern	LONG	IDXTop(IDX *);
extern	LONG	IDXBottom(IDX *);
extern	LONG	IDXCount(IDX *, VOID *, SHORT);
extern	VOID	IDXMake(DBF *, IDX *);
extern	LONG	DBRecCount(DB *);
extern	BOOL	DBBof(DB *);
extern	BOOL	DBEof(DB *);
extern	LONG	DBRecNo(DB *);
extern	VOID	SetDeleted(BOOL);
extern	VOID	DBErrHandler(VOID (*func)(SHORT));
extern	VOID	DBErr(SHORT);
extern	SHORT	natoi(CHAR *, SHORT);
extern	SHORT	GetKey(DBF *, IDX_H *, CHAR *);
extern	FIELD	*GetField(DBF *, CHAR *);

/*	monitor.c	*/
extern	VOID	Monitor(USHORT);
extern	MRESULT CALLBACK MonitorWindowProc(HWND, USHORT, MPARAM, MPARAM);

/*	terminal.c	*/
extern	VOID	Terminal(USHORT);
extern	MRESULT CALLBACK TermWindowProc(HWND, USHORT, MPARAM, MPARAM);
extern	VOID	TermBlockCount(USHORT, USHORT);

/*	com.c		*/
extern	BOOL	SetPortParameter(USHORT);
extern	VOID	FAR Ch0Thread(VOID);
extern	VOID	FAR Ch1Thread(VOID);
extern	VOID	FAR Ch2Thread(VOID);
extern	VOID	FAR Ch3Thread(VOID);
extern	VOID	Disconxxxt(USHORT);
extern	BOOL	ReceiveLine(USHORT, CHAR *, USHORT);
extern	BOOL	ReceiveChar(USHORT, CHAR *);
extern	BOOL	SendLine(USHORT, CHAR *, USHORT);
extern	VOID	SendCRLF(USHORT, USHORT);
extern	VOID	ClearRcvQue(USHORT);

/*	xmodem.c	*/
extern	SHORT	SXmodem(USHORT, FILE *, VOID (*)());
extern	SHORT	RXmodem(USHORT, FILE *, VOID (*)());
extern	VOID	AbortXmodem(USHORT);

/*	login.c		*/
extern	BOOL	Login(USHORT portNo);
extern	BOOL	CheckPassWord(CHAR *, CHAR *);
extern	BOOL	ReadUserData(CHAR *, USERDBF *);
extern	VOID	Logout(USHORT portNo);

/*	file.c		*/
extern	BOOL	SelectFile(HWND, CHAR *, CHAR *);

/*	csub.c		*/
extern	VOID	CopyToDBF(CHAR *, CHAR *, SHORT);
extern	VOID	CopyFromDBF(CHAR *, CHAR *, SHORT);
extern	VOID	SetEntryData(HWND, ENTRY_TBL *, SHORT);
extern	VOID	GetEntryData(HWND, ENTRY_TBL *, SHORT);
extern	VOID	ExecEditor(CHAR *);
extern	VOID	SetWindowPos(HWND, SHORT, SHORT);
extern	VOID	CenterDlgBox(HWND);
extern	VOID	ReadEnvData(VOID);
extern	VOID	WriteEnvData(VOID);
extern	FILE	*MakeNewFile(CHAR *, CHAR *, CHAR *, CHAR *);
MRESULT CALLBACK InputFileNameDlg(HWND, USHORT, MPARAM, MPARAM);
extern	BOOL	FileCopy(HWND, CHAR *, CHAR *);
MRESULT CALLBACK CopyMsg(HWND, USHORT, MPARAM, MPARAM);
extern	VOID	SetPointer(SHORT);
extern	BOOL	CheckFileName(CHAR *);
extern	VOID	ToUpperStr(CHAR *, SHORT);
extern	VOID	DEBUG(CHAR *fmt, ...);

/*	data.c		*/
extern	CHAR	systemName[];
extern	char	version[];
extern	CHAR	szMainClassName[];
extern	CHAR	szMonitorClassName[];
extern	CHAR	szUserClassName[];
extern	CHAR	szBBSClassName[];
extern	CHAR	szMailClassName[];
extern	CHAR	szTerminalClassName[];

extern	HAB	hab;
extern	HWND	hwndMain;
extern	HWND	hwndUser;
extern	HWND	hwndBBS;
extern	HWND	hwndMail;
extern	HWND	clientMonitor[];
extern	HWND	clientTerminal[];
extern	SAVEDATA	sv;
extern	char	*portName[];
extern	BOOL	portOpenFlag[];
extern	HFILE	portHndl[];
extern	HFILE	ch0Hndl;
extern	USHORT	threadID[];
extern	BOOL	monitorFlag[];
extern	USHORT	terminalMode[];
extern	FILE	*termXmodemFP[];
extern	BOOL	closeRequest;
extern	USHORT	receiveTimer[];
extern	BOOL	conxxxtFlag[];
extern	FILE	*logFp[];
extern	SHORT	cxScreen;
extern	SHORT	cyScreen;
extern	SHORT	gcyChar;
extern	SHORT	gcxAveChar;
extern	SHORT	gcxMaxChar;
extern	BOOL	echoBackFlag[];
extern	CHAR	cUserID[][9];
extern	CHAR	cGroup[][9];
extern	CHAR	cUserLevel[];

extern	CHAR	fnEnvData[];
extern	CHAR	fnOperation[];

extern	CHAR	fnConxxxtMsg[];
extern	CHAR	fnLoginMsg[];

extern	CHAR	dnLOGFILE[];
extern	CHAR	fnLOGDBF[];
extern	CHAR	fnLOGNDX[];
extern	DB	*logDBp;

extern	CHAR	fnUSERDBF[];
extern	CHAR	fnUSERIDNDX[];
extern	CHAR	fnFURIGANANDX[];
extern	SHORT	indexUserID;
extern	SHORT	indexFurigana;
extern	DB	*userDBp;

extern	CHAR	fnSALESDBF[];
extern	CHAR	fnSALESNDX[];
extern	SHORT	indexSales;
extern	DB	*salesDBp;

extern	CHAR	dnBBS[];
extern	CHAR	fnBBSMDBF[];
extern	CHAR	fnBBSMNDX[];
extern	SHORT	indexBBSM;
extern	DB	*BBSMDBp;

extern	CHAR	fnBBSDBF[];
extern	CHAR	fnBBSNDX[];

extern	CHAR	dnMAIL[];
extern	CHAR	fnMAILDBF[];
extern	CHAR	fnMAILANDX[];
extern	CHAR	fnMAILS1NDX[];
extern	CHAR	fnMAILR1NDX[];
extern	CHAR	fnMAILS2NDX[];
extern	CHAR	fnMAILR2NDX[];
extern	SHORT	indexMailA;
extern	SHORT	indexMailS1;
extern	SHORT	indexMailR1;
extern	SHORT	indexMailS2;
extern	SHORT	indexMailR2;
extern	DB	*mailDBp;

extern	CHAR	fnFCHECK[];
