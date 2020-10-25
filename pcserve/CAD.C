/************************************************************************
 *
 *			�o�b�|�r�d�q�u�d
 *
 *		����		: �N���C�A���g���T�[�o�Ǘ��֐�
 *		�t�@�C����	: cad.c
 *		�쐬��		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcsclnt.h"
#include "pcscheck.h"

/*=======================================================================
 |
 |		�T�[�o��~
 |
 |	SHORT	PSADStop(pCCB)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADStop(PCCB pCCB)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else
		err = SendCommand(pCCB, FC_AD_STOP, 0, 0);

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		���O�C����񃊃X�g�擾
 |
 |	SHORT	PSADLogin(pCCB, pLogin, nLogin)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		LOGIN	*pLogin;	���O�C����񃊃X�g
 |		SHORT	*nLogin;	���O�C����
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADLogin(PCCB pCCB, LOGIN *pLogin, SHORT *nLogin)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pOutData = pLogin;
		err = SendCommand(pCCB, FC_AD_LOGIN, 0, 0);
	}

	if (err != 0)
		pCCB->errorCode = err;
	else
		*nLogin = pCCB->response.ret.sValue;

	return err;
}

/*=======================================================================
 |
 |		�I�[�v���t�@�C�����擾
 |
 |	SHORT	PSADOpenFile(pCCB, clientNo, pOpenFile, nOpenFile)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		USHORT	clientNo;	�N���C�A���g�ԍ�
 |		CHAR	*pOpenFile;	�I�[�v���t�@�C�������X�g
 |		SHORT	*nOpenFile;	�I�[�v���t�@�C����
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADOpenFile(PCCB pCCB, USHORT clientNo, CHAR *pOpenFile,
							SHORT *nOpenFile)
{
	struct	PRM_AD_OPENFILE	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pParam = (struct PRM_AD_OPENFILE *)pCCB->command.param;
		pParam->clientNo = clientNo;

		pCCB->pOutData = pOpenFile;
		err = SendCommand(pCCB, FC_AD_OPENFILE,
				sizeof(struct PRM_AD_OPENFILE), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;
	else
		*nOpenFile = pCCB->response.ret.sValue;

	return err;
}

/*=======================================================================
 |
 |		�f�o�b�O���擾
 |
 |	SHORT	PSADDebug(pCCB, code, pInData, inDataLength, pOutData)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		SHORT	code;		�f�o�b�O���R�[�h
 |		VOID	*pInData;	���̓f�[�^
 |		SHORT	inDataLength;	���̓f�[�^��
 |		VOID	*pOutData;	�o�̓f�[�^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADDebug(PCCB pCCB, SHORT code, VOID *pInData,
					SHORT inDataLength, VOID *pOutData)
{
	struct	PRM_AD_DEBUG	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pParam = (struct PRM_AD_DEBUG *)pCCB->command.param;
		pParam->code = code;
		pCCB->pInData = pInData;
		pCCB->pOutData = pOutData;
		err = SendCommand(pCCB, FC_AD_DEBUG,
				sizeof(struct PRM_AD_DEBUG), inDataLength);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		���v���擾
 |
 |	SHORT	PSADGetStatistic(pCCB, statistics)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		PSTATISTICS	pStatistics;	���v���
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADGetStatistics(PCCB pCCB, PSTATISTICS pStatistics)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pOutData = pStatistics;
		err = SendCommand(pCCB, FC_AD_GETSTATISTICS, 0, 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		���v���N���A
 |
 |	SHORT	PSADClrStatistic(pCCB)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADClrStatistics(PCCB pCCB)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else
		err = SendCommand(pCCB, FC_AD_CLRSTATISTICS, 0, 0);

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		��d���X�e�[�^�X�擾
 |
 |	SHORT	PSADDuplexStatus(pCCB, status)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		SHORT	*status;	��d���X�e�[�^�X
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADDuplexStatus(PCCB pCCB, SHORT *status)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else
		err = SendCommand(pCCB, FC_AD_DUPLEXSTATUS, 0, 0);

	if (err != 0)
		pCCB->errorCode = err;
	else
		*status = pCCB->response.ret.sValue;

	return err;
}

/*=======================================================================
 |
 |		��d���t�@�C������
 |
 |	SHORT	PSADDuplexSync(pCCB, fileName)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*fileName;	�t�@�C����
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADDuplexSync(PCCB pCCB, CHAR *fileName)
{
	struct	PRM_AD_DUPLEXSYNC	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pParam = (struct PRM_AD_DUPLEXSYNC *)pCCB->command.param;
		strcpy(pParam->fileName, fileName);

		err = SendCommand(pCCB, FC_AD_DUPLEXSYNC,
				sizeof(struct PRM_AD_DUPLEXSYNC), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		��d�����擾
 |
 |	SHORT	PSADDuplexGetInfo(pCCB, info)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		PDUPLEXINFO info;	��d�����
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADDuplexGetInfo(PCCB pCCB,  PDUPLEXINFO info)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pOutData = info;
		err = SendCommand(pCCB, FC_AD_DUPLEXGETINFO, 0, 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		��d�����ݒ�
 |
 |	SHORT	PSADDuplexSetInfo(pCCB, info)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		PDUPLEXINFO info;	��d�����
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADDuplexSetInfo(PCCB pCCB,  PDUPLEXINFO info)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pInData = info;
		err = SendCommand(pCCB, FC_AD_DUPLEXSETINFO, 0,
							sizeof(DUPLEXINFO));
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		���U�t�@�C������
 |
 |	SHORT	PSADMultiSync(pCCB, fileName)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		CHAR	*fileName;	�t�@�C����
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADMultiSync(PCCB pCCB, CHAR *fileName)
{
	struct	PRM_AD_MULTISYNC	*pParam;
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pParam = (struct PRM_AD_MULTISYNC *)pCCB->command.param;
		strcpy(pParam->fileName, fileName);

		err = SendCommand(pCCB, FC_AD_MULTISYNC,
				sizeof(struct PRM_AD_MULTISYNC), 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		INI �t�@�C���f�[�^�擾
 |
 |	SHORT	PSADGetIniData(pCCB, iniData)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		INIDATA	*iniData;	INI �t�@�C���f�[�^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADGetIniData(PCCB pCCB, INIDATA *iniData)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pOutData = iniData;
		err = SendCommand(pCCB, FC_AD_GETINIDATA, 0, 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		INI �t�@�C���f�[�^�ݒ�
 |
 |	SHORT	PSADSetIniData(pCCB, iniData)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		INIDATA	*iniData;	INI �t�@�C���f�[�^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADSetIniData(PCCB pCCB, INIDATA *iniData)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pInData = iniData;
		err = SendCommand(pCCB, FC_AD_SETINIDATA, 0,
							sizeof(INIDATA));
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		���C�Z���X�f�[�^�擾
 |
 |	SHORT	PSADGetLicenseData(pCCB, licenseData)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		LICENSE	*licenseData;	���C�Z���X�f�[�^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADGetLicenseData(PCCB pCCB, LICENSE *licenseData)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pOutData = licenseData;
		err = SendCommand(pCCB, FC_AD_GETLICENSEDATA, 0, 0);
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}

/*=======================================================================
 |
 |		���C�Z���X�f�[�^�ݒ�
 |
 |	SHORT	PSADSetLicenseData(pCCB, licenseData)
 |
 |		PCCB	pCCB;		�N���C�A���g����u���b�N�|�C���^
 |		LICENSE	*licenseData;	���C�Z���X�t�@�C���f�[�^
 |
 |		SHORT	�Ԓl		�G���[�R�[�h
 |
 =======================================================================*/
SHORT	PSENTRY	PSADSetLicenseData(PCCB pCCB, LICENSE *licenseData)
{
	SHORT	err;

	if (pCCB->chType == CHT_LOCAL)
		err = ERROR_CLIENT_NOSUPPORT;
	else {
		pCCB->pInData = licenseData;
		err = SendCommand(pCCB, FC_AD_SETLICENSEDATA, 0,
							sizeof(LICENSE));
	}

	if (err != 0)
		pCCB->errorCode = err;

	return err;
}