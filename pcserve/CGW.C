/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �O���[�v�E�F�A����
 *		�t�@�C����	: cgw.c
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
 |		���[�U�p�X���[�h�`�F�b�N����
 |
 |	SHORT	PSGWCheckUser(pCCB, userID, password)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*userID;	���[�U�h�c
 |		CHAR	*password;	�p�X���[�h
 |
 |		SHORT	�Ԓl		�O�F�m�f�@�P�F�n�j
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
 |		���s���`�F�b�N����
 |
 |	SHORT	PSGWCheckAccess(pCCB, userID, termID, systemName,
 |							subSystemName)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*userID;	���[�U�h�c
 |		CHAR	*termID;	�[���h�c
 |		CHAR	*systemName;	�V�X�e����
 |		CHAR	*subSystemName;	�T�u�V�X�e����
 |
 |		SHORT	�Ԓl		�O�F�m�f�@�P�F�n�j
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
 |		�p�X���[�h�ݒ菈��
 |
 |	SHORT	PSGWSetPassword(pCCB, userID, oldPassword, newPassword)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*userID;	���[�U�h�c
 |		CHAR	*oldPassword;	���p�X���[�h
 |		CHAR	*newPassword;	�V�p�X���[�h
 |
 |		SHORT	�Ԓl		�O�F�m�f�@�P�F�n�j
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
 |		�[���`�F�b�N����
 |
 |	SHORT	PSGWCheckTerminal(pCCB, termID, userID)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*termID;	�[���h�c
 |		CHAR	*userID;	���[�U�h�c�i���[�U�Œ胂�[�h�j
 |
 |		SHORT	�Ԓl		�O�F���o�^
 |					�P�F�o�^��
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