/************************************************************************
 *									*
 *		�O���t�B�b�N�E�B���h�E���C�u����			*
 *									*
 *		����		: �w�b�_�t�@�C��			*
 *		�t�@�C����	: gwlib.h				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

/*	�L�[�R�[�h	*/
#define	KEY_HELP	0x01
#define	KEY_BS		0x08
#define	KEY_HOME	0x0b
#define	KEY_CLR		0x0c
#define	KEY_CR		0x0d
#define	KEY_ESC		0x1b
#define	KEY_RIGHT	0x1c
#define	KEY_LEFT	0x1d
#define	KEY_UP		0x1e
#define	KEY_DOWN	0x1f
#define	KEY_DEL		0x7f
#define	KEY_INS		0x11
#define	KEY_FUNC	0x0630
#define	KEY_F1		0x0630
#define	KEY_F2		0x0631
#define	KEY_F3		0x0632
#define	KEY_F4		0x0633
#define	KEY_F5		0x0634
#define	KEY_F6		0x0635
#define	KEY_F7		0x0636
#define	KEY_F8		0x0637
#define	KEY_F9		0x0638
#define	KEY_F10		0x0639
#define	MOUSE_R		0x0700
#define	MOUSE_L		0x0701

/*	�O���t�B�b�N�J���[�R�[�h	*/
#define	C_BLACK		0
#define	C_BLUE		1
#define	C_RED		2
#define	C_MAGENTA	3
#define	C_GREEN		4
#define	C_CYAN		5
#define	C_YELLOW	6
#define	C_WHITE		7

/*	�Z�O�����g�A�h���X	*/
#define	SEG_BVRAM	0xa800		/* �O���t�B�b�N�u�q�`�l�Z�O�����g */
#define	SEG_RVRAM	0xb000		/* �O���t�B�b�N�u�q�`�l�Z�O�����g */
#define	SEG_GVRAM	0xb800		/* �O���t�B�b�N�u�q�`�l�Z�O�����g */

/*	���^���|�[�g�A�h���X	*/
#define	PA_INTM1	0x00		/* �}�X�^���荞�݃R���g���[�� */
#define	PA_INTM2	0x02		/* �}�X�^���荞�݃}�X�N���W�X�^ */

/*	�^��`		*/
#ifndef	u_int
#define	u_int	unsigned int
#endif
#ifndef	u_char
#define	u_char	unsigned char
#endif

/*	�E�B���h�E�\����	*/
struct	window	{
	int	sx;			/* �J�n�w���W */
	int	sy;			/* �J�n�x���W */
	int	dx;			/* �P���̃h�b�g�� */
	int	dy;			/* �P�s�̃h�b�g�� */
	int	nx;			/* ���� */
	int	ny;			/* �s�� */
	int	xdot;			/* �w�����h�b�g�� */
	int	ydot;			/* �x�����h�b�g�� */
	int	shadow;			/* �e�����h�b�g�� */
	int	bcolor;			/* �E�B���h�E�w�i�F */
	int	flag;			/* �t���O */
	char	*bram;			/* �O���t�B�b�N�u�q�`�l�Z�[�u�G���A */
	char	*rram;			/* �O���t�B�b�N�u�q�`�l�Z�[�u�G���A */
	char	*gram;			/* �O���t�B�b�N�u�q�`�l�Z�[�u�G���A */
	struct	window	*chain;		/* �`�F�C���|�C���^ */
};
typedef	struct	window	WINDOW;

/*	�}�E�X���	*/
struct	mouse	{
	int	x;			/* �w���W */
	int	y;			/* �x���W */
	int	len;			/* ���� */
	int	code;			/* ���ʃR�[�h */
};
typedef	struct	mouse	MOUSE;

/*	���ڑI�����	*/
struct	select	{
	WINDOW	*wp;			/* �E�B���h�E�|�C���^ */
	MOUSE	*mp;			/* �}�E�X���|�C���^ */
	int	color;			/* �I�����J���[ */
	struct	select	*next;		/* �`�F�C���|�C���^ */
};
typedef	struct	select	SELECT;
	
/*	���C���\���e�[�u��		*/
struct	line	{
	int	flag;			/* �t���O�@=0:���@=1:�g  =-1:�I�� */
	int	pos_x1;			/* �J�n���ʒu */
	int	pos_y1;			/* �J�n�s�ʒu */
	int	pos_x2;			/* �I�����ʒu */
	int	pos_y2;			/* �I���s�ʒu */
};
typedef	struct	line	LINE;

/*	�t�@���N�V�����L�[�ݒ�f�[�^	*/
struct	key_buf {
	char	fun[20][16];		/* �t�@���N�V�����L�[ */
	char	cur[11][6];		/* ����L�[ */
};

/*	�Œ蕔��ʕ\���e�[�u��	*/
struct	scrdat	{
	int	pos_x;			/* �\�����ʒu */
	int	pos_y;			/* �\���s�ʒu */
	char	*data;			/* �\���f�[�^ */
};
typedef	struct scrdat SCRDAT;

/*	�f�[�^���̓p�����[�^	*/
struct	input	{
	char	*bufp;		/* ���̓o�b�t�@�|�C���^ */
	int	x;		/* ���ʒu */
	int	y;		/* �s�ʒu */
	int	len1;		/* �����������i�����^�̏ꍇ�͕������j */
	int	len2;		/* �����������i�����^�̏ꍇ�́|�P�Ƃ���j */
	int	flag;		/* ���̓t���O */
	int	cr;		/* �b�q�L�[ */
	int	up;		/* ����L�[ */
	int	down;		/* �����L�[ */
	int	left;		/* �����L�[ */
	int	right;		/* �E���L�[ */
};
typedef	struct	input	INPUT;

/*	�֐���`	*/

/*	clib.c	*/
extern	int	w_inputx(WINDOW *, INPUT *, int, MOUSE *, int);
extern	int	w_inputs(WINDOW *, char *, int, int, int, int);
extern	int	w_inputn(WINDOW *, char *, int, int, int, int, int);
extern	int	waitkey(WINDOW *);
extern	int	inkey(void);
extern	int	w_input(WINDOW *, int);
extern	void	m_reg(WINDOW *, MOUSE *, int);
extern	void	m_rel(MOUSE *);
extern	void	w_dspstr(WINDOW *, int, int, char *);
extern	void	w_dspnstr(WINDOW *, int, int, char *, int);
/*extern	void	w_xprintf(WINDOW *, int, int, char *, ...);*/
extern	void	w_xprintf();
extern	void	w_dspscr(WINDOW *, SCRDAT *);
extern	void	w_reverse(WINDOW *, int, int, int, int);
extern	void	w_dspline(WINDOW *, LINE *, int);
extern	void	w_loc(WINDOW *, int, int);
extern	void	csr_on(void);
extern	void	csr_off(void);
extern	void	beep(void);
extern	void	w_abort(char *);
extern	void	w_putch(WINDOW *, int);
extern	void	w_cputs(WINDOW *, char *);
extern	void	set_color(int, int);
extern	void	dspfunc(char **);
extern	void	far interrupt csr_dsp(void);
extern	void	csr_dsp1(void);

/*	wlib.c	*/
extern	int	w_select(WINDOW *, char **, int, int, int, int, int);
extern	void	w_stdwin(WINDOW *, char *, int, int, int);
extern	void	w_xmove(WINDOW *);
extern	void	w_move(WINDOW *, int, int);
extern	void	w_frame(int, int, int, int, int);
extern	void	w_initscr(int, int, int, char *);
extern	void	w_endscr(void);
extern	WINDOW	*w_open(int, int, int, int, int, int);
extern	void	w_close(WINDOW *);
extern	void	w_popup(WINDOW *);
extern	WINDOW	*w_check(void);
/*extern	int	w_line(WINDOW *, int, int, int, int, int, int, ...);*/
extern	int	w_line();
extern	void	w_scroll_up(WINDOW *);
extern	void	w_scroll_down(WINDOW *);

/*	tlib.c	*/
extern	void	t_loc(int, int);
extern	void	t_cls(void);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	t_setfont(int, char *);
extern	void	t_getfont(int, char *);
extern	void	t_kcgmode(int);

/*	glib.c	*/
extern	int	g_init(void);
extern	int	g_screen(int ,int, int, int);
extern	int	g_view(int, int, int, int, int, int);
extern	int	g_color(int, int, int, int);
extern	int	g_color2(int, int);
extern	int	g_cls(void);
extern	int	g_pset(int, int, int);
extern	int	g_preset(int, int);
/*extern	int	g_line(int, int, int, int, int, int, ...);*/
extern	int	g_line();
/*extern	int	g_circle(int, int, int, int, int, int, ...);*/
extern	int	g_circle();
extern	int	g_paint(int, int, int, int);
extern	int	g_paint2(int, int, int, unsigned char *, int);
extern	int	g_get(int, int, int, int, unsigned char *, int);
extern	int	g_put(int, int, unsigned char *, int, int, int, int, int);
extern	int	g_kanji(int, int, unsigned int, int, int, int, int);
extern	int	g_roll(int, int, int);
extern	int	g_point(int, int);

/*	mlib.c	*/
extern	int	m_init(void);
extern	void	m_csron(void);
extern	void	m_csroff(void);
extern	void	m_pos(int *, int *, int *, int *);
extern	void	m_loc(int, int);
extern	void	m_lon(int *, int *, int *, int *);
extern	void	m_loff(int *, int *, int *, int *);
extern	void	m_ron(int *, int *, int *, int *);
extern	void	m_roff(int *, int *, int *, int *);
extern	void	m_csr(int, int, char *);
extern	void	m_mov(int *, int *);
extern	void	m_step(int, int);
extern	void	m_area(int, int, int, int);
extern	void	m_color(int);

/*	�f�[�^��`	*/
extern	WINDOW	*scrp;		/* �S��ʃ|�C���^ */
extern	WINDOW	*csr_wp;	/* �J�[�\���̑��݂���E�B���h�E�|�C���^ */
extern	int	func_line;	/* �t�@���N�V�����L�[�\���s */
extern	int	csr_sx;		/* �J�[�\���w���W */
extern	int	csr_sy;		/* �J�[�\���x���W */
extern	int	csr_flag;	/* �J�[�\���\���t���O */
extern	int	g_fcolor;	/* �����F */
extern	int	g_bcolor;	/* �w�i�F */
extern	int	g_kcolor;	/* �������͍s�̐F */
extern	int	g_fkcolor;	/* �t�@���N�V�����L�[�I�����̐F */
extern	int	m_gx;		/* �}�E�X�w���W�i�O���t�B�b�N���W�j */
extern	int	m_gy;		/* �}�E�X�x���W�i�O���t�B�b�N���W�j */
extern	int	m_sel;		/* �}�E�X�I�����ڔԍ� */
extern	int	m_sel_color;	/* �I�����ڂ̔��]�F */
extern	int	m_csr_flag;	/* �}�E�X�J�[�\���\���t���O */
extern	char	ank_font[][16];	/* �`�m�j�t�H���g�o�b�t�@ */
extern	void	(far interrupt *m_intp)();/* �}�E�X���荞�݃Z�[�u�G���A */
extern	int	mouse_drv;	/* �}�E�X�h���C�o��� */
extern	struct key_buf key_data; /* �L�[�R�[�h */

/*	�l�r�|�b�̊֐����s�t�q�a�n�|�b�̊֐��ɒu�������� */
#ifdef	__TURBOC__
#define	_dos_getvect(x)		getvect(x)
#define	_dos_setvect(x,y)	setvect(x,y)
#define	_enable()		enable()
#define	_disable()		disable()
#endif