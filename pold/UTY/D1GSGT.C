/*
	title:		グループ索引ファイルの表示				
	command	name:	d1gsgt
	file name:	d1gsgt.c
	parameter:	ladir
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_gsgt ladir\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
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
		rst =d1gsgt(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1gsgt(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;

/* check number of parameter */

	if(argc != 2)
		return(DE_PRM);
/* call liberary */

	rst = d2gsgt(argv[1]);
		return(rst);
}
