/*		FILE DESCRIPTION

			FILE NAME: d3erms.c
			ROUTINE	 : error messege data
			REVITION : 
			REMARKS	 :	87.10.22	S.AIZAWA

*/

#include	<stdio.h>
#include	"la_ws/include/d2lerr.h"
#include	"d3msgd.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3erms.c
		ROUTINE		: エラーメッセージ取り出し
		INPUT		: msg_no  = エラーコード
		OUTPUT		: msg_buf = メッセージ
*/

d3erms(msg_no, msg_buf)
int	msg_no;
char	*msg_buf;
{
	int	i;

	if (msg_no == -1)
		*msg_buf == NULL;
	else {
		for (i = 0; i < msg_tbl_sz; i++) {
			if (msg_no == msg_tbl[i].msgno)
				break;
		}
		if (i == msg_tbl_sz)
			sprintf(msg_buf, pdsys_err, msg_no);
		else
			strcpy(msg_buf, msg_tbl[i].msg);
	}
}
