/************************************************************
 *	FILE DESCRIPTION
 *		�f�[�^�Z�b�g�̐����̍폜����
 *	FILE	:	d3dcmp.c
 *		:	d3dcmp
 *	REMARKS	:	1988-04-30	M.MOTOKI
 *
 ************************************************************/
#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

static	char	sccsid[]="@(#)d3dcmp.c 1.2 88/06/12 15:01:01";

d3dcmp(mp, comp_name)
DMEM	*mp;
char	*comp_name;
{
	PD_DENT	box;
	int	err, ln, nitems=1, j;
	DMEM	*p1, *p2;

	err = d3gent(mp, comp_name, &box);
	if (err != 0) return(err);

	ln = PD_LENGTH((DMEM *)PD_VALUE(box.ent_parent));
	if (ln == nitems) {		/* �e������ NULL�����ɂ��� */
		PD_MODE(box.ent_parent)   = DS_N;	/* ���� ! */
		PD_LENGTH(box.ent_parent) = 0;
		PD_VALUE(box.ent_parent)  = NULL;	/* ����������
					�R�����g�ɂ��悤 */
	} else {			/* �O�l���� */
		p1 = box.ent_entp;
		p2 = box.ent_entp + nitems;
		for (j = 0; j < ln - box.ent_pos; j++)
			*p1++ = *p2++;

		PD_LENGTH((DMEM *)PD_VALUE(box.ent_parent)) -= nitems;
		PD_LENGTH(box.ent_parent) -= nitems;
	}

	return(0);
}