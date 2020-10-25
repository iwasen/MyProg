/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2dbrs.c
 *		ROUTINE		: ＬＡデータベース復元処理
 *		REVISION	:
 *		REMARKS		: 88.02.19
 */

#include	<stdio.h>
#include	<string.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d2lerr.h"

#define	SV_FILE	".TSAVE"

extern	DSET	*d3lbgn();
extern	struct	dsm	*d3gcmp(), *d3gtra();

static char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			復元メイン処理
 *
 *		FUNC.NAME	: d2dbrs()
 *		INPUT		: ladir = ディレクトリ名
 *				: dev = デバイス名
 *		OUTPUT		: return value = error code
 */

d2dbrs(ladir, dev)
char	*ladir, *dev;
{
	int	rst;
	char	current_dir[128];

	getcwd(current_dir, 128);	/* get current directory */
	if (chdir(ladir) != 0)
		return(E_S);

	rst = dbrs10(dev);

	unlink(SV_FILE);
	chdir(current_dir);

	return(rst);
}

/*	FUNCTION DESCRIPTION
 *
 *			ファイル読み込み＆索引ファイル更新
 *
 *		FUNC.NAME	: dbrs10()
 *		INPUT		: dev = デバイス名
 *		OUTPUT		: return value = error code
 */

static
dbrs10(dev)
char	*dev;
{
	FILE	*fp;
	int	err;
	char	bru_cmd[64];
	char	buf[128];

	if (dev == 0)
		sprintf(bru_cmd, "bru -xv -ur");
	else
		sprintf(bru_cmd, "bru -xvf %s -ur", dev);

	err = system(bru_cmd);

	if (err != 0 && err != 0x200)
		return(E_S);


	if ((fp = fopen(SV_FILE, "r")) == NULL)
		return(d3cver(DE_FILE_IO, "tf"));

	fgets(buf, 128, fp);
	switch (atoi(buf)) {	/* 保存種別チェック */
	case 1:			/* 全ＬＡデータセット */
		err = dbrs20();
		break;
	case 2:			/* 全ユーザ索引 */
		err = d2idmk(".", "IND.USR");
		break;
	case 4:			/* 個別グループ */
		err = dbrs30(fp);
		break;
	case 5:			/* 個別ＬＡデータセット */
	case 6:			/* 個別ユーザ索引ファイル */
		err = dbrs40(fp);
		break;
	default:
		err = 0;
	}

	fclose(fp);

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			全索引ファイル更新
 *
 *		FUNC.NAME	: dbrs20()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
dbrs20()
{
	FILE	*fp;
	char	*p, if_name[128];

	/* pipe open */
	if ((fp = popen("find . -name 'IND.*' -print", "r")) == 0)
		return(d3cver(DE_FILE_IO, "tf"));

	while (fgets(if_name, 128, fp) != 0) {
		if_name[strlen(if_name)-1] = '\0';
		if ((p = strrchr(if_name, '/')) != 0)
			p++;
		else
			p = if_name;
		d2idmk(".", p);
	}

	pclose(fp);

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			索引ファイル更新
 *
 *		FUNC.NAME	: dbrs30()
 *		INPUT		: none
 *		OUTPUT		: return value = error code
 */

static
dbrs30(fp)
FILE	*fp;
{
	char	if_name[128];

	while (fgets(if_name, 128, fp) != 0) {
		if_name[strlen(if_name)-1] = '\0';
		d2idmk(".", if_name);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			個別索引ファイル更新
 *
 *		FUNC.NAME	: dbrs40()
 *		INPUT		: fp = 保存種別ファイルポインタ
 *		OUTPUT		: return value = error code
 */

static
dbrs40(fp)
FILE	*fp;
{
	int	err;
	char	ds_name[128];
	char	if_name[128];

	while (fgets(ds_name, 128, fp) != 0) {
		ds_name[strlen(ds_name)-1] = '\0';
		err = d2ichk(".", ds_name, if_name); /* get index file name */
		if (err != 0)
			return(err);
		err = d2idup(".", if_name, ds_name); /* update index file */
		if (err != 0)
			return(err);
	}

	return(0);
}

