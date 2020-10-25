/*
	title:		条件検索
	command	name:	d1find
	file name:	d1find.c
	parameter:	ladir,idfile,-f,kfile,[-i]
			ladir,idfile,"cond",[-i]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_find ladir idx -f cfile [-i]\n\
       d1_find ladir idx \"cond\" [-i]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	idx   : 索引ファイル名（表データセット）\n\
	cfile : 検索条件ファイル名\n\
	cond  : 検索条件\n\
	-i    : 検索ＬＡデータセット指定フラグ\n\
";

main(argc, argv)
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
		rst =d1find(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
}

static	
d1find(argc, argv)
int	argc;
char	*argv[];
{
	int	rst, i, avi = 0, avf;

	/* check number of parameter */

	if (argc < 4)
		return(DE_PRM);

	/* read the kind of parameters */

	if (strcmp(argv[3], "-f") == 0) {
		if (argc < 5)
			return(DE_PRM);

		for (i = 5 ; i < argc ; ++i) {
			if (strcmp(argv[i], "-i") == 0) {
				if (avi != 0)
					return(DE_PRM);
				else
					avi = 1;
			} else
				return(DE_PRM);
		}

		avf = 1;
		/* call	library */

		rst = d2find(argv[1], argv[2], &avf, argv[4], &avi);
		return(rst);
	} else {
		if (argc < 4)
			return(DE_PRM);

		for (i = 4 ; i < argc ; ++i) {
			if (strcmp(argv[i], "-i") == 0) {
				if (avi != 0)
					return(DE_PRM);
				else
					avi = 1;
			} else
				return(DE_PRM);
		}

		avf = 0;

		rst = d2find(argv[1], argv[2], &avf, argv[3], &avi);
		return(rst);
	}
}


