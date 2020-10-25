/*	FILE DESCRIPTION

		FILE NAME	: d3item.c
		ROUTINE		: 項目一覧表示・選択
		REVISION	:
		REMARKS		:	88.04.19	S.Aizawa
*/

#include	<jcurses.h>
#include	"functn.h"
#include	"d3item.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3item.c
		ROUTINE		: メイン処理
		INPUT		: n_item = 項目数
				: ptr    = 項目テーブルポインタ
				: width  = cell width
				: mode   = 動作モード
		INPUT/OUTPUT	: c_pos  = current reverse position
		OUTPUT		: return value = input pf key code
*/

chtype d3item(n_item, ptr, width, mode, c_pos)
int	n_item;
D_CELL	*ptr;
int	width;
int	mode;
int	*c_pos;
{
	static	char	msg[] = "0000件  page (000/000)";
	int	page, max_page, n_page, n, i, l, c, n_col, c_pos1;
	D_CELL	*p;
	chtype	ch;
	char	s[10];

	getyx(pad, l, c);		/* get cursor position */

	n_col = 80 / width;
	n_page = n_col * (l_num - 9);
	max_page = (n_item - 1) / n_page;	/* set max page */

	sprintf(s, "%4d", n_item);	/* item number display data */
	strncpy(&msg[0], s, 4);
	sprintf(s, "%3d", max_page+1);	/* set max page display data */
	strncpy(&msg[18], s, 3);

	c_pos1 = *c_pos % n_page;
	page = *c_pos / n_page;
	for (;;) {
		sprintf(s, "%3d", page+1);	/* page display data */
		strncpy(&msg[14], s, 3);
		mvwaddstr(pad, 3, 57, msg);	/* display item number & page */
		wmove(pad, l, c);		/* set cursor position */

		n = (page == max_page) ? n_item - page * n_page : n_page;
		p = ptr + page * n_page;

		 /* display & select item */
		ch = d3sitm(n, p, width, n_col, mode, &c_pos1
				, page * n_page + 1);
		switch (ch) {
		case KEY_NPAGE:			/* next page */
		case KEY_F(25):			/* 上スクロール */
			if (page != max_page)
				page++;
			else
				page = 0;
			c_pos1 = 0;
			break;
		case KEY_PPAGE:			/* prev page */
		case KEY_F(26):			/* 下スクロール */
			if (page != 0)
				page--;
			else
				page = max_page;
			c_pos1 = 0;
			break;
		case KEY_F(5):			/* zen sitei */
			if (mode & IT_ALL) {
				if (n_item != 0) {
					for (i = 0; i < n_item; i++)
						ptr[i].flag |= 0x01;
				}
			} else
				goto ret;
			break;
		case KEY_F(6):			/* zen kaijo */
			if (mode & IT_ALL) {
				if (n_item != 0) {
					for (i = 0; i < n_item; i++)
						ptr[i].flag &= ~0x01;
				}
			} else
				goto ret;
			break;
		case KEY_F(19):		/* 前フィールド */
			if (page != 0) {
				page--;
				c_pos1 = n_page - 1;
			}
			break;
		case KEY_F(20):		/* 次フィールド */
			if (page * n_page + c_pos1 + 1 < n_item) {
				page++;
				c_pos1 = 0;
			}
			break;
		case KEY_F(21):		/* 上フィールド */
			if (page != 0) {
				page--;
				c_pos1 += n_page - n_col;
			}
			break;
		case KEY_F(22):		/* 下フィールド */
			if (page * n_page + c_pos1 + n_col < n_item) {
				page++;
				c_pos1 -= n_page - n_col;
			}
			break;
		default:
		ret:
			*c_pos = page * n_page + c_pos1;
			return(ch);
		}
	}
}


