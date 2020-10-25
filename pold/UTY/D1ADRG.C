/*
	title:		�j�f�[�^�Z�b�g����k�`�f�[�^�Z�b�g�����E�o�^		
	command	name:	d1adrg
	file name:	d1adrg.c
	parameter:	ladir,ladata,[-s sfile],[-l lfile]
			[-k kfile],[-w]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_adrg ladir lads [-s sfile] [-l lfile] [-k kfile] [-w]\n\
\n\
	ladir : �k�`�f�[�^�x�[�X�f�B���N�g����\n\
	lads  : �k�`�f�[�^�Z�b�g��\n\
	sfile : �������t�@�C����\n\
 	lfile : ���x�����t�@�C����\n\
	kfile : �j�f�[�^�Z�b�g�t�@�C����\n\
	-w    : �㏑�����t���O\n\
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
		d3cmsg();			/* �V�O�i������ */
		rst =d1adrg(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}

	exit ( rst ) ;
}

static
d1adrg(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i,w = 0;
	char	*avs,*avl,*avk;
	avs = NULL;
	avl = NULL;
	avk = NULL;

/* check number of parameter */

	if(argc < 3)
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
			else if(strcmp(argv[i],"-l") == 0){
				if(++i == argc || avl != NULL)

					return(DE_PRM);
				else
					avl = argv[i];	
			}
			else if(strcmp(argv[i],"-k") == 0){
				if(++i == argc || avk != NULL)

					return(DE_PRM);
				else
					avk = argv[i];
			}						
			else if(strcmp(argv[i],"-w") == 0){
				if(w != 0)
					return(DE_PRM);
				else
					w = 1;
			}
			else
				return(DE_PRM);
		}
	}

/* call library */
	rst = d2adrg(argv[1],argv[2],avs,avl,avk,&w);
	return(rst);
}	