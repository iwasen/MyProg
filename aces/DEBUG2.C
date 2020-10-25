/************************************************************************
 *									*
 *		‚`‚b‚d‚r@‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€			*
 *									*
 *		–¼Ì		: ƒfƒoƒbƒO—p“ü—ÍM†ˆ—		*
 *		ƒtƒ@ƒCƒ‹–¼	: debug2.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=debug2
#pragma	SECTION CONST=debug2
#pragma	SECTION DATA=debug2

#include "sg_cpu.h"

#define	S_SET	1
#define	S_UP	2
#define	S_UPC	3
#define	S_DOWN	4
#define	S_DOWNC	5
#define	S_WAIT	6
#define	S_LOOP	7
#define	S_STOP	8

#define	s_set(data)		S_SET,data
#define	s_up(limit,step,color)	S_UP,limit,step,color
#define	s_upc(limit,step,color)	S_UPC,limit,step,color
#define	s_down(limit,step,color) S_DOWN,limit,step,color
#define	s_downc(limit,step,color) S_DOWNC,limit,step,color
#define	s_wait(count)		S_WAIT,count
#define	s_loop(no)		S_LOOP,no
#define	s_stop()		S_STOP

#define	w_x(x,size)		((x<<5)+size)
#define	w_y(y,color)		((y<<5)+color)
#define	w_ch(ch1,ch2,ch3,ch4)	((ch1<<8)+ch2),((ch3<<8)+ch4)

#define	SN(_blk, _word)	((_blk-1) * 32 + _word - 1)

typedef	struct	{
	short	signal_no;
	short	*datap;
} TEST_DATA;

static	short	data21_01[] = {	/* FCR MODE FIXED SYMBOL 1 */
	s_set(0xefa8),
	s_stop()
};
static	short	data21_02[] = {	/* FCR MODE FIXED SYMBOL 2 */
	s_set(0xe380),
	s_stop()
};
static	short	data21_03[] = {	/* FCR MODE FIXED SYMBOL 3 */
	s_set(0x71e0),
	s_stop()
};
static	short	data21_04[] = {	/* SMS MODE FIXED SYMBOL 1 */
	s_set(0xf800),
	s_wait(100),
	s_set(0xf800),
	s_wait(100),
	s_set(0x07c0),
	s_wait(100),
	s_set(0x07c0),
	s_wait(100),
	s_set(0x003e),
	s_wait(100),
	s_set(0x003e),
	s_wait(100),
	s_set(0x003e),
	s_wait(100),
	s_loop(0)
};
static	short	data21_05[] = {	/* SMS MODE FIXED SYMBOL 2 */
	s_set(0x0000),
	s_wait(100),
	s_set(0xe000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0xb800),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_loop(0)
};
static	short	data21_06[] = {	/* SMS MODE FIXED SYMBOL 3 */
	s_set(0x0000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0x0000),
	s_wait(100),
	s_set(0xc000),
	s_wait(100),
	s_set(0x04c0),
	s_wait(100),
	s_loop(0)
};
static	short	data21_07[] = {	/* EW MODE FIXED SYMBOL 1 */
	s_set(0x72e0),
	s_wait(200),
	s_set(0xb2e0),
	s_wait(200),
	s_loop(0)
};
static	short	data21_08[] = {	/* EW MODE DOTS */
	s_set(0x9402),
	s_wait(100),
	s_down(0x8800, 0x20, 2),
	s_wait(100),
	s_up(0x9400, 0x20, 2),
	s_loop(2)
};
static	short	data21_09[] = {	/* FCR MDOE FIXED SYMBOL 4 */
	s_set(0x9000),
	s_stop()
};
static	short	data26_01[] = {	/* ARTIFICIAL HORIZON LINE, PITCH */
	s_upc(1000, 0x20, 0),
	s_downc(2000, 0x20, 0),
	s_loop(0)
};
static	short	data26_02[] = {	/* ARTIFICIAL HORIZON LINE, ROLL */
	s_upc(1500, 0x20, 0),
	s_downc(1200, 0x20, 0),
	s_loop(0)
};
static	short	data26_03[] = {	/* ARTIFICIAL HORIZON LINE, COLOR */
	s_set(0xc330),
	s_stop()
};
static	short	data26_04[] = {	/* ANTENNA AZ SCALE, X-POSITION */
	s_up(0x7fe0, 0x20, 12),
	s_down(0x8000, 0x20, 12),
	s_loop(0)
};
static	short	data26_05[] = {	/* ANTENNA EL SCALE, Y-POSITION */
	s_up(0x7fe0, 0x20, 8),
	s_down(0x8000, 0x20, 8),
	s_loop(0)
};
static	short	data26_06[] = {	/* ASE CIRCLE, X-POSITION */
	s_set(0x1000),
	s_wait(200),
	s_up(0x2300, 0x20, 0),
	s_down(0xec00, 0x20, 0),
	s_up(0x1000, 0x20, 0),
	s_loop(2)
};
static	short	data26_07[] = {	/* ASE CIRCLE, Y-POSITION */
	s_set(0x1000),
	s_wait(200),
	s_up(0x2300, 0x40, 0),
	s_down(0xec00, 0x20, 0),
	s_up(0x1000, 0x40, 0),
	s_loop(2)
};
static	short	data26_08[] = {	/* ASE CIRCLE, RADIUS */
	s_set(0x1005),
	s_wait(200),
	s_down(0x800, 0x40, 5),
	s_wait(200),
	s_up(0x1000, 0x40, 5),
	s_loop(2)
};
static	short	data26_09[] = {	/* STEERING DOT, X-POSITION */
	s_set(0x2000),
	s_up(0x7fe0, 0x20, 0),
	s_down(0x8030, 0x30, 0),
	s_loop(2)
};
static	short	data26_10[] = {	/* STEERING DOT, Y-POSITION */
	s_set(0xf00c),
	s_down(0x8040, 0x40, 0xc),
	s_up(0x7fe0, 0x20, 0xc),
	s_loop(2)
};
static	short	data26_11[] = {	/* ASM CIRCLE, X-POSITION */
	s_set(0x1000),
	s_up(0x7fd0, 0x30, 0),
	s_down(0x8040, 0x40, 0),
	s_loop(2)
};
static	short	data26_12[] = {	/* ASM CIRCLE, Y-POSITION */
	s_set(0xf804),
	s_up(0x7fd0, 0x30, 4),
	s_down(0x8040, 0x40, 4),
	s_loop(2)
};
static	short	data26_13[] = {	/* R MAX, X-POSITION */
	s_set(0x3a80),
	s_stop()
};
static	short	data26_14[] = {	/* R MAX, Y-POSITION */
	s_set(0x2001),
	s_wait(300),
	s_up(0x3000, 0x20, 1),
	s_wait(300),
	s_down(0x2000, 0x20, 1),
	s_loop(2)
};
static	short	data26_15[] = {	/* R MAX2, X-POSITION */
	s_set(0x3a80),
	s_stop()
};
static	short	data26_16[] = {	/* R MAX2, Y-POSITION */
	s_set(0x803),
	s_wait(250),
	s_up(0x1000, 0x20, 3),
	s_wait(250),
	s_down(0x0000, 0x20, 3),
	s_loop(2)
};
static	short	data26_17[] = {	/* R MIN, X-POSITION */
	s_set(0x3a80),
	s_stop()
};
static	short	data26_18[] = {	/* R MIN, Y-POSITION */
	s_set(0xf002),
	s_wait(100),
	s_down(0xe000, 0x20, 2),
	s_wait(100),
	s_up(0xf000, 0x20, 2),
	s_loop(2)
};
static	short	data26_19[] = {	/* ACQUISITION SYMBOL, X-POSITION */
	s_set(0xf000),
	s_down(0x8040, 0x40, 0),
	s_up(0x7fd0, 0x30, 0),
	s_loop(2)
};
static	short	data26_20[] = {	/* ACQUISITION SYMBOL, Y-POSITION */
	s_set(0x2009),
	s_up(0x7fe0, 0x20, 9),
	s_down(0x8030, 0x30, 9),
	s_loop(2)
};
static	short	data26_21[] = {	/* A/C TO TARGET LINE, X-POSITION */
	s_set(0xf000),
	s_stop()
};
static	short	data26_22[] = {	/* A/C TO TARGET LINE, Y-POSITION */
	s_set(0xd000),
	s_stop()
};
static	short	data26_23[] = {	/* A/C TO TARGET LINE, LENGTH */
	s_set(0x1000),
	s_up(0x2000, 0x20, 0),
	s_down(0x500, 0x20, 0),
	s_loop(2)
};
static	short	data26_24[] = {	/* A/C TO TARGET LINE, RADIUS  */
	s_set(0x4001),
	s_up(0x7f00, 0x100, 1),
	s_down(0x8100, 0x100, 1),
	s_loop(2)
};
static	short	data27_01[] = {	/* X-Y CURSOR, X-POSITION */
	s_set(0xf000),
	s_wait(400),
	s_up(0x2000, 0x40, 0),
	s_wait(300),
	s_down(0xe000, 0x20, 0),
	s_wait(400),
	s_up(0xf000, 0x40, 0),
	s_loop(2)
};
static	short	data27_02[] = {	/* X-Y CURSOR, Y-POSITION */
	s_set(0x2009),
	s_wait(400),
	s_down(0xe000, 0x40, 9),
	s_wait(300),
	s_up(0x3000, 0x20, 9),
	s_wait(400),
	s_down(0x2000, 0x40, 9),
	s_loop(2),
};
static	short	data27_03[] = {	/* A/C SYMBOL/ALT LINE SYMBOL X-POS */
	s_set(0xe000),
	s_down(0x8030, 0x30, 0),
	s_up(0x7fe0, 0x20, 0),
	s_loop(2)
};
static	short	data27_04[] = {	/* A/C SYMBOL/ALT LINE SYMBOL Y-POS */
	s_set(0xf801),
	s_down(0x8040, 0x40, 1),
	s_up(0x7fe0, 0x20, 1),
	s_loop(2)
};
static	short	data27_05[] = {	/* ANTENNA POSITION, X-POSITION */
	s_set(0x3000),
	s_down(0x8040, 0x40, 0),
	s_up(0x7fd0, 0x30, 0),
	s_loop(2)
};
static	short	data27_06[] = {	/* ANTENNA POSITION, Y-POSITION */
	s_set(0x3001),
	s_down(0x8040, 0x40, 1),
	s_up(0x7fe0, 0x20, 1),
	s_loop(2)
};
static	short	data27_07[] = {	/* REACQUSITION SYMBOL, X-POSITION */
	s_set(0xd000),
	s_down(0x8030, 0x30, 0),
	s_up(0x7fe0, 0x20, 0),
	s_loop(2)
};
static	short	data27_08[] = {	/* REACQUSITION SYMBOL, Y-POSITION */
	s_set(0x0001),
	s_up(0x7fe0, 0x20, 1),
	s_down(0x8040, 0x40, 1),
	s_loop(2)
};
static	short	data27_09[] = {	/* MI CUE-ECCM */
	s_set(0x8005),
	s_up(0x7fe0, 0x20, 5),
	s_down(0x8040, 0x40, 5),
	s_loop(2)
};
static	short	data27_10[] = {	/* AOJ STROBE-ECCM */
	s_set(0xe007),
	s_up(0x7fe0, 0x20, 7),
	s_down(0x8040, 0x40, 7),
	s_loop(2)
};
static	short	data28_02[] = {	/* HSI SYMBOL STATUS */
	s_set(0xfff8),
	s_stop()
};
static	short	data28_03[] = {	/* HSI SYMBOL-COLOR CODE 1 */
	s_set(0xf88f),
	s_stop()
};
static	short	data28_04[] = {	/* HSI SYMBOL-COLOR CODE 2 */
	s_set(0x7f83),
	s_stop()
};
static	short	data28_05[] = {	/* HSI SYMBOL-COLOR CODE 3 */
	s_set(0x6658),
	s_stop()
};
static	short	data28_06[] = {	/* HSI SYMBOL-COLOR CODE 4 */
	s_set(0x2cc8),
	s_stop()
};
static	short	data28_07[] = {	/* HSI SYMBOL-COLOR CODE 5 */
	s_set(0xc381),
	s_stop()
};
static	short	data28_08[] = {	/* HSI SYMBOL-COLOR CODE 6 */
	s_set(0xf23f),
	s_stop()
};
static	short	data28_09[] = {	/* HSI SYMBOL-COLOR CODE 7 */
	s_set(0xf900),
	s_stop()
};
static	short	data28_10[] = {	/* HSI-STPT NUMBER */
	s_set(0x2000),
	s_wait(300),
	s_set(0xd800),
	s_wait(300),
	s_set(0xf800),
	s_wait(300),
	s_loop(0)
};
static	short	data28_11[] = {	/* HSI-STPT BEARING */
	s_set(0xf000),
	s_upc(1000, 0x10, 0),
	s_downc(555, 0x20, 0),
	s_loop(2)
};
static	short	data28_12[] = {	/* HSI-PRESENT MAGNETIC HEADING */
	s_set(0x0b64),
	s_upc(1200, 0x08, 0),
	s_downc(700, 0x08, 0),
	s_loop(2)
};
static	short	data28_13[] = {	/* HSI-GROUND TRACK */
	s_set(0x1700),
	s_upc(500, 0x28, 0),
	s_downc(700, 0x28, 0),
	s_loop(2)
};
static	short	data28_14[] = {	/* HSI-COMMAND COURSE */
	s_set(0x1b00),
	s_downc(700, 0x20, 0),
	s_upc(300, 0x18, 0),
	s_loop(2)
};
static	short	data28_15[] = {	/* HSI-DESIGNATED POINT BEARING */
	s_set(0x4000),
	s_downc(800, 0x40, 0),
	s_upc(300, 0x30, 0),
	s_loop(2)
};
static	short	data28_16[] = {	/* HSI-GLIDE SLOPE */
	s_set(0x0000),
	s_up(0x7f00, 0x80, 0),
	s_down(0x8000, 0x80, 0),
	s_loop(2)
};
static	short	data28_17[] = {	/* HSI-TACAN BEARING POINTER */
	s_set(0x6000),
	s_downc(1100, 0x30, 0),
	s_upc(300, 0x30, 0),
	s_loop(2)
};
static	short	data28_18[] = {	/* HSI-LOCALIZER */
	s_set(0x0000),
	s_down(0x8000, 0x100, 0),
	s_up(0x7f00, 0x100, 0),
	s_loop(2)
};
static	short	data28_19[] = {	/* HSI-DEVIATION BAR */
	s_set(0x0000),
	s_up(0x7f00, 0x100, 0),
	s_down(0x8000, 0x100, 0),
	s_loop(2)
};
static	short	data28_20[] = {	/* HSI-HEADING POINTER */
	s_set(0x0000),
	s_downc(1200, 0x28, 0),
	s_upc(300, 0x30, 0),
	s_loop(2)
};
static	short	data28_21[] = {	/* HSI-TRIM 1 */
	s_up(0x7f7f, 0x101, 0),
	s_down(0x8081, 0x101, 0),
	s_loop(0)
};
static	short	data28_22[] = {	/* HSI-TRIM 2 */
	s_up(0x7f00, 0x100, 0),
	s_down(0x8000, 0x100, 0),
	s_loop(0)
};
static	short	data29_01[] = {	/* ADI MODE-SYMBOL STATUS */
	s_set(0xfffc),
	s_stop()
};
static	short	data29_02[] = {	/* ADI SYMBOL-COLOR CODE 1 */
	s_set(0x8f2f),
	s_stop()
};
static	short	data29_03[] = {	/* ADI SYMBOL-COLOR CODE 2 */
	s_set(0xff00),
	s_stop()
};
static	short	data29_04[] = {	/* ADI SYMBOL-COLOR CODE 3 */
	s_set(0x538f),
	s_stop()
};
static	short	data29_05[] = {	/* ADI SYMBOL-COLOR CODE 4 */
	s_set(0x86c8),
	s_stop()
};
static	short	data29_06[] = {	/* ADI SYMBOL-COLOR CODE 5 */
	s_set(0x2fc2),
	s_stop()
};
static	short	data29_07[] = {	/* ADI SYMBOL-COLOR CODE 6 */
	s_set(0x38fc),
	s_stop()
};
static	short	data29_08[] = {	/* ADI SYMBOL-COLOR CODE 7 */
	s_set(0xc8f8),
	s_stop()
};
static	short	data29_09[] = {	/* ADI SYMBOL-COLOR CODE 8 */
	s_set(0x8ff2),
	s_stop()
};
static	short	data29_10[] = {	/* ADI-PRESENT MAGNETIC HEADING */
	s_set(0xd99c),
	s_upc(1000, 0x10, 0),
	s_downc(2000, 0x10, 0),
	s_loop(2)
};
static	short	data29_11[] = {	/* ADI-COMMAND COURSE */
	s_set(0xe7d0),
	s_upc(1000, 0x20, 0),
	s_downc(1000, 0x20, 0),
	s_loop(2)
};
static	short	data29_12[] = {	/* ADI-ROLL ANGLE */
	s_upc(1300, 0x20, 0),
	s_downc(2000, 0x30, 0),
	s_loop(0)
};
static	short	data29_13[] = {	/* ADI-PITCH ANGLE */
	s_upc(2000, 0x18, 0),
	s_downc(1500, 0x20, 0),
	s_loop(0)
};
static	short	data29_14[] = {	/* ADI-FLIGHT PATH MARKER-AZIMUTH */
	s_up(0x6000, 0x20, 0),
	s_down(0xa000, 0x18, 0),
	s_loop(0)
};
static	short	data29_15[] = {	/* ADI-FLIGHT PATH MARKER-ELEVATION */
	s_up(0x4000, 0x10, 0),
	s_down(0xc000, 0x20, 0),
	s_loop(0)
};
static	short	data29_16[] = {	/* ADI-PRESENT ALTITUDE */
	s_up(0x7fe0, 0x8, 0),
	s_down(0x8000, 0x8, 0),
	s_loop(0)
};
static	short	data29_17[] = {	/* ADI-GLIDE SLOPE */
	s_up(0x7f00, 0x80, 0),
	s_down(0x8000, 0x80, 0),
	s_loop(0)
};
static	short	data29_18[] = {	/* ADI-VERTICAL VELOCITY */
	s_up(0x7fe0, 0x20, 0),
	s_down(0x8000, 0x20, 0),
	s_loop(0)
};
static	short	data29_19[] = {	/* ADI-LOCATOR */
	s_up(0x7f00, 0x80, 0),
	s_down(0x8000, 0x80, 0),
	s_loop(0)
};
static	short	data29_20[] = {	/* ADI-AOA */
	s_up(0x7fe0, 0x20, 0),
	s_down(0x8000, 0x20, 0),
	s_loop(0)
};
static	short	data29_21[] = {	/* ADI-PRESENT SPEED */
	s_up(0x7fe0, 0x20, 0),
	s_down(0x8000, 0x20, 0),
	s_loop(0)
};
static	short	data29_22[] = {	/* ADI-TRIM 1 */
	s_up(0x7f7f, 0x101, 0),
	s_down(0x8081, 0x101, 0),
	s_loop(0)
};
static	short	data29_23[] = {	/* ADI-TRIM 2 */
	s_up(0x7f00, 0x100, 0),
	s_down(0x8000, 0x100, 0),
	s_loop(0)
};

static	TEST_DATA	test_data_tbl[] = {
	{SN(21,1), data21_01},
	{SN(21,2), data21_02},
	{SN(21,3), data21_03},
	{SN(21,4), data21_04},
	{SN(21,5), data21_05},
	{SN(21,6), data21_06},
	{SN(21,7), data21_07},
	{SN(21,8), data21_08},
	{SN(21,9), data21_09},
	{SN(26,1), data26_01},
	{SN(26,2), data26_02},
	{SN(26,3), data26_03},
	{SN(26,4), data26_04},
	{SN(26,5), data26_05},
	{SN(26,6), data26_06},
	{SN(26,7), data26_07},
	{SN(26,8), data26_08},
	{SN(26,9), data26_09},
	{SN(26,10), data26_10},
	{SN(26,11), data26_11},
	{SN(26,12), data26_12},
	{SN(26,13), data26_13},
	{SN(26,14), data26_14},
	{SN(26,15), data26_15},
	{SN(26,16), data26_16},
	{SN(26,17), data26_17},
	{SN(26,18), data26_18},
	{SN(26,19), data26_19},
	{SN(26,20), data26_20},
	{SN(26,21), data26_21},
	{SN(26,22), data26_22},
	{SN(26,23), data26_23},
	{SN(26,24), data26_24},
	{SN(27,1), data27_01},
	{SN(27,2), data27_02},
	{SN(27,3), data27_03},
	{SN(27,4), data27_04},
	{SN(27,5), data27_05},
	{SN(27,6), data27_06},
	{SN(27,7), data27_07},
	{SN(27,8), data27_08},
	{SN(27,9), data27_09},
	{SN(27,10), data27_10},
	{SN(28,2), data28_02},
	{SN(28,3), data28_03},
	{SN(28,4), data28_04},
	{SN(28,5), data28_05},
	{SN(28,6), data28_06},
	{SN(28,7), data28_07},
	{SN(28,8), data28_08},
	{SN(28,9), data28_09},
	{SN(28,10), data28_10},
	{SN(28,11), data28_11},
	{SN(28,12), data28_12},
	{SN(28,13), data28_13},
	{SN(28,14), data28_14},
	{SN(28,15), data28_15},
	{SN(28,16), data28_16},
	{SN(28,17), data28_17},
	{SN(28,18), data28_18},
	{SN(28,19), data28_19},
	{SN(28,20), data28_20},
	{SN(28,21), data28_21},
	{SN(28,22), data28_22},
	{SN(29,1), data29_01},
	{SN(29,2), data29_02},
	{SN(29,3), data29_03},
	{SN(29,4), data29_04},
	{SN(29,5), data29_05},
	{SN(29,6), data29_06},
	{SN(29,7), data29_07},
	{SN(29,8), data29_08},
	{SN(29,9), data29_09},
	{SN(29,10), data29_10},
	{SN(29,11), data29_11},
	{SN(29,12), data29_12},
	{SN(29,13), data29_13},
	{SN(29,14), data29_14},
	{SN(29,15), data29_15},
	{SN(29,16), data29_16},
	{SN(29,17), data29_17},
	{SN(29,18), data29_18},
	{SN(29,19), data29_19},
	{SN(29,20), data29_20},
	{SN(29,21), data29_21},
	{SN(29,22), data29_22},
	{SN(29,23), data29_23},
	{-1, NULL}
};

static	MFD_WINDOW	window_data_fcr[] = {
	{w_x(-387,1),  w_y(500,8),   w_ch('A','U','T','O')},
	{w_x(225,1),   w_y(500,8),   w_ch('H','S','T','1')},
	{w_x(-387,1),  w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-225,1),  w_y(-530,8),  w_ch('F','C','R',' ')},
	{w_x(99,1),    w_y(-530,8),  w_ch('E','W',' ',' ')},
	{w_x(261,1),   w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-530,2),  w_y(265,8),   w_ch('f',' ',' ',' ')},
	{w_x(-535,1),  w_y(225,8),   w_ch('8','0',' ',' ')},
	{w_x(-530,2),  w_y(175,8),   w_ch('g',' ',' ',' ')},
	{w_x(-530,1),  w_y(0,8),     w_ch('A',' ',' ',' ')},
	{w_x(-530,1),  w_y(-40,8),   w_ch('6',' ',' ',' ')},
	{w_x(-530,1),  w_y(-162,8),  w_ch('4',' ',' ',' ')},
	{w_x(-530,1),  w_y(-202,8),  w_ch('B',' ',' ',' ')},
	{w_x(-530,1),  w_y(-324,8),  w_ch('P',' ',' ',' ')},
	{w_x(-530,1),  w_y(-364,8),  w_ch('N',' ',' ',' ')},
	{w_x(500,1),   w_y(184,8),   w_ch('A',' ',' ',' ')},
	{w_x(500,1),   w_y(144,8),   w_ch('L',' ',' ',' ')},
	{w_x(500,1),   w_y(104,8),   w_ch('T',' ',' ',' ')},
	{w_x(-300,1),  w_y(410,15),  w_ch('2','0',' ',' ')},
	{w_x(-220,1),  w_y(410,15),  w_ch('A','I','R',' ')},
	{w_x(-50,1),   w_y(410,15),  w_ch('H',' ',' ',' ')},
	{w_x(-11,1),   w_y(410,15),  w_ch('-','0','0',' ')},
	{w_x(130,1),   w_y(410,15),  w_ch('1','6','0',' ')},
	{w_x(232,1),   w_y(410,15),  w_ch('-','0','6',' ')},
	{w_x(-350,1),  w_y(-380,15), w_ch('1','2',' ',' ')},
	{w_x(-100,1),  w_y(-380,15), w_ch('I','N',' ',' ')},
	{w_x(0,1),     w_y(-380,15), w_ch('R','N','G',' ')},
	{w_x(300,1),   w_y(-300,15), w_ch('2','0',' ',' ')},
	{w_x(300,1),   w_y(-380,15), w_ch('5','9',' ',' ')},
	{0, 0, 0, 0}
};
static	MFD_WINDOW	window_data_sms[] = {
	{w_x(-225,1),  w_y(500,8),   w_ch('F','6','1','0')},
	{w_x(99,1),    w_y(500,8),   w_ch('F','6','1','0')},
	{w_x(-387,1),  w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-225,1),  w_y(-530,8),  w_ch('S','M','S',' ')},
	{w_x(-63,1),   w_y(-530,8),  w_ch('S','-','J',' ')},
	{w_x(99,1),    w_y(-530,8),  w_ch('E','W',' ',' ')},
	{w_x(261,1),   w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-530,1),  w_y(324,8),   w_ch('L','O','W',' ')},
	{w_x(-530,1),  w_y(284,8),   w_ch('4','2','0',' ')},
	{w_x(400,1),   w_y(162,8),   w_ch('S','C','A','N')},
	{w_x(400,1),   w_y(0,8),     w_ch('C','O','O','L')},
	{w_x(400,1),   w_y(-324,8),  w_ch(' ','M','S','L')},
	{w_x(400,1),   w_y(-364,8),  w_ch('S','T','E','P')},
	{0, 0, 0, 0}
};
static	MFD_WINDOW	window_data_ew[] = {
	{w_x(-387,1),  w_y(500,8),   w_ch('E','C','M',' ')},
	{w_x(-63,1),   w_y(500,8),   w_ch('N','O','R','M')},
	{w_x(-530,1),  w_y(324,8),   w_ch('A','U','T','O')},
	{w_x(-387,1),  w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-225,1),  w_y(-530,8),  w_ch('H','S','I',' ')},
	{w_x(99,1),    w_y(-530,8),  w_ch('E','W',' ',' ')},
	{w_x(261,1),   w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(300,1),   w_y(480,15),  w_ch('E','S','M',' ')},
	{w_x(435,1),   w_y(480,15),  w_ch('O','N',' ',' ')},
	{w_x(300,1),   w_y(430,15),  w_ch('E','C','M',' ')},
	{w_x(435,1),   w_y(430,15),  w_ch('O','N',' ',' ')},
	{w_x(300,1),   w_y(380,15),  w_ch('C','M','D',' ')},
	{w_x(435,1),   w_y(380,15),  w_ch('O','N',' ',' ')},
	{w_x(-500,1),  w_y(-470,15), w_ch('C','H',' ',' ')},
	{w_x(-398,1),  w_y(-470,15), w_ch('6','0',' ',' ')},
	{w_x(350,1),  w_y(-470,15), w_ch('F','L',' ',' ')},
	{w_x(452,1),  w_y(-470,15), w_ch('6','0',' ',' ')},
	{0, 0, 0, 0}
};
static	MFD_WINDOW	window_data_hsi[] = {
	{w_x(-387,1),  w_y(500,8),   w_ch('N','M','L',' ')},
	{w_x(-63,1),   w_y(500,8),   w_ch('I','L','S','/')},
	{w_x(72,1),    w_y(500,8),   w_ch('T',' ',' ',' ')},
	{w_x(-387,1),  w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-225,1),  w_y(-530,8),  w_ch('H','S','I',' ')},
	{w_x(99,1),    w_y(-530,8),  w_ch('N','O','T','E')},
	{w_x(-520,0),  w_y(510,12),  w_ch('T','C','N',' ')},
	{w_x(-520,0),  w_y(470,12),  w_ch('1','1','8','t')},
	{w_x(-430,0),  w_y(470,12),  w_ch('/',' ',' ',' ')},
	{w_x(-407,0),  w_y(470,12),  w_ch('1','8','.','5')},
	{w_x(350,0),   w_y(510,12),  w_ch('S','T','P','T')},
	{w_x(450,0),   w_y(510,12),  w_ch('1','2',' ',' ')},
	{w_x(320,0),   w_y(470,12),  w_ch('3','4','3','t')},
	{w_x(410,0),   w_y(470,12),  w_ch('/',' ',' ',' ')},
	{w_x(433,0),   w_y(470,12),  w_ch('2','0','.','0')},
	{w_x(410,0),   w_y(420,15),  w_ch('1','0',':',' ')},
	{w_x(478,0),   w_y(420,15),  w_ch('1','5',' ',' ')},
	{w_x(-500,1),  w_y(-400,12), w_ch('H','D','G',' ')},
	{w_x(-500,1),  w_y(-450,12), w_ch('3','3','2','t')},
	{w_x(370,1),   w_y(-400,12), w_ch('C','R','S',' ')},
	{w_x(370,1),   w_y(-450,12), w_ch('0','3','7','t')},
	{0, 0, 0, 0}
};
static	MFD_WINDOW	window_data_adi[] = {
	{w_x(-387,1),  w_y(-530,8),  w_ch('S','W','A','P')},
	{w_x(-225,1),  w_y(-530,8),  w_ch('A','D','I',' ')},
	{w_x(99,1),    w_y(-530,8),  w_ch('N','O','T','E')},
	{w_x(-520,0),  w_y(510,12),  w_ch('I','L','S','/')},
	{w_x(-430,0),  w_y(510,12),  w_ch('T',' ',' ',' ')},
	{w_x(-520,0),  w_y(470,12),  w_ch('0','6','0','t')},
	{w_x(-430,0),  w_y(470,12),  w_ch('/',' ',' ',' ')},
	{w_x(-407,0),  w_y(470,12),  w_ch('9','9','.','5')},
	{w_x(310,1),   w_y(280,15),  w_ch('R',' ',' ',' ')},
	{w_x(370,1),   w_y(280,15),  w_ch('1','0','2','3')},
	{0, 0, 0, 0}
};

void	set_simulation(short cpu_no, short dspm)
{
	if (dspm != DSPM_NOSYMBOL) {
		if (cpu == CPU_1) {
			test_data_change = (cpu_no << 8) | dspm;
			tm_simulation = TM_SIMULATION;
		} else {
			CPU1_VME(test_data_change) = (cpu_no << 8) | dspm;
			CPU1_VME(tm_simulation) = TM_SIMULATION;
		}
	} else {
		if (cpu == CPU_1)
			tm_simulation = TM_STOP;
		else
			CPU1_VME(tm_simulation) = TM_STOP;
	}
}

void	debug_simulation(void)
{
	static	void	set_test_mode(void);
	short	*datap;
	short	signal_no;
	short	*bufp;
	short	cur_data, limit, step, color, count;
	TEST_DATA	*test_data;

	if (test_data_change != 0) {
		set_test_mode();
		test_data_change = 0;
	}

	bufp = (short *)mbi_rcvbuf;
	test_data = test_data_tbl;
	for (;;) {
		signal_no = test_data->signal_no;
		if (signal_no < 0)
			break;
		datap = test_data->datap;
		cur_data = sim_work[signal_no].cur_data;
		switch (datap[cur_data]) {
		case S_SET:
			bufp[signal_no] = datap[cur_data+1];
			cur_data += 2;
			break;
		case S_UP:
			limit = datap[cur_data + 1];
			step = datap[cur_data + 2];
			color = datap[cur_data + 3];
			if (color != 0)
				bufp[signal_no] &= 0xfff0;
			if (bufp[signal_no] < limit)
				bufp[signal_no] += step;
			else
				cur_data += 4;
			if (color != 0)
				bufp[signal_no] |= color;
			break;
		case S_UPC:
			count = datap[cur_data + 1];
			step = datap[cur_data + 2];
			color = datap[cur_data + 3];
			if (color != 0)
				bufp[signal_no] &= 0xfff0;
			if (++sim_work[signal_no].counter < count)
				bufp[signal_no] += step;
			else {
				sim_work[signal_no].counter = 0;
				cur_data += 4;
			}
			if (color != 0)
				bufp[signal_no] |= color;
			break;
		case S_DOWN:
			limit = datap[cur_data + 1];
			step = datap[cur_data + 2];
			color = datap[cur_data + 3];
			if (color != 0)
				bufp[signal_no] &= 0xfff0;
			if (bufp[signal_no] > limit)
				bufp[signal_no] -= step;
			else
				cur_data += 4;
			if (color != 0)
				bufp[signal_no] |= color;
			break;
		case S_DOWNC:
			count = datap[cur_data + 1];
			step = datap[cur_data + 2];
			color = datap[cur_data + 3];
			if (color != 0)
				bufp[signal_no] &= 0xfff0;
			if (++sim_work[signal_no].counter < count)
				bufp[signal_no] -= step;
			else {
				sim_work[signal_no].counter = 0;
				cur_data += 4;
			}
			if (color != 0)
				bufp[signal_no] |= color;
			break;
		case S_WAIT:
			if (++sim_work[signal_no].counter >=
						datap[cur_data + 1]) {
				sim_work[signal_no].counter = 0;
				cur_data += 2;
			}
			break;
		case S_LOOP:
			cur_data = datap[cur_data + 1];
			break;
		case S_STOP:
			break;
		}
		sim_work[signal_no].cur_data = cur_data;
		test_data++;
	}
	count_1553B++;
}

static	void	set_test_mode(void)
{
	MFD_WINDOW	*wp1, *wp2;
	short	mode, sub_mode;

	switch (test_data_change & 0x000f) {
	case DSPM_FCR:
		mode = 1;
		wp1 = window_data_fcr;
		break;
	case DSPM_SMS:
		mode = 2;
		wp1 = window_data_sms;
		break;
	case DSPM_EW:
		mode = 3;
		wp1 = window_data_ew;
		break;
	case DSPM_HSI:
		mode = 4;
		wp1 = window_data_hsi;
		sub_mode = 0x0000;
		break;
	case DSPM_ADI:
		mode = 4;
		wp1 = window_data_adi;
		sub_mode = 0x2000;
		break;
	default:
		return;
	}

	switch ((test_data_change >> 8) & 0x0003) {
	case CPU_1:
		wp2 = (MFD_WINDOW *)&mbi_rcvbuf[0][2];
		mode =  mbi_rcvbuf[0][1] & 0xc3ff | (mode << 10);
		break;
	case CPU_2:
		wp2 = (MFD_WINDOW *)&mbi_rcvbuf[4][26];
		mode = mbi_rcvbuf[0][1] & 0xfc3f | (mode << 6);
		break;
	case CPU_3:
		wp2 = (MFD_WINDOW *)&mbi_rcvbuf[9][18];
		mode = mbi_rcvbuf[0][1] & 0xffc3 | (mode << 2);
		break;
	default:
		return;
	}

	SET_WORD(mbi_rcvbuf, 0, 44*32);
	SET_WORD(sim_work, 0, 44*32*2);

	mbi_rcvbuf[0][1] = mode;
	mbi_rcvbuf[27][0] = sub_mode;

	while (wp1->y != 0)
		*wp2++ = *wp1++;
}
