/***************************************************************************
 *	FILE DESCRIPTION
 *
 *		FILE NAME	: d2idup.c
 *		ROUTINE		: �����t�@�C���X�V����
 *		REVISION	:
 *		REMARKS		: 88.01.25
 HISTORY.......................................................................
  2.	modify	M.MOTOKI       88/07/25	�V�X�e���������Ȃ��Ƃ��V�K�쐬����悤
					�ɂ���
 *****************************************************************************/

#include	<stdio.h>
#include	"la_ws/include/d3libc.h"

extern	DSET	*d3lbgn();
extern	struct dsm *d3gcmp(), *d3gtra();
extern	double	atof();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION
 *
 *			�����t�@�C���X�V
 *
 *		FUNC.NAME	: d2idup()
 *		INPUT		: ladir = directory name
 *				: id_name = �����t�@�C����
 *				: ds_name = data set name
 *		OUTPUT		: return value = error code
 */

d2idup(ladir, id_name, ds_name)
char	*ladir, *id_name, *ds_name;
{
	DSET	*id_dsp, *ds_dsp;
	int	rn, row, err, retry_count;
	char	s[256];
	PD_CNDREC	cond;
	char	tf_buf[4096];
	PD_TFREC	*tfp = (PD_TFREC *)tf_buf;
	char	tmp_buf[4096];
	T_TEMPLETE	*templete = (T_TEMPLETE *)tmp_buf;
	char	buf[4096];
	PD_LNREC	line;

	d3path(ladir, id_name, s);		/* make index path name */

	if ((err = d3fchk(s)) != 0) {
		err = d2mksi(s);		/* �����I�ɐV�K�쐬���� */
		if (err != 0)
			return(err);
	}

	retry_count = 0;
	for (;;) {
		id_dsp = d3lbgn(s, "u", DSO_NBAK, &err); /* �����g�p�J�n */
		if (err == 0)
			break;
		else if (err != DE_LOCK || retry_count++ == 10)
			return(d3cver(err, "if"));
		sleep(1);
	}

	rn = d3trow(id_dsp);			/* �s�����o�� */

	cond.cnd_relop = PD_CND_AND;
	cond.cnd_col = 1;
	cond.cnd_op = PD_CND_EQ;
	cond.cnd_val.c_ptr = ds_name;
	if ((err = d3srch(id_dsp, &cond, 0, tfp)) != 0) {	/* �������� */
		d3lend(id_dsp, "q", 0);
		return(d3cver(err, "if"));
	}

	if (tfp->tf_n != 0) {
		d3toln(tfp, &line, rn);		/* �x�N�g���ϊ� */
		row = line.ln_vec[0];		/* �s�ʒu�Z�b�g */
		if ((err = d3tdl(id_dsp, row, 1)) != 0) { /* �P�s�폜 */
			d3lend(id_dsp, "q", 0);
			return(d3cver(err, "if"));
		}
		rn--;
	}

	d3path(ladir, ds_name, s);		/* make LA dataset path name */
	ds_dsp = d3lbgn(s, "r1", 0, &err);	/* �k�`�f�[�^�Z�b�g�g�p�J�n */
	if (err == 0) {
		templete->tp_bufp = buf;
		idup10(id_dsp, ds_dsp, ds_name, templete); /* �s�f�[�^�쐬 */
		d3lend(ds_dsp, "q", 0);		/* �k�`�f�[�^�Z�b�g�g�p�I�� */
		if ((err = d3tin(id_dsp, rn, 1, templete)) != 0) {
						/* �P�s�}�� */
			d3lend(id_dsp, "q", 0);
			return(d3cver(err, "if"));
		}
	} else if (err != DE_FILE_NONE) {
		d3lend(id_dsp, "q", 0);
		return(d3cver(err, "ds"));
	}

	if ((err = d3lend(id_dsp, "wq", 1)) != 0) /* �����t�@�C���g�p�I�� */
		return(d3cver(err, "if"));

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			�s�f�[�^�쐬
 *
 *		FUNC.NAME	: idup10()
 *		INPUT		: id_dsp = �����t�@�C���Ǘ��|�C���^
 *				: ds_dsp = �k�`�f�[�^�Z�b�g�Ǘ��|�C���^
 *				: ds_name = �f�[�^�Z�b�g��
 *		OUTPUT		: templete = �e���v���[�g
 *				: return value = error code
 */

idup10(id_dsp, ds_dsp, ds_name, templete)
DSET	*id_dsp, *ds_dsp;
char	*ds_name;
T_TEMPLETE	*templete;
{
	struct	dsm	*desp, *labp, *axi2p, *labsp, *kp, *trap;
	char	*bp;
	TEMPLETE	*tp;
	int	i, offset, col, len, mode;

	bp = templete->tp_bufp;		/* set buffer pointer */
	tp = templete->tp_templete;	/* set templete pointer */

	len = (strlen(ds_name) + 2) & ~1;	/* get dataset name length */
	tp->tp_col = 1;			/* set column */
	tp->tp_mode = DS_C;		/* set mode */
	tp->tp_len = len;		/* set length */
	tp->tp_offset = 0;		/* set offset */
	tp++;

	strcpy(bp, ds_name);		/* set dataset name */
	offset = len;
	templete->tp_rlen = offset;
	templete->tp_cnum = 1;

	/* get "Lab" pointer */
	if ((labp = d3gcmp(id_dsp->ds_entry, "Lab")) == NULL)
		return;

	/* get "Axi2" pointer */
	if ((axi2p = d3gcmp(labp, "Axi2")) == NULL)
		return;

	/* get "Labs" pointer */
	if ((labsp = d3gcmp(axi2p, "Labs")) == NULL)
		return;

	/* get "Des" pointer */
	if ((desp = d3gcmp(ds_dsp->ds_entry, "Des")) == NULL)
		return;

	for (i = 0, kp = &desp->ptr.d[2]; i < desp->length; i++, kp++) {
		if (kp->length == 0)
			continue;

		col = idup20(labsp, kp->seibun);	/* get column */
		if (col == 0)
			continue;

		if ((trap = d3gtra(axi2p, col)) == NULL) /* get "Tra" pinter */
			mode = DS_I;
		else
			mode = trap->type;

		if (mode != kp->type)
			continue;

		len = idup30(bp + offset, kp->ptr, mode); /* set buffer */

		tp->tp_col = col;		/* set column */
		tp->tp_mode = mode;		/* set mode */
		tp->tp_len = len;		/* set length */
		tp->tp_offset = offset;		/* set offset */

		offset += len;
		tp++;
		templete->tp_cnum++;
	}

	templete->tp_rlen = offset;		/* set record length */
}

/*	FUNCTION DESCRIPTION
 *
 *			��ԍ����o��
 *
 *		FUNC.NAME	: idup20()
 *		INPUT		: axi2p = "Axi2" pointer
 *				: keyword = keyword
 *		OUTPUT		: return value = ��ԍ�
 */

static
idup20(labsp, keyword)
struct	dsm	*labsp;
char	*keyword;
{
	int	i;

	for (i = 0; i < labsp->length; i++) {
		if (strcmp(keyword, labsp->ptr.c[i]) == 0)
			return(i + 1);
	}

	return(0);
}

/*	FUNCTION DESCRIPTION
 *
 *			�s�o�b�t�@�Z�b�g
 *
 *		FUNC.NAME	: idup30()
 *		INPUT		: bp = �s�o�b�t�@�|�C���^
 *				: key = �L�[�l
 *				: mode = ����
 *		OUTPUT		: return value = data size
 */

static
idup30(bp, key, mode)
char	*bp;
DS_PTR	key;
int	mode;
{
	int	len;

	switch (mode) {
	case DS_L:				/* logical */
	case DS_I:				/* integer */
	case DS_R:				/* real */
		*(int *)bp = key.i[0];
		len = sizeof(int);
		break;
	case DS_C:				/* character */
		strcpy(bp, key.c[0]);		/* copy string */
		len = (strlen(bp) + 2) & ~1;
		break;
	default:
		len = 0;
	}

	return(len);
}
