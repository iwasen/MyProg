/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側カレンダー処理関数
 *		ファイル名	: ccl.c
 *		作成者		: y.kaneko
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcsclnt.h"

/************************************************************************
*	指定日の曜日を返す						*
************************************************************************/
SHORT	PSENTRY	PSCLGetWeek(PCLDATE pClDate)
{
	SHORT	year, month, day, dayofweek;
	LONG	ltime;
	struct	tm	*tm;
	SHORT	err;

	err = 0;
	year = (int)pClDate->year;
	month = (int)pClDate->month;
	day = (int)pClDate->day;

	/* 年月日が全て０の時はシステム日付 */
	if (year == 0 && month == 0 && day == 0) {
		time(&ltime);
		tm = localtime(&ltime);
		pClDate->year  = (SHORT)tm->tm_year;
		pClDate->month = (SHORT)tm->tm_mon + 1;
		pClDate->day   = (SHORT)tm->tm_mday;
		pClDate->week  = (SHORT)tm->tm_wday;
		return err;
	}

	/* 曜日を計算する */
	if (month < 3) {  year--;  month += 12;  }
	dayofweek = (year + year / 4 - year / 100 + year / 400
		+ (13 * month + 8) / 5 + day) % 7;

	pClDate->week = (SHORT)dayofweek;

	return err;
}

/************************************************************************
*	指定日からｎ日後の日付を返す					*
************************************************************************/
SHORT	PSENTRY	PSCLGetDate(PCCB pCCB, CHAR *calendarID, PCLDATE pClDate1,
				SHORT days, PCLDATE pClDate2)
{
	struct	PRM_CL_GETDATE	*pParam;
	SHORT	err;

	if (strlen(calendarID) > 8) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_CL_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = CLGetDate(calendarID, pClDate1, days, pClDate2);
	} else {
		pParam = (struct PRM_CL_GETDATE *)pCCB->command.param;
		strcpy(pParam->calendarID, calendarID);
		pParam->date = *pClDate1;
		pParam->days = days;
		pCCB->pOutData = pClDate2;
		err = SendCommand(pCCB, FC_CL_GETDATE,
					sizeof(struct PRM_CL_GETDATE), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/************************************************************************
*	指定月のカレンダーデータを返す					*
************************************************************************/
SHORT	PSENTRY	PSCLGetCalendar(PCCB pCCB, CHAR *calendarID, PCLDATE pClDate1,
					CHAR *calendar)
{
	struct	PRM_CL_GETCALENDAR	*pParam;
	SHORT	err;

	if (strlen(calendarID) > 8) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_CL_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = CLGetCalendar(calendarID, pClDate1, calendar);
	} else {
		pParam = (struct PRM_CL_GETCALENDAR *)pCCB->command.param;
		strcpy(pParam->calendarID, calendarID);
		pParam->date = *pClDate1;
		pCCB->pOutData = calendar;
		err = SendCommand(pCCB, FC_CL_GETCALENDAR,
					sizeof(struct PRM_CL_GETCALENDAR), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}
