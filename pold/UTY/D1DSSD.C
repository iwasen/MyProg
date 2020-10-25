/*
	title:		外部表現ファイル抽出
	command	name:	d1dssd
	file name:	d1dssd.c
	parameter:	ladir,ladata,ofile
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_dssd ladir lads gfile\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	lads  : ＬＡデータセット名\n\
	gfile : 外部表現ファイル名\n\
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
		rst =d1dssd(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1dssd(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;

/* check number of parameter */

	if(argc != 4)
		return(DE_PRM);

/* call library */

	rst = d2dssd(argv[1],argv[2],argv[3]);
	return(rst);
}
