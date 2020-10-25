/*	FILE DESCRIPTION
 *
 *		FILE NAME	: d2dbrs.c
 *		ROUTINE		: �k�`�f�[�^�x�[�X��������
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
 *			�������C������
 *
 *		FUNC.NAME	: d2dbrs()
 *		INPUT		: ladir = �f�B���N�g����
 *				: dev = �f�o�C�X��
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
 *			�t�@�C���ǂݍ��݁������t�@�C���X�V
 *
 *		FUNC.NAME	: dbrs10()
 *		INPUT		: dev = �f�o�C�X��
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
	switch (atoi(buf)) {	/* �ۑ���ʃ`�F�b�N */
	case 1:			/* �S�k�`�f�[�^�Z�b�g */
		err = dbrs20();
		break;
	case 2:			/* �S���[�U���� */
		err = d2idmk(".", "IND.USR");
		break;
	case 4:			/* �ʃO���[�v */
		err = dbrs30(fp);
		break;
	case 5:			/* �ʂk�`�f�[�^�Z�b�g */
	case 6:			/* �ʃ��[�U�����t�@�C�� */
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
 *			�S�����t�@�C���X�V
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
 *			�����t�@�C���X�V
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
 *			�ʍ����t�@�C���X�V
 *
 *		FUNC.NAME	: dbrs40()
 *		INPUT		: fp = �ۑ���ʃt�@�C���|�C���^
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
