/********************************************************
*							*
*	ÇaÇ`ÇrÇhÇbÅ@ÇsÇnÅ@ÇbÅ@ÉgÉâÉìÉXÉåÅ[É^Å@		*
*							*
*		äOïîéQè∆íËã`				*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.03.03	*
*			file name     : ext.h		*
*			update	      :			*
*							*
********************************************************/

extern	char	b_file[];		/* basic file name		*/
extern	char	c_file[];		/* C file name			*/

extern	FILE	*bfp;			/* file discripter (basic file)	*/
extern	FILE	*cfp;			/* file discripter (C file)	*/

extern	char	o_src;			/* source line output option	*/
extern	int	o_lnsize;		/* source bunkatsu line suu	*/

extern	char	linebuf[];		/* source line buffer		*/
extern	char	*lp;			/* line buffer pointer		*/
extern	char	wordbuf[];		/* word buffer			*/
extern	int	kcode;			/* key code			*/
extern	char	kflag;			/* key word flag		*/
extern	VAL	value;			/* constant value		*/
extern	char	vtype;			/* valiable, constant type	*/

extern	int	vnum;			/* variable number		*/
extern	VAR	vartbl[];	 	/* variable data save area	*/

extern	int	cnum;			/* string constant number	*/
extern	char	*sctbl[];		/* string constant table	*/
extern	char	cbuf[];		/* constant & variable name save area	*/
extern	int	cbufp;			/* cbuff pointer 		*/

extern	char	defvtype[];		/* default variable type	*/

extern	int	dimbuf[];		/* dimension save area		*/
extern	int	dnum;			/* dimension number		*/

extern	int	file;			/* max. file No.		*/
extern	int	rbufsiz;		/* random file buffer size	*/
extern	int	strsize;		/* string variable size		*/

extern	int	lbtbl[];		/* label No. table		*/
extern	int	lnum;			/* label number			*/

extern	char	*fntbl[];		/* user function table		*/
extern	char	fnum;

extern	char	vnamebuf[];		/* variable name buffer		*/

extern	char	tab;			/* tab position			*/

extern	char	*funcp;			/* function name pointer	*/

extern	int	line_no;		/* current line number		*/

extern	char	not_flag;		/* not or complement flag	*/

extern	int	err_count;		/* error counter		*/
extern	char	erl_flag;		/* error line number display flag */

extern	char	fn_flag;		/* DEF FN flag			*/
extern	char	dt_flag;		/* DATA flag			*/
extern	int	fst_data;		/* first data line number	*/

extern	char	usrtbl[];		/* usr table			*/

extern	char	on_err_flag;		/* ON ERROR GOTO flag		*/
extern	char	resume_flag;		/* RESUME flag			*/
extern	char	on_key_flag;		/* ON KEY(n) GOSUB flag		*/
extern	char	int_flag;		/* interrupt flag		*/

extern	KEYWORD	keytbl[];		/* keyword table		*/
extern	int	keyn;			/* keyword number		*/

extern	void	pass1(void);		/* pass1 process		*/
extern	void	pass2(void);		/* pass2 process		*/
extern	void	error(int);		/* error process		*/
extern	void	syntax_err(void);	/* syntax error 		*/
extern	void	type_err(void);		/* type mismatch error		*/ 
extern	void	getcode(void);		/* get code			*/
extern	void	nextline(void);		/* set next line		*/
extern	VAR	*srchvar(void);		/* search variable		*/
extern	int	srchsc(void);		/* search string constant	*/
extern	char	srchlb(void);		/* search label			*/
extern	char	cbufset(char);		/* common buufer set		*/
extern	void	cputc(char);
extern	void	cputs(char *);		/* c file output		*/
extern	int	expr_check(void);	/* expression check		*/
extern	void	nextcom();		/* get next command		*/
extern	void	type_put(char);		/* type put			*/
extern	void	kc_check(int);		/* keycode check		*/
extern	void	syn_check(int);		/* syntax check			*/
extern	void	expect(int);
extern	void	expression(char);
extern	void	expr_(char);
extern	void	expr_out(void);
