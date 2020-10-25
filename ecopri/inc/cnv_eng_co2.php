<?
/******************************************************
' System :Eco-footprint 共通
' Content:CO2換算
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
function cnv_ele_co2($use, $boiler_type = 0) {
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

	// 換算値取得
	$cnv_val = get_cnv_val($eng_type);

	// CO2排出量を計算
	return $use * $cnv_val * 0.86;
}

// ガス[ｍ3]
function cnv_gas_co2($use) {
	// ガス種別取得
	$sql = "SELECT mb_gas_kind_cd FROM t_member WHERE mb_seq_no={$_SESSION['ss_seq_no']}";
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

	// 換算値取得
	$cnv_val = get_cnv_val($eng_type);

	// CO2排出量を計算
	switch ($gas_kind_cd) {
	case GAS_KIND_1:
		return $use * $cnv_val * gas_calorie();
	case GAS_KIND_2:
		return $use * $cnv_val * 26.34;
	}
}

// 水道[ｍ3]
function cnv_wtr_co2($use) {
	// 換算値取得
	$cnv_val = get_cnv_val(ENG_TYPE_5);

	// CO2排出量を計算
	return $use * $cnv_val;
}

// 灯油[L]
function cnv_oil_co2($use, $boiler_type = '') {
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

	// 換算値取得
	$cnv_val = get_cnv_val($eng_type);

	// CO2排出量を計算
	return $use * $cnv_val * 8.9;
}

// ガソリン[L]
function cnv_gso_co2($use, $car_type_cd = '') {
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

	// 換算値取得
	$cnv_val_9 = get_cnv_val(ENG_TYPE_9);
	$cnv_val_10 = get_cnv_val(ENG_TYPE_10);

	// CO2排出量を計算
	return $use * ($cnv_val_9 * $eng_9 * 8.4 + $cnv_val_10 * $eng_10 * 9.2);
}

// ゴミ[kg]
function cnv_dst_co2($use) {

	// 換算値取得
	$cnv_val = get_cnv_val(ENG_TYPE_8);

	// CO2排出量を計算
	return $use * $cnv_val;
}

// 換算値取得
function get_cnv_val($eng_type) {
	$sql = "SELECT co_co2_rate"
			. " FROM m_co2_rate"
			. " WHERE co_eng_type_cd=$eng_type";
	return db_fetch1($sql);
}
?>