/************************************************************
 *	FILE DESCRIPTION
 *		表データセットの列コピー機能
 *              指定された範囲の列を dst 列の直後にコピーする
 *	FILE	:	d3tcpc.c
 *		:	d3tcpc
 *			dsp	:	DSET ポインタ
 *			from	:	コピー開始列番号 ( from >= 1 )
 *			to	:	コピー終了列番号 ( to <= 列数 )
 *			dst	:	コピー先列番号 ( 0 <= dst <= 列数 )
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
						/* チェック */
	if ((err = tcpc10(dsp, from, to, dst, &rn, &cn)) != 0)
		return(err);

	ncol = to - from + 1;
	memno = PD_MEMNO(dsp);
						/* 列名のコピー */
	if ((err = tcpc30(memno, from, dst, ncol)) != 0)
		return(err);
						/* 翻訳部のコピー */
	if ((err = tcpc50(memno, from, to, dst, cn, ncol)) != 0)
		return(err);
						/* データ部のコピー */
	if ((err = tcpc70(memno, from, dst, rn, cn, ncol)) != 0)
		return(err);

	return(0);
}

/****************************************************
*	チェックルーチン
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
*	列名のコピー
****************************************************/
static	tcpc30(memno, from, dst, ncol)
int	memno, from, dst, ncol;
{
	char	**workp, **wp, **np, **p;
	int	i;

				/* 該当列名へのポインタをワークへコピー */
	if ((workp = (char **)malloc(ncol * sizeof(char *))) == NULL)
		return(DE_MEM);
	wp = workp;
	np = (char **)PD_VALUE(labsp) + from - 1;
	for (i=0; i<ncol; i++)
		*wp++ = *np++;

				/* ワーク上の該当列名へのポインタから
				   列名を列成分に追加へコピー */
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
*	翻訳部のコピー
****************************************************/
static	tcpc50(memno, from, to, dst, cn, ncol)
int	memno, from, to, dst, cn, ncol;
{
	DMEM	*workp, *wp, *p, *p_1, *p_save;
	int	i, j, pos, err;
	int	copy, shift;		/* カウンタ !! */
	char	buf[8];

					/* 翻訳部の処理が必要か */
	for (i=1, copy=0, shift=0; i<=cn; i++) {
		if ((p = (DMEM *)d3gtra(axip, i)) != NULL) {
			if (from <= i && i <= to)
				copy++;		/* 翻訳部のコピーカウント */
			if (dst < i)
				shift++;	/* 翻訳部のシフトカウント */
		}
	}
	if (copy == 0 && shift == 0)	/* 両方の <カウント> が ０ なら */
		return(0);		/* 何もしない */

	if (copy) {		/* 該当する翻訳部をワークへコピー */

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

	if (shift) {		/* 挿入列より後ろの Tra 番号をシフト */

		for (i=cn, pos=0; i>dst; i--) {
			if ((p = (DMEM *)d3gtra(axip, i)) == NULL)
				continue;
			sprintf(buf, "Tra%d", i + ncol);
			PD_NAME(p) = d3csav(memno, buf);
		}
	}

	if (copy) {		/* ワーク上のデータを翻訳部にコピー */

					/* 挿入位置を捜す */
		p_save = NULL;
		for (i=1; i<=cn + ncol; i++) {
			if ((p = (DMEM *)d3gtra(axip, i)) != NULL) {
				if (i <= dst)
					p_save = p; /* 挿入位置を保存する */
				else {
					if (p_save == NULL)
						p_save = p - 1;	/* 行き過ぎ */
					break;
				}
			}
		}
					/* エントリ番号に変換する */
		pos = p_save - (DMEM *)PD_VALUE(axip) - 2 + 1;

					/* ワーク上のデータを翻訳部にコピー */
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
*	データ部のコピー
****************************************************/
static	tcpc70(memno, from, dst, rn, cn, ncol)
int	memno, from, dst, rn, cn, ncol;
{
	int	*workp, *wp, *p;
	int	i, num;

						/* Dim 成分の更新 */
	*(PD_VALUE(dimp) + 1) += ncol;
						/* Data 成分の更新 */
	if (rn == 0)
		return(0);

						/* ワークをアロケートする */
	num = ncol * rn;
	if ((workp = (int *)malloc(num * sizeof(int))) == NULL)
		return(DE_MEM);

					/* 該当列をワークにコピーする */
	wp = workp;
	p = PD_VALUE(datap) + (from - 1)*rn;

	for (i=0; i<num; i++)
		*wp++ = *p++;

				/* ワーク上のデータをデータ部にコピー */
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
