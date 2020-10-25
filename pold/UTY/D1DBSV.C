/*
	title:		データセット保存			
	command	name:	d1dbsv
	file name:	d1dbsv.c
	parameter:	ladir,[-f dev],[-s size],[-k 0-6],[name]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_dbsv ladir [-f dev] [-s size] [-k 0-6] [name]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	dev   : デバイス名\n\
	size  : 媒体容量\n\
	0-6   : 保存種別\n\
	name  : 保存対象名\n\
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
		rst =d1dbsv(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1dbsv(argc,argv)
int	argc;
char	*argv[];
{
	int	rst, i, kind, n;
	char	*fdev, **name, *kp, *size;

	fdev = NULL;
	kind = NULL;
	name = NULL;
	n = NULL;
	size = NULL;

	/* check number of parameter */

	if(argc < 2)
		return(DE_PRM);

	/* read the kind of parameters */

	for(i = 2 ; i < argc ; ++i){
		if(strcmp(argv[i],"-f") == 0){
			if(++i == argc || fdev != 0)
				return(DE_PRM);
			else
				fdev = argv[i];
		}
		else if(strcmp(argv[i],"-s") == 0){
			if(++i == argc || size != 0)
				return(DE_PRM);
			else
				size = argv[i];
		}
		else if (strncmp(argv[i],"-k",2) == 0){
			if (kind != 0)
				return(DE_PRM);
			if (strlen(argv[i]) == 2) {
				if (++i == argc)
					return(DE_PRM);
				kp = argv[i];
			} else
				kp = argv[i] + 2;

			if (*kp < '0' || *kp > '6' || strlen(kp) != 1)
				return(DE_PRM);
			else
				kind = *kp - '0';
		}
		else{
			name = &argv[i];
			n = argc - i;
			break;
		}
	}

	/* call library */

	rst = d2dbsv(argv[1],fdev,size,&kind,name,&n);
	return(rst);
}
