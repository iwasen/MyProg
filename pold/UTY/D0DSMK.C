/*
*		file descripsion
*
*		�f�[�^�Z�b�g���� �o�^
*		file name: d0dsmk.c
*		routine	 : data set make
*		revision :
*		remarks  : 		87.11.24		N.MATSUO
*		remarks  : update 	88.02.17		S.Takahashi
*			�P..�Œ藓�`���f�[�^�̃��x�����t�@�C�����̓��͂̕s��
*			�Q..�G�f�b�g��ʂɒ��~�L�[��݂���
*/
#include	"d0dsmk.h"
/*
* �����t���O
*/
#define		LP		0x10		/* 	loop	  */
#define		JK		0x11		/*  jokenkensaku  */
#define		ED		0x12		/*	syuryo	  */
#define		DC		0x13		/* dirctory change */
#define		ST		0x14		/*     chusi	  */
#define		GO		0x15		/*    jikkou	  */

/*
* �l�`�b�q�n
*/
#define		S_CLEAR 	wmove(pad,2,0);wclrtobot(pad)

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

int		dsmk16(),dsmk17(),dsmk14(),dsmk18();
chtype		dsmk13();

static	char	title[] = 			/* title for menu */
"���̓f�[�^�I��";

static	char	*item[3] = {			/* item for menu */
	"  �P  �j�f�[�^�Z�b�g        ",
	"  �Q  �k�`�f�[�^�Z�b�g      "
};
/* function key for menu */
static	char	*func1[8] = {
	NULL,NULL,NULL,NULL,NULL,NULL,"LADB�ύX",NULL};

/* function key for other */
static	char	*func21[8] = {
	"��  �s",NULL,NULL,NULL,"��������","file�ꗗ",NULL,NULL};
static	char	*func22[8] = {
	"��  �s",NULL,NULL,NULL,NULL,"file�ꗗ",NULL,NULL};
/* function key for syori */

static	char	*func3[8] = {
	NULL,NULL,"�͂�","������",NULL,NULL,NULL,NULL};

static	char	imes1[] = 			/* input message for menu */
	"���̓f�[�^�̎�ނ��w�肵�Ă�������"	;

static	char	*pmes[2] = {			/* syori message */
	"��  �j�f�[�^�Z�b�g�̊i�[  ��",
	"��  �k�`�f�[�^�Z�b�g�̊i�[  ��"
};
/*********************************************************************
static	char	*pmes[3] = {			syori message
	"��  �j�f�[�^�Z�b�g�̊i�[  ��",
	"��  �\�f�[�^�Z�b�g�̊i�[  ��",
	"��  �k�`�f�[�^�Z�b�g�̊i�[  ��"
};
*********************************************************************/
static	char	*pmes1[2] = {		/* input message for data set */
	"�k�`�f�[�^�Z�b�g��  �F",
	"�f�[�^�t�@�C����    �F"
};

/*		���C������
*
*		FUNC.NAME:	d0dsmk()
*		input	 : none
*		output	 : none
*		HCP page : 5_0
*		remarks  : 		87.11.24		N.MATSUO
*/

d0dsmk()
{
	int	s_sel,s_no;

	for(;;){
		dsmk01();

		s_sel = dsmk02();			/* syori input */
		if(s_sel == ED)
			break;				/* end */
		if(s_sel >=1 && s_sel <= 2){
			switch(s_sel) {			/* syori furiwake */
			case 1:
				/* ��̓f�[�^ �Œ藓�f�[�^ �̑I�� */
				s_no = s_sel ;/* syori number = syori select */
				dsmk10(s_no);
				break;
			case 2:
				/* �k�`�f�[�^�Z�b�g�o�^ */
				dsmk30();/* la data set kara no sakusei */
				break;
			}
		}
	}
}
/*		���C�������̏�������
*
*		FUNC.NAME: dsmk01()
*		input	 : none
*		output	 : none
*		routine	 : screeen clear & syori name display
*		HCP page : 5_1
*		remarks  : 		87.11.24		N.MATSUO
*/

static
dsmk01()
{
	werase(pad);			/* pad erase */
	d3pned(5);
	d3dred();			/* dir name display */
}

/*		���C�������̃��j���[����
*
*		FUNC.NAME: dsmk02()
*		input	 : none
*		output	 : int	s_sel	syori select
*					GO	jikko
* 					DC	directry change
*					ED	syuryo
*		routine	 : screeen clear & syori name display
*		HCP page : 5_2
* 		remarks  : 		87.11.24		N.MATSUO
*/

static
dsmk02()
{
	int	s_sel;
	char	i_str[50];
	chtype	fno;

	s_sel = LP ;					/* syori select */
	for(;;){
		d3mnmk(0,title,item,2,func1,imes1,i_str,&fno);
		/* menu make */
		switch(fno){				/*last key*/
		case '0' :			/* push <CR> */
			s_sel = (int) i_str[0];	/* syori = number */
			break;
		case '7' :		/* push function_key(7) */
			s_sel = DC ;	/* syori = dir_change */
			break;
		case 'A':		/* �����I�� */
		case 'E':		/* ����I�� */
			s_sel = ED;	/* syori = end of process */
			break ;
		default :
			s_sel = LP ;
		}
		if(s_sel != LP)		break;
	}
	if(s_sel == DC){		/* dir_change */
		d0whre();
	}
	return(s_sel);
}

/*		�����m�n�P�A�Q�̃��C������
*
*		FUNC.NAME:	dsmk10()
*		input	 : syori_no		1: kaiseki data
*						2: koteiran keisiki
*		output	 : none
*		HCP page : 5_3
*		remarks	 :		87.11.24		N.MATSUO
*
*/

static
dsmk10(syori_no)
int	syori_no;
{
	int	i,s_sts,s_sel,m_ctr;
	unsigned char	la_data_name[50],data_file[50];
	char		name[100];/*array for temp name */
	chtype	m_sel;
	FILE	*f_tmp;
	char	*p1;
	int	lib_err ;

/* �������� */

	dsmk11(&s_sel,&m_ctr,&s_sts,name);
					/* ���������t�@�C���̍쐬 */
	if(( f_tmp = fopen(name,"w+")) == NULL){
		d3errw(E_TF_A);
		return;
	}
	S_CLEAR;  			/* ��ʂ̏��� */

	for(;;){
		werase(pad);		/* pad erase */

		d3pned(5);		/* �������̕\�� */
		d3dred();		/* �c�h�q�̕\�� */
					/* �e�t�@�C�����̃N���A�[ */
		la_data_name[0]=data_file[0]=NULL;

/* �X�e�[�^�X�ɂ��k�`�f�[�^�Z�b�g���𓾂� */

		if(s_sts == 2){		/* �X�e�[�^�X���������� �i�������j*/
			m_ctr++;		/* memory counter incriment */
			if(m_ctr>= RST_MAX){		/* memory max */
				*(rst_tbl + RST_MAX) = E_E;
				d3errw(E_M);
				fseek(f_tmp,0,2);
			}
			if(feof(f_tmp) || fgets(la_data_name,20,f_tmp) == NULL){
				d3keka(f_tmp,rst_tbl);	/* keka itiran */

				s_sts = 0 ;	/* counter & status clear */
				m_ctr = -1 ;

				for(i = 0;i < RST_MAX;i++)/* table clear */
					*(rst_tbl+i) = E_CLEAR;
			}
			else{
				p1 = strchr(la_data_name,0x0a);	/* CR->NULL */
				*p1 = NULL ;
			}
		}

		lib_err = 0  ;
		for ( ;; ) {

/* �t�@�C�������͏��� */

			S_CLEAR ;
			s_sel = dsmk12(syori_no,&s_sts,la_data_name,data_file,
			    &m_ctr,f_tmp,name);
			if(s_sel == ST)	break ;		/* select chusi */
			if(s_sel == JK)	break ;		/* select joken */
			if(s_sel == GO){		/* select jikko */

/* �G�f�b�g���� */
				m_sel = dsmk13(la_data_name,data_file,
						syori_no);
				if(m_sel == ON) {

/* �i�[���� */
					lib_err = dsmk14(syori_no,s_sts,
					la_data_name, data_file, m_ctr);
					if (lib_err == 0)
						dsmk20(la_data_name);
				}
			}
				/*�i�[�����G���[�̎��ēx�t�@�C��������*/
			if ( s_sel == GO && m_sel == ON && lib_err == 0 )break ;
		}
		if(s_sel == ST ) break ;
	}

/* �I������ */

	dsmk15(f_tmp,name);			/* tmp_file delete */
}

/*		�����m�n�P�A�Q�̏�������
*
*		FUNC.NAME: dsmk11()
*		input	 : s_sel		syori select
*			   m_ctr		memory counter
*			   s_sts		syori status
*			   name			temporary file name
*		output	 : s_sel		syori select
*		HCP page : 5_3_1
*		remarks  : 		87.11.24		N.MATSUO
*/

static
dsmk11(s_sel,m_ctr,s_sts,name)
int	*s_sel,*m_ctr,*s_sts;
char	*name;
{
	char	*s;
	int	i;

	s = NULL;			/* initialize for temporary name make */
	*s_sel = LP;			/* syori select  -> LP */
	*m_ctr = -1;
	*s_sts = 0 ;			/* counter & status initialize */

	tmpnam(name);

	for(i = 0;i < RST_MAX;i++) *(rst_tbl+i) = E_CLEAR;	 /* talbe clear */
}

/*		�����m�n�P�A�Q�̃t�@�C�������͏���
*
*		FUNC.NAME: dsmk12()
*		input	 : syori_no		syori number
*			   s_sts		syori status
*			   m_ctr		memory counter
*			   la_data_name		la data set name
*			   data_file		data file name
*			   name			tmp_file for jokken kensaku
*			   f_tmp		file pointer for name
*		output	 : s_sel		syori select
*		routine	 : file name input
*		HCP page : 5_3_2
*		remarks  : 		87.11.24		N.MATSUO
*/

static
dsmk12(syori_no,s_sts,la_data_name,data_file,m_ctr,f_tmp,name)
int	syori_no,*s_sts,*m_ctr;
char	*la_data_name,*data_file,*name;
FILE	*f_tmp;
{
	static	char	imes[] = "�t�@�C��������͂��Ă�������";
	int	p_flg, p_ptr, x, y, ncol, i, s_sel, err;
	char	r_str[20], s[80], s2[128];
	chtype	ek;

	dsmk16(syori_no);			/*syori item display */
	dsmk17(la_data_name,data_file);

						/* nyuryoku sokusin message */
	mvwaddstr(pad,21,2,imes);
	prefresh(pad,0,0,0,0,l_num,80);

	for(;;){

		p_flg = 0 ;			/* syori flag initialize */
		x = 32;				/* x = clums */
		y = 5;				/* y = lines */
		s_sel = LP ;			/* syori select */
		ncol = 45;			/*ncol=input character number */

		for(;;){

			switch( p_flg ){	/* flag for display pointer */

			case 0:
				if(*s_sts != 0){	/* only display */
					mvwaddstr(pad,y,x,la_data_name);
					prefresh(pad,0,0,0,0,l_num,80);

					p_ptr = 0 ;
					p_flg++;
					if(*s_sts == 1)
						*s_sts = 0;/* status clear */
				} else {
					d3fcmk(func21);

					/* la data set name input */
					ek = d3chin(y,x,12,la_data_name);
					p_ptr = 1;
				}
				break;
			case 1:
				d3fcmk(func22);
				/* data file name input */
				ek = d3chin(y+2,x,ncol,data_file);
				d3cven(data_file);	
				p_ptr =1;
				break;
			}

			if(p_ptr == 0)
				continue;

			switch(ek){
			case KEY_UP:		/* push UP_KEY */
			case KEY_DOWN:		/* push DOWN_KEY */
			case '\n' :			/* CR */
			cr_key:
				if(p_flg == 0)
					p_flg = 1;
				else
					p_flg = 0;
				break;
			case KEY_F(1):		/* jikko */
				s_sel = GO ;
				break;
			case KEY_F(5):		/* joken kensaku */
				if (p_flg == 0) {
					rewind(f_tmp);
					d0cdrf(name);	/* �������� */

					if(fgets(la_data_name,20,f_tmp)!=NULL){
						p_flg = 1;
						*strchr(la_data_name,0x0a)
								 = NULL;
						if(fgets(r_str,20,f_tmp)) {
							*s_sts = 2;
							s_sel=JK;
						}
					}
					rewind(f_tmp);

					dsmk16(syori_no);
					dsmk17(la_data_name,data_file);
					mvwaddstr(pad,21,2,imes);
					prefresh(pad,0,0,0,0,l_num,80);
				}
				break;
			case KEY_F(6):		/* file ichiran */
				switch (p_flg) {
				case 0:
					sprintf(s2, "%s/%s", cur_ladb,
					    la_data_name[0] ?
						la_data_name : "[!A-Z]*");
					err = d3fsel(s2, s, 0);
					if (s[0] != '\0')
						strcpy(la_data_name, s);
					break;
				case 1:
					err = d3fsel(data_file, s, 1);
					if (s[0] != '\0')
						strcpy(data_file, s, 1);
					break;
				}
				werase(pad);
				dsmk16(syori_no);	/*syori item display */
				dsmk17(la_data_name,data_file);
				mvwaddstr(pad,21,2,imes);
				prefresh(pad,0,0,0,0,l_num,80);
				if (err == 0)
					goto cr_key;
				break;
			case KEY_F(17):		/* �����I�� */
			case KEY_F(18):		/* ����I�� */
				if(*s_sts == 2){
					rst_tbl[*m_ctr] = E_E;
					d3keka(f_tmp,rst_tbl);
					/* keka itiran */
					*s_sts = 0 ;
					*m_ctr = -1;
					/* counter & status clear */
					for(i = 1;i < RST_MAX;i++)
						rst_tbl[i] = E_CLEAR ;
					/* table clear */
					wmove(pad,3,0);
					wclrtobot(pad);
					prefresh(pad,0,0,0,0,l_num,80);
				}

				s_sel = ST;
				break;
			}
			if(s_sel != LP)	break;
		}
		if(s_sel == JK || s_sel == ST) break;
		if(la_data_name[0] != NULL){
			break;
		}

	}

	return(s_sel);
}

/*		�����m�n�P�A�Q�̃G�f�b�g����
*
*		func.name: dsmk13()
*		input	 : la_data_name		la data set name
*			   data_file		data file name
*			   syori_no
*		output	 : chtype ON		syori ari
*  			   chtype OFF		syori nasi
*		routine	 : file edit & syori sentaku
*		HCP page : 5_3_3
*		remarks	 :		87.11.24	N.MATSUO
*
*/

static
chtype	dsmk13(la_data_name,data_file,syori_no)
char	*la_data_name;
int	syori_no;
{
	char	dir_buf[256];
	struct	stat	buff;

	strcpy(dir_buf, cur_ladb);
	strcat(dir_buf,"/");
	strcat(dir_buf,la_data_name);

	if(stat(dir_buf,&buff) == 0){ 	/* �k�`�f�[�^�Z�b�g���ݔ��f */
		if(dsmk18(la_data_name,data_file,
		    syori_no) != ON)	/* �㏑�����邩 */
			return( (chtype) OFF);	/* �m�n */
	}

	return((chtype) ON );
}

/*		�����m�n�P�A�Q�̊i�[����
*
*		func.name: dsmk14()
* 		input	 : int s_num		syori select number
*			   int s_status		syori status
*			   la_data_name		la data set name
*			   data_file		data file name
*			   name			tmp_file for joken kensaku
*			   m_ctr		memory counter
*		output	 : none
*		HCP page : 5_3_4
*		routine  : command jikkou & error message display
*		remarks	 : 		87.11.22	N.MATSUO
*
*/

static
dsmk14(s_num,s_status,la_data_name,data_file,m_ctr)
char	*la_data_name,*data_file;
int	s_num,s_status,m_ctr;
{
	int	rtn;
	char	cmdln[512];
	static	char	*command[2] = {
		"d1_adrg ","d1_kdrg "
	};

/* �R�}���h�̃p�����[�^�̍쐬 */

	strcpy(cmdln,command[s_num-1]);

	strcat(cmdln,cur_ladb);			/* copy for command strings */
	strcat(cmdln," ");
	strcat(cmdln,la_data_name);
	if(*data_file != NULL){			/* file name ari */
		strcat(cmdln," -k ");
		strcat(cmdln,data_file);
	}
	if ( s_num != 2 ) strcat(cmdln," -w");
	else strcat ( cmdln," -wc+" ) ;
	strcat ( cmdln , " 2>/dev/null " ) ;
	/*endwin();*/

/* �R�}���h�̎��s */

	rtn = ((system(cmdln)) >> 8);	/* command for data set seisei */

	if(rtn != NOERR){			/* error message */
		d3errw(rtn);
	}

/* �����X�e�[�^�X���Q �i���������s���j �̎��G���[�R�[�h��ۑ����� */

	if(s_status == 2){			/* error kakunou */
		*(rst_tbl+m_ctr) = rtn;
	}
	return ( rtn ) ;
}

/*		�����m�n�P�A�Q�̏I������
*
*		FUNC.NAME: dsmk15()
* 		input	 : f_tmp	file pointer for name
*			   name		temporary file name
*		output	 : none
*		routine	 : file close & file delete
*		HCP page : 5_3_5
*		remarks  : 		87.11.24		N.MATSUO
*/

static
dsmk15(f_tmp,name)
FILE	*f_tmp;
char	*name;
{

/* �e���|�����B�t�@�C���̍폜 */

	fclose(f_tmp);				/* file close */
	unlink(name);				/* tmp_file delete */
}
/*		�����m�n�P�A�Q�̃t�@�C�������͉�ʕ\������
*
*		FUNC.NAME:	dsmk16()
*		INPUT	 :	num		*0: no display of syori message
*						1: kaiseki data
*						2: koteiran data
*		output	 : none
*		routine  : nyuryoku title display
*		remarks	 :		87.11.24		N.MATSUO
*
*/

static
dsmk16(num)
int	num;
{

	d3pned(5);				/* syori name display */

	d3dred();				/* dir name display */

	if(num != 0){				/* kobetu syori name display */
		mvwaddstr(pad,1,0,pmes[num-1]);
	}

	wmove(pad,5,10);			/* �k�`�f�[�^�Z�b�g��: */

	wclrtoeol(pad);
	waddstr(pad,pmes1[0]);

	wmove(pad,7,10);			/* ���̓t�@�C����: */
	wclrtoeol(pad);
	waddstr(pad,pmes1[1]);
}

/*		�����m�n�P�A�Q�̓��̓t�@�C�����\������
*
*		FUNC.NAME:	dsmk17()
*		INPUT	 :
*			   la_data_name		la data set name
*		   	   data_file		data file name
*		OUTPUT	 : none
*		ROUTINE	 : faile name display
*		REMARKS	 : 		87.11.24		N.MATSUO
*
*/

static
dsmk17(la_data_name,data_file)
char	*la_data_name,*data_file;
{
	mvwaddstr(pad,5,32,la_data_name);	/* la data set name */
	mvwaddstr(pad,7,32,data_file);		/* data file name */
}

/*		�㏑���m�F��ʏ���
*
*		FUNC.NAME:	dsmk18()
*		INPUT	 :
*			   la_data_name		la data set name
*		   	   data_file		data file name
*		OUTPUT	 : ON		la data set uwagaki
*			   OFF		la data set uwagaki nasi
*		HCP page : 5_3_3_1
*		remarks	 :		87.11.24		N.MATSUO
*
*/

static
dsmk18(la_data_name,data_file,syori_no)
char	*la_data_name,*data_file;
int	syori_no ;
{
	chtype	ek;
	char	dum[2];					/* dummy strings */

	d3fcmk(func3);					/* function make */

	dsmk16(syori_no);			/* nyuryoku message disp. */
	dsmk17(la_data_name,data_file);		/* file name display */

	d3msgh(E_LADS_OVW);			/* ���b�Z�[�W�ʒm */
	mvwaddstr(pad,21,2,"�w�肳�ꂽ�k�`�f�[�^�Z�b�g�͊��ɑ��݂��܂��B�㏑�����܂����i�͂��^�������j");

	for(;;){
		ek = d3chin(21,79,0,dum);

		if(ek == KEY_F(3) || ek == KEY_F(4))		break;
	}

	if(ek == KEY_F(3))		return(ON);
	else	return(OFF);
}

/*	FUNCTION DESCRIPTION

		FUNC.NAME	: dsmk20()
		ROUTINE		: ���������x�����̍쐬
		INPUT		: la_data_name = la data set name
		OUTPUT		: none
*/

dsmk20(la_data_name)
char	*la_data_name;
{
	static	char	*func[8] = { 	/* description of func-key */
		"������", "���x����", NULL, NULL,
		NULL, NULL, NULL, NULL
	};
	static	char	imes[] = "�������E���x�������쐬���Ă�������";
	static	char	title[] = "���@�k�`�f�[�^�̊i�[�@��";

	int	loop_sw;		/* ���[�v�X�C�b�` */
	int	err;			/* �G���[�R�[�h */
	chtype	ch;			/* ���̓L�[ */

	loop_sw = 1;			/* ���[�v�X�C�b�`�n�m */

	while (loop_sw) {
		wmove(pad, 21, 2);
		wclrtoeol(pad);
		waddstr(pad, imes);
		d3fcmk(func);		/* �t�@���N�V�����L�[�\�� */

		ch = d3chin(21, 38, 0, 0);
		switch (ch) {
		case KEY_F(1):		/* ������ */
			d3slmt(cur_ladb, la_data_name, 0, title);
			break;
		case KEY_F(2):		/* ���x���� */
			d3slmt(cur_ladb, la_data_name, 1, title);
			break;
		case KEY_F(17):		/* �����I�� */
		case KEY_F(18):		/* ����I�� */
			loop_sw = 0;	/* ���[�v�X�C�b�`�n�e�e */
			break;
		}
	}
}

/*		�k�`�f�[�^�Z�b�g�o�^����
*
*		func.name: dsmk30()
*		input	 : none
*		output	 : none
*		routine	 :  la data set touroku
*		HCP page : 5_4
*		remarks  :		87.11.24	N.MATSUO
*
*/

static
dsmk30()
{

	int	i,s_sts,s_sel,m_ctr;
	char		name[100];		/* array for temp name */
	unsigned char	la_data_name[50],input_file[50];
	FILE	*f_tmp;
	char	*p1;

	S_CLEAR;

	dsmk31(&s_sel,&s_sts,name,&m_ctr);	/* initialize */

	if(( f_tmp = fopen(name,"w+")) == NULL){	/* temp file open */
		d3errw(E_TF_A);
		return;
	}

	for(;;){
		werase(pad);

		d3pned(5);
		d3dred();

		/* array initialize */
		if(s_sel != JK)
			la_data_name[0] = input_file[0] = NULL;
		else	la_data_name[0] = NULL;

		if(s_sts == 2){
			m_ctr++;
			if(m_ctr>=RST_MAX){
				*(rst_tbl + RST_MAX) = E_E;
				d3errw(E_M);
				fseek(f_tmp,0,2);
			}
			if(feof(f_tmp) || fgets(la_data_name,20,f_tmp) == NULL){
				d3keka(f_tmp,rst_tbl);		/* keka itiran */
				s_sts = 0 ;		/* counter & status clear */
				m_ctr = -1;

				for(i = 0;i < RST_MAX;i++)	/* table clear */
					*(rst_tbl+i) = E_CLEAR;

				wmove(pad,3,0);			/* screen clear */
				wclrtobot(pad);
			}
			else{
				p1 = strchr(la_data_name,0x0a);	/* CR -> NULL */
				*p1 = NULL ;
			}

		}

		if(s_sts == 1){				/* 1line read */
			fgets(la_data_name,20,f_tmp);
			p1 = strchr(la_data_name,0x0a);
			*p1 = NULL;
		}
retry:
		s_sel = dsmk32(la_data_name,input_file,&s_sts,
		    name,f_tmp,&m_ctr);
		/* input file name */
		if(s_sel == GO){
			if(dsmk33(la_data_name) == ON){
				if (dsmk34(s_sts,la_data_name,
						input_file,m_ctr) != 0)
					goto retry;
				dsmk20(la_data_name);
			}
			if(s_sts == 1)		s_sts = 0 ;/* status clear */
		}

		if(s_sel == ST)	break;
	}
	dsmk35(f_tmp,name);				/* temp_file delete */
}

/*		��������
*
*		func.name: dsmk31()
* 		input	 : m_ctr	memory counter
*			   s_sts	syori status
*			   s_sel 	syori select
*		output	 : none
*		routine	 : initialize counter , status ,tmp name & table
*		HCP page :  5_4_1
*		remarks  :		87.11.24	N.MATSUO
*
*/

static
dsmk31(s_sel,s_sts,name,m_ctr)
int	*s_sel,*s_sts,*m_ctr;
char	*name;
{
	int	i;

	*s_sel = LP;
	*m_ctr = -1;
	*s_sts = 0 ;			/* counter & status initialize */

	tmpnam(name);			/* temp name get */

	for(i = 0;i < RST_MAX;i++)	/* table clear */
		*(rst_tbl+i) = E_CLEAR;

}

/*		�t�@�C�������͏���
*
*		func.name: dsmk32()
*		input	 : la_data_name	la data set name
*			   input_file
*			   s_sts	syori status
* 			   name		tmp_name for joken kensaku
*			   m_ctr	memory counter
*			   f_tmp	file pointer for name
*		output	 : s_sel	syori select
*		routine	 : file name input
*		HCP page : 5_4_2
*		remarks  :		87.11.24	N.MATSUO
*
*/

static
dsmk32(la_data_name, input_file, s_sts, name, f_tmp, m_ctr)
char	*la_data_name, *input_file, *name;
FILE	*f_tmp;
int	*s_sts, *m_ctr;
{
	int	s_sel, x, y, p_flg, p_ptr, ncol, i, err;
	char	r_str[20], s[80], s2[128];
	chtype	ek;

	p_flg = 0 ;			/* syori flag initialize */
	x = 32;				/* x = clums */
	y = 5;				/* y = lines */
	ncol = 45;			/* ncol = input character number */
	s_sel = LP;
restart:
	werase(pad);
	d3pned(5);
	d3dred();
	mvwaddstr(pad,1,0,pmes[1]);

	wmove(pad,5,10);
	wclrtoeol(pad);
	waddstr(pad,"�k�`�f�[�^�Z�b�g�� �F");

	wmove(pad,7,10);			/* clear to end of line */
	wclrtoeol(pad);
	waddstr(pad,"���̓f�[�^�Z�b�g�� �F");

	wmove(pad,21,2);			/* nyuryoku sokusin message */
	wclrtoeol(pad);
	waddstr(pad,"�f�[�^�Z�b�g������͂��Ă�������");

	mvwaddstr(pad,y,x,la_data_name);
	mvwaddstr(pad,y+2,x,input_file);
	prefresh(pad,0,0,0,0,l_num,80);

	for(;;){

		switch( p_flg ){

		case 0:
			if(*s_sts != 0){
				p_ptr = 0 ;
			} else {
				d3fcmk(func21);		/* func.key set */
				ek = d3chin(y,x,12,la_data_name);
				p_ptr =1;
			}
			break;
		case 1:
			d3fcmk(func22);		/* func.key set */
			ek = d3chin(y+2,x,ncol,input_file);
			d3cven(input_file);
			p_ptr = 1;
			break;
		}

		if(p_ptr != 0){
			switch(ek){
			case '\n':
			case KEY_UP:		/* push UP_KEY */
			case KEY_DOWN:		/* push DOWN_KEY */
				if(p_flg == 0)
					p_flg = 1;
				else
					p_flg = 0;
				break;
			case KEY_F(1):		/* ���s */
				if(*input_file == NULL || *la_data_name == NULL){
				} else {
					s_sel = GO;
				}
				break;
			case KEY_F(5):		/* joken kensaku */
				if (p_flg == 0) {
					rewind(f_tmp);
					d0cdrf(name);	/* �������� */
					if(fgets(la_data_name,20,f_tmp) != NULL){
						p_flg = 0;
						*strchr(la_data_name,0x0a)
								 = NULL;
						if(fgets(r_str,20,f_tmp)) {
							*s_sts = 2;
							s_sel = JK;
						}
					}
					rewind(f_tmp);
				}
				if (*s_sts != 2)
					goto restart;
				break;
			case KEY_F(6):		/* file ichiran */
				if (p_flg == 0) {
					sprintf(s2, "%s/%s", cur_ladb,
					    la_data_name[0] ?
						la_data_name : "[!A-Z]*");
					err = d3fsel(s2, s, 0);
					if (s[0] != '\0')
						strcpy(la_data_name, s);
				} else {
					err = d3fsel(input_file, s, 1);
					if (s[0] != '\0')
						strcpy(input_file, s);
				}
				if (err == 0) {
					if(p_flg >= 1)
						p_flg = 0;
					else	p_flg = 1;
				}
				goto restart;
			case KEY_F(17):		/* �����I�� */
			case KEY_F(18):		/* ����I�� */
				if(*s_sts == 2){
					*(rst_tbl+*m_ctr) = E_E;
					d3keka(f_tmp,rst_tbl);
					/* keka itiran */
					*m_ctr = -1;
					*s_sts = 0 ;
					/* counter & status clear */
					for(i = 1;i < RST_MAX;i++)
						*(rst_tbl+i) = E_CLEAR ;
					/* table clear */
					wmove(pad,3,0);
					wclrtobot(pad);
					prefresh(pad,0,0,0,0,l_num,80);
				}

				s_sel = ST;
				break;
			}
		} else
			p_flg = 1;

		if(s_sel != LP)
			break;
	}

	return(s_sel);
}

/*		�㏑���m�F��ʏ���
*
*		func.name: dsmk33()
*		input	 : la_data_name	la data set name
*		output	 : ON		syori ari
*			   OFF		syori nasi
*		routine  :
*		HCP page : 5_4_3
*		remarks  :		87.11.24	N.MATSUO
*
*/

static
dsmk33(la_data_name)
char	*la_data_name;
{
	chtype	ek;
	char	dum[2],dir_buf[256];			/* dummy string */
	struct	stat	buff;

	strcpy(dir_buf, cur_ladb);
	strcat(dir_buf,"/");
	strcat(dir_buf,la_data_name);

	if(stat(dir_buf,&buff) == 0){		/* file found */

		d3fcmk(func3);			/* func. make */

		d3msgh(E_LADS_OVW);		/* ���b�Z�[�W�ʒm */
		mvwaddstr(pad,21,2,"�w�肳�ꂽ�k�`�f�[�^�Z�b�g�́A���ɑ��݂��܂��B�㏑�����܂����i�͂��^�������j");
		prefresh(pad,0,0,0,0,l_num,80);

		for(;;){
			ek = d3chin(21,79,0,dum);

			if(ek == KEY_F(3) || ek == KEY_F(4))
				break;

		}
		if(ek == KEY_F(3))
			return(ON);
		else	return(OFF);
	}
	else{
		return(ON);
	}
}

/*		�R�}���h���s����
*
*		func.name: dsmk34()
*		input	 : int s_sts		syori status
*			   la_data_name		la data set name
* 			   input_file		input data set name
*			   m_ctr		memory counter
*		output	 : none
*		HCP page : 5_4_4
* 		remarks  :		87.11.24	N.MATSUO
*
*/

static
dsmk34(s_sts,la_data_name,input_file,m_ctr)
char	*la_data_name,*input_file;
int	s_sts,m_ctr;
{
	int	rtn;
	char	cmdln[512];

	sprintf ( cmdln , "%s %s %s %s %s", "d1_dsrg",cur_ladb,la_data_name,
				input_file,"-w 2>/dev/null " ) ;
	/*endwin();*/
	rtn = ((system(cmdln)) >> 8);			/* command */

	if(rtn != NOERR){			/* error message display */
		d3errw(rtn);
	}

	if(s_sts == 2){					/* MULT mode ? */
		*(rst_tbl+m_ctr) = rtn;
	}
	return(rtn);
}

/*		�I������
*
*		func.name: dsmk35()
*		input	 : f_tmp	file pointer for name
*			   name		tmp_file name for joken kensaku
*		output	 : none
*		routine  :
*		HCP page : 5_4_5
*		remarks  :		87.11.24	N.MATSUO
*
*/

static
dsmk35(f_tmp,name)
FILE	*f_tmp;
char	*name;
{
	fclose(f_tmp);				/* file close */
	unlink(name);				/* tmp_file delete */
}