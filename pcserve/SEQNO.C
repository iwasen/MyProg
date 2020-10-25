/************************************************************************
 *
 *			ＰＣ－ＳＥＲＶＥ
 *
 *		名称		: シーケンス番号設定処理
 *		ファイル名	: seqno.c
 *		作成者		: s.aizawa
 *
 ************************************************************************/

#include "pcsos.h"
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "pcsdef.h"
#include "pcsdb.h"
#include "pcssn.h"

static	SHORT	OpenSNDBF(PDB *);

/*=======================================================================
 |
 |		シーケンス番号作成
 |
 |	SHORT	SNCreate(seqNoID, comment, startNo, endNo, step, column)
 |
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
SHORT	SNCreate(CHAR *seqNoID, CHAR *comment, LONG startNo, LONG endNo,
						LONG step, SHORT column)
{
	PDB	dbp;
	CHAR	buf[LEN_SEQNOID];
	SHORT	find;
	SHORT	err;

	if ((err = OpenSNDBF(&dbp)) != 0)
		return err;

	memset(buf, ' ', sizeof(buf));
	memcpy(buf, seqNoID, strlen(seqNoID));
	if ((err = DBSearch2(dbp, buf, sizeof(buf), &find)) != 0) {
		DBClose(dbp);
		return err;
	}

	if (find == 1) {
		DBClose(dbp);
		return ERROR_SN_EXIST;
	}

	DBClrRecord(dbp);

	DBSetField(dbp, SN_SEQNOID, seqNoID);
	DBSetField(dbp, SN_COMMENT, comment);
	DBSetField(dbp, SN_STARTNO, &startNo);
	DBSetField(dbp, SN_ENDNO, &endNo);
	DBSetField(dbp, SN_STEP, &step);
	sprintf(buf, "%d", column);
	DBSetField(dbp, SN_COLUMN, buf);
	DBSetField(dbp, SN_SEQNO, &startNo);

	if ((err = DBAddRecord(dbp)) != 0) {
		DBClose(dbp);
		return err;
	}

	DBClose(dbp);

	return 0;
}

/*=======================================================================
 |
 |		シーケンス番号取得
 |
 |	SHORT	SNGetNo(seqNoID, seqNo)
 |
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |		CHAR	*seqNo;		シーケンス番号
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	SNGetNo(CHAR *seqNoID, CHAR *seqNo)
{
	PDB	dbp;
	CHAR	buf[LEN_SEQNOID];
	SHORT	find;
	LONG	lSeqNo, startNo, endNo, step;
	SHORT	column;
	SHORT	fieldType, dataSize;
	SHORT	err;

	if ((err = OpenSNDBF(&dbp)) != 0)
		return err;

	memset(buf, ' ', sizeof(buf));
	memcpy(buf, seqNoID, strlen(seqNoID));
	if ((err = DBSearch2(dbp, buf, sizeof(buf), &find)) != 0) {
		DBClose(dbp);
		return err;
	}

	if (find != 1) {
		DBClose(dbp);
		return ERROR_SN_NOTFOUND;
	}

	if ((err = DBGetRecord(dbp)) != 0) {
		DBClose(dbp);
		return err;
	}

	DBGetField(dbp, SN_SEQNO, &lSeqNo, &fieldType, &dataSize);
	DBGetField(dbp, SN_ENDNO, &endNo, &fieldType, &dataSize);
	DBGetField(dbp, SN_STEP, &step, &fieldType, &dataSize);
	DBGetField(dbp, SN_COLUMN, buf, &fieldType, &dataSize);
	column = atoi(buf);

	sprintf(buf, "%%0%dlu", column);
	sprintf(seqNo, buf, lSeqNo);

	lSeqNo += step;
	if (lSeqNo > endNo) {
		DBGetField(dbp, SN_STARTNO, &startNo, &fieldType, &dataSize);
		lSeqNo = startNo;
	}
	DBSetField(dbp, SN_SEQNO, &lSeqNo);

	if ((err = DBUpdRecord(dbp)) != 0) {
		DBClose(dbp);
		return err;
	}

	DBClose(dbp);

	return 0;
}

/*=======================================================================
 |
 |		シーケンス番号設定
 |
 |	SHORT	SNSetNo(seqNoID, seqNo)
 |
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |		CHAR	*seqNo;		シーケンス番号
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	SNSetNo(CHAR *seqNoID, LONG seqNo)
{
	PDB	dbp;
	CHAR	buf[LEN_SEQNOID];
	SHORT	find;
	SHORT	fieldType, dataSize;
	SHORT	err;

	if ((err = OpenSNDBF(&dbp)) != 0)
		return err;

	memset(buf, ' ', sizeof(buf));
	memcpy(buf, seqNoID, strlen(seqNoID));
	if ((err = DBSearch2(dbp, buf, sizeof(buf), &find)) != 0) {
		DBClose(dbp);
		return err;
	}

	if (find != 1) {
		DBClose(dbp);
		return ERROR_SN_NOTFOUND;
	}

	if ((err = DBGetRecord(dbp)) != 0) {
		DBClose(dbp);
		return err;
	}

	if (seqNo == -1L)
		DBGetField(dbp, SN_STARTNO, &seqNo, &fieldType, &dataSize);

	DBSetField(dbp, SN_SEQNO, &seqNo);

	if ((err = DBUpdRecord(dbp)) != 0) {
		DBClose(dbp);
		return err;
	}

	DBClose(dbp);

	return 0;
}

/*=======================================================================
 |
 |		シーケンス番号削除
 |
 |	SHORT	SNDelete(seqNoID)
 |
 |		CHAR	*seqNoID;	シーケンス番号ＩＤ（８桁以内）
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
SHORT	SNDelete(CHAR *seqNoID)
{
	PDB	dbp;
	CHAR	buf[LEN_SEQNOID];
	SHORT	find;
	SHORT	err;

	if ((err = OpenSNDBF(&dbp)) != 0)
		return err;

	memset(buf, ' ', sizeof(buf));
	memcpy(buf, seqNoID, strlen(seqNoID));
	if ((err = DBSearch2(dbp, buf, sizeof(buf), &find)) != 0) {
		DBClose(dbp);
		return err;
	}

	if (find != 1) {
		DBClose(dbp);
		return ERROR_SN_NOTFOUND;
	}

	if ((err = DBDelete2(dbp)) != 0) {
		DBClose(dbp);
		return err;
	}

	DBClose(dbp);

	return 0;
}

/*=======================================================================
 |
 |		シーケンス番号管理ファイルオープン
 |
 |	SHORT	OpenSNDBF(dbpp)
 |
 |		PDB	*dbpp;		ＤＢポインタ
 |
 |		SHORT	返値		エラーコード
 |
 =======================================================================*/
static	SHORT	OpenSNDBF(PDB *dbpp)
{
	SHORT	idxNo;
	SHORT	err;
	static	DBF_I	fieldData[] = {
		{SN_SEQNOID, 'C', LEN_SEQNOID, 0},
		{SN_COMMENT, 'C', LEN_COMMENT, 0},
		{SN_STARTNO, 'I', LEN_STARTNO, 0},
		{SN_ENDNO,   'I', LEN_ENDNO,   0},
		{SN_STEP,    'I', LEN_STEP,    0},
		{SN_COLUMN,  'C', LEN_COLUMN,  0},
		{SN_SEQNO,   'I', LEN_SEQNO,   0}
	};

	if (DBOpen(FN_SNDBF, dbpp, PERMISSION_ALL) != 0) {
		if ((err = DBCreate(FN_SNDBF, fieldData, sizeof(fieldData)/sizeof(DBF_I), dbpp, PERMISSION_ALL)) != 0)
			return err;
	} else {
		if (DBIndex(*dbpp, FN_SNNDX, &idxNo) == 0)
			return 0;
	}

	if ((err = DBIdxCreate(*dbpp, FN_SNNDX, SN_SEQNOID, 1, &idxNo)) != 0) {
		DBClose(*dbpp);
		return err;
	}

	return 0;
}
