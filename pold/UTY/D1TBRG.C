/*
	title:		�\�`���t�@�C���ǂݍ���
	command	name:	d1tbrg
	file name:	d1tbrg.c
	parameter:	ladir,ladata,dat_file,des_file,[-w]
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_tbrg ladir lads tbl_file des_file [-w]\n\
\n\
	ladir    : �k�`�f�[�^�x�[�X�f�B���N�g����\n\
	lads     : �k�`�f�[�^�Z�b�g��\n\
	tbl_file : �\�`���t�@�C����\n\
	des_file : �\�`����`�t�@�C����\n\
	-w       : �㏑�����t���O\n\
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
		rst =d1tbrg(argc, argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1tbrg(argc,argv)
int	argc;
char	*argv[];
{
	int	rst, i;
	int	w = 0;

/* check number of parameter */

	if(argc < 5)
		return(DE_PRM);

/* read the kind of parameters */

	for(i = 5; i < argc; ++i) {
		if(strcmp(argv[i], "-w") == 0) {
			if(w != 0)
				return(DE_PRM);
			else
				w = 1;
		} else
			return(DE_PRM);
	}

/* call	library */

	rst = d2tbrg(argv[1], argv[2], argv[3], argv[4], &w);
	return(rst);
}
