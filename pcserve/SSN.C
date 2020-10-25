/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: サーバ側シーケンス番号設定処理
 *		ファイル名	: ssn.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		PSSNCreate() 関数実行処理
 |
 |	VOID	ExecPSSNCreate(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSSNCreate(PSCB pSCB)
{
	struct	PRM_SN_CREATE	*pParam;
	SHORT	err;

	pParam = (struct PRM_SN_CREATE *)pSCB->command.param;
	pParam->seqNoID[sizeof(pParam->seqNoID) - 1] = '\0';
	pParam->comment[sizeof(pParam->comment) - 1] = '\0';

	ENTER_CRITICAL_SECTION(&csSN);

	err = SNCreate(pParam->seqNoID, pParam->comment, pParam->startNo,
			pParam->endNo, pParam->step, pParam->column);

	LEAVE_CRITICAL_SECTION(&csSN);

	pSCB->response.status = err;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSSNGetNo() 関数実行処理
 |
 |	VOID	ExecPSSNGetNo(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSSNGetNo(PSCB pSCB)
{
	struct	PRM_SN_GETNO	*pParam;
	SHORT	err;

	pParam = (struct PRM_SN_GETNO *)pSCB->command.param;
	pParam->seqNoID[sizeof(pParam->seqNoID) - 1] = '\0';

	ENTER_CRITICAL_SECTION(&csSN);

	err = SNGetNo(pParam->seqNoID, pSCB->lpData);

	LEAVE_CRITICAL_SECTION(&csSN);

	pSCB->response.status = err;
	pSCB->response.dataLength = (err == 0) ? strlen(pSCB->lpData) + 1 : 0;
}

/*=======================================================================
 |
 |		PSSNSetNo() 関数実行処理
 |
 |	VOID	ExecPSSNSetNo(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSSNSetNo(PSCB pSCB)
{
	struct	PRM_SN_SETNO	*pParam;
	SHORT	err;

	pParam = (struct PRM_SN_SETNO *)pSCB->command.param;
	pParam->seqNoID[sizeof(pParam->seqNoID) - 1] = '\0';

	ENTER_CRITICAL_SECTION(&csSN);

	err = SNSetNo(pParam->seqNoID, pParam->seqNo);

	LEAVE_CRITICAL_SECTION(&csSN);

	pSCB->response.status = err;
	pSCB->response.dataLength = 0;
}

/*=======================================================================
 |
 |		PSSNDelete() 関数実行処理
 |
 |	VOID	ExecPSSNDelete(pSCB)
 |
 |		PSCB	pSCB;		サーバ制御ブロックポインタ
 |
 =======================================================================*/
VOID	ExecPSSNDelete(PSCB pSCB)
{
	struct	PRM_SN_DELETE	*pParam;
	SHORT	err;

	pParam = (struct PRM_SN_DELETE *)pSCB->command.param;
	pParam->seqNoID[sizeof(pParam->seqNoID) - 1] = '\0';

	ENTER_CRITICAL_SECTION(&csSN);

	err = SNDelete(pParam->seqNoID);

	LEAVE_CRITICAL_SECTION(&csSN);

	pSCB->response.status = err;
	pSCB->response.dataLength = 0;
}
