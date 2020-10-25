/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 共通サブルーチン			*
 *		ファイル名	: csub.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>
#include <io.h>
#include <sys\types.h>
#include <sys\stat.h>

/*=======================================================================
 |
 |		ASCIIZ文字列からデータベースフィールドにコピー
 |
 |	VOID	CopyToDBF(dbField, strbuf, length)
 |
 |		CHAR	*dbField;	データベースフィールド
 |		CHAR	*strBuf;	ASCIIZ文字列
 |		SHORT	length;		フィールド長
 |
 =======================================================================*/
VOID	CopyToDBF(CHAR *dbField, CHAR *strbuf, SHORT length)
{
	SHORT	slen;

	slen = strlen(strbuf);
	memcpy(dbField, strbuf, slen);
	if (slen < length)
		memset(dbField+slen, ' ', length - slen);
}

/*=======================================================================
 |
 |		データベースフィールドからASCIIZ文字列にコピー
 |
 |	VOID	CopyFromDBF(strbuf, dbField, length)
 |
 |		CHAR	*strBuf;	ASCIIZ文字列
 |		CHAR	*dbField;	データベースフィールド
 |		SHORT	length;		フィールド長
 |
 =======================================================================*/
VOID	CopyFromDBF(CHAR *strbuf, CHAR *dbField, SHORT length)
{
	while (length != 0) {
		if (dbField[length-1] != ' ')
			break;
		length--;
	}
	memcpy(strbuf, dbField, length);
	strbuf[length] = '\0';
}

/*=======================================================================
 |
 |		ダイアログボックスのエントリフィールドにデータをセット
 |
 |	VOID	SetEntryData(hwnd, entryTbl, itemNum)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		ENTRY_TBL *entryTBl;	エントリデータテーブル
 |		SHORT	itemNum;	項目数
 |
 =======================================================================*/
VOID	SetEntryData(HWND hwnd, ENTRY_TBL *entryTbl, SHORT itemNum)
{
	CHAR	fieldBuf[100];
	USHORT	i;
	CHAR	*p;

	while (itemNum--) {
		switch (entryTbl->type) {
		case ET_STRINGFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			CopyFromDBF(fieldBuf, entryTbl->ptr, entryTbl->field);
			WinSetDlgItemText(hwnd, entryTbl->id, fieldBuf);
			break;
		case ET_NUMSTRFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			for (i = 0, p = fieldBuf; i < entryTbl->field; i++)
				if (((CHAR *)entryTbl->ptr)[i] != ' ')
					*p++ = ((CHAR *)entryTbl->ptr)[i];
			*p = '\0';
			WinSetDlgItemText(hwnd, entryTbl->id, fieldBuf);
			break;
		case ET_STZEROFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			WinSetDlgItemText(hwnd, entryTbl->id, entryTbl->ptr);
			break;
		case ET_NUMBERFIELD:
			WinSendDlgItemMsg(hwnd, entryTbl->id,
				EM_SETTEXTLIMIT,
				MPFROMSHORT(entryTbl->field), 0);
			WinSetDlgItemShort(hwnd, entryTbl->id,
				*(USHORT *)entryTbl->ptr, FALSE);
			break;
		case ET_RADIOBUTTON:
		case ET_CHECKBUTTON:
			WinSendDlgItemMsg(hwnd, entryTbl->id, BM_SETCHECK,
				MPFROMSHORT(*(CHAR *)entryTbl->ptr ==
				(CHAR)entryTbl->field ? 1 : 0), 0L);
			break;
		}
		entryTbl++;
	}
}

/*=======================================================================
 |
 |		ダイアログボックスのエントリフィールドからデータを取り出す
 |
 |	VOID	GetEntryData(hwnd, entryTbl, itemNum)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		ENTRY_TBL *entryTBl;	エントリデータテーブル
 |		SHORT	itemNum;	項目数
 |
 =======================================================================*/
VOID	GetEntryData(HWND hwnd, ENTRY_TBL *entryTbl, SHORT itemNum)
{
	CHAR	fieldBuf[100];
	SHORT	len;

	while (itemNum--) {
		switch (entryTbl->type) {
		case ET_STRINGFIELD:
			WinQueryDlgItemText(hwnd, entryTbl->id,
					sizeof(fieldBuf), fieldBuf);
			CopyToDBF(entryTbl->ptr, fieldBuf, entryTbl->field);
			break;
		case ET_NUMSTRFIELD:
			WinQueryDlgItemText(hwnd, entryTbl->id,
					sizeof(fieldBuf), fieldBuf);
			len = strlen(fieldBuf);
			memset(entryTbl->ptr, ' ', entryTbl->field - len);
			memcpy((CHAR *)entryTbl->ptr + entryTbl->field - len,
							fieldBuf, len);
			break;
		case ET_STZEROFIELD:
			WinQueryDlgItemText(hwnd, entryTbl->id,
					entryTbl->field + 1, entryTbl->ptr);
			break;
		case ET_NUMBERFIELD:
			WinQueryDlgItemShort(hwnd, entryTbl->id,
					entryTbl->ptr, FALSE);
			break;
		case ET_RADIOBUTTON:
			if (WinSendDlgItemMsg(hwnd, entryTbl->id,
					BM_QUERYCHECK, 0, 0))
				*(CHAR *)entryTbl->ptr = (CHAR)entryTbl->field;
			break;
		case ET_CHECKBUTTON:
			*(CHAR *)entryTbl->ptr = (CHAR)(WinSendDlgItemMsg(hwnd,
				entryTbl->id, BM_QUERYCHECK, 0, 0) ?
				entryTbl->field : ' ');
			break;
		}
		entryTbl++;
	}
}

/*=======================================================================
 |
 |		エディタ起動処理
 |
 |	VOID	ExecEditor(fname)
 |
 |		CHAR	*fname;		エディタ実行ファイル名
 |
 =======================================================================*/
VOID	ExecEditor(CHAR *fname)
{
	static	CHAR	param[40];
	static	STARTDATA	stdata = {
		50, TRUE, FALSE, 0, systemName, sv.env.editor, param,
		0, 0, 1, 0, 0, 0, 32768, 0, 0, 0, 0
	};
	USHORT	idSession, pid;

	sprintf(param, "%s %s", sv.env.edOption, fname);
	DosStartSession(&stdata, &idSession, &pid);
}

/*=======================================================================
 |
 |		ウィンドウの位置と大きさをセット
 |
 |	VOID	SetWindowPos(hwnd, nx, ny)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		SHORT	nx;		ウィンドウ内の桁数
 |		SHORT	ny;		ウィンドウ内の行数
 |
 =======================================================================*/
VOID	SetWindowPos(HWND hwnd, SHORT nx, SHORT ny)
{
	SHORT	cx, cy;

	cx = (SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CXSIZEBORDER) * 2 +
		(SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CXVSCROLL) +
		nx * gcxAveChar;

	cy = (SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYSIZEBORDER) * 2 +
		(SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYTITLEBAR) +
		(SHORT)WinQuerySysValue(HWND_DESKTOP, SV_CYMENU) +
		ny * gcyChar;

	WinSetWindowPos(hwnd, HWND_TOP,
			(cxScreen - cx) / 2,
			(cyScreen - cy) / 2,
			cx, cy, SWP_MOVE | SWP_SIZE | SWP_ACTIVATE);
}

/*=======================================================================
 |
 |		ダイアログウィンドウ表示位置を画面中央にセット
 |
 |	VOID	CenterDlgBox(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
VOID	CenterDlgBox(HWND hwnd)
{
	SWP	swp;
 
	WinQueryWindowPos(hwnd, &swp);
	WinSetWindowPos(hwnd, HWND_TOP,
			(cxScreen - swp.cx) / 2,
			(cyScreen - swp.cy) / 2,
			0, 0, SWP_MOVE);
}

/*=======================================================================
 |
 |		環境設定ファイル読み込み処理
 |
 |	VOID	ReadEnvData()
 |
 =======================================================================*/
VOID	ReadEnvData(VOID)
{
	FILE	*fp;

	if ((fp = fopen(fnEnvData, "rb")) != NULL) {
		fread(&sv, sizeof(sv), 1, fp);
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		環境設定ファイル書き込み処理
 |
 |	VOID	WriteEnvData()
 |
 =======================================================================*/
VOID	WriteEnvData(VOID)
{
	FILE	 *fp;

	if ((fp = fopen(fnEnvData, "wb")) != NULL) {
		fwrite(&sv, sizeof(sv), 1, fp);
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		新規ファイル作成処理
 |
 |	FILE	*MakeNewFile(dirName, fileName, pathName, openMode)
 |
 |		CHAR	*dirName;	ディレクトリ名
 |		CHAR	*fileName;	作成したファイル名を格納するエリア
 |		CHAR	*pathName;	作成したパス名を格納するエリア
 |		CHAR	*openMode;	ファイルオープンモード
 |
 |		FILE	*返値		作成したファイルポインタ
 |
 =======================================================================*/
FILE	*MakeNewFile(CHAR *dirName,
				CHAR *fileName, CHAR *pathName, CHAR *openMode)
{
	static	DOSFSRSEM	sem = {14};
	CHAR	pName[MAXPATHL], fName[13];
	FILE	*fp;
	DATETIME	dateTime;

	DosFSRamSemRequest(&sem, SEM_INDEFINITE_WAIT);

	DosGetDateTime(&dateTime);
	sprintf(fName, "%02u%02u%02u%02u.%02uA",
			dateTime.year % 100,
			dateTime.month,
			dateTime.day,
			dateTime.hours,
			dateTime.minutes);

	for (;;) {
		sprintf(pName, "%s\\%s", dirName, fName);
		if (access(pName, 0) != 0)
			break;
		fName[11]++;
	}

	fp = fopen(pName, openMode);

	DosFSRamSemClear(&sem);

	if (fileName != NULL)
		strcpy(fileName, fName);

	if (pathName != NULL)
		strcpy(pathName, pName);

	return(fp);
}

/*=======================================================================
 |
 |		ファイル名入力ダイアログプロシージャ
 |
 |	MRESULT CALLBACK InputFileNameDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK InputFileNameDlg(HWND hwnd, USHORT msg,
						MPARAM mp1, MPARAM mp2)
{
	INPUTFN	*ifnp;

	switch (msg) {
	case WM_INITDLG:
		ifnp = (INPUTFN *)mp2;
		WinSetWindowText(hwnd, ifnp->title);
		WinSetWindowPtr(hwnd, 0, ifnp);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			ifnp = WinQueryWindowPtr(hwnd, 0);
			WinQueryDlgItemText(hwnd, IDD_FILE_NAME,
						ifnp->bufSize, ifnp->bufp);
			break;
		case DID_CANCEL:
			break;
		}
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		ファイルコピー処理
 |
 |	VOID	FileCopy(hwnd, dstFile, srcFile)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		CHAR	*dstFile;	コピー先ファイル名
 |		CHAR	*srcFile;	コピー元ファイル名
 |
 =======================================================================*/
BOOL	FileCopy(HWND hwnd, CHAR *dstFile, CHAR *srcFile)
{
	HWND	hwndDlg, hwndDlgFrame;
	USHORT	action;
	USHORT	byteRead, byteWritten;
	HFILE	rhFile, whFile;
	struct	stat	st;
	CHAR	buf[1024];
	SWP	swp;
	POINTL	pointl;
	LONG	copySize;
	HPS	hps;

	if (DosOpen(srcFile, &rhFile, &action, 0L, FILE_READONLY,
			FILE_OPEN,
			OPEN_ACCESS_READONLY | OPEN_SHARE_DENYWRITE,
			0L) != 0) {
		WinMessageBox(HWND_DESKTOP, hwnd,
				"コピー元ファイルがありません。",
				systemName, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (access(dstFile, 0) == 0) {
		stat(dstFile, &st);
		if (st.st_size != 0) {
			if (WinMessageBox(HWND_DESKTOP, hwnd,
				"ファイルは既に存在します。上書きしますか？",
					systemName, ID_MB,
					MB_YESNO | MB_ICONQUESTION |
					MB_APPLMODAL
					) == MBID_NO) {
				DosClose(rhFile);
				return(FALSE);
			}
		}
	}

	if (DosOpen(dstFile, &whFile, &action, 0L, FILE_NORMAL,
			FILE_TRUNCATE | FILE_CREATE,
			OPEN_ACCESS_WRITEONLY | OPEN_SHARE_DENYREADWRITE,
			0L) != 0) {
		WinMessageBox(HWND_DESKTOP, hwnd,
				"コピー先ファイルがオープンできません。",
				systemName, ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		DosClose(rhFile);
		return(FALSE);
	}

	stat(srcFile, &st);

	hwndDlg = WinLoadDlg(HWND_DESKTOP, hwnd,
				 CopyMsg, 0, IDD_FILECOPY, NULL);
	hwndDlgFrame = WinWindowFromID(hwndDlg, IDD_FILECOPY_F);
	WinQueryWindowPos(hwndDlgFrame, &swp);

	hps = WinGetPS(hwndDlg);
	pointl.x = swp.x;
	pointl.y = swp.y + 1;
	GpiSetCurrentPosition(hps, &pointl);
	pointl.y = swp.y + swp.cy - 1;
	copySize = 0;

	SetPointer(SPTR_WAIT);
	for (;;) {
		DosRead(rhFile, buf, sizeof(buf), &byteRead);
		if (byteRead == 0)
			break;
		DosWrite(whFile, buf, byteRead, &byteWritten);
		if (byteWritten != byteRead) {
			WinMessageBox(HWND_DESKTOP, hwnd,
					"ファイルの書き込みに失敗しました。",
					"ファイルコピー",
					ID_MB,
					MB_OK | MB_ICONEXCLAMATION
					| MB_APPLMODAL);
			break;
		}
		copySize += byteRead;
		pointl.x = swp.x + swp.cx * copySize / st.st_size;
		GpiBox(hps, DRO_FILL, &pointl, 0, 0);
	}
	SetPointer(SPTR_ARROW);

	WinReleasePS(hps);
	WinDestroyWindow(hwndDlg);

	DosClose(rhFile);
	DosClose(whFile);

	return(TRUE);
}

/*=======================================================================
 |
 |		コピー中メッセージ表示ダイアログプロシージャ
 |
 |	MRESULT CALLBACK CopyMsg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK CopyMsg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	switch (msg) {
	case WM_INITDLG:
		CenterDlgBox(hwnd);
		break;
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		マウスカーソル形状のセット
 |
 |	VOID	SetPointer(iptr)
 |
 |		SHORT	iptr;		システムポインタ
 |
 =======================================================================*/
VOID	SetPointer(SHORT iptr)
{
	HPOINTER	hptr;

	hptr = WinQuerySysPointer(HWND_DESKTOP, iptr, FALSE);
	WinSetPointer(HWND_DESKTOP, hptr);
}

/*=======================================================================
 |
 |		ファイル名チェック
 |
 |	BOOL	CheckFileName(fileName)
 |
 |		CHAR	*fileName;	チェックするファイル名
 |
 |		BOOL	返値		TRUE:正常　　FALSE:異常
 |
 =======================================================================*/
BOOL	CheckFileName(CHAR *fileName)
{
	int	cLen;
	PSZ	lpsz;

	/* ファイル名チェック */
	if (*fileName == '\0')
		return(FALSE);
	cLen = 0;
	lpsz = fileName;
	while (*lpsz != '\0' && *lpsz != '.') {
		if (++cLen > 8 || *lpsz < 0x20)
			return(FALSE);
		switch (*lpsz++) {
		case '?':
		case '*':
		case '\"':
		case '\\':
		case '/':
		case '[':
		case ']':
		case ':':
		case '|':
		case '<':
		case '>':
		case '+':
		case '=':
		case ';':
		case ',':
			return(FALSE);
		}
	}

	/* 拡張子チェック */
	if (*lpsz++ == '\0')
		return(TRUE);
	cLen = 0;
	while (*lpsz != '\0') {
		if (++cLen > 3 || *lpsz < 0x20)
			return(FALSE);
		switch (*lpsz++) {
		case '*':
		case '?':
		case '.':
		case '\"':
		case '\\':
		case '/':
		case '[':
		case ']':
		case ':':
		case '|':
		case '<':
		case '>':
		case '+':
		case '=':
		case ';':
		case ',':
			return(FALSE);
		}
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		文字列中の英字を大文字に変換
 |
 |	VOID	ToUpperStr(str, len)
 |
 |		CHAR	*str;		文字列
 |		SHORT	len;		文字列の長さ
 |
 =======================================================================*/
VOID	ToUpperStr(CHAR *str, SHORT len)
{
	while (len--) {
		*str = (CHAR)toupper((int)*str);
		str++;
	}
}

/*=======================================================================
 |
 |		デバッグ情報表示処理
 |
 |	VOID	DEBUG(fmt, p)
 |
 |		CHAR	*fmt;		表示フォーマット（printf形式）
 |		VOID	*p;		表示データ
 |
 =======================================================================*/
VOID	DEBUG(CHAR *fmt, VOID *p)
{
	static	char	szMsg[100];

	vsprintf(szMsg, fmt, (char *)&p);
	WinPostMsg(hwndMain, WM_USER_MSGBOX, szMsg, "Debug Information");
}
