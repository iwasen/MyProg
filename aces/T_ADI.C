#include "sg_cpu.h"
#include <graph.h>
#include <math.h>
#include "test.h"
#define	DP_TO_MM(dp)	(short)(((long)(dp) * 1925) / 1024)
#define _GBORDER	2	/* draw outline only */
#define _GFILLINTERIOR	3	/* fill using current fill mask */

void	dsp_M065A()
{
	struct	xycoord	coord[10];

	setcolor(dp_tmp.C065A0);
	coord[0].xcoord = 0;
	coord[0].ycoord = 4635;
	coord[1].xcoord = 135;
	coord[1].ycoord = 4500;
	coord[2].xcoord = 750;
	coord[2].ycoord = 4500;
	coord[3].xcoord = 750;
	coord[3].ycoord = 3915;
	coord[4].xcoord = 75;
	coord[4].ycoord = 3915;
	coord[5].xcoord = 0;
	coord[5].ycoord = 3660;
	coord[6].xcoord = -75;
	coord[6].ycoord = 3915;
	coord[7].xcoord = -750;
	coord[7].ycoord = 3915;
	coord[8].xcoord = -750;
	coord[8].ycoord = 4500;
	coord[9].xcoord = -135;
	coord[9].ycoord = 4500;
	polygon(coord, 10, _GBORDER);

	setcolor(dp_tmp.C065A1);
	dsp_font(-571, 4418, dp_tmp.A065A, 3);
	_outgtext("Ò");
}

void	dsp_M065B()
{
	short	x, *p;
	int	i;

	setcolor(dp_tmp.C065B);
	x = -DP_TO_MM(dp_tmp.LX065B);
	p = dp_tmp.A065BH;
	setclip(-2250, 4695, 2250, 5248);
	for (i = 0; i < 36; i++) {
		if (i % 5 == 0) {
			dsp_font(x-191, 5145+50, p, 2);
			line(x, 4875, x, 4695);
			p -= 2;
		} else {
			line(x, 4875, x, 4755);
		}
		x += 150;
	}
	resetclip();
}

void	dsp_M0660(){}
void	dsp_M0661()
{
	short	x;

	setcolor(dp_tmp.C066);
	x = -2250 + DP_TO_MM(dp_tmp.L066);
	line(x, 4785, x-180, 4530);
	line(x, 4785, x+180, 4530);
}

void	dsp_M067A()
{
	setcolor(dp_tmp.C067A);
	arc(0, 0, 2598, 1500, -2598, 1500, 3000);
	arc(0, 0, -2598, -1500, 2598, -1500, 3000);
	line(2598, 1500, 2598, -1500);
	line(-2598, 1500, -2598, -1500);

	line(635, 3604, 666, 3781);
	line(1251, 3439, 1313, 3608);
	line(1830, 3169, 1965, 3403);
	line(2588, 2588, 2715, 2715);
	line(3169, 1830, 3403, 1965);
	line(3169, 0, 3244, 0);

	line(-635, 3604, -666, 3781);
	line(-1251, 3439, -1313, 3608);
	line(-1830, 3169, -1965, 3403);
	line(-2588, 2588, -2715, 2715);
	line(-3169, 1830, -3403, 1965);
	line(-3169, 0, -3349, 0);

	line(2588, -2588, 2662, -2662);
	line(0, -3660, 0, -3765);
	line(-2588, -2588, -2662, -2662);
}

void	dsp_M067B0(){}
void	dsp_M067B1()
{
	struct	xycoord	coord[3];
	short	len;

	setcolor(dp_tmp.C067B);
	len = DP_TO_MM(dp_tmp.L067B);
	coord[0].xcoord = 0;
	coord[0].ycoord = len;
	coord[1].xcoord = 180;
	coord[1].ycoord = len - 300;
	coord[2].xcoord = -180;
	coord[2].ycoord = len - 300;
	rotate2(coord, 3, dp_tmp.R067B);
	polygon(coord, 3, _GFILLINTERIOR);
}

void	dsp_M0680(){}
void	dsp_M0681()
{
	short	y, x1, y1, x2, y2;
	int	i;
	char	buf[10];

	y = DP_TO_MM(dp_tmp.L068);
	setclip(-2598, -3000, 2598, 3000);
	x1 = -7320;
	x2 = -x1;
	y1 = y2 = y - 570 * 18;
	rotate(&x1, &y1, dp_tmp.R068);
	rotate(&x2, &y2, dp_tmp.R068);
	line(x1, y1, x2, y2);
	x1 = 0;
	y1 = y - 570 * 18 + 100;
	rotate(&x1, &y1, dp_tmp.R068);
	setcolor(COLOR_CYAN);
	fill(x1, y1, COLOR_WHITE1);
	x1 = 0;
	y1 = y - 570 * 18 - 100;
	rotate(&x1, &y1, dp_tmp.R068);
	setcolor(COLOR_BROWN);
	fill(x1, y1, COLOR_WHITE1);

	setcolor(dp_tmp.C068);
	for (i = 0; i < 37; i++) {
		if (i == 18)
			x1 = -7320;
		else if (i % 2 == 0) {
			sprintf(buf, "%d", ABS(90-i*5));
			x1 = 1155;
			y1 = y + 120;
			rotate(&x1, &y1, dp_tmp.R068);
			dsp_str(x1, y1, buf, 6);
			x1 = -1538;
			y1 = y + 120;
			rotate(&x1, &y1, dp_tmp.R068);
			dsp_str(x1, y1, buf, 6);
			x1 = -1035;
		} else
			x1 = -375;
		x2 = -x1;
		y1 = y2 = y;
		rotate(&x1, &y1, dp_tmp.R068);
		rotate(&x2, &y2, dp_tmp.R068);
		line(x1, y1, x2, y2);
		y -= 570;
	}
	resetclip();
}

void	dsp_M0690(){}
void	dsp_M0691()
{
	short	x, y;

	setcolor(dp_tmp.C069);
	x = DP_TO_MM(dp_tmp.PX069);
	y = DP_TO_MM(dp_tmp.PY069);
	circle(x, y, 120, _GBORDER);
	line(x, y+120, x, y+270);
	line(x+120, y, x+360, y);
	line(x-120, y, x-360, y);
}

void	dsp_M070A()
{
	struct	xycoord	coord[5];

	coord[0].xcoord = 5340;
	coord[0].ycoord = 225;
	coord[1].xcoord = 3840;
	coord[1].ycoord = 225;
	coord[2].xcoord = 3720;
	coord[2].ycoord = 0;
	coord[3].xcoord = 3840;
	coord[3].ycoord = -225;
	coord[4].xcoord = 5340;
	coord[4].ycoord = -225;
	setcolor(COLOR_BLACK);
	polygon(coord, 5, _GFILLINTERIOR);

	coord[0].xcoord = 5340;
	coord[0].ycoord = 225;
	coord[1].xcoord = 3840;
	coord[1].ycoord = 225;
	coord[2].xcoord = 3720;
	coord[2].ycoord = 0;
	coord[3].xcoord = 3840;
	coord[3].ycoord = -225;
	coord[4].xcoord = 5340;
	coord[4].ycoord = -225;
	setcolor(dp_tmp.C070A0);
	polygon(coord, 5, _GBORDER);

	setcolor(dp_tmp.C070A1);
	dsp_font(3880, 180, dp_tmp.A070A+3, 2);
	dsp_str(4455, 60+30, ".", 6);
	dsp_font(4680, 60+30, dp_tmp.A070A, 3);
}

void	dsp_M070B()
{
	short	y, *p;
	int	i;

	setcolor(dp_tmp.C070B);
	y = -DP_TO_MM(dp_tmp.L070B);
	p = dp_tmp.A070BA;
	setclip(3715, -1800, 5019, 1800);
	for (i = 0; i < 21; i++) {
		if (i % 5 == 0) {
			line(3720, y, 3945, y);
			dsp_font(4035, y+120, p, 4);
			p += 4;
		} else {
			line(3720, y, 3840, y);
		}
		y += 240;
	}
	resetclip();
}

void	dsp_M071A0()
{
	setcolor(dp_tmp.C071A);
	box(3255, -1860, 3675, 1860, _GBORDER);
}

void	dsp_M071A1()
{
	setcolor(dp_tmp.C071A);
	circle(3465, 1560, 90, _GFILLINTERIOR);
}

void	dsp_M071A2()
{
	setcolor(dp_tmp.C071A);
	circle(3465, 780, 90, _GFILLINTERIOR);
}

void	dsp_M071A3()
{
	setcolor(dp_tmp.C071A);
	circle(3465, 0, 90, _GFILLINTERIOR);
}

void	dsp_M071A4()
{
	setcolor(dp_tmp.C071A);
	circle(3465, -780, 90, _GFILLINTERIOR);
}

void	dsp_M071A5()
{
	setcolor(dp_tmp.C071A);
	circle(3465, -1560, 90, _GFILLINTERIOR);
}

void	dsp_M071B0(){}
void	dsp_M071B1()
{
	short	y;

	setcolor(dp_tmp.C071B);
	y = -1860 + DP_TO_MM(dp_tmp.L071B);
	triangle(3450, y, 3675, y+112, 3675, y-112, _GFILLINTERIOR);
}

void	dsp_M072A0(){}
void	dsp_M072A1()
{
	setcolor(dp_tmp.C072A0);
	arc(4300, -3375, 4300, -2730, 4300, -4020, 645);
	arc(4300, -3375, 4300, -2550, 4300, -4200, 825);
	arc(4300, -3375, 4300, -2425, 4300, -4305, 930);
	line(4300, -2730, 4300, -2370);
	line(3977, -2816, 3887, -2660);
	line(3835, -2569, 3797, -2504);
	line(3741, -3052, 3585, -2962);
	line(3494, -2910, 3429, -2875);
	line(3655, -3375, 3475, -3375);
	line(3370, -3375, 3295, -3375);
	line(3741, -3697, 3585, -3787);
	line(3494, -3840, 3429, -3877);
	line(3977, -3933, 3887, -4089);
	line(3835, -4180, 3797, -4245);
	line(4300, -4020, 4300, -4380);
	dsp_str(4108, -1979, "60", 6);
	dsp_str(3470, -2150, "20", 6);
	dsp_str(2891, -2617, "10", 6);
	dsp_str(3103, -3254, "0", 6);
	dsp_str(2665, -3892, "-10", 6);
	dsp_str(3132, -4359, "-20", 6);
	dsp_str(3837, -4530, "-60", 6);

	setcolor(dp_tmp.C072A1);
	dsp_font(3715, -3134, dp_tmp.A072A, 5);
}

void	dsp_M072B()
{
	double	t;

	setcolor(dp_tmp.C072B);
	t = DP_TO_MM(dp_tmp.L072B0) / 675.0;
	arc(4300, -3375, 4300-(short)(cos(t)*675), -3375+(short)(sin(t)*675),
					3625, -3375, 675);
	t = DP_TO_MM(dp_tmp.L072B1) / 705.0;
	arc(4300, -3375, 4300-(short)(cos(t)*705), -3375+(short)(sin(t)*705),
					3595, -3375, 705);
	t = DP_TO_MM(dp_tmp.L072B2) / 735.0;
	arc(4300, -3375, 4300-(short)(cos(t)*735), -3375+(short)(sin(t)*735),
					3565, -3375, 735);
}

void	dsp_M072C()
{
	double	t;

	setcolor(dp_tmp.C072B);
	t = DP_TO_MM(dp_tmp.L072B0) / 675.0;
	arc(4300, -3375, 3625, -3375, 4300-(short)(cos(t)*675),
					-3375-(short)(sin(t)*675), 675);
	t = DP_TO_MM(dp_tmp.L072B1) / 705.0;
	arc(4300, -3375, 3595, -3375, 4300-(short)(cos(t)*705),
					-3375-(short)(sin(t)*705), 705);
	t = DP_TO_MM(dp_tmp.L072B2) / 735.0;
	arc(4300, -3375, 3565, -3375, 4300-(short)(cos(t)*735),
					-3375-(short)(sin(t)*735), 735);
}

void	dsp_M072D0(){}
void	dsp_M072D1()
{
	struct	xycoord	coord[5];
	int	i;

	setcolor(dp_tmp.C072D);
	coord[0].xcoord = -1035;
	coord[0].ycoord = 0;
	coord[1].xcoord = -645;
	coord[1].ycoord = 0;
	coord[2].xcoord = -585;
	coord[2].ycoord = 60;
	coord[3].xcoord = -585;
	coord[3].ycoord = -60;
	coord[4].xcoord = -645;
	coord[4].ycoord = 0;
	rotate2(coord, 5, dp_tmp.R072D);
	for (i = 0; i < 5; i++) {
		coord[i].xcoord += 4300;
		coord[i].ycoord -= 3375;
	}
	polygon(coord, 5, _GFILLINTERIOR);
}

void	dsp_M073A0()
{
	setcolor(dp_tmp.C073A);
	box(-1860, -4200, 1860, -3780, _GBORDER);
}

void	dsp_M073A1()
{
	setcolor(dp_tmp.C073A);
	circle(-1560, -3990, 90, _GFILLINTERIOR);
}

void	dsp_M073A2()
{
	setcolor(dp_tmp.C073A);
	circle(-780, -3990, 90, _GFILLINTERIOR);
}

void	dsp_M073A3()
{
	setcolor(dp_tmp.C073A);
	circle(0, -3990, 90, _GFILLINTERIOR);
}

void	dsp_M073A4()
{
	setcolor(dp_tmp.C073A);
	circle(780, -3990, 90, _GFILLINTERIOR);
}

void	dsp_M073A5()
{
	setcolor(dp_tmp.C073A);
	circle(1560, -3990, 90, _GFILLINTERIOR);
}

void	dsp_M073B0(){}
void	dsp_M073B1()
{
	short	x;

	setcolor(dp_tmp.C073B);
	x = -1860 + DP_TO_MM(dp_tmp.L073B);
	triangle(x, -3990, x+112, -4200, x-112, -4200, _GFILLINTERIOR);
}

void	dsp_M074A0(){}
void	dsp_M074A1()
{
	setcolor(dp_tmp.C074A0);
	arc(-4500, -3375, -4500, -4305, -4500, -2445, 930);
	arc(-4500, -3375, -4087, -2660, -4500, -2550, 825);
	arc(-4500, -3375, -4057, -4141, -4057, -2608, 870);
	arc(-4500, -3375, -4102, -4063, -4102, -2686, 780);
	line(-4500, -2730, -4500, -2340);
	line(-4500, -4020, -4500, -4410);
	line(-4102, -2686, -4065, -2621);
	line(-3811, -2977, -3746, -2940);
	line(-3705, -3375, -3630, -3375);
	line(-3811, -3772, -3746, -3810);
	line(-4102, -4063, -4065, -4128);

	line(-4035, -2569, -3982, -2478);
	line(-3694, -2910, -3603, -2857);
	line(-3705, -3375, -3465, -3375);
	line(-3694, -3840, -3603, -3892);
	line(-4035, -4180, -3982, -4271);

	dsp_str2(-3941, -2390, "30", 6);
	dsp_str2(-3474, -2857, "15", 6);
	dsp_str2(-3078, -3495, "0", 6);
	dsp_str2(-3475, -4132, "-2.5", 6);
	dsp_str2(-3941, -4599, "-5", 6);

	setcolor(dp_tmp.C074A1);
	dsp_font(-5310, -3194, dp_tmp.A074A, 2);
}

void	dsp_M074B()
{
	double	t;

	setcolor(dp_tmp.C074B);

	t = DP_TO_MM(dp_tmp.L074B0) / 855.0;
	arc(-4500, -3375, -3645, -3375, -4500+(short)(cos(t)*855),
				-3375+(short)(sin(t)*855), 855);
	t = DP_TO_MM(dp_tmp.L074B1) / 825.0;
	arc(-4500, -3375, -3675, -3375, -4500+(short)(cos(t)*825),
				-3375+(short)(sin(t)*825), 825);
	t = DP_TO_MM(dp_tmp.L074B2) / 795.0;
	arc(-4500, -3375, -3705, -3375, -4500+(short)(cos(t)*795),
				-3375+(short)(sin(t)*795), 795);
}

void	dsp_M074C()
{
	double	t;

	setcolor(dp_tmp.C074B);

	t = DP_TO_MM(dp_tmp.L074B0) / 855.0;
	arc(-4500, -3375, -4500+(short)(cos(t)*855),
				-3375-(short)(sin(t)*855), -3645, -3375, 855);
	t = DP_TO_MM(dp_tmp.L074B1) / 825.0;
	arc(-4500, -3375, -4500+(short)(cos(t)*825),
				-3375-(short)(sin(t)*825), -3675, -3375, 825);
	t = DP_TO_MM(dp_tmp.L074B2) / 795.0;
	arc(-4500, -3375, -4500+(short)(cos(t)*795),
				-3375-(short)(sin(t)*795), -3705, -3375, 795);
}

void	dsp_M074D0(){}
void	dsp_M074D1()
{
	struct	xycoord	coord[4];
	int	i;

	setcolor(dp_tmp.C074D);
	coord[0].xcoord = 75;
	coord[0].ycoord = 0;
	coord[1].xcoord = 615;
	coord[1].ycoord = 0;
	coord[2].xcoord = 0;
	coord[2].ycoord = 75;
	coord[3].xcoord = 0;
	coord[3].ycoord = -75;
	rotate2(coord, 4, dp_tmp.R074D);
	for (i = 0; i < 4; i++) {
		coord[i].xcoord -= 4500;
		coord[i].ycoord -= 3375;
	}
	line(coord[0].xcoord, coord[0].ycoord,
				coord[1].xcoord, coord[1].ycoord);
	line(coord[0].xcoord, coord[0].ycoord,
				coord[2].xcoord, coord[2].ycoord);
	line(coord[0].xcoord, coord[0].ycoord,
				coord[3].xcoord, coord[3].ycoord);
}

void	dsp_M075A()
{
	struct	xycoord	coord[5];

	coord[0].xcoord = -3825;
	coord[0].ycoord = 225;
	coord[1].xcoord = -4845;
	coord[1].ycoord = 225;
	coord[2].xcoord = -4845;
	coord[2].ycoord = -225;
	coord[3].xcoord = -3825;
	coord[3].ycoord = -225;
	coord[4].xcoord = -3705;
	coord[4].ycoord = 0;
	setcolor(COLOR_BLACK);
	polygon(coord, 5, _GFILLINTERIOR);

	coord[0].xcoord = -3825;
	coord[0].ycoord = 225;
	coord[1].xcoord = -4845;
	coord[1].ycoord = 225;
	coord[2].xcoord = -4845;
	coord[2].ycoord = -225;
	coord[3].xcoord = -3825;
	coord[3].ycoord = -225;
	coord[4].xcoord = -3705;
	coord[4].ycoord = 0;
	setcolor(dp_tmp.C075A0);
	polygon(coord, 5, _GBORDER);

	setcolor(dp_tmp.C075A1);
	dsp_font(-4776, 180, dp_tmp.A075A, 3);
}

void	dsp_M075B()
{
	short	y, *p;
	int	i;

	setcolor(dp_tmp.C075B);
	y = -DP_TO_MM(dp_tmp.LY075B);
	p = dp_tmp.A075BA;
	setclip(-4604, -1800, -3565, 1800);
	for (i = 0; i < 21; i++) {
		if (i % 5 == 0) {
			line(-3600, y, -3825, y);
			dsp_font(-4524, y+120, p, 3);
			p += 3;
		} else {
			line(-3600, y, -3720, y);
		}
		y += 240;
	}
	resetclip();
}

void	dsp_M0760(){}
void	dsp_M0761()
{
	struct	xycoord	coord[8];

	setcolor(dp_tmp.C076);
	coord[0].xcoord = 2775;
	coord[0].ycoord = 60;
	coord[1].xcoord = 875;
	coord[1].ycoord = 60;
	coord[2].xcoord = 405;
	coord[2].ycoord = -465;
	coord[3].xcoord = 165;
	coord[3].ycoord = -60;
	coord[4].xcoord = 45;
	coord[4].ycoord = -105;
	coord[5].xcoord = 405;
	coord[5].ycoord = -585;
	coord[6].xcoord = 870;
	coord[6].ycoord = -60;
	coord[7].xcoord = 2775;
	coord[7].ycoord = -60;
	polygon(coord, 8, _GFILLINTERIOR);

	coord[0].xcoord = -2775;
	coord[0].ycoord = 60;
	coord[1].xcoord = -875;
	coord[1].ycoord = 60;
	coord[2].xcoord = -405;
	coord[2].ycoord = -465;
	coord[3].xcoord = -165;
	coord[3].ycoord = -60;
	coord[4].xcoord = -45;
	coord[4].ycoord = -105;
	coord[5].xcoord = -405;
	coord[5].ycoord = -585;
	coord[6].xcoord = -870;
	coord[6].ycoord = -60;
	coord[7].xcoord = -2775;
	coord[7].ycoord = -60;
	polygon(coord, 8, _GFILLINTERIOR);
	circle(0, 0, 45, _GFILLINTERIOR);
}

void	dsp_M077A(short pa)
{
	short	i, y;

	setcolor(dp_tmp.C077A0);
	for (i = 0; i < 4; i++)
		box(-3945, 4500-i*210, -3945+90, 4500-i*210+90, _GBORDER);
	for (i = 0; i < 4; i++)
		box(-3945, 3450-i*210, -3945+90, 3450-i*210+90, _GBORDER);
	setcolor(dp_tmp.C077A);
	y = 2820 + DP_TO_MM(dp_tmp.L077A);
	line(-4035, y, -3765, y);
}

void	dsp_M077B(short pa)
{
	short	i, x;

	setcolor(dp_tmp.C077B0);
	for (i = 0; i < 4; i++)
		box(-4785+i*210, 3660, -4785+i*210+90, 3660+90, _GBORDER);
	for (i = 0; i < 4; i++)
		box(-3735+i*210, 3660, -3735+i*210+90, 3660+90, _GBORDER);
	setcolor(dp_tmp.C077B);
	x = -4785 + DP_TO_MM(dp_tmp.L077B);
	line(x, 3840, x, 3570);
}

void	dsp_M077C(short pa)
{
	setcolor(dp_tmp.C077C);
	arc(-3900, 3705, -4727, 3010, -3073, 3010, 1080);
	line(-4727, 3010, -4784, 2962);
	line(-4356, 2726, -4388, 2658);
	line(-3900, 2625, -3900, 2550);
	line(-3443, 2726, -3411, 2658);
	line(-3072, 3010, -3015, 2962);
}

void	dsp_M077D()
{
	double	t;

	setcolor(dp_tmp.C077D);
	t = DP_TO_MM(dp_tmp.L077D0) / 1050.0;
	arc(-3900, 3705, -3900, 2655, -3900+(short)(sin(t)*1050),
			3705-(short)(cos(t)*1050), 1050);
	t = DP_TO_MM(dp_tmp.L077D1) / 1020.0;
	arc(-3900, 3705, -3900, 2685, -3900+(short)(sin(t)*1020),
			3705-(short)(cos(t)*1020), 1020);
	t = DP_TO_MM(dp_tmp.L077D2) / 990.0;
	arc(-3900, 3705, -3900, 2715, -3900+(short)(sin(t)*990),
			3705-(short)(cos(t)*990), 990);
}

void	dsp_M077E()
{
	double	t;

	setcolor(dp_tmp.C077D);
	t = DP_TO_MM(dp_tmp.L077D0) / 1050.0;
	arc(-3900, 3705, -3900-(short)(sin(t)*1050), 3705-(short)(cos(t)*1050),
					-3900, 2655, 1050);
	t = DP_TO_MM(dp_tmp.L077D1) / 1020.0;
	arc(-3900, 3705, -3900-(short)(sin(t)*1020), 3705-(short)(cos(t)*1020),
					-3900, 2685, 1020);
	t = DP_TO_MM(dp_tmp.L077D2) / 990.0;
	arc(-3900, 3705, -3900-(short)(sin(t)*990), 3705-(short)(cos(t)*990),
					-3900, 2715, 990);
}
