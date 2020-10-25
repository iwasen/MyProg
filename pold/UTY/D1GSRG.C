/*
	title:		グループ索引の登録			
	command	name:	d1gsrg
	file name:	d1gsrg.c
	parameter:	ladir,ifile
							*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_gsrg ladir gefile\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	gefile: グループ索引編集ファイル名\n\
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
		rst =d1gsrg(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1gsrg(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;

/* check number of parameter */

	if(argc != 3)
		return(DE_PRM);

	rst = d2gsrg(argv[1],argv[2]);
	return(rst);
}
