/*	FUNC. DESCRIPTION
		
		ディレクトリ指定
                 
		FUNC.NAME	: drcg()
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.10.31	K.SIGEMI
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h" 
#include	<string.h> 
#include	"functn.h"

#define		TTY_NM 		(char *)((char *)ttyname(1) +5) 

#include 	<setjmp.h>
jmp_buf 	env0, env1, env2 ;

extern int 	d3kged(), d3drrd(), d3fcmk(), d3smmk(), d3dred(), d3pned() ; 
extern int 	d3drwt() ; 
extern chtype 	d3chin() ; 

#define 	PNAM 	drcg
#define 	PN 	11

#define 	DEBUG  
#define 	DEBUG_EXIT 	ER_EXIT(5) 

/* -----------------------------*/
#define 	WHILE(x) 	while( x ) { ; 
#define 	ENDW 		}
#define 	FOR(x) 		for( x ){ ; 
#define 	ENDFOR	 	}
#define 	SWITCH(x) 	switch( x ){ 
#define 	ENDSW 		}  
#define 	IF 		if( 
#define 	THEN 		){ ; 
#define 	ELSE 		}else{ ; 
#define 	ENDIF 		} 

/* -----------------------------*/
int 		i, j 	;
#define ARRAY_CLR(x) for(i=0;i<sizeof(x)/sizeof(x[0]);i++) x[i]  
#define ARRAY_CLR2(x) for(j=0;j<sizeof(x)/sizeof(x[0]);j++) for(i=0;i<(sizeof(x[0])/sizeof(x[0][0]));i++) x[j][i]  

/* ---------------------------- */
#define 	SCR_OPEN 	slk_init(1); initscr(); clear(); refresh()
#define 	PAD_MAKE 	if (d3pdmk(1))  exit(d3pdmk)
#define 	SCR_CLOSE 	clear() ; slk_clear() ; refresh() ; endwin() 
#define 	SCR_INIT	SCR_OPEN ; PAD_MAKE ; ER_PAD_MAKE ; DSP_PN_DR 
#define 	SCR_CLEAR	wmove(pad,2,0);wclrtobot(pad);pnoutrefresh(pad,0,0,0,0,l_num,80)
#define 	FK_CLEAR 	slk_clear()
#define 	PREFRESH 	prefresh(pad,0,0,0,0,l_num,80)
#define 	SET_CURSOR(l,c)	wmove(pad,l,c); PREFRESH  
#define 	SET_FUNC(x) 	d3fcmk(x) 
chtype 		f_key  ; 
#define SCR_GETS(l,c,ln,bf,f)  d3fcmk(f); PREFRESH; KEY_IN(l,c,ln,bf) 
#define KEY_IN(l,c,ln,bf)      if((f_key=d3chin(l,c,ln,bf)) !=0x0a) ; /* wgetch(pad) */ 
#define 	SET_ANK(l,c,m)	wmove(pad,l,c); waddstr(pad,m)

#define 	DSP_PN_DR 	d3pned(PN) ; d3dred() ; GET_DIR ; doupdate() 
extern	char	cur_ladb[] ;
#define 	GET_DIR 	d3drrd(cur_ladb) 

#define 	DIR_CALL 	SCR_CLOSE ; system("d0_drcg") ; SCR_INIT
#define 	JOKEN_CALL(fl) 	SCR_CLOSE ; SYS_CM("d0_cdrf "," ",fl) ; SCR_INIT 
#define 	ER_PAD_MAKE 	w1 = subpad(pad, 1, 49, l_num -2, 30) 
#define 	ER_MSG(x) 	d3msed(w1,x); prefresh(w1,0,0,l_num-2,30,l_num-2,75) 
#define 	ER_CLR 		ER_MSG(-1)  
#define 	ER_MES(x) 	ER_MSG(x); ER_WAIT 

#define 	ER_EXIT(x) 	ER_MES(x) ; SCR_CLOSE ; exit(x)  
#define 	ER_WAIT 	beep() ;  d3wkey(pad)

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/* -------------------------------- */
/* DESCRIPTION of TMP     FILE      */
/*                                  */
/* tmp_name = tmpnam(tmp_s)    */
static char 	tmp_buf[100] ;	 	/* tmp-file name */
		  /*  = "/usr/tmp/aaaaxxxxxx......" ; */ 
char 		*tmp_name    ; 
char		*tmp_s = NULL  	;
FILE		*fp_tmp		;
#define 	GET_TMP 	strcpy((tmp_name = tmp_buf), tmpnam(tmp_s))  
#define TMP_OPEN(x) if(NULL==(fp_tmp=fopen(tmp_name,x))) { ER_EXIT(E_TF_A) ; }  
#define 	TMP_CLOSE	if(NULL!=fclose(fp_tmp)) { ER_EXIT(E_TF_A) ;} 

/* -------------------------------- */
/* DESCRIPTION of Current FILE      */ 
/*                                  */
#define SET_CRNT_NAME strcpy(crnt_name,"/tmp/la_crd");strcat(crnt_name,TTY_NM); 
static char 	crnt_name[100] ;	/* current file name */ 
FILE		*fp_crnt 	;
#define CRNT_OPEN(x) SET_CRNT_NAME;if(NULL==(fp_crnt=fopen(crnt_name,x))){ER_EXIT(E_CF_A);}
#define CRNT_CLOSE     if(NULL != fclose(fp_crnt)) { ER_EXIT(E_CF_A) ; } 

/* -------------------------------- */
/*                                  */
#define	READ(bf,fp) if(NULL!=fgets(bf,sizeof(bf)-1,fp)){ *(strchr(bf,'\n'))=NULL ;
#define INVALID     }else{ ; 
#define ENDRD       } 
/* -------------------------------- */
/* SYSTEM COMMAND                   */
int 		exit_inf = NULL ; 
extern	char	cmd_b[] 	;
#define SYS_CM(x,y,z) strcpy(cmd_b,x);strcat(cmd_b,y);strcat(cmd_b,z);exit_inf = (system(cmd_b)>>8) 
   
/* -------------------------------- */
/* PROCESS DEFINE                   */
int 		loopsw, loopsw1, loopsw2, loopsw3, loopsw4, loopswg  ;
#define 	LOOP 		1 
#define 	LOOP_END 	0 

int		syori = NULL 		;
#define 	YES 	300
#define 	NO 	400
#define 	DIR 	600 

#define		STOP  	700    		/* 中止 */
#define		CONT  	800   		/* continue */
#define		END  	800   		/* 終了 */
/************************************/
#define		MODIFY	1001
#define		FIND	1002
#define		END_UTY	1009
#define		TMP_DIR	"/tmp/la_directory"  
/* -------------------------------- */
/* WORK to display menu             */
/*                                  */
char          title[]	        /* タイトル名 */
	= "ディレクトリ名一覧" ;       
char		*func[8]    = { 	/* description of func-key */
	  NULL,NULL,NULL,NULL,"dir 検索",NULL,NULL,"中止"  
	  }   			;   
char		imes[]    	/* 入力促進メッセージ */
	= "ディレクトリを指定して下さい  "  ;         
char		pno[160]    	;	/* content of input */
chtype		fno[10] 	;	/* inputed function no */
char		kmes[]    	 	/*  検索中メッセージ */
	= "ディレクトリを検索中     "  ;         

/* --------------------------------- */

main()
{
int 	rtn  		; 

	d3sgnl();			/* シグナル処理設定 */

	lbzzfs();
	lbzzsc();

	SCR_INIT ; d0PNAM() ;  SCR_CLOSE ;

	resetterm(); exit(NULL) ; 
}
d0PNAM()
{
int	rtn_inf = NULL ;
	int	select ; 

	loopsw1 = LOOP ;
	if ( d3fchk(TMP_DIR) != 0 ) PNAM01() ;
	loopsw = LOOP ; 
	WHILE(loopsw == LOOP) 
				/* display and input */ 
 	 SWITCH(PNAM02()) 
	    case  CONT : 
		PNAM03( ) ; 		/* put process */ 
		loopsw = LOOP_END ; 
		break ; 
    	    case  STOP :
		loopsw = LOOP_END ; 
		break ; 
    	    default :  break ; 
  	 ENDSW ;
	ENDW ;
	return ( NULL );
} 

PNAM01( )
{
	SCR_CLEAR ;
	mvwaddstr(pad, l_num -3, 2, kmes) ;  PREFRESH ;
	SYS_CM("find / -type d -name ladb -print > ", TMP_DIR ,  
	       " 2>/dev/null ") 	; 
	IF exit_inf != NULL  THEN  unlink ( TMP_DIR ) ; ER_EXIT(E_E) ;  ENDIF 

	return(NULL) ;
} 

PNAM02( )
{
int 	rtn_inf = NULL  	; 
				/* display menu & input */
	for ( ;; ) {
		*pno = NULL ; 
		d3smmk(title, TMP_DIR, func, imes, pno, fno) ;
		switch ( *fno ) {
			case	'0':	return ( CONT ) ; 
			case	'8':	return ( STOP ) ; 	
			case 	'5':	PNAM01 () ;
		}
	}
} 

PNAM03( )
{
char	*cpa 				; 
int 	rtn_inf = NULL ; 

	rtn_inf = d3drwt(pno) ; /* write directory-name into current-file*/ 
	IF rtn_inf != NULL  THEN  ER_EXIT(rtn_inf) ;  ENDIF  
					/* return */
	return(NULL) ;
} 

