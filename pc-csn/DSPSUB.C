/************************************************************************
 *									*
 *		�V�u�r�d�V�X�e���@�o�b�|�b�m�r�v���O����		*
 *									*
 *		����		: �c�r�o���b�Z�[�W�\���T�u���[�`��	*
 *		�t�@�C����	: dspsub.c				*
 *		�쐬��		: ??/??/??				*
 *									*
 ************************************************************************/

#include "pc_cns.h"

#define	ACT_UIB	0
#define	ACT_AUB	1
#define	ACT_BUS	2
#define	SBY_BUS	3
#define	ERR_BUS	4
#define	FRE_BUS	5

void	dsp_line(int, int, int, int, int);

void	dsp_eqm(int mode, int x, int y)
{
	switch (mode) {
	case 0:		/* ������ */
		dspstr(x, y, "��", C_BLUE);
		break;
	case 1:		/* �^�p�� */
		dspstr(x, y, "��", C_GREEN);
		break;
	case 2:		/* �ҋ@�� */
		dspstr(x, y, "��", C_WHITE);
		break;
	case 3:		/* ��Q */
		dspstr(x, y, "��", C_RED);
		break;
	}
}

void	dsp_bus(int mode, int bus, int x, int y)
{
	switch (mode) {
	case 0:		/* ������ */
		dsp_line(x-1, y, x+3, y, FRE_BUS);
		break;
	case 1:		/* �^�p�� */
		dsp_line(x-1, y, x+3, y, bus == 0 ? ACT_UIB : ACT_AUB);
		break;
	case 2:		/* �ҋ@�� */
		dsp_line(x-1, y, x+3, y, SBY_BUS);
		break;
	case 3:		/* ��Q */
		dsp_line(x-1, y, x+3, y, ERR_BUS);
		break;
	}
}

void	dsp_line(int x1, int y1, int x2, int y2, int type)
{
	x1 = x1 * 8;
	y1 = y1 * 16 + 8;
	x2 = x2 * 8;
	y2 = y2 * 16 + 8;

	switch (type) {
	case ACT_UIB:
		g_line(x1, y1-1, x2, y2-1, C_GREEN, 0, 1, 0xaa22);
		g_line(x1, y1, x2, y2, C_GREEN, 0, 1, 0x5445);
		g_line(x1, y1+1, x2, y2+1, C_GREEN, 0, 1, 0xa88a);
		g_line(x1, y1+2, x2, y2+2, C_GREEN, 0, 1, 0x5115);
		break;
	case ACT_AUB:
		g_line(x1, y1-1, x2, y2-1, C_GREEN, 0, 0);
		g_line(x1, y1, x2, y2, C_GREEN, 0, 0);
		g_line(x1, y1+1, x2, y2+1, C_GREEN, 0, 0);
		break;
	case ACT_BUS:
		g_line(x1, y1, x2, y2, C_GREEN, 0, 0);
		break;
	case SBY_BUS:
		g_line(x1, y1, x2, y2, C_WHITE, 0, 0);
		break;
	case ERR_BUS:
		g_line(x1, y1, x2, y2, C_RED, 0, 1, 0xf333);
		g_line(x1, y1+1, x2, y2+1, C_RED, 0, 1, 0xf333);
		break;
	case FRE_BUS:
		g_line(x1, y1, x2, y2, C_BLUE, 0, 1, 0xe3e3);
		break;
	}
}
