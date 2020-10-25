/*	FILE DESCRIPTION

		FILE NAME	: d3fsel.c
		ROUTINE		: �t�@�C�����ꗗ�\�����I��
		REVISION	:
		REMARKS		:	88.05.20	S.Aizawa
*/

#include	<jcurses.h>
#include	<string.h>
#include	<sys/types.h>
#include	<sys/stat.h>
#include	"d3item.h"
#include	"d3data.h"
#include	"la_ws/include/d2lerr.h"
#include	"la_ws/include/d3libc.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3fsel.c
		INPUT		: path_name = �\������f�B���N�g���܂��̓t�@�C��
		OUTPUT		: file_name = �I�����ꂽ�t�@�C����
				: flag      = 0:���ʂ��t�@�C�����ŕԂ�
				:           = 1:���ʂ��p�X���ŕԂ�
				: return value = error code
*/

d3fsel(path_name, file_name, flag)
char	*path_name;
char	*file_name;
int	flag;
{
	static	char	name[] = "�t�@�C��";

	return(d3fslc(path_name, file_name, flag, name));
}


/*	FUNCTION DESCRIPTION

		FUNC.NAME	: d3fslc.c
		INPUT		: path_name = �\������f�B���N�g���܂��̓t�@�C��
		OUTPUT		: file_name = �I�����ꂽ�t�@�C����
				: flag      = 0:���ʂ��t�@�C�����ŕԂ�
				:           = 1:���ʂ��p�X���ŕԂ�
				: name      = �\�����閼�O
				: return value = error code
*/

d3fslc(path_name, file_name, flag, name)
char	*path_name;
char	*file_name;
int	flag;
char	*name;
{
	static	char	*func[8] = { 	/* description of func-key */
		NULL, NULL, NULL, NULL,
		NULL, NULL, NULL, NULL
	};
	int	mgno, err, cl_n, c_pos, i;
	D_CELL	*file_p;
	chtype	ch;
	char	*p;
	struct	stat	st_buf;
	char	dir_name[128];

	file_name[0] = '\0';

	if ((mgno = d3mgno()) < 0)
		return(E_M);

	wmove(pad, l_num-3, 2);
	wclrtoeol(pad);
	wprintw(pad, "%s������  ", name);
	prefresh(pad,0,0,0,0,l_num,80); /* display screen */

	if ((err = d3list(path_name, mgno, &file_p, &cl_n, dir_name)) != 0) {
		d3free(mgno);
		return(err);
	}

	wmove(pad, 3, 0);
	wclrtobot(pad);
	mvwprintw(pad, 3, 26, "%s���ꗗ", name);

	wmove(pad, l_num-3, 2);
	wclrtoeol(pad);
	wprintw(pad, cl_n ? "%s��I�����Ă�������  " : "�Y������%s�͂���܂���  ", name);

	d3fcmk(func);
	c_pos = 0;
	for (;;) {
		ch = d3item(cl_n, file_p, 20, 0, &c_pos);
		switch (ch) {
		case 0x0a:
			if (cl_n != 0) {
				if (flag == 0)
					strcpy(file_name, file_p[c_pos].ptr);
				else {
					strcpy(file_name, dir_name);
					if (strcmp(dir_name, "/") != 0)
						strcat(file_name, "/");
					strcat(file_name, file_p[c_pos].ptr);
				}
			}
			d3free(mgno);
			return(0);
		case KEY_F(17):		/* �����I�� */
			d3free(mgno);
			return(E_E);
		}
	}
}