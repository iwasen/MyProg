/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: ファイル選択処理			*
 *		ファイル名	: file.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <string.h>

typedef	struct	{
	CHAR	*title;
	CHAR	*pathName;
	CHAR	dirName[MAXPATHL];
	CHAR	fileName[13];
} FPARAM;
	
/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
MRESULT CALLBACK FileDlg(HWND, USHORT, MPARAM, MPARAM);
static	VOID	SetDriveList(HWND);
static	VOID	SetFileList(HWND, FPARAM *);
static	VOID	SelectDrive(HWND, FPARAM *);
static	VOID	SelectDirName(HWND, FPARAM *);
static	VOID	SelectFileName(HWND);
static	BOOL	Enter(HWND, FPARAM *);
static	VOID	SetCurrentDir(FPARAM *);

/*=======================================================================
 |
 |		ファイル選択処理
 |
 |	BOOL	SelectFile(hwnd, title, pathName)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		CHAR	*title;		ダイアログウィンドウタイトル
 |		CHAR	*pathName;	選択されたファイルのパス名
 |
 |		BOOL	返値		TRUE ：ファイルが選択された
 |					FALSE：取消（中止）
 |
 =======================================================================*/
BOOL	SelectFile(HWND hwnd, CHAR *title, CHAR *pathName)
{
	FPARAM	param;

	param.title = title;
	param.pathName = pathName;
	SetCurrentDir(&param);
	if (WinDlgBox(HWND_DESKTOP, hwnd, FileDlg, 0, IDD_FILE,
						 (PVOID)&param) == DID_OK) {
		sprintf(pathName, "%s%s", param.dirName, param.fileName);
		return(TRUE);
	} else
		return(FALSE);
}

/*=======================================================================
 |
 |		ファイル選択ダイアログプロシージャ
 |
 |	MRESULT CALLBACK FileDlg(hwnd, message, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK FileDlg(HWND hwnd, USHORT message, MPARAM mp1, MPARAM mp2)
{
	FPARAM	*param;

	switch (message) {
	case WM_INITDLG:
		param = (FPARAM *)mp2;
		WinSetWindowPtr(hwnd, 0, param);
		WinSetWindowText(hwnd, param->title);
		SetDriveList(hwnd);
		SetFileList(hwnd, param);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			param = WinQueryWindowPtr(hwnd, 0);
			if (Enter(hwnd, param) == FALSE) {
				SetFileList(hwnd, param);
				return(0);
			}
			break;
		case DID_CANCEL:
			break;
		}
		break;
	case WM_CONTROL:
		switch (SHORT1FROMMP(mp1)) {
		case IDD_FILE_DRVLIST:
			param = WinQueryWindowPtr(hwnd, 0);
			switch (SHORT2FROMMP(mp1)) {
			case LN_SELECT:
				SelectDrive(hwnd, param);
				break;
			case LN_ENTER:
				Enter(hwnd, param);
				SetFileList(hwnd, param);
				break;
			}
			break;
		case IDD_FILE_DIRLIST:
			param = WinQueryWindowPtr(hwnd, 0);
			switch (SHORT2FROMMP(mp1)) {
			case LN_SELECT:
				SelectDirName(hwnd, param);
				break;
			case LN_ENTER:
				Enter(hwnd, param);
				SetFileList(hwnd, param);
				break;
			}
			break;
		case IDD_FILE_FILELIST:
			param = WinQueryWindowPtr(hwnd, 0);
			switch (SHORT2FROMMP(mp1)) {
			case LN_SELECT:
				SelectFileName(hwnd);
				break;
			case LN_ENTER:
				if (Enter(hwnd, param) == FALSE)
					SetFileList(hwnd, param);
				else
					WinDismissDlg(hwnd, DID_OK);
				break;
			}
			break;
		}
		break;
	}
	return(WinDefDlgProc(hwnd, message, mp1, mp2));
}

/*=======================================================================
 |
 |		ドライブ一覧リスト設定処理
 |
 |	VOID	SetDriveList(HWND hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	SetDriveList(HWND hwnd)
{
	USHORT	driveNumber;
	ULONG	logicalDrives;
	SHORT	driveName;
	CHAR	strBuf[6];
	SHORT	ipos;

	DosQCurDisk(&driveNumber, &logicalDrives);
	ipos = 0;
	for (driveName = 'A'; driveName <= 'Z'; driveName++) {
		if (logicalDrives & 1) {
			sprintf(strBuf, "%c:", driveName);
			WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST,
				LM_INSERTITEM, MPFROMSHORT(ipos++), strBuf);
		}
		logicalDrives >>= 1;
	}
}

/*=======================================================================
 |
 |		ディレクトリ・ファイル一覧リスト設定処理
 |
 |	VOID	SetFileList(hwnd, param)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		FPARAM	*param;		パラメータポインタ
 |
 =======================================================================*/
static	VOID	SetFileList(HWND hwnd, FPARAM *param)
{
	HDIR	hdir;
	FILEFINDBUF	findBuf;
	USHORT	count;
	CHAR	pathName[MAXPATHL];
	SHORT	ipos;

	WinSetDlgItemText(hwnd, IDD_FILE_FILENAME, param->fileName);
	WinSetDlgItemText(hwnd, IDD_FILE_DIRNAME, param->dirName);

	WinEnableWindowUpdate(WinWindowFromID(hwnd, IDD_FILE_DIRLIST), FALSE);
	WinEnableWindowUpdate(WinWindowFromID(hwnd, IDD_FILE_FILELIST), FALSE);

	WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST, LM_DELETEALL, 0L, 0L);
	WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST, LM_DELETEALL, 0L, 0L);

	sprintf(pathName, "%s*.*", param->dirName);
	hdir = 0xffff;
	count = 1;
	DosFindFirst(pathName, &hdir, FILE_DIRECTORY, &findBuf,
					sizeof(findBuf), &count, 0);
	ipos = 0;
	while (count != 0) {
		if ((findBuf.attrFile & FILE_DIRECTORY)
				&& !(findBuf.achName[0] == '.'
					&& findBuf.achName[1] == '\0')) {
			WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST,
				LM_INSERTITEM, MPFROMSHORT(ipos++),
				findBuf.achName);
		}
		DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
	}
	DosFindClose(hdir);

	sprintf(pathName, "%s%s", param->dirName, param->fileName);
	hdir = 0xffff;
	count = 1;
	DosFindFirst(pathName, &hdir, FILE_NORMAL, &findBuf,
					sizeof(findBuf), &count, 0);
	ipos = 0;
	while (count != 0) {
		WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST,
			LM_INSERTITEM, MPFROMSHORT(ipos++), findBuf.achName);
		DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
	}
	DosFindClose(hdir);

	WinEnableWindowUpdate(WinWindowFromID(hwnd, IDD_FILE_DIRLIST), TRUE);
	WinEnableWindowUpdate(WinWindowFromID(hwnd, IDD_FILE_FILELIST), TRUE);
}

/*=======================================================================
 |
 |		ドライブ選択処理
 |
 |	VOID	SelectDrive(hwnd, param)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		FPARAM	*param;		パラメータポインタ
 |
 =======================================================================*/
static	VOID	SelectDrive(HWND hwnd, FPARAM *param)
{
	SHORT	item;
	CHAR	strBuf[MAXPATHL];
	CHAR	pathName[MAXPATHL];

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item != LIT_NONE)
		WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST,
			LM_SELECTITEM, MPFROMSHORT(item),MPFROMSHORT(FALSE));

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item != LIT_NONE)
		WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST,
			LM_SELECTITEM, MPFROMSHORT(item),MPFROMSHORT(FALSE));

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item == LIT_NONE)
		return;
	WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST, LM_QUERYITEMTEXT,
			MPFROM2SHORT(item, MAXPATHL), (MPARAM)strBuf);

	sprintf(pathName, "%c:\\%s", strBuf[0], param->fileName);
	WinSetDlgItemText(hwnd, IDD_FILE_FILENAME, pathName);
}

/*=======================================================================
 |
 |		ディレクトリ選択処理
 |
 |	VOID	SelectDirName(hwnd, param)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		FPARAM	*param;		パラメータポインタ
 |
 =======================================================================*/
static	VOID	SelectDirName(HWND hwnd, FPARAM *param)
{
	SHORT	item;
	CHAR	strBuf[MAXPATHL];
	CHAR	pathName[MAXPATHL];

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item != LIT_NONE)
		WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST,
			LM_SELECTITEM, MPFROMSHORT(item),MPFROMSHORT(FALSE));

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item != LIT_NONE)
		WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST,
			LM_SELECTITEM, MPFROMSHORT(item),MPFROMSHORT(FALSE));

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item == LIT_NONE)
		return;
	WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST, LM_QUERYITEMTEXT,
			MPFROM2SHORT(item, MAXPATHL), (MPARAM)strBuf);
	sprintf(pathName, "%s\\%s", strBuf, param->fileName);
	WinSetDlgItemText(hwnd, IDD_FILE_FILENAME, pathName);
}

/*=======================================================================
 |
 |		ファイル選択処理
 |
 |	VOID	SelectFileName(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	SelectFileName(HWND hwnd)
{
	SHORT	item;
	CHAR	strBuf[MAXPATHL];

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item != LIT_NONE)
		WinSendDlgItemMsg(hwnd, IDD_FILE_DRVLIST,
			LM_SELECTITEM, MPFROMSHORT(item),MPFROMSHORT(FALSE));

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item != LIT_NONE)
		WinSendDlgItemMsg(hwnd, IDD_FILE_DIRLIST,
			LM_SELECTITEM, MPFROMSHORT(item),MPFROMSHORT(FALSE));

	item = SHORT1FROMMR(WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST,
					LM_QUERYSELECTION, 0L, 0L));
	if (item == LIT_NONE)
		return;
	WinSendDlgItemMsg(hwnd, IDD_FILE_FILELIST, LM_QUERYITEMTEXT,
			MPFROM2SHORT(item, MAXPATHL), (MPARAM)strBuf);
	WinSetDlgItemText(hwnd, IDD_FILE_FILENAME, strBuf);
}

/*=======================================================================
 |
 |		エントリ処理
 |
 |	VOID	Enter(hwnd, param)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		FPARAM	*param;		パラメータポインタ
 |
 =======================================================================*/
static	BOOL	Enter(HWND hwnd, FPARAM *param)
{
	CHAR	strBuf[MAXPATHL];
	CHAR	pathName[MAXPATHL];
	CHAR	dirName[MAXPATHL];
	CHAR	fileName[MAXPATHL];
	CHAR	*p;

	WinQueryDlgItemText(hwnd, IDD_FILE_FILENAME, sizeof(strBuf), strBuf);
	if (strlen(strBuf) == 0)
		return(FALSE);

	if (strBuf[1] == ':') {
		strcpy(dirName, strBuf);
	} else {
		if (strBuf[0] == '\\')
			strcpy(dirName, strBuf);
		else
			sprintf(dirName, "%s%s", param->dirName, strBuf);
	}

	if (DosSearchPath(0, dirName, "*.*", pathName, sizeof(pathName)) == 0) {
		p = strrchr(pathName, '\\') + 1;
		*p = '\0';
		strcpy(param->dirName, pathName);
		return(FALSE);
	} else if (DosSearchPath(0, dirName, ".", pathName,
					sizeof(pathName)) == 0) {
		p = strrchr(pathName, '\\') + 1;
		*p = '\0';
		strcpy(param->dirName, pathName);
		if ((p = strrchr(dirName, '\\')) != NULL)
			p++;
		else if ((p = strrchr(dirName, ':')) != NULL)
			p++;
		else
			p = dirName;
		strcpy(param->fileName, p);
		if (strchr(param->fileName, '*') != NULL)
			return(FALSE);
		if (strchr(param->fileName, '?') != NULL)
			return(FALSE);
		return(TRUE);
	} else {
		if ((p = strrchr(dirName, '\\')) != NULL)
			p++;
		else if ((p = strrchr(dirName, ':')) != NULL)
			p++;
		else
			p = dirName;
		strcpy(fileName, p);
		*p = '\0';
		if (DosSearchPath(0, dirName, "*.*", pathName,
						sizeof(pathName)) == 0) {
			p = strrchr(pathName, '\\') + 1;
			*p = '\0';
			strcpy(param->dirName, pathName);
			strcpy(param->fileName, fileName);
			return(FALSE);
		} else {
			WinMessageBox(HWND_DESKTOP, hwnd,
				"ファイル名に誤りがあります。",
				systemName,
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}
}

/*=======================================================================
 |
 |		カレントディレクトリ取り出し処理
 |
 |	VOID	SetCurrentDir(param)
 |
 |		FPARAM	*param;		パラメータポインタ
 |
 =======================================================================*/
static	VOID	SetCurrentDir(FPARAM *param)
{
	CHAR	pathName[MAXPATHL];
	CHAR	*p;

	DosSearchPath(0, ".", "*.*", pathName, sizeof(pathName));
	p = strrchr(pathName, '\\') + 1;
	*p = '\0';
	strcpy(param->dirName, pathName);
	strcpy(param->fileName, "*.*");
}
