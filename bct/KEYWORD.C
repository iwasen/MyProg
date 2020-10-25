/********************************************************
*							*
*	ＢＡＳＩＣ　ＴＯ　Ｃ　トランスレータ　		*
*							*
*		キーワード　データ定義			*
*							*
*			author	      : s.aizawa	*
*			creation date : 87.02.25	*
*			keyword.c     : keyword.c	*
*			update	      :			*
*							*
********************************************************/

#include <stdio.h>
#include "trans.h"
#include "keyword.h"

/*	command		*/

extern	char	p2_backlight[];
extern	char	p2_call[];
extern	char	p2_close[];
extern	char	p2_def[];
extern	char	p2_for[];
extern	char	p2_get[];
extern	char	p2_put[];
extern	char	p2_gosub[];
extern	char	p2_goto[];
extern	char	p2_if[];
extern	char	p2_else[];
extern	char	p2_field[];
extern	char	p2_input[];
extern	char	p2_kanji[];
extern	char	p2_key[];
extern	char	p2_next[];
extern	char	p2_let[];
extern	char	p2_line[];
extern	char	p2_lprint[];
extern	char	p2_name[];
extern	char	p2_on[];
extern	char	p2_open[];
extern	char	p2_option[];
extern	char	p2_power[];
extern	char	p2_read[];
extern	char	p2_restore[];
extern	char	p2_return[];
extern	char	p2_print[];
extern	char	p2_resume[];
extern	char	p2_stop[];
extern	char	p2_swap[];
extern	char	p2_while[];
extern	char	p2_width[];
extern	char	p2_wend[];
extern	char	p2_write[];

static char b_salxxx[] = "b_salxxx:[s,s,s,W]";
static char b_beep[] = "b_beep:[i{10}]";
static char b_bload[] = "b_bload:s[,i{0},R]";
static char b_brcd[] = "b_brcd:O";
static char b_bsave[] = "b_bsave:s,i,i";
static char b_circle[] = "b_circle:S(i,i),i[,i{1},f{(float)0},f{(float)6.283},f{(float)1}]";
static char b_clear[] = "b_clear:[i{0},i{0xffff},i{0}]";
static char b_cls[] = "b_cls:[i{0}]";
static char b_color[] = "b_color:i";
static char b_com[] = "b_comn:(i)O";
static char b_copy[] = "b_copy";
static char b_end[] = "b_end";
static char b_error[] = "b_error:i";
static char b_kill[] = "b_kill:s";
static char b_lkanji[] = "b_lkanji:xi";
static char b_locate[] = "b_locate:[i{-1},i{-1},i{-1}]";
static char b_lset[] = "b_lset:v=s";
static char b_out[] = "b_out:i,i";
static char b_paint[] = "b_paint:S(i,i)[,T{1},i{1}]";
static char b_poke[] = "b_poke:i,i";
static char b_pset[] = "b_pset:S(i,i)[,i{1}]";
static char b_preset[] = "b_preset:S(i,i)[,i{0}]";
static char b_randomize[] = "b_randomize:[i{-1}]";
static char b_reset[] = "b_reset";
static char b_rset[] = "b_rset:v=s";
static char b_run[] = "b_run:s";
static char b_set[] = "b_set:s[,P]";
static char b_sound[] = "b_sound:i,i";
static char b_system[] = "b_system";
static char b_view[] = "b_view:[(i{0},i{0})-(i{-1},i{-1}),i{-1},i{-1}]";
static char b_wait[] = "b_wait:i,i[,i{0}]";
static char b_window[] = "b_window:[i{-1},i{-1}]";

/*	function	*/

static char b_abs[] = "b_abs:(d)";
static char b_galxxx[] = "b_galxxx:(i)";
static char b_asc[] = "b_asc:(s)";
static char b_atn[] = "b_atn:(f)";
static char b_attr[] = "b_attr:(s)";
static char b_cdbl[] = "b_cdbl:(d)";
static char b_chr[] = "b_chr:(i)";
static char b_cint[] = "b_cint:(d)";
static char b_cos[] = "b_cos:(f)";
static char b_csng[] = "b_csng:(d)";
static char b_csrlin[] = "b_csrlin";
static char b_cvd[] = "b_cvd:(s)";
static char b_cvi[] = "b_cvi:(s)";
static char b_cvs[] = "b_cvs:(s)";
static char b_gdate[] = "b_gdate";
static char b_gday[] = "b_gday";
static char b_dskf[] = "b_dskf:(s)";
static char b_eof[] = "b_eof:(#)";
static char b_exp[] = "b_exp:(f)";
static char b_fix[] = "b_fix:(d)";
static char b_hex[] = "b_hex:(i)";
static char b_inkey[] = "b_inkey";
static char b_inp[] = "b_inp:(i)";
static char b_sinput[] = "b_sinput:(i[,#{0}])";
static char b_instr[] = "b_instr:([i{1},]s,s)";
static char b_int[] = "b_int:(d)";
static char b_kinput[] = "b_kinput:(i)";
static char b_left[] = "b_left:(s,i)";
static char b_len[] = "b_len:(s)";
static char b_loc[] = "b_loc:(#)";
static char b_lof[] = "b_lof:(#)";
static char b_log[] = "b_log:(f)";
static char b_lpos[] = "b_lpos:(i)";
static char b_mid[] = "b_mid:(s,i[,i{255}])";
static char b_mkd[] = "b_mkd:(d)";
static char b_mki[] = "b_mki:(i)";
static char b_mks[] = "b_mks:(f)";
static char b_oct[] = "b_oct:(i)";
static char b_peek[] = "b_peek:(i)";
static char b_point[] = "b_point:(i,i)";
static char b_pos[] = "b_pos:(#)";
static char b_right[] = "b_right:(s,i)";
static char b_rnd[] = "b_rnd:[(i{1})]";
static char b_screen[] = "b_screen:(i,i[,i{0}])";
static char b_sgn[] = "b_sgn:(f)";
static char b_sin[] = "b_sin:(f)";
static char b_space[] = "b_space:(i)";
static char b_sqr[] = "b_sqr:(f)";
static char b_str[] = "b_str:(d)";
static char b_string[] = "b_string:(i,x)";
static char b_tan[] = "b_tan:(f)";
static char b_gtime[] = "b_gtime";
static char b_val[] = "b_val:(s)";

/*	table		*/

KEYWORD	keytbl[] = {
	K_ABS,		"ABS",		b_abs,		V_DBL,
	K_ALxxx,	"ALxxx",	b_salxxx,	0x10,
	K_ALxxxF,	"ALxxx$",	b_galxxx,	V_STR,
	K_AND,		"AND",		NULL,		0x00,
	K_ASC,		"ASC",		b_asc,		V_INT,
	K_ATN,		"ATN",		b_atn,		V_SNG,
	K_ATTR,		"ATTR$",	b_attr,		V_STR,
	K_AUTO,		"AUTO",		NULL,		0x80,
	K_BACKLIGHT,	"BACKLIGHT",	p2_backlight,	0x20,
	K_BEEP,		"BEEP",		b_beep,		0x10,
	K_BLOAD,	"BLOAD",	b_bload,	0x10,
	K_BRCD,		"BRCD",		b_brcd,		0x10,
	K_BSAVE,	"BSAVE",	b_bsave,	0x10,
	K_CALL,		"CALL",		p2_call,	0x20,
	K_CDBL,		"CDBL",		b_cdbl,		V_DBL,
	K_CHAIN,	"CHAIN",	NULL,		0x80,
	K_CHR,		"CHR$",		b_chr,		V_STR,
	K_CINT,		"CINT",		b_cint,		V_INT,
	K_CIRCLE,	"CIRCLE",	b_circle,	0x10,
	K_CLEAR,	"CLEAR",	b_clear,	0x10,	
	K_CLOSE,	"CLOSE",	p2_close,	0x20,
	K_CLS,		"CLS",		b_cls,		0x10,
	K_COLOR,	"COLOR",	b_color,	0x10,
	K_COM,		"COM",		b_com,		0x10,
	K_COMMON,	"COMMON",	NULL,		0x80,
	K_CONT,		"CONT",		NULL,		0x80,
	K_COPY,		"COPY",		b_copy,		0x10,
	K_COS,		"COS",		b_cos,		V_SNG,
	K_CSNG,		"CSNG",		b_csng,		V_SNG,
	K_CSRLIN,	"CSRLIN",	b_csrlin,	V_INT,
	K_CVD,		"CVD",		b_cvd,		V_DBL,
	K_CVI,		"CVI",		b_cvi,		V_INT,
	K_CVS,		"CVS",		b_cvs,		V_SNG,
	K_DATA,		"DATA",		NULL,		0x80,
	K_DATE,		"DATE$",	b_gdate,	V_STR,
	K_DAY,		"DAY",		b_gday,		V_INT,
	K_DEF,		"DEF",		p2_def,		0x20,
	K_DEFDBL,	"DEFDBL",	NULL,		0x40,	
	K_DEFINT,	"DEFINT",	NULL,		0x40,
	K_DEFSNG,	"DEFSNG",	NULL,		0x40,
	K_DEFSTR,	"DEFSTR",	NULL,		0x40,
	K_DELETE,	"DELETE",	NULL,		0x80,
	K_DIM,		"DIM",		NULL,		0x40,
	K_DSKF,		"DSKF",		b_dskf,		V_INT,
	K_EDIT,		"EDIT",		NULL,		0x80,
	K_ELSE,		"ELSE",		p2_else,	0x20,
	K_END,		"END",		b_end,		0x10,
	K_EOF,		"EOF",		b_eof,		V_INT,
	K_EQV,		"EQV",		NULL,		0x00,
	K_ERASE,	"ERASE",	NULL,		0x80,
	K_ERL,		"ERL",		NULL,		0x00,
	K_ERR,		"ERR",		NULL,		0x00,
	K_ERROR,	"ERROR",	b_error,	0x10,
	K_EXP,		"EXP",		b_exp,		V_SNG,
	K_EXTD,		"EXTD",		NULL,		0x80,
	K_FIELD,	"FIELD",	p2_field,	0x20,
	K_FILES,	"FILES",	NULL,		0x80,
	K_FIX,		"FIX",		b_fix,		V_DBL,
	K_FN,		"FN",		NULL,		0x00,
	K_FONT,		"FONT",		p2_kanji,	0x20,
	K_FOR,		"FOR",		p2_for,		0x20,
	K_FRE,		"FRE",		NULL,		0x80,
	K_GET,		"GET",		p2_get,		0x20,
	K_GOSUB,	"GOSUB",	p2_gosub,	0x20,
	K_GOTO,		"GOTO",		p2_goto,	0x20,
	K_HEX,		"HEX$",		b_hex,		V_STR,
	K_IF,		"IF",		p2_if,		0x20,
	K_IMP,		"IMP",		NULL,		0x00,
	K_INKEY,	"INKEY$",	b_inkey,	V_STR,
	K_INP,		"INP",		b_inp,		V_INT,
	K_INPUT,	"INPUT",	p2_input,	0x20,
	K_INPUT,	"INPUT$",	b_sinput,	V_STR,
	K_INSTR,	"INSTR",	b_instr,	V_INT,
	K_INT,		"INT",		b_int,		V_DBL,
	K_KANJI,	"KANJI",	p2_kanji,	0x20,
	K_KEY,		"KEY",		p2_key,		0x20,
	K_KILL,		"KILL",		b_kill,		0x10,
	K_KINPUT,	"KINPUT$",	b_kinput,	V_STR,
	K_LEFT,		"LEFT$",	b_left,		V_STR,
	K_LEN,		"LEN",		b_len,		V_INT,
	K_LET,		"LET",		p2_let,		0x20,
	K_LFONT,	"LFONT",	b_lkanji,	0x10,
	K_LINE,		"LINE",		p2_line,	0x20,
	K_LIST,		"LIST",		NULL,		0x80,
	K_LKANJI,	"LKANJI",	b_lkanji,	0x10,
	K_LLIST,	"LLIST",	NULL,		0x80,
	K_LOAD,		"LOAD",		NULL,		0x80,
	K_LOC,		"LOC",		b_loc,		V_INT,
	K_LOCATE,	"LOCATE",	b_locate,	0x10,
	K_LOF,		"LOF",		b_lof,		V_INT,
	K_LOG,		"LOG",		b_log,		V_SNG,
	K_LOGIN,	"LOGIN",	NULL,		0x80,
	K_LPOS,		"LPOS",		b_lpos,		V_INT,
	K_LPRINT,	"LPRINT",	p2_lprint,	0x20,
	K_LSET,		"LSET",		b_lset,		0x10,
	K_MENU,		"MENU",		NULL,		0x80,
	K_MERGE,	"MERGE",	NULL,		0x80,
	K_MID,		"MID$",		b_mid,		V_STR,
	K_MKD,		"MKD$",		b_mkd,		V_STR,
	K_MKI,		"MKI$",		b_mki,		V_STR,
	K_MKS,		"MKS$",		b_mks,		V_STR,
	K_MOD,		"MOD",		NULL,		0x00,
	K_MOTOR,	"MOTOR",	NULL,		0x80,
	K_MOUNT,	"MOUNT",	NULL,		0x80,
	K_NAME,		"NAME",		p2_name,	0x20,
	K_NEW,		"NEW",		NULL,		0x80,
	K_NEXT,		"NEXT",		p2_next,	0x20,
	K_NOT,		"NOT",		NULL,		0x00,
	K_OCT,		"OCT$",		b_oct,		V_STR,
	K_OFF,		"OFF",		NULL,		0x00,
	K_ON,		"ON",		p2_on,		0x20,
	K_OPEN,		"OPEN",		p2_open,	0x20,
	K_OPTION,	"OPTION",	p2_option,	0x20,
	K_OR,		"OR",		NULL,		0x00,
	K_OUT,		"OUT",		b_out,		0x10,
	K_PAINT,	"PAINT",	b_paint,	0x10,
	K_PCOPY,	"PCOPY",	NULL,		0x80,
	K_PEEK,		"PEEK",		b_peek,		V_INT,
	K_POINT,	"POINT",	b_point,	V_INT,
	K_POKE,		"POKE",		b_poke,		0x10,	
	K_POS,		"POS",		b_pos,		V_INT,
	K_POWER,	"POWER",	p2_power,	0x20,
	K_PRESET,	"PRESET",	b_preset,	0x10,
	K_PRINT,	"PRINT",	p2_print,	0x20,
	K_PSET,		"PSET",		b_pset,		0x10,
	K_PUT,		"PUT",		p2_put,		0x20,
	K_RANDOMIZE,	"RANDOMIZE",	b_randomize,	0x10,
	K_READ,		"READ",		p2_read,	0x20,
	K_REM,		"REM",		NULL,		0x00,
	K_REMOVE,	"REMOVE",	NULL,		0x80,
	K_RENUM,	"RENUM",	NULL,		0x80,
	K_RESET,	"RESET",	b_reset,	0x10,
	K_RESTORE,	"RESTORE",	p2_restore,	0x20,
	K_RESUME,	"RESUME",	p2_resume,	0x20,
	K_RETURN,	"RETURN",	p2_return,	0x20,
	K_RIGHT,	"RIGHT$",	b_right,	V_STR,
	K_RND,		"RND",		b_rnd,		V_SNG,
	K_RSET,		"RSET",		b_rset,		0x10,
	K_RUN,		"RUN",		b_run,		0x10,
	K_SAVE,		"SAVE",		NULL,		0x80,
	K_SCREEN,	"SCREEN",	b_screen,	V_INT,
	K_SET,		"SET",		b_set,		0x10,
	K_SGN,		"SGN",		b_sgn,		V_INT,
	K_SIN,		"SIN",		b_sin,		V_SNG,
	K_SOUND,	"SOUND",	b_sound,	0x10,
	K_SPACE,	"SPACE$",	b_space,	V_STR,
	K_SPC,		"SPC",		NULL,		0x00,
	K_SQR,		"SQR",		b_sqr,		V_SNG,
	K_STAT,		"STAT",		NULL,		0x80,
	K_STEP,		"STEP",		NULL,		0x00,
	K_STOP,		"STOP",		p2_stop,	0x20,
	K_STR,		"STR$",		b_str,		V_STR,
	K_STRING,	"STRING$",	b_string,	V_STR,
	K_SWAP,		"SWAP",		p2_swap,	0x20,
	K_SYSTEM,	"SYSTEM",	b_system,	0x10,
	K_TAB,		"TAB",		NULL,		0x00,
	K_TAN,		"TAN",		b_tan,		V_SNG,
	K_TAPCNT,	"TAPCNT",	NULL,		0x80,
	K_THEN,		"THEN",		NULL,		0x00,
	K_TIME,		"TIME$",	b_gtime,	V_STR,
	K_TITLE,	"TITLE",	NULL,		0x80,
	K_TO,		"TO",		NULL,		0x00,
	K_TROFF,	"TROFF",	NULL,		0x80,
	K_TRON,		"TRON",		NULL,		0x80,
	K_USING,	"USING",	NULL,		0x00,
	K_USR,		"USR",		NULL,		0x00,
	K_USR,		"USR0",		NULL,		0x00,
	K_USR,		"USR1",		NULL,		0x00,
	K_USR,		"USR2",		NULL,		0x00,
	K_USR,		"USR3",		NULL,		0x00,
	K_USR,		"USR4",		NULL,		0x00,
	K_USR,		"USR5",		NULL,		0x00,
	K_USR,		"USR6",		NULL,		0x00,
	K_USR,		"USR7",		NULL,		0x00,
	K_USR,		"USR8",		NULL,		0x00,
	K_USR,		"USR9",		NULL,		0x00,
	K_VAL,		"VAL",		b_val,		V_DBL,
	K_VARPTR,	"VARPTR",	NULL,		0x00,
	K_VIEW,		"VIEW",		b_view,		0x10,
	K_WAIT,		"WAIT",		b_wait,		0x10,
	K_WEND,		"WEND",		p2_wend,	0x20,
	K_WHILE,	"WHILE",	p2_while,	0x20,
	K_WIDTH,	"WIDTH",	p2_width,	0x20,
	K_WINDOW,	"WINDOW",	b_window,	0x10,
	K_WRITE,	"WRITE",	p2_write,	0x20,
	K_XOR,		"XOR",		NULL,		0x00
};

int	keyn = sizeof(keytbl) / sizeof(KEYWORD);
