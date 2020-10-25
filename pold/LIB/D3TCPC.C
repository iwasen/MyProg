/************************************************************
 *	FILE DESCRIPTION
 *		�\�f�[�^�Z�b�g�̗�R�s�[�@�\
 *              �w�肳�ꂽ�͈̗͂�� dst ��̒���ɃR�s�[����
 *	FILE	:	d3tcpc.c
 *		:	d3tcpc
 *			dsp	:	DSET �|�C���^
 *			from	:	�R�s�[�J�n��ԍ� ( from >= 1 )
 *			to	:	�R�s�[�I����ԍ� ( to <= �� )
 *			dst	:	�R�s�[���ԍ� ( 0 <= dst <= �� )
 *	REMARKS	:	1988-07-11	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	DMEM	*d3gcmp(), *d3gtra();
extern	char	*malloc(), *calloc(), *d3csav();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	DMEM	*labp, *axip, *labsp, *datp, *dimp, *datap;

d3tcpc(dsp, from, to, dst)
DSET	*dsp;
int	from, to, dst;
{
	int	rn, cn, ncol, err, memno;
						/* �`�F�b�N */
	if ((err = tcpc10(dsp, from, to, dst, &rn, &cn)) != 0)
		return(err);

	ncol = to - from + 1;
	memno = PD_MEMNO(dsp);
						/* �񖼂̃R�s�[ */
	if ((err = tcpc30(memno, from, dst, ncol)) != 0)
		return(err);
						/* �|�󕔂̃R�s�[ */
	if ((err = tcpc50(memno, from, to, dst, cn, ncol)) != 0)
		return(err);
						/* �f�[�^���̃R�s�[ */
	if ((err = tcpc70(memno, from, dst, rn, cn, ncol)) != 0)
		return(err);

	return(0);
}

/****************************************************
*	�`�F�b�N���[�`��
****************************************************/
static	tcpc10(dsp, from, to, dst, rn, cn)
DSET	*dsp;
int	from, to, dst;
int	*rn, *cn;
{
	if ((datp = d3gcmp(PD_DMEM(dsp), "Dat")) == NULL)
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)
		return(DE_PRM);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)
		return(DE_PRM);

	if (PD_LENGTH(dimp) != 2)
		return(DE_PRM);

	*rn = *PD_VALUE(dimp);
	*cn = *(PD_VALUE(dimp) + 1);

	if (1 <= from	&&
		from <= to	&&
		to   <= *cn	&&
		0    <= dst	&&
		dst  <= *cn)
		;			/* OK */
	else
		return(DE_PRM);

	if ((labp = d3gcmp(PD_DMEM(dsp), "Lab")) == NULL)
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)
		return(DE_DS_FORMAT);

	if ((labsp = d3gcmp(axip, "Labs")) == NULL)
		return(DE_DS_FORMAT);

	return(0);
}

/****************************************************
*	�񖼂̃R�s�[
****************************************************/
static	tcpc30(memno, from, dst, ncol)
int	memno, from, dst, ncol;
{
	char	**workp, **wp, **np, **p;
	int	i;

				/* �Y���񖼂ւ̃|�C���^�����[�N�փR�s�[ */
	if ((workp = (char **)malloc(ncol * sizeof(char *))) == NULL)
		return(DE_MEM);
	wp = workp;
	np = (char **)PD_VALUE(labsp) + from - 1;
	for (i=0; i<ncol; i++)
		*wp++ = *np++;

				/* ���[�N��̊Y���񖼂ւ̃|�C���^����
				   �񖼂�񐬕��ɒǉ��փR�s�[ */
	if ((np = (char **)d3ient(memno, labsp, dst, ncol)) == NULL) {
		free(workp);
		return(DE_MEM);
	}
	p = np;
	wp = workp;
	for (i=0; i<ncol; i++)
		*p++ = d3csav(memno, *wp++);
	free(workp);

	return(0);
}

/****************************************************
*	�|�󕔂̃R�s�[
****************************************************/
static	tcpc50(memno, from, to, dst, cn, ncol)
int	memno, from, to, dst, cn, ncol;
{
	DMEM	*workp, *wp, *p, *p_1, *p_save;
	int	i, j, pos, err;
	int	copy, shift;		/* �J�E���^ !! */
	char	buf[8];

					/* �|�󕔂̏������K�v�� */
	for (i=1, copy=0, shift=0; i<=cn; i++) {
		if ((p = (DMEM *)d3gtra(axip, i)) != NULL) {
			if (from <= i && i <= to)
				copy++;		/* �|�󕔂̃R�s�[�J�E���g */
			if (dst < i)
				shift++;	/* �|�󕔂̃V�t�g�J�E���g */
		}
	}
	if (copy == 0 && shift == 0)	/* ������ <�J�E���g> �� �O �Ȃ� */
		return(0);		/* �������Ȃ� */

	if (copy) {		/* �Y������|�󕔂����[�N�փR�s�[ */

		if ((workp = (DMEM *)malloc(copy * sizeof(DMEM))) == NULL)
			return(DE_MEM);

		for (i=0, j=0; j<copy; i++) {
			if ((p = (DMEM *)d3gtra(axip, from + i)) == NULL)
				continue;
			if ((err = d3lcpy(memno, p, &p_1)) != 0) {
				free(workp);
				return(err);
			}
			*(workp + j) = *p_1;
			sprintf(buf, "Tra%d", dst + 1 + i);
			PD_NAME(workp + j) = d3csav(memno, buf);
			j++;
		}
	}

	if (shift) {		/* �}��������� Tra �ԍ����V�t�g */

		for (i=cn, pos=0; i>dst; i--) {
			if ((p = (DMEM *)d3gtra(axip, i)) == NULL)
				continue;
			sprintf(buf, "Tra%d", i + ncol);
			PD_NAME(p) = d3csav(memno, buf);
		}
	}

	if (copy) {		/* ���[�N��̃f�[�^��|�󕔂ɃR�s�[ */

					/* �}���ʒu��{�� */
		p_save = NULL;
		for (i=1; i<=cn + ncol; i++) {
			if ((p = (DMEM *)d3gtra(axip, i)) != NULL) {
				if (i <= dst)
					p_save = p; /* �}���ʒu��ۑ����� */
				else {
					if (p_save == NULL)
						p_save = p - 1;	/* �s���߂� */
					break;
				}
			}
		}
					/* �G���g���ԍ��ɕϊ����� */
		pos = p_save - (DMEM *)PD_VALUE(axip) - 2 + 1;

					/* ���[�N��̃f�[�^��|�󕔂ɃR�s�[ */
		if ((p = (DMEM *)d3ient(memno, axip, pos, copy)) == NULL) {
			free(workp);
			return(DE_MEM);
		}
		wp = workp;
		for (i=0; i<copy; i++)
			*p++ = *wp++;

		free(workp);
	}

	return(0);
}

/****************************************************
*	�f�[�^���̃R�s�[
****************************************************/
static	tcpc70(memno, from, dst, rn, cn, ncol)
int	memno, from, dst, rn, cn, ncol;
{
	int	*workp, *wp, *p;
	int	i, num;

						/* Dim �����̍X�V */
	*(PD_VALUE(dimp) + 1) += ncol;
						/* Data �����̍X�V */
	if (rn == 0)
		return(0);

						/* ���[�N���A���P�[�g���� */
	num = ncol * rn;
	if ((workp = (int *)malloc(num * sizeof(int))) == NULL)
		return(DE_MEM);

					/* �Y��������[�N�ɃR�s�[���� */
	wp = workp;
	p = PD_VALUE(datap) + (from - 1)*rn;

	for (i=0; i<num; i++)
		*wp++ = *p++;

				/* ���[�N��̃f�[�^���f�[�^���ɃR�s�[ */
	if ((p = (int *)d3ient(memno, datap, rn * dst, num)) == NULL) {
		free(workp);
		return(DE_MEM);
	}
	wp = workp;
	for (i=0; i<num; i++)
		*p++ = *wp++;

	free(workp);
	return(0);
}