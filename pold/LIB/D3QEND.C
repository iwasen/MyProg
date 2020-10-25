/*	FILE DESCROPTION
 *
 *		FILE NAME	: d3qend.c
 *		ROUTINE		: 全データセット使用終了
 *		REVISION	:
 *		REMARKS		: 88.06.08	s.aizawa
 */

#include	"d3lbgn.h"

static	char	sccsid[]="@(#)d3qend.c 1.2 88/06/12 15:04:10";
extern	DSET	ds_mrec[];		/* データセット管理テーブル */

/*	FUNCTION DESCRIPTION
 *		
 *		FUNC.NAME	: d3qend()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 *		REMARKS		:
 */

d3qend()
{
	int	i;

	for (i = 0; i < DSET_TBL_MAX; i ++) {
	 	if (ds_mrec[i].ds_pmode != 0) {		/* free table ? */
			d3lend(&ds_mrec[i], "q", 0);
		}
	}
}
