#include "sg_cpu.h"
#include <graph.h>
#include <math.h>
#include "test.h"
#define	DP_TO_MM(dp)	(short)(((long)(dp) * 1925) / 1024)
#define _GBORDER	2	/* draw outline only */
#define _GFILLINTERIOR	3	/* fill using current fill mask */

void	dsp_TEST1(short pa)
{
}

void	dsp_TEST2(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-5400, -5400, 5400, 5400, _GFILLINTERIOR);
}

void	dsp_TEST3(short pa)
{
	static	ushort	color[16] = {
		COLOR_BLACK, COLOR_BLUE, COLOR_RED1, COLOR_RED2,
		COLOR_MAGENTA, COLOR_BROWN, COLOR_ORANGE, COLOR_AMBER,
		COLOR_CYAN, COLOR_GREEN1, COLOR_GREEN2, COLOR_GREEN3,
		COLOR_GREEN4, COLOR_YELLOW, COLOR_WHITE1, COLOR_WHITE2
	};
	short	i;

	for (i = 0; i < 16; i++) {
		setcolor(color[i]);
		box(-6345 + i * 793, 6345, -6345 + (i+1) * 793, -6345,
							_GFILLINTERIOR);
	}
}

void	dsp_TEST4(short pa)
{
	int	i;

	setcolor(COLOR_WHITE1);
	for (i = -4; i <= 4; i++) {
		line(-5400, i*1080, 5400, i*1080);
		line(i*1080, -5400, i*1080, 5400);
	}
}

void	dsp_TEST7A(short pa)
{
	setcolor(COLOR_WHITE1);
	dsp_font(DP_TO_MM(dp_tmp.PX70), DP_TO_MM(dp_tmp.PY70), dp_tmp.T70A, 2);
}

void	dsp_TEST7B(short pa)
{
	setcolor(COLOR_WHITE1);
	dsp_font(DP_TO_MM(dp_tmp.PX71), DP_TO_MM(dp_tmp.PY71), dp_tmp.T71A, 2);
}

void	dsp_TEST51(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-1620*2 - 500, 4500 - 500, -1620*2 + 500, 4500 + 500, _GBORDER);
}

void	dsp_TEST52(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-1620 - 500, 4500 - 500, -1620 + 500, 4500 + 500, _GBORDER);
}

void	dsp_TEST53(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-500, 4500 - 500, 500, 4500 + 500, _GBORDER);
}

void	dsp_TEST54(short pa)
{
	setcolor(COLOR_WHITE1);
	box(1620 - 500, 4500 - 500, 1620 + 500, 4500 + 500, _GBORDER);
}

void	dsp_TEST55(short pa)
{
	setcolor(COLOR_WHITE1);
	box(1620*2 - 500, 4500 - 500, 1620*2 + 500, 4500 + 500, _GBORDER);
}

void	dsp_TEST56(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, 1620*2 - 500, 4500 + 500, 1620*2 + 500, _GBORDER);
}

void	dsp_TEST57(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, 1620 - 500, 4500 + 500, 1620 + 500, _GBORDER);
}

void	dsp_TEST58(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, -500, 4500 + 500, 500, _GBORDER);
}

void	dsp_TEST59(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, -1620 - 500, 4500 + 500, -1620 + 500, _GBORDER);
}

void	dsp_TEST510(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, -1620*2 - 500, 4500 + 500, -1620*2 + 500, _GBORDER);
}

void	dsp_TEST511(short pa)
{
	setcolor(COLOR_WHITE1);
	box(1620*2 - 500, -4500 - 500, 1620*2 + 500, -4500 + 500, _GBORDER);
}

void	dsp_TEST512(short pa)
{
	setcolor(COLOR_WHITE1);
	box(1620 - 500, -4500 - 500, 1620 + 500, -4500 + 500, _GBORDER);
}

void	dsp_TEST513(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-500, -4500 - 500, 500, -4500 + 500, _GBORDER);
}

void	dsp_TEST514(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-1620 - 500, -4500 - 500, -1620 + 500, -4500 + 500, _GBORDER);
}

void	dsp_TEST515(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-1620*2 - 500, -4500 - 500, -1620*2 + 500, -4500 + 500, _GBORDER);
}

void	dsp_TEST516(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, -1620*2 - 500, -4500 + 500, -1620*2 + 500, _GBORDER);
}

void	dsp_TEST517(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, -1620 - 500, -4500 + 500, -1620 + 500, _GBORDER);
}

void	dsp_TEST518(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, -500, -4500 + 500, 500, _GBORDER);
}

void	dsp_TEST519(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, 1620 - 500, -4500 + 500, 1620 + 500, _GBORDER);
}

void	dsp_TEST520(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, 1620*2 - 500, -4500 + 500, 1620*2 + 500, _GBORDER);
}

void	dsp_TEST521(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, 1620*3 - 200, -4500 + 500, 1620*3 + 200, _GBORDER);
}

void	dsp_TEST522(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, 1620*3 - 800, -4500 + 500, 1620*3 - 400, _GBORDER);
}

void	dsp_TEST523(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, -1620*3 + 800, -4500 + 500, -1620*3 + 400, _GBORDER);
}

void	dsp_TEST524(short pa)
{
	setcolor(COLOR_WHITE1);
	box(-4500 - 500, -1620*3 - 200, -4500 + 500, -1620*3 + 200, _GBORDER);
}

void	dsp_TEST525(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, 1620*3 - 200, 4500 + 500, 1620*3 + 200, _GBORDER);
}

void	dsp_TEST526(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, 1620*3 - 800, 4500 + 500, 1620*3 - 400, _GBORDER);
}

void	dsp_TEST527(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, -1620*3 + 800, 4500 + 500, -1620*3 + 400, _GBORDER);
}

void	dsp_TEST528(short pa)
{
	setcolor(COLOR_WHITE1);
	box(4500 - 500, -1620*3 - 200, 4500 + 500, -1620*3 + 200, _GBORDER);
}

void	dsp_TEST529(short pa)
{
	setcolor(COLOR_WHITE1);
	dsp_str(-3000, 0, "PRESS ALL SWITCHS", 8);
}
