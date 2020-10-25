/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: グループウェア処理
 *		ファイル名	: cgw.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcsclnt.h"

/*=======================================================================
 |
 |		ユーザパスワードチェック処理
 |
 |	SHORT	PSGWCheckUser(pCCB, userID, password)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*userID;	ユーザＩＤ
 |		CHAR	*password;	パスワード
 |
 |		SHORT	返値		０：ＮＧ　１：ＯＫ
 |
 =======================================================================*/
SHORT	PSENTRY	PSGWCheckUser(PCCB pCCB, CHAR *userID, CHAR *password)
{
	struct	PRM_GW_CHECK_USER	*pParam;
	SHORT	result = 0;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		if (strlen(userID) >= sizeof(pParam->userID)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(password) >= sizeof(pParam->password)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_GW_CHECK_USER *)pCCB->command.param;
		strcpy(pParam->userID, userID);
		strcpy(pParam->password, password);
		err = SendCommand(pCCB, FC_GW_CHECK_USER,
					sizeof(struct PRM_GW_CHECK_USER), 0);
		if (err == 0)
			result = pCCB->response.ret.sValue;
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return result;
}

/*=======================================================================
 |
 |		実行権チェック処理
 |
 |	SHORT	PSGWCheckAccess(pCCB, userID, termID, systemName,
 |							subSystemName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*userID;	ユーザＩＤ
 |		CHAR	*termID;	端末ＩＤ
 |		CHAR	*systemName;	システム名
 |		CHAR	*subSystemName;	サブシステム名
 |
 |		SHORT	返値		０：ＮＧ　１：ＯＫ
 |
 =======================================================================*/
SHORT	PSENTRY	PSGWCheckAccess(PCCB pCCB, CHAR *userID, CHAR *termID,
					CHAR *systemName, CHAR *subSystemName)
{
	struct	PRM_GW_CHECK_ACCESS	*pParam;
	SHORT	result = 0;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		if (strlen(userID) >= sizeof(pParam->userID)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(termID) >= sizeof(pParam->termID)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(systemName) >= sizeof(pParam->systemName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(subSystemName) >= sizeof(pParam->subSystemName)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_GW_CHECK_ACCESS *)pCCB->command.param;
		strcpy(pParam->userID, userID);
		strcpy(pParam->termID, termID);
		strcpy(pParam->systemName, systemName);
		strcpy(pParam->subSystemName, subSystemName);
		err = SendCommand(pCCB, FC_GW_CHECK_ACCESS,
					sizeof(struct PRM_GW_CHECK_ACCESS), 0);
		if (err == 0)
			result = pCCB->response.ret.sValue;
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return result;
}

/*=======================================================================
 |
 |		パスワード設定処理
 |
 |	SHORT	PSGWSetPassword(pCCB, userID, oldPassword, newPassword)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*userID;	ユーザＩＤ
 |		CHAR	*oldPassword;	旧パスワード
 |		CHAR	*newPassword;	新パスワード
 |
 |		SHORT	返値		０：ＮＧ　１：ＯＫ
 |
 =======================================================================*/
SHORT	PSENTRY	PSGWSetPassword(PCCB pCCB, CHAR *userID, CHAR *oldPassword,
							CHAR *newPassword)
{
	struct	PRM_GW_SET_PASSWORD	*pParam;
	SHORT	result = 0;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		if (strlen(userID) >= sizeof(pParam->userID)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(oldPassword) >= sizeof(pParam->oldPassword)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		if (strlen(newPassword) >= sizeof(pParam->newPassword)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_GW_SET_PASSWORD *)pCCB->command.param;
		strcpy(pParam->userID, userID);
		strcpy(pParam->oldPassword, oldPassword);
		strcpy(pParam->newPassword, newPassword);
		err = SendCommand(pCCB, FC_GW_SET_PASSWORD,
					sizeof(struct PRM_GW_SET_PASSWORD), 0);
		if (err == 0)
			result = pCCB->response.ret.sValue;
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return result;
}

/*=======================================================================
 |
 |		端末チェック処理
 |
 |	SHORT	PSGWCheckTerminal(pCCB, termID, userID)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*termID;	端末ＩＤ
 |		CHAR	*userID;	ユーザＩＤ（ユーザ固定モード）
 |
 |		SHORT	返値		０：未登録
 |					１：登録済
 |
 =======================================================================*/
SHORT	PSENTRY	PSGWCheckTerminal(PCCB pCCB, CHAR *termID, CHAR *userID)
{
	struct	PRM_GW_CHECK_TERMINAL	*pParam;
	SHORT	result = 0;
	SHORT	err;

	*userID = '\0';

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		if (strlen(termID) >= sizeof(pParam->termID)) {
			err = ERROR_CLIENT_PARAMETER;
			goto ret;
		}

		pParam = (struct PRM_GW_CHECK_TERMINAL *)pCCB->command.param;
		strcpy(pParam->termID, termID);
		pCCB->pOutData = userID;
		err = SendCommand(pCCB, FC_GW_CHECK_TERMINAL,
				sizeof(struct PRM_GW_CHECK_TERMINAL), 0);
		if (err == 0)
			result = pCCB->response.ret.sValue;
	}
ret:
	if (err != 0) {
		pCCB->errorCode = err;
	}

	return result;
}
