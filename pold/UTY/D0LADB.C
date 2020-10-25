/*******************************************************
 *	FILE DESCRIPTION
 *
 *	ROUTION	 : �k�`�f�[�^�x�[�X�̐����E�ێ�
 *	FILE NAME: d0ladb.c
 *		 : d0ladb
 *	REMARKS	 :	88.04.25	M.MOTOKI
 *
 *******************************************************/

#include <jcurses.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <errno.h>
#include <memory.h>
#include "functn.h"
#include "d3item.h"
#include "d3data.h"
#include "la_ws/include/d3nmty.h"
#include "la_ws/include/d2lerr.h"
#include "la_ws/include/tbdefs.h"
#include "la_ws/include/d3libc.h"

#define	PNOUTREFRESH	pnoutrefresh(pad, 0 , 0, 0, 0, l_num, 80);
#define	PREFRESH	prefresh(pad, 0, 0, 0, 0, l_num, 80);
#define MREFRESH	prefresh(w1, 0, 0, l_num-2, 30, l_num-2, 79);
#define WCLRTOEOL(y,x)	{wmove(pad, y, x); wclrtoeol(pad);}
#define RF_M (UT_READ|UT_NUPD|UT_NADC|UT_NDTC|UT_NCOL|UT_NSLC|UT_NADL|UT_NDTL)
#define UP_M (UT_NADC|UT_NDTC|UT_NCOL|UT_NSLC)
#define	ITEM_ENTER		(IT_NUM|IT_CEN)
#define	ITEM_DISP		(IT_NUM|IT_CEN|IT_DSP|IT_NSL)
#define MAX_DBNAME	64
#define	LOOP_END	0
#define	LOOP		1
#define	RET_NORMAL	0
#define	RET_ERROR	1
#define	OLD_LIST	0
#define	NEW_LIST	1
#define	LADB_LIST	"ladb_list"

#define MES_SELECTION	"�k�`�f�[�^�x�[�X��I�����Ă�������  "
#define	MAIN_TITLE	"���@�k�`�f�[�^�x�[�X�̐����E�ێ�  ��"
#define	SUB_TITLE	"�k�`�f�[�^�x�[�X�ꗗ"
#define	RF_T		"��  �O���[�v�����̎Q��  ��"
#define	UP_T		"��  �O���[�v�����̍쐬�E�X�V  ��"

#define	PRINT(err)	sprintf(msgbuf,"���s�G���[ : �G���[�R�[�h=%-2d",err)
#define	PD_ERRCD(err, st)	{d3errw(err); return(st);}
#define	PD_ERRNO(err, st)	{PRINT(err); d3errm(msgbuf); return(st);}

extern	char		*getenv(), *strrchr(), *strchr();
extern	unsigned short	geteuid();

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	*pf_label[8]={
	NULL,"�쐬�X�V","LADB����","�Q  ��",
	NULL,"�ꗗ�\��",NULL,NULL
};
static	char	*pf_label_2[8]={
	NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL
};
static	char	base_ladb[128];
static	char	ladb_list[128];
static	int	new_list, mgno;
static	FILE	*fp;
static	char	msgbuf[30];
static	D_CELL	*cell_p;
static	int	cell_n, c_pos, width;
static	int	first_flag;

d0ladb()
{
	int	err, i, loop;
	chtype	pf_key;
	char	*p;

	ladb02();			/* �������� */

	mgno = -1; fp = NULL;
	new_list = NEW_LIST;
	width = MAX_DBNAME+4; c_pos = 0;
	first_flag = 1;
	loop = LOOP;

	while (loop == LOOP) {
		ladb04(MES_SELECTION);	/* ��ʐݒ� */

		pf_key=d3item(cell_n, cell_p, width, ITEM_ENTER, &c_pos);
		switch (pf_key) {
		case '\n':		/* return key */
			break;

		case KEY_F(2):		/* �O���[�v�����̍쐬�E�X�V */
			err=ladb20(cell_n, cell_p, c_pos, UP_T, UP_M);
			break;
		case KEY_F(3):		/* �k�`�f�[�^�x�[�X�̐��� */
			err=ladb10();
			break;
		case KEY_F(4):		/* �O���[�v�����̎Q�� */
			err=ladb20(cell_n, cell_p, c_pos, RF_T, RF_M);
			break;
		case KEY_F(6):		/* �k�`�f�[�^�x�[�X�̃V�X�e���Ǘ���� */
			err=ladb40(cell_n, cell_p, c_pos);
			break;
		case KEY_F(17):		/* �����I�� */
		case KEY_F(18):		/* ����I�� */
			loop=LOOP_END;
			break;
		}
	}

	if (mgno != -1) d3free(mgno);
	if (fp != NULL) fclose(fp);

	return(0);
}

/***************************************
 *	ladb02	INITIAL PROCCESS
 ***************************************/
static	ladb02()
{
	char	*p;

	strcpy(base_ladb, home_ladb);	/* �k�`�f�[�^�x�[�X */

	strcpy(ladb_list, home_pold);	/* �o�n�k�|�c�z�[���f�B���N�g�� */
	strcat(ladb_list, "/");
	strcat(ladb_list, LADB_LIST);

	if (d3fchk(ladb_list) != 0)	/* "ladb_list" ���Ȃ��Ȃ�A */
		ladb18(base_ladb);	/* Y_LADB ������l�Ƃ��Ċi�[���� */

	return(0);
}

/***************************************
 *	ladb04	SETUP GAMEN
 ***************************************/
static	ladb04(mes)
char	*mes;
{
	int	err, i;

	werase(pad);		/* GAMEN clear ! */

	d3pned(1);			/* display title */
	d3dred();			/* display current ladb */
	mvwaddstr(pad, 3, 18, SUB_TITLE); /* display ITEM NAME */

	d3fcmk(pf_label);	/* display SOFT LABEL KEY */
	mvwaddstr(pad, l_num-3, 2, mes);
 
	if (new_list == NEW_LIST) {
		if (mgno != -1) d3free(mgno);

		mgno = d3mgno();
		err=d3fitm(ladb_list, mgno, &cell_p, &cell_n);
		if (err != 0) PD_ERRCD(err, RET_ERROR);

		new_list = OLD_LIST;
	}

	if (first_flag) {
		for (i = 0; i < cell_n; i++) {
			if (strcmp(cur_ladb, cell_p[i].ptr) == 0) {
				c_pos = i;
				break;
			}
		}
		first_flag = 0;
	}

	return(0);
}

/*************************************
 *	�k�`�f�[�^�x�[�X�̐���
 *************************************/
static	ladb10()
{
	int	loop, err, i, j;
	chtype	rtn;
	struct	stat	stat_buf;
	char	new_ladb[MAX_DBNAME + 1], inbuf[MAX_DBNAME + 1];

	d3item(cell_n, cell_p, width, ITEM_DISP, &c_pos); /* ���]������ */
	d3fcmk(pf_label_2);		/* �f�[�^�x�[�X�����p�\�t�g���x�� */
	mvwaddstr(pad, l_num-3, 2, "�k�`�f�[�^�x�[�X������͂��Ă�������");
	loop = LOOP;
	while (loop) {
		PNOUTREFRESH ;
		wmove(pad, l_num-2, 2);
		for (i=0; i<MAX_DBNAME; inbuf[i++] = ' '); inbuf[i] = '\0';
		rtn = d3chin(l_num-2, 2, MAX_DBNAME, inbuf);
		switch (rtn) {
		case	0x0a:			/* ���^�[���L�[ */
			d3cven(inbuf);
			strcpy(new_ladb, inbuf);
			loop=LOOP_END;
			break;
		case KEY_F(17):			/* �����I�� */
		case KEY_F(18):			/* ����I�� */
			WCLRTOEOL(l_num-3, 2); WCLRTOEOL(l_num-2, 2);
			return(0);
			break;
		}
	}

	err = stat(new_ladb, &stat_buf);

	if (err == 0)		/* ���ɂk�`�f�[�^�x�[�X������ */
		PD_ERRCD(E_EXIST_LADB, RET_ERROR)
	else if (err != 0 && errno == ENOENT) {	/* �f�B���N�g�����Ȃ� */
		err = ladb12(new_ladb);		/* �f�B���N�g���̍쐬 */	
		if (err != 0) return(RET_ERROR);

		err = ladb22(new_ladb, UP_T, UP_M);   /* �O���[�v�����̍쐬 */
	} else
		PD_ERRNO(errno, RET_ERROR);	/* ���s�G���[ */

	return(0);
}

/****************************************************
 *	�k�`�f�[�^�x�[�X�̐��� ("ladb" �� mkdir)
 ****************************************************/
static	ladb12(new_ladb)
char	*new_ladb;
{
	char	*p, path_buf[128];
	struct	stat	stat_buf;
	int	err;

	strcpy(path_buf, new_ladb);
	p = path_buf;
	if ((p = strrchr(p, '/')) != NULL)
		*p = '\0';
	else
		PD_ERRCD(E_PATH_ERR, RET_ERROR);

	err = stat(path_buf, &stat_buf);	/* �e�f�B���N�g���͂��邩 */
	if (err != 0 && errno == ENOENT)
		PD_ERRCD(E_PATH_ERR, RET_ERROR);

	err = stat(new_ladb, &stat_buf);	/* �܂���������Ă��Ȃ��� */
	if (err == 0)
		PD_ERRCD(E_EXIST_LADB, RET_ERROR);

	if (mkdir(new_ladb, PD_LADB_MODE) != 0)
		PD_ERRCD(E_N, RET_ERROR);  /* �f�B���N�g�����쐬�ł��܂��� */

	ladb18(new_ladb);

	return(0);
}

/****************************************************
 *	�k�`�f�[�^�x�[�X�̓o�^ ("ladb_list" �ւ̊i�[)
 ****************************************************/
static	ladb18(new_ladb)
char	*new_ladb;
{
	char	dbbuf[128];
	int	i;
	D_CELL	*f;
 
	for (i=0, f=cell_p; i<cell_n; i++, f++) {
		if (strcmp(new_ladb, f->ptr) == 0) return(0);
	}
	if ((fp = fopen(ladb_list, "a")) == NULL)
		PD_ERRCD(E_TF_A, RET_ERROR);
	strcpy(dbbuf, new_ladb); strcat(dbbuf,"\n");

	fwrite(dbbuf, sizeof(char), strlen(dbbuf), fp);
	fclose(fp);

	new_list = NEW_LIST;
	return(0);
}


/*************************************
 *	�O���[�v�����̍쐬�E�X�V
 *************************************/
static	ladb20(cell_n, cell_p, c_pos, title, mode)
D_CELL	*cell_p;
int	cell_n, c_pos, mode;
char	*title;
{
	int	err, f_no;
	char	cur_ladb[128];

	if ((f_no =d3slct(cell_n, cell_p, 0)) == -1)
		f_no = c_pos;
	strcpy(cur_ladb, (cell_p + f_no)->ptr);

	err = ladb22(cur_ladb, title, mode);	/* �O���[�v�����̍X�V */

	return(0);
}

/*************************************
 *	�O���[�v�����̍쐬�E�X�V
 *************************************/
static	ladb22(cur_ladb, title, mode)
int	mode;
char	*cur_ladb, *title;
{
	int	err, rtn;
	char	main_title[80], path_name[128];
	struct	stat	stat_buf;

	err = stat(cur_ladb, &stat_buf);	/* �f�B���N�g���̃`�F�b�N */
	if (err == 0) {			/* �k�`�f�[�^�x�[�X�����݂��� */
		if (stat_buf.st_uid != geteuid())	/* ���L�҂��`�F�b�N */
			PD_ERRCD(E_NOT_OWNER, RET_ERROR);

		if ((stat_buf.st_mode & PD_LADB_MODE) != PD_LADB_MODE)
			PD_ERRCD(E_LADB_MODE, RET_ERROR);
					/* �p�[�~�b�V�����G���[ */

		d3path(cur_ladb, GROUP_INDEX, path_name);
		err = stat(path_name, &stat_buf);

		if (err != 0 && errno == ENOENT) { /* �O���[�v�������Ȃ� */
			err = d2mkgi(cur_ladb);    /* �O���[�v�����̍쐬 */
			if (err != 0)	PD_ERRCD(err, RET_ERROR);

		} else if (err != 0)
			PD_ERRNO(errno, RET_ERROR); /* ���s�G���[ */
	} else {
		if (errno == ENOENT)		/* �k�`�f�[�^�x�[�X���Ȃ� */ 
			PD_ERRCD(E_NEXST_LADB, RET_ERROR)
		else
			PD_ERRNO(errno, RET_ERROR);	/* ���s�G���[ */
	}

	sprintf(main_title, "     %-32s      < %-32s>",
		title, cur_ladb);

	d3tbhn(cur_ladb, GROUP_INDEX, mode, "", 1, 12, main_title);

	return(0);
}

/*********************************************
 *	�k�`�f�[�^�x�[�X�̏��
 *********************************************/
static	ladb40(cell_n, cell_p, c_pos)
D_CELL	*cell_p;
int	cell_n, c_pos;
{
	int	f;

	if ((f = d3slct(cell_n, cell_p, 0)) == -1) /* ������Ȃ��Ȃ� */
		f = c_pos;	/* �J�[�\���̌��݈ʒu�̃f�[�^�x�[�X�̏�� */

	d3fdsp((cell_p + f)->ptr, NULL);

	return(0);
}