/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: グループウェア処理
 *		ファイル名	: sgw.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

static	SHORT	_CheckAccess(CHAR *, CHAR *, CHAR *, CHAR *, SHORT *);
static	SHORT	_SetPassword(CHAR *, CHAR *, CHAR *, SHORT *);
static	SHORT	_CheckTerminal(CHAR *, CHAR *, SHORT *);

/*=======================================================================
 |
 |		ユーザパスワードチェック処理
 |
 |	VOID	PSGWCheckUser(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGWCheckUser(PSCB pSCB)
{
	struct	PRM_GW_CHECK_USER	*pParam;

	pParam = (struct PRM_GW_CHECK_USER *)pSCB->command.param;
	pSCB->response.status = _CheckUser(pParam->userID, pParam->password,
					&pSCB->response.ret.sValue);

	pSCB->response.dataLength = 0;
}

SHORT	_CheckUser(CHAR *userID, CHAR *password, SHORT *result)
{
	PDB	pUserDB;
	CHAR	tmpUserID[11];
	CHAR	tmpPassword[11];
	SHORT	eof;
	SHORT	fieldType;
	SHORT	dataSize;
	SHORT	err;

	*result = 0;

	if ((err = OpenUserDBF(&pUserDB)) != 0)
		return err;

	StringToUpper(userID);
	memset(tmpUserID, ' ', 11);
	memcpy(tmpUserID + 1, userID, min(strlen(userID), 10));
	if ((err = DBSearch(pUserDB, tmpUserID, 11)) != 0) {
		DBClose(pUserDB);
		return err;
	}
	
	DBEof(pUserDB, &eof);
	if (eof != 0) {
		DBClose(pUserDB);
		return 0;
	}

	if ((err = DBGetRecord(pUserDB)) != 0) {
		DBClose(pUserDB);
		return err;
	}
	if ((err = DBGetField(pUserDB, "PASSWORD", tmpPassword, &fieldType,
							&dataSize)) != 0) {
		DBClose(pUserDB);
		return err;
	}

	DBClose(pUserDB);

	if (strcmp(password, tmpPassword) == 0)
		*result = 1;

	return 0;
}

/*=======================================================================
 |
 |		実行権チェック処理
 |
 |	VOID	PSGWCheckAccess(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGWCheckAccess(PSCB pSCB)
{
	struct	PRM_GW_CHECK_ACCESS	*pParam;

	pParam = (struct PRM_GW_CHECK_ACCESS *)pSCB->command.param;
	pSCB->response.status = _CheckAccess(pParam->userID, pParam->termID,
			pParam->systemName, pParam->subSystemName,
			&pSCB->response.ret.sValue);

	pSCB->response.dataLength = 0;
}

static	SHORT	_CheckAccess(CHAR *userID, CHAR *termID, CHAR *systemName,
					CHAR *subSystemName, SHORT *result)
{
	CHAR	tmpUserID[10];
	CHAR	tmpTermID[10];
	PDB	pAccessDB;
	PDB	pUserGrpDB;
	PDB	pTermGrpDB;
	struct	{
		CHAR	userGrpFlag;
		CHAR	userID[10];
		CHAR	termGrpFlag;
		CHAR	termID[10];
		CHAR	systemName[10];
		CHAR	subSystemName[10];
	} access;
	struct	{
		CHAR	userID[10];
		CHAR	groupID[10];
	} userGrpBuf;
	struct	{
		CHAR	termID[10];
		CHAR	groupID[10];
	} termGrpBuf;
	int	i, j;
	SHORT	eof;
	SHORT	err;

	*result = 0;

	if ((err = OpenAccessDBF(&pAccessDB)) != 0)
		return err;

	if ((err = OpenUserGrpDBF(&pUserGrpDB)) != 0) {
		DBClose(pAccessDB);
		return err;
	}

	if ((err = OpenTermGrpDBF(&pTermGrpDB)) != 0) {
		DBClose(pAccessDB);
		DBClose(pUserGrpDB);
		return err;
	}

	StringToUpper(userID);
	memset(tmpUserID, ' ', 10);
	memcpy(tmpUserID, userID, min(strlen(userID), 10));

	StringToUpper(termID);
	memset(tmpTermID, ' ', 10);
	memcpy(tmpTermID, termID, min(strlen(termID), 10));

	memset(&access, ' ', sizeof(access));
	memcpy(access.systemName, systemName, min(strlen(systemName), 10));
	memcpy(access.subSystemName, subSystemName,
					min(strlen(subSystemName), 10));

	for (i = 0; ; i++) {
		if (i == 0) {
			access.userGrpFlag = ' ';
			memcpy(access.userID, tmpUserID, 10);
		} else {
			if (i == 1)
				DBSearch(pUserGrpDB, tmpUserID, 10);
			else
				DBSkip(pUserGrpDB, 1);

			DBEof(pUserGrpDB, &eof);
			if (eof)
				break;

			DBRead(pUserGrpDB, (CHAR *)&userGrpBuf);
			if (memcmp(userGrpBuf.userID, tmpUserID, 10) != 0)
				break;
				
			access.userGrpFlag = '*';
			memcpy(access.userID, userGrpBuf.groupID, 10);
		}

		for (j = 0; ; j++) {
			if (j == 0) {
				access.termGrpFlag = ' ';
				memcpy(access.termID, tmpTermID, 10);
			} else {
				if (j == 1)
					DBSearch(pTermGrpDB, tmpTermID, 10);
				else
					DBSkip(pTermGrpDB, 1);

				DBEof(pTermGrpDB, &eof);
				if (eof)
					break;

				DBRead(pTermGrpDB, (CHAR *)&termGrpBuf);
				if (memcmp(termGrpBuf.termID, tmpTermID, 10)
									!= 0)
					break;
				
				access.termGrpFlag = '*';
				memcpy(access.termID, termGrpBuf.groupID, 10);
			}
			DBSearch(pAccessDB, (CHAR *)&access, sizeof(access));
			DBEof(pAccessDB, &eof);
			if (eof == 0) {
				*result = 1;
				goto BigBreak;
			}
		}
	}

BigBreak:
	DBClose(pAccessDB);
	DBClose(pUserGrpDB);
	DBClose(pTermGrpDB);

	return 0;
}

/*=======================================================================
 |
 |		パスワード設定処理
 |
 |	VOID	PSGWSetPassowrd(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGWSetPassword(PSCB pSCB)
{
	struct	PRM_GW_SET_PASSWORD	*pParam;

	pParam = (struct PRM_GW_SET_PASSWORD *)pSCB->command.param;
	pSCB->response.status = _SetPassword(pParam->userID,
				pParam->oldPassword, pParam->newPassword,
				&pSCB->response.ret.sValue);

	pSCB->response.dataLength = 0;
}

static	SHORT	_SetPassword(CHAR *userID, CHAR *oldPassword,
					CHAR *newPassword, SHORT *result)
{
	PDB	pUserDB;
	CHAR	tmpUserID[11];
	CHAR	tmpPassword[11];
	SHORT	eof;
	SHORT	fieldType;
	SHORT	dataSize;
	SHORT	err;

	*result = 0;

	if ((err = OpenUserDBF(&pUserDB)) != 0)
		return err;

	StringToUpper(userID);
	memset(tmpUserID, ' ', 11);
	memcpy(tmpUserID + 1, userID, min(strlen(userID), 10));
	if ((err = DBSearch(pUserDB, tmpUserID, 11)) != 0) {
		DBClose(pUserDB);
		return err;
	}
	
	DBEof(pUserDB, &eof);
	if (eof != 0) {
		DBClose(pUserDB);
		return 0;
	}

	if ((err = DBGetRecord(pUserDB)) != 0) {
		DBClose(pUserDB);
		return err;
	}
	if ((err = DBGetField(pUserDB, "PASSWORD", tmpPassword, &fieldType,
							&dataSize)) != 0) {
		DBClose(pUserDB);
		return err;
	}

	if (strcmp(oldPassword, tmpPassword) != 0) {
		DBClose(pUserDB);
		return 0;
	}

	if ((err = DBSetField(pUserDB, "PASSWORD", newPassword)) != 0) {
		DBClose(pUserDB);
		return err;
	}

	if ((err = DBUpdRecord(pUserDB)) != 0) {
		DBClose(pUserDB);
		return err;
	}

	DBClose(pUserDB);

	*result = 1;

	return 0;
}

/*=======================================================================
 |
 |		端末チェック処理
 |
 |	VOID	PSGWCheckTerminal(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSGWCheckTerminal(PSCB pSCB)
{
	struct	PRM_GW_CHECK_TERMINAL	*pParam;

	pParam = (struct PRM_GW_CHECK_TERMINAL *)pSCB->command.param;
	pSCB->response.status = _CheckTerminal(pParam->termID, pSCB->lpData,
					&pSCB->response.ret.sValue);

	pSCB->response.dataLength = strlen(pSCB->lpData) + 1;
}

static	SHORT	_CheckTerminal(CHAR *termID, CHAR *userID, SHORT *result)
{
	PDB	pTermDB;
	CHAR	tmpTermID[11];
	CHAR	remote[2];
	SHORT	eof;
	SHORT	fieldType;
	SHORT	dataSize;
	SHORT	err;

	*result = 0;
	*userID = '\0';

	if ((err = OpenTermDBF(&pTermDB)) != 0)
		return err;

	StringToUpper(termID);
	memset(tmpTermID, ' ', 11);
	memcpy(tmpTermID + 1, termID, min(strlen(termID), 10));
	if ((err = DBSearch(pTermDB, tmpTermID, 11)) != 0) {
		DBClose(pTermDB);
		return err;
	}

	DBEof(pTermDB, &eof);
	if (eof != 0) {
		DBClose(pTermDB);
		return 0;
	}

	if ((err = DBGetRecord(pTermDB)) != 0) {
		DBClose(pTermDB);
		return err;
	}
	if ((err = DBGetField(pTermDB, "USERID", userID, &fieldType,
							&dataSize)) != 0) {
		DBClose(pTermDB);
		return err;
	}
	if ((err = DBGetField(pTermDB, "REMOTE", remote, &fieldType,
							&dataSize)) != 0) {
		DBClose(pTermDB);
		return err;
	}

	DBClose(pTermDB);

	*result = (remote[0] == '*') ? 2 : 1;

	return 0;
}
