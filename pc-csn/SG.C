/************************************************************************
 *									*
 *		新ＶＳＥシステム　ＰＣ－ＣＮＳプログラム		*
 *									*
 *		名称		: ＳＧ関連処理				*
 *		ファイル名	: sg.c					*
 *		作成日		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*=======================================================================
 |
 |		コンソールＳＧファイル検索
 |
 |	int	find_csg()
 |
 |		返値			０：見つかった　１：みつからない
 |
 =======================================================================*/
int	find_csg()
{
	struct	find_t	find;	/* ファイル検索用エリア */
	int	st;		/* ファイル検索結果 */

	st = _dos_findfirst("*.CSG", 0, &find);
	if (st == 0)
		strcpy(csg_file, find.name);	/* ファイル名セーブ */

	return(st);
}

/*=======================================================================
 |
 |		コンソールＳＧファイル読み込み
 |
 |	void	read_csg()
 |
 =======================================================================*/
void	read_csg()
{
	FILE	*fp;		/* ファイルポインタ */

	/* コンソールＳＧファイル読み込み */
	if (csg_file[0] != '\0' && (fp = fopen(csg_file, "rb")) != NULL) {
		fread(&csg, sizeof(CSG), 1, fp);
		fclose(fp);
	} else
		csg = csg_default;

	/* システム絵画面ファイル読み込み */
	if (csg.nsg.grf_file[0] != '\0'
			&& (fp = fopen(csg.nsg.grf_file, "rb")) != NULL) {
		fread(csg_grf, sizeof(csg_grf), 1, fp);
		fclose(fp);
	} else {
		/* 絵画面ファイルが無い場合 */
		memset(csg_grf, 0, sizeof(csg_grf));
		grfputs(2, 1, "Ｗｅｌｃｏｍｅ", C_GREEN, csg_grf);
		grfputs(6, 4, csg.nsg.sys_name, C_RED, csg_grf);
		grfputs(14, 7, "システム", C_GREEN, csg_grf);
		grfputs(1, 11, "?", C_BLUE, csg_grf);
		grfputs(7, 11, "?", C_BLUE, csg_grf);
		grfputs(13, 11, "?", C_BLUE, csg_grf);
		grfputs(19, 11, "?", C_BLUE, csg_grf);
		grfputs(25, 11, "??", C_BLUE, csg_grf);
	}
}

/*=======================================================================
 |
 |		コンソールＳＧファイル書き込み
 |
 |	void	write_csg()
 |
 =======================================================================*/
void	write_csg()
{
	FILE	*fp;		/* ファイルポインタ */

	/* コンソールＳＧファイル書き込み */
	if (csg_file[0] != '\0' && (fp = fopen(csg_file, "wb")) != NULL) {
		fwrite(&csg, sizeof(CSG), 1, fp);
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		システムＳＧファイル検索
 |
 |	int	find_ssg(conxxxtion)
 |
 |		int	conxxxtion;	コネクション番号
 |
 |		返値			０：見つかった　１：みつからない
 |
 =======================================================================*/
int	find_ssg(int conxxxtion)
{
	struct	find_t	find;	/* ファイル検索用エリア */
	char	con_no[4];	/* コネクション番号 */
	int	st;		/* ファイル検索結果 */

	/* コネクション番号を文字列に変換 */
	sprintf(con_no, "%02d.", conxxxtion);

	/* ファイル検索 */
	st = _dos_findfirst("*.SSG", 0, &find);
	while (st == 0) {
		if (strstr(find.name, con_no) != NULL)
			break;
		st = _dos_findnext(&find);
	}
	if (st == 0)
		strcpy(ssg_file, find.name);	/* ファイル名セーブ */

	return(st);
}

/*=======================================================================
 |
 |		システムＳＧファイル読み込み
 |
 |	void	read_ssg()
 |
 =======================================================================*/
void	read_ssg()
{
	FILE	*fp;		/* ファイルポインタ */

	/* コンソールＳＧファイル読み込み */
	if (ssg_file[0] != '\0' && (fp = fopen(ssg_file, "rb")) != NULL) {
		fread(&ssg, sizeof(SSG), 1, fp);
		fclose(fp);
	} else
		memset(&ssg, 0, sizeof(ssg));

	/* システム絵画面ファイル読み込み */
	if (ssg.nsg.grf_file[0] != '\0'
			&& (fp = fopen(ssg.nsg.grf_file, "rb")) != NULL) {
		fread(ssg_grf, sizeof(ssg_grf), 1, fp);
		fclose(fp);
	} else {
		/* 絵画面ファイルが無い場合 */
		memset(ssg_grf, 0, sizeof(ssg_grf));
		grfputs(6, 4, "【", C_BLUE, ssg_grf);
		grfputs(10, 4, ssg.nsg.sys_name, C_CYAN, ssg_grf);
		grfputs(22, 4, "】", C_BLUE, ssg_grf);
		grfputs(4, 8, csg.nsg.sys_name, C_YELLOW, ssg_grf);
		grfputs(14, 10, "システム", C_YELLOW, ssg_grf);
	}
}

/*=======================================================================
 |
 |		システムＳＧファイル書き込み
 |
 |	void	write_ssg()
 |
 =======================================================================*/
void	write_ssg()
{
	FILE	*fp;		/* ファイルポインタ */

	/* コンソールＳＧファイル書き込み */
	if (ssg_file[0] != '\0' && (fp = fopen(ssg_file, "wb")) != NULL) {
		fwrite(&ssg, sizeof(SSG), 1, fp);
		fclose(fp);
	}
}
