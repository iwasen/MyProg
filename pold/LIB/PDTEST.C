/*	ＰＯＬ－Ｄ部品　テストプログラム	*/

#include	<stdio.h>
#include	<sys/types.h>
#include	<sys/stat.h>
#include	<dirent.h>
#include	<errno.h>
#include	"la_ws/include/d3nmty.h"
#include	"la_ws/include/d3libc.h"
#include	"la_ws/include/d3lmac.h"

extern	DIR	*opendir();
extern	struct	dirent	*readdir();
extern	DSET	*d3lbgn();
extern	int	d3lend();
extern	double	atof();

static	int	templete[1024];
static	int	buf[1024*10];

static	DSET	*dsp;
static	int	err, row, col, n, attrib, type, relop, op, in, sync, compno, option;
static	char	label[80], term[8], val[128], ds_name[80], index_name[80];
static	int	from, nitems, pos;
static	char	**keyword;
static	char	start[128], compname[128], workbuf[1024], *wp, *tp[16];
static	char	newname[128];
static	PD_CNDREC	cond;
static	PD_TFREC	tf;
static	PD_LNREC	line;
static	PD_ARYREC	attr;
static	PD_CMPREC	wa_rec;
static	PD_KEYREC	*keyrec;
static	PD_DENT		box;
static	DMEM		*memp;

/************************************************/
/*		メイン処理			*/
/************************************************/
main(argc, argv)
int	argc;
char	*argv[];
{
	char	func[80];

	if (argc > 1) {
		if (strcmp(argv[1], "-") == 0)
			;
		else if (strcmp(argv[1], "-h") == 0) {
			t_t_help();
			exit(0);
		} else
			t_lbgn(argv[1]);
	} else {
		t_lbgn(NULL);
	}

	for (;;) {
		printf("function ? ");
		scanf("%s",func);
		if (func[0] == '!')
			system(&func[1]);
		else if (strcmp(func, "lbgn") == 0)
			t_lbgn(NULL);
		else if (strcmp(func, "tcol") == 0)
			t_tcol();
		else if (strcmp(func, "tdl") == 0)
			t_tdl();
		else if (strcmp(func, "tdlc") == 0)
			t_tdlc();
		else if (strcmp(func, "tdlv") == 0)
			t_tdlv();
		else if (strcmp(func, "tinc") == 0)
			t_tinc();
		else if (strcmp(func, "tin") == 0)
			t_tin();
		else if (strcmp(func, "trd") == 0)
			t_trd();
		else if (strcmp(func, "trdv") == 0)
			t_trdv();
		else if (strcmp(func, "trow") == 0)
			t_trow();
		else if (strcmp(func, "tup") == 0)
			t_tup();
		else if (strcmp(func, "srch") == 0)
			t_srch();
		else if (strcmp(func, "toln") == 0)
			t_toln();
		else if (strcmp(func, "totf") == 0)
			t_totf();
		else if (strcmp(func, "fidx") == 0)
			t_fidx();
		else if (strcmp(func, "aryr") == 0)
			t_aryr();
		else if (strcmp(func, "aryw") == 0)
			t_aryw();
		else if (strcmp(func, "desn") == 0)
			t_desn();
		else if (strcmp(func, "cdes") == 0)
			t_cdes();
		else if (strcmp(func, "defn") == 0)
			t_defn();
		else if (strcmp(func, "pdes") == 0)
			t_pdes();
		else if (strcmp(func, "axin") == 0)
			t_axin();
		else if (strcmp(func, "caxi") == 0)
			t_caxi();
		else if (strcmp(func, "axfn") == 0)
			t_axfn();
		else if (strcmp(func, "paxi") == 0)
			t_paxi();
		else if (strcmp(func, "tkey") == 0)
			t_tkey();
		else if (strcmp(func, "tkyv") == 0)
			t_tkyv();
		else if (strcmp(func, "clky") == 0)
			t_clky();
		else if (strcmp(func, "mktp") == 0)
			t_mktp();
		else if (strcmp(func, "ccmp") == 0)
			t_ccmp();
		else if (strcmp(func, "dcmp") == 0)
			t_dcmp();
		else if (strcmp(func, "ucmp") == 0)
			t_ucmp();
		else if (strcmp(func, "delm") == 0)
			t_delm();
		else if (strcmp(func, "uelm") == 0)
			t_uelm();
		else if (strcmp(func, "gent") == 0)
			t_gent();
		else if (strcmp(func, "ucnm") == 0)
			t_ucnm();
		else if (strcmp(func, "clcm") == 0)
			t_clcm();
		else if (strcmp(func, "dlcm") == 0)
			t_dlcm();
		else if (strcmp(func, "upcm") == 0)
			t_upcm();
		else if (strcmp(func, "dlel") == 0)
			t_dlel();
		else if (strcmp(func, "upel") == 0)
			t_upel();
		else if (strcmp(func, "gtet") == 0)
			t_gtet();
		else if (strcmp(func, "upnm") == 0)
			t_upnm();
		else if (strcmp(func, "istb") == 0)
			t_istb();
		else if (strcmp(func, "rdcm") == 0)
			t_rdcm();
		else if (strcmp(func, "incm") == 0)
			t_incm();
		else if (strcmp(func, "rdel") == 0)
			t_rdel();
		else if (strcmp(func, "inel") == 0)
			t_inel();
		else if (strcmp(func, "lend") == 0)
			t_lend();
		else if (strcmp(func, "cvre") == 0)
			t_cvre();
		else if (strcmp(func, "nmty") == 0)
			t_nmty();
		else if (strcmp(func, "rgex") == 0)
			t_rgex();
		else if (strcmp(func, "tcpc") == 0)
			t_tcpc();
		else if (strcmp(func, "tmvc") == 0)
			t_tmvc();
		else if (strcmp(func, "tcpr") == 0)
			t_tcpr();
		else if (strcmp(func, "tmvr") == 0)
			t_tmvr();
		else if (strcmp(func, "p") == 0) {
			if (dsp != NULL)
				d3dput(dsp->ds_entry, stdout);
		}else if (strcmp(func, "t") == 0)
			dsp_tbl();
		else if (strcmp(func, "c") == 0)
			ds_clear();
		else if (strcmp(func, "wq") == 0) {
			if (dsp != NULL) d3lend(dsp, "wq", 0);
			exit(0);
		} else if (strcmp(func, "q") == 0)
			break;
		else if (strcmp(func, "h") == 0)
			t_t_help();
		else
			printf("%s ???\n", func);
	}

	if (dsp != NULL) d3lend(dsp, "q", 0);
	exit(0);
}

/************************************************/
/*	d3lbgn() : データセット使用開始		*/
/************************************************/
t_lbgn(lads)
char	*lads;
{
	char	file_name[80], mode[10];

	printf("*** d3lbgn ***\n");
	if (dsp != NULL) return(0);

	if (lads == NULL) {
		printf("file name = ");
		scanf("%s",file_name);
	} else {
		printf("file name = %s\n", lads);
		strcpy(file_name, lads);
	}
	printf("mode      = ");
	scanf("%s",mode);
	printf("option    = ");
	scanf("%x",&option);

	dsp = d3lbgn(file_name, mode, option, &err);
	err_disp();
}

/************************************************/
/*	d3tcol() : 列数取り出し			*/
/************************************************/
t_tcol()
{
	printf("*** d3tcol ***\n");
	if (dsp == NULL) return(0);

	err = d3tcol(dsp);
	err_disp();
}

/************************************************/
/*	d3tdl() : ｎ行削除			*/
/************************************************/
t_tdl()
{
	printf("*** d3tdl ***\n");
	if (dsp == NULL) return(0);

	printf("row = ");
	scanf("%d", &row);
	printf("n   = ");
	scanf("%d", &n);

	err = d3tdl(dsp, row, n);
	err_disp();
}

/************************************************/
/*	d3tdlc() : ｎ列削除			*/
/************************************************/
t_tdlc()
{
	printf("*** d3tdlc ***\n");
	if (dsp == NULL) return(0);

	printf("col = ");
	scanf("%d", &col);
	err = d3tdlc(dsp, col);
	err_disp();
}

/************************************************/
/*	d3tdlv() : 任意行の削除			*/
/************************************************/
t_tdlv()
{
	printf("*** d3tdlv ***\n");
	if (dsp == NULL) return(0);

	err = d3tdlv(dsp, &line);
	err_disp();
}

/************************************************/
/*	d3tinc() : １列挿入			*/
/************************************************/
t_tinc()
{
	printf("*** d3tinc ***\n");
	if (dsp == NULL) return(0);

	printf("col    = ");
	scanf("%d", &col);
	printf("attrib = ");
	scanf("%d", &attrib);
	printf("label  = ");
	scanf("%s", label);

	err = d3tinc(dsp, col, attrib, label);
	err_disp();
}

/************************************************/
/*	d3tin() : ｎ行挿入			*/
/************************************************/
t_tin()
{
	printf("*** d3tin ***\n");
	if (dsp == NULL) return(0);

	printf("row = ");
	scanf("%d", &row);
	printf("n   = ");
	scanf("%d", &n);
	data_set(n);

	err = d3tin(dsp, row, n, templete);
	err_disp();
}

/************************************************/
/*	d3trd() : ｎ行読み込み			*/
/************************************************/
t_trd()
{
	printf("*** d3trd ***\n");
	if (dsp == NULL) return(0);

	printf("row = ");
	scanf("%d", &row);
	printf("n   = ");
	scanf("%d", &n);

	tp_set();
	err = d3trd(dsp, row, n, templete);
	if (err == 0)
		dsp_tmp(n);
	err_disp();
}

/************************************************/
/*	d3trdv() : 任意行の読み込み		*/
/************************************************/
t_trdv()
{
	printf("*** d3trdv ***\n");
	if (dsp == NULL) return(0);

	tp_set();
	err = d3trdv(dsp, &line, templete);
	if (err == 0)
		dsp_tmp(line.ln_n);
	err_disp();
}

/************************************************/
/*	d3trow() : 行数取り出し			*/
/************************************************/
t_trow()
{
	printf("*** d3trow ***\n");
	if (dsp == NULL) return(0);

	err = d3trow(dsp);
	err_disp();
}

/************************************************/
/*	d3tup() : １行更新			*/
/************************************************/
t_tup()
{
	printf("*** d3tup ***\n");
	if (dsp == NULL) return(0);

	printf("row = ");
	scanf("%d", &row);
	data_set(1);

	err = d3tup(dsp, row, templete);
	err_disp();
}

/************************************************/
/*	d3srch() : 条件検索			*/
/************************************************/
t_srch()
{
	int	i, rn;

	printf("*** d3srch ***\n");
	if (dsp == NULL) return(0);

	printf("relop = ");
	scanf("%d", &cond.cnd_relop);
	printf("col   = ");
	scanf("%d", &cond.cnd_col);
	printf("op    = ");
	scanf("%d", &cond.cnd_op);
	printf("type  = ");
	scanf("%d", &type);
	printf("val   = ");
	scanf("%s", val);
	printf("in    = ");
	scanf("%d", &in);

	switch (type) {
	case DS_L:
	case DS_I:
		if (strcmp(val, "NA") == 0)
			cond.cnd_val.i = PD_NA;
		else
			cond.cnd_val.i = atoi(val);
		break;
	case DS_R:
		if (strcmp(val, "NA") == 0)
			cond.cnd_val.i = PD_NA;
		else
			cond.cnd_val.r = atof(val);
		break;
	case DS_C:
		cond.cnd_val.c_ptr = val;
		break;
	default:
		printf("type error\n");
		return;
	}

	err = d3srch(dsp, &cond, in ? &tf : 0, &tf);
	if (err == 0) {
		printf("tn = %d\n",tf.tf_n);
		rn = d3trow(dsp);
		for (i = 0; i < rn; i++)
			if (tf.tf_vec[i] != 0)
				printf("T ");
			else
				printf("F ");
		printf("\n");
	}
	err_disp();
}

/************************************************/
/*	d3toln() : 行ベクトル変換		*/
/************************************************/
t_toln()
{
	int	i;

	printf("*** d3toln ***\n");
	if (dsp == NULL) return(0);

	err = d3toln(&tf, &line, d3trow(dsp));
	if (err == 0) {
		printf("ln = %d\n",line.ln_n);
		for (i = 0; i < line.ln_n; i++)
			printf("%d ", line.ln_vec[i]);
		printf("\n");
	}
	err_disp();
}

/************************************************/
/*	d3totf() : 真偽値ベクトル変換		*/
/************************************************/
t_totf()
{
	int	i, rn;

	printf("*** d3totf ***\n");
	if (dsp == NULL) return(0);

	rn = d3trow(dsp);

	err = d3totf(&line, &tf, row);
	if (err == 0) {
		printf("tn = %d\n",tf.tf_n);
		for (i = 0; i < rn; i++)
			if (tf.tf_vec[i] != 0)
				printf("T ");
			else
				printf("F ");
		printf("\n");
	}
	err_disp();
}

/************************************************/
/*	d3fidx() : 索引ファイル名取り出し	*/
/************************************************/
t_fidx()
{
	printf("*** d3fidx ***\n");
	if (dsp == NULL) return(0);

	printf("ds_name = ");
	scanf("%s", ds_name);

	err = d3fidx(dsp, ds_name, index_name);
	if (err == 0)
		printf("index_name = %s\n", index_name);
	err_disp();
}

/************************************************/
/*	d3aryr() : 配列データ読み込み		*/
/************************************************/
t_aryr()
{
	int	n;
	union	{
		int	*i;
		float	*r;
	} ptr;

	printf("*** d3aryr ***\n");
	if (dsp == NULL) return(0);

	attr.array_ptr = buf;
	err = d3aryr(dsp, &attr);
	if (err == 0) {
		printf("type = %d\n", attr.array_attr);
		printf("dimn = %d\n", attr.array_dimno);
		printf("dim1 = %d\n", attr.array_dim1);
		printf("dim2 = %d\n", attr.array_dim2);
		printf("dim3 = %d\n", attr.array_dim3);

		n = attr.array_dim1;
		if (attr.array_dimno >= 2)
			n *= attr.array_dim2;
		if (attr.array_dimno >= 3)
			n *= attr.array_dim3;
		ptr.i = attr.array_ptr;
		while (n--) {
			switch (attr.array_attr) {
			case DS_I:
				printf("%d ", *ptr.i++);
				break;
			case DS_R:
				printf("%g ", *ptr.r++);
				break;
			}
		}
		printf("\n");
	}
	err_disp();
}

/************************************************/
/*	d3aryw() : 配列データ読み込み		*/
/************************************************/
t_aryw()
{
	int	size, i;

	printf("*** d3aryw ***\n");
	if (dsp == NULL) return(0);

	printf("attr = ");
	scanf("%d", &attr.array_attr);
	printf("dimn = ");
	scanf("%d", &attr.array_dimno);
	size = 0;
	if (attr.array_dimno >= 1) {
		printf("dim1 = ");
		scanf("%d", &attr.array_dim1);
		size = attr.array_dim1;
	}
	if (attr.array_dimno >= 2) {
		printf("dim2 = ");
		scanf("%d", &attr.array_dim2);
		size *= attr.array_dim2;
	}
	if (attr.array_dimno >= 3) {
		printf("dim3 = ");
		scanf("%d", &attr.array_dim3);
		size *= attr.array_dim3;
	}
	for (i = 0; i < size; i++) {
		switch (attr.array_attr) {
		case DS_I:
			buf[i] = i + 1;
			break;
		case DS_R:
			((float *)buf)[i] = i + 1;
			break;
		}
	}

	attr.array_ptr = buf;
	
	err = d3aryw(dsp, &attr);
	err_disp();
}

/************************************************/
/*	d3desn() : 説明部成分数取り出し		*/
/************************************************/
t_desn()
{
	printf("*** d3desn ***\n");
	if (dsp == NULL) return(0);

	err = d3desn(dsp);
	err_disp();
}

/************************************************/
/*	d3cdes() : 説明部初期化			*/
/************************************************/
t_cdes()
{
	printf("*** d3cdes ***\n");
	if (dsp == NULL) return(0);

	err = d3cdes(dsp);
	err_disp();
}

/************************************************/
/*	d3defn() : 説明部要素数取り出し		*/
/************************************************/
t_defn()
{
	printf("*** d3defn ***\n");
	if (dsp == NULL) return(0);

	printf("compno = ");
	scanf("%d", &compno);

	err = d3defn(dsp, compno);
	err_disp();
}

/************************************************/
/*	d3pdes() : 説明部操作			*/
/************************************************/
t_pdes()
{
	char	*p;
	int	i;

	printf("*** d3pdes ***\n");
	if (dsp == NULL) return(0);

	printf("mode  = ");
	scanf("%s", wa_rec.cmp_rec_mode);
	printf("cmpno = ");
	scanf("%d", &wa_rec.cmp_rec_cmpno);
	printf("sfno  = ");
	scanf("%d", &wa_rec.cmp_rec_sfno);
	printf("efno  = ");
	scanf("%d", &wa_rec.cmp_rec_efno);
	if (wa_rec.cmp_rec_mode[0] == 'u' || wa_rec.cmp_rec_mode[0] == 'i') {
		printf("num   = ");
		scanf("%d", &wa_rec.cmp_rec_num);
		p = wa_rec.cmp_rec_data;
		for (i = 0; i < wa_rec.cmp_rec_num; i++) {
			printf("data  = ");
			scanf("%s", p);
			p += strlen(p) + 1;
		}
	}
	wa_rec.cmp_rec_len = 256;

	err = d3pdes(dsp, &wa_rec);
	if (err == 0) {
		if (wa_rec.cmp_rec_mode[0] == 'r') {
			printf("num   = %d\n", wa_rec.cmp_rec_num);
			p = wa_rec.cmp_rec_data;
			printf("data  = ");
			for (i = 0; i < wa_rec.cmp_rec_num; i++) {
				printf("%s ", p);
				p += strlen(p) + 1;
			}
			printf("\n");
		}
	}
	err_disp();
}

/************************************************/
/*	d3axin() : 軸ラベル成分数取り出し	*/
/************************************************/
t_axin()
{
	printf("*** d3axin ***\n");
	if (dsp == NULL) return(0);

	err = d3axin(dsp);
	err_disp();
}

/************************************************/
/*	d3caxi() : 軸ラベル初期化		*/
/************************************************/
t_caxi()
{
	printf("*** d3caxi ***\n");
	if (dsp == NULL) return(0);

	err = d3caxi(dsp);
	err_disp();
}

/************************************************/
/*	d3axfn() : 軸ラベル要素数取り出し	*/
/************************************************/
t_axfn()
{
	printf("*** d3axfn ***\n");
	if (dsp == NULL) return(0);

	printf("compno = ");
	scanf("%d", &compno);

	err = d3axfn(dsp, compno);
	err_disp();
}

/************************************************/
/*	d3paxi() : 軸ラベル操作			*/
/************************************************/
t_paxi()
{
	char	*p;
	int	i;

	printf("*** d3paxi ***\n");
	if (dsp == NULL) return(0);

	printf("mode  = ");
	scanf("%s", wa_rec.cmp_rec_mode);
	printf("cmpno = ");
	scanf("%d", &wa_rec.cmp_rec_cmpno);
	printf("sfno  = ");
	scanf("%d", &wa_rec.cmp_rec_sfno);
	printf("efno  = ");
	scanf("%d", &wa_rec.cmp_rec_efno);
	if (wa_rec.cmp_rec_mode[0] == 'u' || wa_rec.cmp_rec_mode[0] == 'i') {
		printf("num   = ");
		scanf("%d", &wa_rec.cmp_rec_num);
		p = wa_rec.cmp_rec_data;
		for (i = 0; i < wa_rec.cmp_rec_num; i++) {
			printf("data  = ");
			scanf("%s", p);
			p += strlen(p) + 1;
		}
	}
	wa_rec.cmp_rec_len = 256;

	err = d3paxi(dsp, &wa_rec);
	if (err == 0) {
		if (wa_rec.cmp_rec_mode[0] == 'r') {
			printf("num   = %d\n", wa_rec.cmp_rec_num);
			p = wa_rec.cmp_rec_data;
			printf("data  = ");
			for (i = 0; i < wa_rec.cmp_rec_num; i++) {
				printf("%s ", p);
				p += strlen(p) + 1;
			}
			printf("\n");
		}
	}
	err_disp();
}

/************************************************/
/*	d3tkey() : キーワード一覧取り出し	*/
/************************************************/
t_tkey()
{
	printf("*** tkey ***\n");
	if (dsp == NULL) return(0);

	err = d3tkey(dsp, &n, &keyword);
	if (err == 0) {
		printf("n = %d\n", n);
		while (n--)
			printf("%s\n", *keyword++);
	}
	err_disp();
}

/************************************************/
/*	d3tkyv() : キー値一覧取り出し		*/
/************************************************/
t_tkyv()
{
	int	i;

	printf("*** tkyv ***\n");
	if (dsp == NULL) return(0);

	printf("col = ");
	scanf("%d", &col);

	err = d3tkyv(dsp, col, &keyrec);
	if (err == 0) {
		printf("col_no = %d\n", keyrec->key_length);
		for (i = 0; i < keyrec->key_length; i++) {
			switch (keyrec->col_type) {
			case DS_L:
				if (keyrec->key_type.i[i] == 0)
					printf("F ");
				else if (keyrec->key_type.i[i] == PD_NA)
					printf("NA ");
				else
					printf("T ");
				break;
			case DS_I:
				if (keyrec->key_type.i[i] == PD_NA)
					printf("NA ");
				else
					printf("%d ", keyrec->key_type.i[i]);
				break;
			case DS_R:
				if (keyrec->key_type.i[i] == PD_NA)
					printf("NA ");
				else
					printf("%g ", keyrec->key_type.r[i]);
				break;
			case DS_C:
				printf("\"%s\" ", keyrec->key_type.c[i]);
				break;
			}
		}
		printf("\n");
		free((char *)keyrec);
	}
	err_disp();
}

/************************************************/
/*	d3clky() : 再コード化			*/
/************************************************/
t_clky()
{
	printf("*** d3clky ***\n");
	if (dsp == NULL) return(0);

	printf("col = ");
	scanf("%d", &col);

	err = d3clky(dsp, col);

	err_disp();
}

/************************************************/
/*	d3mktp() : テンプレート作成		*/
/************************************************/
t_mktp()
{
	T_TEMPLETE	*tp;
	int	i;

	printf("*** d3mktp ***\n");
	if (dsp == NULL) return(0);

	tp = (T_TEMPLETE *)templete;
	tp_set();
	err = d3mktp(dsp, tp);
	if (err == 0) {
		printf("rlen = %d\n", tp->tp_rlen);
		printf("cnum = %d\n", tp->tp_cnum);
		printf("col\tmode\tlen\toffset\n");
		for (i = 0; i < tp->tp_cnum; i++) {
			printf("%d\t%d\t%d\t%d\n",
					tp->tp_templete[i].tp_col,
					tp->tp_templete[i].tp_mode,
					tp->tp_templete[i].tp_len,
					tp->tp_templete[i].tp_offset);
		}
	}
	err_disp();
}

/************************************************/
/*	d3ccmp() : */
/************************************************/
t_ccmp()
{
	printf("*** d3ccmp ***\n");
	if (dsp == NULL) return(0);

	printf("start position ? ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);

	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}
	err=d3ccmp(memp, compname);
	err_disp();
}

/************************************************/
/*	d3dcmp() : 成分の削除                   */
/************************************************/
t_dcmp()
{
	printf("*** d3dcmp ***\n");
	if (dsp == NULL) return(0);

	printf("start position ? ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);

	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}
	err=d3dcmp(memp, compname);
	err_disp();
}

/************************************************/
/*	d3ucmp() : 成分の置換                   */
/************************************************/
t_ucmp()
{
	printf("*** d3ucmp ***\n");
	if (dsp == NULL) return(0);

	printf("start position ? ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);
	printf("newname = ");
	scanf("%s",newname);
	printf("attrib   = ");
	scanf("%d",&attrib);
	printf("nitems   = ");
	scanf("%d",&nitems);
	set_elm(nitems, attrib);

	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}

	err=d3ucmp(memp, compname, newname, attrib, nitems, buf, PD_MEMNO(dsp));
	err_disp();
}

/************************************************/
/*	d3ucnm() : データセットの成分名の変更   */
/************************************************/
t_ucnm()
{
	printf("*** d3ucnm ***\n");
	if (dsp == NULL) return(0);

	printf("start position ? ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);
	printf("newname = ");
	scanf("%s",newname);

	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}

	err=d3ucnm(memp, compname, newname, PD_MEMNO(dsp));
	
	err_disp();
}

/************************************************/
/*	d3gent() : データセットのエントリ情報のとりだし     */
/************************************************/
t_gent()
{
	printf("*** d3gent ***\n");
	if (dsp == NULL) return(0);

	printf("start position ?  ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);
	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}
	err=d3gent(memp, compname, &box);

	printf("ent_entp = %08x\n",box.ent_entp);
	printf("ent_pos = %-3d\n",box.ent_pos);
	printf("ent_parent = %08x\n",box.ent_parent);
	printf("ent_sname = %s\n",box.ent_sname);
	printf("ent_name = %s\n",box.ent_name);
	printf("ent_mode = %-2d\n",box.ent_mode);
	printf("ent_length = %-2d\n",box.ent_length);
	printf("ent_value = %08x\n",box.ent_value);
	err_disp();
}

/************************************************/
/*	d3delm() : */
/************************************************/
t_delm()
{
	printf("*** d3delm ***\n");
	if (dsp == NULL) return(0);

	printf("start position ?  ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);
	printf("from = ");
	scanf("%d",&from);
	printf("nitems = ");
	scanf("%d",&nitems);
	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}

	err=d3delm(memp, compname, from, nitems);
	err_disp();
}

/************************************************/
/*	d3uelm() : データセットの要素の置換     */
/************************************************/
t_uelm()
{
	printf("*** d3uelm ***\n");
	if (dsp == NULL) return(0);

	printf("start position ?  ");
	scanf("%s", start);
	printf("compname = ");
	scanf("%s",compname);
	printf("attrib   = ");
	scanf("%d",&attrib);
	printf("nitems   = ");
	scanf("%d",&nitems);
	printf("from     = ");
	scanf("%d",&from);
	set_elm(nitems, attrib);
	if (strcmp(start, ".") == 0)
		memp=PD_DMEM(dsp);
	else {
		err=d3gent(PD_DMEM(dsp), start, &box);
		memp=box.ent_entp;
	}

	err=d3uelm(memp, compname, attrib, nitems, buf, from, PD_MEMNO(dsp));
	err_disp();
}

/************************************************/
/*	d3clcm() : ＬＡデータセットの成分のＮＵＬＬ化*/
/************************************************/
t_clcm()
{
	printf("*** d3clcm ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	err=d3clcm(dsp, compname);
	err_disp();
}

/************************************************/
/*	d3dlcm() : ＬＡデータセットの成分の削除 */
/************************************************/
t_dlcm()
{
	printf("*** d3dlcm ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	err=d3dlcm(dsp, compname);
	err_disp();
}

/************************************************/
/*	d3upcm() : ＬＡデータセットの成分の置換 */
/************************************************/
t_upcm()
{
	printf("*** d3upcm ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	printf("newname = ");
	scanf("%s",newname);
	printf("attrib   = ");
	scanf("%d",&attrib);
	printf("nitems   = ");
	scanf("%d",&nitems);
	set_elm(nitems, attrib);

	err=d3upcm(dsp, compname, newname, attrib, nitems, buf);
	err_disp();
}

/************************************************/
/*	d3dlel() : ＬＡデータセットの要素の削除 */
/************************************************/
t_dlel()
{
	printf("*** d3dlel ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	printf("from = ");
	scanf("%d",&from);
	printf("nitems = ");
	scanf("%d",&nitems);
	err=d3dlel(dsp, compname, from, nitems);
	err_disp();
}

/************************************************/
/*	d3upel() : ＬＡデータセットの要素の置換 */
/************************************************/
t_upel()
{
	printf("*** d3upel ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	printf("attrib   = ");
	scanf("%d",&attrib);
	printf("nitems   = ");
	scanf("%d",&nitems);
	printf("from     = ");
	scanf("%d",&from);
	set_elm(nitems, attrib);

	err=d3upel(dsp, compname, attrib, nitems, buf, from);
	err_disp();
}

/************************************************/
/*	d3gtet() : ＬＡデータセットのエントリ情報のとりだし */
/************************************************/
t_gtet()
{
	printf("*** d3gtet ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);

	err=d3gtet(dsp, compname, &box);

	printf("ent_entp = %08x\n",box.ent_entp);
	printf("ent_pos = %-3d\n",box.ent_pos);
	printf("ent_parent = %08x\n",box.ent_parent);
	printf("ent_sname = %s\n",box.ent_sname);
	printf("ent_name = %s\n",box.ent_name);
	printf("ent_mode = %-2d\n",box.ent_mode);
	printf("ent_length = %-2d\n",box.ent_length);
	printf("ent_value = %08x\n",box.ent_value);
	err_disp();
}

/************************************************/
/*	d3upnm() : ＬＡデータセットの成分名の変更 */
/************************************************/
t_upnm()
{
	printf("*** d3upnm ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	printf("newname = ");
	scanf("%s",newname);

	err=d3upnm(dsp, compname, newname);
	
	err_disp();
}

/************************************************/
/*	d3istb() : 表形式データフォーマットチェック */
/************************************************/
t_istb()
{
	printf("*** d3istb ***\n");
	if (dsp == NULL) return(0);

	err = d3istb(dsp);

	err_disp();
}

/************************************************/
/*	d3rdcm() : 成分の読み込み（マクロ）	*/
/************************************************/
t_rdcm()
{
	int	i;
	PD_ELM	ptr;

	printf("*** d3rdcm ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s", compname);

	err = d3rdcm(dsp, compname, &attrib, &nitems, &ptr);

	if (err == 0) {
		printf("attrib   = %d\n", attrib);
		printf("nitems   = %d\n", nitems);
		switch (attrib) {
		case DS_L:
		case DS_I:
			for (i = 0; i < nitems; i++)
				printf("%d ", ptr.ptr_i[i]);
			break;
		case DS_R:
			for (i = 0; i < nitems; i++)
				printf("%g ", ptr.ptr_r[i]);
			break;
		case DS_C:
			for (i = 0; i < nitems; i++)
				printf("\"%s\" ", ptr.ptr_c[i]);
			break;
		case DS_STR:
			for (i = 0; i < nitems; i++) {
				printf("%s %d %d\n", ptr.ptr_d[i].name,
							ptr.ptr_d[i].mode,
							ptr.ptr_d[i].length);
			}
			break;
		}
		printf("\n");
	}

	err_disp();
}

/************************************************/
/*	d3incm() : 成分の追加（マクロ）		*/
/************************************************/
t_incm()
{
	printf("*** d3incm ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s", compname);
	printf("newname  = ");
	scanf("%s", newname);
	printf("pos      = ");
	scanf("%d", &pos);

	err = d3incm(dsp, compname, newname, pos);

	err_disp();
}

/************************************************/
/*	d3rdel() : 要素の読み込み（マクロ）	*/
/************************************************/
t_rdel()
{
	int	i;
	PD_ELM	ptr;

	printf("*** d3rdel ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	printf("attrib   = ");
	scanf("%d",&attrib);
	printf("nitems   = ");
	scanf("%d",&nitems);
	printf("pos      = ");
	scanf("%d",&pos);

	err = d3rdel(dsp, compname, attrib, nitems, &ptr, pos);

	if (err == 0) {
		switch (attrib) {
		case DS_L:
		case DS_I:
			for (i = 0; i < nitems; i++)
				printf("%d ", ptr.ptr_i[i]);
			break;
		case DS_R:
			for (i = 0; i < nitems; i++)
				printf("%g ", ptr.ptr_r[i]);
			break;
		case DS_C:
			for (i = 0; i < nitems; i++)
				printf("\"%s\" ", ptr.ptr_c[i]);
			break;
		}
		printf("\n");
	}

	err_disp();
}

/************************************************/
/*	d3inel() : 要素の追加（マクロ） 	*/
/************************************************/
t_inel()
{
	printf("*** d3inel ***\n");
	if (dsp == NULL) return(0);

	printf("compname = ");
	scanf("%s",compname);
	printf("attrib   = ");
	scanf("%d",&attrib);
	printf("nitems   = ");
	scanf("%d",&nitems);
	set_elm(nitems, attrib);
	printf("pos      = ");
	scanf("%d",&pos);

	err = d3inel(dsp, compname, attrib, nitems, buf, pos);

	err_disp();
}

/************************************************/
/*	d3cvre() : 正規表現の変換		*/
/************************************************/
t_cvre()
{
	int	md;
	char	ibuf[64], type_0[10], obuf[64];
	
	printf("*** d3cvre ***\n");

	printf("expression = ");
	scanf("%s", ibuf);

	printf("type (0/1) = ");
	scanf("%s", type_0);
	md = atoi(type_0);

	err = d3cvre(ibuf, obuf, md);

	printf("converted  = %s\n",obuf);
	err_disp();
}

/************************************************/
/*	d3nmty() : ＬＡデータセット名のチェック	*/
/************************************************/
t_nmty()
{
	int	type;
	char	Name[32];

	printf("*** d3nmty ***\n");

	printf("Lads Name = ");
	scanf("%s", Name);

	type = d3nmty(Name);

 	printf("d3nmty:\n");

	if ((type & TYPE_UDATA) == TYPE_UDATA)
 		printf("TYPE_UDATA\n");
	if ((type & TYPE_GROUP) == TYPE_GROUP)
 		printf("TYPE_GROUP\n");
	if ((type & TYPE_SINDX) == TYPE_SINDX)
 		printf("TYPE_SINDX\n");
	if ((type & TYPE_SUSER) == TYPE_SUSER)
 		printf("TYPE_SUSER\n");
	if ((type & TYPE_UUSER) == TYPE_UUSER)
 		printf("TYPE_UUSER\n");
	if ((type & TYPE_BACKF) == TYPE_BACKF)
 		printf("TYPE_BACKF\n");
	if ((type & TYPE_TEMPF) == TYPE_TEMPF)
 		printf("TYPE_TEMPF\n");
	if (type == 0)
		printf("Unkouwn File Name ! : %s\n", Name);
 	printf("\n");

	printf("d3xgrp: %1d\n",d3xgrp(Name));
	printf("d3xind: %1d\n",d3xind(Name));
	printf("d3xsys: %1d\n",d3xsys(Name));
	printf("d3xusr: %1d\n",d3xusr(Name));
	printf("d3xdat: %1d\n",d3xdat(Name));
	printf("d3xbak: %1d\n",d3xbak(Name));
	printf("d3xtmp: %1d\n",d3xtmp(Name));
 	printf("\n");

	return(0);
}

/************************************************/
/*	d3rgex() : ファイル名の正規表現のテスト	*/
/************************************************/
t_rgex()
{
	struct	stat	stat_buf;
	char	dir[128], buf[128], reg_ex[128];
	DIR	*fp;
	struct	dirent	*entp;
	int	e, ie;
	
	printf("*** d3rgex ***\n");

	printf("directory = ");
	scanf("%s", dir);

	printf("reg_ex    = ");
	scanf("%s", reg_ex);

	if ((fp = opendir(dir)) == NULL) {
		printf("open error code=%2d\n", errno);
		return(0);
	}

	while(1) {
		entp = readdir(fp);
		if (entp == NULL) break;

		strcpy(buf, dir); strcat(buf, "/");
		strcat(buf, entp->d_name);

		e = stat(buf, &stat_buf);
		if (e == 0 && ((stat_buf.st_mode & 040000) == 0)) {/* file? */
			ie = d3rgex(entp->d_name, reg_ex, PD_CND_EQ);
			if (ie == 1) 
				printf("%s\n", entp->d_name);
		}
	}

 	printf("\n");
	closedir(fp);

	return(0);
}

/************************************************/
/*	d3tcpc() : 表データセットの列コピー	*/
/************************************************/
t_tcpc()
{
	int	form, to, dst;

	printf("*** d3tcpc ***\n");

	printf("from = ");
	scanf("%d", &from);

	printf("to   = ");
	scanf("%d", &to);

	printf("dst  = ");
	scanf("%d", &dst);

	err = d3tcpc(dsp, from, to, dst);

	err_disp();
}

/************************************************/
/*	d3tmvc() : 表データセットの列移動	*/
/************************************************/
t_tmvc()
{
	int	form, to, dst;

	printf("*** d3tmvc ***\n");

	printf("from = ");
	scanf("%d", &from);

	printf("to   = ");
	scanf("%d", &to);

	printf("dst  = ");
	scanf("%d", &dst);

	err = d3tmvc(dsp, from, to, dst);

	err_disp();
}

/************************************************/
/*	d3tcpr() : 表データセットの行コピー	*/
/************************************************/
t_tcpr()
{
	int	form, to, dst;

	printf("*** d3tcpr ***\n");

	printf("from = ");
	scanf("%d", &from);

	printf("to   = ");
	scanf("%d", &to);

	printf("dst  = ");
	scanf("%d", &dst);

	err = d3tcpr(dsp, from, to, dst);

	err_disp();
}

/************************************************/
/*	d3tmvr() : 表データセットの行コピー	*/
/************************************************/
t_tmvr()
{
	int	form, to, dst;

	printf("*** d3tmvr ***\n");

	printf("from = ");
	scanf("%d", &from);

	printf("to   = ");
	scanf("%d", &to);

	printf("dst  = ");
	scanf("%d", &dst);

	err = d3tmvr(dsp, from, to, dst);

	err_disp();
}

/************************************************/
/*	d3lend() : ＬＡデータセット使用終了	*/
/************************************************/
t_lend()
{
	printf("*** d3lend ***\n");
	if (dsp == NULL) return(0);

	printf("term = ");
	scanf("%s", term);
	printf("sync = ");
	scanf("%d", &sync);

	err = d3lend(dsp, term, sync);
	dsp = NULL;
	err_disp();
}

/************************************************/
/*		サブルーチン			*/
/************************************************/
ds_clear()
{
	struct	dsm	*dp;

	if (dsp == NULL) return(0);

	dp = dsp->ds_entry;
	dp->ptr.d[2].type = 0;
	dp->ptr.d[2].length = 0;
	dp->ptr.d[3].type = 0;
	dp->ptr.d[3].length = 0;
	dp->ptr.d[4].type = 0;
	dp->ptr.d[4].length = 0;
}

tp_set()
{
	T_TEMPLETE	*tp;
	int	i;

	if (dsp == NULL) return(0);

	tp = (T_TEMPLETE *)templete;
	tp->tp_bufp = (char *)buf;
	tp->tp_cnum = d3tcol(dsp);
	for (i = 0; i < tp->tp_cnum; i++)
		tp->tp_templete[i].tp_col = i + 1;

	if (d3mktp(dsp, tp) != 0) {
		printf("mktp error\n");
	}
}

dsp_tmp(n)
int	n;
{
	T_TEMPLETE	*tp;
	union	{
		int	*i;
		float	*r;
		char	*c;
	} bp, bp2;
	int	i, j;
	TEMPLETE	*tp2;

	tp = (T_TEMPLETE *)templete;
	bp.i = buf;
	for (i = 0; i < n; i++) {
		for (j = 0, tp2 = tp->tp_templete; j < tp->tp_cnum; j++, tp2++) {
			bp2.c = bp.c + tp2->tp_offset;
			switch (tp2->tp_mode) {
			case DS_I:
				if (*bp2.i == PD_NA)
					printf(" NA ");
				else
					printf(" %d ",*bp2.i);
				break;
			case DS_R:
				if (*bp2.i == PD_NA)
					printf(" NA ");
				else
					printf(" %g ",*bp2.r);
				break;
			case DS_L:
				if (*bp2.i == PD_NA)
					printf(" NA ");
				else if (*bp2.i)
					printf(" TRUE ");
				else
					printf(" FALSE ");
				break;
			case DS_C:
				printf(" \"%s\" ",bp2.c);
				break;
			}
		}
		bp.c += tp->tp_rlen;
		printf("\n");
	}
}

data_set(n)
{
	T_TEMPLETE	*tp;
	union	{
		int	*i;
		float	*r;
		char	*c;
	} bp, bp2;
	int	i, j, offset, len;
	TEMPLETE	*tp2;

	tp_set();
	tp = (T_TEMPLETE *)templete;
	offset = 0;
	for (i = 0, tp2 = tp->tp_templete; i < tp->tp_cnum; i++, tp2++) {
		if (tp2->tp_mode == DS_C)
			len = 80;
		else
			len = 4;
		tp2->tp_len = len;
		tp2->tp_offset = offset;
		offset += len;
	}
	tp->tp_rlen = offset;

	bp.i = buf;
	for (i = 0; i < n; i++) {
		for (j = 0, tp2 = tp->tp_templete; j < tp->tp_cnum; j++, tp2++) {
			bp2.c = bp.c + tp2->tp_offset;
			switch (tp2->tp_mode) {
			case DS_I:
				printf("%d I = ", j+1);
				scanf("%d", bp2.i);
				break;
			case DS_R:
				printf("%d R = ", j+1);
				scanf("%f", bp2.r);
				break;
			case DS_L:
				printf("%d L = ", j+1);
				scanf("%d", bp2.i);
				break;
			case DS_C:
				printf("%d C = ", j+1);
				scanf("%s", bp2.c);
				break;
			}
		}
		bp.c += tp->tp_rlen;
	}
}

dsp_tbl()
{
	if (dsp == NULL) return(0);

	row = 1;
	n = d3trow(dsp);

	tp_set();
	err = d3trd(dsp, row, n, templete);
	if (err == 0)
		dsp_tmp(n);
	err_disp();
}

set_elm(nitems, attrib)
int	nitems, attrib;
{
	int	i;
	float	work;

	for (i = 0, wp = workbuf; i < nitems; i++) {
		printf("data(%d/%d) = ", i+1, nitems);
		switch (attrib) {
		case DS_L:
		case DS_I:
			scanf("%s", (char *)&buf[i]);
			if (strcmp(&buf[i], "NA") == 0)
				buf[i] = PD_NA;
			else 
				buf[i] = atoi(&buf[i]);
			/* scanf("%d", &buf[i]); */
			break;
		case DS_R:
			scanf("%s", (char *)&buf[i]);
			if (strcmp(&buf[i], "NA") == 0)
				buf[i] = PD_NA;
			else
				((float *)buf)[i] = atof(&buf[i]);
			/* scanf("%f", &buf[i]); */
			break;
		case DS_C:
			((char **)buf)[i] = wp;
			scanf("%s", wp);
			if (strcmp(wp, "\"\"") == 0)
				strcpy(wp, "");
			wp += strlen(wp) + 1;
			break;
		default:
			printf("\n");
		}
	}
}

err_disp()
{
	printf("return value = %d\n\n", err);
}

/************************************************/
/*		ヘルプメッセージ		*/
/************************************************/

#define	MAX_FUN	64
#define	MAX_ERR	18

	static	char	*fun_tb[MAX_FUN] = {
		"aryr","aryw","axfn","axin","clky","caxi","ccmp","cdes",
		"clcm","dcmp","defn","delm","desn","dlcm","dlel","fidx",
		"gent","gtet","lend","mktp","paxi","pdes","srch","tcol",
		"tdl ","tdlc","tdlv","tin ","tinc","tkey","tkyv","toln",
		"totf","trd ","trdv","trow","tup ","ucmp","uelm","ucnm",
		"upcm","upel","upnm","istb","rdcm","incm","rdel","inel",
		"cvre","nmty","rgex","tcpc","tmvc","tcpr","tmvr","    ",
		"h   ","p   ","wq  ","q   ","t   ","c   ","    ","    "
	};

static	char	*errcode[MAX_ERR] = {
	"  0: normal        "," -1: DE_MEM        "," -2: DE_NONE_MEM   ",
	" -3: DE_PRM        "," -4: DE_IF_MEMOVR  "," -5: DE_NONE_DAT   ",
	" -6: DE_FILE_IO    "," -7: DE_DS_FORMAT  "," -8: DE_FILE_NONE  ",
	" -9: DE_FILE_EXIST ","-10: DE_LOCK       ","-11: DE_DTBL_FULL  ",
	"-12: ------------  ","-13: DE_NONE_GRP   ","-14: DE_VI_FMT     ",
	"-15: DE_DSNAME     ","-16: DE_PATH       ","                   "
	};
t_t_help()
{
	int	j;

	printf("Usage:   pd_test\n");
	printf("         pd_test lads_name\n");
	printf("         pd_test -h\n");
	printf("         pd_test -\n\n");

	printf("pd_test function\n");
	for (j=0; j<MAX_FUN; j+=8) {
		printf("        %-8s %-8s %-8s %-8s %-8s %-8s %-8s %-8s\n",
		       fun_tb[j+0], fun_tb[j+1], fun_tb[j+2], fun_tb[j+3],
		       fun_tb[j+4], fun_tb[j+5], fun_tb[j+6], fun_tb[j+7]);
	}
	printf("\n");

	printf("return code\n");
	for (j=0; j<MAX_ERR; j+=3) {
		printf("        %-20s %-20s %-20s\n",
		       errcode[j],errcode[j+1],errcode[j+2]);
	}
	printf("\n");
}
