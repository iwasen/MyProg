/*	FILE DESCRIPTION

		FILE NAME	: d0cdrf.c
		ROUTINE		: ��������
		REVISION	:
		REMARKS		:	87.11.13	S.Aizawa
*/

#include	"d0cdrf.h"
#include	"la_ws/include/d3libc.h"

extern	DSET	*d3lbgn();
extern	char	*d3allc();
extern	double	atof();
chtype	cdrf10();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	int	main_loop;		/* main loop switch */
static	char	*out_file;		/* �o�̓t�@�C���� */
static	char	*prt_name;		/* �v�����^�f�o�C�X�� */
static	int	exec_flag;		/* ���s flag */
static	int	disp_mode;		/* �\�����[�h */
static	int	line;			/* �ꗗ�\���s�� */
static	char	tmp_file[64];		/* temp.file name */
static	struct	jk_tbl	{		/* ���������e�[�u�� */
	int	joken;
	char	atai[42];
} *jktbl;
static	int	ao_flag;		/* AND/OR flag */
static	D_CELL	*dtp;			/* ���ʕ\���e�[�u�� */
static	int	ds_num;			/* �f�[�^�Z�b�g�� */
static	DSET	*idx_dsp;		/* �����t�@�C���p�f�[�^�Z�b�g�|�C���^*/
static	int	mgno;			/* �������O���[�v�m���D */
static	int	kw_n;			/* �L�[���[�h�� */
static	char	**kw_p;			/* �L�[���[�h�e�[�u���|�C���^ */
static	char	*pk_name;		/* ���L�[���[�h */
static	D_CELL	*kw_ptr;		/* �L�[���[�h���ڃ|�C���^ */
static	PD_KEYREC	*keyrec;	/* �L�[�l�ꗗ�|�C���^ */
static	int	row_n;			/* �����t�@�C���s�� */
static	int	col_n;			/* �����t�@�C���� */
static	int	ndsp_fld;		/* �\�����Ȃ����ڐ� */
static	PD_TFREC *tfp;			/* �^�U�l�x�N�g���|�C���^ */
static	PD_LNREC *lnp;			/* �s�ԍ��x�N�g���|�C���^ */
static	char	tmp_buf[sizeof(TEMPLETE) * 100];
static	T_TEMPLETE *tmpp = (T_TEMPLETE *)tmp_buf; /* �e���v���[�gpointer */
static	char	t_buf[4096];		/* �s�f�[�^�ǂݍ��� buffer */
static	char	w_buf[4096];		/* ���[�N�o�b�t�@ */

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d0cdrf()
		ROUTINE		: ���C������
		INPUT		: file = ���ʏo�̓t�@�C����
		OUTPUT		: none
*/

d0cdrf(file)
char	*file;
{
	static char *func1[8] = {
		"��  �s", "���Z�b�g", "�����ݒ�", "�����ύX",
		"�S�w��", "�S����", "LADB�ύX", "�؂芷��"
	};
	static char *func2[8] = {
		NULL, "���Z�b�g", "�����ݒ�", "�����ύX",
		"�S�w��", "�S����", "LADB�ύX", "�؂芷��"
	};
	static char *func3[8] = {
		"���ꗗ", "�\���ݒ�", "��  ��", NULL,
		NULL, NULL, NULL, "�؂芷��"
	};
	static char *func4[8] = {
		"���O�ꗗ", "�\���ݒ�", "��  ��", NULL,
		NULL, NULL, NULL, "�؂芷��"
	};
	static char imes1[] =
		"���s�^�����̑ΏۂƂȂ�%s��I�����Ă�������  ";
	static char imes2[] =
		"�����̑ΏۂƂȂ�%s��I�����Ă�������  ";
	static char imes3[] =
		"�Y������%s�͂���܂���  ";
	int	func_sw;		/* �t�@���N�V�����L�[�ؑփt���O */
	int	c_pos;			/* �J�����g�J�[�\���ʒu */
	int	i;
	chtype	ch;

	main_loop = 1;			/* loop switch on */

	out_file = file;		/* save out put file name */

	cdrf05();			/* �O���� */

	func_sw = 0;			/* reset function key switch */
	c_pos = 0;			/* �J�����g�ʒu���Z�b�g */
	while (main_loop) {
		cdrf90();		/* �Œ蕔�\�� */

		 /* set soft label key */
		d3fcmk(func_sw ? (disp_mode ? func4 : func3) : (out_file ? func1 : func2));
		mvwprintw(pad, l_num-3, 2,
			ds_num != 0 ? (out_file ? imes1 : imes2) : imes3,
			pk_name);
		/* ���ʕ\�����L�[���� */
		ch = cdrf10(&c_pos, disp_mode, IT_MUL);
		switch (ch) {
		case KEY_F(1):		/* ���s�^�i���O�^���j�ꗗ */
			if (func_sw == 0) {
				if (out_file != 0) {
					exec_flag = 1;	/* ���s�t���O �I�� */
					main_loop = 0;
				}
			} else {
				disp_mode = !disp_mode;	/* �\�����[�h�ύX */
			}
			break;
		case KEY_F(2):		/* ���Z�b�g�^���O�ꗗ */
			if (func_sw == 0) {
				cdrf20(out_file ? 0 : 1); /* ���Z�b�g���� */
				c_pos = 0;	/* �J�����g�ʒu���Z�b�g */
			} else
				cdrf45();	/* �\���ݒ� */
			break;
		case KEY_F(3):		/* �����ݒ�^��� */
			if (func_sw == 0) {
				if (row_n != 0) {
					cdrf25();	/* �����ݒ� */
					c_pos = 0;
				} else
					beep();
			} else
				cdrf50();	/* ��� */
			break;
		case KEY_F(4):		/* �����ύX */
			if (func_sw == 0) {
				cdrf40();	/* �����ύX */
				cdrf80();	/* �����t�@�C���g�p�J�n */
				c_pos = 0;	/* �J�����g�ʒu���Z�b�g */
			}
			break;
		case KEY_F(5):		/* �S�w�� */
			if (func_sw == 0) {
				for (i = 0; i < ds_num; i++)
					dtp[i].flag |= 0x01; /* �S�t���O�I�� */
			}
			break;
		case KEY_F(6):		/* �S���� */
			if (func_sw == 0) {
				for (i = 0; i < ds_num; i++)
					dtp[i].flag &= ~0x01;/* �S�t���O�I�t */
			}
			break;
		case KEY_F(7):		/* LADB�ύX */
			if (func_sw == 0) {
				d0whre();	/* �f�B���N�g���ύX */
				cdrf80();	/* �����t�@�C���g�p�J�n */
				c_pos = 0;	/* �J�����g�ʒu���Z�b�g */
			}
			break;
		case KEY_F(8):		/* �؂�ւ� */
			func_sw ^= 1;
			break;
		case KEY_F(17):		/* �����I�� */
			main_loop = 0;	/* reset loop switch */
			break;
		}
	}

	cdrf60(c_pos);			/* �㏈�� */

	werase(pad);			/* ��ʏ��� */
	/*slk_clear();*/			/* clear soft label key */
	/*prefresh(pad,0,0,0,0,l_num,80);*/ /* display screen */

	return(0);
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf05()
		ROUTINE		: �O����
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf05()
{
	cdrf90();
	tmpnam(tmp_file);		/* make temp.file name */
	line = l_num - 9;		/* �ꗗ�\������ */
	tmpp->tp_bufp = t_buf;
	disp_mode = 0;			/* �\�����[�h */
	exec_flag = 0;			/* ���s�t���O���Z�b�g */
	ndsp_fld = 0;

	prt_name = "pr|lp>/dev/null";	/* ����R�}���h */

	cdrf80();			/* �����t�@�C���g�p�J�n */
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf10
		ROUTINE		: �������ʕ\��
		INPUT		: d_mode = �\�����[�h
				: i_mode = ���̓��[�h
		INPUT/OUTPUT	: c_pos  = current reverse position
		OUTPUT		: return value = input pf key code
*/

static
chtype cdrf10(c_pos, d_mode, i_mode)
int	*c_pos;
int	d_mode, i_mode;
{
	static	char	msg[] = "0000��  page (000/000)";
	int	page, max_page, n_page, n, l, c, n_col, c_pos1, width, pn;
	D_CELL	*p;
	chtype	ch;
	int	scr_flag;		/* ���X�N���[���^�s�t���O */
	char	s[10];

	getyx(pad, l, c);		/* get cursor position */

	n_col = d_mode ? 1 : 4;		/* �P�s�̍��ڐ��Z�b�g */
	n_page = n_col * line;		/* �P�y�[�W�̍��ڐ��Z�b�g */
	max_page = (ds_num - 1) / n_page;	/* �y�[�W���Z�b�g */

	sprintf(s, "%4d", ds_num);	/* item number display data */
	strncpy(&msg[0], s, 4);
	sprintf(s, "%3d", max_page+1);	/* set max page display data */
	strncpy(&msg[18], s, 3);

	c_pos1 = *c_pos % n_page;	/* set current cursor position */
	page = *c_pos / n_page;		/* set page No. */
	for (;;) {
		sprintf(s, "%3d", page+1);	/* page display data */
		strncpy(&msg[14], s, 3);
		 /* display item number & page */
		mvwaddstr(pad, d_mode ? 2 : 3, 57, msg);

		n = (page == max_page) ? ds_num - page * n_page : n_page;
		pn = page * n_page;
		p = dtp + pn;

		/* set display data */
		width = cdrf15(n, p, pn, d_mode, &scr_flag) + 2;

		wmove(pad, l, c);		/* set cursor position */
		/* display & select item */
		ch = d3sitm(n, p, width, n_col, i_mode, &c_pos1, 1);
		switch (ch) {
		case KEY_NPAGE:			/* next page */
		case KEY_F(25):			/* ��X�N���[�� */
			if (page != max_page)
				page++;
			else
				page = 0;
			c_pos1 = 0;
			break;
		case KEY_PPAGE:			/* prev page */
		case KEY_F(26):			/* ���X�N���[�� */
			if (page != 0)
				page--;
			else
				page = max_page;
			c_pos1 = 0;
			break;
		case KEY_F(19):		/* �O�t�B�[���h */
			if (page != 0) {
				page--;
				c_pos1 = n_page - 1;
			}
			break;
		case KEY_F(20):		/* ���t�B�[���h */
			if (page * n_page + c_pos1 + 1 < ds_num) {
				page++;
				c_pos1 = 0;
			}
			break;
		case KEY_F(21):		/* ��t�B�[���h */
			if (page != 0) {
				page--;
				c_pos1 += n_page - n_col;
			}
			break;
		case KEY_F(22):		/* ���t�B�[���h */
			if (page * n_page + c_pos1 + n_col < ds_num) {
				page++;
				c_pos1 -= n_page - n_col;
			}
			break;
		case KEY_F(23):		/* ���X�N���[�� */
			if (d_mode && scr_flag)
				ndsp_fld++;
			break;
		case KEY_F(24):		/* �E�X�N���[�� */
			if (d_mode && ndsp_fld != 0)
				ndsp_fld--;
			break;
		default:
			*c_pos = page * n_page + c_pos1;
			return(ch);
		}
	}
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf15()
		ROUTINE		: �������ʃf�[�^�Z�b�g
		INPUT		: n  = �\���f�[�^�Z�b�g��
				: p  = ���ڃe�[�u���|�C���^
				: pn = ���ڔԍ�
				: d_mode = �\�����[�h
				: scr_flag = ���X�N���[���^�s�t���O
		OUTPUT		: return value = �\������
*/

static
cdrf15(n, p, pn, d_mode, scr_flag)
int	n;
D_CELL	*p;
int	pn;
int	d_mode;
int	*scr_flag;
{
	int	i, j, cn, len, len1, len2, dn, dc, ndsp_count, offset[80];
	char	s[82], *sp;

	*scr_flag = 0;
	if (d_mode == 0) {
		tmpp->tp_templete[0].tp_col = 1;
		tmpp->tp_cnum = 1;
		d3mktp(idx_dsp, tmpp);
		dn = 1;
		dc = 18;
		offset[0] = 0;
		mvwprintw(pad, 3, 22, "%s�ꗗ", pk_name);
	} else {
		ndsp_count = ndsp_fld;
		for (i = 0, cn = 0; i < col_n; i++) {
			if (kw_ptr[i].flag != 0) {
				if (i != 0 && ndsp_count != 0)
					ndsp_count--;
				else
					tmpp->tp_templete[cn++].tp_col = i + 1;
			}
		}
		tmpp->tp_cnum = cn;
		d3mktp(idx_dsp, tmpp);

		for (i = 0; i < 80; i++)
			s[i] = 0;

		for (dn = 0, dc = 0; dn < cn; dn++) {
			switch (tmpp->tp_templete[dn].tp_mode) {
			case DS_L:
				len1 = 3;
				break;
			case DS_I:
				len1 = 11;
				break;
			case DS_R:
				len1 = 13;
				break;
			case DS_C:
				len1 = tmpp->tp_templete[dn].tp_len;
				break;
			default:
				len1 = 10;
			}
			len2 = strlen(kw_p[tmpp->tp_templete[dn].tp_col-1]);
			len = (len1 > len2 ? len1 : len2) + 1;
			if (dc+len >= 78) {
				*scr_flag = 1;	/* �\���ł��Ȃ����ڗL�� */
				break;
			}
			offset[dn] = dc;
			strcpy(s+dc, kw_p[tmpp->tp_templete[dn].tp_col-1]);
			dc += len;
		}
		tmpp->tp_cnum = dn;

		for (i = 0; i < dc; i++) {
			if (s[i] == 0)
				s[i] = ' ';
		}
		s[dc] = 0;
		wmove(pad, 3, 2);
		wclrtoeol(pad);
		waddstr(pad, s);
	}

	sp = w_buf;
	for (i = 0; i < n; i++) {
		for (j = 0; j < dc; j++)
			s[j] = ' ';
		d3trd(idx_dsp, lnp->ln_vec[pn], 1, tmpp);
		for (j = 0; j < dn; j++) {
			cdrf85(&t_buf[tmpp->tp_templete[j].tp_offset],
		 	  tmpp->tp_templete[j].tp_mode, s+offset[j]);
		}

		p->ptr = sp;
		for (j = 0; j < dc; j++) {
			if (s[j] == '\0')
				*sp++ = ' ';
			else
				*sp++ = s[j];
		}
		*sp++ = '\0';
		pn++;
		p++;
	}
	return(dc);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf20()
		ROUTINE		: ���Z�b�g����
		INPUT		: flag = �I���t���O
		OUTPUT		: none
*/

static
cdrf20(flag)
{
	int	i;

	ds_num = row_n;			/* �f�[�^�Z�b�g���Z�b�g */

	tfp->tf_n = row_n;
	for (i = 0; i < ds_num; i++) {
		tfp->tf_vec[i] = 1;	/* �s�^�e�x�N�g���Z�b�g */
		dtp[i].flag = flag;	/* �I���t���O �Z�b�g */
	}

	d3toln(tfp, lnp, row_n);	/* �s�ԍ��Z�b�g */
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf25()
		ROUTINE		: �l���́E��������
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf25()
{
	static	char imes[] = "�L�[���[�h����͂��Ă�������";
	static	char *func[8] = {
		"��  ��", "AND/OR", "���^��", "���^��",
		"���^��", "�L-��-�h", "�l�ꗗ", NULL
	};
	static	char	msg[] = "0000��  page (000/000)";
	int	lcount, i, rst, page, n, max_page;
	struct	jk_tbl	*jp;
	char	s[40];

	/* ���������e�[�u�������� */
	for (i = 0; i < kw_n; i++) {
		jktbl[i].joken = PD_CND_EQ;
		jktbl[i].atai[0] = '\0';
	}
	ao_flag = 0;

	max_page = (kw_n - 1) / line;	/* �y�[�W���Z�b�g */
	sprintf(s, "%4d", kw_n);	/* item number display data */
	strncpy(&msg[0], s, 4);
	sprintf(s, "%3d", max_page+1);	/* set max page display data */
	strncpy(&msg[18], s, 3);

	page = 0;		/* clear page counter */
	lcount = 0;		/* clear line counter */
scrdsp:
	wmove(pad, 2, 0); wclrtobot(pad);	/* erase window */
	d3fcmk(func);			/* set soft label key */
	mvwaddstr(pad, 3, 8, "�L�[���[�h");
	mvwaddstr(pad, 3, 28, "�`�m�c�����@�l");
	cdrf26(page, lcount);

	for (;;) {
		sprintf(s, "%3d", page+1);	/* page display data */
		strncpy(&msg[14], s, 3);
		 /* display item number & page */
		mvwaddstr(pad, 3, 57, msg);
		pnoutrefresh(pad,0,0,0,0,l_num,80); /* display screen */

		n = page * line + lcount;
		jp = jktbl + n;
		rst = d3chin(lcount+5, 40, 40, jp->atai);
		switch (rst) {
		case 0x0a:		/* CR */
			if (lcount < line-1 && n < kw_n-1) {
				lcount++;
				cdrf26(page, lcount);
			} else
				beep();
			break;
		case KEY_F(1):		/* ���� */
			cdrf35();	/* �������� */
			return;
		case KEY_F(2):		/* AND/OR */
			if (ao_flag == 0) {
				ao_flag = 1;
				mvwaddstr(pad, 3, 28, "�@�n�q");
			} else {
				ao_flag = 0;
				mvwaddstr(pad, 3, 28, "�`�m�c");
			}
			break;
		case KEY_F(3):		/* ���^�� */
			if (jp->joken  == PD_CND_GT)
				jp->joken = PD_CND_GE;
			else
				jp->joken = PD_CND_GT;
			cdrf27(jp, lcount);
			break;
		case KEY_F(4):		/* ���^�� */
			if (jp->joken == PD_CND_LT)
				jp->joken = PD_CND_LE;
			else
				jp->joken = PD_CND_LT;
			cdrf27(jp, lcount);
			break;
		case KEY_F(5):		/* = */
			if (jp->joken == PD_CND_EQ)
				jp->joken = PD_CND_NE;
			else
				jp->joken = PD_CND_EQ;
			cdrf27(jp, lcount);
			break;
		case KEY_F(6):		/* �L�[���[�h */
			mvwaddstr(pad, l_num-3, 2, imes);
			pnoutrefresh(pad,0,0,0,0,l_num,80); /* display screen */
			s[0] = '\0';
			rst = d3chin(l_num-3, 32, 30, s);
			if (rst == 0x0a) {
				if (isdigit(s[0])) {
					i = atoi(s) - 1;
				} else {
					for (i = 0; i < kw_n; i++) {
						if (strcmp(s, kw_p[i]) == 0)
							break;
					}
				}
				if (i >= 0 && i < kw_n) {
					page = i / line;
					lcount = i % line;
					cdrf26(page, lcount);
				} else
					beep();
			}
			wmove(pad, l_num-3,0);
			wclrtoeol(pad);
			break;
		case KEY_F(7):		/* �l�ꗗ */
			cdrf30(n);	/* �l�ꗗ���� */
			goto scrdsp;
		case KEY_F(17):		/* �����I�� */
			return;
		case KEY_UP:		/* ���� */
			if (lcount != 0) {
				lcount--;
				cdrf26(page, lcount);
			}
			break;
		case KEY_DOWN:		/* ����� */
			if (lcount < line-1 && n < kw_n-1) {
				lcount++;
				cdrf26(page, lcount);
			}
			break;
		case KEY_NPAGE:		/* ���y�[�W */
		case KEY_F(25):			/* ��X�N���[�� */
			if (page < max_page)
				page++;
			else
				page = 0;
			lcount = 0;
			cdrf26(page, lcount);
			break;
		case KEY_PPAGE:		/* �O�y�[�W */
		case KEY_F(26):			/* ���X�N���[�� */
			if (page != 0)
				page--;
			else
				page = max_page;
			lcount = 0;
			cdrf26(page, lcount);
			break;
		}
	}
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf26()
		ROUTINE		: �L�[���[�h�\��
		INPUT		: page   = �y�[�W�ԍ�
				: lcount = �s�ԍ�
		OUTPUT		: none
*/

static
cdrf26(page, lcount)
int	page;
int	lcount;
{
	int	i, n;
	char	s[8];

	wmove(pad, 5, 0); wclrtobot(pad);	/* erase window */
	for (i = 0; i < line; i++) {
		n = i + page * line;
		if (n == kw_n)
			break;
		sprintf(s,"%3d ",n+1);
		if (i == lcount) {
			wattrset(pad, A_REVERSE);
			mvwaddstr(pad, i+5, 0, s);
			wattrset(pad, 0);
		} else
			mvwaddstr(pad, i+5, 0, s);
		mvwaddstr(pad, i+5, 5, kw_p[n]);
		cdrf27(&jktbl[n], i);
		mvwaddstr(pad, i+5, 40, jktbl[n].atai);
	}
	wmove(pad, lcount+5, 40);	/* �J�[�\������������Ȃ��ׂ̏��� */
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf27()
		ROUTINE		: �����\��
		INPUT		: jp     = ���������e�[�u���|�C���^
				: lcount = �s�ԍ�
		OUTPUT		: none
*/

static
cdrf27(jp, lcount)
struct	jk_tbl	*jp;
int	lcount;
{
	char	*s;

	switch (jp->joken) {
	case PD_CND_EQ:
		s = "��";
		break;
	case PD_CND_NE:
		s = "��";
		break;
	case PD_CND_GT:
		s = "��";
		break;
	case PD_CND_GE:
		s = "��";
		break;
	case PD_CND_LT:
		s = "��";
		break;
	case PD_CND_LE:
		s = "��";
		break;
	}
	mvwaddstr(pad, lcount+5, 35, s);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf30()
		ROUTINE		: �l�ꗗ����
		INPUT		: kn = keyword No.
		OUTPUT		: none
*/

static
cdrf30(kn)
int	kn;
{
	static	char *func[8] = {
		NULL, NULL, NULL, NULL, 
		NULL, NULL, NULL, NULL
	};

	int	rst, i, *ip, c_pos, memno, loop_sw;
	int	at_num;			/* �l�̐� */
	chtype	ch;
	D_CELL	*attbl;			/* �l�ꗗ�e�[�u�� */
	char	*wp, wbuf[256];

	wmove(pad,2,0); wclrtobot(pad);	/* erase window */
	mvwaddstr(pad,3,12,"�l�ꗗ");
	mvwaddstr(pad,3,22,kw_p[kn]);	/* display keyword */
	mvwaddstr(pad,l_num-3,2,"�l���w�肵�Ă�������  ");
	d3fcmk(func);			/* set soft label key */

	if ((rst = d3tkyv(idx_dsp, kn+1, &keyrec)) != 0) {
		d3errw(d3cver(rst, "if"));
		return;
	}
	at_num = keyrec->key_length;

	if ((memno = d3mgno()) < 0) {
		free((char *)keyrec);
		d3errw(E_M);
		return;
	}

	if ((attbl = (D_CELL *)d3allc(memno, at_num * sizeof(D_CELL))) == 0) {
		free((char *)keyrec);
		d3free(memno);
		d3errw(E_M);
		return;
	}

	for (i = 0; i < at_num; i++) {
		attbl[i].flag = 0;
		if (keyrec->col_type == DS_C)
			ip = (int *)keyrec->key_type.c[i];
		else
			ip = &keyrec->key_type.i[i];
		cdrf85(ip, keyrec->col_type, wbuf);

		if ((wp = d3allc(memno, strlen(wbuf)+1)) == 0) {
			free((char *)keyrec);
			d3free(memno);
			d3errw(E_M);
			return;
		}
		strcpy(wp, wbuf);
		attbl[i].ptr = wp;
	}

	c_pos = 0;
	for (loop_sw = 1 ;loop_sw != 0; ) {
		ch = d3item(at_num, attbl, 50, IT_NUM | IT_CEN, &c_pos);
		switch (ch) {
		case 0x0a:		/* CR */
			strcpy(jktbl[kn].atai, attbl[c_pos].ptr);
			loop_sw = 0;
			break;
		case KEY_F(17):		/* �����I�� */
			loop_sw = 0;
			break;
		}
	}
	free((char *)keyrec);
	d3free(memno);
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf35()
		ROUTINE		: ��������
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf35()
{
	int	i, err;
	PD_CNDREC	cond;

	wmove(pad,2,0); wclrtobot(pad);	/* erase window */
	slk_clear();			/* clear soft label key */
	mvwaddstr(pad,l_num-3,0,"����������");
	prefresh(pad,0,0,0,0,l_num,80);

	for (i = 0; i < ds_num; i++) {
		if ((dtp[i].flag & 0x01) == 0)
			tfp->tf_vec[lnp->ln_vec[i] - 1] = 0;
	}

	tmpp->tp_cnum = col_n;
	for (i = 0; i < col_n; i++)
		tmpp->tp_templete[i].tp_col = i + 1;
	d3mktp(idx_dsp, tmpp);

	for (i = 0; i < kw_n; i++) {
		if (jktbl[i].atai[0] == '\0')
			continue;
		cond.cnd_relop = ao_flag;
		cond.cnd_col = i + 1;
		cond.cnd_op = jktbl[i].joken;
		cdrf86(jktbl[i].atai, tmpp->tp_templete[i].tp_mode,
				&cond.cnd_val);

		if ((err = d3srch(idx_dsp, &cond, tfp, tfp)) != 0) {
			d3errw(d3cver(err, "if"));
			return;
		}
	}

	d3toln(tfp, lnp, row_n);
	ds_num = lnp->ln_n;

	for (i = 0; i < ds_num; i++)
		dtp[i].flag = 0x01;
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf40()
		ROUTINE		: �����t�@�C�����ꗗ
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf40()
{
	static	char	title[] = "�����t�@�C�����ꗗ";
	static	char *func1[8] = {
		NULL, NULL, NULL, NULL, 
		NULL, NULL, "LADB�ύX", NULL
	};
	static	char *func2[8] = {
		NULL, NULL, NULL, NULL, 
		NULL, NULL, NULL, NULL
	};
	static	char	imes[] = "�����t�@�C����I�����Ă�������  ";
	static	char	msg[] = "�����t�@�C��������  ";
	int	loop_sw, memno, c_pos, c_pos2, n_idx, i, err, usr_flag;
	DSET	*grp_dsp, *usr_dsp;
	D_CELL	*p_idx, *ip;
	PD_KEYREC	*grp_key, *usr_key;
	chtype	ch;
	char	s[128], save_ladb[128], grp_path[128], usr_path[128];

	if ((memno = d3mgno()) < 0) {		/* �������ԍ����o�� */
		free((char *)keyrec);
		d3errw(E_M);
		return;
	}
	strcpy(save_ladb, cur_ladb);		/* �J�����gLADB�Z�[�u */
start:
	wmove(pad,2,0); wclrtobot(pad);	/* erase window */
	mvwaddstr(pad, l_num-3, 2, msg);
	d3fcmk(func1);			/* set soft label key */
	prefresh(pad,0,0,0,0,l_num,80);	/* display screen */

	d3path(cur_ladb, GROUP_INDEX, grp_path);
	if ((grp_dsp = d3lbgn(grp_path, "r", 0, &err)) == 0) {
		d3errw(d3cver(err, "gi"));
		return;
	}
	if ((err = d3tkyv(grp_dsp, 3, &grp_key)) != 0) {
		d3lend(grp_dsp, "q", 0);
		d3errw(d3cver(err, "gi"));
		return;
	}
	d3lend(grp_dsp, "q", 0);
	n_idx = grp_key->key_length + 1;
	usr_flag = 0;
	if (out_file == 0) {
		d3path(cur_ladb, "IND.USR", usr_path);
		if ((usr_dsp = d3lbgn(usr_path, "r", 0, &err)) != 0) {
			if (d3tkyv(usr_dsp, 1, &usr_key) == 0) {
				n_idx += usr_key->key_length;
				usr_flag = 1;
			}
			d3lend(usr_dsp, "q", 0);
		}
	}
	if ((p_idx = (D_CELL *)d3allc(memno, sizeof(D_CELL) * n_idx)) == 0) {
		d3errw(E_M);
		return;
	}
	ip = p_idx;
	ip->result = ip->flag = 0;
	ip->ptr = GROUP_INDEX;
	ip++;
	for (i = 0; i < grp_key->key_length; i++, ip++) {
		ip->result = ip->flag = 0;
		ip->ptr = grp_key->key_type.c[i];
	}
	if (usr_flag) {
		for (i = 0; i < usr_key->key_length; i++, ip++) {
			ip->result = ip->flag = 0;
			ip->ptr = usr_key->key_type.c[i];
		}
	}

	c_pos = 0;
	for (i = 0; i < n_idx; i++) {
		if (strcmp(cur_grp, p_idx[i].ptr) == 0) {
			c_pos = i;
			break;
		}
	}

	loop_sw = 1;
	while (loop_sw) {
		mvwaddstr(pad, 3, 26, title);
		mvwaddstr(pad, l_num-3, 2, imes);
		d3fcmk(func1);			/* set soft label key */
		ch = d3item(n_idx, p_idx, 20, 0, &c_pos);
		switch (ch) {		/* key data check */
		case 0x0a:		/* CR */
			if (strcmp(p_idx[c_pos].ptr, GROUP_INDEX) == 0) {
				cdrf82();
				if ((err = cdrf81(GROUP_INDEX)) != 0) {
					d3errw(err);
					break;
				}
				if (row_n == 0) {
					break;
				}
				cdrf20(0);
				wmove(pad,2,0); wclrtobot(pad);
				mvwaddstr(pad, l_num-3, 2, imes);
				d3fcmk(func2);
				c_pos2 = 0;
			retry:
				if ((ch = cdrf10(&c_pos2, 1, 0)) == '\n') {
					tmpp->tp_cnum = 1;
					tmpp->tp_templete[0].tp_col = 3;
					d3mktp(idx_dsp, tmpp);
					d3trd(idx_dsp, c_pos2+1, 1, tmpp);
					strcpy(cur_grp, t_buf);
					/*d3drwt();*/	/* write current file */
					loop_sw = 0;
				} else if (ch == KEY_F(17)) {
					wmove(pad,2,0); wclrtobot(pad);
					break;
				} else {
					goto retry;
				}
			} else {
				strcpy(cur_grp, p_idx[c_pos].ptr);
				/*d3drwt();*/	/* write current file */
				loop_sw = 0;
			}
			break;
		case KEY_F(7):		/* LADB�ύX */
			d0whre();	/* �f�B���N�g���ύX */
			cdrf90();	/* �������������t�@�C�����\�� */
			goto start;
		case KEY_F(17):		/* �����I�� */
			if (strcmp(cur_ladb, save_ladb) != 0) {
				strcpy(cur_ladb, save_ladb);
				/*d3drwt();*/
			}
			loop_sw = 0;
			break;
		}
	}
	d3free(memno);			/* �o�n�k�|�c��������� */
	free(grp_key);			/* �V�X�e�������ꗗ�p��������� */
	if (usr_flag)
		free(usr_key);		/* ���[�U�����ꗗ�p��������� */
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf45()
		ROUTINE		: �\���ݒ菈��
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf45()
{
	static	char *func[8] = {
		"��  ��", NULL, NULL, NULL,
		"�S�w��", "�S����", NULL, NULL
	};
	int	i, c_pos;
	chtype	ch;

	wmove(pad, 2, 0); wclrtobot(pad);	/* erase window */
	d3fcmk(func);			/* set soft label key */

	for (i = 0; i < kw_n; i++)
		kw_ptr[i].result = kw_ptr[i].flag;	/* save flag */

	mvwaddstr(pad,3,24,"�L�[���[�h");
	mvwaddstr(pad,l_num-3,2,"�\���^�������L�[���[�h��I�����Ă�������  ");

	c_pos = 0;
	for (;;) {
		ch = d3item(kw_n, kw_ptr, 50,
			IT_MUL | IT_NUM | IT_ALL | IT_CEN, &c_pos);
		switch (ch) {
		case KEY_F(1):		/* �ݒ� */
			for (i = 0; i < kw_n; i++) {
				if (kw_ptr[i].flag & 0x01) {
					ndsp_fld = 0;
					return;
				}
			}
			beep();
			break;
		case KEY_F(17):		/* �����I�� */
			for (i = 0; i < kw_n; i++)
				kw_ptr[i].flag = kw_ptr[i].result;
			return;
		}
	}
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf50()
		ROUTINE		: �������
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf50()
{
	int	i, j, cn, len, len1, len2, dn, dc, offset[80];
	int	k, ck, ndsp_count;
	FILE	*pfp;
	char	s[82];

	if ((pfp = popen(prt_name, "w")) == NULL)
		return;

	wmove(pad, l_num-3, 0); wclrtoeol(pad);
	mvwaddstr(pad, l_num-3, 2, "�����  ");
	prefresh(pad,0,0,0,0,l_num,80);

	if (disp_mode == 0) {
		tmpp->tp_templete[0].tp_col = 1;
		tmpp->tp_cnum = 1;
		d3mktp(idx_dsp, tmpp);
		dn = 1;
		dc = 16;
		offset[0] = 0;
		fprintf(pfp, "                    %s�ꗗ\n\n", pk_name);
		ck = 4;
	} else {
		ndsp_count = ndsp_fld;
		for (i = 0, cn = 0; i < col_n; i++) {
			if (kw_ptr[i].flag != 0) {
				if (i != 0 && ndsp_count != 0)
					ndsp_count--;
				else
					tmpp->tp_templete[cn++].tp_col = i + 1;
			}
		}
		tmpp->tp_cnum = cn;
		d3mktp(idx_dsp, tmpp);

		for (i = 0; i < 80; i++)
			s[i] = 0;

		/*strcpy(s+2, "�m��");*/
		for (dn = 0, dc = 0; dn < cn; dn++) {
			switch (tmpp->tp_templete[dn].tp_mode) {
			case DS_L:
				len1 = 3;
				break;
			case DS_I:
				len1 = 11;
				break;
			case DS_R:
				len1 = 13;
				break;
			case DS_C:
				len1 = tmpp->tp_templete[dn].tp_len;
				break;
			default:
				len1 = 10;
			}
			len2 = strlen(kw_p[tmpp->tp_templete[dn].tp_col-1]);
			len = (len1 > len2 ? len1 : len2) + 1;
			if (dc+len >= 78)
				break;
			offset[dn] = dc;
			strcpy(s+dc, kw_p[tmpp->tp_templete[dn].tp_col-1]);
			dc += len;
		}
		tmpp->tp_cnum = dn;

		for (i = 0; i < dc; i++) {
			if (s[i] == 0)
				s[i] = ' ';
		}
		s[dc] = 0;
		fprintf(pfp, "  %s\n\n", s);
		ck = 1;
	}

	for (i = 0, k = 0; i < ds_num; i++) {
		for (j = 0; j < dc; j++)
			s[j] = ' ';
		d3trd(idx_dsp, lnp->ln_vec[i], 1, tmpp);
		for (j = 0; j < dn; j++) {
			cdrf85(&t_buf[tmpp->tp_templete[j].tp_offset],
		 	  tmpp->tp_templete[j].tp_mode, s+offset[j]);
		}

		for (j = 0; j < dc; j++) {
			if (s[j] == '\0')
				s[j] = ' ';
		}
		s[j] = '\0';
		fprintf(pfp, "  %s", s);
		if (++k == ck) {
			fprintf(pfp, "\n");
			k = 0;
		}
	}
	if (k != 0)
		fprintf(pfp, "\n");
	pclose(pfp);
}



/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf60()
		ROUTINE		: �㏈��
		INPUT		: c_pos = current cursor position
		OUTPUT		: none
*/

static
cdrf60(c_pos)
int	c_pos;
{
	if (out_file != 0)		/* �o�̓t�@�C���L�� ? */
		cdrf92(c_pos);		/* ���ʏo�� */
	cdrf82();			/* �����t�@�C���g�p�I�� */

	unlink(tmp_file);		/* erase temp.file */
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf80()
		ROUTINE		: �����t�@�C���g�p�J�n�^�I��
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf80()
{
	static	char	imes[] =
		"�k�`�f�[�^�x�[�X�܂��͍�����ύX���Ă�������  ";
	static char *func[8] = {
		NULL, NULL, NULL, "�����ύX",
		NULL, NULL, "LADB�ύX", NULL
	};
	int	err;
	chtype	ch;

	cdrf82();			/* �����t�@�C���g�p�I�� */
retry:
	if ((err = cdrf81(cur_grp)) != 0) {	/* �����t�@�C���g�p�J�n */
		cdrf90();		/* �����t�@�C�����\�� */
		cdrf82();		/* �����t�@�C���g�p�I�� */
		wmove(pad,2,0); wclrtobot(pad);	/* erase window */
		d3fcmk(func);		/* �t�@���N�V�����L�[�\�� */
		cdrf98(err);		/* �G���[���b�Z�[�W�\�� */
		mvwaddstr(pad, l_num - 3, 2, imes);
		for (;;) {
			ch = d3chin(l_num-3, 48, 0, 0);
			switch (ch) {
			case KEY_F(4):		/* �����ύX */
				cdrf40();
				goto retry;
			case KEY_F(7):		/* LADB�ύX */
				d0whre();	/* �f�B���N�g���ύX */
				goto retry;
			case KEY_F(17):		/* �����I�� */
				main_loop = 0;
				return;
			}
		}
	}
	cdrf20(out_file ? 0 : 1);		/* ���Z�b�g���� */
	ndsp_fld = 0;
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf81()
		ROUTINE		: �����t�@�C���g�p�J�n
		INPUT		: id_file = �����t�@�C����
		OUTPUT		: none
*/

static
cdrf81(id_file)
char	*id_file;
{
	int	err, i;
	char	s[256];
	char	*file;

	if (id_file[0] == '\0')			/* �����t�@�C�����ݒ� */
		return(-1);

	if (d3xgrp(id_file))
		file = "gi";
	else if (d3xind(id_file))
		file = "if";
	else if (d3xusr(id_file))
		file = "uf";
	else if (d3xdat(id_file))
		file = "ds";
	else
		file = "";

	d3path(cur_ladb, id_file, s);
	idx_dsp = d3lbgn(s, "r", 0, &err);	/* �����t�@�C���g�p�J�n */
	if (err != 0)
		return(d3cver(err, file));

	if ((err = d3istb(idx_dsp)) == 2)	/* �\�`���`�F�b�N */
		return(d3cver(DE_DS_FORMAT, file));

	mgno = idx_dsp->ds_memno;		/* �������ԍ����o�� */

	/* �L�[���[�h���o�� */
	if ((err = d3tkey(idx_dsp, &kw_n, &kw_p)) != 0)
		return(d3cver(err, file));
	if (kw_n != 0)
		pk_name = kw_p[0];
	else
		pk_name = "����";

	/* ���������e�[�u���G���A�m�� */
	if ((jktbl = (struct jk_tbl *)d3allc(mgno,
			kw_n * sizeof(struct jk_tbl))) == 0)
		return(d3cver(E_M, file));

	/* �L�[���[�h���ڃe�[�u���쐬 */
	if ((kw_ptr = (D_CELL *)d3allc(mgno, kw_n * sizeof(D_CELL))) == 0)
		return(d3cver(E_M, file));

	for (i = 0; i < kw_n; i++) {
		kw_ptr[i].flag = 0x01;
		kw_ptr[i].ptr = kw_p[i];
	}

	/* �s�����o�� */
	if ((row_n = d3trow(idx_dsp)) < 0)
		return(d3cver(row_n, file));

	/* �񐔎��o�� */
	if ((col_n = d3tcol(idx_dsp)) < 0)
		return(d3cver(col_n, file));

	/* �s�^�e�x�N�g���G���A�m�� */
	if ((tfp = (PD_TFREC *)d3allc(mgno, row_n * sizeof(char) + 4)) == 0)
		return(d3cver(E_M, file));

	/* �s�ԍ��x�N�g���G���A�m�� */
	if ((lnp = (PD_LNREC *)d3allc(mgno, row_n * sizeof(int) + 4)) == 0)
		return(d3cver(E_M, file));

	/* ���ʕ\���e�[�u���G���A�m�� */
	if ((dtp = (D_CELL *)d3allc(mgno, row_n * sizeof(D_CELL))) == 0)
		return(d3cver(E_M, file));

	return(0);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf82()
		ROUTINE		: �����t�@�C���g�p�I��
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf82()
{
	if (idx_dsp != 0) {
		d3lend(idx_dsp, "q", 0);	/* �����t�@�C���g�p�I�� */
		idx_dsp = 0;
	}
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf85()
		ROUTINE		: ������ϊ�
		INPUT		: val = �l
				: type = �f�[�^�^
		OUTPUT		: str = ������
*/

static
cdrf85(val, type, str)
int	*val;
int	type;
char	*str;
{
	switch (type) {
	case DS_L:
		if (*val == 0)
			strcpy(str, "F");
		else if (*val == PD_NA)
			strcpy(str, "NA");
		else
			strcpy(str, "T");
		break;
	case DS_I:
		if (*val == PD_NA)
			strcpy(str, "NA");
		else
			sprintf(str, "%d", *val);
		break;
	case DS_R:
		if (*val == PD_NA)
			strcpy(str, "NA");
		else
			sprintf(str, "%g", *(float *)val);
		break;
	case DS_C:
		strcpy(str, (char *)val);
		break;
	}
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf86()
		ROUTINE		: 
		INPUT		: str = ������
				: type = �f�[�^�^
		OUTPUT		: val = �l
*/

static
cdrf86(str, type, val)
char	*str;
int	type;
int	*val;
{
	switch (type) {
	case DS_L:
		if (strcmp(str, "T") == 0)
			*val = 1;
		else if (strcmp(str, "F") == 0)
			*val = 0;
		else
			*val = PD_NA;
		break;
	case DS_I:
		if (strcmp(str, "NA") == 0)
			*val = PD_NA;
		else
			*val = atoi(str);
		break;
	case DS_R:
		if (strcmp(str, "NA") == 0)
			*val = PD_NA;
		else
			*(float *)val = atof(str);
		break;
	case DS_C:
		*(char **)val = str;
		break;
	}
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf90()
		ROUTINE		: �������������t�@�C�����\��
		INPUT		: none
		OUTPUT		: none
*/

static
cdrf90()
{
	static	char	pname[] = "���@���������@��";
	char	s[40];

	wmove(pad,1,0); wclrtobot(pad);	/* ��ʏ��� */

	mvwaddstr(pad, 1, 0, pname);	/* �������\�� */
	d3dred();		/* �J�����g�f�B���N�g���\�� */

	sprintf(s,"< %s >",cur_grp);
	wmove(pad,1,40);
	wclrtoeol(pad);
	waddstr(pad,s);		/* �����t�@�C�����\�� */
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf92()
		ROUTINE		: ���ʏo�͏���
		INPUT		: c_pos = current cursor position
		OUTPUT		: none
*/

static
cdrf92(c_pos)
int	c_pos;
{
	FILE	*fp;
	int	i, flag;

	if ((fp = fopen(out_file,"w")) == NULL) {	/* open output file */
		d3errw(E_TF_A);			/* error shori */
		return;
	}

	if (exec_flag != 0) {
		d3toln(tfp, lnp, row_n);
		tmpp->tp_cnum = 1;
		tmpp->tp_templete[0].tp_col = 1;
		d3mktp(idx_dsp, tmpp);
		for (i = 0, flag = 0; i < lnp->ln_n; i++) {
			if (dtp[i].flag & 0x01) {
				d3trd(idx_dsp, lnp->ln_vec[i], 1, tmpp);
				fprintf(fp,"%s\n",t_buf);
				flag = 1;
			}
		}
		if (flag == 0 && lnp->ln_n != 0) {
			d3trd(idx_dsp, lnp->ln_vec[c_pos], 1, tmpp);
			fprintf(fp,"%s\n",t_buf);
		}
	}
	fclose(fp);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: cdrf98()
		ROUTINE		: �G���[���b�Z�[�W�\��
		INPUT		: err_code = error code
		OUTPUT		: none
*/

static
cdrf98(err_code)
int	err_code;
{
	beep();
	d3msed(w1,err_code);		/* display error message */
	prefresh(w1,0,0,l_num-2,30,l_num-2,79);
}
