/*
	title:		�O���\���t�@�C���i�[
	command	name:	d1dsrc
	file name:	d1dsrc.c
	parameter:	ladir,ladata,ifile,[-w]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_dsrc ladir lads gfile [-w]\n\
\n\
	ladir : �k�`�f�[�^�x�[�X�f�B���N�g����\n\
	lads  : �k�`�f�[�^�Z�b�g��\n\
	gfile : �O���\���t�@�C����\n\
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
		rst =d1dsrc(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1dsrc(argc,argv)
int	argc;
char	*argv[];
{
	int	rst,i;
	int	w = 0;
	char	*dsname;

/* check number of parameter */

	if(argc < 4)
		return(DE_PRM);

/* read the kind of parameters */

	else{
		for(i = 4 ; i < argc ; ++i){
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

/* call	library */

	if (strcmp(argv[2], "-") == 0)
		dsname = 0;
	else
		dsname = argv[2];

	rst = d2dsrc(argv[1],dsname,argv[3],&w);
	return(rst);
}
