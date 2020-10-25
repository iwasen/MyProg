/************************************************************************
 *									*
 *		�O���t�B�b�N�E�B���h�E���C�u����			*
 *									*
 *		����		: �E�B���h�E���쏈��			*
 *		�t�@�C����	: wlib.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>
#include "gwlib.h"

static	WINDOW	*w_alloc(int, int, int, int, int, int);
static	void	w_save(WINDOW *);
static	void	w_erase(WINDOW *, int);
static	WINDOW	*w_pick(WINDOW *);
static	int	w_pick_sub(WINDOW *, WINDOW *, char *);
static	void	w_restore(WINDOW *);
static	void	w_free(WINDOW *);
static	void	w_push(WINDOW *);
static	WINDOW	*w_pop(void);
static	void	read_font(char *);
static	void	w_change(WINDOW	*, WINDOW *, WINDOW *, char *, int, int, int, int);
static	WINDOW	*w_check_sub(int, int);

static	WINDOW	*wtop;
static	struct key_buf	func_save; /* �t�@���N�V�����L�[�f�[�^�Z�[�u�G���A */

/*=======================================================================
 |
 |		��ʏ�����
 |
 |	void	w_initscr(dx, dy, mouse_d, font_file)
 |
 |		int	dx;		�P���̃h�b�g���i�W�Œ�j
 |		int	dy;		�P�s�̃h�b�g��
 |		int	mouse_d;	�}�E�X�h���C�o���
 |		char	*font_file;	�`�m�j�t�H���g�t�@�C��
 |
 =======================================================================*/
void	w_initscr(int dx, int dy, int mouse_d, char *font_file)
{
	static	char	retkey_font[] = {	/* ���^�[���L�[�̋L�� */
		0x02, 0x02,
		0x00, 0x00, 0x00, 0x1f, 0x00, 0x11, 0x00, 0x11,
		0x06, 0x11, 0x0e, 0x11, 0x1a, 0x31, 0x33, 0xe1,
		0x60, 0x01, 0xc0, 0x01, 0x60, 0x03, 0x33, 0xfe,
		0x1a, 0x00, 0x0e, 0x00, 0x06, 0x00, 0x00, 0x00
	};

	if (m_init() == 0) {		/* �}�E�X������ */
		printf("�}�E�X�h���C�o���g�ݍ��܂�Ă��܂���.\n");
		exit(0);
	}
	t_fget(&func_save);	/* �t�@���N�V�����L�[���x�����o�� */
	t_fput(&key_data);	/* �t�@���N�V�����L�[���x���ݒ� */

	read_font(font_file);	/* �`�m�j�t�H���g�t�@�C���ǂݍ��� */

	mouse_drv = mouse_d;	/* �}�E�X�h���C�o��ʃZ�b�g */

	g_init();		/* �O���t�B�b�N������ */
	g_screen(3, 0, 0, 1);	/* �X�N���[�����[�h�ݒ� */
	g_cls();		/* �O���t�B�b�N��ʃN���A */
	t_cls();		/* �e�L�X�g��ʃN���A */
	t_csroff();		/* �e�L�X�g�J�[�\������ */
	printf("\x1b[>1h");	/* �ŉ��ʍs���[�U�g�p */

	t_kcgmode(1);		/* �r�b�g�}�b�v�A�N�Z�X */

	/* �}�E�X���荞�݃x�N�^���o�� */
	m_intp = _dos_getvect(0x15);

	/* �x�N�^������������ */
	_dos_setvect(0x15, csr_dsp);

	/* �E�B���h�E���Z�b�g */
	scrp->sx = 0;
	scrp->sy = 0;
	scrp->nx = 640 / dx;
	scrp->ny = 400 / dy;
	scrp->dx = dx;
	scrp->dy = dy;
	scrp->xdot = 640;
	scrp->ydot = 400;

	/* �}�E�X�J�[�\���ʒu�ݒ�i��ʒ����j */
	m_gx = 320;
	m_gy = 200;
	m_loc(m_gx, m_gy);

	t_setfont(0x7621, retkey_font);
}

/*=======================================================================
 |
 |		��ʏI������
 |
 |	void	w_endscr()
 |
 =======================================================================*/
void	w_endscr(void)
{
	m_csroff();

	t_kcgmode(0);		/* �R�[�h�A�N�Z�X */

	/* �x�N�^�����ɖ߂� */
	_dos_setvect(0x15, m_intp);

	t_fput(&func_save);	/* �t�@���N�V�����L�[���x���ݒ� */
	g_screen(3, 0, 0, 1);	/* �X�N���[�����[�h�ݒ� */
	g_cls();		/* �O���t�B�b�N��ʃN���A */
	t_cls();		/* �e�L�X�g��ʃN���A */
	t_csron();		/* �e�L�X�g�J�[�\���\�� */
	printf("\x1b[>1l");	/* �ŉ��ʍs�V�X�e���g�p */
}

/*=======================================================================
 |
 |		�E�B���h�E�I�[�v������
 |
 |	WINDOW	*w_open(sx, sy, nx, ny, dx, dy)
 |
 |		int	sx;		�J�n�w���W
 |		int	sy;		�J�n�x���W
 |		int	nx;		����
 |		int	ny;		�s��
 |		int	dx;		�P���̃h�b�g���i�W�Œ�j
 |		int	dy;		�P�s�̃h�b�g��
 |
 |		�Ԓl			�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
WINDOW	*w_open(int sx, int sy, int nx, int ny, int dx, int dy)
{
	WINDOW	*wp;

	sx &= ~7;

	/* �ʒu�␳ */
	if (sx < 0)
		sx = 0;
	if (sy < 0)
		sy = 0;
	if (sx + nx * dx > 640)
		sx = 640 - nx * dx;
	if (sy + ny * dy > 400)
		sy = 400 - ny * dy;

	/* �E�B���h�E�G���A�m�� */
	if ((wp = w_alloc(sx, sy, nx, ny, dx, dy)) == 0)
		return(0);

	/* �`�F�C���o�^���� */
	w_push(wp);

	/* �E�B���h�E�G���A�Z�[�u */
	w_save(wp);

	return(wp);
}

/*=======================================================================
 |
 |		�E�B���h�E��ƃG���A�m��
 |
 |	WINDOW	*w_alloc(sx, sy, nx, ny, dx, dy)
 |
 |		int	sx;		�J�n���ʒu
 |		int	sy;		�J�n�s�ʒu
 |		int	nx;		����
 |		int	ny;		�s��
 |		int	dx;		�P���̃h�b�g���i�W�Œ�j
 |		int	dy;		�P�s�̃h�b�g��
 |
 |		�Ԓl			�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	WINDOW	*w_alloc(int sx, int sy, int nx, int ny, int dx, int dy)
{
	WINDOW	*wp;
	int	size;

	/* �E�B���h�E�\���̃G���A�m�� */
	if ((wp = (WINDOW *)malloc(sizeof(WINDOW))) == 0)
		return(0);
	memset(wp, 0, sizeof(WINDOW));

	/* �E�B���h�E���Z�[�u */
	wp->sx = sx;
	wp->sy = sy;
	wp->nx = nx;
	wp->ny = ny;
	wp->dx = dx;
	wp->dy = dy;
	wp->xdot = nx * dx;
	wp->ydot = ny * dy;

	/* �O���t�B�b�N�u�q�`�l�Z�[�u�G���A�m�� */
	size = dy * ny * nx;
	if ((wp->bram = malloc(size)) == 0) {
		w_free(wp);
		return(0);
	}
	if ((wp->rram = malloc(size)) == 0) {
		w_free(wp);
		return(0);
	}
	if ((wp->gram = malloc(size)) == 0) {
		w_free(wp);
		return(0);
	}

	return(wp);
}

/*=======================================================================
 |
 |		�E�B���h�E�G���A�Z�[�u����
 |
 |	void	w_save(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	void	w_save(WINDOW *wp)
{
	register int	i;
	char	*bp, *rp, *gp;
	char	*vp;

	/* �O���t�B�b�N�u�q�`�l�Z�[�u */
	bp = wp->bram;
	rp = wp->rram;
	gp = wp->gram;
	FP_OFF(vp) = wp->sy * 80 + wp->sx / 8;
	for (i = wp->ydot; i != 0; i--) {
		FP_SEG(vp) = SEG_BVRAM;
		memcpy(bp, vp, wp->nx);
		bp += wp->nx;

		FP_SEG(vp) = SEG_RVRAM;
		memcpy(rp, vp, wp->nx);
		rp += wp->nx;

		FP_SEG(vp) = SEG_GVRAM;
		memcpy(gp, vp, wp->nx);
		gp += wp->nx;

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		�E�B���h�E�N���A����
 |
 |	void	w_erase(wp, color)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |		int	color;		�w�i�F
 |
 =======================================================================*/
static	void	w_erase(WINDOW *wp, int color)
{
	/* �O���t�B�b�N�u�q�`�l�N���A */
	g_line(wp->sx, wp->sy, wp->sx+wp->xdot-1, wp->sy+wp->ydot-1
								, color, 2, 0);
}

/*=======================================================================
 |
 |		�E�B���h�E�N���[�Y����
 |
 |	void	w_close(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
void	w_close(WINDOW *wp)
{
	WINDOW	*cp;

	/* �E�B���h�E���� */
	if (wp == wtop) {
		w_pop();
		w_restore(wp);
		w_free(wp);
	} else if ((cp = w_pick(wp)) != 0) {
		w_restore(wp);
		w_free(cp);
		w_free(wp);
	}
}

/*=======================================================================
 |
 |		�W���^�E�B���h�E�쐬����
 |
 |	void	w_stdwin(wp, title, tcolor, gcolor, scolor)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |		char	*title;		�^�C�g��
 |		int	tcolor;		�^�C�g�������F
 |		int	bcolor;		�w�i�F
 |		int	scolor;		�e�F
 |
 =======================================================================*/
void	w_stdwin(WINDOW *wp, char *title, int tcolor, int bcolor, int scolor)
{
	int	sx, sy, dy, ex, ey, by;
	char	tile[6];

	/* �E�B���h�E�����o�� */
	sx = wp->sx;
	sy = wp->sy;
	dy = wp->dy;
	ex = sx + wp->xdot - 1;
	ey = sy + wp->ydot - 1;

	/* �^�C���p�^�[���Z�b�g */
	if (bcolor & C_BLUE)
		tile[0] = 0xff;
	else
		tile[0] = 0x00;

	if (bcolor & C_RED)
		tile[1] = 0xff;
	else
		tile[1] = 0x00;

	if (bcolor & C_GREEN)
		tile[2] = 0xff;
	else
		tile[2] = 0x00;

	if (scolor & C_BLUE)
		tile[3] = 0xff;
	else
		tile[3] = 0x00;

	if (scolor & C_RED)
		tile[4] = 0xff;
	else
		tile[4] = 0x00;

	if (scolor & C_GREEN)
		tile[5] = 0xff;
	else
		tile[5] = 0x00;

	/* �E�B���h�E�\���g */
	g_line(sx, sy, ex-8, ey-8, bcolor, 2, 0);
	g_line(sx+8, ey-7, ex, ey, scolor, 2, 0);
	g_line(ex-7, sy+8, ex, ey, scolor, 2, 0);
	by = sy + (dy - 16) / 2 + 1;
	g_line(sx+3, by, ex-11, by+13, bcolor, 2, 2, 0, 6, tile);
	wp->shadow = 8;

	/* �^�C�g���\�� */
	w_loc(wp, (wp->nx - strlen(title)) / 2 - 1, 0);
	set_color(tcolor, bcolor);
	w_putch(wp, ' ');
	w_cputs(wp, title);
	w_putch(wp, ' ');

	g_line(sx, sy, ex-8, ey-8, scolor, 1, 0);
	g_line(sx+3, sy+dy-1, ex-11, ey-11, scolor, 1, 0);

	wp->bcolor = bcolor;	/* �w�i�F�Z�[�u */
}

/*=======================================================================
 |
 |		���ڑI������
 |
 |	int	w_select(wp, strp, nx, ny, sel, fcolor, scolor)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |		char	**strp;		���ڕ\���f�[�^
 |		int	nx;		���������ڐ�
 |		int	ny;		�c�������ڐ�
 |		int	sel;		�����I������
 |		int	fcolor;		�����F
 |		int	scolor;		�I�����ڐF
 |
 |		�Ԓl			�I�����ڔԍ�
 |
 =======================================================================*/
int	w_select(WINDOW *wp, char **strp, int nx, int ny, int sel, int fcolor
	, int scolor)
{
	register int	x, y;
	int	tx, ty, len, i, loop_sw, ch;
	MOUSE	*mp, *wmp;

	/* ���[�N�G���A�m�� */
	if ((mp = malloc(sizeof(MOUSE) * (nx*ny+1))) == 0)
		return(-1);

	w_popup(wp);

	/* �����F�ݒ� */
	set_color(fcolor, wp->bcolor);

	m_csroff();		/* �}�E�X�J�[�\���n�e�e */

	/* �}�E�X�I�����ڃf�[�^�쐬 */
	len = (wp->nx - 3) / nx;
	wmp = mp;
	i = 0;
	for (x = 0; x < nx; x++) {
		for (y = 0; y < ny; y++) {
			tx = x * len + 1;
			ty = y + 1;
			w_dspstr(wp, tx+1, ty, *strp++);
			wmp->x = tx;
			wmp->y = ty;
			wmp->len = len;
			wmp->code = 0;
			wmp++;
			if (i++ == sel)
				m_loc(wp->sx + (x * len + len * 3 / 4) * wp->dx
				,wp->sy + (y + 1) * wp->dy + wp->dy / 2);
		}
	}
	wmp->len = 0;

	/* �}�E�X�I�����ړo�^ */
	m_reg(wp, mp, scolor);

	/* ���͏��� */
	loop_sw = 1;
	while(loop_sw) {
		ch = w_input(wp, 0);
		if (m_sel != -1) {
			x = m_sel / ny;
			y = m_sel % ny;
		}
		if (ch >= KEY_F1 && ch <= KEY_F10) {
			sel = ch;
			break;
		}
		switch (ch) {
		case MOUSE_R:
		case MOUSE_L:
		case KEY_CR:
			sel = m_sel;
			loop_sw = 0;
			break;
		case KEY_UP:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (y == 0)
				y = ny - 1;
			else
				y--;
		csr_set:
			m_gx = wp->sx + (x * len + len * 3 / 4) * wp->dx;
			m_gy = wp->sy + (y + 1) * wp->dy + wp->dy / 2;
			m_loc(m_gx, m_gy);
			break;
		case KEY_DOWN:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (y == ny - 1)
				y = 0;
			else
				y++;
			goto csr_set;
		case KEY_RIGHT:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (x == nx - 1)
				x = 0;
			else
				x++;
			goto csr_set;
		case KEY_LEFT:
			if (m_sel == -1) {
				x = 0;
				y = 0;
			} else if (x == 0)
				x = nx - 1;
			else
				x--;
			goto csr_set;
		case KEY_ESC:
			sel = -1;
			loop_sw = 0;
			break;
		}
	}

	/* �}�E�X�I�����ډ��� */
	m_rel(mp);

	/* ���[�N�G���A��� */
	free(mp);

	return(sel);
}

/*=======================================================================
 |
 |		�E�B���h�E�ړ�����
 |
 |	void	w_xmove(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
void	w_xmove(WINDOW *wp)
{
	int	plsw, prsw;
	int	w_gx, w_gy;
	int	dx, dy;

	dx = m_gx - wp->sx;
	dy = m_gy - wp->sy;

	m_area(dx, dy, 640 - wp->xdot + dx, 400 - wp->ydot + dy);

	w_gx = m_gx;
	w_gy = m_gy;
	m_csroff();
	w_frame(wp->sx, wp->sy, wp->xdot, wp->ydot, C_MAGENTA);
	w_frame(wp->sx, wp->sy, wp->xdot, wp->ydot, C_WHITE);
	m_csron();
	for (;;) {
		m_pos(&m_gx, &m_gy, &plsw, &prsw);
		if (plsw == 0)
			break;
		if (m_gx != w_gx || m_gy != w_gy) {
			m_csroff();
			w_frame(w_gx-dx, w_gy-dy, wp->xdot, wp->ydot, C_WHITE);
			w_frame(m_gx-dx, m_gy-dy, wp->xdot, wp->ydot, C_WHITE);
			m_csron();
			w_gx = m_gx;
			w_gy = m_gy;
		}
	}
	m_csroff();
	w_frame(w_gx-dx, w_gy-dy, wp->xdot, wp->ydot, C_WHITE);
	w_frame(wp->sx, wp->sy, wp->xdot, wp->ydot, C_MAGENTA);
	m_area(0, 0, 639, 399);

	if (wp == csr_wp) {
		csr_sx += (w_gx - dx - wp->sx) & ~7;
		csr_sy += w_gy - dy - wp->sy;
	}

	w_move(wp, w_gx - dx, w_gy - dy);

	m_csron();
}

/*=======================================================================
 |
 |		�E�B���h�E�ړ�����
 |
 |	void	w_move(wp, x, y)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |		int	x;		�ړ���w���W
 |		int	y;		�ړ���x���W
 |
 =======================================================================*/
void	w_move(WINDOW *wp, int x, int y)
{
	WINDOW	*cp;

	if ((cp = w_pick(wp)) != 0) {
		w_restore(wp);
		wp->sx = cp->sx = x & ~7;
		wp->sy = cp->sy = y;
		w_save(wp);
		w_restore(cp);
		w_push(wp);
		w_free(cp);
	}
}

/*=======================================================================
 |
 |		�E�B���h�E�g�\������
 |
 |	void	w_frame(sx, sy, dx, dy, color)
 |
 |		int	sx;		�J�n���ʒu
 |		int	sy;		�J�n�s�ʒu
 |		int	dx;		����
 |		int	dy;		�s��
 |		int	color;		�g�F
 |
 =======================================================================*/
void	w_frame(int sx, int sy, int dx, int dy, int color)
{
	char	far	*vp;
	register int	i, j;
	static	int	col_tbl[3][2] = {
		{C_BLUE,  SEG_BVRAM},
		{C_RED,   SEG_RVRAM},
		{C_GREEN, SEG_GVRAM}
	};

	for (j = 0; j < 3; j++) {
		if (color & col_tbl[j][0]) {
			FP_SEG(vp) = col_tbl[j][1];
			for (i = 0; i < dx / 8; i++) {
				FP_OFF(vp) = sy * 80 + sx / 8 + i;
				*vp ^= 0xff;
				FP_OFF(vp) = (sy + dy) * 80 + sx / 8 + i;
				*vp ^= 0xff;
			}
			for (i = 0; i < dy; i++) {
				FP_OFF(vp) = (sy + i) * 80 + sx / 8;
				*vp ^= 0x80;
				FP_OFF(vp) = (sy + i) * 80 + (sx + dx) / 8 - 1;
				*vp ^= 0x01;
			}
		}
	}
}

/*=======================================================================
 |
 |		�E�B���h�E�|�b�v�A�b�v����
 |
 |	void	w_popup(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
void	w_popup(WINDOW *wp)
{
	WINDOW	*cp;

	if (wp != wtop) {
		if ((cp = w_pick(wp)) != 0) {
			w_restore(cp);
			w_free(cp);
			w_push(wp);
		}
	}
}

/*=======================================================================
 |
 |		�E�B���h�E�s�b�N�A�b�v����
 |
 |	WINDOW	*w_pick(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 |		�Ԓl			�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	WINDOW	*w_pick(WINDOW *wp)
{
	WINDOW	*cp;
	char	*mp;
	int	size, st;

	/* ���[�N�G���A�m�� */
	size = wp->ydot * wp->nx;
	if ((mp = malloc(size)) == 0)
		return(0);
	memset(mp, 0, size);

	/* ���E�B���h�E�Z�[�u */
	if ((cp = w_alloc(wp->sx, wp->sy, wp->nx, wp->ny, wp->dx, wp->dy)) == 0) {
		free(mp);
		return(0);
	}
	cp->shadow = wp->shadow;
	w_save(cp);

	/* �s�b�N�A�b�v���� */
	st = w_pick_sub(wp, cp, mp);

	/* ���[�N�G���A��� */
	free(mp);

	/* ���[�N�E�B���h�E��� */
	if (st == 0) {
		w_free(cp);
		return(0);
	}

	return(cp);
}

static	int	w_pick_sub(WINDOW *wp, WINDOW *cp, char *mp)
{
	int	sx1, sy1, ex1, ey1, sx2, sy2, ex2, ey2, sd1, sd2;
	int	sx, sy, ex, ey;
	WINDOW	*tp;

	tp = w_pop();
	if (tp == NULL)
		return(0);

	if (tp != wp) {
		if (w_pick_sub(wp, cp, mp) == 0) {
			w_push(tp);
			return(0);
		}

		sx1 = wp->sx;
		sy1 = wp->sy;
		ex1 = sx1 + wp->xdot;
		ey1 = sy1 + wp->ydot;
		sd1 = wp->shadow;

		sx2 = tp->sx;
		sy2 = tp->sy;
		ex2 = sx2 + tp->xdot;
		ey2 = sy2 + tp->ydot;
		sd2 = tp->shadow;

		sx = max(sx1, sx2);
		sy = max(sy1, sy2);
		ex = min(ex1 - sd1, ex2 - sd2);
		ey = min(ey1 - sd1, ey2 - sd2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1, ex2 - sd2);
		sy = max(sy1, sy2 + sd2);
		ex = min(ex1 - sd1, ex2);
		ey = min(ey1 - sd1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1, sx2 + sd2);
		sy = max(sy1, ey2 - sd2);
		ex = min(ex1 - sd1, ex2);
		ey = min(ey1 - sd1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(ex1 - sd1, sx2);
		sy = max(sy1 + sd1, sy2);
		ex = min(ex1, ex2 - sd2);
		ey = min(ey1, ey2 - sd2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(ex1 - sd1, ex2 - sd2);
		sy = max(sy1 + sd1, sy2 + sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(ex1 - sd1, sx2 + sd2);
		sy = max(sy1 + sd1, ey2 - sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1 + sd1, sx2);
		sy = max(ey1 - sd1, sy2);
		ex = min(ex1, ex2 - sd2);
		ey = min(ey1, ey2 - sd2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1 + sd1, ex2 - sd2);
		sy = max(ey1 - sd1, sy2 + sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		sx = max(sx1 + sd1, sx2 + sd2);
		sy = max(ey1 - sd1, ey2 - sd2);
		ex = min(ex1, ex2);
		ey = min(ey1, ey2);
		w_change(wp, cp, tp, mp, sx, sy, ex, ey);

		w_push(tp);
	}
	return(1);
}

static	void	w_change(WINDOW *wp, WINDOW *cp, WINDOW *tp, char *mp, int sx
		, int sy, int ex, int ey)
{
	int	x, y;
	register int	p1, p2;
	char	c;

	sx /= 8;
	ex /= 8;
	for (y = sy; y < ey; y++) {
		for (x = sx; x < ex; x++) {
			p1 = (y - wp->sy) * wp->nx + (x - wp->sx / 8);
			if (mp[p1] == 0) {
				p2 = (y - tp->sy) * tp->nx + (x - tp->sx / 8);
				c = tp->bram[p2];
				tp->bram[p2] = wp->bram[p1];
				wp->bram[p1] = cp->bram[p1];
				cp->bram[p1] = c;
				c = tp->rram[p2];
				tp->rram[p2] = wp->rram[p1];
				wp->rram[p1] = cp->rram[p1];
				cp->rram[p1] = c;
				c = tp->gram[p2];
				tp->gram[p2] = wp->gram[p1];
				wp->gram[p1] = cp->gram[p1];
				cp->gram[p1] = c;
				mp[p1] = 1;
			}
		}
	}
}

/*=======================================================================
 |
 |		�E�B���h�E���X�g�A����
 |
 |	void	w_restore(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	void	w_restore(WINDOW *wp)
{
	register int	i;
	int	len, sdx, sdy;
	char	*bp, *rp, *gp;
	char	*vp;

	/* �O���t�B�b�N�u�q�`�l���X�g�A */
	bp = wp->bram;
	rp = wp->rram;
	gp = wp->gram;
	sdy = wp->shadow;
	sdx = sdy / 8;
	len = wp->nx - sdx;
	FP_OFF(vp) = wp->sy * 80 + wp->sx / 8;
	for (i = wp->ydot; i != 0; i--) {
		if (i == wp->ydot - sdy)
			len += sdx;
		else if (i == sdy) {
			len -= sdx;
			FP_OFF(vp) += sdx;
			bp += sdx;
			rp += sdx;
			gp += sdx;
		}

		FP_SEG(vp) = SEG_BVRAM;
		memcpy(vp, bp, len);
		bp += wp->nx;

		FP_SEG(vp) = SEG_RVRAM;
		memcpy(vp, rp, len);
		rp += wp->nx;

		FP_SEG(vp) = SEG_GVRAM;
		memcpy(vp, gp, len);
		gp += wp->nx;

		FP_OFF(vp) += 80;
	}
}

/*=======================================================================
 |
 |		�E�B���h�E��ƃG���A�������
 |
 |	void	w_free(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	void	w_free(WINDOW *wp)
{
	if (wp != 0) {
		/* �u�q�`�l�Z�[�u�G���A��� */
		if (wp->bram != 0)
			free(wp->bram);
		if (wp->rram != 0)
			free(wp->rram);
		if (wp->gram != 0)
			free(wp->gram);

		/* �E�B���h�E�\���̉�� */
		free(wp);
	}
}

/*=======================================================================
 |
 |		�E�B���h�E�`�F�C���o�^����
 |
 |	void	w_push(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	void	w_push(WINDOW *wp)
{
	wp->chain = wtop;
	wtop = wp;
}

/*=======================================================================
 |
 |		�E�B���h�E�`�F�C�����O������
 |
 |	WINDOW	*w_pop()
 |
 |		�Ԓl			�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
static	WINDOW	*w_pop(void)
{
	WINDOW	*wp;

	if (wtop == NULL)
		return(NULL);

	wp = wtop;
	wtop = wp->chain;
	return(wp);
}

/*=======================================================================
 |
 |		�}�E�X�J�[�\�����ǂ̃E�B���h�E���ɂ��邩�`�F�b�N
 |
 |	WINDOW	*w_check()
 |
 |		�Ԓl			�E�B���h�E�\���̂ւ̃|�C���^
 |					�i�O�F�ǂ̃E�B���h�E���ɂ��Ȃ��j
 |
 =======================================================================*/
WINDOW	*w_check(void)
{
	return(w_check_sub(m_gx, m_gy));
}

static	WINDOW	*w_check_sub(int x, int y)
{
	WINDOW	*wp;

	for (wp = wtop; wp != 0; wp = wp->chain) {
		if (x >= wp->sx && x < wp->sx + wp->xdot
				&& y >= wp->sy && y < wp->sy + wp->ydot)
			return(wp);
	}
	return(0);
}


/*=======================================================================
 |
 |		g_line�̃E�B���h�E�Ή�
 |
 |	int	w_line(wp,x1,y1,x2,y2,pal1,dc,lsw,lstyle,lentile,ptile)
 |
 =======================================================================*/
int	w_line(WINDOW *wp, int x1, int y1, int x2, int y2, int pal1, int dc
	, int lsw, int lstyle, int lentile, char *ptile)
{
	return(g_line(x1+wp->sx,y1+wp->sy,x2+wp->sx,y2+wp->sy,pal1,dc,lsw,lstyle,lentile,ptile));
}

/*=======================================================================
 |
 |		�X�N���[���A�b�v
 |
 |	void	w_scroll_up(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
void	w_scroll_up(WINDOW *wp)
{
	char	*src, *dst;
	int	byte, i;

	/* �u�q�`�l�A�h���X�Z�b�g */
	FP_OFF(dst) = (wp->sy+wp->dy) * 80 + (wp->sx+wp->dx) / 8;
	FP_OFF(src) = FP_OFF(dst) + wp->dy * 80;

	byte = wp->nx - 3;

	/* �P�s�X�N���[�� */
	for (i = wp->ydot - wp->dy*3; i > 0; i--) {
		FP_SEG(src) = FP_SEG(dst) = SEG_BVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_RVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_GVRAM;
		memcpy(dst, src, byte);

		FP_OFF(src) += 80;
		FP_OFF(dst) += 80;
	}

	/* �ŉ��ʍs�N���A */
	for (i = wp->dy; i > 0; i--) {
		FP_SEG(dst) = SEG_BVRAM;
		memset(dst, (wp->bcolor & C_BLUE) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_RVRAM;
		memset(dst, (wp->bcolor & C_RED) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_GVRAM;
		memset(dst, (wp->bcolor & C_GREEN) ? 0xff : 0, byte);

		FP_OFF(dst) += 80;
	}
}

/*=======================================================================
 |
 |		�X�N���[���_�E��
 |
 |	void	w_scroll_down(wp)
 |
 |		WINDOW	*wp;		�E�B���h�E�\���̂ւ̃|�C���^
 |
 =======================================================================*/
void	w_scroll_down(WINDOW *wp)
{
	char	*src, *dst;
	int	byte, i;

	/* �u�q�`�l�A�h���X�Z�b�g */
	FP_OFF(dst) = (wp->sy+wp->dy*(wp->ny-1)) * 80 + (wp->sx+wp->dx) / 8;
	FP_OFF(src) = FP_OFF(dst) - wp->dy * 80;

	byte = wp->nx - 3;

	/* �P�s�X�N���[�� */
	for (i = wp->ydot - wp->dy*3; i > 0; i--) {
		FP_SEG(src) = FP_SEG(dst) = SEG_BVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_RVRAM;
		memcpy(dst, src, byte);
		FP_SEG(src) = FP_SEG(dst) = SEG_GVRAM;
		memcpy(dst, src, byte);

		FP_OFF(src) -= 80;
		FP_OFF(dst) -= 80;
	}

	/* �ŏ�ʍs�N���A */
	for (i = wp->dy; i > 0; i--) {
		FP_SEG(dst) = SEG_BVRAM;
		memset(dst, (wp->bcolor & C_BLUE) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_RVRAM;
		memset(dst, (wp->bcolor & C_RED) ? 0xff : 0, byte);
		FP_SEG(dst) = SEG_GVRAM;
		memset(dst, (wp->bcolor & C_GREEN) ? 0xff : 0, byte);

		FP_OFF(dst) -= 80;
	}
}

/*=======================================================================
 |
 |		�`�m�j�t�H���g�t�@�C���ǂݍ���
 |
 |	void	read_font(font_file)
 |
 |		char	*font_file;	�t�H���g�t�@�C����
 |
 =======================================================================*/
static	void	read_font(char *font_file)
{
	FILE	*fp;
	register int	i;
	char	fbuf[34];

	if (font_file) {
		/* �t�H���g�t�@�C���w�肠�� */
		if ((fp = fopen(font_file, "rb")) == 0)
			return;
		fread(ank_font, 16, 256, fp);
		fclose(fp);
	} else {
		/* �t�H���g�t�@�C���w��Ȃ� */
		for (i = 0; i < 256; i++) {
			t_getfont(i+0x8000, fbuf);
			memcpy(ank_font[i], fbuf+2, 16);
		}
	}
}