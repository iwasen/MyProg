/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3trd.c
 *		ROUTINE		: �\����  ���s�ǂݍ���
 *		REVISION	:
 *		REMARKS		: 88.01.09
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp(), *d3gtra();

static	char	sccsid[]="@(#)d3trd.c 1.4 88/06/12 14:59:15";

/*	FUNCTION DESCRIPTION
 *
 *		FUNC.NAME	: d3trd()
 *		INPUT		: dsp = data set pointer
 *				: row = �ǂݍ��ݍs
 *				: n   = �ǂݍ��ݍs��
 *				: templete = templete pointer
 *		OUTPUT		: return value = error code
 */

d3trd(dsp, row, n, templete)
DSET	*dsp;
int	row, n;
T_TEMPLETE	*templete;
{
	struct	dsm	*labp, *axip, *trap, *datp, *dimp, *datap;
	int	rn, cn, reclen, cnum, i, col, data, *dp;
	TEMPLETE	*tp;
	union	{
		int	*i;
		char	*c;
	} bp, bp2;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL) /* search "Lab" */
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)	/* search "Axi2" */
		return(DE_DS_FORMAT);

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)	/* search "Dim" */
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)	/* search "Data" */
		return(DE_DS_FORMAT);

	if (dimp->length != 2)			/* �������`�F�b�N */
		return(DE_DS_FORMAT);

	rn = dimp->ptr.i[0];			/* �s�����o�� */
	cn = dimp->ptr.i[1];			/* �񐔎��o�� */
	dp = datap->ptr.i;			/* set data pointer */

	if (row + n - 1 > rn)			/* �p�����[�^�`�F�b�N */
		return(DE_PRM);

	bp.c = templete->tp_bufp;		/* set buffer pointer */
	reclen = templete->tp_rlen;		/* set record length */
	cnum = templete->tp_cnum;		/* set column number */

	while (n--) {
		for (i = 0, tp = templete->tp_templete; i < cnum; i++, tp++) {
			bp2.c = bp.c + tp->tp_offset;	/* set buffer pointer */
			col = tp->tp_col;		/* get column */
			data = dp[row-1 + (col-1)*rn];	/* get data */
			trap = d3gtra(axip, col);	/* �|�󕔃T�[�` */
			if (trap == NULL)
				*bp2.i = data;
			else {
				switch (trap->type) {
				case DS_I:
				case DS_R:
				case DS_L:
					*bp2.i = trap->ptr.i[data-1];
					break;
				case DS_C:
					strncpy(bp2.c, trap->ptr.c[data-1], tp->tp_len);
					break;
				default:
					return(DE_DS_FORMAT);
				}
			}
		}
		row++;				/* �s�ʒu�X�V */
		bp.c += reclen;			/* buffer pointer �X�V */
	}
	return(0);
}
