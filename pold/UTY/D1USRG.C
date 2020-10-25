/*
	title:		���[�U�����t�@�C���̓o�^				
	command	name:	d1usrg
	file name:	d1usrg.c
	parameter:	ladir,uidx,uidfile,[-w]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_usrg ladir uidx ufile [-w]\n\
\n\
	ladir : �k�`�f�[�^�x�[�X�f�B���N�g����\n\
	uidx  : ���[�U������\n\
	ufile : ���[�U�����ҏW�t�@�C����\n\
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
		rst =d1usrg(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1usrg(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i,w = 0;

/* check number of parameter */

	if(argc < 4)
		return(DE_PRM);

/* read the kind of parameters */

	else{
		for(i=4;i<argc;++i){
			if(strcmp(argv[i],"-w") == 0){
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

	rst = d2usrg(argv[1],argv[2],argv[3],&w);
	return(rst);
}