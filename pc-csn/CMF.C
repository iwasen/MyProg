/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h�t�@�C������			*
 *		�t�@�C����	: cmf.c					*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	�����֐�	*/
static	int	cmf_sort(CMF_TBL *, CMF_TBL *);

/*=======================================================================
 |
 |		�R�}���h�t�@�C����������
 |
 |	void	find_cmf()
 |
 =======================================================================*/
void	find_cmf()
{
	struct	find_t	find;	/* �t�@�C�������\���� */
	char	*cmf_path;	/* �R�}���h�t�@�C���p�X�� */
	int	st;		/* �t�@�C���������� */
	int	count;		/* �R�}���h�t�@�C���� */
	int	len;		/* �R�}���h�t�@�C���̐������̒��� */
	FILE	*fp;		/* �t�@�C���|�C���^ */
	CMF_TBL	*cmfp;		/* �R�}���h�t�@�C���e�[�u���|�C���^ */

	cmf_path = make_cmfpath("*.CMF");	/* �p�X���Z�b�g */

	/* �R�}���h�t�@�C������ */
	count = 0;
	st = _dos_findfirst(cmf_path, 0, &find);
	for (count = 0; st == 0; count++)
		st = _dos_findnext(&find);

	/* ���݂̃R�}���h�t�@�C���e�[�u������� */
	if (cmf_tbl != NULL)
		free(cmf_tbl);

	/* �V���ɃR�}���h�t�@�C���e�[�u�����m�� */
	cmf_tbl = talloc(sizeof(CMF_TBL) * count);

	/* �R�}���h�t�@�C���e�[�u���쐬 */
	st = _dos_findfirst(cmf_path, 0, &find);
	for (count = 0, cmfp = cmf_tbl; st == 0; ) {
		/* �R�}���h�t�@�C���I�[�v�� */
		if ((fp = fopen(make_cmfpath(find.name), "r")) != NULL) {
			/* �g���q����菜�� */
			*strchr(find.name, '.') = '\0';

			/* �R�}���h�t�@�C�����Z�[�u */
			strcpy(cmfp->name, find.name);

			/* �������ǂݍ��� */
			fgets(cmfp->guide, 80, fp);
			len = strlen(cmfp->guide);
			if (cmfp->guide[len-1] == '\n')
				cmfp->guide[len-1] = '\0';

			/* �R�}���h�t�@�C���N���[�Y */
			fclose(fp);

			count++;	/* �R�}���h�t�@�C�����X�V */
			cmfp++;	/* �R�}���h�t�@�C���e�[�u���|�C���^�X�V */
		}
		st = _dos_findnext(&find);
	}

	n_cmf = count;		/* �R�}���h�t�@�C�����Z�[�u */

	/* �R�}���h�e�[�u�����R�}���h�t�@�C�����Ń\�[�g */
	qsort(cmf_tbl, n_cmf, sizeof(CMF_TBL), cmf_sort);
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C���\�[�g����
 |
 |	int	cmf_sort(p1, p2)
 |
 |		CMF_TBL	*p1;		�R�}���h�t�@�C���e�[�u���|�C���^�P
 |		CMF_TBL	*p2;		�R�}���h�t�@�C���e�[�u���|�C���^�Q
 |
 |		�Ԓl			�R�}���h�t�@�C�����̔�r����
 |
 =======================================================================*/
static	int	cmf_sort(CMF_TBL *p1, CMF_TBL *p2)
{
	/* �R�}���h�t�@�C�����̔�r */
	return(strcmp(p1->name, p2->name));
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C���ǂݍ��ݏ���
 |
 |	void	read_cmf()
 |
 =======================================================================*/
void	read_cmf()
{
	FILE	*fp;		/* �t�@�C���|�C���^ */
	char	buf[256];	/* �R�}���h�t�@�C���ǂݍ��݃o�b�t�@ */

	/* �R�}���h�t�@�C���I�[�v�� */
	if ((fp = fopen(cmf_name, "r")) == NULL)
		return;

	/* �@�\�T�v�ǂݍ��� */
	fgets(cmf_guide, sizeof(cmf_guide), fp);
	cmf_guide[strlen(cmf_guide) - 1] = '\0';

	/* �R�}���h�t�@�C���ǂݍ��� */
	while (fgets(buf, sizeof(buf), fp) != NULL) {
		buf[strlen(buf) - 1] = '\0';
		enq_cmf(buf);
	}

	fclose(fp);
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C���������ݏ���
 |
 |	void	write_cmf()
 |
 =======================================================================*/
void	write_cmf()
{
	char	*cmd;		/* �R�}���h�f�[�^�|�C���^ */
	FILE	*fp;		/* �t�@�C���|�C���^ */

	if (cmf_que != NULL) {
		/* �R�}���h�t�@�C�I�[�v�� */
		if ((fp = fopen(cmf_name, "w")) != NULL) {
			/* �������������� */
			fprintf(fp, "%s\n", cmf_guide);

			/* �R�}���h�f�[�^�������� */
			while ((cmd = deq_cmf()) != NULL) {
				fprintf(fp, "%s\n", cmd);
				free(cmd);
			}

			fclose(fp);	/* �R�}���h�t�@�C���N���[�Y */

			/* �R�}���h�t�@�C���쐬���b�Z�[�W�o�� */
			dsp_loc_msg(LOC_MSG_MKCMF, cmf_name);
		}
	}
}

/*=======================================================================
 |
 |		�R�}���h�L���[�o�^����
 |
 |	void	enq_cmf(cmd)
 |
 |		char	*cmd;		�R�}���h
 |
 =======================================================================*/
void	enq_cmf(char *cmd)
{
	en_queue(&cmf_que, cmd, strlen(cmd) + 1);
}

/*=======================================================================
 |
 |		�R�}���h�L���[���o������
 |
 |	char	*deq_cmf()
 |
 |		�Ԓl			�R�}���h�f�[�^
 |
 =======================================================================*/
char	*deq_cmf()
{
	return(de_queue(&cmf_que));
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C���p�X���ҏW����
 |
 |	char	*make_cmfpath(cmf)
 |
 |		char	*cmf;		�R�}���h�t�@�C����
 |
 |		�Ԓl			�R�}���h�t�@�C���p�X��
 |
 =======================================================================*/
char	*make_cmfpath(char *cmf)
{
	static	char	cmf_path[SZ_PATH];	/* �R�}���h�t�@�C���p�X�� */
	char	*p;

	/* �R�}���h�t�@�C���p�X���̃Z�b�g */
	strcpy(cmf_path, ssg.cmf.cmd_path);
	if ((p = strchr(cmf_path, ':')) != NULL)
		p++;
	else
		p = cmf_path;

	if (*p != '\0' && p[strlen(p)-1] != '\\')
		strcat(cmf_path, "\\");

	strcat(cmf_path, cmf);		/* �R�}���h�t�@�C�����̃Z�b�g */

	return(cmf_path);
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C�����s�J�n����
 |
 |	int	start_cmf(cmf_name)
 |
 |		char	*cmf_name;	�R�}���h�t�@�C����
 |
 |		�Ԓl			�O�F����I���@�P�F�ُ�I��
 |
 =======================================================================*/
int	start_cmf(char *cmf_name)
{
	char	cmf[13];	/* �R�}���h�t�@�C���� */
	char	buf[256];	/* �R�}���h�t�@�C���ǂݍ��݃o�b�t�@ */

	/* ���łɃR�}���h�t�@�C�����s���������璆�~ */
	if (exec_cmf_flag) {
		fclose(fp_cmf);
		exec_cmf_flag = 0;
	}

	/* �R�}���h�t�@�C�����ҏW */
	sprintf(cmf, "%s.CMF", cmf_name);

	/* �R�}���h�t�@�C���I�[�v�� */
	if ((fp_cmf = fopen(make_cmfpath(cmf), "r")) == NULL) {
		dsp_loc_msg(LOC_MSG_ERCMF, cmf);
		return(1);
	}
	exec_cmf_flag = 1;	/* �R�}���h�t�@�C�����s���t���O�n�m */

	/* �@�\�T�v�ǂݔ�΂� */
	fgets(buf, sizeof(buf), fp_cmf);

	/* �R�}���h�t�@�C�����s */
	exec_cmf();

	return(0);
}

/*=======================================================================
 |
 |		�R�}���h�t�@�C�����s����
 |
 |	void	exec_cmf()
 |
 =======================================================================*/
void	exec_cmf()
{
	char	buf[256];
	int	len, c;

	for (;;) {
		/* �R�}���h�t�@�C���ǂݍ��� */
		if (fgets(buf, sizeof(buf), fp_cmf) == NULL) {
			fclose(fp_cmf);
			exec_cmf_flag = 0;
			break;
		}
		len = strlen(buf);
		if (buf[len-1] == '\n')
			buf[len-1] = '\0';

		/* �R�}���h�t�@�C�����s */
		if (exec_cmd(buf) != 0) {
			if (csg.opt.cmd_err) {
				fclose(fp_cmf);
				exec_cmf_flag = 0;
				break;
			}
		}

		/* �d�n�e�Ȃ�I�� */
		if ((c = fgetc(fp_cmf)) == EOF) {
			fclose(fp_cmf);
			exec_cmf_flag = 0;
			break;
		}
		ungetc(c, fp_cmf);

		/* �I�����C���R�}���h�������烁�b�Z�[�W��M�̂��߂ʂ��� */
		if (buf[0] == csg.nsg.online_id)
			break;
	}
}