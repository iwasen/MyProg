/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: ファイル保守				*
 *		ファイル名	: hoshu.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <string.h>

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	VOID	Maintenance(HWND);
static	VOID	BBSDeleteFiles(VOID);
static	VOID	MailDeleteFiles(VOID);
static	VOID	LogDeleteFiles(VOID);
MRESULT CALLBACK MainteMsg(HWND, USHORT, MPARAM, MPARAM);

/*=======================================================================
 |
 |		保守ダイアログプロシージャ
 |
 |	MRESULT CALLBACK MaintenanceDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK MaintenanceDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	switch (msg) {
	case WM_INITDLG:
		WinSendDlgItemMsg(hwnd, IDD_MNT_USERIDX, BM_SETCHECK,
					MPFROMSHORT(1), 0L);
		WinSendDlgItemMsg(hwnd, IDD_MNT_BBSMIDX, BM_SETCHECK,
					MPFROMSHORT(1), 0L);
		WinSendDlgItemMsg(hwnd, IDD_MNT_MAILIDX, BM_SETCHECK,
					MPFROMSHORT(1), 0L);
		WinSendDlgItemMsg(hwnd, IDD_MNT_SALEIDX, BM_SETCHECK,
					MPFROMSHORT(1), 0L);
		WinSendDlgItemMsg(hwnd, IDD_MNT_BBSIDX, BM_SETCHECK,
					MPFROMSHORT(1), 0L);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			Maintenance(hwnd);
			return(0);
		case DID_CANCEL:
			WinPostMsg(NULL, WM_QUIT, 0, 0);
			return(0);
		}
		break;
	case WM_CLOSE:
		WinPostMsg(NULL, WM_QUIT, 0, 0);
		return(0);
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		ファイル保守処理
 |
 |	VOID	Maintenance(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	Maintenance(HWND hwnd)
{
	HWND	hwndDlg;
	DB	*BBSDBp;
	BBSMDBF	BBSMBuf;
	CHAR	BBSName[10];

	hwndDlg = WinLoadDlg(HWND_DESKTOP, hwnd,
				 MainteMsg, 0, IDD_MNT_EXEC, NULL);

	WinSetDlgItemText(hwndDlg, IDD_MNT_MSG1, "ファイル修復中！！");

	SetPointer(SPTR_WAIT);

	/* ユーザ管理ファイルインデックス再構築 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_USERIDX, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"ユーザ管理ファイル");
		if (OpenUserDBF(TRUE) == TRUE) {
			DBClose(userDBp);
		}
	}

	/* 掲示板管理ファイルインデックス再構築 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_BBSMIDX, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"掲示板管理ファイル");
		if (OpenBBSMDBF(TRUE) == TRUE) {
			DBClose(BBSMDBp);
		}
	}

	/* メール管理ファイルインデックス再構築 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_MAILIDX, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"メール管理ファイル");
		if (OpenMailDBF(TRUE) ==TRUE) {
			DBClose(mailDBp);
		}
	}

	/* 販売情報ファイルインデックス再構築 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_SALEIDX, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"販売情報ファイル");
		if (OpenSalesDBF(TRUE) == TRUE) {
			DBClose(salesDBp);
		}
	}

	/* 各掲示板ファイルインデックス再構築 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_BBSIDX, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"各掲示板ファイル");
		if (OpenBBSMDBF(FALSE) == TRUE) {
			for (DBChgIdx(BBSMDBp, 0); !DBEof(BBSMDBp);
						DBSkip(BBSMDBp, 1L)) {
				DBRead(BBSMDBp, &BBSMBuf);
				CopyFromDBF(BBSName, BBSMBuf.name,
						sizeof(BBSMBuf.name));
				if ((BBSDBp = OpenBBSDBF(BBSName, TRUE))
								!= NULL) {
					CloseBBSDBF(BBSDBp);
				}
			}
			DBClose(BBSMDBp);
		}
	}

	WinSetDlgItemText(hwndDlg, IDD_MNT_MSG1, "ファイル整理中！！");

	/* 不要掲示ファイル削除 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_BBSDEL, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"掲示板ファイル");
		if (OpenBBSMDBF(FALSE) == TRUE) {
			BBSDeleteFiles();
			DBClose(BBSMDBp);
		}
	}

	/* 不要掲示ファイル削除 */
	if (WinSendDlgItemMsg(hwnd, IDD_MNT_MAILDEL, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"メールファイル");
		if (OpenMailDBF(FALSE) == TRUE) {
			MailDeleteFiles();
			DBClose(mailDBp);
		}
	}

	if (WinSendDlgItemMsg(hwnd, IDD_MNT_LOGFILE, BM_QUERYCHECK, 0L, 0L)) {
		WinSetDlgItemText(hwndDlg, IDD_MNT_MSG2,
					"通信履歴ファイル");
		if (OpenLogDBF(FALSE) == TRUE) {
			LogDeleteFiles();
			DBClose(logDBp);
		}
	}

	SetPointer(SPTR_ARROW);

	WinDestroyWindow(hwndDlg);
}

/*=======================================================================
 |
 |		実行中メッセージ表示ダイアログプロシージャ
 |
 |	MRESULT CALLBACK MainteMsg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK MainteMsg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
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
 |		不要掲示ファイル削除処理
 |
 |	VOID	BBSDeleteFiles()
 |
 =======================================================================*/
static	VOID	BBSDeleteFiles(VOID)
{
	DB	*dbp;
	IDX	*ip;
	HDIR	hdir;
	FILEFINDBUF	findBuf;
	USHORT	count;
	CHAR	fileName[12], pathName[26];
	BBSMDBF	BBSMBuf;
	CHAR	BBSName[10];

	if ((ip = IDXCreate("TMP.NDX", "ファイル名", 0)) == NULL)
		return;

	for (DBChgIdx(BBSMDBp, 0); !DBEof(BBSMDBp); DBSkip(BBSMDBp, 1L)) {
		DBRead(BBSMDBp, &BBSMBuf);
		CopyFromDBF(BBSName, BBSMBuf.name, sizeof(BBSMBuf.name));
		sprintf(pathName, "%s.BBS\\%s", BBSName, fnBBSDBF);
		if ((dbp = DBOpen(pathName)) == NULL)
			continue;
		_dbErrCode = 0;
		IDXMake(dbp->dp, ip);
		DBClose(dbp);
		if (_dbErrCode != 0)
			continue;

		hdir = 0xffff;
		count = 1;
		sprintf(pathName, "%s.BBS\\*.*", BBSName);
		DosFindFirst(pathName, &hdir, FILE_NORMAL, &findBuf,
					sizeof(findBuf), &count, 0);
		while (count != 0) {
			CopyToDBF(fileName, findBuf.achName, 12);
			if (strcmp(findBuf.achName, fnBBSDBF) != 0 &&
				strcmp(findBuf.achName, fnBBSNDX) != 0 &&
				IDXSearch(ip, fileName, 12) == 0L) {
				sprintf(pathName, "%s.BBS\\%s", BBSName,
							findBuf.achName);
				DosDelete(pathName, 0L);
			}
			DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
		}
		DosFindClose(hdir);
	}
	IDXClose(ip);
	DosDelete("TMP.NDX", 0L);
}

/*=======================================================================
 |
 |		不要メールファイル削除処理
 |
 |	VOID	MailDeleteFiles()
 |
 =======================================================================*/
static	VOID	MailDeleteFiles(VOID)
{
	IDX	*ip;
	HDIR	hdir = 0xffff;
	FILEFINDBUF	findBuf;
	USHORT	count = 1;
	CHAR	fileName[12], pathName[26];

	if ((ip = IDXCreate("TMP.NDX", "ファイル名", 0)) == NULL)
		return;
	_dbErrCode = 0;
	IDXMake(mailDBp->dp, ip);
	if (_dbErrCode == 0) {
		sprintf(pathName, "%s\\*.*", dnMAIL);
		DosFindFirst(pathName, &hdir, FILE_NORMAL, &findBuf,
					sizeof(findBuf), &count, 0);
		while (count != 0) {
			CopyToDBF(fileName, findBuf.achName, 12);
			if (IDXSearch(ip, fileName, 12) == 0L) {
				sprintf(pathName, "%s\\%s", dnMAIL,
							findBuf.achName);
				DosDelete(pathName, 0L);
			}
			DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
		}
		DosFindClose(hdir);
	}

	IDXClose(ip);
	DosDelete("TMP.NDX", 0L);
}

/*=======================================================================
 |
 |		不要通信内容保存ファイル削除処理
 |
 |	VOID	LogDeleteFiles()
 |
 =======================================================================*/
static	VOID	LogDeleteFiles(VOID)
{
	IDX	*ip;
	HDIR	hdir = 0xffff;
	FILEFINDBUF	findBuf;
	USHORT	count = 1;
	CHAR	fileName[12], pathName[26];

	if ((ip = IDXCreate("TMP.NDX", "ファイル名", 0)) == NULL)
		return;
	_dbErrCode = 0;
	IDXMake(logDBp->dp, ip);
	if (_dbErrCode == 0) {
		sprintf(pathName, "%s\\*.*", dnLOGFILE);
		DosFindFirst(pathName, &hdir, FILE_NORMAL, &findBuf,
					sizeof(findBuf), &count, 0);
		while (count != 0) {
			CopyToDBF(fileName, findBuf.achName, 12);
			if (IDXSearch(ip, fileName, 12) == 0L) {
				sprintf(pathName, "%s\\%s", dnLOGFILE,
							findBuf.achName);
				DosDelete(pathName, 0L);
			}
			DosFindNext(hdir, &findBuf, sizeof(findBuf), &count);
		}
		DosFindClose(hdir);
	}

	IDXClose(ip);
	DosDelete("TMP.NDX", 0L);
}
