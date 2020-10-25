/*	FILE DESCRIPTION

		FILE NAME	: d3rslt
		ROUTINE		: 結果一覧表示
		REVISION	:
		REMARKS		:	88.04.22	S.Aizawa
*/

#include	<jcurses.h>
#include	"functn.h"
#include	"d3item.h"
#include	"la_ws/include/d2lerr.h"

extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3rslt.c
		ROUTINE		: メイン処理
		INPUT		: n_item = 項目数
				: p_item = 項目テーブルポインタ
		OUTPUT		: return value = error code
*/

d3rslt(n_item, p_item)
int	n_item;
D_CELL	*p_item;
{
	static	char	title[] = "処 理 結 果 一 覧";
	static	char	imes[] = "確認後、リターンキーを押してください　";
	static char *func[8] = {
		NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL
	};
	int	i, j, n_rslt, mgno, c_pos, len;
	D_CELL	*p_rslt, *ip, *rp;
	chtype	ch;
	char	s[128];

	for (i = 0, n_rslt = 0; i < n_item; i++) {
		if (p_item[i].flag & 0x01)
			n_rslt++;
	}

	if (n_rslt == 0)
		return(0);

	if ((mgno = d3mgno()) < 0)
		return(E_M);

	if ((p_rslt = (D_CELL *)d3allc(mgno, sizeof(D_CELL) * n_rslt)) == 0) {
		d3free(mgno);
		return(E_M);
	}

	for (i = 0, ip = p_item, rp = p_rslt; i < n_item; i++, ip++) {
		if (ip->flag & 0x01) {
			for (j = 0; j < 20; j++)
				s[j] = ' ';
			len = strlen(ip->ptr);
			if (len > 14)
				len = 14;
			strncpy(s, ip->ptr, len);
			d3erms((int)ip->result, s + 16);
			if ((rp->ptr = d3allc(mgno, strlen(s) + 1)) == 0) {
				d3free(mgno);
				return(E_M);
			}
			strcpy(rp->ptr, s);
			rp->flag = rp->result = 0;
			rp++;
		}
	}

	wmove(pad,2,0);
	wclrtobot(pad);			/* erase window */

	mvwaddstr(pad, 3, 26, title);
	mvwaddstr(pad, l_num-3, 2, imes);
	d3fcmk(func);

	c_pos = 0;
	for (;;) {
		ch = d3item(n_rslt, p_rslt, 68, IT_CEN, &c_pos);
		if (ch == '\n' || ch == KEY_F(17) || ch == KEY_F(18))
			break;
	}
	d3free(mgno);
	return(0);
}

