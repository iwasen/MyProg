/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3tup.c
 *		ROUTINE		: �\����  �P�s�X�V
 *		REVISION	:
 *		REMARKS		: 88.01.09
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp(), *d3gtra();
extern	char	*d3allc(), *d3csav();
extern	void	*d3ient();

static	char	sccsid[]="@(#)d3tup.c 1.4 88/06/12 14:59:56";

/*	FUNCTION DESCRIPTION
 *
 *			�P�s�X�V
 *
 *		FUNC.NAME	: d3tup()
 *		INPUT		: dsp = data set pointer
 *				: row = �X�V�s�ʒu
 *				: templete = templete pointer
 *		OUTPUT		: return value = error code
 */

d3tup(dsp, row, templete)
DSET	*dsp;
int	row;
T_TEMPLETE	*templete;
{
	struct	dsm	*labp, *axip, *datp, *dimp, *datap;
	int	rn, cn;

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

	if (row <= 0 || row > rn)		/* �p�����[�^�`�F�b�N */
		return(DE_PRM);

	return(tup10(dsp, axip, datap, rn, row, 1, templete));
}

/*	FUNCTION DESCRIPTION
 *
 *			���s�X�V
 *
 *		FUNC.NAME	: tup10()
 *		INPUT		: dsp = data set pointer
 *				: axip = ���x�����|�C���^
 *				: datap = �f�[�^���|�C���^
 *				: rn = �s��
 *				: row = �X�V�s�ʒu
 *				: n = �X�V�s��
 *				: templete = templete pointer
 *		OUTPUT		: return value = error code
 */

tup10(dsp, axip, datap, rn, row, n, templete)
DSET	*dsp;
struct	dsm	*axip, *datap;
int	rn, row, n;
T_TEMPLETE	*templete;
{
	int	reclen, cnum, i, col, code, err;
	TEMPLETE	*tp, *tp2;
	char	*bp, *bp2;

	bp = templete->tp_bufp;			/* set buffer pointer */
	reclen = templete->tp_rlen;		/* set record length */
	cnum = templete->tp_cnum;		/* set column number */
	tp = templete->tp_templete;		/* set templete pointer */

	while (n--) {
		for (i = 0, tp2 = tp; i < cnum; i++, tp2++) {
			bp2 = bp + tp2->tp_offset; /* set buffer pointer */
			col = tp2->tp_col;		/* get column */
			err = tup20(axip, bp2, col, dsp->ds_memno, &code);
			if (err < 0)
				return(err);
			datap->ptr.i[row-1 + (col-1)*rn] = code; /* get data */
		}
		row++;				/* �s�ʒu�X�V */
		bp += reclen;			/* buffer pointer �X�V */
	}
	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			�G���R�[�h����
 *
 *		FUNC.NAME	: tup20()
 *		INPUT		: axip = ���x�����|�C���^
 *				: dp = �f�[�^�|�C���^
 *				: col = ��ԍ�
 *				: memno = �������ԍ�
 *		OUTPUT		: code = �R�[�h�����l
 *				: return value = error code
 */

tup20(axip, dp, col, memno, code)
struct	dsm	*axip;
void	*dp;
int	col, memno, *code;
{
	struct	dsm	*trap;

	if ((trap = d3gtra(axip, col)) == NULL)
		*code = *(int *)dp;
	else {
		*code = tup30(trap, dp);
		if (*code == 0)
			return(tup40(trap, dp, memno, code));
	}
	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			�R�[�h������
 *
 *		FUNC.NAME	: tup30()
 *		INPUT		: trap = �|�󕔃|�C���^
 *				: dp = �f�[�^�|�C���^
 *		OUTPUT		: return value = �R�[�h�����l
 */

static
tup30(trap, dp)
struct	dsm	*trap;
void	*dp;
{
	int	i;

	for (i = 0; i < trap->length; i++) {
		switch (trap->type) {
		case DS_L:
		case DS_I:
		case DS_R:
			if (trap->ptr.i[i] == *(int *)dp)
				return(i+1);
			break;
		case DS_C:
			if (strcmp(trap->ptr.c[i], (char *)dp) == 0)
				return(i+1);
			break;
		}
	}
	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			�|�󕔃f�[�^�o�^����
 *
 *		FUNC.NAME	: tup40()
 *		INPUT		: trap = �|�󕔃|�C���^
 *				: dp = �f�[�^�|�C���^
 *				: memno = �������ԍ�
 *		OUTPUT		: code = �R�[�h�����l
 *				: return value = error code
 */

static
tup40(trap, dp, memno, code)
struct	dsm	*trap;
void	*dp;
int	memno, *code;
{
	union	{
		int	*i;
		char	**c;
	} mp;
	int	pos;

	pos = trap->length;
	switch (trap->type) {
	case DS_L:
	case DS_I:
	case DS_R:
		if ((mp.i = (int *)d3ient(memno, trap, pos, 1)) == NULL)
			return(DE_MEM);
		*mp.i = *(int *)dp;
		break;
	case DS_C:
		if ((mp.c = (char **)d3ient(memno, trap, pos, 1)) == NULL)
			return(DE_MEM);
		if ((*mp.c = d3csav(memno, (char *)dp)) == NULL)
			return(DE_MEM);
		break;
	}
	
	*code = pos + 1;
	return(0);
}
