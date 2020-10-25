/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �r�f�֘A����				*
 *		�t�@�C����	: sg.c					*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*=======================================================================
 |
 |		�R���\�[���r�f�t�@�C������
 |
 |	int	find_csg()
 |
 |		�Ԓl			�O�F���������@�P�F�݂���Ȃ�
 |
 =======================================================================*/
int	find_csg()
{
	struct	find_t	find;	/* �t�@�C�������p�G���A */
	int	st;		/* �t�@�C���������� */

	st = _dos_findfirst("*.CSG", 0, &find);
	if (st == 0)
		strcpy(csg_file, find.name);	/* �t�@�C�����Z�[�u */

	return(st);
}

/*=======================================================================
 |
 |		�R���\�[���r�f�t�@�C���ǂݍ���
 |
 |	void	read_csg()
 |
 =======================================================================*/
void	read_csg()
{
	FILE	*fp;		/* �t�@�C���|�C���^ */

	/* �R���\�[���r�f�t�@�C���ǂݍ��� */
	if (csg_file[0] != '\0' && (fp = fopen(csg_file, "rb")) != NULL) {
		fread(&csg, sizeof(CSG), 1, fp);
		fclose(fp);
	} else
		csg = csg_default;

	/* �V�X�e���G��ʃt�@�C���ǂݍ��� */
	if (csg.nsg.grf_file[0] != '\0'
			&& (fp = fopen(csg.nsg.grf_file, "rb")) != NULL) {
		fread(csg_grf, sizeof(csg_grf), 1, fp);
		fclose(fp);
	} else {
		/* �G��ʃt�@�C���������ꍇ */
		memset(csg_grf, 0, sizeof(csg_grf));
		grfputs(2, 1, "�v������������", C_GREEN, csg_grf);
		grfputs(6, 4, csg.nsg.sys_name, C_RED, csg_grf);
		grfputs(14, 7, "�V�X�e��", C_GREEN, csg_grf);
		grfputs(1, 11, "?", C_BLUE, csg_grf);
		grfputs(7, 11, "?", C_BLUE, csg_grf);
		grfputs(13, 11, "?", C_BLUE, csg_grf);
		grfputs(19, 11, "?", C_BLUE, csg_grf);
		grfputs(25, 11, "??", C_BLUE, csg_grf);
	}
}

/*=======================================================================
 |
 |		�R���\�[���r�f�t�@�C����������
 |
 |	void	write_csg()
 |
 =======================================================================*/
void	write_csg()
{
	FILE	*fp;		/* �t�@�C���|�C���^ */

	/* �R���\�[���r�f�t�@�C���������� */
	if (csg_file[0] != '\0' && (fp = fopen(csg_file, "wb")) != NULL) {
		fwrite(&csg, sizeof(CSG), 1, fp);
		fclose(fp);
	}
}

/*=======================================================================
 |
 |		�V�X�e���r�f�t�@�C������
 |
 |	int	find_ssg(conxxxtion)
 |
 |		int	conxxxtion;	�R�l�N�V�����ԍ�
 |
 |		�Ԓl			�O�F���������@�P�F�݂���Ȃ�
 |
 =======================================================================*/
int	find_ssg(int conxxxtion)
{
	struct	find_t	find;	/* �t�@�C�������p�G���A */
	char	con_no[4];	/* �R�l�N�V�����ԍ� */
	int	st;		/* �t�@�C���������� */

	/* �R�l�N�V�����ԍ��𕶎���ɕϊ� */
	sprintf(con_no, "%02d.", conxxxtion);

	/* �t�@�C������ */
	st = _dos_findfirst("*.SSG", 0, &find);
	while (st == 0) {
		if (strstr(find.name, con_no) != NULL)
			break;
		st = _dos_findnext(&find);
	}
	if (st == 0)
		strcpy(ssg_file, find.name);	/* �t�@�C�����Z�[�u */

	return(st);
}

/*=======================================================================
 |
 |		�V�X�e���r�f�t�@�C���ǂݍ���
 |
 |	void	read_ssg()
 |
 =======================================================================*/
void	read_ssg()
{
	FILE	*fp;		/* �t�@�C���|�C���^ */

	/* �R���\�[���r�f�t�@�C���ǂݍ��� */
	if (ssg_file[0] != '\0' && (fp = fopen(ssg_file, "rb")) != NULL) {
		fread(&ssg, sizeof(SSG), 1, fp);
		fclose(fp);
	} else
		memset(&ssg, 0, sizeof(ssg));

	/* �V�X�e���G��ʃt�@�C���ǂݍ��� */
	if (ssg.nsg.grf_file[0] != '\0'
			&& (fp = fopen(ssg.nsg.grf_file, "rb")) != NULL) {
		fread(ssg_grf, sizeof(ssg_grf), 1, fp);
		fclose(fp);
	} else {
		/* �G��ʃt�@�C���������ꍇ */
		memset(ssg_grf, 0, sizeof(ssg_grf));
		grfputs(6, 4, "�y", C_BLUE, ssg_grf);
		grfputs(10, 4, ssg.nsg.sys_name, C_CYAN, ssg_grf);
		grfputs(22, 4, "�z", C_BLUE, ssg_grf);
		grfputs(4, 8, csg.nsg.sys_name, C_YELLOW, ssg_grf);
		grfputs(14, 10, "�V�X�e��", C_YELLOW, ssg_grf);
	}
}

/*=======================================================================
 |
 |		�V�X�e���r�f�t�@�C����������
 |
 |	void	write_ssg()
 |
 =======================================================================*/
void	write_ssg()
{
	FILE	*fp;		/* �t�@�C���|�C���^ */

	/* �R���\�[���r�f�t�@�C���������� */
	if (ssg_file[0] != '\0' && (fp = fopen(ssg_file, "wb")) != NULL) {
		fwrite(&ssg, sizeof(SSG), 1, fp);
		fclose(fp);
	}
}