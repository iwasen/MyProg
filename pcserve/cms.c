/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側ネットワークメッセージ
 *							送信設定処理
 *		ファイル名	: cms.c
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
 |		ネットワークメッセージ送信要求
 |
 |	SHORT	PSMSSendRequest(pCCB, message, name, timeout)
 |
 |		PCCB	pCCB;		サーバ識別ポインタ
 |		CHAR	*message;	送信メッセージ
 |		CHAR	*name;		メッセージ宛先名
 |		SHORT	timeout;	メッセージ再送タイムアウト（分）
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSMSSendRequest(PCCB pCCB, CHAR *message, CHAR *name,
								SHORT timeout)
{
	struct	PRM_MS_SEND_REQUEST	*pParam;
	SHORT	err;

	if (strlen(name) > 20) {
		pCCB->errorCode = ERROR_MS_PARAMETER;
		return ERROR_MS_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_MS_SEND_REQUEST *)pCCB->command.param;
		strcpy(pParam->name, name);
		pParam->timeout = timeout;
		pCCB->pInData = message;
		err = SendCommand(pCCB, FC_MS_SEND_REQUEST,
					sizeof(struct PRM_MS_SEND_REQUEST),
					strlen(message) + 1);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		グループメンバー登録
 |
 |	SHORT	PSMSSetMember(pCCB, groupName, member)
 |
 |		PCCB	pCCB;		サーバ識別ポインタ
 |		CHAR	*groupName;	グループ名
 |		CHAR	*member;	登録するメンバー名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSMSSetMember(PCCB pCCB, CHAR *groupName, CHAR *member)
{
	struct	PRM_MS_SET_MEMBER	*pParam;
	SHORT	err;

	if (strlen(groupName) > 20) {
		pCCB->errorCode = ERROR_MS_PARAMETER;
		return ERROR_MS_PARAMETER;
	}

	if (strlen(member) > 20) {
		pCCB->errorCode = ERROR_MS_PARAMETER;
		return ERROR_MS_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_MS_SET_MEMBER *)pCCB->command.param;
		strcpy(pParam->groupName, groupName);
		strcpy(pParam->member, member);
		err = SendCommand(pCCB, FC_MS_SET_MEMBER,
					sizeof(struct PRM_MS_SET_MEMBER), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		グループメンバー削除
 |
 |	SHORT	PSMSDeleteMember(pCCB, groupName, member)
 |
 |		PCCB	pCCB;		サーバ識別ポインタ
 |		CHAR	*groupName;	グループ名
 |		CHAR	*member;	登録するメンバー名
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSMSDeleteMember(PCCB pCCB, CHAR *groupName, CHAR *member)
{
	struct	PRM_MS_DELETE_MEMBER	*pParam;
	SHORT	err;

	if (strlen(groupName) > 20) {
		pCCB->errorCode = ERROR_MS_PARAMETER;
		return ERROR_MS_PARAMETER;
	}

	if (strlen(member) > 20) {
		pCCB->errorCode = ERROR_MS_PARAMETER;
		return ERROR_MS_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_MS_DELETE_MEMBER *)pCCB->command.param;
		strcpy(pParam->groupName, groupName);
		strcpy(pParam->member, member);
		err = SendCommand(pCCB, FC_MS_DELETE_MEMBER,
				sizeof(struct PRM_MS_DELETE_MEMBER), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		グループメンバー取得
 |
 |	SHORT	PSMSGetMember(pCCB, groupName, member, size, numMember)
 |
 |		PCCB	pCCB;		サーバ識別ポインタ
 |		CHAR	*groupName;	グループ名
 |		CHAR	*member;	グループ内のメンバーを格納するエリア
 |		SHORT	size;		メンバー名を格納するエリアのサイズ
 |		SHORT	*numMember;	メンバー数
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSMSGetMember(PCCB pCCB, CHAR *groupName, CHAR *member,
						SHORT size, SHORT *numMember)
{
	struct	PRM_MS_GET_MEMBER	*pParam;
	SHORT	err;

	if (strlen(groupName) > 20) {
		pCCB->errorCode = ERROR_MS_PARAMETER;
		return ERROR_MS_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = ERROR_CLIENT_NOSUPPORT;
	} else {
		pParam = (struct PRM_MS_GET_MEMBER *)pCCB->command.param;
		strcpy(pParam->groupName, groupName);
		pParam->size = size;
		pCCB->pOutData = member;
		err = SendCommand(pCCB, FC_MS_GET_MEMBER,
				sizeof(struct PRM_MS_GET_MEMBER), 0);
		*numMember = pCCB->response.ret.sValue;
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}
