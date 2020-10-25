/*		FILE DESCRIPTION

		FILE NAME: d3keka.c
		ROUTINE	 : 
		REVISION :
		REMARKS	 :	88.05.13	S.AIZAWA

*/

#include	"d3keka.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

		func.name: d3keka()
		input	 : jfile	opened display file
			   kekatbl	table for error message
		output	 : none
		remarks  : 	88.05.13	S.AIZAWA

*/
d3keka( jfile , kekatbl )
FILE	*jfile;
short	*kekatbl;
{
	int	mgno;			/* メモリ番号 */
	D_CELL	*ptr_item;		/* 項目テーブルポインタ */
	int	n_item;			/* 項目数 */
	int	i, err;

	if ((mgno = d3mgno()) < 0)	/* メモリ番号取り出し */
		return;

	rewind(jfile);
	err = d3fit2(jfile, mgno, &ptr_item, &n_item); /* make item table */
	if (err != 0) {
		d3free(mgno);
		d3errw(err);
		return;
	}

	for (i = 0; i < n_item; i++) {
		if ((ptr_item[i].result = kekatbl[i]) != -1)
			ptr_item[i].flag = 0x01;
	}

	d3rslt(n_item, ptr_item);		/* 結果一覧表示 */

	d3free(mgno);			/* メモリ解放 */
}
