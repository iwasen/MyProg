/***********	dp_calc.c	***********/
	{1, 10},		/* 356 デジタル値の更新レート */
	{-422, 423},		/* 357 ミサイルシンボル・オフセット量(Ｘ座標)*/
	{-422, 423}		/* 358 ミサイルシンボル・オフセット量(Ｙ座標)*/

int	data_check(int dno)
{
	int	error, n;
	short	high, low;

	if (dno == 27) {
		/* ウォーニング１，２ */
		high = (input_data[27] >> 8) & 0x00ff;
		low = input_data[27] & 0x00ff;
		error = (CHECK(high, 0, 1) || CHECK(low, 0, 1));
	} else if (dno == 28) {
		/* ウォーニング３，４ */
		high = (input_data[28] >> 8) & 0x00ff;
		low = input_data[28] & 0x00ff;
		error = (CHECK(high, 0, 1) || CHECK(low, 0, 1));
	} else if (dno == 29) {
		/* ミサイルシンボルの種類，ミサイル探知ステータス */
		high = (input_data[29] >> 8) & 0x00ff;
		low = input_data[29] & 0x00ff;
		error = (CHECK(high, 0, 3) || CHECK(low, 0, 1));
	}else if (dno <= 45) {
		/* ２７，２８，２９を除く番号０～４５までのデータ */
		error = (CHECK(input_data[dno], data_check_tbl1[dno][0],
						data_check_tbl1[dno][1]));
	} else if (dno <= 345) {
		/* 障害物データ（番号４６～３４５） */
		n = (dno - 46) % 3;
		error = (CHECK(input_data[dno], data_check_tbl2[n][0],
						data_check_tbl2[n][1]));
*	} else if (dno <= 358) {
*		/* 番号３４６～３５８までのデータ */
		n = dno - 346;
		error = (CHECK(input_data[dno], data_check_tbl3[n][0],
						data_check_tbl3[n][1]));
	} else
		error = 0;

	if (error)
		data_error_flag = 1;	/* データエラーフラグセット */

	return(error);
}
/***********	inpdata.c	***********/
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
