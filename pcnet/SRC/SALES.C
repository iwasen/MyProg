/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: 販売情報入力処理			*
 *		ファイル名	: sales.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <string.h>

/*--------------------------------------*/
/*		内部関数		*/
/*--------------------------------------*/
static	VOID	SetSalesData(HWND);
static	VOID	WriteSalesData(HWND, BOOL);
static	BOOL	CreateSalesDBF(VOID);
static	BOOL	UseSalesIndex(BOOL);

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	SALESDBF	salesBuf;	/* 販売情報ファイルバッファ */
static	SALESDBF	salesBuf2;	/* 販売情報ファイルバッファ２ */
static	ENTRY_TBL	entryTbl[6] = {	/* 入力情報テーブル */
	{ET_STRINGFIELD, IDD_SALE_SExxxN, 40, salesBuf.sexxxn},
	{ET_STRINGFIELD, IDD_SALE_SERIAL, 16, salesBuf.serial},
	{ET_STRINGFIELD, IDD_SALE_DATE,    8, salesBuf.date},
	{ET_STRINGFIELD, IDD_SALE_PRICE,  12, salesBuf.price},
	{ET_STRINGFIELD, IDD_SALE_SHOP,   40, salesBuf.shop},
	{ET_STRINGFIELD, IDD_SALE_BIKOU,  40, salesBuf.bikou}
};
static	CHAR	userID[8];		/* ユーザＩＤ */
static	SHORT	page;			/* カレントページ */
static	SHORT	nPage;			/* ページ数 */

/*=======================================================================
 |
 |		販売情報入力ダイアログプロシージャ
 |
 |	MRESULT CALLBACK SalesDlg(hwnd, msg, mp1, mp2)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		USHORT	msg;		メッセージ識別子
 |		MPARAM	mp1;		メッセージパラメータ１
 |		MPARAM	mp2;		メッセージパラメータ２
 |
 =======================================================================*/
MRESULT CALLBACK SalesDlg(HWND hwnd, USHORT msg, MPARAM mp1, MPARAM mp2)
{
	switch (msg) {
	case WM_INITDLG:
		memcpy(userID, (CHAR *)mp2, 8);
		nPage = (SHORT)DBCount(salesDBp, userID, 8, indexSales);
		if (nPage == 0)
			page = 0;
		else
			page = nPage - 1;
		SetSalesData(hwnd);
		CenterDlgBox(hwnd);
		break;
	case WM_COMMAND:
		switch(SHORT1FROMMP(mp1)) {
		case DID_OK:
			WriteSalesData(hwnd, FALSE);
			break;
		case DID_CANCEL:
			break;
		case IDD_CMD_DELETE:
			if (WinMessageBox(HWND_DESKTOP, hwnd,
					"削除します。よろしいですか？",
					"確　認", ID_MB,
					MB_YESNO | MB_ICONQUESTION |
					MB_APPLMODAL) == MBID_YES) {
				DBDelete2(salesDBp);
				nPage--;
				if (page == nPage && page != 0)
					page--;
				SetSalesData(hwnd);
			}
			return(0);
		case IDD_CMD_PPAGE:
			WriteSalesData(hwnd, TRUE);
			if (page > 0) {
				page--;
				SetSalesData(hwnd);
			}
			return(0);
		case IDD_CMD_NPAGE:
			WriteSalesData(hwnd, TRUE);
			if (page < nPage) {
				page++;
				SetSalesData(hwnd);
			}
			return(0);
		}
	}
	return(WinDefDlgProc(hwnd, msg, mp1, mp2));
}

/*=======================================================================
 |
 |		販売情報をダイアログに表示する
 |
 |	VOID	SetSalesData(hwnd)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |
 =======================================================================*/
static	VOID	SetSalesData(HWND hwnd)
{
	CHAR	strBuf[40];

	if (page == nPage) {
		memset(&salesBuf, ' ', sizeof(salesBuf));
		memcpy(salesBuf.userID, userID, 8);
	} else {
		DBSearch(salesDBp, userID, 8, indexSales);
		DBSkip(salesDBp, (long)page);
		DBRead(salesDBp, &salesBuf);
	}
	salesBuf2 = salesBuf;

	SetEntryData(hwnd, entryTbl, sizeof(entryTbl) / sizeof(ENTRY_TBL));

	if (page == nPage)
		sprintf(strBuf, "販売情報（新規登録）");
	else
		sprintf(strBuf, "販売情報 (%d/%d)", page+1, nPage);
	WinSetWindowText(hwnd, strBuf);

	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_DELETE), page != nPage);
	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_PPAGE), page != 0);
	WinEnableWindow(WinWindowFromID(hwnd, IDD_CMD_NPAGE), page != nPage);
}

/*=======================================================================
 |
 |		ダイアログより販売情報を取り出し、ファイルに書き込む
 |
 |	VOID	WriteSalesData(hwnd, fCheck)
 |
 |		HWND	hwnd;		ウィンドウハンドル
 |		BOOL	fCheck;		更新確認有無フラグ
 |
 =======================================================================*/
static	VOID	WriteSalesData(HWND hwnd, BOOL fCheck)
{
	GetEntryData(hwnd, entryTbl, sizeof(entryTbl) / sizeof(ENTRY_TBL));

	if (memcmp(&salesBuf, &salesBuf2, sizeof(SALESDBF)) == 0)
		return;

	if (fCheck) {
		if (WinMessageBox(HWND_DESKTOP, hwnd,
				"更新しますか？",
				"確　認", ID_MB,
				MB_YESNO | MB_ICONQUESTION |
				MB_APPLMODAL) != MBID_YES) {
			return;
		}
	}

	if (page == nPage) {
		DBStore(salesDBp, &salesBuf);
		nPage++;
	} else {
		DBUpdate(salesDBp, &salesBuf);
	}
}

/*=======================================================================
 |
 |		販売情報ファイルオープン処理
 |
 |	BOOL	OpenSalesDBF(fIndexCreate)
 |
 |		BOOL	fIndexCreate;	インデックスファイル作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：オープン失敗（中止）
 |
 =======================================================================*/
BOOL	OpenSalesDBF(BOOL fIndexCreate)
{
	if ((salesDBp = DBOpen(fnSALESDBF)) == NULL) {
		if (WinMessageBox(HWND_DESKTOP, hwndMain,
				"新規に作成しますか？",
				"販売情報ファイルがありません！", ID_MB,
				MB_YESNO | MB_ICONQUESTION | MB_APPLMODAL
				) == MBID_YES)
			return(CreateSalesDBF());
		else
			return(FALSE);
	} else {
		if (DBCheckSize(salesDBp))
			fIndexCreate = TRUE;
	}

	if (UseSalesIndex(fIndexCreate) == FALSE) {
		DBClose(salesDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		販売情報ファイル新規作成処理
 |
 |	BOOL	CreateSalesDBF()
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	CreateSalesDBF(VOID)
{
	static	DBF_I	fieldData[] = {
		{"ユーザＩＤ", 'C',  8, 0},
		{"製品名",     'C', 40, 0},
		{"シリアルNo", 'C', 16, 0},
		{"購入日付",   'C',  8, 0},
		{"購入価格",   'C', 12, 0},
		{"販売店",     'C', 40, 0},
		{"備考",       'C', 40, 0}
	};

	if ((salesDBp = DBCreate(fnSALESDBF, fieldData,
				sizeof(fieldData)/sizeof(DBF_I))) == NULL) {
		WinMessageBox(HWND_DESKTOP, hwndMain,
				"ファイルが作成できませんでした。",
				"販売情報ファイルアクセスエラー！",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
		return(FALSE);
	}

	if (UseSalesIndex(TRUE) == FALSE) {
		DBClose(salesDBp);
		return(FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		販売情報ファイルのインデックスファイルオープン／作成
 |
 |	BOOL	UseSalesIndex(fCreate)
 |
 |		BOOL	fCreate;	作成フラグ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：作成失敗
 |
 =======================================================================*/
static	BOOL	UseSalesIndex(BOOL fCreate)
{
	if (fCreate || (indexSales = DBIndex(salesDBp, fnSALESNDX)) == 0) {
		if ((indexSales = DBIdxCreate(salesDBp, fnSALESNDX,
					"ユーザＩＤ+購入日付", 0)) == 0) {
			WinMessageBox(HWND_DESKTOP, hwndMain,
				"インデックスファイルが作成できませんでした。",
				"販売情報ファイルアクセスエラー！",
				ID_MB,
				MB_OK | MB_ICONEXCLAMATION | MB_APPLMODAL);
			return(FALSE);
		}
	}
	return(TRUE);
}
