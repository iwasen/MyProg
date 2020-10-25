/************************************************************************
 *									*
 *		�ʐM�v���O�����@�x�|�s�d�q�l				*
 *									*
 *		����		: �O���ϐ�				*
 *		�t�@�C����	: data.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include <stdio.h>
#include "rs232c.h"
#include "yterm.h"

MODEM	modem;			/* ���ݒ��� */
CENTER	cprm;			/* �J�����g�Z���^�[��� */
CENTER	center[10];		/* �Z���^�[��� */
int	csr1_x;			/* �J�[�\�����ʒu */
int	csr1_y;			/* �J�[�\���s�ʒu */
int	csr2_x = 0;		/* �J�[�\�����ʒu */
int	csr2_y = 1;		/* �J�[�\���s�ʒu */
char	*func0[] = {		/* �t�@���N�V�����L�[�S���� */
	NULL, NULL, NULL, NULL, NULL,
	NULL, NULL, NULL, NULL, NULL
};
long	c_time;			/* �ʐM�J�n���� */
int	conxxxt;		/* �ڑ��t���O */
RSPRM	default_prm = {		/* �f�t�H���g�ʐM�p�����[�^ */
	ASYNC,
	DUPLEX_FULL,
	BLT_300,
	PARITY_NONE,
	CHR_8,
	STP_1,
	FLOW_NONE,
	DTR_OFF,
	0,
	8196,
	NULL
};
FILE	*rcv_fp;		/* ��M�t�@�C���|�C���^ */
FILE	*snd_fp;		/* ���M�t�@�C���|�C���^ */
int	send_wait;		/* �t�@�C�����M�҂��t���O */
int	vbufc;			/* �u�q�`�l�o�b�t�@�J�E���^ */
int	vbufn;			/* �u�q�`�l�o�b�t�@�i�[�s�� */
static	WINDOW	scr = {		/* �S��ʗp�E�B���h�E�f�[�^ */
	0, 0, 80, 25, C_WHITE, NULL, NULL
};
WINDOW	*scrp = &scr;		/* �S��ʗp�E�B���h�E�|�C���^ */
int	opt_m;			/* �^�l�I�v�V�����t���O */
WINDOW	*wp_deverr;		/* �f�o�C�X�G���[�\���E�B���h�E */