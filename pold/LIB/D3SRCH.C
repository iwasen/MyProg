/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d3srch.c
 *		ROUTINE		: 条件検索
 *		REVISION	:
 *		REMARKS		: 88.01.16
 */

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	struct	dsm	*d3gcmp(), *d3gtra();
extern	char	*malloc(), *regcmp(), *regex(), *strrchr();

static	char	sccsid[]="@(#)d3srch.c 1.4 88/06/12 14:56:31";

/*	FUNCTION DESCRIPTION
 *
 *			条件検索
 *
 *		FUNC.NAME	: d3srch()
 *		INPUT		: dsp = data set pointer
 *				: cond = 条件レコード
 *				: in = 入力行番号ベクトル
 *		OUTPUT		: out = 出力行番号ベクトル
 *				: return value = error code
 */

d3srch(dsp, cond, in, out)
DSET	*dsp;
PD_CNDREC	*cond;
PD_TFREC	*in, *out;
{
	struct	dsm	*labp, *axip, *datp, *trap, *dimp, *datap;
	char	*work_tf;
	int	i, rn, cn;

	if ((labp = d3gcmp(dsp->ds_entry, "Lab")) == NULL)
		return(DE_DS_FORMAT);

	if ((axip = d3gcmp(labp, "Axi2")) == NULL)
		return(DE_DS_FORMAT);

	if ((datp = d3gcmp(dsp->ds_entry, "Dat")) == NULL)
		return(DE_DS_FORMAT);

	if ((dimp = d3gcmp(datp, "Dim")) == NULL)
		return(DE_DS_FORMAT);

	if ((datap = d3gcmp(datp, "Data")) == NULL)
		return(DE_DS_FORMAT);

	rn = dimp->ptr.i[0];
	cn = dimp->ptr.i[1];

	if ((trap = d3gtra(axip, cond->cnd_col)) != 0) {
		if ((work_tf = malloc(trap->length)) == NULL)
			return(DE_MEM);
		for (i = 0; i < trap->length; i++)
			work_tf[i] = srch30(cond, trap->ptr.i[i], trap->type);
		srch20(datap, rn, cond, in, out, work_tf);
		free(work_tf);
	} else {
		srch10(datap, rn, cond, in, out);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			整数型条件検索
 *
 *		FUNC.NAME	: srch10()
 *		INPUT		: datap = データ部ポインタ
 *				: rn = 行数
 *				: cond = 条件レコード
 *				: in = 入力行番号ベクトル
 *		OUTPUT		: out = 出力行番号ベクトル
 */

static
srch10(datap, rn, cond, in, out)
struct	dsm	*datap;
int	rn;
PD_CNDREC	*cond;
PD_TFREC	*in, *out;
{
	int	*dp, i, tf, tn;

	dp = datap->ptr.i;

	tn = 0;
	for (i = 0; i < rn; i++) {
		if (in == 0)
			tf = srch30(cond, dp[i + (cond->cnd_col - 1) * rn], DS_I);
		else {
			if (cond->cnd_relop == PD_CND_AND) {
				if (in->tf_vec[i] != 0)
					tf = srch30(cond, dp[i + (cond->cnd_col - 1) * rn], DS_I);
				else
					tf = 0;
			} else {
				if (in->tf_vec[i] != 0)
					tf = 1;
				else
					tf = srch30(cond, dp[i + (cond->cnd_col - 1) * rn], DS_I);
			}
		}
		out->tf_vec[i] = tf;
		if (tf)
			tn++;
	}
	out->tf_n = tn;
}

/*	FUNCTION DESCRIPTION
 *
 *			整数型以外条件検索
 *
 *		FUNC.NAME	: srch20()
 *		INPUT 		: datap = データ部ポインタ
 *				: rn = 行数
 *				: cond = 条件レコード
 *				: in = 入力行番号ベクトル
 *				: work_tf = ワークＴ／Ｆベクトル
 *		OUTPUT		: out = 出力行番号ベクトル
 */

static
srch20(datap, rn, cond, in, out, work_tf)
struct	dsm	*datap;
int	rn;
PD_CNDREC	*cond;
PD_TFREC	*in, *out;
char	*work_tf;
{
	int	*dp, i, tf, tn;

	dp = datap->ptr.i;

	tn = 0;
	for (i = 0; i < rn; i++) {
		tf = work_tf[dp[i + (cond->cnd_col - 1) * rn] - 1];
		if (in != 0) {
			if (cond->cnd_relop == PD_CND_AND) {
				if (in->tf_vec[i] == 0)
					tf = 0;
			} else {
				if (in->tf_vec[i] != 0)
					tf = 1;
			}
		}
		out->tf_vec[i] = tf;
		if (tf)
			tn++;
	}
	out->tf_n = tn;
}

/*	FUNCTION DESCRIPTION
 *
 *			比較処理
 *
 *		FUNC.NAME	: srch30()
 *		INPUT		: cond = 条件レコード
 *				: key_val = キー値
 *				: type = data type
 *		OUTPUT		: return value = 比較結果
 */

static
srch30(cond, key_val, type)
PD_CNDREC	*cond;
int	key_val, type;
{
	int	reg_flag;
	char	wbuf[256], *cp, *dqp;

	if (type != DS_C) {
		if (cond->cnd_val.i == PD_NA) {
			switch (cond->cnd_op) {
			case PD_CND_EQ:
			case PD_CND_NE:
				break;
			default:
				return(0);
			}
			return(srch31(cond->cnd_val.i, key_val, cond->cnd_op, DS_I));
		}

		if (key_val == PD_NA)
			return(0);

		return(srch31(cond->cnd_val.i, key_val, cond->cnd_op, type));
	} else {
		cp = cond->cnd_val.c_ptr;
		if (*cp == '"') {
			strcpy(wbuf, cp);
			cp = wbuf;
			if ((dqp = strrchr(++cp, '"')) != 0)
				*dqp = '\0';
			reg_flag = 0;
		} else
			reg_flag = 1;

		if (*cp == '\0') {
			switch (cond->cnd_op) {
			case PD_CND_EQ:
			case PD_CND_NE:
				break;
			default:
				return(0);
			}
			return(srch31(cp, key_val, cond->cnd_op, type));
		}

		if (*(char *)key_val == '\0')
			return(0);

		if ((cond->cnd_op == PD_CND_EQ || cond->cnd_op == PD_CND_NE)
				&& reg_flag != 0)
			return(srch32(cp, key_val, cond->cnd_op));
		return(srch31(cp, key_val, cond->cnd_op, type));
	}
}

/*	FUNCTION DESCRIPTION
 *
 *			比較処理
 *
 *		FUNC.NAME	: srch31()
 *		INPUT		: cond = 条件レコード
 *				: key_val = キー値
 *				: type = data type
 *		OUTPUT		: return value = 比較結果
 */

static
srch31(val, key_val, op, type)
union	{
	int	i;
	float	r;
	char	*c;
} val, key_val;
int	op, type;
{
	int	cf;

	switch (type) {
	case DS_L:
	case DS_I:
		cf = key_val.i - val.i;
		break;
	case DS_R:
		if (key_val.r == val.r)
			cf = 0;
		else if (key_val.r > val.r)
			cf = 1;
		else
			cf = -1;
		break;
	case DS_C:
		cf = strcmp(key_val.c, val.c);
		break;
	}

	switch (op) {
	case PD_CND_EQ:
		return(cf == 0 ? 1 : 0);
	case PD_CND_NE:
		return(cf != 0 ? 1 : 0);
	case PD_CND_GE:
		return(cf >= 0 ? 1 : 0);
	case PD_CND_LE:
		return(cf <= 0 ? 1 : 0);
	case PD_CND_GT:
		return(cf > 0 ? 1 : 0);
	case PD_CND_LT:
		return(cf < 0 ? 1 : 0);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			正規表現比較処理
 *
 *		FUNC.NAME	: srch32()
 *		INPUT		: cond = 条件レコード
 *				: cp = キー値
 *		OUTPUT		: return value = 比較結果
 */

static
srch32(val, key_val, op)
char	*val, *key_val;
int	op;
{
	char	*name, *point, ws[128];
	int	cf;

	d3cvre(val, ws, 0);
	if ((name = regcmp(ws, 0)) == NULL)
		return(0);
	point = regex(name, key_val);
	free(name);
	if (point == 0)
		cf = 0;
	else
		cf = 1;

	if (op == PD_CND_NE)
		cf = !cf;

	return(cf);
}

