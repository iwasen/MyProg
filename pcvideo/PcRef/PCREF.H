#define	TIMEOUT_USER	60
#define	GUARD_TIME	3
#define USER_CHECK_COUNT	3

#define	MAX_UNICAST	10
#define	MAX_AUTOCONxxxT	10
#define	MAX_VIDEO_WINDOW	32

typedef	struct	_SEND {
	u_long	IPAddress;
	u_short	portNo;
	BOOL	bReflector;
	BOOL	bDisconxxxt;
	struct	_USER *lpUser;
	struct	_SEND *lpChain;
} SEND, FAR *LPSEND;

typedef	struct	_USER	{
	DWORD	id;
	u_short	portNo;
	BOOL	bSecond;
	char	userName[32];
	short	modeFlag;
	int	channelNo;
	int	timer;
	int	nSendVideo;
	DWORD	sendVideo[MAX_VIDEO_WINDOW];
	struct	_SEND *lpSend;
	struct	_USER *lpSendUser;
	struct	_USER *lpChain;
} USER, *LPUSER;

typedef	struct	{
	struct	{
		char	serverName[32];
		char	logFile[128];
		char	chatLogFile[128];
	} settings;
	struct	{
		char	host[MAX_UNICAST][32];
	} uniCast;
	struct	{
		char	host[MAX_AUTOCONxxxT][32];
	} autoConxxxt;
} INIDATA;
