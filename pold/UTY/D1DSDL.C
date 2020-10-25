/*
	title:		データセット削除				
	command	name:	d1dsdl
	file name:	d1dsdl.c
	parameter:	ladir,[-nc],ladat,,,
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_dsdl ladir [-nc] lads ...\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	-nc   : 連続処理フラグ\n\
	lads  : ＬＡデータセット名\n\
";

main(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;

	if (argc == 1) {
		fprintf(stderr, usage);
		rst = DE_PRM;
	} else {
		d3cmsg();			/* シグナル処理 */
		rst =d1dsdl(argc,argv);
	}

	exit(rst);
}

static
d1dsdl(argc,argv)
int	argc;
char	*argv[];
{	
	int	rst,i=2,nc=1;
	char	check;
	char	msgbuf[80];

/* number of parameter check */

	if(argc < 3)
		return(DE_PRM);

/* kakunin ari nashi check */

	if(strcmp(argv[i],"-nc") == 0){
		nc = 0;
		if(++i == argc)
			return(DE_PRM);
	}

/* call library with kakunin */

	for(i = i ; i < argc ; i++){
		if(nc == 1){
			printf("delete %s Yes or No? ",argv[i]);
			while(1){
				scanf("%c",&check);
				if(check == 'y' || check == 'Y'){
					break;
				}
				else if(check == 'n' || check == 'N')
					goto	owari;
			}
		}
 		rst = d2dsdl(argv[1],argv[i]);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
owari:;
	}
	return(rst);
}
