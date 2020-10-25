/*		FILE DESCRIPTION

			FILE NAME: d3chin.c
			ROUTINE	 : input strings
			REVISION :
			REMARKS	 :		87.10.20	N.MATSUO

*/
#include	"d3chin.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC.DESCRIPTION

			FUNC.NAME: last = d3chin()
			INPUT	 : x		lines
				   y		clums
				   ncol 	string number
				   str		string buffer
			OUTPUT	 : last		push last key
				   str		string
			REMARKS	 :		87.10.20	N.MATSUO

*/

chtype d3chin(y,x,ncols,str)
int	x,y,ncols;
unsigned char	*str;
{
WINDOW	*w3;
chtype	last;
int	i,j,k,p_y,p_x;
char	input_str[100],*p1;

	if ( ncols == 0 ) {
		noecho();
		cbreak();
		meta(pad,TRUE);
		keypad(pad,TRUE);
		wmove(pad,y,x);			/* pointer for pad */
		prefresh(pad,0,0,0,0,l_num,80);
		for (;;) {
			i=lbmmgc(pad);			/* input */
			if (i == KEY_F(16)) {
				clearok(curscr, TRUE);
				wrefresh(curscr);
			} else
				break;
		}
		echo();
		return( (chtype) i );

	} else {
		w3 = subpad( pad , 1 , ncols ,  y , x );

		echo();				/* option */
		cbreak();
		meta(w3,TRUE);
		keypad(w3,TRUE);

		strcpy(input_str,str);	/* string copy original to dummy */
	
		p_y = y ;  p_x = x ;		/* screen pointer for subpad */

		for (;;) {
			last = lbmmle ( w3 ,&p_y , &p_x , input_str );

			if (last == -1) {		/* curses error */
				j = 0x06;
				d3errw(j);
				endwin();
				exit( j );
			} else if (last == KEY_F(16)) {
				clearok(curscr, TRUE);
				wrefresh(curscr);
			} else
				break;
		}

		k = strlen( input_str );

		if ( k != 0 ) {

			k = k - 1;

			while( *(input_str + k ) == 0x20 ) { /* space => NULL */

				*(input_str + k ) = NULL;
				if ( k <= 0)
					break;
				k--;

			}
		}
		p1 = strrchr(input_str,0x20);
		if (*p1 != NULL)
			p1 = p1 + 1 ;
		else	p1 = input_str;

		strcpy(str,p1);

		wmove(w3,0,0);
		wclear(w3);
		waddstr(w3,str);
		prefresh(w3,0,0,y,x,y,x + ncols);
		delwin(w3);
		return( last );
	}
}

d3wkey(pad)
WINDOW	*pad;
{
	int	c;

	noecho();
	cbreak();
	meta(pad,TRUE);
	keypad(pad,TRUE);

	c = lbmmgc(pad);
	echo();
}

