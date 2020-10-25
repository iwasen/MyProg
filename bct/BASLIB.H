#pragma	nonrec

#define	sprintf	fp_sprintf
#define	sscanf	fp_sscanf

#define	NULL	0
#define	EOF	0x1a

char	_asm_c(char *);
char	_asm_ci(char *, int);

#define	label(c)	_asm_ci("\nL$1:\n",c)
#define	jmp(c)		_asm_ci("\n\tjmp\tL$1\n",c)
#define	jmpf(c)		_asm_ci("\n\tjmp\tf$1_\n",c)
#define	jmpr(c)		_asm_ci("\n\textrn\tspsave_\n\tlhld\tspsave_\n\tsphl\n\tjmp\tL$1\n", c)
#define	call(c)		_asm_ci("\n\tcall\tL$1\n",c)
#define	ret()		_asm_c("\n\tret\n")
#define	retn(c)		_asm_ci("\n\tpop\tb\n\tjmp\tL$1\n",c)
#define	on_err_goto(c)	_asm_ci("\n\tlxi\th,L$1\n",c);b_on_err()
#define	on_com_gosub(n,c)	_asm_ci("\n\tlxi\td,L$1\n",c);b_on_com(n)
#define	on_key_gosub(n,c)	_asm_ci("\n\tlxi\td,L$1\n",c);b_on_key(n)
#define	on_brcd_gosub(c)	_asm_ci("\n\tlxi\th,L$1\n",c);b_on_brcd()

typedef	struct	{
	char	s_len;
	char	*s_str;
} STRTBL;

typedef	struct	{
	char	f_mode;
	char	f_fcb[36];
	char	f_dummy1[2];
	char	f_iptr;
	char	f_remchr;
	char	f_dummy2[3];
	char	f_device;
	char	f_maxcn;
	char	f_dummy3[3];
	char	f_dmabuf[128];
	int	f_recsiz;
	int	f_precno;
	int	f_lrecno;
	char	f_flag;
	char	f_dummy4[2];
#ifdef	RBUFSIZ
	char	f_rbuf[RBUFSIZ];
#else
	char	f_rbuf[128];
#endif
} FB;

typedef	struct	{
	int	x;
	int	y;
} LASTPOINT;

extern	char	b_setjmp();
extern	void	b_error(int);
extern	int	b_eqv(int, int);
extern	int	b_imp(int, int);
extern	float	b_ipwr(float, int);
extern	float	b_rpwr(float, float);

extern	STRTBL	*b_getarea(char);
extern	void	b_strset(STRTBL *, STRTBL *);
extern	STRTBL	*b_stradd(STRTBL *, STRTBL *);
extern	int	b_strcmp(STRTBL *, STRTBL *);
extern	void	b_cursor_off();
extern	void	b_keyint();
extern	void	b_intrpt();

/*	command		*/

extern	void	b_backlight();
extern	void	b_beep();
extern	void	b_bload();
extern	void	b_brcd();
extern	void	b_bsave();
extern	void	b_call(int, ...);
extern	void	b_circle();
extern	void	b_clear();
extern	void	b_close(FB *, ...);
extern	void	b_cls();
extern	void	b_color();
extern	void	b_comn();
extern	void	b_copy();
extern	void	b_country();
extern	void	b_currency();
extern	void	b_sdate();
extern	void	b_sday();
extern	void	b_end();
extern	void	b_field(FB *, ...);
extern	void	b_gaiji();
extern	void	b_get();
extern	void	b_geta();
extern	void	b_input(FB *, char *, ...);
extern	void	b_kanji(int, int, int, int, int, ...);
extern	void	b_key();
extern	void	b_keyn();
extern	void	b_touch();
extern	void	b_kill();
extern	void	b_line();
extern	void	b_lineinp();
extern	void	b_lkanji(int, ...);
extern	void	b_locate();
extern	void	b_lset();
extern	void	b_midc();
extern	void	b_mscreen();
extern	void	b_name();
extern	void	b_on_brcd();
extern	void	b_on_err();
extern	void	b_on_com();
extern	void	b_on_key();
extern	void	b_open1();
extern	void	b_open2();
extern	void	b_out();
extern	void	b_paint();
extern	void	b_poke();
extern	void	b_power();
extern	void	b_pset();
extern	void	b_preset();
extern	void	b_print(FB *, char *, ...);
extern	void	b_prtuse(FB *, char *, ...);
extern	void	b_put();
extern	void	b_puta();
extern	void	b_randomize();
extern	void	b_read(char *, ...);
extern	void	b_reset();
extern	void	b_resume();
extern	void	b_rset();
extern	void	b_run();
extern	void	b_salxxx();
extern	void	b_set();
extern	void	b_sound();
extern	void	b_stop();
extern	void	b_cswap();
extern	void	b_iswap();
extern	void	b_lswap();
extern	void	b_fswap();
extern	void	b_dswap();
extern	void	b_sswap();
extern	void	b_system();
extern	void	b_stime();
extern	void	b_view();
extern	void	b_wait();
extern	void	b_width();
extern	void	b_dwidth();
extern	void	b_fwidth();
extern	void	b_lwidth();
extern	void	b_window();
extern	void	b_write(FB *, char *, ...);

/*	function	*/

extern	double	b_abs();
extern	int	b_asc();
extern	float	b_atn();
extern	STRTBL *b_attr();
extern	double	b_cdbl();
extern	STRTBL *b_chr();
extern	int	b_cint();
extern	float	b_cos();
extern	float	b_csng();
extern	int	b_csrlin();
extern	double	b_cvd();
extern	int	b_cvi();
extern	float	b_cvs();
extern	STRTBL *b_galxxx();
extern	STRTBL *b_gdate();
extern	int	b_gday();
extern	int	b_dskf();
extern	int	b_eof();
extern	float	b_exp();
extern	double	b_fix();
extern	STRTBL *b_hex();
extern	STRTBL *b_inkey();
extern	int	b_inp();
extern	STRTBL *b_sinput();
extern	int	b_instr();
extern	double	b_int();
extern	STRTBL *b_kinput();
extern	STRTBL *b_left();
extern	int	b_len();
extern	int	b_loc();
extern	int	b_lof();
extern	float	b_log();
extern	int	b_lpos();
extern	STRTBL *b_mid();
extern	STRTBL *b_mkd();
extern	STRTBL *b_mki();
extern	STRTBL *b_mks();
extern	STRTBL *b_oct();
extern	int	b_peek();
extern	int	b_point();
extern	int	b_pos();
extern	STRTBL *b_right();
extern	float	b_rnd();
extern	int	b_screen();
extern	int	b_sgn();
extern	float	b_sin();
extern	STRTBL *b_space();
extern	float	b_sqr();
extern	STRTBL *b_str();
extern	STRTBL *b_string();
extern	float	b_tan();
extern	STRTBL *b_gtime();
extern	double	b_val();
extern	int	b_iusr();
extern	long	b_lusr();
extern	float	b_fusr();
extern	double	b_dusr();
extern	STRTBL *b_susr();

/*	data	*/

extern	int	erl;
extern	int	err;
extern	int	lno;
extern	char	b_work[256];
extern	char	cursor_flag;
extern	LASTPOINT	lp;
extern	char	*data_ptr;
extern	char	lpos;
extern	char	lp_width;
extern	char	cm_width[4];
extern	char	currency;

/*	error code	*/

#define	E_OD	4		/* out of data			*/
#define	E_FC	5		/* illegal function		*/
#define	E_OV	6		/* over	flow			*/
#define	E_OM	7		/* out of memory		*/
#define	E_CO	28		/* communication buffer overflow */
#define	E_BN	52		/* bad file number		*/
#define	E_NE	53		/* file not found		*/
#define	E_BF	54		/* bad file mode		*/
#define	E_AO	55		/* file already open		*/
#define	E_IO	57		/* device i/o error		*/
#define	E_DF	61		/* disk full			*/
#define	E_IE	62		/* input past end		*/
#define	E_FD	64		/* bad file desctiptor		*/
#define	E_DU	68		/* device unavailable		*/
#define	E_WP	69		/* write protect error		*/
#define	E_DR	70		/* disk read error		*/
#define	E_DW	71		/* disk write  error		*/
