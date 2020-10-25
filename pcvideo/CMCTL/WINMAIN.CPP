/************************************************************************
 *									*
 *			カメラコントロールＤＬＬ			*
 *									*
 *		名称		: Windows DLL メイン処理		*
 *		ファイル名	: winmain.c				*
 *		作成者		: s.aizawa				*
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
