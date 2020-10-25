
/***********************************************************************
 |
 |	RS232C Win32 共通サブルーチンライブラリ
 |
 ||		ＲＳ２３２Ｃ関連
 |
 |			rs.c
 |
 |		Ver 1.00    1994/12/12  HAMA
 |		Ver 2.00	1998/11/19	M.HAMA	Win32対応
 |
 ************************************************************************/

#include "stdafx.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "rs.h"

#ifdef __cplusplus
extern "C" {
#endif

	/* RS232C初期化ｾｯﾄ構造体 */
typedef struct _RS232CSET {
	CHAR	portName[6];		/* ﾎﾟｰﾄ番号(COM1, COM2...) */
	DCB	dcb;					/* DCB構造体 */
	COMMTIMEOUTS	ctmo;		/* COMMTIMEOUTS構造体 */
} RS232CSET, *LPRS232CSET;

static	int	RsPrmcheck(LPRSPRM, LPRS232CSET);

static	HANDLE	rsDev;
static	int	open_flag;
static	int	duplex;
static	DWORD	tmStart;
static	DWORD	tmSetSec;

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープン
 |
 |	int	RsOpen(LPSTR rsprm)
 |
 |		LPSTR	rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |
 |		返値		０：正常
 |					１：二重モードパラメータエラー
 |					２：ボーレートパラメータエラー
 |					３：パリティーパラメータエラー
 |					４：キャラクタ長パラメータエラー
 |					５：ストップビット長パラメータエラー
 |					６：COMパラメータエラー
 |					７：オープンエラー
 |					８：電源が入っていないか、接続されていない。
 |
 =======================================================================*/
int	RsOpen(LPSTR prm)
{
	RS232CSET	rs232cset;
	LPRSPRM	lpParam;
	int	rt;

	lpParam = (LPRSPRM)prm;

	if ((rt = RsPrmcheck((LPRSPRM)lpParam, (LPRS232CSET)&rs232cset)) != 0)
		return(rt);

	/* 初期化 */
	if ((rsDev = CreateFile(rs232cset.portName, GENERIC_READ | GENERIC_WRITE, 0,
			NULL, OPEN_EXISTING, 0, NULL))	== INVALID_HANDLE_VALUE) {
		return(7);
	}

	/* 送受信ﾊﾞｯﾌｧｾｯﾄ */
	if (!SetupComm(rsDev, 1200, 1200)) {
		CloseHandle(rsDev);
		return(7);
	}

	/* RS232C環境ｾｯﾄ */
	if (!SetCommState(rsDev, &rs232cset.dcb)) {
		CloseHandle(rsDev);
		return(7);
	}

	/* ﾀｲﾑｱｳﾄ時間設定 */
	if (!SetCommTimeouts(rsDev, &rs232cset.ctmo)) {
		CloseHandle(rsDev);
		return(7);
	}

	open_flag = 1;

	return(0);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃオープンパラメータチェック
 |
 |	int	RsPrmcheck(RSPRM *rsprm, LPRS232CSET rs232cset)
 |
 |		RSPRM	*rsprm;		ＲＳ－２３２Ｃオープンパラメータ
 |		LPRS232CSET	rs232cset	パラメータ編集バッファ
 |
 |		返値			０：正常パラメータ
 |					１：二重モードパラメータエラー
 |					６：ＣｏｍＮｏパラメータエラー
 |	
 =======================================================================*/
static int RsPrmcheck(LPRSPRM rsprm, LPRS232CSET rs232cset)
{
	/* 二重モードパラメータチェック */
	switch (duplex = rsprm->duplex) {
	case DUPLEX_FULL:	/* 全二重 */
		break;
	case DUPLEX_HALF:	/* 半二重 */
		break;
	default:
		return(1);	/* パラメータエラー */
	}

	/* ＣｏｍＮｏチェック */
	switch (rsprm->comno) {
	case COM1:	/* COM1 */
	case COM2:	/* COM2 */
	case COM3:	/* COM3 */
		break;
	default:	/* パラメータエラー */
		return(6);
	}

	duplex = rsprm->duplex;

	memset(&rs232cset->dcb, 0, sizeof(DCB));
	rs232cset->dcb.DCBlength = sizeof(DCB);
	rs232cset->dcb.BaudRate = rsprm->blt;
	rs232cset->dcb.fBinary = TRUE;
	rs232cset->dcb.fOutxCtsFlow = TRUE;
	rs232cset->dcb.fDtrControl = DTR_CONTROL_ENABLE;
	rs232cset->dcb.fRtsControl = RTS_CONTROL_ENABLE;
	rs232cset->dcb.ByteSize = rsprm->chr_len;
	rs232cset->dcb.Parity = rsprm->parity;
	rs232cset->dcb.StopBits = rsprm->stp_len;
	rs232cset->dcb.XonChar = 0x11;
	rs232cset->dcb.XoffChar = 0x13;

	memset(&rs232cset->ctmo, 0, sizeof(COMMTIMEOUTS));
	rs232cset->ctmo.ReadTotalTimeoutConstant = 3000;
	rs232cset->ctmo.WriteTotalTimeoutConstant = 3000;

	sprintf(rs232cset->portName, "COM%d", rsprm->comno + 1);
			
	return(0);
}

/*=======================================================================
 |
 |		ＲＳ－２３２Ｃクローズ
 |
 |	VOID	RsClose(VOID)
 |
 =======================================================================*/
VOID RsClose()
{
	/* 未オープンなら何もしない */
	if (open_flag == 0)
		return;

	PurgeComm(rsDev, PURGE_RXABORT | PURGE_RXCLEAR);
	CloseHandle(rsDev);

	open_flag = 0;		/* オープン中フラグリセット */
}

/*=======================================================================
 |
 |		１文字受信
 |
 |	int	RsGet(VOID)
 |
 |		返値		受信文字
 |
 =======================================================================*/
DWORD	RsGet()
{
	DWORD	fModemStatus;
	DWORD	numBytes;
	CHAR	buf[2];
	DWORD	c;

	/* DSRチェック */
	GetCommModemStatus(rsDev, &fModemStatus);
	if (!(fModemStatus & MS_DSR_ON))
		return(0x100);

	/* 1文字読み込み */
	if (!ReadFile(rsDev, buf, 1, &numBytes, NULL))
		return(0xffff);

	if (numBytes != 1)
		return(0x200);

	c = (DWORD)(buf[0] & 0xff);

	return(c);
}

/*=======================================================================
 |
 |		１文字送信
 |
 |	VOID	RsPut(c)
 |
 |		int	c;		送信文字
 |
 =======================================================================*/
BOOL RsPut(int c)
{
	DWORD	numBytes;
	char	buf;
	
	if (open_flag == 0)
		return(FALSE);

	buf = (char)c;
	
	return(WriteFile(rsDev, &buf, 1, &numBytes, NULL));
}

/*=======================================================================
 |
 |		ブロック送信
 |
 |	VOID	RsSend(LPSTR s, int len)
 |
 |		LPSTR	s;		送信文字列
 |		int	len;		文字列長
 |
 =======================================================================*/
BOOL RsSend(LPSTR s, int len)
{
	if (open_flag == 0)
		return(FALSE);

	if (len == 0)
		return(FALSE);

	if (duplex == DUPLEX_HALF) {
		/* 送信可、受信禁止、ＲＴＳオン */
		RsRts(1);
	}

	/* データ送信 */
	while (len--){
		if (! RsPut(*s++))
			return(FALSE);
	}

	if (duplex == DUPLEX_HALF) {
		/* 待つ */
		TmWait(5);	

		/* 受信可、ＲＴＳオフ */
		RsRts(0);
	}
	return(TRUE);
}

/*=======================================================================
 |
 |		ＤＴＲ制御
 |
 |	VOID	RsDtr(sw);
 |
 |		int	sw;		０：ＤＴＲオフ　≠０：ＤＴＲオン
 |
 =======================================================================*/
VOID RsDtr(int sw)
{
	EscapeCommFunction(rsDev, sw == 0 ? CLRDTR: SETDTR);
}

/*=======================================================================
 |
 |		ＲＴＳ制御
 |
 |	VOID	RsRts(sw);
 |
 |		int	sw;		０：ＲＴＳオフ　≠０：ＲＴＳオン
 |
 =======================================================================*/
VOID RsRts(int sw)
{
	/* RTS　*/
	EscapeCommFunction(rsDev, sw == 0 ? CLRRTS : SETRTS);

	/* 待つ */
	TmWait(sw == 0 ? 1 : 24);	
}

/*=======================================================================
 |
 |		ＤＳＲ状態変化チェック
 |
 |	int	RsDsr()
 |
 |		返値	０：ＤＳＲ状態変更無し　１：ＤＳＲ状態変更あり
 |
 =======================================================================*/
int RsDsr()
{
	DWORD	fModemStatus;

	GetCommModemStatus(rsDev, &fModemStatus);
	return(fModemStatus & MS_DSR_ON);
}

/*=======================================================================
 |
 |		キャリアチェック
 |
 |	int	RsCd()
 |
 |		返値		０：キャリアオフ　１：キャリアオン
 |
 =======================================================================*/
int	 RsCd()
{
	DWORD	fModemStatus;

	GetCommModemStatus(rsDev, &fModemStatus);
	return(fModemStatus & MS_RLSD_ON);
}

/*=======================================================================
 |
 |		ＣＴＳチェック
 |
 |	int	RsCts()
 |
 |		返値		０：ＣＴＳオフ　１：ＣＴＳオン
 |
 =======================================================================*/
int	 RsCts(void)
{
	DWORD	fModemStatus;

	GetCommModemStatus(rsDev, &fModemStatus);
	return(fModemStatus & MS_CTS_ON);
}

/*=======================================================================
 |
 |		タイマ開始
 |
 |	VOID	TmSet(int m)
 |
 |		int	m;		時間（１０ｍｓ単位）
 |
 =======================================================================*/
VOID TmSet(int m)
{
	tmStart = GetTickCount();
	tmSetSec = (DWORD)m;
}

/*=======================================================================
 |
 |		タイムアウトチェック
 |
 |	BOOL	TmCheck()
 |
 |		返り値		０：時間内	１：タイムアウト
 |
 =======================================================================*/
BOOL TmCheck()
{
	DWORD	tt;

	tt = GetTickCount();

	return(((tt - tmStart) / 10L >= tmSetSec));
}

/*=======================================================================
 |
 |		ウエイト処理
 |
 |	VOID	TmWait(int m)
 |
 |		int	m;		時間（１０ｍｓ単位）
 |
 =======================================================================*/
VOID TmWait(int m)
{
	TmSet(m);
	for(;;){
		if (TmCheck())
			break;
	}
}

#ifdef __cplusplus
}
#endif
