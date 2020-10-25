/************************************************************************
 *									*
 *		�O���t�B�b�N�E�B���h�E���C�u����			*
 *			�T���v���v���O����				*
 *									*
 ************************************************************************/

/*--------------------------------------------------------------*/
/*	�W���C���N���[�h�t�@�C�� 				*/
/*--------------------------------------------------------------*/
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dos.h>

/*--------------------------------------------------------------*/
/*	�O���t�B�b�N�E�B���h�E���C�u�����p�C���N���[�h�t�@�C��	*/
/*	�K���C���N���[�h���邱��				*/
/*--------------------------------------------------------------*/
#include "gwlib.h"

/*--------------------------------------------------------------*/
/*	���[�h�̒�`		 				*/
/*--------------------------------------------------------------*/
#define	M_MAKE	0
#define	M_DEL	1
#define	M_PICK	2
#define	M_MOVE	3
#define	M_END	6

/*--------------------------------------------------------------*/
/*	�����֐��̒�`		 				*/
/*--------------------------------------------------------------*/
static	void	menu(void);
static	void	title(void);
static	void	make(int);
static	int	set_color_c(void);
static	int	set_color_w(void);

/*--------------------------------------------------------------*/
/*	�E�B���h�E���ɕ\������f�[�^ 				*/
/*--------------------------------------------------------------*/
static	char	*mstr[] = {
	"1 MSDOS.SYS",
	"2 IO.SYS",
	"3 COMMAND.COM",
	"4 CONFIG.SYS",
	"5 AUTOEXEC.BAT",
	"6 FORMAT.EXE",
	"7 ASSIGN.EXE",
	"8 DISKCOPY.EXE",
};

/*--------------------------------------------------------------*/
/*	�X�^�e�B�b�N�ϐ��̒�`	 				*/
/*--------------------------------------------------------------*/
static	int	mode;			/* ���݂̃��[�h */
static	int	color_w = C_WHITE;	/* �����F */
static	int	color_c = C_BLACK;	/* �w�i�F */

/*=======================================================================
 *
 *		���C������
 *
 *	void	main()
 *
 =======================================================================*/
main(int argc, char *argv[])
{
	void	menu(), make();
	WINDOW	*wp;
	int	loop_sw, mouse;
	int	n = 0;

	/* �p�����[�^�`�F�b�N */
	switch (argc) {
	case 1:
		mouse = 0;
		break;
	case 2:
		if (strcmp(argv[1], "1") == 0)
			mouse = 1;
		else {
			printf("�p�����[�^���Ⴂ�܂��D\n");
			exit(1);
		}
		break;
	default:
		printf("�p�����[�^�̐����Ⴂ�܂��D\n");
		exit(1);
	}

	w_initscr(8, 16, mouse, "ANK.FNT");	/* ���������� */
/*                �E   �E    �E        �E
                  �E   �E    �E        �W�C�C�C �`�m�j�t�H���g�t�@�C����
                  �E   �E    �W�C�C�C�C�C�C�C�C�C�C�C�C �}�E�X�h���C�o���
                  �E   �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �P�s�̃h�b�g��
                  �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �P���̃h�b�g��
*/
	title();		/* �^�C�g���\�� */

	loop_sw = 1;
	while (loop_sw) {
		switch (w_input(scrp, 0)) {	/* �P�������� */
/*                               �E    �E
                                 �E    �W�C�C�C�C �����J�[�\���\���Ȃ�
                                 �W�C�C�C�C�C�C�C�C�C �S��ʃ|�C���^
*/
		case MOUSE_L:
			switch (mode) {
			case M_MAKE:
				make(++n);
				break;
			case M_DEL:
				if ((wp = w_check()) != 0)
					w_close(wp);
				break;
			case M_PICK:
				if ((wp = w_check()) != 0)
					w_popup(wp);
				break;
			case M_MOVE:
				if ((wp = w_check()) != 0)
					w_xmove(wp);
				break;
			}
			break;
		case MOUSE_R:
			menu();
			if (mode == M_END)
				loop_sw = 0;
			break;
		case KEY_ESC:
			loop_sw = 0;
			break;
		}
	}

	w_endscr();		/* �I������ */

	return(0);
}
/*=======================================================================
 *
 *		�^�C�g���\��
 *
 *	void	title()
 *
 =======================================================================*/
static	void	title(void)
{

	static	char	tile[12] = {
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa,
		0xff, 0x55, 0x55,
		0xff, 0x00, 0xaa
	};

	g_line(0, 0, 639, 399, 0, 2, 2, 0, 12, tile);

	set_color(C_BLUE, C_WHITE);	/* �F�ݒ� */
/*                  �E        �E
                    �E        �W�C�C�C �w�i�F�i���j
                    �W�C�C�C�C�C�C�C�C�C�C�C�C �����F�i�j
*/
	/* ������\�� */
	w_dspstr(scrp, 18, 0, "*** GRAPHIC WINDOW LIBRARY DEMONSTRATION ***");
/*                �E     �E  �E     �E
                  �E     �E  �E     �W�C�C�C �\��������
                  �E     �E  �W�C�C�C�C�C�C�C�C�C �s�ʒu
                  �E     �W�C�C�C�C�C�C�C�C�C�C�C�C ���ʒu
                  �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �S��ʃ|�C���^
*/
}

/*=======================================================================
 *
 *		���j���[����
 *
 *	void	menu()
 *
 =======================================================================*/
static	void	menu(void)
{
	static	char	title[] = "���j���[";
	static	char	*item[] = {
		"��    ��",		/* 0 */
		"��    ��",		/* 1 */
		"�����グ",		/* 2 */
		"��    ��",		/* 3 */
		"�� �� �F",		/* 4 */
		"�w �i �F",		/* 5 */
		"�I    ��"		/* 6 */
	};
	WINDOW	*wp;
	static	int	sel = 0;
	int	csel;
	int	loop_sw;
	int	sx, sy;

	sx = m_gx - 13 * 8 / 2;
	sy = m_gy - 10 * 18 / 2;

	/* �E�B���h�E�I�[�v�� */
	if ((wp = w_open(sx, sy, 13, 9, 8, 18)) == 0) {
/*                        �E   �E   �E  �E  �E   �E
                          �E   �E   �E  �E  �E   �W�C�C�C �P�s�̃h�b�g��
                          �E   �E   �E  �E  �W�C�C�C�C�C�C�C �P���̃h�b�g��
                          �E   �E   �E  �W�C�C�C�C�C�C�C�C�C�C �s��
                          �E   �E   �W�C�C�C�C�C�C�C�C�C�C�C�C�C ����
                          �E   �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �E�B���h�E����̂x���W
                          �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �E�B���h�E����̂w���W
*/
		mode = M_DEL;
		return;
	}

	/* �E�B���h�E�\�� */
	w_stdwin(wp, title, C_BLACK, C_YELLOW, C_BLACK);
/*                �E    �E      �E        �E         �E
                  �E    �E      �E        �E         �W�C�C�C �e�̐F�i���j
                  �E    �E      �E        �W�C�C�C�C�C�C�C�C�C�C�C�C�C �w�i�F�i���j
                  �E    �E      �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �����F�i���j
                  �E    �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �^�C�g��
                  �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �E�B���h�E�|�C���^
*/
	loop_sw = 1;
	while (loop_sw) {
		/* �����I�� */
		csel = w_select(wp, item, 1, 7, sel, C_BLACK, C_BLUE);
/*                              �E    �E    �E  �E   �E     �E        �E
                                �E    �E    �E  �E   �E     �E        �W�C�C�C �I�����̐F
                                �E    �E    �E  �E   �E     �W�C�C�C �����F
                                �E    �E    �E  �E   �W�C�C�C �ŏ��ɑI������Ă��鍀��
                                �E    �E    �E  �W�C�C�C�C�C�C�C �c�����̍��ڐ�
                                �E    �E    �W�C�C�C�C�C�C�C�C�C�C �������̍��ڐ�
                                �E    �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C ���ڕ�����
                                �W�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C�C �E�B���h�E�|�C���^
*/
		switch (csel) {       
		case 0:		/* �쐬 */
		case 1:		/* ���� */
		case 2:		/* �����グ */
		case 3:		/* �ړ� */
		case 6:		/* �I�� */
			mode = csel;
			loop_sw = 0;
			break;
		case 4:		/* �����F */
			if (set_color_c() != -1)
				loop_sw = 0;
			break;
		case 5:		/* �w�i�F */
			if (set_color_w() != -1)
				loop_sw = 0;
			break;
		case -1:
			loop_sw = 0;
			break;
		}

		if (csel != -1)
			sel = csel;
	}

	w_close(wp);	/* �E�B���h�E�N���[�Y */
/*               �E
                 �W�C�C�C �E�B���h�E�|�C���^
*/
}

/*=======================================================================
 *
 *		�E�B���h�E�쐬
 *
 *	void	make()
 *
 =======================================================================*/
static	void	make(int n)
{
	int	i;
	WINDOW	*wp;
	char	title[20];

	/* �E�B���h�E�I�[�v�� */
	if ((wp = w_open(m_gx, m_gy, 20, 10, 8, 18)) == 0)
		return;

	/* �E�B���h�E�\�� */
	sprintf(title, "WINDOW%d", n);
	w_stdwin(wp, title, C_BLACK, color_w, C_BLACK);

	/* �E�B���h�E���ɕ����\�� */
	set_color(color_c, color_w);
	for (i = 0; i < 8; i++)
		w_dspstr(wp, 2, i+1, mstr[i]);
}

/*=======================================================================
 *
 *		�����F�ݒ�
 *
 *	int	set_color_c()
 *
 =======================================================================*/
static	int	set_color_c(void)
{
	static	char	title[] = "�����F";
	static	char	*item[] = {
		"��",
		"��",
		"�V�A��",
		"��",
		"�}�[���^",
		"��",
		"��",
		"��"
	};
	static	int	sel = 0;
	int	csel;
	WINDOW	*wp;

	/* �����F�I���E�B���h�E�I�[�v�� */
	if ((wp = w_open(m_gx, m_gy, 13, 10, 8, 18)) == 0)
		return(-1);

	/* �E�B���h�E�\�� */
	w_stdwin(wp, title, C_WHITE, C_MAGENTA, C_BLACK);

	/* �����F�I�� */
	csel = w_select(wp, item, 1, 8, sel, C_WHITE, C_CYAN);
	switch (csel) {
	case 0:		/* �� */
		color_c = C_WHITE;
		break;
	case 1:		/* �� */
		color_c = C_YELLOW;
		break;
	case 2:		/* �V�A�� */
		color_c = C_CYAN;
		break;
	case 3:		/* �� */
		color_c = C_GREEN;
		break;
	case 4:		/* �}�[���^ */
		color_c = C_MAGENTA;
		break;
	case 5:		/* �� */
		color_c = C_RED;
		break;
	case 6:		/* �� */
		color_c = C_BLUE;
		break;
	case 7:		/* �� */
		color_c = C_BLACK;
		break;
	}

	if (csel != -1)
		sel = csel;

	w_close(wp);	/* �E�B���h�E�N���[�Y */

	return(csel);
}

/*=======================================================================
 *
 *		�w�i�F�ݒ�
 *
 *	int	set_color_w()
 *
 =======================================================================*/
static	int	set_color_w(void)
{
	static	char	title[] = "�w�i�F";
	static	char	*item[] = {
		"��",
		"��",
		"�V�A��",
		"��",
		"�}�[���^",
		"��",
		"��",
		"��"
	};
	static	int	sel = 0;
	int	csel;
	WINDOW	*wp;

	/* �w�i�F�I���E�B���h�E�I�[�v�� */
	if ((wp = w_open(m_gx, m_gy, 13, 10, 8, 18)) == 0)
		return(-1);

	/* �E�B���h�E�\�� */
	w_stdwin(wp, title, C_BLACK, C_CYAN, C_BLACK);

	/* �w�i�F�I�� */
	csel = w_select(wp, item, 1, 8, sel, C_BLACK, C_MAGENTA);
	switch (csel) {
	case 0:		/* �� */
		color_w = C_WHITE;
		break;
	case 1:		/* �� */
		color_w = C_YELLOW;
		break;
	case 2:		/* �V�A�� */
		color_w = C_CYAN;
		break;
	case 3:		/* �� */
		color_w = C_GREEN;
		break;
	case 4:		/* �}�[���^ */
		color_w = C_MAGENTA;
		break;
	case 5:		/* �� */
		color_w = C_RED;
		break;
	case 6:		/* �� */
		color_w = C_BLUE;
		break;
	case 7:		/* �� */
		color_w = C_BLACK;
		break;
	}

	if (csel != -1)
		sel = csel;

	w_close(wp);	/* �E�B���h�E�N���[�Y */

	return(csel);
}