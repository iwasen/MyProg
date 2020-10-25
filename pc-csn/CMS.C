/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h�I���t�@�C������		*
 *		�t�@�C����	: cms.c					*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	�����֐�	*/
static	CMS_TBL	*read_cms(FILE *);
static	CMD_TBL	*read_cmd(FILE *, int);
static	PRM_TBL	*read_prm(FILE *, int);
static	PRT_TBL	*read_prt(FILE *, int);
static	SEL_TBL	*read_sel(FILE *);
static	NUM_TBL	*read_num(FILE *);
static	NAM_TBL	*read_nam(FILE *);
static	GUD_TBL	*read_gud(FILE *);
static	char	*getstr(FILE *);
static  int	getnum(FILE *);
static	CMD_TBL	*search_cmd(CMS_TBL *, char *, char *);
static	PRM_TBL	*search_prm(CMD_TBL *, char *);

/*=======================================================================
 |
 |		�R�}���h�I���t�@�C���ǂݍ���
 |
 |	void	read_cmdfile()
 |
 =======================================================================*/
void	read_cmdfile()
{
	FILE	*fp;		/* �t�@�C���|�C���^ */
	int	i;		/* ���[�v�J�E���^ */

	/* �I�����C���R�}���h�I���t�@�C���ǂݍ��� */
	for (i = 0; i < 5; i++) {
		if (csg.fsg.cms_file[i][0] != '\0') {
			/* �R�}���h�I���t�@�C���I�[�v�� */
			if ((fp = fopen(csg.fsg.cms_file[i], "rb")) != NULL) {

				/* �R�}���h�I���t�@�C���ǂݍ��� */
				cmstbl[i] = read_cms(fp);

				/* �R�}���h�I���t�@�C���N���[�Y */
				fclose(fp);
			}
		}
	}

	/* �I�t���C���R�}���h�I���t�@�C���ǂݍ��� */
	if ((fp = fopen(csg.fsg.loc_file, "rb")) != NULL) {

		/* �R�}���h�I���t�@�C���ǂݍ��� */
		loctbl = read_cms(fp);

		/* �R�}���h�I���t�@�C���N���[�Y */
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		�R�}���h�^�C�g���ǂݍ���
 |
 |	CMS_TBL	*read_cms(fp)
 |
 |		char	*file_name;	�R�}���h�t�@�C����
 |
 |		�Ԓl			�R�}���h�I���e�[�u���|�C���^
 |
 =======================================================================*/
static	CMS_TBL	*read_cms(FILE *fp)
{
	CMS_TBL	*ptr;		/* �R�}���h�I���e�[�u���|�C���^ */

	ptr = talloc(sizeof(CMS_TBL));	/* �������m�� */

	ptr->title = getstr(fp);	/* �^�C�g���ǂݍ��� */
	ptr->guide = getstr(fp);	/* �����ǂݍ��� */
	ptr->n = getnum(fp);		/* �R�}���h���ǂݍ��� */
	ptr->ptr = read_cmd(fp, ptr->n); /* �R�}���h�f�[�^�ǂݍ��� */

	return(ptr);
}

/*=======================================================================
 |
 |		�R�}���h�f�[�^�ǂݍ���
 |
 |	CMD_TBL	*read_cmd(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	n;		�R�}���h��
 |
 |		�Ԓl			�R�}���h�e�[�u���|�C���^
 |
 =======================================================================*/
static	CMD_TBL	*read_cmd(FILE *fp, int n)
{
	CMD_TBL	*ptr, *p;	/* �R�}���h�e�[�u���|�C���^ */

	if (n == 0)
		return(NULL);

	ptr = talloc(sizeof(CMD_TBL) * n);	/* �������m�� */

	for (p = ptr; n--; p++) {
		p->name = getstr(fp);	/* �R�}���h���ǂݍ��� */
		p->guide = getstr(fp);	/* �����ǂݍ��� */
		p->n = getnum(fp);	/* �p�����[�^��ތ��ǂݍ��� */
		p->ptr = read_prm(fp, p->n); /* �p�����[�^��ޓǂݍ��� */
	}

	return(ptr);
}

/*=======================================================================
 |
 |		�p�����[�^��ރf�[�^�ǂݍ���
 |
 |	PRM_TBL *read_prm(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	n;		�p�����[�^��ތ�
 |
 |		�Ԓl			�p�����[�^��ރe�[�u���|�C���^
 |
 =======================================================================*/
static	PRM_TBL	*read_prm(FILE *fp, int n)
{
	PRM_TBL	*ptr, *p;	/* �p�����[�^��ރe�[�u���|�C���^ */

	if (n == 0)
		return(NULL);

	ptr = talloc(sizeof(PRM_TBL) * n);	/* �������m�� */

	for (p = ptr; n--; p++) {
		p->name = getstr(fp);		/* �p�����[�^���ǂݍ��� */
		p->guide = getstr(fp);		/* �����ǂݍ��� */
		p->n = getnum(fp);		/* �p�����[�^���ǂݍ��� */
		p->ptr = read_prt(fp, p->n); /* �p�����[�^�f�[�^�ǂݍ��� */
	}

	return(ptr);
}

/*=======================================================================
 |
 |		�p�����[�^�f�[�^�ǂݍ���
 |
 |	PRT_TBL	*read_prt(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	n;		�p�����[�^��
 |
 |		�Ԓl			�p�����[�^�e�[�u��
 |
 =======================================================================*/
static	PRT_TBL	*read_prt(FILE *fp, int n)
{
	PRT_TBL	*ptr, *p;	/* �p�����[�^�e�[�u���|�C���^ */

	if (n == 0)
		return(NULL);

	ptr = talloc(sizeof(PRT_TBL) * n);	/* �������m�� */

	for (p = ptr; n--; p++) {
		p->type = getnum(fp);		/* �p�����[�^��ޓǂݍ��� */
		p->guide = getstr(fp);		/* �����ǂݍ��� */
		switch (p->type) {
		case KWD_SEL:	/* �������� */
			p->ptr.sel = read_sel(fp);
			break;
		case KWD_NUM:	/* ���l���� */
			p->ptr.num = read_num(fp);
			break;
		case KWD_NAM:	/* ���̍��� */
			p->ptr.nam = read_nam(fp);
			break;
		case KWD_MSG:	/* �������� */
			p->ptr.gud = read_gud(fp);
			break;
		}
	}

	return(ptr);
}

/*=======================================================================
 |
 |		�I�����ڃf�[�^�ǂݍ���
 |
 |	SEL_TBL	*read_sel(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 |		�Ԓl			�I�����ڃe�[�u���|�C���^
 |
 =======================================================================*/
static	SEL_TBL	*read_sel(FILE *fp)
{
	SEL_TBL	*ptr;		/* �I�����ڃe�[�u���|�C���^ */
	int	i;		/* ���[�v�J�E���^ */
	int	n;		/* �I�����ڐ� */

	n = getnum(fp);		/* �I�����ڐ��ǂݍ��� */
	ptr = talloc(sizeof(SEL_TBL)+sizeof(ptr->ptr) * n); /* �������m�� */
	ptr->n = n;

	for (i = 0; i < n; i++) {
		ptr->ptr[i][0] = getstr(fp); /* �I�����ځi�\���j�ǂݍ��� */
		ptr->ptr[i][1] = getstr(fp); /* �I�����ځi���́j�ǂݍ��� */
	}

	return(ptr);
}

/*=======================================================================
 |
 |		���l���ڃf�[�^�ǂݍ���
 |
 |	NUM_TBL	*read_num(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 |		�Ԓl			���l���ڃe�[�u���|�C���^
 |
 =======================================================================*/
static	NUM_TBL	*read_num(FILE *fp)
{
	NUM_TBL	*ptr;		/* ���l���ڃe�[�u���|�C���^ */

	ptr = talloc(sizeof(NUM_TBL));	/* �������m�� */

	ptr->min_flg = getnum(fp);	/* �ŏ��l�w��t���O�ǂݍ��� */
	ptr->min = getnum(fp);		/* �ŏ��l�ǂݍ��� */
	ptr->min_syc = getnum(fp);	/* �ŏ��l�V�X�e���萔�ǂݍ��� */
	ptr->max_flg = getnum(fp);	/* �ő�l�w��t���O�ǂݍ��� */
	ptr->max = getnum(fp);		/* �ő�l�ǂݍ��� */
	ptr->max_syc = getnum(fp);	/* �ő�l�V�X�e���萔�ǂݍ��� */
	ptr->prmid = getstr(fp);	/* �p�����[�^�h�c�ǂݍ��� */
	ptr->sel = getstr(fp);		/* �I�����ړǂݍ��� */

	return(ptr);
}

/*=======================================================================
 |
 |		���̍��ڃf�[�^�ǂݍ���
 |
 |	NAM_TBL	*read_nam(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 |		�Ԓl			���̍��ڃe�[�u���|�C���^
 |
 =======================================================================*/
static	NAM_TBL	*read_nam(FILE *fp)
{
	NAM_TBL	*ptr;		/* ���̍��ڃe�[�u���|�C���^ */

	ptr = talloc(sizeof(NAM_TBL));	/* �������m�� */

	ptr->length = getnum(fp);	/* �ő啶�����ǂݍ��� */
	ptr->sel = getstr(fp);		/* �I�����ړǂݍ��� */
	ptr->ext1 = getstr(fp);		/* �g���q�P�ǂݍ��� */
	ptr->ext2 = getstr(fp);		/* �g���q�Q�ǂݍ��� */

	return(ptr);
}

/*=======================================================================
 |
 |		�������ڃf�[�^�ǂݍ���
 |
 |	GUD_TBL	*read_gud(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 |		�Ԓl			���̍��ڃe�[�u���|�C���^
 |
 =======================================================================*/
static	GUD_TBL	*read_gud(FILE *fp)
{
	GUD_TBL	*ptr;		/* �������ڃe�[�u���|�C���^ */

	ptr = talloc(sizeof(GUD_TBL));	/* �������m�� */

	ptr->length = getnum(fp);	/* �ő啶�����ǂݍ��� */

	return(ptr);
}

/*=======================================================================
 |
 |		������ǂݍ���
 |
 |	char	*getstr(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 |		�Ԓl			�ǂݍ��񂾕�����̃|�C���^
 |
 =======================================================================*/
static	char	*getstr(FILE *fp)
{
	char	*ptr;		/* ������|�C���^ */
	int	len;		/* ������ */

	len = getnum(fp);		/* �����ǂݍ��� */

	if (len != -1) {
		ptr = talloc(len + 1);		/* �������m�� */

		fread(ptr, 1, len, fp);		/* ������ǂݍ��� */
		ptr[len] = '\0';
	} else
		ptr = NULL;

	return(ptr);
}

/*=======================================================================
 |
 |		���l�ǂݍ���
 |
 |	int	getnum(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 |		�Ԓl			�ǂݍ��񂾐��l
 |
 =======================================================================*/
static	int	getnum(FILE *fp)
{
	int	n;		/* ���l�f�[�^ */

	/* ���l�f�[�^�ǂݍ��� */
	fread(&n, sizeof(int), 1, fp);

	return(n);
}

/*=======================================================================
 |
 |		�R�}���h�I���e�[�u������
 |
 |	CMS_TBL	*search_cms(bufp)
 |
 |		char	*bufp;		�R�}���h�o�b�t�@
 |
 |		�Ԓl			�R�}���h�I���e�[�u���|�C���^
 |
 =======================================================================*/
CMS_TBL	*search_cms(char *bufp)
{
	char	cmd_name[4];	/* �R�}���h�� */
	char	prm_name[4];	/* �p�����[�^�� */
	int	i;		/* ���[�v�J�E���^ */

	/* �R�}���h�����o�� */
	for (i = 0; i < 3 && *bufp != '\0'; i++, bufp++)
		cmd_name[i] = *bufp;
	cmd_name[i] = '\0';

	/* �p�����[�^�����o�� */
	while (*bufp == ' ')
		bufp++;
	for (i = 0; i < 3 && *bufp != '\0'; i++, bufp++)
		prm_name[i] = *bufp;
	prm_name[i] = '\0';

	/* �R�}���h������ */
	for (i = 0; i < 5; i++) {
		cmd_current = search_cmd(cmstbl[i], cmd_name, prm_name);
		if (cmd_current != NULL)
			return(cmstbl[i]);
	}
	return(NULL);
}

/*=======================================================================
 |
 |		�R�}���h�e�[�u������
 |
 |	CMD_TBL	*search_cmd(cmstp, cmd_name, prm_name)
 |
 |		CMS_TBL	*cmsp;		�R�}���h�I���e�[�u���|�C���^
 |		char	*cmd_name;	�R�}���h��
 |		char	*prm_name;	�p�����[�^��
 |
 |		�Ԓl			�R�}���h�e�[�u���|�C���^
 |
 =======================================================================*/
static	CMD_TBL	*search_cmd(CMS_TBL *cmstp, char *cmd_name, char *prm_name)
{
	int	i;		/* ���[�v�J�E���^ */
	CMD_TBL	*cmdtp;		/* �R�}���h�e�[�u���|�C���^ */

	if (cmstp == NULL)
		return(NULL);

	/* �p�����[�^������ */
	for (i = 0, cmdtp = cmstp->ptr; i < cmstp->n; i++, cmdtp++) {
		if (strcmp(cmd_name, cmdtp->name) == 0) {
			prm_current = search_prm(cmdtp, prm_name);
			if (prm_current != NULL)
				return(cmdtp);
		}
	}

	return(NULL);
}

/*=======================================================================
 |
 |		�p�����[�^�e�[�u������
 |
 |	PRM_TBL	*search_prm(cmdtp, prm_name)
 |
 |		CMD_TBL	*cmdp;		�R�}���h�e�[�u���|�C���^
 |		char	*prm_name;	�p�����[�^��
 |
 |		�Ԓl			�p�����[�^��ރe�[�u���|�C���^
 |
 =======================================================================*/
static	PRM_TBL	*search_prm(CMD_TBL *cmdtp, char *prm_name)
{
	int	i;		/* ���[�v�J�E���^ */
	PRM_TBL	*prmtp;		/* �p�����[�^��ރe�[�u���|�C���^ */

	/* �p�����[�^������ */
	for (i = 0, prmtp = cmdtp->ptr; i < cmdtp->n; i++, prmtp++) {
		if (prmtp->name == NULL)
			return(prmtp);
		if (strcmp(prm_name, prmtp->name) == 0)
			return(prmtp);
	}

	return(NULL);
}