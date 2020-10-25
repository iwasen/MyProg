/************************************************************
 *	FILE DESCRIPTION
 *		�\�f�[�^�Z�b�g�̍s�R�s�[�@�\
 *              �w�肳�ꂽ�͈͂̍s�� dst �s�̒���ɃR�s�[����
 *	FILE	:	d3tcpr.c
 *		:	d3tcpr
 *			dsp	:	DSET �|�C���^
 *			from	:	�R�s�[�J�n�s�ԍ� ( from >= 1 )
 *			to	:	�R�s�[�I���s�ԍ� ( to <= �s�� )
 *			dst	:	�R�s�[��s�ԍ� ( 0 <= dst <= �s�� )
 *	REMARKS	:	1988-07-11	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc();
extern	DMEM	*d3gcmp(), *d3gtra();
extern	char	*malloc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";
static	DMEM	*datp, *dimp, *datap;

d3tcpr(dsp, from, to, dst)
DSET	*dsp;
int	from, to, dst;
{
	int	err, rn, cn, memno, nline;

						/* �`�F�b�N */
	err = tcpr10(dsp, from, to, dst, &rn, &cn, &datp, &dimp, &datap);
	if (err != 0)
		return(err);

	nline = to - from + 1;
	memno = PD_MEMNO(dsp);
						/* �f�[�^���̃R�s�[ */
	if ((err = tcpr70(memno, from, dst, rn, cn, nline)) != 0)
		return(err);

	return(0);
}

/****************************************************
*	�`�F�b�N���[�`��
****************************************************/
tcpr10(dsp, from, to, dst, rn, cn, datp, dimp, datap)
DSET	*dsp;
int	from, to, dst;
int	*rn, *cn;
DMEM	**datp, **dimp, **datap;
{
	if ((*datp = d3gcmp(PD_DMEM(dsp), "Dat")) == NULL)
		return(DE_DS_FORMAT);

	if ((*datap = d3gcmp(*datp, "Data")) == NULL)
		return(DE_PRM);

	if ((*dimp = d3gcmp(*datp, "Dim")) == NULL)
		return(DE_PRM);

	if (PD_LENGTH(*dimp) != 2)
		return(DE_PRM);

	*rn = *(PD_VALUE(*dimp) + 0);
	*cn = *(PD_VALUE(*dimp) + 1);

	if (1 <= from	&&
		from <= to	&&
		to   <= *rn	&&
		0    <= dst	&&
		dst  <= *rn)
		;			/* OK */
	else
		return(DE_PRM);

	return(0);
}

/****************************************************
*	�f�[�^���̃R�s�[
****************************************************/
static	tcpr70(memno, from, dst, rn, cn, nline)
int	memno, from, dst, rn, cn, nline;
{
	int	*work, *data, *d, *s, *w;
	int	*p, *q;
	int	i, j, k , size;
	int	*source, *destin;

					/* Dim �����̍X�V */
	*PD_VALUE(dimp) += nline;
					/* Data �����̍X�V */

	source = PD_VALUE(datap);	/* �I���W�i���f�[�^�ւ̃|�C���^
					   ��ۑ����� */

					/* �f�[�^���ɋ󂫂����邩 */
	size = d3gsiz((rn + nline) * cn);

	if (size != d3gsiz(rn * cn)) {
		if ((data = (int *)d3allc(memno, size * sizeof(int))) == NULL)
			return(DE_MEM);

		PD_VALUE(datap) = data;	/* �V�����f�[�^�̈�ւ̃|�C���^ */
	}

	destin = PD_VALUE(datap);	/* �]����̈�ւ̃A�h���X */

					/* ���[�N�̈���A���P�[�g */
	if ((work = (int *)malloc(nline * cn * sizeof(int))) == NULL)
		return(DE_MEM);

					/* �s�f�[�^�����[�N�ɃR�s�[ */
	q = work;
	for (i=1; i<=cn; i++) {
		p = source + rn*(i - 1) + (from - 1);	/* �I���W�i���f�[�^ */

		for (j=0; j<nline; j++)
			*q++ = *p++;
	}

				/* ���[�N�̍s�f�[�^���f�[�^���ɃR�s�[ */
	s = source + rn * cn;		/* �]�����A�h���X */
	d = destin + (rn + nline) * cn;	/* �]����A�h���X */
	w = work + nline * cn;		/* ���[�N�A�h���X */

	for (i=cn; i>0; i--) {

		k = rn - dst;
		for (j=0; j<k; j++)
			*--d = *--s;

		for (j=0; j<nline; j++)
			*--d = *--w;

		k = dst;
		for (j=0; j<k; j++)
			*--d = *--s;
	}

					/* Data �����̗v�f�����X�V���� */
	PD_LENGTH(datap) += nline * cn;

	free(work);
	return(0);
}