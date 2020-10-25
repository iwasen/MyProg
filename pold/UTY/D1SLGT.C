/*
	title:		説明部・ラベル部の表示			
	command	name:	d1slgt
	file name:	d1slgt.c
	parameter:	ladir,ladata,[-s sfile],[-l lfile]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_slgt ladir lads [-s sfile] [-l lfile]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	lads  : ＬＡデータセット名\n\
	sfile : 説明部ファイル名\n\
 	lfile : ラベル部ファイル名\n\
";

main(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;
	char	msgbuf[80];

	if (argc == 1) {
		fprintf(stderr, usage);
		rst = DE_PRM;
	} else {
		d3cmsg();			/* シグナル処理 */
		rst =d1slgt(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}	

static
d1slgt(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i;
	char	*avs,*avl;
	avs = NULL;
	avl = NULL;
/* check number of parameter */

	if(argc < 3)
		return(DE_PRM);
/* read the kind of parameters */

	else{
		for(i = 3 ; i < argc ; ++i){
			if(strcmp(argv[i],"-s") == 0){
				if(++i == argc || avs !=NULL )
					return(DE_PRM);
				else
					avs = argv[i];
			}
			else if(strcmp(argv[i],"-l") == 0){
				if(++i == argc || avl != NULL)
					return(DE_PRM);
				else
					avl = argv[i];	
			}
			else
				return(DE_PRM);
		}
	}

/* call library */

	rst = d2slgt(argv[1],argv[2],avs,avl);
	return(rst);
}	
