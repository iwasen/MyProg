/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R�}���h�t�@�C���ϊ����[�e�B���e�B�[	*
 *		�t�@�C����	: cmdcnv.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	�ő啶����	*/
#define	LEN_CMS		34
#define	LEN_CMSGUIDE	72
#define	LEN_CMDGUIDE	56
#define	LEN_PRMGUIDE	56
#define	LEN_PRTGUIDE	23

/*	���ʃG���A	*/
static	int	unget_flg;
static	char	line_buf[256];
static	int	line_no;

/*	�����֐���`	*/
static	CMS_TBL	*read_file(FILE *);
static	CMD_TBL	*read_cmd(FILE *, int *);
static	PRM_TBL	*read_prm(FILE *, int *);
static	PRT_TBL	*read_prt(FILE *, int *);
static	SEL_TBL	*read_sel(FILE *);
static	NUM_TBL	*read_num(FILE *);
static	NAM_TBL	*read_nam(FILE *);
static	GUD_TBL	*read_gud(FILE *);
static	int	check_key(FILE *);
static	char	*getstr(FILE *, int);
static	int	gettok(FILE *, char *);
static	int	fgetchr(FILE *);
static	void	*talloc(int);
static	void	*trealloc(void *, int);
static  void	syntax_err(void);
static  void	length_err(char *, int);
static	void	write_file(FILE *, CMS_TBL *);
static	void	write_cmd(FILE *, CMD_TBL *, int);
static	void	write_prm(FILE *, PRM_TBL *, int);
static	void	write_prt(FILE *, PRT_TBL *, int);
static	void	write_sel(FILE *, SEL_TBL *);
static	void	write_num(FILE *, NUM_TBL *);
static	void	write_nam(FILE *, NAM_TBL *);
static	void	write_gud(FILE *, GUD_TBL *);
static	void	outstr(FILE *, char *);
static	void	outnum(FILE *, int);

/*=======================================================================
 |
 |		���C������
 |
 |	int	main()
 |
 =======================================================================*/
main()
{
	char	read_file_name[64], write_file_name[64];
	char	*p;
	FILE	*fp;
	CMS_TBL	*ptr;
	int	ch;

	/* �^�C�g���\�� */
	printf("�������@�R�}���h�t�@�C�� �t�H�[�}�b�g�ϊ����[�e�B���e�B�[�@������\n\n");

	/* �R�}���h�t�@�C�������� */
	printf("�R�}���h�t�@�C��������͂��ĉ����� [.TXT] ==>");
	scanf("%s", read_file_name);

	/* �I�����C���^���[�J���I�� */
	printf("�O�F�I�����C���@�P�F���[�J�� ==>");
	for (;;) {
		ch = getch();
		if (ch == '0' || ch == '1') {
			printf("%c\n\n", ch);
			break;
		}
	}

	/* �t�@�C�����ҏW */
	strcpy(write_file_name, read_file_name);
	if ((p = strrchr(read_file_name, '.')) == NULL) {
		strcat(read_file_name, ".TXT");
		strcat(write_file_name, ch == '0' ? ".CMS" : ".LOC");
	} else
		strcpy(strrchr(write_file_name, '.'), ch == '0' ? ".CMS" : ".LOC");

	/* ���̓t�@�C�����\�� */
	printf("���̓t�@�C�� %s\n",read_file_name);

	/* ���̓t�@�C���I�[�v�� */
	if ((fp = fopen(read_file_name, "r")) == 0) {
		printf("�t�@�C�����I�[�v���ł��܂��� %s\n", read_file_name);
		exit(1);
	}

	/* ���̓t�@�C���ǂݍ��� */
	ptr = read_file(fp);
	fclose(fp);

	/* �o�̓t�@�C�����\�� */
	printf("�o�̓t�@�C�� %s\n",write_file_name);

	/* �o�̓t�@�C���I�[�v�� */
	if ((fp = fopen(write_file_name, "wb")) == 0) {
		printf("�t�@�C�����I�[�v���ł��܂��� %s\n", write_file_name);
		exit(1);
	}

	/* �o�̓t�@�C���������� */
	write_file(fp, ptr);
	fclose(fp);

	printf("\n����I�����܂���\n");

	return(0);
}

/*=======================================================================
 |
 |		���̓t�@�C���i�e�L�X�g�t�@�C���j�ǂݍ���
 |
 |	CMS_TBL	*read_file(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	CMS_TBL	*read_file(FILE *fp)
{
	CMS_TBL	*ptr;

	ptr = NULL;
	if (check_key(fp) == KWD_TTL) {
		ptr = talloc(sizeof(CMS_TBL));	/* �������m�� */
		ptr->title = getstr(fp, 1);	/* �^�C�g���ǂݍ��� */
		if (strlen(ptr->title) > LEN_CMS)
			length_err(ptr->title, LEN_CMS);
		ptr->guide = getstr(fp, 1);	/* �����ǂݍ��� */
		if (strlen(ptr->guide) > LEN_CMSGUIDE)
			length_err(ptr->guide, LEN_CMSGUIDE);
		ptr->ptr = read_cmd(fp, &ptr->n); /* �R�}���h�f�[�^�ǂݍ��� */
	}
	return(ptr);
}

/*=======================================================================
 |
 |		�R�}���h�f�[�^�ǂݍ���
 |
 |	CMD_TBL	*read_cmd(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	*n;		�R�}���h��
 |
 =======================================================================*/
static	CMD_TBL	*read_cmd(FILE *fp, int *n)
{
	CMD_TBL	*ptr, *p;

	ptr = NULL;
	*n = 0;
	while (check_key(fp) == KWD_COM) {
		if (ptr == NULL)
			ptr = talloc(sizeof(CMD_TBL));	/* �������m�� */
		else
			ptr = trealloc(ptr, sizeof(CMD_TBL) * (*n + 1));

		p = &ptr[*n];
		p->name = getstr(fp, 1);	/* �R�}���h���ǂݍ��� */
		p->guide = getstr(fp, 1);	/* �����ǂݍ��� */
		if (strlen(p->guide) > LEN_CMDGUIDE)
			length_err(p->guide, LEN_CMDGUIDE);
		p->ptr = read_prm(fp, &p->n);	/* �p�����[�^��ޓǂݍ��� */

		(*n)++;
	}
	unget_flg = 1;
	return(ptr);
}

/*=======================================================================
 |
 |		�p�����[�^��ޓǂݍ���
 |
 |	PRM_TBL	*read_prm(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	*n;		�p�����[�^��ތ�
 |
 =======================================================================*/
static	PRM_TBL	*read_prm(FILE *fp, int *n)
{
	PRM_TBL	*ptr, *p;
	int	key;

	ptr = NULL;
	*n = 0;

	key = check_key(fp);
	unget_flg = 1;
	if (key == KWD_SEL || key == KWD_NUM
				|| key == KWD_NAM || key == KWD_MSG) {
		ptr = talloc(sizeof(PRM_TBL));	/* �������m�� */
		ptr->name = NULL;		/* �p�����[�^���ǂݍ��� */
		ptr->guide = NULL;		/* �����ǂݍ��� */
		ptr->ptr = read_prt(fp, &ptr->n);/* �p�����[�^�ǂݍ��� */
		(*n)++;
	} else {
	    while (check_key(fp) == KWD_PRM) {
		if (ptr == NULL)
			ptr = talloc(sizeof(PRM_TBL));	/* �������m�� */
		else
			ptr = trealloc(ptr, sizeof(PRM_TBL) * (*n + 1));

		p = &ptr[*n];
		p->name = getstr(fp, 1);	/* �p�����[�^���ǂݍ��� */
		p->guide = getstr(fp, 1);	/* �����ǂݍ��� */
		if (strlen(p->guide) > LEN_PRMGUIDE)
			length_err(p->guide, LEN_PRMGUIDE);
		p->ptr = read_prt(fp, &p->n);	/* �p�����[�^�ǂݍ��� */
		(*n)++;
	    }
	}
	unget_flg = 1;
	return(ptr);
}

/*=======================================================================
 |
 |		�e�p�����[�^�ǂݍ���
 |
 |	PRT_TBL	*read_prt(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	*n;		�p�����[�^��
 |
 =======================================================================*/
static	PRT_TBL	*read_prt(FILE *fp, int *n)
{
	PRT_TBL	*ptr, *p;
	int	loop_sw, key;

	ptr = NULL;
	*n = 0;
	loop_sw = 1;
	while ((key = check_key(fp)) == KWD_SEL || key == KWD_NUM
				|| key == KWD_NAM || key == KWD_MSG) {
		if (ptr == NULL)
			ptr = talloc(sizeof(PRT_TBL));	/* �������m�� */
		else
			ptr = trealloc(ptr, sizeof(PRT_TBL) * (*n + 1));

		p = &ptr[*n];
		p->type = key;			/* �p�����[�^��ރZ�b�g */
		p->guide = getstr(fp, 1);	/* �����ǂݍ��� */
		if (strlen(p->guide) > LEN_PRTGUIDE)
			length_err(p->guide, LEN_PRTGUIDE);
		switch (key) {
		case KWD_SEL:	/* �I������ */
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

		(*n)++;
	}

	unget_flg = 1;

	return(ptr);
}

/*=======================================================================
 |
 |		�I�����ړǂݍ���
 |
 |	SEL_TBL	*read_sel(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	SEL_TBL	*read_sel(FILE *fp)
{
	SEL_TBL	*ptr;
	char	*p, *sp;

	ptr = talloc(sizeof(SEL_TBL));	/* �������m�� */
	ptr->n = 0;
	while (p = getstr(fp, 0)) {	/* �I�����ړǂݍ��� */
		ptr = trealloc(ptr, sizeof(SEL_TBL)+ptr->n*sizeof(ptr->ptr));
		ptr->ptr[ptr->n][0] = p;
		if (p[strlen(p)-1] == '*')
			ptr->ptr[ptr->n][1] = &p[strlen(p)];
		else if ((sp = strchr(p, '(')) != NULL) {
			sp++;
			p = talloc(strlen(sp) + 1);
			strcpy(p, sp);
			if ((sp = strchr(p, ')')) != NULL)
				*sp = '\0';
			ptr->ptr[ptr->n][1] = p;
		} else
			ptr->ptr[ptr->n][1] = p;
		ptr->n++;
	}

	return(ptr);
}

/*=======================================================================
 |
 |		���l���ړǂݍ���
 |
 |	NUM_TBL	*read_num(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	NUM_TBL	*read_num(FILE *fp)
{
	NUM_TBL	*ptr;
	char	*p;

	ptr = talloc(sizeof(NUM_TBL));		/* �������m�� */
	if ((p = getstr(fp, 0)) != NULL) {	/* �ŏ��l�ǂݍ��� */
		ptr->min_flg = 1;		/* �ŏ��l�w��L�� */
		ptr->min = atoi(p);		/* �ŏ��l�Z�b�g */
		if ((p = strstr(p, "SYC")) != NULL)
			ptr->min_syc = atoi(p + 3);	/* �V�X�e���萔�L�� */
		else
			ptr->min_syc = 0;		/* �V�X�e���萔���� */
	} else
		ptr->min_flg = 0;		/* �ŏ��l�w�薳�� */

	if ((p = getstr(fp, 0)) != NULL) {	/* �ő�l�ǂݍ��� */
		ptr->max_flg = 1;		/* �ő�l�w��L�� */
		ptr->max = atoi(p);		/* �ő�l�Z�b�g */
		if ((p = strstr(p, "SYC")) != NULL)
			ptr->max_syc = atoi(p + 3);	/* �V�X�e���萔�L�� */
		else
			ptr->max_syc = 0;		/* �V�X�e���萔���� */
	} else
		ptr->max_flg = 0;		/* �ő�l�w�薳�� */

	ptr->prmid = getstr(fp, 0);	/* �p�����[�^�h�c�ǂݍ��� */
	ptr->sel = getstr(fp, 0);	/* �I�����ړǂݍ��� */

	return(ptr);
}

/*=======================================================================
 |
 |		���̍��ړǂݍ���
 |
 |	NAM_TBL	*read_nam(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	NAM_TBL	*read_nam(FILE *fp)
{
	NAM_TBL	*ptr;
	char	*p;

	ptr = talloc(sizeof(NAM_TBL));	/* �������m�� */

	p = getstr(fp, 0);	/* �ő啶�����ǂݍ��� */
	ptr->length = (p != NULL) ? atoi(p) : 0;
	ptr->sel = getstr(fp, 0);	/* �I�����ړǂݍ��� */
	ptr->ext1 = getstr(fp, 0);	/* �g���q�P�ǂݍ��� */
	ptr->ext2 = getstr(fp, 0);	/* �g���q�Q�ǂݍ��� */

	return(ptr);
}

/*=======================================================================
 |
 |		�������ړǂݍ���
 |
 |	GUD_TBL	*read_gud(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	GUD_TBL	*read_gud(FILE *fp)
{
	GUD_TBL	*ptr;
	char	*p;

	ptr = talloc(sizeof(GUD_TBL));	/* �������m�� */

	p = getstr(fp, 0);		/* �ő啶�����ǂݍ��� */
	ptr->length = (p != NULL) ? atoi(p) : 0;

	return(ptr);
}

/*=======================================================================
 |
 |		�L�[���[�h�`�F�b�N
 |
 |	int	check_key(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	int	check_key(FILE *fp)
{
	char	buf[80];
	int	st;

	switch (gettok(fp, buf)) {
	case EOF:
		st = EOF;
		break;
	case 1:		/* �L�[���[�h */
		if (strcmp(buf, "@TTL") == 0)
			st = KWD_TTL;
		else if (strcmp(buf, "@COM") == 0)
			st = KWD_COM;
		else if (strcmp(buf, "@PRM") == 0)
			st = KWD_PRM;
		else if (strcmp(buf, "@SEL") == 0)
			st = KWD_SEL;
		else if (strcmp(buf, "@NUM") == 0)
			st = KWD_NUM;
		else if (strcmp(buf, "@NAM") == 0)
			st = KWD_NAM;
		else if (strcmp(buf, "@MSG") == 0)
			st = KWD_MSG;
		else	
			syntax_err();
		break;
	default:
		syntax_err();
	}

	return(st);
}

/*=======================================================================
 |
 |		������ǂݍ���
 |
 |	char	*getstr(fp, flag)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	flag;		�O�F�ȗ��@�P�F�ȗ��s��
 |
 =======================================================================*/
static	char	*getstr(FILE *fp, int flag)
{
	char	buf[80];
	char	*p;

	switch (gettok(fp, buf)) {
	case 0:		/* �ȗ� */
	case EOF:
		if (flag != 0)
			syntax_err();
		p = NULL;
		break;
	case 1:		/* �L�[���[�h */
		if (flag != 0)
			syntax_err();
		unget_flg = 1;
		p = NULL;
		break;
	case 2:		/* ���̑��̕��� */
		p = talloc(strlen(buf)+1);
		strcpy(p, buf);
		break;
	}
	return(p);
}

/*=======================================================================
 |
 |		�g�[�N�����o��
 |
 |	int	gettok(fp, bufp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		char	*bufp;		�g�[�N���i�[�G���A
 |
 =======================================================================*/
static	int	gettok(FILE *fp, char *bufp)
{
	int	mode, ch, loop_sw;
	char	*p;
	static	char	sv_buf[80];
	static	int	sv_mode;

	if (unget_flg) {
		unget_flg = 0;
		strcpy(bufp, sv_buf);
		return(sv_mode);
	}

	mode = 0;
	p = bufp;
	loop_sw = 1;
	while (loop_sw) {
		ch = fgetchr(fp);
		switch (ch) {
		case EOF:
			mode = EOF;
			loop_sw = 0;
			break;
		case '@':
			if (mode == 0)
				mode = 1;
			*p++ = ch;
			break;
		case ' ':
		case '\t':
			if (mode == 1)
				loop_sw = 0;
			else if (mode == 2)
				*p++ = ch;
			break;
		case ',':
			loop_sw = 0;
			break;
		case '\n':
			if (mode != 0)
				loop_sw = 0;
			break;
		default:
			if (mode == 0)
				mode = 2;
			*p++ = ch;
			break;
		}
	}

	for ( ;p != bufp; p--) {
		if (*(p - 1) != ' ' && *(p - 1) != '\t')
			break;
	}
	*p = '\0';

	strcpy(sv_buf, bufp);
	sv_mode = mode;

	return(mode);
}

/*=======================================================================
 |
 |		�P�����ǂݍ��݁i�R�����g�ǂ݂Ƃ΂��j
 |
 |	int	fgetchr(fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	int	fgetchr(FILE *fp)
{
	int	ch, mode;
	static	char	*p = line_buf;

	mode = 0;
	for (;;) {
		if ((ch = *p) == '\0') {
			if (fgets(line_buf, sizeof(line_buf), fp) == NULL)
				return(EOF);
			p = line_buf;
			line_no++;
			continue;
		}
		p++;
			
		if (mode == 0 && ch == '/')
			mode = 1;
		else if (mode == 1 && ch == '*')
			mode = 2;
		else if (mode == 2 && ch == '*')
			mode = 3;
		else if (mode == 3 && ch == '/')
			mode = 0;
		else if (mode == 0)
			break;
		else if (mode == 1) {
			p--;
			ch = '/';
			break;
		} else if (mode == 3)
			mode = 2;
	}

	return(ch);
}

/*=======================================================================
 |
 |		�������m�ہi�G���[�`�F�b�N�L��j
 |
 |	void	*talloc(size)
 |
 |		int	size;		�m�ۂ��郁�����T�C�Y
 |
 =======================================================================*/
static	void	*talloc(int size)
{
	void	*p;

	if ((p = malloc(size)) == NULL) {
		printf("�������̊m�ۂɎ��s���܂���\n");
		exit(1);
	}
	return(p);
}

/*=======================================================================
 |
 |		�������T�C�Y�ύX�i�G���[�`�F�b�N�L��j
 |
 |	void	*trealloc(p, size)
 |
 |		void	*p;		�ύX�O�̃������|�C���^
 |		int	size;		�ύX���郁�����T�C�Y
 |
 =======================================================================*/
static	void	*trealloc(void *p, int size)
{
	if ((p = realloc(p, size)) == NULL) {
		printf("�������̊m�ۂɎ��s���܂���\n");
		exit(1);
	}
	return(p);
}

/*=======================================================================
 |
 |		�V���^�b�N�X�G���[����
 |
 |	void	syntax_err()
 |
 =======================================================================*/
static	void	syntax_err()
{
	printf("\n�L�q�Ɍ�肪����܂� : (�s�ԍ� %d)\n%s\n", line_no, line_buf);
	printf("\n�ُ�I�����܂���\n");
	exit(1);
}

/*=======================================================================
 |
 |		�����I�[�o�G���[����
 |
 |	void	length_err()
 |
 =======================================================================*/
static	void	length_err(char *p, int len)
{
	printf("\n�����񂪒������܂� : (�s�ԍ� %d)\n", line_no);
	printf("�y%s�z (%d�����ȉ��ɂ��ĉ�����)\n", p, len);
	printf("\n�ُ�I�����܂���\n");
	exit(1);
}

/*=======================================================================
 |
 |		�t�@�C����������
 |
 |	void	write_file(fp, ptr)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		CMS_TBL	*ptr;		�^�C�g���f�[�^	
 |
 =======================================================================*/
static	void	write_file(FILE *fp, CMS_TBL *ptr)
{
	if (ptr != NULL) {
		outstr(fp, ptr->title);		/* �^�C�g���������� */
		outstr(fp, ptr->guide);		/* ������������ */
		outnum(fp, ptr->n);		/* �R�}���h���������� */
		write_cmd(fp, ptr->ptr, ptr->n); /* �R�}���h�f�[�^�������� */
	}
}

/*=======================================================================
 |
 |		�R�}���h�f�[�^��������
 |
 |	void	write_cmd(fp, ptr, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		CMD_TBL	*ptr;		�R�}���h�f�[�^
 |		int	n;		�R�}���h��
 |
 =======================================================================*/
static	void	write_cmd(FILE *fp, CMD_TBL *ptr, int n)
{
	while (n--) {
		outstr(fp, ptr->name);	/* �R�}���h���������� */
		outstr(fp, ptr->guide);	/* ������������ */
		outnum(fp, ptr->n);	/* �p�����[�^��ތ��������� */
		write_prm(fp, ptr->ptr, ptr->n); /* �p�����[�^��ޏ������� */
		ptr++;
	}
}

/*=======================================================================
 |
 |		�p�����[�^��ރf�[�^��������
 |
 |	void	write_prm(fp, ptr, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		PRM_TBL	*ptr;		�p�����[�^��ރf�[�^
 |		int	n;		�p�����[�^��ތ�
 |
 =======================================================================*/
static	void	write_prm(FILE *fp, PRM_TBL *ptr, int n)
{
	while (n--) {
		outstr(fp, ptr->name);		/* �p�����[�^���������� */
		outstr(fp, ptr->guide);		/* ������������ */
		outnum(fp, ptr->n);		/* �p�����[�^���������� */
		write_prt(fp, ptr->ptr, ptr->n); /* �p�����[�^�f�[�^�������� */
		ptr++;
	}
}

/*=======================================================================
 |
 |		�p�����[�^�f�[�^��������
 |
 |	void	write_prt(fp, ptr, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		PRT_TBL	*ptr;		�p�����[�^�f�[�^
 |		int	n;		�p�����[�^��
 |
 =======================================================================*/
static	void	write_prt(FILE *fp, PRT_TBL *ptr, int n)
{
	while (n--) {
		outnum(fp, ptr->type);		/* �p�����[�^��ޏ������� */
		outstr(fp, ptr->guide);		/* ������������ */
		switch (ptr->type) {
		case KWD_SEL:	/* �������� */
			write_sel(fp, ptr->ptr.sel);
			break;
		case KWD_NUM:	/* ���l���� */
			write_num(fp, ptr->ptr.num);
			break;
		case KWD_NAM:	/* ���̍��� */
			write_nam(fp, ptr->ptr.nam);
			break;
		case KWD_MSG:	/* �������� */
			write_gud(fp, ptr->ptr.gud);
			break;
		}
		ptr++;
	}
}

/*=======================================================================
 |
 |		�I�����ڃf�[�^��������
 |
 |	void	write_sel(fp, ptr)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		SEL_TBL	*ptr;		�I�����ڃf�[�^
 |
 =======================================================================*/
static	void	write_sel(FILE *fp, SEL_TBL *ptr)
{
	int	i;

	outnum(fp, ptr->n);		/* �I�����ڐ��������� */
	for (i = 0; i < ptr->n; i++) {
		outstr(fp, ptr->ptr[i][0]); /* �I�����ځi�\���j�������� */
		outstr(fp, ptr->ptr[i][1]); /* �I�����ځi���́j�������� */
	}
}

/*=======================================================================
 |
 |		���l���ڃf�[�^��������
 |
 |	void	write_num(fp, ptr)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		NUM_TBL	*ptr;		���l���ڃf�[�^
 |
 =======================================================================*/
static	void	write_num(FILE *fp, NUM_TBL *ptr)
{
	outnum(fp, ptr->min_flg);	/* �ŏ��l�w��t���O�������� */
	outnum(fp, ptr->min);		/* �ŏ��l�������� */
	outnum(fp, ptr->min_syc);	/* �ŏ��l�V�X�e���萔�������� */
	outnum(fp, ptr->max_flg);	/* �ő�l�w��t���O�������� */
	outnum(fp, ptr->max);		/* �ő�l�������� */
	outnum(fp, ptr->max_syc);	/* �ő�l�V�X�e���萔�������� */
	outstr(fp, ptr->prmid);		/* �p�����[�^�h�c�������� */
	outstr(fp, ptr->sel);		/* �I�����ڏ������� */
}

/*=======================================================================
 |
 |		���̍��ڃf�[�^��������
 |
 |	void	write_nam(fp, ptr)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		NAM_TBL	*ptr;		���̍��ڃf�[�^
 |
 =======================================================================*/
static	void	write_nam(FILE *fp, NAM_TBL *ptr)
{
	outnum(fp, ptr->length);	/* �ő啶������������ */
	outstr(fp, ptr->sel);		/* �I�����ڏ������� */
	outstr(fp, ptr->ext1);		/* �g���q�P�������� */
	outstr(fp, ptr->ext2);		/* �g���q�Q�������� */
}

/*=======================================================================
 |
 |		�������ڃf�[�^��������
 |
 |	void	write_gud(fp, ptr)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		GUD_TBL	*ptr;		�������ڃf�[�^
 |
 =======================================================================*/
static	void	write_gud(FILE *fp, GUD_TBL *ptr)
{
	outnum(fp, ptr->length);	/* �ő啶������������ */
}

/*=======================================================================
 |
 |		�����񏑂�����
 |
 |	void	outstr(fp, p)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		char	*p;		������
 |
 =======================================================================*/
static	void	outstr(FILE *fp, char *p)
{
	int	len;

	if (p != NULL) {
		len = strlen(p);		/* �������o�� */
		outnum(fp, len);		/* ������������ */
		fwrite(p, 1, strlen(p), fp);	/* �����񏑂����� */
	} else 
		outnum(fp, -1);
}

/*=======================================================================
 |
 |		���l��������
 |
 |	void	outnum(fp, n)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |		int	n;		���l
 |
 =======================================================================*/
static	void	outnum(FILE *fp, int n)
{
	fwrite(&n, sizeof(int), 1, fp);
}