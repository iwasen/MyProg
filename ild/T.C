#include "sg_cpu.h"
#include <math.h>
#define	DP_TO_MM(dp)	(short)(((long)(dp) * 1925) / 1024)
#define _GBORDER	2	/* draw outline only */
#define _GFILLINTERIOR	3	/* fill using current fill mask */
#define	PI	3.1415926536

void	dsp_str(short, short, char *);
void	scopy(char *, ushort *, short);
void	font7(void);
void	font8(void);
void	setcolor(ushort);
void	setlinestyle(ushort);
void	line(short, short, short, short);
void	box(short, short, short, short, short);
void	circle(short, short, short, short);
void	triangle(short, short, short, short, short, short, short);
void	fill(short, short, ushort);

void	dsp_tstptn0(short pa)
{
}

void	dsp_tstptn1(short pa)
{
	setcolor(COLOR_WHITE);
	box(-6345, -6345, 6345, 6345, _GFILLINTERIOR);
}

void	dsp_tstptn2(short pa)
{
	static	ushort	color[8] = {
		COLOR_CYAN, COLOR_YELLOW, COLOR_WHITE, COLOR_BLACK,
		COLOR_BLUE, COLOR_RED, COLOR_MAGENTA, COLOR_GREEN
	};
	short	i;

	for (i = 0; i < 8; i++) {
		setcolor(color[i]);
		box(-6345, 6345 - i * 1586, 6345, 6345 - (i+1) * 1586,
							_GFILLINTERIOR);
	}
}

void	dsp_tstptn3(short pa)
{
	static	ushort	color[8] = {
		COLOR_CYAN, COLOR_YELLOW, COLOR_WHITE, COLOR_BLACK,
		COLOR_BLUE, COLOR_RED, COLOR_MAGENTA, COLOR_GREEN
	};
	short	i, x, y;

	setcolor(COLOR_CYAN);
	circle(0, 0, 4500, _GBORDER);
	for (i = 0; i < 7; i++) {
		x = i * 1125 - 3375;
		y = (short)(sqrt(4500.0 * 4500.0 - (double)x * (double)x));
		line(x, y, x, -y);
	}
	for (i = 0; i < 8; i++) {
		setcolor(color[i]);
		fill(i * 1125 - 3937, 0, COLOR_CYAN);
	}

	setcolor(COLOR_WHITE);
	circle(0, 0, 4500, _GBORDER);
	for (i = 0; i < 7; i++) {
		x = i * 1125 - 3375;
		y = (short)(sqrt(4500.0 * 4500.0 - (double)x * (double)x));
		line(x, y, x, -y);
	}
}

void	dsp_bite(short pa)
{
	setcolor(COLOR_WHITE);
	font8();
	dsp_str(-5340, 4835, "BITE");
}

/*=======================================================================
 |
 |		レティクル（レーザー照射点）
 |
 |	void	dsp_reticle()
 |
 =======================================================================*/
void	dsp_reticle(short pa)
{
	short	x, y;

	x= DP_TO_MM(dp.PX01);
	y= DP_TO_MM(dp.PY01);

	setcolor(dp.C001);
	line(x, y+300, x, y+1005);
	line(x+300, y, x+1005, y);
	line(x, y-300, x, y-1005);
	line(x-300, y, x-1005, y);
}

/*=======================================================================
 |
 |		方位目盛り（索敵サイトモード１）
 |
 |	void	dsp_heading_scale1()
 |
 =======================================================================*/
void	dsp_heading_scale1(short pa)
{
	short	i, x;
	char	*ch[7] = {"W", "N", "E", "S", "W", "N", "E"};

	setcolor(dp.C001);
	font8();
	x = -DP_TO_MM(dp.LX20A);
	for (i = 0; i < 55; i++) {
		if (x > -4400 && x < 4400) {
			if (i % 9 == 0) {
				dsp_str(x - 210, 6230, ch[i / 9]);
				line(x, 5143, x, 5653);
			} else if (i % 3 == 0)
				line(x, 5143, x, 5488);
			else
				line(x, 5143, x, 5353);
		}
		x += 450;
	}
	line(0, 5083, 0, 4873);
}

/*=======================================================================
 |
 |		センサ方位（索敵サイトモード１）
 |
 |	void	dsp_sensor_heading1()
 |
 =======================================================================*/
void	dsp_sensor_heading1(short pa)
{
	short	x;

	setcolor(dp.C001);
	switch (pa) {
	case PA_K020B:
		x = -4050 + DP_TO_MM(dp.LX20B);
		line(x, 5083, x-121, 4873);
		line(x, 5083, x+121, 4873);
		break;
	case PA_K020C:
		line(-4260, 4978, -4050, 5083);
		line(-4260, 4978, -4050, 4873);
		break;
	case PA_K020D:
		line(4260, 4978, 4050, 5083);
		line(4260, 4978, 4050, 4873);
		break;
	default:
		return;
	}
}

/*=======================================================================
 |
 |		センサＦＯＲ
 |
 |	void	dsp_sensor_FOR()
 |
 =======================================================================*/
void	dsp_sensor_FOR(short pa)
{
	setcolor(dp.C001);
	switch (pa) {
	case PA_K027A:
		box(-1800, -4935, 1800, -6135, _GBORDER);
		line(-900, -4935, -900, -5135);
		line(0, -4935, 0, -5135);
		line(900, -4935, 900, -5135);
		line(-900, -5935, -900, -6135);
		line(0, -5935, 0, -6135);
		line(900, -5935, 900, -6135);
		line(-1800, -5535, -1600, -5535);
		line(1800, -5535, 1600, -5535);
		break;
	case PA_K027B:
		box(-1800, 4935, 1800, 6135, _GBORDER);
		line(-900, 4935, -900, 5135);
		line(0, 4935, 0, 5135);
		line(900, 4935, 900, 5135);
		line(-900, 5935, -900, 6135);
		line(0, 5935, 0, 6135);
		line(900, 5935, 900, 6135);
		line(-1800, 5535, -1600, 5535);
		line(1800, 5535, 1600, 5535);
		break;
	default:
		return;
	}
}

/*=======================================================================
 |
 |		センサＦＯＲ異常時シンボル
 |
 |	void	dsp_sensor_FOR_err()
 |
 =======================================================================*/
void	dsp_sensor_FOR_err(short pa)
{
	setcolor(COLOR_YELLOW);
	switch (pa) {
	case PA_K024A:
		line(-1800, -4935, 1800, -6135);
		line(-1800, -6135, 1800, -4935);
		break;
	case PA_K024B:
		line(-1800, 4935, 1800, 6135);
		line(-1800, 6135, 1800, 4935);
		break;
	default:
		return;
	}
}

/*=======================================================================
 |
 |		センサＬＯＳ（シンボル）
 |
 |	void	dsp_sensor_LOS_symbol()
 |
 =======================================================================*/
void	dsp_sensor_LOS_symbol(short pa)
{
	short	x, y, x1, y1, x2, y2;

	x = DP_TO_MM(dp.LX28);
	y = DP_TO_MM(dp.LY28);
	x1 = x - 150;
	y1 = y - 150;
	x2 = x + 150;
	y2 = y + 150;
	setcolor(dp.C001);
	switch (pa) {
	case PA_K028A:
		box(-1800+x1, -6135+y1, -1800+x2, -6135+y2, _GBORDER);
		break;
	case PA_K028B:
		box(-1800+x1, 4935+y1, -1800+x2, 4935+y2, _GBORDER);
		break;
	}
}

/*=======================================================================
 |
 |		センサＬＯＳ（デジタル）
 |
 |	void	dsp_sensor_LOS_digital()
 |
 =======================================================================*/
void	dsp_sensor_LOS_digital(short pa)
{
	char	buf[5];

	font8();
	setcolor(dp.C001);

	scopy(buf, dp.A121, 4);
	dsp_str(4645, 6135, buf);

	scopy(buf, dp.A122, 4);
	dsp_str(4645, 5425, buf);

	if (pa == PA_KD12B) {
		line(4595, 6945, 4595, 4820);
		line(4595, 4820, 6345, 4820);
	}
}

/*=======================================================================
 |
 |		レーザー操作ステータス
 |
 |	void	dsp_laser_status()
 |
 =======================================================================*/
void	dsp_laser_status(short pa)
{
	switch (pa) {
	case PA_K002A:
		setcolor(COLOR_RED);
		break;
	case PA_K002B:
		setcolor(dp.C001);
		break;
	default:
		return;
	}
	circle(DP_TO_MM(dp.PX02), DP_TO_MM(dp.PY02), 300, _GBORDER);
}

/*=======================================================================
 |
 |		ＦＯＶゲート
 |
 |	void	dsp_FOV_gate()
 |
 =======================================================================*/
void	dsp_FOV_gate(short pa)
{
	short	x0, y0, x1, y1;

	x0 = DP_TO_MM(dp.PX090);
	y0 = DP_TO_MM(dp.PY090);
	x1 = DP_TO_MM(dp.PX091);
	y1 = DP_TO_MM(dp.PY091);

	setcolor(dp.C001);
	line(x0, y0, x0, y0+300);
	line(x0, y0, x0+300, y0);
	line(x1, y0, x1, y0+300);
	line(x1, y0, x1-300, y0);
	line(x1, y1, x1, y1-300);
	line(x1, y1, x1-300, y1);
	line(x0, y1, x0, y1-300);
	line(x0, y1, x0+300, y1);
}

/*=======================================================================
 |
 |		センサ画像
 |
 |	void	dsp_sensor_monitor()
 |
 =======================================================================*/
void	dsp_sensor_monitor(short pa)
{
	char	buf[5];
	short	y;

	switch (pa) {
	case PA_KD11A:
		scopy(buf, dp.A111_A, 4);
		y = 5607;
		font8();
		break;
	case PA_KD11B:
		scopy(buf, dp.A111_B, 4);
		y = 5507;
		font7();
		break;
	case PA_KD11C:
		scopy(buf, dp.A111_A, 4);
		y = 4600;
		font8();
		break;
	default:
		return;
	}
	setcolor(dp.C001);
	dsp_str(-6345 + 105, y, buf);
}

/*=======================================================================
 |
 |		速度
 |
 |	void	dsp_speed()
 |
 =======================================================================*/
void	dsp_speed(short pa)
{
	char	buf[4];
	short	y;

	switch (pa) {
	case PA_KD15A:
		y = -4145;
		break;
	case PA_KD15B:
		y = -5740;
		break;
	case PA_KD15C:
		y = 4345;
		break;
	default:
		return;
	}
	scopy(buf, dp.A151, 3);
	font8();
	setcolor(dp.C001);
	dsp_str(-2760, y, buf);
}

/*=======================================================================
 |
 |		高度
 |
 |	void	dsp_altitude()
 |
 =======================================================================*/
void	dsp_altitude(short pa)
{
	char	buf[6];
	short	y;

	switch (pa) {
	case PA_KD16A:
		y = -4145;
		break;
	case PA_KD16B:
		y = -5740;
		break;
	case PA_KD16C:
		y = 4345;
		break;
	default:
		return;
	}
	scopy(buf, dp.A161, 5);
	font8();
	setcolor(dp.C001);
	dsp_str(900, y, buf);
}

/*=======================================================================
 |
 |		メッセージエリア
 |
 |	void	dsp_message()
 |
 =======================================================================*/
void	dsp_message(short pa)
{
	char	buf[10];
	short	y;
	ushort	color;

	switch (pa) {
	case PA_KD13A0:
		scopy(buf, dp.A131, 9);
		color = dp.C130;
		y = -4755;
		break;
	case PA_KD13A1:
		scopy(buf, dp.A132, 9);
		color = dp.C131;
		y = -5295;
		break;
	case PA_KD13A2:
		scopy(buf, dp.A133, 9);
		color = dp.C132;
		y = -5840;
		break;
	case PA_KD13B0:
		scopy(buf, dp.A131, 9);
		color = dp.C130;
		y = 6240;
		break;
	case PA_KD13B1:
		scopy(buf, dp.A132, 9);
		color = dp.C131;
		y = 5697;
		break;
	case PA_KD13B2:
		scopy(buf, dp.A133, 9);
		color = dp.C132;
		y = 5155;
		break;
	default:
		return;
	}
	font7();
	setcolor(color);
	dsp_str(-6240, y, buf);
}

/*=======================================================================
 |
 |		レジェンド
 |
 |	void	dsp_legend()
 |
 =======================================================================*/
void	dsp_legend(short pa)
{
	char	buf[10];
	short	y;
	ushort	color;

	switch (pa) {
	case PA_KD140:
		scopy(buf, dp.A141, 9);
		color = dp.C140;
		y = 3640;
		break;
	case PA_KD141:
		scopy(buf, dp.A142, 9);
		color = dp.C141;
		y = 2930;
		break;
	case PA_KD142:
		scopy(buf, dp.A143, 9);
		color = dp.C142;
		y = 2220;
		break;
	case PA_KD143:
		scopy(buf, dp.A144, 9);
		color = dp.C143;
		y = 1510;
		break;
	case PA_KD144:
		scopy(buf, dp.A145, 9);
		color = dp.C144;
		y = 800;
		break;
	default:
		return;
	}
	font8();
	setcolor(color);
	dsp_str(-6240, y, buf);
}

/*=======================================================================
 |
 |		追尾ゲート
 |
 |	void	dsp_tsuibi_gate()
 |
 =======================================================================*/
void	dsp_tsuibi_gate(short pa)
{
	short	x0, y0, x1, y1, L1, L2, L3, L4, L5, L6, L7, L8;

	x0 = DP_TO_MM(dp.PX030);
	y0 = DP_TO_MM(dp.PY030);
	x1 = DP_TO_MM(dp.PX031);
	y1 = DP_TO_MM(dp.PY031);
	L1 = DP_TO_MM(dp.LX031);
	L2 = DP_TO_MM(dp.LX032);
	L3 = DP_TO_MM(dp.LY033);
	L4 = DP_TO_MM(dp.LY034);
	L5 = DP_TO_MM(dp.LY035);
	L6 = DP_TO_MM(dp.LY036);
	L7 = DP_TO_MM(dp.LX037);
	L8 = DP_TO_MM(dp.LX038);

	setcolor(dp.C001);
	line(x0, y0, x0-L1, y0);
	line(x0, y0, x0+L2, y0);
	line(x0, y0, x0, y0+L3);
	line(x0, y0, x0, y0-L4);

	line(x1, y0, x1+L1, y0);
	line(x1, y0, x1-L2, y0);
	line(x1, y0, x1, y0+L3);
	line(x1, y0, x1, y0-L4);

	line(x1, y1, x1+L1, y1);
	line(x1, y1, x1-L2, y1);
	line(x1, y1, x1, y1-L3);
	line(x1, y1, x1, y1+L4);

	line(x0, y1, x0-L1, y1);
	line(x0, y1, x0+L2, y1);
	line(x0, y1, x0, y1-L3);
	line(x0, y1, x0, y1+L4);

	line(x0+L2+L8, y0, x0+L2+L8+L7, y0);
	line(x1, y0+L3+L5, x1, y0+L3+L5+L6);
	line(x1-L2-L8, y1, x1-L2-L8-L7, y1);
	line(x0, y1-L3-L5, x0, y1-L3-L5-L6);
}

/*=======================================================================
 |
 |		時刻
 |
 |	void	dsp_time()
 |
 =======================================================================*/
void	dsp_time(short pa)
{
	char	buf[9];

	buf[0] = dp.A181[0];
	buf[1] = dp.A181[1];
	buf[2] = ':';
	buf[3] = dp.A181[2];
	buf[4] = dp.A181[3];
	buf[5] = ':';
	buf[6] = dp.A181[4];
	buf[7] = dp.A181[5];
	buf[8] = '\0';
	font8();
	setcolor(dp.C001);
	dsp_str(2945, -5740, buf);
}

/*=======================================================================
 |
 |		ミサイルシンボル
 |
 |	void	dsp_missile_symbol()
 |
 =======================================================================*/
void	dsp_missile_symbol(short pa)
{
	short	x, y;

	x = DP_TO_MM(dp.PX05);
	y = DP_TO_MM(dp.PY05);
	setcolor(COLOR_RED);
	circle(x, y, 300, _GBORDER);
	line (x-212, y-212, x-350, y-350);
	line (x+212, y-212, x+350, y-350);
	line (x+212, y+212, x+350, y+350);
	line (x-212, y+212, x-350, y+350);
}

/*=======================================================================
 |
 |		ミサイルＬＯＳ
 |
 |	void	dsp_missile_LOS()
 |
 =======================================================================*/
void	dsp_missile_LOS(short pa)
{
	short	x, y;

	switch (pa) {
	case PA_K023A:
		x = DP_TO_MM(dp.LX23) - 1800;
		y = DP_TO_MM(dp.LY23) - 6135;
		break;
	case PA_K023B:
		x = DP_TO_MM(dp.LX23) - 1800;
		y = DP_TO_MM(dp.LY23) + 4935;
		break;
	default:
		return;
	}
	setcolor(COLOR_RED);
	circle(x, y, 70, _GBORDER);
	line (x-42, y-42, x-116, y-116);
	line (x+42, y-42, x+116, y-116);
	line (x+42, y+42, x+116, y+116);
	line (x-42, y+42, x-116, y+116);
}

/*=======================================================================
 |
 |		ミサイル方位（索敵サイトモード１）
 |
 |	void	dsp_missile_heading1()
 |
 =======================================================================*/
void	dsp_missile_heading1(short pa)
{
	short	x;

	setcolor(COLOR_RED);
	switch (pa) {
	case PA_K025A:
		x = -4050 + DP_TO_MM(dp.LX25A);
		triangle(x, 5083, x - 121, 4873,
				x + 121, 4873, _GFILLINTERIOR);
		break;
	case PA_K025B:
		triangle(-4260, 4978, -4050, 5083, -4050, 4873,
						_GFILLINTERIOR);
		break;
	case PA_K025C:
		triangle(4260, 4978, 4050, 5083, 4050, 4873,
						_GFILLINTERIOR);
		break;
	}
}

/*=======================================================================
 |
 |		ミサイル方位（索敵サイトモード２）
 |
 |	void	dsp_missile_heading2()
 |
 =======================================================================*/
void	dsp_missile_heading2(short pa)
{
	short	x;

	setcolor(COLOR_RED);
	x = -4320 + DP_TO_MM(dp.LX25D);
	triangle(x, 5083, x - 121, 4873,
				x + 121, 4873, _GFILLINTERIOR);
}

/*=======================================================================
 |
 |		ミサイル警報
 |
 |	void	dsp_missile_alxxx(short pa)
 |
 =======================================================================*/
void	dsp_missile_alxxx(short pa)
{
	char	buf[8];

	setcolor(COLOR_BLACK);
	box(3259, -4202, 6240, -6240, _GFILLINTERIOR);
	setcolor(COLOR_RED);
	box(3259, -4202, 6240, -6240, _GBORDER);

	setcolor(dp.C001);
	font7();

	scopy(buf, dp.A103, 7);
	dsp_str(3454, -4397, buf);

	font8();
	scopy(buf, dp.A101, 4);
	dsp_str(3754, -4940, buf);

	scopy(buf, dp.A102, 4);
	dsp_str(3754, -5635, buf);
}

/*=======================================================================
 |
 |		ミサイル存在方向１
 |
 |	void	dsp_missile_sonzai1()
 |
 =======================================================================*/
void	dsp_missile_sonzai1(short pa)
{
	short	x1, y1, x2, y2;
	double	r;

	x1 = DP_TO_MM(dp.PX01);
	y1 = DP_TO_MM(dp.PY01);
	r = dp.R06 / 32768.0 * PI;
	x2 = x1 + (short)(cos(r) * 1500);
	y2 = y1 + (short)(sin(r) * 1500);

	setcolor(COLOR_RED);
	circle(x2, y2, 70, _GBORDER);
	line (x2-42, y2-42, x2-116, y2-116);
	line (x2+42, y2-42, x2+116, y2-116);
	line (x2+42, y2+42, x2+116, y2+116);
	line (x2-42, y2+42, x2-116, y2+116);

	line(x1, y1, x2, y2);
}

/*=======================================================================
 |
 |		ミサイル表示方向
 |
 |	void	dsp_missile_hyouji()
 |
 =======================================================================*/
void	dsp_missile_hyouji(short pa)
{
	short	x1, y1, x2, y2, l;
	double	r;

	x1 = DP_TO_MM(dp.PX01);
	y1 = DP_TO_MM(dp.PY01);
	r = dp.R08 / 32768.0 * PI;
	l = DP_TO_MM(dp.LX08);
	x2 = x1 + (short)(cos(r) * l);
	y2 = y1 + (short)(sin(r) * l);

	setcolor(COLOR_RED);
	line(x1, y1, x2, y2);
}

/*=======================================================================
 |
 |		ミサイル存在方向２
 |
 |	void	dsp_missile_sonzai2()
 |
 =======================================================================*/
void	dsp_missile_sonzai2(short pa)
{
	short	x1, y1, x2, y2, l, x3, y3;
	double	r;

	x1 = DP_TO_MM(dp.PX01);
	y1 = DP_TO_MM(dp.PY01);
	r = dp.R07 / 32768.0 * PI;
	l = DP_TO_MM(dp.LX07) + 405;
	x2 = x1 + (short)(cos(r) * l);
	y2 = y1 + (short)(sin(r) * l);

	setcolor(COLOR_RED);
	line(x1, y1, x2, y2);

	x3 = x2 + (short)(cos(r + 2.618) * 468);
	y3 = y2 + (short)(sin(r + 2.618) * 468);
	line(x2, y2, x3, y3);

	x3 = x2 + (short)(cos(r - 2.618) * 468);
	y3 = y2 + (short)(sin(r - 2.618) * 468);
	line(x2, y2, x3, y3);
}

/*=======================================================================
 |
 |		ＩＦＦゲートａ　Ｄ／Ｐ計算
 |
 |	void	dsp_IFF_gateA()
 |
 =======================================================================*/
void	dsp_IFF_gateA(short pa)
{
	short	x0, y0, x1, y1, l;

	switch (pa) {
	case PA_K004A0:
		x0 = DP_TO_MM(dp.IFF_gate_a[0].PX04A_0);
		y0 = DP_TO_MM(dp.IFF_gate_a[0].PY04A_0);
		x1 = DP_TO_MM(dp.IFF_gate_a[0].PX04A_1);
		y1 = DP_TO_MM(dp.IFF_gate_a[0].PY04A_1);
		l = DP_TO_MM(dp.IFF_gate_a[0].L04A_0);
		break;
	case PA_K004A1:
		x0 = DP_TO_MM(dp.IFF_gate_a[1].PX04A_0);
		y0 = DP_TO_MM(dp.IFF_gate_a[1].PY04A_0);
		x1 = DP_TO_MM(dp.IFF_gate_a[1].PX04A_1);
		y1 = DP_TO_MM(dp.IFF_gate_a[1].PY04A_1);
		l = DP_TO_MM(dp.IFF_gate_a[1].L04A_0);
		break;
	case PA_K004A2:
		x0 = DP_TO_MM(dp.IFF_gate_a[2].PX04A_0);
		y0 = DP_TO_MM(dp.IFF_gate_a[2].PY04A_0);
		x1 = DP_TO_MM(dp.IFF_gate_a[2].PX04A_1);
		y1 = DP_TO_MM(dp.IFF_gate_a[2].PY04A_1);
		l = DP_TO_MM(dp.IFF_gate_a[2].L04A_0);
		break;
	default:
		return;
	}

	setcolor(COLOR_YELLOW);
	setlinestyle(0xcccc);
	box(x0, y0, x1, y1, _GBORDER);
	setlinestyle(0xffff);
}

/*=======================================================================
 |
 |		ＩＦＦゲートｂ　Ｄ／Ｐ計算
 |
 |	void	dsp_IFF_gateB()
 |
 =======================================================================*/
void	dsp_IFF_gateB(short pa)
{
	short	x, y, l, r;

	switch (pa) {
	case PA_K004B0:
		x = DP_TO_MM(dp.IFF_gate_b[0].PX04B_0);
		y = DP_TO_MM(dp.IFF_gate_b[0].PY04B_0);
		l = DP_TO_MM(dp.IFF_gate_b[0].L04B_0);
		r = DP_TO_MM(dp.IFF_gate_b[0].L04B_1);
		break;
	case PA_K004B1:
		x = DP_TO_MM(dp.IFF_gate_b[1].PX04B_0);
		y = DP_TO_MM(dp.IFF_gate_b[1].PY04B_0);
		l = DP_TO_MM(dp.IFF_gate_b[1].L04B_0);
		r = DP_TO_MM(dp.IFF_gate_b[1].L04B_1);
		break;
	case PA_K004B2:
		x = DP_TO_MM(dp.IFF_gate_b[2].PX04B_0);
		y = DP_TO_MM(dp.IFF_gate_b[2].PY04B_0);
		l = DP_TO_MM(dp.IFF_gate_b[2].L04B_0);
		r = DP_TO_MM(dp.IFF_gate_b[2].L04B_1);
		break;
	default:
		return;
	}

	setcolor(COLOR_YELLOW);
	setlinestyle(0xcccc);
	circle(x, y + r, r, _GBORDER);
	setlinestyle(0xffff);
}

/*=======================================================================
 |
 |		ＩＦＦゲートｃ　Ｄ／Ｐ計算
 |
 |	void	dsp_IFF_gateC()
 |
 =======================================================================*/
void	dsp_IFF_gateC(short pa)
{
	short	x0, y0, x1, y1, x2, y2, x3, y3, l;

	switch (pa) {
	case PA_K004C0:
		x0 = DP_TO_MM(dp.IFF_gate_c[0].PX04C_0);
		y0 = DP_TO_MM(dp.IFF_gate_c[0].PY04C_0);
		x1 = DP_TO_MM(dp.IFF_gate_c[0].PX04C_1);
		y1 = DP_TO_MM(dp.IFF_gate_c[0].PY04C_1);
		x2 = DP_TO_MM(dp.IFF_gate_c[0].PX04C_2);
		y2 = DP_TO_MM(dp.IFF_gate_c[0].PY04C_2);
		x3 = DP_TO_MM(dp.IFF_gate_c[0].PX04C_3);
		y3 = DP_TO_MM(dp.IFF_gate_c[0].PY04C_3);
		l = DP_TO_MM(dp.IFF_gate_c[0].L04C_0);
		break;
	case PA_K004C1:
		x0 = DP_TO_MM(dp.IFF_gate_c[1].PX04C_0);
		y0 = DP_TO_MM(dp.IFF_gate_c[1].PY04C_0);
		x1 = DP_TO_MM(dp.IFF_gate_c[1].PX04C_1);
		y1 = DP_TO_MM(dp.IFF_gate_c[1].PY04C_1);
		x2 = DP_TO_MM(dp.IFF_gate_c[1].PX04C_2);
		y2 = DP_TO_MM(dp.IFF_gate_c[1].PY04C_2);
		x3 = DP_TO_MM(dp.IFF_gate_c[1].PX04C_3);
		y3 = DP_TO_MM(dp.IFF_gate_c[1].PY04C_3);
		l = DP_TO_MM(dp.IFF_gate_c[1].L04C_0);
		break;
	case PA_K004C2:
		x0 = DP_TO_MM(dp.IFF_gate_c[2].PX04C_0);
		y0 = DP_TO_MM(dp.IFF_gate_c[2].PY04C_0);
		x1 = DP_TO_MM(dp.IFF_gate_c[2].PX04C_1);
		y1 = DP_TO_MM(dp.IFF_gate_c[2].PY04C_1);
		x2 = DP_TO_MM(dp.IFF_gate_c[2].PX04C_2);
		y2 = DP_TO_MM(dp.IFF_gate_c[2].PY04C_2);
		x3 = DP_TO_MM(dp.IFF_gate_c[2].PX04C_3);
		y3 = DP_TO_MM(dp.IFF_gate_c[2].PY04C_3);
		l = DP_TO_MM(dp.IFF_gate_c[2].L04C_0);
		break;
	default:
		return;
	}

	setcolor(COLOR_RED);
	setlinestyle(0xcccc);
	line(x0, y0, x1, y1);
	line(x1, y1, x2, y2);
	line(x2, y2, x3, y3);
	line(x3, y3, x0, y0);
	setlinestyle(0xffff);
}

/*=======================================================================
 |
 |		障害物シンボル
 |
 |	void	dsp_shougai_symbol()
 |
 =======================================================================*/
void	dsp_shougai_symbol(short pa)
{
	short	n, x, y;

	if (pa >= PA_K0100 && pa < PA_K0100 + 100) {
		n =  pa - PA_K0100;
		setcolor(COLOR_YELLOW);
	} else if (pa >= PA_K0200 && pa < PA_K0200 + 100) {
		n =  pa - PA_K0200;
		setcolor(COLOR_RED);
	} else if (pa >= PA_K0300 && pa < PA_K0300 + 100) {
		n =  pa - PA_K0300;
		setcolor(COLOR_GREEN);
	}
	x = DP_TO_MM(dp.obst[n].PX100);
	y = DP_TO_MM(dp.obst[n].PY100);
	box(x - 150, y - 52, x + 150, y + 52, _GFILLINTERIOR);
}

/*=======================================================================
 |
 |		任意図形（線分）
 |
 |	void	dsp_zukei0()
 |
 =======================================================================*/
void	dsp_zukei0(short pa)
{
	short	n, x0, y0, l, x1, y1;
	double	d;

	n = pa - PA_K050A;
	x0 = DP_TO_MM(dp.zukei0[n].PX50);
	y0 = DP_TO_MM(dp.zukei0[n].PY50);
	l = DP_TO_MM(dp.zukei0[n].L50);
	d = dp.zukei0[n].D50 / 32768.0 * PI;
	x1 = x0 + (short)(l * cos(d));
	y1 = y0 + (short)(l * sin(d));

	setcolor(dp.zukei0[n].C05);
	line(x0, y0, x1, y1);
}

/*=======================================================================
 |
 |		任意図形（長方形）
 |
 |	void	dsp_zukei1()
 |
 =======================================================================*/
void	dsp_zukei1(short pa)
{
	short	n, x, y, lx, ly;

	n = pa - PA_K051A;
	x = DP_TO_MM(dp.zukei1[n].PX51);
	y = DP_TO_MM(dp.zukei1[n].PY51);
	lx = DP_TO_MM(dp.zukei1[n].LX51);
	ly = DP_TO_MM(dp.zukei1[n].LY51);

	setcolor(dp.zukei0[n].C05);
	box(x, y, x + lx, y - ly, _GBORDER);
}

/*=======================================================================
 |
 |		任意図形（塗りつぶし長方形）
 |
 |	void	dsp_zukei2()
 |
 =======================================================================*/
void	dsp_zukei2(short pa)
{
	short	n, x0, y0, x1, y1;

	n = pa - PA_K052A;
	x0 = DP_TO_MM(dp.zukei2[n].PX52_1);
	y0 = DP_TO_MM(dp.zukei2[n].PY52_1);
	x1 = DP_TO_MM(dp.zukei2[n].PX52_2);
	y1 = DP_TO_MM(dp.zukei2[n].PY52_2);

	setcolor(dp.zukei0[n].C05);
	box(x0, y0, x1, y1, _GFILLINTERIOR);
}

/*=======================================================================
 |
 |		任意図形（円）
 |
 |	void	dsp_zukei3()
 |
 =======================================================================*/
void	dsp_zukei3(short pa)
{
	short	n, x, y, l0, l1;

	n = pa - PA_K053A;
	x = DP_TO_MM(dp.zukei3[n].PX53);
	y = DP_TO_MM(dp.zukei3[n].PY53);
	l0 = DP_TO_MM(dp.zukei3[n].L53_0);
	l1 = DP_TO_MM(dp.zukei3[n].L53_1);

	setcolor(dp.zukei0[n].C05);
	circle(x, y + l1, l1, _GBORDER);
}

/*=======================================================================
 |
 |		方位目盛り（索敵サイトモード２）
 |
 |	void	dsp_heading_scale2()
 |
 =======================================================================*/
void	dsp_heading_scale2(short pa)
{
	short	i, x;
	char	*ch[9] = {"S", "W", "N", "E", "S", "W", "N", "E", "S"};

	setcolor(dp.C001);
	font8();
	x = -DP_TO_MM(dp.LX21A);
	for (i = 0; i < 73; i++) {
		if (x > -4520 && x < 4520) {
			if (i % 9 == 0) {
				dsp_str(x - 210, 6230, ch[i / 9]);
				line(x, 5143, x, 5653);
			} else if (i % 3 == 0)
				line(x, 5143, x, 5488);
			else
				line(x, 5143, x, 5353);
		}
		x += 240;
	}
	line(0, 5083, 0, 4873);
	line(-2160, 5083, -2160, 4873);
	line(2160, 5083, 2160, 4873);
}

/*=======================================================================
 |
 |		センサ方位（索敵サイトモード２）
 |
 |	void	dsp_sensor_heading2()
 |
 =======================================================================*/
void	dsp_sensor_heading2(short pa)
{
	short	x;

	setcolor(dp.C001);
	switch (pa) {
	case PA_K021B:
		setcolor(dp.C001);
		break;
	case PA_K021C:
		setcolor(COLOR_YELLOW);
		break;
	default:
		return;
	}
	x = -4320 + DP_TO_MM(dp.LX21B);
	line(x, 5083, x-121, 4873);
	line(x, 5083, x+121, 4873);
}

/*=======================================================================
 |
 |		ピッチ目盛り
 |
 |	void	dsp_pitch_scale()
 |
 =======================================================================*/
void	dsp_pitch_scale(short pa)
{
	short	i, y;

	setcolor(dp.C001);
	y = -DP_TO_MM(dp.LY22A);
	for (i = 0; i < 13; i++) {
		if (i == 6)
			line(5340, y, 6045, y);
		else if (i % 3 == 0)
			line(5340, y, 5850, y);
		else
			line(5340, y, 5640, y);
		y += 240;
	}
	line(4980, 0, 5280, 0);
}

/*=======================================================================
 |
 |		センサピッチ
 |
 |	void	dsp_sensor_pitch()
 |
 =======================================================================*/
void	dsp_sensor_pitch(short pa)
{
	short	y;

	switch (pa) {
	case PA_K022B:
		setcolor(dp.C001);
		y = -1440 + DP_TO_MM(dp.LY22B);
		break;
	case PA_K022C:
		setcolor(COLOR_YELLOW);
		y = -1440 + DP_TO_MM(dp.LY22C);
		break;
	default:
		return;
	}
	line(5280, y, 4980, y - 173);
	line(5280, y, 4980, y + 173);
}

/*=======================================================================
 |
 |		ミサイルピッチ
 |
 |	void	dsp_missile_pitch()
 |
 =======================================================================*/
void	dsp_missile_pitch(short pa)
{
	short	y;

	setcolor(COLOR_RED);
	y = -1440 + DP_TO_MM(dp.LY26);
	triangle(5280, y, 4980, y-173, 4980, y+173, _GFILLINTERIOR);
}

/*=======================================================================
 |
 |		機首方位（デジタル）
 |
 |	void	dsp_heading_digital()
 |
 =======================================================================*/
void	dsp_heading_digital(short pa)
{
	char	buf[4];

	font8();
	setcolor(dp.C001);

	scopy(buf, dp.A171, 3);
	dsp_str(-682, 4375, buf);

	line(0, 4935, -787, 4480);
	line(-787, 4480, -787, 3770);
	line(-787, 3770, 787, 3770);
	line(787, 3770, 787, 4480);
	line(787, 4480, 0, 4935);
}

/*=======================================================================
 |
 |		更新レート
 |
 |	void	dsp_update_rate()
 |
 =======================================================================*/
void	dsp_update_rate(short pa)
{
	char	buf[6];

	font8();
	setcolor(COLOR_WHITE);

	scopy(buf, dp.A191, 2);
	buf[2] = ' ';
	scopy(buf+3, dp.A191+2, 2);
	dsp_str(-6240, -105, buf);
}

/*=======================================================================
 |
 |		ミサイル警戒画面（同心円）
 |
 |	void	dsp_missile_circle1()
 |
 =======================================================================*/
void	dsp_missile_circle1(short pa)
{
	setcolor(COLOR_WHITE);
	circle(0, 2940, 2805, _GBORDER);
	circle(0, 2940, 2415, _GBORDER);
	circle(0, 2940, 1395, _GBORDER);
}

void	dsp_missile_circle2(short pa)
{
	setcolor(COLOR_WHITE);
	circle(0, -2940, 2805, _GBORDER);
	circle(0, -2940, 2415, _GBORDER);
	circle(0, -2940, 1395, _GBORDER);
}

/*=======================================================================
 |
 |		機体後方からのシンボル
 |
 |	void	dsp_missile_v1()
 |
 =======================================================================*/
void	dsp_missile_v1(short pa)
{
	short	l, x, y;
	double	r;

	setcolor(COLOR_RED);
	l = DP_TO_MM(dp.L40A);
	r = dp.D40A0 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) + 2940;
	line(0, 2940, x, y);

	r = dp.D40A1 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) + 2940;
	line(0, 2940, x, y);

	r = dp.D40A2 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) + 2940;
	line(0, 2940, x, y);

	r = dp.D40A3 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) + 2940;
	line(0, 2940, x, y);

	r = dp.D40A4 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) + 2940;
	line(0, 2940, x, y);
}

void	dsp_missile_c1(short pa)
{
	short	 x, y;
	ushort	 color;

	switch (pa) {
	case PA_K040B:
		color = COLOR_BLACK;
		break;
	case PA_K045B:
		color = COLOR_RED;
		break;
	default:
		return;
	}
	x = DP_TO_MM(dp.PX40B);
	y = DP_TO_MM(dp.PY40B);
	setcolor(COLOR_CYAN);
	circle(x, y, 202, _GFILLINTERIOR);
	setcolor(color);
	circle(x, y, 202, _GFILLINTERIOR);
	setcolor(COLOR_RED);
	circle(x, y, 202, _GBORDER);
}

/*=======================================================================
 |
 |		機体上方からのシンボル
 |
 |	void	dsp_missile_v2()
 |
 =======================================================================*/
void	dsp_missile_v2(short pa)
{
	short	l, x, y;
	double	r;

	setcolor(COLOR_RED);
	l = DP_TO_MM(dp.L41A);
	r = dp.D41A0 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) - 2940;
	line(0, -2940, x, y);

	r = dp.D41A1 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) - 2940;
	line(0, -2940, x, y);

	r = dp.D41A2 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) - 2940;
	line(0, -2940, x, y);

	r = dp.D41A3 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) - 2940;
	line(0, -2940, x, y);

	r = dp.D41A4 / 32768.0 * PI;
	x = (short)(l * cos(r));
	y = (short)(l * sin(r)) - 2940;
	line(0, -2940, x, y);
}

void	dsp_missile_c2(short pa)
{
	short	x, y;
	ushort	color;

	switch (pa) {
	case PA_K041B:
		color = COLOR_BLACK;
		break;
	case PA_K046B:
		color = COLOR_RED;
		break;
	default:
		return;
	}
	x = DP_TO_MM(dp.PX41B);
	y = DP_TO_MM(dp.PY41B);
	setcolor(COLOR_CYAN);
	circle(x, y, 202, _GFILLINTERIOR);
	setcolor(color);
	circle(x, y, 202, _GFILLINTERIOR);
	setcolor(COLOR_RED);
	circle(x, y, 202, _GBORDER);
}

/*=======================================================================
 |
 |		デジタル数値＆レジェンド
 |
 |	void	dsp_missile_digital()
 |
 =======================================================================*/
void	dsp_missile_digital(short pa)
{
	char	buf[8];

	setcolor(COLOR_WHITE);
	font8();

	scopy(buf, dp.A431, 7);
	dsp_str(3160, 1625, buf);

	scopy(buf, dp.A432, 2);
	dsp_str(3160, 710, buf);
	scopy(buf, dp.A432+2, 4);
	dsp_str(4250, 710, buf);

	scopy(buf, dp.A433, 2);
	dsp_str(3160, -205, buf);
	scopy(buf, dp.A433+2, 4);
	dsp_str(4250, -205, buf);
}
