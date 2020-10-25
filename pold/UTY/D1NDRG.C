/*
	title:		�x�f�[�^�Z�b�g����k�`�f�[�^�Z�b�g�����E�o�^		
	command	name:	d1ndrg
	file name:	d1ndrg.c
	parameter:	ladir,ladata,[-s sfile],[-l lfile]
			[-b bfile],[-w]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_ndrg ladir lads [-s sfile] [-l lfile] [-b bfile] [-w]\n\
\n\
	ladir : �k�`�f�[�^�x�[�X�f�B���N�g����\n\
	lads  : �k�`�f�[�^�Z�b�g��\n\
	sfile : �������t�@�C����\n\
 	lfile : ���x�����t�@�C����\n\
	bfile : �x�f�[�^�Z�b�g�t�@�C����\n\
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
		rst =d1ndrg(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1ndrg(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i,w = 0;
	char	*avs,*avl,*avb;
	avs = NULL;
	avl = NULL;
	avb = NULL;

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
			else if(strcmp(argv[i],"-b") == 0){
				if(++i == argc || avb != NULL)

					return(DE_PRM);
				else
					avb = argv[i];
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

	rst = d2ndrg(argv[1],argv[2],avs,avl,avb,&w);
	return(rst);
}