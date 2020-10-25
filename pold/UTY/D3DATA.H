/*	FILE DESCRIPTION
 * ----- %Z%%M% %I% %E% %U% -----

		FILE NAME	: d0data.h
		ROUTINE		: 共通データ
		REVISION	:
		REMARKS		:	88.05.10	S.Aizawa
*/

#define 	RST_MAX 3000		/* 結果テーブルサイズ */

extern	short 	rst_tbl[RST_MAX+1];	/* result table */
extern	char	home_pold[128];		/* directory name from $Y_PDHOME */
extern	char	home_ladb[128];		/* directory name from $Y_LADB */
extern	char	home_grp[16];		/* group name from $Y_DSGRP */
extern	char	home_dir[128];		/* cur working dir from getcwd() */
extern	char	cur_ladb[128];		/* current ladb directory */
extern	char	cur_grp[128];		/* current group */
extern	char	cmd_b[512];		/* コマンド編集バッファ */
extern	WINDOW	*pad;			/* common pad */
extern	WINDOW	*w1;			/* error message pad */
extern	int	l_num;			/* number of user line */
