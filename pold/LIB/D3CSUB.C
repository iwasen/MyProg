/*	FILE DESCRIPTION

		FILE NAME	: d3csub.c
		ROUTINE		: ライブラリ共通サブルーチン
		REMARKS		:
*/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	char	*d3allc();

static	char	sccsid[]="@(#)d3csub.c 1.3 88/06/12 14:48:42";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3gcmp()
		ROUTINE		: 成分取り出し
		INPUT		: mp = memory data pointer
				: sn = seibun mei
		OUTPUT		: return value = seibun data address
*/

struct	dsm *
d3gcmp(mp, sn)
struct	dsm	*mp;
char	*sn;
{
	int	len;

	switch (mp->type) {
	case DS_STR:
		mp = mp->ptr.d;
		break;
	case DS_DIR:
		break;
	default:
		return(0);
	}

	len = mp->length;
	mp += 2;
	while (len--) {
		if (strcmp(sn, mp->seibun) == 0)
			return(mp);
		mp++;
	}

	return(0);
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3gtra()
		ROUTINE		: 翻訳部サーチ
		INPUT		: lp = ラベル部ポインタ
				: tn = 翻訳部番号
		OUTPUT		: return value = 翻訳部アドレス
*/

struct	dsm *
d3gtra(lp, tn)
struct	dsm	*lp;
int	tn;
{
	char	s[32];

	sprintf(s, "Tra%d", tn);
	return(d3gcmp(lp, s));
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3gsiz()
		ROUTINE		: 要素エリア数計算
		INPUT		: n = 要素数
		OUTPUT		: return value = 要素エリア数
*/

d3gsiz(n)
int	n;
{
	int	x;

	if (n == 0)
		return(0);

	n--;
	for (x = 1; n != 0; x <<= 1, n >>= 1)
		;
	return(x);
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3ient()
		ROUTINE		: ｎ要素追加
		INPUT		: mgno = memory group No.
				  mp = ds pointer
				  pos = 追加位置
				  n = 追加個数
		OUTPUT		: return value = 追加要素アドレス
*/

void	*
d3ient(mgno,mp,pos,n)
int	mgno;
struct	dsm	*mp;
int	pos, n;
{
	int	size1, size2, i, type, len, allc_size;
	union	{
		int	*ip;
		char	**cp;
		struct	dsm	*dp;
		void	*vp;
	} old, new;
	struct	dsm	*mp2;

	type = mp->type;
	len = mp->length;

	if (pos > len)
		return(0);

	size1 = d3gsiz(len);
	size2 = d3gsiz(len + n);
/* for debug
printf("type = %d\nlen = %d\nsize1 = %d\nsize2 = %d\n",type,len,size1,size2);
*/
	if (size1 < size2) {
		switch (type) {
		case DS_I:
		case DS_L:
		case DS_R:
			allc_size = sizeof(int) * size2;
			if ((new.ip = (int *)d3allc(mgno,allc_size)) == 0)
				return(0);
			old.ip = mp->ptr.i;
			mp->ptr.i = new.ip;
			while (len--)
				*new.ip++ = *old.ip++;
			break;
		case DS_C:
			allc_size = sizeof(char *) * size2;
			if ((new.cp = (char **)d3allc(mgno,allc_size)) == 0)
				return(0);
			old.cp = mp->ptr.c;
			mp->ptr.c = new.cp;
			while (len--)
				*new.cp++ = *old.cp++;
			break;
		case DS_STR:
			allc_size = sizeof(struct dsm) * (size2 + 2);
			if ((new.dp = (struct dsm *)d3allc(mgno,allc_size)) == 0)
				return(0);
			old.dp = mp->ptr.d;
			mp->ptr.d = new.dp;
			len += 2;
			while (len--)
				*new.dp++ = *old.dp++;
			break;
		default:
			return(0);
		}
	}
	switch (type) {
	case DS_I:
	case DS_L:
	case DS_R:
		old.ip = mp->ptr.i + mp->length;
		new.ip = old.ip + n;
		for (i = mp->length; i > pos; i--)
			*--new.ip = *--old.ip;
		break;
	case DS_C:
		old.cp = mp->ptr.c + mp->length;
		new.cp = old.cp + n;
		for (i = mp->length; i > pos; i--)
			*--new.cp = *--old.cp;
		break;
	case DS_STR:
		old.dp = mp->ptr.d + mp->length + 2;
		new.dp = old.dp + n;
		for (i = mp->length; i > pos; i--)
			*--new.dp = *--old.dp;
		mp2 = mp->ptr.d;
		mp2->length += n;
		mp2++;
		(*(int *)&mp2->ptr) += n;
		break;
	}
	mp->length += n;
	return(old.vp);
}

/*	FUNCTION DESCRIPTION
 *
 *			文字列セーブ処理
 *
 *		FUNC.NAME	: d3csav()
 *		INPUT		: memno = memory No.
 *				: str = 文字列
 *		OUTPUT		: return value = save address
 */

char	*
d3csav(memno, str)
int	memno;
char	*str;
{
	char	*cp;

	if ((cp = d3allc(memno, strlen(str) + 1)) == NULL)
		return(NULL);

	strcpy(cp, str);
	return(cp);
}


/*	FUNCTION DESCRIPTION
 *
 *			パス名作成
 *
 *		FUNC.NAME	: d3path()
 *		INPUT		: dir = directory name
 *				: file = file name
 *				: path = path name
 *		OUTPUT		: none
 */

d3path(dir, file, path)
char	*dir, *file, *path;
{
	int	len;

	if ((len = strlen(dir)) == 0)
		strcpy(path, file);
	else {
		strcpy(path, dir);
		if (dir[len - 1] != '/')
			strcat(path, "/");
		strcat(path, file);
	}
}

