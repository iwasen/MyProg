/****************************************************************************
 |
 |   			TWAIN対応　ｽｷｬﾅｰ画像取り込みＤＬＬ
 | 
 |			ﾌｧｲﾙ名 :		main.c		
 |			作成者 :		m.hama
 |			日付：			96/09/03
 |
 |
 ***************************************************************************/
#include <windows.h>
#include "twain.h"         // for TW data type defines
#include "code.h"
#include "extern.h"
#include "data.h"

BOOL	WINAPI	DllMain(HINSTANCE hInstDLL, DWORD fdwReason,
						LPVOID lpvReserved)
{
	ghInst = hInstDLL;
	return TRUE;
}
