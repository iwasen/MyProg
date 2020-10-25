/************************************************************
 *	FILE DESCRIPTION
 *		メモリ上のデータセットをＰＯＬＤメモリ上にコピーする
 *              ent1 -> ent2
 *			ent1	:	転送元データセットへのポインタ
 *			ent2	:	転送先データセットへのポインタ
 *			memno	:	転送先のメモリ番号
 *	FILE	:	d3lcpy.c
 *		:	d3lcpy
 *	REMARKS	:	1988-07-11	M.MOTOKI
 *
 ************************************************************/

#include <stdio.h>
#include <memory.h>
#include "la_ws/include/d3libc.h"
#include "la_ws/include/d3lmac.h"

extern	char	*d3allc(), *d3csav(), *memcpy();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3lcpy(memno, ent1, ent2)
int	memno;
DMEM	*ent1, **ent2;
{
	int	size, i, err, *ip_1, *ip_2;
	DMEM	*p, *p1, *p2, *p3;
	char	**cp_1, **cp_2;

	switch( PD_MODE(ent1) ) {
	case	DS_L:
	case	DS_I:
	case	DS_R:
		*ent2 = (DMEM *)d3allc(memno, sizeof(DMEM));
		PD_NAME(*ent2)   = d3csav(memno, PD_NAME(ent1));
		PD_MODE(*ent2)   = PD_MODE(ent1);
		PD_LENGTH(*ent2) = PD_LENGTH(ent1); 
		size  = d3gsiz(PD_LENGTH(*ent2));
		PD_VALUE(*ent2)  = (int *)d3allc(memno, size * sizeof(int));
		ip_1 = PD_VALUE(ent1);
		ip_2 = PD_VALUE(*ent2);

		for (i=0; i<PD_LENGTH(*ent2); i++)
			*ip_2++ = *ip_1++;
		break;

	case	DS_C:
		*ent2 = (DMEM *)d3allc(memno, sizeof(DMEM));
		PD_NAME(*ent2)   = d3csav(memno, PD_NAME(ent1));
		PD_MODE(*ent2)   = PD_MODE(ent1);
		PD_LENGTH(*ent2) = PD_LENGTH(ent1);
		size = d3gsiz(PD_LENGTH(*ent2));
		PD_VALUE(*ent2)  = (int *)d3allc(memno, size * sizeof(int));
		cp_1 = (char **)PD_VALUE(ent1);
		cp_2 = (char **)PD_VALUE(*ent2);

		for (i=0; i<PD_LENGTH(*ent2); i++)
			*cp_2++ = d3csav(memno, *cp_1++);
		break;

	case	DS_STR:
		*ent2 = (DMEM *)d3allc(memno, sizeof(DMEM));
		PD_NAME(*ent2)   = d3csav(memno, PD_NAME(ent1));
		PD_MODE(*ent2)   = PD_MODE(ent1);
		PD_LENGTH(*ent2) = PD_LENGTH(ent1);
		size = d3gsiz(PD_LENGTH(*ent2) + PD_DIRHD);
		PD_VALUE(*ent2)  = (int *)d3allc(memno, size * sizeof(DMEM));

		memcpy((char *)PD_VALUE(*ent2), (char *)PD_VALUE(ent1),
				(PD_LENGTH(ent1) + PD_DIRHD) * sizeof(DMEM));
					/* ent1 --> *ent2 */
		p1 = (DMEM *)PD_VALUE(ent1) + 2;
		p2 = p1 + PD_LENGTH(ent1);
		p3 = (DMEM *)PD_VALUE(*ent2) + 2;
		for (; p1<p2; p1++, p3++) {
			if ((err = d3lcpy(memno, p1, &p)) != 0)
				return(err);
			PD_NAME(p3)   = PD_NAME(p);
			PD_MODE(p3)   = PD_MODE(p);
			PD_LENGTH(p3) = PD_LENGTH(p);
			PD_VALUE(p3)  = PD_VALUE(p);
		}

		break;

	case	DS_N:
	case	DS_MISS:
		*ent2 = (DMEM *)d3allc(memno, sizeof(DMEM));
		PD_NAME(*ent2)   = d3csav(memno, PD_NAME(ent1));
		PD_MODE(*ent2)   = PD_MODE(ent1);
		PD_LENGTH(*ent2) = 0;
		PD_VALUE(*ent2)  = NULL;
		break;

	default:
		return(DE_DS_FORMAT);
	}

 	return(0);
}
