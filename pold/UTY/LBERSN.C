#include	<jcurses.h>

extern	WINDOW	*pad;
extern	int	l_num;

lbersn(errcd, argc, argv)
char	*errcd;
int	argc;
char	*argv[];
{
	int	i;
	char	buf[40];

	sprintf(buf, "errcd=%s argc=%d", errcd, argc);
	if (argc != 0) {
		strcat(buf, " argv=");
		for (i = 0; i < argc; i++) {
			strcat(buf, " ");
			strcat(buf, argv[i]);
		}
	}
	mvwaddstr(pad, l_num-2, 0, buf);
	pnoutrefresh(pad,l_num-2,0,l_num-2,0,l_num-2,80); /* display screen */
}
