#include		"d3edds.h"

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

d3edds(edtnam)
char	*edtnam;
{
	FILE	*f_dum;
	int	i,j,m,n,c_ctr;
	char	str[1025],*p1;


	werase(pad);
	waddstr(pad,"＜ ファイル名 ：");
	waddstr(pad,edtnam);
	waddstr(pad," ＞");

	if((f_dum = fopen(edtnam,"r")) == NULL){	/* file open error */
				return(E_TF_A);
	}

	wmove(pad,4,0);
	waddstr(pad,"**** edit file ****************************************************************\n");
	i = 0 ;
	while(i < 15){
		if(fgets(str,1024,f_dum)==NULL)		/*1 line read */
			break;

		n = strlen(str);

		c_ctr = 0;				/* tab counter init. */

		waddstr(pad,str);			/* display	*/

		for(j=0;j<n;j++){			/* tab count */
			c_ctr = c_ctr + 1 ;

			if(str[j] == '\t'){
/*				if(c_ctr > 8){
					m = c_ctr / 8 ;	
					c_ctr = ((m + 1 ) * 2 );
				}
				else 	c_ctr = 8;		*/
				c_ctr = (c_ctr+8) / 8 * 8;
			}

		}
	if(c_ctr > 80)					/* line get */
		i = i + (c_ctr / 80) + 1 ;
	else
		i++;

	}



	wmove(pad,20,0);
	wclrtobot(pad);
	wmove(pad,20,0);
	waddstr(pad,"************************************************************* edit file ******");	

	pnoutrefresh(pad,0,0,0,0,l_num,80);
	fclose(f_dum);
	return(NOERR);
}

