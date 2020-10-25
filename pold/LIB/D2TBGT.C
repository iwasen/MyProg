/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2tbgt.c
 *		ROUTINE		: �\�`���t�@�C���̏o��
 *		REVISION	:
 *		REMARKS		: 88.07.11
 */

#include	<stdio.h>
#include	<string.h>
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d2lerr.h"

extern	DSET	*d3lbgn();
extern	char	*d3allc();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			���C������
 *
 *		FUNC.NAME	: d2tbgt()
 *		INPUT		: ladir = �k�`�f�[�^�x�[�X�f�B���N�g����
 *				: lads  = �k�`�f�[�^�Z�b�g��
 *				: tbl_file = �\�`���t�@�C����
 *				:            �i�m�t�k�k�̎��͕W���o�́j
 *				: col_tbl  = ��ԍ��e�[�u��
 *				: sprt     = �Z�p���[�^
 *		OUTPUT		: return value = error code
 */

d2tbgt(ladir, lads, tbl_file, col_tbl, sprt)
char	*ladir, *lads, *tbl_file;
int	*col_tbl;
char	*sprt;
{
	DSET	*dsp;
	FILE	*fp;
	int	col, row, col_n, err;
	char	lads_path[128];

	/* �k�`�f�[�^�Z�b�g�g�p�J�n */
	d3path(ladir, lads, lads_path);
	if ((dsp = d3lbgn(lads_path, "r", 0, &err)) == 0)
		return(d3cver(err, "ds"));

	/* �\�`���`�F�b�N */
	if (d3istb(dsp) == 3)
		return(E_DS_F);

	/* �񐔎��o�� */
	if ((col = d3tcol(dsp)) < 0) {
		d3lend(dsp, "q", 0);
		return(d3cver(err, "ds"));
	}

	/* �s�����o�� */
	if ((row = d3trow(dsp)) < 0) {
		d3lend(dsp, "q", 0);
		return(d3cver(err, "ds"));
	}

	/* ��ԍ��`�F�b�N */
	if (col_tbl != 0) {
		for (col_n = 0; col_tbl[col_n]; col_n++) {
			if (col_tbl[col_n] <= 0 || col_tbl[col_n] > col) {
				d3lend(dsp, "q", 0);
				return(E_P);
			}
		}
	} else
		col_n = col;

	/* �\�`���t�@�C���I�[�v�� */
	if (tbl_file != 0) {
		if ((fp = fopen(tbl_file, "w")) == 0) {
			d3lend(dsp, "q", 0);
			return(E_KF_N);
		}
	} else
		fp = stdout;

	/* �\�`���t�@�C���̏o�� */
	err = tbgt10(dsp, fp, col_tbl, col_n, row, sprt);

	if (tbl_file != 0)
		fclose(fp);		/* �\�`���t�@�C���N���[�Y */

	d3lend(dsp, "q", 0);		/* �k�`�f�[�^�Z�b�g�g�p�I�� */

	return(err);
}

/*	FUNCTION DESCRIPTION
 *
 *			�\�`���t�@�C���̏o��
 *
 *		FUNC.NAME	: tbgt10()
 *		INPUT		: dsp     = �f�[�^�Z�b�g�|�C���^
 *				: fp      = �\�`���t�@�C���|�C���^
 *				: col_tbl = ��ԍ��e�[�u��
 *				: col_n   = �o�͗�
 *				: row     = �s��
 *				: sprt    = �Z�p���[�^
 *		OUTPUT		: return value = error code
 */

static
tbgt10(dsp, fp, col_tbl, col_n, row, sprt)
DSET	*dsp;
FILE	*fp;
int	*col_tbl;
int	col_n;
int	row;
char	*sprt;
{
	int	i, err;
	T_TEMPLETE	*tp;

	/* �e���v���[�g�G���A�m�� */
	if ((tp = (T_TEMPLETE *)d3allc(dsp->ds_memno,
			sizeof(T_TEMPLETE) + sizeof(TEMPLETE) * col_n)) == 0)
		return(E_M);

	/* �e���v���[�g�Z�b�g */
	tp->tp_cnum = col_n;
	for (i = 0; i < col_n; i++) {
		if (col_tbl != 0)
			tp->tp_templete[i].tp_col = col_tbl[i];
		else
			tp->tp_templete[i].tp_col = i + 1;
	}
	if ((err = d3mktp(dsp, tp)) != 0)
		return(d3cver(err,"ds"));

	/* �\�f�[�^�ǂݍ��݃o�b�t�@�m�� */
	if ((tp->tp_bufp = d3allc(dsp->ds_memno, tp->tp_rlen)) == 0)
		return(E_M);

	for (i = 0; i < row; i++) {
		/* �\�f�[�^�ǂݍ��� */
		if ((err = d3trd(dsp, i+1, 1, tp)) != 0)
			return(d3cver(err, "ds"));

		/* �\�`���t�@�C���o�� */
		if ((err = tbgt20(fp, tp, sprt)) != 0)
			return(err);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			�\�`���t�@�C���̏o��
 *
 *		FUNC.NAME	: tbgt20()
 *		INPUT		: fp   = �\�`���t�@�C���|�C���^
 *				: tp   = �e���v���[�g�|�C���^
 *				: sprt = �Z�p���[�^
 *		OUTPUT		: return value = error code
 */

static
tbgt20(fp, tp, sprt)
FILE	*fp;
T_TEMPLETE	*tp;
char	*sprt;
{
	union	{
		int	*i;
		float	*f;
		char	*c;
	} val;
	int	i, j, len, sp_len;
	char	str[256];

	for (i = 0; i < tp->tp_cnum; i++) {
		if (i != 0) {
			if (sprt != 0)
				fputs(sprt, fp);
			else {
				sp_len = len - strlen(str);
				for (j = 0; j < sp_len; j++)
					str[j] = ' ';
				str[j] = '\0';
				fputs(str, fp);
			}
		}

		val.c = tp->tp_bufp + tp->tp_templete[i].tp_offset;
		switch (tp->tp_templete[i].tp_mode) {
		case DS_L:
			if (*val.i == 0)
				strcpy(str, "F");
			else if (*val.i == PD_NA)
				strcpy(str, "NA");
			else
				strcpy(str, "T");
			len = 3;
			break;
		case DS_I:
			if (*val.i == PD_NA)
				strcpy(str, "NA");
			else
				sprintf(str, "%d", *val.i);
			len = 12;
			break;
		case DS_R:
			if (*val.i == PD_NA)
				strcpy(str, "NA");
			else
				sprintf(str, "%g", *val.f);
			len = 14;
			break;
		case DS_C:
			strcpy(str, val.c);
			len = tp->tp_templete[i].tp_len;
			break;
		}

		fputs(str, fp);
	}

	fputs("\n", fp);

	return(0);
}
