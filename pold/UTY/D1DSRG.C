/*
	title:		データセット登録		
	command	name:	d1dsrg
	file name:	d1dsrg.c
	parameter:	oladir,oladata,path_idata,[-w]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_dsrg ladir lads dsfile [-w]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	lads  : ＬＡデータセット名\n\
	dsfile: ＬＡデータセットファイル名\n\
	-w    : 上書き許可フラグ\n\
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
		rst =d1dsrg(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1dsrg(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i;
	int	w = 0;

/* check number of parameter */

	if(argc < 4)
		return(DE_PRM);

/* read the kind of parameters */

	for(i = 4 ; i < argc ; ++i){
		if(strcmp(argv[i],"-w") == 0){
			if(w != 0)
				return(DE_PRM);
			else
				w = 1;
		}
		else
			return(DE_PRM);
	}

/* call library */

	rst = d2dsrg(argv[1],argv[2],argv[3],&w);
	return(rst);
}
