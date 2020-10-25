/*		FILE DESCRIPTION

		FILE NAME: d3pned.c
		ROUTINE	 : 
		REVISION :
		REMARKS	 : 	87.10.21	N.MATSUO

*/
#include	"d3pned.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static char	*ptabl[12] = {	/* table for title */
	"＜　ＬＡデータ管理　＞",
	"＜  グループ索引の保守  ＞",
	"＜  説明部・ラベル部の保守  ＞",
	"＜  表データセットの保守  ＞",
	"＜  ユーザ索引の保守  ＞",
	"＜  ＬＡデータの格納  ＞",
	"＜  外部表現ファイルの保守  ＞",
	"＜  条件検索  ＞",
	"＜  ＬＡデータセットの削除  ＞",
	"＜  システム索引の保守  ＞",
	"＜  ＬＡデータセットの保存・復元  ＞",
	"＜  バックアップファイルの保守  ＞"
};
/*
static char	*ptabl[11] = {	#* table for title *#
	"＜  ＬＡデータベースの生成  ＞",
	"＜  説明部・ラベル部の保守  ＞",
	"＜  表データの保守  ＞",
	"＜  ユーザ索引の保守  ＞",
	"＜  ＬＡデータセットの登録・格納  ＞",
	"＜  ＬＡデータセットの表現形式の変換  ＞",
	"＜  条件検索  ＞",
	"＜  ＬＡデータセットの削除  ＞",
	"＜  システム索引の保守  ＞",
	"＜  ＬＡデータベースの保存・復元  ＞",
	"＜  ＬＡデータベースの変更  ＞"
};
*/

/*		FUNC. DESCRIPTION

		FUNC.NAME: d3pned()
		INPUT	 : pno	message no.
		OUTPUT	 : none
		REMARKS  : 	87.10.21	N.MATSUO

*/
d3pned(pno)
int	pno;
{
	mvwaddstr( pad , 0 , 0 , ptabl[pno] );

	/*pnoutrefresh(pad,0,0,0,0,l_num,80);*/
}

