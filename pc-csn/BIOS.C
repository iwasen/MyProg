/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �a�h�n�r�R�[������			*
 *		�t�@�C����	: bios.c				*
 *		�쐬��		: 91/04/01				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

#define		high(x)		(int)(((x) >> 8) & 0xff)
			/* ���[�h�^����n�C�E�o�C�g�𓾂�}�N�� */
#define		low(x)		(int)((x) & 0xff)
			/* ���[�h�^���烍�[�E�o�C�g�𓾂�}�N�� */
#define		btow(h,l)	(((u_int)(h) << 8) | (l))
			/* �n�C�E�o�C�g�ƃ��[�E�o�C�g���烏�[�h�^�𓾂�}�N�� */
mouse_drv = 0;

static	u_char	glio_work[0x1200];	/* �O���t�k�h�n���[�N�G���A */

static	u_int	glio_seg;	/* ���[�N�G���A�̃Z�O�����g */
static	u_int	glio_off;	/* ���[�N�G���A�̃I�t�Z�b�g */
static	u_char	glio_ah;	/*  */

static	u_char	iret = 0xcf;	/* �h�q�d�s �R�[�h */

static	u_int	g_lio(int);
static	void	m_int(union REGS *);

/*=======================================================================
 |
 |		�J�[�\���ʒu�ݒ�
 |
 |	void	t_loc(x, y)
 |
 |		int	x;		�w���W�i�O�|�V�X�j
 |		int	y;		�x���W�i�O�|�Q�S�j
 |
 =======================================================================*/
void	t_loc(int x, int y)
{
	union REGS	reg;

	reg.h.cl = 0x10;
	reg.h.ah = 3;
	reg.h.dh = y;
	reg.h.dl = x;
	int86(0xdc, &reg, &reg);
}

/*=======================================================================
 |
 |		�����F�̐ݒ�
 |
 |	void	t_color(c)
 |
 |		int	c;		�e�L�X�g�J���[�R�[�h
 |
 =======================================================================*/
void	t_color(int c)
{
	union REGS	reg;


	reg.h.cl = 0x10;
	reg.h.ah = 2;
	reg.h.dl = c;
	int86(0xdc,&reg,&reg);
}

/*=======================================================================
 |
 |		�e�L�X�g��ʃN���A
 |
 |	void	t_cls()
 |
 =======================================================================*/
void	t_cls()
{
	union REGS	reg;

	reg.h.cl = 0x10;
	reg.h.ah = 10;
	reg.h.dl = 2;
	int86(0xdc, &reg, &reg);
}

/*=======================================================================
 |
 |		�e�L�X�g�J�[�\���\��
 |
 |	void	t_csron()
 |
 =======================================================================*/
void	t_csron()
{
	union REGS	reg;

	reg.h.ah = 0x11;
	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		�e�L�X�g�J�[�\������
 |
 |	void	t_csroff()
 |
 =======================================================================*/
void	t_csroff()
{
	union REGS	reg;

	reg.h.ah = 0x12;
	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		�t�H���g�f�[�^�ǂݍ���
 |
 |	void	t_getfont(code, font)
 |
 |		int	code;		�O���R�[�h
 |		char	*font;		�t�H���g�f�[�^
 |
 =======================================================================*/
void	t_getfont(int code, char *font)
{
	union REGS	reg;
	void	far *fptr;

	reg.h.ah = 0x14;
	reg.x.dx = code;
	fptr = font;
	reg.x.cx = FP_OFF(fptr);
	reg.x.bx = FP_SEG(fptr);

	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		�O���t�H���g�f�[�^�Z�b�g
 |
 |	void	t_setfont(code, font)
 |
 |		int	code;		�O���R�[�h
 |		char	*font;		�t�H���g�f�[�^
 |
 =======================================================================*/
void	t_setfont(int code, char *font)
{
	union REGS	reg;
	void	far *fptr;

	reg.h.ah = 0x1a;
	reg.x.dx = code;
	fptr = font;
	reg.x.cx = FP_OFF(fptr);
	reg.x.bx = FP_SEG(fptr);

	int86(0x18, &reg, &reg);
}

/*=======================================================================
 |
 |		�t�@���N�V�����L�[�̓��e�̎擾
 |
 |	void	t_fget(keystr)
 |
 |		struct key_buf *keystr;		���o���ꂽ���e
 |
 =======================================================================*/
void	t_fget(struct key_buf *keystr)
{
	union REGS	reg;
	struct SREGS	sreg;
	void	far *fptr;

	reg.x.ax = 0;
	reg.x.cx = 0x0c;
	fptr = keystr;
	reg.x.dx = FP_OFF(fptr);
	sreg.ds = FP_SEG(fptr);

	int86x(0xdc, &reg, &reg, &sreg);
}

/*=======================================================================
 |
 |		�t�@���N�V�����L�[�̓��e�̐ݒ�
 |
 |	void	t_fput(keystr)
 |
 |		struct key_buf *keystr;		�ݒ肷����e
 |
 =======================================================================*/
void	t_fput(struct key_buf *keystr)
{
	union REGS	reg;
	struct SREGS	sreg;
	void	far *fptr;

	reg.x.ax = 0;
	reg.x.cx = 0x0d;
	fptr = keystr;
	reg.x.dx = FP_OFF(fptr);
	sreg.ds = FP_SEG(fptr);

	int86x(0xdc, &reg, &reg, &sreg);
}

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
int	g_init()
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

	err = high(g_lio(0xa1));

	outp(0x68,8);			/*  graph mode 400  */

	return (err);
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
 |		�`��̈�̓h��Ԃ�
 |
 |	int	g_cls()
 |
 |		�Ԓl			�I���R�[�h
 |
 =======================================================================*/
int	g_cls()
{
	return (high(g_lio(0xa5)));
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
 |		�}�E�X�h���C�o�[�Ăяo��
 |
 |	void	m_int(reg)
 |
 |		union REGS *reg;	���W�X�^
 |
 =======================================================================*/
static	void	m_int(union REGS *reg)
{
	int86(0x33, reg, reg);
}

/*=======================================================================
 |
 |		�}�E�X�h���C�o�[������
 |
 |	int	m_init()
 |
 |		int	c;		�\������
 |
 |		�Ԓl			�O�F�h���C�o�Ȃ��@�P�F����
 |
 =======================================================================*/
int	m_init()
{
	union	REGS	reg;

	reg.x.ax = 0;
	m_int(&reg);
	
	return (reg.x.ax);
}

/*=======================================================================
 |
 |		�}�E�X�J�[�\���\���n�m
 |
 |	void	m_csron()
 |
 =======================================================================*/
void	m_csron()
{
	union	REGS	reg;

	reg.x.ax = 1;
	m_int(&reg);
}

/*=======================================================================
 |
 |		�}�E�X�J�[�\���\���n�e�e
 |
 |	void	m_csroff()
 |
 =======================================================================*/
void	m_csroff()
{
	union	REGS	reg;

	reg.x.ax = 2;
	m_int(&reg);
}

/*=======================================================================
 |
 |		�}�E�X�J�[�\���̈ʒu�𓾂�
 |
 |	void	m_pos(px, py, plsw, prsw)
 |
 |		int	*px;		�}�E�X�J�[�\���̂w���W
 |		int	*py;		�}�E�X�J�[�\���̂x���W
 |		int	*plsw;      	���{�^���̏��
 |		int	*prsw;      	�E�{�^���̏��
 |
 =======================================================================*/
void	m_pos(int *px, int *py, int *plsw, int *prsw)
{
	union	REGS	reg;

	reg.x.ax = 3;
	m_int(&reg);

	*px = reg.x.cx;
	*py = reg.x.dx;
	*plsw = reg.x.ax;
	*prsw = reg.x.bx;
	if (mouse_drv) {
		*plsw = (*prsw & 0x01) ? -1 : 0;
		*prsw = (*prsw & 0x02) ? -1 : 0;
	}
}

/*=======================================================================
 |
 |		�}�E�X�J�[�\���̈ʒu��ݒ肷��
 |
 |	void	m_loc(x, y)
 |
 |		int	x;		�}�E�X�J�[�\���̂w���W
 |		int	y;		�}�E�X�J�[�\���̂x���W
 |
 =======================================================================*/
void	m_loc(int x, int y)
{
	union	REGS	reg;

	reg.x.ax = 4;
	reg.x.cx = x;
	reg.x.dx = y;
	m_int(&reg);
}

/*=======================================================================
 |
 |		�^�C�}�[�Z�b�g����
 |
 |	void	timset(tm)
 |
 |		int	tm;		�^�C�}�[�l�i�P�O�����P�ʁj
 |
 =======================================================================*/
void	timset(int tm)
{
	extern	interrupt far	tm_int();
	union	REGS	reg;
	struct	SREGS	seg;
	void	far	*fptr;

	tm_ovf = 0;		/* �^�C�}�[�I�[�o�[�t���[�t���O�N���A */

	/* �^�C�}�[�Z�b�g */
	reg.h.ah = 0x02;
	reg.x.cx = tm;
	fptr = tm_int;
	reg.x.bx = FP_OFF(fptr);
	seg.es =FP_SEG(fptr);
	int86x(0x1c, &reg, &reg, &seg);
}

/*=======================================================================
 |
 |		�^�C�}�[���荞�ݏ���
 |
 |	void	tm_int()
 |
 =======================================================================*/
interrupt far	tm_int()
{
	tm_ovf = 1;
}

/*=======================================================================
 |
 |		�^�C�}�[���Z�b�g����
 |
 |	void	timreset()
 |
 =======================================================================*/
void	timreset()
{
	timset(1);
	while (tm_ovf == 0);
		;
	tm_ovf = 0;
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�I�[�v��
 |
 |	void	rs1_open(speed, mode, cmd)
 |
 |		int	speed;		�ʐM���x
 |		int	mode;		�ʐM���[�h
 |		int	cmd;		�����R�}���h
 |
 =======================================================================*/
void	rs1_open(int speed, int mode, int cmd)
{
	static	char	rsbuf[8196];		/* ��M�o�b�t�@ */
	union	REGS	reg;
	struct	SREGS	seg;
	char	far	*fptr;

	reg.h.ah = 0x01;	/* �R�}���h���ʃR�[�h */
	reg.h.al = speed;	/* �X�s�[�h�ݒ� */

	reg.h.bh = 0;		/* ���M���^�C���A�E�g���ԁi�f�t�H���g�j */
	reg.h.bl = 0;		/* ��M���^�C���A�E�g���ԁi�f�t�H���g�j */

	reg.h.ch = mode;	/* ���[�h�ݒ� */
	reg.h.cl = cmd;		/* �R�}���h�ݒ� */

	fptr = rsbuf;
	reg.x.di = FP_OFF(fptr);
	seg.es = FP_SEG(fptr);
	reg.x.dx = sizeof(rsbuf) - 20;	/* ��M�o�b�t�@�T�C�Y */

	int86x(0x19, &reg, &reg, &seg);	/* �a�h�n�r�R�[�� */
}

/*=======================================================================
 |
 |		��M�o�b�t�@�`�F�b�N
 |
 |	int	rs1_rxc()
 |
 |		�Ԓl		��M������
 |
 =======================================================================*/
int	rs1_rxc()
{
	union	REGS	reg;

	reg.h.ah = 0x02;		/* �R�}���h���ʃR�[�h */
	int86(0x19, &reg, &reg);	/* �a�h�n�r�R�[�� */

	return(reg.x.cx);
}

/*=======================================================================
 |
 |		�P������M
 |
 |	int	rs1_get()
 |
 |		�Ԓl		��M����
 |
 =======================================================================*/
int	rs1_get()
{
	union	REGS	reg;

	reg.h.ah = 0x04;		/* �R�}���h���ʃR�[�h */
	int86(0x19, &reg, &reg);	/* �a�h�n�r�R�[�� */

	if (reg.h.cl & 0x38)		/* �G���[�`�F�b�N */
		return(0x100);

	return(reg.h.ch);
}

/*=======================================================================
 |
 |		�P�������M
 |
 |	void	rs1_put(c)
 |
 |		int	c;		���M����
 |
 =======================================================================*/
void	rs1_put(int c)
{
	union	REGS	reg;

	reg.h.ah = 0x03;		/* �R�}���h���ʃR�[�h */
	reg.h.al = c;
	int86(0x19, &reg, &reg);	/* �a�h�n�r�R�[�� */
}

/*=======================================================================
 |
 |		�q�r�|�Q�R�Q�b�N���[�Y
 |
 |	void	rs1_close()
 |
 =======================================================================*/
void	rs1_close()
{
	union	REGS	reg;

	reg.h.ah = 0x05;		/* �R�}���h���ʃR�[�h */
	reg.h.al = 0x40;
	int86(0x19, &reg, &reg);	/* �a�h�n�r�R�[�� */
}

/*=======================================================================
 |
 |		�P�����v�����^�[�o��
 |
 |	void	lputc(c)
 |
 =======================================================================*/
void	lputc(int c)
{
	union REGS	reg;

	reg.h.ah = 0x11;
	reg.h.al = c;
	int86(0x1a, &reg, &reg);
}