/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �R���\�[�����o�̓T�u���[�`��		*
 *		�t�@�C����	: console.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"
#include <ctype.h>
#include <jctype.h>
#include <jstring.h>

/*	�X�^�e�B�b�N�ϐ�	*/
static	char	key_buf[160], *key_bufp;
static	int	key_buf_len;
static	MOUSE	*mouse_dat;
static	int	mouse_n;
static	int	mouse_sel;
static	int	mouse_lsw;
static	int	mouse_rsw;

/*	�����֐�	*/
static	void	lputs(char *);

/*=======================================================================
 |
 |		������\��
 |
 |	void	dspstr(x, y, str, color)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		char	*str;		�\���f�[�^
 |		int	color;		�\���F
 |
 =======================================================================*/
void	dspstr(int x, int y, char *str, int color)
{
	t_loc(x, y);			/* �J�[�\���ʒu�Z�b�g */
	t_color(t_color_code(color));	/* �\���F�Z�b�g */
	cputs(str);			/* ������\�� */
}

/*=======================================================================
 |
 |		������\���i�܂�Ԃ�����j
 |
 |	void	dspstr2(x, y, len, str, color)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	len;		�P�s�̕�����
 |		char	*str;		�\���f�[�^
 |		int	color;		�\���F
 |
 =======================================================================*/
int	dspstr2(int x, int y, int len, char *str, int color)
{
	int	x_count;	/* ���ʒu�J�E���^ */
	int	y_count;	/* �s�ʒu�J�E���^ */
	char	*sp;

	t_color(t_color_code(color));	/* �\���F�Z�b�g */

	x_count = len;
	y_count = 0;
	for (sp = str; *sp != '\0'; sp++) {
		if (x_count == len) {
			t_loc(x, y++);
			x_count = 0;
			y_count++;
		}
		putch(*sp);
		x_count++;
	}

	return(y_count);
}

/*=======================================================================
 |
 |		������\���i�����w��j
 |
 |	void	dspnstr(x, y, str, len, color)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		char	*str;		�\���f�[�^
 |		int	len;		����
 |		int	color;		�\���F
 |
 =======================================================================*/
void	dspnstr(int x, int y, char *str, int len, int color)
{
	t_loc(x, y);			/* �J�[�\���ʒu�Z�b�g */

	t_color(t_color_code(color));	/* �\���F�Z�b�g */
	while (len--)
		putch(*str++);
}

/*=======================================================================
 |
 |		�ʒu�w�菑���t���\��
 |
 |	void	xprintf(x, y, color, fmt, p, .....)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		char	*fmt;		����
 |		...	p;		�\���f�[�^
 |
 =======================================================================*/
void	xprintf(int x, int y, int color, char *fmt, char *p)
{
	char	s[82];

	vsprintf(s, fmt, (char *)&p);	/* �\���f�[�^�ҏW */
	dspstr(x, y, s, color);		/* ������\�� */
}

/*=======================================================================
 |
 |		�Œ蕔��ʕ\��
 |
 |	void	dspscr(scrdat, color)
 |
 |		SCRDAT	*scrdat;	�Œ蕔��ʃf�[�^
 |		int	color;		�F���
 |
 =======================================================================*/
void	dspscr(SCRDAT *scrdat, int color)
{
	t_color(t_color_code(color));	/* �\���F�Z�b�g */

	while (scrdat->data) {
		t_loc(scrdat->pos_x, scrdat->pos_y);	/* �J�[�\���ʒu�Z�b�g*/
		cputs(scrdat->data);	/* ������\�� */
		scrdat++;
	}
}

/*=======================================================================
 |
 |		�X�y�[�X�\��
 |
 |	void	dspspc(x, y, len)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	len;		����
 |
 =======================================================================*/
void	dspspc(int x, int y, int len)
{
	t_loc(x, y);		/* �J�[�\���ʒu�Z�b�g */
	while (len--)
		putch(' ');	/* �X�y�[�X�\�� */
}

/*=======================================================================
 |
 |		�J�[�\���ʒu���s���܂ŃN���A
 |
 |	void	clrtoeol()
 |
 =======================================================================*/
void	clrtoeol()
{
	cputs("\x1b[0K");
}

/*=======================================================================
 |
 |		�r���\��
 |
 |	void	dspline(line, color)
 |
 |		LINE	*line;		���C���f�[�^
 |		int	color;		�F���
 |
 =======================================================================*/
void	dspline(LINE *line, int color)
{
	int	col;

	col = g_color_code(color);	/* �J���[�R�[�h���o�� */

	while (line->flag != -1) {
		if (line->flag != 3) {
			/* ���C���C�{�b�N�X */
			g_line(line->pos_x1*8, line->pos_y1*16+8,
				line->pos_x2*8, line->pos_y2*16+8,
				col, line->flag, 0);
		} else {
			/* ��d�� */
			g_line(line->pos_x1*8, line->pos_y1*16+7,
				line->pos_x2*8, line->pos_y2*16+7,
				col, 0, 0);
			g_line(line->pos_x1*8, line->pos_y1*16+10,
				line->pos_x2*8, line->pos_y2*16+10,
				col, 0, 0);
		}
		line++;
	}
}

/*=======================================================================
 |
 |		�e�L�X�g��ʐF�R�[�h���o��
 |
 |	int	t_color_code(color)
 |
 |		int	color;		�F���
 |
 =======================================================================*/
int	t_color_code(int color)
{
	static	int	t_color_data[8] = {
		0x01, 0x21, 0x41, 0x61,
		0x81, 0xa1, 0xc1, 0xe1
	};

	return(t_color_data[color & 0x100 ? ((int *)&csg.col)[color & 0xff] : color]);
}

/*=======================================================================
 |
 |		�O���t�B�b�N��ʐF�R�[�h���o��
 |
 |	int	g_color_code(color)
 |
 |		int	color;		�F���
 |
 =======================================================================*/
int	g_color_code(int color)
{
	return(color & 0x100 ? ((int *)&csg.col)[color & 0xff] : color);
}

/*=======================================================================
 |
 |		���̓t�B�[���h�\��
 |
 |	void	dsp_field(x, y, n, color)
 |		int	x;		���ʒu
 |		int	y;		�s�ʒu
 |		int	n;		����
 |		int	color;		�F���
 |
 =======================================================================*/
void	dsp_field(int x, int y, int n, int color)
{
	int	col;		/* �J���[�R�[�h */

	col = g_color_code(color);	/* �J���[�R�[�h���o�� */

	while (n--) {
		g_line(x*8-4, y*16, x*8+9, y*16+16, col, 1, 0);	/* �{�b�N�X */
		x += 2;
	}
}

/*=======================================================================
 |
 |		�P��������
 |
 |	int	inkey()
 |
 =======================================================================*/
int	inkey()
{
	int	ch, i;
	union	REGS	reg;
	int	m_x, m_y, m_lsw, m_rsw;
	MOUSE	*mp;

	ch = 0;
	if (key_buf_len != 0) {
		/* �L�[�o�b�t�@����P�������o�� */
		ch = *key_bufp++;
		key_buf_len--;
	} else if (kbhit()) {
		ch = getch();		/* �P�����ǂݍ��� */
		if (ch == 0x06)		/* �t�@���N�V�����L�[ ? */
			ch = KEY_FUNC | getch();
		else if (ch == KEY_ESC || ch == KEY_TAB) {
			/* �V�t�g�L�[�`�F�b�N */
			reg.h.ah = 2;
			int86(0x18, &reg, &reg);
			if (reg.h.al & 0x01)
				ch |= KEY_SHIFT;
		}
	} else if (mouse && mouse_n) {
		/* �}�E�X�J�[�\���ʒu���o�� */
		m_pos(&m_x, &m_y, &m_lsw, &m_rsw);
		if (m_lsw != mouse_lsw) {
			if (m_lsw != 0 && mouse_sel != -1)
				ch = mouse_dat[mouse_sel].l_code;
			mouse_lsw = m_lsw;
		} else if (m_rsw != mouse_rsw) {
			if (m_rsw != 0 && mouse_sel != -1)
				ch = mouse_dat[mouse_sel].r_code;
			mouse_rsw = m_rsw;
		} else {
			m_x /= 8;
			m_y /= 16;
			for (mp = mouse_dat, i = 0; i < mouse_n; i++, mp++) {
				if (mp->x <= m_x && mp->x+mp->len > m_x
							&& mp->y == m_y)
					break;
			}
			if (i == mouse_n)
				i = -1;
			if (i != mouse_sel && i != -1)
				ch = mouse_dat[i].s_code;
			mouse_sel = i;
		}
	}

	return(ch);
}

/*=======================================================================
 |
 |		�P�������͑�
 |
 |	int	getchr()
 |
 =======================================================================*/
int	getchr()
{
	int	ch;
	long	stime, ctime;

	if (mouse)
		m_csron();		/* �}�E�X�J�[�\���\�� */

	time(&stime);
	while ((ch = inkey()) == 0) {
		if (online && csg.opt.cmd_to != 0) {
			/* �R�}���h���̓^�C���A�E�g�`�F�b�N */
			time(&ctime);
			if (ctime - stime > csg.opt.cmd_to * 60) {
				ch = KEY_ESC;
				break;
			}
		}
	}

	if (mouse)
		m_csroff();		/* �}�E�X�J�[�\������ */

	return(ch);
}

/*=======================================================================
 |
 |		�L�[�o�b�t�@�Z�b�g
 |
 |	void	set_keybuf(p)
 |
 |		char	*p;		�L�[�o�b�t�@�ɃZ�b�g����f�[�^
 |
 =======================================================================*/
void	set_keybuf(char *p)
{
	key_buf_len = strlen(p);
	memcpy(key_buf, p, key_buf_len);
	key_bufp = key_buf;
}

/*=======================================================================
 |
 |		���������
 |
 |	int	inputs(buf, x, y, len, flag, color)
 |
 |		char	*buf;		���̓o�b�t�@
 |		int	x;		���͌��ʒu
 |		int	y;		���͍s�ʒu
 |		int	len;		���͕�����
 |		int	flag;	bit0�@0/1 = �����s�^��
 |				bit1  0/1 = �p�����^����
 |				bit2  0/1 = �ʏ�^�p�X���[�h
 |				bit3  0/1 = �P�O�i�^�P�U�i�i�������͂̏ꍇ�j
 |				bit4  0/1 = �o�b�t�@���������Ȃ��^����
 |				bit6  0/1 = �㉺���L�[�Ń��^�[�����Ȃ��^����
 |				bit7  0/1 = ���E���L�[�Ń��^�[�����Ȃ��^����
 |				bit8  0/1 = �m�t�k�k�Ȃ��^�t��
 |		int	color;		�F���
 |
 =======================================================================*/
int	inputs(char *buf, int x, int y, int len, int flag, int color)
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

	t_color(t_color_code(color));

	/* �o�b�t�@������ */
	if (flag & 0x10) {
		memset(buf, ' ', len);
	}

	d_flag = 1;
	loop_sw = 1;
	while(loop_sw) {
		if (count == len && (flag & 0x200))
			count--;

 		if (d_flag) {
			/* ���̓f�[�^�\�� */
			t_loc(x, y);
			for (i = 0; i < len; i++) {
				if ((flag & 0x04) && buf[i] != ' ')
					putch('*');
				else
					putch(buf[i]);
				if (flag & 0x200)
					putch(' ');
			}
			d_flag = 0;
		}

		if (flag & 0x200)
			t_loc(x + count*2, y);	/* �J�[�\���ʒu�Z�b�g */
		else
			t_loc(x + count, y);	/* �J�[�\���ʒu�Z�b�g */

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
		case KEY_SFT_ESC:
		case KEY_HOME:
		case KEY_HELP:
		case KEY_TAB:
		case KEY_SFT_TAB:
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
			if ((c & 0xff00) == KEY_MOUSE) {
				loop_sw = 0;
				break;
			}
			/* �������͕s�Ŋ��������͂��ꂽ��G���[ */
			if ((flag & 0x01) == 0 && c2 != 0) {
				beep();
				break;
			}

			/* �p�������͂̏ꍇ�̕����`�F�b�N */
			if (count < (c2 ? len-1 : len)
				  && ((flag & 0x01) != 0 || !iscntrl(c & 0x7f))
				  && ((flag & 0x02) == 0 ||
					(flag & 0x08 ? isxdigit(c)
							: isdigit(c)))) {
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

					buf[count++] = (char)c;
					if (c2) {
						buf[count++] = (char)c2;
					}
					d_flag = 1;
				} else {
					if (flag & 0x04)
						putch('*');
					else
						putch(c);
					buf[count++] = (char)c;
					if (c2) {
						putch(c2);
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
	if (flag & 0x100)
		buf[len] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		��������́i�����s�j
 |
 |	int	inputm(buf, x, y, len, nx, color)
 |
 |		char	*buf;		���̓o�b�t�@
 |		int	x;		���͌��ʒu
 |		int	y;		���͍s�ʒu
 |		int	len;		���͕�����
 |		int	nx;		�P�s�̕�����
 |		int	color;		�F���
 |
 =======================================================================*/
int	inputm(char *buf, int x, int y, int len, int nx, int color)
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

	t_color(t_color_code(color));

	/* �o�b�t�@������ */
	memset(buf, ' ', len);

	d_flag = 1;
	loop_sw = 1;
	while(loop_sw) {
 		if (d_flag) {
			for (i = 0; i < len; i++) {
				if (i % nx == 0)
					t_loc(x, y + i / nx);
				putch(buf[i]);
			}
			d_flag = 0;
		}

		t_loc(x + count % nx, y + count / nx);/* �J�[�\���ʒu�Z�b�g */

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
		case KEY_SFT_ESC:
		case KEY_HOME:
		case KEY_HELP:
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
		case KEY_RIGHT:
			if (count+1 < len) {
				count++;
				/* �����R�[�h��������Q�o�C�g�i�߂� */
				if (nthctype(buf, count) == CT_KJ2)
					count++;
			}
			break;
		case KEY_LEFT:
			if (count != 0) {
				count--;
				/* �����R�[�h��������Q�o�C�g�߂� */
				if (nthctype(buf, count) == CT_KJ2)
					count--;
			}
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
			if ((c & 0xff00) == KEY_MOUSE) {
				loop_sw = 0;
				break;
			}

			/* �p�������͂̏ꍇ�̕����`�F�b�N */
			if (count < (c2 ? len-1 : len)) {
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

					buf[count++] = (char)c;
					if (c2) {
						buf[count++] = (char)c2;
					}
					d_flag = 1;
				} else {
					putch(c);
					buf[count++] = (char)c;
					if (c2) {
						putch(c2);
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
	buf[len] = '\0';

	return(c);
}

/*=======================================================================
 |
 |		�}�E�X���ݒ�
 |
 |	int	m_reg(mp, n)
 |
 |		MOUSE	*mp		�}�E�X���
 |		int	n		�}�E�X���f�[�^��
 |
 =======================================================================*/
void	m_reg(MOUSE *mp, int n)
{
	mouse_dat = mp;
	mouse_n = n;
	mouse_sel = -1;
}

/*=======================================================================
 |
 |		�A�g���r���[�g�ݒ�
 |
 |	void	attrib(x, y, len, mask, attr)
 |
 |		int	x;		���ʒu
 |		int	y;		�s�ʒu
 |		int	len;		����
 |		int	mask;		�}�X�N�f�[�^
 |		int	attr;		�A�g���r���[�g
 |
 =======================================================================*/
void	attrib(int x, int y, int len, int mask, int attr)
{
	char	far	*p;

	FP_SEG(p) = SEG_ATTR;
	FP_OFF(p) = (y * 80 + x) * 2;
	while (len--) {
		*p = (*p & ~mask) | (attr & mask);
		p += 2;
	}
}

/*=======================================================================
 |
 |		�G��ʕ\��
 |
 |	void	dspgrf(grf_data)
 |
 |		char	*grf_data;	�O���t�B�b�N�f�[�^
 |
 =======================================================================*/
void	dspgrf(char *grf_data)
{
	int	i;
	char	far	*vp;

	FP_OFF(vp) = (4 * 16) * 80 + 4;
	for (i = 0; i < 16 * 13; i++) {
		FP_SEG(vp) = 0xa800;
		memcpy(vp, grf_data, 34);
		grf_data += 34;

		FP_SEG(vp) = 0xb000;
		memcpy(vp, grf_data, 34);
		grf_data += 34;

		FP_SEG(vp) = 0xb800;
		memcpy(vp, grf_data, 34);
		grf_data += 34;

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		�t�@���N�V�����L�[�ݒ�
 |
 |	void	setfunc(func_sw)
 |
 |		int	func_sw;	�t�@���N�V�����L�[�X�C�b�`
 |
 =======================================================================*/
void	setfunc(int func_sw)
{
	static	char	func_key[10][3] = {
		{0x06,'1'}, {0x06,'2'}, {0x06,'3'}, {0x06,'4'}, {0x06,'5'},
		{0x06,'6'}, {0x06,'7'}, {0x06,'8'}, {0x06,'9'}, {0x06,'0'}
	};
	register int	i;

	for (i = 0; i < 10; i++) {
		if (func_sw & 0x01)
			strcpy(key_data.fun[i], func_key[i]);
		else
			key_data.fun[i][0] = '\0';
		func_sw >>= 1;
	}

	t_fput(&key_data);
}

/*=======================================================================
 |
 |		���b�Z�[�W�o�͊��\��
 |
 |	void	dsp_msgenv()
 |
 =======================================================================*/
void	dsp_msgenv()
{
	dspstr(2, 23, msg40, C_CRM);
	if (ssg.anm.dsp)
		attrib(31, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.anm.prn)
		attrib(35, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.anm.fil == 1)
		attrib(38, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.anm.fil == 2)
		attrib(43, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.crm.dsp)
		attrib(58, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.crm.prn)
		attrib(62, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.crm.fil == 1)
		attrib(66, 23, 3, A_REVERSE, A_REVERSE);
	if (ssg.crm.fil == 2)
		attrib(70, 23, 3, A_REVERSE, A_REVERSE);
}

/*=======================================================================
 |
 |		�G��ʂɂP�����\��
 |
 |	void	grfputs(x, y, str, color, grfbuf)
 |
 |		int	x;		���ʒu
 |		int	y;		�s�ʒu
 |		char	*str;		������
 |		int	color;		�����F
 |		char	*grfbuf;	�G��ʃf�[�^�o�b�t�@
 |
 =======================================================================*/
void	grfputs(int x, int y, char *str, int color, char *grfbuf)
{
	static	void	d_kanji(int, int, int, int, char *);
	u_char	c, sc;
	union	{
		int	i;
		u_char	c[2];
	} kanji;

	sc = 0;
	while (c = *str++) {
		if (sc) {
			/* �����R�[�h�̂Q�o�C�g�� */
			kanji.c[0] = c;
			kanji.c[1] = sc;
			d_kanji(x, y, jmstojis(kanji.i), color, grfbuf);
			x += 4;	/* �J�[�\���ʒu�X�V */
			sc = 0;
		} else {
			if (iskanji(c)) {
				/* �����R�[�h */
				sc = c;	/* �����R�[�h�P�o�C�g�ڃZ�[�u */
			} else {
				/* �`�m�j�R�[�h */
				d_kanji(x, y, c | 0x8000, color, grfbuf);
				x += 2;	/* �J�[�\���ʒu�X�V */
			}
		}
	}
}

/*=======================================================================
 |
 |		�G��ʂɊ����\��
 |
 |	void	d_kanji(x, y, code, color, grfbuf)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |		int	code;		�����R�[�h�i�i�h�r�R�[�h�j
 |		int	color;		�����F
 |		char	*grfbuf;	�G��ʃf�[�^�o�b�t�@
 |
 =======================================================================*/
static	void	d_kanji(int x, int y, int code, int color, char *grfbuf)
{
	struct	font	{
		char	y;
		char	x;
		u_char	f[32];
	} font;
	int	i, j, k;
	u_int	*vp, f2;
	u_char	*fontp;
	u_int	f;

	t_getfont(code, (char *)&font);	/* �t�H���g�p�^�[���ǂݍ��� */
	vp = (int *)(grfbuf + y * 34 * 3 * 16 + x);	/* �\���A�h���X�v�Z */
	fontp = font.f;
	for (i = 0; i < font.y*8; i++) {
		for (j = 0; j < font.x; j++) {
			f = *fontp++;
			for (k = 0; k < 8; k++) {
				f2 <<= 2;
				if (f & 0x80)
					f2 |= 0x07;
				f <<= 1;
			}
			f2 = (f2 >> 8) | (f2 << 8);
			vp[j] = (color & C_BLUE) ? f2 : 0;
			vp[j+17] = (color & C_RED) ? f2 : 0;
			vp[j+34] = (color & C_GREEN) ? f2 : 0;
		}
		vp += 17 * 3;
	}
}

/*=======================================================================
 |
 |		�v�����^�[�o�͏���
 |
 |	void	lprintf(fmt, p)
 |
 |		char	fmt;		����
 |		char	*p;		�v�����g�f�[�^
 |
 =======================================================================*/
void	lprintf(char *fmt, char *p)
{
	union	{
		int	i;
		u_char	c[2];
	} kanji;
	static	int	kanji_mode = 0;
	char	s[84], *sp;

	vsprintf(s, fmt, (char *)&p);

	sp = s;
	while (*sp) {
		if (iskanji(*sp)) {
			if (!kanji_mode) {
				switch (csg.opt.printer) {
				case 0:		/* �m�d�b�@�o�b�|�o�q�Q�O�P */
				case 1:         /* �m�d�b�@�o�b�|�o�q�U�O�Q */
				case 2:         /* �m�d�b�@�m�l�V���[�Y */
					lputs("\x1c\x42\x1b\x4b");
					break;
				case 3:         /* �d�o�r�n�m */
					lputs("\x1c\x26\x1c\x53\x03\x09");
					break;
				}
				kanji_mode = 1;
			}
			kanji.c[1] = *sp++;
			kanji.c[0] = *sp++;
			kanji.i = jmstojis(kanji.i);
			lputc(kanji.c[1]);
			lputc(kanji.c[0]);
		} else {
			if (kanji_mode) {
				switch (csg.opt.printer) {
				case 0:		/* �m�d�b�@�o�b�|�o�q�Q�O�P */
				case 1:         /* �m�d�b�@�o�b�|�o�q�U�O�Q */
				case 2:         /* �m�d�b�@�m�l�V���[�Y */
					lputs("\x1b\x48\x1c\x41");
					break;
				case 3:         /* �d�o�r�n�m */
					lputs("\x1c\x2e");
					break;
				}
				kanji_mode = 0;
			}
			lputc(*sp++);
		}
	}
}

/*=======================================================================
 |
 |		�n�[�h�R�s�[����
 |
 |	void	hcopy(sx, sy, nx, ny)
 |
 |		int	sx;		�J�n��
 |		int	sy;		�J�n�s
 |		int	nx;		����
 |		int	ny;		�s��
 |
 =======================================================================*/
void	hcopy(int sx, int sy, int nx, int ny)
{
	char	buf[80 * 16];
	register int	i, j;
	int	x, y;
	char	code[2];
	char	font[40];
	char	*p;
	char	data;
	char	*vp;
	char	ctrl[8];
	static	char	bit1[8] = {0x80, 0x40, 0x20, 0x10, 8, 4, 2, 1};
	static	char	bit2[4] = { 2, 8, 0x20, 0x80};

	lputs("\x1c\x42");		/*�v�����^�[���Z�b�g*/
	lputs("\x1bT12\x0d\x0a");	/*�P�Q�^�P�Q�O�C���`�ݒ�*/

	sprintf(ctrl, "\x1bI%04d", nx*16);

	for (y = sy; y < sy + ny; y++){
		/* �O���t�B�b�N�u�q�`�l�P�s�ǂݍ��� */
		p = buf;
		for (i = 0; i < 16; i++) {
			FP_OFF(vp) = (y * 16 + i) * 80 + sx;
			for (j = 0; j < nx; j++) {
				FP_SEG(vp) = 0xa800;
				*p = *vp;
				FP_SEG(vp) = 0xb000;
				*p |= *vp;
				FP_SEG(vp) = 0xb800;
				*p++ |= *vp++;
			}
		}

		for (x = 0; x < nx; x++) {
			/*�e�L�X�g�u�q�`�l�P�s�f�d�s*/
			FP_SEG(vp) = 0xa000;
			FP_OFF(vp) = (y * 80 + x + sx) * 2;
			*(int *)code = *(int *)vp;
			if (code[1]) {
				t_getfont((code[0]+0x20)*256 + code[1], font);
				for (i = 0, p = font+2; i < 16; i++) {
					buf[x+i*nx] |= *p++;
					buf[x+i*nx+1] |= *p++;
				}
				x++;
			} else {
				t_getfont(code[0] + 0x8000, font);
				for (i = 0, p = font+2; i < 16; i++) {
					buf[x+i*nx] |= *p++;
				}
			}
		}

		lputs(ctrl);

		for (x = 0; x < nx * 8; x++) {
			/*�u�q�`�l�PBYTE��-�16��ݏo��*/
			data = 0;
			for (i = 0; i < 4; i++) {
				if (buf[x/8+i*nx] & bit1[x % 8])
					data |= bit2[i];
			}
			lputc(data);
			data = 0;
			for (i = 4; i < 8; i++) {
				if (buf[x/8+i*nx] & bit1[x % 8])
					data |= bit2[i-4];
			}
			lputc(data);
			lputc(0);
			lputc(0);
		}
		lputs("\x0d\x0a");

		lputs(ctrl);
		for (x = 0; x < nx * 8; x++) {
			data = 0;
			for (i = 0; i < 4; i++) {
				if (buf[x/8+(8+i)*nx] & bit1[x % 8])
					data |= bit2[i];
			}
			lputc(data);
			data = 0;
			for (i = 4; i < 8; i++) {
				if (buf[x/8+(8+i)*nx] & bit1[x % 8])
					data |= bit2[i-4];
			}
			lputc(data);
			lputc(0);
			lputc(0);
		}
		lputs("\x0d\x0a");
	}

	lputs("\x1b\A\x0d\x0a");	/*�v�����^�[�������*/
}
static	void	lputs(s)
char	*s;
{
	while (*s)
		lputc(*s++);
}