/************************************************************************
 *									*
 *			�J�����R���g���[���c�k�k			*
 *									*
 *		����		: Windows DLL ���C������		*
 *		�t�@�C����	: winmain.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <windows.h>
#include <mmsystem.h>
#include "cmctldll.h"

extern	"C" int	CALLBACK LibMain(HANDLE hModule, WORD wDataSeg,
				WORD cbHeapSize, LPSTR lpszCmdLine)
{
	return(1);
}

extern	"C" int	CALLBACK _export _loadds WEP(int bSystemExit)
{
	if (hWinSock != 0)
		FreeLibrary(hWinSock);

	return(1);
}