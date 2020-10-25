/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: ���b�Z�[�W�t�@�C���ϊ����[�e�B���e�B�[*
 *		�t�@�C����	: msgcnv.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

/*	���b�Z�[�W���	*/
typedef	struct	{
	int	msg_no;		/* ���b�Z�[�W�ԍ� */
	int	class;		/* �������b�Z�[�W�N���X�i�`�����a�j */
	int	n_kahenbu;	/* �ϕ��� */
	KAHENBU	*kahenbu;	/* �ϕ���� */
	char	*msg;		/* ���b�Z�[�W�f�[�^ */
} MSG;

/*	�����֐�	*/
static	void	*talloc(int);
static	void	*trealloc(void *, int);
static	MSG	*read_file(FILE *, int *);
static	int	getmsg(FILE *, MSG *);
static	int	readmsg(FILE *, int *, int *, char *);
static	char	*getline(char *, int, FILE *);
static	int	fgetchr(FILE *);
static	void	write_file(FILE *, MSG *, int);

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
	MSG	*ptr;
	int	n_msg;

	/* �^�C�g���\�� */
	printf("�������@���b�Z�[�W�t�@�C�� �t�H�[�}�b�g�ϊ����[�e�B���e�B�[�@������\n\n");

	/* ���b�Z�[�W�t�@�C�������� */
	printf("���b�Z�[�W�t�@�C��������͂��ĉ����� [.TXT] ==>");
	scanf("%s", read_file_name);

	/* �t�@�C�����ҏW */
	strcpy(write_file_name, read_file_name);
	if ((p = strrchr(read_file_name, '.')) == NULL) {
		strcat(read_file_name, ".TXT");
		strcat(write_file_name, ".MSG");
	} else
		strcpy(strrchr(write_file_name, '.'), ".MSG");

	/* ���̓t�@�C�����\�� */
	printf("���̓t�@�C�� %s\n",read_file_name);

	/* ���̓t�@�C���I�[�v�� */
	if ((fp = fopen(read_file_name, "r")) == 0) {
		printf("�t�@�C�����I�[�v���ł��܂��� %s\n", read_file_name);
		exit(1);
	}

	/* ���̓t�@�C���ǂݍ��� */
	ptr = read_file(fp, &n_msg);
	fclose(fp);

	/* �o�̓t�@�C�����\�� */
	printf("�o�̓t�@�C�� %s\n",write_file_name);

	/* �o�̓t�@�C���I�[�v�� */
	if ((fp = fopen(write_file_name, "wb")) == 0) {
		printf("�t�@�C�����I�[�v���ł��܂��� %s\n", write_file_name);
		exit(1);
	}

	/* �o�̓t�@�C���������� */
	write_file(fp, ptr, n_msg);
	fclose(fp);

	printf("\n����I�����܂���\n");

	return(0);
}

/*=======================================================================
 |
 |		���b�Z�[�W�t�@�C���ǂݍ��ݏ���
 |
 |	MSG	*read_file(fp, n_msg)
 |
 |		FILE	*fp;		�ǂݍ��݃t�@�C���|�C���^
 |		int	*n_msg;		���b�Z�[�W��
 |
 =======================================================================*/
static	MSG	*read_file(FILE *fp, int *n_msg)
{
	MSG	*ptr;

	ptr = NULL;
	*n_msg = 0;
	for (;;) {
		/* �������m�� */
		if (ptr == NULL)
			ptr = talloc(sizeof(MSG));
		else
			ptr = trealloc(ptr, sizeof(MSG) * (*n_msg + 1));
		if (getmsg(fp, ptr + *n_msg) == EOF)
			break;
		(*n_msg)++;
	}
	return(ptr);
}

/*=======================================================================
 |
 |		�P���b�Z�[�W��荞�ݏ���
 |
 |	int	getmsg(fp, ptr)
 |
 |		FILE	*fp;		�ǂݍ��݃t�@�C���|�C���^
 |		MSG	*ptr;		���b�Z�[�W���
 |
 =======================================================================*/
static	int	getmsg(FILE *fp, MSG *ptr)
{
	static	char	msgbuf[2048];
	static	KAHENBU	kahenbu[256];
	int	n, kn, kflag, kahenbu_no;
	char	*rp, *wp;

	if (readmsg(fp, &ptr->msg_no, &ptr->class, msgbuf) == EOF)
		return(EOF);

	n = 0;
	kn = 1;
	kflag = 0;
	kahenbu_no = 0;
	for (rp = msgbuf, wp = msgbuf; *rp != '\0'; rp++) {
		switch (*rp) {
		case '!':
			kahenbu_no = atoi(rp+1);
			break;
		case '%':
			if (kflag) {
				kahenbu[n].kahenbu_no = 0;
				kahenbu[n++].type = 1;
				kflag = 0;
				*wp++ = 's';
			} else {
				if (kahenbu_no != 0)
					kahenbu[n].kahenbu_no = kahenbu_no;
				else
					kahenbu[n].kahenbu_no = kn;
				kahenbu_no = 0;
				kflag = 1;
				*wp++ = *rp;
			}
			break;
		case 'm':
			if (kflag) {
				kahenbu[n++].type = 2;
				kflag = 0;
				*wp++ = 's';
				kn++;
			} else
				*wp++ = *rp;
			break;
		case 'd':
		case 'x':
		case 'u':
			if (kflag) {
				kahenbu[n++].type = 0;
				kflag = 0;
				kn++;
			}
			*wp++ = *rp;
			break;
		case 's':
			if (kflag) {
				kahenbu[n++].type = 1;
				kflag = 0;
				kn++;
			}
			*wp++ = *rp;
			break;
		case '0':
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
		case '6':
		case '7':
		case '8':
		case '9':
			if (kahenbu_no == 0)
				*wp++ = *rp;
			break;
		default:
			*wp++ = *rp;
			break;
		}
	}
	*wp = '\0';

	ptr->n_kahenbu = n;

	ptr->kahenbu = talloc(sizeof(KAHENBU) * n);
	memcpy(ptr->kahenbu, kahenbu, sizeof(KAHENBU) * n);

	ptr->msg = talloc(strlen(msgbuf) + 1);
	strcpy(ptr->msg, msgbuf);

	return(0);
}

/*=======================================================================
 |
 |		�P���b�Z�[�W�ǂݍ��ݏ���
 |
 |	int	readmsg(fp, msg_no, class, buf)
 |
 |		FILE	*fp;		�ǂݍ��݃t�@�C���|�C���^
 |		int	*msg_no;	���b�Z�[�W�ԍ�
 |		int	*class;		�������b�Z�[�W�N���X�i�`�����a�j
 |		char	*msgbuf;	���b�Z�[�W�ǂݍ��݃o�b�t�@
 |
 =======================================================================*/
static	int	readmsg(FILE *fp, int *msg_no, int *class, char *msgbuf)
{
	static	int	first_flag = 1;
	static	int	end_flag = 0;
	static	char	buf[80];
	int	c;
	char	*cp;

	/* �I���t���O�`�F�b�N */
	if (end_flag)
		return(EOF);

	if (first_flag) {
		/* ���b�Z�[�W�ԍ��ǂݍ��� */
		for (;;) {
			if (getline(buf, sizeof(buf), fp) == NULL)
				return(EOF);
			if (strncmp(buf, "@MSG", 4) == 0)
				break;
		}

		first_flag = 0;
	}

	/* ���b�Z�[�W�ԍ��Z�b�g */
	*msg_no = atoi(buf + 4);

	/* �������b�Z�[�W�N���X�Z�b�g */
	if ((cp = strchr(buf, ',')) != NULL) {
		c = *(cp + 1);
		if (c == 'A' || c == 'a')
			*class = (*(cp + 2) == '1') ? MSG_CLASS_A1 : MSG_CLASS_A2;
		else if (c == 'B' || c == 'b')
			*class = MSG_CLASS_B;
		else
			*class = MSG_CLASS_DFLT;
	} else
		*class = MSG_CLASS_DFLT;

	/* ���b�Z�[�W�ǂݍ��� */
	for (;;) {
		if (getline(buf, sizeof(buf), fp) == NULL) {
			end_flag = 1;
			break;
		}

		if (strncmp(buf, "@MSG", 4) == 0) 
			break;

		strcpy(msgbuf, buf);
		msgbuf += strlen(buf);
	}

	/* �Ō�̂k�e����菜�� */
	if (*(msgbuf-1) == '\n')
		msgbuf--;
	*msgbuf = '\0';

	return(0);
}

/*=======================================================================
 |
 |		�P�s�ǂݍ��݁i�R�����g�͏����j
 |
 |	char	*getline(buf, bufsize, fp)
 |
 |		FILE	*fp;		�t�@�C���|�C���^
 |
 =======================================================================*/
static	char	*getline(char *buf, int bufsize, FILE *fp)
{
	int	i, ch;
	char	*p;

	p = buf;
	for (i = 0; i < bufsize-1; i++) {
		ch = fgetchr(fp);
		if (ch == EOF) {
			if (i == 0)
				return(NULL);
			else
				break;
		}
		*p++ = ch;
		if (ch == '\n')
			break;
	}
	*p = '\0';

	return(buf);
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

	mode = 0;
	for (;;) {
		ch = fgetc(fp);
		if (ch == EOF)
			break;

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
			ungetc(ch, fp);
			ch = '/';
			break;
		} else if (mode == 3)
			mode = 2;
	}

	return(ch);
}

/*=======================================================================
 |
 |		���b�Z�[�W�������ݏ���
 |
 |	void	write_file(fp, msgp, n_msg)
 |
 |		FILE	*fp;		�������݃t�@�C���|�C���^
 |		MSG	*msgp;		���b�Z�[�W���
 |		int	n_msg;		���b�Z�[�W��
 |
 =======================================================================*/
static	void	write_file(FILE *fp, MSG *msgp, int n_msg)
{
	int	i, len;
	MSG	*ptr;
	long	offset;

	fwrite(&n_msg, sizeof(int), 1, fp);

	offset = sizeof(int) + (sizeof(int) + sizeof(long)) * n_msg;
	for (i = 0, ptr = msgp; i < n_msg; i++, ptr++) {
		fwrite(&ptr->msg_no, sizeof(int), 1, fp);
		fwrite(&offset, sizeof(long), 1, fp);
		offset += sizeof(int) * 2 + sizeof(KAHENBU) * ptr->n_kahenbu
					+ sizeof(int) + strlen(ptr->msg);
	}

	for (i = 0, ptr = msgp; i < n_msg; i++, ptr++) {
		fwrite(&ptr->class, sizeof(int), 1, fp);
		fwrite(&ptr->n_kahenbu, sizeof(int), 1, fp);
		fwrite(ptr->kahenbu, sizeof(KAHENBU), ptr->n_kahenbu, fp);
		len = strlen(ptr->msg);
		fwrite(&len, sizeof(int), 1, fp);
		fwrite(ptr->msg, 1, len, fp);
	}
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