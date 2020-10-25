/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tinc.c
 *		ROUTINE		: 表操作 １列挿入
 *		REVISION	:
 *		REMARKS		: 88.01.13
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();
extern	char	*d3allc(), *d3csav();

static	char	sccsid[]="@(#)d3tinc.c 1.4 88/06/12 14:57:59";

/*	FUNCTION DESCRIPTION
 *
 *			１列挿入
 *
 *		FUNC.NAME	: d3tinc()
 *		INPUT		: dsp = data set pointer
 *				: col = 挿入列番号
 *				: attrib = 属性
 *				: label = ラベルデータ
 *		OUTPUT		: return value = error code
 */

d3tinc(dsp, col, attrib, label)
DSET	*dsp;
int	col, attrib;
char	*label;
{
	struct	dsm	*labp, *datp;
	int	err;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL) /* search "Lab" */
		return(DE_DS_FORMAT);

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	switch (attrib) {			/* check attibute */
	case DS_L:
	case DS_I:
	case DS_R:
	case DS_C:
		break;
	default:
		return(DE_PRM);
	}

	if ((err = tinc10(labp, col, label, dsp->ds_memno)) != 0)
		return(err);

	if ((err = tinc20(labp, col, attrib, dsp->ds_memno)) != 0)
		return(err);

	if ((err = tinc30(datp, col, attrib, dsp->ds_memno)) != 0)
		return(err);

	dsp->ds_entry->ptr.d[1].type = DSID_TBL; /* 構造体ｉｄセット */

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			ラベル追加
 *
 *		FUNC.NAME	: tinc10()
 *		INPUT		: labp = ラベル部ポインタ
 *				: col = 挿入列番号
 *				: label = ラベルデータ
 *				: memno = memory No.
 *		OUTPUT		: return value = error code
 */

static
tinc10(labp, col, label, memno)
struct	dsm	*labp;
int	col;
char	*label;
int	memno;
{
	struct	dsm	*sp, *axip, *labsp;
	char	**cpp;

	if (labp->type != DS_STR) {
		if ((sp = (struct dsm *)d3allc(memno, sizeof(struct dsm) * 2)) == NULL)
			return(DE_MEM);
		labp->type = DS_STR;
		labp->length = 0;
		labp->ptr.d = sp;

		sp->seibun = 0;
		sp->type = DS_DIR;
		sp->length = 0;
		if ((sp->ptr.s = d3csav(memno, "Lab")) == NULL)
			return(DE_MEM);

		sp++;
		sp->seibun = 0;
		sp->type = 10;
		sp->length = 0;
		*(int *)&sp->ptr = 0;
	}

	if ((axip = d3gcmp(labp, "Axi2")) == NULL) {
		if ((axip = (struct dsm *)d3ient(memno, labp, 0, 1)) == NULL)
			return(DE_MEM);
		if ((axip->seibun = d3csav(memno, "Axi2")) == NULL)
			return(DE_MEM);
		if ((sp = (struct dsm *)d3allc(memno, sizeof(struct dsm) * 2)) == NULL)
			return(DE_MEM);
		axip->type = DS_STR;
		axip->length = 0;
		axip->ptr.d = sp;

		sp->seibun = 0;
		sp->type = DS_DIR;
		sp->length = 0;
		if ((sp->ptr.s = d3csav(memno, "Axi2")) == NULL)
			return(DE_MEM);

		sp++;
		sp->seibun = 0;
		sp->type = 10;
		sp->length = 0;
		*(int *)&sp->ptr = 0;
	}

	if ((labsp = d3gcmp(axip, "Labs")) == NULL) {
		if ((labsp = (struct dsm *)d3ient(memno, axip, 0, 1)) == NULL)
			return(DE_MEM);
		if ((labsp->seibun = d3csav(memno, "Labs")) == NULL)
			return(DE_MEM);
		labsp->type = DS_C;
		labsp->length = 0;
	}

	if (col > labsp->length)
		return(DE_PRM);

	if ((cpp = (char **)d3ient(memno, labsp, col, 1)) == NULL)
		return(DE_MEM);
	if ((*cpp = d3csav(memno, label)) == NULL)
		return(DE_MEM);

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			翻訳部追加
 *
 *		FUNC.NAME	: tinc20()
 *		INPUT		: labp = ラベル部ポインタ
 *				: col = 挿入列番号
 *				: attrib = 属性
 *				: memno = memory No.
 *		OUTPUT		: return value = error code
 */

static
tinc20(labp, col, attrib, memno)
struct	dsm	*labp;
int	col, attrib, memno;
{
	int	i, n;
	struct	dsm	*axip2, *axip3, *axip, *trap;
	char	tra[8], *cp;

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)
		return(DE_DS_FORMAT);

	axip2 = axip->ptr.d;

	for (i = 0, axip3 = axip2 + 2; i < axip->length; i++, axip3++) {
		if (strncmp(axip3->seibun, "Tra", 3) == 0) {
			n = atoi(axip3->seibun + 3);
			if (n > col) {
				sprintf(tra, "Tra%d", n + 1);
				if (strlen(tra) > strlen(axip3->seibun)) {
					if ((cp = d3allc(memno, strlen(tra)+1)) == NULL)
						return(DE_MEM);
					axip3->seibun = cp;
				}
				strcpy(axip3->seibun, tra);
			}
		}
	}

	if (attrib == DS_I)
		return(0);

	if ((trap = (struct dsm *)d3ient(memno, axip, axip->length, 1)) == NULL)
		return(DE_MEM);
	sprintf(tra, "Tra%d", col + 1);
	if ((trap->seibun = d3csav(memno, tra)) == NULL)
		return(DE_MEM);
	trap->type = attrib;
	trap->length = 1;
	switch (attrib) {
	case DS_L:
	case DS_I:
	case DS_R:
		if ((trap->ptr.i = (int *)d3allc(memno, sizeof(int))) == NULL)
			return(DE_MEM);
		trap->ptr.i[0] = PD_NA;
		break;
	case DS_C:
		if ((trap->ptr.c = (char **)d3allc(memno, sizeof(char *))) == NULL)
			return(DE_MEM);
		if ((trap->ptr.c[0] = d3allc(memno, 1)) == NULL)
			return(DE_MEM);
		*trap->ptr.c[0] = '\0';
		break;
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			データ部列挿入
 *
 *		FUNC.NAME	: tinc30()
 *		INPUT		: datp = データ部ポインタ
 *				: col = 挿入列番号
 *				: attrib = 属性
 *				: memno = memory No.
 *		OUTPUT		: return value = error code
 */

static
tinc30(datp, col, attrib, memno)
struct	dsm	*datp;
int	attrib, col, memno;
{
	struct	dsm	*sp, *dimp, *datap;
	int	rn, cn, *ip;

	if (datp->type != DS_STR) {
		if ((sp = (struct dsm *)d3allc(memno, sizeof(struct dsm) * 4)) == NULL)
			return(DE_MEM);

		datp->type = DS_STR;
		datp->length = 2;
		datp->ptr.d = sp;

		sp->seibun = 0;
		sp->type = DS_DIR;
		sp->length = 2;
		if ((sp->ptr.s = d3csav(memno, "Dat")) == NULL)
			return(DE_MEM);

		sp++;
		sp->seibun = 0;
		sp->type = 21;
		sp->length = 0;
		*(int *)&sp->ptr = 2;

		sp++;
		if ((sp->seibun = d3csav(memno, "Dim")) == NULL)
			return(DE_MEM);
		sp->type = DS_I;
		sp->length = 2;
		if ((sp->ptr.i = (int *)d3allc(memno, sizeof(int) * 2)) == NULL)
			return(DE_MEM);
		sp->ptr.i[0] = sp->ptr.i[1] = 0;

		sp++;
		if ((sp->seibun = d3csav(memno, "Data")) == NULL)
			return(DE_MEM);
		sp->type = DS_I;
		sp->length = 0;
	}

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)
		return(DE_DS_FORMAT);

	rn = dimp->ptr.i[0];
	cn = dimp->ptr.i[1];

	if (col > cn)
		return(DE_PRM);

	if (rn != 0) {
		if ((ip = (int *)d3ient(memno, datap, col * rn, rn)) == NULL)
			return(DE_MEM);
		while (rn--)
			*ip++ = (attrib == DS_I) ? PD_NA : 1;
	}
	dimp->ptr.i[1]++;

	return(0);
}

