/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3aryr.c
 *		ROUTINE		: �z��ǂݍ��ݏ���
 *		REVISION	:
 *		REMARKS		: 88.01.19
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp();

static	char	sccsid[]="@(#)d3aryr.c 1.3 88/06/12 14:46:34";

/*	FUNCTION DESCRIPTION
 *
 *			�z��ǂݍ���
 *
 *		FUNC.NAME	: d3aryr()
 *		INPUT		: dsp = data set pointer
 *				: attr = �z��f�[�^
 *		OUTPUT		: return value = error code
 */

d3aryr(dsp, attr)
DSET	*dsp;
PD_ARYREC	*attr;
{
	struct	dsm	*datp, *dimp, *datap;

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* search "Dat" */
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)	/* search "Dim" */
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)	/* search "Data" */
		return(DE_DS_FORMAT);

	attr->array_attr = datap->type;			/* �����Z�b�g */

	attr->array_dimno = dimp->length;		/* �������Z�b�g */

	if (dimp->length > 0)
		attr->array_dim1 = dimp->ptr.i[0];	/* �v�f���P�Z�b�g */
	else
		attr->array_dim2 = 0;

	if (dimp->length > 1)
		attr->array_dim2 = dimp->ptr.i[1];	/* �v�f���Q�Z�b�g */
	else
		attr->array_dim2 = 0;

	if (dimp->length > 2)
		attr->array_dim3 = dimp->ptr.i[2];	/* �v�f���R�Z�b�g */
	else
		attr->array_dim3 = 0;

	attr->array_ptr = datap->ptr.i;			/* �|�C���^�Z�b�g */

	return(0);
}
