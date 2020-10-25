/************************************************************************
 *									*
 *		�h�r�`�l���C�u����					*
 *									*
 *		����		: �E�B���h�E�ŃT���v���v���O����	*
 *		�t�@�C����	: wsample.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <signal.h>
#include "isam.h"
#include "gwlib.h"

static	WINDOW	*dwp;
static	void	err_proc(int);

static	void	t_search(DB *);
static	void	inp_field(WINDOW *, int);
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
static	int	t_idx_create(DB *, char *);
static	int	sel_key(DBF *, char *);
static	void	display(WINDOW *, DB *, int);
static	void	display2(DB *);
static	void	w_cls(WINDOW *);
static	int	jikkou(WINDOW *);
static	void	err_proc(int);
static	void	title(void);
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
	DBF	*dp;
	FIELD	*flp;
	int	loop_sw;
	int	max_length, i;
	int	csel;
	WINDOW	*wp;
	static	int	sel;
	static	char	*item[] = {
		"��  ��",
		"��  ��",
		"�X  �V",
		"��  ��",
		"�\  ��",
		"��  �u",
		"���̑�",
		"�I  ��"
	};

	/* �p�����[�^���`�F�b�N */
	if (argc < 2) {
		printf("�g�����F�v�r�`�l�o�k�d�@�f�[�^�t�@�C�����@�m�C���f�b�N�X�t�@�C�����@�D�D�D�n\n");
		exit(1);
	}

	w_initscr(8, 18, 1, "ANK.FNT");		/* ������ */

	signal(SIGINT, stop_handler);	/* �r�s�n�o�L�[�ŏ������~ */

	title();		/* �^�C�g���\�� */

	/* �������t�@�C���I�[�v�� */
	argc--;
	strcpy(dbf_file_name, *++argv);
	if (strchr(dbf_file_name, '.') == NULL)
		strcat(dbf_file_name, ".DBF");
	if ((dbp = db_open(dbf_file_name)) == NULL) {
		if ((dbp = t_dbf_create(dbf_file_name)) == NULL) {
			w_endscr();
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
			if (t_idx_create(dbp, idx_file_name) == 0) {
				db_close(dbp);
				w_endscr();
				exit(1);
			}
		}
	}

	db_err_handler(err_proc);	/* �G���[�������[�`���ݒ� */

	/* ���j���[�E�B���h�E�I�[�v�� */
	wp = w_open(100, 70, 11, 10, 8, 18);
	w_stdwin(wp, "MENU", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �J�����g���R�[�h�E�B���h�E�I�[�v�� */
	dp = dbp->dp;
	max_length = 7;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (flp->l_field > max_length)
			max_length = flp->l_field;
	}
	dwp = w_open(200, 100, max_length+16, dp->n_field + 2, 8, 18);
	w_stdwin(dwp, "�J�����g���R�[�h", C_BLACK, C_WHITE, C_BLACK);
	dwp->flag = 0x01;

	display2(dbp);			/* �J�����g���R�[�h�\�� */

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 8, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* ���� */
			t_search(dbp);
			break;
		case 1:		/* �ǉ� */
			t_store(dbp);
			break;
		case 2:		/* �X�V */
			t_update(dbp);
			break;
		case 3:		/* �폜 */
			t_delete(dbp);
			break;
		case 4:		/* �\�� */
			t_list(dbp);
			break;
		case 5:		/* �ʒu */
			t_locate(dbp);
			break;
		case 6:		/* ���̑� */
			t_etc(dbp);
			break;
		case 7:		/* �I�� */
			loop_sw = 0;
			break;
		}

		sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
	}

	db_close(dbp);

	w_endscr();		/* �I������ */

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
	IDX_H	*ihp;
	WINDOW	*wp;
	int	i, kl, cn, ch, loop_sw;
	char	buf[40];

	ip = dbp->ip[dbp->master];

	if (ip == NULL)
		return;

	ihp = ip->ihp;

	kl = strlen(ihp->index);
	cn = kl + ihp->l_key + 5;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, cn, 3, 8, 18);
	w_stdwin(wp, "����", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �\�����\�� */
	w_xprintf(wp, 1, 1, "%s", ihp->index);
	inp_field(wp, ihp->l_key);

	memset(buf, ' ', ihp->l_key);
	loop_sw = 1;
	while (loop_sw) {
		w_popup(wp);

		/* ������������� */
		if (ihp->type == 0)
			ch = w_inputs(wp, buf, kl+2, 1, ihp->l_key, 0x81);
		else
			ch = w_inputn(wp, buf, kl+2, 1, ihp->l_key, 0, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			for (i = ihp->l_key - 1; i >= 0; i--) {
				if (buf[i] != ' ')
					break;
			}

			if (i >= 0) {
				db_search(dbp, buf, i + 1);	/* ���� */
				display2(dbp);
			} else
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	w_close(wp);
}

static	void	inp_field(WINDOW *wp, int len)
{
	w_putch(wp, '[');
	while(len--)
		w_putch(wp, ' ');
	w_putch(wp, ']');
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
	WINDOW	*wp;
	register int	i;
	DBF_H	*dhp;
	FIELD	*flp;
	int	max_length, loop_sw, ch;
	char	ibuf[256], *ibp;

	dp = dbp->dp;

	dhp = dp->dhp;

	/* �E�B���h�E�I�[�v�� */
	max_length = 0;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (flp->l_field > max_length)
			max_length = flp->l_field;
	}
	wp = w_open(m_gx, m_gy, max_length+16, dp->n_field + 3, 8, 18);
	w_stdwin(wp, "�ǉ�", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/*�t�B�[���h���\�� */
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		w_xprintf(wp, 1, i+1, "%-10s ", flp->name);
		inp_field(wp, flp->l_field);
	}

	/* ���R�[�h�o�b�t�@�N���A */
	memset(ibuf, ' ', dhp->l_rec - 1);

	/* �t�B�[���h�f�[�^���� */
	ibp = ibuf;
	loop_sw = 1;
	for (i = 0, flp = dp->flp; loop_sw && i < dp->n_field; ) {
		if (flp->type == 'C')
			ch = w_inputs(wp, ibp, 13, i+1, flp->l_field, 0x01);
		else
			ch = w_inputn(wp, ibp, 13, i+1, flp->l_field, flp->l_dec, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			ibp += flp->l_field;
			i++;
			flp++;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	if (loop_sw) {
		if (jikkou(wp)) {
			db_store(dbp, ibuf);	/* �ǉ����� */
			display2(dbp);
		}
	}

	w_close(wp);
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
	WINDOW	*wp;
	register int	i;
	DBF_H	*dhp;
	FIELD	*flp;
	int	max_length, loop_sw, ch;
	char	ibuf[256], *ibp, fmt[20];

	dp = dbp->dp;

	dhp = dp->dhp;

	/* �E�B���h�E�I�[�v�� */
	max_length = 0;
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		if (flp->l_field > max_length)
			max_length = flp->l_field;
	}
	wp = w_open(m_gx, m_gy, max_length+16, dp->n_field + 3, 8, 18);
	w_stdwin(wp, "�X�V", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �t�B�[���h���\�� */
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		sprintf(fmt, "%%-10s [%%.%ds]", flp->l_field);
		w_xprintf(wp, 1, i+1, fmt, flp->name, flp->fad);
	}

	memcpy(ibuf, dp->rbp+1, dhp->l_rec - 1);

	/* �t�B�[���h������ */
	ibp = ibuf;
	loop_sw = 1;
	for (i = 0, flp = dp->flp; loop_sw && i < dp->n_field; ) {
		if (flp->type == 'C')
			ch = w_inputs(wp, ibp, 13, i+1, flp->l_field, 0x01);
		else
			ch = w_inputn(wp, ibp, 13, i+1, flp->l_field, flp->l_dec, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			ibp += flp->l_field;
			i++;
			flp++;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	if (loop_sw) {
		if (jikkou(wp)) {
			db_update(dbp, ibuf);	/* �X�V���� */
			display2(dbp);
		}
	}

	w_close(wp);
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
	WINDOW	*wp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 11, 3, 8, 18);
	w_stdwin(wp, "�폜", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		db_delete(dbp);		/* �폜���� */
		display2(dbp);
	}

	w_close(wp);
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
	static	int	sel;
	static	char	*item[] = {
		"��  ��",
		"��  ��"
	};
	int	csel;
	WINDOW	*wp;
	int	loop_sw;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 11, 4, 8, 18);
	w_stdwin(wp, "�\��", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 2, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* �ꗗ */
			t_ichiran(dbp);
			break;
		case 1:		/* ��� */
			t_info(dbp);
			break;
		default:
			loop_sw = 0;
		}

		if (csel != -1)
			sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
		else
			loop_sw = 0;
	}

	w_close(wp);
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
	static	char	*item[] = {
		"�擪���R�[�h����\��",
		"���ă��R�[�h����\��"
	};
	DBF	*dp;
	WINDOW	*wp;
	int	cn, ln, ch, csel;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 25, 4, 8, 18);
	w_stdwin(wp, "�ꗗ", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	csel = w_select(wp, item, 1, 2, 0, C_BLACK, C_BLUE);
	w_close(wp);
	if (csel == 0)
		db_top(dbp);
	else if (csel == -1)
		return;

	dp = dbp->dp;

	cn = max(dp->dhp->l_rec+dp->n_field, 5) + 3;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, cn, 15, 8, 18);
	w_stdwin(wp, "�ꗗ", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	ln = 0;
	for (;;) {
		ln++;
		if (ln <= 13)
			display(wp, dbp, ln);	/* �P���R�[�h�\�� */
		else {
			w_scroll_up(wp);	/* ���[���A�b�v */
			display(wp, dbp, 13);	/* �P���R�[�h�\�� */
		}

		if (db_eof(dbp)) {
			waitkey(wp);
			break;
		}

		db_skip(dbp, 1L);		/* �����R�[�h */

		if (ch = inkey()) {
			if (ch == KEY_ESC || ch == MOUSE_R)
				break;
			else if (ch == ' ' || ch == MOUSE_L) {
				ch = waitkey(wp);
				w_popup(wp);
				if (ch == KEY_ESC || ch == MOUSE_R)
					break;
			}
		}
	}

	w_close(wp);

	display2(dbp);
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
	static	int	sel;
	char	*item[20];
	int	csel;
	WINDOW	*wp;
	int	loop_sw, max_fnl, cn, n, len;

	dp = dbp->dp;

	/* �t�@�C�������o�� */
	n = 1;
	item[0] = dp->file_name;
	max_fnl = strlen(dp->file_name);
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		if ((len = strlen(ip->file_name)) > max_fnl)
			max_fnl= len;
		item[n] = ip->file_name;
	}

	if (n == 1)
		t_info_dbf(dp);		/* �������t�@�C�����\�� */
	else {
		/* �E�B���h�E�I�[�v�� */
		cn = max(max_fnl, 4) + 4;
		wp = w_open(m_gx, m_gy, cn, n+2, 8, 18);
		w_stdwin(wp, "���", C_BLACK, C_WHITE, C_BLACK);
		wp->flag = 0x01;

		/* �t�@�C���I�� */
		loop_sw = 1;
		while (loop_sw) {
			csel = w_select(wp, item, 1, n, sel, C_BLACK, C_BLUE);
			switch (csel) {
			case 0:		/* �c�a�e�t�@�C�� */
				t_info_dbf(dp);
				break;
			default:	/* �m�c�w�t�@�C�� */
				t_info_idx(dbp->ip[csel]);
				break;
			case -1:
				loop_sw = 0;
				break;
			}

			if (csel != -1)
				sel = csel;

			w_popup(wp);
			if (w_check() == wp)
				sel = -1;
			else
				loop_sw = 0;
		}
		w_close(wp);
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
	WINDOW	*wp;
	register int	i;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 40, dp->n_field+6, 8, 18);
	w_stdwin(wp, dp->file_name, C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �������t�@�C�����\�� */
	dhp = dp->dhp;
	w_xprintf(wp, 1, 1, "�ŏI�X�V�� : %2d/%02d/%02d\n", dhp->date[0], dhp->date[1], dhp->date[2]);
	w_xprintf(wp, 1, 2, "���R�[�h�� : %ld\n", dhp->n_rec);
	w_xprintf(wp, 1, 3, "���R�[�h�� : %d\n\n", dhp->l_rec);
	w_xprintf(wp, 1, 4, "�t�B�[���h���@�`���@�����@�����ʌ���\n");
	for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
		w_xprintf(wp, 1, i+5, " %-11s   %c     %2d     %2d\n",
				flp->name,
				flp->type,
				flp->l_field,
				flp->l_dec);
	}

	waitkey(wp);

	w_close(wp);
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
	WINDOW	*wp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 40, 9, 8, 18);
	w_stdwin(wp, ip->file_name, C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �C���f�b�N�X�t�@�C�����\�� */
	ihp = ip->ihp;
	w_xprintf(wp, 1, 1, "���[�g�m�� : %ld\n", ihp->root);
	w_xprintf(wp, 1, 2, "�u���b�N�� : %ld\n", ihp->n_block);
	w_xprintf(wp, 1, 3, "�L�[��     : %d\n", ihp->l_key);
	w_xprintf(wp, 1, 4, "����       : %d\n", ihp->order);
	w_xprintf(wp, 1, 5, "�f�[�^��� : %d\n", ihp->type);
	w_xprintf(wp, 1, 6, "���R�[�h�� : %d\n", ihp->l_rec);
	w_xprintf(wp, 1, 7, "�\����     : %s\n", ihp->index);

	waitkey(wp);

	w_close(wp);
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
	static	int	sel;
	static	char	*item[] = {
		"��  ��",
		"��  ��",
		"�w��ں���",
		"�O��ړ�"
	};
	int	csel;
	WINDOW	*wp, *wp2;
	int	loop_sw, loop_sw2, ch;
	char	buf[6];
	static	char	rnbuf[6] = "    1";

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 13, 6, 8, 18);
	w_stdwin(wp, "�ʒu", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 4, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* �擪 */
			db_top(dbp);
			display2(dbp);
			break;
		case 1:		/* �Ō� */
			db_bottom(dbp);
			display2(dbp);
			break;
		case 2:		/* �w��ں��� */
			wp2 = w_open(m_gx, m_gy, 23, 3, 8, 18);
			w_stdwin(wp2, "�w��ں���", C_BLACK, C_WHITE, C_BLACK);
			wp2->flag = 0x01;
			w_dspstr(wp2, 1, 1, "���R�[�h�ԍ� ");
			inp_field(wp2, 5);
			memset(buf, ' ', 5);
			loop_sw2 = 1;
			while (loop_sw2) {
				w_popup(wp2);
				ch = w_inputn(wp2, buf, 15, 1, 5, 0, 0x180);
				switch (ch) {
				case MOUSE_R:
				case MOUSE_L:
					if (w_check() != wp2) {
						loop_sw2 = 0;
						break;
					}
				case KEY_CR:
					db_set(dbp, atol(buf));
					display2(dbp);
					break;
				case KEY_ESC:
					loop_sw2 = 0;
					break;
				default:
					beep();
				}
			}
			w_close(wp2);
			break;
		case 3:		/* �O��ړ� */
			wp2 = w_open(m_gx, m_gy, 25, 3, 8, 18);
			w_stdwin(wp2, "�O��ړ�", C_BLACK, C_WHITE, C_BLACK);
			wp2->flag = 0x01;
			w_dspstr(wp2, 1, 1, "�ړ����R�[�h�� ");
			inp_field(wp2, 5);
			w_dspstr(wp2, 17, 1, rnbuf);
			loop_sw2 = 1;
			while (loop_sw2) {
				w_popup(wp2);
				ch = w_inputn(wp2, rnbuf, 17, 1, 5, 0, 0x180);
				switch (ch) {
				case MOUSE_R:
				case MOUSE_L:
					if (w_check() != wp2) {
						loop_sw2 = 0;
						break;
					}
				case KEY_CR:
					db_skip(dbp, atol(rnbuf));
					display2(dbp);
					break;
				case KEY_ESC:
					loop_sw2 = 0;
					break;
				default:
					beep();
				}
			}
			w_close(wp2);
			break;
		default:
			loop_sw = 0;
		}

		if (csel != -1)
			sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
		else
			loop_sw = 0;
	}

	w_close(wp);
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
	static	int	sel;
	static	char	*item[] = {
		"�\�[�g",
		"�p�b�N",
		"���ޯ���č\�z",
		"���ޯ���ؑ�",
		"�J�E���g",
		"����",
		"�폜���[�h",
		"�t���b�V��",
		"���ޯ���쐬"
	};
	int	csel;
	WINDOW	*wp;
	int	loop_sw;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 18, 11, 8, 18);
	w_stdwin(wp, "���̑�", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	loop_sw = 1;
	while (loop_sw) {
		csel = w_select(wp, item, 1, 9, sel, C_BLACK, C_BLUE);
		switch (csel) {
		case 0:		/* �\�[�g */
			t_sort(dbp);
			break;
		case 1:		/* �p�b�N */
			t_pack(dbp);
			break;
		case 2:		/* �C���f�b�N�X�č\�z */
			t_reindex(dbp);
			break;
		case 3:		/* �C���f�b�N�X�ؑ� */
			t_chgidx(dbp);
			break;
		case 4:		/* �J�E���g */
			t_count(dbp);
			break;
		case 5:		/* ���� */
			t_recall(dbp);
			break;
		case 6:		/* �폜���[�h */
			del_mode();
			break;
		case 7:		/* �t���b�V�� */
			t_flush(dbp);
			break;
		case 8:		/* ���ޯ���쐬 */
			t_idx_create(dbp, NULL);
			break;
		default:
			loop_sw = 0;
		}

		if (csel != -1)
			sel = csel;

		w_popup(wp);
		if (w_check() == wp)
			sel = -1;
		else
			loop_sw = 0;
	}

	w_close(wp);
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
	WINDOW	*wp;
	char	file_name[10], key[11];
	int	ch;

	dp = dbp->dp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 30, 6, 8, 18);
	w_stdwin(wp, "�\�[�g", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	w_dspstr(wp, 1, 1, "�o�̓t�@�C���� [        ]");
	w_dspstr(wp, 1, 2, "�L�[           [          ]");

	/* �o�̓t�@�C�������� */
	ch = w_inputs(wp, file_name, 17, 1, 8, 0x111);
	switch (ch) {
	case MOUSE_R:
	case MOUSE_L:
		if (w_check() != wp)
			break;
	case KEY_CR:
		/* �L�[�I�� */
		if (sel_key(dp, key) != -1) {
			w_dspstr(wp, 17, 2, key);
			if (jikkou(wp)) {
				set_color(C_RED, -1);
				w_dspstr(wp, 1, 3, "���s��");
				dbf_sort(dp, file_name, key);	/* �\�[�g */
			}
		}
		break;
	}

	w_close(wp);
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
	WINDOW	*wp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 11, 4, 8, 18);
	w_stdwin(wp, "�p�b�N", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		set_color(C_RED, -1);
		w_dspstr(wp, 1, 1, "���s��");
		db_pack(dbp);		/* �p�b�N���� */
		display2(dbp);
	}

	w_close(wp);
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
	WINDOW	*wp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 18, 4, 8, 18);
	w_stdwin(wp, "���ޯ���č\�z", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		set_color(C_RED, -1);
		w_dspstr(wp, 1, 1, "���s��");
		db_reindex(dbp);	/* �C���f�b�N�X�č\�z���� */
		display2(dbp);
	}

	w_close(wp);
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
	static	int	sel;
	char	*item[20];
	int	csel;
	WINDOW	*wp;
	int	max_fnl, cn, n, len;

	if (dbp->n_idx == 0)
		return;

	/* �C���f�b�N�X�t�@�C�������o�� */
	item[0] = "�Ȃ�";
	max_fnl = 0;
	for (n = 1; n <= dbp->n_idx; n++) {
		ip = dbp->ip[n];
		if ((len = strlen(ip->file_name)) > max_fnl)
			max_fnl= len;
		item[n] = ip->file_name;
	}

	/* �E�B���h�E�I�[�v�� */
	cn = max(max_fnl, 11) + 4;
	wp = w_open(m_gx, m_gy, cn, n+2, 8, 18);
	w_stdwin(wp, "���ޯ���ؑ�", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �C���f�b�N�X�t�@�C���I�� */
	csel = w_select(wp, item, 1, n, sel, C_BLACK, C_BLUE);
	if (csel != -1) {
		db_chgidx(dbp, csel);	/* �C���f�b�N�X�؂�ւ� */
		display2(dbp);
		sel = csel;
	}

	w_close(wp);
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
	IDX_H	*ihp;
	WINDOW	*wp;
	int	i, kl, cn, ch, loop_sw;
	char	buf[40];
	long	n;

	ip = dbp->ip[dbp->master];

	if (ip == NULL)
		return;

	ihp = ip->ihp;

	kl = strlen(ihp->index);
	cn = kl + ihp->l_key + 2;
	cn = max(cn, 8) + 3;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, cn, 4, 8, 18);
	w_stdwin(wp, "�J�E���g", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	
	/* �\�����\�� */
	w_xprintf(wp, 1, 1, "%s", ihp->index);
	inp_field(wp, ihp->l_key);

	memset(buf, ' ', ihp->l_key);
	loop_sw = 1;
	while (loop_sw) {
		w_popup(wp);
		if (ihp->type == 0)
			ch = w_inputs(wp, buf, kl+2, 1, ihp->l_key, 0x81);
		else
			ch = w_inputn(wp, buf, kl+2, 1, ihp->l_key, 0, 0x00);
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
			if (w_check() != wp) {
				loop_sw = 0;
				break;
			}
		case KEY_CR:
			for (i = ihp->l_key - 1; i >= 0; i--) {
				if (buf[i] != ' ')
					break;
			}

			if (i >= 0) {
				n = db_count(dbp, buf, i + 1);	/* �J�E���g */
				w_xprintf(wp, 1, 2, "%5ld ��", n);
				display2(dbp);
			} else
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	w_close(wp);
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
	WINDOW	*wp;

	wp = w_open(m_gx, m_gy, 11, 3, 8, 18);
	w_stdwin(wp, "����", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		db_recall(dbp);		/* �������� */
		display2(dbp);
	}

	w_close(wp);
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
	static	char	*item[] = {
		"�폜���R�[�h�L��",
		"�폜���R�[�h����"
	};
	int	csel;
	WINDOW	*wp;

	wp = w_open(m_gx, m_gy, 21, 4, 8, 18);
	w_stdwin(wp, "�폜���[�h", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	csel = w_select(wp, item, 1, 2, _db_set_deleted, C_BLACK, C_BLUE);
	if (csel != -1)
		set_deleted(csel);	/* �폜���[�h�Z�b�g */

	w_close(wp);
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
	WINDOW	*wp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 17, 3, 8, 18);
	w_stdwin(wp, "�t���b�V��", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	if (jikkou(wp)) {
		db_flush(dbp);	/* �t���b�V������ */
		display2(dbp);
	}

	w_close(wp);
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
	WINDOW	*wp, *wp2;
	char	buf[4];
	int	y, i, j, csel;
	DBF_I	dbfi[20];
	static	char	*item[] = {
		"�����^",
		"���l�^",
		"���t�^",
		"�_���^"
	};

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(180, 100, 37, 13, 8, 18);
	w_stdwin(wp, "�ް��ް��쐬", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	/* �f�[�^�t�@�C�����\�� */
	w_xprintf(wp, 1, 1, "�ް� ̧�ٖ� %s", file_name);

	w_dspstr(wp, 1, 2, "�t�B�[���h��   �`��     ����  ����");
	for (i = 0; ; i++) {
		if (i < 9) {
			y = i + 3;
		} else {
			y = 11;
			w_scroll_up(wp);
		}
		w_dspstr(wp, 1, y, "[          ] [      ]  [   ]  [  ]");

		/* �t�@�C�������� */
		w_inputs(wp, dbfi[i].name, 2, y, 10, 0x91);
		for (j = 10; j > 0; j--) {
			if (dbfi[i].name[j-1] != ' ')
				break;
		}
		if (j == 0)
			break;
		dbfi[i].name[j] = '\0';

		/* �t�B�[���h�`���I�� */
		wp2 = w_open(m_gx, m_gy, 13, 6, 8, 18);
		w_stdwin(wp2, "�`��", C_BLACK, C_WHITE, C_BLACK);
		csel = w_select(wp2, item, 1, 4, 0, C_BLACK, C_BLUE);
		w_close(wp2);
		if (csel == -1)
			break;
		w_dspstr(wp, 15, y, item[csel]);
		switch (csel) {
		case 0:
			dbfi[i].type = 'C';
			break;
		case 1:
			dbfi[i].type = 'N';
			break;
		case 2:
			dbfi[i].type = 'D';
			break;
		case 3:
			dbfi[i].type = 'L';
			break;
		}

		/* �t�B�[���h������ */
		for (;;) {
			if (dbfi[i].type != 'D') {
				w_inputn(wp, buf, 25, y, 3, 0, 0x90);
				dbfi[i].l_field = natoi(buf, 3);
			} else {
				w_dspstr(wp, 25, y, "  8");
				dbfi[i].l_field = 8;
			}
			if (dbfi[i].l_field > 0 && dbfi[i].l_field < 128)
				break;
		}

		/* �������������� */
		if (dbfi[i].type == 'N') {
			w_inputn(wp, buf, 32, y, 2, 0, 0x90);
			dbfi[i].l_dec = natoi(buf, 2);
		} else
			dbfi[i].l_dec = 0;
	}

	if (i != 0)
		dbp = db_create(file_name, dbfi, i);	/* �f�[�^�x�[�X�쐬 */
	else
		dbp = NULL;

	w_close(wp);

	return(dbp);
}

/*=======================================================================
 |
 |		�C���f�b�N�X�t�@�C���쐬����
 |
 |	int	t_idx_create(dbp, fname)
 |
 |		DB	*dbp;		�c�a�|�C���^
 |		char	*fname;		�C���f�b�N�X�t�@�C����
 |
 =======================================================================*/
static	int	t_idx_create(DB *dbp, char *fname)
{
	DBF	*dp;
	WINDOW	*wp, *wp2;
	char	file_name[14], buf[11], key[100];
	int	ch, uniq, csel, idx;
	static	char	*item1[] = {
		"�I��",
		"�A��"
	};
	static	char	*item2[] = {
		"����",
		"�����Ȃ�"
	};

	idx = 0;
	dp = dbp->dp;

	/* �E�B���h�E�I�[�v�� */
	wp = w_open(m_gx, m_gy, 40, 7, 8, 18);
	w_stdwin(wp, "���ޯ���쐬", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;

	w_dspstr(wp, 1, 1, "���ޯ�� ̧�ٖ� [        ]");
	w_dspstr(wp, 1, 2, "�L�[           [                    ]");
	w_dspstr(wp, 1, 3, "�L�[�̏d��     [        ]");

	/* �C���f�b�N�X�t�@�C�������� */
	if (fname == NULL) {
		ch = w_inputs(wp, file_name, 17, 1, 8, 0x111);
	} else {
		strcpy(file_name, fname);
		w_dspstr(wp, 17, 1, file_name);
		ch = KEY_CR;
	}
	if (strchr(file_name, '.') == NULL)
		strcat(file_name, ".NDX");
	switch (ch) {
	case MOUSE_R:
	case MOUSE_L:
		if (w_check() != wp)
			break;
	case KEY_CR:
		key[0] = '\0';
	select_key:
		/* �L�[�I�� */
		if (sel_key(dp, buf) == -1)
			break;
		strcat(key, buf);
		w_dspstr(wp, 17, 2, key);
		wp2 = w_open(m_gx, m_gy, 17, 4, 8, 18);
		w_stdwin(wp2, "�L�[�̘A��", C_BLACK, C_WHITE, C_BLACK);
		csel = w_select(wp2, item1, 1, 2, 0, C_BLACK, C_BLUE);
		w_close(wp2);
		if (csel == 1) {
			strcat(key, "+");
			goto select_key;
		} else if (csel == -1)
			break;

		/* �L�[�̏d���I�� */
		wp2 = w_open(m_gx, m_gy, 17, 4, 8, 18);
		w_stdwin(wp2, "�L�[�̏d��", C_BLACK, C_WHITE, C_BLACK);
		csel = w_select(wp2, item2, 1, 2, 0, C_BLACK, C_BLUE);
		w_close(wp2);
		if (csel == -1)
			break;
		w_dspstr(wp, 17, 3, item2[csel]);
		uniq = csel;

		if (jikkou(wp)) {
			/* �C���f�b�N�X�t�@�C���쐬 */
			idx = db_idx_create(dbp, file_name, key, uniq);
			if (fname == NULL)
				display2(dbp);
		}
		break;
	}

	w_close(wp);

	return(idx);
}

/*=======================================================================
 |
 |		�L�[�I������
 |
 |	void	sel_key(dp, key)
 |
 |		DBF	*dp;		�c�a�e�|�C���^
 |		char	*key;		�L�[���̓o�b�t�@
 |
 =======================================================================*/
static	int	sel_key(DBF *dp, char *key)
{
	WINDOW	*wp;
	register int 	i;
	int	csel;
	char	*item[30];

	for (i = 0; i < dp->n_field; i++)
		item[i] = dp->flp[i].name;

	wp = w_open(m_gx, m_gy, 13, dp->n_field+2, 8, 18);
	w_stdwin(wp, "�L�[�ꗗ", C_BLACK, C_WHITE, C_BLACK);
	wp->flag = 0x01;
	csel = w_select(wp, item, 1, dp->n_field, 0, C_BLACK, C_BLUE);
	if (csel != -1)
		strcpy(key, item[csel]);

	w_close(wp);

	return(csel);
}

/*=======================================================================
 |
 |		���R�[�h�\���i�ꗗ�`���j
 |
 |	void	display(wp, dbp, y)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		DB	*dbp;		�c�a�|�C���^
 |		int	y;		�\���s�ʒu
 |
 =======================================================================*/
static	void	display(WINDOW *wp, DB *dbp, int y)
{
	char	fmt[10];
	DBF	*dp;
	FIELD	*flp;
	int	i, x;

	dp = dbp->dp;
	flp = dp->flp;
	if (db_bof(dbp)) {
		set_color(C_BLACK, -1);
		sprintf(fmt, "%%-%ds", dp->dhp->l_rec);
		w_xprintf(wp, 1, y, fmt, "[BOF]");
	} else if (db_eof(dbp))  {
		set_color(C_BLACK, -1);
		sprintf(fmt, "%%-%ds", dp->dhp->l_rec);
		w_xprintf(wp, 1, y, fmt, "[EOF]");
	} else {
		dbf_read(dp, dbp->rno);
		set_color(*dp->rbp == '*' ? C_BLUE : C_BLACK, -1);
		w_dspnstr(wp, 1, y, dp->rbp, 1);
		x = 2;
		for (i = 0; i < dp->n_field; i++) {
			w_dspnstr(wp, x, y, flp->fad, flp->l_field);
			x += flp->l_field;
			w_dspstr(wp, x, y, " ");
			x++;
			flp++;
		}
		/*w_dspnstr(wp, 1, y, dp->rbp, dp->dhp->l_rec);*/
		set_color(C_BLACK, -1);
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

	w_popup(dwp);

	dp = dbp->dp;

	if (db_bof(dbp)) {
		set_color(C_RED, -1);
		w_cls(dwp);
		w_dspstr(dwp, 1, 1, "[BOF]");
	} else if (db_eof(dbp)) {
		set_color(C_RED, -1);
		w_cls(dwp);
		w_dspstr(dwp, 1, 1, "[EOF]");
	} else {
		dbf_read(dp, dbp->rno);
		set_color(*dp->rbp == '*' ? C_BLUE : C_BLACK, -1);

		for (i = 0, flp = dp->flp; i < dp->n_field; i++, flp++) {
			sprintf(fmt, "%%-10s [%%.%ds]", flp->l_field);
			w_xprintf(dwp, 1, i+1, fmt, flp->name, flp->fad);
		}
	}
	set_color(C_BLACK, -1);
}

static	void	w_cls(WINDOW *wp)
{
	int	x, y;

	for (y = 1; y < wp->ny - 1; y++) {
		w_loc(wp, 1, y);
		for (x = 1; x < wp->nx - 2; x++)
			w_putch(wp, ' ');
	}
}

/*=======================================================================
 |
 |		���s�m�F
 |
 |	int	jikkou(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 =======================================================================*/
static	int	jikkou(WINDOW *wp)
{
	static	MOUSE	mouse[2];
	int	x, y, ch;

	set_color(C_BLUE, -1);

	x = wp->nx - 8;
	y = wp->ny - 2;
	w_dspstr(wp, x, y, "���s");
	w_line(wp, x*wp->dx-1, y*wp->dy-1, (x+4)*wp->dx, (y+1)*wp->dy
							, C_BLUE, 1, 0);
	mouse[0].x = x;
	mouse[0].y = y;
	mouse[0].len = 4;
	mouse[0].code = KEY_CR;
	m_reg(wp, mouse, C_BLUE);
	ch = w_input(wp, 0);
	m_rel(mouse);

	return(ch == KEY_CR ? 1 : 0);
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
	WINDOW	*wp;
	char	*msg;

	wp = w_open(m_gx, m_gy, 33, 3, 8, 18);
	w_stdwin(wp, "�G���[���b�Z�[�W", C_RED, C_WHITE, C_BLACK);
	wp->flag = 0x01;
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
	w_dspstr(wp, 1, 1, msg);
	waitkey(wp);
	w_close(wp);
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

	static	char	tile[12] = {
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa,
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa
	};

	g_line(0, 0, 639, 399, 0, 2, 2, 0, 12, tile);
	set_color(C_BLUE, C_WHITE);
	w_dspstr(scrp, 22, 0, "*** ISAM LIBRARY SAMPLE PROGRAM ***");
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
	w_endscr();		/* �I������ */
	printf("�����I�����܂���.\n");
	exit(1);
}