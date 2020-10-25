/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �N���C�A���g���V�[�P���X�ԍ��ݒ菈��
 *		�t�@�C����	: csn.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcsclnt.h"

/*=======================================================================
 |
 |		�V�[�P���X�ԍ��쐬
 |
 |	SHORT	PSSNCreate(pCCB, seqNoID, comment, startNo, endNo, step,
 |								column)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*seqNoID;	�V�[�P���X�ԍ��h�c�i�W���ȓ��j
 |		CHAR	*comment;	�R�����g�i�R�O���ȓ��j
 |		LONG	startNo;	�����l
 |		LONG	endNo;		�ŏI�l
 |		LONG	step;		����
 |		SHORT	column;		�����i�P�`�X�j
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
 |		�V�[�P���X�ԍ��擾
 |
 |	SHORT	PSSNGetNo(pCCB, seqNoID, seqNo)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*seqNoID;	�V�[�P���X�ԍ��h�c�i�W���ȓ��j
 |		CHAR	*seqNo;		�V�[�P���X�ԍ�
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
 |		�V�[�P���X�ԍ��ݒ�
 |
 |	SHORT	PSSNSetNo(pCCB, seqNoID, seqNo)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*seqNoID;	�V�[�P���X�ԍ��h�c�i�W���ȓ��j
 |		CHAR	*seqNo;		�V�[�P���X�ԍ�
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
 |		�V�[�P���X�ԍ��폜
 |
 |	SHORT	PSSNDelete(pCCB, seqNoID)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*seqNoID;	�V�[�P���X�ԍ��h�c�i�W���ȓ��j
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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