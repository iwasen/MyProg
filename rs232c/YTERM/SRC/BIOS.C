/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �a�h�n�r�Ăяo������			*
 *		�t�@�C����	: bios.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <dos.h>

typedef	unsigned char	u_char;
typedef	unsigned int	u_int;

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
 |		�e�L�X�g�J�[�\���\��
 |
 |	void	t_csron()
 |
 =======================================================================*/
void	t_csron(void)
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
void	t_csroff(void)
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