/*
	title:		バックアップファイルメンテナンス
	command	name:	d1back
	file name:	d1back.c
	parameter:	[-r] [-t] [-d] ladir lads
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_back [-r] [-t] [-d] ladir lads\n\
\n\
	-r    : 復元処理\n\
	-t    : 一覧出力\n\
	-d    : 削除処理\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	lads  : ＬＡデータセット名\n\
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
		rst =d1back(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1back(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;
	int	mode;

/* check number of parameter */

	if(argc < 3)
		return(DE_PRM);

	if (strcmp(argv[1], "-r") == 0)
		mode = 0;
	else if (strcmp(argv[1], "-t") == 0)
		mode = 1;
	else if (strcmp(argv[1], "-d") == 0)
		mode = 2;
	else
		return(DE_PRM);

	if (mode == 1) {
		if (argc != 3)
			return(DE_PRM);
		rst = d2back(argv[2], 0, &mode);
	} else {
		if (argc != 4)
			return(DE_PRM);
		rst = d2back(argv[2], argv[3], &mode);
	}

	return(rst);
}
