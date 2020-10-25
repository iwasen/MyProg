/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: ÉNÉâÉCÉAÉìÉgë§ã§í ÉwÉbÉ_
 *		ÉtÉ@ÉCÉãñº	: pcsclnt.h
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#include "pcsdef.h"
#include "pcssys.h"
#include "pcsfc.h"
#include "pcsdb.h"
#include "pcssn.h"
#include "pcscl.h"
#include "pcscom.h"

#if defined OS_MSDOS || defined OS_WINDOWS
#include <dos.h>
#endif

#define	ID_CCB	0x7e01
#define	MAX_RS232C_PORT	3
#define	READ_BUFFER_SIZE	64000U

typedef	struct	_FCB {
	USHORT	fileType;
	int	fileHandle;
	PDB	pDB;
	struct	_CCB	*pCCB;
	DBINF	DBInf;
	SHORT	readSize;
	CHAR	*pReadBuffer;
	USHORT	readBufRecord;
	USHORT	readBufCounter;
	SHORT	errorCode;
	struct	_FCB	*chain;
} FCB, *PFCB;

typedef	struct	_RS232C {
	int	handle;
	CHAR	*pRcvBuf;
	CHAR	*pSndBuf;
	USHORT	rcvLength;
	USHORT	readCounter;
	SHORT	fModem;
	int	chNo;
	int	openCount;
	BOOL	fChannel[MAX_RS232C_CHANNEL];
} RS232C, *PRS232C;

typedef	struct	_CCB {
	USHORT	id;
	USHORT	chType;
	int	handle;
	SHORT	async;
	COMMAND	command;
	RESPONSE response;
	VOID	*pInData;
	VOID	*pOutData;
	SHORT	errorCode;
	LONG	extSendDataLength;
	LONG	extReceiveDataLength;
	VOID	(*pErrHandler)(PFCB, SHORT);
#if defined OS_MSDOS || defined OS_WINDOWS
	struct	find_t	find;
#elif defined OS_WNT
	HANDLE	hdir;
#endif
	PRS232C	rsp;
	PFCB	pFCB;
	char	serverName[128];
} CCB, *PCCB;

/*	ioc.c	*/
extern	SHORT	NetConxxxt(PCCB, CHAR *);
extern	BOOL	NetDisconxxxt(PCCB);
extern	SHORT	SendCommand(PCCB, UINT, UINT, UINT);
extern	BOOL	SendData(PCCB, VOID *, UINT);
extern	BOOL	ReceiveData(PCCB, VOID *, UINT);

/*	rs232c.c	*/
extern	int	OpenRS232C(int, int, BOOL);
extern	int	ReadRS232C(int, CHAR *, int);
extern	int	WriteRS232C(int, CHAR *, int);
extern	VOID	CloseRS232C(int);
extern	BOOL	CheckDSRRS232C(int);
extern	BOOL	CheckCDRS232C(int);
extern	SHORT	Telephoxxxall(int, CHAR *);

// LoginDlg.c
extern BOOL LoginDialog(PCCB pCCB);

extern	int	tcpipPort;
extern	char	pipeName[32];

#include "pcsfunc.h"
