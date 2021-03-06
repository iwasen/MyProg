/*		FILE	DESCRIPTION

			FILE NAME: d3pdmk.c
			ROUTINE	 : PAD MAKE
			REVISION :
			REMARKS	 :		87.10.20	N.MATSUO

*/

#include	"d3pdmk.h"	

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

			FUNC. NAME:
			INPUT	  : 0 = menu 
				    1 = itiran
			output	  : 0 = OK
				    1 = ERROR
			REMARKS	  : 		87.10.20	N.MATSUO

*/
d3pdmk(ptype)
int	ptype;
{
	char	*dlp,*dcp,*getenv();
	int	i,j,ln;

	dlp = getenv("Y_DLINE");	/* Y_DLINE = line */
	dcp = getenv("Y_DCOL");		/* Y_DCOL = cxxx */
	if (dlp == 0 || dcp == 0) {
		slk_clear();
		endwin();
		fprintf(stderr, "環境変数が設定されていません  (Y_DLINE,Y_DCOL)\n\n");
		exit(-1);
	}
	i = atoi(dlp);
	j = atoi(dcp);


	if(ptype == 0){			/* ptype == 0 => ｺﾃｲﾋｮｳｼﾞ */

		pad = newpad(24,80);	/* initialize for pad */
		l_num = i;		/* l_num = Y_DLINE */

		return(0);		/* noerror */
	}
	else{

		if(LINES < 9){		/* error */

			printf("        一覧表示できません");

			return(1);

		}
		else{

			l_num = LINES + 1 ;

			ln = (LINES > i) ? LINES : i;

			pad = newpad(ln,80);	/* initialize for pad */

			return(0);
		}
	}
}

