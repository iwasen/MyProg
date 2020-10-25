/************************************************************************
 *									*
 *		���Y�����ԁ@�o���p�[�h�F�x���V�X�e��			*
 *									*
 *		����		: ���ʃw�b�_				*
 *		�t�@�C����	: bumper.h				*
 *									*
 ************************************************************************/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <memory.h>
#include <dos.h>
#include <signal.h>
#include <conio.h>

/*	�^��`		*/
typedef	unsigned int	u_int;
typedef	unsigned char	u_char;

/*	�L�[�R�[�h	*/
#define	KEY_SHIFT	0x100
#define	KEY_FUNC	0x600
#define	KEY_HELP	0x01
#define	KEY_BS		0x08
#define	KEY_TAB		0x09
#define	KEY_SFT_TAB	(KEY_SHIFT|KEY_TAB)
#define	KEY_HOME	0x0b
#define	KEY_CLR		0x0c
#define	KEY_CR		0x0d
#define	KEY_ESC		0x1b
#define	KEY_SFT_ESC	(KEY_SHIFT|KEY_ESC)
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
#define	KEY_MOUSE	0x700

/*	�J���[�R�[�h	*/
#define	C_BLACK		0
#define	C_BLUE		1
#define	C_RED		2
#define	C_MAGENTA	3
#define	C_GREEN		4
#define	C_CYAN		5
#define	C_YELLOW	6
#define	C_WHITE		7

/*	�A�g���r���[�g		*/
#define	A_BLINK		0x02	/* �u�����N */
#define	A_REVERSE	0x04	/* ���o�[�X */
#define	A_ULINE		0x08	/* �A���_�[���C�� */
#define	A_BLUE		0x20	/* �� */
#define	A_RED		0x40	/* �� */
#define	A_MAGENTA	0x60	/* �� */
#define	A_GREEN		0x80	/* �� */
#define	A_CYAN		0xa0	/* ���F */
#define	A_YELLOW	0xc0	/* ���F */
#define	A_WHITE		0xe0	/* �� */

/*	�Z�O�����g�A�h���X	*/
#define	SEG_TVRAM	0xa000		/* �e�L�X�g�u�q�`�l�Z�O�����g */
#define	SEG_ATTR	0xa200		/* �A�g���r���[�g�Z�O�����g */
#define	SEG_GVRAM	0xa800		/* �O���t�B�b�N�u�q�`�l�Z�O�����g */

/*	�O���[�o�����W�X�^	*/
#define	GR_BPT		0001
#define	GR_TRT		2001
#define	GR_DLT		2201
#define	GR_CCT		2701
#define	GR_DCT		2821

/*	�h�[���[����e�[�u��	*/
struct	schedul	{
	int	dolly_no[100];		/* �h�[���[�m���D */
	int	color_code[100];	/* �J���[�R�[�h */
};
typedef	struct	schedul	SCHEDUL;

/*	�o���p�[�e�[�u��	*/
struct	bumper	{
	int	dolly_code[500];	/* �h�[���[�R�[�h */
	int	color_code[500];	/* �J���[�R�[�h */
	int	tekisei_zaiko[500];	/* �K���݌� */
};
typedef	struct	bumper	BUMPER;

/*	�Œ蕔��ʕ\���f�[�^	*/
struct	scrdat	{
	int	pos_x;			/* �\�����ʒu */
	int	pos_y;			/* �\���s�ʒu */
	char	*data;			/* �\���f�[�^ */
};
typedef	struct scrdat SCRDAT;

/*	�r���\���e�[�u��		*/
struct	line	{
	int	flag;			/* �t���O�@=0:���@=1:�g  =-1:�I�� */
	int	pos_x1;			/* �J�n���ʒu */
	int	pos_y1;			/* �J�n�s�ʒu */
	int	pos_x2;			/* �I�����ʒu */
	int	pos_y2;			/* �I���s�ʒu */
};
typedef	struct	line	LINE;

/*	�t�@���N�V�����L�[�ݒ�f�[�^	*/

struct	key_buf				/*  bdos format of func-key buffer  */ 
{
	char	fun[20][16];
	char	cur[11][6];
};

extern	struct key_buf key_data; /* �L�[�R�[�h */
extern	int	tm_ovf;
extern	SCHEDUL	schedule_tbl;		/* �X�P�W���[���e�[�u�� */
extern	BUMPER	bumper_tbl;		/* �o���p�[�e�[�u�� */
extern	int	dolly_tbl[500];		/* �h�[���[�m���e�[�u�� */
extern	char	colcnv_tbl[30][4];	/* �F�R�[�h�ϊ��e�[�u�� */
extern	char	dolcnv_tbl[50][6];	/* �h�[���[��R�[�h�ϊ��e�[�u�� */
extern	int	target_id;		/* �\�[�X�h�c�i�b�b�l�j */
extern	int	source_id;		/* �^�[�Q�b�g�h�c�i�b�b�l�j */

/*	main.c	*/
extern	void	initialize(void);
extern	void	final(void);
/*	menu.c	*/
extern	void	menu(void);
/*	schedule.c	*/
extern	void	schedule(void);
/*	bumper.c	*/
extern	void	bumper(void);
/*	dolly.c	*/
extern	void	dolly(void);
/*	colcnv.c()	*/
extern	void	colcnv(void);
/*	dolcnv.c()	*/
extern	void	dolcnv(void);
/*	ccm.c	*/
int	wccm(int, int, void *, int, int);
int	rccm(int, int, void *, int, int);
/*	csub.c	*/
extern	void	dspstr(int, int, char *, int);
extern	void	dspnstr(int, int, char *, int, int);
extern	void	xprintf(int, int, int, char *, ...);
extern	void	dspscr(SCRDAT *, int);
extern	void	dspspc(int, int, int);
extern	void	clrtoeol(void);
extern	void	dspline(LINE *, int);
extern	int	t_color_code(int);
extern	void	dsp_field(int, int, int, int);
extern	int	strcount(char *);
extern	int	inkey(void);
extern	int	getchr(void);
extern	void	set_keybuf(char *);
extern	int	inputs(char *, int, int, int, int, int);
extern	void	attrib(int, int, int, int, int);
extern	void	dsp_func(char **);
extern	void	*talloc(int);
extern	void	*trealloc(void *, int);
extern	int	kakunin(char *);
extern	void	message(char *, int);
/*	bios.c	*/
extern	void	t_loc(int, int);
extern	void	t_color(int);
extern	void	t_cls(void);
extern	void	t_csron(void);
extern	void	t_csroff(void);
extern	void	beep(void);
extern	void	t_setfont(int, char *);
extern	void	t_fget(struct key_buf *);
extern	void	t_fput(struct key_buf *);
extern	int	g_init(void);
extern	int	g_screen(int, int, int, int);
extern	int	g_color(int, int, int, int);
extern	int	g_color2(int, int);
extern	int	g_cls(void);
extern	int	g_line(int, int, int, int, int, int, ...);
extern	void	timset(int);
extern	int	far _interrupt tm_int(void);
extern	void	rs1_open(int, int, int);
extern	int	rs1_rxc(void);
extern	int	rs1_get(void);
extern	void	rs1_put(int);
extern	void	rs1_close(void);