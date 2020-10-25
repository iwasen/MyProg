<?
/******************************************************
' System :Eco-footprint 共通
' Content:料金換算
'******************************************************/

// エネルギー分類コード
define('ENG_TYPE_1', 1);		// 電気（一般電灯）
define('ENG_TYPE_2', 2);		// 電気(深夜電灯）
define('ENG_TYPE_3', 3);		// 都市ガス
define('ENG_TYPE_4', 4);		// ＬＰガス
define('ENG_TYPE_5', 5);		// 水道
define('ENG_TYPE_6', 6);		// 灯油（給湯アリ）
define('ENG_TYPE_7', 7);		// 灯油(給湯ナシ）
define('ENG_TYPE_8', 8);		// ゴミ
define('ENG_TYPE_9', 9);		// ガソリン
define('ENG_TYPE_10', 10);	// 軽油

// 給湯器タイプコード
define('BOILER_TYPE_1', 1);
define('BOILER_TYPE_2', 2);

// ガス種別コード
define('GAS_KIND_1', 1);
define('GAS_KIND_2', 2);

// 車種コード
define('CAR_TYPE_1', 1);
define('CAR_TYPE_2', 2);

// 電気[kWh]
function cnv_ele_mny($use, $boiler_type = 0, $mb_seq_no = 0) {
	// 給湯器タイプ取得
	if ($boiler_type == -1) {
		if ($mb_seq_no == 0)
			$mb_seq_no = $_SESSION['ss_seq_no'];
		$sql = "SELECT mb_boiler_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
		$boiler_type = db_fetch1($sql);
	}

	// エネルギー分類取得
	switch ($boiler_type) {
	case BOILER_TYPE_1:
	case BOILER_TYPE_2:
		$eng_type = ENG_TYPE_2;
		break;
	default:
		$eng_type = ENG_TYPE_1;
		break;
	}

	// 単価取得
	$price_unit = get_price_unit($eng_type, $mb_seq_no);

	// 料金を計算
	return $use * $price_unit;
}

// ガス[ｍ3]
function cnv_gas_mny($use, $mb_seq_no = 0) {
	// 会員番号取得
	if ($mb_seq_no == 0)
		$mb_seq_no = $_SESSION['ss_seq_no'];

	// ガス種別取得
	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no=$mb_seq_no";
	$gas_kind_cd = db_fetch1($sql);

	// エネルギー分類取得
	switch ($gas_kind_cd) {
	case GAS_KIND_1:
		$eng_type = ENG_TYPE_3;
		break;
	case GAS_KIND_2:
		$eng_type = ENG_TYPE_4;
		break;
	default:
		return 0;
	}

	// 単価取得
	$price_unit = get_price_unit($eng_type, $mb_seq_no);

	// 料金を計算
	switch ($gas_kind_cd) {
	case GAS_KIND_1:
		return $use * $price_unit * gas_calorie($mb_seq_no);
	case GAS_KIND_2:
		return $use * $price_unit;
	}
}

// 水道[ｍ3]
function cnv_wtr_mny($use, $mb_seq_no = 0) {
	// 単価取得
	$price_unit = get_price_unit(ENG_TYPE_5, $mb_seq_no);

	// 料金を計算
	return $use * $price_unit;
}

// 灯油[L]
function cnv_oil_mny($use, $boiler_type = '') {
	// エネルギー分類取得
	if ($boiler_type) {
		switch ($boiler_type) {
		case 3:
			$eng_type = ENG_TYPE_6;
			break;
		default:
			$eng_type = ENG_TYPE_7;
			break;
		}
	} else {
		$sql = "SELECT mb_boiler_cd FROM t_member WHERE mb_seq_no={$_SESSION['ss_seq_no']}";
		switch (db_fetch1($sql)) {
		case 5:		// 石油式給湯器
			$eng_type = ENG_TYPE_6;
			break;
		default:
			$eng_type = ENG_TYPE_7;
			break;
		}
	}

	// 単価取得
	$price_unit = get_price_unit($eng_type);

	// 料金を計算
	return $use * $price_unit;
}

// ガソリン[L]
function cnv_gso_mny($use, $car_type_cd = '') {
	if ($car_type_cd) {
		// エネルギー分類取得
		switch ($car_type_cd) {
		case CAR_TYPE_2:
			$eng_9 = 0;
			$eng_10 = 1;
			break;
		default:
			$eng_9 = 1;
			$eng_10 = 0;
			break;
		}
	} else {
		$sql = "SELECT mb_gcar_num,mb_dcar_num FROM t_member WHERE mb_seq_no={$_SESSION['ss_seq_no']}";
		$result = db_exec($sql);
		if (pg_numrows($result)) {
			$fetch = pg_fetch_object($result, 0);
			$car_num = $fetch->mb_gcar_num + $fetch->mb_dcar_num;
			if ($car_num) {
				$eng_9 = $fetch->mb_gcar_num / $car_num;
				$eng_10 = $fetch->mb_dcar_num / $car_num;
			} else {
				$eng_9 = 1;
				$eng_10 = 0;
			}
		}
	}

	// 単価取得
	$price_unit_9 = get_price_unit(ENG_TYPE_9);
	$price_unit_10 = get_price_unit(ENG_TYPE_10);

	// 料金を計算
	return $use * ($price_unit_9 * $eng_9 + $price_unit_10 * $eng_10);
}

// 単価取得[\]
function get_price_unit($eng_type, $mb_seq_no = 0) {
	if ($mb_seq_no == 0)
		$mb_seq_no = $_SESSION['ss_seq_no'];

	$sql = "SELECT pr_price_unit"
			. " FROM t_member"
			. " JOIN m_area ON mb_area_cd=ar_area_cd"
			. " JOIN m_price_rate ON ar_area2_cd=pr_area2_cd"
			. " WHERE pr_eng_type_cd=$eng_type AND mb_seq_no=$mb_seq_no";
	return db_fetch1($sql);
}
?>