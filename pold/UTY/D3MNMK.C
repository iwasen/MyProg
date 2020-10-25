/*		FILE DESCREPTION

		FILE NAME: d3mnmk.c
		ROUTINE	 : MENU EDIT
		REVISION :
		REMARKS	 :		87.11.4		N.MATSUO
		REMARKS	 :		87.11.26	N.MATSUO
*/
#include	"d3mnmk.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

		FUNC.NAME: d3mnmk()
		INPUT	 : mmode	0 = no input string
					1 = input string
			   title	menu title
			   item		menu item
			   item_no	item number
			   func		function key disp. string
			   imes		input message
			   pno		string buffer
			   fno		push function no.
		OUTPUT	 : pno		strings
			   fno		push function no.
		REMARKS	 :		87.11.4		N.MATSUO
*/


d3mnmk(mmode, title, item, item_no, func, imes, pno, fno)
int	mmode, item_no;
char	*title, *item[], *imes, *func[], *pno;
chtype	*fno;
{
	int	syori_no, i, l, n, x, y, z, set;
	chtype	ek;


	d3dred();			/* dir_name set */

	syori_no = 1 ;			/* syori_no initialize */

	*pno = NULL;

	l = item_no;

	mnmk01( syori_no, l, title, item );		/* menu hyouji */

	d3fcmk(func);			/* function key set */

	n = strlen(imes);

	for (;;) {

		x = n + 4 ;			/* x = cxxxs */

		y = 21 ;			/* y = line  21 lines fix */
		z = 3 ;				/* z = input charcter */

		mvwaddstr(pad, y, 2, imes);		/* input */

		prefresh(pad, 0, 0, 0, 0, l_num, 80);

		ek = d3chin(y, x, z, pno);	/* input strings */

		set = OFF ;			/* end key set */

		switch ( ek ) {
		case 0x0a :		/* push < CR > */
			if (*pno == NULL) {
				*pno = (char) syori_no;
				*(pno + 1) = NULL;
				*fno = '0';
				set =  ON;
			} else {
				i = atoi(pno);		/* ascii to integer */
				if ( i < 1 || i > item_no ) {
					if (mmode == 1) { /* mmode =1 OK */
						*fno = '0'; /* mmode =0 NG */
						set = ON ;
					} else
						beep();
				} else {
					*pno = (char) i ;
					*(pno + 1) = NULL;
					*fno = '0' ;
					set = ON ;

					/*mnmk01(i, l, title, item);*/
					/*prefresh(pad, 0, 0, 0, 0, l_num, 80);*/
				}
			}
			break;
			/* push < FUNCTION KEY > */
		case KEY_F(1) :
			if ( *func  != NULL) {
				*fno = '1';
				set = ON ;
			}
			break;
		case KEY_F(2) :
			if ( *(func + 1)  != NULL) {
				*fno = '2';
				set = ON ;
			}
			break;
		case KEY_F(3) :
			if ( *(func + 2)  != NULL) {
				*fno = '3';
				set = ON ;
			}
			break;
		case KEY_F(4) :
			if ( *(func + 3)  != NULL) {
				*fno = '4';
				set = ON ;
			}
			break;
		case KEY_F(5) :
			if ( *(func + 4)  != NULL) {
				*fno = '5';
				set = ON ;
			}
			break;
		case KEY_F(6) :
			if ( *(func + 5)  != NULL) {
				*fno = '6';
				set = ON ;
			}
			break;
		case KEY_F(7) :
			if ( *(func + 6)  != NULL) {
				*fno = '7';
				set = ON ;
			}
			break;
		case KEY_F(8) :
			if ( *(func + 7)  != NULL) {
				*fno = '8';
				set = ON ;
			}
			break;
		case KEY_F(17):		/* 強制終了 */
			*fno = 'A';
			set = ON;
			break;
		case KEY_F(18):		/* 正常終了 */
			*fno = 'E';
			set = ON;
			break;
			/* push up_key or down_key */
		case KEY_UP:
		case KEY_F(21):		/* 上フィールド */
			if ( syori_no <= 1)	/* syori_no = 1 */
				syori_no = item_no;
			else
				syori_no = syori_no - 1;
			mnmk01(syori_no, l, title, item);
			break;
		case KEY_DOWN:
		case KEY_F(22):		/* 下フィールド */
			if ( syori_no >= item_no )
				syori_no = 1;
			else
				syori_no = syori_no + 1;
			mnmk01(syori_no, l, title, item);
			break;
		}
		if ( set ==  ON )
			break;

	}
	mnmk01( 0, l, title, item );		/* menu hyouji */
	prefresh(pad, 0, 0, 0, 0, l_num, 80);

}


static
mnmk01(no, i_no, str1, str2)			/*		*/
int	no, i_no;
char	*str1, *str2[];
{
	int	i;

	mvwaddstr(pad, 3, 15, str1);

	for ( i = 1 ; i <= i_no ; i++) {	/* item */
		wmove( pad, 4 + i , 9 );
		if ( i == no ) {		/* reverse of string */
			wattrset(pad, A_REVERSE);
			waddstr(pad, *str2);
			wattrset(pad, 0);
		} else {
			waddstr(pad, *str2);
		}

		str2++;
	}
}


