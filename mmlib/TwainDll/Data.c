/****************************************************************************
 |
 |   			TWAIN対応　ｽｷｬﾅｰ画像取り込みＤＬＬ
 | 
 |			ﾌｧｲﾙ名 :		data.c		
 |			作成者 :		m.hama
 |			日付：			96/09/03
 |
 |
 ***************************************************************************/
#include <windows.h>    // Note: twain.h also REQUIRES windows defs
#include "twain.h"         // for TW data type defines

TW_IDENTITY appID, dsID;          // storage for App and DS (Source) states
DSMENTRYPROC lpDSM_Entry;         // entry point to the SM
char		Filename[256];
HINSTANCE	ghInst ;
TW_STR255	IniFile;
HANDLE		hStruct;
LPSTR		pStruct;
HANDLE   	hInst;             // current instance
HCURSOR		hWait;
HCURSOR		hReady;
BOOL	WriteDibFlag;
HANDLE hBitmap;
int	mode;
TW_IMAGEINFO twImageInfo;
