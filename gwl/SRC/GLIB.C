/************************************************************************
 *									*
 *		�O���t�B�b�N�E�B���h�E���C�u����			*
 *									*
 *		����		: �O���t�B�b�N����			*
 *		�t�@�C����	: glib.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<dos.h>
#include	<jstring.h>
#include	<conio.h>
#include	"gwlib.h"

#define		high(x)		(int)(((x) >> 8) & 0xff)
			/* ���[�h�^����n�C�E�o�C�g�𓾂�}�N�� */
#define		low(x)		(int)((x) & 0xff)
			/* ���[�h�^���烍�[�E�o�C�g�𓾂�}�N�� */

static	u_char	g_scrmod;	/*  screen mode  */

static	u_char	glio_work[0x1300];	/* �O���t�k�h�n���[�N�G���A */

static	u_int	glio_seg;	/* ���[�N�G���A�̃Z�O�����g */
static	u_int	glio_off;	/* ���[�N�G���A�̃I�t�Z�b�g */
static	u_char	glio_ah;	/*  */

static	u_char	iret = 0xcf;	/* �h�q�d�s �R�[�h */

static	u_int	g_lio(int);

/*=======================================================================
 |
 |		�O���t�k�h�n���[�`���̌Ăяo��
 |
 |	int	g_lio(vect)
 |
 |		int	vect;		�O���t�k�h�n�̓��������R�[�h
 |
 |		�Ԓl	ret;		�O���t�k�h�n�̏I������
 |
 =======================================================================*/
static	u_int	g_lio(int vect)
{
	union	REGS	reg;
	struct	SREGS	seg;

	reg.h.ah = glio_ah;
	reg.x.bx = glio_off;
	seg.es = seg.ds = glio_seg;

	int86x(vect, &reg, &reg, &seg);

	return ((u_int)reg.x.ax);
}

/*=======================================================================
 |
 |		�O���t�k�h�n�̏�����
 |
 |	int	g_init()
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_init(void)
{
	register int	i;
	u_int	doff, dseg;
	void	far *fptr;
	unsigned far *romp, far *vectp;

	fptr = glio_work;
	doff = FP_OFF(fptr);
	dseg = FP_SEG(fptr);

	glio_seg = dseg + (doff >> 4);
	glio_off = doff & 15;

	FP_SEG(romp) = 0xf990;
	FP_OFF(romp) = 6;
	FP_SEG(vectp) = 0;
	FP_OFF(vectp) = 0xa0 * 4;

	/* �����x�N�^�[�`�O�g�`�`�e�g�̐ݒ� */
	for (i = 16; i != 0; --i) {
		*vectp++ = *romp;
		*vectp++ = 0xf990;
		romp += 2;
	}

	/* �����x�N�^�[�b�T�g�̐ݒ� */
	_dos_setvect(0xc5, (void (interrupt far *)())&iret);

	g_scrmod = 0;			/*  initialize screen mode  */

	return (high(g_lio(0xa0)));
}

/*=======================================================================
 |
 |		�O���t�B�b�N��ʃ��[�h�ݒ�
 |
 |	int	g_screen(mod, sw, ac, disp)
 |
 |		int	mod;		��ʃ��[�h
 |		int	sw;		��ʃX�C�b�`
 |		int	ac;		�A�N�e�B�u���
 |		int	disp;		�f�B�X�v���C���
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_screen(int mod, int sw, int ac, int disp)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_char	mod;
		u_char	sw;
		u_char	ac;
		u_char	disp;
	};
	int	err;

	((struct param *)glio_work)->mod  = mod;
	((struct param *)glio_work)->sw   = sw;
	((struct param *)glio_work)->ac   = ac;
	((struct param *)glio_work)->disp = disp;

	if ((err = high(g_lio(0xa1))) == 0) {
		if ((char)mod != 0xff)
			g_scrmod = mod;
	}

	outp(0x68,8);			/*  graph mode 400  */

	return (err);
}


/*=======================================================================
 |
 |		�`��̈�̎w��
 |
 |	int	g_view(x1, y1, x2, y2, ca, cb)
 |
 |		int	x1,y1;		�r���[�|�[�g�̍�����W
 |		int	x2,y2;		�r���[�|�[�g�̉E�����W
 |		int	ca;		�̈�F
 |		int	cb;		���E�F
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_view(int x1, int y1, int x2, int y2, int ca, int cb)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x1;
		u_int	y1;
		u_int	x2;
		u_int	y2;
		u_char	ca;
		u_char	cb;
	};

	((struct param *)glio_work)->x1 = x1;
	((struct param *)glio_work)->y1 = y1;
	((struct param *)glio_work)->x2 = x2;
	((struct param *)glio_work)->y2 = y2;
	((struct param *)glio_work)->ca = ca;
	((struct param *)glio_work)->cb = cb;

	return (high(g_lio(0xa2)));
}

/*=======================================================================
 |
 |		�w�i�F���̎w��
 |
 |	int	g_color(cbg, cbd, cfg, pmod)
 |
 |		int	cbg;		�o�b�N�O�����h�J���[
 |		int	cbd;		�{�[�_�[�J���[
 |		int	cfg;		�t�H�A�O�����h�J���[
 |		int	pmod;		�p���b�g���[�h
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_color(int cbg, int cbd, int cfg, int pmod)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_char	dummy;
		u_char	cbg;
		u_char	cbd;
		u_char	cfg;
		u_char	pmod;
	};

	((struct param *)glio_work)->cbg  = cbg;
	((struct param *)glio_work)->cbd  = cbd;
	((struct param *)glio_work)->cfg  = cfg;
	((struct param *)glio_work)->pmod = pmod;

	return (high(g_lio(0xa3)));
}

/*=======================================================================
 |
 |		�p���b�g�ԍ��ƕ\���F�R�[�h�̑Ή�
 |
 |	int	g_color2(pal, cc)
 |
 |		int	pal;		�p���b�g�ԍ�
 |		int	cc;		�\���F�R�[�h
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_color2(int pal, int cc)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_char	pal;
		u_int	cc;
	};

	((struct param *)glio_work)->pal = pal;
	((struct param *)glio_work)->cc  = cc;

	return (high(g_lio(0xa4)));
}

/*=======================================================================
 |
 |		�`��̈�̓h��Ԃ�
 |
 |	int	g_cls()
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_cls(void)
{
	return (high(g_lio(0xa5)));
}


/*=======================================================================
 |
 |		�_�̕`��
 |
 |	int	g_pset(x, y, pal)
 |
 |		int	x,y;		�_�̍��W
 |		int	pal;		�p���b�g�ԍ�
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_pset(int x, int y, int pal)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
		u_char	pal;
	};

	((struct param *)glio_work)->x   = x;
	((struct param *)glio_work)->y   = y;
	((struct param *)glio_work)->pal = pal;

	glio_ah = 1;

	return (high(g_lio(0xa6)));
}

/*=======================================================================
 |
 |		�_�̏���
 |
 |	int	g_preset(x, y)
 |
 |		int	x,y;		�_�̍��W
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_preset(int x, int y)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
		u_char	pal;
	};

	((struct param *)glio_work)->x   = x;
	((struct param *)glio_work)->y   = y;
	((struct param *)glio_work)->pal = 0xff;

	glio_ah = 2;

	return (high(g_lio(0xa6)));
}


/*=======================================================================
 |
 |		�����܂��͋�`�̕`��
 |
 |	int	g_line(x1, y1, x2, y2, pal1, dc, lsw, lstyle, lentile, ptile)
 |
 |		int	x1,y1;		���W�P
 |		int	x2,y2;		���W�Q
 |		int	pal1;		�p���b�g�ԍ��P
 |		int	dc;		�`��R�[�h
 |		int	lsw;		�X�C�b�`
 |		int	lstyle; 	���C���X�^�C���܂��̓p���b�g�ԍ��Q
 |		int	lentile;	�^�C���p�^�[����
 |		u_char	*ptile; 	�^�C���p�^�[��
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_line(int x1, int y1, int x2, int y2, int pal1, int dc, int lsw
	, int lstyle, int lentile, u_char *ptile)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x1;
		u_int	y1;
		u_int	x2;
		u_int	y2;
		u_char	pal1;
		u_char	dc;
		u_char	lsw;
		u_int	lstyle;
		u_char	lentile;
		u_int	ptile_off;
		u_int	ptile_seg;
	};

	void	far *fptr;

	((struct param *)glio_work)->x1 = x1;
	((struct param *)glio_work)->y1 = y1;
	((struct param *)glio_work)->x2 = x2;
	((struct param *)glio_work)->y2 = y2;

	((struct param *)glio_work)->pal1 = pal1;
	((struct param *)glio_work)->dc   = dc;
	((struct param *)glio_work)->lsw  = lsw;
	((struct param *)glio_work)->lstyle = lstyle;
	((struct param *)glio_work)->lentile = lentile;

	fptr = ptile;
	((struct param *)glio_work)->ptile_off = FP_OFF(fptr);
	((struct param *)glio_work)->ptile_seg = FP_SEG(fptr);

	return (high(g_lio(0xa7)));
}

/*=======================================================================
 |
 |		�~�܂��͑ȉ~�̕`��
 |
 |	int	g_circle(cx,cy,rx,ry,pal1,flag,sx,sy,ex,ey,lentile,ptile)
 |
 |		int	cx,cy;		���S�_�̍��W
 |		int	rx;		�w�����̔��a
 |		int	ry;		�x�����̔��a
 |		int	pal1;		�p���b�g�ԍ��P
 |		int	flag;		�t���O
 |		int	sx,sy;		�J�n�_�̍��W
 |		int	ex,ey;		�I���_�̍��W
 |		int	lentile;	�p���b�g�ԍ��Q�܂��̓^�C���p�^�[����
 |		u_char	*ptile; 	�^�C���p�^�[��
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_circle(int cx, int cy, int rx, int ry, int pal1, int flag, int sx
	, int sy, int ex, int ey, int lentile, u_char *ptile)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	cx;
		u_int	cy;
		u_int	rx;
		u_int	ry;
		u_char	pal1;
		u_char	flag;
		u_int	sx;
		u_int	sy;
		u_int	ex;
		u_int	ey;
		u_char	lentile;
		u_int	ptile_off;
		u_int	ptile_seg;
	};
	void	far *fptr;

	((struct param *)glio_work)->cx = cx;
	((struct param *)glio_work)->cy = cy;
	((struct param *)glio_work)->rx = rx;
	((struct param *)glio_work)->ry = ry;

	((struct param *)glio_work)->pal1 = pal1;
	((struct param *)glio_work)->flag = flag;

	((struct param *)glio_work)->sx = sx;
	((struct param *)glio_work)->sy = sy;
	((struct param *)glio_work)->ex = ex;
	((struct param *)glio_work)->ey = ey;

	((struct param *)glio_work)->lentile = lentile;

	fptr = ptile;
	((struct param *)glio_work)->ptile_off = FP_OFF(fptr);
	((struct param *)glio_work)->ptile_seg = FP_SEG(fptr);

	return (high(g_lio(0xa8)));
}

/*=======================================================================
 |
 |		�w��F�ɂ��h��Ԃ�
 |
 |	int	g_paint(x,y,pal1,pal2)
 |
 |		int	x,y;		�J�n�_�̍��W
 |		int	pal1;		�̈�F�i�p���b�g�ԍ��j
 |		int	pal2;		���E�F�i�p���b�g�ԍ��j
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_paint(int x, int y, int pal1, int pal2)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
		u_char	pal1;
		u_char	pal2;
		u_int	e_work;
		u_int	s_work;
	};

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	((struct param *)glio_work)->pal1 = pal1;
	((struct param *)glio_work)->pal2 = pal2;

	((struct param *)glio_work)->e_work = 0x12f8;
	((struct param *)glio_work)->s_work = 0x1200;

	return (high(g_lio(0xa9)));
}

/*=======================================================================
 |
 |		�^�C���p�^�[���ɂ��h��Ԃ�
 |
 |	int	g_paint2(x, y, lentile, ptile, cb)
 |
 |		int	x,y;		�J�n�_�̍��W
 |		int	lentile;	�^�C���p�^�[����
 |		u_char	*ptile; 	�^�C���p�^�[��
 |		int	cb;		���E�F�i�p���b�g�ԍ��j
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_paint2(int x, int y, int lentile, u_char *ptile, int cb)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
		u_char	dummy1;
		u_char	lentile;
		u_int	ptile_off;
		u_int	ptile_seg;
		u_char	cb;
		u_char	dummy2;
		u_int	dummy3;
		u_int	dummy4;
		u_int	e_work;
		u_int	s_work;
	};
	void	far *fptr;

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	((struct param *)glio_work)->lentile = lentile;

	fptr = ptile;
	((struct param *)glio_work)->ptile_off = FP_OFF(fptr);
	((struct param *)glio_work)->ptile_seg = FP_SEG(fptr);

	((struct param *)glio_work)->cb = cb;

	((struct param *)glio_work)->e_work = 0x12f8;
	((struct param *)glio_work)->s_work = 0x1200;

	return (high(g_lio(0xaa)));
}

/*=======================================================================
 |
 |		�`����̊i�[
 |
 |	int	g_get(x1, y1, x2, y2, buff, len)
 |
 |		int	x1,y1;		�w��̈�̍���̍��W
 |		int	x2,y2;		�w��̈�̉E���̍��W
 |		u_char	*buff;		�O���t�B�b�N�p�^�[�����i�[����̈�
 |		int	len;		�i�[�̈�̃T�C�Y
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_get(int x1, int y1, int x2, int y2, u_char *buff, int len)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x1;
		u_int	y1;
		u_int	x2;
		u_int	y2;
		u_int	buff_off;
		u_int	buff_seg;
		u_int	len;
	};
	void	far *fptr;

	((struct param *)glio_work)->x1 = x1;
	((struct param *)glio_work)->y1 = y1;
	((struct param *)glio_work)->x2 = x2;
	((struct param *)glio_work)->y2 = y2;

	fptr = buff;
	((struct param *)glio_work)->buff_off = FP_OFF(fptr);
	((struct param *)glio_work)->buff_seg = FP_SEG(fptr);
	((struct param *)glio_work)->len      = len;

	return (high(g_lio(0xab)));
}

/*=======================================================================
 |
 |		�`������i�[�悩��̈�֖߂�
 |
 |	int	g_put(x, y, buff, len, dm, csw, fc, bc)
 |
 |		int	x,y;		�\��������̈�̍�����W
 |		u_char	*buf;		�i�[��̃|�C���^
 |		int	len;		�O���t�B�b�N�p�^�[���̃T�C�Y
 |		int	dm;		�\�����[�h
 |		int	csw;		�J���[�X�C�b�`
 |		int	fc;		���̃p���b�g�ԍ�
 |		inc	bc;		���̃p���b�g�ԍ�
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_put(int x, int y, u_char *buff, int len, int dm, int csw, int fc
	, int bc)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
		u_int	buff_off;
		u_int	buff_seg;
		u_int	len;
		u_char	dm;
		u_char	csw;
		u_char	fc;
		u_char	bc;
	};
	void	far *fptr;

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	fptr = buff;
	((struct param *)glio_work)->buff_off = FP_OFF(fptr);
	((struct param *)glio_work)->buff_seg = FP_SEG(fptr);
	((struct param *)glio_work)->len      = len;

	((struct param *)glio_work)->dm  = dm;
	((struct param *)glio_work)->csw = csw;
	((struct param *)glio_work)->fc  = fc;
	((struct param *)glio_work)->bc  = bc;

	return (high(g_lio(0xac)));
}

/*=======================================================================
 |
 |		�����t�H���g�̕\��
 |
 |	int	g_kanji(x, y, jap, dm, csw, fc, bc)
 |
 |		int	x,y;		�\��������̈�̍�����W
 |		u_int	jap;		�����R�[�h
 |		int	dm;		�\�����[�h
 |		int	csw;		�J���[�X�C�b�`
 |		int	fc;		�t�H���g�̃p���b�g�ԍ�
 |		int	bc;		�t�H���g�ȊO�̕����̃p���b�g�ԍ�
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_kanji(int x, int y, u_int jap, int dm, int csw, int fc, int bc)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
		u_int	jap;
		u_char	dm;
		u_char	csw;
		u_char	fc;
		u_char	bc;
	};

	if (0x8000 <= jap)
		jap = jmstojis(jap);

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	((struct param *)glio_work)->jap = jap;

	((struct param *)glio_work)->dm  = dm;
	((struct param *)glio_work)->csw = csw;
	((struct param *)glio_work)->fc  = fc;
	((struct param *)glio_work)->bc  = bc;

	return (high(g_lio(0xad)));
}

/*=======================================================================
 |
 |		�O���t�B�b�N��ʂ̃X�N���[��
 |
 |	int	g_roll(updot, leftdot, cf)
 |
 |		int	updot;		�㉺�����h�b�g��
 |		int	leftdot;	���E�����h�b�g��
 |		int	cf;		�N���A�t���O
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_roll(int updot, int leftdot, int cf)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	updot;
		u_int	leftdot;
		u_char	cf;
	};

	((struct param *)glio_work)->updot   = updot;
	((struct param *)glio_work)->leftdot = leftdot;

	((struct param *)glio_work)->cf = cf;

	return (high(g_lio(0xae)));
}

/*=======================================================================
 |
 |		�h�b�g�̃p���b�g�ԍ��𓾂�
 |
 |	int	g_point(x, y)
 |
 |		int	pal;		�p���b�g�ԍ�
 |		int	x,y;		�h�b�g�̍��W
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_point(int x, int y)
{
	struct	param	{		/* �p�����[�^���X�g */
		u_int	x;
		u_int	y;
	};

	((struct param *)glio_work)->x = x;
	((struct param *)glio_work)->y = y;

	return (low(g_lio(0xaf)));
}