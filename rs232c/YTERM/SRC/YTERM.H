/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �w�b�_�t�@�C��			*
 *		�t�@�C����	: term.h				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

/*	�L�[�R�[�h	*/
#define	KEY_FUNC	0x600
#define	KEY_HELP	0x01
#define	KEY_BELL	0x07
#define	KEY_BS		0x08
#define	KEY_TAB		0x09
#define	KEY_LF		0x0a
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
#define	KEY_F1		(KEY_FUNC|'1')
#define	KEY_F2		(KEY_FUNC|'2')
#define	KEY_F3		(KEY_FUNC|'3')
#define	KEY_F4		(KEY_FUNC|'4')
#define	KEY_F5		(KEY_FUNC|'5')
#define	KEY_F6		(KEY_FUNC|'6')
#define	KEY_F7		(KEY_FUNC|'7')
#define	KEY_F8		(KEY_FUNC|'8')
#define	KEY_F9		(KEY_FUNC|'9')
#define	KEY_F10		(KEY_FUNC|'0')

/*	�Z�O�����g�A�h���X	*/
#define	SEG_TVRAM	0xa000		/* �e�L�X�g�u�q�`�l�Z�O�����g */
#define	SEG_ATTR	0xa200		/* �A�g���r���[�g�Z�O�����g */
#define	SEG_GVRAM	0xa800		/* �O���t�B�b�N�u�q�`�l�Z�O�����g */

/*	�A�g���r���[�g		*/
#define	A_BLINK		0x02	/* �u�����N */
#define	A_REVERSE	0x04	/* ���o�[�X */
#define	A_ULINE		0x08	/* �A���_�[���C�� */

/*	�����F	*/
#define	C_BLACK		0x01	/* �� */
#define	C_BLUE		0x21	/* �� */
#define	C_RED		0x41	/* �� */
#define	C_MAGENTA	0x61	/* �� */
#define	C_GREEN		0x81	/* �� */
#define	C_CYAN		0xa1	/* ���F */
#define	C_YELLOW	0xc1	/* ���F */
#define	C_WHITE		0xe1	/* �� */

/*	���]�����F	*/
#define	RC_BLACK	0x05	/* �� */
#define	RC_BLUE		0x25	/* �� */
#define	RC_RED		0x45	/* �� */
#define	RC_MAGENTA	0x65	/* �� */
#define	RC_GREEN	0x85	/* �� */
#define	RC_CYAN		0xa5	/* ���F */
#define	RC_YELLOW	0xc5	/* ���F */
#define	RC_WHITE	0xe5	/* �� */

/* �u�q�`�l�o�b�t�@�T�C�Y	*/
#define	MAX_VBUF	1228

/*	�Z���^�[���	*/
struct	center	{
	char	name[25];		/* �Z���^�[�� */
	char	tel[15];		/* �d�b�ԍ� */
	int	speed;			/* �ʐM���x */
	int	parity;			/* �p���e�B�[ */
	int	chrlen;			/* �L�����N�^�� */
	int	stplen;			/* �X�g�b�v�r�b�g�� */
	int	xon;			/* �w�n�m�^�w�n�e�e */
	int	echo;			/* ���[�J���G�R�[�o�b�N */
	int	retkey;			/* ���^�[���L�[ */
	int	prompt;			/* �t�@�C�����M�v�����v�g */
	char	f3dsp[7];		/* ���E�R�\���f�[�^ */
	char	f3cmd[16];		/* ���E�R���s�R�}���h */
	char	mdcmd[27];		/* ���f���ڑ����⏕�R�}���h */
	char	redial[3];		/* ���_�C�����Ԋu */
	char	login[49];		/* �I�[�g���O�C���R�}���h */
	char	reserve[256-158];	/* ���U�[�u */
};
typedef	struct	center	CENTER;

/*	�����	*/
struct	modem	{
	int	dial;			/* �O�F�_�C�����@�P�F�g�[�� */
	int	modem;			/* �O�F�`�s�R�}���h�@�Q�F�u�Q�Tbis */
	char	mdcmd[27];		/* �ڑ����⏕�R�}���h */
	char	f2dsp[7];		/* ���E�Q�\���f�[�^ */
	char	f2cmd[16];		/* ���E�Q���s�R�}���h */
	char	reserve[128-54];	/* ���U�[�u */
};
typedef	struct	modem	MODEM;
	
/*	�E�C���h�D�\����	*/
struct	window	{
	int	sx;			/* �J�n���ʒu */
	int	sy;			/* �J�n�s�ʒu */
	int	nx;			/* ���� */
	int	ny;			/* �s�� */
	int	color;			/* �E�B���h�E�F */
	int	*tram;			/* �e�L�X�g�u�q�`�l�Z�[�u�G���A */
	int	*attr;			/* �A�g���r���[�g�Z�[�u�G���A */
};
typedef	struct	window	WINDOW;

/*	�Œ蕔��ʕ\���f�[�^	*/
struct	scrdat	{
	int	x;			/* �\�����ʒu */
	int	y;			/* �\���s�ʒu */
	char	*data;			/* �\���f�[�^ */
};
typedef	struct scrdat SCRDAT;

/*	�t�@���N�V�����L�[�ݒ�f�[�^	*/
struct	key_buf				/*  bdos format of func-key buffer  */ 
{
	char	fun[20][16];
	char	cur[11][6];
};

/*	�t�@�C����	*/
#define	FN_CNT		"YTERM.CNT"	/* �Z���^�[���t�@�C�� */
#define	FN_ENV		"YTERM.ENV"	/* ���ݒ�t�@�C�� */

/*	main.c	*/
extern	void	dsp_ctrl(int);
extern	void	rs1_putc(int);

/*	term.c	*/
extern	void	setuzoku(void);
extern	void	touroku(void);
extern	void	settei(void);
extern	void	setudan(void);
extern	void	parameter(void);
extern	void	tm_wait(int);

/*	file.c	*/
extern	void	file(void);
extern	void	rcv_text_end(void);
extern	void	snd_text_end(void);

/*	xmodem.c	*/
extern	int	x_send(FILE *, WINDOW *);
extern	int	x_receive(FILE *, WINDOW *);

/*	console.c	*/
extern	WINDOW	*w_open(int, int, int, int, char *, int);
extern	WINDOW	*w_alloc(int, int);
extern	void	w_display(WINDOW *, int, int, char *, int);
extern	void	w_close(WINDOW *);
extern	void	w_erase(WINDOW *);
extern	void	w_free(WINDOW *);
extern	void	w_dspstr(WINDOW *, int, int, int, char *);
extern	void	w_dspnstr(WINDOW *, int, int, int, char *, int);
extern	void	w_dspspc(WINDOW *, int, int, int);
extern	void	w_printf();
extern	int	inkey(void);
extern	int	getchr(void);
extern	int	w_inputs(WINDOW *, char *, int, int, int, int, int);
extern	void	beep(void);
extern	int	w_newline(WINDOW *, int);
extern	void	dsp_func(char **);
extern	void	w_loc(WINDOW *, int, int);
extern	int	w_select(WINDOW *, char **, int, int, int);
extern	void	w_color(WINDOW *, int, int, int, int);
extern	void	w_dspscr(WINDOW *, SCRDAT *);
extern	void	cputs1(char *, int);
extern	void	putch1(int, int);
extern	void	putch2(int);
extern	void	scroll_up(void);
extern	void	scroll_down(void);

/*	bios.c	*/
extern	void	t_loc(int, int);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	t_getfont(int, char *);
extern	void	t_setfont(int, char *);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);

/*	data.c	*/
extern	MODEM	modem;
extern	CENTER	cprm;
extern	CENTER	center[10];
extern	int	csr1_x;
extern	int	csr1_y;
extern	int	csr2_x;
extern	int	csr2_y;
extern	char	*func0[];
extern	long	c_time;
extern	int	conxxxt;
extern	RSPRM	default_prm;
extern	FILE	*rcv_fp;
extern	FILE	*snd_fp;
extern	int	send_wait;
extern	int	vbufc;
extern	int	vbufn;
extern	WINDOW	*scrp;
extern	int	opt_m;
extern	WINDOW	*wp_deverr;