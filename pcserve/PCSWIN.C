/************************************************************************
 *
 *			‚o‚b|‚r‚d‚q‚u‚d
 *
 *		–¼Ì		: Windows DLL ƒƒCƒ“ˆ—
 *		ƒtƒ@ƒCƒ‹–¼	: pcswin.c
 *		ì¬Ò		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <dos.h>
#include <memory.h>
#include "pcssock.h"
#include "pcsclnt.h"

struct	mdi	{
	short	inf_level;
	long	serial_No;
	char	vol_label[11];
	char	file_system_ID[8];
};

static	char	iniFile[] = "pcswin.ini";

HINSTANCE hInst;

static	WORD DPMIint86x(int, union _REGS *, union _REGS *, struct _SREGS *);

int	CALLBACK LibMain(HANDLE hModule, WORD wDataSeg,
				WORD cbHeapSize, LPSTR lpszCmdLine)
{
//	WSADATA	WSAData;

	hInst = hModule;

	GetPrivateProfileString("NETBIOS", "PipeName", "\\PIPE\\PCSERVE",
				pipeName, sizeof(pipeName), iniFile);
	tcpipPort = GetPrivateProfileInt("TCPIP", "PortNo", TCPIP_PORT,
				iniFile);

	return 1;
}


int	CALLBACK _export _loadds WEP(int bSystemExit)
{
#ifdef	OS_WINDOWS
	{
		extern	HANDLE	hWinSock;
		if (hWinSock != 0)
			FreeLibrary(hWinSock);
	}
#endif
	return 1;
}

long	WINAPI	_export _loadds PSGetVolumeSerialNo(SHORT drive)
{
	union	_REGS	reg;
	struct	_SREGS	sreg;
	DWORD	dAlloc;
	struct	mdi	far *mdip;
	long	vsn;

	dAlloc = GlobalDosAlloc((DWORD)sizeof(struct mdi));
	FP_SEG(mdip) = LOWORD(dAlloc);
	FP_OFF(mdip) = 0;
	_fmemset(mdip, 0, sizeof(struct mdi));

	memset(&reg, 0, sizeof(reg));
	memset(&sreg, 0, sizeof(sreg));
	reg.x.ax = 0x440d;
	reg.x.bx = drive;
	reg.x.cx = 0x0866;

	sreg.ds = HIWORD(dAlloc);
	reg.x.dx = 0;

	DPMIint86x(0x21, &reg, &reg, &sreg);
	vsn = mdip->serial_No;
	GlobalDosFree(LOWORD(dAlloc));

	return vsn;
}

static	WORD DPMIint86x(int no, union _REGS *inregs, union _REGS *outregs,
							struct _SREGS *segregs)
{
	static union  _REGS  _inregs;
	static union  _REGS  _outregs;
	static struct _SREGS _segregs;
	static	WORD         regs[32], far *p;

	memset(&_inregs, 0, sizeof(_inregs));
	memset(&_outregs, 0, sizeof(_outregs));
	memset(&_segregs, 0, sizeof(_segregs));

	memset(regs, 0, sizeof(regs));

	regs[14] = inregs->x.ax;
	regs[8]  = inregs->x.bx;
	regs[12] = inregs->x.cx;
	regs[10] = inregs->x.dx;
	regs[2]  = inregs->x.si;
	regs[0]  = inregs->x.di;
	regs[17] = segregs->es;
	regs[18] = segregs->ds;

	_inregs.x.ax = 0x0300;
	_inregs.x.bx = no;
	p = regs;
	_segregs.es  = FP_SEG(p);
	_inregs.x.di = FP_OFF(p);

	_int86x(0x31, &_inregs, &_outregs, &_segregs);

	outregs->x.ax = regs[14];
	outregs->x.bx = regs[8];
	outregs->x.cx = regs[12];
	outregs->x.dx = regs[10];
	outregs->x.si = regs[2];
	outregs->x.di = regs[0];
	outregs->x.cflag = regs[16];
	segregs->es   = regs[17];
	segregs->ds   = regs[18];

	return outregs->x.ax;
}
