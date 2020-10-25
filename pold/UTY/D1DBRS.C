/*
	title:		データセット復元
	command	name:	d1dbrs
	file name:	d1dbrs.c
	parameter:	ladir,[-f dev]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_dbrs ladir [-f dev]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	dev   : デバイス名\n\
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
		rst =d1dbrs(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1dbrs(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i;
	char	*avv;
	avv = NULL;

/* check number of parameter */

	if(argc < 2)
		return(DE_PRM);

/* read the kind of parameters */

	for(i = 2 ; i < argc ; ++i){
		if(strcmp(argv[i],"-f") == 0){
			if(++i == argc || avv != NULL)

				return(DE_PRM);
			else
				avv = argv[i];
		}
		else
			return(DE_PRM);
	}

/* call library */

	rst = d2dbrs(argv[1],avv);
	return(rst);
}
