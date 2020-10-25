/*	FILE DESCRIPTION

		FILE NAME	: d3sitm.c
		ROUTINE		: 項目一覧表示・選択（１画面処理）
		REVISION	:
		REMARKS		:	88.04.19	S.Aizawa
*/

#include	<jcurses.h>
#include	"functn.h"
#include	"d3item.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	D_CELL	*ptr_s;
static	int	width_s, n_col_s, mode_s, lno_s;

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3sitm()
		ROUTINE		: メイン処理
		INPUT		: n_item = 項目数
				: ptr    = 項目テーブルポインタ
				: width  = cell width
				: mode   = 動作モード
		INPUT/OUTPUT	: c_pos  = current reverse position
		OUTPUT		: return value = input pf key code
*/

chtype d3sitm(n_item, ptr, width, n_col, mode, c_pos, lno)
int	n_item;
D_CELL	*ptr;
int	width;
int	n_col;
int	*c_pos;
int	lno;
{
	int	i, j, n, l, c, rev_x, rev_y, no;
	chtype	ch;
	D_CELL	*p;
	char	s[10];

	ptr_s = ptr;
	width_s = width;
	n_col_s = n_col;
	mode_s = mode;
	lno_s = lno;

	getyx(pad, l, c);		/* get cursor position */

	for (i = 5; i < l_num-4; i++) {
		wmove(pad, i, 0);
		wclrtoeol(pad);		/* clear screen */
	}

	n = n_item;
	for (i = 0; i < l_num-9 && n != 0; i++) {
		for (j = 0; j < n_col && n != 0; j++) {
			sitm10(i, j);	/* display 1 item */
			n--;
		}
	}

	rev_x = *c_pos % n_col;
	rev_y = *c_pos / n_col;
	if (n_item != 0 && (mode & IT_NSL) == 0)
		sitm20(rev_y, rev_x, A_REVERSE);	/* reverse (0,0) */

	if ((mode & IT_DSP) != 0) {
		prefresh(pad,0,0,0,0,l_num,80);
		wattrset(pad, 0);	/* clear attribute */
		return(0);
	}

	for (;;) {
		prefresh(pad,0,0,0,0,l_num,80);
		/* get 1 character */
		s[0] = '\0';
		ch = d3chin(l, c, (mode & IT_NUM) && n_col == 1 ? 4 : 0, s);
		if (s[0] != '\0') {
			no = atoi(s) - lno;
			if (no >= 0 && no < n_item) {
				sitm20(rev_y, rev_x, 0);
				rev_x = no % n_col;
				rev_y = no / n_col;
				sitm20(rev_y, rev_x, A_REVERSE);
			} else {
				beep();
				continue;
			}
		}
		switch (ch) {		/* check input character */
		case KEY_UP:
		case KEY_F(21):		/* 上フィールド */
			if ((mode & IT_NSL) || n_item == 0) {
				break;
			}
			sitm20(rev_y, rev_x, 0);
			if (rev_y != 0)
				rev_y--;
			else {
				if (ch == KEY_UP)
					rev_y = (n_item - rev_x - 1) / n_col;
				else
					goto case_return;
			}
			sitm20(rev_y, rev_x, A_REVERSE);
			break;
		case KEY_DOWN:
		case KEY_F(22):		/* 下フィールド */
			if ((mode & IT_NSL) || n_item == 0) {
				break;
			}
			sitm20(rev_y, rev_x, 0);
			if ((rev_y+1)*n_col+rev_x < n_item)
				rev_y++;
			else {
				if (ch == KEY_DOWN)
					rev_y = 0;
				else
					goto case_return;
			}
			sitm20(rev_y, rev_x, A_REVERSE);
			break;
		case KEY_LEFT:
		case KEY_F(19):		/* 前フィールド */
			if ((mode & IT_NSL) || n_item == 0) {
				break;
			}
			sitm20(rev_y, rev_x, 0);
			if (rev_x != 0)
				rev_x--;
			else {
				if (ch == KEY_LEFT) {
					rev_x = (rev_y == n_item / n_col) ?
						n_item % n_col - 1 : n_col - 1;
				} else {
					if (rev_y != 0) {
						rev_x = n_col - 1;
						rev_y--;
					} else
						goto case_return;
				}
			}
			sitm20(rev_y, rev_x, A_REVERSE);
			break;
		case KEY_RIGHT:
		case KEY_F(20):		/* 次フィールド */
			if ((mode & IT_NSL) || n_item == 0) {
				break;
			}
			sitm20(rev_y, rev_x, 0);
			if (rev_x+1 < n_col && rev_y*n_col+rev_x+1 < n_item)
				rev_x++;
			else {
				if (ch == KEY_RIGHT)
					rev_x = 0;
				else {
					if (rev_y*n_col+rev_x+1 < n_item) {
						rev_x = 0;
						rev_y++;
					} else
						goto case_return;
				}
			}
			sitm20(rev_y, rev_x, A_REVERSE);
			break;
		case KEY_HOME:
			if ((mode & IT_NSL) || n_item == 0) {
				break;
			}
			sitm20(rev_y, rev_x, 0);
			rev_x = rev_y = 0;
			sitm20(rev_y, rev_x, A_REVERSE);
			break;
		case 0x0a:		/* line feed */
			if (mode & IT_MUL) {
				if ((mode & IT_NSL) || n_item == 0) {
					break;
				}
				p = ptr + rev_y * n_col + rev_x;
				p->flag ^= 0x01;
				sitm10(rev_y, rev_x);
				break;
			}
			/* no break */
		case KEY_F(1):		/* F1 */
		case KEY_F(2):		/* F2 */
		case KEY_F(3):		/* F3 */
		case KEY_F(4):		/* F4 */
		case KEY_F(5):		/* F5 */
		case KEY_F(6):		/* F6 */
		case KEY_F(7):		/* F7 */
		case KEY_F(8):		/* F8 */
		case KEY_F(17):		/* f9 （強制終了） */
		case KEY_F(18):		/* f10 （正常終了） */
		case KEY_F(23):		/* 左スクロール */
		case KEY_F(24):		/* 右スクロール */
		case KEY_F(25):		/* f17 （上スクロール） */
		case KEY_F(26):		/* f18 （下スクロール） */
		case KEY_NPAGE:		/* next page */
		case KEY_PPAGE:		/* prev page */
		case_return:
			if ((mode & IT_NSL) == 0 && n_item != 0) {
				sitm20(rev_y, rev_x, 0);
				wmove(pad, l, c);
				prefresh(pad,0,0,0,0,l_num,80);
			}
			*c_pos = rev_y * n_col + rev_x;
			return(ch);
		}
	}
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: sitm10()
		ROUTINE		: １項目表示
		INPUT		: y   = 行位置
				: x   = 桁位置
		OUTPUT		: none
*/

static
sitm10(y, x)
int	y, x;
{
	D_CELL	*p;
	int	i, len, offset, sp, flen, no;
	char	s[80], w[6];

	no = y * n_col_s + x;
	p = ptr_s + no;			/* set item table pointer */
	if (mode_s & IT_CEN)
		offset = (80 - (width_s * n_col_s)) / 2;
	else
		offset = 0;

	for (i = 0; i < width_s - 2; i++)
		s[i] = ' ';		/* clear display buffer */
	s[width_s - 2] = '\0';

	if ((p->flag & 0x01) != 0)
		s[0] = '*';		/* set select mark */

	if ((mode_s & IT_NUM) && n_col_s == 1) {
		sprintf(w, "%4d", lno_s + no);
		strncpy(&s[1], w, 4);
		flen = width_s - 9;
		sp = 7;
	} else {
		flen = width_s - 4;
		sp = 2;
	}

	len = strlen(p->ptr);		/* get length */
	if (len > flen)
		len = flen;
	strncpy(s+sp, p->ptr, len);	/* set item */

	mvwaddstr(pad, y+5, x*width_s+offset, s);	/* display 1 item */
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: sitm20()
		ROUTINE		: １項目反転表示
		INPUT		: y    = 行位置
				: x    = 桁位置
				: attr = 表示属性
		OUTPUT		: none
*/

static
sitm20(y, x, attr)
int	y, x;
chtype	attr;
{
	wattrset(pad, attr);
	sitm10(y, x);
}

