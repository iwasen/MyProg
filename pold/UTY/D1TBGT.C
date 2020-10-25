/*
	title:		表形式ファイル出力
	command	name:	d1tbgt
	file name:	d1tbgt.c
	parameter:	ladir,ladata,col,sp
								*/
#include	<stdio.h>
#include	<string.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_tbgt ladir lads [-c col] [-s sp]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	lads  : ＬＡデータセット名\n\
	col   : 出力列番号（複数指定可，省略時は全列）\n\
	sp    : セパレータ（省略時は空白）\n\
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
		rst =d1tbgt(argc, argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1tbgt(argc,argv)
int	argc;
char	*argv[];
{
	int	rst, i, j;
	char	*sp, *p;
	int	col_n, s_col, e_col, col_tbl[1024];

/* check number of parameter */

	if(argc < 3)
		return(DE_PRM);

/* read the kind of parameters */

	col_n = 0;
	sp = 0;

	for(i = 3; i < argc; ++i) {
		if(strcmp(argv[i], "-c") == 0) {
			if(++i == argc)
				return(DE_PRM);
			if ((p = strchr(argv[i], '-')) != 0) {
				*p = '\0';
				s_col = atoi(argv[i]);
				e_col = atoi(p + 1);
				if (s_col <= 0 || s_col > e_col)
					return(DE_PRM);
				for (j = s_col; j <= e_col; j++)
					col_tbl[col_n++] = j;
			} else {
				s_col = atoi(argv[i]);
				if (s_col <= 0)
					return(DE_PRM);
				col_tbl[col_n++] = s_col;
			}
		} else if (strcmp(argv[i], "-s") == 0) {
			if(++i == argc)
				return(DE_PRM);
			sp = argv[i];
		} else
			return(DE_PRM);
	}

	col_tbl[col_n] = 0;

/* call	library */

	rst = d2tbgt(argv[1], argv[2], 0, col_n ? col_tbl : 0, sp);

	return(rst);
}

