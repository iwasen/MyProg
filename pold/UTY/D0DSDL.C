/*		FILE DESCRIPTION

			file name: d0dsdl.c
			routine	 : LA data set delete
			revision :
			remarks	 :		87.11.18	N.MATSUO

*/
#include	"d0dsdl.h"
/*  status flag 	*/
#define		LP	0		/*  not escape loop	*/
#define		GO	1		/*  escape for loop	*/
#define		JK	2		/*  jouken kensaku	*/
#define		DC	3		/*  directory change	*/
#define		ED	4		/*  end for process	*/

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	jmp_buf	env;

static	char	*func[8] = {		/* function key for menu */
	NULL, NULL, NULL, NULL,
	"条件検索", "file一覧", "LADB変更", NULL
};

static	char	*func2[8] = {		/* function key for syori */
	NULL, NULL, NULL, NULL,
	NULL, NULL, NULL, NULL
};

/****************************************************************/
/*			main processing				*/
/****************************************************************/

d0dsdl()
{

	int	m_ctr,s_sts,s_sel;
	char	selectname[48];
	char	tmp_name[100];
	FILE	*f_crnt;
	char	*p1,*r_err;
	int	i,j;

	/*	initialize	(parameter,counter,temp.file)	*/

	dsdl01(&f_crnt,tmp_name,&m_ctr,&s_sts,&s_sel);

	/*	error (temporary file access error) handling	*/

	setjmp(env);

	for (;;) {

		d3pned(8);			/* p_name display */

		d3dred();			/* dir_name display */

		/* 	renzoku delete		*/

		if (s_sts == 2) {			/* 条件検索 */
			if (feof(f_crnt) || (r_err=fgets(selectname,20,f_crnt)) == NULL) {
				d3keka(f_crnt, rst_tbl);/* keka disply */
				for (j=0;j<RST_MAX;j++)	/* table init */
					rst_tbl[j] = E_CLEAR ;

				m_ctr = s_sts = 0 ;	/* counter & status init */
				wmove(pad,2,0);
				wclrtobot(pad);		/* clear screen */
			} else {
				p1 = strchr(selectname,0x0a);	/* \n -> NULL */
				*p1 = NULL;
			}
		}

		/*	tandoku delete		*/

		if (s_sts != 2) {

			selectname[0] = NULL;
			for (;;) {

				/*	nyuryoku routine	*/
				s_sel = dsdl04(selectname,&s_sts,tmp_name,&f_crnt);

				if (s_sel != LP)	/* syori joken */
					break;
			}

			if (s_sel == ED)		/* end of process */
				break;
			if (s_sel == JK) {		/* joken kensaku */

				fgets(selectname,20,f_crnt);	/* 1 line read */
				p1 = strchr(selectname,'\n');
				*p1 = NULL;
			}
			if (s_sel == DC) {
				d3dred();
			}

		}

		if (s_sel != DC && s_sts != 0) {
			dsdl02(&m_ctr,selectname,&s_sts,&f_crnt);
		}
	}

	fclose(f_crnt);				/* file close */
	unlink(tmp_name);			/* delete crent file */

	return(0);
}

/*		FUNC.DESCRIPSION

		func.name: dsdl01()
		routine	 : initialize for syori
		input	 : none
		output	 : none
		remarks  :		87.11.18	N.MATSUO

*/

static
dsdl01(f_crnt,tmp_name,m_ctr,s_sts,s_sel)
FILE		**f_crnt;
char		*tmp_name;
int		*m_ctr,*s_sts,*s_sel;
{
	char	*s = NULL;
	int	j;

	werase(pad);			/* erase screen */

	tmpnam(tmp_name);			/* makeing temp_file */

	for (j=0;j<RST_MAX;j++)
		rst_tbl[j] = E_CLEAR;			/* table clear */

	*m_ctr = *s_sts = 0 ;			/* counter & status clear */

	*s_sel = LP ;


	if ((*f_crnt = fopen(tmp_name,"w+")) == NULL) {	/* tmp_file open*/
		d3errw(E_TF_A);
	}

}


/*		FUNC.DESCRIPSION

		func.name: dsdl02()
		routine	 : delete of la data set
		input	 : none
		output	 : none
		remarks  :		87.11.18	N.MATSUO

*/

static
dsdl02(m_ctr,selectname,s_sts,f_crnt)
int	*m_ctr,*s_sts;
char	*selectname;
FILE	**f_crnt;
{
	static	char	msg[] = "削除処理実行中  ";
	int	rtn,k;
	char	cmdln1[512];

	/*	direcrory name read	*/

	dsdl05(selectname);

	/*	command line make	*/

	mvwaddstr(pad, l_num-3, 2, msg);	/* 実行中メッセージ表示 */
	d3fcmk(func2);		/* func_key set */
	prefresh(pad,0,0,0,0,l_num-2,80);

	sprintf(cmdln1, "d1_dsdl %s -nc %s 2>/dev/null", cur_ladb, selectname);
	rtn = system(cmdln1) >> 8;

	wmove(pad, l_num-3, 0); wclrtoeol(pad);	/* 実行中メッセージ消去 */

	if (rtn != NOERR) {
		if (*s_sts != 2)
			d3errw(rtn);
		else
			d3msgh(rtn);
	}
	*(rst_tbl + *m_ctr) = rtn ;

	if (*s_sts == 2) {
		*m_ctr = *m_ctr + 1;
		if ( *m_ctr >= RST_MAX ) {		/* memory over */
			*(rst_tbl + RST_MAX) = E_E;
			d3errw(E_M);
			fseek(*f_crnt,0,2);	/* file pointer -> eof */
		}

	}
}
/*		FUNC.DESCRIPSION

		func.name: dsdl03()
		routine	 : searching wild card
		input	 : char * la_str	searched strings
		output	 : int		-1	wild card found
					0	not found
		remarks  :		87.11.13	N.MATSUO

*/

static
dsdl03(la_str)
char	*la_str;
{
	int	n;

	n = strlen(la_str);

	if (strcspn(la_str,"*") != n)
		return(-1);
	else if (strcspn(la_str,"?") != n)
		return(-1);
	else if (strcspn(la_str,"[") != n)
		return(-1);
	else if (strcspn(la_str,"]") != n)
		return(-1);
	else if (strcmp(la_str,".") == 0 || strcmp(la_str,"..") == 0)
		return(-1);
	else
		return(0);

}

/*		FUNC.DESCRIPSION

		func.name: dsdl04()
		routine	 : LA data set name input
		input	 : char *selectname	LA data set name
				*tmp_name	tmp file name
				*s_sts		syori status
				**f_crnt	file pointer for tmp file
		output	 : int	s_sel		syori select
		remarks  :		87.11.26	N.MATSUO

*/

static
dsdl04(selectname,s_sts,tmp_name,f_crnt)
char	*selectname,*tmp_name;
int	*s_sts;
FILE	**f_crnt;
{
	int	x, y, s_sel;
	char	cmdln[512], read_str[20], s[128], s2[128];
	chtype	ek;

	d3fcmk(func);		/* func_key set */

	wmove(pad,5,10);
	waddstr(pad,"ＬＡデータセット名 ：");
	wclrtoeol(pad);
	pnoutrefresh(pad,0,0,0,0,l_num-2,80);

	x = 31;			/* x = clum */
	y = 5 ;			/* y = line */

	ek = d3chin(y,x,12,selectname);	/* input routine */

	switch( ek ) {
	case 0x0a:		/* 実行*/
		if (selectname[0] != NULL) {
			if (dsdl03(selectname) == 0) {
				*s_sts = 1;
				s_sel = GO;
				wmove(pad,y,x);
				wclrtoeol(pad);
				/*pnoutrefresh(pad,0,0,0,0,l_num,80);*/
			} else {
				s_sel = LP;
			}
		} else {
			s_sel = LP ;
		}
		break;

	case KEY_F(5):		/* 条件検索 */
		rewind(*f_crnt);
		d0cdrf(tmp_name);
		d3pned(8);				/* p_name display */
		d3dred();

		if (fgets(read_str,20,*f_crnt) == NULL) {
			longjmp(env,1);
		} else {
			*s_sts = 1;
			if (fgets(read_str,20,*f_crnt) != NULL)
				*s_sts = 2;
		}
		rewind(*f_crnt);
		s_sel = JK;
		break;

	case KEY_F(6):			/* file一覧 */
		sprintf(s2, "%s/%s", cur_ladb,
				selectname[0] ? selectname : "[!A-Z]*");
		d3fsel(s2, s, 0);
		if (s[0] != '\0')
			strcpy(selectname, s);
		werase(pad);
		d3pned(8);				/* p_name display */
		d3dred();			/* display current ladb */
		s_sel = LP;
		break;

	case KEY_F(7):		/* dir change */
		d0whre();

		s_sel = DC;
		break;

	case KEY_F(17):		/* 強制終了 */
	case KEY_F(18):		/* 正常終了 */
		s_sel = ED;
		break;

	default :
		s_sel = LP;

	}

	return(s_sel);
}

/*		FUNC.DESCRIPSION

		func.name: dsdl05()
		routine	 : delete LA data set name display
		input	 : syori_name		LA data set name
		output	 : none
		remarks  :		87.11.26	N.MATSUO

*/

static
dsdl05(syori_name)
char	*syori_name;
{
	wmove(pad,5,10);
	waddstr(pad,"ＬＡデータセット名 ：");
	wclrtoeol(pad);
	wprintw(pad,"%s",syori_name);
}
