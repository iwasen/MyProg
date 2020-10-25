/*
	title:		�\�`���f�[�^�Z�b�g�̕\��			
	command	name:	d1kdgt
	file name:	d1kdgt.c
	parameter:	ladir,ladata
								*/
#include	<stdio.h>
#define	DE_PRM	3

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

static	char	usage[] =
"usage: d1_kdgt ladir lads\n\
\n\
	ladir : �k�`�f�[�^�x�[�X�f�B���N�g����\n\
	lads  : �k�`�f�[�^�Z�b�g���i�\�f�[�^�Z�b�g�j\n\
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
		rst =d1kdgt(argc,argv);
		if (rst != 0) {
			d3erms(rst, msgbuf);
			fprintf(stderr, "%s: %s\n", argv[0], msgbuf);
		}
	}
	exit(rst);
}

static
d1kdgt(argc,argv)
int	argc;
char	*argv[];
{
	int	rst;

/* check number of parameter */
	if(argc != 3)
		return(DE_PRM);

/* call liberary */

	rst = d2kdgt(argv[1],argv[2]);
	return(rst);
}