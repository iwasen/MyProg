/********************************************************
*							*
*	‚a‚`‚r‚h‚b@‚s‚n@‚b@ƒgƒ‰ƒ“ƒXƒŒ[ƒ^@		*
*							*
*		‚o‚`‚r‚r‚Q@‚c‚d‚e•¶ˆ—		*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.04.30	*
*			file name     : pass2.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<ctype.h>
#include	<setjmp.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

extern	jmp_buf	jmpbuf;			/* long jump buffer		*/

void	p2_deffn(void);
void	p2_data();
char	out_type(void);


/*	‚c‚d‚e•¶ˆ—	*/

void ot_deffn()
{
	if (fn_flag == 0)
		return;

	fseek(bfp, 0L, 0);

	setjmp(jmpbuf);

	while (1) {
		getcode();
		switch (kcode) {
		case EOF:
			return;
		case K_REM:
		case '\'':
			nextline();
			break;
		case K_DEF:
			getcode();
			switch (kcode) {
			case K_FNX:
				p2_deffn();
				break;
			}
		}
	}
}


/*	‚c‚d‚e@‚e‚m•¶ˆ—	*/

static void p2_deffn()
{
	char	type;
	char	*p, vn;
	VAR	*vbuf[10], *vp;

	if (o_src)
		fprintf(cfp,"/*\n%s*/\n",linebuf);

	type = vtype;
	if (fnum == MAX_DEFFN)
		error(E_DEFFNOV);
	fntbl[fnum++] = &cbuf[cbufp];

	out_type();
	cputs(vnamebuf);
	cputc((char)'(');
	for (p = vnamebuf; *p; )
		cbufset(*p++);

	vn = 0;
	getcode();
	if (kcode == '(') {
		cbufset((char)':');
		cbufset((char)'(');
		while (1) {
			getcode();
			kc_check(K_VAR);
			cbufset(out_type());
			cputs(vnamebuf);

			vp = srchvar();
			if (vp->vartype == V_STR) {
				vp->vartype = V_STRP;
				vbuf[vn++] = vp;
			}

			getcode();
			if (kcode != ',')
				break;

			cbufset((char)',');
			cputc((char)',');
		}
		expect(')');
		cbufset((char)')');
	}
	cbufset((char)0);

	cputs(")\n{\n\treturn(");
	expect('=');
	expression(type);
	cputs(");\n}\n\n");

	while (vn--)
		vbuf[vn]->vartype = V_STR;
}

static char out_type()
{
	char	c;

	switch (vtype) {
	case V_CHR:
		cputs("char ");
		c = 'c';
		break;
	case V_INT:
		cputs("int ");
		c = 'i';
		break;
	case V_LNG:
		cputs("long ");
		c = 'l';
		break;
	case V_SNG:
		cputs("float ");
		c = 'f';
		break;
	case V_DBL:
		cputs("double ");
		c = 'd';
		break;
	case V_STR:
		cputs("STRTBL *");
		c = 's';
		break;
	}
	return(c);
}


/*	‚c‚`‚s‚`•¶ˆ—	*/

void ot_data()
{
	if (dt_flag == 0)
		return;

	fseek(bfp, 0L, 0);
	fst_data = 0;

	setjmp(jmpbuf);
	while (1) {
		getcode();
		switch (kcode) {
		case EOF:
			cputs("char\tDEND = 0xff;\n\n");
			return;
		case K_REM:
		case '\'':
			nextline();
			break;
		case K_DATA:
			if (fst_data == 0)
				fst_data = value.i;
			p2_data();
		}
	}
}

static void p2_data()
{
	while (isspace(*lp) != 0)		/* space ?		*/
		lp++;				/* yes skip		*/

	fprintf(cfp,"char\tD%u[] = \"",value.i);

	while (*lp != 0 && *lp != 0x0a) {
		if (*lp == '"')
			cputc((char)'\\');
		cputc(*lp++);
	}
	cputs("\";\n");
}
