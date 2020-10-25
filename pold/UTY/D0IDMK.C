/*	FUNC. DESCRIPTION
		
	 	�C���f�b�N�X�t�@�C���̍č쐬
                 
		FUNC.NAME	: idmk()
		INPUT		: none
		OUTPUT		: none
		REMARKS		:	87.11.31	K.SIGEMI
*/

#include	<jcurses.h>
#include	"la_ws/include/d2lerr.h" 
#include	<string.h> 

#include 	<setjmp.h>
jmp_buf 	env0, env1, env2 ;
#include 	<sys/types.h> 
#include 	<sys/stat.h> 
struct  stat 	stat_bf ; 

#define		TTY_NM 		(char *)((char *)ttyname(1) +5) 

extern int 	d3kged(),d3fcmk(),d3smmk(),d3pned(),d3dred(); 
extern chtype 	d3chin() ; 

#define 	PNAM 	idmk
#define 	PN 	9

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
#define 	SCR_CLEAR	wclear(pad) ; refresh() 
#define 	FK_CLEAR 	slk_clear()
#define 	PREFRESH 	prefresh(pad,0,0,0,0,l_num,80)
#define 	SET_CURSOR(l,c)	wmove(pad,l,c); PREFRESH  
chtype 		f_key  ; 
#define SCR_GETS(l,c,ln,bf,f)  FK_CLEAR; d3fcmk(f); PREFRESH; KEY_IN(l,c,ln,bf) 
#define KEY_IN(l,c,ln,bf) switch(f_key=d3chin(l,c,ln,bf)){IN_DMY; default:/*wgetch(pad)*/;}
#define IN_DMY  case 0x0a:case KEY_UP:case KEY_DOWN:case KEY_NPAGE:case KEY_PPAGE: break 
#define 	SET_ANK(l,c,m)	wmove(pad,l,c); waddstr(pad,m)

#define 	DSP_PN_DR 	d3pned(PN) ; d3dred() ; doupdate() 
extern	char	cur_ladb[] ;

#define 	DIR_CALL 	endwin() ; system("d0_drcg") ; d3dred()
#define 	ER_PAD_MAKE 	w1 = subpad(pad, 1, 49, l_num -2, 30) 
#define 	ER_MES(x) 	d3msed(w1,x);prefresh(w1,0,0,l_num-2,30,l_num-2,75);ER_WAIT 
#define 	ER_CLR	 	d3msed(w1,-1);prefresh(w1,0,0,l_num-2,30,l_num-2,75)
WINDOW		*w1   		;	/* pointer of pad */
int		msno  		;	/* message no (errror code) */ 

#define 	ER_EXIT(x) 	ER_MES(x) ; SCR_CLOSE ; exit(x)  
#define 	ER_WAIT 	beep(); d3wkey(pad)

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

#define 	FILE_EXIST_CHECK(fl) 	file_exist=stat(fl, &stat_bf)  
#define 	FILE_EXIST   0  
#define  	FILE_NONE    -1     
int 		file_exist = FILE_NONE    ; /* �f�[�^�Z�b�g���ݔ��f�r�v */ 
/* -------------------------------- */
/* SYSTEM COMMAND                   */
int 		exit_inf = NULL ; 
char		cmd_b[512] 	;
#define 	CM_CAT(x) 		strcat(cmd_b,x)
#define SYS_CM(x,y,z) strcpy(cmd_b,x);CM_CAT(y);CM_CAT(z);exit_inf = (system(cmd_b)>>8) 
#define SYS_CM5(x1,x2,x3,x4,x5) strcpy(cmd_b,x1);CM_CAT(x2);CM_CAT(x3);CM_CAT(x4);CM_CAT(x5);exit_inf = (system(cmd_b)>>8) 
   
/* -------------------------------- */
/* PROCESS DEFINE                   */
int 		loopsw, loopsw1, loopsw2, loopsw3, loopsw4, loopswg  ;
#define 	LOOP 		1 
#define 	LOOP_END 	0 

int 		field_no = 0 ;		/* ���̓t�B�[���h�m���D */ 
int 		field_max = 1 ; 	/* �ŏI���̓t�B�[���h�m���D */ 
#define 	FIELD_END  	500 

int		syori = NULL 		;

#define 	EXEC 	100  
#define 	YES 	300
#define 	NO 	400
#define 	JOKEN   500  
#define 	DIR 	600 

#define		STOP  	700    		/* ���~ */
#define		CONT  	800   		/* continue */
#define		END  	800   		/* �I�� */
   
/* -------------------------------- */
/* WORK AREA of PNAM                */

char 		index_fl_name[30] 	; /* �����t�@�C���� */ 
unsigned int 	index_ctr = 0 ; 

char		pno[200]    	; 	/* content of input */
chtype		fno[10] 	;	/* inputed function no */

/* --------------------------------- */

main()
{
	d3sgnl();			/* �V�O�i�������ݒ� */
	lbzzfs();
	lbzzsc();
	SCR_OPEN ; PAD_MAKE ; ER_PAD_MAKE ; d0PNAM() ; SCR_CLOSE ; 	
	resetterm();
	exit(NULL) ;
}

/*---------------------------------------------*/
/* main part */
d0PNAM()
{
int	rtn_inf = NULL ;

	PNAM10() ;  			/* �������� */ 

	PNAM20(tmp_name) ; 		/* �����̒��o */ 
	loopsw = LOOP 				; 
	WHILE(loopsw == LOOP)
	  SWITCH(PNAM30()) 			/* ��� [9-1] �̏��� */ 
	    case EXEC : 
	      PNAM40() ;  break ;  		/* �����t�@�C���̍쐬���� */ 
	    case DIR :
	      DIR_CALL ;			/* �f�B���N�g���w�� */ 
		PNAM20(tmp_name) ;		/* �����̒��o */ 
		break;
	    case END : 				/* �I�� */  
	      loopsw = LOOP_END ;  break ; 
	    default :
	      break ; 
	  ENDSW ; 
	ENDW ; 

	PNAM50() ;  			/* �I������ */ 
	
	return(rtn_inf) 			;
} 

/*----------------------------------------------*/
/* �������� */
PNAM10( )
{
				/* �����t�@�C�����̏����� */ 
	ARRAY_CLR(index_fl_name) = NULL ;  
		 		/* �������𒊏o����C�e���|�����t�@�C���̊m��*/ 
	GET_TMP ; 		

	return(NULL) ; 
} 

/*----------------------------------------------*/
/* �����̒��o */ 
PNAM20(tmp_name)
char	*tmp_name ; 			/* ���o���ʂ��i�[����t�@�C�� */ 
{ 
int 	rtn_inf ; 

	SCR_CLEAR ;  DSP_PN_DR ; 
	mvwaddstr(pad, l_num-2, 2, "�����t�@�C�����𒊏o��  ") ; 
	PREFRESH ; sleep(1) ; 

	SYS_CM5( "ls ",cur_ladb,"/IND.* >",tmp_name ," 2>/dev/null");
	d3psdl(tmp_name);

	return(rtn_inf) ; 
} 

/*----------------------------------------------*/
/* ��� [9-1] �̏��� */ 
PNAM30( )
{
int 	rtn_inf = NULL 			; 
int	err;
static char 	title[] =  		/* �^�C�g�� */ 
 			"�����t�@�C���I��" ; 
static char 	imes[] =  		/* ���͑��i���b�Z�[�W */ 
 "�����t�@�C����I�����ĉ�����  " ; 
static char	*func1[8]    = { 	/* description of func-key */
	  NULL, 				
	  NULL,  
	  NULL,  
	  NULL,  
	  NULL,   				
	  "dir �ύX",  			
	  NULL,
	  "�I��"   				
	  }   			;   

	SCR_CLEAR ; DSP_PN_DR ; FK_CLEAR ; 
	loopswg = LOOP ; 

	WHILE(loopswg == LOOP) 
	  *pno = NULL ; 
	  err = d3smmk(title, tmp_name, func1, imes, pno, fno) ;
	  SWITCH(*fno) 
	    case  '0' : 
	      if (err != 0)
			break;
	      ARRAY_CLR(index_fl_name) = NULL ; 
	      strcpy(index_fl_name, pno) ; 
	      rtn_inf = EXEC ;  loopswg = LOOP_END ;  break ; 
	    case  '6' : 
	      rtn_inf = DIR ;  loopswg = LOOP_END ;  break ; 
	    case  '8' : 
	      rtn_inf = END ;  loopswg = LOOP_END ;  break ; 
	    default :
	      beep() ;  break ; 
	  ENDSW ; 
	ENDW ; 

	return(rtn_inf) ;
} 

/*----------------------------------------------*/
/* �����t�@�C���̍쐬���� */ 
PNAM40( )
{
int 	rtn_inf = NULL ; 

	move(l_num-2,0); refresh();
	SYS_CM5("d1_idmk ",cur_ladb," ",index_fl_name," 2>/dev/null") ; 

	IF  exit_inf != NULL 			/* �G���[���f */
		THEN  ER_MES(exit_inf) ; 
	ENDIF 

	return(rtn_inf) ;   
} 

/*----------------------------------------------*/
/* �I������ */
PNAM50( )
{ 
int 	rtn_inf = NULL ; 

	unlink(tmp_name) ; 
	return(rtn_inf) ;
} 
