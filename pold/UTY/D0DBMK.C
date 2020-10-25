/*		file description

			file name: d0dbmk.c
			routine	 : la data base make 
			remarks	 : 		87.11.10	N.MATSUO

*/
#include	"d0dbmk.h"

#define		DIR_MODE	0750		/* ladb directory mode */
#define		LP		0x10
#define		ED		0x11
#define		DC		0x12
#define		GO		0x14

WINDOW		*w1;				/* subpad for error message */
jmp_buf		env;

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	*func[8]={NULL,NULL,"はい","いいえ",NULL,"dir 変更",NULL,NULL};
static	char	*func_yn[8]={NULL,NULL,"はい","いいえ",NULL,NULL,NULL,NULL};
main()
{
	int	i,s_sel;
	char	dir_buf[100];

	d3sgnl();			/* シグナル処理設定 */

	if (setjmp(env))	goto startscr;

	lbzzfs();
	lbzzsc();
	slk_init(1);			/* screen initialize */
	initscr();

	if ((i = d3pdmk(1)) != NOERR) {		/* pad make */
		slk_clear();
		endwin();
		resetterm();
		exit(i);
	}

	w1 = subpad(pad,1,49,l_num-2,30);	/* subpad for error message */

	for (;;) {

		dbmk01();			/* initialize */
startscr:
		s_sel = dbmk02(dir_buf);

		if (s_sel == ED)			/* select chusi */
			break;

		if (s_sel == DC) {			/* select dir_change */
			slk_clear();
			endwin();
			system("d0_drcg");
		}
		if (s_sel == GO) {			/* select keizoku */
			dbmk03(dir_buf);
		}

	}

	clear();
	slk_clear();
	refresh();
	endwin();
	resetterm();

	exit(NOERR);
}

/*		func.description

		func.name: dbmk01()
		input	 : none
		output	 : none
		routine	 : initialize for screen
		remarks  : 		87.11.12	N.MATSUO

*/
dbmk01()
{
	clear();			/* screen clear */
	wnoutrefresh(stdscr);
	werase(pad);
	pnoutrefresh(pad,0,0,0,0,l_num,80);

	d3pned(1);			/* syori name display	*/

	if (d3dred() != NOERR) {		/* dir name display	*/
		d3msed(w1,E_TF_A);
		prefresh(w1,0,0,l_num-2,30,l_num-2,79);
	}

	d3fcmk(func);			/* function key set */
}

/*		func.description

		func.name: dbmk02()
		input	 : char *dir_buf -> dir_name buffer
		output	 : none
		routine	 : directory find & make / nyuryoku machi
		remarks  : 		87.11.12	N.MATSUO

*/

dbmk02(dir_buf)
char	*dir_buf;
{
	struct	stat	buff;
	int	dir_flg,s_sel,x,y;
	chtype	ek;
	char	dummy_string[10];

	d3drrd(dir_buf);		/* dir name read */

	if (stat(dir_buf,&buff) != 0) {		/* dir name not found */
		mvwaddstr(pad,21,2,"ディレクトリが存在しません．生成しますか （はい／いいえ）");
		dir_flg = 0 ;			/* dir flag */
	} else {
		mvwaddstr(pad,21,2,"グループ索引ファイルの生成／更新を行いますか（はい／いいえ）");
		dir_flg = 1;
	}

	getyx(pad,y,x);				/* cursol pointer get */

	prefresh(pad,0,0,0,0,l_num,80);

	for (;;) {

		ek = d3chin(y,x,0,dummy_string);	/* input function key */

		switch (ek) {
		case KEY_F(3):		/* OK	*/
			s_sel = GO ;	/* select JIKKO */
			if (dir_flg == 0) {
				if (dbmk10(dir_buf,DIR_MODE) != 0) {
					d3msed(w1,E_N);
					prefresh(w1,0,0,l_num-2,30,l_num-2,79);
					beep();
					longjmp(env,1);
				}
			}	
			break;
		case KEY_F(4):		/* NO GOOD */
			s_sel = ED;	/* select END */
			break;
		case KEY_F(6):		/* dir change */
			s_sel = DC ;	/* select Dir Change */
			break;
		default	:
			beep();
			s_sel = LP ;		/* select LOOP */
		}

		if (s_sel != LP )
			break;
	}

	return(s_sel);
}

/*		func.description

		func.name: dbmk03()
		input	 : char *d_name		dir_name
		output	 : none
		routine	 : group sakuin edit
		remarks  : 		87.11.12	N.MATSUO

*/

dbmk03(dir_name)
char	*dir_name;
{
	char	cmdln1[512],cmdln2[512],temp_name[100],*s;
	int	err;
	int	rtn ;

	s = NULL;

	strcpy(temp_name,tmpnam(s));	/* temporary file name get */

	sprintf ( cmdln1 , "%s %s %s %s %s","d1_gsgt",dir_name,">",temp_name,
					"2>/dev/null" ) ;
	sprintf ( cmdln2 , "%s %s","jvi",temp_name ) ;

	err = (system(cmdln1)) >> 8;		/* command for data base make */
	if (err != NOERR) {
		slk_init(1);
		initscr();
		d3msed(w1,err);
		prefresh(w1,0,0,l_num-2,30,l_num-2,79);
		beep();
		d3wkey(pad);
		return;
	}
	for ( ;; ) {
		clear();
		/*refresh(); */
		slk_clear();
		refresh();
		endwin();
		system(cmdln2);				/* command for editor */
		slk_init(1);
		initscr();
		clear();
		wnoutrefresh(stdscr);
		werase(pad);
		pnoutrefresh(pad,0,0,0,0,l_num,80);
		d3pned(1);
		d3dred();

		mvwaddstr(pad,3,10,"エディット データ");	/* edit data hyouji */

		d3edds(temp_name);

		wmove(pad,21,0);
		wclrtoeol(pad);

		mvwaddstr(pad,21,2,"グループ索引ファイルに登録しますか（はい／いいえ）");

		if (dbmk04() == ON) {
			rtn = dbmk05(dir_name,temp_name);
			if ( rtn == NOERR ) break ;
		} else break ;
	}

	unlink(temp_name);				/* temp_file delete */

}
/*		func.discripsion

		func.name: dbmk04()
		input	 : none
		output	 : ON		syori ari
			   OFF		syori nasi
		routine	 : function key nyuuryoku machi
		remarks	 :		87.11.12	N.MATSUO

*/

dbmk04()
{
	chtype	ek;
	char	dummy_string[10];			/* dummy string */

	d3fcmk(func_yn);

	for (;;) {

		ek = d3chin(21,52,0,dummy_string);	/* nyuuryoku mati */

		if (ek == KEY_F(4) || ek == KEY_F(3))
			break;
		else		beep();

	}

	if (ek == KEY_F(3))
		return(ON);
	else
		return(OFF);

}

/*		func.descripsion

		func.name: dbmk05()
		input	 : char *dir_name	directry name
			   char	*file_name	file name
		output	 : none
		routine	 : group sakuin make
		remarks	 :		87.11.12	N.MATSUO

*/

dbmk05(dir_name,file_name)
char	*dir_name,*file_name;
{
	char	cmdln[512];
	int	rtn;

	strcpy(cmdln,"d1_gsrg ");		/* group sakuin touroku */
	strcat(cmdln,dir_name);			/* dir_name */
	strcat(cmdln," ");
	strcat(cmdln,file_name);		/* temp_file name */
	strcat(cmdln," 2>/dev/null ");		/*#append 880205*/

	clear();
	slk_clear();
	refresh();
	endwin();
	rtn = (system(cmdln)) >> 8 ;

	slk_init(1);
	initscr();
	d3pned(1);
	d3dred();

	if (rtn != NOERR) {		/* make error */
		d3msed(w1,rtn);	
		prefresh(w1,0,0,l_num-2,30,l_num-2,79);
		beep();
		d3wkey(pad);
	}
	return ( rtn ) ;
}

/*		func.descripsion

		func.name: dbmk10()
		input	 : char *dir_name	directry name
		output	 : error code
		routine	 : make data base directory
		remarks	 :		87.11.12	N.MATSUO

*/
dbmk10(dir_name,dir_mode)
char	*dir_name;
int	dir_mode;
{
	char	dir_buf[256];
	char	*p;
	struct	stat	stat_buf;

	strcpy(dir_buf, dir_name);

	p = dir_buf;
	if (*p == '/')
		p++;

	for (;;) {
		if ((p = strchr(p, '/')) == 0)
			break;
		*p = '\0';
		if (stat(dir_buf, &stat_buf) != 0)
			break;
		*p++ = '/';
	}

	for (;;) {
		if (mkdir(dir_buf, dir_mode) != 0)
			return(1);

		if (p == 0)
			break;

		*p++ = '/';
		if ((p = strchr(p, '/')) != 0)
			*p = '\0';
	}
	return(0);
}

