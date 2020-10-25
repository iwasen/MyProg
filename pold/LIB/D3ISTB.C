/*	FILE DESCROPTION
 *
 *		FILE NAME	: d3istb.c
 *		ROUTINE		: �\�`���f�[�^�t�H�[�}�b�g�`�F�b�N
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
 *		OUTPUT		: return value = 0 �\�f�[�^�Z�b�g(id = 254)
 *				:              = 1 �m�t�k�k�f�[�^�Z�b�g
 *				:              = 2 �\�f�[�^�Z�b�g�Ƃ݂Ȃ���
 *				:                                (id != 254)
 *				:              = 3 �\�f�[�^�Z�b�g�ł͂Ȃ�
 *		REMARKS		:
 */

d3istb(dsp)
DSET	*dsp;
{
	struct	dsm	*datp, *dimp, *datap, *labp, *axip, *labsp;
	int	ncol = 0;

	/* �\���̂h�c�`�F�b�N */
	if (dsp->ds_entry->ptr.d[1].type == DSID_TBL)
		return(0);

	/* �f�[�^���`�F�b�N */
	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL) /* "Dat"�͂��邩? */
		return(3);

	switch (datp->type) {			/* �����`�F�b�N */
	case DS_N:				/* NULL */
		break;
	case DS_STR:				/* �\���� */
		if (datp->length != 2)		/* "Dat"�̗v�f���͂Q�� */
			return(3);

		if ((dimp = d3gcmp(datp, "Dim")) == NULL) /* "Dim"�͂��邩 ? */
			return(3);

		if (dimp->type != DS_I)			/* �����^ ? */
			return(3);

		if (dimp->length != 2)			/* �Q���� ? */
			return(3);

		ncol = dimp->ptr.i[1];			/* �񐔎��o�� */

		if ((datap = d3gcmp(datp, "Data")) == NULL)/* "Data"�͂��邩 ?*/
			return(3);

		if (datap->type != DS_I)		/* �����^ ? */
			return(3);
		break;
	default:
		return(3);
	}

	/* ���x�����`�F�b�N */
	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL) /* "Lab"�͂��邩 ? */
		return(3);

	switch (labp->type) {			/* �����`�F�b�N */
	case DS_N:				/* NULL */
		if (datp->type == DS_N)		/* �f�[�^�����m�t�k�k�� ? */
			return(1);
		if (ncol != 0)			/* �񐔂͂O�� ? */
			return(3);
		break;
	case DS_STR:				/* �����^ */
		if ((axip = d3gcmp(labp, "Axi2")) == NULL) /* "Axi2"�͂��邩 ?*/
			return(3);

		if (axip->type != DS_STR)		/* �\���� ? */
			return(3);

		if ((labsp = d3gcmp(axip, "Labs")) == NULL)/* "Labs"�͂��邩 ?*/
			return(3);

		if (labsp->length != ncol)	/* �񐔂ƈ�v���Ă��邩 ? */
			return(3);
		break;
	default:
		return(3);
	}

	return(2);
}
