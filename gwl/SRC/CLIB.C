/************************************************************************
 *									*
 *		�O���t�B�b�N�E�B���h�E���C�u����			*
 *									*
 *		����		: �ėp���o�͏���			*
 *		�t�@�C����	: clib.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>
#include <ctype.h>
#include <jctype.h>
#include <jstring.h>
#include <conio.h>
#include "gwlib.h"

static	void	nedit(char *, char *, int, int);
static	void	d_kanji(int, int, int);
static	void	d_ank(int, int, int);
static	char	**sv_func;	/* �t�@���N�V�����L�[�f�[�^�Z�[�u�|�C���^ */
static	SELECT	*s_top;
static	int	csr_sl = 0, csr_el = 16, csr_kanji = 0;
static	int	csr_blink;

/*=======================================================================
 |
 |		�����t�B�[���h�f�[�^����
 |
 |	int	w_inputx(wp, idata, n, mp, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		INPUT	idata[];	���̓t�B�[���h���
 |		int	n;		�������̓t�B�[���h
 |		MOUSE	*mp;		�}�E�X�I�����
 |		int	color;		�}�E�X�I�����J���[
 |
 =======================================================================*/
int	w_inputx(WINDOW *wp, INPUT idata[], int n, MOUSE *mp, int color)
{
	INPUT	*ip;
	int	loop_sw, ch;

	m_reg(wp, mp, color);	/* �}�E�X�I��o�^ */

	loop_sw = 1;
	while (loop_sw) {
		ip = &idata[n];
		if (ip->len2 == -1) {
			/* ��������� */
			ch = w_inputs(wp, ip->bufp, ip->x, ip->y
					, ip->len1, ip->flag);
		} else {
			/* ���l���� */
			ch = w_inputn(wp, ip->bufp, ip->x, ip->y
					, ip->len1, ip->len2, ip->flag);
		}
		switch (ch) {
		case KEY_UP:
			if (ip->up != -1)
				n = ip->up;
			else
				beep();
			break;
		case KEY_DOWN:
			if (ip->down != -1)
				n = ip->down;
			else
				beep();
			break;
		case KEY_LEFT:
			if (ip->left != -1)
				n = ip->left;
			else
				beep();
			break;
		case KEY_RIGHT:
			if (ip->right != -1)
				n = ip->right;
			else
				beep();
			break;
		case KEY_CR:
			if (ip->cr != -1)
				n = ip->cr;
			else
				loop_sw = 0;
			break;
		case MOUSE_L:
			if (m_sel != -1)
				n = m_sel;
			break;
		case MOUSE_R:
		case KEY_F1:
		case KEY_F2:
		case KEY_F3:
		case KEY_F4:
		case KEY_F5:
		case KEY_F6:
		case KEY_F7:
		case KEY_F8:
		case KEY_F9:
		case KEY_F10:
		case KEY_ESC:
			loop_sw = 0;
			break;
		default:
			beep();
			break;
		}
	}

	m_rel(mp);	/* �}�E�X�I������ */

	return(ch);
}

/*=======================================================================
 |
 |		���������
 |
 |	int	w_inputs(wp, buf, x, y, len, flag)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		char	*buf;		���̓o�b�t�@
 |		int	x;		���͌��ʒu
 |		int	y;		���͍s�ʒu
 |		int	len;		���͕�����
 |		int	flag;		bit0�@0/1 = �����s�^��
 |					bit1  0/1 = �p�����^����
 |					bit4  0/1 = �o�b�t�@���������Ȃ��^����
 |					bit7  0/1 = ���E�L�[�t�B�[���h�ԁ^��
 |					bit8  0/1 = �m�t�k�k�Ȃ��^�t��
 |
 =======================================================================*/
int	w_inputs(WINDOW *wp, char *buf, int x, int y, int len, int flag)
{
	register int	i;		/* ���[�v�J�E���^ */
	register int	c;		/* ���͕��� */
	int	c2;			/* �����̂Q�o�C�g�� */
	int	delc2;			/* �����̂Q�o�C�g�ڏ����t���O */
	int	count;			/* ���͕������J�E���^ */
	int	loop_sw;		/* ���[�v����X�C�b�` */
	int	ins_mode;		/* �}�����[�h�t���O */
	WINDOW	*kwp;

	/* �o�b�t�@������ */
	if (flag & 0x10) {
		memset(buf, ' ', len);
		w_dspnstr(wp, x, y, buf, len);
	}

	/* �������͂̂��߂̏��� */
	if ((flag & 0x01) && g_kcolor != -1) {
		kwp = w_open(0, 24*16, 80, 1, 8, 16);
		w_line(kwp, 0, 0, 639, 15, g_kcolor, 2, 0);
	}

	ins_mode = 0;
	delc2 = 0;
	count = 0;
	loop_sw = 1;
	while(loop_sw) {
		w_loc(wp, x + count, y);	/* �J�[�\���ʒu�Z�b�g */

		if (iskanji(buf[count]))	/* �J�[�\���T�C�Y�ݒ� */
			csr_kanji = 1;
		else
			csr_kanji = 0;

		c = w_input(wp, 1);	/* �L�[���� */

		if (iskanji(c))		/* �����R�[�h�H */
			c2 = getch();	/* �����R�[�h�̂Q�o�C�g�ڎ��o�� */
		else
			c2 = 0;

		switch (c) {
		case KEY_CLR:
			memset(buf, ' ', len);
			w_loc(wp, x, y);
			for (i = 0; i < len; i++)
				w_putch(wp, ' ');
			/* no break */
		case KEY_HOME:
			count = 0;
			break;
		case KEY_F1:
		case KEY_F2:
		case KEY_F3:
		case KEY_F4:
		case KEY_F5:
		case KEY_F6:
		case KEY_F7:
		case KEY_F8:
		case KEY_F9:
		case KEY_F10:
		case KEY_CR:
		case KEY_UP:
		case KEY_DOWN:
		case KEY_ESC:
		case MOUSE_L:
		case MOUSE_R:
			loop_sw = 0;
			break;
		case KEY_RIGHT:
			if (count+1 < len) {
				count++;
				/* �����R�[�h��������Q�o�C�g�i�߂� */
				if (nthctype(buf, count) == CT_KJ2)
					count++;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
				/* �����R�[�h��������Q�o�C�g�߂� */
				if (nthctype(buf, count) == CT_KJ2)
					count--;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_BS:
			if (count != 0) {
				count--;
				w_putch(wp, '\b');
				/* �����R�[�h��������Q�o�C�g�߂� */
				if (nthctype(buf, count) == CT_KJ2) {
					count--;
					w_putch(wp, '\b');
				}
			} else
				break;
			/* no break */
		case KEY_DEL:
			if (count < len) {
				if (iskanji(buf[count])) {
				memcpy(&buf[count], &buf[count+1]
							, len - count - 1);
					buf[len-1] = ' ';
				}
				memcpy(&buf[count], &buf[count+1]
							, len - count - 1);
				buf[len-1] = ' ';
				for (i = count; i < len; i++)
					w_putch(wp, buf[i]);
			}
			break;
		case KEY_INS:
			if (ins_mode == 0) {
				ins_mode = 1;
				csr_sl = 8;
			} else {
				ins_mode = 0;
				csr_sl = 0;
			}
			break;
		default:
			/* �������͕s�Ŋ��������͂��ꂽ��G���[ */
			if ((flag & 0x01) == 0 && c2 != 0) {
				beep();
				break;
			}

			/* �p�������͂̏ꍇ�̕����`�F�b�N */
			if (count < (c2 ? len-1 : len)
				  && ((flag & 0x01) != 0 || !iscntrl(c & 0x7f))
				  && ((flag & 0x02) == 0 || isdigit(c))) {
				if (ins_mode) {
					if (buf[len-1] != ' ') {
						beep();
						break;
					}
					if (c2 != 0) {
						if (buf[len-2] != ' ') {
							beep();
							break;
						}
						for (i = len-1; i > count; i--)
							buf[i] = buf[i-1];
					}
					for (i = len-1; i > count; i--)
						buf[i] = buf[i-1];
				}else{
					if (c2 == 0){
						if (iskanji(buf[count]))
							delc2 = 1;
					}else{
						if (! iskanji(buf[count]) &&
							 iskanji(buf[count+1]))
							delc2 = 1;
					}
				}
				w_putch(wp, c);
				buf[count++] = (char)c;
				if (c2) {
					w_putch(wp, c2);
					buf[count++] = (char)c2;
				}
				if (delc2) {
					w_putch(wp, ' ');
					buf[count] = ' ';
					delc2 = 0;
				}
				if (ins_mode) {
					for (i = count; i < len; i++)
						w_putch(wp, buf[i]);
				}
			} else
				beep();
			break;
		}
	}

	csr_sl = 0;
	csr_kanji = 0;

	/* �������̓E�B���h�E�N���[�Y */
	if ((flag & 0x01) && g_kcolor != -1) {
		w_close(kwp);
	}

	/* �m�t�k�k�t�� */
	if (flag & 0x100)
		buf[len] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		���l����
 |
 |	int	w_inputn(wp buf, x, y, len1, len2, flag)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		char	*buf;		���̓o�b�t�@
 |		int	x;		���͌��ʒu
 |		int	y;		���͍s�ʒu
 |		int	len1;		�t�B�[���h��
 |		int	len2;		����������
 |		int	flag		bit7  0/1 = ���E�L�[�t�B�[���h�ԁ^��
 |					bit4  0/1 = �o�b�t�@���������Ȃ��^����
 |					bit8  0/1 = �m�t�k�k�Ȃ��^�t��
 |
 =======================================================================*/
int	w_inputn(WINDOW *wp, char *buf, int x, int y, int len1, int len2
	, int flag)
{
	register int	i;		/* ���[�v�J�E���^ */
	register int	c;		/* ���͕��� */
	int	count;			/* ���͕������J�E���^ */
	int	loop_sw;		/* ���[�v����X�C�b�` */
	char	ibuf[40];		/* ���̓o�b�t�@ */

	/* �o�b�t�@������ */
	if (flag & 0x10) {
		memset(buf, ' ', len1);
		w_dspnstr(wp, x, y, buf, len1);
	}

	count = 0;

	memset(ibuf, ' ', len1);
	loop_sw = 1;
	while(loop_sw) {
		w_loc(wp, x + count, y);	/* �J�[�\���ʒu�Z�b�g */

		c = w_input(wp, 1);		/* �L�[���� */
		switch (c) {
		case KEY_BS:
			if (count != 0) {
				w_cputs(wp, "\b \b");
				ibuf[--count] = ' ';
			}
			break;
		case KEY_CLR:
			memset(buf, ' ', len1);
			w_loc(wp, x, y);
			for (i = 0; i < len1; i++)
				w_putch(wp, ' ');
			/* no break */
		case KEY_HOME:
			count = 0;
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_RIGHT:
			if (count+1 < len1) {
				count++;
			} else {
				if ((flag & 0x80) == 0)
					loop_sw = 0;
			}
			break;
		case KEY_F1:
		case KEY_F2:
		case KEY_F3:
		case KEY_F4:
		case KEY_F5:
		case KEY_F6:
		case KEY_F7:
		case KEY_F8:
		case KEY_F9:
		case KEY_F10:
		case KEY_CR:
		case KEY_UP:
		case KEY_DOWN:
		case KEY_ESC:
		case MOUSE_R:
		case MOUSE_L:
			if (count != 0)
				nedit(ibuf, buf, len1, len2);
			loop_sw = 0;
			break;
		default:
			if (isdigit(c) == 0) {
				beep();
				break;
			}
			/* no break */
		case '+':
		case '-':
		case '.':
			if (count < len1) {
				w_putch(wp, c);
				ibuf[count++] = (char)c;
			} else
				beep();
			break;
		}
	}

	w_dspnstr(wp, x, y, buf, len1);

	/* �m�t�k�k�t�� */
	if (flag & 0x100)
		buf[len1] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		���͐��l�ҏW
 |
 |	int	nedit(ibuf, obuf, len1, len2)
 |
 |		char	*ibuf;		���̓o�b�t�@
 |		char	*obuf;		�o�̓o�b�t�@
 |		int	len1;		�t�B�[���h��
 |		int	len2;		����������
 |
 =======================================================================*/
static	void	nedit(char *ibuf, char *obuf, int len1, int len2)
{
	char	fmt[40], buf1[40], buf2[40];

	strncpy(buf1, ibuf, len1);
	buf1[len1] = '\0';

	sprintf(fmt, "%%%d.%dlf", len1, len2);
	sprintf(buf2, fmt, atof(buf1));

	strncpy(obuf, buf2, len1);
}

/*========================================================================
 |
 |		�L�[���͑ҏ���
 |
 |	int	waitkey(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 |		�Ԓl			���̓L�[�R�[�h
 |
 =======================================================================*/
int	waitkey(WINDOW *wp)
{
	w_loc(wp, 0, 0);
	return(w_input(wp, 0));		/* �L�[���͑� */
}

/*========================================================================
 |
 |		�L�[���͏���
 |
 |	int	inkey()
 |
 |		�Ԓl			���̓L�[�R�[�h
 |					�i�O�F�L�[���͂Ȃ��j
 |
 =======================================================================*/
int	inkey(void)
{
	register int	ch;
	static	int	plsw, prsw;
	int	wplsw, wprsw;
	int	sv_csr_flag;
	WINDOW	*wp;

	ch = 0;

	if (kbhit() != 0) {		/* �L�[���̓`�F�b�N */
		ch = getch();		/* �P�����ǂݍ��� */
		if (ch == 0x06)		/* �t�@���N�V�����L�[ ? */
			ch = 0x0600 | getch();
	} else {
		/* �}�E�X�J�[�\���ʒu���o�� */
		m_pos(&m_gx, &m_gy, &wplsw, &wprsw);
		if (plsw != wplsw) {
			if (wplsw != 0) {
				ch = MOUSE_L;	/* ���{�^���������ꂽ */
				if ((wp = w_check()) != NULL) {
					if (wp->flag & 0x01) {
						if (m_gy < wp->sy + wp->dy) {
							sv_csr_flag = csr_flag;
							if (sv_csr_flag)
								csr_off();
							w_xmove(wp);
							if (sv_csr_flag)
								csr_on();
							ch = 0;
						}
					}
				}
			}
			plsw = wplsw;
		} else if (prsw != wprsw) {
			if (wprsw != 0)
				ch = MOUSE_R;	/* �E�{�^���������ꂽ */
			prsw = wprsw;
		}
	}
	csr_dsp1();

	return(ch);
}

/*========================================================================
 |
 |		�L�[���͏���
 |
 |	int	w_input(wp, cursor)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	cursor;		�J�[�\���\���t���O
 |
 |		�Ԓl			���̓L�[�R�[�h
 |
 =======================================================================*/
int	w_input(WINDOW *wp, int cursor)
{
	int	ch;
	int	n, m_tx, m_ty;
	WINDOW	*twp, *cwp;
	MOUSE	*mp;
	SELECT	*m_sp, *sp;

	/* �����J�[�\���\�� */
	if (cursor)
		csr_on();

	/* �}�E�X�J�[�\���\�� */
	m_csron();

	m_sel = -1;
	m_sp = 0;

	t_kcgmode(0);		/* �R�[�h�A�N�Z�X */

	for (;;) {
		/* �I���ʒu�T�[�` */
		for (sp = s_top; sp != 0; sp = sp->next) {
			twp = sp->wp;
			if (twp != wp && twp != scrp)
				continue;
			m_tx = (m_gx - twp->sx) / twp->dx;
			m_ty = (m_gy - twp->sy) / twp->dy;
			for (mp = sp->mp, n = 0; mp->len != 0; mp++, n++) {
				if (mp->x <= m_tx && mp->x+mp->len > m_tx
							&& mp->y == m_ty) {
					cwp = w_check();
					if (cwp == twp || cwp == NULL)
						goto match;
					else
						break;
				}
			}
		}
		n = -1;
match:
		if (sp != m_sp || n != m_sel) {
			if (m_sel != -1) {
				/* ���]��߂� */
				mp = &m_sp->mp[m_sel];
				m_csroff();
				w_reverse(m_sp->wp, mp->x, mp->y, mp->len
								, m_sp->color);
				m_csron();
			}
			if (n != -1) {
				/* �I���ʒu�𔽓]������ */
				mp = &sp->mp[n];
				m_csroff();
				w_reverse(sp->wp, mp->x, mp->y, mp->len
								, sp->color);
				m_csron();
			}
			m_sel = n;
			m_sp = sp;
		}

		if ((ch = inkey()) != 0) {	/* �L�[���͗L�� ? */
			break;
		}
	}

	t_kcgmode(1);		/* �r�b�g�}�b�v�A�N�Z�X */

	/* �}�E�X�J�[�\������ */
	m_csroff();

	if (m_sel != -1) {
		/* ���]��߂� */
		mp = &m_sp->mp[m_sel];
		w_reverse(m_sp->wp, mp->x, mp->y, mp->len, m_sp->color);
		if ((mp->code != 0) && (ch == MOUSE_L || ch == MOUSE_R))
			ch = mp->code;
	}

	/* �����J�[�\������ */
	if (cursor)
		csr_off();

	return(ch);
}

/*========================================================================
 |
 |		�}�E�X�I��o�^����
 |
 |	void	m_reg(wp, mp, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		MOUSE	*mp;		�}�E�X���|�C���^
 |		int	color;		���]�F
 |
 =======================================================================*/
void	m_reg(WINDOW *wp, MOUSE *mp, int color)
{
	SELECT	*sp;

	if (mp == 0)
		return;

	if ((sp = malloc(sizeof(SELECT))) == 0)
		return;

	sp->wp = wp;
	sp->mp = mp;
	sp->color = wp->bcolor ^ color;
	sp->next = s_top;

	s_top = sp;
}

/*========================================================================
 |
 |		�}�E�X�I����������
 |
 |	void	m_rel(mp)
 |
 |		MOUSE	*mp;		�}�E�X���|�C���^
 |
 =======================================================================*/
void	m_rel(MOUSE *mp)
{
	SELECT	**spp, *sp;

	if (mp == 0)
		return;

	for (spp = &s_top; *spp != 0; spp = &(*spp)->next) {
		if ((*spp)->mp == mp) {
			sp = *spp;
			*spp = (*spp)->next;
			free(sp);
			break;
		}
	}
}

/*=======================================================================
 |
 |		������\��
 |
 |	void	w_dspstr(wp, x, y, str)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		char	*str;		�\���f�[�^
 |
 =======================================================================*/
void	w_dspstr(WINDOW *wp, int x, int y, char *str)
{
	w_loc(wp, x, y);	/* �J�[�\���ʒu�Z�b�g */
	w_cputs(wp, str);	/* ������\�� */
}

/*=======================================================================
 |
 |		������\���i�����w��j
 |
 |	void	w_dspnstr(wp, x, y, str, len)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		char	*str;		�\���f�[�^
 |		int	len;		����
 |
 =======================================================================*/
void	w_dspnstr(WINDOW *wp, int x, int y, char *str, int len)
{
	w_loc(wp, x, y);	/* �J�[�\���ʒu�Z�b�g */

	while (len--)
		w_putch(wp, *str++);	/* �P�����\�� */
}

/*=======================================================================
 |
 |		�ʒu�w�菑���t���\��
 |
 |	void	w_xprintf(wp, x, y, fmt, p1, p2, .....)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |
 =======================================================================*/
void	w_xprintf(WINDOW *wp, int x, int y, char *fmt, char *p)
{
	char	s[82];

	vsprintf(s, fmt, (char *)&p);
	w_dspstr(wp, x, y, s);
}

/*=======================================================================
 |
 |		�Œ蕔��ʕ\��
 |
 |	void	w_dspscr(wp, scrdat)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		SCRDAT	*scrdat;	�Œ蕔��ʃf�[�^
 |
 =======================================================================*/
void	w_dspscr(WINDOW *wp, SCRDAT *scrdat)
{
	while (scrdat->data) {
		w_dspstr(wp, scrdat->pos_x, scrdat->pos_y, scrdat->data);
		scrdat++;
	}
}

/*=======================================================================
 |
 |		���]�\��
 |
 |	void	w_reverse(wp, x, y, len, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		���ʒu
 |		int	y;		�s�ʒu
 |		int	len;		����
 |		int	color		���]����F
 |
 =======================================================================*/
void	w_reverse(WINDOW *wp, int x, int y, int len, int color)
{
	register int	i, j;
	char	far *vp;

	for (i = 0; i < wp->dy; i++) {
		/* ���]�ʒu�v�Z */
		FP_OFF(vp) = (wp->sy + y * wp->dy + i) * 80 + wp->sx / 8 + x;

		/* �w��F�𔽓] */
		for (j = 0; j < len; j++) {
			if (color & C_BLUE) {
				FP_SEG(vp) = SEG_BVRAM;
				*vp ^= 0xff;
			}
			if (color & C_RED) {
				FP_SEG(vp) = SEG_RVRAM;
				*vp ^= 0xff;
			}
			if (color & C_GREEN) {
				FP_SEG(vp) = SEG_GVRAM;
				*vp ^= 0xff;
			}
			vp++;
		}
	}
}

/*=======================================================================
 |
 |		���C���\��
 |
 |	void	w_dspline(wp, line, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		LINE	*line;		���C���f�[�^
 |		int	color;		�F
 |
 =======================================================================*/
void	w_dspline(WINDOW *wp, LINE *line, int color)
{
	while (line->flag != -1) {
		g_line(wp->sx + line->pos_x1 * wp->dx,
			wp->sy + line->pos_y1 * wp->dy,
			wp->sx + line->pos_x2 * wp->dx,
			wp->sy + line->pos_y2 * wp->dy,
			color, line->flag, 0);
		line++;
	}
}

/*=======================================================================
 |
 |		�J�[�\���ʒu�Z�b�g
 |
 |	void	w_loc(wp, x, y)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |
 =======================================================================*/
void	w_loc(WINDOW *wp, int x, int y)
{
	csr_wp = wp;
	csr_sx = wp->sx + x * wp->dx;
	csr_sy = wp->sy + y * wp->dy + (wp->dy - 16) / 2;
}

/*=======================================================================
 |
 |		�J�[�\���\��
 |
 |	void	csr_on()
 |
 =======================================================================*/
void	csr_on(void)
{
	if (csr_flag == 0) {
		csr_flag = 1;

		_disable();
		csr_dsp1();
		_enable();
	}
}

/*=======================================================================
 |
 |		�J�[�\������
 |
 |	void	csr_off()
 |
 =======================================================================*/
void	csr_off(void)
{
	if (csr_flag != 0) {
		csr_flag = 0;

		_disable();
		csr_dsp1();
		_enable();
	}
}

/*=======================================================================
 |
 |		�a�d�d�o
 |
 |	void	beep()
 |
 =======================================================================*/
void	beep(void)
{
	putch(0x07);
}

/*=======================================================================
 |
 |		���f����
 |
 |	void	w_abort(err_msg)
 |
 |		char	*err_msg;	�G���[���b�Z�[�W
 |
 =======================================================================*/
void	w_abort(char *err_msg)
{
	w_endscr();
	puts(err_msg);
	exit(1);
}

/*=======================================================================
 |
 |		�P�����\��
 |
 |	void	w_putch(wp, c)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	c;		�\������
 |
 =======================================================================*/
void	w_putch(WINDOW *wp, int c)
{
	static	unsigned char	sc;
	int	px, py;
	union	{
		int	i;
		char	c[2];
	} kanji;

	/* �J�[�\���ʒu���o�� */
	px = csr_sx;
	py = csr_sy;

	if (sc) {
		/* �����R�[�h�̂Q�o�C�g�� */
		csr_sx += wp->dx * 2;	/* �J�[�\���ʒu�X�V */
		kanji.c[0] = c;
		kanji.c[1] = sc;
		d_kanji(px, py, jmstojis(kanji.i));	/* �����\�� */
		sc = 0;
	} else {
		if (iskanji(c)) {
			/* �����R�[�h */
			sc = c;		/* �����R�[�h�P�o�C�g�ڃZ�[�u */
		} else {
			/* �`�m�j�R�[�h */
			if (c == '\b')		/* �o�b�N�X�y�[�X */
				csr_sx -= wp->dx;	/* �J�[�\����߂� */
			else {
				csr_sx += wp->dx;	/* �J�[�\���ʒu�X�V */
				d_ank(px, py, c); /* �`�m�j�\�� */
			}
		}
	}
}

/*=======================================================================
 |
 |		�����\��
 |
 |	void	d_kanji(x, y, code)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	code;		�����R�[�h�i�i�h�r�R�[�h�j
 |
 =======================================================================*/
static
void	d_kanji(int x, int y, int code)
{
	struct	font	{
		char	y;
		char	x;
		union	{
			char	b[32];
			int	w[16];
		} f;
	} font;
	register int	i;
	void	*vp;
	unsigned char	b;
	unsigned int	w;

	t_getfont(code, (char *)&font);	/* �t�H���g�p�^�[���ǂݍ��� */
	FP_OFF(vp) = y * 80 + x / 8;	/* �\���A�h���X�v�Z */
	if (font.x == 1) {
		/* ���p���� */
		for (i = 0; i < font.y*8; i++) {
			b = font.f.b[i];

			FP_SEG(vp) = SEG_BVRAM;
			*(char *)vp = ((g_fcolor & C_BLUE) ? b : 0)
					| ((g_bcolor & C_BLUE) ? ~b : 0);

			FP_SEG(vp) = SEG_RVRAM;
			*(char *)vp = ((g_fcolor & C_RED) ? b : 0)
					| ((g_bcolor & C_RED) ? ~b : 0);

			FP_SEG(vp) = SEG_GVRAM;
			*(char *)vp = ((g_fcolor & C_GREEN) ? b : 0)
					| ((g_bcolor & C_GREEN) ? ~b : 0);

			FP_OFF(vp) += 80;
		}
	} else {
		/* �S�p���� */
		for (i = 0; i < font.y*8; i++) {
			w = font.f.w[i];

			FP_SEG(vp) = SEG_BVRAM;
			*(int *)vp = ((g_fcolor & C_BLUE) ? w : 0)
					| ((g_bcolor & C_BLUE) ? ~w : 0);

			FP_SEG(vp) = SEG_RVRAM;
			*(int *)vp = ((g_fcolor & C_RED) ? w : 0)
					| ((g_bcolor & C_RED) ? ~w : 0);

			FP_SEG(vp) = SEG_GVRAM;
			*(int *)vp = ((g_fcolor & C_GREEN) ? w : 0)
					| ((g_bcolor & C_GREEN) ? ~w : 0);

			FP_OFF(vp) += 80;
		}
	}
}

/*=======================================================================
 |
 |		�`�m�j�\��
 |
 |	void	d_ank(x, y, code)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	code;		�`�m�j�R�[�h
 |
 =======================================================================*/
static
void	d_ank(int x, int y, int code)
{
	register int	i;
	char	*vp, b;

	FP_OFF(vp) = y * 80 + x / 8;	/* �\���A�h���X�v�Z */
	for (i = 0; i < 16; i++) {
		b = ank_font[code][i];	/* �t�H���g�p�^�[�����o�� */

		FP_SEG(vp) = SEG_BVRAM;
		*vp = ((g_fcolor & C_BLUE) ? b : 0)
				| ((g_bcolor & C_BLUE) ? ~b : 0);

		FP_SEG(vp) = SEG_RVRAM;
		*vp = ((g_fcolor & C_RED) ? b : 0)
				| ((g_bcolor & C_RED) ? ~b : 0);

		FP_SEG(vp) = SEG_GVRAM;
		*vp = ((g_fcolor & C_GREEN) ? b : 0)
				| ((g_bcolor & C_GREEN) ? ~b : 0);

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		������\��
 |
 |	void	w_cputs(wp, s)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		char	*s;		�\��������
 |
 =======================================================================*/
void	w_cputs(WINDOW *wp, char *s)
{
	while (*s)
		w_putch(wp, *s++);
}

/*=======================================================================
 |
 |		�����F�ݒ�
 |
 |	void	set_color(fc, bc)
 |
 |		int	fc;		�t�H�A�O�����h�J���[
 |		int	bc;		�o�b�N�O�����h�J���[
 |
 =======================================================================*/
void	set_color(int fc, int bc)
{
	if (fc != -1)
		g_fcolor = fc;

	if (bc != -1)
		g_bcolor = bc;
}

/*=======================================================================
 |
 |		�t�@���N�V�����L�[�\��
 |
 |	void	dspfunc(func)
 |
 |		char	*func[];	�t�@���N�V�����L�[�\���f�[�^
 |
 =======================================================================*/
void	dspfunc(char *func[])
{
	static	char	func_key[10][3] = {
		{0x06,'0'}, {0x06,'1'}, {0x06,'2'}, {0x06,'3'}, {0x06,'4'},
		{0x06,'5'}, {0x06,'6'}, {0x06,'7'}, {0x06,'8'}, {0x06,'9'}
	};
	static	int	pos[] = {4, 11, 18, 25, 32, 42, 49, 56, 63, 70};
	static	char	spc[] = "      ";
	static	MOUSE	m_func[11];
	register int	i, j;
	int	sv_fcolor, sv_bcolor;

	m_rel(m_func);
	sv_func = func;
	sv_fcolor = g_fcolor;
	sv_bcolor = g_bcolor;
	set_color(C_BLACK, C_WHITE);
	for (i = j = 0; i < 10; i++) {
		if (*func) {
			w_dspnstr(scrp, pos[i], func_line, *func, 6);
			if (*func[0] == 0xfe)
				strcpy(key_data.fun[i], *func+6);
			else
				strcpy(key_data.fun[i], func_key[i]);
			m_func[j].x = pos[i];
			m_func[j].y = func_line;
			m_func[j].len = 6;
			m_func[j].code = KEY_FUNC + i;
			j++;
		} else {
			w_dspnstr(scrp, pos[i], func_line, spc, 6);
			key_data.fun[i][0] = '\0';
		}
		func++;
	}
	m_func[j].len = 0;
	m_reg(scrp, m_func, g_fkcolor ^ C_WHITE);

	t_fput(&key_data);

	g_fcolor = sv_fcolor;
	g_bcolor = sv_bcolor;
}

/*=======================================================================
 |
 |		�}�E�X���荞�ݏ����i�����J�[�\���\���j
 |
 |	interrupt far	csr_dsp()
 |
 =======================================================================*/
void	interrupt far csr_dsp(void)
{
	static	int	counter;

	/* �J�[�\���_�ŃJ�E���^�X�V */
	if (++counter == 50) {
		counter = 0;
		csr_blink = 1;
	}
/*	_chain_intr(m_intp);*/
	(*m_intp)();
}

/*=======================================================================
 |
 |		�����J�[�\���_�ŏ���
 |
 |	void	csr_dsp1()
 |
 =======================================================================*/
void	csr_dsp1(void)
{
	static	int	dsp_flag;
	static	int	move_flag;
	static	int	w_csr_sx, w_csr_sy;
	register int	i, chg_flag;
	static	char	*vp;
	static	union	REGS	reg;

	/* �J�[�\���\���`�F�b�N */
	chg_flag = 0;
	if (move_flag) {
		if (csr_flag)
			chg_flag = 1;
		move_flag = 0;
	} else {
		if (csr_flag) {
			if (csr_blink)
				chg_flag = 1;
			if (dsp_flag && (csr_sx != w_csr_sx
						|| csr_sy != w_csr_sy)) {
				chg_flag = 1;
				move_flag = 1;
			}
		} else {
			if (dsp_flag)
				chg_flag = 1;
		}
	}
	csr_blink = 0;
		
	if (chg_flag) {
		/* �J�[�\���ʒu�Z�[�u */
		if (dsp_flag == 0) {
			w_csr_sx = csr_sx;
			w_csr_sy = csr_sy;
		}

		/* �J�[�\���\���A�h���X�v�Z */
		FP_OFF(vp) = (w_csr_sy + csr_sl) * 80 + w_csr_sx / 8;

		/* �}�E�X�J�[�\���n�e�e */
		if (m_csr_flag) {
			reg.x.ax = 2;
			int86(0x33, &reg, &reg);
		}

		/* �J�[�\���\�� */
		if (csr_kanji == 0) {
			for (i = csr_sl; i < csr_el; i++) {
				FP_SEG(vp) = SEG_BVRAM;
				*vp ^= 0xff;
				FP_SEG(vp) = SEG_RVRAM;
				*vp ^= 0xff;
				FP_SEG(vp) = SEG_GVRAM;
				*vp ^= 0xff;
				FP_OFF(vp) += 80;
			}
		} else {
			for (i = csr_sl; i < csr_el; i++) {
				FP_SEG(vp) = SEG_BVRAM;
				*(int *)vp ^= 0xffff;
				FP_SEG(vp) = SEG_RVRAM;
				*(int *)vp ^= 0xffff;
				FP_SEG(vp) = SEG_GVRAM;
				*(int *)vp ^= 0xffff;
				FP_OFF(vp) += 80;
			}
		}

		/* �}�E�X�J�[�\���n�m */
		if (m_csr_flag) {
			reg.x.ax = 1;
			int86(0x33, &reg, &reg);
		}

		/* �J�[�\���_�Ńt���O���] */
		dsp_flag = !dsp_flag;
	}
}