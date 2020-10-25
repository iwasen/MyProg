/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: �R�}���h���͔ŃT���v���v���O����	*
 *		�t�@�C����	: csample.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include <conio.h>
#include "isam.h"

#define	ESC	0x1b

static	void	err_proc(int);

static	void	t_search(DB *);
static	void	t_store(DB *);
static	void	t_update(DB *);
static	void	t_delete(DB *);
static	void	t_list(DB *);
static	void	t_ichiran(DB *);
static	void	t_info(DB *);
static	void	t_info_dbf(DBF *);
static	void	t_info_idx(IDX *);
static	void	t_locate(DB *);
static	void	t_etc(DB *);
static	void	t_sort(DB *);
static	void	t_pack(DB *);
static	void	t_reindex(DB *);
static	void	t_chgidx(DB *);
static	void	t_count(DB *);
static	void	t_recall(DB *);
static	void	del_mode(void);
static	void	t_flush(DB *);
static	DB	*t_dbf_create(char *);
static	void	t_idx_create(DB *);
static	void	display(DB *);
static	void	display2(DB *);
static	int	jikkou(void);
static	void	err_proc(int);
static	void	title(void);
static	void	beep(void);
static	void	stop_handler(void);

static	DB	*sv_dbp;

/*=======================================================================
 |
 |		���C������
 |
 |	int	main(argc, argv)
 |
 |		int	argc;		�p�����[�^��
 |		char	*argv[];	�R�}���h���C���p�����[�^
 |
 =======================================================================*/
main(int argc, char *argv[])
{
	char	dbf_file_name[32];
	char	idx_file_name[32];
	DB	*dbp;
	int	loop_sw, ch;

	/* �p�����[�^���`�F�b�N */
	if (argc < 2) {
		printf("�g�����F�b�r�`�l�o�k�d�@�f�[�^�t�@�C�����@�m�C���f�b�N�X�t�@�C�����@�D�D�D�n\n");
		exit(1);
	}

	signal(SIGINT, stop_handler);	/* �r�s�n�o�L�[�ŏ������~ */

	title();		/* �^�C�g���\�� */

	db_err_handler(err_proc);	/* �G���[�������[�`���ݒ� */

	/* �������t�@�C���I�[�v�� */
	argc--;
	strcpy(dbf_file_name, *++argv);
	if (strchr(dbf_file_name, '.') == NULL)
		strcat(dbf_file_name, ".DBF");
	if ((dbp = db_open(dbf_file_name)) == 0) {
		for (;;) {
			printf("\n�f�[�^�t�@�C��������܂���B�쐬���܂��� (Y/N) ==>");
			ch = getch();
			printf("%c\n", ch);
			if (ch == 'Y' || ch == 'y')
				break;
			if (ch == 'N' || ch == 'n')
				exit(0);
		}
		if ((dbp = t_dbf_create(dbf_file_name)) == 0) {
			exit(1);
		}
	}
	sv_dbp = dbp;

	/* �C���f�b�N�X�t�@�C���I�[�v�� */
	while (--argc) {
		strcpy(idx_file_name, *++argv);
		if (strchr(idx_file_name, '.') == NULL)
			strcat(idx_file_name, ".NDX");
		if (db_index(dbp, idx_file_name) == 0) {
			printf("file open error (%s)\n", idx_file_name);
			db_close(dbp);
			exit(1);
		}
	}

	display2(dbp);	/* �J�����g���R�[�h�\�� */

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== ���j���[ =====\n");
		printf("1.����  2.�ǉ�  3.�X�V  4.�폜  5.�\��  6.�ʒu  7.���̑�  8.�I�� ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* ���� */
			t_search(dbp);
			break;
		case '2':		/* �ǉ� */
			t_store(dbp);
			break;
		case '3':		/* �X�V */
			t_update(dbp);
			break;
		case '4':		/* �폜 */
			t_delete(dbp);
			break;
		case '5':		/* �\�� */
			t_list(dbp);
			break;
		case '6':		/* �ʒu */
			t_locate(dbp);
			break;
		case '7':		/* ���̑� */
			t_etc(dbp);
			break;
		case '8':		/* �I�� */
			loop_sw = 0;
			break;
		default:
			beep();
		}
	}

	db_close(dbp);

	return(0);
}

/*=======================================================================
 |
 |		��������
 |
 |	void	t_search(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_search(DB *dbp)
{
	IDX	*ip;
	char	buf[40];

	printf("\n===== ���� =====\n");

	ip = dbp->ip[dbp->master];

	if (ip == NULL) {
		printf("�C���f�b�N�X�t�@�C��������܂���\n");
		return;
	}

	/* ������������� */
	printf("%s ==>", ip->ihp->index);
	scanf("%s", buf);

	db_search(dbp, buf, strlen(buf));	/* ���� */

	display2(dbp);		/* �J�����g���R�[�h�\�� */
}

/*=======================================================================
 |
 |		�ǉ�����
 |
 |	void	t_store(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_store(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	ibuf[256], *ibp, buf[100], buf2[100], fmt[20];

	printf("\n===== �ǉ� =====\n");

	dp = dbp->dp;

	/* ���R�[�h�o�b�t�@�N���A */
	memset(ibuf, ' ', dp->dhp->l_rec - 1);

	/* �t�B�[���h������ */
	ibp = ibuf;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		printf("%-10s(%d) ==>", flp->name, flp->l_field);
		scanf("%s", buf);

		if (flp->type != 'N')
			memcpy(ibp, buf, strlen(buf));
		else {
			sprintf(fmt, "%%%d.%dlf", flp->l_field, flp->l_dec);
			sprintf(buf2, fmt, atof(buf));
			memcpy(ibp, buf2, flp->l_field);
		}

		ibp += flp->l_field;
	}

	if (i == dp->n_field) {
		if (jikkou()) {
			db_store(dbp, ibuf);	/* ���R�[�h�ǉ� */
			display2(dbp);		/* �J�����g���R�[�h�\�� */
		}
	}
}

/*=======================================================================
 |
 |		�X�V����
 |
 |	void	t_update(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_update(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	ibuf[256], *ibp, buf[100], buf2[100], fmt[20];

	printf("\n===== �X�V =====\n");

	dp = dbp->dp;

	/* ���R�[�h�o�b�t�@�N���A */
	memset(ibuf, ' ', dp->dhp->l_rec - 1);

	/* �t�B�[���h������ */
	ibp = ibuf;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		printf("%-10s(%d) ==>", flp->name, flp->l_field);
		scanf("%s", buf);

		if (flp->type != 'N')
			memcpy(ibp, buf, strlen(buf));
		else {
			sprintf(fmt, "%%%d.%dlf", flp->l_field, flp->l_dec);
			sprintf(buf2, fmt, atof(buf));
			memcpy(ibp, buf2, flp->l_field);
		}
		
		ibp += flp->l_field;
	}

	if (i == dp->n_field) {
		if (jikkou()) {
			db_update(dbp, ibuf);	/* ���R�[�h�X�V */
			display2(dbp);		/* �J�����g���R�[�h�\�� */
		}
	}
}

/*=======================================================================
 |
 |		�폜����
 |
 |	void	t_delete(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_delete(DB *dbp)
{
	printf("\n===== �폜 =====\n");

	if (jikkou()) {
		db_delete(dbp);		/* ���R�[�h�폜 */
		display2(dbp);		/* �J�����g���R�[�h�\�� */
	}
}

/*=======================================================================
 |
 |		�\������
 |
 |	void	t_list(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_list(DB *dbp)
{
	int	ch;
	int	loop_sw;

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== �\�� =====\n");
		printf("1.�ꗗ  2.��� ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* �ꗗ */
			t_ichiran(dbp);
			break;
		case '2':		/* ��� */
			t_info(dbp);
			break;
		case ESC:		/* �߂� */
			loop_sw = 0;
			break;
		default:
			beep();
		}
	}
}

/*=======================================================================
 |
 |		�ꗗ�\������
 |
 |	void	t_ichiran(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_ichiran(DB *dbp)
{
	int	ch, loop_sw;

	printf("\n===== �ꗗ =====\n");

	loop_sw = 1;
	while (loop_sw) {
		printf("1.�擪���R�[�h����\��  2.�J�����g���R�[�h����\�� ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':	/* �擪���R�[�h����\�� */
			db_top(dbp);
			loop_sw = 0;
			break;
		case '2':	/* �J�����g���R�[�h����\�� */
			loop_sw = 0;
			break;
		case ESC:	/* �߂� */
			return;
		default:
			beep();
		}
	}

	for (;;) {
		display(dbp);		/* �P���R�[�h�\�� */

		if (db_eof(dbp))
			break;

		db_skip(dbp, 1L);	/* �����R�[�h */

		if (kbhit()) {
			if (ch = getch()) {
				if (ch == ESC)
					break;
				else if (ch == ' ') {
					ch = getch();
					if (ch == ESC)
						break;
				}
			}
		}
	}
}

/*=======================================================================
 |
 |		���\������
 |
 |	void	t_info(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_info(DB *dbp)
{
	DBF	*dp;
	IDX	*ip;
	int	ch, n;

	printf("\n===== ��� =====\n");

	dp = dbp->dp;

	/* �t�@�C�����\�� */
	n = 1;
	printf("0.%s\n", dp->file_name);
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		printf("%d.%s\n", n, ip->file_name);
	}

	if (n == 1)
		t_info_dbf(dp);		/* �������t�@�C�����\�� */
	else {
		for (;;) {
			printf("==>");
			ch = getch();
			printf("%c\n", ch);
			if (ch == ESC)
				break;
			else if (ch == '0') {
				t_info_dbf(dp);	/* �c�a�e�t�@�C�� */
				break;
			} else if (ch >= '1' && ch < n+'0') {
				t_info_idx(dbp->ip[ch-'0']);/* �m�c�w�t�@�C�� */
				break;
			} else
				beep();
		}
	}
}

/*=======================================================================
 |
 |		�������t�@�C�����\������
 |
 |	void	t_info_dbf(dp)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |
 =======================================================================*/
static	void	t_info_dbf(DBF *dp)
{
	DBF_H	*dhp;
	FIELD	*flp;
	register int	i;

	dhp = dp->dhp;
	printf("*** %s *** \n", dp->file_name);
	printf("�ŏI�X�V�� : %2d/%02d/%02d\n", dhp->date[0], dhp->date[1], dhp->date[2]);
	printf("���R�[�h�� : %ld\n", dhp->n_rec);
	printf("���R�[�h�� : %d\n\n", dhp->l_rec);
	printf("�t�B�[���h���@�`���@�����@�����ʌ���\n");
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		printf(" %-11s   %c     %2d     %2d\n",
				flp->name,
				flp->type,
				flp->l_field,
				flp->l_dec);
	}
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C�����\������
 |
 |	void	t_info_idx(ip)
 |
 |		IDX	*ip;		�h�c�w�|�C���^
 |
 =======================================================================*/
static	void	t_info_idx(IDX *ip)
{
	IDX_H	*ihp;

	ihp = ip->ihp;
	printf("*** %s *** \n", ip->file_name);
	printf("���[�g�m�� : %ld\n", ihp->root);
	printf("�u���b�N�� : %ld\n", ihp->n_block);
	printf("�L�[��     : %d\n", ihp->l_key);
	printf("����       : %d\n", ihp->order);
	printf("�f�[�^��� : %d\n", ihp->type);
	printf("���R�[�h�� : %d\n", ihp->l_rec);
	printf("�\����     : %s\n", ihp->index);
}

/*=======================================================================
 |
 |		�ʒu����
 |
 |	void	t_locate(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_locate(DB *dbp)
{
	int	loop_sw, ch;
	char	buf[6];

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== �ʒu =====\n");
		printf("1.�擪  2.�Ō�  3.�w��ں���  4.�O��ړ� ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* �擪 */
			db_top(dbp);
			display2(dbp);
			break;
		case '2':		/* �Ō� */
			db_bottom(dbp);
			display2(dbp);
			break;
		case '3':		/* �w��ں��� */
			printf("���R�[�h�ԍ� ==>");
			scanf("%s", buf);
			db_set(dbp, atol(buf));
			display2(dbp);
			break;
		case '4':		/* �O��ړ� */
			printf("�ړ����R�[�h�� ==>");
			scanf("%s", buf);
			db_skip(dbp, atol(buf));
			display2(dbp);
			break;
		case ESC:		/* �߂� */
			loop_sw = 0;
			break;
		default:
			break;
		}
	}
}

/*=======================================================================
 |
 |		���̑�����
 |
 |	void	t_etc(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_etc(DB *dbp)
{
	int	ch;
	int	loop_sw;

	loop_sw = 1;
	while (loop_sw) {
		printf("\n===== ���̑� =====\n");
		printf("1.�\�[�g  2.�p�b�N      3.���ޯ���č\�z  4.���ޯ���ؑ�  5.�J�E���g\n");
		printf("6.����    7.�폜���[�h  8.�t���b�V��     9.���ޯ���쐬  ==>");
		ch = getch();
		printf("%c\n", ch);
		switch (ch) {
		case '1':		/* �\�[�g */
			t_sort(dbp);
			break;
		case '2':		/* �p�b�N */
			t_pack(dbp);
			break;
		case '3':		/* �C���f�b�N�X�č\�z */
			t_reindex(dbp);
			break;
		case '4':		/* �C���f�b�N�X�ؑ� */
			t_chgidx(dbp);
			break;
		case '5':		/* �J�E���g */
			t_count(dbp);
			break;
		case '6':		/* ���� */
			t_recall(dbp);
			break;
		case '7':		/* �폜���[�h */
			del_mode();
			break;
		case '8':		/* �t���b�V�� */
			t_flush(dbp);
			break;
		case '9':		/* ���ޯ���쐬 */
			t_idx_create(dbp);
			break;
		case ESC:		/* �߂� */
			loop_sw = 0;
			break;
		default:
			beep();
		}
	}
}

/*=======================================================================
 |
 |		�\�[�g����
 |
 |	void	t_sort(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_sort(DB *dbp)
{
	DBF	*dp;
	char	file_name[10], key[100];

	printf("\n===== �\�[�g =====\n");

	dp = dbp->dp;

	/* �o�̓t�@�C�������� */
	printf("�o�̓t�@�C���� ==>");
	scanf("%s", file_name);

	/* �L�[���� */
	printf("�L�[ ==>");
	scanf("%s", key);

	if (jikkou()) {
		printf("���s��\n");
		dbf_sort(dp, file_name, key);	/* �\�[�g */
	}
}

/*=======================================================================
 |
 |		�p�b�N����
 |
 |	void	t_pack(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_pack(DB *dbp)
{
	printf("\n===== �p�b�N =====\n");

	if (jikkou()) {
		printf("���s��\n");
		db_pack(dbp);		/* �p�b�N���� */
		display2(dbp);		/* �J�����g���R�[�h�\�� */
	}
}

/*=======================================================================
 |
 |		�C���f�b�N�X�č\�z����
 |
 |	void	t_reindex(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_reindex(DB *dbp)
{
	printf("\n===== ���ޯ���č\�z =====\n");

	if (jikkou()) {
		printf("���s��\n");
		db_reindex(dbp);	/* �C���f�b�N�X�č\�z */
		display2(dbp);		/* �J�����g���R�[�h�\�� */
	}
}

/*=======================================================================
 |
 |		�C���f�b�N�X�؂�ւ�����
 |
 |	void	t_chgidx(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_chgidx(DB *dbp)
{
	IDX	*ip;
	int	ch;
	int	n;

	if (dbp->n_idx == 0)
		return;

	/* �C���f�b�N�X�t�@�C�����\�� */
	printf("0.�Ȃ�\n");
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		printf("%d.%s\n", n, ip->file_name);
	}

	for (;;) {
		printf("==>");
		ch = getch();
		printf("%c\n", ch);
		if (ch == ESC)
			break;
		else if (ch >= '0' && ch < n+'0') {
			db_chgidx(dbp, ch - '0'); /* �C���f�b�N�X�؂�ւ� */
			display2(dbp);		/* �J�����g���R�[�h�\�� */
			break;
		} else
			beep();
	}
}

/*=======================================================================
 |
 |		�J�E���g����
 |
 |	void	t_count(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_count(DB *dbp)
{
	IDX	*ip;
	char	buf[40];
	long	n;

	printf("\n===== �J�E���g =====\n");

	ip = dbp->ip[dbp->master];

	if (ip == NULL)
		return;

	/* �L�[�f�[�^���� */
	printf("%s ==>", ip->ihp->index);
	scanf("%s", buf);

	n = db_count(dbp, buf, strlen(buf));	/* �J�E���g���� */
	printf("%5ld ��\n", n);			/* �����\�� */

	display2(dbp);				/* �J�����g���R�[�h�\�� */
}

/*=======================================================================
 |
 |		��������
 |
 |	void	t_recall(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_recall(DB *dbp)
{
	printf("\n===== ���� =====\n");

	if (jikkou()) {
		db_recall(dbp);		/* �������� */
		display2(dbp);		/* �J�����g���R�[�h�\�� */
	}
}

/*=======================================================================
 |
 |		�폜���[�h����
 |
 |	void	del_mode(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	del_mode()
{
	int	ch;

	printf("\n===== �폜���[�h =====\n");

	printf("1.�폜���R�[�h�L��  2.�폜���R�[�h���� ==>");
	ch = getch();
	printf("%c\n", ch);
	if (ch == '1')
		set_deleted(0);		/* �폜���R�[�h�L�� */
	else if (ch == '2')
		set_deleted(1);		/* �폜���R�[�h���� */
}

/*=======================================================================
 |
 |		�t���b�V������
 |
 |	void	t_flush(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_flush(DB *dbp)
{
	printf("\n===== �t���b�V�� =====\n");

	if (jikkou()) {
		db_flush(dbp);		/* �t���b�V������ */
		display2(dbp);		/* �J�����g���R�[�h�\�� */
	}
}

/*=======================================================================
 |
 |		�������t�@�C���쐬����
 |
 |	DB	*t_dbf_create(file_name)
 |
 |		char	*file_name;	�������t�@�C����
 |
 =======================================================================*/
static	DB	*t_dbf_create(char *file_name)
{
	DB	*dbp;
	char	buf[10];
	int	i, ch, loop_sw;
	DBF_I	dbfi[20];

	printf("\n===== �ް��ް��쐬 =====\n");

	/* �f�[�^�t�@�C�������� */
	printf("�ް� ̧�ٖ� %s\n", file_name);

	for (i = 0; ; i++) {
		/* �t�B�[���h������ */
		printf("�t�B�[���h���i�I��:CTRL-Z�j ==>");
		dbfi[i].name[0] = '\0';
		scanf("%s", dbfi[i].name);
		if (strlen(dbfi[i].name) == 0)
			break;

		/* �t�B�[���h�`������ */
		printf("�`�� (C,N,D,L) ==>");
		loop_sw = 1;
		while (loop_sw) {
			ch = getch();
			switch (ch) {
			case 'C':
			case 'c':
				dbfi[i].type = 'C';
				printf("C\n");
				loop_sw = 0;
				break;
			case 'N':
			case 'n':
				dbfi[i].type = 'N';
				printf("N\n");
				loop_sw = 0;
				break;
			case 'D':
			case 'd':
				dbfi[i].type = 'D';
				printf("D\n");
				loop_sw = 0;
				break;
			case 'L':
			case 'l':
				dbfi[i].type = 'L';
				printf("L\n");
				loop_sw = 0;
				break;
			default:
				beep();
			}
		}

		/* �t�B�[���h������ */
		printf("���� ==>");
		scanf("%s", buf);
		dbfi[i].l_field = atoi(buf);

		/* �������������� */
		if (dbfi[i].type == 'N') {
			printf("���� ==>");
			scanf("%s", buf);
			dbfi[i].l_dec = atoi(buf);
		} else
			dbfi[i].l_dec = 0;
	}

	if (i != 0)
		dbp = db_create(file_name, dbfi, i);	/* �f�[�^�x�[�X�쐬 */
	else
		dbp = NULL;

	return(dbp);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���쐬����
 |
 |	void	t_idx_create(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	t_idx_create(DB *dbp)
{
	char	file_name[14], key[100];
	int	ch, uniq;

	printf("\n===== ���ޯ���쐬 =====\n");

	/* �C���f�b�N�X�t�@�C�������� */
	printf("���ޯ�� ̧�ٖ� ==>");
	scanf("%s", file_name);
	strcat(file_name, ".NDX");

	/* �L�[���� */
	printf("�L�[ ==>");
	scanf("%s", key);

	/* �L�[�̏d������ */
	printf("�L�[�̏d�� �i�O�F�L��@�P�F�����j ==>");
	for (;;) {
		ch = getch();
		printf("%c\n", ch);
		if (ch == '0') {
			uniq = 0;
			break;
		} else if (ch == '1') {
			uniq = 1;
			break;
		} else
			beep();
	}
	if (jikkou()) {
		/* �C���f�b�N�X�t�@�C���쐬 */
		db_idx_create(dbp, file_name, key, uniq);

		display2(dbp);	/* �J�����g���R�[�h�\�� */
	}
}

/*=======================================================================
 |
 |		���R�[�h�\���i�ꗗ�`���j
 |
 |	void	display(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	display(DB *dbp)
{
	char	fmt[10];
	DBF	*dp;
	FIELD	*flp;
	int	i;

	dp = dbp->dp;
	flp = dp->flp;

	if (db_bof(dbp)) {
		printf("[BOF]\n");
	} else if (db_eof(dbp))  {
		printf("[EOF]\n");
	} else {
		dbf_read(dp, dbp->rno);
		printf("%c", *dp->rbp);
		for (i = 0; i < dp->n_field; i++) {
			sprintf(fmt, "%%.%ds", flp->l_field);
			printf(fmt, flp->fad);
			printf(" ");
			flp++;
		}
		printf("\n");
	}
}

/*=======================================================================
 |
 |		���R�[�h�\��
 |
 |	void	display2(dbp)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |
 =======================================================================*/
static	void	display2(DB *dbp)
{
	DBF	*dp;
	register int	i;
	FIELD	*flp;
	char	fmt[20];

	printf("##### �J�����g���R�[�h #####\n");

	dp = dbp->dp;

	if (db_bof(dbp)) {
		printf("[BOF]\n");
	} else if (db_eof(dbp)) {
		printf("[EOF]\n");
	} else {
		dbf_read(dp, dbp->rno);
		if (*dp->rbp == '*')
			printf("*�폜��*\n");
		for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
			sprintf(fmt, "%%-10s [%%.%ds]\n", flp->l_field);
			printf(fmt, flp->name, flp->fad);
		}
	}
}

/*=======================================================================
 |
 |		���s�m�F
 |
 |	int	jikkou()
 |
 =======================================================================*/
static	int	jikkou()
{
	int	ch;

	printf("���s���܂��� (Y/N) ");
	for (;;) {
		ch = getch();
		if (ch == 'Y' || ch == 'y') {
			printf("%c\n", ch);
			return(1);
		} else if (ch == 'N' || ch == 'n') {
			printf("%c\n", ch);
			return(0);
		}
	}
}

/*=======================================================================
 |
 |		�G���[�\������
 |
 |	void	err_proc(err_code)
 |
 |		int	err_code;	�G���[�R�[�h
 |
 =======================================================================*/
static	void	err_proc(int err_code)
{
	char	*msg;

	printf("�G���[���b�Z�[�W �F ");
	switch (err_code) {
	case IE_MEMORY:
		msg = "�������s��";
		break;
	case IE_FOPEN:
		msg = "�t�@�C���I�[�v���G���[ ";
		break;
	case IE_NOFILE:
		msg = "�I�[�v������t�@�C�����Ȃ�";
		break;
	case IE_IDXOVER:
		msg = "�C���f�b�N�X�t�@�C������������";
		break;
	case IE_TMPFILE:
		msg = "�e���|�����t�@�C�������Ȃ�";
		break;
	case IE_NOKEY:
		msg = "�w�肳�ꂽ�L�[�����݂��Ȃ�";
		break;
	case IE_DBLKEY:
		msg = "�L�[���d�����Ă���";
		break;
	case IE_RECNO:
		msg = "���R�[�h�ԍ����s��";
		break;
	case IE_EOF:
		msg = "�a�n�e�܂��͂d�n�e";
		break;
	case IE_STRFILE:
		msg = "�\���t�@�C�����������Ȃ�";
		break;
	case IE_READ:
		msg = "�t�@�C�����������G���[";
		break;
	case IE_WRITE:
		msg = "�t�@�C�������������G���[";
		break;
	case IE_IDXKEY:
		msg = "�C���f�b�N�X�̃L�[�\�������s��";
		break;
	default:
		msg = "??????????";
		break;
	}
	printf("%s\n", msg);
}

/*=======================================================================
 |
 |		�^�C�g���\������
 |
 |	void	title()
 |
 =======================================================================*/
static	void	title()
{

	printf("*** ISAM LIBRARY SAMPLE PROGRAM ***\n");
}

/*=======================================================================
 |
 |		�a�d�d�o����
 |
 |	void	beep()
 |
 =======================================================================*/
static	void	beep()
{
	putch(0x07);
}

/*=======================================================================
 |
 |		�r�s�n�o�L�[����
 |
 |	void	stop_handler()
 |
 =======================================================================*/
static	void	stop_handler(void)
{
	db_close(sv_dbp);
	printf("�����I�����܂���.\n");
	exit(1);
}