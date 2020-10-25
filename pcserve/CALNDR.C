/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �J�����_�[����
 *		�t�@�C����	: calndr.c
 *		�쐬��		: y.kaneko
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include "pcsdef.h"
#include "pcsdb.h"
#include "pcscl.h"

static SHORT	CLGetFlagIndex(SHORT, SHORT, SHORT);
static SHORT	CLGetDaysOfYear(SHORT);
static SHORT	CLGetDaysOfMonth(SHORT, SHORT);
static SHORT	CLGetCalendarRec(PDB *, CHAR *, SHORT, CALENDR *);

/************************************************************************
*	�w������炎����̓��t��Ԃ�					*
************************************************************************/
SHORT	CLGetDate(CHAR *calendarID, PCLDATE pClDate1, SHORT days,
							PCLDATE pClDate2)
{
	PDB	dbp;
	SHORT	idxNo;
	SHORT	err;
	SHORT	year, month, day;
	LONG	ltime;
	struct	tm	tmTbl;
	CALENDR	Calendr;
	SHORT	daysOfYear;
	SHORT	flgIndex;
	SHORT	realDays;
	SHORT	daycnt, i;

	year = pClDate1->year;
	month = pClDate1->month;
	day = pClDate1->day;

	/* �N�������S�ĂO�̎��̓V�X�e�����t */
	if (year == 0 && month == 0 && day == 0) {
		time(&ltime);
		tmTbl = *localtime(&ltime);
		pClDate1->year  = year = tmTbl.tm_year + 1900;
		pClDate1->month = month = tmTbl.tm_mon + 1;
		pClDate1->day   = day = tmTbl.tm_mday;
		pClDate1->week  = tmTbl.tm_wday;
	} else {
		tmTbl.tm_year = pClDate1->year - 1900;
		tmTbl.tm_mon = pClDate1->month - 1;
		tmTbl.tm_mday = pClDate1->day;
	}
	tmTbl.tm_hour = tmTbl.tm_min = tmTbl.tm_sec = tmTbl.tm_isdst = 0;

	/* �f�[�^�x�[�X�I�[�v�� */
	if ((err = DBOpen(FN_CLDBF, &dbp, PERMISSION_ALL)) != 0)
		return err;

	if (DBIndex(dbp, FN_CLNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(dbp, FN_CLNDX, "CODEYEAR", 1, &idxNo))
								!= 0) {
			DBClose(dbp);
			return err;
		}
	}

	/* �w��N�̃J�����_�[���R�[�h��ǂ� */
	if ((err = CLGetCalendarRec(&dbp, calendarID, year, &Calendr)) != 0) {
		DBClose(dbp);
		return err;
	}

	daycnt=realDays=0;
	flgIndex = CLGetFlagIndex(year, month, day);

	/* ������̏ꍇ */
	if (days > 0) {
		daysOfYear = CLGetDaysOfYear(year);
		flgIndex++;
		for( ; ; ) {
			for(i=0; daycnt<days && flgIndex+i<daysOfYear;
							realDays++,i++) {
				if (Calendr.Flag[flgIndex+i] == '0') daycnt++;
			}
			if (daycnt == days) {
				tmTbl.tm_mday = tmTbl.tm_mday + realDays;
				if (mktime(&tmTbl) == (time_t)-1) {
					DBClose(dbp);
					return ERROR_CL_NOTFOUND;
				}
				pClDate2->year  = tmTbl.tm_year + 1900;
				pClDate2->month = tmTbl.tm_mon + 1;
				pClDate2->day   = tmTbl.tm_mday;
				pClDate2->week  = tmTbl.tm_wday;
				break;
			}
			year++;
			if ((err = CLGetCalendarRec(&dbp, calendarID, year,
							&Calendr)) != 0) {
				DBClose(dbp);
				return err;
			}
			daysOfYear = CLGetDaysOfYear(year);
			flgIndex = 0;
		}

	/* �����O�̏ꍇ */
	} else {
		flgIndex--;
		for( ; ; ) {
			for(i=0; daycnt>days && flgIndex+i>=0; realDays--,i--) {
				if (Calendr.Flag[flgIndex+i] == '0') daycnt--;
			}
			if (daycnt == days) {
				tmTbl.tm_mday = tmTbl.tm_mday + realDays;
				if (mktime(&tmTbl) == (time_t)-1) {
					DBClose(dbp);
					return ERROR_CL_NOTFOUND;
				}
				pClDate2->year  = tmTbl.tm_year + 1900;
				pClDate2->month = tmTbl.tm_mon + 1;
				pClDate2->day   = tmTbl.tm_mday;
				pClDate2->week  = tmTbl.tm_wday;
				break;
			}
			year--;
			if ((err = CLGetCalendarRec(&dbp, calendarID, year,
							&Calendr)) != 0) {
				DBClose(dbp);
				return err;
			}
			flgIndex = CLGetDaysOfYear(year)-1;
		}
	}

	DBClose(dbp);

	return 0;
}

/************************************************************************
*	�w�茎�̃J�����_�[�f�[�^��Ԃ�					*
************************************************************************/
SHORT	CLGetCalendar(CHAR *calendarID, PCLDATE pClDate, CHAR *calendar)
{
	PDB	dbp;
	SHORT	idxNo;
	SHORT	err;
	SHORT	year, month;
	LONG	ltime;
	struct	tm	*tm;
	CALENDR	Calendr;
	SHORT	flgIndex;
	SHORT	daysOfMonth;

	year = pClDate->year;
	month = pClDate->month;

	/* �N�������S�ĂO�̎��̓V�X�e�����t */
	if (year == 0 && month == 0) {
		time(&ltime);
		tm = localtime(&ltime);
		pClDate->year  = year = tm->tm_year+1900;
		pClDate->month = month = tm->tm_mon + 1;
		pClDate->day   = tm->tm_mday;
		pClDate->week  = tm->tm_wday;
	}

	/* �f�[�^�x�[�X�I�[�v�� */
	if ((err = DBOpen(FN_CLDBF, &dbp, PERMISSION_ALL)) != 0)
		return err;

	if (DBIndex(dbp, FN_CLNDX, &idxNo) != 0) {
		if ((err = DBIdxCreate(dbp, FN_CLNDX, "CODEYEAR", 1, &idxNo)) != 0) {
			DBClose(dbp);
			return err;
		}
	}

	/* �w��N�̃J�����_�[���R�[�h��ǂ� */
	if ((err = CLGetCalendarRec(&dbp, calendarID, year, &Calendr)) != 0) {
		DBClose(dbp);
		return err;
	}

	flgIndex = CLGetFlagIndex(year, month, 1);
	daysOfMonth = CLGetDaysOfMonth(year, month);
	memcpy(calendar, Calendr.Flag+flgIndex, daysOfMonth);
	calendar[daysOfMonth] = '\0';

	DBClose(dbp);

	return 0;
}


/************************************************************************
*	�w����̃C���f�b�N�X��Ԃ�					*
************************************************************************/
static SHORT	CLGetFlagIndex(SHORT year, SHORT month, SHORT day)
{
	SHORT	index;
	SHORT	daysTbl[2][12] = {
		{31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31},
		{31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31}};
	SHORT	leap;
	SHORT	i;

	leap = year%4 == 0 && year%100 != 0 || year%400 == 0;
	for (i=0,index=day-1; i<month-1; i++)
		index += daysTbl[leap][i];

	return index;
}


/************************************************************************
*	�w�茎�̓�����Ԃ�						*
************************************************************************/
static SHORT	CLGetDaysOfMonth(SHORT year, SHORT month)
{
	SHORT	days;
	SHORT	daysTbl[2][12] = {
		{31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31},
		{31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31}};
	SHORT	leap;

	leap = year%4 == 0 && year%100 != 0 || year%400 == 0;
	days = daysTbl[leap][month-1];

	return days;
}


/************************************************************************
*	�w��N�̓�����Ԃ�						*
************************************************************************/
static SHORT	CLGetDaysOfYear(SHORT year)
{
	SHORT	days;
	SHORT	daysTbl[2] = {365,366};
	SHORT	leap;

	leap = year%4 == 0 && year%100 != 0 || year%400 == 0;
	days = daysTbl[leap];

	return days;
}


/************************************************************************
*	�w��N�̃J�����_�[���R�[�h��ǂ�				*
************************************************************************/
static SHORT	CLGetCalendarRec(PDB *dbp, CHAR *calendarID, SHORT year, CALENDR *Calendr)
{
	CHAR	buf[13];
	SHORT	find;
	SHORT	err;

	memset(buf, ' ', sizeof(buf));
	memcpy(buf, calendarID, strlen(calendarID));
	sprintf(buf+8, "%4d", year);

	if ((err = DBSearch2(*dbp, buf, sizeof(buf), &find)) != 0)
		return err;

	if (find != 1)
		return ERROR_CL_NOTFOUND;

	DBRead(*dbp, (CHAR *)Calendr);

	return 0;
}