/********************************************************
*							*
*	ＢＡＳＩＣ　ＴＯ　Ｃ　トランスレータ　		*
*							*
*		ＰＡＳＳ２処理				*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.03.04	*
*			file name     : pass2.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<string.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

void	p2_init();
void	ot_pripro();
void	ot_var();
void	ot_sconst();
void	ot_file();
void	ot_usr();
void	ot_init();
extern	void	ot_data();
extern	void	ot_deffn();
extern	void	ot_main();
extern	void	indent();

/*	ｐａｓｓ２　メイン処理		*/

void pass2()
{
	printf("--- pass 2 ---\n");	/* pass 2 start message		*/

	ot_pripro();
	ot_var();
	ot_sconst();
	ot_file();
	ot_data();
	ot_usr();
	ot_init();
	ot_deffn();
	ot_main();
}


/*	プリプロセッサコマンド出力	*/

static void ot_pripro()
{
	if (rbufsiz != 128)
		fprintf(cfp,"#define\tRBUFSIZ\t%d\n",rbufsiz);
	cputs("#include \"baslib.h\"\n\n");
}


/*	変数定義	*/

static void ot_var()
{
	int	i;
	char	d;
	VAR	*vp;
	char	sbuf[32];

	for (i = 0, vp = vartbl; i != vnum; i++, vp++) {
		if (vp->use_flag == 0)
			continue;

		switch (vp->vartype) {
		case V_CHR:
			cputs("char\t");
			break;
		case V_INT:
			cputs("int\t");
			break;
		case V_LNG:
			cputs("long\t");
			break;
		case V_SNG:
			cputs("float\t");
			break;
		case V_DBL:
			cputs("double\t");
			break;
		case V_STR:
			strcpy(sbuf, vp->vnamep);
			*(strrchr(sbuf, '_') + 1) = 'b';
			fprintf(cfp,"char\t%s",sbuf);
			for (d = 0; d != vp->dim; d++)
				fprintf(cfp,"[%d]",vp->dimbufp[d]+1);
			fprintf(cfp,"[%d];\n",(int)vp->strsiz);
			cputs("STRTBL\t");
			break;
		}
		cputs(vp->vnamep);
		for (d = 0; d != vp->dim; d++)
			fprintf(cfp,"[%d]",vp->dimbufp[d]+1);
		if (vp->vartype == V_STR && vp->dim == 0)
			fprintf(cfp," = {0,%s}",sbuf);
		cputs(";\n");
	}
	cputc((char)'\n');
}


/*	文字定数定義	*/

static void ot_sconst()
{
	int	i;
	char	*s;

	if (cnum != 0) {
		cputs("STRTBL\tconst_s[] = {\n");
		i = 0;
		while (1) {
			s = sctbl[i];
			fprintf(cfp,"\t%u,\"%s\"",strlen(s),s);
			if (++i == cnum)
				break;
			cputs(",\n");
		}
		cputs("\n};\n\n");
	}
}


/*	ファイルバッファ定義	*/

static void ot_file()
{
	fprintf(cfp,"FB\tf_buff[%d];\n\n", file+1);
}


/*	ＵＳＲ変数定義		*/

static void ot_usr()
{
	char	i;

	for (i = 0; i < 10; i++) {
		if (usrtbl[i])
			fprintf(cfp,"int\tusr%d;\n",(int)i);
	}
	cputc((char)'\n');
}


/*	初期化処理出力		*/

static void ot_init()
{
	int	i;
	char	d, maxdim;
	VAR	*vp;
	char	sbuf[32];

	cputs("init()\n{\n");
	tab = 1;

	maxdim = 0;
	for (i = 0, vp = vartbl; i != vnum; i++, vp++) {
		if (vp->vartype == V_STR && maxdim < vp->dim && vp->use_flag)
			maxdim = vp->dim;
	}
	if (maxdim) {
		cputs("\tint\t");
		for (i = 0; ; ) {
			fprintf(cfp,"w%d",i);
			if (++i >= maxdim)
				break;
			cputc((char)',');
		}
		cputs(";\n\n");
	}

	for (i = 0, vp = vartbl; i != vnum; i++, vp++) {
		if (vp->vartype == V_STR && vp->dim != 0 && vp->use_flag) {
			strcpy(sbuf, vp->vnamep);
			*(strrchr(sbuf, '_') + 1) = 'b';
			for (d = 0; d < vp->dim; d++) {
				indent();
				fprintf(cfp,"for (w%d = 0; w%d <= %d; w%d++) {\n",d,d,vp->dimbufp[d],(int)d);
				tab++;
			}
			indent();
			cputs(vp->vnamep);
			for (d = 0; d < vp->dim; d++)
				fprintf(cfp,"[w%d]",(int)d);
			cputs(".s_len = 0;\n");

			indent();
			cputs(vp->vnamep);
			for (d = 0; d < vp->dim; d++)
				fprintf(cfp,"[w%d]",(int)d);
			cputs(".s_str = ");
			cputs(sbuf);
			for (d = 0; d < vp->dim; d++)
				fprintf(cfp,"[w%d]",(int)d);
			cputs(";\n");

			while (tab > 1) {
				--tab;
				indent();
				cputs("}\n");
			}
		}
	}

	if (dt_flag)
		fprintf(cfp,"\tdata_ptr = D%u;\n",fst_data);

	if (on_key_flag)
		cputs("\tb_keyint();\n");

	cputs("\tb_cursor_off();\n");
	cputs("}\n\n");
}
