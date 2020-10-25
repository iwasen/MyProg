/*		FILE DESCRIPTION

			FILE NAME: d3msgh.c
			ROUTINE	 : メッセージ通知
			REVITION : 
			REMARKS	 :	88.06.08	S.AIZAWA

*/

#include	<stdio.h>
#include	"la_ws/include/d2lerr.h"
#include	"d3msgd.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3msgh.c
		ROUTINE		: エラーメッセージ通知
		INPUT		: msg_no = エラーコード
		OUTPUT		: argv   = パラメータ
*/

d3msgh(msg_no, argv)
char	*argv;
{
	int	i;
	char	errcd[6];

	for (i = 0; i < msg_tbl_sz; i++) {
		if (msg_no == msg_tbl[i].msgno)
			break;
	}
	if (i == msg_tbl_sz || msg_tbl[i].type == 0)
		return;

	sprintf(errcd, "41%.2x%c", msg_no, msg_tbl[i].type);

	lbersn(errcd, msg_tbl[i].argc, &argv);
}

