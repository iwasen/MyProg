/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2dbsv.c
 *		ROUTINE		: ＬＡデータベース保存処理
 *		REVISION	:
 *		REMARKS		: 88.02.19
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3nmty.h"

#define	SV_FILE	".TSAVE"

extern	DSET	*d3lbgn();
extern	struct	dsm	*d3gcmp(), *d3gtra();

static char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			保存メイン処理
 *
 *		FUNC.NAME	: d2dbsv()
 *		INPUT		: ladir = ディレクトリ名
 *				: dev = デバイス名
 *				: kind = 保存種別
 *				: name = 保存ファイル名
 *				: n = 保存ファイル数
 *		OUTPUT		: return value = error code
 */

d2dbsv(ladir, dev, size, kind, name, n)
char	*ladir, *dev, *size;
int	*kind;
char	*name[];
int	*n;
{
	int	rst;
	char	current_dir[128];

	getcwd(current_dir, 128);	/* get current directory */
	if (chdir(ladir) != 0)
		return(E_S);

	rst = dbsv05(*kind, name, *n);
	if (rst == 0) {
		switch (*kind) {
		case 0:			/* 全体 */
			rst = dbsv10(dev, size);
			break;
		case 1:			/* 全ＬＡデータセット */
			rst = dbsv20(dev, size);
			break;
		case 2:			/* 全ユーザ索引ファイル */
			rst = dbsv30(dev, size);
			break;
		case 3:			/* グループ索引のみ */
			rst = dbsv40(dev, size);
			break;
		case 4:			/* 個別グループ */
			rst = dbsv50(dev, size, name, *n);
			break;
		case 5:			/* 個別ＬＡデータセット */
			rst = dbsv60(dev, size, name, *n);
			break;
		case 6:			/* 個別ユーザ索引ファイル */
			rst = dbsv70(dev, size, name, *n);
			break;
		default:
			rst = E_P;
		}
		unlink(SV_FILE);
	}

	chdir(current_dir);

	return(rst);
}

/*	FUNCTION DESCRIPTION
 *
 *			保存種別ファイル出力処理
 *
 *		FUNC.NAME	: dbsv05()
 *				: kind = 保存種別
 *				: name = 保存ファイル名
 *				: n = 保存ファイル数
 *		OUTPUT		: return value = error code
 */

static
dbsv05(kind, name, n)
int	kind;
char	*name[];
int	n;
{
	FILE	*fp;

	if ((fp = fopen(SV_FILE, "w")) == NULL)
		return(E_TF_A);

	fprintf(fp, "%d\n", kind);
	while (n--)
		fprintf(fp, "%s\n", *name++);

	fclose(fp);

	return(0);
}


/*	FUNCTION DESCRIPTION
 *
 *			全体保存処理
 *
 *		FUNC.NAME	: dbsv10()
 *		INPUT		: dev = デバイス名
 *		OUTPUT		: return value = error code
 */

static
dbsv10(dev, size)
char	*dev;
char	*size;
{
	return(dbsv85(dev, size, "-name '[!.]*'"));
}

/*	FUNCTION DESCRIPTION
 *
 *			全ＬＡデータセット保存処理
 *
 *		FUNC.NAME	: dbsv20()
 *		INPUT		: dev = デバイス名
 *		OUTPUT		: return value = error code
 */

static
dbsv20(dev, size)
char	*dev;
char	*size;
{
	DSET	*dsp;
	int	err;

	dsp = d3lbgn(GROUP_INDEX, "r", 0, &err);
	if (err != 0)
		return(d3cver(err, "gi"));

	err = dbsv25(dsp, dev, size);

	d3lend(dsp, "q", 0);

	return(err);
}

static
dbsv25(dsp, dev, size)
DSET	*dsp;
char	*dev;
char	*size;
{
	struct dsm *labp, *axi2p, *labsp, *trap;
	int	prefix, i;
	char	buf[1024*10];

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL)
		return(E_GI_F);

	if ((axi2p = d3gcmp(labp, "Axi2")) == NULL)
		return(E_GI_F);

	if ((labsp = d3gcmp(axi2p, "Labs")) == NULL)
		return(E_GI_F);

	prefix = 0;
	for (i = 0; i < labsp->length; i++) {
		if (strcmp(labsp->ptr.c[i], GRP_PREFIX) == 0) {
			prefix = i + 1;
			break;
		}
	}
	if (prefix == 0)
		return(E_GI_F);

	if ((trap = d3gtra(axi2p, prefix)) == NULL)
		return(E_GI_F);

	buf[0] = '\0';
	for (i = 0; i < trap->length; i++) {
		if (i != 0)
			strcat(buf, "-o ");
		strcat(buf, "-name '");
		strcat(buf, trap->ptr.c[i]);
		strcat(buf, "*' ");
	}

	return(dbsv85(dev, size, buf));
}

/*	FUNCTION DESCRIPTION
 *
 *			全ユーザ索引保存処理
 *
 *		FUNC.NAME	: dbsv30()
 *		INPUT		: dev = デバイス名
 *		OUTPUT		: return value = error code
 */

static
dbsv30(dev, size)
char	*dev;
char	*size;
{
	return(dbsv85(dev, size, "-name 'USR.*'"));
}

/*	FUNCTION DESCRIPTION
 *
 *			グループ索引保存処理
 *
 *		FUNC.NAME	: dbsv40()
 *		INPUT		: dev = デバイス名
 *		OUTPUT		: return value = error code
 */

static
dbsv40(dev, size)
char	*dev;
char	*size;
{
	if (d3fchk("GRP.IND") != 0)
		return(E_GI_N);

	return(dbsv80(dev, size, "GRP.IND"));
}

/*	FUNCTION DESCRIPTION
 *
 *			個別グループ保存処理
 *
 *		FUNC.NAME	: dbsv50()
 *		INPUT		: dev = デバイス名
 *				: name = 保存ファイル名
 *				: n = 保存ファイル数
 *		OUTPUT		: return value = error code
 */

static
dbsv50(dev, size, name, n)
char	*dev;
char	*size;
char	*name[];
int	n;
{
	DSET	*dsp;
	int	err;
	char	buf[1024*10];

	dsp = d3lbgn(GROUP_INDEX, "r", 0, &err);
	if (err != 0)
		return(d3cver(err, "gi"));

	buf[0] = '\0';
	while (n--) {
		if (d3fchk(*name) != 0)
			return(E_IF_N);
		strcat(buf, "-name '");
		strcat(buf, *name);
		strcat(buf, "' ");

		err = dbsv55(dsp, *name, buf);
		if (err != 0) {
			d3lend(dsp, "q", 0);
			return(err);
		}
		name++;
	}

	d3lend(dsp, "q", 0);

	return(dbsv85(dev, size, buf));
}

static
dbsv55(dsp, idx_file, bufp)
DSET	*dsp;
char	*idx_file, *bufp;
{
	struct dsm *labp, *datp, *axi2p, *labsp, *tra1p, *tra3p, *dimp, *datap;
	int	i, rn, ds_no, index_no, *dp, prefix, index;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL)
		return(E_GI_F);

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL)
		return(E_GI_F);

	if ((axi2p = d3gcmp(labp, "Axi2")) == NULL)
		return(E_GI_F);

	if ((labsp = d3gcmp(axi2p, "Labs")) == NULL)
		return(E_GI_F);

	prefix = index = 0;
	for (i = 0; i < labsp->length; i++) {
		if (strcmp(labsp->ptr.c[i], GRP_PREFIX) == 0)
			prefix = i + 1;
		else if (strcmp(labsp->ptr.c[i], GRP_INDEX) == 0)
			index = i + 1;
	}
	if (prefix == 0 || index == 0)
		return(E_GI_F);
	
	if ((tra1p = d3gtra(axi2p, prefix)) == NULL)
		return(E_GI_F);

	if ((tra3p = d3gtra(axi2p, index)) == NULL)
		return(E_GI_F);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)
		return(E_GI_F);

	if ((datap = d3gcmp(datp, "Data")) == NULL)
		return(E_GI_F);

	rn = dimp->ptr.i[0];
	dp = datap->ptr.i;

	for (i = 0; i < tra3p->length; i++) {
		if (strcmp(idx_file, tra3p->ptr.c[i]) == 0)
			break;
	}

	if (i == tra3p->length)
		return(E_GRP_NONE_ENTRY);

	index_no = i + 1;

	for (i = 0; i < rn; i++) {
		if (index_no == dp[i + rn * (index-1)]) {
			ds_no = dp[i + rn * (prefix-1)];
			strcat(bufp, "-o -name '");
			strcat(bufp, tra1p->ptr.c[ds_no - 1]);
			strcat(bufp, "*' ");
		}
	}
	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			個別ＬＡデータセット保存処理
 *
 *		FUNC.NAME	: dbsv60()
 *		INPUT		: dev = デバイス名
 *				: name = 保存ファイル名
 *				: n = 保存ファイル数
 *		OUTPUT		: return value = error code
 */

static
dbsv60(dev, size, name, n)
char	*dev;
char	*size;
char	*name[];
int	n;
{
	char	buf[1024*10];

	buf[0] = '\0';
	while (n--) {
		if (d3fchk(*name) != 0)
			return(E_DS_N);
		strcat(buf, *name++);
		strcat(buf, " ");
	}

	return(dbsv80(dev, size, buf));
}

/*	FUNCTION DESCRIPTION
 *
 *			個別ユーザ索引ファイル保存処理
 *
 *		FUNC.NAME	: dbsv70()
 *		INPUT		: dev = デバイス名
 *				: name = 保存ファイル名
 *				: n = 保存ファイル数
 *		OUTPUT		: return value = error code
 */

static
dbsv70(dev, size, name, n)
char	*dev;
char	*size;
char	*name[];
int	n;
{
	char	buf[1024*10];

	buf[0] = '\0';
	while (n--) {
		if (d3fchk(*name) != 0)
			return(E_UF_N);
		strcat(buf, *name++);
		strcat(buf, " ");
	}

	return(dbsv80(dev, size, buf));
}

/*	FUNCTION DESCRIPTION
 *
 *			ｂｒｕ起動処理
 *
 *		FUNC.NAME	: dbsv80()
 *		INPUT		: dev = デバイス名
 *				: fname = save file name
 *		OUTPUT		: return value = error code
 */

static
dbsv80(dev, size, fname)
char	*dev;
char	*size;
char	*fname;
{
	int	err;
	char	bru_cmd[1024*10];

	sprintf(bru_cmd, "bru -cv %s %s %s %s %s %s",
			dev == 0 ? "" : "-f",
			dev == 0 ? "" : dev,
			size == 0 ? "" : "-s",
			size == 0 ? "" : size,
			SV_FILE, fname);
	err = system(bru_cmd);

	if (err != 0 && err != 0x200)
		return(E_S);

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			ｆｉｎｄ＆ｂｒｕ起動処理
 *
 *		FUNC.NAME	: dbsv85()
 *		INPUT		: dev = デバイス名
 *				: fname = save file name
 *		OUTPUT		: return value = error code
 */

static
dbsv85(dev, size, fname)
char	*dev;
char	*size;
char	*fname;
{
	int	err;
	char	bru_cmd[1024*10];

	sprintf(bru_cmd, "find . \\( -name %s -o %s \\) -print|bru -cv %s %s %s %s -",
			SV_FILE, fname,
			dev == 0 ? "" : "-f",
			dev == 0 ? "" : dev,
			size == 0 ? "" : "-s",
			size == 0 ? "" : size);
	err = system(bru_cmd);

	if (err != 0 && err != 0x200)
		return(E_S);

	return(0);
}

