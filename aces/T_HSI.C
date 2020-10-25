#include "sg_cpu.h"
#include <graph.h>
#include <math.h>
#include "test.h"
#define	DP_TO_MM(dp)	(short)(((long)(dp) * 1925) / 1024)
#define _GBORDER	2	/* draw outline only */
#define _GFILLINTERIOR	3	/* fill using current fill mask */

void	dsp_M051A(short pa)
{
	struct	xycoord	coord[5];
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C051A0);
	coord[0].xcoord = 0;
	coord[0].ycoord = 4200;
	coord[1].xcoord = 285;
	coord[1].ycoord = 4035;
	coord[2].xcoord = 285;
	coord[2].ycoord = 3510;
	coord[3].xcoord = -285;
	coord[3].ycoord = 3510;
	coord[4].xcoord = -285;
	coord[4].ycoord = 4035;
	rotate2(coord, 5, dp_tmp.R051);
	polygon(coord, 5, _GBORDER);
	x1 = 0;
	y1 = 3510;
	rotate(&x1, &y1, dp_tmp.R051);
	x2 = 0;
	y2 = 3300;
	rotate(&x2, &y2, dp_tmp.R051);
	line(x1, y1, x2, y2);

	setcolor(dp_tmp.C051A1);
	x1 = 0;
	y1 = -3300;
	rotate(&x1, &y1, dp_tmp.R051);
	x2 = 0;
	y2 = -3765;
	rotate(&x2, &y2, dp_tmp.R051);
	line(x1, y1, x2, y2);
}

void	dsp_M051B(short pa)
{
	short	x, y;

	setcolor(dp_tmp.C051B);
	x = 0;
	y = 3772;
	rotate(&x, &y, dp_tmp.R051);
	dsp_font(x-191, y+120, dp_tmp.A051B, 2);
}

void	dsp_M052A(short pa)
{
	struct	xycoord	coord[7];

	setcolor(dp_tmp.C052A0);
	coord[0].xcoord = 750;
	coord[0].ycoord = 4560;
	coord[1].xcoord = 750;
	coord[1].ycoord = 3975;
	coord[2].xcoord = 150;
	coord[2].ycoord = 3975;
	coord[3].xcoord = 0;
	coord[3].ycoord = 3795;
	coord[4].xcoord = -150;
	coord[4].ycoord = 3975;
	coord[5].xcoord = -750;
	coord[5].ycoord = 3975;
	coord[6].xcoord = -750;
	coord[6].ycoord = 4560;
	polygon(coord, 7, _GBORDER);
	line(0, 3795, 0, 3300);

	setcolor(dp_tmp.C052A1);
	dsp_font(-575, 4418, dp_tmp.A052A, 3);
	_outgtext("Ò");
}

void	dsp_M052B(short pa)
{
	setcolor(dp_tmp.C052B);
	line(2333, 2333, 2492, 2492);
	line(3300, 0, 3525, 0);
	line(2333, -2333, 2492, -2492);
	line(0, -3300, 0, -3525);
	line(-2333, -2333, -2492, -2492);
	line(-3300, 0, -3525, 0);
	line(-2333, 2333, -2492, 2492);
}

void	dsp_M052C(short pa)
{
	static	int	angle[] = {
		10, 20, 40, 50, 70, 80,
		100, 110, 130, 140, 160, 170,
		190, 200, 220, 230, 250, 260,
		280, 290, 310, 320, 340, 350
	};
	int	i;
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C052C);
	for (i = 0; i < 24; i++) {
		x1 = 3300 * sin(angle[i]/180.0*PI);
		y1 = 3300 * cos(angle[i]/180.0*PI);
		rotate(&x1, &y1, dp_tmp.R052C);
		x2 = 2550 * sin(angle[i]/180.0*PI);
		y2 = 2550 * cos(angle[i]/180.0*PI);
		rotate(&x2, &y2, dp_tmp.R052C);
		line(x1, y1, x2, y2);
	}
}

void	dsp_M052D(short pa)
{
	int	i;
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C052C);
	for (i = 0; i < 36; i++) {
		x1 = 3015 * sin((5+i*10)/180.0*PI);
		y1 = 3015 * cos((5+i*10)/180.0*PI);
		rotate(&x1, &y1, dp_tmp.R052C);
		x2 = 2835 * sin((5+i*10)/180.0*PI);
		y2 = 2835 * cos((5+i*10)/180.0*PI);
		rotate(&x2, &y2, dp_tmp.R052C);
		line(x1, y1, x2, y2);
	}
}

void	dsp_M052E(short pa)
{
	int	i;
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C052C);
	for (i = 0; i < 12; i++) {
		x1 = 2685 * sin((i*30)/180.0*PI);
		y1 = 2685 * cos((i*30)/180.0*PI);
		rotate(&x1, &y1, dp_tmp.R052C);
		x2 = 2550 * sin((i*30)/180.0*PI);
		y2 = 2550 * cos((i*30)/180.0*PI);
		rotate(&x2, &y2, dp_tmp.R052C);
		line(x1, y1, x2, y2);
	}
}

void	dsp_M052F(short pa)
{
	static	char	*heading[] = {
		"N", " 3", " 6", "E", "12", "15",
		"S", "21", "24", "W", "30", "33"
	};
	int	i, size;
	short	x, y;

	setcolor(dp_tmp.C052C);
	for (i = 0; i < 12; i++) {
		x = 2925 * sin((i*30)/180.0*PI);
		y = 2925 * cos((i*30)/180.0*PI);
		rotate(&x, &y, dp_tmp.R052C);
		if (strlen(heading[i]) == 1) {
			x -= 118;
			y += 180;
			size = 7;
		} else {
			x -= 191;
			y += 120;
			size = 6;
		}
		dsp_str(x, y, heading[i], size);
	}
}

void	dsp_M052G(short pa)
{
	int	i;
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C052C);
	for (i = 0; i < 12; i++) {
		x1 = 3300 * sin((i*30)/180.0*PI);
		y1 = 3300 * cos((i*30)/180.0*PI);
		rotate(&x1, &y1, dp_tmp.R052C);
		x2 = 3165 * sin((i*30)/180.0*PI);
		y2 = 3165 * cos((i*30)/180.0*PI);
		rotate(&x2, &y2, dp_tmp.R052C);
		line(x1, y1, x2, y2);
	}
}

void	dsp_M053(short pa)
{
	struct	xycoord	coord[4];
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C053);
	coord[0].xcoord = 225;
	coord[0].ycoord = 3540;
	coord[1].xcoord = 360;
	coord[1].ycoord = 3300;
	coord[2].xcoord = -360;
	coord[2].ycoord = 3300;
	coord[3].xcoord = -225;
	coord[3].ycoord = 3540;
	rotate2(coord, 4, dp_tmp.R053);
	polygon(coord, 4, _GBORDER);
	x1 = 0;
	y1 = 3540;
	rotate(&x1, &y1, dp_tmp.R053);
	x2 = 0;
	y2 = 3300;
	rotate(&x2, &y2, dp_tmp.R053);
	line(x1, y1, x2, y2);
}

void	dsp_M054(short pa)
{
	struct	xycoord	coord[7];

	setcolor(dp_tmp.C0540);
	coord[0].xcoord = 0;
	coord[0].ycoord = 2550;
	coord[1].xcoord = 180;
	coord[1].ycoord = 2190;
	coord[2].xcoord = 90;
	coord[2].ycoord = 2190;
	coord[3].xcoord = 90;
	coord[3].ycoord = 1095;
	coord[4].xcoord = -90;
	coord[4].ycoord = 1095;
	coord[5].xcoord = -90;
	coord[5].ycoord = 2190;
	coord[6].xcoord = -180;
	coord[6].ycoord = 2190;
	rotate2(coord, 7, dp_tmp.R054);
	polygon(coord, 7, _GFILLINTERIOR);

	setcolor(dp_tmp.C0541);
	coord[0].xcoord = 90;
	coord[0].ycoord = -1200;
	coord[1].xcoord = 90;
	coord[1].ycoord = -2550;
	coord[2].xcoord = -90;
	coord[2].ycoord = -2550;
	coord[3].xcoord = -90;
	coord[3].ycoord = -1200;
	rotate2(coord, 4, dp_tmp.R054);
	polygon(coord, 4, _GFILLINTERIOR);
}

void	dsp_M055(short pa)
{
	struct	xycoord	coord[4];
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C055);
	coord[0].xcoord = 0;
	coord[0].ycoord = 2550;
	coord[1].xcoord = 210;
	coord[1].ycoord = 2055;
	coord[2].xcoord = 0;
	coord[2].ycoord = 1935;
	coord[3].xcoord = -210;
	coord[3].ycoord = 2055;
	rotate2(coord, 4, dp_tmp.R055);
	polygon(coord, 4, _GFILLINTERIOR);
	x1 = 0;
	y1 = 1935;
	rotate(&x1, &y1, dp_tmp.R055);
	x2 = 0;
	y2 = 1560;
	rotate(&x2, &y2, dp_tmp.R055);
	line(x1, y1, x2, y2);
}

void	dsp_M056A0(short pa)
{
	setcolor(dp_tmp.C056A);
	box(4150, -2490, 4600, 2490, _GBORDER);
}

void	dsp_M056A1(short pa)
{
	setcolor(dp_tmp.C056A);
	circle(4375, 2070, 90, _GFILLINTERIOR);
}

void	dsp_M056A2(short pa)
{
	setcolor(dp_tmp.C056A);
	circle(4375, 1035, 90, _GFILLINTERIOR);

}

void	dsp_M056A3(short pa)
{
	setcolor(dp_tmp.C056A);
	circle(4375, 0, 90, _GFILLINTERIOR);
}

void	dsp_M056A4(short pa)
{
	setcolor(dp_tmp.C056A);
	circle(4375, -1035, 90, _GFILLINTERIOR);
}

void	dsp_M056A5(short pa)
{
	setcolor(dp_tmp.C056A);
	circle(4375, -2070, 90, _GFILLINTERIOR);
}

void	dsp_M056B0(short pa){}
void	dsp_M056B1(short pa)
{
	short	y;

	setcolor(dp_tmp.C056B);
	y = -2490 + DP_TO_MM(dp_tmp.L056B);
	triangle(4375, y, 4600, y+112, 4600, y-112, _GFILLINTERIOR);
}

void	dsp_M057A0(short pa){}
void	dsp_M057A1(short pa)
{
	struct	xycoord	coord[3];
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C057A0);
	coord[0].xcoord = 0;
	coord[0].ycoord = 4050;
	coord[1].xcoord = 420;
	coord[1].ycoord = 3555;
	coord[2].xcoord = -420;
	coord[2].ycoord = 3555;
	rotate2(coord, 3, dp_tmp.R057);
	polygon(coord, 3, _GBORDER);
	x1 = 0;
	y1 = 3555;
	rotate(&x1, &y1, dp_tmp.R057);
	x2 = 0;
	y2 = 3300;
	rotate(&x2, &y2, dp_tmp.R057);
	line(x1, y1, x2, y2);

	setcolor(dp_tmp.C057A1);
	x1 = 0;
	y1 = -3300;
	rotate(&x1, &y1, dp_tmp.R057);
	x2 = 0;
	y2 = -3675;
	rotate(&x2, &y2, dp_tmp.R057);
	line(x1, y1, x2, y2);
}

void	dsp_M057B(short pa)
{
	short	x, y;

	setcolor(dp_tmp.C057B);
	x = 0;
	y = 3700;
	rotate(&x, &y, dp_tmp.R057);
	dsp_str(x-79, y+120, "T", 6);
}

void	dsp_M058A0(short pa)
{
	setcolor(dp_tmp.C058A);
	box(-2490, -4130, 2490, -3680, _GBORDER);
}

void	dsp_M058A1(short pa)
{
	setcolor(dp_tmp.C058A);
	circle(-2070, -3905, 90, _GFILLINTERIOR);
}

void	dsp_M058A2(short pa)
{
	setcolor(dp_tmp.C058A);
	circle(-1035, -3905, 90, _GFILLINTERIOR);
}

void	dsp_M058A3(short pa)
{
	setcolor(dp_tmp.C058A);
	circle(0, -3905, 90, _GFILLINTERIOR);
}

void	dsp_M058A4(short pa)
{
	setcolor(dp_tmp.C058A);
	circle(1035, -3905, 90, _GFILLINTERIOR);
}

void	dsp_M058A5(short pa)
{
	setcolor(dp_tmp.C058A);
	circle(2070, -3905, 90, _GFILLINTERIOR);
}

void	dsp_M058B0(short pa){}
void	dsp_M058B1(short pa)
{
	short	x;

	setcolor(dp_tmp.C058B);
	x = -2490 + DP_TO_MM(dp_tmp.L058B);
	triangle(x, -3905, x+112, -4130, x-112, -4130, _GFILLINTERIOR);
}

void	dsp_M059A0(short pa){}
void	dsp_M059A1(short pa)
{
	short	x;
	struct	xycoord	coord[4];

	setcolor(dp_tmp.C059A);
	x = -1530 + DP_TO_MM(dp_tmp.LX059A);
	coord[0].xcoord = x + 90;
	coord[0].ycoord = 1155;
	coord[1].xcoord = x + 90;
	coord[1].ycoord = -1155;
	coord[2].xcoord = x - 90;
	coord[2].ycoord = -1155;
	coord[3].xcoord = x - 90;
	coord[3].ycoord = 1155;
	rotate2(coord, 4, dp_tmp.R054);
	polygon(coord, 4, _GFILLINTERIOR);
}

void	dsp_M059B0(short pa)
{
	short	x, y;

	setcolor(dp_tmp.C059B);
	x = -1530;
	y = 0;
	rotate(&x, &y, dp_tmp.R054);
	circle(x, y, 90, _GFILLINTERIOR);
}

void	dsp_M059B1(short pa)
{
	short	x, y;

	setcolor(dp_tmp.C059B);
	x = -765;
	y = 0;
	rotate(&x, &y, dp_tmp.R054);
	circle(x, y, 90, _GFILLINTERIOR);
}

void	dsp_M059B2(short pa)
{
	short	x, y;

	setcolor(dp_tmp.C059B);
	x = 765;
	y = 0;
	rotate(&x, &y, dp_tmp.R054);
	circle(x, y, 90, _GFILLINTERIOR);
}

void	dsp_M059B3(short pa)
{
	short	x, y;

	setcolor(dp_tmp.C059B);
	x = 1530;
	y = 0;
	rotate(&x, &y, dp_tmp.R054);
	circle(x, y, 90, _GFILLINTERIOR);
}

void	dsp_M060(short pa)
{
	struct	xycoord	coord[20];

	setcolor(dp_tmp.C060);
	coord[0].xcoord = 0;
	coord[0].ycoord = 585;
	coord[1].xcoord = 0;
	coord[1].ycoord = 465;
	coord[2].xcoord = 120;
	coord[2].ycoord = 90;
	coord[3].xcoord = 540;
	coord[3].ycoord = 0;
	coord[4].xcoord = 570;
	coord[4].ycoord = -75;
	coord[5].xcoord = 120;
	coord[5].ycoord = -105;
	coord[6].xcoord = 120;
	coord[6].ycoord = -180;
	coord[7].xcoord = 300;
	coord[7].ycoord = -240;
	coord[8].xcoord = 330;
	coord[8].ycoord = -315;
	coord[9].xcoord = 30;
	coord[9].ycoord = -315;
	coord[10].xcoord = 0;
	coord[10].ycoord = -390;
	coord[11].xcoord = -30;
	coord[11].ycoord = -315;
	coord[12].xcoord = -330;
	coord[12].ycoord = -315;
	coord[13].xcoord = -300;
	coord[13].ycoord = -240;
	coord[14].xcoord = -120;
	coord[14].ycoord = -180;
	coord[15].xcoord = -120;
	coord[15].ycoord = -105;
	coord[16].xcoord = -570;
	coord[16].ycoord = -75;
	coord[17].xcoord = -540;
	coord[17].ycoord = 0;
	coord[18].xcoord = -120;
	coord[18].ycoord = 90;
	coord[19].xcoord = 0;
	coord[19].ycoord = 465;
	polygon(coord, 20, _GFILLINTERIOR);
}

void	dsp_M0610(short pa){}
void	dsp_M0611(short pa)
{
	struct	xycoord	coord[3];
	short	x1, y1, x2, y2;

	setcolor(dp_tmp.C061);
	coord[0].xcoord = 0;
	coord[0].ycoord = 2550;
	coord[1].xcoord = 195;
	coord[1].ycoord = 2055;
	coord[2].xcoord = -195;
	coord[2].ycoord = 2055;
	rotate2(coord, 3, dp_tmp.R061);
	polygon(coord, 3, _GFILLINTERIOR);
	x1 = 0;
	y1 = 2055;
	rotate(&x1, &y1, dp_tmp.R061);
	x2 = 0;
	y2 = 1560;
	rotate(&x2, &y2, dp_tmp.R061);
	line(x1, y1, x2, y2);
}

void	dsp_M062A(short pa)
{
	short	i, y;

	setcolor(dp_tmp.C062A0);
	for (i = 0; i < 4; i++)
		box(-3945, 4500-i*210, -3945+90, 4500-i*210+90, _GBORDER);
	for (i = 0; i < 4; i++)
		box(-3945, 3450-i*210, -3945+90, 3450-i*210+90, _GBORDER);
	setcolor(dp_tmp.C062A);
	y = 2820 + DP_TO_MM(dp_tmp.L062A);
	line(-4035, y, -3765, y);
}

void	dsp_M062B(short pa)
{
	short	i, x;

	setcolor(dp_tmp.C062B0);
	for (i = 0; i < 4; i++)
		box(-4785+i*210, 3660, -4785+i*210+90, 3660+90, _GBORDER);
	for (i = 0; i < 4; i++)
		box(-3735+i*210, 3660, -3735+i*210+90, 3660+90, _GBORDER);
	setcolor(dp_tmp.C062B);
	x = -4785 + DP_TO_MM(dp_tmp.L062B);
	line(x, 3840, x, 3570);
}

void	dsp_M062C(short pa)
{
	setcolor(dp_tmp.C062C);
	arc(-3900, 3705, -4727, 3010, -3073, 3010, 1080);
	line(-4727, 3010, -4784, 2962);
	line(-4356, 2726, -4388, 2658);
	line(-3900, 2625, -3900, 2550);
	line(-3443, 2726, -3411, 2658);
	line(-3072, 3010, -3015, 2962);
}

void	dsp_M062D(short pa)
{
	double	t;

	setcolor(dp_tmp.C062D);
	t = DP_TO_MM(dp_tmp.L062D0) / 1050.0;
	arc(-3900, 3705, -3900, 2655, -3900+(short)(sin(t)*1050),
			3705-(short)(cos(t)*1050), 1050);
	t = DP_TO_MM(dp_tmp.L062D1) / 1020.0;
	arc(-3900, 3705, -3900, 2685, -3900+(short)(sin(t)*1020),
			3705-(short)(cos(t)*1020), 1020);
	t = DP_TO_MM(dp_tmp.L062D2) / 990.0;
	arc(-3900, 3705, -3900, 2715, -3900+(short)(sin(t)*990),
			3705-(short)(cos(t)*990), 990);
}

void	dsp_M062E(short pa)
{
	double	t;

	setcolor(dp_tmp.C062D);
	t = DP_TO_MM(dp_tmp.L062D0) / 1050.0;
	arc(-3900, 3705, -3900-(short)(sin(t)*1050), 3705-(short)(cos(t)*1050),
					-3900, 2655, 1050);
	t = DP_TO_MM(dp_tmp.L062D1) / 1020.0;
	arc(-3900, 3705, -3900-(short)(sin(t)*1020), 3705-(short)(cos(t)*1020),
					-3900, 2685, 1020);
	t = DP_TO_MM(dp_tmp.L062D2) / 990.0;
	arc(-3900, 3705, -3900-(short)(sin(t)*990), 3705-(short)(cos(t)*990),
					-3900, 2715, 990);
}
