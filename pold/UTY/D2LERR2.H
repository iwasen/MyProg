/*	FILE DESCRIPTION
 * ----- %Z%%M% %I% %E% %U% -----

		FILE NAME: ERCODE.H
		ROUTINE	 : HEADER FILE
		REVISION :
		REMARKS	 :		87.10.21		N.MATSUO

*/

/*		エラーコード形式

		8 bit code
		x x x x x x x x
		----- ---------
		  |	  |
		  |	  ---ファイル種別
		  |
		  -----------エラー種別
				＝０：ファイル以外のエラー
				　０：ファイルに関するエラー
*/

/*------------ ファイルに関するエラー ----------------*/

/*	エラー種別	*/

#define	E_FN	0x20		/* ファイルが無い */
#define	E_FA	0x40		/* アクセスエラー */
#define	E_FU	0x60		/* 上書きエラー */
#define	E_FF	0x80		/* フォーマットエラー */
#define	E_FL	0xa0		/* ロックエラー */

/*	ファイル種別	*/

#define	F_FL	0x00		/* ファイル */
#define	F_GI	0x01		/* グループ索引ファイル */
#define	F_IF	0x02		/* 索引ファイル */
#define	F_DS	0x03		/* ＬＡデータセット */
#define	F_UF	0x04		/* ユーザ索引ファイル */
#define	F_EF	0x05		/* 説明部ファイル */
#define	F_LF	0x06		/* ラベル部ファイル */
#define	F_RF	0x07		/* 生データファイル */
#define	F_AF	0x08		/* 解析データファイル */
#define	F_DF	0x09		/* ＬＡデータファイル */
#define	F_KF	0x0a		/* 固定欄形式ファイル */
#define	F_GF	0x0b		/* グループ索引エディットファイル */
#define	F_UE	0x0c		/* ユーザ索引エディットファイル */
#define	F_TF	0x0d		/* テンポラリファイル */
#define	F_CF	0x0e		/* カレントファイル */
#define	F_KJ	0x0f		/* 検索条件ファイル */
#define	F_GH	0x10		/* 外部表現形式ファイル */
#defile	F_BK	0x11		/* バックアップファイル */


/*-------------- ファイル以外のエラー ----------------*/

#define		E_CLEAR		-1	/*  error messege clear  */
#define		NOERR		0x00	/*  OK  */
#define		E_X		0x01	/*  command error */
#define		E_M		0x02	/*  memory error  */
#define		E_P		0x03	/*  parameter error  */
#define		E_D		0x04	/*  data error  */
#define		E_S		0x05	/*  save error  */
#define		E_E		0x06	/*  quit  */
#define		E_C		0x07	/*  curses error  */
#define		E_K		0x08	/*  kouho nasi	*/
#define		E_N		0x09	/*  directory can't make  */
#define		E_GRP_NONE_ENTRY 0x0a	/* none entry group sakuin file */
#define		E_DSNAME	 0x0b	/* no correct data set name */

