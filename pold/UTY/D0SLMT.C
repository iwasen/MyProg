/*	FILE DESCRIPTION

		FILE NAME	: d0slmt.c
		ROUTINE		: 説明部ラベル部の保守
		REVISION	:
		REMARKS		: created	88.06.15	s.aizawa
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3libc.h"
#include	"d3data.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d0slmt()
		ROUTINE		: 説明部ラベル部の保守
		INPUT		: none
		OUTPUT		: none
*/

d0slmt()
{
	static	char	*func[8] = { 	/* description of func-key */
		"説明部", "ラベル部", NULL, NULL,
		"条件検索", "file一覧", "LADB変更", NULL
	};
	static	char	title[] = "＜　説明部・ラベル部の保守　＞";

	int	loop_sw;		/* ループスイッチ */
	int	err;			/* エラーコード */
	chtype	ch;			/* 入力キー */
	FILE	*fp;			/* ファイルポインタ */
	char	dataset[14];		/* ＬＡデータセット名 */
	char	tmp_file[128];		/* テンポラリファイル名 */
	char	s[128], s2[128];	/* ワークバッファ */

	loop_sw = 1;			/* ループスイッチＯＮ */
	dataset[0] = '\0';		/* データセット名クリア */

	tmpnam(tmp_file);		/* テンポラリファイル名作成 */

	while (loop_sw) {
		werase(pad);		/* 画面消去 */
		d3pned(2);		/* 処理名表示 */
		d3dred();		/* カレントディレクトリ表示 */
		d3fcmk(func);		/* ファンクションキー表示 */

		mvwaddstr(pad, 5, 10, "ＬＡデータセット名  : ");
		pnoutrefresh(pad,0,0,0,0,l_num,80); /* display screen */

		ch = d3chin(5, 32, 12, dataset);
		switch (ch) {
		case KEY_F(1):		/* 説明部 */
			if (dataset[0] == '\0') {	/* 未入力 ? */
				break;
			}

			if (d3xdat(dataset) == 0) {
				d3errw(E_DSNAME);
				break;
			}

			sprintf(s, "%s/%s", cur_ladb, dataset);

			d3slmt(cur_ladb, dataset, 0, title);
			break;
		case KEY_F(2):		/* ラベル部 */
			if (dataset[0] == '\0') {	/* 未入力 ? */
				break;
			}

			if (d3xdat(dataset) == 0) {
				d3errw(E_DSNAME);
				break;
			}

			sprintf(s, "%s/%s", cur_ladb, dataset);

			d3slmt(cur_ladb, dataset, 1, title);
			break;
		case KEY_F(5):		/* 条件検索 */
			d0cdrf(tmp_file);
			if ((fp = fopen(tmp_file, "r")) == 0)
				break;
			if (fgets(dataset, sizeof(dataset), fp) != 0)
				dataset[strlen(dataset) - 1] = '\0';
			fclose(fp);
			unlink(tmp_file);	/* テンポラリファイル削除 */
			break;
		case KEY_F(6):		/* file一覧 */
			sprintf(s, "%s/%s", cur_ladb,
				dataset[0] ? dataset : "[!A-Z]*");
			d3fsel(s, s2, 0);
			if (s2[0] != '\0')
				strcpy(dataset, s2);
			break;
		case KEY_F(7):		/* LADB変更 */
			d0whre();
			break;
		case KEY_F(17):		/* 強制終了 */
		case KEY_F(18):		/* 正常終了 */
			loop_sw = 0;	/* ループスイッチＯＦＦ */
			break;
		}
	}
}

