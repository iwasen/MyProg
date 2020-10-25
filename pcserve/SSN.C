/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �T�[�o���V�[�P���X�ԍ��ݒ菈��
 *		�t�@�C����	: ssn.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcserve.h"

/*=======================================================================
 |
 |		PSSNCreate() �֐����s����
 |
 |	VOID	ExecPSSNCreate(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		PSSNGetNo() �֐����s����
 |
 |	VOID	ExecPSSNGetNo(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		PSSNSetNo() �֐����s����
 |
 |	VOID	ExecPSSNSetNo(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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
 |		PSSNDelete() �֐����s����
 |
 |	VOID	ExecPSSNDelete(pSCB)
 |
 |		PSCB	pSCB;		�T�[�o����u���b�N�|�C���^
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