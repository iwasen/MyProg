/************************************************************************
 *									*
 *		�O���t�B�b�N�E�B���h�E���C�u����			*
 *									*
 *		����		: ���ʃf�[�^��`			*
 *		�t�@�C����	: dlib.c				*
 *		�쐬��		: s.aizawa				*
 *									*
 ************************************************************************/

#include "gwlib.h"

WINDOW	std_win;	/* �S��ʗp�E�B���h�E */
WINDOW	*scrp = &std_win;	/* �S��ʃ|�C���^ */
int	func_line = 23;	/* �t�@���N�V�����L�[�\���s */
WINDOW	*csr_wp;	/* �J�[�\���̑��݂���E�B���h�E�|�C���^ */
int	csr_sx;		/* �J�[�\���w���W */
int	csr_sy;		/* �J�[�\���x���W */
int	csr_flag;	/* �J�[�\���\���t���O */
int	g_fcolor = C_WHITE;	/* �����F */
int	g_bcolor = C_BLACK;	/* �w�i�F */
int	g_kcolor = C_BLUE;	/* �������͍s�̐F */
int	g_fkcolor = C_BLUE;	/* �t�@���N�V�����L�[�I�����̐F */
int	m_gx;		/* �}�E�X�w���W�i�O���t�B�b�N���W�j */
int	m_gy;		/* �}�E�X�x���W�i�O���t�B�b�N���W�j */
int	m_sel;		/* �}�E�X�I�����ڔԍ� */
int	m_csr_flag;	/* �}�E�X�J�[�\���\���t���O */
int	mouse_drv;	/* �}�E�X�h���C�o��� �i�O�F�m�d�b�@�P�F�A�X�L�[�j */
char	ank_font[256][16];	/* �`�m�j�t�H���g�o�b�t�@ */
void	(far interrupt *m_intp)();
struct key_buf key_data = {	/* �L�[�R�[�h */
	{
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0},
		{0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}, {0}
	},
	{
		{0}, {0}, {KEY_INS}, {KEY_DEL},
		{KEY_UP}, {KEY_LEFT}, {KEY_RIGHT}, {KEY_DOWN},
		{KEY_CLR}, {KEY_HELP}, {KEY_HOME}
	}
};