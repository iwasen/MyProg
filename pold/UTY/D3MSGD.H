/* ----- %Z%%M% %I% %E% %U% ----- */
/*		FILE DESCRIPTION

			FILE NAME: d3msgd.h
			ROUTINE	 : メッセージテーブル定義
			REVITION : 
			REMARKS	 :	88.06.08	S.AIZAWA

*/


/*	メッセージテーブル	*/

struct	msgtbl {
	int	msgno;		/* メッセージ番号 */
	char	type;		/* メッセージ種別 */
	int	argc;		/* パラメータの個数 */
	char	*msg;		/* メッセージデータ */
};

extern	struct	msgtbl	msg_tbl[];	/* メッセージテーブル */
extern	int	msg_tbl_sz;		/* メッセージテーブルサイズ */
extern	char	pdsys_err[];		/* ＰＯＬ－Ｄシステムエラー */
