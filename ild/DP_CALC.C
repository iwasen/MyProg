/************************************************************************
 *									*
 *		統合光波表示装置　Ｓ／Ｇ　ＣＰＵプログラム		*
 *									*
 *		名称		: ダイナミック・パラメータ計算処理	*
 *		ファイル名	: dp_calc.c				*
 *									*
 ************************************************************************/
#pragma	SECTION PROG=dp_calc

#include "sg_cpu.h"

static	void	message_check(void);
static	void	dp_bit(void);
static	void	dp_testptn(void);
static	void	dp_sakuteki1(void);
static	void	dp_sakuteki2(void);
static	void	dp_sakuteki3(void);
static	void	dp_sakuteki(void);
static	void	dp_reticle(void);
static	void	dp_heading_scale1(void);
static	void	dp_sensor_heading1(void);
static	void	dp_sensor_FOR(void);
static	void	dp_sensor_LOS_symbol(void);
static	void	dp_sensor_LOS_digital(void);
static	void	dp_laser_mode(void);
static	void	dp_laser_sokukyo(void);
static	void	dp_laser_status(void);
static	void	dp_FOV_gate(void);
static	void	dp_sensor_monitor(void);
static	void	dp_speed(void);
static	void	dp_altitude(void);
static	void	dp_message(void);
static	void	dp_data_fail(void);
static	void	dp_tsuibi_gate(void);
static	void	dp_time(void);
static	void	dp_missile_symbol(void);
static	void	dp_missile_LOS(void);
static	void	dp_missile_heading1(void);
static	void	dp_missile_heading2(void);
static	void	dp_missile_alxxx(void);
static	void	dp_missile_sonzai1(void);
static	void	dp_missile_hyouji(void);
static	void	dp_missile_sonzai2(void);
static	void	dp_IFF_gate(void);
static	void	dp_IFF_gate1(void);
static	void	dp_IFF_gate2(void);
static	void	dp_IFF_gate3(void);
static	void	dp_IFF_gateA(short, IFF_GATE_A *);
static	void	dp_IFF_gateB(short, IFF_GATE_B *);
static	void	dp_IFF_gateC(short, IFF_GATE_C *);
static	void	dp_answer_code(void);
static	void	dp_obst_symbol(void);
static	void	dp_obst_kyori(void);
static	void	dp_zukei(void);
static	void	dp_zukei0(short, ZUKEI0 *, ushort *, short);
static	void	dp_zukei1(short, ZUKEI1 *, ushort *, short);
static	void	dp_zukei2(short, ZUKEI2 *, ushort *, short);
static	void	dp_zukei3(short, ZUKEI3 *, ushort *, short);
static	void	dp_heading_scale2(void);
static	void	dp_sensor_heading2(void);
static	void	dp_pitch_scale(void);
static	void	dp_sensor_pitch(void);
static	void	dp_missile_pitch(void);
static	void	dp_heading_digital(void);
static	void	dp_update_rate(void);
static	void	dp_missile(void);
static	void	dp_missile_s1(short, short);
static	void	dp_missile_s2(short, short);
static	void	dp_missile_digital(short, short);

static	ushort	zukei_color_tbl[8] = {
	COLOR_BLACK, COLOR_RED, COLOR_BLUE, COLOR_YELLOW,
	COLOR_GREEN, COLOR_MAGENTA, COLOR_CYAN, COLOR_WHITE
};

/*=======================================================================
 |
 |		ダイナミック・パラメータ計算処理
 |
 |	void	calc_dp;
 |
 =======================================================================*/
void	calc_dp(int check_1553B_flag)
{
	/* Ｐ／Ａビットクリア */
	SET_WORD(pa, 0, N_PABIT);

	/* ＤＯＴ欠落防止シンボル */
	SET_PABIT(PA_KDOT00);

	/* メッセージチェック */
	message_check();

	/* モードが替わった場合、デジタル数値を表示する */
	if (sg_mode != old_sg_mode) {
		digital_update_flag = 1;
		old_sg_mode = sg_mode;
	}

	dp_update_rate();	/* 更新レート表示 */

	/* １５５３Ｂの更新レートが２７Ｈｚ以下になった場合 */
	/* "DATA FAIL" と更新レート以外の全てのシンボルを消す */
	if (debug_flag == 0 && check_1553B_flag) {
		if (rate_1553B < 27) {
			dp_data_fail();
			return;
		}
	}

	dp_zukei();		/* 任意図形 */

	/* モード別に表示されるシンボル */
	switch (sg_mode) {
	case MODE_BIT:
		dp_bit();
		break;
	case MODE_TESTPTN:
		dp_testptn();
		break;
	case MODE_SAKUTEKI1:
		dp_sakuteki1();
		break;
	case MODE_SAKUTEKI2:
		dp_sakuteki2();
		break;
	case MODE_SAKUTEKI3:
		dp_sakuteki3();
		break;
	case MODE_MISSILE:
		dp_missile();
		break;
	}

	data_error_flag = 0;		/* データエラーフラグリセット */

	digital_update_flag = 0;	/* デジタル数値更新フラグリセット */
}

/*=======================================================================
 |
 |		メッセージ変化チェック
 |
 |	void	message_check()
 |
 =======================================================================*/
static	void	message_check(void)
{
	short	dno, idata;
	int	i, j;

	dno = 22;
	for (i = 0; i < 4; i++, dno++) {
		idata = input_data[dno];

		/* データチェック */
		if (data_check(dno))
			idata = 0;

		/* 変化があったかチェック */
		if (message_data[i] != idata) {
			/* 既に表示中だったら消去する */
			for (j = 0; j < 3; j++) {
				if (message_no[j] == i + 1)
					break;
			}
			for ( ; j < 3; j++)
				message_no[j] = message_no[j+1];

			/* データが０でなかったら一番上に表示する */
			if (idata != 0) {
				message_no[2] = message_no[1];
				message_no[1] = message_no[0];
				message_no[0] = i + 1;
			}

			/* 現在のメッセージデータ保存 */
			message_data[i] = idata;
		}
	}
}

/*=======================================================================
 |
 |		ＢＩＴ処理モード
 |
 |	void	dp_bit()
 |
 =======================================================================*/
static	void	dp_bit(void)
{
	switch (bit_status) {
	case BIT_END:		/* 終了 */
		break;
	case BIT_START:		/* 開始 */
		/*SET_PABIT(PA_T004A);*/	/* "BYTE" */
		break;
	case BIT_NORMAL:	/* 正常 */
		SET_PABIT(PA_T004A);	/* "BYTE" */
		SET_PABIT(PA_T004B);	/* "NORMAL" */
		break;
	case BIT_DUERR:		/* Ｄ／Ｕエラー */
		SET_PABIT(PA_T004A);	/* "BYTE" */
		SET_PABIT(PA_T004C);	/* "D/U ERROR */
		break;
	}
}

/*=======================================================================
 |
 |		テストパターンモード
 |
 |	void	dp_testptn()
 |
 =======================================================================*/
static	void	dp_testptn(void)
{
	/* パターン別にＰ／Ａビットをセット */
	switch (input_data[347]) {
	case 1:
		SET_PABIT(PA_T000);
		break;
	case 2:
		SET_PABIT(PA_T001);
		break;
	case 3:
		SET_PABIT(PA_T002);
		break;
	default:
		data_error_flag = 1;	/* データエラー */
		break;
	}
}

/*=======================================================================
 |
 |		索敵サイトモード１　Ｄ／Ｐ計算
 |
 |	void	dp_sakuteki1()
 |
 =======================================================================*/
static	void	dp_sakuteki1(void)
{
	dp_sakuteki();		/* 索敵サイトモード前処理 */

	/* 機体及び索敵サイト情報に関するシンボル */
	dp_reticle();		/* レティクル */
	dp_heading_scale1();	/* 方位目盛り */
	dp_sensor_heading1();	/* センサ方位 */
	dp_sensor_FOR();	/* センサＦＯＲ */
	dp_sensor_LOS_symbol();	/* センサＬＯＳ（シンボル） */
	dp_sensor_LOS_digital();/* センサＬＯＳ（デジタル） */
	dp_laser_mode();	/* レーザー照射モード */
	dp_laser_sokukyo();	/* レーザー測距結果 */
	dp_laser_status();	/* レーザー操作ステータス */
	dp_FOV_gate();		/* ＦＯＶゲート */
	dp_sensor_monitor();	/* センサ画像 */
	dp_speed();		/* 速度 */
	dp_altitude();		/* 高度 */
	dp_tsuibi_gate();	/* 追尾ゲート */
	dp_time();		/* 時刻 */

	/* ミサイル警報に関するシンボル */
	if ((input_data[29] & 0xff00)) {	/* ミサイル有無チェック */
		if (data_check(29) == 0 && data_check(30) == 0 &&
				data_check(31) == 0) {	/* データチェック */
			dp_missile_symbol();	/* ミサイルシンボル */
			dp_missile_LOS();	/* ミサイルＬＯＳ */
			dp_missile_heading1();	/* ミサイル方位 */
			dp_missile_alxxx();	/* ミサイル警報 */
			dp_missile_sonzai1();	/* ミサイル存在方向１ */
			dp_missile_hyouji();	/* ミサイル表示方向 */
			dp_missile_sonzai2();	/* ミサイル存在方向２ */
		}
	}

	/* 敵味方識別情報に関するシンボル */
	dp_IFF_gate();		/* ＩＦＦゲート */
	dp_answer_code();	/* 応答コード */

	/* 障害物探知情報に関するシンボル */
	if (input_data[44] == 1) {		/* 障害物有無チェック */
		dp_obst_symbol();	/* 障害物シンボル */
		dp_obst_kyori();	/* 障害物までの最短距離 */
	}

	dp_message();		/* メッセージエリア */
}

/*=======================================================================
 |
 |		索敵サイトモード２　Ｄ／Ｐ計算
 |
 |	void	dp_sakuteki2()
 |
 =======================================================================*/
static	void	dp_sakuteki2(void)
{
	dp_sakuteki();		/* 索敵サイトモード前処理 */

	/* 機体及び索敵サイト情報に関するシンボル */
	dp_reticle();		/* レティクル */
	dp_heading_scale2();	/* 方位目盛り */
	dp_sensor_heading2();	/* センサ方位 */
	dp_pitch_scale();	/* ピッチ目盛り */
	dp_sensor_pitch();	/* センサピッチ */
	dp_sensor_LOS_digital();/* センサＬＯＳ（デジタル） */
	dp_laser_mode();	/* レーザー照射モード */
	dp_laser_sokukyo();	/* レーザー測距結果 */
	dp_laser_status();	/* レーザー操作ステータス */
	dp_FOV_gate();		/* ＦＯＶゲート */
	dp_sensor_monitor();	/* センサ画像 */
	dp_speed();		/* 速度 */
	dp_altitude();		/* 高度 */
	dp_tsuibi_gate();	/* 追尾ゲート */
	dp_time();		/* 時刻 */

	/* ミサイル警報に関するシンボル */
	if ((input_data[29] & 0xff00)) {
		if (data_check(29) == 0 && data_check(30) == 0 &&
				data_check(31) == 0) {	/* データチェック */
			dp_missile_symbol();	/* ミサイルシンボル */
			dp_missile_heading2();	/* ミサイル方位 */
			dp_missile_pitch();	/* ミサイルピッチ */
			dp_missile_alxxx();	/* ミサイル警報 */
			dp_missile_sonzai1();	/* ミサイル存在方向１ */
			dp_missile_hyouji();	/* ミサイル表示方向 */
			dp_missile_sonzai2();	/* ミサイル存在方向２ */
		}
	}

	/* 敵味方識別情報に関するシンボル */
	dp_IFF_gate();		/* ＩＦＦゲート */
	dp_answer_code();	/* 応答コード */

	/* 障害物探知情報に関するシンボル */
	if (input_data[44] == 1) {
		dp_obst_symbol();	/* 障害物シンボル */
		dp_obst_kyori();	/* 障害物までの最短距離 */
	}

	dp_message();		/* メッセージエリア */
}

/*=======================================================================
 |
 |		索敵サイトモード３　Ｄ／Ｐ計算
 |
 |	void	dp_sakuteki3()
 |
 =======================================================================*/
static	void	dp_sakuteki3(void)
{
	dp_sakuteki();		/* 索敵サイトモード前処理 */

	/* 機体及び索敵サイト情報に関するシンボル */
	dp_reticle();		/* レティクル */
	dp_heading_digital();	/* 機首方位（デジタル） */
	dp_sensor_FOR();	/* センサＦＯＲ */
	dp_sensor_LOS_symbol();	/* センサＬＯＳ（シンボル） */
	dp_sensor_LOS_digital();/* センサＬＯＳ（デジタル） */
	dp_laser_mode();	/* レーザー照射モード */
	dp_laser_sokukyo();	/* レーザー測距結果 */
	dp_laser_status();	/* レーザー操作ステータス */
	dp_FOV_gate();		/* ＦＯＶゲート */
	dp_sensor_monitor();	/* センサ画像 */
	dp_speed();		/* 速度 */
	dp_altitude();		/* 高度 */
	dp_tsuibi_gate();	/* 追尾ゲート */
	dp_time();		/* 時刻 */

	/* ミサイル警報に関するシンボル */
	if ((input_data[29] & 0xff00)) {
		if (data_check(29) == 0 && data_check(30) == 0 &&
				data_check(31) == 0) {	/* データチェック */
			dp_missile_symbol();	/* ミサイルシンボル */
			dp_missile_LOS();	/* ミサイルＬＯＳ */
			dp_missile_alxxx();	/* ミサイル警報 */
			dp_missile_sonzai1();	/* ミサイル存在方向１ */
			dp_missile_hyouji();	/* ミサイル表示方向 */
			dp_missile_sonzai2();	/* ミサイル存在方向２ */
		}
	}

	/* 敵味方識別情報に関するシンボル */
	dp_IFF_gate();		/* ＩＦＦゲート */
	dp_answer_code();	/* 応答コード */

	/* 障害物探知情報に関するシンボル */
	if (input_data[44] == 1) {
		dp_obst_symbol();	/* 障害物シンボル */
		dp_obst_kyori();	/* 障害物までの最短距離 */
	}

	dp_message();		/* メッセージエリア */
}

/*=======================================================================
 |
 |		索敵サイトモード前処理
 |
 |	void	dp_sakuteki()
 |
 =======================================================================*/
static	void	dp_sakuteki(void)
{
	/* ビデオ表示モード別のＦＯＶのドット数 */
	switch (input_data[346]) {
	case 0:		/* １：１モード */
		dot_video = 1128;
		break;
	case 1:		/* ４：３モード */
		dot_video = 853;
		break;
	case 2:		/* １：１Ｍｏｄｉｆｉｅｄモード */
		dot_video = 1280;
		break;
	default:
		data_error_flag = 1;
		break;
	}

	/* 無指定色(C001)の設定 */
	if (input_data[15] == 2)
		dp.C001 = COLOR_GREEN;
	else
		dp.C001 = COLOR_WHITE;
}

/*=======================================================================
 |
 |		レティクル（レーザー照射点）
 |
 |	void	dp_reticle()
 |
 =======================================================================*/
static	void	dp_reticle(void)
{
	/* データチェック */
	if (data_check(8) || data_check(9))
		return;

	/* レティクルの位置(PX01,PY01)設定 */
	dp.PX01 = DOT_TO_DP(input_data[8]);
	dp.PY01 = DOT_TO_DP(-input_data[9]);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K001);
}

/*=======================================================================
 |
 |		方位目盛り（索敵サイトモード１）
 |
 |	void	dp_heading_scale1()
 |
 =======================================================================*/
static	void	dp_heading_scale1(void)
{
	/* データチェック */
	if (data_check(2))
		return;

	/* ＬＸ２０Ａ設定 */
	dp.LX20A  = MM_TO_DP(((ushort)input_data[2] * 9 / 2 + 4050)
							* 1024L / 1026);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K020A);
}

/*=======================================================================
 |
 |		センサ方位（索敵サイトモード１）
 |
 |	void	dp_sensor_heading1()
 |
 =======================================================================*/
static	void	dp_sensor_heading1(void)
{
	short	roll;
	long	heading;

	/* ウォーニング２（空間安定装置制御部）が１の場合は表示しない */
	if ((input_data[27] & 0x00ff) == 1)
		return;

	/* データチェック */
	if (data_check(4) || data_check(5) || data_check(6))
		return;

	/* 計算用にロールを小数第１位まで取り出す */
	roll = ROUND(input_data[4], 10);

	/* AZ*cos(Roll) + EL*sin(Roll) を計算 */
	heading = ((long)input_data[5] * (long)cos(roll) +
			(long)input_data[6] * (long)sin(roll)) >> 14;

	/* Ｐ／Ａビット設定 */
	if (heading > 9000)
		SET_PABIT(PA_K020D);	/* ＋９０゜を越えた場合 */
	else if (heading < -9000)
		SET_PABIT(PA_K020C);	/* －９０゜を越えた場合 */
	else {
		dp.LX20B = MM_TO_DP((heading + 9000) * 4050 / 9000);
		SET_PABIT(PA_K020B);
	}
}

/*=======================================================================
 |
 |		センサＦＯＲ
 |
 |	void	dp_sensor_FOR()
 |
 =======================================================================*/
static	void	dp_sensor_FOR(void)
{
	/* Ｐ／Ａビット設定 */
	switch (sg_mode) {
	case MODE_SAKUTEKI1:
		SET_PABIT(PA_K027A);

		/* ウォーニング２が１の場合対角線を黄色で表示する */
		if ((input_data[27] & 0x00ff) == 1)
			SET_PABIT(PA_K024A);
		break;
	case MODE_SAKUTEKI3:
		SET_PABIT(PA_K027B);

		/* ウォーニング２が１の場合対角線を黄色で表示する */
		if ((input_data[27] & 0x00ff) == 1)
			SET_PABIT(PA_K024B);
		break;
	}

}

/*=======================================================================
 |
 |		センサＬＯＳ（シンボル）
 |
 |	void	dp_sensor_LOS_symbol()
 |
 =======================================================================*/
static	void	dp_sensor_LOS_symbol(void)
{
	short	tmp;

	/* データチェック */
	if (data_check(5) || data_check(6))
		return;

	/* Ｌ１(LY28)の設定 */
	tmp = input_data[6];	/* センサＬＯＳ（エレベーション値）取り出し */
	if (tmp < -3000)	/* ±３０゜に抑える */
		tmp = -3000;
	else if (tmp > 3000)
		tmp = 3000;
	dp.LY28 = MM_TO_DP(tmp / 5 + 600);

	/* Ｌ２(LX28)の設定 */
	tmp = input_data[5];	/* センサＬＯＳ（アジマス値）取り出し */
	dp.LX28 = MM_TO_DP(tmp / 10 + 1800);

	/* Ｐ／Ａビット設定 */
	switch (sg_mode) {
	case MODE_SAKUTEKI1:
		SET_PABIT(PA_K028A);
		break;
	case MODE_SAKUTEKI3:
		SET_PABIT(PA_K028B);
		break;
	}
}

/*=======================================================================
 |
 |		センサＬＯＳ（デジタル）
 |
 |	void	dp_sensor_LOS_digital()
 |
 =======================================================================*/
static	void	dp_sensor_LOS_digital(void)
{
	short	tmp;

	/* データチェック */
	if (data_check(5) || data_check(6))
		return;

	if (digital_update_flag) {
		/* アジマス値のＤ／Ｐ設定 */
		/* 符号（Ｒ，Ｌ）セット */
		tmp = ROUND(input_data[5], 100);	/* 小数以下四捨五入 */
		if (tmp > 0)
			dp.A122[0] = FONT8_R;
		else if (tmp < 0) {
			dp.A122[0] = FONT8_L;
			tmp = -tmp;
		} else
			dp.A122[0] = FONT8_SP;

		/* 数値セット */
		tonumR(tmp, &dp.A122[1], 3, font8_tbl, FONT8_SP);

		/* エレベーション値のＤ／Ｐ設定 */
		/* 符号（＋，－）セット */
		tmp = ROUND(input_data[6], 100);	/* 小数以下四捨五入 */
		if (tmp > 0)
			dp.A121[0] = FONT8_PLUS;
		else if (tmp < 0) {
			dp.A121[0] = FONT8_MINUS;
			tmp = -tmp;
		} else
			dp.A121[0] = FONT8_SP;

		/* 数値セット */
		tonumR(tmp, &dp.A121[1], 3, font8_tbl, FONT8_SP);
	}

	/* Ｐ／Ａビット設定 */
	switch (sg_mode) {
	case MODE_SAKUTEKI1:
	case MODE_SAKUTEKI3:
		SET_PABIT(PA_KD12A);
		break;
	case MODE_SAKUTEKI2:
		SET_PABIT(PA_KD12B);
		break;
	}
}

/*=======================================================================
 |
 |		レーザー照射モード及びレーザーコード
 |
 |	void	dp_laser_mode()
 |
 =======================================================================*/
static	void	dp_laser_mode(void)
{
	static	ushort	legend[4][4] = {
		{FONT8_SP, FONT8_SP, FONT8_SP, FONT8_SP},
		{FONT8_L, FONT8_R, FONT8_F, FONT8_SP},
		{FONT8_D, FONT8_E, FONT8_S, FONT8_G},
		{FONT8_I, FONT8_F, FONT8_F, FONT8_SP}
	};
	short	laser_mode;

	/* データチェック */
	if (data_check(12))
		return;

	/* レジェンドセット */
	laser_mode = input_data[12];
	COPY_WORD(&dp.A142[0], legend[laser_mode], 4);

	/* レーザーコードセット */
	if (laser_mode == 2) {
		dp.A142[4] = FONT8_SP;
		tohex(input_data[11] >> 4, &dp.A142[5], 3, font8_tbl);
		dp.A142[8] = FONT8_SP;
	} else if (laser_mode == 3) {
		tohex(input_data[11], &dp.A142[4], 4, font8_tbl);
		dp.A142[8] = FONT8_SP;
	} else {
		SET_WORD(&dp.A142[4], FONT8_SP, 4);
	}

	/* 色コード設定 */
	dp.C141 = dp.C001;		/* 白／緑 */

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD141);
}

/*=======================================================================
 |
 |		レーザー測距結果
 |
 |	void	dp_laser_sokukyo()
 |
 =======================================================================*/
static	void	dp_laser_sokukyo(void)
{
	/* 最近点 */
	if (data_check(13) == 0) {
		if (input_data[13] != 0) {
			if (digital_update_flag) {
				/* "N " セット */
				dp.A144[0] = FONT8_N;
				dp.A144[1] = FONT8_SP;

				/* 数値セット */
				tonumR(input_data[13], &dp.A144[2], 4,
						font8_tbl, FONT8_SP);

				/* 残りのエリアにスペースをセット */
				dp.A144[6] = dp.A144[7] = dp.A144[8] =
								FONT8_SP;
			}

			/* 色コード設定 */
			dp.C143 = dp.C001;		/* 白／緑 */

			/* Ｐ／Ａビット設定 */
			SET_PABIT(PA_KD143);
		}
	}

	/* 最遠点 */
	if (data_check(14) == 0) {
		if (input_data[14] != 0) {
			if (digital_update_flag) {
				/* "F " セット */
				dp.A145[0] = FONT8_F;
				dp.A145[1] = FONT8_SP;

				/* 数値セット */
				tonumR(input_data[14], &dp.A145[2], 4,
						font8_tbl, FONT8_SP);

				/* 残りのエリアにスペースをセット */
				dp.A145[6] = dp.A145[7] = dp.A145[8] =
								FONT8_SP;
			}

			/* 色コード設定 */
			dp.C144 = dp.C001;		/* 白／緑 */

			/* Ｐ／Ａビット設定 */
			SET_PABIT(PA_KD144);
		}
	}
}

/*=======================================================================
 |
 |		レーザー操作ステータス
 |
 |	void	dp_laser_status()
 |
 =======================================================================*/
static	void	dp_laser_status(void)
{
	/* データチェック */
	if (data_check(8) || data_check(9) || data_check(10) || data_check(12))
		return;

	/* レーザー操作ステータスの中心位置(PX02,PY02)設定 */
	dp.PX02 = DOT_TO_DP(input_data[8]);
	dp.PY02 = DOT_TO_DP(-input_data[9]);

	/* Ｐ／Ａビット設定 */
	if (input_data[10] == 1) {
		/* レーザー操作ステータスが１の場合 */
		SET_PABIT(PA_K002B);
	} else if (input_data[10] == 2) {
		/* レーザー操作ステータスが２の場合 */
		if (data_check(26))	/* レーザーオフ指令データチェック */
			return;

		if (input_data[26] == 0) {
			/* レーザーオフ指令が０の場合 */
			SET_PABIT(PA_K002A);
		} else {
			/* レーザーオフ指令が１の場合 */
			if (blink_flag)		/* 点滅させる */
				SET_PABIT(PA_K002A);
		}
	}
}

/*=======================================================================
 |
 |		ＦＯＶゲート
 |
 |	void	dp_FOV_gate()
 |
 =======================================================================*/
static	void	dp_FOV_gate(void)
{
	short	size2;

	/* データチェック */
	if (data_check(7))
		return;

	/* センサＦＯＶが１８．４゜及び２６．４゜以外の場合表示しない */
	if (input_data[7] != 1840 && input_data[7] != 2640)
		return;

	/* 最小のＦＯＶゲートサイズ（ドット数）の１／２を求める */
	switch (input_data[346]) {
	case 0:		/* １：１モード */
		size2 = (1128 / 4) / 2;
		break;
	case 1:		/* ４：３モード */
		size2 = (853 / 4) / 2;
		break;
	case 2:		/* １：１Ｍｏｄｉｆｉｅｄモード */
		size2 = (1280 / 4) / 2;
		break;
	default:
		return;
	}

	/* ＦＯＶゲートの座標の設定 */
	dp.PX090 = DOT_TO_DP(-size2);
	dp.PY090 = DOT_TO_DP(-size2);
	dp.PX091 = DOT_TO_DP(size2);
	dp.PY091 = DOT_TO_DP(size2);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K009);
}

/*=======================================================================
 |
 |		センサ画像
 |
 |	void	dp_sensor_monitor()
 |
 =======================================================================*/
static	void	dp_sensor_monitor(void)
{
	static	ushort	legend7[4][4] = {
		{FONT7_S, FONT7_M, FONT7_B, FONT7_L},
		{FONT7_T, FONT7_V, FONT7_SP, FONT7_SP},
		{FONT7_F, FONT7_L, FONT7_I, FONT7_R},
		{FONT7_F, FONT7_L, FONT7_I, FONT7_R}
	};
	static	ushort	legend8[4][4] = {
		{FONT8_S, FONT8_M, FONT8_B, FONT8_L},
		{FONT8_T, FONT8_V, FONT8_SP, FONT8_SP},
		{FONT8_F, FONT8_L, FONT8_I, FONT8_R},
		{FONT8_F, FONT8_L, FONT8_I, FONT8_R}
	};
	short	sensor_screen;

	/* データチェック */
	if (data_check(15))
		return;

	sensor_screen = input_data[15];
	switch (sg_mode) {
	case MODE_SAKUTEKI1:	/* 索敵モード１ */
		/* レジェンドセット */
		COPY_WORD(&dp.A111_A, legend8[sensor_screen], 4);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_KD11A);
		break;
	case MODE_SAKUTEKI2:	/* 索敵モード２ */
		/* レジェンドセット */
		COPY_WORD(&dp.A111_B, legend7[sensor_screen], 4);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_KD11B);
		break;
	case MODE_SAKUTEKI3:	/* 索敵モード３ */
		/* レジェンドセット */
		COPY_WORD(&dp.A111_A, legend8[sensor_screen], 4);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_KD11C);
		break;
	}
}

/*=======================================================================
 |
 |		速度
 |
 |	void	dp_speed()
 |
 =======================================================================*/
static	void	dp_speed(void)
{
	/* データチェック */
	if (data_check(0))
		return;

	/* －５１の場合表示しない */
	if (input_data[0] == -51)
		return;

	if (digital_update_flag) {
		/* 数値セット */
		tonumR(ABS(input_data[0]), &dp.A151[0], 3, font8_tbl,
								FONT8_SP);

		/* マイナス符号セット */
		if (input_data[0] < -9)
			dp.A151[0] = FONT8_MINUS;
		else if (input_data[0] < 0)
			dp.A151[1] = FONT8_MINUS;
	}

	/* Ｐ／Ａビット設定 */
	switch (sg_mode) {
	case MODE_SAKUTEKI1:	/* 索敵モード１ */
		SET_PABIT(PA_KD15A);
		break;
	case MODE_SAKUTEKI2:	/* 索敵モード２ */
		SET_PABIT(PA_KD15B);
		break;
	case MODE_SAKUTEKI3:	/* 索敵モード３ */
		SET_PABIT(PA_KD15C);
		break;
	}
}

/*=======================================================================
 |
 |		高度
 |
 |	void	dp_altitude()
 |
 =======================================================================*/
static	void	dp_altitude(void)
{
	/* データチェック */
	if (data_check(1))
		return;

	/* －１００１の場合表示しない */
	if (input_data[1] == -1001)
		return;

	if (digital_update_flag) {
		/* 数値セット */
		tonumR(ABS(input_data[1]), &dp.A161[0], 5, font8_tbl,
								FONT8_SP);

		/* マイナス符号セット */
		if (input_data[1] < -999)
			dp.A161[0] = FONT8_MINUS;
		else if (input_data[1] < -99)
			dp.A161[1] = FONT8_MINUS;
		else if (input_data[1] < -9)
			dp.A161[2] = FONT8_MINUS;
		else if (input_data[1] < 0)
			dp.A161[3] = FONT8_MINUS;
	}

	/* Ｐ／Ａビット設定 */
	switch (sg_mode) {
	case MODE_SAKUTEKI1:	/* 索敵モード１ */
		SET_PABIT(PA_KD16A);
		break;
	case MODE_SAKUTEKI2:	/* 索敵モード２ */
		SET_PABIT(PA_KD16B);
		break;
	case MODE_SAKUTEKI3:	/* 索敵モード３ */
		SET_PABIT(PA_KD16C);
		break;
	}
}

/*=======================================================================
 |
 |		メッセージエリア
 |
 |	void	dp_message()
 |
 =======================================================================*/
static	void	dp_message(void)
{
	static	ushort	*dp_message[3] = {dp.A131, dp.A132, dp.A133};
	static	ushort	*dp_color[3] = {&dp.C130, &dp.C131, &dp.C132};
	static	ushort	pa_a[3] = {PA_KD13A0, PA_KD13A1, PA_KD13A2};
	static	ushort	pa_b[3] = {PA_KD13B0, PA_KD13B1, PA_KD13B2};
	static	ushort	message1[9][9] = {
		{
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_C,  FONT7_A,  FONT7_G,  FONT7_E,  FONT7__,
			FONT7_1,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_C,  FONT7_A,  FONT7_G,  FONT7_E,  FONT7__,
			FONT7_2,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_M,  FONT7_A,  FONT7_N,  FONT7_T,  FONT7_R,
			FONT7_K,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_A,  FONT7_C,  FONT7_Q,  FONT7_SP, FONT7_SP,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_S,  FONT7_C,  FONT7_R,  FONT7_T,  FONT7_R,
			FONT7_K,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_A,  FONT7_U,  FONT7_T,  FONT7_O,  FONT7_T,
			FONT7_R,  FONT7_K,  FONT7_SP, FONT7_SP
		},{
			FONT7_C,  FONT7_O,  FONT7_A,  FONT7_S,  FONT7_T,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_R,  FONT7_E,  FONT7_L,  FONT7_O,  FONT7_C,
			FONT7_K,  FONT7_O,  FONT7_N,  FONT7_SP
		}
	};
	static	ushort	message2[3][9] = {
		{
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_C,  FONT7_E,  FONT7_N,  FONT7_T,  FONT7_R,
			FONT7_K,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_C,  FONT7_O,  FONT7_R,  FONT7_T,  FONT7_R,
			FONT7_K,  FONT7_SP, FONT7_SP, FONT7_SP
		}
	};
	static	ushort	message3[3][9] = {
		{
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_W,  FONT7__,  FONT7_H,  FONT7_O,  FONT7_T,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_B,  FONT7__,  FONT7_H,  FONT7_O,  FONT7_T,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		}
	};
	static	ushort	message4[4][9] = {
		{
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP,
			FONT7_SP, FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_Z,  FONT7_O,  FONT7_O,  FONT7_M,  FONT7__,
			FONT7_3,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_Z,  FONT7_O,  FONT7_O,  FONT7_M,  FONT7__,
			FONT7_5,  FONT7_SP, FONT7_SP, FONT7_SP
		},{
			FONT7_Z,  FONT7_O,  FONT7_O,  FONT7_M,  FONT7__,
			FONT7_8,  FONT7_SP, FONT7_SP, FONT7_SP
		}
	};
	static	ushort	warning1[9] = {
		FONT7_L,  FONT7_A,  FONT7_S,  FONT7_E,  FONT7_R,
		FONT7__,  FONT7_C,  FONT7_N,  FONT7_T
	};
	static	ushort	warning3[9] = {
		FONT7_M,  FONT7_W,  FONT7_S,  FONT7__,  FONT7_F,
		FONT7_A,  FONT7_I,  FONT7_L,  FONT7_SP
	};
	static	ushort	warning4[9] = {
		FONT7_I,  FONT7_F,  FONT7_F,  FONT7__,  FONT7_F,
		FONT7_A,  FONT7_I,  FONT7_L,  FONT7_SP
	};
	static	ushort	data_fail[9] = {
		FONT7_D,  FONT7_A,  FONT7_T,  FONT7_A,  FONT7_SP,
		FONT7_F,  FONT7_A,  FONT7_I,  FONT7_L
	};
	int	n, i;
	ushort	*pa_bit;

	n = 0;
	pa_bit = (sg_mode == MODE_SAKUTEKI3) ? pa_b : pa_a;

	/* ウォーニング１表示 */
	if ((input_data[27] & 0xff00) == 0x0100) {
		COPY_WORD(dp_message[n], warning1, 9);	/* レジェンドセット */
		*dp_color[n] = COLOR_YELLOW;	/* 色コード（黄）セット */
		SET_PABIT(pa_bit[n]);		/* Ｐ／Ａビットセット */
		n++;
	}

	/* ウォーニング３表示 */
	if ((input_data[28] & 0xff00) == 0x0100) {
		COPY_WORD(dp_message[n], warning3, 9);	/* レジェンドセット */
		*dp_color[n] = COLOR_YELLOW;	/* 色コード（黄）セット */
		SET_PABIT(pa_bit[n]);		/* Ｐ／Ａビットセット */
		n++;
	}
		
	/* ウォーニング４表示 */
	if ((input_data[28] & 0x00ff) == 0x0001) {
		COPY_WORD(dp_message[n], warning4, 9);	/* レジェンドセット */
		*dp_color[n] = COLOR_YELLOW;	/* 色コード（黄）セット */
		SET_PABIT(pa_bit[n]);		/* Ｐ／Ａビットセット */
		n++;
	}

	/* "DATA_FAIL" 表示 */
	if (n < 3 && data_error_flag != 0) {
		COPY_WORD(dp_message[n], data_fail, 9);	/* レジェンドセット */
		*dp_color[n] = COLOR_YELLOW;	/* 色コード（黄）セット */
		SET_PABIT(pa_bit[n]);		/* Ｐ／Ａビットセット */
		n++;
	}

	/* メッセージ表示 */
	for (i = 0; n < 3 ;i++, n++) {
		if (message_no[i] == 0)
			break;
		switch (message_no[i]) {
		case 1:
			COPY_WORD(dp_message[n], message1[message_data[0]], 9);
			break;
		case 2:
			COPY_WORD(dp_message[n], message2[message_data[1]], 9);
			break;
		case 3:
			COPY_WORD(dp_message[n], message3[message_data[2]], 9);
			break;
		case 4:
			COPY_WORD(dp_message[n], message4[message_data[3]], 9);
			break;
		}

		*dp_color[n] = dp.C001;		/* 色コード（白／緑）セット */
		SET_PABIT(pa_bit[n]);		/* Ｐ／Ａビットセット */
	}
}

/*=======================================================================
 |
 |		ＤＡＴＡ　ＦＡＩＬ表示
 |
 |	void	dp_data_fail()
 |
 =======================================================================*/
static	void	dp_data_fail(void)
{
	static	ushort	data_fail[9] = {
		FONT7_D,  FONT7_A,  FONT7_T,  FONT7_A,  FONT7_SP,
		FONT7_F,  FONT7_A,  FONT7_I,  FONT7_L
	};

	COPY_WORD(dp.A131, data_fail, 9);
	dp.C130 = COLOR_YELLOW;
	SET_PABIT(sg_mode == MODE_SAKUTEKI3 ? PA_KD13B0 : PA_KD13A0);
}

/*=======================================================================
 |
 |		追尾ゲート
 |
 |	void	dp_tsuibi_gate()
 |
 =======================================================================*/
static	void	dp_tsuibi_gate(void)
{
	short	w, h, x, y;

	/* データチェック */
	if (data_check(16) || data_check(17) || data_check(18) ||
				data_check(19) || data_check(20))
		return;

	/* 追尾ゲートの種類が０に場合は表示しない */
	if (input_data[16] == 0 || input_data[19] == 0 || input_data[20] == 0)
		return;

	/* 横幅の１／２をｍｍに変換 */
	w = DOT_TO_MM(input_data[19]) / 2;

	/* 高さの１／２をｍｍに変換 */
	h = DOT_TO_MM(input_data[20]) / 2;

	/* 追尾ゲートの位置取り出し */
	x = DOT_TO_MM(input_data[17]) - 6345;
	y = 6345 - DOT_TO_MM(input_data[18]);

	/* 左下の座標(PX030,PY030)の設定 */
	dp.PX030 = MM_TO_DP(x - w);
	dp.PY030 = MM_TO_DP(y - h);

	/* 右上の座標(PX031,PY031)の設定 */
	dp.PX031 = MM_TO_DP(x + w);
	dp.PY031 = MM_TO_DP(y + h);

	/* Ｌ７(LX037)の設定 */
	if (w >= 450)			/* ４．５ｍｍ(L2+L8)以上？ */
		dp.LX037 = MM_TO_DP(w * 2 - 900);
	else
		dp.LX037 = 0;

	/* Ｌ８(LX038)の設定 */
	if (w >= 450)			/* ４．５ｍｍ(L2+L8)以上？ */
		dp.LX038 = MM_TO_DP(150);	/* １．５ｍｍ */
	else if (w >= 300)		/* ３ｍｍ(L2)以上？ */
		dp.LX038 = MM_TO_DP(w - 300);
	else
		dp.LX038 = 0;

	/* Ｌ２(LX032)の設定 */
	if (w >= 300)			/* ３ｍｍ(L2)以上？ */
		dp.LX032 = MM_TO_DP(300);	/* ３ｍｍ */
	else if (w >= 30)		/* ０．３ｍｍ以上？ */
		dp.LX032 = MM_TO_DP(w);
	else
		dp.LX032 = MM_TO_DP(30);	/* ０．３ｍｍ */

	/* Ｌ１(LX031)の設定 */
	if (input_data[16] == 2 || input_data[16] == 4)
		dp.LX031 = dp.LX032;
	else
		dp.LX031 = 0;

	/* Ｌ６(LY036)の設定 */
	if (h >= 450)			/* ４．５ｍｍ(L3+L5)以上？ */
		dp.LY036 = MM_TO_DP(h * 2 - 900);
	else
		dp.LY036 = 0;

	/* Ｌ５(LY035)の設定 */
	if (h >= 450)			/* ４．５ｍｍ(L3+L5)以上？ */
		dp.LY035 = MM_TO_DP(150);	/* １．５ｍｍ */
	else if (h >= 300)		/* ３ｍｍ(L3)以上？ */
		dp.LY035 = MM_TO_DP(h - 300);
	else
		dp.LY035 = 0;

	/* Ｌ３(LY033)の設定 */
	if (h >= 300)			/* ３ｍｍ(L3)以上？ */
		dp.LY033 = MM_TO_DP(300);	/* ３ｍｍ */
	else if (h >= 30)		/* ０．３ｍｍ以上？ */
		dp.LY033 = MM_TO_DP(h);
	else
		dp.LY033 = MM_TO_DP(30);	/* ０．３ｍｍ */

	/* Ｌ４(LY034)の設定 */
	if (input_data[16] == 2 || input_data[16] == 3)
		dp.LY034 = dp.LY033;
	else
		dp.LY034 = 0;

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K003);
}

/*=======================================================================
 |
 |		時刻
 |
 |	void	dp_time()
 |
 =======================================================================*/
static	void	dp_time(void)
{
	/* データチェック */
	if (data_check(350) || data_check(351) || data_check(352))
		return;

	/* 時分秒のいずれかが－１の場合表示しない */
	if (input_data[350] == -1 || input_data[351] == -1 ||
						input_data[352] == -1)
		return;

	/* ミサイルがある場合は表示しない */
	if ((input_data[29] & 0xff00))
		return;

	/* 時(A1810,1811)の設定 */
	dp.A181[0] = font8_tbl[input_data[350] / 10];
	dp.A181[1] = font8_tbl[input_data[350] % 10];

	/* 分(A1812,1813)の設定 */
	dp.A181[2] = font8_tbl[input_data[351] / 10];
	dp.A181[3] = font8_tbl[input_data[351] % 10];

	/* 秒(A1814,1815)の設定 */
	dp.A181[4] = font8_tbl[input_data[352] / 10];
	dp.A181[5] = font8_tbl[input_data[352] % 10];

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD18);
}

/*=======================================================================
 |
 |		ミサイルシンボル
 |
 |	void	dp_missile_symbol()
 |
 =======================================================================*/
static	void	dp_missile_symbol(void)
{
	long	x, y;

	/* データチェック */
	if (data_check(7) || data_check(357) || data_check(358))
		return;

	/* センサＦＯＶが０の時は表示しない */
	if (input_data[7] == 0)
		return;

	/* ミサイルのＸ，Ｙ座標計算 */
	x = (long)dot_video * input_data[30] / input_data[7] + input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] - input_data[358];

	/* 画面内にない場合表示しない */
	if (x > 423 || x < -423 || y > 423 || y < -423)
		return;

	/* ミサイル位置（ＰＸ０５，ＰＹ０５）の設定 */
	dp.PX05 = DOT_TO_DP(x);
	dp.PY05 = DOT_TO_DP(y);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K005);
}

/*=======================================================================
 |
 |		ミサイルＬＯＳ
 |
 |	void	dp_missile_LOS()
 |
 =======================================================================*/
static	void	dp_missile_LOS(void)
{
	long	tmp;

	/* データチェック */
	if (data_check(5) || data_check(6))
		return;

	/* エレベーション値を±３０゜に抑える */
	tmp = input_data[6] + input_data[31];
	if (tmp > 3000)
		tmp = 3000;
	else if (tmp < -3000)
		tmp = -3000;

	/* Ｌ１(LY23)の設定 */
	dp.LY23 = MM_TO_DP(tmp / 5 + 600);

	/* アジマス値を±１８０゜に抑える */
	tmp = input_data[5] + input_data[30];
	if (tmp > 18000)
		tmp -= 36000;
	else if (tmp < -18000)
		tmp += 36000;

	/* Ｌ２(LX23)の設定 */
	dp.LX23 = MM_TO_DP(tmp / 10 + 1800);

	/* Ｐ／Ａビット設定 */
	switch (sg_mode) {
	case MODE_SAKUTEKI1:
		SET_PABIT(PA_K023A);
		break;
	case MODE_SAKUTEKI3:
		SET_PABIT(PA_K023B);
		break;
	}
}

/*=======================================================================
 |
 |		ミサイル方位（索敵サイトモード１）
 |
 |	void	dp_missile_heading1()
 |
 =======================================================================*/
static	void	dp_missile_heading1(void)
{
	long	mwaz, mwel, heading;
	short	roll;

	/* データチェック */
	if (data_check(4) || data_check(5) || data_check(6))
		return;

	/* ＭＷAZの計算 */
	mwaz = (long)input_data[30] + (long)input_data[5];
	if (mwaz > 18000)
		mwaz -= 36000;
	else if (mwaz < -18000)
		mwaz += 36000;

	/* ＭＷELの計算 */
	mwel = (long)input_data[31] + (long)input_data[6];
	if (mwel > 9000)
		mwel = 18000 - mwel;
	else if (mwel < -9000)
		mwel = -18000 - mwel;

	/* 計算用にロールを小数第１位まで取り出す */
	roll = ROUND(input_data[4], 10);

	/* ＭＷAZ*cos(Roll) + ＭＷEL*sin(Roll) を計算 */
	heading = (mwaz * cos(roll) + mwel * sin(roll)) >> 14;

	/* Ｐ／Ａビット設定 */
	if (heading > 9000)
		SET_PABIT(PA_K025B);	/* ＋９０゜を越えた場合 */
	else if (heading < -9000)
		SET_PABIT(PA_K025C);	/* －９０゜を越えた場合 */
	else {
		dp.LX25A = MM_TO_DP((heading + 9000) * 4050L / 9000);
		SET_PABIT(PA_K025A);
	}
}

/*=======================================================================
 |
 |		ミサイル方位（索敵サイトモード２）
 |
 |	void	dp_missile_heading2()
 |
 =======================================================================*/
static	void	dp_missile_heading2(void)
{
	long	mwaz, mwel, heading;
	short	roll;

	/* データチェック */
	if (data_check(4) || data_check(5) || data_check(6))
		return;

	/* ＭＷAZの計算 */
	mwaz = (long)input_data[30] + (long)input_data[5];
	if (mwaz > 18000)
		mwaz -= 36000;
	else if (mwaz < -18000)
		mwaz += 36000;

	/* ＭＷELの計算 */
	mwel = (long)input_data[31] + (long)input_data[6];
	if (mwel > 9000)
		mwel = 18000 - mwel;
	else if (mwel < -9000)
		mwel = -18000 - mwel;

	/* 計算用にロールを小数第１位まで取り出す */
	roll = ROUND(input_data[4], 10);

	/* ＭＷAZ*cos(Roll) + ＭＷEL*sin(Roll) を計算 */
	heading = (mwaz * cos(roll) + mwel * sin(roll)) >> 14;

	if (heading > 18000)
		heading -= 36000;	/* ＋１８０゜を越えた場合 */
	else if (heading < -18000)
		heading += 36000;	/* －１８０゜を越えた場合 */

	/* LX25D の設定 */
	dp.LX25D = MM_TO_DP((heading + 18000) * 2160 / 9000);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K025D);
}

/*=======================================================================
 |
 |		ミサイル警報
 |
 |	void	dp_missile_alxxx(void)
 |
 =======================================================================*/
static	void	dp_missile_alxxx(void)
{
	static	ushort	legend[2][7] = {
		{FONT7_M, FONT7_I, FONT7_S, FONT7_S,
		 FONT7_I, FONT7_L, FONT7_E},
		{FONT7_L, FONT7_O, FONT7_S, FONT7_T,
		 FONT7_SP, FONT7_SP, FONT7_SP}
	};
	long	mwaz, mwel;
	short	tmp;

	/* データチェック */
	if (data_check(5) || data_check(6))
		return;

	/* レジェンドセット */
	COPY_WORD(&dp.A103[0], legend[input_data[29] & 0x00ff], 7);

	if (digital_update_flag) {
		/* ＭＷAZの計算 */
		mwaz = (long)input_data[30] + (long)input_data[5];
		if (mwaz > 18000)
			mwaz -= 36000;
		else if (mwaz < -18000)
			mwaz += 36000;

		/* ＭＷELの計算 */
		mwel = (long)input_data[31] + (long)input_data[6];
		if (mwel > 9000)
			mwel = 18000 - mwel;
		else if (mwel < -9000)
			mwel = -18000 - mwel;

		/* エレベーション値設定 */
		tmp = ROUND(mwel, 100);	/* 小数以下四捨五入 */
		if (tmp > 0)
			dp.A101[0] = FONT8_PLUS;
		else if (tmp < 0) {
			dp.A101[0] = FONT8_MINUS;
			tmp = -tmp;
		} else
			dp.A101[0] = FONT8_SP;
		tonumR(tmp, &dp.A101[1], 3, font8_tbl, FONT8_SP);

		/* アジマス値設定 */
		tmp = ROUND(mwaz, 100);	/* 小数以下四捨五入 */
		if (tmp > 0)
			dp.A102[0] = FONT8_R;
		else if (tmp < 0) {
			dp.A102[0] = FONT8_L;
			tmp = -tmp;
		} else
			dp.A102[0] = FONT8_SP;
		tonumR(tmp, &dp.A102[1], 3, font8_tbl, FONT8_SP);
	}

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD10);
}

/*=======================================================================
 |
 |		ミサイル存在方向１
 |
 |	void	dp_missile_sonzai1()
 |
 =======================================================================*/
static	void	dp_missile_sonzai1(void)
{
	long	x, y;

	/* 番号２９の上位８ｂｉｔが２の時は表示しない */
	if ((input_data[29] & 0xff00) == 0x200)
		return;

	/* ±９０゜の範囲にない場合は表示しない */
	if (input_data[30] > 9000 || input_data[30] < -9000)
		return;

	/* データチェック */
	if (data_check(7) || data_check(8) || data_check(9) ||
					data_check(357) || data_check(358))
		return;

	/* センサＦＯＶが０の時は表示しない */
	if (input_data[7] == 0)
		return;

	/* ミサイルシンボルと重なる場合は表示しない */
	x = (long)dot_video * input_data[30] / input_data[7] - input_data[8] +
						input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] + input_data[9] -
						input_data[358];
	if (DOT_TO_MM(sqrt(x * x + y * y)) <= 1800)
		return;

	/* 角度(R06)の設定 */
	dp.R06 = atan((short)y, (short)x);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K006);
}

/*=======================================================================
 |
 |		ミサイル表示方向
 |
 |	void	dp_missile_hyouji()
 |
 =======================================================================*/
static	void	dp_missile_hyouji(void)
{
	long	x, y;
	short	l;

	/* 番号２９の上位８ｂｉｔが１の時は表示しない */
	if ((input_data[29] & 0xff00) == 0x100)
		return;

	/* データチェック */
	if (data_check(7) || data_check(8) || data_check(9) ||
					data_check(357) || data_check(358))
		return;

	/* センサＦＯＶが０の時は表示しない */
	if (input_data[7] == 0)
		return;

	/* ミサイル位置のＸ，Ｙ座標計算 */
	x = (long)dot_video * input_data[30] / input_data[7] + input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] - input_data[358];

	/* 画面内にない場合表示しない */
	if (x > 423 || x < -423 || y > 423 || y < -423)
		return;

	/* レティクルからの相対位置に変換 */
	x -= input_data[8];
	y += input_data[9];

	/* 長さ計算 */
	l = DOT_TO_MM(sqrt(x * x + y * y)) - 300;

	/* 長さが０以下の場合は表示しない */
	if (l < 0)
		return;

	/* 線の長さ(LX08)の設定 */
	dp.LX08 = MM_TO_DP(l);

	/* 角度(R08)の設定 */
	dp.R08 = atan((short)y, (short)x);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K008);
}

/*=======================================================================
 |
 |		ミサイル存在方向２
 |
 |	void	dp_missile_sonzai2()
 |
 =======================================================================*/
static	void	dp_missile_sonzai2(void)
{
	register long	x, y, rx, ry, tx, ty, len;

	/* 番号２９の上位８ｂｉｔが１の時は表示しない */
	if ((input_data[29] & 0xff00) == 0x100)
		return;

	/* データチェック */
	if (data_check(7) || data_check(8) || data_check(9) ||
					data_check(357) || data_check(358))
		return;

	/* センサＦＯＶが０の時は表示しない */
	if (input_data[7] == 0)
		return;

	/* ±９０゜の範囲にない場合は表示しない */
	if (input_data[30] > 9000 || input_data[30] < -9000)
		return;

	/* ミサイル位置のＸ，Ｙ座標計算 */
	x = (long)dot_video * input_data[30] / input_data[7] + input_data[357];
	y = (long)dot_video * input_data[31] / input_data[7] - input_data[358];

	/* 画面内にある場合表示しない */
	if (x <= 423 && x >= -423 && y <= 423 && y >= -423)
		return;

	/* レティクルからの相対位置に変換 */
	rx = x - input_data[8];
	ry = y + input_data[9];

	/* 角度(R07)の設定 */
	dp.R07 = atan((short)ry, (short)rx);

	/* Ｘ，Ｙ方向の位置の絶対値を求める */
	x = ABS(x);
	y = ABS(y);
	rx = ABS(rx);
	ry = ABS(ry);

	/* Ｘ方向の長さを画面内（４００ドット以内）に収める */
	if (x > 400) {
		tx = rx - (x - 400);
		ty = ry * tx / rx;
		y -= ry - ty;
		rx = tx;
		ry = ty;
	}

	/* Ｙ方向の長さを画面内（３２０ドット以内）に収める */
	if (y > 320) {
		tx = ry - (y - 320);
		rx = rx * tx / ry;
		ry = tx;
	}

	/* 長さを求める */
	len = DOT_TO_MM(sqrt(rx * rx + ry * ry));

	/* 長さが４．０５ｍｍ（矢印の長さ）より短い場合は表示しない */
	if (len < 405)
		return;

	/* 長さ(LX07)の設定 */
	dp.LX07 = MM_TO_DP(len - 405);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K007);
}

/*=======================================================================
 |
 |		ＩＦＦゲート
 |
 |	void	dp_IFF_gate()
 |
 =======================================================================*/
static	void	dp_IFF_gate(void)
{
	dp_IFF_gate1();
	dp_IFF_gate2();
	dp_IFF_gate3();
}

/*=======================================================================
 |
 |		ＩＦＦゲート１
 |
 |	void	dp_IFF_gate1()
 |
 =======================================================================*/
static	void	dp_IFF_gate1(void)
{
	/* データチェック */
	if (data_check(32) || data_check(33) ||
				data_check(34) || data_check(35))
		return;

	/* サイズが０の場合表示しない */
	if (input_data[35] == 0)
		return;

	switch (input_data[34]) {
	case 0:
		/* ＩＦＦゲートａ　Ｄ／Ｐ計算 */
		dp_IFF_gateA(32, &dp.IFF_gate_a[0]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004A0);
		break;
	case 1:
		/* ＩＦＦゲートｂ　Ｄ／Ｐ計算 */
		dp_IFF_gateB(32, &dp.IFF_gate_b[0]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004B0);
		break;
	case 2:
		/* ＩＦＦゲートｃ　Ｄ／Ｐ計算 */
		dp_IFF_gateC(32, &dp.IFF_gate_c[0]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004C0);
		break;
	}
}

/*=======================================================================
 |
 |		ＩＦＦゲート２
 |
 |	void	dp_IFF_gate2()
 |
 =======================================================================*/
static	void	dp_IFF_gate2(void)
{
	/* データチェック */
	if (data_check(36) || data_check(37) ||
				data_check(38) || data_check(39))
		return;

	/* サイズが０の場合表示しない */
	if (input_data[39] == 0)
		return;

	switch (input_data[38]) {
	case 0:
		/* ＩＦＦゲートａ　Ｄ／Ｐ計算 */
		dp_IFF_gateA(36, &dp.IFF_gate_a[1]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004A1);
		break;
	case 1:
		/* ＩＦＦゲートｂ　Ｄ／Ｐ計算 */
		dp_IFF_gateB(36, &dp.IFF_gate_b[1]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004B1);
		break;
	case 2:
		/* ＩＦＦゲートｃ　Ｄ／Ｐ計算 */
		dp_IFF_gateC(36, &dp.IFF_gate_c[1]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004C1);
		break;
	}
}

/*=======================================================================
 |
 |		ＩＦＦゲート３
 |
 |	void	dp_IFF_gate3()
 |
 =======================================================================*/
static	void	dp_IFF_gate3(void)
{
	/* データチェック */
	if (data_check(40) || data_check(41) ||
				data_check(42) || data_check(43))
		return;

	/* サイズが０の場合表示しない */
	if (input_data[43] == 0)
		return;

	switch (input_data[42]) {
	case 0:
		/* ＩＦＦゲートａ　Ｄ／Ｐ計算 */
		dp_IFF_gateA(40, &dp.IFF_gate_a[2]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004A2);
		break;
	case 1:
		/* ＩＦＦゲートｂ　Ｄ／Ｐ計算 */
		dp_IFF_gateB(40, &dp.IFF_gate_b[2]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004B2);
		break;
	case 2:
		/* ＩＦＦゲートｃ　Ｄ／Ｐ計算 */
		dp_IFF_gateC(40, &dp.IFF_gate_c[2]);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K004C2);
		break;
	}
}

/*=======================================================================
 |
 |		ＩＦＦゲートａ　Ｄ／Ｐ計算
 |
 |	void	dp_IFF_gateA()
 |
 =======================================================================*/
static	void	dp_IFF_gateA(short dno, IFF_GATE_A *dpp)
{
	short	l, l2, x, y;

	/* ＩＦＦゲートのサイズ取り出し */
	l = DOT_TO_MM(input_data[dno + 3]);

	/* 最小サイズは１．８ｍｍ */
	if (l < 180)
		l = 180;

	/* 長さの１／２を求める */
	l2 = l / 2;

	/* ＩＦＦゲートの位置取り出し */
	x = DOT_TO_MM(input_data[dno]) - 6345;
	y = 6345 - DOT_TO_MM(input_data[dno + 1]);

	/* 左下の位置のＤ／Ｐの設定 */
	dpp->PX04A_0 = MM_TO_DP(x - l2);
	dpp->PY04A_0 = MM_TO_DP(y - l2);

	/* 右上の位置のＤ／Ｐの設定 */
	dpp->PX04A_1 = MM_TO_DP(x + l2);
	dpp->PY04A_1 = MM_TO_DP(y + l2);

	/* 長さのＤ／Ｐの設定 */
	dpp->L04A_0 = MM_TO_DP(l);
}

/*=======================================================================
 |
 |		ＩＦＦゲートｂ　Ｄ／Ｐ計算
 |
 |	void	dp_IFF_gateB()
 |
 =======================================================================*/
static	void	dp_IFF_gateB(short dno, IFF_GATE_B *dpp)
{
	short	l, r, x, y;

	/* ＩＦＦゲートのサイズ取り出し */
	l = DOT_TO_MM(input_data[dno + 3]);

	/* 最小サイズは１．８ｍｍ */
	if (l < 180)
		l = 180;

	/* 半径を求める */
	r = l / 2;

	/* ＩＦＦゲートの位置取り出し */
	x = DOT_TO_MM(input_data[dno]) - 6345;
	y = 6345 - DOT_TO_MM(input_data[dno + 1]);

	/* 円の下端のＤ／Ｐの設定 */
	dpp->PX04B_0 = MM_TO_DP(x);
	dpp->PY04B_0 = MM_TO_DP(y - r);

	/* 弧の長さのＤ／Ｐの設定 */
	dpp->L04B_0 = MM_TO_DP(ARC_LENGTH(r));

	/* 半径のＤ／Ｐの設定 */
	dpp->L04B_1 = radius(r);
}

/*=======================================================================
 |
 |		ＩＦＦゲートｃ　Ｄ／Ｐ計算
 |
 |	void	dp_IFF_gateC()
 |
 =======================================================================*/
static	void	dp_IFF_gateC(short dno, IFF_GATE_C *dpp)
{
	short	l, l2, x, y;

	/* ＩＦＦゲートのサイズ取り出し */
	l = DOT_TO_MM(input_data[dno + 3]);

	/* 最小サイズは１．８ｍｍ */
	if (l < 180)
		l = 180;

	/* 長さの１／?２を求める */
	l2 = (short)((l * 0xb504L) >> 16);

	/* ＩＦＦゲートの位置取り出し */
	x = DOT_TO_MM(input_data[dno]) - 6345;
	y = 6345 - DOT_TO_MM(input_data[dno + 1]);

	/* 上側の位置のＤ／Ｐの設定 */
	dpp->PX04C_0 = MM_TO_DP(x);
	dpp->PY04C_0 = MM_TO_DP(y + l2);

	/* 左側の位置のＤ／Ｐの設定 */
	dpp->PX04C_1 = MM_TO_DP(x - l2);
	dpp->PY04C_1 = MM_TO_DP(y);

	/* 下側の位置のＤ／Ｐの設定 */
	dpp->PX04C_2 = MM_TO_DP(x);
	dpp->PY04C_2 = MM_TO_DP(y - l2);

	/* 右側の位置のＤ／Ｐの設定 */
	dpp->PX04C_3 = MM_TO_DP(x + l2);
	dpp->PY04C_3 = MM_TO_DP(y);

	/* 長さのＤ／Ｐの設定 */
	dpp->L04C_0 = MM_TO_DP(l);
}

/*=======================================================================
 |
 |		応答コード
 |
 |	void	dp_answer_code()
 |
 =======================================================================*/
static	void	dp_answer_code(void)
{
	static	ushort	legend[4] = {
		FONT8_A, FONT8_N, FONT8_S, FONT8_SP
	};

	/* データチェック */
	if (data_check(353))
		return;

	/* 応答コードが０の場合表示しない */
	if (input_data[353] == 0)
		return;

	/* レジェンドセット */
	COPY_WORD(&dp.A143[0], legend, 4);

	/* 応答コードセット */
	tohex(input_data[353], &dp.A143[4], 4, font8_tbl);

	/* 残りのエリアにスペースをセット */
	dp.A143[8] = FONT8_SP;

	/* 色コード設定 */
	dp.C142 = dp.C001;		/* 白／緑 */

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD142);
}

/*=======================================================================
 |
 |		障害物シンボル
 |
 |	void	dp_obst_symbol()
 |
 =======================================================================*/
static	void	dp_obst_symbol(void)
{
	int	n;
	short	*datap, fov, fov2, s1, s2;

	/* データチェック */
	if (data_check(44) || data_check(7) ||
				data_check(354) || data_check(355))
		return;

	fov = input_data[7];	/* センサＦＯＶ */

	/* センサＦＯＶが０の場合は表示しない */
	if (fov == 0)
		return;

	/* しきい値１，２取り出し */
	s1 = input_data[354];
	s2 = input_data[355];
	if (s2 <= s1)
		s2 = 1000;

	fov2 = fov / 2;
	datap = &input_data[46];	/* 障害物入力データ先頭アドレス */
	/* 最大１００個の障害物シンボルを描く */
	for (n = 0; n < 100; n++, datap += 3) {
		/* 距離が０の場合表示しない */
		if (datap[2] == 0)
			continue;

		/* データチェック */
		if (CHECK(datap[0], -18000, 18000) ||
				CHECK(datap[1], -8999, 8999) ||
				CHECK(datap[2], 0, 999)) {
			data_error_flag = 1;
			continue;
		}

		/* ＦＯＶゲート内にない場合表示しない */
		if (ABS(datap[0]) > fov2 || ABS(datap[1]) > fov2)
			continue;

		/* Ｘ，Ｙ座標の設定 */
		dp.obst[n].PX100 = DOT_TO_DP((long)dot_video * datap[0] / fov);
		dp.obst[n].PY100 = DOT_TO_DP((long)dot_video * datap[1] / fov);

		/* Ｐ／Ａビット設定 */
		if (datap[2] < s1)
			SET_PABIT(PA_K0200 + n);/* しきい値１未満（赤） */
		else if (datap[2] < s2)
			SET_PABIT(PA_K0100 + n);/* しきい値２未満（黄） */
		else
			SET_PABIT(PA_K0300 + n);/* しきい値２以上（緑） */
	}
}

/*=======================================================================
 |
 |		障害物までの最短距離
 |
 |	void	dp_obst_kyori()
 |
 =======================================================================*/
static	void	dp_obst_kyori(void)
{
	short	s1, s2;

	static	ushort	legend[5] = {
		FONT8_O, FONT8_B, FONT8_S, FONT8_T, FONT8_SP
	};

	/* データチェック */
	if (data_check(44) || data_check(45) ||
				data_check(354) || data_check(355))
		return;

	/* レジェンドセット */
	COPY_WORD(&dp.A141[0], legend, 5);

	if (digital_update_flag) {
		/* 距離セット */
		if (input_data[45] == 0) {
			SET_WORD(&dp.A141[5], FONT8_SP, 4);
			dp.C140 = COLOR_YELLOW;		/* 黄 */
		} else {
			tonumL(input_data[45], &dp.A141[5], 3, font8_tbl,
								FONT8_SP);

			/* 残りのエリアにスペースをセット */
			dp.A141[8] = FONT8_SP;

			/* しきい値１，２取り出し */
			s1 = input_data[354];
			s2 = input_data[355];
			if (s2 <= s1)
				s2 = 1000;

			/* 色コードセット */
			if (input_data[45] < s1)
				dp.C140 = COLOR_RED;		/* 赤 */
			else if (input_data[45] < s2)
				dp.C140 = COLOR_YELLOW;		/* 黄 */
			else
				dp.C140 = COLOR_GREEN;		/* 緑 */
		}
	}

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD140);
}

/*=======================================================================
 |
 |		任意図形
 |
 |	void	dp_zukei()
 |
 =======================================================================*/
static	void	dp_zukei(void)
{
	int	n;
	short	dno;
	ushort	pa_bit0, pa_bit1, pa_bit2, pa_bit3;

	dno = 384;
	pa_bit0 = PA_K050A;
	pa_bit1 = PA_K051A;
	pa_bit2 = PA_K052A;
	pa_bit3 = PA_K053A;
	for (n = 0; n < 18; n++, dno += 7,
				pa_bit0++, pa_bit1++, pa_bit2++, pa_bit3++) {
		/* 図形ｎの種類が０の場合、以降を表示しない */
		if (input_data[dno] == 0)
			break;

		switch (input_data[dno]) {
		case 1:		/* 線分 */
			dp_zukei0(dno, &dp.zukei0[n],
					&dp.zukei0[n].C05, pa_bit0);
			break;
		case 2:		/* 長方形 */
			if (input_data[dno+6] == 0)
				/* 塗りつぶし無し */
				dp_zukei1(dno, &dp.zukei1[n],
						&dp.zukei0[n].C05, pa_bit1);
			else if (input_data[dno+6] == 1)
				/* 塗りつぶし有り */
				dp_zukei2(dno, &dp.zukei2[n],
						&dp.zukei0[n].C05, pa_bit2);
			else
				/* データエラー */
				data_error_flag = 1;
			break;
		case 3:		/* 円 */
			dp_zukei3(dno, &dp.zukei3[n],
					&dp.zukei0[n].C05, pa_bit3);
			break;
		default:	/* データエラー */
			data_error_flag = 1;
			break;
		}
	}
}

/*=======================================================================
 |
 |		任意図形（線分）
 |
 |	void	dp_zukei0()
 |
 =======================================================================*/
static	void	dp_zukei0(short dno, ZUKEI0 *dpp, ushort *color, short pa_bit)
{
	short	x1, y1, x2, y2;
	short	lx, ly;

	/* データチェック */
	if (CHECK(input_data[dno+1], 0, 845) ||
			CHECK(input_data[dno+2], 0, 845) ||
			CHECK(input_data[dno+3], 0, 845) ||
			CHECK(input_data[dno+4], 0, 845) ||
			CHECK(input_data[dno+5], 1, 7)) {
		data_error_flag = 1;
		return;
	}

	/* 描画開始座標の取り出し */
	x1 = DOT_TO_MM(input_data[dno+1]) - 6345;
	y1 = 6345 - DOT_TO_MM(input_data[dno+2]);

	/* 描画終了座標の取り出し */
	x2 = DOT_TO_MM(input_data[dno+3]) - 6345;
	y2 = 6345 - DOT_TO_MM(input_data[dno+4]);

	/* x1,y1からのx2,y2の相対位置を求める */
	lx = x2 - x1;
	ly = y2 - y1;

	/* 線分の描画開始位置(PX50x,PY50x)の設定 */
	dpp->PX50 = MM_TO_DP(x1);
	dpp->PY50 = MM_TO_DP(y1);

	/* 線分の角度(D50x)の設定 */
	dpp->D50 = atan(ly, lx);

	/* 線分の長さ(L50x)の設定 */
	dpp->L50 = MM_TO_DP(sqrt((long)lx * (long)lx + (long)ly * (long)ly));

	/* 色(C05x)の設定 */
	*color = zukei_color_tbl[input_data[dno+5]];

	/* Ｐ／Ａビット設定 */
	SET_PABIT(pa_bit);
}

/*=======================================================================
 |
 |		任意図形（長方形）
 |
 |	void	dp_zukei1()
 |
 =======================================================================*/
static	void	dp_zukei1(short dno, ZUKEI1 *dpp, ushort *color, short pa_bit)
{
	short	x1, y1, x2, y2;

	/* データチェック */
	if (CHECK(input_data[dno+1], 0, 845) ||
			CHECK(input_data[dno+2], 0, 845) ||
			CHECK(input_data[dno+3], 0, 845) ||
			CHECK(input_data[dno+4], 0, 845) ||
			CHECK(input_data[dno+5], 1, 7)) {
		data_error_flag = 1;
		return;
	}

	/* 描画開始座標の取り出し */
	x1 = DOT_TO_MM(input_data[dno+1]) - 6345;
	y1 = 6345 - DOT_TO_MM(input_data[dno+2]);

	/* 描画終了座標の取り出し */
	x2 = DOT_TO_MM(input_data[dno+3]) - 6345;
	y2 = 6345 - DOT_TO_MM(input_data[dno+4]);

	/* 左上をx1,y1とする */
	if (x1 > x2)
		SWAP(x1, x2);
	if (y1 < y2)
		SWAP(y1, y2);

	/* 描画開始位置(PX51x,PY51x)の設定 */
	dpp->PX51 = MM_TO_DP(x1);
	dpp->PY51 = MM_TO_DP(y1);

	/* 辺の長さ(LX51x,LY51x)の設定 */
	dpp->LX51 = MM_TO_DP(x2 - x1);
	dpp->LY51 = MM_TO_DP(y1 - y2);

	/* 色(C05x)の設定 */
	*color = zukei_color_tbl[input_data[dno+5]];

	/* Ｐ／Ａビット設定 */
	SET_PABIT(pa_bit);
}

/*=======================================================================
 |
 |		任意図形（塗りつぶし長方形）
 |
 |	void	dp_zukei2()
 |
 =======================================================================*/
static	void	dp_zukei2(short dno, ZUKEI2 *dpp, ushort *color, short pa_bit)
{
	short	x1, y1, x2, y2;
	ushort	lx, ly;

	/* データチェック */
	if (CHECK(input_data[dno+1], 0, 845) ||
			CHECK(input_data[dno+2], 0, 845) ||
			CHECK(input_data[dno+3], 0, 845) ||
			CHECK(input_data[dno+4], 0, 845) ||
			CHECK(input_data[dno+5], 1, 7)) {
		data_error_flag = 1;
		return;
	}

	/* 描画開始座標の取り出し */
	x1 = DOT_TO_MM(input_data[dno+1]) - 6345;
	y1 = 6345 - DOT_TO_MM(input_data[dno+2]);

	/* 描画終了座標の取り出し */
	x2 = DOT_TO_MM(input_data[dno+3]) - 6345;
	y2 = 6345 - DOT_TO_MM(input_data[dno+4]);

	/* 左下をx1,y1とする */
	if (x1 > x2)
		SWAP(x1, x2);
	if (y1 > y2)
		SWAP(y1, y2);

	/* 辺の長さは１～２０ｍｍまでとする */
	lx = x2 - x1;
	ly = y2 - y1;
	if (lx < 100 || lx > 2000 || ly < 100 || ly > 2000) {
		data_error_flag = 1;
		return;
	}

	/* 中心座標(PX52x0,PY52x0)の設定 */
	dpp->PX52_0 = MM_TO_DP((x1 + x2) / 2);
	dpp->PY52_0 = MM_TO_DP((y1 + y2) / 2);

	/* FIX WINDOWの左下位置(PX52x1,PY52x1)の設定 */
	dpp->PX52_1 = MM_TO_DP(x1);
	dpp->PY52_1 = MM_TO_DP(y1);

	/* FIX WINDOWの右上位置(PX52x2,PY52x2)の設定 */
	dpp->PX52_2 = MM_TO_DP(x2);
	dpp->PY52_2 = MM_TO_DP(y2);

	/* COLOR WINDOWの左下位置(PX52x3,PY52x3)の設定 */
	dpp->PX52_3 = MM_TO_DP(x1-10);
	dpp->PY52_3 = MM_TO_DP(y1-10);

	/* COLOR WINDOWの右上位置(PX52x4,PY52x4)の設定 */
	dpp->PX52_4 = MM_TO_DP(x2+10);
	dpp->PY52_4 = MM_TO_DP(y2+10);

	/* 色(C05x)の設定 */
	*color = zukei_color_tbl[input_data[dno+5]];

	/* Ｐ／Ａビット設定 */
	SET_PABIT(pa_bit);
}

/*=======================================================================
 |
 |		任意図形（円）
 |
 |	void	dp_zukei3()
 |
 =======================================================================*/
static	void	dp_zukei3(short dno, ZUKEI3 *dpp, ushort *color, short pa_bit)
{
	short	x, y, r;

	/* データチェック */
	if (CHECK(input_data[dno+1], 0, 845) ||
			CHECK(input_data[dno+2], 0, 845) ||
			CHECK(input_data[dno+3], 0, 845) ||
			CHECK(input_data[dno+4], 1, 7)) {
		data_error_flag = 1;
		return;
	}

	/* 中心座標の取り出し */
	x = DOT_TO_MM(input_data[dno+1]) - 6345;
	y = 6345 - DOT_TO_MM(input_data[dno+2]);

	/* 半径の取り出し */
	r = DOT_TO_MM(input_data[dno+3]);
	if (r < 100)
		r = 90;		/* 半径の最小値は０．９ｍｍとする */

	/* 描画開始位置(PX53,PY53)の設定 */
	dpp->PX53 = MM_TO_DP(x);
	dpp->PY53 = MM_TO_DP(y - r);

	/* 弧の長さ(LX53x0)の設定 */
	dpp->L53_0 = MM_TO_DP(ARC_LENGTH(r));

	/* 半径(L53x1)の設定 */
	dpp->L53_1 = radius(r);

	/* 色(C05x)の設定 */
	*color = zukei_color_tbl[input_data[dno+4]];

	/* Ｐ／Ａビット設定 */
	SET_PABIT(pa_bit);
}

/*=======================================================================
 |
 |		方位目盛り（索敵サイトモード２）
 |
 |	void	dp_heading_scale2()
 |
 =======================================================================*/
static	void	dp_heading_scale2(void)
{
	/* データチェック */
	if (data_check(2))
		return;

	/* ＬＸ２１Ａ設定 */
	dp.LX21A  = MM_TO_DP((ushort)input_data[2] * 12 / 5 + 4320);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K021A0);
	SET_PABIT(PA_K021A1);
	SET_PABIT(PA_K021A2);
}

/*=======================================================================
 |
 |		センサ方位（索敵サイトモード２）
 |
 |	void	dp_sensor_heading2()
 |
 =======================================================================*/
static	void	dp_sensor_heading2(void)
{
	short	roll;
	long	heading;

	/* データチェック */
	if (data_check(4) || data_check(5) || data_check(6))
		return;

	/* 計算用にロールを小数第１位まで取り出す */
	roll = ROUND(input_data[4], 10);

	/* AZ*cos(Roll) + EL*sin(Roll) を計算 */
	heading = ((long)input_data[5] * (long)cos(roll) +
			(long)input_data[6] * (long)sin(roll)) >> 14;

	if (heading > 18000)
		heading -= 36000;	/* ＋１８０゜を越えた場合 */
	else if (heading < -18000)
		heading += 36000;	/* －１８０゜を越えた場合 */

	/* ウォーニング２（空間安定装置制御部）が１の場合は黄色で表示 */
	if ((input_data[27] & 0x00ff) == 1) {
		/* LX21C設定 */
		dp.LX21C = MM_TO_DP((heading + 18000) * 2160 / 9000);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K021C);
	} else {
		/* LX21B設定 */
		dp.LX21B = MM_TO_DP((heading + 18000) * 2160 / 9000);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K021B);
	}
}

/*=======================================================================
 |
 |		ピッチ目盛り
 |
 |	void	dp_pitch_scale()
 |
 =======================================================================*/
static	void	dp_pitch_scale(void)
{
	/* データチェック */
	if (data_check(3))
		return;

	/* ＬＹ２２Ａ設定 */
	dp.LY22A  = MM_TO_DP((input_data[3] + 6000) * 1440L / 6000);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K022A);
}

/*=======================================================================
 |
 |		センサピッチ
 |
 |	void	dp_sensor_pitch()
 |
 =======================================================================*/
static	void	dp_sensor_pitch(void)
{
	long	az, el;
	short	roll, pitch;

	/* データチェック */
	if (data_check(4) || data_check(5) || data_check(6))
		return;

	/* センサＬＯＳのアジマス値補正 */
	az = input_data[5];
	if (az > 9000)
		az = 18000 - az;
	else if (az < -9000)
		az = -18000 - az;

	/* センサＬＯＳのエレベーション値取り出し */
	el = input_data[6];

	/* 計算用にロールを小数第１位まで取り出す */
	roll = ROUND(input_data[4], 10);

	/* AZ*sin(Roll) + EL*cos(Roll) を計算 */
	pitch = (short)((az * sin(roll) + el * cos(roll)) >> 14);

	/* 範囲補正 */
	if (pitch > 6000)
		pitch = 6000;
	else if (pitch < -6000)
		pitch = -6000;

	/* ウォーニング２（空間安定装置制御部）が１の場合は黄色で表示 */
	if ((input_data[27] & 0x00ff) == 1) {
		/* LY22C設定 */
		dp.LY22C = MM_TO_DP((pitch + 6000) * 1440L / 6000);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K022C);
	} else {
		/* LY22B設定 */
		dp.LY22B = MM_TO_DP((pitch + 6000) * 1440L / 6000);

		/* Ｐ／Ａビット設定 */
		SET_PABIT(PA_K022B);
	}
}

/*=======================================================================
 |
 |		ミサイルピッチ
 |
 |	void	dp_missile_pitch()
 |
 =======================================================================*/
static	void	dp_missile_pitch(void)
{
	long	mwaz, mwel;
	short	roll, pitch;

	/* データチェック */
	if (data_check(4) || data_check(5) || data_check(6))
		return;

	/* ＭＷAZの計算 */
	mwaz = (long)input_data[30] + (long)input_data[5];
	if (mwaz > 9000)
		mwaz = 18000 - mwaz;
	else if (mwaz < -9000)
		mwaz = -18000 - mwaz;

	/* ＭＷELの計算 */
	mwel = (long)input_data[31] + (long)input_data[6];
	if (mwel > 9000)
		mwel = 18000 - mwel;
	else if (mwel < -9000)
		mwel = -18000 - mwel;

	/* 計算用にロールを小数第１位まで取り出す */
	roll = ROUND(input_data[4], 10);

	/* ＭＷAZ*sin(Roll) + ＭＷEL*cos(Roll) + Pitch を計算 */
	pitch = (short)((mwaz * sin(roll) + mwel * cos(roll)) >> 14);

	/* 範囲補正 */
	if (pitch > 6000)
		pitch = 6000;
	else if (pitch < -6000)
		pitch = -6000;

	/* LY26設定 */
	dp.LY26 = MM_TO_DP((pitch + 6000) * 1440L / 6000);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_K026);
}

/*=======================================================================
 |
 |		機首方位（デジタル）
 |
 |	void	dp_heading_digital()
 |
 =======================================================================*/
static	void	dp_heading_digital(void)
{
	short	tmp;

	/* データチェック */
	if (data_check(2))
		return;

	if (digital_update_flag) {
		/* 数値セット */
		tmp = ROUND(input_data[2], 10);		/* 小数以下四捨五入 */
		tonumR(tmp, &dp.A171[0], 3, font8_tbl, FONT8_0);
	}

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD17);
}

/*=======================================================================
 |
 |		更新レート
 |
 |	void	dp_update_rate()
 |
 =======================================================================*/
static	void	dp_update_rate(void)
{
	if (rate_dsp_flag == 0)
		return;

	/* 表示の更新レートセット */
	tonumR(rate_gscint, &dp.A191[0], 2, font8_tbl, FONT8_0);

	/* １５５３Ｂバスの更新レートセット */
	tonumR(rate_1553B, &dp.A191[2], 2, font8_tbl, FONT8_0);

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD19);
}

/*=======================================================================
 |
 |		ミサイル警戒画面
 |
 |	void	dp_missile()
 |
 =======================================================================*/
static	void	dp_missile(void)
{
	long	mwaz, mwel;

	/* ＭＷAZの計算 */
	if (data_check(5) || data_check(30))
		mwaz = -18001;		/* データエラー */
	else {
		mwaz = (long)input_data[30] + (long)input_data[5];
		if (mwaz > 18000)
			mwaz -= 36000;
		else if (mwaz < -18000)
			mwaz += 36000;
	}

	/* ＭＷELの計算 */
	if (data_check(6) || data_check(31))
		mwel = -9001;		/* データエラー */
	else {
		mwel = (long)input_data[31] + (long)input_data[6];
		if (mwel > 9000)
			mwel = 18000 - mwel;
		else if (mwel < -9000)
			mwel = -18000 - mwel;
	}

	dp_missile_s1((short)mwaz, (short)mwel);
	dp_missile_s2((short)mwaz, (short)mwel);
	dp_missile_digital((short)mwaz, (short)mwel);
}

/*=======================================================================
 |
 |		機体後方からのシンボル
 |
 |	void	dp_missile_s1()
 |
 =======================================================================*/
static	void	dp_missile_s1(short mwaz, short mwel)
{
	short	tmp_mwaz, tmp_mwel, x, y, L2;
	long	L1_2;
	ushort	theta, alpha;

	if (mwaz == -18001 || mwel == -9001) {
		/* データエラーの場合は外周円と機体シルエットのみ表示 */
		SET_PABIT(PA_K044A);	/* 外周円と方向線 */
		SET_PABIT(PA_K042A);	/* 機体シルエット */
		return;
	}

	tmp_mwaz = ROUND(mwaz, 10);
	tmp_mwel = ROUND(mwel, 10);
	x = (short)((((2790L * sin(tmp_mwaz)) >> 14) * cos(tmp_mwel)) >> 14);
	y = (short)((2790L * sin(tmp_mwel)) >> 14);

	/* L1*L1 の計算 */
	L1_2 = (long)x * (long)x + (long)y * (long)y;

	/* L2 の計算 */
	if (L1_2 > 41006L)
		L2 = sqrt(L1_2 - 41006L);
	else
		L2 = 0;

	/* L40A の設定 */
	dp.L40A = MM_TO_DP(L2);

	/* θの計算 */
	theta = atan(y, x);

	/* α／２の計算 */
	alpha = atan(405 / 2, L2);

	/* D40A0～D40A4 の設定 */
	dp.D40A0 = theta + alpha;
	dp.D40A1 = theta + alpha / 2;
	dp.D40A2 = theta;
	dp.D40A3 = theta - alpha / 2;
	dp.D40A4 = theta - alpha;

	/* PX40Bの設定 */
	dp.PX40B = MM_TO_DP(x);

	/* PY40Bの設定 */
	dp.PY40B = MM_TO_DP(y + 2955);

	/* Ｐ／Ａビット設定 */
	if (mwaz <= 9000 && mwaz >= -9000) {
		/* ±９０゜範囲内 */
		SET_PABIT(PA_K044A);	/* 外周円と方向線 */
		SET_PABIT(PA_K042A);	/* 機体シルエット */
		SET_PABIT(PA_K040A);	/* ミサイルの円 */
		SET_PABIT(PA_K040B);	/* ５本の線 */
	} else {
		/* ±９０゜範囲外 */
		SET_PABIT(PA_K048A);	/* 外周円と方向線 */
		SET_PABIT(PA_K047A);	/* 機体シルエット */
		SET_PABIT(PA_K045A);	/* ミサイルの円 */
		SET_PABIT(PA_K045B);	/* ５本の線 */
	}
}

/*=======================================================================
 |
 |		機体上方からのシンボル
 |
 |	void	dp_missile_s2()
 |
 =======================================================================*/
static	void	dp_missile_s2(short mwaz, short mwel)
{
	short	tmp_mwaz, tmp_mwel, x, y, L2;
	long	L1_2;
	ushort	theta, alpha;

	if (mwaz == -18001 || mwel == -9001) {
		/* データエラーの場合は外周円と機体シルエットのみ表示 */
		SET_PABIT(PA_K044B);	/* 外周円と方向線 */
		SET_PABIT(PA_K042B);	/* 機体シルエット */
		return;
	}

	tmp_mwaz = ROUND(mwaz, 10);
	tmp_mwel = ROUND(mwel, 10);
	x = (short)((((2790L * sin(tmp_mwaz)) >> 14) * cos(tmp_mwel)) >> 14);
	y = (short)((((2790L * cos(tmp_mwel)) >> 14) * cos(tmp_mwaz)) >> 14);

	/* L1*L1 の計算 */
	L1_2 = (long)x * (long)x + (long)y * (long)y;

	/* L2 の計算 */
	if (L1_2 > 41006L)
		L2 = sqrt(L1_2 - 41006L);
	else
		L2 = 0;

	/* L41A の設定 */
	dp.L41A = MM_TO_DP(L2);

	/* θの計算 */
	theta = atan(y, x);

	/* α／２の計算 */
	alpha = atan(405 / 2, L2);

	/* D41A0～D41A4 の設定 */
	dp.D41A0 = theta + alpha;
	dp.D41A1 = theta + alpha / 2;
	dp.D41A2 = theta;
	dp.D41A3 = theta - alpha / 2;
	dp.D41A4 = theta - alpha;

	/* PX41Bの設定 */
	dp.PX41B = MM_TO_DP(x);

	/* PY41Bの設定 */
	dp.PY41B = MM_TO_DP(y - 2955);

	/* Ｐ／Ａビット設定 */
	if (mwel >= 0) {
		/* ０゜以上 */
		SET_PABIT(PA_K044B);	/* 外周円と方向線 */
		SET_PABIT(PA_K042B);	/* 機体シルエット */
		SET_PABIT(PA_K041A);	/* ミサイルの円 */
		SET_PABIT(PA_K041B);	/* ５本の線 */
	} else {
		/* ０゜未満 */
		SET_PABIT(PA_K048B);	/* 外周円と方向線 */
		SET_PABIT(PA_K047B);	/* 機体シルエット */
		SET_PABIT(PA_K046A);	/* ミサイルの円 */
		SET_PABIT(PA_K046B);	/* ５本の線 */
	}
}

/*=======================================================================
 |
 |		デジタル数値＆レジェンド
 |
 |	void	dp_missile_digital()
 |
 =======================================================================*/
static	void	dp_missile_digital(short mwaz, short mwel)
{
	static	ushort	legend_lost[7] = {
		FONT8_L, FONT8_O, FONT8_S, FONT8_T,
		FONT8_SP, FONT8_SP, FONT8_SP
	};
	short	tmp;

	/* レジェンド(A4310～A4316)設定 */
	if ((input_data[29] & 0x00ff) == 1) {
		COPY_WORD(&dp.A431[0], legend_lost, 7);
	} else {
		SET_WORD(&dp.A431[0], FONT8_SP, 7);
	}

	/* エレベーション値(A4320～A4325)設定 */
	dp.A432[0] = FONT8_E;
	dp.A432[1] = FONT8_L;
	if (mwel != -9001) {
		if (digital_update_flag) {
			tmp = ROUND(mwel, 100);	/* エレベーション値整数化 */
			if (tmp > 0)
				dp.A432[2] = FONT8_PLUS;
			else if (tmp < 0) {
				dp.A432[2] = FONT8_MINUS;
				tmp = -tmp;
			} else
				dp.A432[2] = FONT8_SP;
			tonumR(tmp, &dp.A432[3], 3, font8_tbl, FONT8_SP);
		}
	} else {
		SET_WORD(&dp.A432[3], FONT8_SP, 3);
	}

	/* アジマス値(A4330～A4335)設定 */
	dp.A433[0] = FONT8_A;
	dp.A433[1] = FONT8_Z;
	if (mwaz != -18001) {
		if (digital_update_flag) {
			tmp = ROUND(mwaz, 100);	/* アジマス値整数化 */
			if (tmp > 0)
				dp.A433[2] = FONT8_R;
			else if (tmp < 0) {
				dp.A433[2] = FONT8_L;
				tmp = -tmp;
			} else
				dp.A433[2] = FONT8_SP;
			tonumR(tmp, &dp.A433[3], 3, font8_tbl, FONT8_SP);
		}
	} else {
		SET_WORD(&dp.A433[3], FONT8_SP, 3);
	}

	/* Ｐ／Ａビット設定 */
	SET_PABIT(PA_KD43);
}
