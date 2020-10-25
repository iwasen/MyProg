/************************************************************************
 *									*
 *		ＩＳＡＭライブラリ					*
 *									*
 *		名称		: 共通サブルーチン			*
 *		ファイル名	: csub.c				*
 *		作成者		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>
#include <jctype.h>
#include "isam.h"

static	void	(*_db_err_handler)() = 0;	/* エラー処理関数ポインタ */

/*=======================================================================
 |
 |		エラーハンドラ設定
 |
 |	void	db_err_handler(func)
 |
 |		void	(*func)();	エラー処理関数へのポインタ
 |
 =======================================================================*/
void	db_err_handler(void (*func)())
{
	_db_err_handler = func;
}

/*=======================================================================
 |
 |		エラー処理
 |
 |	void	db_err(err_code)
 |
 |		int	err_code;	エラーコード
 |
 =======================================================================*/
void	db_err(int err_code)
{
	_db_err_code = err_code;

	if (_db_err_handler != NULL) {
		/* エラーハンドラが設定されていたら呼び出す */
		(*_db_err_handler)(err_code);
	}
}

/*=======================================================================
 |
 |		文字列から実数への変換
 |
 |	double	natof(str, len)
 |
 |		char	*str;		文字列
 |		int	len;		長さ
 |
 |		返値			実数値
 |
 =======================================================================*/
double	natof(char *str, int len)
{
	char	buf[32];

	memcpy(buf, str, len);
	buf[len] = '\0';
	return(atof(buf));
}

/*=======================================================================
 |
 |		文字列から整数への変換
 |
 |	int	natoi(str, len)
 |
 |		char	*str;		文字列
 |		int	len;		長さ
 |
 |		返値			実数値
 |
 =======================================================================*/
int	natoi(char *str, int len)
{
	char	buf[32];

	memcpy(buf, str, len);
	buf[len] = '\0';
	return(atoi(buf));
}

/*=======================================================================
 |
 |		キー取り出し
 |
 |	int	get_key(dp, ihp, kp)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		IDX_H	*ihp;		ＩＤＸ＿Ｈポインタ
 |		char	*kp;		キーバッファ
 |
 |		返値			０：キーなし　１：キーあり
 |
 =======================================================================*/
int	get_key(DBF *dp, IDX_H *ihp, char *kp)
{
	FIELD	*flp;
	char	buf[100], *p;
	int	flag;

	strcpy(buf, ihp->index);
	p = strtok(buf, "+ ");
	if (ihp->type == 0)
		*kp = '\0';
	else
		*(double *)kp = 0;
	flag = 0;
	while (p) {
		if ((flp = get_field(dp, p)) != NULL) {
			if (ihp->type == 0) {
				memcpy(kp, flp->fad, flp->l_field);
				kp += flp->l_field;
			} else {
				*(double *)kp += natof(flp->fad, flp->l_field);
			}
			flag = 1;
		} else
			return(0);

		p = strtok(NULL, "+ ");
	}

	return(flag);
}

/*=======================================================================
 |
 |		フィールド情報取り出し
 |
 |	FIELD	*get_field(dp, field)
 |
 |		DBF	*dp;		ＤＢＦポインタ
 |		char	*field;		フィールド名
 |
 |		返値			フィールド情報へのポインタ
 |
 =======================================================================*/
FIELD	*get_field(DBF *dp, char *field)
{
	register int	i;
	FIELD	*flp;
	char	buf[11], *p;

	strcpy(buf, field);
	for (p = buf; *p; p++) {
		if (iskanji(*p))
			p++;
		else if (islower(*p))
			*p -= 0x20;
	}

	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (strcmp(buf, flp->name) == 0)
			return(flp);
	}
	return(NULL);
}
