/********************************************************
*							*
*	‚a‚`‚r‚h‚b@‚s‚n@‚b@ƒgƒ‰ƒ“ƒXƒŒ[ƒ^@		*
*							*
*		‚o‚`‚r‚r‚Q ƒRƒ}ƒ“ƒhŒÂ•Êˆ—		*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.03.05	*
*			file name     : comsub.c	*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<string.h>
#include	<ctype.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"

#ifdef	TMP_MAX
#pragma	nonrec
#endif

extern	void	indent(), indentx(), com();
extern	void	print(char), input();
extern	char	varout(char);
extern	char	for_nest, while_nest;
extern	char	then_flag;
static	char	if_nest = 0, else_flag = 0, if_table[10] = {0};

/*	‚a‚`‚b‚j‚k‚h‚f‚g‚s•¶ˆ—	*/

void p2_backlight()
{
	indentx();
	cputs("b_backlight(");
	switch (kcode) {
	case K_CONT:
		cputc((char)'0');
		getcode();
		break;
	case K_ON:
		cputs("-2");
		getcode();
		break;
	case K_OFF:
		cputs("-1");
		getcode();
		break;
	default:
		expression((char)V_INT);
		break;
	}
	cputs(");");
}



/*	‚b‚`‚k‚k•¶ˆ—		*/

void p2_call()
{
	switch (kcode) {
	case K_AVAR:
		vnamebuf[strlen(vnamebuf)-1] = 0;
		kcode = K_VAR;
	case K_VAR:
		break;
	default:
		syntax_err();
	}

	indentx();
	cputs("b_call((int)");
	varout((char)0);
	cputc((char)',');

	if (kcode == '(') {
		do {
			getcode();
			varout((char)1);
			cputc((char)',');
		} while (kcode == ',');
		expect(')');
	}
	cputs("0);");
}


/*	‚b‚k‚n‚r‚d•¶ˆ—	*/

void p2_close()
{
	static char b_close[] = "b_close:x#";
	int	i;

	if (expr_check() || kcode == '#')
		com(b_close);
	else {
		indentx();
		cputs("b_close(");
		for (i = 1; i <= file; i++)
			fprintf(cfp,"&f_buff[%d],",i);
		cputs("-1);");
	}
}


/*	‚c‚d‚e•¶ˆ—	*/

void p2_def()
{
	if (kcode == K_USR) {
		indentx();
		fprintf(cfp,"usr%d = ",value.i);
		getcode();
		expect('=');
		expression((char)0);
		cputc((char)';');
	} else
		nextcom();
}


/*	‚e‚h‚d‚k‚c•¶ˆ—	*/

void p2_field()
{
	indentx();

	cputs("b_field(");
	expression((char)V_FILE);
	cputc((char)',');

	while (kcode == ',') {
		getcode();
		expression((char)V_INT);
		cputc((char)',');
		if (strcmp(wordbuf,"AS") != 0)
			syntax_err();
		getcode();
		if (varout((char)0) != V_STR)
			type_err();
		cputc((char)',');
	}
	cputs("-1);");
}


/*	‚e‚n‚q•¶ˆ—	*/

void p2_for()
{
	char	vnamesav[32];

	indentx();
	kc_check(K_VAR);
	fprintf(cfp,"for (%s = ",vnamebuf);
	strcpy(vnamesav,vnamebuf);
	getcode();
	expect('=');
	expression((char)0);
	fprintf(cfp,"; %s ",vnamesav);
	expect(K_TO);
	expr_((char)0);
	if (kcode == K_STEP) {
		getcode();
		if (kcode == '-')
			cputs(">= ");
		else
			cputs("<= ");
		expr_out();
		fprintf(cfp,"; %s += ",vnamesav);
		expression((char)0);
	} else {
		cputs("<= ");
		expr_out();
		fprintf(cfp,"; %s += 1",vnamesav);
	}
	cputs(") {");
	for_nest++;
	tab++;
}


/*	‚m‚d‚w‚s•¶ˆ—	*/

void p2_next()
{
	while (1) {
		if (for_nest == 0)
			error(E_NF);
		--for_nest;
		--tab;
		indent();
		cputc((char)'}');
		if (kcode == K_VAR)
			getcode();
		else
			break;
		if (kcode != ',')
			break;
		cputc((char)'\n');
		getcode();
	}
}


/*	‚f‚d‚s•¶ˆ—	*/

void p2_get()
{
	char	*s;
	static char b_get[] = "b_get:#[,i{0}]";
	static char b_geta[] = "b_geta:(i,i)-[S](i,i),A";

	switch (kcode) {
	case '@':
		getcode();
	case '(':
		s = b_geta;
		break;
	default:
		s = b_get;
	}
	com(s);
}


/*	‚o‚t‚s•¶ˆ—	*/

void p2_put()
{
	char	*s;
	static char b_put[] = "b_put:#[,i{0}]";
	static char b_puta[] = "b_puta:(i,i),A[,M]";

	switch (kcode) {
	case '@':
		getcode();
	case '(':
		s = b_puta;
		break;
	default:
		s = b_put;
	}
	com(s);
}


/*	‚f‚n‚r‚t‚a•¶ˆ—	*/

void p2_gosub()
{
	kc_check(K_NCONST);
	indent();
	fprintf(cfp,"call(%u);",(int)value.d);
	getcode();
}


/*	‚f‚n‚s‚n•¶ˆ—		*/

void p2_goto()
{
	kc_check(K_NCONST);
	indent();
	fprintf(cfp,"jmp(%u);",(int)value.d);
	getcode();
}


/*	‚h‚e•¶ˆ—	*/

void p2_if()
{
	int	kc;

	indentx();
	cputs("if (");
	not_flag = 1;
	expression((char)V_REL);
	not_flag = 0;
	cputs(") {");
	tab++;
	kc = kcode;
	getcode();
	switch (kc) {
	case K_THEN:
		if (kcode != K_NCONST) {
			then_flag = 1;
			break;
		}
	case K_GOTO:
		cputc((char)'\n');
		p2_goto();
		break;
	default:
		syntax_err();
	}
	if_table[++if_nest] = 0;
	else_flag = 0;
}


/*	‚d‚k‚r‚d•¶ˆ—		*/

void p2_else()
{
	if (if_nest == 0)
		syntax_err();

	if (else_flag) {
		while (if_table[if_nest]) {
			--tab;
			indent();
			cputs("}\n");
			--if_nest;
		}
	}
	if_table[if_nest] = 1;

	--tab;
	indent();
	cputs("} else {");
	tab++;
	if (kcode == K_NCONST) {
		cputc((char)'\n');
		p2_goto();
	} else
		then_flag = 1;

	else_flag = 1;
}


/*	‚h‚e•¶I—¹ˆ—		*/

void p2_if_end()
{
	while (if_nest) {
		--tab;
		indent();
		cputs("}\n");
		--if_nest;
	}
}


/*	‚h‚m‚o‚t‚s•¶ˆ—	*/

void p2_input()
{
	indentx();
	cputs("b_input(");
	if (kcode == '#') {
		expression((char)V_FILE);
		expect(',');
	} else
		cputs("(FB *)0");
	cputc((char)',');
	input();
	cputs(");");
}


/*	‚j‚`‚m‚i‚hC‚e‚n‚m‚s•¶ˆ—	*/

void p2_kanji()
{
	char	*s;
	static char b_kanji[] = "b_kanji:S(i,i),M,xi";
	static char b_gaiji[] = "b_gaiji:s";

	if (kcode == K_STEP || kcode == '(')
		s = b_kanji;
	else
		s = b_gaiji;
	com(s);
}


/*	‚j‚d‚x•¶ˆ—	*/

void p2_key()
{
	static char b_key[] = "b_key:i[,s]";
	static char b_touch[] = "b_touch:(i,i)[-(i{-1},i{-1})],s,i,s";
	static char b_keyn[] = "b_keyn:(i)O";
	char	*s, *lpsave;
	int	kc;

	if (kcode == '(') {
		lpsave = lp;
		getcode();
		expr_((char)V_INT);
		kc = kcode;
		lp = lpsave;
		kcode = '(';
		if (kc == ')')
			s = b_keyn;
		else
			s = b_touch;
	} else
		s = b_key;
	com(s);
}


/*	‚k‚d‚s•¶ˆ—	*/

void p2_let()
{
	indentx();

	if (vtype == V_STR) {
		cputs("b_strset(");
		varout((char)0);
		expect('=');
		cputc((char)',');
		expression((char)V_STR);
		cputc((char)')');
	} else {
		varout((char)0);
		expect('=');
		cputs(" = ");
		expression((char)0);
	}
	cputc((char)';');
}


/*	‚k‚h‚m‚d•¶ˆ—		*/

void p2_line()
{
	static char b_line[] = "b_line:S[(i{0},i{0})]-S(i,i)[,i{1},B,i{0xffff}]";

	if (kcode != K_INPUT) {
		com(b_line);
		return;
	}

	indentx();
	cputs("b_lineinp(");
	getcode();
	if (kcode == '#') {
		expression((char)V_FILE);
		expect(',');
	} else
		cputs("(FB *)0");
	cputc((char)',');

	if (kcode == K_SCONST) {
		expression((char)V_STR);
		expect(';');
	} else
		cputs("(STRTBL *)0");
	cputc((char)',');

	if (varout((char)0) != V_STR)
		type_err();
	cputs(");");
}


/*	‚k‚o‚q‚h‚m‚s•¶ˆ—	*/

void p2_lprint()
{
	indentx();
	if (kcode == K_USING) {
		cputs("b_prtuse((FB *)1,");
		getcode();
	} else
		cputs("b_print((FB *)1,");
	print((char)0);
	cputs(");");
}


/*	‚m‚`‚l‚d•¶ˆ—		*/

void p2_name()
{
	indentx();
	cputs("b_name(");
	expression((char)V_STR);
	cputc((char)',');
	if (strcmp(wordbuf,"AS") != 0)
		syntax_err();
	getcode();
	expression((char)V_STR);
	cputs(");");
}


/*	‚o‚q‚h‚m‚s•¶ˆ—	*/

void p2_print()
{
	int	kc;

	indentx();
	if ((kc = kcode) == '#') {
		expr_((char)V_FILE);
		expect(',');
	}
	if (kcode == K_USING) {
		cputs("b_prtuse(");
		getcode();
	} else
		cputs("b_print(");
	if (kc == '#')
		expr_out();
	else
		cputs("(FB *)0");
	cputc((char)',');
	print((char)0);
	cputs(");");
}


/*	‚n‚m•¶ˆ—	*/

void p2_on()
{
	int	n;
	char	gflag;

	switch (kcode) {
	case K_ERROR:
		syn_check(K_GOTO);
		getcode();
		indent();
		if (kcode == K_NCONST) {
			n = value.d;
			if (n == 0)
				fprintf(cfp,"b_on_err(0);");
			else
				fprintf(cfp,"on_err_goto(%u);",n);
			getcode();
		} else
			fprintf(cfp,"b_on_err(0);");
		break;
	case K_COM:
		syn_check('(');
		syn_check(K_NCONST);
		n = value.d;
		syn_check(')');
		syn_check(K_GOSUB);
		syn_check(K_NCONST);
		indent();
		fprintf(cfp,"on_com_gosub(%u,%u);",n,(int)value.d);
		getcode();
		break;
	case K_BRCD:
		syn_check(K_GOSUB);
		syn_check(K_NCONST);
		indent();
		fprintf(cfp,"on_brcd_gosub(%u);",(int)value.d);
		getcode();
		break;
	case K_KEY:
		syn_check('(');
		syn_check(K_NCONST);
		n = value.d;
		syn_check(')');
		syn_check(K_GOSUB);
		syn_check(K_NCONST);
		indent();
		fprintf(cfp,"on_key_gosub(%u,%u);",n,(int)value.d);
		getcode();
		break;
	default:
		indentx();
		cputs("switch (");
		expression((char)V_INT);
		cputs(") {\n");

		switch (kcode) {
		case K_GOTO:
			gflag = 0;
			break;
		case K_GOSUB:
			gflag = 1;
			break;
		default:
			syntax_err();
		}
		n = 1;
		do {
			syn_check(K_NCONST);
			indent();
			fprintf(cfp,"case %d:\n",n);
			tab++;
			if (gflag == 0)
				p2_goto();
			else {
				p2_gosub();
				cputs("\tbreak;");
			}
			cputc((char)'\n');
			--tab;
			n++;
		} while (kcode == ',');
		indent();
		cputc((char)'}');
	}
}


/*	‚n‚o‚d‚m•¶ˆ—		*/

void p2_open()
{
	int	iom;

	indentx();

	expr_((char)V_STR);

	if (kcode != ',') {
		cputs("b_open1(");
		expr_out();

		if (kcode == K_FOR) {		/* "FOR" ?		*/
			getcode();		/* yes			*/
			if (strcmp("INPUT",wordbuf) == 0)
				iom = 0x01;
			else if (strcmp("OUTPUT",wordbuf) == 0)
				iom = 0x02;
			else if (strcmp("APPEND",wordbuf) == 0)
				iom = 0x08;
			else
				syntax_err();
			getcode();
		} else
			iom = 0x04;
		fprintf(cfp,",%d,",iom);

		if (strcmp(wordbuf, "AS"))
			syntax_err();
		getcode();
		expression((char)V_FILE);
		cputc((char)',');

		if (kcode == K_LEN) {
			getcode();
			expect('=');
			expression((char)V_INT);
		} else
			cputs("128");
	} else {
		cputs("b_open2(");
		expr_out();
		cputc((char)',');

		getcode();
		expression((char)V_FILE);
		expect(',');
		cputc((char)',');

		expression((char)V_STR);
		cputc((char)',');
		if (kcode == ',') {
			getcode();
			expression((char)V_INT);
		} else
			cputs("128");
	}
	cputs(");");
}


/*	‚n‚o‚s‚h‚n‚m•¶ˆ—	*/

void p2_option()
{
	if (strcmp(wordbuf,"BASE") == 0) {
		nextcom();
		return;
	} else if (strcmp(wordbuf,"COUNTRY") == 0) {
		getcode();
		com("b_country:s");
	} else if (strcmp(wordbuf,"CURRENCY") == 0) {
		getcode();
		com("b_currency:s");
	} else
		syntax_err();
}
	

/*	‚o‚n‚v‚d‚q•¶ˆ—	*/

void p2_power()
{
	indentx();
	cputs("b_power(");
	switch (kcode) {
	case K_CONT:
		cputc((char)'0');
		getcode();
		break;
	case K_OFF:
		cputs("-1");
		getcode();
		break;
	default:
		expression((char)V_INT);
		break;
	}
	cputs(");");
}


/*	‚q‚d‚`‚c•¶ˆ—		*/

void p2_read()
{
	indentx();
	cputs("b_read(");
	input();
	cputs(");");
}


/*	‚q‚d‚l•¶ˆ—	*/

void p2_rem()
{
	getcode();
	if (strcmp(wordbuf,"INTERRUPT") == 0) {
		getcode();
		switch (kcode) {
		case K_ON:
			int_flag = 1;
			break;
		case K_OFF:
			int_flag = 0;
			break;
		default:
			syntax_err();
		}
	} else if (strcmp(wordbuf,"INLINE") == 0) {
		while (isspace(*lp) != 0)	/* space ?		*/
			lp++;			/* yes skip		*/
		indentx();
		cputs(lp);
	}
	while (kcode != K_LNO && kcode != EOF)
		getcode();
}


/*	‚q‚d‚r‚s‚n‚q‚d•¶ˆ—	*/

void p2_restore()
{
	int	dn;

	if (kcode == K_NCONST) {
		dn = value.d;
		getcode();
	} else
		dn = fst_data;

	indent();
	fprintf(cfp,"data_ptr = D%u;",dn);
}


/*	‚q‚d‚r‚t‚l‚d•¶ˆ—	*/

void p2_resume()
{
	indent();
	switch (kcode) {
	case K_NEXT:
		cputs("b_resume(1);");
		getcode();
		break;
	case K_NCONST:
		fprintf(cfp,"jmpr(%u);",(int)value.d);
		getcode();
		break;
	default:
		cputs("b_resume(0);");
	}
}


/*	‚q‚d‚s‚t‚q‚m•¶ˆ—	*/

void p2_return()
{
	indent();
	if (kcode == K_NCONST) {
		fprintf(cfp,"retn(%u);",(int)value.d);
		getcode();
	} else
		cputs("ret();");
}


/*	‚r‚s‚n‚o•¶ˆ—		*/

void p2_stop()
{
	indent();
	fprintf(cfp,"b_stop(%u);",line_no);
}


/*	‚r‚v‚`‚o•¶ˆ—		*/

void p2_swap()
{
	char	vt1, vt2;

	indent();

	cputs("b_");
	type_put(vtype);
	cputs("swap(");
	vt1 = varout((char)1);
	expect(',');
	cputc((char)',');
	vt2 = varout((char)1);
	if (vt1 != vt2)
		type_err();
	cputs(");");
}


/*	‚v‚h‚c‚s‚g•¶	*/

void p2_width()
{
	switch (kcode) {
	case K_LPRINT:
		getcode();
		com("b_lwidth:i");
		break;
	case '#':
		com("b_fwidth:#,i");
		break;
	default:
		if (vtype == V_STR)
			com("b_dwidth:s,i");
		else
			com("b_width:[i{-1},i{-1}]");
	}
}


/*	‚v‚g‚h‚k‚d•¶ˆ—	*/

void p2_while()
{
	indentx();
	cputs("while (");
	expression((char)0);
	cputs(") {");
	while_nest++;
	tab++;
}


/*	‚v‚d‚m‚c•¶ˆ—		*/

void p2_wend()
{
	if (while_nest == 0)
		error(E_WH);
	--while_nest;
	--tab;
	indent();
	cputc((char)'}');
}


/*	‚v‚q‚h‚s‚d•¶ˆ—	*/

void p2_write()
{
	indentx();
	cputs("b_write(");
	if (kcode == '#') {
		expression((char)V_FILE);
		expect(',');
	} else
		cputs("(FB *)0");
	cputc((char)',');
	print((char)1);
	cputs(");");
}
