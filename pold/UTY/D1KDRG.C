/*
	title:		表形式データセットの登録		
	command	name:	d1kdrg
	file name:	d1kdrg.c
	parameter:	ladir,ladata,[-s sfile],
			[-k kfile],-w
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_kdrg ladir lads [-s sfile] [-k kfile] [-w]\n\
\n\
	ladir : ＬＡデータベースディレクトリ名\n\
	lads  : ＬＡデータセット名\n\
	sfile : 説明部ファイル名\n\
	kfile : 表形式ファイル名\n\
	-w    : 上書き許可フラグ\n\
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
		rst =d1kdrg(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1kdrg(argc,argv)
int	argc;
char	*argv[];
{	
	int	rst,i = 0;
	char	*avs,*avk,*avw;
	avs = NULL;
	avk = NULL;
	avw = NULL ;

/* check number of parameter */
	if(argc < 4)
		return(DE_PRM);

/* read the kind of parameters */
	else{
		for(i = 3 ; i < argc ; ++i){
			if(strcmp(argv[i],"-s") == 0){
				if(++i == argc || avs != NULL)

					return(DE_PRM);
				else
					avs = argv[i];
			}
			else if(strcmp(argv[i],"-k") == 0){
				if(++i == argc || avk != NULL)

					return(DE_PRM);
				else
					avk = argv[i];
			}						
			else if(strcmp(argv[i],"-wu") == 0){
				if ( avw != NULL ) return ( DE_PRM ) ;
				else  avw = "u" ;
			}
			else if(strcmp(argv[i],"-wc+") == 0){
				if ( avw != NULL ) return ( DE_PRM ) ;
				else  avw = "c+" ;
			}
			else if(strcmp(argv[i],"-wc") == 0){
				if ( avw != NULL ) return ( DE_PRM ) ;
				else  avw = "c" ;
			}
			else
				return(DE_PRM);
		}
	}
	if ( avw == NULL )
		avw = "c";

/* call library */

	rst = d2kdrg(argv[1],argv[2],avs,avk,avw);
	return(rst);
}	
