/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tdlc.c
 *		ROUTINE		: 表操作 １列削除
 *		REVISION	:
 *		REMARKS		: 88.01.12
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();
static	char	sccsid[]="@(#)d3tdlc.c 1.4 88/06/12 14:57:18";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3tdlc()
 *		INPUT		: dsp = data set pointer
 *				: col = 削除列
 *		OUTPUT		: return value = error code
 */

d3tdlc(dsp, col)
DSET	*dsp;
int	col;
{
	struct	dsm	*labp, *datp, *dimp, *datap, *axip, *labsp;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL) /* search "Lab" */
		return(DE_DS_FORMAT);

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)	/* search "Dim" */
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)	/* search "Data" */
		return(DE_DS_FORMAT);

	if (dimp->length != 2)			/* 次元数チェック */
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)	/* search "Axi2" */
		return(DE_DS_FORMAT);

	if ((labsp = d3gcmp(axip, "Labs")) == NULL)	/* search "Labs" */
		return(DE_DS_FORMAT);

	if (col == 0 || col > labsp->length)	/* パラメータチェック */
		return(DE_PRM);

	tdlc10(labsp, col);			/* ラベル削除 */

	tdlc20(axip, col);			/* 翻訳部削除 */

	tdlc30(dimp, datap, col);		/* データ部削除 */

	return(0);
}


/*	FUNCTION DESCRIPTION
 *
 *			ラベル削除
 *
 *		FUNC.NAME	: tdlc10()
 *		INPUT		: labsp = "Labs" pointer
 *				: col = 列番号
 *		OUTPUT		: none
 */

static
tdlc10(labsp, col)
struct	dsm	*labsp;
int	col;
{
	int	i;

	for (i = col; i <= labsp->length; i++)
		labsp->ptr.c[i-1] = labsp->ptr.c[i];

	labsp->length--;
}

/*	FUNCTION DESCRIPTION
 *
 *			翻訳部削除
 *
 *		FUNC.NAME	: tdlc20()
 *		INPUT		: axip = ラベル部ポインタ
 *				: col = 列番号
 *		OUTPUT		: none
 */

static
tdlc20(axip, col)
struct	dsm	*axip;
int	col;
{
	char	tra[8];
	struct	dsm	*axip2, *axip3;
	int	len, i, n;

	if (axip->type != DS_STR)
		return;

	axip2 = axip->ptr.d;
	sprintf(tra, "Tra%d", col);
	len = axip->length;
	axip3 = axip2 + 2;
	while (len--) {
		if (strcmp(tra, axip3->seibun) == 0)
			break;
		axip3++;
	}
	if (len >= 0) {
		while (len--) {
			*axip3 = *(axip3 + 1);
			axip3++;
		}
		axip->length--;
		axip2->length--;
		(*(int *)&(axip2 + 1)->ptr)--;
	}

	for (i = 0, axip3 = axip2 + 2; i < axip->length; i++, axip3++) {
		if (strncmp(axip3->seibun, "Tra", 3) == 0) {
			n = atoi(axip3->seibun + 3);
			if (n > col) {
				sprintf(tra, "Tra%d", n - 1);
				strcpy(axip3->seibun, tra);
			}
		}
	}
}

/*	FUNCTION DESCRIPTION
 *
 *			データ部削除
 *
 *		FUNC.NAME	: tdlc30()
 *		INPUT		: dimp = 次元数部ポインタ
 *				: datap = データ部ポインタ
 *				: col = 列番号
 *		OUTPUT		: none
 */

static
tdlc30(dimp, datap, col)
struct	dsm	*dimp, *datap;
int	col;
{
	int	rn, cn, i, j, *rp, *wp;

	rn = dimp->ptr.i[0];
	cn = dimp->ptr.i[1];

	rp = datap->ptr.i + col * rn;
	wp = rp - rn;
	for (i = col; i < cn; i++) {
		for (j = 1; j <= rn; j++)
			*wp++ = *rp++;
	}
	dimp->ptr.i[1]--;
	datap->length -= rn;
}

