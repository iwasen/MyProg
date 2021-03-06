 /*
 * File		: tbfnky.c
 * Comment	: Function key (F1..F5) process
 *			(User's definition enable)
 *
 *
 * --- Modify history ---
 * No.   Date      Name        Description                       Report No
 * --- --------  --------  ------------------------------------  ---------
 *  2. 88/04/19  M.Hosho   append tbfn11-15, tbfn21-25
 *  1. 88/03/18  M.Hosho   1st released
 *
 */

#define	ACT_INPT	0x00040000	/* goto input data */
					/* equal to 'act_inpt' in 'epsymb.h' */

extern	int	slblno;			/* page(0..3) of F1-F5 key */

static	char	sccsid[]="%Z%%M% %I% %E% %U%";

/*
 * Entry point	: tbfn01
 * Comment	: Func_key F1 processes
 *
 */
tbfn01()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		rtn = tbprnt();
		return( rtn );
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn02
 * Comment	: Func_key F2 processes
 *
 */
tbfn02()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		rtn = tbserc();
		return( rtn );
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn03
 * Comment	: Func_key F3 processes
 *
 */
tbfn03()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn04
 * Comment	: Func_key F4 processes
 *
 */
tbfn04()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbcltb() );		/* Iðð */
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn05
 * Comment	: Func_key F5 processes
 *
 */
tbfn05()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbsatr() );
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn06
 * Comment	: Func_key F6 processes (ñìæÊ)
 *
 */
tbfn06()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );		/* re_entr */
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn07
 * Comment	: Func_key F7 processes (ñìæÊ)
 *
 */
tbfn07()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );		/* re_entr */
	default: ;
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Comment	: Function key (F1..F5) process
 *			(sì^ñìæÊ)
 *			(User's definition enable)
 */

/*
 * Entry point	: tbfn21
 * Comment	: Func_key F1 processes (sìæÊ)
 *
 */
tbfn21()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbtm01() );		/* sí */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn22
 * Comment	: Func_key F2 processes (sìæÊ)
 *
 */
tbfn22()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbtm00() );		/* sCOPY */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn23
 * Comment	: Func_key F3 processes (sìæÊ)
 *
 */
tbfn23()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbprnt() );		/* óü */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn24
 * Comment	: Func_key F4 processes (sìæÊ)
 *
 */
tbfn24()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbsrec() );		/* R[hIðæÊw */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn25
 * Comment	: Func_key F5 processes (sìæÊ)
 *
 */
tbfn25()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbsat2() );		/* ñìæÊÖ */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn26
 * Comment	: Func_key F6 processes (ñìæÊ)
 *
 */
tbfn26()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbserc() );		/* re_entr */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn27
 * Comment	: Func_key F7 processes (ñìæÊ)
 *
 */
tbfn27()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );		/* re_entr */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn31
 * Comment	: Func_key F1 processes (ñìæÊ)
 *
 */
tbfn31()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbtm03() );		/* ñí */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn32
 * Comment	: Func_key F2 processes (ñìæÊ)
 *
 */
tbfn32()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbtm02() );		/* ñ}ü */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn33
 * Comment	: Func_key F3 processes (ñìæÊ)
 *
 */
tbfn33()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn34
 * Comment	: Func_key F4 processes (ñìæÊ)
 *
 */
tbfn34()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbsrec() );		/* R[hIðæÊw */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn35
 * Comment	: Func_key F5 processes (ñìæÊ)
 *
 */
tbfn35()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( tbsat2() );		/* sìæÊÖ */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn36
 * Comment	: Func_key F6 processes (ñìæÊ)
 *
 */
tbfn36()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );		/* re_entr */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}

/*
 * Entry point	: tbfn37
 * Comment	: Func_key F7 processes (ñìæÊ)
 *
 */
tbfn37()
{
	int	func_no = 0;
	int	rtn	= 0;

	switch (slblno) {
	case 0:	
		return( ACT_INPT );		/* re_entr */
	case 1:	
		return( ACT_INPT );		/* re_entr */
	}
	return( ACT_INPT );			/* re_entr */
}
