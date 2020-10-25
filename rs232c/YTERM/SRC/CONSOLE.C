/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �R���\�[������			*
 *		�t�@�C����	: console.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>
#include <string.h>
#include <jctype.h>
#include <jstring.h>
#include <conio.h>
#include <dos.h>
#include "rs232c.h"
#include "yterm.h"

struct key_buf key_data = {	/* �L�[�R�[�h */
	{
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0},
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}
	},
	{
		{0}, {0}, {KEY_INS}, {KEY_DEL},
		{KEY_UP}, {KEY_LEFT}, {KEY_RIGHT}, {KEY_DOWN},
		{KEY_HOME}, {KEY_HELP}, {KEY_HOME}
	}
};


/*=======================================================================
 |
 |		�E�C���h�D�I�[�v������
 |
 |	WINDOW	*w_open(sx, sy, nx, ny, title, color)
 |
 |		int	sx;		�J�n���ʒu
 |		int	sy;		�J�n�s�ʒu
 |		int	nx;		����
 |		int	ny;		�s��
 |		int	title;		�^�C�g��
 |		int	color;		�^�C�g�������F
 |
 |		�Ԓl			�E�B���h�E�|�C���^
 |
 =======================================================================*/
WINDOW	*w_open(int sx, int sy, int nx, int ny, char *title, int color)
{
	WINDOW	*wp;

	/* �E�B���h�E���[�N�G���A�m�� */
	if ((wp = w_alloc(nx, ny)) == NULL)
		return(NULL);

	/* �E�B���h�E�\�� */
	w_display(wp, sx, sy, title, color);

	return(wp);
}

/*=======================================================================
 |
 |		�E�C���h�D���[�N�G���A�m�ۏ���
 |
 |	WINDOW	*w_alloc(nx, ny)
 |
 |		int	nx;		����
 |		int	ny;		�s��
 |
 |		�Ԓl			�E�B���h�E�|�C���^
 |
 =======================================================================*/
WINDOW	*w_alloc(int nx, int ny)
{
	WINDOW *wp;

	/* �E�C���h�D�\���̃G���A�m�� */
	if ((wp = (WINDOW *)malloc(sizeof(WINDOW))) == NULL)
		return(NULL);

	/* �e�L�X�g�u�q�`�l�Z�[�u�G���A�m�� */
	if ((wp->tram = malloc(nx * ny * 2)) == NULL) {
		free(wp);
		return(NULL);
	}

	/* �A�g���r���[�g�Z�[�u�G���A�m�� */
	if ((wp->attr = malloc(nx * ny * 2)) == NULL) {
		free(wp->tram);
		free(wp);
		return(NULL);
	}

	/* �E�C���h�D���Z�[�u */
	wp->nx = nx;
	wp->ny = ny;

	return(wp);
}

/*=======================================================================
 |
 |		�E�C���h�D�\������
 |
 |	void	w_display(sx, sy, title, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	sx;		�J�n���ʒu
 |		int	sy;		�J�n�s�ʒu
 |		int	title;		�^�C�g��
 |		int	color;		�^�C�g�������F
 |
 =======================================================================*/
void	w_display(WINDOW *wp, int sx, int sy, char *title, int color)
{
	int	nx, ny, i, x, y;
	int	*tp, *ap;
	int	*vp, *cp;

	nx = wp->nx;
	ny = wp->ny;

	/* �ʒu�␳ */
	if (sx < 0)
		sx = 0;
	if (sy < 0)
		sy = 0;
	if (sx + nx > 80)
		sx = 80 - nx;
	if (sy + ny > 24)
		sy = 24 - ny;

	/* �E�C���h�D���Z�[�u */
	wp->sx = sx;
	wp->sy = sy;
	if (opt_m)
		color &= ~A_REVERSE;
	wp->color = color;

	/* �E�C���h�D�G���A�Z�[�u */
	tp = wp->tram;
	ap = wp->attr;
	FP_OFF(vp) = (wp->sy * 80 + wp->sx) * 2;
	for (i = 0; i < wp->ny; i++) {
		FP_SEG(vp) = SEG_TVRAM;
		memcpy(tp, vp, wp->nx * 2);
		tp += wp->nx;

		if ((*vp & 0xff00) != 0 && (*vp & 0x80) != 0)
			*(vp-1) = 0x20;

		cp = vp + wp->nx - 1;
		if ((*cp & 0xff00) != 0 && (*cp & 0x80) == 0)
			*(cp+1) = 0x20;

		FP_SEG(vp) = SEG_ATTR;
		memcpy(ap, vp, wp->nx * 2);
		ap += wp->nx;

		FP_OFF(vp) += 160;
	}

	/* �E�B���h�E�\�� */
	y = 0;
	w_loc(wp, 0, y++);
	cputs1("�", color);
	for (x = sx+2; x < sx+nx-2; x += 2)
		cputs1("�", color);
	cputs1("�", color);

	w_loc(wp, 0, y++);
	cputs1("�", color);
	for (x = sx+2; x < sx+nx-2; x += 2)
		cputs1("�", color);
	cputs1("�", color);

	w_loc(wp, 0, y++);
	cputs1("�", color);
	for (x = sx+2; x < sx+nx-2; x += 2)
		cputs1("��", color);
	cputs1("�", color);

	for (; y < ny-1; y++) {
		w_loc(wp, 0, y);
		cputs1("�", color);
		for (x = sx+2; x < sx+nx-2; x += 2)
			cputs1("  ", color);
		cputs1("�", color);
	}

	w_loc(wp, 0, y);
	cputs1("�", color);
	for (x = sx+2; x < sx+nx-2; x += 2)
		cputs1("�", color);
	cputs1("�", color);

	/* �^�C�g���\�� */
	w_loc(wp, ((nx - strlen(title)) / 2) & ~1, 1);
	putch1(' ', color);
	cputs1(title, color);
	putch1(' ', color);
}

/*=======================================================================
 |
 |		�E�C���h�D�N���[�Y����
 |
 |	void	w_close(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 =======================================================================*/
void	w_close(WINDOW *wp)
{
	w_erase(wp);		/* �E�B���h�E���� */
	w_free(wp);		/* �E�B���h�E���[�N�G���A��� */
}

/*=======================================================================
 |
 |		�E�C���h�D��������
 |
 |	void	w_erase(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 =======================================================================*/
void	w_erase(WINDOW *wp)
{
	int	i;
	int	*tp, *ap;
	int	*vp, *cp;

	/* �e�L�X�g�u�q�`�l�C�A�g���r���[�g���X�g�A */
	tp = wp->tram;
	ap = wp->attr;
	FP_OFF(vp) = (wp->sy * 80 + wp->sx) * 2;
	for (i = 0; i < wp->ny; i++) {
		FP_SEG(vp) = SEG_TVRAM;
		memcpy(vp, tp, wp->nx * 2);
		tp += wp->nx;

		if ((*vp & 0xff00) != 0 && (*vp & 0x80) != 0)
			*(vp-1) = *vp & 0xff7f;

		cp = vp + wp->nx - 1;
		if ((*cp & 0xff00) != 0 && (*cp & 0x80) == 0)
			*(cp+1) = *cp | 0x80;

		FP_SEG(vp) = SEG_ATTR;
		memcpy(vp, ap, wp->nx * 2);
		ap += wp->nx;

		FP_OFF(vp) += 160;
	}
}

/*=======================================================================
 |
 |		�E�C���h�D���[�N�G���A�������
 |
 |	void	w_free(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |
 =======================================================================*/
void	w_free(WINDOW *wp)
{
	free(wp->tram);
	free(wp->attr);
	free(wp);
}

/*=======================================================================
 |
 |		������\��
 |
 |	void	w_dspstr(wp, x, y, color, str)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	color;		�\���F
 |		char	*str;		�\���f�[�^
 |
 =======================================================================*/
void	w_dspstr(WINDOW *wp, int x, int y, int color, char *str)
{
	w_loc(wp, x, y);		/* �J�[�\���ʒu�Z�b�g */
	cputs1(str, color);		/* ������\�� */
}

/*=======================================================================
 |
 |		������\���i�����w��j
 |
 |	void	w_dspnstr(wp, x, y, color, str, len)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	color;		�\���F
 |		char	*str;		�\���f�[�^
 |		int	len;		����
 |
 =======================================================================*/
void	w_dspnstr(WINDOW *wp, int x, int y, int color, char *str, int len)
{
	w_loc(wp, x, y);		/* �J�[�\���ʒu�Z�b�g */
	while (len-- && *str != '\0')
		putch1(*str++, color);
}

/*=======================================================================
 |
 |		�X�y�[�X�\��
 |
 |	void	w_dspspc(wp, x, y, len)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	len;		����
 |
 =======================================================================*/
void	w_dspspc(WINDOW *wp, int x, int y, int len)
{
	w_loc(wp, x, y);		/* �J�[�\���ʒu�Z�b�g */
	while (len--)
		putch1(' ', wp->color);	/* �X�y�[�X�\�� */
}

/*=======================================================================
 |
 |		�ʒu�w�菑���t���\��
 |
 |	void	w_printf(wp, x, y, color, fmt, p, .....)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	color;		�\���F
 |		char	*fmt;		����
 |		...	p;		�\���f�[�^
 |
 =======================================================================*/
void	w_printf(WINDOW *wp, int x, int y, int color, char *fmt, char *p)
{
	char	s[82];

	vsprintf(s, fmt, (char *)&p);		/* �\���f�[�^�ҏW */
	w_dspstr(wp, x, y, color, s);		/* ������\�� */
}

/*=======================================================================
 |
 |		�P��������
 |
 |	int	inkey()
 |
 =======================================================================*/
int	inkey(void)
{
	int	ch;

	ch = 0;
	if (kbhit()) {
		ch = getch();		/* �P�����ǂݍ��� */
		if (ch == 0x06)		/* �t�@���N�V�����L�[ ? */
			ch = KEY_FUNC | getch();
	} else
		dsp_ctrl(0);		/* �R���g���[�����C���C�ʐM���ԕ\�� */

	return(ch);
}

/*=======================================================================
 |
 |		�P�������͑�
 |
 |	int	getchr()
 |
 =======================================================================*/
int	getchr(void)
{
	int	ch;

	while ((ch = inkey()) == 0)
		;

	return(ch);
}

/*=======================================================================
 |
 |		���������
 |
 |	int	w_inputs(wp, buf, x, y, len, flag, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		char	*buf;		���̓o�b�t�@
 |		int	x;		���͌��ʒu
 |		int	y;		���͍s�ʒu
 |		int	len;		���͕�����
 |		int	flag;	bit0�@0/1 = �����s�^��
 |				bit1  0/1 = �p�����^����
 |				bit4  0/1 = �o�b�t�@���������Ȃ��^����
 |				bit6  0/1 = �㉺���L�[�Ń��^�[�����Ȃ��^����
 |				bit7  0/1 = ���E���L�[�Ń��^�[�����Ȃ��^����
 |				bit8  0/1 = �m�t�k�k�Ȃ��^�t��
 |		int	color;		�\���F
 |
 =======================================================================*/
int	w_inputs(WINDOW *wp, char *buf, int x, int y, int len, int flag, int color)
{
	register int	i;		/* ���[�v�J�E���^ */
	register int	c;		/* ���͕��� */
	int	c2;			/* �����̂Q�o�C�g�� */
	int	count;			/* ���͕������J�E���^ */
	int	loop_sw;		/* ���[�v����X�C�b�` */
	int	ins_mode;		/* �}�����[�h�t���O */
	int	d_flag;

	ins_mode = 0;
	d_flag = 0;
	count = 0;

	/* �o�b�t�@������ */
	if (flag & 0x10) {
		memset(buf, ' ', len);
	}

	d_flag = 1;
	loop_sw = 1;
	while(loop_sw) {
 		if (d_flag) {
			/* ���̓f�[�^�\�� */
			w_loc(wp, x, y);
			for (i = 0; i < len; i++)
				putch1(buf[i], color);
			d_flag = 0;
		}

		w_loc(wp, x + count, y);	/* �J�[�\���ʒu�Z�b�g */

		t_loc(csr1_x, csr1_y);
		t_csron();		/* �J�[�\���\�� */
		c = getchr();		/* �L�[���� */
		t_csroff();		/* �J�[�\������ */

		if (iskanji(c))		/* �����R�[�h�H */
			c2 = getchr();	/* �����R�[�h�̂Q�o�C�g�ڎ��o�� */
		else
			c2 = 0;

		switch (c) {
		case KEY_CLR:
			memset(buf, ' ', len);
			d_flag = 1;
		case KEY_CR:
		case KEY_ESC:
		case KEY_HOME:
		case KEY_HELP:
		case KEY_TAB:
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
			loop_sw = 0;
			break;
		case KEY_UP:
		case KEY_DOWN:
			if (flag & 0x40)
				loop_sw = 0;
			break;
		case KEY_RIGHT:
			if (count+1 < len) {
				count++;
				/* �����R�[�h��������Q�o�C�g�i�߂� */
				if (nthctype(buf, count) == CT_KJ2)
					count++;
			} else if (flag & 0x80)
				loop_sw = 0;
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
				/* �����R�[�h��������Q�o�C�g�߂� */
				if (nthctype(buf, count) == CT_KJ2)
					count--;
			} else if (flag & 0x80)
				loop_sw = 0;
			break;
		case KEY_BS:
			if (count != 0) {
				count--;
				/* �����R�[�h��������Q�o�C�g�߂� */
				if (nthctype(buf, count) == CT_KJ2) {
					count--;
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
				d_flag = 1;
			}
			break;
		case KEY_INS:
			if (ins_mode == 0) {
				ins_mode = 1;
				/*csr_sl = 8;*/
			} else {
				ins_mode = 0;
				/*csr_sl = 0;*/
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
				  && c >= 0x20
				  && ((flag & 0x02) == 0 || isdigit(c))) {
				if (ins_mode) {
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

					buf[count++] = (char)c;
					if (c2) {
						buf[count++] = (char)c2;
					}
					d_flag = 1;
				} else {
					if (c2 == 0) {
						c2 = buf[count];
						putch1(c, color);
						buf[count++] = (char)c;
						if (count < len && iskanji(c2)) {
							putch1(' ', color);
							buf[count] = ' ';
						}
					} else {
						putch1(c, color);
						buf[count++] = (char)c;
						putch1(c2, color);
						buf[count++] = (char)c2;
					}
				}
			} else
				beep();
			break;
		}
	}

	/*csr_sl = 0;*/

	/* �m�t�k�k�t�� */
	if (flag & 0x100) {
		for (i = len; i > 0; i--) {
			if (buf[i-1] != ' ')
				break;
		}
		buf[i] = '\0';
	}

	return(c);
}

/*=======================================================================
 |
 |		�x����炷
 |
 |	void	beep()
 |
 =======================================================================*/
void	beep(void)
{
	putch(KEY_BELL);
}

/*=======================================================================
 |
 |		�E�B���h�E�����s����
 |
 |	int	w_newline(wp, y)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	y;		���݂̍s�ʒu
 |
 |		�Ԓl			���s��̍s�ʒu
 |
 =======================================================================*/
int	w_newline(WINDOW *wp, int y)
{
	int	i;
	int	*vp;

	if (y < wp->ny - 2)
		y++;
	else {
		for (i = 3; ; i++) {
			FP_OFF(vp) = ((wp->sy + i) * 80 + wp->sx + 2) * 2;
			if (i == y)
				break;
			FP_SEG(vp) = SEG_TVRAM;
			memcpy(vp, vp + 80, (wp->nx - 4) * 2);
			FP_SEG(vp) = SEG_ATTR;
			memcpy(vp, vp + 80, (wp->nx - 4) * 2);
		}

		for (i = 0; i < wp->nx - 4; i++) {
			FP_SEG(vp) = SEG_TVRAM;
			*vp = ' ';
			FP_SEG(vp) = SEG_ATTR;
			*vp = wp->color;
			vp++;
		}
	}
	return(y);
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
void	dsp_func(func)
char	*func[];
{
	static	char	func_key[10][3] = {
		{0x06,'1'}, {0x06,'2'}, {0x06,'3'}, {0x06,'4'}, {0x06,'5'},
		{0x06,'6'}, {0x06,'7'}, {0x06,'8'}, {0x06,'9'}, {0x06,'0'}
	};
	static	int	pos[] = {4, 11, 18, 25, 32, 42, 49, 56, 63, 70};
	static	char	spc[] = "      ";
	register int	i, j;

	for (i = 0; i < 10; i++) {
		w_loc(scrp, pos[i], 24);
		if (*func) {
			for (j = 0; j < 6; j++)
				putch1((*func)[j], C_WHITE|A_REVERSE);
			if (*func[0] == 0xfe)
				strcpy(key_data.fun[i], *func+6);
			else
				strcpy(key_data.fun[i], func_key[i]);
		} else {
			cputs1(spc, C_WHITE|A_REVERSE);
			key_data.fun[i][0] = '\0';
		}
		func++;
	}

	t_fput(&key_data);
}
/*=======================================================================
 |
 |		�E�B���h�E���J�[�\���ʒu�Z�b�g
 |
 |	void	w_loc(wp, x, y)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		���ʒu
 |		int	y;		�s�ʒu
 |
 =======================================================================*/
void	w_loc(WINDOW *wp, int x, int y)
{
	csr1_x = wp->sx + x;
	csr1_y = wp->sy + y;
}

/*=======================================================================
 |
 |		���ڑI������
 |
 |	int	w_select(wp, strp, n, sel, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		char	**strp;		���ڕ\���f�[�^
 |		int	n;		���ڐ�
 |		int	sel;		�����I������
 |		int	color;		�I��F
 |
 |		�Ԓl			�I�����ڔԍ�
 |
 =======================================================================*/
int	w_select(WINDOW *wp, char **strp, int n, int sel, int color)
{
	int	ch, loop_sw, i;

	for (i = 0; i < n; i++)
		w_dspstr(wp, 3, i+3, wp->color, strp[i]);

	loop_sw = 1;
	while(loop_sw) {
		w_color(wp, 2, sel+3, wp->nx-4, color);
		ch = getchr();
		w_color(wp, 2, sel+3, wp->nx-4, wp->color);
		switch (ch) {
		case KEY_CR:
			loop_sw = 0;
			break;
		case KEY_UP:
			if (sel == 0)
				sel = n;
			sel--;
			break;
		case KEY_DOWN:
			sel++;
			if (sel == n)
				sel = 0;
			break;
		case KEY_ESC:
		case KEY_F9:
			sel = -1;
			loop_sw = 0;
			break;
		}
	}

	return(sel);
}

/*=======================================================================
 |
 |		�����F�ύX�ݒ�
 |
 |	void	w_color(wp, x, y, len, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�|�C���^
 |		int	x;		���ʒu
 |		int	y;		�s�ʒu
 |		int	len;		����
 |		int	color;		�����F
 |
 =======================================================================*/
void	w_color(WINDOW *wp, int x, int y, int len, int color)
{
	char	*p;
	int	mask;

	x += wp->sx;
	y += wp->sy;

	if (opt_m) {
		color = (color & 0xe0) == (wp->color & 0xe0) ? 0 : A_REVERSE;
		mask = A_REVERSE;
	} else
		mask = C_WHITE;

	FP_SEG(p) = SEG_ATTR;
	FP_OFF(p) = (y * 80 + x) * 2;
	while (len--) {
		*p = (*p & ~mask) | (color & mask);
		p += 2;
	}
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
		w_dspstr(wp, scrdat->x, scrdat->y, wp->color, scrdat->data);
		scrdat++;
	}
}

/*=======================================================================
 |
 |		������\��
 |
 |	void	cputs1(str, color)
 |
 |		char	*str;		�\��������
 |		int	color;		�\���F
 |
 =======================================================================*/
void	cputs1(char *str, int color)
{
	while (*str)
		putch1(*str++, color);
}

/*=======================================================================
 |
 |		�P�����\��
 |
 |	void	putch1(c, color)
 |
 |		int	c;		�\������
 |		int	color;		�\���F
 |
 =======================================================================*/
void	putch1(int c, int color)
{
	static	int	kanji;
	int	*vp;
	union	{
		int	i;
		char	c[2];
	} kc1, kc2;

	if (opt_m)
		color |= C_WHITE;

	if (kanji == 0 && iskanji(c))
		kanji = c;
	else {
		FP_OFF(vp) = (csr1_y * 80 + csr1_x) * 2;
		if (kanji == 0) {
			FP_SEG(vp) = SEG_TVRAM;
			*vp = c;
			FP_SEG(vp) = SEG_ATTR;
			*vp = color;
		} else {
			kc1.c[0] = c;
			kc1.c[1] = kanji;
			kc1.i = jmstojis(kc1.i);
			kc2.c[0] = kc1.c[1] - 0x20;
			kc2.c[1] = kc1.c[0];
			FP_SEG(vp) = SEG_TVRAM;
			*vp = kc2.i;
			*(vp+1) = kc2.i | 0x80;
			FP_SEG(vp) = SEG_ATTR;
			*vp = color;
			*(vp+1) = color;
			csr1_x++;
			kanji = 0;
		}
		csr1_x++;
	}
}

/*=======================================================================
 |
 |		�ʐM��ʂP�����\��
 |
 |	void	putch2(c)
 |
 |		int	c;		�\������
 |
 =======================================================================*/
void	putch2(int c)
{
	static	int	kanji;
	int	*vp;
	char	*vbufp;
	int	sp;
	FILE	*save_fp;
	union	{
		int	i;
		char	c[2];
	} kc1, kc2;

	switch(c) {
	case KEY_CR:
		csr2_x = 0;
		break;
	case KEY_LF:
		csr2_x = 0;
		if (csr2_y == 22)
			scroll_up();
		else
			csr2_y++;
		if (vbufn < MAX_VBUF-1)
			vbufn++;
		vbufc = ++vbufc % MAX_VBUF;
		FP_OFF(vbufp) = 0;
		FP_SEG(vbufp) = SEG_GVRAM + vbufc * 5;
		memset(vbufp, 0, 80);
		break;
	case KEY_BS:
		if (csr2_x == 0) {
			if (csr2_y != 1) {
				csr2_x = 79;
				csr2_y--;
				vbufn--;
				vbufc = (vbufc + MAX_VBUF - 1) % MAX_VBUF;
			}
		} else
			csr2_x--;
		break;
	case KEY_TAB:
		sp = 8 - csr2_x % 8;
		save_fp = rcv_fp;
		rcv_fp = NULL;
		while (sp--)
			putch2(' ');
		rcv_fp = save_fp;
		break;
	case KEY_BELL:
		putch(c);
		break;
	case KEY_CLR:
		w_dspspc(scrp, 0, 1, 80*22);
		csr2_x = 0;
		csr2_y = 1;
		break;
	default:
		if (c < 0x20)
			break;
		if (kanji == 0 && iskanji(c))
			kanji = c;
		else {
			FP_OFF(vp) = (csr2_y * 80 + csr2_x) * 2;
			FP_SEG(vp) = SEG_TVRAM;
			FP_OFF(vbufp) = csr2_x;
			FP_SEG(vbufp) = SEG_GVRAM + vbufc * 5;
			if (kanji == 0) {
				*vp = c;
				*vbufp = c;
			} else {
				kc1.c[0] = c;
				kc1.c[1] = kanji;
				kc1.i = jmstojis(kc1.i);
				kc2.c[0] = kc1.c[1] - 0x20;
				kc2.c[1] = kc1.c[0];
				*vp++ = kc2.i;
				*vp = kc2.i | 0x80;
				*vbufp++ = kanji;
				*vbufp = c;
				csr2_x++;
				kanji = 0;
			}
		}
		if (kanji == 0 || csr2_x == 79) {
			if (++csr2_x == 80) {
				csr2_x = 0;
				if (csr2_y == 22)
					scroll_up();
				else
					csr2_y++;
				if (vbufn < MAX_VBUF-1)
					vbufn++;
				vbufc = ++vbufc % MAX_VBUF;
				FP_OFF(vbufp) = 0;
				FP_SEG(vbufp) = SEG_GVRAM + vbufc * 5;
				memset(vbufp, 0, 80);
			}
		}
		break;
	}

	t_loc(csr2_x, csr2_y);
	if (rcv_fp != NULL)
		fputc(c, rcv_fp);
}

/*=======================================================================
 |
 |		�ʐM��ʂP�����X�N���[���A�b�v����
 |
 |	void	scroll_up()
 |
 =======================================================================*/
void	scroll_up(void)
{
	int	*vp;
	int	i;

	FP_OFF(vp) = 160;
	FP_SEG(vp) = SEG_TVRAM;
	memcpy(vp, vp + 80, 160*21);
	FP_SEG(vp) = SEG_ATTR;
	memcpy(vp, vp + 80, 160*21);

	FP_OFF(vp) = 160 * 22;
	for (i = 0; i < 80; i++) {
		FP_SEG(vp) = SEG_TVRAM;
		*vp = ' ';
		FP_SEG(vp) = SEG_ATTR;
		*vp = C_WHITE;
		vp++;
	}
}

/*=======================================================================
 |
 |		�ʐM��ʂP�����X�N���[���_�E������
 |
 |	void	scroll_down()
 |
 =======================================================================*/
void	scroll_down(void)
{
	int	*vp;
	int	i;

	FP_OFF(vp) = 160;
	FP_SEG(vp) = SEG_TVRAM;
	memmove(vp + 80, vp, 160*21);
	FP_SEG(vp) = SEG_ATTR;
	memmove(vp + 80, vp, 160*21);

	FP_OFF(vp) = 160;
	for (i = 0; i < 80; i++) {
		FP_SEG(vp) = SEG_TVRAM;
		*vp = ' ';
		FP_SEG(vp) = SEG_ATTR;
		*vp = C_WHITE;
		vp++;
	}
}