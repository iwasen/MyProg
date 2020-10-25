/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �N���C�A���g���l�b�g���[�N���b�Z�[�W
 *							���M�ݒ菈��
 *		�t�@�C����	: cms.c
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
 |		�l�b�g���[�N���b�Z�[�W���M�v��
 |
 |	SHORT	PSMSSendRequest(pCCB, message, name, timeout)
 |
 |		PCCB	pCCB;		�T�[�o���ʃ|�C���^
 |		CHAR	*message;	���M���b�Z�[�W
 |		CHAR	*name;		���b�Z�[�W���於
 |		SHORT	timeout;	���b�Z�[�W�đ��^�C���A�E�g�i���j
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
 |		�O���[�v�����o�[�o�^
 |
 |	SHORT	PSMSSetMember(pCCB, groupName, member)
 |
 |		PCCB	pCCB;		�T�[�o���ʃ|�C���^
 |		CHAR	*groupName;	�O���[�v��
 |		CHAR	*member;	�o�^���郁���o�[��
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
 |		�O���[�v�����o�[�폜
 |
 |	SHORT	PSMSDeleteMember(pCCB, groupName, member)
 |
 |		PCCB	pCCB;		�T�[�o���ʃ|�C���^
 |		CHAR	*groupName;	�O���[�v��
 |		CHAR	*member;	�o�^���郁���o�[��
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
 |		�O���[�v�����o�[�擾
 |
 |	SHORT	PSMSGetMember(pCCB, groupName, member, size, numMember)
 |
 |		PCCB	pCCB;		�T�[�o���ʃ|�C���^
 |		CHAR	*groupName;	�O���[�v��
 |		CHAR	*member;	�O���[�v���̃����o�[���i�[����G���A
 |		SHORT	size;		�����o�[�����i�[����G���A�̃T�C�Y
 |		SHORT	*numMember;	�����o�[��
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
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
