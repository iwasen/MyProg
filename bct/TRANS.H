/********************************************************
*							*
*	ＢＡＳＩＣ　ＴＯ　Ｃ　トランスレータ　		*
*							*
*		共通ヘッダー				*
*							*
*			author	      : s.aizawa(xxxx)	*
*			creation date : 87.02.25	*
*			file name     : trans.h		*
*			update	      :			*
*							*
********************************************************/

#ifdef	TMP_MAX
#define	fprintf	fp_fprintf
#define	sscanf	fp_sscanf
#endif

/*	システム定数	*/

#define	MAX_VAR		1000		/* max. variable number		*/
#define	MAX_DIM		100		/* max. dimension number	*/
#define	MAX_LABEL	500		/* max. labal number		*/
#define	MAX_DEFFN	20		/* max. user function		*/
#define	CBUFSIZ		5000		/* common buffer size		*/
#define	SCTBLSIZ	300		/* string constant save area size */
#define EXPRNODE	100		/* expression node		*/


/*	構造体定義	*/

typedef	struct	{
	int	keycode;
	char	*keydata;
	char	*funcname;
	char	keyflag;
} KEYWORD;

typedef	struct	{
	char	vartype;
	char	*vnamep;
	char	strsiz;
	char	dim;
	int	*dimbufp;
	char	use_flag;
} VAR;

typedef	struct	_EXPR {
	int	n_kcode;
	double	n_value;
	char	n_type;
	struct	_EXPR	*n_left;
	struct	_EXPR	*n_right;
} EXPR;


typedef	union	{
	double	d;
	int	i;
} VAL;


/*	変数，定数型定義	*/

#define	V_CHR	1
#define	V_INT	2
#define	V_LNG	3
#define	V_SNG	4
#define	V_DBL	5
#define	V_STR	6
#define	V_STRP	7
#define	V_FILE	8
#define	V_REL	9

/*	エラーコード定義	*/

#define	E_NF		1		/* NEXT without FOR		*/
#define	E_SN		2		/* syntax error			*/
#define	E_FC		5		/* illegal function call	*/
#define	E_UL		8		/* undefined line number	*/
#define	E_DD		10		/* duplicate definition		*/
#define	E_TM		13		/* type mismatch		*/
#define	E_UF		18		/* undefined user function	*/
#define	E_FN		26		/* FOR without NEXT		*/
#define	E_WE		29		/* WHILE without WEND		*/
#define	E_WH		30		/* WEND without WHILE		*/
#define	E_FU		75		/* future not available		*/

#define	E_VAROV		101		/* variable number over		*/
#define	E_LABELOV	102		/* label number	over		*/
#define	E_DIMOV		103		/* dimension number over	*/
#define	E_CBUFOV	104		/* common buffer over		*/
#define	E_CFILE		105		/* C file access error		*/
#define	E_EXPRNODE	106		/* expression node table overflow */
#define	E_SCTBLOV	107		/* string constant table overflow */
#define	E_DEFFNOV	108		/* user function overflow	*/
