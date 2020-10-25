#include "sg_cpu.h"
#include <graph.h>
#include <math.h>
#include "test.h"
#define	DP_TO_MM(dp)	(short)(((long)(dp) * 1925) / 1024)
#define _GBORDER	2	/* draw outline only */
#define _GFILLINTERIOR	3	/* fill using current fill mask */

void	dsp_window1(short pa)
{
	int	n;

	n = pa - PA_MW00;
	setcolor(dp_tmp.mfd_window1[n].W_C);
	dsp_font2(DP_TO_MM(dp_tmp.mfd_window1[n].W_PX),
		DP_TO_MM(dp_tmp.mfd_window1[n].W_PY),
		dp_tmp.mfd_window1[n].W_A, 4);
}

void	dsp_window2(short pa)
{
	int	n;

	n = pa - PA_MW80;
	setcolor(dp_tmp.mfd_window2[n].W_C);
	dsp_font2(DP_TO_MM(dp_tmp.mfd_window2[n].W_PX),
		DP_TO_MM(dp_tmp.mfd_window2[n].W_PY),
		dp_tmp.mfd_window2[n].W_A, 4);
}

void	dsp_M001(short pa)
{
	setcolor(dp_tmp.C001);
	line(-600, 0, -345, 0);
	line(-345, 0, -172, -300);
	line(-172, -300, 0, 0);
	line(0, 0, 172, -300);
	line(172, -300, 345, 0);
	line(345, 0, 600, 0);
}

void	dsp_M002(short pa)
{
	short	py, x1, y1, x2, y2;

	setclip(-4680, -4680, 4680, 4680);

	setcolor(dp_tmp.C002);
	py = -4680 + DP_TO_MM(dp_tmp.LY002);

	x1 = -2520;
	y1 = py;
	rotate(&x1, &y1, dp_tmp.R002);
	x2 = -2520;
	y2 = py - 255;
	rotate(&x2, &y2, dp_tmp.R002);
	line(x1, y1, x2, y2);

	x2 = -615;
	y2 = py;
	rotate(&x2, &y2, dp_tmp.R002);
	line(x1, y1, x2, y2);

	x1 = 2520;
	y1 = py;
	rotate(&x1, &y1, dp_tmp.R002);
	x2 = 2520;
	y2 = py - 255;
	rotate(&x2, &y2, dp_tmp.R002);
	line(x1, y1, x2, y2);

	x2 = 615;
	y2 = py;
	rotate(&x2, &y2, dp_tmp.R002);
	line(x1, y1, x2, y2);

	resetclip();
}

void	dsp_M003A0(short pa){}
void	dsp_M003A1(short pa)
{
	setcolor(dp_tmp.C003A);
	line(-4185, 3500, -4410, 3500);
	line(-4185, 2800, -4410, 2800);
	line(-4185, 2100, -4320, 2100);
	line(-4185, 1400, -4410, 1400);
	line(-4185, 700, -4410, 700);
	line(-4185, 0, -4680, 0);
	line(-4185, -700, -4410, -700);
	line(-4185, -1400, -4410, -1400);
	line(-4185, -2100, -4320, -2100);
	line(-4185, -2800, -4410, -2800);
	line(-4185, -3500, -4410, -3500);

	dsp_str(-4618, 2280, "3", 7);
	dsp_str(-4618, -1919, "3", 7);
}

void	dsp_M003B0(short pa){}
void	dsp_M003B1(short pa)
{
	short	y;
	struct	xycoord	coord[8];

	setcolor(dp_tmp.C003B);

	y = DP_TO_MM(dp_tmp.PY003B);
	coord[0].xcoord = -4175;
	coord[0].ycoord = y+45;
	coord[1].xcoord = -3935;
	coord[1].ycoord = y+45;
	coord[2].xcoord = -3935;
	coord[2].ycoord = y+165;
	coord[3].xcoord = -3845;
	coord[3].ycoord = y+165;
	coord[4].xcoord = -3845;
	coord[4].ycoord = y-165;
	coord[5].xcoord = -3935;
	coord[5].ycoord = y-165;
	coord[6].xcoord = -3935;
	coord[6].ycoord = y-45;
	coord[7].xcoord = -4175;
	coord[7].ycoord = y-45;
	polygon(coord, 8, _GFILLINTERIOR);
}

void	dsp_M004A0(short pa){}
void	dsp_M004A1(short pa)
{
	setcolor(dp_tmp.C004A);
	line(-3500, -4185, -3500, -4410);
	line(-2800, -4185, -2800, -4410);
	line(-2100, -4185, -2100, -4320);
	line(-1400, -4185, -1400, -4410);
	line(-700, -4185, -700, -4410);
	line(0, -4185, 0, -4680);
	line(700, -4185, 700, -4410);
	line(1400, -4185, 1400, -4410);
	line(2100, -4185, 2100, -4320);
	line(2800, -4185, 2800, -4410);
	line(3500, -4185, 3500, -4410);
}

void	dsp_M004B0(short pa){}
void	dsp_M004B1(short pa)
{
	short	x;
	struct	xycoord	coord[8];

	setcolor(dp_tmp.C004B);

	x = DP_TO_MM(dp_tmp.PX004B);
	coord[0].ycoord = -4175;
	coord[0].xcoord = x+45;
	coord[1].ycoord = -3935;
	coord[1].xcoord = x+45;
	coord[2].ycoord = -3935;
	coord[2].xcoord = x+165;
	coord[3].ycoord = -3845;
	coord[3].xcoord = x+165;
	coord[4].ycoord = -3845;
	coord[4].xcoord = x-165;
	coord[5].ycoord = -3935;
	coord[5].xcoord = x-165;
	coord[6].ycoord = -3935;
	coord[6].xcoord = x-45;
	coord[7].ycoord = -4175;
	coord[7].xcoord = x-45;
	polygon(coord, 8, _GFILLINTERIOR);
}

void	dsp_M0050(short pa){}
void	dsp_M0051(short pa)
{
	setcolor(dp_tmp.C005);

	line(-1785, -1785, 1785, 1785);
	line(-1785, 1785, 1785, -1785);
}

void	dsp_M006(short pa)
{
	short	x, y, r;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C006);
	x = DP_TO_MM(dp_tmp.PX006);
	y = DP_TO_MM(dp_tmp.PY006);
	r = DP_TO_MM(dp_tmp.R006);
	circle(x, y+r, r, _GBORDER);
	resetclip();
}

void	dsp_M006B(short pa)
{
	short	x, y, r;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C006);
	x = DP_TO_MM(dp_tmp.PX006);
	y = DP_TO_MM(dp_tmp.PY006);
	r = DP_TO_MM(dp_tmp.R006/2);
	circle(x, y+r, r, _GBORDER);
	resetclip();
}

void	dsp_M007(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C007);
	x = DP_TO_MM(dp_tmp.PX007);
	y = DP_TO_MM(dp_tmp.PY007);
	box(x-60, y-60, x+60, y+60, _GFILLINTERIOR);
	resetclip();
}

void	dsp_M008A(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C008A);
	x = DP_TO_MM(dp_tmp.PX008A);
	y = DP_TO_MM(dp_tmp.PY008A);
	box(x-465, y-45, x, y+45, _GFILLINTERIOR);
	resetclip();
}

void	dsp_M008B(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C008B);
	x = DP_TO_MM(dp_tmp.PX008B);
	y = DP_TO_MM(dp_tmp.PY008B);
	box(x-465, y-45, x, y+45, _GFILLINTERIOR);
	resetclip();
}

void	dsp_M008C(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C008C);
	x = DP_TO_MM(dp_tmp.PX008C);
	y = DP_TO_MM(dp_tmp.PY008C);
	box(x-465, y-45, x, y+45, _GFILLINTERIOR);
	resetclip();
}

void	dsp_M0090(short pa){}
void	dsp_M0091(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C009);
	x = DP_TO_MM(dp_tmp.PX009);
	y = DP_TO_MM(dp_tmp.PY009);
	line(x-210, y-225, x-210, y+225);
	line(x+210, y-225, x+210, y+225);
	resetclip();
}

void	dsp_M010(short pa)
{
	setcolor(dp_tmp.C010);
	line(-3495, 2340, 3495, 2340);
	line(-3495, 0, -690, 0);
	line(3495, 0, 690, 0);
	line(-3495, -2340, 3495, -2340);
	line(-2100, 3885, -2100, -3885);
	line(0, 3885, 0, 360);
	line(0, -3885, 0, -360);
	line(2100, 3885, 2100, -3885);
}

void	dsp_M011(short pa)
{
	setcolor(dp_tmp.C011);
	line(4680, 2355, 4095, 2355);
	line(4680, 0, 4095, 0);
	line(4680, -2355, 4095, -2355);
}

void	dsp_M012(short pa)
{
	short	x, y, x2, y2, len;
	double	a;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C012);
	x = DP_TO_MM(dp_tmp.PX012);
	y = DP_TO_MM(dp_tmp.PY012);
	len = DP_TO_MM(dp_tmp.L012);
	a = (double)dp_tmp.R012 / 32768 * PI;
	x2 = (short)(cos(a) * len) + x;
	y2 = (short)(sin(a) * len) + y;
	line(x, y, x2, y2);
	resetclip();
}

void	dsp_M0130(short pa){}
void	dsp_M0131(short pa)
{
	setcolor(dp_tmp.C013);

	box(-4680, -4680, 4680, 4680, _GBORDER);
}

void	dsp_M014A(short pa)
{
	setcolor(dp_tmp.C014);
	arc(0, -4680, 2026, -3510, -2026, -3510, 2340);
}

void	dsp_M014B(short pa)
{
	setcolor(dp_tmp.C014);
	arc(0, -4680, 4052, -2340, -4052, -2340, 4680);
}

void	dsp_M014C(short pa)
{
	setcolor(dp_tmp.C014);
	arc(0, -4680, 4680, 552, -4680, 552, 7020);
}

void	dsp_M015(short pa)
{
	setcolor(dp_tmp.C015);
	line(-4680, 0, 4680, 0);
}

void	dsp_M016(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	x = DP_TO_MM(dp_tmp.PX016);
	y = DP_TO_MM(dp_tmp.PY016);
	setcolor(dp_tmp.C016);
	line(x, 4680, x, -4680);
	line(-4680, y, 4680, y);
	resetclip();
}

void	dsp_M017A(short pa)
{
	setcolor(dp_tmp.C017);
	line(4300, -2350, 4300, -2650);
}

void	dsp_M017B(short pa)
{
	setcolor(dp_tmp.C017);
	line(4255, -2350, 4255, -2650);
	line(4345, -2350, 4345, -2650);
}

void	dsp_M017C(short pa)
{
	setcolor(dp_tmp.C017);
	line(4210, -2350, 4210, -2650);
	line(4300, -2350, 4300, -2650);
	line(4390, -2350, 4390, -2650);
}

void	dsp_M017D(short pa)
{
	setcolor(dp_tmp.C017);
	line(4165, -2350, 4165, -2650);
	line(4255, -2350, 4255, -2650);
	line(4345, -2350, 4345, -2650);
	line(4435, -2350, 4435, -2650);
}

void	dsp_MB18(short pa){}
void	dsp_M018(short pa)
{
	short	x, y;
	int	n;
	struct	xycoord	coord[4];

	n = (pa - PA_M018A0) / 2;
	setcolor(dp_tmp.fcr_target[n].C018);
	if (dp_tmp.fcr_target[n].R018 == 0) {
		x = DP_TO_MM(dp_tmp.fcr_target[n].PX018);
		y = DP_TO_MM(dp_tmp.fcr_target[n].PY018);
		box(x-105, y-105, x+105, y+105, _GFILLINTERIOR);
	} else {
		x = DP_TO_MM(dp_tmp.fcr_target[n].PX018);
		y = DP_TO_MM(dp_tmp.fcr_target[n].PY018);
		coord[0].xcoord = x;
		coord[0].ycoord = y + 150;
		coord[1].xcoord = x + 150;
		coord[1].ycoord = y;
		coord[2].xcoord = x;
		coord[2].ycoord = y - 150;
		coord[3].xcoord = x - 150;
		coord[3].ycoord = y;
		polygon(coord, 4, _GFILLINTERIOR);
	}
}

void	dsp_M019(short pa)
{
	short	x, y;
	struct	xycoord	coord[12];

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C019);
	x = DP_TO_MM(dp_tmp.PX019);
	y = DP_TO_MM(dp_tmp.PY019);
	coord[0].xcoord = x + 60;
	coord[0].ycoord = y + 150;
	coord[1].xcoord = x + 60;
	coord[1].ycoord = y + 60;
	coord[2].xcoord = x + 150;
	coord[2].ycoord = y + 60;
	coord[3].xcoord = x + 150;
	coord[3].ycoord = y - 60;
	coord[4].xcoord = x + 60;
	coord[4].ycoord = y - 60;
	coord[5].xcoord = x + 60;
	coord[5].ycoord = y - 150;
	coord[6].xcoord = x - 60;
	coord[6].ycoord = y - 150;
	coord[7].xcoord = x - 60;
	coord[7].ycoord = y - 60;
	coord[8].xcoord = x - 150;
	coord[8].ycoord = y - 60;
	coord[9].xcoord = x - 150;
	coord[9].ycoord = y + 60;
	coord[10].xcoord = x - 60;
	coord[10].ycoord = y + 60;
	coord[11].xcoord = x - 60;
	coord[11].ycoord = y + 150;
	polygon(coord, 12, _GFILLINTERIOR);
	resetclip();
}

void	dsp_M020(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C020);
	x = DP_TO_MM(dp_tmp.PX020);
	y = DP_TO_MM(dp_tmp.PY020);
	line(x-150, y, x+150, y);
	line(x, y-150, x, y+150);
	line(x-60, y+150, x+60, y+150);
	line(x+150, y+60, x+150, y-60);
	line(x-60, y-150, x+60, y-150);
	line(x-150, y+60, x-150, y-60);
	resetclip();
}

void	dsp_M0210(short pa){}
void	dsp_M0211(short pa)
{
	short	x, y;
	struct	xycoord	coord[12];

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C021);
	x = DP_TO_MM(dp_tmp.PX021);
	y = DP_TO_MM(dp_tmp.PY021);
	coord[0].xcoord = x - 45;
	coord[0].ycoord = y + 150;
	coord[1].xcoord = x + 45;
	coord[1].ycoord = y + 150;
	coord[2].xcoord = x + 45;
	coord[2].ycoord = y + 75;
	coord[3].xcoord = x + 180;
	coord[3].ycoord = y + 75;
	coord[4].xcoord = x + 180;
	coord[4].ycoord = y + 15;
	coord[5].xcoord = x + 255;
	coord[5].ycoord = y + 15;
	coord[5].xcoord = x + 255;
	coord[5].ycoord = y - 15;
	coord[6].xcoord = x + 180;
	coord[6].ycoord = y - 15;
	coord[7].xcoord = x + 180;
	coord[7].ycoord = y - 15;
	coord[8].xcoord = x + 180;
	coord[8].ycoord = y - 75;
	coord[9].xcoord = x + 45;
	coord[9].ycoord = y - 75;
	coord[10].xcoord = x + 45;
	coord[10].ycoord = y - 150;
	coord[11].xcoord = x - 45;
	coord[11].ycoord = y - 150;
	polygon(coord, 12, _GFILLINTERIOR);
	resetclip();
}

void	dsp_M022(short pa)
{
	short	x, y;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C022);
	x = DP_TO_MM(dp_tmp.PX022);
	y = DP_TO_MM(dp_tmp.PY022);
	circle(x, y, 195, _GBORDER);
	resetclip();
}

void	dsp_M0230(short pa){}
void	dsp_M0231(short pa)
{
	setcolor(dp_tmp.C023);
	box(-3510-105, 4095-105, -3510+105, 4095+105, _GBORDER);
	box(-3510-105, 3510-105, -3510+105, 3510+105, _GBORDER);
	box(-3510-105, 2925-105, -3510+105, 2925+105, _GBORDER);
}

void	dsp_M0240(short pa){}
void	dsp_M0241(short pa)
{
	short	x;
	int	i;

	setclip(-4680, -4680, 4680, 4680);
	setcolor(dp_tmp.C024);
	x = DP_TO_MM(dp_tmp.PX024);
	for (i = 0; i < 8; i++)
		box(x-105, 3990-i*1140-105, x+105, 3990-i*1140+105, _GBORDER);
	resetclip();
}
