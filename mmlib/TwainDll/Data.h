/****************************************************************************
 |
 |   			TWAIN対応　ｽｷｬﾅｰ画像取り込みＤＬＬ
 | 
 |			ﾌｧｲﾙ名 :		data.h		
 |			作成者 :		m.hama
 |			日付：			96/09/03
 |
 |
 ***************************************************************************/
extern	TW_IDENTITY appID, dsID;          // storage for App and DS (Source) states
extern	DSMENTRYPROC lpDSM_Entry;         // entry point to the SM
extern	char	Filename[256];
extern	HINSTANCE	ghInst ;
extern	TW_STR255	IniFile;
extern	HANDLE		hStruct;
extern	LPSTR		pStruct;
extern	HANDLE   	hInst;             // current instance
extern	HCURSOR		hWait;
extern	HCURSOR		hReady;
extern	BOOL	WriteDibFlag;
extern	HANDLE	hBitmap;
extern	int mode;
extern TW_IMAGEINFO twImageInfo;
