/************************************************************************
 *									*
 *		ＯＳ／２　オンラインユーザサポートシステム		*
 *			ＰＣ－ＮＥＴ／２				*
 *									*
 *		名称		: ログイン処理				*
 *		ファイル名	: login.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include "pcnet.h"
#include <stdlib.h>
#include <string.h>

/*--------------------------------------*/
/*		内部変数		*/
/*--------------------------------------*/
static	CHAR	loginErrMsg[] = "ﾕｰｻﾞｰID または ﾊﾟｽﾜｰﾄﾞ が違います.";
static	ULONG	conxxxtTime[4];

/*=======================================================================
 |
 |		ログイン処理
 |
 |	BOOL	Login(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：中止（回線断）
 |
 =======================================================================*/
BOOL	Login(USHORT chNo)
{
	CHAR	userID[9], passWord[9];
	SEL	globalSeg, localSeg;
	GINFOSEG	FAR *pgis;
	LOGIN	*mp;
	USERDBF	userData;
	CHAR	dateStr[9], timeStr[9], cCount[7];
	CHAR	logFileName[13];
	FILE	*fp;
	CHAR	strBuf[256];
	SHORT	len;

	SendLine(chNo, "\x0c", 1);	
	if ((fp = fopen(fnConxxxtMsg, "r")) != NULL) {
		while (fgets(strBuf, sizeof(strBuf), fp) != NULL) {
			len = strlen(strBuf);
			if (len != 0 && strBuf[len-1] == '\n')
				strBuf[len-1] = '\0';
			SendLine(chNo, strBuf, 1);
		}
		fclose(fp);
		SendCRLF(chNo, 1);
	}

	for (;;) {
		echoBackFlag[chNo] = TRUE;
		if (SendLine(chNo, " ﾕｰｻﾞｰID  : ", 0) == FALSE)
			return(FALSE);
		if (ReceiveLine(chNo, userID, sizeof(userID)) == FALSE)
			return(FALSE);

		echoBackFlag[chNo] = FALSE;
		if (SendLine(chNo, " ﾊﾟｽﾜｰﾄﾞ  : ", 0) == FALSE)
			return(FALSE);
		if (ReceiveLine(chNo, passWord, sizeof(passWord)) == FALSE)
			return(FALSE);
		echoBackFlag[chNo] = TRUE;
		SendCRLF(chNo, 2);

		if (CheckPassWord(userID, passWord))
			break;

		if (SendLine(chNo, loginErrMsg, 2) == FALSE)
			return(FALSE);
	}

	DosGetInfoSeg(&globalSeg, &localSeg);
	pgis = MAKEPGINFOSEG(globalSeg);
	sprintf(dateStr, "%2u/%02u/%02u",
				pgis->year % 100, pgis->month, pgis->day);
	sprintf(timeStr, "%2u:%02u:%02u",
				pgis->hour % 100, pgis->minutes, pgis->seconds);
	conxxxtTime[chNo] = pgis->time;

	DBLock(userDBp);
	if (ReadUserData(userID, &userData) == TRUE) {
		if (strncmp(userData.sDate, "        ", 8) == 0) {
			memcpy(userData.sDate, dateStr, 8);
		}
		memcpy(userData.eDate, dateStr, 8);
		CopyFromDBF(cCount, userData.cCount, sizeof(userData.cCount));
		sprintf(cCount, "%6ld", atol(cCount)+1);
		CopyToDBF(userData.cCount, cCount, sizeof(userData.cCount));
		DBUpdate(userDBp, &userData);
	}
	DBRelease(userDBp);

	strcpy(cUserID[chNo], userID);
	CopyFromDBF(cGroup[chNo], userData.group, sizeof(userData.group));
	cUserLevel[chNo] = userData.userLevel[0];

	if (sv.env.logSave == '*' && userData.logSave[0] == '*') {
		if ((logFp[chNo] = MakeNewFile(dnLOGFILE,
					logFileName, NULL, "wb")) != NULL) {
			fprintf(logFp[chNo],
				"==============================\x0d\x0a");
			fprintf(logFp[chNo],
				"USER ID    : %s\x0d\x0a", userID);
			fprintf(logFp[chNo],
				"LOGIN TIME : %s %s\x0d\x0a",
				dateStr, timeStr);
			fprintf(logFp[chNo],
				"==============================\x0d\x0a");
		} else
			logFileName[0] = '\0';
	} else
		logFileName[0] = '\0';

	if ((mp = malloc(sizeof(LOGIN))) != NULL) {
		mp->chNo = chNo;
		strcpy(mp->userID, userID);
		mp->loginDate.year = pgis->year;
		mp->loginDate.month = pgis->month;
		mp->loginDate.day = pgis->day;
		mp->loginTime.hour = pgis->hour;
		mp->loginTime.minutes = pgis->minutes;
		mp->loginTime.seconds = pgis->seconds;
		strcpy(mp->logFile, logFileName);
		while (WinPostMsg(hwndMain, WM_USER_LOGIN,
					(MPARAM)mp, 0L) == FALSE);
	}

	return(TRUE);
}

/*=======================================================================
 |
 |		パスワードチェック処理
 |
 |	BOOL	CheckPassWord(userID, passWord)
 |
 |		CHAR	*userID;	ユーザＩＤ
 |		CHAR	*passWord;	パスワード
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：パスワードが違う
 |
 =======================================================================*/
BOOL	CheckPassWord(CHAR *userID, CHAR *passWord)
{
	CHAR	passWordBuf[8];
	USHORT	i;
	USERDBF	userData;

	if (ReadUserData(userID, &userData) == FALSE)
		return(FALSE);

	for (i = 0; i < 8; i++) {
		if ((passWordBuf[i] = passWord[i]) == '\0')
			break;
	}
	for ( ; i < 8; i++)
		passWordBuf[i] = ' ';

	if (memcmp(userData.password, passWordBuf, 8) != 0)
		return(FALSE);

	return(TRUE);
}

/*=======================================================================
 |
 |		ユーザ管理情報読み込み処理
 |
 |	BOOL	ReadUserData(userID, userData)
 |
 |		CHAR	*userID;	ユーザＩＤ
 |		USERDBF	*userData;	ユーザ管理情報読み込みバッファ
 |
 |		BOOL	返値		TRUE ：正常
 |					FALSE：ユーザＩＤ未登録
 |
 =======================================================================*/
BOOL	ReadUserData(CHAR *userID, USERDBF *userData)
{
	CHAR	userIDBuf[8];
	BOOL	st;


	DBLock(userDBp);

	CopyToDBF(userIDBuf, userID, 8);
	if (DBSearch(userDBp, userIDBuf, 8, indexUserID)) {
		if (userData != NULL)
			DBRead(userDBp, userData);
		st = TRUE;
	} else
		st = FALSE;

	DBRelease(userDBp);

	return(st);
}

/*=======================================================================
 |
 |		ログアウト処理
 |
 |	VOID	Logout(chNo)
 |
 |		USHORT	chNo;		チャネル番号
 |
 =======================================================================*/
VOID	Logout(USHORT chNo)
{
	SEL	globalSeg, localSeg;
	GINFOSEG	FAR *pgis;
	USERDBF	userData;
	ULONG	usedTime;
	ULONG	cTime;
	LOGOUT	*mp;
	CHAR	strBuf[11], hour[5], minute[3], second[3];
	CHAR	msg[80];

	DosGetInfoSeg(&globalSeg, &localSeg);
	pgis = MAKEPGINFOSEG(globalSeg);
	usedTime = pgis->time - conxxxtTime[chNo];

	sprintf(msg,
		"ご利用ありがとうございました。 通信時間は %d分 %d秒です。",
		(USHORT)(usedTime / 60), (USHORT)(usedTime % 60));
	SendCRLF(chNo, 1);
	SendLine(chNo, msg, 2);
	SendLine(chNo, "回線を切って下さい。", 2);

	if (logFp[chNo] != NULL) {
		fclose(logFp[chNo]);
		logFp[chNo] = NULL;
	}

	DBLock(userDBp);
	if (ReadUserData(cUserID[chNo], &userData) == TRUE) {
		CopyFromDBF(hour, userData.cTime, 4);
		CopyFromDBF(minute, userData.cTime+5, 2);
		CopyFromDBF(second, userData.cTime+8, 2);
		cTime = usedTime + atoi(hour) * 3600L + atoi(minute) * 60
							+ atoi(second);
		sprintf(strBuf, "%4u:%02u:%02u",
				(USHORT)(cTime / 3600),
				(USHORT)(cTime / 60 % 60),
				(USHORT)(cTime % 60));
		CopyToDBF(userData.cTime, strBuf, sizeof(userData.cTime));
		DBUpdate(userDBp, &userData);
	}
	DBRelease(userDBp);

	if ((mp = malloc(sizeof(LOGOUT))) != NULL) {
		mp->chNo = chNo;
		mp->logoutTime.hour = pgis->hour;
		mp->logoutTime.minutes = pgis->minutes;
		mp->logoutTime.seconds = pgis->seconds;
		mp->usedTime = usedTime;
		while (WinPostMsg(hwndMain, WM_USER_LOGOUT,
					(MPARAM)mp, 0L) == FALSE);
	}
}
