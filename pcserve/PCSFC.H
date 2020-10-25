/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ÉRÉ}ÉìÉhÉRÅ[ÉhÅ^ÉpÉâÉÅÅ[É^íËã`
 *		ÉtÉ@ÉCÉãñº	: pcsfc.h
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#define	FC_CONxxxT			1
#define	FC_DISCONxxxT		2
#define	FC_EXEC_COMMAND		3
#define	FC_GET_TIME			4
#define	FC_EXEC_SQL			5
#define	FC_GET_SQL			6
#define	FC_GET_ASYNC_STATUS	7
#define FC_LOGIN			8

#define	FC_DB_OPEN			100
#define	FC_DB_CREATE		101
#define	FC_DB_CLOSE			102
#define	FC_DB_FLUSH			103
#define	FC_DB_INDEX			104
#define	FC_DB_IDX_CREATE	105
#define	FC_DB_CHGIDX		106
#define	FC_DB_SEARCH		107
#define	FC_DB_SEARCH2		108
#define	FC_DB_COUNT			109
#define	FC_DB_STORE			110
#define	FC_DB_UPDATE		111
#define	FC_DB_DELETE		112
#define	FC_DB_DELETE2		113
#define	FC_DB_DELETE3		114
#define	FC_DB_RECALL		115
#define	FC_DB_TOP			116
#define	FC_DB_BOTTOM		117
#define	FC_DB_SET			118
#define	FC_DB_SKIP			119
#define	FC_DB_REINDEX		120
#define	FC_DB_PACK			121
#define	FC_DB_READ			122
#define	FC_DB_READNEXT		123
#define	FC_DB_READBACK		124
#define	FC_DB_CLRRECORD		125
#define	FC_DB_GETRECORD		126
#define	FC_DB_GETFIELD		127
#define	FC_DB_SETFIELD		128
#define	FC_DB_ADDRECORD		129
#define	FC_DB_UPDRECORD		130
#define	FC_DB_COPY			131
#define	FC_DB_CHECK_DELETED	132
#define	FC_DB_SET_FILTER	133
#define	FC_DB_SET_DELETED	134
#define	FC_DB_LRECNO		135
#define	FC_DB_LRECCOUNT		136
#define	FC_DB_LSET			137
#define	FC_DB_ZIP			138
#define	FC_DB_CHECK_UPDATE	139
#define	FC_DB_LOCK			140
#define	FC_DB_UNLOCK		141
#define	FC_DB_RECCOUNT		142
#define	FC_DB_BOF			143
#define	FC_DB_DBF			144
#define	FC_DB_EOF			145
#define	FC_DB_NFIELD		146
#define	FC_DB_FIELD			147
#define	FC_DB_NDX			148
#define	FC_DB_RECNO			149
#define	FC_DB_RECSIZE		150
#define	FC_DB_STOREUNIQ		151
#define	FC_DB_DELETEKEY		152
#define	FC_DB_SEARCHLOCK	153
#define	FC_DB_READKEY		154
#define	FC_DB_SET_READFIELD	155
#define	FC_DB_UPDATEKEY		156
#define	FC_DB_SET_SCRAMBLE	157
#define	FC_DB_UPDATEORACLE	158
#define	FC_DB_DELETEORACLE	159
#define	FC_DB_SET_PASSWORD	160
#define	FC_DB_UPDATE_EX		161
#define	FC_DB_DELETE_EX		162
#define	FC_DB_RECALL_EX		163
#define	FC_DB_DELETE2_EX	164
#define	FC_DB_LOCK_EX		165
#define	FC_DB_SELECT		166
#define	FC_DB_PACKINDEX		167
#define	FC_DB_READBINARY	168
#define	FC_DB_WRITEBINARY	169
#define	FC_DB_WRITEBINARY_EX	170
#define	FC_DB_GETBINARYSIZE	171

#define	FC_RF_OPEN			200
#define	FC_RF_CREATE		201
#define	FC_RF_CLOSE			202
#define	FC_RF_READ			203
#define	FC_RF_WRITE			204
#define	FC_RF_SEEK			205
#define	FC_RF_LOCK			206
#define	FC_RF_UNLOCK		207
#define	FC_RF_NEWSIZE		208
#define	FC_RF_WRITE_EX		209
#define	FC_RF_FILETIME		210

#define	FC_CF_COPY			300
#define	FC_CF_DELETE		301
#define	FC_CF_FINDFIRST		302
#define	FC_CF_FINDNEXT		303
#define	FC_CF_RENAME		304
#define	FC_CF_STAT			305
#define	FC_CF_CREATEDIRECTORY	306
#define	FC_CF_REMOVEDIRECTORY	307

#define	FC_SN_CREATE		400
#define	FC_SN_GETNO			401
#define	FC_SN_DELETE		402
#define	FC_SN_SETNO			403

#define	FC_CL_GETDATE		410
#define	FC_CL_GETCALENDAR	411

#define	FC_MS_SEND_REQUEST	412
#define	FC_MS_SET_MEMBER	413
#define	FC_MS_DELETE_MEMBER	414
#define	FC_MS_GET_MEMBER	415

#define	FC_AD_STOP			500
#define	FC_AD_EXIT			501
#define	FC_AD_LOGIN			502
#define	FC_AD_OPENFILE		503
#define	FC_AD_DEBUG			504
#define	FC_AD_GETSTATISTICS	505
#define	FC_AD_CLRSTATISTICS	506
#define	FC_AD_DUPLEXSTATUS	507
#define	FC_AD_DUPLEXSYNC	508
#define	FC_AD_DUPLEXGETINFO	509
#define	FC_AD_DUPLEXSETINFO	510
#define	FC_AD_MULTISYNC		511
#define	FC_AD_GETINIDATA	512
#define	FC_AD_SETINIDATA	513
#define	FC_AD_GETLICENSEDATA	514
#define	FC_AD_SETLICENSEDATA	515

#define	FC_GW_CHECK_USER	600
#define	FC_GW_CHECK_ACCESS	601
#define	FC_GW_SET_PASSWORD	602
#define	FC_GW_CHECK_TERMINAL	603

#define	FC_DUPLEX_KEEPALIVE	700
#define	FC_DUPLEX_CREATE	701
#define	FC_DUPLEX_WRITE		702
#define	FC_DUPLEX_NEWSIZE	703
#define	FC_DUPLEX_DELETE	704
#define	FC_DUPLEX_ERROR		705
#define	FC_DUPLEX_SYNC		706
#define	FC_DUPLEX_FILETIME	707
#define	FC_DUPLEX_COPY		708
#define	FC_DUPLEX_RENAME	709

#define	FC_MULTI_SYNC		806
#define	FC_MULTI_COPYINFO	807

struct	PRM_CONxxxT {
	CHAR	clientID[9];
	CHAR	computerName[17];
};

struct	PRM_LOGIN {
	CHAR	userID[11];
	CHAR	password[11];
};

struct	PRM_DB_OPEN {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_DB_CREATE {
	CHAR	fileName[MAX_FILE_NAME];
	SHORT	nField;
};

struct	PRM_DB_CLOSE {
	SHORT	handle;
};

struct	PRM_DB_FLUSH {
	SHORT	handle;
};

struct	PRM_DB_INDEX {
	SHORT	handle;
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_DB_IDX_CREATE {
	SHORT	handle;
	CHAR	fileName[MAX_FILE_NAME];
	CHAR	key[100];
	SHORT	uniq;
};

struct	PRM_DB_CHGIDX {
	SHORT	handle;
	SHORT	n;
};

struct	PRM_DB_SEARCH {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
};

struct	PRM_DB_SEARCH2 {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
};

struct	PRM_DB_SEARCHLOCK {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
};

struct	PRM_DB_COUNT {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
};

struct	PRM_DB_STORE {
	SHORT	handle;
};

struct	PRM_DB_STOREUNIQ {
	SHORT	handle;
};

struct	PRM_DB_UPDATE {
	SHORT	handle;
};

struct	PRM_DB_UPDATEKEY {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
};

struct	PRM_DB_DELETE {
	SHORT	handle;
};

struct	PRM_DB_RECALL {
	SHORT	handle;
};

struct	PRM_DB_DELETE2 {
	SHORT	handle;
};

struct	PRM_DB_DELETE3 {
	SHORT	handle;
};

struct	PRM_DB_DELETEKEY {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
	SHORT	flag;
};

struct	PRM_DB_TOP {
	SHORT	handle;
};

struct	PRM_DB_BOTTOM {
	SHORT	handle;
};

struct	PRM_DB_SET {
	SHORT	handle;
	LONG	recNo;
};

struct	PRM_DB_SKIP {
	SHORT	handle;
	LONG	n;
};

struct	PRM_DB_REINDEX {
	SHORT	handle;
};

struct	PRM_DB_PACK {
	SHORT	handle;
};

struct	PRM_DB_READ {
	SHORT	handle;
};

struct	PRM_DB_READNEXT {
	SHORT	handle;
	SHORT	nRec;
};

struct	PRM_DB_READBACK {
	SHORT	handle;
	SHORT	nRec;
};

struct	PRM_DB_READKEY {
	SHORT	handle;
	CHAR	key[100];
	SHORT	len;
	SHORT	lock;
};

struct	PRM_DB_CLRRECORD {
	SHORT	handle;
};

struct	PRM_DB_GETRECORD {
	SHORT	handle;
};

struct	PRM_DB_GETFIELD  {
	SHORT	handle;
	CHAR	field[11];
};

struct	PRM_DB_SETFIELD {
	SHORT	handle;
	CHAR	field[11];
};

struct	PRM_DB_ADDRECORD {
	SHORT	handle;
};

struct	PRM_DB_UPDRECORD {
	SHORT	handle;
};

struct	PRM_DB_COPY {
	SHORT	handle1;
	SHORT	handle2;
};

struct	PRM_DB_CHECK_DELETED {
	SHORT	handle;
};

struct	PRM_DB_SET_FILTER {
	SHORT	handle;
};

struct	PRM_DB_SET_DELETED {
	SHORT	handle;
	SHORT	flag;
};

struct	PRM_DB_LRECNO {
	SHORT	handle;
};

struct	PRM_DB_LRECCOUNT {
	SHORT	handle;
};

struct	PRM_DB_LSET {
	SHORT	handle;
	LONG	lRecNo;
};

struct	PRM_DB_ZIP {
	SHORT	handle;
};

struct	PRM_DB_CHECK_UPDATE {
	SHORT	handle;
};

struct	PRM_DB_LOCK {
	SHORT	handle;
	SHORT	lock;
};

struct	PRM_DB_UNLOCK {
	SHORT	handle;
	SHORT	lock;
};

struct	PRM_DB_RECCOUNT {
	SHORT	handle;
};

struct	PRM_DB_BOF {
	SHORT	handle;
};

struct	PRM_DB_DBF {
	SHORT	handle;
};

struct	PRM_DB_EOF {
	SHORT	handle;
};

struct	PRM_DB_NFIELD {
	SHORT	handle;
};

struct	PRM_DB_FIELD {
	SHORT	handle;
	SHORT	n;
};

struct	PRM_DB_NDX {
	SHORT	handle;
	SHORT	n;
};

struct	PRM_DB_RECNO {
	SHORT	handle;
};

struct	PRM_DB_RECSIZE {
	SHORT	handle;
};

struct	PRM_DB_SET_READFIELD {
	SHORT	handle;
};

struct	PRM_DB_SET_SCRAMBLE {
	SHORT	handle;
	CHAR	scrambleKey[17];
};

struct	PRM_DB_SET_PASSWORD {
	SHORT	handle;
	CHAR	password[9];
};

struct	PRM_DB_UPDATEORACLE {
	SHORT	handle;
	CHAR	key[100];
};

struct	PRM_DB_DELETEORACLE {
	SHORT	handle;
	CHAR	key[100];
};

struct	PRM_DB_UPDATE_EX {
	SHORT	handle;
	LONG	recNo;
	LONG	recCount;
};

struct	PRM_DB_DELETE_EX {
	SHORT	handle;
	LONG	recNo;
	LONG	recCount;
};

struct	PRM_DB_RECALL_EX {
	SHORT	handle;
	LONG	recNo;
	LONG	recCount;
};

struct	PRM_DB_DELETE2_EX {
	SHORT	handle;
	LONG	recNo;
	LONG	recCount;
};

struct	PRM_DB_LOCK_EX {
	SHORT	handle;
	LONG	recNo;
	LONG	recCount;
	SHORT	lock;
};

struct	PRM_DB_SELECT {
	SHORT	handle;
};

struct	PRM_DB_PACKINDEX {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_DB_READBINARY {
	SHORT	handle;
	CHAR	field[11];
	LONG	len;
};

struct	PRM_DB_WRITEBINARY {
	SHORT	handle;
	CHAR	field[11];
	LONG	len;
};

struct	PRM_DB_WRITEBINARY_EX {
	SHORT	handle;
	CHAR	field[11];
	LONG	len;
	LONG	recNo;
	LONG	recCount;
};

struct	PRM_DB_GETBINARYSIZE {
	SHORT	handle;
	CHAR	field[11];
};

struct	PRM_RF_OPEN {
	CHAR	fileName[MAX_FILE_NAME];
	USHORT	accessMode;
	USHORT	shareMode;
};

struct	PRM_RF_CREATE {
	CHAR	fileName[MAX_FILE_NAME];
	USHORT	accessMode;
	USHORT	shareMode;
};

struct	PRM_RF_CLOSE {
	SHORT	handle;
};

struct	PRM_RF_READ {
	SHORT	handle;
	USHORT	length;
};

struct	PRM_RF_WRITE {
	SHORT	handle;
	USHORT	length;
};

struct	PRM_RF_SEEK {
	SHORT	handle;
	LONG	offset;
	SHORT	origin;
};

struct	PRM_RF_LOCK {
	SHORT	handle;
	LONG	offset;
	LONG	range;
};

struct	PRM_RF_UNLOCK {
	SHORT	handle;
	LONG	offset;
	LONG	range;
};

struct	PRM_RF_NEWSIZE {
	SHORT	handle;
	LONG	fileSize;
};

struct	PRM_RF_WRITE_EX {
	SHORT	handle;
	LONG	offset;
	USHORT	length;
};

#if defined (_SERVER)
struct	PRM_RF_FILETIME {
	SHORT	handle;
	FILETIME ftCreation;
	FILETIME ftLastAccess;
	FILETIME ftLastWrite;
};
#endif

struct	PRM_CF_COPY {
	CHAR	srcFileName[MAX_FILE_NAME];
	CHAR	dstFileName[MAX_FILE_NAME];
};

struct	PRM_CF_DELETE {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_CF_FINDFIRST {
	CHAR	pathName[MAX_FILE_NAME];
	USHORT	attribute;
};

struct	PRM_CF_RENAME {
	CHAR	oldFileName[MAX_FILE_NAME];
	CHAR	newFileName[MAX_FILE_NAME];
};

struct	PRM_CF_STAT {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_CF_CREATEDIRECTORY {
	CHAR	dirName[MAX_FILE_NAME];
};

struct	PRM_CF_REMOVEDIRECTORY {
	CHAR	dirName[MAX_FILE_NAME];
};

struct	PRM_SN_CREATE {
	CHAR	seqNoID[9];
	CHAR	comment[31];
	LONG	startNo;
	LONG	endNo;
	LONG	step;
	SHORT	column;
};

struct	PRM_SN_GETNO {
	CHAR	seqNoID[9];
};

struct	PRM_SN_SETNO {
	CHAR	seqNoID[9];
	LONG	seqNo;
};

struct	PRM_SN_DELETE {
	CHAR	seqNoID[9];
};

struct	PRM_CL_GETDATE {
	CHAR	calendarID[9];
	CLDATE	date;
	SHORT	days;
};

struct	PRM_CL_GETCALENDAR {
	CHAR	calendarID[9];
	CLDATE	date;
};

struct	PRM_AD_OPENFILE {
	USHORT	clientNo;
};

struct	PRM_AD_DEBUG {
	SHORT	code;
};

struct	PRM_AD_DUPLEXSYNC {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_AD_MULTISYNC {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_EXEC_COMMAND {
	CHAR	command[128];
};

struct	PRM_GET_SQL {
	SHORT	nRec;
};

struct	PRM_GW_CHECK_USER {
	CHAR	userID[11];
	CHAR	password[11];
};

struct	PRM_GW_CHECK_ACCESS {
	CHAR	userID[11];
	CHAR	termID[11];
	CHAR	systemName[11];
	CHAR	subSystemName[11];
};

struct	PRM_GW_SET_PASSWORD {
	CHAR	userID[11];
	CHAR	oldPassword[11];
	CHAR	newPassword[11];
};

struct	PRM_GW_CHECK_TERMINAL {
	CHAR	termID[11];
} ;

struct	PRM_MS_SEND_REQUEST {
	CHAR	name[22];
	USHORT	timeout;
};

struct	PRM_MS_SET_MEMBER {
	CHAR	groupName[21];
	CHAR	member[21];
};

struct	PRM_MS_DELETE_MEMBER {
	CHAR	groupName[21];
	CHAR	member[21];
};

struct	PRM_MS_GET_MEMBER {
	CHAR	groupName[21];
	USHORT	size;
};

#if defined (_SERVER)
struct	PRM_DUPLEX_CREATE {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_DUPLEX_WRITE {
	CHAR	fileName[MAX_FILE_NAME];
	USHORT	size;
	LONG	offset;
};

struct	PRM_DUPLEX_NEWSIZE {
	CHAR	fileName[MAX_FILE_NAME];
	LONG	size;
};

struct	PRM_DUPLEX_DELETE {
	CHAR	fileName[MAX_FILE_NAME];
};

struct	PRM_DUPLEX_ERROR {
	CHAR	fileName[MAX_FILE_NAME];
	SHORT	errorCode;
};

struct	PRM_DUPLEX_FILETIME {
	CHAR	fileName[MAX_FILE_NAME];
	FILETIME ftCreation;
	FILETIME ftLastAccess;
	FILETIME ftLastWrite;
};

struct	PRM_DUPLEX_COPY {
	CHAR	srcFileName[MAX_FILE_NAME];
	CHAR	dstFileName[MAX_FILE_NAME];
};

struct	PRM_DUPLEX_RENAME {
	CHAR	oldFileName[MAX_FILE_NAME];
	CHAR	newFileName[MAX_FILE_NAME];
};

struct	PRM_MULTI_COPYINFO {
	SHORT	dataType;
};
#endif
