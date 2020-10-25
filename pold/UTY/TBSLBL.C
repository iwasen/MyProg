 /*
 * File		: tbslbl.c
 * Comment	: Data entry for slk labels
 *			(parts replaced in UNIX file manager)
 *
 * --- Modify history ---
 * No.   Date      Name        Description                       Report No
 * --- --------  --------  ------------------------------------  ---------
 *  2. 88/04/19  M.Hosho   for options (No_delete_line etc.)
 *  1. 88/04/07  M.Hosho   created
 *
 */

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/* ソフトキー・ラベル（システム用） */
/* for 1st page on chapter 1 */
#define SYS_F01	"一覧印字"
#define SYS_F02 "条件検索"
#define SYS_F03 "        "
#define SYS_F04 "選択解除"
#define SYS_F05 "属性設定"
#define SYS_F06 "        "
#define SYS_F07 "        "

/* for 2nd page on chapter 1 */
#define SYS_F11	" 行削除 "
#define SYS_F12	" 行COPY "
#define SYS_F13	"一覧印字"
#define SYS_F14 "rec 選択"
#define SYS_F15 " 列操作 "
#define SYS_F16 "条件検索"
#define SYS_F17 "        "

/* for 3rd page on chapter 1 */
#define SYS_F21	" 列削除 "
#define SYS_F22	" 列追加 "
#define SYS_F23 "        "
#define SYS_F24 "rec 選択"
#define SYS_F25 " 行操作 "
#define SYS_F26 "        "
#define SYS_F27 "        "

int	tb_slk1[8] = {
	7,
	(int)SYS_F01, (int)SYS_F02, (int)SYS_F03, (int)SYS_F04,
	(int)SYS_F05, (int)SYS_F06, (int)SYS_F07
	};

int	tb_slk2[8] = {
	7,
	(int)SYS_F11, (int)SYS_F12, (int)SYS_F13, (int)SYS_F14,
	(int)SYS_F15, (int)SYS_F16, (int)SYS_F17
	};

int	tb_slk3[8] = {
	7,
	(int)SYS_F21, (int)SYS_F22, (int)SYS_F23, (int)SYS_F24,
	(int)SYS_F25, (int)SYS_F26, (int)SYS_F27
	};
