/************************************************************************
 *									*
 *		“‡Œõ”g•\Ž¦‘•’u@‚r^‚f@‚b‚o‚tƒvƒƒOƒ‰ƒ€		*
 *									*
 *		–¼Ì		: ƒfƒoƒbƒO—p“ü—ÍM†ˆ—		*
 *		ƒtƒ@ƒCƒ‹–¼	: debug2.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=debug2
#pragma	SECTION CONST=debug_data

#include "sg_cpu.h"

#define	S_SET	1
#define	S_UP	2
#define	S_DOWN	3
#define	S_WAIT	4
#define	S_LOOP	5
#define	S_STOP	6

#define	s_set(data)		S_SET,data
#define	s_up(limit,step)	S_UP,limit,step
#define	s_down(limit,step)	S_DOWN,limit,step
#define	s_wait(count)		S_WAIT,count
#define	s_loop()		S_LOOP
#define	s_stop()		S_STOP

typedef	struct	{
	short	signal_no;
	short	*datap;
} TEST_DATA;

static	const	short	data0[] = {
	s_up(300, 1),
	s_down(-50, 1),
	s_loop()
};
static	const	short	data1[] = {
	s_up(15000, 1),
	s_down(-1000, 1),
	s_loop()
};

static	const	short	data2[] = {
	s_up(3599, 2),
	s_down(0, 2),
	s_loop()
};

static	const	short	data3[] = {
	s_up(4500, 4),
	s_down(-4500, 4),
	s_loop()
};

static	const	short	data5[] = {
	s_up(18000, 20),
	s_down(-18000, 20),
	s_loop()
};

static	const	short	data6[] = {
	s_up(6000, 10),
	s_down(-6000, 10),
	s_loop()
};

static	const	short	data7[] = {
	s_set(1840),
	s_stop()
};

static	const	short	data10[] = {
	s_set(0),
	s_wait(300),
	s_set(1),
	s_wait(300),
	s_set(2),
	s_wait(300),
	s_loop()
};

static	const	short	data11[] = {
	s_set(0x1234),
	s_stop()
};

static	const	short	data12[] = {
	s_set(0),
	s_wait(1500),
	s_set(1),
	s_wait(1500),
	s_set(2),
	s_wait(1500),
	s_set(3),
	s_wait(1500),
	s_loop()
};

static	const	short	data13[] = {
	s_up(9995, 1),
	s_down(0, 1),
	s_loop()
};

static	const	short	data14[] = {
	s_up(9995, 2),
	s_down(0, 2),
	s_loop()
};

static	const	short	data15[] = {
	s_set(2),
	s_stop()
};

static	const	short	data16[] = {
	s_set(0),
	s_wait(80),
	s_set(1),
	s_wait(200),
	s_loop()
};

static	const	short	data17[] = {
	s_up(600, 2),
	s_wait(30),
	s_down(100, 3),
	s_wait(40),
	s_up(400, 4),
	s_wait(80),
	s_loop()
};

static	const	short	data18[] = {
	s_up(500, 2),
	s_wait(60),
	s_down(150, 3),
	s_wait(50),
	s_up(700, 4),
	s_wait(80),
	s_down(400, 8),
	s_wait(80),
	s_loop()
};

static	const	short	data19[] = {
	s_set(150),
	s_wait(70),
	s_up(200, 4),
	s_wait(70),
	s_down(150, 4),
	s_loop()
};

static	const	short	data20[] = {
	s_set(200),
	s_wait(70),
	s_up(250, 4),
	s_wait(70),
	s_down(200, 4),
	s_loop()
};

static	const	short	data21a[] = {
	s_set(1),
	s_stop()
};

static	const	short	data21b[] = {
	s_set(2),
	s_stop()
};

static	const	short	data21c[] = {
	s_set(3),
	s_stop()
};

static	const	short	data22[] = {
	s_set(0),
	s_wait(205),
	s_set(1),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(2),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(3),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(4),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(5),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(6),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(7),
	s_wait(105),
	s_set(0),
	s_wait(205),
	s_set(8),
	s_loop()
};

static	const	short	data23[] = {
	s_set(0),
	s_wait(111),
	s_set(1),
	s_wait(121),
	s_set(0),
	s_wait(131),
	s_set(2),
	s_wait(141),
	s_loop()
};

static	const	short	data24[] = {
	s_set(0),
	s_wait(152),
	s_set(1),
	s_wait(162),
	s_set(0),
	s_wait(172),
	s_set(2),
	s_wait(182),
	s_loop()
};

static	const	short	data25[] = {
	s_set(0),
	s_wait(253),
	s_set(1),
	s_wait(263),
	s_set(0),
	s_wait(273),
	s_set(2),
	s_wait(283),
	s_set(0),
	s_wait(273),
	s_set(3),
	s_wait(283),
	s_loop()
};

static	const	short	data26[] = {
	s_set(0),
	s_wait(300),
	s_set(1),
	s_wait(300),
	s_loop()
};

static	const	short	data29a[] = {
	s_set(0x000),
	s_wait(100),
	s_set(0x100),
	s_wait(1000),
	s_set(0x200),
	s_wait(1000),
	s_loop()
};

static	const	short	data29b[] = {
	s_set(0x300),
	s_wait(300),
	s_set(0x301),
	s_wait(300),
	s_loop()
};

static	const	short	data30[] = {
	s_up(1500, 5),
	s_wait(100),
	s_down(-1500, 5),
	s_wait(100),
	s_loop()
};

static	const	short	data31[] = {
	s_up(1500, 4),
	s_wait(200),
	s_down(-1500, 4),
	s_wait(200),
	s_loop()
};

static	const	short	data44[] = {
	s_set(1),
	s_stop()
};

static	const	short	data45[] = {
	s_up(999, 1),
	s_down(0, 1),
	s_loop()
};

static	const	short	data46[] = {
	s_set(-937-2000),
	s_up(-937+2000, 2),
	s_loop()
};

static	const	short	data47[] = {
	s_set(328),
	s_stop()
};

static	const	short	data48[] = {
	s_set(190),
	s_stop()
};

static	const	short	data49[] = {
	s_set(-828-2000),
	s_up(-828+2000, 2),
	s_loop()
};

static	const	short	data50[] = {
	s_set(266),
	s_stop()
};

static	const	short	data51[] = {
	s_set(180),
	s_stop()
};

static	const	short	data52[] = {
	s_set(-719-2000),
	s_up(-719+2000, 2),
	s_loop()
};

static	const	short	data53[] = {
	s_set(243),
	s_stop()
};

static	const	short	data54[] = {
	s_set(170),
	s_stop()
};

static	const	short	data55[] = {
	s_set(-609-2000),
	s_up(-609+2000, 2),
	s_loop()
};

static	const	short	data56[] = {
	s_set(203),
	s_stop()
};

static	const	short	data57[] = {
	s_set(160),
	s_stop()
};

static	const	short	data58[] = {
	s_set(-516-2000),
	s_up(-516+2000, 2),
	s_loop()
};

static	const	short	data59[] = {
	s_set(187),
	s_stop()
};

static	const	short	data60[] = {
	s_set(150),
	s_stop()
};

static	const	short	data61[] = {
	s_set(-437-2000),
	s_up(-437+2000, 2),
	s_loop()
};

static	const	short	data62[] = {
	s_set(172),
	s_stop()
};

static	const	short	data63[] = {
	s_set(165),
	s_stop()
};

static	const	short	data64[] = {
	s_set(-344-2000),
	s_up(-344+2000, 2),
	s_loop()
};

static	const	short	data65[] = {
	s_set(156),
	s_stop()
};

static	const	short	data66[] = {
	s_set(180),
	s_stop()
};

static	const	short	data67[] = {
	s_set(-250-2000),
	s_up(-250+2000, 2),
	s_loop()
};

static	const	short	data68[] = {
	s_set(187),
	s_stop()
};

static	const	short	data69[] = {
	s_set(195),
	s_stop()
};

static	const	short	data70[] = {
	s_set(-141-2000),
	s_up(-141+2000, 2),
	s_loop()
};

static	const	short	data71[] = {
	s_set(219),
	s_stop()
};

static	const	short	data72[] = {
	s_set(210),
	s_stop()
};

static	const	short	data73[] = {
	s_set(-62-2000),
	s_up(-62+2000, 2),
	s_loop()
};

static	const	short	data74[] = {
	s_set(266),
	s_stop()
};

static	const	short	data75[] = {
	s_set(225),
	s_stop()
};

static	const	short	data76[] = {
	s_set(31-2000),
	s_up(31+2000, 2),
	s_loop()
};

static	const	short	data77[] = {
	s_set(312),
	s_stop()
};

static	const	short	data78[] = {
	s_set(240),
	s_stop()
};

static	const	short	data79[] = {
	s_set(125-2000),
	s_up(125+2000, 2),
	s_loop()
};

static	const	short	data80[] = {
	s_set(344),
	s_stop()
};

static	const	short	data81[] = {
	s_set(255),
	s_stop()
};

static	const	short	data82[] = {
	s_set(234-2000),
	s_up(234+2000, 2),
	s_loop()
};

static	const	short	data83[] = {
	s_set(359),
	s_stop()
};

static	const	short	data84[] = {
	s_set(270),
	s_stop()
};

static	const	short	data85[] = {
	s_set(344-2000),
	s_up(344+2000, 2),
	s_loop()
};

static	const	short	data86[] = {
	s_set(406),
	s_stop()
};

static	const	short	data87[] = {
	s_set(285),
	s_stop()
};

static	const	short	data88[] = {
	s_set(453-2000),
	s_up(453+2000, 2),
	s_loop()
};

static	const	short	data89[] = {
	s_set(437),
	s_stop()
};

static	const	short	data90[] = {
	s_set(295),
	s_stop()
};

static	const	short	data91[] = {
	s_set(531-2000),
	s_up(531+2000, 2),
	s_loop()
};

static	const	short	data92[] = {
	s_set(484),
	s_stop()
};

static	const	short	data93[] = {
	s_set(296),
	s_stop()
};

static	const	short	data94[] = {
	s_set(640-2000),
	s_up(640+2000, 2),
	s_loop()
};

static	const	short	data95[] = {
	s_set(516),
	s_stop()
};

static	const	short	data96[] = {
	s_set(297),
	s_stop()
};

static	const	short	data97[] = {
	s_set(734-2000),
	s_up(734+2000, 2),
	s_loop()
};

static	const	short	data98[] = {
	s_set(609),
	s_stop()
};

static	const	short	data99[] = {
	s_set(298),
	s_stop()
};

static	const	short	data100[] = {
	s_set(828-2000),
	s_up(828+2000, 2),
	s_loop()
};

static	const	short	data101[] = {
	s_set(687),
	s_stop()
};

static	const	short	data102[] = {
	s_set(299),
	s_stop()
};

static	const	short	data103[] = {
	s_set(937-2000),
	s_up(937+2000, 2),
	s_loop()
};

static	const	short	data104[] = {
	s_set(750),
	s_stop()
};

static	const	short	data105[] = {
	s_set(300),
	s_stop()
};

static	const	short	data106[] = {
	s_set(-937-2000),
	s_up(-937+2000, 2),
	s_loop()
};

static	const	short	data107[] = {
	s_set(94),
	s_stop()
};

static	const	short	data108[] = {
	s_set(301),
	s_stop()
};

static	const	short	data109[] = {
	s_set(-859-2000),
	s_up(-859+2000, 2),
	s_loop()
};

static	const	short	data110[] = {
	s_set(31),
	s_stop()
};

static	const	short	data111[] = {
	s_set(302),
	s_stop()
};

static	const	short	data112[] = {
	s_set(-782-2000),
	s_up(-782+2000, 2),
	s_loop()
};

static	const	short	data113[] = {
	s_set(-16),
	s_stop()
};

static	const	short	data114[] = {
	s_set(305),
	s_stop()
};

static	const	short	data354[] = {
	s_set(300),
	s_stop()
};

static	const	short	data355[] = {
	s_set(500),
	s_stop()
};

static	const	TEST_DATA	test_data1[] = {
	{0, data0},
	{1, data1},
	{2, data2},
	{3, data3},
	{5, data5},
	{6, data6},
	{7, data7},
	{10, data10},
	{11, data11},
	{12, data12},
	{13, data13},
	{14, data14},
	{15, data15},
	{16, data16},
	{17, data17},
	{18, data18},
	{19, data19},
	{20, data20},
	{21, data21a},
	{22, data22},
	{23, data23},
	{24, data24},
	{25, data25},
	{26, data26},
	{29, data29a},
	{30, data30},
	{31, data31},
	{44, data44},
	{45, data45},
	{46, data46},
	{47, data47},
	{48, data48},
	{49, data49},
	{50, data50},
	{51, data51},
	{52, data52},
	{53, data53},
	{54, data54},
	{55, data55},
	{56, data56},
	{57, data57},
	{58, data58},
	{59, data59},
	{60, data60},
	{61, data61},
	{62, data62},
	{63, data63},
	{64, data64},
	{65, data65},
	{66, data66},
	{67, data67},
	{68, data68},
	{69, data69},
	{70, data70},
	{71, data71},
	{72, data72},
	{73, data73},
	{74, data74},
	{75, data75},
	{76, data76},
	{77, data77},
	{78, data78},
	{79, data79},
	{80, data80},
	{81, data81},
	{82, data82},
	{83, data83},
	{84, data84},
	{85, data85},
	{86, data86},
	{87, data87},
	{88, data88},
	{89, data89},
	{90, data90},
	{91, data91},
	{92, data92},
	{93, data93},
	{94, data94},
	{95, data95},
	{96, data96},
	{97, data97},
	{98, data98},
	{99, data99},
	{100, data100},
	{101, data101},
	{102, data102},
	{103, data103},
	{104, data104},
	{105, data105},
	{106, data106},
	{107, data107},
	{108, data108},
	{109, data109},
	{110, data110},
	{111, data111},
	{112, data112},
	{113, data113},
	{114, data114},
	{354, data354},
	{355, data355},
	{-1, NULL}
};

static	const	TEST_DATA	test_data2[] = {
	{29, data29b},
	{30, data30},
	{31, data31},
	{-1, NULL}
};

static	const	TEST_DATA *const test_data_tbl[] = {
	test_data1,
	test_data2
};

void	debug_simulation(void)
{
	register short	*datap;
	register short	signal_no;
	short	cur_data, limit, step;
	const	TEST_DATA	*test_data;

	if (test_data_change != 0) {
		if (test_data_change > 0 && test_data_change <=
				sizeof(test_data_tbl) / sizeof(TEST_DATA *)) {
			SET_WORD(sim_work, 0, 512*2);
			SET_WORD(input_data, 0, 512);
			test_data_no = test_data_change;
		} else
			test_data_no = 0;
		test_data_change = 0;
	}

	if (test_data_no == 0)
		return;

	if (ibuf_flag != 0)
		return;

	test_data = test_data_tbl[test_data_no - 1];
	for (;;) {
		signal_no = test_data->signal_no;
		if (signal_no < 0)
			break;
		datap = test_data->datap;
		cur_data = sim_work[signal_no].cur_data;
		switch (datap[cur_data]) {
		case S_SET:
			input_data[signal_no] = datap[cur_data+1];
			cur_data += 2;
			break;
		case S_UP:
			limit = datap[cur_data + 1];
			step = datap[cur_data + 2];
			if (input_data[signal_no] + step <= limit) {
				if (step >= 0)
					input_data[signal_no] += step;
				else
					if (++sim_work[signal_no].counter >
								-step) {
						input_data[signal_no]++;
						sim_work[signal_no].counter
									 = 0;
					}
			} else
				cur_data += 3;
			break;
		case S_DOWN:
			limit = datap[cur_data + 1];
			step = datap[cur_data + 2];
			if (input_data[signal_no] - step >= limit) {
				if (step >= 0)
					input_data[signal_no] -= step;
				else
					if (++sim_work[signal_no].counter >
								-step) {
						input_data[signal_no]--;
						sim_work[signal_no].counter
									 = 0;
					}
			} else
				cur_data += 3;
			break;
		case S_WAIT:
			if (++sim_work[signal_no].counter >=
						datap[cur_data + 1]) {
				sim_work[signal_no].counter = 0;
				cur_data += 2;
			}
			break;
		case S_LOOP:
			cur_data = 0;
			break;
		case S_STOP:
			break;
		}
		sim_work[signal_no].cur_data = cur_data;
		test_data++;
	}

	ibuf_flag = 1;
}
