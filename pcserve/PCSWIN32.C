/************************************************************************
 *
 *			ÇoÇbÅ|ÇrÇdÇqÇuÇd
 *
 *		ñºèÃ		: Windows DLL ÉÅÉCÉìèàóù
 *		ÉtÉ@ÉCÉãñº	: pcswin32.c
 *		çÏê¨é“		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include "pcssock.h"
#include "pcsclnt.h"

extern	CRITICALSECTION	csSort;
extern	CRITICALSECTION	csDB;
extern	CRITICALSECTION	csDBL;
extern	CRITICALSECTION	csDBF;
extern	CRITICALSECTION	csIDX;

static	char	iniFile[] = "pcswin.ini";
static	int	initialFlag = FALSE;

HINSTANCE hInst;

BOOL	WINAPI	DllMain(HINSTANCE hInstDLL, DWORD fdwReason,
						LPVOID lpvReserved)
{
	WSADATA	WSAData;

	hInst = hInstDLL;

	if (fdwReason == DLL_PROCESS_ATTACH) {
		if (!initialFlag) {
			GetPrivateProfileString("NETBIOS", "PipeName",
				"\\PIPE\\PCSERVE",
				pipeName, sizeof(pipeName), iniFile);
			tcpipPort = GetPrivateProfileInt("TCPIP", "PortNo",
				TCPIP_PORT, iniFile);

			INIT_CRITICAL_SECTION(&csSort);
			INIT_CRITICAL_SECTION(&csDB);
			INIT_CRITICAL_SECTION(&csDBL);
			INIT_CRITICAL_SECTION(&csDBF);
			INIT_CRITICAL_SECTION(&csIDX);

			initialFlag = TRUE;
		}
		WSAStartup(0x0101, &WSAData);
	} else if (fdwReason == DLL_PROCESS_DETACH) {
		WSACleanup();
	}

	return TRUE;
}


long	PSENTRY PSGetVolumeSerialNo(SHORT drive)
{
	char	dir[4];
	long	vsn;

	wsprintf(dir, "%c:\\", drive + 0x40);
	GetVolumeInformation(dir, NULL, 0, &vsn, NULL, NULL, NULL, 0);
	return vsn;
}
