/*	FILE DESCROPTION
 *
 *		FILE NAME	: d3istb.c
 *		ROUTINE		: 表形式データフォーマットチェック
 *		REVISION	:
 *		REMARKS		: 88.05.19	s.aizawa
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();

static	char	sccsid[]="@(#)d3istb.c 1.3 88/06/12 15:02:48";

/*	FUNCTION DESCRIPTION
 *		
 *		FUNC.NAME	: d3istb()
 *		INPUT		: dsp = data set pointer
 *		OUTPUT		: return value = 0 表データセット(id = 254)
 *				:              = 1 ＮＵＬＬデータセット
 *				:              = 2 表データセットとみなせる
 *				:                                (id != 254)
 *				:              = 3 表データセットではない
 *		REMARKS		:
 */

d3istb(dsp)
DSET	*dsp;
{
	struct	dsm	*datp, *dimp, *datap, *labp, *axip, *labsp;
	int	ncol = 0;

	/* 構造体ＩＤチェック */
	if (dsp->ds_entry->ptr.d[1].type == DSID_TBL)
		return(0);

	/* データ部チェック */
	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* "Dat"はあるか? */
		return(3);

	switch (datp->type) {			/* 属性チェック */
	case DS_N:				/* NULL */
		break;
	case DS_STR:				/* 構造体 */
		if (datp->length != 2)		/* "Dat"の要素数は２か */
			return(3);

		if ((dimp = d3gcmp(datp, "Dim")) == NULL) /* "Dim"はあるか ? */
			return(3);

		if (dimp->type != DS_I)			/* 整数型 ? */
			return(3);

		if (dimp->length != 2)			/* ２次元 ? */
			return(3);

		ncol = dimp->ptr.i[1];			/* 列数取り出し */

		if ((datap = d3gcmp(datp, "Data")) == NULL)/* "Data"はあるか ?*/
			return(3);

		if (datap->type != DS_I)		/* 整数型 ? */
			return(3);
		break;
	default:
		return(3);
	}

	/* ラベル部チェック */
	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL) /* "Lab"はあるか ? */
		return(3);

	switch (labp->type) {			/* 属性チェック */
	case DS_N:				/* NULL */
		if (datp->type == DS_N)		/* データ部もＮＵＬＬか ? */
			return(1);
		if (ncol != 0)			/* 列数は０か ? */
			return(3);
		break;
	case DS_STR:				/* 文字型 */
		if ((axip = d3gcmp(labp, "Axi2")) == NULL) /* "Axi2"はあるか ?*/
			return(3);

		if (axip->type != DS_STR)		/* 構造体 ? */
			return(3);

		if ((labsp = d3gcmp(axip, "Labs")) == NULL)/* "Labs"はあるか ?*/
			return(3);

		if (labsp->length != ncol)	/* 列数と一致しているか ? */
			return(3);
		break;
	default:
		return(3);
	}

	return(2);
}

