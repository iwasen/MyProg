/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側シーケンス番号設定処理
 *		ファイル名	: csn.c
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
 |		シーケンス番号作成
 |
 |	SHORT	PSSNCreate(pCCB, seqNoID, comment, startNo, endNo, step,
 |								column)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |		CHAR	*comment;	コメント（３０桁以内）
 |		LONG	startNo;	初期値
 |		LONG	endNo;		最終値
 |		LONG	step;		増分
 |		SHORT	column;		桁数（１～９）
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSSNCreate(PCCB pCCB, CHAR *seqNoID, CHAR *comment,
			LONG startNo, LONG endNo, LONG step, SHORT column)
{
	struct	PRM_SN_CREATE	*pParam;
	SHORT	err;

	if (strlen(seqNoID) > 8) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_SN_PARAMETER;
	}

	if (strlen(comment) > 30) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_SN_PARAMETER;
	}

	if (column < 1 || column > 9) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_SN_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = SNCreate(seqNoID, comment, startNo, endNo, step, column);
	} else {
		pParam = (struct PRM_SN_CREATE *)pCCB->command.param;
		strcpy(pParam->seqNoID, seqNoID);
		strcpy(pParam->comment, comment);
		pParam->startNo = startNo;
		pParam->endNo = endNo;
		pParam->step = step;
		pParam->column = column;
		err = SendCommand(pCCB, FC_SN_CREATE,
					sizeof(struct PRM_SN_CREATE), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		シーケンス番号取得
 |
 |	SHORT	PSSNGetNo(pCCB, seqNoID, seqNo)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |		CHAR	*seqNo;		シーケンス番号
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSSNGetNo(PCCB pCCB, CHAR *seqNoID, CHAR *seqNo)
{
	struct	PRM_SN_GETNO	*pParam;
	SHORT	err;

	if (strlen(seqNoID) > 8) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_SN_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = SNGetNo(seqNoID, seqNo);
	} else {
		pParam = (struct PRM_SN_GETNO *)pCCB->command.param;
		strcpy(pParam->seqNoID, seqNoID);
		pCCB->pOutData = seqNo;
		err = SendCommand(pCCB, FC_SN_GETNO,
					sizeof(struct PRM_SN_GETNO), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		シーケンス番号設定
 |
 |	SHORT	PSSNSetNo(pCCB, seqNoID, seqNo)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |		CHAR	*seqNo;		シーケンス番号
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSSNSetNo(PCCB pCCB, CHAR *seqNoID, LONG seqNo)
{
	struct	PRM_SN_SETNO	*pParam;
	SHORT	err;

	if (strlen(seqNoID) > 8) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_SN_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = SNSetNo(seqNoID, seqNo);
	} else {
		pParam = (struct PRM_SN_SETNO *)pCCB->command.param;
		strcpy(pParam->seqNoID, seqNoID);
		pParam->seqNo = seqNo;
		err = SendCommand(pCCB, FC_SN_SETNO,
					sizeof(struct PRM_SN_SETNO), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		シーケンス番号削除
 |
 |	SHORT	PSSNDelete(pCCB, seqNoID)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	PSENTRY	PSSNDelete(PCCB pCCB, CHAR *seqNoID)
{
	struct	PRM_SN_DELETE	*pParam;
	SHORT	err;

	if (strlen(seqNoID) > 8) {
		pCCB->errorCode = ERROR_SN_PARAMETER;
		return ERROR_SN_PARAMETER;
	}

	if (pCCB->chType == CHT_LOCAL) {
		err = SNDelete(seqNoID);
	} else {
		pParam = (struct PRM_SN_DELETE *)pCCB->command.param;
		strcpy(pParam->seqNoID, seqNoID);
		err = SendCommand(pCCB, FC_SN_DELETE,
					sizeof(struct PRM_SN_DELETE), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}
