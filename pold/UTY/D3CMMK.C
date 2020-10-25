/*			FILE DESCREPTION

			FILE NAME: d3cmmk.c
			ROUTINE	 : MENU EDIT
			REVISION :
			REMARKS	 :		87.10.20	N.MATSUO

*/
#include	"d3cmmk.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*		FUNC. DESCRIPTION

			FUNC.NAME: d3cmmk()
			INPUT	 : none
			OUTPUT	 : none
			REMARKS	 :		87.10.20	N.MATSUO
*/


d3cmmk(mmode,title,item,item_no,func,imes,pno,fno)
int mmode,item_no;
char	*title,*imes;
char	*func[];
char	*pno,*item;
chtype	*fno;
{
	int syori_no,l,n,x,y,z,set;
	chtype	ek;
	char 	*pc1,  *pc2 ;

	wmove(pad,2,0);
	wclrtobot(pad);			/* clear screen */

	d3dred();			/* dir_name set */

	syori_no = 1 ;			/* syori_no initialize */

	l=item_no;

	cmmk01( syori_no,l,title,item );		/* menu hyouji */
	d3fcmk(func);			/* function key set */

	n = strlen(imes);

	for (;;) {

		x = n + 4 ;			/* x = cxxxs */

		y = l_num - 3 ;			/* y = line */

		z = 4 ;				/* z = input length */

		mvwaddstr(pad,y,2,imes);	/* input message */

		prefresh(pad,0,0,0,0,l_num,80);

		ek = d3chin( y , x , z ,pno );	/* input strings */

		set = OFF ;			/* end key set */

		switch( ek ) {
		case 0x0a :		/* push < CR > */
			if (*pno == NULL) {
				pc2 = pno ;
				pc1 = item + (syori_no -1) * 50 ;
				while (*pc1 == ' ')  pc1++ ;
				while ('0' <= *pc1 && *pc1 <= '9')
					*pc2++ = *pc1++  ;
				*pc2 = NULL  ;
				*fno = '0';
				set =  ON;
			} else {
				if (mmode == 1) {	    /* mmode =1 OK */
					*fno = '0';
					set = ON ;
				}

			}
			break;
			/* push < FUNCTION KEY > */
		case KEY_F(1):
			if ( *func != NULL) {
				*fno = '1';
				set = ON ;
			}
			break;
		case KEY_F(2):
			if ( *(func + 1) != NULL) {
				*fno = '2';
				set = ON ;
			}
			break;
		case KEY_F(3):
			if ( *(func + 2) != NULL) {
				*fno = '3';
				set = ON ;
			}
			break;
		case KEY_F(4):
			if ( *(func + 3) != NULL) {
				*fno = '4';
				set = ON ;
			}
			break;
		case KEY_F(5):
			if ( *(func + 4) != NULL) {
				*fno = '5';
				set = ON ;
			}
			break;
		case KEY_F(6):
			if ( *(func + 5) != NULL) {
				*fno = '6';
				set = ON ;
			}
			break;
		case KEY_F(7):
			if ( *(func + 6) != NULL) {
				*fno = '7';
				set = ON ;
			}
			break;
		case KEY_F(8):
			if ( *(func + 7) != NULL) {
				*fno = '8';
				set = ON ;
			}
			break;
			/* push next_page or prev_page */
		case KEY_NPAGE:
		case KEY_PPAGE:
			*fno = ek ;
			set = ON;
			break;
			/* push up_key & down_key */
		case KEY_UP:
			if ( syori_no <= 1)	/* syori_no = 1 */
				syori_no = item_no;
			else
				syori_no = syori_no - 1;
			cmmk01(syori_no,l,title,item);
			break;
		case KEY_DOWN:
			if ( syori_no >= item_no )
				syori_no = 1;
			else
				syori_no = syori_no + 1;
			cmmk01(syori_no,l,title,item);
			break;
		}
		if ( set ==  ON )
			break;
	}
}


static
cmmk01(no,i_no,str1,str2)			/*		*/
int no,i_no;
char	*str1;
char	*str2;
{
	int 	i;

	mvwaddstr(pad,3,15,str1);		/* title */

	for ( i = 1 ; i <= i_no ; i++ ) {	/* item */
		wmove( pad, 4+i ,7 );
		if ( i == no ) {		/* reverse of string */
			wattrset(pad,A_REVERSE);
			waddstr(pad,str2);
			wattrset(pad,0);
		} else {
			waddstr(pad,str2);
		}

		str2 += 50;

		if (*str2 == NULL)
			break;
	}
}
