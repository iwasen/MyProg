/************************************************************
 *	FILE DESCRIPTION
 *		�\�f�[�^�Z�b�g�̍s�ړ��@�\
 *              �w�肳�ꂽ�͈͂̍s�� dst �s�̒���Ɉړ�����
 *	FILE	:	d3tmvr.c
 *		:	d3tmvr
 *			dsp	:	DSET �|�C���^
 *			from	:	�ړ��J�n�s�ԍ� ( from >= 1 )
 *			to	:	�ړ��I���s�ԍ� ( to <= �s�� )
 *			dst	:	�ړ���s�ԍ� ( 0 <= dst <= �s�� )
 *	REMARKS	:	1988-07-14	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

#define	PREV	1
#define	SUCC	0

extern	char	*d3allc();
extern	DMEM	*d3gcmp(), *d3gtra();
extern	char	*malloc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";
static	DMEM	*datp, *dimp, *datap;

d3tmvr(dsp, from, to, dst)
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
						/* �f�[�^���̈ړ� */
	if ((err = tmvr70(memno, from, to, dst, rn, cn, nline)) != 0)
		return(err);

	return(0);
}

/****************************************************
*	�f�[�^���̈ړ��E�ړ�
****************************************************/
static	tmvr70(memno, from, to, dst, rn, cn, nline)
int	memno, from, to, dst, rn, cn, nline;
{
	int	*work;
	int	*p, *q;
	int	newdst, i, j, k , shift;

	if (to < dst)
		shift = PREV;		/* �O�l�� */
	else if (dst < from - 1)
		shift = SUCC;		/* ��l�� */
	else
		return(0);		/* ��������K�v���Ȃ� */

	/* Data �����̍X�V */
					/* ���[�N�̈���A���P�[�g */
	if ((work = (int *)malloc(nline * cn * sizeof(int))) == NULL)
		return(DE_MEM);

					/* �s�f�[�^�����[�N�ɃR�s�[ */
	q = work;
	for (i=1; i<=cn; i++) {
		p = PD_VALUE(datap) + rn*(i - 1) + (from - 1);
		for (j=0; j<nline; j++)
			*q++ = *p++;
	}

					/* �f�[�^�̃V�t�g*/
	if (shift == PREV) {		/* �O�l�� */
		for (i=1; i<=cn; i++) {
			p = PD_VALUE(datap) + rn*(i - 1) + to;
			q = PD_VALUE(datap) + rn*(i - 1) + (from - 1);
			k = dst - to;
			for (j=0; j<k; j++)
				*q++ = *p++;
		}
		newdst = dst - nline;
	} else {			/* ��l�� */
		for (i=1; i<=cn; i++) {
			p = PD_VALUE(datap) + rn*(i - 1) + (from - 1);
			q = PD_VALUE(datap) + rn*(i - 1) + to;
			k = from - dst - 1;
			for (j=0; j<k; j++)
				*--q = *--p;
		}
		newdst = dst;
	}
				/* ���[�N�̍s�f�[�^���f�[�^���ɃR�s�[ */
	p = work;
	for (i=1; i<=cn; i++) {
		q = PD_VALUE(datap) + rn*(i - 1) + newdst;
		for (j=0; j<nline; j++)
			*q++ = *p++;
	}

	free(work);
	return(0);
}