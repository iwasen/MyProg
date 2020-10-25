/************************************************************************
 *									*
 *		�q�r�|�Q�R�Q�b���C�u����				*
 *									*
 *		����		: �^�C�}�[����				*
 *		�t�@�C����	: timer.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include	<stddef.h>
#include	<stdlib.h>
#include	<dos.h>
#include	<conio.h>
#include	"rs232c.h"

/*	�^�C�}�[���[�N�G���A��`	*/
struct	timer	{
	int	no;
	int	counter;
	struct	timer	*chain;
};
typedef	struct	timer	TIMER;

/*	�����֐�	*/
static	void	interrupt far	tm_intr(void);

/*	�X�^�e�B�b�N�ϐ�	*/
static	int	open_flag;
static	TIMER	*tm_top;
static	void	(interrupt far *tm_vect)();

/*	�P�O�����J�E���^�[ */
long	tm_counter;

#pragma	check_stack-		/* �X�^�b�N�`�F�b�N���� */

/*=======================================================================
 |
 |		�^�C�}�[�g�p��������
 |
 |	int	tm_open()
 |
 |		�Ԓl			�O�F����@�@�P�F�I�[�v���ς�
 |
 =======================================================================*/
int	tm_open(void)
{
	char	far *fptr;

	if (open_flag != 0)
		return(1);

	/* ���荞�݃x�N�^�Z�b�g */
	tm_vect = _dos_getvect(0x08);
	_dos_setvect(0x08, tm_intr);

	/* �^�C�}�[�N�� */
	FP_SEG(fptr) = 0;
	FP_OFF(fptr) = 0x501;
	(void)outp(IOP_TMC_CWW, 0x36);
	(void)outp(IOP_TMC_C0L, 0x00);
	(void)outp(IOP_TMC_C0L, *fptr & 0x80 ? 0x4e : 0x60);
	(void)outp(IOP_ICM_IMR, inp(IOP_ICM_IMR) & 0xfe);

	tm_counter = 0L;	/* �P�O�����J�E���^�[�N���A */

	open_flag = 1;		/* �I�[�v���t���O�Z�b�g */

	return(0);
}

/*=======================================================================
 |
 |		�^�C�}�[�Z�b�g
 |
 |	int	tm_set(no, tm)
 |
 |		int	no;		�^�C�}�[�ԍ�
 |		int	tm;		�^�C�}�[�l�i�P�O�����P�ʁj
 |
 |		�Ԓl			�O�F����
 |					�P�F���I�[�v��
 |					�Q�F���[�N�G���A�m�ێ��s
 |
 =======================================================================*/
int	tm_set(int no, int tm)
{
	TIMER	*tp;

	if (open_flag == 0)
		return(1);

	_disable();

	/* ���[�N�G���A���T�[�`���� */
	for (tp = tm_top; tp != NULL; tp = tp->chain) {
		if (tp->no == no)
			break;
	}

	if (tp == NULL) {
		/* ���[�N�G���A�m�� */
		if ((tp = malloc(sizeof(TIMER))) == NULL) {
			_enable();
			return(2);
		}

		/* ���[�N�G���A�ɃZ�b�g */
		tp->no = no;
		tp->chain = tm_top;
		tm_top = tp;
	}

	tp->counter = tm;

	_enable();

	return(0);
}

/*=======================================================================
 |
 |		�^�C���A�E�g�`�F�b�N
 |
 |	int	tm_check(no)
 |
 |		int	no;		�^�C�}�[�ԍ�
 |
 |		�Ԓl			�O�F�^�C���A�E�g���Ă��Ȃ�
 |					�P�F�^�C���A�E�g����
 |					�@�@�i�^�C�}�[���Z�b�g����Ă��Ȃ��j
 |
 =======================================================================*/
int	tm_check(int no)
{
	TIMER	*tp;

	/* ���[�N�G���A���T�[�`�� */
	for (tp = tm_top; tp != NULL; tp = tp->chain) {
		if (tp->no == no) {
			if (tp->counter == 0) {
				tm_stop(no);
				return(1);
			} else
				return(0);
		}
	}

	return(1);
}

/*=======================================================================
 |
 |		�^�C�}�[��~
 |
 |	void	tm_stop(no)
 |
 |		int	no;		�^�C�}�[�ԍ�
 |
 =======================================================================*/
void	tm_stop(int no)
{
	TIMER	*tp, **tpp;

	/* ���[�N�G���A���T�[�`���A������� */
	_disable();
	for (tpp = &tm_top; *tpp != 0; tpp = &(*tpp)->chain) {
		if ((*tpp)->no == no) {
			tp = *tpp;
			*tpp = (*tpp)->chain;
			free(tp);
			break;
		}
	}
	_enable();
}

/*=======================================================================
 |
 |		�^�C�}�[�g�p�I������
 |
 |	void	tm_close()
 |
 =======================================================================*/
void	tm_close(void)
{
	if (open_flag == 0)
		return;

	/* �S�Ẵ^�C�}�[���I�������� */
	while (tm_top != NULL)
		tm_stop(tm_top->no);

	/* ���荞�݃x�N�^�����ɖ߂� */
	_disable();
	(void)outp(IOP_ICM_IMR, inp(IOP_ICM_IMR) | 0x01);
	_dos_setvect(0x08, tm_vect);
	_enable();

	open_flag = 0;
}

/*=======================================================================
 |
 |		�^�C�}�[���荞�ݏ���
 |
 |	void	interrupt far	tm_intr()
 |
 =======================================================================*/
static	void	interrupt far	tm_intr(void)
{
	TIMER	*tp;

	for (tp = tm_top; tp != NULL; tp = tp->chain) {
		if (tp->counter != 0)
			tp->counter--;	/* �^�C�}�[�J�E���g�_�E�� */
	}

	tm_counter++;		/* �P�O�����J�E���^�[�X�V */

	/* ���荞�ݏI���ʒm */
	(void)outp(IOP_ICM_EOI, 0x20);
}