/*
	title:   	ユーザ索引ファイルの表示				
	command	name:	d1usgt
	file name:	d1usgt.c
	parameter:	ladir,uidfile
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_usgt ladir uidx\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	uidx  : ユーザ索引名\n\
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
		rst =d1usgt(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1usgt(argc,argv)
int	argc;
char	*argv[];	
{
	int	rst;

/* check number of parameter */

	if(argc != 3)
		return(DE_PRM);

/* call library */

	rst = d2usgt(argv[1],argv[2]);
	return(rst);
}
