/********************************************************
*							*
*	ＢＡＳＩＣ　ＴＯ　Ｃ　トランスレータ　		*
*							*
*		メイン　プログラム			*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.02.25	*
*			file name     : main.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<string.h>
#include	"trans.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

extern	void	exit(int);
void strmfe();
void getfname();
void setfname();


/*	メイン　プログラム	*/

main(argc,argv)
int	argc;
char	*argv[];
{
	int	i;
	char	bflag, cflag, *p;

	printf("Basic to C source Translator  version 2.01.03\n");

	bflag = cflag = 0;
	for (i = 1; i < argc; i++) {
		p = argv[i];
		if (*p == '-') {	/* option ?		*/
			switch (*(p+1)) {
			case 'S':
			case 's':
				o_src = 1;
				break;
			case 'F':
			case 'f':
				p += 2;
				if (*p == 0)
					o_lnsize = 50;
				else
					sscanf(p,"%d",&o_lnsize);
				break;
			case 'D':
			case 'd':
				erl_flag = 1;
				break;
			default:
				printf("Invalid option \"%s\"\n",p);
				exit(1);
			}
			continue;
		}
		if (bflag == 0) {
			setfname(p,"BAS",b_file);/* set basic file name	*/
			bflag = 1;
		} else if (cflag == 0) {
			setfname(p,"C",c_file);	/* set C file name	*/
			cflag = 1;
		}
	}

	if (cflag == 0) {
		if (bflag == 0)
			getfname();		/* get file name	*/
		else
			strmfe(c_file,b_file,"C"); /* set C file name	*/
	}
	printf("Translating %s --> %s\n",b_file,c_file);/* display file name	*/

	if ((bfp = fopen(b_file,"r")) == NULL) {	/* open basic file */
		printf("can't open: %s\n",b_file);
		exit(1);
	}
	
	if ((cfp = fopen(c_file,"w")) == NULL) {	/* open C file	*/
		printf("can't open: %s\n",c_file);
		exit(1);
	}

	pass1();			/* pass 1 process	*/
	pass2();			/* pass 2 process	*/

	cputc((char)0x1a);		/* write EOF		*/

	if (err_count == 0) {		/* error ari ?		*/
		printf("\nno error\n");	/* display "no error"	*/
		exit(0);
	} else {
		printf("\n%d error(s)\n",err_count);	/* display error */
		exit(1);
	}
}


/*	ファイル名入力		*/

static void getfname()
{
	char	fn[32], *s;

	printf("Basic file name [.BAS]: ");
	gets(fn);				/* input basic file name */
	setfname(fn,"BAS",b_file);		/* set file name	*/

	if ((s = strchr(fn,'.')) != NULL)
		*s = NULL;

	printf("C file name [%s.C]: ",fn);
	gets(fn);				/* input C file name	*/
	if (fn[0] == NULL)
		strmfe(c_file,b_file,"C");
	else
		setfname(fn,"C",c_file);	/* set file name	*/

}


/*	ファイル名セット	*/

static void setfname(fni,ex,fno)
char	*fni, *ex, *fno;
{
	if (strchr(fni,'.') == NULL)
		strmfe(fno,fni,ex);		/* make file name	*/
	else
		strcpy(fno,fni);		/* copy file name	*/
}


/*	ファイル名作成	*/

static void strmfe(fd, fs, ex)
char	*fd, *fs, *ex;
{
	while (*fs && *fs != '.')
		*fd++ = *fs++;
	*fd++ = '.';
	while (*fd++ = *ex++) {}
}
