#include "sg_cpu.h"
#include <graph.h>
#include <math.h>
#include "test.h"
#define	DP_TO_MM(dp)	(short)(((long)(dp) * 1925) / 1024)
#define _GBORDER	2	/* draw outline only */
#define _GFILLINTERIOR	3	/* fill using current fill mask */

void	dsp_M041A(short pa)
{
	setcolor(dp_tmp.C041);
	line(-195, 0, 195, 0);
	line(0, -195, 0, 195);
}

void	dsp_M041B(short pa)
{
	setcolor(dp_tmp.C041);
	line(-45, 0, -215, 0);
	line(45, 0, 215, 0);
	line(0, -45, 0, -215);
	line(0, 45, 0, 215);
}

void	dsp_M0420(short pa){}
void	dsp_M0421(short pa)
{
	setcolor(dp_tmp.C042);
	circle(0, 0, 4680, _GBORDER);
}

void	dsp_M043(short pa)
{
	short	len, i, x, y;
	double	angle;

	setcolor(dp_tmp.C043);
	len = DP_TO_MM(dp_tmp.L043);
	for (i = 0; i < 12; i++) {
		angle = 2 * PI * i / 12;
		x = len * cos(angle);
		y = len * sin(angle);
		circle(x, y, 90, _GFILLINTERIOR);
	}
}

void	dsp_M044(short pa)
{
	setcolor(dp_tmp.C044);
	circle(0, 0, 500, _GBORDER);
}
