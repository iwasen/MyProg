/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: クライアント側サーバ管理関数
 *		ファイル名	: cad.c
 *		作成者		: s.aizawa
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
 |		サーバ停止
 |
 |	SHORT	PSADStop(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
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
 |		ログイン情報リスト取得
 |
 |	SHORT	PSADLogin(pCCB, pLogin, nLogin)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		LOGIN	*pLogin;	ログイン情報リスト
 |		SHORT	*nLogin;	ログイン数
 |
 |		SHORT	返値		エラーコード
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
 |		オープンファイル情報取得
 |
 |	SHORT	PSADOpenFile(pCCB, clientNo, pOpenFile, nOpenFile)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		USHORT	clientNo;	クライアント番号
 |		CHAR	*pOpenFile;	オープンファイル名リスト
 |		SHORT	*nOpenFile;	オープンファイル数
 |
 |		SHORT	返値		エラーコード
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
 |		デバッグ情報取得
 |
 |	SHORT	PSADDebug(pCCB, code, pInData, inDataLength, pOutData)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		SHORT	code;		デバッグ情報コード
 |		VOID	*pInData;	入力データ
 |		SHORT	inDataLength;	入力データ長
 |		VOID	*pOutData;	出力データ
 |
 |		SHORT	返値		エラーコード
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
 |		統計情報取得
 |
 |	SHORT	PSADGetStatistic(pCCB, statistics)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		PSTATISTICS	pStatistics;	統計情報
 |
 |		SHORT	返値		エラーコード
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
 |		統計情報クリア
 |
 |	SHORT	PSADClrStatistic(pCCB)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |
 |		SHORT	返値		エラーコード
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
 |		二重化ステータス取得
 |
 |	SHORT	PSADDuplexStatus(pCCB, status)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		SHORT	*status;	二重化ステータス
 |
 |		SHORT	返値		エラーコード
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
 |		二重化ファイル同期
 |
 |	SHORT	PSADDuplexSync(pCCB, fileName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*fileName;	ファイル名
 |
 |		SHORT	返値		エラーコード
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
 |		二重化情報取得
 |
 |	SHORT	PSADDuplexGetInfo(pCCB, info)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		PDUPLEXINFO info;	二重化情報
 |
 |		SHORT	返値		エラーコード
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
 |		二重化情報設定
 |
 |	SHORT	PSADDuplexSetInfo(pCCB, info)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		PDUPLEXINFO info;	二重化情報
 |
 |		SHORT	返値		エラーコード
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
 |		分散ファイル同期
 |
 |	SHORT	PSADMultiSync(pCCB, fileName)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		CHAR	*fileName;	ファイル名
 |
 |		SHORT	返値		エラーコード
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
 |		INI ファイルデータ取得
 |
 |	SHORT	PSADGetIniData(pCCB, iniData)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		INIDATA	*iniData;	INI ファイルデータ
 |
 |		SHORT	返値		エラーコード
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
 |		INI ファイルデータ設定
 |
 |	SHORT	PSADSetIniData(pCCB, iniData)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		INIDATA	*iniData;	INI ファイルデータ
 |
 |		SHORT	返値		エラーコード
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
 |		ライセンスデータ取得
 |
 |	SHORT	PSADGetLicenseData(pCCB, licenseData)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		LICENSE	*licenseData;	ライセンスデータ
 |
 |		SHORT	返値		エラーコード
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
 |		ライセンスデータ設定
 |
 |	SHORT	PSADSetLicenseData(pCCB, licenseData)
 |
 |		PCCB	pCCB;		クライアント制御ブロックポインタ
 |		LICENSE	*licenseData;	ライセンスファイルデータ
 |
 |		SHORT	返値		エラーコード
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
