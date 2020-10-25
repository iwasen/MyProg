/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: ���ʃT�u���[�`��			*
 *		�t�@�C����	: csub.c				*
 *									*
 ************************************************************************/
#include "bumper.h"
#include <ctype.h>
#include <jctype.h>
#include <jstring.h>

static	char	key_buf[160], *key_bufp;
static	int	key_buf_len;

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
	t_loc(x, y);
	t_color(t_color_code(color));
	cputs(str);
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
	t_loc(x, y);

	t_color(t_color_code(color));
	while (len--)
		putch(*str++);
}

/*=======================================================================
 |
 |		�ʒu�w�菑���t���\��
 |
 |	void	xprintf(x, y, color, p1, p2, .....)
 |
 |		int	x;		�\�����ʒu
 |		int	y;		�\���s�ʒu
 |
 =======================================================================*/
void	xprintf(int x, int y, int color, char *fmt, char *p)
{
	char	s[82];

	vsprintf(s, fmt, (char *)&p);
	dspstr(x, y, s, color);
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
	t_color(t_color_code(color));

	while (scrdat->data) {
		t_loc(scrdat->pos_x, scrdat->pos_y);
		cputs(scrdat->data);
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
	t_loc(x, y);
	while (len--)
		putch(' ');
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
	while (line->flag != -1) {
		if (line->flag != 3) {
			g_line(line->pos_x1*8, line->pos_y1*16+8,
				line->pos_x2*8, line->pos_y2*16+8,
				color, line->flag, 0);
		} else {
			g_line(line->pos_x1*8, line->pos_y1*16+7,
				line->pos_x2*8, line->pos_y2*16+7,
				color, 0, 0);
			g_line(line->pos_x1*8, line->pos_y1*16+10,
				line->pos_x2*8, line->pos_y2*16+10,
				color, 0, 0);
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

	return(t_color_data[color]);
}

/*=======================================================================
 |
 |		���͕������J�E���g
 |
 |	int	strcount(p)
 |
 |		char	*p;		���͕�����
 |
 =======================================================================*/
int	strcount(char *p)
{
	int	i;

	for (i = strlen(p); i > 0; i--) {
		if (p[i-1] != ' ')
			break;
	}
	return(i);
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
	while (n--) {
		g_line(x*8-4, y*16, x*8+9, y*16+16, color, 1, 0);
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
	int	ch;
	union	REGS	reg;

	ch = 0;
	if (key_buf_len != 0) {
		ch = *key_bufp++;
		key_buf_len--;
	} else if (kbhit()) {
		ch = getch();
		if (ch == 0x06)		/* �t�@���N�V�����L�[ ? */
			ch = KEY_FUNC | getch();
		else if (ch == KEY_ESC || ch == KEY_TAB) {
			/* �V�t�g�L�[�`�F�b�N */
			reg.h.ah = 2;
			int86(0x18, &reg, &reg);
			if (reg.h.al & 0x01)
				ch |= KEY_SHIFT;
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

	while ((ch = inkey()) == 0)
		;

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
			t_loc(x, y);
			for (i = 0; i < len; i++) {
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
	if (flag & 0x100)
		buf[len] = '\0';

	return(c);
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
 *
 *		�t�@���N�V�����L�[�\��
 *
 *	void	dspfunc(func)
 *
 *		char	*func[];	�t�@���N�V�����L�[�\���f�[�^
 *
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

	t_color(A_WHITE|A_REVERSE|0x01);
	for (i = 0; i < 10; i++) {
		t_loc(pos[i], 24);
		if (*func) {
			for (j = 0; j < 6; j++)
				putch((*func)[j]);
			if (*func[0] == 0xfe)
				strcpy(key_data.fun[i], *func+6);
			else
				strcpy(key_data.fun[i], func_key[i]);
		} else {
			cputs(spc);
			key_data.fun[i][0] = '\0';
		}
		func++;
	}

	t_fput(&key_data);
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
void	*talloc(int size)
{
	void	*p;

	if ((p = malloc(size)) == NULL) {
		final();
		printf("memory allocation error\n");
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
void	*trealloc(void *p, int size)
{
	if ((p = realloc(p, size)) == NULL) {
		final();
		printf("�������̊m�ۂɎ��s���܂���\n");
		exit(1);
	}
	return(p);
}

int	kakunin(char *msg)
{
	int	loop_sw, yn;

	dspstr(2, 22, msg, C_WHITE);
	clrtoeol();
	t_csron();

	loop_sw = 1;
	while (loop_sw) {	
		switch (getchr()) {
		case 'Y':
		case 'y':
			putch('Y');
			yn = 1;
			loop_sw = 0;
			break;
		case 'N':
		case 'n':
			putch('N');
			yn = 0;
			loop_sw = 0;
			break;
		}
	}

	t_csroff();

	return(yn);
}

void	message(char *msg, int color)
{
	dspstr(2, 22, msg, color);
	clrtoeol();

	timset(150);
	while (tm_ovf == 0);
		;
}