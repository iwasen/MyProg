/*	FILE DESCRIPTION

		FILE NAME	: d3slmt.c
		ROUTINE		: ���������x�����̕ێ�
		REVISION	:
		REMARKS		: created	88.06.15	s.aizawa
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h"
#include	"d3data.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3slmt()
		ROUTINE		: ���������x�����̕ێ�
		INPUT		: flag  = 0 �������̕ێ�
				:       = 1 ���x�����̕ێ�
				: lads  = �k�`�f�[�^�Z�b�g��
				: title = �^�C�g�����b�Z�[�W
		OUTPUT		: none
*/
d3slmt(ladir, lads, flag, title)
char	*ladir;
char	*lads;
int	flag;
char	*title;
{
	int	err;
	char	flag_s[4];

	endwin();

	sprintf(flag_s, "%d", flag);
	err = (d3exec("d0_slhn", ladir, lads, flag_s, title, 0) >> 8) & 0xff;

	if (err != 0 && err != E_E)
		d3errw(err);

	return(err);
}