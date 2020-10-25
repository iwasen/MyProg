/********************************************************
*							*
*	‚a‚`‚r‚h‚b@‚s‚n@‚b@ƒgƒ‰ƒ“ƒXƒŒ[ƒ^@		*
*							*
*		‚o‚`‚r‚r‚Q ®“WŠJˆ—			*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.03.18	*
*			file name     : expr.c		*
*			update	      :			*
*							*
********************************************************/

#include	<stdio.h>
#include	<string.h>
#include	"trans.h"
#include	"keyword.h"
#include	"ext.h"


static	EXPR	expr_node[EXPRNODE];	/* expression node		*/
static	int	free_node;		/* free node			*/
static	EXPR	*to_expr;

EXPR	*put_node(int, double, char);
EXPR	*cons_node(int, char, EXPR *, EXPR *);
EXPR	*tcc_node(int, char, EXPR *, EXPR *);
void	type_check(EXPR *);
EXPR	*exp_cast(char);
EXPR	*exp_eqv();
EXPR	*exp_imp();
EXPR	*exp_xor();
EXPR	*exp_or();
EXPR	*exp_and();
EXPR	*exp_not();
EXPR	*exp_rel();
EXPR	*exp_pm();
EXPR	*exp_mod();
EXPR	*exp_idiv();
EXPR	*exp_md();
EXPR	*exp_minus();
EXPR	*exp_power();
EXPR	*exp_primary();
EXPR	*exp_func();
void	out_rec(EXPR *);


/*	®“WŠJo—Íˆ—		*/

void expression(cast)
char	cast;
{
	expr_(cast);
	expr_out();
}


/*	•Ï”o—Íˆ—	*/

char varout(flag)
char	flag;
{
	char	vt;

	vt = vtype;
	switch (kcode) {
	case K_VAR:
	case K_AVAR:
		if (flag && vt != V_STR)
			cputc((char)'&');
		free_node = 0;
		out_rec(exp_primary());
		break;
	default:
		syntax_err();
	}
	return(vt);
}


/*	®“WŠJˆ—	*/

void expr_(cast)
char	cast;
{
	free_node = 0;
	to_expr = exp_cast(cast);
}


static EXPR *put_node(kc, value, type)
int	kc;
double	value;
char	type;
{
	EXPR	*ptr;

	if (free_node >= EXPRNODE)
		error(E_EXPRNODE);
	ptr = &expr_node[free_node++];
	ptr->n_kcode = kc;
	ptr->n_type = type;
	ptr->n_value = value;
	return(ptr);
}


static EXPR *cons_node(kc, type, left, right)
int	kc;
char	type;
EXPR	*left, *right;
{
	EXPR	*ptr;

	if (free_node >= EXPRNODE)
		error(E_EXPRNODE);
	ptr = &expr_node[free_node++];
	ptr->n_kcode = kc;
	ptr->n_type = type;
	ptr->n_left = left;
	ptr->n_right = right;
	return(ptr);
}


static EXPR *tcc_node(kc, type, left, right)
int	kc;
char	type;
EXPR	*left, *right;
{
	if ((left->n_type == V_STR) != (right->n_type == V_STR))
		type_err();
	if (type == 0)
		type = left->n_type > right->n_type ? left->n_type : right->n_type;
	return(cons_node(kc, type, left, right));
}
 

/*	”’lŒ^ƒ`ƒFƒbƒN	*/

static void type_check(exp)
EXPR	*exp;
{
	if (exp->n_type == V_STR)
		type_err();
}


/*	ƒLƒƒƒXƒgˆ—	*/

static EXPR *exp_cast(cast)
char	cast;
{
	EXPR	*result;

	result = exp_eqv();
	if (((result->n_type == V_STR) != (cast == V_STR))
			|| (result->n_type == V_FILE && cast != V_FILE))
		type_err();
	if (cast != 0 && cast != result->n_type)
		result = cons_node(K_CAST, cast, result, NULL);
	return(result);
}


/*	‚d‚p‚uˆ—	*/

static EXPR *exp_eqv()
{
	EXPR	*result;

	result = exp_imp();
	while (kcode == K_EQV) {
		type_check(result);
		getcode();
		result = tcc_node(K_EQV, (char)V_INT, result, exp_imp());
	}
	return(result);
}


/*	‚h‚l‚oˆ—	*/

static EXPR *exp_imp()
{
	EXPR	*result;

	result = exp_xor();
	while (kcode == K_IMP) {
		type_check(result);
		getcode();
		result = tcc_node(K_IMP, (char)V_INT, result, exp_xor());
	}
	return(result);
}


/*	‚w‚n‚qˆ—	*/

static EXPR *exp_xor()
{
	EXPR	*result;

	result = exp_or();
	while (kcode == K_XOR) {
		type_check(result);
		getcode();
		result = tcc_node(K_XOR, (char)V_INT, result, exp_or());
	}
	return(result);
}


/*	‚n‚qˆ—	*/

static EXPR *exp_or()
{
	EXPR	*result;

	result = exp_and();
	while (kcode == K_OR) {
		type_check(result);
		getcode();
		result = tcc_node(K_OR, (char)V_INT, result, exp_and());
	}
	return(result);
}


/*	‚`‚m‚cˆ—	*/

static EXPR *exp_and()
{
	EXPR	*result;

	result = exp_not();
	while (kcode == K_AND) {
		type_check(result);
		getcode();
		result = tcc_node(K_AND, (char)V_INT, result, exp_not());
	}
	return(result);
}


/*	‚m‚n‚sˆ—	*/

static EXPR *exp_not()
{
	EXPR	*result;

	if (kcode == K_NOT) {
		getcode();
		result = exp_rel();
		type_check(result);
		result = cons_node(K_NOT, result->n_type, result, NULL);
	}
	else
		result = exp_rel();
	return(result);
}


/*	ŠÖŒW‰‰Zˆ—	*/

static EXPR *exp_rel()
{
	EXPR	*result;
	int	kc;

	result = exp_pm();
	while (kcode == '=' || kcode == '<' || kcode == '>'
			|| kcode == K_LE || kcode == K_GE || kcode == K_NE) {
		kc = kcode;
		getcode();
		result = tcc_node(kc, (char)V_INT, result, exp_pm());
	}
	return(result);
}


/*	{C| ˆ—	*/

static EXPR *exp_pm()
{
	EXPR	*result;
	int	kc;

	result = exp_mod();
	while (kcode == '+' || kcode == '-') {
		if (kcode == '-' && result->n_type == V_STR)
			type_err();
		kc = kcode;
		getcode();
		result = tcc_node(kc, 0, result, exp_mod());
	}
	return(result);
}


/*	‚l‚n‚cˆ—	*/

static EXPR *exp_mod()
{
	EXPR	*result;

	result = exp_idiv();
	while (kcode == K_MOD) {
		type_check(result);
		getcode();
		result = tcc_node(K_MOD, (char)V_INT, result, exp_idiv());
	}
	return(result);
}


/*	®”‰‰Zijˆ—	*/

static EXPR *exp_idiv()
{
	EXPR	*result;

	result = exp_md();
	while (kcode == '\\') {
		type_check(result);
		getcode();
		result = tcc_node('\\', (char)V_INT, result, exp_md());
	}
	return(result);
}


/*	–C^ ˆ—	*/

static EXPR *exp_md()
{
	EXPR	*result;
	int	kc;

	result = exp_minus();
	while (kcode == '*' || kcode == '/') {
		type_check(result);
		kc = kcode;
		getcode();
		result = tcc_node(kc, 0, result, exp_minus());
	}
	return(result);
}


/*	|•„†ˆ—	*/

static EXPR *exp_minus()
{
	EXPR	*result;

	if (kcode == '-') {
		getcode();
		result = exp_power();
		type_check(result);
		result = cons_node(K_MINUS, result->n_type, result, NULL);
	}
	else
		result = exp_power();
	return(result);
}


/*	‚×‚«æˆ—	*/

static EXPR *exp_power()
{
	EXPR	*result;

	result = exp_primary();
	while (kcode == '^') {
		type_check(result);
		getcode();
		result = tcc_node('^', (char)V_SNG, result, exp_primary());
	}
	return(result);
}


/*	ˆêŸ®ˆ—	*/

static EXPR *exp_primary()
{
	EXPR	*result, *temp, *temp2;
	char	i, type, **fp;
	VAR	*vp;
	char	fncmp(char *);
	int	val;

	switch (kcode) {
	case '#':
		getcode();
		result = exp_cast((char)V_FILE);
		break;
	case K_NCONST:
		if (value.d == (int)value.d)
			type = V_INT;
		else if (value.d == (long)value.d)
			type = V_LNG;
		else
			type = V_DBL;
		result = put_node(kcode, value.d, type);
		if (vtype)
			result = cons_node(K_CAST, vtype, result, NULL);
		getcode();
		break;
	case K_SCONST:
		result = put_node(kcode, (double)srchsc(), (char)V_STR);
		getcode();
		break;
	case K_VAR:
		result = cons_node(kcode, vtype, (EXPR *)srchvar(), NULL);
		getcode();
		break;
	case K_AVAR:
		vp = srchvar();
		result = cons_node(kcode, vtype, (EXPR *)vp, NULL);
		getcode();
		expect('(');
		temp = result;
		for (i = 0; i != vp->dim; ) {
			temp2 = exp_cast((char)V_INT);
			if (temp2->n_type == V_STR)
				type_err();
			temp = temp->n_right =
				cons_node(0, temp2->n_type, temp2, NULL);
			if (++i != vp->dim)
				expect(',');
		}
		expect(')');
		break;
	case '(':
		getcode();
		result = exp_eqv();
		expect(')');
		break;
	case K_USR:
		if (usrtbl[value.i] == 0)
			error(E_FC);
		val = value.i;
		getcode();
		expect('(');
		temp = exp_eqv();
		expect(')');
		result = cons_node(K_USR, temp->n_type, temp, NULL);
		*(int *)&result->n_value = val;
		break;
	case K_VARPTR:
		getcode();
		expect('(');
		switch (kcode) {
		case K_VAR:
		case K_AVAR:
		case '#':
			break;
		default:
			syntax_err();
		}
		result = cons_node(K_VARPTR, (char)V_INT, exp_primary(), NULL);
		expect(')');
		break;
	case K_ERL:
	case K_ERR:
		result = cons_node(kcode, (char)V_INT, NULL, NULL);
		getcode();
		break;
	case K_FNX:
		for (i = 0, fp = fntbl; ; i++, fp++) {
			if (i == fnum)
				error(E_UF);
			if (fncmp(*fp) == 0)
				break;
		}
		funcp = *fp;
	default:
		result = exp_func();
		break;
	}
	return(result);
}

static char fncmp(fp)
char	*fp;
{
	char	*p;

	for (p = vnamebuf; ; p++, fp++) {
		if ((*fp == ':' || *fp == 0) && *p == 0)
			return(0);
		if (*fp != *p)
			break;
	}
	return(1);
}		


/*	ŠÖ”ˆ—	*/

static EXPR *exp_func()
{
	EXPR	*result, *temp, *temp2;
	char	*s, *p, c, sflag;
	char	cast;

	if (vtype == 0)
		syntax_err();

	s = strchr(funcp,':');

	result = cons_node(kcode, vtype, (EXPR *)funcp, NULL);

	getcode();
	if (s == NULL)
		return(result);

	temp = result;
	temp2 = NULL;
	sflag = 0;
	s++;
	while (*s) {
		switch (c = *s++) {
		case 'c':
			cast = V_CHR;
			break;
		case 'i':
			cast = V_INT;
			break;
		case 'l':
			cast = V_LNG;
			break;
		case 'f':
			cast = V_SNG;
			break;
		case 'd':
			cast = V_DBL;
			break;
		case 's':
			cast = V_STR;
			break;
		case '#':
			cast = V_FILE;
			break;
		case 'x':
			if (vtype == V_STR)
				cast = V_STR;
			else
				cast = V_INT;
			temp = temp->n_right = cons_node(0, (char)V_INT, put_node(K_NCONST, cast == V_STR ? (double)1 : (double)0, (char)V_INT), NULL);
			break;
		case '[':
			sflag = 1;
			continue;
		case ']':
			sflag = 0;
			continue;
		default:
			if (kcode == c)
				getcode();
			else if (sflag == 0)
				syntax_err();
			continue;
		}

		if (sflag) {
			p = s;
			while (*s++ != '}') {}
			if (expr_check() == 0 && kcode != '#') {
def_data:
				temp = temp->n_right
					 = cons_node(1, cast, (EXPR *)p, NULL);
				continue;
			}
			temp2 = exp_eqv();
			if ((temp2->n_type == V_STR) != (cast == V_STR)) {
				s++;
				goto def_data;
			}
		}
		if (temp2 == NULL)
			temp2 = exp_eqv();
		if (((temp2->n_type == V_STR) != (cast == V_STR))
				|| (temp2->n_type == V_FILE && cast != V_FILE))
			type_err();
		if (cast != temp2->n_type)
			temp2 = cons_node(K_CAST, cast, temp2, NULL);
		temp = temp->n_right =
			cons_node(0, cast, temp2, NULL);
		temp2 = NULL;
	}
	return(result);
}


/*	®o—Íˆ—	*/

void expr_out()
{
	out_rec(to_expr);
}


static void out_rec(ptr)
EXPR	*ptr;
{
	void	out_sub(EXPR*, char);
	char	*s;
	char	cast;
	VAR	*vp;

	switch (ptr->n_kcode) {
	case K_CAST:
		cast = ptr->n_type;
		switch (cast) {
		case V_FILE:
			cputs("&f_buff[");
			out_sub(ptr->n_left,(char)V_INT);
			cputc((char)']');
			break;
		case V_REL:
			if (ptr->n_left->n_type != V_INT) {
				out_rec(ptr->n_left);
				cputs(" != 0");
				break;
			}
			/* no break */
		default:
			goto out_2;
		}
		break;
	case K_EQV:
		cputs("b_eqv");
		cast = V_INT;
		goto out_3;
	case K_IMP:
		cputs("b_imp");
		cast = V_INT;
		goto out_3;
	case K_XOR:
		s = "^";
		goto out_0;
	case K_OR:
		s = "|";
		goto out_0;
	case K_AND:
		s = "&";
		goto out_0;
	case K_NOT:
		if (not_flag == 0)
			cputc((char)'~');
		else
			cputc((char)'!');
		cast = V_INT;
		goto out_2;
	case '=':
		s = "==";
		goto out_rel;
	case '>':
		s = ">";
		goto out_rel;
	case '<':
		s = "<";
		goto out_rel;
	case K_GE:
		s = ">=";
		goto out_rel;
	case K_LE:
		s = "<=";
		goto out_rel;
	case K_NE:
		s = "!=";
	out_rel:
		if (ptr->n_left->n_type == V_STR) {
			cputs("(b_strcmp(");
			out_sub(ptr->n_left,(char)V_STR);
			cputc((char)',');
			out_sub(ptr->n_right,(char)V_STR);
			fprintf(cfp,") %s 0)",s);
			break;
		}
		goto out_1;
	case '+':
		if (ptr->n_type == V_STR) {
			cputs("b_stradd");
			cast = V_STR;
			goto out_3;
		}
		s = "+";
		goto out_1;
	case '-':
		s = "-";
		goto out_1;
	case K_MOD:
		s = "%";
	out_0:
		cast = V_INT;
		goto out_11;
	case '\\':
		cputs("(int)");
		s = "/";
		goto out_1;
	case '*':
		s = "*";
		goto out_1;
	case '/':
		s = "/";
	out_1:
		cast = 0;
	out_11:
		cputc((char)'(');
		out_sub(ptr->n_left,cast);
		cputc((char)' ');
		cputs(s);
		cputc((char)' ');
		out_sub(ptr->n_right,cast);
		cputc((char)')');
		break;
	case K_MINUS:
		cputc((char)'-');
		cast = 0;
	out_2:
		out_sub(ptr->n_left,cast);
		break;
	case '^':
		if (ptr->n_right->n_type == V_INT) {
			cputs("b_ipwr");
			cast = V_INT;
		} else {
			cputs("b_rpwr");
			cast = V_SNG;
		}
		cputc((char)'(');
		out_sub(ptr->n_left,(char)V_SNG);
		goto out_4;
	out_3:
		cputc((char)'(');
		out_sub(ptr->n_left,cast);
	out_4:
		cputc((char)',');
		out_sub(ptr->n_right,cast);
		cputc((char)')');
		break;
	case K_NCONST:
		switch (ptr->n_type) {
		case V_FILE:
		case V_INT:
		case V_LNG:
			s = "%.0lf";
			break;
		case V_SNG:
			s = "%lg";
			break;
		case V_DBL:
			s = "%.15lg";
			break;
		}
		fprintf(cfp,s,ptr->n_value);
		break;
	case K_SCONST:
		fprintf(cfp,"&const_s[%u]",(int)ptr->n_value);
		break;
	case K_VAR:
		vp = (VAR *)ptr->n_left;
		if (vp->vartype == V_STR)
			cputc((char)'&');
		cputs(vp->vnamep);
		break;
	case K_AVAR:
		vp = (VAR *)ptr->n_left;
		if (vp->vartype == V_STR)
			cputc((char)'&');
		cputs(vp->vnamep);
		while (ptr = ptr->n_right) {
			cputc((char)'[');
			out_rec(ptr->n_left);
			cputc((char)']');
		}
		break;
	case K_USR:
		cputs("b_");
		type_put(ptr->n_type);
		fprintf(cfp,"usr(usr%d,",*(int *)&ptr->n_value);
		out_rec(ptr->n_left);
		cputc((char)')');
		break;
	case K_VARPTR:
		cputs("(int)");
		switch (ptr->n_left->n_type) {
		case V_STR:
		case V_FILE:
			break;
		default:
			cputc((char)'&');
		}
		out_rec(ptr->n_left);
		break;
	case K_ERL:
		cputs("erl");
		break;
	case K_ERR:
		cputs("err");
		break;
	default:
		s = (char *)ptr->n_left;
		while (*s != NULL && *s != ':')
			cputc(*s++);
		cputc((char)'(');
		while (ptr = ptr->n_right) {
			if (ptr->n_kcode) {
				s = (char *)ptr->n_left;
				while (*(++s) != '}')
					cputc(*s);
			} else
				out_rec(ptr->n_left);
			if (ptr->n_right)
				cputc((char)',');
		}
		cputc((char)')');
		break;
	}
}

static void out_sub(ptr, cast)
EXPR	*ptr;
char	cast;
{
	if (ptr->n_type != cast) {
		switch (cast) {
		case V_CHR:
			cputs("(char)");
			break;
		case V_INT:
			cputs("(int)");
			break;
		case V_LNG:
			cputs("(long)");
			break;
		case V_SNG:
			cputs("(float)");
			break;
		case V_DBL:
			cputs("(double)");
			break;
		}
	}
	out_rec(ptr);
}


/*	‚o‚q‚h‚m‚sC‚v‚q‚h‚s‚dƒf[ƒ^•ÒWo—Íˆ—	*/

void print(flag)
char	flag;		/* flag=0:print flag=1:write	*/ 
{
	EXPR	*top, *temp, *temp2;
	int	kc;

	free_node = 0;
	top = temp = cons_node(0, (char)0, NULL, NULL);
	while (1) {
		if (expr_check()) {
			temp2 = exp_eqv();
			temp2 = cons_node(0, temp2->n_type, temp2, NULL); 
		} else {
			switch (kcode) {
			case ';':
				if (flag != 0)
					syntax_err();
			case ',':
				temp2 = cons_node(kcode, (char)0, NULL, NULL);
				getcode();
				break;
			case K_SPC:
			case K_TAB:
				if (flag != 0)
					syntax_err();
				kc = kcode;
				getcode();
				expect('(');
				temp2 = cons_node(kc,(char)0,exp_cast((char)V_INT),NULL);
				expect(')');
				break;
			default:
				goto loop_end;
			}
		}
		temp = temp->n_right = temp2;
	}
loop_end:
	cputc((char)'"');
	for (temp = top->n_right; temp; ) {
		switch (temp->n_kcode) {
		case 0:
			type_put(temp->n_type);
			break;
		case ';':
		case ',':
			if (flag == 0)
				cputc((char)temp->n_kcode);
			break;
		case K_SPC:
			cputc((char)'b');
			break;
		case K_TAB:
			cputc((char)'t');
			break;
		}
		temp = temp->n_right;
	}
	cputc((char)'"');
	for (temp = top->n_right; temp; ) {
		switch (temp->n_kcode) {
		case 0:
		case K_SPC:
		case K_TAB:
			cputc((char)',');
			out_rec(temp->n_left);
			break;
		}
		temp = temp->n_right;
	}
}


/*	‚h‚m‚o‚t‚sƒf[ƒ^•ÒWo—Íˆ—	*/

void input()
{
	EXPR	*top, *temp, *temp2, *temp3, *temp4;
	char	i;
	VAR	*vp;

	free_node = 0;
	top = temp = cons_node(0, (char)0, NULL, NULL);
	if (kcode == K_SCONST) {
		temp = temp->n_right = put_node(0, (double)srchsc(), (char)V_STR);
		getcode();
		if (kcode != ',' && kcode != ';')
			syntax_err();
		temp->n_kcode = kcode;
		getcode();
	}

	while (1) {
		switch (kcode) {
		case K_VAR:
			temp2 = cons_node(kcode, vtype, (EXPR *)srchvar(), NULL);
			getcode();
			break;
		case K_AVAR:
			vp = srchvar();
			temp2 = cons_node(kcode, vtype, (EXPR *)vp, NULL);
			getcode();
			expect('(');
			temp3 = temp2;
			for (i = 0; i != vp->dim; ) {
				temp4 = exp_cast((char)V_INT);
				if (temp4->n_type == V_STR)
					type_err();
				temp3 = temp3->n_right =
					cons_node(0,temp4->n_type,temp4,NULL);
				if (++i != vp->dim)
					expect(',');
			}
			expect(')');
			break;
		default:
			syntax_err();
		}
		temp = temp->n_right = cons_node(0, temp2->n_type, temp2, NULL);
		if (kcode != ',')
			break;
		getcode();
	}

	cputc((char)'"');
	for (temp = top->n_right; temp; ) {
		switch (temp->n_kcode) {
		case 0:
			type_put(temp->n_type);
			break;
		case ',':
		case ';':
			cputc((char)'p');
			cputc((char)temp->n_kcode);
			break;
		}
		temp = temp->n_right;
	}
	cputc((char)'"');
	for (temp = top->n_right; temp; ) {
		cputc((char)',');
		switch (temp->n_kcode) {
		case 0:
			if (temp->n_type != V_STR)
				cputc((char)'&');
			out_rec(temp->n_left);
			break;
		case ',':
		case ';':
			fprintf(cfp,"&const_s[%u]",(int)temp->n_value);
			break;
		}
		temp = temp->n_right;
	}
}
